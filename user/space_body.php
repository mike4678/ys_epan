<?php $_name = isset($_GET['name']) ? $_GET['name'] : ''; //原始值；SQL转义由System方法内部负责 ?>
<body scroll="no" style="height:100%;">
<div id="k_page" class="k-page">

  <!-- ===== 顶部 ===== -->
  <header id="idtop" class="k-top tdtop">
    <nav class="k-nav">
      <a href="/" target="_top" class="k-nav-item"><?php echo $T['nav_home']; ?></a>
      <a href="/ht/" target="_top" class="k-nav-item"><?php echo $T['nav_panel']; ?></a>
      <a href="/kf.php" target="_top" class="k-nav-item"><?php echo $T['nav_support']; ?></a>
      <a href="/help/" target="_top" class="k-nav-item"><?php echo $T['nav_help']; ?></a>
    </nav>
    <div class="k-head">
      <h1 id="kjbt"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
      <div id="sylj">
        <?php
        $link = $db -> LoadingLink($_name);
        if($link != NULL) {
          foreach($link as $list=>$things){
            echo "<img border=0 src='/user/images/point.gif' width=12 height=8><a href='".htmlspecialchars($things[2],ENT_QUOTES,'UTF-8')."' target=_blank>".htmlspecialchars($things[1],ENT_QUOTES,'UTF-8')."</a>&nbsp;";
          }
        }
        ?>
      </div>
      <!-- 空间用量条 -->
      <div class="k-spwrap">
        <div class="k-sp-info">
          <?php
          $totalM = (float)$db -> UserSpaceSize($_name);
          $_usedR = $db -> fetch_array($db -> query('select COALESCE(sum(file_size),0)/1048576 as u from epan_files where userid = (select userid from epan_users where username = "'.$db->escape($_name).'")'));
          $usedM = (isset($_usedR['u']) && is_numeric($_usedR['u'])) ? round((float)$_usedR['u'],1) : 0;
          $remainM = max(0, round($totalM - $usedM,1));
          ?>
          <span><?php echo $T['total']; ?> <label id="kjz1"><?php echo (int)$totalM; ?></label>M</span>
          <span><?php echo $T['left']; ?> <label id="kjz2"><?php echo $remainM; ?></label>M</span>
          <span><?php echo $T['online']; ?> <label id="zxrsts" style="color:yellow;">0</label><?php echo $T['ren']; ?></span>
        </div>
        <div class="k-sp-track"><div id="k_spbar" class="k-sp-fill"></div></div>
      </div>
    </div>
  </header>

  <!-- ===== 主体：左菜单 + 右目录 ===== -->
  <div class="k-body">

    <!-- 左侧 -->
    <aside class="k-left">
      <div id="ysmenu" class="k-menu">
        <div class="ysm1" id="mm1" style="display:block;margin-left:2px;" onmouseover="mlq_ov(this);" onmouseout="mlq_ou(this);" onclick="qhzml(1)"><?php echo $T['menu_msg']; ?></div>
        <div class="ysm0" id="mm2" style="display:block;" onmouseover="mlq_ov(this);" onmouseout="mlq_ou(this);" onclick="qhzml(2)"><?php echo $T['menu_folder']; ?></div>
        <div class="ysm0" id="mm3" style="display:block;" onmouseover="mlq_ov(this);" onmouseout="mlq_ou(this);" onclick="qhzml(3)"><?php echo $T['menu_file']; ?></div>
        <div class="ysm0" id="mm4" style="display:block;" onmouseover="mlq_ov(this);" onmouseout="mlq_ou(this);" onclick="qhzml(4)"><?php echo $T['menu_manage']; ?></div>
      </div>

      <!-- 操作区 -->
      <div id="idczq" class="k-opwrap">
        <div id="ysmenu1" class="k-oparea">

          <div id='ml_1' style="display:block;padding-top:8px;">
            <div><?php echo $T['ml1_name']; ?><input type="text" id="xm" size="8" class="TX3">
              <?php echo $T['ml1_public']; ?><input type="checkbox" disabled="disabled" id="bdgkpdlyb" checked >
              <img border="0" src="user/images/00zxz1.gif" width="15" height="15" onclick="changetp('z');" style="cursor:pointer;">
              <img border="0" name="t_bq" src="user/face/f1.gif" width="15" height="15">
              <img border="0" src="user/images/00zxy1.gif" width="15" height="15" onclick="changetp('y');" style="cursor:pointer;">
              <input type="hidden" id="bdbq" value="1" />
            </div>
            <div><textarea id="nr"></textarea></div>
            <div style="text-align:center;">
              <input type="button" value="<?php echo $T['ml1_submit']; ?>" onclick="checkly('');" class='BT2' id="buly" />
            </div>
          </div>

          <div id='ml_2' style="display:none;padding-top:8px;">
            <div><label class="la1"><?php echo $T['ml2_title']; ?></label><input type="text" id="bdbt" style="width:148px;" class="tx3" onkeypress="if (window.event.keyCode==13){$('bdsm').focus();return false;}"/></div>
            <div><label class="la1"><?php echo $T['ml2_sign']; ?></label><input type="text" id="bdsm" style="width:148px;" class="tx3" onkeypress="if (window.event.keyCode==13){$('bdmlmm').focus();return false;}"/></div>
            <div><label class="la1"><?php echo $T['ml2_pass']; ?></label><input type="password" id="bdmlmm" class="tx3" style="width:85px;" onkeypress="if (window.event.keyCode==13){$('sutjbt').focus();return false;}"/>
              [<a href="user/note/mlszsm.htm" target="_blank"><?php echo $T['ml2_guide']; ?></a>]
            </div>
            <div style="text-align:center;margin-top:6px;"><input type="button" id="sutjbt" value="<?php echo $T['ml2_add']; ?>" onclick="_m.tj();" class='BT2'/></div>
          </div>

          <div id='ml_3' style="display:none;padding-top:8px;">
            <div style="border-bottom:1px dashed black;text-align:center;margin-bottom:10px;">
              <input type="radio" id="wjlx1" name="r1" checked="checked" onclick="changelx(1);" style="cursor:pointer;"/><label for="wjlx1" style="cursor:pointer;"><?php echo $T['ml3_up']; ?></label>
              <input type="radio" id="wjlx2" name="r1" onclick="changelx(2)" style="cursor:pointer;"/><label for="wjlx2" style="cursor:pointer;"><?php echo $T['ml3_link']; ?></label>
              <input type="radio" id="wjlx3" name="r1" onclick="changelx(3)" style="cursor:pointer;"/><label for="wjlx3" style="cursor:pointer;"><?php echo $T['ml3_sub']; ?></label>
            </div>
            <div id="idwj1" style="display:block;">
              <div id="rqfile1" style="margin-left:16px;">
                <iframe style="display:block;" name="upf_0" id="upf_0" src="upfile/up.htm?005092" height="24" width="230" scrolling=no frameborder="0"></iframe>
              </div>
              <div id="rqfile" style="margin-left:16px;display:none;"></div>
              <div><label><?php echo $T['ml3_note']; ?></label><input type="text" id="scbt" style="width:180px;" class="tx3" onkeypress="if (window.event.keyCode==13){filesc2.focus();return false;}"/></div>
              <div id="filesczt1" style="display:block;">
                <div style="margin-top:8px;margin-bottom:8px;">
                  <input type="checkbox" id="scmore" value="ON" onclick="fmore();"><label for="scmore" style="cursor:pointer;"><?php echo $T['ml3_multi']; ?></label>
                  <input type="button" value="<?php echo $T['ml3_start']; ?>" class="bt2" id="filesc2" onclick="_C.kq(_CK.scjd);"/>
                </div>
              </div>
              <div id="filesczt2" style="display:none;"></div>
            </div>
            <div id="idwj2" style="display:none;">
              <div><?php echo $T['ml3_linktitle']; ?><input type=text id="scbt2" class="tx3" size=26></div>
              <div><?php echo $T['ml3_linkurl']; ?><input type=text id="teljdz" class=tx3 size=26></div>
              <div style="text-align:center;"><input type="button" value="<?php echo $T['ml3_addlink']; ?>" onclick="checkfu();" class='BT2' id="bulj"/></div>
            </div>
            <div id="idwj3" style="display:none;">
              <div><?php echo $T['ml3_subname']; ?><input type=text id="tezml" class=tx3 size=20 style="background-color:#FFEBCD" onkeypress="if (window.event.keyCode==13){$('buzml').click();return false;}"/></div>
              <div style="text-align:center;"><input type="button" value="<?php echo $T['ml3_addsub']; ?>" onclick="_zml.cj();" class='BT2' id="buzml"></div>
            </div>
          </div>

          <div id='ml_4' style="display:none;padding-top:15px;">
            <div id='glyq1' style="display:block;">
              <div style="text-align:center;"><?php echo $T['ml4_pass']; ?><input type="password" id="bdglymm" onkeypress="if (window.event.keyCode==13){sutjgl.click();}" class="tx3" style="height:18px;width:70px">
                <input type=button value="<?php echo $T['ml4_login']; ?>" onclick="checkgl('');" class='BT2' id="sutjgl"/></div>
              <div><font color='#008000'><?php echo $T['ml4_note']; ?></font></div>
            </div>
            <div id='glyq2' style="display:none;">
              <div><?php echo $T['ml4_level']; ?><span id="index_L_kjjb"><?php echo $disk; ?></span></div>
              <div><?php echo $T['ml4_expire']; ?><span id="index_L_fwdq"><?php echo $time; ?><a class=actuatorn href='ht/index.php?method=kjsj' target=_blank><?php echo $T['ml4_upgrade']; ?></a></span></div>
              <div style="text-align:center;">
                <input type="button" value="<?php echo $T['ml4_settings']; ?>" onclick="_C.kq(_CK.bjym);" style="color:Blue;"/>
                <input type=button value="<?php echo $T['ml4_exit']; ?>" id='glytc' class='bt2' onclick="fglytc();"></div>
            </div>
            <div>
              <div>
                <img src="user/images/wh.gif" width="14" height="14"/><a class="jaml" href="javascript:" onclick="kq_help();"><?php echo $T['ml4_help']; ?></a>
                <img src="user/images/go.gif" width="16" height="16"/><a href="ht/login.php" class="jaml" target="_blank"><?php echo $T['ml4_enter']; ?></a>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- 留言列表 -->
      <div id="lynr" class="k-msglist lynrc"><label><?php echo $T['reading']; ?></label></div>
    </aside>

    <!-- 右侧目录区 -->
    <section class="k-right">
      <div class="k-filepanel">
        <div class="k-filehead ta_wjq">
          <span><?php echo $T['dirlist']; ?></span>
          <a href="javascript:" class="k-sortbtn" onclick="sortMenuList()"><?php echo $T['sort']; ?></a>
        </div>
        <div id="dzx" class="k-filetree">
          <div id="zdy_wz0" style="display:none; float:right;">
            <div id='idzdy' class='yszdyq' style="display:none;"></div>
            <div id='idzdy1' class='yszdyq' style="display:none;"></div>
          </div>
          <div id="mainMenu"><ul id="menuList"><li style="margin:3px;BORDER-BOTTOM:0;"><?php echo $T['reading']; ?></li></ul></div>
          <div id="zdy_bz"></div>
          <p style="text-align:right;" id="sxts">
            <a href='javascript:' style="font:9pt;padding-left:14px;background:url(user/images/refresh.gif) no-repeat 0em -0.1em;" onclick="if (confirm('<?php echo $T['refresh_confirm']; ?>'))_m.cxdq();" class='a1'><?php echo $T['refresh']; ?></a>
          </p>
          <div style="margin-left:10px;" id="mfkjtz"></div>
        </div>
      </div>

      <!-- 底部状态栏 -->
      <footer id="idsyjy" class="k-foot">
        <div class="k-footline">
          <span id="index_gbtz"></span>
          <span id="emyz"></span>
          <span id="index_dqtz"></span>
          <label id="laczts"><?php echo $jy; ?></label>
        </div>
        <div class="k-footbar">
          <span style='font-size: 9pt'><a href='eform/' target='_blank'><font color='#000000'><?php echo $T['report']; ?></font></a></span>
          <span style='font-size: 9pt'><a href='help/' target='_blank'><font color='#000000'><?php echo $T['guide']; ?></font></a></span>
          <input type="button" value="<?php echo $T['online_list']; ?>" class='bt2' onclick="javascript:onlineuser();"/>
          <?php $_isAdm=false; if(isset($_SESSION['username_epan'])&&$_SESSION['username_epan']!=''){ $_ar=$db->fetch_array($db->query("select is_Administrator from epan_userdata where user='".$db->escape($_SESSION['username_epan'])."'")); $_isAdm=($_ar && (int)$_ar['is_Administrator']==1); } $__dl=(isset($_SESSION['username_epan'])&&$_SESSION['username_epan']==$_name)||isset($_SESSION['gly_'.$_name])||$_isAdm; ?>
          <input type="button" id="buxz" value="<?php echo $T['dl_record']; ?>" onclick="javascript:downuser();" class='bt2'<?php echo $__dl ? '' : ' disabled="disabled"'; ?>/>
          <input type="button" class="bt2" id="buexit" onclick="fglytc();" value="<?php echo $T['exit_sys']; ?>"<?php echo $__dl ? '' : ' disabled="disabled"'; ?>/>
          <iframe name="frxcx" id="frxcx" src="user/Ccenter.php?name=<?php echo urlencode($_GET['name']); ?>" style="display:none;"></iframe>
          <iframe name="ys_ck" src="javascript:" style="display:none;"></iframe>
        </div>
      </footer>
    </section>

  </div>
</div>
<div id="ctrq"></div>
</body>
</html>
