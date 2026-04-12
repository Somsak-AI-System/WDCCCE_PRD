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
/*********************************************************************************
 * $Header$
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('include/CustomFieldUtil.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');
global $mod_strings,$app_strings,$currentModule,$theme,$singlepane_view;

$focus = CRMEntity::getInstance($currentModule);

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Smartemail");
    $focus->id = $_REQUEST['record'];   
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
    
} 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Smartemail detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
 
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));
$smarty->assign("UPDATEINFO",updateInfo($focus->id));

$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("ID", vtlib_purify($_REQUEST['record']));

// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if ($mod_seq_field != null) {
    $mod_seq_id = $focus->column_fields[$mod_seq_field['name']];
} else {
    $mod_seq_id = $focus->id;
}
$smarty->assign('MOD_SEQ_ID', $mod_seq_id);
// END

$smarty->assign("SINGLE_MOD",'Smart Email');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Smartemail","EditView",$_REQUEST['record']) == 'yes')
    $smarty->assign("EDIT_DUPLICATE","permitted");

    
$smarty->assign("CREATEPDF","permitted");

if(isPermitted("SalesOrder","EditView",$_REQUEST['record']) == 'yes')
    $smarty->assign("CONVERTSALESORDER","permitted");

if(isPermitted("Invoice","EditView",$_REQUEST['record']) == 'yes')
    $smarty->assign("CONVERTINVOICE","permitted");

if(isPermitted("Smartemail","Delete",$_REQUEST['record']) == 'yes')
    $smarty->assign("DELETE","permitted");


$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Smartemail',$focus));

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

 $tabid = getTabid("Smartemail");
 $validationData = getDBValidationData($focus->tab_name,$tabid);
 $data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);
$smarty->assign("EDIT_PERMISSION",isPermitted($currentModule,'EditView',$_REQUEST['record']));

$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));

if($singlepane_view == 'true')
{
    $related_array = getRelatedLists($currentModule,$focus);
    $smarty->assign("RELATEDLISTS", $related_array);
}

$smarty->assign("SinglePane_View", $singlepane_view);
$smarty->assign("TODO_PERMISSION",CheckFieldPermission('parent_id','Calendar'));
$smarty->assign("EVENT_PERMISSION",CheckFieldPermission('parent_id','Events'));

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
    $recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
    VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}
// Record Change Notification
$focus->markAsViewed($current_user->id);
// END

$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', false));

include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$pquery = "select email_status , email_setup from aicrm_smartemail where smartemailid = '".$focus->id."' ";
$data = $myLibrary_mysqli->select($pquery);

$email_status=$data[0]["email_status"];
$email_setup=$data[0]["email_setup"];

$smarty->assign("email_status",$email_status);
$smarty->assign("email_setup",$email_setup);

$pquery = " select a.email Email
from
(
    select 
    b.email as email
    from aicrm_smartemail_leadsrel a
    left join aicrm_leaddetails  b on a.leadid=b.leadid
    left join aicrm_leadscf c on b.leadid=c.leadid
    left join aicrm_crmentity d on d.crmid=b.leadid
    where 1
    and d.deleted=0
    and b.email<>''
    -- and b.email_consent IN ('','1')
    and b.email LIKE '%@%'
    and a.smartemailid= '".$focus->id."'
    group by b.email
    
    union

    select 
    email1  as email
    from aicrm_smartemail_accountsrel a
    left join aicrm_account  b on a.accountid=b.accountid
    left join aicrm_accountscf c on b.accountid=c.accountid
    left join aicrm_crmentity d on d.crmid=b.accountid
    where 1
    and d.deleted=0
    and email1<>''
    -- and b.email_consent IN ('','1')
    and email1 LIKE '%@%'
    and a.smartemailid='".$focus->id."'
    group by email1
    
    union
    
    select 
    b.email as email
    from aicrm_smartemail_contactsrel a
    left join aicrm_contactdetails b on a.contactid=b.contactid
    left join aicrm_contactscf c on b.contactid=c.contactid
    left join aicrm_crmentity d on d.crmid=b.contactid
    where 1
    and d.deleted=0
    and b.email<>''
    -- and b.email_consent IN ('','1')
    and b.email LIKE '%@%'
    and a.smartemailid='".$focus->id."'
    group by b.email    
) as a 
group by email ";

//echo $pquery; exit();

$res = $myLibrary_mysqli->select($pquery);
$count_email = count($res);

// echo $count_email; exit();

$smarty->assign("count_email",$count_email);

$smarty->display("DetailView.tpl");
?>