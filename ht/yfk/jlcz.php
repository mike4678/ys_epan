<?php
// ===== 充值记录页（需求4/5）：查询 epan_orders 充值订单，与消费记录(jlxf)区分 =====
$uid = 2; $sid = 2;
$rows = array();
if($db -> LoginCheck() != 0){
	$mu = $db->fetch_array($db->query('select userid,username from epan_users where username="'.$db->escape($_SESSION['username_epan']).'"'));
	$myid = is_array($mu) ? (int)$mu['userid'] : 0;
	$q = $db->query('select * from epan_orders where userid='.$myid.' order by in_time desc');
	if($q){ while($r=$db->fetch_array($q)){ $rows[] = $r; } }
}
$pmMap = array('alipay'=>'支付宝','tenpay'=>'财付通','wx'=>'微信','zx'=>'在线银行','yhzz'=>'银行转账','' => '未指定');
?>
<div id="ysright">
	<h1><span id="yhztxs"><?php echo $db->LoginBar(); if($_SESSION['username_epan'] != NULL){ echo $top; }?></span>
<img border="0" src="/ht/images/money.gif" width="32" height="32" alt="" />充值记录</h1>
	<div class="ysdb2">
	<table class="ht-table">
		<tr class="trbt"><th>订单号</th><th>支付方式</th><th>金额(元)</th><th>状态</th><th>下单时间</th></tr>
		<?php if(empty($rows)){ echo '<tr><td colspan="5" style="padding:16px;text-align:center;color:#9ca3af;">暂无充值记录</td></tr>'; }
			else { foreach($rows as $r){
				$p = isset($pmMap[$r['pay_method']]) ? $pmMap[$r['pay_method']] : $r['pay_method'];
				$st = (in_array($r['pay_status'], array('Y','1','success','SUCCESS'), true)) ? '<font color="#16a34a">成功</font>' : ((trim($r['pay_status'])==='') ? '待支付' : $r['pay_status']);
				$t = $r['in_time'] ? date('Y-m-d H:i:s', (int)$r['in_time']) : '-';
				echo '<tr><td>'.htmlspecialchars($r['order_number'], ENT_QUOTES,'UTF-8').'</td><td>'.htmlspecialchars($p, ENT_QUOTES,'UTF-8').'</td><td>'.$r['total_fee'].'</td><td>'.$st.'</td><td>'.$t.'</td></tr>';
			}} ?>
	</table>
	<p style="margin-top:10px;font-size:12px;color:#6b7280;">注：充值记录按订单生成时间倒序显示。</p>
	</div>
</div>
