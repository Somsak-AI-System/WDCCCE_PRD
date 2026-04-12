<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class quick_view extends MY_Controller
{
  private $description, $title, $keyword, $screen;
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
	  $this->load->helper('form');
  }
 

  public function quick_create_branch($branchid='')
  {
  	$data = array();
  
    $this->template->title('Quick',$this->title); 
  	$this->template->screen('Quick',$this->screen); 
    $this->template->set_metadata('description', "Quick Create"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
  	
    $data['action'] = 'add';
    $data['branchid'] = $branchid;

    if(!empty($branchid) && $branchid != ''){
      $branchid = @$branchid;
      $json_url = $this->url_service."branch/list_content";
      $fields = array(
          'AI-API-KEY'=>"1234",
          'module' => "Branch", 
          "crmid" => $branchid,
          "pj_status" => '',
          "branch_name" => '',
          "limit" => "0",
          "offset" => "0",
          'userid' => $this->session->userdata('user.user_id'),
          "orderby"=>"aicrm_branch.branchid,DESC"
      );
      //echo json_encode($fields);exit;
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      $data['action'] = 'edit';
    }
    $data['data'] = @$result['data'][0];
    $this->template->set_layout('theme-no-header');
  	$this->template->build('quick_view_branch', $data);
  }

  public function save_branch()
  {
    $post_data = $this->input->post();
    $crmid = $post_data['crmid'];
    $action = $post_data['action'];
    $branch_name = $post_data['branch_name'];
    
    $data = array(
      "branch_no"=>'',
      'branch_name' => $branch_name,
      'pj_name_en' => $branch_name,
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



  public function quick_create_building($buildingid='0',$branchid='0')
  {
    $data = array();
    //alert($this->input->get()); exit;
    $this->template->title('Quick',$this->title); 
    $this->template->screen('Quick',$this->screen); 
    $this->template->set_metadata('description', "Quick Create"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    
    $data['action'] = 'add';
    $data['buildingid'] = @$buildingid;
    $data['branchid'] = @$branchid;

    $buildingid = @$buildingid;
    $branchid = @$branchid;

    if(!empty($buildingid) && $buildingid != ''){
      
      $json_url = $this->url_service."building/list_content";
      $fields = array(
          'AI-API-KEY'=>"1234",
          'module' => "Building", 
          "crmid" => @$buildingid,
          "branchid" => $branchid,
          "limit" => "0",
          "offset" => "0",
          'userid' => $this->session->userdata('user.user_id'),
      );
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      $data['action'] = 'edit';
    }
    
    $data['branch'] = $this->get_branch($branchid);
    $data['data'] = @$result['data'][0];

    //alert($data['branchid']); exit;
    $this->template->set_layout('theme-no-header');
    $this->template->build('quick_view_building', $data);
  }


  public function get_branch($branchid='')
  {
    $this->load->library('db_api');
    $this->load->database();

    $sql = "select aicrm_branch.branchid,aicrm_branch.branch_name from aicrm_branch
            inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branch.branchid
            inner join aicrm_branchcf on aicrm_branch.branchid= aicrm_branchcf.branchid
            where aicrm_crmentity.deleted = 0 ";
    $sql .= "order by aicrm_branch.branch_name ";
    $query = $this->db->query($sql);
    $result = $query->result(0);
    
    return $result;
    //alert($a_result); exit;
    /*$data = array(); 
    $crmid = @$branchid;
    $json_url = $this->url_service."branch/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Branch", 
        "crmid" => '',
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
    );
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    return $result['data'];*/
  }

  public function save_building(){

    $post_data = $this->input->post();
    $crmid = $post_data['crmid'];
    $action = $post_data['action'];
    $branchid = $post_data['branchid'];
    $building_name = $post_data['building_name'];
    //$building_name = $post_data['building_name'];
    $data = array(
      "building_no"=>'',
      'building_name' => @$building_name,
      'building_name_en' => @$building_name,
      'branchid' => $branchid,
      'bd_status' => 'Active',
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

  public function quick_create_unit($branchid='',$buildingid='',$floor_no='')
  {
    $data = array();

    $this->template->title('Quick',$this->title); 
    $this->template->screen('Quick',$this->screen); 
    $this->template->set_metadata('description', "Quick Create"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    
    $data['action'] = 'add';
   
    $data['branchid'] = @$branchid;
    $data['buildingid'] = @$buildingid;
    $data['floor_no'] = @$floor_no;

    $buildingid = @$buildingid;
    $branchid = @$branchid;
    
    $this->template->set_layout('theme-no-header');
    $this->template->build('quick_view_unit', $data);
  
  }
  
  public function save_unit(){

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
      'customer_status' => 'None',
      'contractor_status' => 'None',
      'inspector_customer' => 'None',
      'inspector_contractor' => 'None',
      'productstatus' => 1,
      'modifiedby' => $this->session->userdata('user.user_id'),
      'smownerid' => $this->session->userdata('user.user_id'),
    );
    
    if($action == 'add'){
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

    //alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function edit_unit(){

    $post_data = $this->input->post();
    $product_crmid = $post_data['product_crmid'];
    $action = $post_data['action'];
    $data = array(
      'productname' => @$post_data['productname'],
      'floor_no' => @$post_data['floor_no'],
      'house_no' => @$post_data['house_no'],
      'unit_size' => @$post_data['unit_size'],
      'customer_name' => @$post_data['customer_name'],
      'phone' => @$post_data['phone'],
      'phone_other' => @$post_data['phone_other'],
      'email' => @$post_data['email'],
      'modifiedby' => $this->session->userdata('user.user_id'),
      'smownerid' => $this->session->userdata('user.user_id'),
    );
    
    if($action == 'add'){
      $data['smcreatorid'] = $this->session->userdata('user.user_id');
    }
    
    $json_url = $this->url_service."Products/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Products",
        "crmid" => $product_crmid, 
        'action' => $action,
        'userid' => $this->session->userdata('user.user_id'),
        'data' => $data,
    );

    //alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function detail_product($productid ='' )
  {
    $data = array();
    $this->template->title('Quick',$this->title); 
    $this->template->screen('Quick',$this->screen); 
    $this->template->set_metadata('description', "Quick Create"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    $this->template->set_layout('theme-no-header');


    $data['product'] = $this->get_productdetail($productid);
    $data['customer'] = $this->get_product_inspect_cus($productid);
    $data['contractor'] = $this->get_product_inspect_con($productid);
    //alert($data['customer']); exit;
    $this->template->build('product_detail', $data);
  }

  public function get_productdetail($productid='')
  {
    $this->load->library('db_api');
    $this->load->database();

    $sql = "select aicrm_products.* , aicrm_building.building_name ,aicrm_branch.branch_name from aicrm_products
            inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
            inner join aicrm_productcf on aicrm_productcf.productid= aicrm_products.productid
            inner join aicrm_branch on aicrm_branch.branchid= aicrm_products.branchid
            Left join aicrm_building on aicrm_building.buildingid= aicrm_products.buildingid
            where aicrm_crmentity.deleted = 0 and aicrm_products.productid = '".$productid."' ";

    $query = $this->db->query($sql);
    $result = $query->result(0);
    
    return $result;
  }

  public function get_product_inspect_cus($productid='')
  {
    $this->load->library('db_api');
    $this->load->database();

    $sql = "select aicrm_inspection.* from aicrm_inspection 
        inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid 
        where aicrm_crmentity.deleted = 0 and aicrm_inspection.productid = '".$productid."' and aicrm_inspection.inspection_type = 'Customer'
        order by aicrm_inspection.inspection_timeno asc,aicrm_inspection.inspectionid desc";
    //echo $sql; exit;      
    $query = $this->db->query($sql);
    $result = $query->result(0);
    
    return $result;
  }

  public function get_product_inspect_con($productid='')
  {
    $this->load->library('db_api');
    $this->load->database();

    $sql = "select aicrm_inspection.* from aicrm_inspection 
        inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid 
        where aicrm_crmentity.deleted = 0 and aicrm_inspection.productid = '".$productid."' and aicrm_inspection.inspection_type = 'Contractor'
        order by aicrm_inspection.inspection_timeno asc,aicrm_inspection.inspectionid desc";
            
    $query = $this->db->query($sql);
    $result = $query->result(0);
    
    return $result;
  }


  public function edit_product($productid ='' )
  {
    $data = array();
    $this->template->title('Quick',$this->title); 
    $this->template->screen('Quick',$this->screen); 
    $this->template->set_metadata('description', "Quick Create"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    $this->template->set_layout('theme-no-header');
    
    $data['product'] = $this->get_productdetail($productid);

    $this->template->build('product_edit', $data);
  }

/*  function multiRequest($data, $options = array()) {
    // array of curl handles
    $curly = array();
    // data to be returned
    $result = array();
   
    // multi handle
    $mh = curl_multi_init();
   
    // loop through $data and create curl handles
    // then add them to the multi-handle
      $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
      curl_setopt($curly[$id], CURLOPT_URL,            $url);
      curl_setopt($curly[$id], CURLOPT_HEADER,         0);
      curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
   
      // post?
      if (is_array($d)) {
        if (!empty($d['post'])) {
          curl_setopt($curly[$id], CURLOPT_POST,       1);
          curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
        }
      }
   
      // extra options?
      if (!empty($options)) {
        curl_setopt_array($curly[$id], $options);
      }
      curl_multi_add_handle($mh, $curly[$id]);
    //}
    // execute the handles
    $running = null;
    do {
      curl_multi_exec($mh, $running);
    } while($running > 0);
    // get content and remove handles
    foreach($curly as $id => $c) {
      $result[$id] = curl_multi_getcontent($c);
      curl_multi_remove_handle($mh, $c);
    }
    // all done
    curl_multi_close($mh);
    return $result;
  }*/

  
}