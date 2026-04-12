<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

if ( ! function_exists('url_title'))
{
	function url_title($title, $separator = 'dash', $lowercase = TRUE)
	{
	  $title = str_replace(' ', '-', $title);
	  $title = mb_substr(strip_tags($title), 0, 20);
	  return rawurlencode(preg_replace('/\//', ' ', $title));

		//$title = strtolower(str_replace(" ","-",$title));
		$title = strtolower(preg_replace('~[^a-z0-9ก-๙\-\_\ \(\)]~iu','',$title));
		$title = preg_replace("/[\-]{2,}/",'-',$title);
		$title = trim($title, '-');
	
		return $title ;
	}
}
/*
if ( ! function_exists('url_title'))
{
	function url_title($str, $separator = '-', $lowercase = FALSE)
	{
		if ($separator == 'dash')
		{
		    $separator = '-';
		}
		else if ($separator == 'underscore')
		{
		    $separator = '_';
		}

		$q_separator = preg_quote($separator);

		$trans = array(
			'&.+?;'                 => '',
			'[^a-z0-9ก-๙\.\\_ -]~iu'          => '',
			'\s+'                   => $separator,
			'('.$q_separator.')+'   => $separator
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}

		return trim($str, $separator);
	}
}
*/
include(BASEPATH . 'helpers/url_helper.php');

?>