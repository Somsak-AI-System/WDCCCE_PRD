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

global $current_user,$mod_strings,$app_strings,$currentModule,$theme,$singlepane_view , $report_viewer_url ,$report_viewer_url_pdf ;

$focus = CRMEntity::getInstance($currentModule);

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Quotes");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Quote detail view");

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

$smarty->assign("SINGLE_MOD",'Quote');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Quotes","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

$smarty->assign("CREATEPDF","permitted");

if(isPermitted("Quotes","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

$sql = "select pricetype from aicrm_quotes where quoteid='".$_REQUEST['record']."' ";
$price_type = $adb->pquery($sql,'');
$pricetype = $adb->query_result($price_type, 0, "pricetype");
$focus->column_fields['pricetype'] = $pricetype;

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Quotes',$focus));

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

 $tabid = getTabid("Quotes");
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
$smarty->assign('site_URL', $site_URL);
$smarty->assign('Report_URL', $report_viewer_url);
$smarty->assign('Report_PDF', $report_viewer_url_pdf);

//Added to display the service comments information
$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}

// Record Change Notification
$focus->markAsViewed($current_user->id);
//$smarty->assign("logoflg", $focus->column_fields["cf_4227"]);

$approveflg = false;
$levelflg = '';
$quotation_status = $focus->column_fields["quotation_status"];

$quotation_type = $focus->column_fields["quotation_type"];
$smarty->assign("quotation_type",$quotation_type);

if($quotation_status !='ปิดการขาย' && $quotation_status !='อนุมัติใบเสนอราคา'){
    $watermark = 1;
}else{
    $watermark = 0;
}

$smarty->assign("watermark", $watermark);

if($quotation_status=="ขออนุมัติใบเสนอราคา"){
	$sql = "select count(id) as countid,level
			from tbt_quotes_approve
			where crmid='".$_REQUEST['record']."'
			and appstatus = 0
			group by crmid,level
			having count(id) >0
			order by level asc "; //echo $sql; echo '<br>';
	$cf_res = $adb->pquery($sql,'');
	$countid=$adb->query_result($cf_res, 0, "countid");
	$level=$adb->query_result($cf_res, 0, "level");

	$sql2 = "select level,userid,username
			from tbt_quotes_approve
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
if($quotation_status=="อนุมัติใบเสนอราคา" ||$quotation_status=="ไม่อนุมัติใบเสนอราคา"  ){
	$pquery = "select smcreatorid from aicrm_crmentity where crmid = '".$focus->id."' ";
	$cf_res = $adb->pquery($pquery,'');
	$smcreatorid=$adb->query_result($cf_res, 0, "smcreatorid");

	if($smcreatorid == $_SESSION['authenticated_user_id']){
		$flagassign = true;
	/*}else{
		$sql2 = "select level,userid,username
			from tbt_quotes_approve
			where crmid='".$_REQUEST['record']."'
			and userid = '".$_SESSION['authenticated_user_id']."'
			and level = '4'
			order by level asc ";
		//echo $sql2;
		$app_res = $adb->pquery($sql2,'');
		$userid=$adb->query_result($app_res, 0, "userid");
		if($userid!=''){
			$flagassign = true;
		}*/
	}
}else{
	//Assigned To User
	//print_r($current_user_groups);
	//echo $is_admin;
	//echo $focus->column_fields['assigned_user_id'];
	if($is_admin){
		$flagassign = true;
	}elseif($focus->column_fields['assigned_user_id'] == $_SESSION['authenticated_user_id']){
		$flagassign = true;
		//echo 'user';
	}else{
	   foreach($current_user_groups as $key => $val){
		if($focus->column_fields['assigned_user_id'] == $val){
			$flagassign = true;
		}
	   }
	}

	/*elseif(array_search($focus->column_fields['assigned_user_id'], $current_user_groups)){
		echo 111;
		//Assigned To Group
    		$flagassign = true;
		//echo 'group';
	}*/
	$flagassign = true;
}

$taxflag = $focus->column_fields["quota_vat_show"];

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


//convert
$cquery = "SELECT convertflag, sono, flag_erp_response_status FROM aicrm_quotes WHERE quoteid = '".$focus->id."' ";
$result_c = $adb->pquery($cquery,'');
$convertflag =$adb->query_result($result_c, 0, "convertflag");
$sono =$adb->query_result($result_c, 0, "sono");
$flag_erp_response_status =$adb->query_result($result_c, 0, "flag_erp_response_status");
$smarty->assign("convertflag", $convertflag);
$smarty->assign("sono", $sono);
$smarty->assign("flag_erp_response_status", $flag_erp_response_status);

$smarty->assign("is_permmited", $is_permmited);
$smarty->assign("quotation_status", $quotation_status);
$smarty->assign("approveflg",$approveflg);
$smarty->assign("flagassign",$flagassign);
$smarty->assign("levelflg",$levelflg);
$smarty->assign("taxflag",$taxflag);
$smarty->assign("quotes_format",$focus->column_fields["quota_form"]);

// END
global  $quoteid_id,$report_viewer_url;
$quoteid_id= $focus->id;
$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', false));
$smarty->assign("reporturl", $report_viewer_url);
//echo $report_viewer_url;


$sql = "SELECT
aicrm_role2profile.profileid
FROM
aicrm_users
LEFT JOIN aicrm_user2role ON aicrm_users.id = aicrm_user2role.userid
LEFT JOIN aicrm_role2profile ON aicrm_user2role.roleid = aicrm_role2profile.roleid
WHERE
id = '".$current_user->id."'";
$profile_id = $adb->pquery($sql, array());
$profileid = $adb->query_result($profile_id,0,'profileid');
// echo $profileid; exit;

// echo $tabid; exit;
// $a_data_fields = array(
//     array('class' => 'product_cost_avg', 'visible' => 1),
//     array('class' => 'rrr', 'visible' => 0),
//     // Add more elements as needed
// );

$sql = "SELECT
aicrm_field.fieldname class,
aicrm_profile2field.visible
FROM
aicrm_field
INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid = aicrm_field.fieldid
INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid = aicrm_field.fieldid 
WHERE
aicrm_field.tabid = '".$tabid."'
AND aicrm_field.displaytype = 5 
AND aicrm_profile2field.profileid = '".$profileid."' 
AND aicrm_field.presence IN ( 0, 2 )";
$a_data_fields = $adb->pquery($sql, array());
$smarty->assign('HIDDEN_FIELDS', $a_data_fields);

$assigned = 0;
if($focus->column_fields['assigned_user_id'] == $_SESSION['authenticated_user_id']){
	$assigned = 1;
}
$smarty->assign('assigned', $assigned);

$smarty->display("Inventory/InventoryDetailView.tpl");

?>