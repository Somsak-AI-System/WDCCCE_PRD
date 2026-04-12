<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('modules/Documents/Documents.php');
require_once('include/logging.php');
require_once('include/upload_file.php');
global $root_directory;
$local_log =& LoggerManager::getLogger('index');
global $currentModule;
$focus = new Documents();
//added to fix 4600
setObjectValuesFromRequest($focus);
$search=vtlib_purify($_REQUEST['search_url']);


if(isset($_REQUEST['notecontent']) && $_REQUEST['notecontent'] != "")
	$_REQUEST['notecontent'] = fck_from_html($_REQUEST['notecontent']);

if (!isset($_REQUEST['date_due_flag'])) $focus->date_due_flag = 'off';

if(isset($_REQUEST['parentid']) && $_REQUEST['parentid'] != '')
	$focus->parentid = $_REQUEST['parentid'];
if($_REQUEST['assigntype'] == 'U')  {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}
//Save the Document

$focus->save($currentModule);

$return_id = $focus->id;
$note_id = $return_id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Documents";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);


$local_log->debug("Saved record with id of ".$return_id);

//Redirect to EditView if the given file is not valid.
if($file_upload_error)
{
	$return_module = 'Documents';
	$return_action = 'EditView';
	$return_id = $note_id.'&upload_error=true&return_module='.$return_module.'&return_action='.$return_action.'&return_id='.$return_id;
}

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != ""){
	$sql = " select count(aicrm_senotesrel.crmid) as document_count
				from aicrm_senotesrel
				where aicrm_senotesrel.crmid = '".$_REQUEST['return_id']."'
				group by aicrm_senotesrel.crmid  ";
			$result = $adb->pquery($sql,array());
			$document_count=  $adb->query_result($result,0,"document_count");
		$a_doc = array("Booking","Agreement","Installment","PersonalLoan","Inspection","Transfer");
		if (in_array($return_module, $a_doc)) {
			require_once('modules/'.$return_module.'/'.$return_module.'.php');
			$parent_focus = new $return_module();
			$parent_focus->retrieve_entity_info($_REQUEST['return_id'],$return_module);
			$parent_focus->column_fields['document'] =$document_count;
			$parent_focus->mode = "edit";
			$parent_focus->id = $_REQUEST['return_id'];
			$parent_focus->save($return_module);
		}
}
if(isset($_REQUEST['related_id']) && $_REQUEST['related_id'] != ""){
	$adb->pquery("insert into aicrm_senotesrel values (?,?)", array($_REQUEST['related_id'],$note_id));
}
//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);
header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>