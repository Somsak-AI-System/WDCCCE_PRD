<?
	function get_pk($table_name){
		if($table_name=="aicrm_crmentity"){
			$PK="crmid";	
		}else if ($table_name=="aicrm_accountbillads" ||$table_name=="aicrm_accountshipads"){
			$PK="accountaddressid";
		}else if ($table_name=="aicrm_account" ||$table_name=="aicrm_accountscf"){
			$PK="accountid";
		}else if ($table_name=="aicrm_activity" ||$table_name=="aicrm_activitycf" ||$table_name=="aicrm_seactivityrel"){
			$PK="activityid";
		}else if ($table_name=="aicrm_servicerequests" ||$table_name=="aicrm_servicerequestscf"){
			$PK="servicerequestid";
		}else if ($table_name=="aicrm_jobdetails" ||$table_name=="aicrm_jobdetailscf"){
			$PK="jobdetailid";
		}
		return $PK;
	}
?>