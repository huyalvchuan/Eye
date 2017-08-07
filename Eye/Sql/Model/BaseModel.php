<?php 
/*
 * 这是数据库操作层，与数据适配层相连
 * 只是做了一个单个表的查询，如果时间充裕，将进行复杂查询的构建。
 */

class BaseModel
{
	private $db;
	private $sql;
	private $operation;
	private $TABLE_NAME;
	private $u;	//如果进行update，生成的字符串，保留在这里。
	private $values;
	private $cache;	
	private static $prefix;
	public function __construct($table_name=null)
	{
		$this->TABLE_NAME=$table_name;
	}
	//这里通过唯一标识来识别每一个model
	public function Find($key, $id){
		if($this->cache){
		$this->CacheForId($id);
		}
		$this->operation="read";
		$sql="select * from ".$this->TABLE_NAME." where $key='$id'";
		$result=$this->Query($sql);
		$this->values=$result[0];
		return $this;
	}
	/*
	public function Where($map)
	{
		//map中是&操作 
		foreach ($map as $key => $value) {
			$this->sql.=$key." ".$value;
		}
		return $this;
	}
    public function Select($filter)
    {
    	$this->operation="read";
		switch ($filter) {
		case '*':
			$this->sql="select * from ".$TABLE_NAME." ".$this->sql;
			break;
		case "count":
			$this->sql="select count(*) from ".$TABLE_NAME." ".$this->sql;
		break;
		default:
			$this->sql="select ".$filter." from ".$TABLE_NAME." ".$this->sql;			
			break;
	}
		if($this->cache){
			$this->CacheForSql($this->sql);
		}
		return $this->Query($this->sql);
	
    }
    public function Limit($start,$count)
    { 
    	$this->sql=$this->sql." "."limit $start , $count ";
    } 
	
	 
	 */
	public function Query($sql, $values=null)
	{
		
		if($this->db==null){
			$this->db=new BaseData();
			
		}
		$result=$this->db->Query($sql, $values);
		return $result;
		//在这里才延迟执行数据库选择操作。

	}
	//装载类，负责把多种类型的字符串拼接赋值给类
	public function Load($result)
	{
		$class = new \ReflectionClass(get_class($this));
        $properties = $class -> getProperties();
		foreach($property as $properties)
		{
			$this->$property=$result[$property];
		}
	}
	public function Save()
	{
		
		$values;
		$i=1;
		foreach ($this->$values as $key => $value) {
			if(lowercase($key) =="id")
			 continue;
			if(is_string($value))
			$values=$values."$key='".$value."'";
			else 
			$values=$values."$key=".$value;	
			if($i++<count($this->u))
			$values.=", ";
		}
		//这里做一个update操作
		$sql="UPDATE ".$this->TABLE_NAME." SET $values"." WHERE id=".$values["id"];	
		return $this->Query($sql);
	}
	 
	public function Delete()
	{
	$sql="DELETE FROM ".$this->TABLE_NAME." WHERE id=".$this->id;	
	return $this->Query($sql);
	}
	
	public function Add()
	{
		$head="(";
		$end="(";
		$i=1;
		foreach ($this->values as $key => $value) {
			$head.="$key ";
			if(is_string($value))
			{
				$end.="'".$value."'";
			}
			else
			{
				$end.=$value;
			}
			
			if($i<count($this->values)){
				$head.=" , ";
				$end.=" , ";
				$i++;
			}
			
	}
		$head.=")";
		$end.=")";
		$sql="INSERT INTO $this->TABLE_NAME $head VALUE $end";
		$result = $this->Query($sql);
		return $result;
	}
	
	public function Where($values, $region, $condition){
		$region.$this->prefix;
		$sql="select $values from $region where $condition";
		return $this->Query($sql);
	}
	
	public function Count($region, $condition)
	{
		$region.$this->prefix;
		return $this->Where(" COUNT(*) ", $this->prefix.$region, $condition);
	}
	
	public function CQ($sql, $values){
		
	}
	
	
	public function ToArr()
	{
		if($arr!=null)
		{
			return $arr;
		}
		else
		{
			
		}
	}
	
	public function ToMap()
	{	
	}
	
	public  function  __get($proname){//__get方法是系统调用的，添加此魔术方法之后，我们直接对象->属性名获取值时，系统会自动地调用这个方法，但是我们必须添加参数，以及添加方法体。	
	if(property_exists("BaseModel", $proname)){
		return $this->$proname;
	}
	else {
	return $this->values[$proname];
	}
	}
	
	public  function  __set($proname,$value){//设置参数，我们需要设置两个参数
	$this->values[$proname]=$value;
	}
	 
	public function CacheForId($id){
		$cache.=$id;
		
		
	}
	
	public function CacheForSql($sql)
	{
		$cache.=$aql;
	}
	
	public function geterror() {
		
	}
}




