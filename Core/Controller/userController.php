<?php
	class userController extends Eye {
		function login () {
			$request = $this->I("json");
			$code = $request->code;
			$wx = $this->doaction("wx");
			$wx->init($this);
			$userdata = $wx->getOpenid($code);
			$db=$this->M();
			$user = $db->select("user_account", "id", [
					"openid"=>$userdata->openid
				]
			);
			if(count($user) == 0) {
				$this->mylog("openid:".$userdata->openid);
				$this->reasponse(10001, "登录失败你将获得该用户的openid", ["openid"=>$userdata->openid]);
			} 
			if(count($user) == 1) {
				$token = $this->loginService($user[0]);
				$this->reasponse(10006, "登录成功", ["token"=>$token]);
			}
		}
		function pcLogin() {
			$request = $this->I("json");
			$db = $this->M();
			$user = $db->select("user_account",
				["id", "wx_name", "r_name", "avatar"], [
				"AND"=>[
				"email"=>$request->username,
				"pwd"=>$request->pwd
				]]
			);
			if(count($user) == 0) {
				$this->reasponse(10001, "登录失败", "无情拒绝");
			} else {
				$token = $this->loginService($user[0]);
				$this->reasponse(10006, "登录成功", ["token"=>$token, "userInfo"=>$user[0]]);
			}
		}
		function edit () {
			$request = $this->I("json");
		}
		function check () {}
		/**
		 * @parms 
		 */
		function userExit () {
		}
		function register() {
			$request = $this->I("json");
			$db = $this->M();
			$request->userArray = json_decode(json_encode($request->userArray),true);  
			$this->mylog($request->userArray);
			$user = $db->select("user_account", "id", [
					"openid"=>$request->userArray["openid"]
				]
			);
			if(count($user) > 0) {
				$this->reasponse(10008, "重复注册", null);
				return;
			}
			$result = $db->insert("user_account", $request->userArray);
			$this->mylog($db->error().$db->last_query());
			$token = $this->loginService($result);
			$this->reasponse(10006, "注册成功", ["token"=>$token]);
		}
		function loginService($userId) {
			$reIP=$_SERVER["REMOTE_ADDR"]; 
		    $token = md5(time().$result[0].rand(0, 1000)."zhiliao");
			$this->M()->update("user_account", [
				"last_login_ip"=>$reIP,
				"last_login_time"=>date("Y-m-d H:i:s"),
				"token"=>$token
			], [
				"id"=>$userId
			]);
			return $token;
		}
	}
?>