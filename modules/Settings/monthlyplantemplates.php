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
$query="SELECT * FROM aicrm_monthly_plan ";
$result = $adb->pquery($query, array());
$num_rows=$adb->num_rows($result);
$toll_details=Array();
for($i=0;$i<$num_rows;$i++)
{
	$grpInfo=Array();
	$id=$adb->query_result($result,$i,'id');
	$section=$adb->query_result($result,$i,'section');
	$date_send=$adb->query_result($result,$i,'date_send');
	$time_send=$adb->query_result($result,$i,'time_send');
	$send_to=get_username($adb->query_result($result,$i,'send_to'));
	
	$toll_Info[0]=$section;
	$toll_Info[1]=$date_send;
	$toll_Info[2]=$time_send;
	$toll_Info[3]=$send_to;
	$toll_details[$id]=$toll_Info;
}
	
//$groupInfo=getAllGroupInfo();

$cnt=1;
$output='';
$list_header = array($mod_strings['LBL_LIST_TOOLS'],'Section','Date Send Month','Time Send','Send To');
$return_data = array();
foreach($toll_details as $id=>$toll_details)
{
	
	if($toll_details[1] == 99){
		$day = 'สิ้นเดือน';
	}else{
		$day =$toll_details[1];
	}
	
	$standCustFld = array();
	$standCustFld['id']= $id;	
	$standCustFld['section']= $toll_details[0];
	$standCustFld['date_send']= $day;
	$standCustFld['time_send']= $toll_details[2];
	$standCustFld['send_to']= $toll_details[3];
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


$smarty->display("ListmonthlyTemplates.tpl");
?>
