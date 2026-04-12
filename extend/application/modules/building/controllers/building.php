<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Building extends MY_Controller
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
    $this->url_service= $this->config->item('service');
	  $this->load->model('building_model');
  }
  
  public function index()
  {
    $this->template->title("Building | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Building', $this->screen);
    $this->template->modulename('building', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $data = array();
    //$data['event'] = $this->get_event($productid);
    $this->template->set_layout('theme');
    $this->template->build('index', $data, FALSE, TRUE);
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
    //alert($data); exit;
    echo json_encode($data);
  } 

  public function create_building(){

    $post_data = $this->input->post();
    $crmid = $post_data['crmid'];
    $action = $post_data['action'];
    //$building_name = $post_data['building_name'];
    $data = array(
      "building_no"=>'',
      'building_name' => @$post_data['building_name'],
      'building_name_en' => @$post_data['building_name'],
      'branchid' => @$post_data['branchid'],
      'address1' => @$post_data['address1'],
      'address2' => @$post_data['address2'],
      'building_subdistrict' => @$post_data['building_subdistrict'],
      'building_district' => @$post_data['building_district'],
      'building_province' => @$post_data['building_province'],
      'zipcode' => @$post_data['zipcode'],
      'bd_status' => @$post_data['bd_status'],
      'modifiedby' => $this->session->userdata('user.user_id'),
      'smownerid' => $this->session->userdata('user.user_id'),
    );

    if($action == 'add'){
      $data['smcreatorid'] = $this->session->userdata('user.user_id');
    }
    
    $json_url = $this->url_service."Building/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Building",
        "crmid" => $crmid, 
        'action' => $action,
        'userid' => $this->session->userdata('user.user_id'),
        'data' => $data,
    );

    //alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function deleted(){

    $data = $this->input->post();
    $crmid = $data['crmid'];
 
    $json_url = $this->url_service."Building/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Building",
        "crmid" => $crmid, 
        'action' => 'edit',
        'userid' => $this->session->userdata('user.user_id'),
        'data' => array(
                'deleted' => 1,
                'modifiedby' => $this->session->userdata('user.user_id'),
         ),
    );
    //echo json_encode($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

}