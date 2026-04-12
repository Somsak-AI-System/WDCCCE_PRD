<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

if ( ! function_exists('url_title'))
{
	function url_title($title, $separator = '  ', $lowercase = TRUE)
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
include(BASEPATH . 'helpers/url_helper.php');

?>