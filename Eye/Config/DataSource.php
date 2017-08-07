<?php

/**
 * 这里就是连接和错误机制，防止注入攻击(mysql_real_escape_string)。
 */
//namespace DataBase;
include "DataConfig.php";
class  DataSource {
	public $host;
	public $port;
	public $root;
	public $pwd;
	public $config;
	public $read;
	public $conn;
	public $db;
	public $cmd;
	public $prefix;
	//表的前缀
	public $stat;
	//该数据库的状态
	public function __construct($config) {
	}
	
	public function Innit()
	{
		$this -> conn = mysql_connect($this->config['host'] . ":" . $this->config['port'], $this->config['root'], $this->config['pwd']);
		$this -> Read = $this->config['read'];
		mysql_select_db($this->config['databasename']);
		mysql_query("set names 'utf-8'");
		
	}

	public function Query($sql) {
		//这里通过?占位符号用于替换
		//如果字符串中包含select则选择读数据库反之选择写数据库。
		$this->ChooseData($sql);
		$args = func_get_args();

		if (count($args) == 1) {
			return mysql_query($args[0]);
		} else {
			$sql = Replace($args[0], $args[1]);
			return mysql_query($sql);
		}
	}
	
	//数据库选择
	public function ChooseData()
	{
		if(strstr($classname, "SELECT")||strstr($classname, "select"))	//读操作
		{
			if(count($config["DataSource"])==1)
				{
					$this->config=$config["DataSource"][0];
				}
			else
				{
					$databaseid=rand(1,count($config['DataSource'])-1);
					$this->config=$config["DataSource"][$databaseid];
				}
		}
		else
		{
			$this->config=$config["DataSource"][0];
		}
		
		$this->Innit();
	}

	public function GetSql() {
		return mysql_info();
	}

	public function GetDbError() {
		return mysql_error();
	}

	public function Replace($sql, $values) {
		foreach ($values as $key => $value) {
			$values[$key] = mysql_real_escape_string($values[$key]);
		}
		return strtr($sql, $values);
	}

}
?>