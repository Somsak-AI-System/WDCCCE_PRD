<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Salesorder/Salesorder.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');


global $app_strings,$list_max_entries_per_page,$currentModule,$theme,$current_user;

$log = LoggerManager::getLogger('salesorder_list');

if (!isset($where)) $where = "";

$url_string = '';

$focus = new Salesorder();
// Initialize sort by fields
$focus->initSortbyField('Salesorder');
// END
$smarty = new vtigerCRM_Smarty;
$other_text = Array();

if(!$_SESSION['lvs'][$currentModule])
{
	unset($_SESSION['lvs']);
	$modObj = new ListViewSession();
	$modObj->sorder = $sorder;
	$modObj->sortby = $order_by;
	$_SESSION['lvs'][$currentModule] = get_object_vars($modObj);
}

if($_REQUEST['errormsg'] != '')
{
        $errormsg = vtlib_purify($_REQUEST['errormsg']);
        $smarty->assign("ERROR","The User does not have permission to Change/Delete ".$errormsg." ".$currentModule);
}else
{
        $smarty->assign("ERROR","");
}
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
$sorder = $focus->getSortOrder();
$order_by = $focus->getOrderBy();

$_SESSION['SALESORDER_ORDER_BY'] = $order_by;
$_SESSION['SALESORDER_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>


/*Padding 20 -200*/
	global $list_max_entries_per_page;
	
	// edit to show more than 20 records
	if (isset($_REQUEST['pagesize']) && $_REQUEST['pagesize']!="")
	{
		$list_max_entries_per_page = $_REQUEST['pagesize'];
	}else{
		global $list_max_entries_per_page;
	}
/*Padding 20 -200*/

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	list($where, $ustring) = split("#@@#",getWhereCondition($currentModule));
	// we have a query
	$url_string .="&query=true".$ustring;
	$log->info("Here is the where clause for the list view: $where");
	$smarty->assign("SEARCH_URL",$url_string);
}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Salesorder");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);

//Added to handle approving or denying status-public by the admin in CustomView
$statusdetails = $oCustomView->isPermittedChangeStatus($viewnamedesc['status']);
$smarty->assign("CUSTOMVIEW_PERMISSION",$statusdetails);

//To check if a user is able to edit/delete a customview
$edit_permit = $oCustomView->isPermittedCustomView($viewid,'EditView',$currentModule);
$delete_permit = $oCustomView->isPermittedCustomView($viewid,'Delete',$currentModule);
$smarty->assign("CV_EDIT_PERMIT",$edit_permit);
$smarty->assign("CV_DELETE_PERMIT",$delete_permit);

//<<<<<customview>>>>>
$smarty->assign("CHANGE_OWNER",getUserslist());
$smarty->assign("CHANGE_GROUP_OWNER",getGroupslist());

if(isPermitted('Salesorder','Delete','') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}
if(isPermitted('Salesorder','EditView','') == 'yes')
{
	$other_text['mass_edit'] = $app_strings[LBL_MASS_EDIT];
	$other_text['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
}
if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Salesorder');
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("BUTTONS", $other_text);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Salesorder");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Salesorder");
}else
{
	$query = getListQuery("Salesorder");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
    $query .= ' and '.$where;
    $_SESSION['export_where'] = $where;
}

if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
        {
		$query .= " ORDER BY case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end ".$sorder;
        }
        else
        {
		$tablename = getTableNameForField('Salesorder',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');
            $query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
        }
}
//echo $query;
//Postgres 8 fixes
if( $adb->dbType == "pgsql")
	$query = fixPostgresQuery( $query, $log, 0);

if(PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true){
	$count_result = $adb->query( mkCountQuery( $query));
	$noofrows = $adb->query_result($count_result,0,"count");
}else{
	$noofrows = null;
}

$queryMode = (isset($_REQUEST['query']) && $_REQUEST['query'] == 'true');
$start = ListViewSession::getRequestCurrentPage($currentModule, $query, $viewid, $queryMode);

$navigation_array = VT_getSimpleNavigationValues($start,$list_max_entries_per_page,$noofrows);

$limit_start_rec = ($start-1) * $list_max_entries_per_page;

if( $adb->dbType == "pgsql")
	$list_result = $adb->pquery($query. " OFFSET $limit_start_rec LIMIT $list_max_entries_per_page", array());
else
	$list_result = $adb->pquery($query. " LIMIT $limit_start_rec, $list_max_entries_per_page", array());

$recordListRangeMsg = getRecordRangeMessage($list_result, $limit_start_rec,$noofrows);
$smarty->assign('recordListRange',$recordListRangeMsg);

//Retreive the List View Table Header
if($viewid !='')
$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Salesorder",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"Salesorder",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"Salesorder",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

//Added to select Multiple records in multiple pages
$smarty->assign("SELECTEDIDS", vtlib_purify($_REQUEST['selobjs']));
$smarty->assign("ALLSELECTEDIDS", vtlib_purify($_REQUEST['allselobjs']));
$smarty->assign("CURRENT_PAGE_BOXES", implode(array_keys($listview_entries),";"));

$navigationOutput = getTableHeaderSimpleNavigation($navigation_array, $url_string,"Salesorder","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','subject','true','basic',"","","","",$viewid);
$fieldnames = getAdvSearchfields($module);
$criteria = getcriteria_options();
$smarty->assign("CRITERIA", $criteria);
$smarty->assign("FIELDNAMES", $fieldnames);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("BUTTONS", $other_text);

$smarty->assign("is_admin", $current_user->is_admin);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$_SESSION[$currentModule.'_listquery'] = $query;

// Get salesorder Status
$listquery1 =  getListQuery("Salesorder");
$listviewquery1 = substr($listquery1, strpos($listquery1,'FROM'),strlen($listquery1));

$pquery1 = "select aicrm_salesorder.quotation_status,aicrm_salesorder.salesorderid ";
$pquery1 .= $listviewquery1; 

if(isset($where) && $where != '')
{
	$pquery1 .= ' and '.$where;
	$_SESSION['export_where'] = $where;
}
if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
	{
		$pquery1 .= " ORDER BY case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end ".$sorder;
	}
	else
	{
		$tablename = getTableNameForField('Salesorder',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');
		$pquery1 .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
	}
}
$cf_res = $adb->pquery($pquery1 ." LIMIT $limit_start_rec, $list_max_entries_per_page",'');

$rowCount = $adb->num_rows($cf_res);
$a_response = array();
if($rowCount>0){
	for($i=0;$i<=$rowCount;$i++){
		$a_data=array();
		$salesorderid=$adb->query_result($cf_res, $i, "salesorderid");
		$salesorderstt = $adb->query_result($cf_res,$i, "quotation_status");
		
		$a_response[$salesorderid]["quotation_status"] = $salesorderstt;
	}
}
//########## Quostatus;
$smarty->assign("a_status", $a_response);

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>