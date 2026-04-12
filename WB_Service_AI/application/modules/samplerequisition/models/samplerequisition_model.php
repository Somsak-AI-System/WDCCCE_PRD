<?php
class Samplerequisition_model extends CI_Model
{
  var $ci;
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }
 
  function get_samplerequisition($a_request=array())
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
      $a_condition["aicrm_crmentity.setype"] = $module;
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_samplerequisition.samplerequisitionid',"inner");
        $this->db->join('aicrm_samplerequisitioncf', 'aicrm_samplerequisitioncf.samplerequisitionid = aicrm_samplerequisition.samplerequisitionid',"inner");
        $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_samplerequisition.accountid',"left");
        $this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = aicrm_samplerequisition.contactid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $query = $this->db->get('aicrm_samplerequisition');

      // echo $this->db->last_query(); exit;

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
        
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
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


}
