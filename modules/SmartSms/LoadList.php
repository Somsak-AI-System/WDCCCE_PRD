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
//echo "555";exit;
global $singlepane_view;
$cvObj = new CustomView(vtlib_purify($_REQUEST["list_type"]));

$listquery = getListQuery(vtlib_purify($_REQUEST["list_type"]));
//echo '<pre>';print_r($_REQUEST);echo '</pre>'; exit;
$rs = $adb->query($cvObj->getModifiedCvListQuery(vtlib_purify($_REQUEST["cvid"]),$listquery,vtlib_purify($_REQUEST["list_type"])));
//print_r($rs);exit;
//echo $_REQUEST["list_type"]."<br>";exit;
//echo $cvObj->getModifiedCvListQuery(vtlib_purify($_REQUEST["cvid"]),$listquery,vtlib_purify($_REQUEST["list_type"]));
if($_REQUEST["list_type"] == "EmailTarget"){//echo "55";exit;
		$reltable = "aicrm_smartsms_emailtargetrel";
		$relid = "emailtargetid";
}
elseif($_REQUEST["list_type"] == "Contacts"){
		$reltable = "aicrm_smartsms_contactsrel";
		$relid = "contactid";
}
elseif($_REQUEST["list_type"] == "Accounts"){
		$reltable = "aicrm_smartsms_accountsrel";
		$relid = "accountid";
}
elseif($_REQUEST["list_type"] == "Opportunity"){
		$reltable = "aicrm_smartsms_opportunityrel";
		$relid = "opportunityid";
}
elseif($_REQUEST["list_type"] == "Questionnaire"){
		$reltable = "aicrm_smartsms_questionnairerel";
		$relid = "questionnaireid";
}
elseif($_REQUEST["list_type"] == "Leads"){
		$reltable = "aicrm_smartsms_leadsrel";
		$relid = "leadid";
}
//echo "555";exit;

while($row=$adb->fetch_array($rs)) {
	
	$sql = "delete from $reltable where $relid = ? and smartsmsid = ?";
	$adb->pquery($sql, array($row["crmid"], $_REQUEST['return_id']));
	$adb->pquery("INSERT INTO ".$reltable." VALUES(?,?)", array($_REQUEST["return_id"], $row["crmid"]));
}

// header("Location: index.php?module=SmartSms&action=SmartSmsAjax&file=CallRelatedList&ajax=true&record=".vtlib_purify($_REQUEST['return_id']));
/*echo "<script >window.location.href='index.php';</script>";*/
//header("Location: index.php?module=SmartSms&action=SmartSmsAjax&file=CallRelatedList&ajax=true&record=993788&parenttab=Marketing");
?>