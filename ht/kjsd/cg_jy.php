<?php
$uid = 3;
$sid = 3;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ( strlen($_POST['t3']) >= 600){
		$db ->message('内容不能超过600字！');
		} else {
			if( stripos ($_POST['t3'],'</iframe>') != false || stripos ($_POST['t3'],'</script>') != false || stripos ($_POST['t3'],'<script>') != false || stripos ($_POST['t3'],'iframe') != false) {
				$db ->message('错误：系统不允许使用符号：iframe、script');
				} else {
				if($db ->SpaceUpdate($_SESSION['username_epan'] , 'Message' , base64_encode($_POST['t3'])) != 0) {
					$db ->message('更新成功！');
						} else { $db ->message('更新失败！'); }
					}
			}
	}

if($db -> LoginCheck() != 0){
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1">';
	if ($db -> SpaceSetting($_SESSION['username_epan'],'Message') != NULL) {
		$title = '<textarea name="t3" rows="2" cols="20" id="t3" style="height:100px;width:450px;" />'.base64_decode($db -> SpaceSetting($_SESSION['username_epan'],'Message')).'</textarea>';
		} else { $title = '<textarea name="t3" rows="2" cols="20" id="t3" style="height:100px;width:450px;"/></textarea>'; }
	} else {
	$bottom = '<input type="submit" name="bu1" value="提交设置" id="bu1" disabled="disabled"><font color="#FF0000"><span id="ysts"><a href=\'/ht/login.php\'><font color=red>请先登录</font></a></span></font>';
	$title = '<textarea name="t3" rows="2" cols="20" id="t3" disabled="disabled" style="height:100px;width:450px;"/></textarea>';
	}
?>
<div id="ysright">
                   <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="/ht/images/ys168_sd_cg.gif" width="32" height="32" alt="" />设置首页寄语</h1><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />首页寄语</h1>
            <div class="ysdb2">
            <form name="ctl00" method="post" action="?method=cg_jy" id="ctl00">
                    <table>
                    <tr>
                        <td>
                            <p>首页寄语</p>
                            <?php echo $title; ?>
			                    <span id="zzjyts"></span>
			                    <ul class="yslb2">
				                    <li>首页寄语显示于空间右下角，可以写一些介绍、欢迎等类型的文字</li>
				                    <li>如果在此处写入代码后空间不能正常使用，请删除所写代码</li>
				                </ul>
                        </td>
                    </tr>
                </table>
            <p>
                <?php echo $bottom;?>
            </p>
            <ul class="yslb2">
                <li>为了加强空间安全管理，防止空间内挂马，空间不支持写入script代码和iframe。</li>
                <li>首页寄语最多支持600个字符。</li>
                <li>QQ临时对话设置：请打开：<a href="http://shang.qq.com/widget/consult.php" target="_blank">http://shang.qq.com/widget/consult.php</a>。登录后先开通QQ临时对话功能，然后取得临时对话代码，复制在首页寄语内。</li>

            </ul>
            </form>
            </div>
        </div>
