<?php
/* ---------------------------------------------------- */
/* 程序名称: admin 后台账号安全模块（兼容 PHP 7.3）
/* 程序功能: 后台登录密码加密/校验，算法与 zd_admin_user 存储哈希一致
/* ---------------------------------------------------- */
class Account {
	//密码加密（旧自定义混淆算法：md5(key) 循环异或 + base64）
	public static function PwdEnc($data, $keycode = '') {
		$key = md5((string)$keycode);
		$len = strlen((string)$data);
		$l = strlen($key);
		$x = 0;
		$char = '';
		for ($i = 0; $i < $len; $i++) {
			$char .= $key[$x];
			$x++;
			if ($x == $l) $x = 0;
		}
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$str .= chr(ord($data[$i]) + (ord($char[$i])) % 256);
		}
		return base64_encode($str);
	}

	//密码校验
	public static function PwdCheck($data, $hash, $keycode = '') {
		return hash_equals((string)self::PwdEnc($data, $keycode), (string)$hash);
	}
}
?>
