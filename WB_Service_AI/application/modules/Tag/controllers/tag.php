<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Tag extends REST_Controller
{
	private $crmid;
	function __construct()
	{
	    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->library('lib_api_common');
	    $this->load->database();
		$this->load->library("common");
		$this->load->model("tag_model");
		$this->_module = 'Tag';
		$this->_format = "array";
		$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
				'tagid' => null,
				'no' => null,
				'name' => null,
				'color' => null,
			),
		);
	}

	private function set_param($a_param=array())
	{
		$a_condition = array();
		/*if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_announcement.announcementid"] =  $a_param["crmid"] ;
		}*/
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

	public function list_content_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data);
	}
	
	public function list_content_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);
		$a_data = $this->get_cache($a_param);
		$this->return_data($a_data);
	}

	public function list_detail_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);
		$a_data = $this->get_detail($a_param);
		$this->return_data($a_data);
	}

	private function get_detail($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("tag_model");
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$a_condition = array();
		$a_condition = $this->set_param($a_params);
		
		$crmid = @$a_params["crmid"];
		$userid = @$a_params["userid"];

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
			$a_list = $this->announcement_model->get_detail($a_condition,$a_order,$a_limit);
			$a_data = $this->get_data_detail($a_list);

			/*if($a_params['flagread'] == 0 ){
				$q_update = "update ai_notification set flagread =1 , readdate = '".date('Y-m-d H:i:s')."' where crmid = ".$crmid." and userid = ".$userid." ";
				$this->db->query($q_update);
			}*/

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
	
	public function notification_list_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param  = json_decode($request_body,true);
		$a_data = $this->get_list_noti($a_param);
		$this->return_data($a_data);
	}

	private function get_list_noti($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("announcement_model");
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
			$a_list = $this->announcement_model->get_noti_list($a_condition,$a_order,$a_limit,$a_params);
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

		$this->common->_filename= "Insert_Tag";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Tag==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Tag==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}

	}

	private function get_insert_data($a_request){
		//echo date('Y-m-d H:i:s'); exit;
		$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$relation_id=isset($a_request['relation_id']) ? $a_request['relation_id'] : "";
	  	$name = isset($a_request['name']) ? $a_request['name'] : "";
	  	$relation_module = isset($a_request['relation_module']) ? $a_request['relation_module'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$chanel=isset($a_request['chanel']) ? $a_request['chanel'] : "Mobile";

	  	if ($relation_module=="Calendar" || $relation_module=="Sales Visit" ||$relation_module=="Sale Visit" ||$relation_module=="Events" ||$relation_module=="Visit") {
			$relation_module = "Calendar";
		}
	  	
	  	$sql_check = "SELECT * FROM aicrm_freetags where  tag = '".$name."' ";
	  	$query = $this->db->query($sql_check);
		$a_result  = $query->result_array() ;
		
		if(!empty($a_result)){
			//Yes Data
			$tagid = $a_result[0]['id'];
			$int_dup = "INSERT INTO aicrm_freetagged_objects (tag_id,tagger_id,object_id,tagged_on,module) VALUES ('".$tagid."','".$userid."','".$relation_id."','".date('Y-m-d H:i:s')."','".$relation_module."') ON DUPLICATE KEY UPDATE tag_id = '".$tagid."', object_id = '".$relation_id."' ";
			$this->db->query($int_dup);

		}else{
			//No Data
			$sql_seq = "SELECT * FROM aicrm_freetags_seq";
	  		$s_query = $this->db->query($sql_seq);
	  		$a_id  = $s_query->result_array();
	  		$tagid = ($a_id[0]['id']+1);

	  		$seq_intert = "Update aicrm_freetags_seq set id = '".$tagid."' ";
	  		$this->db->query($seq_intert);
	  		
	  		$sql_interttag = "INSERT INTO aicrm_freetags (id,tag,raw_tag) value ('".$tagid."','".$name."','".$name."') ";
	  		$this->db->query($sql_interttag);

	  		$int_dup = "INSERT INTO aicrm_freetagged_objects (tag_id,tagger_id,object_id,tagged_on,module) VALUES ('".$tagid."','".$userid."','".$relation_id."','".date('Y-m-d H:i:s')."','".$relation_module."') ON DUPLICATE KEY UPDATE tag_id = '".$tagid."', object_id = '".$relation_id."' ";
			$this->db->query($int_dup);

		}
		
		$int_transition = "INSERT INTO aicrm_freetags_transition (tag_id,tag,tagger_id,object_id,tagged_on,module,chanel) VALUES ('".$tagid."','".$name."','".$userid."','".$relation_id."','".date('Y-m-d H:i:s')."','".$relation_module."','".$chanel."')";
			$this->db->query($int_transition);


		$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
		$a_return["data"] =array(
			'tagid' => $tagid,
			'no' => '',
			'name' => $name,
			'color' => '#a0a0a0',

		);

		return array_merge($this->_return,$a_return);
	
	}
	

	public function delete_content_post(){

		$this->common->_filename= "Delete_Tag";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Delete Tag==>',$url,$a_request);
		$response_data = $this->delete_data($a_request);	
	  	$this->common->set_log('After Delete Tag==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}

	}

	private function delete_data($a_request){
		//echo date('Y-m-d H:i:s'); exit;
		$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$relation_id=isset($a_request['relation_id']) ? $a_request['relation_id'] : "";
	  	$name = isset($a_request['name']) ? $a_request['name'] : "";
	  	$relation_module = isset($a_request['relation_module']) ? $a_request['relation_module'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$action_type=isset($a_request['action_type']) ? $a_request['action_type'] : ""; //all , only
	  	$id = isset($a_request['id']) ? $a_request['id'] : "";
	  	

	  	if($action_type == 'all'){
	  		$seq_clearall = "delete FROM  aicrm_freetagged_objects where object_id = '".$relation_id."' ";
	  		$this->db->query($seq_clearall);
	  		$a_return["Message"] = ($action=="delete")?"Delete Complete" : "Delete Complete";
			$a_return["data"] =array();

	  	}else if($action_type == 'only'){

	  		$seq_clearall = "delete FROM  aicrm_freetagged_objects where tag_id = '".$id."' and object_id = '".$relation_id."' ";
	  		$this->db->query($seq_clearall);
	  		$a_return["Message"] = ($action=="delete")?"Delete Complete" : "Delete Complete";
			$a_return["data"] =array();
	  	}else{

	  		$a_return  =  array(
				'Type' => 'E',
				'Message' => 'Unable to complete transaction',
			);

	  	}

	  	return array_merge($this->_return,$a_return);

	
	}




	private function get_data($a_data)
	{
		if($a_data['result'] != ''){			
		}
		return $a_data;
	}

	private function get_data_detail($a_data)
	{
		if($a_data['result'] != ''){

			foreach ($a_data["result"]["data"] as $key =>$val){
				$announcementid = $val["announcementid"];
				$a_announcement[] = $announcementid;
			}

			$a_conditionin["aicrm_announcement.announcementid"] = $a_announcement;
			$a_image = $this->common->get_a_image($a_conditionin,$this->_module);

			foreach ($a_data["result"]["data"] as $key =>$val){
				$ticketid = $val["announcementid"];
				$a_return = $val;
				$a_return["image"] =( !empty($a_image[$ticketid]["image"]))?$a_image[$ticketid]["image"] :array();
				$a_response[] = $a_return;
			}
			$a_data["result"]["data"] = $a_response;
		}
		return $a_data;
	}

	public function accept_post(){
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->get_insert_accept($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Accept_Announcement";

	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_accept($a_request){
	  	
	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "";
	  	
  		if($module=="Announcement" && $crmid != "" && $userid != ""){
  			
  			$q_update = "update ai_notification set flagaccept =1 , acceptdate = '".date('Y-m-d H:i:s')."' where crmid = ".$crmid." and userid = ".$userid." ";
			$this->db->query($q_update);
  			
			$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
			$a_return["data"] =array(
				'Crmid' => $crmid,
				'DocNo' => $DocNo,
			);
	  			
	  	}else{
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	
	}


}
