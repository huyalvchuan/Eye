

<?php 
//系统的配置文件，数据库配置	
	$cacheconfig=array();
	$dataconfig=array("client"=>array("host"=>"127.0.0.1","username"=>"root","password"=>"12345678","port"=>"3306","dbname"=>"db_wenwu", "prefix"=>"s_"));
	$viewconfig=array();
	$autoconfig=array();
	$actioncofig=["wx"=>["appId"=>"","appKey"=>""], "yiban"=>['x' => array(
			'appID'		=> 'd8cd1211841b1a7907777b0d9649c27f',
			'appSecret'	=> '026ae82656cb5a32c997cfb9015bf799',
			'callback'	=> 'http://f.yiban.cn/webapptest'		 
		),
		 
		'm' => array(
			'appID'		=> 'f7761ed00edadbfa',
			'appSecret'	=> '3e5c66afcec4cff23903d838a0e072c0',
			'callback'	=> 'http://www.chinayapa.com/yiban/yiban/yiban/authorize.php'
		)]
		];
	$ecyconfig=array("cache"=>$cacheconfig,"data"=>$dataconfig,"view"=>$viewconfig,"auto"=>$autoconfig);
	return $ecyconfig;
?>