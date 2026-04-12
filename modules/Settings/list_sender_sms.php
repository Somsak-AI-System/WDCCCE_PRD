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

require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

$smarty = new vtigerCRM_Smarty;
$query="SELECT * FROM aicrm_config_sender_sms WHERE 1 and deleted=0 ";
$result = $adb->pquery($query, array());
$num_rows=$adb->num_rows($result);
$toll_details=Array();
for($i=0;$i<$num_rows;$i++)
{
	$grpInfo=Array();
	$id=$adb->query_result($result,$i,'id');
	$sms_sender=$adb->query_result($result,$i,'sms_sender');
	$sms_status=$adb->query_result($result,$i,'sms_status');
	
	$toll_Info[0]=$sms_sender;
	$toll_Info[1]=$sms_status;
	$toll_details[$id]=$toll_Info;
}
	
//$groupInfo=getAllGroupInfo();

$cnt=1;
$output='';
$list_header = array($mod_strings['LBL_LIST_TOOLS'],$mod_strings['LBL_SMS_SENDER'],$mod_strings['LBL_SMS_STATUS']);
$return_data = array();
foreach($toll_details as $id=>$toll_details)
{
	
	$standCustFld = array();
	$standCustFld['id']= $id;	
	$standCustFld['sms_sender']= $toll_details[0];
	$standCustFld['sms_status']= $toll_details[1];
	$return_data[]=$standCustFld;
	$cnt++;
}

$smarty->assign("LIST_HEADER",$list_header);
$smarty->assign("LIST_ENTRIES",$return_data);
$smarty->assign("PROFILES", $standCustFld);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign('GRPCNT', count($return_data));


$smarty->display("list_sender_sms.tpl");
?>
