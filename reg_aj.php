<?php
session_start();
include "includes/Init.php";

// 服务端用户名白名单校验（与前端正则一致：3-15位，数字字母-）
function _reg_check_username($u) {
	return preg_match('/^[0-9a-zA-Z][0-9a-zA-Z\-]{1,13}[0-9a-zA-Z]$/', $u) === 1;
}

switch ($_GET['lx']){

	case 'jcdlmc':     //用户名检查
	$username = $db -> query("select username from epan_users where username = '". $db->escape($_GET['dlmc']) ."';") ;
	if($db -> affected_rows() != NULL)
	{ echo "<img src='images/reg_f.gif' /><font color='red'>此用户名已存在</font>";
	  break;
	} else {
		echo "<img src='images/reg_t.gif' /><font color='green'>此用户名可用</font>";
		break;
		}


	case 'yzm':      //验证码部分
	if (Checkcode($_GET['yzm']) == 0)
	{
		unset($_SESSION['_verycode']);
		echo "<img src='images/reg_f.gif' />";   //验证码错误
		break;
	 	} else {
			echo "<img src='images/reg_t.gif' />";} //验证码正确
			break;


	case 'tj1':     //提交第一个页面的请求，如果没有错误则存入临时缓存中
	//1.首先检查用户名
	if (!_reg_check_username($_GET['dlmc'])) {
		echo "用户名不合法";
		break;
	}
	$username = $db -> query("select username from epan_users where username = '". $db->escape($_GET['dlmc']) ."';") ;
	if($db -> affected_rows() != NULL)
	{ echo "此用户名已存在";
	  break;
	} else {
	//2.检查验证码
			if (Checkcode($_GET['yzm']) == 0)
			{
				unset($_SESSION['_verycode']);
				echo "验证码错误";   //验证码错误
				break;
	 			}
	}
	break;


	case 'tj3':    //提交第三个页面的请求，如果没有错误则存入临时缓存中
	if (!_reg_check_username($_GET['dlmc'])) {
		echo "n";
		break;
	}
	// ===== 开放注册开关 + 禁用用户名名单（需求3-5）=====
	if ($db->Info('reg_open') === '0') {
		echo "注册已关闭，暂不接受新用户注册";
		break;
	}
	$bannedRaw = (string)$db->Info('reg_banned_users');
	if ($bannedRaw !== '') {
		$banned = array();
		foreach (explode(',', $bannedRaw) as $b) {
			$b = trim($b);
			if ($b !== '') { $banned[strtolower($b)] = true; }
		}
		if (isset($banned[strtolower($_GET['dlmc'])])) {
			echo "该用户名不允许注册";
			break;
		}
	}

	$username = $db -> query("select username from epan_users where username = '". $db->escape($_GET['dlmc']) ."';") ;
	if($db -> affected_rows() != NULL)
	{
			echo "n";    //用户名重复
			break;
		}

		$sid = mysqli_fetch_array($db->query('select max(userid) from epan_users')) ;
		$count = $sid[0] + 1;
		$safe_dlmc = $db->escape($_GET['dlmc']);
		$safe_mm = $db->escape(Crypt::PwdEnc($_GET['mm'], 'source'));
		$safe_emqr = $db->escape($_GET['emqr']);
		$safe_wen1 = $db->escape(Crypt::unescape($_GET['wen1']));
		$safe_da1qr = $db->escape(Crypt::unescape($_GET['da1qr']));
		$safe_wen2 = $db->escape(Crypt::unescape($_GET['wen2']));
		$safe_da2qr = $db->escape(Crypt::unescape($_GET['da2qr']));
		$db->query('INSERT INTO `epan_users` (`userid`, `username`, `password`, `email`, `is_activated`,`last_login_ip`,  `reg_time`,`reg_ip`, `reset_code`, `user_file_types`, `user_store_space`, `user_rent_space`, `space_day_credits`, `max_flow_down`, `max_flow_view`, `wen1`, `daan1`, `wen2`, `daan2`) VALUES ('. $count.', "'.$safe_dlmc.'", "'.$safe_mm.'", "'.$safe_emqr.'", \'0\',\'unknown\', '.TS.',"'.GetHostByName($_SERVER['SERVER_NAME']).'", \'\', \'\', \'\', \'\', 0, \'0\', \'0\', "'. $safe_wen1 .'" , "'. $safe_da1qr .'" , "'. $safe_wen2 .'" ,"'. $safe_da2qr .'" )'); //都没有错误则将数据写入数据库
		if ($db->affected_rows() > 0)
		{
			// ===== 任务3：注册同时创建空间数据记录（epan_userdata），diskLevel=所选空间级别 code =====
			$jb = (isset($_GET['jb']) && is_numeric($_GET['jb'])) ? (int)$_GET['jb'] : 0;
			$db->query('INSERT INTO `epan_userdata` (`user`, `setting`, `diskLevel`, `Expire_time`, `is_Administrator`) VALUES ("'.$safe_dlmc.'", "0", '.$jb.', 0, 0)');
			// ===== 需求6：注册同时创建空间行（epan_space），保证空间标题/语言等设置可写 =====
			$db->query('INSERT INTO `epan_space` (username) SELECT "'.$safe_dlmc.'" WHERE NOT EXISTS (SELECT 1 FROM epan_space WHERE username = "'.$safe_dlmc.'")');
			// ===== 任务2：注册即登录——把当前会话切换到新用户，ht/ 后台不再默认显示旧用户 =====
			session_regenerate_id(true);
			$_SESSION['username_epan'] = $safe_dlmc;
			$_SESSION['QueryCode'] = '';
			echo "y";
			break;
			} else {
				echo "n";
				break;
				}
		}

function Checkcode($code) {
	if (!$code || strtolower($code) != strtolower($_SESSION['_verycode']))
	{
		return 0;
	 	} else { return 1; }

	}

?>
