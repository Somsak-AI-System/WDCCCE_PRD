<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once('src/OAuth2/Autoloader.php');
require_once(APPPATH.'/libraries/REST_Controller.php');

//ini_set('display_errors', 1);

class Rewards extends REST_Controller
{
	
	private $crmid;
	private $tab_name = array('aicrm_crmentity','aicrm_rewards','aicrm_rewardscf');
	private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_rewards'=>'rewardsid','aicrm_rewardscf'=>'rewardsid');
  
	function __construct()
	{
		parent::__construct();
	    $this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->_limit = 20;
		$this->_format = "array";
		$this->_module = 'Rewards';
		$this->load->model("rewards_model");
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
            $a_condition["aicrm_rewards.rewardsid"] = $a_param["crmid"];
        }

        if (isset($a_param["lastupdate"]) && $a_param["lastupdate"] != "") {
        	
        	$start_date=date('Y-m-d', strtotime("-1 days"));
			$last_date=date('Y-m-d');

            $a_condition["LEFT(aicrm_crmentity.modifiedtime, 10) >="] = $start_date;
            $a_condition["LEFT(aicrm_crmentity.modifiedtime, 10) <="] = $last_date;
        }

        //$a_condition["aicrm_rewards.rewards_status"] = 'Active';
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
				$rewardsid = $val["rewardsid"];
				$a_rewards[] = $rewardsid;
			}

			$a_conditionin["aicrm_rewards.rewardsid"] = $a_rewards;
			$a_image = $this->common->get_a_image($a_conditionin,$this->_module);
			
			foreach ($a_data["result"]["data"] as $key =>$val){
				$rewardsid = $val["rewardsid"];
				$a_return = $val;

				$a_return["image"] = (!empty($a_image[$rewardsid]["image"]))?$a_image[$rewardsid]["image"] : array();
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
            $a_data['limit'] = 20;
            return $a_data;
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
            
            $a_list=$this->rewards_model->get_list($a_condition,$a_order,$a_limit);

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

		$this->common->_filename= "Insert_Reward";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Reward==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Reward==>',$a_request,$response_data);
	  
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
  	$action=isset($a_request['action']) ? $a_request['action'] : "";
  	$data=isset($a_request['data']) ? $a_request['data'] : "";
  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
  		
  		if(count($data[0])>0 and $module=="Rewards"){
  			
  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
	
  			if($chk=="0"){
  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
  				$a_return["data"] =array(
  						'Crmid' => $crmid,
  						'RewardNo' => $DocNo,

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
  }

	
	
}