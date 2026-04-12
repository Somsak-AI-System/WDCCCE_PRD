<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
*  # Class Parcel
*   ## Webservice Module Parcel พัสดุ
*/
class Calendar extends REST_Controller
{
	private $crmid;
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("calendar_model");
		$this->_limit = 10;
		$this->_module = "Calendar";
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
				'DocNo' => null
			),
		);
	}

	public function insert_content_post(){

		$this->common->_filename= "Insert_Calendar";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Calendar ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Calendar ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}

	}

	function get_returnlocation($crmid=""){
		$sql ="select *
		FROM aicrm_activity
		LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
		LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activitycf.accountid
		WHERE aicrm_crmentity.deleted = 0
		AND crmid = ".$crmid ."
		AND aicrm_activitycf.location ='' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();

		if(count($data)>0){
			return "E";
		}else{
			return "S";
		}
	}

	private function get_insert_data($a_request){

		$response_data = null;
		$module=isset($a_request['module']) ? $a_request['module'] : "";
		$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$action=isset($a_request['action']) ? $a_request['action'] : "";
		$data=isset($a_request['data']) ? $a_request['data'] : "";
		$ischeckin = isset($a_request['ischeckin']) ? $a_request['ischeckin'] : "";
		// $DocNo=isset($a_request['activityid']) ? $a_request['activityid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";

		if($ischeckin=="location"){
			$data[0]["cf_4009"]=date('Y-m-d H:i:s');
		}else if($ischeckin=="location_chkout"){
			$chk_return = $this->get_returnlocation($a_request['crmid']);
			if($chk_return=="E"){
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Please check in before check out.',
				);
				return array_merge($this->_return,$a_return);
			}else{
				$data[0]["cf_4362"]=date('Y-m-d H:i:s');
			}
		}

		$this->load->config('config_module');

		$config = $this->config->item('module');
		$configModule = @$config[$module];
		$tab_name = @$configModule['tab_name'];
		$tab_name_index = @$configModule['tab_name_index'];

		if($module=="Sales Visit" || $module=="Events" || $module=="SalesVisit"  || $module=="Calendar" ){

			$configModule = @$config['Calendar'];
			$tab_name = @$configModule['tab_name'];
			$tab_name_index = @$configModule['tab_name_index'];

			$module = "Events";
			$smownerid =  isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
			$data[0]["smownerid"] = @$smownerid;

		}else if($module = "Job"){

			$smownerid =  isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
			$serialid =  isset($a_request['data'][0]['serialid']) && $a_request['data'][0]['serialid']!="" ? $a_request['data'][0]['serialid'] : "0";
			$product_id =  isset($a_request['data'][0]['product_id']) && $a_request['data'][0]['product_id']!="" ? $a_request['data'][0]['product_id'] : "0";
			$ticketid =  isset($a_request['data'][0]['ticketid']) && $a_request['data'][0]['ticketid']!="" ? $a_request['data'][0]['ticketid'] : "0";
			$data[0]["smownerid"] = @$smownerid;
			$data[0]["serialid"] = @$serialid;
			$data[0]["product_id"] = @$product_id;
			$data[0]["ticketid"] = @$ticketid;

		}

		if(count($data[0])>0 and $module!=""){

			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$tab_name,$tab_name_index,$data);


			if($chk=="0"){

				if($module=="Events"){
					$this->set_notification($data,$crmid,$smownerid);
					$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
					$a_return["data"] =array(
						'Crmid' => $crmid,

					);
				}else{
					$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
					$a_return["data"] =array(
						'Crmid' => $crmid,

					);
				}
			}else{
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Unable to complete transaction',
				);
			}
		}else{
			$a_return  =  array(
				'Type' => 'E',
				'Message' =>  'Invalid Request!',
			);
		}
		return array_merge($this->_return,$a_return);
	}

	public function send_notification_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$crmid = isset($a_param['crmid']) ? $a_param['crmid'] : "";
		$smownerid = isset($a_param['smownerid']) ? $a_param['smownerid'] : "";
		$userid = isset($a_param['userid']) ? $a_param['userid'] : "";
		$a_data =  isset($a_param['data']) && $a_param['data']!="" ? $a_param['data']: "";

		$a_data =$this->set_notification($a_data,$crmid,$smownerid,$userid);
		$this->common->_filename= "Set_Calendar_Notification";
  		$this->common->set_log($url,$a_param,$a_data);
		$this->return_data($a_data);
	}

	public function set_notification($a_data = array(),$crmid="",$smownerid="",$userid="")
	{
		if(empty($a_data)) return "";
		if($crmid=="") return "";
		if($smownerid=="") return "";
		
		$this->load->config('config_notification');
		$config = $this->config->item('notification');
		
		$method = 'calendar';
		$a_condition["aicrm_activity.activityid"] = $crmid;
		$a_result = $this->calendar_model->get_notification($a_condition);
		$a_activity = @$a_result["result"]["data"][0];
		
		$queryfunction = @$config[$method]["queryfunction"];
		
		//get data notification
		if (method_exists($this, $queryfunction))
		{	
			$a_data_return_noti = $this->{$queryfunction}($crmid,$config[$method]);
			if($a_data_return_noti["status"]===false || empty($a_data_return_noti["result"])){
				return array_merge($this->_return,$a_data_return_noti);
				exit();
			}
			$a_noti = $a_data_return_noti["result"][0];
			//alert($a_noti); exit;
		}else{
			$a_data["status"] = false;
			$a_data["message"] = "Method Query is null";
			return array_merge($this->_return,$a_data);
		}                            
		
		//Get Time Send Notification
		$a_send = $this->get_send($config[$method]["send"],$a_noti);

		//$assingto = $this->get_userorgroup($smownerid);

		if($a_send["status"]===false || empty($a_send["result"])){
			return array_merge($this->_return,$a_send);
			exit();
		}
		
		$a_send_data = $a_send["result"];
		$notificationid = ($a_noti["notificationid"]==0 || $a_noti["notificationid"]=="") ?"": $a_noti["notificationid"];

		$msg = $this->get_message($a_noti);
		// Get field start date
		$field_startdate = $config[$method]["startdate"];
		$field_starttime = @$config[$method]["starttime"];
		$startdate_date = @$a_noti[$field_startdate];
		$startdate_time = @$a_noti[$field_starttime];
		
		$startdate = $startdate_date .( $startdate_time != "" ? " ".$startdate_time:"");
		
		// Get field End date
		$field_enddate = $config[$method]["enddate"];
		$field_endtime = @$config[$method]["endtime"];
		$enddate_date = @$a_noti[$field_enddate];
		$enddate_time = @$a_noti[$field_endtime];
		
		$enddate = $enddate_date . ($enddate_time != "" ? " ".$enddate_time:"");
		
		if($startdate!="" && $enddate=="" ){
			$enddate =  date("Y-m-d H:i:s", strtotime(($startdate) . "+1 hour"));
		}
		
		$a_param["Value1"] = $notificationid;
		$a_param["Value2"] = $crmid;
		$a_param["Value3"] = "Calendar";
		$a_param["Value4"] = "";//send_total
		$a_param["Value5"] = "";//send_success;
		$a_param["Value6"] = "";//send_unsuccess*/
		$a_param["Value7"] = date('Y-m-d');
		$a_param["Value8"] = date('H:i', strtotime($a_activity["time_start"].'+1 minutes'));//send_time
		$a_param["Value9"] = $msg;//send_message
		$a_param["Value10"] = "1";//noti_type
		$a_param["Value11"] = "";//result_filename
		$a_param["Value12"] = "";//result_status
		$a_param["Value13"] = "";//result_errorcode
		$a_param["Value14"] = "";//result_errormsg
		$a_param["Value15"] = $smownerid;//userid
		$a_param["Value16"] = $userid;//empcd
		$a_param["Value17"] = "";//noti_status
		$a_param["Value18"] = $config["projectcode"];//project_code
		$a_param["Value19"] = $startdate;//
		$a_param["Value20"] = $enddate;//
		
		$a_param["Value21"] = $a_noti[$config[$method]["send"]['field_crm_date']];// Start Date CRM
		$a_param["Value22"] = $a_noti[$config[$method]["send"]['field_crm_time']];// Start Time CRM
		
		$method = "SetNotificationPersonal";

		//$url = $config["url"].$method;
		$url = $config["url"];
		
		//echo $url; 
		//echo json_encode($a_param); exit;
		$this->load->library('curl');
		$this->common->_filename= "Insert_Notification";
		$this->common->set_log($url."_Begin",$a_param,array());
		$s_result = $this->curl->simple_post($url, $a_param,array(),"json");
		$a_response = json_decode($s_result,true);
		$notificationid = $a_response["Value3"];
		
		$a_update["crmid"] = $crmid;
		$a_update["notificationid"] = $notificationid;
		$sql = $this->db->insert_string('aicrm_crmentity_notification', $a_update) . " ON DUPLICATE KEY UPDATE notificationid='".$notificationid."'";
		$this->db->query($sql);

		$this->common->set_log($url." update data ",$this->db->last_query(),"");
		
		$this->common->set_log($url."_End",$a_param,$s_result);
		$this->common->_filename= "Insert_Calendar";
		return $s_result;
	}

	public function get_message( $data=array() ){
		
		$message = 'วันที่ : '.date("d-m-Y", strtotime($data['date_start'])).'\nเวลา : '.$data['time_start'].'\nObj : '.$data['activitytype'].'\nCustomer Name : '.$data['accountname'];
		return $message;
	 }
	
	public function get_userorgroup($smownerid = ""){
		
		$sql_userlist = "select aicrm_users.id  as userid
	      FROM aicrm_groups
	      INNER join aicrm_crmentity on aicrm_groups.groupid = aicrm_crmentity.smownerid
		  INNER JOIN aicrm_users2group on aicrm_users2group.groupid =  aicrm_groups.groupid
		  LEFT JOIN aicrm_users on aicrm_users.id =  aicrm_users2group.userid
	      WHERE aicrm_groups.groupid = '".$smownerid."' and aicrm_users.deleted=0 group by aicrm_users2group.userid";

		$query = $this->db->query($sql_userlist);
		$a_data =  $query->result_array() ;

		if(empty($a_data)){
			$data = $smownerid;
		}else {
			$data = $a_data;
			foreach ($data as $key => $value) {
				$data_value = implode( ",", $value );
				$data_3 .= $data_value.",";
				$test =  rtrim($data_3,",");
				$data = $test;
			}
		}

		return $data;	
	}
	public function get_calendar($crmid="",$a_cofig=array())
	  {
		$sql = " select aicrm_activity.*,aicrm_activitycf.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid ,

				case when ifnull(aicrm_account.accountid,'') != '' then  aicrm_account.accountname
					 when ifnull(aicrm_leaddetails.leadid,'') != '' then  concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) 
					 end as accountname
				from aicrm_activity
				inner join aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
				left join aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
				left join aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid 
				inner join aicrm_crmentity on aicrm_activity.activityid = aicrm_crmentity.crmid	
				left join aicrm_crmentity_notification on aicrm_crmentity.crmid =  aicrm_crmentity_notification.crmid";
		
		$sql .= " where aicrm_crmentity.deleted = 0	";
		if (isset($crmid) && $crmid!="") {
			$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
		}
		//echo $sql;
		$query = $this->db->query($sql);
		//alert($query); exit;
		if(!$query){
			$a_return["status"] = false;
			$a_return["message"] = $this->ci->db->_error_message();
			$a_return["result"] = "";
		}else{
			$a_data =  $query->result_array() ;
			if (!empty($a_data)) {
				$a_return["status"] = true;
				$a_return["message"] =  "";
				$a_return["result"] = $a_data;
			}else{
				$a_return["status"] = false;
				$a_return["message"] =  "No Data";
				$a_return["result"] = "";
			}
		}
		return $a_return;
	}

	private function get_send($a_config=array(),$a_data=array())
	  	{
  		if(empty($a_config)){
  			$a_return["status"] = false;
  			$a_return["message"] = "Send Time is null";
  			return array_merge($this->_return,$a_return);
  		}
  		
  		$send_mode = $a_config["send_mode"];
  		$send_time = @$a_config["send_time"];
  		$field_crm_date = @$a_config["field_crm_date"];
  		$field_crm_time = @$a_config["field_crm_time"];
		
  		if($send_mode=="save")
  		{
  			$a_response["senddate"] = date("Y-m-d");
  			$a_response["sendtime"] = date("H:i", strtotime("+10 minutes"));
  		}
  		else if($send_mode=="batch")
  		{
  			$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
  			if($send_time!=""){
  				$a_response["sendtime"] = str_replace(".", ":", $send_time);
  			}else{
  				$a_response["sendtime"] = date("H:i");
  			}  			
  		}
  		else if($send_mode=="fieldsend")
  		{
  			$s_senddate = @$a_data[$field_crm_date];
  			if($s_senddate!=""){
  				$a_response["senddate"] = $s_senddate;
  			}else{
  				$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
  			}
  			
  			$s_sendtime = @$a_data[$field_crm_time];
  			if($s_sendtime!=""){
  				$a_response["sendtime"] = str_replace(".", ":", $s_sendtime);
  			}else{
  				$a_response["sendtime"] = date("H:i");
  			}
  		}
  		$a_return["status"] = true;
  		$a_return["message"] =  "";
  		$a_return["result"] = $a_response;
  		return $a_return;
  		
  	}

	public function delete_item_post(){

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		$a_request =$dataJson;

		$a_return["Message"] =  "Update Complete";
		$crmid = $a_request['data']["crmid"] ;
		$userid = $a_request['data']["smownerid"] ;


	$a_return["data"] = array();
	$data = array();

	if($crmid == ""){

		$data = array(
			'crmid' => $crmid,
			'modifiedby' => $userid,
			'modifiedtime' =>date("Y-m-d H:i:s"),
			'deleted' => "0"
		);
		$deleted = "0";
		$modifiedby = $userid;
		$modifiedtime =  date("Y-m-d H:i:s");
		$a_return["Type"] = "E";
		$a_return["Message"] = "Update Fail";

	}else{
		foreach ($crmid as $key => $value) {
			$data = array(
				'crmid' => $value,
				'modifiedby' => $userid,
				'modifiedtime' =>date("Y-m-d H:i:s"),
				'deleted' => "1"
			);
			$deleted = "1";
			$modifiedby = $userid;
			$modifiedtime =  date("Y-m-d H:i:s");
			$a_return["data"][] =$data;

			$sql = "UPDATE aicrm_crmentity SET deleted='".$deleted."', modifiedby='".$modifiedby."' ,modifiedtime='".$modifiedtime."'
			where crmid='".$value."'";
			$query = $this->db->query($sql);

		}
	
	}
	$response_data = array_merge($this->_return,$a_return);

	if ( $response_data ) {
		$this->response($response_data, 200); // 200 being the HTTP response code
	} else {
		$this->response(array(
			'error' => 'Couldn\'t find Set Content!'
		), 404);
	}
}

private function set_param($a_param=array())
{
	$a_condition = array();
	if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
		$a_condition["aicrm_activity.activityid"] =  $a_param["crmid"] ;
	}

	if (isset($a_param["contactid"]) && $a_param["contactid"]!="") {
		$a_condition["aicrm_activitycf.contactid"] =  $a_param["contactid"] ;
	}

	if (isset($a_param["accountid"]) && $a_param["accountid"]!="") {
		$a_condition["aicrm_activitycf.accountid"] =  $a_param["accountid"] ;
	}


	if (isset($a_param["userid"]) && $a_param["userid"]!="") {
		$userid = array();
		$userid = $this->common->get_user2role($a_param["userid"]);
		$this->db->where("aicrm_crmentity.smownerid in (".$userid.")");
	}

	if ((isset($a_param["date_start"]) && $a_param["date_start"]!="")  && (isset($a_param["due_date"]) && $a_param["due_date"]!="")) {
		$date_start = $a_param['date_start'];
		$due_date = $a_param['due_date'];
		$this->db->where('aicrm_activity.date_start >=', $date_start);
		$this->db->where('aicrm_activity.due_date <=', $due_date);
	}

	return $a_condition;
}
private function set_order($a_orderby=array())
{
	if(empty($a_orderby)){
		$a_order[0]["field"] = "aicrm_users.user_name";
		$a_order[0]["order"] = "";
		$a_order[1]["field"] = "aicrm_activity.date_start";
		$a_order[1]["order"] = "";
		$a_order[2]["field"] = "aicrm_activity.time_start";
		$a_order[2]["order"] = "ASC";
		return $a_order;
	}
	if($a_orderby == 'aicrm_activity.activityid,asc'){
		$a_order[0]["field"] = "aicrm_users.user_name";
		$a_order[0]["order"] = "";
		$a_order[1]["field"] = "aicrm_activity.date_start";
		$a_order[1]["order"] = "";
		$a_order[2]["field"] = "aicrm_activity.time_start";
		$a_order[2]["order"] = "ASC";
		return $a_order;
	}

	$a_order = array();
	$a_condition = explode( "|",$a_orderby);

	for ($i =0;$i<count($a_condition) ;$i++)
	{
		list($field,$order) = explode(",", $a_condition[$i]);
		$a_order[$i]["field"] = $field;
		$a_order[$i]["order"] = $order;
	}


	return $a_order;
}
private function get_cache($a_params=array())
{
	$this->load->library('managecached_library');

	$a_cache = array();
	$a_cache["_ctag"] =  $this->_module.'/';
	$a_cache["_ckname"] =$this->_module.'/get_content';

	$a_condition = array();
	$a_condition = $this->set_param($a_params);

	$optimize = @$a_params['optimize'];

	$userid = @$a_params['userid'];

	$limit = @$a_params["limit"];
	$offset = @$a_params["offset"];
	$order= @$a_params["orderby"];
	$a_order = $this->set_order($order);


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
		// echo $queryhistory; exit;
		$this->db->query($queryhistory);

	}
	if($a_data===false)
	{

		$a_list=$this->calendar_model->get_list($a_condition,$a_order,$a_limit,$optimize,$a_params);
		$a_data = $this->get_data($a_list,$optimize);

		// alert($a_data);exit();
		$a_data["data"] = $a_data["result"];
		$a_data["limit"] = $a_limit["limit"]  ;
		$a_data["offset"] = $a_limit["offset"]  ;
		$a_data["time"] = date("Y-m-d H:i:s");
		$a_cache["data"] = $a_data["result"];
		$a_cache["data"]["time"] = date("Y-m-d H:i:s");
		$this->managecached_library->set_memcache($a_cache,"2400");
	}
	return $a_data;
}

private function get_data($a_data,$optimize='')
{ 
	if($optimize == '' ){
		if(!empty($a_data["result"]["data"]) && $a_data["status"] ){

			foreach ($a_data["result"]["data"] as $key =>$val){

				$activityid = $val["activityid"];
				$a_activity[] = $activityid;

			}

			$a_conditionin["aicrm_activity.activityid"] = $a_activity;
			$a_image = $this->common->get_a_image($a_conditionin,$this->_module);

			foreach ($a_data["result"]["data"] as $key =>$val){
				$activityid = $val["activityid"];
				$a_return = $val;
				$a_return["image"] =( !empty($a_image[$activityid]["image"]))?$a_image[$activityid]["image"] :array();


		$a_response[] = $a_return;

	}
	$a_data["result"]["data"] = $a_response;
}
}

return $a_data;
}
/**
*  ## List Content :: Get All Content
*    | Field                        | Description                                                                                                                                                                |
*    | ------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
*    | Description            | Get All Content on Parcel                                                                                                                                     |
*    | URL                          | http://localhost/spcp/WB_Service_AI/Calendar/list_content?AI-API-KEY=1234&module=Calendar  |
*    | Method Type        | Get :: Post                                                                                                                                                                   |
*
*
*  ## Request Parameter
*  | Name              | Type                 | Description                                                                  					 |  Value  	  | Default Value        | Mandatory	 |
*  | ------------------- | -------------------   | -------------------------------------------------------------------------------- | -----------	  |-------------------------- |------------------	 |
*  | format              | String                 | Expected response format                    					                 | json/xml	  | json                          | No          	     |
*  | offset                | Int      	          	    | Start index result set                                  					        	     |           	      | 0                                | No       	         |
*  | limit                   | Int    	 	                | Number of item in result  0 = unlimit      						             |            	      | 10                              | No       	         |
*  | AI-API-KEY    | Int     	                | Secret key                                                     						         |           	      |                                    | Yes     	         |
*  | orderby           | String                 | Order by ascending or descending  									 |          	      |                      			   | No      	         |
*  | crmid		         | Int        			        | parcelid																							 |          	      |                      			   | No      	         |
*  | productid        | Int			                | productid																						 |          	      |                      			   | No      	         |
*  | contactid		 | Int   			            | contactid																						 |          	      |                      			   | No      	         |
*  | accountid       | Int			                | accountid																						 |          	      |                      			   | No      	         |
*  | buildingid       | Int			                | buildingid																						 |          	      |                      			   | No      	         |
*
*
*  ## Return Result
*  | Name              | Type                 | Description                                                                  					 |  Value  	 																 |
*  | ------------------- | -------------------   | -------------------------------------------------------------------------------- | ----------------------------------------------------------	 |
*  | Type              	 | String                 | Return status							                    					                 | S/Success :: E/Error								       	     |
*  | Message       | String                 | Error Message				                                  					             |     																      	         |
*  | Total                | INT                     | Result  Total Data								    						             |   																      	         |
*  | offset				 | INT                     | Start index result set                                   						             |           	   													     	         |
*  | limit			         | INT		               | Number of item in result  0 = unlimit 	  									 |  																	   	         |
*	 | data		         | Arra		               | Result Data 									 |  																	   	         |
*
* ## Example Parameter
* URL :: http://localhost/spcp/WB_Service_AI/Calendar/list_content?AI-API-KEY=1234&module=Calendar&offset:0&limit=0&orderby=aicrm_activity.activityid,asc
*
*  ## Example Return Success
*  ~~~~~~~~~~~~~{.py}
* {
*  Type: "S",
*  Message: "",
*  total: "1",
*  offset: 0,
*  limit: "0",
*  cachetime: "2015-12-18 12:46:24",
*  data: [
*      {
*       	parcelid: "1810582",
*			parcel_no: "PRC1",
*			parcel_name: "test",
*			accountid: "1098407",
*			cf_2678: "22",
*			cf_2679: "22",
*			cf_2680: "112",
*			cf_2681: "4",
*			cf_2682: "2015-12-01",
*			cf_2683: "กำลังดำเนินการ",
*			crmid: "1810582",
*			smcreatorid: "11057",
*			smownerid: "11057",
*			modifiedby: "0",
*			setype: "Parcel",
*			description: "est",
*			createdtime: "2015-12-17 12:12:07",
*			modifiedtime: "2015-12-17 12:12:07",
*			viewedtime: "2015-12-17 12:12:10",
*			status: null,
*			version: "0",
*			presence: "1",
*			deleted: "0"
*		}
*	]
*}
*~~~~~~~~~~~~~
*/

public function list_content_get()
{
	$a_param =  $this->input->get();
	$a_data =$this->get_cache($a_param);
	$this->return_data($a_data);
}

public function list_content_post()
{
	$request_body = file_get_contents('php://input');
	$a_param     = json_decode($request_body,true);
	
	$a_data =$this->get_cache($a_param);
	$this->return_data($a_data);
}

public function return_data($a_data)
{
	if($a_data)
	{
		$format =  $this->input->get("format",true);
		$a_return["Type"] = ($a_data["status"])?"S":"E";
		$a_return["Message"] =$a_data["error"];
		$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"] : 0;
		$a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
		$a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
		$a_return["cachetime"] = $a_data["time"];
		$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;

		if ($format!="json" && $format!="xml"  ) {
			$this->response($a_return, 200); // 200 being the HTTP response code

		}else{

			$this->response($a_return, 200); // 200 being the HTTP response code
		}
	}
	else
	{
		$this->response(array('error' => 'Couldn\'t find any Parcel!'), 404);

	}

}


public function upload_image_post(){

	$request_body = file_get_contents('php://input');
	$dataJson     = json_decode($request_body,true);
	$a_request =$dataJson['data'][0];

	$a_return["Message"] =  "Upload Complete";

	$file_maximumsize = 1048576; // Maximum size is 1MB
	$path 		= "storage/".date("Y")."/".date("F")."/week".date("W")."";
	$url = "http://".$_SERVER['HTTP_HOST']."/medt/".$path;

	global $root_directory;

	$folder = $root_directory;
	$temp_path 	= $folder."/".$path;

	if (!is_dir($temp_path)) {

		$folder2 = $folder.'/storage';

		// Find each subfolder in storage's image
		$storage_folder = explode('storage/' , $temp_path);
		$new_folder = explode('/' , $storage_folder[1]);

		for($i=0; $i<=count($new_folder)-2; $i++){

			//Create subfolder if it not found folder
			$folder2 = $folder2."/".$new_folder[$i];

			if (!is_dir($folder2)) {
				$old = umask(0);
				mkdir($folder2 ."/" ,0777,true);
				umask($old);

			}
		}

		$old = umask(0);
		mkdir($temp_path ."/" ,0777,true);
		umask($old);
	}//if check folder

	$a_return["data"] = array();
	$data = array();

	for($i=0; $i<count($a_request['image']); $i++){

		//Get Crmid
		$sql_next_id = "SELECT max(id)+1 as next_id FROM aicrm_crmentity_seq";
		$query_next_id = $this->db->query($sql_next_id);
		$result_next_id = $query_next_id->result(0);
		$next_id = $result_next_id[0]['next_id'];

		$sql_insert_new1 = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$a_request['smownerid']."', '".$a_request['smownerid']."', 'Calendar Image', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		$sql_insert_new2 = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$a_request['crmid']."' , '".$next_id."')";
		$sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";

		$this->db->query($sql_insert_new1);
		$this->db->query($sql_insert_new2);
		$this->db->query($sql_update);

		//Insert Image
		$file_type = "image/jpeg";
		$image_name = $next_id."_Calendar.jpg";
		$binary = base64_decode($a_request['image'][$i]);
		header("Content-Type: bitmap; charset=utf-8");
		$file = "../".$path."/" . $image_name;
		$image_upload = fopen($file, "w");
		$filepath = $image_name;
		fwrite($image_upload, $binary);
		fclose($image_upload);
		chmod($file, 0777);

		$sql2 = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('".$next_id."' , '".$image_name."', '".$file_type."', '".$path."/')";
		$this->db->query($sql2);


		$data = array(
			'crmid' => $next_id,
			'modifiedby' => $a_request["smownerid"],
			'modifiedtime' => date("Y-m-d H:i:s"),
		);

		array_push($a_return["data"] , $data);

	}// for

	$response_data = array_merge($this->_return,$a_return);
	if ( $response_data ) {
		$this->response($response_data, 200); // 200 being the HTTP response code
	} else {
		$this->response(array(
			'error' => 'Couldn\'t find Set Content!'
		), 404);
	}

}



	public function get_visit_post(){

		$this->common->_filename= "Detail_Calendar";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Detail Calendar ==>',$url,$a_request);

		//$response_data = $this->calendar_model->get_calendar($a_request);
		$a_data = $this->calendar_model->get_calendar($a_request);
		//alert($a_data);exit;

	  	//$this->common->set_log('After Detail Calendar ==>',$a_request,$response_data);
	  	$this->common->set_log('After Detail Calendar ==>',$a_request,$a_data);

	  	$a_data["data"] = $a_data["result"];
		$a_data["limit"] = $a_limit["limit"]  ;
		$a_data["offset"] = $a_limit["offset"]  ;
		$a_data["time"] = date("Y-m-d H:i:s");
		$a_cache["data"] = $a_data["result"];
		$a_cache["data"]["time"] = date("Y-m-d H:i:s");
		//$this->managecached_library->set_memcache($a_cache,"2400");
	  	//$this->return_data($a_data);
	  	//$a_data =$this->get_cache($a_param);
		$this->return_data($a_data);
	  	/*if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}*/
	}




}
