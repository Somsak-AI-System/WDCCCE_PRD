<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('Smarty_setup.php');
require_once('include/utils/utils.php');
require_once 'modules/Vtiger/EditView.php';
global $app_strings,$mod_strings,$theme,$currentModule;

$focus = CRMEntity::getInstance($currentModule);
$smarty = new vtigerCRM_Smarty();
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);

if(isset($_REQUEST['record']) && $_REQUEST['record'] !='') 
{
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit'; 	
	$focus->retrieve_entity_info($_REQUEST['record'],"HelpDesk");
	$focus->name=$focus->column_fields['ticket_title'];

	global $current_user;	
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$old_id = $_REQUEST['record'];
	if (! empty($focus->filename) )
	{	
		$old_id = $focus->id;
	}
	$focus->id = "";
    	$focus->mode = ''; 	
	$pquery = "select user_name from aicrm_users where id = '".$_SESSION["authenticated_user_id"]."' ";
	$cf_res = $adb->pquery($pquery,'');
	$user_name=$adb->query_result($cf_res, 0, "user_name");
	$focus->column_fields['assigned_user_id']=$user_name;
	$focus->column_fields['date_of_execution']='';
	$focus->column_fields['execution_time']='';
	$focus->column_fields['date_completed']='';
	$focus->column_fields['time_completed']='';
	$focus->column_fields['date_incomplete']='';
	$focus->column_fields['time_incomplete']='';

	$focus->column_fields['date_cancelled']='';
	$focus->column_fields['time_cancelled']='';
	$focus->column_fields['closed_date']='';
	$focus->column_fields['closed_time']='';

	$focus->column_fields['case_status'] = 'เปิด';
}

if(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	
	$focus->column_fields['case_open_date'] = date('Y-m-d');
	$focus->column_fields['open_time'] = date('H:i');

	$focus->column_fields['case_date'] = date('Y-m-d');
	$focus->column_fields['case_time'] = date('H:i');
	$focus->column_fields['approved_status'] = '-- None --';
	$focus->column_fields['case_status'] = 'เปิด';
	setObjectValuesFromRequest($focus);
	$pquery = "select * 
	from aicrm_contactdetails 
	where contactid = '".$_REQUEST["return_id"]."' ";
	$cf_res = $adb->pquery($pquery,'');
	$phone=$adb->query_result($cf_res, 0, "phone");
	$email=$adb->query_result($cf_res, 0, "email");
	$mobile=$adb->query_result($cf_res, 0, "mobile");
	$focus->column_fields['phone']= $phone;
	$focus->column_fields['email']= $email;
	$focus->column_fields['mobile']= $mobile;
}

if(isset($_REQUEST['account_id']) && $_REQUEST['account_id'] != ''){
	require_once('modules/Accounts/Accounts.php');
    $acct_focus = new Accounts();
    $acct_focus->retrieve_entity_info($_REQUEST['account_id'], "Accounts");
    setObjectValuesFromRequest($focus);

	$focus->column_fields['nname'] = $acct_focus->column_fields['nickname'];
	$focus->column_fields['tel'] = $acct_focus->column_fields['mobile'];
	$focus->column_fields['email'] = $acct_focus->column_fields['email1'];

	$focus->column_fields['address'] = $acct_focus->column_fields['address1'];
	$focus->column_fields['subdistrict'] = $acct_focus->column_fields['subdistrict'];
	$focus->column_fields['district'] = $acct_focus->column_fields['district'];
	$focus->column_fields['province'] = $acct_focus->column_fields['province'];
	$focus->column_fields['postalcode'] = $acct_focus->column_fields['postalcode'];
	$focus->column_fields['erpaccountid'] = $acct_focus->column_fields['erpaccountid'];
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$disp_view = getView($focus->mode);
//echo $disp_view;
if($disp_view == 'edit_view'){
	$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields));
}else	
{
	$smarty->assign("BASBLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'BAS'));
}

$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Case');


$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);

if (isset($focus->name)) 
$smarty->assign("NAME", $focus->name);
else 
$smarty->assign("NAME", "");

if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}
$smarty->assign("ID", $focus->id);
$smarty->assign("OLD_ID", $old_id );
if($focus->mode == 'edit')
{
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
        $smarty->assign("MODE", $focus->mode);
        $smarty->assign("OLDSMOWNERID", $focus->column_fields['assigned_user_id']);
}

if(isset($_REQUEST['return_module'])) 
$smarty->assign("RETURN_MODULE", vtlib_purify($_REQUEST['return_module']));
if(isset($_REQUEST['return_action'])) 
$smarty->assign("RETURN_ACTION", vtlib_purify($_REQUEST['return_action']));
if(isset($_REQUEST['return_id'])) 
$smarty->assign("RETURN_ID", vtlib_purify($_REQUEST['return_id']));
if(isset($_REQUEST['product_id'])) 
$smarty->assign("PRODUCTID", vtlib_purify($_REQUEST['product_id']));
if (isset($_REQUEST['return_viewname'])) 
$smarty->assign("RETURN_VIEWNAME", vtlib_purify($_REQUEST['return_viewname']));

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$tabid = getTabid("HelpDesk");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$validationDataUitype = getDBValidationDataUitype($focus->tab_name,$tabid);
$dataUitype = split_validationdataUitype($validationDataUitype);

$smarty->assign("VALIDATION_DATA_UITYPE",$dataUitype['datauitype']);
$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

if($_REQUEST['record'] != '')
{
	//Added to display the ticket comments information
	$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));
}
$smarty->assign("DUPLICATE",vtlib_purify($_REQUEST['isDuplicate']));

global $adb;
// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if($focus->mode != 'edit' && $mod_seq_field != null) {
		$autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
		$mod_seq_string = $adb->pquery("SELECT prefix, cur_id from aicrm_modentity_num where semodule = ? and active=1",array($currentModule));
        $mod_seq_prefix = $adb->query_result($mod_seq_string,0,'prefix');
        $mod_seq_no = $adb->query_result($mod_seq_string,0,'cur_id');
        if($adb->num_rows($mod_seq_string) == 0 || $adb->num_rows($mod_seq_string) == 0 || $focus->checkModuleSeqNumber($focus->table_name, $mod_seq_field['column'], $mod_seq_prefix.$mod_seq_no))
                echo '<br><font color="#FF0000"><b>'. getTranslatedString('LBL_DUPLICATE'). ' '. getTranslatedString($mod_seq_field['label'])
                	.' - '. getTranslatedString('LBL_CLICK') .' <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings&selmodule='.$currentModule.'">'.getTranslatedString('LBL_HERE').'</a> '
                	. getTranslatedString('LBL_TO_CONFIGURE'). ' '. getTranslatedString($mod_seq_field['label']) .'</b></font>';
        else
                $smarty->assign("MOD_SEQ_ID",$autostr);
} else {
	$smarty->assign("MOD_SEQ_ID", $focus->column_fields[$mod_seq_field['name']]);
}
// END

// Gather the help information associated with fields
$smarty->assign('FIELDHELPINFO', vtlib_getFieldHelpInfo($currentModule));
// END
global  $ticket_id,$chkfield,$chk_close;
$chkfield=0;
$ticket_id= $focus->id;
$pquery = "select aicrm_troubletickets.case_status , aicrm_crmentity.smcreatorid from aicrm_troubletickets 
inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_troubletickets.ticketid 
where aicrm_troubletickets.ticketid = '".$focus->id."' ";
$cf_res = $adb->pquery($pquery,'');
$chk_close=$adb->query_result($cf_res, 0, "case_status");
$smcreatorid=$adb->query_result($cf_res, 0, "smcreatorid");

if($smcreatorid==""){
	$smcreatorid=$_SESSION["user_id"];
}
if($smcreatorid!=$_SESSION["user_id"]){
	$chkfield=1;
}
$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($currentModule);
$smarty->assign("PICKIST_DEPENDENCY_DATASOURCE", Zend_Json::encode($picklistDependencyDatasource));
if($focus->mode == 'edit')
	$smarty->display("salesEditView.tpl");
else
	$smarty->display("CreateView.tpl");
