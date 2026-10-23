<?php
include "includes/Init.php";

// ===== 开放注册开关（需求3-5）：reg_open=0 时展示关闭提示，不输出注册表单 =====
$__reg_open = $db->Info('reg_open');
if ($__reg_open === '0') {
    include PHPDISK_ROOT.'includes/top.php';
    echo '<div style="max-width:640px;margin:60px auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:40px;text-align:center;box-shadow:0 4px 14px rgba(0,0,0,.06);">';
    echo '<h1 style="font-size:22px;color:#1f2937;margin:0 0 16px;">注册暂停</h1>';
    echo '<p style="color:#6b7280;font-size:15px;line-height:1.8;">'.htmlspecialchars($db->Info('close_register_reason') ?: '系统暂时关闭注册，请稍后再试。', ENT_QUOTES, 'UTF-8').'</p>';
    echo '<p style="margin-top:24px;"><a href="/" style="color:#2563eb;text-decoration:none;">返回首页</a></p>';
    echo '</div>';
    include PHPDISK_ROOT.'includes/footer.php';
    echo '</body></html>';
    exit;
}
include PHPDISK_ROOT.'includes/top.php';
?>
<link rel="stylesheet" href="css/reg.css">
<link rel="stylesheet" href="css/register.css">
<style>
/* ===== 注册页内联设计（自包含，避免外部css部署/缓存导致版式失效） ===== */
#ysm{background:#f3f4f6!important;font-family:'Segoe UI','Microsoft YaHei','PingFang SC','Helvetica Neue',Arial,sans-serif!important;color:#1f2937;min-height:100vh;padding:32px 16px 48px;box-sizing:border-box}
.reg-wrap{max-width:1160px;margin:0 auto}
.reg-page-title{text-align:center;margin-bottom:28px}
.reg-page-title h1{font-size:26px;font-weight:700;color:#1f2937;margin:0 0 8px}
.reg-page-title p{font-size:14px;color:#6b7280;margin:0}
/* ===== 整体一张宽卡片：左信息面板 + 右表单（自适应宽度，非窄卡片） ===== */
.reg-layout{display:flex!important;align-items:stretch;background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 2px 6px rgba(0,0,0,.06),0 10px 30px rgba(0,0,0,.08);overflow:hidden}
.reg-side{flex:0 0 400px!important;min-width:0!important;width:auto!important;margin:0!important;background:linear-gradient(165deg,#eff6ff,#dbeafe 60%,#c7d7fb);overflow:hidden}
.reg-notice{background:transparent!important;border:none!important;box-shadow:none!important;border-radius:0!important;padding:40px 34px}
.reg-notice h2{font-size:18px;font-weight:700;color:#1d4ed8;margin:0 0 18px;padding:0 0 14px;border-bottom:2px solid rgba(37,99,235,.15)}
.notice-list{list-style:none;margin:0;padding:0;display:block}
.notice-list li{position:relative;font-size:13px;line-height:1.7;color:#374151;padding:8px 0 8px 22px;margin-bottom:6px;white-space:normal!important;overflow-wrap:break-word;word-break:break-all;box-sizing:border-box}
.notice-list li::before{content:"";position:absolute;left:2px;top:16px;width:8px;height:8px;border-radius:50%;background:#2563eb;box-shadow:0 0 0 4px rgba(37,99,235,.12)}
.reg-main{flex:1 1 auto!important;min-width:0!important;width:auto!important}
.reg-form-card{background:transparent!important;border:none!important;box-shadow:none!important;border-radius:0!important;max-width:none!important;margin:0!important;padding:38px 46px 42px}
/* ===== 区块标题 ===== */
#zcnr1 .phd-sec{margin-bottom:26px}
#zcnr1 .phd-sec:last-child{margin-bottom:0}
#zcnr1 .phd-sec-title{position:relative;font-size:16px;font-weight:700;color:#1f2937;margin:0 0 20px;padding:0 0 12px;border-bottom:1px solid #e5e7eb;text-align:left}
#zcnr1 .phd-sec-title::after{content:"";position:absolute;left:0;bottom:-1px;width:48px;height:2px;background:#2563eb}
/* ===== 字段 ===== */
#zcnr1 .phd-field{margin-bottom:18px}
#zcnr1 .phd-field:last-child{margin-bottom:0}
#zcnr1 .phd-label{display:block;font-size:13.5px;font-weight:600;color:#1f2937;margin-bottom:8px;text-align:left}
#zcnr1 .phd-label span{color:#ef4444;margin-right:3px}
#zcnr1 .phd-input,#zcnr1 .phd-select{width:100%!important;max-width:none!important;height:46px;padding:0 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:14px;font-family:inherit;color:#1f2937;background:#fff;box-sizing:border-box;outline:none;transition:border-color .15s,box-shadow .15s}
#zcnr1 .phd-input::placeholder{color:#b0b7c3}
#zcnr1 .phd-input:focus,#zcnr1 .phd-select:focus{border-color:#2563eb;box-shadow:0 0 0 3px #eff6ff}
#zcnr1 .phd-select{cursor:pointer}
#zcnr1 .phd-sub-hint{margin-top:6px;font-size:12.5px;text-align:left}
#zcnr1 .smnr{display:block;margin-top:6px;font-size:12.5px;line-height:1.6}
/* ===== 验证码 ===== */
#zcnr1 .captcha-row{display:flex;gap:12px;align-items:stretch}
#zcnr1 .captcha-row .phd-input{flex:1 1 auto;width:auto;max-width:none}
#zcnr1 .captcha-img{flex:0 0 auto;display:block;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;line-height:0;cursor:pointer}
#zcnr1 .captcha-img img{display:block}
#zcnr1 .captcha-links{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:8px}
#zcnr1 .captcha-links .smnr{margin-top:0}
/* ===== 协议行 ===== */
#zcnr1 .phd-agree{text-align:left;font-size:13.5px;color:#6b7280;margin:4px 0 22px}
#zcnr1 .phd-agree label{display:flex;align-items:center;gap:6px}
#zcnr1 .phd-agree input{accent-color:#2563eb}
/* ===== 注册按钮 ===== */
#zcnr1 .phd-submit{width:100%!important;margin:0}
#zcnr1 .phd-submit button{width:100%!important;height:50px!important;max-width:none;border:none;border-radius:10px;background:#2563eb;color:#fff;font-size:16px;font-weight:600;font-family:inherit;cursor:pointer;transition:background .15s,box-shadow .15s,transform .1s}
#zcnr1 .phd-submit button:hover{background:#1d4ed8;box-shadow:0 5px 16px rgba(37,99,235,.35)}
#zcnr1 .phd-submit button:active{transform:translateY(1px)}
.reg-link{color:#2563eb;text-decoration:none}
.reg-link:hover{text-decoration:underline}
/* ===== 响应式 ===== */
@media(max-width:960px){.reg-layout{flex-direction:column}.reg-side{flex:none;width:100%;background:#eff6ff}.reg-notice{padding:22px 24px}}
@media(max-width:600px){.reg-form-card{padding:24px 20px 30px}}
@media(max-width:480px){.captcha-row{flex-direction:column}.captcha-img img{width:100%;height:auto}}
</style>
<title>账户注册</title>
</head>
<script>
function look() {
	art.dialog.open('xy.php', {title: '注册协议', lock: true, background: '#000', opacity: 0.87});}
function trim(str) {
  return str.replace(/(^\s+)|(\s+$)/g, "");
}
</script> 
<script language="javascript" type="text/javascript">
    var data;
	var data1;
	function $(rid) { return document.getElementById(rid); }
    var _w1;
    var _w2;

    //登陆名称
    function checkdlmc() {
        var reg = new RegExp("^[0-9a-zA-Z][0-9a-zA-Z\-]{1,13}[0-9a-zA-Z]$");
        if (reg.test($('tedlmc').value) == false) { $("sm_dlmc").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>用户名不合法。请重新输入。</font>"; return ""; }
        sendRequest('reg_aj.php', 'lx=jcdlmc&dlmc=' + bm($('tedlmc').value.toLowerCase()), xmljs, '');
    }
    function writedlmc() { $("sm_dlmc").innerHTML = "<br><font color='#777777'>用户名限制3－15位，支持数字、字母和“-”,但“-”不能放在开头和结尾。不区分大小写。</font><font color='blue'>不支持汉字和其他特殊字符等</font><br><font color='red'>注册完成后用户名不支持修改。</font>"; }
    function xmljs(s1) { $("sm_dlmc").innerHTML = s1; }

    //级别
    function checkjb() { if ($('jbxz').options[0].selected == true) { $("sm_jb").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>请选择空间级别</font>"; } else { $("sm_jb").innerHTML = "<img src='images/reg_t.gif' />"; } }
    function writejb() { $("sm_jb").innerHTML = ""; }
    function xzjb() {
        if ($('jbxz').options[1].selected == true) {
            //alert('抱歉，免费空间暂停注册，请选择其他类型！');
            //$('jbxz').options[0].selected=true;
            $("jbts").style.display = 'none';
        } else if ($('jbxz').options[0].selected == true) {
            $("jbts").style.display = 'none';
        } else {
            $("jbts").innerHTML = "您选择的是付费空间，需要充值后才可以使用。<br>如果需要试用，请选择免费型空间，注册后可以随时进行升级。";
            $("jbts").style.display = 'block';
        }
    }

    //密码
    function checkmm() {
        if ($('temm').value == '') { $("sm_mm").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>需输入管理密码</font>"; return ""; }
        if ($('temm').value.length > 20) { $("sm_mm").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>需输入少于20个字符</font>"; return ""; }
        if ($('temm').value.length < 6) { $("sm_mm").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>需输入多于6字符</font>"; return ""; }

        if ($('temm').value.indexOf("'") >= 0) { $("sm_mm").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>管理密码中不得含有单引号，请重新填写</font>"; return ""; }
        if ($('temm').value.indexOf(" ") >= 0) { $("sm_mm").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>管理密码中不得含有空格，请重新填写</font>"; return ""; }
        if ($('temm').value.indexOf("\"") >= 0) { $("sm_mm").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>管理密码中不得含有双引号，请重新填写</font>"; return ""; }

        $("sm_mm").innerHTML = "<img src='images/reg_t.gif' />";
    }
    function writemm() { $("sm_mm").innerHTML = "<br><font color='#777777'>限制6-20位，区分大小写，不支持字符：单引号、双引号、空格。</font>"; }
    function checkmmqr() {
        if ($('temmqr').value == "") { $("sm_mmqr").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>请再次输入管理密码</font>"; return ""; }
        if ($('temmqr').value != $('temm').value) { $("sm_mmqr").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>两次输入管理密码不一致</font>"; return ""; }
        $("sm_mmqr").innerHTML = "<img src='images/reg_t.gif' />";
    }
    function writemmqr() { $("sm_mmqr").innerHTML = "<font color='#777777'>请再次输入管理密码</font>"; }

    //验证码
	function yzm() {
		var today = new Date();
		document.getElementById('imgyzm').src = "includes/imgcode.php?verycode_type=2&t="+Math.random();
		$('teyzm').value = "";
		
	}
    function writeyzm() { $("sm_yzm").innerHTML = "<font color='#777777'>请输入图片显示内容。</font>"; }
    function checkyzm() {
        if ($('teyzm').value.length != 4) { $("sm_yzm").innerHTML = "<font color='#777777'><img src='images/reg_f.gif' />请填写正确验证码。</font>"; return false; }
        sendRequest('reg_aj.php', 'lx=yzm&yzm=' + bm($("teyzm").value), yzmjc, '');
    }
    function yzmjc(s1) { $("sm_yzm").innerHTML = s1; }

    //邮箱
    function checkem() {
        if ($('teem').value == '') { $("sm_em").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>需输入邮箱地址</font>"; return ""; }
        if ($('teem').value.length < 6) { $("sm_em").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>需填写正确的邮箱</font>"; return ""; }
        if ($('teem').value.length > 50) { $("sm_em").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>邮箱地址不能超过50个字符</font>"; return ""; }
        if ($('teem').value.indexOf("@") < 0 || $('teem').value.indexOf(".") < 0) { $("sm_em").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>邮箱地址不合法</font>"; return ""; }
        if (!checkzf($("teem").value)) { $("sm_em").innerHTML = "<img src='images/reg_f.gif' /><font color='red'>不支持特殊字符</font>"; return ""; }
        $("sm_em").innerHTML = "<img src='images/reg_t.gif' />";
    }
    function writeem() { $("sm_em").innerHTML = "<font color='#777777'>邮箱可用于找回密码</font>"; }





    //单页注册提交（合并原 1/2/3 步校验，一次写入）
    //单页注册提交（无密保，一次写入）
    function butz() {
        //用户名
        var reg = new RegExp("^[0-9a-zA-Z][0-9a-zA-Z\-]{1,13}[0-9a-zA-Z]$");
        if (reg.test($('tedlmc').value) == false) { alert('用户名不合法。请重新输入。'); $('tedlmc').focus(); return ""; }
        if ($('jbxz').selectedIndex == 0) { alert("请选择空间级别"); return ""; }
        //密码
        if ($('temm').value == '') { alert('请填写管理密码'); $('temm').focus(); return ""; }
        if ($('temm').value.length > 20) { alert('管理密码不能超过20个字符'); $('temm').focus(); return ""; }
        if ($('temm').value.length < 6) { alert('管理密码不能少于6个字符'); $('temm').focus(); return ""; }
        if ($('temm').value.indexOf("'") >= 0) { alert('管理密码中不得含有单引号，请重新填写'); $('temm').focus(); return ""; }
        if ($('temm').value.indexOf(" ") >= 0) { alert('管理密码中不得含有空格，请重新填写'); $('temm').focus(); return ""; }
        if ($('temm').value.indexOf("\"") >= 0) { alert('管理密码中不得含有双引号，请重新填写'); $('temm').focus(); return ""; }
        if ($('temmqr').value == '') { alert('请确认管理密码'); $('temmqr').focus(); return ""; }
        if ($('temmqr').value != $('temm').value) { alert('2次输入的密码不一致'); $('temmqr').focus(); return ""; }
        //邮箱
        if ($('teem').value == '') { alert("需输入邮箱地址"); $('teem').focus(); return ""; }
        if ($('teem').value.length < 6) { alert("需填写正确的邮箱"); $('teem').focus(); return "";}
        if ($('teem').value.length > 50) { alert("邮箱地址不能超过50个字符"); $('teem').focus(); return "";}
        if ($('teem').value.indexOf("@") < 0 || $('teem').value.indexOf(".") < 0) { alert("邮箱地址不符合规范"); $('teem').focus(); return "";}
        if ($('teem').value.indexOf("'") >= 0) { alert("邮箱地址不支持单分号"); $('teem').focus(); return "";}
        if ($('teem').value.indexOf("<") >= 0 || $('teem').value.indexOf(">") >= 0) { alert("邮箱地址不支持尖括号"); $('teem').focus(); return ""; }
        //验证码
        if ($('teyzm').value == '') { alert('请填写验证码'); $('teyzm').focus(); return ""; }
        if ($('teyzm').value.length != 4) { alert("请输入正确的验证码"); $('teyzm').focus(); return ""; }
        //校验验证码通过后再提交
        sendRequest('reg_aj.php', 'lx=yzm&yzm=' + bm($("teyzm").value), butz_submit, '');
    }
    //验证码通过后正式提交（tj3 一次写入，密保字段置空）
    function butz_submit(s1) {
        if (!s1 || s1.indexOf('reg_t') < 0) { alert("验证码错误，请重新输入"); yzm(); return ""; }
        var sc = 'lx=tj3&dlmc=' + bm($("tedlmc").value.toLowerCase()) + '&jb=' + bm($("jbxz").value) + '&mm=' + bm($("temm").value) + '&emqr=' + bm($("teem").value.toLowerCase()) + '&wen1=&da1qr=&wen2=&da2qr=';
        sendRequest('reg_aj.php', sc, tj3h, '');
    }
    function tj3h(r) {
        if (trim(r) == 'y') { nrshow(4); return ""; }
        if (trim(r) == 'n') { alert("注册失败，请稍后再试！"); return ""; }
        alert(trim(r));
    }
    //成功面板
    function nrshow(n) {
        if (n == 4) {
            $("zcnr1").style.display = "none";
            $("zcnr4").style.display = "block";
            $("kjdz").innerHTML = "<a href ='http://" + window.location.host + "/k.php?name=" + $("tedlmc").value + "' target='_blank' style ='color :red ;' >http://"+ window.location.host + "/k.php?name=" + $("tedlmc").value + "</a>";
            $("li_ff").style.display = $("jbxz").selectedIndex == 1 ? "none" : "block";
        }
    }
    function bm(r) { return encodeURIComponent(escape(r));}
</script>

<body>
<div id="ysm">
<div class="reg-wrap">
	<div class="reg-container">
		<div class="reg-page-title">
			<h1>注册账户</h1>
			<p>创建您的个人网络空间账户，几分钟即可完成</p>
		</div>

		<div class="reg-layout">
			<aside class="reg-side">
				<div class="reg-card reg-notice">
					<h2>注意事项</h2>
					<ul class="notice-list">
						<li>您现在注册的是<?php echo $settings['site_title']; ?>空间，注册成功后可随时升级空间容量。</li>
						<li>请在表单中准确填写邮箱地址，该邮箱将用于找回密码。</li>
						<li>用户名限制 3－15 位，支持数字、字母和“-”，但“-”不能放在开头和结尾，不区分大小写，不支持汉字和特殊字符。</li>
						<li>密码长度 6－20 位，区分大小写，不支持单引号、双引号和空格。</li>
					</ul>
				</div>
			</aside>

			<main class="reg-main">
				<div class="reg-card reg-form-card">
		<div class="zcnr" id="main1">
		<div id="zcnr1" style="display:block;">
			<!-- 账户信息 -->
			<div class="phd-sec">
				<h3 class="phd-sec-title">账户信息</h3>
				<div class="phd-field">
					<label class="phd-label" for="tedlmc"><span>*</span>用户名</label>
					<input class="phd-input" type="text" id="tedlmc" onblur="checkdlmc();" onfocus="writedlmc();" placeholder="3-15 位，支持数字、字母和 -" />
					<span class="smnr" id="sm_dlmc"></span>
				</div>
				<div class="phd-field">
					<label class="phd-label" for="jbxz"><span>*</span>空间级别</label>
					<select class="phd-select" id="jbxz" onblur="checkjb();" onfocus="writejb();" onchange="xzjb();">
						<option value="NULL" selected="selected">请选择空间级别</option>
						<?php $db->Product('2');?>
					</select>
					<span class="smnr" id="sm_jb"></span>
					<div class="phd-sub-hint"><a href="list_cp.php" target="_blank" class="reg-link">查看级别详情</a></div>
					<div id="jbts" style="display:none; margin-top:5px;border:1px solid #C3DAEB;padding:6px 8px; background-color:#E4F3FC;border-radius:6px;font-size:12px;"></div>
				</div>
				<div class="phd-field">
					<label class="phd-label" for="temm"><span>*</span>管理密码</label>
					<input class="phd-input" type="password" id="temm" onblur="checkmm();" onfocus="writemm();" placeholder="6-20 位，区分大小写" />
					<span class="smnr" id="sm_mm"></span>
				</div>
				<div class="phd-field">
					<label class="phd-label" for="temmqr"><span>*</span>确认管理密码</label>
					<input class="phd-input" type="password" id="temmqr" onblur="checkmmqr();" onfocus="writemmqr();" placeholder="请再次输入管理密码" />
					<span class="smnr" id="sm_mmqr"></span>
				</div>
			</div>

			<!-- 联系邮箱 -->
			<div class="phd-sec">
				<h3 class="phd-sec-title">联系邮箱</h3>
				<div class="phd-field">
					<label class="phd-label" for="teem"><span>*</span>邮箱地址</label>
					<input class="phd-input" type="text" id="teem" onblur="checkem();" onfocus="writeem();" placeholder="用于找回密码，请填写有效邮箱" />
					<span class="smnr" id="sm_em"></span>
				</div>
			</div>

			<!-- 安全验证 -->
			<div class="phd-sec">
				<h3 class="phd-sec-title">安全验证</h3>
				<div class="phd-field">
					<label class="phd-label" for="teyzm"><span>*</span>验证码</label>
					<div class="captcha-row">
						<input class="phd-input" type="text" id="teyzm" onblur="checkyzm();" onfocus="writeyzm();" maxlength="4" placeholder="输入图片中的 4 位字符" />
						<a href="javascript:yzm();" class="captcha-img" title="点击刷新"><img id="imgyzm" border="0" src="includes/imgcode.php?verycode_type=2" width="130" height="45" alt="验证码" /></a>
					</div>
					<div class="captcha-links">
						<span class="smnr" id="sm_yzm"></span>
						<a href="javascript:yzm();" class="reg-link">看不清楚，换一张</a>
					</div>
				</div>
			</div>

			<p class="phd-agree">
				<label><input name="checkbox" type="checkbox" disabled="disabled" checked="checked">我已阅读并同意<a href="javascript:look()" class="reg-link">注册协议</a></label>
			</p>
			<p class="phd-submit">
				<button type="button" onclick="butz();">注&nbsp;&nbsp;册</button>
			</p>
		</div>

		<!-- 注册成功 -->
		<div id="zcnr4" style="display:none;text-align:center;">
			<img src="images/sucs.fw.png" width="310" height="59" alt="" class="sucs-img" />
			<div class="kjdz-box">
				<div class="kjdz-head">
					<img src="images/E_bt.png" alt="" class="e-bt" />
					<span class="kjdz-title">进入空间</span>
				</div>
				<p class="kjdz-url">
					<label style="display:inline-block; font-weight:bold;" id="kjdz"></label>
				</p>
				<p class="kjdz-tip">
					这个网址是您的空间地址，请谨记。点击此链接可进入空间。打开空间后请先在左上角的管理区登录，然后进行增加目录、上传文件等操作。
				</p>
			</div>
			<ul class="yslb2 reg-tips">
				<li id="li_ff">您注册的是付费空间，需要付费后才可以使用。请<a href="/ht/index.php?method=kjyq" class="reg-link">点此操作</a></li>
				<li>使用前建议先查看：<a href="/help/" class="reg-link">操作指南</a></li>
				<li>登陆空间后台可以对空间进行设置：<a href="/ht/" class="reg-link">空间后台</a>。</li>
				<li>如果您不想让别人进入您的空间，你可以<a href="/ht/index.php?method=dlmm" class="reg-link">设定登录密码</a>。同时您还可以<a href="/ht/index.php?method=qx" class="reg-link">设置访客权限</a>以及<a href="/ht/index.php?method=fg" class="reg-link">选择空间风格</a>等。</li>
			</ul>
		</div>
	</div>

				</div>
			</main>
		</div>
	</div>
</div>


</div>
<?php

include PHPDISK_ROOT."./includes/footer.php";

?>
</body>

</html>
