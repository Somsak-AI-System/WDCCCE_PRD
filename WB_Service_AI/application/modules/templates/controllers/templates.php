<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class Templates extends REST_Controller
{
  private $crmid;
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("templates_model");
		$this->_module = "Templates";
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

	public function gettemplates_post(){
		$this->common->_filename= "Templates_gettemplates";
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
        $a_request = $dataJson;
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->set_log('Get Social==>',$url,$a_request);

        $a_data = $this->get_template($a_request);
        
        $this->return_data($a_data);
    }
	

	private function get_template($a_params=array())
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
            
            $a_list=$this->templates_model->get_list($a_condition,$a_order,$a_limit);
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

	private function set_param($a_param=array())
	{
		$a_condition = array();
	
		if (isset($a_param["templateid"]) && $a_param["templateid"]!="") {
			$a_condition["aicrm_templatesmessage.templateid"] =  $a_param["templateid"] ;
		}

		if (isset($a_param["keyword"]) && $a_param["keyword"]!="") {
			$a_condition["aicrm_templatesmessage.subject like "] =  "%".$a_param["keyword"]."%" ;
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
		if($a_data['result'] != ''){
			
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

	public function insert_content_post(){
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->get_insert_data($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_Template";

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
	  	$templateid=isset($a_request['templateid']) ? $a_request['templateid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";

	  		
	  		if(count($data[0])>0 and $module== "Templates"){
	  				
	  				//$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				if($action=="add"){
	  					
	  					$subject = str_replace("'","''",$data[0]['subject']);
						$body = str_replace("'","''",$data[0]['body']);
	  					$sql = "insert into aicrm_templatesmessage (subject,body) value ('".$subject."','".$body."');";
	  					//echo $sql; exit;
	  					$this->db->query($sql);
	  					
	  					$templateid = $this->db->insert_id();
	  					
	  					$a_return["Message"] = "Insert Complete";
	  					$a_return["data"] =array(
							'Templateid' => $templateid,
						);

		  			}else if($action=="edit" && $templateid != ''){
		  				$subject = str_replace("'","''",$data[0]['subject']);
						$body = str_replace("'","''",$data[0]['body']);
		  				$sql = "UPDATE aicrm_templatesmessage set subject = '".$subject."' , body = '".$body."' where templateid = '".$templateid."'; ";
		  				//echo $sql; exit;
		  				$this->db->query($sql);
		  				$a_return["Message"] = "Update Complete";
		  				$a_return["data"] =array(
							'Templateid' => $templateid,
						);

		  			}else if($action=="deleted" && $templateid != ''){
		  				$sql = 'DELETE FROM aicrm_templatesmessage WHERE templateid = "'.$templateid.'"; ';
		  				$this->db->query($sql);
		  				$a_return["Message"] = "Deleted Complete";
		  				$a_return["data"] =array(
							'Templateid' => $templateid,
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

}