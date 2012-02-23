<?php

class User extends CI_Controller {

	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Group_model');
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
		$users = $this->User_model->get_all();
		
		$data['header']['meta_title'] = '用户列表 - 用户管理';
		$data['main']['users'] = $users;
		_load_viewer('admin/user_all', $data);
	
	}
	
	function add()
	{
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$new_user['username'] = $this->input->post('username');
			$new_user['password'] = $this->input->post('password');
			$new_user['password_c'] = $this->input->post('password_c');
			$new_user['email'] = $this->input->post('email');
			$new_user['mobile'] = $this->input->post('mobile');
			
			//分组信息
			$new_user['group_id'] = $this->input->post('group_id');			
			
			if(empty($new_user['username']) || empty($new_user['password']) || empty($new_user['password_c']) || empty($new_user['mobile']) || empty($new_user['email']) || empty($new_user['group_id']))
			{
				$notify = '请填写完整的用户信息';
				$this->_load_user_add_view($notify, $new_user);
			}
			elseif(!$this->_check_username($new_user['username']))
			{
				$notify = '用户名只能由3-16位字母、数字、下划线(_)或者点(.)构成, 请重新输入.';
				$this->_load_user_add_view($notify, $new_user);
			}
			elseif($new_user['password'] != $new_user['password_c'])
			{
				$notify = '两次输入密码不一致, 请重新输入.';
				$this->_load_user_add_view($notify, $new_user);
			}
			elseif($this->User_model->username_has_exist($new_user['username'])) //检查重名.
			{
				$notify = '用户名已经存在, 请重新输入.';
				$this->_load_user_add_view($notify, $new_user);
			}
			else
			{
				//add into DB
				if($this->User_model->add($new_user))
				{
					show_result_page('员工已经添加成功! ', 'admin/user');
				}
				else
				{
					$notify = '员工添加失败, 请重试.';
					$this->_load_user_add_view($notify, $new_user);
				}
			}
		}
		else
		{
			$this->_load_user_add_view();
		}
	}
	
	function edit($user_id = 0)
	{
		//判断staff_id是否合法.
		$user_id = (empty($user_id))? $this->input->post('user_id') : intval($user_id);
		if($user_id <= 0)
		{
			show_error_page('您输入参数不合法, 请返回重试.', 'admin/user');
			return false;
		}
		
		//获取 user 信息.
		$user_info = $this->User_model->get_one($user_id);
		
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			//必填信息.
			$edit_user['username'] = $this->input->post('username');
			$edit_user['password'] = $this->input->post('password');
			$edit_user['password_c'] = $this->input->post('password_c');
			$edit_user['email'] = $this->input->post('email');
			$edit_user['mobile'] = $this->input->post('mobile');
			
			//分组信息
			$edit_user['group_id'] = $this->input->post('group_id');		
			
			if($edit_user['password'] != $edit_user['password_c'])
			{
				$notify = '两次输入密码不一致, 请重新输入.';
				$this->_load_user_edit_view($notify, $edit_user);
				return false;
			}
			
			//检查修改项
			$update_field = array();
			foreach($edit_user as $key => $val)
			{
				if($key == 'password_c')
					continue;
				
				if($key == 'password' && !empty($val))
					$val = md5($val);
				
				if(!empty($val) && ($val != $user_info[$key]))
					$update_field[$key] = $val;
			}
			
			if($this->User_model->update($user_id, $update_field))
			{
				show_result_page('用户已经更新成功! ', 'admin/user');
			}
			else
			{
				$notify = '更新失败, 请重试.';
				$this->_load_user_edit_view($notify, $edit_user);
			}
		}
		else
		{
			$this->_load_user_edit_view('', $user_info);
		}
	}
	
	function delete($user_id)
	{
		//判断staff_id是否合法.
		$user_id = (empty($user_id))? $this->input->post('user_id') : intval($user_id);
		if(empty($user_id))
		{
			show_error_page('您输入的参数不合法, 请返回重试.', 'admin/user');
			return false;
		}
		
		$user_info = $this->User_model->get_one($user_id);
		
		if($this->User_model->delete($user_id))
		{
			$notify = '员工已成功删除！';
			show_result_page($notify, 'admin/user');
		}
		else
		{
			$notify = '删除失败, 请重试.';
			show_error_page($notify, 'admin/user');
		}
	}
	
	function _check_username($username)
	{
		return preg_match('/^[0-9a-zA-Z][a-zA-Z0-9_.]{1,14}[a-zA-Z0-9]$/i', $username);
	}
	
	function _get_groups()
	{
		return $this->Group_model->get_all();
	}
	
	function _load_user_add_view($notify = '', $user = array())
	{
		$data['header']['meta_title'] = '添加用户 - 用户管理';
		$data['main']['groups'] = $this->_get_groups();
		$data['main']['notification'] = $notify;
		$data['main']['user'] = $user;
		_load_viewer('admin/user_add', $data);
	}
	
	function _load_user_edit_view($notify = '', $user_info = array())
	{
		$data['header']['meta_title'] = '编辑用户 - 用户管理';
		$data['main']['groups'] = $this->_get_groups();
		$data['main']['notification'] = $notify;
		$data['main']['user'] = $user_info;
		_load_viewer('admin/user_edit', $data);
	}
}

/* End of file user.php */
/* Location: ./application/controllers/admin/user.php */