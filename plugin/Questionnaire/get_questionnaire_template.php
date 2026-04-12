<?php
header('Content-Type: text/html; charset=utf-8');
include("../../config.inc.php");
include("../../library/dbconfig.php");
require_once("../../library/general.php");
require_once("../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

	/*if($_REQUEST["branchid"] != ""){
        $branchid = $_REQUEST["branchid"];
    }*/
    if($_REQUEST["questionnairetemplateid"] != ""){
    	$questionnairetemplateid = $_REQUEST["questionnairetemplateid"];
    }

    $pquery = "SELECT  aicrm_questionnairetemplate_page.*, aicrm_questionnairetemplate_choice.* ,aicrm_questionnairetemplate_choicedetail.*
	FROM aicrm_questionnairetemplate
	left join aicrm_questionnairetemplate_page on aicrm_questionnairetemplate_page.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid 
	left join aicrm_questionnairetemplate_choice on aicrm_questionnairetemplate_choice.pageid = aicrm_questionnairetemplate_page.pageid
	left join aicrm_questionnairetemplate_choicedetail on aicrm_questionnairetemplate_choicedetail.choiceid = aicrm_questionnairetemplate_choice.choiceid
	WHERE aicrm_questionnairetemplate.questionnairetemplateid = '".$questionnairetemplateid."' ";
	$a_data = $myLibrary_mysqli->select($pquery);

	if(empty($a_data)){
    	$result['type'] = 'E';
    	$result['data'] = '';
    	$result['msg'] = 'ไม่พบข้อมูลแบบสอบถาม';
    	echo json_encode($result);
    	return false;
    }

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
	
    $result['type'] = 'S';
    $result['data'] = $data_template;
    $result['msg'] = '';

	echo json_encode($result);

?>
