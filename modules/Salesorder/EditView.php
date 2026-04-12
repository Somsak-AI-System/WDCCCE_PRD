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
$log->debug("Inside Salesorder EditView");
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
    $log->debug("Mode is Edit. Salesorderid is ".$focus->id);
    $focus->retrieve_entity_info($_REQUEST['record'],"Salesorder");
	$focus->name = $focus->column_fields['salesorder_no'];
	if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
	else $smarty->assign("NAME", "");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true'){
	$smarty->assign("DUPLICATE_FROM", $focus->id);
    $QUOTE_associated_prod = getAssociatedProducts("Salesorder",$focus);
	$log->debug("Mode is Duplicate. Salesorderid to be duplicated is ".$focus->id);
	$focus->column_fields['assigned_user_id'] = $_SESSION['authenticated_user_id'];

	$inventory_cur_info = getInventoryCurrencyInfo('Salesorder', $focus->id);
	$smarty->assign("INV_CURRENCY_ID", $inventory_cur_info['currency_id']);
	$sql = "select aicrm_salesorder.pricetype from aicrm_salesorder where aicrm_salesorder.salesorderid='".$focus->id."' ";
	$price_type = $adb->pquery($sql, array());
	$pricetype = $adb->query_result($price_type,0,'pricetype');
	$smarty->assign("pricetype", $pricetype);

	$focus->column_fields['s_date']=date('Y-m-d');
	$focus->column_fields['e_date']=date("Y-m-d", strtotime((date("Y-m-d")) . "+7 day"));
	$focus->column_fields['salesorder_status']='เปิดใบขาย';
	
	$focus->id = "";
    $focus->mode = '';
}


if(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
	$focus->column_fields['s_date']=date('Y-m-d');
	$focus->column_fields['e_date']=date("Y-m-d", strtotime((date("Y-m-d")) . "+7 day"));
	$focus->column_fields['salesorder_status']='เปิดใบขาย';
}

if(isset($_REQUEST['product_id']) && $_REQUEST['product_id'] !=''){
    $focus->column_fields['product_id'] = $_REQUEST['product_id'];
    $log->debug("Product Id from the request is ".$_REQUEST['product_id']);
    $associated_prod = getAssociatedProducts("Products",$focus,$focus->column_fields['product_id']);
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
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] !='' && $_REQUEST['return_module']== 'Deal'){
	include("config.inc.php");
	include_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$sql = "select 
	aicrm_deal.deal_no,
	aicrm_account.accountid,
	aicrm_account.accountname,
	aicrm_account.mobile,
	aicrm_account.idcardno,
	aicrm_account.village,
	aicrm_account.addressline,
	aicrm_account.addressline1,
	aicrm_account.villageno,
	aicrm_account.street,
	aicrm_account.lane,
	aicrm_account.subdistrict,
	aicrm_account.district,
	aicrm_account.province,
	aicrm_account.postalcode,
	aicrm_account.billingvillage,
	aicrm_account.billingaddressline,
	aicrm_account.billingaddressline1,
	aicrm_account.billingvillageno,
	aicrm_account.billingstreet,
	aicrm_account.billinglane,
	aicrm_account.billingsubdistrict,
	aicrm_account.billingdistrict,
	aicrm_account.billingprovince,
	aicrm_account.billingpostalcode,
	aicrm_account.shippingvillage,
	aicrm_account.shippingaddressline,
	aicrm_account.shippingaddressline1,
	aicrm_account.shippingvillageno,
	aicrm_account.shippingstreet,
	aicrm_account.shippinglane,
	aicrm_account.shippingsubdistrict,
	aicrm_account.shippingdistrict,
	aicrm_account.shippingprovince,
	aicrm_account.shippingpostalcode
	from aicrm_deal
	inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
	inner join aicrm_account on aicrm_account.accountid = aicrm_deal.parentid
	where aicrm_crmentity.deleted = 0 and aicrm_deal.dealid = '".$_REQUEST['return_id']."' ";
	$data = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($data); echo "</pre>";
	$focus->column_fields['dealid']=$_REQUEST['return_id'];
	$focus->column_fields['deal_no']=$data[0]['deal_no'];
	$focus->column_fields['account_id']=$data[0]['accountid'];
	$focus->column_fields['account_name']=$data[0]['accountname'];
	$focus->column_fields['mobile']=$data[0]['mobile'];
	$focus->column_fields['taxid_no']=$data[0]['idcardno'];
	$focus->column_fields['account_name']=$data[0]['accountname'];

	//Address Information
	$focus->column_fields['village']=$data[0]['village'];// อาคาร/หมู่บ้าน village
	$focus->column_fields['room_no']=$data[0]['addressline'];// ห้องเลขที่/ชั้นที่ addressline
	$focus->column_fields['address_no']=$data[0]['addressline1'];// เลขที่ addressline1
	$focus->column_fields['village_no']=$data[0]['villageno'];// หมู่ที่ villageno
	$focus->column_fields['street']=$data[0]['street'];// ถนน street
	$focus->column_fields['lane']=$data[0]['lane'];// ตรอก/ซอย lane
	$focus->column_fields['sub_district']=$data[0]['subdistrict'];// ตำบล/แขวง subdistrict
	$focus->column_fields['district']=$data[0]['district'];// อำเภอ/เขต district
	$focus->column_fields['province']=$data[0]['province'];// จังหวัด province
	$focus->column_fields['postal_code']=$data[0]['postalcode'];// รหัสไปรษณีย์ postalcode

	//Shipping Address
	$focus->column_fields['village_shipping']=$data[0]['shippingvillage'];// อาคาร/หมู่บ้าน(จัดส่ง) shippingvillage
	$focus->column_fields['room_no_shipping']=$data[0]['shippingaddressline'];// ห้องเลขที่/ชั้นที่(จัดส่ง) shippingaddressline
	$focus->column_fields['address_no_shipping']=$data[0]['shippingaddressline1'];// เลขที่(จัดส่ง) shippingaddressline1
	$focus->column_fields['village_no_shipping']=$data[0]['shippingvillageno'];// หมู่ที่(จัดส่ง) shippingvillageno
	$focus->column_fields['street_shipping']=$data[0]['shippingstreet'];// ถนน(จัดส่ง) shippingstreet
	$focus->column_fields['lane_shipping']=$data[0]['shippinglane'];// ตรอก/ซอย(จัดส่ง) shippinglane
	$focus->column_fields['sub_district_shipping']=$data[0]['shippingsubdistrict'];// ตำบล/แขวง(จัดส่ง) shippingsubdistrict
	$focus->column_fields['district_shipping']=$data[0]['shippingdistrict'];// อำเภอ/เขต(จัดส่ง) shippingdistrict
	$focus->column_fields['province_shipping']=$data[0]['shippingprovince'];// จังหวัด(จัดส่ง) shippingprovince
	$focus->column_fields['postal_code_shipping']=$data[0]['shippingpostalcode'];// รหัสไปรษณีย์(จัดส่ง) shippingpostalcode
}

if(!empty($_REQUEST['parent_id']) && !empty($_REQUEST['return_module'])){

    if ($_REQUEST['return_module'] == 'Services') {
	    $focus->column_fields['product_id'] = $_REQUEST['parent_id'];
	    $log->debug("Service Id from the request is ".$_REQUEST['parent_id']);
	    $associated_prod = getAssociatedProducts("Services",$focus,$focus->column_fields['product_id']);
		for ($i=1; $i<=count($associated_prod);$i++) {
			$associated_prod_id = $associated_prod[$i]['hdnProductId'.$i];
			$associated_prod_prices = getPricesForProducts($currencyid,array($associated_prod_id),'Services');
			$associated_prod[$i]['listPrice'.$i] = $associated_prod_prices[$associated_prod_id];
		}
	   	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');

    }
}

if(isset($_REQUEST['account_id']) && $_REQUEST['account_id']!='' && $_REQUEST['record']==''){
	require_once('modules/Accounts/Accounts.php');
	$acct_focus = new Accounts();
	$acct_focus->retrieve_entity_info($_REQUEST['account_id'],"Accounts");
	$focus->column_fields['mobile']=$acct_focus->column_fields['mobile'];
	$focus->column_fields['taxid_no']=$acct_focus->column_fields['idcardno'];
	$focus->column_fields['account_name']=$acct_focus->column_fields['accountname'];

	//Address Information
	$focus->column_fields['village']=$acct_focus->column_fields['village'];// อาคาร/หมู่บ้าน village
	$focus->column_fields['room_no']=$acct_focus->column_fields['addressline'];// ห้องเลขที่/ชั้นที่ addressline
	$focus->column_fields['address_no']=$acct_focus->column_fields['addressline1'];// เลขที่ addressline1
	$focus->column_fields['village_no']=$acct_focus->column_fields['villageno'];// หมู่ที่ villageno
	$focus->column_fields['street']=$acct_focus->column_fields['street'];// ถนน street
	$focus->column_fields['lane']=$acct_focus->column_fields['lane'];// ตรอก/ซอย lane
	$focus->column_fields['sub_district']=$acct_focus->column_fields['subdistrict'];// ตำบล/แขวง subdistrict
	$focus->column_fields['district']=$acct_focus->column_fields['district'];// อำเภอ/เขต district
	$focus->column_fields['province']=$acct_focus->column_fields['province'];// จังหวัด province
	$focus->column_fields['postal_code']=$acct_focus->column_fields['postalcode'];// รหัสไปรษณีย์ postalcode

	//Shipping Address
	$focus->column_fields['village_shipping']=$acct_focus->column_fields['shippingvillage'];// อาคาร/หมู่บ้าน(จัดส่ง) shippingvillage
	$focus->column_fields['room_no_shipping']=$acct_focus->column_fields['shippingaddressline'];// ห้องเลขที่/ชั้นที่(จัดส่ง) shippingaddressline
	$focus->column_fields['address_no_shipping']=$acct_focus->column_fields['shippingaddressline1'];// เลขที่(จัดส่ง) shippingaddressline1
	$focus->column_fields['village_no_shipping']=$acct_focus->column_fields['shippingvillageno'];// หมู่ที่(จัดส่ง) shippingvillageno
	$focus->column_fields['street_shipping']=$acct_focus->column_fields['shippingstreet'];// ถนน(จัดส่ง) shippingstreet
	$focus->column_fields['lane_shipping']=$acct_focus->column_fields['shippinglane'];// ตรอก/ซอย(จัดส่ง) shippinglane
	$focus->column_fields['sub_district_shipping']=$acct_focus->column_fields['shippingsubdistrict'];// ตำบล/แขวง(จัดส่ง) shippingsubdistrict
	$focus->column_fields['district_shipping']=$acct_focus->column_fields['shippingdistrict'];// อำเภอ/เขต(จัดส่ง) shippingdistrict
	$focus->column_fields['province_shipping']=$acct_focus->column_fields['shippingprovince'];// จังหวัด(จัดส่ง) shippingprovince
	$focus->column_fields['postal_code_shipping']=$acct_focus->column_fields['shippingpostalcode'];// รหัสไปรษณีย์(จัดส่ง) shippingpostalcode
}

if(isset($_REQUEST['quotesid']) && $_REQUEST['quotesid'] !=''){

	$focus->column_fields['quoteid'] = $_REQUEST['quotesid'];
	require_once('modules/Quotes/Quotes.php');
	$quotes_focus = new Quotes();
	$quotes_focus->retrieve_entity_info($_REQUEST['quotesid'],"Quotes");
	/*$focus->column_fields['account_id'] = $quotes_focus->column_fields['account_id'];
	$focus->column_fields['contact_id'] = $quotes_focus->column_fields['contact_id'];
	$focus->column_fields['cf_4938'] = $quotes_focus->column_fields['quotes_bill_name'];
	$focus->column_fields['cf_4939'] = $quotes_focus->column_fields['address_shipping'];
	$focus->column_fields['cf_4940'] = $quotes_focus->column_fields['moo_shipping'];
	$focus->column_fields['cf_4941'] = $quotes_focus->column_fields['village_shipping'];
	$focus->column_fields['cf_4942'] = $quotes_focus->column_fields['alley_shipping'];
	$focus->column_fields['cf_4943'] = $quotes_focus->column_fields['road_shipping'];
	$focus->column_fields['cf_4944'] = $quotes_focus->column_fields['province_shipping'];
	$focus->column_fields['cf_4945'] = $quotes_focus->column_fields['district_shipping'];
	$focus->column_fields['cf_4956'] = $quotes_focus->column_fields['subdistrict_shipping'];
	$focus->column_fields['cf_4947'] = $quotes_focus->column_fields['postcode_shipping'];
	$focus->column_fields['cf_4948'] = $quotes_focus->column_fields['due'];
	$focus->column_fields['cf_4949'] = $quotes_focus->column_fields['quota_sendproduct'];
	$focus->column_fields['cf_4950'] = $quotes_focus->column_fields['terms_conditions'];
	$focus->column_fields['cf_4951'] = $quotes_focus->column_fields['quote_termcondition'];*/

	$focus->id = "";
    $focus->mode = '';
}

//$quotation_status = $focus->column_fields["quotation_status"];
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
$smarty->assign("SINGLE_MOD",'Sales Order');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$log->info("Salesorder view");
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
	$associated_prod = getAssociatedProducts("Salesorder",$focus);//getProductDetailsBlockInfo('edit','Salesorder',$focus);
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("MODE", $focus->mode);
	$sql = "select aicrm_salesorder.pricetype from aicrm_salesorder where aicrm_salesorder.salesorderid='".$focus->id."' ";
	$price_type = $adb->pquery($sql, array());
	$pricetype = $adb->query_result($price_type,0,'pricetype');
	$smarty->assign("pricetype", $pricetype);
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

if(isset($_REQUEST['quotesid']) && $_REQUEST['quotesid'] !=''){
	
	$_REQUEST['return_module'] = 'Salesorder';
	$_REQUEST['return_action'] = 'DetailView';
	$inventory_cur_info = getInventoryCurrencyInfo('Quotes', $_REQUEST['quotesid']);
	$smarty->assign("INV_CURRENCY_ID", $inventory_cur_info['currency_id']);
	$sql = "select aicrm_quotes.pricetype from aicrm_quotes where aicrm_quotes.quoteid='".$_REQUEST['quotesid']."' ";
	$price_type = $adb->pquery($sql, array());
	$pricetype = $adb->query_result($price_type,0,'pricetype');
	$smarty->assign("pricetype", $pricetype);
	$smarty->assign("UPDATEINFO",updateInfo($_REQUEST['quotesid']));
	$quotes_focus->id = $_REQUEST['quotesid'];
	$quotes_focus->retrieve_entity_info($_REQUEST['quotesid'],"Quotes");
	$QUOTE_associated_prod = getAssociatedProducts("Quotes",$quotes_focus);
	$smarty->assign("quotesid",$_REQUEST['quotesid']);
	$smarty->assign("ASSOCIATEDPRODUCTS", $QUOTE_associated_prod);
	$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	$smarty->assign("MODE", '');
}

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", vtlib_purify($_REQUEST['return_module']));
else $smarty->assign("RETURN_MODULE","Salesorder");
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", vtlib_purify($_REQUEST['return_action']));
else $smarty->assign("RETURN_ACTION","index");
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", vtlib_purify($_REQUEST['return_id']));
if(isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", vtlib_purify($_REQUEST['return_viewname']));
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);

$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

//in create new Salesorder, get all available product taxes and shipping & Handling taxes

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

$tabid = getTabid("Salesorder");
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

if($_REQUEST['record'] != '')
{
	//Added to display the service comments information
	$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));
}

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
$smarty->assign("CURRENCIES_LIST", getAllCurrencies());

if($focus->mode == 'edit'){
	$smarty->display("Salesorder/InventoryEditView.tpl");
}
else{
	$smarty->display('Salesorder/InventoryCreateView.tpl');
}

?>