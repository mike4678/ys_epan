<?php
include "includes/Init.php";

// ===== 维护模式门禁（需求3-1）：site_maintenance=1 时向非 admin 访客展示维护页 =====
// 判断依据：admin 后台登录后会在 $_SESSION['username'] 写入用户名（admin/login_check.php L84）；
// 该会话与前台共享（同一 session 文件），故管理员访问前台时不被锁外。
if ($db->Info('site_maintenance') == '1' && empty($_SESSION['username'])) {
    $maintMsg = $db->Info('site_maintenance_msg');
    if ($maintMsg === '' || $maintMsg === null) { $maintMsg = '系统维护中，请稍后访问。'; }
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
    echo '<!DOCTYPE html><html lang="zh-cn"><head><meta charset="utf-8"><title>' . htmlspecialchars($db->Info('site_title'), ENT_QUOTES, 'UTF-8') . ' - 维护中</title>';
    echo '<style>body{font-family:"Microsoft YaHei",Arial,sans-serif;background:#f3f4f6;margin:0;padding:0;display:flex;align-items:center;justify-content:center;min-height:100vh;}';
    echo '.box{background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.08);padding:40px 48px;max-width:560px;text-align:center;}';
    echo 'h1{color:#1f2937;font-size:22px;margin:0 0 16px;}p{color:#4b5563;font-size:15px;line-height:1.8;margin:0;}</style>';
    echo '</head><body><div class="box"><h1>网站维护中</h1><p>' . nl2br(htmlspecialchars($maintMsg, ENT_QUOTES, 'UTF-8')) . '</p></div></body></html>';
    exit;
}

function Write_Data($name,$db)  //输出内容
{
	if($db->Info( $name.'_state') == 1) { //启用
	$data = $db->Info($name);
	if($data != NULL) {
		$value = "";
		$arr = explode(";",$data);
		foreach($arr as $u){
			$url = explode(",",$u);
			$url0 = htmlspecialchars($url[0], ENT_QUOTES, 'UTF-8');
			$url1 = htmlspecialchars($url[1], ENT_QUOTES, 'UTF-8');
			$value .= "<li><a href='" . $url1 . "'>" . $url0 . "</a></li>";
		}
	} else { $value = "无可用数据！"; return $value;}
}
}

function bkxs($name,$db) //是否显示板块
{
	if($db->Info( $name.'_state') == 1) { //启用
		echo "<div>";
		} else { echo "<div style='display:none'>"; }
}

//显示免费空间数
$query = $db->select_all('epan_product');
while ($row = $db->fetch_array($query))
{
	if($row[5] != 1 and $row[6] != 1)
		{
		$free = $row[1];
			}
		}

include PHPDISK_ROOT.'includes/top.php';
?>
<title><?php echo Website_title; ?> - 专业数据存储、交流平台</title>
<link rel="stylesheet" href="<?php echo PHPDISK_ROOT_WEB; ?>css/index.css">
<body>

<section class="hero">
  <div class="container hero-inner">
    <h1 class="hero-title"><?php echo Website_title; ?></h1>
    <p class="hero-sub">专业数据存储、交流平台</p>
    <p class="hero-desc">可以保存您的文件、网址、记事等，以便随时随地调用或与朋友、同事分享</p>
    <div class="hero-cta">
      <a class="btn btn-primary" href="/register.php">现在注册</a>
      <a class="btn btn-secondary" href="/k.php?name=admin">示例空间</a>
    </div>
    <p class="free-note">
      <?php if(!empty($free)) echo $free; ?> M 免费空间，可随时自助升级扩容
    </p>
  </div>
</section>

<section class="features container">
  <div class="feature-card">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    </div>
    <h3>直接登陆</h3>
    <p>以二级域名形式直达空间，地址形如：<br><code><?php echo Website; ?>k.php?name=用户名</code></p>
  </div>
  <div class="feature-card">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    </div>
    <h3>简单易用</h3>
    <p>点击数据项前的小图标，即可弹出编辑或查看窗口，无需复杂操作。</p>
  </div>
  <div class="feature-card">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
    </div>
    <h3>在线查看</h3>
    <p>文本、图片、视频等常见文件类型均可在线直接查看，无需下载。</p>
  </div>
  <div class="feature-card">
    <div class="feature-icon">
      <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>
    <h3>权限控制</h3>
    <p>通过登录密码与目录密码，灵活控制访问、上传与下载权限。</p>
  </div>
</section>

<section class="dynamic container">
  <div class="dyn-col">

    <?php bkxs('gg',$db);?>
    <div class="card">
      <h2 class="card-title">公告栏</h2>
      <ul class="card-list">
        <?php echo Write_Data('gg',$db); ?>
      </ul>
    </div>
    </div>

    <div class="card">
      <h2 class="card-title">首页快捷导航</h2>
      <ul class="card-list">
        <li><a href="/ht/login.php">登录空间后台</a></li>
        <li><a href="/ht/index.php?method=dlmm">设置登录密码</a></li>
        <li><a href="/ht/index.php?method=kjsj">空间升级</a></li>
        <li><a href="/ht/index.php?method=kjyq">空间延期</a></li>
        <li><a href="/ht/index.php?method=qx">设置访客权限</a></li>
        <li><a href="/ht/index.php?method=yfk">账户充值</a></li>
        <li><a href="/help/diy.php">如何设置自定义区</a></li>
      </ul>
    </div>
  </div>

  <div class="dyn-col">
    <div class="card">
      <h2 class="card-title">我们的客户</h2>
      <marquee class="kh-marquee" scrollamount='2' scrolldelay='30' direction='UP' height='130' onmouseover='this.stop()' onmouseout='this.start()'>
        <ul class="card-list">
        <?php
        $userList = $db->Info('khlb');
        $arr = explode(";",$userList);
        foreach($arr as $u){
            echo "<li>".htmlspecialchars($u, ENT_QUOTES, 'UTF-8')."</li>";
        }
        ?>
        </ul>
      </marquee>
    </div>

    <?php bkxs('yqlj',$db);?>
    <div class="card">
      <h2 class="card-title">友情链接</h2>
      <ul class="card-list">
        <?php echo Write_Data('yqlj',$db); ?>
      </ul>
    </div>
    </div>
  </div>
</section>

<?php
include PHPDISK_ROOT."includes/footer.php";
?>

</body>
</html>
