<?php
class message_model extends CI_Model
{
  var $ci;

  /**
   */
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
  			$a_condition["aicrm_crmentity.setype"] = "Accounts";
  			$a_condition["aicrm_crmentity.deleted"] = "0";
  		}

  		$this->db->select("count(DISTINCT aicrm_account.accountid) as total");
  		$this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid',"inner");
  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid',"inner");
  		$this->db->join('aicrm_accountbillads', 'aicrm_accountbillads.accountaddressid = aicrm_account.accountid',"inner");   				
  		$this->db->join('aicrm_accountshipads', 'aicrm_accountshipads.accountaddressid = aicrm_account.accountid',"inner");
  		$query = $this->db->get('aicrm_account');
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
      //$a_condition["aicrm_crmentity.setype"] = "Branch";
      //$a_condition["aicrm_crmentity.deleted"] = "0";

      //$this->db->select("aicrm_branchs.*, aicrm_branchscf.* ");
      $this->db->select("message_customer.* " ,false);
   

      //$this->db->join('aicrm_branchscf', 'aicrm_branchscf.branchid = aicrm_branchs.branchid');
      //$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_branchs.branchid');

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

      //echo $this->db->last_query(); exit();

     if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        //$a_total = $this->get_total_branch($a_condition) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = (@$a_total["status"]) ? @$a_total["result"]["total"] : 0;
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

      //alert($a_return);exit;
      return $a_return;
  }

}