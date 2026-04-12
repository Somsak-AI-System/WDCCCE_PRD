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
$query="SELECT * FROM aicrm_config_sendemail WHERE 1 and deleted=0  and email_type='reply' ";
$result = $adb->pquery($query, array());
$num_rows=$adb->num_rows($result);
$toll_details=Array();
for($i=0;$i<$num_rows;$i++)
{
	$grpInfo=Array();
	$id=$adb->query_result($result,$i,'id');
	$email_from_name=$adb->query_result($result,$i,'email_from_name');
	$email_user=$adb->query_result($result,$i,'email_user');
	$email_pass=$adb->query_result($result,$i,'email_pass');
	$email_server=$adb->query_result($result,$i,'email_server');
	$email_port=$adb->query_result($result,$i,'email_port');
	$email_status=$adb->query_result($result,$i,'email_status');
	
	$toll_Info[0]=$email_from_name;
	$toll_Info[1]=$email_user;
	$toll_Info[2]=$email_pass;
	$toll_Info[3]=$email_server;
	$toll_Info[4]=$email_port;
	$toll_Info[5]=$email_status;
	$toll_details[$id]=$toll_Info;
}
	
//$groupInfo=getAllGroupInfo();

$cnt=1;
$output='';
$list_header = array($mod_strings['LBL_LIST_TOOLS'],$mod_strings['LBL_Email_Server'],$mod_strings['LBL_Email_Port'],$mod_strings['LBL_Email_Name'],$mod_strings['LBL_Email'],$mod_strings['LBL_Email_Pass']);
$return_data = array();
foreach($toll_details as $id=>$toll_details)
{
	
	$standCustFld = array();
	$standCustFld['id']= $id;	
	$standCustFld['email_from_name']= $toll_details[0];
	$standCustFld['email_user']= $toll_details[1];
	$standCustFld['email_pass']= $toll_details[2];
	$standCustFld['email_server']= $toll_details[3];
	$standCustFld['email_port']= $toll_details[4];
	$standCustFld['email_status']= $toll_details[5];
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


$smarty->display("list_reply_email.tpl");
?>
