<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Register extends REST_Controller
{
  
	private $crmid;
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	//$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("register_model");
	//$this->load->model("knowledgebase_model");
	$this->_limit = 100;
	$this->_module = "Register";
	$this->_format = "array";

	$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'data' => array(),
	);

	$this->_data= array(
			"status" => false,
			"message" =>"",
			"time" => date("Y-m-d H:i:s"),
			"data" => array("data" => ""),
	);
  }


  	public function check_username_get()
	{
		$a_param =  $this->input->get();
		$a_data=$this->register_model->check_username($a_param);

		if(!isset($a_data['number_username'])){
			$a_data["status"] = false;
			$a_data["message"] = "Cann't Found Data";

		}else{

			if($a_data['number_username'] > 0){
				/* Username is exist already */
				$a_data["status"] = false;
				$a_data["message"] = "Number of username : ".$a_data['number_username']." number";
			}else{
				/* Username can used */
				$a_data["status"] = true;
				$a_data["message"] = "Can used username : ".$a_param['username'];
			}
			
		}

		$this->return_data($a_data);
	}

	public function check_username_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$a_data=$this->register_model->check_username($a_param['condition']);

		if(!isset($a_data['number_username'])){
			$a_data["status"] = false;
			$a_data["message"] = "Cann't Found Data";

		}else{

			if($a_data['number_username'] > 0){
				/* Username is exist already */
				$a_data["status"] = false;
				$a_data["message"] = "Number of username : ".$a_data['number_username']." number";
			}else{
				/* Username can used */
				$a_data["status"] = true;
				$a_data["message"] = "Can used username : ".$a_param['condition']['username'];
			}
		}

		$this->return_data($a_data);
	}

	private function check_username($a_params=array())
	{
		
		if(empty($a_params["username"])){
			$a_data["status"] = false;
			$a_data["message"] = "Username is null";
			return array_merge($this->_data,$a_data);
		}
	}

	public function return_data($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["message"];
			$a_return["cachetime"] = @$a_data["time"];
			//$a_return["data"] = @$a_data["data"]["data"];
		
			if ($format!="json" && $format!="xml"  ) {
					$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
				$this->response(array('error' => 'Couldn\'t find any Register!'), 404);
		}
	}
}