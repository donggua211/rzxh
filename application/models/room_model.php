<?php

class Room_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_rooms()
    {
        $query = $this->db->get('room');
		if($this->db->affected_rows()> 0)
        {
			foreach($query->result_array() as $val)
				$result[$val['room_id']] = $val;
			return $result;
		}
		else
			return array();
    }
	
    function get_one_room($room_id)
    {
		$where =  array('room_id' => $room_id);
        $query = $this->db->get_where('room', $where);
        return $query->row_array();
    }
	
    function get_extendinterfaces($room_id)
    {
		$where =  array('room_id' => $room_id);
        $query = $this->db->get_where('extendinterface', $where);
		
		if($this->db->affected_rows()> 0)
        {
			foreach($query->result_array() as $val)
				$result[$val['extendinterface_id']] = $val;
			return $result;
		}
		else
			return array();
    }
	
	function insert_rooms($rooms, $empty_first = false)
	{
		if(empty($rooms) || !is_array($rooms))
			return false;
		
		if($empty_first)
			$this->db->truncate('room');
		
		$room_sql = array();
		foreach($rooms as $val)
			$room_sql[] = "('{$val['RoomID']}', '{$val['RoomName']}', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."') ";
		
		//insert room data
		$sql = "INSERT INTO " . $this->db->dbprefix('room') ." (`room_num`, `room_name`, `add_time`, `update_time`) VALUES ".implode(',', $room_sql);
		$this->db->query($sql);
		
		return ($this->db->affected_rows()> 0) ? true : false;
	}
	
	function truncate_extendinterfaces()
	{
		$this->db->truncate('extendinterface');
	}
	
	function truncate_room()
	{
		$this->db->truncate('room');
	}
	
	function insert_extendinterface($extendinterfaces, $room_id)
	{
		if(empty($extendinterfaces) || !is_array($extendinterfaces))
			return false;
		
		$extendinterface_sql = array();
		foreach($extendinterfaces as $val)
			$extendinterface_sql[] = "('{$room_id}', '{$val['ExtendInterfaceID']}', '{$val['ExtendInterfaceName']}', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."') ";
		
		//insert room data
		$sql = "INSERT INTO " . $this->db->dbprefix('extendinterface') ." (`room_id`, `extendinterface_num`, `extendinterface_name`, `add_time`, `update_time`) VALUES ".implode(',', $extendinterface_sql);
		$this->db->query($sql);
		
		return ($this->db->affected_rows()> 0) ? true : false;
	}
}