 <?php 
   //
	require Utils."Helper/PDO.php";
	require Utils."Helper/String.php";
 	class BaseData
 	{
 		
 		private $config;
		private static $pdo=null;
		public function __construct(){
			$config=require DIR."/Core/config.php";
			$dataconfig=$config["data"];
			if($this->pdo==null){
				$this->pdo=new EyePdo($dataconfig["client"]);
				return $this;
			}
			else{
				
				return $this->pdo;
			}
			
		}
		public function choosedata(){
			
		}
		
		public function innit(){
			
		}
		public function Query($sql, $value=null){
			$result;
			if($value==null){
				if(String::Contains($sql, "select")||String::Contains($sql, "SELECT")){
					$result=$this->pdo->Query($sql);
				}else {
					$result=$this->pdo->CommonQuery($sql);
					
				}
			}
			else {
				$result=$this->pdo->PreQuery($sql, $value);
			}
			if($this->pdo->GetError()[0] !="00000") {
				print_r($this->pdo->GetError());
			}
			
			return $result;
		}
		
		
 	}
 	
 	
 	?>