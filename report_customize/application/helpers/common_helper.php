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
if ( ! function_exists('site_privilege_url'))
{
	function site_privilege_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url('user_privileges/'.$uri);
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

	if ( ! function_exists('drop_down'))
{
    function drop_down($name, $match, $data)
    {
        $form = '<select name="'.$name.'"> ' ."\n";

        foreach($data as $key => $value)
        {
            $selected = ($match == $key) ? 'selected="selected"' : NULL ;
            $form .= '<option value="'. $key .'" '. $selected .'>'.$value.'' . "\n";
        }

        $form .= '</select>' . "\n";
        return $form;
    }
}

if ( ! function_exists('query_update'))
{
	function query_update(&$value, $index)
	{
		$value =  "". $index ."=" ."'".str_replace("'","''",$value) ."'" ;
	}
}
if ( ! function_exists('gen_query'))
{
	function gen_query($a_param=array())
	{
		if(empty($a_param)) return null;
		array_walk($a_param,'query_update');
		$s_update = implode($a_param,",");
		return $s_update;
	}
}

function date_set($date=null ,$format="Y-m-d")
{
	if ($date=="") return $date;
	//echo $date;
	$a_date = explode('/',$date);
	if(is_array($a_date) )
	{
		$yyyy_mm_dd = $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0];
		return  date($format, strtotime($yyyy_mm_dd));
	}
	else
	{
		return date($format, strtotime($date));
	}

}
