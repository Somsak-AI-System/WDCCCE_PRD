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
require_once('include/utils/utils.php');
require_once('modules/Calendar/Calendar.php');

global $app_strings, $mod_strings, $currentModule, $theme;

$focus = CRMEntity::getInstance($currentModule);
$smarty = new vtigerCRM_Smarty();
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends
if ($_REQUEST['action'] == 'EditView') {
    $focus->column_fields['time_start'] = date('H:i');
    $focus->column_fields['time_end'] = date('H:i', strtotime(' +1 hours'));//date('H:i');
}

if (isset($_REQUEST['record']) && $_REQUEST['record'] != '') {
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit';
    $focus->retrieve_entity_info($_REQUEST['record'], "Calendar");
    $focus->name = $focus->column_fields['activitytype'];
}

if (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
    $focus->mode = '';
    $focus->column_fields['location'] = '';
    $focus->column_fields['location_chkout'] = '';
    $focus->column_fields['checkindate'] = '';
    $focus->column_fields['checkoutdate'] = '';

}

if (empty($_REQUEST['record']) && $focus->mode != 'edit') {

    setObjectValuesFromRequest($focus);
    $focus->column_fields['activitytype'] = $_REQUEST['activitytype'];

    /*if (isset($_REQUEST['account_id']) && $_REQUEST['account_id'] != '') {
        require_once('modules/Accounts/Accounts.php');
        $acct_focus = new Accounts();
        $acct_focus->retrieve_entity_info($_REQUEST['account_id'], "Accounts");
        setObjectValuesFromRequest($focus);
    }

    if (isset($_REQUEST['lead_id']) && $_REQUEST['lead_id'] != '') {
        require_once('modules/Leads/Leads.php');
        $lead_focus = new Leads();
        $lead_focus->retrieve_entity_info($_REQUEST['lead_id'], "Leads");
        setObjectValuesFromRequest($focus);
    }*/

    if (isset($_REQUEST['contact_id']) && $_REQUEST['contact_id'] != '') {
        require_once('modules/Contacts/Contacts.php');
        $con_focus = new Contacts();
        $con_focus->retrieve_entity_info($_REQUEST['contact_id'], "Contacts");
        setObjectValuesFromRequest($focus);
        $focus->column_fields['con_department'] = $con_focus->column_fields['con_department'];
        $focus->column_fields['con_position'] = $con_focus->column_fields['con_position'];
        $focus->column_fields['email'] = $con_focus->column_fields['email'];
        $focus->column_fields['phone'] = $con_focus->column_fields['mobile'];

        $focus->column_fields['parentid'] = $con_focus->column_fields['account_id'];
        
    }

    if (isset($_REQUEST['times']) && $_REQUEST['times'] != '') {
        $date = $_REQUEST['times'];
        $focus->column_fields['time_start'] = $date;
        $focus->column_fields['time_end'] = $date;
    }
    if ($_REQUEST["day"] != '') {
        $focus->column_fields['date_start'] = date("Y-m-d", strtotime($_REQUEST["day"]));
        $focus->column_fields['due_date'] = date("Y-m-d", strtotime($_REQUEST["day"]));
    }
    if ($_REQUEST["day"] != "" and $_REQUEST["month"] != "" and $_REQUEST["year"] != "") {
        $focus->column_fields['date_start'] = date("Y-m-d", strtotime($_REQUEST["day"] . "-" . $_REQUEST["month"] . "-" . $_REQUEST["year"]));
        $focus->column_fields['due_date'] = date("Y-m-d", strtotime($_REQUEST["day"] . "-" . $_REQUEST["month"] . "-" . $_REQUEST["year"]));
    }
}

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Accounts' && $_REQUEST['return_id'] != ''  ) {
    require_once('modules/Accounts/Accounts.php');
    $acc_focus = new Accounts();
    $acc_focus->retrieve_entity_info($_REQUEST['return_id'], "Accounts");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['parentid'] = $_REQUEST['return_id'];
    $focus->column_fields['parent_name'] = $acc_focus->column_fields['accountname'];
    $focus->column_fields['phone'] = $acc_focus->column_fields['mobile'];
    $focus->column_fields['email'] = $acc_focus->column_fields['email1'];

}else if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Leads' && $_REQUEST['return_id'] != '' ){
    require_once('modules/Leads/Leads.php');
    $lead_focus = new Leads();
    $lead_focus->retrieve_entity_info($_REQUEST['return_id'], "Leads");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['parentid'] = $_REQUEST['return_id'];
    $focus->column_fields['parent_name'] = $lead_focus->column_fields['firstname'].' '.$lead_focus->column_fields['lastname'];
    $focus->column_fields['phone'] = $lead_focus->column_fields['mobile'];
    $focus->column_fields['email'] = $lead_focus->column_fields['email'];

}else if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Deal' && $_REQUEST['return_id'] != '' ){
    require_once('modules/Deal/Deal.php');
    $deal_focus = new Deal();
    $deal_focus->retrieve_entity_info($_REQUEST['return_id'], "Deal");
    setObjectValuesFromRequest($focus);

    if (isset($deal_focus->column_fields['parentid'])&& $deal_focus->column_fields['parentid'] !=''){
        require_once('include/database/PearDatabase.php');

        require_once("config.php");
        global $adb;
        $sql_module = "SELECT setype from aicrm_crmentity where crmid = '".$deal_focus->column_fields['parentid']."' ";
        $settype_data = $adb->pquery($sql_module,'');
        $setype = $adb->query_result($settype_data, 0, "setype");

        if(isset($setype) && $setype == 'Accounts'){

            require_once('modules/Accounts/Accounts.php');
            $acc_focus = new Accounts();
            $acc_focus->retrieve_entity_info($deal_focus->column_fields['parentid'], "Accounts");
            setObjectValuesFromRequest($focus);
            $focus->column_fields['parentid'] = $deal_focus->column_fields['parentid'];
            $focus->column_fields['parent_name'] = $acc_focus->column_fields['accountname'];
            $focus->column_fields['fullname'] = $acc_focus->column_fields['accountname'];
            $focus->column_fields['phone'] = $acc_focus->column_fields['mobile'];
            $focus->column_fields['email'] = $acc_focus->column_fields['email1'];
        }
        if(isset($setype) && $setype == 'Leads'){
            require_once('modules/Leads/Leads.php');
            $lead_focus = new Leads();
            $lead_focus->retrieve_entity_info($deal_focus->column_fields['parentid'], "Leads");
            setObjectValuesFromRequest($focus);
            $focus->column_fields['parentid'] = $deal_focus->column_fields['parentid'];
            $focus->column_fields['parent_name'] = $lead_focus->column_fields['firstname'].' '.$lead_focus->column_fields['lastname'];
            $focus->column_fields['fullname'] = $lead_focus->column_fields['firstname'].' '.$lead_focus->column_fields['lastname'];
            $focus->column_fields['phone'] = $lead_focus->column_fields['mobile'];
            $focus->column_fields['email'] = $lead_focus->column_fields['email'];

        }
    }
    $focus->column_fields['dealid'] = $_REQUEST['return_id'];
    $focus->column_fields['deal_no'] = $deal_focus->column_fields['deal_no'];

}

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Job' && $_REQUEST['return_id'] != ''  ) {
    require_once('modules/Job/Job.php');
    $job_focus = new Job();
    $job_focus->retrieve_entity_info($_REQUEST['return_id'], "Job");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['event_id'] = $_REQUEST['return_id'];
    $focus->column_fields['event_name'] = $job_focus->column_fields['job_name'];
}else if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'HelpDesk' && $_REQUEST['return_id'] != '' ){
    require_once('modules/HelpDesk/HelpDesk.php');
    $help_focus = new HelpDesk();
    $help_focus->retrieve_entity_info($_REQUEST['return_id'], "HelpDesk");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['event_id'] = $_REQUEST['return_id'];
    $focus->column_fields['event_name'] = $help_focus->column_fields['ticket_no'];
}else if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Projects' && $_REQUEST['return_id'] != '' ){
    require_once('modules/Projects/Projects.php');
    $pro_focus = new Projects();
    $pro_focus->retrieve_entity_info($_REQUEST['return_id'], "Projects");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['event_id'] = $_REQUEST['return_id'];
    $focus->column_fields['parentid'] = $pro_focus->column_fields['account_id'];
    $focus->column_fields['contact_id'] = $pro_focus->column_fields['contact_id'];
}else if(isset($_REQUEST['related_module']) && $_REQUEST['related_module'] == 'Projects' && $_REQUEST['related_id'] != '' ){
    require_once('modules/Projects/Projects.php');
    $pro_focus = new Projects();
    $pro_focus->retrieve_entity_info($_REQUEST['related_id'], "Projects");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['event_id'] = $_REQUEST['related_id'];
    $focus->column_fields['parentid'] = $pro_focus->column_fields['account_id'];
    $focus->column_fields['contact_id'] = $pro_focus->column_fields['contact_id'];
}else if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Competitor' && $_REQUEST['return_id'] != '' ){
    require_once('modules/Competitor/Competitor.php');
    $com_focus = new Competitor();
    $com_focus->retrieve_entity_info($_REQUEST['return_id'], "Competitor");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['competitorid'] = $_REQUEST['return_id'];
    $focus->column_fields['competitor_name'] = $com_focus->column_fields['competitor_name'];
}

$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";

$disp_view = getView($focus->mode);

if ($disp_view == 'edit_view')
    $smarty->assign("BLOCKS", getBlocks($currentModule, $disp_view, $mode, $focus->column_fields));
else {
    $smarty->assign("BASBLOCKS", getBlocks($currentModule, $disp_view, $mode, $focus->column_fields, 'BAS'));
}

$smarty->assign("OP_MODE", $disp_view);

$smarty->assign("MODULE", $currentModule);
$smarty->assign("SINGLE_MOD", 'Sales Visit');

$category = getParentTab();
$smarty->assign("CATEGORY", $category);

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);

if (isset($focus->name))
    $smarty->assign("NAME", $focus->name);
else
    $smarty->assign("NAME", "");

if (isset($cust_fld)) {
    $smarty->assign("CUSTOMFIELD", $cust_fld);
}
$smarty->assign("ID", $focus->id);
if ($focus->mode == 'edit') {
    $smarty->assign("MODE", $focus->mode);
    $smarty->assign("OLDSMOWNERID", $focus->column_fields['assigned_user_id']);
    $smarty->assign("UPDATEINFO", updateInfo($focus->id));
}

if (isset($_REQUEST['return_module']))
    $smarty->assign("RETURN_MODULE", vtlib_purify($_REQUEST['return_module']));
if (isset($_REQUEST['return_action']))
    $smarty->assign("RETURN_ACTION", vtlib_purify($_REQUEST['return_action']));
if (isset($_REQUEST['return_id']))
    $smarty->assign("RETURN_ID", vtlib_purify($_REQUEST['return_id']));
if (isset($_REQUEST['product_id']))
    $smarty->assign("PRODUCTID", vtlib_purify($_REQUEST['product_id']));
if (isset($_REQUEST['return_viewname']))
    $smarty->assign("RETURN_VIEWNAME", vtlib_purify($_REQUEST['return_viewname']));

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=" . session_id() . $GLOBALS['request_string']);

$tabid = getTabid("Calendar");
$tabid = "16";
$validationData = getDBValidationData($focus->tab_name, $tabid);
$data = split_validationdataArray($validationData);

$validationDataUitype = getDBValidationDataUitype($focus->tab_name,$tabid);
$dataUitype = split_validationdataUitype($validationDataUitype);

$smarty->assign("VALIDATION_DATA_UITYPE",$dataUitype['datauitype']);
$smarty->assign("VALIDATION_DATA_FIELDNAME", $data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE", $data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL", $data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("DUPLICATE", vtlib_purify($_REQUEST['isDuplicate']));

global $adb;
// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);

if ($focus->mode != 'edit' && $mod_seq_field != null) {
    $autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
    $mod_seq_string = $adb->pquery("SELECT prefix, cur_id from aicrm_modentity_num where semodule = ? and active=1", array($currentModule));
    $mod_seq_prefix = $adb->query_result($mod_seq_string, 0, 'prefix');
    $mod_seq_no = $adb->query_result($mod_seq_string, 0, 'cur_id');
    if ($adb->num_rows($mod_seq_string) == 0 || $focus->checkModuleSeqNumber($focus->table_name, $mod_seq_field['column'], $mod_seq_prefix . $mod_seq_no))
        echo '<br><font color="#FF0000"><b>' . getTranslatedString('LBL_DUPLICATE') . ' ' . getTranslatedString($mod_seq_field['label'])
            . ' - ' . getTranslatedString('LBL_CLICK') . ' <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings&selmodule=' . $currentModule . '">' . getTranslatedString('LBL_HERE') . '</a> '
            . getTranslatedString('LBL_TO_CONFIGURE') . ' ' . getTranslatedString($mod_seq_field['label']) . '</b></font>';
    else
        $smarty->assign("MOD_SEQ_ID", $autostr);
} else {
    $smarty->assign("MOD_SEQ_ID", $focus->column_fields[$mod_seq_field['name']]);
}
// END;

if($_REQUEST['record'] != '')
{
    //Added to display the service comments information
    $smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));
}

$flagcontact = false;
$flagaccount = false;
$flagproject = false;
$flagplan = false;
$flaglocation = false;

global $current_user;
require('user_privileges/user_privileges_' . $current_user->id . '.php');
require('user_privileges/sharing_privileges_' . $current_user->id . '.php');

if ($focus->mode == 'edit' and $is_admin == false) {
    if ($focus->column_fields["contact_id"] != 0 and $focus->column_fields["contact_id"] != "") {
        $flagcontact = true;
    }
    if ($focus->column_fields["account_id"] != 0 and $focus->column_fields["account_id"] != "") {
        $flagaccount = true;
    }
    if ($focus->column_fields["projectid"] != 0 and $focus->column_fields["projectid"] != "") {
        $flagproject = true;
    }
    if ($focus->column_fields["description"] != '') {
        $flagplan = true;
    }
    if ($focus->column_fields["location"] != '') {
        $flaglocation = true;
    }
}
$smarty->assign("flagcontact", $flagcontact);
$smarty->assign("flagaccount", $flagaccount);
$smarty->assign("flagproject", $flagproject);
$smarty->assign("flagplan", $flagplan);
$smarty->assign("flaglocation", $flaglocation);

$flag_send_report = 0;
if($_REQUEST['record'] != ''){
    $cquery = "SELECT flag_send_report FROM aicrm_activity WHERE activityid = '".$focus->id."' ";
    $result_c = $adb->pquery($cquery,'');
    $flag_send_report =$adb->query_result($result_c, 0, "flag_send_report");
}
//echo $flag_send_report; exit;
$smarty->assign("flag_send_report", $flag_send_report);

$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource("Events");
$smarty->assign("PICKIST_DEPENDENCY_DATASOURCE", Zend_Json::encode($picklistDependencyDatasource));

if ($focus->mode == 'edit')
    $smarty->display("salesEditView.tpl");
else
    $smarty->display("CreateView.tpl");

?>