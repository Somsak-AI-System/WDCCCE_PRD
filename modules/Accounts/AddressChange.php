<?php
/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('modules/Accounts/Accounts.php');
require_once('modules/Contacts/Contacts.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');
global $adb;
global $log;
//When changing the Account Address Information  it should also change the related contact address --Dinakaran

$record = $_REQUEST['record'];
$sql ="select aicrm_account.accountid,aicrm_accountbillads.bill_street as billingstreet, aicrm_accountbillads.bill_city as billingcity,aicrm_accountbillads.bill_code as billingcode,aicrm_accountbillads.bill_country as billingcountry,aicrm_accountbillads.bill_state as billingstate,aicrm_accountbillads.bill_pobox as billingpobox ,aicrm_accountshipads.* from aicrm_account inner join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid inner join aicrm_accountshipads on aicrm_accountshipads.accountaddressid = aicrm_account.accountid where accountid=?";


$result = $adb->pquery($sql, array($record));
$value = $adb->fetch_row($result);

if(($_REQUEST['bill_city'] != $value['billingcity'] && isset($_REQUEST['bill_city']))  ||
 ($_REQUEST['bill_street'] != $value['billingstreet'] && isset($_REQUEST['bill_street'])) ||
 ($_REQUEST['bill_country']!=$value['billingcountry'] && isset($_REQUEST['bill_country']))|| 
 ($_REQUEST['bill_code']!=$value['billingcode'] && isset($_REQUEST['bill_code']))||
 ($_REQUEST['bill_pobox']!=$value['billingpobox'] && isset($_REQUEST['bill_pobox'])) || 
 ($_REQUEST['bill_state']!=$value['billingstate'] && isset($_REQUEST['bill_state']))||
 ($_REQUEST['ship_country']!=$value['ship_country'] && isset($_REQUEST['ship_country']))|| 
 ($_REQUEST['ship_city']!=$value['ship_city'] && isset($_REQUEST['ship_city']))||
 ($_REQUEST['ship_state']!=$value['ship_state'] && isset($_REQUEST['ship_state']))||
 ($_REQUEST['ship_code']!=$value['ship_code'] && isset($_REQUEST['ship_code']))||
 ($_REQUEST['ship_street']!=$value['ship_street'] && isset($_REQUEST['ship_street']))|| 
 ($_REQUEST['ship_pobox']!=$value['ship_pobox'] && isset($_REQUEST['ship_pobox'])))
{
	$sql1="select contactid from aicrm_contactdetails where accountid=?";
	$result1 = $adb->pquery($sql1, array($record));
        if($adb->num_rows($result1) > 0)
	{
		echo 'address_change';
	}
	else
	{
		echo 'No Changes';
	}
}
else
{
	echo 'No Changes';
}
?>
