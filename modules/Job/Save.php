<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /cvsroot/vtigercrm/aicrm_crm/modules/Quotes/Save.php,v 1.10 2005/12/14 18:51:30 jerrydgeorge Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Job/Job.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
include("modules/Emails/mail.php");



$local_log =& LoggerManager::getLogger('index');

$focus = new Job();
//added to fix 4600
$search=vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);

$focus->column_fields['currency_id'] = $_REQUEST['inventory_currency'];
$cur_sym_rate = getCurrencySymbolandCRate($_REQUEST['inventory_currency']);
$focus->column_fields['conversion_rate'] = $cur_sym_rate['rate'];

if($_REQUEST['assigntype'] == 'U') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
	$user_assigned = $_REQUEST['assigned_user_id'];

} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
	require_once('include/utils/GetGroupUsers.php');
	$getGroupObj=new GetGroupUsers();
	$getGroupObj->getAllUsersInGroup($focus->column_fields['assigned_user_id']);
	$userIds=$getGroupObj->group_users;
	$group_user_assigned = implode(",", $userIds);
	$user_assigned = $group_user_assigned;
}


if($focus->mode == "edit"){

	global $adb, $log;
	$sql = "select aicrm_jobs.*,aicrm_jobscf.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid
	from aicrm_jobs
	inner join aicrm_jobscf on aicrm_jobscf.jobid = aicrm_jobs.jobid
	left join aicrm_account on aicrm_account.accountid = aicrm_jobs.accountid
	inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_jobs.jobid
	left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid 
	where aicrm_crmentity.deleted = 0 and aicrm_crmentity.crmid = '".$focus->id."' ";

		// echo $sql; exit();

	$job_data = $adb->pquery($sql,'');
	$jobid = $adb->query_result($job_data, 0, "jobid");	
	$servicerequestid = $adb->query_result($job_data, 0, "servicerequestid");			
	$job_status = $adb->query_result($job_data, 0, "job_status");
	$job_close_date = $adb->query_result($job_data, 0, "close_date");
	$job_closed_time = $adb->query_result($job_data, 0, "closed_time");

		// echo $job_status; exit();

	$jobdate_operate = date('d-m-Y', strtotime($adb->query_result($job_data, 0, "jobdate_operate")));

		// echo $jobdate_operate; exit();
	$start_time = $adb->query_result($job_data, 0, "start_time");	
	$smownerid = $adb->query_result($job_data, 0, "smownerid");	

}
//exit;
$focus->save("Job");

if($focus->mode == "edit"){
	include_once("library/dbconfig.php");
	include_once("library/generate_MYSQL.php");
	global $generate;
	$generate = new generate($dbconfig ,"DB");

	$close_date = date('Y-m-d');
	$closed_time = date('H:i');

	$sql_servicerequest = "SELECT jobid FROM aicrm_jobs WHERE servicerequestid = '".$servicerequestid."' and job_status <> 'ปิดงาน'";
	$res =$generate->process($sql_servicerequest,"all");
	$rows = $generate->num_rows($res);

	// echo $rows;

	if($rows == 0){
		$sql_update_servicerequest = "UPDATE aicrm_servicerequest SET service_request_status = 'ปิดงาน', closed_date = '$close_date', closed_time = '$closed_time' WHERE servicerequestid = '".$servicerequestid."'";
		$generate->query($sql_update_servicerequest);
		// echo $sql_update_servicerequest;
	}
	if($job_status == "ปิดงาน"){
		$sql = "UPDATE aicrm_jobs SET close_date = '$close_date', closed_time = '$closed_time' WHERE jobid = '".$focus->id."' ";
		$generate->query($sql);
	}
	// exit();
}

/*if($focus->mode == "edit" && ($job_status != $focus->column_fields['job_status'] || $jobdate_operate  != $focus->column_fields['jobdate_operate']  || $start_time  != $focus->column_fields['start_time'] || $smownerid  != $focus->column_fields['assigned_user_id'] ) ){
		//Send Notification
		require_once("library/general.php");
		include_once("library/log.php");
		global $generate,$current_user,$root_directory,$root_directory,$site_URL;
		
		$General = new libGeneral();
		$Log = new log();
		$Log->_logname ="logs/send_notification";
		$url = $site_URL."WB_Service_AI/job/send_notification" ;
		$a_param["AI-API-KEY"] = "1234";
		$a_param["crmid"] = $focus->id;
		$a_param["action"] = "";
		$a_param["data"] = $focus->column_fields;
		$a_param["userid"] = $current_user->currency_id;
		$a_param["smownerid"] = $user_assigned;
		$a_curl = $General->curl($url,$a_param,"json");
		$Log->write_log("url =>".$url);
		$Log->write_log("parameter =>".json_encode($a_param));
		$Log->write_log("response =>".json_encode($a_curl));	

}else if($focus->mode != "edit"){
    //Save
	require_once("library/general.php");
	include_once("library/log.php");
	global $generate,$current_user,$root_directory,$root_directory,$site_URL;

	$General = new libGeneral();
	$Log = new log();
	$Log->_logname ="logs/send_notification";	
	$url = $site_URL."WB_Service_AI/job/send_notification" ;
	
	$a_param["AI-API-KEY"] = "1234";
	$a_param["crmid"] = $focus->id;
	$a_param["action"] = "";
	$a_param["data"] = $focus->column_fields;	
	$a_param["userid"] = $current_user->currency_id;
	$a_param["smownerid"] = $user_assigned;
	$a_curl = $General->curl($url,$a_param,"json");
	$Log->write_log("url =>".$url);
	$Log->write_log("parameter =>".json_encode($a_param));
	$Log->write_log("response =>".json_encode($a_curl));	
}*/

$return_id = $focus->id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Job";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == "JobList")
{
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "")
	{

		$sql = "delete from aicrm_jobslist_jobsrel  where jobid  = ?";
		$adb->pquery($sql, array($focus->id));
		$sql = "insert into aicrm_jobslist_jobsrel  values (?,?)";
		$adb->pquery($sql, array($_REQUEST['return_id'], $focus->id));
	}
}

if ($return_action == "CallRelatedList" && $return_module =="HelpDesk"){
    header("Location: index.php?action=CallRelatedList&module=$return_module&parenttab=$parenttab&record=$return_id");

}else{
    header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);

}
?>