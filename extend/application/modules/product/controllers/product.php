<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Product extends MY_Controller
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
	  $this->load->model('product_model');

  }

  public function index()
  {
    $this->template->title("Unit | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Unit', $this->screen);
    $this->template->modulename('product', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $data = array();
    //$data['event'] = $this->get_event($productid);
    $this->template->set_layout('theme');
    $this->template->build('index', $data, FALSE, TRUE);

  }
  public function getproduct(){

    $post_data = $this->input->post();
    $branchid = @$post_data['branchid'];
    $buildingid = @$post_data['buildingid'];
    $productname = @$post_data['productname'];

    $json_url = $this->url_service."products/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Products",
        "crmid" => "",
        "branchid" => $branchid,
        "buildingid" => $buildingid,
        "productname" => $productname,
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
      "product_no"=>'',
      'productname' => @$post_data['productname'],
      'buildingid' => @$post_data['buildingid'],
      'branchid' => @$post_data['branchid'],
      'floor_no' => @$post_data['floor_no'],
      'house_no' => @$post_data['house_no'],
      'unit_size' => @$post_data['unit_size'],
      'roomplanid' => @$post_data['roomplanid'],
      'customer_name' => @$post_data['customer_name'],
      'phone' => @$post_data['phone'],
      'phone_other' => @$post_data['phone_other'],
      'email' => @$post_data['email'],
      'productstatus' => 1,
      'modifiedby' => $this->session->userdata('user.user_id'),
      'smownerid' => $this->session->userdata('user.user_id'),
    );

    if($action == 'add'){
      $data['customer_status'] = 'None';
      $data['contractor_status'] = 'None';
      $data['smcreatorid'] = $this->session->userdata('user.user_id');
    }

    $json_url = $this->url_service."Products/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Products",
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

    $json_url = $this->url_service."Products/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Products",
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
