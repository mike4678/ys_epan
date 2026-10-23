<?php
$uid = 3;
$sid = 2;
if($db -> LoginCheck() != 0){
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1">';
	$Lang = (int)$db -> SpaceSetting($_SESSION['username_epan'],'Language');
	$title = 'document.getElementsByName("rsd")['.$Lang.'].checked="true";';
	} else {
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1" disabled="disabled"><font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font>';
	$title = '<input name="t1" type="text" id="t1" disabled="disabled" style="width:500px;" />';
	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$rsd = isset($_POST['rsd']) && is_numeric($_POST['rsd']) ? (int)$_POST['rsd'] : -1;
	if($rsd >= 0 && $rsd <= 2 && $db ->SpaceUpdate($_SESSION['username_epan'] , 'Language' ,  $rsd) != 0) {
		$db ->message('更新成功！');
		} else { $db ->message('更新失败！'); }
	}
?>
<script> function $(rid) { return document.getElementById(rid); } </script>
<script>
 document.onreadystatechange = function () {
        if (document.readyState == "complete") {
			<?php echo $title; ?>
        }
    }
</script>
<div id="ysright">
                   <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />设置空间语言</h1>
            <div class="ysdb2">
            <form name="ctl00" method="post" action="?method=cg_yy" id="ctl00">
                    <table>
                    <tr>
                        <td>
                            <p>空间语言</p>
                            <div style="cursor:hand;" onclick="document.forms[0].p1.checked=true">
		                        <input value="0" name="rsd" type="radio" id="p1" />中文（空间界面以中文语言显示）
                            </div>
		                    <br />
                            <div style="cursor:hand;margin-top:-10px;" onclick="document.forms[0].p2.checked=true">
		                        <input value="1" name="rsd" type="radio" id="p2" />英文（空间界面以英文语言显示）
                            </div>
                            <br />
                            <div style="cursor:hand;margin-top:-10px;" onclick="document.forms[0].p3.checked=true">
		                        <input value="2" name="rsd" type="radio" id="p3" />日文（空间界面以日文语言显示）
                            </div>
                        </td>
                    </tr>
                </table>
            <p>
                <?php echo $bottom;?>
            </p>
            <ul class="yslb2">
                <li>您可以在这里设置空间的显示语言。</li>
            </ul>
            </form>
            </div>
        </div>
