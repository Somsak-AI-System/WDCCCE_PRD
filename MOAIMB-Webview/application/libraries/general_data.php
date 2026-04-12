<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class General_data
{
  public $_tab = array();

  function __construct()
  {
    $this->CI = & get_instance();

    $this->_lang = $this->CI->config->item('lang');
    $this->CI->lang->load('ai',$this->_lang);
  }

  public function get_component($itemcd=null,$totalqty=0,$orderid=null,$module,$param=array())
  {
  	if($itemcd=="" || $orderid==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$itemcd;
  	$a_param["Value2"] =$totalqty;
  	$a_param["Value3"] =$orderid;


  	$a_data = $this->CI->api_cms->get_content_production("GetComponent",$module,$a_param);
	//alert($a_data);
  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata") . " ".$data['msg'] ;
  		$data['status'] = false;
  		$data['error'] = @$a_data["error"];
  	}else{
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }

  public function get_component_issue($itemcd=null,$module,$param=array())
  {
  	if($itemcd==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$itemcd;

  	$a_data = $this->CI->api_cms->get_content_production("GetComponentIssue",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata") . " ".$data['msg'] ;
  		$data['status'] = false;
  	}else{
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }

  public function get_sodtl($sono=null,$module,$param=array())
  {
  	if($sono==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "sono": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$sono;


  	$a_data = $this->CI->api_cms->get_content_production("GetComboSodtl",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_loaddate($carno=null,$process=null,$module,$param=array())
  {
  	if($carno=="" && $process=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "sono": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$carno;
  	$a_param["Value2"] =$process;


  	$a_data = $this->CI->api_cms->get_content_production("GetLoadDate",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_entrancedate($carno=null,$process=null,$module,$param=array())
  {
  	if($carno=="" && $process=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$carno;
  	$a_param["Value2"] =$process;


  	$a_data = $this->CI->api_cms->get_content_production("GetEntranceDate",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_sodtlbywo($sono=null,$sodtlno=null,$module,$param=array())
  {
  	if($sono==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "sono": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$sono;
  	$a_param["Value2"] = (isset($sodtlno) && $sodtlno!="") ? $sodtlno : "";

  	$a_data = $this->CI->api_cms->get_content_production("GetComboSodtlByWo",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  		$data['error'] = @$a_data["error"];
  	}else{
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  		$data['error'] = @$a_data["error"];
  	}
  	return $data;
  }
  public function checkProductType($productType=null,$module,$param=array())
  {
  	if($productType==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$productType;


  	$a_data = $this->CI->api_cms->get_content_production("checkProductType",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_scrap($itemcd=null,$woqty=null,$module,$param=array())
  {
  	if($itemcd==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	if($woqty==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$itemcd;
  	$a_param["Value2"] =$woqty;


  	$a_data = $this->CI->api_cms->get_content_production("GetScrapQty",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata")." Yield%";
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_employee($empcd=null,$module,$param=array())
  {
  	if($empcd==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "empcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$empcd;
  	$a_param["Value2"] ="";
  	$a_param["Value3"] ="";


  	$a_data = $this->CI->api_cms->get_content_master("GetEmployee",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_report_code($module,$param=array())
  {
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] ="";
  	$a_param["Value2"] ="";
  	$a_param["Value3"] ="";


  	$a_data = $this->CI->api_cms->get_content_production("GetFormCode",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_report_sap_code($module,$param=array())
  {
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] ="";
  	$a_param["Value2"] ="";
  	$a_param["Value3"] ="";


  	$a_data = $this->CI->api_cms->get_content_production("GetFormSAPError",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_dk_kiln_no($kilnno=null,$module,$param=array())
  {
  	if($kilnno==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$kilnno;
  	$a_param["Value2"] ="";
  	$a_param["Value3"] ="";


  	$a_data = $this->CI->api_cms->get_content_production("GetDKKilnNo",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_bone_kilnno($kilnno=null,$module,$param=array())
  {
  	if($kilnno==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$kilnno;
  	$a_param["Value2"] ="";
  	$a_param["Value3"] ="";


  	$a_data = $this->CI->api_cms->get_content_production("GetBoneKilnNo",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_rounting_data($operation=null,$prtitemcd=null,$groupcounter=null,$module,$param=array())
  {
  	if($prtitemcd=="" ||$operation==""||$groupcounter==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "wkccd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$prtitemcd;
  	$a_param["Value2"] =$groupcounter;
  	$a_param["Value3"] =$operation;


  	$a_data = $this->CI->api_cms->get_content_master("GetRouting",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }

  public function get_wo_data($wono=null,$module,$param=array())
  {
  	if($wono==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "wkccd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$wono;
  	$a_param["Value2"] ="";
  	$a_param["Value3"] ="";


  	$a_data = $this->CI->api_cms->get_content_production("GetPdrtnWO",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = $result;
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_order_item($itemcd=null,$orderid=null,$firetype=null,$module,$param=array())
  {
  	if($itemcd=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	if($orderid=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	/*if($firetype=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = "please insert data Firetype";
  		return $data;
  	}*/
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$itemcd;
  	$a_param["Value2"] =$orderid;
  	$a_param["Value3"] = $firetype;


  	$a_data = $this->CI->api_cms->get_content_production("GetOrderNoByItem",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_order_no($orderid=null,$module,$param=array())
  {
  	if($orderid=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$orderid;
  	$a_param["Value2"] ="";

  	$a_data = $this->CI->api_cms->get_content_production("GetOrderNo",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_operation_by_order($orderid=null,$module,$param=array())
  {
  	if($orderid=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$orderid;
  	$a_param["Value2"] ="";

  	$a_data = $this->CI->api_cms->get_content_production("GetOperationByOrder",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = $result;
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }

  public function get_kilnno($kilntype=null,$module,$param=array())
  {
  	if($kilntype=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}



  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$kilntype;


  	$a_data = $this->CI->api_cms->get_content_production("GetKilnno",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_cycletm($process=null,$kilnno=null, $speed=null, $entrancedate=null,$entrancetime=null,$module=null,$param=array())
  {
  	if($process=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}

  	if($kilnno=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}


  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] = (isset($process) && $process !="") ?$process:"";
	$a_param["Value2"] = (isset($kilnno) && $kilnno != "" ) ? $kilnno:"";
	$a_param["Value3"] = (isset($speed) && $speed !="") ?$speed:"";
	$a_param["Value4"] = (isset($entrancedate) && $entrancedate != "" ) ? $entrancedate:"";
	$a_param["Value5"] = (isset($entrancetime) && $entrancetime != "" ) ? $entrancetime:"";


  	$a_data = $this->CI->api_cms->get_content_production("GetCycletm_New",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];


  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_kilntype($kilntype=null,$entrancedate=null,$entrancetime=null,$module,$param=array())
  {
  	if($kilntype=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}

  	if($entrancedate=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}

  	if($entrancetime=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}

  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$kilntype;
  	$a_param["Value2"] =date_set($entrancedate,"Y-m-d");
  	$a_param["Value3"] =$entrancetime;


  	$a_data = $this->CI->api_cms->get_content_production("GetKilnType",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_wo_ref_material_data($itemcd=null,$itemcdhead,$module,$param=array())
  {
  	if($itemcd=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$itemcdhead;
  	$a_param["Value2"] =$itemcd;




  	$a_data = $this->CI->api_cms->get_content_production("GetDataMaterialRefOrder",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_wo_ref_order_data($orderid=null,$module,$param=array())
  {
  	if($orderid=="" ){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "orderid": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$orderid;



  	$a_data = $this->CI->api_cms->get_content_production("GetDataWoRefOrder",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
  public function get_workcenter_name($wkccd=null,$module,$param=array())
  {
  	if($wkccd==""){
  		$data["status"]=FALSE;
  		$data['error'] = lang("msg_get_data_insertdata");
  		return $data;
  	}
  	$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
  	$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
  	$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "wkccd": $param["Orderby"];
  	$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
  	$a_param["Value1"] =$wkccd;


  	$a_data = $this->CI->api_cms->get_content_master("GetWorkCenter",$module,$a_param);

  	$data['msg'] = $a_data["msg"];
  	$data['status'] = $a_data['status'];

  	$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
  	if (empty($result)) {
  		$data['data'] = $result;
  		$data['msg'] = lang("msg_get_data_nodata");
  		$data['status'] = false;
  	}else{
  		//$result["Sodt"] = date_get($result["Sodt"]);
  		$data['data']  = array_change_key_case($result, CASE_LOWER );
  		$data['msg'] = lang("msg_get_data_success");
  		$data['status'] = true;
  	}
  	return $data;
  }
	public function get_item_name($itemcd=null,$module,$param=array())
	{
		if($itemcd==""){
			$data["status"]=FALSE;
			$data['error'] = lang("msg_get_data_insertdata");
			return $data;
		}
		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
		$a_param["Value1"] =$itemcd;


		$a_data = $this->CI->api_cms->get_content_production("GetItem",$module,$a_param);

		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
		if (empty($result)) {
			$data['data'] = $result;
			$data['msg'] = lang("msg_get_data_nodata");
			$data['status'] = false;
		}else{
			//$result["Sodt"] = date_get($result["Sodt"]);
			$data['data']  = array_change_key_case($result, CASE_LOWER );
			$data['msg'] = lang("msg_get_data_success");
			$data['status'] = true;
		}
		return $data;
	}

	public function get_item_machine_name($itemcd=null,$groupcounter,$module,$param=array())
	{
		if($itemcd==""){
			$data["status"]=FALSE;
			$data['error'] = lang("msg_get_data_insertdata");
			return $data;
		}
		if($groupcounter==""){
			$data["status"]=FALSE;
			$data['error'] = lang("msg_get_data_insertdata");
			return $data;
		}
		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
		$a_param["Value1"] =$itemcd;
		$a_param["Value2"] =$groupcounter;


		$a_data = $this->CI->api_cms->get_content_production("GetItemMachine",$module,$a_param);

		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
		if (empty($result)) {
			$data['data'] = $result;
			$data['msg'] = lang("msg_get_data_nodata");
			$data['status'] = false;
		}else{
			//$result["Sodt"] = date_get($result["Sodt"]);
			$data['data']  = array_change_key_case($result, CASE_LOWER );
			$data['msg'] = lang("msg_get_data_success");
			$data['status'] = true;
		}
		return $data;
	}

	public function get_data_control($module,$param=array())
	{
		$a_param = $param;
		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];

		$a_data = $this->CI->api_cms->get_content_production("GetRecordResult",$module,$a_param);

		//alert($a_data);exit();

		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
		if (empty($result)) {
			$data['data'] = $result;
			$data['msg'] = $data['msg'];
			$data['status'] = false;
		}else{
			//$result["Sodt"] = date_get($result["Sodt"]);
			if ($data['status']) {
				$data['data']  = array_change_key_case($result, CASE_LOWER );
				$data['msg'] = lang("msg_get_data_success");
				$data['status'] = true;
			}else{
				$data['data']  = $result;
				$data['msg'] = $data['msg'];
				$data['status'] = false;
			}

		}
		return $data;
	}

	public function get_data_control_prodIss($module,$param=array())
	{
		$a_param = $param;
		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];

		$a_data = $this->CI->api_cms->get_content_production("GetIdProdIss",$module,$a_param);

		//alert($a_data);exit();

		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
		if (empty($result)) {
			$data['data'] = $result;
			$data['msg'] = $data['msg'];
			$data['status'] = false;
		}else{
			//$result["Sodt"] = date_get($result["Sodt"]);
			if ($data['status']) {
				$data['data']  = array_change_key_case($result, CASE_LOWER );
				$data['msg'] = lang("msg_get_data_success");
				$data['status'] = true;
			}else{
				$data['data']  = $result;
				$data['msg'] = $data['msg'];
				$data['status'] = false;
			}

		}
		return $data;
	}
	public function get_data_control_prodTrf($module,$param=array())
	{
		$a_param = $param;
		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];

		$a_data = $this->CI->api_cms->get_content_production("GetIdProdTrf",$module,$a_param);

		//alert($a_data);exit();

		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
		if (empty($result)) {
			$data['data'] = $result;
			$data['msg'] = $data['msg'];
			$data['status'] = false;
		}else{
			//$result["Sodt"] = date_get($result["Sodt"]);
			if ($data['status']) {
				$data['data']  = array_change_key_case($result, CASE_LOWER );
				$data['msg'] = lang("msg_get_data_success");
				$data['status'] = true;
			}else{
				$data['data']  = $result;
				$data['msg'] = $data['msg'];
				$data['status'] = false;
			}

		}
		return $data;
	}
	public function get_casttype($castcd=null,$module,$param=array())
	{
		$data["result"] = "";
		if($castcd==""){
			$data["status"]=FALSE;
			$data['error'] = lang("msg_get_data_insertdata");
			return $data;
		}
		if ($castcd == "1") {
			$data["result"]["casttype"] = "DRAIN";
		}
		elseif ($castcd == "2") {
			$data["result"]["casttype"] = "SOLID";
		}
		elseif ($castcd == "3") {
			$data["result"]["casttype"] = "HANDHELD";
		}
    	elseif ($castcd == "4") {
			$data["result"]["casttype"] = "HIGH PRESSURE";
		}
		if (empty($data["result"])) {
			$data['msg'] = lang("msg_get_data_nodata");
			$data['status'] =false;
		}else{
			$data['msg'] = lang("msg_get_data_success");
			$data['status'] =true;
		}


		//$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];

			$data['data']  = $data["result"];
			//$data['msg'] = lang("msg_get_data_success");
			//$data['status'] = true;

		return $data;
	}
	public function get_inload_name($inloadtype=null,$module,$param=array())
	{
		$data["result"] = "";
		if($inloadtype==""){
			$data["status"]=FALSE;
			$data['error'] = lang("msg_get_data_insertdata");
			return $data;
		}
		if ($inloadtype == "1") {
			$data["result"]["inloadnm"] = "เผาเคลือบสี";
		}
		elseif ($inloadtype == "2") {
			$data["result"]["inloadnm"] = "งานซ่อม";
		}
		elseif ($inloadtype == "3") {
			$data["result"]["inloadnm"] = "งาน Rework";
		}
		elseif ($inloadtype == "4") {
			$data["result"]["inloadnm"] = " งาน Decal";
		}

		if (empty($data["result"])) {
			$data['msg'] = lang("msg_get_data_nodata");
			$data['status'] =false;
		}else{
			$data['msg'] = lang("msg_get_data_success");
			$data['status'] =true;
		}


		//$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];

		$data['data']  = $data["result"];
		//$data['msg'] = lang("msg_get_data_success");
		//$data['status'] = true;

		return $data;
	}
	public function get_ng_field($function=null,$module,$a_param=array())
	{

		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "": $param["Rowno"];
		if($function == "GetCastCastingDefect" || $function == "GetCastScrappingDefect"  || $function == "GetCastFinishingDefect"  )
		{
			$a_param["Value1"] ="1";
		}else{
			$a_param["Value1"] =(isset($a_param["Value1"]) && $a_param["Value1"] != "") ? $a_param["Value1"] : "" ;
		}


		$a_data = $this->CI->api_cms->get_content_production($function,$module,$a_param);
		//alert($a_data);
		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
		$data['data'] = $result;
		return $data;
	}

	public function get_ng_value($module,$a_param=array())
	{
		$method="GetNGResult";
		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"All": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "": $param["Rowno"];



		$a_data = $this->CI->api_cms->get_content_production($method,$module,$a_param);
		//alert($a_data);exit();
		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"]:$a_data["result"];
		$data['data'] = $result;
		return $data;
	}

	public function get_cusBySo($sono=null,$module=null,$param=array())
	{
		if($sono==""){
			$data["status"]=FALSE;
			$data['error'] = lang("msg_get_data_insertdata");
			return $data;
		}
		$a_param["Condition"] = (empty($param["Condition"]) || $param["Condition"]=="")?"": $param["Condition"];
		$a_param["Offset"] =  (empty($param["Offset"]) || $param["Offset"]=="")?"1": $param["Offset"];
		$a_param["Orderby"] =  (empty($param["Orderby"]) || $param["Orderby"]=="")? "itemcd": $param["Orderby"];
		$a_param["Rowno"] =  (empty($param["Rowno"]) || $param["Rowno"]=="")? "1": $param["Rowno"];
		$a_param["Value1"] =$sono;

		$a_data = $this->CI->api_cms->get_content_production("GetCusBySO",$module,$a_param);

		$data['msg'] = $a_data["msg"];
		$data['status'] = $a_data['status'];

		$result = is_array($a_data["result"]) ? $a_data["result"][0]:$a_data["result"];
		if (empty($result)) {
			$data['data'] = $result;
			$data['msg'] = lang("msg_get_data_nodata");
			$data['status'] = false;
		}else{
			$result["Sodt"] = date_get($result["Sodt"]);
			$data['data']  = array_change_key_case($result, CASE_LOWER );
			$data['msg'] = lang("msg_get_data_success");
			$data['status'] = true;
		}


		return $data;
	}
}
