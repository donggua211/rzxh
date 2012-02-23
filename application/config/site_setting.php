<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| USER AGENT TYPES
| -------------------------------------------------------------------
| This file contains four arrays of user agent data.  It is used by the
| User Agent Class to help identify browser, platform, robot, and
| mobile device data.  The array keys are used to identify the device
| and the array values are used to set the actual name of the item.
|
*/

// There are hundreds of bots but these are the most common.
$config['site_setting']['basic']['site_name'] = '清华大学后台系统';

$config['site_setting']['basic']['refresh_interval'] = 5 * 1000;//刷新时间： 微秒

$config['site_setting']['basic']['warning_interval_time'] = 60 * 60 *24;//报警信息不再提示的时间， 单位:秒


$config['site_setting']['device_drive']['adapter'] = 'socket';

$config['site_setting']['device_drive']['socket']['ip'] = '166.111.111.213';
$config['site_setting']['device_drive']['socket']['port'] = '8888';

/* End of file user_agents.php */
/* Location: ./application/config/user_agents.php */