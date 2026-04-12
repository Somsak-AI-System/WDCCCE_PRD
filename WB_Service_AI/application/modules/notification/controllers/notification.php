<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
 *  # Class Debitnote
 *   ## Webservice Module Debitnote ค่าส่วนกลาง
*/
class Notification extends REST_Controller
{
	private $crmid;
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	
	$this->_limit = 10;
	$this->_module = "Notification";
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
				'No' => null
		),
	);
  }
 
	public function set_notification_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->set_notification($a_param);
		$this->return_data($a_data);
	}

	public function send_notification_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$crmid = isset($a_param['crmid']) ? $a_param['crmid'] : "";
		$smownerid = isset($a_param['smownerid']) ? $a_param['smownerid'] : "";
		$userid = isset($a_param['userid']) ? $a_param['userid'] : "";
		$module = isset($a_param['module']) ? $a_param['module'] : "";
		$a_data =  isset($a_param['data']) && $a_param['data']!="" ? $a_param['data']: "";
		$a_data =$this->set_notification($a_data,$crmid,$smownerid,$userid,$module);
		$this->common->_filename= "Set_CRM_Notification";
  		$this->common->set_log($url,$a_param,$a_data);
		$this->return_data($a_data);
	}

	public function set_notification($a_data = array(),$crmid="",$smownerid="",$userid="",$module='')
	{
		if(empty($a_data)) return "";
		if($crmid=="") return "";
		if($smownerid=="") return "";
		//alert($a_data); exit;
		$this->load->config('config_notification');
		$config = $this->config->item('notification');
		
		$method = $module;
		/*$method = 'calendar';
		$a_condition["aicrm_activity.activityid"] = $crmid;
		$a_result = $this->calendar_model->get_notification($a_condition);
		$a_activity = @$a_result["result"]["data"][0];
		$queryfunction = @$config[$method]["queryfunction"];*/
		//get data notification
		/*if (method_exists($this, $queryfunction))
		{
			$a_data_return_noti = $this->{$queryfunction}($crmid,$config[$method]);
			if($a_data_return_noti["status"]===false || empty($a_data_return_noti["result"])){
				return array_merge($this->_return,$a_data_return_noti);
				exit();
			}
			$a_noti = $a_data_return_noti["result"][0];
		}else{
			$a_data["status"] = false;
			$a_data["message"] = "Method Query is null";
			return array_merge($this->_return,$a_data);
		} */                           
		
		//Get Time Send Notification
		/*alert($config[$method]); exit;
		$a_send = $this->get_send($config[$method]["send"],$a_noti);
		alert($a_send); exit;
		//$assingto = $this->get_userorgroup($smownerid);

		if($a_send["status"]===false || empty($a_send["result"])){
			return array_merge($this->_return,$a_send);
			exit();
		}
		
		$a_send_data = $a_send["result"];
		$notificationid = ($a_noti["notificationid"]==0 || $a_noti["notificationid"]=="") ?"": $a_noti["notificationid"];*/

		$msg = $this->get_message($a_data,$module);
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
		//echo 555;exit;

		$a_param["Value1"] = @$notificationid;
		$a_param["Value2"] = $crmid;
		$a_param["Value3"] = $module;
		$a_param["Value4"] = "";//send_total
		$a_param["Value5"] = "";//send_success;
		$a_param["Value6"] = "";//send_unsuccess*/
		$a_param["Value7"] = date('Y-m-d');
		$a_param["Value8"] = date('H:i', strtotime(date('H:i').'+5 minutes'));//send_time
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

		$url = $config["url"].$method;
		
		
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
	
	public function get_message($data=array(),$module='' ){
		
		$message ='';
		if($module == 'Accounts'){
			$message = 'หมายเลขลูกค้า : '.$data['account_no'].'\nชื่อลูกค้า  : '.$data['accountname'].'\nสาขา : '.$data['branch'];
		}else if($module == 'Leads'){
			$message = 'Lead No : '.$data['lead_no'].'\nชื่อลูกค้า  : '.$data['firstname'].' '.$data['lastname'].'\nเบอร์มือถือ : '.$data['mobile'];
		}else if($module == 'Deal'){
			
			$sql_acc = "select accountname from aicrm_account where accountid = '".$data['account_id']."' ";
			$query = $this->db->query($sql_acc);
			$acc = $query->result_array();

			$message = 'หมายเลขโอกาสในการขาย : '.$data['deal_no'].'\nชื่อลูกค้า  : '.$acc[0]['accountname'].'\nเบอร์มือถือ : '.$data['mobile'];
		}else if($module == 'Questionnaire'){
			$message = 'หมายเลขแบบสอบถาม : '.$data['questionnaire_no'].'\nแบบประเมินเครดิต  : '.$data['scoring'].'\nชื่อแบบสอบถาม : '.$data['questionnaire_name'];
		}
		//$message = 'วันที่ : '.date("d-m-Y", strtotime($data['date_start'])).'\nเวลา : '.$data['time_start'].'\nObj : '.$data['activitytype'].'\nCustomer Name : '.$data['accountname'];
		
		return $message;
	}

	public function return_data($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = (@$a_data["status"])?"S":"E";
			$a_return["Message"] =@$a_data["message"];
			$a_return["cachetime"] = @$a_data["time"];
			$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;
		//alert($a_return["data"]);
			if ($format!="json" && $format!="xml"  ) {
					$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
				$this->response(array('error' => 'Couldn\'t find any Debitnote!'), 404);
		}
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
	
}