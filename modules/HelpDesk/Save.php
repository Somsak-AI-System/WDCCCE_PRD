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
 * $Header: /advent/projects/wesat/aicrm_crm/vtigercrm/modules/HelpDesk/Save.php,v 1.8 2005/04/25 05:21:46 Mickie Exp $
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

$focus = new HelpDesk();

//added to fix 4600
$search=vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);
global $adb,$mod_strings;
//Added to update the ticket history
//Before save we have to construct the update log.
$mode = $_REQUEST['mode'];
if($mode == 'edit')
{
	$usr_qry = $adb->pquery("select * from aicrm_crmentity where crmid=?", array($focus->id));
	$old_user_id = $adb->query_result($usr_qry,0,"smownerid");
}
$grp_name = getGroupName($_REQUEST['assigned_group_id']);

if($_REQUEST['assigntype'] == 'U')  {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T'){
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}

$fldvalue = $focus->constructUpdateLog($focus, $mode, $grp_name, $_REQUEST['assigntype']);
$fldvalue = from_html($fldvalue,($mode == 'edit')?true:false);

$focus->save("HelpDesk");
//After save the record, we should update the log
$total_claim = $_REQUEST["total_claim"] ;
$total_return= $_REQUEST["total_return"] ;

$adb->pquery("update aicrm_troubletickets set update_log=?,total_claim=?,total_return=?  where ticketid=?", array($fldvalue,$total_claim,$total_return,$focus->id));

$return_id = $focus->id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "HelpDesk";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

if($_REQUEST['mode'] == 'edit')
	$reply = 'Re : ';
else
	$reply = '';

$query="select ticket_no,title from aicrm_troubletickets  where ticketid=?";
$params = array($focus->id);
$data_tik = $adb->pquery($query, $params);
$ticketno=$adb->query_result($data_tik,0,'title');
$ticket_no=$adb->query_result($data_tik,0,'ticket_no');

//Send Email Team QA
/*if($focus->column_fields['cf_4660'] == 'Yes'){
	require_once('include/email_alert/email_alert.php');
	if($mode == 'edit'){
		$act = 'Edit';
	}else{
		$act = 'Create';
	}
	$subject = "Record ".$act." Case -: ".$ticket_no;
	require_once('modules/Emails/mail.php');
	$user_emailid = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
	$user_name = getUserName($focus->column_fields['assigned_user_id']);
	$email_body ='';
	$email_body = "Dear QA Team,<br><br>".$user_name." Create<br>";
	
	$email_body .= "Case No : ".$ticket_no."<br>";
	$email_body .= "Case Name : ".$ticketno."<br>";
	$email_body .= "has been created for Case <br><br>";
	$email_body .="Link <a href='".$site_URL."/index.php?action=DetailView&module=HelpDesk&record=".$focus->id."&parenttab=Support'>Click here</a><br><br>";
	$email_body .= "Thanks,<br>".$user_name;
	$user_emailid = 'csc@qbiosci.com';
	$bcc = 'wuttipong@aisyst.com,noppawat@aisyst.com';
	//echo $email_body; exit;
	send_mail('HelpDesk',$user_emailid,'','',$subject,$email_body,'',$bcc);
}*/

$_REQUEST['ticket_title']=$ticketno;
if($focus->column_fields['ticketstatus'] == $mod_strings["Closed"]){
	$subject = $focus->column_fields['ticket_no'] . ' [ Closed '.$mod_strings['LBL_TICKET_ID'].' : '.$focus->id.' ] '.$reply.$_REQUEST['ticket_title'];
}else{
	$subject = $focus->column_fields['ticket_no'] . ' [ '.$mod_strings['LBL_TICKET_ID'].' : '.$focus->id.' ] '.$reply.$_REQUEST['ticket_title'];
}

$subject =$_REQUEST['ticket_title'];
$bodysubject = $mod_strings['Case No'] .":<br>" . $focus->column_fields['ticket_no'] . "<br>" . $mod_strings['LBL_TICKET_ID'].' : '.$focus->id.'<br> '.$mod_strings['LBL_SUBJECT'].$_REQUEST['ticket_title'];

$emailoptout = 0;

//Get the status of the aicrm_portal user. if the customer is active then send the aicrm_portal link in the mail
if($contact_mailid != '')
{
	$sql = "select * from aicrm_portalinfo where user_name=?";
	$isactive = $adb->query_result($adb->pquery($sql, array($contact_mailid)),0,'isactive');
}
if($isactive == 1)
{
	$url = "<a href='".$PORTAL_URL."/index.php?module=Tickets&action=index&ticketid=".$focus->id."&fun=detail'>".$mod_strings['LBL_TICKET_DETAILS']."</a>";
	$email_body = $bodysubject.'<br><br>'.getPortalInfo_Ticket($focus->id,$_REQUEST['ticket_title'],$contactname,$url,$_REQUEST['mode']);
}
else
{
	$data['sub']=$_REQUEST['ticket_title'];
	$data['parent_name']=$parentname;
	$data['status']=$focus->column_fields['ticketstatus'];
	$data['category']=$focus->column_fields['ticketcategories'];
	$data['severity'] = $focus->column_fields['ticketseverities'];
	$data['priority']=$focus->column_fields['ticketpriorities'];
	$data['description']=$focus->column_fields['description'];
	$data['solution'] = $focus->column_fields['solution'];
	$data['mode']= $_REQUEST['mode'];
	$email_body = getTicketDetails($focus->id,$data);
}
//get email========================================================================
//require_once('include/email_alert/email_alert.php');
//$email_body =GetEmail("HelpDesk",'Case',$focus->id,"ticketid");
//get email========================================================================
$_REQUEST['return_id'] = $return_id;

if($_REQUEST['return_module'] == 'Products' & $_REQUEST['product_id'] != '' &&  $focus->id != '')
	$return_id = vtlib_purify($_REQUEST['product_id']);

//get email========================================================================
$pquery = "select aicrm_troubletickets.ticket_no,aicrm_troubletickets.title ,aicrm_troubletickets.case_category
from aicrm_troubletickets
left join aicrm_ticketcf on aicrm_ticketcf.ticketid=aicrm_troubletickets.ticketid
where aicrm_troubletickets.ticketid = '".$focus->id."' ";
//echo $pquery;exit;
$cf_res = $adb->pquery($pquery,'');
$ticket_no=$adb->query_result($cf_res, 0, "ticket_no");
$case_category=$adb->query_result($cf_res, 0, "case_category");
$title=$adb->query_result($cf_res, 0, "title");

// require_once('include/email_alert/email_alert.php');
// $email_body =GetEmail("HelpDesk",'header_alert.jpg',$focus->id,"ticketid");

// $type = 'ประเภทการรับเรื่อง';
// $utf8 = iconv("tis-620", "utf-8", $type );
// $subject = "Case No : [".$ticket_no."] ".$utf8." : ".$case_category;
// //get email========================================================================
// //send mail to the assigned to user and the parent to whom this ticket is assigned
// require_once('modules/Emails/mail.php');
// $user_emailid = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
// if($user_emailid != '')
// {
// 	if($_REQUEST['mode'] != 'edit')
// 	{
// 		$mail_status = send_mail('HelpDesk',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
// 	}
// 	else
// 	{
// 		if(($focus->column_fields['ticketstatus'] == $mod_strings["Closed"]) || ($focus->column_fields['assigned_user_id'] != $old_user_id))
// 		{
// 			$mail_status = send_mail('HelpDesk',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
// 		}
// 	}
// 	$mail_status_str = $user_emailid."=".$mail_status."&&&";
// }
// else
// {
// 	$mail_status_str = "'".$to_email."'=0&&&";
// }
// //added condition to check the emailoptout(this is for contacts and vtiger_accounts.)

// if($emailoptout == 0)
// {
// 	//send mail to parent
// 	if($_REQUEST['parent_id'] != '' && $_REQUEST['parent_type'] != '')
//         {
//                 $parentmodule = $_REQUEST['parent_type'];
//                 $parentid = $_REQUEST['parent_id'];

// 		$parent_email = getParentMailId($parentmodule,$parentid);

// 		if($_REQUEST['mode'] != 'edit'){
// 			$mail_status = send_mail('HelpDesk',$parent_email,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
// 		}else{
// 			if(( $focus->column_fields['ticketstatus']== $mod_strings["Closed"]) || ($focus->column_fields['comments'] != '' ) || ($_REQUEST['helpdesk_solution'] != $_REQUEST['solution']))
// 			{
// 				$mail_status = send_mail('HelpDesk',$parent_email,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
// 			}
// 		}
// 		$mail_status_str .= $parent_email."=".$mail_status."&&&";
//         }
// }
// else
// {
// 	$adb->println("'".$parentname."' is not want to get the email about the ticket details as emailoptout is selected");
// }

// if ($mail_status != '') {
// 	$mail_error_status = getMailErrorString($mail_status_str);
// }

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);
header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&$mail_error_status&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>