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

require_once('modules/Servicerequest/Servicerequest.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
include("modules/Emails/mail.php");

$local_log =& LoggerManager::getLogger('index');

$focus = new Servicerequest();
//added to fix 4600
$search=vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);

$focus->column_fields['currency_id'] = $_REQUEST['inventory_currency'];
$cur_sym_rate = getCurrencySymbolandCRate($_REQUEST['inventory_currency']);
$focus->column_fields['conversion_rate'] = $cur_sym_rate['rate'];

$mode = $_REQUEST['mode'];
if($mode == 'edit')
{
	$usr_qry = $adb->pquery("select * from aicrm_crmentity where crmid=?", array($focus->id));
	$old_user_id = $adb->query_result($usr_qry,0,"smownerid");
}
$grp_name = getGroupName($_REQUEST['assigned_group_id']);

if($_REQUEST['assigntype'] == 'U') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}
$focus->save("Servicerequest");

$return_id = $focus->id;


//get email========================================================================
$pquery = "select aicrm_servicerequest.servicerequest_no,aicrm_servicerequest.servicerequest_name ,aicrm_servicerequest.service_request_type
from aicrm_servicerequest
left join aicrm_servicerequestcf on aicrm_servicerequestcf.servicerequestid=aicrm_servicerequest.servicerequestid
where aicrm_servicerequest.servicerequestid = '".$focus->id."' ";
//echo $pquery;exit;
$cf_res = $adb->pquery($pquery,'');
$servicerequest_no=$adb->query_result($cf_res, 0, "servicerequest_no");
$service_request_type=$adb->query_result($cf_res, 0, "service_request_type");
$servicerequest_name=$adb->query_result($cf_res, 0, "servicerequest_name");

require_once('include/email_alert/email_alert.php');
$email_body =GetEmail("Servicerequest",'header_alert.jpg',$focus->id,"servicerequestid");

$utf8 = iconv("tis-620", "utf-8", $type );
$subject = "หมายเลขคำขอบริการ : [".$servicerequest_no."] ".$service_request_type." : ".$servicerequest_name;
//get email========================================================================
//send mail to the assigned to user and the parent to whom this ticket is assigned
require_once('modules/Emails/mail.php');
$user_emailid = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
if($user_emailid != '')
{
	if($_REQUEST['mode'] != 'edit')
	{
		$mail_status = send_mail('Servicerequest',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
	}
	else
	{
		if(($focus->column_fields['service_request_status'] == 'ปิดงาน') || ($focus->column_fields['assigned_user_id'] != $old_user_id))
		{
			$mail_status = send_mail('HelpDesk',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
		}
	}
	$mail_status_str = $user_emailid."=".$mail_status."&&&";
}
else
{
	$mail_status_str = "'".$to_email."'=0&&&";
}
//added condition to check the emailoptout(this is for contacts and vtiger_accounts.)

if ($mail_status != '') {
	$mail_error_status = getMailErrorString($mail_status_str);
}

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Servicerequest";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == "Accounts")
{
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "")
	{
		 
		 $sql = "delete from aicrm_crmentityrel  where relcrmid = ? and crmid = ?";
		 $adb->pquery($sql, array($focus->id, $_REQUEST['return_module']));
		 $sql = "insert into aicrm_crmentityrel  values (?,?,?,?)";
		 $adb->pquery($sql, array($_REQUEST['return_id'],$_REQUEST['return_module'],$focus->id,"Servicerequest"));
	}
}
header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>