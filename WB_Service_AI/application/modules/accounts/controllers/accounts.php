<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class Accounts extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_accountshipads','aicrm_accountscf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("accounts_model");
		$this->load->load->config('config_module');
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
		
		$dsn  = 'mysql:dbname=' . $this->config->item('oauth_db_database') . ';host=' . $this->config->item('oauth_db_host');
		$dbusername = $this->config->item('oauth_db_username');
		$dbpassword = $this->config->item('oauth_db_password');
		OAuth2\Autoloader::register();

		// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
		$storage = new OAuth2\Storage\Pdo(array(
			'dsn' => $dsn,
			'username' => $dbusername,
			'password' => $dbpassword
		));
		// Pass a storage object or array of storage objects to the OAuth2 server class
		$this->oauth_server = new OAuth2\Server($storage);
		// Add the "Client Credentials" grant type (it is the simplest of the grant types)
		$this->oauth_server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
		// Add the "Authorization Code" grant type (this is where the oauth magic happens)
		$this->oauth_server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
	}
	
	public function insert_content_post(){

	  	$this->common->_filename= "Insert_Accounts";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Account ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Accounts ==>',$a_request,$response_data);
	  
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
		// $DocNo=isset($a_request['accountid']) ? $a_request['accountid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  		
	  		if(count($data[0])>0 and $module=="Accounts"){

	  			 $a_condition["aicrm_account.accountname"] = trim($data[0]['accountname']);
			      // $a_condition["aicrm_crmentity.smownerid"] = $userid;
			      $a_condition["aicrm_crmentity.deleted"] = "0";

			      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid',"inner");

			       if (!empty($a_condition)) {
	     				$this->db->where($a_condition);
	     			}

 				$query = $this->db->get('aicrm_account');

 			// 	echo $this->db->last_query(); exit;
 				
			      if($action=="add"){
					$dataAccount=$query->result_array();
					if(!empty($dataAccount)){
						return array(
							'Type' => 'E',
							'Message' => 'มี account นี้อยู่ในระบบแล้ว กรุณากดปุ่มตรวจสอบข้อมูลลูกค้า',
							'cache_time' => date("Y-m-d H:i:s"),
							'total' => "0",
							'offset' => "0",
							'limit' =>"1",
							'data'=>array(
						));
					}
				}

				if($action == 'edit'){

					$sql_acc = "select smownerid from aicrm_crmentity where crmid = '".$crmid."' ";
	  				$query = $this->db->query($sql_acc);
					$d_acc = $query->result_array();
					$d_smownerid = $deal[0]['smownerid'];
					$smownerid=isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
					//alert($d_smownerid); exit;
	  			}

				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				if($action == 'edit'){

	  					if($d_smownerid != $smownerid){
	  						$data[0]['account_no']=$DocNo;
	  						$this->set_notification($data[0],$crmid,$smownerid,$userid);
	  					}
	  				}

	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'AccountsNo' => $DocNo,

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

	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();
	
		if (isset($a_param["accountname"]) && $a_param["accountname"]!="") {
			$a_condition["aicrm_account.accountname like "] =  "%".$a_param["accountname"]."%" ;
		}
		
		if (isset($a_param["accountid"]) && $a_param["accountid"]!="") {
			$a_condition["aicrm_account.accountid"] =  $a_param["accountid"] ;
		}

		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_account.accountid"] =  $a_param["crmid"] ;
		}

		if (isset($a_param["mobile"]) && $a_param["mobile"]!="") {
			$a_condition["aicrm_account.mobile like "] = "%".$a_param["mobile"]."%";
		}
		
		if (isset($a_param["textsearch"]) && $a_param["textsearch"]!="") {
			$a_condition["(aicrm_account.accountname like '%".$a_param["textsearch"]."%' or aicrm_account.account_no like '%".$a_param["textsearch"]."%' or aicrm_account.sap_no like '%".$a_param["textsearch"]."%' or aicrm_account.mobile like '%".$a_param["textsearch"]."%' or aicrm_account.email1 like '%".$a_param["textsearch"]."%')"] =  null;
		}

		if(isset($a_param["searchname"]) && $a_param["searchname"]!=""){
			
			if($a_param["searchmobile"] == '' && $a_param["searchemail"] == ''){

				$a_condition["(aicrm_account.accountname like '%".$a_param["searchname"]."%')"] =  null;

			}else if($a_param["searchmobile"] != '' && $a_param["searchemail"] == ''){

				$a_condition["(aicrm_account.accountname like '%".$a_param["searchname"]."%' or aicrm_account.mobile like '%".$a_param["searchmobile"]."%')"] =  null;

			}else if($a_param["searchmobile"] == '' && $a_param["searchemail"] != ''){

				$a_condition["(aicrm_account.accountname like '%".$a_param["searchname"]."%' or aicrm_account.email1 like '%".$a_param["searchemail"]."%')"] =  null;

			}else{

				$a_condition["(aicrm_account.accountname like '%".$a_param["searchname"]."%' or aicrm_account.mobile like '%".$a_param["searchmobile"]."%' or aicrm_account.email1 like '%".$a_param["searchemail"]."%')"] =  null;

			}
		}

		if (isset($a_param["nocrmid"]) && $a_param["nocrmid"]!="") {
			$a_condition["aicrm_account.accountid != "] =  $a_param["nocrmid"] ;
		}
		
		if(isset($a_param["socialid"]) && $a_param["socialid"]!=""){
			$a_condition["aicrm_account.social_id"] =  $a_param["socialid"] ;
			$a_condition["aicrm_account.social_channel"] =  'Line' ;
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
		$this->load->model("accounts_model");
	
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
			$a_list=$this->accounts_model->get_list($a_condition,$a_order,$a_limit);
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
			foreach($a_data['result']['data'] as $key => $val ){
				$sub_index = iconv_substr($val['accountname'],0,1,"UTF-8");
				$a_data['result']['data'][$key]['index_name'] = $sub_index;
			}
		}
		return $a_data;
	}

	public function list_content_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);

		$this->return_data($a_data);
	}

	public function findAccount_post()
	{
		$request_body = file_get_contents('php://input');
		$param = json_decode($request_body,true);

		$this->db->select('aicrm_account.accountid,
		aicrm_account.accountname,
		aicrm_account.account_no,
		aicrm_account.email1,
		aicrm_account.mobile,
		aicrm_account.idcardno,
		aicrm_account.erpaccountid,
		aicrm_deal.gwno,
		aicrm_deal.wmno,
		aicrm_deal.machinenumber');
		$this->db->from('aicrm_account');
		$this->db->join('aicrm_crmentity AS crm_account', 'crm_account.crmid = aicrm_account.accountid');
		$this->db->join('aicrm_deal', 'aicrm_deal.accountid = aicrm_account.accountid', 'left');
		// $this->db->join('aicrm_crmentity AS crm_deal', 'crm_deal.crmid = aicrm_deal.dealid');
		$this->db->where(array(
			'crm_account.deleted' => 0,
			// 'crm_deal.deleted' => 0
		));
		
		if(isset($param['searchAccount']) && $param['searchAccount']!=''){
					$this->db->where('(aicrm_account.accountname LIKE "%'.$param['searchAccount'].'%" OR aicrm_account.account_no LIKE "%'.$param['searchAccount'].'%" OR aicrm_account.email1 LIKE "%'.$param['searchAccount'].'%" OR aicrm_account.mobile LIKE "%'.$param['searchAccount'].'%" OR aicrm_account.erpaccountid LIKE "%'.$param['searchAccount'].'%" OR aicrm_deal.gwno LIKE "%'.$param['searchAccount'].'%" OR aicrm_deal.wmno LIKE "%'.$param['searchAccount'].'%" OR aicrm_deal.machinenumber LIKE "%'.$param['searchAccount'].'%")');
		}
		$sql = $this->db->get();
		$result = $sql->result_array();

		$this->response($result, 200);
	}
	
	public function list_content_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_cache($a_param);
		
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

	public function get_account_post(){

		$this->common->_filename= "Detail_Accounts";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "0";

	  	$this->common->set_log('Before Detail Accounts==>',$url,$a_request);
		$response_data = $this->accounts_model->get_account($a_request);
		//alert($response_data);exit;
	  	
	  	$get_case = $this->case_list($crmid);
	  	$get_deal = $this->deal_list($crmid);
	  	$get_visit = $this->visit_list($crmid);

	  	$response_data['case'] = $get_case;
	  	$response_data['deal'] = $get_deal;
	  	$response_data['visit'] = $get_visit;
	  	//alert($response_data); exit;

	  	$this->common->set_log('After Detail Accounts==>',$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	public function case_list($crmid=''){

		$sql=" select count(aicrm_troubletickets.ticketid) as data
	    from aicrm_troubletickets
	    inner join aicrm_ticketcf on aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
	    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
	    where aicrm_troubletickets.parentid ='".$crmid."' and aicrm_crmentity.deleted = 0";

		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		
		return $a_data[0]['data'];
	}

	public function deal_list($crmid=''){

		$sql=" select count(aicrm_dealcf.dealid) as data
	    from aicrm_deal
	    inner join aicrm_dealcf on aicrm_dealcf.dealid = aicrm_deal.dealid
	    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
	    where aicrm_deal.parentid ='".$crmid."' and aicrm_crmentity.deleted = 0";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		
		return $a_data[0]['data'];	
	}

	public function visit_list($crmid=''){

		$sql=" select count(aicrm_activity.activityid) as data
	    from aicrm_activity
	    inner join aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
	    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_activity.activityid
	    where aicrm_activity.parentid ='".$crmid."' and aicrm_crmentity.deleted = 0";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		
		return $a_data[0]['data'];		
	}

	public function set_notification($a_data = array(),$crmid="",$smownerid="",$userid)
	{	
		//alert($a_data); exit;
		if(empty($a_data)) return "";
		if($crmid=="") return "";
		if($smownerid=="") return "";
		//alert($a_data); exit;
		$this->load->config('config_notification');
		$config = $this->config->item('notification');
		
		$method = 'Accounts';
		$msg = $this->get_message($a_data,$crmid);
		//echo $msg; exit;
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
		$a_param["Value3"] = 'Accounts';
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
	
	public function get_message( $data=array(),$crmid=''){
		
		$sql_acc = "select accountname from aicrm_account where accountid = '".$crmid."' ";
		$query = $this->db->query($sql_acc);
		$acc = $query->result_array();

		$message ='';
		$message = 'หมายเลขลูกค้า : '.$data['account_no'].'\nชื่อลูกค้า  : '.$acc[0]['accountname'].'\nสาขา : '.$data['branch'];

		return $message;
	}

	public function mergeAccount_post()
	{
		$this->common->_filename= "Merge_Accounts";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->set_log('Before Merge Account ==>',$url,$a_request);

	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$accountid=isset($a_request['accountid']) ? $a_request['accountid'] : "0";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "";

	  	$this->db->update('message_customer', array('parentid'=>$accountid), array('parentid'=>$crmid));

	  	$this->getRelatedNotesAttachments($crmid,$accountid);
	  	$this->getRelatedvisit($crmid,$accountid);
		$this->getRelatedleads($crmid,$accountid);
		$this->getRelatedcontacts($crmid,$accountid);
		$this->getRelateddeal($crmid,$accountid);
		$this->getRelatedproducts($crmid,$accountid);
		$this->getRelatedHelpDesk($crmid,$accountid);
		
		//Deleted Account merge
		//$this->db->update('aicrm_crmentity', array('deleted'=>0), array('crmid'=>$crmid));

		$sql = $this->db->get_where('aicrm_account', array('accountid'=>$accountid));
		$result = $sql->row_array();
		
		$this->response($result, 200);
	}

	private function getRelatedNotesAttachments($crmid,$accountid)
	{
		$sql_lead_notes	="select * from aicrm_senotesrel where crmid=?";
		$acc_notes_result = $this->db->query($sql_lead_notes, array($crmid));
		$noofrows = $acc_notes_result->result_array();
		
		foreach ($noofrows as $key => $value) {
			$sql_insert_notes="insert into aicrm_senotesrel(crmid,notesid) values (?,?)";
			$this->db->query($sql_insert_notes, array($accountid, $value['notesid']));
		}

	    $sql_acc_attachment="select * from aicrm_seattachmentsrel where crmid=?";
		$acc_attachment_result = $this->db->query($sql_acc_attachment, array($crmid));
		$noofrows = $acc_attachment_result->result_array();

		foreach ($noofrows as $key => $value) {
			$sql_insert_attachment="insert into aicrm_seattachmentsrel(crmid,attachmentsid) values (?,?)";                        
	        $this->db->query($sql_insert_attachment, array($accountid, $value['attachmentsid']));
		}
	}

	private function getRelatedvisit($crmid,$accountid)
	{
		$sql_update_deal="update aicrm_activity set parentid = ? where parentid = ?";                     
	    $this->db->query($sql_update_deal, array($accountid,$crmid));
	}

	private function getRelateddeal($crmid,$accountid)
	{
		$sql_update_deal="update aicrm_deal set parentid = ? where parentid = ?";                        
	    $this->db->query($sql_update_deal, array($accountid, $crmid));
	}

	private function getRelatedproducts($crmid,$accountid)
	{
		$sql_seproductsrel	="select * from aicrm_seproductsrel where crmid=?";
		$acc_productsrel_result = $this->db->query($sql_seproductsrel, array($crmid));
		$noofrows = $acc_productsrel_result->result_array();

		foreach ($noofrows as $key => $value) {
			$sql_insert_productsrel="insert into aicrm_seproductsrel(crmid,productid,setype) values (?,?,?)";
			$this->db->query($sql_insert_productsrel, array($accountid,$value['productid'],'Accounts'));
		}
	}

	private function getRelatedHelpDesk($crmid,$accountid)
	{
		$sql_update_deal="update aicrm_troubletickets set parentid = ? where parentid = ?";                        
	    $this->db->query($sql_update_deal, array($accountid, $crmid));
	}

	private function getRelatedleads($crmid,$accountid)
	{
		$sql_update_leads="update aicrm_leaddetails set accountid = ? where accountid = ?";                        
	    $this->db->query($sql_update_leads, array($accountid, $crmid));
	}

	private function getRelatedcontacts($crmid,$accountid)
	{
		$sql_update_contacts="update aicrm_contactdetails set accountid = ? where accountid = ?";                        
	    $this->db->query($sql_update_contacts, array($accountid, $crmid));
	}
	
	//Loyalty
	public function checkregister_post(){
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_cache_data($a_param);
		$this->return_data($a_data);
	}
	private function get_cache_data($a_params=array())
	{	
		if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}

		$this->load->library('managecached_library');
		$this->load->model("accounts_model");
		
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
			$a_list=$this->accounts_model->get_list_data($a_condition,$a_order,$a_limit);

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
	public function return_data_token($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"T";
			$a_return["Message"] = $a_data["error"];
			$a_return["total"] = @$a_data["data"]["total"];
			$a_return["offset"] = $a_data["offset"];
			$a_return["limit"] = $a_data["limit"];
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = @$a_data["data"]["data"];
			if ($format!="json" && $format!="xml"){
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
		  	$this->response(array('error' => 'Couldn\'t find any Building!'), 404);
		}
	}
	public function register_post(){

	  	$this->common->_filename= "Insert_Accounts_Loyalty";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Account ==>',$url,$a_request);
		$response_data = $this->register_data($a_request);	
	  	$this->common->set_log('After Insert Accounts ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function register_data($a_request){

	  	if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}

		$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";

	  	if($module=="Accounts"){
  			
  			$mobileno = '';
	  		$this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid',"inner");
	  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid',"inner");
	  		$this->db->where("aicrm_crmentity.deleted",0);
	  		$this->db->where("(aicrm_account.social_id","'".$data[0]['social_id']."'",false);
	  		$this->db->or_where("aicrm_account.mobile","'".$data[0]['mobile']."' )",false) ;
	  		$this->db->order_by("aicrm_crmentity.crmid", "desc");
	  		$query = $this->db->get('aicrm_account');
	  		
	  		$dataAccount = $query->result_array();
	  		
	  		if(!empty($dataAccount)){
				$accountid = $dataAccount[0]['accountid'];
				$mobileno = $dataAccount[0]['mobile'];

				if($mobileno != ''){
					$resmobile = str_replace('-', '', $mobileno);
					$first_string = substr($resmobile, 0, 3);
					$last_string = substr($resmobile, -4);

					$str_mobile = $first_string."-XXX-".$last_string;
				}	
  			}
  			
  			if(isset($data[0]['mobile']) && $data[0]['mobile'] != ''){ 
				
	        	if($mobileno != ''){
					$mobile = $mobileno;
				}else{
					$mobile = $data[0]['mobile'];
				}
	        	
	        	$sql="update tbm_history_otp set status ='Inactive' ,modifiy_date=NOW() where mobile = '".$mobile."' ";
	        	$this->db->query($sql) ;
	        	
	        	//Gen Number OTP
	            $random_number = mt_rand(100000, 999999);
	            //Insert data to Tbm history otp
	            $sql_insert=" INSERT INTO tbm_history_otp (forgot_date ,otp_number ,otp_request_chanel ,expired_date ,status ,create_date ,modifiy_date,type_otp,mobile)
	            VALUES (NOW() , '".$random_number."', 'SMS' , NOW() + INTERVAL 5 MINUTE, 'Active', NOW(), NOW(),'Register_OTP','".$mobile."')  ";
	            $this->db->query($sql_insert);
	            $history_otp_id = $this->db->insert_id();

	            $sql_e ="SELECT expired_date FROM tbm_history_otp WHERE id = '".$history_otp_id."' ";
	            $query_e = $this->db->query($sql_e) ;
	            $expired = $query_e->result_array();
	            
	            $data_account['otp_number'] = $random_number ;
	            if(isset($data[0]['mobile']) && $data[0]['mobile'] != ""){ 

	            	$this->load->library('lib_sms_template');
		            $method["method"] = "register_otp";
		            $a_return = $this->lib_sms_template->send_registersms_otp($method,$data_account,$mobile);
		        	
		            if($a_return['status'] == false){
		            	$a_return  =  array(
		  					'Type' => 'E',
		  					'Message' =>  "Can't Send SMS,Please try again",
		  				);
		  				return array_merge($this->_return,$a_return);
		            }else{
		            	$a_return  =  array(
		  					'Type' => 'S',
		  					'Message' =>  'Send OTP Complete',
		  					'Expired' => @$expired[0]['expired_date'],
		  					'crmid' => @$accountid,
		  					'phone' => @$str_mobile,
		  				);
		            	return array_merge($this->_return,$a_return);
		            }
	            }
			} else {
				$a_return  =  array(
  					'Type' => 'E',
  					//'Message' =>  'Please contact tel. 02-731-2333',
  					'Message' =>  'Please contact tel. 02-731-2333',
  				);
  				return array_merge($this->_return,$a_return);
			}
	  		
	  	}else{
	  		$a_return  =  array(
	  			'Type' => 'E',
	  			'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	}

	public function list_detail_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_detail($a_param);
		
		$this->return_data($a_data);
	}
	private function get_detail($a_params=array())
	{	
		if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}
		$this->load->library('managecached_library');
		$this->load->model("accounts_model");
		
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
			$a_list=$this->accounts_model->get_list($a_condition,$a_order,$a_limit);
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

	public function edit_content_post(){

	  	$this->common->_filename= "Edit_Accounts_Loyaty";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Edit Account ==>',$url,$a_request);
		$response_data = $this->get_edit_data($a_request);	
	  	$this->common->set_log('After Edit Accounts ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_edit_data($a_request){

		if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  		
	  		if(count($data[0])>0 and $module=="Accounts"){

			    /*$a_condition["aicrm_crmentity.deleted"] = "0";
			    $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid',"inner");

		        if (!empty($a_condition)) {
     				$this->db->where($a_condition);
     			}

				$query = $this->db->get('aicrm_account');
			    
			    if($action=="add"){
					$dataAccount=$query->result_array();
					
					if(!empty($dataAccount)){
						return array(
							'Type' => 'E',
							'Message' => 'มี account นี้อยู่ในระบบแล้ว กรุณากดปุ่มตรวจสอบข้อมูลลูกค้า',
							'cache_time' => date("Y-m-d H:i:s"),
							'total' => "0",
							'offset' => "0",
							'limit' =>"1",
							'data'=>array(
						));
					}
				}
				if($action == 'edit'){
					$sql_acc = "select smownerid from aicrm_crmentity where crmid = '".$crmid."' ";
	  				$query = $this->db->query($sql_acc);
					$d_acc = $query->result_array();
					$d_smownerid = $deal[0]['smownerid'];
					$smownerid=isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
	  			}*/

				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'AccountsNo' => $DocNo,

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

	public function otpresend_post(){
		$this->common->_filename= "Accounts_ResendOTP_Loyalty";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Resend Account ==>',$url,$a_request);
		$response_data = $this->otpresend_data($a_request);	
	  	$this->common->set_log('After Resend Accounts ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function otpresend_data($a_request){

	  	if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}

		$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$mobile=isset($a_request['mobile']) ? $a_request['mobile'] : ""; 

	  	if($module=="Accounts"){
  			  			
  			if(isset($mobile) && $mobile != ''){ 
					        	
	        	$sql="update tbm_history_otp set status ='Inactive' ,modifiy_date=NOW() where mobile = '".$mobile."' ";
	        	$this->db->query($sql);

	        	//Gen Number OTP
	            $random_number = mt_rand(100000, 999999);
	            //Insert data to Tbm history otp
	            $sql_insert=" INSERT INTO tbm_history_otp (forgot_date ,otp_number ,otp_request_chanel ,expired_date ,status ,create_date ,modifiy_date,type_otp,mobile)
	            VALUES (NOW() , '".$random_number."', 'SMS' , NOW() + INTERVAL 5 MINUTE, 'Active', NOW(), NOW(),'Register_OTP','".$mobile."')  ";
	            $this->db->query($sql_insert);
	            $history_otp_id = $this->db->insert_id();

	            $sql_e ="SELECT expired_date FROM tbm_history_otp WHERE id = '".$history_otp_id."' ";
	            $query_e = $this->db->query($sql_e) ;
	            $expired = $query_e->result_array();
	            
	            $data_account['otp_number'] = $random_number ;
	            if(isset($mobile) && $mobile != ""){ 

	            	$this->load->library('lib_sms_template');
		            $method["method"] = "register_otp";
		            $a_return = $this->lib_sms_template->send_registersms_otp($method,$data_account,$mobile);
		        	
		            if($a_return['status'] == false){
		            	$a_return  =  array(
		  					'Type' => 'E',
		  					'Message' =>  "Can't Send SMS,Please try again",
		  				);
		  				return array_merge($this->_return,$a_return);
		            }else{
		            	$a_return  =  array(
		  					'Type' => 'S',
		  					'Message' =>  'Send OTP Complete',
		  					'Expired' => @$expired[0]['expired_date'],
		  					'crmid' => @$accountid,
		  					'phone' => @$str_mobile,
		  				);
		            	return array_merge($this->_return,$a_return);
		            }
	            }
			} else {
				$a_return  =  array(
  					'Type' => 'E',
  					//'Message' =>  'Please contact tel. 02-731-2333',
  					'Message' =>  'Please contact tel. 02-731-2333',
  				);
  				return array_merge($this->_return,$a_return);
			}
	  		
	  	}else{
	  		$a_return  =  array(
	  			'Type' => 'E',
	  			'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	}

	public function insert_registerotp_post(){
		$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);

	  	$response_data = null;
	  	$a_request =$dataJson;
		$response_data = $this->get_insert_registerotp_data($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_Register_OTP";

	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_registerotp_data($a_request) {

		if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? strtolower($a_request['action']) : "";
	  	$acc_data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$point=isset($acc_data[0]['point']) ? $acc_data[0]['point'] : "0";

	  	$mobileno = isset($acc_data[0]['mobile']) ? $acc_data[0]['mobile'] : "";
	  	$social_id = isset($acc_data[0]['social_id']) ? $acc_data[0]['social_id'] : "";
	  	$confirmotp = isset($acc_data[0]['confirmotp']) ? $acc_data[0]['confirmotp'] : "";
	  	$userid = isset($acc_data[0]['userid']) ? $acc_data[0]['userid'] : "19330";

	  	if($mobileno != '' && $confirmotp != ''){
	  		//mobile = '".$mobileno."' AND
			$sql ="SELECT id, TIMESTAMPDIFF(MINUTE , forgot_date, NOW( ) ) AS  `difference` FROM tbm_history_otp WHERE  status =  'active' and otp_number = '".$confirmotp."' and mobile = '".$mobileno."' ";
            $query = $this->db->query($sql) ;
            $a_data = $query->result_array();
            
	  		if(!empty($a_data) && $a_data[0]['difference'] < 5 ){

	  			if($action == 'add'){
	  				$data[0]['account_no'] = '';
	  				$data[0]['accountstatus'] = 'Non-Customer';
	  				$data[0]['accountname'] = $acc_data[0]['firstname'].' '.$acc_data[0]['lastname'];
	  				$data[0]['firstname'] = $acc_data[0]['firstname'];
		  			$data[0]['lastname'] = $acc_data[0]['lastname'];
		  			$data[0]['email1'] = $acc_data[0]['email1'];

		  			$data[0]['mobile'] = $acc_data[0]['mobile'];
		  			$data[0]['address'] = $acc_data[0]['address'];
		  			$data[0]['subdistrict'] = $acc_data[0]['subdistrict'];
		  			$data[0]['district'] = $acc_data[0]['district'];
		  			$data[0]['province'] = $acc_data[0]['province'];
		  			$data[0]['postalcode'] = $acc_data[0]['postalcode'];
		  			$data[0]['social_id'] = $acc_data[0]['social_id'];
		  			$data[0]['social_channel'] = 'Line';
		  			$data[0]['first_contact_date'] = date('Y-m-d');
		  			$data[0]['birthdate'] = $acc_data[0]['birthdate'];
		  			$data[0]['gender'] = $acc_data[0]['gender'];
		  			$data[0]['smcreatorid'] = '1';
		  			$data[0]['smownerid'] = '19330';
		  			$acc_name = $acc_data[0]['firstname']." ".$acc_data[0]['lastname'];
		  			$data[0]['main_contact_channel'] = 'Line';
		  			$data[0]['accounttype'] = 'Loyalty';
					$data[0]['line_loyalty'] = '1';
					$data[0]['line_oa_facebook_fan_page_name'] = $acc_data[0]['channel_name'];
					$data[0]['social_name'] = $acc_data[0]['social_name'];
					
	  			}else if($action == 'edit'){
	  				
  					$data[0]['firstname'] = $acc_data[0]['firstname'];
		  			$data[0]['lastname'] = $acc_data[0]['lastname'];
		  			$data[0]['birthdate'] = $acc_data[0]['birthdate'];

		  			$data[0]['smcreatorid'] = '1';
		  			$data[0]['modifiedby'] = '1';
		  			$data[0]['modifiedtime'] = date('Y-m-d h:i:s');
		  			$data[0]['accountname'] = $acc_data[0]['firstname']." ".$acc_data[0]['firstname'];
		  			$acc_name = $acc_data[0]['firstname'].' '.$acc_data[0]['lastname'];
		  			$data[0]['email1'] = $acc_data[0]['email1'];
		  			$data[0]['mobile'] = $acc_data[0]['mobile'];
		  			$data[0]['address'] = $acc_data[0]['address'];
		  			$data[0]['subdistrict'] = $acc_data[0]['subdistrict'];
		  			$data[0]['district'] = $acc_data[0]['district'];
		  			$data[0]['province'] = $acc_data[0]['province'];
		  			$data[0]['postalcode'] = $acc_data[0]['postalcode'];
		  			$data[0]['gender'] = $acc_data[0]['gender'];
	  			}
	  			
	  			$sql="update tbm_history_otp set status ='Inactive' ,modifiy_date=NOW() where id = '".$a_data[0]['id']."' ";
        		$this->db->query($sql) ;
        		
	  		}else if(!empty($a_data) && $a_data[0]['difference'] > 5){
	  			return array(
  					'Type' => 'E',
  					'Message' => 'รหัส OTP หมดอายุ',
  					'cache_time' => date("Y-m-d H:i:s"),
  					'total' => "0",
  					'offset' => "0",
  					'limit' =>"1",
  					'data'=>array()
  				);
                return array_merge($this->_data, $a_data);
	  		}else{
	  			return array(
  					'Type' => 'E',
  					'Message' => 'รหัส OTP ไม่ถูกต้อง',
  					'cache_time' => date("Y-m-d H:i:s"),
  					'total' => "0",
  					'offset' => "0",
  					'limit' =>"1",
  					'data'=>array()
	  			);
	  		}
	  	}

	  	if($module=="Accounts"){

	  		if($action=="add"){
	  			/*$sql_point = "SELECT type1 , type8 FROM aicrm_point_config";
	  			$query_point = $this->db->query($sql_point) ;
	        	$a_point = $query_point->result_array();
	        	$type1 = isset($a_point[0]['type1']) ? $a_point[0]['type1'] : "0";//New Register*/
	        	$type1 = isset($point) ? $point : "0";//New Register
	        	//$type8 = isset($a_point[0]['type8']) ? $a_point[0]['type8'] : "0";//Get Friend*/
        	}

	  		list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
	  			
	  			if($chk=="0"){
	  				
	  				if($action=="add"){
			  			//Create Contacts
			  			$config =  $this->config->item('module');
						$this->tab_name_con = $config["Contacts"]["tab_name"];
						$this->tab_name_index_con = $config["Contacts"]["tab_name_index"];
						
						$con_data[] =array(
							'contact_no'			=> '',
							'contactstatus'			=> 'Active',
							'contactname'			=> $acc_data[0]['firstname'].' '.$acc_data[0]['lastname'],
							'firstname'				=> $acc_data[0]['firstname'],
							'lastname'				=> $acc_data[0]['lastname'],
							'birthdate'				=> $acc_data[0]['birthdate'],
							'email'					=> $acc_data[0]['email1'],
							'mobile'				=> $acc_data[0]['mobile'],
							'account_id'			=> $crmid,
							'address' 				=> $acc_data[0]['address'],
				  			'subdistrict'			=> $acc_data[0]['subdistrict'],
				  			'district' 				=> $acc_data[0]['district'],
				  			'province' 				=> $acc_data[0]['province'],
				  			'postalcode' 			=> $acc_data[0]['postalcode'],
				  			'social_channel' 		=> 'Line',
				  			'social_id' 			=> $acc_data[0]['social_id'],
				  			'main_contact_channel'	=> 'Line',
				  			'first_contact_date'	=> date('Y-m-d')
						);

						$con_data[0]['smownerid']= 19374;
						$module_con = "Contacts";
						$userid = 1;
						$action_con="add";
						$crmid_con="";
						list($chk_c,$crmid_c,$DocNo_c)=$this->crmentity->Insert_Update($module_con,$crmid_con,$action_con,$this->tab_name_con,$this->tab_name_index_con,$con_data,$userid);
						//Create Contacts
						
						//Update Lead
						$convert_sql="update aicrm_leaddetails
							inner join message_customer on message_customer.parentid = aicrm_leaddetails.leadid
							inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
							set aicrm_leaddetails.converted = 1 , aicrm_leaddetails.convert_lead = 1 ,
							aicrm_leaddetails.accountid = '".$crmid."' , aicrm_leaddetails.contactid = '".$crmid_con."' , aicrm_leaddetails.convert_date = '".date('Y-m-d H:i:s')."'
							where aicrm_crmentity.deleted =0 and message_customer.customerno = '".$acc_data[0]['social_id']."' ";
						$this->db->query($convert_sql);
						//Update Lead

						//Update OC
						$message_sql=" update message_customer set module = 'Accounts' , parentid = '".$crmid."' , contactid = '".$crmid_c."' where customerno = '".$acc_data[0]['social_id']."' and module = 'Leads'";
						$this->db->query($message_sql);
						//Update OC
					}
					
					if($action=="edit"){
						$config =  $this->config->item('module');
						$this->tab_name_con = $config["Contacts"]["tab_name"];
						$this->tab_name_index_con = $config["Contacts"]["tab_name_index"];
						
						$con_data[] =array(
							'contact_no'			=> '',
							'contactstatus'			=> 'Active',
							'contactname'			=> $acc_data[0]['firstname'].' '.$acc_data[0]['lastname'],
							'firstname'				=> $acc_data[0]['firstname'],
							'lastname'				=> $acc_data[0]['lastname'],
							'birthdate'				=> $acc_data[0]['birthdate'],
							'email'					=> $acc_data[0]['email1'],
							'mobile'				=> $acc_data[0]['mobile'],
							'account_id'			=> $crmid,
							'address' 				=> $acc_data[0]['address'],
				  			'subdistrict'			=> $acc_data[0]['subdistrict'],
				  			'district' 				=> $acc_data[0]['district'],
				  			'province' 				=> $acc_data[0]['province'],
				  			'postalcode' 			=> $acc_data[0]['postalcode'],
				  			'social_channel' 		=> 'Line',
				  			'social_id' 			=> $acc_data[0]['social_id'],
				  			'main_contact_channel'	=> 'Line',
				  			'first_contact_date'	=> date('Y-m-d')

						);
						$con_data[0]['smownerid']= 19374;
						$module_con = "Contacts";
						$userid = 1;
						$action_con="add";
						$crmid_con="";
						list($chk_c,$crmid_c,$DocNo_c)=$this->crmentity->Insert_Update($module_con,$crmid_con,$action_con,$this->tab_name_con,$this->tab_name_index_con,$con_data,$userid);

						$convert_sql="update aicrm_leaddetails
							inner join message_customer on message_customer.parentid = aicrm_leaddetails.leadid
							inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
							set aicrm_leaddetails.converted = 1 , aicrm_leaddetails.convert_lead = 1 ,
							aicrm_leaddetails.accountid = '".$crmid."' , aicrm_leaddetails.contactid = '".$crmid_con."' , aicrm_leaddetails.convert_date = '".date('Y-m-d H:i:s')."'
							where aicrm_crmentity.deleted =0 and message_customer.customerno = '".$acc_data[0]['social_id']."' ";
						$this->db->query($convert_sql);

						$message_sql=" update message_customer set module = 'Accounts' , parentid = '".$crmid."' , contactid = '".$crmid_c."' where customerno = '".$acc_data[0]['social_id']."' and module = 'Leads'";
						$this->db->query($message_sql);

					}
	
	  				//Add Point New Register
	  				if($action=="add"){
		  				
		  				if($type1 != 0){

			  				$tab_name_point = array('aicrm_crmentity','aicrm_point','aicrm_pointcf');
		  					$tab_name_index_point = array('aicrm_crmentity'=>'crmid','aicrm_point'=>'pointid','aicrm_pointcf'=>'pointid');
		  					$module_point = "Point";

		  					$data_point[0]['point_no'] = '';//Point No
		  					$data_point[0]['point_name'] = 'New Register';//Point Name
		  					$data_point[0]['accountid'] = $crmid;//ชื่อลูกค้า
		  					$data_point[0]['sourcestatus'] = 'Add';//สถานะคะแนน
		  					$data_point[0]['points'] = $type1;//คะแนน
		  					$data_point[0]['point_source'] = '--None--';//ช่องทางการได้คะแนน
		  					$data_point[0]['total_point'] = $type1;//คะแนนทั้งหมด
		  					$data_point[0]['remain_point'] = $type1;//คะแนนคงเหลือ
		  					$data_point[0]['point_start_date'] = date('Y-m-d');//วันที่สร้างคะแนน
		  					$data_point[0]['point_expired_date'] = date('Y-12-31', strtotime('+1 year'));//วันที่หมดอายุคะแนน	
		  					$data_point[0]['smownerid'] = '1';//Assigned To
		  					$data_point[0]['smcreatorid'] = '1';//Assigned To
		  					$data_point[0]['description'] = 'Add Point New Register '.$type1.' Point';//คำอธิบาย
		  					
		  					list($chk_point,$crmid_point,$DocNo_point)=$this->crmentity->Insert_Update($module_point,'',$action,$tab_name_point,$tab_name_index_point,$data_point,$userid);

		  					if($chk_point=="0"){
		  						$a_data=array();
		  						$a_data['action'] = 'add';
		  						$a_data['brand'] = '';
		  						$a_data['channel'] = 'New Register';
		  						$a_data['point'] = $type1;
		  						$a_data['accountid'] = $crmid;
		  						$a_data['type'] = '';
		  						$a_data['pointid'] = $crmid_point;
		  						$this->load->library('lib_point');
	            				$this->lib_point->get_adjust($a_data);
	            			}
            			}

	  				}else if($action == "edit"){

	  				}

	  				$a_return["Message"] = ($action=="add")?"Successful" : "Successful";
	  				$a_return["data"] =array(
	  					'Crmid' => $crmid,
	  					'account_no' => $DocNo,
	  				);

	  			}else{
	  				$a_return  =  array(
	  					'Type' => 'E',
	  					'Message' => 'Unable to complete transaction',
	  					'data' => array(
                            'Crmid' => '',
                            'account_no' => ''
                        )
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
	//Loyalty
}