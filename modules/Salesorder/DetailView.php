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
require('user_privileges/user_privileges_'.$current_user->id.'.php');

global $current_user,$mod_strings,$app_strings,$currentModule,$theme,$singlepane_view;


$focus = CRMEntity::getInstance($currentModule);

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Salesorder");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Salesorder detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign('IS_ADMIN', $current_user->is_admin);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

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

$smarty->assign("SINGLE_MOD",'Salesorder');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Salesorder","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

$smarty->assign("CREATEPDF","permitted");

if(isPermitted("Salesorder","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


$smarty->assign("CONVERTMODE",'salesordertoinvoice');
$smarty->assign("MODULE", $currentModule);

$sql = "select pricetype from aicrm_salesorder where salesorderid='".$_REQUEST['record']."' ";
$price_type = $adb->pquery($sql,'');
$pricetype = $adb->query_result($price_type, 0, "pricetype");
$focus->column_fields['pricetype'] = $pricetype;

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Salesorder',$focus));
//echo "<pre>"; print_r(getDetailAssociatedProducts('Salesorder',$focus)); echo "</pre>"; exit;
$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

 $tabid = getTabid("Salesorder");
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

//Added to display the service comments information
$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));

$smarty->assign("SinglePane_View", $singlepane_view);
$smarty->assign("TODO_PERMISSION",CheckFieldPermission('parent_id','Calendar'));
$smarty->assign("EVENT_PERMISSION",CheckFieldPermission('parent_id','Events'));

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}

// Record Change Notification
$focus->markAsViewed($current_user->id);

$approveflg = false;
$levelflg = '';
$salesorder_status = $focus->column_fields["salesorder_status"];
//echo $quotation_status;
if($salesorder_status !='ชำระแล้ว' && $salesorder_status !='ไม่อนุมัติใบขาย'){
    $watermark = 1;
}else{
    $watermark = 0;
}

$smarty->assign("watermark", $watermark);

if($salesorder_status=="ขออนุมัติใบขาย"){
	$sql = "select count(id) as countid,level
			from tbt_salesorder_approve
			where crmid='".$_REQUEST['record']."'
			and appstatus = 0
			group by crmid,level
			having count(id) >0
			order by level asc "; //echo $sql; echo '<br>';
	$cf_res = $adb->pquery($sql,'');
	$countid=$adb->query_result($cf_res, 0, "countid");
	$level=$adb->query_result($cf_res, 0, "level");

	$sql2 = "select level,userid,username
			from tbt_salesorder_approve
			where crmid='".$_REQUEST['record']."'
			and appstatus = 0
			and userid = '".$_SESSION['authenticated_user_id']."' 
			and level = '".$level."'
			order by level asc "; //echo $sql2;
	$app_res = $adb->pquery($sql2,'');
	$levelflg=$adb->query_result($app_res, 0, "level");

	if($levelflg!=''){
		$approveflg = true;
	}
}

$flagassign = false;
if($salesorder_status=="อนุมัติใบขาย" ||$salesorder_status=="ไม่อนุมัติใบขาย"  ){
	$pquery = "select smcreatorid from aicrm_crmentity where crmid = '".$focus->id."' ";
	$cf_res = $adb->pquery($pquery,'');
	$smcreatorid=$adb->query_result($cf_res, 0, "smcreatorid");

	if($smcreatorid == $_SESSION['authenticated_user_id']){
		$flagassign = true;
	}
}else{
	//Assigned To User
	//print_r($current_user_groups);
	//echo $focus->column_fields['assigned_user_id'];
	if($focus->column_fields['assigned_user_id'] == $_SESSION['authenticated_user_id']){
		$flagassign = true;
		//echo 'user';
	}else{
	   foreach($current_user_groups as $key => $val){
	   	
		if($focus->column_fields['assigned_user_id'] == $val){
			$flagassign = true;
		}
	   }
	
	}
}

$flagaccount = false;
if($salesorder_status=="อนุมัติใบขาย" && $current_user->approve_payment == 1){
	$flagaccount = true;
}
//$taxflag = $focus->column_fields["quota_vat_show"];

$result = $adb->pquery("SELECT share_roleid FROM aicrm_datashare_role2role WHERE to_roleid=?", array($current_user->roleid));
$users = array();
for($k=0;$k < $adb->num_rows($result);$k++)
{
    $roleid = $adb->query_result($result,$k);
    $rs1 = $adb->pquery("SELECT aicrm_user2role.userid 
                                FROM aicrm_user2role 
                                LEFT JOIN aicrm_role ON aicrm_role.roleid=aicrm_user2role.roleid 
                                WHERE aicrm_role.parentrole LIKE '%".$roleid."%'
                                GROUP BY aicrm_user2role.userid");
    $count = $adb->num_rows($rs1);
    for($j=0;$j < $count;$j++)
    {
        $users[] = $adb->query_result($rs1,$j);
    }
}

$result = $adb->pquery("SELECT smownerid FROM aicrm_crmentity WHERE crmid=?", array($focus->id));
$smownerid = $adb->query_result($result,0);

if(in_array($smownerid, $users)){
    $is_permmited = true;
}else{
    $is_permmited = true;
}

$smarty->assign("is_permmited", $is_permmited);
$smarty->assign("salesorder_status", $salesorder_status);
$smarty->assign("approveflg",$approveflg);
$smarty->assign("flagassign",$flagassign);
$smarty->assign("flagaccount",$flagaccount);
$smarty->assign("levelflg",$levelflg);
$smarty->assign("taxflag",$taxflag);
$smarty->assign("salesorder_format",$focus->column_fields["quota_form"]);

// END
global  $salesorderid_id,$report_viewer_url;
$salesorderid_id= $focus->id;
$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', false));
$smarty->assign("reporturl", $report_viewer_url);
//echo $report_viewer_url;
$smarty->display("Salesorder/InventoryDetailView.tpl");

?>