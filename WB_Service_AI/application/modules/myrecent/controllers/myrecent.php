<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Myrecent extends REST_Controller
{

	private $crmid;
		private $tab_name = array('aicrm_users');
		private $tab_name_index = array('aicrm_users'=>'id');
    function __construct()
  {
    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		// $this->load->config('config_module');
		$this->load->library('lib_api_common');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("myrecent_model");
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


	 function list_myrecent_get(){
		$a_param =  $this->input->get();
		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data);
	}

	public function list_myrecent_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data);
	}


	private function get_cachedata($a_params=array())
	{

		$this->load->library('managecached_library');

		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$module = @$a_params["module"];
		$action = @$a_params["action"];
		$userid = @$a_params["userid"];
		// $crmid= @$a_params["crmid"];
		// $crmid= @$a_params["userid"];
		//$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($module)));
		$a_data = $this->managecached_library->get_memcache($a_cache);

		if($a_data===false)
		{

			$a_list=$this->myrecent_model->get_list($userid);
			// $a_data = $this->get_data($a_list,$optimize);
			// $a_data = $a_list;
			$a_data["status"] = $a_list['status'];
			$a_data["error"] = $a_list['error'];
			$a_data["total"] = count($a_list['result']);
			$a_data["data"] = $a_list["result"];
			$a_data["time"] = date("Y-m-d H:i:s");
			
			$a_cache["data"] = $a_list["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	    return $a_data;
	}



	public function return_data($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = !empty($a_data["total"]) ? $a_data["total"] : 0 ;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			if ($format!="json" && $format!="xml"  ) {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}



}
