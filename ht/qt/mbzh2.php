<?php
$uid = 4;
$sid = 7;
switch($_SERVER['REQUEST_METHOD']) {
	case 'GET' :
	if ($_GET['dlmc'] == NULL) {
		echo '<script>alert("用户名不能为空！");</script>';
		$table = '请求无效或被拒绝！<div class="ysdb2" style=\'display:none\'>';
		break;
		}  else { 
			if($db -> UserCheck($_GET['dlmc']) == 0) {
				echo '<script>alert("用户不存在！");</script>';
				$table = '请求无效或被拒绝！<div class="ysdb2" style=\'display:none\'>';
				break;
				} else { $wen1 = $db -> MBWT_User('wen1',$_GET['dlmc']); 
						 $wen2 = $db -> MBWT_User('wen2',$_GET['dlmc']); 
						 $table = '<div class="ysdb2">';  
						 break;
						 }
			}
			
	case 'POST' :
	if($_POST['bu_da1'] != NULL and $_POST['bu_da2'] != NULL and $_POST['dl3'] != NULL) {
		if (strtolower($_POST['dl3']) != strtolower($_SESSION['_verycode'])) {
			$db -> message('验证码错误！');
			echo'<script>window.history.back();location.reload();</script>';
			break;
			} else { if($_POST['bu_da1'] == $db -> MBWT_User('daan1',$_POST['user'])) {
						if($_POST['bu_da2'] == $db -> MBWT_User('daan2',$_POST['user'])) {	
							$hash = Crypt::get_hash();
							setcookie("tocken", $hash, time() + 180);
							header("Location: index.php?method=csmm&dlmc=". urlencode($_POST['user']) .'&hash='. $hash ); //重定向浏览器
							exit;
							break;
						} else { $db ->message('答案2检查失败！');echo'<script>window.history.back();location.reload();</script>'; break; }
				} else { $db ->message('答案1检查失败！');echo'<script>window.history.back();location.reload();</script>'; break; }
			}
		} else { $db ->message('答案或验证码不能为空！');echo'<script>window.history.back();location.reload();</script>'; break; }
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
        <form name="ctl00" method="post" action="?method=mbzh2" id="ctl00">
        <?php echo $table; ?>
            <table border="1" width="100%" id="table1">
	                <tr>
		                <td class="tdbt" width="90">用户名：</td>
		                <td><span id="la_yhm"><?php echo htmlspecialchars($_GET['dlmc'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <input name="user" type="hidden" value='<?php echo htmlspecialchars($_GET['dlmc'], ENT_QUOTES, 'UTF-8'); ?>' id="user">
	                </tr>
	                <tr>
		                <td class="tdbt" width="90">问题1：</td>
		                <td><span id="wenti1"><?php echo htmlspecialchars($wen1, ENT_QUOTES, 'UTF-8'); ?></span></td>
	                </tr>
	                <tr>
		                <td class="tdbt" width="90">答案：</td>
		                <td><input name="bu_da1" type="text" id="bu_da1" style="width:300px;" /></td>
	                </tr>
	                <tr>
		                <td class="tdbt" width="90">问题2：</td>
		                <td><span id="wenti2"><?php echo htmlspecialchars($wen2, ENT_QUOTES, 'UTF-8'); ?></span></td>
	                </tr>
	                <tr>
		                <td class="tdbt" width="90">答案：</td>
		                <td><input name="bu_da2" type="text" id="bu_da2" style="width:300px;" /></td>
	                </tr>
	                <tr>
		                <td class="tdbt" width="90">验证码：</td>
		                <td>
		                    <input name="dl3" type="text" id="dl3" style="width:120px;" />
		                <a href="javascript:yzm();"><img id="imgyzm" border="0" src="<?php echo PHPDISK_ROOT_WEB; ?>/includes/imgcode.php?verycode_type=2" width="100" height="35" alt="" /></a>
		                <a href="javascript:yzm();">看不清楚</a>			                
		            </td>
	                </tr>

            </table>
            <br />
            <input type="submit" name="butj" value=" 重置密码 " onclick="return check();" id="butj"  style="margin-left:100px;"/>
            <br /><br />

        </div>
        </form>

    </div>
</div>
