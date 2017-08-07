<?php 
/*通过Redis构建的Cache
 * 对于数据的查询，由于用户写入的数据出了insert外，是从视图中有所改变，所以在缓存中命中的概率非常大。
 * 那么在缓存中采用怎么样的形式去缓存数据
 * 缓存的数据特点：用户经常使用的数据。包括用户个人的数据，实时更新的数据，
 * 由于所有的数据，有个特定的key值，也只能通过key值去更新。
 * 这块只负责某个key的逻辑
 */
include "../../../Utils/Redis/Redis.php";
include "Cache.php"; 
class MysqlCache extends  Cache
{
	public function __construct($redis){
		parent::__construct($redis);
	}	
	/**
	 * LookCache是Cache类唯一的入口
	 * 
	 */
	public function LookCache($key){
		 
	}
	
}

?>