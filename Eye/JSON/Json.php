<?php

namespace EYE;
class Json {
	public $name;
	public $Body;//body是一个键值数组用于存储类反射后的属性
	public $Value;
	public $Count;
	public function GetMe() {
		$class = new \ReflectionClass(get_class($this));
        $properties = $class -> getProperties();
		$arr=[];
		foreach ($properties as $property) {
		$this->Count++;
		$this->Body[$property->name]="";
		}
	}
}

$json=new Json();
$json->GetMe();


?>