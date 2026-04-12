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

    $focus->retrieve_entity_info($_REQUEST['record'],"Inspection");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $focus->id = "";
}


$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Inspection detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

if (isset($focus->column_fields['inspection_name'])) $smarty->assign("NAME", $focus->column_fields['inspection_name']);
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
$smarty->assign("SINGLE_MOD",'Inspection');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Inspection","EditView",$_REQUEST['record']) == 'yes')
    $smarty->assign("EDIT_DUPLICATE","permitted");


$smarty->assign("CREATEPDF","permitted");

if(isPermitted("SalesOrder","EditView",$_REQUEST['record']) == 'yes')
    $smarty->assign("CONVERTSALESORDER","permitted");

if(isPermitted("Invoice","EditView",$_REQUEST['record']) == 'yes')
    $smarty->assign("CONVERTINVOICE","permitted");

if(isPermitted("Inspection","Delete",$_REQUEST['record']) == 'yes')
    $smarty->assign("DELETE","permitted");


$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Inspection',$focus));

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$tabid = getTabid("Inspection");
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
    //echo $_SESSION[$currentModule.'_listquery'];
    $recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
    //print_r($recordNavigationInfo);
    VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}

//echo "<pre>"; print_r($focus->column_fields['inspectiontemplateid']); echo "</pre>"; exit;

include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

if(isset($focus->column_fields['inspectiontemplateid']) && $focus->column_fields['inspectiontemplateid'] != 0){

    $pquery = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.* 
	FROM aicrm_inspectiontemplate
	INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
	LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
	-- LEFT JOIN aicrm_inspection_answer on aicrm_inspection_answer.inspectionid = aicrm_inspectiontemplate.inspectiontemplateid
	WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$focus->column_fields['inspectiontemplateid']."' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";

    $a_data = $myLibrary_mysqli->select($pquery);

    $pquery2 = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.*, aicrm_inspectiontemplate_choicedetail2.* 
    FROM aicrm_inspectiontemplate
    INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
    LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
    INNER JOIN aicrm_inspectiontemplate_choicedetail2 ON aicrm_inspectiontemplate_choicedetail.choicedetailid = aicrm_inspectiontemplate_choicedetail2.choiceid 
    WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$focus->column_fields['inspectiontemplateid']."' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";
    $a_data2 = $myLibrary_mysqli->select($pquery2);

    $pquery_answer = "SELECT  aicrm_inspection_answer.*
	FROM aicrm_inspection_answer
	INNER JOIN aicrm_inspection on aicrm_inspection.inspectionid = aicrm_inspection_answer.inspectionid 
	LEFT JOIN aicrm_inspectiontemplate on aicrm_inspectiontemplate.inspectiontemplateid = aicrm_inspection_answer.inspectiontemplateid
	WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$focus->column_fields['inspectiontemplateid']."' and aicrm_inspection.inspectionid = '".$focus->id."' order by aicrm_inspection_answer.choiceid , aicrm_inspection_answer.choicedetailid asc ";
//    echo $pquery_answer;exit;
    $a_data_answer = $myLibrary_mysqli->select($pquery_answer);
//    echo "<pre>"; print_r($a_data_answer); echo "</pre>"; exit;
    $answer = array();

    if(!empty($a_data_answer)){
        foreach ($a_data_answer as $key => $value) {
            $average = 0;
            $averag_val = 0;
            if($value['choice_type'] == 'calibrate'){
                if($value['data_col1'] != '0.00'){
                    $average++;
                }
                if($value['data_col2'] != '0.00'){
                    $average++;
                }
                if($value['data_col3'] != '0.00'){
                    $average++;
                }
                if($value['data_col4'] != '0.00'){
                    $average++;
                }
                if($value['data_col5'] != '0.00'){
                    $average++;
                }
                if($value['data_col6'] != '0.00'){
                    $average++;
                }
                if($value['data_col7'] != '0.00'){
                    $average++;
                }
                if($value['data_col8'] != '0.00'){
                    $average++;
                }

                $averag_val = (($value['data_col1']+$value['data_col2']+$value['data_col3']+$value['data_col4']+$value['data_col5']+$value['data_col6']+$value['data_col7']+$value['data_col8'])/$average);
                //echo number_format($averag_val,2); echo "<br>";
            }
            $value['average']= number_format($averag_val,2);
            //echo $average; echo "<br>";
            $answer[$value['choiceid']][$value['choicedetailid']] = $value;
        }
    }
    //echo "<pre>"; print_r($answer); echo "</pre>";exit;
    $data_template = array();
    //echo "<pre>"; print_r($a_data); echo "</pre>";
    if(!empty($a_data)){

        $choiceid = '';
        $i= -1;
        foreach ($a_data as $key => $value) {

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

                $data_template[$i]['list'][$c]['choicedetailid'] = $value['choicedetailid'];
                $data_template[$i]['list'][$c]['sequence_detail'] = $value['sequence_detail'];
                $data_template[$i]['list'][$c]['col0'] = $value['col0'];
                $data_template[$i]['list'][$c]['col1'] = $value['col1'];
                $data_template[$i]['list'][$c]['std_resolution'] = $value['std_resolution'];
                $data_template[$i]['list'][$c]['min'] = $value['min'];
                $data_template[$i]['list'][$c]['max'] = $value['max'];
                $data_template[$i]['list'][$c]['list'] = $value['list'];
                $data_template[$i]['list'][$c]['sublist'] = $value['sublist'];

                $data_template[$i]['list'][$c]['list2'] = $value['list2_template10'];

                $data_template[$i]['list'][$c]['answer'] = $answer[$value['choiceid']][$value['choicedetailid']];
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

                $data_template[$i]['list'][$c]['list2'] = $value['list2_template10'];

                $data_template[$i]['list'][$c]['answer'] = $answer[$value['choiceid']][$value['choicedetailid']];
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

}
//alert($choiceDetailList);exit;
//echo json_encode($data_template);exit;
//echo "<pre>"; print_r($data_template); echo "</pre>"; //exit;
// Record Change Notification
$focus->markAsViewed($current_user->id);
// END
$smarty->assign("CONVERTFLAG",$convertsttflag);
$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', false));
//$smarty->display("DetailView.tpl");
$smarty->display("Inspection/InventoryDetailView.tpl");
?>