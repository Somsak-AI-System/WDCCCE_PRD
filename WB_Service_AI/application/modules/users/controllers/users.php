<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller
{
  private $crmid;
  private $tab_name = array('aicrm_users','aicrm_attachments','aicrm_user2role','aicrm_asteriskextensions');
  private $tab_name_index = array('aicrm_users'=>'id','aicrm_attachments'=>'attachmentsid','aicrm_user2role'=>'userid','aicrm_asteriskextensions'=>'userid');
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("users_model");
	$this->_module = "Users";
    $this->_limit = 10;
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
	
	// $dsn  = 'mysql:dbname=' . $this->config->item('oauth_db_database') . ';host=' . $this->config->item('oauth_db_host');
	// $dbusername = $this->config->item('oauth_db_username');
	// $dbpassword = $this->config->item('oauth_db_password');
	// OAuth2\Autoloader::register();
	
	// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
	// $storage = new OAuth2\Storage\Pdo(array(
	// 	'dsn' => $dsn,
	// 	'username' => $dbusername,
	// 	'password' => $dbpassword
	// ));
	// // Pass a storage object or array of storage objects to the OAuth2 server class
	// $this->oauth_server = new OAuth2\Server($storage);
	// // Add the "Client Credentials" grant type (it is the simplest of the grant types)
	// $this->oauth_server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
	// // Add the "Authorization Code" grant type (this is where the oauth magic happens)
	// $this->oauth_server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
  }

  	public function getuser_post(){
		$this->common->_filename= "User_getdetail";
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
        $a_request = $dataJson;
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->set_log('Get Login==>',$url,$a_request);

        $a_data = $this->get_user($a_request);
        
        $this->return_data($a_data);
    }

  	private function get_user($a_params=array())
    {
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
            
            if($a_params['userid'] == ''){
				
  				$data['status'] = false;
	            $data['error'] = 'Userid not found';
	            $data['time'] = date("Y-m-d H:i:s");
	            $data["data"]["data"] = '';
	            $data["data"]['total'] = 0;
	            $data['offset'] = 0;
	            $data['limit'] = 20;
	        
	            return $data;
			}

            $a_list=$this->users_model->get_list($a_condition,$a_order,$a_limit);


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

    private function set_param($a_param = array())
    {
        $a_condition = array();
        
        if(isset($a_param["userid"]) && $a_param["userid"] != "") {
        	$a_condition["aicrm_users.id"] = $a_param["userid"];
        }
        
        $a_condition["aicrm_users.deleted"] = '0';
        $a_condition["aicrm_users.status"] = 'Active';
        
        return $a_condition;
    }

    private function get_data($a_data)
    {
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
	

	public function login_post(){
		$this->common->_filename= "User_Login";
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$response_data = null;
		$a_request =$dataJson;
		$response_data = $this->get_detail_data($a_request);
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->set_log($url,$a_request,$response_data);
		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
		$this->response(array(
			'error' => 'Couldn\'t find data!'
			), 404);
		}
	}

	private function get_detail_data($a_request){
		$this->load->library('managecached_library');
		$response_data = null;
		$user=isset($a_request['user']) ? $a_request['user'] : "";
		$pass=isset($a_request['pass']) ? $a_request['pass'] : "";
		$account_no=isset($a_request['account_no']) ? $a_request['account_no'] : "";

		$limit = isset($a_request['limit']) ? $a_request['limit'] : '1';
		$offset = isset($a_request['offset']) ? $a_request['offset'] : '0';
		$language = isset($a_request['language']) ? $a_request['language'] : 'TH';
		$clear_cache = isset($a_request['clear_cache']) ? true : false;

		if($user!='' and $pass!=''){
			$module="Users";
			$cache_key = 'api_detail_'.$module;
			$cache_key = strtolower($cache_key);

			$cache_data = null;
			if($clear_cache){
				@$this->memcached_library->delete($cache_key);
			}else{
				$cache_data = @$this->memcached_library->get($cache_key);

			}
			if($cache_data){
				$response_data = $cache_data;
			}else{
				$tabid=$this->crmentity->Get_Tab_ID($module);
				$data_field = $this->crmentity->Get_Field_Show($tabid,"");
				$field_select="";
				if(count($data_field)>0){
					$field_select=$this->crmentity->Get_Select_Field($data_field);
				}else{
					$field_select="aicrm_crmentity.crmid";
				}
				
				$sql_select="select  aicrm_users.id AS user_id, aicrm_users.user_name, aicrm_users.is_admin, aicrm_users.position as section, aicrm_users.email1 as branchid, aicrm_users.create_task, aicrm_users.status,aicrm_groups.groupid , '".$language."' as user_language , aicrm_users.first_name , aicrm_users.last_name";

				//fix cf_2034
        		$sql_from=$this->crmentity->Get_Query($module);
				$sql_where="";
				if($user!=""){
					$sql_where.= " and user_name='".$user."' ";
				}
				if($pass!=""){
					$salt = substr($user, 0, 2);
					$salt = '$1$' . $salt . '$';
					$encrypted_password = crypt($pass, $salt);
					$sql_where.= " and user_password ='".$encrypted_password."' ";
				}

				// $sql_where.= " and status = 'Active' and moaimb = 'on' ";
				$sql_where.= " and status = 'Active' and moaimb = '1' ";
				
				$sql_group="";
				$sql_order="";
				$sql_limit = " LIMIT $offset, $limit";
				$query = $this->db->query($sql_select.$sql_from.$sql_where.$sql_group.$sql_order.$sql_limit);
				// echo $sql_select.$sql_from.$sql_where.$sql_group.$sql_order.$sql_limit; exit;
				if($query->num_rows()>0){
					$response_data['Type'] = "S";
					$response_data['Message'] = "Log in Complete";
					$response_data['cache_time'] = date("Y-m-d H:i:s");
					$response_data['total'] = $query->num_rows();
					$response_data['offset'] = $offset;
					$response_data['limit'] = $limit;

					$rows=$query->num_rows();
					$data=$query->result_array();
					$userid=$data[0]['user_id'];
					
					$sql_history = "INSERT INTO ai_check_user_login_system (`user_id` ,`username` ,`ipaddress` ,`use_date` ,`sysytem_name` ,`status`) VALUES
					('".$data[0]['user_id']."', '".$data[0]['user_name']."', '', now(), 'Login Mobile Application', '1');";

					$query = $this->db->query($sql_history);

					$sql_language = "Update aicrm_users set user_language = '".$language."' where id = '".$data[0]['user_id']."' ";
					$this->db->query($sql_language);

					$branchid=array();
					for($i=0;$i<count($data);$i++){
						$data_field=array_keys($data[$i]);
						$data_value=array_values($data[$i]);
						for($j=0;$j<count($data_field);$j++){
							if($data_field[$j]=="imagename"){
								
								$data_v2[$data_field[$j]]=$this->crmentity->Get_Image($module,$this->crmid);
							}else{
								$data_v2[$data_field[$j]]=$data_value[$j];
								
							}
						}
						
						$data_v1[]=$data_v2;
					}
					$response_data['data']=$data_v1[0];

					$sql_org = 'SELECT project_code FROM  aicrm_organizationdetails limit 1 ';
					$query_org = $this->db->query($sql_org);
					$data_org = $query_org->result_array();

					$response_data['data']['project_code'] = @$data_org[0]['project_code'];
					$user_lang = "EN";
					$user_lang = $this->crmentity->Get_userlang($userid);
		  			
					$response_data['data']['project_code'] = @$data_org[0]['project_code'];
					$response_data['data']['language'] = @$user_lang;
					$response_data['data']['upload_setting'] = $this->uploadSetting();
				}else{
					$response_data['Type'] = "E";
					$response_data['Message'] = 'You must specify a valid username and password!';
					$response_data['cache_time'] = date("Y-m-d H:i:s");
					$response_data['total'] = 0;
					$response_data['offset'] = $offset;
					$response_data['limit'] = $limit;
				}
			}
		}else{
			$response_data['Type'] = "E";
			$response_data['Message'] = 'Username and Password is Null';
			$response_data['cache_time'] = date("Y-m-d H:i:s");
			$response_data['total'] = 0;
			$response_data['offset'] = $offset;
			$response_data['limit'] = $limit;
		}

		return $response_data;
	}

	private function uploadSetting()
	{	
		$sql = $this->db->get('aicrm_setting_upload_channel');
		$rs = $sql->result_array();

		$result = [];
		foreach($rs as $row){
			$result[] = $row;
			if($row['module'] == 'Sales Visit'){
				$row['module'] = 'Calendar';
				$result[] = $row;
			}
		}

		array_walk_recursive($result, function (&$item, $key) {
            $item = null === $item ? '0' : $item;
        });

		return $result;
	}

	private function register_token($a_user=array(),$a_request=array())
	{
		if(empty($a_user)) return "";
		if(empty($a_request)) return "";
		$this->load->config('config_notification');
		$config = $this->config->item('notification');

		$user_id = $a_user[0]["user_id"];
		$token = $a_request["token"];
		$device = $a_request["device"];
		$mobile_version = $a_request["mobile_version"];

		$a_param["Value1"] = $user_id;
		$a_param["Value2"] = $token;
		$a_param["Value3"] = $device;
		$a_param["Value4"] = $mobile_version;

		$method = "RegisterToken";


		$url = $config["url"].$method;

		$this->load->library('curl');
		$this->common->_filename= "Insert_Token";
		$this->common->set_log($url."_Begin",$a_param,array());
		$s_result = $this->curl->simple_post($url, $a_param,array(),"json");
		$this->common->set_log($url."_End",$a_param,$s_result);
		$this->common->_filename= "User_Login";
		return $s_result;
	}

	private function get_update_pass_data($a_request){
		$this->load->library('managecached_library');
		$response_data = null;
		$accountid=isset($a_request['accountid']) ? $a_request['accountid'] : "";
		$user=isset($a_request['user']) ? $a_request['user'] : "";
		$pass=isset($a_request['pass']) ? $a_request['pass'] : "";
		$limit = isset($a_request['limit']) ? $a_request['limit'] : '24';
		$offset = isset($a_request['offset']) ? $a_request['offset'] : '0';
		$clear_cache = isset($a_request['clear_cache']) ? true : false;
		if($accountid!='' and $user!=''){
			$module="Accounts";
			$cache_key = 'api_account_update_pass_'.$module;
			$cache_key = strtolower($cache_key);

			$cache_data = null;
			if($clear_cache){
				@$this->memcached_library->delete($cache_key);
			}else{
				$cache_data = @$this->memcached_library->get($cache_key);
			}
			if($cache_data){
				$response_data = $cache_data;
			}else{
				$sql="update aicrm_accountscf set cf_1581='".$pass."'
				where `accountid`='".$accountid."'
				";
				if($this->db->query($sql)){
					$sql="update aicrm_account set email1='".$user."'
					where `accountid`='".$accountid."'
					";
					if($this->db->query($sql)){
						$sql="update aicrm_crmentity set modifiedby=1 ,modifiedtime=NOW()
						where crmid='".$accountid."'
						";
						if($this->db->query($sql)){
							$this->response(array(
								'Type' => 'S',
								'message' => 'Update Complete!',
								'cache_time' => date("Y-m-d H:i:s"),
								'total' => "",
								'offset' => "",
								'limit' =>"",
							  ), 200);
						}else{
							$this->response(array(
								'Type' => 'E',
								'message' => 'Can not Update!',
								'cache_time' => date("Y-m-d H:i:s"),
								'total' => "",
								'offset' => "",
								'limit' =>"",
							  ), 404);
						}
					}else{
						$this->response(array(
							'Type' => 'E',
							'message' => 'Can not Update!',
							'cache_time' => date("Y-m-d H:i:s"),
							'total' => "",
							'offset' => "",
							'limit' =>"",
						  ), 404);
					}
				}else{
					$this->response(array(
						'Type' => 'E',
						'message' => 'Can not Update!',
						'cache_time' => date("Y-m-d H:i:s"),
						'total' => "",
						'offset' => "",
						'limit' =>"",
					  ), 404);
				}
				$this->managecached_library->set_memcache($response_data,"2400");
			}
		}else{
			$this->response(array(
				'Type' => 'E',
				'message' => 'Invalid Request!',
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "",
				'offset' => "",
				'limit' =>"",
			  ), 404);
		}
	}

	public function register_post()
	{
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);

		$this->common->_filename= "Register_User";
	  	$this->common->set_log("Parameter ==> ",$dataJson,array());

		$response_data = null;
	  	$a_request =$dataJson;

	  	$response_data = $this->get_insert_data($a_request);
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->common->_filename= "Register_User";
	  	$this->common->set_log($url,$a_request,$response_data);

		if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
  	}


    private function get_insert_data($a_request)
    {

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";

		$user=isset($a_request['data'][0]['user_name']) ? $a_request['data'][0]['user_name'] : "";
		$pass=isset($a_request['data'][0]['user_password']) ? $a_request['data'][0]['user_password'] : "";
		if($user == '' || $pass == '' ){
			$a_return  =  array(
				'Type' => 'E',
				'Message' =>  'Invalid Request!',
				'cache_time' => date("Y-m-d H:i:s"),
			);
			return $a_return;
		}

		//Check Username
		$sql="select  * from aicrm_users where aicrm_users.deleted=0 and aicrm_users.user_name = '".$user."' ";
		$query = $this->db->query($sql);
		$rows=$query->num_rows();
		$data_field1=$query->result_array();

	 	if(!empty($data_field1)){
			$a_return  =  array(
				'Type' => 'E',
				'Message' =>  'This username already exists on online system',
				'cache_time' => date("Y-m-d H:i:s"),
			);
			return $a_return;
		}
		//Check Username

		$salt = substr($user, 0, 2);
		$salt = '$1$' . $salt . '$';
		$encrypted_password = crypt($pass, $salt);
		$data[0]['user_password'] = $encrypted_password;
	  		if(count($data[0])>0 and $module=="Users"){

	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data);

	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Register Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
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

  
	public function update_language_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		$response_data = null;

		$a_request =$dataJson;

			$response_data = $this->get_update_lang($a_request);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= "Insert_Job";
		$this->common->set_log($url,$a_request,$response_data);
		if ( $response_data ) {
		  $this->response($response_data, 200); // 200 being the HTTP response code
		} else {
		  $this->response(array(
			  'error' => 'Couldn\'t find Set Content!'
		  ), 404);
		}

	}
  
    public function get_update_lang($a_request){
		$chk=0;
		$response_data = null;
		$module=isset($a_request['module']) ? $a_request['module'] : "";
		$userid=isset($a_request['userid']) ? $a_request['userid'] : "";
		$action=isset($a_request['action']) ? $a_request['action'] : "";
		$user_language=isset($a_request['user_language']) ? $a_request['user_language'] : "";
	  
		$sql = "update aicrm_users set user_language='".$user_language."'  where aicrm_users.id='".$userid."' ";
	  
		if($this->db->query($sql)){
	  
		}else{$chk=1;}
	  
	  
		  if($chk=="0"){
			  // $this->set_notification($data,$crmid,$smownerid);
			  $a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
			  $a_return["data"] =array(
				  'userid' => $userid,
	  
			  );
			}else{
			  $a_return  =  array(
				  'Type' => 'E',
				  'Message' => 'Unable to complete transaction',
			  );
			}
	  
		return array_merge($this->_return,$a_return);
  	}

  	public function get_role_post()
	{
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);
		$userID = $req['userid'];

		$userData = $this->common->get_role($userID);

		$role = [];
		foreach($userData as $row){
			$role[] = $row['roleid'];
		}

		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['role'] = $role;

		$this->response($returnData, 200);
	} 
  
}
