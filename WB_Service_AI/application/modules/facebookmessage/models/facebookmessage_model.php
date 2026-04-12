<?php
class facebookmessage_model extends CI_Model
{
  var $ci;

  /**
   */
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }

  /*function get_list($customerid='')
  {
    try {
      
      $a_condition["message_customer.deleted"] = "0";
      $a_condition["message_customer.customerid"] = $customerid;
      $this->db->select('message_customer.customerid, message_customer.pictureurl, message_customer.channel,message_customer.socialname, case when message_customer.module = "Accounts" then aicrm_account.newsocialname else aicrm_leaddetails.newsocialname end as newsocialname, message_customer.parentid, message_customer.module, IFNULL(message_chathistory1.unread, "0") as unread, CASE WHEN message_chathistory2.total_message = "1" THEN "เพิ่มเพื่อน" WHEN message_chathistory2.message_type = "image" THEN "Image"
        WHEN message_chathistory2.message_type = "sticker" THEN "Sticker"
        WHEN message_chathistory2.message_type = "file" THEN "Attach File"
        WHEN message_chathistory2.message_type = "video" THEN "Video"
        WHEN message_chathistory2.message_type = "audio" THEN "Audio"
        WHEN message_chathistory2.message_type = "location" THEN "Location" ELSE message_chathistory2.message END AS lastmessage, message_chathistory2.messagetime as lastupdate, message_customer.createdate as firstcontact, message_customer.customerno as socialid, case when message_customer.module = "Accounts" then if(aicrm_account.interest="--None--" ,"",aicrm_account.interest) else if(aicrm_leaddetails.interest="--None--" ,"",aicrm_leaddetails.interest) end as interest, case when message_customer.module = "Accounts" then aicrm_account.spam else aicrm_leaddetails.spam end as spam, case when message_customer.module = "Accounts" then aicrm_account.chat_status else aicrm_leaddetails.chat_status end as chat_status , message_chathistory2.total_message , "2021-02-16 09:30:40" as doneat ,"" as finishat,message_customer.auto_reply , CASE WHEN message_customer.channel = "facebook" and modifiedtime < DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN "1" ELSE "0" END AS disable ',false);
      $this->db->join('( select customerid,count(flag_read) as unread from message_chathistory where flag_read = 0 group by customerid ) message_chathistory1', 'message_chathistory1.customerid = message_customer.customerid',"left");
      $this->db->join('( SELECT c1.customerid , c1.message , c1.messagetime ,c2.total_message ,c1.message_type FROM message_chathistory c1 INNER JOIN ( SELECT customerid, MAX(chatid) AS max_chatid, COUNT(*) AS total_message FROM message_chathistory GROUP BY customerid ) c2 ON c1.customerid = c2.customerid and c2.max_chatid = c1.chatid group by customerid order by messagetime DESC ) message_chathistory2', 'message_chathistory2.customerid = message_customer.customerid',"left");
      $this->db->join('aicrm_account', 'aicrm_account.accountid = message_customer.parentid and message_customer.module = "Accounts" ',"left");
      $this->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = message_customer.parentid and message_customer.module = "Leads" ',"left");
      
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $this->db->group_by('message_customer.customerid'); 

      $query = $this->db->get('message_customer');
      
      //echo $this->db->last_query(); exit;

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        //$a_total = $this->get_total($a_condition,$a_order,$a_limit,$a_params) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        
        $a_data["total"] = (@$a_total["status"]) ? @$a_total["result"] : 0;
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
  }*/


  /*function get_list_detail($customerid='')
  {
    try {

      $a_condition["message_chathistory.deleted"] = "0";
      $a_condition["message_chathistory.customerid"] = $customerid;
      //$this->db->select('chatno,customerid,customerno as socialid ,messageaction,chatactionname,message,messagetime,isagent,channel,flag_read,deleted',false);
      
      $this->db->select('message_chathistory.chatno, message_chathistory.customerid,message_chathistory.customerno as socialid,message_chathistory.messageaction,message_chathistory.chatactionname, message_chathistory.message, message_chathistory.messagetime, message_chathistory.isagent,message_chathistory.channel, message_chathistory.flag_read, message_chathistory.deleted, ifnull(concat(aicrm_users.first_name," " ,aicrm_users.last_name),"") as agent_name , 
        case when aicrm_social_config.image_profile is null then "'.$protocol.$domainName.'/storage/imagesocial/tmp.png" 
        else aicrm_social_config.image_profile end as socail_profile ,message_chathistory.message_type 
        ' , false);      
      $this->db->join('message_customer', 'message_customer.customerid = message_chathistory.customerid',"inner");
      $this->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = message_chathistory.userid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }

      $this->db->limit(1);

      $this->db->order_by('message_chathistory.messagetime', 'DESC');

      $query = $this->db->get('message_chathistory');
      
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
        //$a_total = $this->get_total_detail($a_condition,$a_limit) ;
        $a_total = 0;
        //alert($a_total);exit;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"] : 0;
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
  }*/

}