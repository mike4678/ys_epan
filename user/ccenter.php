<?php
include "../includes/Init.php";
$_name = isset($_GET['name']) ? $_GET['name'] : ''; //原始值；SQL转义由System方法内部负责，直接query处单独escape
//真实剩余空间(MB)与在线人数（替代原硬编码 1000.0 / 1，逻辑同 C_zxsj.php）
$_sy = 0; $_zx = 0;
if ($_name != '') {
	$usArr = $db->fetch_array($db->query('select SUM(file_size) from epan_files where userid = (select userid from epan_users where username = "'.$db->escape($_name).'");'));
	$_sy = round($db->UserSpaceSize($_name) - ($usArr[0] / 1024 / 1024), 2);
	$onArr = $db->fetch_array($db->query("select count(*) from epan_OnlineUser where username = '".$db->escape($_name)."';"));
	$_zx = (int)$onArr[0];
}
//访客权限（fk_*：0=关闭 1=允许；空间主人与管理区登录者不受限）
$fkBrowse = 1; $fkDownload = 1; $fkUpload = 1;
if ($_name != '') {
	$fkBrowse = $db->SpaceSetting($_name,'fk_browse');
	$fkDownload = $db->SpaceSetting($_name,'fk_download');
	$fkUpload = $db->SpaceSetting($_name,'fk_upload');
	if($fkBrowse === '' || $fkBrowse === NULL){ $fkBrowse = 1; } else { $fkBrowse = (int)$fkBrowse; }
	if($fkDownload === '' || $fkDownload === NULL){ $fkDownload = 1; } else { $fkDownload = (int)$fkDownload; }
	if($fkUpload === '' || $fkUpload === NULL){ $fkUpload = 1; } else { $fkUpload = (int)$fkUpload; }
}
$isOwner = (isset($_SESSION['username_epan']) && $_SESSION['username_epan'] == $_name);
$isMgmt = isset($_SESSION['gly_'.$_name]);
$isGuest = !($isOwner || $isMgmt);
//上传接收端 host：Website(disk_url) 为空时退化为当前 HTTP_HOST，保证上传表单 action 可解析
$_f_s_base = (defined('Website') && trim(Website) !== '') ? rtrim(Website,'/') : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
$f_s_js = $_f_s_base . '/up';
//渲染一个目录节点 <li>（根目录/子目录通用结构，与初始 GET 加载保持一致）
function render_folder_li($fr) {
	$hasPw = (trim($fr['password']) != '') ? '1' : '0';
	$fo = preg_replace('/[^01]/','',(string)$fr['fileorder']);
	if($fo == '' || strlen($fo) != 6) { $fo = '011000'; }
	return "<li id='x".(int)$fr['folder_id']."_0|".$fo."|".$hasPw."'><table cellspacing='1'><tr><td class='g'></td><td class='r'></td><td class='g'></td></tr></table><img border=0 src='user/images/mlgk.gif'/><a href='javascript:;' class='ml'>".htmlspecialchars($fr['folder_name'],ENT_QUOTES,'UTF-8')."</a><label></label><ul style='display:none;' id='ZMm_".(int)$fr['folder_id']."' class=menu></ul>";
}
//读取某目录下的直接子目录节点 HTML（用于打开目录时展示子目录）
function subfolder_html($db, $_name, $folderId) {
	$out = '';
	$fq = $db->query('SELECT folder_id, folder_name, password, fileorder FROM epan_folders WHERE userid=(select userid from epan_users where username="'.$db->escape($_name).'") AND parent_id='.(int)$folderId);
	while($fr = $db->fetch_array($fq)) { $out .= render_folder_li($fr); }
	return $out;
}
//任务5：管理区登录态——GET 分支 Q.a 注入值（true=已登录/管理员，false=普通访客）
$qav = ($isOwner || $isMgmt) ? 'true' : 'false';
//Get部分
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	//目录列表读取
	if($isGuest && $fkBrowse == 0) {
		$ys3 = "<li id='demo' style='padding:5px;font-size:9pt;'>空间主人已关闭访客浏览权限</li>";
		$jscode = "function onloads(){parent.Qcxzt=true;parent.ysdy();zx();setTimeout('parent.Jxzx();',200);}
	function zx(){var F=document.forms[0];parent.Q.fmax=15;parent.Q.a=$qav;parent.glyqxkz();parent.Q.fk_browse='".$fkBrowse."';parent.Q.fk_download='".$fkDownload."';parent.Q.fk_upload='".$fkUpload."';parent._rz.rzjl='';parent.$('zxrsts').innerHTML='.$_zx.';parent.Q.f_s='".$f_s_js."';parent.ymbgz('kjz2', '".$_sy."');parent._m.xs();parent.nr.value='';parent.Lyb_xs();parent.lygxpd=0;parent.$('demo').innerText='空间主人已关闭访客浏览权限';setTimeout(dy(),15000);}
	function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
	} else if($db -> LoadingFolder($_name) == '') {
		$ys3 = "<li id='demo' style='padding:5px;font-size:9pt;'></li>";
		$jscode = "function onloads(){parent.Qcxzt=true;parent.ysdy();zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.fmax=15;parent.Q.a=$qav;parent.glyqxkz();parent.Q.fk_browse='".$fkBrowse."';parent.Q.fk_download='".$fkDownload."';parent.Q.fk_upload='".$fkUpload."';parent._rz.rzjl='';parent.$('zxrsts').innerHTML='.$_zx.';parent.Q.f_s='".$f_s_js."';parent.ymbgz('kjz2', '".$_sy."');parent._m.xs();parent.nr.value='';parent.Lyb_xs();parent.lygxpd=0;parent.document.getElementById('demo').innerText = parent.C.nolist;setTimeout(dy(),15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
		} else { 
		$jscode = "function onloads(){parent.Qcxzt=true;parent.ysdy();zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.fmax=15;parent.Q.a=$qav;parent.glyqxkz();parent.Q.fk_browse='".$fkBrowse."';parent.Q.fk_download='".$fkDownload."';parent.Q.fk_upload='".$fkUpload."';parent._rz.rzjl='';parent.$('zxrsts').innerHTML='.$_zx.';parent.Q.f_s='".$f_s_js."';parent.ymbgz('kjz2', '".$_sy."');parent._m.xs();parent.nr.value='';parent.Lyb_xs();parent.lygxpd=0;setTimeout(dy(),15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
		$ys3 = '';
		//查询目录的密码与权限串（fileorder），用于密码目录认证
		$fq = $db->query('SELECT f.folder_id, f.folder_name, f.password, f.fileorder FROM epan_folders f WHERE f.userid = (select userid from epan_users where username = "'.$db->escape($_name).'") AND f.parent_id = -1;');
		while($fr = $db->fetch_array($fq)) {
			$hasPw = (trim($fr['password']) != '') ? '1' : '0';
			$fo = preg_replace('/[^01]/','',(string)$fr['fileorder']);
			if($fo == '' || strlen($fo) != 6) { $fo = '011000'; }
			$ys3.="<li id='x".(int)$fr['folder_id']."_0|".$fo."|".$hasPw."'><table cellspacing='1'><tr><td class='g'></td><td class='r'></td><td class='g'></td></tr></table><img border=0 src='user/images/mlgk.gif'/><a href='javascript:;' class='ml'>".htmlspecialchars($fr['folder_name'],ENT_QUOTES,'UTF-8')."</a><label></label><ul style='display:none;' id='ZMm_".(int)$fr['folder_id']."' class=menu></ul>";
		}
				
	}
	//留言内容读取
	if($db -> LoadingGuestbook($_name,'userspace') == NULL) {
		$ys4 = "<P><br>&nbsp;尚无留言！</P><div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共0条记录<br>&nbsp;</div>";
		} else { 
		$ys4 = '';
		$u = $db -> LoadingGuestbook($_name,'userspace');
		foreach($u as $list=>$things){
			$ipRaw = isset($things['ip']) ? $things['ip'] : '';
			$ipMask = htmlspecialchars(preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$ipRaw),ENT_QUOTES,'UTF-8');
			$ipLast = preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$4",$ipRaw);
			if(!preg_match('/^[0-9]{1,3}$/',(string)$ipLast)) { $ipLast = '0'; }
			$ys4.="<p class='cslyb'><img src='user/face/f".(int)$things['face'].".gif' onclick='lyedit(".(int)$things['id'].");'><b id='Xm_".(int)$things['id']."'>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</b><br><span id='Nr_".(int)$things['id']."'>".htmlspecialchars($things['data'],ENT_QUOTES,'UTF-8')."</span><br><label>". date('Y-m-d H:i:s', $things['t']) ."</label><label id='i1_".$ipLast."'>IP: ".$ipMask."</label></p>";
		}
		$ys4.="<div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共".count($u)."条记录<br>&nbsp;</div>";		
	}
}

//Get 部分结束	

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
	switch($_POST['Yz0']) {
		//默认加载
		case 'dq':
			$yz1 = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			if($_POST['Yz1'] == NULL) {   //没有指定目录
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$ys1 = "";
				break;
			} else if($isGuest && $fkBrowse == 0) {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$ys1 = "<li id='demo' style='padding:5px;font-size:9pt;'>空间主人已关闭访客浏览权限</li>";
				break;
			} else if($db->LoadingFile($_name,$yz1) != NULL) {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$u = $db -> LoadingFile($_name,$yz1);
				foreach($u as $list=>$things){
				$ys1.="<li><img src='user/images/url.gif' id=W_".(int)$things['id']." onclick='_fbj.tj(this)'><a href='".htmlspecialchars(($isGuest && $fkDownload == 0) ? 'javascript:;' : $things['url'],ENT_QUOTES,'UTF-8')."' class=lj>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</a><font></font></li>";
					}
				$ys1 = subfolder_html($db, $_name, $yz1) . $ys1;
				break;
				} else {
					$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
					$ys1 = subfolder_html($db, $_name, $yz1);
					break;
			}

		
		//管理区登录
		case 'gly_dl' :
					$_admr = $db->fetch_array($db->query("select is_Administrator from epan_userdata where user='".$db->escape($_name)."'"));
			$_admA = ($_admr && (int)$_admr['is_Administrator']==1) ? 1 : 0;
			if($db -> PWDCheck ($_name , $_POST['Yz1']) == 1 || $_admA) {
			$db -> SpaceUpdate ($_name  , 'login' , base64_encode(TS));
			$_SESSION['gly_'.$_name] = TS;
			$jscode = "
document.domain = qdhost()
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.a=true;parent.glyqxkz();parent.loginsucc();parent.lygxjc();setTimeout('dy()', 15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }"; 
			break;
	 		} else { 
				$jscode = "
document.domain = qdhost()
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];alert(parent.C.lr1);parent.$('bdglymm').focus();setTimeout('dy()', 15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";  
				break;
				}
		
		//留言提交
		case 'lytj':
		$face = (int)str_replace("n","",$_POST['Yz1']);
		if($db -> WriteGuestbook ($_name,$_POST['Yz2'],$_POST['Yz3'],$face) == 1) {
			if(function_exists('epan_oplog')) epan_oplog('feedback','to='.$_name.' from='.(isset($_POST['Yz2'])?$_POST['Yz2']:'').' len='.strlen(isset($_POST['Yz3'])?$_POST['Yz3']:''));
			$jscode = "
document.domain = qdhost()
function onloads(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent.nr.value='';parent.Qlyfyxx='';parent.Lyb_xs();parent.lygxpd=0;parent.Qcxzt=true;setTimeout('dy()',15000);} function qdhost() { var x = document.domain.split("."); if (x.length == 3) return x[1] + "." + x[2]; return x[2] + "." + x[3]; }";
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			$ys4 = '';
			$u = $db -> LoadingGuestbook($_name,'userspace');
			foreach($u as $list=>$things){
			$ipRaw = isset($things['ip']) ? $things['ip'] : '';
			$ipMask = htmlspecialchars(preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$ipRaw),ENT_QUOTES,'UTF-8');
			$ipLast = preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$4",$ipRaw);
			if(!preg_match('/^[0-9]{1,3}$/',(string)$ipLast)) { $ipLast = '0'; }
			$ys4.="<p class='cslyb'><img src='user/face/f".(int)$things['face'].".gif' onclick='lyedit(".(int)$things['id'].");'><b id='Xm_".(int)$things['id']."'>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</b><br><span id='Nr_".(int)$things['id']."'>".htmlspecialchars($things['data'],ENT_QUOTES,'UTF-8')."</span><br><label>". date('Y-m-d H:i:s', $things['t']) ."</label><label id='i1_".$ipLast."'>IP: ".$ipMask."</label></p>";
				}
			$ys4.="<div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共".count($u)."条记录</div>";	
			break;
			} else { 
				$jscode = "
document.domain = qdhost()
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];alert('提交失败');parent.$('bdglymm').focus();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";  
				break;
				}
				
		//留言修改		
		case 'lyxg':
		$face = (int)str_replace("n","",$_POST['Yz1']);
		if($db -> EditGuestbook ((int)$_POST['Yz4'],$_POST['Yz2'],$_name,$_POST['Yz3'],$face) == 1) { 
			$jscode = "
document.domain = qdhost()
function onloads(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent.nr.value='';parent.Qlyfyxx='';parent.Lyb_xs();parent.lygxpd=0;parent.Qcxzt=true;setTimeout('dy()',15000);} function qdhost() { var x = document.domain.split("."); if (x.length == 3) return x[1] + "." + x[2]; return x[2] + "." + x[3]; }";
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			$ys4 = '';
			$u = $db -> LoadingGuestbook($_name,'userspace');
			foreach($u as $list=>$things){
			$ipRaw = isset($things['ip']) ? $things['ip'] : '';
			$ipMask = htmlspecialchars(preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$ipRaw),ENT_QUOTES,'UTF-8');
			$ipLast = preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$4",$ipRaw);
			if(!preg_match('/^[0-9]{1,3}$/',(string)$ipLast)) { $ipLast = '0'; }
			$ys4.="<p class='cslyb'><img src='user/face/f".(int)$things['face'].".gif' onclick='lyedit(".(int)$things['id'].");'><b id='Xm_".(int)$things['id']."'>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</b><br><span id='Nr_".(int)$things['id']."'>".htmlspecialchars($things['data'],ENT_QUOTES,'UTF-8')."</span><br><label>". date('Y-m-d H:i:s', $things['t']) ."</label><label id='i1_".$ipLast."'>IP: ".$ipMask."</label></p>";
				}
			$ys4.="<div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共".count($u)."条记录</div>";	
			break;
			} else { 
				$jscode = "
document.domain = qdhost()
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];alert('更改失败');parent.$('bdglymm').focus();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";  
				break;
				}
			
		//留言删除		
		case 'lysc':
		if($db -> DelGuestbook ($_name,(int)$_POST['Yz1']) == 1) { 
			$jscode = "document.domain = qdhost();
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent.Qlyfyxx='';parent.Lyb_xs();parent.lygxpd=0;setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split("."); if (x.length == 3) return x[1] + "." + x[2]; return x[2] + "." + x[3]; }";
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			$ys1 = htmlspecialchars($_POST['Yz1'],ENT_QUOTES,'UTF-8');
			$ys4 = '';
			$u = $db -> LoadingGuestbook($_name,'userspace');
			foreach($u as $list=>$things){
			$ipRaw = isset($things['ip']) ? $things['ip'] : '';
			$ipMask = htmlspecialchars(preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$ipRaw),ENT_QUOTES,'UTF-8');
			$ipLast = preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$4",$ipRaw);
			if(!preg_match('/^[0-9]{1,3}$/',(string)$ipLast)) { $ipLast = '0'; }
			$ys4.="<p class='cslyb'><img src='user/face/f".(int)$things['face'].".gif' onclick='lyedit(".(int)$things['id'].");'><b id='Xm_".(int)$things['id']."'>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</b><br><span id='Nr_".(int)$things['id']."'>".htmlspecialchars($things['data'],ENT_QUOTES,'UTF-8')."</span><br><label>". date('Y-m-d H:i:s', $things['t']) ."</label><label id='i1_".$ipLast."'>IP: ".$ipMask."</label></p>";
				}
			$ys4.="<div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共".count($u)."条记录</div>";
			break;	
			} else { 
				$jscode = "
document.domain = qdhost()
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];alert('删除失败');parent.$('bdglymm').focus();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";  
				break;
				}	
			
		//管理员退出
		case 'gly_tc':
			$db -> SpaceUpdate ($_name  , 'login' , '');
			unset($_SESSION['gly_'.$_name]);
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.a=false;parent.glyqxkz();parent.lygxjc();parent.$('mfkjtz').innerHTML='';setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;
			
			
		case 'xszxlb':
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent.D_zxlb_i.$('nr').innerHTML=F.Yz1.value;setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			$ys1 = "<table class='sjtb' cellspacing='0' cellpadding='2'><tr class='trbt'><td width='33'>编号</td><td>IP</td><td>进入时间</td></tr>";
			$u = $db -> query('select * from epan_OnlineUser where username = "'.$db->escape($_name).'";');
			foreach($u as $list=>$things){
			$ys1.="<tr><td>". (int)$things['id'] ."</td><td><font color=blue>". htmlspecialchars($things['ip'],ENT_QUOTES,'UTF-8') ."</td><td>". date('Y-m-d H:i:s', $things['dtstamp']) ."</td></tr>";
				}	
			$ys1.= "</table>";
			break;
			
			
		case 'xsdllb':
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			$page = isset($_POST['Yz1']) ? max(1,(int)$_POST['Yz1']) : 1;
			$rxx = ''; $ys1 = '';
			if($isOwner || $isMgmt){
				$where = "userid = (select userid from epan_users where username = '".$db->escape($_name)."')";
				$tc = $db->fetch_array($db->query("select count(*) c from epan_download where ".$where));
				$total = (int)$tc['c'];
				$per = 20;
				$pages = max(1, ceil($total/$per));
				if($page > $pages) $page = $pages;
				$start = ($page-1)*$per;
				$u = $db->query("select * from epan_download where ".$where." order by id desc limit ".$start.",".$per);
				$ys1 = "<table class='sjtb' cellspacing='0' cellpadding='2'><tr class='trbt'><td width='40'>编号</td><td>文件名</td><td>大小</td><td>IP</td><td>下载时间</td></tr>";
				foreach($u as $things){
					$ys1.="<tr><td>".(int)$things['id']."</td><td>".htmlspecialchars($things['wenjian'],ENT_QUOTES,'UTF-8')."</td><td>".htmlspecialchars($things['dx'],ENT_QUOTES,'UTF-8')."</td><td>".htmlspecialchars($things['ip'],ENT_QUOTES,'UTF-8')."</td><td>".date('Y-m-d H:i:s',$things['downtime'])."</td></tr>";
				}
				$ys1.= "</table>";
				$rxx = $total.",".$page.",".$pages.",".($page>1?'1':'0').",".($page<$pages?'1':'0').",".($page==$pages?'1':'0');
			}
			$jscode = "
	function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
	function zx(){var F=document.forms[0];parent.D_xzlb_i.$('nr').innerHTML=F.Yz1.value;parent.D_xzlb_i.clfy('".$rxx."');setTimeout('dy()',15000);}
	function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;

		case 'xzml':
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			$mlName = isset($_POST['Yz1']) ? trim($_POST['Yz1']) : '';
			$mlSm = isset($_POST['Yz2']) ? trim($_POST['Yz2']) : '';
			$mlMm = isset($_POST['Yz3']) ? $_POST['Yz3'] : '';
			$mlPx = isset($_POST['Yz4']) ? $_POST['Yz4'] : '011000';
			if($mlName != '' && strlen($mlName) <= 150 && strlen($mlSm) <= 765 && $db -> AddFolder($_name,$mlName,$mlSm,$mlMm,$mlPx) != NULL) { 
				$jscode = "
function onloads(){parent.Qcxzt=true;parent.ysdy();zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.xs();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$u = $db -> LoadingFolder($_name);
				$ys3 = '';
				if($u != NULL) {
					$fq = $db->query('SELECT f.folder_id, f.folder_name, f.password, f.fileorder FROM epan_folders f WHERE f.userid = (select userid from epan_users where username = "'.$db->escape($_name).'") AND f.parent_id = -1;');
					while($fr = $db->fetch_array($fq)) {
						$hasPw = (trim($fr['password']) != '') ? '1' : '0';
						$fo = preg_replace('/[^01]/','',(string)$fr['fileorder']);
						if($fo == '' || strlen($fo) != 6) { $fo = '011000'; }
						$ys3.="<li id='x".(int)$fr['folder_id']."_0|".$fo."|".$hasPw."'><table cellspacing='1'><tr><td class='g'></td><td class='r'></td><td class='g'></td></tr></table><img border=0 src='user/images/mlgk.gif'/><a href='javascript:;' class='ml'>".htmlspecialchars($fr['folder_name'],ENT_QUOTES,'UTF-8')."</a><label></label><ul style='display:none;' id='ZMm_".(int)$fr['folder_id']."' class=menu></ul>";
					}
				} else { $ys3 = "<li id='demo' style='padding:5px;font-size:9pt;'></li>"; }
				} else { 
				$jscode = "
document.domain = qdhost()
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];alert('提交失败');setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";  
				}
				break;
				
		case 'ml_dq':
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			if($db -> LoadingFolder($_name) == NULL) {
				$ys3 = "<li id='demo' style='padding:5px;font-size:9pt;'></li>";
				} else { 
				$ys3 = '';
				$fq = $db->query('SELECT f.folder_id, f.folder_name, f.password, f.fileorder FROM epan_folders f WHERE f.userid = (select userid from epan_users where username = "'.$db->escape($_name).'") AND f.parent_id = -1;');
				while($fr = $db->fetch_array($fq)) {
					$hasPw = (trim($fr['password']) != '') ? '1' : '0';
					$fo = preg_replace('/[^01]/','',(string)$fr['fileorder']);
					if($fo == '' || strlen($fo) != 6) { $fo = '011000'; }
					$ys3.="<li id='x".(int)$fr['folder_id']."_0|".$fo."|".$hasPw."'><table cellspacing='1'><tr><td class='g'></td><td class='r'></td><td class='g'></td></tr></table><img border=0 src='user/images/mlgk.gif'/><a href='javascript:;' class='ml'>".htmlspecialchars($fr['folder_name'],ENT_QUOTES,'UTF-8')."</a><label></label><ul style='display:none;' id='ZMm_".(int)$fr['folder_id']."' class=menu></ul>";
				}
				}
			$jscode = "
function onloads(){parent.Qcxzt=true;parent.ysdy();zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.xs();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;
			
		case 'lyb_dq':
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			if($db -> LoadingGuestbook($_name,'userspace') == NULL) {
				$ys4 = "<P><br>&nbsp;尚无留言！</P><div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共0条记录<br>&nbsp;</div>";
				} else { 
				$ys4 = '';
				$u = $db -> LoadingGuestbook($_name,'userspace');
				foreach($u as $list=>$things){
				$ipRaw = isset($things['ip']) ? $things['ip'] : '';
				$ipMask = htmlspecialchars(preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$ipRaw),ENT_QUOTES,'UTF-8');
				$ipLast = preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$4",$ipRaw);
				if(!preg_match('/^[0-9]{1,3}$/',(string)$ipLast)) { $ipLast = '0'; }
				$ys4.="<p class='cslyb'><img src='user/face/f".(int)$things['face'].".gif' onclick='lyedit(".(int)$things['id'].");'><b id='Xm_".(int)$things['id']."'>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</b><br><span id='Nr_".(int)$things['id']."'>".htmlspecialchars($things['data'],ENT_QUOTES,'UTF-8')."</span><br><label>". date('Y-m-d H:i:s', $things['t']) ."</label><label id='i1_".$ipLast."'>IP: ".$ipMask."</label></p>";
				}
				$ys4.="<div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共".count($u)."条记录<br>&nbsp;</div>";		
				}
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent.Lyb_xs();parent.lygxpd=0;setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;
			
		case 'dqfile':
			$yz1 = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			if($_POST['Yz1'] == NULL) {   //没有指定目录
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$ys1 = "";
				break;
				} else if($isGuest && $fkBrowse == 0) {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$ys1 = "<li id='demo' style='padding:5px;font-size:9pt;'>空间主人已关闭访客浏览权限</li>";
				break;
			} else if($db->LoadingFile($_name,$yz1) != NULL) {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$u = $db -> LoadingFile($_name,$yz1);
				foreach($u as $list=>$things){
				$ys1.="<li><img src='user/images/url.gif' id=W_".(int)$things['id']." onclick='_fbj.tj(this)'><a href='".htmlspecialchars(($isGuest && $fkDownload == 0) ? 'javascript:;' : $things['url'],ENT_QUOTES,'UTF-8')."' class=lj>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</a><font></font></li>";
					}
				$ys1 = subfolder_html($db, $_name, $yz1) . $ys1;
				break;
				} else {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.sz($yz1,0,'');parent.mldq($yz1);setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				$ys1 = subfolder_html($db, $_name, $yz1);
				break;
			}
			
		case 'xzlj':
			$ljName = isset($_POST['Yz1']) ? trim($_POST['Yz1']) : '';  // 名称
			$ljUrl  = isset($_POST['Yz2']) ? trim($_POST['Yz2']) : '';  // 地址/内容
			$ljMlbh = isset($_POST['Yz3']) ? (int)$_POST['Yz3'] : 0;    // 目录编号
			$yz3js = json_encode($ljMlbh, JSON_UNESCAPED_UNICODE);
			$ys0 = htmlspecialchars($_POST['Yz0'],ENT_QUOTES,'UTF-8');
			$ys3 = htmlspecialchars($_POST['Yz3'],ENT_QUOTES,'UTF-8');
			//需求4：无 http/https 前缀 → 作为普通文字文件（text/plain, txt），有前缀 → 链接
			$isLink = (preg_match('#^https?://#i', $ljUrl)) ? true : false;
			if($ljName != '' && $ljUrl != '') {
				if($isLink) {
					$ok = ($db->AddUrl($_name,$ljMlbh,$ljName,$ljUrl) == 1);
				} else {
					//作为纯文字文件写入 epan_files
					$fk = substr(md5(uniqid(mt_rand(), true)), 0, 8);
					$fsu = substr(md5(uniqid(mt_rand(), true)), 8, 6);
					$uidQ = '(select userid from epan_users where username = "'.$db->escape($_name).'")';
					$ins = $db->query("INSERT INTO `epan_files` (`file_name`, `file_key`, `file_short_url`, `file_extension`, `file_mime`, `file_description`, `file_store_path`, `file_real_name`, `file_md5`, `userid`, `folder_id`, `ip`, `file_size`, `file_time`, `in_share`, `is_public`) VALUES ('".$db->escape($ljName."_txt")."', '".$fk."', '".$fsu."', 'txt', 'text/plain', '".$db->escape($ljUrl)."', '', '".$db->escape($ljName)."', '', ".$uidQ.", '".(int)$ljMlbh."', '".$db->escape($db->GetClientIP())."', '0', '".TS."', '0', '0');");
					$ok = ($db->affected_rows() !== NULL && (int)$db->affected_rows() >= 1);
				}
			} else { $ok = false; }
			if($ok) {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent.addsj(".$yz3js.");setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
				if($isLink) {
					$ys1 = "<img src='/user/images/url.gif' id='u_".(int)$ljMlbh."' onclick='_fbj.tj(this)'><a href='".htmlspecialchars($ljUrl,ENT_QUOTES,'UTF-8')."' style='color:blue' target=_blank class=lj>".htmlspecialchars($ljName,ENT_QUOTES,'UTF-8')."</a>";
				} else {
					$ys1 = "<img src='/user/ck/images/filetype/txt.gif' id='t_".(int)$ljMlbh."' onclick='_fbj.tj(this)'><a href='javascript:;' class=lj>".htmlspecialchars($ljName,ENT_QUOTES,'UTF-8')."</a><font></font>";
				}
			} else {
				$jscode = "
document.domain = qdhost()
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];alert('添加失败');setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			}
			break;

		case 'kqmmpd':
			//目录密码认证：Yz1=目录id, Yz2=密码
			$mmId = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			$mmVal = isset($_POST['Yz2']) ? $_POST['Yz2'] : '';
			$folderRow = $db->fetch_array($db->query('SELECT password FROM epan_folders WHERE folder_id = '.$mmId.' AND userid = (select userid from epan_users where username = "'.$db->escape($_name).'") LIMIT 1'));
			$rjg = ($folderRow && trim($folderRow['password']) !== '' && $folderRow['password'] === $mmVal) ? 1 : 0;
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){if(parent.D_mmrz_i&&parent.D_mmrz_i.zx_fh){parent.D_mmrz_i.zx_fh(".$mmId.",".$rjg.");}setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;

		case 'kqmmfq':
			//放弃目录密码认证：Yz1=目录id
			$mmId2 = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){if(parent.D_mmrz_i&&parent.D_mmrz_i.fq_fh){parent.D_mmrz_i.fq_fh(".$mmId2.");}setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;

		case 'scml':
			//删除目录：Yz1=目录id
			$delMl = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			$uidQ = '(select userid from epan_users where username = "'.$db->escape($_name).'")';
			$db->query("DELETE FROM epan_files WHERE folder_id = ".$delMl." AND userid = ".$uidQ);
			$db->query("DELETE FROM epan_folders WHERE folder_id = ".$delMl." AND userid = ".$uidQ);
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.cxdq();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;

		case 'scjl':
			//删除文件/链接：Yz1=类型(u/w/t/z), Yz2=记录id, Yz3=目录id
			$scLx = isset($_POST['Yz1']) ? $_POST['Yz1'] : '';
			$scId = isset($_POST['Yz2']) ? (int)$_POST['Yz2'] : 0;
			$scMl = isset($_POST['Yz3']) ? (int)$_POST['Yz3'] : 0;
			$uidQ = '(select userid from epan_users where username = "'.$db->escape($_name).'")';
			$db->query("DELETE FROM epan_files WHERE file_id = ".$scId." AND userid = ".$uidQ);
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){if(parent.D_bj_i&&parent.D_bj_i.f_sc_fh){parent.D_bj_i.f_sc_fh('".htmlspecialchars($_POST['Yz2'],ENT_QUOTES,'UTF-8')."','');}setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			$ys1 = htmlspecialchars($_POST['Yz2'],ENT_QUOTES,'UTF-8');
			break;

		case 'xgml':
			//编辑目录：Yz1=目录id, Yz2=标题, Yz3=署名, Yz4=密码|权限串
			$xgId = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			$xgBt = isset($_POST['Yz2']) ? trim($_POST['Yz2']) : '';
			$xgSm = isset($_POST['Yz3']) ? trim($_POST['Yz3']) : '';
			$xgMmRaw = isset($_POST['Yz4']) ? $_POST['Yz4'] : '';
			$xgParts = explode('|', $xgMmRaw);
			$xgMm = isset($xgParts[0]) ? $xgParts[0] : '';
			$xgFo = (isset($xgParts[1])) ? preg_replace('/[^01]/','',(string)$xgParts[1]) : '011000';
			if($xgFo == '' || strlen($xgFo) != 6) { $xgFo = '011000'; }
			$uidQ = '(select userid from epan_users where username = "'.$db->escape($_name).'")';
			$db->query("UPDATE epan_folders SET folder_name='".$db->escape($xgBt)."', folder_description='".$db->escape($xgSm)."', password='".$db->escape($xgMm)."', fileorder='".$db->escape($xgFo)."' WHERE folder_id=".$xgId." AND userid=".$uidQ);
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';parent._m.cxdq();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			break;

		case 'xgsj':
			//编辑文件/链接：Yz1=记录id, Yz2=新标题, Yz3=新描述/地址, Yz4=目标目录
			$xjId = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			$xjBt = isset($_POST['Yz2']) ? trim($_POST['Yz2']) : '';
			$xjSm = isset($_POST['Yz3']) ? trim($_POST['Yz3']) : '';
			$xjMl = isset($_POST['Yz4']) ? $_POST['Yz4'] : '0';
			$uidQ = '(select userid from epan_users where username = "'.$db->escape($_name).'")';
			//判断原记录类型
			$origRow = $db->fetch_array($db->query("SELECT file_mime, file_extension FROM epan_files WHERE file_id=".$xjId." AND userid=".$uidQ));
			if($origRow) {
				if($origRow['file_mime'] == 'application/url') {
					$db->query("UPDATE epan_files SET file_name='".$db->escape($xjBt)."', file_description='".$db->escape($xjSm)."' WHERE file_id=".$xjId." AND userid=".$uidQ);
				} else {
					$db->query("UPDATE epan_files SET file_name='".$db->escape($xjBt)."', file_description='".$db->escape($xjSm)."' WHERE file_id=".$xjId." AND userid=".$uidQ);
				}
			}
			$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){if(parent.D_bj_i&&parent.D_bj_i.f_bj_fh){parent.D_bj_i.f_bj_fh();}setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			$ys1 = htmlspecialchars($_POST['Yz1'],ENT_QUOTES,'UTF-8');
			$ys2 = htmlspecialchars($_POST['Yz2'],ENT_QUOTES,'UTF-8');
			$ys3 = htmlspecialchars($_POST['Yz3'],ENT_QUOTES,'UTF-8');
			$ys4 = htmlspecialchars($_POST['Yz4'],ENT_QUOTES,'UTF-8');
			break;

		case 'dqscpz':
			//上传前取目录上传令牌：scpz=目录id（up/index.php 从 p= 中解析），随后回调 ks1() 提交表单
			$dqMl = isset($_POST['Yz1']) ? (int)$_POST['Yz1'] : 0;
			if($isGuest && $fkUpload == 0) {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){alert('空间主人已关闭上传');setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			} else {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';var o=parent._m.f(".$dqMl.");if(o){o.scpz='".$dqMl."';}parent._fup.ks1();setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			}
			break;

		case 'xzml2':
			//创建子目录（服务端持久化）：Yz1=子目录名, Yz2=父目录id
			$subName = isset($_POST['Yz1']) ? trim($_POST['Yz1']) : '';
			$subParent = isset($_POST['Yz2']) ? (int)$_POST['Yz2'] : -1;
			if($isGuest) {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){alert('仅空间管理员可创建子目录');setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			} elseif($subName != '' && strlen($subName) <= 50) {
				$db->query("INSERT INTO epan_folders (parent_id, folder_node, folder_name, folder_description, in_recycle, in_share, folder_order, folder_size, userid, in_time, password, fileorder) VALUES (".$subParent.", 0, '".$db->escape($subName)."', '', 0, 0, 0, 0, (select userid from epan_users where username='".$db->escape($_name)."'), ".time().", '', '011000')");
				$newId = (int)$db->insert_id();
				//读取新目录行，渲染节点 li 返回前端，插入父目录 ZMm_<parent>
				$ys1 = '';
				if($newId > 0) {
					$nrow = $db->fetch_array($db->query("SELECT folder_id, folder_name, password, fileorder FROM epan_folders WHERE folder_id=".$newId." AND userid=(select userid from epan_users where username='".$db->escape($_name)."') LIMIT 1"));
					if($nrow) { $ys1 = render_folder_li($nrow); }
				}
				$ys3 = (string)$subParent;
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.Q.f_s='".$f_s_js."';var plh=parent.$('ZMm_".(int)$subParent."');if(plh){plh.insertAdjacentHTML('afterbegin',F.Yz1.value);if(plh.style.display=='none')plh.style.display='block';parent.mlxs(".(int)$subParent.");}parent.Q.mlxz(".$newId.");setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			} else {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){alert('子目录名不能为空');setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			}
			break;
				
		case 'xgzy':
			//空间设置：保存空间标题
			$newTitle = isset($_POST['Yz1']) ? trim($_POST['Yz1']) : '';
			if($newTitle == '' || strlen($newTitle) <= 50) {
				$db->SpaceUpdate($_name, 'title', $newTitle);
				$dispTitle = ($newTitle == '') ? ($_name . '的空间') : $newTitle;
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){var F=document.forms[0];parent.$('kjbt').innerHTML='".addslashes($dispTitle)."';setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			} else {
				$jscode = "
function onloads(){parent.Qcxzt=true;zx();setTimeout('parent.Jxzx();',200);}
function zx(){alert('标题不能超过50字符');setTimeout('dy()',15000);}
function qdhost() { var x = document.domain.split(\".\"); if (x.length == 3) return x[1] + \".\" + x[2]; return x[2] + \".\" + x[3]; }";
			}
			break;
			
		}
}	
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="JavaScript" type="text/javascript" src="js/aj.js"></script>
</head>
<script language="JavaScript" type="text/javascript">
<?php echo $jscode; ?>
</script>
<body onLoad="onloads()">
<form method="post">
<input type="hidden" id="Yz0" name="Yz0" value="<?php echo $ys0; ?>"/>
<input type="hidden" id="Yz1" name="Yz1" value="<?php echo $ys1; ?>"/>
<input type="hidden" id="Yz2" name="Yz2" value="<?php echo $ys2; ?>"/>
<input type="hidden" id="Yz3" name="Yz3" value="<?php echo $ys3; ?>"/>
<input type="hidden" id="Yz4" name="Yz4" value="<?php echo $ys4; ?>"/>
</form>
</body>
</html>

