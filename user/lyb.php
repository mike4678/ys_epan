<?php
$showrow = 10; //一页显示的行数
$_user = isset($_COOKIE['User']) ? $_COOKIE['User'] : ''; //原始值；SQL转义由System方法内部负责，直接query处单独escape
$curpage = empty($_GET['page']) ? 1 : (int)$_GET['page']; //当前的页,还应该处理非数字的情况
$url = "?page={page}"; //分页地址，如果有检索条件 ="?page={page}&q=".$_GET['q']
$total = $db->num_rows($db->query('select * from epan_lyb where userspace = "'.$db->escape($_user).'";')); //记录总条数
if (!empty($_GET['page']) && $total != 0 && $curpage > ceil($total / $showrow))
    $curpage = (int)ceil($total / $showrow); //当前页数大于最后页数，取最后一页
//获取数据
$sql .= " LIMIT " . ($curpage - 1) * $showrow . ",$showrow;";
$query = $db->query('select * from epan_lyb where userspace = "'.$db->escape($_user).'";');

if($db -> LoadingGuestbook($_user,'userspace') == NULL) {
	echo "<P><br>&nbsp;尚无留言！</P><div id='L_dh' class='ysdh' style='line-height:150%;text-align:right;'>共0条记录,第0/0页<br>&nbsp;<input type='button' value='首页' class='bt2' onclick='lyfy(this,1);'/><input type='button' value='上页' disabled='disabled' class='bt2'/><input type='button' value='下页' disabled='disabled' class='bt2'/><input type='button' value='尾页' disabled='disabled' class='bt2'/></div>";
} else { while ($row = $db->fetch_array($query)) { 
 // 显示数据区
	$ys4 = '';
		$u = $db -> LoadingGuestbook($_user,'userspace');
		foreach($u as $list=>$things){
	$ys4.= "<p><img src='user/face/f".(int)$things['face'].".gif' onclick='lyedit(".(int)$things['id'].");'><b id='Xm_".(int)$things['id']."'>".htmlspecialchars($things['name'],ENT_QUOTES,'UTF-8')."</b><br><span id='Nr_".(int)$things['id']."'>".htmlspecialchars($things['data'],ENT_QUOTES,'UTF-8')."</span><br><label>". date('Y-m-d H:i:s', $things['t']) ."</label><label id='i1_40'>IP: ". htmlspecialchars(preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.$3.*",$things['ip']),ENT_QUOTES,'UTF-8') ."</label></p>"; }

if ($total > $showrow) {//总记录数大于每页显示数，显示分页
	$page = new page($total, $showrow, $curpage, $url, 2);
	$ys4.=$page->myde_write();
 }
}
		}
echo $ys4;
?>





