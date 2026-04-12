<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once('src/OAuth2/Autoloader.php');
require_once(APPPATH.'/libraries/REST_Controller.php');
// ini_set('display_errors', 1);

class Leads extends REST_Controller
{

	private $crmid;
	private $tab_name = array('aicrm_crmentity','aicrm_leaddetails','aicrm_leadsubdetails','aicrm_leadaddress','aicrm_leadscf');
	private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_leaddetails'=>'leadid','aicrm_leadsubdetails'=>'leadsubscriptionid','aicrm_leadaddress'=>'leadaddressid','aicrm_leadscf'=>'leadid');
  
	function __construct()
	{
		parent::__construct();
	    $this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->_limit = 20;
		$this->_format = "array";
		$this->_module = 'Leads';
		$this->load->model("leads_model");
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

	private function set_param($a_param = array())
    {
        
        $a_condition = array();
        
       	if (isset($a_param["crmid"]) && $a_param["crmid"] != "") {
            $a_condition["aicrm_leads.leadsid"] = $a_param["crmid"];
        }

        if (isset($a_param["lastupdate"]) && $a_param["lastupdate"] != "") {
        	$start_date=date('Y-m-d', strtotime("-1 days"));
			$last_date=date('Y-m-d');

            $a_condition["LEFT(aicrm_crmentity.modifiedtime, 10) >="] = $start_date;
            $a_condition["LEFT(aicrm_crmentity.modifiedtime, 10) <="] = $last_date;
        }
        return $a_condition;
    
    }

    private function set_order($a_orderby = array())
    {
        if (empty($a_orderby)) return false;
        
        $a_order = array();
        $a_condition = explode("|", $a_orderby);
        
        for ($i = 0; $i < count($a_condition); $i++) {
            list($field, $order) = explode(",", $a_condition[$i]);
            $a_order[$i]["field"] = $field;
            $a_order[$i]["order"] = $order;
        }
        return $a_order;
    }

    private function get_data($a_data)
    {
    	if(!empty($a_data["result"]["data"]) && $a_data["status"] ){

			foreach ($a_data["result"]["data"] as $key =>$val){
				$leadsid = $val["leadsid"];
				$a_leads[] = $leadsid;
			}

			$a_conditionin["aicrm_leads.leadsid"] = $a_leads;
			$a_image = $this->common->get_a_image($a_conditionin,$this->_module);
			
			foreach ($a_data["result"]["data"] as $key =>$val){
				$leadsid = $val["leadsid"];
				$a_return = $val;

				$a_return["image"] = (!empty($a_image[$leadsid]["image"]))?$a_image[$leadsid]["image"] : array();
				$a_response[] = $a_return;
			}
			$a_data["result"]["data"] = $a_response;
		}
		//alert($a_data);exit;
        return $a_data;
    }

	public function list_post(){

		$this->common->_filename= "Login_Contact";
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		
        $a_request = $dataJson;
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->set_log('Get Login==>',$url,$a_request);

        $a_data = $this->get_list($a_request);
        $this->return_data($a_data);
    }

    private function get_list($a_params=array())
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

        $a_cache = array();
        $a_cache["_ctag"] = $this->_module . '/';
        $a_cache["_ckname"] = $this->_module . '/get_content';
        $a_condition = array();

        $a_condition = $this->set_param($a_params);
        
        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order = @$a_params["orderby"];
        $a_order = $this->set_order($order);

        $a_limit["limit"] = ($limit == "") ? $this->_limit : $limit;
        $a_limit["offset"] = ($offset == "") ? 0 : $offset;
        $a_data = $this->managecached_library->get_memcache($a_cache);

        if ($a_data === false) {
            
            $a_list=$this->leads_model->get_list($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["status"] = $a_list["status"];
			$a_data["error"] = $a_list["error"]  ;
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
			$a_return["Message"] = $a_data["error"];
			$a_return["total"] = @$a_data["data"]["total"];
			$a_return["offset"] = $a_data["offset"];
			$a_return["limit"] = $a_data["limit"];
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = @$a_data["data"]["data"];
			if ($format!="json" && $format!="xml"  ) {
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


	public function insert_content_post(){

		$this->common->_filename= "Insert_Leads";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Leads==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Leads==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_data($a_request){

	  	/*if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
	        $this->return_data_token($a_data);
		}*/

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$DocNo=isset($a_request['leadid']) ? $a_request['leadid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  		
	  		if(count($data[0])>0 and $module=="Leads"){
	  			if($action == 'edit'){

					$sql_lead = "select smownerid from aicrm_crmentity where crmid = '".$crmid."' ";
	  				$query = $this->db->query($sql_lead);
					$lead = $query->result_array();
					$d_smownerid = $lead[0]['smownerid'];
					$smownerid=isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
					//alert($d_smownerid); exit;
	  			}

	  			list($chk,$crmid,$DocNo,$name,$no)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				if($action == 'edit'){

	  					if($d_smownerid != $smownerid){
	  						$data[0]['lead_no']=$no;
	  						$this->set_notification($data[0],$crmid,$smownerid,$userid);
	  					}
	  				}

	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'LeadsNo' => $DocNo,

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

	public function mergeAccount_post()
	{
		$request_body = file_get_contents('php://input');
		$param = json_decode($request_body,true);

		$sql = $this->db->get_where('message_customer', array('parentid'=>$param['leadID'], 'module'=>'Leads'));
		$cusMsg = $sql->row_array();

		$this->db->update('message_customer', array('parentid'=>$param['accountID'], 'module'=>'Accounts'), array('customerno'=>$cusMsg['customerno']));
		$this->db->update('aicrm_leaddetails', array('accountid'=>$param['accountID']), array('leadid'=>$param['leadID']));

		switch($cusMsg['channel']){
			case 'line':
				$this->db->update('aicrm_account', array('socialid'=>$cusMsg['customerno'], 'socialname'=>$cusMsg['socialname']), array('accountid'=>$param['accountID']));
				break;
			case 'facebook':
				$this->db->update('aicrm_account', array('fbid'=>$cusMsg['customerno'], 'fbname'=>$cusMsg['socialname']), array('accountid'=>$param['accountID']));
				break;
		}

		$sql = $this->db->get_where('aicrm_account', array('accountid'=>$param['accountID']));
		$result = $sql->row_array();

		$this->response($result, 200);
	}

	public function get_lead_post(){
		$this->common->_filename= "Detail_Leads";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "0";

	  	$this->common->set_log('Before Detail Leads==>',$url,$a_request);
		$response_data = $this->leads_model->get_lead($a_request);

		$get_case = $this->case_list($crmid);
	  	$get_deal = $this->deal_list($crmid);
	  	$get_visit = $this->visit_list($crmid);

	  	$response_data['case'] = $get_case;
	  	$response_data['deal'] = $get_deal;
	  	$response_data['visit'] = $get_visit;

		// alert($response_data);exit;
	  	$this->common->set_log('After Detail Leads==>',$a_request,$response_data);
	  
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
		
		$method = 'Leads';
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
		$a_param["Value3"] = 'Leads';
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
		
		$message ='';
		$message = 'Lead No : '.$data['lead_no'].'\nชื่อลูกค้า  : '.$data['firstname'].' '.$data['lastname'].'\nเบอร์มือถือ : '.$data['mobile'];

		return $message;
	}

	
	/*private function get_insert_data($a_request){

	  	if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['Type'] = "E";
	        $a_data['Message'] = 'Access Token not found';
	        $a_data['time'] = date("Y-m-d H:i:s");
	        $a_data["data"]["data"] = '';
	        $a_data["data"]['total'] = 0;
	        $a_data['offset'] = 0;
	        $a_data['limit'] = 20;
	        return $a_data;
		}

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$DocNo=isset($a_request['leadid']) ? $a_request['leadid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  		
	  		if(count($data[0])>0 and $module=="Leads"){
	  			
	  			if($data[0]['channel'] == 'Web Portal'){
	  				$data[0]['firstname_eng'] = $data[0]['firstname'];
	  				$data[0]['lastname_eng'] = $data[0]['lastname'];
	  				$data[0]['lead_status'] = 'Open';
	  				$data[0]['emailstatus'] = 'Active';
	  				$data[0]['smsstatus'] = 'Active';
	  				$data[0]['smownerid'] = '1';
	  			}
	  			//alert($data); exit;
	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'LeadsNo' => $DocNo,

	  				);
	  			}else{
	  				$a_return  =  array(
	  						'Type' => 'E',
	  						'Message' => 'Unable to complete transaction',
	  				);
	  			}
	  	}else{//echo "ddd";
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}
	  	return array_merge($this->_return,$a_return);
	}*/

}