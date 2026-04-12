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
$query="
SELECT 
cf_2249id,cf_2249,aicrm_cf_2249.branch_id,branch_name
FROM aicrm_cf_2249 
left join aicrm_branchs on aicrm_branchs.branchid=aicrm_cf_2249.branch_id
WHERE 1 and cf_2249!='--None--'
";
$result = $adb->pquery($query, array());
$num_rows=$adb->num_rows($result);
$toll_details=Array();
for($i=0;$i<$num_rows;$i++)
{
	$grpInfo=Array();
	$cf_2249id=$adb->query_result($result,$i,'cf_2249id');
	$cf_2249=$adb->query_result($result,$i,'cf_2249');
	$branch_id=$adb->query_result($result,$i,'branch_id');
	$branch_name=$adb->query_result($result,$i,'branch_name');
	
	$toll_Info[0]=$cf_2249;
	$toll_Info[1]=$branch_id;
	$toll_Info[2]=$branch_name;
	$toll_details[$cf_2249id]=$toll_Info;
}
	
//$groupInfo=getAllGroupInfo();

$cnt=1;
$output='';
$list_header = array($mod_strings['LBL_LIST_TOOLS'],$mod_strings['LBL_Case_Cat'],$mod_strings['LBL_Branch_Id'],$mod_strings['LBL_Branch_Name']);
$return_data = array();
foreach($toll_details as $cf_2249id=>$toll_details)
{
	
	$standCustFld = array();
	$standCustFld['cf_2249id']= $cf_2249id;	
	$standCustFld['cf_2249']= $toll_details[0];
	$standCustFld['branch_id']= $toll_details[1];
	$standCustFld['branch_name']= $toll_details[2];
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

$smarty->display("list_case_cat.tpl");
?>
