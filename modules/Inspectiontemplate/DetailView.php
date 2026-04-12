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
    $focus->retrieve_entity_info($_REQUEST['record'],"Inspectiontemplate");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Inspectiontemplate detail view");

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

$smarty->assign("SINGLE_MOD",'Inspection Template');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Inspectiontemplate","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

$smarty->assign("CREATEPDF","permitted");

if(isPermitted("SalesOrder","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTSALESORDER","permitted");

if(isPermitted("Invoice","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTINVOICE","permitted");

if(isPermitted("Inspectiontemplate","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

//$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
//$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Inspectiontemplate',$focus));

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$tabid = getTabid("Inspectiontemplate");
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

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}
// Record Change Notification
$focus->markAsViewed($current_user->id);
// END
global  $quoteid_id;
$quoteid_id= $focus->id;
$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', true));

include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$pquery = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.*
FROM aicrm_inspectiontemplate
INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$focus->id."' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";
$a_data = $myLibrary_mysqli->select($pquery);
//echo '<pre>';print_r($pquery);exit;

$pquery2 = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.*, aicrm_inspectiontemplate_choicedetail2.* 
FROM aicrm_inspectiontemplate
INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
INNER JOIN aicrm_inspectiontemplate_choicedetail2 ON aicrm_inspectiontemplate_choicedetail.choicedetailid = aicrm_inspectiontemplate_choicedetail2.choiceid 
WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$focus->id."' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";
$a_data2 = $myLibrary_mysqli->select($pquery2);
//echo '<pre>';print_r($a_data2);exit;

$data_template = array();//aicrm_inspectiontemplate_choicedetail
$data_template8 = array();//aicrm_inspectiontemplate_choicedetail2

if(!empty($a_data)){

	$choiceid = '';
	$i= -1;
	foreach ($a_data as $key => $value) {
//		alert($value);
		if($choiceid != $value['choiceid']){

			$c=0;
			$i++;
			$data_template[$i]['choice_type'] = $value['choice_type'];
			$data_template[$i]['choice_title'] = $value['choice_title'];
			$data_template[$i]['sequence'] = $value['sequence'];
			$data_template[$i]['tolerance_type'] = $value['tolerance_type'];
			$data_template[$i]['unit'] = $value['unit'];
			$data_template[$i]['check_tolerance_unit'] = $value['check_tolerance_unit'];
			$data_template[$i]['tolerance_unit'] = $value['tolerance_unit'];
			$data_template[$i]['check_tolerance_percent'] = $value['check_tolerance_percent'];
			$data_template[$i]['tolerance_percent'] = $value['tolerance_percent'];
			$data_template[$i]['check_tolerance_fso'] = $value['check_tolerance_fso'];
			$data_template[$i]['tolerance_fso_percent'] = $value['tolerance_fso_percent'];
			$data_template[$i]['tolerance_fso_val'] = $value['tolerance_fso_val'];
			$data_template[$i]['set_tole_amount'] = $value['set_tole_amount'];
			$data_template[$i]['uncer_setting'] = $value['uncer_setting'];
			$data_template[$i]['uncer_reading'] = $value['uncer_reading'];
			$data_template[$i]['head_col0'] = $value['head_col0'];
			$data_template[$i]['head_col1'] = $value['head_col1'];
			$data_template[$i]['head_col2'] = $value['head_col2'];
			$data_template[$i]['head_col3'] = $value['head_col3'];
			$data_template[$i]['head_col4'] = $value['head_col4'];
			$data_template[$i]['head_col5'] = $value['head_col5'];
			$data_template[$i]['head_col6'] = $value['head_col6'];
			$data_template[$i]['head_col7'] = $value['head_col7'];
			$data_template[$i]['head_col8'] = $value['head_col8'];
			$data_template[$i]['head_col9'] = $value['head_col9'];
            $data_template[$i]['head_col10'] = $value['head_col10'];
            $data_template[$i]['head_col11'] = $value['head_col11'];
            $data_template[$i]['head_col12'] = $value['head_col12'];
            $data_template[$i]['head_col13'] = $value['head_col13'];
            $data_template[$i]['head_col14'] = $value['head_col14'];
            $data_template[$i]['head_col15'] = $value['head_col15'];
            $data_template[$i]['head_col16'] = $value['head_col16'];
            $data_template[$i]['head_col17'] = $value['head_col17'];
            $data_template[$i]['head_col18'] = $value['head_col18'];
            $data_template[$i]['head_col19'] = $value['head_col19'];
            $data_template[$i]['head_col20'] = $value['head_col20'];

            $data_template[$i]['head_col21'] = $value['head_col21'];
            $data_template[$i]['head_col22'] = $value['head_col22'];
            $data_template[$i]['head_col23'] = $value['head_col23'];
            $data_template[$i]['head_col24'] = $value['head_col24'];

			$data_template[$i]['list'][$c]['choicedetailid'] = $value['choicedetailid'];
			$data_template[$i]['list'][$c]['sequence_detail'] = $value['sequence_detail'];
			$data_template[$i]['list'][$c]['col0'] = $value['col0'];
			$data_template[$i]['list'][$c]['col1'] = $value['col1'];
			$data_template[$i]['list'][$c]['std_resolution'] = $value['std_resolution'];
			$data_template[$i]['list'][$c]['min'] = $value['min'];
			$data_template[$i]['list'][$c]['max'] = $value['max'];
			$data_template[$i]['list'][$c]['list'] = $value['list'];
            $data_template[$i]['list'][$c]['sublist'] = $value['sublist'];
            $data_template[$i]['list'][$c]['accept_range'] = $value['accept_range'];

			$choiceid = $value['choiceid'];

		}else if($choiceid == $value['choiceid']){

			$c++;
			$data_template[$i]['list'][$c]['choicedetailid'] = $value['choicedetailid'];
			$data_template[$i]['list'][$c]['sequence_detail'] = $value['sequence_detail'];
			$data_template[$i]['list'][$c]['col0'] = $value['col0'];
			$data_template[$i]['list'][$c]['col1'] = $value['col1'];
			$data_template[$i]['list'][$c]['std_resolution'] = $value['std_resolution'];
			$data_template[$i]['list'][$c]['min'] = $value['min'];
			$data_template[$i]['list'][$c]['max'] = $value['max'];
			$data_template[$i]['list'][$c]['list'] = $value['list'];
            $data_template[$i]['list'][$c]['sublist'] = $value['sublist'];
            $data_template[$i]['list'][$c]['accept_range'] = $value['accept_range'];


            $choiceid = $value['choiceid'];
		}

	}

}

$choiceDetailList = [];
if(!empty($a_data2)){

    foreach ($a_data2 as $key => $value) {
        $choiceDetailList[$value['choiceid']][] = [
            'sequence_detail' => $value['sequence_detail'],
            'value' => $value['list2']
        ];
    }

}

$smarty->assign("DATA_TEMPLATE",$data_template);
$smarty->assign("DATA_TEMPLATE8",$choiceDetailList);


//Check Data Setup/Send
/*$pquery = " select a.relcrmid
	from aicrm_inspectiontemplaterel a
	Inner Join aicrm_inspectiontemplate b on b.inspectiontemplateid = a.inspectiontemplateid
	where a.inspectiontemplateid = '".$focus->id."'	";
$count_questionnaire=$adb->pquery($pquery, array());
$count = $adb->num_rows($count_questionnaire);*/

$inspectiontemplateid = $focus->id;

$smarty->display("Inspectiontemplate/InventoryDetailView.tpl");
?>
