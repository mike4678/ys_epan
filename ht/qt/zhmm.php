<?php
$uid = 4;
$sid = 7;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($_POST['bu3'] != NULL) {
		header("Location: index.php?method=zhmm2"); //重定向浏览器
		exit;
		}
	if ($_POST['bu1'] != NULL)
	{
		header("Location: index.php?method=yjzh"); //重定向浏览器
		exit;
		}
	if ($_POST['bu2'] != NULL)
	{
		header("Location: index.php?method=mbzh1"); //重定向浏览器
		exit;
		}
	}
?>
<div id="ysright">
    <h1><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />重置密码</h1>
    <div class="ysdb2">
        <form name="ctl00" method="post" action="?method=zhmm" id="ctl00">
        <p style="text-align:center; margin-top:130px;"><input type="submit" name="bu2" value="  通过密保问题重置密码  " id="bu2" style="height:32px;" /></p>
        <p style="text-align:center; margin-top :70px;"><input type="submit" name="bu1" value=" 通过设置的邮箱重置密码 " id="bu1" style="height:32px;" /></p>
        <p style="text-align:center; margin-top :70px;"><input type="submit" name="bu3" value="    以上2种均未成功     " id="bu3" style="height:32px;" /></p>
        </form>
    </div>
</div>
