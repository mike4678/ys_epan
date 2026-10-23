<?php
// ===== 银行转账（batch12）：读 pay_yhzz_* 后台配置；提交汇款记录 = 生成 WAIT 订单 =====
include_once dirname(__FILE__).'/../../includes/Init.php';
include_once dirname(__FILE__).'/pay_common.php';
include_once PHPDISK_ROOT.'includes/top.php';
if (!isset($top)) $top = '';
$uid = 2; $sid = 1;

$username = pay_require_login();
$myid = pay_userid($db, $username);

$yh_bank    = pay_cfg($db, 'pay_yhzz_bank');
$yh_account = pay_cfg($db, 'pay_yhzz_account');
$yh_name    = pay_cfg($db, 'pay_yhzz_name');
$msg = '';

// POST：提交汇款记录 → 生成 WAIT 订单
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do']) && $_POST['do'] === 'yhzz_submit') {
    if ($myid <= 0) {
        $msg = '<p style="color:red">无法定位用户，请重新登录。</p>';
    } else {
        $amount = (float)$_POST['yh_amount'];
        $memo   = isset($_POST['yh_memo']) ? trim((string)$_POST['yh_memo']) : '';
        if ($amount < 0.01) {
            $msg = '<p style="color:red">汇款金额必须大于 0.01 元</p>';
        } else {
            $orderNumber = pay_gen_order_number();
            $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            $db->query("insert into epan_orders (pay_method,userid,order_number,total_fee,pay_status,retcode,in_time,ip) values (
                'yhzz', ".(int)$myid.", '".$db->escape($orderNumber)."', "
                .number_format($amount, 2, '.', '').", 'WAIT', 0, "
                .time().", '".$db->escape($ip)."')");
            $msg = '<p style="color:#16a34a;">已提交汇款记录（订单号 '.htmlspecialchars($orderNumber, ENT_QUOTES,'UTF-8').'），请等待管理员确认到账后自动充入余额。<a href="/ht/index.php?method=jlcz">查看充值记录</a></p>';
        }
    }
}
?>
<div id="ysright">
<form name="ctl00" method="post" action="/ht/index.php?method=yhzz" id="ctl00">
<input type="hidden" name="do" value="yhzz_submit">
<h1><span id="yhztxs"><?php echo $db->LoginBar(); if ($_SESSION['username_epan'] != NULL) echo $top; ?></span><img border="0" src="/ht/images/money.gif" width="32" height="32" alt="" />银行转账支付</h1>

<h3>银行转账支付流程</h3>
<ul class="yslb2">
    <li>第一步：按下方收款账号转账</li>
    <li>第二步：填写并提交汇款记录</li>
    <li>第三步：<?php echo Website_title; ?> 工作人员核对到账</li>
    <li>第四步：到账后为您充入相应余额</li>
</ul>

<h3>收款账号（后台配置）</h3>
<table border="1" width="440" style="border-color:#808000;background:#f0fdf4;">
    <tr>
        <td class="tdbt" width="90">开户行</td>
        <td><b><?php echo htmlspecialchars($yh_bank ?: '（未配置）', ENT_QUOTES,'UTF-8'); ?></b></td>
    </tr>
    <tr>
        <td class="tdbt">户名</td>
        <td><b><?php echo htmlspecialchars($yh_name ?: '（未配置）', ENT_QUOTES,'UTF-8'); ?></b></td>
    </tr>
    <tr>
        <td class="tdbt">账号</td>
        <td><u><font size="4" color="#b91c1c"><?php echo htmlspecialchars($yh_account ?: '（未配置）', ENT_QUOTES,'UTF-8'); ?></font></u></td>
    </tr>
</table>

<h3>提交汇款记录</h3>
<?php echo $msg; ?>
<table style="width:440px;">
    <tr>
        <td class="tdbt" width="90">汇款金额</td>
        <td><input type="number" step="0.01" min="0.01" name="yh_amount" class="form-control" style="max-width:200px;" placeholder="例如 100.00" required> 元</td>
    </tr>
    <tr>
        <td class="tdbt">备注</td>
        <td><textarea name="yh_memo" rows="3" class="form-control" placeholder="请填写汇款时间/汇款人姓名，便于客服核对"></textarea></td>
    </tr>
</table>
<p style="margin-top:10px;"><button class="btn btn-primary" type="submit">提交汇款记录</button>
&nbsp;&nbsp;<a href="/ht/index.php?method=yfk">&laquo; 返回充值方式</a></p>
</form>
</div>
