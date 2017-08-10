<?php
	/**
	 * 易班开放平台SDK
	 *
	 * 单例，使用此对象初始化其它YBAPI的实例对象。
	 */
	class YBOpenApi
	{
		
		const YIBAN_OPEN_URL = "https://openapi.yiban.cn/";
		
		private $ybapi_path;
		
		private static $mpInstance = NULL;
		
		private $_config = array(
			'appid'	 => '',
			'seckey' => '',
			'token'	 => '',
			'backurl'=> ''
		);
		
		private $_instance = array();
		
		
		/**
		 * 取YBOpenApi实例对象
		 * 
		 * 单例，其它的配置参数使用init()或bind()方法设置
		 */
		public static function getInstance()
		{
			if (self::$mpInstance == NULL)
			{
				self::$mpInstance = new self();
			}
			return self::$mpInstance;
		}
		
		/**
		 * 构造函数
		 * 
		 * 使用 YBOpenApi::getInstance() 初始化
		 */
		private function __construct()
		{
			$this->ybapi_path = YBAPI_CLASSESS_DIR . 'YBAPI' . DIRECTORY_SEPARATOR;
		}
		
		/**
		 * 初始化设置
		 *
		 * YBOpenApi对象的AppID、AppSecret、回调地址参数设定
		 *
		 * @param String 应用的APPID
		 * @param String 应用的AppSecret
		 * @param String 回调地址
		 * @return YBOpenApi 自身实例
		 */
		public function init($appID, $appSecret, $callback_url='')
		{
			$this->_config['appid']   = $appID;
			$this->_config['seckey']  = $appSecret;
			$this->_config['backurl'] = $callback_url;
			
			return self::$mpInstance;
		}
		
		/** 
		 * 设定访问令牌
		 *
		 * 如果已经取到访问令牌，使用此方法设定
		 * 大多的接口只需要访问令牌即可完成操作
		 * 这类接口不需要调用init()方法
		 *
		 * @param String 访问令牌
		 * @return YBOpenApi 自身实例
		 */
		public function bind($access_token)
		{
			$this->_config['token']  = $access_token;
			
			return self::$mpInstance;
		}
		
		/**
		 * 站内应用辅助接口类
		 * 
		 * 可以使用该类快速便捷的进行站内应用的授权认证
		 *
		 * @return YBAPI::FrameUtil
		 */
		public function getFrameUtil()
		{
			if (!class_exists('YBAPI_FrameUtil'))
			{
				include($this->ybapi_path . 'FrameUtil.class.php');
			}
			if (!isset($this->_instance['frameutil']))
			{
				assert(!empty($this->_config['appid']), YBLANG::E_NO_APPID);
				assert(!empty($this->_config['seckey']), YBLANG::E_NO_APPSECRET);
				assert(!empty($this->_config['backurl']), YBLANG::E_NO_CALLBACKURL);
				
				$this->_instance['frameutil'] = new YBAPI_FrameUtil(
					$this->_config['appid'],
					$this->_config['seckey'],
					$this->_config['backurl']
				);
			}
			return $this->_instance['frameutil'];
		}
		
		/**
		 * 授权接口功能类
		 *
		 * 通用的授权认证接口对象，可以对访问令牌进行查询回收操作
		 *
		 * @return YBAPI::Authorize
		 */
		public function getAuthorize()
		{
			if (!class_exists('YBAPI_Authorize'))
			{
				include($this->ybapi_path . 'Authorize.class.php');
			}
			if (!isset($this->_instance['authorize']))
			{
				$this->_instance['authorize'] = new YBAPI_Authorize($this->_config);
			}
			return $this->_instance['authorize'];
		}
		
		/**
		 * 授权接口功能类
		 *
		 * 通用的授权认证接口对象，可以对访问令牌进行查询回收操作
		 *
		 * @return YBAPI::Authorize
		 */
		public function getUser()
		{
			if (!class_exists('YBAPI_User'))
			{
				include($this->ybapi_path . 'User.class.php');
			}
			if (!isset($this->_instance['user']))
			{
	
			 
				assert(!empty($this->_config['token']), YBLANG::E_NO_ACCESSTOKEN);
				
				$this->_instance['user'] = new YBAPI_User($this->_config['token']);
			}
			return $this->_instance['user'];
		}
		
		/**
		 * 授权接口功能类
		 *
		 * 通用的授权认证接口对象，可以对访问令牌进行查询回收操作
		 *
		 * @return YBAPI::Authorize
		 */
		public function getFriend()
		{
			if (!class_exists('YBAPI_Friend'))
			{
				include($this->ybapi_path . 'Friend.class.php');
			}
			if (!isset($this->_instance['friend']))
			{
				assert(!empty($this->_config['token']), YBLANG::E_NO_ACCESSTOKEN);
				
				$this->_instance['friend'] = new YBAPI_Friend($this->_config['token']);
			}
			return $this->_instance['friend'];
		}
		
		
		/**
		 * HTTP请求辅助函数
		 *
		 * 对CURL使用简单封装，实现POST与GET请求
		 *
		 * @param String URL地址
		 * @param Array  参数数组
		 * @param Boolean 是否使用POST方式请求
		 * @param Array   服务返回的JSON数组
		 */
		public static function QueryURL($url, $param = array(), $isPOST = false)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			if ($isPOST)
			{
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
			}
			else if (!empty($param))
			{
				$xi   = parse_url($url);
				$url .= empty($xi['query']) ? '?' : '&';
				$url .= http_build_query($param);
			}
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			if ($result == false)
			{
				throw new YBException(curl_error($ch));
			}
			return json_decode($result, true);
		}
		
	}
	
	

?>