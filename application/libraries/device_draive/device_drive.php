<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * device drive Class 
 *
 * @package device drive
 */
class Device_Drive extends CI_Driver_Library {

	protected $_adapter = '';
	protected $valid_drivers 	= array(
		'device_drive_php', 'device_drive_socket'
	);
	
	/**
	 * Constructor
	 *
	 * @param array
	 */
	public function __construct()
	{
		//加载配置, 初始化adapter
		$CI = & get_instance();
		$this->_adapter = $CI->config->config['site_setting']['device_drive']['adapter'];
	}
	
	/**
	 * __get()
	 *
	 * @param 	child
	 * @return 	object
	 */
	public function __get($child)
	{
		$obj = parent::__get($child);
		return $obj;
	}
	
	/**
	 * Get room names
	 */
	public function get_room_name()
	{
		return $this->{$this->_adapter}->get_room_name();
	}
	
	/**
	 * Get all devices in a room which specified by room_id.
	 */
	public function get_device_name($room_id)
	{
		//switch device
		$result['switch'] = $this->get_switch_device_name($room_id);
		
		//poweralone device
		$result['poweralone'] = $this->get_poweralone_device_name($room_id);
		
		//powerbind device
		$result['powerbind'] = $this->get_powerbind_device_name($room_id);
		
		//number device
		$result['number'] = $this->get_number_device_name($room_id);
		
		return $result;
	}
	
	/**
	 * Get switch devices in a room which specified by room_id.
	 */
	public function get_switch_device_name($room_id)
	{
		return $this->{$this->_adapter}->get_switch_device_name($room_id);
	}
	
	/**
	 * Get poweralone devices in a room which specified by room_id.
	 */
	public function get_poweralone_device_name($room_id)
	{
		return $this->{$this->_adapter}->get_poweralone_device_name($room_id);
	}
	
	/**
	 * Get powerbind devices in a room which specified by room_id.
	 */
	public function get_powerbind_device_name($room_id)
	{
		return $this->{$this->_adapter}->get_powerbind_device_name($room_id);
	}
	
	/**
	 * Get poweralone devices in a room which specified by room_id.
	 */
	public function get_number_device_name($room_id)
	{
		return $this->{$this->_adapter}->get_number_device_name($room_id);
	}
	
	/**
	 * Get all extendinterface name in a room which specified by room_id.
	 */
	public function get_extendinterface_name($room_id)
	{
		return $this->{$this->_adapter}->get_extendinterface_name($room_id);
	}
	
	
	
	
	
	
	
	
	/**
	 * Get switch devices status in a room which specified by room_id.
	 */
	public function get_switch_state($room_id)
	{
		return $this->{$this->_adapter}->get_switch_state($room_id);
	}
	
	/**
	 * Get poweralone devices status in a room which specified by room_id.
	 */
	public function get_poweralone_state($room_id)
	{
		return $this->{$this->_adapter}->get_poweralone_state($room_id);
	}
	
	/**
	 * Get powerbind devices status in a room which specified by room_id.
	 */
	public function get_powerbind_state($room_id)
	{
		return $this->{$this->_adapter}->get_powerbind_state($room_id);
	}
	
	/**
	 * Get poweralone devices status in a room which specified by room_id.
	 */
	public function get_number_state($room_id)
	{
		return $this->{$this->_adapter}->get_number_state($room_id);
	}
	
	/**
	 * Get poweralone devices status in a room which specified by room_id.
	 */
	public function get_video_list($room_id)
	{
		return $this->{$this->_adapter}->get_video_list($room_id);
	}
	
	// ------------------------------------------------------------------------
}
// End Class

/* End of file Device_Drive.php */
/* Location: ./application/libraries/Device_Drive/Device_Drive.php */