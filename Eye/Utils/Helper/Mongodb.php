<?php
class MongoDB{
		protected $config;
		protected static $conn;
		protected $collection;
		
		public function _construct($config){
			$this->config=$config;
			$dns='mongodb://'.$config["username"].':'.$config["password"].'@'.$config["host"];
			try{
				$conn=new Mongo($dns,array("persist"=>"t"));
				$db=$conn->selectDB("mydb");
				$collection=$db->selectCollection('column');
			}catch(Mongoexception $e){
				print($e->getmessage());
				}
		}
		public function insert($array){
			try{
			$collection->insert($array,array('safe'=>false,'fsync'=>false,'timeout'=>10000));
			
		}catch(MongoCursorException $e){
			echo "can't save twice!\n";
		}
	}
	public function update($where,$newdata){
		$where=$where;
		$newdata=$newdata;
		$result=$collection->update($where,array('$set'=>$newdata),array('multiple'=>true));
	}
	public function remove($array){
		$array=$array;
		$collection->remove($array);
	}
	public function count($array){
		$array=$array;
		$result=$collection->count($array);
		return $result;
	}
	public function queryall(){
		$cursor=$collection->find()->snapshot();
	    show($cursor);
	}
	public function query($array){
		$cursor = $collection->find()->fields($array);
		show($cursor);
		}
	public function show($array){
		$array=$array;
		foreach($array as $id=>$value){
			echo "$id";
			var_dump($value);
			echo "<br>";
		}
	}
	public function close(){
		$conn->close();
	}
	
		

	
}
$config=array("dbname"=>"student","host"=>"127.0.0.1","username"=>"root","password"=>"12345678");
$mongodb=new MongoDB($config);


?>

