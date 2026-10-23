<?php
require("../../includes/AdminInit.php");
if (!defined('source'))
	header("Location: ../login.php"); //重定向浏览器
$db -> WSCS_Check();       //安全检查，防护系统
$state = $db -> AccountState();
if ($state == 'Access denied') 
{
	header("Location: ../login.php");	
} 

$sql = "TRUNCATE TABLE zd_imgdata; TRUNCATE TABLE zd_type; TRUNCATE TABLE zd_software; TRUNCATE TABLE zd_feedback; TRUNCATE TABLE zd_config;";
$outside =  explode('; ', $sql);
foreach ($outside as $x) 
{
	if($x != NULL)
	{
		if(!$db->query($x))
		{
			echo $x;
			echo 'error,' . $db->SystemError() ;
	
		} 
	}
} 
$db -> WriteLog('GET', '用户执行软件管家数据库重置，操作结果：Success!','reset.php');
echo '<script language="JavaScript">window.alert("重置完成！");history.back(-1);</script>';
?>

