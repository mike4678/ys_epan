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
 
$temp_file = '../../images/tmplogo.png';
$fp = fopen($temp_file,"w");
switch($_GET['m'])
{
	case 'logosetting':
		fwrite($fp,$db->SInfo('softwarelogo'));
		break;
		
	case 'softlogo':
		fwrite($fp,$db->SInfo('softwarelogo'));
	
	case 'softrunimg':
}
fclose($fp);
//header("Content-type: image/jpeg");
//file_get_contents($temp_file);
?>
<img src="../../images/tmplogo.png">
