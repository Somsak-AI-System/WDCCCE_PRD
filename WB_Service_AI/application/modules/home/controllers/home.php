<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Home extends REST_Controller
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
	$this->load->model("home_model");
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


	 function list_home_get(){
		$a_param =  $this->input->get();
		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data);
	}

	public function list_home_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "List_Home";
	  	$this->common->set_log($url,"Parameter =>",$a_param);

		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data);
	}

	public function update_shortcut_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param = json_decode($request_body,true);

		$userID = $a_param['userid'];
		$menus = $a_param['menus'];

		$this->db->delete('aicrm_home_shortcut_menu', ['userid'=>$userID]);

		foreach($menus as $menu){
			$this->db->insert('aicrm_home_shortcut_menu', [
				'userid' => $userID,
				'tabid' => $menu['tabid'],
				'name' => $menu['name'],
				'seq' => $menu['seq']
			]);
		}

		$returnData = [
			'Type' => "S",
			'Message' => "Update Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => $menus
		];

		$this->response($returnData, 200);
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
		$month = @$a_params["month"];
		$a_data = $this->managecached_library->get_memcache($a_cache);

		if($a_data===false)
		{

			$a_list=$this->home_model->get_list($userid,$month);

			$sql = $this->db->query("SELECT DISTINCT
				aicrm_tab.tabid,
				aicrm_tab.name 
				FROM aicrm_field
				INNER JOIN aicrm_tab ON aicrm_tab.tabid = aicrm_field.tabid 
				WHERE aicrm_field.quickcreate = 0 AND aicrm_tab.presence != 1 AND aicrm_tab.mobile_seq != 0
				ORDER BY aicrm_tab.tablabel");
			$menuList = $sql->result_array();

			$this->db->select('tabid, name, seq');
			$sql = $this->db->get_where('aicrm_home_shortcut_menu', ['userid'=>$userid]);
			$userMenus = $sql->result_array();

			$a_data["status"] = $a_list['status'];
			$a_data["error"] = $a_list['error'];
			$a_data["profile"] = $a_list['profile'];
			$a_data["event"] = $a_list['event'];
			$a_data["summary"] = $a_list['summary'];
			$a_data["recent"] = $a_list['recent'];
			$a_data['menuList'] = $menuList;
			$a_data['userMenus'] = $userMenus;

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
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			// $a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$a_return["profile"] = !empty($a_data["profile"]) ? $a_data["profile"] : null ;
			$a_return["event"] = !empty($a_data["event"]) ? $a_data["event"] : null ;
			$a_return["summary"] = !empty($a_data["summary"]) ? $a_data["summary"] : null ;
			$a_return["myrecent"] = !empty($a_data["recent"]) ? $a_data["recent"] : null ;
			$a_return["menu_list"] = $a_data["menuList"];
			$a_return["user_menus"] = $a_data["userMenus"];

			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		  	$this->common->_filename= "List_Home";
		  	$this->common->set_log($url,"Response =>",$a_return);

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


	public function check_summary_post()
	{

		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Check_Summary";
	  	$this->common->set_log($url,"Parameter =>",$a_param);

		$userid = @$a_param["userid"];
		$month  = @$a_param["month"];

	  	$a_list=$this->home_model->get_summary($userid,$month);

	  	if(!empty($a_list)){

	  	$format =  $this->input->get("format",true);
			$a_return["Type"] = "S";
			$a_return["Message"] ="";
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			// $a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$a_return["summary"] = !empty($a_list) ? $a_list : null ;

		}else{

			$a_return["Type"] = "E";
			$a_return["Message"] ="No Data";
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			// $a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$a_return["summary"] = !empty($a_list) ? $a_list : null ;

		}

		$this->common->_filename= "Check_Summary";
	  	$this->common->set_log($url,"Response =>",$a_return);



	  	if ($format!="json" && $format!="xml"  ) {
			$this->response($a_return, 200); // 200 being the HTTP response code
		}else{
			$this->response($a_return, 200); // 200 being the HTTP response code
		}

		
	}


	public function all_summary_post()
	{

		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "All_Summary";
	  	$this->common->set_log($url,"Parameter =>",$a_param);

		$userid = @$a_param["userid"];
		$month  = @$a_param["month"];

	  	$a_list=$this->home_model->get_allsummary($userid,$month);

	  	if(!empty($a_list)){

	  	$format =  $this->input->get("format",true);
			$a_return["Type"] = "S";
			$a_return["Message"] ="";
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			// $a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$a_return["data"] = !empty($a_list) ? $a_list : null ;

		}else{

			$a_return["Type"] = "E";
			$a_return["Message"] ="No Data";
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			// $a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
			$a_return["data"] = !empty($a_list) ? $a_list : null ;

		}

		$this->common->_filename= "All_Summary";
	  	$this->common->set_log($url,"Response =>",$a_return);

	  	if ($format!="json" && $format!="xml"  ) {
			$this->response($a_return, 200); // 200 being the HTTP response code
		}else{
			$this->response($a_return, 200); // 200 being the HTTP response code
		}

		
	}



}
