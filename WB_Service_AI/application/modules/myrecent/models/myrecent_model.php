<?php
class myrecent_model extends CI_Model
{
  var $ci;

  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	$this->load->library("common");
    $this->_limit = "10";
  }




//   function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$a_conditionin=array())
  function get_list($userid=array())
  {

  	try {

			$sql=" select
		aicrm_audit_trial.module,
		CASE 
		WHEN aicrm_audit_trial.module = 'Accounts' THEN aicrm_account.name 
		WHEN aicrm_audit_trial.module = 'Leads' THEN aicrm_leaddetails.name 
		WHEN aicrm_audit_trial.module = 'Deal' THEN aicrm_deal.name
		WHEN aicrm_audit_trial.module = 'Calendar' THEN aicrm_activity.name
		WHEN aicrm_audit_trial.module = 'Questionnaire' THEN aicrm_questionnaire.name
		ELSE '' 
		END as name,
			CASE 
		WHEN aicrm_audit_trial.module = 'Accounts' THEN aicrm_account.no 
		WHEN aicrm_audit_trial.module = 'Leads' THEN aicrm_leaddetails.no 
		WHEN aicrm_audit_trial.module = 'Deal' THEN aicrm_deal.no
		WHEN aicrm_audit_trial.module = 'Calendar' THEN aicrm_activity.no
		WHEN aicrm_audit_trial.module = 'Questionnaire' THEN aicrm_questionnaire.no
		ELSE '' 
		END as no,
		CASE 
		WHEN aicrm_audit_trial.action = 'DetailView' THEN concat('View ',aicrm_audit_trial.module )
		WHEN aicrm_audit_trial.action = 'EditView' THEN concat('Edit ',aicrm_audit_trial.module )
		WHEN aicrm_audit_trial.action = 'Save' THEN concat('Add ',aicrm_audit_trial.module )
		ELSE ''
		END as action,
		aicrm_audit_trial.recordid as id,
		aicrm_audit_trial.userid ,
		
		DATE_FORMAT(actiondate,'%d/%m/%Y %H:%i:%s') as viewtime
		from aicrm_audit_trial
		INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_account.accountid, aicrm_account.accountname as name , aicrm_account.account_no as no from aicrm_account
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_account on aicrm_account.accountid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_leaddetails.leadid, concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as name,aicrm_leaddetails.lead_no as no  from aicrm_leaddetails
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_leaddetails on aicrm_leaddetails.leadid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_activity.activityid, aicrm_account.accountname as name ,aicrm_account.account_no as no from aicrm_activity
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_activity.activityid
		     INNER JOIN aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
		    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
    		LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_activity on aicrm_activity.activityid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_deal.dealid, aicrm_account.accountname as name, aicrm_deal.deal_no as no from aicrm_deal
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
		    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_deal.parentid
    		LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_deal.parentid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_deal on aicrm_deal.dealid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_questionnaire.questionnaireid, aicrm_questionnaire.questionnaire_name as name, aicrm_questionnaire.questionnaire_no as no from aicrm_questionnaire
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_questionnaire on aicrm_questionnaire.questionnaireid = aicrm_audit_trial.recordid
		where aicrm_audit_trial.userid='".$userid."' and aicrm_audit_trial.action in('DetailView','EditView','Save')
		and aicrm_audit_trial.module in('Accounts','Calendar','Deal','Leads','Questionnaire')
		and aicrm_crmentity.deleted=0 
		order by aicrm_audit_trial.actiondate 
		desc limit 40;";

			//  limit 40
		$query = $this->ci->db->query($sql);

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
