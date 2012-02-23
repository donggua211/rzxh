<?php

class Video_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    //添加分组
	function insert_video($video, $room_id)
	{
		if(empty($video) || !is_array($video))
			return false;
		
		foreach($video as $val)
		{
			//必填项
			$data = array();
			$data['room_id'] = $room_id;
			$data['name'] = $val['VideoName'];
			$data['ip'] = $val['VideoIP'];
			$data['port'] = $val['VideoPort'];
			$data['channel'] = $val['VideoChannel'];
			$data['username'] = $val['UserName'];
			$data['password'] = $val['Password'];
			$data['server_type'] = $val['VideoServerType'];
			
			$data['add_time'] = date('Y-m-d H:i:s');
				
			$this->db->insert('video', $data);
		}

		return true;
	}
	
	function get_one_room($room_id)
	{
		$this->db->where('room_id', $room_id);
		$query = $this->db->get('video');
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
}