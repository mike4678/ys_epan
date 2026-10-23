<?php
/* ---------------------------------------------------- */
/* 程序名称: 核心函数
/* 程序功能: 所有核心函数的调用均在此页面中
/* 程序开发: Source
/* 联系方式: 542112943@qq.comp
/* Date: 1970-01-01 / 2016-08-30
/* ---------------------------------------------------- */
/* 使用条款:
/* 1.该软件免费使用.
/* 2.禁止任何衍生版本.
/* ---------------------------------------------------- */

class System extends DbMysql 
{
	//系统信息
	/* ---------------------------------------------------- */
	function SystemInfo($data) {                  
		$query = $this->select_all('system');
		$row = $this->fetch_array($query);
		switch($data) {
			case 'bottom':       //底部信息
				$bottom = "Copyright &copy; $row[year] $row[corp]. All Rights Reserved.";
				echo $bottom;
				break;
				
			case 'corp':    //版权名称
				$bottom = "$row[corp]";
				echo $bottom;
				break;
				
			case 'ver':   //版本
				$bottom = "$row[version]";
				return $bottom;
				break;
				
			case 'logo':   //左上角Logo
				$bottom = "<a href='#' target='_blank'><img src='".$row['logo']."' alt='Logo' width='94' height='40'/></a></div>";
				return $bottom;
				break;
			}

		}
	/* ---------------------------------------------------- */
	
	//删除一条或多条数据
	/* ---------------------------------------------------- */
	function DelData($table,$hterm,$term,$method)    //参数：表$table,条件头$hterm,条件$term,操作方式$method
	{
   if(empty($table) || empty($hterm) || empty($term) || empty($method)) {     //五个变量任意一个如果为空
		echo "<script language='JavaScript'>window.alert(\"操作请求所需要的值错误，请检查！\")</script>";
	} else {
	// 表名/字段名白名单：仅允许字母、数字、下划线，杜绝注入
	if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table) || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $hterm)) {
		echo '<script language="JavaScript">window.alert("无效的操作请求")</script>';
		return;
	}
	switch ($method) {
		default:   //请求无效
		echo '<script language="JavaScript">window.alert("无效的操作请求")</script>';
		break;

		case 'POST':    //处理多个删除任务
		// $term 为逗号分隔的值列表，逐个转义后拼入 IN (...)
		$term_parts = array_filter(array_map('trim', explode(',', $term)));
		$escaped = array();
		foreach ($term_parts as $tp) {
			$escaped[] = "'" . $this->escape($tp) . "'";
		}
		if (empty($escaped)) {
			echo '<script language="JavaScript">window.alert("删除失败！")</script>';
			break;
		}
		$sql = "delete from ".$table." where ".$hterm." in (".implode(',', $escaped).");";
		if (!$this->query($sql)) {
   			echo '<script language="JavaScript">window.alert("删除失败！")</script>';
     	}
    	echo '<script language="JavaScript">window.alert("删除成功！")</script>';
    	echo '<script language="JavaScript">self.location=document.referrer;</script>';
    	break;

		case 'GET':    //处理单个删除任务
		if (!csrf_check(isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '')) {
			exit('CSRF 校验失败');
		}
		if (count(explode("%",$term)) > 1)  //一般认为一个中文字会转换成3个类似%D8%E5%C3的代码，因此这里以3为判断标准
		{  $url = urldecode($term);
		$sql = "delete from ".$table." where ".$hterm." = '" .$this->escape($url). "';";
		if (!$this->query($sql)) {
   			echo '<script language="JavaScript">window.alert("删除失败！")</script>';
     	}
    	echo '<script language="JavaScript">window.alert("删除成功！")</script>';
    	echo '<script language="JavaScript">self.location=document.referrer;</script>';
		} else {
		$sql = "delete from ".$table." where ".$hterm." = '" .$this->escape($term). "';";
		if (!$this->query($sql)) {
   			echo '<script language="JavaScript">window.alert("删除失败！")</script>';
     	}
    	echo '<script language="JavaScript">window.alert("删除成功！")</script>';
    	echo '<script language="JavaScript">self.location=document.referrer;</script>'; }
		break;
		}
	}
	}
	/* ---------------------------------------------------- */
	
	//页面功能生成
	/* ---------------------------------------------------- */
	function table_list($table,$table_list){  //自动生成顶部选项位置以及当前选中的左边列表
	if(empty($table) && empty($table_list) )   //检查，如果这两个参数都为空，则返回错误信息，
	{ echo '<script language="JavaScript">window.alert("参数不能为空！");history.back(-1);</script>';
 	 } else
  	 { 
	$tab = ""; //初始化变量
	$query = $this->select_all('table_list'); //顶部标题部分
	$infor = array(); 
	while (	$row = $this->fetch_array($query))   {
	if($table == $row[0]) {	
	$tab.= "<li class='active'><a href=".$row[2]." class=".$row[1]."> ".$row[0]."</a><ul>"; 
	$list = $this->select('menu_list', '*', "menu_list.table = '".$table."' ORDER BY count ASC;", $debug = ''); //生成左边列表部分
	$infor1 = array(); 
	while ($row1 = $this->fetch_array($list))   {
	if($table_list == $row1[0]){
		$tab.= "<li class='active'><a href=".$row1[1]."> ".$row1[0]."</a></li>";
		}else {
		$tab.= "<li><a href=".$row1[1]."> ".$row1[0]."</a></li>";
		}
	}
	$tab.= "</li></ul>";
	}else {
		$tab.= "<li><a href='".$row[2]."' class='".$row[1]."'> ".$row[0]."</a></li>";
	}
	}

   }
   return $tab;
	}
	
	
	function navigation ($table,$table_list) {   //导航栏内容
	if(empty($table) || empty($table_list) )   //检查
	{ echo '<script language="JavaScript">window.alert("参数不能为空！");history.back(-1);</script>';
  	} else
   	{ 
	$infor = array(); 
	$query = $this->select('Menu_list', '*', "Menu_list.table='".$table."'  AND Menu_list.list = '".$table_list."' UNION ALL SELECT * FROM `menu_list` where menu_list.table = '".$table."' ORDER BY count ASC limit 2;", $debug = '');
	while (	$row = $this->fetch_array($query))   {
			echo "<li><a href='index.php' class='icon-home'> 开始</a></li>";
			echo "<li><a href=".$row[1].">".$row[2]."管理</a></li>"; 
			echo "<li>".$table_list."</li>";
			break;
		}}}

	function convert($table,$table_list) {  //将英文名称转换为中文
	$query = $this->select('Adminlist', '*', "Adminlist.t_e = '".$table."' AND m_e = '".$table_list."'", $debug = '');
	$row = $this->fetch_array($query);
	return $row;
	}
	
	function AddrConvery($data) {  //处理请求
	foreach ($data as $key=>$value) //把参数传入变量key中
	$arr = explode("/",$key);
	return $arr;
	}	
	
	function PageLoading($table,$table_list = '') {  //载入框架页面
	$query = $this->select('Menu_list', '*', "Menu_list.table = '".$table."' AND Menu_list.list = '".$table_list."'", $debug = '');
	$row = $this->fetch_array($query);
	return $row['page'];
	}
	
	function FormCheck() {   //检查框架页面是否存在，即不允许直接访问
	echo '<script>if(window.top==window.self){ window.alert("未检测到框架窗口，请从主窗口进入！") var browserName=navigator.appName; if (browserName=="Netscape") {window.open("","_self","");window.close(); } else {window.close();}  }</script>';
	}
	
	
	function FormCreate($data) { //多选项夹生成
		$form = '<div class="tab"><div class="tab-head"><strong>'.$data['TabName']['TabName'].'</strong><ul class="tab-nav">';
		unset($data['TabName']); //删除标题数组
		foreach ($data as $row)   {
			if($row['active'] != 'active') {
				$form.= "<li><a href=".$row['tab'].">".$row['Name']."</a></li>";
				} else {
					$form.= "<li class=\"active\"><a href=".$row['tab'].">".$row['Name']."</a></li>";
					}
		}
		$form.= "</ul></div>"; 
		echo $form;
	}
	/* ---------------------------------------------------- */
	
	//写入系统日志
	/* ---------------------------------------------------- */
	function WriteLog($method,$data,$addr)     //参数：请求方式、操作内容、操作页面
	{
	$ip = $_SERVER["REMOTE_ADDR"]; //获取IP
	$time = date("y/m/d",time());  //获取现在时间
	$query = $this->select('system_log', 'max(id)', "", $debug = '');
	while ($row = $this->fetch_array($query))
	{
	$id = $row['max(id)'] + 1;
	}
	$this->query("INSERT INTO system_log (id, method, ip, data, addr, time) VALUES ('".$id."','".$this->escape($method)."','".$this->escape($ip)."','".$this->escape($data)."','".$this->escape($addr)."','".$this->escape($time)."')");
 	 if ($this->affected_rows() == NULL) {
      echo '<script language="JavaScript">window.alert("写入日志失败！")</script>';
     }
	}
	/* ---------------------------------------------------- */
		
	//Curl读取页面
	//$page 为网页的地址
	//$method 为提交方式(get/post)
	//$data 为post时所需要提交的数据，post方式时有效
	/* ---------------------------------------------------- */
	function Curl_Load_Page($page,$Method = 'get',$array = '')   
	{
		$curl = curl_init();  //初始化
		switch (strtolower($Method))
		{
			case 'get' :
    			//设置抓取的url
   			 	curl_setopt($curl, CURLOPT_URL, $page); 
    			//设置头文件的信息作为数据流输出
    			//curl_setopt($curl, CURLOPT_HEADER, 1);
    			//设置获取的信息以文件流的形式返回，而不是直接输出。
    			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   				 //执行命令
    			$data = curl_exec($curl);
    			//显示获得的数据
				return $data;
				//print_r($data);
				break;
			
			case 'post' :
				curl_setopt ( $curl, CURLOPT_URL, $page );
				curl_setopt ( $curl, CURLOPT_POST, 1 );
				curl_setopt ( $curl, CURLOPT_HEADER, 0 );
				curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $curl, CURLOPT_POSTFIELDS, $array );
				$data = curl_exec ( $curl );
				return $data;
				//print_r($data);
				break;
		}
		curl_close($curl);   //关闭URL请求
	}
	/* ---------------------------------------------------- */

	//系统设置表信息
	/* ---------------------------------------------------- */
	function Info($data) {                  
		$query = $this->select_all('epan_settings');
		while ($row = $this->fetch_array($query)) 
		{
			if ($row[0] == $data)
			{
				return $row[1];
				break;
				}
			}

		}
	/* ---------------------------------------------------- */
	
		//系统错误提示
	/* ---------------------------------------------------- */
	function message($data , $mode = 1) {                  
		if ($mode = 1) {   //不黑屏
			echo '<script>art.dialog.tips(\''.$data.'\',2);</script>' ;
			}

		}
	/* ---------------------------------------------------- */
	
		//系统产品读取及状态查询
	/* ---------------------------------------------------- */
	function Product($type = '1') {   
		$query = $this->select_all('epan_product');
		switch($type)
		{
		case '1':     //只读取
			$product_list = '<table><tr><td class="tdbt" style="text-align: center;">产品类型</td><td class="tdbt" style="text-align: center;">空间大小</td><td class="tdbt" style="text-align: center;">单个文件最大</td><td class="tdbt" style="text-align: center;">目录总数限制</td><td class="tdbt" style="text-align: center;">两小时最大下载量</td><td class="tdbt" style="text-align: center;">是否付费</td><td class="tdbt" style="text-align: center;">状态</td></tr>';
			while ($row = $this->fetch_array($query)) 
			{
				if($row[7] != -1)
				{
					$product_list.='<tr bgcolor="#FFFFFF"><td align="center"><font color="#003399"><b>'.$row[0].'</b></font></td>
 <td align="center"><font color="#000080" size="3"><b>'.$row[1].'</b>MB</font></td><td align="center">'.$row[2].'MB</td><td align="center">'.$row[3].'</td><td align="center">'.$row[4].'MB</td><td align="center">';
					if ($row[5] == 1)
					{
						$product_list.='√</td><td align="center">'	;
						} else { $product_list.='×</td><td align="center">'	; }
					if ($row[6] == 1) 
					{
						$product_list.='申请禁止！</td>'	;
						} else { $product_list.='允许申请！</td>'	; }
					}
				}
			echo $product_list;
			break;
			
		case '2' :    //获取当前开放的免费空间
		$product_list = '';
		while ($row = $this->fetch_array($query)) 
			{
				if($row[5] != 1 and $row[6] != 1)
					{
						$product_list.= '<option value="'.$row[7].'">'.$row[0].$row[1].'MB</option>';
						} 
				}
		echo $product_list ;
		break;
			
		case '3' :    //获取当前开放的收费空间
		$product_list = '';
		while ($row = $this->fetch_array($query)) 
			{
				if($row[5] != 0 and $row[6] != 1)
					{
						$product_list.= '<option value="'.$row[7].'">'.$row[0].$row[1].'MB</option>';
						} 
				}
		echo $product_list ;
		break;
			}
			
		}
	/* ---------------------------------------------------- */
	
	//账户模块(用户名检查，密码检查，登陆操作) $_session[username_epan]
	/* ---------------------------------------------------- */	
	function Login ($code = '') //参数为登录必须字符串
	{
		if($code == NULL) {
			return 0 ; 
			} elseif ($this -> PWDCheck ($code['User'],$code['PWD']) == 0) {   //二次验证密码
					return 0 ; 
				} else {   //执行登录
					$Qc = Crypt::PwdEncode(TS, 'Query'); //生成QC码（QueryCode 保留旧算法）
					$safeLoginUser = $this->escape($code['User']);
					//UPDATE `epan_users` SET `last_login_time`='0', `QueryCode`='666' WHERE (`userid`='3')
					$LoginData = $this -> query("UPDATE epan_users SET last_login_time = '". TS ."', last_login_ip = '". GetHostByName($_SERVER['SERVER_NAME']) ."', QueryCode ='". $Qc ."' Where username = '". $safeLoginUser ."'") ;
					if ($this -> affected_rows() != NULL) {   //更新数据库
						session_start();
						session_regenerate_id(true);
						$_SESSION['username_epan'] = $code['User'];
						$_SESSION['QueryCode'] = $Qc;
						$LoginData = $this -> query("INSERT INTO epan_userloginlog (`user`, `lasttime`, `lastip`) VALUES ('".$safeLoginUser."', '".TS."', '".GetHostByName($_SERVER['SERVER_NAME'])."')") ;
						return 1 ;  //登录成功！
						} else { return 0 ; }
					}
		}
	
	function UserCheck ($user)    //用户名检查
	{
		$username = $this -> query("select username from epan_users where username = '". $this->escape($user) ."';") ;
		if($this -> affected_rows() != NULL)
		{
			return 1 ;  //存在用户
			} else { return 0 ; }  //不存在该用户
		}
		
	function PWDCheck ($user,$PWD)
	{
		$query = $this->query("select * from epan_users where username = '". $this->escape($user) ."';");
		$row = $this->fetch_array($query);
		if ($row && Crypt::PwdCheck((string)$PWD, (string)$row['password'])) {
			// 旧格式命中时原地升级为 bcrypt
			if (!password_verify((string)$PWD, (string)$row['password'])) {
				$newhash = Crypt::PwdEnc((string)$PWD, 'source');
				$this->query("UPDATE epan_users SET password = '".$this->escape($newhash)."' WHERE username = '".$this->escape($user)."';");
			}
			return 1;
		}
		return 0;
		}
	
	function LoginBar(){
		if ($this -> LoginCheck () == 0){
			return '<label class=dl0>您尚未登录！<a class=go2 href=\'/ht/login.php\'>登录</a></label>';
			} else { return '<label class=dl1>用户名：<font color=green>'. htmlspecialchars($_SESSION['username_epan'], ENT_QUOTES, 'UTF-8') .'</font>  账户余额：0元  <a href=\'/ht/index.php?method=yfk\'>账户充值</a> <a href=\'/ht/exit.php\'>退出</a></label>' ;}
		}
		
	function LoginCheck (){
		if($_SESSION['username_epan'] == NULL){  //如果为空，则返回假，客户端则跳转到登录界面
			return 0; }		
		$this -> query('select QueryCode from epan_users where username = "'.$this->escape($_SESSION['username_epan']).'"');	  //查询是否有QC码，如果没有则判断未登录，返回假
			if($this->affected_rows() == NULL){
				return 0;
				} else { return 1; }
	}
	
	
	function QueryCode ($User) {
		$QC = $this -> query('select QueryCode from epan_users where username = "'.$this->escape($_SESSION['username_epan']).'"');	  //查询是否有QC码，如果没有则判断未登录，返回假
		$row = $this->fetch_array($QC); 
		return $row[0];
		}
	
	function ExitLogin() {      //退出登陆
	session_start(); 
	session_destroy(); 
	unset($_SESSION); 
	}
	
	function AccountData($data , $user = '') {            //账户数据（参数为想要获取的账户内容）
		//首先获得账户的QC码
		$QC = $this-> QueryCode ($_SESSION['username_epan']) ;
		if ($user == NULL) {
			if ($QC != NULL) {
				$query = $this->query('select u.*, d.* from epan_users u inner join epan_userData d on d.`user` = u.username where u.username = "'.$this->escape($_SESSION['username_epan']).'" and u.QueryCode = "'. $this->escape($QC) .'";');
				$row = $this->fetch_array($query);
				return (string)$row[$data];
				} else { return '无效请求！'; }
		} else {
			if ($QC != NULL) {
			$query = $this->query('select u.*, d.* from epan_users u inner join epan_userData d on d.`user` = u.username where u.username = "'.$this->escape($user).'" and u.QueryCode = "'. $this->escape($QC) .'";');
			$row = $this->fetch_array($query);
			return (string)$row[$data];
				} else { return '无效请求！'; }
			}
	}
	
	function MBWT() {   //获取密保问题
	$list ='';
	$arr = explode(";",$this -> Info('mbwt'));
	foreach($arr as $u){
	$list.='<option value="'.$u.'">'.$u.'</option>';
		}
	return $list;
	}
	
	function MBWT_User($wen , $user) {   //获取密保问题
		if($wen == 'wen1' || $wen == 'wen2' || $wen == 'daan1' || $wen == 'daan2') {
			$query = $this->query('select * from epan_users where username = "'.$this->escape($user).'";');
			$row = $this->fetch_array($query);
			return (string)$row[$wen];
			}else {
				return '不支持的请求'; 
				}
	}
	
	function UserSettingUpdate ($user , $data) {
		$QC = $this-> QueryCode ($user) ;
		if ($QC != NULL) {
			$query = $this->query('UPDATE epan_users,epan_userData SET '. $data . ' Where (username =\''.$this->escape($user).'\');');
			if ($this -> affected_rows() != NULL) {   //更新数据库
			return 1;
				} else { return 0; }
		
			} else { return 0; }
		}
		  
	function PWDReset_Admin ($pwd , $user) {       //特权密码重设
		$Qc = Crypt::PwdEncode(TS, 'Query'); //生成QC码（QueryCode 保留旧算法）
		$query = $this->query('UPDATE epan_users SET '. $pwd . ' Where (username =\''.$this->escape($user).'\');');
			if ($this -> affected_rows() != NULL) {   //更新数据库
			return 1;
				} else { return 0; }
		}
	/* ---------------------------------------------------- */
	
	//空间设置
	/* ---------------------------------------------------- */	
	function SpaceSetting($user , $data) {
		$query = $this->query('select * from epan_space where username = "'.$this->escape($user).'";');
			$row = $this->fetch_array($query);
			return (string)$row[$data];
		}
	
	/* ---------------------------------------------------- */
	
	//空间参数设置
	/* ---------------------------------------------------- */	
	function SpaceUpdate ($user , $table , $data) {
		if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) { return 0; }
		$this->query("UPDATE epan_space SET ".$table." = '".$this->escape($data)."' WHERE username = '".$this->escape($user)."' ;");
		// 若该用户尚无 epan_space 行（新注册/数据迁移），自动补行，保证空间设置可写；
		// epan_space 无唯一索引，用 NOT EXISTS 幂等补行，避免重复
		if((int)$this->affected_rows() < 1){
			$this->query('INSERT INTO epan_space (username) SELECT "'.$this->escape($user).'" WHERE NOT EXISTS (SELECT 1 FROM epan_space WHERE username = "'.$this->escape($user).'")');
		}
		// 字段名与用户参数均合法即视为保存成功（避免 affected_rows 为 0 时误报"失败"）
		return 1;
		}
	/* ---------------------------------------------------- */
	
	//链接读取
	/* ---------------------------------------------------- */	
	function LoadingLink ($user,$uid = 'ALL') {
		switch($uid) {
			default:
			case 'ALL':
			$query = $this->query('select * from epan_link where username = "'.$this->escape($user).'";');
			if ($this -> affected_rows() != NULL) {   //更新数据库
				while ($row = $this->fetch_array($query)) 
				{
					$res[] = $row  ;
						}
				return $res;
				break;
				} else { return NULL; break;}	
				
			case $uid:
			$safeLinkUid = is_numeric($uid) ? (int)$uid : 0;
			$query = $this->query('select * from epan_link where username = "'.$this->escape($user).'" AND link_id = '.$safeLinkUid.';');
			if ($this -> affected_rows() != NULL) {   //更新数据库
				return $this->fetch_array($query);
				break;
				} else { return NULL;break; }
			}
		}
	/* ---------------------------------------------------- */
	
	//编辑链接
	/* ---------------------------------------------------- */	
	function EditLink ($uid,$name,$mc,$dz) {
		$safeEditUid = is_numeric($uid) ? (int)$uid : 0;
		$query = $this->query("UPDATE epan_link SET link_title ='".$this->escape($mc)."', link_address ='".$this->escape($dz)."' WHERE link_id ='".$safeEditUid."' AND username = '".$this->escape($name)."' ;");
		if ($this -> affected_rows() != NULL) {   //更新数据库
			return 1;
			} else { return NULL; }
		}
	/* ---------------------------------------------------- */
	
	//链接删除
	/* ---------------------------------------------------- */	
	function DelLink ($user,$uid) {
		$safeDelUid = is_numeric($uid) ? (int)$uid : 0;
		$query = $this->query('DELETE FROM epan_link where link_id = "'.$safeDelUid.'" AND username = "'.$this->escape($user).'";');
		if ($this -> affected_rows() != NULL) {   //更新数据库
				return 1;
				} else { return 0;}	
				
	}
	/* ---------------------------------------------------- */
	
	//链接添加
	/* ---------------------------------------------------- */	
	function AddLink ($mc,$dz,$user) {
		$query = $this->query("INSERT INTO epan_link (`link_id`, `link_title`, `link_address`, `username`) VALUES ('".$this->num_rows($this->query('select * from epan_link ;'))."','".$this->escape($mc)."','".$this->escape($dz)."','".$this->escape($user)."');");
		if ($this -> affected_rows() != NULL) {   //更新数据库
				return 1;
				} else { return 0;}	
				
	}
	/* ---------------------------------------------------- */
	
	//用户文件读取
	/* ---------------------------------------------------- */	
	function LoadingFile ($user,$folder = NULL) {
		$uidQ = '(select userid from epan_users where username = "'.$this->escape($user).'")';
		if($folder == NULL) {
			$query = $this  -> query('select * from epan_files where userid = '.$uidQ.';');
			if ($this -> affected_rows() != NULL) {   //更新数据库
				while ($row = $this->fetch_array($query)) 
					{
					$file[] = array('id'=>$row['file_id'],'name'=>$row['file_name'], 'mime'=>$row['file_mime'],'md5'=>$row['file_md5'],'description'=>$row['file_description'], 'size'=>$row['file_size'],'folder'=>$row['folder_id'],'url'=>($row['file_mime']=='application/url' ? $row['file_description'] : ''), 'user'=>$row['userid'],'ip'=>$row['ip'],'time'=>$row['file_time']);
						}
					return $file;
				} else { return NULL; }
		} else {
			$query = $this -> query('select * from epan_files where userid = '.$uidQ.' AND folder_id = "'.$this->escape($folder).'";');
			if ($this -> affected_rows() != NULL) {   //更新数据库
				while ($row = $this->fetch_array($query)) 
					{
					$file[] = array('id'=>$row['file_id'],'name'=>$row['file_name'], 'mime'=>$row['file_mime'],'md5'=>$row['file_md5'],'description'=>$row['file_description'], 'size'=>$row['file_size'],'folder'=>$row['folder_id'],'url'=>($row['file_mime']=='application/url' ? $row['file_description'] : ''), 'user'=>$row['userid'],'ip'=>$row['ip'],'time'=>$row['file_time']);
						}
					return $file;
				} else { return NULL; }
			
			}
		}
		
	/* ---------------------------------------------------- */
	
	//用户目录读取
	/* ---------------------------------------------------- */	
	function LoadingFolder ($user) {
		$query = $this->query('select * from epan_folders where userid = (select userid from epan_users where username = "'.$this->escape($user).'") AND parent_id = -1 ;');
		if ($this -> affected_rows() != NULL) {   //更新数据库
			while ($row = $this->fetch_array($query)) 
			{
				$folder[] = array('id'=>$row['folder_id'], 'name'=>$row['folder_name']);
				}
			return $folder;
			} else { return NULL; }
		
		}
		
	/* ---------------------------------------------------- */
	
	//新增目录（空间界面"新建目录"xzml 操作）
	/* ---------------------------------------------------- */	
	function AddFolder ($user,$name,$description,$password,$fileorder) { 
		$safeOrder = preg_replace('/[^0-9]/','',(string)$fileorder);
		if($safeOrder == '' || strlen($safeOrder) > 6) { $safeOrder = '011000'; }
		$query = $this->query("INSERT INTO `epan_folders` (`parent_id`, `folder_node`, `folder_name`, `folder_description`, `in_recycle`, `in_share`, `folder_order`, `folder_size`, `userid`, `in_time`, `password`, `fileorder`) VALUES ('-1', '0', '".$this->escape($name)."', '".$this->escape($description)."', '0', '0', '0', '0', (select userid from epan_users where username = '".$this->escape($user)."'), '".time()."', '".$this->escape($password)."', '".$safeOrder."');");
		if ($this -> affected_rows() != NULL) {   //更新数据库
			return 1;
			} else { return NULL;  }
	}
	
	/* ---------------------------------------------------- */
	
	//留言数据读取
	/* ---------------------------------------------------- */	
	function LoadingGuestbook ($user,$data) {
		switch($data) {
			case 'userspace':
				$query = $this->query('select * from epan_lyb where userspace = "'.$this->escape($user).'";');
				if ($this -> affected_rows() != NULL) {   //更新数据库
					while ($row = $this->fetch_array($query)) 
					{
						$ly[] = array('id'=>$row['uid'], 't'=>$row['time'], 'ip'=>$row['ip'], 'name'=>$row['name'], 'data'=>$row['data'], 'face'=>$row['face']);
						}
					return $ly;
					break;
							} else { return NULL;break; }
							
			case 'uid':
				$safeLybUid = is_numeric($user) ? (int)$user : 0;
					$query = $this->query('select * from epan_lyb where uid = "'.$safeLybUid.'";');
					if ($this -> affected_rows() != NULL) {   //更新数据库
						while ($row = $this->fetch_array($query)) 
						{
							$ly[] = array('name'=>$row['name'], 'data'=>$row['data'], 'face'=>$row['face']);
							}
						return $ly;
						break;
							} else { return NULL;break; }
					}
		}
		
	/* ---------------------------------------------------- */

	//写留言
	/* ---------------------------------------------------- */	
	function WriteGuestbook ($name,$user,$data,$face) { 
		$query = $this->query("INSERT INTO epan_lyb (`time`, `ip`, `name`, `data`, `face`, `userspace`) VALUES ('".TS."', '".$this->escape($this->GetClientIP())."', '".$this->escape($user)."', '".$this->escape($data)."', '".$this->escape($face)."', '".$this->escape($name)."')");
		if ($this -> affected_rows() != NULL) {   //更新数据库
			return 1;
			} else { return NULL;  }
		}
		
	/* ---------------------------------------------------- */

	//删除留言
	/* ---------------------------------------------------- */	
	function DelGuestbook ($user,$uid) {
		$safeDelLybUid = is_numeric($uid) ? (int)$uid : 0;
		$query = $this->query("DELETE FROM epan_lyb WHERE uid ='".$safeDelLybUid."' AND userspace = '".$this->escape($user)."';");
		if ($this -> affected_rows() != NULL) {   //更新数据库
			return 1;
			} else { return NULL;  }
		}
		
	/* ---------------------------------------------------- */

	//编辑留言
	/* ---------------------------------------------------- */	
	function EditGuestbook ($uid,$name,$user,$data,$face) {
		$safeEditLybUid = is_numeric($uid) ? (int)$uid : 0;
		$query = $this->query("UPDATE epan_lyb SET name ='".$this->escape($name)."', data='".$this->escape($data)."', face ='".$this->escape($face)."' WHERE uid ='".$safeEditLybUid."' AND userspace = '".$this->escape($user)."' ;");
		if ($this -> affected_rows() != NULL) {   //更新数据库
			return 1;
			} else { return NULL;  }
		}
		
	/* ---------------------------------------------------- */

	//获取用户真实IP
	/* ---------------------------------------------------- */	
	function GetClientIP() { 
  	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
    $ip = getenv("HTTP_CLIENT_IP"); 
  	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), 
"unknown")) 
    $ip = getenv("HTTP_X_FORWARDED_FOR"); 
  	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
    $ip = getenv("REMOTE_ADDR"); 
		else if (isset ($_SERVER['REMOTE_ADDR']) && 
$_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
    $ip = $_SERVER['REMOTE_ADDR']; 
  	else
    $ip = "unknown"; 
  	return ($ip); 
	} 
	
	/* ---------------------------------------------------- */

	//获取用户总空间数
	/* ---------------------------------------------------- */	
	function UserSpaceSize($user) { 
  	$level = $this -> fetch_array($this -> query('select * from epan_users,epan_userData where username = "'.$this->escape($user).'" and `user` = "'.$this->escape($user).'";'));
	$safeDiskLevel = is_numeric($level['diskLevel']) ? (int)$level['diskLevel'] : 0;
	$diskTemp = $this->fetch_array($this -> query('select name,data from epan_product where code = '. $safeDiskLevel )); 
	return($diskTemp['data']);
	} 
	
	
	/* ---------------------------------------------------- */

	//新增URL数据
	/* ---------------------------------------------------- */	
	function AddUrl($user,$folder,$name,$url) { 
		$fk = substr(md5(uniqid(mt_rand(), true)), 0, 8);
		$fsu = substr(md5(uniqid(mt_rand(), true)), 8, 6);
		$query = $this->query("INSERT INTO `epan_files` (`file_name`, `file_key`, `file_short_url`, `file_extension`, `file_mime`, `file_description`, `file_store_path`, `file_real_name`, `file_md5`, `userid`, `folder_id`, `ip`, `file_size`, `file_time`, `in_share`, `is_public`) VALUES ('".$this->escape($name)."', '".$fk."', '".$fsu."', 'url', 'application/url', '".$this->escape($url)."', '', '".$this->escape($name)."', '', (select userid from epan_users where username = '".$this->escape($user)."'), '".(int)$folder."', '".$this->escape($this->GetClientIP())."', '0', '".TS."', '0', '0');");
		if ($this->affected_rows() !== NULL && (int)$this->affected_rows() >= 1) { return 1; } else { return NULL; }
	} 
}
?>