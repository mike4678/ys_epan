<style>
.ta{border:2px solid #335374;width:350px;border-collapse:collapse;background-color:#EFEFEF;}
.ta td{padding:3px;border:1px solid #9C9C9C;}
</style>
<?php
$uid = 4;
$sid = 6;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//首先检查账号状态，防止未登录状态下修改
	if($db -> LoginCheck() != 0){
		//验证表项是否为空
		if($_POST['y_da1'] != NULL and $_POST['y_da2'] != NULL and $_POST['x_da1'] != NULL and $_POST['x_da2'] != NULL) {
			//验证原始密保是否正确
			if ($_POST['y_da1'] == $db -> AccountData('daan1') and $_POST['y_da2'] == $db -> AccountData('daan2')) {
				//验证密保问题是否相同
				if ($_POST['x_da1'] != $_POST['x_da2']) {

					if($_POST['x_wt1'] == '自定义问题') {
					$quest1 = $_POST['x_wenti1'];
					} else { $quest1 = $_POST['x_wt1']; }

					if($_POST['x_wt2'] == '自定义问题') {
					$quest2 = $_POST['x_wenti2'];
					} else { $quest2 = $_POST['x_wt2']; }

					if($db ->UserSettingUpdate($_SESSION['username_epan'] , ' wen1 =\''. $db->escape($quest1) .'\', daan1 =\'' . $db->escape($_POST['x_da1']) . '\',  wen2 =\''. $db->escape($quest2) .'\', daan2 =\''. $db->escape($_POST['x_da2']) .'') != 0) {
					$db ->message('更新成功！');
						} else { $db ->message('更新失败！'); }

					} else { $db ->message('更新失败！密保答案不允许相同！'); }
				} else { $db ->message('更新失败！原始密保信息验证失败！'); }
			} else { $db ->message('更新失败！参数不能为空！'); }
		} else { $db ->message('更新失败！未登录状态不允许修改！'); }
}

if($db -> LoginCheck() != 0){
	$bottom = '<span style=" padding-left:190px;"><input type="submit" name="bu1" value="确认修改" id="bu1" > </span>';
	$wen1 = $db -> AccountData('wen1');
	$wen2 = $db -> AccountData('wen2');
	} else {
	$bottom = '<span style=" padding-left:180px;"><input type="submit" name="bu1" value="提交设置" id="bu1" disabled="disabled">  <font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font></span>';
	}
?>
<script language="javascript" type="text/javascript">
    window.onload = function() { var ts = ""; if (ts != "") alert(ts); }
    var ja_zs= 19;

    function check() {
        if (!confirm('请确认是否进行提交')) return false;

        var ys = document.forms[0];

        //原答案判断
        if (ys.y_da1.value == '') { alert('原答案1不能为空！'); ys.y_da1.focus(); return false; }
        if (ys.y_da1.value.length > 50) { alert('原答案1最多50个字符！'); ys.y_da1.focus(); return false; }
        if (ys.y_da2.value == '') { alert('原答案2不能为空！'); ys.y_da2.focus(); return false; }
        if (ys.y_da2.value.length > 50) { alert('原答案2最多50个字符！'); ys.y_da2.focus(); return false; }

        if (checkzf(ys.y_da1.value) == 'F') { alert('原答案1不支持特殊字符的输入！'); ys.y_da1.focus(); return false; }
        if (checkzf(ys.y_da2.value) == 'F') { alert('原答案2不支持特殊字符的输入！'); ys.y_da2.focus(); return false; }

        //新问题和答案判断
        if (ys.x_wt1.selectedIndex == ja_zs) {
            if (ys.x_wenti1.value == '') { alert('自定义问题1不能为空。'); ys.x_wenti1.focus(); return false; }
            if (ys.x_wenti1.value.length > 50) { alert('自定义区问题1至少3个字符，最多50个字符。'); ys.x_wenti1.focus(); return false; }
            if (ys.x_wenti1.value.length < 3) { alert('自定义区问题1至少3个字符，最多50个字符。'); ys.x_wenti1.focus(); return false; }
            if (checkzf(ys.x_wenti1.value) == 'F') { alert('自定义区问题1不支持特殊字符的输入。'); ys.x_wenti1.focus(); return false; }
        }

        if (ys.x_wt2.selectedIndex == ja_zs) {
            if (ys.x_wenti2.value == '') { alert('自定义问题2不能为空。'); ys.x_wenti2.focus(); return false; }
            if (ys.x_wenti2.value.length > 50) { alert('自定义区问题2至少3个字符，最多50个字符。'); ys.x_wenti2.focus(); return false; }
            if (ys.x_wenti2.value.length < 3) { alert('自定义区问题2至少3个字符，最多50个字符。'); ys.x_wenti2.focus(); return false; }
            if (checkzf(ys.x_wenti2.value) == 'F') { alert('自定义区问题2不支持特殊字符的输入。'); ys.x_wenti2.focus(); return false; }
        }

        if (ys.x_wt1.selectedIndex == ja_zs && ys.x_wt2.selectedIndex == ja_zs) { if (ys.x_wenti1.value == ys.x_wenti2.value) { alert('抱歉，不能设置2个相同的问题。'); return false; } }

        if (ys.x_wt1.selectedIndex == 0) { alert('请选择新问题1。');  return false; }
        if (ys.x_da1.value == '') { alert('新答案1不能为空。'); ys.x_da1.focus(); return false; }
        if (ys.x_da1.value.length > 50) { alert('新答案1最多50个字符。'); ys.x_da1.focus(); return false; }
        if (checkzf(ys.x_da1.value) == 'F') { alert('新答案1不支持特殊字符的输入。'); ys.x_da1.focus(); return false; }

        if (ys.x_wt2.selectedIndex == 0) { alert('请选择新问题2。');  return false; }
        if (ys.x_da2.value == '') { alert('新答案2不能为空。'); ys.x_da2.focus(); return false; }
        if (ys.x_da2.value.length > 50) { alert('新答案2最多50个字符。'); ys.x_da2.focus(); return false; }
        if (checkzf(ys.x_da2.value) == 'F') { alert('新答案2不支持特殊字符的输入。'); ys.x_da2.focus(); return false; }

        if (ys.x_da2.value == ys.x_da1.value) { alert('2个新答案不能相同。'); ys.x_da1.focus(); return false; }

    }

        function dk() {

            if (document.forms[0].x_wt1.selectedIndex == ja_zs) { document.getElementById("z_wt1").style.display = 'block'; document.getElementById("x_wenti1").focus(); }
            else {
                document.getElementById("z_wt1").style.display = 'none';
                if (document.forms[0].x_wt1.selectedIndex != 0) {
                    if (document.forms[0].x_wt1.selectedIndex == document.forms[0].x_wt2.selectedIndex) {
                        alert('抱歉，不能选择2个相同的问题。');
                        document.forms[0].x_wt1.selectedIndex = 0;
                    }
                }
            }
        }
        function dk2() {

            if (document.forms[0].x_wt2.selectedIndex == ja_zs) { document.getElementById("z_wt2").style.display = 'block'; document.getElementById("x_wenti2").focus(); }
            else {
                document.getElementById("z_wt2").style.display = 'none';
                if (document.forms[0].x_wt2.selectedIndex != 0) {
                    if (document.forms[0].x_wt1.selectedIndex == document.forms[0].x_wt2.selectedIndex) {
                        alert('抱歉，不能选择2个相同的问题。');
                        document.forms[0].x_wt2.selectedIndex = 0;
                    }
                }
            }
        }


    function checkzf(nr) {
        var zf = "/\\,'<>\"&"
        for (i = 0; i <= zf.length - 1; i++) {
            if (nr.indexOf(zf.substr(i, 1)) >= 0) { return "F" }
        }
        return "T"
    }



</script>
        <div id="ysright">
                   <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/xg.gif" width="32" height="32" alt="" />修改密码保护</h1>
            <div class="ysdb3">
            <form name="ctl00" method="post" action="?method=xgmb" id="ctl00">
               <div>
                    <h3>请正确输入原问题答案。</h3>
                    <div style=" padding-left:50px;">
                        <table class="ta">
	                        <tr>
		                        <td class="tdbt" width="76">问题1：</td>
		                        <td width="260"><span id="y_wt1"><?php echo htmlspecialchars($wen1, ENT_QUOTES, 'UTF-8'); ?></span></td>
	                        </tr>
	                        <tr>
		                        <td class="tdbt" width="76">答案：</td>
		                        <td width="260"><input name="y_da1" type="text" id="y_da1" style="width:200px;" /></td>
	                        </tr>
	                        <tr>
		                        <td class="tdbt" width="76">问题2：</td>
		                        <td width="260"><span id="y_wt2"><?php echo htmlspecialchars($wen2, ENT_QUOTES, 'UTF-8'); ?></span></td>
	                        </tr>
	                        <tr>
		                        <td class="tdbt" width="76">答案：</td>
		                        <td width="260"><input name="y_da2" type="text" id="y_da2" style="width:200px;" /></td>
	                        </tr>
                        </table>
                    </div>
                    <h3>请设置新的密保问题。</h3>
	                <div style=" padding-left:50px;">
                        <table class="ta">
	                        <tr>
		                        <td class="tdbt" width="76">问题1：</td>
		                        <td width="260">
		                            <select name="x_wt1" id="x_wt1" OnChange="dk()" style="width:200px;">
									<?php echo $db -> MBWT() ; ?>
</select>
                                    <div id="z_wt1" style=" display:none;">
                                        <input name="x_wenti1" type="text" id="x_wenti1" style="width:200px;" />
		                            </div>
		                        </td>
	                        </tr>
	                        <tr>
		                        <td class="tdbt" width="76">答案：</td>
		                        <td><input name="x_da1" type="text" id="x_da1"  style="width:200px;" /></td>
	                        </tr>
	                        <tr>
		                        <td class="tdbt" width="76">问题2：</td>
		                        <td>
		                            <select name="x_wt2" id="x_wt2"  OnChange="dk2()" style="width:200px;">
									<?php echo $db -> MBWT() ; ?>
</select>
                                    <div id="z_wt2" style=" display:none;">
                                        <input name="x_wenti2" type="text" id="x_wenti2" style="width:200px;" />
		                            </div>
		                        </td>
		                    </tr>
	                        <tr>
		                        <td class="tdbt" width="76">答案：</td>
		                        <td><input name="x_da2" type="text" id="x_da2"  style="width:200px;" /></td>
	                        </tr>
	                    </table>
	                </div>

	                <br />
	                <?php echo $bottom; ?>
	                <br /><br />
                    <ul class="yslb2">
                        <li>请认真选择或填写问题、答案，此将成为您找回密码的凭据。</li>
                        <li>请牢记密保答案，如果遗失，将无法找回或重置。</li>
                        <li>问题和答案不支持字符（' &lt; &gt; / \ &quot; &amp;）</li>
                        <li>密保答案不区分大小写</li>
                    </ul>
                </div>
                </form>
        </div>
