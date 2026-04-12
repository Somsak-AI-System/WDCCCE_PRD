<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Login extends MY_Controller
{
	
  public function __construct()
  {
    parent::__construct();
    
  }

  public function index()
  {
    
    $this->template->set_layout('homelogin');
  	$this->template->build('login');
  }

  
}