<?php

class Period extends CI_Controller {

	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->model('period_model');
		$this->load->library('session');
		
		//如果没有经登录, 就跳转到admin/login登陆页
		if (!has_login())
		{
			goto_login();
		}
		
		//访问权限：管理员。如果不是管理员，则显示access deny页面。
		if(!is_admin())
		{
			show_access_deny_page();
		}
		
		$this->user_info = get_user_info();
	}
	
	function index()
	{
		$periods = $this->period_model->get_all();
		
		foreach($periods as $key => $val)
			$periods[$key]['period_arr'] = period_str_to_arr($val['period']);
		
		$data['header']['meta_title'] = '时间段列表 - 时间段管理';
		$data['main']['periods'] = $periods;
		_load_viewer('admin/period_all', $data);
	
	}
	
	function add()
	{
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$new_period['period_name'] = $this->input->post('period_name');
			
			//时间
			$period['period'] = $this->input->post('period');
			$period['s_hour'] = $this->input->post('s_hour');
			$period['s_mins'] = $this->input->post('s_mins');
			$period['e_hour'] = $this->input->post('e_hour');
			$period['e_mins'] = $this->input->post('e_mins');
			
			//转换成string
			$new_period['period'] = period_arr_to_str($period);
			
			if(empty($new_period['period']) || empty($new_period['period_name']))
			{
				$notify = '请填写完整的时间段信息';
				$this->_load_period_add_view($notify, $new_period);
			}
			else
			{
				//add into DB
				if($this->period_model->add($new_period))
				{
					show_result_page('时间段已经添加成功! ', 'admin/period');
				}
				else
				{
					$notify = '添加失败, 请重试.';
					$this->_load_period_add_view($notify, $new_period);
				}
			}
		}
		else
		{
			$this->_load_period_add_view();
		}
	}
	
	function edit($period_id = 0)
	{
		//判断staff_id是否合法.
		$period_id = (empty($period_id))? $this->input->post('period_id') : intval($period_id);
		if($period_id <= 0)
		{
			show_error_page('您输入参数不合法, 请返回重试.', 'admin/period');
			return false;
		}
		
		//获取 period 信息.
		$period_info = $this->period_model->get_one($period_id);
		
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$edit_period['period_name'] = $this->input->post('period_name');
			
			//时间
			$period['period'] = $this->input->post('period');
			$period['s_hour'] = $this->input->post('s_hour');
			$period['s_mins'] = $this->input->post('s_mins');
			$period['e_hour'] = $this->input->post('e_hour');
			$period['e_mins'] = $this->input->post('e_mins');
			
			//转换成string
			$edit_period['period'] = period_arr_to_str($period);
			
			//检查修改项
			$update_field = array();
			foreach($edit_period as $key => $val)
			{
				if(!empty($val) && ($val != $period_info[$key]))
					$update_field[$key] = $val;
			}
			
			if($this->period_model->update($period_id, $update_field))
			{
				show_result_page('时间段已经更新成功! ', 'admin/period');
			}
			else
			{
				$notify = '更新失败, 请重试.';
				$this->_load_period_edit_view($notify, $edit_period);
			}
		}
		else
		{
			$this->_load_period_edit_view('', $period_info);
		}
	}
	
	function delete($period_id)
	{
		//判断staff_id是否合法.
		$period_id = (empty($period_id))? $this->input->post('period_id') : intval($period_id);
		if(empty($period_id))
		{
			show_error_page('您输入的参数不合法, 请返回重试.', 'admin/period');
			return false;
		}
		
		$period_info = $this->period_model->get_one($period_id);
		
		if($this->period_model->delete($period_id))
		{
			$notify = '时间段已成功删除！';
			show_result_page($notify, 'admin/period');
		}
		else
		{
			$notify = '删除失败, 请重试.';
			show_error_page($notify, 'admin/period');
		}
	}
	
	function _load_period_add_view($notify = '', $period = array())
	{
		$data['header']['meta_title'] = '添加时间段 - 时间段管理';
		$data['main']['notification'] = $notify;
		$data['main']['period'] = $period;
		_load_viewer('admin/period_add', $data);
	}
	
	function _load_period_edit_view($notify = '', $period_info = array())
	{
		$data['header']['meta_title'] = '编辑时间段 - 时间段管理';
		$data['main']['notification'] = $notify;
		$data['main']['period'] = $period_info;
		_load_viewer('admin/period_edit', $data);
	}
}

/* End of file period.php */
/* Location: ./application/controllers/admin/period.php */