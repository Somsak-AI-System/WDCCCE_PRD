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
 * $Header: /cvsroot/vtigercrm/aicrm_crm/modules/Quotes/Save.php,v 1.10 2005/12/14 18:51:30 jerrydgeorge Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Claim/Claim.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
include("modules/Emails/mail.php");
global $log,$adb;
// echo '<pre>';
// print_r($_REQUEST);
// echo '</pre>';
// exit();

$local_log =& LoggerManager::getLogger('index');

$focus = new Claim();
//added to fix 4600
$search=vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);

$focus->column_fields['currency_id'] = $_REQUEST['inventory_currency'];
$cur_sym_rate = getCurrencySymbolandCRate($_REQUEST['inventory_currency']);
$focus->column_fields['conversion_rate'] = $cur_sym_rate['rate'];

if($_REQUEST['assigntype'] == 'U') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}
$focus->save("Claim");

/** Update list price_type */
$adb->pquery("UPDATE aicrm_claim SET list1_price_type=?, list2_price_type=? WHERE claimid=?", [$_REQUEST['list1_price_type'], $_REQUEST['list2_price_type'], $focus->id]);

$return_id = $focus->id;
/** Insert Product Item List */
$query = "DELETE FROM aicrm_inventoryclaim WHERE crmid=".$focus->id;
$adb->pquery($query, '');

if(isset($_REQUEST['product_list_1']) && !empty($_REQUEST['product_list_1']))
{
	foreach($_REQUEST['product_list_1'] as $i => $no){
		$productid = $_REQUEST['list1_product_id_'.$no];
		$productname = str_replace("'", "\'", $_REQUEST['list1_product_name_'.$no]);
		$inv_no = $_REQUEST['list1_inv_no_'.$no];
		$color = $_REQUEST['list1_color_'.$no];
		$buy_amount = str_replace(',', '', $_REQUEST['list1_buy_amount_'.$no]);
		$claim_amount = str_replace(',', '', $_REQUEST['list1_claim_amount_'.$no]);
		$price = $_REQUEST['list1_price_'.$no];
		$price_include_vat = $_REQUEST['list1_price_include_vat_'.$no];
		$price_exclude_vat = $_REQUEST['list1_price_exclude_vat_'.$no];
		$unit = $_REQUEST['list1_unit_'.$no];
		$total_price = str_replace(',', '', $_REQUEST['list1_total_price_'.$no]);

		if(!empty($productid) && $productid != ''){
			$query = "INSERT INTO aicrm_inventoryclaim (crmid, type, productid, productname, inv_no, color, buy_amount, claim_amount, price, price_include_vat, price_exclude_vat, unit, total_price)
			VALUES ('".$focus->id."', 'buy', '".$productid."', '".$productname."', '".$inv_no."', '".$color."', '".$buy_amount."', '".$claim_amount."', '".$price."', '".$price_include_vat."', '".$price_exclude_vat."', '".$unit."', '".$total_price."')";
			$adb->pquery($query, '');
		}

	}
}

if(isset($_REQUEST['product_list_2']) && !empty($_REQUEST['product_list_2']))
{
	foreach($_REQUEST['product_list_2'] as $i => $no){
		$productid = $_REQUEST['list2_product_id_'.$no];
		$productname = str_replace("'", "\'", $_REQUEST['list2_product_name_'.$no]);
		$inv_no = $_REQUEST['list2_inv_no_'.$no];
		$color = $_REQUEST['list2_color_'.$no];
		$buy_amount = str_replace(',', '', $_REQUEST['list2_buy_amount_'.$no]);
		$claim_amount = str_replace(',', '', $_REQUEST['list2_claim_amount_'.$no]);
		$price = $_REQUEST['list2_price_'.$no];
		$price_include_vat = $_REQUEST['list2_price_include_vat_'.$no];
		$price_exclude_vat = $_REQUEST['list2_price_exclude_vat_'.$no];
		$unit = $_REQUEST['list2_unit_'.$no];
		$total_price = str_replace(',', '', $_REQUEST['list2_total_price_'.$no]);

		if(!empty($productid) && $productid != ''){
			$sql = "INSERT INTO aicrm_inventoryclaim (crmid, type, productid, productname, inv_no, color, buy_amount, claim_amount, price, price_include_vat, price_exclude_vat, unit, total_price)
			VALUES ('".$focus->id."', 'claim', '".$productid."', '".$productname."', '".$inv_no."', '".$color."', '".$buy_amount."', '".$claim_amount."', '".$price."', '".$price_include_vat."', '".$price_exclude_vat."', '".$unit."', '".$total_price."')";
			$adb->pquery($sql, '');
		}
		
	}
}

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Claim";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == "ClaimList")
{
	if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "")
	{
		 
		 $sql = "delete from aicrm_claimlist_claimrel  where claimid  = ?";
		 $adb->pquery($sql, array($focus->id));
		 $sql = "insert into aicrm_claimlist_claimrel  values (?,?)";
		 $adb->pquery($sql, array($_REQUEST['return_id'], $focus->id));
	}
}
header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>