<?php

//框架入口文件
//跨域
header("Access-Control-Allow-Origin: *");
//error_reporting(7);
PHP_VERSION >5.3 or die("Php Version Error");
//设置选择的主题，默认vue.js
define("theme", "vue");
define("APP_PATH", "Core");
define("DIR", __DIR__."/");

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
?>