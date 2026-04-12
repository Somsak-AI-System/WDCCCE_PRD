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

   function get_total($a_condition=array(),$a_limit=array())
  {
  	try {
  		
  		$this->db->select("count(DISTINCT message_customer.customerid) as total");
  		
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
      $query = $this->db->get('message_customer');

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


  function get_list($a_condition=array(),$a_order=array(),$a_limit=array())
  {
  	try {

      /*$this->db->select('message_customer.customerid, message_customer.socialid, message_customer.pictureurl, message_customer.channel, message_customer.socialname, message_customer.parentid, message_customer.module, IFNULL(message_chathistory.unread, "0") as unread',false);

      $this->db->join('( select customerid,count(flag_read) as unread from message_chathistory where flag_read = 0 group by customerid )message_chathistory', 'message_chathistory.customerid = message_customer.customerid',"left");*/
      /*message_customer.customerid, message_customer.pictureurl, message_customer.channel,
        message_customer.socialname, 
        case when message_customer.module = 'Accounts' then aicrm_account.newsocialname
        else aicrm_leaddetails.newsocialname
        end as newsocialname,
        message_customer.parentid, message_customer.module, IFNULL(message_chathistory1.unread, "0")
        as unread,
        message_chathistory2.message as lastmessage,
        message_chathistory2.messagetime as lastupdate,
        message_customer.createdate as firstcontact,
        case when message_customer.module = 'Accounts' then aicrm_account.socialid
        else aicrm_leaddetails.socialid
        end as socialid,
        case when message_customer.module = 'Accounts' then if(aicrm_account.interest='--None--' ,'',aicrm_account.interest)
        else if(aicrm_leaddetails.interest='--None--' ,'',aicrm_leaddetails.interest)
        end as interest,
        case when message_customer.module = 'Accounts' then aicrm_account.spam
        else aicrm_leaddetails.spam
        end as spam,
        case when message_customer.module = 'Accounts' then aicrm_account.chat_status
        else aicrm_leaddetails.chat_status
        end as chat_status*/
      $this->db->select('message_customer.customerid, message_customer.pictureurl, message_customer.channel,message_customer.socialname, case when message_customer.module = "Accounts" then aicrm_account.newsocialname else aicrm_leaddetails.newsocialname end as newsocialname, message_customer.parentid, message_customer.module, IFNULL(message_chathistory1.unread, "0") as unread, message_chathistory2.message as lastmessage, message_chathistory2.messagetime as lastupdate, message_customer.createdate as firstcontact, case when message_customer.module = "Accounts" then aicrm_account.socialid else aicrm_leaddetails.socialid end as socialid, case when message_customer.module = "Accounts" then if(aicrm_account.interest="--None--" ,"",aicrm_account.interest) else if(aicrm_leaddetails.interest="--None--" ,"",aicrm_leaddetails.interest) end as interest, case when message_customer.module = "Accounts" then aicrm_account.spam else aicrm_leaddetails.spam end as spam, case when message_customer.module = "Accounts" then aicrm_account.chat_status else aicrm_leaddetails.chat_status end as chat_status ',false);

      $this->db->join('( select customerid,count(flag_read) as unread from message_chathistory where flag_read = 0 group by customerid ) message_chathistory1', 'message_chathistory1.customerid = message_customer.customerid',"left");
      $this->db->join('( select customerid,message,messagetime from message_chathistory group by customerid order by messagetime DESC ) message_chathistory2', 'message_chathistory2.customerid = message_customer.customerid',"left");

      $this->db->join('aicrm_account', 'aicrm_account.accountid = message_customer.parentid and message_customer.module = "Accounts" ',"left");
      $this->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = message_customer.parentid and message_customer.module = "Leads" ',"left");
      //LEFT JOIN aicrm_account on aicrm_account.accountid = message_customer.parentid and message_customer.module = 'Accounts'
      //LEFT JOIN aicrm_leaddetails on aicrm_leaddetails.leadid = message_customer.parentid and message_customer.module = 'Leads'


       

   		if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}

      $this->db->group_by('message_customer.customerid'); 

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
  		$query = $this->db->get('message_customer');
		
		  /*echo $this->db->last_query();
	    exit;*/

  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
  			$a_result  = $query->result_array() ;

  			$a_total = $this->get_total($a_condition,$a_limit) ;
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


  function get_list_detail($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    try {

      
      $a_condition["message_chathistory.deleted"] = "0";
      $this->db->select('chatid,customerid,chatno, customerno as socialid ,messageaction,chatactionname,message,messagetime,isagent,channel,flag_read,deleted',false);
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

  function get_total_detail($a_condition=array(),$a_limit=array())
  {
    try {
      
      $this->db->select("count(DISTINCT message_chathistory.chatid) as total");
      
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

}