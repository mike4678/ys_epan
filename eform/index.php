<?php
include "../includes/Init.php";
?>
<title>举报中心</title>
<link rel="stylesheet" href=<?php PHPDISK_ROOT ?>"../css/eform.css">
	<script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.9.1.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/artDialog.js?skin=default"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/iframeTools.js"></script>
<body>
<form name="report" method="post" action="index.php">
<script>
function look1() {
	art.dialog.open('eform_law1.php', {title: '互联网信息服务管理办法', lock: true, background: '#000', opacity: 0.87});}
	
function look2() {
	art.dialog.open('eform_law2.php', {title: '不良信息自律规范', lock: true, background: '#000', opacity: 0.87});}
	
function look3() {
	art.dialog.open('eform_law4.php', {title: '关于开展对“私服”、“外挂”专项治理的通知', lock: true, background: '#000', opacity: 0.87});}
</script> 
<?php 

if( $_SERVER['REQUEST_METHOD'] == 'POST') 
{ 
	if(!empty($_POST['dlmc']) && !empty($_POST['nr']) && !empty($_POST['lylx']) && !empty($_POST['nryy']))  //这些提交上来的内容都不为空情况下才写入数据库
		{	
			$dlmc = $db->escape($_POST['dlmc']);
			$nr   = $db->escape($_POST['nr']);
			$nryy = $db->escape($_POST['nryy']);
			$allowed_types = array('反动内容','色情内容','侵犯版权','其他');
			$lylx = in_array($_POST['lylx'], $allowed_types) ? $_POST['lylx'] : '其他';
			$db->query("INSERT INTO `epan_UserReport` (`Contant`, `Address`, `Type`, `Data`) VALUES ('".$dlmc."', '".$nr."', '".$lylx."', '".$nryy."');");
			if($db->affected_rows() != NULL)
				{
					$db -> message('感谢您的提交，我们将尽快进行核实！');
					} else { $db -> message('提交失败！'); }
			 } else {  $db -> message('提交失败！'); }
	}
?>
<div style="border:0;width:100%;cellspacing:0;cellpadding:0;height:100%;">
	<div class='top'>
		<p align="right"><span style="font-size: 9pt">
	  <a target="_blank" href="../kf.php"><font color="#FFFFFF">返回客服中心</font></a></span></p></td>
	</div>
	<tr>
		<td valign="top"><br /><br />
			<div align="center">
			    <table border="1" width="531" id="table1" cellpadding="20" bgcolor="#F5F8FB" style="border-collapse: collapse" bordercolor="#99BBFF">
				    <tr>
					    <td>
					        <p align="center"><span style="font-size: 15pt; font-weight: 700">报告滥用行为</span></p>
			                <p><span style="font-size: 10pt">&nbsp;&nbsp;请将任何非法或不正当的行为报告给我们，协助我们维护一个健康的网络环境。如果您发现某个硬盘空间里面存有反动、色情、病毒等违规文件，请使用此表格通知我们。
			                我们将针对您的报告开展调查并对此空间采取适当的措施。我们会将所有非法活动报告给相关权力机构。</span></p>
			                <p>请输入您的联系方式，如qq、msn、email、联系电话等：</p>
			                <p><input name="dlmc" type="text" id="dlmc" size="40" /></p>
			                <p>请输入您要举报的空间地址(完整网址)：</p>
			                <p><input name="nr" type="text" id="nr" size="40" /></p>
			                <p>请选择滥用的类型：</p>
					            <select name="lylx" id="lylx">
	<option selected="selected" value="请选择一个选项">请选择一个选项</option>
	<option value="反动内容">反动内容</option>
	<option value="色情内容">色情内容</option>
	<option value="侵犯版权">侵犯版权</option>
	<option value="其他">其他</option>

</select>
			                <p>为了帮助我们迅速查清问题，请就您所举报的滥用或侵害行为提供尽可能详细的资料。</p>
			                <p><textarea name="nryy" id="nryy" rows="6" cols="38"></textarea></p>
			                <p>
			                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="ctl01" type="submit" value=" 提 交 " />
			                
			                </p>
					    </td>
				    </tr>
			    </table>
			</div>
			<br /><br />
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<div class='footer'>
		<p align="center"><span style="font-size: 9pt">
		相关链接：<a href="javascript:look1()"><font color ="#FFFFFF">互联网信息服务管理办法</font></a>&nbsp; 
		<a href="javascript:look2()"><font color ="#FFFFFF">互联网站禁止传播淫秽、色情等不良信息自律规范</font></a>&nbsp; 
		<a href="javascript:look3()"><font color ="#FFFFFF">关于开展对“私服”、“外挂”专项治理的通知</font></a></span></p>
	</div>
</div>
</form>
</body>
</html>