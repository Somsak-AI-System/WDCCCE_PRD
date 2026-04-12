<?php
class Voucher_model extends CI_Model
{
  var $ci;
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }

  function get_total($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Voucher";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }
      
      $this->db->select("count(DISTINCT aicrm_voucher.voucherid) as total");
      $this->db->join('aicrm_vouchercf', 'aicrm_vouchercf.voucherid = aicrm_voucher.voucherid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_voucher.voucherid',"inner");
      
      $query = $this->db->get('aicrm_voucher');
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
  
  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$a_param=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Voucher";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_voucher.*,aicrm_vouchercf.*, case when (aicrm_users.user_name not like '') then concat(aicrm_users.first_name,' ',aicrm_users.last_name) else aicrm_groups.groupname end as user_name , aicrm_account.accountname , concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname ) as leadname ,aicrm_promotionvoucher.promotionvoucher_name , aicrm_crmentity.* ,aicrm_promotionvoucher.promotionvoucher_no ",false);

      $this->db->join('aicrm_vouchercf', 'aicrm_vouchercf.voucherid = aicrm_voucher.voucherid', "inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_voucher.voucherid',"inner");
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_voucher.accountid',"left");
      $this->db->join('aicrm_promotionvoucher', 'aicrm_promotionvoucher.promotionvoucherid = aicrm_voucher.promotionvoucherid',"left");
      $this->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = aicrm_voucher.leadid',"left");
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");
      
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
      $query = $this->db->get('aicrm_voucher');

      /*echo $this->db->last_query();
      exit;*/
      
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total($a_condition) ;
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