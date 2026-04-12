<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Announcement extends REST_Controller
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
		$this->load->model("announcement_model");
		$this->_module = 'Announcement';
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

	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_announcement.announcementid"] =  $a_param["crmid"] ;
		}
		return $a_condition;
	}

	private function set_order($a_orderby=array())
	{
		if(empty($a_orderby)) return false;
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

	public function list_content_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data);
	}
	
	public function list_content_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);
		$a_data = $this->get_cache($a_param);
		$this->return_data($a_data);
	}
	
	public function list_detail_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);
		//alert($a_param); exit;
		$a_data = $this->get_detail($a_param);
		$this->return_data($a_data);
	}

	private function get_detail($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("announcement_model");
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$a_condition = array();
		$a_condition = $this->set_param($a_params);
		
		$crmid = @$a_params["crmid"];
		$userid = @$a_params["userid"];

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
		
		if($a_data===false)
		{
			$a_list = $this->announcement_model->get_detail($a_condition,$a_order,$a_limit,$a_params);
			$a_data = $this->get_data_detail($a_list);

			if($a_params['flagread'] == 0 ){
				
				$q_update = "update ai_notification set flagread =1 , readdate = '".date('Y-m-d H:i:s')."' where crmid = ".$crmid." and userid = ".$userid." ";
				$this->db->query($q_update);
			}

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
	
	public function notification_list_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);
		$a_data = $this->get_list_noti($a_param);
		$this->return_data($a_data);
	}

	private function get_list_noti($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("announcement_model");
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$a_condition = array();
		$a_condition = $this->set_param($a_params);
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
		
		if($a_data===false)
		{
			$a_list = $this->announcement_model->get_noti_list($a_condition,$a_order,$a_limit,$a_params);
			$a_data = $this->get_data($a_list);
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

  	public function return_data($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"]:0;
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
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

	public function send_notification_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);

		$crmid = isset($a_param['crmid']) ? $a_param['crmid'] : "";
		$smownerid = isset($a_param['smownerid']) ? $a_param['smownerid'] : "";
		$userid = isset($a_param['userid']) ? $a_param['userid'] : "";
		$a_data =  isset($a_param['data']) && $a_param['data']!="" ? $a_param['data']: "";
		
		$a_data = $this->set_notification($a_data,$crmid,$smownerid,$userid);

		$this->common->_filename= "Set_Announcement_Notification";
  		$this->common->set_log($url,$a_param,$a_data);
		$this->return_data($a_data);
	}

	public function get_message( $msg='',$senddate='',$sendtime=''){
		$message = 'วันที่ประกาศ : '.date("d-m-Y", strtotime($senddate)).'\nเวลาประกาศ : '.$sendtime.'\nเรื่อง : '.$msg ;
		return $message;
	}

	public function set_notification($a_data = array(),$crmid="",$smownerid="",$userid="")
	{
		if(empty($a_data)) return "";
		if($crmid=="") return "";
		if($smownerid=="") return "";
		
		$this->load->config('config_notification');
		$config = $this->config->item('notification');

		$msg = $this->get_message($a_data['send_message'],$a_data['send_date'],$a_data['send_time']);

		//alert($a_data); exit;
		$a_param["Value1"] = '';
		$a_param["Value2"] = $crmid;
		$a_param["Value3"] = "Announcement";
		$a_param["Value4"] = "";//send_total
		$a_param["Value5"] = "";//send_success;
		$a_param["Value6"] = "";//send_unsuccess
		$a_param["Value7"] = date('Y-m-d');
		$a_param["Value8"] = date('H:i', strtotime(date('H:i').'+5 minutes')); //send_time
		$a_param["Value9"] = $msg;//send_message
		$a_param["Value10"] = "1";//noti_type
		$a_param["Value11"] = "";//result_filename
		$a_param["Value12"] = "";//result_status
		$a_param["Value13"] = "";//result_errorcode
		$a_param["Value14"] = "";//result_errormsg
		$a_param["Value15"] = $userid;//userid
		$a_param["Value16"] = $smownerid;//empcd
		$a_param["Value17"] = "";//noti_status
		$a_param["Value18"] = $config["projectcode"];//project_code
		$a_param["Value19"] = $startdate;//
		$a_param["Value20"] = $enddate;//
		$a_param["Value21"] = $a_data['date_start_crm'];// Start Date CRM
		$a_param["Value22"] = $a_data['time_start_crm'];// Start Time CRM
		
		$method = "SetNotificationPersonal";
		$url = $config["url"].$method;

		$this->load->library('curl');
		$this->common->_filename= "Insert_Notification";
		$this->common->set_log($url."_Begin",$a_param,array());
		$s_result = $this->curl->simple_post($url, $a_param,array(),"json");
		$a_response = json_decode($s_result,true);
		$notificationid = $a_response["Value3"];
		
		/**/
		$a_update["crmid"] = $crmid;
		$a_update["notificationid"] = $notificationid;
		$sql = $this->db->insert_string('aicrm_crmentity_notification', $a_update) . " ON DUPLICATE KEY UPDATE notificationid='".$notificationid."'";
		$this->db->query($sql);
		$this->common->set_log($url." update data ",$this->db->last_query(),"");
		$this->common->set_log($url."_End",$a_param,$s_result);

		/**/
		$a_update["crmid"] = $crmid;
		$a_update["notificationid"] = $notificationid;
		$a_update["userid"] = $userid;
		$a_update["module"] = 'Announcement';
		//$a_update["datesend"] = $a_data['send_date'];
		//$a_update["timesend"] = $a_data['send_time'];
		$a_update["datesend"] = date('Y-m-d');
		$a_update["timesend"] = date('H:i:s');
		$sql_tmp = $this->db->insert_string('ai_notification', $a_update) . " ON DUPLICATE KEY UPDATE notificationid='".$notificationid."'";
		$this->db->query($sql_tmp);

		return $s_result;
	}
	
	private function get_data($a_data)
	{
		if($a_data['result'] != ''){			
		}
		return $a_data;
	}

	private function get_data_detail($a_data)
	{
		if($a_data['result'] != ''){

			foreach ($a_data["result"]["data"] as $key =>$val){
				$announcementid = $val["announcementid"];
				$a_announcement[] = $announcementid;
			}

			$a_conditionin["aicrm_announcement.announcementid"] = $a_announcement;
			$a_image = $this->common->get_a_image($a_conditionin,$this->_module);

			foreach ($a_data["result"]["data"] as $key =>$val){
				$ticketid = $val["announcementid"];
				$a_return = $val;
				$a_return["image"] =( !empty($a_image[$ticketid]["image"]))?$a_image[$ticketid]["image"] :array();
				$a_response[] = $a_return;
			}
			$a_data["result"]["data"] = $a_response;
		}
		return $a_data;
	}

	public function accept_post(){
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->get_insert_accept($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Accept_Announcement";

	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_accept($a_request){
	  	
	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "";
	  	
  		if($module=="Announcement" && $crmid != "" && $userid != ""){
  			
  			$q_update = "update ai_notification set flagaccept =1 , acceptdate = '".date('Y-m-d H:i:s')."' where crmid = ".$crmid." and userid = ".$userid." ";
			$this->db->query($q_update);
  			
			$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
			$a_return["data"] =array(
				'Crmid' => $crmid,
				'DocNo' => $DocNo,
			);
	  			
	  	}else{
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	
	}


}
