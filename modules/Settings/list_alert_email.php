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
$query="SELECT * FROM aicrm_config_alert WHERE 1 and deleted=0 and alert_type='Email' ";
$result = $adb->pquery($query, array());
$num_rows=$adb->num_rows($result);
$toll_details=Array();
for($i=0;$i<$num_rows;$i++)
{
	$grpInfo=Array();
	$id=$adb->query_result($result,$i,'id');
	$alert_name=$adb->query_result($result,$i,'alert_name');
	$alert_msg=$adb->query_result($result,$i,'alert_msg');
	$status=$adb->query_result($result,$i,'status');
	
	$toll_Info[0]=$alert_name;
	$toll_Info[1]=$alert_msg;
	$toll_Info[2]=$status;
	$toll_details[$id]=$toll_Info;
}
	
//$groupInfo=getAllGroupInfo();

$cnt=1;
$output='';
$list_header = array($mod_strings['LBL_LIST_TOOLS'],$mod_strings['LBL_ALERT_NAME'],$mod_strings['LBL_ALERT_MESSAGE'],$mod_strings['LBL_ALERT_STATUS']);
$return_data = array();
foreach($toll_details as $id=>$toll_details)
{
	
	$standCustFld = array();
	$standCustFld['id']= $id;	
	$standCustFld['alert_name']= $toll_details[0];
	$standCustFld['alert_msg']= $toll_details[1];
	$standCustFld['status']= $toll_details[2];
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


$smarty->display("list_alert_email.tpl");
?>
