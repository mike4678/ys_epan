<?php
$uid = 4;
$sid = 5;
if($db -> LoginCheck() != 0){
	$Message = '<span><font color=blue>您好，您的密保已经设置。如果需要修改请点<a href=\'index.php?method=xgmb\'>修改密保</a></font></span>';
	} else {
	$Message = '<font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font>';
	}
?>
        <div id="ysright">
                   <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/xg.gif" width="32" height="32" alt="" />设置密码保护</h1>
	                <?php echo $Message; ?>
                </form>
        </div>
