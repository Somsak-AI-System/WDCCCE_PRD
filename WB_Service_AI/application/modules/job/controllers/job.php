<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Job extends REST_Controller
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
		$this->load->model("job_model");
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
				'JobNo' => null
			),
		);
	}


	//  public function insert_job_post(){

	//  $request_body = file_get_contents('php://input');
	//  $dataJson     = json_decode($request_body,true);

	//  $response_data = null;

	//  $a_request =$dataJson;

	// $response_data = $this->get_insert_data($a_request);

	//  $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	//  $this->common->_filename= "Insert_Job";
	//  $this->common->set_log($url,$a_request,$response_data);
	//  if ( $response_data ) {
	// 	 $this->response($response_data, 200); // 200 being the HTTP response code
	//  } else {
	// 	 $this->response(array(
	// 			 'error' => 'Couldn\'t find Set Content!'
	// 	 ), 404);
	//  }
	// }

	public function insert_content_post()
	{


		$this->common->_filename = "Insert_Job";
		header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body, true);
		$response_data = null;
		$a_request = $dataJson;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->common->set_log('Before Insert Job==>', $url, $a_request);
		$response_data = $this->get_insert_data($a_request);
		$this->common->set_log('After Insert Job==>', $a_request, $response_data);

		if ($response_data) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}

	function get_returnlocation($crmid = "")
	{
		$sql = "select *
			 FROM aicrm_activity
		 LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
		 LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
		 LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activitycf.accountid
		 WHERE aicrm_crmentity.deleted = 0
		 AND crmid = " . $crmid . "
		 AND aicrm_activitycf.location ='' ";

		$query = $this->db->query($sql);
		$data = $query->result_array();

		if (count($data) > 0) {
			return "E";
		} else {
			return "S";
		}
	}

	public function get_insert_data($a_request)
	{

		$response_data = null;
		$module = isset($a_request['module']) ? $a_request['module'] : "";
		$crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
		// $DocNo=isset($a_request['leadid']) ? $a_request['leadid'] : "";
		$DocNo = isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$action = isset($a_request['action']) ? $a_request['action'] : "";
		$data = isset($a_request['data']) ? $a_request['data'] : "";
		$userid = isset($a_request['userid']) ? $a_request['userid'] : "1";

		$smownerid =  isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid'] != "" ? $a_request['data'][0]['smownerid'] : "0";
		$serialid =  isset($a_request['data'][0]['serialid']) && $a_request['data'][0]['serialid'] != "" ? $a_request['data'][0]['serialid'] : "0";
		$product_id =  isset($a_request['data'][0]['product_id']) && $a_request['data'][0]['product_id'] != "" ? $a_request['data'][0]['product_id'] : "0";
		$ticketid =  isset($a_request['data'][0]['ticketid']) && $a_request['data'][0]['ticketid'] != "" ? $a_request['data'][0]['ticketid'] : "0";
		$data[0]["smownerid"] = $smownerid;
		$data[0]["serialid"] = $serialid;
		$data[0]["product_id"] = $product_id;
		$data[0]["ticketid"] = $ticketid;

		if (count($data[0]) > 0 and $module == "Job") {

			$this->load->config('config_module');

			$config = $this->config->item('module');
			$configModule = $config['Job'];
			$tab_name = $configModule['tab_name'];
			$tab_name_index = $configModule['tab_name_index'];


			list($chk, $crmid, $DocNo) = $this->crmentity->Insert_Update($module, $crmid, $action, $tab_name, $tab_name_index, $data, $userid);

			if ($chk == "0") {
				// $this->set_notification($data,$crmid,$smownerid);
				$a_return["Message"] = ($action == "add") ? "Insert Complete" : "Update Complete";
				$a_return["data"] = array(
					'Crmid' => $crmid,
					'JobNo' => $DocNo,

				);
			} else {
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Unable to complete transaction',
				);
			}
		} else {
			$a_return  =  array(
				'Type' => 'E',
				'Message' =>  'Invalid Request!',
			);
		}
		return array_merge($this->_return, $a_return);
	}

	private function get_cache($a_params = array())
	{	
		$this->load->library('managecached_library');

		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module . '/';
		$a_cache["_ckname"] = $this->_module . '/get_content';
		$module = @$a_params["module"];
		$action = @$a_params["action"];
		$userid = @$a_params["userid"];
		$crmid = @$a_params["crmid"];

		$a_data = $this->managecached_library->get_memcache($a_cache);

		if ($module == "Calendar" || $module == "Sales Visit" || $module == "Sale Visit" || $module == "Events" || $module == "Visit") {
			$module = "Calendar";
		} elseif ($module == "Projectorders" || $module == "Project Orders" || $module == "Projectorder" || $module == "Project Order") {
			$module = "Projects";
		} elseif ($module == "Spare Part" || $module == "SparePart") {
			$module = "Sparepart";
		} elseif ($module == "Errors List" || $module == "ErrorsList") {
			$module = "Errorslist";
		} elseif ($module == "Spare Part List" || $module == "SparePartList" || $module == "Spare Part List") {
			$module = "Sparepartlist";
		} else if ($module == "Case") {
			$module = "HelpDesk";
		}

		if ($action == "relate") {
			$a_list = $this->lib_api_common->Get_Relate($module, $crmid, $userid);
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache, "2400");
		} else {
			if ($a_data === false) {
				$a_list = $this->lib_api_common->Get_Block($module, $action, $crmid);
				$a_cache["data"]["time"] = date("Y-m-d H:i:s");
				$this->managecached_library->set_memcache($a_cache, "2400");
			}
		}
		return $a_list;
	}


	public function return_data($a_data)
	{
		if ($a_data) {
			$format =  $this->input->get("format", true);
			$a_return["Type"] = ($a_data["status"]) ? "S" : "E";
			$a_return["Message"] = $a_data["error"];
			$a_return["total"] = 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"][0]["jsonrpc"] = "2.0";
			$a_return["data"][0]["id"] = "";
			$a_return["data"][0]["result"] = !empty($a_data["data"]) ? $a_data["data"] : null;
			if ($format != "json" && $format != "xml") {
				$this->response($a_return, 200); // 200 being the HTTP response code
			} else {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		} else {
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

	public function GETFORM_get()
	{
		$a_param =  $this->input->get();
		$a_data = $this->get_cache($a_param);
		$this->return_data($a_data);
	}

	public function GETFORM_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body, true);
		$a_data = $this->get_cache($a_param);
		$this->return_data($a_data);
	}



	public function list_job_get()
	{
		$a_param =  $this->input->get();
		$a_data = $this->get_cachedata($a_param);
		$this->return_data($a_data);
	}

	public function list_job_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body, true);

		$a_data = $this->get_cachedata($a_param);
		$this->return_data($a_data);
	}


	private function get_cachedata($a_params = array())
	{

		$this->load->library('managecached_library');

		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module . '/';
		$a_cache["_ckname"] = $this->_module . '/get_content';

		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$optimize = @$a_params['optimize'];

		$userid = @$a_params['userid'];
		$module = @$a_params['module'];


		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order = @$a_params["orderby"];
		$a_order = $this->set_order($order);

		$a_limit["limit"] = ($limit == "") ? $this->_limit : $limit;
		$a_limit["offset"] = ($offset == "") ? 0 : $offset;

		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ? $a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);

		$a_cache["_ckname"] .= '_' . str_replace("&", "-", rawurldecode(http_build_query($a_build)));

		$a_data = $this->managecached_library->get_memcache($a_cache);

		$ipaddress = gethostbyaddr($_SERVER['REMOTE_ADDR']) . " (" . $_SERVER["REMOTE_ADDR"] . ")";

		if ($userid != '') {
			$queryhistory = "INSERT INTO ai_check_user_login (user_id ,username,ipaddress,start_time,end_time,sysytem_name )
			Select  '" . $userid . "', aicrm_users.user_name, '" . $ipaddress . "', '" . date('Y-m-d h:i:s') . "', '0000-00-00 00:00:00'  , 'Mobile'
			from aicrm_users WHERE aicrm_users.id = '" . $userid . "' ";
			$this->db->query($queryhistory);
		}
		if ($a_data === false) {
			$this->load->config('config_module');

			$config = $this->config->item('module');
			$configModule = $config[$module];
			$tab_name = $configModule['tab_name'];
			$tab_name = $tab_name[1];
			$tab_name_index = $configModule['tab_name_index'];
			$tab_name_index = $tab_name_index[$tab_name];

			$a_list = $this->job_model->get_list($a_condition, $a_order, $a_limit, $a_limit, $a_params);

			$a_data = $this->get_data($a_list, $optimize);

			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"];
			$a_data["offset"] = $a_limit["offset"];
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache, "2400");
		}
		return $a_data;
	}

	private function get_data($a_data, $optimize = '')
	{

		if ($optimize == '') {
			if (!empty($a_data["result"]["data"]) && $a_data["status"]) {
				foreach ($a_data["result"]["data"] as $key => $val) {
					$activityid = $val["activityid"];
					$a_activity[] = $activityid;
				}

				$a_conditionin["aicrm_activity.activityid"] = $a_activity;

				foreach ($a_data["result"]["data"] as $key => $val) {
					$activityid = $val["activityid"];
					$a_return = $val;

					$a_response[] = $a_return;
				}
				$a_data["result"]["data"] = $a_response;
			}
		}

		return $a_data;
	}


	private function set_param($a_param = array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"] != "") {
			$a_condition["aicrm_jobs.jobid"] =  $a_param["crmid"];
		}

		if (isset($a_param["userid"]) && $a_param["userid"] != "") {
			$userid = array();
			$userid = $this->common->get_user2role($a_param["userid"]);
			$this->db->where("aicrm_crmentity.smownerid in (" . $userid . ")");
		}

		if ((isset($a_param["date_start"]) && $a_param["date_start"] != "")  && (isset($a_param["due_date"]) && $a_param["due_date"] != "")) {
			$date_start = $a_param['date_start'];
			$due_date = $a_param['due_date'];
			/*$this->db->where("aicrm_activity.date_start  >=  '$date_start' AND aicrm_activity.due_date <= '$due_date'");*/
			$this->db->where('aicrm_activity.date_start >=', $date_start);
			$this->db->where('aicrm_activity.due_date <=', $due_date);
		}

		return $a_condition;
	}
	private function set_order($a_orderby = array())
	{
		if (empty($a_orderby)) {
			$a_order[0]["field"] = "aicrm_users.user_name";
			$a_order[0]["order"] = "";
			$a_order[1]["field"] = "aicrm_jobs.close_date";
			$a_order[1]["order"] = "";
			$a_order[2]["field"] = "aicrm_jobs.job_minit";
			$a_order[2]["order"] = "ASC";
			return $a_order;
		}
		if ($a_orderby == 'aicrm_jobs.jobid,asc') {
			$a_order[0]["field"] = "aicrm_users.user_name";
			$a_order[0]["order"] = "";
			$a_order[1]["field"] = "aicrm_jobs.date_start";
			$a_order[1]["order"] = "";
			$a_order[2]["field"] = "aicrm_jobs.time_start";
			$a_order[2]["order"] = "ASC";
			return $a_order;
		}

		$a_order = array();
		$a_condition = explode("|", $a_orderby);

		for ($i = 0; $i < count($a_condition); $i++) {
			list($field, $order) = explode(",", $a_condition[$i]);
			$a_order[$i]["field"] = $field;
			$a_order[$i]["order"] = $order;
		}


		return $a_order;
	}


	public function get_relate_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body, true);

		$this->common->_filename = "Get_Releted";
		header('Content-Type:application/json; charset=UTF-8');
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->set_log('Before Get_Releted==>', $url, $a_param);
		$a_data = $this->get_cache($a_param);
		$this->return_dataRelate($a_data);
	}

	public function return_dataRelate($a_data)
	{
		if ($a_data) {
			$format =  $this->input->get("format", true);
			$a_return["Type"] = ($a_data["status"]) ? "S" : "E";
			$a_return["Message"] = $a_data["error"];
			$a_return["total"] = 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"]["jsonrpc"] = "2.0";
			$a_return["data"]["id"] = "";
			$a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : [];

			$this->common->_filename = "Get_Releted";
			header('Content-Type:application/json; charset=UTF-8');
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->set_log('After Get_Releted==>', "response", $a_return);

			if ($format != "json" && $format != "xml") {
				$this->response($a_return, 200); // 200 being the HTTP response code
			} else {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		} else {
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

	public function send_notification_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body, true);

		$crmid = isset($a_param['crmid']) ? $a_param['crmid'] : "";
		$smownerid = isset($a_param['smownerid']) ? $a_param['smownerid'] : "";
		$userid = isset($a_param['userid']) ? $a_param['userid'] : "";
		$a_data =  isset($a_param['data']) && $a_param['data'] != "" ? $a_param['data'] : "";
		$a_data = $this->set_notification($a_data, $crmid, $smownerid, $userid);

		$this->common->_filename = "Set_Calendar_Notification";
		$this->common->set_log($url, $a_param, $a_data);
		$this->return_data($a_data);
	}

	public function set_notification($a_data = array(), $crmid = "", $smownerid = "", $userid = "")
	{

		if (empty($a_data)) return "";
		if ($crmid == "") return "";
		if ($smownerid == "") return "";

		$this->load->config('config_notification');
		$config = $this->config->item('notification');

		$method = 'Job';
		$a_condition["aicrm_jobs.jobid"] = $crmid;
		$a_result = $this->job_model->get_notification($a_condition);
		$a_activity = @$a_result["result"]["data"][0];
		$queryfunction = @$config[$method]["queryfunction"];

		//get data notification
		if (method_exists($this, $queryfunction)) {
			$a_data_return_noti = $this->{$queryfunction}($crmid, $config[$method]);
			if ($a_data_return_noti["status"] === false || empty($a_data_return_noti["result"])) {
				return array_merge($this->_return, $a_data_return_noti);
				exit();
			}
			$a_noti = $a_data_return_noti["result"][0];
		} else {
			$a_data["status"] = false;
			$a_data["message"] = "Method Query is null";
			return array_merge($this->_return, $a_data);
		}

		//Get Time Send Notification
		$a_send = $this->get_send($config[$method]["send"], $a_noti);
		//$assingto = $this->get_userorgroup($smownerid);

		if ($a_send["status"] === false || empty($a_send["result"])) {
			return array_merge($this->_return, $a_send);
			exit();
		}

		$a_send_data = $a_send["result"];
		$notificationid = ($a_noti["notificationid"] == 0 || $a_noti["notificationid"] == "") ? "" : $a_noti["notificationid"];

		$msg = $this->get_message($a_noti);
		// Get field start date
		$field_startdate = $config[$method]["startdate"];
		$field_starttime = @$config[$method]["starttime"];
		$startdate_date = @$a_noti[$field_startdate];
		$startdate_time = @$a_noti[$field_starttime];

		$startdate = $startdate_date . ($startdate_time != "" ? " " . $startdate_time : "");

		// Get field End date
		$field_enddate = $config[$method]["enddate"];
		$field_endtime = @$config[$method]["endtime"];
		$enddate_date = @$a_noti[$field_enddate];
		$enddate_time = @$a_noti[$field_endtime];

		$enddate = $enddate_date . ($enddate_time != "" ? " " . $enddate_time : "");

		if ($startdate != "" && $enddate == "") {
			$enddate =  date("Y-m-d H:i:s", strtotime(($startdate) . "+1 hour"));
		}

		$a_param["Value1"] = $notificationid;
		$a_param["Value2"] = $crmid;
		$a_param["Value3"] = "Job";
		$a_param["Value4"] = ""; //send_total
		$a_param["Value5"] = ""; //send_success;
		$a_param["Value6"] = ""; //send_unsuccess*/
		$a_param["Value7"] = date('Y-m-d');
		$a_param["Value8"] = date('H:i', strtotime($a_activity["time_start"] . '+1 minutes')); //send_time
		$a_param["Value9"] = $msg; //send_message
		$a_param["Value10"] = "1"; //noti_type
		$a_param["Value11"] = ""; //result_filename
		$a_param["Value12"] = ""; //result_status
		$a_param["Value13"] = ""; //result_errorcode
		$a_param["Value14"] = ""; //result_errormsg
		$a_param["Value15"] = $smownerid; //userid
		$a_param["Value16"] = $userid; //empcd
		$a_param["Value17"] = ""; //noti_status
		$a_param["Value18"] = $config["projectcode"]; //project_code
		$a_param["Value19"] = $startdate; //
		$a_param["Value20"] = $enddate; //

		$a_param["Value21"] = $a_noti[$config[$method]["send"]['field_crm_date']]; // Start Date CRM
		$a_param["Value22"] = $a_noti[$config[$method]["send"]['field_crm_time']]; // Start Time CRM

		$method = "SetNotificationPersonal";

		$url = $config["url"] . $method;

		$this->load->library('curl');
		$this->common->_filename = "Insert_Notification";
		$this->common->set_log($url . "_Begin", $a_param, array());
		$s_result = $this->curl->simple_post($url, $a_param, array(), "json");
		$a_response = json_decode($s_result, true);
		$notificationid = $a_response["Value3"];

		$a_update["crmid"] = $crmid;
		$a_update["notificationid"] = $notificationid;
		$sql = $this->db->insert_string('aicrm_crmentity_notification', $a_update) . " ON DUPLICATE KEY UPDATE notificationid='" . $notificationid . "'";
		$this->db->query($sql);

		$this->common->set_log($url . " update data ", $this->db->last_query(), "");

		$this->common->set_log($url . "_End", $a_param, $s_result);
		$this->common->_filename = "Insert_Calendar";
		return $s_result;
	}

	public function get_message($data = array())
	{

		$message = 'วันที่ : ' . date("d-m-Y", strtotime($data['jobdate'])) . '\nเวลา : ' . $data['notification_time'] . '\nJob Name : ' . $data['job_name'] . '\nCustomer Name : ' . $data['accountname'];
		return $message;
	}

	public function get_userorgroup($smownerid = "")
	{

		$sql_userlist = "select aicrm_users.id  as userid
	      FROM aicrm_groups
	      INNER join aicrm_crmentity on aicrm_groups.groupid = aicrm_crmentity.smownerid
		  INNER JOIN aicrm_users2group on aicrm_users2group.groupid =  aicrm_groups.groupid
		  LEFT JOIN aicrm_users on aicrm_users.id =  aicrm_users2group.userid
	      WHERE aicrm_groups.groupid = '" . $smownerid . "' and aicrm_users.deleted=0 group by aicrm_users2group.userid";

		$query = $this->db->query($sql_userlist);
		$a_data =  $query->result_array();

		if (empty($a_data)) {
			$data = $smownerid;
		} else {
			$data = $a_data;
			foreach ($data as $key => $value) {
				$data_value = implode(",", $value);
				$data_3 .= $data_value . ",";
				$test =  rtrim($data_3, ",");
				$data = $test;
			}
		}

		return $data;
	}

	// public function get_job($crmid="",$a_cofig=array())
	//   {
	// 	$sql = " select aicrm_jobs.*,aicrm_jobscf.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid ,aicrm_account.accountname
	// 			from aicrm_jobs
	// 			inner join aicrm_jobscf on aicrm_jobscf.jobid = aicrm_jobs.jobid
	// 			left join aicrm_account on aicrm_account.accountid = aicrm_jobs.accountid
	// 			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_jobs.jobid
	// 			left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid";
	// 	$sql .= " where aicrm_crmentity.deleted = 0	";
	// 	if (isset($crmid) && $crmid!="") {
	// 		$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
	// 	}

	// 	$query = $this->db->query($sql);

	// 	if(!$query){
	// 		$a_return["status"] = false;
	// 		$a_return["message"] = $this->ci->db->_error_message();
	// 		$a_return["result"] = "";
	// 	}else{
	// 		$a_data =  $query->result_array() ;
	// 		if (!empty($a_data)) {
	// 			$a_return["status"] = true;
	// 			$a_return["message"] =  "";
	// 			$a_return["result"] = $a_data;
	// 		}else{
	// 			$a_return["status"] = false;
	// 			$a_return["message"] =  "No Data";
	// 			$a_return["result"] = "";
	// 		}
	// 	}
	// 	return $a_return;
	// }

	private function get_send($a_config = array(), $a_data = array())
	{
		if (empty($a_config)) {
			$a_return["status"] = false;
			$a_return["message"] = "Send Time is null";
			return array_merge($this->_return, $a_return);
		}

		$send_mode = $a_config["send_mode"];
		$send_time = @$a_config["send_time"];
		$field_crm_date = @$a_config["field_crm_date"];
		$field_crm_time = @$a_config["field_crm_time"];

		if ($send_mode == "save") {
			$a_response["senddate"] = date("Y-m-d");
			$a_response["sendtime"] = date("H:i", strtotime("+10 minutes"));
		} else if ($send_mode == "batch") {
			$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
			if ($send_time != "") {
				$a_response["sendtime"] = str_replace(".", ":", $send_time);
			} else {
				$a_response["sendtime"] = date("H:i");
			}
		} else if ($send_mode == "fieldsend") {
			$s_senddate = @$a_data[$field_crm_date];
			if ($s_senddate != "") {
				$a_response["senddate"] = $s_senddate;
			} else {
				$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
			}

			$s_sendtime = @$a_data[$field_crm_time];
			if ($s_sendtime != "") {
				$a_response["sendtime"] = str_replace(".", ":", $s_sendtime);
			} else {
				$a_response["sendtime"] = date("H:i");
			}
		}
		$a_return["status"] = true;
		$a_return["message"] =  "";
		$a_return["result"] = $a_response;
		return $a_return;
	}

	public function relate_product_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body, true);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename = "Save_Related_Product";
		$this->common->set_log($url, $a_param, "");
		
		$data = $this->job_model->check_products($a_param);


		if ($data['status'] == true) {
			$a_return["Type"] = "S";
			$a_return["Message"] = "Insert Success";
		} else {
			$a_return["Type"] = "E";
			$a_return["Message"] = !empty($data['error']) ? $data['error'] : "ลองอีกครั้ง";
		}

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename = "Save_Related_Product";
		$this->common->set_log($url, $a_param, $a_return);

		if ($a_return) {
			$this->response($a_return, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}

	public function get_job_post()
	{

		$this->common->_filename = "Detail_Job";
		header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body, true);
		$response_data = null;
		$a_request = $dataJson;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->common->set_log('Before Detail Job==>', $url, $a_request);

		$response_data = $this->job_model->get_job($a_request);
		// alert($response_data);exit;

		$this->common->set_log('After Detail Job==>', $a_request, $response_data);

		if ($response_data) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}
}
