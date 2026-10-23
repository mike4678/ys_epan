<?php
/* ---------------------------------------------------- */
/* 程序名称: ConCurrent OnLine statistics 
/* 程序功能: 统计同时在线浏览的人数 
/* 程序修改: Source
/* 联系方式: 542112943@qq.com
/* Date: 1970-01-01 / 2017-02-01
/* ---------------------------------------------------- */
/* 使用条款:
/* 1.该软件免费使用.
/* 2.禁止任何衍生版本.
/* ---------------------------------------------------- */

include "../includes/Init.php";

//统计在线人数
$ip = $db -> escape($db -> GetClientIP()); //获取客户端IP (直接query用，已转义)
$_user = isset($_GET['user']) ? $_GET['user'] : ''; //原始值；SQL转义由System方法内部负责，直接query处单独escape
$time = TS; 
//查询表中是否有ip为当前访客IP的记录 
$query = $db ->query("select id from epan_OnlineUser where ip = '$ip' AND username = '".$db->escape($_user)."'"); 
if($db ->affected_rows() == NULL){//如果不存在访客IP 
  //将访客信息插入到数据表中 
  $db ->query("insert into epan_OnlineUser (ip,dtstamp,username) values ('".$ip."','".$time."','".$db->escape($_user)."')"); 
}else{//如果存在，则更新该用户访问时间 
  $db ->query("update epan_OnlineUser set dtstamp = '$time' where ip ='$ip' AND username = '".$db->escape($_user)."'"); 
} 
//删除已过期的记录 
$outtime = $time - 180; 
$db ->query("delete from epan_OnlineUser where dtstamp < $outtime AND username = '".$db->escape($_user)."'"); 
//统计总记录数，即在线用户数 
list($totalOnline) = $db ->fetch_array($db ->query("select count(*) from epan_OnlineUser where username = '".$db->escape($_user)."'")); 

//判断是否登录
if($db ->SpaceSetting($_user, 'login') == NULL) { // 由后台生成，一串特定
	$status = 0;
} else {
	if( base64_decode($db -> SpaceSetting($_user,'login')) + 900 < TS ) {  //15分钟后没有动作自动超时
		$status = 0;
		} else { $status = 1; }
	}

//获取已使用空间
$UseSpace = $db->fetch_array($db -> query('select SUM(file_size) from epan_files where userid = (select userid from epan_users where username = "'.$db->escape($_user).'");'));
$disk = round($db->UserSpaceSize($_user) - ($UseSpace[0] / 1024 /1024), 2);   //总空间数(MB)

echo '{zx:"'.(int)$totalOnline.'",gl:"'.(int)$status.'",ll:"0",sy:"'.(float)$disk.'",gx:"",wh:""}';  //sy:可用空间    zx:在线人数
?> 