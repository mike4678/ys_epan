<?php
$uid = 3;
$sid = 5;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if( stripos ($_POST['ljmc'],'</iframe>') != false || stripos ($_POST['ljmc'],'</script>') != false || stripos ($_POST['ljmc'],'<script>') != false || stripos ($_POST['ljmc'],'iframe') != false || stripos ($_POST['ljdz'],'</iframe>') != false || stripos ($_POST['ljdz'],'</script>') != false || stripos ($_POST['ljdz'],'<script>') != false || stripos ($_POST['ljdz'],'iframe') != false) {
				$db ->message('错误：系统不允许使用符号：iframe、script');
				} else {
					if($db -> LoginCheck() != 0) {
						if($db ->AddLink ($_POST['ljmc'],$_POST['ljdz'],$_SESSION['username_epan']) != 0) {
						$db ->message('更新成功！');
						} else { $db ->message('更新失败！'); }
					}
			}
	}

if($db -> LoginCheck() != 0){
	$bottom = '<td style="padding-left:100px;border:0px;">&nbsp;&nbsp;<input type="submit" name="bu2" value=" 添 加 " onclick="return check();" id="bu2" style="height:30px;" />';
	if($_GET['id'] != NULL) {
		if(!is_numeric($_GET['id'])) { $db->message('参数错误！'); }
		else if(!csrf_check(isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '')) { $db->message('CSRF 校验失败，请刷新页面后重试！'); }
		else if($db ->DelLink ($_SESSION['username_epan'] ,(int)$_GET['id'] ) == 1) {
		$db -> message('删除成功！');
			} else { $db -> message('删除失败！'); }
		}
	if($db -> LoadingLink($_SESSION['username_epan'] ) == NULL) {
		$Link_list = '尚无内容';
		} else {
		$link = $db -> LoadingLink($_SESSION['username_epan'] );
		$Link_list = '';
		foreach($link as $list=>$things){
			$url_safe = htmlspecialchars($things[2], ENT_QUOTES, 'UTF-8');
			$Link_list.="显示名称：<font color=blue>".$things[1]."</font><br />链接地址：<a href='".$url_safe."' target='_blank'><font color=blue>".$url_safe."</font></a><br /><input type='button' onclick ='del(".$things[0].")' value ='删除' />&nbsp;<input type='button' onclick ='edit(".$things[0].")' value ='编辑' /><hr>";
				}
		}
	} else {
	$bottom = '<td style="padding-left:100px;border:0px;">&nbsp;&nbsp;<input type="submit" name="bu2" value=" 添 加 " onclick="return check();" id="bu2" style="height:30px;" disabled="disabled">';
	$Link_list = '尚无内容';
	}
?>
<style type="text/css">
    .xs{background:#E4F3FC; min-height:50px; width:450px;  font-size:9pt; padding:5 5 5 5; color:#000000;border:#C3DAEB solid;border-width:1px;word-break: break-all;}
</style>
<script language="javascript" type ="text/javascript">
    var csrfToken = "<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>";
    window.onload = function() {
        var ts = "";
        if (ts != "") { alert(ts); }
    }

    function del(bh) {
        if (!confirm('请确认是否进行删除')) return;
		document.forms[0].submit();
		window.location = "index.php?method=lj&id=" + bh + "&csrf_token=" + encodeURIComponent(csrfToken);
    }

    function edit(bh) {
        if (!confirm('请确认是否进行编辑')) return;
        window.location = "index.php?method=ljxg&id=" + bh;
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
        if (!confirm('请确认是否进行添加！')) return false;
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
            <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />设置首页链接</h1>
            <div class="ysdb2">
            <p>首页链接将出现在用户空间内空间标题下面。</p>
                <form name="ctl00" method="post" action="?method=lj" id="ctl00">
                    <table style="background:#fff; border:0px;">
                        <tr>
                            <td style="border:0px;">显示名称：<input name="ljmc" type="text" id="ljmc" style="width:250px;" /></td>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:0px;">链接地址：<input name="ljdz" type="text" id="ljdz" style="width:250px;" /></td>
                        </tr>
                        <tr>
                        <?php echo $bottom; ?>
                        </tr>
                    </table>
                    <br />
                    <div class="xs" id="xssj">
                    <?php echo $Link_list;?>
                    </div>

                </form>
            </div>
            <ul class="yslb2">
                <li>链接地址必须是 http:// 或 https:// 或 ftp://  开头。</li>
                <li>为了加强空间安全管理，防止空间内挂马，空间不支持写入script代码和iframe。</li>
                <li>链接名称和链接地址不支持字符（ , ; ' \ ）。链接地址不支持字符（ < > ）</li>
                <li>如果需要显示图片，请在显示名称的框里输入：&lt;img src=&quot;图片地址&quot; /&gt; </li>
                <li>首页链接的位置如图所示：<br /><img alt="" src="/images/sylj.gif"  style="width:399px;height:171px" /></li>
            </ul>
        </div>
