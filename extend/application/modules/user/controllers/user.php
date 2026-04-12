<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class User extends MY_Controller
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
    $this->lang->load('admin/user',$lang);
    $this->load->config('api');
    $this->url_service= $this->config->item('service');
	  $this->load->model('user_model');
	
  }
  public function index()
  {
  	$loggedin = $this->session->userdata('login');
	  $theme = $this->session->userdata('theme');
  	// if ($loggedin!="yes"){
  	// 	redirect(site_url('user/login'));
  	// 	exit();
  	// }
  	// else
  	// {	
	  //  if(IS_ADMIN == 'on'){
	  //   redirect(site_url('dashboard'));
	  //  }else{
	  //   redirect(site_url('home'));
	  //  }	
  	// }
  }

  public function login()
  {
  	$data ='';
  	$this->template->title(lang("user.login"),$this->title); // 11 words or 70 characters
  	$this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
  	$this->template->set_metadata('keywords', $this->keyword);
  	$this->template->set_layout('login');
    /*alert ($_SERVER); echo "<br>";
    echo gethostbyaddr($_SERVER['REMOTE_ADDR']);*/
  	$this->template->build('login', $data, FALSE, TRUE);
  }
 
  public function checklogin()
	{	 
    //echo 'User IP - '.gethostbyaddr($_SERVER['REMOTE_ADDR'])."(".$_SERVER["REMOTE_ADDR"].")"; exit;
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		if($username != "" && $password != ""){
			$json_url = $this->url_service."users/login";
      		//echo $json_url ; exit;
			$fields = array(
					'AI-API-KEY'=>"1234",
					'module' => "User", 
					'user' => $username, 
					'pass' => $password,
          "device" => "1",
          "token" => "",
          "mobile_version" => "",
          "mobile_device" => "",
          "app_version"=> "",
          "ipaddress" => gethostbyaddr($_SERVER['REMOTE_ADDR'])."(".$_SERVER["REMOTE_ADDR"].")",
          "use_device" => "CRM",
			);
      
      /*echo $json_url;
      echo json_encode($fields); exit;*/
			$response = $this->curl->simple_post($json_url,$fields,array(),"json");
			$result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
			//$this->common->set_log("checklogin",$fields,$result);	
      //alert($result); exit;
			if($result['Type'] == "S"){	
				$this->session->set_userdata('login', 'yes');
        $row['user.user_id'] = $result['data']['user_id'];
        $row['user.user_name'] = $result['data']['user_name'];
        $row['user.firstname'] = $result['data']['first_name'];
        $row['user.lastname'] = $result['data']['last_name'];
        $row['user.phone_mobile'] = $result['data']['phone_mobile'];
        $row['user.email1'] = $result['data']['email1'];
        $row['user.is_admin'] = $result['data']['is_admin'];
        $this->session->set_userdata($row); 

        $result['is_admin'] = $result['data']['is_admin'];
          echo json_encode($result);
	//echo $result['Message'];
	} else {
	  echo json_encode($result);
	 //echo $result['Message'];				
	}
		}
  	
	} 
  
  public function logout()
  { 
    //echo 'User IP - '.gethostbyaddr($_SERVER['REMOTE_ADDR'])." (".$_SERVER["REMOTE_ADDR"].")"; exit;
    $json_url = $this->url_service."users/logout";
    $fields = array(
          'AI-API-KEY'=>"1234",
          'module' => "User", 
          'userid' => USERID, 
          "device" => "1",
          "token" => "",
          "mobile_version" => "",
          "mobile_device" => "",
          "app_version"=> "",
          "ipaddress" => gethostbyaddr($_SERVER['REMOTE_ADDR'])."(".$_SERVER["REMOTE_ADDR"].")",
          "use_device" => "CRM",
    );
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      //$this->common->set_log("checklogin",$fields,$result); 
      //alert($result); exit;
      if($result['Type'] == "S"){ 
      	$this->session->unset_userdata('login');
        $this->session->unset_userdata('user.user_id');
        $this->session->unset_userdata('user.user_name');
      	$this->session->unset_userdata('user.firstname');
      	$this->session->unset_userdata('user.lastname');
        $this->session->unset_userdata('user.phone_mobile');
        $this->session->unset_userdata('user.email1');
        $this->session->unset_userdata('user.is_admin');
      	redirect(site_url('user'));
      }else{
        redirect(site_url('dashboard'));
      }
  }

  public function manage_user()
  {
    if(IS_ADMIN != 'on'){
      redirect(site_url('home'));
    }
    $this->template->title("Users | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Users', $this->screen);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->modulename('users', $this->modulename);
    //$this->template->set_layout('theme');
    $data = array();
    //$data['event'] = $this->get_event($productid);
    $this->template->set_layout('theme');
    $this->template->build('index', $data, FALSE, TRUE);
    
  }

  public function getusers(){
    $post_data = $this->input->post();
    $user_name = @$post_data['user_name'];
    $first_name = @$post_data['first_name'];
    $last_name = @$post_data['last_name'];
    $status = @$post_data['status'];

    $json_url = $this->url_service."users/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "User",
        'userid' => '',
        "user_name" => $user_name,
        "first_name" => $first_name,
        "last_name" => $last_name,
        "status" => $status,
        "limit" => "0",
        "offset" => "0",
        
    );
    /*echo $json_url;
    echo json_encode($fields);exit;*/
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    /*alert($result); exit;*/
    
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

  public function deleted(){

    $data = $this->input->post();
    $userid = $data['user_id'];
    $action = $data['action'];
    $json_url = $this->url_service."users/edit_user";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Users",
        "id" => $userid, 
        'action' => $action,
    );

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function chang_password()
  {  
    $data = $this->input->post();
    $userid = $data['userid'];
    $action = $data['action'];
    $user_name = $data['user_name'];
    $password = $data['change_password'];

    $json_url = $this->url_service."users/chang_password";
    $data = array(
      'user_name' => $user_name,
      'password'=> $password
    );
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Users",
        'action' => $action,
        'id' => $userid,
        'data' => $data,
    );

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

    echo json_encode($result);
  } 

  public function edit_profile()
  {  
    $data = $this->input->post();
    $userid = $data['userid'];
    $action = $data['action'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $phone_mobile = $data['phone_mobile'];
    $email1 = $data['email1'];
    $status = @$data['status'];
    $create_task = @$data['create_task'];
    
    //alert($data); exit;
    /*$data = array(
      'user_name' => $user_name,
      'password'=> $password
    );*/
  	//$json_url = "http://localhost:8090/LKHD/WB_Service_AI/users/edit_user";
    
    $json_url = $this->url_service."users/edit_user";
    //echo $json_url; exit;
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Users",
        'action' => $action,
        'id' => $userid,
        'data' => $data,
    );
    
    /*echo $json_url;
    echo json_encode($fields); exit;*/

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    //alert($result); exit;
    if($result['Type'] == 'S'){
      if($userid == $this->session->userdata('user.user_id')){
        $row['user.firstname'] = $data['first_name'];
        $row['user.lastname'] = $data['last_name'];
        $row['user.phone_mobile'] = $data['phone_mobile'];
        $row['user.email1'] = $data['email1'];
        $result['reload'] = true;
        $this->session->set_userdata($row); 
      }
    }

    echo json_encode($result);
  } 

  public function create()
  {  
    $data = $this->input->post();
    $userid = $data['userid'];
    $action = $data['action'];
    $user_name = $data['user_name'];
    $password = $data['password'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $phone_mobile = $data['phone_mobile'];
    $email1 = $data['email1'];
    $status = $data['status'];
    $create_task = $data['create_task'];

    $data = array(
      'user_name' => $user_name,
      'user_password'=> $password,
      'first_name' => $first_name,
      'last_name'=> $last_name,
      'phone_mobile' => $phone_mobile,
      'email1'=> $email1,
      'status'=> $status,
      'create_task'=> $create_task,
    );
    $json_url = $this->url_service."users/insert_user";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Users",
        'action' => $action,
        'id' => $userid,
        'data' => $data,
    );
    
    //echo json_encode($fields); exit;

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    //alert($result); exit;
   
    echo json_encode($result);
  } 



}