<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class searchall extends REST_Controller
{

	private $crmid;

  function __construct()
  {
    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->config('config_module');
		$this->load->library('lib_api_common');
    $this->load->database();
		$this->load->library("common");
		$this->load->model("searchall_model");
		$this->_format = "array";
		$this->_limit = 0;
		$this->_return = array(
			'Type' => "S",
			'Message' => "Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
					'Crmid' => null,
			),
	);
	}

	public function searchData_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		//echo 555; exit;
		$data = $dataJson['data'];
		$userid = $dataJson['userid'];
		$limit = $dataJson["limit"];
		$offset = $dataJson["offset"];

		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;

		$a_response = $this->searchall_model->searchall($data,$a_limit);

		$log_filename = "Select_SearchALL";
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$dataJson,$a_response);
		
		if($a_response!=null){

			$response_data['Type']='S';
			$response_data['Message']='Success';
			$response_data['cache_time']=date("Y-m-d H:i:s");
			$response_data['total']=!empty($a_response['result']['total']) ? $a_response['result']['total'] : 0;
			$response_data['limit']=count($a_response['result']['data']);
			$response_data['data']=$a_response['result']['data'];

		}else{
			$response_data['Type']='E';
			$response_data['Message']='No Data';
			$response_data['cache_time']=date("Y-m-d H:i:s");
			$response_data['data']="";

		}

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
					'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}


		public function searchAddress_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		//echo 555; exit;

		$search = $dataJson['search'];
		$userid = $dataJson['userid'];
		$limit = $dataJson["limit"];
		$offset = $dataJson["offset"];

		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;

		$a_response = $this->searchall_model->searchaddress($search,$a_limit);

		$log_filename = "Select_SearchAddress";
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$dataJson,$a_response);
		
		if($a_response!=null){

			$response_data['Type']='S';
			$response_data['Message']='Success';
			$response_data['cache_time']=date("Y-m-d H:i:s");
			$response_data['total']=!empty($a_response['result']['total']) ? $a_response['result']['total'] : 0;
			$response_data['limit']=count($a_response['result']['data']);
			$response_data['data']=$a_response['result']['data'];

		}else{
			$response_data['Type']='E';
			$response_data['Message']='No Data';
			$response_data['cache_time']=date("Y-m-d H:i:s");
			$response_data['data']="";

		}

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
					'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}


}
