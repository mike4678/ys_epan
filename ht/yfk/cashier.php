<?php
// ===== 收银台（batch12 收单链路）=====
// 路由：/ht/yfk/cashier.php?pm=wx|alipay|tenpay|zx
// 流程：选金额 → 下单（epan_orders WAIT）→ 显示订单详情 + 本地模拟扫码（local 模式）/ 真实平台跳转（wechat/alipay 模式）
include_once dirname(__FILE__).'/../../includes/Init.php';
include_once dirname(__FILE__).'/pay_common.php';
include_once PHPDISK_ROOT.'includes/top.php';
if (!isset($top)) $top = '';

$username = pay_require_login();
$myid = pay_userid($db, $username);
if ($myid <= 0) { echo '<div id="ysright"><p style="color:red">无法定位用户，请重新登录。</p></div>'; return; }

$pm = isset($_GET['pm']) ? preg_replace('/[^a-z]/i', '', $_GET['pm']) : '';
$pmMap = array('alipay'=>'支付宝','tenpay'=>'财付通','wx'=>'微信','zx'=>'网银在线');
if (!isset($pmMap[$pm])) {
    echo '<div id="ysright"><p style="color:red">无效的支付方式。<a href="/ht/index.php?method=yfk">返回充值</a></p></div>';
    return;
}

// 方式是否在后台开启
$onKey = 'pay_'.$pm;
if (pay_cfg($db, $onKey) !== '1') {
    echo '<div id="ysright"><p style="color:red">该支付方式未启用。<a href="/ht/index.php?method=yfk">返回充值</a></p></div>';
    return;
}

$gw = pay_gateway($db);
$msg = '';

// POST：下单
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do']) && $_POST['do'] === 'create_order') {
    $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
    if ($amount < 0.01) {
        $msg = '<p style="color:red">充值金额必须大于 0.01 元</p>';
    } else {
        $orderNumber = pay_gen_order_number();
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $sql = "insert into epan_orders (pay_method,userid,order_number,total_fee,pay_status,retcode,in_time,ip) values (
            '".$db->escape($pm)."', ".(int)$myid.", '".$db->escape($orderNumber)."', "
            .number_format($amount, 2, '.', '').", 'WAIT', 0, "
            .time().", '".$db->escape($ip)."')";
        $db->query($sql);
        $newId = $db->insert_id();
        // 跳转到本页 GET 展示该订单（避免 POST 刷新重复下单）
        header('Location: /ht/yfk/cashier.php?pm='.$pm.'&order='.(int)$newId);
        exit;
    }
}

// 读取指定订单（若 URL 带 order）
$order = null;
$oid = isset($_GET['order']) ? (int)$_GET['order'] : 0;
if ($oid > 0) {
    $order = $db->fetch_array($db->query("select * from epan_orders where order_id=".(int)$oid." and userid=".(int)$myid." limit 1"));
}
?>
<div id="ysright">
<h1><span id="yhztxs"><?php echo $db->LoginBar(); if ($_SESSION['username_epan'] != NULL) echo $top; ?></span>
<img border="0" src="/ht/images/money.gif" width="32" height="32" alt="" />收银台 - <?php echo htmlspecialchars($pmMap[$pm], ENT_QUOTES,'UTF-8'); ?></h1>

<?php echo $msg; ?>

<?php if (!$order): ?>
<h3>第一步：输入充值金额</h3>
<form method="post" action="/ht/yfk/cashier.php?pm=<?php echo $pm; ?>" class="form-x">
    <input type="hidden" name="do" value="create_order">
    <div class="form-group">
        <label>充值金额（元）</label>
        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" style="max-width:220px;" placeholder="例如 10.00" required>
    </div>
    <div><button class="btn btn-primary" type="submit">生成 <?php echo htmlspecialchars($pmMap[$pm], ENT_QUOTES,'UTF-8'); ?> 订单</button></div>
</form>
<p style="margin-top:14px;"><a href="/ht/index.php?method=yfk">&laquo; 返回充值方式选择</a></p>

<?php else:
    $stOk = in_array($order['pay_status'], array('Y','1','success','SUCCESS'), true);
    $sign = pay_sign($order['order_number'], $order['total_fee'], $gw['secret']);
?>
<h3>第二步：完成支付</h3>
<table class="ht-table" style="max-width:520px;">
    <tr><td class="tdbt">订单号</td><td><?php echo htmlspecialchars($order['order_number'], ENT_QUOTES,'UTF-8'); ?></td></tr>
    <tr><td class="tdbt">支付方式</td><td><?php echo htmlspecialchars($pmMap[$order['pay_method']], ENT_QUOTES,'UTF-8'); ?></td></tr>
    <tr><td class="tdbt">金额</td><td><b style="color:#e53935;font-size:18px;"><?php echo number_format((float)$order['total_fee'], 2); ?> 元</b></td></tr>
    <tr><td class="tdbt">状态</td><td><?php echo $stOk ? '<font color="#16a34a">已支付</font>' : '<font color="#fb8c00">待支付</font>'; ?></td></tr>
</table>

<?php if ($stOk): ?>
<div class="ht-warn" style="margin-top:14px;">
    <b>支付成功！</b> 余额已自动充入。<a href="/ht/index.php?method=jlcz">查看充值记录</a> · <a href="/ht/index.php?method=yfk">继续充值</a>
</div>
<?php else: ?>
<div style="margin-top:18px;padding:16px;border:1px dashed #2563eb;border-radius:10px;background:#f8fbff;max-width:520px;">
<?php if ($gw['mode'] === 'local'): ?>
    <p style="margin:0 0 8px;"><b>沙箱模式（local）</b>：本地无公网回调，模拟用户扫码后点击「我已完成支付」即可触发回调验签并到账。</p>
    <div style="text-align:center;margin:14px 0;">
        <div style="display:inline-block;width:180px;height:180px;background:#fff;border:1px solid #ddd;line-height:180px;color:#999;font-size:13px;">[ 模拟二维码 ]</div>
    </div>
    <p style="font-size:12px;color:#6b7280;">订单号 <?php echo htmlspecialchars($order['order_number'], ENT_QUOTES,'UTF-8'); ?> · 金额 <?php echo number_format((float)$order['total_fee'],2); ?> 元</p>
    <a class="btn btn-primary" href="/ht/yfk/notify.php?order_number=<?php echo urlencode($order['order_number']); ?>&total_fee=<?php echo urlencode(number_format((float)$order['total_fee'],2,'.','')); ?>&sign=<?php echo urlencode($sign); ?>">我已完成支付（沙箱回调）</a>
<?php else: ?>
    <p>当前网关模式：<b><?php echo htmlspecialchars($gw['mode'], ENT_QUOTES,'UTF-8'); ?></b>（商户号 <?php echo htmlspecialchars($gw['mchid']?:'未配置', ENT_QUOTES,'UTF-8'); ?>）。</p>
    <p style="color:#b45309;font-size:13px;">真实微信 Native / 支付宝当面付跳转代码已就位，但需在后台配置商户号、密钥与公网回调地址后方可联调。当前环境无公网回调，请到后台「支付管理 → 设置」将网关模式切回 <code>local</code> 完成本地闭环。</p>
    <a class="btn btn-primary" href="/ht/yfk/notify.php?order_number=<?php echo urlencode($order['order_number']); ?>&total_fee=<?php echo urlencode(number_format((float)$order['total_fee'],2,'.','')); ?>&sign=<?php echo urlencode($sign); ?>">模拟回调（本地测试）</a>
<?php endif; ?>
</div>
<p style="margin-top:14px;"><a href="/ht/yfk/cashier.php?pm=<?php echo $pm; ?>">重新发起一笔</a> · <a href="/ht/index.php?method=yfk">返回充值</a></p>
<?php endif; ?>
<?php endif; ?>
</div>
