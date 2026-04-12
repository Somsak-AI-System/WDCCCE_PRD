<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Sidemenu extends REST_Controller
{

	private $crmid;
  function __construct()
  {
    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->config('config_module');
		$this->load->library('lib_api_common');
		$this->load->library('Lib_user_permission');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("sidemenu_model");
	$this->_format = "array";
	$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
					'Crmid' => "",
					'ServicerequestNo' => ""
			),
	);
	}

	public function list_menu_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,"List");
	}

	public function list_menu_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

	  	$log_filename = "List_Module";
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$a_param,"");
	  
		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,"List");
	}


	private function get_cachedata($a_params=array())
	{

		$this->load->library('managecached_library');

		$a_cache = array();
    	$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$a_condition = array();

		$optimize = @$a_params['optimize'];

		$userid = @$a_params['userid'];

		$user_lang = "EN";
		$user_lang = $this->crmentity->Get_userlang($userid);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$a_order = @$a_params["orderby"];

		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;

		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);

		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));


		$a_data = $this->managecached_library->get_memcache($a_cache);


		$ipaddress=gethostbyaddr($_SERVER['REMOTE_ADDR'])." (".$_SERVER["REMOTE_ADDR"].")";

		if($userid != ''){
			$queryhistory ="INSERT INTO ai_check_user_login (user_id ,username,ipaddress,start_time,end_time,sysytem_name )
			Select  '".$userid."', aicrm_users.user_name, '".$ipaddress."', '".date('Y-m-d h:i:s')."', '0000-00-00 00:00:00'  , 'Mobile'
			from aicrm_users WHERE aicrm_users.id = '".$userid."' ";
			$this->db->query($queryhistory);

		}
		if($a_data===false)
		{
			
			$a_list=$this->sidemenu_model->get_menu($a_params);
			// alert($a_list);exit;
			$a_data["data"] = 	$a_list;
			if($a_list['status']=="1"){
				$a_data["status"] = "S";
				$a_data["language"] = $user_lang;
			}else if($a_list['status']==false){
				$a_data["status"] = "E";
			}
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = 	$a_list;
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	    return $a_data;
	}


	public function return_data($a_data,$action="")
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = $a_data["status"];

			$a_return["Message"] =!empty($a_data["error"]) ? $a_data["error"] : "" ;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["language"]= !empty($a_data["language"]) ? $a_data["language"] : "" ;
			$a_return["data"]["jsonrpc"] = "2.0";
			$a_return["data"]= !empty($a_data["data"]) ? $a_data["data"] : "" ;

			$log_filename = $action."_Module";
			$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= $log_filename;
			$this->common->set_log($url,"",$a_return["data"]);
	  
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



	function update_menu_post(){
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);

		// $a_params=$a_param;
		$log_filename = "Update_Module";
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$a_param,"");

		$this->load->library('managecached_library');

		$a_cache = array();
    $a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$a_condition = array();

		$optimize = @$a_param['optimize'];

		$userid = @$a_param['userid'];


		$limit = @$a_param["limit"];
		$offset = @$a_param["offset"];
		$a_order = @$a_param["orderby"];
		// $a_order = $this->set_order($order);

		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;

		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);

		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));


		$a_data = $this->managecached_library->get_memcache($a_cache);

		if($a_data===false)
		{
		$a_list=$this->sidemenu_model->update_tabmenu($a_param);

		$a_data["data"] = 	$a_list;
		$a_data["data"] = 	$a_list;
		if($a_list['status']=="1"){
			$a_data["status"] = "S";
		}else if($a_list['status']==false){
			$a_data["status"] = "E";
		}
		$a_data["time"] = date("Y-m-d H:i:s");
		$a_cache["data"] = 	$a_list;
		$a_cache["data"]["time"] = date("Y-m-d H:i:s");
		$this->managecached_library->set_memcache($a_cache,"2400");
	}

	$this->return_data($a_data,"Update");

	}


}
