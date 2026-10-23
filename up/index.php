<?php
//上传接收端：接收 /up/?p=<scpz1>&ly=<user>&m=<MB>&f=<name>&l=<fl>&t=<note> 的 multipart 文件上传
//scpz1 = pjs(2位) + scpz(目录id) + upxh(1位上传槽位)
error_reporting(E_ALL);
session_start();
header('Content-Type: text/html; charset=utf-8');

//--- 1) Referer 守卫（保留原有）---
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
if($ref == ''){ exit; }

//--- 2) 最小 bootstrap DB（不引 Init.php，避免其全局 CSRF 校验拦截 multipart 上传）---
require __DIR__ . '/../includes/Conf.php';
require __DIR__ . '/../includes/class/mysql.Class.php';
require __DIR__ . '/../includes/class/System.class.php';
$db = new System($DBSERVER, $USER, $PASSWORD, $DB, 'utf8');

//--- 2.5) 上传存放目录（epan_settings: upload_dir；空=默认 upfile）---
// 相对路径按站点根解析（up/ 的上一级 = 站点根 __DIR__.'/../'）；盘符开头视为绝对路径
$upDirRaw = trim(setting_val($db, 'file_path'), " \t/\\");
if ($upDirRaw === '') { $upDirRaw = 'upfile'; }
$upIsAbs = (bool)preg_match('#^[a-zA-Z]:#', $upDirRaw) || (strpos($upDirRaw, '/') === 0);
if ($upIsAbs) {
	$GLOBALS['UP_BASE_DIR'] = rtrim(str_replace('\\','/', $upDirRaw), '/') . '/';
	$GLOBALS['UP_WEB_DIR']  = 'upfile'; // 绝对路径不在 web 可达范围，回调页仍走默认 upfile/up.htm
} else {
	$GLOBALS['UP_BASE_DIR'] = __DIR__ . '/../' . $upDirRaw . '/';
	$GLOBALS['UP_WEB_DIR']  = $upDirRaw;
}

//统一结束：跳转回 upfile/up.htm，由前端解析结果码
//成功码形如 "3<folder>,<file>"，不带尾部 92 结束符（避免 92 拼到 fileId 后）；错误码为单数字，补 92
function updone($upxh, $code){
	$webDir = isset($GLOBALS['UP_WEB_DIR']) ? $GLOBALS['UP_WEB_DIR'] : 'upfile';
	$prefix = '/' . trim($webDir, '/') . '/up.htm?';
	if(strpos($code, '3') === 0 && strpos($code, ',') !== false){
		$loc = $prefix . $upxh . $code;
	} else {
		$loc = $prefix . $upxh . $code . '92';
	}
	header('Location: ' . $loc);
	exit;
}

//--- 3) 解析参数 ---
$p      = isset($_GET['p']) ? (string)$_GET['p'] : '';
$ly     = isset($_GET['ly']) ? trim($_GET['ly']) : '';
$mMax   = isset($_GET['m']) ? (float)$_GET['m'] : 15.0;      //MB 上限（前端 Q.fmax）
$fName  = isset($_GET['f']) ? urldecode((string)$_GET['f']) : '';
$note   = isset($_GET['t']) ? urldecode((string)$_GET['t']) : '';

if($p == '' || $ly == '' || strlen($p) < 4){ updone('0','6'); }
//upxh = 末位；scpz(目录id) = 去掉前2位 pjs 与末位 upxh
$upxh    = substr($p, -1, 1);
$folderId= (int)substr($p, 2, strlen($p) - 3);
if($folderId <= 0){ updone($upxh,'6'); }

//--- 4) 空间归属与权限 ---
$uRow = $db->fetch_array($db->query("select userid from epan_users where username='".$db->escape($ly)."' limit 1"));
if(!$uRow){ updone($upxh,'6'); }
$userId = (int)$uRow['userid'];

$isOwner = (isset($_SESSION['username_epan']) && $_SESSION['username_epan'] == $ly);
$isMgmt  = isset($_SESSION['gly_'.$ly]);
$isGuest = !($isOwner || $isMgmt);
if($isGuest){
	$fkUp = $db->SpaceSetting($ly,'fk_upload');
	if($fkUp === '' || $fkUp === NULL){ $fkUp = 1; }
	if((int)$fkUp == 0){ updone($upxh,'1'); }   //访客上传被关闭
}
//目录归属校验
$fRow = $db->fetch_array($db->query("select folder_id from epan_folders where folder_id=".$folderId." and userid=".$userId." limit 1"));
if(!$fRow){ updone($upxh,'6'); }

//--- 5) 文件接收 ---
if(!isset($_FILES['f']) || $_FILES['f']['error'] != UPLOAD_ERR_OK){ updone($upxh,'6'); }
$tmpName = $_FILES['f']['tmp_name'];
$size    = (int)$_FILES['f']['size'];
$origName= $fName != '' ? $fName : $_FILES['f']['name'];
$origName = trim($origName);
if($origName == '' || $size <= 0){ updone($upxh,'6'); }
//大小限制
if($mMax > 0 && $size > $mMax * 1048576){ updone($upxh,'2'); }

//扩展名
$pathinfo = pathinfo($origName);
$ext = isset($pathinfo['extension']) ? strtolower($pathinfo['extension']) : '';

//--- 6) 类型白/黑名单（预留：admin 批落地 epan_settings key）---
//读取 epan_settings 中相关 key；不存在则默认放行
function setting_val($db, $k){
	$r = $db->query("select value from epan_settings where vars='".$db->escape($k)."' limit 1");
	$row = $db->fetch_array($r);
	return $row ? trim((string)$row['value']) : '';
}
$allowList = setting_val($db, 'upload_type_allow');   //逗号分隔扩展名，非空=白名单
$denyList  = setting_val($db, 'filter_extension');       //逗号分隔扩展名，非空=黑名单（主读键）
if ($denyList === '') { $denyList = setting_val($db, 'upload_type_deny'); }  //兼容旧键
if($allowList !== ''){
	$allow = array_map('trim', explode(',', $allowList));
	if($ext == '' || !in_array($ext, $allow)){ updone($upxh,'6'); }
} else if($denyList !== ''){
	$deny = array_map('trim', explode(',', $denyList));
	if($ext != '' && in_array($ext, $deny)){ updone($upxh,'6'); }
}

//--- 7) 物理落盘 <UP_WEB_DIR>/YYYYMM/（目录按 upload_dir 设置解析）---
$baseDir = $GLOBALS['UP_BASE_DIR'];
$subDir  = date('Ym');
if(!is_dir($baseDir . $subDir)){ @mkdir($baseDir . $subDir, 0755, true); }
$uniq = substr(md5(uniqid('', true) . $userId), 0, 16);
$saveName = $uniq . ($ext != '' ? '.' . $ext : '');
$webDir = $GLOBALS['UP_WEB_DIR'];
$savePathRel = $webDir . '/' . $subDir . '/' . $saveName;   //web 相对路径
$savePathAbs = $baseDir . $subDir . '/' . $saveName;
if(!@move_uploaded_file($tmpName, $savePathAbs)){ updone($upxh,'6'); }

//--- 8) 写 epan_files ---
$mime = 'application/octet-stream';
if(function_exists('finfo_file')){
	$fi = finfo_open(FILEINFO_MIME_TYPE);
	if($fi){ $mime = finfo_file($fi, $savePathAbs); finfo_close($fi); }
}
$md5 = md5_file($savePathAbs);
$ip  = function_exists('getClientIP') ? '' : '';
$ip  = substr($_SERVER['REMOTE_ADDR'], 0, 15);
$db->query("INSERT INTO epan_files (file_name, file_key, file_short_url, file_extension, is_image, file_mime, file_description, file_store_path, file_real_name, file_md5, store_old, file_size, file_time, in_share, space_pos, in_recycle, is_public, userid, folder_id, cate_id, subcate_id, ip) VALUES (
'".$db->escape($origName)."',
'".substr(md5(uniqid('',true)),0,8)."',
'',
'".$db->escape($ext)."',
0,
'".$db->escape($mime)."',
'".$db->escape($note)."',
'".$db->escape($savePathRel)."',
'".$db->escape($origName)."',
'".$db->escape($md5)."',
0,
".(int)$size.",
".time().",
0,0,0,0,
".$userId.",
".$folderId.",
0,0,
'".$db->escape($ip)."'
)");
$newFileId = (int)$db->insert_id();
//回查真实自增 id（个别环境 mysqli_insert_id 可能不返回本连接值）
$idRow = $db->fetch_array($db->query("select file_id from epan_files where file_store_path='".$db->escape($savePathRel)."' and userid=".$userId." order by file_id desc limit 1"));
if($idRow){ $newFileId = (int)$idRow['file_id']; }

//--- 9) 成功：跳回 up.htm，payload=<folderId>,<fileId> 供 xzts 回调 dqfile ---
if($newFileId > 0){ updone($upxh, '3'.$folderId.','.$newFileId); }
updone($upxh,'6');
