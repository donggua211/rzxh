<?php

class Group extends CI_Controller {

	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->model('Group_model');
		$this->load->model('User_model');
		$this->load->model('room_model');
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
		$groups = $this->Group_model->get_all();
		
		$data['header']['meta_title'] = '用户列表 - 用户管理';
		$data['main']['groups'] = $groups;
		_load_viewer('admin/group_all', $data);
	
	}
	
	function add()
	{

		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$new_group['group_name'] = $this->input->post('group_name');
			$room = $this->input->post('room');
			$role = $this->input->post('role');
			
			$role_arr = array();
			foreach($room as $one)
			{
				if(isset($role[$one]))
					$role_arr[] = $one.':'.$role[$one];
			}
			
			if(empty($new_group['group_name']) || empty($role_arr))
			{
				$notify = '请填写完整的分组信息';
				$this->_load_group_add_view($notify, $new_group);
			}
			else
			{
				//组装权限数组
				$new_group['group_role'] = implode(',', $role_arr);
				//add into DB
				if($this->Group_model->add($new_group))
				{
					show_result_page('分组已经添加成功! ', 'admin/group');
				}
				else
				{
					$notify = '分组添加失败, 请重试.';
					$this->_load_group_add_view($notify, $new_group);
				}
			}
		}
		else
		{
			$this->_load_group_add_view();
		}
	}
	
	function edit($group_id = 0)
	{
		//判断staff_id是否合法.
		$group_id = (empty($group_id))? $this->input->post('group_id') : intval($group_id);
		if($group_id <= 0)
		{
			show_error_page('您输入参数不合法, 请返回重试.', 'admin/group');
			return false;
		}
		
		//获取 group 信息.
		$group_info = $this->Group_model->get_one($group_id);
		
		$group_role_arr = explode(',', $group_info['group_role']);
		foreach($group_role_arr as $val)
		{
			list($room_id, $role) = explode(':', $val);
			$group_info['role'][$room_id] = $role;
		}
		
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$edit_group['group_name'] = $this->input->post('group_name');
			$room = $this->input->post('room');
			$role = $this->input->post('role');
			
			$role_arr = array();
			foreach($room as $one)
			{
				if(isset($role[$one]))
					$role_arr[] = $one.':'.$role[$one];
			}
			
			//组装权限数组
			$edit_group['group_role'] = (empty($role_arr)) ? array() : implode(',', $role_arr);
			
			//检查修改项
			$update_field = array();
			foreach($edit_group as $key => $val)
			{
				if(!empty($val) && ($val != $group_info[$key]))
					$update_field[$key] = $val;
			}
			
			if($this->Group_model->update($group_id, $update_field))
			{
				show_result_page('用户已经更新成功! ', 'admin/group');
			}
			else
			{
				$notify = '更新失败, 请重试.';
				$this->_load_group_edit_view($notify, $edit_group);
			}
		}
		else
		{
			$this->_load_group_edit_view('', $group_info);
		}
	}
	
	function delete($group_id)
	{
		//判断staff_id是否合法.
		$group_id = (empty($group_id))? $this->input->post('group_id') : intval($group_id);
		if(empty($group_id))
		{
			show_error_page('您输入的参数不合法, 请返回重试.', 'admin/group');
			return false;
		}
		
		$group_info = $this->Group_model->get_one($group_id);
		
		if($this->Group_model->delete($group_id))
		{
			//删除user group
			$this->User_model->delete_user_group('', $group_id);
			
			$notify = '分组已成功删除！';
			show_result_page($notify, 'admin/group');
		}
		else
		{
			$notify = '删除失败, 请重试.';
			show_error_page($notify, 'admin/group');
		}
	}
	
	function _load_group_add_view($notify = '', $group = array())
	{
		$data['header']['meta_title'] = '添加分组 - 用户管理';
		$data['main']['notification'] = $notify;
		$data['main']['group'] = $group;
		$data['main']['rooms'] = $this->room_model->get_rooms();
		_load_viewer('admin/group_add', $data);
	}
	
	function _load_group_edit_view($notify = '', $group = array())
	{
		$data['header']['meta_title'] = '编辑分组 - 用户管理';
		$data['main']['notification'] = $notify;
		$data['main']['users'] = $this->User_model->get_all();
		$data['main']['user_group'] = $this->User_model->get_user_group('', $group['group_id']);
		$data['main']['group'] = $group;
		$data['main']['rooms'] = $this->room_model->get_rooms();
		_load_viewer('admin/group_edit', $data);
	}
}

/* End of file group.php */
/* Location: ./application/controllers/admin/group.php */