<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Competitor extends REST_Controller
{

	// $config['module']['Documents']["tab_name"] =  array('aicrm_crmentity','aicrm_attachments');
// $config['module']['Documents']["tab_name_index"] =  array('aicrm_crmentity'=>'crmid','aicrm_attachments'=>'attachmentsid');

// $config['module']['Documents']["tab_name"] =  array('aicrm_crmentity','aicrm_notes','aicrm_notescf');
// $config['module']['Documents']["tab_name_index"] =  array('aicrm_crmentity'=>'crmid','aicrm_notes'=>'notesid' ,'aicrm_notescf'=>'notesid');

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_competitor','aicrm_competitorcf');
  private $tab_name_index =  array('aicrm_crmentity'=>'crmid','aicrm_competitor'=>'competitorid' ,'aicrm_competitorcf'=>'competitorid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("competitor_model");
		$this->_module = "Competitor";
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
				'CompetitorNo' => null
			),
		);
	}
	
	public function insert_content_post(){

	  	$this->common->_filename= "Insert_Competitor";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Competitor ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Competitor ==>',$a_request,$response_data);
	  
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

	  		if(count($data[0])>0 and $module=="Competitor"){

					list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'DocumentsNo' => $DocNo,

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


	public function get_competitor_post(){

		$this->common->_filename= "Detail_Competitor";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Detail Competitor==>',$url,$a_request);

		$response_data = $this->competitor_model->get_competitor($a_request);
		// alert($response_data);exit;

	  	$this->common->set_log('After Detail Competitor==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

}
