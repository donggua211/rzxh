<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * device drive socket Class
 *
 * @package device drive
 */

class Device_Drive_socket extends CI_Driver {
	private $_socket_setting = array();
	private $_socket_obj = NULL;
	
	/**
	 * Constructor
	 *
	 * @param array
	 */
	public function __construct()
	{
		//加载配置
		$CI = & get_instance();
		$this->_socket_setting = $CI->config->config['site_setting']['device_drive']['socket'];
		
		//初始化 socket class
		require_once(APPPATH.'libraries/socket_class.php');
		$this->_socket_obj = new client_socket();
		$this->_socket_obj->open($this->_socket_setting['ip'], $this->_socket_setting['port']);//打开连接
		$this->_socket_obj->send("userongzhixinghuapowermanagerpublicdatainterface");//握手
	}
	
	function __destruct() {
		$this->_socket_obj->close();
	}
	
	/**
	 * test only
	 *
	 * @return TRUE;
	 */
	public function get_room_name()
	{
		$this->_socket_obj->send("GetRoomName");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['RoomNameList']))
			return array();
		
		return (isset($result['DocumentElement']['RoomNameList'][0])) ? $result['DocumentElement']['RoomNameList'] : array($result['DocumentElement']['RoomNameList']);
	}
	
	public function get_switch_device_name($room_id)
	{
		$this->_socket_obj->send("GetSwitchName $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['SwitchNameList']))
			return array();
		
		return (isset($result['DocumentElement']['SwitchNameList'][0])) ? $result['DocumentElement']['SwitchNameList'] : array($result['DocumentElement']['SwitchNameList']);
	}
	
	/**
	 * Get poweralone devices in a room which specified by room_id.
	 */
	public function get_poweralone_device_name($room_id)
	{
		$this->_socket_obj->send("GetPowerAloneName $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['PowerAloneNameList']))
			return array();
		
		return (isset($result['DocumentElement']['PowerAloneNameList'][0])) ? $result['DocumentElement']['PowerAloneNameList'] : array($result['DocumentElement']['PowerAloneNameList']);
	}
	
	/**
	 * Get powerbind devices in a room which specified by room_id.
	 */
	public function get_powerbind_device_name($room_id)
	{
		$this->_socket_obj->send("GetPowerBindName $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['PowerBindNameList']))
			return array();
		
		return (isset($result['DocumentElement']['PowerBindNameList'][0])) ? $result['DocumentElement']['PowerBindNameList'] : array($result['DocumentElement']['PowerBindNameList']);
	}
	
	/**
	 * Get poweralone devices in a room which specified by room_id.
	 */
	public function get_number_device_name($room_id)
	{
		$this->_socket_obj->send("GetNumberName $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['NumberNameList']))
			return array();
		
		return (isset($result['DocumentElement']['NumberNameList'][0])) ? $result['DocumentElement']['NumberNameList'] : array($result['DocumentElement']['NumberNameList']);
	}
	
	/**
	 * 获取机房内所有扩展界面的值
	 *
	 * @return TRUE;
	 */
	public function get_extendinterface_name($room_id)
	{
		$result = $this->_socket_obj->send("GetExtendInterfaceName $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['ExtendInterfaceList']))
			return array();
		
		return (isset($result['DocumentElement']['ExtendInterfaceList'][0])) ? $result['DocumentElement']['ExtendInterfaceList'] : array($result['DocumentElement']['ExtendInterfaceList']);
	}
	
	
	
	
	
	
	
	
	
	public function get_switch_state($room_id)
	{
		$this->_socket_obj->send("GetSwitchState $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['SwitchStateList']))
			return array();
		
		return (isset($result['DocumentElement']['SwitchStateList'][0])) ? $result['DocumentElement']['SwitchStateList'] : array($result['DocumentElement']['SwitchStateList']);
	}
	
	/**
	 * Get poweralone devices in a room which specified by room_id.
	 */
	public function get_poweralone_state($room_id)
	{
		$this->_socket_obj->send("GetPowerAloneState $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['PowerAloneStateList']))
			return array();
		
		return (isset($result['DocumentElement']['PowerAloneStateList'][0])) ? $result['DocumentElement']['PowerAloneStateList'] : array($result['DocumentElement']['PowerAloneStateList']);
	}
	
	/**
	 * Get powerbind devices in a room which specified by room_id.
	 */
	public function get_powerbind_state($room_id)
	{
		$this->_socket_obj->send("GetPowerBindState $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['PowerBindStateList']))
			return array();
		
		return (isset($result['DocumentElement']['PowerBindStateList'][0])) ? $result['DocumentElement']['PowerBindStateList'] : array($result['DocumentElement']['PowerBindStateList']);
	}
	
	/**
	 * Get poweralone devices in a room which specified by room_id.
	 */
	public function get_number_state($room_id)
	{
		$this->_socket_obj->send("GetNumberState $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['NumberStateList']))
			return array();
		
		return (isset($result['DocumentElement']['NumberStateList'][0])) ? $result['DocumentElement']['NumberStateList'] : array($result['DocumentElement']['NumberStateList']);
	}

	/**
	 * Get poweralone devices in a room which specified by room_id.
	 */
	public function get_video_list($room_id)
	{
		$this->_socket_obj->send("GetVideoList $room_id");
		$result = $this->_socket_obj->recv();
		
		if(empty($result) || !isset($result['DocumentElement']['VideoList']))
			return array();
		
		return (isset($result['DocumentElement']['VideoList'][0])) ? $result['DocumentElement']['VideoList'] : array($result['DocumentElement']['VideoList']);
	}

	// ------------------------------------------------------------------------

}
// End Class

/* End of file Cache_dummy.php */
/* Location: ./system/libraries/Cache/drivers/Cache_dummy.php */