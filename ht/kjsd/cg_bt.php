<?php
$uid = 3;
$sid = 1;
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
?>
<div id="ysright">
           <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />设置空间标题</h1>
            <div class="ysdb2">
            <form name="ctl00" method="post" action="?method=cg_bt" id="ctl00">
                <table>
                    <tr>
                        <td>
	                        空间标题：<br /><br />
                            <?php echo $title; ?>
                        </td>
                    </tr>
                </table>
            <p>
                <?php echo $bottom;?>
            </p>
            <ul class="yslb2">
                <li>空间标题显示在空间左上角。如果留空，则显示空间的网址。显示位置如下图箭头所示：<br />
                    <img src="/ht/images/cg_bt.jpg" width="358" height="162" />
                </li>
                <li>空间标题最多支持100个字符。</li>
                <li>如需在空间标题位置显示图片，代码为：&lt;img src=&quot;图片网址&quot;&gt;</li>
                <li>为了加强空间安全管理，防止空间内挂马，空间不支持写入script代码和iframe。</li>
            </ul>
            </form>
            </div>
        </div>
