<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_socket
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

  function emit_socket($customerid = '',$displayName=''){
    if($customerid == '' ){
      return false;
    }

    $data = $this->get_list($customerid); 
    //alert($data['result']['data']); exit;
    $socialid = @$data['result']['data'][0]['channelid'];

    $msg = array(
      "newchat" => $data['result']['data'],
      //"newmessage" => $data_message['result']['data'],
      "displayName" =>$displayName
    );
    //alert($msg); exit;
    $port = $this->ci->config->item('socketIOPort');
    require_once '/SocketIO.php';
    //$client = new SocketIO('localhost',$port);
    $client = new SocketIO('ocgp.moai-crm.com',$port);
    $client->setQueryParams([
        'token' => 'edihsudshuz',
        'id' => '8782',
        'cid' => '344',
        'cmp' => 2339
    ]);
    
    $success = $client->emit('update_customer', ['agentid' => $displayName,'customerid' => $customerid ,'msg'=>$msg ,'socialid'=>$socialid ]);
    /*if(!$success)
    {
        var_dump($client->getErrors());
    }
    else{
        var_dump("Success");
    }
    exit;*/
    return true;
  }

  function emit_socket_updatecrm($crmid = '',$displayName='',$module=''){
    if($crmid == '' ){
      return false;
    }

    if($module == 'Accounts'){
      $sql = "select customerid from message_customer where contactid = '".$crmid."'; ";
    }else{
      $sql = "select customerid from message_customer where parentid = '".$crmid."'; ";
    }
    
    $query = $this->ci->db->query($sql);
    $a_result  = $query->result_array() ;
    $customerid = @$a_result[0]['customerid'];

    $data = $this->get_list($customerid); 
    $socialid = @$data['result']['data'][0]['channelid'];

    $msg = array(
      "newchat" => $data['result']['data'],
      //"newmessage" => $data_message['result']['data'],
      "displayName" =>$displayName
    );
    
    $port = $this->ci->config->item('socketIOPort');

    require_once '/SocketIO.php';
    $client = new SocketIO('ocgp.moai-crm.com',$port);
    //$client = new SocketIO('localhost',$port);
    $client->setQueryParams([
        'token' => 'edihsudshuz',
        'id' => '8783',
        'cid' => '344',
        'cmp' => 2339
    ]);
    
    $success = $client->emit('update_customer', ['agentid' => $displayName,'customerid' => $customerid ,'msg'=>$msg ,'socialid'=>$socialid ]);
    /*if(!$success)
    {
        var_dump($client->getErrors());
    }
    else{
        var_dump("Success");
    }*/
    return true;
  }

  public function get_list($customerid='')
  {
    try {
      $a_condition["message_customer.deleted"] = "0";
      $a_condition["message_customer.customerid"] = $customerid;
      
      /*$this->ci->db->select('message_customer.customerid, message_customer.pictureurl, message_customer.channel,message_customer.socialname, "" as newsocialname, message_customer.parentid, message_customer.module, IFNULL(message_chathistory1.unread, "0") as unread, CASE WHEN message_chathistory2.total_message = "1" THEN "เพิ่มเพื่อน" WHEN message_chathistory2.message_type = "image" THEN "Image"
        WHEN message_chathistory2.message_type = "sticker" THEN "Sticker"
        WHEN message_chathistory2.message_type = "file" THEN "Attach File"
        WHEN message_chathistory2.message_type = "video" THEN "Video"
        WHEN message_chathistory2.message_type = "audio" THEN "Audio"
        WHEN message_chathistory2.message_type = "location" THEN "Location" ELSE message_chathistory2.message END AS lastmessage, message_chathistory2.messagetime as lastupdate, message_customer.createdate as firstcontact, message_customer.customerno as socialid, "" as interest, case when message_customer.module = "Accounts" then aicrm_account.spam else aicrm_leaddetails.spam end as spam, case when message_customer.module = "Accounts" then aicrm_account.chat_status else aicrm_leaddetails.chat_status end as chat_status , message_chathistory2.total_message , sla.inbox AS doneat ,sla.finish as finishat,message_customer.auto_reply , CASE WHEN message_customer.channel = "facebook" and modifiedtime < DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN "1" ELSE "0" END AS disable ,message_customer.channelid',false);
      
      $this->ci->db->join('( select customerid,count(flag_read) as unread from message_chathistory where flag_read = 0 group by customerid ) message_chathistory1', 'message_chathistory1.customerid = message_customer.customerid',"left");
      
      $this->ci->db->join('( SELECT c1.customerid , c1.message , c1.messagetime ,c2.total_message, c1.message_type FROM message_chathistory c1 INNER JOIN ( SELECT customerid, MAX(chatid) AS max_chatid, COUNT(*) AS total_message FROM message_chathistory GROUP BY customerid ) c2 ON c1.customerid = c2.customerid and c2.max_chatid = c1.chatid group by customerid order by messagetime DESC ) message_chathistory2', 'message_chathistory2.customerid = message_customer.customerid',"left");
      
      $this->ci->db->join('(SELECT message ,customerid FROM message_chathistory ) message_chathistory3', 'message_chathistory3.customerid = message_customer.customerid',"left");      
      
      $this->ci->db->join('(SELECT p.customerid, max(Case when p.chat_status = "อินบ็อกซ์" then p.updatedate else "" end) as inbox, max(Case when p.chat_status = "เสร็จสิ้น" then p.updatedate else "" end) as finish FROM aicrm_sla AS p INNER JOIN (SELECT customerid, MAX(sla_group) AS sla_group FROM aicrm_sla GROUP BY customerid order by id desc ) AS es ON p.sla_group = es.sla_group and p.customerid = es.customerid AND p.sla_group = es.sla_group  group by p.customerid ) sla', 'sla.customerid = message_customer.customerid',"left");
      
      $this->ci->db->join('aicrm_account', 'aicrm_account.accountid = message_customer.parentid and message_customer.module = "Accounts" ',"left");
      $this->ci->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = message_customer.parentid and message_customer.module = "Leads" ',"left");
      $this->ci->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"left");*/

      $this->ci->db->select('message_customer.channelid as channelid,aicrm_social_config.name as channelname ,aicrm_social_config.image_profile as social_image, message_customer.customerid, message_customer.pictureurl, message_customer.channel,message_customer.socialname, case when message_customer.module = "Accounts" then aicrm_contactdetails.social_name else aicrm_leaddetails.social_name end as newsocialname, message_customer.parentid, message_customer.module ,message_customer.contactid, IFNULL(message_chathistory1.unread, "0") as unread, CASE WHEN message_chathistory2.total_message = "1" THEN "เพิ่มเพื่อน" WHEN message_chathistory2.message_type = "image" THEN "Image"
        WHEN message_chathistory2.message_type = "sticker" THEN "Sticker"
        WHEN message_chathistory2.message_type = "file" THEN "Attach File"
        WHEN message_chathistory2.message_type = "video" THEN "Video"
        WHEN message_chathistory2.message_type = "audio" THEN "Audio"
        WHEN message_chathistory2.message_type = "location" THEN "Location" ELSE message_chathistory2.message END AS lastmessage, CASE WHEN message_chathistory2.messageaction = "agent" then true ELSE false end AS isagent, message_chathistory2.messagetime as lastupdate, message_customer.createdate as firstcontact, message_customer.customerno as socialid, case when message_customer.module = "Accounts" then aicrm_contactdetails.spam else aicrm_leaddetails.spam end as spam, case when message_customer.module = "Accounts" then aicrm_contactdetails.chat_status else aicrm_leaddetails.chat_status end as chat_status , message_chathistory2.total_message , sla.inbox AS doneat ,sla.finish as finishat,message_customer.auto_reply , CASE WHEN message_customer.channel = "facebook" and modifiedtime < DATE_SUB(NOW(), INTERVAL 168 HOUR) THEN "1" ELSE "0" END AS disable ,case when ifnull(aicrm_users.id,"") = "" THEN "" else concat(aicrm_users.first_name," ",aicrm_users.last_name)  END AS assignto ,message_customer.smownerid',false);

      $this->ci->db->join('( select customerid,count(flag_read) as unread from message_chathistory where flag_read = 0 group by customerid ) message_chathistory1', 'message_chathistory1.customerid = message_customer.customerid',"left");
      
      $this->ci->db->join('( SELECT c1.customerid , c1.message , c1.messagetime ,c2.total_message, c1.message_type,c1.messageaction FROM message_chathistory c1 INNER JOIN ( SELECT customerid, MAX(chatid) AS max_chatid, COUNT(*) AS total_message FROM message_chathistory GROUP BY customerid ) c2 ON c1.customerid = c2.customerid and c2.max_chatid = c1.chatid group by customerid order by messagetime DESC ) message_chathistory2', 'message_chathistory2.customerid = message_customer.customerid',"left");


      $this->ci->db->join('(SELECT message ,customerid FROM message_chathistory ) message_chathistory3', 'message_chathistory3.customerid = message_customer.customerid',"left");
    
      
      $this->ci->db->join('(SELECT p.customerid, max(Case when p.chat_status = "อินบ็อกซ์" then p.updatedate else "" end) as inbox, max(Case when p.chat_status = "เสร็จสิ้น" then p.updatedate else "" end) as finish FROM aicrm_sla AS p INNER JOIN (SELECT customerid, MAX(sla_group) AS sla_group FROM aicrm_sla GROUP BY customerid order by id desc ) AS es ON p.sla_group = es.sla_group and p.customerid = es.customerid AND p.sla_group = es.sla_group  group by p.customerid ) sla', 'sla.customerid = message_customer.customerid',"left");

      $this->ci->db->join('aicrm_account', 'aicrm_account.accountid = message_customer.parentid and message_customer.module = "Accounts" ',"left");
      $this->ci->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = message_customer.contactid and message_customer.module = "Accounts" ',"left");
      $this->ci->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = message_customer.parentid and message_customer.module = "Leads" ',"left");

      $this->ci->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"left");
      $this->ci->db->join('aicrm_users', 'aicrm_users.id = message_customer.smownerid',"left");
      
      if (!empty($a_condition)) {
        $this->ci->db->where($a_condition);
      }
      
      $this->ci->db->group_by('message_customer.customerid'); 
      $query = $this->ci->db->get('message_customer');
      
      /*echo $this->ci->db->last_query();
      exit;*/
      
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->ci->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_data["offset"] = @$a_limit["offset"];
        $a_data["limit"] = @$a_limit["limit"];
        
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
  }

  function emit_socket_detail($customerid = '',$displayName=''){

    if($customerid == '' ){
      return false;
    }
    
    $data = $this->get_list($customerid); //alert($data['result']['data'][0]['channelid']); exit;
    $data_message=$this->get_list_detail($customerid);//alert($data_message['result']['data']); exit;

    $socialid = @$data['result']['data'][0]['channelid'];
    
    $msg = array(
      "newchat" => $data['result']['data'],
      "newmessage" => $data_message['result']['data'],
      "displayName" =>$displayName
    );


    //alert($msg); exit;
    $port = $this->ci->config->item('socketIOPort');

    require_once '/SocketIO.php';
    $client = new SocketIO('ocgp.moai-crm.com',$port);
    //$client = new SocketIO('localhost',$port);
    $client->setQueryParams([
        'token' => 'edihsudshuz',
        'id' => '8780',
        'cid' => '344',
        'cmp' => 2339
    ]);
    
    $success = $client->emit('joinRoom_user', ['agentid' => $displayName,'customerid' => $customerid ,'msg'=>$msg ,'socialid'=> $socialid]);
    /*if(!$success)
    {
        var_dump($client->getErrors());
    }
    else{
        var_dump("Success");
    }*/
    return true;
  }

  public function get_list_detail($customerid='')
  {
    try {

      $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
      $domainName = $_SERVER['HTTP_HOST'].'/';

      $a_condition["message_chathistory.deleted"] = "0";
      $a_condition["message_chathistory.customerid"] = $customerid;
      //$this->db->select('chatno,customerid,customerno as socialid ,messageaction,chatactionname,message,messagetime,isagent,channel,flag_read,deleted',false);

      $this->ci->db->select('message_chathistory.chatno, message_chathistory.customerid,message_chathistory.customerno as socialid,message_chathistory.messageaction,message_chathistory.chatactionname, message_chathistory.message, message_chathistory.messagetime, message_chathistory.isagent,message_chathistory.channel, message_chathistory.flag_read, message_chathistory.deleted, ifnull(concat(aicrm_users.first_name," " ,aicrm_users.last_name),"") as agent_name , 
        case when aicrm_social_config.image_profile is null then "'.$protocol.$domainName.'/storage/imagesocial/tmp.png" 
        else aicrm_social_config.image_profile end as socail_profile ,message_chathistory.message_type 
        ' , false);      
      $this->ci->db->join('message_customer', 'message_customer.customerid = message_chathistory.customerid',"inner");
      $this->ci->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"left");
      $this->ci->db->join('aicrm_users', 'aicrm_users.id = message_chathistory.userid',"left");
             
      if (!empty($a_condition)) {
        $this->ci->db->where($a_condition);
      }

      $this->ci->db->limit(1);

      $this->ci->db->order_by('message_chathistory.messagetime', 'DESC');

      $query = $this->ci->db->get('message_chathistory');
      
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->ci->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
        //$a_total = $this->get_total_detail($a_condition,$a_limit) ;
        $a_total = 0;
        //alert($a_total);exit;
        $a_data["offset"] = @$a_limit["offset"];
        $a_data["limit"] = @$a_limit["limit"];

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
  }
  
  
  
}
