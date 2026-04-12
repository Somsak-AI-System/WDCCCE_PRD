<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/utils/utils.php');

$inv_type='Inventory';
$inv_tandc=from_html($_REQUEST['inventory_tandc']);
$inv_type2='Inventory';
$inv_tandc2=from_html($_REQUEST['inventory_tandc2']);

$sql="select * from aicrm_inventory_tandc where type=?";
$result = $adb->pquery($sql, array($inv_type));

$inv_id = $adb->query_result($result,0,'id');
$inv_id2 = $adb->query_result($result,1,'id');

if($inv_id == '' || $inv_id2 == '')
{
	$inv_id=$adb->getUniqueID('aicrm_inventory_tandc');
    $sql="insert into aicrm_inventory_tandc values(?,?,?)";
	$params = array($inv_id, $inv_type, $inv_tandc);

	$inv_id2=$adb->getUniqueID('aicrm_inventory_tandc');
    $sql2="insert into aicrm_inventory_tandc values(?,?,?)";
	$params2 = array($inv_id2, $inv_type, $inv_tandc2);
}
else
{
	$sql="update aicrm_inventory_tandc set type = ?, tandc = ? where id = ?";
	$params = array($inv_type, $inv_tandc, $inv_id);

	$sql2="update aicrm_inventory_tandc set type = ?, tandc = ? where id = ?";
	$params2 = array($inv_type2, $inv_tandc2, $inv_id2);
}

$adb->pquery($sql, $params);
$adb->pquery($sql2, $params2);

header("Location: index.php?module=Settings&action=OrganizationTermsandConditions&parenttab=Settings");
?>
