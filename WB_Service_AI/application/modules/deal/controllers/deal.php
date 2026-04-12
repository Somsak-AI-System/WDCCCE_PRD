<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Deal extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_deal','aicrm_dealcf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_deal'=>'dealid','aicrm_dealcf'=>'dealid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->_module = 'Deal';
		$this->load->model("deal_model");
		$this->_return = array(
				'Type' => "S",
				'Message' => "Insert Complete",
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "1",
				'offset' => "0",
				'limit' => "1",
				'data' => array(
						'Crmid' => null,
						'DealNo' => null
				),
		);
	}
	
	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_deal.dealid"] =  $a_param["crmid"] ;
		}

		if (isset($a_param["accountid"]) && $a_param["accountid"]!="") {
			$a_condition["aicrm_deal.accountid"] = $a_param["accountid"];
		}
		if (isset($a_param["contactid"]) && $a_param["contactid"]!="") {
			$a_condition["aicrm_deal.contactid"] = $a_param["contactid"];
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
		$this->load->model("deal_model");
	
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
			$a_list=$this->deal_model->get_list($a_condition,$a_order,$a_limit);
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

			/*foreach ($a_data["result"]["data"] as $key =>$val){
				$dealid = $val["dealid"];
				$a_ticket[] = $dealid;
			}

			$a_conditionin["aicrm_deal.dealid"] = $a_ticket;
			$a_image = $this->common->get_a_image($a_conditionin,$this->_module);

			foreach ($a_data["result"]["data"] as $key =>$val){
				$dealid = $val["dealid"];
				$a_return = $val;
				$a_return["image"] =( !empty($a_image[$dealid]["image"]))?$a_image[$dealid]["image"] :array();
				$a_response[] = $a_return;
			}
			
			$a_data["result"]["data"] = $a_response;*/

			
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
	  	$this->common->_filename= "Insert_Deal";

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

	  		if(count($data[0])>0 and $module== "Deal"){
	  			
	  			/*if($action=="edit"){
	  				$sql_deal = "select smownerid from aicrm_crmentity where crmid = '".$crmid."' ";
	  				$query = $this->db->query($sql_deal);
					$deal = $query->result_array();
					$d_smownerid = $deal[0]['smownerid'];
	  			}*/

	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data);
	  			
	  			if($chk=="0"){
	  				if($action=="add"){
	  					$data[0]['deal_no']=$DocNo;
	  					$this->set_notification($data[0],$crmid,$smownerid,$userid);
	  				}/*else if($action=="edit"){

	  					if($d_smownerid != $smownerid){
	  						$data[0]['deal_no']=$DocNo;
	  						$this->set_notification($data[0],$crmid,$smownerid,$userid);
	  					}
	  				}*/
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

	public function set_notification($a_data = array(),$crmid="",$smownerid="",$userid)
	{
		if(empty($a_data)) return "";
		if($crmid=="") return "";
		if($smownerid=="") return "";
		//alert($a_data); exit;
		$this->load->config('config_notification');
		$config = $this->config->item('notification');
		
		$method = 'Deal';
		$msg = $this->get_message($a_data);
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

		$a_param["Value1"] = @$notificationid;
		$a_param["Value2"] = $crmid;
		$a_param["Value3"] = 'Deal';
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
	
	public function get_message( $data=array()){
		
		$message ='';
		$message = 'หมายเลขโอกาสในการขาย : '.$data['deal_no'].'\nชื่อลูกค้า  : '.$data['account_name'].'\nเบอร์มือถือ : '.$data['mobile'];

		return $message;
	}
	

}