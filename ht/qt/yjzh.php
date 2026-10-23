<?php
$uid = 4;
$sid = 7;
?>
<script language="javascript" type="text/javascript">
function $(id) { return document.getElementById(id); }
    function yzm() {
        var today = new Date();
        document.getElementById('imgyzm').src = "<?php echo PHPDISK_ROOT_WEB ?>/includes/imgcode.php?verycode_type=2&t="+Math.random();
        document.getElementById('teyzm').focus();
    }
	
	function writeyzm() { $("sm_yzm").innerHTML = "<font color='#f00'>请输入图片显示内容。</font>"; }
    function checkyzm() {
        if ($('teyzm').value.length != 4) 
		{ $("sm_yzm").innerHTML = "<font color='#f00'><img src='<?php echo PHPDISK_ROOT_WEB ?>/images/reg_f.gif' />请填写正确验证码。</font>"; 
		return false; } else { $("sm_yzm").innerHTML = "";  }
    }
</script>
<div id="ysright">
    <h1><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />重置密码</h1>
    <div class="ysdb2">
        <form name="ctl00" method="post" action="?method=yjzh" id="ctl00">
<table border="1" width="100%">
	                <tr>
		                <td class="tdbt">用户名:</td>
		                <td><input name="tedlmc" type="text" id="tedlmc" /></td>
	                </tr>
	                <tr>
		                <td class="tdbt">用户信箱:</td>
		                <td><input name="teem" type="text" id="teem" /></td>
	                </tr>
	                <tr>
		                <td class="tdbt">验证码:</td>
		                <td><input name="teyzm" type="text" id="teyzm" onkeypress="if (window.event.keyCode==13){bux1.click();}" onblur="checkyzm();" onfocus="writeyzm();"/>
			                <a href="javascript:yzm();"><img id="imgyzm" border="0" src="<?php echo PHPDISK_ROOT_WEB ?>/includes/imgcode.php?verycode_type=2" width="100" height="35" alt="" /></a>
			                <a href="javascript:yzm();">看不清楚</a>	
                            <br /><span class="smnr" id="sm_yzm"></span>		                
		                </td>
	                </tr>
                </table>
                <p>
                <span id="czts"></span>
                <input type="submit" name="bu1" value=" 把重置密码链接发至信箱 " id="bu1" />
                </p>
                <p><font color=blue><span id="la_jg"></span></font></p>
                </form>

    </div>
</div>
