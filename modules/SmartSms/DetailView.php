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
    $focus->retrieve_entity_info($_REQUEST['record'],"SmartSms");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	
} 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("SmartSms detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
 
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");





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

$smarty->assign("SINGLE_MOD",'Smart sms');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("SmartSms","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

	
$smarty->assign("CREATEPDF","permitted");

if(isPermitted("SalesOrder","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTSALESORDER","permitted");

if(isPermitted("Invoice","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTINVOICE","permitted");

if(isPermitted("SmartSms","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('SmartSms',$focus));


$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

 $tabid = getTabid("SmartSms");
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

global $smartsmsid,$setup_sms,$send_sms;
/*$pquery = "select sms_status, setup_sms,send_sms from aicrm_smartsms where smartsmsid = '".$focus->id."' ";
$cf_res = $adb->pquery($pquery,'');
$setup_sms=$adb->query_result($cf_res, 0, "setup_sms");
$send_sms=$adb->query_result($cf_res, 0, "send_sms");
$sms_status=$adb->query_result($cf_res, 0, "sms_status");*/

include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$pquery = "select sms_status, setup_sms,send_sms from aicrm_smartsms where smartsmsid = '".$focus->id."' ";
$data = $myLibrary_mysqli->select($pquery);

$setup_sms=$data[0]["setup_sms"];
$send_sms=$data[0]["send_sms"];
$sms_status=$data[0]["sms_status"];

$smartsmsid=$focus->id;

$resend = 0;

if($setup_sms == 1 && $send_sms == 1 && $sms_status == 'Complete'){
	$pquery = "SELECT count(resend) as resend FROM tbt_sms_log_smartsmsid_".$focus->id." WHERE 1 and resend > 0 ";
	$field = $myLibrary_mysqli->select($pquery);
	$resend = $field[0]["resend"];
}

$smarty->assign("setup_sms",$setup_sms);
$smarty->assign("send_sms",$send_sms);
$smarty->assign("sms_status",$sms_status);
$smarty->assign("resend",$resend);

$pquery = " select a.mobile
from
(	
		select 
		aicrm_account.mobile as mobile
		from aicrm_smartsms_accountsrel
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_accountsrel.smartsmsid= '".$focus->id."'
		and mobile<>''
		group by mobile
		union
		select 
		aicrm_leaddetails.mobile as mobile
		from aicrm_smartsms_leadsrel
		left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_leadsrel.smartsmsid='".$focus->id."'
		and mobile<>''
		group by mobile
		union
	    select 
	    mobile
	    from aicrm_smartsms_contactsrel
	    left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
	    and aicrm_smartsms_contactsrel.smartsmsid='".$focus->id."'
	    and mobile<>''
		group by mobile 
) as a 
where LENGTH( a.mobile )= 10 or LENGTH(replace(replace(replace(a.mobile,'+66','0'),' ',''),'-','')) = 10 and left(replace(replace(replace(a.mobile,'+66','0'),' ',''),'-',''),2) in ('08','09','06') 
group by mobile";

// echo $pquery; exit;

$res = $myLibrary_mysqli->select($pquery);
$count_sms = count($res);
$smarty->assign("count_sms",$count_sms);

$smarty->display("DetailView.tpl");
?>