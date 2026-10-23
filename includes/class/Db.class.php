<?php
/* ---------------------------------------------------- */
/* 程序名称: admin 后台数据库类（兼容 PHP 7.3，接入主库 sourcepan）
/* 程序功能: SystemModule.php 的 `class System extends Db` 基类
/* ---------------------------------------------------- */
class Db {
	public $connection;
	public $error = '';

	function __construct($host, $user, $pwd, $db = '', $charset = 'utf8') {
		$this->connection = @mysqli_connect($host, $user, $pwd, $db);
		if(!$this->connection) {
			$this->error = mysqli_connect_error();
			return;
		}
		if($charset) @mysqli_set_charset($this->connection, $charset);
	}

	function query($sql) { return @mysqli_query($this->connection, $sql); }

	function prepare($sql) { return @mysqli_prepare($this->connection, $sql); }

	function fetch_array($result) { return @mysqli_fetch_array($result); }

	function select($table, $various = '*', $condition = '', $debug = '') {
		$sql = "SELECT $various FROM $table";
		if($condition != '') $sql .= " WHERE $condition";
		if($debug) echo $sql;
		return $this->query($sql);
	}

	function select_all($table, $various = '*') { return $this->select($table, $various); }

	function fetch_array_all($table, $condition = '') {
		$r = $this->select($table, '*', $condition);
		$out = array();
		while($row = @mysqli_fetch_array($r)) $out[] = $row;
		return $out;
	}

	function affected_rows() { return @mysqli_affected_rows($this->connection); }

	function insert_id() { return @mysqli_insert_id($this->connection); }

	function escape($string) { return @mysqli_real_escape_string($this->connection, (string)$string); }
}
?>
