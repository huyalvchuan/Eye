<?php 



class EyePdo
{
	protected $config;
	protected static $pdh;
	protected $res;
	
	public function __construct($config){
		$this->config=$config;
		$dsn='mysql:dbname='.$config["dbname"].';host='.$config["host"].'';
		try{
		$this->pdh= new PDO($dsn, $this->config['root'], $this->config['password'],array(PDO::ATTR_CASE, PDO::CASE_UPPER)); 
		$this->pdh->query('set names utf8;'); 
		$this->pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		} catch(PDOException $e){		
			print($e->getMessage());
		}
	}
	 
	public function  __GetInstance(){
		if($this->pdh==null){
			$this->pdh=new self($config);			
			return $this->pdh;
		}
		else{
			return $this->pdh;
		}
	}
	
	public function PreQuery($sql, $values){
		 $result=$this->pdh->prepare($sql);
		 $result->execute($values);
		 $row = $result->fetchAll(PDO :: FETCH_ASSOC);
		 return $row;	
	}
	//主要用于完成update delete insert操作。
	public  function CommonQuery($sql, $values=null){
		return $this->pdh->exec($sql);
	}
	public function Query($sql){
		return $this->pdh->query($sql)->fetchAll(PDO :: FETCH_ASSOC);
	}
	
	
	
	public function GetError(){
		return $this->pdh->errorInfo();
	}
	
	    /**
     * beginTransaction 事务开始
     */
    private function beginTransaction(){
        $this->dbh->beginTransaction();
    }
    
    /**
     * commit 事务提交
     */
    private function commit(){
        $this->pdh->commit();
    }
    
    /**
     * rollback 事务回滚
     */
    private function rollback(){
        $this->dbh->rollback();
    }
    
    /**
     * transaction 通过事务处理多条SQL语句
     * 调用前需通过getTableEngine判断表引擎是否支持事务
     *
     * @param array $arraySql
     * @return Boolean
     */
    public function execTransaction($arraySql){
        $retval = 1;
        $this->beginTransaction();
        foreach ($arraySql as $strSql) {
            if ($this->Query($strSql) == 0) $retval = 0;
        }
        if ($retval == 0) {
            $this->rollback();
            return false;
        } else {
            $this->commit();
            return true;
        }
    }
	
/**
     * getPDOError 捕获PDO错误信息
     */
    public function getPDOError()
    {
        if ($this->dbh->errorCode() != '00000') {
            $arrayError = $this->dbh->errorInfo();
            $this->outputError($arrayError[2]);
            return $arrayError;
        }
    }
    
    /**
     * debug
     * 
     * @param mixed $debuginfo
     */
    private function debug($debuginfo)
    {
        var_dump($debuginfo);
        exit();
    }
    
    /**
     * 输出错误信息
     * 
     * @param String $strErrMsg
     */
    private function outputError($strErrMsg)
    {
        throw new Exception('MySQL Error: '.$strErrMsg);
    }
    
    /**
     * destruct 关闭数据库连接
     */
    public function destruct()
    {
        $this->dbh = null;
    }
	 
}
 //$config=array("dbname"=>"student","host"=>"127.0.0.1","name"=>"root","password"=>"12345678");

?>