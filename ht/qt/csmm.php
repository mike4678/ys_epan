<?php
$uid = 4;
$sid = 7;
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
	if (isset($_COOKIE["tocken"])) {
		if($_GET['hash'] == NULL || $_COOKIE["tocken"] != $_GET['hash']) {
		$table = '操作已过期，请返回重试！<div class="ysdb2" style=\'display:none\'>';
		} else {
			if ($_GET['dlmc'] == NULL) {
				$table = '服务器返回错误信息，用户名不存在！<div class="ysdb2" style=\'display:none\'>';
				}  else { 
					if($db -> UserCheck($_GET['dlmc']) == 0) {
						$table = '服务器返回错误信息，用户名不存在！<div class="ysdb2" style=\'display:none\'>';
						} else { 
							setcookie("tocken", "", time() - 180);
							$table = '<div class="ysdb2">';  
						 	}
					}
			}
		} else { $table = '操作已过期，请返回重试！<div class="ysdb2" style=\'display:none\'>'; }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	setcookie("tocken", $_GET["hash"], time() + 180);
	if($_POST['temm'] == NULL or $_POST['temmqr'] == NULL) {  //检测是否为空值
		$db -> message ('欲重设密码不能为空！') ;
		echo'<script>window.history.go(-2);location.reload();</script>';
		} else { 
			if($_POST['temm'] != $_POST['temmqr']) {    //检测是否相同
				$db -> message ('两次密码不同，请重试！') ;
				echo'<script>window.history.go(-2);location.reload();</script>';
				} else {
					if($db -> StringlengthCheck((string)$_POST['temm'] , 6 , 20) == 0 and $db -> StringlengthCheck((string)$_POST['temmqr'] , 6 , 20) == 0) {    //检测密码1和2的长度是否符合
						$db -> message ('密码长度不符合要求') ;
						echo'<script>window.history.go(-2);location.reload();</script>';
						} else {
							if (empty($_SESSION['__pwreset_ok']) || empty($_SESSION['__pwreset_user'])) {
								$db -> message ('操作已过期或未通过密保验证，请返回重试！') ;
								echo'<script>window.history.go(-2);location.reload();</script>';
							} else {
								$resetUser = (string)$_SESSION['__pwreset_user'];
								unset($_SESSION['__pwreset_ok'], $_SESSION['__pwreset_user']);
								$pwd = 'password = "' . Crypt::PwdEnc($_POST['temmqr'], 'source') . '"';
								if ($db -> PWDReset_Admin($pwd ,$resetUser ) == 0) {
									$db -> message ('密码更改失败，请与管理员联系！') ;
									} else { $db -> message ('密码更改成功') ; 
										header("Location: /ht/login.php"); //重定向浏览器
										exit;
									}
							}
							}
					}
			}
	
	
	


}
?>
<script>
function $(id) { return document.getElementById(id); }
    function yzm() {
        var today = new Date();
        document.getElementById('imgyzm').src = "<?php echo PHPDISK_ROOT_WEB; ?>/includes/imgcode.php?verycode_type=2&t="+Math.random();
        document.getElementById('dl3').focus();
    }
</script>
        <div id="ysright">
            <h1><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />重置密码</h1>
                <form name="ctl00" method="post" action="?method=csmm" id="ctl00">
               <?php echo $table; ?>
                <p>请输入新的管理密码。</p>
                    <table border="1" width="100%" id="table1">
	                    <tr>
		                    <td class="tdbt" width="90">空间用户名：</td>
		                    <td><?php echo htmlspecialchars($_GET['dlmc'], ENT_QUOTES, 'UTF-8'); ?></td><input type="hidden" id="user" name="user" value="<?php echo htmlspecialchars($_GET['dlmc'], ENT_QUOTES, 'UTF-8'); ?>">
	                    </tr>
	                    <tr>
		                    <td class="tdbt" width="90">设置管理密码：</td>
		                    <td><input name="temm" type="password" id="temm" style="width:150px;" /></td>
	                    </tr>
	                    <tr>
		                    <td class="tdbt" width="90">确认密码：</td>
		                    <td><input name="temmqr" type="password" id="temmqr" style="width:150px;" /></td>
	                    </tr>
                    </table>
	                <br />
	                <input type="submit" name="idsc" value=" 提交 " onclick="return check();" id="idsc" />
	                <br /><br />
                    <ul class="yslb2">
                        <li>此页面只在此次打开有效。</li>
                        <li>管理密码是空间最高权限密码，请记好勿泄漏。</li>
                        <li>管理密码长度为6－20位。区分大小写，不支持字符：单引号、双引号、空格。</li>
                    </ul>
                </div>

            </form>


            </div>
        </div>
