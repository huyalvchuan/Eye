<?php
/*
 * Redis是基础操作类，给集群留出接口使用
 * 
 */
class Redis
{
	private $redis;

    /**
     * @param string $host
     * @param int $post
     */
    public function __construct($host = '10.102.1.8', $port = 6379) {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
        return $this->redis;
    }

    /**
     * 设置值  构建一个字符串
     * @param string $key KEY名称
     * @param string $value  设置值
     * @param int $timeOut 时间  0表示无过期时间
     */
    public function set($key, $value, $timeOut=0) {
        $retRes = $this->redis->set($key, $value);
        if ($timeOut > 0)
            $redis->expire('$key', $timeOut);
        return $retRes;
    }

    /*
     * 构建一个集合(无序集合)
     * @param string $key 集合Y名称
     * @param string|array $value  值
     */ 
    public function sadd($key,$value){
        return $this->redis->sadd($key,$value);
    }
    
    /*
     * 构建一个集合(有序集合)
     * @param string $key 集合名称
     * @param string|array $value  值
     */
    public function zadd($key,$value){
        return $this->redis->zadd($key,$value);
    }
    
    /**
     * 取集合对应元素
     * @param string $setName 集合名字
     */
    public function smembers($setName){
        return $this->redis->smembers($setName);
    }

    /**
     * 构建一个列表(先进后去，类似栈)
     * @param sting $key KEY名称
     * @param string $value 值
     */
    public function lpush($key,$value){
        echo "$key - $value \n";
        return $this->redis->LPUSH($key,$value);
    }
    
      /**
     * 构建一个列表(先进先去，类似队列)
     * @param sting $key KEY名称
     * @param string $value 值
     */
    public function rpush($key,$value){
        echo "$key - $value \n";
        return $this->redis->rpush($key,$value);
    }
    /**
     * 获取所有列表数据（从头到尾取）
     * @param sting $key KEY名称
     * @param int $head  开始
     * @param int $tail     结束
     */
    public function lranges($key,$head,$tail){
        return $this->redis->lrange($key,$head,$tail);
    }
    
    /**
     * HASH类型
     * @param string $tableName  表名字key
     * @param string $key            字段名字
     * @param sting $value          值
     */
    public function hset($tableName,$field,$value){
        return $this->redis->hset($tableName,$field,$value);
    }
    
    public function hget($tableName,$field){
        return $this->redis->hget($tableName,$field);
    }
    
    
    /**
     * 设置多个值
     * @param array $keyArray KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间
     */
    public function sets($keyArray, $timeout) {
        if (is_array($keyArray)) {
            $retRes = $this->redis->mset($keyArray);
            if ($timeout > 0) {
                foreach ($keyArray as $key => $value) {
                    $this->redis->expire($key, $timeout);
                }
            }
            return $retRes;
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * 通过key获取数据
     * @param string $key KEY名称
     */
    public function get($key) {
        $result = $this->redis->get($key);
        return $result;
    }

    /**
     * 同时获取多个值
     * @param ayyay $keyArray 获key数值
     */
    public function gets($keyArray) {
        if (is_array($keyArray)) {
            return $this->redis->mget($keyArray);
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * 获取所有key名，不是值
     */
    public function keyAll() {
        return $this->redis->keys('*');
    }

    /**
     * 删除一条数据key
     * @param string $key 删除KEY的名称
     */
    public function del($key) {
        return $this->redis->delete($key);
    }

    /**
     * 同时删除多个key数据
     * @param array $keyArray KEY集合
     */
    public function dels($keyArray) {
        if (is_array($keyArray)) {
            return $this->redis->del($keyArray);
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }
    
    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key) {
        return $this->redis->incr($key);
    }
    
    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key) {
        return $this->redis->decr($key);
    }
   
    
    /**
     * 判断key是否存在
     * @param string $key KEY名称
     */
    public function isExists($key){
        return $this->redis->exists($key);
    }

    /**
     * 重命名- 当且仅当newkey不存在时，将key改为newkey ，当newkey存在时候会报错哦RENAME   
     *  和 rename不一样，它是直接更新（存在的值也会直接更新）
     * @param string $Key KEY名称
     * @param string $newKey 新key名称
     */
    public function updateName($key,$newKey){
        return $this->redis->RENAMENX($key,$newKey);
    }
    
   /**
    * 获取KEY存储的值类型
    * none(key不存在) int(0)  string(字符串) int(1)   list(列表) int(3)  set(集合) int(2)   zset(有序集) int(4)    hash(哈希表) int(5)
    * @param string $key KEY名称
    */
    public function dataType($key){
        return $this->redis->type($key);
    }

   
    /**
     * 清空数据
     */
    public function flushAll() {
        return $this->redis->flushAll();
    }


     
    /**
     * 返回redis对象
     * redis有非常多的操作方法，我们只封装了一部分
     * 拿着这个对象就可以直接调用redis自身方法
     * eg:$redis->redisOtherMethods()->keys('*a*')   keys方法没封
     */
    public function redisOtherMethods() {
        return $this->redis;
    }

}
	



?>