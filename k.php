<?php
include "includes/Init.php";

// ===== 维护模式门禁（需求3-1）：site_maintenance=1 时向非 admin 访客展示维护页 =====
// 判断依据：admin 后台登录后 $_SESSION['username'] 非空（admin/login_check.php L84），
// 与前台共享 session；管理员访问空间页时不被锁外。
if ($db->Info('site_maintenance') == '1' && empty($_SESSION['username'])) {
    $maintMsg = $db->Info('site_maintenance_msg');
    if ($maintMsg === '' || $maintMsg === null) { $maintMsg = '系统维护中，请稍后访问。'; }
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
    echo '<!DOCTYPE html><html lang="zh-cn"><head><meta charset="utf-8"><title>' . htmlspecialchars($db->Info('site_title'), ENT_QUOTES, 'UTF-8') . ' - 维护中</title>';
    echo '<style>body{font-family:"Microsoft YaHei",Arial,sans-serif;background:#f3f4f6;margin:0;padding:0;display:flex;align-items:center;justify-content:center;min-height:100vh;}';
    echo '.box{background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.08);padding:40px 48px;max-width:560px;text-align:center;}';
    echo 'h1{color:#1f2937;font-size:22px;margin:0 0 16px;}p{color:#4b5563;font-size:15px;line-height:1.8;margin:0;}</style>';
    echo '</head><body><div class="box"><h1>网站维护中</h1><p>' . nl2br(htmlspecialchars($maintMsg, ENT_QUOTES, 'UTF-8')) . '</p></div></body></html>';
    exit;
}

// 服务端白名单校验用户名，防注入/Header注入
$_k_name = isset($_GET['name']) ? $_GET['name'] : '';
if (!preg_match('/^[a-zA-Z0-9\-]{1,32}$/', $_k_name)) {
	$_k_name = '';
}
if($_k_name == NULL ) {
	header("Location: index.php"); //重定向浏览器
	} else { 
		if($db -> UserCheck($_k_name) == 0)
		{
			header("Location: index.php"); //重定向浏览器
			} 
		}
		
	//空间到期时间
if ($db ->AccountData('Expire_time') != 0) {
	$time = date("Y-m-d H:i:s" , $db ->AccountData('Expire_time'));
	} else {
		$time = '<strong>免费用户 </strong> ';
			}
	//空间级别
	$level = $db -> fetch_array($db -> query('select * from epan_users,epan_userData where username = "'.$db->escape($_k_name).'" and `user` = "'.$db->escape($_k_name).'";'));
	$diskTemp = $db->fetch_array($db -> query('select name,data from epan_product where code = '. (is_numeric($level['diskLevel']) ? (int)$level['diskLevel'] : 0) )); 
 	$disk = $diskTemp['name'].'['.$diskTemp['data'].'MB]';

//获取空间语言（归一化：非 1/2 一律按简体中文，保证 c_{Lang}.js 与语言包必然存在，避免 c_.js 404）
$Lang = $db ->SpaceSetting($_k_name,'Language');
if ($Lang !== '1' && $Lang !== '2') { $Lang = '0'; }
setcookie('Lang',$Lang);//定义全局用户语言
setcookie('User',$_k_name);//定义全局用户
//皮肤样式：若未设置或对应 CSS 文件不存在，回退默认皮肤 1，避免 user/css/.css 404
$style = $db ->SpaceSetting($_k_name,'style');
if (!preg_match('/^[a-zA-Z0-9_\-]{1,32}$/', $style) || !file_exists(dirname(__FILE__).'/user/css/'.$style.'.css')) {
	$style = '1';
}
$jy = base64_decode($db -> SpaceSetting($_SESSION['username_epan'],'Message'));

//读取网站标题
switch ($Lang) {
	default:
	case '0':
	if ($db -> SpaceSetting($_k_name,'title') == NULL){
		$title = $_k_name."的空间";
		} else {
			$title = $db -> SpaceSetting($_k_name,'title');
			}
		break;

	case '1':
	if ($db -> SpaceSetting($_k_name,'title') == NULL){
		$title = "The space of ".$_k_name;
		} else {
			$title = $db -> SpaceSetting($_k_name,'title');
			}
		break;

	case '2':
	if ($db -> SpaceSetting($_k_name,'title') == NULL){
		$title = $_k_name."の空間";
		} else {
			$title = $db -> SpaceSetting($_k_name,'title');
			}
		break;

}
?><html>
<head>
<base href="<?php echo PHPDISK_ROOT_WEB; ?>" target="ys_ck" />
<meta http-equiv="Content-Language" content="zh-cn" />
   	<title>
   	<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="shortcut icon" href="<?php echo PHPDISK_ROOT_WEB ?>images/favicon.ico">
    <meta name="keywords" content="<?php echo htmlspecialchars($db -> Info('meta_keywords'), ENT_QUOTES, 'UTF-8');?>" />
    <meta name="description" content="<?php echo htmlspecialchars($db -> Info('meta_description'), ENT_QUOTES, 'UTF-8');?>" />
    <link rel="Stylesheet" href="user/css/<?php echo $style; ?>.css"/>
    <link rel="Stylesheet" href="<?php echo PHPDISK_ROOT_WEB ?>css/k_ui.css"/>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>user/js/c_<?php echo $Lang; ?>.js"></script>
	<script src="<?php echo PHPDISK_ROOT_WEB ?>user/js/m.js"></script>
	<script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.9.1.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/artDialog.js?skin=simple"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/iframeTools.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery.js"></script>
</head>

<script type="text/javascript">
	var ddlmc = <?php echo json_encode($_k_name); ?>;
    document.domain = qdhost();var xdwjsl = 60; duser = 5756179;var dxkbt = false;var dxkly = 0;var dxksc = false;dffpd = false;var dxkjl = false; dfgxz = '5';var dxken = <?php echo ($Lang === '1') ? 'true' : 'false'; ?>;var dmlpx = 2;var Qsjk = '5';var Nfs = false;
    var jzpd = 0; var owPd = 0; var wjscjs = 1; var isFir = false; var isIE = false; var isIE10 = false;
    var Qcxzt = false; var PD_gzgg = "0"; var Qzdqbh = -1; ffOK = false;var ccOK = false;
    var FFGQPD = "0"; var Qzydz = "<?php echo PHPDISK_ROOT_WEB ?>user/"; var Qccym = "<?php echo str_replace(HttpsCheck(),"",PHPDISK_ROOT_WEB); ?>user/"; var bbx;
    var Qwh = "";
    window.onload = function () {
        bbx = navigator.userAgent.toLowerCase();
        if (bbx.indexOf("firefox") > 0) isFir = true;

        if (bbx.indexOf("msie") > 0 || bbx.indexOf("trident") > 0) isIE = true
        if (isIE) {
            if (navigator.appVersion.match(/10./i) == '10.' || bbx.indexOf("trident") > 0) isIE10 = true;

        }
        jcgd();
        $("scmore").checked = false;
        Q.f_ly = <?php echo json_encode($_k_name); ?>;

        if (Qcxzt) { ysdy(); } ffOK = true;

    }
    window.onresize = function () { jcgd(); }
    function $(rid) { return document.getElementById(rid); }

    function ysdy() { if (ccOK) return; ccOK = true; frxcx.zx(); }
    function tc() { window.location = "/exit.php?dlmc=" + ddlmc + '&s=c5'; }
	function qdhost() { var x = document.domain.split("."); if (x.length >= 3) return x[1] + "." + x[2]; if (x.length == 2) return x[0] + "." + x[1]; return document.domain; }
	function gbdm() { if (Q.a) { _C.kq(_CK.gbtz); } else { alert("请管理员在管理区登录后查看。"); } }
	function jcgd() {
	    if (!isIE10 && !isFir && bbx.indexOf("edge/") < 0) return;
	    var d12 = $("idsyjy").offsetHeight;
	    var d1 = $("idtop").offsetHeight;
	    var d11 = $("idczq").offsetHeight;
	    var d2 = $("lynr").offsetHeight;
	    var d3 = $("dzx").offsetHeight;
	    var d0 = document.body.offsetHeight;
	    $("lynr").style.height = (d0 - d1 - d11 - 48) + "px";
	    $("dzx").style.height = ((d0 - d1 - d12 - 35)) + "px";
	    $("lynr").style.width = "248px";

	}

	function csaj_(r) { alert(r); }

	/* ===== 改版新增：空间用量条 + 目录排序（纯前端，不调用/不修改既有函数） ===== */
	function updateSpBar() {
		var el1 = $("kjz1"), el2 = $("kjz2"), bar = $("k_spbar");
		if (!el1 || !bar) return;
		var total = parseFloat(el1.innerHTML);
		var remainTxt = el2 ? el2.innerHTML : '-';
		var remain = parseFloat(remainTxt);
		var pct = 0;
		if (!isNaN(total) && total > 0 && !isNaN(remain) && remain >= 0) {
			var used = total - remain;
			if (used < 0) used = 0;
			if (used > total) used = total;
			pct = Math.round(used / total * 100);
		}
		bar.style.width = pct + "%";
		bar.className = (pct > 85) ? "k-sp-fill k-sp-hot" : (pct > 60 ? "k-sp-fill k-sp-warm" : "k-sp-fill");
	}
	setInterval(updateSpBar, 2000);

	var _kSortAsc = false;
	function sortMenuList() {
		var ul = $("menuList");
		if (!ul) { alert("目录尚未加载完成"); return; }
		var items = [];
		for (var i = 0; i < ul.childNodes.length; i++) {
			var n = ul.childNodes[i];
			if (n && n.id && n.id.indexOf("ZM_") === 0) items.push(n);
		}
		if (items.length < 2) { alert("可排序的目录项不足"); return; }
		items.sort(function (a, b) {
			var ta = (a.textContent || a.innerText || "");
			var tb = (b.textContent || b.innerText || "");
			if (_kSortAsc) return ta < tb ? -1 : (ta > tb ? 1 : 0);
			return ta > tb ? -1 : (ta < tb ? 1 : 0);
		});
		for (var j = 0; j < items.length; j++) ul.appendChild(items[j]);
		_kSortAsc = !_kSortAsc;
	}
 </script>

<?php 
// ===== 任务9：i18n 统一语言加载入口（一个主文件 i18n.php，按语言代码加载对应语言文件）=====
include PHPDISK_ROOT.'user/Language/i18n.php';
$T = lang_load($Lang, $_k_name);
include PHPDISK_ROOT.'user/space_body.php';
?>


