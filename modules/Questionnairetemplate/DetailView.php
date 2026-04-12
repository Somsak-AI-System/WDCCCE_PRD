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
    $focus->retrieve_entity_info($_REQUEST['record'],"Questionnairetemplate");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Questionnairetemplate detail view");

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

$smarty->assign("SINGLE_MOD",'Questionnairetemplate');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Questionnairetemplate","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

	
$smarty->assign("CREATEPDF","permitted");

if(isPermitted("SalesOrder","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTSALESORDER","permitted");

if(isPermitted("Invoice","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTINVOICE","permitted");

if(isPermitted("Questionnairetemplate","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Questionnairetemplate',$focus));

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

 $tabid = getTabid("Questionnairetemplate");
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
//Added to display the service comments information
$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}
// Record Change Notification
$focus->markAsViewed($current_user->id);
// END

$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', true));

//$smarty->display("DetailView.tpl");
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;
				
	$pquery = "SELECT  aicrm_questionnairetemplate_page.*, aicrm_questionnairetemplate_choice.* ,aicrm_questionnairetemplate_choicedetail.*
	FROM aicrm_questionnairetemplate
	left join aicrm_questionnairetemplate_page on aicrm_questionnairetemplate_page.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid 
	left join aicrm_questionnairetemplate_choice on aicrm_questionnairetemplate_choice.pageid = aicrm_questionnairetemplate_page.pageid
	left join aicrm_questionnairetemplate_choicedetail on aicrm_questionnairetemplate_choicedetail.choiceid = aicrm_questionnairetemplate_choice.choiceid
	WHERE aicrm_questionnairetemplate.questionnairetemplateid = '".$focus->id."' order by aicrm_questionnairetemplate_page.pageid asc, aicrm_questionnairetemplate_choice.choiceid asc , aicrm_questionnairetemplate_choicedetail.choicedetailid asc";
	$a_data = $myLibrary_mysqli->select($pquery);
	//echo "<pre>"; print_r ($a_data) ;echo "</pre>"; exit;
	$data_template = array();
	$data_template['title'] = $a_data[0]['title_questionnaire'];
	$pageid = '';
	$i=-1;$c=0;
	foreach($a_data as $key => $val){
		if($pageid != $val['pageid']){
			$c=0;
			$i++;	
			$data_template["pages"][$i]['name'] = $val['name_page'];
			$data_template["pages"][$i]['title'] = $val['title_page'];
			if($val['choicedetail_other'] == 1){
				$data_template["pages"][$i]['otherText']  = $val['choicedetail_name'] ; //choicedetail_name
			}
			$data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
			$data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
			$data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
			$data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
			$data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
			if($val['choicedetail_other'] == 1){
				$data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
			}else if($val['choice_type'] != 'text'){
				$k=0;
				$data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
			} 
			
			$pageid = $val['pageid'];
			$choiceid = $val['choiceid'];
			//$i++;	
		}else if($pageid == $val['pageid']){
				
				if($choiceid != $val['choiceid']){
					$c++;
					$data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
					$data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
					$data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
					$data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
					$data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
					if($val['choicedetail_other'] == 1){
						$data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
					}else if($val['choice_type'] != 'text'){
						$k=0;
						$data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
					}
				}else{
					if($val['choicedetail_other'] == 1){
						$data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
					}else if($val['choice_type'] != 'text'){
						$k++;
						$data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
					}
				}
				
				$pageid = $val['pageid'];
				$choiceid = $val['choiceid'];
		}
	}//foreach
	$data_template= htmlspecialchars(json_encode($data_template), ENT_QUOTES, 'UTF-8');
	$smarty->assign("DATA_TEMPLATE",$data_template);
//echo json_encode($data_template); exit;
//Check Data Setup/Send
$pquery = " select a.relcrmid 
				  from aicrm_questionnairetemplaterel a
			      Inner Join aicrm_questionnairetemplate b on b.questionnairetemplateid = a.questionnairetemplateid
				  where a.questionnairetemplateid = '".$focus->id."'	";

$count_questionnaire=$adb->pquery($pquery, array());
$count = $adb->num_rows($count_questionnaire);

/*$pquery = "select questionnairetemplate_setup,questionnairetemplate_send ,questionnairetemplate_status from aicrm_questionnairetemplate where questionnairetemplateid = '".$focus->id."' ";
$cf_res = $adb->pquery($pquery,'');
$questionnairetemplate_setup=$adb->query_result($cf_res, 0, "questionnairetemplate_setup");
$questionnairetemplate_send=$adb->query_result($cf_res, 0, "questionnairetemplate_send");
$questionnairetemplate_status=$adb->query_result($cf_res, 0, "questionnairetemplate_status");*/
$questionnairetemplateid=$focus->id;



$smarty->display("Questionnairetemplate/InventoryDetailView.tpl");

?>