<?php
// ===== 收单链路公共库（batch12）=====
// 被 ht/yfk/index.php、cashier.php、notify.php、yhzz.php 共享。
// 配置全部落 epan_settings（与 admin/system/pay.php 的 pay_* 同一套表）。
if (!defined('PHPDISK_ROOT')) define('PHPDISK_ROOT', dirname(__FILE__).'/../../');

function pay_cfg($db, $var) {
    $var_esc = $db->escape($var);
    $r = $db->fetch_array($db->query("select value from epan_settings where vars='".$var_esc."' limit 1"));
    if (is_array($r) && array_key_exists('value', $r) && $r['value'] !== null && $r['value'] !== '') return trim($r['value']);
    $r2 = $db->fetch_array($db->query("select value from zd_system_setting where vars='".$var_esc."' limit 1"));
    return (is_array($r2) && array_key_exists('value', $r2) && $r2['value'] !== null) ? trim($r2['value']) : '';
}

// 网关配置（新增键，缺省值保证 local 沙箱可闭环）
function pay_gateway($db) {
    static $g = null;
    if ($g !== null) return $g;
    $mode = pay_cfg($db, 'pay_gateway_mode');
    if ($mode !== 'wechat' && $mode !== 'alipay') $mode = 'local'; // 缺省/未知 = local 沙箱
    $g = array(
        'mode'       => $mode,
        'mchid'      => pay_cfg($db, 'pay_gateway_mchid'),
        'secret'     => pay_cfg($db, 'pay_gateway_secret'),
        'notify_url' => pay_cfg($db, 'pay_gateway_notify_url'),
    );
    if ($g['secret'] === '') $g['secret'] = 'pan_local_secret_2026'; // local 沙箱默认密钥
    return $g;
}

// 订单号生成：YMHis + 随机6位
function pay_gen_order_number() {
    return date('YmdHis') . str_pad((string)mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// 签名：md5(order_number|total_fee|secret)，total_fee 保留2位
function pay_sign($order_number, $total_fee, $secret) {
    return md5($order_number . '|' . number_format((float)$total_fee, 2, '.', '') . '|' . $secret);
}

// 根据用户名取 userid（epan_users 主键列名是 userid，不是 id）
function pay_userid($db, $username) {
    $row = $db->fetch_array($db->query("select userid from epan_users where username='".$db->escape($username)."' limit 1"));
    return ($row && isset($row['userid'])) ? (int)$row['userid'] : 0;
}

// 登录态检查（与 ht/index.php 口径一致）
function pay_require_login() {
    if (empty($_SESSION['username_epan'])) {
        header('Location: /ht/login.php');
        exit;
    }
    return $_SESSION['username_epan'];
}
