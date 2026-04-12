<?php
if ( !defined('BASEPATH') ) exit('No direct script access allowed');
class Home extends MY_Controller
{
  private $description, $title, $keyword, $screen,$modulename;
  public function __construct()
  {
    parent::__construct();
    $meta = $this->config->item('meta');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->module = 'Home';
    $this->lang->load('ai', $this->session->userdata('lang'));
    $this->load->config('api');
    $this->url_service = $this->config->item('service');
    $this->load->model('home_model');
    $this->url_crm = $this->config->item('path_crm');
    $this->url_path = $this->config->item('path');

    $this->params = [
      // 'UserID' => $this->session->userdata('userid'),
      // 'ComputerName' => COMPUTER_NAME,
      'Language' => $this->session->userdata('lang')
      // 'TabID' => $this->tabid
    ];
  }

  public function index()
  {
    $data = array();
    $data['time'] = time();
    $this->template->title('Home',$this->title); 
	  $this->template->screen('Home',$this->screen); 
    $this->template->modulename('home', $this->modulename);
    $this->template->set_metadata('description', "Home"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);

    $data['assigned'] = $this->get_assigned();

    $this->template->set_layout('index');
  	$this->template->build('home', $data);
    // $this->lang->load("ai","thailand");
  }

  public function get_assigned()
  { 
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("home_model");
    $data = $this->home_model->get_assigned();
    
    return $data ;
  }


  public function unit_status()
  {
    $data = array();
    $data['time'] = time();
    $this->template->title('Unit Status',$this->title); 
    $this->template->screen('Unit Status',$this->screen); 
    $this->template->modulename('unit_status', $this->modulename);
    $this->template->set_metadata('description', "Unit Status"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
   
    $this->template->set_layout('unitmatrix');
    $this->template->build('unit_status', $data);
  }

  public function getbranch(){
    
    $post_data = $this->input->post();
    $branch_name = @$post_data['branch_name'];
    $pj_status = @$post_data['pj_status'];
    $crmid = @$post_data['branch_id'];
    $json_url = $this->url_service."branch/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Branch", 
        "crmid" => $crmid,
        "pj_status" => $pj_status,
        "branch_name" => $branch_name,
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
        "orderby"=>"aicrm_branch.branchid,DESC"
    );
    /*echo $json_url;
    echo json_encode($fields); exit;*/
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result['data']);
  }  

  public function getbuilding(){
    $post_data = $this->input->post();
    $branchid = @$post_data['branchid'];
    $building_name = @$post_data['building_name'];
    $bd_status = @$post_data['bd_status'];

    $json_url = $this->url_service."building/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Building", 
        "crmid" => "",
        "branchid" => $branchid,
        "building_name" => $building_name,
        "bd_status" => $bd_status,
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
    );
    
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result['data']);
  } 

  public function getbuilding_group(){
    $post_data = $this->input->post();
    $branchid = @$post_data['branchid'];
    //$building_name = @$post_data['building_name'];
    //$bd_status = @$post_data['bd_status'];

    $json_url = $this->url_service."building/list_content_group";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Building", 
        "crmid" => "",
        "branchgroup" => $branchid,
        //"building_name" => $building_name,
        //"bd_status" => $bd_status,
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
    );
    
    //echo $json_url; echo json_encode($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result['data']);
  } 

  public function getfloor(){
    
    $branchid = $this->input->post('branchid');
    $buildingid = $this->input->post('buildingid');
    $pj_project_type = $this->input->post('pj_project_type');
    
    if($pj_project_type == 'แนวราบ' || $buildingid == 0){
      $a_result = array();
    }else{
      $this->load->library('db_api');
      $this->load->database();
      $this->load->model("home_model");
      $a_result = $this->home_model->get_floor($branchid,$buildingid);
    }
    echo json_encode($a_result);
  }

  public function getunitmatrix(){
    
    $branchid = $this->input->post('branchid');
    $buildingid = $this->input->post('buildingid');
    $floorno = $this->input->post('floorno');
    $inspecttype = $this->input->post('inspecttype');
    $floorno =  str_replace(",","','",$floorno);
    //echo $floorno; exit;
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("home_model");
    $data = $this->home_model->get_unitmatrix($branchid,$buildingid,$floorno,$inspecttype);
    //alert($data); exit;
    $tab ='';
    $floorno =''; 
    $mirror =''; 
    $loopfl ="Y";
    $i=0;

    // getFloor
    $floorlist=array();
    $floor='';
    $totalunit=0;
    $totalNone=0;
    $totalOpen=0;
    $totalClosed=0;
    $totalDealy=0;
    $totalProcessing = 0;
    $i = 0;
    foreach($data as $flr){
      
      if($flr['unit_status']=="None")
      {
        $totalNone=$totalNone+1;
      }
      else if($flr['unit_status']=="Open")
      {
        $totalOpen = $totalOpen+1;
      }
      else if($flr['unit_status']=="Processing")
      {
        $totalProcessing = $totalProcessing+1;
      }
      else if($flr['unit_status']=="Closed")
      {
        $totalClosed = $totalClosed+1;
      }
      
      if($flr['unit_status']=="Open"){

        if($data[$i]['inspection_date'] < date('Y-m-d')){
          $data[$i]['dealy'] = 'Dealy';
          $totalDealy = $totalDealy+1;
        }else{
          $data[$i]['dealy'] = '';
        }
      }else{
        $data[$i]['dealy'] = '';
      }
      
      if($floor !=$flr['floor_no']){
        $floorlist[]['floor'] =  $flr['floor_no'];
        $floor=$flr['floor_no'];
      }

      $data[$i]['inspecttype'] = $inspecttype;
      $i++;
    }
    
    $result_data['rows']  = $data;    
    $result_data["total"] = count($data);
    $result_data['floorlist'] = $floorlist;
    $result_data['totalNone']=$totalNone; 
    $result_data['totalOpen']=$totalOpen;
    $result_data['totalClosed']=$totalClosed;
    $result_data['totalDealy']=$totalDealy;
    $result_data['totalProcessing']=$totalProcessing;
     
    echo json_encode($result_data);
  }

  public function getunitstatus(){
    
    $branchid = $this->input->post('branchid');
    $buildingid = $this->input->post('buildingid');
    $floorno = $this->input->post('floorno');
    $inspecttype = $this->input->post('inspecttype');
    $floorno =  str_replace(",","','",$floorno);
    //echo $floorno; exit;
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("home_model");
    $data = $this->home_model->get_unitstatus($branchid,$buildingid,$floorno,$inspecttype);
    //alert($data); exit;
    $tab ='';
    $floorno =''; 
    $mirror =''; 
    $loopfl ="Y";
    $i=0;

    // getFloor
    $floorlist=array();
    $floor='';
    $totalunit=0;
    $totalNone=0;
    $totalOpen=0;
    $totalClosed=0;
    $totalDealy=0;
    $totalProcessing = 0;
    $i = 0;
    foreach($data as $flr){
      
      if($flr['unit_status']=="None")
      {
        $totalNone=$totalNone+1;
      }
      else if($flr['unit_status']=="Open")
      {
        $totalOpen = $totalOpen+1;
      }
      else if($flr['unit_status']=="Processing")
      {
        $totalProcessing = $totalProcessing+1;
      }
      else if($flr['unit_status']=="Closed")
      {
        $totalClosed = $totalClosed+1;
      }
           
      if($floor !=$flr['floor_no']){
        $floorlist[]['floor'] =  $flr['floor_no'];
        $floor=$flr['floor_no'];
      }

      $data[$i]['inspecttype'] = $inspecttype;
      $i++;
    }
    
    $result_data['rows']  = $data;    
    $result_data["total"] = count($data);
    $result_data['floorlist'] = $floorlist;
    $result_data['totalNone']=$totalNone; 
    $result_data['totalOpen']=$totalOpen;
    $result_data['totalClosed']=$totalClosed;
    $result_data['totalDealy']=$totalDealy;
    $result_data['totalProcessing']=$totalProcessing;
     
    echo json_encode($result_data);
  }

  public function getaccount(){
    $post_data = $this->input->post();
    $account_name = @$post_data['accountname'];
    $mobile = @$post_data['mobile'];
    $phone = @$post_data['phone'];
    $accountid = @$post_data['accountid'];

    $json_url = $this->url_service."Accounts/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Accounts",
      "crmid" => "",
      "accountname" => $account_name,
      "mobile" => $mobile,
      "phone" => $phone,
      "accountid" => $accountid,
      "limit" => "0",
      "offset" => "0",
    );
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    if($result['Type'] == 'S'){
        $data['Type'] = $result['Type'];
        $data['data'] = $result['data'];
        $data['message'] = $result['Message'];
        $data['total'] = $result['total'];
    }else{
       $data['Type'] = $result['Type'];
       $data['data'] = array();
       $data['message'] = $result['Message'];
       $data['total'] = $result['total'];
    }
    echo json_encode($data); 
    //exit;
  }

  public function getfaq() {
    $post_data = $this->input->post();
    $faqname = @$post_data['faqname'];

    $json_url = $this->url_service."faq/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Faq",
      "crmid" => "",
      "faqname" => $faqname,
      "limit" => "0",
      "offset" => "0",
    );
    //echo json_encode($fields); exit;
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
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

  public function countview_faq() {
    $post_data = $this->input->post();
    // alert($post_data); exit();

    $crmid = @$post_data['crmid'];

    $data[] = array(
      // "faq_no" => ""
    );

    $json_url = $this->url_service."faq/update_lead";
    $fields = array(
      'AI-API-KEY' => '1234',
      'model' => "Faq",
      "crmid" => $crmid,
      "action" => "edit",
      "data" => $data,

    );
    //echo $json_url;
    //echo json_encode($fields);exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

    //alert($result); exit;
     // echo json_encode($fields);exit;
    // alert($fields); exit;
    
    echo json_encode($result);

  }

  public function getkm() {
    $post_data = $this->input->post();
    // $knowledgebasename = @$post_data['knowledgebasename'];

    $json_url = $this->url_service."knowledge/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "KnowledgeBase",
      'crmid' => "",
      'userid' => USERID,
      "limit" => "0",
      "offset" => "0",
    );
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
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

  public function countview_km() {
    $post_data = $this->input->post();
    $crmid = @$post_data['crmid'];

    $data[] = array(
      // "knowledgebase_no" => ""
    );

    $json_url = $this->url_service."knowledge/update_lead";
    $fields = array(
      'AI-API-KEY' => '1234',
      'model' => "KnowledgeBase",
      "crmid" => $crmid,
      "action" => "edit",
      "data" => $data,

    );
    //echo $json_url;
    // echo json_encode($fields);exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);


  }

  public function addfavorite() {
    $post_data = $this->input->post();
    $crmid = @$post_data['crmid'];
    $flag = @$post_data['flag'];

    $data[] = array(
      "flag" => $flag,
      "userid" => USERID,
    );

    $json_url = $this->url_service."knowledge/favorite";
    $fields = array(
      'AI-API-KEY' => '1234',
      'model' => "KnowledgeBase",
      "crmid" => $crmid,
      "action" => "edit",
      "data" => $data,
    );
    //echo $json_url;
    //echo json_encode($fields);exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
    
  }

  public function getplant() {
    $post_data = $this->input->post();
    $plantid = @$post_data['plant_id'];

    $json_url = $this->url_service."plant/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Plant",
      'crmid' => "",
      "plantid" => $plantid,
      "limit" => "100",
      "offset" => "0",
    );
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

    // alert($result); 
    
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

  public function getpricelist() {
    $post_data = $this->input->post();
    $accountid = @$post_data['accountid'];

    $json_url = $this->url_service."pricelist/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Plant",
      'crmid' => "",
      "accountid" => $accountid,
      "limit" => "100",
      "offset" => "0",
    );
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

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

  public function gethistorycase() {
    $post_data = $this->input->post();
    $accountid = @$post_data['accountid'];
    $contactid = @$post_data['contactid'];

    $json_url = $this->url_service."helpdesk/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Case",
      'crmid' => "",
      "accountid" => $accountid,
      "contactid" => $contactid,
      "limit" => "0",
      "offset" => "0",
      "orderby"=>"aicrm_crmentity.crmid,DESC"
    );

    $data = array();
    //echo json_encode($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    // alert($result); 
    
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

  public function gethistoryorder() {
    $post_data = $this->input->post();
    $accountid = @$post_data['accountid'];
    $contactid = @$post_data['contactid'];

    $json_url = $this->url_service."order/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Order",
      'crmid' => "",
      "accountid" => $accountid,
      "contactid"=> $contactid,
      "limit" => "0",
      "offset" => "0",
      "orderby" => "aicrm_crmentity.crmid,DESC"
    );
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    // alert($result); 
    
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

  public function getcontact() {
    $post_data = $this->input->post();
    $firstname = @$post_data['firstname'];
    $contactno = @$post_data['contactno'];
    $mobile = @$post_data['mobile'];
    $line_id = @$post_data['line_id'];
    $facebook = @$post_data['facebook'];

    $json_url = $this->url_service."contacts/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => 'Contacts',
      'crmid' => "",
      "firstname" => $firstname,
      "contactno" => $contactno,
      "mobile" => $mobile,
      "line_id" => $line_id,
      "facebook" => $facebook,
      "limit" => "0",
      "offset" => "0",
      "orderby"=> "aicrm_contactdetails.contactid,DESC"
    );
    
    /*echo $json_url;
    echo json_encode($fields); exit;*/
    
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    // alert($result); 
    
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

  public function create_case() {
    $post_data = $this->input->post();
    
    $case_date = '';
    $case_time = '';
    $case_com_date = '';
    $case_com_time = '';

    if($post_data['case_date'] != ""){
      //2020-12-20T02:56
      $date_time = explode("-", $post_data['case_date']);
      $ex_time = explode("T", $date_time['2']);
      $case_date = $date_time['0'].'-'.$date_time['1'].'-'.$ex_time['0'];
      $case_time = $ex_time ['1'];
    }
    if($post_data['date_completed'] != ""){
      //2020-12-20T02:56
      $date_com_time = explode("-", $post_data['date_completed']);
      $ex_com_time = explode("T", $date_com_time['2']);
      $case_com_date = $date_com_time['0'].'-'.$date_com_time['1'].'-'.$ex_com_time['0'];
      $case_com_time = $ex_com_time ['1'];
    }

    $data[] = array(
      "ticket_no" =>'',
      "ticketid" => @$post_data['ticketid'],
      "task_name" => @$post_data['task_name'],
      "status" => @$post_data['status'],
      "contact_channel" => @$post_data['contact_channel'],
      "response" => @$post_data['response'],
      "smownerid" => @$post_data['smownerid'],
      // "date_of_execution" => date("Y-m-d", strtotime(@$post_data['date_of_execution'])),
      //"date_completed" => date("Y-m-d", strtotime(@$post_data['date_completed'])),
      "date_completed" => $case_com_date,
      "time_completed" => $case_com_time,
      "description" => @$post_data['description'],
      "notes" => @$post_data['notes'],
      "handled_by" => @$post_data['handled_by'],
      //"case_date" => date("Y-m-d", strtotime(@$post_data['case_date'])),
      "tel" => $post_data['tel'],
      "email" => $post_data['email'],
      "line_id" => $post_data['line_id'],
      "facebook" => $post_data['facebook'],
      "case_date" => $case_date,
      "case_time" => $case_time,
      "accountid" => @$post_data['accountid'],
      "contactid" => @$post_data['contactid'],
      "case_status" => @$post_data['case_status'],
      "smcreatorid"=> USERID,
    );

    // alert($data);
    //alert($_FILES);
    $json_url = $this->url_service."helpdesk/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "HelpDesk",
        'action' => "add",
        "crmid" => "", 
        'data' => $data,
    );

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    

    if($result['Type'] == 'S'){

	    if(!empty($_FILES['file_upload']['tmp_name'])){
	        //Get folder
	        $day  = date('j');
			$week   = '';

			if($day > 0 && $day <= 7){
				$week = 'week1';
			}
			elseif($day > 7 && $day <= 14){
				$week = 'week2';
			}
			elseif($day > 14 && $day <= 21){
				$week = 'week3';
			}
			elseif($day > 21 && $day <= 28 ){
				$week = 'week4';
			}
			else{
				$week = 'week5';
			}

			$file_maximumsize = 2097152; // Maximum size is 2MB
			$path = "storage/" . date("Y") . "/" . date("F") . "/" . $week . "";
			$url = $this->url_path.$path;
			$data = [];
			$data['folder'] = $this->url_crm;
			$folder = $this->url_crm;
			$temp_path = $folder . "/" . $path;

			if (!is_dir($temp_path)) {
			  $folder2 = $folder . '/storage';
			  // Find each subfolder in storage's image
			  $storage_folder = explode('storage/', $temp_path);
			  $new_folder = explode('/', $storage_folder[1]);

			  for ($i = 0; $i <= count($new_folder) - 2; $i++) {
			      //Create subfolder if it not found folder
			      $folder2 = $folder2 . "/" . $new_folder[$i];

			      if (!is_dir($folder2)) {
			          $old = umask(0);
			          mkdir($folder2 . "/", 0777, true);
			          umask($old);
			      }
			  }
			  $old = umask(0);
			  mkdir($temp_path . "/", 0777, true);
			  umask($old);
			}

	        $tmp_name = $_FILES['file_upload']['tmp_name'];
	        $file_type = $_FILES['file_upload']['type'];
	        $name = basename($_FILES['file_upload']['name']);
	        
	        $this->load->library('db_api');
	        $this->load->database();

	        $this->db->select_max('id', 'next_id');
	        $this->db->from('aicrm_crmentity_seq');
	        $sql = $this->db->get();
	        $result_seq = $sql->row_array();
	        $next_id = $result_seq['next_id'] + 1;
	  
	        $new_name = $next_id."_".$name;

          $new_name_upload = iconv("utf-8", "tis-620", $new_name );

			if (move_uploaded_file($tmp_name, $temp_path."/".$new_name_upload)){

				$userid = USERID;
				$smownerid = USERID;

				$sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";
				$this->db->query($sql_update);

				$sql_insert = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$userid."', '".$smownerid."', 'HelpDesk Attachment', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
				$this->db->query($sql_insert);

				$sql_att = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('" . $next_id . "' , '" . $new_name . "', '" . $file_type . "', '" . $path . "/')";
				$this->db->query($sql_att);

				$crmid = $result['data']['Crmid'];
				$sql_attrel = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$crmid."' , '".$next_id."')";
				$this->db->query($sql_attrel);
			}
	       
	    }//IF
	
	}
    
    echo json_encode($result);

  }

  public function create_order() {
    $post_data = $this->input->post();
    // alert(@$post_data['plan_date']); exit();

    if($post_data['plan_date'] == "") {
      $plan_date = '';
    }else{
      $plan_date = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['plan_date'])));
    }

    $data[] = array(
      "order_no" => '',
      "order_name" => '-',
      // "order_date" => date("Y-m-d", strtotime(@$post_data['order_date'])),
      "order_date" => date("Y-m-d", strtotime(str_replace('/', '-', $post_data['order_date']))),
      "order_status_order" => @$post_data['order_status_order'],
      "completed_sub_status_order" => @$post_data['completed_sub_status_order'],
      "completed_remark" => @$post_data['completed_remark'],
      "lost_reason_order" => @$post_data['lost_reason_order'],
      "account_name" => @$post_data['account_name'],
      "contact_name" => @$post_data['contact_name'],
      "address" => @$post_data['address'],
      "contact_no" => @$post_data['contact_no'],
      "telephone" => @$post_data['telephone'],
      "sales_name" => @$post_data['sales_name'],
      "objective_order" => @$post_data['objective_order'],
      "sales_tel" => @$post_data['sales_tel'],
      "mix_easy_site_code" => @$post_data['mix_easy_site_code'],
      "assigned_user_id" => @$post_data['assigned_user_id'],
      "vendor_site_code" => @$post_data['vendor_site_code'],
      "delivery_location" => @$post_data['delivery_location'],
      "site_person" => @$post_data['site_person'],
      "site_phone_delivery" => @$post_data['site_phone_delivery'],
      "fax_delivery" => @$post_data['fax_delivery'],
      "province_order" => @$post_data['province_order'],
      "billing_name" => @$post_data['billing_name'],
      // "vendor_hq" => @$post_data['vendor_hq'],
      "tax_address" => @$post_data['tax_address'],
      "mailing_address" => @$post_data['mailing_address'],
      "taxpayer_identification_no_bill_to" => @$post_data['taxpayer_identification_no_bill_to'],
      "corporate_registration_number_crn" => @$post_data['corporate_registration_number_crn'],
      "phone_bill_to" => @$post_data['phone_bill_to'],
      "fax_bill_to" => @$post_data['fax_bill_to'],
      "contact_person" => @$post_data['contact_person'],
      "contact_tel" => @$post_data['contact_tel'],
      // "validity" => @$post_data['validity'],
      "validity" => date("Y-m-d", strtotime(str_replace('/', '-', $post_data['validity']))),
      "term_and_condition" => @$post_data['term_and_condition'],
      "description_order" => @$post_data['description_order'],
      
      //"plan_date" => date("Y-m-d", strtotime(str_replace('/', '-', $post_data['plan_date']))),
      "plan_date" => $plan_date,
      // "plan_time" => @$post_data['plan_time'],
      "plan_time" => date("h:i", strtotime(@$post_data['plan_time'])),      
      "payment_method_name" => @$post_data['payment_method_name'],      
      "bank_order" => @$post_data['bank_order'],
      "branch" => @$post_data['branch'],
      "acc_no" => @$post_data['acc_no'],
      "payment_method" => @$post_data['payment_method'],
      "receive_money" => @$post_data['receive_money'],
      "not_match_payment_remark" => @$post_data['not_match_payment_remark'],
      "payment_remark" => @$post_data['payment_remark'],
      "vender_plant" => @$post_data['vender_plant'],
      "payment_code" => @$post_data['payment_code'],
      "vendor_bank" => @$post_data['vendor_bank'],
      "vendor_bank_account" => @$post_data['vendor_bank_account'],
      "credit_term" => @$post_data['credit_term'],
      "description_vendor" => @$post_data['description_vendor'],
      "description" => @$post_data['description'],
      "accountid" => @$post_data['accountid'],
      "contactid" => @$post_data['contactid'],
      "pricetype" => @$post_data['pricetype'],
      "taxtype" => @$post_data['taxtype'],
      "productName1" =>@$post_data['productName1'],
      "ProductId" => @$post_data['ProductId'],
      "km" => @$post_data['km'],
      "zone" => @$post_data['zone'],
      "carsize" => @$post_data['carsize'],
      "unit1" => @$post_data['unit1'],
      "number1" => @$post_data['number1'],
      "priceperunit1" => @$post_data['priceperunit1'],
      "amount1" => @$post_data['amount1'],
      "min" => @$post_data['min'],
      "dlv_c" => @$post_data['dlv_c'],
      "dlv_cvat" => @$post_data['dlv_cvat'],
      "dlv_pvat" => @$post_data['dlv_pvat'],
      "lp" => @$post_data['lp'],
      "discount" => @$post_data['discount'],
      "c_cost" => @$post_data['c_cost'],
      "afterdiscount1" => @$post_data['afterdiscount1'],
      "purchaseamount1" => @$post_data['purchaseamount1'],
      "productName2" => @$post_data['productName2'],
      "unit2" => @$post_data['unit2'],
      "number2" => @$post_data['number2'],
      "priceperunit2" => @$post_data['priceperunit2'],
      "amount2" => @$post_data['amount2'],
      "afterdiscount2" => @$post_data['afterdiscount2'],
      "purchaseamount2" => @$post_data['purchaseamount2'],
      "productName3" => @$post_data['productName3'],
      "unit3" => @$post_data['unit3'],
      "number3" => @$post_data['number3'],
      "priceperunit3" => @$post_data['priceperunit3'],
      "purchaseamount3" => @$post_data['purchaseamount3'],
      "amount3" => @$post_data['amount3'],
      "afterdiscount3" => @$post_data['afterdiscount3'],
      "productName4" => @$post_data['productName4'],
      "unit4" => @$post_data['unit4'],
      "number4" => @$post_data['number4'],
      "priceperunit4" => @$post_data['priceperunit4'],
      "amount4" => @$post_data['amount4'],
      "afterdiscount4" => @$post_data['afterdiscount4'],
      "purchaseamount4" => @$post_data['purchaseamount4'],
      "subTotal1" => @$post_data['subTotal1'],
      "Vat1" => @$post_data['Vat1'],
      "Total1" => @$post_data['Total1'],
      "subTotal2" => @$post_data['subTotal2'],
      "Vat2" => @$post_data['Vat2'],
      "Total2" => @$post_data['Total2'],
      "plantid" => @$post_data['plantid'],
      "project_address" => @$post_data['project_address'],
      "truck_size_order" => @$post_data['truck_size_order'],
      "mat_type_order" => @$post_data['mat_type_order'],
      "descrtion_order" => @$post_data['descrtion_order'],
	    "profit" => @$post_data['profit'],
      "strength_order" => @$post_data['strength_order'],
      "smownerid" => @$post_data['smownerid'],
      //Fig value
      "producttype1" => 'Product',
      "producttype2" => 'Service',
      "queue_qty" => @$post_data['queue_qty'],
      "vendor_register_address" => @$post_data['vendor_register_address'],
      "smcreatorid"=> USERID,
    );

    $json_url = $this->url_service."order/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Order",
      'action' => "add",
      "crmid" => "",
      'data' => $data,
    );

    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  // public function getoder() {
  //   $post_data = $this->input->post();

  //   $json_url = $this->url_service."api/list_content";
  //   $fields = array(
  //       'AI-API-KEY'=>"1234",
  //       'module' => "Order",
  //       'action' => "add",
  //       "crmid" => "",
  //       "crm_subid" => "",
  //       "limit" => "0",
  //       "offset" => "0",
  //       "related_module" => "",
  //       "userid" => "2",
  //   );

  //   // echo json_encode($fields);exit;
  //   //alert($fields); exit;

  //   $response = $this->curl->simple_post($json_url,$fields,array(),"json");
  //   $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
  //   echo json_encode($result);

  // }

  public function getorder() {
    $post_data = $this->input->post();
    $contactid = @$post_data['contactid'];
    $telephone = @$post_data['telephone'];
    $line_id = @$post_data['line_id'];
    $conract_name = @$post_data['conract_name'];
    $project_address = @$post_data['project_address'];
    $delivery_location = @$post_data['delivery_location'];
    $date_from = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['date_from'])));
    $date_to = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['date_to'])));


    $json_url = $this->url_service."order/list_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Order",
      'crmid' => "",
      "accountid" => "",
      "contactid" => $contactid,
      "telephone" => $telephone,
      "line_id" => $line_id,
      "conract_name" => $conract_name,
      "project_address" => $project_address,
      "delivery_location" => $delivery_location,
      "date_from" => $date_from,
      "date_to" => $date_to,
      "limit" => "100",
      "offset" => "0",
      "orderby"=> "aicrm_order.orderid,desc"
    );
    // echo json_encode($fields); exit;
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
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
    // echo json_encode($data); 

    $this->load->view('orderinquiry', $data);
    
  }

  public function plan_get() {

    $post_data = $this->input->post();
    $latitudeplant = @$post_data['latitudeplant'];
    $longitudeplant = @$post_data['longitudeplant'];
    $trucksizeplant = @$post_data['trucksizeplant'];
    $mattypeplant = @$post_data['mattypeplant'];
    //$strengthplant = @$post_data['strengthplant'];
    if(empty($post_data['strengthplant'])){
      $strengthplant = '';
    }else{
      $strengthplant = implode(",", @$post_data['strengthplant']);
    }
    
    $cityplant = @$post_data['cityplant'];

    $json_url = $this->url_service."plant/list_plant";
    $fields = array(
      'AI-API-KEY'=>'1234',
      'model' => "Plant",
      'crmid' => "",
      "latitudeplant" => $latitudeplant,
      "longitudeplant" => $longitudeplant,
      "trucksizeplant" => $trucksizeplant,
      "mattypeplant" => $mattypeplant,
      "strengthplant" => $strengthplant,
      "cityplant" => $cityplant,
      "limit" => "100",
      "offset" => "0",
    );
    //echo $json_url;
    //echo json_encode($fields);exit;

    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

    //alert($result); exit;
    // echo json_encode($fields);exit;
    // alert($fields); exit;

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

  public function create_contacts() {
    $post_data = $this->input->post();
    $action = @$post_data['action'];
    $crmid = @$post_data['crmid'];

    $data[] = array(
      "contact_no" => '',
      "con_contactstatus" => @$post_data['con_contactstatus'],
      "con_thainametitle" => @$post_data['con_thainametitle'],
      "firstname" => @$post_data['firstname'],
      "lastname" => @$post_data['lastname'],
      "mobile" => @$post_data['mobile'],
      "email" => @$post_data['email'],
      "line_id" => @$post_data['line_id'],
      "facebook" => @$post_data['facebook'],
      "remark" => @$post_data['remark'],
      "emotion_details" => @$post_data['emotion_details'],
      "contact_type_details" => @$post_data['contact_type_details'],
      "smownerid"=> USERID,
      "smcreatorid"=> USERID,
    );

    $json_url = $this->url_service."contacts/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Contacts",
      'action' => $action,
      "crmid" => $crmid,
      'data' => $data,
    );

    //echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

    
  }
  public function logout()
  { 
    $this->load->database();
    //$ipaddress = gethostbyaddr($_SERVER['REMOTE_ADDR'])." (".$_SERVER["REMOTE_ADDR"].")";
    $sql = "UPDATE ai_check_user_login SET status = 1 ,end_time = '".$_SESSION["user_start_time"]."' WHERE user_id = '".USERID."' and id = '".$_SESSION["login_id"]."' ";
    //echo $sql; exit;
    $this->db->query($sql);
    session_destroy();
    define("IN_LOGIN", true);
    /*$data['Type'] = 'S';
    $data['data'] = '';
    $data['message'] = 'Log Out Complet';
    echo json_encode($data);*/
    //echo 'http://'.$_SERVER['HTTP_HOST'].'/MOAIDB/';
    redirect('http://'.$_SERVER['HTTP_HOST']);
  }


}
