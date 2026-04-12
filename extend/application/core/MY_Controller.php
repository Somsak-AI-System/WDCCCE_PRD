<?php
session_start();
(defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{
  public function __construct()
  {
    parent::__construct();
    //$this->load->view('index');
    //alert($_SESSION); exit;
	  $loggedin = $_SESSION['user_id'];
    if($loggedin !=""){
      define('USERID', $_SESSION['user_id']);
      define('USERNAME', $_SESSION['login_user_name']);
      define('FIRSTNAME', $_SESSION['fullname']);
      define('LASTLOGIN', $_SESSION['lastlogin']);
      define('IMAGEUSER', $_SESSION['imageuser']);

      global $report_viewer_url;
    
      define('REPORT_VIEWER_URL', $report_viewer_url);
  	}else{
  		redirect('http://'.$_SERVER['HTTP_HOST']);
      exit();
  	}
    

  }

}