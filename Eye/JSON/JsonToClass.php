<?php 

namespace Json;
class JsonToClass extends  Json
{
	
	public static function Transformation($jsonstl)
	{
	   $jsonobject=(array)json_encode($jsonstl);
       foreach($jsonobject as $arrvalue)
       {
         foreach($arrvalue as $key=>$value)
         {
         	$this->Body[$key]=$value;
         }
       }
	   return $this;
	}
	//对于所有的值，才有一个可替换策略，值的括号类型，和所有值的括号类型
	public function ProduceValue($valuebrackets,$toltalbrackets,$arr)
	{
		$stt=$toltalbrackets;
	  for($i=0;$i<count($arr);$i++)
		{
			
			$stt+=$valuebrackets+$arr[$i]+$valuebrackets;
			if($i!=count($arr)-1)
			$stt+=",";
		}
		
		$stt+=$toltalbrackets;
		return $stt;
	}
	
}

?>