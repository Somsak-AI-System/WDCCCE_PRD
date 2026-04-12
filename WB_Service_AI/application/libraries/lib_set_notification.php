<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_set_notification
{


  function __construct()
  {
    $this->ci = & get_instance();
    $this->ci->load->database();
    $this->ci->load->library('crmentity');
    $this->ci->load->library("common");
    $this->ci->load->library('memcached_library');
    $this->ci->load->library('Lib_user_permission');
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
        'DocNo' => null
      ),
    );
  }

  function get_notification($a_condition=array(),$module="")
  {

    try {
      $a_condition["aicrm_crmentity.deleted"] = "0";


      if (!empty($a_condition)) {
        $this->ci->db->where($a_condition);
      }
      if($module=="Events" || $module=="Calendar" || $module=="Sales Visit"){
        $this->ci->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid',"inner");
        $this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid',"inner");
        $this->ci->db->join('aicrm_crmentity_notification', 'aicrm_crmentity_notification.crmid = aicrm_activity.activityid',"left");
        $query = $this->ci->db->get('aicrm_activity');
      }elseif($module=="Job") {
        $this->ci->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid',"inner");
        $this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid',"inner");
        $this->ci->db->join('aicrm_crmentity_notification', 'aicrm_crmentity_notification.crmid = aicrm_jobs.jobid',"left");
        $query = $this->ci->db->get('aicrm_jobs');
      }elseif ($module=="Leads") {
        $this->ci->db->join('aicrm_leadscf', 'aicrm_leadscf.leadid = aicrm_leaddetails.leadid',"inner");
        $this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_leaddetails.leadid',"inner");
        $this->ci->db->join('aicrm_crmentity_notification', 'aicrm_crmentity_notification.crmid = aicrm_leaddetails.leadid',"left");
        $query = $this->ci->db->get('aicrm_leaddetails');
      }
      
      // alert($this->ci->db->last_query());
      // alert($query);exit;

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->ci->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
       // alert($a_result);exit;

        $a_data["data"] = $a_result;
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_data;
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }
      }
    }catch (Exception $e) {
      $a_return["status"] = false;
      $a_return["error"] =  $e->getMessage();
      $a_return["result"] = "";
    }

    return $a_return;
  }


  public function set_notification($a_data = array(),$crmid="",$smownerid="",$module="",$action="",$userid="")
  {
    if($crmid=="") return "";
    if($smownerid=="") return "";
    $a_data_new = $a_data[0];

    $this->ci->load->config('config_notification');
    $config = $this->ci->config->item('notification');

    if($module=="Events" || $module=="Calendar" || $module=="Sales visit" ){
      $method = 'calendar';
      $a_condition["aicrm_activity.activityid"] = $crmid;
    }elseif ($module=="Job") {
      $method = 'Job';
      $a_condition["aicrm_jobs.jobid"] = $crmid;
    }elseif ($module=="Leads") {
      $method = 'Leads';
      $a_condition["aicrm_leaddetails.leadid"] = $crmid;
    }elseif ($module=="Accounts") {
      $method = 'Accounts';
      $a_condition["aicrm_account.accountid"] = $crmid;
    }elseif ($module=="Deal") {
      $method = 'Deal';
      $a_condition["aicrm_deal.dealid"] = $crmid;
    }elseif ($module=="Questionnaire") {
      $method = 'Questionnaire';
      $a_condition["aicrm_questionnaire.questionnaireid"] = $crmid;
    }

    $a_result = $this->get_notification($a_condition,$module);
    $a_activity = @$a_result["result"]["data"][0];
    $queryfunction = @$config[$method]["queryfunction"];

    if (method_exists($this, $queryfunction))
    {
      $a_data_return_noti = $this->{$queryfunction}($crmid,$config[$method],$action,$a_data_new);

      if($a_data_return_noti["status"]===false || empty($a_data_return_noti["result"])){
        return array_merge($this->_return,$a_data_return_noti);
        exit();
      }
      $a_noti = $a_data_return_noti["result"];
    }else{
      $a_data["status"] = false;
      $a_data["message"] = "Method Query is null";
      return array_merge($this->_return,$a_data);
    }

    //Get Time Send Notification
    $a_send = $this->get_send($config[$method]["send"],$a_noti);
    if($a_send["status"]===false || empty($a_send["result"])){
      return array_merge($this->_return,$a_send);
      exit();
    }

    $a_send_data = $a_send["result"];
    $notificationid = ($a_noti["notificationid"]==0 || $a_noti["notificationid"]=="") ?"": $a_noti["notificationid"];
    
    if($module=="Leads" || $module=="Accounts"){
      $sql_user = "select CONCAT(first_name,' ',last_name)as name ,user_name from aicrm_users where id='".$userid."'";
      $query_user = $this->ci->db->query($sql_user);
      $response_user = $query_user->result_array();
      if(!empty($response_user)){
        $a_noti['name']=$response_user[0]['name'];
      }
    }
    
    $msg = $this->get_message($a_noti,$module,$action);


    $field_startdate = $config[$method]["startdate"];
    $field_starttime = @$config[$method]["starttime"];
    $startdate_date = @$a_noti[$field_startdate];
    $startdate_time = @$a_noti[$field_starttime];
    if($module=="Leads" || $module=="Accounts" || $module=="Deal" || $module=="Questionnaire"){
      $startdate_date = date("Y-m-d");
      // $startdate_time = date("H:i:s");
      $startdate_time = date("H:i:s", strtotime(($startdate) . "+2 minutes"));
    }

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

    if($module=="Leads" && $action=="request_approve"){

      $data_usergroup = $this->get_leadsapprove($smownerid,$action);
    }else {
      $data_usergroup = $this->get_usergroup($smownerid);
  }

    // alert($data_usergroup);exit;

    if(!empty($data_usergroup)){
      $smownerid =$data_usergroup;
    }


    if($module=="Events" || $module=="Sales Visit" ){
      $module="Calendar";
    }
    $a_param["Value1"] = $notificationid;
    $a_param["Value2"] = $crmid;

    $a_param["Value3"] = $module;
    $a_param["Value4"] = "";//send_total
    $a_param["Value5"] = "";//send_success;
    $a_param["Value6"] = "";//send_unsuccess*/
    $a_param["Value7"] = date('Y-m-d');
    //$a_param["Value8"] = date('H:i','+1 minutes');//send_time
    $a_param["Value8"] = date('H:i', strtotime('+4 minutes'));//send_time ,

    $a_param["Value9"] = $msg;//send_message
    $a_param["Value10"] = "1";//noti_type
    $a_param["Value11"] = "";//result_filename
    $a_param["Value12"] = "";//result_status
    $a_param["Value13"] = "";//result_errorcode
    $a_param["Value14"] = "";//result_errormsg
    $a_param["Value15"] = $smownerid;//userid
    $a_param["Value16"] = $userid;//empcd
    $a_param["Value17"] = "";//noti_status
    $a_param["Value18"] = $config["projectcode"];//project_code
    $a_param["Value19"] = $startdate;//
    $a_param["Value20"] = $enddate;//
    $a_param["Value21"] = $a_noti[$config[$method]["send"]['field_crm_date']];// Start Date CRM
    $a_param["Value22"] = $a_noti[$config[$method]["send"]['field_crm_time']];// Start Time CRM

    // alert($module);
    // alert($a_param);exit;
    $method = "SetNotificationPersonal";
    $url = $config["url"].$method;
    $this->ci->load->library('curl');
    $this->ci->common->_filename= "Insert_Notification";
    $this->ci->common->set_log($url."_Begin",$a_param,array());
    //Send Noti To .Net
    $s_result = $this->ci->curl->simple_post($url, $a_param,array(),"json");
    $a_response = json_decode($s_result,true);
    $notificationid = $a_response["Value3"];
    // alert($notificationid);exit;
    // alert($s_result);
    //  alert($url);
    //  alert(json_encode($a_param));exit;

    $a_update["crmid"] = $crmid;
    $a_update["notificationid"] = $notificationid;
    $sql = $this->ci->db->insert_string('aicrm_crmentity_notification', $a_update) . " ON DUPLICATE KEY UPDATE notificationid='".$notificationid."'";
    $this->ci->db->query($sql);

    if($module=="Calendar" || $module=="Events"){
      $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
      $this->ci->common->set_log($url."_End",$a_param,$s_result);
      $this->ci->common->_filename= "Insert_Calendar";

      if($action=="commentplan"){
        $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
        $this->ci->common->set_log($url."_End",$a_param,$s_result);
        $this->ci->common->_filename= "Comment_Calendar";
      }else {
        $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
        $this->ci->common->set_log($url."_End",$a_param,$s_result);
        $this->ci->common->_filename= "Insert_Calendar";
      }

    }
    if($module=="Job"){
      $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
      $this->ci->common->set_log($url."_End",$a_param,$s_result);
      $this->ci->common->_filename= "Insert_Job";

    }
    if($module=="Leads"){
      $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
      $this->ci->common->set_log($url."_End",$a_param,$s_result);
       $this->ci->common->_filename= "Insert_Leads";
      // if($action=="request_approve"){
      //   $this->ci->common->_filename= "Request_Approve";
      // }elseif($action=="Approve") {
      //   $this->ci->common->_filename= "Approve";
      // }else {
      //   $this->ci->common->_filename= "Reject";
      // }
    }

    if($module=="Accounts"){
      $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
      $this->ci->common->set_log($url."_End",$a_param,$s_result);
      $this->ci->common->_filename= "Insert_Accounts";
    }

    if($module=="Deal"){
      $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
      $this->ci->common->set_log($url."_End",$a_param,$s_result);
      $this->ci->common->_filename= "Insert_Deal";
    }

    if($module=="Questionnaire"){
      $this->ci->common->set_log($url." update data ",$this->ci->db->last_query(),"");
      $this->ci->common->set_log($url."_End",$a_param,$s_result);
      $this->ci->common->_filename= "Insert_Questionnaire";
    }

    // alert($s_result);exit;

    return $s_result;
  }

  public function get_message( $data=array() ,$module,$action=""){

    if($module=="Events"  || $module=="Calendar"){
      $message = 'วันที่ : '.date("d-m-Y", strtotime($data['date_start'])).'\nเวลา : '.$data['time_start'].'\nObj : '.$data['activitytype'].'\nCustomer Name : '.$data['accountname'];
    }elseif ($module=="Job") {
      $message = 'วันที่ : '.date("d-m-Y", strtotime($data['jobdate'])).'\nเวลา : '.$data['notification_time'].'\nJob Name : '.$data['job_name'].'\nCustomer Name : '.$data['accountname'];
    }elseif ($module=="Leads") {
      $message = 'วันที่ : '.date("d-m-Y", strtotime($data['modifiedtime'])).'\nเวลา : '.date("H:i:s", strtotime($data['modifiedtime'])).'\nLead Name : '.$data['leadname'].'\nLead Status : '.$data['leadstatus'];
      // if($action=="request_approve"){
      //   $message = 'แจ้งเตือนเรื่อง : Request Approve for convert Lead \nLead Name : '.$data['leadname'].'\nRequest by : '.$data['name'];
      // }else {
      //   $message = 'แจ้งเตือนเรื่อง : '.$action.' the Request for convert Lead \nLead Name : '.$data['leadname'].'\Approve by : '.$data['name'];
      // }
    }elseif ($module=="Accounts") {
      $message = 'วันที่ : '.date("d-m-Y", strtotime($data['modifiedtime'])).'\nเวลา : '.date("H:i", strtotime($data['modifiedtime'])).'\nAccount Name : '.$data['accountname'].'\nMobile : '.$data['mobile'];
    }elseif ($module=="Deal") {
      $message = 'วันที่ : '.date("d-m-Y", strtotime($data['modifiedtime'])).'\nเวลา : '.date("H:i", strtotime($data['modifiedtime'])).'\nDeal No : '.$data['deal_no'].'\nDeal Stage : '.$data['stage'];
    }elseif ($module=="Questionnaire") {
      $message = 'วันที่ : '.date("d-m-Y", strtotime($data['modifiedtime'])).'\nเวลา : '.date("H:i", strtotime($data['modifiedtime'])).'\nQuestionnaire Name : '.$data['questionnaire_name'];
    }

    // alert($action);
    // alert($data);
    // alert($message);exit;

    return $message;
  }

  public function get_calendar($crmid="",$a_cofig=array(),$action="",$a_data_new="")
  {

    $activitytype = $a_data_new['activitytype'];
    $smownerid = $a_data_new['smownerid'];
    $date_start = $a_data_new['date_start'];
    $time_start= $a_data_new['time_start'];
    $due_date= $a_data_new['due_date'];
    $time_end= $a_data_new['time_end'];
    $eventstatus= $a_data_new['eventstatus'];
    $account_id= $a_data_new['accountid'];

    $sql = " select aicrm_activity.*,aicrm_activitycf.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid ,aicrm_account.accountname
    from aicrm_activity
    inner join aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
    left join aicrm_account on aicrm_activity.parentid = aicrm_account.accountid
    inner join aicrm_crmentity on aicrm_activity.activityid = aicrm_crmentity.crmid
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
      $a_data = $a_data[0];
// alert($a_data);exit;
      // foreach ($a_data as $key => $value) {
      if($action=="edit" && ($activitytype!=$a_data['activitytype'] || $smownerid!=$a_data['smownerid'] || $date_start!=$a_data['date_start'] || $time_start!=$a_data['time_start']
      || $due_date!=$a_data['due_date'] || $time_end!=$a_data['time_end'] || $eventstatus!=$a_data['eventstatus'] || $account_id!=$a_data['accountid'])){
        $a_data['notificationid']="";
        $a_data = $a_data;

      }elseif ($action=="edit" && $activitytype==$a_data['activitytype'] && $smownerid==$a_data['smownerid'] && $date_start==$a_data['date_start'] && $time_start==$a_data['time_start']
      && $due_date==$a_data['due_date'] && $time_end==$a_data['time_end'] && $eventstatus==$a_data['eventstatus'] && $account_id==$a_data['accountid']) {
        $a_data = $a_data;
      }elseif ($action=="commentplan") {
        $a_data['notificationid']="";
        $a_data = $a_data;
      }

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

  public function get_job($crmid="",$a_cofig=array(),$action="",$a_data_new="")
  {

    $smownerid = $a_data_new['smownerid'];

    $sql = " select aicrm_jobs.*,aicrm_jobscf.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid ,aicrm_account.accountname
    from aicrm_jobs
    inner join aicrm_jobscf on aicrm_jobscf.jobid = aicrm_jobs.jobid
    left join aicrm_account on aicrm_account.accountid = aicrm_jobs.accountid
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_jobs.jobid
    left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid";

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
      $a_data = $a_data[0];
      // foreach ($a_data as $key => $value) {
      if($action=="edit" && $smownerid!=$a_data['smownerid']){
        $a_data['notificationid']="";
        $a_data = $a_data;
        // echo 111;
        // alert($a_data);exit;
      }elseif ($action=="edit" && $smownerid==$a_data['smownerid']) {
        $a_data = $a_data;
      }

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

  
  public function get_leads($crmid="",$a_cofig=array(),$action="",$a_data_new="")
  {

    $smownerid = $a_data_new['smownerid'];

    $sql = " select aicrm_leaddetails.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid,
    CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname)  AS leadname
    from aicrm_leaddetails
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
    left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid";

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
      $a_data = $a_data[0];

      if($action=="edit" && $smownerid!=$a_data['smownerid']){
        $a_data['notificationid']="";
        $a_data = $a_data;
        // echo 111;
        // alert($a_data);exit;
      }elseif ($action=="edit" && $smownerid==$a_data['smownerid']) {
        $a_data = $a_data;
      }
      
     
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

    public function get_accounts($crmid="",$a_cofig=array(),$action="",$a_data_new="")
  {

    $smownerid = $a_data_new['smownerid'];

    $sql = " select aicrm_account.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid
    from aicrm_account
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
    left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid";

    $sql .= " where aicrm_crmentity.deleted = 0 ";
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
      $a_data = $a_data[0];

      if($action=="edit" && $smownerid!=$a_data['smownerid']){
        $a_data['notificationid']="";
        $a_data = $a_data;
        // echo 111;
        // alert($a_data);exit;
      }elseif ($action=="edit" && $smownerid==$a_data['smownerid']) {
        $a_data = $a_data;
      }
      
     
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


   public function get_deal($crmid="",$a_cofig=array(),$action="",$a_data_new="")
  {

    $smownerid = $a_data_new['smownerid'];

    $sql = " select aicrm_deal.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid
    from aicrm_deal
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
    left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid";

    $sql .= " where aicrm_crmentity.deleted = 0 ";
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
      $a_data = $a_data[0];

      if($action=="edit" && $smownerid!=$a_data['smownerid']){
        $a_data['notificationid']="";
        $a_data = $a_data;
        // echo 111;
        // alert($a_data);exit;
      }elseif ($action=="edit" && $smownerid==$a_data['smownerid']) {
        $a_data = $a_data;
      }
      
     
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


   public function get_questionnaire($crmid="",$a_cofig=array(),$action="",$a_data_new="")
  {

    $smownerid = $a_data_new['smownerid'];

    $sql = " select aicrm_questionnaire.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid
    from aicrm_questionnaire
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
    left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid";

    $sql .= " where aicrm_crmentity.deleted = 0 ";
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
      $a_data = $a_data[0];

      if($action=="edit" && $smownerid!=$a_data['smownerid']){
        $a_data['notificationid']="";
        $a_data = $a_data;
        // echo 111;
        // alert($a_data);exit;
      }elseif ($action=="edit" && $smownerid==$a_data['smownerid']) {
        $a_data = $a_data;
      }
      
     
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


  public function get_usergroup($smownerid=""){


    $sql_userlist = "select * from aicrm_users2group where groupid= '".$smownerid."' ";
    $query = $this->ci->db->query($sql_userlist);
    $a_data =  $query->result_array() ;
    $data=array();
    $groupid="";
    if(!empty($a_data)){
      foreach ($a_data as $key => $value) {
        $groupid .= "'".$value['groupid']."',";
        $wheregroupid =  rtrim($groupid,",");

      }
      $data_group = $this->getGroupUsers($wheregroupid);
      if(!empty($data_group)){
        foreach ($data_group as $key => $value) {
          array_push($data,$value);
        }
      }
    }

    $sql_userlist = "select * from aicrm_group2role where groupid= '".$smownerid."' ";
    $query = $this->ci->db->query($sql_userlist);
    $a_data =  $query->result_array() ;
    $roleid="";
    if(!empty($a_data)){
      foreach ($a_data as $key => $value) {
        $roleid .= "'".$value['roleid']."',";
        $whererole =  rtrim($roleid,",");
      }
      $data_role = $this->getRoleUsers($whererole);
      if(!empty($data_role)){
        foreach ($data_role as $key => $value) {
          array_push($data,$value);
        }
      }
    }

    $sql_userlist = "select * from aicrm_group2rs where groupid= '".$smownerid."' ";
    $query = $this->ci->db->query($sql_userlist);
    $a_data =  $query->result_array() ;
    $rolesub="";
    if(!empty($a_data)){
      foreach ($a_data as $key => $value) {
        $rolesub .= "'".$value['roleandsubid']."',";
        $roleandsubid =  rtrim($rolesub,",");
      }
      $data_sub = $this->getRoleAndSubordinateUsers($roleandsubid);
      $data_roleandsubid =  $data_sub;
      if(!empty($data_roleandsubid)){
        foreach ($data_roleandsubid as $key => $value) {
          array_push($data,$value);
        }
      }
    }

    $sql_userlist = "select * from aicrm_group2grouprel where groupid= '".$smownerid."' ";
    $query = $this->ci->db->query($sql_userlist);
    $a_data =  $query->result_array() ;
    $containsgroupid="";
    if(!empty($a_data)){
      foreach ($a_data as $key => $value) {
        $containsgroupid .= "'".$value['containsgroupid']."',";
        $wherecontainsgroup =  rtrim($containsgroupid,",");
      }
      $data_containsgroup = $this->getGroupUsers($wherecontainsgroup);
      if(!empty($data_containsgroup)){
        foreach ($data_containsgroup as $key => $value) {
          array_push($data,$value);
        }
      }
    }

    if(!empty($data)){
      $data = implode( ",", $data );

    }else {
      $data = $smownerid;
    }
    // alert($data);exit;

    return $data;

  }

  
  public function get_leadsapprove($smownerid="",$action=""){

    $sql_approve_lead = "select entity,entity_value from aicrm_approve_lead  ";
    $query = $this->ci->db->query($sql_approve_lead);
    $data_approve =  $query->result_array();
    $entity = $data_approve[0]["entity"];
    $entity_value = $data_approve[0]["entity_value"];
    $userid="";

    if($entity=="Role"){
      $sql_user = "SELECT aicrm_user2role.userid from aicrm_users
      LEFT JOIN  aicrm_user2role on  aicrm_user2role.userid = aicrm_users.id
      WHERE roleid ='".$entity_value."'";
      $query_user = $this->ci->db->query($sql_user);
      $data_user =  $query_user->result_array();
      foreach ($data_user as $key => $value) {
        $userid .= "".$value['userid'].",";
        $user_id =  rtrim($userid,",");
      }
      $data = $user_id;
    }else {
      $data = $entity_value;
    }

  // $data="2";

  return $data;
}

  public function getGroupUsers($groupid){

    $query_user = " select * from aicrm_users2group where groupid in  (".$groupid.")";
    $query = $this->ci->db->query($query_user);
    $data_user =  $query->result_array() ;
    $data=array();

    foreach ($data_user as $key => $value) {
      if($value['userid']!=""){
        $data[] = $value['userid'];
      }
    }
    return $data;
  }

  public function getRoleUsers($roleid){

    $query_user = " select aicrm_user2role.userid from aicrm_role
    LEFT JOIN aicrm_user2role on aicrm_user2role.roleid = aicrm_role.roleid
    WHERE aicrm_role.roleid in (".$roleid.")";
    $query = $this->ci->db->query($query_user);
    $data_user =  $query->result_array() ;
    $data=array();

    foreach ($data_user as $key => $value) {
      if($value['userid']!=""){
        $data[] = $value['userid'];

      }

    }
    return $data;
  }


  public function getRoleAndSubordinateUsers($roleandsubid){
    $query_parent = "select * from aicrm_role where roleid in (".$roleandsubid.")";
    $query = $this->ci->db->query($query_parent);
    $data_parent=  $query->result_array() ;
    $data=array();

    foreach ($data_parent as $key => $value) {

      $parentrole = $value['parentrole'];

      if(!empty($parentrole)){

        $query_user = " select aicrm_user2role.*,aicrm_users.user_name from aicrm_user2role
        inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid
        inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid
        where aicrm_role.parentrole like '".$parentrole."'";

        $query = $this->ci->db->query($query_user);
        $data_user =  $query->result_array() ;
        foreach ($data_user as $k => $v) {
          if($v['userid']!=""){
            $data[] = $v['userid'];

          }

        }

      }

    }

    return $data;
  }


  // public function get_usergroup($smownerid=""){
  //
  //
  //   $sql_userlist = "select  aicrm_users.id  as userid
  //   FROM aicrm_groups
  //   inner join aicrm_crmentity on aicrm_groups.groupid = aicrm_crmentity.smownerid
  //   inner JOIN aicrm_users2group on aicrm_users2group.groupid =  aicrm_groups.groupid
  //   LEFT JOIN aicrm_users on aicrm_users.id =  aicrm_users2group.userid
  //   WHERE aicrm_groups.groupid = '".$smownerid."' and aicrm_users.deleted=0 group by aicrm_users2group.userid";
  //
  //   $query = $this->ci->db->query($sql_userlist);
  //   $a_data =  $query->result_array() ;
  //
  //   if(empty($a_data)){
  //     $data = $smownerid;
  //     // $data[] = array("userid"=>$smownerid);
  //   }else {
  //     $data = $a_data;
  //     foreach ($data as $key => $value) {
  //       $data_value = implode( ",", $value );
  //       $data_3 .= $data_value.",";
  //       $test =  rtrim($data_3,",");
  //       $data = $test;
  //     }
  //   }
  //
  //   return $data;
  //
  // }


}
