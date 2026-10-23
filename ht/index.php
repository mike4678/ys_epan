<?php
include "../includes/Init.php";
include PHPDISK_ROOT.'includes/top.php';
$sid = 1;
$uid = 1;

// ===== ht 后台单页框架：method → 功能页内容片段映射（div/flex 现代布局版）=====
// 菜单点击仅替换右侧内容区，不整页跳转；URL 形如 ht/index/xxx
// 白名单分发：method 不存在或非法时回退到默认"空间状态"
$methods = array(
	'cg_bt' => 'kjsd/cg_bt.php',
	'cg_jy' => 'kjsd/cg_jy.php',
	'cg_yy' => 'kjsd/cg_yy.php',
	'fg'    => 'kjsd/fg.php',
	'qx'    => 'kjsd/qx.php',
	'lj'    => 'kjsd/lj.php',
	'ljsc'  => 'kjsd/ljsc.php',
	'ljxg'  => 'kjsd/ljxg.php',
	'kjsj'  => 'kjsj.php',
	'kjyq'  => 'kjyq.php',
	'csmm'  => 'qt/csmm.php',
	'hywj'  => 'qt/hywj.php',
	'kjjj'  => 'qt/kjjj.php',
	'ly'    => 'qt/ly.php',
	'mbzh1' => 'qt/mbzh1.php',
	'mbzh2' => 'qt/mbzh2.php',
	'ml'    => 'qt/ml.php',
	'yjzh'  => 'qt/yjzh.php',
	'zhmm'  => 'qt/zhmm.php',
	'zhmm2' => 'qt/zhmm2.php',
	'yfk'   => 'yfk/index.php',
	'jlcz'  => 'yfk/jlcz.php',
	'jlxf'  => 'yfk/jlxf.php',
	'yhzz'  => 'yfk/yhzz.php',
	'xgzl'  => 'zlwh/xgzl.php',
	'xgmm'  => 'zlwh/xgmm.php',
	'xgem'  => 'zlwh/xgem.php',
	'dlmm'  => 'zlwh/dlmm.php',
	'szmb'  => 'zlwh/szmb.php',
	'xgmb'  => 'zlwh/xgmb.php',
);

// method → 中文标题（浏览器标签页 <title> 随当前操作菜单项变化）
$method_titles = array(
	'cg_bt' => '设置空间标题','cg_jy' => '设置空间简介','cg_yy' => '设置首页寄语','fg' => '空间风格','qx' => '设置访客权限',
	'lj' => '首页链接','ljsc' => '删除链接','ljxg' => '修改链接',
	'kjsj' => '空间升级','kjyq' => '空间续费/延期','csmm' => '重置密码','hywj' => '恢复误删文件',
	'kjjj' => '空间降级','ly' => '在线留言','mbzh1' => '空间模板一','mbzh2' => '空间模板二',
	'ml' => '目录列表','yjzh' => '邮件激活','zhmm' => '找回密码','zhmm2' => '找回密码(二)',
	'yfk' => '账户充值','jlcz' => '充值记录','jlxf' => '消费记录','yhzz' => '银行转账',
	'xgzl' => '修改资料','xgmm' => '修改密码','xgem' => '修改邮箱','dlmm' => '空间登录密码',
	'szmb' => '设置模板','xgmb' => '修改模板',
);

if($db -> LoginCheck() != 0){
	//空间地址
	$address = Website."k.php?name=".$_SESSION['username_epan']; 
	//当前账户：epan_users 取基本资料（email 等），diskLevel/Expire_time 权威来源为 epan_userData（live 库 epan_users 无此两列），left join 取齐
	$me = $db->fetch_array($db->query('select u.*, d.diskLevel, d.Expire_time from epan_users u left join epan_userData d on d.`user` = u.username where u.username = "'.$db->escape($_SESSION['username_epan']).'"'));
	if(!is_array($me)){ $me = array(); }
	//邮箱
	$email = Crypt::hideStar(isset($me['email']) ? $me['email'] : '') . '&nbsp;&nbsp;&nbsp;&nbsp;<a href="index/xgem">修改邮箱</a>';
	//空间到期时间
	$Expire = isset($me['Expire_time']) ? $me['Expire_time'] : 0;
	if ($Expire != 0) {
		$time = date("Y-m-d H:i:s" , $Expire);
		} else {
			$time = '<P><b>免费用户<font color=red>一年内至少要在空间管理区或者空间后台登录一次</font>，否则空间会被锁定。<br />如果空间被锁定，在空间后台登录一次即可解锁，如果用户超过一年没有解锁，系统将清除此空间。删除的空间和空间内数据无法还原。</b></P>';
			}
	//空间级别（guard：diskLevel 列缺失时回退到 code=0 的产品）
	$DiskLevel = (isset($me['diskLevel']) && is_numeric($me['diskLevel'])) ? (int)$me['diskLevel'] : 0;
	$diskTemp = $db->fetch_array($db->query('select name,data from epan_product where code = '. $DiskLevel));
	$disk = (isset($diskTemp['name']) ? $diskTemp['name'] : '免费空间').'['.(isset($diskTemp['data']) ? $diskTemp['data'] : '0').'MB] <a href="index/kjsj">空间升级</a>';
		}

// method 分发（仅允许字母数字下划线，白名单防路径注入）
$method = isset($_GET['method']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['method']) : 'index';
$fragment = (isset($methods[$method])) ? $methods[$method] : null;   // null = 默认空间状态
$page_title = isset($method_titles[$method]) ? $method_titles[$method] : '空间状态';

?>
<title><?php echo $page_title; ?> - <?php echo Website_title; ?>空间后台</title>
<script src="control.js"></script>
<body>
<div id="ysm">
  <div class="ht-layout">
    <?php include 'left.php'; ?>
    <main class="ht-main">
      <?php if($fragment !== null){
          // 功能片段自带 <div id="ysright"> 外壳；此处仅预置空 $top（首页弹窗只在空间状态触发）
          $top = '';
          include PHPDISK_ROOT.'ht/'.$fragment;
      } else { ?>
      <div id="ysright">
        <?php $top = '<script>window.onload = function () {art.dialog.open(\'managerlist.php\', {title: \'最近登录记录\'});}</script>'; ?>
        <h1><span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
<img border="0" src="images/ys168_sd_cg.gif" width="32" height="32" alt="" />空间状态</h1>
        <h3>基本状态</h3>
        <div class="ysdb2">
          <table class="ht-table">
              <tr>
                <td class="tdbt" width="120">空间地址:</td>
                <td><span id="la01"><?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></span></td>
              </tr>
              <tr>
                <td class="tdbt">空间级别:</td>
                <td><span id="la03"><?php echo $disk; ?></span></td>
              </tr>
              <tr>
                <td class="tdbt">账户余额:</td>
                <td><span id="la04"><font color=blue size=3><b><u>0.0000</u></b></font>元
<a href="index/yfk">账户充值</a>(账户余额可用于空间升级或延期)</span></td>
              </tr>
              <tr>
                <td class="tdbt">服务到期日:</td>
                <td><span id="la05"><?php echo $time; ?></span></td>
              </tr>
              <tr>
                <td class="tdbt">邮箱:</td>
                <td><span id="laem"><?php echo $email; ?></span></td>
              </tr>
          </table>
          <h3>其它方面</h3>
          <div class="ht-warn">
                <font color="black"><?php echo Website_title; ?>内禁止存放反动、色情、游戏辅助外挂及其他国家法律法规禁止的内容，如收到举报经查实后，空间将被关闭。</font>
          </div>
          <ul class="yslb2 ht-tips">
          <span id="la06"><li>您尚未设置<?php echo Website_title; ?>标题，<a href="index/cg_bt">进入设置</a></li><li>您没有设置<?php echo Website_title; ?>登录密码，<a href="index/dlmm">进入设置</a></li><li>及时更新您的个人资料有助于我们更好的为您服务，<a href="index/xgzl">现在更新</a></li><li>设置访客权限有助于您对空间的管理，<a href="index/qx">进入设置</a></li><li>您尚未设置首页链接，<a href="index/lj">进入设置</a></li><li>如果您的Email信箱地址有变化，请及时更新您的Email地址，<a href="index/xgem">现在更新</a></li><li>如果您没有设置空间自定义区，请查看<a href="/help/diy.php">如何设置自定义区</a></li><li>使用中如有问题，请查看<a href="/help/">操作说明</a>，或到<a href="/kf.php">客服中心</a>和我们联系</li></span>
          </ul>
        </div>
      </div>
      <?php } ?>
    </main>
  </div>
</div>
<style>
/* ===== ht 后台现代 div/flex 布局（替代旧 table 布局；设计 Token：#2563eb）===== */
/* 后台容器：不搞满屏——居中、设宽度上限（1280px），右侧框在容器内贴右 */
#ysm{max-width:1280px;margin:8px auto}
/* 布局：左上留白、右/底贴边；两栏等高（align-items:stretch），高度随内容、不撑满视口 */
.ht-layout{display:flex;align-items:stretch;gap:20px;padding:24px 24px 0 24px}
/* 左侧菜单：固定宽度、两栏等高 */
#ysleft{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);width:212px;flex:0 0 212px;box-sizing:border-box;padding:18px 14px;overflow:visible}
#ysleft h2{font-size:15px;font-weight:700;color:#1d4ed8;border-bottom:1px solid #dbeafe;padding-bottom:10px;margin:0 0 10px;line-height:1.5}
/* 以下以 !important 压过 common.css 旧规则（li 固定高17px / strong 固定高15px / ul overflow:hidden 导致选中重叠与文字显示不完整） */
#ysleft strong{display:block;height:auto !important;margin:16px 0 6px !important;padding:0 !important;font-size:12.5px;color:#6b7280;font-weight:600;line-height:1.5}
#ysleft ul{list-style:none;margin:0 !important;padding:0 !important;width:auto;overflow:visible !important}
#ysleft li{height:auto !important;line-height:1.6 !important;margin:5px 0 !important}
#ysleft a{display:block;color:#374151;text-decoration:none;padding:8px 10px !important;border-radius:8px;font-size:13px;line-height:1.6;overflow:visible;white-space:nowrap;width:auto !important;box-sizing:border-box}
#ysleft a:hover{background:#eff6ff;color:#2563eb}
/* 当前菜单项选中态：主色深蓝底 + 白字（JS 按当前 method 动态互斥高亮） */
#ysleft a.ht-active{background:#2563eb !important;color:#fff !important;font-weight:600}
/* 右侧主内容区：flex 拉伸占满剩余宽度，并随布局高度贴底 */
.ht-main{flex:1 1 auto;min-width:0;display:flex;flex-direction:column}
/* 右侧主内容区：拉伸贴外框（#ysm）右缘；覆盖 common.css 的 #ysright{width:536px;margin-left:10px} */
#ysright{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 14px rgba(0,0,0,.06);padding:24px 28px;box-sizing:border-box;overflow:auto;flex:1 1 auto;width:auto !important;margin-left:0}
#ysright h1{font-size:20px;font-weight:700;color:#1f2937;border-bottom:1px solid #e5e7eb;padding-bottom:12px;margin:0 0 6px;display:flex;align-items:center;gap:8px}
#ysright h3{font-size:15px;font-weight:700;color:#1d4ed8;margin:22px 0 10px;padding-left:10px;border-left:3px solid #2563eb}
.ht-table{width:100%;border-collapse:collapse;background:#fff}
.ht-table td{border-bottom:1px solid #e5e7eb;padding:11px 14px;font-size:14px;color:#374151}
.ht-table td.tdbt{font-weight:600;color:#1f2937;background:#f9fafb;white-space:nowrap}
.ht-table a{color:#2563eb;text-decoration:none}
.ht-table a:hover{text-decoration:underline}
.ht-warn{border:1px dashed #f59e0b;background:#fffbeb;border-radius:10px;padding:12px 16px;font-size:13px;color:#374151;line-height:1.7;margin-bottom:14px}
.ht-tips{list-style:none;margin:0;padding:0}
.ht-tips li{font-size:13px;color:#374151;line-height:1.8;padding:7px 0 7px 20px;position:relative;border-bottom:1px solid #f3f4f6}
.ht-tips li::before{content:"•";position:absolute;left:4px;color:#2563eb;font-weight:bold}
.ht-tips a{color:#2563eb;text-decoration:none}
.ht-tips a:hover{text-decoration:underline}
@media(max-width:960px){
  .ht-layout{flex-direction:column;padding:14px}
  #ysleft{width:100%;flex:1 1 auto}
  #ysright{margin-left:0;min-width:0}
}
</style>
<script>
/* 单页后台：根据当前 URL 的 method 高亮左侧对应菜单项（蓝底白字） */
(function(){
  var m = (new URLSearchParams(location.search)).get('method');
  m = m ? m.replace(/[^a-zA-Z0-9_]/g,'') : 'index';
  var links = document.querySelectorAll('#ysleft a');
  for(var i=0;i<links.length;i++){
    var a = links[i];
    if(a.getAttribute('href').indexOf('index.php') === -1){ continue; } /* 只处理框架内菜单项，跳过安全退出/外链 */
    var am = null;
    try{ am = new URL(a.href).searchParams.get('method') || 'index'; }catch(e){}
    if(am === m){ a.className += ' ht-active'; }
  }
})();
</script>
</body>
<?php
include PHPDISK_ROOT."includes/footer.php";
?>
</html>
