<?php 
	/**
	 * 在这里进行缓存算法
	 */
	 define("STEP", 10);
	 define("TIMEMAX", 3000);
	class CacheCore
	{
		private $db;
		public function __construct(){
			
		}
		/**
		 * 这里主要针对的是主页数据缓存和全名热点
		 */
		public function CacheBySql($sql){
			
		}
		/**
		 * 这里只针对单个ID，该ID的时间会有步长。
		 */
		public function CacheByID($Id){
			
		}
		
		public function Query($sql){
			//在这里进行Sql选择,对于插入写入操作，会直接插入操作。
			//对于选择的话，选择SQL，或者ID方式缓存
			if($this->Choose($sql)=="ID"){
				return $this->CacheByID($sql);
			}	
			else if($this->Choose($sql)=="SQL"){
				return $this->CacheBySql($sql);
			}	
		}
		
		public function Common(){
			
		}
		
		public function Choose($sql){
			if(String::Contains($sql, "WHERE id =")||String::Contains($sql, "where id=")){
				
				return "ID";
			}
			else {
				
				return "SQL";
			}
			
		}
						
	}
	?>