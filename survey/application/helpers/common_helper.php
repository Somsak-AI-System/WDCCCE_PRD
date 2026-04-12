<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Site Assets URL
 *
 * Create a local URL based on your basepath. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('site_assets_url'))
{
	function site_assets_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url('assets/'.$uri);
	}
}
if(! function_exists('alert'))
{

	function alert()
	{
		$arg_list = func_get_args();
		foreach($arg_list as $k => $v)
		{
			print '<pre class="alert">';
			print_r($v);
			print '</pre>';
		}
	}
}
// ------------------------------------------------------------------------

/**
 * Site Root URL
 *
 * Create a local URL based on your basepath. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('site_root_url'))
{
	function site_root_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url($uri);
	}
}
