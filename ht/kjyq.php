<?php
$sid = 3;
$uid = 1;

// ===== 空间延期 POST 处理（batch11 需求2）：选时长 → 校验登录/CSRF（CSRF 由 Init.php 统一拦截）→ 更新 Expire_time =====
// 时长白名单：p1=一月(30天) p2=半年(180天) p3=一年(365天) p4=两年(730天)；
// 续期基数 = max(now, 旧到期)（已过期从 now 起算，未过期在旧到期上累加）。
// 与 kjsj.php 升级链路同口径：escape 用户名、显式 update epan_userData、不实际扣款（账户余额恒 0，与 kjsj 一致）。
$__renewDays = array('p1'=>30, 'p2'=>180, 'p3'=>365, 'p4'=>730);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db->LoginCheck() != 0 && isset($_POST['tj'])) {
	$uname = $db->escape($_SESSION['username_epan']);
	// 仅收费空间（diskLevel>0）可延期
	$curLvl = $db->fetch_array($db->query('select d.diskLevel, d.Expire_time from epan_users u inner join epan_userData d on d.`user`=u.username where u.username="'.$uname.'"'));
	$curLvlNum = (is_array($curLvl) && isset($curLvl['diskLevel']) && is_numeric($curLvl['diskLevel'])) ? (int)$curLvl['diskLevel'] : 0;
	if ($curLvlNum <= 0) {
		$db->message('免费空间无需延期，如需更大容量请办理升级。');
	} else {
		$sel = isset($_POST['aa']) ? trim($_POST['aa']) : '';
		if (!isset($__renewDays[$sel])) {
			$db->message('请先选择要延期的期限！');
		} else {
			$oldExpire = (is_array($curLvl) && isset($curLvl['Expire_time']) && is_numeric($curLvl['Expire_time'])) ? (int)$curLvl['Expire_time'] : 0;
			$base = max(time(), $oldExpire);
			$addSec = (int)$__renewDays[$sel] * 86400;  // 一年=365天，与 kjsj 升级一年期 365*86400 口径一致
			$newExpire = $base + $addSec;
			$db->query("UPDATE epan_userData SET Expire_time = ".((int)$newExpire)." WHERE `user` = '".$uname."'");
			$db->message('延期成功！新到期时间：'.date("Y-m-d H:i:s", $newExpire));
		}
	}
}

if($db -> LoginCheck() != 0){
	$login =  '<span id="ysts" style="display:none;" >';
	//首先获取空间级别（batch10 需求2）：权威来源 = epan_userData.diskLevel（live 库 epan_users 无 diskLevel 列），
	//与 kjsj.php / ht/index.php 修法一致，显式 join 读 d.diskLevel/d.Expire_time，不再走 AccountData('diskLevel') 的 u.*,d.* 通配。
	$cur = $db->fetch_array($db->query('select d.diskLevel, d.Expire_time from epan_users u inner join epan_userData d on d.`user` = u.username where u.username = "'.$db->escape($_SESSION['username_epan']).'"'));
	$DiskLevel = (is_array($cur) && isset($cur['diskLevel']) && is_numeric($cur['diskLevel'])) ? (int)$cur['diskLevel'] : 0;
	$Expire    = (is_array($cur) && isset($cur['Expire_time']) && is_numeric($cur['Expire_time'])) ? (int)$cur['Expire_time'] : 0;
	if($DiskLevel == 0 ) {
		$free = '<span id="free" >';
		$pay = '<span id="pay"  style="display:none;">';
	} else {
		$free = '<span id="free" style="display:none;" >';
		$pay = '<span id="pay" >';
		$diskTemp = $db->fetch_array($db -> query('select name,data from epan_product where code = '. $DiskLevel ));
 		$diskType = '空间类型：' . $diskTemp['name'].'['.$diskTemp['data'].'MB] <br />'. '    到期时间：' . date("Y-m-d H:i:s" , $Expire);
		}

} else { $login =  '<span id="ysts" >';}

?>
<style type="text/css">
    .cc{cursor:hand; font-size:10pt;}
</style>
<form name="ctl00" method="post" action="?method=kjyq" id="ctl00">
<div>
<input name="t_jlje" type="hidden" id="t_jlje" />
<div id="ysright">
        <h1><span id="yhztxs"><?php echo $db -> LoginBar();?><img border="0" src="/ht/images/sj.gif" width="32" height="32" alt="" />空间延期</h1>
        <?php echo $free; ?>
        <p>您的空间是免费空间，无需进行延期操作。如果需要增加空间容量，请点<a href="index/kjsj">空间升级</a>进行操作。</p> </span>
        
        <?php echo $pay; ?>
        <p><b>当前空间类型：</b><br /><br />
            <span style="color:Blue";><?php echo $diskType; ?></span>
            <br /><br />
            <b>请选择延期期限：</b><br />
                        
            <div id="Panel1" style="margin-left:20px;">
				<span class="cc"><input id="p1" type="radio" name="aa" value="p1"/><label for="p1">一月期</label></span></div>
				<div id="Panel2" style="margin-left:20px;">
				<span class="cc"><input id="p2" type="radio" name="aa" value="p2"/><label for="p2">半年期</label></span></div>
                <div id="Panel3" style="margin-left:20px;">
				<span class="cc"><input id="p3" type="radio" name="aa" value="p3"/><label for="p3">一年期</label></span></div>
           	<div id="Panel4" style="margin-left:20px;">
				<span class="cc"><input id="p4" type="radio" name="aa" value="p4"/><label for="p4">两年期</label></span></div>
            <div id="Panel4" style="margin-left:20px;">
                <span class="cc"><input type="submit" name="tj" value="立即延期" id="tj" /></span></div>
            </p>

        
            <p><span id="sm"></span></p></span>
            <p>
                <?php echo $login;?>请<a href='/ht/login.php'>登录</a>后再进行空间延期</span>
                
            </p>         
            <ul class="yslb2">
            
                <li>您的账户余额为:<b>0</b>元。<a href='index/yfk'>账户充值</a>。</li>
            
            </ul>

        </div>
</div>
</form>
