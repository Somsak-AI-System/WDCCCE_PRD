<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Relate extends REST_Controller
{

	private $crmid;
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
	$this->load->library('lib_api_common');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("relate_model");
	$this->_format = "array";
	$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
					'Crmid' => null,
					'ServicerequestNo' => null
			),
	);
  }

	public function insert_data_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param = json_decode($request_body,true);
		// alert($a_param); exit;
		// echo $a_param['relate_module']; exit;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		  $this->common->_filename= "Save_Related_".$a_param['relate_module'];
		//   echo $this->common->_filename; exit;
		  $this->common->set_log($url,$a_param,"");

		  $data = $this->relate_model->check_relate_list($a_param);
		  // alert($data); exit;

		if($data['status']==true){
			$a_return["Type"] = "S";
			$a_return["Message"] = "Insert Success";
		}else{
			$a_return["Type"] = "E";
			$a_return["Message"] = !empty($data['error']) ? $data['error'] : "ลองอีกครั้ง" ;

		}

		  $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		  $this->common->_filename= "Save_Related_".$a_param['relate_module'];
		  $this->common->set_log($url,$a_param,$a_return);
			
		  if ($a_return) {
			  $this->response($a_return, 200); // 200 being the HTTP response code
		  } else {
			  $this->response(array(
				  'error' => 'Couldn\'t find Set Content!'
			  ), 404);
	  }
	}
	
	
}