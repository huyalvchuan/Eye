<?php 
require "../Redis/Redis.php";
	class Session
	{
		private $sessionid;
		private $sessionkey;
		private $sessionvalue;
		private  static $session;
		public  function __construct($sessionid){
			$this->sessionid=$sessionid;
			if(self::$session==null){
				self::$session=new Redis($host,$port);
			}
			else{
				return self::$session;
			}
		}
		
		
		public function set($key, $value)
		{
			 self::$session->set($this->sessionid.$key,$value,60*30);
		}
		
		public function get($key){
			return self::$session->get($this->sessionid.$key);
		}
		
	}
	
	
	?>