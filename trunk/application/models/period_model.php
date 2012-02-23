<?php

class Period_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    //添加分组
	function add($period)
	{
		//必填项
		$data['period'] = $period['period'];
		$data['period_name'] = $period['period_name'];
		
		$data['add_time'] = date('Y-m-d H:i:s');
		$data['update_time'] = date('Y-m-d H:i:s');
			
		if($this->db->insert('period', $data))
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
		$query = $this->db->get('period');
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
	
	function get_one($period_id)
	{
		$this->db->where('period_id', $period_id);
		$query = $this->db->get('period');
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return array();
		}
	}
	
	function delete($period_id)
	{
		$this->db->where('period_id', $period_id);
		$this->db->delete('period'); 
		return ($this->db->affected_rows() > 0 ) ? TRUE : FALSE;
	}
	
	function update($period_id, $update_field = array())
	{
		if(empty($update_field))
			return true;
		
		foreach($update_field as $key => $val)
		{
				$data[$key] = $val;
		}
		
		$this->db->where('period_id', $period_id);
		if($this->db->update('period', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}