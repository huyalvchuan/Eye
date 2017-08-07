<?php 
//用于CacheCore之间的交流
class Cache
{
	
	private $redis;
	
	public function __construct($redis){
		$this->redis=$redis;
	}
	/**
	 * @param string|array key
	 * 
	 */
	 
	public function Select($key){
		if($this->Check($key)){
			$this->redis->get($key);
		}
		else {
			return null;
		}
	}
	
	public function Update($key,$value,$timeout){
		if($this->Check($key)){
			$this->redis->set($key,$value,$timeout);
		}
		else {
			return NULL;
		}
	}
	
	public function Check($key){
		if($this->redis->sExists($key))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function Add($key, $value, $timeout){
		$this->redis->set($key, $value, $timeout);
	}
	
	/**
	 * 合法性检测,判断是否一致
	 */
	public function LCheck($key){
		
	}

}

?>