<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Indexselect extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_accountshipads','aicrm_accountscf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid');
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	$this->_module = "Accounts";
	$this->_format = "array";
  }


	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();

		if (isset($a_param["name"]) && $a_param["name"]!="") {
			$a_condition["like"] =  "%".$a_param["name"]."%" ;

		}

		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["crmid"] =  $a_param["crmid"] ;
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
		$this->load->model("Indexselect_model");
		$module= @$a_params["module"];


		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$module= @$a_params["module"];
		$search_module= @$a_params["search_module"];
		$section= @$a_params["section"];
		$relate_field = @$a_params["relate_field"];
		$userid = @$a_params["userid"];

		if($module=="Case"){
			$module="HelpDesk";
		}elseif ($module=="Spare Part" || $module=="SparePart") {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList" ) {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartList") {
			$module = "Sparepartlist";
		}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder") {
			$module = "Projectorder";
		}
		

		if($search_module=="Case"){
			$search_module="HelpDesk";
		}elseif ($search_module=="Spare Part" || $search_module=="SparePart") {
			$search_module = "Sparepart";
		}elseif ($search_module=="Errors List" || $search_module=="ErrorsList" ) {
			$search_module = "Errorslist";
		}elseif ($search_module=="Spare Part List" || $search_module=="SparePartList") {
			$search_module = "Sparepartlist";
		}elseif ($search_module=="Projectorders" || $search_module=="Project Orders" || $search_module=="Projectorder") {
			$search_module = "Projectorder";
		}
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

			$a_list=$this->Indexselect_model->get_list($a_condition,$a_order,$a_limit,$module,$search_module,$section,$relate_field,$userid);
			//alert($a_list);exit;
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

	private function get_data($a_data)
	{
		if($a_data['result'] != ''){
			foreach($a_data['result']['data'] as $key => $val ){
				for($i=0;$i<count($val);$i++){
					if($i==2){
						$index = array_values($val)[1];
						if($index==""){
							$index = array_values($val)[2];
						}
					}
				}

				$sub_index = iconv_substr($index,0,1,"UTF-8");
				if($sub_index==false){
					$sub_index="";
				}
				$a_data['result']['data'][$key]['index_name'] = $sub_index;

			}
		}
		return $a_data;
	}


	public function list_select_get()
	{
		$a_param =  $this->input->get();
		$search_module = @$a_param['search_module'];
		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data,$a_param,$search_module);
	}

	public function list_select_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$search_module = @$a_param['search_module'];
		
		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data,$a_param,$search_module);
	}

	public function return_data($a_data,$a_param,$search_module)
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
			$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : null ;

			$log_filename = "Select_".$search_module;
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= $log_filename;
			$this->common->set_log($url,$a_param,$a_data);
	
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
