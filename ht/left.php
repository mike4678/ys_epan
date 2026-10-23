<?php
error_reporting(0);
if(empty($uid) && empty($sid) )   //检查，如果这两个参数都为空，则自动默认为1
{ $uid = 1; 
  $sid = 1;
} 

// ===== 菜单 URL 单页化转换（UI 改版第 4 批）=====
// 功能页 URL → index/xxx（白名单基于 ht/index.php 的 $methods）
// 特殊项保留原样：空间状态(index.php)、安全退出(exit.php)、javascript:error()、支付网关子页、外链
function ht_menu_url($u){
	global $methods;
	$u = trim($u);
	if($u == '' || $u == '/ht/' || $u == 'ht/' || $u == '/ht/index.php' || $u == 'ht/index.php' || $u == 'index.php'){ return 'index.php'; }
	if(strpos($u,'javascript:') !== false){ return $u; }
	if(strpos($u,'exit.php') !== false){ return $u; }
	if(strpos($u,'http://') === 0 || strpos($u,'https://') === 0 || strpos($u,'//') === 0){ return $u; }
	// ===== 任务7：旧独立页面/失效链接 → 单页 method 映射（避免 404）=====
	// 先精确匹配文件名（把 jlcz.php/jlxf.php 等从 yfk/ 目录中分离，避免被 yfk/ 分支吞成 method=yfk）
	if(preg_match('#([^/]+)\.php$#i', $u, $m)){
		$base = strtolower($m[1]);
		if(isset($methods[$base])){ return 'index/'.$base; }
	}
	if(stripos($u,'yfk/') !== false && isset($methods['yfk'])){ return 'index/yfk'; }
	if(stripos($u,'jlcz.php') !== false && isset($methods['jlcz'])){ return 'index/jlcz'; }
	return $u;
}

	$left = '';
	$list = @$db->query('select * from epan_menu;'); 
	if($list){
		while ($row = $db->fetch_array($list)) 
		{
			if($row['table'] != NULL) 
			{
				$left.='</ul><strong>'.$row[2].'</strong><ul class=\'yslb3\'>';
				}
				
			$murl = ht_menu_url($row[4]);
			//框架内相对地址补全为绝对路径；特殊项原样输出
			$href = (strpos($murl,'index.php') === 0 || strpos($murl,'index/') === 0) ? PHPDISK_ROOT_WEB.'ht/'.$murl : $murl;
			if ($row[0] == $uid and $row[1] == $sid) 
			{
				$left.='<li><a href=\''. $href.'\'>'.$row[3].'</a></li>';
				 }elseif ( strstr($row[4] , 'javascript:error()' ) != false) {
					$left.='<li><a  href=\''.$murl.'\'>'.$row[3].'</a></li>';
					} else {
						$left.='<li><a href=\''. $href.'\'>'.$row[3].'</a></li>';
						}
			}
		}
	//epan_menu 表缺失或为空时，回退显示默认操作菜单，避免侧栏空白
	if(trim($left) == ''){
		$left = '<strong>空间设置</strong><ul class=\'yslb3\'>
<li><a href=\'index.php\'>空间状态</a></li>
<li><a href=\'index/cg_bt\'>修改空间标题</a></li>
<li><a href=\'index/cg_jy\'>修改空间简介</a></li>
<li><a href=\'index/fg\'>空间风格</a></li>
<li><a href=\'index/qx\'>设置访客权限</a></li>
<li><a href=\'index/lj\'>首页链接</a></li>
</ul>
<strong>空间业务</strong><ul class=\'yslb3\'>
<li><a href=\'index/kjsj\'>空间升级</a></li>
<li><a href=\'index/kjyq\'>空间缴费/延期</a></li>
<li><a href=\'index/kjjj\'>空间降级</a></li>
<li><a href=\'index/yfk\'>账户充值</a></li>
</ul>
<strong>资料管理</strong><ul class=\'yslb3\'>
<li><a href=\'index/xgzl\'>修改资料</a></li>
<li><a href=\'index/xgmm\'>修改密码</a></li>
<li><a href=\'index/xgem\'>修改邮箱</a></li>
<li><a href=\'index/dlmm\'>空间登录密码</a></li>
</ul>
<strong>其他</strong><ul class=\'yslb3\'>
<li><a href=\'index/hywj\'>恢复误删文件</a></li>
<li><a href=\'/ht/exit.php\'>安全退出</a></li>
</ul>';
	}
	


?>

<div id='ysleft'>
    <h2>空间后台操作菜单</h2>
    <?php echo $left;?>
  </div>
