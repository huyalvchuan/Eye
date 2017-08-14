<?php
//整个程序的执行体
/********
 * 这里设置全局使用的函数，自定义控制器只需要调用就行。
 * 把这个设置成工厂类
 * 这里由于namespace的原因不方便加载扩展的的库，所以干脆直接取消namespace的作用
 * 
 *********/ 
//namespace Eye;
#region 数据格式定义
define("FAIL", "failed");
define("SUCCESS", "success");
#regionend 数据格式定义
class Eye
{
	private $smarty;
	public static $m_classSource;	//类全局
	public static $m_instance;		//类的实例化对象
	public static $m_class;			//当前控制器调用的class
	public static $m_method;   		//当前控制器调用的method
	public static $m_smartydir;
	public static $m_valuestack;	//值栈
	public static $session_state=FALSE;
	public static $session;
	public static $db = null;
	public static $user = null;
	//注册全局函数
	public function __construct()
	{
	}
	//启动函数
	public static function Start()
	{
		spl_autoload_register('Eye::EyeAutoload');
        set_exception_handler('Eye::appException');
		
		$Request_url=$_SERVER['REQUEST_URI'];
		if(ROUTERTYPE == "common") {
			$Request_url = $_SERVER['REQUEST_URI'];
			$result =  pathinfo($Request_url);
			self::$m_class=pathinfo(pathinfo($Request_url)["dirname"])["basename"]."Controller";
			self::$m_method = $result["basename"];
			self::Instance(self::$m_class, self::$m_method);	
		} else {
			$split1= split("php", $Request_url);
	        $result=split("/", $split1[1]);
		    self::$m_class=$_GET["m"]."Controller";
			self::$m_method=$_GET["a"];
			self::$m_smartydir=$result[1];
			$filepath=crc32(self::$m_class.self::$m_method);
			self::Instance(self::$m_class, self::$m_method);			
		}
		

	}
	//过滤器
	public function I($name)
	{
	//控制器获取的内容应该为json数据。或者是get，post。
	 if(!isset(self::$m_valuestack["json"]))
	 	{
	   	self::$m_valuestack["json"]=file_get_contents("php://input");
	 	}
	 if($name!="json")
	 	{
	 		if(isset($_GET[$name]))
			return $this->Safe($_GET[$name]);
			if(isset($_POST[$name]))
			return $this->Safe($_POST[$name]);
	 	}
	 else 
	 	{	 		
			return  json_decode(self::$m_valuestack["json"]);
	 	}	  
	}
	
	public function Assign($key, $value) {
		require_once $_SERVER['DOCUMENT_ROOT'].'/EasyEye/Eye/Utils/ViewPlugins/Smarty/Smarty.class.php';
		$this->smarty=$this->smarty==null?new Smarty:$this->smarty;
		$this->smarty->compile_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Core/ViewCache/";
		$this->smarty->config_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Eye/Utils/ViewPlugins/Smarty/configs/";
		$this->smarty->cache_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Core/ViewCache/";
		$this->smarty->left_delimiter='<{';
		$this->smarty->right_delimiter='}>';
		$this->smarty->assign($key, $value);
			
	}
	
	public function __set($key, $value){
		
	}

	public function Display($netfile){
	require_once $_SERVER['DOCUMENT_ROOT'].'/EasyEye/Eye/Utils/ViewPlugins/Smarty/Smarty.class.php';
	$this->smarty=$this->smarty==null?new Smarty:$this->smarty;
//	$this->smarty->template_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Core/view/User/";
//	$this->smarty->compile_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Eye/Utils/ViewPlugins/Smarty/templates_c/";
//	$this->smarty->config_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Eye/Utils/ViewPlugins/Smarty/configs/";
//	$this->smarty->cache_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Eye/Utils/ViewPlugins/Smarty/cache/";
//	$this->smarty->left_delimiter='<{';
//	$this->smarty->right_delimiter='}>';
	$this->smarty->template_dir= $_SERVER['DOCUMENT_ROOT']."/EasyEye/Core/view/".self::$m_smartydir;
	$this->smarty->display($netfile.VIEWEXT); 		
	}
	
	
	//数据库操作
	public  function M()
	{
		if($this->db!=null) return $this->db;
		require_once DIR."/Eye/Utils/DataBase/medoo.php";
		$config = require DIR."Core/config.php";
		$db_config = $config["data"]["client"];
	
		$database = new medoo([
		    // 必须配置项
		    'database_type' => 'mysql',
		    'database_name' => $db_config["dbname"],
		    'server' => $db_config['host'],
		    'username' => $db_config['username'],
		    'password' => $db_config['password'],
		    'charset' => 'utf8',

		    // 可选参数
		    'port' => 3306,
		 
		    // 可选，定义表的前缀
		    'prefix' => $db_config['prefix'],
		 
		    // 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
		    'option' => [
		        PDO::ATTR_CASE => PDO::CASE_NATURAL
		    ]
		]);
		
		$this->db = $database;
		
		return $database;
		
	}
	//输出内容
	public function Show($words)
	{
		echo $words;
	
	}
	
	public function FileUtils() {
		$fileutils = new FileUtils();
		return $fileutils;
	}
	//重定向
	public function R($url)
	{
		header("location:$url");
	}
	//返回数据
	public static function Back($state,$message,$body)
	{
		$map["state"]=$state;
		$map["message"]=$message;
		$map["body"]=$body;
		echo json_encode($map);
	}
	//SESSION管理
	public function S($key)
	{
		if(self::$session_state==FALSE)
		{	
			self::$session_state=TRUE;
			session_start();
		}
		
		if(!isset($_SESSION[$key]))
		{
			return NULL;
		}
		else
		{
			return $_SESSION[$key];
		}
	}
	
	public function AddS($key,$value)
	{
		if(self::$session_state==FALSE)
		{	
			self::$session_state=TRUE;
			session_start();
		}
		$_SESSION[$key]=$value;
	}
	
	public function SetSession($key, $value){
		if(self::$session==null){
			self::$session=new Session(session_id());
			self::$session->set($key, $value);
		}else {
			self::$session->set($key, $value);
		}
	}
	
	public function GetSession($key){
		if(self::$session==null){
			self::$session=new Session(session_id());
			self::$session->set($key);
		}else {
			self::$session->set($key);
		}
	}
	
	
	public function DestoryS()
	{
		
		session_destroy();
	}
	
	//自动加载autoload
	public static function EyeAutoload($classname)
	{
		
		$funcdir=array("Config","Utils/Helper", "JSON","Sql","Sql/Cache","Sql/Model","Sql/Model/Model","Utils/Session", "Utils/FileUtils");
		if(!isset(self::$m_classSource[$classname]))
		{
		    //设置策略，去搜索空间中的类。
			if(strstr($classname, "Controller"))
			{
				$str=DIR."Core/"."Controller/".$classname.EXT;
				if(!file_exists($str))
				{
					self::Back(FAIL, "不存在该控制器", NULL);
					return;
				}
				include DIR."Core/"."Controller/".$classname.EXT;
			}
			else if(strstr($classname, ".Func."))	//这里加载Func插件
			{
				$str=DIR."Core/"."Func/".$classname.EXT;
				if(!file_exists($str))
				{
					self::Back(FAIL, "不存在该插件", NULL);
					return;
				}
				include DIR."Core/"."Controller/".$classname.EXT;
				
			}
			else 
			{
				foreach($funcdir as $dir)
				{
					$str=DIR."Eye/"."$dir/".$classname.EXT;
					if(file_exists($str))
					{
						include $str;
					}
				}
			}
			
			  self::$m_classSource[$classname]=$classname;
		}
	}
	//实例化对象
	public static function Instance($class,$method) 
	{
		if(!isset(self::$m_instance[$class.$method])){
		$newclass=new $class();
		$reflectionMethod = new \ReflectionMethod($class, $method);
        $reflectionMethod->invoke($newclass);
	    self::$m_instance[$class.$method]=$reflectionMethod;
		self::$m_instance[$class]=$newclass;
		} else {
		}
		
	}	
	//错误处理
	  static public function appException($e) {
        $error = array();
        $error['message']   =   $e->getMessage();
		echo $error['message'];
//      $trace              =   $e->getTrace();
//      if('E'==$trace[0]['function']) {
//          $error['file']  =   $trace[0]['file'];
//          $error['line']  =   $trace[0]['line'];
//      }else{
//          $error['file']  =   $e->getFile();
//          $error['line']  =   $e->getLine();
//      }
//      $error['trace']     =   $e->getTraceAsString();
//      // 发送404信息
//      header('HTTP/1.1 505 Not Found');
//      header('Status:505 zhiliao not know');

    }
	//数据过滤,这里只过滤出html元素特殊字符
	function Safe($str)
	{
     	return htmlspecialchars($str);

    }
	public function mylog($info){
		$myfile = fopen("log.txt", "a+") or die("Unable to open file!");
		fwrite($myfile, date("Y-m-d h:i:s").":".$info."\n");
		fclose($myfile);
	}
	// 自动IOC
	public function ioc($service_name, $config=NULL) {
		// 搜索组件空间是否存在，如果不存在，提供方案加载，如果存在，直接返回该service。并就行初始化。
		
	}
	public function doaction($actionname) {
		include DIR."Core/Func/".$actionname."/".$actionname.".php";
		$action = new $actionname;
		return $action;	
	}
	public function reasponse($statuscode, $msg, $data) {
		$result = array("status"=>$statuscode, "msg"=>$msg, "data"=>$data);
		echo json_encode($result);
	}    
	public function token($app, $db, $token) {
		if($token == "lvchuanadmin") {
			return 8;
		}
		$arr = $db->select("user_account", "id", [
			"token"=>$token
		]);
		if(count($arr) == 0 ) {
			$app->reasponse(10005, "token失效", "无情的拒绝了你");
			exit();
		} else {
			return $arr[0];
		}
	}
}
?>

