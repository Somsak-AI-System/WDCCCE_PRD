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
 * $Header$
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('include/CustomFieldUtil.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');
global $mod_strings,$app_strings,$currentModule,$theme,$singlepane_view;

$focus = CRMEntity::getInstance($currentModule);

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Smartquestionnaire");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	
} 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Smartquestionnaire detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
 
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

$focus->column_fields['email_start_date'] = $focus->column_fields['email_start_date'] != '' && $focus->column_fields['email_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($focus->column_fields['email_start_date'])):'';
$focus->column_fields['prepare_date'] = $focus->column_fields['prepare_date'] != '' && $focus->column_fields['prepare_date'] != '0000-00-00' ? date('d-m-Y', strtotime($focus->column_fields['prepare_date'])):'';
$focus->column_fields['send_date'] = $focus->column_fields['send_date'] != '' && $focus->column_fields['send_date'] != '0000-00-00' ? date('d-m-Y', strtotime($focus->column_fields['send_date'])):'';

$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));
$smarty->assign("UPDATEINFO",updateInfo($focus->id));


/*echo "<pre>";
print_r(getBlocks($currentModule,"detail_view",'',$focus->column_fields));
echo "</pre>";
exit;*/
$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("ID", vtlib_purify($_REQUEST['record']));

// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if ($mod_seq_field != null) {
	$mod_seq_id = $focus->column_fields[$mod_seq_field['name']];
} else {
	$mod_seq_id = $focus->id;
}
$smarty->assign('MOD_SEQ_ID', $mod_seq_id);
// END

$smarty->assign("SINGLE_MOD",'Smart Questionnaire');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Smartquestionnaire","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

	
$smarty->assign("CREATEPDF","permitted");

if(isPermitted("SalesOrder","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTSALESORDER","permitted");

if(isPermitted("Invoice","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTINVOICE","permitted");

if(isPermitted("Smartquestionnaire","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Smartquestionnaire',$focus));

$pquery = "select smartquestionnaire_status , prepare_status from aicrm_smartquestionnaire where smartquestionnaireid = '".$focus->id."' ";
$cf_res = $adb->pquery($pquery,'');
$smartquestionnaire_status=$adb->query_result($cf_res, 0, "smartquestionnaire_status");
$prepare_status=$adb->query_result($cf_res, 0, "prepare_status");
$smarty->assign("questionnairetemplateid",$focus->column_fields['questionnairetemplateid']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

 $tabid = getTabid("Smartquestionnaire");
 $validationData = getDBValidationData($focus->tab_name,$tabid);
 $data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);
$smarty->assign("EDIT_PERMISSION",isPermitted($currentModule,'EditView',$_REQUEST['record']));

$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));

if($singlepane_view == 'true')
{
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);
}

$smarty->assign("SinglePane_View", $singlepane_view);
$smarty->assign("TODO_PERMISSION",CheckFieldPermission('parent_id','Calendar'));
$smarty->assign("EVENT_PERMISSION",CheckFieldPermission('parent_id','Events'));

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}
// Record Change Notification
$focus->markAsViewed($current_user->id);
// END

$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', false));
global $smartquestionnaireid,$prepare_status,$send_email;

// global $smartquestionnaireid,$setup_sms,$send_sms;
//$pquery = "select sms_status, setup_sms,send_sms from aicrm_smartquestionnaire where smartquestionnaireid = '".$focus->id."' ";
//$cf_res = $adb->pquery($pquery,'');
//$setup_sms=$adb->query_result($cf_res, 0, "setup_sms");
//$send_sms=$adb->query_result($cf_res, 0, "send_sms");
//$sms_status=$adb->query_result($cf_res, 0, "sms_status");
/*$pquery = "select sms_status from aicrm_smartquestionnaire where smartquestionnaireid = '".$focus->id."' ";
$cf_res = $adb->pquery($pquery,'');
$sms_status=$adb->query_result($cf_res, 0, "sms_status");*/
$smartquestionnaireid=$focus->id;

$resend = 0;

if($prepare_status == 1 && $send_email == 1 && $smartquestionnaire_status == 'InActive'){
	$pquery = "SELECT count(resend) as resend FROM tbt_email_log_smartquestionnaireid_".$focus->id." WHERE 1 and resend > 0 ";
	$field = $adb->pquery($pquery,'');
	$resend=$adb->query_result($field, 0, "resend");
}

$smarty->assign("smartquestionnaire_status",$smartquestionnaire_status);
$smarty->assign("prepare_status",$prepare_status);


$pquery = " select a.email Email
from
(	
		select 
		aicrm_account.email1  as email
		from aicrm_smartquestionnaire_accountsrel
		left join aicrm_account  on aicrm_smartquestionnaire_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartquestionnaire_accountsrel.smartquestionnaireid= '".$focus->id."'
		and email1<>''
		group by email1
		
		union
		select 
		aicrm_leaddetails.email as email
		from aicrm_smartquestionnaire_leadsrel
		left join aicrm_leaddetails  on aicrm_smartquestionnaire_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartquestionnaire_leadsrel.smartquestionnaireid='".$focus->id."'
		and email<>''
		group by email
		
		union
	    select 
	    email
	    from aicrm_smartquestionnaire_contactsrel
	    left join aicrm_contactdetails on aicrm_smartquestionnaire_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
	    and aicrm_smartquestionnaire_contactsrel.smartquestionnaireid='".$focus->id."'
	    and email<>''
		group by email 
) as a 
group by email";

$res=$adb->pquery($pquery, array());
$count_email = $adb->num_rows($res);

//echo "<pre>"; print_r($count_email); echo "</pre>"; exit;

$smarty->assign("count_email",$count_email);

$rsChkTable = $adb->pquery($pquery, array());
$countChk = $adb->num_rows($rsChkTable);

// echo $countChk; exit();

if($countChk != 0){
	$smarty->assign("tbtEmailLog", 'yes');
}else{
	$smarty->assign("tbtEmailLog", 'no');
}

$smarty->display("DetailView.tpl");
?>