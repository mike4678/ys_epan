<?php
$uid = 3;
$sid = 7;

if($db -> LoginCheck() != 0){
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1">';
	} else {
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1" disabled="disabled"><font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font>';
	}

if($db -> SpaceSetting($_SESSION['username_epan'],'style') != NULL) {
	$count = (int)($db -> SpaceSetting($_SESSION['username_epan'],'style')) - 1;
	$load = 'document.getElementsByName("rsd")['. $count .'].checked="checked";';
	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(isset($_POST['rsd']) && is_numeric($_POST['rsd'])) {
		$rsd = (int)$_POST['rsd'];
		if($rsd >= 1 && $rsd <= 6 && $db ->SpaceUpdate($_SESSION['username_epan'] , 'style' , $rsd) != 0) {
			$db ->message('更新成功！');
			} else { $db ->message('更新失败！'); }
	} else { $db ->message('参数错误！'); }
	}
?>
<script> function $(rid) { return document.getElementById(rid); } </script>
<script>
 document.onreadystatechange = function () {
        if (document.readyState == "complete") {
			<?php echo $load; ?>
        }
    }
</script>
<div id="ysright">
                   <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/ys168_sd_cg.gif" width="32" height="32" alt="" />选择空间风格</h1>
            <div class="ysdb3">
            <form name="ctl00" method="post" action="?method=fg" id="ctl00">
                                <table border="0" width="48%" cellpadding="2" cellspacing="1">
	                <tr>
		                <td>
		                    <p align="center">
		                    <img border="0" style="cursor:hand;" src="/ht/images/fg_st_1.gif" width="100" height="66" onclick="document.forms[0].p1.checked=true" alt="" />
		                    </p>
		                </td>
		                <td>
						    <p align="center">
						    <img border="0" style="cursor:hand;" src="/ht/images/fg_st_2.gif" width="100" height="66" onclick="document.forms[0].p2.checked=true" alt="" />
						    </p>
						</td>
	                <td>
						    <p align="center">
						    <img border="0" style="cursor:hand;" src="/ht/images/fg_st_3.gif" width="100" height="66" onclick="document.forms[0].p3.checked=true" alt="" />
						    </p>
						</td>
	                </tr>
	                <tr>
		                <td align="center"><input value="1" name="rsd" type="radio" id="p1" /></td>
		                <td align="center"><input value="2" name="rsd" type="radio" id="p2" /></td>
		                <td align="center"><input value="3" name="rsd" type="radio" id="p3" /></td>
	                </tr>
	                <tr>
		                <td align="center"><img border="0" style="cursor:hand;" src="/ht/images/fg_st_4.gif" width="100" height="66" onclick="document.forms[0].p4.checked=true" alt="" /></td>
		                <td align="center"><img style="cursor:hand;" border="0" src="/ht/images/fg_st_5.gif" width="100" height="66" onclick="document.forms[0].p5.checked=true" alt="" /></td>
		                <td align="center"><img style="cursor:hand;" border="0" src="/ht/images/fg_st_6.gif" width="100" height="66" onclick="document.forms[0].p6.checked=true" alt="" /></td>
	                </tr>
	                <tr>
		                <td align="center"><input value="4" name="rsd" type="radio" id="p4" /></td>
		                <td align="center"><input value="5" name="rsd" type="radio" id="p5" /></td>
		                <td align="center"><input value="6" name="rsd" type="radio" id="p6" /></td>
	                </tr>
	            </table>
            <p>
                <?php echo $bottom;?>
            </p>
            </form>
            <ul class="yslb2">
            <li>在此处可以选择您喜欢的页面风格。</li>
            </ul>
        </div>
