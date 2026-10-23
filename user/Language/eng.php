<?php $_name = isset($_GET['name']) ? $_GET['name'] : ''; //原始值；SQL转义由System方法内部负责 ?>
<?php
$T = array(
  'nav_home'=>'Home', 'nav_panel'=>'Control Panel', 'nav_support'=>'Support', 'nav_help'=>'Help Guide',
  'total'=>'Total', 'left'=>'Left', 'online'=>'Online', 'ren'=>'',
  'menu_msg'=>'Message', 'menu_folder'=>'Folder', 'menu_file'=>'File', 'menu_manage'=>'Manage',
  'ml1_name'=>'Name:', 'ml1_public'=>'Public:', 'ml1_submit'=>' Submit ',
  'ml2_title'=>'Folder Title:', 'ml2_sign'=>'Signature:', 'ml2_pass'=>'Password:', 'ml2_guide'=>'Guide', 'ml2_add'=>' Add Folder ',
  'ml3_up'=>'Upload', 'ml3_link'=>'Link', 'ml3_sub'=>'Subdir', 'ml3_note'=>'Note:', 'ml3_multi'=>'Multi Upload', 'ml3_start'=>'Upload',
  'ml3_linktitle'=>'Title:', 'ml3_linkurl'=>'URL:', 'ml3_addlink'=>'Add Link', 'ml3_subname'=>'Subdir Name:', 'ml3_addsub'=>'Add Subdir',
  'ml4_pass'=>'Password:', 'ml4_login'=>' Login ', 'ml4_note'=>'Note: Admin can edit/delete files, folders and messages after login.',
  'ml4_level'=>'Level:', 'ml4_expire'=>'Expires:', 'ml4_upgrade'=>'[Upgrade]', 'ml4_settings'=>'Settings', 'ml4_exit'=>'Logout',
  'ml4_help'=>'How to edit/delete data', 'ml4_enter'=>'Enter Control Panel',
  'reading'=>'Loading...', 'dirlist'=>'Folder List', 'sort'=>'Sort', 'refresh'=>'Refresh', 'refresh_confirm'=>'Refresh folder data?',
  'report'=>'[Report]', 'guide'=>'[Guide]', 'online_list'=>'Online List', 'dl_record'=>'Download Log', 'exit_sys'=>'Exit',
  'loading'=>'Loading...', 'close'=>'Close', 'first_page'=>'First', 'prev_page'=>'Prev', 'next_page'=>'Next', 'last_page'=>'Last',
  'powered_by'=>'Powered by:', 'wait_up'=>'Uploading, please wait...', 'no_list'=>'No records'
);
