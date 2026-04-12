<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('include/utils/utils.php');
require_once('Smarty_setup.php');

$smarty=new vtigerCRM_Smarty;
$sql="select fieldid from aicrm_field, aicrm_tab where aicrm_field.tabid=aicrm_tab.tabid and generatedtype=2 and aicrm_tab.name='Leads' and aicrm_field.presence in (0,2)";	
$result = $adb->pquery($sql, array());
$noofrows = $adb->num_rows($result);

for($i=0;$i<$noofrows;$i++)
{
	$lead_id=$adb->query_result($result,$i,"fieldid");
	$account_id_name=$lead_id."_account";			
	$contact_id_name=$lead_id."_contact";			
	//$potential_id_name=$lead_id."_potential";
	//$deal_id_name=$lead_id."_deal";			
	
	$account_id_val=$_REQUEST[$account_id_name];
	$contact_id_val=$_REQUEST[$contact_id_name];
	//$potential_id_val=$_REQUEST[$potential_id_name];
	//$deal_id_val=$_REQUEST[$deal_id_name];

	if($account_id_val=="None")
	{
		$account_id_val=0;
	}
	if($contact_id_val=="None")
	{
		$contact_id_val=0;
	}
	/*if($potential_id_val =="None")	
	{
		$potential_id_val=0;
	}
	/*if($deal_id_val =="None")	
	{
		$deal_id_val=0;
	}*/
	//$update_sql="update aicrm_convertleadmapping set accountfid=?, contactfid=?, potentialfid=? where leadfid=?";
	//$update_params = array($account_id_val, $contact_id_val, $potential_id_val, $lead_id);
	$update_sql="update aicrm_convertleadmapping set accountfid=?, contactfid=? where leadfid=?";
	//echo $update_sql; echo "<br>";
	$update_params = array($account_id_val, $contact_id_val, $lead_id);
	//print_r($update_params); echo "<br>";
	//$update_sql="update aicrm_convertleadmapping set accountfid=?, dealfid=? where leadfid=?";
	//$update_params = array($account_id_val, $deal_id_val, $lead_id);
	$adb->pquery($update_sql, $update_params);
}
//exit;
header("Location: index.php?action=CustomFieldList&module=Settings&parenttab=Settings");
	
?>