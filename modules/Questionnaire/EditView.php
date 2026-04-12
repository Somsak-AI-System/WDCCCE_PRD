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
require_once('modules/Questionnaire/Questionnaire.php');

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
    $focus->retrieve_entity_info($_REQUEST['record'],"Questionnaire");
    $focus->name=$focus->column_fields['Questionnaire_name'];       
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
    $focus->id = "";
    $focus->mode = '';  
    
    include("config.inc.php");
    include_once("library/dbconfig.php");
    include_once("library/myLibrary_mysqli.php");
    $myLibrary_mysqli = new myLibrary_mysqli();
    $myLibrary_mysqli->_dbconfig = $dbconfig;

    $pquery = "SELECT  aicrm_questionnaire_page.*, aicrm_questionnaire_choice.* ,aicrm_questionnaire_choicedetail.*
    FROM aicrm_questionnaire
    left join aicrm_questionnaire_page on aicrm_questionnaire_page.questionnaireid = aicrm_questionnaire.questionnaireid 
    left join aicrm_questionnaire_choice on aicrm_questionnaire_choice.pageid = aicrm_questionnaire_page.pageid
    left join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
    WHERE aicrm_questionnaire.questionnaireid = '".$_REQUEST['record']."' ";
    $a_data = $myLibrary_mysqli->select($pquery);

    $data_template = array();
    $d_awnser = array();

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
            $data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
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
                /*$data_template["pages"][$i]['elements'][$c]['choices'][$k] = array(
                    'choicedetailid' => $val['choicedetailid'],
                    'choicedetail_name' => $val['choicedetail_name'],
                );*/
            } 
            
            $pageid = $val['pageid'];
            $choiceid = $val['choiceid'];
            //$i++; 
        }else if($pageid == $val['pageid']){
                
                if($choiceid != $val['choiceid']){
                    $c++;
                    $data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
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

    $awnser = "SELECT aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choice.choice_name , 
        aicrm_questionnaire_choice.choice_type , 
        aicrm_questionnaire_choice.hasother ,
        aicrm_questionnaire_choicedetail.choicedetail_name ,
        aicrm_questionnaire_choicedetail.choicedetail_other ,
        aicrm_questionnaire_answer.choicedetail
    FROM aicrm_questionnaire_answer
    inner join aicrm_questionnaire_choice on aicrm_questionnaire_choice.choiceid = aicrm_questionnaire_answer.choiceid
    inner join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choicedetailid = aicrm_questionnaire_answer.choicedetailid
    WHERE aicrm_questionnaire_answer.questionnaireid = '".$_REQUEST['record']."' order by aicrm_questionnaire_choice.choiceid";
    $a_awnser = $myLibrary_mysqli->select($awnser);

    if(!empty($a_awnser)){
       
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

    $d_awnser = htmlspecialchars(json_encode($d_awnser), ENT_QUOTES);
    $smarty->assign("DATA_TEMPLATE",$data_template);
    $smarty->assign("DATA_ANSWER",$d_awnser);

}

if(empty($_REQUEST['record']) && $focus->mode != 'edit'){ 
    //$focus->column_fields['questionnaire_date'] = date('Y-m-d');
    //$focus->column_fields['questionnaire_time'] = date('H:i');
    setObjectValuesFromRequest($focus);
}

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Projects' && $_REQUEST['return_id'] != '' ){
    require_once('modules/Projects/Projects.php');
    $pro_focus = new Projects();
    $pro_focus->retrieve_entity_info($_REQUEST['return_id'], "Projects");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['event_id'] = $_REQUEST['return_id'];
    $focus->column_fields['account_id'] = $pro_focus->column_fields['account_id'];
}else if(isset($_REQUEST['related_module']) && $_REQUEST['related_module'] == 'Projects' && $_REQUEST['related_id'] != '' ){
    require_once('modules/Projects/Projects.php');
    $pro_focus = new Projects();
    $pro_focus->retrieve_entity_info($_REQUEST['related_id'], "Projects");
    setObjectValuesFromRequest($focus);
    $focus->column_fields['event_id'] = $_REQUEST['related_id'];
    $focus->column_fields['account_id'] = $pro_focus->column_fields['account_id'];
}

if(isset($_REQUEST['type']) && $_REQUEST['type'] !='') 
{
    $focus->column_fields['questionnaire_type'] = $_REQUEST['type'];   
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
    
    $smarty->assign("BLOCKS",$blocks);
    $smarty->assign("BLOCKS_COUNT",count($blocks));
}
    
$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Questionnaire');


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

$tabid = getTabid("Questionnaire");
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
if($_REQUEST['record'] != '')
{
        //Added to display the service comments information
        $smarty->assign("COMMENT_BLOCK",$focus->getCommentInformation($_REQUEST['record']));
}

global $adb;
// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if($focus->mode != 'edit' && $mod_seq_field != null) {
        $autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
        $mod_seq_string = $adb->pquery("SELECT prefix, cur_id from aicrm_modentity_num where semodule = ? and active=1",array($currentModule));
        $mod_seq_prefix = $adb->query_result($mod_seq_string,0,'prefix');
        $mod_seq_no = $adb->query_result($mod_seq_string,0,'cur_id');
        if($adb->num_rows($mod_seq_string) == 0 || $focus->checkModuleSeqNumber($focus->table_name, $mod_seq_field['column'], $mod_seq_prefix.$mod_seq_no))
                echo '<br><font color="#FF0000"><b>'. getTranslatedString('LBL_DUPLICATE'). ' '. getTranslatedString($mod_seq_field['label'])
                    .' - '. getTranslatedString('LBL_CLICK') .' <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings&selmodule='.$currentModule.'">'.getTranslatedString('LBL_HERE').'</a> '
                    . getTranslatedString('LBL_TO_CONFIGURE'). ' '. getTranslatedString($mod_seq_field['label']) .'</b></font>';
        else
                $smarty->assign("MOD_SEQ_ID",$autostr);
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

    $pquery = "SELECT  aicrm_questionnaire_page.*, aicrm_questionnaire_choice.* ,aicrm_questionnaire_choicedetail.*
    FROM aicrm_questionnaire
    left join aicrm_questionnaire_page on aicrm_questionnaire_page.questionnaireid = aicrm_questionnaire.questionnaireid 
    left join aicrm_questionnaire_choice on aicrm_questionnaire_choice.pageid = aicrm_questionnaire_page.pageid
    left join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
    WHERE aicrm_questionnaire.questionnaireid = '".$focus->id."' ";
    $a_data = $myLibrary_mysqli->select($pquery);

    $data_template = array();
    $d_awnser = array();

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
            $data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
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
                /*$data_template["pages"][$i]['elements'][$c]['choices'][$k] = array(
                    'choicedetailid' => $val['choicedetailid'],
                    'choicedetail_name' => $val['choicedetail_name'],
                );*/
            } 
            
            $pageid = $val['pageid'];
            $choiceid = $val['choiceid'];
            //$i++; 
        }else if($pageid == $val['pageid']){
                
                if($choiceid != $val['choiceid']){
                    $c++;
                    $data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
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

    $awnser = "SELECT aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choice.choice_name , 
        aicrm_questionnaire_choice.choice_type , 
        aicrm_questionnaire_choice.hasother ,
        aicrm_questionnaire_choicedetail.choicedetail_name ,
        aicrm_questionnaire_choicedetail.choicedetail_other ,
        aicrm_questionnaire_answer.choicedetail
    FROM aicrm_questionnaire_answer
    inner join aicrm_questionnaire_choice on aicrm_questionnaire_choice.choiceid = aicrm_questionnaire_answer.choiceid
    inner join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choicedetailid = aicrm_questionnaire_answer.choicedetailid
    WHERE aicrm_questionnaire_answer.questionnaireid = '".$focus->id."' order by aicrm_questionnaire_choice.choiceid";
    $a_awnser = $myLibrary_mysqli->select($awnser);

    if(!empty($a_awnser)){
       
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

    $d_awnser = htmlspecialchars(json_encode($d_awnser), ENT_QUOTES);
}

$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($currentModule);
$smarty->assign("PICKIST_DEPENDENCY_DATASOURCE", Zend_Json::encode($picklistDependencyDatasource));

if($focus->mode == 'edit') {
    $smarty->assign("DATA_TEMPLATE",$data_template);
    $smarty->assign("DATA_ANSWER",$d_awnser);
    $smarty->display('Questionnaire/InventoryEditView.tpl');
} else {
    $smarty->display('Questionnaire/InventoryCreateView.tpl');
}

?>