<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class IndexList extends REST_Controller
{

	private $crmid;
  function __construct()
  {
    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		// $this->load->config('config_module');
		$this->load->library('lib_api_common');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("indexlist_model");
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

  	public function return_data($a_data,$module,$a_param)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"][0]["jsonrpc"] = "2.0";
			$a_return["data"][0]["id"] = "";
			$a_return["data"][0]["result"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$log_filename = "List_".$module;
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			// $this->common->_filename= "Insert_Calendar";
			$this->common->_filename= $log_filename;
			$this->common->set_log($url,$a_param,$a_return);

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



	public function list_index_get()
	{
		$a_param =  $this->input->get();
		$module = $a_param['module'];
		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,$module,$a_param);
	}

	public function list_index_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$module = $a_param['module'];
    // alert($module);exit;
		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,$module,$a_param);
	}


	private function get_cachedata($a_params=array())
	{
    // echo 11111;exit;
		$this->load->library('managecached_library');

		$a_cache = array();
    $a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$a_condition = array();

		if (isset($a_params["userid"]) && $a_params["userid"]!="") {
			$userid = array();
			$userid = $this->common->get_user2role($a_params["userid"]);
				$where_user = "aicrm_crmentity.smownerid in (".$userid.")";
		}


		$optimize = @$a_params['optimize'];

		$userid = @$a_params['userid'];
		$module = @$a_params['module'];

		if($module=="Case"){
	    $module="HelpDesk";
	    }elseif ($module=="Spare Part" || $module=="SparePart") {
	      $module = "Sparepart";
	    }elseif ($module=="Errors List" || $module=="ErrorsList" ) {
	      $module = "Errorslist";
	    }elseif ($module=="Spare Part List" || $module=="SparePartList") {
	      $module = "Sparepartlist";
	    }

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];

		$this->load->config('config_module');

		$config = $this->config->item('module');
		$configModule = $config[$module];
		$tab_name = $configModule['tab_name'];
		$table_name = $tab_name[1];
		$tab_name_index = $configModule['tab_name_index'];
		$column_name = $tab_name_index[$table_name];


		$a_order[0]["field"] = $table_name.".".$column_name."";
		$a_order[0]["order"] = "ASC";
		$orderby = $a_order[0]["field"];

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
			$this->load->config('config_module');

			$a_list=$this->indexlist_model->get_list($a_condition,$orderby,$a_limit,$a_limit,$a_params,$where_user);

			$data = $a_list["result"];

			$a_data["data"] = $a_list["result"];
			$a_data["status"] = $a_list["status"];
			$a_data["limit"] = $a_limit["limit"]  ;
			if($a_list['error']=="No Data"){
					$a_data["error"] = $a_list['error'] ;
			}else {
				$a_data["error"] = "" ;
			}
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $data;
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
    //alert($a_data);exit;
	    return $a_data;
	}



}
