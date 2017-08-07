<?php 

/**
* innodb只负责写入数据，这里只有更新，删除，和插入。
*由于缓存可能存储在脏数据，所以这个时候应该检测，合法性，通过时间戳检验合法性
*/
//namespace DataBase;

class WriteDb extends DataSource
{
	
	function __construct()
	{
	}
    
	public function Lock()
	{
	  self::Query("BEGIN");
		
	}
	public function Unlock($method)
	{
		self::Query($method);
	}
	public function Write()
	{
		self::Check();//数据合法性检测，根据时间戳检测。
		return $this->Query($sql);
		
	}
    public function Check()
    {
    	
    /*
	 *检测到不合法，同时通知Redis更新数据
     *合法性会有数据库去操作 
	 * 这个时候主要是检测更新数据：
	 *如果是delete，不用查找是否有此id，直接删除。
	 * 如果是update，直接更新这个id。
	 * insert则不采取任何措施。
	 */
    }
	

}


?>