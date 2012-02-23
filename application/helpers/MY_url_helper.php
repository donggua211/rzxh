<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * images文件夹url路径
 */
function img_base_url()
{
	$CI =& get_instance();
	return $CI->config->base_url().'/images/';
}

/**
 * css文件夹url路径
 */
function css_base_url()
{
	$CI =& get_instance();
	return $CI->config->base_url().'/css/';
}

/**
 * js文件夹url路径
 */
function js_base_url()
{
	$CI =& get_instance();
	return $CI->config->base_url().'/js/';
}

/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */