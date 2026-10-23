<html>
<head>
    <title></title>
    <style type="text/css">
        body    {background-color:#FAFAD2;font-size:9pt;border:0px;padding:0px;margin:0;padding-top:18px;font-family: 宋体, Century Gothic, Arial, Helvetica;}
        .tx3 	{/*height:18px;font-size: 9pt; border: 1px solid; border-color: black black #000000; color:black ;*/width:148px;}
        p	    {margin:2px 0 0 16px;line-height:20px;}
        .la1    {width:58px;display:inline-block;}
        #nr	    {BORDER:rgb(51,51,51) 1px solid;width: 220; height:75;font-family: 宋体, Century Gothic, Arial, Helvetica; font-size: 9pt}
    </style>
    <script language="javascript" type="text/javascript">
        var ckid = "D_bj";
        var lx = ""; var epd = false;
        var msj = { bt: "", sm: "", mm: "", jlsx: "", jlmm: "", mlbh: "" };
        //禁右
        //document.oncontextmenu = function() { return false; }
        //document.ondragstart = function() { return false; }
        function csdy() {

            lx = parent.Q.bjlx; epd = parent.dxken; 
            
            parent._CK.bj.gb = function() {
                parent._CK.bj.ck.style.display = "none";
                if (parent._CK.mmsz.zt) {
                    if (parent._CK.mmsz.ck.style.display == "block") parent._CK.mmsz.gb();
                }
                parent.Q.bjts.qx();
                //$p(ckid).blur();
                //$p(ckid).style.display = 'none';
            }

            xssz(); qcsj();
        }
        function gb() {
            parent._CK.bj.gb();
        }
        
        
        function qdhost() { var x = document.domain.split("."); if (x.length >= 3) return x[1] + "." + x[2]; if (x.length == 2) return x[0] + "." + x[1]; return document.domain; }
        function l_bj_tj() {
            if (!parent.Q.a) {
                var ts1 = "The manager has forbidden the message function!\n\nPrompt: The manager lands after the administrative district may the message";
                if (!parent.dxkly) { alert((epd) ? ts1 : "管理员已禁止留言功能！\n\n提示: 管理员在管理区登录后可以留言"); return false; }
            }
            var sxm = parent.zfaq($("xm").value);
            var snr = parent.zfaq($("nr").value);

            if (sxm == "") { alert((epd) ? "The name cannot be spatial!" : "留言人姓名不能为空！"); return false; }
            if (snr == "") { alert((epd) ? "The content cannot be spatial!" : "留言内容不能为空！"); return false }
            if (snr.length > 1000) { alert("留言内容长度不能超过1000个字符！"); return false; }
            var cz1 = (($("gkpd").checked) ? "n" : "y") + $("bdbq").value;
            if (parent.Q.a && $("gkpd").checked == false) { alert("由于您已经在管理区登录，不公开的留言也可以查看，\n\n如要查看效果，请在管理区退出"); }
            parent.readxml("lyxg", cz1 + parent.Qlyfyxx, sxm, snr, parent.Q.lybj.bh);
            gb();
        }

        function l_sc_tj() {
            if (!confirm((epd) ? "Please confirmed whether deletes the message!" : "请确认是否删除留言！")) { return false; }
            parent.readxml("lysc", parent.Q.lybj.bh, parent.Qlyfyxx, "", "");
            gb();
        }
        
        
        
        //-----------------
        function M_bj_zx() {
            if (msj.mm != "") {
                alert("如要查看目录权限设置效果，需在空间管理区退出\n\n(管理员可查看所有加密目录)");
            
            }
            parent.readxml("xgml", msj.mlbh, msj.bt, msj.sm, msj.mm + "|" + parent._CK.mmsz.z[0]);
            gb();
        }
        function m_bj_tj() {
            msj.bt = parent.zfaq($("mt1").value);
            msj.sm = parent.zfaq($("mt2").value);
            msj.mm = parent.zfaq($("mt3").value);
            if ($("mt3").value != msj.mm) {
                alert("目录密码中含有非法字符!");
                return;
            }
            if (msj.bt == "") { alert((!epd) ? "目录标题不能为空！" : "Please fill in the title"); return false; }
            if (msj.mm.length > 15) { alert((!epd) ? "目录开启密码不能超过15个字符！" : "the password cannot surpass 15 characters!"); return false; }
            parent._CK.mmsz.z[0] = msj.jlsx;
            if (msj.mm != "") {
                //parent.qxck.kq(true);
                parent._CK.mmsz.z[1] = true; parent._C.kq(parent._CK.mmsz);
                parent._CK.mmsz.ck.style.left = parseInt(parent._CK.bj.ck.style.left) + 100;
                parent._CK.mmsz.ck.style.top = parseInt(parent._CK.bj.ck.style.top) - 30;
                return; 
            }
            
            
            
            M_bj_zx();
            
         }
        function m_sc_tj() { 
            if (!parent.Q.a) { alert((!epd)? "只有管理员在空间管理区登录后才能删除目录！" : "Need administrator login"); return; }
            var s1= (!epd)? "此操作将删除目录:" :"This operation will delete the directory:";
            var s2= (!epd) ? "\n\n此目录内所有文件将同时会被删除，请确认！" :"\n\nIn this directory all documents at the same time could delete, please confirmed!";
            if (!confirm(s1 + $("mt1").value + s2)) return false;
            parent.readxml("scml", msj.mlbh, "", "", "");
            gb();
        }
        function f_sc_tj() {
            var msg = ""; var smc = parent._fbj.ob.parentNode.childNodes[1].innerHTML;
            if (lx == "z") {
                
                msg = (epd) ? "At the same time the deletion category can delete under this category all documents!\n\nPlease confirmed whether has to delete the category:" : "删除类别同时会删除此类别下所有文件！\n\n请确认是否要删除类别:";
                msg += smc;
            } else {
                msg = (epd) ? "Please confirmed whether deletes:" : "请确认是否删除:";
                msg += smc;
            }
            if (!confirm(msg)) { return false; }
            parent.readxml("scjl", lx, parent._fbj.id0, parent._fbj.ml0, ""); return;
        }
        function f_sc_fh(rid, rsj) {
            var ob1 = $p(rid).parentNode;
            var mlbh = ob1.parentNode.id.split("_")[1];
            ob1.parentNode.removeChild(ob1);
            parent.mlxs(mlbh);
            parent.myTree.branches[parent.findml(mlbh)].text = $p("ZMm_" + mlbh).innerHTML;
            parent.myTree.branches[parent.findml(mlbh)].fsj = rsj;
            if (mlbh == parent.Qzdqbh) parent.Qzdy.kq();
            gb();

        }
        function f_bj_fh() {
            var ob1 = parent.frxcx.document.forms[0];
            var id0 = ob1.Yz1.value;
            var Sj1 = ob1.Yz2.value; var Sj2 = ob1.Yz3.value;
            var xm1 = ob1.Yz4.value; var xm0 = $p(id0).parentNode.parentNode.id;
            var ml0 = xm0.split("_")[1];var ml1 = xm1.split("_")[1];
            var nxh = parent.findml(ml0);
            var lx = id0.split("_")[0];
            parent.myTree.branches[nxh].fsj = ob1.Yz0.value;
            var op = $p(id0).parentNode;
            if (lx == "w") {
                var swj = op.childNodes[1].innerHTML; 
                op.childNodes[1].innerHTML = Sj1 + swj.substr(swj.lastIndexOf(" "));
                if (op.childNodes[2].className == "yyck") { op.childNodes[3].innerHTML = Sj2; } else { op.childNodes[2].innerHTML = Sj2;}
                
            }
            if (lx == "u") { op.childNodes[1].innerHTML = Sj1; op.childNodes[1].href = Sj2; }
            if (lx == "t") { op.childNodes[1].innerHTML = Sj1; op.childNodes[2].innerHTML = Sj2; }
            if (lx == "z") {
                var idn = "z_" + ml1 + "_" + Sj2;
                op.lastChild.id = idn;
                op.childNodes[1].innerHTML = Sj2;
                op.childNodes[0].id = idn + "_i";
            }
            if (xm0 != xm1) {
                if ($p(xm1).childNodes.length != 0) {
                    $p(xm1).insertBefore(op, $p(xm1).childNodes[0]);
                    if (xm1.split("_")[0] == "z") parent._zml.jcts(xm1);
                } else {
                    op.parentNode.removeChild(op);
                }
            }
            //if (xm0.split("_")[0] == "z") parent._zml.jcts(xm0);
            parent.Q.bjts.qx();
            parent.myTree.branches[nxh].text = $p("ZMm_" + ml0).innerHTML;
            var ml1 = xm1.split("_")[1];
            if (ml1 != ml0) {
                if ($p("ZMm_" + ml1).innerHTML != "") parent.myTree.branches[parent.findml(ml1)].text = $p("ZMm_" + ml1).innerHTML;
            }
            parent.mlxs(ml0);
            if (ml0 == parent.Qzdqbh) parent.Qzdy.kq();
            gb();
        }


        function f_bj_tj() {
            //var o = parent._fbj.ob;
            var ml0 = parent._fbj.ob.parentNode.parentNode.id.split("_")[1];
            var ml1 = $("wt3").value.split("_")[1];
            if (ml0 != ml1) {
                var M = new parent.ML(ml1);
                if (!M.xkadd(false)) { alert("目标目录:\"" + $("wt3")[$("wt3").selectedIndex].text + "\"无增加数据权限"); return false; }
            }
            var Sj1 = parent.zfaq($("wt1").value);
            var Sj2 = parent.zfaq($("wt2").value);
            if (lx == "z") {
                Sj2 = parent.vreplace(Sj2, " ", "");
                if (parent._zml.jc(Sj2) != "") { alert(parent._zml.jc(Sj2)); return false; }
                //if (Sj2 == o.parentNode.childNodes[1].innerHTML) { gb(); return; } 
                var zid = "z_" + ml1 + "_" + Sj2;
                if (parent.document.getElementById(zid)) { alert("此子目录名已经存在！"); return false; }
                var z1 = parent._fbj.ob.parentNode.lastChild;
                if (z1.childNodes.length == 0 || z1.lastChild.className == "lxts") {
                    if (ml0 != ml1) {alert("空目录不允许变更主目录");return; }
                    z1.id = zid;
                    z1.parentNode.childNodes[0].id = z1.id + "_i";
                    z1.parentNode.childNodes[1].innerHTML = Sj2;
                    if (z1.style.display == "none") {
                        parent.Q.flid = "";
                    } else {
                        parent.Q.flid = z1.id;
                    }
                    parent.qhzml(3);
                    gb();
                    return;
                }
            }

            if (lx == "u") {
                if (Sj1 == "") { alert((!epd) ? "标题不能为空！" : "The title cannot be spatial"); return false; }
                if (Sj2 == "") { alert((!epd) ? "请填写网址" : "Please fill in the website!"); return false; }
                if (Sj2.indexOf("://") < 0) { alert((!epd) ? "链接地址不合法！" : "The website has harms!"); return false; }
            }
            if (lx == "w") {
                if (Sj1 == "") { alert((!epd) ? "文件名不能为空！" : "he filename cannot be spatial!"); return false; }
                ddwjm = Sj1.substr(Sj1.lastIndexOf("\\") + 1);
                ddwjm = ddwjm.toLowerCase();
                if (parent.f_kzm(ddwjm) != ("." + parent.fxwj(parent._fbj.ob).kzm)) {
                    alert((!epd) ? "不能更改文件的扩展名！" : "Cannot change the document's extension!"); return false;
                }
                xjg = parent.f_check(ddwjm); if (xjg != "") { alert(xjg); return false; }
            }

            if (lx == "t") {
                if (Sj1 == "") { alert((!epd) ? "标题不能为空！" : "The title cannot be spatial"); return false; }
                if (Sj2 == "") { alert((!epd) ? "内容不能为空" : "Content can not be empty"); return false; }
            }
             jgpd = parent.readxml("xgsj", parent._fbj.id0, Sj1, Sj2, $("wt3").value);
            //gb();
            return;
        }
        function qcsj() {
            if (lx == "l") {
                var bh = parent.Q.lybj.bh;
                var Sxm = $p("Xm_" + bh).innerHTML;
                var Snr = $p("Nr_" + bh).innerHTML;
                $("xm").value = Sxm;
                Snr = parent.vreplace(Snr, "<br>", "\n");
                Snr = parent.vreplace(Snr, "<BR>", "\n");
                $("nr").value = Snr;
                $("bdbq").value = parent.Q.lybj.tp;
                if (parent.Q.lybj.gk == "y") { $("gkpd").checked = false; } else { $("gkpd").checked = true; }
                $("t_bq").src = "/tp" + "/face2/f" + parent.Q.lybj.tp + ".gif";
                return;
            }
            if (lx == "m") {
                msj.mlbh = parent._m.jlid;
                var om = $p("ZMm_" + msj.mlbh);
                
                msj.jlmm = ("~~~~~~~~~~~~~~~").substr(0, om.parentNode.id.split("_")[1].split("|")[0]);
                msj.jlsx = om.parentNode.id.split("|")[1];
                if (msj.jlmm == "") msj.jlsx = "011000";
                
                $("mt1").value = om.parentNode.childNodes[2].innerHTML;
                $("mt2").value = om.parentNode.childNodes[3].innerHTML;
                $("mt3").value = msj.jlmm;
                $("mt3").readOnly = (msj.jlmm == "") ? false : true;
                return;
            }
            
            var o = parent._fbj.ob;
            if (lx == "w") {
                var fh = parent.fxwj(o);
                $("wt1").value = fh.wjm;
                $("wt2").value = fh.bz;
            }
            if (lx == "u") {
                $("wt1").value = o.parentNode.childNodes[1].innerHTML;
                $("wt2").value = o.parentNode.childNodes[1].href;
            }
            if (lx == "t") {
                $("wt1").value = o.parentNode.childNodes[1].innerHTML;
                $("wt2").value = o.parentNode.childNodes[2].innerHTML;
            }
            if (lx == "z") {
                $("wt2").value = o.parentNode.childNodes[1].innerHTML;
                //parent._fbj.ob.parentNode.lastChild.style.display = "none";
            }
            mllb(o.parentNode.parentNode.id)
            //if (lx == "z") { $("wt2").focus(); } else { $("wt1").focus(); }
        }
        function xssz() {
            if (lx == "l") {
                $("d0").style.display = "none";
                $("d1").style.display = "none";
                $("d2").style.display = "block";
                if (epd) {
                    $("l1").innerHTML = "Name";
                    $("l2").innerHTML = "Public";
                    $("lb1").value = "Modify";
                    $("lb2").value = "Delete";
                    $("lb3").value = "Close";
                }
                xsbt(parent.C.c71);
                return;
            }
            if (lx == "m") {
                $("d0").style.display = "none";
                $("d2").style.display = "none";
                $("d1").style.display = "block";
                //if ($p("D_yz").style.display == "block") { parent.qxck.gb(); }
                if (parent._CK.mmsz.zt) {
                    if (parent._CK.mmsz.ck.style.display == "block") parent._CK.mmsz.gb();
                }
                if (epd) {
                    $("m0").innerHTML = "Title:";
                    $("m1").innerHTML = "Name:";
                    $("m2").innerHTML = "Password:";
                    $("mb1").value = "Modify";
                    $("mb2").value = "Delete";
                    $("mb3").value = "Close";
                }

                xsbt(parent.C.c72);
                return;
            }
            $("d1").style.display = "none";
            $("d2").style.display = "none";
            $("d0").style.display = "block";
            if (lx == "w") {
                $("w1").innerHTML = (epd) ? "Name:" : "文件名称:";
                $("w2").innerHTML = (epd) ? "Note:" : "文件备注:";
            }
            if (lx == "u") {
                $("w1").innerHTML = (epd) ? "Name:" : "链接标题:";
                $("w2").innerHTML = (epd) ? "Website:" : "链接地址:";
            }
            if (lx == "t") {
                $("w1").innerHTML = (epd) ? "Title:" : "文字标题:";
                $("w2").innerHTML = (epd) ? "Content:" : "文字内容:";
            }
            if (lx == "z") {
                $("w2").innerHTML = (epd) ? "Name:" : "子目录名:";
                $("wp1").style.display = "none";
            } else {
                $("wp1").style.display = "block";
            }
            if (epd) {
                $("wb1").value = "Modify";
                $("wb2").value = "Delete";
                $("wb3").value = "Close";
            
            }
            $("w3").innerHTML = (epd) ? "Folder:" : "改变目录:";
            xsbt(parent.C.c73)

        }
        function xsbt(rbt) {
            //parent._CK.bj.ck.childNodes[0].childNodes[1].innerHTML = rbt;
            parent._C.bt(ckid, rbt);
        }
        function mllb(mbid) {
            var objzml = $p("menuList"); var jsq = 0; $("wt3").innerHTML = "";
            var pd = true;
            if ($("wp1").style.display == "none") pd = false;
            var os1=$("wt3");
            for (var j = 0; j < objzml.childNodes.length; j++) {
                dobj = objzml.childNodes[j];
                xmmc = dobj.childNodes[2].innerHTML;
                xmbh = dobj.lastChild.id; 
                os1.options[jsq] = new Option(xmmc, xmbh, '');
                if (xmbh == mbid) {
                    //wjbj_jl3.value = xmbh; 
                    os1.options[jsq].selected = true;
                }
                jsq++; if (!pd) continue;
                
                for (var z = 0; z < dobj.lastChild.childNodes.length; z++) {
                    dfml = dobj.lastChild.childNodes[z];
                    if (dfml.lastChild.className != "menu") continue;
                    xmmc = dfml.childNodes[1].innerHTML;
                    xmbh = dfml.lastChild.id;
                    os1.options[jsq] = new Option("  ┠ " + xmmc, xmbh, '');
                    if (xmbh == mbid) {
                        //wjbj_jl3.value = xmbh;
                        os1.options[jsq].selected = true;
                    }
                    jsq++;
                }
            }
        }
        function mllbxs(mbid) {
            var os1 = $("wt3");
            for (var i = 0; i < os1.options.length; i++) {
                if (mbid == os1.options[i].value) {
                    os1.options[i].selected = true;
                    break;
                }
            }
        }

        function mmclick(mmobj) {
            if (mmobj.readOnly == true) {
                if (mmobj.value == "") {
                    mmobj.readOnly = false;return true;
                } else {
                    if (confirm("是否改变密码?")) { mmobj.value = ""; mmobj.readOnly = false;return true; } else { mmobj.blur(); }
                }
            }
            return false;
        
        
            //if (mmobj.readOnly == false) return true;
            //if (mmobj.value == "" && mmobj.readOnly==true) { mmobj.readOnly = false; return true; }
            //if (!confirm("是否改变密码?")) { mmobj.readOnly = true; mmobj.blur(); return false; }
            //mmobj.value = ""; mmobj.readOnly = false;mmobj.focus(); 
        }
        function changetp(pd) {
            if (pd == "z") { bdbq.value = ++bdbq.value; } if (pd == "y") { bdbq.value = --bdbq.value; }
            if (bdbq.value < 0) { bdbq.value = 9; } if (bdbq.value > 9) { bdbq.value = 0; }
            $("t_bq").src = "/tp/face2/f" + bdbq.value + ".gif";
        }
        function $p(rid) {return parent.document.getElementById(rid)}
        function $(rid) { return document.getElementById(rid); }

        
    </script>
</head>
<body onmousedown ="parent._C.zd(ckid);" onLoad="csdy();">
<div id="d0" style="display:none;">
	<p style="display:block" id="wp1"><label id="w1" class="la1">文件名称:</label><input type="text" id="wt1" class="tx3"  onkeypress="if (window.event.keyCode==13){wt2.focus();return false;}"/></p>
	<p><label id="w2" class="la1">文件备注:</label><input type="text" id="wt2" class="tx3" onKeyPress="if (window.event.keyCode==13){wt3.focus();return false;}"/></p>
	<p><label id="w3" class="la1">改变目录:</label><select size="1" id="wt3" name="wt3" class="tx3" onkeypress="if (window.event.keyCode==13){wb1.focus();return false;}"></select></p>
		<p style="text-align:center;margin-top:20px;">
		<input type="button" value="修改" onClick="f_bj_tj();"  id="wb1"/>
		<input type="button" value="删除" onClick="f_sc_tj();"  id="wb2"/>
		<input type="button" value="关闭" onClick="gb();" id="wb3"/>
	</p>
</div>
<div id="d1" style="display:none;">
	<p><label class="la1" id="m0">目录标题:</label><input type="text" id="mt1"  class="tx3"/></p>
	<p><label class="la1" id="m1">签写署名:</label><input type="text" id="mt2" class="tx3"/></p>
	<p><label class="la1" id="m2">目录密码:</label><input type="password" id="mt3" class="tx3" readonly="true" onFocus="if(mmclick(this)) this.focus();"/></p>
	<p style="text-align:center;margin-top:20px;">
		<input type="button" value="修改" onClick="m_bj_tj();" id="mb1"/>
		<input type="button" value="删除" onClick="m_sc_tj();" id="mb2"/>
		<input type="button" value="关闭" onClick="gb();" id="mb3"/>
	</p>

</div>

<div id="d2" style="display:none;">
	<p> <label id="l1">姓名:</label><input type="text" id="xm" class="tx3" style="width:80px;"/>
		<label id="l2">公开:</label><input type="checkbox" id="gkpd"  checked />
		<img border="0" src="/images/00zxz1.gif" width="15" height="15" onClick="changetp('z');" style="cursor:pointer;"/>
		<img border="0" name="t_bq" id="t_bq" src="/images/f1.gif" width="15" height="15"/>
		<img border="0" src="/images/00zxy1.gif" width="15" height="15" onClick="changetp('y');" style="cursor:pointer;"/> 
	</p>
	<p><textarea id="nr"></textarea></p>	
	<p align="center" style="margin-top:10px;">	
			<input type="button" id="lb1" value="修改" onClick="l_bj_tj();"/>
			<input type="button" id="lb2" value="删除" onClick="l_sc_tj();"/>
			<input type="button" id="lb3" value="关闭" onClick="gb();"/>
		    <input type="hidden" id="bdbq" value="1" />
	</p>
</div>
</body>
</html>
