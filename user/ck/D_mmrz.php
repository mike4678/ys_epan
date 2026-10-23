<html>
<head>
    <title></title>
    <style type="text/css">
        body    {background-color:#FFFFE6;font-size:9pt;border:0px;padding:10px;margin:0;font-family: 宋体, Century Gothic, Arial, Helvetica;}
         p	    {margin:0px;line-height:150%;}
         .tb {width:7px;height:15px;font-size:0px;float:left;background-color:Red;margin-top:2px;margin-right:3px;}
         .ollb {margin:1px;padding:1px;list-style-type:none;margin-left:10px;padding-left:10px;line-height:20px;}
    </style>
    <script language="javascript" type="text/javascript">
        ckid = "D_mmrz";
        function onloads() { csdy(); }



        function csdy() {
            parent._C.bt(ckid, (parent.dxken) ? "Directory password authentication" : "目录密码认证");
            if (parent.dxken) {
                $i("p1", "after confirmation：");
                $i("p4", "give up confirmation：");
                $i("f0", "allow to upload");
                $i("f1", "allow to download");
                $i("f2", "allow to edit and delete");
                $i("f3", "allow to View the list");
                $i("f4", "allow to upload");
                $i("f5", "allow to download");
                $i("bt3", "password");
                $i("p2", "Log management zone are not restricted to this set");
                $("d_mm_b1").value = "Confirm";
                $("d_mm_b2").value = "Give up";
                $("b0").value = "Close";
            }
            ///////
            var sx = parent._rz.sx;
            for (var i = 0; i < 6; i++) {
                $("xxc" + i).previousSibling.style.backgroundColor = (sx.substr(i, 1) == "1") ? "green" : "red";
                $("xxc" + i).style.display = (sx.substr(i, 1) == "1") ? "none" : "inline-block";
                $("xxc" + i).innerHTML = (parent.dxken) ? "not&nbsp;" : "不";
            }
            var pd1 = (sx.substr(3, 3) == "000");
            $("D_mmf").style.display = pd1 ? "none" : "block";
            $("d_mm_b2").style.display = pd1 ? "none" : "inline-block";
            $("pass1").value = "";
            $("pass1").focus();
            $("d_mm_b1").disabled = "";
        }
        function zx() {
            if ($("pass1").value == "") { alert(parent.C.m8); return false; }
            if ($("pass1").value.length > 15) { alert(parent.C.m5); return false; }
            $("d_mm_b1").disabled = "true";
            parent.readxml("kqmmpd", parent._rz.mlbh, $("pass1").value, "", "");
            $("d_mm_b1").disabled = "true";
        }
         function fq() { parent.readxml("kqmmfq", parent._rz.mlbh, "", "", ""); }


         function zx_fh(rmlbh, rjg) {
             if (rmlbh != parent._rz.mlbh) { return false; }
             $("d_mm_b1").disabled = "";
             if (rjg == 1) { parent._rz.jl(rmlbh); parent._CK.mmrz.gb(); parent.mlkq_x(rmlbh); return; }
             alert(parent.C.m9); $("pass1").value = ""; $("pass1").focus();
         }
         function fq_fh(rmlbh) {
             if (rmlbh != parent._rz.mlbh) { return false; }
             parent._CK.mmrz.gb();
             if (parent._rz.jc(rmlbh)) { parent._rz.rzjl = parent.vreplace(parent._rz.rzjl, "[" + rmlbh + "]", ""); }
             parent.mlkq_x(rmlbh);
         }
                
        //fq: function() { readxml("kqmmfq", this.mlbh, "", "", ""); },
        function $(rid) { return document.getElementById(rid); }
        function $i(rid, rnr) { $(rid).innerHTML = rnr; }
    </script>
</head>
<body onmousedown ="parent._C.zd(ckid);" onLoad="onloads();">
                 <p style="font-weight:bold;" id="p1">通过密码验证后的权限：</p>
                <ol class="ollb">
                    <li><div class="tb"></div><label id="xxc1">不</label><label id="f0">可以上传文件</label></li>
                    <li><div class="tb"></div><label id="xxc2">不</label><label id="f1">可以下载文件</label></li>
                    <li><div class="tb"></div><label id="xxc0">不</label><label id="f2">可以编辑删除目录内文件</label></li>
                </ol>
                <div id="D_mmf">
                <p style="font-weight:bold;" id="p4">放弃密码验证后的权限：</p>
                    <ol class="ollb">
                    <li><div class="tb"></div><label id="xxc3">不</label><label id="f3">可以查看文件列表</label></li>
                    <li><div class="tb"></div><label id="xxc4">不</label><label id="f4">可以上传文件</label></li>
                    <li><div class="tb"></div><label id="xxc5">不</label><label id="f5">可以下载文件</label></li>
                </ol>
                </div>
    <hr />
    <p><label id="bt3">目录开启密码:</label><input type="password" size="15"  id="pass1" class="tx3" onKeyPress="if (window.event.keyCode==13){d_mm_b1.click();}" /></p>
       <p style="width:100%;color:#228B22;padding:1px;text-align:center;border:1px dashed #228B22;line-height:120%;" id="p2">提示:在空间管理区登录后不受此权限限制。</p>
    <p style="text-align:center;margin:10px;">
        <input type="button" id="d_mm_b1" value="开始认证" onclick ="zx();" />
        <input type="button" id="d_mm_b2" style="display:inline-block;" value="放弃认证" onclick ="fq();" />
        <input type="button" id="b0" value="关闭" onclick ="parent._CK.mmrz.gb();" />
    </p>
  
</body>
</html>
