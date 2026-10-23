<?php 
include "../includes/Init.php";
include PHPDISK_ROOT.'includes/top.php';
if ($db -> LoginCheck () != 0) {
	header("Location: index.php"); //重定向浏览器
	}

switch ($_SERVER['REQUEST_METHOD']) 
{
	case 'POST':
	if(!empty($_POST['dl1']) && !empty($_POST['dl2']) && !empty($_POST['dl3'])) {
		session_start();
		//第一步检查用户名是否存在
		if($db -> UserCheck($_POST['dl1']) == 0)
		{
			if(function_exists('epan_oplog')) epan_oplog('login','fail user='.$_POST['dl1'].' reason=no_user');
			$db -> message('用户名不存在！');
			break;
			}
		//第二步检查验证码是否正确
		if (strtolower($_POST['dl3']) != strtolower($_SESSION['_verycode'])) {
			if(function_exists('epan_oplog')) epan_oplog('login','fail user='.$_POST['dl1'].' reason=bad_captcha');
			$db -> message('验证码错误！');
			break;
			}
		//第三步检查密码是否正确
		if ($db -> PWDCheck($_POST['dl1'] , $_POST['dl2']) == 0) {
			if(function_exists('epan_oplog')) epan_oplog('login','fail user='.$_POST['dl1'].' reason=bad_password');
			$db -> message('密码错误！');
			break;
			}
		//如果没有问题，则执行登录请求
		$Login = array("User" => $_POST['dl1'] , "PWD" => $_POST['dl2']) ;
		if ($db -> Login($Login) == 0) {
			if(function_exists('epan_oplog')) epan_oplog('login','fail user='.$_POST['dl1'].' reason=login_api');
			$db -> message('登录失败，请与管理员联系！');
			break;
			} else {
				if(function_exists('epan_oplog')) epan_oplog('login','success user='.$_POST['dl1']);
				header("Location: index.php"); //重定向浏览器
				exit; }
			
	} else { $db -> message('账号、密码、验证码不能为空！'); }

}
//dl1=admin154&dl2=123456&dl3=&bu1=+%E7%99%BB+%E5%BD%95+
?>
<style>
/* ===== ht/login.php 自包含布局样式（内联，避免 css 缓存导致的旧版错位；与前端 design Token 一致） ===== */
* { box-sizing: border-box; }
body { margin:0; padding:0; background:#f3f4f6; color:#1f2937; font-family:'Segoe UI','Microsoft YaHei','PingFang SC','Helvetica Neue',Arial,sans-serif; font-size:14px; line-height:1.6; }
/* 登录内容在共享蓝色导航下方水平居中（不再整页 flex 垂直居中） */
.login-wrap { display:flex; gap:24px; width:100%; max-width:880px; margin:40px auto 48px; align-items:stretch; }
#ysleft { flex:1 1 320px; background:#eff6ff; border:1px solid #dbeafe; border-radius:12px; padding:28px 24px; color:#1f2937; }
#ysleft h2 { margin:0 0 14px; font-size:18px; color:#1d4ed8; border-bottom:1px solid #dbeafe; padding-bottom:10px; }
#ysleft .desc { font-size:14px; color:#374151; line-height:1.8; }
#ysright { flex:1 1 380px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 14px rgba(0,0,0,.06); padding:28px 28px 24px; }
#ysright h1 { margin:0 0 20px; font-size:20px; font-weight:600; color:#1f2937; display:flex; align-items:center; gap:8px; padding-bottom:14px; border-bottom:1px solid #e5e7eb; }
.form-row { margin-bottom:16px; }
.form-label { display:block; margin-bottom:6px; color:#374151; font-size:13px; font-weight:500; }
.form-input { width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:8px; background:#fff; color:#1f2937; font-size:14px; outline:none; transition:border-color .15s,box-shadow .15s; }
.form-input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.15); }
.captcha-row { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.captcha-row .form-input { width:130px; }
#imgyzm { border:1px solid #e5e7eb; border-radius:6px; cursor:pointer; vertical-align:middle; }
.link { color:#2563eb; text-decoration:none; font-size:13px; }
.link:hover { color:#1d4ed8; text-decoration:underline; }
.link.forgot { margin-left:8px; }
#sm_yzm { display:block; margin-top:6px; font-size:12px; min-height:16px; }
.btn-row { margin-top:22px; display:flex; align-items:center; gap:14px; }
.btn-primary { flex:1; padding:10px 0; background:#2563eb; color:#fff; border:none; border-radius:8px; font-size:15px; cursor:pointer; transition:background .15s; }
.btn-primary:hover { background:#1d4ed8; }
.btn-primary:disabled { opacity:.7; cursor:not-allowed; }
.register-link { font-size:13px; white-space:nowrap; }
@media (max-width:768px){ .login-wrap{flex-direction:column;max-width:420px;margin:20px auto 28px;} #ysleft,#ysright{padding:20px;} }
</style>
<title>用户登录</title>
</head>
<script language="javascript" type="text/javascript">
    function $(id) { return document.getElementById(id); }

    function yzm() {
        var today = new Date();
        document.getElementById('imgyzm').src = "../includes/imgcode.php?verycode_type=2&t="+Math.random();
        document.getElementById('dl3').focus();
    }
	
	function writeyzm() { $("sm_yzm").innerHTML = "<font color='#f00'>请输入图片显示内容。</font>"; }
    function checkyzm() {
        if ($('dl3').value.length != 4) 
		{ $("sm_yzm").innerHTML = "<font color='#f00'><img src='../images/reg_f.gif' />请填写正确验证码。</font>"; 
		return false; } else { $("sm_yzm").innerHTML = "";  }
    }
	
    function gb(){window.location.href="";}
    function zxdl() {
        $("bux1").disabled = "disabled";
        $("bu1").click();
    
    }
</script>
<body>
    <div class="login-wrap">
        <div id="ysleft">
            <h2>使用说明</h2>
            <div class="desc">
                登录成功后，您可在空间后台完成空间设置、空间升级或延期等操作；如有使用问题，也可前往客服中心提交工单。
            </div>
        </div>
        <div id="ysright">
            <h1><span id="yhztxs"></span>
            <img border="0" src="/ht/images/dl.gif" width="30" height="27" alt="" />用户登录</h1>
            <!--
            <p style="font-size:11pt;color:red;">抱歉，后台程序正在维护，请稍候再登录</p>
             -->
            <div class="ysdb2" style="display:block;">
                <form name="ctl00" method="post" action="login.php" id="ctl00">
                    <div class="form-row">
                        <label class="form-label" for="dl1">用户名</label>
                        <input name="dl1" type="text" id="dl1" class="form-input" />
                    </div>
                    <div class="form-row">
                        <label class="form-label" for="dl2">管理密码</label>
                        <input name="dl2" type="password" id="dl2" class="form-input" onkeypress="if (window.event.keyCode==13){document.getElementById(&#39;dl3&#39;).focus();}" />
                        <a href="/ht/index/zhmm" class="link forgot">忘记密码</a>
                    </div>
                    <div class="form-row">
                        <label class="form-label" for="dl3">验证码</label>
                        <div class="captcha-row">
                            <input name="dl3" type="text" id="dl3" class="form-input" onkeypress="if (window.event.keyCode==13){bux1.click();}" onblur="checkyzm();" onfocus="writeyzm();"/>
                            <a href="javascript:yzm();"><img id="imgyzm" border="0" src="../includes/imgcode.php?verycode_type=2" width="100" height="35" alt="" /></a>
                            <a href="javascript:yzm();" class="link">看不清楚</a>
                        </div>
                        <span class="smnr" id="sm_yzm"></span>
                    </div>
                    <div class="btn-row">
                        <input id="bux1" type="button" value=" 登 录 " onclick="zxdl();" class="btn-primary" />
                        <input type="submit" name="bu1" value=" 登 录 " id="bu1" style="display:none;" />
                        <a href="/register.php" class="link register-link">注册</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
<?php 
include PHPDISK_ROOT."./includes/footer.php";
?>
</html>
