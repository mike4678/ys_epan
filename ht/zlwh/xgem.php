<?php
$uid = 4;
$sid = 4;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//检查用户提交的原始邮箱是否跟数据库中的相同
	if($_POST['sd_te1'] == $db ->AccountData('email')) {
		if($db ->UserSettingUpdate($_SESSION['username_epan'] , ' email =\''. $db->escape($_POST['sd_te2']) .'') != 0) {
					$db ->message('更新成功！');
						} else { $db ->message('更新失败！'); }

		} else { $db ->message('更新失败！原邮箱信息验证失败'); }

	}
if($db -> LoginCheck() != 0){
	$bottom = '<span style=" padding-left:100px;"><input type="submit" name="bu1" value="提交修改" id="bu1" > </span>';
	$email = Crypt::hideStar($db ->AccountData('email'));
	} else {
	$bottom = '<span style=" padding-left:100px;"><input type="submit" name="bu1" value="提交修改" id="bu1" disabled="disabled">  <font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font></span>';
	}
?>
<script language="javascript" type="text/javascript">
    window.onload = function() { var ts = ""; if (ts != "") alert(ts); if (ts == "已成功修改数据！点击确定后转到邮件重发页面。") { window.location.href = "/ht/zlwh/emcf.php"; } }
    function check() {
        var ys = document.forms[0];

        if (checkzf(ys.sd_te1.value) == 'F') { alert('原邮箱不支持特殊字符的输入。'); ys.sd_te1.focus(); return false; }
        if (checkzf(ys.sd_te2.value) == 'F') { alert('新邮箱不支持特殊字符的输入。'); ys.sd_te2.focus(); return false; }
        if (!confirm('请确认是否提交设置')) return;
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
            <h1><span id="yhztxs"><?php echo $db -> LoginBar();?></span><img border="0" src="/ht/images/xg.gif" width="32" height="32" alt="" />修改邮箱</h1>
            <form name="ctl00" method="post" action="?method=xgem" id="ctl00">
            <input name="jlly" type="hidden" id="jlly" />
                <div class="ysdb2">
                    <table border="1" width="100%" id="table1">
	                    <tr>
		                    <td class="tdbt" width="90">原邮箱：</td>
		                    <td><input name="sd_te1" type="text" id="sd_te1" style="width:150px;" />
	                        <span id="la_emts">提示：<?php echo $email; ?></span></td>
	                    </tr>
	                    <tr>
		                    <td class="tdbt" width="90">新邮箱：</td>
		                    <td><input name="sd_te2" type="text" id="sd_te2"  style="width:150px;" /></td>
	                    </tr>
	                </table>
	                <br />
	                <?php echo $bottom; ?>
	                <br /><br />
                    <ul class="yslb2">
                        <li>操作提示：请认真填写邮箱地址，在密码丢失或者忘记密码时，可以取回密码。</li>
                    </ul>
                </div>
            </form>
        </div>
