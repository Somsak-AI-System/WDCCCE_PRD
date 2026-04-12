<?php
class Competitor_model extends CI_Model
{
  var $ci;
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }
 
  function get_competitor($a_request=array())
  {

    $a_return["status"] = false;
    $a_return["error"] =  "No Data";
    $a_return["result"] = "";

    $crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
    $userid = isset($a_request['userid']) ? $a_request['userid'] : "";
    $module = isset($a_request['module']) ? $a_request['module'] : "";
      // alert($a_request);exit;


    if(empty($crmid)){
       return $a_return;
    }
    
    try {

      $a_condition["aicrm_crmentity.crmid"] = $crmid;
      $a_condition["aicrm_crmentity.setype"] = $module;
      // $a_condition["aicrm_crmentity.smownerid"] = $userid;
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_competitor.competitorid',"inner");
       $this->db->join('aicrm_competitorcf', 'aicrm_competitorcf.competitorid = aicrm_competitor.competitorid',"inner");
      
        $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_competitor.account_id',"left");
        
      // $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      // $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $query = $this->db->get('aicrm_competitor');

      // echo $this->db->last_query(); exit;

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
        // alert($a_result); exit;
        // $a_data["data"] = $a_result;
        
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_result;
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
