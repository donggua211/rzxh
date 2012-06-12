<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//device type
define('DEVICE_CAT_SWITCH', 1);
define('DEVICE_CAT_POWERALONE', 2);
define('DEVICE_CAT_POWERBIND', 3);
define('DEVICE_CAT_NUMBER', 4);

//user type
define('USER_TYPE_CONFIG', 1); //配置员
define('USER_TYPE_ADMIN', 2); //系统管理员
define('USER_TYPE_USER', 3); //用户

//history
define('HISTORY_PER_PAGE', 20);

//group
define('GROUP_ROLE_READABLE', 1);
define('GROUP_ROLE_CONFIGABLE', 5);

define('DEVICE_STATE_GET_NONE', -1);		//通过socket获取为-1
define('DEVICE_STATE_GET_FAILED', -100);	//通过socket获取失败
define('DEVICE_STATE_GET_EMPTY', -200);		//通过socket获取为空

define('DEVICE_STATE_GET_NONE_TEXT', '未知');		//通过socket获取为-1
define('DEVICE_STATE_GET_FAILED_TEXT', '等待');		//通过socket获取失败
define('DEVICE_STATE_GET_EMPTY_TEXT', '未知');		//通过socket获取为空


/* End of file constants.php */
/* Location: ./application/config/constants.php */