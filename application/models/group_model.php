<?php

class Group_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    //添加分组
	function add($group)
	{
		/*
		 * 插入student表
		*/
		//必填项
		$data['group_name'] = $group['group_name'];
		$data['group_role'] = $group['group_role'];
		
		$data['add_time'] = date('Y-m-d H:i:s');
		$data['update_time'] = date('Y-m-d H:i:s');
			
		if($this->db->insert('group', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	
	function get_all()
	{
		$query = $this->db->get('group');
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
	
	function get_one($group_id)
	{
		$this->db->where('group_id', $group_id);
		$query = $this->db->get('group');
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return array();
		}
	}
	
	function delete($group_id)
	{
		$this->db->where('group_id', $group_id);
		$this->db->delete('group'); 
		return ($this->db->affected_rows() > 0 ) ? TRUE : FALSE;
	}
	
	function update($group_id, $update_field = array())
	{
		if(empty($update_field))
			return true;
		
		foreach($update_field as $key => $val)
		{
				$data[$key] = $val;
		}
		
		$this->db->where('group_id', $group_id);
		if($this->db->update('group', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}