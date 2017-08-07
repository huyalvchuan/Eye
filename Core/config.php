

<?php 
//系统的配置文件，数据库配置	
	$cacheconfig=array();
	$dataconfig=array("client"=>array("host"=>"127.0.0.1","username"=>"root","password"=>"12345678","port"=>"3306","dbname"=>"db_wenwu", "prefix"=>"s_"));
	$viewconfig=array();
	$autoconfig=array();
	$ecyconfig=array("cache"=>$cacheconfig,"data"=>$dataconfig,"view"=>$viewconfig,"auto"=>$autoconfig);
	return $ecyconfig;
?>