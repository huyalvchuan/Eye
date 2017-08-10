<?php
	/**
	 * @package YBAPI
	 */
	/**
	 * 站内应用接入辅助类
	 *
	 * 对使用站内应用接入方式的应用，提供快速进行授权认证方法
	 * 对于没通过授权的用户，直接跳转到授权认证页面（不需要自己实现跳转）
	 * 已通过授权的用户返回相关用户信息（包含访问令牌）
	 */
	class YBAPI_FrameUtil
	{
		const IAPP_AUTHURL = "https://openapi.yiban.cn/oauth/authorize";
		
		private $appid	= '';
		private $seckey	= '';
		private $backurl= '';
		
		/**
		 * 构造函数
		 *
		 * @param String 应用的APPID
		 * @param String 应用的Secret
		 * @param String 站内应用入口地址
		 */
		public function __construct($appid, $seckey, $backurl)
		{
			$this->appid	= $appid;
			$this->seckey	= $seckey;
			$this->backurl	= $backurl;
		}
		
		/**
		 * 对站内应用授权进行验证
		 *
		 * 对于站内应用使用iframe接入的方式，
		 * 认证时从POST的参数verify_request串中解密出相关授权信息
		 * 如已经授权，显示应用内容，
		 * 若末授权，则跳转到授权服务去进行授权
		 *
		 * @return Array	授权信息数据
		 */
		public function perform()
		{	
			if (!isset($_REQUEST['verify_request']) || empty($_REQUEST['verify_request']))
			{
				throw new YBException(YBLANG::E_EXE_PERFORM);
			}
			$encText = addslashes($_REQUEST['verify_request']);
			$strText = pack("H*", $encText);
			$decText = (strlen($this->appid) == 16)
						? mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->seckey, $strText, MCRYPT_MODE_CBC, $this->appid)
						: mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->seckey, $strText, MCRYPT_MODE_CBC, $this->appid);
			if (empty($decText))
			{
				throw new YBException(YBLANG::E_DEC_STRING);
			}
			$decInfo = json_decode(trim($decText), true);
			if (!is_array($decInfo) || !isset($decInfo['visit_oauth']))
			{
				throw new YBException(YBLANG::E_DEC_RESULT);
			}
			if (!$decInfo['visit_oauth'])
			{
				if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET')
				{
					return $this->appForAuth();
				}
				return $this->jumpForAuth();
			}
			return $decInfo;
		}
		
		private function appForAuth()
		{
			$location  = self::IAPP_AUTHURL;
			$location .= "?client_id=";
			$location .= $this->appid;
			$location .= "&redirect_uri=";
			$location .= urlencode($this->backurl);
			$location .= "&display=html";
			echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="refresh" content="0; url={$location}" />
<title>Redirect TO ...</title>
</head>
<body></body>
</html>
EOF;
		}
		
		/**
		 * 重定向到授权认证页面
		 *
		 * 在perform()方法中若未通过授权，
		 * 自动调用此方法重定向到授权服务器进行授权
		 *
		 * @param int 授权窗口高度
		 */
		private function jumpForAuth($height = 60)
		{
			echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Redirect TO ...</title>
<script src="http://f.yiban.cn/global/js/jquery1.11.0.min.js" type="text/javascript"></script>
<script src="http://f.yiban.cn/apps/js/authiframe.js" type="text/javascript"></script>
</head>
<body>
<script type="text/javascript">
$(function() {
	(function(){
		App.AuthDialog.show({
			client_id: "{$this->appid}",
			redirect_uri: "{$this->backurl}",
			height: {$height},
			scope:  ""
		});
	})();
});
</script>
</body>
</html>
EOF;
		}
		
		
		/**
		 * 站内应用页面自适应代码
		 *
		 * 在页面中嵌入此代码可以进行页面自适应
		 * 返回字符串，可以选择是否使用此代码进行自适应
		 * 
		 * @param int 宽
		 * @param int 高
		 * @param String Frame名称
		 * @return String 页面自适应代码
		 */
		public function adaptive($width = 1024, $height = 768, $framename = "c_iframe")
		{
			return <<<EOF
<iframe id="{$framename}" height="0" width="0" src="" style="display:none"></iframe>
<script type="text/javascript">
function setSize(w, h) {
	var c_iframe = document.getElementById("{$framename}");
		c_iframe.src = "http://f.yiban.cn/apps.html#"+w+"|"+h;
	}
setSize($width, {$height});
</script>
EOF;
		}
		
		
	}


?>