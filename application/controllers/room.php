<?php
/* 
  机房控制器
 */
class Room extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('room_model');
		$this->load->model('device_model');
		$this->load->model('group_model');
		$this->load->model('period_model');
		$this->load->model('video_model');
		$this->load->model('User_model');
		$this->load->model('strategy_model');
		
		//如果没有经登录, 就跳转到admin/login登陆页
		if (!has_login())
		{
			goto_login();
		}
		
		$this->user_info = get_user_info();
		
	}
	
	function index($room_id = 1)
	{
		//room 信息
		$rooms = $this->room_model->get_rooms();
		
		//载入数据
		$data['rooms'] = $rooms;
		
		$this->load->view('header');
		$this->load->view('room_index', $data);
		$this->load->view('footer');
	}
	
	//获取一个机房信息：只显示灯
	function one($room_id = 1)
	{
		//room 信息
		$room_info = $this->room_model->get_one_room($room_id);
		
		//设备信息
		$device = $this->device_model->get_devices_by_room($room_id);
		
		//载入数据
		$data['room_info'] = $room_info;
		$data['device'] = $device;
		
		$this->load->view('header');
		$this->load->view('room_one', $data);
		$this->load->view('footer');
	}
	
	//获取一个机房的详细信息
	function detail($room_id = 1, $extendinterface_id = 0)
	{
		//room 信息
		$room_info = $this->room_model->get_one_room($room_id);
		$extend_interfaces = $this->room_model->get_extendinterfaces($room_id);
		
		//设备信息
		$device = $this->device_model->get_devices_by_extendinterface($room_id, $extendinterface_id);
		
		//获取策略名
		$strategy = $this->strategy_model->get_strategy_by_device_array(array_keys($device));
		
		//获取 video info
		$video_info = $this->video_model->get_one_room($room_id);
		
		//载入数据
		$data['room_info'] = $room_info;
		$data['extendinterface_id'] = $extendinterface_id;
		$data['groups'] = $this->group_model->get_all();
		$data['periods'] = $this->period_model->get_all();
		$data['extend_interfaces'] = $extend_interfaces;
		$data['device'] = $device;
		$data['strategy'] = $strategy;
		$data['video_info'] = $video_info;
		
		$this->load->view('header');
		$this->load->view('room_detail', $data);
		$this->load->view('footer');
	}
	
	//视频
	function video($room_id = 1)
	{
		//room 信息
		$room_info = $this->room_model->get_one_room($room_id);
		$extend_interfaces = $this->room_model->get_extendinterfaces($room_id);
		$video_info = $this->video_model->get_one_room($room_id);
		
		//载入数据
		$data['room_info'] = $room_info;
		$data['extend_interfaces'] = $extend_interfaces;
		
		$data['video_info'] = $video_info[0];
		
		$this->load->view('header');
		$this->load->view('room_video', $data);
		$this->load->view('footer');
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */