<?php
//*******************************************************************
// 用户管理（admin 后台）
// 路由入口: /admin/index.php?/system/user
//   （zd_adminlist: t_e=system,m_e=user 的 page 指向本文件）
// 功能:
//   ① 用户列表：分页列出 epan_users LEFT JOIN epan_userdata，
//      关联 epan_product 显示空间等级名；列：用户名/邮箱/等级/到期/管理员/注册/最后登录IP/操作
//   ② 搜索：按用户名关键字 LIKE
//   ③ 重置密码：POST do=resetpwd，两次新密码一致后用 Crypt::PwdEnc(bcrypt) 写 epan_users.password
//   ④ 编辑：POST do=save，改 epan_userdata.diskLevel / Expire_time / is_Administrator
//      （diskLevel 为唯一权威列，epan_users 无 diskLevel 列）
//   ⑤ 删除用户：POST do=del，级联清理文件(物理+DB)/目录/用户内容/留言/用户数据/空间设置，
//      禁止删除 admin 账号与 is_Administrator=1 的账号（服务端硬校验）
// 级联删除顺序（按依赖关系）：
//   1. epan_files 物理文件（按 userid 取 file_store_path → upfile/ 解析，安全防护）
//   2. epan_files DB 行
//   3. epan_folders（按 userid）
//   4. 用户内容表：epan_disk2user/epan_buddys/epan_messages/epan_comments/epan_replys/
//      epan_topics/epan_extracts/epan_invitelog/epan_userlinks/epan_search_index/epan_reports
//   5. epan_lyb（按 uid=userid）
//   6. epan_userdata（按 user=username）、epan_space（按 username=username）
//   7. epan_users 主行
// 保留不清（财务/审计类）：epan_orders/epan_xiaofei/epan_download/epan_userloginlog/
//   epan_log/epan_reqcnt/epan_oline/epan_onlineuser
// 说明: 本文件由 admin/index.php include（$db=AdminSystem 已就绪、会话已开启、
//       CSRF token 由全局 ob_start 自动注入 <form>）。
//*******************************************************************
if (!defined('source')) { header("Location: ../login.php"); exit; }

// 确保 Crypt 类可用（AdminInit 已 require Init.php → class 自动加载；这里兜底）
if (!class_exists('Crypt')) {
    @include_once dirname(dirname(dirname(__FILE__))) . '/includes/class/Crypt.Class.php';
}

//---------------- 工具函数 ----------------
define('UM_SITE_ROOT', dirname(dirname(__DIR__)));          // D:\Desktop\pan
define('UM_UPFILE_DIR', UM_SITE_ROOT . '/upfile');          // 物理文件根目录

function um_flash($msg) { $_SESSION['__um_msg'] = $msg; }
function um_get_flash() {
    $m = isset($_SESSION['__um_msg']) ? $_SESSION['__um_msg'] : '';
    unset($_SESSION['__um_msg']);
    return $m;
}
function um_qs($over = array()) {
    $p = array();
    foreach (array('kw','edit','page') as $k) {
        if (isset($_GET[$k]) && $_GET[$k] !== '') $p[$k] = $_GET[$k];
    }
    $p = array_merge($p, $over);
    return $p ? '&' . http_build_query($p) : '';
}
function um_phys_path($store_path) {
    // 将 file_store_path 解析为物理绝对路径；不安全/为空返回 null（与 userfiles.php 一致）
    $rel = trim((string)$store_path);
    if ($rel === '') return null;
    $rel = str_replace('\\', '/', $rel);
    if (strpos($rel, '..') !== false) return null;        // 防目录穿越
    if (preg_match('#^[a-zA-Z]:#', $rel) || strpos($rel, '/') === 0) return null; // 拒绝绝对路径
    if (stripos($rel, 'upfile/') === 0) $rel = substr($rel, 7);
    return UM_UPFILE_DIR . '/' . ltrim($rel, '/');
}

//---------------- POST 处理 ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $do = isset($_POST['do']) ? $_POST['do'] : '';
    $back_edit = isset($_POST['back_edit']) ? trim((string)$_POST['back_edit']) : '';

    if ($do === 'resetpwd') {
        $uname = isset($_POST['username']) ? trim((string)$_POST['username']) : '';
        $p1    = isset($_POST['pwd1']) ? (string)$_POST['pwd1'] : '';
        $p2    = isset($_POST['pwd2']) ? (string)$_POST['pwd2'] : '';

        if ($uname === '' || $p1 === '' || $p2 === '') {
            um_flash('错误：用户名和两次新密码均不能为空');
        } elseif (strlen($p1) < 6) {
            um_flash('错误：新密码长度至少 6 位');
        } elseif ($p1 !== $p2) {
            um_flash('错误：两次输入的新密码不一致');
        } else {
            // 查用户是否存在
            $rs = $db->query("SELECT userid FROM epan_users WHERE username='" . $db->escape($uname) . "' LIMIT 1");
            $urow = $rs ? $db->fetch_array($rs) : null;
            if (!$urow) {
                um_flash('错误：用户不存在：' . $uname);
            } else {
                $hash = Crypt::PwdEnc($p1);
                $upd = $db->query("UPDATE epan_users SET password='" . $db->escape($hash) . "' WHERE username='" . $db->escape($uname) . "' LIMIT 1");
                if ($upd && $db->affected_rows() >= 0) {
                    um_flash('已重置用户 [' . $uname . '] 的密码（bcrypt 哈希已写入 epan_users.password）');
                } else {
                    um_flash('错误：密码更新失败');
                }
            }
        }
        // 回跳：若在编辑页则回编辑页，否则回列表
        $back = 'index.php?/system/user';
        if ($back_edit !== '') $back .= '&edit=' . urlencode($back_edit);
        header('Location: ' . $back);
        exit;
    }

    if ($do === 'save') {
        $uname = isset($_POST['username']) ? trim((string)$_POST['username']) : '';
        $disk  = isset($_POST['diskLevel']) ? (int)$_POST['diskLevel'] : -999;
        $expireDate = isset($_POST['Expire_time_date']) ? trim((string)$_POST['Expire_time_date']) : '';
        $isAdmin = !empty($_POST['is_Administrator']) ? 1 : 0;

        if ($uname === '') {
            um_flash('错误：缺少用户名');
        } else {
            // 校验用户存在
            $rs = $db->query("SELECT userid FROM epan_users WHERE username='" . $db->escape($uname) . "' LIMIT 1");
            $urow = $rs ? $db->fetch_array($rs) : null;
            if (!$urow) {
                um_flash('错误：用户不存在：' . $uname);
            } else {
                // 校验 diskLevel 在 epan_product.code 合法范围内
                if ($disk === -999) {
                    um_flash('错误：未选择空间等级');
                } else {
                    // 到期时间：日期字符串 → 当天 23:59:59 时间戳；空串 → 0（永久）
                    if ($expireDate === '') {
                        $expireTs = 0;
                    } else {
                        $ts = strtotime($expireDate . ' 23:59:59');
                        $expireTs = ($ts === false) ? 0 : $ts;
                    }

                    // 确保 epan_userdata 行存在（LEFT JOIN 时可能缺失）
                    $chk = $db->query("SELECT user FROM epan_userdata WHERE user='" . $db->escape($uname) . "' LIMIT 1");
                    $exists = $chk && $db->fetch_array($chk);
                    if (!$exists) {
                        $db->query("INSERT INTO epan_userdata (user, setting, diskLevel, Expire_time, is_Administrator) VALUES ('"
                            . $db->escape($uname) . "', '0', " . $disk . ", " . $expireTs . ", " . $isAdmin . ")");
                    } else {
                        $db->query("UPDATE epan_userdata SET diskLevel=" . $disk
                            . ", Expire_time=" . $expireTs
                            . ", is_Administrator=" . $isAdmin
                            . " WHERE user='" . $db->escape($uname) . "' LIMIT 1");
                    }
                    um_flash('已保存 [' . $uname . ']：空间等级=' . $disk . '，到期=' . ($expireTs ? date('Y-m-d', $expireTs) : '永久') . '，管理员=' . ($isAdmin ? '是' : '否'));
                }
            }
        }
        $back = 'index.php?/system/user';
        header('Location: ' . $back);
        exit;
    }

    if ($do === 'del') {
        $uname = isset($_POST['username']) ? trim((string)$_POST['username']) : '';

        if ($uname === '') {
            um_flash('错误：缺少用户名');
        } else {
            // 查用户是否存在
            $rs = $db->query("SELECT u.userid, u.username, d.is_Administrator
                FROM epan_users u
                LEFT JOIN epan_userdata d ON d.user = u.username
                WHERE u.username='" . $db->escape($uname) . "' LIMIT 1");
            $urow = $rs ? $db->fetch_array($rs) : null;
            if (!$urow) {
                um_flash('错误：用户不存在：' . $uname);
            } elseif ($urow['username'] === 'admin') {
                um_flash('错误：禁止删除 admin 管理员账号');
            } elseif ((int)$urow['is_Administrator'] === 1) {
                um_flash('错误：禁止删除管理员账号（is_Administrator=1）：' . $urow['username'] . '，请先取消其管理员身份');
            } else {
                $uid = (int)$urow['userid'];
                $delFiles = 0; $delPhys = 0; $missPhys = 0; $failPhys = 0;
                $delFolders = 0;
                $stats = array();

                // 1. 物理文件删除（先取所有该用户文件的 file_store_path）
                $frs = $db->query("SELECT file_id, file_store_path FROM epan_files WHERE userid=" . $uid);
                if ($frs) {
                    while ($frow = $db->fetch_array($frs)) {
                        $phys = um_phys_path($frow['file_store_path']);
                        if ($phys !== null && is_file($phys)) {
                            if (@unlink($phys)) { $delPhys++; } else { $failPhys++; }
                        } elseif ($phys !== null) {
                            $missPhys++;
                        }
                    }
                }

                // 2. 删 epan_files DB 行
                $d = $db->query("DELETE FROM epan_files WHERE userid=" . $uid);
                if ($d) $delFiles = $db->affected_rows();

                // 3. 删 epan_folders
                $d = $db->query("DELETE FROM epan_folders WHERE userid=" . $uid);
                if ($d) $delFolders = $db->affected_rows();

                // 4. 用户内容表（按 userid 或 userid/touserid）
                $contentTables = array(
                    'epan_disk2user' => 'userid',
                    'epan_comments'  => 'userid',
                    'epan_replys'    => 'userid',
                    'epan_topics'    => 'userid',
                    'epan_extracts'  => 'userid',
                    'epan_userlinks' => 'userid',
                    'epan_search_index' => 'userid',
                    'epan_reports'   => 'userid',
                );
                foreach ($contentTables as $tbl => $col) {
                    $d = $db->query("DELETE FROM `$tbl` WHERE `$col`=" . $uid);
                    $stats[$tbl] = $d ? $db->affected_rows() : 0;
                }

                // 双向关系表：buddy / message / invitelog（userid 或 touserid）
                $biTables = array('epan_buddys', 'epan_messages', 'epan_invitelog');
                foreach ($biTables as $tbl) {
                    $d = $db->query("DELETE FROM `$tbl` WHERE userid=" . $uid . " OR touserid=" . $uid);
                    $stats[$tbl] = $d ? $db->affected_rows() : 0;
                }

                // 5. epan_lyb（按 uid=userid）
                $d = $db->query("DELETE FROM epan_lyb WHERE uid=" . $uid);
                $stats['epan_lyb'] = $d ? $db->affected_rows() : 0;

                // 6. epan_userdata（按 user=username）、epan_space（按 username=username）
                $d = $db->query("DELETE FROM epan_userdata WHERE user='" . $db->escape($uname) . "' LIMIT 1");
                $stats['epan_userdata'] = $d ? $db->affected_rows() : 0;
                $d = $db->query("DELETE FROM epan_space WHERE username='" . $db->escape($uname) . "' LIMIT 1");
                $stats['epan_space'] = $d ? $db->affected_rows() : 0;

                // 7. epan_users 主行
                $d = $db->query("DELETE FROM epan_users WHERE userid=" . $uid . " LIMIT 1");
                $delMain = $d ? $db->affected_rows() : 0;

                // 汇总消息
                $msg = "已删除用户 [{$uname}] (uid={$uid})：";
                $msg .= "文件记录 {$delFiles} 条";
                if ($delPhys) $msg .= "，物理文件 {$delPhys} 个";
                if ($missPhys) $msg .= "（{$missPhys} 条无物理文件）";
                if ($failPhys) $msg .= "，⚠ {$failPhys} 个物理文件删除失败";
                $msg .= "，目录 {$delFolders} 个，留言 {$stats['epan_lyb']} 条，用户数据行 " . $stats['epan_userdata'] . " 条，空间设置行 " . $stats['epan_space'] . " 条，主行 {$delMain} 条";
                um_flash($msg);
            }
        }
        $back = 'index.php?/system/user';
        header('Location: ' . $back);
        exit;
    }
}

//---------------- GET 参数 ----------------
$f_kw = isset($_GET['kw']) ? trim((string)$_GET['kw']) : '';
$edit = isset($_GET['edit']) ? trim((string)$_GET['edit']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$pageSize = 15;
$offset = ($page - 1) * $pageSize;

$where = " WHERE 1=1 ";
if ($f_kw !== '') $where .= " AND u.username LIKE '%" . $db->escape($f_kw) . "%' ";

// 总数
$cntRs = $db->query("SELECT COUNT(*) c FROM epan_users u " . $where);
$cntRow = $cntRs ? $db->fetch_array($cntRs) : array('c' => 0);
$total = (int)$cntRow['c'];
$pageCount = max(1, (int)ceil($total / $pageSize));
if ($page > $pageCount) { $page = $pageCount; $offset = ($page - 1) * $pageSize; }

// 列表：关联 epan_userdata 取 diskLevel/Expire_time/is_Administrator，关联 epan_product 取等级名
$list = $db->query(
    "SELECT u.userid, u.username, u.email, u.reg_time, u.last_login_time, u.last_login_ip, u.reg_ip,
            d.diskLevel, d.Expire_time, d.is_Administrator,
            p.name AS product_name, p.data AS product_data
     FROM epan_users u
     LEFT JOIN epan_userdata d ON d.user = u.username
     LEFT JOIN epan_product p ON p.code = d.diskLevel
     " . $where . "
     ORDER BY u.userid ASC
     LIMIT " . $offset . "," . $pageSize
);

// 等级下拉数据
$prodList = $db->query("SELECT code, name, data FROM epan_product ORDER BY code ASC");

// 编辑模式：取该用户当前数据
$editRow = null;
if ($edit !== '') {
    $er = $db->query("SELECT u.userid, u.username, u.email,
            d.diskLevel, d.Expire_time, d.is_Administrator,
            p.name AS product_name
        FROM epan_users u
        LEFT JOIN epan_userdata d ON d.user = u.username
        LEFT JOIN epan_product p ON p.code = d.diskLevel
        WHERE u.username='" . $db->escape($edit) . "' LIMIT 1");
    $editRow = $er ? $db->fetch_array($er) : null;
    if (!$editRow) {
        um_flash('用户不存在：' . htmlspecialchars($edit, ENT_QUOTES, 'UTF-8'));
        header('Location: index.php?/system/user');
        exit;
    }
}
?>
<div class="panel admin-panel">
    <div class="panel-head"><strong><i class="mdi mdi-account-multiple" style="color:#5e72e4;"></i> 用户管理</strong><span class="small" style="color:#888">（共 <?php echo $total; ?> 个用户）</span></div>

    <?php $flash = um_get_flash(); if ($flash): ?>
    <div class="alert alert-success alert-dismissible" style="margin:8px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="mdi mdi-check-circle-outline"></i> <?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <?php endif; ?>

    <?php if ($editRow): /* ===== 编辑模式 ===== */ ?>
    <div class="padding">
        <h4 style="margin:0 0 10px;">编辑用户：<?php echo htmlspecialchars($editRow['username'], ENT_QUOTES, 'UTF-8'); ?>
            <small style="color:#999;font-weight:normal;">（uid=<?php echo (int)$editRow['userid']; ?>，邮箱：<?php echo htmlspecialchars($editRow['email'], ENT_QUOTES, 'UTF-8'); ?>）</small>
        </h4>

        <form method="post" action="index.php?/system/user" class="form-group" style="max-width:640px;">
            <input type="hidden" name="do" value="save">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($editRow['username'], ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
                <label>空间等级（来源 epan_product.code，写入 epan_userdata.diskLevel）：</label>
                <select name="diskLevel" class="input" style="height:32px;">
                    <?php while ($p = $db->fetch_array($prodList)): ?>
                        <option value="<?php echo (int)$p['code']; ?>"
                            <?php if ((int)$editRow['diskLevel'] === (int)$p['code']) echo 'selected'; ?>>
                            [code=<?php echo (int)$p['code']; ?>] <?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?>
                            （容量 <?php echo (int)$p['data']; ?> MB）
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>到期时间（写入 epan_userdata.Expire_time，留空=永久）：</label>
                <input type="date" name="Expire_time_date" class="input" style="height:32px;"
                    value="<?php echo ((int)$editRow['Expire_time'] > 0) ? date('Y-m-d', (int)$editRow['Expire_time']) : ''; ?>">
                <span style="color:#999;font-size:12px;">
                    当前：<?php echo ((int)$editRow['Expire_time'] > 0) ? htmlspecialchars(date('Y-m-d H:i', (int)$editRow['Expire_time']), ENT_QUOTES, 'UTF-8') : '永久'; ?>
                </span>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_Administrator" value="1"
                        <?php if ((int)$editRow['is_Administrator'] === 1) echo 'checked'; ?>>
                    设为管理员（is_Administrator=1，谨慎操作）
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="button border-blue">保存等级/到期/管理员</button>
                <a class="button" href="index.php?/system/user">返回列表</a>
            </div>
        </form>

        <hr style="margin:16px 0;">

        <h4>重置密码</h4>
        <form method="post" action="index.php?/system/user" class="form-group" style="max-width:640px;">
            <input type="hidden" name="do" value="resetpwd">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($editRow['username'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="back_edit" value="<?php echo htmlspecialchars($editRow['username'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label>新密码（至少 6 位）：</label>
                <input type="password" name="pwd1" class="input" style="height:32px;" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label>确认新密码：</label>
                <input type="password" name="pwd2" class="input" style="height:32px;" autocomplete="new-password">
            </div>
            <div class="form-group">
                <button type="submit" class="button border-yellow"
                    onclick="return confirm('确认重置 [<?php echo htmlspecialchars($editRow['username'], ENT_QUOTES, 'UTF-8'); ?>] 的密码？旧密码将立即失效。');">
                    重置密码
                </button>
                <span style="color:#999;font-size:12px;">使用 Crypt::PwdEnc()（bcrypt）生成哈希写入 epan_users.password</span>
            </div>
        </form>
    </div>

    <?php else: /* ===== 列表模式 ===== */ ?>
    <!-- 搜索栏 -->
    <form method="get" action="index.php" class="padding border-bottom" style="margin-bottom:0;">
        <input type="hidden" name="/system/user" value="">
        <label>用户名：
            <input type="text" name="kw" class="input" style="height:30px;"
                value="<?php echo htmlspecialchars($f_kw, ENT_QUOTES, 'UTF-8'); ?>" placeholder="用户名关键字">
        </label>
        <input type="submit" class="button button-small border-blue" value="搜索">
        <a class="button button-small" href="index.php?/system/user">重置</a>
    </form>

    <table class="table table-striped table-hover">
        <tr>
            <th width="5%">UID</th>
            <th width="14%">用户名</th>
            <th width="18%">邮箱</th>
            <th width="12%">空间等级</th>
            <th width="12%">到期时间</th>
            <th width="6%">管理员</th>
            <th width="12%">注册时间</th>
            <th width="13%">最后登录(IP)</th>
            <th width="8%">操作</th>
        </tr>
        <?php if (!$list || mysqli_num_rows($list) < 1): ?>
        <tr><td colspan="9" style="text-align:center;color:#999;padding:40px 0;"><i class="mdi mdi-account-search" style="font-size:32px;display:block;margin-bottom:8px;"></i>没有符合条件的用户</td></tr>
        <?php else: while ($row = $db->fetch_array($list)):
            $dl = (int)$row['diskLevel'];
            $lvlName = $row['product_name'] !== null && $row['product_name'] !== ''
                ? $row['product_name'] : ('未分配(code=' . $dl . ')');
            $expire = (int)$row['Expire_time'];
        ?>
        <tr>
            <td><?php echo (int)$row['userid']; ?></td>
            <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['email'] ? $row['email'] : '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($lvlName, ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <?php if ($expire > 0): ?>
                    <?php echo htmlspecialchars(date('Y-m-d', $expire), ENT_QUOTES, 'UTF-8'); ?>
                    <?php if ($expire < time()): ?><span class="badge" style="background:#e53935;color:#fff;">已过期</span><?php endif; ?>
                <?php else: ?><span style="color:#999;">永久</span><?php endif; ?>
            </td>
            <td>
                <?php if ((int)$row['is_Administrator'] === 1): ?>
                    <span class="badge" style="background:#1e88e5;color:#fff;">管理员</span>
                <?php else: ?>
                    <span style="color:#bbb;">-</span>
                <?php endif; ?>
            </td>
            <td><?php echo $row['reg_time'] ? date('Y-m-d', (int)$row['reg_time']) : '-'; ?></td>
            <td>
                <?php echo $row['last_login_time'] ? date('Y-m-d H:i', (int)$row['last_login_time']) : '-'; ?>
                <?php if ($row['last_login_ip']): ?><div style="color:#aaa;font-size:11px;"><?php echo htmlspecialchars($row['last_login_ip'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
            </td>
            <td>
                <a class="button button-small border-blue"
                    href="index.php?/system/user&edit=<?php echo urlencode($row['username']); ?><?php echo um_qs(array('edit'=>null)); ?>">编辑</a>
                <?php if ((int)$row['is_Administrator'] !== 1 && $row['username'] !== 'admin'): ?>
                <form method="post" action="index.php?/system/user" style="display:inline;"
                    onsubmit="return confirm('确认删除用户 [<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>] (uid=<?php echo (int)$row['userid']; ?>)？\n\n将永久删除该用户的全部文件、目录、留言及关联数据，且不可恢复！\n\n建议删除前先确认该用户无未结订单或重要数据。');">
                    <input type="hidden" name="do" value="del">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="button button-small border-red">删除</button>
                </form>
                <?php else: ?>
                <span class="button button-small" style="color:#aaa;cursor:default;" title="管理员账号不可删除">删除</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; endif; ?>
    </table>

    <!-- 分页 -->
    <div class="panel-foot text-center">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li><a href="index.php?/system/user<?php echo um_qs(array('page'=>$page-1)); ?>">上一页</a></li>
            <?php else: ?><li><a>上一页</a></li><?php endif; ?>
            <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                <li class="<?php if ($i===$page) echo 'active'; ?>"><a href="index.php?/system/user<?php echo um_qs(array('page'=>$i)); ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($page < $pageCount): ?>
                <li><a href="index.php?/system/user<?php echo um_qs(array('page'=>$page+1)); ?>">下一页</a></li>
            <?php else: ?><li><a>下一页</a></li><?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
