<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Relatedmodule extends REST_Controller
{

	private $crmid;
  function __construct()
  {
    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		// $this->load->config('config_module');
		$this->load->library('lib_api_common');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("relatedmodule_model");
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
					'ServicerequestNo' => null
			),
	);
	}


 function get_returnlocation($crmid=""){
	 $sql ="select *
			 FROM aicrm_activity
		 LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
		 LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
		 LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activitycf.accountid
		 WHERE aicrm_crmentity.deleted = 0
		 AND crmid = ".$crmid ."
		 AND aicrm_activitycf.location ='' ";
	 $query = $this->db->query($sql);
 $data=$query->result_array();

 if(count($data)>0){
	 return "E";
 }else{
	 return "S";
 }
 }



	private function get_cache($a_params=array())
	{

		$module = $a_params["module"];
		$action = $a_params["action"];
		$userid = $a_params["userid"];
		$crmid= $a_params["crmid"];

		if($action == "relate"){
			$a_list = $this->relatedmodule_model->Get_Relate($module,$crmid);
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");

		}else{
				if($a_data===false)
			{

				$a_list = $this->lib_api_common->Get_Block($module,$action,$crmid);
				$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			}

		}

    $a_list['language']=$user_lang;

		return $a_list;

	}


	public function get_relate_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data =$this->get_cache($a_param);
		$this->return_dataRelate($a_data);

	}

	public function return_dataRelate($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = 0;
      $a_return["language"] = !empty($a_data["language"]) ? $a_data["language"] : "" ;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"]["jsonrpc"] = "2.0";
			$a_return["data"]["id"] = "";
			$a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : [] ;
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
