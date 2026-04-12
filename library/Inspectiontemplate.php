<?php
//error_reporting(E_ALL  & ~E_NOTICE  & ~E_WARNING );
//ini_set('display_errors', 1);
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

class libinspectiontemplate{

    public $_dbconfig;
    public $_page;
    public function __construct($dbconfig){
        global  $list_max_entries_per_page;
        $this->_dbconfig = $dbconfig;
        $this->list_max_entries_per_page =$list_max_entries_per_page;
    }

    public function  get_server_name()
    {
        return  gethostbyaddr($_SERVER['REMOTE_ADDR']);;
    }

    public function save_template($data=array(),$inspectiontemplateid=NULL,$action=NULL){
//echo 555;exit;
        $myLibrary_mysqli = new myLibrary_mysqli();
        $myLibrary_mysqli->_dbconfig = $this->_dbconfig;
//echo '<pre>'; print_r($data); echo '</pre>';exit;

        if($action != 'edit'){
            //Action Add
           
            foreach($data['tables_parameter'] as $key => $val){ //Choice

                $key_table = $val; //Key ของ Table Choice
                $type = $data[$key_table.'_type'];//ประเภทของ Choice
               //echo "<pre>"; print_r($key_table); echo "</pre>"; //exit;
                $temp_key_table[] = $key_table;
                $templates = [];

                if ($type == 'template1'){
                    $title = $data[$key_table.'_headtext'];//Title
                    $detail = $data[$key_table.'_template1text'];// Detail
                    $sequence = ($key+1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'".$inspectiontemplateid."',	'".$type."',		'".$title."',			'".$sequence."'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
				 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
				 	) VALUES (
				 		'".$inspectiontemplateid."',	'".$choiceid."',	'1', '".$detail."',	'".$type."'
				 	)";
                    $myLibrary_mysqli->Query($sql_list);

                }else if($type == 'template2'){

                    $title = $data[$key_table.'_headtext'];//Title
                    $sequence = ($key+1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'".$inspectiontemplateid."',	'".$type."',		'".$title."',			'".$sequence."'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_'.$key_table] as $k => $v) {

                        $sequence_detail= ($k+1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'".$inspectiontemplateid."',	'".$choiceid."',	'".$sequence_detail."',	'".$v."',	'".$type."'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }


                }else if ($type == 'template3') {

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                } else if($type == 'template4') {

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }
                }else if($type == 'template5'){

                    $title = $data[$key_table.'_headtext'];//Title
                    $detail = $data[$key_table.'_template1text'];// Detail
                    $sequence = ($key+1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'".$inspectiontemplateid."',	'".$type."',		'".$title."',			'".$sequence."'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
				 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
				 	) VALUES (
				 		'".$inspectiontemplateid."',	'".$choiceid."',	'1', '".$detail."',	'".$type."'
				 	)";
                    $myLibrary_mysqli->Query($sql_list);

                }else if($type == 'template6'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template7'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template8'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

//                    foreach ($data['label_' . $key_table] as $k => $v) {
//
//                        $sequence_detail = ($k + 1);
//
//                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
//					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
//					 	) VALUES (
//					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
//					 	)";
//                        $myLibrary_mysqli->Query($sql_list);
//                    }
//////////////
                    for($i=0; $i<count($data['label_'.$key_table]); $i++){

                        $sequence_detail = ($i + 1);
//                        alert($data['label_'.$key_table]);

//                        if (isset($data['label_'.$key_table])){
//                            foreach($data['label_'.$key_table] as $k => $v){
//                                $sequence_detail = ($k+1);
////                                alert($k);
//                            }
//                        }

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail( inspectiontemplateid,choiceid,sequence_detail,list,choice_type ) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $data['label_'.$key_table][$i] . "',	'" . $type . "'
					 	)";
                        $choiceID = $myLibrary_mysqli->Query($sql_list);
//                        alert($data);exit;
//                        alert($data['child_label_'.$data['tr_'.$key_table][$i]]);exit;
                        if(isset($data['child_label_'.$data['tr_'.$key_table][$i]])){
                            foreach($data['child_label_'.$data['tr_'.$key_table][$i]] as $k => $childLabel){
                                $sequence_detail = $k+1;
                                $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail2 (inspectiontemplateid, choiceid, sequence_detail, list2, choice_type) VALUES ('".$inspectiontemplateid."','". $choiceID ."', '".$sequence_detail."','".$childLabel."','".$type."')";
                                $quert_list = $myLibrary_mysqli->Query($sql_list);
                            }
                       }
                    }
///////////////
//                    $templates = [];
//                    foreach($data['template_temp_id'] as $temp_id){
//                        $temp = [];
//                        if(isset($data['label_'.$temp_id])){
//                            for($i=0; $i<count($data['label_'.$temp_id]); $i++){
//                                $child = [];
//                                if(isset($data['child_label_'.$data['tr_'.$temp_id][$i]])){
//                                    foreach($data['child_label_'.$data['tr_'.$temp_id][$i]] as $childLabel){
//                                        $child[] = $childLabel;
//                                    }
//                                    alert($child);
////                                    foreach ($child as $key => $value){
////                                        alert($child);
////                                    }
//                                }
//
//                                $temp[] = [
////                                    'label' => $data['label_'.$temp_id][$i],
//                                    'child' => $child
//                                ];
//                            }
//                        }
//                    }
//
////                    alert($temp);
//
//                    foreach ($temp as $k => $v){
//
//                        foreach ($v['child'] as $k2 => $v2){
//                            $sequence_detail = ($k2 + 1);
//
//                            $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail2(
//					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list2,		choice_type
//					 	) VALUES (
//					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v2 . "',	'" . $type . "'
//					 	)";
//                            $myLibrary_mysqli->Query($sql_list);
//
//                        }
//                    }



                }else if($type == 'template9'){
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];//หัวข้อตรวจ ช่องที่0
                    $head_col1 = $data[$key_table.'_head_col1'];//หัวข้อตรวจ ช่องที่1
                    $head_col2 = $data[$key_table.'_head_col2'];//หัวข้อตรวจ ช่องที่2
                    $head_col3 = $data[$key_table.'_head_col3'];//หัวข้อตรวจ ช่องที่3
                    $head_col4 = $data[$key_table.'_head_col4'];//หัวข้อตรวจ ช่องที่4
                    $head_col5 = $data[$key_table.'_head_col5'];//หัวข้อตรวจ ช่องที่5
                    $head_col6 = $data[$key_table.'_head_col6'];//หัวข้อตรวจ ช่องที่6
                    $head_col7 = $data[$key_table.'_head_col7'];//หัวข้อตรวจ ช่องที่7
                    $head_col8 = $data[$key_table.'_head_col8'];//หัวข้อตรวจ ช่องที่8
                    $head_col9 = $data[$key_table.'_head_col9'];//หัวข้อตรวจ ช่องที่9
                    $head_col10 = $data[$key_table.'_head_col10'];//หัวข้อตรวจ ช่องที่10
                    $head_col11 = $data[$key_table.'_head_col11'];//หัวข้อตรวจ ช่องที่11
//                    $head_col12 = $data[$key_table.'_head_col12'];//หัวข้อตรวจ ช่องที่12
//                    $head_col13 = $data[$key_table.'_head_col13'];//หัวข้อตรวจ ช่องที่13
//                    $head_col14 = $data[$key_table.'_head_col14'];//หัวข้อตรวจ ช่องที่14
//                    $head_col15 = $data[$key_table.'_head_col15'];//หัวข้อตรวจ ช่องที่15
//                    $head_col16 = $data[$key_table.'_head_col16'];//หัวข้อตรวจ ช่องที่16

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,	 		head_col2,              head_col3,
						head_col4,                      head_col5, 			head_col6, 				head_col7,	
						head_col8,                      head_col9,          head_col10,             head_col11
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',	'".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."',		        '".$head_col5."',	'".$head_col6."',	        '".$head_col7."',		
					   	'".$head_col8."',               '".$head_col9."',   '".$head_col10."',           '".$head_col11."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data[$key_table.'_seq'] as $k => $v) {
//                        echo "<pre>"; print_r($v); echo "</pre>";
                        $key_tr = $v; //Key ของ Tr Choice List
                        $sequence_detail= ($k+1);
                        $data_col0 = $data[$key_tr.'_seq'];

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		choice_type, 		col0
					 	) VALUES (
					 		'".$inspectiontemplateid."',	'".$choiceid."',	'".$sequence_detail."',	'".$type."',		'".$data_col0."'
					 	)";

//                        echo $sql_list;
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template10'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template11'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template10'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template12'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
//                    echo "<pre>"; print_r($head_col0); echo "</pre>";// exit;
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                     head_col1
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "',
					   	'".$head_col0."',               '".$head_col1."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;

                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template13'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];

                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                     head_col1
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "',
					   	'".$head_col0."',               '".$head_col1."'
					)";

//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;

                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $sub_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $sub_label[] = $data['sub_label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      sublist
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $sub_label[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template14'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];

                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                     head_col1
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "',
					   	'".$head_col0."',               '".$head_col1."'
					)";

                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template15'){
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];
                    $head_col3 = $data[$key_table.'_head_col3'];
                    $head_col4 = $data[$key_table.'_head_col4'];

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,          head_col2,             head_col3,
				        head_col4
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $accept_range = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $accept_range[] = $data['accept_range_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      accept_range
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $accept_range[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template16') {
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];
                    $head_col3 = $data[$key_table.'_head_col3'];
                    $head_col4 = $data[$key_table.'_head_col4'];

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,         head_col2,               head_col3,
				        head_col4
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $sub_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $sub_label[] = $data['sub_label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      sublist
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $sub_label[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template17'){
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];
                    $head_col3 = $data[$key_table.'_head_col3'];
                    $head_col4 = $data[$key_table.'_head_col4'];
                    $head_col5 = $data[$key_table.'_head_col5'];

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,         head_col2,              head_col3,
				        head_col4,                      head_col5
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."',               '".$head_col5."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $sub_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $sub_label[] = $data['sub_label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      sublist
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $sub_label[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template18'){
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                      head_col1,         head_col2
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
//                        echo "<pre>"; print_r($sql_list); echo "</pre>"; exit;
                    }

                } else {



                }

            }

        }else{

            //Action Edit
            $sql_choice = "delete from aicrm_inspectiontemplate_choice WHERE inspectiontemplateid = '".$inspectiontemplateid."' ";
            $sql_choicedetail = "delete from aicrm_inspectiontemplate_choicedetail WHERE inspectiontemplateid = '".$inspectiontemplateid."' ";
            $myLibrary_mysqli->Query($sql_choice);
            $myLibrary_mysqli->Query($sql_choicedetail);

            foreach($data['tables_parameter'] as $key => $val){ //Choice

                $key_table = $val; //Key ของ Table Choice
                $type = $data[$key_table.'_type'];//ประเภทของ Choice
//                echo "<pre>"; print_r($key_table); echo "</pre>"; //exit;
                $temp_key_table[] = $key_table;
                $templates = [];

                if ($type == 'template1'){
                    $title = $data[$key_table.'_headtext'];//Title
                    $detail = $data[$key_table.'_template1text'];// Detail
                    $sequence = ($key+1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'".$inspectiontemplateid."',	'".$type."',		'".$title."',			'".$sequence."'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
				 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
				 	) VALUES (
				 		'".$inspectiontemplateid."',	'".$choiceid."',	'1', '".$detail."',	'".$type."'
				 	)";
                    $myLibrary_mysqli->Query($sql_list);

                }else if($type == 'template2'){

                    $title = $data[$key_table.'_headtext'];//Title
                    $sequence = ($key+1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'".$inspectiontemplateid."',	'".$type."',		'".$title."',			'".$sequence."'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);
//echo '<pre>'; print_r($data);echo '</pre>';exit;
                    foreach ($data['label_'.$key_table] as $k => $v) {

                        $sequence_detail= ($k+1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'".$inspectiontemplateid."',	'".$choiceid."',	'".$sequence_detail."',	'".$v."',	'".$type."'
					 	)";
//                        echo $sql_list;exit;
                        $myLibrary_mysqli->Query($sql_list);
                    }


                }else if ($type == 'template3') {

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                } else if($type == 'template4') {

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }
                }else if($type == 'template5'){

                    $title = $data[$key_table.'_headtext'];//Title
                    $detail = $data[$key_table.'_template1text'];// Detail
                    $sequence = ($key+1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'".$inspectiontemplateid."',	'".$type."',		'".$title."',			'".$sequence."'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
				 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
				 	) VALUES (
				 		'".$inspectiontemplateid."',	'".$choiceid."',	'1', '".$detail."',	'".$type."'
				 	)";
                    $myLibrary_mysqli->Query($sql_list);

                }else if($type == 'template6'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template7'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template8'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

//                    foreach ($data['label_' . $key_table] as $k => $v) {
//
//                        $sequence_detail = ($k + 1);
//
//                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
//					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
//					 	) VALUES (
//					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
//					 	)";
//                        $myLibrary_mysqli->Query($sql_list);
//                    }
//////////////
                    for($i=0; $i<count($data['label_'.$key_table]); $i++){

                        $sequence_detail = ($i + 1);
//                        alert($data['label_'.$key_table]);

//                        if (isset($data['label_'.$key_table])){
//                            foreach($data['label_'.$key_table] as $k => $v){
//                                $sequence_detail = ($k+1);
////                                alert($k);
//                            }
//                        }

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail( inspectiontemplateid,choiceid,sequence_detail,list,choice_type ) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $data['label_'.$key_table][$i] . "',	'" . $type . "'
					 	)";
                        $choiceID = $myLibrary_mysqli->Query($sql_list);
//alert($data['child_label_'.$data['tr_'.$key_table][$i]]);exit;
                        if(isset($data['child_label_'.$data['tr_'.$key_table][$i]])){
                            foreach($data['child_label_'.$data['tr_'.$key_table][$i]] as $k => $childLabel){
                                $sequence_detail = $k+1;
                                $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail2 (inspectiontemplateid, choiceid, sequence_detail, list2, choice_type) VALUES ('".$inspectiontemplateid."','". $choiceID ."', '".$sequence_detail."','".$childLabel."','".$type."')";
                                $quert_list = $myLibrary_mysqli->Query($sql_list);
                            }
                        }
                    }
///////////////
//                    $templates = [];
//                    foreach($data['template_temp_id'] as $temp_id){
//                        $temp = [];
//                        if(isset($data['label_'.$temp_id])){
//                            for($i=0; $i<count($data['label_'.$temp_id]); $i++){
//                                $child = [];
//                                if(isset($data['child_label_'.$data['tr_'.$temp_id][$i]])){
//                                    foreach($data['child_label_'.$data['tr_'.$temp_id][$i]] as $childLabel){
//                                        $child[] = $childLabel;
//                                    }
//                                    alert($child);
////                                    foreach ($child as $key => $value){
////                                        alert($child);
////                                    }
//                                }
//
//                                $temp[] = [
////                                    'label' => $data['label_'.$temp_id][$i],
//                                    'child' => $child
//                                ];
//                            }
//                        }
//                    }
//
////                    alert($temp);
//
//                    foreach ($temp as $k => $v){
//
//                        foreach ($v['child'] as $k2 => $v2){
//                            $sequence_detail = ($k2 + 1);
//
//                            $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail2(
//					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list2,		choice_type
//					 	) VALUES (
//					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v2 . "',	'" . $type . "'
//					 	)";
//                            $myLibrary_mysqli->Query($sql_list);
//
//                        }
//                    }



                }else if($type == 'template9'){
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];//หัวข้อตรวจ ช่องที่0
                    $head_col1 = $data[$key_table.'_head_col1'];//หัวข้อตรวจ ช่องที่1
                    $head_col2 = $data[$key_table.'_head_col2'];//หัวข้อตรวจ ช่องที่2
                    $head_col3 = $data[$key_table.'_head_col3'];//หัวข้อตรวจ ช่องที่3
                    $head_col4 = $data[$key_table.'_head_col4'];//หัวข้อตรวจ ช่องที่4
                    $head_col5 = $data[$key_table.'_head_col5'];//หัวข้อตรวจ ช่องที่5
                    $head_col6 = $data[$key_table.'_head_col6'];//หัวข้อตรวจ ช่องที่6
                    $head_col7 = $data[$key_table.'_head_col7'];//หัวข้อตรวจ ช่องที่7
                    $head_col8 = $data[$key_table.'_head_col8'];//หัวข้อตรวจ ช่องที่8
                    $head_col9 = $data[$key_table.'_head_col9'];//หัวข้อตรวจ ช่องที่9
                    $head_col10 = $data[$key_table.'_head_col10'];//หัวข้อตรวจ ช่องที่10
                    $head_col11 = $data[$key_table.'_head_col11'];//หัวข้อตรวจ ช่องที่11
//                    $head_col12 = $data[$key_table.'_head_col12'];//หัวข้อตรวจ ช่องที่12
//                    $head_col13 = $data[$key_table.'_head_col13'];//หัวข้อตรวจ ช่องที่13
//                    $head_col14 = $data[$key_table.'_head_col14'];//หัวข้อตรวจ ช่องที่14
//                    $head_col15 = $data[$key_table.'_head_col15'];//หัวข้อตรวจ ช่องที่15
//                    $head_col16 = $data[$key_table.'_head_col16'];//หัวข้อตรวจ ช่องที่16

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,	 		head_col2,              head_col3,
						head_col4,                      head_col5, 			head_col6, 				head_col7,	
						head_col8,                      head_col9,          head_col10,             head_col11
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',	'".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."',		        '".$head_col5."',	'".$head_col6."',	        '".$head_col7."',		
					   	'".$head_col8."',               '".$head_col9."',   '".$head_col10."',           '".$head_col11."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);
//alert($data[$key_table.'_seq']);exit;
                    foreach ($data[$key_table.'_seq'] as $k => $v) {
//                        echo "<pre>"; print_r($v); echo "</pre>";
                        $key_tr = $v; //Key ของ Tr Choice List
                        $sequence_detail= ($k+1);
                        $data_col0 = $data[$key_tr.'_seq'];

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		choice_type, 		col0
					 	) VALUES (
					 		'".$inspectiontemplateid."',	'".$choiceid."',	'".$sequence_detail."',	'".$type."',		'".$data_col0."'
					 	)";

//                        echo $sql_list;
                        $myLibrary_mysqli->Query($sql_list);
                    }//exit;

                }else if($type == 'template10'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template11'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template10'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "'
					)";
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template12'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
//                    echo "<pre>"; print_r($head_col0); echo "</pre>";// exit;
                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                     head_col1
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "',
					   	'".$head_col0."',               '".$head_col1."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;

                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template13'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];

                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                     head_col1
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "',
					   	'".$head_col0."',               '".$head_col1."'
					)";

//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;

                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $sub_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $sub_label[] = $data['sub_label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      sublist
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $sub_label[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template14'){

                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];

                    $sequence = ($key + 1);
                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                     head_col1
						) VALUES (
					   	'" . $inspectiontemplateid . "',	'" . $type . "',		'" . $title . "',			'" . $sequence . "',
					   	'".$head_col0."',               '".$head_col1."'
					)";

                    $choiceid = $myLibrary_mysqli->Query($sql);

                    foreach ($data['label_' . $key_table] as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
                    }

                }else if($type == 'template15'){
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];
                    $head_col3 = $data[$key_table.'_head_col3'];
                    $head_col4 = $data[$key_table.'_head_col4'];

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,          head_col2,             head_col3,
				        head_col4
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $accept_range = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $accept_range[] = $data['accept_range_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      accept_range
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $accept_range[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template16') {
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table . '_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];
                    $head_col3 = $data[$key_table.'_head_col3'];
                    $head_col4 = $data[$key_table.'_head_col4'];

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,         head_col2,               head_col3,
				        head_col4
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $sub_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $sub_label[] = $data['sub_label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      sublist
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $sub_label[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template17'){
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];
                    $head_col3 = $data[$key_table.'_head_col3'];
                    $head_col4 = $data[$key_table.'_head_col4'];
                    $head_col5 = $data[$key_table.'_head_col5'];

                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
				        head_col0,                      head_col1,         head_col2,              head_col3,
				        head_col4,                      head_col5
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."',           '".$head_col3."',
					   	'".$head_col4."',               '".$head_col5."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];
                    $sub_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];
                        $sub_label[] = $data['sub_label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type,      sublist
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "',	'" . $sub_label[$k] . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);

                    }

                }else if($type == 'template18'){
//                    echo "<pre>"; print_r($data); echo "</pre>"; exit;
                    $title = $data[$key_table.'_headtext'];//Title
                    $head_col0 = $data[$key_table.'_head_col0'];
                    $head_col1 = $data[$key_table.'_head_col1'];
                    $head_col2 = $data[$key_table.'_head_col2'];
                    $sequence = ($key+1);

                    $sql = "INSERT INTO aicrm_inspectiontemplate_choice( 
						inspectiontemplateid,			choice_type,		choice_title,			sequence,
						head_col0,                      head_col1,         head_col2
						) VALUES (
					   	'".$inspectiontemplateid."',    '".$type."',		'".$title."',			    '".$sequence."',
					   	'".$head_col0."',               '".$head_col1."',   '".$head_col2."'
					)";
//                    echo "<pre>"; print_r($sql); echo "</pre>"; exit;
                    $choiceid = $myLibrary_mysqli->Query($sql);

                    $temp_label = [];

                    foreach ($data['label_' . $key_table] as $index => $value) {

                        $temp_label[] = $data['label_' . $key_table][$index];

                    }

                    foreach ($temp_label as $k => $v) {

                        $sequence_detail = ($k + 1);

                        $sql_list = "INSERT INTO aicrm_inspectiontemplate_choicedetail(
					 		inspectiontemplateid, 			choiceid, 			sequence_detail, 		list,		choice_type
					 	) VALUES (
					 		'" . $inspectiontemplateid . "',	'" . $choiceid . "',	'" . $sequence_detail . "',	'" . $v . "',	'" . $type . "'
					 	)";
                        $myLibrary_mysqli->Query($sql_list);
//                        echo "<pre>"; print_r($sql_list); echo "</pre>"; exit;
                    }

                } else {



                }

            }

        }//Closed If Else Action
//        ptest
//exit;
    }

}//End Clasa libquestionnairetemplate
?>
