<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inspection extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $meta = $this->config->item('meta');
        $lang = $this->config->item('lang');
        $this->title = $meta["default"]["title"];
        $this->keyword = $meta["default"]["keyword"];
        $this->description = $meta["default"]["description"];
        $this->load->library('curl');
        $this->lang->load('ai',$lang);
        //$this->load->config('api');
        $this->url_service= $this->config->item('service');
        $this->load->model('inspection_model');
    }

    public function index()
      {
        $crmid = $this->input->get('crmid');
//        alert($crmid); exit;
        $this->template->title("Inspection | MEDT"); // 11 words or 70 characters
        $this->template->screen('Inspection', $this->screen);
        $this->template->modulename('product', $this->modulename);
        $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
        $this->template->set_metadata('keywords', $this->keyword);
        $data = array();
        $data['crmid'] = $crmid;

        $value = $this->check_report_inspection($crmid);
//        alert($value);exit;

        $data['start_date'] = $value['start_date'];
        $data['start_time'] = $value['start_time'];

        $data['data_template'] = $this->get_inspection_template($crmid);
        $data['choiceDetailList'] = $this->get_inspection_template8($crmid);
//alert($data);
        $this->template->set_layout('inspection');
        $this->template->build('index', $data, FALSE, TRUE);
      }

    public function check_report_inspection($crmid=null){
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("inspection_model");
        $a_result = $this->inspection_model->get_inspection($crmid);

        if(!empty($a_result)){
            if($a_result[0]['inspection_status']=='Closed'){
                redirect(site_url('inspection/thank?crmid='.$crmid));
                return false;
            }else if($a_result[0]['inspection_status']=='Open'){
                $data['start_date'] = date('Y-m-d');
                $data['start_time'] = date('H:i');
            }else{
                $data['start_date'] = @$a_result[0]['start_date'];
                $data['start_time'] = @$a_result[0]['start_time'];
            }
        }

        return $data;

    }

    public function get_inspection_template($crmid=null){
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("inspection_model");

        $a_result = $this->inspection_model->get_inspection_template($crmid);
        $a_answer = $this->inspection_model->get_inspection_answer($crmid);
//alert($a_answer);exit;
        $answer = array();
        if(!empty($a_answer)){
            foreach ($a_answer as $key => $value) {
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
                    $averag_val =(($value['data_col1']+$value['data_col2']+$value['data_col3']+$value['data_col4']+$value['data_col5']+$value['data_col6']+$value['data_col7']+$value['data_col8'])/$average);
                }
                $value['average']= number_format($averag_val,2);
                $answer[$value['choiceid']][$value['choicedetailid']] = $value;
            }
        }

//        alert($a_answer); exit;
        $data_template = array();

        if(!empty($a_result)){

            $choiceid = '';
            $i= -1;
            foreach ($a_result as $key => $value) {

                if($choiceid != $value['choiceid']){

                    $c=0;
                    $i++;
                    $data_template[$i]['choiceid'] = $value['choiceid'];
                    $data_template[$i]['inspectiontemplateid'] = $value['inspectiontemplateid'];
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
                    $data_template[$i]['list'][$c]['list2'] = $value['list2_template10'];
                    $data_template[$i]['list'][$c]['sublist'] = $value['sublist'];
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
                    $data_template[$i]['list'][$c]['accept_range'] = $value['accept_range'];
                    $data_template[$i]['list'][$c]['answer'] = $answer[$value['choiceid']][$value['choicedetailid']];
                    $choiceid = $value['choiceid'];
                }

            }

        }

        return $data_template;
    }

    public function get_inspection_template8($crmid=null){
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("inspection_model");

        $a_result = $this->inspection_model->get_inspection_template8($crmid);

        $choiceDetailList = [];
        if(!empty($a_result)){

            foreach ($a_result as $key => $value) {
                $choiceDetailList[$value['choiceid']][] = [
                    'sequence_detail' => $value['sequence_detail'],
                    'value' => $value['list2']
                ];
            }

        }

        return $choiceDetailList;
    }

    public function save($data=array()){
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("inspection_model");
        $data = $this->input->post('data');
        $template ='';
        parse_str($data, $template);
        $a_result = $this->inspection_model->insert($template);

        //if($this->input->post('id') == '' ){
            $return['id'] = '';//$a_result['id'];
            $return['Message'] = 'บันทึกสำเร็จ';
            $return['status'] = '1';
        /*}else{
            $return['id'] = $a_result['id'];
            $return['msg'] = 'อัพเดทสำเร็จ';
            $return['status'] = '1';
        }*/

        echo json_encode($return);
    }

    public function save_send($data=array()){
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("inspection_model");
        $data = $this->input->post('data');
        $template ='';
        parse_str($data, $template);
        $a_result = $this->inspection_model->insert_send($template);

        //if($this->input->post('id') == '' ){
            $return['id'] = '';//$a_result['id'];
            $return['Message'] = 'บันทึกสำเร็จ';
            $return['status'] = '1';
        /*}else{
            $return['id'] = $a_result['id'];
            $return['msg'] = 'อัพเดทสำเร็จ';
            $return['status'] = '1';
        }*/

        echo json_encode($return);
    }



    public function thank()
    {
        $crmid = $this->input->get('crmid');
        $this->template->title("Inspection | MEDT"); // 11 words or 70 characters
        $this->template->screen('Inspection', $this->screen);
        $this->template->modulename('product', $this->modulename);
        $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
        $this->template->set_metadata('keywords', $this->keyword);
        $data = array();
        $data['crmid'] = $crmid;
        $data['inspection_no'] = $this->get_inspection_no($crmid);
        //alert($data['inspection_no']);exit;
        $this->template->set_layout('inspection');
        $this->template->build('thank', $data, FALSE, TRUE);
    }

    public function get_inspection_no($crmid=null){
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("inspection_model");
        //$a_result = array();
        $a_result = $this->inspection_model->get_inspection_no($crmid);
        //alert($a_result); exit;
        return @$a_result[0]['inspection_no'];
    }


}
