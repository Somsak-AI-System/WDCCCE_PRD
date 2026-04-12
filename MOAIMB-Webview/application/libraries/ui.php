<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Ui
{
  public $_tab = array();

  function __construct()
  {
    $this->CI = & get_instance();
  }


  public function get_block($tab=null)
  {
  	if ($tab=="") return false;
  	$this->_module ="block";
  	$a_param["Condition"] ="All";
  	$a_param["Offset"] =0;
  	$a_param["Orderby"] ="";
  	$a_param["Rowno"] ="100";
  	$a_param["Value1"] =$tab;
  	$a_data = $this->CI->api_cms->get_content_master("GetBlock",$this->_module,$a_param);
	//alert($a_data["result"]);exit();
	if(!empty($a_data["result"]))
	{
		return $a_data["result"];
	}
	else
	{
		return false;
	}


  	/*$file_name = "tab_".$tab.".json";
  	$response =  file_get_contents(site_cache_url($file_name));
  	$json =  json_decode($response,true);
  	return $json;*/
  }
  public function searchSubArray(Array $array, $key, $value) {
  	foreach ($array as $subarray){
  		if (isset($subarray[$key]) && $subarray[$key] == $value)
  			return $subarray;
  	}
  }
  public function get_picklist($name=null)
  {
  	$a_return = array();
  	if ($name=="") return false;
  	$file_name = "cache/picklist.json";
  	$response =  file_get_contents($file_name);
  	$a_data =  json_decode($response,true);
  	$a_picklist =  $this->searchSubArray($a_data,"name",strtolower($name));
  	//alert($a_picklist);

	return $a_picklist;
  }
  public function get_field($tab=null)
  {
  	if ($tab=="") return false;
	  	$this->_module ="field";
	  	$a_param["Condition"] ="All";
	  	$a_param["Offset"] =0;
	  	$a_param["Orderby"] ="";
	  	$a_param["Rowno"] ="100";
	  	$a_param["Value1"] =$tab;
	  	$a_data = $this->CI->api_cms->get_content_master("GetTableField",$this->_module,$a_param);
	  	//alert($a_data["result"]);exit();
	  	if(!empty($a_data["result"]))
	  	{
	  		return $a_data["result"];
	  	}
	  	else
	  	{
	  		return false;
	  	}

  	/*
  	$file_name = "field_".$tab.".json";
  	$response =  file_get_contents(site_cache_url($file_name), FILE_USE_INCLUDE_PATH);
  	$json =  json_decode($response,true);
  	return $json;
  	*/
  }
  public function format($block=array(),$field=array())
  {
  	$data = array();
  	if (empty($block)) return false;
  	foreach($block as $k_content => $v_result){
  		$block_id = $v_result["Blockid"];
  		$data[$block_id]["Label"] = $v_result["Blocklabel"] ;
  		foreach($field as $k=> $v){
  			if ($block_id == $v["Block"]) {	  			;
  			$data[$block_id]["Field"][] = $v;
  			}
  		}
  	}
  	//alert($data);
  	return($data);
  }

  public function get_files($files=null)
  {
  	if ($files =="")  return false;
  	$response =  file_get_contents($files);
  	$json =  json_decode($response,true);
  	return $json;
  }
}
