<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_sms_template
{


  function __construct()
  {
    $this->ci = & get_instance();

    $this->ci->load->database();
    $this->ci->load->library('email');
    $this->ci->config->load('email');
 	  $this->_configsms = array(
			"sender_name" =>"",
			"username" =>"",
			"password" =>"",
			"phonelist" =>"",
			"msg" =>"",
 			"module" =>"",
		);
	 	$this->_return = array(
	 			"status" => false,
	 			"message" =>"",
	 			"time" => date("Y-m-d H:i:s"),
	 			"data" => array("data" => ""),
	 	);
 		
 		$this->_sms_config_id = "";
 		$this->ci->load->load->config('config_sms');

 		$this->SMS_Server = 'https://portal-otp.smsmkt.com/api/';
		$this->SMS_API_key = 'd4b10e844de51c339e701c9b3209b873';
		$this->SMS_Secret_Key = 'VzUvGaaIi8EtSy9F';
		$this->SMS_Sender = 'AISYSTEM';
		$this->SMS_Header = [
			'Content-Type: application/json',
			'api_key:'.$this->SMS_API_key,
			'secret_key:'.$this->SMS_Secret_Key,
		];
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

	public function get_data($a_params=array(),$a_service_data=array())
	{
		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];
	
		if(empty($a_service_data) || !isset($a_service_data["result"]["data"]) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't  Found Data";
  			return array_merge($this->_return,$a_data);
		}

		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}
		
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
		$a_service = $a_service_data["result"]["data"][0];
		//alert($config);
		//alert($a_service);
		
		$module = $config[$method]["module"];
		
		$field_contacttel = $config[$method]["contacttel"];
		$contacttel = str_replace("-","", $a_service[$field_contacttel]) ;
		
		$field_contactname = $config[$method]["contactname"];
		$contactname =$a_service[$field_contactname] ;
		
		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_service[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);
				}
				
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			}
		}
				
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] = $contacttel;

		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = $a_service["crmid"];
		$a_post_data["to_name"] =$contactname;
		$a_post_data["to_phone"] =$contacttel;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		if(isset($a_params['response']))
		{
			return $a_post_data;
			exit();
	
		}
		else
		{
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		//echo $id;exit();
		return $this->send_sms($id);
	
		}
	
		
	}


public function sendListsms($a_params=array())
{

		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];
		$mobile =$a_params["mobile"];
		$msg =$a_params["msg"];	
		if(empty($mobile) || !isset($mobile) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't  Found Data";
  			return array_merge($this->_return,$a_params);
		}

		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}		
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
	//	$a_service = $a_service_data["result"]["data"][0];
		$module = $config[$method]["module"];	
	
		$contacttel = str_replace("-","", $mobile) ;
		if($this->_sms_config_id =="9"){
			$contacttel = str_replace(",",";", $mobile) ;
		}
		$contactname ='';
		
		
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] =$contacttel; 

		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = $a_params["crmid"];
		$a_post_data["to_name"] =$contactname;
		$a_post_data["to_phone"] =$contacttel;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		//print_r($a_post_data);
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		 return $this->send_sms($id);
	}

 // 	public function send_sms($id="")
 //  	{
  	
 //  	$dataconfig = $this->get_config_sms_data();
 //  	$a_dataconfig = $dataconfig["result"]["data"][0];
 //  	$a_option =array();

 //  	$this->_configsms["sender_name"] = $a_dataconfig["sms_sender"];
 //  	$this->_configsms["username"]  = $a_dataconfig["sms_username"];
 //  	$this->_configsms["password"] = $a_dataconfig["sms_password"];
	

 //  	$url =  $a_dataconfig["sms_url"];
 //  	$a_param["User"] = $this->_configsms["username"] ;
 //  	$a_param["Password"] = $this->_configsms["password"] ;
	// $a_param["Msnlist"] = $this->_configsms["phonelist"]  ;//hide for test
 //  	//$a_param["Msnlist"] = "0846948899" ;
	// //$a_param["Msnlist"] = "0846948899" ;
 //  	$a_param["Msg"] =  urlencode(iconv("utf-8","tis-620",$this->_configsms["msg"] ) );
 //  	$a_param["Sender"] = $this->_configsms["sender_name"] ;
 // 	//alert($a_param); exit;
 //  	$this->ci->load->library('curl');
 //  	//$parameter = $url ."?". http_build_query($a_param, '', '&');
 //  	//$parameter = $url ."?". implode(@genurl($a_param),'&');
 //  	//$s_result = $this->ci->curl->simple_get($url, $a_option);
 //  	$s_result = $this->ci->curl->simple_post($url, $a_param,array(),"string");
 //  //	echo $url;
 //  //	alert($a_param);
 //  //	alert($s_result);
 //  	$str=explode(",",$s_result);
 //  	$str=explode("=",$str[0]);
 //  	$send_status=$str[1];

 //  	$a_data = array(
 //  		"send_result" => $s_result,
 //  		"date_start" => date("Y-m-d H:i:s"),
 //  		"STATUS" =>  $send_status=="0" ? "1":"2",
 //  	);
 //  	//$this->ci->db->set($a_data);
 //  	$this->ci->db->where('id', $id);
 //  	$this->ci->db->update('tbt_sms_log_sms',$a_data);

 //  	if($send_status=="0"){
 //  		$a_data['status'] = true;
 //  		$a_data['message'] = "Send SMS Complete";
	//   }else{
	//   	$a_data['status'] = false;
	//   	$a_data['message'] = "Can't Send SMS,Please try again";
	//   }
	//   $a_data["time"] = date("Y-m-d H:i:s");
	//   $a_data["data"]["data"] = "";
 //  	//echo $parameter;
 //  	return $a_data;
 //  }

 public function send_sms($id = "")
 {

	 $dataconfig = $this->get_config_sms_data();
	 $a_dataconfig = $dataconfig["result"]["data"][0];
	 $a_option = array();

	 $this->_configsms["sender_name"] = $a_dataconfig["sms_sender"];
	 $this->_configsms["username"]  = $a_dataconfig["sms_username"];
	 $this->_configsms["password"] = $a_dataconfig["sms_password"];

	 $url =  $a_dataconfig["sms_url"] . 'send-message';
	 $a_param["phone"] = $this->_configsms["phonelist"];
	 $a_param["message"] =  $this->_configsms["msg"];
	 $a_param["sender"] = $this->_configsms["sender_name"];
	 $this->ci->load->library('curl');
	 $this->ci->curl->http_header('api_key:' . $a_dataconfig["api_key"]);
	 $this->ci->curl->http_header('secret_key:' . $a_dataconfig["secret_key"]);
	 $s_result = $this->ci->curl->simple_post($url, $a_param, array(), "string");

	 $str = explode(",", $s_result);

	 $str = explode(":", $str[1]);

	 $send_status = $str[1];

	 $a_data = array(
		 "send_result" => $s_result,
		 "date_start" => date("Y-m-d H:i:s"),
		 "STATUS" =>  $send_status == '"OK."' ? "1" : "2",
	 );
	 $this->ci->db->where('id', $id);
	 $this->ci->db->update('tbt_sms_log_sms', $a_data);
	 if ($send_status == '"OK."') {
		 $a_data['status'] = true;
		 $a_data['message'] = "Send SMS Complete";
	 } else {
		 $a_data['status'] = false;
		 $a_data['message'] = "Can't Send SMS,Please try again";
	 }
	 $a_data["time"] = date("Y-m-d H:i:s");
	 $a_data["data"]["data"] = "";
	 return $a_data;
 }


  public function get_config_sms_data($a_condition=array())
  {
  	// echo "get_config_sms_data"; exit;
  		//if(empty($a_condition)) return null;
  		$a_condition["id"] = $this->_sms_config_id;
  		$this->ci->load->model("services/services_model");
  		$a_list=$this->ci->services_model->get_config_sender_sms($a_condition);
		// echo "<pre>";print_r($a_list);echo"</pre>"; exit;
  		return $a_list;
  }
  
  public function send_registersms_otp($a_params=array(),$a_service_data=array(),$mobile=NULL)
	{
		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];
		
		if(empty($a_service_data) || !isset($a_service_data) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't  Found Data";
  			return array_merge($this->_return,$a_data);
		}
		
		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
		$a_service = $a_service_data;
		
		
		$module = $config[$method]["module"];
		//$field_accounttel = $config[$method]["accounttel"];	
		//$accounttel = str_replace("-","", $a_service[$field_accounttel]) ;
	
		//$field_accountname = $config[$method]["accountname"];
		//$accountname =$a_service[$field_accountname] ;

		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_service[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);
				}
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			};
		}
		//echo $msg ; exit;
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] = $mobile;
		
		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = @$a_service["crmid"];
		$a_post_data["to_name"] = 'Register';
		$a_post_data["to_phone"] =$mobile;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		// echo "<pre>";print_r($a_post_data); echo"</pre>"; exit;
		if(isset($a_params['response']))
		{
			// echo "4444"; exit;
			return $a_post_data;
			exit();
		}
		else
		{
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		// echo $id;exit();
		return $this->send_sms($id);
	
		}
	}
  
  
  public function send_sms_otp($a_params=array(),$a_service_data=array(),$mobile=NULL)
	{
		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];
		
		if(empty($a_service_data) || !isset($a_service_data) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't  Found Data";
  			return array_merge($this->_return,$a_data);
		}
		
		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
		$a_service = $a_service_data;
		
		
		$module = $config[$method]["module"];
		//$field_accounttel = $config[$method]["accounttel"];	
		//$accounttel = str_replace("-","", $a_service[$field_accounttel]) ;
	
		//$field_accountname = $config[$method]["accountname"];
		//$accountname =$a_service[$field_accountname] ;

		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_service[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);
				}
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			};
		}
		//echo $msg ; exit;
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] = $mobile;
		
		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = @$a_service["crmid"];
		$a_post_data["to_name"] = 'Register';
		$a_post_data["to_phone"] =$mobile;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		// echo "<pre>";print_r($a_post_data); echo"</pre>"; exit;
		if(isset($a_params['response']))
		{
			// echo "4444"; exit;
			return $a_post_data;
			exit();
		}
		else
		{
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		// echo $id;exit();
		return $this->send_sms($id);
	
		}
	}
  /*public function send_sms_otp($a_params=array(),$a_service_data=array())
	{
		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];
		
		if(empty($a_service_data) || !isset($a_service_data) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't  Found Data";
  			return array_merge($this->_return,$a_data);
		}

		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
		$a_service = $a_service_data;
		$module = $config[$method]["module"];
		$field_accounttel = $config[$method]["accounttel"];	
		$accounttel = str_replace("-","", $a_service[$field_accounttel]) ;

		// echo $accounttel; exit;
	
		$field_accountname = $config[$method]["accountname"];
		$accountname =$a_service[$field_accountname] ;

		// echo $accountname; exit;

		
		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_service[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);
				}
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			};
		}
		// echo $msg ; exit;
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] = $accounttel;
		
		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = $a_service["crmid"];
		$a_post_data["to_name"] =$accountname;
		$a_post_data["to_phone"] =$accounttel;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		// echo "<pre>";print_r($a_post_data); echo"</pre>"; exit;
		if(isset($a_params['response']))
		{
			// echo "4444"; exit;
			return $a_post_data;
			exit();
		}
		else
		{
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		// echo $id;exit();
		return $this->send_sms($id);
	
		}
	}*/

	public function send_sms_payment($a_params=array(),$a_service_data=array())
	{

		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];

		//echo $method; exit;
		
		if(empty($a_service_data) || !isset($a_service_data) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't Found Data";
  			return array_merge($this->_return,$a_data);
		}

		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
		
		//alert($this->_sms_config_id); exit;
		$a_service = $a_service_data;
		$module = $config[$method]["module"];
		$field_contacttel = $config[$method]["accountmobile"];	
		//alert($field_contacttel); exit;
		$accountmobile = str_replace("-","", $a_service[$field_contacttel]) ;

		//$field_accountname = $config[$method]["firstname"].' '.$config[$method]["lastname"];
		$field_firstname = $config[$method]["firstname"];
		$field_lastname = $config[$method]["lastname"];
		$accountname =$a_service[$field_firstname].' '.$a_service[$field_lastname] ;

		//$accountname =$a_service[$field_accountname] ;
		//alert($accountname); exit;
		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_service[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);
				}
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			};
		}
		//echo $msg; exit;
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] = $accountmobile;

		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = $a_service["crmid"];
		$a_post_data["to_name"] =$accountname;
		$a_post_data["to_phone"] =$accountmobile;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		
		//echo "<pre>";print_r($a_post_data); echo"</pre>"; exit;
		
		if(isset($a_params['response']))
		{
			return $a_post_data;
			exit();
	
		}
		else
		{
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		return $this->send_sms($id);
	
		}
	}
	
	public function send_sms_changpassword($a_params=array(),$a_service_data=array())
	{
		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];
		
		if(empty($a_service_data) || !isset($a_service_data) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't  Found Data";
  			return array_merge($this->_return,$a_data);
		}

		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
		$a_service = $a_service_data;

		$module = $config[$method]["module"];
		
		$field_contacttel = $config[$method]["contacttel"];
		$contacttel = str_replace("-","", $a_service[$field_contacttel]) ;
		
		$field_contactname = $config[$method]["contactname"];
		$contactname =$a_service[$field_contactname] ;
		
		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_service[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);
				}
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			};
		}
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] = $contacttel;
		
		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = $a_service["crmid"];
		$a_post_data["to_name"] =$contactname;
		$a_post_data["to_phone"] =$contacttel;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		//echo "<pre>";print_r($a_post_data); echo"</pre>"; exit;
		if(isset($a_params['response']))
		{
			return $a_post_data;
			exit();
		}
		else
		{
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		//echo $id;exit();
		return $this->send_sms($id);
	
		}		
	}
	
	
	public function send_sms_contactowner($a_params=array(),$a_service_data=array())
	{
		
		$config = $this->ci->config->item('sms');
		$config_method = $config["method"];
		$method = $a_params["method"];
		
		if(empty($a_service_data) || !isset($a_service_data) ){
			$a_data["status"] = false;
  			$a_data["message"] = "Can't  Found Data";
  			return array_merge($this->_return,$a_data);
		}

		if( !isset($method) ||  !in_array($method,$config_method) ){
			$a_data["status"] = false;
			$a_data["message"] = "No Method";
			return array_merge($this->_return,$a_data);
		}
		
		$this->_sms_config_id = $config[$method]["sms_settingid"];
		
		$a_service = $a_service_data;
		$module = $config[$method]["module"];
		$field_contacttel = $config[$method]["contacttel"];	
		$contacttel = str_replace("-","", $a_service[$field_contacttel]) ;
		
		$field_contactname = $config[$method]["contactname"];
		$contactname =$a_service[$field_contactname] ;
		
		$a_field =  $config[$method]["field"];
		$msg = $config[$method]["msg"];
		if(!empty($a_field))
		{
			foreach ($a_field as $key => $value)
			{
				$fieldmap = $value["columnmap"];
				$fieldcrm = $value["columncrm"];
				$function = $value["function"];
				$data_value = @$a_service[$fieldcrm];
				if($function!=""){
					if (method_exists($this, $function))
						$data_value = $this->{$function}($data_value);
				}
				$msg = str_replace("[".$fieldmap."]",$data_value,$msg);
			};
		}
		//echo $msg ; exit;
		$this->_configsms["msg"] = $msg;
		$this->_configsms["phonelist"] = $contacttel;
		
		$a_post_data["id"] = 0;
		$a_post_data["smartsmsid"] = 0;
		$a_post_data["sms_marketingid"] = 0;
		$a_post_data["from_id"] = @$a_service["contactid"];
		$a_post_data["to_name"] =$contactname;
		$a_post_data["to_phone"] =$contacttel;
		$a_post_data["from_module"] = $module;
		$a_post_data["invalid_sms"] = '1';
		$a_post_data["msg"] = $msg;
		//echo "<pre>";print_r($a_post_data); echo"</pre>"; exit;
		if(isset($a_params['response']))
		{
			return $a_post_data;
			exit();
	
		}
		else
		{
		$this->ci->db->insert('tbt_sms_log_sms',$a_post_data);
		$id = $this->ci->db->insert_id();
		//echo $id;exit();
		return $this->send_sms($id);
	
		}
	}
	
}
