<?php 
include "../../includes/Init.php";

$_user = isset($_COOKIE['User']) ? $_COOKIE['User'] : ''; //原始值；SQL转义由System方法内部负责
$_lang = preg_replace('/[^a-zA-Z0-9_\-]/', '', isset($_COOKIE['Lang']) ? $_COOKIE['Lang'] : 'zh');

$ys4 = '';
$u = $db -> LoadingGuestbook($_user,'userspace');
foreach($u as $list=>$things){
	$ys4.="<div class='sely'><input type='checkbox' name='id[]' id='".(int)$things['id']."'><b>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</b><lable style='float:right;'>". date('Y-m-d H:i:s', $things['t']) ."</lable><div style='margin-top:5px;'>".htmlspecialchars($things['data'],ENT_QUOTES,'UTF-8')."</div></div>";
	}
$Count = count($u);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.9.1.js"></script>
<script src="<?php echo PHPDISK_ROOT_WEB ?>js/artDialog.js?skin=simple"></script>
<script src="<?php echo PHPDISK_ROOT_WEB ?>js/iframeTools.js"></script>
<script src="<?php echo PHPDISK_ROOT_WEB ?>user/js/c_<?php echo $_lang; ?>.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body    {background-color:#FFFFE6;font-size:9pt;border:0px; font-family: 宋体, Century Gothic, Arial, Helvetica;}
 p	    {line-height:150%;}

 #d_lb {position:absolute; background-color:#fff;left:10px;top:10px;right:10px;bottom:77px;overflow:auto;z-index:5; border:1px solid #888;}
	#d_lb ul {padding-left:0px;margin:3px;}
		#d_lb ul li {list-style-type:none;}

.czq {position:absolute;left:10px;right:10px;bottom:0px;height:70px; }
	.czq a { color :#000;text-decoration:none ; }
		.czq a:hover { text-decoration :underline ; color:blue ;}

.sely {margin-bottom:10px;border:1px solid #aaa;padding:3px;}
.selz {border:2px solid red;}
</style>
<script type="text/javascript">
            function selectAll(checkbox) {
                $('input[type=checkbox]').prop('checked', $(checkbox).prop('checked'));
            }
        </script>
</head>
<body>
<form method="post" action="D_bjlyd.php">
	<div id="d_lb"><?php echo $ys4 ; ?></div>
	<div class="czq">
<div class="xzq">操作:<input id="b1" type="button" value="删除所选" />&nbsp;<input id="b1" type="button" value="全部删除" onclick ="del_All();" />&nbsp;<input type="button" value="关闭" onclick ="art.dialog.close();" />
            <span style="margin-left:20px; color:#555;">当前共<label id="xs_z"><?php echo $Count; ?></label>条留言</span>
        </div>  

</div>

</form>
</body>
</html>







