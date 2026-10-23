<?php include "../../includes/Init.php";
$_name = isset($_GET['name']) ? $_GET['name'] : '';
// 空间设置窗口：编辑空间标题
$_curTitle = $db->SpaceSetting($_name, 'title');
if ($_curTitle === NULL || $_curTitle === '') { $_curTitle = $_name . '的空间'; }
?>
<html>
<head>
<style type="text/css">
    body {background-color:#FFFFE6;font-size:9pt;padding:15px;margin:0;font-family:宋体, Arial, Helvetica;}
    .row {margin:8px 0;}
    .la {display:inline-block;width:70px;text-align:right;margin-right:6px;}
    input.tx3 {width:260px;}
    .btnrow {text-align:center;margin-top:16px;}
    .tip {color:#666;margin-top:8px;font-size:9pt;}
</style>
<script language="javascript" type="text/javascript">
    ckid = "D_bjym";
    function csdy() {
        parent._C.bt(ckid, '空间设置');
        parent._CK.bjym.gb = function() {
            parent._CK.bjym.ck.style.display = "none";
        };
        var cur = parent.document.getElementById('kjbt');
        if (cur) { $("bt").value = cur.innerHTML; }
    }
    function bc() {
        var v = $("bt").value;
        if (v.length > 50) { alert('标题不能超过50字符'); return; }
        parent.readxml("xgzy", v, "", "", "");
        parent._CK.bjym.gb();
    }
    function $(rid) { return document.getElementById(rid); }
</script>
</head>
<body onmousedown="parent._C.zd(ckid);" onLoad="csdy();">
    <div class="row"><span class="la">空间标题:</span><input type="text" id="bt" class="tx3" value="<?php echo htmlspecialchars($_curTitle, ENT_QUOTES, 'UTF-8'); ?>"/></div>
    <div class="tip">修改空间显示标题，留空则显示默认标题。</div>
    <div class="btnrow">
        <input type="button" value="保存" onclick="bc();" />
        <input type="button" value="关闭" onclick="parent._CK.bjym.gb();" />
    </div>
</body>
</html>
