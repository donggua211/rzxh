<?php

class Device_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_devices_by_room($room_id)
    {
		$sql ="SELECT device.* FROM " . $this->db->dbprefix('device') ." as device
				WHERE device.room_id = $room_id ";
		
		$query = $this->db->query($sql);
		
		if($this->db->affected_rows()> 0)
        {
			foreach($query->result_array() as $val)
				$res[$val['device_id']] = $val;
			
			return $res;
		}
		else
		{
			return array();
        }
    }
	
    function get_devices_by_extendinterface($room_id, $extendinterface)
    {
		$sql ="SELECT DISTINCT device_extendinterface.device_id, device_extendinterface.*, device.* FROM " . $this->db->dbprefix('device_extendinterface') ." as device_extendinterface
				LEFT JOIN " . $this->db->dbprefix('device') ." as device ON device.device_id = device_extendinterface.device_id
				WHERE device_extendinterface.room_id = $room_id 
				AND device_extendinterface.extendinterface_id = $extendinterface ";
		$sql .= " ORDER BY `rank`, device_extendinterface.device_id";
		
		$query = $this->db->query($sql);
		
		if($this->db->affected_rows()> 0)
        {
			foreach($query->result_array() as $val)
				$res[$val['device_id']] = $val;
			
			return $res;
		}
		else
		{
			return array();
        }
    }
	
	function update_devices_rank($room_id, $extendinterface, $set, $where)
    {
		$sql ="UPDATE " . $this->db->dbprefix('device_extendinterface') ." as device_extendinterface
				SET ".$set."
				WHERE device_extendinterface.room_id = $room_id AND device_extendinterface.extendinterface_id = $extendinterface ";
		
		$sql .= " AND ".$where;
		
		$query = $this->db->query($sql);
	}
	
	function truncate_device()
	{
		$this->db->truncate('device');
		$this->db->truncate('device_extendinterface');
	}
	
	function insert_device($device, $room_id, $device_cat, $ei_arr)
	{
		if(empty($device) || !is_array($device))
			return false;
		
		foreach($device as $val)
		{
			//插入 device 表
			switch($device_cat)
			{
				case DEVICE_CAT_SWITCH:
					if(!isset($val['SwitchName']) || !isset($val['SwitchID']) || !isset($val['SwitchType']) || !isset($val['ExtendInterfaceID']))
						continue;
					
					$data['device_name'] = $val['SwitchName'];
					$data['device_num'] = $val['SwitchID'];
					$data['device_type'] = $val['SwitchType'];
					$data['device_cat'] = DEVICE_CAT_SWITCH;
					$data['error_range'] = 0.1;
					break;
				case DEVICE_CAT_POWERALONE:
					if(!isset($val['PowerAloneID']) || !isset($val['PowerAloneName']) || !isset($val['ExtendInterfaceID']))
						continue;
					
					$data['device_name'] = $val['PowerAloneName'];
					$data['device_num'] = $val['PowerAloneID'];
					$data['device_cat'] = DEVICE_CAT_POWERALONE;
					$data['device_type'] = 0;
					$data['error_range'] = 0.1;
					break;
				case DEVICE_CAT_POWERBIND:
					if(!isset($val['PowerBindName']) || !isset($val['PowerBindID']) || !isset($val['ExtendInterfaceID']))
						continue;
						
					$data['device_name'] = $val['PowerBindName'];
					$data['device_num'] = $val['PowerBindID'];
					$data['device_cat'] = DEVICE_CAT_POWERBIND;
					$data['device_type'] = 0;
					$data['error_range'] = 0.1;
					break;
				case DEVICE_CAT_NUMBER:
					if(!isset($val['NumberName']) || !isset($val['NumberID']) || !isset($val['NumberType']) || !isset($val['ExtendInterfaceID']))
						continue;
					
					$data['device_name'] = $val['NumberName'];
					$data['device_num'] = $val['NumberID'];
					$data['device_type'] = $val['NumberType'];
					$data['device_cat'] = DEVICE_CAT_NUMBER;
					$data['error_range'] = 0.1;
					break;
			}
			$sql = "INSERT INTO " . $this->db->dbprefix('device') ." 
					(`device_name`, `device_num`, `device_type`, `device_cat`, `error_range`, `room_id`, `add_time`, `update_time`)
					VALUES
					('{$data['device_name']}', '{$data['device_num']}', '{$data['device_type']}', '{$data['device_cat']}', '{$data['error_range']}', '{$room_id}', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."')";
			
			$this->db->query($sql);
			
			//如果插入 device 表失败
			if($this->db->affected_rows() <= 0)
				continue;
			
			//插入 device_extendinterface 表
			$device_id = $this->db->insert_id();
			$data['extendinterface_id'] = (isset($val['ExtendInterfaceID']) && isset($ei_arr[$val['ExtendInterfaceID']])) ? $ei_arr[$val['ExtendInterfaceID']] : 0;
			
			$device_e_i_arr = array(abs($data['extendinterface_id']));
			if($data['extendinterface_id'] < 0)
				$device_e_i_arr[] = 0;
			
			foreach($device_e_i_arr as $val)
				$device_e_i_sql_arr[] = "('{$device_id}', '{$room_id}', '{$val}', '{$device_id}', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."')";
			
			$sql = "INSERT INTO " . $this->db->dbprefix('device_extendinterface') ." 
					(`device_id`, `room_id`, `extendinterface_id`, `rank`, `add_time`, `update_time`)
					VALUES ".implode(', ', $device_e_i_sql_arr);
			
			$this->db->query($sql);
		}
		
		return ($this->db->affected_rows()> 0) ? true : false;
	}
}