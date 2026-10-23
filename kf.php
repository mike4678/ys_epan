<?php
include "includes/Init.php";
//$khlb = htmlspecialchars_decode($settings['khlb']);
//$contact1 = htmlspecialchars_decode($settings['contact1']);
//$db->query ("select ")
//$UserList = $settings['Client list'];
//$arr = explode("；",$UserList);
include PHPDISK_ROOT.'includes/top.php';
?>
<title>客服中心</title>

<table cellpadding="0" border="0" style="border-collapse: collapse"><tbody><tr valign="top"><td>
<div id="ysleft">
<h2>客服联系方式</h2>
<ul class='yslb'>
<?php 
$userList = $db->Info('contact1');
$arr = explode(";",$userList);
foreach($arr as $u){
echo "<li>".htmlspecialchars($u, ENT_QUOTES, 'UTF-8')."</li>";
}
?>
</ul>
<h2>我们的客户</h2>
<marquee scrollamount='2' scrolldelay='30' direction= 'UP' id='helpor_net' height='130' onmouseover='helpor_net.stop()' onmouseout='helpor_net.start()' Author:redriver='redriver'>
<ul class='yslb'>
<?php
$userList = $db->Info('khlb');
$arr = explode(";",$userList);
foreach($arr as $u){
echo "<li>".htmlspecialchars($u, ENT_QUOTES, 'UTF-8')."</li>";
}
?>
</ul>
</marquee> 
</div>
</td><td>
<div id="ysright">
<h1><img width="32" height="32" border="0" alt="" src="/ht/images/group.gif">客服中心</h1>
<div>
<h3>提交问题</h3>
<p><span id="ysts2">请在此处提交问题，我们会尽快回复。</span><a href="eform/">举报请按此进入</a></p>
<div align="center" style="margin-top:10px;">
<textarea style="height:80px;width:380px;" id="idnr" cols="20" rows="2" name="idnr"></textarea>
</div>
<div style="margin-left:74px;">
<input type="checkbox" id="fslyb" name="fslyb">把此问题的回复同时显示在空间的留言区内
</div>
<p align="center">
<span id="czts"></span>
<input type="submit" id="butj" onclick="return confirm('请确认是否提交问题');" value="提交问题" name="butj">
</p>
</div>
</div>            
</td>
</tr>
</tbody>
</table>
<?php
include PHPDISK_ROOT."includes/footer.php";

//http://kf.ys168.com/msg.aspx?mc=ys168&msg=%BF%CD%B7%FE%CE%CA%CC%E2:[mike4678]%B2%E2%CA%D4%B2%E2%CA%D4
//mc=ys168&msg=%BF%CD%B7%FE%CE%CA%CC%E2:[mike4678]%B2%E2%CA%D4%B2%E2%CA%D4

?>

