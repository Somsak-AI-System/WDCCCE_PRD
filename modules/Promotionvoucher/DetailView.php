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
    $focus->retrieve_entity_info($_REQUEST['record'],"Promotionvoucher");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Promotionvoucher detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
 
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

$recordID = $focus->column_fields['record_id'];
/*$sql_5381 = "SELECT * FROM aicrm_voucher INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid WHERE aicrm_voucher.promotionvoucherid = $recordID AND aicrm_crmentity.deleted = 0";
$query_5381 = $adb->pquery($sql_5381, []);
$count_5381 = $adb->num_rows($query_5381);

$sql_temp = "SELECT SUM(voucher_amount) AS voucher_amount FROM tbt_promotionvoucher_voucher WHERE promotionvoucherid = $recordID AND autogen_status = 'Pending'";
$query_temp = $adb->query($sql_temp);
$tempPending = $adb->query_result($query_temp,'0','voucher_amount');

$sql_5383 = "SELECT * FROM aicrm_voucher INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid WHERE aicrm_voucher.promotionvoucherid = $recordID AND aicrm_voucher.voucher_status = 'สร้าง' AND aicrm_crmentity.deleted = 0";
$query_5383 = $adb->pquery($sql_5383, []);
$count_5383 = $adb->num_rows($query_5383);

$focus->column_fields['cf_5381'] = $count_5381 + $tempPending; // voucher ทั้งหมด
$focus->column_fields['cf_5383'] = $count_5383; // voucher ที่เหลือใช้ได้*/

$smarty->assign('USERID', $current_user->id);
$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));
$smarty->assign("UPDATEINFO",updateInfo($focus->id));

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

$smarty->assign("SINGLE_MOD",'Promotionvoucher');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Promotionvoucher","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

	
$smarty->assign("CREATEPDF","permitted");

if(isPermitted("SalesOrder","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTSALESORDER","permitted");

if(isPermitted("Invoice","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTINVOICE","permitted");

if(isPermitted("Promotionvoucher","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Promotionvoucher',$focus));

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$tabid = getTabid("Promotionvoucher");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

//Added to display the service comments information
$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));

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
$smarty->display("DetailView.tpl");
?>