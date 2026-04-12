<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class popup_search extends MY_Controller
{
  private $description, $title, $keyword, $screen;
  public function __construct()
  {
    parent::__construct();
    $meta = $this->config->item('meta');
    $lang = $this->config->item('lang');
    $this->title = $meta["default"]["title"];
//    $this->screen = $meta["default"]["screen"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->lang->load('ai',$lang);
	$this->load->helper('form');
  }
  
    public function seller_search()
  {    
	/*** Get data ***/
	$this->my_server = $this->load->database();		
	$query=$this->my_server->query(" select id,cat,seller_id,seller_name,seller_company from seller where deleted = 0  order by id asc  ");
	$seller_list = $query->result_array();
	
	echo json_encode($seller_list);
  }
  
   public function customer_search()
  {    
	/*** Get data ***/
	$this->my_server = $this->load->database();		
	$query=$this->my_server->query(" select id,cus_code, cus_name,cus_mobile from customer where deleted = 0  order by id asc  ");
	$seller_list = $query->result_array();
	
	echo json_encode($seller_list);
  }
  
  
  
}