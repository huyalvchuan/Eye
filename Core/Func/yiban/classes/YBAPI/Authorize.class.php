<?php
	/**
	 * @package YBAPI
	 */
	/**
	 * 授权认证接口
	 *
	 * 使用授权认证协议认证方式的接口
	 * 授权接口中的接口对于appid或appsecret还有access_token有不同的需求
	 * 调用不同方法时需要开发人员保证已经把对应的配置值传入
	 */
	class YBAPI_Authorize
	{
		
		const API_OAUTH_CODE	= "oauth/authorize";
		const API_OAUTH_TOKEN	= "oauth/access_token";
		const API_TOKEN_QUERY	= "oauth/token_info";
		const API_TOKEN_REVOKE	= "oauth/revoke_token";
		
		/**
		 * 终端类型
		 */
		private $_T_DISPLAY = array('web', 'mobile', 'client');
		
		/**
		 * 构造函数
		 *
		 * 使用YBOpenApi里的config数组初始化
		 *
		 * @param Array 配置（对应YBOpenApi里的config数组）
		 */
		public function __construct($config)
		{
			foreach ($config as $key => $val)
			{
				$this->$key	= $val;
			}
		}
		
		/**
		 * 设置访问令牌
		 *
		 * @param String 访问令牌
		 * @return YBAPI_Authorize 本身实例
		 */
		public function bind($token)
		{
			$this->token = $token;
			return $this;
		}
		
		/**
		 * 生成授权认证地址
		 *
		 * 客户端重定向到授权地址
		 * 获取授权认证的CODE用于取得访问令牌
		 *
		 * @param	String 回调地址
		 * @param	String 防跨站伪造参数
		 * @param	String 授权页终端类型，默认web浏览器
		 * @return	String 授权认证页面地址
		 */
		public function forwardurl($state = 'QUERY', $display = 'web')
		{
			assert(!empty($this->appid),   YBLANG::E_NO_APPID);
			assert(!empty($this->seckey),  YBLANG::E_NO_APPSECRET);
			assert(!empty($this->backurl), YBLANG::E_NO_CALLBACKURL);
			assert(in_array($display, $this->_T_DISPLAY), YBLANG::E_TYPE_DISPLAY);
			
			$query	= http_build_query(array(
				'client_id'		=> $this->appid,
				'redirect_uri'	=> $this->backurl,
				'state'			=> $state,
				'display'		=> $display
			));
			return YBOpenApi::YIBAN_OPEN_URL . self::API_OAUTH_CODE . '?' . $query;
		}
		
		/**
		 * 通过授权的CODE获取访问令牌
		 *
		 * 应用服务器只需要请用此接口
		 * 自动处理重定向
		 *
		 * @param	String 授权CODE
		 * @param	String 应用回调地址
		 * @return	Array  访问令牌哈希数组
		 */
		public function querytoken($code, $redirect_uri = '')
		{
			assert(!empty($this->appid),   YBLANG::E_NO_APPID);
			assert(!empty($this->seckey),  YBLANG::E_NO_APPSECRET);
			
			if (empty($redirect_uri))
			{
				$redirect_uri = $this->backurl;
			}
			$param	= array(
				'client_id'		=> $this->appid,
				'client_secret'	=> $this->seckey,
				'code'			=> $code,
				'redirect_uri'	=> $redirect_uri
			);
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::API_OAUTH_TOKEN, $param, true);
		}
		
		
		/**
		 * 查询授权状态
		 *
		 * 如访问令牌的有效期等信息
		 *
		 * @return Array 授权信息哈希数组
		 */
		public function query()
		{
			assert(!empty($this->appid), YBLANG::E_NO_APPID);
			assert(!empty($this->token), YBLANG::E_NO_ACCESSTOKEN);
			
			$param = array(
				'client_id'		=> $this->appid,
				'access_token'	=> $this->token
			);
			
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::API_TOKEN_QUERY, $param, true);
		}
		
		/**
		 * 回收授权
		 *
		 * 回收通过授权的访问令牌
		 *
		 * @return Array 授权操作哈希数组
		 */
		public function revoke()
		{
			assert(!empty($this->appid), YBLANG::E_NO_APPID);
			assert(!empty($this->token), YBLANG::E_NO_ACCESSTOKEN);
			
			$param = array(
				'client_id'		=> $this->appid,
				'access_token'	=> $this->token
			);
			
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::API_TOKEN_REVOKE, $param, true);
		}
		
	}
	
?>