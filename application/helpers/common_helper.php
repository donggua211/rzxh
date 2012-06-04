<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/* 
		通用方法.
	 */
	
	/** 
	 * 判断用户是否已经登录
	 * @return bool true代表已登录；false代表未登录 
	 */
	function has_login() 
	{
		$CI =& get_instance();
		return ($CI->session->userdata('user_id'))? true : false;
	}
	
	/** 
	 * 判断用户是否是管理员
	 * @return bool true代表已登录；false代表未登录 
	 */
	function is_admin()
	{
		$user_info = get_user_info();
		return ($user_info['type'] == USER_TYPE_ADMIN) ? TRUE : FALSE;
	}
	
	function show_access_deny_page()
	{
		show_error_page('Access Deny for this User.');
	}
	
	function get_user_type_text($key = 1)
	{
		$key = intval($key);
		$text = array(
			USER_TYPE_ADMIN => '管理员',
			USER_TYPE_USER => '用户',
		);
		
		if(!array_key_exists($key, $text))
			return '';
		else
			return $text[$key];
	}
	
	/** 
	 * @param string $string 原文或者密文 
	 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE 
	 * @param string $key 密钥 
	 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效 
	 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文 
	 * 
	 * @example 
	 * 
	 *  $a = authcode('abc', 'ENCODE', 'key');
	 *  $b = authcode($a, 'DECODE', 'key');  // $b(abc)
	 * 
	 *  $a = authcode('abc', 'ENCODE', 'key', 3600);
	 *  $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空 
	 */

	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600)
	{
		$default_key = 'ndedu';
		$ckey_length = 4;
		// 随机密钥长度 取值 0-32; 
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。 
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方 
		// 当此值为 0 时，则不产生随机密钥 
		$key = md5($key ? $key : $default_key); 
		$keya = md5(substr($key, 0, 16)); 
		$keyb = md5(substr($key, 16, 16)); 
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : ''; 
		$cryptkey = $keya.md5($keya.$keyc); 
		$key_length = strlen($cryptkey);
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string; 
		$string_length = strlen($string); 
		$result = ''; 
		$box = range(0, 255); 
		$rndkey = array(); 

		for($i = 0; $i <= 255; $i++) { 
			$rndkey[$i] = ord($cryptkey[$i % $key_length]); 
		} 

		for($j = $i = 0; $i < 256; $i++) { 
			$j = ($j + $box[$i] + $rndkey[$i]) % 256; 
			$tmp = $box[$i]; 
			$box[$i] = $box[$j]; 
			$box[$j] = $tmp; 
		} 

		for($a = $j = $i = 0; $i < $string_length; $i++)
		{ 
			$a = ($a + 1) % 256; 
			$j = ($j + $box[$a]) % 256; 
			$tmp = $box[$a]; 
			$box[$a] = $box[$j]; 
			$box[$j] = $tmp; 
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256])); 
		} 

		if($operation == 'DECODE')
		{
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) { 
			return substr($result, 26); 
			} else { 
			return ''; 
			} 
		} else { 
			return $keyc.str_replace('=', '', base64_encode($result)); 
		}
	}
	
	function set_auth_cookie($key, $value, $expire = 0)
	{
		$authcookie_expire = 3600 * 12; //默认2个小时
		$pycrypto_value = authcode($value, 'ENCODE', '', $authcookie_expire);
		setcookie($key, $pycrypto_value, $expire);
	}
	
	function get_auth_cookie($key)
	{
		return (isset($_COOKIE[$key])) ? authcode($_COOKIE[$key], 'DECODE') : '';
	}
	
	function is_kaiguan($device_cat = 0, $device_type = 0)
	{
		if(empty($device_cat) || empty($device_type))
			return false;
		
		if($device_cat == DEVICE_CAT_SWITCH)
			return true;
		
		return false;
	}
	
	//获取用户的登录信息。
	function get_user_info()
	{
		$CI =& get_instance();
		$user_info = array();
		$user_info['user_id'] = $CI->session->userdata('user_id');
		$user_info['type'] = $CI->session->userdata('type');
		$user_info['username'] = $CI->session->userdata('username');
		
		return $user_info;
	}
	
	/* 
		跳转到登陆页
	*/
	function goto_login()
	{
		redirect("user/login");
		exit();
	}
	
	function _load_viewer($template, $data = array())
	{
		$CI =& get_instance();
		//加载header
		if( !isset($data['header']) )
			$data['header'] = array();
		$CI->load->view('header', $data['header']);
		
		//加载主页面
		if( !isset($data['main']) )
			$data['main'] = array();
		
		if(is_array($template))
		{
			foreach($template as $one)
			{
				$one = add_suffix($one);
				$CI->load->view($one, $data['main']);
			}
		}
		else
		{
			$template = add_suffix($template);
			$CI->load->view($template, $data['main']);
		}
		
		//加载footer
		if( !isset($data['footer']) )
			$data['footer'] = array();
		$CI->load->view('footer', $data['footer']);
	}
	
	function add_suffix($template)
	{
		if(!strpos($template, '.php'))
			$template .= EXT;
		return $template;
	}
	
	function page_nav($total, $pagesize, $current_page)
	{
		$total_page = ceil( $total / $pagesize);
		if( $current_page > $total_page ) $current_page = $total_page;
		if( $current_page < 1 ) $current_page = 1;

		$page_nav = array();	
		$page_nav['total'] = $total;
		$page_nav['total_page'] = $total_page;
		$page_nav['last_page'] = ($total_page == 0) ? 1 : $total_page;
		$page_nav['start'] = ( $current_page - 1 ) * $pagesize;
		
		if( $current_page < $total_page ){
			$page_nav['next'] = $current_page + 1;
		}
		if( $current_page > 1 ){
			$page_nav['previous'] = $current_page - 1;
		}
		$page_nav['current_page'] = $current_page;
		$page_nav['pagesize'] = $pagesize;

		return $page_nav;	
	}
	
	//解析URL中的 filter
	function parse_filter($filter)
	{
		if(empty($filter))
			return array();
		$filter = html_entity_decode($filter);
		$temp = explode('&', $filter);
		$result = array();
		foreach($temp as $val)
		{
			list($key, $value) = explode('=', $val);
			$result[$key] = $value;
		}
		return $result;
	}
	
	//把 filter 封装成URL
	function pack_fileter_url($page, $base_url, $filter)
	{
		if(empty($filter))
			return '';
		
		$filter['page'] = $page;
		
		$temp = array();
		foreach($filter as $key => $val)
		{
			if(empty($val) && ($val === FALSE))
				continue;
			
			$temp[] = $key.'='.$val;
		}
		
		$filter_string = implode('&', $temp);
		return site_url($base_url.'/'.$filter_string);
	}
	
	function show_result_page($notify, $back_url = '')
	{
		if(empty($back_url))
		{
			if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
				$back_url = $_SERVER['HTTP_REFERER'];
			else
				$back_url = site_url('');
		}
		else
		{
			$back_url = site_url($back_url);
		}
		
		$CI =& get_instance();
		
		//加载header
		$CI->load->view('header');
		
		//加载主页面
		$data['main']['notification'] = $notify;
		$data['main']['back_url'] = $back_url;
		$CI->load->view('common_result', $data['main']);
		
		//加载footer
		$CI->load->view('footer');
	}
	
	
	function show_error_page($notify, $back_url = '')
	{
		if(empty($back_url))
		{
			if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
				$back_url = $_SERVER['HTTP_REFERER'];
			else
				$back_url = site_url('');
		}
		else
		{
			$back_url = site_url($back_url);
		}
		
		$CI =& get_instance();
		
		//加载header
		$data['header']['meta_title'] = '错误!';
		$CI->load->view('header', $data['header']);
		
		//加载主页面
		$data['main']['notification'] = $notify;
		$data['main']['back_url'] = $back_url;
		$CI->load->view('common_error', $data['main']);
		
		//加载footer
		$CI->load->view('footer');
		
		echo $CI->output->get_output();
		exit();
	}
	
	function show_hour_options($name, $selected = '')
	{
		//默认选择。
		if(empty($selected))
			$selected ='00';
		
		$str = '<select name="'.$name.'">';
		for($i = 0; $i < 24; $i++)
			$str .= '<option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'" '.($selected == $i ? 'SELECTED' : '').'>'.str_pad($i, 2, 0, STR_PAD_LEFT);
		
		$str .= '</select>';
		return $str;
	}
	
	function show_mins_options($name, $selected = '')
	{
		//默认选择。
		if(empty($selected))
			$selected ='00';
		
		$str = '<select name="'.$name.'">';
		for($i = 0; $i < 60; $i++)
			$str .= '<option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'" '.($selected == $i ? 'SELECTED' : '').'>'.str_pad($i, 2, 0, STR_PAD_LEFT);
		
		$str .= '</select>';
		return $str;
	}
	
	function num_to_day($day)
	{
		switch($day)
		{
			case 1:
				return '周一';
				break;
			case 2:
				return '周二';
				break;
			case 3:
				return '周三';
				break;
			case 4:
				return '周四';
				break;
			case 5:
				return '周五';
				break;
			case 6:
				return '周六';
				break;
			case 0:
			case 7:
				return '周日';
				break;
		}
	}
	
	function period_str_to_arr($period_str)
	{
		$return = array();
		$week = explode(';', $period_str);
		foreach($week as $val)
		{
			list($day, $time) = explode(' ', $val);
			list($s_time, $e_time) = explode('-', $time);
			list($s_hour, $s_mins) = explode(':', $s_time);
			list($e_hour, $e_mins) = explode(':', $e_time);
			$return[$day] = array(
				's_hour' => $s_hour,
				's_mins' => $s_mins,
				'e_hour' => $e_hour,
				'e_mins' => $e_mins,
			);
		}
		
		return $return;
	}
	
	function period_arr_to_str($period_arr)
	{
		if(!isset($period_arr['period']) || empty($period_arr['period']))
			return array();
		
		$period_temp = array();
		foreach($period_arr['period'] as $val)
		{
			$s_hour = $period_arr['s_hour'][$val];
			$s_mins = $period_arr['s_mins'][$val];
			$e_hour = $period_arr['e_hour'][$val];
			$e_mins = $period_arr['e_mins'][$val];
			if($s_hour == '00' && $s_mins == '00' && $e_hour == '00' && $e_mins == '00')
			{
				$e_hour = '23';
				$e_mins = '59';
			}
			
			$period_temp[] = $val.' '.$s_hour.':'.$s_mins.'-'.$e_hour.':'.$e_mins;
		}
		
		return implode(';', $period_temp);
	}
	
	function check_strategy_available($period)
	{
		$period_arr = period_str_to_arr($period);
		$week_day = date("N");
		if(!isset($period_arr[$week_day]))
			return false;
		
		if($period_arr[$week_day]['s_hour'].':'.$period_arr[$week_day]['s_mins'] > date('H:i') 
			|| $period_arr[$week_day]['e_hour'].':'.$period_arr[$week_day]['e_mins'] < date('H:i'))
			return false;
		
		return true;
	}
	
	function check_user_role($room_id)
	{
		$user_info = get_user_info();
		
		if($user_info['type'] == USER_TYPE_CONFIG || $user_info['type'] == USER_TYPE_ADMIN)
			return 100;
		
		//用户信息
		$CI = & get_instance();
		$user_info = $CI->user_model->get_one($user_info['user_id']);
		if(empty($user_info['group_role']))
			return 0;
		
		$group_role_arr = explode(',', $user_info['group_role']);
		foreach($group_role_arr as $val)
		{
			list($id, $role) = explode(':', $val);
			$user_role[$id] = $role;
		}
		
		if(empty($user_role) || !isset($user_role[$room_id]))
			return 0;
		
		return $user_role[$room_id];
	}

	function device_sort($array)
	{
		usort($array, "device_sort_cmp");
		return $array;
	
	}
	function device_sort_cmp($a, $b)
	{
		if($a['status'] == -1)
			$a['status'] = 10;
		elseif($a['status'] == -100)
			$a['status'] = 20;
		elseif($a['status'] == -200)
			$a['status'] = 30;
		
		if($b['status'] == -1)
			$b['status'] = 10;
		elseif($b['status'] == -100)
			$b['status'] = 20;
		elseif($b['status'] == -200)
			$b['status'] = 30;
		
		
		if ($a['status'] == $b['status']) {
			return 0;
		}
		return ($a['status'] > $b['status']) ? -1 : 1;
	}
	
	function cache_room_info()
	{
		$file_name = APPPATH.'cache/room_cache.php';
		
		$CI =& get_instance();
		$CI->load->model('room_model');
		$rooms = $CI->room_model->get_rooms();
		
		$arr = array();
		foreach($rooms as $val)
			$arr[$val['room_id']] = $val['room_num'];
		
		$str = var_export( $arr, true );
		
		file_put_contents( $file_name, "<?php\n\$rooms=" . $str . ';');
	}
/* End of file common_helper.php */
/* Location: application/helpers/common_helper.php */