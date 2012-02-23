<?php

class User_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
	function get_all()
	{
		$this->db->where('type !=', USER_TYPE_CONFIG);
		$query = $this->db->get('user');
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $val)
				$result[$val['user_id']] = $val;
			return $result;
		}
		else
		{
			return array();
		}
	}
	
	function get_one($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->join('group', 'group.group_id = user.group_id', 'left');
		$query = $this->db->get('user');
		if ($query->num_rows() > 0)
		{
			$user = $query->row_array();
		}
		else
		{
			return array();
		}
		
		//获取 user 对应的 group
		$sql = "SELECT user_group.group_id FROM " . $this->db->dbprefix('user_group') . " as user_group
				WHERE user_group.user_id = ".$user_id;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $val)
				$user['groups'][] = $val['group_id'];
		}
		else
		{
			$user['groups'] = array();
		}

		return $user;
	}
	
	//添加用户
	function add($user)
	{
		/*
		 * 插入student表
		*/
		//必填项
		$data['username'] = $user['username'];
		$data['password'] = md5($user['password']);
		$data['email'] = $user['email'];
		$data['mobile'] = $user['mobile'];
		$data['group_id'] = $user['group_id'];
		$data['type'] = USER_TYPE_USER; //默认type为用户
		
		$data['add_time'] = date('Y-m-d H:i:s');
		$data['update_time'] = date('Y-m-d H:i:s');
			
		if($this->db->insert('user', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	/*
	  员工登陆. On success, return employ info, on failed, return empty array.
	*/
	function login($user)
	{
		$sql = "SELECT user_id, username, type FROM ".$this->db->dbprefix('user')." as user
				WHERE username='".$user['username']."'
				AND password='".md5($user['password'])."' 
				LIMIT 1";
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return array();
		}
	}
	
	function check_password($user_id, $password)
	{
		$sql = "SELECT user_id FROM ".$this->db->dbprefix('user')." 
				WHERE user_id='".$user_id."'
				AND password='".md5($password)."' 
				LIMIT 1";
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function username_has_exist($username)
	{
		$sql = "SELECT user_id FROM ".$this->db->dbprefix('user')." 
				WHERE username='".$username."'
				LIMIT 1";
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function delete($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('user'); 
		return ($this->db->affected_rows() > 0 ) ? TRUE : FALSE;
	}
	
	function update($user_id, $update_field = array())
	{
		if(empty($update_field))
			return true;
		
		foreach($update_field as $key => $val)
		{
				$data[$key] = $val;
		}
		$data['update_time'] = date('Y-m-d H:i:s');
		$this->db->where('user_id', $user_id);
		if($this->db->update('user', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function get_user_group($user_id = '', $group_id = '')
	{
		if(!empty($user_id))
			$this->db->where('user_id', $user_id);
		
		if(!empty($group_id))
			$this->db->where('group_id', $group_id);
		
		$query = $this->db->get('user_group'); 
		
		return $query->result_array();
	}
	
	function get_user_by_strategy($strategy_id)
	{
		$sql = "SELECT group_id FROM ".$this->db->dbprefix('strategy_group')." 
				WHERE strategy_id=".$strategy_id." ";
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $val)
				$group_arr[] = $val['group_id'];
			
			$sql = "SELECT user_id, email, mobile FROM ".$this->db->dbprefix('user')." 
				WHERE group_id IN (".implode(',', $group_arr).") ";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return array();
			}
		}
		else
		{
			return array();
		}
	}
}