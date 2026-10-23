<?php
date_default_timezone_set('Asia/Shanghai'); //时区设定

// 配置唯一来源：安装器写入的 system/configs.inc.php
// 运行时统一从这里读取，避免"安装写 system/configs.inc.php、运行时却读 includes/Conf.php"导致的连库失败
$config_file = dirname(__FILE__) . '/../system/configs.inc.php';
if (file_exists($config_file)) {
    require $config_file; // 加载 $configs 数组
    $DBSERVER = isset($configs['dbhost'])   ? $configs['dbhost']   : 'localhost';
    $USER     = isset($configs['dbuser'])   ? $configs['dbuser']   : 'root';
    $PASSWORD = isset($configs['dbpasswd']) ? $configs['dbpasswd'] : '';
    $DB       = isset($configs['dbname'])   ? $configs['dbname']   : '';
    unset($configs);
} else {
    // 未安装时回退到默认（正常情况下 Init.php 检测到未安装会跳转安装页，这里仅作兜底）
    $DBSERVER = 'localhost';
    $USER = 'root';
    $PASSWORD = '';
    $DB = '';
}
define("Time", date("y/m/d|H:i:s", time()));
?>
