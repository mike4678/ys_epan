<?php 
include "../../includes/Init.php"; 
//与 k.php 相同语言机制：读 Lang cookie 加载 i18n 语言包
$_tLang = isset($_COOKIE['Lang']) ? $_COOKIE['Lang'] : '0';
require __DIR__ . '/../Language/i18n.php';
$T = lang_load($_tLang);
?>
<html>
<head>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.9.1.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/artDialog.js?skin=simple"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/iframeTools.js"></script>
    <style type="text/css">
        body    {background-color:#FFFFE6;font-size:9pt;border:0px;padding:10px;margin:0;font-family: 宋体, Century Gothic, Arial, Helvetica;}
         p	    {margin:0px;line-height:150%;}
        .sjtb         {border:1px solid #BDB76B;border-collapse: collapse;width:100%;}
        .sjtb td      {border:1px solid #BDB76B;font-size:9pt;}
        .sjtb tr.trbt{background-color:#EEE8AA;color:#000;}
        .ad{font-size:9pt;color:blue; }
        .ad:hover {COLOR: red;TEXT-DECORATION: none; }  
    </style>
    <script language="javascript" type="text/javascript">
        var ckid = "D_zxlb";
        function csdy() {
            parent.readxml('xszxlb', '', '', '', '');
         }
         function $(rid) { return document.getElementById(rid); }
         function qdhost() { var x = document.domain.split("."); if (x.length >= 3) return x[1] + "." + x[2]; if (x.length == 2) return x[0] + "." + x[1]; return document.domain; }
    </script>
</head>
<body onmousedown ="parent._C.zd(ckid);" onLoad="csdy();">
		<div id="nr"><?php echo htmlspecialchars($T['loading'],ENT_QUOTES,'UTF-8'); ?></div>
<div style="text-align:center;margin:30px;"><input type="button" value="<?php echo htmlspecialchars($T['close'],ENT_QUOTES,'UTF-8'); ?>" onClick="parent._CK.zxlb.gb();" />
		<br><br><p><?php echo htmlspecialchars($T['powered_by'],ENT_QUOTES,'UTF-8'); ?><a class="ad" href="<?php echo htmlspecialchars($db->Info('disk_url'),ENT_QUOTES,'UTF-8');?>" target="_blank"><?php echo htmlspecialchars($db->Info('corp'),ENT_QUOTES,'UTF-8');?></a></p>
</body>
</html>
