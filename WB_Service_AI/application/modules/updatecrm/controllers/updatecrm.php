<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once('src/OAuth2/Autoloader.php');
require_once(APPPATH.'/libraries/REST_Controller.php');
//ini_set('display_errors', 1);

class Updatecrm extends REST_Controller
{
	
	private $crmid;
	private $tab_name_leads = array('aicrm_crmentity','aicrm_leaddetails','aicrm_leadsubdetails','aicrm_leadaddress','aicrm_leadscf');
	private $tab_name_index_leads = array('aicrm_crmentity'=>'crmid','aicrm_leaddetails'=>'leadid','aicrm_leadsubdetails'=>'leadsubscriptionid','aicrm_leadaddress'=>'leadaddressid','aicrm_leadscf'=>'leadid');

	private $tab_name_accounts = array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_accountshipads','aicrm_accountscf');
    private $tab_name_index_accounts = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid');
	  	
  	private $tab_name_contacts =  array('aicrm_crmentity','aicrm_contactdetails','aicrm_contactaddress','aicrm_contactsubdetails','aicrm_contactscf','aicrm_customerdetails');
	private $tab_name_index_contacts = array('aicrm_crmentity'=>'crmid','aicrm_contactdetails'=>'contactid','aicrm_contactaddress'=>'contactaddressid','aicrm_contactsubdetails'=>'contactsubscriptionid','aicrm_contactscf'=>'contactid','aicrm_customerdetails'=>'customerid');

	function __construct()
	{
		parent::__construct();
	    $this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->load->library('lib_socket');
		$this->_limit = 20;
		$this->_format = "array";
		$this->_module = 'CRM';
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

		$this->common->_filename= "Update_CRM";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	//alert($a_request); exit;
	  	$this->common->set_log('Before Insert CRM==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);
	  	$this->common->set_log('After Insert CRM==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_data($a_request){

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
		$DocNo=isset($a_request['leadid']) ? $a_request['leadid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$customerid=isset($a_request['customerid']) ? $a_request['customerid'] : "0";
	  	
	  		if(count($data[0])>0 and $module=="Leads"){

	  			list($chk,$crmid,$DocNo) = $this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name_leads,$this->tab_name_index_leads,$data,$userid);
			
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  					'Crmid' => $crmid,
	  					'LeadsNo' => $DocNo,
	  				);
	  				
	  				if(empty($data[0]['newsocialname'])){
	  					
	  					if(isset($data[0]['chat_status'])){

	  						$ins_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group)
								SELECT message_customer.parentid, message_customer.module, message_customer.customerid ,'".$data[0]['chat_status']."' ,'".date('Y-m-d H:i:s')."' , '".$userid."', max(aicrm_sla.sla_group) as sla_group  FROM message_customer
								inner join aicrm_sla on aicrm_sla.customerid = message_customer.customerid
								WHERE message_customer.parentid = '".$crmid."'; ";
							$this->db->query($ins_sla);
	  					}
		  				//$this->emit_socket($crmid,'Agent');
	  					$this->lib_socket->emit_socket_updatecrm($crmid,'Agent',$module);
		  			}

		  			if($data[0]['chat_status'] == 'เสร็จสิ้น'){
						$up_assign = 'update message_customer set smownerid = "0" where customerid = "'.$customerid.'"; ';
						$this->db->query($up_assign);
					}
					/*Log Change Assibg To*/
	  				$l_data = array(
						'userid' => $userid,
						'customerid' => $customerid,
						'smownerid' => 0,
						'chatstatus' => $data[0]['chat_status'],
						'action' => 'Change Status'
						);
					$this->db->insert('message_assignto',$l_data);
					/*Log Change Assibg To*/
	  			}else{
	  				$a_return  =  array(
	  					'Type' => 'E',
	  					'Message' => 'Unable to complete transaction',
	  				);
	  			}
	  		}else if(count($data[0])>0 and $module=="Accounts"){
	  			//alert($a_request); exit;
	  			//tab_name_contacts
	  			//tab_name_index_contacts
		     	$a_condition["message_customer.customerid"] = $customerid;

		     	$this->db->select("message_customer.contactid");
				$this->db->where($a_condition);
				$query = $this->db->get('message_customer');
				$a_contacts = $query->result_array();
				//alert($a_contacts[0]['contactid']); exit;
				$module = 'Contacts';
				$contactid = @$a_contacts[0]['contactid'];
				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$contactid,$action,$this->tab_name_contacts,$this->tab_name_index_contacts,$data,$userid);
	  			/*list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name_accounts,$this->tab_name_index_accounts,$data,$userid);*/
				
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  					'Crmid' => $contactid,
	  					'AccountsNo' => $DocNo,
	  				);
	  				if(empty($data[0]['newsocialname'])){
	  					
	  					if(isset($data[0]['chat_status'])){

							$ins_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group)
								SELECT message_customer.contactid, 'Contacts', message_customer.customerid ,'".$data[0]['chat_status']."' ,'".date('Y-m-d H:i:s')."' , '".$userid."', max(aicrm_sla.sla_group) as sla_group  FROM message_customer
								inner join aicrm_sla on aicrm_sla.customerid = message_customer.customerid
								WHERE message_customer.contactid = '".$contactid."'; ";
							$this->db->query($ins_sla);
	  					}

	  					$this->lib_socket->emit_socket_updatecrm($contactid,'Agent',$module);
		  			
		  			}

		  			if($data[0]['chat_status'] == 'เสร็จสิ้น'){
						$up_assign = 'update message_customer set smownerid = "0" where customerid = "'.$customerid.'"; ';
						$this->db->query($up_assign);
					}

					/*Log Change Assibg To*/
	  				$l_data = array(
						'userid' => $userid,
						'customerid' => $customerid,
						'smownerid' => 0,
						'chatstatus' => $data[0]['chat_status'],
						'action' => 'Change Status'
						);
					$this->db->insert('message_assignto',$l_data);
					/*Log Change Assibg To*/

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

  	//public function emit_socket($customerid = '',$displayName=''){
  	/*public function emit_socket_updatecrm($crmid = '',$displayName=''){
		if($crmid == '' ){
			return false;
		}
		$sql = "select customerid from message_customer where parentid = '".$crmid."'; ";
		$query = $this->db->query($sql);
		$a_result  = $query->result_array() ;
		$customerid = @$a_result[0]['customerid'];

		$this->load->model("updatecrm_model"); 
		//echo 7777; exit;
		$data = $this->updatecrm_model->get_list_socket($customerid); //alert($data['result']['data']); exit;
		

		$msg = array(
			"newchat" => $data['result']['data'],
			//"newmessage" => $data_message['result']['data'],
			"displayName" =>$displayName
		);
		
		$port = $this->config->item('socketIOPort');

		require_once '/SocketIO.php';
		//$client = new SocketIO('localhost',$port);
		$client = new SocketIO('moaioc.moai-crm.com',$port);
		//$client = new SocketIO('localhost',$port);
		$client->setQueryParams([
		    'token' => 'edihsudshuz',
		    'id' => '8783',
		    'cid' => '344',
		    'cmp' => 2339
		]);
		
		$success = $client->emit('update_customer', ['agentid' => $displayName,'customerid' => $customerid ,'msg'=>$msg]);
	
		if(!$success)
		{
		    var_dump($client->getErrors());
		}
		else{
		    var_dump("Success");
		}
		return true;
	}*/

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