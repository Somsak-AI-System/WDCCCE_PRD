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

require_once('modules/Quotes/Quotes.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');


include("modules/Emails/mail.php");

$local_log =& LoggerManager::getLogger('index');
$focus = new Quotes();
//added to fix 4600
$search = vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);
$focus->column_fields['currency_id'] = $_REQUEST['inventory_currency'];
$focus->column_fields['pricetype'] = $_REQUEST['pricetype'];

$cur_sym_rate = getCurrencySymbolandCRate($_REQUEST['inventory_currency']);
$focus->column_fields['conversion_rate'] = $cur_sym_rate['rate'];

if ($_REQUEST['assigntype'] == 'U') {
    $focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif ($_REQUEST['assigntype'] == 'T') {
    $focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}

$focus->save("Quotes");

$crmid = $focus->id;

$query="select quote_no from aicrm_quotes where quoteid=?";
$params = array($focus->id);        
$data_tik = $adb->pquery($query, $params);
$quote_no=$adb->query_result($data_tik,0,'quote_no');
$_REQUEST['quote_no'] = $quote_no;

//echo $quote_no; exit;
require_once('include/email_alert/email_alert.php');

$sql_group = "select userid from aicrm_users2group where groupid = ?";
$params = array($focus->column_fields['assigned_user_id']);
$data_group = $adb->pquery($sql_group, $params);
$count_data = $adb->num_rows($data_group);


// echo "<pre>"; print_r($focus); echo "</pre>"; exit;
// $data = $adb->fetch_array($data_group);
if($count_data > 0){
    $userid = Array();
    $user_email = Array();
    for ($i = 0; $i < $count_data; $i++) {
        $userid[] = $adb->query_result($data_group, $i, "userid");

        $user_email[] = getUserEmailId('id',$userid[$i]);
    }

    foreach ($user_email as $key => $value) {
        if(($key+1) < $count_data){
            $char = ",";
        }else{
            $char = "";
        }
        $user_emailid .= $value.$char;
    }
}else{
    $user_emailid = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
}
// echo $user_emailid; exit();


if($focus->column_fields['quotation_status']=="ขออนุมัติใบเสนอราคา"){
    $subject = "Request for Quotation Approval - Quote No. [".$quote_no."] เรื่อง : ".$focus->column_fields['quote_name'];
    $Dear ="Dear User,<br>You have a new quotation. Please take a moment to review and approve in this quote.<br><br>";
}else{
    $subject = "Quote No. [".$quote_no."] เรื่อง : ".$focus->column_fields['quote_name'];
    if($focus->column_fields['quotation_status']=="เปิดใบเสนอราคา"){
        $Dear ="Dear User,<br>Your Quotation has been created.<br><br>";
    }elseif($focus->column_fields['quotation_status']=="อนุมัติใบเสนอราคา"){
        $Dear ="Dear User,<br>Your Quotation has been approved.<br><br>";
    }elseif($focus->column_fields['quotation_status']=="ไม่อนุมัติใบเสนอราคา"){
        $Dear ="Dear User,<br>Your Quotation has been rejected.<br><br>";
    }elseif($focus->column_fields['quotation_status']=="ยกเลิกใบเสนอราคา"){
        $Dear ="Dear User,<br>Your Quotation has been cancelled.<br><br>";
    }
}



$email_body = GetEmail("Quotes","header_alert.jpg",$crmid,"quoteid");
// echo $email_body; exit;
// $mail_status = send_mail('Quotes',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$Dear.$email_body);

//print_r($mail_status); exit;
//Check Data Duplicate
/*if (isset($_REQUEST['Duplicateid']) && $_REQUEST['Duplicateid'] != '') {

    $sql = " select case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,'Documents' ActivityType,
				aicrm_attachments.type FileType,crm2.modifiedtime lastmodified, aicrm_seattachmentsrel.attachmentsid attachmentsid, 
				aicrm_notes.notesid crmid, aicrm_notes.notecontent description,aicrm_notes.*,aicrm_crmentity.* 
				from aicrm_notes 
				inner join aicrm_senotesrel on aicrm_senotesrel.notesid= aicrm_notes.notesid 
				inner join aicrm_crmentity on aicrm_crmentity.crmid= aicrm_notes.notesid and aicrm_crmentity.deleted=0 
				inner join aicrm_crmentity crm2 on crm2.crmid=aicrm_senotesrel.crmid 
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid =aicrm_notes.notesid 
				left join aicrm_attachments on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid 
				left join aicrm_users on aicrm_crmentity.smownerid= aicrm_users.id where crm2.crmid='" . $_REQUEST['Duplicateid'] . "' ";

    $data = $adb->pquery($sql, '');
    $count_data = $adb->num_rows($data);

    if ($count_data > 0) {

        require_once('modules/Documents/Documents.php');
        $documents_focus = new Documents();

        for ($i = 0; $i < $count_data; $i++) {

            $documents_focus->column_fields['notes_title'] = $adb->query_result($data, $i, "title");
            $documents_focus->column_fields['createdtime'] = '';
            $documents_focus->column_fields['modifiedtime'] = '';
            $documents_focus->column_fields['filename'] = $adb->query_result($data, $i, "filename");
            $documents_focus->column_fields['assigned_user_id'] = $_SESSION["authenticated_user_id"];     // Assigned To
            $documents_focus->column_fields['notecontent'] = $adb->query_result($data, $i, "notecontent");
            $documents_focus->column_fields['filetype'] = $adb->query_result($data, $i, "filetype");
            $documents_focus->column_fields['filesize'] = $adb->query_result($data, $i, "filesize");
            $documents_focus->column_fields['filelocationtype'] = $adb->query_result($data, $i, "filelocationtype");
            $documents_focus->column_fields['fileversion'] = $adb->query_result($data, $i, "fileversion");
            $documents_focus->column_fields['filestatus'] = $adb->query_result($data, $i, "filestatus");
            $documents_focus->column_fields['filedownloadcount'] = $adb->query_result($data, $i, "filedownloadcount");
            $documents_focus->column_fields['folderid'] = $adb->query_result($data, $i, "folderid");
            $documents_focus->column_fields['note_no'] = '';

            $documents_focus->parentid = $focus->id;

            $_REQUEST["module"] = "Documents";
            $documents_focus->mode = "";
            $documents_focus->id = "";
            $documents_focus->save("Documents");

            $docid = $documents_focus->id;
            //Insert aicrm_attachments Image.
            $sql_crm_seq = "SELECT (max(id)+1)as maxid FROM aicrm_crmentity_seq";
            $data_id = $adb->pquery($sql_crm_seq, '');
            $max_id = $adb->query_result($data_id, 0, "maxid");

            $sql_update = "update aicrm_crmentity_seq set id ='" . $max_id . "' ";
            $adb->pquery($sql_update, '');

            $add_crmentity = "INSERT INTO aicrm_crmentity(crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,viewedtime,status,version,presence,deleted) 
							 SELECT '" . $max_id . "', '" . $_SESSION["authenticated_user_id"] . "', '" . $_SESSION["authenticated_user_id"] . "', 0 ,setype ,description,'" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "', 
							 viewedtime,status,version,presence,deleted
							 FROM aicrm_crmentity
							 WHERE crmid= '" . $adb->query_result($data, $i, "attachmentsid") . "' ";
            $adb->pquery($add_crmentity, '');

            $insert_att = "INSERT INTO aicrm_attachments(attachmentsid,name,description,type,path,subject) 
			    SELECT '" . $max_id . "', name, description,type,path,subject
									FROM aicrm_attachments
									WHERE attachmentsid= '" . $adb->query_result($data, $i, "attachmentsid") . "'  ";
            $adb->pquery($insert_att, '');

            $insert_seattachmentsrel = "INSERT INTO aicrm_seattachmentsrel (crmid,attachmentsid)  VALUES ('" . $docid . "','" . $max_id . "')";
            $adb->pquery($insert_seattachmentsrel, '');
        }
    }
}*/
//Check Data Duplicate

$return_id = $focus->id;



// echo '<pre>';
// print_r($_REQUEST);
// echo '<pre>';
// exit();

$query = "DELETE FROM aicrm_inventory_quotes_list WHERE crmid=".$focus->id;
$adb->pquery($query, '');

function convertdateToDB($date){
   $data = explode("-", $date);
   return $data['2']."-".$data['1']."-".$data['0'];
}

if(isset($_REQUEST['list']) && !empty($_REQUEST['list'])){
    foreach($_REQUEST['list'] as $i => $no){
		$delivered_date = convertdateToDB($_REQUEST['delivered_date'.$no]);
        // echo $delivered_date; exit;
		$delivered_value = str_replace(',', '', $_REQUEST['delivered_value'.$no]);
        $remark = $_REQUEST['remark'.$no];
        $total_delivered_value =  str_replace(',', '', $_REQUEST['hdn_total_delivered_value']);

		if(!empty($delivered_value) && $delivered_value != ''){
			$query = "INSERT INTO aicrm_inventory_quotes_list (crmid, sequence_no, delivered_date, delivered_value, remark,total_delivered_value)
			VALUES ('".$focus->id."', '".$no."', '".$delivered_date."', '".$delivered_value."', '".$remark."', '".$total_delivered_value."')";
            // echo $query."<br>";
			$adb->pquery($query, '');
		}

	}
    // exit;
}



$parenttab = getParentTab();
if (isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Quotes";
if (isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if (isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of " . $return_id);

//code added for returning back to the current view after edit from list view
if ($_REQUEST['return_viewname'] == '') $return_viewname = '0';
if ($_REQUEST['return_viewname'] != '') $return_viewname = vtlib_purify($_REQUEST['return_viewname']);

header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=" . vtlib_purify($_REQUEST['pagenumber']) . $search);
?>