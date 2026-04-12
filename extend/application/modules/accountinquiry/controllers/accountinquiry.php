<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Accountinquiry extends MY_Controller
{
	
  public function __construct()
  {
    parent::__construct();
    
  }

  public function index()
  {
    
    $this->template->set_layout('accountinquiry');
  	$this->template->build('index');
  }

  
}