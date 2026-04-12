<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function unicode_decode($str) 
	{ 
		return stripcslashes(preg_replace("/\\\u([0-9A-F]{4})/ie", "iconv('utf-16', 'utf-8', hex2str(\"$1\"))", $str)); 
	} 
	function hex2str($hex){
	    $string='';
	    for ($i=0; $i < strlen($hex)-1; $i+=2){
	        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
	    }
	    return $string;
	}
/* End of file common_helper.php */
/* Location: ./system/helpers/common_helper.php */