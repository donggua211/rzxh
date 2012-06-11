<?php
/* 
  配置同步文件. 将接口读取的数据存取本地数据库
  公共权限
 */
set_time_limit(0);

class Synch extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('http_multirequest');
		$this->load->helper('xml');
		$this->load->model('room_model');
		$this->load->model('device_model');
		$this->load->model('video_model');
		$this->load->driver('device_drive');
		
		$this->user_info = get_user_info();
	}
	
	function index()
	{
		$step = ($this->input->post('step')) ? $this->input->post('step') : 0;
		
		if($step == 0) {
			$this->load->view('header');
			$this->load->view('synch/step0.php');
			$this->load->view('footer');
		}
		//step1: 清理数据
		elseif($step == 1) {
			//执行清理
			$this->truncate_data();
			$this->load->view('header');
			$this->load->view('synch/step1.php');
			$this->load->view('footer');
		}
		//step2: 载入机房数据
		elseif($step == 2)
		{
			//执行清理
			$data['rooms'] = $this->rooms();
			$this->load->view('header');
			$this->load->view('synch/step2.php', $data);
			$this->load->view('footer');
		}
		//step3: 载入设备和拓展截面数据
		elseif($step == 3)
		{
			//从本地获取 room 数据
			$rooms = $this->room_model->get_rooms();
			$crontab_url = site_url('configer/synch/room/');
			
			foreach($rooms as $room)
			{
				$urls[] = $crontab_url.'/'.$room['room_id'];
			}
			
			$this->http_multirequest->setUrls($urls);

			//parallel fetch（并行抓取）:
			$data['result'] = $this->http_multirequest->exec();

			$this->load->view('header');
			$this->load->view('synch/step3.php', $data);
			$this->load->view('footer');
		}
		//执行一次数据同步
		elseif($step == 4)
		{
			//从本地获取 room 数据
			$rooms = $this->room_model->get_rooms();
			$crontab_url = base_url().'crontab/device_data.php';
			
			$urls[] = $crontab_url;
			
			$this->http_multirequest->setUrls($urls);

			//parallel fetch（并行抓取）:
			$data['result'] = $this->http_multirequest->exec();

			$this->load->view('header');
			$this->load->view('synch/step4.php', $data);
			$this->load->view('footer');
		}
	}
	
	function truncate_data()
	{
		$this->room_model->truncate_room();
		$this->room_model->truncate_extendinterfaces();
		$this->device_model->truncate_device();
	}
	
	function rooms()
	{
		//获取room数据。
		$rooms = $this->device_drive->get_room_name();
		
		if(empty($rooms))
		{
			show_error('controller/configer/synch/index. can not load rooms');
		}
		
		$this->room_model->insert_rooms($rooms, true);
		
		//refresh cached room info
		cache_room_info();
		
		return $rooms;
	}
	
	function room($room_id = 0)
	{
		if($room_id < 0)
			return false;
		
		//room 信息
		$room = $this->room_model->get_one_room($room_id);
			
		echo 'room_id<br/>';
		
		$result = $this->device_drive->get_extendinterface_name($room['room_num']);
		
		$ei_arr = array();
		if(!empty($result))
		{
			$this->room_model->insert_extendinterface($result, $room['room_id']);
			foreach($this->room_model->get_extendinterfaces($room['room_id']) as $val)
				$ei_arr[$val['extendinterface_num']] = $val['extendinterface_id'];
		}
		
		$result = $this->device_drive->get_switch_device_name($room['room_num']);
		$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_SWITCH, $ei_arr);
		
		$result = $this->device_drive->get_poweralone_device_name($room['room_num']);
		$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_POWERALONE, $ei_arr);
		
		$result = $this->device_drive->get_powerbind_device_name($room['room_num']);
		$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_POWERBIND, $ei_arr);
		
		$result = $this->device_drive->get_number_device_name($room['room_num']);
		$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_NUMBER, $ei_arr);
		
		$result = $this->device_drive->get_video_list($room['room_num']);
		$this->video_model->insert_video($result, $room['room_id']);
	}
	
	function device()
	{
		//清空设备信息
		$this->room_model->truncate_extendinterfaces();
		$this->device_model->truncate_device();
		
		//从本地获取 room 数据
		$rooms = $this->room_model->get_rooms();
		
		foreach($rooms as $room)
		{
			if($room['room_id'] <= 23 || $room['room_id'] >= 27)
				continue;
				
			echo '<h1>正在处理: '.$room['room_name'].'.....</h1>';
			
			echo '<h3>处理扩展界面</h3>';
			$result = $this->device_drive->get_extendinterface_name($room['room_num']);
			
			$ei_arr = array();
			if(!empty($result))
			{
				$this->room_model->insert_extendinterface($result, $room['room_id']);
				foreach($this->room_model->get_extendinterfaces($room['room_id']) as $val)
					$ei_arr[$val['extendinterface_num']] = $val['extendinterface_id'];
			}
			
			echo '<h3>处理 switch </h3><br/>';
			$result = $this->device_drive->get_switch_device_name($room['room_num']);
			$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_SWITCH, $ei_arr);
			
			
			echo '<h3>处理 power alone </h3><br/>';
			$result = $this->device_drive->get_poweralone_device_name($room['room_num']);
			$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_POWERALONE, $ei_arr);
			
			
			echo '<h3>处理 power bind </h3><br/>';
			$result = $this->device_drive->get_powerbind_device_name($room['room_num']);
			$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_POWERBIND, $ei_arr);
			
			
			echo '<h3>处理 number </h3><br/>';
			$result = $this->device_drive->get_number_device_name($room['room_num']);
			$this->device_model->insert_device($result, $room['room_id'], DEVICE_CAT_NUMBER, $ei_arr);
			
			echo '<h3>处理 video </h3><br/>';
			$result = $this->device_drive->get_video_list($room['room_num']);
			$this->video_model->insert_video($result, $room['room_id']);
			
		}
	}
	
	function video()
	{
		$room['room_id'] = 1;
		$room['room_num'] = 19;
		$result = $this->device_drive->get_video_list($room['room_num']);
		
		$this->video_model->insert_video($result, $room['room_id']);
	}
	
	function repair_room($room_id = 0)
	{
		if($room_id < 0)
			return false;
		
		//room 信息
		$room = $this->room_model->get_one_room($room_id);
			
		echo 'room_id'.$room_id.'<br/><pre>';
		
		$ex_db = $this->room_model->get_extendinterfaces($room_id);
		$ei_db_num_arr = array();
		foreach($ex_db as $val)
			$ei_db_num_arr[] = $val['extendinterface_num'];
		
		$result = $this->device_drive->get_extendinterface_name($room['room_num']);
		
		$ei_arr = array();
		if(!empty($result))
		{
			$add_ei_list = array();
			foreach($result as $val)
			{
				if(!in_array($val['ExtendInterfaceID'], $ei_db_num_arr))
					$add_ei_list[] = $val;			
			}
			
			$this->room_model->insert_extendinterface($add_ei_list, $room['room_id']);
			
			foreach($this->room_model->get_extendinterfaces($room['room_id']) as $val)
				$ei_arr[$val['extendinterface_num']] = $val['extendinterface_id'];
		}
		
		//get device from db
		$device_db = $this->device_model->get_devices_by_room($room_id);
		$device_db_num_arr = array();
		foreach($device_db as $val)
			$device_db_num_arr[] = $val['device_num'];
		
		
		$result = $this->device_drive->get_switch_device_name($room['room_num']);
		//check device
		$add_switch_device_list = array();
		foreach($result as $val)
		{
			if(!in_array($val['SwitchID'], $device_db_num_arr))
				$add_switch_device_list[] = $val;			
		}
		echo 'add_switch_device_list';
		print_r($add_switch_device_list);
		$this->device_model->insert_device($add_switch_device_list, $room['room_id'], DEVICE_CAT_SWITCH, $ei_arr);
		
		
		$result = $this->device_drive->get_poweralone_device_name($room['room_num']);
		//check device
		$add_poweralone_device_list = array();
		foreach($result as $val)
		{
			if(!in_array($val['PowerAloneID'], $device_db_num_arr))
				$add_poweralone_device_list[] = $val;			
		}
		echo 'add_poweralone_device_list';
		print_r($add_poweralone_device_list);
		$this->device_model->insert_device($add_poweralone_device_list, $room['room_id'], DEVICE_CAT_POWERALONE, $ei_arr);
		
		
		$result = $this->device_drive->get_powerbind_device_name($room['room_num']);
		//check device
		$add_powerbind_device_list = array();
		foreach($result as $val)
		{
			if(!in_array($val['PowerBindID'], $device_db_num_arr))
				$add_powerbind_device_list[] = $val;			
		}
		echo 'add_powerbind_device_list';
		print_r($add_powerbind_device_list);
		$this->device_model->insert_device($add_powerbind_device_list, $room['room_id'], DEVICE_CAT_POWERBIND, $ei_arr);
		
		
		$result = $this->device_drive->get_number_device_name($room['room_num']);
		//check device
		$add_number_device_list = array();
		foreach($result as $val)
		{
			if(!in_array($val['NumberID'], $device_db_num_arr))
				$add_number_device_list[] = $val;			
		}
		echo 'add_number_device_list';
		print_r($add_number_device_list);
		$this->device_model->insert_device($add_number_device_list, $room['room_id'], DEVICE_CAT_NUMBER, $ei_arr);
		
		
		$vedio_db = $this->video_model->get_one_room($room_id);
		$device_vedio_arr = array();
		foreach($vedio_db as $val)
			$device_vedio_arr[] = $val['name'];
		
		$result = $this->device_drive->get_video_list($room['room_num']);
		//check device
		$add_video_device_list = array();
		foreach($result as $val)
		{
			if(!in_array($val['VideoName'], $device_vedio_arr))
				$add_video_device_list[] = $val;			
		}
		echo 'add_video_device_list';
		print_r($add_video_device_list);
		$this->video_model->insert_video($add_video_device_list, $room['room_id']);
		
		echo '</pre><hr/>';
	}
	
	function repair()
	{
		$step = ($this->input->post('step')) ? $this->input->post('step') : 1;
		if($step == 1)
		{
			//repaire room info
			$room_socket = $this->device_drive->get_room_name();
			$rooms_db = $this->room_model->get_rooms();
			
			$rooms_db_roomnum = array();
			foreach($rooms_db as $val)
				$rooms_db_roomnum[] = $val['room_num'];
			
			//check room
			$add_room_list = array();
			foreach($room_socket as $val)
			{
				if(!in_array($val['RoomID'], $rooms_db_roomnum))
					$add_room_list[] = $val;			
			}
			
			$this->room_model->insert_rooms($add_room_list, false);
			
			$data['add_room_list'] = $add_room_list;
			$this->load->view('header');
			$this->load->view('synch/repair_step1.php', $data);
			$this->load->view('footer');
		}
		//step2: 载入设备和拓展截面数据
		elseif($step == 2)
		{
			//从本地获取 room 数据
			$rooms = $this->room_model->get_rooms();
			$crontab_url = site_url('configer/synch/repair_room/');
			
			foreach($rooms as $room)
			{
				$urls[] = $crontab_url.'/'.$room['room_id'];
			}
			
			$this->http_multirequest->setUrls($urls);

			//parallel fetch（并行抓取）:
			$data['result'] = $this->http_multirequest->exec();

			$this->load->view('header');
			$this->load->view('synch/repair_step2.php', $data);
			$this->load->view('footer');
		}
		//执行一次数据同步
		elseif($step == 3)
		{
			//从本地获取 room 数据
			$rooms = $this->room_model->get_rooms();
			$crontab_url = base_url().'crontab/device_data.php';
			
			$urls[] = $crontab_url;
			
			$this->http_multirequest->setUrls($urls);

			//parallel fetch（并行抓取）:
			$data['result'] = $this->http_multirequest->exec();

			$this->load->view('header');
			$this->load->view('synch/step4.php', $data);
			$this->load->view('footer');
		}
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */