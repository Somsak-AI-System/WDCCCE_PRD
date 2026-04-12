<?php
class Quotes_model extends CI_Model
{
  var $ci;
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }
 
  function get_quotes($a_request=array())
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
      
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_quotes.quoteid',"inner");
        $this->db->join('aicrm_quotesbillads', 'aicrm_quotesbillads.quotebilladdressid = aicrm_quotes.quoteid',"inner");
        $this->db->join('aicrm_quotesshipads', 'aicrm_quotesshipads.quoteshipaddressid = aicrm_quotes.quoteid',"inner");
        $this->db->join('aicrm_quotescf', 'aicrm_quotescf.quoteid = aicrm_quotes.quoteid',"inner");
      
        $this->db->join('aicrm_currency_info', 'aicrm_currency_info.id = aicrm_quotes.currency_id',"left");
        $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_quotes.accountid',"left");
        $this->db->join('aicrm_potential', 'aicrm_potential.potentialid = aicrm_quotes.potentialid',"left");
        $this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = aicrm_quotes.contactid',"left");
        
      // $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      // $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $query = $this->db->get('aicrm_quotes');

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
