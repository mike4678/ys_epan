<?php
$sid = 2;
$uid = 1;

// ===== 自主升级全局开关门禁（需求3-8）：upgrade_enable=0 时拒绝升级 POST 并隐藏升级控件 =====
$__upgrade_enabled = ($db->Info('upgrade_enable') === '1');

// ===== 空间升级 POST 处理（先于展示执行，保证页面回显读到升级后的新级别）=====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db->LoginCheck() != 0 && isset($_POST['tj'])) {
	if (!$__upgrade_enabled) {
		$db->message('管理员已关闭自主升级，请联系管理员办理升级。');
	} else {
		// 合法付费级别白名单：pay=1（付费）且 status=0（允许申请），与 Product('3') 口径一致
		$valid = array();
		$vres = $db->query('SELECT code,name,data FROM epan_product WHERE pay = 1 AND status = 0');
		while ($p = $db->fetch_array($vres)) { $valid[(int)$p['code']] = $p; }
		$sel = isset($_POST['jbxz']) ? trim($_POST['jbxz']) : '';
		$selCode = (is_numeric($sel) ? (int)$sel : -999);
		if (!isset($valid[$selCode])) {
			$db->message('请先选择要升级的空间级别！');
		} else {
			// 升级为付费级别：写 diskLevel，并把到期时间置为 1 年后（与 kjyq 一年期口径一致）
			$expire = time() + 365 * 86400;
			$uname = $db->escape($_SESSION['username_epan']);
			$db->query("UPDATE epan_userData SET diskLevel = ".((int)$selCode).", Expire_time = ".((int)$expire)." WHERE `user` = '".$uname."'");
			$db->message('空间升级成功！当前为：'.$valid[$selCode]['name'].'['.$valid[$selCode]['data'].'MB]');
		}
	}
}

if($db -> LoginCheck() != 0){
	$login =  '<span id="ysts" style="display:none;" >';
	// 当前级别（POST 升级后此处读到新值；用独立 SQL 避免命中查询缓存里的旧结果）
	// diskLevel 权威来源为 epan_userData（live 库 epan_users 无 diskLevel 列，原 u.diskLevel 会触发 Unknown column 错误）
	$cur = $db->fetch_array($db->query('select d.diskLevel, d.Expire_time from epan_users u inner join epan_userData d on d.`user` = u.username where u.username = "'.$db->escape($_SESSION['username_epan']).'"'));
	$DiskLevel = (isset($cur['diskLevel']) && is_numeric($cur['diskLevel'])) ? (int)$cur['diskLevel'] : 0;
	$Expire    = (isset($cur['Expire_time']) && is_numeric($cur['Expire_time'])) ? (int)$cur['Expire_time'] : 0;
	$diskTemp = $db->fetch_array($db -> query('select name,data from epan_product where code = '. $DiskLevel .';'));
	if($DiskLevel == 0) {
		$diskType = '空间类型：' . $diskTemp['name'].'['.$diskTemp['data'].'MB] <br />'; }
		else if ($DiskLevel == -1) {
			$diskType = '空间类型：' . $diskTemp['name'].' <br />';
		} else {
		$diskType = '空间类型：' . $diskTemp['name'].'['.$diskTemp['data'].'MB] <br />'. '    到期时间：' . date("Y-m-d H:i:s" , $Expire);
		}

	// 构建供 JS 回显用的级别表：code => {n:名称, s:容量MB, p:参考价(元/年)}
	// epan_product 无价格列，参考价按容量档位给展示值（本系统余额恒为 0，不实际扣款）
	$priceMap = array(1=>30, 2=>60, 3=>100, 4=>300, 5=>500);
	$jbJs = array();
	$jbRes = $db->query('SELECT code,name,data FROM epan_product WHERE pay = 1 AND status = 0 ORDER BY code ASC');
	while ($jp = $db->fetch_array($jbRes)) {
		$c = (int)$jp['code'];
		$jbJs[$c] = array('n' => $jp['name'], 's' => (int)$jp['data'], 'p' => isset($priceMap[$c]) ? $priceMap[$c] : 0);
	}
	$jbJsJson = json_encode($jbJs, JSON_UNESCAPED_UNICODE);
} else { $login =  '<span id="ysts" >'; $jbJsJson = '{}'; }

?>
<style type="text/css">
    .cc{cursor:hand; font-size:10pt;}
    #jbxz{padding:6px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;background:#fff;}
    #tj{padding:8px 22px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:14px;cursor:pointer;}
    #tj:hover{background:#1d4ed8;}
</style>
<form name="ctl00" method="post" action="?method=kjsj" id="ctl00" onsubmit="return checkjb();">
<div>
<input name="t_jlje" type="hidden" id="t_jlje" />
<div id="ysright">
        <h1><span id="yhztxs"><?php echo $db -> LoginBar();?><img border="0" src="/ht/images/sj.gif" width="32" height="32" alt="" />空间升级</h1>
<?php if (!$__upgrade_enabled): ?>
        <div style="margin:24px 0;padding:16px;background:#fff7ed;border:1px solid #fdba74;border-radius:8px;color:#9a3412;font-size:14px;line-height:1.8;">
            管理员已关闭「自主升级空间」功能。如有升级需求，请联系站点管理员办理。
        </div>
<?php else: ?>
        <span id="pay">
        <p><b>当前空间类型：</b><br /><br />
            <span style="color:Blue";><?php echo $diskType; ?></span>
            <br /><br />
            <b>请选择想升级的空间：</b><br /><br />
			<span>*</span>空间级别：
                    <select id="jbxz" name="jbxz" onblur="checkjb();" onfocus="writejb();" onchange="xzjb();">
                        <option value="NULL" selected="selected">请选择</option>
                        <?php
							if($DiskLevel != -1){ $db->Product('3'); }
							?>
                    </select>
                <span class="cc"><input type="submit" name="tj" value="立即升级" id="tj" /></span>
                <br /><br /><span id="jbmsg"></span>
            </p>
            <p>
                <?php echo $login;?>请<a href='/ht/login.php'>登录</a>后再进行空间升级</span>

            </p>
            <ul class="yslb2">

                <li>您的账户余额为:<b>0</b>元。<a href='index/yfk'>账户充值</a>。</li>

            </ul>
<?php endif; ?>
        </div>
</div>
</form>
<script type="text/javascript">
/* 级别表：code -> {n:名称, s:容量MB, p:参考价元/年} */
var JB_DATA = <?php echo $jbJsJson; ?>;
function $(id){ return document.getElementById(id); }

/* onfocus：聚焦下拉时给出选择提示 */
function writejb(){
	var sel = $('jbxz');
	if(!sel || sel.value == 'NULL' || sel.value == ''){
		$('jbmsg').innerHTML = '<font color="#888">请在下拉框中选择要升级的空间级别。</font>';
	}
}

/* onchange：选中级别后回显对应容量/参考价，并写入隐藏价格字段 */
function xzjb(){
	var sel = $('jbxz');
	var v = sel.value;
	if(v == 'NULL' || v == '' || !JB_DATA[v]){
		$('jbmsg').innerHTML = '';
		$('t_jlje').value = '';
		return;
	}
	var d = JB_DATA[v];
	$('jbmsg').innerHTML = '<font color="blue">容量：<b>'+d.s+' MB</b>　参考价：<b>'+d.p+' 元/年</b>　升级后有效期 1 年</font>';
	$('t_jlje').value = d.p;
}

/* onblur / 提交前校验：必须已选择具体级别 */
function checkjb(){
	var sel = $('jbxz');
	if(!sel || sel.value == 'NULL' || sel.value == ''){
		$('jbmsg').innerHTML = '<font color="red">请选择要升级的空间级别后再点“立即升级”。</font>';
		return false;
	}
	return true;
}
</script>
