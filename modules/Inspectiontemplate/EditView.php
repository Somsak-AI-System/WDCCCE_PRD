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
require_once('Smarty_setup.php');
require_once('include/CustomFieldUtil.php');
require_once('modules/Inspectiontemplate/Inspectiontemplate.php');

global $app_strings,$mod_strings,$currentModule,$theme;

$focus = CRMEntity::getInstance($currentModule);
$smarty = new vtigerCRM_Smarty();
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends


if(isset($_REQUEST['record']) && $_REQUEST['record'] !='') 
{
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit'; 	
	$focus->retrieve_entity_info($_REQUEST['record'],"Inspectiontemplate");
	$focus->name=$focus->column_fields['Inspectiontemplate_name'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
    $focus->mode = ''; 	
	
	//Duplicate	
	include("config.inc.php");
	include_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
				
	$pquery = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.*
	FROM aicrm_inspectiontemplate
	INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
	LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
	WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$_REQUEST['record']."' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";
	$a_data = $myLibrary_mysqli->select($pquery);

    $pquery2 = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.*, aicrm_inspectiontemplate_choicedetail2.* 
FROM aicrm_inspectiontemplate
INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
INNER JOIN aicrm_inspectiontemplate_choicedetail2 ON aicrm_inspectiontemplate_choicedetail.choicedetailid = aicrm_inspectiontemplate_choicedetail2.choiceid 
WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$focus->id."' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";
    $a_data2 = $myLibrary_mysqli->select($pquery2);

//echo '<pre>';print_r($a_data2);exit;

	$data_template = array();
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
				$data_template[$i]['head_col1'] = $value['head_col1'];
				$data_template[$i]['head_col2'] = $value['head_col2'];
				$data_template[$i]['head_col3'] = $value['head_col3'];
				$data_template[$i]['head_col4'] = $value['head_col4'];
				$data_template[$i]['head_col5'] = $value['head_col5'];
				$data_template[$i]['head_col6'] = $value['head_col6'];
				$data_template[$i]['head_col7'] = $value['head_col7'];
				$data_template[$i]['head_col8'] = $value['head_col8'];
				$data_template[$i]['head_col9'] = $value['head_col9'];

				$data_template[$i]['list'][$c]['choicedetailid'] = $value['choicedetailid'];
				$data_template[$i]['list'][$c]['sequence_detail'] = $value['sequence_detail'];
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
		
}

if(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	
	$focus->column_fields['inspectiontemplate_status'] = "Active";
	$focus->column_fields['start_date'] = date('Y-m-d');
	setObjectValuesFromRequest($focus);
}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields));
else	
{
	$bas_block = getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'BAS');
	$adv_block = getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'ADV');
	$blocks['basicTab'] = $bas_block;
	if(is_array($adv_block ))
		$blocks['moreTab'] = $adv_block;
//	echo '<pre>'; print_r($bas_block); echo '</pre>'; exit();
	$smarty->assign("BLOCKS",$blocks);
	$smarty->assign("BLOCKS_COUNT",count($blocks));
}
	
$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Inspection Template');


$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);

if (isset($focus->name)) 
$smarty->assign("NAME", $focus->name);
else 
$smarty->assign("NAME", "");

if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}
$smarty->assign("ID", $focus->id);
if($focus->mode == 'edit')
{
    $smarty->assign("MODE", $focus->mode);
    $smarty->assign("OLDSMOWNERID", $focus->column_fields['assigned_user_id']);
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
}

if(isset($_REQUEST['return_module'])) 
$smarty->assign("RETURN_MODULE", vtlib_purify($_REQUEST['return_module']));
if(isset($_REQUEST['return_action'])) 
$smarty->assign("RETURN_ACTION", vtlib_purify($_REQUEST['return_action']));
if(isset($_REQUEST['return_id'])) 
$smarty->assign("RETURN_ID", vtlib_purify($_REQUEST['return_id']));
if(isset($_REQUEST['product_id'])) 
$smarty->assign("PRODUCTID", vtlib_purify($_REQUEST['product_id']));
if (isset($_REQUEST['return_viewname'])) 
$smarty->assign("RETURN_VIEWNAME", vtlib_purify($_REQUEST['return_viewname']));
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$tabid = getTabid("Inspectiontemplate");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$validationDataUitype = getDBValidationDataUitype($focus->tab_name,$tabid);
$dataUitype = split_validationdataUitype($validationDataUitype);

$smarty->assign("VALIDATION_DATA_UITYPE",$dataUitype['datauitype']);
$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);
$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("DUPLICATE",vtlib_purify($_REQUEST['isDuplicate']));

global $adb;
// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if($focus->mode != 'edit' && $mod_seq_field != null) {
		$Inspectiontemplatestr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
		$mod_seq_string = $adb->pquery("SELECT prefix, cur_id from aicrm_modentity_num where semodule = ? and active=1",array($currentModule));
        $mod_seq_prefix = $adb->query_result($mod_seq_string,0,'prefix');
        $mod_seq_no = $adb->query_result($mod_seq_string,0,'cur_id');
        if($adb->num_rows($mod_seq_string) == 0 || $focus->checkModuleSeqNumber($focus->table_name, $mod_seq_field['column'], $mod_seq_prefix.$mod_seq_no))
                echo '<br><font color="#FF0000"><b>'. getTranslatedString('LBL_DUPLICATE'). ' '. getTranslatedString($mod_seq_field['label'])
                	.' - '. getTranslatedString('LBL_CLICK') .' <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings&selmodule='.$currentModule.'">'.getTranslatedString('LBL_HERE').'</a> '
                	. getTranslatedString('LBL_TO_CONFIGURE'). ' '. getTranslatedString($mod_seq_field['label']) .'</b></font>';
        else
                $smarty->assign("MOD_SEQ_ID",$Inspectiontemplatestr);
} else {
	$smarty->assign("MOD_SEQ_ID", $focus->column_fields[$mod_seq_field['name']]);
}
// END

if($focus->mode == 'edit') {
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

    $pquery2 = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.*, aicrm_inspectiontemplate_choicedetail2.* 
FROM aicrm_inspectiontemplate
INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
INNER JOIN aicrm_inspectiontemplate_choicedetail2 ON aicrm_inspectiontemplate_choicedetail.choicedetailid = aicrm_inspectiontemplate_choicedetail2.choiceid 
WHERE aicrm_inspectiontemplate.inspectiontemplateid = '".$focus->id."' order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc ";
    $a_data2 = $myLibrary_mysqli->select($pquery2);

	$data_template = array();
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

}
//echo"<pre>"; print_r($focus->mode);echo"</pre>";exit;
if($focus->mode == 'edit') {
	$smarty->assign("DATA_TEMPLATE",$data_template);
    $smarty->assign("DATA_TEMPLATE8",$choiceDetailList);
	$smarty->display('Inspectiontemplate/InventoryEditView.tpl');
} else {
//    echo"<pre>"; print_r('Create');echo"</pre>"; exit;
	$smarty->display('Inspectiontemplate/InventoryCreateView.tpl');
}
?>