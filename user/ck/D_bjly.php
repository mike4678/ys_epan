<?php 
include "../../includes/Init.php";
$_lang = preg_replace('/[^a-zA-Z0-9_\-]/', '', isset($_COOKIE['Lang']) ? $_COOKIE['Lang'] : 'zh');
$u = $db -> LoadingGuestbook((int)$_GET['id'] , 'uid');
foreach($u as $list=>$things){
$name = $things['name'];
$face = $things['face'];
$data = $things['data'];
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style type="text/css">
        body    {background-color:#f3f4f6;font-size:9pt;border:0px;padding:0px;margin:0;padding-top:14px;font-family:'Segoe UI','Microsoft YaHei','PingFang SC','Helvetica Neue',Arial,sans-serif;}
        .tx3 	{width:148px;border:1px solid #d1d5db;border-radius:6px;padding:2px 6px;background:#ffffff;color:#1f2937;}
        div	    {margin:2px 0 0 16px;line-height:20px;}
        #nr	    {border:1px solid #d1d5db;border-radius:8px;width:100%; height:100%;font-family:inherit; font-size: 9pt;box-sizing:border-box;color:#1f2937;}
        .g      {display:none;}
        .zhx    {text-decoration: line-through;}
        .ct1 {position:absolute;top:10px;height:25px;}
        .ct2 {position:absolute;top:40px;left:0px;right:20px;bottom:40px;background-color:red;box-sizing:border-box;}
        .ct3 {position:absolute;bottom:0px;height:25px;}
        #lb1,#lb2 {border:1px solid #2563eb;background:#2563eb;color:#ffffff;border-radius:6px;padding:2px 10px;cursor:pointer;}
        #lb2 {background:#ef4444;border-color:#ef4444;}
        #lb3 {border:1px solid #e5e7eb;background:#ffffff;color:#1f2937;border-radius:6px;padding:2px 10px;cursor:pointer;}
        a {color:#2563eb;}
    </style>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.9.1.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/artDialog.js?skin=simple"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/iframeTools.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>user/js/c_<?php echo $_lang; ?>.js"></script>
    <script type="text/javascript">		
        var epd = false;
        var msj = { bt: "", sm: "", mm: "", jlsx: "", jlmm: "", mlbh: "" };
        var ojl = "";

        function l_bj_tj() {     //编辑留言
            if (!parent.Q.a) {
                alert(parent.C.l1); return false; }
            
            var sxm = $("xm").value;
            var snr = $("nr").value;

			if (sxm == "") { alert(parent.C.l2); return false; }
            if (snr == "") { alert(parent.C.l3); return false; }
            if (snr.length > 1000) { alert(parent.C.ly1 + snr.length); return false; }
            var cz1 = (($("gkpd").checked) ? "n" : "y") + $("bdbq").value;
            if (parent.Q.a && $("gkpd").checked == false) { alert(parent.C.ly2); }
			var id = GetQueryString("id");
            parent.readxml("lyxg", cz1 + parent.Qlyfyxx, sxm, snr,id );
            art.dialog.close();
        }

        function l_sc_tj() {   //删除提交
            if (!confirm(parent.C.ly2)) { return false; }
            parent.readxml("lysc", GetQueryString("id"), parent.Qlyfyxx, "", "");
            art.dialog.close();
        }


        function zfth(r1) {
            r1 = parent.vreplace(r1, "<br>", "\n");
            r1 = parent.vreplace(r1, "<BR>", "\n");
            r1 = parent.vreplace(r1, "&amp;", "&");
            r1 = parent.vreplace(r1, "&lt;", "<");
            r1 = parent.vreplace(r1, "&gt;", ">");
            return r1;
        }

        function qcsj() {
            var bh = parent.Q.lybj.bh;
            var Sxm = $p("Xm_" + bh).innerHTML;
            var Snr = $p("Nr_" + bh).innerHTML;
            var Sip = $p("Xm_" + bh).parentNode.lastChild.innerHTML;
            if (Sip.indexOf(".") <= 0) {
                $("lb1").className = "zhx"; $("lb1").onclick = function () { alert(parent.C.lyerr); };
            } else {
                $("lb1").className = ""; $("lb1").onclick = function () { l_bj_tj(); };
            }

            $("xm").value = zfth(Sxm);
            $("nr").value = zfth(Snr);
            $("bdbq").value = parent.Q.lybj.tp;
            if (parent.Q.lybj.gk == "y") { $("gkpd").checked = false; } else { $("gkpd").checked = true; }
            $("t_bq").src = "../face/f" + parent.Q.lybj.tp + ".gif";
        }
        function xssz() {
            if (epd) {
                $("l1").innerHTML = "Name";
                $("l2").innerHTML = "Public";
                $("lb1").value = "Modify";
                $("lb2").value = "Delete";
                $("lb3").value = "Close";
            }
        }

        function changetp(pd) {
            if (pd == "z") { bdbq.value = ++bdbq.value; } if (pd == "y") { bdbq.value = --bdbq.value; }
            if (bdbq.value < 0) { bdbq.value = 9; } if (bdbq.value > 9) { bdbq.value = 0; }
            $("t_bq").src = "../face/f" + bdbq.value + ".gif";
        }
        function $p(rid) { return parent.document.getElementById(rid) }
        function $(rid) { return document.getElementById(rid); }
        function csdy() { onloads(); }
        function zzzbj() {
            if (!parent.Q.a) { alert(parent.C.lylogin); return; }
            //parent._C.kq(parent._CK.bjlyd);art.dialog.close();
			parent.bjlyd();art.dialog.close();
        }
		
		function GetQueryString(name) { 
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
			var r = window.location.search.substr(1).match(reg); 
			if (r != null) return unescape(r[2]); return null; 
		} 
		
		function onloads() { 
		document.getElementById("l1").innerText = parent.C.name; 
		document.getElementById("l2").innerText = parent.C.pub;
		$("lb3").value = parent.C.close;
		$("lb2").value = parent.C.del;
		$("lb1").value = parent.C.edit;
		}
    </script>
</head>
<body onLoad="onloads()">

    <div class="ct1"> <label id="l1"></label><input type="text" id="xm" class="tx3" style="width:80px;" value="<?php echo htmlspecialchars($name,ENT_QUOTES,'UTF-8'); ?>"/>
	    <label id="l2"></label><input type="checkbox" id="gkpd"  checked="checked" />
	    <img alt="" border="0" src="../images/00zxz1.gif" width="15" height="15" onclick="changetp('z');" style="cursor:pointer;"/>
	    <img alt="" border="0" name="t_bq" id="t_bq" src="../face/f<?php echo (int)$face; ?>.gif" width="15" height="15"/>
	    <img alt="" border="0" src="../images/00zxy1.gif" width="15" height="15" onclick="changetp('y');" style="cursor:pointer;"/> 
    </div>
    <div class="ct2" id="d_nr"><textarea id="nr"><?php echo htmlspecialchars($data,ENT_QUOTES,'UTF-8'); ?></textarea></div>	
    <div class="ct3" align="center" style="margin-top:10px;">
		    <input type="button" id="lb1" value="" onclick="l_bj_tj();"/>
		    <input type="button" id="lb2" value="" onclick="l_sc_tj();"/>
		    <input type="button" id="lb3" value="" onclick="javascript:art.dialog.close();"/>
		    <a href="javascript:;" onclick="zzzbj();" >[多项删除]</a>
		    <input type="hidden" id="bdbq" value="1" />
    </div>

</body>
</html>
