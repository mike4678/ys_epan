
function AJ(rdz, rml, rcallback, rsj, rdata, rerrcl) {
    var method = "GET"; if (!$_(rdata)) method = "POST"; if ($_(rdata)) rdata = null; if ($_(rsj)) rsj = null; if ($_s(rcallback)) rcallback = null;
    if (!$_s(rml)) rml = ""; if (rml != "") rml = "&" + rml;
    rdz = "/cx_old/" + rdz + ".aspx?dlmc=" + ddlmc + rml;
    frxcx.XMLHttp.sendReq(method, rdz, rdata, rcallback, rsj, rerrcl);
}
function $i(id, rhtml) {if ($_s(rhtml)) { $id(id).innerHTML = rhtml; return; } return $id(id).innerHTML;}
function $a(ro, attName, attValue) {
    if ($_(attName)) attName = "d"; attName = "data-" + attName;
    if (!$_(attValue)) { ro.setAttribute(attName, attValue); return; }
    return ro.getAttribute(attName);
}
function $id(id) {if (typeof (id) != "string") return id; return document.getElementById(id);}
function vre(z1, z2, z3) { jgzz = z1.replace(new RegExp(z2, "g"), z3); return jgzz; }
function $_(r) { return typeof r == "undefined"; }
function $_s(r) { return typeof r == "string"; }
function $c(ro, claName, kg) {
    //无值判断 ；1.增加；0,删除；2，更换
    if ($_s(ro)) ro = $id(ro); if (!ro) { alert("$c(),目标不存在") };
    if ($_(claName)) return ro.className; var c1 = " " + ro.className + " "; var cz = (c1.indexOf(" " + claName + " ") >= 0);
    if ($_(kg)) return cz; if (kg == 2) kg = cz ? 0 : 1;
    if (!kg) { if (cz) { c1 = vre(c1, " " + claName + " ", ""); ro.className = $trim(c1); } return 0; }
    if (!cz) { c1 = ro.className + " " + claName; ro.className = $trim(c1); } return 1;
}
function $k(ro, kg) {
    if ($_(kg)) return !$c(ro, "g");
    if (kg) $c(ro, "g", 0); else $c(ro, "g", 1);
    //if (kg) ro.style.display = "block"; else ro.style.display = "none";
}
var $b = function (ro, kg) { if ($_(kg)) return ro.disabled; if (kg) ro.removeAttribute('disabled'); else ro.disabled = 'false'; }
var $e = function (rq, f) { for (var i = 0; i < rq.length; i++) { if (f.call(rq[i])) { return i; break; } } return -1; }
var $bm = function (r) { return encodeURIComponent(escape(r)); }
var $jm = function (r) { return unescape(decodeURIComponent(r)); }

var $tn = function (r) { return r.tagName.toLowerCase(); }
var $trim = function (r) { return r.replace(/(^\s*)|(\s*$)/g, ""); }




var jkjsq = 0; var tcxk = "T";
var Q = {
    a: false, ggts: false, mlbh: -1, flid: "", iptsf: "", llcx: "", ckbh: "0", wg: false, lybj: { bh: "", tp: "", gk: "" }, bjlx: "",
    bjts: { o: null, bh: function (ro) { this.qx(); this.o = ro; this.o.style.border = "2px solid red"; }, qx: function () { if (!!this.o) { this.o.style.border = "0";; this.o = null; } } },
    fl_mc: function () { if (Q.flid == "") return ""; return Q.flid.split("_")[2] },
    mlxz: function (rbh) { if (Q.mlbh == rbh) return; Q.mlbh = rbh; Q.flid = ""; if (rbh >= 0) { qhzml(3); } else { if ($("ml_" + 3).style.display == "block") { qhzml(2); } } },
    f_ly: "", fmax: 0, f_s: "", mcs: false
}
Q.llbg = function (x) { if (Q.llcx == x) return; if (Q.llcx == "") { Q.llcx = x; return; } Q.llcx = x; _CK.xxtx.tx("A_LL;"); }
var _m = {
    jlid: "", jlsx: "", jlmm: "", sBt: "", sSm: "", sMm: "", xzbh: "", bhjl: "",
    xs: function () {
        var omm = document.getElementById("menuList"); _m.bc(); var obj1 = Rx(); if (!obj1) return; omm.innerHTML = obj1.Yz3.value; this.bhjl = "|";
        for (var i = 0; i < omm.childNodes.length; i++) {
            if (omm.childNodes[i].childNodes.length > 2) {
                var nbh1 = omm.childNodes[i].id.split("_")[0].substr(1);
                this.bhjl += (nbh1 + "|");
                omm.childNodes[i].childNodes[0].onclick = function () { _CK.qxsm.o = this; _C.kq(_CK.qxsm); }
                omm.childNodes[i].childNodes[1].onclick = function () { _m.b(this); }
                omm.childNodes[i].childNodes[2].onclick = function () { mlkq(this); }
            }
        }
        if (this.xzbh != "") { var b1 = this.xzbh; this.xzbh = ""; var o = _m.fa(b1); o.cssj(); var M = new ML(b1); if (M.mmcd == 0) { Q.mlxz(b1); } else { Q.mlxz(-1); } }
        if (Q.mlbh == -1) { qhzml(1); } else { mlkq_x(Q.mlbh); }
        if (Qzdqbh < 0) {
            $("idzdy").style.display = "none";
            if ($("idzdy1").style.display == "none") $("zdy_wz0").style.display = "none";
        }
    },
    cxdq: function () { readxml("ml_dq", "", "", "", ""); },
    cxdq1: function () { alert(C.load); _m.cxdq(); },  //需要重新读取目录数据
    b: function (ro) {
        var mlbh = this.qdmlbh(ro); if (!Q.a && ro.parentNode.childNodes[4].className != "yybj") { alert(C.m1); return; }
        this.jlid = mlbh; Q.bjts.bh(ro.parentNode.childNodes[2]); Q.bjlx = "m"; _C.kq(_CK.bj);
    },
    add: function (mlbh) { this.xzbh = mlbh; bdbt.value = ""; $("bdmlmm").value = ""; },
    tj2: function () { readxml("xzml", this.sBt, this.sSm, $("bdmlmm").value, _CK.mmsz.z[0]); },
    tj: function () {
        if (_CK.bj.zt) { _CK.bj.gb(); }
        //if ($("bdmlmm").value != "") { if (($("bdmlmm").value) != $("bdmlmm").value) { alert("目录密码含有非法字符！"); return; } }
        if (!dxkbt && !Q.a) { alert(C.m3); return false; }; this.sBt = $("bdbt").value; this.sSm = $("bdsm").value;
        if (this.sBt.length > 50) { alert(C.m10 + this.sBt.length); return false; } //目录标题长度不能超过50字符
        if (this.sSm.length > 50) { alert(C.m11 + this.sSm.length); return false; } //属名长度不能超过50字符
        if (this.sBt == "") { alert(C.m4); return false; }; if ($("bdmlmm").value.length > 15) { alert(C.m5); return false; }
        _CK.mmsz.z[0] = "011000"; if ($("bdmlmm").value != "") { _CK.mmsz.z[1] = false; _C.kq(_CK.mmsz); return; } this.tj2();
    },
    qdmlbh: function (ro) { return ro.parentNode.id.split("_")[0].substr(1); }
}
_m.gg = function (rbh, r1) { $("ZMm_" + rbh).parentNode.childNodes[2].className = r1; }
_m.f = function (mlbh) { if (!mlbh) mlbh = Q.mlbh; for (var j = 0; j < myTree.branches.length; j++) { if (myTree.branches[j].id == mlbh) { return myTree.branches[j]; break; } } return null; }
_m.fa = function (mlbh) { var x = this.f(mlbh); if (!x) { x = new branch(mlbh); myTree.add(x); } return x; }
_m.sz = function (mlbh, rcx, rpz) { var o = _m.fa(mlbh); o.llcx = rcx; o.scpz = rpz; Q.llbg(rcx) }
_m.bc = function () { for (var j = 0; j < myTree.branches.length; j++) { myTree.branches[j].bc(); } }
_m.jljc = function (rbh) { if (this.bhjl.indexOf("|" + rbh + "|") >= 0) return 1; return 0; }
_m.dqwj = function (rbh) { readxml("dq", rbh); }

var _CK = {
    glts: { mc: "glts", w: 290, h: 110 }, gbtz: { mc: "gbtz", w: 360, h: 260 }, yzck: { mc: "yzck", w: 300, h: 220, z: { a: "" }, wz: [180, 150] },
    bj: { mc: "bj", w: 255, h: 180, v: "?v=7.0" }, qxsm: { mc: "qxsm", w: 300, h: 200 }, mmrz: { mc: "mmrz", w: 264, h: 310, v: "?v=2" },
    mmsz: { mc: "mmsz", w: 300, h: 350, z: ["", false], wz: [180, 150] },
    scjd: { mc: "scjd", w: 362, h: 180, v: "?v=6.8", nx: 1 }, zxlb: { mc: "zxlb", w: 350, h: 250, size: true },
    xzll: { mc: "xzll", w: 350, h: 300, size: true }, xzlb: { mc: "xzlb", w: 400, h: 300, size: true },
    zxtp: { mc: "zxtp", w: 750, h: 600, v: "?v=1.0", size: true }, wgts2: { mc: "wgts2", w: 250, h: 140 },
    xxtx: { mc: "xxtx-1", w: 260, h: 120, v: "?v=1", size: true, bjs: "#cffebb", cz: "", tx: function (r) { this.cz += r; _C.kq(this); } },
    bjym: { mc: "bjym", w: 500, h: 410, size: true, bjs: "#0099CC" }, xztp: { mc: "xztp", w: 402, h: 365, v: "?v=1.0", size: true, z: { hs: "", src: "" } },
    wbck: { mc: "wbck", w: 500, h: 410, size: true, z: "" },
    bjd: { mc: "bjd", w: 310, h: 400,v:"?v=1.0", size: true, z: "" },
    bjly: { mc: "bjly", w: 300, h: 230, size: true, z: "" },
    bjlyd: { mc: "bjlyd", w: 470, h: 550, size: true, z: "" }
}
var myTree = new tree();
function tree() { this.branches = new Array(); this.add = addBranch; }
function addBranch(branch) { this.branches[this.branches.length] = branch; }
function branch(id) {
    this.id = id; this.text = ""; this.sj = new Date().getTime(); this.rzmm = ""; this.yqgx = 0; this.llcx = 0; this.xkck = 1; this.xkxz = 1; this.wjsl = -1; this.scpz = "";
    this.cssj = function () { this.sj = new Date().getTime(); var M = new ML(this.id); this.xkck = M.xkck(); this.xkxz = M.xkxz(); this.yqgx = 0; this.text = ""; }
    this.cs = function () { var sjc = new Date().getTime() - this.sj; sjc = Math.round(sjc / 60 / 1000); if (sjc > 50) { return true; } else { return false; } }
    this.xygx = function () {
        if (!_m.jljc(this.id)) return false; if (this.yqgx) return true; if (this.cs()) return true; var M = new ML(this.id);
        if (this.xkck != M.xkck()) return true; if (!M.xkck()) return false;
        if (this.wjsl == 0) return false; if (this.xkxz != M.xkxz()) return true; if (this.llcx != Q.llcx) return true; return false;
    }
    this.bc = function () {
        if (this.wjsl == 0) { this.text = ""; return; } if (this.text != "") return;
        if (_m.jljc(this.id)) { this.text = $("ZMm_" + this.id).innerHTML; Ts(C.save + this.id, 1); } //保存目录

    }
}
//----------------
function findml(mlbh) {
    //alert("findml() 已经停用");
    var i = -1; for (var j = 0; j < myTree.branches.length; j++) { if (myTree.branches[j].id == mlbh) { i = j; break; } } return i;
}
function Ts(s, addpd) { return; $("laczts").innerHTML += "|" + sj + s; jcgd(); }
//------------------
function readxml(cz, z1, z2, z3, z4) {
    if (!Qcxzt) { alert(C.m0); return; } var dobj = Rx(); if (!dobj) return; Qcxzt = false;
    dobj.Yz0.value = cz; dobj.Yz1.value = (z1) ? z1 : ""; dobj.Yz2.value = (z2) ? z2 : ""; dobj.Yz3.value = (z3) ? z3 : ""; dobj.Yz4.value = (z4) ? z4 : "";
    Ts("R(" + cz + "," + dobj.Yz1.value + ")", 1);
    setTimeout("frxcx.document.forms[0].submit();", 100); return;
}
function Rx() { try { var ox = frxcx.document.forms[0].Yz0 } catch (error) { alert(C.m0); return false; } finally { } return frxcx.document.forms[0]; }
function ML(mlbh) {
    if (mlbh == "") mlbh = Q.mlbh; var _t = this; _t.bh = mlbh; _t.oM = $("ZMm_" + _t.bh); _t.oP = _t.oM.parentNode; _t.mmcd = _t.oP.id.split("_")[1].split("|")[0]; _t.sx = _t.oP.id.split("|")[1]; _t.pwflag = (_t.oP.id.split("|").length > 2) ? _t.oP.id.split("|")[2] : "0";
    _t.jl = function () { return _m.f(mlbh); }
    _t.xkxz = function () { if (Q.a) return true; if (_t.mmcd == 0) return true; if (!_rz.jc(_t.bh)) return (_t.sx.substr(5, 1) == 1); if (_rz.jc(_t.bh)) return (_t.sx.substr(2, 1) == 1); return true; };
    _t.xkck = function () { if (Q.a) return true; if (_t.mmcd == 0) return true; if (_t.sx == "000000") return false; if (!_rz.jc(_t.bh)) return (_t.sx.substr(3, 1) == 1); return true; };
    _t.xksc = function () { if (Q.a) return true; if (_t.mmcd == 0) { if (!dxksc) { return false; } return true; }; if (_rz.jc(_t.bh)) { if (_t.sx.substr(1, 1) != 1) { return false; } return true; }; if (_t.sx.substr(4, 1) != 1) { return false; }; return true; };
    _t.xkadd = function (tzpd) {
        if (FFGQPD == "1") { if (tzpd) alert(C.q2); return false; }
        if (this.jl()) { if (this.jl().wjsl > xdwjsl) { if (tzpd) alert(C.w1); return false } }
        if (Q.a) return true; if (Qzdqbh == _t.bh) { if (tzpd) alert(C.q1); return false; }; if (_t.mmcd == 0) { if (!dxksc) { if (tzpd) alert(C.q3); return false; } return true; };
        if (_rz.jc(_t.bh)) { if (_t.sx.substr(1, 1) != 1) { if (tzpd) alert(C.q4); return false; } return true; }; if (_t.sx.substr(4, 1) != 1) { if (tzpd) alert(C.q5); return false; }
        return true;
    }
    _t.xkbj = function () { if (Q.a) return true; if (_t.mmcd == 0) { alert(C.q6); return false; }; if (_rz.jc(_t.bh)) { if (_t.sx.substr(0, 1) != 1) { alert(C.q7); return false } return true; }; alert(C.q8); return false; };
}
var _rz = {
    mlbh: 0, sx: "", rzjl: "",
    jl: function (rbh, rmm) { var s1 = "[" + rbh + "]"; if (this.rzjl.indexOf(s1) < 0) this.rzjl += s1; var o = _m.fa(rbh); o.rzmm = rmm; },
    jc: function (rbh) { return (this.rzjl.indexOf("[" + rbh + "]") >= 0); },
    clear: function () { if (_CK.mmrz.zt) { if (_CK.mmrz.ck.style.display == "block") _CK.mmrz.gb(); } },
    kq: function (rmlbh, rsx) {
        this.mlbh = rmlbh; this.sx = rsx; $("ZMm_" + this.mlbh).parentNode.style.backgroundColor = "#F8C7C9";
        var h1 = (this.sx.substr(3, 3) == "000") ? "220" : "310"; if (_CK.mmrz.zt) { _CK.mmrz.ck.childNodes[1].style.height = h1 + "px"; } else { _CK.mmrz.h = h1; }
        _C.kq(_CK.mmrz); _CK.mmrz.gb = function () { _CK.mmrz.ck.style.display = "none"; $("ZMm_" + _rz.mlbh).parentNode.style.backgroundColor = "#FFFFFF"; };
    }
}
function mlkq(ro) {
    if (!Qcxzt) { alert(C.m0); return; } ro.blur(); var mlbh = _m.qdmlbh(ro); _rz.clear(); var M = new ML(mlbh);
    if (M.oM.style.display == "block") { M.oM.style.display = "none"; Q.mlxz(-1); return; }
    if (!Q.a && M.pwflag == "1") { _rz.kq(mlbh, M.sx); return; }
    if (!Q.a && M.mmcd != 0) { if (M.sx == "000000") { alert(C.q9); return; } if (M.sx.substr(0, 3) != "000") { _rz.kq(mlbh, M.sx); return; } }
    mlkq_x(mlbh); return;
}
function mlkq_x(mlbh) {
    var mllb = $("menuList"); var M = new ML(mlbh); Q.mlxz(mlbh); var dobj;
    for (var j = 0; j < mllb.childNodes.length; j++) {
        dobj = mllb.childNodes[j].lastChild; if (dobj.style.display == "block") { if (dobj.id != M.oM.id) { dobj.style.display = "none"; break; } }
    }
    var adpd = M.xkadd(false);
    fxk("filesc2", adpd); fxk("bulj", adpd); fxk("buzml", adpd);
    var xj = M.jl();
    if (xj) {
        if (!xj.xygx()) {
            if (M.oM.innerHTML == "" && xj.text != "") { M.oM.innerHTML = xj.text; Ts("从缓存读取:" + mlbh, 1); }
            if (M.oM.innerHTML != "") { mlxs(mlbh); M.oM.style.display = "block"; return; }
        }
    }
    M.oM.innerHTML = "<li class='lxts'><span class='s3'>" + C.x1 + "</span></li>"; M.oM.style.display = "block";
    _m.dqwj(mlbh);
}
function mlxs(mlbh) {
    var js = 0; var M = new ML(mlbh); var objml = M.oM; if (objml.innerHTML == "") objml.innerHTML = tjzf(mlbh); var tjpd = -1;
    for (var j = 0; j < objml.childNodes.length; j++) {
        dobj = objml.childNodes[j];
        if (dobj.className == "lxts") { if (dobj.childNodes[0].className == "s1") tjpd = j; continue };
        if (dobj.lastChild.className != "menu") { js += 1; continue; }
        js += dobj.lastChild.childNodes.length; if (dobj.lastChild.style.display == "block") dobj.lastChild.style.display = "none";
    }
    if (Q.flid != "") {
        if (Q.flid.split("_")[1] == mlbh) {
            if (document.getElementById(Q.flid)) { $(Q.flid).style.display = "block"; _zml.jcts(Q.flid); } else { Q.flid = ""; }
        } else { Q.flid = ""; }
    }
    var x1 = _m.f(mlbh); if (x1) x1.wjsl = js;
    if (tjpd >= 0) {
        var s1 = "<label>" + C.g3 + ":" + js;
        if (M.mmcd != 0) { s1 += " 目录认证:"; if (Q.a) { s1 += "管理员" } else { if (_rz.jc(mlbh)) { s1 += "通过"; } else { s1 += "放弃"; } } }
        s1 += "</label>"; objml.childNodes[tjpd].childNodes[0].innerHTML = s1;
    }
}
function checkfu() { //增加链接/文字
    var M = new ML(""); if (!M.xkadd(true)) return;
    var Sbt = scbt2.value; var Sdz = teljdz.value;
    if (Sbt == "") { alert(C.w3); return false; }
    if (Sdz == "") { alert(C.w4); return false; }
    if (Sdz.length > 200) { alert(C.w5); return false; }
    readxml("xzlj", Sbt, Sdz, Q.mlbh, Q.fl_mc());
}
function mldq(mlbh) {
    var xdobj = Rx(); if (!xdobj) return; var mdnr = $("ZMm_" + mlbh); mdnr.innerHTML = xdobj.Yz1.value + tjzf(mlbh);
    var o = _m.fa(mlbh); if (o) o.cssj();
    mlxs(mlbh); if (mlbh == Qzdqbh) { $("idzdy").style.display = "block"; $("zdy_wz0").style.display = "block"; Qzdy.kq() }
}
function tjzf(mlbh) {
    var s1 = "<li class='lxts'><span class='s1'></span>";
    s1 += "&nbsp;<a style='color:#999966;' href='javascript:' onclick='sxmldq(" + mlbh + ")'>[" + C.g4 + "]</a>";
    if (mlbh == Qzdqbh && Q.a) { s1 += " <font style='background-color:yellow;color:#000;padding:2px;'><b>注意:</b>自定义目录的内容会自动显示到客户端，请勿在此目录保存重要数据！</font>"; }
    s1 += "</li>"; return s1;
}
function qhzml(x) {
    var dobj = $("mm" + x); if (dobj.className == "ysm1" && dobj.style.display == "block") return;
    if (x == 3) { if (Q.mlbh == -1) { alert(C.ml1); return; } }
    for (i = 1; i <= 4; i++) { $("mm" + i).className = "ysm0"; }
    dobj.className = "ysm1"; for (i = 1; i <= 4; i++) $("ml_" + i).style.display = "none"; $("ml_" + x).style.display = "block";
}
function zmlkq(ro) {
    var menu = ro.parentNode.lastChild;
    var display = menu.style.display;
    menu.style.display = (display == "block") ? "none" : "block";
    Q.flid = (display == "block") ? "" : menu.id;
    if (display == "block") return;
    if (wjlx1.checked == true) { changelx(1) } else { changelx(2) }
    var objml = menu.parentNode.parentNode;
    for (var j = 0; j < objml.childNodes.length; j++) {
        dobj = objml.childNodes[j];
        if (dobj.lastChild.className == "menu") { dobjx = dobj.lastChild; if (dobjx != menu && dobjx.style.display == "block") { dobjx.style.display = "none"; } }
    }
    return;
}
function sxmldq(mlbh) { if (confirm(C.w7)) { _m.dqwj(mlbh); return; } }
var _zml = {
    jcts: function (rid) {
        var ob1 = $(rid);
        if (ob1.childNodes.length > 0) {
            if (ob1.lastChild.className == "lxts") ob1.removeChild(ob1.lastChild)
        } else { ob1.innerHTML = "<li class='lxts'><span class='s1' style='font-weight:bold;color:red;'>" + C.z2 + "</span></li>"; }
    },
    cj: function () {
        var M = new ML(""); if (!M.xkadd(true)) return false;
        zhbd = tezml.value;
        if (this.jc(zhbd) != "") { alert(this.jc(zhbd)); return false; }
        zhbd = vreplace(zhbd, " ", ""); zhbd = vreplace(zhbd, "&", "*");
        readxml("xzml2", zhbd, Q.mlbh, "", "");
        $("tezml").value = "";
    },
    jc: function (x1) {
        if (x1 == "") return (dxken) ? "The Child folder name cannot for be spatial!" : "子目录名称不能为空！";
        if (_zml.zfjc(x1) != "") return "子目录名称不得含有字符 " + _zml.zfjc(x1);
        if (x1.length > 50) return "子目录名称长度不能超过50个字符！"; return "";
    },
    zfjc: function (r1) { var s1 = "_#+?/%&\\<>\'\""; for (i = 0; i <= s1.length - 1; i++) { if (r1.indexOf(s1.substr(i, 1)) >= 0) return s1.substr(i, 1) } return ""; }
}
function addsj(mlbh) {
    var xdobj = Rx(); if (!xdobj) { return; }
    var mbml;
    if (xdobj.Yz2.value != "") {
        var fmlid = "z_" + mlbh + "_" + xdobj.Yz2.value;
        if (document.getElementById(fmlid)) { mbml = $(fmlid) } else { mbml = $("ZMm_" + mlbh); };
    } else {
        mbml = $("ZMm_" + mlbh);
    }
    var newNode = document.createElement("li"); mbml.insertBefore(newNode, mbml.childNodes[0]);
    mbml.childNodes[0].innerHTML = xdobj.Yz1.value; mbml.childNodes[0].childNodes[0].onclick = function () { _fbj.tj(this); }
    if (mbml.id.split("_")[0] == "z") _zml.jcts(mbml.id); mlxs(mlbh);
    if (mlbh == Qzdqbh) Qzdy.kq(); scbt2.value = ""; teljdz.value = ""; _m.gg(mlbh, "mln");
}
function fxwj(ro) {
    var fh = { bh: "", wjm: "", bz: "", kzm: "", dz: "", kqzf: "" }; fh.bh = ro.parentNode.childNodes[0].id.split("_")[1]; fh.wjm = ro.parentNode.childNodes[1].innerHTML;
    fh.dz = ro.parentNode.childNodes[1].href; var n1 = fh.wjm.lastIndexOf(" "); fh.wjm = fh.wjm.substr(0, n1); fh.kzm = fh.wjm.substr(fh.wjm.lastIndexOf(".") + 1).toLowerCase();
    if (ro.parentNode.childNodes[2].className == "yyck") { fh.bz = ro.parentNode.childNodes[3].innerHTML; fh.kqzf = ro.parentNode.childNodes[2].innerHTML; } else { fh.bz = ro.parentNode.childNodes[2].innerHTML; }
    return fh;
}
var _fbj = {
    id0: "", lx: "", ml0: "", ob: null,
    tj: function (ro) {
        this.ob = ro;
        this.id0 = ro.parentNode.childNodes[0].id;
        this.lx = this.id0.split("_")[0];
        this.ml0 = ro.parentNode.parentNode.id.split("_")[1];
        if (this.lx == "z" && !Q.a) { alert("子目录需管理员登陆后才能编辑"); return; }
        if (this.lx != "z") { if (ro.parentNode.childNodes[ro.parentNode.childNodes.length - 1].className != "yybj") { var M = new ML(this.ml0); if (!M.xkbj()) return; } }
        Q.bjts.bh(ro.parentNode.childNodes[1]);
        Q.bjlx = this.lx;
        _C.kq(_CK.bj);
    }
}
function f_kzm(a) { var x1 = a.lastIndexOf("."); if (x1 <= 0) return ""; return a.substr(a.lastIndexOf(".")).toLowerCase(); }
function changelx(pd) {
    if ($("idwj" + pd).style.display == "block") return;
    for (var i = 1; i <= 3; i++) { $("idwj" + i).style.display = "none" }
    $("idwj" + pd).style.display = "block";
    if (pd == 3) $("tezml").focus();
    if (pd == 2) $("scbt2").focus();
    if (pd == 1) $("scbt").focus();
}
function zfaq(str) {
    str = vreplace(str, "'", "’"); str = vreplace(str, "\"", "|"); str = vreplace(str, "<", "《"); str = vreplace(str, ">", "》");
    str = vreplace(str, "&#", "*");
    return str;
}
function zfaqwjm(str) { str = vreplace(str, "'", "’"); return str; }
function vreplace(z1, z2, z3) { jgzz = z1.replace(new RegExp(z2, "g"), z3); return jgzz; }
function show(ro) {
    var fx1 = fxwj(ro); var lx = fx1.kqzf; var bh = fx1.bh; var kzm = "." + fx1.kzm; var wjm = fx1.wjm; var wdz = fx1.dz;
    if (lx == "查看" || lx == "View") {
        Q.ckbh = bh;
        x = window.open(Qzydz + "ck/show_tp.htm", duser + "ys168_1", "width=750,height=" + (600) + ",toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=yes");
        x.focus();
    }
    if (lx == "播放" || lx == "Play") {
        x = window.open(Qzydz + "ck/show_sp.aspx?wdz=" + wdz, "ys168_2", "width=355,height=" + (288) + ",toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=yes");
        x.focus();
    }
    if (lx == "打开" || lx == "Open") {
        x = window.open(wdz, duser + "ys168_3" + bh, "width=600,height=450,toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=yes"); x.focus();
        //_CK.wbck.z = fx1.dz;
        //_C.kq(_CK.wbck);
        //return;
    }
    if (x == null) { alert("由于您的浏览器禁止了弹出窗口，不能进行在线观看！"); return false; }
}
function glyqxjy(rzt) { if (Q.a != rzt) { Q.a = rzt; glyqxjy(); } };
function glyqxkz() {
    var sglmc = C.g2;
    if (Q.a) {
        if (Q.wg) { loginpermissions(); } _rz.clear(); bdglymm.value = "";
        $("glyq1").style.display = "none"; $("glyq2").style.display = "block";
        document.getElementById("mm4").innerHTML = sglmc + "<font color='green'>√</font>";
    } else {
        $("glyq1").style.display = "block"; $("glyq2").style.display = "none"; document.getElementById("mm4").innerHTML = sglmc; if (_CK.glts.zt) _CK.glts.gb();
    }
    buqx(); var o1 = _m.f(Q.mlbh); if (!o1) return; if (o1.xygx()) _m.dqwj(Q.mlbh);
}
function buqx() {
    if (!document.getElementById("buxz")) return; if (Q.a) {
        fxk("buly", true); fxk("sutjbt", true); fxk("buxz", true); fxk("filesc2", true); fxk("bulj", true); fxk("buzml", true); lyxkzt(true);
    } else {
        fxk("sutjbt", dxkbt); fxk("buxz", dxkjl); lyxkzt(dxkly);
        if (Q.mlbh >= 0) { var M = new ML(""); fxk("filesc2", M.xkadd(false)); fxk("bulj", M.xkadd(false)); fxk("buzml", M.xkadd(false)); }
        else { fxk("filesc2", dxksc); fxk("bulj", dxksc); fxk("buzml", dxksc); }
    }
}
//-------
var Qdhrs = ""; Qlyfyxx = ""; var lygxpd = "0";
var Qzdy = {
    zt: false,
    kq: function () { if (!this.zt) { this.zt = true; var sdz = document.createElement("script"); sdz.setAttribute("src", Qzydz + "js/zdy.js?wns0125"); document.body.appendChild(sdz); } else { zdqdq(); } }
};
function lyxkzt(rxk) { var onr = $("nr"); if (!rxk) { fxk("buly", false); onr.readOnly = true; if (onr.value == "") { onr.value = "未开放访客留言权限" }; onr.style.color = "#696969"; } else { fxk("buly", true); onr.readOnly = false; onr.style.color = "blue"; if (onr.value == "未开放访客留言权限") { onr.value = "" }; } }
function fxk(rid, rzt) { $(rid).style.textDecoration = (rzt) ? "none " : "line-through"; };
function lyfy(ro, rfyxx) { if (!Qcxzt) { alert(C.m0); return; } ro.disabled = "true"; readxml("lyb_dq", rfyxx, "", "", ""); }
function Lyb_xs() { var ob1 = Rx(); if (!ob1) return; $("lynr").innerHTML = ob1.Yz4.value; try { var ryz0 = ob1.Yz0.value; if (ryz0 != "lyxg" && ryz0 != "lysc") { $("lynr").scrollTop = 0; } } catch (error) { } finally { } }
function checkly(ryzm) {
    if (!Q.a && dxkly == 0) { if (dxkly == 0) { alert(C.l1); return false; }; }; //管理员已禁止留言功能 提示
    if ($("bdgkpdlyb").checked) {
        if (!Q.a && dxkly == 2) {
            if (!confirm(C.l4)) { return false; };  //管理员禁止公开留言 提示
            $("bdgkpdlyb").checked = false;
        };
    };
    var sxm = $("xm").value;
    var snr = $("nr").value;
    if (sxm == "") { alert(C.l2); return false };  //留言人姓名不能为空 提示
    if (snr == "") { alert(C.l3); return false };  //留言内容不能为空 提示
    if (snr.length > 1000) { alert(C.ly1 + snr.length); return false; };
    var cz1 = (($("bdgkpdlyb").checked) ? "n" : "y") + $("bdbq").value;
    if (Q.a && $("bdgkpdlyb").checked == false) { alert("由于您已经在管理区登陆，不公开的留言也可以查看，\n\n如要查看效果，请在管理区退出"); }
    readxml("lytj", cz1, sxm, snr, ryzm);
}

function lyxg2(ro) { ro.parentNode.childNodes[0].click(); }
// function lyxg(bh, tp, gk) { if (!Q.a && $("Xm_" + bh).nextSibling.className != "yybj") { alert("管理员在空间管理区登陆后才能编辑此项数据！"); return; }; if (gk == "y" && !Q.a) { alert("只有管理员登陆后才能修改不公开的留言"); return; }; Q.lybj.bh = bh; Q.lybj.tp = tp; Q.lybj.gk = gk; Q.bjlx = "l"; _C.kq(_CK.bjly); return; } -->
function changetp(pd) { if (pd == "z") { bdbq.value = ++bdbq.value; } if (pd == "y") { bdbq.value = --bdbq.value; }; if (bdbq.value < 0) { bdbq.value = 9; } if (bdbq.value > 9) { bdbq.value = 0; } document.images['t_bq'].src = "user/face/f" + bdbq.value + ".gif"; }
function lygxjc() { if (lygxpd == "1") { readxml("lyb_dq", Qlyfyxx, "", "", ""); return; }; var ob1 = $("lynr"); var ars; for (var z = 0; z < ob1.childNodes.length; z++) { if (ob1.childNodes[z].className != "cslyb") continue; ars = ob1.childNodes[z].lastChild.innerHTML.split("."); if (ars.length != 4) continue; if (!Q.a) { ob1.childNodes[z].lastChild.innerHTML = ars[0] + "." + ars[1] + "." + ars[2] + "." + "*"; } else { ob1.childNodes[z].lastChild.innerHTML = ars[0] + "." + ars[1] + "." + ars[2] + "." + ob1.childNodes[z].lastChild.id.split("_")[1]; } } }
var jcjs = 0;
function checkgl(ryzm) { if (bdglymm.value == "") { alert(C.g1); $('bdglymm').focus(); return false; }; readxml("gly_dl", bdglymm.value, ryzm, ddlmc, ""); return; }; function fglytc() { readxml("gly_tc", "", "", "", "") }
function mlq_ov(ro) { if (ro.className == "ysm1") return; ro.className = "ysm0x" }; function mlq_ou(ro) { if (ro.className == "ysm1") return; ro.className = "ysm0" };
function kq_help() { art.dialog.open('user/note/help_bj.htm', {title: '如何编辑、删除数据'}); }

function fmore() {
    if ($("scmore").checked == true) {
        if (Q.mcs == false) {
            Q.mcs = true; var s1 = ""; for (var i = 1; i <= 5; i++) {
                s1 += "<Iframe id='upf_" + i + "' name='upf_" + i + "' src='/upfile/up.htm?" + i + "0" + dfgxz + ((dxken) ? "1" : "0") + "92";
                s1 += "' width='230' height=24 scrolling=no frameborder='0'></iframe>";
            }; $("rqfile").innerHTML = s1;
        }
        $("rqfile").style.display = "block";
    } else { $("rqfile").style.display = "none"; }
    jcgd();
}
function $i(rid, rz) { if (rz) { $(rid).innerHTML = rz; } else { return $(rid).innerHTML } };
var _C = {
    z: 2000, t: function () { _C.z += 1; return _C.z }, zd: function (rid) { if ($(rid).style.zIndex < _C.z) { $(rid).style.zIndex = _C.t(); } },
    kq: function (r) { if (r.mc == "scjd" && Q.fk_upload == 0) { alert(C.scjdbj || "空间主人已关闭访客上传权限"); return; } if (!r.zt) { r.zt = true; this.qd(r); this.jz(r); } else { this.cx(r); eval(r.id + "_i.csdy();"); } },
    qd: function (r) {
        r.id = "D_" + r.mc.split("-")[0]; var s1 = "<div id='" + r.id + "' class='ck' style='width:" + r.w + "px;left:180px;top:150px;display:none;'>";
        s1 += "<div class='ckbt'><a href='javascript:' onclick='this.focus();eval(_CK." + r.mc.split("-")[0] + ".gb())' class='abu'>x</a><label></label></div>";
        s1 += "<div style='height:" + r.h + "px; overflow-y :auto;'>";
        s1 += "</div>";
        r.url1 = "/user/ck/D_" + r.mc + ".php" + ((r.v) ? r.v : "");
        if (r.size) s1 += "<div style='text-align:right;background-color:" + ((r.bjs) ? r.bjs : "#FFFFE6") + ";'><img style='cursor:nw-resize;' src='/images/ccls.gif' width='12' height='12' /></div>";
        s1 += "</div>"; var e1 = document.createElement('div'); document.body.appendChild(e1); e1.innerHTML = s1; r.ck = $(r.id);
        var ir1; if (isIE && !isIE10) { ir1 = document.createElement("<iframe name='" + r.id + "_i'>"); } else { ir1 = document.createElement('iframe'); ir1.name = r.id + "_i"; }
        ir1.id = r.id + "_i"; ir1.style.cssText = "width:100%;height:100%;border:0;"; ir1.scrolling = (r.size) ? "auto" : "no";
        r.ck.childNodes[1].appendChild(ir1);
        drag(r.ck.childNodes[0], r.ck, true);
        if (r.size) drag(r.ck.lastChild, r.ck.childNodes[1], false); r.ck.childNodes[1].onmousedown = function () { if (r.ck.style.zIndex < _C.z) { r.ck.style.zIndex = _C.t(); } };
        r.gb = function () { r.ck.style.display = "none" };
    },
    xs: function (r) {
        if (r.ck.style.zIndex < _C.z) r.ck.style.zIndex = _C.t();
        if (r.ck.style.display == "none") {
            if (r.url1 != "") { r.ck.childNodes[1].childNodes[0].src = r.url1; r.url1 = ""; }
            if (!r.nx) r.ck.style.display = "block";
        }
    },
    jz: function (r) { if (r.wz) { r.ck.style.left = r.wz[0]; r.ck.style.top = r.wz[1]; this.xs(r); return; }; var x1 = parseInt(document.body.clientWidth / 2 - parseInt(r.ck.style.width) / 2); var y1 = parseInt(document.body.clientHeight / 2 - parseInt(r.ck.childNodes[1].style.height) / 2 - 40); r.ck.style.left = x1; r.ck.style.top = y1; this.xs(r); },
    cx: function (r) { if (r.wz) { _C.jz(r); return; } var w1 = parseInt(r.ck.style.width) / 2; var h1 = parseInt(r.ck.childNodes[1].style.height) / 2; var l1 = parseInt(r.ck.style.left); var t1 = parseInt(r.ck.style.top); if (l1 > (document.body.clientWidth - w1)) { this.jz(r); return; }; if (t1 > (document.body.clientHeight - h1)) { this.jz(r); return; }; this.xs(r); },
    bt: function (rid, rbt) { var e = $(rid); if (!e || !e.childNodes[0] || !e.childNodes[0].childNodes[1]) return; e.childNodes[0].childNodes[1].innerHTML = rbt; }
};
function drag(o, omb, isyd) { if (!isyd) { o.firstChild.onmousedown = function () { return false; }; }; o.onmousedown = function (a) { if (isyd) { if (omb.style.zIndex < _C.z) { omb.style.zIndex = _C.t(); } }; var d = document; if (!a) a = window.event; var x_ = a.clientX; var y_ = a.clientY; var x0; var y0; if (isyd) { x0 = parseInt(omb.style.left); y0 = parseInt(omb.style.top); } else { x0 = parseInt(omb.parentNode.style.width); y0 = parseInt(omb.style.height); }; if (o.setCapture) { o.setCapture(); } else if (window.captureEvents) { window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP); }; d.onmousemove = function (a) { if (!a) a = window.event; var x1 = x0 + (a.clientX - x_); var y1 = y0 + (a.clientY - y_); x1 = (x1 < 0) ? 0 : x1; y1 = (y1 < 0) ? 0 : y1; if (isyd) { omb.style.left = x1; omb.style.top = y1; } else { omb.parentNode.style.width = x1 + "px"; omb.style.height = y1 + "px"; }; }; d.onmouseup = function () { if (o.releaseCapture) { o.releaseCapture(); } else if (window.captureEvents) { window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP); }; d.onmousemove = null; d.onmouseup = null; }; } }
function fa1() { Qdhrs = $("zxrsts").innerHTML; $("zxrsts").innerHTML = "<u>" + Qdhrs + "</u>"; return "/user/C_zxsj.php?user=" + ddlmc; }
function fa2(rs) {
    var f = eval("(" + rs + ")");
    Qwh = f.wh;
    if (!f.zx) return; $i("zxrsts", Qdhrs);
    if (f.zx != Qdhrs) ymbgz("zxrsts", f.zx); if (f.ll != "") Q.llbg(f.ll); if (f.sy != "") ymbgz("kjz2", f.sy); var x1 = (f.gl == "1") ? true : false;
    if (Q.a != x1) { Q.a = x1; glyqxkz(); }
    if (f.gx != "") { _CK.xxtx.tx(f.gx); }
    if (Q.mlbh != -1) { var o1 = _m.f(); if (!o1) return; if (o1.xygx()) { if (!Qcxzt) { Q.gxxl.wj = Q.mlbh; return; } _m.dqwj(Q.mlbh); } }
}
Q.gxxl = { ly: 0, ml: 0, wj: 0, xzwj: "" }
function ymbgz(rid, rz) {
    if ($i(rid) == "-") { $i(rid, rz); return; }
    if (rz == $i(rid)) return; var cjl = $(rid).style.color;
    if ($(rid).style.color == "red") return; $(rid).style.color = "red"
    setTimeout("$('" + rid + "').style.color='" + cjl + "';$('" + rid + "').innerHTML='" + rz + "';", 1000);
}
function Jxzx() {
    if (!Qcxzt) return;
    if (Q.gxxl.xzwj != "") { var x1 = Q.gxxl.xzwj; Q.gxxl.xzwj = ""; readxml("dqfile", x1.split(",")[0], x1.split(",")[1], "", ""); return; }
    if (Q.gxxl.ly == 1) { Q.gxxl.ly = 0; readxml("lyb_dq", "", "", "", ""); return; }
    if (Q.gxxl.ml == 1) { Q.gxxl.ml = 0; readxml("ml_dq", "", "", "", ""); return; }
    if (Q.gxxl.wj != 0) { if (Q.gxxl.wj != Q.mlbh) { Q.gxxl.wj = 0; return; } Q.gxxl.wj = 0; _m.dqwj(Q.mlbh); return; }
}

function f_check(wjm) {
    var s1 = fkzm(wjm); if (s1 == "") return "文件名须带扩展名";
    if (s1.length > 10) return "文件扩展名不能超过１０个字符";
    var ar = ["%", "&", "#", "*", "..", "+", "\\", "://", "\'", ",", "<", ">", "?", "\""];
    for (i = 0; i <= ar.length - 1; i++) { if (wjm.indexOf(ar[i]) >= 0) { return "文件名中不能含有 " + ar[i]; } }
    if (wjm.length > 100) return "文件名不能超过100个字符"; return "";
}

function fkzm(rs) { var n1 = rs.lastIndexOf("."); if (n1 < 0) return ""; return rs.substr(n1 + 1).toLowerCase(); }
	
function onlineuser() {
	_C.kq(_CK.zxlb);}

function downuser() {
	_C.kq(_CK.xzlb);}
	
function loginsucc() {
	art.dialog.open('user/ck/D_glts.php', {title: '登录成功！'});}
	
function loginpermissions() {
	art.dialog.open('user/ck/D_wgts2.htm', {title: '错误！'});}

function bjlyd() {
	art.dialog.data('test', document.getElementById('nr').value);
	art.dialog.open('user/ck/D_bjlyd.php',{width: 420, height: 490});}
	
function lyedit(id) {
	if (!Q.a ) { alert(C.lyedit); return; };  art.dialog.open('user/ck/D_bjly.php?id=' + id , {width: 269, height: 180});} 
	
