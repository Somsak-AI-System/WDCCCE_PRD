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
 * All Rights PriceListd.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /cvsroot/vtigercrm/aicrm_crm/modules/PriceList/Save.php,v 1.10 2005/12/14 18:51:30 jerrydgeorge Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights PriceListd.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/PriceList/PriceList.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
include("modules/Emails/mail.php");
/*if(isset($_REQUEST['dup_check']) && $_REQUEST['dup_check'] != ''){
	$account_type = $_REQUEST['account_type'];	
	if($account_type != '--None--'){
			$query = "SELECT * FROM `aicrm_pricelist_accountsrel`
							LEFT JOIN aicrm_crmentityrel ON aicrm_crmentityrel.crmid = aicrm_pricelist_accountsrel.pricelistid
							WHERE aicrm_pricelist_accountsrel.pricelistid =?";
			$params = array($_REQUEST['record']);			
			$result = $adb->pquery($query, $params);
		
			 if($adb->num_rows($result) > 0){
	 			echo "Contact First Name - Lastname Already Exists!";
	 		}
			else
			{
				echo 'SUCCESS';
			}
			die;
	}
}*/

$local_log =& LoggerManager::getLogger('index');
$focus = new PriceList();
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
$focus->save("PriceList");

$return_id = $focus->id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "PriceList";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);

header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>