<?php

class Strategy_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
	function get_one($strategy_id)
	{
		$this->db->where('strategy_id', $strategy_id);
		$query = $this->db->get('strategy');
		if ($query->num_rows() > 0)
		{
			$strategy = $query->row_array();
			
			$this->db->where('strategy_id', $strategy_id);
			$query = $this->db->get('strategy_group');
			if ($query->num_rows() > 0)
			{
				foreach($query->result_array() as $val)
					$strategy['groups'][] = $val['group_id'];
			}
			else
			{
				$strategy['groups'] = array();
			}
			
			return $strategy;
		}
		else
		{
			return array();
		}
	}
	
    function get_strategy_by_device($device_id)
    {
        $where =  array('device_id' => $device_id);
		$this->db->join('period', 'period.period_id = strategy.period_id', 'left');
        $query = $this->db->get_where('strategy', $where);
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $val)
				$result[$val['strategy_id']] = $val;
			
			return $result;
		}
		else
		{
			return array();
		}
    }
	
    function get_strategy_by_device_array($device_array)
    {
		if(empty($device_array))
			return array();
		
		$this->db->where_in('device_id', $device_array);
		$this->db->join('period', 'period.period_id = strategy.period_id', 'left');
        $query = $this->db->get('strategy');
		
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $val)
				$result[$val['strategy_id']] = $val;
			
			//strategy group
			$this->db->flush_cache();
			$this->db->where_in('strategy_id', array_keys($result));
			$query = $this->db->get('strategy_group');
			if ($query->num_rows() > 0)
			{
				foreach($query->result_array() as $val2)
					$result[$val2['strategy_id']]['group'][] = $val2['group_id'];
			}
			
			foreach($result as $val3)
				$strategy[$val3['device_id']][$val3['strategy_id']] = $val3;
			
			return $strategy;
		}
		else
		{
			return array();
		}
    }
	
	function get_strategy_group_by_strategy($strategy_ids)
	{
		if(empty($strategy_ids))
			return array();
		
		$this->db->where_in('strategy_id', $strategy_ids);
		$this->db->join('group', 'group.group_id = strategy_group.group_id', 'left');
        $query = $this->db->get_where('strategy_group');
		
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $val)
				$result[$val['strategy_id']][] = $val;
			
			return $result;
		}
		else
		{
			return array();
		}
	}
	
	function insert_strategy($strategy)
	{
		$data['device_id'] = $strategy['device_id'];
		$data['strategy_name'] = $strategy['strategy_name'];
		$data['value'] = $strategy['value'];
		$data['condition'] = $strategy['condition'];
		$data['warning_level'] = $strategy['level'];
		$data['warning_content'] = $strategy['content'];
		$data['period_id'] = $strategy['period_id'];
		$data['sound_alert'] = $strategy['sound_alert'];
		$data['add_time'] = date('Y-m-d h:i:s');
		$data['update_time'] = date('Y-m-d h:i:s');
		
		if($this->db->insert('strategy', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	
	function update($strategy_id, $update_field = array())
	{
		if(empty($update_field))
			return true;
		
		foreach($update_field as $key => $val)
		{
				$data[$key] = $val;
		}
		$data['update_time'] = date('Y-m-d H:i:s');
		$this->db->where('strategy_id', $strategy_id);
		if($this->db->update('strategy', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function update_strategy_group($strategy_id, $del_group_id = array(), $add_group_id = array())
	{
		if(!empty($del_group_id))
		{
			$sql = "DELETE FROM " . $this->db->dbprefix('strategy_group') ." 
					WHERE strategy_id= $strategy_id 
					AND group_id IN ( ".implode(',', $del_group_id)." ) ";
			$this->db->query($sql);
		}
		
		if(!empty($add_group_id))
		{
			$groups_sql = array();
			foreach($add_group_id as $val)
				$groups_sql[] = "('$strategy_id', '{$val}', '".date('Y-m-d h:i:s')."') ";
		
		
			$sql = "INSERT INTO " . $this->db->dbprefix('strategy_group') ." (`strategy_id`, `group_id`, `add_time`) VALUES ".implode(',', $groups_sql);
			$this->db->query($sql);
		}
		
		return true;
	}
	
	function delete($strategy_id)
	{
		$this->db->where('strategy_id', $strategy_id);
		$this->db->delete('strategy'); 
		return ($this->db->affected_rows() > 0 ) ? TRUE : FALSE;
	}
	
	function delete_strategy_group($strategy_id = '', $group_id = '')
	{
		if(!empty($strategy_id))
			$this->db->where('strategy_id', $strategy_id);
		
		if(!empty($group_id))
			$this->db->where('group_id', $group_id);
		
		$this->db->delete('strategy_group'); 
		return ($this->db->affected_rows() > 0 ) ? TRUE : FALSE;
	}
}