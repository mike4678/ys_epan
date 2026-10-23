<?php

error_reporting(0);

define('PHPDISK_ROOT', '../');
define('IN_PHPDISK', true);
define('NOW_YEAR',date('Y'));

$charset = 'utf-8';
$total_step = 8;
$lang_id = 'zh_cn';

// 内置翻译函数（替代缺失的 php-gettext 依赖）
function __($key) {
	static $map = array(
		'pay_version' => '商业版',
		'free_version' => '免费版',
		'db_file_not_exists' => '数据库安装文件不存在，请检查 install/install.sql 是否存在',
		'install_guide' => '系统安装向导',
		'next_step' => '下一步',
		'prev_step' => '上一步',
		'previous_step' => '上一步',
		'agree' => '同意',
		'disagree' => '不同意',
		'the' => '第',
		'step' => '步',
		'install_note' => '安装环境检查',
		'welcome_to_use' => '欢迎使用 PHPDisk 网络硬盘系统',
		'install_settings' => '请确认以下安装条件',
		'mysql_server_or_ip' => 'MySQL 服务器或 IP 地址',
		'mysql_username_or_password' => 'MySQL 用户名和密码',
		'mysql_database_name' => 'MySQL 数据库名称',
		'check_dir1' => '目录权限检查',
		'check_dir2' => '是否可写',
		'database_tips_info' => '请确保已创建好数据库，并知道数据库的连接信息',
		'php_env_setting' => 'PHP 环境设置',
		'short_open_tag' => '建议开启 short_open_tag（短标签支持）',
		'config_file_not_exists' => '配置文件不存在',
		'config_file_cant_write' => '配置文件不可写',
		'database_setting' => '数据库设置',
		'file_exists' => '文件存在',
		'file_writeable' => '文件可写',
		'base_info' => '数据库连接信息',
		'dbhost' => '数据库服务器',
		'dbhost_tips' => '通常为 localhost',
		'dbuser' => '数据库用户名',
		'dbpasswd' => '数据库密码',
		'js_dbhost' => '请输入数据库服务器地址',
		'js_dbuser' => '请输入数据库用户名',
		'js_dbpasswd' => '数据库密码为空，确定继续？',
		'fill_all_blank' => '请填写完整信息',
		'install_database' => '选择/创建数据库',
		'mysql_not_support' => '服务器不支持 MySQLi 扩展，无法继续安装',
		'could_not_connect' => '数据库连接失败：',
		'database_info' => '数据库信息',
		'fill_db_tips' => '请填写数据库名称，或选择已有的数据库',
		'no_script_tips' => '请勿在本页面执行其他脚本操作',
		'dbname' => '数据库名',
		'please_select' => '请选择',
		'dbname2_null' => '请选择一个数据库',
		'dbname1_null' => '请输入数据库名称',
		'set_admin_account' => '设置管理员账号',
		'invalid_query' => '数据库操作失败：',
		'write_success' => '写入成功',
		'write_failture' => '写入失败',
		'write_fail' => '写入失败',
		'cant_write' => '不可写',
		'cannot_write' => '不可写',
		'php_version_too_lower' => 'PHP 版本过低，需要 4.0.6 以上',
		'mysql_version_too_lower' => 'MySQL 版本过低，需要 4.1 以上',
		'database_already_exists' => '数据库已安装过，继续将覆盖数据！',
		'database_confirm' => '数据库已存在，确定覆盖？',
		'file_not_chmod' => '请检查目录权限',
		'server_env_ok' => '服务器环境符合要求',
		'quit' => '退出',
		'username' => '管理员用户名',
		'password' => '管理员密码',
		'confirm_password' => '确认密码',
		'email' => '邮箱',
		'js_username' => '请输入管理员用户名',
		'js_password' => '请输入管理员密码',
		'js_password_too_short' => '密码长度至少6位',
		'js_confirm_password' => '两次输入的密码不一致',
		'js_email' => '请输入正确的邮箱地址',
		'check_user_info' => '确认账号信息',
		's_password_too_short' => '密码长度至少5位',
		's_password_not_same' => '两次密码输入不一致',
		's_email' => '邮箱格式不正确',
		's_check_server_env' => '信息正确，正在检查服务器环境...',
		'bad_chars' => '用户名包含非法字符',
		'user_info_right' => '以上信息是否正确？确认后点击下一步',
		'import_data' => '导入数据',
		'all_create' => '成功创建 ',
		'tables' => ' 张数据表',
		'base_settings' => '基本设置',
		'site_title' => '网站标题',
		'phpdisk_url' => '网站地址',
		'phpdisk_url_tips' => '请填写本系统的访问网址',
		'encrypt_key' => '加密密钥',
		'reflash_key' => '刷新密钥',
		'open_gzip' => '开启 Gzip 压缩',
		'yes' => '是',
		'no' => '否',
		'gzipcompress_not_support' => '服务器不支持 Gzip 压缩',
		'js_site_title' => '请输入网站标题',
		'js_phpdisk_url' => '请输入网站地址',
		'js_encrypt_key' => '请输入加密密钥',
		'site_title_and_phpdisk_url' => '请填写网站标题和网站地址',
		'finished_install' => '安装完成',
		'finished_tips' => '安装完成，感谢使用！请删除 install 目录以保障安全。',
		'reinstall_tips' => '如需重新安装，请删除 system/install.lock 文件。',
		'thanks' => '感谢使用 PHPDisk 网络硬盘系统！',
		'visit_phpdisk' => '访问网站首页',
		'phpdisk_official_site' => 'PHPDisk 官方网站',
		'create_table' => '创建数据表',
		'success' => '成功',
	);
	return isset($map[$key]) ? $map[$key] : $key;
}

if(file_exists(PHPDISK_ROOT.'system/install.lock')){
	header("Location: ../index.php");
	exit;
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	define('LF',"\r\n");
} else {
	define('LF',"\n");
}

$version_type = __('free_version');
ob_start();

function addslashes_array(&$array) {
	if(is_array($array)){
		foreach($array as $k => $v) {
			$array[$k] = addslashes_array($v);
		}
	}elseif(is_string($array)){
		$array = addslashes($array);
	}
	return $array;
}
function defend_xss($val){
	return str_ireplace(array('<','>'),'',$val);
}

function gpc($name,$w = 'GPC',$default = '',$d_xss=0){
	$i = 0;
	for($i = 0; $i < strlen($w); $i++) {
		if($w[$i] == 'G' && isset($_GET[$name])) return $d_xss ? defend_xss($_GET[$name]) : $_GET[$name];
		if($w[$i] == 'P' && isset($_POST[$name])) return $d_xss ? defend_xss($_POST[$name]) : $_POST[$name];
		if($w[$i] == 'C' && isset($_COOKIE[$name])) return $d_xss ? defend_xss($_COOKIE[$name]) : $_COOKIE[$name];
	}
	return $default;
}
// PHP 7+ 已移除 magic_quotes，改为在各使用点做 mysqli_real_escape_string / addslashes 转义

$step = (int)gpc('step','GP',0);
$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$config_file = PHPDISK_ROOT.'./system/configs.inc.php';

$sqlfile = PHPDISK_ROOT.'install/install.sql';
if(!file_exists($sqlfile) || !is_readable($sqlfile)) {
	exit(__('db_file_not_exists'));
}
$fp = fopen($sqlfile, 'rb');
$sql = fread($fp, filesize($sqlfile));
fclose($fp);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />
<title>系统安装向导</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body style="margin-top:5px">
<div id="main">
<?php if($step ==3 || $step ==4 || $step ==5 || $step ==7){?>
  <form method="post" name="myform" action="<?php echo $php_self;?>" onsubmit="return chksubmit(this);">
<?php }else{?>  
  <form method="post" name="myform" action="<?php echo $php_self;?>">
<?php }?>
    <p class="title"><?php echo __('install_guide') ?></p>
    <hr noshade="noshade" />
<?php
if(!$step){
?>
<p>
	
</p>
<p>&nbsp;</p>
<p align="center">欢迎使用，安装向导会指引您安装整个系统。</p>
<p>&nbsp;</p>
<hr noshade="noshade" />
<p align="right">
<input type="hidden" name="step" value="1" />
<input class="formbutton" type="submit" value="<?php echo __('next_step') ?>" />
</p>
<?php
}elseif($step == 1){
?>
    <p>本二次开发于 PHPDisk 网络硬盘，界面类似于永硕E盘，是一款基于PHP+MySQL的强大的网络硬盘管理系统，您可以将一些资料通过网络上传到您的系统中，以一种图形界面的方式，方便的管理您每一个文件。方便的异地办公，资料的保存将会是您的首选。</p>
    <hr noshade="noshade" />
    <p align="right">
      <input type="hidden" name="step" value="<?php echo $step+1 ?>" />
      <input class="formbutton" type="submit" value="<?php echo __('agree') ?>" />&nbsp;&nbsp;<input class="formbutton" type="button" value="<?php echo __('disagree') ?>" onclick="javascript:window.close();" />
    </p>
<?php 
}else if($step ==2){
	$short_open_tag = @ini_get('short_open_tag');
	$system_dir = PHPDISK_ROOT.'system/';
	$filestores_dir = PHPDISK_ROOT.'up/';
	$template_dir = PHPDISK_ROOT.'templates/';
	$plugins_dir = PHPDISK_ROOT.'plugins/';
	$chk_system_dir = dir_writeable($system_dir) ? 1 : 0;
	$chk_filestores_dir = dir_writeable($filestores_dir) ? 1 : 0;
	$chk_template_dir = dir_writeable($template_dir) ? 1 : 0;
	$chk_plugins_dir = dir_writeable($plugins_dir) ? 1 : 0;
	if($short_open_tag && $chk_system_dir && $chk_filestores_dir && $chk_template_dir && $chk_plugins_dir){
		$install_halt =0;
	}else{
		$install_halt =1;
	}
?>
    <p class="title"><?php echo __('the') ?>(<span class="txtblue"><?php echo $step ?></span>/<?php echo $total_step ?>)<?php echo __('step') ?>:<?php echo __('install_note') ?></p>
    <p><?php echo __('welcome_to_use') ?>，<?php echo __('install_settings') ?>: </p>
    <ul>
      <li><?php echo __('mysql_server_or_ip') ?> </li>
      <li><?php echo __('mysql_username_or_password') ?> </li>
      <li><?php echo __('mysql_database_name') ?> </li>
      <li><?php echo $system_dir ?> <?php echo __('check_dir1') ?></li>
	  <li><?php echo $system_dir ?> <?php echo __('check_dir2') ?>：<?php output_txt($chk_system_dir,1); ?></li>
      <li><?php echo $filestores_dir ?> <?php echo __('check_dir1') ?></li>
	  <li><?php echo $filestores_dir ?> <?php echo __('check_dir2') ?>：<?php output_txt($chk_filestores_dir,1); ?></li>
      <li><?php echo $template_dir ?> <?php echo __('check_dir1') ?></li>
	  <li><?php echo $template_dir ?> <?php echo __('check_dir2') ?>：<?php output_txt($chk_template_dir,1); ?></li>
      <li><?php echo $plugins_dir ?> <?php echo __('check_dir1') ?></li>
	  <li><?php echo $plugins_dir ?> <?php echo __('check_dir2') ?>：<?php output_txt($chk_plugins_dir,1); ?></li>
    </ul>
    <p><?php echo __('database_tips_info') ?></p>
	<?php 
	if($short_open_tag !=1){
	?>
		<p class="txtred"><b><?php echo __('php_env_setting') ?>：</b><?php echo __('short_open_tag') ?></p>
	<?php 
	}
	?>
	<?php if(!$install_halt){?>
    <hr noshade="noshade" />
    <p align="right">
      <input type="hidden" name="step" value="<?php echo $step+1 ?>" />
      <input class="formbutton" type="submit" value="<?php echo __('next_step') ?>" />
    </p>
	<?php }?>
<?php
} elseif ($step == 3) {

	$fp = fopen($config_file,'w');
	if (!$fp) {
		exit("Can not open file <b>$config_file</b> .");
	}
	@fclose($fp);
	$exist_error = false;
	$write_error = false;
	if (file_exists($config_file)) {
		$fileexists = output_txt(1, 0);
	} else {
		$fileexists = output_txt(0, 0);
		$exist_error = true;
	}
	if (is_writable($config_file)) {
		$filewriteable = output_txt(1, 0);
	} else {
		$filewriteable = output_txt(0, 0);
		$write_error = true;
	}
	if ($exist_error) {
		$config_info = $config_file.' '.__('config_file_not_exists');
	} elseif($write_error) {
		$config_info = __('config_file_cant_write');
	}
?>
    <p class="title"><?php echo __('the') ?>(<span class="txtblue"><?php echo $step ?></span>/<?php echo $total_step ?>)<?php echo __('step') ?>:<?php echo __('database_setting') ?></p>
    <p><?php echo $config_file;?> <?php echo __('file_exists') ?> <?php echo $fileexists;?></p>
    <p><?php echo $config_file;?> <?php echo __('file_writeable') ?> <?php echo $filewriteable;?></p>
<?php
if ($config_info) {
?>
    <p><?php echo $config_info;?></p>
    <hr noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="<?php echo __('prev_step') ?>" onclick="history.back(1)" />
    </p>
<?php
} else {
?>
    <hr noshade="noshade" />
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2" class="bold"><?php echo __('base_info') ?></td>
      </tr>
      <tr>
        <td width="30%"><?php echo __('dbhost') ?>:</td>
        <td><input type="text" value="localhost" name="dbhost" class="formfield" style="width:150px"> <?php echo __('dbhost_tips') ?></td>
      </tr>
      <tr>
        <td><?php echo __('dbuser') ?>:</td>
        <td><input type="text" value="root" name="dbuser" class="formfield" style="width:150px" onclick="this.value='';"></td>
      </tr>
      <tr>
        <td><?php echo __('dbpasswd') ?>:</td>
        <td><input type="password" value="" name="dbpasswd" class="formfield" style="width:150px" onclick="this.value='';"></td>
      </tr>
    </table>
	<script language="javascript">
	document.myform.dbhost.focus();
	function chksubmit(o){
		if(o.dbhost.value == ""){
			alert("<?php echo __('js_dbhost') ?>");
			o.dbhost.focus();
			return false;
		}
		if(o.dbuser.value == ""){
			alert("<?php echo __('js_dbuser') ?>");
			o.dbuser.focus();
			return false;
		}
		if(o.dbpasswd.value == ""){
			if(!confirm('<?php echo __('js_dbpasswd') ?>')){
				o.dbpasswd.focus();
				return false;
			}
		}
		o.s1.disabled = true;
		return true;
	}
	</script>
    <p>&nbsp;</p>
    <p><?php echo __('database_tips_info') ?></p>
    <hr noshade="noshade" />
    <p align="right">
      <input type="hidden" name="step" value="<?php echo $step+1 ?>" />
      <input class="formbutton" name="s1" type="submit" value="<?php echo __('next_step') ?>" />
    </p>
<?php 
}
} elseif ($step == 4) {
	$dbhost = trim(gpc('dbhost','P','',1));
	$dbuser = trim(gpc('dbuser','P','',1));
	$dbpasswd = trim(gpc('dbpasswd','P','',1));
	
	if(!$dbhost || !$dbuser){

?>
    <p><?php echo __('fill_all_blank') ?></p>
    <hr noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="<?php echo __('prev_step') ?>" onclick="history.back(1)" />
    </p>
<?php
	} else {
?>
    <p class="title"><?php echo __('the') ?>(<span class="txtblue"><?php echo $step ?></span>/<?php echo $total_step ?>)<?php echo __('step') ?>:<?php echo __('install_database') ?></p>
<?php
if(!extension_loaded('mysqli')){
	exit(__('mysql_not_support'));
}
$list_db = 0;

$mysqli = @new mysqli($dbhost, $dbuser, $dbpasswd);
if($mysqli->connect_error){
	exit(__('could_not_connect') . ' ' . htmlspecialchars($mysqli->connect_error, ENT_QUOTES, 'UTF-8'));
}

$q = $mysqli->query("SHOW DATABASES;");
if($q){
	$list_db = 1;
	$db_list = array();
	while($rs = $q->fetch_array()){
		$db_list[] = $rs;
	}
	$q->free();
	unset($rs);
}
$mysqli->close();
?>
    <hr noshade="noshade" />
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2" class="bold"><?php echo __('database_info') ?></td>
      </tr>
	  <tr>
	  	<td colspan="2"><span class="txtred"><?php echo __('fill_db_tips') ?></span></td>
	  </tr>
	  <tr>
	  	<td colspan="2"><span class="txtred"><?php echo __('no_script_tips') ?></span></td>
	  </tr>
      <tr>
        <td width="20%"><?php echo __('dbname') ?>:</td>
        <td><input type="radio" id="rd1" name="rd" onclick="chg_db(1);" checked="checked" />
		<input type="text" value="这里填数据库名称" name="dbname1" id="dbname1" class="formfield" style="width:150px" onclick="this.value='';"></td>
      </tr>
      <?php
      if($list_db){
      ?>
	  <tr>
	  	<td></td>
		<td><input type="radio" id="rd2" name="rd" onclick="chg_db(2);" />
		<select name="dbname2" id="dbname2">
		<option value="0"><?php echo __('please_select') ?></option>
		<?php
		sort($db_list);
		foreach($db_list as $k => $v){
			echo '<option value="'.$db_list[$k]['Database'].'">'.$db_list[$k]['Database'].'</option>';
		}
		?>
		</select>
		</td>
	  </tr>
	  <?php
      }
	  ?>
    </table>
	<br />
    <hr noshade="noshade" />
    <p align="right">
	  <input type="hidden" name="dbhost" value="<?php echo htmlspecialchars($dbhost, ENT_QUOTES, 'UTF-8') ?>"	 />
	  <input type="hidden" name="dbuser" value="<?php echo htmlspecialchars($dbuser, ENT_QUOTES, 'UTF-8') ?>" />
	  <input type="hidden" name="dbpasswd" value="<?php echo htmlspecialchars($dbpasswd, ENT_QUOTES, 'UTF-8') ?>" />
	  <input type="hidden" name="dbname" id="dbname" value="" />
      <input type="hidden" name="step" value="<?php echo $step+1 ?>" />
      <!--<input class="formbutton" type="submit" value="<?php echo __('previous_step') ?>" />-->
      <input class="formbutton" name="s1" type="submit" value="<?php echo __('next_step') ?>" />
    </p>
	<script type="text/javascript">
	function g(id){
		return document.getElementById(id);
	}
	function chg_db(id){
		if(id==1){
			g('dbname1').disabled = false;
			g('dbname2').disabled = true;
		}else{
			g('dbname1').disabled = true;
			g('dbname2').disabled = false;
		}
	}
	function chksubmit(o){
		if(g('rd2').checked==true){
			if(o.dbname2.value ==0){
				alert("<?php echo __('dbname2_null') ?>");
				o.dbname2.focus();
				return false;
			}else{
				o.dbname.value = o.dbname2.value;
			}
		}else{
			if(o.dbname1.value ==''){
				alert("<?php echo __('dbname1_null') ?>");
				o.dbname1.focus();
				return false;
			}else{
				o.dbname.value = o.dbname1.value;
			}
		}
	}
	g('dbname2').disabled = true;
	</script>
<?php
	}
} elseif ($step == 5) {
	$dbname = trim(gpc('dbname','P','',1));
	$dbhost = trim(gpc('dbhost','P','',1));
	$dbuser = trim(gpc('dbuser','P','',1));
	$dbpasswd = trim(gpc('dbpasswd','P','',1));

	if(!$dbname || !$dbhost || !$dbuser || !$dbpasswd){

?>
    <p><?php echo __('fill_all_blank') ?></p>
    <hr noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="<?php echo __('prev_step') ?>" onclick="history.back(1)" />
    </p>
<?php
	} else {
?>
    <p class="title"><?php echo __('the') ?>(<span class="txtblue"><?php echo $step ?></span>/<?php echo $total_step ?>)<?php echo __('step') ?>:<?php echo __('set_admin_account') ?></p>
<?php

// 校验数据库名：仅允许字母数字下划线，拒绝反引号等危险字符
$dbname_clean = preg_replace('/[^a-zA-Z0-9_]/', '', $dbname);
if($dbname_clean === '' || $dbname_clean !== $dbname){
	exit(__('invalid_query') . ' 数据库名称仅允许字母、数字、下划线');
}

$_l = @new mysqli($dbhost, $dbuser, $dbpasswd);
if($_l->connect_error) {
	exit(__('could_not_connect') . ' ' . htmlspecialchars($_l->connect_error, ENT_QUOTES, 'UTF-8'));
}
if(!@$_l->select_db($dbname_clean)){
	if(!$_l->query("CREATE DATABASE IF NOT EXISTS `".$dbname_clean."` DEFAULT CHARACTER SET utf8;")) {
		exit(__('invalid_query') . ' ' . htmlspecialchars($_l->error, ENT_QUOTES, 'UTF-8'));
	}
}
$_l->close();

// 写配置文件前对用户输入做 addslashes 转义，防止 PHP 代码注入
$dbhost_e  = addslashes($dbhost);
$dbname_e  = addslashes($dbname_clean);
$dbuser_e  = addslashes($dbuser);
$dbpasswd_e= addslashes($dbpasswd);

if(is_writable($config_file)) {

	$str = "<?php".LF.LF;
	$str .= "\$configs = array(".LF.LF;
	$str .= "\t'dbhost' => '$dbhost_e',".LF.LF;
	$str .= "\t'dbname' => '$dbname_e',".LF.LF;
	$str .= "\t'dbuser' => '$dbuser_e',".LF.LF;
	$str .= "\t'dbpasswd' => '$dbpasswd_e',".LF.LF;
	$str .= "\t'pconnect' => 0,".LF.LF;
	$str .= "\t'tpf' => 'epan_',".LF.LF;
	$str .= "\t'charset' => '$charset',".LF.LF;
	$str .= "\t'debug' => '0',".LF.LF;
	$str .= ");".LF.LF;
	$str .= "define('ADMINCP','admincp');".LF;
	$str .= "?>".LF;


	$fp = fopen($config_file,'w');
	if (!$fp) {
		exit("Can not open file <b>$config_file</b> .");
	}
	if(is_writable($config_file)){
		if(@fwrite($fp,$str)){
			$msg .= "<font color=blue>{$config_file} ".__('write_success')."</font>";
		}else{
			$msg .= "<font color=red>{$config_file} ".__('write_failture')."</font>";
			$flag = true;
		}
		@fclose($fp);
	}else{
		$msg .= "<font color=red>{$config_file} ".__('cant_write')."</font>";
		$flag = true;
	}
	echo $msg;
	if($flag){
		exit;
	}
}

require_once $config_file;

$mysqli = @new mysqli($configs['dbhost'], $configs['dbuser'], $configs['dbpasswd'], $configs['dbname']);
if($mysqli->connect_error){
	exit(__('could_not_connect') . ' ' . htmlspecialchars($mysqli->connect_error, ENT_QUOTES, 'UTF-8'));
}
$mysqli->set_charset('utf8');
unset($configs['dbhost'],$configs['dbuser'],$configs['dbpasswd']);
$tpf = $configs['tpf'];

$msg = '';
$error = false;
$curr_os = PHP_OS;
$curr_php_version = PHP_VERSION;
if($curr_php_version < '4.0.6') {
	$msg .= "<font color=\"#FF0000\">".__('php_version_too_lower')."</font><br />";
	$error = true;
}

$query = $mysqli->query("SELECT VERSION()");
$row = $query ? $query->fetch_row() : null;
$curr_mysql_version = $row ? $row[0] : '0';
if($curr_mysql_version < '4.1') {
	$msg .= "<font color=\"#FF0000\">".__('mysql_version_too_lower')."</font><br />";
	$error = true;
}

// 检查 users 表是否已存在（判断是否重复安装）
$chk_users = $mysqli->query("SELECT COUNT(*) FROM `{$tpf}users`");
if($chk_users !== false) {
	$msg .= "<font color=\"#FF0000\">".__('database_already_exists')."</font><br />";
	$alert = " onclick=\"return confirm('".__('database_confirm')."');\"";
} else {
	$alert = '';
}

if($error) {
	$msg .= "<font color=\"#FF0000\">".__('file_not_chmod')."</font>";
} else {
	$msg .= __('server_env_ok');
}
if ($msg) {
	echo "<p>".$msg."</p>";
}
if($error) {
?>
    <p align="right">
      <input type="button" class="formbutton" value="<?php echo __('quit') ?>" onclick="javascript: window.close();">
    </p>
<?php
} else {
?>
    <p>&nbsp;</p>
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="30%" nowrap><?php echo __('username') ?>:</td>
        <td><input type="text" value="admin" name="username" class="formfield" style="width:150px"></td>
      </tr>
      <tr>
        <td width="30%" nowrap><?php echo __('password') ?>:</td>
        <td><input type="password" value="" name="password" class="formfield" style="width:150px"></td>
      </tr>
      <tr>
        <td width="30%" nowrap><?php echo __('confirm_password') ?>:</td>
        <td><input type="password" value="" name="confirmpassword" class="formfield" style="width:150px"></td>
      </tr>
      <tr>
        <td width="30%" nowrap><?php echo __('email') ?>:</td>
        <td><input type="text" value="admin@haic.cc" name="email" class="formfield" style="width:150px"></td>
      </tr>
    </table>
	<script language="JavaScript" type="text/javascript">
	document.myform.username.focus();
	function chksubmit(o){
		if(o.username.value == ""){
			alert("<?php echo __('js_username') ?>");
			o.username.focus();
			return false;
		}
		if(o.password.value == ""){
			alert("<?php echo __('js_password') ?>");
			o.password.focus();
			return false;
		}
		if(o.password.value.length <6){
			alert("<?php echo __('js_password_too_short') ?>");
			o.password.focus();
			return false;
		}
		if(o.password.value != o.confirmpassword.value){
			alert("<?php echo __('js_confirm_password') ?>");
			o.confirmpassword.focus();
			return false;
		}
		if(o.email.value == "" || o.email.value.indexOf('@') ==-1 || o.email.value.indexOf('.') ==-1){
			alert("<?php echo __('js_email') ?>");
			o.email.focus();
			return false;
		}
		o.s1.disabled = true;
		return true;
	}
	</script>
    <p>&nbsp;</p>
    <hr noshade="noshade" />
    <p align="right">
      <input type="hidden" name="step" value="<?php echo $step+1 ?>" />
      <input class="formbutton" name="s1" type="submit" value="<?php echo __('next_step') ?>" <?php echo $alert;?> />
    </p>
<?php
}
	}
} elseif ($step == 6) {
	
	$username = trim(gpc('username','P','',1));
	$password = trim(gpc('password','P','',1));
	$confirmpassword = trim(gpc('confirmpassword','P','',1));
	$email = trim(gpc('email','P','',1));
?>
    <p class="title"><?php echo __('the') ?>(<span class="txtblue"><?php echo $step ?></span>/<?php echo $total_step ?>)<?php echo __('step') ?>:<?php echo __('check_user_info') ?></p>
<?php
if (!$username || !$password || !$confirmpassword || !$email) {
	$msg = "<p>".__('fill_all_blank')."</p>";
	$error = true;
} elseif (strlen($password) < 5) {
	$msg = "<p>".__('s_password_too_short')."</p>";
	$error = true;
} elseif ($password != $confirmpassword) {
	$msg = "<p>".__('s_password_not_same')."</p>";
	$error = true;
} elseif (strlen($email) <6 || strpos($email,'@') ===false){
	$msg = "<p>".__('s_email')."</p>";
	$error = false;
} else {
	$msg = "<p>".__('s_check_server_env')."</p>";
	$error = false;
}
$name_key = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#','$','(',')','%','@','+','?',';','^');
foreach($name_key as $value){
	if (strpos($username,$value) !== false){
		$msg = "<p>".__('bad_chars')."</p>";
		$error = true;
	}
}
if ($error) {
	echo $msg;
?>
    <hr noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="<?php echo __('prev_step') ?>" onclick="history.back(1)" />
    </p>
<?php
} else {
	echo $msg;
?>
    <p>&nbsp;</p>
    <p><?php echo __('username') ?>: <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');?><input type="hidden" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');?>" /></p>
    <p><?php echo __('password') ?>: <?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8');?><input type="hidden" name="password" value="<?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8');?>" /></p>
    <p><?php echo __('email') ?>: <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8');?><input type="hidden" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8');?>" /></p>
    <p>&nbsp;</p>
    <p><?php echo __('user_info_right') ?></p>
    <hr noshade="noshade" />
    <p align="right">
      <input type="hidden" name="step" value="<?php echo $step+1 ?>" />
      <input class="formbutton" type="submit" value="<?php echo __('next_step') ?>" />
    </p>
<?php
}
} elseif ($step == 7) {
	$username = trim(gpc('username','P','',1));
	$password = trim(gpc('password','P','',1));
	$email = trim(gpc('email','P','',1));
?>
    <p class="title"><?php echo __('the') ?>(<span class="txtblue"><?php echo $step ?></span>/<?php echo $total_step ?>)<?php echo __('step') ?>:<?php echo __('import_data') ?></p>
	<p>
<?php
require_once $config_file;

$mysqli = @new mysqli($configs['dbhost'], $configs['dbuser'], $configs['dbpasswd'], $configs['dbname']);
if($mysqli->connect_error){
	exit(__('could_not_connect') . ' ' . htmlspecialchars($mysqli->connect_error, ENT_QUOTES, 'UTF-8'));
}
$mysqli->set_charset('utf8');
unset($configs['dbhost'],$configs['dbuser'],$configs['dbpasswd']);
$tpf = $configs['tpf'];
$db = $mysqli; // 供 runquery() 全局函数使用

$sql = preg_replace("/\-\-.*[\r\n]+/i","",trim($sql));
runquery($sql);

$username_e = $mysqli->real_escape_string($username);
$password_e = password_hash($password, PASSWORD_BCRYPT);
$email_e    = $mysqli->real_escape_string($email);
$reg_ip_e   = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
$mysqli->query("INSERT INTO `{$tpf}users` (`username`, `password`, `email`, `gid`, `reg_time`, `reg_ip`) VALUES ('".$username_e."', '".$password_e."', '".$email_e."', 1, ".time().", '".$reg_ip_e."');");
//需求6：安装时管理员账户同步创建 userdata 空间行，并标记 is_Administrator=1（仅此账号允许登录 admin 后台）
$mysqli->query("INSERT INTO `{$tpf}userdata` (`user`, `setting`, `diskLevel`, `Expire_time`, `is_Administrator`) VALUES ('".$username_e."', '0', 5, 0, 1);");
?>
    </p>
    <p><?php echo __('all_create') ?><?php echo $tablenum;?> <?php echo __('tables') ?>.</p>
    <hr noshade="noshade" />
    <p><?php echo __('base_settings') ?>：</p>
	<?php 
	$arr = explode('install',$_SERVER['REQUEST_URI']);
	$port = $_SERVER['SERVER_PORT']=='80' ? '' : ':'.$_SERVER['SERVER_PORT'];
	$phpdisk_url = 'http://'.$_SERVER['SERVER_NAME'].$port.$arr[0];
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="15%" nowrap><?php echo __('site_title') ?>:</td>
        <td><input name="site_title" type="text" class="formfield" style="width:200px" maxlength="50"></td>
      </tr>
      <tr>
        <td width="15%" nowrap><?php echo __('phpdisk_url') ?>:</td>
        <td><input type="text" value="<?php echo htmlspecialchars($phpdisk_url, ENT_QUOTES, 'UTF-8'); ?>" name="phpdisk_url" class="formfield" style="width:300px" maxlength="50"> <?php echo __('phpdisk_url_tips') ?></td>
      </tr>
      <tr>
        <td width="15%" nowrap><?php echo __('encrypt_key') ?>:</td>
        <td><input type="text" id="encrypt_key" name="encrypt_key" value="" class="formfield" maxlength="16"/>&nbsp;<a href="###" onclick="make_key();"><?php echo __('reflash_key') ?></a></td>
      </tr>
      <tr>
        <td width="15%" nowrap><?php echo __('open_gzip') ?>:</td>
        <td>
        <?php if(function_exists('ob_gzhandler')){?>
        <input type="radio" name="gzipcompress" value="1" checked="checked" /><?php echo __('yes') ?>
		<input type="radio" name="gzipcompress" value="0" /><?php echo __('no') ?>
		<?php }else{?>
		<span class="txtred"><?php echo __('gzipcompress_not_support') ?></span>
		<?php }?>
		</td>
      </tr>
	</table>  
    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');?>" />
    <input type="hidden" name="password" value="<?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8');?>" />
	<script language="javascript">
	make_key();
	function chksubmit(o){
		if(o.site_title.value == ""){
			alert("<?php echo __('js_site_title') ?>");
			o.site_title.focus();
			return false;
		}
		if(o.phpdisk_url.value == ""){
			alert("<?php echo __('js_phpdisk_url') ?>");
			o.phpdisk_url.focus();
			return false;
		}
		if(o.encrypt_key.value == ""){
			alert("<?php echo __('js_encrypt_key') ?>");
			o.encrypt_key.focus();
			return false;
		}
	}
	function make_key(){
		var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		var tmp = "";
		var code = "";
		for(var i=0;i<12;i++){
			code += chars.charAt(Math.ceil(Math.random()*100000000)%chars.length);
		}
		document.getElementById('encrypt_key').value = code;
	}
	</script>
    <p align="right">
      <input type="hidden" name="step" value="<?php echo $step+1 ?>" />
      <input class="formbutton" type="submit" value="<?php echo __('next_step') ?>" />
    </p>
<?php 
}elseif($step ==8){
	require_once $config_file;

	$mysqli = @new mysqli($configs['dbhost'], $configs['dbuser'], $configs['dbpasswd'], $configs['dbname']);
	if($mysqli->connect_error){
		exit(__('could_not_connect') . ' ' . htmlspecialchars($mysqli->connect_error, ENT_QUOTES, 'UTF-8'));
	}
	$mysqli->set_charset('utf8');
	unset($configs['dbhost'],$configs['dbuser'],$configs['dbpasswd']);
	$tpf = $configs['tpf'];
	$db = $mysqli;

	$site_title = trim(gpc('site_title','P','',1));
	$phpdisk_url = trim(gpc('phpdisk_url','P','',1));
	$gzipcompress = trim(gpc('gzipcompress','P','',1));
	$encrypt_key = trim(gpc('encrypt_key','P','',1));
	$username = trim(gpc('username','P','',1));
	$password = trim(gpc('password','P','',1));

	// 校验 encrypt_key 仅允许字母数字
	if(!preg_match('/^[a-zA-Z0-9]{1,16}$/', $encrypt_key)){
		$encrypt_key = substr(preg_replace('/[^a-zA-Z0-9]/', '', $encrypt_key), 0, 16);
	}

	if (!$site_title || !$phpdisk_url || !$encrypt_key) {
		$msg = "<p>".__('site_title_and_phpdisk_url')."</p>";
		$error = true;
	}

	if ($error) {
		echo $msg;
?>
    <hr noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="<?php echo __('prev_step') ?>" onclick="javascript:history.back();" />
    </p>
<?php
	} else {
?>
    <p class="title"><?php echo __('the') ?>(<span class="txtblue"><?php echo $step ?></span>/<?php echo $total_step ?>)<?php echo __('step') ?>:<?php echo __('finished_install') ?></p>
	<p>
<?php
$settings = array(
'site_title' => $site_title,
'phpdisk_url' => $phpdisk_url,
'gzipcompress' => (int)$gzipcompress,
'encrypt_key' => $encrypt_key,
'max_file_size' => 0,
);
$setting_file = PHPDISK_ROOT.'system/settings.inc.php';
$str = $str2 = '';
foreach($settings as $k => $v){
	$k_e = $mysqli->real_escape_string($k);
	$v_e = $mysqli->real_escape_string((string)$v);
	$str2 .= "('".$k_e."','".$v_e."'),";
}
$str2 = substr($str2,0,-1);
$mysqli->query("REPLACE INTO `{$tpf}settings`(vars,value) VALUES ".$str2." ;");

$q = $mysqli->query("SELECT * FROM `{$tpf}settings`");
$str_c = '';
while($rs = $q->fetch_array()){
	// 写入 PHP 文件时转义单引号和反斜杠
	$str_c .= "\t'".addslashes($rs['vars'])."' => '".addslashes($rs['value'])."',".LF;
}
$q->free();
unset($rs);

$str = "<?php".LF.LF;
$str .= "// Cache Time: ".date("Y-m-d H:i:s").LF.LF;
$str .= "\$settings = array(".LF.LF;
$str .= $str_c;
$str .= ");".LF.LF;
$str .= "?>".LF;

$msg = '';
$fp = fopen($setting_file,'w');
if (!$fp) {
	exit("Can not open file <b>$setting_file</b> .");
}
if(is_writable($setting_file)){
	if(@fwrite($fp,$str)){
		$msg .= "<font color=blue>{$setting_file} ".__('write_success')."</font>";
	}else{
		$msg .= "<font color=red>{$setting_file} ".__('write_fail')."</font>";
		$flag = true;
	}
	@fclose($fp);
}else{
	$msg .= "<font color=red>{$setting_file} ".__('cannot_write')."</font>";
	$flag = true;
}
write_file(PHPDISK_ROOT.'system/install.lock','安装时间! Time:'.date("Y-m-d H:i:s"));
echo $msg;
if($flag){
	exit;
}
?>
    <hr noshade="noshade" />
    <p><?php echo __('finished_tips') ?></p>
	<p class="txtblue"><?php echo __('reinstall_tips') ?></p>
    <p><?php echo __('thanks') ?></p>
    <p>&nbsp;</p>
    <p><?php echo __('username') ?>: <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');?></p>
    <p><?php echo __('password') ?>: <?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8');?></p>
    <p>&nbsp;</p>
    <p><a href="../"><?php echo __('visit_phpdisk') ?></a></p>
    <hr noshade="noshade" />
    <p align="right"><?php echo __('phpdisk_official_site') ?></p>
<?php
	}
}
@unlink($setting_file);
?>
  </form>
</div>
</body>
</html>
<?php

function output_txt($output_txt = 1, $output = 1) {
	if($output_txt) {
		$text = '... <font color="#0000EE">Yes</font><br />';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">No</font><br />';
		if(!$output) {
			return $text;
		}
		echo $text;
	}
}

function runquery($sql) {
	global $db, $tablenum;

	$sql = str_replace("\r", "\n", $sql);

	foreach(explode(";\n", trim($sql)) as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 26) == 'CREATE TABLE IF NOT EXISTS') {
				$name = preg_replace("/CREATE TABLE IF NOT EXISTS `([a-z0-9_]+)` .*/is", "\\1", $query);
				echo __('create_table').' '.$name.' ... <font color="#0000EE">'.__('success').'</font><br />';
				$db->query($query);
				$tablenum++;
			} else {
				$db->query($query);
			}
		}
	}
}
function write_file($f,$str,$mode = 'wb') {
	$fp = fopen($f,$mode);
	if (!$fp) {
		exit("Can not open file <b>$f</b> .code:1");
	}
	if(is_writable($f)){
		if(!fwrite($fp,$str)){
			exit("Can not write file <b>$f</b> .code:2");
		}
	}else{
		exit("Can not write file <b>$f</b> .code:3");
	}
	fclose($fp);
}
function read_file($f) {
	if (file_exists($f)) {
		if (PHP_VERSION >= "4.3.0") return file_get_contents($f);
		$fp = fopen($f,"r");
		$fsize = filesize($f);
		$c = fread($fp, $fsize);
		fclose($fp);
		return $c;
	} else{
		exit("<b>$f</b> does not exist!");
	}
}
function dir_writeable($dir) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/phpdisk.test", 'w')) {
			@fclose($fp);
			@unlink("$dir/phpdisk.test");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

?>