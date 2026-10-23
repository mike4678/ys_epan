<?php
$uid = 3;
$sid = 5;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( stripos ($_POST['t1'],'</iframe>') != false || stripos ($_POST['t1'],'</script>') != false || stripos ($_POST['t3'],'<script>') != false || stripos ($_POST['t1'],'iframe') != false) {
				$db ->message('错误：系统不允许使用符号：iframe、script');
				} else {
					if($db ->SpaceUpdate($_SESSION['username_epan'] , 'title' , $_POST['t1']) != 0) {
					$db ->message('更新成功！');
					} else { $db ->message('更新失败！'); }
			}
	}

if($db -> LoginCheck() != 0){
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1">';
	if ($db -> SpaceSetting($_SESSION['username_epan'],'title') != NULL) {
		$title = '<input name="t1" type="text" id="t1"  style="width:500px;" value="'.htmlspecialchars($db -> SpaceSetting($_SESSION['username_epan'],'title'), ENT_QUOTES, 'UTF-8').'"/>';
		} else { $title = '<input name="t1" type="text" id="t1"  style="width:500px;"/>'; }
	} else {
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1" disabled="disabled"><font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font>';
	$title = '<input name="t1" type="text" id="t1" disabled="disabled" style="width:500px;" />';
	}

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$safeId = (int)$_GET['id'];
	if($db -> LoadingLink($_SESSION['username_epan'] , $safeId) == NULL) {
		$db ->message('查询异常！');
		} else {
		$link = $db -> LoadingLink($_SESSION['username_epan'] , $safeId);
			if((int)$link[0] == $safeId) {
				$edit_title = $link[1];
				$edit_link = $link[2];
				}
		}
}
?>
<style type="text/css">
    .xs{background:#E4F3FC; min-height:50px; width:450px;  font-size:9pt; padding:5 5 5 5; color:#000000;border:#C3DAEB solid;border-width:1px;word-break: break-all;}
</style>
<script language="javascript" type ="text/javascript">
    window.onload = function() {
        var ts = "";
        if (ts != "") { alert(ts); }
    }

    function check() {
        var ys = document.forms[0];
        if (checkzf(ys.ljmc.value) == 'F') { alert('显示名称不支持特殊字符的输入。'); ys.ljmc.focus(); return false; }
        if (checkzf(ys.ljdz.value) == 'F') { alert('链接地址不支持特殊字符的输入。'); ys.ljdz.focus(); return false; }
        if (ys.ljmc.value == '') { alert('显示名称不能为空。'); ys.ljmc.focus(); return false; }
        if (ys.ljdz.value == '') { alert('链接地址不能为空。'); ys.ljdz.focus(); return false; }
        if (ys.ljdz.value.length < 9) { alert('请填写正确完整的链接地址。地址必须是 http:// 或 https:// 或 ftp://  开头。'); ys.ljdz.focus(); return false; }
        if (ys.ljdz.value.indexOf('<') >= 0 || ys.ljdz.value.indexOf('>') >= 0) { alert('链接地址不支持特殊字符（< >）输入。'); ys.ljdz.focus(); return false; }
        var ysdz = ys.ljdz.value.toLowerCase();
        if (ysdz.substr(0, 7) != 'http://' && ysdz.substr(0, 8) != 'https://' && ysdz.substr(0, 6) != 'ftp://') { alert('请填写正确完整的链接地址。地址必须是 http:// 或 https:// 或 ftp://  开头。'); ys.ljdz.focus(); return false; }
        if (!confirm('请确认是否进行提交！')) return false;
    }

    function checkzf(nr) {
        var zf = "\\;,'"
        for (i = 0; i <= zf.length - 1; i++) {
            if (nr.indexOf(zf.substr(i, 1)) >= 0) { return "F" }
        }
        return "T"
    }
</script>
<div id="ysright">
            <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />首页链接-编辑</h1>
            <div class="ysdb2">
                <form name="ctl00" method="post" action="?method=ljxg" id="ctl00">
                    <table style="background:#fff; border:0px;">
                        <tr>
                            <td style="border:0px;">显示名称：<input name="ljmc" type="text" value="<?php echo htmlspecialchars($edit_title, ENT_QUOTES, 'UTF-8'); ?>" id="ljmc" style="width:450px;" /></td>
                        </tr>
                        <tr>
                            <td style="border:0px;">链接地址：<input name="ljdz" type="text" value="<?php echo htmlspecialchars($edit_link, ENT_QUOTES, 'UTF-8'); ?>" id="ljdz" style="width:450px;" /></td>
                        </tr>
                    </table>
                    <br />
                    <input type="submit" name="bu2" value=" 提 交 " onclick="return check();" id="bu2" />&nbsp;&nbsp;
                    <input type="submit" name="bu1" value=" 取 消 " id="bu1" />

                </form>
            </div>
            <ul class="yslb2">
                <li>链接地址必须是 http:// 或 https:// 或 ftp://  开头。</li>
                <li>为了加强空间安全管理，防止空间内挂马，空间不支持写入script代码和iframe。</li>
                <li>链接名称和链接地址不支持字符（ , ; ' \ ）。链接地址不支持字符（ < > ）</li>
                <li>如果需要显示图片，请在显示名称的框里输入：&lt;img src=&quot;图片地址&quot; /&gt; </li>
                <li>首页链接的位置如图所示：<br /><img alt="" src="/images/sylj.gif"  style="width: 399px; height: 164px" /></li>
            </ul>
        </div>
