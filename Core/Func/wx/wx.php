<?php
	class wx {
		public $url;
		public $appId = "";
		public $appSecret = "";
		public $netClient;
		public $app;
		function init($app) {
			$this->app = $app;
			$this->netClient = $this->app->doaction("netclient"); 
			if($appId == null || $appSecret == "") {
				 return;
			}
		}
		function getOpenid($code) {
			$url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$this->appId."&secret=".$this->appSecret."&js_code=".$code."&grant_type=authorization_code";
			$this->netClient->init($url, "GET", "json");
			return $this->netClient->netGet();
		}
	}
?>
