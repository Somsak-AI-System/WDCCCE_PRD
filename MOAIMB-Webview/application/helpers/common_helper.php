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
if ( ! function_exists('site_cache_url'))
{
	function site_cache_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url('cache/'.$uri);
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

if(! function_exists('date_set'))
{

	function date_set($date=null ,$format="Y/m/d")
	{
		if ($date=="") return $date;
		$date = str_replace('/', '-', $date);
		$a_date = explode('-',$date);
		if(is_array($a_date) && $a_date[2]=="9999")
		{
			return $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0];
		}
		else
		{
			return date($format, strtotime($date));
		}

	}
}

if(! function_exists('date_import'))
{

	function date_import($date=null ,$format="Y/m/d")
	{
		if ($date=="") return $date;
		$date = str_replace('.', '-', $date);
		$a_date = explode('-',$date);
		if(is_array($a_date) )
		{
			$date =  $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0];
			if($a_date[2]=="9999"){
				return $date;
			}else{
				return date($format, strtotime($date));
			}
		}
			return false;
	}
}


if(! function_exists('valid_number'))
{

	function valid_number($data=null ,$decimal="0")
	{
		if ($data=="") return 0;
		if(is_numeric($data)){
			return $data;
		}else{
			return 0;
		}
	}
}

if(! function_exists('valid_start_single_quote'))
{

	function valid_start_single_quote($data=null)
	{
		if ($data=="") return '';
		if(startsWith($data,"'")){

			$str = substr($data, strlen("'"));
			if($str){
				return $str;
			}else{
				return "";
			}
		}else{
			return $data;
		}
	}
}

if(! function_exists('date_get'))
{

	function date_get($date=null ,$format="d/m/Y")
	{
		if ($date=="") return $date;
		//date format m/d/Y
		//$date = "7/14/2014";
		$date_time = explode(' ',$date);
		if (is_array($date_time)) {
			$date = $date_time[0];
		}
		//alert($date);
		//list($date,$time) =  explode(' ',$date);
		$date_explode = explode('/',$date);
		//alert(is_array($date_explode));
		//alert($date_explode);
		if (count($date_explode)>1) {
			//echo $date_explode[1];
			if($date_explode[2]=="9999"){
				$date_convert = $date_explode[1]  . '/' .  $date_explode[0] . '/' . $date_explode[2] ;
			}else{
				$yyyy_mm_dd = $date_explode[2] . '-' . $date_explode[0] . '-' . $date_explode[1];
				$date_convert = date($format, strtotime($yyyy_mm_dd));
			}

		}else{
			$date_convert = "";
		}

		//$date = str_replace('/', '-', $date);
		return $date_convert;
	}
}


if(! function_exists('time_get'))
{

	function time_get($date=null ,$format="d/m/Y")
	{
		if ($date=="") return $date;
		//date format m/d/Y
		//$date = "7/14/2014";
		$date_time = explode(' ',$date);
		if (is_array($date_time)) {
			$date = $date_time[0];
			$time = $date_time[1];
		}
		//return $time;

		$date_explode = explode('/',$date);
		if (count($date_explode)>1) {
			$yyyy_mm_dd = $date_explode[2] . '-' . $date_explode[0] . '-' . $date_explode[1] ." " .$time;
			$date_convert = date_format( date_create($yyyy_mm_dd),'H:i');
		}else{
			$date_convert = "";
		}

		//echo $date_convert;
		//$date = str_replace('/', '-', $date);
		return $date_convert;
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

if ( ! function_exists('content_substr'))
{
	function content_substr($s_content,$i_start = 0,$i_ext = 15,$s_dot = '..'){
		$s_title = mb_substr($s_content, $i_start, $i_ext, "utf-8");
		if($s_dot) {
			if(mb_substr($s_content, $i_ext,1, "utf-8")) $s_ext = $s_dot;
			else $s_ext = '';
			return $s_title.$s_ext;
		} else {
			return $s_title;
		}
	}
}


if ( ! function_exists('startsWith'))
{
	function startsWith($haystack, $needle)
	{
		return $needle === "" || strpos($haystack, $needle) === 0;
	}
}

if ( ! function_exists('endsWith'))
{
	function endsWith($haystack, $needle)
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
}

if ( ! function_exists('getPermission'))
{
	function getPermission($module)
	{
		$data = [];

		$filename = FILE_MENU_PATH.FILE_PREFIX_PERMISSION.PROFILEID.FILE_TYPE_JSON;

		$string = file_get_contents($filename);

		$json = json_decode($string, true); //alert($json);

		$a_permission = $json['permission'];

		for($i = 0 ; $i < count($a_permission) ; $i++)
		{
			if(isset($a_permission[$module]))
			{
				$data = $a_permission[$module];
			}
		}

		foreach($a_permission as $key => $permission){
			if($permission['menuid'] == $module)
			{
				$data = $a_permission[$key];
			}
		}
		return $data;
	}
}


if ( ! function_exists('setActiveTab'))
{
	function setActiveTab()
	{
		$CI =& get_instance();
		$active = $CI->session->userdata('active_tab');

		if($active == "1"){
			$result['active_tab'] = "0";
			$CI->session->set_userdata($result);
		}else{
			$result['active_tab'] = "1";
			$CI->session->set_userdata($result);
		}


	}
}


if ( ! function_exists('getActiveTab'))
{
	function getActiveTab()
	{
		$CI =& get_instance();
		return $CI->session->userdata('active_tab');
	}
}

if ( ! function_exists('convertDateFormat'))
{
	function convertDateFormat($date,$time)
	{
		$ex = explode('/', $date);
		$new_date = $ex[1].'/'.$ex[0].'/'.$ex[2].' '.$time;
		return $new_date;
	}
}

function convertDateToDB($date){
    if($date != ''){
        $date = str_replace('/','-',$date);
        $date = date('Y-m-d' , strtotime($date));
    }
    return $date;
}

function convertDateToDisplay($date){
    if($date != ''){
        $date = date('d/m/Y' , strtotime($date));
    }
    return $date;
}

function genRecordId($str){

    $recordid = array();
    $ar1 = explode('#', $str);
    foreach($ar1 as $key){
        $ar2 = explode('|', $key);
        $field = array(
            'FieldName' => $ar2[0],
            'Value' => $ar2[1]
        );
        $recordid[] = $field;
    }
    return $recordid;
}

function sortMultiArray (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

function arraySortASC (&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
	asort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[]=$array[$ii];
	}
	$array=$ret;
}

function arraySortDESC (&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
	arsort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[]=$array[$ii];
	}
	$array=$ret;
}

if ( ! function_exists('convertDateFormatToSave'))
{
	function convertDateFormatToSave($date)
	{
		$new_date = "";
		if($date != ""){
			$ex = explode('/', $date);
			$new_date = $ex[2].'-'.$ex[1].'-'.$ex[0];
		}

		return $new_date;
	}
}

function calculate_time_diff($date,$time){
	$date = convertDateFormat($date,$time);
	$seconds  = strtotime(date('m/d/Y H:i:s')) - strtotime($date);

	$months = floor($seconds / (3600*24*30));
	$days = floor($seconds / (3600*24));
	$hours = floor($seconds / 3600);
	$mins = floor(($seconds - ($hours*3600)) / 60);
	$secs = floor($seconds % 60);

//	if($seconds < 60)
//		$time = $secs." seconds ago";
//	else if($seconds < 60*60 )
//		$time = $mins." min ago";
//	else if($seconds < 24*60*60)
//		$time = $hours." hours ago";
//	else if($seconds < 24*60*60)
//		$time = $day." day ago";
//	else
//		$time = $months." month ago";

	if($months > 0)
		$time = $months." months ago";
    else if($days > 0)
		$time = $days." days ago";
    else if($hours > 0)
		$time = $hours." hours ago";
    else if($mins > 0 && $secs > 0)
		$time = $mins." minutes ago";
    else if($secs > 0)
		$time = $secs." seconds ago";
    else
		$time = '';


	return $time;
}