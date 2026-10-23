<?php
include "../includes/Init.php";

$dlmc = isset($_GET['dlmc']) ? trim($_GET['dlmc']) : '';

if($dlmc == "") {
	echo "<br /><font color='red'>请输入您的用户名</font>";
} else {
	// 用户名白名单校验：仅允许数字、字母、短横线，最长15字符
	if(!preg_match('/^[a-zA-Z0-9\-]{1,15}$/', $dlmc)) {
		echo "<br /><font color='red'>用户名格式不正确</font>";
		exit;
	}
	$dlmc_esc = $db->escape($dlmc);
	$db->query("select * From epan_users where username='".$dlmc_esc."';");
	$rs = $db->affected_rows();
		if($rs != "") {
			$dlmc_out = htmlspecialchars($dlmc, ENT_QUOTES, 'UTF-8');
			$url_out = htmlspecialchars($settings['phpdisk_url'], ENT_QUOTES, 'UTF-8');
			echo "<br /><font color='green'>您的空间地址是:".$url_out."k.php?name=".$dlmc_out."</font>";
			} else {
				echo "<br /><font color='red'>不存在此用户</font>";
				}
	}

?>
