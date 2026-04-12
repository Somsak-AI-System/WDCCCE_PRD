<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Inspection extends MY_Controller
{

  private $description, $title, $keyword, $screen,$modulename;
  public function __construct()
  {
    parent::__construct();
    $meta = $this->config->item('meta');
    $lang = $this->config->item('lang');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->lang->load('ai',$lang);
    $this->load->config('api');
    $this->url_service = $this->config->item('service');
    $this->url_path = $this->config->item('path');
	  $this->load->model('inspection_model');
    $this->load->library('Excel');
	
  }
  
  public function index()
  {
    $this->template->title("Inspection | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Inspection', $this->screen);
    $this->template->modulename('inspection', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
   
    $this->template->set_layout('theme');
    $data = array();
    //echo $data; exit;
    $data['data_branch'] = $this->get_branch();
    $data['data_building'] = $this->get_building();
    $data['data_unit'] = $this->get_unit();
    $data['data_status'] = $this->get_status();
    $this->template->build('index', $data, FALSE, TRUE);
  }

  public function mnt($crmid=null)
  {
    $this->template->title("Inspection | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Inspection', $this->screen);
    $this->template->modulename('inspection', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('mnt');
    $data = array();
    
    if(empty($crmid)){
      $data['crmid'] = '';
      $data['mod'] = 'add';
      $data['record'] = '';
      $block = $this->get_block();
      $data['block'] = $block;

      foreach($block['data'] as $key => $val){
        //block
        foreach ($val['form'] as $k => $v) {
          //alert($v['columnname']);
          if($v['columnname'] == 'inspection_type'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'inspection_status'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'smownerid'){
            $data[$v['columnname']] = $v;
          }
        }
      }

    }else{
      $data['crmid'] = $crmid;
      $data['mod'] = 'edit';
      $block = $this->get_block($crmid);
      $data['record'] = $block['data'][0]['form'][0]['value'];
      $data['block'] = $block;

      foreach($block['data'] as $key => $val){
        //block
        foreach ($val['form'] as $k => $v) {
          if($v['columnname'] == 'inspection_type'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'inspection_status'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'smownerid'){
            $data[$v['columnname']] = $v;
          }
        }
      }
    }

    //alert($data); exit;
    $data['data_branch'] = $this->get_branch_mnt();
    $data['data_building'] = $this->get_building_mnt();
    $data['data_unit'] = $this->get_unit_mnt();
    $data['data_status'] = $this->get_status();
    //alert($data); exit;
    $this->template->build('mnt', $data, FALSE, TRUE); 
  }

  public function get_block($crmid=null){

    $json_url = $this->url_service."api/list_content";
    //echo $json_url; exit;
    $crmid = ($crmid !=''? $crmid : '');
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Inspection",
        "crmid" => $crmid, 
        'action' => "View",
        'userid' => $this->session->userdata('user.user_id')
    );
    //echo json_encode($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    //alert($result); exit;
    if($result['Type'] == 'S'){
        $data['Type'] = $result['Type'];
        $data['data'] = $result['data'][0]['result'];
        $data['message'] = $result['Message'];
    }else{
       $data['Type'] = $result['Type'];
       $data['data'] = '';
       $data['message'] = $result['Message'];
    }
    //alert($data); exit;
    return $data;
  }

  public function detail($crmid=null)
  {
    $this->template->title("Inspection | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Inspection', $this->screen);
    $this->template->modulename('inspection', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('mnt');
    $data = array();
    $data['crmid'] = @$crmid;
    $data['mod'] = 'detail';
    
    $block = $this->get_block(@$crmid);
    $data['block'] = $block;
    $data['record'] = $block['data'][0]['form'][0]['value'];

    foreach($block['data'] as $key => $val){
      //block
      foreach ($val['form'] as $k => $v) {
        if($v['columnname'] == 'inspection_type'){
          $data[$v['columnname']] = $v;
          $data['inspection_type'] = $v['value'];
        }elseif($v['columnname'] == 'inspection_status'){
          $data[$v['columnname']] = $v;
          $data['inspection_status'] = $v['value'];
        }elseif($v['columnname'] == 'smownerid'){
          $data[$v['columnname']] = $v;
        }
      }
    }
    
    $data['data_branch'] = $this->get_branch();
    $data['data_building'] = $this->get_building();
    $data['data_unit'] = $this->get_unit();
    $data['data_status'] = $this->get_status();
    //$data['url_path'] = $this->url_path;
    $signature_customer = $this->get_signature_customer($crmid);
    $sig_customer ='';
    $sig_contracto = '';
    if(!empty($signature_customer)){
      $sig_customer = $this->url_path.$signature_customer[0]['path'].$signature_customer[0]['name'];
    }
    //$data['signature_contracto'] = $this->get_signature_contractor($crmid);
    $signature_contracto = $this->get_signature_contractor($crmid);
    if(!empty($signature_contracto)){
      $sig_contracto = $this->url_path.$signature_contracto[0]['path'].$signature_contracto[0]['name'];
    }
    $data['sig_contracto'] = $sig_contracto;
    $data['sig_customer'] = $sig_customer;

    
    //alert($data['signature_customer']);exit;
    $data['defect_list'] = json_encode($this->get_defect_list($crmid));
    //alert($data['defect_list']); exit;
    $this->template->build('detail', $data, FALSE, TRUE); 
  
  }
  
  public function duplicate(){

    $data_post = $this->input->post();    
    $crmid = $data_post['crmid'];
    $defectlist = $data_post['notpass'];
    $inspection_date = $data_post['inspection_date'];
    $inspection_time = $data_post['inspection_time'];

    $data = $this->get_inspection_detail($crmid);
    $date = str_replace('/', '-', $data_post['inspection_date']);
    $data[0]['inspection_date'] = date("Y-m-d", strtotime($date));
    //$data[0]['inspection_date'] = date('Y-m-d' ,strtotime($data_post['inspection_date']));
    $data[0]['inspection_time'] = $data_post['inspection_time'];
    $json_url = $this->url_service."indexinsert/insert_content";

    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Inspection",
        "crmid" => $crmid,
        'device' => 'Web',
        'action' => "duplicate",
        "defectlist" => $defectlist,
        'userid' => $this->session->userdata('user.user_id'),
        'data' => $data[0],
    );

    //echo json_encode($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function save(){

    $data = $this->input->post();
    if($data['inspection_date'] != ''){
      $date = str_replace('/', '-', $data['inspection_date']);
      $data['inspection_date'] = date("Y-m-d", strtotime($date));
    }

    if($data['inspection_type'] == 'Contractor'){
      $data['vendor_name'] = $data['customer_name'];
      $data['vendor_mobile'] = $data['phone'];
      $data['vendor_email'] = $data['email'];
      $data['vendor_mobile_other'] = $data['phone_other'];
      $data['customer_name'] = '';
      $data['phone'] = '';
      $data['phone_other'] = '';
      $data['email'] = '';
      $data['show_inspection_list'] = @$data['show_inspection_list'];
      $data['show_defect_list'] = @$data['show_defect_list'];
    }
    

    $crmid = $data['crmid'];
    $action = $data['action'];
    //alert($data); exit;
    $json_url = $this->url_service."indexinsert/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Inspection",
        "crmid" => $crmid, 
        'action' => $action,
        'device' => 'Web',
        'userid' => $this->session->userdata('user.user_id'),
        'data' => $data,
    );
    /*echo $json_url;
    echo json_encode($fields); exit;*/
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function deleted(){

    $data = $this->input->post();
    $crmid = $data['crmid'];
    $json_url = $this->url_service."indexinsert/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Inspection",
        "crmid" => $crmid, 
        'action' => 'edit',
        'device' => 'Web',
        'userid' => $this->session->userdata('user.user_id'),
        'data' => array(
            'deleted' => 1,
         ),
    );
    //echo json_encode($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }
  
  public function get_signature_customer($crmid=null){

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_signature_customer($crmid);
    return $a_result;
  }

  public function get_signature_contractor($crmid=null){

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_signature_contractor($crmid);
    return $a_result;
  }

   public function get_inspection_detail($crmid=null){

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_inspection_detail($crmid);
    return $a_result;
  }

  public function get_defect_list($crmid=null){

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_defect_list($crmid);
    return $a_result;
  }
  public function get_branch(){
    $data = $this->input->post();

    $branchid = $data['branchid'];
    $buildingid = $data['buildingid'];
    $productid = $data['productid'];

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_branch($branchid,$buildingid,$productid);

    return $a_result;
  }
  public function get_building(){
    $data = $this->input->post();

    $branchid = $data['branchid'];
    $buildingid = $data['buildingid'];
    $productid = $data['productid'];

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_building($branchid,$buildingid,$productid);
    //alert( $a_result);exit;
    return $a_result;
  }

  
  public function get_unit(){
    $data = $this->input->post();
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_unit($data);
    return $a_result;
  }

   public function get_status(){
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_status();
    return $a_result;
  }
  
  public function search(){

    $data = $this->input->post();
    $branchid = '';
    $buildingid = '';
    $productid = '';
    $startdate = '';
    $enddate = '';
    $inspection_no = '';
    $inspection_status = '';

    if(!empty($data['branchid'])){
      $branchid = implode( "','", $data['branchid']);
      $branchid = $data['branchid'];
    }
    if(!empty($data['buildingid'])){
      $buildingid = implode( "','", $data['buildingid']);
      $buildingid = $data['buildingid'];
    }
    if(!empty($data['productid'])){
      $productid = implode( "','", $data['productid']);
      $productid = $data['productid'];
    }

    if(!empty($data['inspection_date'])){
      $date = explode("-", $data['inspection_date']);
      $date_0 = str_replace('/', '-', $date[0]);
      $date_1 = str_replace('/', '-', $date[1]);
      $startdate = date("Y-m-d", strtotime($date_0));
      $enddate = date("Y-m-d", strtotime($date_1));
    }

    if(!empty($data['inspection_no'])){
      $inspection_no = $data['inspection_no'];
    }

    if(!empty($data['inspection_status'])){
      $inspection_status = implode( "','", $data['inspection_status']);
      $inspection_status = $data['inspection_status'];
    }
    
    $json_url = $this->url_service."inspectiondefect/list_inspection";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Inspection",
        //"crmid" => $crmid, 
        'action' => "get",
        'branchid' => $branchid,
        'buildingid' => $buildingid,
        'productid' => $productid,
        'startdate' => $startdate,
        'enddate' => $enddate,
        'inspection_no' => $inspection_no,
        'inspection_status' => $inspection_status,
        'userid' => $this->session->userdata('user.user_id'),
        'limit' => '0' ,
        'offset' => '0'
    );
    //echo json_encode($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    //alert($result); exit;
    if($result['Type'] == 'S'){
        $data['Type'] = $result['Type'];
        $data['data'] = $result['data'];
        $data['message'] = $result['Message'];
    }else{
       $data['Type'] = $result['Type'];
       $data['data'] = array();
       $data['message'] = $result['Message'];
    }

    echo json_encode($data);
  }

  public function export(){

    $data = $this->input->post();
    $branchid = '';
    $buildingid = '';
    $productid = '';
    $startdate = '';
    $enddate = '';
    $inspection_no = '';
    $inspection_status = '';

    if(!empty($data['branchid'])){
      $branchid = implode( "','", $data['branchid']);
      $branchid = $data['branchid'];
    }
    if(!empty($data['buildingid'])){
      $buildingid = implode( "','", $data['buildingid']);
      $buildingid = $data['buildingid'];
    }
    if(!empty($data['productid'])){
      $productid = implode( "','", $data['productid']);
      $productid = $data['productid'];
    }

    if(!empty($data['inspection_date'])){
      $date = explode("-", $data['inspection_date']);
      $date_0 = str_replace('/', '-', $date[0]);
      $date_1 = str_replace('/', '-', $date[1]);
      $startdate = date("Y-m-d", strtotime($date_0));
      $enddate = date("Y-m-d", strtotime($date_1));
    }

    if(!empty($data['inspection_no'])){
      $inspection_no = $data['inspection_no'];
    }

    if(!empty($data['inspection_status'])){
      $inspection_status = implode( "','", $data['inspection_status']);
      $inspection_status = $data['inspection_status'];
    }
    
    $fields = array(
        'branchid' => $branchid,
        'buildingid' => $buildingid,
        'productid' => $productid,
        'startdate' => $startdate,
        'enddate' => $enddate,
        'inspection_no' => $inspection_no,
        'inspection_status' => $inspection_status,
        'userid' => $this->session->userdata('user.user_id'),
    );

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_data_export($fields);
    //alert($a_result); exit;
    // create file name
    $fileName = 'Inspection_'.date('YmdHis').'.xlsx';    
    
    // load excel library
    $this->load->library('excel');
  
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    // set Header
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Inspection No');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Inspection Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Inspection Round No');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Inspection Type');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Inspection Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Inspection Time');   
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Inspection Closed Date'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Inspection Closed Time'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Status'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Project Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Building Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Unit Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'House No');
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Room Plan'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Customer Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Customer Mobile'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Customer Mobile Others'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Customer Email'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Contractor Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Contractor Mobile'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Contractor Mobile Others'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Contractor Email');
    $objPHPExcel->getActiveSheet()->SetCellValue('W1', 'Inspector Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('X1', 'Zone'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'Defect Name'); 
    $objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'Defect Status');
    $objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'Description'); 
    

    // set Row
      $rowCount = 2;
      if(!empty($a_result)){
        foreach ($a_result as $key => $val) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val['inspection_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val['inspection_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val['inspection_timeno']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val['inspection_type']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $this->convert_date($val['inspection_date']));
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val['inspection_time']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $this->convert_date($val['inspection_closed_date']));
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val['inspection_closed_time']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $val['inspection_status']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $val['branch_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $val['building_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $val['productname']);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $val['house_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $val['roomplan_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $val['customer_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $val['phone']);
            $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $val['phone_other']);
            $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $val['email']);
            $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $val['vendor_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $val['vendor_mobile']);
            $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, $val['vendor_mobile_other']);
            $objPHPExcel->getActiveSheet()->SetCellValue('V' . $rowCount, $val['vendor_email']);
            $objPHPExcel->getActiveSheet()->SetCellValue('W' . $rowCount, $val['user_inspector']);
            $objPHPExcel->getActiveSheet()->SetCellValue('X' . $rowCount, $val['zone_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('Y' . $rowCount, $val['defect_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('Z' . $rowCount, $val['defectlist_status']);
            $objPHPExcel->getActiveSheet()->SetCellValue('AA' . $rowCount, $val['description']);
            $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($_SERVER['DOCUMENT_ROOT'].'/smartinspection/export/'.$fileName);

        $return['path'] = '/export/'.$fileName;
        $return['message'] = '';
        $return['Type'] = 'S';  
        echo json_encode($return);        
      }else{
        $return['path'] = '';
        $return['message'] = 'No Data';
        $return['Type'] = 'E';  
        echo json_encode($return);  
      }

  }

  public function convert_date($date=null){

    if($date == '0000-00-00' || $date == null || $date == '' ){
      $data = '';
    }else{
      $value = explode("-",$date);
      $data = $value[2].'/'.$value[1].'/'.$value[0];
    }
    return $data;
  }
  
  public function getinspection(){

    $json_url = $this->url_service."indexlist/list_index";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Inspection", 
        'action' => "get",
        'userid' => $this->session->userdata('user.user_id')
    );
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    //alert($result);
    if($result['Type'] == 'S'){
        $data['Type'] = $result['Type'];
        $data['data'] = $result['data'][0]['result'];
        $data['message'] = $result['Message'];
    }else{
       $data['Type'] = $result['Type'];
       $data['data'] = array();
       $data['message'] = $result['Message'];
    }

    echo json_encode($data);
  }


  public function get_branch_mnt(){
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_branch_mnt();
    return $a_result;
  }
  public function get_building_mnt(){
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_building_mnt();
    //alert( $a_result);exit;
    return $a_result;
  }

  public function get_unit_mnt(){
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_unit_mnt();
    return $a_result;
  }
  public function get_unit_inspection($productid=null){
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_unit_inspection($productid);
    return $a_result;
  }
  

  public function get_building_post(){
    $data = $this->input->post();
    $branchid = $data['branchid'];
    
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_building_post($branchid);
   
    echo json_encode($a_result);
  }

  public function get_unit_post(){

    $data = $this->input->post();
    $branchid = @$data['branchid'];
    $buildingid = @$data['buildingid'];

    $data = $this->input->post();
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_unit_post($branchid,$buildingid);

    echo json_encode($a_result);
  }

  public function get_brunch_building(){

    $data = $this->input->post();
    $productid = @$data['productid'];
    
    $data = $this->input->post();
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_result = $this->inspection_model->get_brunch_building($productid);

    echo json_encode($a_result);
  }


  public function quick_mnt($productid=null,$type='Customer')
  {
    if(empty($productid) && $productid == ''){
      redirect(site_url('inspection'));
    }
    $this->template->title("Inspection | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Inspection', $this->screen);
    $this->template->modulename('inspection', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('mnt');
    $data = array();
    
    if(empty($crmid)){
      $data['crmid'] = '';
      $data['mod'] = 'duplicate';
      $data['record'] = '';
      $block = $this->get_block();
      $data['block'] = $block;

      foreach($block['data'] as $key => $val){
        //block
        foreach ($val['form'] as $k => $v) {
          //alert($v['columnname']);
          if($v['columnname'] == 'inspection_type'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'inspection_status'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'smownerid'){
            $data[$v['columnname']] = $v;
          }
        }
      }
    }else{
      $data['crmid'] = $crmid;
      $data['mod'] = 'duplicate';
      $block = $this->get_block($crmid);
      $data['record'] = $block['data'][0]['form'][0]['value'];
      $data['block'] = $block;
      foreach($block['data'] as $key => $val){
        //block
        foreach ($val['form'] as $k => $v) {
          if($v['columnname'] == 'inspection_type'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'inspection_status'){
            $data[$v['columnname']] = $v;
          }elseif($v['columnname'] == 'smownerid'){
            $data[$v['columnname']] = $v;
          }
        }
      }
    }
    //alert($data['block']); exit;
    //$data['data_branch'] = $this->get_branch_mnt();
    //$data['data_building'] = $this->get_building_mnt();
    //$data['data_unit'] = $this->get_unit_mnt();
    $data['data_status'] = $this->get_status();

    $data['type'] = $type;
    $data['product_detail'] = $this->get_unit_inspection($productid);
    $this->template->build('quick_mnt', $data, FALSE, TRUE); 
  }

  public function save_quick(){
    $data = $this->input->post();
    if($data['inspection_date'] != ''){
      $date = str_replace('/', '-', $data['inspection_date']);
      $data['inspection_date'] = date("Y-m-d", strtotime($date));
    }

    if($data['inspection_type'] == 'Contractor'){
      $data['vendor_name'] = $data['customer_name'];
      $data['vendor_mobile'] = $data['phone'];
      $data['vendor_email'] = $data['email'];
      $data['vendor_mobile_other'] = $data['phone_other'];
      $data['customer_name'] = '';
      $data['phone'] = '';
      $data['phone_other'] = '';
      $data['email'] = '';
      $data['show_inspection_list'] = @$data['show_inspection_list'];
      $data['show_defect_list'] = @$data['show_defect_list'];
    }
    $crmid = $data['crmid'];
    $action = $data['action'];

    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("inspection_model");
    $a_check = $this->inspection_model->check_inspection($data['branchid'],$data['buildingid'],$data['productid'],$data['inspection_type']);

    if(empty($a_check)){
      $crmid = '';
      $defectlist = '';
      $action = 'add';
    }else{
      $crmid = $a_check[0]['inspectionid'];
      $defectlist = $a_check[0]['notpass'];
    }
    
    $json_url = $this->url_service."indexinsert/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Inspection",
      "crmid" => $crmid, 
      'action' => $action,
      "defectlist" => $defectlist,
      'device' => 'Web',
      'userid' => $this->session->userdata('user.user_id'),
      'data' => $data,
    );

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }


}