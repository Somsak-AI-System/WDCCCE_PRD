<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class General
{
  public $current_get_api = array();
  public $current_post_api = array();
  public $current_post_params = array();
  public $debug = FALSE;

  function __construct()
  {
    $this->CI = & get_instance();
	$this->CI->load->library('crm_api');

  }
  function get_warranty($productid,$chk_type){
		$module = "products";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		$methode = $module."/get_warranty/";

		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => $module, //module
				'productid' => $productid, //productid
				'chk_type' => $chk_type, //chk_type
				'limit' => "1000",
				'offset' => "0",
		);
	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	return $a_result;
 }
 function get_columnname($module,$columnname,$roleid="",$branchid=""){
 		$roleid = $roleid==""?"H2":$roleid;
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		//$methode = "ai_function/get_data/get_columnname/";
		$methode = "ai_function/get_data/get_issuelist/";
		
		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => $module, //module
				'columnname' => $columnname, //columnname
				'branchid' =>$branchid,
				'limit' => "1000",
				'offset' => "0",
		);
	
		if($roleid!="" ){
			$params['roleid'] = $roleid; //roleid 
		}
	//alert (json_encode($params)); exit;
	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	//alert($a_result); exit;
	//alert($params );
	return $a_result;
 }

  function get_branch($branchtype,$idcard){
	  if(empty($branchtype) || empty($idcard)) return null;
		$module = "branch";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		$methode = $module."/list_type/";
		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => "Branch", //module
				'where' => $branchtype,  //cf_2052 ประเภทโครงการ ::> บ้านเดี่ยว / ทาวน์เฮาส์ :: คอนโดมิเนียม
				'where1' => $idcard, //cf_2109 หมายเลขบัตรประชาชน
				'limit' => "1000",
				'offset' => "0",
		);

	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	return $a_result;
	//return ">>".print_r($a_result);
 }
  function get_branch_no($branchtype,$branchid,$idcard){
	  if(empty($branchtype) || empty($branchid) || empty($idcard)) return null;
		$module = "branch";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		$methode = $module."/list_branch_no/";

		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => "Branch", //module
				'where' =>   $branchtype, //cf_2052 ประเภทโครงการ ::> บ้านเดี่ยว / ทาวน์เฮาส์ :: คอนโดมิเนียม
				'where1' => $branchid, //cf_1059
				'where2' => $idcard, //cf_2109 หมายเลขบัตรประชาชน
				'limit' => "1000",
				'offset' => "0",
		);

	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	return $a_result;
 }
   function get_conname($con_name){
	  if(empty($con_name)) return null;
		$module = "contacts";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		$methode = $module."/detail/";

		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => "Contacts", //module
				'where' => $con_name, //module
				'limit' => "1000",
				'offset' => "0",
		);

	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	return $a_result;
 }
 function get_contact_login($username,$password){
	  if(empty($username) ||empty($password) ) return null;
		$module = "contacts";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		$methode = $module."/login/";

		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => "Contacts", //module
				'user' => $username,
				'pass' => $password,
				'limit' => "1000",
				'offset' => "0",
		);

	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	return $a_result;
 }
 
 
 // function check_id_card($id_card){
 // 	if(empty($id_card)) return null;
 // 		$module = "accounts";
	// 	$url = $this->CI->config->item('url');
	// 	$url_service=$url["url_main"];
	// 	$methode = $module."/check_id_card/";

	// 	$params = array(
	// 		'AI-API-KEY'=>"1234",
	// 		'module' => "Accounts", //module
	// 		'action' => "edit", //module
	// 		//'id_card' => "1719900175605", //module
	// 		'id_card' => $id_card, //module
	// 	);

	// $a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	// return $a_result;
 // } 
 	
function get_building_list($branchid){
	
	  $status = !empty($_REQUEST["status"])?$_REQUEST["status"]:"Active";
		
	  if(empty($branchid)) return null;
		$module = "Building";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		$methode = $module."/get_building_data/";

		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => "Building", //module
				'branchid' => $branchid, //cf_1059 โครงการ
				//'status' => 'Active',
				'limit' => "1000",
				'offset' => "0",
		);

	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	//alert($a_result);exit();
	return $a_result;
  }
  
function get_house_no($buildingid){
	
	//  $status = !empty($_REQUEST["status"])?$_REQUEST["status"]:"Active";
		
	  if(empty($buildingid)) return null;
	  
		$module = "products";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		$methode = $module."/get_product_data/";

		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => "products", //module
				'buildingid' => $buildingid, //อาคาร
				//'buildingid' => '1054896', //อาคาร
				'limit' => "1000",
				'offset' => "0",
				'orderby' =>"cf_2061,asc",
		);
	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	//alert($a_result);exit();
	return $a_result;
}


function get_branchtype($branchtype){
	
	  if(empty($branchtype)) return null;
		
		$module = "branch";
		$url = $this->CI->config->item('url');
		$url_service=$url["url_main"];
		//alert($url); exit;
		//$url_service = "http://localhost/sena/WB_Service_AI/";
		$methode = $module."/get_branch_data/";

		$params = array(
				'AI-API-KEY'=>"1234",
				'module' => "Branch", //module
				//'branchtype' => 'คอนโดมิเนียม',
				'branchtype' => $branchtype, 
				'status' => 'Active',
				'limit' => "0",
				//'offset' => "0",
		);
	//echo $url_service."/".	$methode."<br>"; 
	//alert ($params); exit;
	$a_result = $this->CI->crm_api->get_cache($params ,$url_service,$methode, $module);
	//alert($a_result);exit();
	return $a_result;
  }
  
 
}