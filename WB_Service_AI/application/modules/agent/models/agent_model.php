<?php
class agent_model extends CI_Model
{
  var $ci;

  /**
   */
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
    $this->_limit = 100;
  }

  function get_total($a_condition=array(),$a_order=array(),$a_limit=array(),$a_params=array())
  {
    try {
      
      $a_condition["message_customer.deleted"] = "0";
      
      //$this->db->select("count(DISTINCT message_customer.customerid) as total");
      $this->db->select("message_customer.customerid");
      $this->db->join('( select customerid,count(flag_read) as unread from message_chathistory where flag_read = 0 group by customerid ) message_chathistory1', 'message_chathistory1.customerid = message_customer.customerid',"left");
      
      $this->db->join('( select customerid,message,messagetime from message_chathistory group by customerid order by messagetime DESC ) message_chathistory2', 'message_chathistory2.customerid = message_customer.customerid',"left");
      
      $this->db->join('(SELECT message ,customerid FROM message_chathistory ) message_chathistory3', 'message_chathistory3.customerid = message_customer.customerid',"left");

      //$this->db->join('aicrm_account', 'aicrm_account.accountid = message_customer.parentid and message_customer.module = "Accounts" ',"left");
      //$this->ci->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = message_customer.contactid and message_customer.module = "Accounts" ',"left");
      $this->db->join('(SELECT aicrm_contactdetails.* FROM aicrm_contactdetails inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid where aicrm_crmentity.deleted = 0 ) as aicrm_contactdetails','aicrm_contactdetails.contactid = message_customer.contactid and message_customer.module = "Accounts" ',"left");
      $this->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = message_customer.parentid and message_customer.module = "Leads" ',"left");
      $this->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = message_customer.smownerid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }

      if(isset($a_params["spam"]) && $a_params["spam"] == true) {

        $this->db->where("(aicrm_contactdetails.spam='1' OR aicrm_leaddetails.spam = '1')", NULL, FALSE);

      }else if(isset($a_params["myjob"]) && $a_params["myjob"] == true) {

        $this->db->where("message_customer.smownerid = ",$a_params["userid"]);
        
      }else if(isset($a_params["unread"]) && $a_params["unread"] == true) {

        $this->db->where('message_chathistory1.unread >=', "1");

      }else if(isset($a_params["status"]) && $a_params["status"]!="" && $a_params["status"]!="all") {

        if($a_params["status"] == 'inbox' ){
          $this->db->where("((aicrm_contactdetails.chat_status='อินบ็อกซ์' OR aicrm_leaddetails.chat_status = 'อินบ็อกซ์') OR (aicrm_contactdetails.chat_status='ดำเนินการ' OR aicrm_leaddetails.chat_status = 'ดำเนินการ')) ", NULL, FALSE);
          $this->db->where("(aicrm_contactdetails.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);
        }else if($a_params["status"] == 'process' ){
          $this->db->where("(aicrm_contactdetails.chat_status='ดำเนินการ' OR aicrm_leaddetails.chat_status = 'ดำเนินการ')", NULL, FALSE);
          $this->db->where("(aicrm_contactdetails.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);
        }elseif($a_params["status"] == 'complete' ){
          $this->db->where("(aicrm_contactdetails.chat_status='เสร็จสิ้น' OR aicrm_leaddetails.chat_status = 'เสร็จสิ้น')", NULL, FALSE);
          $this->db->where("(aicrm_contactdetails.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);
        }
      }
      /*if(isset($a_params["spam"]) && $a_params["spam"] == true) {

        $this->db->where("(aicrm_account.spam='1' OR aicrm_leaddetails.spam = '1')", NULL, FALSE);

      }else if(isset($a_params["unread"]) && $a_params["unread"] == true) {

        $this->db->where('message_chathistory1.unread >=', "1");
        //$this->db->where("(aicrm_account.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);

      }else if(isset($a_params["status"]) && $a_params["status"]!="" && $a_params["status"]!="all") {

        if($a_params["status"] == 'inbox' ){
          
          $this->db->where("(aicrm_account.chat_status='อินบ็อกซ์' OR aicrm_leaddetails.chat_status = 'อินบ็อกซ์')", NULL, FALSE);
          //$this->db->where("(aicrm_account.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);

        }else if($a_params["status"] == 'process' ){

          $this->db->where("(aicrm_account.chat_status='ดำเนินการ' OR aicrm_leaddetails.chat_status = 'ดำเนินการ')", NULL, FALSE);
          //$this->db->where("(aicrm_account.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);

        }elseif($a_params["status"] == 'complete' ){
        
          $this->db->where("(aicrm_account.chat_status='เสร็จสิ้น' OR aicrm_leaddetails.chat_status = 'เสร็จสิ้น')", NULL, FALSE);
          //$this->db->where("(aicrm_account.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);

        }
      }*/

      $this->db->group_by('message_customer.customerid');

      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      
      /*if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }*/
      $query = $this->db->get('message_customer');

      /*echo $this->db->last_query();
      exit;*/

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = count($query->result_array()) ;
        //echo $a_result; exit;
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_result;
          //$a_return["result"] = $a_result[0];
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

  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$a_params=array())
  {
  	try {
      
      $a_condition["message_customer.deleted"] = "0";
      /*message_customer.channelid as socialid,aicrm_social_config.name as socialname,aicrm_social_config.image_profile as social_image,*/
      /*$this->db->select('message_customer.channelid as channelid,aicrm_social_config.name as socialname,aicrm_social_config.image_profile as social_image, message_customer.customerid, message_customer.pictureurl, message_customer.channel,message_customer.socialname, case when message_customer.module = "Accounts" then aicrm_account.social_name else aicrm_leaddetails.social_name end as newsocialname, message_customer.parentid, message_customer.module ,message_customer.contactid, IFNULL(message_chathistory1.unread, "0") as unread, CASE WHEN message_chathistory2.total_message = "1" THEN "เพิ่มเพื่อน" WHEN message_chathistory2.message_type = "image" THEN "Image"
        WHEN message_chathistory2.message_type = "sticker" THEN "Sticker"
        WHEN message_chathistory2.message_type = "file" THEN "Attach File"
        WHEN message_chathistory2.message_type = "video" THEN "Video"
        WHEN message_chathistory2.message_type = "audio" THEN "Audio"
        WHEN message_chathistory2.message_type = "location" THEN "Location" ELSE message_chathistory2.message END AS lastmessage, CASE WHEN message_chathistory2.messageaction = "agent" then true ELSE false end AS isagent, message_chathistory2.messagetime as lastupdate, message_customer.createdate as firstcontact, message_customer.customerno as socialid, case when message_customer.module = "Accounts" then if(aicrm_account.interest="--None--" ,"",aicrm_account.interest) else if(aicrm_leaddetails.interest="--None--" ,"",aicrm_leaddetails.interest) end as interest, case when message_customer.module = "Accounts" then aicrm_account.spam else aicrm_leaddetails.spam end as spam, case when message_customer.module = "Accounts" then aicrm_account.chat_status else aicrm_leaddetails.chat_status end as chat_status , message_chathistory2.total_message , sla.inbox AS doneat ,sla.finish as finishat,message_customer.auto_reply , CASE WHEN message_customer.channel = "facebook" and modifiedtime < DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN "1" ELSE "0" END AS disable ,case when ifnull(aicrm_users.id,"") = "" THEN "" else concat(aicrm_users.first_name," ",aicrm_users.last_name)  END AS assigto',false);*/

      $this->db->select('message_customer.channelid as channelid,aicrm_social_config.name as channelname ,aicrm_social_config.image_profile as social_image, message_customer.customerid, message_customer.pictureurl, message_customer.channel,message_customer.socialname, case when message_customer.module = "Accounts" then aicrm_contactdetails.social_name else aicrm_leaddetails.social_name end as newsocialname, message_customer.parentid, message_customer.module ,message_customer.contactid, IFNULL(message_chathistory1.unread, "0") as unread, CASE WHEN message_chathistory2.total_message = "1" THEN "เพิ่มเพื่อน" WHEN message_chathistory2.message_type = "image" THEN "Image"
        WHEN message_chathistory2.message_type = "sticker" THEN "Sticker"
        WHEN message_chathistory2.message_type = "file" THEN "Attach File"
        WHEN message_chathistory2.message_type = "video" THEN "Video"
        WHEN message_chathistory2.message_type = "audio" THEN "Audio"
        WHEN message_chathistory2.message_type = "location" THEN "Location" ELSE message_chathistory2.message END AS lastmessage, CASE WHEN message_chathistory2.messageaction = "agent" then true ELSE false end AS isagent, message_chathistory2.messagetime as lastupdate, message_customer.createdate as firstcontact, message_customer.customerno as socialid, case when message_customer.module = "Accounts" then aicrm_contactdetails.spam else aicrm_leaddetails.spam end as spam, case when message_customer.module = "Accounts" then aicrm_contactdetails.chat_status else aicrm_leaddetails.chat_status end as chat_status , message_chathistory2.total_message , sla.inbox AS doneat ,sla.finish as finishat,message_customer.auto_reply , CASE WHEN message_customer.channel = "facebook" and modifiedtime < DATE_SUB(NOW(), INTERVAL 168 HOUR) THEN "1" ELSE "0" END AS disable ,case when ifnull(aicrm_users.id,"") = "" THEN "" else concat(aicrm_users.first_name," ",aicrm_users.last_name)  END AS assignto ,message_customer.smownerid',false);

      $this->db->join('( select customerid,count(flag_read) as unread from message_chathistory where flag_read = 0 group by customerid ) message_chathistory1', 'message_chathistory1.customerid = message_customer.customerid',"left");
      
      $this->db->join('( SELECT c1.customerid , c1.message , c1.messagetime ,c2.total_message, c1.message_type,c1.messageaction FROM message_chathistory c1 INNER JOIN ( SELECT customerid, MAX(chatid) AS max_chatid, COUNT(*) AS total_message FROM message_chathistory GROUP BY customerid ) c2 ON c1.customerid = c2.customerid and c2.max_chatid = c1.chatid group by customerid order by messagetime DESC ) message_chathistory2', 'message_chathistory2.customerid = message_customer.customerid',"left");


      $this->db->join('(SELECT message ,customerid FROM message_chathistory ) message_chathistory3', 'message_chathistory3.customerid = message_customer.customerid',"left");
    
      
      $this->db->join('(SELECT p.customerid, max(Case when p.chat_status = "อินบ็อกซ์" then p.updatedate else "" end) as inbox, max(Case when p.chat_status = "เสร็จสิ้น" then p.updatedate else "" end) as finish FROM aicrm_sla AS p INNER JOIN (SELECT customerid, MAX(sla_group) AS sla_group FROM aicrm_sla GROUP BY customerid order by id desc ) AS es ON p.sla_group = es.sla_group and p.customerid = es.customerid AND p.sla_group = es.sla_group  group by p.customerid ) sla', 'sla.customerid = message_customer.customerid',"left");

      //$this->db->join('aicrm_account', 'aicrm_account.accountid = message_customer.parentid and message_customer.module = "Accounts" ',"left");
      $this->db->join('(SELECT aicrm_contactdetails.* FROM aicrm_contactdetails inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid where aicrm_crmentity.deleted = 0 ) as aicrm_contactdetails','aicrm_contactdetails.contactid = message_customer.contactid and message_customer.module = "Accounts" ',"left");
      //$this->ci->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = message_customer.contactid and message_customer.module = "Accounts" ',"left");
      $this->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = message_customer.parentid and message_customer.module = "Leads" ',"left");

      $this->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = message_customer.smownerid',"left");
      
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }

      if(isset($a_params["spam"]) && $a_params["spam"] == true) {

        $this->db->where("(aicrm_contactdetails.spam='1' OR aicrm_leaddetails.spam = '1')", NULL, FALSE);

      }else if(isset($a_params["myjob"]) && $a_params["myjob"] == true) {

        $this->db->where("message_customer.smownerid = ",$a_params["userid"]);

      }else if(isset($a_params["unread"]) && $a_params["unread"] == true) {

        $this->db->where('message_chathistory1.unread >=', "1");

      }else if(isset($a_params["status"]) && $a_params["status"]!="" && $a_params["status"]!="all") {

        if($a_params["status"] == 'inbox' ){
          $this->db->where("((aicrm_contactdetails.chat_status='อินบ็อกซ์' OR aicrm_leaddetails.chat_status = 'อินบ็อกซ์') OR (aicrm_contactdetails.chat_status='ดำเนินการ' OR aicrm_leaddetails.chat_status = 'ดำเนินการ')) ", NULL, FALSE);
          $this->db->where("(aicrm_contactdetails.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);
        }else if($a_params["status"] == 'process' ){
          $this->db->where("(aicrm_contactdetails.chat_status='ดำเนินการ' OR aicrm_leaddetails.chat_status = 'ดำเนินการ')", NULL, FALSE);
          $this->db->where("(aicrm_contactdetails.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);
        }elseif($a_params["status"] == 'complete' ){
          $this->db->where("(aicrm_contactdetails.chat_status='เสร็จสิ้น' OR aicrm_leaddetails.chat_status = 'เสร็จสิ้น')", NULL, FALSE);
          $this->db->where("(aicrm_contactdetails.spam='0' OR aicrm_leaddetails.spam = '0')", NULL, FALSE);
        }
      }

      $this->db->group_by('message_customer.customerid'); 
      
      $this->db->order_by('message_chathistory2.messagetime', 'DESC');

      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('message_customer');
   	  	
		  /*echo $this->db->last_query();
	    exit;*/

  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
  			$a_result  = $query->result_array() ;

  			$a_total = $this->get_total($a_condition,$a_order,$a_limit,$a_params) ;
  			$a_data["offset"] = $a_limit["offset"];
  			$a_data["limit"] = $a_limit["limit"];
  			//$a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
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
  }

  function get_list_detail($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    try {

      $a_condition["message_chathistory.deleted"] = "0";
  
      //$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
      //$domainName = $_SERVER['HTTP_HOST'].'/';
      $domainName = 'localhost:8090';
      //return $protocol.$domainName;

      $this->db->select('message_chathistory.chatno, message_chathistory.customerid,message_chathistory.customerno as socialid,message_chathistory.messageaction,message_chathistory.chatactionname, message_chathistory.message, message_chathistory.messagetime, message_chathistory.isagent,message_chathistory.channel, message_chathistory.flag_read, message_chathistory.deleted, ifnull(concat(aicrm_users.first_name," " ,aicrm_users.last_name),"") as agent_name , 
        case when aicrm_social_config.image_profile is null then "'.$protocol.$domainName.'/storage/imagesocial/tmp.png" 
        else aicrm_social_config.image_profile end as socail_profile ,message_chathistory.message_type 
        ' , false);

      //ifnull(aicrm_social_config.image_profile,"") as socail_profile
      //echo $_SERVER['SERVER_NAME']; exit;
      
      $this->db->join('message_customer', 'message_customer.customerid = message_chathistory.customerid',"inner");
      $this->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = message_chathistory.userid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }

      //$this->db->group_by('message_customer.customerid'); 

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
      $query = $this->db->get('message_chathistory');
      
      /*echo $this->db->last_query();
      exit;*/

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_detail($a_condition,$a_limit) ;
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
  }

  function get_total_detail($a_condition=array(),$a_limit=array())
  {
    try {
      
      /*$this->db->select("count(DISTINCT message_chathistory.chatid) as total");*/
      $this->db->select(" message_chathistory.chatid ");
      $this->db->join('message_customer', 'message_customer.customerid = message_chathistory.customerid',"inner");
      $this->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"inner");

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
      $query = $this->db->get('message_chathistory');

      /*echo $this->db->last_query();
      exit;*/

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = count($query->result_array()) ;

        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          //$a_return["result"] = $a_result[0];
          $a_return["result"] = $a_result;
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

  function get_list_search($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    try {
      $a_condition["message_chathistory.deleted"] = "0";
      $this->db->select('customerid,chatno, customerno as socialid ,messageaction,chatactionname,message,messagetime,isagent,channel,flag_read,deleted',false);
      //$this->db->select('message_customer.customerid, message_customer.socialid, message_customer.pictureurl, message_customer.channel, message_customer.socialname, message_customer.parentid, message_customer.module, IFNULL(message_chathistory.message, "0") as message',false);
      //$this->db->join('( select customerid,count(flag_read) as message from message_chathistory where flag_read = 0 group by customerid )message_chathistory', 'message_chathistory.customerid = message_customer.customerid',"left");
       
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }

      //$this->db->group_by('message_customer.customerid'); 

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
      $query = $this->db->get('message_chathistory');
      
      /*echo $this->db->last_query();
      exit;*/

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_detail($a_condition,$a_limit) ;
        //alert($a_total);exit;
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

  function get_list_assignto($a_condition=array(),$a_order=array(),$a_limit=array(),$a_params=array())
  {
    try {

      $profile = array();
      $profile = $this->get_profile_agent($a_params['userid']);

      if(empty($profile)){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
        return $a_return;
      }
      $a_condition["aicrm_role.parentrole LIKE "] = $profile[0]['parentrole']."%";
      $a_condition["aicrm_users.deleted"] = "0";

      $this->db->select('aicrm_users.id ,aicrm_users.first_name, aicrm_users.last_name, aicrm_role.rolename, aicrm_role.parentrole,
        concat(aicrm_users.first_name," ",aicrm_users.last_name) as fullname,
        CASE WHEN ifnull(ai_check_user_login.status,"") = "" THEN false
             WHEN ai_check_user_login.status = 1 THEN false 
             WHEN ai_check_user_login.status = 0 and ai_check_user_login.end_time < now() THEN false
             when ai_check_user_login.status = 0 and ai_check_user_login.end_time > now() then true
             ELSE false 
            END AS "online"',false);
      //$this->db->select('CASE WHEN ifnull(aicrm_attachments.attachmentsid,"") = "" THEN "storage/imagesocial/tmp_user.png" ELSE concat(aicrm_attachments.path,aicrm_attachments.name) end as img_profile',false);
      $this->db->select('CASE WHEN ifnull(aicrm_attachments.attachmentsid,"") = "" THEN "https://'.$_SERVER["HTTP_HOST"].'/storage/imagesocial/tmp_user.png" ELSE concat("https://","'.$_SERVER["HTTP_HOST"].'/",aicrm_attachments.path,aicrm_attachments.attachmentsid,"_",aicrm_attachments.name) end as img_profile',false);

      $this->db->join('aicrm_user2role', 'aicrm_user2role.roleid = aicrm_role.roleid',"inner");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_user2role.userid',"inner");
      $this->db->join('(SELECT * FROM ai_check_user_login where id in ( SELECT MAX(id) FROM ai_check_user_login where ai_check_user_login.sysytem_name = "CRM" GROUP BY user_id )  ORDER BY ai_check_user_login.id DESC ) as ai_check_user_login ', 'ai_check_user_login.user_id = aicrm_users.id',"inner");
      
      $this->db->join('aicrm_salesmanattachmentsrel', 'aicrm_salesmanattachmentsrel.smid = aicrm_users.id',"left");
      $this->db->join('aicrm_attachments', 'aicrm_attachments.attachmentsid = aicrm_salesmanattachmentsrel.attachmentsid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $this->db->order_by('aicrm_role.depth, online', 'DESC');
      $this->db->order_by('fullname','ASC');

      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_role');
      //H939 => Supervisor
      //H940 => Agent
      /*echo $this->db->last_query();
      exit;*/

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = count($a_result);
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = count($a_result);
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

  function get_assignto($a_condition=array(),$a_order=array(),$a_limit=array(),$a_params=array()){
    try {

      $profile = array();
      $profile = $this->get_profile_agent($a_params['userid']);

      if(empty($profile)){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
        return $a_return;
      }

      $a_condition["aicrm_users.deleted"] = "0";
      $a_condition["message_customer.customerid"] = $a_params['customerid'];

      $this->db->select('aicrm_users.id ,aicrm_users.first_name, aicrm_users.last_name, aicrm_role.rolename, aicrm_role.parentrole,
        concat(aicrm_users.first_name," ",aicrm_users.last_name) as fullname,
        CASE WHEN ifnull(ai_check_user_login.status,"") = "" THEN false
             WHEN ai_check_user_login.status = 1 THEN false 
             WHEN ai_check_user_login.status = 0 and ai_check_user_login.end_time < now() THEN false
             when ai_check_user_login.status = 0 and ai_check_user_login.end_time > now() then true
             ELSE false 
            END AS "online"',false);

      $this->db->select('CASE WHEN ifnull(aicrm_attachments.attachmentsid,"") = "" THEN "https://'.$_SERVER["HTTP_HOST"].'/storage/imagesocial/tmp_user.png" ELSE concat("https://","'.$_SERVER["HTTP_HOST"].'/",aicrm_attachments.path,aicrm_attachments.attachmentsid,"_",aicrm_attachments.name) end as img_profile',false);
      $this->db->join('aicrm_user2role', 'aicrm_user2role.roleid = aicrm_role.roleid',"inner");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_user2role.userid',"inner");
      $this->db->join('(SELECT * FROM ai_check_user_login where id in ( SELECT MAX(id) FROM ai_check_user_login where ai_check_user_login.sysytem_name = "CRM" GROUP BY user_id )  ORDER BY ai_check_user_login.id DESC ) as ai_check_user_login ', 'ai_check_user_login.user_id = aicrm_users.id',"inner");
      $this->db->join('message_customer', 'message_customer.smownerid = aicrm_users.id',"inner");
      $this->db->join('aicrm_salesmanattachmentsrel', 'aicrm_salesmanattachmentsrel.smid = aicrm_users.id',"left");
      $this->db->join('aicrm_attachments', 'aicrm_attachments.attachmentsid = aicrm_salesmanattachmentsrel.attachmentsid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $this->db->order_by('aicrm_role.depth, online', 'DESC');
      $this->db->order_by('fullname','ASC');

      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_role');
     /* echo $this->db->last_query();
      exit;*/
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = count($a_result);
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = count($a_result);
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
  
  function get_profile_agent($userid=''){
    $a_condition["aicrm_user2role.userid"] = $userid;
    $this->db->select('aicrm_role.*, aicrm_user2role.* ',false);
    $this->db->join('aicrm_user2role', 'aicrm_user2role.roleid = aicrm_role.roleid',"inner");
    if (!empty($a_condition)) {
      $this->db->where($a_condition);
    }
    $query = $this->db->get('aicrm_role');
    $a_result = $query->result_array();
    return $a_result;
  }

  
}