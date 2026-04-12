<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Crm_api
{
  public $current_get_api = array();
  public $current_post_api = array();
  public $current_post_params = array();
  public $debug = FALSE;

  function __construct()
  {
    $this->CI = & get_instance();

  }

  public function get_cache($a_params=array(),$url=null,$method=null,$module=null)
  {
  		$url = $url.$method;
		
  		$response = $this->CI->curl->simple_post($url,$a_params,array(),"json");
		
		//print_r($response);
		
		$a_data =  json_decode($response,true);
		//print_r($a_data);

  		if(isset($_GET['debug']) && isset($_GET['passcode']) && $_GET['passcode'] == PASSCODE)
        {
  			/*echo "<code>".($url.$method.'?'.rawurldecode(http_build_query($a_params))). "</code><br/>\n\n";*/
			echo "<code>".($url.'?'.rawurldecode(http_build_query($a_params))). "</code><br/>\n\n";
  		}
	
  	return $a_data;
  }




  function get_server_name()
  {
  	$s_hostname =	$this->CI->session->userdata('user.hostname');
	if($s_hostname!=""){
		return $s_hostname;
	}else{
		$a_data['user.hostname'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$this->CI->session->set_userdata($a_data);
		return $a_data['user.hostname'];
	}


  }

  /**
   * Api Post Api
   * Call api from get
   * $method = method call
   * $param = option
   */
  public function post_api($url = NULL,$method=null, $module = NULL, $params = array())
  {
    if(! $url) return 'Method require';

    $a_data = $this->get_cache($params,$url,$method,$module);
	
	//print_r($a_data);exit();

   	$data["result"]["data"] = @$a_data["data"];
    $data["total"] = @$a_data["total"];
    $data["msg"] = $a_data["Message"];
    $data["status"] = (isset($a_data["Type"]) && $a_data["Type"]=="S")?true:false;
    return $data;
  }
}
