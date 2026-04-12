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


if ( ! function_exists('query_multi'))
{
	function query_multi(&$value, $index)
	{
		$value =  "". $value ."= VALUES (".$value .") " ;
	}
}

if ( ! function_exists('query_update'))
{
	function query_update(&$value, $index)
	{
		$value =  "". $index ."=" ."'".str_replace("'","''",$value) ."'" ;
	}
}

if ( ! function_exists('gen_query_multi'))
{
	function gen_query_multi($a_param=array())
	{
		if(empty($a_param)) return null;
		array_walk($a_param, "query_multi" );
		$s_update = implode($a_param,",");
		return $s_update;
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

if(! function_exists('replace_url'))
{

	function replace_url($data=null)
	{
		$CI =& get_instance();
		$a_url_old = $CI->config->item('url_old');
		$url_new= $CI->config->item('url_new');
		if(!empty($a_url_old)){

			foreach ($a_url_old as $val){

				$data = str_replace($val, $url_new, $data);
			}
		}
		return $data;
	}
}

if(! function_exists('date_questionnaire_mobile'))
{

	function date_questionnaire_mobile($date=null ,$format="Y-m-d")
	{
		if ($date=="") return $date;
		$a_date = explode('-',$date);
		if(is_array($a_date) && $a_date[0]>="2400")
		{
			$year = $a_date[0];
			$year = $year-543;
			return $year . '-' . $a_date[1] . '-' . $a_date[0];
		}
		else
		{
			return date($format, strtotime($date));
		}

	}
}

if(! function_exists('date_set'))
{

	function date_set($date=null ,$format="Y-m-d")
	{
		if ($date=="") return $date;
		$a_date = explode('-',$date);
		if(is_array($a_date) && $a_date[0] >"50")
		{
			$yyyy_mm_dd = $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0];
			return  date($format, strtotime($yyyy_mm_dd));
		}
		else
		{
			return date($format, strtotime($date));
		}

	}
}
if(! function_exists('datethai'))
{
	function datethai($strDate)
	{
		if(empty($strDate) || $strDate=="") return $strDate;
		$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		$strMonth= date("m",strtotime($strDate));
		$strDay= date("d",strtotime($strDate));
		$strwDay= date("w",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		//$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$TH_Day = array("อา.","จ.","อ.","พ.","พฤ.","ศ.","ส.");
		return "$TH_Day[$strwDay]  $strDay/$strMonth/$strYear";
	}
}

if(! function_exists('genurl'))
{
	function genurl($parameter=array())
	{
		if(empty($parameter)) return null;
		foreach( $parameter as $key => $key_value ){

			$query_array[] = urlencode( $key ) . '=' . urlencode( $key_value );

		}
		return $query_array;
	}
}
function encodearray(&$value, $index)
{
	//alert($value);
	//exit;
	//echo "<br>";exit();
	if($value == ''){
		return $value;
	}
	$value = iconv("tis-620","utf-8",trim($value)) ;
	//alert($value);
}
function gen_arrayiconv($a_param=array())
{
	if(empty($a_param)) return null;
	//alert($a_param);
	array_walk($a_param, "encodearray" );

	return $a_param;
}

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}


if(! function_exists('convert_datetime'))
{
	function convert_datetime($date="")
	{
		if(empty($date) || $date=="") return $date;
		$a_date["Monday"] = "วันจันทร์";
		$a_date["Tuesday"] = "วันอังคาร";
		$a_date["Wednesday"] = "วันพุธ";
		$a_date["Thursday"] = "วันพฤหัส";
		$a_date["Friday"] = "วันศุกร์";
		$a_date["Saturday"] = "วันเสาร์";
		$a_date["Sunday"] = "วันอาทิตย์";
		
		return @$a_date[$date];
	}
}