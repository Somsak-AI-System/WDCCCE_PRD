<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class General
{

  function __construct()
  {
    $this->ci = & get_instance();
    $this->ci->load->config('api');
    $this->ci->load->library('curl');
    $this->ci->load->library('common');

  }
  function get_plant(){
  	$this->ci->load->model("truck/truck_model");
	$a_result = $this->ci->truck_model->get_plant();
	return $a_result;
 }



 public function call_sap($method="",$a_param=array())
 {
 	if($method=="") return false;
 	$configsap =  $this->ci->config->item('sap');
 	$url = $configsap["url"]. $method;

 	$response = $this->ci->curl->simple_post($url,$a_param,array(),"json");
 	$a_response = json_decode($response,true);

 	$this->ci->common->set_log("call sap ".$url,$a_param,$a_response);

 	if(isset($a_response["IsError"]) && !$a_response["IsError"])
 	{

 		$a_return["status"] = true;

 		/*if(empty($a_response["ResultList"])){
 			$a_return["status"] = false;
 			$a_return["msg"] = "No Response From SAP";
 			return $a_return;
 		}



 		$result = $a_response["ResultList"][0];
 		if(!empty($result)){
 			$msg_type = $result["MessageType"];
 			if($msg_type=="S"){
 				$a_return["status"] = true;
 				$a_return["msg"] = "";
 			}else{
 				$a_return["status"] = false;
 				$a_return["msg"] = "SAP : ".$result["Message"];
 			}
 		}*/


 	}
 	else
 	{
 		$a_return["status"] = false;

 	}
 	$a_return["msg"] = $a_response["Message"];
 	$a_return["result"] = $a_response;
 	return $a_return;
 }


 public function call_ap($method="",$a_param=array())
 {
 	if($method=="") return false;

 	if(empty($a_param)){
 		$this->ci->common->set_log(" call_ap ","","response:no data");
 		return false;
 	}
 	$configap =  $this->ci->config->item('ap');
 	$url = $configap["url"]. $method;

 	$response = $this->ci->curl->simple_post($url,$a_param,array(),"json");
 	$a_response = json_decode($response,true);

 	$this->ci->common->set_log(" call ap ".$url,$a_param,$a_response);
 	if(isset($a_response["IsError"]) && !$a_response["IsError"])
 	{
 		$a_return["status"] = true;
 		$a_return["msg"] = "";
 	}else{
 		$a_return["status"] = false;
 		$a_return["msg"] = "Auto Plant : ".$a_response["Message"];
 	}
 	return $a_return;
 }


 public function getepcbytagid($number = '')
  {
     $url = "http://10.224.29.228:10030/DataControl/GetEPCByTagID" ;
    // $url = "http://10.224.29.222:10011/ReceiveTag/GetEPCByTagID" ;

     $a_param["Value1"] = $number;


     $a_param["ChannelCode"] = "web";
     $a_param["UserName"] = "web";


    $response =  $this->ci->curl->simple_post($url,$a_param,array(),"json");
    $a_response = json_decode($response,true);
    return  $a_response;


  }


  public function setopenbarrier($channlecd = '', $username = '')
  {

     $url = "http://10.224.29.228:10020/DeviceCommand/SendDeviceCommand";
     $a_param["Module"] = "OpenBarrier";
     $a_param["ChannelCode"] = $channlecd;
     $a_param["UserName"] = $username;


    $response =  $this->ci->curl->simple_post($url,$a_param,array(),"json");
    $a_response = json_decode($response,true);
    return  $a_response;


  }

    public function SAPProcessOrder($orderdtl = '')
  {

       $url = "http://10.224.29.228:10050/SAPDataControl/SAPProcessOrder" ;

     $a_param["Value1"] = "0";
     $a_param["Value2"] = "0";
     $a_param["Value3"] = $orderdtl;
     $a_param["Value4"] = "P";
     $a_param["ChannelCode"] = "web";
     $a_param["UserName"] = "web";

     $a_param["ChannelCode"] = "web";
     $a_param["UserName"] = "web";


    $response =  $this->ci->curl->simple_post($url,$a_param,array(),"json");
    $a_response = json_decode($response,true);
    return  $a_response;




  }

}
