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
require_once('include/utils/utils.php');require_once('modules/Salesinvoice/Salesinvoice.php');

global $app_strings,$mod_strings,$currentModule,$theme;

$focus = CRMEntity::getInstance($currentModule);
$smarty = new vtigerCRM_Smarty();
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends

if(isset($_REQUEST['record']) && $_REQUEST['record'] !='') 
{
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit'; 	
	$focus->retrieve_entity_info($_REQUEST['record'],"Salesinvoice");
	$focus->name=$focus->column_fields['salesinvoice_name'];		
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
    $focus->mode = ''; 	
}
if(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
}

/** get quote data */
if(@$_REQUEST['QuoteID'] != '') {
require_once('modules/Quotes/Quotes.php');
$focusQuotes = new Quotes();
$focusQuotes->retrieve_entity_info($_REQUEST['QuoteID'],"Quotes");
$focusQuotes->id = $_REQUEST['QuoteID'];
$focus->column_fields['quoteid'] = $_REQUEST['QuoteID'];

$focus->column_fields['invoice_date'] = date('Y-m-d');
$focus->column_fields['accountid'] = $focusQuotes->column_fields['account_id'];
$focus->column_fields['contactid'] = $focusQuotes->column_fields['contactid'];
$focus->column_fields['deposit'] = $focusQuotes->column_fields['deposit'];
$focus->column_fields['taxid_no'] = $focusQuotes->column_fields['taxid_no'];
$focus->column_fields['email'] = $focusQuotes->column_fields['email'];
$focus->column_fields['mobile'] = $focusQuotes->column_fields['mobile'];
$focus->column_fields['village'] = $focusQuotes->column_fields['village'];
$focus->column_fields['room_no'] = $focusQuotes->column_fields['room_no'];
$focus->column_fields['address_no'] = $focusQuotes->column_fields['address_no'];
$focus->column_fields['village_no'] = $focusQuotes->column_fields['village_no'];
$focus->column_fields['lane'] = $focusQuotes->column_fields['lane'];
$focus->column_fields['street'] = $focusQuotes->column_fields['street'];
$focus->column_fields['sub_district'] = $focusQuotes->column_fields['sub_district'];
$focus->column_fields['district'] = $focusQuotes->column_fields['district'];
$focus->column_fields['province'] = $focusQuotes->column_fields['province'];
$focus->column_fields['postal_code'] = $focusQuotes->column_fields['postal_code'];
$focus->column_fields['customer_id'] = $focusQuotes->column_fields['quotation_acc_cd_no'];
$focus->column_fields['billing_address'] = $focusQuotes->column_fields['billing_address'];
}
/** get quote data */

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields));
else
{
	$smarty->assign("BASBLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'BAS'));
}

$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Sales Invoice');

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
if($focus->mode == 'edit')
{
    $smarty->assign("MODE", $focus->mode);
    $smarty->assign("OLDSMOWNERID", $focus->column_fields['assigned_user_id']);
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
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

$tabid = getTabid("Salesinvoice");
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
$smarty->assign("DUPLICATE",vtlib_purify($_REQUEST['isDuplicate']));

global $adb;
// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if($focus->mode != 'edit' && $mod_seq_field != null) {
		$autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
		$mod_seq_string = $adb->pquery("SELECT prefix, cur_id from aicrm_modentity_num where semodule = ? and active=1",array($currentModule));
        $mod_seq_prefix = $adb->query_result($mod_seq_string,0,'prefix');
        $mod_seq_no = $adb->query_result($mod_seq_string,0,'cur_id');
        if($adb->num_rows($mod_seq_string) == 0 || $focus->checkModuleSeqNumber($focus->table_name, $mod_seq_field['column'], $mod_seq_prefix.$mod_seq_no))
                echo '<br><font color="#FF0000"><b>'. getTranslatedString('LBL_DUPLICATE'). ' '. getTranslatedString($mod_seq_field['label'])
                	.' - '. getTranslatedString('LBL_CLICK') .' <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings&selmodule='.$currentModule.'">'.getTranslatedString('LBL_HERE').'</a> '
                	. getTranslatedString('LBL_TO_CONFIGURE'). ' '. getTranslatedString($mod_seq_field['label']) .'</b></font>';
        else
                $smarty->assign("MOD_SEQ_ID",$autostr);
} else {
	$smarty->assign("MOD_SEQ_ID", $focus->column_fields[$mod_seq_field['name']]);
}
// END
$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($currentModule);
$smarty->assign("PICKIST_DEPENDENCY_DATASOURCE", Zend_Json::encode($picklistDependencyDatasource));

$smarty->assign("a_product_price_type", get_product_price_type());
$smarty->assign("a_pricelist_type", get_pricelist_type());

if($focus->mode == 'edit') {
	$associated_prod = getAssociatedProducts("Quotes",$focus);
} else {
	if(@$_REQUEST['QuoteID'] != '') {
	$sql = "select aicrm_quotes.pricetype, aicrm_quotes.hdn_bill_discount, aicrm_quotes.subtotal, aicrm_quotes.tax1 from aicrm_quotes where aicrm_quotes.quoteid='".$focusQuotes->id."' ";
	$price_type = $adb->pquery($sql, array());
	$pricetype = $adb->query_result($price_type,0,'pricetype');
	$subTotal = $adb->query_result($price_type,0,'subtotal');
	$subDiscount = $adb->query_result($price_type,0,'hdn_bill_discount');
	$tax1 = $adb->query_result($price_type,0,'tax1');
	$smarty->assign("pricetype", $pricetype);
	$smarty->assign("subTotal", $subTotal);
	$smarty->assign("subDiscount", $subDiscount);
	$smarty->assign("tax1", $tax1);

	$associated_prod = getAssociatedProducts("Salesinvoice",$focusQuotes);
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	}
}

if($focus->mode == 'edit')
	$smarty->display("salesEditView.tpl");
else
	$smarty->display("CreateView.tpl");

?>