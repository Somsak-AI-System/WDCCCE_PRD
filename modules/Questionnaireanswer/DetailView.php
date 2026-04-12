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
    $focus->retrieve_entity_info($_REQUEST['record'],"Questionnaireanswer");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['subject'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Errors list detail view");

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

$smarty->assign("SINGLE_MOD",'Questionnaireanswer');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Questionnaireanswer","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

	
$smarty->assign("CREATEPDF","permitted");

if(isPermitted("Questionnaireanswer","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


$smarty->assign("CONVERTMODE",'quotetoinvoice');
$smarty->assign("MODULE", $currentModule);

//Get the associated Products and then display above Terms and Conditions
$smarty->assign("ASSOCIATED_PRODUCTS",getDetailAssociatedProducts('Questionnaireanswer',$focus));

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

 $tabid = getTabid("Questionnaireanswer");
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
//echo "<pre>"; print_r($focus->getCommentInformation($_REQUEST['record'])); echo "</pre>"; exit;
$smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}
// Record Change Notification
$focus->markAsViewed($current_user->id);
// END

//$quoteid_id= $focus->id;
$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', true));
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$pquery = "SELECT  aicrm_questionnaire_page.*, aicrm_questionnaire_choice.* ,aicrm_questionnaire_choicedetail.*
FROM aicrm_questionnaireanswer
left join aicrm_questionnaire_page on aicrm_questionnaire_page.questionnaireid = aicrm_questionnaireanswer.questionnaireanswerid 
left join aicrm_questionnaire_choice on aicrm_questionnaire_choice.pageid = aicrm_questionnaire_page.pageid
left join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
WHERE aicrm_questionnaireanswer.questionnaireanswerid = '".$focus->id."' order by aicrm_questionnaire_page.pageid asc, aicrm_questionnaire_choice.choiceid asc , aicrm_questionnaire_choicedetail.choicedetailid asc";

$a_data = $myLibrary_mysqli->select($pquery);
// echo "<pre>"; print_r($a_data); echo "</pre>"; exit;
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
			$data_template["pages"][$i]['elements'][$c]['choices'][$k]['text'] = $val['choicedetail_name'];
			$data_template["pages"][$i]['elements'][$c]['choices'][$k]['value'] = $val['choicedetail_value'];
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
					$data_template["pages"][$i]['elements'][$c]['choices'][$k]['text'] = $val['choicedetail_name'];
					$data_template["pages"][$i]['elements'][$c]['choices'][$k]['value'] = $val['choicedetail_value'];
				}
			}else{
				if($val['choicedetail_other'] == 1){
					$data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
				}else if($val['choice_type'] != 'text'){
					$k++;
					$data_template["pages"][$i]['elements'][$c]['choices'][$k]['text'] = $val['choicedetail_name'];
					$data_template["pages"][$i]['elements'][$c]['choices'][$k]['value'] = $val['choicedetail_value'];
				}
			}
			
			$pageid = $val['pageid'];
			$choiceid = $val['choiceid'];
	}
}//foreach

$data_template= htmlspecialchars(json_encode($data_template), ENT_QUOTES, 'UTF-8');
$smarty->assign("DATA_TEMPLATE",$data_template);
	$awnser = "SELECT aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choice.choice_name , 
		aicrm_questionnaire_choice.choice_type , 
		aicrm_questionnaire_choice.hasother ,
		aicrm_questionnaire_choicedetail.choicedetail_name ,
		aicrm_questionnaire_choicedetail.choicedetail_other ,
		aicrm_questionnaire_answer.choicedetail
	FROM aicrm_questionnaire_answer
	inner join aicrm_questionnaire_choice on aicrm_questionnaire_choice.choiceid = aicrm_questionnaire_answer.choiceid
	inner join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choicedetailid = aicrm_questionnaire_answer.choicedetailid
	WHERE aicrm_questionnaire_answer.questionnaireid = '".$focus->id."' order by aicrm_questionnaire_choice.choiceid asc , aicrm_questionnaire_choicedetail.choicedetailid asc";
	//echo $awnser; exit;
$a_awnser = $myLibrary_mysqli->select($awnser);

if(!empty($a_awnser)){
	$d_awnser = array();
	$choiceid = '';
	foreach ($a_awnser as $key => $value) {
		//echo "<pre>"; print_r($value); echo "</pre>"; exit;
		if($value['choiceid'] == $choiceid){

			if($value['choice_type'] == 'checkbox'){
				if($value['choicedetail_other']  == '1'){
					array_push($d_awnser[$value['choice_name']], 'other');
					$d_awnser[$value['choice_name'].'-Comment']= $value['choicedetail'];
				}else{
					array_push($d_awnser[$value['choice_name']],$value['choicedetail']);
				}
			}
			$choiceid = $value['choiceid'];

		}else{

			if($value['choice_type'] == 'checkbox'){

				$d_awnser[$value['choice_name']] = array();
				if($value['choicedetail_other']  == '1'){
					array_push($d_awnser[$value['choice_name']], 'other');
					$d_awnser[$value['choice_name'].'-Comment']= $value['choicedetail'];
				}else{
					array_push($d_awnser[$value['choice_name']],$value['choicedetail']);
				}

			}else{

				if($value['choicedetail_other']  == '1'){

					$d_awnser[$value['choice_name']] = 'other';
					$d_awnser[$value['choice_name'].'-Comment']= $value['choicedetail'];
				}else{
					$d_awnser[$value['choice_name']] = $value['choicedetail'];
				}

			}

			$choiceid = $value['choiceid'];
		}//if
	
	}//foreach
}//if empty
$d_awnser= htmlspecialchars(json_encode($d_awnser), ENT_QUOTES);
$smarty->assign("DATA_ANSWER",$d_awnser);

$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', true));
$smarty->display("Questionnaireanswer/InventoryDetailView.tpl");

?>