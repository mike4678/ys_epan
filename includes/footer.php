<?php
$etime = microtime(true);//获取程序执行结束的时间
$total = $etime-TS;   //计算差值
$data = sprintf("%.3f", $total);   //保留三位小数
?>
<footer class="phd-footer">
  <div class="phd-footer-inner">
    <div class="phd-footer-text"><?php echo $db->Info('footer'); ?></div>
    <div class="phd-footer-time">页面执行时间：<?php echo $data; ?> 秒</div>
  </div>
</footer>
