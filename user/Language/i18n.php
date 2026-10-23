<?php
// ===== 多语言 i18n 统一加载入口 =====
// 用法：lang_load($lang, $name) —— 按语言代码(0中文/1英文/2日文)加载对应语言文件，返回 $T 语言数组。
// 一个主文件 i18n.php，按需加载对应语言文件，避免 k.php 里散落的 switch include。
function lang_load($lang, $name = '') {
    $_name = isset($name) ? $name : '';
    $T = array();
    $map = array('0' => 'zh.php', '1' => 'eng.php', '2' => 'jpn.php');
    $key = isset($map[$lang]) ? $map[$lang] : 'zh.php';
    $langFile = dirname(__FILE__) . '/' . $key;
    if (file_exists($langFile)) { include $langFile; }
    if (!is_array($T)) { $T = array(); }
    return $T;
}
?>
