<?php
/* 
  接口类 
  公共权限
 */
class api extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('room_model');
		$this->load->model('device_model');
		$this->load->model('strategy_model');
		$this->load->driver('cache', array('adapter' => 'file'));
		
		$this->load->library('Services_JSON');
		//如果没有经登录, 就跳转到admin/login登陆页
		if (!has_login())
		{
			goto_login();
		}
		
		$this->user_info = get_user_info();
	}
	
	//根据设备id获取所有策略
	function get_strategy()
	{
		$device_id = $this->input->post('device_id');
		$strategy = $this->strategy_model->get_strategy_by_device($device_id);
		if(empty($strategy))
			echo '-1';
		else
		{
			$strategy_group = $this->strategy_model->get_strategy_group_by_strategy(array_keys($strategy));
			foreach($strategy_group as $strategy_id => $val)
			{
				$strategy[$strategy_id]['groups'] = $val;
			}
			
			echo urldecode($this->services_json->encode($strategy));
		}
	}
	
	//获取一个机房内的所有设备
	function get_device()
	{
		$room_id = $this->input->post('room_id');
		$device = $this->device_model->get_devices_by_extendinterface($room_id, 0);
		
		foreach($device as $device_id => $device_info)
		{
			$arr[$device_id] = $device_info['device_name'];
		}
		
		echo urldecode($this->services_json->encode($arr));
	}
	
	//获取一条策略的信息
	function get_one_strategy()
	{
		$strategy_id = $this->input->post('strategy_id');
		$strategy = $this->strategy_model->get_one($strategy_id);
		
		if(empty($strategy))
			echo '-1';
		else
		{
			echo urldecode($this->services_json->encode($strategy));
		}
	}
	
	//或者所有设备的状态, 会被controller: room, mothed: index 调用
	function get_device_status()
	{
		$room_id = $this->input->post('room_id');
		$extendinterface_id = $this->input->post('extendinterface_id');
		$device_str = $this->input->post('devices');
		
		//获取策略
		if(empty($device_str))
		{
			$device = $this->device_model->get_devices_by_room($room_id);
			$device_array = array_keys($device);
		}
		else
		{
			$device_array = explode(',', $device_str);
		}
		
		$device_strategy = $this->strategy_model->get_strategy_by_device_array($device_array);
		
		//设备值
		$device_status = $this->cache->get('room_'.$room_id);
		
		$arr = array();
		foreach($device_array as $device_id)
		{
			$value = isset($device_status[$device_id]) ? $device_status[$device_id] : -100;
			$strategy = isset($device_strategy[$device_id]) ? $device_strategy[$device_id] : array();
			
			$arr[$device_id]['val'] = $value;
			$arr[$device_id]['device_id'] = $device_id;
			
			if($value == DEVICE_STATE_GET_NONE)
			{
				$arr[$device_id]['status'] = -1;
				$arr[$device_id]['active_strategy'] = array('strategy_name' => DEVICE_STATE_GET_NONE_TEXT, 'warning_content' => DEVICE_STATE_GET_NONE_TEXT, 'sound_alert' => '0');
				continue;
			}elseif($value == DEVICE_STATE_GET_FAILED)
			{
				$arr[$device_id]['status'] = -1;
				$arr[$device_id]['active_strategy'] = array('strategy_name' => DEVICE_STATE_GET_FAILED_TEXT, 'warning_content' => DEVICE_STATE_GET_FAILED_TEXT, 'sound_alert' => '0');
				continue;
			}elseif($value == DEVICE_STATE_GET_EMPTY)
			{
				$arr[$device_id]['status'] = 0;
				$arr[$device_id]['active_strategy'] = array('strategy_name' => DEVICE_STATE_GET_EMPTY_TEXT, 'warning_content' => DEVICE_STATE_GET_EMPTY_TEXT, 'sound_alert' => '0');
				continue;
			}
			
			if(empty($strategy))
			{
				$arr[$device_id]['status'] = 0;
				$arr[$device_id]['active_strategy'] = array('strategy_name' => '', 'warning_content' => '', 'sound_alert' => '0');
				continue;
			}
			
			//策略判断
			$max_status = 0;
			$active_strategy = array();
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
				
				//有报警处理：
				//更新max status
				if($max_status < $one['warning_level'])
				{
					$max_status = $one['warning_level'];
					$active_strategy = $one;
				}
				
				//判断level级别
				if($one['warning_level'] > 0)
				{
					//发邮件
					
					
					
					//短信
				
				
				}
			}
			
			$arr[$device_id]['status'] = $max_status;
			$arr[$device_id]['active_strategy'] = $active_strategy;
			$arr[$device_id]['sound_alert'] = $active_strategy['sound_alert'];
		}
		
		$arr = device_sort($arr);
		echo urldecode($this->services_json->encode($arr));
	}
	
	//获取所有机房的状态，会被controller: room, mothed: room 调用
	function get_room_status()
	{
		$rooms = $this->room_model->get_rooms();
		
		$arr = array();
		foreach($rooms as $val)
		{
			if(check_user_role($val['room_id']) < GROUP_ROLE_READABLE)
				continue;
			
			$device = $this->device_model->get_devices_by_room($val['room_id']);
			
			//获取策略
			$device_array = array_keys($device);
			$device_strategy = $this->strategy_model->get_strategy_by_device_array($device_array);
			
			//设备值
			$device_status = $this->cache->get('room_'.$val['room_id']);
			
			$max_room_status = 0;
			foreach($device as $device_id => $device_info)
			{
				$value = isset($device_status[$device_id]) ? $device_status[$device_id] : -100;
				$strategy = isset($device_strategy[$device_id]) ? $device_strategy[$device_id] : array();
				
				if($value == DEVICE_STATE_GET_NONE)
				{
					$arr[$val['room_id']]['device'][$device_id]['val'] = $value;
					$arr[$val['room_id']]['device'][$device_id]['name'] = $device_info['device_name'];
					$arr[$val['room_id']]['device'][$device_id]['status'] = -1;
					$arr[$val['room_id']]['device'][$device_id]['active_strategy'] = array('strategy_name' => DEVICE_STATE_GET_NONE_TEXT, 'warning_content' => DEVICE_STATE_GET_NONE_TEXT, 'sound_alert' => '0');
					$max_room_status = -1;
					continue;
				}elseif($value == DEVICE_STATE_GET_FAILED)
				{
					$arr[$val['room_id']]['device'][$device_id]['val'] = $value;
					$arr[$val['room_id']]['device'][$device_id]['name'] = $device_info['device_name'];
					$arr[$val['room_id']]['device'][$device_id]['status'] = -1;
					$arr[$val['room_id']]['device'][$device_id]['active_strategy'] = array('strategy_name' => DEVICE_STATE_GET_FAILED_TEXT, 'warning_content' => DEVICE_STATE_GET_FAILED_TEXT, 'sound_alert' => '0');
					$max_room_status = -1;
					continue;
				}elseif($value == DEVICE_STATE_GET_EMPTY)
				{
					continue;
				}
				
				if(empty($strategy))
				{
					continue;
				}
				
				//策略判断
				$max_status = 0;
				$active_strategy = array();
				foreach($strategy as $key => $one)
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
						$active_strategy = $one;
					}
				}
				
				if($max_status != 0)
				{
					$arr[$val['room_id']]['device'][$device_id]['val'] = $value;
					$arr[$val['room_id']]['device'][$device_id]['name'] = $device_info['device_name'];
					$arr[$val['room_id']]['device'][$device_id]['status'] = $max_status;
					$arr[$val['room_id']]['device'][$device_id]['active_strategy'] = $active_strategy;
				}
				
				$max_room_status = ($max_room_status == -1) ? -1 : (($max_room_status > $max_status) ? $max_room_status : $max_status);
			}
			
			$arr[$val['room_id']]['status'] = $max_room_status;
			if(isset($arr[$val['room_id']]['device']))
			{
				$arr[$val['room_id']]['device'] = device_sort($arr[$val['room_id']]['device']);
			}
		}
		
		echo urldecode($this->services_json->encode($arr));
	}
	
	//更新排序
	function update_rank()
	{
		$room_id = $this->input->post('room_id');
		$extendinterface_id = $this->input->post('extendinterface_id');
		$device_id = $this->input->post('device_id');
		$prev_device_id = $this->input->post('prev_device_id');
		$next_device_id = $this->input->post('next_device_id');
		
		$device_info = $this->device_model->get_devices_by_extendinterface($room_id, $extendinterface_id);
		
		foreach($device_info as $val)
			$orig_rank[$val['device_id']] = $val['rank'];
		
		$this_rank = $orig_rank[$device_id];
		$next_rank = isset($orig_rank[$next_device_id]) ? $orig_rank[$next_device_id] : end($orig_rank);
		$prev_rank = isset($orig_rank[$prev_device_id]) ? $orig_rank[$prev_device_id] : 1;
		
		//排序无变化
		if($prev_rank < $this_rank && $this_rank < $next_rank)
		{
			echo 'OK';
			return true;
		}
		//前移
		elseif($this_rank > $next_rank)
		{
			$this->device_model->update_devices_rank($room_id, $extendinterface_id, '`rank` = `rank`+1', 'rank >= '.$next_rank.' AND rank < '.$this_rank);
			$this->device_model->update_devices_rank($room_id, $extendinterface_id, 'rank = '.$next_rank, 'device_id = '.$device_id);
		}
		//后移
		elseif($this_rank < $prev_rank)
		{
			$this->device_model->update_devices_rank($room_id, $extendinterface_id, '`rank` = `rank`-1', 'rank <= '.$prev_rank.' AND rank > '.$this_rank);
			$this->device_model->update_devices_rank($room_id, $extendinterface_id, 'rank = '.$prev_rank, 'device_id = '.$device_id);
		}
		
		echo 'OK';
	}
	
	//删除一个策略
	function delete_one_strategy()
	{
		//判断staff_id是否合法.
		$strategy_id = $this->input->post('strategy_id');
		if(empty($strategy_id))
		{
			show_error_page('您输入的参数不合法, 请返回重试.', 'admin/user');
			return false;
		}
		
		$strategy_info = $this->strategy_model->get_one($strategy_id);
		
		if($this->strategy_model->delete($strategy_id))
		{
			//删除user group
			$this->strategy_model->delete_strategy_group($strategy_id);
			echo 'OK';
		}
		else
		{
			$notify = '删除失败, 请重试.';
			echo 'NG';
		}
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */