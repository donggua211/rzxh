<?php

class User extends CI_Controller {

	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('session');
	}
	
	/* 用户登录 */
	function login()
	{
		// 如果已经登录, 就跳转到admin首页
		if (has_login())
		{
			redirect("");
		}
		
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			$username = $this->input->post("username");
			$password = $this->input->post("password");
			
			if($username == FAlSE || $password == FAlSE)
			{
				$notification = '用户名或者密码不能为空';
				$this->_load_login_view($notification);
			}
			else
			{
				$user_info = $this->user_model->login(array('username'=>$username, 'password'=>$password));
				
				/* 
					登录成功, 设置session: staff_id, group_id. 然后跳转至admin首页
				*/
				if (!empty($user_info))
				{
					$session_data = array('user_id' => $user_info['user_id'], 'username' => $user_info['username'], 'type' => $user_info['type']);
					$this->session->set_userdata($session_data);
					redirect('');
				}
				else
				{
					$notification = '用户名或者密码错误';
					$this->_load_login_view($notification);
				}
			}
		}
		else
		{
			$this->_load_login_view();
		}
	}
	
	//登出
	function logout()
	{
		$this->session->sess_destroy();
		redirect('user/login');
	}
	
	//修改密码
	function change_psd()
	{
		if(isset($_POST['submit']) && !empty($_POST['submit']))
		{
			$this->user_info = get_user_info();
			
			$old_password = $this->input->post("old_password");
			$new_password = $this->input->post("new_password");
			$new_password_c = $this->input->post("new_password_c");
			
			if(empty($old_password) || empty($new_password) || empty($new_password_c))
			{
				$this->_load_change_pwd_view('请填写完整密码信息!');
				return false;
			}
			elseif($new_password != $new_password_c)
			{
				$this->_load_change_pwd_view('您两次密码输入不一致, 请重新输入!');
				return false;
			}
			else if (strpos($new_password, ' ') !== FALSE) 
			{
				$this->_load_change_pwd_view('您的新密码不能包含空格, 请重新输入!');
				return false;
			
			}
			else if (strlen($new_password) < 4) 
			{
				$this->_load_change_pwd_view('您的新密码至少要4位以上, 请重新输入!');
				return false;			
			}
			elseif(!$this->_check_password($this->user_info['user_id'], $old_password))
			{
				$this->_load_change_pwd_view('您的密码有误, 请重新输入!');
				return false;
			}
			else
			{
				$update_field['password'] = md5($new_password);
				if($this->user_model->update($this->user_info['user_id'], $update_field))
				{
					show_result_page('您的密码已经更新成功! ', '');
				}
				else
				{
					$this->_load_change_pwd_view('您的密码已经更新失败, 请重试');
				}
			}
		}
		else
		{
			$this->_load_change_pwd_view();
		}
	}
	
	function _load_change_pwd_view($notify = '')
	{
		$data['header']['meta_title'] = '修改密码';
		$data['main']['notification'] = $notify;
		_load_viewer('user_edit_password', $data);
	}
	
	function _load_login_view($notify = '')
	{
		$data['header']['meta_title'] = '用户登录';
		$data['main']['notification'] = $notify;
		_load_viewer('user_login', $data);
	}
	
	
	function _check_password($user_id, $password)
	{
		$login_info = $this->user_model->check_password($user_id, $password);
		return (empty($login_info)) ? FALSE : TRUE;
		
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */