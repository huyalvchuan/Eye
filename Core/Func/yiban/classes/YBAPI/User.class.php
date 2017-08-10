<?php
	/**
	 * @package YBAPI
	 */
	/**
	 * 易班API的用户接口管理
	 *
	 * 用户管理，查看个人基本信息，实名信息及查看其它用户信息
	 */
	class YBAPI_User
	{
		private $token = '';
		
		const YIBAN_USER_ME_INFO	= "user/me";
		const YIBAN_USER_OTHER		= "user/other";
		const YIBAN_USER_REALME		= "user/real_me";
		
		/**
		 * 构造函数
		 */
		public function __construct($token)
		{
			$this->token = $token;
		}
		
		/**
		 * 个人基本信息
		 *
		 * 不包含实名信息，基本信息其它人可以查看
		 *
		 * @return	Array 个人基本信息数组
		 */
		public function me()
		{
			$param = array
			(
				'access_token'	=> $this->token
			);
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::YIBAN_USER_ME_INFO, $param);
		}
		
		/**
		 * 查看用户信息
		 *
		 * 通过指定用户ID查看其它用户的基本信息
		 * 
		 * @param	int		用户ID
		 * @return	Array	用户基本信息数组
		 */
		public function other($userid)
		{
			$param = array
			(
				'access_token'	=> $this->token,
				'yb_userid'		=> $userid
			);
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::YIBAN_USER_OTHER, $param);
		}
		
		/**
		 * 自己的实名信息
		 *
		 * 只能查看自己的实名信息，不能查看他人的 
		 * 
		 * @return	Array 用户实名信息数组
		 */
		public function realme()
		{
			$param = array
			(
				'access_token'	=> $this->token
			);
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::YIBAN_USER_REALME, $param);
		}
		
	}

?>