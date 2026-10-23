<?php
//*******************************************************************
// 用户文件管理（admin 后台）
// 路由入口: /admin/index.php?/system/upload
//   （zd_adminlist: t_e=system,m_e=upload 的 page 已重指到本文件）
// 功能:
//   ① 文件分页列表：用户名(关联 epan_users)、所在目录(关联 epan_folders, 根目录显示 "/")、
//      文件名、大小、上传时间、锁定状态
//   ② 筛选/搜索：按用户、按目录、按文件名关键字
//   ③ 删除：删 epan_files 行 + 物理文件(file_store_path 相对 upfile/ 解析；
//      为空/不存在则仅删行并提示)
//   ④ 锁定开关：复用已有列 epan_files.is_locked（未新增列）
// 说明: 本文件由 admin/index.php include（$db=AdminSystem 已就绪、会话已开启、
//       CSRF token 由全局 ob_start 自动注入 <form>）。
//*******************************************************************
if (!defined('source')) { header("Location: ../login.php"); exit; }

define('UF_SITE_ROOT', dirname(dirname(__DIR__)));          // D:\Desktop\pan
define('UF_UPFILE_DIR', UF_SITE_ROOT . '/upfile');          // 物理文件根目录

//---------------- 工具函数 ----------------
function uf_size($b) {
    $b = (int)$b;
    if ($b <= 0) return '0 B';
    $u = array('B','KB','MB','GB','TB');
    $i = (int)floor(log($b, 1024));
    if ($i > 4) $i = 4;
    return round($b / pow(1024, $i), ($i == 0 ? 0 : 2)) . ' ' . $u[$i];
}
function uf_phys_path($store_path) {
    // 将 file_store_path 解析为物理绝对路径；不安全/为空返回 null
    $rel = trim((string)$store_path);
    if ($rel === '') return null;
    $rel = str_replace('\\', '/', $rel);
    if (strpos($rel, '..') !== false) return null;        // 防目录穿越
    if (preg_match('#^[a-zA-Z]:#', $rel) || strpos($rel, '/') === 0) return null; // 拒绝绝对路径
    // 统一相对 upfile/
    if (stripos($rel, 'upfile/') === 0) $rel = substr($rel, 7);
    return UF_UPFILE_DIR . '/' . ltrim($rel, '/');
}
function uf_flash($msg) { $_SESSION['__uf_msg'] = $msg; }
function uf_get_flash() { $m = isset($_SESSION['__uf_msg']) ? $_SESSION['__uf_msg'] : ''; unset($_SESSION['__uf_msg']); return $m; }

//---------------- POST：删除 / 锁定 ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $do = isset($_POST['do']) ? $_POST['do'] : '';

    if ($do === 'del') {
        $ids = array();
        if (!empty($_POST['file_id'])) $ids[] = (int)$_POST['file_id'];
        if (!empty($_POST['file_ids']) && is_array($_POST['file_ids'])) {
            foreach ($_POST['file_ids'] as $v) $ids[] = (int)$v;
        }
        $ids = array_values(array_unique(array_filter($ids)));
        $delRow = 0; $delPhys = 0; $missPhys = 0;
        foreach ($ids as $fid) {
            $fid = (int)$fid;
            $rs = $db->query("SELECT file_id, file_name, file_store_path FROM epan_files WHERE file_id=" . $fid . " LIMIT 1");
            $row = $rs ? $db->fetch_array($rs) : null;
            if (!$row) continue;
            $phys = uf_phys_path($row['file_store_path']);
            if ($phys !== null && is_file($phys)) {
                if (@unlink($phys)) $delPhys++; else uf_flash('警告：物理文件删除失败：' . $row['file_store_path']);
            } elseif ($phys !== null) {
                $missPhys++;   // 记录指向的物理文件本就不存在
            }
            $d = $db->query("DELETE FROM epan_files WHERE file_id=" . (int)$row['file_id'] . " LIMIT 1");
            if ($d && $db->affected_rows() >= 1) $delRow++;
        }
        $msg = "已删除 {$delRow} 条记录";
        if ($delPhys) $msg .= "，删除物理文件 {$delPhys} 个";
        if ($missPhys) $msg .= "（{$missPhys} 条记录无对应物理文件，仅删除记录）";
        uf_flash($msg);
    } elseif ($do === 'lock') {
        $fid = (int)(isset($_POST['file_id']) ? $_POST['file_id'] : 0);
        if ($fid > 0) {
            $db->query("UPDATE epan_files SET is_locked = 1 - is_locked WHERE file_id=" . $fid . " LIMIT 1");
            uf_flash('已切换锁定状态');
        }
    }
    // 回跳本页（保留筛选）
    $back = 'index.php?/system/upload';
    $qs = array();
    foreach (array('userid','folder_id','kw','page') as $k) {
        if (!empty($_POST['back_' . $k])) $qs[$k] = $_POST['back_' . $k];
    }
    if ($qs) $back .= '&' . http_build_query($qs);
    header('Location: ' . $back);
    exit;
}

//---------------- GET：筛选参数 ----------------
$f_userid   = isset($_GET['userid'])   ? (int)$_GET['userid']   : 0;
$f_folder   = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : -1; // -1=全部
$f_kw       = isset($_GET['kw'])       ? trim((string)$_GET['kw']) : '';
$page       = isset($_GET['page'])      ? max(1, (int)$_GET['page']) : 1;
$pageSize   = 15;
$offset     = ($page - 1) * $pageSize;

$where = " WHERE 1=1 ";
if ($f_userid > 0) $where .= " AND f.userid=" . $f_userid . " ";
if ($f_folder >= 0) $where .= " AND f.folder_id=" . $f_folder . " ";
if ($f_kw !== '')  $where .= " AND f.file_name LIKE '%" . $db->escape($f_kw) . "%' ";

// 总数
$cntRs = $db->query("SELECT COUNT(*) c FROM epan_files f " . $where);
$cntRow = $cntRs ? $db->fetch_array($cntRs) : array('c' => 0);
$total = (int)$cntRow['c'];
$pageCount = max(1, (int)ceil($total / $pageSize));
if ($page > $pageCount) { $page = $pageCount; $offset = ($page - 1) * $pageSize; }

// 列表
$list = $db->query(
    "SELECT f.file_id, f.file_name, f.file_real_name, f.file_extension, f.file_size,
            f.file_time, f.userid, f.folder_id, f.is_locked, f.file_store_path,
            u.username, d.folder_name
     FROM epan_files f
     LEFT JOIN epan_users u ON u.userid = f.userid
     LEFT JOIN epan_folders d ON d.folder_id = f.folder_id
     " . $where . "
     ORDER BY f.file_id DESC
     LIMIT " . $offset . "," . $pageSize
);

// 筛选下拉数据：有文件的用户 / 全部目录
$userOpts = $db->query("SELECT DISTINCT f.userid, u.username FROM epan_files f LEFT JOIN epan_users u ON u.userid=f.userid ORDER BY f.userid");
$folderOpts = $db->query("SELECT folder_id, folder_name, userid FROM epan_folders ORDER BY userid, folder_id");

function uf_qs($over = array()) {
    $p = array();
    foreach (array('userid','folder_id','kw','page') as $k) {
        if (isset($_GET[$k]) && $_GET[$k] !== '') $p[$k] = $_GET[$k];
    }
    $p = array_merge($p, $over);
    return $p ? '&' . http_build_query($p) : '';
}
?>
<div class="panel admin-panel">
    <div class="panel-head"><strong><i class="mdi mdi-file-multiple" style="color:#5e72e4;"></i> 用户文件管理</strong><span class="small" style="color:#888">（共 <?php echo $total; ?> 条记录）</span></div>

    <?php $flash = uf_get_flash(); if ($flash): ?>
    <div class="alert alert-success alert-dismissible" style="margin:8px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="mdi mdi-check-circle-outline"></i> <?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php endif; ?>

    <!-- 筛选栏 -->
    <form method="get" action="index.php" class="padding border-bottom" style="margin-bottom:0;">
        <input type="hidden" name="/system/upload" value="">
        <label>用户：
            <select name="userid" class="input" style="height:30px;">
                <option value="0">全部用户</option>
                <?php while ($uo = $db->fetch_array($userOpts)): ?>
                    <option value="<?php echo (int)$uo['userid']; ?>" <?php if ($f_userid===(int)$uo['userid']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($uo['username'] . ' (uid=' . $uo['userid'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label>目录：
            <select name="folder_id" class="input" style="height:30px;">
                <option value="-1">全部目录</option>
                <option value="0" <?php if ($f_folder===0) echo 'selected'; ?>>/ (根目录)</option>
                <?php while ($fo = $db->fetch_array($folderOpts)): ?>
                    <option value="<?php echo (int)$fo['folder_id']; ?>" <?php if ($f_folder===(int)$fo['folder_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars('[' . $fo['folder_name'] . '] (#' . $fo['folder_id'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label>文件名：
            <input type="text" name="kw" class="input" style="height:30px;" value="<?php echo htmlspecialchars($f_kw, ENT_QUOTES, 'UTF-8'); ?>" placeholder="文件名关键字">
        </label>
        <input type="submit" class="button button-small border-blue" value="筛选">
        <a class="button button-small" href="index.php?/system/upload">重置</a>
    </form>

    <form method="post" action="index.php?/system/upload">
        <?php
        // 回传筛选状态（供 POST 后回跳保留条件）
        foreach (array('userid'=>$f_userid,'folder_id'=>$f_folder,'kw'=>$f_kw,'page'=>$page) as $bk=>$bv) {
            echo '<input type="hidden" name="back_' . $bk . '" value="' . htmlspecialchars((string)$bv, ENT_QUOTES, 'UTF-8') . '">';
        }
        ?>
        <table class="table table-striped table-hover">
            <tr>
                <th width="3%"><input type="checkbox" id="uf_checkall"></th>
                <th width="12%">用户名</th>
                <th width="16%">所在目录</th>
                <th width="24%">文件名</th>
                <th width="9%">大小</th>
                <th width="15%">上传时间</th>
                <th width="8%">状态</th>
                <th width="13%">操作</th>
            </tr>
            <?php if (!$list || mysqli_num_rows($list) < 1): ?>
            <tr><td colspan="8" style="text-align:center;color:#999;padding:40px 0;"><i class="mdi mdi-file-tree" style="font-size:32px;display:block;margin-bottom:8px;"></i>没有符合条件的文件记录</td></tr>
            <?php else: while ($row = $db->fetch_array($list)):
                $folderName = ((int)$row['folder_id'] === 0 || $row['folder_name'] === null || $row['folder_name'] === '')
                    ? '/' : $row['folder_name'];
            ?>
            <tr>
                <td><input type="checkbox" name="file_ids[]" value="<?php echo (int)$row['file_id']; ?>"></td>
                <td><?php echo htmlspecialchars($row['username'] ? $row['username'] : ('uid='.$row['userid']), ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($folderName, ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <?php echo htmlspecialchars($row['file_name'], ENT_QUOTES, 'UTF-8'); ?>
                    <?php if ($row['file_extension']): ?><span style="color:#aaa;font-size:12px;">.<?php echo htmlspecialchars($row['file_extension'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                    <?php if ($row['file_store_path']): ?><div style="color:#bbb;font-size:11px;">phys:<?php echo htmlspecialchars($row['file_store_path'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                </td>
                <td><?php echo uf_size($row['file_size']); ?></td>
                <td><?php echo $row['file_time'] ? date('Y-m-d H:i', (int)$row['file_time']) : '-'; ?></td>
                <td>
                    <?php if ((int)$row['is_locked'] === 1): ?><span class="badge" style="background:#e53935;color:#fff;">已锁定</span>
                    <?php else: ?><span class="badge" style="background:#43a047;color:#fff;">正常</span><?php endif; ?>
                </td>
                <td>
                    <button type="submit" class="button button-small border-yellow" name="do" value="lock" formnovalidate
                        onclick="this.form.elements['file_id'].value=<?php echo (int)$row['file_id']; ?>">
                        <?php echo ((int)$row['is_locked']===1?'解锁':'锁定'); ?>
                    </button>
                    <button type="submit" class="button button-small border-red" name="do" value="del"
                        onclick="this.form.elements['file_id'].value=<?php echo (int)$row['file_id']; ?>; return confirm('确认删除该文件？将删除数据库记录及对应物理文件！');">删除</button>
                    <input type="hidden" name="file_id" value="0">
                </td>
            </tr>
            <?php endwhile; endif; ?>
        </table>

        <div class="panel-foot">
            <button type="submit" class="button button-small border-red" name="do" value="del"
                onclick="return confirm('确认删除选中的文件？');">批量删除</button>
        </div>
    </form>

    <!-- 分页 -->
    <div class="panel-foot text-center">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li><a href="index.php?/system/upload<?php echo uf_qs(array('page'=>$page-1)); ?>">上一页</a></li>
            <?php else: ?><li><a>上一页</a></li><?php endif; ?>
            <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <li class="<?php if ($i===$page) echo 'active'; ?>"><a href="index.php?/system/upload<?php echo uf_qs(array('page'=>$i)); ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($page < $pageCount): ?>
                <li><a href="index.php?/system/upload<?php echo uf_qs(array('page'=>$page+1)); ?>">下一页</a></li>
            <?php else: ?><li><a>下一页</a></li><?php endif; ?>
        </ul>
    </div>
</div>
<script>
(function(){
    var all = document.getElementById('uf_checkall');
    if (all) all.onchange = function(){
        document.querySelectorAll('input[name="file_ids[]"]').forEach(function(c){ c.checked = all.checked; });
    };
})();
</script>
</div>
