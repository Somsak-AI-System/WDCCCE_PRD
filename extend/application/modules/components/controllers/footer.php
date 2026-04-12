<?php

if ( !defined('BASEPATH') )
  exit('No direct script access allowed');

class Footer extends MX_Controller
{

  public function __construct()
  {
    parent::__construct();
    $lang = $this->config->item('lang');
    $this->lang->load('ai',$lang);
  }

  public function index()
  {
    $this->load->view('components/footer');
  }
}