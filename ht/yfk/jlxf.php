<?php
// ===== 消费记录页（需求4/5）：查询 epan_xiaofei 消费记录，与充值记录(jlcz)区分 =====
$uid = 2; $sid = 3;
$rows = array();
if($db -> LoginCheck() != 0){
	$mu = $db->fetch_array($db->query('select id from epan_users where username="'.$db->escape($_SESSION['username_epan']).'"'));
	$myid = is_array($mu) ? (int)$mu['id'] : 0;
	$q = $db->query('select * from epan_xiaofei where userid='.$myid.' order by intime desc');
	if($q){ while($r=$db->fetch_array($q)){ $rows[] = $r; } }
}
?>
<div id="ysright">
	<h1><span id="yhztxs"><?php echo $db->LoginBar(); if($_SESSION['username_epan'] != NULL){ echo $top; }?></span>
<img border="0" src="/ht/images/money.gif" width="32" height="32" alt="" />消费记录</h1>
	<div class="ysdb2">
	<table class="ht-table">
		<tr class="trbt"><th>编号</th><th>付费类型</th><th>时间</th><th>金额</th><th>服务到期</th></tr>
		<?php if(empty($rows)){ echo '<tr><td colspan="5" style="padding:16px;text-align:center;color:#9ca3af;">暂无消费记录</td></tr>'; }
			else { foreach($rows as $r){
				echo '<tr><td>'.$r['id'].'</td><td>'.htmlspecialchars($r['xfname'],ENT_QUOTES,'UTF-8').'</td>'
					.'<td>'.($r['intime']?date('Y-m-d H:i:s',(int)$r['intime']):'-').'</td>'
					.'<td>'.$r['je'].'</td>'
					.'<td>'.($r['outtime']?date('Y-m-d H:i:s',(int)$r['outtime']):'-').'</td></tr>';
			}} ?>
	</table>
	<p style="margin-top:10px;font-size:12px;color:#6b7280;">注：空间升级、延期等产生的消费记录，按消费时间倒序显示。</p>
	</div>
</div>
