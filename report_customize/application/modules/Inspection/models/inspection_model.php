<?php

class inspection_model extends CI_Model
{

    function __construct()
    {
        $this->CI = get_instance();
    }

    function get_inspection_template($crmid = null)
    {

        $sql = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.* 
			FROM aicrm_inspectiontemplate
			INNER JOIN aicrm_inspection on aicrm_inspection.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid
			INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
			LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
			WHERE aicrm_inspection.inspectionid = '" . $crmid . "' 
			order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function get_inspection_template8($crmid = null)
    {

        $sql = "SELECT  aicrm_inspectiontemplate_choice.*, aicrm_inspectiontemplate_choicedetail.* , aicrm_inspectiontemplate_choicedetail2.* 
			FROM aicrm_inspectiontemplate
			INNER JOIN aicrm_inspection on aicrm_inspection.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid
			INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
			LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
			INNER JOIN aicrm_inspectiontemplate_choicedetail2 ON aicrm_inspectiontemplate_choicedetail.choicedetailid = aicrm_inspectiontemplate_choicedetail2.choiceid 
			WHERE aicrm_inspection.inspectionid = '" . $crmid . "' 
			order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);

        return $result;
    }

    function get_inspection_answer($crmid = null)
    {

        $sql = "SELECT aicrm_inspection_answer.* 
				FROM aicrm_inspection_answer 
				INNER JOIN aicrm_inspection on aicrm_inspection.inspectionid = aicrm_inspection_answer.inspectionid
				LEFT JOIN aicrm_inspectiontemplate on aicrm_inspectiontemplate.inspectiontemplateid = aicrm_inspection_answer.inspectiontemplateid 
				and aicrm_inspection.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid
				WHERE aicrm_inspection.inspectionid = '" . $crmid . "' 
				order by aicrm_inspection_answer.choiceid , aicrm_inspection_answer.choicedetailid asc";
        //		echo $sql;exit;
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function get_inspection($crmid = null)
    {
        $sql = "SELECT aicrm_inspection.inspection_status,aicrm_inspection.start_date,aicrm_inspection.start_time
				from aicrm_inspection
				inner join aicrm_inspectioncf on aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
				where aicrm_crmentity.deleted = 0 and aicrm_inspection.inspectionid = '" . $crmid . "' ";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function get_inspection_no($crmid = null)
    {
        $sql = "SELECT aicrm_inspection.inspection_no
				from aicrm_inspection
				inner join aicrm_inspectioncf on aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
				where aicrm_crmentity.deleted = 0 and aicrm_inspection.inspectionid = '" . $crmid . "' ";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function get_choicedetail($crmid = null)
    {

        $sql = "SELECT  aicrm_inspectiontemplate_choicedetail.* 
			FROM aicrm_inspectiontemplate
			INNER JOIN aicrm_inspection on aicrm_inspection.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid
			INNER JOIN aicrm_inspectiontemplate_choice on aicrm_inspectiontemplate_choice.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid 
			LEFT JOIN aicrm_inspectiontemplate_choicedetail on aicrm_inspectiontemplate_choicedetail.choiceid = aicrm_inspectiontemplate_choice.choiceid
			WHERE aicrm_inspection.inspectionid = '" . $crmid . "' 
			order by aicrm_inspectiontemplate_choice.sequence , aicrm_inspectiontemplate_choicedetail.sequence_detail asc";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function get_answer($crmid = null)
    {

        $sql = "SELECT aicrm_inspection_answer.* 
				FROM aicrm_inspection_answer 
				INNER JOIN aicrm_inspection on aicrm_inspection.inspectionid = aicrm_inspection_answer.inspectionid
				LEFT JOIN aicrm_inspectiontemplate on aicrm_inspectiontemplate.inspectiontemplateid = aicrm_inspection_answer.inspectiontemplateid 
				and aicrm_inspection.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid
				WHERE aicrm_inspection.inspectionid = '" . $crmid . "' 
				order by aicrm_inspection_answer.choiceid , aicrm_inspection_answer.choicedetailid asc";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function insert($data)
    {

        $date = date('Y-m-d H:i:s');
        $crmid = $data['crmid'];

        $start_date = $data['start_date'];
        $start_time = $data['start_time'];

        $sql_answer = "DELETE FROM aicrm_inspection_answer WHERE inspectionid = '" . $crmid . "' ";
        $this->db->query($sql_answer);

        foreach ($data['tables_parameter'] as $key => $val) { //Choice
            $key_table = $val; //Key ของ Table Choice
            $type = $data[$key_table . '_type']; //ประเภทของ Choice

            if ($type == 'template1') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $datatemplate = $data[$key_tr . '_template1text'];

                    $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid, 
					 		detail_template1
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $datatemplate . "'
					 	)";
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template2') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template2
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template3') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template3
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template4') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $datatemplate = $data[$key_tr . '_result'];

                    for ($i = 0; $i < count($datatemplate); $i++) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid, 
					 		status_list_template4
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $datatemplate[$i] . "'
					 	)";
                    }

                    //                        echo $sql_list;
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template5') {
                //alert($data);
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $datatemplate = $data[$key_tr . '_notetext'];

                    for ($i = 0; $i < count($datatemplate); $i++) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid, 
					 		comment
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $datatemplate . "'
					 	)";
                    }

                    //                        echo $sql_list;
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template6') {
                //                    alert($data);

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                //alert($data);
                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];
                    //                        alert($result);
                    $check_result = [];

                    foreach ($result as $val_value) {
                        //                            alert($val_value);
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            //                                alert($val_choicedetailid);
                            if ($val_value === $val_choicedetailid) {
                                //                                    alert($val_choicedetailid);

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template6
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template7') {
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template7
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $choicedetailid . "', '" . $result . "'
					 	)";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";

                }
            } else if ($type == 'template8') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template8
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template9') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                //                    alert($data);exit;
                $val_date = $data[$key_table . 'list'];
                //                    alert($val_date);exit;
                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List

                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $val_col1 = $data[$key_tr . '_data_col1'];
                    $val_col2 = $data[$key_tr . '_data_col2'];
                    $val_col3 = $data[$key_tr . '_data_col3'];
                    $val_col4 = $data[$key_tr . '_data_col4'];
                    $val_col5 = $data[$key_tr . '_data_col5'];
                    $val_col6 = $data[$key_tr . '_data_col6'];
                    $val_col7 = $data[$key_tr . '_data_col7'];
                    $val_col8 = $data[$key_tr . '_data_col8'];


                    $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid,
					 		data_col1, 					data_col2, 				data_col3, 			data_col4,		data_col5,
					 		data_col6,					data_col7,				data_col8,          detail_template9
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $val_col1 . "',			'" . @$val_col2 . "',		'" . @$val_col3 . "',	'" . @$val_col4 . "',		'" . @$val_col5 . "',
					 		'" . @$val_col6 . "',			'" . @$val_col7 . "',		'" . @$val_col8 . "',   '" . @$val_date . "'
					 	)";
                    $this->db->query($sql_list);
                    //                        echo $sql_list; echo "<br>";exit;
                }
            } else if ($type == 'template10') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                //                    alert($data);
                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 1; $j++) {
                            $rowData[$id][$j] = 0;
                            //                                alert($data[$key_table.'_list3_'.$i.'_'.$id]);
                            //                                alert($data[$key_table.'_list2_'.$id]);exit;
                            if (isset($data[$key_table . '_list2_' . $id])) {
                                foreach ($data[$key_table . '_list2_' . $id] as $ans2) {
                                    $rowData[$id]['Value'] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_list3_' . $i . '_' . $id])) {
                                foreach ($data[$key_table . '_list3_' . $i . '_' . $id] as $ans) {
                                    //                                        alert($ans);
                                    $rowData[$id]['Ans'] = $ans;
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                //                    alert($data);
                //                    exit;

                foreach ($rowData as $key => $value) {

                    $id = $key;

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template10, detail_template10
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $value['Ans'] . "', '" . $value['Value'] . "'
                        )";

                    $this->db->query($sql_list);
                }
            } else if ($type == 'template11') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template11
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template12') {

                //                    alert($data);
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template12, data_list_template12
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template13') {

                //                    alert($data);exit;
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template13, data_list_template13
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template14') {
                //echo 555;exit;
                //                    alert($data);
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);exit;
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template14_1, status_list_template14_2
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template15') {
                //echo 555;exit;
                //                    alert($data);exit;
                //                    $choiceid = $data[$key_table . '_choiceid'];//
                //                    $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                //
                ////                    alert($data);
                //                    foreach ($data[$key_table . '_rows'] as $k => $v) {
                //
                //                        $key_tr = $v; //Key ของ Tr Choice List
                //
                //                        $choicedetailid = $data[$key_tr . '_choicedetailid'];
                //                        $val_col1 = $data[$key_tr . '_list1'];
                //                        $val_col2 = $data[$key_tr . '_list2'];
                //                        $val_col3 = $data[$key_tr . '_list3'];
                ////                        $val_col4 = $data[$key_tr . '_list4'];
                //
                //                        $status_list_template15_1 = 0;
                //                        if ($val_col3 == 1){
                //                            $status_list_template15_1 =1;
                //                        }
                //
                //                        $sql_list = "INSERT INTO aicrm_inspection_answer(
                //					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid,
                //					 		data_list_template15_1, 	data_list_template15_2,  status_list_template15_1
                //					 	) VALUES (
                //					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
                //					 		'" . $val_col1 . "',			'" . @$val_col2 . "',		'" . @$status_list_template15_1 . "'
                //					 	)";
                //                        $this->db->query($sql_list);
                ////                        echo $sql_list; echo "<br>";exit;
                //                    }

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                //                    alert($data);
                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];
                        //alert($id);exit;
                        for ($j = 1; $j <= 1; $j++) {
                            $rowData[$id][$j] = 0;
                            //                                alert($data[$key_table.'_list3_'.$i.'_'.$id]);
                            //                                alert($data[$key_table.'_list1_'.$id]);exit;
                            if (isset($data[$key_table . '_list1_' . $id])) {
                                foreach ($data[$key_table . '_list1_' . $id] as $ans1) {
                                    $rowData[$id]['Value1'] = $ans1;
                                }
                            }
                            if (isset($data[$key_table . '_list2_' . $id])) {
                                foreach ($data[$key_table . '_list2_' . $id] as $ans2) {
                                    $rowData[$id]['Value2'] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_list3_' . $i . '_' . $id])) {
                                foreach ($data[$key_table . '_list3_' . $i . '_' . $id] as $ans) {
                                    //                                        alert($ans);
                                    $rowData[$id]['Ans'] = $ans;
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                //                    alert($data);
                //                    exit;

                foreach ($rowData as $key => $value) {

                    $id = $key;

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, data_list_template15_1, 	data_list_template15_2,  status_list_template15_1
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "',  '" . $value['Value1'] . "', '" . $value['Value2'] . "', '" . $value['Ans'] . "'
                        )";

                    //                        echo $sql_list;exit;
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template16') {
                //echo 555;exit;
                //                    alert($data);exit;
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 3; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);exit;
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    $v3 = $value[3];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template16_1, status_list_template16_2, status_list_template16_3
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "', '" . $v3 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template17') {
                //echo 555;exit;
                //                    alert($data);exit;
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }

                $val_col1 = $data[$key_table . '_list1'];
                $val_col2 = $data[$key_table . '_list2'];

                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        alert($val_col2);exit;
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template17_1, status_list_template17_2, data_list_template17_1, data_list_template17_2
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "', '" . $val_col1 . "', '" . $val_col2 . "'
					    )";
                    //                        echo $sql_list; echo "<br>";exit;

                    $this->db->query($sql_list);
                }
            } else if ($type == 'template18') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {

                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];
                        //alert($id); echo '=========';
                        for ($j = 1; $j <= 1; $j++) {
                            $rowData[$id][$j] = 0;
                            //                                alert($data);exit;
                            if (isset($data[$key_table . '_list1_' . $id])) {
                                foreach ($data[$key_table . '_list1_' . $id] as $ans1) {
                                    $rowData[$id]['Value1'] = $ans1;
                                }
                            }
                            if (isset($data[$key_table . '_list2_' . $i . '_' . $id])) {
                                foreach ($data[$key_table . '_list2_' . $i . '_' . $id] as $ans) {
                                    //                                        alert($ans);
                                    $rowData[$id]['Ans'] = $ans;
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                //                    alert($data);
                //                    exit;

                foreach ($rowData as $key => $value) {

                    $id = $key;

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, data_list_template18_2,   status_list_template18_1
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "',  '" . $value['Value1'] . "', '" . $value['Ans'] . "'
                        )";

                    //                        echo $sql_list;exit;
                    $this->db->query($sql_list);
                }

                /*//echo 555;exit;
//                    alert($data);exit;
                    $choiceid = $data[$key_table.'_choiceid'];//
                    $inspectiontemplateid = $data[$key_table.'_inspectiontemplateid'];

                    $choiceid = $data[$key_table . '_choiceid'];//
                    $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

//                    alert($data);
                    foreach ($data[$key_table . '_rows'] as $k => $v) {
//alert($v);exit;
                        $key_tr = $v; //Key ของ Tr Choice List

                        $choicedetailid = $data[$key_tr . '_choicedetailid'];
                        $val_col1 = $data[$key_tr . '_list1'];
                        $val_col2 = $data[$key_tr . '_list2'];

//alert($val_col1);
                        $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid,
					 		data_list_template18_1, 	data_list_template18_2
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $val_col1 . "',			'" . @$val_col2 . "'
					 	)";

                        $this->db->query($sql_list);
//                        echo $sql_list; echo "<br>";exit;
                    }*/
            }
        }

        $sql = "UPDATE aicrm_inspection SET inspection_status='Processing' , start_date = '" . $start_date . "' ,start_time = '" . $start_time . "'  WHERE inspectionid = '" . $crmid . "' ";
        $this->db->query($sql);

        return true;
    }


    function insert_send($data)
    {

        $date = date('Y-m-d H:i:s');
        $crmid = $data['crmid'];

        $start_date = $data['start_date'];
        $start_time = $data['start_time'];

        $sql_answer = "DELETE FROM aicrm_inspection_answer WHERE inspectionid = '" . $crmid . "' ";
        $this->db->query($sql_answer);

        foreach ($data['tables_parameter'] as $key => $val) { //Choice
            $key_table = $val; //Key ของ Table Choice
            $type = $data[$key_table . '_type']; //ประเภทของ Choice

            if ($type == 'template1') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $datatemplate = $data[$key_tr . '_template1text'];

                    $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid, 
					 		detail_template1
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $datatemplate . "'
					 	)";
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template2') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template2
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template3') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template3
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template4') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $datatemplate = $data[$key_tr . '_result'];

                    for ($i = 0; $i < count($datatemplate); $i++) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid, 
					 		status_list_template4
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $datatemplate[$i] . "'
					 	)";
                    }

                    //                        echo $sql_list;
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template5') {
                //alert($data);
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $datatemplate = $data[$key_tr . '_notetext'];

                    for ($i = 0; $i < count($datatemplate); $i++) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid, 
					 		comment
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $datatemplate . "'
					 	)";
                    }

                    //                        echo $sql_list;
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template6') {
                //                    alert($data);

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                //alert($data);
                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];
                    //                        alert($result);
                    $check_result = [];

                    foreach ($result as $val_value) {
                        //                            alert($val_value);
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            //                                alert($val_choicedetailid);
                            if ($val_value === $val_choicedetailid) {
                                //                                    alert($val_choicedetailid);

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template6
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template7') {
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template7
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $choicedetailid . "', '" . $result . "'
					 	)";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";

                }
            } else if ($type == 'template8') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {
                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template8
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template9') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                //                    alert($data);exit;
                $val_date = $data[$key_table . 'list'];
                //                    alert($val_date);exit;
                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List

                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $val_col1 = $data[$key_tr . '_data_col1'];
                    $val_col2 = $data[$key_tr . '_data_col2'];
                    $val_col3 = $data[$key_tr . '_data_col3'];
                    $val_col4 = $data[$key_tr . '_data_col4'];
                    $val_col5 = $data[$key_tr . '_data_col5'];
                    $val_col6 = $data[$key_tr . '_data_col6'];
                    $val_col7 = $data[$key_tr . '_data_col7'];
                    $val_col8 = $data[$key_tr . '_data_col8'];


                    $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid,
					 		data_col1, 					data_col2, 				data_col3, 			data_col4,		data_col5,
					 		data_col6,					data_col7,				data_col8,          detail_template9
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $val_col1 . "',			'" . @$val_col2 . "',		'" . @$val_col3 . "',	'" . @$val_col4 . "',		'" . @$val_col5 . "',
					 		'" . @$val_col6 . "',			'" . @$val_col7 . "',		'" . @$val_col8 . "',   '" . @$val_date . "'
					 	)";
                    $this->db->query($sql_list);
                    //                        echo $sql_list; echo "<br>";exit;
                }
            } else if ($type == 'template10') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                //                    alert($data);
                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 1; $j++) {
                            $rowData[$id][$j] = 0;
                            //                                alert($data[$key_table.'_list3_'.$i.'_'.$id]);
                            //                                alert($data[$key_table.'_list2_'.$id]);exit;
                            if (isset($data[$key_table . '_list2_' . $id])) {
                                foreach ($data[$key_table . '_list2_' . $id] as $ans2) {
                                    $rowData[$id]['Value'] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_list3_' . $i . '_' . $id])) {
                                foreach ($data[$key_table . '_list3_' . $i . '_' . $id] as $ans) {
                                    //                                        alert($ans);
                                    $rowData[$id]['Ans'] = $ans;
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                //                    alert($data);
                //                    exit;

                foreach ($rowData as $key => $value) {

                    $id = $key;

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template10, detail_template10
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $value['Ans'] . "', '" . $value['Value'] . "'
                        )";

                    $this->db->query($sql_list);
                }
            } else if ($type == 'template11') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                foreach ($data[$key_table . '_rows'] as $k => $v) {

                    $key_tr = $v; //Key ของ Tr Choice List
                    $choicedetailid = $data[$key_tr . '_choicedetailid'];
                    $result = @$data[$key_tr . '_result'];

                    $check_result = [];

                    foreach ($result as $val_value) {
                        $default = 0;
                        foreach ($choicedetailid as $val_choicedetailid) {
                            if ($val_value === $val_choicedetailid) {

                                $default = 1;
                                $check_result[] = [
                                    'choiceDetailid' => $val_value,
                                    'Value' => $default
                                ];
                            }
                        }
                    }

                    foreach ($check_result as $value) {

                        $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template11
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $value['choiceDetailid'] . "', '" . $value['Value'] . "'
					 	)";

                        $this->db->query($sql_list);
                        //                            echo $sql_list; echo "<br>";
                    }
                }
            } else if ($type == 'template12') {

                //                    alert($data);
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template12, data_list_template12
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template13') {

                //                    alert($data);exit;
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template13, data_list_template13
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template14') {
                //echo 555;exit;
                //                    alert($data);
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);exit;
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template14_1, status_list_template14_2
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template15') {
                //echo 555;exit;
                //                    alert($data);exit;
                //                    $choiceid = $data[$key_table . '_choiceid'];//
                //                    $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];
                //
                ////                    alert($data);
                //                    foreach ($data[$key_table . '_rows'] as $k => $v) {
                //
                //                        $key_tr = $v; //Key ของ Tr Choice List
                //
                //                        $choicedetailid = $data[$key_tr . '_choicedetailid'];
                //                        $val_col1 = $data[$key_tr . '_list1'];
                //                        $val_col2 = $data[$key_tr . '_list2'];
                //                        $val_col3 = $data[$key_tr . '_list3'];
                ////                        $val_col4 = $data[$key_tr . '_list4'];
                //
                //                        $status_list_template15_1 = 0;
                //                        if ($val_col3 == 1){
                //                            $status_list_template15_1 =1;
                //                        }
                //
                //                        $sql_list = "INSERT INTO aicrm_inspection_answer(
                //					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid,
                //					 		data_list_template15_1, 	data_list_template15_2,  status_list_template15_1
                //					 	) VALUES (
                //					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
                //					 		'" . $val_col1 . "',			'" . @$val_col2 . "',		'" . @$status_list_template15_1 . "'
                //					 	)";
                //                        $this->db->query($sql_list);
                ////                        echo $sql_list; echo "<br>";exit;
                //                    }

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                //                    alert($data);
                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];
                        //alert($id);exit;
                        for ($j = 1; $j <= 1; $j++) {
                            $rowData[$id][$j] = 0;
                            //                                alert($data[$key_table.'_list3_'.$i.'_'.$id]);
                            //                                alert($data[$key_table.'_list1_'.$id]);exit;
                            if (isset($data[$key_table . '_list1_' . $id])) {
                                foreach ($data[$key_table . '_list1_' . $id] as $ans1) {
                                    $rowData[$id]['Value1'] = $ans1;
                                }
                            }
                            if (isset($data[$key_table . '_list2_' . $id])) {
                                foreach ($data[$key_table . '_list2_' . $id] as $ans2) {
                                    $rowData[$id]['Value2'] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_list3_' . $i . '_' . $id])) {
                                foreach ($data[$key_table . '_list3_' . $i . '_' . $id] as $ans) {
                                    //                                        alert($ans);
                                    $rowData[$id]['Ans'] = $ans;
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                //                    alert($data);
                //                    exit;

                foreach ($rowData as $key => $value) {

                    $id = $key;

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, data_list_template15_1, 	data_list_template15_2,  status_list_template15_1
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "',  '" . $value['Value1'] . "', '" . $value['Value2'] . "', '" . $value['Ans'] . "'
                        )";

                    //                        echo $sql_list;exit;
                    $this->db->query($sql_list);
                }
            } else if ($type == 'template16') {
                //echo 555;exit;
                //                    alert($data);exit;
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 3; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);exit;
                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    $v3 = $value[3];
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template16_1, status_list_template16_2, status_list_template16_3
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "', '" . $v3 . "'
					    )";

                    $this->db->query($sql_list);
                    //                            echo $sql_list; echo "<br>";
                }
            } else if ($type == 'template17') {
                //echo 555;exit;
                //                    alert($data);exit;
                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {
                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];

                        for ($j = 1; $j <= 2; $j++) {
                            $rowData[$id][$j] = 0;

                            if (isset($data[$key_table . '_level' . $j . '_' . $id])) {
                                foreach ($data[$key_table . '_level' . $j . '_' . $id] as $ans2) {
                                    $rowData[$id][$j] = $ans2;
                                }
                            }
                            if (isset($data[$key_table . '_level' . $j])) {
                                foreach ($data[$key_table . '_level' . $j] as $ans) {
                                    if ($ans == $id) {
                                        $rowData[$id][$j] = 1;
                                    }
                                }
                            }
                        }
                    }
                }

                $val_col1 = $data[$key_table . '_list1'];
                $val_col2 = $data[$key_table . '_list2'];

                foreach ($rowData as $key => $value) {
                    $id = $key;
                    $v1 = $value[1];
                    $v2 = $value[2];
                    //                        alert($val_col2);exit;
                    //                        echo $id.' '.$v1.' '.$v2.'<br />';

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, status_list_template17_1, status_list_template17_2, data_list_template17_1, data_list_template17_2
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "', '" . $v1 . "', '" . $v2 . "', '" . $val_col1 . "', '" . $val_col2 . "'
					    )";
                    //                        echo $sql_list; echo "<br>";exit;

                    $this->db->query($sql_list);
                }
            } else if ($type == 'template18') {

                $choiceid = $data[$key_table . '_choiceid']; //
                $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

                $rowData = [];
                if (isset($data[$key_table . '_choicedetailid']) && count($data[$key_table . '_choicedetailid']) > 0) {

                    for ($i = 0; $i < count($data[$key_table . '_choicedetailid']); $i++) {
                        $id = $data[$key_table . '_choicedetailid'][$i];
                        //alert($id); echo '=========';
                        for ($j = 1; $j <= 1; $j++) {
                            $rowData[$id][$j] = 0;
                            //                                alert($data);exit;
                            if (isset($data[$key_table . '_list1_' . $id])) {
                                foreach ($data[$key_table . '_list1_' . $id] as $ans1) {
                                    $rowData[$id]['Value1'] = $ans1;
                                }
                            }
                            if (isset($data[$key_table . '_list2_' . $i . '_' . $id])) {
                                foreach ($data[$key_table . '_list2_' . $i . '_' . $id] as $ans) {
                                    //                                        alert($ans);
                                    $rowData[$id]['Ans'] = $ans;
                                }
                            }
                        }
                    }
                }
                //                    alert($rowData);
                //                    alert($data);
                //                    exit;

                foreach ($rowData as $key => $value) {

                    $id = $key;

                    $sql_list = "INSERT INTO aicrm_inspection_answer(inspectiontemplateid, inspectionid, choice_type, choiceid, choicedetailid, data_list_template18_2,   status_list_template18_1
                        ) VALUES (
                        '" . $inspectiontemplateid . "','" . $crmid . "', '" . $type . "',	'" . $choiceid . "', '" . $id . "',  '" . $value['Value1'] . "', '" . $value['Ans'] . "'
                        )";

                    //                        echo $sql_list;exit;
                    $this->db->query($sql_list);
                }

                /*//echo 555;exit;
//                    alert($data);exit;
                    $choiceid = $data[$key_table.'_choiceid'];//
                    $inspectiontemplateid = $data[$key_table.'_inspectiontemplateid'];

                    $choiceid = $data[$key_table . '_choiceid'];//
                    $inspectiontemplateid = $data[$key_table . '_inspectiontemplateid'];

//                    alert($data);
                    foreach ($data[$key_table . '_rows'] as $k => $v) {
//alert($v);exit;
                        $key_tr = $v; //Key ของ Tr Choice List

                        $choicedetailid = $data[$key_tr . '_choicedetailid'];
                        $val_col1 = $data[$key_tr . '_list1'];
                        $val_col2 = $data[$key_tr . '_list2'];

//alert($val_col1);
                        $sql_list = "INSERT INTO aicrm_inspection_answer(
					 		inspectiontemplateid, 		inspectionid, 			choice_type, 		choiceid, 		choicedetailid,
					 		data_list_template18_1, 	data_list_template18_2
					 	) VALUES (
					 		'" . $inspectiontemplateid . "','" . $crmid . "',			'" . $type . "',		'" . $choiceid . "',		'" . $choicedetailid . "',
					 		'" . $val_col1 . "',			'" . @$val_col2 . "'
					 	)";

                        $this->db->query($sql_list);
//                        echo $sql_list; echo "<br>";exit;
                    }*/
            }
        }

        $sql = "UPDATE aicrm_inspection SET inspection_status='Closed' , start_date = '" . $start_date . "' ,start_time = '" . $start_time . "' ,end_date = '" . $end_date . "' ,end_time = '" . $end_time . "' WHERE inspectionid = '" . $crmid . "' ";
        $this->db->query($sql);

        return true;
    }
}
