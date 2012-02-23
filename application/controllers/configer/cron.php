<?php
/* 
  作业类。
  1. 从socket读取数据，将数据存到本地。
  2. 对数据判断报警。
  3. 
 */
class Cron extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->helper('xml');
		$this->load->model('room_model');
		$this->load->model('device_model');
		$this->load->model('strategy_model');
		$this->load->model('history_model');
		$this->load->helper('email');
		$this->load->driver('device_drive');
		$this->load->driver('cache', array('adapter' => 'file'));
	}
	
	function index()
	{
		$rooms = $this->room_model->get_rooms();
		
		//循环获取各个房间的数据。
		foreach($rooms as $one_room)
		{
			echo '<a target="_blank" href="'.site_url('configer/cron/room/'.$one_room['room_id']).'">'.$one_room['room_name'].'</a><br/>';
		}
	}
	
	function room($room_id = 0)
	{
		if($room_id < 0)
			return false;
		
		//room 信息
		$room_info = $this->room_model->get_one_room($room_id);
		
		//获取本地缓存
		$device_status = $this->cache->get('room_'.$room_id);
		
		//本地获取设备
		$devices = $this->device_model->get_devices_by_room($room_id);
		
		//整理 devices 数组
		foreach($devices as $val)
		{
			$refined_devices[$val['device_cat']][$val['device_num']] = $val['device_id'];
		}
		
		//调用接口，获取设备值
		$api_result['switch_state'] = $this->device_drive->get_switch_state($room_info['room_num']);
		$api_result['poweralone_state'] = $this->device_drive->get_poweralone_state($room_info['room_num']);
		$api_result['powerbind_state'] = $this->device_drive->get_powerbind_state($room_info['room_num']);
		$api_result['number_state'] = $this->device_drive->get_number_state($room_info['room_num']);
		
		foreach($api_result as $cat => $result)
		{
			switch($cat)
			{
				case 'switch_state':
					$device_cat = DEVICE_CAT_SWITCH;
					$device_num_key = 'SwitchID';
					$device_status_key = 'SwitchState';
					break;
				case 'poweralone_state':
					$device_cat = DEVICE_CAT_POWERALONE;
					$device_num_key = 'PowerAloneID';
					$device_status_key = 'PowerAloneState';
					break;
				case 'powerbind_state':
					$device_cat = DEVICE_CAT_POWERBIND;
					$device_num_key = 'PowerBindID';
					$device_status_key = 'PowerBindState';
					break;
				case 'number_state':
					$device_cat = DEVICE_CAT_NUMBER;
					$device_num_key = 'NumberID';
					$device_status_key = 'NumberState';
					break;
			}
			
			foreach($result as $val)
			{
				$device_id = (isset($refined_devices[$device_cat][$val[$device_num_key]])) ? $refined_devices[$device_cat][$val[$device_num_key]] : '';
				
				if(empty($device_id) || !isset($val[$device_status_key]))
					continue;
				
				$device_status[$device_id] = is_array($val[$device_status_key]) ? -200 : $val[$device_status_key];
			}
		}
		
		//存入缓存
		if($device_status)
			$this->cache->save('room_'.$room_id, $device_status, 60 * 60 * 24);
		
		//策略判断
		$device_array = array_keys($devices);
		$device_strategy = $this->strategy_model->get_strategy_by_device_array($device_array);
		
		//设备值
		$device_status = $this->cache->get('room_'.$room_id);
		
		foreach($devices as $device_id => $device_info)
		{
			$value = isset($device_status[$device_id]) ? $device_status[$device_id] : -100;
			$strategy = isset($device_strategy[$device_id]) ? $device_strategy[$device_id] : array();
			
			if(is_array($value))
				$value = -200;
			
			if($value < 0)
			{
				$device['status'] = $value;
				$avtive_strategy = array();
			}
			elseif(empty($strategy))
			{
				$device['status'] = 0;
				$avtive_strategy = array();
			}
			else
			{
				//策略判断
				$max_status = 0;
				$avtive_strategy = array();
				foreach($strategy as $one)
				{
					if(!check_strategy_available($one['period']))
						continue;
						
					if(($one['condition'] == 'gt') && !($value > $one['value']))
					continue;
				
					if(($one['condition'] == 'eq') && !($value == $one['value']))
						continue;
						
					if(($one['condition'] == 'lt') && !($value < $one['value']))
						continue;
					
					
					if($max_status < $one['warning_level'])
					{
						$max_status = $one['warning_level'];
						$avtive_strategy = $one;
					}
				}
				
				
				$device['status'] = $max_status;
			}
			
			$device['val'] = $value;
			
			//插入历史记录
			$last_history = $this->history_model->get_last_history_be_device($device_id);
			
			if(empty($last_history) || abs($last_history['value'] - $value) > $device_info['error_range'] )
			{
				$history['device_id'] = $device_id;
				$history['room_id'] = $room_id;
				$history['value'] = $value;
				$this->history_model->add_history($history);
			}
			
			if($device['status'] != 0)
			{
				//报警
				if(!empty($avtive_strategy))
				{
					$users = $this->user_model->get_user_by_strategy($avtive_strategy['strategy_id']);
					
					//短信
					
					
					
					
					//email
					
					foreach($users as $val)
						send_email($val['email'], $room_info['room_name'].'：'.$device_info['device_name'].'-'.$max_status.'级报警', $avtive_strategy['warning_content']);
				}
				//插入报警记录
				$history = array();
				$history['warning_level'] = $device['status'];
				$history['device_id'] = $device_id;
				$history['room_id'] = $room_id;
				$history['value'] = $value;
				$this->history_model->add_warning_history($history);
			}
		}
		
		echo 'ok';
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */