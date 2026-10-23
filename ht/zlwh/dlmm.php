<?php
$uid = 4;
$sid = 1;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//首先检查账号状态，防止未登录状态下修改
	if($db -> LoginCheck() != 0){ 
		//验证表项是否为空
		if($_POST['rsd'] == 'A1') { 
				if($db ->SpaceUpdate($_SESSION['username_epan'] , 'pwd' , '')) {
					$db ->message('更新成功！');
					} else { $db ->message('更新失败！'); }
			}
		if($_POST['rsd'] == 'A2') {
			if($_POST['t2'] == NULL) { 
				$db ->message('密码不能为空'); 
					}  else { 
							$pwd = Crypt::PwdEnc($_POST['t2'], 'source');
							if($db ->SpaceUpdate($_SESSION['username_epan'] , 'pwd' , $pwd)) {
							$db ->message('更新成功！');
							} else { $db ->message('更新失败！'); }
						}
			}
		}
}

if($db -> LoginCheck() != 0){ 
	$bottom = '<span style=" padding-left:100px;"><input type="submit" name="bu1" value="确认修改" id="bu1" onclick="return confirm(&#39;请确认是否提交修改&#39;);"> </span>';
	if ($db -> SpaceSetting($_SESSION['username_epan'],'pwd') != NULL) {
		$spacepwd = '<span style="color:#0f0">已设置登录密码</span>' ;
		} else { $spacepwd = '<span style="color:#f00">无登录密码</span>' ; }
	} else { 
	$bottom = '<span style=" padding-left:100px;"><input type="submit" name="bu1" value="确认修改" id="bu1" disabled="disabled">  <font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font></span>';
	}
?>
<div id="ysright">
           <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />设置登录密码</h1>
            <div class="ysdb3">
            <form name="ctl00" method="post" action="?method=dlmm" id="ctl00">
            <p style="font-size:10pt; "><b>登录密码是登录空间的密码。如果不设置登录密码，则可以直接进入空间。</b></p>
            当前设置：<?php echo $spacepwd ; ?>
			<div style="border:1px solid #C3DAEB;padding:5px; width:320px;background-color:#E4F3FC;">
                    <div style="cursor:hand;" onclick="document.forms[0].p1.checked=true;document.forms[0].t1.focus();">
                    <input value="A1" name="rsd" type="radio" id="p1" checked="checked" />无登录密码（输入空间地址后即可直接打开空间）。</div>
                    <br />
                    <div style="cursor:hand;" onclick="document.forms[0].p2.checked=true;document.forms[0].t2.focus();">
                    <input value="A2" name="rsd" type="radio" id="p2" />设置登录密码为：<input name="t2" type="text" id="t2" /></div>
                </div>
                <!-- 隐藏的控件，为了可以按回车激活按钮  -->
                <div style=" display:none;"><input name="TextBox1" type="text" id="TextBox1" style="width:130px;" /></div>
            <p>
                <?php echo $bottom; ?>
            </p>
            <ul class="yslb2">
                <li>登录密码是登录空间的密码，请尽量不要和管理密码相同</li>
                <li>登录密码支持字母和数字，最多15个字符</li>
            </ul>
            </form>   
        </div>
