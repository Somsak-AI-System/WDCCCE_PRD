<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Helpdesk extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_troubletickets','aicrm_ticketcf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_troubletickets'=>'ticketid','aicrm_ticketcf'=>'ticketid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->_module = "HelpDesk";
		$this->load->model("helpdesk_model");
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
				'HelpDeskNo' => null
			),
		);
	}
	
	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();

			
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_troubletickets.ticketid"] =  $a_param["crmid"] ;
		}

		if (isset($a_param["accountid"]) && $a_param["accountid"]!="") {
			$a_condition["aicrm_troubletickets.accountid"] = $a_param["accountid"];
		}
		if (isset($a_param["contactid"]) && $a_param["contactid"]!="") {
			$a_condition["aicrm_troubletickets.contactid"] = $a_param["contactid"];
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

	private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');
		$this->load->model("helpdesk_model");
	
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
			$a_list=$this->helpdesk_model->get_list($a_condition,$a_order,$a_limit);
			// alert($a_list); exit();
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
	
	private function get_data($a_data)
	{
		if($a_data['result'] != ''){

			foreach ($a_data["result"]["data"] as $key =>$val){
				$ticketid = $val["ticketid"];
				$a_ticket[] = $ticketid;
			}

			$a_conditionin["aicrm_troubletickets.ticketid"] = $a_ticket;
			$a_image = $this->common->get_a_image($a_conditionin,$this->_module);

			foreach ($a_data["result"]["data"] as $key =>$val){
				$ticketid = $val["ticketid"];
				$a_return = $val;
				$a_return["image"] =( !empty($a_image[$ticketid]["image"]))?$a_image[$ticketid]["image"] :array();
				$a_response[] = $a_return;
			}
			
			$a_data["result"]["data"] = $a_response;

			
		}

		return $a_data;
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

	public function insert_content_post(){

		$this->common->_filename= "Insert_HelpDesk";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert HelpDesk==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert HelpDesk==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}

	}

	private function get_insert_data($a_request){

		$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		// $DocNo=isset($a_request['leadid']) ? $a_request['leadid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  		
	  		if(count($data[0])>0 and $module=="HelpDesk"){
	  			
	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'HelpDeskNo' => $DocNo,

	  				);
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

public function get_helpdesk_post(){

		$this->common->_filename= "Detail_HelpDesk";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Detail HelpDesk==>',$url,$a_request);

		$a_data = $this->helpdesk_model->get_helpdesk($a_request);
		// alert($a_data);exit;

	  	$this->common->set_log('After Detail HelpDesk==>',$a_request,$a_data);

	  	$a_data["data"] = $a_data["result"];
		$a_data["limit"] = $a_limit["limit"]  ;
		$a_data["offset"] = $a_limit["offset"]  ;
		$a_data["time"] = date("Y-m-d H:i:s");
		$a_cache["data"] = $a_data["result"];
		$a_cache["data"]["time"] = date("Y-m-d H:i:s");

		$this->return_data($a_data);
	  
	  	// if ( $response_data ) {
	  	// 	$this->response($response_data, 200); // 200 being the HTTP response code
	  	// } else {
	  	// 	$this->response(array(
	  	// 			'error' => 'Couldn\'t find Set Content!'
	  	// 	), 404);
	  	// }
	}


}