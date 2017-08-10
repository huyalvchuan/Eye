<?php 
//易班接口封装
header("Content-type: text/html; charset=utf-8");
require ("../classes/yb-globals.inc.php");
require_once 'config.php';
require_once 'sdk.php';
class  yiban {
	
	//易班认证
	public function auth() {
		$api = YBOpenApi::getInstance() -> init($cfg['m']['appID'], $cfg['m']['appSecret'], $cfg['m']['callback']);
		$au = $api -> getAuthorize();

		$token = isset($_SESSION['__TOKEN__']) ? $_SESSION['__TOKEN__'] : false;

		if (empty($token)) {

			if (isset($_GET['code']) && !empty($_GET['code'])) {

				$info = $au -> querytoken($_GET['code']);

				if (isset($info['access_token'])) {
					$_SESSION['__TOKEN__'] = $info['access_token'];
					location();
				} else {

					//更具token获取用户的信息。

				}
			} else {
				header('location: ' . $au -> forwardurl());
			}
		} else {
			location();

		}
	}

 

	function location(){
		//如果要做跳转里面包含了用户的信息。请用sha1认证签名加密。
		header("location:" ."");
	}
	
	
	function getinfo($method) {
		$api = YBOpenApi::getInstance()->bind($_SESSION['__TOKEN__']);
		if($method="common") {
	  	$user = $api->getUser();
	   // $user->me();
		$content=$user->me();
      	 return $content;
		}
		else {
			
			$content2=$user->realme();
			return $content;
		}
	}
	
	//支付模块
	function pay( $orderid, $mount, $userid=null) {
		$paycore = new Pay();
		if($userid==null) {
		$result = $paycore -> trade($_SESSION['token'],$orderid,200);
		}
		else {
		$result = $paycore -> trade($_SESSION['token'], $orderid, $userid, 200);
		}
	}
	

	

	
}	
	
	
		

?>