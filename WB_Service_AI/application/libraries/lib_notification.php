<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_notification
{


  function __construct()
  {
    $this->ci = & get_instance();

    $this->ci->load->database();

 	$this->_return = array(
 			"status" => false,
 			"message" =>"",
 			"time" => date("Y-m-d H:i:s"),
 			"data" => array("data" => ""),
 	);
 		
 	$this->ci->load->config('config_notification');
  }

  private function get_date($value="")
  {
  	if($value=="") return "";
  	
  	if(isset($value) && $value!="" && $value !="0000-00-00")
  	{
  		$value =  date("d-m-Y",strtotime($value));
  	}
  	else 
  	{
  		$value ="";
  	} 	
  	
  	return $value;
  }

 private function get_fulldate($value="")
  {
  	if($value=="") return "";
  	
  	if(isset($value) && $value!="" && $value !="0000-00-00")
  	{
  		$value =  date("d M Y",strtotime($value));
  	}
  	else 
  	{
  		$value ="";
  	} 	
  	
  	return $value;
  }
  private function get_fullTime($value="")
  {
  	if($value=="") return "";
  	
  	if(isset($value) && $value!="" && $value !="00:00")
  	{
  		$value =  date("h:i A",strtotime($value));
  	}
  	else 
  	{
  		$value ="";
  	} 	
  	
  	return $value;
  }
  
  private function get_datethai($strDate)
  {
  	if(empty($strDate) || $strDate=="") return $strDate;
  	
  	$strYear = substr(date("Y",strtotime($strDate))+543,-2);
  	$strMonth= date("m",strtotime($strDate));
  	$strDay= date("d",strtotime($strDate));
  	$strwDay= date("w",strtotime($strDate));
  	$strHour= date("H",strtotime($strDate));
  	$strMinute= date("i",strtotime($strDate));
  	$strSeconds= date("s",strtotime($strDate));
  	//$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  	$TH_Day = array("อา.","จ.","อ.","พ.","พฤ.","ศ.","ส.");
  	return "$TH_Day[$strwDay] $strDay/$strMonth/$strYear";
  }
  public function get_servicerequest($crmid="",$a_cofig=array())
  {
  	$sql = " select aicrm_servicerequests.*,aicrm_servicerequestscf.*,aicrm_crmentity.*,aicrm_crmentity_notification.notificationid
  	
			from aicrm_servicerequests
			inner join aicrm_servicerequestscf on aicrm_servicerequests.servicerequestid = aicrm_servicerequestscf.servicerequestid
			inner join aicrm_crmentity on aicrm_servicerequests.servicerequestid = aicrm_crmentity.crmid
  			left join aicrm_crmentity_notification on aicrm_crmentity.crmid =  aicrm_crmentity_notification.crmid";
  	
  	$sql .= " where aicrm_crmentity.deleted = 0	";
  	if (isset($crmid) && $crmid!="") {
  		$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
  	}
  	
  	$query = $this->ci->db->query($sql);
  	if(!$query){
  		$a_return["status"] = false;
  		$a_return["message"] = $this->ci->db->_error_message();
  		$a_return["result"] = "";
  	}else{
  		$a_data =  $query->result_array() ;
  		if (!empty($a_data)) {
  			$a_return["status"] = true;
  			$a_return["message"] =  "";
  			$a_return["result"] = $a_data;
  		}else{
  			$a_return["status"] = false;
  			$a_return["message"] =  "No Data";
  			$a_return["result"] = "";
  		}
  	}
  	return $a_return;
  }
  public function get_percel($crmid="",$a_cofig=array())
  {
  	$sql = " select aicrm_parcel.*,aicrm_parcelcf.*,aicrm_crmentity.*,aicrm_crmentity_notification.notificationid
		
			from aicrm_parcel
			inner join aicrm_parcelcf on aicrm_parcel.parcelid = aicrm_parcelcf.parcelid
			inner join aicrm_crmentity on aicrm_parcel.parcelid = aicrm_crmentity.crmid
  			left join aicrm_crmentity_notification on aicrm_crmentity.crmid =  aicrm_crmentity_notification.crmid";
  	 
  	$sql .= " where aicrm_crmentity.deleted = 0	";
  	if (isset($crmid) && $crmid!="") {
  		$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
  	}
  	 
  	$query = $this->ci->db->query($sql);
  	if(!$query){
  		$a_return["status"] = false;
  		$a_return["message"] = $this->ci->db->_error_message();
  		$a_return["result"] = "";
  	}else{
  		$a_data =  $query->result_array() ;
  		if (!empty($a_data)) {
  			$a_return["status"] = true;
  			$a_return["message"] =  "";
  			$a_return["result"] = $a_data;
  		}else{
  			$a_return["status"] = false;
  			$a_return["message"] =  "No Data";
  			$a_return["result"] = "";
  		}
  	}
  	return $a_return;
  }
  
  public function get_debitnote($crmid="",$a_cofig=array())
	  {
		$sql = " select aicrm_debitnote.*,aicrm_debitnotecf.*,aicrm_crmentity.*,aicrm_crmentity_notification.notificationid
				from aicrm_debitnote
				inner join aicrm_debitnotecf on aicrm_debitnote.debitnoteid = aicrm_debitnotecf.debitnoteid
				inner join aicrm_crmentity on aicrm_debitnote.debitnoteid = aicrm_crmentity.crmid
				left join aicrm_crmentity_notification on aicrm_crmentity.crmid =  aicrm_crmentity_notification.crmid";
		$sql .= " where aicrm_crmentity.deleted = 0	";
		if (isset($crmid) && $crmid!="") {
			$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
		}
		 
		$query = $this->ci->db->query($sql);
		if(!$query){
			$a_return["status"] = false;
			$a_return["message"] = $this->ci->db->_error_message();
			$a_return["result"] = "";
		}else{
			$a_data =  $query->result_array() ;
			if (!empty($a_data)) {
				$a_return["status"] = true;
				$a_return["message"] =  "";
				$a_return["result"] = $a_data;
			}else{
				$a_return["status"] = false;
				$a_return["message"] =  "No Data";
				$a_return["result"] = "";
			}
		}
		return $a_return;
	  }
  public function get_news($crmid="",$a_cofig=array())
  {
  	$sql = " select aicrm_knowledgebase.*,aicrm_knowledgebasecf.*,aicrm_crmentity.*,aicrm_crmentity_notification.notificationid
			
			from aicrm_knowledgebase
			inner join aicrm_knowledgebasecf on aicrm_knowledgebase.knowledgebaseid = aicrm_knowledgebasecf.knowledgebaseid
			inner join aicrm_crmentity on aicrm_knowledgebase.knowledgebaseid = aicrm_crmentity.crmid	
  			left join aicrm_crmentity_notification on aicrm_crmentity.crmid =  aicrm_crmentity_notification.crmid";
  	
  	$sql .= " where aicrm_crmentity.deleted = 0	";
  	if (isset($crmid) && $crmid!="") {
  		$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
  	}
  	
  	$query = $this->ci->db->query($sql);
  	if(!$query){
  		$a_return["status"] = false;
  		$a_return["message"] = $this->ci->db->_error_message();
  		$a_return["result"] = "";
    }else{
    	$a_data =  $query->result_array() ;
    	if (!empty($a_data)) {
    		$a_return["status"] = true;
    		$a_return["message"] =  "";
    		$a_return["result"] = $a_data;
    	}else{
    		$a_return["status"] = false;
    		$a_return["message"] =  "No Data";
    		$a_return["result"] = "";
    	}
    }
    return $a_return;
  }
  
  	private function get_contact_km($condition="")
  	{
  		
  		$sql = "select aicrm_contactdetails.contactid 
  				from aicrm_contactdetails 
  				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
  				left join aicrm_products on aicrm_products.productid = aicrm_contactdetails.product_id
  				where aicrm_crmentity.deleted = 0 ";
  		if($condition!=""){
  			$sql .= $condition;
  		}
  		$query = $this->ci->db->query($sql);
  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["message"] = $this->ci->db->_error_message();
  			$a_return["result"] = "";
  		}else{
  			$a_data =  $query->result_array() ;
  			$a_result=array();
  			if (!empty($a_data)) {
  				foreach ($a_data as $k=>$v){
  					$contactid = $v["contactid"];
  					$a_result[]= $contactid;
  				}
  				
  				$a_return["status"] = true;
  				$a_return["message"] =  "";
  				$a_return["result"] = implode(",", $a_result);
  			}else{
  				$a_return["status"] = false;
  				$a_return["message"] =  "No Data";
  				$a_return["result"] = "";
  			}
  		}
  		return $a_return;
  	}
  	private function get_userid($a_config=array(),$a_data=array())
  	{
  		//alert($a_config);
  		if(empty($a_config["send_user"])){
  			$a_return["status"] = false;
  			$a_return["message"] = "Userid is null";
  			return array_merge($this->_return,$a_return);
  		}
  		$usermode = $a_config["send_user"]["user_mode"];
  		$user_query = $a_config["send_user"]["user_query"];
  		$condition = $a_config["send_user"]["condition"];
  		$field_crm_userid = @$a_config["send_user"]["field_crm_userid"];
  		//if usermode = "building" || All;
  		if($user_query=="get_contact_km" && $usermode=="building"){
  			$buildingid = $a_data["buildingid"];
  			if($buildingid!=""){
  				$condition .= " and aicrm_products.buildingid = '".$buildingid."' ";
  			}  			
  		}
  		
  		
  		if($user_query!=""){
  			if (method_exists($this, $user_query))
  			{
  				$a_return = $this->{$user_query}($condition,$usermode);
  				return $a_return;
  			}
  		}
  		else
  		{
  			//if usesrmode = only
  			if($usermode == "only")
  			{  				
  				$a_return["result"] = @$a_data[$field_crm_userid];  				
  				if(empty($a_return["result"]) || $a_return["result"]==""){
  					$a_return["status"] = false;
  					$a_return["message"] = "User id is null";
  				}else{
  					$a_return["status"] = true;
  					$a_return["message"] = "";
  				}  				
  			}
  			else{
  				$a_return["status"] = false;
  				$a_return["message"] = "User Method is null";
  			}
  			
  			return array_merge($this->_return,$a_return);
  		}
  	}
  	
  	private function get_send($a_config=array(),$a_data=array())
  	{
  		if(empty($a_config)){
  			$a_return["status"] = false;
  			$a_return["message"] = "Send Time is null";
  			return array_merge($this->_return,$a_return);
  		}
  		
  		$send_mode = $a_config["send_mode"];
  		$send_time = @$a_config["send_time"];
  		$field_crm_date = @$a_config["field_crm_date"];
  		$field_crm_time = @$a_config["field_crm_time"];
  		
  		if($send_mode=="save")
  		{
  			$a_response["senddate"] = date("Y-m-d");
  			$a_response["sendtime"] = date("H:i", strtotime("+10 minutes"));
  		}
  		else if($send_mode=="batch")
  		{
  			$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
  			if($send_time!=""){
  				$a_response["sendtime"] = str_replace(".", ":", $send_time);
  			}else{
  				$a_response["sendtime"] = date("H:i");
  			}  			
  		}
  		else if($send_mode=="fieldsend")
  		{
  			$s_senddate = @$a_data[$field_crm_date];
  			if($s_senddate!=""){
  				$a_response["senddate"] = $s_senddate;
  			}else{
  				$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
  			}
  			
  			$s_sendtime = @$a_data[$field_crm_time];
  			if($s_sendtime!=""){
  				$a_response["sendtime"] = str_replace(".", ":", $s_sendtime);
  			}else{
  				$a_response["sendtime"] = date("H:i");
  			}
  		}
  		
  		$a_return["status"] = true;
  		$a_return["message"] =  "";
  		$a_return["result"] = $a_response;
  		return $a_return;
  		
  	}
	public function get_value($a_params=array())
	{
		//check method 
		if(empty($a_params["method"])){
			$a_data["status"] = false;
			$a_data["message"] = "Method is null";
			return array_merge($this->_return,$a_data);
		}
		
		//check crmid
		if(empty($a_params["crmid"])){
			$a_data["status"] = false;
			$a_data["message"] = "CRM ID is null";
			return array_merge($this->_return,$a_data);
		}
		
		$crmid = $a_params["crmid"];
		$method = $a_params["method"];
		$config = $this->ci->config->item('notification');
		$queryfunction = @$config[$method]["queryfunction"];
		
		//check method function
		if($queryfunction==""){
			$a_data["status"] = false;
			$a_data["message"] = "Method is not match";
			return array_merge($this->_return,$a_data);
		}
		//get data notification
		if (method_exists($this, $queryfunction))
		{
			$a_data_return_noti = $this->{$queryfunction}($crmid,$config[$method]);
			if($a_data_return_noti["status"]===false || empty($a_data_return_noti["result"])){
				return array_merge($this->_return,$a_data_return_noti);
				exit();
			}
			$a_noti = $a_data_return_noti["result"][0];
		}else{
			$a_data["status"] = false;
			$a_data["message"] = "Method Query is null";
			return array_merge($this->_return,$a_data);
		}
		
		//replace word message				
		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_noti[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);				
				}
				
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			}
		}
		$notificationid = ($a_noti["notificationid"]==0 || $a_noti["notificationid"]=="") ?"": $a_noti["notificationid"];
		$smownerid = $a_noti["modifiedby"]=="" || $a_noti["modifiedby"] =="0"  ? $a_noti["smcreatorid"] : $a_noti["modifiedby"] ;
		
		// Get field start date
		$field_startdate = $config[$method]["startdate"];
		$field_starttime = @$config[$method]["starttime"];
		$startdate_date = @$a_noti[$field_startdate];
		$startdate_time = @$a_noti[$field_starttime];
		
		$startdate = $startdate_date .( $startdate_time != "" ? " ".$startdate_time:"");
		
		// Get field End date
		$field_enddate = $config[$method]["enddate"];
		$field_endtime = @$config[$method]["endtime"];
		$enddate_date = @$a_noti[$field_enddate];
		$enddate_time = @$a_noti[$field_endtime];
		
		$enddate = $enddate_date . ($enddate_time != "" ? " ".$enddate_time:"");
		
		if($startdate!="" && $enddate=="" ){
			$enddate =  date("Y-m-d H:i:s", strtotime(($startdate) . "+1 hour"));
		}
		
		//Get User
		$a_user = $this->get_userid($config[$method],$a_noti);
		if($a_user["status"]===false || empty($a_user["result"])){
			return array_merge($this->_return,$a_user);
			exit();
		}
		$userid = $a_user["result"];

		//Get Time Send Notification
		$a_send = $this->get_send($config[$method]["send"],$a_noti);
		if($a_send["status"]===false || empty($a_send["result"])){
			return array_merge($this->_return,$a_send);
			exit();
		}
		
		$a_send_data = $a_send["result"];
		
		//Parameter to Webservice Notification
		$a_param["Value1"] = $notificationid;
		$a_param["Value2"] = $crmid;
		$a_param["Value3"] = $method;
		$a_param["Value4"] = "";//send_total
		$a_param["Value5"] = "";//send_success;
		$a_param["Value6"] = "";//send_unsuccess*/
		$a_param["Value7"] = $a_send_data["senddate"];//send_date
		$a_param["Value8"] = $a_send_data["sendtime"];//send_time
		$a_param["Value9"] = $msg;//send_message
		$a_param["Value10"] = "1";//noti_type
		$a_param["Value11"] = "";//result_filename
		$a_param["Value12"] = "";//result_status
		$a_param["Value13"] = "";//result_errorcode
		$a_param["Value14"] = "";//result_errormsg
		$a_param["Value15"] = $userid;//userid
		$a_param["Value16"] = $smownerid;//empcd
		$a_param["Value17"] = "";//noti_status
		$a_param["Value18"] = $config["projectcode"];//project_code
		$a_param["Value19"] = $startdate;//noti_status
		$a_param["Value20"] = $enddate;//noti_status
	
				
		$method_wb = "SetNotification";	
		$url = $config["url"].$method_wb;

		$this->ci->load->library('curl');
		$this->ci->load->library('common');
		$this->ci->common->_filename= "Insert_Notification";
		$this->ci->common->set_log($url."_Begin",$a_param,array());
		
		/*$a_result["IsError"] = "false";
		$a_result["Message"] = "Success";
		$a_result["Value1"] = "S";
		$a_result["Value2"] = "Save Success";
		$a_result["Value3"] = "28";//notificationid		
		$s_result = json_encode($a_result);*/
		$s_result = $this->ci->curl->simple_post($url, $a_param,array(),"json");
		//echo "<pre>"; print_r($s_result); echo "</pre>";
		$a_response = json_decode($s_result,true);
		$notificationid = $a_response["Value3"];
		
		$a_update["crmid"] = $crmid;
		$a_update["notificationid"] = $notificationid;
		$a_update["module"] = $method;
		$a_update["msg"] = $msg;
		$a_update["senduserid"] = $userid;
		$a_update["startdate"] = $startdate;
		$a_update["enddate"] = $enddate;
		$a_update["sendtime"] = $a_param["Value7"]." ".$a_param["Value8"];
		$a_update["returnstatus"] = $a_response["IsError"] == "false" ? "1" : "0";
		$a_update["response"] = $s_result;
		$adddate = date("Y-m-d H:i:s");
		$a_update["adddt"] = $adddate;
		$a_update["upddt"] = $adddate;
		
		$sql = $this->ci->db->insert_string('aicrm_crmentity_notification', $a_update) . " 
				ON DUPLICATE KEY UPDATE 
				notificationid='".$notificationid."'
				,module='".$a_update["module"]."'
				,msg='".$a_update["msg"]."'
				,senduserid='".$a_update["senduserid"]."'		
				,startdate='".$startdate."'
				,enddate='".$enddate."'
				,sendtime='".$a_update["sendtime"]."'
				,upddt='".$adddate."'		";
		$this->ci->db->query($sql);
		
		$this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
		
		$this->ci->common->set_log($url."_End",$a_param,$s_result);
		
		$a_return["status"] =  $a_response["IsError"] === false ? true: false;
		$a_return["message"] =$a_response["Message"];
		$a_return["data"]["data"] = $a_response;
		return $a_return;
	}
  
}
