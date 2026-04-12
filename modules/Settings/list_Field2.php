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
$query="select 
aicrm_field.* 
,aicrm_tab.tablabel
from aicrm_field
inner join aicrm_tab on aicrm_tab.tabid=aicrm_field.tabid
where 1
and aicrm_field.presence=2
and left(columnname,3)='cf_'
and uitype not in(15,33)
order by  tabid,block,sequence
";
//$query=$list_query;
if( $adb->dbType == "pgsql")
	$query = fixPostgresQuery( $query, $log, 0);

if(PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true){
	$count_result = $adb->query( mkCountQuery( $query));
	$noofrows = $adb->query_result($count_result,0,"count");
}else{
	$noofrows = null;
}
//echo $noofrows;exit;
$queryMode = (isset($_REQUEST['query']) && $_REQUEST['query'] == 'true');
$start = ListViewSession::getRequestCurrentPage($currentModule, $query, $viewid, $queryMode);

$navigation_array = VT_getSimpleNavigationValues($start,$list_max_entries_per_page,$noofrows);
//print_r($navigation_array);exit;
$limit_start_rec = ($start-1) * $list_max_entries_per_page;

if( $adb->dbType == "pgsql")
	$result = $adb->pquery($query. " order by  tabid,block,sequence OFFSET $limit_start_rec LIMIT $list_max_entries_per_page ", array());
else
	$result = $adb->pquery($query. " order by  tabid,block,sequence LIMIT $limit_start_rec, $list_max_entries_per_page", array());
	
$recordListRangeMsg = getRecordRangeMessage($result, $limit_start_rec,$noofrows);
//echo $recordListRangeMsg;exit;
$result = $adb->pquery($query, array());
$num_rows=$adb->num_rows($result);
//echo $num_rows;exit;
$field_details=Array();
for($i=0;$i<$num_rows;$i++)
{
	$grpInfo=Array();
	$tabid=$adb->query_result($result,$i,'tablabel');
	$fieldid=$adb->query_result($result,$i,'fieldid');
	$tablename=$adb->query_result($result,$i,'tablename');
	$columnname=$adb->query_result($result,$i,'columnname');
	$fieldlabel=$adb->query_result($result,$i,'fieldlabel');
	
	$field_Info[0]=$tabid;
	$field_Info[1]=$fieldid;
	$field_Info[2]=$tablename;
	$field_Info[3]=$columnname;
	$field_Info[4]=$fieldlabel;
	$field_details[$fieldid]=$field_Info;
	
}
	
//$groupInfo=getAllGroupInfo();

$cnt=1;
$output='';
$list_header = array($mod_strings['LBL_LIST_TOOLS'],$mod_strings['LBL_Field_Module'],$mod_strings['LBL_Field_Table_Name'],$mod_strings['LBL_Field_Colum_Name'],$mod_strings['LBL_Field_Field_Label']);
$return_data = array();
foreach($field_details as $fieldid=>$field_details)
{
	
	$standCustFld = array();
	$standCustFld['tabid']= $field_details[0];	
	$standCustFld['fieldid']= $field_details[1];
	$standCustFld['tablename']= $field_details[2];
	$standCustFld['columnname']= $field_details[3];
	$standCustFld['fieldlabel']= $field_details[4];
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

//Ekk=========================================================================================================================================
//echo "444";exit;

/*$smarty->assign('recordListRange',$recordListRangeMsg);
//$listview_entries = getListViewEntries($focus,"Users",$list_result,$navigation_array,"","","EditView","Delete","");
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Settings","index",'');
//print_r($navigation_array );

$smarty->assign("LIST_HEADER",$list_header);
$smarty->assign("LIST_ENTRIES",$return_data);
$smarty->assign("USER_COUNT",$no_of_users);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("USER_IMAGES",getUserImageNames());
$smarty->assign("PROFILES", $standCustFld);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign('GRPCNT', count($return_data));*/
//Ekk=========================================================================================================================================

$smarty->display("ListField2.tpl");
?>
