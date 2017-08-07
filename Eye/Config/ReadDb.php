<?php 

/**
* 读取Db
*/
//namespace DataBase;

class ReadDb extends DataSource
{
	
	function __construct($config)
	{
		parent::__construct($config);
	}
	//锁定只能按行锁
   public function Lock($locksql)
	{
	  self::Query($locksql);
	}
	//对表解除锁定
	public function Unlock()
	{
		self::Query("UNLOCK TABLES");
	}
	public function Read($sql)
	{  //这个东西在Model里面进行解析
		return  mysql_fetch_array(self::Query($sql));
	}

}



?>