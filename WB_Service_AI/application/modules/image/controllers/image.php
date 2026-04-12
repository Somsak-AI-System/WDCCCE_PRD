<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
*  # Class Parcel
*   ## Webservice Module Parcel พัสดุ
*/
class Image extends REST_Controller
{
	private $crmid;
	private $tab_name = array('aicrm_crmentity','aicrm_jobs','aicrm_jobscf');
	private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_jobs'=>'jobid','aicrm_jobscf'=>'jobid');
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("image_model");
		$this->_limit = 100;
		$this->_module = "Job";
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
	}

	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["module"] == 'Job') {
			$a_condition["aicrm_jobs.jobid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["module"] == 'Calendar') {
			$a_condition["aicrm_activity.activityid"] =  $a_param["crmid"] ;
		}

		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["module"] == 'HelpDesk') {
			$a_condition["aicrm_troubletickets.ticketid"] =  $a_param["crmid"] ;
		}
		
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["module"] == 'Questionnaire') {
			$a_condition["aicrm_questionnaire.questionnaireid"] =  $a_param["crmid"] ;
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
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$a_condition = array();
		$a_condition = $this->set_param($a_params);
		$module = @$a_params['module'];

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
			$a_list=$this->image_model->get_list($a_condition,$a_order,$a_limit,$module);
			$a_data = $this->get_data($a_list,$module);

			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");

			$t=$this->managecached_library->set_memcache($a_cache,"2400");

		}
		// alert($a_data);exit;

		return $a_data;
	}

	private function get_data($a_data=NULL,$module=NULL)
	{

		if(!empty($a_data["result"]["data"]) && $a_data["status"] ){

			if($module == 'Calendar'){
				$crmid = 'activityid';
			}else if($module == 'Job'){
				$crmid = 'jobid';
			}else if($module == 'Questionnaire'){
				$crmid = 'questionnaireid';
			}else if($module == 'HelpDesk'){
				$crmid = 'ticketid';
			}

			// alert($a_data);exit;
			foreach ($a_data["result"]["data"] as $key =>$val){
				$id = $val[$crmid];
				$a_activity[] = $id;
			}
			if($module == 'Calendar'){
				$a_conditionin["aicrm_activity.activityid"] = $a_activity;
			}else if($module == 'Job'){
				$a_conditionin["aicrm_jobs.jobid"] = $a_activity;
			}else if($module == 'Questionnaire'){
				$a_conditionin["aicrm_questionnaire.questionnaireid"] = $a_activity;
			}else if($module == 'HelpDesk'){
				$a_conditionin["aicrm_troubletickets.ticketid"] = $a_activity;
			}

			$a_image = $this->common->get_a_image($a_conditionin,$module);
			foreach ($a_data["result"]["data"] as $key =>$val){
					
					$job_id = $val[$crmid];
					$a_return["image"] =( !empty($a_image[$job_id]["image"]))?$a_image[$job_id]["image"] :array();
					$a_response[] = $a_return;
			}
			$a_data["result"]["data"] = $a_response;
		}


		return $a_data;
	}

	public function list_content_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);
		$module = $a_param['module'];
		$this->return_data($a_data,$module,$a_param);
	}

	public function list_content_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		if($a_param['module'] == 'Sales Visit'){
			$a_param['module'] = 'Calendar';
		}
		
		$module = $a_param['module'];
		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data,$module,$a_param);
	}

	public function return_data($a_data,$module,$a_param)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"] : 0;
			$a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
			$a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;

			$log_filename = "View_Image_".$module;
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			// $this->common->_filename= "Insert_Calendar";
			$this->common->_filename= $log_filename;
			$this->common->set_log($url,$a_param,$a_return);
			// }

			if ($format!="json" && $format!="xml"  ) {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Parcel!'), 404);
		}
	}

	public function upload_image_post(){

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;
		$a_request =$dataJson['data'][0];
		$module = $dataJson['module'];

		$a_return["Message"] =  "Upload Complete";

		$file_maximumsize = 1048576; // Maximum size is 1MB
		$path 		= "storage/".date("Y")."/".date("F")."/week".date("W")."";
		//$url = "https://".$_SERVER['HTTP_HOST']."/MOAIOC/".$path;
		$url = "https://".$_SERVER['HTTP_HOST']."/".$path;
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
			$sql_next_id = "SELECT max(id)+1 as next_id FROM aicrm_crmentity_seq";
			$query_next_id = $this->db->query($sql_next_id);
			$result_next_id = $query_next_id->result(0);
			$next_id = $result_next_id[0]['next_id'];

			$sql_insert_new1 = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$a_request['smownerid']."', '".$a_request['smownerid']."', '".$a_data['module']." Image', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
			$sql_insert_new2 = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$a_request['crmid']."' , '".$next_id."')";
			$sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";

			$this->db->query($sql_insert_new1);
			$this->db->query($sql_insert_new2);
			$this->db->query($sql_update);

			//Insert Image
			$file_type = "image/jpeg";
			$image_name = $next_id."_".$a_data['module'].".jpg";
			$date = date("YmdHis");

			if($module=="Questionnaire"){
				$image_name = $a_data['module'].$date.".jpg";
				$image_name_upload = $next_id."_".$image_name;
			}else{
				$image_name = $next_id."_".$a_data['module'].".jpg";
				$image_name_upload = $image_name;
			}

			$binary = base64_decode($a_request['image'][$i]);

			header("Content-Type: bitmap; charset=utf-8");
			$file = "../".$path."/" . $image_name_upload;
			$image_upload = fopen($file, "w");

			$filepath = $image_name_upload;
			fwrite($image_upload, $binary);
			fclose($image_upload);
			chmod($file, 0777);

			$sql2 = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('".$next_id."' , '".$image_name."', '".$file_type."', '".$path."/')";
			$this->db->query($sql2);

			$data = array(
				'crmid' => $next_id,
				'modifiedby' => $a_request["smownerid"],
				'modifiedtime' => date("Y-m-d H:i:s"),
			);

			array_push($a_return["data"] , $data);

		}// for

		$response_data = array_merge($this->_return,$a_return);

		$log_filename = "Insert_Image_".$module;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$a_data,$response_data);

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}


	public function delete_image_post(){

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		$a_data =$dataJson;
		$a_request =$dataJson['data'];
		$module =$dataJson['module'];

		$a_return["Message"] =  "Update Complete";

		$a_return["data"] = array();
		$data = array();

		foreach ($a_request["crmid"] as $crmid) {

			$data = array(
				'crmid' => $crmid,
				'modifiedby' => $a_request["modifiedby"],
				'modifiedtime' => date("Y-m-d H:i:s"),
				'deleted' => "1"
			);

			array_push($a_return["data"] , $data);
		}

		$update_status = $this->db->update_batch('aicrm_crmentity', $a_return["data"] , 'crmid');


		$response_data = array_merge($this->_return,$a_return);

		$log_filename = "Delete_Image_".$module;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// $this->common->_filename= "Insert_Calendar";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$a_data,$response_data);

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}


	public function upload_jobSign_post(){

		$this->common->_filename= "Insert_Image_Signature";

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;

		$image_user = $a_data['image_user'];
		$image_customer = $a_data['image_customer'];
		$action = $a_data['action'];
		$module = $a_data['module'];
		$crmid = $a_data['crmid'];

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Image Signature ==>',$url,$a_data);

		if($image_user!=""){
			$uitype = "999";
		}
		if($image_customer!=""){
			$uitype = "998";
		}


		$a_return["Message"] =  "Upload Complete";

		$file_maximumsize = 1048576; // Maximum size is 1MB
		$path 		= "storage/".date("Y")."/".date("F")."/week".date("W")."";
		// $url = "http://".$_SERVER['HTTP_HOST']."/MXES/".$path;
		$url = "https://".$_SERVER['HTTP_HOST']."/".$path;
		// alert($url);exit;

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

		if($action=="edit"){
			// echo 111;exit;
			if($image_user!=""){
				$delquery = "delete aicrm_seattachmentsrel from aicrm_seattachmentsrel
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid
				where aicrm_crmentity.setype='Image 999' and aicrm_seattachmentsrel.crmid='".$crmid."'" ;
			}

			if($image_customer!=""){
				$delquery = "delete aicrm_seattachmentsrel from aicrm_seattachmentsrel
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid
				where aicrm_crmentity.setype='Image 998' and aicrm_seattachmentsrel.crmid='".$crmid."'";
			}
			// alert($delquery);exit;
			$this->db->query($delquery);


		}


		//Get Crmid
		$sql_next_id = "SELECT max(id)+1 as next_id FROM aicrm_crmentity_seq";
		$query_next_id = $this->db->query($sql_next_id);
		$result_next_id = $query_next_id->result(0);
		$next_id = $result_next_id[0]['next_id'];

		$sql_insert_new1 = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$a_data['userid']."', '".$a_data['userid']."', 'Image ".$uitype."', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		$sql_insert_new2 = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$a_data['crmid']."' , '".$next_id."')";
		$sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";

		$this->db->query($sql_insert_new1);
		$this->db->query($sql_insert_new2);
		$this->db->query($sql_update);

		//Insert Image
		$file_type = "image/jpeg";
		$image_name = $next_id."_".$a_data['module'].".jpg";
		$binary = base64_decode($image_customer);
		if($image_customer!=""){
			$binary = base64_decode($image_customer);
		}
		if($image_user!=""){
			$binary = base64_decode($image_user);
		}


		header("Content-Type: bitmap; charset=utf-8");
		$file = "../".$path."/" . $image_name;
		$image_upload = fopen($file, "w");


		$filepath = $image_name;
		fwrite($image_upload, $binary);
		fclose($image_upload);
		chmod($file, 0777);
		if($image_user!=""){
			$sql_insert_new1 = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('".$next_id."' , '".$image_name."', '".$file_type."', '".$path."/')";
			$sql_update = "UPDATE aicrm_jobs SET image_user='".$image_name."',user_signature_time=NOW() where aicrm_jobs.jobid='".$a_data['crmid']."'";
			$insert = $this->db->query($sql_insert_new1);
			$update = $this->db->query($sql_update);
		}

		if($image_customer!=""){
			$sql_insert_new1 = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('".$next_id."' , '".$image_name."', '".$file_type."', '".$path."/')";
			$sql_update = "UPDATE aicrm_jobs SET image_customer='".$image_name."' , customer_signature_time=NOW() where aicrm_jobs.jobid='".$a_data['crmid']."'";
			$insert = $this->db->query($sql_insert_new1);
			$update = $this->db->query($sql_update);
		}



		$data = array(
			'crmid' => $next_id,
			'modifiedby' => $a_data["userid"],
			'modifiedtime' => date("Y-m-d H:i:s"),
		);

		array_push($a_return["data"] , $data);


		$response_data = array_merge($this->_return,$a_return);

	  	$this->common->set_log('After Insert Image Signature ==>',$a_data,$response_data);

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}

}
