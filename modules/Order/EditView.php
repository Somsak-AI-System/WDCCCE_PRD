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

global $app_strings,$mod_strings,$log,$theme,$currentModule,$current_user;

$log->debug("Inside Order EditView");

$focus = CRMEntity::getInstance($currentModule);

$smarty = new vtigerCRM_Smarty;
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends

$currencyid=fetchCurrency($current_user->id);
$rate_symbol = getCurrencySymbolandCRate($currencyid);
$rate = $rate_symbol['rate'];

if(isset($_REQUEST['record']) && $_REQUEST['record'] != ''){
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit';
    $log->debug("Mode is Edit. Orderid is ".$focus->id);
    $focus->retrieve_entity_info($_REQUEST['record'],"Order");
	$focus->name = $focus->column_fields['order_no'];

	if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
	else $smarty->assign("NAME", "");
}

if($focus->mode == 'edit' || $_REQUEST['isDuplicate'] == 'true' || $_REQUEST['isRevise'] == 'true') {
	$inventory_cur_info = getInventoryCurrencyInfo('Order', $focus->id);
	$smarty->assign("INV_CURRENCY_ID", $inventory_cur_info['currency_id']);
	$sql = "select aicrm_order.pricetype from aicrm_order where aicrm_order.orderid='".$focus->id."' ";
	$price_type = $adb->pquery($sql, array());
	$pricetype = $adb->query_result($price_type,0,'pricetype');
	$smarty->assign("pricetype", $pricetype);
} else {
	$smarty->assign("INV_CURRENCY_ID", $currencyid);
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$smarty->assign("DUPLICATE_FROM", $focus->id);
    $QUOTE_associated_prod = getAssociatedProductsOrder("Order",$focus);
	$log->debug("Mode is Duplicate. Orderid to be duplicated is ".$focus->id);
	$focus->column_fields['quotation_status']="เปิดใบเสนอราคา";
	$focus->column_fields['quotation_date']=date('Y-m-d');
	$focus->column_fields['assigned_user_id'] = $_SESSION['authenticated_user_id'];
	$focus->column_fields['order_status_order'] = "Open";

	$focus->id = "";
    $focus->mode = '';
}

if(isset($_REQUEST['isRevise']) && $_REQUEST['isRevise'] == 'true') {
	$smarty->assign("REVISE_FROM", $focus->id);
	$QUOTE_associated_prod = getAssociatedProductsOrder("Order",$focus);
	$log->debug("Mode is Revise. Orderid to be revised is ".$focus->id);
	$sql = "
	select 
	aicrm_order.order_no,
	aicrm_order.rev_no,
	aicrm_order.order_no_rev		
	from aicrm_order
	left join aicrm_ordercf on  aicrm_order.orderid=aicrm_ordercf.orderid
	left join aicrm_crmentity c  on aicrm_order.orderid  = c.crmid 
	where 1 
	and c.deleted <> 1 
	and aicrm_order.orderid='".$focus->id."'
	group by aicrm_order.orderid
	";

	$res=$adb->pquery($sql, array());
	$data_order_no = $adb->query_result($res,0,'order_no');
	$data_rev_no = $adb->query_result($res,0,'rev_no');
	$data_order_no_rev = $adb->query_result($res,0,'order_no_rev');


	if($data_rev_no=="" || $data_rev_no=="0"){
		$data_rev="1";
	}else{
		$data_rev=$data_rev_no+1;
	}

    $focus->column_fields['rev_no'] = $data_rev;
    $focus->column_fields['order_no_rev'] = $data_order_no;
    $focus->column_fields['order_status_order'] = "Open";

    $focus->id = "";
    $focus->mode = '';

}
if(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
	//$focus->column_fields['quotation_date']=date('Y-m-d');
	//$focus->column_fields['quotation_status']='เปิดใบเสนอราคา';
	$focus->column_fields['order_name'] = "-";
	$focus->column_fields['order_status_order'] = "Open";
}

if(isset($_REQUEST['product_id']) && $_REQUEST['product_id'] !=''){
    $focus->column_fields['product_id'] = $_REQUEST['product_id'];
    $log->debug("Product Id from the request is ".$_REQUEST['product_id']);
    $associated_prod = getAssociatedProductsOrder("Products",$focus,$focus->column_fields['product_id']);
	for ($i=1; $i<=count($associated_prod);$i++) {
		$associated_prod_id = $associated_prod[$i]['hdnProductId'.$i];
		$associated_prod_prices = getPricesForProducts($currencyid,array($associated_prod_id),'Products');
		$associated_prod[$i]['listPrice'.$i] = $associated_prod_prices[$associated_prod_id];
	}
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("AVAILABLE_PRODUCTS", 'true');
}

if(isset($_REQUEST['contact_id']) && $_REQUEST['contact_id']){
	require_once('modules/Contacts/Contacts.php');
	$cont_focus = new Contacts();
	$cont_focus->retrieve_entity_info($_REQUEST['contact_id'],"Contacts");
}

if(!empty($_REQUEST['parent_id']) && !empty($_REQUEST['return_module'])){
    if ($_REQUEST['return_module'] == 'Services') {
	    $focus->column_fields['product_id'] = $_REQUEST['parent_id'];
	    $log->debug("Service Id from the request is ".$_REQUEST['parent_id']);
	    $associated_prod = getAssociatedProductsOrder("Services",$focus,$focus->column_fields['product_id']);
		for ($i=1; $i<=count($associated_prod);$i++) {
			$associated_prod_id = $associated_prod[$i]['hdnProductId'.$i];
			$associated_prod_prices = getPricesForProducts($currencyid,array($associated_prod_id),'Services');
			$associated_prod[$i]['listPrice'.$i] = $associated_prod_prices[$associated_prod_id];
		}
	   	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');
    }
}

$quotation_status = $focus->column_fields["quotation_status"];
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$disp_view = getView($focus->mode);
$mode = $focus->mode;
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields));
else
{
	$bas_block = getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'BAS');
	$adv_block = getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'ADV');
	$blocks['basicTab'] = $bas_block;
	if(is_array($adv_block ))
		$blocks['moreTab'] = $adv_block;

	$smarty->assign("BLOCKS",$blocks);
	$smarty->assign("BLOCKS_COUNT",count($blocks));
}
$smarty->assign("OP_MODE",$disp_view);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Order');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$log->info("Order view");
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");
if(isset($cust_fld))
{
	$log->debug("Custom Field is present");
    $smarty->assign("CUSTOMFIELD", $cust_fld);
}

if($focus->mode == 'edit')
{
    $smarty->assign("quotation_status", $quotation_status);
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
	$associated_prod = getAssociatedProductsOrder("Order",$focus);//getProductDetailsBlockInfo('edit','Order',$focus);
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("MODE", $focus->mode);
}
elseif(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$smarty->assign("ASSOCIATEDPRODUCTS", $QUOTE_associated_prod);
	$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	$smarty->assign("MODE", $focus->mode);
}

elseif(isset($_REQUEST['isRevise']) && $_REQUEST['isRevise'] == 'true') {
	$smarty->assign("ASSOCIATEDPRODUCTS", $QUOTE_associated_prod);
	$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	$smarty->assign("MODE", $focus->mode);
	$smarty->assign("isRevise", 'true');
	$smarty->assign('assigned_user_id', $current_user->id);
	$smarty->assign('quotation_date', date('d-m-Y'));
}

elseif((isset($_REQUEST['potential_id']) && $_REQUEST['potential_id'] != '') || (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '')) {
        $smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
        $smarty->assign("MODE", $focus->mode);

	//this is to display the Product Details in first row when we create new PO from Product relatedlist
	if($_REQUEST['return_module'] == 'Products')
	{
		$smarty->assign("PRODUCT_ID",vtlib_purify($_REQUEST['product_id']));
		$smarty->assign("PRODUCT_NAME",getProductName($_REQUEST['product_id']));
		$smarty->assign("UNIT_PRICE",vtlib_purify($_REQUEST['product_id']));
		$smarty->assign("QTY_IN_STOCK",getPrdQtyInStck($_REQUEST['product_id']));
		$smarty->assign("VAT_TAX",getProductTaxPercentage("VAT",$_REQUEST['product_id']));
		$smarty->assign("SALES_TAX",getProductTaxPercentage("Sales",$_REQUEST['product_id']));
		$smarty->assign("SERVICE_TAX",getProductTaxPercentage("Service",$_REQUEST['product_id']));
	}
}
else
{
	$smarty->assign("ROWCOUNT", '1');
}

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", vtlib_purify($_REQUEST['return_module']));
else $smarty->assign("RETURN_MODULE","Order");
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", vtlib_purify($_REQUEST['return_action']));
else $smarty->assign("RETURN_ACTION","index");
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", vtlib_purify($_REQUEST['return_id']));
if(isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", vtlib_purify($_REQUEST['return_viewname']));
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);

$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

//in create new Order, get all available product taxes and shipping & Handling taxes

if($focus->mode != 'edit')
{
	$tax_details = getAllTaxes('available');
	$sh_tax_details = getAllTaxes('available','sh');
}
else
{
	$tax_details = getAllTaxes('available','',$focus->mode,$focus->id);
    $sh_tax_details = getAllTaxes('available','sh','edit',$focus->id);
}

$smarty->assign("GROUP_TAXES",$tax_details);
$smarty->assign("SH_TAXES",$sh_tax_details);

$tabid = getTabid("Order");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$validationDataUitype = getDBValidationDataUitype($focus->tab_name,$tabid);
$dataUitype = split_validationdataUitype($validationDataUitype);

$smarty->assign("VALIDATION_DATA_UITYPE",$dataUitype['datauitype']);
$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$smarty->assign("MODULE", $module);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("DUPLICATE",vtlib_purify($_REQUEST['isDuplicate']));

$smarty->assign("REVISE",vtlib_purify($_REQUEST['isRevise']));

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

$smarty->assign("CURRENCIES_LIST", getAllCurrencies());

if($focus->mode == 'edit')
	$smarty->display("Order/InventoryEditView.tpl");
else
	$smarty->display('Order/InventoryCreateView.tpl');

?>