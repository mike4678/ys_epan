<?php $_name = isset($_GET['name']) ? $_GET['name'] : ''; //原始值；SQL转义由System方法内部负责 ?>
<?php
$T = array(
  'nav_home'=>'首页', 'nav_panel'=>'空间后台', 'nav_support'=>'客服中心', 'nav_help'=>'操作指南',
  'total'=>'总空间', 'left'=>'剩余', 'online'=>'在线', 'ren'=>'人',
  'menu_msg'=>'留 言', 'menu_folder'=>'增加目录', 'menu_file'=>'上传文件', 'menu_manage'=>'管理区',
  'ml1_name'=>'姓名:', 'ml1_public'=>'公开:', 'ml1_submit'=>' 提交留言 ',
  'ml2_title'=>'目录标题:', 'ml2_sign'=>'签写署名:', 'ml2_pass'=>'目录密码:', 'ml2_guide'=>'设置指南', 'ml2_add'=>' 添加目录 ',
  'ml3_up'=>'上传文件', 'ml3_link'=>'链接网址', 'ml3_sub'=>'子目录', 'ml3_note'=>'备注:', 'ml3_multi'=>'同时上传多个', 'ml3_start'=>'开始上传',
  'ml3_linktitle'=>'标题:', 'ml3_linkurl'=>'网址:', 'ml3_addlink'=>'增加链接', 'ml3_subname'=>'子目录名:', 'ml3_addsub'=>'增加子目录',
  'ml4_pass'=>'管理密码:', 'ml4_login'=>' 登  录 ', 'ml4_note'=>'注:管理员登录后可以编辑、删除空间内文件、目录、留言等。',
  'ml4_level'=>'空间级别:', 'ml4_expire'=>'到期日期:', 'ml4_upgrade'=>'[升级空间]', 'ml4_settings'=>'空间设置', 'ml4_exit'=>'退出管理',
  'ml4_help'=>'如何编辑删除数据', 'ml4_enter'=>'进入空间后台',
  'reading'=>'正在读取...', 'dirlist'=>'目录列表', 'sort'=>'排序', 'refresh'=>'刷新目录', 'refresh_confirm'=>'是否刷新目录数据？',
  'report'=>'[举报]', 'guide'=>'[操作指南]', 'online_list'=>'在线列表', 'dl_record'=>'下载记录', 'exit_sys'=>'退出系统',
  'loading'=>'数据读取中...', 'close'=>'关 闭', 'first_page'=>'首页', 'prev_page'=>'上一页', 'next_page'=>'下一页', 'last_page'=>'尾页',
  'powered_by'=>'空间与技术提供:', 'wait_up'=>'正在上传，请稍候...', 'no_list'=>'暂无记录'
);
