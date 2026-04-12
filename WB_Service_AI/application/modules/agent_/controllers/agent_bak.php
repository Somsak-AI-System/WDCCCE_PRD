<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class Agent extends REST_Controller
{
  private $crmid;
  //private $tab_name = array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_agenthipads','aicrm_agentcf');
  //private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_agenthipads'=>'accountaddressid','aicrm_agentcf'=>'accountid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("agent_model");
		$this->_module = "Agent";
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

	public function getsocial_post(){
		$this->common->_filename= "Agent_getsocial";
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
        $a_request = $dataJson;
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->set_log('Get Social==>',$url,$a_request);

        $a_data = $this->get_social($a_request);
        
        $this->return_data($a_data);
    }
	

	private function get_social($a_params=array())
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
        $a_cache["_ckname"] = $this->_module . '/get_user';
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
            
            $a_list=$this->agent_model->get_list($a_condition,$a_order,$a_limit);
            //alert($a_list); exit;
			$a_data = $this->get_data($a_list);
			$a_data["status"] = $a_list["status"];
			$a_data["error"] = $a_list["error"]  ;
			$a_data["data"] = $a_list["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_list["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
        }
        
        return $a_data;

    }

    public function getsocialdetail_post(){
		$this->common->_filename= "Agent_getsocialdetail";
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
        $a_request = $dataJson;
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->set_log('Get Social Detail==>',$url,$a_request);

        $a_data = $this->get_socialdetail($a_request);
        
        $this->return_data($a_data);
    }

    private function get_socialdetail($a_params=array())
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
        $a_cache["_ckname"] = $this->_module . '/get_user';
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
            
            $a_list=$this->agent_model->get_list_detail($a_condition,$a_order,$a_limit);
            
            if(isset($a_params['customerid']) && $a_params['customerid'] != ''){
            	$this->flag_read($a_params);
            }
            
			
			$a_data = $this->get_data($a_list);
			$a_data["status"] = $a_list["status"];
			$a_data["error"] = $a_list["error"]  ;
			$a_data["data"] = $a_list["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_list["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
        }
        
        return $a_data;

    }

    public function flag_read($a_params){

    	$customerid = $a_params['customerid'];
    	$sql = "UPDATE message_chathistory set flag_read = '1' where customerid = '".$customerid."'; ";
		$this->db->query($sql);
		return true;
    }

    public function deleted_detail_post(){
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->deleted_data($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Deleted_Detail";

	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function deleted_data($a_request){

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
	  	$chatid=isset($a_request['chatid']) ? $a_request['chatid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	
	  		
	  		if($chatid != "" and $module== "Agent"){
	  				if($action=="deleted" && $chatid != ''){
		  				$sql = 'update message_chathistory set deleted = 1 where chatid = "'.$chatid.'"; ';
		  				$this->db->query($sql);
		  				$a_return["Message"] = "Deleted Complete";
		  				$a_return["data"] =array(
							'Chatid' => $chatid,
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



	/*public function insert_content_post(){
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->get_insert_data($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_Agent";

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

	  		if(count($data[0])>0 and $module=="Agent"){
	  			
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
	}*/

	private function set_param($a_param=array())
	{
		$a_condition = array();
	
		if (isset($a_param["customerid"]) && $a_param["customerid"]!="") {
			$a_condition["message_chathistory.customerid"] =  $a_param["customerid"] ;
		}

		if (isset($a_param["message"]) && $a_param["message"]!="") {
			$a_condition["message_chathistory.message LIKE "] =  "%".$a_param["message"]."%" ;
		}

		if (isset($a_param["socialname"]) && $a_param["socialname"]!="") {
			$a_condition["message_customer.socialname LIKE "] =  "%".$a_param["socialname"]."%" ;
		}

		if (isset($a_param["channel"]) && $a_param["channel"]!="" && $a_param["channel"]!="all" ) {
			$a_condition["message_customer.channel"] = $a_param["channel"] ;
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

	/*private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');
		$this->load->model("agent_model");
	
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
			$a_list=$this->agent_model->get_list($a_condition,$a_order,$a_limit);
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
	}*/
	
	private function get_data($a_data)
	{
		if($a_data['result'] != ''){
			
		}
		return $a_data;
	}

	/*public function list_content_get()
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
	}*/
	
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