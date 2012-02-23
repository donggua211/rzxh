<?php
/* 
  后台入口.
  公共权限
 */
class api extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		
		$this->load->library('Services_JSON');
		//如果没有经登录, 就跳转到admin/login登陆页
		if (!has_login())
		{
			goto_login();
		}
		
		$this->user_info = get_user_info();
	}
	
	function update_user_group()
	{
		$group_id = $this->input->post('group_id');
		$user_id = $this->input->post('user_id');
		$action = $this->input->Post('action');
		
		if($action == 'del')
			$result = $this->User_model->update_user_group($user_id, array($group_id));
		elseif($action == 'add')
			$result = $this->User_model->update_user_group($user_id, array(), array($group_id));
		else
			$result = false;
		
		if($result)
			echo 'OK';
		else
			echo 'NG';
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */