<?php
/* 
  后台入口.
  公共权限
 */
class Strategy extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('strategy_model');
		
		//如果没有经登录, 就跳转到admin/login登陆页
		if (!has_login())
		{
			goto_login();
		}
		
		$this->user_info = get_user_info();
	}
	
	//添加策略
	function add()
	{
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$strategy['strategy_name'] = $this->input->post('strategy_name');
			$strategy['value'] = $this->input->post('value');
			$strategy['condition'] = $this->input->post('condition');
			$strategy['level'] = ($this->input->post('level')) ? $this->input->post('level') : 0;
			$strategy['content'] = $this->input->post('content');
			$strategy['device_id'] = $this->input->post('device_id');
			$strategy['room_id'] = $this->input->post('room_id');
			$strategy['period_id'] = $this->input->post('period_id');
			$strategy['sound_alert'] = ($this->input->post('sound_alert')) ? 1 : 0;
			
			$strategy['group_id'] = $this->input->post('group_id');
			
			if($strategy['value'] === FALSE || empty($strategy['condition']) || empty($strategy['content']) || empty($strategy['period_id']))
			{
				$notify = '请填写完整的策略信息';
				show_error_page($notify, 'room/detail/'.$strategy['room_id']);
			}
			else
			{
				//add into DB
				$insert_id = $this->strategy_model->insert_strategy($strategy);
				if($insert_id)
				{
					//插如 strategy_group 表
					$this->strategy_model->update_strategy_group($insert_id, array(), $strategy['group_id']);
					
					show_result_page('新策略已经添加成功! ', 'room/detail/'.$strategy['room_id']);
				}
				else
				{
					$notify = '添加失败, 请重试.';
					show_error_page($notify, 'room/detail/'.$strategy['room_id']);
				}
			}
		}
	}
	
	//编辑策略
	function edit()
	{
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$room_id = $this->input->post('room_id');
			$strategy_id = $this->input->post('strategy_id');
			
			$strategy['strategy_name'] = $this->input->post('strategy_name');
			$strategy['value'] = $this->input->post('value');
			$strategy['condition'] = $this->input->post('condition');
			$strategy['warning_level'] = ($this->input->post('level')) ? $this->input->post('level') : 0;
			$strategy['warning_content'] = $this->input->post('content');
			$strategy['device_id'] = $this->input->post('device_id');
			$strategy['period_id'] = $this->input->post('period_id');
			
			$strategy['sound_alert'] = $this->input->post('sound_alert');
			
			$strategy['groups'] = ($this->input->post('group_id')) ? $this->input->post('group_id') : array();
			
			$strategy_info = $this->strategy_model->get_one($strategy_id);
			
			//检查修改项
			$update_field = array();
			foreach($strategy as $key => $val)
			{
				if($key == 'groups')
					continue;
				
				if(($val !== FALSE) && ($val != $strategy_info[$key]))
					$update_field[$key] = $val;
			}
			
			if($this->strategy_model->update($strategy_id, $update_field))
			{
				//更新use group信息
				//要删除的group id
				$del_group_id = array();
				foreach($strategy_info['groups'] as $val)
				{
					if(!in_array($val, $strategy['groups']))
						$del_group_id[] = $val;
				}
				//要新加的group id
				$add_group_id = array();
				foreach($strategy['groups'] as $val)
				{
					if(!in_array($val, $strategy_info['groups']))
						$add_group_id[] = $val;
				}
				
				$this->strategy_model->update_strategy_group($strategy_id, $del_group_id, $add_group_id);
				
				show_result_page('更新成功! ', 'room/detail/'.$room_id);
			}
			else
			{
				show_result_page('更新失败, 请重试! ', 'room/detail/'.$room_id);
			}
		}
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */