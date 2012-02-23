<?php
/* 
  历史记录.
 */
class History extends CI_Controller
{
	//构造函数
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('history_model');
		$this->load->model('room_model');
		
		//如果没有经登录, 就跳转到admin/login登陆页
		if (!has_login())
		{
			goto_login();
		}
		
		$this->user_info = get_user_info();
	}
	
	//默认页
	function index($filter_string = '')
	{
		//默认值
		$filter['page'] = 1;
		$filter['room_id'] = $this->input->post('room_id');
		$filter['device_id'] = $this->input->post('device_id');
		$filter['add_time_a'] = ($this->input->post('add_time_a')) ? $this->input->post('add_time_a').' 00:00:00' : '';
		$filter['add_time_b'] = ($this->input->post('add_time_b')) ? $this->input->post('add_time_b').' 23:59:59' : '';
		$filter['type'] = ($this->input->post('submit') == '查询报警数据') ? 'warning' : '';
		
		$filter = $this->_parse_filter($filter_string, $filter);
		
		//Page Nav
		$total = $this->history_model->getAll_count($filter['type'], $filter);
		$page_nav = page_nav($total, HISTORY_PER_PAGE, $filter['page']);
		$page_nav['base_url'] = 'history';
		$page_nav['filter'] = $filter;
		$data['main']['page_nav'] = $this->load->view('common_page_nav', $page_nav, true);
		
		$history = $this->history_model->getAll($filter['type'], $filter, $page_nav['start'], HISTORY_PER_PAGE, 'staff_extra.subject_id');
		
		$data['main']['history'] = $history;
		$data['main']['rooms'] = $this->room_model->get_rooms();
		$data['main']['filter'] = $filter;
		_load_viewer('history_index', $data);
	}
	
	//解析器，将url参数转换成数组
	function _parse_filter($filter_string, $filter)
	{
		$input_filter = parse_filter($filter_string);
		foreach($filter as $key => $value)
		{
			if(!isset($input_filter[$key]))
				continue;
			
			switch($key)
			{
				case 'add_time_a':
				case 'add_time_b':
					$filter[$key] = $input_filter[$key];
					break;
				case 'page':
				case 'room_id':
				case 'device_id':
					$input_filter[$key] = intval($input_filter[$key]);
					break;
			}
			
			if(empty($input_filter[$key]) && $input_filter[$key] !== 0)
				continue;
			
			$filter[$key] = $input_filter[$key];
		}
		return $filter;
	}
}

/* End of file entry.php */
/* Location: application/controllers/entry.php */