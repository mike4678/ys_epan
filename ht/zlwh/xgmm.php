<style>
.ta{border:2px solid #335374;width:350px;border-collapse:collapse;background-color:#EFEFEF;}
.ta td{padding:3px;border:1px solid #9C9C9C;}
</style>
<?php
$uid = 4;
$sid = 3;
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
	$bottom = '<span style=" padding-left:100px;"><input type="submit" name="bu1" value="确认修改" id="bu1" onclick="return confirm(&#39;请确认是否提交修改&#39;);"> </span>';
	} else {
	$bottom = '<span style=" padding-left:100px;"><input type="submit" name="bu1" value="确认修改" id="bu1" disabled="disabled">  <font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font></span>';
	}
?>
        <div id="ysright">
                   <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/xg.gif" width="32" height="32" alt="" />修改管理密码</h1>
            <div class="ysdb3">
            <form name="ctl00" method="post" action="?method=xgmb" id="ctl00">
               <div>
                    <div>
                        <table class="ta">
	                        <tr>
		                        <td class="tdbt" width="90">原管理密码：</td>
		                        <td width="260"><input name="sd_te1" type="password" id="sd_te1" style="width:150px;" /></td>
	                        </tr>
	                        <tr>
		                        <td class="tdbt" width="90">新管理密码：</td>
		                        <td width="260"><input name="sd_te2" type="password" id="sd_te1" style="width:150px;" /></td>
	                        </tr>
	                        <tr>
		                        <td class="tdbt" width="90">确认新密码：</td>
		                        <td width="260"><input name="sd_te3" type="password" id="sd_te1" style="width:150px;" /></td>
	                        </tr>
                        </table>
                      <br />
	                <?php echo $bottom; ?>
	                <br /><br />
                    </div>
                    <ul class="yslb2">
                        <li>操作提示：管理密码是空间最高权限密码，请记好勿泄漏。</li>
                        <li>管理密码长度为6－20位。区分大小写，不支持字符：单引号、双引号、空格。</li>

                    </ul>
                </div>
                </form>
        </div>
