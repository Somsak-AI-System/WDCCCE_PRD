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

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/Activity.php');
require_once("config.php");
require_once('modules/Calendar/CalendarCommon.php');

$local_log =& LoggerManager::getLogger('index');
$activity_mode = vtlib_purify($_REQUEST['activity_mode']);
$tab_type = 'Calendar';

$focus = new Activity();
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
		$sql = "select aicrm_activity.activitytype,
		aicrm_activity.date_start ,case when (aicrm_activity.status not like '') then aicrm_activity.status else aicrm_activity.eventstatus end as activitystatus,
		aicrm_activity.time_start,aicrm_activity.time_end, aicrm_activity.activityid, aicrm_account.accountid, aicrm_leaddetails.leadid ,aicrm_activity.activitytype as type, 
		aicrm_activity.priority, case when (aicrm_activity.status not like '') then aicrm_activity.status else aicrm_activity.eventstatus end as status, 
		aicrm_crmentity.crmid ,aicrm_crmentity.smownerid ,aicrm_activity.parentid 
		FROM aicrm_activity 
		LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid 
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid 
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid 
		LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid 
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_activity.activityid  = '".$focus->id."' ";
	
		$activity_data = $adb->pquery($sql,'');
		$activitytype = $adb->query_result($activity_data, 0, "activitytype");					
		$activitystatus = $adb->query_result($activity_data, 0, "activitystatus");		
		$date_start = date('d-m-Y', strtotime($adb->query_result($activity_data, 0, "date_start")));
		$time_start = $adb->query_result($activity_data, 0, "time_start");	
		$parentid = $adb->query_result($activity_data, 0, "parentid");	
		$smownerid = $adb->query_result($activity_data, 0, "smownerid");
		$sql_comment = "select count(commentplanid) as num_record from aicrm_commentplan where activityid = '".$focus->id."' ";

		$data_comment = $adb->pquery($sql_comment,'');
		$num_record = $adb->query_result($data_comment, 0, "num_record");	

		// $focus->column_fields['commentplan'] = '';

}
//echo "<pre>"; print_r($focus->column_fields); echo "</pre>";exit;
$focus->save($tab_type);
		
if($focus->mode == "edit"){
	$sql_editcomment = "select count(commentplanid) as num_record from aicrm_commentplan where activityid = '".$focus->id."' ";
	$data_editcomment = $adb->pquery($sql_editcomment,'');
	$num_edit_record = $adb->query_result($data_editcomment, 0, "num_record");
}

/*if($focus->mode == "edit" && ($activitytype != $focus->column_fields['activitytype'] ||  $activitystatus  != $focus->column_fields['eventstatus'] 
   || $date_start  != $focus->column_fields['date_start']  || $time_start  != $focus->column_fields['time_start'] || $parentid  != $focus->column_fields['parentid']  || $smownerid  != $focus->column_fields['assigned_user_id']  || $num_record != $num_edit_record )){
		//Send Notification
		require_once("library/general.php");
		include_once("library/log.php");
		global $generate,$current_user,$root_directory,$root_directory,$site_URL;
		
		$General = new libGeneral();
		$Log = new log();
		$Log->_logname ="logs/send_notification";
					
		$url = $site_URL."WB_Service_AI/calendar/send_notification" ;
		$a_param["AI-API-KEY"] = "1234";
		$a_param["crmid"] = $focus->id;
		$a_param["action"] = "";
		$a_param["data"] = $focus->column_fields;
		$a_param["userid"] = $current_user->currency_id;
		$a_param["smownerid"] = $user_assigned ;			
	
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
					
		$url = $site_URL."WB_Service_AI/calendar/send_notification" ;
		$a_param["AI-API-KEY"] = "1234";
		$a_param["crmid"] = $focus->id;
		$a_param["action"] = "";
		$a_param["data"] = $focus->column_fields;	
		$a_param["userid"] = $current_user->currency_id;
		$a_param["smownerid"] = $user_assigned ;		
		$a_curl = $General->curl($url,$a_param,"json");
		
		$Log->write_log("url =>".$url);
		$Log->write_log("parameter =>".json_encode($a_param));
		$Log->write_log("response =>".json_encode(@$a_curl));	
}*/

$return_id = $focus->id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Calendar";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);

//echo $_REQUEST['return_id']; exit;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == "Job")
{
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "")
	{
		 $sql = "insert into aicrm_crmentityrel values (?,?,?,?)";
		 $adb->pquery($sql, array($_REQUEST['return_id'],$_REQUEST['return_module'],$focus->id,'Calendar'));
	}
}

header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>