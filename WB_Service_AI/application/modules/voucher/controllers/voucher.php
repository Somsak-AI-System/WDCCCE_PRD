<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Voucher extends REST_Controller
{
	private $crmid;
	private $tab_name = array('aicrm_crmentity','aicrm_voucher','aicrm_vouchercf');
  	private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_voucher'=>'voucherid','aicrm_vouchercf'=>'voucherid');

	function __construct()
	{
	    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->library('lib_api_common');
	    $this->load->database();
		$this->load->library("common");
		$this->load->model("voucher_model");
		$this->_module = 'Voucher';
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
				'VoucherNo' => null
			),
		);
	}

	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_voucher.voucherid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["promotionid"]) && $a_param["promotionid"]!="") {
			$a_condition["aicrm_voucher.promotionid"] =  $a_param["promotionid"] ;
		}
		
		if (isset($a_param["promotionvoucherid"]) && $a_param["promotionvoucherid"]!="") {
			$a_condition["aicrm_voucher.promotionvoucherid"] =  $a_param["promotionvoucherid"] ;
		}

		if (isset($a_param["blank"]) && $a_param["blank"]!="") {
			$a_condition["ifnull(aicrm_voucher.leadid,0)"] =  '0' ;
			$a_condition["ifnull(aicrm_voucher.accountid,0)"] =  '0' ;
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

	private function get_cache($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("voucher_model");
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

			$a_list = $this->voucher_model->get_list($a_condition,$a_order,$a_limit,$a_params);
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

	private function get_data($a_data)
	{
		if($a_data['result'] != ''){			
		}
		return $a_data;
	}

	public function insert_content_post(){

		$this->common->_filename= "Insert_Voucher";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Voucher==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Voucher==>',$a_request,$response_data);
	  
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
	  		
	  		if(count($data[0])>0 and $module=="Voucher"){
	  			
	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
				
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'VoucherNo' => $DocNo,

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


	public function update_content_post(){

		$this->common->_filename= "Select_Voucher";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Select Voucher==>',$url,$a_request);
		//alert($a_request); exit;
		$response_data = $this->get_update_data($a_request);	
	  	$this->common->set_log('After Select Voucher==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}


	}

	private function get_update_data($a_request){

	  	$response_data = null;
	  	$module = isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$relmodule = isset($a_request['relmodule']) ? $a_request['relmodule'] : "";
	  	$data = isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid = isset($a_request['userid']) ? $a_request['userid'] : "1";

  		if(count($data['voucher'])>0 and $module=="Voucher"){
  			$r_data = array();
  			foreach ($data['voucher'] as $key => $value) {
  				if($relmodule == 'Accounts'){
  					$sql_rel = "update aicrm_voucher 
  					inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_voucher.voucherid 
  					set aicrm_voucher.accountid = '".$crmid."' , aicrm_crmentity.modifiedby = '".$userid."' , aicrm_crmentity.modifiedtime = '".date('Y-m-d H:i:s')."' 
  					where  aicrm_voucher.voucherid = '".$value."' ";
  				}else if($relmodule == 'Leads'){
  					$sql_rel = "update aicrm_voucher 
  					inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_voucher.voucherid
  					set aicrm_voucher.leadid = '".$crmid."' , aicrm_crmentity.modifiedby = '".$userid."' , aicrm_crmentity.modifiedtime = '".date('Y-m-d H:i:s')."' 	  					
  					where  aicrm_voucher.voucherid = '".$value."' ";
  				}
  				$this->db->query($sql_rel);

  				$select = "select aicrm_voucher.voucherid as 'id' ,aicrm_voucher.voucher_name as 'name'
			    , aicrm_voucher.voucher_no as 'no' , aicrm_voucher.vouchermessage as 'detail' , aicrm_voucher.startdate as startdate, aicrm_voucher.enddate as enddate,
			    aicrm_voucher.value ,
			    DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate ,aicrm_voucher.voucher_status as status
			    from aicrm_voucher
			    inner join aicrm_vouchercf on aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
			    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_voucher.voucherid
			    where aicrm_crmentity.deleted = 0 and aicrm_voucher.voucherid ='".$value."' ";
			    $query = $this->db->query($select);
			    $a_data =  $query->result_array();
	
				array_push($r_data, $a_data[0]) ;
  			}

  			//alert($r_data); exit;

  			$a_return["Message"] = "Select Complete";
				$a_return["data"] =$r_data;
  			
	  	}else{
	  		$a_return  =  array(
	  			'Type' => 'E',
	  			'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	}

}
