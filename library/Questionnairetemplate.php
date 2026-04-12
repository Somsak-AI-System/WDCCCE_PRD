<?php
//error_reporting(E_ALL  & ~E_NOTICE  & ~E_WARNING );
//ini_set('display_errors', 1);
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/generate_MYSQLi.php");

class libQuestionnairetemplate{
	public  $_dbconfig;
	public $_page;

	public function __construct($dbconfig){
		global  $list_max_entries_per_page;
		$this->_dbconfig = $dbconfig;
		$this->generate = new generate($this->_dbconfig  ,"DB");
		$this->list_max_entries_per_page =$list_max_entries_per_page;

	}
	
	public function  get_server_name()
	{
		return  gethostbyaddr($_SERVER['REMOTE_ADDR']);;
	}
	
	public function save_template($data=array(),$questionnairetemplateid=NULL,$action=NULL){
		
		
		if($action != 'edit'){
			//Action Add
			$title_questionnaire = $data['title'];
			foreach($data['pages'] as $key => $val){
				//inset tabe aicrm_questionnairetemplate_page
				$sql = "insert into aicrm_questionnairetemplate_page (questionnairetemplateid,title_questionnaire,title_page,name_page,sequence_page) ";
				$sql .= " VALUES ('".$questionnairetemplateid."','".$title_questionnaire."','".$val['title']."','".$val['title']."','".($key +1)."'); ";

				/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle,date('Y-m-d H:i:s')." Sql page ==> ".$sql."\r\n");*/

				$this->generate->query($sql);//echo $sql; exit;
				$pageid = $this->generate->con->insert_id;
				//echo $pageid."<br>";
					foreach($val['elements'] as $k => $v){
					//inset tabe aicrm_questionnairetemplate_choice
						$hasOther = (isset($v['hasOther']) && $v['hasOther'] == true) ? 1 : 0;
						$isRequired =(isset($v['isRequired']) && $v['isRequired'] == true) ? 1 : 0;

						$sql_choice = "insert into aicrm_questionnairetemplate_choice (questionnairetemplateid,choice_type,pageid,choice_title,choice_name,sequence,hasother,required) ";
						$sql_choice .= " VALUES ('".$questionnairetemplateid."','".$v['type']."','".$pageid."','".@$v['title']."','".@$v['title']."','".($k+1)."','".$hasOther."','".$isRequired."'); ";
						//echo $sql_choice; exit;
						/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
						$FileHandle = fopen($FileName, 'a+') or die("can't open file");
						fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine ==> ".$sql_choice."\r\n");*/
						
						$this->generate->query($sql_choice);//echo $sql; exit;
						$choiceid = $this->generate->con->insert_id;
						//echo $choiceid."<br>";
						
						if($v['type'] == 'text'){
									//inset tabe aicrm_questionnairetemplate_choicedetail (Type Text)
									$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
									$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','','1','0'); ";
									
									/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
									$FileHandle = fopen($FileName, 'a+') or die("can't open file");
									fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine Detail ==> ".$sql_choicedetail."\r\n");	*/
									
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
									//inset tabe aicrm_questionnairetemplate_choicedetail
									$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_value,choicedetail_name,sequence_detail,choicedetail_other) ";
									$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','".$value."','".$text."','".($kchoice+1)."','0'); ";

									/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
									$FileHandle = fopen($FileName, 'a+') or die("can't open file");
									fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine Detail ==> ".$sql_choicedetail."\r\n");*/
									$this->generate->query($sql_choicedetail);
									$kc++;
							}
							if(isset($v['otherText']) && $v['otherText'] != ''){
									$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
									$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','".$v['otherText']."','".$kc."','1'); ";	
									/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
									$FileHandle = fopen($FileName, 'a+') or die("can't open file");
									fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine Detail ==> ".$sql_choicedetail."\r\n");*/
									$this->generate->query($sql_choicedetail);
							}
						}//else

						
						
					}
			}
		}else{
			//Action Edit
			$sql = "delete from aicrm_questionnairetemplate_page WHERE questionnairetemplateid = '".$questionnairetemplateid."' ";
			$sql_choice = "delete from aicrm_questionnairetemplate_choice WHERE questionnairetemplateid = '".$questionnairetemplateid."' ";
			$sql_choicedetail = "delete from aicrm_questionnairetemplate_choicedetail WHERE questionnairetemplateid = '".$questionnairetemplateid."' ";
			//echo $sql; echo "<br>";
			//echo $sql_choice; echo "<br>";
			//echo $sql_choicedetail; echo "<br>"; exit;
			$this->generate->query($sql);
			$this->generate->query($sql_choice);
			$this->generate->query($sql_choicedetail);
			//echo "<pre>"; print_r($data); echo "</ore>"; exit;
			$title_questionnaire = $data['title'];
				foreach($data['pages'] as $key => $val){
					//inset tabe aicrm_questionnairetemplate_page
					$sql = "insert into aicrm_questionnairetemplate_page (questionnairetemplateid,title_questionnaire,title_page,name_page,sequence_page) ";
					$sql .= " VALUES ('".$questionnairetemplateid."','".$title_questionnaire."','".$val['title']."','".$val['title']."','".($key +1)."'); ";

					/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,date('Y-m-d H:i:s')." Sql page ==> ".$sql."\r\n");*/

					$this->generate->query($sql);//echo $sql; exit;
					$pageid = $this->generate->con->insert_id;
					//echo $pageid."<br>";
						foreach($val['elements'] as $k => $v){
						//inset tabe aicrm_questionnairetemplate_choice
							$hasOther = (isset($v['hasOther']) && $v['hasOther'] == true) ? 1 : 0;
							$isRequired =(isset($v['isRequired']) && $v['isRequired'] == true) ? 1 : 0;

							$sql_choice = "insert into aicrm_questionnairetemplate_choice (questionnairetemplateid,choice_type,pageid,choice_title,choice_name,sequence,hasother,required) ";
							$sql_choice .= " VALUES ('".$questionnairetemplateid."','".$v['type']."','".$pageid."','".@$v['title']."','".@$v['title']."','".($k+1)."','".$hasOther."','".$isRequired."'); ";
							//echo $sql_choice; exit;
							/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
							$FileHandle = fopen($FileName, 'a+') or die("can't open file");
							fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine ==> ".$sql_choice."\r\n");*/
							
							$this->generate->query($sql_choice);//echo $sql; exit;
							$choiceid = $this->generate->con->insert_id;
							//echo $choiceid."<br>";
							
							if($v['type'] == 'text'){
										//inset tabe aicrm_questionnairetemplate_choicedetail (Type Text)
										$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
										$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','','1','0'); ";
										
										/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
										$FileHandle = fopen($FileName, 'a+') or die("can't open file");
										fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine Detail ==> ".$sql_choicedetail."\r\n");	*/
										
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
										//inset tabe aicrm_questionnairetemplate_choicedetail
										$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_value,choicedetail_name,sequence_detail,choicedetail_other) ";
										$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','".$value."','".$text."','".($kchoice+1)."','0'); ";

										/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
										$FileHandle = fopen($FileName, 'a+') or die("can't open file");
										fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine Detail ==> ".$sql_choicedetail."\r\n");*/
										$this->generate->query($sql_choicedetail);
										$kc++;
								}
								if(isset($v['otherText']) && $v['otherText'] != ''){
										$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
										$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','".$v['otherText']."','".$kc."','1'); ";	
										/*$FileName = "Smart_Questionnaire_".date('Y_m_d').".txt";
										$FileHandle = fopen($FileName, 'a+') or die("can't open file");
										fwrite($FileHandle,date('Y-m-d H:i:s')." Sql Choine Detail ==> ".$sql_choicedetail."\r\n");*/
										$this->generate->query($sql_choicedetail);
								}
							}//else

							
							
						}
				}
				/*foreach($data['pages'] as $key => $val){
					//inset tabe aicrm_questionnairetemplate_page
					$sql = "insert into aicrm_questionnairetemplate_page (questionnairetemplateid,title_questionnaire,title_page,name_page,sequence_page) ";
					$sql .= " VALUES ('".$questionnairetemplateid."','".$title_questionnaire."','".$val['title']."','".$val['title']."','".($key +1)."'); ";
					$this->generate->query($sql);//echo $sql; exit;
					$pageid = $this->generate->con->insert_id;
						foreach($val['elements'] as $k => $v){
							$hasOther = (isset($v['hasOther']) && $v['hasOther'] == true) ? 1 : 0;
							$isRequired =(isset($v['isRequired']) && $v['isRequired'] == true) ? 1 : 0;

							$sql_choice = "insert into aicrm_questionnairetemplate_choice (questionnairetemplateid,choice_type,pageid,choice_title,choice_name,sequence,hasother,required) ";
							$sql_choice .= " VALUES ('".$questionnairetemplateid."','".$v['type']."','".$pageid."','".@$v['title']."','".@$v['title']."','".($k+1)."','".$hasOther."','".$isRequired."'); ";
							$this->generate->query($sql_choice);//echo $sql; exit;
							$choiceid = $this->generate->con->insert_id;

							if($v['type'] == 'text'){
									$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
									$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','','1','0'); ";	
									$this->generate->query($sql_choicedetail);
									$kc++;
							}else{
								$kc = 1 ;
								foreach($v['choices'] as $kchoice => $choice){
										//inset tabe aicrm_questionnairetemplate_choicedetail
										if(is_array($choice)){
											$value = $choice['value'];
											$text = $choice['text'] ;
										}else{
											$value = $choice ;
											$text = $choice ;
										}

										$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_value,choicedetail_name,sequence_detail,choicedetail_other) ";
										$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','".$value."','".$text."','".($kchoice+1)."','0'); ";
										$this->generate->query($sql_choicedetail);
										$kc++;
								}
								if(isset($v['otherText']) && $v['otherText'] != ''){
										$sql_choicedetail = "insert into aicrm_questionnairetemplate_choicedetail (questionnairetemplateid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
										$sql_choicedetail .= " VALUES ('".$questionnairetemplateid."','".$choiceid."','".$v['otherText']."','".$kc."','1'); ";	
										
										$this->generate->query($sql_choicedetail);
								}
							}//else
						}//foreach
				}//foreach*/
		
		}
		
		//exit;
		//echo $questionnairetemplateid; exit;
		/*$sql = " insert into aicrm_questionnairesdtl ( " .$field_column. " )";
			$sql .= " VALUES ( ".implode(" ) , ( ", $field_value)." ) ";
			$sql .= " ON DUPLICATE KEY UPDATE  " .gen_query_multi($query_key_update) ;
			//echo $sql; exit;
			$this->generate->query($sql);
		*/
	}
	
	





}
?>
