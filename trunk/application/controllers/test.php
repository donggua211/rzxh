<?php
/* 
  后台入口.
  公共权限
 */
class Test extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('User_model');
		
		//如果没有经登录, 就跳转到admin/login登陆页
		if (!has_login())
		{
			goto_login();
		}
		
		$this->user_info = get_user_info();
	}
	
	//默认首页
	function index()
	{
		cache_room_info();
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */