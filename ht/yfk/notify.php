<?php
// ===== 收单回调 notify（batch12）=====
// 路由：/ht/yfk/notify.php?order_number=xxx&total_fee=xx&sign=xxx
// 真实平台会以 POST 回调此地址；本地沙箱以 GET 模拟。验签失败一律返回 fail 且不动库。
include_once dirname(__FILE__).'/../../includes/Init.php';
include_once dirname(__FILE__).'/pay_common.php';

// notify 不输出 HTML，纯文本结果
header('Content-Type: text/plain; charset=utf-8');

function notify_out($code, $msg) {
    echo $code . ' ' . $msg;
    exit;
}

$order_number = isset($_REQUEST['order_number']) ? trim((string)$_REQUEST['order_number']) : '';
$total_fee    = isset($_REQUEST['total_fee'])    ? (float)$_REQUEST['total_fee'] : 0;
$sign        = isset($_REQUEST['sign'])           ? trim((string)$_REQUEST['sign']) : '';

if ($order_number === '' || $sign === '') notify_out('fail', 'missing_params');

$gw = pay_gateway($db);
$expect = pay_sign($order_number, $total_fee, $gw['secret']);
if (!hash_equals($expect, $sign)) {
    notify_out('fail', 'bad_sign');
}

$orow = $db->fetch_array($db->query("select * from epan_orders where order_number='".$db->escape($order_number)."' limit 1"));
if (!$orow) notify_out('fail', 'order_not_found');

// 幂等：已成功直接返回 success
if (in_array($orow['pay_status'], array('Y','1','success','SUCCESS'), true)) {
    notify_out('success', 'already_paid');
}

// 金额比对（容差 0.005）
if (abs((float)$orow['total_fee'] - $total_fee) > 0.005) {
    notify_out('fail', 'amount_mismatch');
}

$db->query("update epan_orders set pay_status='Y', retcode=0 where order_id=".(int)$orow['order_id']." limit 1");

// 加余额（wealth 是 float）
$db->query("update epan_users set wealth = wealth + " . number_format((float)$orow['total_fee'], 2, '.', '') . " where userid=" . (int)$orow['userid'] . " limit 1");

// 写消费/充值流水 epan_xiaofei（je 为 int 分）
$feeFen = (int)round((float)$orow['total_fee'] * 100);
$db->query("insert into epan_xiaofei (userid, xfname, je, outtime, intime) values (
    ".(int)$orow['userid'].", '充值到账-".(int)$orow['order_id']."', ".$feeFen.", ".time().", ".time().")");

notify_out('success', 'ok');
