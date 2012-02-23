<?php
/* 
  后台入口.
  公共权限
 */
class Entry extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('room_model');
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
		$data['main_url'] = 'room';
		$this->load->view('common_frame', $data);
	}
	
	//左侧菜单
	function menu()
	{
		$rooms = $this->room_model->get_rooms();
		
		$data_main = array('rooms' => $rooms);
		$this->load->view('header');
		$this->load->view('common_menu', $data_main);
		$this->load->view('footer');
	}
	
	//页面顶部
	function top()
	{
		$data_main['user_info'] = $this->user_info;
		$this->load->view('header');
		$this->load->view('common_top', $data_main);
		$this->load->view('footer');
	}
	
	//拖拉条
	function drag()
	{
		$this->load->view('common_drag');
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */