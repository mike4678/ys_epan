<?php
/* ---------------------------------------------------- */
/* 程序名称: 后台验证码类（兼容 PHP 7.3）
/* 程序功能: admin/code.php 使用的验证码，原 kernl/Caption.Class.php 迁移重建
/* ---------------------------------------------------- */
class Utils_Caption {
	public $TFontSize = array(15, 17);
	public $Width = 50;
	public $Height = 20;
	private $code = '';

	function RandRSI() {
		$chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';
		$code = '';
		$len = strlen($chars);
		for ($i = 0; $i < 4; $i++) $code .= $chars[mt_rand(0, $len - 1)];
		$this->code = $code;
		return $code;
	}

	function Draw() {
		if (!function_exists('imagecreatetruecolor')) { header('Content-Type: text/plain'); echo $this->code; return; }
		header('Content-Type: image/png');
		$img = @imagecreatetruecolor($this->Width, $this->Height);
		if (!$img) { header('Content-Type: text/plain'); echo $this->code; return; }
		$bg = imagecolorallocate($img, 255, 255, 255);
		imagefill($img, 0, 0, $bg);
		$txt = imagecolorallocate($img, 30, 60, 140);
		for ($i = 0; $i < 40; $i++) {
			imagesetpixel($img, mt_rand(0, $this->Width - 1), mt_rand(0, $this->Height - 1),
				imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
		}
		imagestring($img, 5, 8, 3, $this->code, $txt);
		imagepng($img);
		imagedestroy($img);
	}
}
?>
