<?php
$uid = 5;
$sid = 3;
if($db -> LoginCheck() != 0){
	//首先获取空间级别（batch11 需求1）：权威来源 = epan_userData.diskLevel（live 库 epan_users 无 diskLevel 列），
	//与 ht/kjyq.php、ht/kjsj.php 修法一致，显式 join 读 d.diskLevel/d.Expire_time，不再走 AccountData 的 u.*,d.* 通配。
	$cur = $db->fetch_array($db->query('select d.diskLevel, d.Expire_time from epan_users u inner join epan_userData d on d.`user` = u.username where u.username = "'.$db->escape($_SESSION['username_epan']).'"'));
	$DiskLevel = (is_array($cur) && isset($cur['diskLevel']) && is_numeric($cur['diskLevel'])) ? (int)$cur['diskLevel'] : 0;
	$Expire    = (is_array($cur) && isset($cur['Expire_time']) && is_numeric($cur['Expire_time'])) ? (int)$cur['Expire_time'] : 0;
	$diskTemp = $db->fetch_array($db -> query('select name,data from epan_product where code = '. $DiskLevel .';'));
	if($DiskLevel == 0) {
		$diskType = '空间类型：' . $diskTemp['name'].'['.$diskTemp['data'].'MB] <br />';
		$downlevel = '<div id="level" style="display:none">';
		$downfree = '<div id="free">';
		} else {
		$diskType = '空间类型：' . $diskTemp['name'].'['.$diskTemp['data'].'MB] <br />'. '    到期时间：' . date("Y-m-d H:i:s" , $Expire);
		$downlevel = '<div id="level" >';
		$downfree = '<div id="free" style="display:none">';
		}

}
?>
<style type="text/css">
td{ vertical-align:top;}
</style>
<div id="ysright">
    <h1><span id="yhztxs"><?php echo $db -> LoginBar();?></span><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />空间降级</h1>
    <form name="ctl00" method="post" action="?method=kjjj" id="ctl00">
    <span id="pay">
<p><b>当前空间类型：</b><br /><br />
    <span style="color:Blue";><?php echo $diskType; ?></span>
    <br />
    <b>请选择想降级的空间：</b><br /><br />
    <?php echo $downlevel; ?>
	<span>*</span>空间级别：
        
            <select id="jbxz" onblur="checkjb();" onfocus="writejb();" onchange="xzjb();">
                <option value="NULL" selected="selected">请选择</option>
                <?php $db->Product('3');?>
            </select>
        <span class="cc"><input type="submit" name="tj" value="立即降级" id="tj" /></span> </p> </div>

      <?php echo $downfree; ?><p style="font-size:10pt;"><b>您的空间是免费型，不能进行降级操作。</b></p></div>
        
      <ul class="yslb2" style="margin-top:50px;">
            <li>只有付费空间到期时间在5天内或者已经过期的才可以降级。</li>
            <li>降级后，空间到期时间不变。</li>
            <li>降级后，如果空间内的文件总量超过空间容量，文件将不能下载，需要删除一部分文件，使文件总量小于空间容量后才可以正常下载。</li>
        </ul>
  </form>

</div>
