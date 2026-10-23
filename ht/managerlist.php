<?php
//处理逻辑：
//如果没有获取到userid 则显示未登录，并显示为登录，如果已经登录则显示当前用户的最近5条登录记录
include "../includes/Init.php";
$list = '';
$safeUser = $db->escape($_SESSION['username_epan']);
$query = $db->query("select * from epan_userloginlog where user = '". $safeUser ."' order by lasttime desc limit 0,5;");
while ($row = $db->fetch_array($query)) 
{ $list.='<tr><td>'.date('Y-m-d H:i:s', $row[1]).'</td><td>'.htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8'). '</td><td><a href=\'http://www.baidu.com/s?wd='.urlencode($row[2]).'\' target=\'_blank\'>查看</a></td></tr>';

}

?>
<link rel="stylesheet" href="../css/common.css">
<table>
<tr><td class="tdbt" style="text-align:center;">登录时间</td><td class="tdbt" style="text-align:center;">登录ip</td><td class="tdbt" style="text-align:center;">ip所在地 </td></tr>
<?php echo $list ; ?>
<div style="text-align:left;padding-left:10px;">如有异常，请尽快修改密码，确认邮箱和密保是否正确</div>