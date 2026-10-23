<?php
if (!defined('source'))
	header("Location: ../login.php"); //重定向浏览器

//初始化相关参数信息

if($_SERVER["REQUEST_METHOD"] == "GET")
{
	$status = $db->Info('server_status');
	$IndexStatus = $db->Info('index_status');
	$db->CheckUploadSize();  //上传大小检查，如果超过设定，则自动强制修正
	if($_COOKIE['set'] == NULL && $_COOKIE['up'] == NULL)
	{
		$db->cookie("set", 'active');
		$db->cookie("up", '');
		$db->cookie("adv", '');
		$db->cookie("email", '');
	}

}

// ===== 公共：写入 epan_settings（前台 $db->Info() 读取的键值表）=====
// admin 侧 $db 为 AdminSystem（旧设置表），前台 System 类读 epan_settings；
// 网站名/底部/维护模式等前台可见项必须写 epan_settings 才生效。
if (!function_exists('epan_set')) {
	function epan_set($db, $k, $v) {
		$k = (string)$k;
		$v = (string)$v;
		$stmt = null;
		// AdminSystem 基于 mysqli，使用 prepare
		$sql = "INSERT INTO epan_settings (vars, value) VALUES ('".$db->escape($k)."', '".$db->escape($v)."')
				ON DUPLICATE KEY UPDATE value = VALUES(value)";
		return $db->query($sql);
	}
}
// 读取 epan_settings（绕过 AdminSystem::Info）
if (!function_exists('epan_get')) {
	function epan_get($db, $k) {
		$res = $db->query("SELECT value FROM epan_settings WHERE vars='".$db->escape((string)$k)."' LIMIT 1");
		if (!$res) return '';
		$row = $db->fetch_array($res);
		return $row ? (string)$row['value'] : '';
	}
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	switch($_POST['Tab'])
	{
		case 'tab-system':
			$_POST['path'] = empty($_POST['path']) ? '' : $_POST['path'];

			//页面条数显示数检查
			if( $_POST['disp'] == 0 || $_POST['disp'] == NULL || is_int($_POST['disp']) == False)
			{
				$Page_Display = 20;

			} else { $Page_Display = $_POST['disp']; }

			//检查结束

			// 全部写入 epan_settings（前台实际读取表；不再写旧设置表）
		epan_set($db, 'site_maintenance',       (isset($_POST['serverstate']) && $_POST['serverstate']==='1') ? '1' : '0');
		epan_set($db, 'site_maintenance_msg',    isset($_POST['sys']) ? $_POST['sys'] : '');
		epan_set($db, 'site_title',              isset($_POST['sitename']) ? $_POST['sitename'] : '');
		epan_set($db, 'footer',                  isset($_POST['sysinfo']) ? $_POST['sysinfo'] : '');
		epan_set($db, 'khlb',                    isset($_POST['khlb']) ? $_POST['khlb'] : '');
		epan_set($db, 'upgrade_enable',          (isset($_POST['upgrade_enable']) && $_POST['upgrade_enable']==='1') ? '1' : '0');
		// 既有旧键一并写 epan_settings，供后台壳层 Info() 漏斗读取
		epan_set($db, 'pagedisplay',             (string)$Page_Display);
		epan_set($db, 'encrypted',               isset($_POST['pwdreq']) ? $_POST['pwdreq'] : '0');
		epan_set($db, 'VaildCode',               isset($_POST['vaildcode']) ? $_POST['vaildcode'] : '0');
		epan_set($db, 'logo',                    isset($_POST['path']) ? $_POST['path'] : '');

		echo '<script language="JavaScript">SystemBox(3,"参数已更新！")</script>';
		$db->cookie("set", 'active' , 300);
		$db->cookie("up", '' , 300);
		$db->cookie("adv", '' , 300);
break;

		case 'tab-upload':
			$phpsize = str_replace("M","",$_POST['php_size']);
			if($_POST['file_size'] > $phpsize)   //如果文件大小超过PHP系统限定大小
			{
				echo '<script language="JavaScript">SystemBox(3,"当前允许上传文件大小超过PHP系统限定值！")</script>';
				echo '<script language="JavaScript">window.history.back();</script>';
			} else {
				epan_set($db, 'upload_size',  isset($_POST['file_size']) ? $_POST['file_size'] : '0');
					epan_set($db, 'file_path',   isset($_POST['file_path']) ? trim($_POST['file_path']) : '');
					// ===== 文件类型白/黑名单（前台 up/index.php 读取）=====
					epan_set($db, 'upload_type_allow', isset($_POST['upload_type_allow']) ? $_POST['upload_type_allow'] : '');
					epan_set($db, 'filter_extension',  isset($_POST['filter_extension'])  ? $_POST['filter_extension']  : '');
					echo '<script language="JavaScript">SystemBox(3,"参数已更新！")</script>';
					$db->cookie("set", '', 300);
					$db->cookie("up", 'active' , 300);
					$db->cookie("adv", '' , 300);
				}
			break;

			case 'tab-user':
				$cur_user = isset($_SESSION['username']) && $_SESSION['username'] != '' ? $_SESSION['username'] : 'admin';
				$old_pwd = empty($_POST['old_pwd']) ? '' : $_POST['old_pwd'];
				$new_pwd = empty($_POST['new_pwd']) ? '' : $_POST['new_pwd'];
				$conf_new_pwd = empty($_POST['conf_new_pwd']) ? '' : $_POST['conf_new_pwd'];
				if($old_pwd == '' && $new_pwd == '' && $conf_new_pwd == '')
				{
					// 未提交改密字段：视为只保存注册开关
					epan_set($db, 'reg_open',         (isset($_POST['reg_open']) && $_POST['reg_open']==='1') ? '1' : '0');
					epan_set($db, 'reg_banned_users', isset($_POST['reg_banned_users']) ? $_POST['reg_banned_users'] : '');
					echo '<script language="JavaScript">SystemBox(3,"注册设置已保存！")</script>';
					$db->cookie("email", 'active' , 300);
					break;
				}
				if($db -> PWDCheck($cur_user, $old_pwd) == 0)  //ht 账户 bcrypt 校验旧密码
				{
					echo '<script language="JavaScript">SystemBox(3,"旧密码输入错误，请重新输入！")</script>';
					echo '<script language="JavaScript">window.history.go(-1);</script>';
					exit;
				}
				if($new_pwd != $conf_new_pwd)
				{
					echo '<script language="JavaScript">SystemBox(3,"两次输入的密码不一致，请重新输入！")</script>';
					echo '<script language="JavaScript">window.history.go(-1);</script>';
					exit;
				}
				$encryption_pwd = Crypt::PwdEnc($new_pwd, 'source'); //ht bcrypt
				$sql = "UPDATE epan_users SET password = '".$db->escape($encryption_pwd)."' where username = '".$db->escape($cur_user)."';";
				if (!$db->query($sql))
				{
					echo '<script language="JavaScript">SystemBox(3,"修改密码失败")</script>';
					echo '<script language="JavaScript">window.history.go(-1);</script>';
					} else {
						// 改密成功同时保存注册开关
						epan_set($db, 'reg_open',         (isset($_POST['reg_open']) && $_POST['reg_open']==='1') ? '1' : '0');
						epan_set($db, 'reg_banned_users', isset($_POST['reg_banned_users']) ? $_POST['reg_banned_users'] : '');
						echo '<script language="JavaScript">SystemBox(3,"密码已更新，请重新登陆")</script>';
						echo '<script language="JavaScript">window.location.href="index.php?/exit"</script>';
					}
			break;   // 修复原代码漏 break 导致 fall-through 到 tab-pay 的问题

		case 'tab-product':
			$pAct = isset($_POST['p_act']) ? $_POST['p_act'] : 'save';
			if ($pAct === 'add') {
				// 新增级别：code = 当前最大 code + 1（避开 -1）
				$newName = trim(isset($_POST['p_new_name']) ? $_POST['p_new_name'] : '');
				if ($newName === '') {
					echo '<script language="JavaScript">SystemBox(3,"级别名称不能为空")</script>';
				} else {
					$mxRow = $db->fetch_array($db->query("SELECT MAX(code) AS m FROM epan_product"));
					$newCode = ((int)$mxRow['m']) + 1;
					$a_data     = (int)$_POST['p_new_data'];
					$a_price    = (float)$_POST['p_new_price'];
					$a_filemax  = (int)$_POST['p_new_filemax'];
					$a_path     = (int)$_POST['p_new_path'];
					$a_downnum  = (int)$_POST['p_new_downnum'];
					$a_valid    = (int)$_POST['p_new_valid_days'];
					$a_pay      = !empty($_POST['p_new_pay']) ? 1 : 0;
					$a_status   = !empty($_POST['p_new_status']) ? 1 : 0;
					$db->query("INSERT INTO epan_product (name,data,filemax,path,downnum,pay,status,code,price,valid_days) VALUES (
					'".$db->escape($newName)."',".$a_data.",".$a_filemax.",".$a_path.",".$a_downnum.",".$a_pay.",".$a_status.",".$newCode.",".$a_price.",".$a_valid.")");
					echo '<script language="JavaScript">SystemBox(3,"已新增级别：'.str_replace('"','',$newName).'（code='.$newCode.'）")</script>';
				}
				$db->cookie("set", '', 300);
				$db->cookie("up", '', 300);
				$db->cookie("adv", '', 300);
				$db->cookie("email", '', 300);
				$db->cookie("sec", '', 300);
				$db->cookie("pay", '', 300);
				$db->cookie("prod", 'active' , 300);
			} elseif ($pAct === 'del') {
				// 删除级别：先校验用户引用；-1/0 保留级不可删
				$dc = (int)$_POST['p_del_code'];
				if ($dc === -1 || $dc === 0) {
					echo '<script language="JavaScript">SystemBox(3,"系统管理员(code=-1)与免费型(code=0)为保留级别，不可删除")</script>';
				} else {
					$cntRow = $db->fetch_array($db->query("SELECT COUNT(*) AS c FROM epan_userdata WHERE diskLevel=".$dc));
					$cnt = (int)$cntRow['c'];
					if ($cnt > 0) {
						echo '<script language="JavaScript">SystemBox(3,"该级别已被 '.$cnt.' 个用户使用，无法删除")</script>';
					} else {
						$db->query("DELETE FROM epan_product WHERE code=".$dc);
						echo '<script language="JavaScript">SystemBox(3,"已删除级别 code='.$dc.'")</script>';
					}
				}
				$db->cookie("set", '', 300);
				$db->cookie("up", '', 300);
				$db->cookie("adv", '', 300);
				$db->cookie("email", '', 300);
				$db->cookie("sec", '', 300);
				$db->cookie("pay", '', 300);
				$db->cookie("prod", 'active' , 300);
			} else {
				// ===== 空间级别 epan_product 批量更新（保留原逻辑）=====
				$codes = isset($_POST['p_code']) && is_array($_POST['p_code']) ? $_POST['p_code'] : array();
				$updCnt = 0;
				foreach ($codes as $c) {
					$c = (int)$c;
					if ($c === 0 && !isset($_POST['p_name'][(string)$c])) continue;
					$name     = isset($_POST['p_name'][(string)$c])     ? $_POST['p_name'][(string)$c]     : '';
					$data     = isset($_POST['p_data'][(string)$c])     ? (int)$_POST['p_data'][(string)$c]     : 0;
					$price    = isset($_POST['p_price'][(string)$c])    ? (float)$_POST['p_price'][(string)$c]  : 0;
					$filemax  = isset($_POST['p_filemax'][(string)$c])   ? (int)$_POST['p_filemax'][(string)$c]   : 0;
					$path     = isset($_POST['p_path'][(string)$c])     ? (int)$_POST['p_path'][(string)$c]     : 0;
					$downnum  = isset($_POST['p_downnum'][(string)$c])   ? (int)$_POST['p_downnum'][(string)$c]   : 0;
					$valid    = isset($_POST['p_valid_days'][(string)$c]) ? (int)$_POST['p_valid_days'][(string)$c] : 365;
					$pay      = isset($_POST['p_pay'][(string)$c])       ? 1 : 0;
					$status   = isset($_POST['p_status'][(string)$c])    ? 1 : 0;
					$sql = "UPDATE epan_product SET
						name='".$db->escape($name)."',
						data=".$data.",
						price=".$price.",
						filemax=".$filemax.",
						path=".$path.",
						downnum=".$downnum.",
						valid_days=".$valid.",
						pay=".$pay.",
						status=".$status."
						WHERE code=".$c;
					if ($db->query($sql)) { $updCnt++; }
				}
				echo '<script language="JavaScript">SystemBox(3,"空间级别已更新（'.$updCnt.' 条）")</script>';
				$db->cookie("set", '', 300);
				$db->cookie("up", '', 300);
				$db->cookie("adv", '', 300);
				$db->cookie("email", '', 300);
				$db->cookie("sec", '', 300);
				$db->cookie("pay", '', 300);
				$db->cookie("prod", 'active' , 300);
			}
			break;

		case 'tab-security':
			// 安全防御设置存储（检测逻辑由 Init.php 实现，此处只落 epan_settings）
			epan_set($db, 'sec_sql_block',     (isset($_POST['sec_sql_block'])     && $_POST['sec_sql_block']==='1')     ? '1' : '0');
			epan_set($db, 'sec_xss_block',      (isset($_POST['sec_xss_block'])      && $_POST['sec_xss_block']==='1')      ? '1' : '0');
			epan_set($db, 'sec_danger_ext',     isset($_POST['sec_danger_ext'])     ? trim($_POST['sec_danger_ext'])     : '');
			epan_set($db, 'sec_rate_limit',    (isset($_POST['sec_rate_limit'])    && $_POST['sec_rate_limit']==='1')    ? '1' : '0');
			epan_set($db, 'sec_rate_max',       (isset($_POST['sec_rate_max'])      && (int)$_POST['sec_rate_max'] > 0)   ? (int)$_POST['sec_rate_max'] : 60);
			epan_set($db, 'sec_rate_window',    (isset($_POST['sec_rate_window'])   && (int)$_POST['sec_rate_window'] > 0) ? (int)$_POST['sec_rate_window'] : 60);
			epan_set($db, 'sec_admin_ip_whitelist', isset($_POST['sec_admin_ip_whitelist']) ? trim($_POST['sec_admin_ip_whitelist']) : '');
			epan_set($db, 'sec_oplog',          (isset($_POST['sec_oplog'])         && $_POST['sec_oplog']==='1')         ? '1' : '0');
			echo '<script language="JavaScript">SystemBox(3,"安全防御设置已保存！")</script>';
			$db->cookie("set", '', 300);
			$db->cookie("up", '', 300);
			$db->cookie("adv", '', 300);
			$db->cookie("email", '', 300);
			$db->cookie("prod", '', 300);
			$db->cookie("pay", '', 300);
			$db->cookie("sec", 'active' , 300);

	}
}

//生成顶部选项夹
$data = array
(
"TabName"=>array("TabName"=>""), //第一个为标题
		   array("Name"=>"基础设置","tab"=>'#tab-set',"active"=>$_COOKIE['set']), //往后的为选项夹
		   array("Name"=>"用户与注册","tab"=>'#tab-email',"active"=>$_COOKIE['email']),
		   array("Name"=>"上传设置","tab"=>'#tab-upload',"active"=>$_COOKIE['up']),
		   array("Name"=>"空间级别","tab"=>'#tab-product',"active"=>$_COOKIE['prod']),
		   array("Name"=>"安全防御","tab"=>'#tab-security',"active"=>$_COOKIE['sec'])
);

$db->FormCreate($data);

// 读取 epan_settings 当前值（前台实际生效值）
$ep_site_maintenance     = epan_get($db, 'site_maintenance');
$ep_site_maintenance_msg = epan_get($db, 'site_maintenance_msg');
$ep_site_title           = epan_get($db, 'site_title');
$ep_footer               = epan_get($db, 'footer');
$ep_khlb                 = epan_get($db, 'khlb');
$ep_reg_open             = epan_get($db, 'reg_open');
$ep_reg_banned           = epan_get($db, 'reg_banned_users');
$ep_up_allow             = epan_get($db, 'upload_type_allow');
$ep_up_deny              = epan_get($db, 'filter_extension');
$ep_upgrade_enable       = epan_get($db, 'upgrade_enable');
$ep_up_dir               = epan_get($db, 'file_path');
$ep_sec_sql   = epan_get($db, 'sec_sql_block');
$ep_sec_xss   = epan_get($db, 'sec_xss_block');
$ep_sec_dext  = epan_get($db, 'sec_danger_ext');
$ep_sec_rl    = epan_get($db, 'sec_rate_limit');
$ep_sec_rmax  = epan_get($db, 'sec_rate_max');
$ep_sec_rwin  = epan_get($db, 'sec_rate_window');
$ep_sec_ip    = epan_get($db, 'sec_admin_ip_whitelist');
$ep_sec_log   = epan_get($db, 'sec_oplog');

// 兼容旧 radio：维护模式 radio 选中态
$maintCheckedOn  = ($ep_site_maintenance === '1') ? 'checked' : '';
$maintCheckedOff  = ($ep_site_maintenance !== '1') ? 'checked' : '';
?><style>
.delimg{margin-left:20px; color:#090; cursor:pointer}
.pd-table th,.pd-table td{padding:6px 8px;border:1px solid #ddd;font-size:13px}
.pd-table th{background:#f3f4f6}
.pd-table input[type=text],.pd-table input[type=number]{width:90px;padding:3px 5px}
.pd-table input.in-name{width:120px}
</style>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script language=javascript>   //文件上传处理
$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var showimg = $('#showimg');
	var progress = $(".progress");
	var files = $(".files");
	var btn = $(".btn span");
	$("#fileupload").wrap("<form id='myupload' action='system/upload.php?act=add&ifr=system' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg.empty();
				progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
				btn.html("上传中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
			success:function(data) {
				if(data.status == '0' || data.status == null)
				{
					var img = "../images/"+data.pic;
					showimg.html("<input type='hidden' name='path' value='"+img+"'><img src='"+img+"'><span class='delimg' rel='"+data.pic+"'>删除</span>");
					$("#fileupload").hide();
					progress.hide();
					files.hide();
				} else {
					btn.html("上传失败");
					bar.width('0')
					files.html(data.message);
				}

			},
		});

	});

	$(".delimg").live('click',function(){
		var pic = $(this).attr("rel");
		$.post("system/upload.php?act=del&ifr=system",{imagename:pic},function(msg){
			var objs = $.parseJSON(msg);
			if(objs.status == 0){
				files.show();
				files.html("删除成功.&nbsp;&nbsp;<a href='javascript:history.go(0)'>刷新</a>");
				showimg.empty();
				progress.hide();
			}else{
				alert(objs.message);
			}
		});
	});

});
</script>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade in <?php echo $_COOKIE['set']; ?>" role="tabpanel" id="tab-set" aria-labelledby="home-tab" >
        	<form method="post" class="form-x" action="#">
        		<input type="hidden" value="tab-system" id="Tab" name="Tab">
						<div class="row">
			<div class="col-md-12 col-xs-12">
					<div class="form-group">
                	<label>网站状态（维护模式）</label>
								<div class="radio info"><input type="radio" name="serverstate" value='0' id="open" <?php echo $maintCheckedOff; ?>><label for="open">正常模式</label></div>
								<div class="radio info"><input type="radio" name="serverstate" value='1' id="close" <?php echo $maintCheckedOn; ?>><label for="close">维护模式（前台访客可见维护说明，管理员可正常访问）</label></div>
                </div>
                <div class="form-group">
                    <label for="sys">维护说明（维护模式开启时展示给访客）</label>
                    	<textarea name="sys" cols="50" rows="3" class="form-control" id="sys" ><?php echo htmlspecialchars($ep_site_maintenance_msg, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="sitename">网站名称（前台 &lt;title&gt; 与头部显示）</label>
                    	<input type="text" class="form-control" id="sitename" name="sitename" size="50" placeholder="请填写你网站的名称" value="<?php echo htmlspecialchars($ep_site_title, ENT_QUOTES, 'UTF-8'); ?>" />
                </div>
                <div class="form-group">
                    <label for="sysinfo">底部内容（前台页脚）</label>
                    	<textarea name="sysinfo" cols="50" rows="3" class="form-control" id="sysinfo" ><?php echo htmlspecialchars($ep_footer, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="khlb">我们的客户（首页滚动客户列表，一行一个客户名，或用分号 ; 分隔）</label>
                    	<textarea name="khlb" cols="50" rows="6" class="form-control" id="khlb" ><?php echo htmlspecialchars($ep_khlb, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="form-group">
                	<label>是否允许用户自主升级空间（关闭后 ht/kjsj.php 升级页与升级 POST 均被拒绝）</label>
								<div class="radio info"><input type="radio" name="upgrade_enable" value='1' id="upg_on" <?php echo ($ep_upgrade_enable==='1')?'checked':''; ?>><label for="upg_on">允许</label></div>
								<div class="radio info"><input type="radio" name="upgrade_enable" value='0' id="upg_off" <?php echo ($ep_upgrade_enable!=='1')?'checked':''; ?>><label for="upg_off">关闭</label></div>
                </div>
                <div><button class="btn btn-primary btn-sm waves-effect waves-light" type="submit">提交</button></div>
            </form></div></div>
        </li></div>
        <div class="tab-pane fade in <?php echo $_COOKIE['email']; ?>" role="tabpanel" id="tab-email" aria-labelledby="home-tab" >
                 <form method="post" class="form-x" action="#">
                 <input type="hidden" value="tab-user" id="Tab" name="Tab">
			<div class="row">
			<div class="col-md-12 col-xs-12">
	          <div class="form-group">
	           <label>开放注册开关</label>
						<div class="radio info"><input type="radio" name="reg_open" value='1' id="reg_on" <?php echo ($ep_reg_open!=='0')?'checked':''; ?>><label for="reg_on">开放注册</label></div>
						<div class="radio info"><input type="radio" name="reg_open" value='0' id="reg_off" <?php echo ($ep_reg_open==='0')?'checked':''; ?>><label for="reg_off">关闭注册（注册页与注册接口均拒绝）</label></div>
	          </div>
	          <div class="form-group">
	           <label for="reg_banned">禁止注册的用户名（逗号分隔，命中即拒绝，不区分大小写）</label>
	           <input type="text" class="form-control" id="reg_banned" name="reg_banned_users" size="50" placeholder="例如：admin,root,test,系统管理员" value="<?php echo htmlspecialchars($ep_reg_banned, ENT_QUOTES, 'UTF-8'); ?>"/>
	          </div>
	          <hr/>
	          <div class="form-group">
	           <label for="old">修改管理员密码（留空则不修改，仅保存上方注册设置）</label>
	            <input type="password" class="form-control" id="old_pwd" name="old_pwd" size="50" placeholder="当前账户旧密码"/>
	          </div>
	          <div class="form-group">
	           <label for="new">新密码</label>
	            <input type="password" class="form-control" id="new_pwd" name="new_pwd" size="50" placeholder="新密码"/>
	          </div>
	           <div class="form-group">
			   <label for="conf_new">确认新密码</label>
	             <input type="password" class="form-control" id="conf_new_pwd" name="conf_new_pwd" size="50" placeholder="再次输入新密码" />
	          </div>
	           <div><button class="btn btn-primary btn-sm waves-effect waves-light" type="submit">保存 / 更新密码</button></div>
			   </div></div>
	         </form>

	        </div>
	        <div class="tab-pane fade <?php echo $_COOKIE['up']; ?> in" role="tabpanel" id="tab-upload" aria-labelledby="home-tab" >
         <form method="post" class="form-x" action="#">
         <input type="hidden" value="tab-upload" id="Tab" name="Tab">
		<div class="row">
		<div class="col-md-12 col-xs-12">
          <div class="form-group">
           <label for="file_size">允许单个文件大小(MB)</label>
            <input type="text" class="form-control" id="file_size" name="file_size" size="20" placeholder="填写允许上传的文件大小,单位MB" value="<?php echo htmlspecialchars(epan_get($db, 'upload_size'), ENT_QUOTES, 'UTF-8'); ?>" />
          </div>
           <div class="form-group">
		    <label>允许上传文件类型 — 白名单（逗号分隔扩展名，留空=不限制；一旦填写，仅允许列表内类型）</label>
             <input type="text" class="form-control" name="upload_type_allow" size="50" placeholder="例如：jpg,jpeg,png,gif,zip,rar,doc,pdf" value="<?php echo htmlspecialchars($ep_up_allow, ENT_QUOTES, 'UTF-8'); ?>"/>
          </div>
		  <div class="form-group">
		    <label>允许上传文件类型 — 黑名单（逗号分隔扩展名，留空=不限制；与白名单同时填写时白名单优先）</label>
		     <input type="text" class="form-control" name="filter_extension" size="50" placeholder="例如：exe,bat,sh,php,asp" value="<?php echo htmlspecialchars($ep_up_deny, ENT_QUOTES, 'UTF-8'); ?>"/>
		  </div>
		  <div class="form-group">
		    <label for="file_path">上传文件存放目录（留空=默认 upfile）</label>
		     <input type="text" class="form-control" id="file_path" name="file_path" size="50" placeholder="例如：upfile 或 upfile2（相对站点根），也可填 D:/pan/data（绝对路径）" value="<?php echo htmlspecialchars($ep_up_dir, ENT_QUOTES, 'UTF-8'); ?>"/>
		     <p style="color:#888;font-size:12px;margin-top:4px;">相对路径按站点根解析（即 up/ 的上一级，D:\Desktop\pan\），如 <b>upfile</b>、<b>upfile2</b>；以盘符开头（如 D:/...）视为绝对路径直接使用。目录不存在自动递归创建。修改后仅影响新上传文件的落盘位置，不迁移历史文件。</p>
		  </div>
		  <div class="form-group">
		   <label>上传参数（既有上传框架）</label>
				<?php
				echo '<select name="tableSelect" size="10" id="uploadSelect" class="form-control" style="margin-top:6px">';
					$UData = $db -> UploadFrame();
					foreach ($UData as $value)
					{
						echo '<option value='.$value[0].'>'.$value[0].'('.str_replace("*","、",$value[1]).')</option>';
					}
				?>
				</select><br />
				<input name="databasebak" type="button" class="btn btn-xs waves-effect waves-light" id="databasebak" href='#' onclick="javascript:SystemBox(2,'system/uploadframe.php?m=add', '','新增上传框架', 400,420)" value="添加" />&nbsp;&nbsp;<input type="button" class="btn btn-xs waves-effect waves-light" href='#' onclick="javascript:SystemBox(2,'system/uploadframe.php?m=edit','','修改上传框架',402,420)" value="编辑" />&nbsp;&nbsp;<input type="button" class="btn btn-xs waves-effect waves-light" href='#' onclick="javascript:SystemBox(2,'/admin/system/uploadframe.php?m=del', '','删除上传框架',235,420)" value="删除" />
				</div>
		  <div><button class="btn btn-primary btn-sm waves-effect waves-light" type="submit">提交</button></div>
         </form></div></div>
        </div>

        <div class="tab-pane fade in <?php echo $_COOKIE['prod']; ?>" role="tabpanel" id="tab-product" aria-labelledby="home-tab" >
		 <form method="post" class="form-x" action="#">
		 <input type="hidden" value="tab-product" id="Tab" name="Tab">
		 <input type="hidden" name="p_act" value="save">
		 <input type="hidden" name="p_del_code" value="">
		<div class="row">
		<div class="col-md-12 col-xs-12">
		  <p style="color:#888;font-size:13px;">空间级别（epan_product）。<b>status=1</b> 表示停用/禁止注册申请；<b>pay=1</b> 表示付费级别；code 为级别标识（只读）。</p>
		  <table class="pd-table">
			<thead><tr>
				<th>级别名</th><th>大小(MB)</th><th>价格(元/年)</th><th>单文件上限(MB)</th><th>目录数上限</th><th>2h下载(MB)</th><th>有效期(天)</th><th>付费</th><th>停用</th><th>code</th><th>操作</th>
			</tr></thead>
			<tbody>
			<?php
			$pres = $db->query("SELECT code,name,data,price,filemax,path,downnum,valid_days,pay,status FROM epan_product ORDER BY code ASC");
			while ($p = $db->fetch_array($pres)) {
				$pc = (int)$p['code'];
				echo '<tr>';
				echo '<td><input class="in-name" type="text" name="p_name['.$pc.']" value="'.htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8').'"></td>';
				echo '<td><input type="number" name="p_data['.$pc.']" value="'.(int)$p['data'].'"></td>';
				echo '<td><input type="number" step="0.01" name="p_price['.$pc.']" value="'.htmlspecialchars($p['price'], ENT_QUOTES, 'UTF-8').'"></td>';
				echo '<td><input type="number" name="p_filemax['.$pc.']" value="'.(int)$p['filemax'].'"></td>';
				echo '<td><input type="number" name="p_path['.$pc.']" value="'.(int)$p['path'].'"></td>';
				echo '<td><input type="number" name="p_downnum['.$pc.']" value="'.(int)$p['downnum'].'"></td>';
				echo '<td><input type="number" name="p_valid_days['.$pc.']" value="'.(int)$p['valid_days'].'"></td>';
				echo '<td><input type="checkbox" name="p_pay['.$pc.']" value="1" '.((int)$p['pay']===1?'checked':'').'></td>';
				echo '<td><input type="checkbox" name="p_status['.$pc.']" value="1" '.((int)$p['status']===1?'checked':'').'></td>';
				echo '<td>'.$pc.'<input type="hidden" name="p_code[]" value="'.$pc.'"></td>';
				$delBtn = ($pc===-1 || $pc===0) ? '<span style="color:#bbb;">保留</span>' : '<button type="button" class="btn btn-xs btn-danger" onclick="delLevel('.$pc.',\''.addslashes(htmlspecialchars($p['name'],ENT_QUOTES,'UTF-8')).'\')">删除</button>';
				echo '<td>'.$delBtn.'</td>';
				echo '</tr>';
			}
			?>
			</tbody>
		  </table>
		  <p style="color:#888;font-size:12px;margin-top:8px;">注：复选框未勾选 = 0；"停用"勾选后该级别不再出现在注册/升级可选列表中。删除前会校验是否仍被用户引用；code=-1（系统管理员）与 code=0（免费型）不可删除。</p>
		  <div><button class="btn btn-primary btn-sm waves-effect waves-light" type="submit">保存级别</button></div>
		 </div></div>
		 </form>
		 <script language="javascript">
		 function delLevel(c, nm){
			if(!confirm('确认删除级别「'+nm+'」(code='+c+')？删除前将校验用户引用。')) return;
			document.forms[0].p_act.value='del';
			document.forms[0].p_del_code.value=c;
			document.forms[0].submit();
		 }
		 </script>
		 <div class="row" style="margin-top:14px;border-top:1px dashed #ccc;padding-top:10px;">
		 <div class="col-md-12 col-xs-12">
		  <p style="color:#090;font-size:13px;"><b>新增级别</b>（code 自动取当前最大+1）</p>
		  <form method="post" class="form-x" action="#">
		   <input type="hidden" value="tab-product" name="Tab">
		   <input type="hidden" name="p_act" value="add">
		   <table class="pd-table">
			 <thead><tr><th>级别名</th><th>大小(MB)</th><th>价格(元/年)</th><th>单文件上限(MB)</th><th>目录数上限</th><th>2h下载(MB)</th><th>有效期(天)</th><th>付费</th><th>停用</th><th></th></tr></thead>
			 <tbody><tr>
				<td><input class="in-name" type="text" name="p_new_name" placeholder="级别名"></td>
				<td><input type="number" name="p_new_data" value="1024"></td>
				<td><input type="number" step="0.01" name="p_new_price" value="0"></td>
				<td><input type="number" name="p_new_filemax" value="100"></td>
				<td><input type="number" name="p_new_path" value="100"></td>
				<td><input type="number" name="p_new_downnum" value="500"></td>
				<td><input type="number" name="p_new_valid_days" value="365"></td>
				<td><input type="checkbox" name="p_new_pay" value="1"></td>
				<td><input type="checkbox" name="p_new_status" value="1"></td>
				<td><button class="btn btn-success btn-sm" type="submit">新增</button></td>
			 </tr></tbody>
		   </table>
		  </form>
		 </div></div>
		 </div>

        <div class="tab-pane fade in <?php echo $_COOKIE['sec']; ?>" role="tabpanel" id="tab-security" aria-labelledby="home-tab" >
		 <form method="post" class="form-x" action="#">
		 <input type="hidden" value="tab-security" name="Tab">
		 <div class="row">
		 <div class="col-md-12 col-xs-12">
		  <p style="color:#888;font-size:13px;">安全防御设置（开关与参数落库 epan_settings；实际检测逻辑在系统引导层实现）。</p>
		  <div class="form-group"><label>SQL 注入拦截</label>
			<div class="radio info"><input type="radio" name="sec_sql_block" value="1" id="ssql_on" <?php echo ($ep_sec_sql==='1')?'checked':''; ?>><label for="ssql_on">开启</label></div>
			<div class="radio info"><input type="radio" name="sec_sql_block" value="0" id="ssql_off" <?php echo ($ep_sec_sql!=='1')?'checked':''; ?>><label for="ssql_off">关闭</label></div>
		  </div>
		  <div class="form-group"><label>XSS 过滤</label>
			<div class="radio info"><input type="radio" name="sec_xss_block" value="1" id="sxss_on" <?php echo ($ep_sec_xss==='1')?'checked':''; ?>><label for="sxss_on">开启</label></div>
			<div class="radio info"><input type="radio" name="sec_xss_block" value="0" id="sxss_off" <?php echo ($ep_sec_xss!=='1')?'checked':''; ?>><label for="sxss_off">关闭</label></div>
		  </div>
		  <div class="form-group"><label for="sec_dext">危险上传扩展名黑名单（逗号分隔，留空=不限制）</label>
			<input type="text" class="form-control" name="sec_danger_ext" size="50" placeholder="例如：php,jsp,asp,sh,exe" value="<?php echo htmlspecialchars($ep_sec_dext, ENT_QUOTES, 'UTF-8'); ?>"/>
		  </div>
		  <div class="form-group"><label>请求频率限制</label>
			<div class="radio info"><input type="radio" name="sec_rate_limit" value="1" id="srl_on" <?php echo ($ep_sec_rl==='1')?'checked':''; ?>><label for="srl_on">开启</label></div>
			<div class="radio info"><input type="radio" name="sec_rate_limit" value="0" id="srl_off" <?php echo ($ep_sec_rl!=='1')?'checked':''; ?>><label for="srl_off">关闭</label></div>
		  </div>
		  <div class="form-group"><label for="srmax">窗口内最大请求数</label>
			<input type="number" class="form-control" name="sec_rate_max" style="width:120px;" value="<?php echo htmlspecialchars($ep_sec_rmax===''?'60':$ep_sec_rmax, ENT_QUOTES, 'UTF-8'); ?>"/>
		  </div>
		  <div class="form-group"><label for="srwin">频率窗口（秒）</label>
			<input type="number" class="form-control" name="sec_rate_window" style="width:120px;" value="<?php echo htmlspecialchars($ep_sec_rwin===''?'60':$ep_sec_rwin, ENT_QUOTES, 'UTF-8'); ?>"/>
		  </div>
		  <div class="form-group"><label for="secip">后台访问 IP 白名单（逗号分隔，留空=不限制；命中才允许进后台）</label>
			<input type="text" class="form-control" name="sec_admin_ip_whitelist" size="50" placeholder="例如：127.0.0.1,192.168.1.10" value="<?php echo htmlspecialchars($ep_sec_ip, ENT_QUOTES, 'UTF-8'); ?>"/>
		  </div>
		  <div class="form-group"><label>操作日志记录</label>
			<div class="radio info"><input type="radio" name="sec_oplog" value="1" id="slog_on" <?php echo ($ep_sec_log==='1')?'checked':''; ?>><label for="slog_on">开启</label></div>
			<div class="radio info"><input type="radio" name="sec_oplog" value="0" id="slog_off" <?php echo ($ep_sec_log!=='1')?'checked':''; ?>><label for="slog_off">关闭</label></div>
		  </div>
		  <div><button class="btn btn-primary btn-sm waves-effect waves-light" type="submit">提交</button></div>
		 </div></div>
		 </form>
		 </div>
        </div></div></div>
      </div></div></ul>
    </div>
<?php echo '<script>javascript:StatusEdit('.$db->Info('server_status').',\'open\',\'close\');StatusEdit('.$db->Info('VaildCode').',\'enable\',\'disable\');</script>'; ?>
