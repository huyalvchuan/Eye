<?php
	/**
	 * @package YBAPI
	 */
	/**
	 * 易班API的好友接口管理
	 *
	 * 查看自己的好友关系
	 */
	class YBAPI_Friend
	{
		private $token = '';
		
		const YIBAN_FRIEND_LIST		= "friend/me_list";
		const YIBAN_FRIEND_CHECK	= "friend/check";
		
		/**
		 * 构造函数
		 */
		public function __construct($token)
		{
			$this->token = $token;
		}
		
		
		/**
		 * 查看好友列表
		 * 
		 * 查看个人所有的好友，用page与count分页
		 *
		 * @param	int		分页码
		 * @param	int		页数
		 * @return	Array	好友列表
		 */
		public function myfriends($page, $count)
		{
			$param = array
			(
				'access_token'	=> $this->token,
				'page'			=> $page,
				'count'			=> $count
			);
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::YIBAN_FRIEND_LIST, $param);
		}
		
		/**
		 * 查询与指定用户的好友关系
		 *
		 * @param	int		指定用户ID
		 * @return	Array	好友关系数组
		 */
		public function checkuid($friendid)
		{
			$param = array
			(
				'access_token'	=> $this->token,
				'yb_friend_uid'	=> $friendid
			);
			return YBOpenApi::QueryURL(YBOpenApi::YIBAN_OPEN_URL . self::YIBAN_FRIEND_CHECK, $param);
		}
		
		
	}

?>