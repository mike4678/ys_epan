<?php $_name = isset($_GET['name']) ? $_GET['name'] : ''; //原始值；SQL转义由System方法内部负责 ?>
<?php
$T = array(
  'nav_home'=>'ホーム', 'nav_panel'=>'空間管理', 'nav_support'=>'サポート', 'nav_help'=>'操作ガイド',
  'total'=>'総容量', 'left'=>'残量', 'online'=>'オンライン', 'ren'=>'人',
  'menu_msg'=>'留言', 'menu_folder'=>'目録追加', 'menu_file'=>'ファイル', 'menu_manage'=>'管理区',
  'ml1_name'=>'氏名:', 'ml1_public'=>'公開:', 'ml1_submit'=>' 提交 ',
  'ml2_title'=>'目録タイトル:', 'ml2_sign'=>'署名:', 'ml2_pass'=>'パスワード:', 'ml2_guide'=>'ガイド', 'ml2_add'=>' 目録追加 ',
  'ml3_up'=>'アップロード', 'ml3_link'=>'リンク', 'ml3_sub'=>'サブ目録', 'ml3_note'=>'備考:', 'ml3_multi'=>'複数アップロード', 'ml3_start'=>'開始',
  'ml3_linktitle'=>'タイトル:', 'ml3_linkurl'=>'URL:', 'ml3_addlink'=>'リンク追加', 'ml3_subname'=>'サブ目録名:', 'ml3_addsub'=>'サブ目録追加',
  'ml4_pass'=>'パスワード:', 'ml4_login'=>' ログイン ', 'ml4_note'=>'管理者はログイン後ファイル、目録、留言を編集できます。',
  'ml4_level'=>'レベル:', 'ml4_expire'=>'期限:', 'ml4_upgrade'=>'[アップ]', 'ml4_settings'=>'設定', 'ml4_exit'=>'ログアウト',
  'ml4_help'=>'データ編集方法', 'ml4_enter'=>'空間管理へ',
  'reading'=>'読込中...', 'dirlist'=>'目録リスト', 'sort'=>'並替', 'refresh'=>'更新', 'refresh_confirm'=>'目録データを更新しますか？',
  'report'=>'[報告]', 'guide'=>'[ガイド]', 'online_list'=>'オンラインリスト', 'dl_record'=>'ダウンロード記録', 'exit_sys'=>'終了'
);
