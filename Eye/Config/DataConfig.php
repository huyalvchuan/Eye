<?php
//namespace DataBase;
//require_once "ReadDb.php";
//require_once "WriteDb.php";
$config=array('Cache' => true, 
'DataSource'=>array('master'=>array('host'=>"127.0.0.1",'port'=>3036,'root'=>"root",'pwd'=>"appmysql201511",'read'=>TRUE)),//数据库配置，read只能设置Master的为true，其余的都是slave
'Redis'=>array('host'=>"127.0.0.1",'port'=>6062));

//class DataCore
//{
////这里设置一个随机器，延时加载数据库连接。
//public $Read;
//public $DataSource;
//public $CacheStat;
//public function __construct($Read)
//{
//	$this->Read=$Read;
//	$CacheStat=$config['Cache'];
//
//}
//public function GetDataSource()
//{
//if($Read==true)
//{
//$databaseid=rand(2,count($config['DataSource']));
////初始化数据库进行操作。
//}
//}
//
//
//
//}
//

?>