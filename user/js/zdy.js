var Yimg = null;
var zml = new Array();
var Qtplb = new Array();
var Qtpbh = 0;
var Qtpob = null;
var Qtpma = 166;
var elady_tp = new Array();
var adNumvii = 0;
var Qti = 0;
var Qtpzt = 0;
var Qhome = 0;
var iicjpd = false;
//总控室
//onload = function() { zdqdq(); }


function $p(rid) { return document.getElementById(rid) }

function zdqdq() {



    Yimg = null;
    zml = new Array();
    Qtplb = new Array();
    Qtpbh = 0;
    Qtpob = null;
    elady_tp = new Array();
    adNumvii = 0;
    Qhome = 0;
    var P_sp = 0, P_tp = 0;

    var mlbh = Qzdqbh;
    var menu = $p("ZMm_" + mlbh);



    if (menu.innerHTML == "") {
        var o1 = _m.f(mlbh);if (o1) menu.innerHTML = o1.text;
//        j = findml(mlbh);
//        if (j != -1) { menu.innerHTML = myTree.branches[j].text }
//        else { $p("idzdy").innerHTML = "未查到数据！"; return; }
        
    }

    var js = 0;
    $p("idzdy").innerHTML = "";

    for (var z = 0; z < menu.childNodes.length; z++) {
        dfml = menu.childNodes[z];
        if (dfml.lastChild.className != "menu") {
            if (dfml.childNodes.length < 4) continue;
            if (dfml.childNodes[2].className != "yyck") continue;
            if (dfml.childNodes[2].innerHTML != "打开" && dfml.childNodes[2].innerHTML != "Open") continue;
            dho = fxwj(dfml.childNodes[0]).wjm.toUpperCase();
            if (dho != "HOME.TXT" && dho != "HOME.HTM") continue;
            dho1 = fxwj(dfml.childNodes[0]).bz
            if (Qhome == 1) continue;
            Qhome = 1;
            x7 = dho1.indexOf("*");
            xwidth = 500; xheight = 350;
            if (x7 > 0) {
                x8 = dho1.substr(0, x7);
                x9 = dho1.substr(x7 + 1);
                if ((x8 - 1) && (x9 - 1)) {
                    xwidth = x8; xheight = x9;
                    if (x8 < 50) xwidth = 50;
                    if (x8 > 800) xwidth = 800;
                    if (x9 < 50) xheight = 50;
                    if (x9 > 1000) xheight = 1000;
                }
            }
            xobj = $p("zdy_bz");
            xobj.innerHTML = "<br><font size=2 color=blue>&nbsp;&nbsp;抱歉，暂取消home.htm文件显示。删除home.htm文件后，此提示不再显示。</font>";
            //免费空间不再显示 home内容
            //var str_dm = location.search;
            //if (!dffpd) {
            //    xobj.innerHTML = "<br><font size=2 color=blue>&nbsp;&nbsp;抱歉！为有效防止空间挂马行为，免费空间不再支持home.htm文件，空间管理员删除自定义目录内的home.htm 文件后，此文字不再显示。</font>";
            //} else if (str_dm.indexOf("dm=no") >= 0) {
            //    xobj.innerHTML = "<br><font size=2 color=blue>&nbsp;&nbsp;禁止home.htm文件显示。</font>";
            //} else {
            //    //xobj.innerHTML = "<Iframe name='xfx' src='" + dfml.childNodes[1].href + "' width='" + xwidth + "' height='" + xheight + "' scrolling='no' frameborder='0'></iframe>"
            //    if (iicjpd == false) {
            //        iicjpd = true;
            //        var ir2; if (isIE) { ir2 = document.createElement("<iframe name='xfx' frameborder='0'>"); } else { ir2 = document.createElement('iframe'); ir2.name = "xfx"; }
            //        ir2.id = "xfx"; ir2.style.cssText = "width:" + xwidth + ";height:" + xheight + ";border:0;";
                    
            //        ir2.scrolling = "no"; xobj.appendChild(ir2);

            //    }
            //    xobj.childNodes[0].src = dfml.childNodes[1].href;


            //}
            continue;

        }

        var y = dfml.lastChild.childNodes.length;
        if (y == 0) continue;
        js += 1;
        //if (js > 10) return;
        xmmc = dfml.childNodes[1].innerHTML;
        Fcr(xmmc, js);
        xob = $p("zdyBo_" + js);
        var opd = dfml.lastChild.childNodes[0];


        if (opd.childNodes[0].id.substr(0, 1) == "W") {
            var _fx = fxwj(opd.childNodes[0]);

            if (_fx.kqzf == "播放" || _fx.kqzf == "Play") {
                var gdz = 45;
                if (_fx.bz == "*") { gdz = 140 }
                if (_fx.kzm == "swf") gdz = 120;
                xob.innerHTML = "<Iframe name='fff' src='" + Qzydz + "/ck/show_sp.aspx?wdz=" + _fx.dz + "' width='100%' height='" + gdz + "' scrolling='no' frameborder='0'></iframe>"
                continue;
            }

            if (_fx.kqzf == "查看" || _fx.kqzf == "View") {

                dimtp = new Array();
                dimjs = 0;

                for (var i = 0; i < dfml.lastChild.childNodes.length; i++) {
                    if (dfml.lastChild.childNodes[i].childNodes[0].id.substr(0, 1) == "W") {
                        var fx2 = fxwj(dfml.lastChild.childNodes[i].childNodes[0]);
                        if (fx2.kqzf == "查看" || fx2.kqzf == "View") { dimjs += 1; dimtp[dimjs - 1] = fx2.dz; }
                    }
                }

                if (dimtp.length == 1 || P_tp == 1) {
                    xob.innerHTML = "<img border='0' src='" + dimtp[0] + "' width='" + Qtpma + "'>";

                } else {
                    if (P_tp == 1) continue; P_tp = 1;
                    elady_tp = dimtp;
                    xob.innerHTML = "<img title='单击暂停' style='cursor:hand;' onclick='tpkz();' style='FILTER: revealTrans(duration=2,transition=20)' src='" + elady_tp[0] + "' border=0 name='elady_tprotator1' id='elady_tprotator1' width='" + Qtpma + "' style='display:none;height:60'>";
                    tp_cc();
                }
                continue;
            }
        }
        dnr = "<ul>"
        for (var u = 0; u < dfml.lastChild.childNodes.length; u++) {
            dxx = dfml.lastChild.childNodes[u];
            if (dxx.childNodes[0].id.split("_")[0] == "t") {
                dnr += "<li><img border=0 src='" + Qzydz + "/tp/wjlx2/wj.gif' width=16 height=16>";
                dnr += "<font color=blue>" + dxx.childNodes[1].innerHTML + "</font>";
                dnr += dxx.childNodes[2].innerHTML + "</li>";
            }
            if (dxx.childNodes[0].id.split("_")[0] == "u") {
                dnr += "<li><img border=0 src='" + Qzydz + "/tp/wjlx2/url.gif' width=16 height=16>";
                dnr += "<a href='" + dxx.childNodes[1].href + "' target='_blank'>" + dxx.childNodes[1].innerHTML + "</a></li>";
            }
        }
        dnr += "</ul>";
        xob.innerHTML = dnr;
    }
    if (js == 0) { $p("idzdy").innerHTML = "<div style='color:blue;padding:3px 3px 3px 3px;'>请在自定义目录下增加子目录，并在子目录中增加数据，每个子目录对应一个栏目。</div>"; }
    return;
}

function Fcr(x1, x2) {
    $p("idzdy").innerHTML += "<div class='z_bt'>" + x1 + "</div>";
    $p("idzdy").innerHTML += "<div class='z_bo' id='zdyBo_" + x2 + "'></div>";
}
function dqq() {
    if (document.all) { if (Yimg.readyState != "complete") return false; };
    h1 = parseInt(Yimg.height);
    w1 = parseInt(Yimg.width);
    x = (Qtpma * h1) / w1;
    $p("elady_tprotator1").style.height = Math.round(x);
    $p("elady_tprotator1").style.display = "block";
    if (Qti != 1) { Qti = 1; next_elady1(); }

}

function tp_cc() {
    Yimg = document.createElement("img");
    Yimg.style.display = "none";
    if (document.all) { myAttachEvent(Yimg, "readystatechange", dqq); } else { Yimg.onload = function() { if (Yimg.complete == true) { dqq(); } } }
    Yimg.src = elady_tp[0];
    Yimg.onload = function() { if (Yimg.complete == true) { dqq(); } };
}
function tpkz() { if (Qtpzt == 0) { Qtpzt = 1; $p("elady_tprotator1").title = '单击继续'; } else { Qtpzt = 0; $p("elady_tprotator1").title = '单击暂停'; } }
function next_elady1() {
    if (Qtpzt == 0) {
        if (adNumvii < elady_tp.length - 1) adNumvii++; else adNumvii = 0;
        if (document.all) {
            $p("elady_tprotator1").filters.revealTrans.Transition = Math.floor(Math.random() * 23);
            $p("elady_tprotator1").filters.revealTrans.apply();
            $p("elady_tprotator1").src = elady_tp[adNumvii];
            $p("elady_tprotator1").filters.revealTrans.play();

        } else {
            $p("elady_tprotator1").src = elady_tp[adNumvii];
        }

    }
    theTimer = setTimeout("next_elady1()", 5000);

}
var myAttachEvent = function(obj, evt, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(evt, fn, false);
    }
    else if (obj.attachEvent) {
        obj.attachEvent("on" + evt, fn);

    }
}

zdqdq();