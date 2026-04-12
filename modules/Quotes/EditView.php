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

$log->debug("Inside Quote EditView");

$focus = CRMEntity::getInstance($currentModule);

$smarty = new vtigerCRM_Smarty;
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends

//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;

$currencyid=fetchCurrency($current_user->id);
$rate_symbol = getCurrencySymbolandCRate($currencyid);
$rate = $rate_symbol['rate'];

if(isset($_REQUEST['record']) && $_REQUEST['record'] != ''){
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit';
    $log->debug("Mode is Edit. Quoteid is ".$focus->id);
    $focus->retrieve_entity_info($_REQUEST['record'],"Quotes");
	$focus->name = $focus->column_fields['quote_no'];
	if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
	else $smarty->assign("NAME", "");
}

if($focus->mode == 'edit' || $_REQUEST['isDuplicate'] == 'true' || $_REQUEST['isRevise'] == 'true') {
	$inventory_cur_info = getInventoryCurrencyInfo('Quotes', $focus->id);
	$smarty->assign("INV_CURRENCY_ID", $inventory_cur_info['currency_id']);
	$sql = "select aicrm_quotes.pricetype from aicrm_quotes where aicrm_quotes.quoteid='".$focus->id."' ";
	$price_type = $adb->pquery($sql, array());
	$pricetype = $adb->query_result($price_type,0,'pricetype');
	$smarty->assign("pricetype", $pricetype);
} else {
	$smarty->assign("INV_CURRENCY_ID", $currencyid);
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$smarty->assign("DUPLICATE_FROM", $focus->id);
    $QUOTE_associated_prod = getAssociatedProducts("Quotes",$focus);
	$log->debug("Mode is Duplicate. Quoteid to be duplicated is ".$focus->id);
	$focus->column_fields['quotation_status']="เปิดใบเสนอราคา";
	$focus->column_fields['quotation_date']=date('Y-m-d');

	$focus->column_fields['quotation_enddate']=date("Y-m-d", strtotime("+30 days"));
	$focus->column_fields['assigned_user_id'] = $_SESSION['authenticated_user_id'];
	$focus->column_fields['quota_cancel'] = '';
	$focus->column_fields['quota_notapprove'] = '';
	$focus->column_fields['quote_no_rev'] = '';
	$focus->column_fields['rev_no'] = '';
	$focus->column_fields['sono']= '';
	$focus->column_fields['flag_erp_response_status']= '0';
	$focus->column_fields['flag_projects']= '0';
	$focus->column_fields['erp_response_status']= '';
	$focus->column_fields['reference_id']= '';
	$focus->column_fields['projects_reference_id']= '';
	$focus->id = "";
    $focus->mode = '';
	$focus->column_fields['quotation_type']='กระเบื้อง';
}

if(isset($_REQUEST['isRevise']) && $_REQUEST['isRevise'] == 'true') {
	$smarty->assign("REVISE_FROM", $focus->id);
	$QUOTE_associated_prod = getAssociatedProducts("Quotes",$focus);
	$log->debug("Mode is Revise. Quoteid to be revised is ".$focus->id);
	$sql = "
	select 
	aicrm_quotes.quote_no,
	aicrm_quotes.rev_no,
	aicrm_quotes.quote_no_rev		
	from aicrm_quotes
	left join aicrm_quotescf on  aicrm_quotes.quoteid=aicrm_quotescf.quoteid
	left join aicrm_crmentity c  on aicrm_quotes.quoteid  = c.crmid 
	where 1 
	and c.deleted <> 1 
	and aicrm_quotes.quoteid='".$focus->id."'
	group by aicrm_quotes.quoteid
	";

	$res = $adb->pquery($sql, array());
	$data_quote_no = $adb->query_result($res,0,'quote_no');
	$data_rev_no = $adb->query_result($res,0,'rev_no');
	$data_quote_no_rev = $adb->query_result($res,0,'quote_no_rev');

	if($data_rev_no=="" || $data_rev_no=="0"){
		$data_rev="1";
	}else{
		$data_rev=$data_rev_no+1;
	}

    $focus->column_fields['rev_no'] = $data_rev;
    $focus->column_fields['quote_no_rev'] = $data_quote_no;
    $focus->column_fields['quotation_status'] = "เปิดใบเสนอราคา";
    $focus->column_fields['quotation_date']=date('Y-m-d');
	$focus->column_fields['quotation_enddate']=date("Y-m-d", strtotime("+30 days"));
	$focus->column_fields['quota_cancel'] = '';
	$focus->column_fields['quota_notapprove'] = '';
    $focus->id = "";
    $focus->mode = '';
	$focus->column_fields['quotation_type']='กระเบื้อง';
}
if(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
	$focus->column_fields['quotation_date']=date('Y-m-d');
	$focus->column_fields['quotation_enddate']=date("Y-m-d", strtotime("+30 days"));
	$focus->column_fields['quotation_status']='เปิดใบเสนอราคา';
	$focus->column_fields['discount']='0';
	$focus->column_fields['deposit']='0';
	$focus->column_fields['quotation_type']='กระเบื้อง';
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
		
	$focus->column_fields['dealid']=$_REQUEST['return_id'];
	$focus->column_fields['deal_no']=$data[0]['deal_no'];
	$focus->column_fields['account_id']=$data[0]['accountid'];
	$focus->column_fields['account_name']=$data[0]['accountname'];
	$focus->column_fields['mobile']=$data[0]['mobile'];
	$focus->column_fields['taxid_no']=$data[0]['idcardno'];
	//$focus->column_fields['account_name']=$data[0]['accountname'];

	$focus->column_fields['parentid']=$data[0]['accountid'];
	$focus->column_fields['parent_name']=$data[0]['accountname'];
	$focus->column_fields['customer_name']=$data[0]['accountname'];

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

	//Billing Information
	/*$focus->column_fields['quotes_bill_name']=$data[0]['accountname'];// ออกบิลในนาม (ใบเสร็จ)
	$focus->column_fields['quotes_village']=$data[0]['billingvillage'];// อาคาร/หมู่บ้าน (ใบเสร็จ) billingvillage
	$focus->column_fields['quotes_room_no']=$data[0]['billingaddressline'];// ห้องเลขที่/ชั้นที่ (ใบเสร็จ) billingaddressline
	$focus->column_fields['quotes_address']=$data[0]['billingaddressline1'];// เลขที่ (ใบเสร็จ) billingaddressline1
	$focus->column_fields['quotes_moo']=$data[0]['billingvillageno'];// หมู่ที่ (ใบเสร็จ) billingvillageno
	$focus->column_fields['quotest_road']=$data[0]['billingstreet'];// ถนน (ใบเสร็จ) billingstreet
	$focus->column_fields['quotest_alley']=$data[0]['billinglane'];// ตรอก/ซอย (ใบเสร็จ) billinglane
	$focus->column_fields['quotes_subdistrict']=$data[0]['billingsubdistrict'];// ตำบล/แขวง (ใบเสร็จ) billingsubdistrict
	$focus->column_fields['quotes_district']=$data[0]['billingdistrict'];// อำเภอ/เขต (ใบเสร็จ) billingdistrict
	$focus->column_fields['quotes_province']=$data[0]['billingprovince'];// จังหวัด (ใบเสร็จ) billingprovince
	$focus->column_fields['quotes_postcode']=$data[0]['billingpostalcode'];// รหัสไปรษณีย์ (ใบเสร็จ) billingpostalcode*/

	//Shipping Address
	/*$focus->column_fields['bill_name_shipping']=$data[0]['accountname'];// ออกบิลในนาม(จัดส่ง)
	$focus->column_fields['village_shipping']=$data[0]['shippingvillage'];// อาคาร/หมู่บ้าน(จัดส่ง) shippingvillage
	$focus->column_fields['room_no_shipping']=$data[0]['shippingaddressline'];// ห้องเลขที่/ชั้นที่(จัดส่ง) shippingaddressline
	$focus->column_fields['address_shipping']=$data[0]['shippingaddressline1'];// เลขที่(จัดส่ง) shippingaddressline1
	$focus->column_fields['moo_shipping']=$data[0]['shippingvillageno'];// หมู่ที่(จัดส่ง) shippingvillageno
	$focus->column_fields['road_shipping']=$data[0]['shippingstreet'];// ถนน(จัดส่ง) shippingstreet
	$focus->column_fields['alley_shipping']=$data[0]['shippinglane'];// ตรอก/ซอย(จัดส่ง) shippinglane
	$focus->column_fields['subdistrict_shipping']=$data[0]['shippingsubdistrict'];// ตำบล/แขวง(จัดส่ง) shippingsubdistrict
	$focus->column_fields['district_shipping']=$data[0]['shippingdistrict'];// อำเภอ/เขต(จัดส่ง) shippingdistrict
	$focus->column_fields['province_shipping']=$data[0]['shippingprovince'];// จังหวัด(จัดส่ง) shippingprovince
	$focus->column_fields['postcode_shipping']=$data[0]['shippingpostalcode'];// รหัสไปรษณีย์(จัดส่ง) shippingpostalcode*/

	if ($_REQUEST['return_module'] == 'Deal') {

	    //$focus->column_fields['return_id'] = $_REQUEST['return_id'];
	    $log->debug("Service Id from the request is ".$_REQUEST['parent_id']);
	    $associated_prod = getAssociatedProducts_deal("Products",$_REQUEST['return_id']);
	    //echo "<pre>"; print_r($associated_prod); echo "</pre>"; exit;

		for ($i=1; $i<=count($associated_prod);$i++) {
			$associated_prod_id = $associated_prod[$i]['hdnProductId'.$i];
			$associated_prod_prices = getPricesForProducts($currencyid,array($associated_prod_id),'Products');
		}
		//echo "<pre>"; print_r($associated_prod); echo "</pre>"; exit;
	   	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');
    }

}
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Projects' && $_REQUEST['return_id'] != '' ){
    require_once('modules/Projects/Projects.php');
    $pro_focus = new Projects();
    $pro_focus->retrieve_entity_info($_REQUEST['return_id'], "Projects");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['event_id'] = $_REQUEST['return_id'];
    $focus->column_fields['account_id'] = $pro_focus->column_fields['account_id'];
    $focus->column_fields['contact_id'] = $pro_focus->column_fields['contact_id'];
}else if(isset($_REQUEST['related_module']) && $_REQUEST['related_module'] == 'Projects' && $_REQUEST['related_id'] != '' ){
    require_once('modules/Projects/Projects.php');
    $pro_focus = new Projects();
    $pro_focus->retrieve_entity_info($_REQUEST['related_id'], "Projects");
    setObjectValuesFromRequest($focus);
	$focus->column_fields['projectsid'] = $_REQUEST['related_id'];
    $focus->column_fields['event_id'] = $_REQUEST['related_id'];
    $focus->column_fields['account_id'] = $pro_focus->column_fields['account_id'];
    $focus->column_fields['contact_id'] = $pro_focus->column_fields['contact_id'];
    $focus->column_fields['project_name'] = $pro_focus->column_fields['projects_name'];
    $focus->column_fields['project_status'] = $pro_focus->column_fields['projectorder_status'];
    $focus->column_fields['project_est_s_date'] = $pro_focus->column_fields['project_s_date'];
    $focus->column_fields['project_est_e_date'] = $pro_focus->column_fields['project_estimate_e_date'];

	if($pro_focus->column_fields['account_id'] != ''){
		require_once('modules/Accounts/Accounts.php');
		$acct_focus = new Accounts();
		$acct_focus->retrieve_entity_info($pro_focus->column_fields['account_id'],"Accounts");
		$focus->column_fields['mobile'] = $acct_focus->column_fields['mobile'];
		$focus->column_fields['email'] = $acct_focus->column_fields['email1'];
		$focus->column_fields['taxid_no'] = $acct_focus->column_fields['idcardno'];
		$focus->column_fields['village'] = $acct_focus->column_fields['village'];
		$focus->column_fields['room_no'] = $acct_focus->column_fields['addressline'];
		$focus->column_fields['address_no'] = $acct_focus->column_fields['address1'];
		$focus->column_fields['village_no'] = $acct_focus->column_fields['villageno'];
		$focus->column_fields['lane'] = $acct_focus->column_fields['lane'];
		$focus->column_fields['street'] = $acct_focus->column_fields['street'];
		$focus->column_fields['sub_district'] = $acct_focus->column_fields['subdistrict'];
		$focus->column_fields['district'] = $acct_focus->column_fields['district'];
		$focus->column_fields['province'] = $acct_focus->column_fields['province'];
		$focus->column_fields['postal_code'] = $acct_focus->column_fields['postalcode'];
		$focus->column_fields['quotation_acc_cd_no'] = $acct_focus->column_fields['account_no'].'/'.$acct_focus->column_fields['cd_no'];
		$focus->column_fields['payment_terms_type'] = $acct_focus->column_fields['payment_terms_type'];
		// $focus->column_fields['payment_terms'] = $acct_focus->column_fields['payment_terms'];
	}

    $associated_prod = getAssociatedProducts_Projects("Products",$_REQUEST['related_id']);
    $smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
    $smarty->assign("AVAILABLE_PRODUCTS", 'true');

    include("config.inc.php");
	include_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$sql = "select *
	from aicrm_inventoryowner
	where id = '".$_REQUEST['related_id']."' order by sequence_no asc limit 1";
	$d_owner = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($d_owner); echo "</pre>"; exit;
    /*Owner*/
    $focus->column_fields['contact_id1'] = @$d_owner[0]['contactid'];
    $focus->column_fields['service_level_owner_1'] = @$d_owner[0]['service_level_owner'];

    $sql = "select *
	from aicrm_inventoryconsultant
	where id = '".$_REQUEST['related_id']."' order by sequence_no asc limit 1";
	$d_consu = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($d_consu); echo "</pre>"; exit;
    /*Consultant*/
    $focus->column_fields['contact_id2'] = @$d_consu[0]['contactid'];
    $focus->column_fields['service_level_consultant_1'] = @$d_consu[0]['service_level_consultant'];
    
    $sql = "select *
	from aicrm_inventoryarchitecture
	where id = '".$_REQUEST['related_id']."' order by sequence_no asc limit 1";
	$d_arc = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($d_arc); echo "</pre>"; exit;
    /*Architecture*/
    $focus->column_fields['contact_id3'] = @$d_arc[0]['contactid'];
    $focus->column_fields['service_level_architecture_1'] = @$d_arc[0]['service_level_architecture'];

    $sql = "select *
	from aicrm_inventoryconstruction
	where id = '".$_REQUEST['related_id']."' order by sequence_no asc limit 1";
	$d_const = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($d_const); echo "</pre>"; exit;
    /*Construction*/
    $focus->column_fields['contact_id4'] = @$d_const[0]['contactid'];
    $focus->column_fields['service_level_construction_1'] = @$d_const[0]['service_level_construction'];

    $sql = "select *
	from aicrm_inventorydesigner
	where id = '".$_REQUEST['related_id']."' order by sequence_no asc limit 2";
	$d_des = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($d_des); echo "</pre>"; exit;
    /*Designer*/
    $focus->column_fields['contact_id5'] = @$d_des[0]['contactid'];
    $focus->column_fields['service_level_designer_1'] = @$d_des[0]['service_level_designer'];
    
    $focus->column_fields['contact_id6'] = @$d_des[1]['contactid'];
    $focus->column_fields['service_level_designer_2'] = @$d_des[1]['service_level_designer'];
    

    $sql = "select *
	from aicrm_inventorycontractor
	where id = '".$_REQUEST['related_id']."' order by sequence_no asc limit 2";
	$d_contr = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($d_contr); echo "</pre>"; exit;
    /*Contractor*/
    $focus->column_fields['contact_id7'] = @$d_contr[0]['contactid'];
    $focus->column_fields['service_level_contractor_1'] = @$d_contr[0]['service_level_contractor'];
    
    $focus->column_fields['contact_id8'] = @$d_contr[01]['contactid'];
    $focus->column_fields['service_level_contractor_2'] = @$d_contr[1]['service_level_contractor'];
    

    $sql = "select *
	from aicrm_inventorysubcontractor
	where id = '".$_REQUEST['related_id']."' order by sequence_no asc limit 2";
	$d_subcontr = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($d_subcontr); echo "</pre>"; exit;
    /*Sub Contractor*/
    $focus->column_fields['contact_id9'] = @$d_subcontr[0]['contactid'];
    $focus->column_fields['service_level_sub_contractor_1'] = @$d_subcontr[0]['service_level_sub_contractor'];

    $focus->column_fields['contact_id10'] = @$d_subcontr[1]['contactid'];
    $focus->column_fields['service_level_sub_contractor_2'] = @$d_subcontr[1]['service_level_sub_contractor'];
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

// Get Account address if aicrm_account is given
if(isset($_REQUEST['account_id']) && $_REQUEST['account_id']!='' && $_REQUEST['record']==''){
	require_once('modules/Accounts/Accounts.php');
	$acct_focus = new Accounts();
	$acct_focus->retrieve_entity_info($_REQUEST['account_id'],"Accounts");
	$focus->column_fields['parentid'] = $_REQUEST['account_id'];
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

	//Billing Information
	$focus->column_fields['quotes_bill_name']=$acct_focus->column_fields['accountname'];// ออกบิลในนาม (ใบเสร็จ)
	$focus->column_fields['quotes_village']=$acct_focus->column_fields['billingvillage'];// อาคาร/หมู่บ้าน (ใบเสร็จ) billingvillage
	$focus->column_fields['quotes_room_no']=$acct_focus->column_fields['billingaddressline'];// ห้องเลขที่/ชั้นที่ (ใบเสร็จ) billingaddressline
	$focus->column_fields['quotes_address']=$acct_focus->column_fields['billingaddressline1'];// เลขที่ (ใบเสร็จ) billingaddressline1
	$focus->column_fields['quotes_moo']=$acct_focus->column_fields['billingvillageno'];// หมู่ที่ (ใบเสร็จ) billingvillageno
	$focus->column_fields['quotest_road']=$acct_focus->column_fields['billingstreet'];// ถนน (ใบเสร็จ) billingstreet
	$focus->column_fields['quotest_alley']=$acct_focus->column_fields['billinglane'];// ตรอก/ซอย (ใบเสร็จ) billinglane
	$focus->column_fields['quotes_subdistrict']=$acct_focus->column_fields['billingsubdistrict'];// ตำบล/แขวง (ใบเสร็จ) billingsubdistrict
	$focus->column_fields['quotes_district']=$acct_focus->column_fields['billingdistrict'];// อำเภอ/เขต (ใบเสร็จ) billingdistrict
	$focus->column_fields['quotes_province']=$acct_focus->column_fields['billingprovince'];// จังหวัด (ใบเสร็จ) billingprovince
	$focus->column_fields['quotes_postcode']=$acct_focus->column_fields['billingpostalcode'];// รหัสไปรษณีย์ (ใบเสร็จ) billingpostalcode

	//Shipping Address
	$focus->column_fields['bill_name_shipping']=$acct_focus->column_fields['accountname'];// ออกบิลในนาม(จัดส่ง)
	$focus->column_fields['village_shipping']=$acct_focus->column_fields['shippingvillage'];// อาคาร/หมู่บ้าน(จัดส่ง) shippingvillage
	$focus->column_fields['room_no_shipping']=$acct_focus->column_fields['shippingaddressline'];// ห้องเลขที่/ชั้นที่(จัดส่ง) shippingaddressline
	$focus->column_fields['address_shipping']=$acct_focus->column_fields['shippingaddressline1'];// เลขที่(จัดส่ง) shippingaddressline1
	$focus->column_fields['moo_shipping']=$acct_focus->column_fields['shippingvillageno'];// หมู่ที่(จัดส่ง) shippingvillageno
	$focus->column_fields['road_shipping']=$acct_focus->column_fields['shippingstreet'];// ถนน(จัดส่ง) shippingstreet
	$focus->column_fields['alley_shipping']=$acct_focus->column_fields['shippinglane'];// ตรอก/ซอย(จัดส่ง) shippinglane
	$focus->column_fields['subdistrict_shipping']=$acct_focus->column_fields['shippingsubdistrict'];// ตำบล/แขวง(จัดส่ง) shippingsubdistrict
	$focus->column_fields['district_shipping']=$acct_focus->column_fields['shippingdistrict'];// อำเภอ/เขต(จัดส่ง) shippingdistrict
	$focus->column_fields['province_shipping']=$acct_focus->column_fields['shippingprovince'];// จังหวัด(จัดส่ง) shippingprovince
	$focus->column_fields['postcode_shipping']=$acct_focus->column_fields['shippingpostalcode'];// รหัสไปรษณีย์(จัดส่ง) shippingpostalcode
	$log->debug("Accountid Id from the request is ".$_REQUEST['account_id']);
}

if(isset($_REQUEST['lead_id']) && $_REQUEST['lead_id'] != ''){
	require_once('modules/Leads/Leads.php');
    $lead_focus = new Leads();
    $lead_focus->retrieve_entity_info($_REQUEST['lead_id'], "Leads");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['parentid'] = $_REQUEST['lead_id'];
    /*$focus->column_fields['salutation'] = $lead_focus->column_fields['salutationtype'];
    $focus->column_fields['firstname'] = $lead_focus->column_fields['firstname'];
    $focus->column_fields['lastname'] = $lead_focus->column_fields['lastname'];
    $focus->column_fields['idcardno'] = $lead_focus->column_fields['idcardno'];
    $focus->column_fields['birthdate'] = $lead_focus->column_fields['birthdate'];
    $focus->column_fields['gender'] = $lead_focus->column_fields['gender'];
    $focus->column_fields['mobile'] = $lead_focus->column_fields['mobile'];
    $focus->column_fields['email'] = $lead_focus->column_fields['email'];*/
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
$smarty->assign("SINGLE_MOD",'Quote');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$log->info("Quote view");
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
	$associated_prod = getAssociatedProducts("Quotes",$focus);//getProductDetailsBlockInfo('edit','Quotes',$focus);

	// echo "<pre>"; print_r($associated_prod); echo "</pre>"; exit;
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
else $smarty->assign("RETURN_MODULE","Quotes");
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", vtlib_purify($_REQUEST['return_action']));
else $smarty->assign("RETURN_ACTION","index");
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", vtlib_purify($_REQUEST['return_id']));
if(isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", vtlib_purify($_REQUEST['return_viewname']));
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);

$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

//in create new Quote, get all available product taxes and shipping & Handling taxes

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

$tabid = getTabid("Quotes");
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

if($_REQUEST['record'] != '')
{
	//Added to display the service comments information
	$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));
}

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
// $smarty->assign("a_product_own_brand", getProduct_Own_Brand());
// $smarty->assign("a_competitor_brand", getCompetitor_Brand());
// $smarty->assign("a_compet_brand_in_proj", getCompet_Brand_in_proj());

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

$smarty->assign("a_product_price_type", get_product_price_type());
$smarty->assign("a_pricelist_type", get_pricelist_type());

$smarty->assign('HIDDEN_FIELDS', $a_data_fields);
if($focus->mode == 'edit')
	$smarty->display("Inventory/InventoryEditView.tpl");
else
	$smarty->display('Inventory/InventoryCreateView.tpl');

?>