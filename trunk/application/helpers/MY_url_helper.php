<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * images�ļ���url·��
 */
function img_base_url()
{
	$CI =& get_instance();
	return $CI->config->base_url().'/images/';
}

/**
 * css�ļ���url·��
 */
function css_base_url()
{
	$CI =& get_instance();
	return $CI->config->base_url().'/css/';
}

/**
 * js�ļ���url·��
 */
function js_base_url()
{
	$CI =& get_instance();
	return $CI->config->base_url().'/js/';
}

/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */