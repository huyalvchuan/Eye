<?php 
namespace Cycle\Controller;
	
 
class httpclient
{
  public 	$url;
  public 	$arr;
  public 	$method;
	
	public function __construct($url,$arr,$method)
	{
		$this->url=$url;
		$this->arr=$arr;
	    $this->method=$method;
	} 
	
 
	
	//获取请求后的结果。
	
	public function request()

   {
   	
	    $ch = curl_init();
		$timeout = 5;
		 
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
 
		$content = curl_exec($ch);
        
      echo $content;
		curl_close($ch);
		
		return  json_decode($content);	 
        
	
   }


}


	class Map
	{
		
		public function getdistance($x1,$y1,$x2,$y2)
		{
		$url="http://api.map.baidu.com/direction/v1?mode=driving&origin=".$x1.",".$y1."&destination=".$x2.",".$y2."&origin_region=上海&destination_region=上海&output=json&ak=ggZ0Me3AwB9oC1BjUWmObONz";
		// echo $url;
		$client=new httpclient($url,"","");
		return $client->request();
		
		
		}
	}
class Pay
	{
	 public $url="https://openapi.yiban.cn/pay/";
		public function payorder($token,$pay)
		{
			$url=$this->url."trade_wx?access_token=".$token."&pay=".$pay."&sign_back=http://www.chinayapa.com/cycle/heymyfriends/?m=Index&a=endride&yb_userid=".$userid;
			
			
			$client=new httpclient($url,"","");
			
			return $client->request();
			
		}
		
		public function trade($token,$userid,$pay)
		{
			$url=$this->url."trade_wx?access_token=".$token."&pay=".$pay."&sign_back=http://www.chinayapa.com/cycle/heymyfriends/?m=Index&a=endride&yb_userid=".$userid;
			$client=new httpclient($url,"","");
			
			return $client->request();
		}
		
		
		
	}
	
	class MQ
	{
		
		public function sendmessage($emailto,$subject,$message,$name)
		{
			
		
		   $url="http://www.chinayapa.com/cycle/heymyfriends/Application/Mail/emailto.php?email=".$emailto."&subject=".$subject."&message=".$message;
			
		   $client=new httpclient($url,"","");
			
	echo 	 $client->request();
			
		}
		
		
	}
	
	
	
	?>