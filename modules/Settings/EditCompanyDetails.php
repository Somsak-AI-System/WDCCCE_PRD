<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('Smarty_setup.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

$smarty = new vtigerCRM_Smarty;
//error handling
if(isset($_REQUEST['flag']) && $_REQUEST['flag'] != '')
{
	$flag = $_REQUEST['flag'];
	switch($flag)
	{
		case 1:
			$smarty->assign("ERRORFLAG","<font color='red'><B>".$mod_strings['LOGO_ERROR']."</B></font>");;
			break;
		case 2:
			$smarty->assign("ERRORFLAG","<font color='red'><B>".$mod_strings['Error_Message']."<ul><li><font color='red'>".$mod_strings['Invalid_file']."</font><li><font color='red'>".$mod_strings['File_has_no_data']."</font></ul></B></font>");
			break;
		case 3:
			$smarty->assign("ERRORFLAG","<B><font color='red'>".$mod_strings['Sorry'].",".$mod_strings['uploaded_file_exceeds_maximum_limit'].".".$mod_strings['try_file_smaller']."</font></B>");
			break;
		case 4:
			$smarty->assign("ERRORFLAG","<b>".$mod_strings['Problems_in_upload'].". ".$mod_strings['Please_try_again']." </b><br>");
			break;
		default:
			$smarty->assign("ERRORFLAG","");
		
	}
}
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$sql="select * from aicrm_organizationdetails";
$result = $adb->pquery($sql, array());
$organization_name = str_replace('"','&quot;',$adb->query_result($result,0,'organizationname'));
$organization_address= $adb->query_result($result,0,'address');
$organization_city = $adb->query_result($result,0,'city');
$organization_state = $adb->query_result($result,0,'state');
$organization_code = $adb->query_result($result,0,'code');
$organization_country = $adb->query_result($result,0,'country');
$organization_phone = $adb->query_result($result,0,'phone');
$organization_fax = $adb->query_result($result,0,'fax');
$organization_tax = $adb->query_result($result,0,'tax');
$organization_website = $adb->query_result($result,0,'website');
$organization_logoname = $adb->query_result($result,0,'logoname');
$organization_imgfootername = $adb->query_result($result,0,'imgfootername');
$organization_limit_time = $adb->query_result($result,0,'limit_time');
$organization_payterm = $adb->query_result($result,0,'payterm');

$organization_email = $adb->query_result($result,0,'email');
$organization_purchase_cost = $adb->query_result($result,0,'purchase_cost');
$organization_consultant_cost = $adb->query_result($result,0,'consultant_cost');
$organization_cam_bath = $adb->query_result($result,0,'cam_bath');
$organization_exp_year = $adb->query_result($result,0,'exp_year');
$organization_sms_url = $adb->query_result($result,0,'sms_url');
$organization_sms_sendername = $adb->query_result($result,0,'sms_sendername');
$organization_sms_username = $adb->query_result($result,0,'sms_username');
$organization_sms_password = $adb->query_result($result,0,'sms_password');
$organization_questionnaire_backup = $adb->query_result($result,0,'questionnaire_backup');

$project_code = $adb->query_result($result,0,'project_code');
//echo $organization_limit_time;

if (isset($organization_name))
	$smarty->assign("ORGANIZATIONNAME",$organization_name);
if (isset($organization_address))
	$smarty->assign("ORGANIZATIONADDRESS",$organization_address);
if (isset($organization_city))
	$smarty->assign("ORGANIZATIONCITY",$organization_city);
if (isset($organization_state))
	$smarty->assign("ORGANIZATIONSTATE",$organization_state);
if (isset($organization_code))
	$smarty->assign("ORGANIZATIONCODE",$organization_code);
if (isset($organization_country))
	$smarty->assign("ORGANIZATIONCOUNTRY",$organization_country);
if (isset($organization_phone))
	$smarty->assign("ORGANIZATIONPHONE",$organization_phone);
if (isset($organization_fax))
	$smarty->assign("ORGANIZATIONFAX",$organization_fax);
if (isset($organization_tax))
    $smarty->assign("ORGANIZATIONTAX",$organization_tax);
if (isset($organization_website))
	$smarty->assign("ORGANIZATIONWEBSITE",$organization_website);
if ($organization_logoname == '') 
	$organization_logoname = vtlib_purify($_REQUEST['prev_name']);
if (isset($organization_logoname)) 
	$smarty->assign("ORGANIZATIONLOGONAME",$organization_logoname);
if (isset($organization_limit_time)) 
	$smarty->assign("ORGANIZATIONLIMITTIME",$organization_limit_time);
if (isset($organization_payterm)) 
	$smarty->assign("ORGANIZATIONPAYTERM",$organization_payterm);

if (isset($project_code))
	$smarty->assign("PROJECT_CODE",$project_code);

if (isset($organization_email)) 
	$smarty->assign("ORGANIZATION_EMAIL",$organization_email);
if (isset($organization_purchase_cost)) 
	$smarty->assign("ORGANIZATION_PURCHASE_COST",$organization_purchase_cost);
if (isset($organization_consultant_cost)) 
	$smarty->assign("ORGANIZATION_CONSULTANT_COST",$organization_consultant_cost);
if (isset($organization_cam_bath)) 
	$smarty->assign("ORGANIZATION_CAM_BATH",$organization_cam_bath);
if (isset($organization_exp_year)) 
	$smarty->assign("ORGANIZATION_EXP_YEAR",$organization_exp_year);
if (isset($organization_sms_url)) 
	$smarty->assign("ORGANIZATIONSMSURL",$organization_sms_url);	
if (isset($organization_sms_sendername)) 
	$smarty->assign("ORGANIZATIONSMSSENDERNAME",$organization_sms_sendername);
if (isset($organization_sms_username)) 
	$smarty->assign("ORGANIZATIONSMSUSERNAME",$organization_sms_username);
if (isset($organization_sms_password)) 
	$smarty->assign("ORGANIZATIONSMSPASSWORD",$organization_sms_password);	
if (isset($organization_questionnaire_backup)) 
	$smarty->assign("ORGANIZATIONQUESTIONNAIREBACKUP",$organization_questionnaire_backup);	
if (isset($organization_imgfootername))
	$smarty->assign("ORGANIZATIONIMGFOOTER",$organization_imgfootername);
	
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->display('Settings/EditCompanyInfo.tpl');
?>