<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="<?php echo PHPDISK_ROOT_WEB ?>images/favicon.ico">
    <meta name="keywords" content="<?php echo htmlspecialchars($db -> Info('meta_keywords'), ENT_QUOTES, 'UTF-8');?>" />
    <meta name="description" content="<?php echo htmlspecialchars($db -> Info('meta_description'), ENT_QUOTES, 'UTF-8');?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" href="<?php echo PHPDISK_ROOT_WEB ?>css/common.css">
    <link rel="stylesheet" href="<?php echo PHPDISK_ROOT_WEB ?>css/design.css">
	<script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.9.1.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/artDialog.js?skin=default"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/iframeTools.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/jquery-1.7.1.min.js"></script>
    <script src="<?php echo PHPDISK_ROOT_WEB ?>js/ajax-dq.js"></script>
</head>
<div id="ysm">
<header class="phd-header">
  <div class="phd-inner">
    <div class="phd-brand">
      <a href="/" class="phd-brand-link">
        <span id="logo" class="phd-logo"></span>
        <span class="phd-name"><?php echo Website_title; ?></span>
      </a>
    </div>
    <button type="button" class="phd-toggle" id="phdToggle" aria-label="展开菜单"><span></span><span></span><span></span></button>
    <nav class="phd-nav" id="phdNav">
      <a href='/'>首页</a>
      <a href='/register.php'>注册</a>
      <a href='/list_cp.php'>产品列表</a>
      <a href='/help/'>操作指南</a>
      <a href='/ht/'>空间后台</a>
    </nav>
    <a class="phd-cta" href="/register.php">现在注册</a>
  </div>
</header>
<script>
(function(){
  var t=document.getElementById('phdToggle');
  var n=document.getElementById('phdNav');
  if(t&&n){
    t.addEventListener('click',function(){n.classList.toggle('phd-open');});
  }
})();
</script>
