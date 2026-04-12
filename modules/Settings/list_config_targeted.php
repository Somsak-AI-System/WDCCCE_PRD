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
$query="SELECT * FROM aicrm_config_rpt2 WHERE 1 and deleted=0 ";
$result = $adb->pquery($query, array());
$num_rows=$adb->num_rows($result);
$toll_details=Array();
for($i=0;$i<$num_rows;$i++)
{
	$grpInfo=Array();
	$id=$adb->query_result($result,$i,'id');
	$target_year=$adb->query_result($result,$i,'target_year');
	$call_in=$adb->query_result($result,$i,'call_in');
	$call_out=$adb->query_result($result,$i,'call_out');
	$walk=$adb->query_result($result,$i,'walk');
	$gross_booking=$adb->query_result($result,$i,'gross_booking');
	$booking_amount=$adb->query_result($result,$i,'booking_amount');
	$net_booking=$adb->query_result($result,$i,'net_booking');
	$net_booking_amount=$adb->query_result($result,$i,'net_booking_amount');
	
	$toll_Info[0]=$target_year;
	$toll_Info[1]=number_format($call_in,0);
	$toll_Info[2]=number_format($call_out,0);
	$toll_Info[3]=number_format($walk,0);
	$toll_Info[4]=number_format($gross_booking,0);
	$toll_Info[5]=number_format($booking_amount,0);
	$toll_Info[6]=number_format($net_booking,0);
	$toll_Info[7]=number_format($net_booking_amount,0);
	$toll_details[$id]=$toll_Info;
}
	
//$groupInfo=getAllGroupInfo();

$cnt=1;
$output='';
$list_header = array($mod_strings['LBL_LIST_TOOLS'],'Year','Call In','Call Out','Walk','Gross Booking','Gross Booking Value (MB)','Net Booking','Net Booking Value (MB)');
$return_data = array();
foreach($toll_details as $id=>$toll_details)
{
	
	$standCustFld = array();
	$standCustFld['id']= $id;	
	$standCustFld['target_year']= $toll_details[0];
	$standCustFld['call_in']= $toll_details[1];
	$standCustFld['call_out']= $toll_details[2];
	$standCustFld['walk']= $toll_details[3];
	$standCustFld['gross_booking']= $toll_details[4];
	$standCustFld['booking_amount']= $toll_details[5];
	$standCustFld['net_booking']= $toll_details[6];
	$standCustFld['net_booking_amount']= $toll_details[7];
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

$smarty->display("list_config_targeted.tpl");
?>
