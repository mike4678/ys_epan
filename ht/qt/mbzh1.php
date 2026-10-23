<?php
$uid = 4;
$sid = 7;

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
	if ($_POST['yhm'] != NULL) {
		header("Location: index.php?method=mbzh2&dlmc=". urlencode($_POST['yhm'])); //重定向浏览器
		exit;
		} else { $db ->message('用户名不能为空！'); }
	}
?>
<div id="ysright">
    <h1><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />重置密码</h1>
    <div class="ysdb2">
        <form name="ctl00" method="post" action="?method=mbzh1" id="ctl00">
        <p style="text-align:center; margin-top:100px;">请输入您的用户名：<input name="yhm" type="text" id="yhm" style="width:130px;" /></p>
        
            <div style=" display:none;"><input name="TextBox1" type="text" id="TextBox1" style="width:130px;" /></div>
        <p style="text-align:center; margin-top:50px;"><input type="submit" name="bu2" value=" 下一步 " id="bu2" /></p>
        </form>
    </div>
</div>
