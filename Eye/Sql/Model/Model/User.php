<?php
/**
* 
*/
class User extends BaseModel
{
	//下面的属性对应的是User表里面的属性
	public $id;
	public $name;
    public $TABLE_NAME;
	public $TABLE_INSTRUCTION;

	function __construct($argument)
	{
		
	}
}

?>