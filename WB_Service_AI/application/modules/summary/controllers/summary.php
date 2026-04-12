<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Summary extends REST_Controller
{

	private $crmid;
	private $tab_name = array('aicrm_users');
	private $tab_name_index = array('aicrm_users' => 'id');
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		// $this->load->config('config_module');
		$this->load->library('lib_api_common');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("summary_model");
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


	function list_summary_get()
	{
		$a_param =  $this->input->get();
		$a_data = $this->get_cachedata($a_param);
		$this->return_data($a_data);
	}

	public function list_summary_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body, true);
		
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename = "List_Summary";
		$this->common->set_log($url, "Parameter =>", $a_param);
		
		$a_data = $this->get_cachedata($a_param);
		$this->return_data($a_data);
	}

	private function get_cachedata($a_params = array())
	{

		$this->load->library('managecached_library');

		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module . '/';
		$a_cache["_ckname"] = $this->_module . '/get_content';

		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if ($a_data === false) {

			$a_list = $this->summary_model->get_list($a_params);

			$a_data["status"] = $a_list['status'];
			$a_data["error"] = $a_list['error'];
			$a_data["profile"] = $a_list['profile'];
			$a_data["summary"] = $a_list['summary'];
			$a_data["summary_of_type"] = $a_list['summary_of_type'];

			$a_cache["data"] = $a_list["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache, "2400");
		}
		return $a_data;
	}



	public function return_data($a_data)
	{
		if ($a_data) {
			$format =  $this->input->get("format", true);
			$a_return["Type"] = ($a_data["status"]) ? "S" : "E";
			$a_return["Message"] = $a_data["error"];
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["profile"] = !empty($a_data["profile"]) ? $a_data["profile"] : null;
			$a_return["summary"] = !empty($a_data["summary"]) ? $a_data["summary"] : null;
			$a_return["summary_of_type"] = !empty($a_data["summary_of_type"]) ? $a_data["summary_of_type"] : null;

			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename = "List_Summary";
			$this->common->set_log($url, "Response =>", $a_return);

			if ($format != "json" && $format != "xml") {
				$this->response($a_return, 200); // 200 being the HTTP response code
			} else {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		} else {
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

	public function all_summary_post()
	{

		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body, true);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename = "All_Summary";
		$this->common->set_log($url, "Parameter =>", $a_param);

		$module = @$a_param["module"];
		$userid = @$a_param["userid"];
		$month  = @$a_param["month"];

		$a_list = $this->summary_model->get_allsummary($userid, $month, $module);

		if (!empty($a_list)) {

			$format =  $this->input->get("format", true);
			$a_return["Type"] = "S";
			$a_return["Message"] = "";
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			// $a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$a_return["data"] = !empty($a_list) ? $a_list : null;
		} else {

			$a_return["Type"] = "E";
			$a_return["Message"] = "No Data";
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			// $a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$a_return["data"] = !empty($a_list) ? $a_list : null;
		}

		$this->common->_filename = "All_Summary";
		$this->common->set_log($url, "Response =>", $a_return);

		if ($format != "json" && $format != "xml") {
			$this->response($a_return, 200); // 200 being the HTTP response code
		} else {
			$this->response($a_return, 200); // 200 being the HTTP response code
		}
	}
}
