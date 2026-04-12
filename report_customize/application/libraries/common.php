<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Common
{
  public $_tab = array();

  function __construct()
  {
    $this->CI = & get_instance();
    $this->CI->load->library('logging');
    $this->_format = "array";//array|json
    $this->_filename = "info";

  }


  public function set_log($url=null,$param=null,$response=null)
  {
  	//if($url=="") return false;
	//print_r($response);
	 $this->write_log($url,$param,$response);
  }

  public function set_log_error($url=null,$param=null,$response=null)
  {
  	if($this->_filename == "info" || $this->_filename ==""){
  		$this->_filename = "error";
  	}
  	$logger = $this->CI->logging->get_logger($this->_filename );
  	//echo $response;
  	$logger->error("URL:: ".$url);
  	$logger->error("Parameter ::".json_encode($param));
  	if($this->_format =="array"){
  		$logger->error("Response:: ".json_encode($response));
  	}else{//json
  		$logger->error("Response:: ".$response);
  	}
  }

  private function write_log($url=null,$param=null,$response=null)
  {
  		$logger =$this->CI->logging->get_logger($this->_filename);


  		$logger->info("URL:: ".$url);
  		$logger->info("Parameter ::".json_encode($param));
  		if($this->_format =="array"){
			$logger->info("Response:: ".json_encode($response));
  		}else{//json
  			$logger->info("Response:: ".$response);
  		}

  	}
	public function get_user_email($user_id = "")
  	{
  		if($user_id==""){
  			return array();
  		}
  		$a_return = array();
  		$a_condition["id"] = $user_id;
  		$this->CI->db->where($a_condition);
  		$query = $this->CI->db->get('aicrm_users');
  		$a_return  = $query->result_array() ;
  		return @$a_return[0]["email1"] ;
  	}
}
