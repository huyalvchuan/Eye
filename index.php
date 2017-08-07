<?php 
//框架入口文件
//跨域
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
	exit;
}
//error_reporting(0);
PHP_VERSION >5.3 or die("Php Version Error");
//设置选择的主题，默认vue.js
define("theme", "vue");
define("APP_PATH", "Core");
define("DIR", __DIR__."/");
define("ROUTERTYPE", "common");
require_once 'Eye/Eye.php';
?>

