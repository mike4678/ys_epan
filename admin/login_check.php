<?php 
//载入函数PHP
require("../includes/AdminInit.php"); //初始化基础参数
$db -> WSCS_Check();
//来路判断


//初始化变量
$method = $_SERVER['REQUEST_METHOD']; //操作方式，post/GET
$addr = $_SERVER['PHP_SELF'].'?'.file_get_contents('php://input'); //提交地址
$username = $_POST['username'];  //用户名
$PWD = $_POST['password'];  //密码
$ref = empty($_POST['return']) ? '' : $_POST['return']; //跳转地址

if(!isset($_COOKIE["SourceTryCount"]) || $_COOKIE["SourceTryCount"] == NULL) 
{
	$db->cookie("SourceTryCount", 0, time()+36000);	
	
} elseif($_COOKIE["SourceTryCount"] >= $_G['Login']['MaxNumber']) {
	
	echo json_encode(array('status'=>'-1','message'=>'超过最大尝试次数！'));
	exit;
	
}

if ($db -> Info('VaildCode') == 0)   //启用验证码
{
	session_start(); //初始化验证码Session
	if(empty($_POST['code']) || $_POST['code'] != $_SESSION["VaildCode"])
	{
		$data = $_COOKIE["SourceTryCount"] + 1;
		$db->cookie("SourceTryCount", $data , time()+36000);
		if(function_exists('epan_oplog')) epan_oplog('login','admin fail user='.$username.' reason=bad_captcha');
		echo json_encode(array('status'=>'-1','message'=>'验证码错误'));
		exit;
	}
	session_destroy(); //验证码Session销毁
} 


	//判断最基本的两个值是否为空
if (empty($username) || empty($PWD) )  //判断POST回来的用户名或密码是否为空
{ 
	$data = $_COOKIE["SourceTryCount"] + 1;
	$db->cookie("SourceTryCount", $data , time()+36000);
	if(function_exists('epan_oplog')) epan_oplog('login','admin fail user='.$username.' reason=empty');
	echo json_encode(array('status'=>'-2','message'=>'用户名或密码不能为空'));
	exit;
} 

//需求6：账户与加密方式同 ht（epan_users + bcrypt），仅允许 is_Administrator=1 的用户登录后台
if ($db -> UserCheck($username) == 0) {
	$data = $_COOKIE["SourceTryCount"] + 1;
	$db->cookie("SourceTryCount", $data , time()+36000);
	if(function_exists('epan_oplog')) epan_oplog('login','admin fail user='.$username.' reason=no_user');
	echo json_encode(array('status'=>'-3','message'=>'用户名不存在'));
	exit;
}
$__ar = $db->fetch_array($db->query("select is_Administrator from epan_userdata where user='".$db->escape($username)."'"));
if (!$__ar || (int)$__ar['is_Administrator'] != 1) {
	$data = $_COOKIE["SourceTryCount"] + 1;
	$db->cookie("SourceTryCount", $data , time()+36000);
	if(function_exists('epan_oplog')) epan_oplog('login','admin fail user='.$username.' reason=not_admin');
	echo json_encode(array('status'=>'-3','message'=>'无权限登录后台'));
	exit;
}
if ($db -> PWDCheck($username, $PWD) == 0) {
	$data = $_COOKIE["SourceTryCount"] + 1;
	$db->cookie("SourceTryCount", $data , time()+36000);
	if(function_exists('epan_oplog')) epan_oplog('login','admin fail user='.$username.' reason=bad_password');
	echo json_encode(array('status'=>'-3','message'=>'密码错误'));
	$db -> WriteLog('POST', '用户尝试登陆，但密码错误！','Login.php');
	exit;
}

if (session_status() === PHP_SESSION_NONE) session_start(); //标志Session的开始 
$_COOKIE["SourceTryCount"] = 0;
$iipp = $_G['IPS']['ClientIP']; //获取登录者ip
$time = time(); //登录时间（epan_users.last_login_time 为 int 时间戳；旧代码用 constant("Time") 取日期字符串，写入 int 列会失真）
// 记住登录 cookie：存库内 bcrypt 哈希快照（旧代码 Crypt::PwdEnc 每次随机盐，与库中哈希永远不匹配，是跳回 login.php 的根因之一）
$pwrow = $db->fetch_array($db->query("select password from epan_users where username='".$db->escape($username)."'"));
$passcode = $pwrow ? $pwrow['password'] : '';
if(isset($_POST['save']) && $_POST['save'] == 'true')   //判断是否记住登陆信息
{
	$db->cookie("usr", $username, time()+36000);
	$db->cookie("pwd", $passcode, time()+36000); 
	$db->cookie("state", time(), time()+36000);		
}
$_SESSION['username'] = $username; 
$_SESSION['time'] = $time;
$db -> query("update epan_users set last_login_time = '$time' , last_login_ip = '$iipp' where username = '".$db->escape($username)."';");
if(function_exists('epan_oplog')) epan_oplog('login','admin success user='.$username);
echo json_encode(array('status'=>'0','message'=>'success'));

?>