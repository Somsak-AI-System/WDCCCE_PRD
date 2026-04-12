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
 * $Header: /cvsroot/vtigercrm/aicrm_crm/modules/Salesorder/Save.php,v 1.10 2005/12/14 18:51:30 jerrydgeorge Exp $
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Salesorder/Salesorder.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
include("modules/Emails/mail.php");

$local_log =& LoggerManager::getLogger('index');
$focus = new Salesorder();
//added to fix 4600
$search = vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);
$focus->column_fields['currency_id'] = $_REQUEST['inventory_currency'];
$focus->column_fields['pricetype'] = $_REQUEST['pricetype'];

$cur_sym_rate = getCurrencySymbolandCRate($_REQUEST['inventory_currency']);
$focus->column_fields['conversion_rate'] = $cur_sym_rate['rate'];

if ($_REQUEST['assigntype'] == 'U') {
    $focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif ($_REQUEST['assigntype'] == 'T') {
    $focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}

include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

/*echo "<pre>"; print_r($_REQUEST['convert_quotesid']); echo "</pre>"; exit;
*/
if($focus->mode == 'edit'){
	$sql ="select * from aicrm_inventoryproductrel where id = '".$focus->id."' ";
	$after_p = $myLibrary_mysqli->select($sql);
}
$focus->save("Salesorder");

//booking stock Action Add
if($focus->mode == ''){
	$data_p = array();
	for ($i=1; $i <= $_REQUEST['totalProductCount']; $i++) {
		$sql_p = "update aicrm_products set stockbooking = (stockbooking+".$_REQUEST['qty'.$i].") , stockavailable = (stockavailable-".$_REQUEST['qty'.$i].") where productid = '".$_REQUEST['hdnProductId'.$i]."' ";
		$myLibrary_mysqli->Query($sql_p);
	}
	if($_REQUEST['convert_quotesid'] != ''){
		$sql_q = "Update aicrm_quotes set convertflag = 1 where quoteid = '".$_REQUEST['convert_quotesid']."' ";
		$myLibrary_mysqli->Query($sql_q);
	}
}else if($focus->mode == 'edit'){//Action Edit
	foreach ($after_p as $key => $value) {
		$sql_p = "update aicrm_products set stockbooking = (stockbooking-".$value['quantity'].") , stockavailable = (stockavailable+".$value['quantity'].") where productid = '".$value['productid']."' ";
		$myLibrary_mysqli->Query($sql_p);
	}
	for ($i=1; $i <= $_REQUEST['totalProductCount']; $i++) {
		$sql_p = "update aicrm_products set stockbooking = (stockbooking+".$_REQUEST['qty'.$i].") , stockavailable = (stockavailable-".$_REQUEST['qty'.$i].") where productid = '".$_REQUEST['hdnProductId'.$i]."' ";
		$myLibrary_mysqli->Query($sql_p);
	}
}

$crmid = $focus->id;

$query="select salesorder_no from aicrm_salesorder where salesorderid=?";
$params = array($focus->id);        
$data_tik = $adb->pquery($query, $params);
$salesorder_no=$adb->query_result($data_tik,0,'salesorder_no');
$_REQUEST['salesorder_no'] = $salesorder_no;

/*require_once('include/email_alert/email_alert.php');
$user_emailid = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
$subject = "Sales Qrder No : [".$salesorder_no."] เรื่อง : ".$focus->column_fields['subject'];
$email_body = GetEmail("Salesorder","header_alert.jpg",$crmid,"salesorderid");
$mail_status = send_mail('Salesorder',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);*/

//print_r($mail_status); exit;

$return_id = $focus->id;

$parenttab = getParentTab();
if (isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Salesorder";
if (isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if (isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of " . $return_id);

//code added for returning back to the current view after edit from list view
if ($_REQUEST['return_viewname'] == '') $return_viewname = '0';
if ($_REQUEST['return_viewname'] != '') $return_viewname = vtlib_purify($_REQUEST['return_viewname']);

header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=" . vtlib_purify($_REQUEST['pagenumber']) . $search);
?>