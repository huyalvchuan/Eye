<?php
use Workerman\Worker;
require_once './Eye/Socket/Workerman/Autoloader.php';
require_once './Eye/EyeSocket.php';
//这里只允许
defined("SAPI") or define("SAPI", php_sapi_name()==="cli"?1:0);
if(SAPI == 0)
exit();

// 创建一个Worker监听2346端口，使用websocket协议通讯
$ws_worker = new Worker("websocket://0.0.0.0:2346");
//创建Eye控制器,里面有一系列的数据库和缓存操作。
$Eye = new Eye();
// 启动4个进程对外提供服务
$ws_worker->count = 4;

// 当收到客户端发来的数据后返回hello $data给客户端
$ws_worker->onMessage = function($connection, $data)
{
	
    $connection->send("hello world");
};

// 运行worker
Worker::runAll();


?>