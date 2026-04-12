<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Defect extends MY_Controller
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
	  $this->load->model('defect_model');

  }

  public function index()
  {
    $this->template->title("Defect | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Defect', $this->screen);
    $this->template->modulename('defect', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('theme');
    $data = array();
    $this->template->build('index', $data, FALSE, TRUE);

  }
  public function get_zone(){
    $post_data = $this->input->post();
    $json_url = $this->url_service."zone/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Zone",
        "crmid" => "",
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
        'orderby' => "aicrm_zone.zone_name,ASC"
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

    echo json_encode($data);
  }

  public function getroomplan(){
    $post_data = $this->input->post();
    $roomplan_name = @$post_data['roomplan_name'];
    $json_url = $this->url_service."roomplan/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Roomplan",
        "crmid" => "",
        "roomplan_name" => "",
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
    );
    
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

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

  public function getdefect(){

    $post_data = $this->input->post();
    $zoneid = @$post_data['zoneid'];
    $defect_name = @$post_data['defect_name'];

    $json_url = $this->url_service."defect/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Defect",
        "crmid" => "",
        "zoneid" => $zoneid,
        "defect_name" => $defect_name,
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
    );
    /*echo $json_url;
    echo json_encode($fields); exit;*/
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

  public function create(){

    $post_data = $this->input->post();
    $crmid = $post_data['crmid'];
    $action = $post_data['action'];
    $data = array(
      "defect_no"=>'',
      'zone_name' => @$post_data['zone_name'],
      'defect_name' => @$post_data['defect_name'],
      'defect_status' => @$post_data['defect_status'],
      'roomplanid' => @$post_data['roomplanid'],
      'modifiedby' => $this->session->userdata('user.user_id'),
      'smownerid' => $this->session->userdata('user.user_id'),
    );

    if($action == 'add'){
      $data['smcreatorid'] = $this->session->userdata('user.user_id');
    }

    $json_url = $this->url_service."Defect/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Defect",
        "crmid" => $crmid,
        'action' => $action,
        'userid' => $this->session->userdata('user.user_id'),
        'data' => $data,
    );
    
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

    echo json_encode($result);
  }

  public function deleted(){

    $data = $this->input->post();
    $crmid = $data['crmid'];

    $json_url = $this->url_service."Defect/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Defect",
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
