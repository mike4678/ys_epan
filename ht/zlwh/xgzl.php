<?php
$uid = 3;
$sid = 8;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($db -> LoginCheck() != 0){
		//验证表项是否为空
		if($_POST['zl_te1'] != NULL and $_POST['zl_te2'] != NULL and $_POST['zl_te3'] != NULL and $_POST['zl_te4'] != NULL and $_POST['zl_te5'] != NULL) {
			if($db -> UserSettingUpdate($_SESSION['username_epan'] , ' setting = \'1\' , zsxm = \''. $db->escape($_POST['zl_te1']) . '\',  tel =\''. $db->escape($_POST['zl_te2']). '\', qq =\'' . $db->escape($_POST['zl_te3']) . '\', address =\'' . $db->escape($_POST['zl_te4']) . '\', sfz =\'' . $db->escape($_POST['zl_te5']). '') != 0 ) {
				$db ->message('更新成功！');
				} else { $db ->message('更新失败！'); }
			}
	}
}

if($db -> LoginCheck() != 0){
		if($db -> AccountData('setting') != 0){
		$bottom = '<span></span>';
		$name = '<input name="zl_te1" type="text" id="zl_te1" value="'.Crypt::hideStar($db -> AccountData('zsxm')).'" style="width:250px;"  disabled="disabled"/>' ;
		$tel = '<input name="zl_te1" type="text" id="zl_te1" value="'.Crypt::hideStar($db -> AccountData('tel')).'" style="width:250px;"  disabled="disabled"/>' ;
		$qq = '<input name="zl_te1" type="text" id="zl_te1" value="'.Crypt::hideStar($db -> AccountData('qq')).'" style="width:250px;"  disabled="disabled"/>' ;
		$address = '<input name="zl_te1" type="text" id="zl_te1" value="'.Crypt::hideStar($db -> AccountData('address')).'" style="width:250px;"  disabled="disabled"/>' ;
		$sfz = '<input name="zl_te1" type="text" id="zl_te1" value="'.Crypt::hideStar($db -> AccountData('sfz')).'" style="width:250px;"  disabled="disabled"/>' ;
		} else {
		$bottom = '<span style=" padding-left:190px;"><input type="submit" name="bu1" value="提交设置" id="bu1" onclick="return check();"> </span>';
		$name = '<input name="zl_te1" type="text" id="zl_te1" style="width:250px;" />' ;
		$tel = '<input name="zl_te2" type="text" id="zl_te1" style="width:250px;" />' ;
		$qq = '<input name="zl_te3" type="text" id="zl_te1" style="width:250px;" />' ;
		$address = '<input name="zl_te4" type="text" id="zl_te1" style="width:250px;" />' ;
		$sfz = '<input name="zl_te5" type="text" id="zl_te1" style="width:250px;" />' ;
		}
	} else {
	$bottom = '<span style=" padding-left:180px;"><input type="submit" name="bu1" value="提交设置" id="bu1" disabled="disabled">  <font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font></span>';
	}
?>
<script language="javascript" type="text/javascript">
    window.onload = function() { var ts = ""; if (ts != "") alert(ts); }

    function check() {
        var ys = document.forms[0];
        var zf = ys.zl_te1.value + ys.zl_te2.value + ys.zl_te3.value + ys.zl_te4.value + ys.zl_te5.value;
        if (checkzf(zf) == 'F') { alert('不支持特殊字符的输入。'); return false; }

        if (!confirm('设置后不可更改，请确认是否提交设置')) return;
    }

    function checkzf(nr) {
        var zf = "/\\'<>\"&"
        for (i = 0; i <= zf.length - 1; i++) {
            if (nr.indexOf(zf.substr(i, 1)) >= 0) { return "F" }
        }
        return "T"
    }

</script>
        <div id="ysright">
                   <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/xg.gif" width="32" height="32" alt="" />个人资料修改</h1>
            <div class="ysdb2">
            <form name="ctl00" method="post" action="?method=xgzl" id="ctl00">
            郑重承诺：不将用户信息用于任何违法用途！
                   <table border="1" width="100%" id="table1">
	                    <tr>
		                    <td class="tdbt" width="90">真实姓名：</td>
		                    <td><?php echo $name; ?></td>
	                    </tr>
	                    <tr>
		                    <td class="tdbt" width="90">联系电话：</td>
		                    <td><?php echo $tel; ?></td>
	                    </tr>
	                    <tr>
		                    <td class="tdbt" width="90">联系QQ或者MSN：</td>
		                    <td><?php echo $qq; ?></td>
	                    </tr>
	                    <tr>
		                    <td class="tdbt" width="90">联系地址：</td>
		                    <td><?php echo $address; ?></td>
	                    </tr>
	                    <tr>
		                    <td class="tdbt" width="90">身份证号码：</td>
		                    <td><?php echo $sfz; ?></td>
	                    </tr>
                    </table>
                    <br />
                    <?php echo $bottom;?>
                    <br /><br />
                    <ul class="yslb2">
                        <li>操作提示：请认真填写本处内容，我们对用户发放通知、寄送发票等要用到此处数据。</li>
                        <li>注意：信息一经填写，将无法进行修改!填写完成后，我们将会隐藏部分信息以保护您的隐私</li>
                        <li>不支持特殊字符：（/ \ &#39; &lt; &gt; &quot; &amp;）</li>
                    </ul>
                </div>
            </form>
        </div>
