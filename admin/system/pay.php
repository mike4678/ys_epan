<?php
//*******************************************************************
// 支付管理（admin 后台）
// 路由入口: /admin/index.php?/system/pay
//   （zd_adminlist: t_e=system,m_e=pay 的 page 指向本文件）
// 数据源: epan_orders（充值/支付订单表）
//   列: order_id, pay_method(alipay/tenpay/wx/zx/yhzz), userid,
//       order_number, total_fee, pay_status, retcode, in_time(时间戳), ip
//   LEFT JOIN epan_users u ON u.userid = o.userid 取用户名
// 功能:
//   ① 列表：订单号/用户名/支付方式/金额/状态/下单时间/IP
//   ② 筛选：用户名关键字、支付方式下拉、订单状态下拉、起止日期
//   ③ 分页
//   ④ POST：删除订单；标记状态（待支付/成功/失败）
// 说明: 本文件由 admin/index.php include（$db=AdminSystem 已就绪、会话已开启、
//       CSRF token 由全局 ob_start 自动注入 <form>）。
//       本页只读 epan_orders；消费记录表 epan_xiaofei 属空间升级/延期扣费，
//       与支付订单是两类数据，不在本页合并。
//*******************************************************************
if (!defined('source')) { header("Location: ../login.php"); exit; }

function pay_flash($msg) { $_SESSION['__pay_msg'] = $msg; }
function pay_get_flash() { $m = isset($_SESSION['__pay_msg']) ? $_SESSION['__pay_msg'] : ''; unset($_SESSION['__pay_msg']); return $m; }

// ===== epan_settings 读写（与 admin/system/system.php 同一套键值表）=====
if (!function_exists('epan_set')) {
    function epan_set($db, $k, $v) {
        $k = (string)$k;
        $v = (string)$v;
        $sql = "INSERT INTO epan_settings (vars, value) VALUES ('".$db->escape($k)."', '".$db->escape($v)."')
                ON DUPLICATE KEY UPDATE value = VALUES(value)";
        return $db->query($sql);
    }
}
if (!function_exists('epan_get')) {
    function epan_get($db, $k) {
        $res = $db->query("SELECT value FROM epan_settings WHERE vars='".$db->escape((string)$k)."' LIMIT 1");
        if (!$res) return '';
        $row = $db->fetch_array($res);
        return $row ? (string)$row['value'] : '';
    }
}

//---------------- POST：删除 / 标记状态 / 保存充值设置 ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $do = isset($_POST['do']) ? $_POST['do'] : '';

    if ($do === 'pay_settings') {
        // 充值设置落库 epan_settings（键名/语义与原 admin/system/system.php 的 tab-pay 完全一致）
        $payKeys = array('pay_alipay','pay_alipay_key','pay_tenpay','pay_tenpay_key','pay_wx','pay_wx_key','pay_zx','pay_zx_key','pay_yhzz','pay_yhzz_bank','pay_yhzz_account','pay_yhzz_name');
        foreach ($payKeys as $pk) {
            epan_set($db, $pk, isset($_POST[$pk]) ? $_POST[$pk] : '');
        }
        pay_flash('充值设置已保存');
        setcookie('paytab', 'settings', time()+300, '/');
        header('Location: index.php?/system/pay');
        exit;
    }

    if ($do === 'del') {
        $ids = array();
        if (!empty($_POST['order_id'])) $ids[] = (int)$_POST['order_id'];
        if (!empty($_POST['order_ids']) && is_array($_POST['order_ids'])) {
            foreach ($_POST['order_ids'] as $v) $ids[] = (int)$v;
        }
        $ids = array_values(array_unique(array_filter($ids)));
        $delRow = 0;
        foreach ($ids as $oid) {
            $oid = (int)$oid;
            $d = $db->query("DELETE FROM epan_orders WHERE order_id=" . $oid . " LIMIT 1");
            if ($d && $db->affected_rows() >= 1) $delRow++;
        }
        pay_flash("已删除 {$delRow} 条支付订单");
    } elseif ($do === 'status') {
        $oid = (int)(isset($_POST['order_id']) ? $_POST['order_id'] : 0);
        $st  = isset($_POST['pay_status']) ? trim((string)$_POST['pay_status']) : '';
        $allowed = array('WAIT','Y','F','success','fail','');
        if ($oid > 0 && in_array($st, $allowed, true)) {
            $db->query("UPDATE epan_orders SET pay_status='" . $db->escape($st) . "' WHERE order_id=" . $oid . " LIMIT 1");
            pay_flash('已更新订单 #' . $oid . ' 状态为「' . ($st === '' ? '待支付' : $st) . '」');
        } else {
            pay_flash('状态更新被拒绝：非法状态值');
        }
    }

    // 回跳本页（保留筛选）；订单类操作后保持「支付管理」tab
    setcookie('paytab', 'orders', time()+300, '/');
    $back = 'index.php?/system/pay';
    $qs = array();
    foreach (array('user','method','status','date_from','date_to','page') as $k) {
        if (!empty($_POST['back_' . $k])) $qs[$k] = $_POST['back_' . $k];
    }
    if ($qs) $back .= '&' . http_build_query($qs);
    header('Location: ' . $back);
    exit;
}

//---------------- GET：筛选参数 ----------------
$f_user    = isset($_GET['user'])    ? trim((string)$_GET['user'])    : '';
$f_method  = isset($_GET['method'])  ? trim((string)$_GET['method'])  : '';
$f_status  = isset($_GET['status'])  ? trim((string)$_GET['status'])  : '';
$f_date_from = isset($_GET['date_from']) ? trim((string)$_GET['date_from']) : '';
$f_date_to   = isset($_GET['date_to'])   ? trim((string)$_GET['date_to'])   : '';
$page      = isset($_GET['page'])    ? max(1, (int)$_GET['page'])     : 1;
$pageSize  = 15;
$offset    = ($page - 1) * $pageSize;

$validMethods = array('alipay','tenpay','wx','zx','yhzz');
// 状态筛选：空=全部；Y/success=成功；WAIT/''=待支付；F/fail=失败
$validStatus = array('WAIT','Y','F','success','fail','');

$where = " WHERE 1=1 ";
if ($f_user !== '')   $where .= " AND u.username LIKE '%" . $db->escape($f_user) . "%' ";
if (in_array($f_method, $validMethods, true)) $where .= " AND o.pay_method='" . $db->escape($f_method) . "' ";
// 状态：成功匹配 Y/success/SUCCESS；待支付匹配空串或 WAIT；失败匹配 F/fail
if ($f_status === 'ok')       $where .= " AND o.pay_status IN ('Y','1','success','SUCCESS') ";
elseif ($f_status === 'wait') $where .= " AND (o.pay_status='' OR o.pay_status='WAIT' OR o.pay_status IS NULL) ";
elseif ($f_status === 'fail') $where .= " AND o.pay_status IN ('F','fail','FAILED') ";

$tsFrom = $f_date_from ? strtotime($f_date_from . ' 00:00:00') : 0;
$tsTo   = $f_date_to   ? strtotime($f_date_to   . ' 23:59:59') : 0;
if ($tsFrom > 0) $where .= " AND o.in_time >= " . $tsFrom . " ";
if ($tsTo > 0)   $where .= " AND o.in_time <= " . $tsTo . " ";

// 总数
$cntRs = $db->query("SELECT COUNT(*) c FROM epan_orders o LEFT JOIN epan_users u ON u.userid=o.userid " . $where);
$cntRow = $cntRs ? $db->fetch_array($cntRs) : array('c' => 0);
$total = (int)$cntRow['c'];
$pageCount = max(1, (int)ceil($total / $pageSize));
if ($page > $pageCount) { $page = $pageCount; $offset = ($page - 1) * $pageSize; }

// 列表
$list = $db->query(
    "SELECT o.order_id, o.pay_method, o.userid, o.order_number, o.total_fee,
            o.pay_status, o.retcode, o.in_time, o.ip, u.username
     FROM epan_orders o
     LEFT JOIN epan_users u ON u.userid = o.userid
     " . $where . "
     ORDER BY o.order_id DESC
     LIMIT " . $offset . "," . $pageSize
);

$methodLabels = array('alipay'=>'支付宝','tenpay'=>'财付通','wx'=>'微信','zx'=>'网银在线','yhzz'=>'银行转账');

function pay_status_label($s) {
    $s = (string)$s;
    if (in_array($s, array('Y','1','success','SUCCESS'), true)) return array('成功','#43a047');
    if ($s === '' || $s === 'WAIT') return array('待支付','#fb8c00');
    if (in_array($s, array('F','fail','FAILED'), true)) return array('失败','#e53935');
    return array($s ? $s : '未知','#777');
}

function pay_qs($over = array()) {
    $p = array();
    foreach (array('user','method','status','date_from','date_to','page') as $k) {
        if (isset($_GET[$k]) && $_GET[$k] !== '') $p[$k] = $_GET[$k];
    }
    $p = array_merge($p, $over);
    return $p ? '&' . http_build_query($p) : '';
}
// ===== 两个 tab：支付管理(orders) / 设置(settings)，cookie paytab 记忆 =====
// GET 携带订单筛选/分页参数时，强制切回「支付管理」tab（筛选表单与分页链接均为 GET）
$__ordersCtx = isset($_GET['user']) || isset($_GET['method']) || isset($_GET['status'])
            || isset($_GET['date_from']) || isset($_GET['date_to']) || isset($_GET['page']);
$paytab = isset($_COOKIE['paytab']) ? $_COOKIE['paytab'] : 'orders';
if ($paytab !== 'settings') $paytab = 'orders';
if ($__ordersCtx && $paytab !== 'orders') {
    $paytab = 'orders';
    setcookie('paytab', 'orders', time()+300, '/');
}
$ordersActive   = ($paytab === 'orders')   ? 'active in' : '';
$settingsActive = ($paytab === 'settings') ? 'active in' : '';
$ordersLi       = ($paytab === 'orders')   ? 'active' : '';
$settingsLi     = ($paytab === 'settings') ? 'active' : '';
?>
<?php $flash = pay_get_flash(); if ($flash): ?>
<div class="alert alert-success alert-dismissible" style="margin:8px;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="mdi mdi-check-circle-outline"></i> <?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?>
</div>
<?php endif; ?>

<ul class="nav nav-tabs" role="tablist" style="margin-bottom:0;">
    <li class="<?php echo $ordersLi; ?>" role="presentation">
        <a href="#pane-orders" data-toggle="tab" onclick="document.cookie='paytab=orders;path=/;max-age=300';">
            <i class="mdi mdi-cash-multiple" style="color:#5e72e4;"></i> 支付管理
        </a>
    </li>
    <li class="<?php echo $settingsLi; ?>" role="presentation">
        <a href="#pane-settings" data-toggle="tab" onclick="document.cookie='paytab=settings;path=/;max-age=300';">
            <i class="mdi mdi-wallet" style="color:#2dce89;"></i> 设置
        </a>
    </li>
</ul>

<div class="tab-content" id="payTabContent">
  <!-- ===== pane: 支付管理（订单列表） ===== -->
  <div class="tab-pane fade <?php echo $ordersActive; ?>" id="pane-orders">
<div class="panel admin-panel">
    <div class="panel-head"><strong><i class="mdi mdi-cash-multiple" style="color:#5e72e4;"></i> 支付管理</strong><span class="small" style="color:#888">（共 <?php echo $total; ?> 条充值订单 · 数据源 epan_orders）</span></div>

    <!-- 筛选栏 -->
    <form method="get" action="index.php" class="padding border-bottom" style="margin-bottom:0;">
        <input type="hidden" name="/system/pay" value="">
        <label>用户名：
            <input type="text" name="user" class="input" style="height:30px;width:120px;" value="<?php echo htmlspecialchars($f_user, ENT_QUOTES, 'UTF-8'); ?>" placeholder="用户名关键字">
        </label>
        <label>支付方式：
            <select name="method" class="input" style="height:30px;">
                <option value="">全部方式</option>
                <?php foreach ($methodLabels as $mk => $ml): ?>
                    <option value="<?php echo $mk; ?>" <?php if ($f_method===$mk) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($ml.' ('.$mk.')', ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>状态：
            <select name="status" class="input" style="height:30px;">
                <option value="">全部状态</option>
                <option value="ok"   <?php if ($f_status==='ok')   echo 'selected'; ?>>成功</option>
                <option value="wait" <?php if ($f_status==='wait') echo 'selected'; ?>>待支付</option>
                <option value="fail" <?php if ($f_status==='fail') echo 'selected'; ?>>失败</option>
            </select>
        </label>
        <label>起始：
            <input type="date" name="date_from" class="input" style="height:30px;" value="<?php echo htmlspecialchars($f_date_from, ENT_QUOTES, 'UTF-8'); ?>">
        </label>
        <label>截止：
            <input type="date" name="date_to" class="input" style="height:30px;" value="<?php echo htmlspecialchars($f_date_to, ENT_QUOTES, 'UTF-8'); ?>">
        </label>
        <input type="submit" class="button button-small border-blue" value="筛选">
        <a class="button button-small" href="index.php?/system/pay">重置</a>
    </form>

    <form method="post" action="index.php?/system/pay">
        <?php
        foreach (array('user'=>$f_user,'method'=>$f_method,'status'=>$f_status,'date_from'=>$f_date_from,'date_to'=>$f_date_to,'page'=>$page) as $bk=>$bv) {
            echo '<input type="hidden" name="back_' . $bk . '" value="' . htmlspecialchars((string)$bv, ENT_QUOTES, 'UTF-8') . '">';
        }
        ?>
        <table class="table table-striped table-hover">
            <tr>
                <th width="3%"><input type="checkbox" id="pay_checkall"></th>
                <th width="6%">ID</th>
                <th width="14%">订单号</th>
                <th width="10%">用户名</th>
                <th width="9%">方式</th>
                <th width="8%">金额(元)</th>
                <th width="8%">状态</th>
                <th width="15%">下单时间</th>
                <th width="9%">IP</th>
                <th width="18%">操作</th>
            </tr>
            <?php if (!$list || mysqli_num_rows($list) < 1): ?>
            <tr><td colspan="10" style="text-align:center;color:#999;padding:40px 0;"><i class="mdi mdi-currency-usd" style="font-size:32px;display:block;margin-bottom:8px;"></i>没有符合条件的支付订单</td></tr>
            <?php else: while ($row = $db->fetch_array($list)):
                list($stText, $stColor) = pay_status_label($row['pay_status']);
                $ml = isset($methodLabels[$row['pay_method']]) ? $methodLabels[$row['pay_method']] : $row['pay_method'];
            ?>
            <tr>
                <td><input type="checkbox" name="order_ids[]" value="<?php echo (int)$row['order_id']; ?>"></td>
                <td><?php echo (int)$row['order_id']; ?></td>
                <td style="word-break:break-all;"><?php echo htmlspecialchars($row['order_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['username'] ? $row['username'] : ('uid='.$row['userid']), ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($ml, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo number_format((float)$row['total_fee'], 2); ?></td>
                <td><span class="badge" style="background:<?php echo $stColor; ?>;color:#fff;"><?php echo htmlspecialchars($stText, ENT_QUOTES, 'UTF-8'); ?></span></td>
                <td><?php echo $row['in_time'] ? date('Y-m-d H:i:s', (int)$row['in_time']) : '-'; ?></td>
                <td><?php echo htmlspecialchars($row['ip'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <select name="pay_status" class="input" style="height:26px;font-size:12px;display:inline-block;width:80px;">
                        <option value="WAIT" <?php if ($row['pay_status']===''||$row['pay_status']==='WAIT') echo 'selected'; ?>>待支付</option>
                        <option value="Y"     <?php if ($row['pay_status']==='Y') echo 'selected'; ?>>成功</option>
                        <option value="F"     <?php if ($row['pay_status']==='F') echo 'selected'; ?>>失败</option>
                    </select>
                    <button type="submit" class="button button-small border-blue" name="do" value="status"
                        onclick="this.form.elements['order_id'].value=<?php echo (int)$row['order_id']; ?>;">改状态</button>
                    <button type="submit" class="button button-small border-red" name="do" value="del"
                        onclick="this.form.elements['order_id'].value=<?php echo (int)$row['order_id']; ?>; return confirm('确认删除该条支付订单 #<?php echo (int)$row['order_id']; ?>？');">删</button>
                    <input type="hidden" name="order_id" value="0">
                </td>
            </tr>
            <?php endwhile; endif; ?>
        </table>

        <div class="panel-foot">
            <button type="submit" class="button button-small border-red" name="do" value="del"
                onclick="return confirm('确认删除选中的支付订单？');">批量删除</button>
            <span style="color:#999;font-size:12px;margin-left:10px;">提示：删除仅移除订单记录，不回退用户余额（epan_users.wealth）；如需退款请另行人工处理。</span>
        </div>
    </form>

    <!-- 分页 -->
    <div class="panel-foot text-center">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li><a href="index.php?/system/pay<?php echo pay_qs(array('page'=>$page-1)); ?>">上一页</a></li>
            <?php else: ?><li><a>上一页</a></li><?php endif; ?>
            <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <li class="<?php if ($i===$page) echo 'active'; ?>"><a href="index.php?/system/pay<?php echo pay_qs(array('page'=>$i)); ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($page < $pageCount): ?>
                <li><a href="index.php?/system/pay<?php echo pay_qs(array('page'=>$page+1)); ?>">下一页</a></li>
            <?php else: ?><li><a>下一页</a></li><?php endif; ?>
        </ul>
    </div>
</div>
  </div><!-- /pane-orders -->

  <!-- ===== pane: 设置（充值设置） ===== -->
  <div class="tab-pane fade <?php echo $settingsActive; ?>" id="pane-settings">
<!-- ==================== 充值设置（由原 system.php tab-pay 迁移而来） ==================== -->
<div class="panel admin-panel">
    <div class="panel-head"><strong><i class="mdi mdi-wallet" style="color:#2dce89;"></i> 充值设置</strong><span class="small" style="color:#888">（写入 epan_settings 的 pay_* 键；用户端「账户充值」页据此显示可用充值方式）</span></div>

    <div style="padding:14px;">
        <p style="color:#888;font-size:13px;margin:0 0 10px;">充值方式需【启用】且填写对应 key 后，才会在用户端「账户充值」页显示；未启用或 key 为空则不显示。</p>
        <form method="post" action="index.php?/system/pay" class="form-x">
            <input type="hidden" name="do" value="pay_settings">
            <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="form-group"><label>支付宝充值</label>
                    <div class="radio info"><input type="radio" name="pay_alipay" value="1" id="pay_alipay_on" <?php echo (epan_get($db, 'pay_alipay')=='1')?'checked':''; ?>><label for="pay_alipay_on">启用</label></div>
                    <div class="radio info"><input type="radio" name="pay_alipay" value="0" id="pay_alipay_off" <?php echo (epan_get($db, 'pay_alipay')!='1')?'checked':''; ?>><label for="pay_alipay_off">关闭</label></div>
                </div>
                <div class="form-group"><label for="pay_alipay_key">支付宝合作key</label><input type="text" class="form-control" name="pay_alipay_key" size="50" value="<?php echo htmlspecialchars(epan_get($db, 'pay_alipay_key'), ENT_QUOTES, 'UTF-8'); ?>"/></div>

                <div class="form-group"><label>财付通充值</label>
                    <div class="radio info"><input type="radio" name="pay_tenpay" value="1" id="pay_tenpay_on" <?php echo (epan_get($db, 'pay_tenpay')=='1')?'checked':''; ?>><label for="pay_tenpay_on">启用</label></div>
                    <div class="radio info"><input type="radio" name="pay_tenpay" value="0" id="pay_tenpay_off" <?php echo (epan_get($db, 'pay_tenpay')!='1')?'checked':''; ?>><label for="pay_tenpay_off">关闭</label></div>
                </div>
                <div class="form-group"><label for="pay_tenpay_key">财付通key</label><input type="text" class="form-control" name="pay_tenpay_key" size="50" value="<?php echo htmlspecialchars(epan_get($db, 'pay_tenpay_key'), ENT_QUOTES, 'UTF-8'); ?>"/></div>

                <div class="form-group"><label>微信充值</label>
                    <div class="radio info"><input type="radio" name="pay_wx" value="1" id="pay_wx_on" <?php echo (epan_get($db, 'pay_wx')=='1')?'checked':''; ?>><label for="pay_wx_on">启用</label></div>
                    <div class="radio info"><input type="radio" name="pay_wx" value="0" id="pay_wx_off" <?php echo (epan_get($db, 'pay_wx')!='1')?'checked':''; ?>><label for="pay_wx_off">关闭</label></div>
                </div>
                <div class="form-group"><label for="pay_wx_key">微信key</label><input type="text" class="form-control" name="pay_wx_key" size="50" value="<?php echo htmlspecialchars(epan_get($db, 'pay_wx_key'), ENT_QUOTES, 'UTF-8'); ?>"/></div>

                <div class="form-group"><label>网银在线充值</label>
                    <div class="radio info"><input type="radio" name="pay_zx" value="1" id="pay_zx_on" <?php echo (epan_get($db, 'pay_zx')=='1')?'checked':''; ?>><label for="pay_zx_on">启用</label></div>
                    <div class="radio info"><input type="radio" name="pay_zx" value="0" id="pay_zx_off" <?php echo (epan_get($db, 'pay_zx')!='1')?'checked':''; ?>><label for="pay_zx_off">关闭</label></div>
                </div>
                <div class="form-group"><label for="pay_zx_key">网银在线key</label><input type="text" class="form-control" name="pay_zx_key" size="50" value="<?php echo htmlspecialchars(epan_get($db, 'pay_zx_key'), ENT_QUOTES, 'UTF-8'); ?>"/></div>

                <div class="form-group"><label>银行转账</label>
                    <div class="radio info"><input type="radio" name="pay_yhzz" value="1" id="pay_yhzz_on" <?php echo (epan_get($db, 'pay_yhzz')=='1')?'checked':''; ?>><label for="pay_yhzz_on">启用</label></div>
                    <div class="radio info"><input type="radio" name="pay_yhzz" value="0" id="pay_yhzz_off" <?php echo (epan_get($db, 'pay_yhzz')!='1')?'checked':''; ?>><label for="pay_yhzz_off">关闭</label></div>
                </div>
                <div class="form-group"><label for="pay_yhzz_bank">开户行</label><input type="text" class="form-control" name="pay_yhzz_bank" size="50" value="<?php echo htmlspecialchars(epan_get($db, 'pay_yhzz_bank'), ENT_QUOTES, 'UTF-8'); ?>"/></div>
                <div class="form-group"><label for="pay_yhzz_account">银行账号</label><input type="text" class="form-control" name="pay_yhzz_account" size="50" value="<?php echo htmlspecialchars(epan_get($db, 'pay_yhzz_account'), ENT_QUOTES, 'UTF-8'); ?>"/></div>
                <div class="form-group"><label for="pay_yhzz_name">户名</label><input type="text" class="form-control" name="pay_yhzz_name" size="50" value="<?php echo htmlspecialchars(epan_get($db, 'pay_yhzz_name'), ENT_QUOTES, 'UTF-8'); ?>"/></div>

                <div><button class="btn btn-primary btn-sm waves-effect waves-light" type="submit">保存充值设置</button></div>
            </div>
            </div>
        </form>
    </div>
</div>
  </div><!-- /pane-settings -->
</div><!-- /tab-content -->

<script>
(function(){
    var all = document.getElementById('pay_checkall');
    if (all) all.onchange = function(){
        document.querySelectorAll('input[name="order_ids[]"]').forEach(function(c){ c.checked = all.checked; });
    };
})();
</script>
</div>
