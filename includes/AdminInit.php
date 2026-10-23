<?php
/* ---------------------------------------------------- */
/* 程序名称: admin 后台统一初始化（与前台共用 includes/Init.php 引导）
/* 程序功能:
/*   1) 复用前台 Init.php：session/CSRF 基础设施、时区、HttpsCheck/GetHostByName、
/*      system/configs.inc.php 配置加载、DbMysql/System/Crypt 等前台类、全局 POST CSRF 校验；
/*   2) 加载后台专用类 AdminSystem（modules/System/SystemModule.php，extends Db；
/*      batch7 起 Info() 主路径读 epan_settings，缺键回退 zd_system_setting）；
/*      注：前台 class/System.Class.php 已占用类名 System，后台类改名 AdminSystem，两者不再同名冲突；
/*   3) 以同一套 configs.inc.php 配置实例化后台 $db（admin 全站统一用 $db，不再使用 $dou）；
/*   4) 补齐 admin 专属常量（source/key/Debug/LocalSource/ip_key）与 $_G。
/* ---------------------------------------------------- */

// 共享引导（session、CSRF、配置、前台类、全局 CSRF 校验均在此完成）
require_once dirname(__FILE__) . '/Init.php';

// 后台专用类
require_once dirname(__FILE__) . '/class/Db.class.php';          // AdminSystem 的基类 Db（mysqli 封装）
require_once dirname(__FILE__) . '/modules/System/SystemModule.php'; // AdminSystem extends Db
require_once dirname(__FILE__) . '/class/Account.class.php';       // First.php 初始化残留用到 Account::PwdEnc

// 后台数据库实例：与前台同配置源（system/configs.inc.php），变量名统一为 $db
$db = new AdminSystem($DBSERVER, $USER, $PASSWORD, $DB, 'utf8');
if (!$db->connection) { die('数据库连接失败: ' . $db->error); }

// 后台专属常量（Init/Conf 未定义的部分在此补齐；已定义的不重复 define）
if (!defined('source')) define('source', 'service.csource.com.cn');
if (!defined('key'))     define('key', $db->Info('encrypted'));      // 后台加密 key（batch7 起读 epan_settings.encrypted）
if (!defined('Debug'))   define('Debug', 'Off');
if (!defined('LocalSource')) define('LocalSource', 'F:\\SourceInc\\installpack');
if (!defined('ip_key'))     define('ip_key', 'bh3E6Q02n8TOZVxR8sd1Zw==');
if (!defined('ip_license')) define('ip_license', '15944a3c-7889-32d0-a0e8-a3a9c3f28efc');

// 后台全局变量（登录尝试次数上限、客户端 IP）
$_G = array(
	'Login' => array('MaxNumber' => ($db->Info('MaxCount') ? $db->Info('MaxCount') : 4)),
	'IPS' => array('ClientIP' => (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')),
);

// ===== 后台 IP 白名单（需求5-4）=====
// 仅作用于 admin 全站（本文件为所有 admin 入口的统一引导）。
// 白名单存于 epan_settings.sec_admin_ip_whitelist（逗号/空白分隔的 IP 列表）。
// 为空 → 不限制（默认关闭）；非空且当前 REMOTE_ADDR 不在其中 → 403 拒绝并写 epan_log。
// 测试时务必先把 127.0.0.1 写入白名单再测，否则本地所有 admin 请求（含登录）都会被拒。
$__sec_wl = '';
$__wlrs = @$db->query("SELECT value FROM epan_settings WHERE vars='sec_admin_ip_whitelist' LIMIT 1");
if ($__wlrs && ($__wlrow = $db->fetch_array($__wlrs))) { $__sec_wl = (string)$__wlrow[0]; }
if (trim($__sec_wl) !== '') {
	$__clientIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	$__allowIp = false;
	foreach (preg_split('/[,\s]+/', $__sec_wl) as $__wip) {
		$__wip = trim($__wip);
		if ($__wip !== '' && $__wip === $__clientIp) { $__allowIp = true; break; }
	}
	if (!$__allowIp) {
		if (function_exists('epan_log_write')) { epan_log_write('block', 'admin ip not in whitelist: '.$__clientIp); }
		http_response_code(403);
		header('Content-Type: text/plain; charset=utf-8');
		echo '403 Forbidden：您的 IP（'.htmlspecialchars($__clientIp, ENT_QUOTES, 'UTF-8').'）不在后台访问白名单内。';
		exit;
	}
}
// ===== 后台 IP 白名单结束 =====
?>
