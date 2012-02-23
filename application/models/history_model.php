<?php

class History_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
    //�����ʷ
	function add_history($history)
	{
		if(is_array($history['value']))
			return true;
		//������
		$data['device_id'] = $history['device_id'];
		$data['room_id'] = $history['room_id'];
		$data['value'] = $history['value'];
		$data['start_time'] = date('Y-m-d H:i:s');
		
		$data['add_time'] = date('Y-m-d H:i:s');
		
		if($this->db->insert('history', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	
	//��ӱ�����ʷ
	function add_warning_history($history)
	{
		if(is_array($history['value']))
			return true;
		
		//������
		$data['device_id'] = $history['device_id'];
		$data['room_id'] = $history['room_id'];
		$data['warning_level'] = $history['warning_level'];
		$data['value'] = $history['value'];
		$data['start_time'] = date('Y-m-d H:i:s');
		$data['add_time'] = date('Y-m-d H:i:s');
		
		if($this->db->insert('history_warning', $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	
	function get_last_history_be_device($device_id)
	{
		$sql ="SELECT * FROM " . $this->db->dbprefix('history') ." as history
				WHERE device_id = $device_id
				ORDER BY start_time DESC 
				LIMIT 1";
		
		$query = $this->db->query($sql);
		
		if($this->db->affected_rows()> 0)
        {
			return $query->row_array();
		}
		else
		{
			return array();
        }
	
	}
	
	
	function getAll($type = '', $filter, $offset = 0, $row_count = 0)
	{
		if($type == 'warning')
			$table = 'history_warning';
		else
			$table = 'history';
		
		$where = '';
		
		//��ӵ�ʱ���: ��ʼʱ��
        if (isset($filter['add_time_a']) && $filter['add_time_a'])
        {
            $where .= " AND history.add_time >= '{$filter['add_time_a']}' ";
        }
		//��ӵ�ʱ���: ����ʱ��
		if (isset($filter['add_time_b']) && $filter['add_time_b'])
        {
            $where .= " AND history.add_time <= '{$filter['add_time_b']}' ";
        }
		if (isset($filter['room_id']) && $filter['room_id'])
        {
            $where .= " AND history.room_id = {$filter['room_id']} ";
        }
		//ѧ��
		if (isset($filter['device_id']) && $filter['device_id'])
        {
            $where .= " AND history.device_id = {$filter['device_id']} ";
        }
		
		//student������Ϣ
		$sql = "SELECT history.*, room.room_name, device.device_name FROM ".$this->db->dbprefix($table)." as history 
				LEFT JOIN ".$this->db->dbprefix('room')." as room ON room.room_id = history.room_id 
				LEFT JOIN ".$this->db->dbprefix('device')." as device ON device.device_id = history.device_id ";
		
		if(!empty($where))
			$sql .= substr_replace($where, ' WHERE ', 0, strpos($where, 'AND') + 3);
		
		//order by
		$sql .= " ORDER BY history.room_id, history.device_id, history.add_time DESC ";
		
		//LIMIT
		if (!empty($row_count))
        {
            $sql .= " LIMIT $offset, $row_count";
        }
		
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
	
	function getAll_count($type = '', $filter)
	{
		if($type == 'warning')
			$table = 'history_warning';
		else
			$table = 'history';
		
		$where = '';
		
		//��ӵ�ʱ���: ��ʼʱ��
        if (isset($filter['add_time_a']) && $filter['add_time_a'])
        {
            $where .= " AND add_time >= '{$filter['add_time_a']}' ";
        }
		//��ӵ�ʱ���: ����ʱ��
		if (isset($filter['add_time_b']) && $filter['add_time_b'])
        {
            $where .= " AND add_time <= '{$filter['add_time_b']}' ";
        }
		if (isset($filter['room_id']) && $filter['room_id'])
        {
            $where .= " AND room_id = {$filter['room_id']} ";
        }
		//ѧ��
		if (isset($filter['device_id']) && $filter['device_id'])
        {
            $where .= " AND device_id = {$filter['device_id']} ";
        }
		
		//student������Ϣ
		$sql = "SELECT COUNT(*) AS total FROM ".$this->db->dbprefix($table);
		
		if(!empty($where))
			$sql .= substr_replace($where, ' WHERE ', 0, strpos($where, 'AND') + 3);
		
		$query = $this->db->query($sql);
		$row = $query->row_array();
		return $row['total'];
	}
}