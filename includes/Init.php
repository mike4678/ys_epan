<?PHP

error_reporting(0);

// ===== CSRF 防护基础设施（全站唯一实现点）=====
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!function_exists('csrf_token')) {
	function csrf_token() { return isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; }
}
if (!function_exists('csrf_check')) {
	function csrf_check($t) { return !empty($_SESSION['csrf_token']) && is_string($t) && hash_equals($_SESSION['csrf_token'], $t); }
}
if (!function_exists('csrf_field')) {
	function csrf_field() { return '<input type="hidden" name="csrf_token" value="'.htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8').'" />'; }
}
// 表单自动注入令牌（shutdown 时把隐藏字段塞进每个 <form>）
if (!defined('PHPDISK_CSRF_OB_STARTED')) {
	define('PHPDISK_CSRF_OB_STARTED', 1);
	ob_start();
	register_shutdown_function(function(){
		$html = ob_get_clean();
		if ($html === false || strpos($html, '<form') === false) { echo $html; return; }
		echo preg_replace('/(<form[^>]*>)/is', '$1'.csrf_field(), $html);
	});
}
// ===== CSRF 基础设施结束 =====

// GetHostByName 兼容函数：原系统多处调用但未定义，导致留言(WriteGuestbook)/登录日志 fatal error。
// 语义同 gethostbyname（把主机名解析为 IP），解析失败时原样返回传入值。
if (!function_exists('GetHostByName')) {
	function GetHostByName($host) { return @gethostbyname((string)$host); }
}

// 调整时区
if (PHP_VERSION >= '5.1') 
{
date_default_timezone_set('PRC');
}

//初始化常量
define('PHPDISK_ROOT', substr(dirname(__FILE__), 0, -8));
define('PHPDISK_ROOT_BAK', $_SERVER['DOCUMENT_ROOT']);
define('PHPDISK_ROOT_WEB', HttpsCheck(). $_SERVER['HTTP_HOST'] . '/');
define('SERVER_NAME',$_SERVER['SERVER_NAME']);	
$timestamp = time();
define('TS',$timestamp);
// @set_magic_quotes_runtime(0); // PHP8 已删除该函数，移除调用

// 取得当前站点所在的根目录

$root_url = strtolower(dirname(HttpsCheck() . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'])) . '/';


//检测是否为https模式
function HttpsCheck()
    {
        if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        {
            return 'https://';
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        {
            return 'https://';
        }
        elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
        {
            return 'https://';
        }
  
        return 'http://';
    }

//配置文件检查：仅当系统已安装（存在 configs.inc.php 与 install.lock）才继续；
//否则跳转到安装向导，避免缺失配置时直接连库报 "Can not connect to mysql server"
$conf_file = PHPDISK_ROOT .'system/configs.inc.php';
$lock_file = PHPDISK_ROOT .'system/install.lock';
if(!file_exists($conf_file) || !file_exists($lock_file))
{  
	$jump =  HttpsCheck(). $_SERVER['HTTP_HOST'] . '/install/index.php';
	header("Location: ".$jump); //重定向浏览器
	exit;
} else {    //如果存在则引入该文件
	require_once 'Conf.php';
	}

//初始化模块
$arr = array('Mysql','System','zip','Crypt','Image');
for ($i = 0 ; $i < count($arr); $i++){
	require('Class/'.$arr[$i].'.Class.php');
}

// 实例化类
$db = new System($DBSERVER, $USER, $PASSWORD, $DB, 'utf8');

//初始化站名及网址
define('Website_title', $db->Info('site_title'));
define('Website', $db->Info('disk_url'));

// ===== 全站安全检测（需求5）=====
// 配置只读自 epan_settings（vars/value 两列），键名固定，缺省/不存在即视为关闭（交付态全关）。
// 后台 system.php「安全防御」tab 由并行 agent 写入以下键：
//   sec_sql_block / sec_xss_block / sec_danger_ext / sec_rate_limit /
//   sec_rate_max(默认60) / sec_rate_window(默认60) / sec_admin_ip_whitelist / sec_oplog
// 检测范围：REQUEST_URI（urldecode 后）+ GET 参数；不扫描 POST body（避免误伤 ccenter 留言/登录 AJAX）。
if (!function_exists('epan_log_write')) {
	function epan_log_write($type, $detail, $uri = null, $method = null) {
		global $db;
		if (!isset($db) || !is_object($db) || !method_exists($db, 'query')) return;
		$ip  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$uri = ($uri === null)  ? (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '') : $uri;
		$method = ($method === null) ? (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '') : $method;
		$ck  = '';
		if (!empty($_COOKIE)) { $ck = @json_encode($_COOKIE, JSON_UNESCAPED_UNICODE); }
		$sql = "INSERT INTO epan_log (log_type,log_time,log_ip,log_uri,log_method,log_cookie,log_detail) VALUES (".
			"'".$db->escape($type)."',".time().",".
			"'".$db->escape($ip)."',".
			"'".$db->escape(substr((string)$uri,0,500))."',".
			"'".$db->escape(substr((string)$method,0,10))."',".
			"'".$db->escape(substr((string)$ck,0,2000))."',".
			"'".$db->escape(substr((string)$detail,0,500))."')";
		@$db->query($sql);
	}
}
if (!function_exists('epan_sec_cfg')) {
	function epan_sec_cfg() {
		global $db;
		static $cfg = null;
		if ($cfg !== null) return $cfg;
		$cfg = array();
		$keys = "'sec_sql_block','sec_xss_block','sec_danger_ext','sec_rate_limit','sec_rate_max','sec_rate_window','sec_admin_ip_whitelist','sec_oplog'";
		$rs = @$db->query("SELECT vars,value FROM epan_settings WHERE vars IN (".$keys.")");
		if ($rs) { while ($row = $db->fetch_array($rs)) { $cfg[$row[0]] = $row[1]; } }
		return $cfg;
	}
}
if (!function_exists('epan_sec_on')) {
	function epan_sec_on($k, $cfg) {
		return isset($cfg[$k]) && (string)$cfg[$k] === '1';
	}
}
if (!function_exists('epan_oplog')) {
	// 操作/登录日志打点：仅当 sec_oplog=1 时落库（login/feedback/op）
	function epan_oplog($type, $detail) {
		$cfg = function_exists('epan_sec_cfg') ? epan_sec_cfg() : array();
		if (epan_sec_on('sec_oplog', $cfg)) { epan_log_write($type, $detail); }
	}
}

$__sec_cfg = epan_sec_cfg();
$__sec_blocked = false; $__sec_hit = '';
// 待匹配文本：urldecode(REQUEST_URI) + 所有 GET 标量值
$__sec_hay = isset($_SERVER['REQUEST_URI']) ? urldecode($_SERVER['REQUEST_URI']) : '';
if (!empty($_GET)) {
	foreach ($_GET as $__gk => $__gv) {
		if (is_string($__gv)) $__sec_hay .= "\x0".$__gk."=".$__gv;
	}
}

// 1) SQL 注入窄特征
if (epan_sec_on('sec_sql_block', $__sec_cfg)) {
	$__sec_sql_pats = array(
		'sql:union_select'   => '/union\s+select/i',
		'sql:stacked_func'   => '/\b(sleep|benchmark|pg_sleep)\s*\(\s*\d/i',
		'sql:or_1eq1'        => "/('\s*or\s*'\d+'\s*=\s*'\d+)|(\"\s*or\s*\d+\s*=\s*\d+)|(\bor\s+1\s*=\s*1)/i",
		'sql:meta_table'     => '/information_schema|performance_schema|@@version|concat\s*\(/i',
	);
	foreach ($__sec_sql_pats as $__name => $__re) {
		if (preg_match($__re, $__sec_hay)) { $__sec_blocked = true; $__sec_hit = $__name; break; }
	}
}
// 2) XSS 窄特征
if (!$__sec_blocked && epan_sec_on('sec_xss_block', $__sec_cfg)) {
	$__sec_xss_pats = array(
		'xss:script_tag'  => '/<\s*script/i',
		'xss:on_event'    => '/\bon(error|load|click|mouseover|focus)\s*=/i',
		'xss:javascript'  => '/javascript\s*:/i',
	);
	foreach ($__sec_xss_pats as $__name => $__re) {
		if (preg_match($__re, $__sec_hay)) { $__sec_blocked = true; $__sec_hit = $__name; break; }
	}
}
// 说明：sec_danger_ext（危险扩展名）的真正拦截在 up/index.php 上传链路
// （up/index.php 不 include Init.php，此处无法拦截；由并行 agent 在 up/index.php 用 upload_type_deny/allow 实现），
// 本键仅作语义开关记录，检测块不重复实现，避免误杀。

// 命中即阻断
if ($__sec_blocked) {
	epan_log_write('block', $__sec_hit);
	http_response_code(403);
	header('Content-Type: text/plain; charset=utf-8');
	echo "403 Forbidden：请求包含被拦截的特征（".$__sec_hit."），已记录日志。";
	exit;
}

// 3) 请求频率限制（按 IP 窗口计数；默认关闭。开启时每请求 1~2 条轻量查询）
if (epan_sec_on('sec_rate_limit', $__sec_cfg)) {
	$__rmax = (isset($__sec_cfg['sec_rate_max']) && (int)$__sec_cfg['sec_rate_max'] > 0) ? (int)$__sec_cfg['sec_rate_max'] : 60;
	$__rwin = (isset($__sec_cfg['sec_rate_window']) && (int)$__sec_cfg['sec_rate_window'] > 0) ? (int)$__sec_cfg['sec_rate_window'] : 60;
	$__rip  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	if ($__rip !== '') {
		$__now = time();
		$__eip = $db->escape($__rip);
		$__rc  = $db->fetch_array($db->query("SELECT cnt,t0 FROM epan_reqcnt WHERE ip='".$__eip."'"));
		if (!$__rc || ($__now - (int)$__rc['t0']) >= $__rwin) {
			// 新窗口：重置计数为 1
			@$db->query("REPLACE INTO epan_reqcnt (ip,cnt,t0) VALUES ('".$__eip."',1,".$__now.")");
		} else {
			$__cnt = (int)$__rc['cnt'] + 1;
			@$db->query("UPDATE epan_reqcnt SET cnt=".$__cnt." WHERE ip='".$__eip."'");
			if ($__cnt > $__rmax) {
				epan_log_write('block', 'rate limit: '.$__cnt.'/'.$__rmax.' in '.$__rwin.'s');
				http_response_code(429);
				header('Retry-After: '.$__rwin);
				header('Content-Type: text/plain; charset=utf-8');
				echo "429 Too Many Requests：访问过于频繁（".$__cnt.'/'.$__rmax."），请稍后再试。";
				exit;
			}
		}
	}
}
// ===== 全站安全检测结束 =====

// 全局 POST  CSRF 校验（GET 不拦截，避免破坏分页/验证码/注册 AJAX）
// 空间界面隐藏 iframe AJAX 页豁免——它们经隐藏表单+验证码/管理区登录校验提交，全局校验会误拦留言/目录/上传等操作
// admin 豁免：login_check.php 为登录 AJAX（手工拼 data，不带表单注入的 csrf_token）；
// system/upload.php、system/databasecontrol.php 为后台手工 AJAX（json 响应）。
// 注意：旧正则写的是 admin/LoginCheck\.php，但实际文件是 admin/login_check.php（带下划线），路径不匹配，已修正。
$__csrf_skip = false;
$__csrf_script = str_replace('\\','/', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '');
if (preg_match('#/(user/(ccenter|C_zxsj|lyb)|admin/(login_check|system/upload|system/databasecontrol))\.php$#i', $__csrf_script)) { $__csrf_skip = true; }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$__csrf_skip) {
	if (!csrf_check(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
		http_response_code(403);
		exit('CSRF 校验失败，请刷新页面后重试');
	}
}

//结束php部分初始化，初始化页面顶部信息
?>
