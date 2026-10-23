<?php
//*******************************************************************
// 系统日志（admin 后台）
// 路由入口: /admin/index.php?/system/log
//   （zd_adminlist: t_e=system,m_e=log 的 page 指向本文件）
// 数据源: epan_log（log_id,log_type,log_time,log_ip,log_uri,log_method,log_cookie,log_detail）
// 功能:
//   ① 筛选：日志类型下拉(login/feedback/block/op/req/全部)、起止日期、IP 关键字
//   ② 分页列表：时间/IP/URI/方法/Cookie(截断)/类型/明细
//   ③ 删除单条；清空全部（POST 带 confirm）
// 说明: 本文件由 admin/index.php include（$db=AdminSystem 已就绪、会话已开启、
//       CSRF token 由全局 ob_start 自动注入 <form>）。
//*******************************************************************
if (!defined('source')) { header("Location: ../login.php"); exit; }

function lg_flash($msg) { $_SESSION['__lg_msg'] = $msg; }
function lg_get_flash() { $m = isset($_SESSION['__lg_msg']) ? $_SESSION['__lg_msg'] : ''; unset($_SESSION['__lg_msg']); return $m; }

//---------------- POST：删除单条 / 清空 ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$do = isset($_POST['do']) ? $_POST['do'] : '';
	if ($do === 'del') {
		$lid = (int)(isset($_POST['log_id']) ? $_POST['log_id'] : 0);
		if ($lid > 0) {
			$db->query("DELETE FROM epan_log WHERE log_id=" . $lid . " LIMIT 1");
			lg_flash('已删除日志 #' . $lid);
		}
	} elseif ($do === 'clear') {
		$db->query("TRUNCATE TABLE epan_log");
		lg_flash('已清空全部日志');
	}
	$back = 'index.php?/system/log';
	$qs = array();
	foreach (array('type','date_from','date_to','ip','page') as $k) {
		if (!empty($_POST['back_' . $k])) $qs[$k] = $_POST['back_' . $k];
	}
	if ($qs) $back .= '&' . http_build_query($qs);
	header('Location: ' . $back);
	exit;
}

//---------------- GET：筛选参数 ----------------
$f_type     = isset($_GET['type']) ? trim((string)$_GET['type']) : '';
$f_date_from = isset($_GET['date_from']) ? trim((string)$_GET['date_from']) : '';
$f_date_to   = isset($_GET['date_to']) ? trim((string)$_GET['date_to']) : '';
$f_ip       = isset($_GET['ip']) ? trim((string)$_GET['ip']) : '';
$page       = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$pageSize   = 20;
$offset     = ($page - 1) * $pageSize;

$validTypes = array('login','feedback','block','op','req');
$where = " WHERE 1=1 ";
if (in_array($f_type, $validTypes, true)) {
	$where .= " AND log_type='" . $db->escape($f_type) . "' ";
}
// 日期 → 时间戳（date_from 取当天 00:00:00；date_to 取当天 23:59:59）
$tsFrom = $f_date_from ? strtotime($f_date_from . ' 00:00:00') : 0;
$tsTo   = $f_date_to   ? strtotime($f_date_to   . ' 23:59:59') : 0;
if ($tsFrom > 0) $where .= " AND log_time >= " . $tsFrom . " ";
if ($tsTo > 0)   $where .= " AND log_time <= " . $tsTo . " ";
if ($f_ip !== '') $where .= " AND log_ip LIKE '%" . $db->escape($f_ip) . "%' ";

// 总数
$cntRs = $db->query("SELECT COUNT(*) c FROM epan_log " . $where);
$cntRow = $cntRs ? $db->fetch_array($cntRs) : array('c' => 0);
$total = (int)$cntRow['c'];
$pageCount = max(1, (int)ceil($total / $pageSize));
if ($page > $pageCount) { $page = $pageCount; $offset = ($page - 1) * $pageSize; }

// 列表
$list = $db->query(
	"SELECT log_id,log_type,log_time,log_ip,log_uri,log_method,log_cookie,log_detail
	 FROM epan_log " . $where . "
	 ORDER BY log_id DESC
	 LIMIT " . $offset . "," . $pageSize
);

function lg_qs($over = array()) {
	$p = array();
	foreach (array('type','date_from','date_to','ip','page') as $k) {
		if (isset($_GET[$k]) && $_GET[$k] !== '') $p[$k] = $_GET[$k];
	}
	$p = array_merge($p, $over);
	return $p ? '&' . http_build_query($p) : '';
}
$typeLabels = array('login'=>'登录','feedback'=>'留言','block'=>'拦截','op'=>'操作','req'=>'请求');
?>
<div class="panel admin-panel">
	<div class="panel-head"><strong><i class="mdi mdi-shield-search" style="color:#5e72e4;"></i> 系统日志</strong><span class="small" style="color:#888">（共 <?php echo $total; ?> 条记录）</span></div>

	<?php $flash = lg_get_flash(); if ($flash): ?>
	<div class="alert alert-success alert-dismissible" style="margin:8px;">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="mdi mdi-check-circle-outline"></i> <?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?>
	</div>
	<?php endif; ?>

	<!-- 筛选栏 -->
	<form method="get" action="index.php" class="padding border-bottom" style="margin-bottom:0;">
		<input type="hidden" name="/system/log" value="">
		<label>类型：
			<select name="type" class="input" style="height:30px;">
				<option value="">全部</option>
				<?php foreach ($validTypes as $vt): ?>
					<option value="<?php echo $vt; ?>" <?php if ($f_type===$vt) echo 'selected'; ?>>
						<?php echo htmlspecialchars($typeLabels[$vt].' ('.$vt.')', ENT_QUOTES, 'UTF-8'); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</label>
		<label>起始：
			<input type="date" name="date_from" class="input" style="height:30px;" value="<?php echo htmlspecialchars($f_date_from, ENT_QUOTES, 'UTF-8'); ?>">
		</label>
		<label>截止：
			<input type="date" name="date_to" class="input" style="height:30px;" value="<?php echo htmlspecialchars($f_date_to, ENT_QUOTES, 'UTF-8'); ?>">
		</label>
		<label>IP：
			<input type="text" name="ip" class="input" style="height:30px;width:120px;" value="<?php echo htmlspecialchars($f_ip, ENT_QUOTES, 'UTF-8'); ?>" placeholder="IP 关键字">
		</label>
		<input type="submit" class="button button-small border-blue" value="筛选">
		<a class="button button-small" href="index.php?/system/log">重置</a>
	</form>

	<form method="post" action="index.php?/system/log">
		<?php
		foreach (array('type'=>$f_type,'date_from'=>$f_date_from,'date_to'=>$f_date_to,'ip'=>$f_ip,'page'=>$page) as $bk=>$bv) {
			echo '<input type="hidden" name="back_' . $bk . '" value="' . htmlspecialchars((string)$bv, ENT_QUOTES, 'UTF-8') . '">';
		}
		?>
		<table class="table table-striped table-hover">
			<tr>
				<th width="5%">ID</th>
				<th width="14%">时间</th>
				<th width="11%">IP</th>
				<th width="24%">URI</th>
				<th width="6%">方法</th>
				<th width="12%">Cookie(截断)</th>
				<th width="8%">类型</th>
				<th width="15%">明细</th>
				<th width="5%">操作</th>
			</tr>
			<?php if (!$list || mysqli_num_rows($list) < 1): ?>
			<tr><td colspan="9" style="text-align:center;color:#999;padding:40px 0;"><i class="mdi mdi-clipboard-text-outline" style="font-size:32px;display:block;margin-bottom:8px;"></i>没有符合条件的日志记录</td></tr>
			<?php else: while ($row = $db->fetch_array($list)):
				$ck = (string)$row['log_cookie'];
				if (mb_strlen($ck, 'UTF-8') > 60) $ck = mb_substr($ck, 0, 60, 'UTF-8') . '…';
				$tl = isset($typeLabels[$row['log_type']]) ? $typeLabels[$row['log_type']] : $row['log_type'];
			?>
			<tr>
				<td><?php echo (int)$row['log_id']; ?></td>
				<td><?php echo $row['log_time'] ? date('Y-m-d H:i:s', (int)$row['log_time']) : '-'; ?></td>
				<td><?php echo htmlspecialchars($row['log_ip'], ENT_QUOTES, 'UTF-8'); ?></td>
				<td style="word-break:break-all;"><?php echo htmlspecialchars($row['log_uri'], ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($row['log_method'], ENT_QUOTES, 'UTF-8'); ?></td>
				<td style="word-break:break-all;color:#999;font-size:11px;"><?php echo htmlspecialchars($ck, ENT_QUOTES, 'UTF-8'); ?></td>
				<td>
					<?php
					$badge = '#999';
					if ($row['log_type']==='block') $badge='#e53935';
					elseif ($row['log_type']==='login') $badge='#1e88e5';
					elseif ($row['log_type']==='feedback') $badge='#43a047';
					elseif ($row['log_type']==='op') $badge='#fb8c00';
					?>
					<span class="badge" style="background:<?php echo $badge; ?>;color:#fff;"><?php echo htmlspecialchars($tl, ENT_QUOTES, 'UTF-8'); ?></span>
				</td>
				<td style="word-break:break-all;"><?php echo htmlspecialchars($row['log_detail'], ENT_QUOTES, 'UTF-8'); ?></td>
				<td>
					<button type="submit" class="button button-small border-red" name="do" value="del"
						onclick="this.form.elements['log_id'].value=<?php echo (int)$row['log_id']; ?>; return confirm('确认删除该条日志？');">删</button>
					<input type="hidden" name="log_id" value="0">
				</td>
			</tr>
			<?php endwhile; endif; ?>
		</table>

		<div class="panel-foot">
			<button type="submit" class="button button-small border-red" name="do" value="clear"
				onclick="return confirm('确认清空全部日志？此操作不可恢复！');">清空全部日志</button>
		</div>
	</form>

	<!-- 分页 -->
	<div class="panel-foot text-center">
		<ul class="pagination">
			<?php if ($page > 1): ?>
				<li><a href="index.php?/system/log<?php echo lg_qs(array('page'=>$page-1)); ?>">上一页</a></li>
			<?php else: ?><li><a>上一页</a></li><?php endif; ?>
			<?php for ($i = 1; $i <= $pageCount; $i++): ?>
				<li class="<?php if ($i===$page) echo 'active'; ?>"><a href="index.php?/system/log<?php echo lg_qs(array('page'=>$i)); ?>"><?php echo $i; ?></a></li>
			<?php endfor; ?>
			<?php if ($page < $pageCount): ?>
				<li><a href="index.php?/system/log<?php echo lg_qs(array('page'=>$page+1)); ?>">下一页</a></li>
			<?php else: ?><li><a>下一页</a></li><?php endif; ?>
		</ul>
	</div>
</div>
</div>
