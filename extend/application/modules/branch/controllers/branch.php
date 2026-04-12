<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Branch extends MY_Controller
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
	  $this->load->model('branch_model');
	
  }
  
  public function index()
  {
    $this->template->title("Projects | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Projects', $this->screen);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->modulename('branch', $this->modulename);
    //$this->template->set_layout('theme');
    $data = array();
    //$data['event'] = $this->get_event($productid);
    $this->template->set_layout('theme');
    $this->template->build('index', $data, FALSE, TRUE);
    
  }
  public function getbranch(){
    
    $post_data = $this->input->post();
    $branch_name = @$post_data['branch_name'];
    $pj_status = @$post_data['pj_status'];
    //$crmid = @$post_data['branch_id'];

    $json_url = $this->url_service."branch/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Branch", 
        "crmid" => "",
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

  public function create_branch(){

    $post_data = $this->input->post();
    $crmid = $post_data['crmid'];
    $action = $post_data['action'];
    $branch_name = $post_data['branch_name'];
    $branch_type = $post_data['branch_type'];
    $data = array(
      "branch_no"=>'',
      'branch_name' => $branch_name,
      'pj_name_en' => $branch_name,
      'pj_project_type' => $branch_type,
      'modifiedby' => $this->session->userdata('user.user_id'),
      'smownerid' => $this->session->userdata('user.user_id'),
    );

    if($action == 'add'){
      $data['smcreatorid'] = $this->session->userdata('user.user_id');
    }
    
    $json_url = $this->url_service."branch/insert_branch";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Branch",
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


  public function get_projecttype(){
    $post_data = $this->input->post();
    $json_url = $this->url_service."ai_function/get_data/get_columnname";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Branch",
        "columnname" => "pj_project_type",
        "limit" => "0",
        "offset" => "0"
    );
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

    //if($result['Type'] == 'S'){
        $data['Type'] = 'S';
        $data['data'] = $result['data'];
        $data['message'] = @$result['Message'];
    /*}else{
       $data['Type'] = @$result['Type'];
       $data['data'] = array();
       $data['message'] = @$result['Message'];
    }*/
    echo json_encode($data);
  }

}