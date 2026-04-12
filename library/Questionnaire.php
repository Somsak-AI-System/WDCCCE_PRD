<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '2048M');

include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
include_once("library/generate_MYSQLi.php");

class libquestionnaire{
	public $_dbconfig;
	public $_page;

	public function __construct($dbconfig){
		global  $list_max_entries_per_page;
		$this->_dbconfig = $dbconfig;
		$this->generate = new generate($this->_dbconfig  ,"DB");
		$this->list_max_entries_per_page =$list_max_entries_per_page;
	}
	
	public function  get_server_name()
	{
		return  gethostbyaddr($_SERVER['REMOTE_ADDR']);
	}
	
	public function save_questionnaire($data=array(),$questionnaireid=NULL,$action=NULL,$questionnaire_answer=array(), $questionnairetemplateid=NULL){
		
		$myLibrary_mysqli = new myLibrary_mysqli();
		$myLibrary_mysqli->_dbconfig = $this->_dbconfig;

		$d_template = array();

		//Inset Template
		if($action != 'edit'){
			//Action Add
			$title_questionnaire = $data['title'];
			foreach($data['pages'] as $key => $val){
				//inset tabe aicrm_questionnaire_page
				$sql = "insert into aicrm_questionnaire_page (questionnaireid,title_questionnaire,title_page,name_page,sequence_page) ";
				$sql .= " VALUES ('".$questionnaireid."','".$title_questionnaire."','".$val['title']."','".$val['name']."','".($key +1)."'); ";

				$this->generate->query($sql);
				$pageid = $this->generate->con->insert_id;
				
				foreach($val['elements'] as $k => $v){
					//inset tabe aicrm_questionnaire_choice
					$hasOther = (isset($v['hasOther']) && $v['hasOther'] == true) ? 1 : 0;
					$isRequired =(isset($v['isRequired']) && $v['isRequired'] == true) ? 1 : 0;

					$sql_choice = "insert into aicrm_questionnaire_choice (questionnaireid,choice_type,pageid,choice_title,choice_name,sequence,hasother,required) ";
					$sql_choice .= " VALUES ('".$questionnaireid."','".$v['type']."','".$pageid."','".@$v['title']."','".@$v['name']."','".($k+1)."','".$hasOther."','".$isRequired."'); ";
					
					$this->generate->query($sql_choice);
					$choiceid = $this->generate->con->insert_id;
				
					if($v['type'] == 'text'){
						//inset tabe aicrm_questionnaire_choicedetail (Type Text)
						$sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
						$sql_choicedetail .= " VALUES ('".$questionnaireid."','".$choiceid."','','1','0'); ";
						
						$this->generate->query($sql_choicedetail);
						$kc++;
					}else{
						$kc = 1 ;
						foreach($v['choices'] as $kchoice => $choice){

							if(is_array($choice)){
								$value = $choice['value'];
								$text = $choice['text'] ;
							}else{
								$value = $choice ;
								$text = $choice ;
							}
							//inset tabe aicrm_questionnaire_choicedetail
							$sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_value,choicedetail_name,sequence_detail,choicedetail_other) ";
							$sql_choicedetail .= " VALUES ('".$questionnaireid."','".$choiceid."','".$value."','".$text."','".($kchoice+1)."','0'); ";

							$this->generate->query($sql_choicedetail);
							$kc++;
						}
						if(isset($v['otherText']) && $v['otherText'] != ''){
							$sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
							$sql_choicedetail .= " VALUES ('".$questionnaireid."','".$choiceid."','".$v['otherText']."','".$kc."','1'); ";	
							
							$this->generate->query($sql_choicedetail);
						}
					}//else
				}//foreach elements
			}//foreach pages
		}

		//Select Template
		foreach($data['pages'] as $key => $value) {
			
			foreach($value['elements'] as $k1 => $v1) {

				$a_choice = '';
				$v1['questionnaireid'] = $questionnaireid;
				$v1['answer'] = '';
				
				if(isset($questionnaire_answer[$v1['name']]) && $questionnaire_answer[$v1['name']] != ''){
					
					if($v1['type'] == 'text'){
 						
 						$select_choice = "SELECT aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choicedetail.choicedetailid ,aicrm_questionnaire_choicedetail.choicedetail_other
						FROM aicrm_questionnaire
						INNER JOIN aicrm_questionnaire_choice on aicrm_questionnaire_choice.questionnaireid = aicrm_questionnaire.questionnaireid
						INNER JOIN aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
						WHERE aicrm_questionnaire.questionnaireid = '".$questionnaireid."' and aicrm_questionnaire_choice.choice_name = '".$v1['name']."' ";

						$a_choice = $myLibrary_mysqli->select($select_choice);
						$v1['answer'] = $questionnaire_answer[$v1['name']];

						if(!empty($a_choice)){
							$v1['choiceid'] = $a_choice[0]['choiceid'];
							$v1['choicedetailid'] = $a_choice[0]['choicedetailid'];
							$v1['choicedetail_other'] = $a_choice[0]['choicedetail_other'];
						}else{
							$v1['choiceid'] = '';
							$v1['choicedetailid'] = '';
							$v1['hasother'] = '';
						}

					}else if($v1['type']== 'checkbox'){

						$checkbox = array();
						foreach ($questionnaire_answer[$v1['name']] as $K_checkbox => $v1_checkbox) {
							
							if($v1_checkbox == 'other' && $v1['hasOther'] == 1){

								$select_choice = "SELECT  aicrm_questionnaire_choice.choiceid, aicrm_questionnaire_choicedetail.choicedetailid ,aicrm_questionnaire_choicedetail.choicedetail_other
								FROM aicrm_questionnaire
								INNER JOIN aicrm_questionnaire_choice on aicrm_questionnaire_choice.questionnaireid = aicrm_questionnaire.questionnaireid
								INNER JOIN aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
								WHERE aicrm_questionnaire.questionnaireid = '".$questionnaireid."' and aicrm_questionnaire_choicedetail.choicedetail_name = '".$v1['otherText']."' and aicrm_questionnaire_choice.choice_title  = '".$v1['name']."'; ";
								$a_choice = $myLibrary_mysqli->select($select_choice);
									
								if(!empty($a_choice)){
									$a_choice['0']['answer'] = $questionnaire_answer[$v1['name']."-Comment"];
									array_push($checkbox , $a_choice['0']);
								}
							}else{
								$select_choice = "SELECT  aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choicedetail.choicedetailid ,aicrm_questionnaire_choicedetail.choicedetail_other
								FROM aicrm_questionnaire
								INNER JOIN aicrm_questionnaire_choice on aicrm_questionnaire_choice.questionnaireid = aicrm_questionnaire.questionnaireid
								INNER JOIN aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
								WHERE aicrm_questionnaire.questionnaireid = '".$questionnaireid."' and aicrm_questionnaire_choicedetail.choicedetail_value = '".$v1_checkbox."' and aicrm_questionnaire_choice.choice_title  = '".$v1['name']."'; ";
								$a_choice = $myLibrary_mysqli->select($select_choice);

								if(!empty($a_choice)){
									$a_choice['0']['answer'] = $v1_checkbox;
									array_push($checkbox , $a_choice['0']);
								}
							}

						}
						$v1['answer'] = $checkbox;
						
					}else{

						if($questionnaire_answer[$v1['name']] == 'other' && $v1['hasOther'] == 1){

							$select_choice = "SELECT  aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choicedetail.choicedetailid ,aicrm_questionnaire_choicedetail.choicedetail_other
							FROM aicrm_questionnaire
							INNER JOIN aicrm_questionnaire_choice on aicrm_questionnaire_choice.questionnaireid = aicrm_questionnaire.questionnaireid
							INNER JOIN aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
							WHERE aicrm_questionnaire.questionnaireid = '".$questionnaireid."' and aicrm_questionnaire_choicedetail.choicedetail_name = '".$v1['otherText']."' and aicrm_questionnaire_choice.choice_title  = '".$v1['name']."'; ";
														
							$a_choice = $myLibrary_mysqli->select($select_choice);
							$v1['answer'] = $questionnaire_answer[$v1['name']."-Comment"];
							if(!empty($a_choice)){
								$v1['choiceid'] = $a_choice[0]['choiceid'];
								$v1['choicedetailid'] = $a_choice[0]['choicedetailid'];
								$v1['choicedetail_other'] = $a_choice[0]['choicedetail_other'];
							}else{
								$v1['choiceid'] = '';
								$v1['choicedetailid'] = '';
								$v1['hasother'] = '';
							}

						}else{

							$select_choice = "SELECT  aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choicedetail.choicedetailid ,aicrm_questionnaire_choicedetail.choicedetail_other
							FROM aicrm_questionnaire
							INNER JOIN aicrm_questionnaire_choice on aicrm_questionnaire_choice.questionnaireid = aicrm_questionnaire.questionnaireid
							INNER JOIN aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choiceid = aicrm_questionnaire_choice.choiceid
							WHERE aicrm_questionnaire.questionnaireid = '".$questionnaireid."' and aicrm_questionnaire_choicedetail.choicedetail_value = '".$questionnaire_answer[$v1['name']]."' and aicrm_questionnaire_choice.choice_title  = '".$v1['name']."'; ";
							
							$a_choice = $myLibrary_mysqli->select($select_choice);
							$v1['answer'] = $questionnaire_answer[$v1['name']];
							if(!empty($a_choice)){
								$v1['choiceid'] = $a_choice[0]['choiceid'];
								$v1['choicedetailid'] = $a_choice[0]['choicedetailid'];
								$v1['choicedetail_other'] = $a_choice[0]['choicedetail_other'];
							}else{
								$v1['choiceid'] = '';
								$v1['choicedetailid'] = '';
								$v1['hasother'] = '';
							}
						}
					}
					
				}

				array_push($d_template , $v1);				
			}
		}

		//Inset answer
		if($action != 'edit'){
			
			foreach ($d_template as $key => $value) {
				if($value['answer'] != ''){

					if($value['type'] == 'checkbox' ){

						foreach ($value['answer'] as $k => $v) {
							$sql_insert = "INSERT INTO aicrm_questionnaire_answer
							(questionnaireid, choiceid, choicedetailid, choicedetail ) VALUES 
							('".$questionnaireid."','".$v['choiceid']."','".$v['choicedetailid']."','".$v['answer']."')";
							$myLibrary_mysqli->Query($sql_insert);
						}
						
					}else{
						$sql_insert = "INSERT INTO aicrm_questionnaire_answer
							(questionnaireid, choiceid, choicedetailid, choicedetail ) VALUES 
							('".$questionnaireid."','".$value['choiceid']."','".$value['choicedetailid']."','".$value['answer']."')";
							$myLibrary_mysqli->Query($sql_insert);
					}
				}	
			}
		
		}else{
			
			sleep(5);

			$sql_del = "delete from aicrm_questionnaire_answer WHERE questionnaireid = '".$questionnaireid."' ";
			
			$myLibrary_mysqli->Query($sql_del);
			//echo "<pre>"; print_r($d_template); echo "</pre>"; exit;
			foreach ($d_template as $key => $value) {

				if($value['answer'] != ''){

					if($value['type'] == 'checkbox' ){

						foreach ($value['answer'] as $k => $v) {

							$sql_insert = "";
							$sql_insert = "INSERT INTO aicrm_questionnaire_answer
							(questionnaireid, choiceid, choicedetailid, choicedetail ,relmodule,relcrmid) VALUES 
							('".$questionnaireid."','".@$v['choiceid']."','".@$v['choicedetailid']."','".@$v['answer']."' ,'','');";
							$myLibrary_mysqli->Query($sql_insert);
						}
						
					}else{
						$sql_insert = "";
						$sql_insert = "INSERT INTO aicrm_questionnaire_answer
							(questionnaireid, choiceid, choicedetailid, choicedetail ,relmodule,relcrmid) VALUES 
							('".$questionnaireid."','".@$value['choiceid']."','".@$value['choicedetailid']."','".@$value['answer']."','','');";
							$myLibrary_mysqli->Query($sql_insert);
					}
				}	
			}
		}
		//if Action
		//echo "<pre>"; print_r($d_template); echo "</pre>"; exit;
		/*$update_scroll = "UPDATE aicrm_questionnaire
			INNER JOIN (
				SELECT
				sum(aicrm_questionnaire_choicedetail.choicedetail_value) as scroll , aicrm_questionnaire_answer.questionnaireid
				FROM aicrm_questionnaire_answer 
				inner join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choicedetailid = aicrm_questionnaire_answer.choicedetailid
				where aicrm_questionnaire_answer.questionnaireid = '".$questionnaireid."'
				group by aicrm_questionnaire_answer.questionnaireid
			) answer ON answer.questionnaireid = aicrm_questionnaire.questionnaireid 
			Set aicrm_questionnaire.point = answer.scroll
			WHERE aicrm_questionnaire.questionnaireid = '".$questionnaireid."'; ";
		$myLibrary_mysqli->Query($update_scroll);*/

	}
	// Inset answer
	

	public function insert_activity($questionnaireid='',$accountid='',$branchid='',$accountname='',$questionnaire_type=''){

		$myLibrary_mysqli = new myLibrary_mysqli();
		$myLibrary_mysqli->_dbconfig = $this->_dbconfig;
		$userid = $_REQUEST["userid"]==""? $_SESSION['authenticated_user_id']:$_REQUEST["userid"];

		$select_questionnaire = "select aicrm_questionnaire.* ,aicrm_crmentity.description from aicrm_questionnaire 
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
			where questionnaireid = '".$questionnaireid."' ";
		$d_ques = $myLibrary_mysqli->select($select_questionnaire);

		if($accountid == ''){
			
			$select_acc = "select aicrm_account.* from aicrm_account
						   inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
						   where aicrm_crmentity.deleted = 0 and aicrm_account.accountname = '".$accountname."' ";
			$d_acc = $myLibrary_mysqli->select($select_acc);
			
			if(!empty($d_acc)){//Create Activity

				require_once('modules/Calendar/Activity.php');
				$activity_focus = new Activity();
				$activity_focus->column_fields['activitytype'] = $questionnaire_type;//Objective
				$activity_focus->column_fields['account_id'] = $d_acc[0]['accountid'];//Account Name
				$activity_focus->column_fields['activity_name'] = $questionnaire_type;//
				$activity_focus->column_fields['mobile'] = $d_acc[0]['mobile'];//Mobile
				$activity_focus->column_fields['branchid'] = $branchid;//Project Name
				$activity_focus->column_fields['date_start'] = $d_ques[0]['questionnaire_date']; //วันที่ติดต่อ
				$activity_focus->column_fields['phone'] = $d_acc[0]['phone'];//Telephone
				$activity_focus->column_fields['activity_grade'] = $d_ques[0]['grade'];//Grade
				$activity_focus->column_fields['eventstatus'] = 'Complete';//Activity Status
				$activity_focus->column_fields['time_start'] = $d_ques[0]['questionnaire_time'];//เวลาที่ติดต่อ/Start Time
				$activity_focus->column_fields['activity_priority'] = 'Low';//Priority
				$activity_focus->column_fields['description'] = $d_ques[0]['ccomment'];//Comment
				$activity_focus->column_fields['mediaid'] = $d_ques[0]['mediaid'];
				$activity_focus->column_fields['line_id'] = $d_ques[0]['line_id'];
				$activity_focus->column_fields['email'] = $d_ques[0]['email'];
				$activity_focus->column_fields['praise'] = $d_ques[0]['praise'];
				$activity_focus->column_fields['customers_criticize'] = $d_ques[0]['questionnaire_complaint'];
				$activity_focus->column_fields['unitsid'] = $d_ques[0]['unitsid'];
				$activity_focus->column_fields['assigned_user_id'] = $userid; // Assigned To
				$activity_focus->column_fields['smcreatorid'] = $userid; // Created By
				$_REQUEST["module"] = "Calendar";
				$activity_focus->mode ="";
				$activity_focus->id="";
				$activity_focus->save("Calendar");
				//Update Account to Questionnaire
				$sql_update = "Update aicrm_questionnaire set accountid = '".$d_acc[0]['accountid']."' where questionnaireid = '".$questionnaireid."' ";
				$myLibrary_mysqli->Query($sql_update);

			}else{//Create Account - Create Activity

				//Insert Account
				require_once('modules/Accounts/Accounts.php');
				$acc_focus = new Accounts();
				$acc_focus->column_fields['account_salutation'] = 'คุณ';//คำนำหน้า
				$acc_focus->column_fields['accountname'] = $d_ques[0]['firstname'].' '.$d_ques[0]['lastname'];//account name
				$acc_focus->column_fields['firstname'] = $d_ques[0]['firstname'];//ชื่อลูกค้า(Thai)
				$acc_focus->column_fields['lastname'] = $d_ques[0]['lastname'];//นามสกุล(Thai)	
				$acc_focus->column_fields['accountdate'] = $d_ques[0]['questionnaire_date'];//วันที่ติดต่อ
				$acc_focus->column_fields['account_type'] = 'บุคคลธรรมดา';//ประเภทลูกค้า	
				$acc_focus->column_fields['source'] = $questionnaire_type;//แหล่งที่มา	
				$acc_focus->column_fields['accountstatus'] = 'Non-Account';//Account Status
				$acc_focus->column_fields['mobile'] = $d_ques[0]['tel'];//Mobile
				$acc_focus->column_fields['phone'] = $d_ques[0]['tel'];//Telephone	
				$acc_focus->column_fields['email1'] = $d_ques[0]['email'];//E-Mail
				$acc_focus->column_fields['accountgender'] = $d_ques[0]['gender'];//เพศ
				$acc_focus->column_fields['accountage'] = $d_ques[0]['age'];//อายุ
				$acc_focus->column_fields['birthdate'] = $d_ques[0]['birthdate'];//วันเกิด
				$acc_focus->column_fields['assigned_user_id'] = $userid; //Assigned To
				$acc_focus->column_fields['smcreatorid'] = $userid; //Created By
				$_REQUEST["module"] = "Accounts";
				$acc_focus->mode ="";
				$acc_focus->id="";
				$acc_focus->save("Accounts");

				$accountid = $acc_focus->id ;
				$sql_update = "Update aicrm_questionnaire set accountid = '".$accountid."' where questionnaireid = '".$questionnaireid."' ";
				$myLibrary_mysqli->Query($sql_update);

				//Insert Activity
				require_once('modules/Calendar/Activity.php');
				$activity_focus = new Activity();
				$activity_focus->column_fields['activitytype'] = $questionnaire_type;//Objective
				$activity_focus->column_fields['account_id'] = $accountid;//Account Name
				$activity_focus->column_fields['activity_name'] = $questionnaire_type;//
				$activity_focus->column_fields['mobile'] = $d_ques[0]['tel'];//Mobile
				$activity_focus->column_fields['branchid'] = $branchid;//Project Name
				$activity_focus->column_fields['date_start'] = $d_ques[0]['questionnaire_date']; //วันที่ติดต่อ
				$activity_focus->column_fields['phone'] = $d_ques[0]['tel'];//Telephone
				$activity_focus->column_fields['activity_grade'] = $d_ques[0]['grade'];//Grade
				$activity_focus->column_fields['eventstatus'] = 'Complete';//Activity Status
				$activity_focus->column_fields['time_start'] = $d_ques[0]['questionnaire_time'];//เวลาที่ติดต่อ/Start Time
				$activity_focus->column_fields['activity_priority'] = 'Low';//Priority
				$activity_focus->column_fields['description'] = $d_ques[0]['ccomment'];//Comment
				$activity_focus->column_fields['mediaid'] = $d_ques[0]['mediaid'];
				$activity_focus->column_fields['praise'] = $d_ques[0]['praise'];
				$activity_focus->column_fields['customers_criticize'] = $d_ques[0]['questionnaire_complaint'];
				$activity_focus->column_fields['unitsid'] = $d_ques[0]['unitsid'];
				$activity_focus->column_fields['line_id'] = $d_ques[0]['line_id'];
				$activity_focus->column_fields['email'] = $d_ques[0]['email'];
				$activity_focus->column_fields['assigned_user_id'] = $userid; // Assigned To
				$activity_focus->column_fields['smcreatorid'] = $userid; // Created By
				$_REQUEST["module"] = "Calendar";
				$activity_focus->mode ="";
				$activity_focus->id="";
				$activity_focus->save("Calendar");
			}
			
		}else{
			require_once('modules/Calendar/Activity.php');
			$activity_focus = new Activity();
			$activity_focus->column_fields['activitytype'] = $questionnaire_type;//Objective
			$activity_focus->column_fields['account_id'] = $d_acc[0]['accountid'];//Account Name
			$activity_focus->column_fields['activity_name'] = $questionnaire_type;//
			$activity_focus->column_fields['mobile'] = $d_acc[0]['mobile'];//Mobile
			$activity_focus->column_fields['branchid'] = $branchid;//Project Name
			$activity_focus->column_fields['date_start'] = $d_ques[0]['questionnaire_date']; //วันที่ติดต่อ
			$activity_focus->column_fields['phone'] = $d_acc[0]['phone'];//Telephone
			$activity_focus->column_fields['activity_grade'] = $d_ques[0]['grade'];//Grade
			$activity_focus->column_fields['eventstatus'] = 'Complete';//Activity Status
			$activity_focus->column_fields['time_start'] = $d_ques[0]['questionnaire_time'];//เวลาที่ติดต่อ/Start Time
			$activity_focus->column_fields['activity_priority'] = 'Low';//Priority
			$activity_focus->column_fields['line_id'] = $d_ques[0]['line_id'];
			$activity_focus->column_fields['email'] = $d_ques[0]['email'];
			$activity_focus->column_fields['praise'] = $d_ques[0]['praise'];
			$activity_focus->column_fields['customers_criticize'] = $d_ques[0]['questionnaire_complaint'];
			$activity_focus->column_fields['unitsid'] = $d_ques[0]['unitsid'];
			$activity_focus->column_fields['mediaid'] = $d_ques[0]['mediaid'];
			$activity_focus->column_fields['description'] = $d_ques[0]['ccomment'];//Comment
			$activity_focus->column_fields['assigned_user_id'] = $userid; // Assigned To
			$activity_focus->column_fields['smcreatorid'] = $userid; // Created By
			$_REQUEST["module"] = "Calendar";
			$activity_focus->mode ="";
			$activity_focus->id="";
			$activity_focus->save("Calendar");
			//Update Account to Questionnaire
			$sql_update = "Update aicrm_questionnaire set accountid = '".$d_acc[0]['accountid']."' where questionnaireid = '".$questionnaireid."' ";
			$myLibrary_mysqli->Query($sql_update);
		}

		//exit;
	}//insert_activity


	public function save_smartquestionnaire($smartquestionnaireid=NULL,$action=NULL,$questionnairetemplateid=NULL){
		$myLibrary_mysqli = new myLibrary_mysqli();
		$myLibrary_mysqli->_dbconfig = $this->_dbconfig;
		$d_template = array();

		$sql_update = "update aicrm_smartquestionnaire set questionnairetemplateid = '".$questionnairetemplateid."' where smartquestionnaireid = '".$smartquestionnaireid."' ";
		$myLibrary_mysqli->Query($sql_update);	
	}//save_smartquestionnaire


}//End Clasa libquestionnaire

?>
