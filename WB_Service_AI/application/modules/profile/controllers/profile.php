<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Profile extends REST_Controller
{

	private $crmid;
		private $tab_name = array('aicrm_users');
		private $tab_name_index = array('aicrm_users'=>'id');
  function __construct()
  {
    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->config('config_module');
		$this->load->library('lib_api_common');
    $this->load->database();
		$this->load->library("common");
		$this->load->model("profile_model");
		$this->_format = "array";
		$this->_return = array(
				'Type' => "S",
				'Message' => "Insert Complete",
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "1",
				'offset' => "0",
				'limit' => "1",
				'data' => array(
						'Crmid' => "",
				),
		);
	}

	public function change_password_post(){

		//header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		//$this->social = 'twitter';
		// $response_data = null;

		//$a_request = $this->input->post(NULL, TRUE);
		$a_request =$dataJson;
		$userid = $a_request['userid'];
		$action = $a_request['action'];
		$username = $a_request['data'][0]['username'];
		$currentpass = $a_request['data'][0]['currentpassword'];
		$newpass = $a_request['data'][0]['newpassword'];
		$confirmpass = $a_request['data'][0]['confirmpassword'];

	$data = $this->profile_model->changePass_post($userid,$username,$currentpass,$newpass,$confirmpass);

	$response_data = $this->get_return($data,$action);


		// return array_merge($this->_return,$a_return);
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= "Change_Pass_Users";
		$this->common->set_log($url,$dataJson,$response_data);
		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
					'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}


	public function get_return($data,$action){
			$crmid = $data['crmid'];
			$message = $data['Message'];
				 if($crmid!=null){
					 // $this->set_notification($data,$crmid,$smownerid);
					 $a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
					 $a_return["data"] =array(
							 'userid' => $crmid,
					 );

				 }elseif($message!=null){
					$a_return  =  array(
						'Type' => 'E',
						'Message' => $message,
					);

				 }else{
					 $a_return  =  array(
							 'Type' => 'E',
							 'Message' => 'Unable to complete transaction',
					 );
				 }
				 return array_merge($this->_return,$a_return);
	}


  public function edit_Profile_post(){

 	//header('Content-Type:application/json; charset=UTF-8');
	$request_body = file_get_contents('php://input');
	$dataJson     = json_decode($request_body,true);

	//$this->social = 'twitter';
	$response_data = null;

	//$a_request = $this->input->post(NULL, TRUE);
	$a_request =$dataJson;

	$response_data = $this->get_insert_data($a_request);

	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$this->common->_filename= "Edit_Users";
	$this->common->set_log($url,$dataJson,$response_data);
		 
	if ( $response_data ) {
		$this->response($response_data, 200); // 200 being the HTTP response code
	} else {
		$this->response(array(
			'error' => 'Couldn\'t find Set Content!'
		), 404);
	}
 }



	public function get_insert_data($a_request){

  	$response_data = null;
  	$module=isset($a_request['module']) ? $a_request['module'] : "";
  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "";
  	$action=isset($a_request['action']) ? $a_request['action'] : "";
  	$data=isset($a_request['data']) ? $a_request['data'] : "";

  		if(count($data[0])>0 and $module=="Users"){

				$this->load->config('config_module');

				$config = $this->config->item('module');
				$configModule = $config['Users'];
				$tab_name = $configModule['tab_name'];
				$tab_name_index = $configModule['tab_name_index'];

			list($chk,$crmid)=$this->crmentity->Insert_Update($module,$userid,$action,$tab_name,$tab_name_index,$data);

			if($chk=="0"){
					// $this->set_notification($data,$crmid,$smownerid);
  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
  				$a_return["data"] =array(
  						'userid' => $crmid,
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


	private function get_cache($a_params=array())
	{
		// get form action = 'get'
		// get related action = 'relate'
		$this->load->library('managecached_library');

		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$module = @$a_params["module"];
		$action = @$a_params["action"];
		$userid = @$a_params["userid"];
		$crmid= @$a_params["userid"];
		$a_data = $this->managecached_library->get_memcache($a_cache);

				if($a_data===false)
			{
				$a_list = $this->lib_api_common->Get_Block($module,$action,$crmid);
				$a_cache["data"]["time"] = date("Y-m-d H:i:s");
				$this->managecached_library->set_memcache($a_cache,"2400");
			}

		return $a_list;

	}


  	public function return_data($a_data,$a_param)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = 0;
			$a_return["offset"] = 0;
			$a_return["limit"] = 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"][0]["jsonrpc"] = "2.0";
			$a_return["data"][0]["id"] = "";
			$a_return["data"][0]["result"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;

			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			// $this->common->_filename= "Insert_Calendar";
			$this->common->_filename= "Detail_Users";
			$this->common->set_log($url,$a_param,$a_return);

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



	public function list_profile_get()
	{
		$a_param =  $this->input->get();

		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,$a_param);
	}

	public function list_profile_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			// $this->common->_filename= "Insert_Calendar";
		$this->common->_filename= "Detail_Users";
		$this->common->set_log($url,"parameter",$a_param);

		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,$a_param);

	}


	private function get_cachedata($a_params=array())
	{

		$this->load->library('managecached_library');

		$a_cache = array();
    $a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$optimize = @$a_params['optimize'];

		$userid = @$a_params['userid'];


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

		$ipaddress=gethostbyaddr($_SERVER['REMOTE_ADDR'])." (".$_SERVER["REMOTE_ADDR"].")";

		if($userid != ''){
			$queryhistory ="INSERT INTO ai_check_user_login (user_id ,username,ipaddress,start_time,end_time,sysytem_name )
			Select  '".$userid."', aicrm_users.user_name, '".$ipaddress."', '".date('Y-m-d h:i:s')."', '0000-00-00 00:00:00'  , 'Mobile'
			from aicrm_users WHERE aicrm_users.id = '".$userid."' ";
			$this->db->query($queryhistory);

		}
		if($a_data===false)
		{

			$a_list=$this->profile_model->get_list($a_condition,$a_order,$a_limit,$a_limit,$a_params);

			// $a_data = $this->get_data($a_list,$optimize);
			$a_data = $a_list;

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


		private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["userid"]) && $a_param["userid"]!="") {
			$a_condition["aicrm_users.id"] =  $a_param["userid"] ;
		}

		return $a_condition;
	}
	private function set_order($a_orderby=array())
	{
		if(empty($a_orderby)){
			$a_order[0]["field"] = "aicrm_users.user_name";
			$a_order[0]["order"] = "";
			return $a_order;
		}
		if($a_orderby == 'aicrm_users.id,asc'){
			$a_order[0]["field"] = "aicrm_users.user_name";
			$a_order[0]["order"] = "";
			return $a_order;
		}

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

	function userForm_post(){
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$log_filename = "Detail_Users";
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,"parameters",$a_param);

		$a_data =$this->get_cachedata($a_param);
		$this->return_data($a_data,$a_param);
	}

	public function imageprofile_post(){

		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$result = $this->profile_model->get_imageprofile($a_param);
		$a_return["Message"] =  "";
		$a_return["data"] = array();

		$data = array(
			'userid' => $result['userid'],
			'image'=> $result['image']
		);

		array_push($a_return["data"] , $data);

		$response_data = array_merge($this->_return,$a_return);

		$log_filename = "Get_Image_Profile";
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$a_param,$response_data);
		
		if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
		}


	}


	public function upload_imageprofile_post(){

    $request_body = file_get_contents('php://input');
	  $dataJson     = json_decode($request_body,true);
		$a_request =$dataJson['data'][0];
		$a_return["Message"] =  "Upload Complete";

		$log_filename = "Upload_Image_Profile";
		$url_log = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url_log,"parameters-->",$dataJson);


		$file_maximumsize = 1048576; // Maximum size is 1MB
		//$helpdeskid = $crmid;
		$path 		= "storage/".date("Y")."/".date("F")."/week".date("W")."";
		$url = "http://".$_SERVER['HTTP_HOST']."/moaioc/".$path;

		//$folder = $_SERVER['DOCUMENT_ROOT']."/sdcc_uat/";
		global $root_directory;

		$folder = $root_directory;
		$temp_path 	= $folder."/".$path;

		//check folder
		if (!is_dir($temp_path)) {

			$folder2 = $folder.'/storage';

			// Find each subfolder in storage's image
			$storage_folder = explode('storage/' , $temp_path);
			$new_folder = explode('/' , $storage_folder[1]);

			for($i=0; $i<=count($new_folder)-2; $i++){

				   //Create subfolder if it not found folder
				   $folder2 = $folder2."/".$new_folder[$i];

					if (!is_dir($folder2)) {
						//echo "<br/>##".$folder2;
						$old = umask(0);
						mkdir($folder2 ."/" ,0777,true);
						umask($old);

					}
			 }

		 $old = umask(0);
		 mkdir($temp_path ."/" ,0777,true);
		 umask($old);

	    }//if check folder

		$a_return["data"] = array();
		$data = array();

		for($i=0; $i<count($a_request['image']); $i++){

			//Get Crmid
			$sql_next_id = "SELECT max(attachmentsid)+1 as next_id FROM aicrm_attachments";
			$query_next_id = $this->db->query($sql_next_id);
			$result_next_id = $query_next_id->result(0);
			$next_id = $result_next_id[0]['next_id'];

			// $sql_insert_new1 = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$a_request['smownerid']."', '".$a_request['smownerid']."', 'Calendar Image', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
			// $sql_insert_new2 = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$a_request['crmid']."' , '".$next_id."')";

			// $sql_insert_new1 = "INSERT INTO aicrm_attachments (attachmentsid,name,description,type,path,subject) VALUES ('".$next_id."', '".$name."', '', '".$file_type."', '".$filepath."', '')";
			// $sql_update = "UPDATE aicrm_users SET imagename='".$name."' where id='".$a_request['userid']."'";

			// $this->db->query($sql_insert_new1);
			// $this->db->query($sql_update);

				//Insert Image
				$file_type = "image/jpeg";
				$image_name = $next_id.".jpg";
				$binary = base64_decode($a_request['image'][$i]);
				//echo $binary."<br>";
				// binary, utf-8 bytes
				header("Content-Type: bitmap; charset=utf-8");
				$file = "../".$path."/" . $image_name;
				$image_upload = fopen($file, "w");
				//$file = fopen("../".$path."/" . $image_name, "w");
				$filepath = $image_name;
				fwrite($image_upload, $binary);
				fclose($image_upload);
				chmod($file, 0777);

			$sql_insert_new1 = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('".$next_id."' , '".$image_name."', '".$file_type."', '".$path."/')";
			$sql_update = "UPDATE aicrm_users SET imagename='".$image_name."' where aicrm_users.id='".$dataJson['userid']."'";
			$insert = $this->db->query($sql_insert_new1);
			$update = $this->db->query($sql_update);

			$data = array(
				'userid' => $dataJson['userid'],
		    	// 'modifiedby' => $a_request["smownerid"],
		    	'modifiedtime' => date("Y-m-d H:i:s"),
			);

			array_push($a_return["data"] , $data);

		}// for

		$response_data = array_merge($this->_return,$a_return);
		if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
		}

	}

}
