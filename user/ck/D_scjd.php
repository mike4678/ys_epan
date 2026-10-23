<html>
<head>
    <title></title>
    <style type="text/css">
        body    {background-color:#FFFFE6;font-size:9pt;border:0px;padding:10px;margin:0;font-family: 宋体, Century Gothic, Arial, Helvetica;}
        p	    {margin:0px;line-height:150%;}
        .ProgressBar {position: relative;width: 340px;border: 1px solid green;padding: 1px;}
        .ProgressBar div {display: block;position: relative;background: green;color: #333333;height: 20px;line-height: 20px;}
        .ProgressBar div span {position: absolute;width: 340px;text-align: center;font-weight: bold;}
    </style>
    <script language="javascript" type="text/javascript">
        var erjs = 0;var erjl = "";
        var docu1 = document.domain.split(".")[0];
        document.oncontextmenu = function() { if (docu1 != "y") self.event.returnValue = false; } //禁右
        document.domain = qdhost();
        var ckid = document.URL.substr(document.URL.lastIndexOf("/") + 1).split(".")[0].split("-")[0];
        window.onload = function() { csdy(); }
        var ajurl = "";
        
        //上传完成进入
        function okcl(r1) { setTimeout("xzts('" + r1 + "');", 200); $("D_wj_2").firstChild.firstChild.innerHTML = "100%"; $("D_wj_2").firstChild.style.width = "100%"; pl(); }
        function xzts(r1) {
            var s1 = r1;
            if (s1.length < 4) { alert("上传返回信息有误"); _fup.stop(6); return false; }
            s1 = s1.substr(3);
            _fup.jdgb();
            if (!parent.Qcxzt) {
                parent.Q.gxxl.xzwj = s1;
            } else {
                parent.readxml("dqfile", s1.split(",")[0], s1.split(",")[1], "", "");
            }
            _fup.upend();
        }
//.net 版本接收
        function jsxx(rmsg) {
            //bfb & "," & sysj & "," & speed & "," & fdq & "," & fdx & "," & errbh & "," & ljpd
            if (parent._CK.scjd.ck.style.display == "none") return false;
            var a1 = rmsg.split(",");
            if (a1.length < 6) return true;
            if (a1.length >= 10) { if (a1[9] != "") { alert(a1[9]); } };
            if (a1[5] != "") { _fup.stop(a1[5]); return; }
            if (a1.length >= 7) {
                if (a1[6] == "0") {
                    if (a1[0] == "100") {
                        _fup.stop("9"); alert("请刷新空间查看文件是否上传成功(此种情况只在网络不稳定时有可能发生)");return;
                    } else {
                        erjs += 1;
                        if (erjs > 5) {
                            _fup.stop("9");
                            alert("抱歉，上传没能完成，请重新上传文件");
                        }
                    }
                }
            }
       
            if (a1[0] == "100") return false;
            $("D_wj_2").firstChild.style.width = a1[0] + "%";
            $("D_wj_2").firstChild.firstChild.innerHTML = a1[0] + "%";
            $("D_wj_3").innerHTML = "剩余时间 " + a1[1] + " 速度:" + a1[2] + "K/秒 已完成:" + a1[3] + " 共:" + a1[4];
            jcsyl(a1[4]);
            return true;
        }        

        function jcsyl(rz) {
            var s1 = rz;
            if (s1.length >= 2) {
                var s2 = s1.substr(s1.length - 1).toUpperCase(); var s3 = s1.substr(0, s1.length - 1);
                if (!isNaN(s3)) {
                    var n1 = s3 * 1; if (s2 == "M") n1 = s3 * 1024;;
                    var s4 = parent.$("kjz2").innerHTML;
                    if (!isNaN(s4)) {
                        if (n1 > s4 * 1024) { _fup.stop(1); }
                    }
                }
            }
        }
        
        function $(rid) { return document.getElementById(rid); }
        function $p(rid) { return parent.document.getElementById(rid) }
        function qdhost() { var x = document.domain.split("."); if (x.length >= 3) return x[1] + "." + x[2]; if (x.length == 2) return x[0] + "." + x[1]; return document.domain; }

        function pl() {
            if ($("idc1").checked == false) return;
            var isIE = false;
            var ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf("msie") >= 0 || ua.indexOf("trident") > 0) { isIE = true; }
            if (isIE) {
                //if ($("ysaudio").innerHTML == "") $("ysaudio").innerHTML = "<OBJECT   ID='Player' CLASSID='CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6'><PARAM   NAME='invokeURLs'   VALUE='False'></OBJECT>";
                //$("Player").URL = "UP.wav"

                $("ysaudio_ie").innerHTML = "<bgsound  loop='false'   autostart='false'  id='bgss' src='UP.wav'>";
            } else {
                if ($("ysaudio").innerHTML == "") $("ysaudio").innerHTML = "<audio id='snd' src='UP.wav'>unsupport</audio>";
                $("snd").play();
            }
        }
        //----
        _fup = { pjs: 0, mcs: false, mlbh: "", fl: "", upxh: 0, scpz: "", scpz1: "",
            wjmc: "",
            fwjm: function(rs) { var n1 = rs.lastIndexOf("\\"); if (n1 < 0) return rs; return rs.substr(n1 + 1); },
            fkzm: function(rs) { var n1 = rs.lastIndexOf("."); if (n1 < 0) return ""; return rs.substr(n1 + 1).toLowerCase(); },
            gzzt: function(rpd) { var s1 = (rpd) ? "true" : ""; $p("scmore").disabled = s1; $p("filesc2").disabled = s1; },
            fjc: function () {
                if ($p("scbt").value.length > 50) { alert("备注文字不能超过50字符，当前长度为:" + $p("scbt").value.length); return false; }
                var ts1 = parent._zml.zfjc($p("scbt").value);
                if (ts1 != "") { alert("备注文字不能含有字符:" + ts1); return false; }
                var js = 0;
                for (var i = 0; i <= (($p("scmore").checked) ? 5 : 0); i++) {
                    var uphh = eval("parent.upf_" + i);
                    if (!uphh) { alert("请重新进入空间！"); return false; };
                    var s1 = uphh.document.forms[0].f.value;
                    if (s1 != "") {
                        if (parent.f_check(this.fwjm(s1)) != "") { alert(parent.f_check(this.fwjm(s1))); return false; };
                        if ("exe".indexOf(this.fkzm(s1)) >= 0 && !parent.Q.a) {
                            var m = new parent.ML(""); if (m.mmcd == 0) {
                                alert("访客不能在未设置权限的目录里上传EXE格式文件！\n\n建议访客把文件压缩后再上传.(管理员在管理区登陆后不受此限制)"); return false;
                            }
                        };
                        js += 1;
                    }
                }; if (js == 0) { alert("您没有选择文件!"); return false; }; return true;
            },
            ks: function() {
                if (parent.Q.mlbh < 0) { alert("需先选择一个目录"); return false; };
                var M = new parent.ML("");
                if (!M.xkadd(true)) return;
                if (!this.fjc()) return;
                this.mlbh = parent.Q.mlbh;
                this.fl = parent.Q.fl_mc();
                if (!M.jl()) { alert("请刷新目录再次上传(1)"); return; };

                //
                this.scpz = M.jl().scpz;
                //////////////////////////////////////////////
                /////////
                //////////////////////////////////////////////
                
                if (this.scpz == "" || parent.Qwh!="") {
                    parent.readxml("dqscpz", this.mlbh); return;
                    return;
                };
                this.ks1();
            },
            ks1: function() {
                var o1 = parent._m.f(this.mlbh);
                this.scpz = o1.scpz;
                if (this.scpz == "") { alert("未能进行上传,请重新进入空间再次上传"); return; }
                this.gzzt(true); this.upxh = 0;
                this.up();
            },

            up: function() {
                var f = eval("parent.upf_" + this.upxh).document.forms[0];
                if (f.f.value == "") { this.upend(); return; }
                this.wjmc = this.fwjm(f.f.value);
                var sbt1 = parent.zfaq($p("scbt").value);
                sbt1 = parent.vreplace(sbt1, "&", "*");
                this.pjs = this.pjs + 1; if (this.pjs > 99) this.pjs = 0;
                this.scpz1 = ((this.pjs < 10) ? "0" + this.pjs : this.pjs) + this.scpz + this.upxh;
                f.action = "http://" + parent.Q.f_s + "/?p=" + this.scpz1 + "&ly=" + parent.Q.f_ly + "&m=" + parent.Q.fmax + "&f=" + bm(this.wjmc) + "&l=" + bm(this.fl) + "&t=" + bm(sbt1);
                //alert(f.action);
                this.jdks();
                var gb11 = eval("parent.upf_" + this.upxh).$("gbhh"); if (gb11) { gb11.innerHTML = "" };
                f.submit();
            },
            upend: function() { if (this.upxh < (($p("scmore").checked) ? 5 : 0)) { this.upxh += 1; this.up(); } else { this.upjs(); } },
            upjs: function() { this.gzzt(false); if ($p("scmore").checked) { $p("scmore").checked = false; parent.fmore(); } },
            errjs: function(rlx) {
                this.upjs();
                var s1 = "上传任务未完成，原因:\n\n";
                if (rlx.length > 1) rlx = rlx.substr(0, 1);
                if (rlx == 1) { alert(s1 + "所选文件大小超过了空间剩余大小,不能进行上传！"); return; }
                if (rlx == 2) { alert(s1 + "单个文件大小不能超出限定:" + parent.Q.fmax + " MB\n\n注:空间级别不同，单个文件大小限定不同，详细请看:永硕E盘产品介绍http://www.ys168.com/list_cp.aspx"); return; }
                if (rlx == 6) { alert(s1 + "报歉！上传中出现问题，请重新登陆空间再进行上传操作。"); return; }
                if (rlx == 9) { return; }
            },
            stop: function(rs) { eval("parent.upf_" + this.upxh).location.href = "http://" + parent.Q.f_ly + "/upfile/up.htm?" + this.upxh + rs + "92"; },
            jdgb: function() { parent._CK.scjd.ck.style.display = "none"; $("D_wj_if").src = "about:blank"; }
        }
        _fup.jdks = function() {
            $("D_wj_1").innerHTML = "文件:" + this.wjmc;
            $("D_wj_2").firstChild.style.width = "0%";
            $("D_wj_2").firstChild.firstChild.innerHTML = "0%";
            $("D_wj_3").innerHTML = "正在上传...";
            $("D_wj_if").src = "http://" + parent.Q.f_s + "/up/upa.htm";
            parent._CK.scjd.ck.style.display = "block";
        }
        function bm(r) { return encodeURIComponent(escape(r)); }
        function csdy() {
            erjs = 0;
            if (parent.Q.fk_upload == 0) { alert("空间主人已关闭访客上传权限"); window.close(); return; }
            if ($p("filesc2").disabled) {alert("上传中请勿刷新本窗口，否则将看不到上传进度！"); return ; }
            parent._C.bt(ckid, (parent.dxken) ? "Uploading files" : "正在上传文件");
            parent._CK.scjd.gb = function() {
                if (confirm('是否要停止上传？')) { _fup.stop(9); }
            }
        
            _fup.ks();
        }
        
    </script>
</head>
<body onmousedown ="parent._C.zd(ckid);">
        <iframe name="D_wj_if" id="D_wj_if" src='about:blank' style="display:none;"></iframe>
        <div id="D_wj_1" style="line-height:30px;overflow:hidden;height:30px;"></div>
        <div id="D_wj_2" class="ProgressBar"><div style="width:55%;"><span></span></div></div>
        <div id="D_wj_3" style="line-height:25px;overflow:hidden;">正在上传...</div>
        <div style="text-align:right;margin-top:10px;">
            <label for="idc1">完成后声音提醒</label><input type="checkbox" id="idc1" checked ="checked"/>
            [<a href="javascript:" onclick="parent._CK.scjd.gb();">取消上传</a>]</div>
        <div id ="ysaudio" style="display:none;"></div>
        <div id ="ysaudio_ie" style="display:none;"></div>
    <div style="margin-top:10px;color:green;">请严格遵守相关法律法规，禁止上传有关反动、色情、暴力、侵权等违规内容。严格遵守保密法律法规，严禁在互联网上存储、处理、传输、发布涉密信息。</div>
</body>
</html>
