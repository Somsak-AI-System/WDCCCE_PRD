<?php
session_start();
(defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{

  public function __construct()
  {
    parent::__construct();

  /*  if (   $this->uri->segment(2) == "login"
    	|| $this->uri->segment(2) == "checklogin"
    	|| ($this->uri->segment(1)=='user' && $this->uri->segment(2) =='')
    )
    {

    }
    else
    {
    	$loggedin = $this->session->userdata('login');
    	if ($loggedin!="yes"){
    		redirect(base_url('user'));
    		exit();
    	}else{
    		define('USERID', $this->session->userdata('user.id'));
    		define('USEREMAIL', $this->session->userdata('user.email'));
    		define('USERNAME', $this->session->userdata('user.username'));
    		define('USERFNAME', $this->session->userdata('user.name'));
			define('USERROLE', $this->session->userdata('user.rolename'));
			define('USERSECTION', $this->session->userdata('user.section'));
    	}
    }*/
    $uesrid = isset($_SESSION["authenticated_user_id"]) && $_SESSION["authenticated_user_id"]!=""? $_SESSION["authenticated_user_id"] : 1;
    define('USERID', $uesrid);
    define('USEREMAIL', $this->session->userdata('user.email'));
    define('USERNAME', $this->session->userdata('user.username'));
    define('USERFNAME', $this->session->userdata('user.name'));
    define('USERROLE', $this->session->userdata('user.rolename'));
    define('USERSECTION', $this->session->userdata('user.section'));
    global $report_viewer_url;
    
    define('REPORT_VIEWER_URL', $report_viewer_url);
  }

}