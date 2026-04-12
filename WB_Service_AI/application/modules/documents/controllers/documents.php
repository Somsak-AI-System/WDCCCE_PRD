<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Documents extends REST_Controller
{

	// $config['module']['Documents']["tab_name"] =  array('aicrm_crmentity','aicrm_attachments');
// $config['module']['Documents']["tab_name_index"] =  array('aicrm_crmentity'=>'crmid','aicrm_attachments'=>'attachmentsid');

// $config['module']['Documents']["tab_name"] =  array('aicrm_crmentity','aicrm_notes','aicrm_notescf');
// $config['module']['Documents']["tab_name_index"] =  array('aicrm_crmentity'=>'crmid','aicrm_notes'=>'notesid' ,'aicrm_notescf'=>'notesid');

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_notes','aicrm_notescf');
  private $tab_name_index =  array('aicrm_crmentity'=>'crmid','aicrm_notes'=>'notesid' ,'aicrm_notescf'=>'notesid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("documents_model");
		$this->_module = "Documents";
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
				'DocumentsNo' => null
			),
		);
	}
	
	public function insert_content_post(){

	  	$this->common->_filename= "Insert_Documents";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Documents ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Documents ==>',$a_request,$response_data);
	  
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
	  	
	  	$related_id=isset($a_request['related_id']) ? $a_request['related_id'] : "";
	  	//$related_module = isset($a_request['related_module']) ? $a_request['related_module'] : "";
	  	
	  		if(count($data[0])>0 and $module=="Documents"){

				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  					'Crmid' => $crmid,
	  					'DocumentsNo' => $DocNo,
	  				);

	  				if($related_id != ''){
	  					$sql= "insert into aicrm_senotesrel (crmid,notesid) value ('".$related_id."','".$crmid."')";
	  					$this->db->query($sql);
	  				}

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

	public function return_data_documents($a_data)
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


	public function get_documents_post(){

		$this->common->_filename= "Detail_Documents";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Detail Documents==>',$url,$a_request);

		// $response_data = $this->documents_model->get_document($a_request);
		$a_data = $this->documents_model->get_document($a_request);
		// alert($response_data);exit;

		$this->common->set_log('After Detail Documents==>',$a_request,$a_data);
	  	// $this->common->set_log('After Detail Documents==>',$a_request,$response_data);

	  	$a_data["data"] = $a_data["result"];
		$a_data["limit"] = $a_limit["limit"];
		$a_data["offset"] = $a_limit["offset"];
		$a_data["time"] = date("Y-m-d H:i:s");
		$a_cache["data"] = $a_data["result"];
		$a_cache["data"]["time"] = date("Y-m-d H:i:s");

		$this->return_data_documents($a_data);
	  
	  	// if ( $response_data ) {
	  	// 	$this->response($response_data, 200); // 200 being the HTTP response code
	  	// } else {
	  	// 	$this->response(array(
	  	// 			'error' => 'Couldn\'t find Set Content!'
	  	// 	), 404);
	  	// }
	}

}