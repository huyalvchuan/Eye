<?php 
date_default_timezone_set('PRC'); //设置中国时区 
$Request_url=$_SERVER['REQUEST_URI'];
//这里进行一些全局配置的设定 
$GLOBALS['beginTime'] = microtime(TRUE);
// 记录内存初始使用，跟踪内存使用。
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
//设置操作系统变量
define("OS", PHP_OS=="WINNT"?1:0);
define("METHOD",$_SERVER["REQUEST_METHOD"]=="GET"?0:1);
//获得sapi信息，本框架只针对nginx和cli模式
defined("SAPI") or define("SAPI", php_sapi_name()==="cli"?0:1);
//设置目录信息
if(defined("DIR"))
 define("CORE_PATH", DIR."Eye/"); 
else 
define("CORE_PATH", __DIR__."/");
define("Utils", DIR."/Eye/Utils/");
define("APP_CORE", "Eye");
define("CONFIG", "Config");
define("TEMPLATE", "Template");
define("VIEW", "View");
define("CONTROLLER", "Controller");
define("EXT", ".php");

//设置视图层的后缀名
define("VIEWEXT", ".html");

require_once  CORE_PATH."/EyeController.php";
Eye::Start();
?>