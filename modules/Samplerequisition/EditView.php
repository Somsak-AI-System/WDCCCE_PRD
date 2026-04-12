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
require_once ('modules/Quotes/Quotes.php');
require_once('include/CustomFieldUtil.php');
require_once('include/utils/utils.php');

global $app_strings,$mod_strings,$log,$theme,$currentModule,$current_user;

$log->debug("Inside Samplerequisition EditView");

$focus = CRMEntity::getInstance($currentModule);
$smarty = new vtigerCRM_Smarty;
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends
//echo "<pre>"; print_r($focus->mode); echo "</pre>"; exit;
$currencyid=fetchCurrency($current_user->id);
$rate_symbol = getCurrencySymbolandCRate($currencyid);
$rate = $rate_symbol['rate'];
//==========================================================================================
if (isset ($_REQUEST['record']) && $_REQUEST['record'] != '') {
	if (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'quotetopro') {
		$quoteid = $_REQUEST['record'];
		$quote_focus = new Quotes();
		$quote_focus->id = $quoteid;
		$quote_focus->retrieve_entity_info($quoteid, "Quotes");
		$focus = getConvertQuoteToProject($focus, $quote_focus, $quoteid);
		$focus->id = $quoteid;

		// Reset the value w.r.t Quote Selected
		$currencyid = $quote_focus->column_fields['currency_id'];
		$rate = $quote_focus->column_fields['conversion_rate'];

		//Added to display the Quotes's associated aicrm_products -- when we create SO from Quotes DetailView
		$associated_prod = getAssociatedSamplerequisition("Samplerequisition", $quote_focus);
		$smarty->assign("CONVERT_MODE", $_REQUEST['convertmode']);
		$smarty->assign("QUOTE_ID", $quoteid);
		$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("MODE", $quote_focus->mode);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');

	}
	elseif (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'update_quote_val') {
		//Updating the Selected Quote Value in Edit Mode
		foreach ($focus->column_fields as $fieldname => $val) {
			if (isset ($_REQUEST[$fieldname])) {
				$value = $_REQUEST[$fieldname];
				$focus->column_fields[$fieldname] = $value;
			}
		}
		//Handling for dateformat in due_date aicrm_field
		if ($focus->column_fields['duedate'] != '') {
			$curr_due_date = $focus->column_fields['duedate'];
			$focus->column_fields['duedate'] = getDBInsertDateValue($curr_due_date);
		}

		$quoteid = $focus->column_fields['quote_id'];
		$smarty->assign("QUOTE_ID", $focus->column_fields['quote_id']);
		$quote_focus = new Quotes();
		$quote_focus->id = $quoteid;
		$quote_focus->retrieve_entity_info($quoteid, "Quotes");
		$focus = getConvertQuoteToProject($focus, $quote_focus, $quoteid);
		$focus->id = $_REQUEST['record'];
		$focus->mode = 'edit';
		$focus->name = $focus->column_fields['subject'];
		// Reset the value w.r.t Quote Selected
		$currencyid = $quote_focus->column_fields['currency_id'];
		$rate = $quote_focus->column_fields['conversion_rate'];

	} else {
		$focus->id = $_REQUEST['record'];
		$focus->mode = 'edit';
		$focus->retrieve_entity_info($_REQUEST['record'], "Samplerequisition");
		$focus->name = $focus->column_fields['samplerequisition_name'];
	}
} else {
	if (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'update_quote_val') {
		//Updating the Select Quote Value in Create Mode
		foreach ($focus->column_fields as $fieldname => $val) {
			if (isset ($_REQUEST[$fieldname])) {
				$value = $_REQUEST[$fieldname];
				$focus->column_fields[$fieldname] = $value;
			}
		}
		//Handling for dateformat in due_date aicrm_field
		if ($focus->column_fields['duedate'] != '') {
			$curr_due_date = $focus->column_fields['duedate'];
			$focus->column_fields['duedate'] = getDBInsertDateValue($curr_due_date);
		}
		$quoteid = $focus->column_fields['quote_id'];
		$quote_focus = new Quotes();
		$quote_focus->id = $quoteid;
		$quote_focus->retrieve_entity_info($quoteid, "Quotes");
		$focus = getConvertQuoteToSoObject($focus, $quote_focus, $quoteid);

		// Reset the value w.r.t Quote Selected
		$currencyid = $quote_focus->column_fields['currency_id'];
		$rate = $quote_focus->column_fields['conversion_rate'];

		//Added to display the Quotes's associated aicrm_products -- when we select Quote in New SO page
		if (isset ($_REQUEST['quote_id']) && $_REQUEST['quote_id'] != '') {
			$associated_prod = getAssociatedSamplerequisition("Samplerequisition", $quote_focus, $focus->column_fields['quote_id']);
		}

		$smarty->assign("QUOTE_ID", $focus->column_fields['quote_id']);
		$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("MODE", $quote_focus->mode);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	}
	else{
        //$pquery = "select user_name from aicrm_users where id = '".$_SESSION["authenticated_user_id"]."' ";
        //$cf_res = $adb->pquery($pquery,'');
        //$user_name=$adb->query_result($cf_res, 0, "user_name");
        //$focus->column_fields['cf_4394']=$user_name;
    }
}
//==========================================================================================
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$smarty->assign("DUPLICATE_FROM", $focus->id);
	$QUOTE_associated_prod = getAssociatedSamplerequisition("Samplerequisition",$focus);
	$log->debug("Mode is Duplicate. Samplerequisitionid to be duplicated is ".$focus->id);
	
	$focus->column_fields['samplerequisition_status']="Create";
	$focus->column_fields['date']=date('Y-m-d');
	$focus->column_fields['purchasing_period']=date("Y-m-d", strtotime((date("Y-m-d")) . "+7 day"));
	$focus->column_fields['assigned_user_id'] = $_SESSION['authenticated_user_id'];

	$focus->id = "";
    $focus->mode = ''; 	
}
if(empty($_REQUEST['record']) && $focus->mode != 'edit'){

	$focus->column_fields['samplerequisition_status']="Create";
	$focus->column_fields['date']=date('Y-m-d');
	$focus->column_fields['purchasing_period']=date("Y-m-d", strtotime((date("Y-m-d")) . "+7 day"));
	$focus->column_fields['assigned_user_id'] = $_SESSION['authenticated_user_id'];

	$sql = "select id as id
	,user_name as user_name
	, position
	, CONCAT(first_name_th, ' ', last_name_th) as 'sale_name'
	, IFNULL(area,'') as area
	, case when section	= '--None--' then '' else section end as section
	from aicrm_users
	where id= ".$_SESSION['authenticated_user_id']."
	and status='Active'";

	$res = $adb->pquery($sql, array());
	$sale_name = $adb->query_result($res,0,'sale_name');
	$position = $adb->query_result($res,0,'position');

	$focus->column_fields['to_mail']="";
	$focus->column_fields['from_mail'] = $sale_name;
	$focus->column_fields['position'] = $position;
	setObjectValuesFromRequest($focus);
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
    $focus->column_fields['account_id'] = $pro_focus->column_fields['account_id'];
    $focus->column_fields['contact_id'] = $pro_focus->column_fields['contact_id'];
}

if(isset($_REQUEST['isRevise']) && $_REQUEST['isRevise'] == 'true') {
	$smarty->assign("REVISE_FROM", $focus->id);
	$associated_prod = getAssociatedSamplerequisition("Samplerequisition",$focus);
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	$log->debug("Mode is Revise. Quoteid to be revised is ".$focus->id);

	$sql = "
	select 
	aicrm_samplerequisition.samplerequisition_no,
	aicrm_samplerequisition.revised_no,
	aicrm_samplerequisition.ref_sample_request		
	from aicrm_samplerequisition
	left join aicrm_samplerequisitioncf on  aicrm_samplerequisitioncf.samplerequisitionid=aicrm_samplerequisition.samplerequisitionid
	left join aicrm_crmentity c  on aicrm_samplerequisitioncf.samplerequisitionid  = c.crmid 
	where 1 
	and c.deleted <> 1 
	and aicrm_samplerequisition.samplerequisitionid='".$focus->id."'
	group by aicrm_samplerequisition.samplerequisitionid
	";

	$res = $adb->pquery($sql, array());
	$data_quote_no = $adb->query_result($res,0,'samplerequisition_no');
	$data_rev_no = $adb->query_result($res,0,'revised_no');
	$data_quote_no_rev = $adb->query_result($res,0,'ref_sample_request');

	if($data_rev_no=="" || $data_rev_no=="0"){
		$data_rev="1";
	}else{
		$data_rev=$data_rev_no+1;
	}

    $focus->column_fields['revised_no'] = $data_rev;
    $focus->column_fields['ref_sample_request'] = $data_quote_no;
    $focus->column_fields['samplerequisition_status'] = "Create";
    $focus->column_fields['date']=date('Y-m-d');
	$focus->column_fields['purchasing_period']=date("Y-m-d", strtotime((date("Y-m-d")) . "+7 day"));

    $focus->id = "";
    $focus->mode = '';

}

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
$smarty->assign("SINGLE_MOD",'Samplerequisition');
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
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
	$associated_prod = getAssociatedSamplerequisition("Samplerequisition",$focus);//getProductDetailsBlockInfo('edit','Quotes',$focus); 
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("MODE", $focus->mode);

}elseif(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        $smarty->assign("ASSOCIATEDPRODUCTS", $QUOTE_associated_prod);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');
        $smarty->assign("MODE", $focus->mode);

}elseif((isset($_REQUEST['potential_id']) && $_REQUEST['potential_id'] != '') || (isset($_REQUEST['product_id']) && $_REQUEST['product_id'] != '')) {
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

}else{
	$smarty->assign("ROWCOUNT", '1');
}

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", vtlib_purify($_REQUEST['return_module']));
else $smarty->assign("RETURN_MODULE","Samplerequisition");
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

$tabid = getTabid("Samplerequisition");
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
//Module Sequence Numbering
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
//echo "<pre>"; print_r(getAssociatedSamplerequisition("Samplerequisition",$focus)); echo "</pre>"; exit;
$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($currentModule);
$smarty->assign("PICKIST_DEPENDENCY_DATASOURCE", Zend_Json::encode($picklistDependencyDatasource));

$smarty->assign("CURRENCIES_LIST", getAllCurrencies());
$smarty->assign("a_sr_finish", get_sr_finish());
$smarty->assign("a_sr_size_mm", get_sr_size_mm());
$smarty->assign("a_sr_thickness_mm", get_sr_thickness_mm());
if($focus->mode == 'edit')
	$smarty->display("Samplerequisition/InventoryEditView.tpl");
else
	$smarty->display('Samplerequisition/InventoryCreateView.tpl');

?>