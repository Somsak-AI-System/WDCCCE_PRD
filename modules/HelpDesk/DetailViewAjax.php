<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
global $adb;

$local_log =& LoggerManager::getLogger('HelpDeskAjax');
global $currentModule;
$modObj = CRMEntity::getInstance($currentModule);

$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "DETAILVIEW")
{
	$crmid = $_REQUEST["recordid"];
	$tablename = $_REQUEST["tableName"];
	$fieldname = $_REQUEST["fldName"];
	$fieldvalue = utf8RawUrlDecode($_REQUEST["fieldValue"]);

	if($crmid != ""){
		$modObj->retrieve_entity_info($crmid,"HelpDesk");

		//Added to avoid the comment save, when we edit other fields through ajax edit
		if($fieldname != 'comments')
			$modObj->column_fields['comments'] = '';

		$modObj->column_fields[$fieldname] = $fieldvalue;
		$modObj->id = $crmid;
		$modObj->mode = "edit";

		//Added to construct the update log for Ticket history
		$assigned_group_name = getGroupName($_REQUEST['assigned_group_id']);
		$assigntype = $_REQUEST['assigntype'];

		$fldvalue = $modObj->constructUpdateLog($modObj, $modObj->mode, $assigned_group_name, $assigntype);
		$fldvalue = from_html($fldvalue,($modObj->mode == 'edit')?true:false);

		$modObj->save("HelpDesk");

		if( $modObj->column_fields['ticketstatus']== $mod_strings["Closed"]){
			$sqlupdate = " update   aicrm_ticketcf set cf_1491 = '".date('Y-m-d')."' where  ticketid= '".$modObj->id."' and ( UNIX_TIMESTAMP(cf_1491) = 0 or cf_1491 is null  )   ";
			$adb->pquery($sqlupdate, array());
		}

		global $mod_strings;
		if($fieldname == "solution" || $fieldname == "comments" || $fieldname =="assigned_user_id" ||($fieldname == "ticketstatus" && $fieldvalue == $mod_strings['Closed'])){
			require_once('modules/Emails/mail.php');
			$user_emailid = getUserEmailId('id',$modObj->column_fields['assigned_user_id']);
			if($modObj->mode == 'edit')
				$reply = 'Re : ';
			else
				$reply = '';
			if($modObj->column_fields['ticketstatus']==$mod_strings["Closed"]){
				$subject = $modObj->column_fields['ticket_no'] . ' [ Closed '.$mod_strings['LBL_TICKET_ID'].' : '.$modObj->id.' ] Re : '.$modObj->column_fields['ticket_title'];
			}else{
				$subject = $modObj->column_fields['ticket_no'] . ' [ '.$mod_strings['LBL_TICKET_ID'].' : '.$modObj->id.' ] Re : '.$modObj->column_fields['ticket_title'];
			}
			$str_profix=split("-",$modObj->column_fields['cf_1050']);
			$subject =$modObj->column_fields['ticket_title']." ".$modObj->column_fields['cf_1149']." ,".$modObj->column_fields['cf_969'];

			$parent_id = $modObj->column_fields['parent_id'];
			if(!empty($parent_id) && $parent_id!=0){
				$parent_module = getSalesEntityType($parent_id);
				if($parent_module == 'Contacts'){
					$result = $adb->pquery("select * from aicrm_contactdetails where contactid=?", array($parent_id));
					$emailoptout = $adb->query_result($result,0,'emailoptout');
					$contactname = $adb->query_result($result,0,'firstname').' '.$adb->query_result($result,0,'lastname');
					$parentname = $contactname;
					$contact_mailid = $adb->query_result($result,0,'email');
				}
				if($parent_module == 'Accounts'){
					$result = $adb->pquery("select * from aicrm_account where accountid=?", array($parent_id));
					$emailoptout = $adb->query_result($result,0,'emailoptout');
					$parentname = $adb->query_result($result,0,'accountname');
				}
				if($contact_mailid != ''){
					$sql = "select * from aicrm_portalinfo where user_name=?";
					$isactive = $adb->query_result($adb->pquery($sql, array($contact_mailid)),0,'isactive');
				}

				if($isactive == 1){
					$url = "<a href='".$PORTAL_URL."/index.php?module=Tickets&action=index&ticketid=".$modObj->id."&fun=detail'>Ticket Details</a>";
					$email_body = $subject.'<br><br>'.getPortalInfo_Ticket($modObj->id,$sub,$contactname,$url,"edit");
				}else{
					$data['sub']=$modObj->column_fields['ticket_title'];
					$data['parent_name']=$parentname;
					$data['status']=$modObj->column_fields['ticketstatus'];
					$data['category']=$modObj->column_fields['ticketcategories'];
					$data['severity'] = $modObj->column_fields['ticketseverities'];
					$data['priority']=$modObj->column_fields['ticketpriorities'];
					$data['description']=$modObj->column_fields['description'];
					$data['solution'] = $modObj->column_fields['solution'];
					$data['mode']= 'edit';
					$email_body = getTicketDetails($modObj->id,$data);
				}
			}
			$data['sub']=$modObj->column_fields['ticket_title'];
			$data['parent_name']=$parentname;
			$data['status']=$modObj->column_fields['ticketstatus'];
			$data['category']=$modObj->column_fields['ticketcategories'];
			$data['severity'] = $modObj->column_fields['ticketseverities'];
			$data['priority']=$modObj->column_fields['ticketpriorities'];
			$data['description']=$modObj->column_fields['description'];
			$data['solution'] = $modObj->column_fields['solution'];
			$data['mode']= 'edit';
			$email_body = getTicketDetails($modObj->id,$data);
//������Ẻ group=======================================================================================
			require_once('include/email_alert/email_alert.php');
			$email_body =GetEmail("HelpDesk",'Case',$modObj->id,"ticketid");
			if($user_emailid != ''){
				$mail_status = send_mail('HelpDesk',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
			}else{
				$groupid=$modObj->column_fields['assigned_user_id'];
				$result1 = $adb->pquery("
				select aicrm_users.email1
				from aicrm_group2role
				left join aicrm_user2role on aicrm_user2role.roleid=aicrm_group2role.roleid
				left join aicrm_users on aicrm_users.id=aicrm_user2role.userid
				LEFT JOIN aicrm_users2group ON aicrm_users2group.userid = aicrm_users.id
				where
				aicrm_users.deleted=0
				and (aicrm_group2role.groupid=? or aicrm_users2group.groupid =?)
				", array($groupid,$groupid));
				$noofrows = $adb->num_rows($result1);
				for($i=0;$i<$noofrows;$i++){
					$mail_status = send_mail('HelpDesk',$adb->query_result($result1,$i,'email1'),$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
				}
			}
//������Ẻ group=======================================================================================
			if($emailoptout == 0){
				//send mail to parent
				if(!empty($parent_id)){
					$parent_email = getParentMailId($parent_module,$parent_id);
					//$mail_status = send_mail('HelpDesk',$parent_email,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
				}
			}
		}
		//update the log information for ticket history
		$adb->pquery("update aicrm_troubletickets set update_log=? where ticketid=?", array($fldvalue, $modObj->id));

		if($modObj->id != ""){
			if($fieldname == "comments"){
				$comments = $modObj->getCommentInformation($modObj->id);
				echo ":#:SUCCESS".$comments;
			}else{
				echo ":#:SUCCESS";
			}
		}else{
			echo ":#:FAILURE";
		}
	}else{
		echo ":#:FAILURE";
	}
}
?>