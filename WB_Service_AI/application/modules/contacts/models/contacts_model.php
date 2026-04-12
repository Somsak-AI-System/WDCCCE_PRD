<?php
class Contacts_model extends CI_Model
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
  			$a_condition["aicrm_crmentity.setype"] = "Contacts";
  			$a_condition["aicrm_crmentity.deleted"] = "0";
  		}

  		$this->db->select("count(DISTINCT aicrm_contactdetails.contactid) as total");
  		$this->db->join('aicrm_contactscf', 'aicrm_contactscf.contactid = aicrm_contactdetails.contactid',"inner");
  		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_contactdetails.contactid',"inner");
      $this->db->join('aicrm_contactaddress', 'aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid', "inner");
      $this->db->join('aicrm_contactsubdetails', 'aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid', "inner");
      $this->db->join('aicrm_customerdetails', 'aicrm_customerdetails.customerid = aicrm_contactdetails.contactid', "inner");
  		

      $query = $this->db->get('aicrm_contactdetails');

      //echo $this->db->last_query(); exit;

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
  	$this->load->library('crmentity');
  	try {
  		$a_condition["aicrm_crmentity.setype"] = "Contacts";
  		$a_condition["aicrm_crmentity.deleted"] = "0";
  		
      //$this->db->select("aicrm_account.* ,aicrm_account.phone as account_phone ,aicrm_contactdetails.* ,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname ) as full_name ,aicrm_crmentity.modifiedtime ",false );

      $this->db->select("ifnull(aicrm_account.accountid,'') as accountid ,ifnull(aicrm_account.accountname,'') as accountname ,ifnull(aicrm_account.brands,'') as brands ,ifnull(aicrm_account.phone,'') as account_phone , ifnull(aicrm_account.taxpayer_identification_no_bill_to,'') as taxpayer_identification_no_bill_to ,ifnull(aicrm_account.bill_to_address,'') as bill_to_address ,ifnull(aicrm_account.contact_person,'') as contact_person , ifnull(aicrm_account.contact_tel,'') as contact_tel ,ifnull(aicrm_account.mailing_address ,'') as mailing_address ,
aicrm_contactdetails.*,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname ) as full_name ,aicrm_crmentity.modifiedtime ,aicrm_account.corporate_registration_number_crn ",false );

      $this->db->join('aicrm_contactscf', 'aicrm_contactscf.contactid = aicrm_contactdetails.contactid', "inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_contactdetails.contactid',"inner");
      $this->db->join('aicrm_contactaddress', 'aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid', "inner");
      $this->db->join('aicrm_contactsubdetails', 'aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid', "inner");
      //$this->db->join('aicrm_customerdetails', 'aicrm_customerdetails.customerid = aicrm_contactdetails.contactid', "inner");
      $this->db->join('aicrm_crmentityrel', 'aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid', "left");
      
      //$this->db->join('aicrm_account', 'aicrm_crmentityrel.relcrmid = aicrm_account.accountid OR aicrm_crmentityrel.crmid = aicrm_account.accountid', "left");

      $this->db->join('( select aicrm_account.* from aicrm_account inner join aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid where aicrm_crmentity.deleted =0 ) aicrm_account ON `aicrm_crmentityrel`.`relcrmid` = `aicrm_account`.`accountid` OR aicrm_crmentityrel.crmid = aicrm_account.accountid', "left");
      /*LEFT JOIN ( select aicrm_account.* from aicrm_account
  inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
    where aicrm_crmentity.deleted =0
) aicrm_account ON `aicrm_crmentityrel`.`relcrmid` = `aicrm_account`.`accountid` OR aicrm_crmentityrel.crmid = aicrm_account.accountid*/

      //INNER JOIN aicrm_crmentityrel ON (aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
      //LEFT JOIN aicrm_account ON (aicrm_crmentityrel.relcrmid = aicrm_account.accountid OR aicrm_crmentityrel.crmid = aicrm_account.accountid) 
      //$this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_contactdetails.accountid');
  		
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
  		$query = $this->db->get('aicrm_contactdetails');

      //echo $this->db->last_query(); exit;
	  
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


  function get_contact($a_request=array())
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
      
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_contactdetails.contactid',"inner");
      $this->db->join('aicrm_contactaddress', 'aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid',"inner");           
      $this->db->join('aicrm_contactsubdetails', 'aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid',"inner");
      $this->db->join('aicrm_contactscf', 'aicrm_contactscf.contactid = aicrm_contactdetails.contactid',"inner");
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $query = $this->db->get('aicrm_contactdetails');

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