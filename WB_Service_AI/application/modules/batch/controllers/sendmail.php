<?php
//echo "555";
ini_set('max_execution_time', 36000);
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Sendmail extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
        $this->load->database();
		$this->load->library("common");
		$this->load->library("lib_sendmail");
		$this->_format = "array";
		
		$this->_return = array(
				'Type' => "S",
				'Message' => "Complete",
				'cache_time' => date("Y-m-d H:i:s"),	
				'total' => "0",
				'data' => array(),
		);

  }

  public function return_data($module,$a_param,$a_data)
  {
  	//alert($a_data);
  	if($a_data)
  	{
  		$format =  $this->input->get("format",true);
  		$a_return["Type"] = ($a_data["status"])?"S":"E";
  		$a_return["Message"] =$a_data["error"];
  		$a_return["total"] = isset($a_data["total"]) && $a_data["total"]!="" ? $a_data["total"] :0;
  		$a_return["data"] = $a_data["result"];
  		$a_return= array_merge($this->_return,$a_return);
   		$this->common->set_log($module." End",$a_param,$a_return);
  		if ($format!="json" && $format!="xml"  ) {
  			$this->response($a_return, 200); // 200 being the HTTP response code
  		}else{
  			$this->response($a_return, 200); // 200 being the HTTP response code
  		}
  	}
  	else
  	{
  		$this->response(array('error' => 'Couldn\'t find!'), 404);
  	}
  }
  
  public function weeklyplan_post()
  {
  	$this->common->_filename= "Sendmail_weeklyplan";
  	$module = "weeklyplan_post";
  	$request_body = file_get_contents('php://input');
  	$a_param     = json_decode($request_body,true);
  	$this->common->set_log($module." Begin",$a_param,array());
  	$a_data =$this->weeklyplan($a_param,$module);
  	$this->return_data($module,$a_param,$a_data);
  }
  public function weeklyplan_get()
  {
  	$this->common->_filename= "Sendmail_weeklyplan";
  	$module = "weeklyplan_get";
	 $a_param =  $this->input->get(); 	
 	$this->common->set_log($module." Begin",$a_param,array());

  	$a_data =$this->weeklyplan($a_param,$module);
  	$this->return_data($module,$a_param,$a_data);
  }
  public function weeklyplan($a_param,$module)
  {	  
	  $a_return = array();
	 
	  $currentdate = isset($a_param["date_run"]) && $a_param["date_run"]!="" ? $a_param["date_run"] : date("Y-m-d H:i");
	  if(empty($a_param["runtype"]) || ($a_param["runtype"]!="1" && $a_param["runtype"]!="2") )
	  {
	  	$a_response["status"] = false;
	  	$a_response["error"] =  "runtype ไม่ถูกต้อง";
	  	$a_response["result"] = "";
	  	return $a_response;
 		exit();
	  }
	  
	  if($a_param["runtype"]=="1")
	  {
	  	//runtype = auto
	  	$curdateofweek =  date( 'l', strtotime( $currentdate ));
	  	$curtime =  date("H:i", strtotime( $currentdate ));
	  	$a_paramter["date"] =  $curdateofweek;
	  	$a_paramter["time"] =  $curtime;
	  }
	  else 
	  {
	  	//runtype = manual
	  	if(empty($a_param["section"]) || $a_param["section"]==""){
	  		$a_response["status"] = false;
	  		$a_response["error"] =  "ไม่มีข้อมูล Section";
	  		$a_response["result"] = "";
	  		return $a_response;
	  		exit();
	  	}
	  	$a_paramter["section"] =  $a_param["section"];
	  }
 	  
	  $a_section = $this->lib_sendmail->get_config_weekly($a_paramter);
	  if(empty($a_section)){
	  	$a_response["status"] = false;
	  	$a_response["error"] =  "No Data";
	  	$a_response["result"] = "";
	  	return $a_response;
 		exit();
	  }
	  
	
	 	foreach ($a_section as $k => $v)
	  	{
	  		$date_week = strtotime($v["date_send"]." this week", strtotime($currentdate));
	  		$date_week = date("Y-m-d",$date_week);
	  		$currentdate = $date_week." ".$v["time_send"];
	  		
	  		$v["currentdate"] = $currentdate;
	  		//alert($v);exit();
	  		if(!empty($v["send_to"])){
	  			$a_param_user["userid"] =  $v["send_to"];
	  			$a_usermail =  $this->lib_sendmail->get_mail_user($a_param_user);
	  			
	  		}
	  		
	  		$a_return[$k]["config"] = $v;
	  		$a_return[$k]["config"]["mailto"] = @$a_usermail;	  		
	  		$a_return[$k]["weeklyplan"] = $this->lib_sendmail->getdata_weekly($v); 
	  		$a_return[$k]["dailyreport"] = $this->lib_sendmail->getdata_daily($v);
	  	}
	  
	  $a_response["status"] = true;
	  $a_response["error"] =  "";
	  $a_response["total"] = count($a_section);
	  $a_response["result"] = $a_return;
	  return $a_response;
  }//end weeklyplan

  
  public function monthlyplan_post()
  {
  	$this->common->_filename= "Sendmail_monthlyplan";
  	$module = "monthlyplan_post";
  	$request_body = file_get_contents('php://input');
  	$a_param     = json_decode($request_body,true);
  	$this->common->set_log($module." Begin",$a_param,array());
  	$a_data =$this->monthlyplan($a_param,$module);
  	$this->return_data($module,$a_param,$a_data);
  }
  public function monthlyplan_get()
  {
  	$this->common->_filename= "Sendmail_monthlyplan";
  	$module = "monthlyplan_get";
  	$a_param =  $this->input->get();
  	$this->common->set_log($module." Begin",$a_param,array());
  
  	$a_data =$this->monthlyplan($a_param,$module);
  	$this->return_data($module,$a_param,$a_data);
  }
  
  public function monthlyplan($a_param,$module)
  {
  	$a_return = array();
  
  	$currentdate = isset($a_param["date_run"]) && $a_param["date_run"]!="" ? $a_param["date_run"] : date("Y-m-d H:i");
  	if(empty($a_param["runtype"]) || ($a_param["runtype"]!="1" && $a_param["runtype"]!="2") )
  	{
  		$a_response["status"] = false;
  		$a_response["error"] =  "runtype ไม่ถูกต้อง";
  		$a_response["result"] = "";
  		return $a_response;
  		exit();
  	}
  	 
  	$curdateofmonth =  date( 'd', strtotime( $currentdate ));
  	$curtime =  date("H:i", strtotime( $currentdate ));
  	if($a_param["runtype"]=="1")
  	{
  		//runtype = auto  		
  		$a_paramter["date"] =  $curdateofmonth;
  		$a_paramter["time"] =  $curtime;
  	}
  	else
  	{
  		//runtype = manual
  		if(empty($a_param["section"]) || $a_param["section"]==""){
  			$a_response["status"] = false;
  			$a_response["error"] =  "ไม่มีข้อมูล Section";
  			$a_response["result"] = "";
  			return $a_response;
  			exit();
  		}
  		$a_paramter["section"] =  $a_param["section"];
  	}
  
  	//get lastdate from currentdate or parameter date
  	$last_date_find = strtotime(date("Y-m-d", strtotime($currentdate)) . ", last day of this month");
  	$last_date = date("d",$last_date_find);
  	$lastdateflg = 0;
  	if($last_date==$curdateofmonth){
  		$lastdateflg = "1";
  	}
  	$a_paramter["lastdateflg"] =  $lastdateflg;
  	//echo $lastdateflg;
  	//echo $last_date;exit();
  	$a_section = $this->lib_sendmail->get_config_monthly($a_paramter);
  	//alert($a_section);exit();
  	if(empty($a_section)){
  		$a_response["status"] = false;
  		$a_response["error"] =  "No Data";
  		$a_response["result"] = "";
  		return $a_response;
  		exit();
  	}
  	 
  	foreach ($a_section as $k => $v)
  	{
  		if($v["date_send"]=="99"){
  			$date_month = date("Y-m-d",$last_date_find); 
  		}else{
  			$date_month = date("Y-m",strtotime($currentdate));  			
  			$date_month =  $date_month."-".str_pad($v["date_send"],2,"0",STR_PAD_LEFT);
  		}
  		
  		$currentdate = $date_month." ".$v["time_send"];
  		
  		$v["currentdate"] = $currentdate;
  	  
  		if(!empty($v["send_to"])){
  			$a_param_user["userid"] =  $v["send_to"];
  			$a_usermail =  $this->lib_sendmail->get_mail_user($a_param_user);
  		}
  	  
  		$a_return[$k]["config"] = $v;
  		$a_return[$k]["config"]["mailto"] = @$a_usermail;
  		$a_return[$k]["monthlyplan"] = $this->lib_sendmail->getdata_monthly($v);
  		
  	}
  	 
  	$a_response["status"] = true;
  	$a_response["error"] =  "";
  	$a_response["total"] = count($a_section);
  	$a_response["result"] = $a_return;
  	return $a_response;
  }//end weeklyplan
 

}