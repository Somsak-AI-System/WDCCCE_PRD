<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
 * ### Class Social ๏ฟฝ๏ฟฝ๏ฟฝ๏ฟฝ๏ฟฝ๏ฟฝับ๏ฟฝึง API ๏ฟฝอง Social ๏ฟฝ๏ฟฝาง ๏ฟฝ
 */
class Sms extends REST_Controller
{
  /**
   * crmid ๏ฟฝ๏ฟฝ๏ฟฝ crmid ๏ฟฝ aicrm_crmentity
   */
	private $crmid;
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	//$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	//$this->load->model("knowledgebase_model");
	$this->_limit = 100;
	$this->_module = "Services";
	$this->_format = "array";

	$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'data' => array(),
	);

	$this->_data= array(
			"status" => false,
			"message" =>"",
			"time" => date("Y-m-d H:i:s"),
			"data" => array("data" => ""),
	);
  }

	public function get_service_data($a_condition=array())
	{
		// echo "555"; exit;
		if(empty($a_condition)) return null;
		$this->load->model("servicerequest/servicerequest_model");
		$a_list=$this->servicerequest_model->get_list($a_condition);
		return $a_list;
	}

	public function get_inspection_data($a_condition=array())
	{
		if(empty($a_condition)) return null;
		$this->load->model("inspection/inspection_model");
		$a_list=$this->inspection_model->get_list($a_condition);
		return $a_list;
	}
	
	public function get_transfer_data($a_condition=array())
	{
		if(empty($a_condition)) return null;
		$this->load->model("tranfer/transfer_model");
		$a_list=$this->transfer_model->get_list($a_condition);
		return $a_list;
	}
	public function get_reservation_data($a_condition=array())
	{
		if(empty($a_condition)) return null;
		$this->load->model("reservation/reservation_model");
		$a_list=$this->reservation_model->get_list($a_condition);
		//print_r($a_list);
		return $a_list;
	}

	private function get_data($a_params=array())
	{

		if(empty($a_params["method"])){
			$a_data["status"] = false;
			$a_data["message"] = "Method is null";
			return array_merge($this->_data,$a_data);
		}
		
		$method = $a_params["method"];

		
		if($method=="service_request"){
			if(empty($a_params["servicerequestid"])){
				$a_data["status"] = false;
				$a_data["message"] = "Service Requestid is null";
				return array_merge($this->_data,$a_data);
			}
			$this->load->library('lib_sms_template');
			$a_condition["aicrm_servicerequests.servicerequestid"] = $a_params["servicerequestid"];
			$a_service_data = $this->get_service_data($a_condition);
		}
		else if($method=="service_close"){
			if(empty($a_params["servicerequestid"])){
				$a_data["status"] = false;
				$a_data["message"] = "Service Requestid is null";
				return array_merge($this->_data,$a_data);
			}
			$this->load->library('lib_sms_template');
			$a_condition["aicrm_servicerequests.servicerequestid"] = $a_params["servicerequestid"];
			$a_service_data = $this->get_service_data($a_condition);
		}
		else if ($method=="inspection_appointment")
		{
			if(empty($a_params["inspectionid"])){
				$a_data["status"] = false;
				$a_data["message"] = "Inspectionid is null";
				return array_merge($this->_data,$a_data);
			}
			$this->load->library('lib_sms_template');
			$a_condition["aicrm_inspection.inspectionid"] = $a_params["inspectionid"];
			$a_service_data = $this->get_inspection_data($a_condition);
			
			if(!empty($a_service_data)){
				$a_service = $a_service_data["result"]["data"][0];
				$inspection_timeno = $a_service["inspection_timeno"];
				if($inspection_timeno=="2"){
					$a_params["method"] = "inspection_appointment_second";
				}
			}		
			
		}
		else if ($method=="transfer_appointment")
		{
			if(empty($a_params["transferid"])){
				$a_data["status"] = false;
				$a_data["message"] = "Transferid is null";
				return array_merge($this->_data,$a_data);
			}
			$this->load->library('lib_sms_template');
			$a_condition["aicrm_transfer.transferid"] = $a_params["transferid"];
			$a_service_data = $this->get_transfer_data($a_condition);
				
		}
		else if ($method=="reserved")
		{
		
		
			if(empty($a_params["crmid"])){
				
				$a_data["status"] = false;
				$a_data["message"] = "bookingid is null";
				return array_merge($this->_data,$a_data);
			}
	
			$this->load->library('lib_sms_template');
		
			$a_condition["aicrm_booking.bookingid"] = $a_params["crmid"];			
			$a_service_data = $this->get_reservation_data($a_condition);
			
				
		}
		else if ($method=="contract_notification")
		{
		
			if(empty($a_params["crmid"])){
				
				$a_data["status"] = false;
				$a_data["message"] = "bookingid is null";
				return array_merge($this->_data,$a_data);
			}
	
			$this->load->library('lib_sms_template');
		
			$a_condition["aicrm_booking.bookingid"] = $a_params["crmid"];			
			$a_service_data = $this->get_reservation_data($a_condition);
			
				
		}
		$a_return = array();
		//$method = $a_params["method"];
		/*if (method_exists($this->lib_sms_template, $method)){
			$a_return = $this->lib_sms_template->{$method}($a_params);
		}*/
		$a_return = $this->lib_sms_template->get_data($a_params,$a_service_data);
	//	print_r($a_return);
		 return $a_return;
		//	echo  $a_return;
	}
	public function sendManual_get()
	{
		$this->common->_filename= "Send_SMS";
		$module = "sendManual_get";	 
		$a_params =  $this->input->get();
	
		$this->common->set_log($module." Begin",$a_params,array());
		$this->load->library('lib_sms_template');
		$a_return = $this->lib_sms_template->sendListsms($a_params);
		$this->return_data($a_return,$module,$a_params);
		
		
	}
	public function sendManual_post()
	{
		$this->common->_filename= "Send_SMS";
		$module = "sendManual_post";
		$request_body = file_get_contents('php://input');
		$a_params =  json_decode($request_body,true);
		$this->common->set_log($module." Begin",$a_params,array());
		$this->load->library('lib_sms_template');
		$a_return = $this->lib_sms_template->sendListsms($a_params);
		$this->return_data($a_return,$module,$a_params);
		
		
	}

	public function sendsms_get()
	{
		$this->common->_filename= "Send_SMS";
		$module = "sendsms_get";
	 
		$a_param =  $this->input->get();
		$this->common->set_log($module." Begin",$a_param,array());
	
		$a_data =$this->get_data($a_param);



		if(isset($a_param['response']))
		{
			$this->response($a_data, 200); // 200 being the HTTP response code
		}
		else
		{
			$this->return_data($a_data,$module,$a_param);
		}
	}

	/*
	 * parameter  username
	 * parameter passcode
	 */

	public function sendsms_post()
	{
		$this->common->_filename= "Send_SMS";
		$module = "sendsms_post";
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$this->common->set_log($module." Begin",$a_param,array());
		$a_data =$this->get_data($a_param);
		
		if(isset($a_param['response']))
		{
			$this->response($a_data, 200); // 200 being the HTTP response code
		}
		else
		{
			$this->return_data($a_data,$module,$a_param);
		}	


		//$this->return_data($a_data,$module,$a_param);
	}



	public function return_data($a_data,$module="sms",$a_param=array())
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["message"];
			$a_return["cachetime"] = @$a_data["time"];
			$a_return["data"] = @$a_data["data"]["data"];
		//alert($a_return["data"]);
			$this->common->set_log($module." End",$a_param,$a_return);
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