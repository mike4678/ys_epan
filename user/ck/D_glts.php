<?php include "../../includes/Init.php";
$_lang = preg_replace('/[^a-zA-Z0-9_\-]/', '', isset($_COOKIE['Lang']) ? $_COOKIE['Lang'] : '0');
?>
<html>
<head>
<style type="text/css">
        *{box-sizing:border-box}
        body{margin:0;padding:24px 28px;background:#fff;font-size:13px;line-height:1.7;color:#374151;font-family:'Segoe UI','Microsoft YaHei','PingFang SC',Arial,sans-serif;}
        .msg{font-size:15px;color:#16a34a;font-weight:600;}
        .tip{color:#6b7280;margin-top:8px;}
        p{margin:0}
        .btn{display:block;margin:20px auto 0;padding:8px 30px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:14px;cursor:pointer;}
        .btn:hover{background:#1d4ed8}
</style>
<script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.9.1.js"></script>
<script src="<?php echo PHPDISK_ROOT_WEB ?>js/artDialog.js?skin=simple"></script>
<script src="<?php echo PHPDISK_ROOT_WEB ?>js/iframeTools.js"></script>
<script src="<?php echo PHPDISK_ROOT_WEB ?>user/js/c_<?php echo $_lang; ?>.js"></script>
<script>
function $(rid) { return document.getElementById(rid); }
function onloads() {
    var txt = "您已经在管理区登录，拥有空间所有权限，可直接开启设权限的目录，查看留言人IP。";
    var btntxt = "关闭";
    try {
        if (parent && parent.C) {
            if (parent.C.actlogin) txt = parent.C.actlogin;
            if (parent.C.close) btntxt = parent.C.close;
        }
    } catch (e) {}
    $("divId").innerText = txt;
    $("bt1").value = btntxt;
    var secs = 10;
    for (var i = 1; i <= secs; i++) { window.setTimeout("update(" + i + ")", i * 1000); }
}
function update(num) {
    if (num == 10) { art.dialog.close(); }
    else { var p = 10 - num; $("bt1").value = "(" + p + ") " + ($("bt1").getAttribute("data-txt") || "关闭"); }
}
</script>
</head>
<body onLoad="onloads()">
        <div class="msg" id="divId"></div>
        <div class="tip" id="divTip"></div>
        <p align="center"><input type="button" value="关闭" id="bt1" data-txt="关闭" class="btn" onclick="javascript:art.dialog.close();" /></p>
</body>
</html>
