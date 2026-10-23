<?php
$uid = 3;
$sid = 4;

if($db -> LoginCheck() != 0){
	$bottom = '<input type="submit" name="bu1" value="保存设置" id="bu1">';
	} else {
	$bottom = '<input type="submit" name="bu1" value="保存设置" id="bu1" disabled="disabled"><font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font>';
	}

//读当前访客权限（未设置空间行按允许=1）
$fb = $db -> SpaceSetting($_SESSION['username_epan'],'fk_browse');
$fd = $db -> SpaceSetting($_SESSION['username_epan'],'fk_download');
$fu = $db -> SpaceSetting($_SESSION['username_epan'],'fk_upload');
if($fb === '' || $fb === NULL){ $fb = 1; } else { $fb = (int)$fb; }
if($fd === '' || $fd === NULL){ $fd = 1; } else { $fd = (int)$fd; }
if($fu === '' || $fu === NULL){ $fu = 1; } else { $fu = (int)$fu; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$b = isset($_POST['fk_browse']) ? 1 : 0;
	$d = isset($_POST['fk_download']) ? 1 : 0;
	$u = isset($_POST['fk_upload']) ? 1 : 0;
	//SpaceUpdate 以 affected_rows(变化行) 判返回，值未变化时返回 0；这里参数已合法（0/1 固定值），执行更新后即视为保存成功
	$db ->SpaceUpdate($_SESSION['username_epan'] , 'fk_browse' , $b);
	$db ->SpaceUpdate($_SESSION['username_epan'] , 'fk_download' , $d);
	$db ->SpaceUpdate($_SESSION['username_epan'] , 'fk_upload' , $u);
	$db ->message('保存成功！');
	}
?>
<div id="ysright">
	<h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
设置访客权限</h1>
	<div class="ysdb3">
	<form name="qxform" method="post" action="?method=qx" id="qxform">
		<div class="qx-card">
			<label class="qx-row">
				<input type="checkbox" name="fk_browse" value="1" <?php if($fb==1){echo 'checked="checked"';}?> />
				<span class="qx-name">允许访客浏览文件列表</span>
				<span class="qx-desc">关闭后，未登录访客进入空间将看不到目录与文件列表。</span>
			</label>
			<label class="qx-row">
				<input type="checkbox" name="fk_download" value="1" <?php if($fd==1){echo 'checked="checked"';}?> />
				<span class="qx-name">允许访客下载</span>
				<span class="qx-desc">关闭后，访客的文件下载链接将被禁用。</span>
			</label>
			<label class="qx-row">
				<input type="checkbox" name="fk_upload" value="1" <?php if($fu==1){echo 'checked="checked"';}?> />
				<span class="qx-name">允许访客上传</span>
				<span class="qx-desc">关闭后，访客的上传入口将被隐藏并拒绝打开。</span>
			</label>
		</div>
		<p style="margin-top:18px;"><?php echo $bottom;?></p>
	</form>
	<ul class="yslb2" style="margin-top:8px;">
		<li>空间主人（已登录本空间账户）与空间管理区登录者不受以上限制。</li>
		<li>未登录访客浏览时受「允许访客浏览」限制；下载链接受「允许访客下载」限制；上传窗口受「允许访客上传」限制。</li>
	</ul>
</div>
<style>
/* 访客权限设置卡片（ht 后台设计 token：#2563eb） */
.qx-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px 20px;max-width:560px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.qx-row{display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid #f3f4f6;cursor:pointer}
.qx-row:last-child{border-bottom:none}
.qx-row input{margin-top:3px;width:16px;height:16px;accent-color:#2563eb}
.qx-name{font-weight:600;color:#1f2937;min-width:150px}
.qx-desc{color:#6b7280;font-size:9pt}
</style>
