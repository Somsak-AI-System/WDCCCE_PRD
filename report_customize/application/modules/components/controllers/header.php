<?php

if ( !defined('BASEPATH') )
  exit('No direct script access allowed');

class Header extends MX_Controller
{
  private $_fb_user = null;
  private $_fb_profile = null;

  public function __construct()
  {
    parent::__construct();
    $lang = $this->config->item('lang');
    $this->lang->load('ai',$lang);
  }

  public function index()
  {
    $page = $this->_active_page();
    $sub = $this->_active_function();
    $a_data = array();
    
    $a_menu["home"] = ($page=="" || $page=="index" || $page=="home" || $page=="main")?"active":"";
    $a_menu["master"] = ($page=="master") ? 'active':"";
    $a_menu["planning"] = ($page=="planning") ? "active":"";
    $a_menu["production"] = ($page=="production") ? "active":"";
	$a_menu["queuing"] = ($page=="queuing") ? "active":"";
    $a_data["a_menu"] = $a_menu;
    $this->load->view('components/header', $a_data);
  }

 
  private function _active_page() {
    return $this->uri->segment(1);
  }
  private function _active_function() {
  	return $this->uri->segment(2);
  }
  
  public function toolbar(){
  	$page = $this->_active_page();
  	$sub = $this->_active_function();
  	if ($page== "user")
  		$module = "user";
  	else if ($page=="production" && $sub=="po" ) {
  		$module = "production/po_";
  	}
  	else if ($page=="production" && $sub!="" ) {
  		$module = "production/".$sub."/".$sub."_";
 	 }
	 else if ($page== "queuing")
  		$module = "queuing";
  	$a_data["url"] = site_url($module);
  	$a_data["module"] = $module;
  	$this->load->view('components/toolbar',$a_data);
  	
  }
  
  public function toolbar_mnt(){
  	$page = $this->_active_page();
  	$sub = $this->_active_function();
  	if ($page== "user")
  		$module = "user";
  	else if ($page=="production" && $sub=="po" ) {
  		$module = "production/po_";
  	}
  	else if ($page=="production" && $sub!="" ) {
  		$module = "production/".$sub."/".$sub."_";
  	}
  	$a_data["url"] = site_url($module);
  	$a_data["module"] = $module;
  	$this->load->view('components/toolbar_mnt',$a_data);
  	 
  }
  public function left_bar()
  {
	 $a_data = array();	 
	$this->load->driver('cache');
	$cache_id = 'ai-' .php_uname('n').'-' . $this->session->userdata('user.username');
    $cache_menu = $this->cache->file->get($cache_id);	

	$a_data['left_menu']= $cache_menu;
  	$this->load->view('components/left_bar',$a_data);
  }
 

}