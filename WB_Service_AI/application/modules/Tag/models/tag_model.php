<?php
class Tag_model extends CI_Model
{
  var $ci;
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }

  function get_total($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Announcement";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_troubletickets.ticketid) as total");
      $this->db->join('aicrm_ticketcf', 'aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_troubletickets.ticketid',"inner");
      

      $query = $this->db->get('aicrm_troubletickets');
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_result[0];
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


  function get_list($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Announcement";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_troubletickets.*, case when (aicrm_users.user_name not like '') then concat(aicrm_users.first_name,' ',aicrm_users.last_name) else aicrm_groups.groupname end as user_name ",false);
            
      $this->db->join('aicrm_ticketcf', 'aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid', "inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_troubletickets.ticketid',"inner");
      $this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = aicrm_troubletickets.contactid',"left");
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_troubletickets.accountid',"left");
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");
      
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_troubletickets');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total($a_condition) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
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

  function get_total_list($a_condition=array(),$a_param=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Announcement";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT ai_notification.id) as total");
      $this->db->join('aicrm_announcementcf', 'aicrm_announcementcf.announcementid = aicrm_announcement.announcementid', "inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_announcement.announcementid',"inner");
      $this->db->join('ai_notification', 'ai_notification.crmid = aicrm_announcement.announcementid',"inner");
      
      if (isset($a_param["userid"]) && $a_param["userid"]!="") {
        $a_condition["ai_notification.userid"] =  $a_param["userid"] ;
        $a_condition["ai_notification.module"] =  'Announcement' ;
      }

      $query = $this->db->get('aicrm_announcement');
      /*echo $this->db->last_query();
      exit;*/
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_result[0];
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

  function get_noti_list($a_condition=array(),$a_order=array(),$a_limit=array(),$a_param=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Announcement";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_announcement.announcementid , ai_notification.crmid , ai_notification.module , ai_notification.userid ,ai_notification.message ,ai_notification.flagread ,ai_notification.flagaccept , ai_notification.datesend , ai_notification.timesend ",false);
            
      $this->db->join('aicrm_announcementcf', 'aicrm_announcementcf.announcementid = aicrm_announcement.announcementid', "inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_announcement.announcementid',"inner");
      $this->db->join('ai_notification', 'ai_notification.crmid = aicrm_announcement.announcementid',"inner");

      if (isset($a_param["userid"]) && $a_param["userid"]!="") {
        $a_condition["ai_notification.userid"] =  $a_param["userid"] ;
        $a_condition["ai_notification.module"] =  'Announcement' ;
      }

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_announcement');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_list($a_condition,$a_param) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
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


  function get_total_detail($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Announcement";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_announcement.announcementid) as total");
      $this->db->join('aicrm_announcementcf', 'aicrm_announcementcf.announcementid = aicrm_announcement.announcementid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_announcement.announcementid',"inner");
      
      $query = $this->db->get('aicrm_announcement');
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_result[0];
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


  function get_detail($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Announcement";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_announcement.announcementid,aicrm_announcement.announcement_no,aicrm_announcement.announcement_name,aicrm_announcement.detail,aicrm_announcement.senddate,aicrm_announcement.sendtime ",false);
 
      $this->db->join('aicrm_announcementcf', 'aicrm_announcementcf.announcementid = aicrm_announcement.announcementid', "inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_announcement.announcementid',"inner");
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");
      
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_announcement');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_detail($a_condition) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
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

}