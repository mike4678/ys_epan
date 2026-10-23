<?php
/* ---------------------------------------------------- */
/* 程序名称: 转换模块
/* 程序功能: 后台登陆安全及密码管理核心模块
/* 程序开发: Source
/* 联系方式: 542112943@qq.com
/* Date: 1970-01-01 / 2016-09-30
/* ---------------------------------------------------- */
class Crypt             
{
	//密码哈希（新算法：bcrypt。忽略 $keycode 参数以兼容旧签名）
	/* ---------------------------------------------------- */
	public static function PwdEnc($data, $keycode = '') {
		return password_hash((string)$data, PASSWORD_BCRYPT);
	}
	/* ---------------------------------------------------- */

	//旧版自定义混淆算法（仅供 QueryCode 等非密码用途 PwdEncode 使用，不再用于密码存储）
	/* ---------------------------------------------------- */
	public static function PwdEncode($data, $keycode) {
		$x = 0;
		$key = md5($keycode);
	    $len = strlen($data);
	    $l  = strlen($key);
	    $char = ''; $str = '';
	    for ($i = 0; $i < $len; $i++)
	    {
	        if ($x == $l)
	        {
	         $x = 0;
	        }
	        $char .= $key[$x];
	        $x++;
	    }
	    for ($i = 0; $i < $len; $i++)
	    {
	        $str .= chr(ord($data[$i]) + (ord($char[$i])) % 256);
	    }
		return base64_encode($str);
	}
	/* ---------------------------------------------------- */

	//密码校验：支持 bcrypt 新哈希、旧自定义混淆、旧 md5 三种格式
	/* ---------------------------------------------------- */
	public static function PwdCheck($data, $hash) {
		if (empty($hash)) return false;
		if (password_verify((string)$data, (string)$hash)) return true;
		if (is_string($hash)) {
			if (hash_equals((string)self::PwdEncode((string)$data, 'source'), (string)$hash)) return true;
			if (strlen((string)$hash) === 32 && hash_equals(md5((string)$data), (string)$hash)) return true;
		}
		return false;
	}
	/* ---------------------------------------------------- */
	
	//解密密码
	/* ---------------------------------------------------- */
	public static function Decrypt($data, $keycode) {     
	$key = md5($data);
    $x = 50;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l) 
        {
         $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
        {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }
        else
        {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
} 
	/* ---------------------------------------------------- */
	
	//生成随机字符串
	/* ---------------------------------------------------- */
	public static function randstr($len=15)   
	{ 
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~*?'; 
	mt_srand((double)microtime() * 1000000 * getmypid() ); 
	$password = ''; 
	while( strlen( $password ) < $len) 
	$password.= substr( $chars,( mt_rand() % strlen( $chars )) , 1 ); 
	return $password; 
	}
	/* ---------------------------------------------------- */
		
	//字符串解密
	/* ---------------------------------------------------- */
	
	public static function unescape($str) 
{ 
    $ret = ''; 
    $len = strlen($str); 
    for ($i = 0; $i < $len; $i ++) 
    { 
        if ($str[$i] == '%' && $str[$i + 1] == 'u') 
        { 
            $val = hexdec(substr($str, $i + 2, 4)); 
            if ($val < 0x7f) 
                $ret .= chr($val); 
            else  
                if ($val < 0x800) 
                    $ret .= chr(0xc0 | ($val >> 6)) . 
                     chr(0x80 | ($val & 0x3f)); 
                else 
                    $ret .= chr(0xe0 | ($val >> 12)) . 
                     chr(0x80 | (($val >> 6) & 0x3f)) . 
                     chr(0x80 | ($val & 0x3f)); 
            $i += 5; 
        } else  
            if ($str[$i] == '%') 
            { 
                $ret .= urldecode(substr($str, $i, 3)); 
                $i += 2; 
            } else 
                $ret .= $str[$i]; 
    } 
    return $ret; 
}
	/* ---------------------------------------------------- */
	
	//用户名、邮箱、手机账号中间字符串以*隐藏 
	/* ---------------------------------------------------- */
	public static function hideStar($str) { 
    if (strpos($str, '@')) { 
        $email_array = explode("@", $str); 
        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀 
        $count = 0; 
        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count); 
        $rs = $prevfix . $str; 
    } else { 
        $pattern = '/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i'; 
        if (preg_match($pattern, $str)) { 
            $rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4); 
        } else { 
            $rs = substr($str, 0, 3) . "***" . substr($str, -1); 
        } 
    } 
    return $rs; 
	}
	
	/* ---------------------------------------------------- */
	
	//生成Hash码
	/* ---------------------------------------------------- */
	public static function get_hash(){
  	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()+-';
 	$random = $chars[mt_rand(0,73)].$chars[mt_rand(0,73)].$chars[mt_rand(0,73)].$chars[mt_rand(0,73)].$chars[mt_rand(0,73)];//Random 5 times
  	$content = uniqid().$random;  // 类似 5443e09c27bf4aB4uT
  	return sha1($content); 
	}
	
	/* ---------------------------------------------------- */
	
	//多维数组转换为一维数组
	/* ---------------------------------------------------- */	
	public static function arr_foreach ($arr) {
	static $data; 
	if (!is_array ($arr)) {
		return $data;
	}
	foreach ($arr as $key => $val ) {
		if (is_array ($val)) {
			self::arr_foreach ($val);
		} else {
			$data[]=$val;
		}
	}
	return $data;
	}
	
	/* ---------------------------------------------------- */
	
	//字符串长度检测
	/* ---------------------------------------------------- */	
	public static function StringlengthCheck ($String , $small , $big) {
		if ($small == NULL or $big == NULL) {
			return 0;   //返回假
			} else { if(strlen($String) >= $small) {
						if(strlen($String) <= $big) {
							return 1;
							} else { return 0; }
					} else { return 0; }
				}
	}
	
}
?>