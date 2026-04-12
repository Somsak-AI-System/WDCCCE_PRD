<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
 * ### Class Social
 */
class Message extends REST_Controller
{
  /**
   * crmid ¤×Í crmid ã¹ aicrm_crmentity
   */
  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_accountshipads','aicrm_accountscf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid');
  
  function __construct()
  {
    parent::__construct();
    $this->output->set_header('Access-Control-Allow-Origin: *');
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	$this->_limit = 10;
	$this->_module = "Accounts";
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
					'AccountsNo' => null
			),
	);
  }

	private function set_param($a_param=array()){
		
		//$a_param_cardno = str_replace("-","",rawurldecode($a_param["ACCOUNTCARDNO"]));
		$a_condition = array();
		if (isset($a_param["ACCOUNTCARDTYPE"]) && $a_param["ACCOUNTCARDTYPE"]!="") {
			$a_condition["aicrm_accountscf.cf_2108"] = $a_param["ACCOUNTCARDTYPE"];
		}
		if (isset($a_param_cardno) && $a_param_cardno !="") {
			$a_condition["Replace(aicrm_accountscf.cf_2109,'-','' ) = "] = $a_param_cardno;
		}
		if(isset($a_param["ACCOUNTPHONE"]) && $a_param["ACCOUNTPHONE"] !="") {
			$a_param_phone = str_replace("-","",rawurldecode($a_param["ACCOUNTPHONE"]));
			$a_condition["Replace(aicrm_accountscf.cf_956,'-','' ) = "] = $a_param_phone;
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

	private function get_data($a_data)
	{
		return $a_data;
	}

	public function get_message_post(){
				
		$request_body = file_get_contents('php://input');
		$dataJson =  $this->input->post();
		
		if(empty($dataJson)){	
			$dataJson = json_decode($request_body,true);
		}
	
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		
		$response_data = null;
		$a_request =$dataJson;

		$this->common->set_log($url,$a_request,$request_body);
		$response_data = $this->get_cache_message($a_request);

		
		$this->common->set_log($url,$a_request,$response_data);
		$this->return_data($response_data);
	}

	private function get_cache_message($a_params=array()){
		$this->load->library('managecached_library');
		$this->load->model("message_model");
		$cachetime = date("Y-m-d H:i:s");
		
		$a_cache = array();
		if(!empty($a_params)){
			$a_condition = $this->set_param($a_params);
		}else{
			$a_condition = array();
		}
		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		
		$a_order = $this->set_order($order);
	
		$a_limit["limit"] = ($limit == "") ? @$this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;

		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_data = false;

		if($a_data===false)
		{
			$a_list=$this->message_model->get_list($a_condition,$a_order,$a_limit);
			//alert($a_list); exit;
			
			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"];
			$a_data["offset"] = $a_limit["offset"];
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
}