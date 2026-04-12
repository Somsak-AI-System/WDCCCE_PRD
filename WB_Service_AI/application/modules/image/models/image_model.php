<?php
class Image_model extends CI_Model
{
  var $ci;

  /**
  */
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
    $this->_limit = "10";
  }

  function get_total($a_condition=array(),$module=null)
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }


      if($module == 'Job'){

        $this->db->select("count(DISTINCT aicrm_jobs.jobid) as total");
        $this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid');

        $query = $this->db->get('aicrm_jobs');

      }else if($module == 'Calendar'){
        $this->db->select("count(DISTINCT aicrm_activity.activityid) as total");
        $this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid');

        $query = $this->db->get('aicrm_activity');

      }else if($module == 'Questionnaire'){

         $a_condition= [];

        $this->db->select("count(DISTINCT aicrm_questionnaire.questionnaireid) as total");
        $this->db->join('aicrm_questionnairecf', 'aicrm_questionnairecf.questionnaireid = aicrm_questionnaire.questionnaireid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid');

        $query = $this->db->get('aicrm_questionnaire');
      
      }else if($module == 'HelpDesk'){

        $this->db->select("aicrm_troubletickets.*, aicrm_ticketcf.*" );
        $this->db->join('aicrm_ticketcf', 'aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_troubletickets.ticketid');

        $query = $this->db->get('aicrm_troubletickets');

      }



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

  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$module =NULL)
  {
    try {
      

      if($module == 'Calendar'){
        $a_condition["aicrm_crmentity.deleted"] = "0";
        $this->db->select("aicrm_activity.*, aicrm_activitycf.* " );
        $this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid');

      }else if($module == 'Job'){
        $a_condition["aicrm_crmentity.deleted"] = "0";
        $this->db->select("aicrm_jobs.*, aicrm_jobscf.*" );
        $this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid');

      }else if($module == 'Questionnaire'){

        $this->db->select("aicrm_questionnaire.*, aicrm_questionnairecf.*" );
        $this->db->join('aicrm_questionnairecf', 'aicrm_questionnairecf.questionnaireid = aicrm_questionnaire.questionnaireid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid');

      }else if($module == 'HelpDesk'){

        $a_condition["aicrm_crmentity.deleted"] = "0";
        $this->db->select("aicrm_troubletickets.*, aicrm_ticketcf.*" );
        $this->db->join('aicrm_ticketcf', 'aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_troubletickets.ticketid');

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

      if($module == 'Calendar'){
        $query = $this->db->get('aicrm_activity');
      }else if($module == 'Job'){
        $query = $this->db->get('aicrm_jobs');
      }else if($module == 'Questionnaire'){
        $query = $this->db->get('aicrm_questionnaire');
      }else if($module == 'HelpDesk'){
        $query = $this->db->get('aicrm_troubletickets');
      }

      //alert($this->db->last_query());exit;

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
        // alert($a_result);exit;

        $a_total = $this->get_total($a_condition,$module) ;
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
