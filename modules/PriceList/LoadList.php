<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): mmbrich
 ********************************************************************************/
      
require_once('modules/CustomView/CustomView.php');
require_once('user_privileges/default_module_view.php');

global $singlepane_view;
$cvObj = new CustomView(vtlib_purify($_REQUEST["list_type"]));

$listquery = getListQuery(vtlib_purify($_REQUEST["list_type"]));
$rs = $adb->query($cvObj->getModifiedCvListQuery(vtlib_purify($_REQUEST["cvid"]),$listquery,vtlib_purify($_REQUEST["list_type"])));

if($_REQUEST["list_type"] == "Accounts"){
		$reltable = "aicrm_pricelist_accountsrel";
		$relid = "accountid";
		$table = "aicrm_crmentityrel";
		$crmid = "crmid";
		$module = "module";
		$relcrmid = "relcrmid";
		$relmodule = "relmodule";
}

while($row=$adb->fetch_array($rs)) {
	
	$sql = "delete from $reltable where $relid = ? and pricelistid = ?";
	$adb->pquery($sql, array($row["crmid"], $_REQUEST['return_id']));
	$adb->pquery("INSERT INTO ".$reltable." VALUES(?,?)", array($_REQUEST["return_id"], $row["crmid"]));

	$sql = "delete from $table where $crmid = ? and $relcrmid = ?";
	$adb->pquery($sql, array( $_REQUEST['return_id'] ,$row["crmid"] ));
	$adb->pquery("INSERT INTO ".$table." VALUES(?,?,?,?)", array($_REQUEST["return_id"],$_REQUEST["module"] , $row["crmid"],$_REQUEST["list_type"]));
	
	
}

header("Location: index.php?module=PriceList&action=PriceListAjax&file=CallRelatedList&ajax=true&record=".vtlib_purify($_REQUEST['return_id']));
?>