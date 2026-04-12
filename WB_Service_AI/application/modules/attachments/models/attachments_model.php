<?php
class attachments_model extends CI_Model
{
  var $ci;
  public $_module;

  /**
   */
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
    $this->get_field();
  }

  function get_field($module="")
  {
  	$a_return = array();

    if($module!=""){
  		if($module== "KnowledgeBase"){
  			$this->_module = $module;
  			$this->_pkfield = "aicrm_knowledgebasecf.knowledgebaseid" ;
  			$this->_table = "aicrm_knowledgebasecf" ;
  			$this->_fieldid = "knowledgebaseid";
  		}
  		if($module== "Ads"){
  			$this->_module = $module;
  			$this->_pkfield = "aicrm_ads.adsid" ;
  			$this->_table = "aicrm_ads" ;
  			$this->_fieldid = "adsid";
  		}
		  if($module== "Calendar"){
  			$this->_module = $module;
  			$this->_pkfield = "aicrm_activity.activityid" ;
  			$this->_table = "aicrm_activity" ;
  			$this->_fieldid = "activityid";
  		}
      if($module== "Job"){
        $this->_module = $module;
        $this->_pkfield = "aicrm_jobs.jobid" ;
        $this->_table = "aicrm_jobs" ;
        $this->_fieldid = "jobid";
      }
      if($module== "Questionnaire"){
        $this->_module = $module;
        $this->_pkfield = "aicrm_questionnaire.questionnaireid" ;
        $this->_table = "aicrm_questionnaire" ;
        $this->_fieldid = "questionnaireid";
      }
      if($module== "HelpDesk"){
        $this->_module = $module;
        $this->_pkfield = "aicrm_troubletickets.ticketid" ;
        $this->_table = "aicrm_troubletickets" ;
        $this->_fieldid = "ticketid";
      }
      if($module == "Announcement"){
        $this->_module = $module;
        $this->_pkfield = "aicrm_announcement.announcementid" ;
        $this->_table = "aicrm_announcement" ;
        $this->_fieldid = "announcementid";
      }

  		$a_return["_field"] = $this->_fieldid ;


  	}
  	return $a_return;
  }
   function get_total($a_condition=array(),$a_conditionin=array())
  {
  	try {
  		if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}else{
        if($this->_module == 'HelpDesk'){
          $a_condition["aicrm_crmentity.setype"] = $this->_module." Image";
        }else{
          $a_condition["aicrm_crmentity.setype"] = $this->_module." Image";
        }
  			
  			$a_condition["aicrm_crmentity.deleted"] = "0";
  		}

  		if (!empty($a_conditionin)) {
  			$this->db->where_in($a_conditionin);
  		}

  		$this->db->select("count(DISTINCT aicrm_attachments.attachmentsid) as total");
  		$this->db->join('aicrm_seattachmentsrel', 'aicrm_seattachmentsrel.crmid = '.$this->_pkfield);
  		$this->db->join('aicrm_attachments', 'aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid');
  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_attachments.attachmentsid');

  		$query = $this->db->get($this->_table);
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


  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$a_conditionin=array())
  {
  	try {
  			//$a_condition["aicrm_crmentity.setype"] = $this->_module." Image";
  			if($this->_module == 'HelpDesk'){
          $a_condition["aicrm_crmentity.setype"] = $this->_module." Image";
        }else{
          $a_condition["aicrm_crmentity.setype"] = $this->_module." Image";
        }
        $a_condition["aicrm_crmentity.deleted"] = "0";

  		$this->db->select("aicrm_attachments.attachmentsid,
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype, ".$this->_pkfield." ");
  		$this->db->join('aicrm_seattachmentsrel', 'aicrm_seattachmentsrel.crmid = '.$this->_pkfield);
  		$this->db->join('aicrm_attachments', 'aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid');
  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_attachments.attachmentsid');

		if (!empty($a_conditionin)) {
  			//$this->db->where_in($a_conditionin);
			$this->db->where_in( $this->_pkfield , $a_conditionin[$this->_pkfield]);
			//$this->db->where_in('id', array('20','15','22','42','86'));
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

  		$query = $this->db->get($this->_table);

      //echo $this->db->last_query();
     // exit;

  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
  			$a_result  = $query->result_array() ;

  			$a_total = $this->get_total($a_condition,$a_conditionin) ;
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
