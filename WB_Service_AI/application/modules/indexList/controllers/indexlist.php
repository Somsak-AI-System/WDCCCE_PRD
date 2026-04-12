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
		$this->load->library('lib_api_common');
		$this->load->library('Lib_user_permission');
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
			$a_return["total"] = !empty($a_data["total"]) ? $a_data["total"] : 0;
			$a_return["limit"] = !empty($a_data["limit"]) ? $a_data["limit"] : 0;
			$a_return["offset"] = !empty($a_data["offset"]) ? $a_data["offset"] : 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"][0]["jsonrpc"] = "2.0";
			$a_return["data"][0]["language"] = !empty($a_data["language"]) ? $a_data["language"] : "EN";
			$a_return["data"][0]["id"] = "";
			$a_return["data"][0]["action_button"] = !empty($a_data["button"]) ? $a_data["button"] : array() ;
			$a_return["data"][0]["result"] = !empty($a_data["data"]) ? $a_data["data"] : array() ;

			if($module == "Calendar" || $module == "Sales Visit" || $module == "Schedule" || $module == "Events"){
				$a_return["data"][0]["result_birthdate"] = !empty($a_data["result_birthdate"]) ? $a_data["result_birthdate"] : array() ;
				$a_return["data_filter"] = !empty($a_data["data_filter"]) ? $a_data["data_filter"] : "" ;
			}

			$log_filename = "List_".$module;
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
		if($module=="Case"){
			$module="HelpDesk";
		}elseif ($module=="Spare Part" || $module=="SparePart" ) {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList") {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartList" || $module=="Spare Part List") {
			$module = "Sparepartlist";
		}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder" || $module=="Project Order") {
			$module = "Projects";
		}elseif($module=="Quotation"){
			$module="Quotes";
		}
		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,$module,$a_param);
	}
	
	private function get_cachedata($a_params=array())
	{
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
	    }elseif ($module=="Spare Part List" || $module=="SparePartList" || $module=="sparepartlist") {
	      	$module = "Sparepartlist";
		}elseif ($module=="Sales Visit" || $module=="Events"){
			$module="Calendar";
		}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder" || $module=="Project Order") {
			$module = "Projects";
		}elseif($module=="Quotation"){
			$module="Quotes";
	    }

		$this->load->library('Lib_user_permission');

		$sql_tabid = "select tabid from aicrm_tab where name='".$module."' ";
		$query_tab = $this->db->query($sql_tabid);
		if(!empty($query_tab)){
			$tabid = $query_tab->result_array();
			$tabid = $tabid[0]['tabid'];
		}else {
			$tabid = "";
		}
		
		$data_privileges =  $this->lib_user_permission->Get_user_privileges($userid);
		$sharing_module = @$data_sharing['SharingPermission'][$tabid]; 
		
		if($sharing_module=="0"){
		  	$sharing_public = true;
		}elseif ($sharing_module=="1") {
		  	$sharing_public = true;
		}elseif ($sharing_module=="2") {
		  	$sharing_public = true;
		}elseif ($sharing_module=="4") {
		  	$sharing_public = false;
		}

		$check_admin =  $data_privileges['is_admin'];
		$groupid =  $data_privileges['current_user_groups'];
		$parent_role =  $data_privileges['current_user_parent_role_seq'];

		$action_button = array();
		$action_button = array(
			'Create' => "true",
			'Edit' => "true",
			'Duplicate' => "true",
			'Delete' => "flase",
			'View' => "true"
		);

		if ($check_admin == 1) {
			$action_button['Create'] = "true";
			$action_button['Edit'] = "true";
			$action_button['Duplicate'] = "true";
			$action_button['Delete'] = "true";
			$action_button['View'] = "true";

			if ($module != 'Calendar') {
				$action_button['ConvertLead'] = "true";
			}
		} else {
			// alert($data_privileges['profileActionPermission'][$tabid]); exit();
			if (isset($data_privileges['profileActionPermission'][$tabid]) && !empty($data_privileges['profileActionPermission'][$tabid])) {
				
				foreach ($data_privileges['profileActionPermission'][$tabid] as $key => $value) {
					if ($value == 1) {
						$value_action = "flase";
					} else {
						$value_action = "true";
					}
					$sql_get_action = "select actionname from aicrm_actionmapping where actionid ='" . $key . "'";
					$query_permission = $this->db->query($sql_get_action);
					$response_permission = $query_permission->result_array();

					foreach ($response_permission as $k => $v) {
						if ($v['actionname'] == 'Save') {
							$action_button['Create'] = $value_action;
						} elseif ($v['actionname'] == 'EditView') {
							$action_button['Edit'] = $value_action;
						} elseif ($v['actionname'] == 'DuplicatesHandling') {
							if ($tabid == '14') $value_action = "flase";
							$action_button['Duplicate'] = $value_action;
						} elseif ($v['actionname'] == 'Delete') {
							$action_button['Delete'] = $value_action;
						} elseif ($v['actionname'] == 'DetailView') {
							$action_button['View'] = $value_action;
						} elseif ($v['actionname'] == 'ConvertLead') {
							$action_button['ConvertLead'] = $value_action;
						}
					}
				}
			}

		}

		if(!empty($groupid)){
			$groupid = implode(",",$groupid);
		}
		if (isset($userid) && $userid!="") {
			$where_user = " (aicrm_crmentity.smownerid IN (".$userid.")";

			if(!empty($parent_role) && $sharing_public==false){
				$where_user .= "  OR aicrm_crmentity.smownerid IN (SELECT aicrm_user2role.userid
				FROM  aicrm_user2role
				INNER JOIN  aicrm_users ON aicrm_users.id = aicrm_user2role.userid
				INNER JOIN  aicrm_role ON aicrm_role.roleid = aicrm_user2role.roleid
				WHERE aicrm_role.parentrole LIKE '".$parent_role."::%' )  ";
			}

			if(!empty($tabid)){
				$where_user .= " OR aicrm_crmentity.smownerid IN (SELECT shareduserid
				FROM  aicrm_tmp_read_user_sharing_per
				WHERE userid = '".$userid."' AND tabid = '".$tabid."') ";
			}

			$where_user .= "OR ( ";

			if(!empty($groupid) &&  $sharing_public==false){
				$where_user .= " aicrm_groups.groupid IN (".$groupid.")
				OR";
			}

			$where_user .= " aicrm_groups.groupid IN (SELECT
			aicrm_tmp_read_group_sharing_per.sharedgroupid
			FROM
			aicrm_tmp_read_group_sharing_per
			WHERE
			userid = '".$userid."' AND tabid = '".$tabid."')) ";

			$where_user .= " )";
		}

		if($sharing_public==true){
			$where_user = "aicrm_crmentity.smownerid != 0";
		  }
		
		if($check_admin==true){
			$where_user="aicrm_crmentity.smownerid != 0";
		}

		if($module=="Calendar"){
			$a_condition['date_start'] = @$a_params["date_start"];
			$a_condition['due_date'] = @$a_params["due_date"];
		}

		$optimize = @$a_params['optimize'];

		$userid = @$a_params['userid'];

		$user_lang = "EN";
		$user_lang = $this->crmentity->Get_userlang($userid);
		
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
		$a_filter = @$a_params['filter'];
		//alert($a_filter); exit;
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

			$a_list=$this->indexlist_model->get_list($a_condition,$orderby,$a_limit,$a_limit,$a_params,$where_user,$a_filter,$check_admin);
			
			$data = $a_list["result"];
			
			$a_data["data"] = isset($a_list["result"])?$a_list["result"]:null;
			$a_data["status"] = isset($a_list["status"])?$a_list["status"]:null;
			$a_data["limit"] = isset($a_limit["limit"])?$a_limit["limit"]:0;
			$a_data["offset"] = isset($a_limit["offset"])?$a_limit["offset"]:0;
			$a_data["total"] = isset($a_list["total"])?$a_list["total"]:0;
			$a_data["language"] = isset($user_lang)?$user_lang:"EN";
			$a_data["button"] = $action_button;

			if($module == "Calendar" || $module == "Sales Visit" || $module == "Schedule" || $module == "Events"){
				$a_data["result_birthdate"] = !empty($a_list["result_birthdate"]) ? $a_list["result_birthdate"] : array() ;
				$a_data["data_filter"] = !empty($a_list["data_filter"]) ? $a_list["data_filter"] : "" ;
			}

			if($a_list['error']=="No Data"){
					$a_data["error"] = $a_list['error'] ;
			}else {
				$a_data["error"] = "" ;
			}
			
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = isset($data)?$data:null;
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
		
	    return $a_data;
	}

	public function birthday_calendar_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$module = $a_param['module'];
		$a_condition['date_start'] = $a_param['date_start'];
		$a_condition['due_date'] = $a_param['due_date'];
		$userid = $a_param['userid'];

		
		$a_data=$this->indexlist_model->get_birthdate($a_condition,$userid);


		if($a_data)
		{
		
			$format =  $this->input->get("format",true);
			if(!empty($a_data)){
				$a_return["Type"] = "S";
				$a_return["Message"] = "" ;
				$a_return["cachetime"] = date("Y-m-d H:i:s");
				$a_return["data_birthdate"][] = !empty($a_data) ? $a_data : "" ;
			}else{
				$a_return["Type"] = "E";
				$a_return["Message"] = "No Data" ;
				$a_return["cachetime"] = date("Y-m-d H:i:s");
				$a_return["data_birthdate"][] = !empty($a_data) ? $a_data : "" ;
			}
			

			$log_filename = "Birthday_Calendar";
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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



}
