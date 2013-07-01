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
		$this->load->driver('device_drive');
		
		$room_num = 19;
		
		$api_result['switch_state'] = $this->device_drive->get_room_name();
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */