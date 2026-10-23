<?php

session_start();
$verycode_type = isset($_GET['verycode_type']) ? (int)$_GET['verycode_type'] : 0;
$nosess = isset($_GET['nosess']) ? (int)$_GET['nosess'] : 0;

// GD 扩展缺失时给出明确诊断（SVG 无需 GD），避免空白/破图，便于排查
if (!function_exists('imagecreate') || !function_exists('imagettftext')) {
	header('content-type:image/svg+xml');
	header('Pragma:no-cache');
	header('Cache-Control:no-cache');
	header('Expires:0');
	echo '<svg xmlns="http://www.w3.org/2000/svg" width="140" height="40">'
		. '<rect width="100%" height="100%" fill="#fff2f2" stroke="#d33" stroke-width="1"/>'
		. '<text x="8" y="25" font-size="11" fill="#d33">验证码需启用 PHP GD 扩展</text></svg>';
	exit;
}

switch($verycode_type){
	case 2:
		$code_length = 4;
		$w = 58;
		$h = 22;
		$fontsize = 12;
		$seed_str = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$_verycode = "";

		$im = imagecreate($w, $h);

		$bgcolor = imagecolorallocate($im, 255, 255, 255);
		imagefill($im, 0, 0, $bgcolor);

		$bordercolor = imagecolorallocate($im, 150, 150, 150);
		imagerectangle($im, 0, 0, $w-1, $h-1, $bordercolor);
		
			$lineColor1 = imagecolorallocate($im, mt_rand(174,218),mt_rand(190,225),mt_rand(217,237));
			for($j=1;$j<=2;$j=$j+3){
				for($ii=0;$ii<10;$ii++){
					imageline($im,0,$j+mt_rand(1,30),80,$j+mt_rand(1,30),$lineColor1);
				}
			}
			
		for($i=0; $i<$code_length; $i++){
			$temp = mt_rand(0, strlen($seed_str)-1);
			$code = substr($seed_str, $temp, 1);
			$j = !$i ? 4 : $j+12;
			$color3 = imagecolorallocate($im, mt_rand(0,255),mt_rand(0,120),mt_rand(0,100));
			imagettftext($im, $fontsize, 0, $j, 15, $color3, dirname(__FILE__).'/fonts/lsansdi.ttf', $code);
			$_verycode .= $code;
		}

		if(!$nosess){
			$_SESSION['_verycode'] = $_verycode;
		}
		if (ob_get_length() !== false) { ob_end_clean(); }
		header("Pragma:no-cache");
		header("Cache-Control:no-cache");
		header("Expires:0");

		if(function_exists("imagepng")){
			header("content-type:image/png");
			imagepng($im);
		}elseif(function_exists("imagegif")){
			header("content-type:image/gif");
			imagepng($im);
		}else{
			header("content-type:image/jpeg");
			imagejpeg($im);
		}
		imagedestroy($im);		
		break;
	case 1:	
	default:
		$code_length = 4;
		$w = 58;
		$h = 22;
		$fontsize = 12;
		$seed_str = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$_verycode = "";

		$im = imagecreate($w, $h);

		$bgcolor = imagecolorallocate($im, 255, 255, 255);
		imagefill($im, 0, 0, $bgcolor);

		$bordercolor = imagecolorallocate($im, 150, 150, 150);
		imagerectangle($im, 0, 0, $w-1, $h-1, $bordercolor);
		
		for($i=0; $i<$code_length; $i++){
			$temp = mt_rand(0, strlen($seed_str)-1);
			$code = substr($seed_str, $temp, 1);
			$j = !$i ? 4 : $j+12;
			$color3 = imagecolorallocate($im, mt_rand(0,125),mt_rand(0,100),mt_rand(0,100));
			imagettftext($im, $fontsize, 0, $j, 15, $color3, dirname(__FILE__).'/fonts/consolas.ttf', $code);
			$_verycode .= $code;
		}

		for($i=0; $i<$code_length*40; $i++){
			$color2 = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
			imagesetpixel($im, mt_rand(0,$w), mt_rand(0,$h), $color2);
		}
		if(!$nosess){
			$_SESSION['_verycode'] = $_verycode;
		}
		if (ob_get_length() !== false) { ob_end_clean(); }
		header("Pragma:no-cache");
		header("Cache-Control:no-cache");
		header("Expires:0");

		if(function_exists("imagepng")){
			header("content-type:image/png");
			imagepng($im);
		}elseif(function_exists("imagegif")){
			header("content-type:image/gif");
			imagepng($im);
		}else{
			header("content-type:image/jpeg");
			imagejpeg($im);
		}
		imagedestroy($im);
}
?>