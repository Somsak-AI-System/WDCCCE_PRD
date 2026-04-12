<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class SmartSms extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_smartsms','aicrm_smartsmscf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_smartsms'=>'smartsmsid','aicrm_smartsmscf'=>'smartsmsid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->_module = 'SmartSms';
		$this->load->model("smartsms_model");
		$this->_return = array(
				'Type' => "S",
				'Message' => "Insert Complete",
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "1",
				'offset' => "0",
				'limit' => "1",
				'data' => array(
						'Crmid' => null,
						'SmartSmsNo' => null
				),
		);
	}
	
	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_smartsms.smartsmsid"] =  $a_param["crmid"] ;
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
		$this->load->model("smartsms_model");
	
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
			$a_list=$this->smartsms_model->get_list($a_condition,$a_order,$a_limit);
			//alert($a_list); exit();
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
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->get_insert_data($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_SmartSms";

	  	$this->common->set_log($url,$a_request,$response_data);
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
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "";

	  	$smownerid=isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";

	  		if(count($data[0])>0 and $module== "SmartSms"){
	  			
	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data);
	  			
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  					'Crmid' => $crmid,
	  					'DocNo' => $DocNo,
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

	public function repeat_purchase_post(){
        
        $request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data =$this->get_repeat_purchase($a_param);
		return true;
    }

    private function get_repeat_purchase($a_params=array())
	{
		$this->load->library('managecached_library');
		$this->load->model("smartsms_model");
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$this->common->_filename= "Repeat_Purchase";
		
		$a_condition["aicrm_smartemail.email_start_date = DATE_ADD(DATE(NOW()), INTERVAL -2 DAY)"] = null;
		$a_condition["aicrm_smartemail.flow"] = 'Repeat Purchase';
     	$a_condition["aicrm_crmentity.deleted"] = "0";
     	$a_condition["aicrm_smartemail.email_status"] = "Completed";

     	$this->db->select("aicrm_smartemail.smartemailid ,aicrm_smartemail.smartemail_no ,aicrm_smartemail.sms_message , aicrm_crmentity.smownerid");
      	$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_smartemail.smartemailid',"inner");
		$this->db->where($a_condition);
		$query = $this->db->get('aicrm_smartemail');
		//echo $this->db->last_query(); exit;
		$d_smartemial = $query->result_array();
		//alert($d_smartemial); exit;
		$this->common->set_log('Get Smartemail---> ',date('Y-m-d'),$d_smartemial);

		if(!empty($d_smartemial)){

			foreach ($d_smartemial as $key => $value) {
				
				$crmid="";
				$doc_no="";
				$action="add";
				$module= "SmartSms";
				$data = array();
				$smartemailid = $value['smartemailid'];
				$userid = 1;
				$data[] =array(
					'smartsms_no'				=> '',
					'smartsms_name'				=> 'Repeat Purchase-'.$value['smartemail_no'],
					'sms_status'				=> 'Schedule',
					'sms_sender_name'			=> 1,
					'sms_message'				=> $value['sms_message'],
					'sms_start_date'			=> date('Y-m-d'),
					'sms_start_time'			=> '11:00',
					'sms_character'				=> $this->get_character($value['sms_message']),
					'sms_credit'				=> $this->get_credit($value['sms_message']),
					'smartemailid'				=> $value['smartemailid'],
					'smownerid'					=> $value['smownerid'],
				);

				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
				$this->common->set_log('Inert SmartSMS---> ',$crmid, $DocNo);

				$sql="INSERT INTO aicrm_smartsms_accountsrel (smartsmsid, accountid)
				select '".$crmid."',aicrm_account.accountid 
				from aicrm_smartemail_accountsrel
				inner join aicrm_account on aicrm_account.accountid = aicrm_smartemail_accountsrel.accountid
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
				where aicrm_smartemail_accountsrel.smartemailid = '".$smartemailid."' and aicrm_account.repeat_buyers = 0";
				$this->db->query($sql);
			}
	
		}
		
		return true;
	}
	
	public function monthly_promotion_post(){
        
        $request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data =$this->get_monthly_promotion($a_param);
		return true;
    }

    private function get_monthly_promotion($a_params=array())
	{
		$this->load->library('managecached_library');
		$this->load->model("smartsms_model");
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$this->common->_filename= "Monthly_Promotion";
		
		$a_condition["aicrm_smartemail.email_start_date = DATE_ADD(DATE(NOW()), INTERVAL -1 DAY)"] = null;
		$a_condition["aicrm_smartemail.flow"] = 'Monthly Promotion or Newsletter';
     	$a_condition["aicrm_crmentity.deleted"] = "0";
     	$a_condition["aicrm_smartemail.email_status"] = "Completed";

     	$this->db->select("aicrm_smartemail.smartemailid ,aicrm_smartemail.smartemail_no ,aicrm_smartemail.sms_message , aicrm_crmentity.smownerid");
      	$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_smartemail.smartemailid',"inner");
		$this->db->where($a_condition);
		$query = $this->db->get('aicrm_smartemail');
		//echo $this->db->last_query(); exit;
		$d_smartemial = $query->result_array();
		//alert($d_smartemial); exit;
		$this->common->set_log('Get Smartemail---> ',date('Y-m-d'),$d_smartemial);

		if(!empty($d_smartemial)){

			foreach ($d_smartemial as $key => $value) {
				
				$crmid="";
				$doc_no="";
				$action="add";
				$module= "SmartSms";
				$data = array();
				$smartemailid = $value['smartemailid'];
				$userid = 1;
				$data[] =array(
					'smartsms_no'				=> '',
					'smartsms_name'				=> 'Monthly Promotion or Newsletter-'.$value['smartemail_no'],
					'sms_status'				=> 'Schedule',
					'sms_sender_name'			=> 1,
					'sms_message'				=> $value['sms_message'],
					'sms_start_date'			=> date('Y-m-d'),
					'sms_start_time'			=> '11:00',
					'sms_character'				=> $this->get_character($value['sms_message']),
					'sms_credit'				=> $this->get_credit($value['sms_message']),
					'smartemailid'				=> $value['smartemailid'],
					'smownerid'					=> $value['smownerid'],
				);

				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
				$this->common->set_log('Inert SmartSMS---> ',$crmid, $DocNo);

				$sql="INSERT INTO aicrm_smartsms_accountsrel (smartsmsid, accountid)
				select '".$crmid."',aicrm_account.accountid 
				from aicrm_smartemail_accountsrel
				inner join aicrm_account on aicrm_account.accountid = aicrm_smartemail_accountsrel.accountid
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
				where aicrm_smartemail_accountsrel.smartemailid = '".$smartemailid."' and aicrm_account.repeat_buyers = 0";
				$this->db->query($sql);
			}
	
		}
		
		return true;
	}

	private function get_character($text=''){
		$character = 0;

		if (!preg_match('/[^A-Za-z0-9]/', $text)){
		  	$character = mb_strlen($text, 'UTF-8');
		}else{
			$character = mb_strlen($text, 'UTF-8');
		}
		return $character;
	}

	private function get_credit($text=''){
		$character = mb_strlen($text, 'UTF-8');
		if (!preg_match('/[^A-Za-z0-9]/', $text)){
			if($character <= 160 && $character != 0){
				return 1;
			}else if($character == 0){
				return 0;
			}else{
				return ceil($character/157);
			}
		}else{
			if($character <= 70 && $character != 0){
				return 1;
			}else if($character == 0){
				return 0;
			}else{
				return ceil($character/67);
			}
		}
	}

}