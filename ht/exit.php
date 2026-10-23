<?php
include "../includes/Init.php";
include PHPDISK_ROOT.'includes/top.php';
$db -> ExitLogin();
?>

<title>您已成功退出系统</title>
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
<body>
<div id="ysm">
<div class="ht-layout">
        <div id="ysleft">
            <h2>注意</h2>
            <div style="padding:5px;">
                在公共场合登录后一定要退出。
            </div>
        </div>
        <main class="ht-main">
            <h1><img src='/ht/images/js.gif' width="30" height="32" alt="" />您已安全退出</h1>
            <p><a class="go" href="/ht/login.php">重新登录</a></p>
        </main>
</div>
<?php
include PHPDISK_ROOT."includes/footer.php";

?>
</body>

</html>