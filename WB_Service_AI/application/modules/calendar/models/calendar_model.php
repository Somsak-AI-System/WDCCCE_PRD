<?php
class calendar_model extends CI_Model
{
  var $ci;

  /**
   */
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	$this->load->library("common");
    $this->_limit = "10";
  }

  function get_notification($a_condition=array())
  {
  	try {
  		$a_condition["aicrm_crmentity.deleted"] = "0";
  		if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}
  		//$this->db->select("*" );
  		$this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid',"inner");
  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid',"inner");
  		$this->db->join('aicrm_crmentity_notification', 'aicrm_crmentity_notification.crmid = aicrm_activity.activityid',"inner");
  		$query = $this->db->get('aicrm_activity');

  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
  			$a_result  = $query->result_array() ;
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

   function get_total($a_condition=array() ,$a_params=array())
  {
  	try {
  		/*if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}else{*/
  			//$a_condition["aicrm_crmentity.setype"] = "Calendar";
  			$a_condition["aicrm_crmentity.deleted"] = "0";
  		/*}*/

	   if(isset($a_params["userid"]) && $a_params["userid"]!="") {
			$userid = array();
			$userid = $this->common->get_user2role($a_params["userid"]);
			$this->db->where("aicrm_crmentity.smownerid in (".$userid.")");
		}

		if ((isset($a_params["date_start"]) && $a_params["date_start"]!="")  && (isset($a_params["due_date"]) && $a_params["due_date"]!="")) {
			$date_start = $a_params['date_start'];
			$due_date = $a_params['due_date'];
			$this->db->where('aicrm_activity.date_start >=', $date_start);
			$this->db->where('aicrm_activity.due_date <=', $due_date);
		}else{
			date_default_timezone_set("Asia/Bangkok");
			$last_date =  date("t",strtotime(date('Y-m')."-01"));

			$date_start =date('Y-m')."-01";
			$due_date = date('Y-m')."-".$last_date;
			$this->db->where('aicrm_activity.date_start >=', $date_start);
			$this->db->where('aicrm_activity.due_date <=', $due_date);
		}

  	  $this->db->select("count(DISTINCT aicrm_activity.activityid) as total");
      $this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid');
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','inner');
      $this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = aicrm_activitycf.contactid','left');
      $this->db->join('aicrm_contactscf', 'aicrm_contactscf.contactid = aicrm_contactdetails.contactid','left');

      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_activitycf.accountid','left');
      $this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_accountscf.accountid','left');

	  if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}

	  $query = $this->db->get('aicrm_activity');

	  //echo $this->db->last_query(); exit;

   		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{

			$a_result  = $query->result_array() ;
			//echo 666; exit;
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


  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$optimize=array(),$a_params=array())
  {
		$module = $a_params['module'];
		$userid = $a_params['userid'];
		$crmid = $a_params['crmid'];

		if($module=="Calendar" || $module=="Schedule" || $module=="Sales Visit" || $module=="sale visit"){
			$module = "Events";
		}

  	try {
			// $a_condition["aicrm_crmentity.setype"] = "Calendar";
			// alert($optimize);exit;
			$a_condition["aicrm_crmentity.deleted"] = "0";

	    if($optimize == '1'){
			 $this->db->select("aicrm_activity.activityid,aicrm_activity.date_start,aicrm_activity.due_date,aicrm_activity.time_start, aicrm_activity.time_end,aicrm_account.accountname ,aicrm_activity.activitytype, aicrm_activity.eventstatus,,aicrm_users.user_name as user_assign, aicrm_activitycf.location" );
		}else{
			// $this->db->select("aicrm_activity.*, aicrm_activitycf.* , aicrm_account.accountid , aicrm_account.accountname , aicrm_contactdetails.contactid
			// , aicrm_contactdetails.contactid,aicrm_users.user_name as user_assign" );
			// $this->db->select("CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) AS contactname", FALSE);
			  $this->db->select("aicrm_activity.activityid " );
				}

  		$this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid','inner');
  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid' ,'inner');
  		$this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','inner');
        $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_activitycf.accountid','left');
        $this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid','left');
        $this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = aicrm_activitycf.contactid','left');
        $this->db->join('aicrm_contactscf', 'aicrm_contactscf.contactid = aicrm_contactdetails.contactid','left');

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
  		$query = $this->db->get('aicrm_activity');

  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
				$a_result  = $query->result_array() ;

				$val_change = null;
				$checkin_status = null;
				foreach($a_result as $key => $val){
				
					foreach($val as $k => $v){
					if($v==null){
						$v="";
						$val[$k] = $v;
						$val_change = $val[$k];
					}
					$val[$k] = $v;
				}
				$a_result[$key] = $val;

				}

  			$a_total = $this->get_total($a_condition,$a_params) ;
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


  function get_calendar($a_request=array())
  {

    $a_return["status"] = false;
    $a_return["error"] =  "No Data";
    $a_return["result"] = "";

    $crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
    $userid = isset($a_request['userid']) ? $a_request['userid'] : "";
    $module = isset($a_request['module']) ? $a_request['module'] : "";


    if(empty($crmid)){
       return $a_return;
    }
    
    try {

      $a_condition["aicrm_crmentity.crmid"] = $crmid;
      //$a_condition["aicrm_crmentity.setype"] = $module;
      // $a_condition["aicrm_crmentity.smownerid"] = $userid;
      $a_condition["aicrm_crmentity.deleted"] = "0";

      $this->db->select("aicrm_activity.*,aicrm_activitycf.*, case when ifnull(aicrm_account.accountid,'') != '' then aicrm_account.accountid when ifnull(aicrm_leaddetails.leadid,'') != '' then aicrm_leaddetails.leadid else '' end as accountid ,case when ifnull(aicrm_account.accountid,'') != '' then aicrm_account.accountname when ifnull(aicrm_leaddetails.leadid,'') != '' then aicrm_leaddetails.leadname else '' end as accountname  , aicrm_contactdetails.contactid , aicrm_contactdetails.contactname ,aicrm_crmentity.* " ,false);

      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid',"inner");
      $this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid',"inner");

      $this->db->join('aicrm_account','aicrm_account.accountid = aicrm_activity.parentid',"left");
      $this->db->join('aicrm_leaddetails','aicrm_leaddetails.leadid = aicrm_activity.parentid',"left");
      $this->db->join('aicrm_contactdetails','aicrm_contactdetails.contactid = aicrm_activity.contactid',"left");

      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");

      // $this->db->join('aicrm_users as create_by', 'create_by.id = aicrm_crmentity.smcreatorid');
      // $this->db->join('aicrm_users as modified_by', 'modified_by.id = aicrm_crmentity.modifiedby');

      

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $query = $this->db->get('aicrm_activity');

     //echo $this->db->last_query(); exit;

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        // alert($a_result); exit;
        // $a_data["data"] = $a_result;
        $a_data["data"] = $a_result;
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_data;
          //$a_data["data"] = $a_result;
          // $a_return["result"] = $a_data;
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
