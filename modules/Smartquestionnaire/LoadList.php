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
$rs = $adb->query($cvObj->getModifiedCvListQuery(vtlib_purify($_REQUEST["cvid"]),$listquery,vtlib_purify($_REQUEST["list_type"])));
//echo $_REQUEST["cvid"]."<br>";
//echo $cvObj->getModifiedCvListQuery(vtlib_purify($_REQUEST["cvid"]),$listquery,vtlib_purify($_REQUEST["list_type"]));
if($_REQUEST["list_type"] == "EmailTarget"){//echo "55";exit;
		$reltable = "aicrm_smartquestionnaire_emailtargetrel";
		$relid = "emailtargetid";
}
elseif($_REQUEST["list_type"] == "Contacts"){
		$reltable = "aicrm_smartquestionnaire_contactsrel";
		$relid = "contactid";
}
elseif($_REQUEST["list_type"] == "Accounts"){
		$reltable = "aicrm_smartquestionnaire_accountsrel";
		$relid = "accountid";
}
elseif($_REQUEST["list_type"] == "Opportunity"){
		$reltable = "aicrm_smartquestionnaire_opportunityrel";
		$relid = "opportunityid";
}
elseif($_REQUEST["list_type"] == "Questionnaire"){
		$reltable = "aicrm_smartquestionnaire_questionnairerel";
		$relid = "questionnaireid";
}
elseif($_REQUEST["list_type"] == "Leads"){
		$reltable = "aicrm_smartquestionnaire_leadsrel";
		$relid = "leadid";
}
//echo "555";exit;
//print_r($rs);exit;
while($row=$adb->fetch_array($rs)) {
	
	$sql = "delete from $reltable where $relid = ? and smartquestionnaireid = ?";
	$adb->pquery($sql, array($row["crmid"], $_REQUEST['return_id']));
	$adb->pquery("INSERT INTO ".$reltable." VALUES(?,?)", array($_REQUEST["return_id"], $row["crmid"]));
}

header("Location: index.php?module=Smartquestionnaire&action=SmartquestionnaireAjax&file=CallRelatedList&ajax=true&record=".vtlib_purify($_REQUEST['return_id']));
/*echo "<script >window.location.href='index.php';</script>";*/
//header("Location: index.php?module=Smartquestionnaire&action=SmartquestionnaireAjax&file=CallRelatedList&ajax=true&record=993788&parenttab=Marketing");
?>