<?php
class indexinsert_model extends CI_Model
{
  var $ci;


  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	$this->load->library("common");
    $this->_limit = "10";
  }

  function get_notification($a_condition=array(),$module="")
  {

  	try {
  		$a_condition["aicrm_crmentity.deleted"] = "0";


  		if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}
      if($module=="Events" || $module=="Calendar" || $module=="Sales Visit"){
  		$this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid',"inner");
  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid',"inner");
  		$this->db->join('aicrm_crmentity_notification', 'aicrm_crmentity_notification.crmid = aicrm_activity.activityid',"left");
  		$query = $this->db->get('aicrm_activity');
    }elseif($module=="Job") {
      $this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid',"inner");
      $this->db->join('aicrm_crmentity_notification', 'aicrm_crmentity_notification.crmid = aicrm_jobs.jobid',"left");
      $query = $this->db->get('aicrm_jobs');
    }

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




}
