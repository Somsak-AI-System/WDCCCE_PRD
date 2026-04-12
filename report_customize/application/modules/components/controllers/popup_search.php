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
 
  
   public function customer_search($condition='')
  {
	$data = array();
    
    $this->template->title('Customer List',$this->title); 
	$this->template->screen('Customer List',$this->screen); 
    $this->template->set_metadata('description', "Customer List for Popup Search"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    
	/*** Get data ***/
	$this->my_server = $this->load->database('SCCC', True);		
	$query=$this->my_server->query(" select * from tbm_sale_cust where cuscd is not null  ".$condition);
	$customer_list = $query->result_array();

 	$data['customer_list']=	$customer_list;
	
		
    $this->template->set_layout('theme-no-header');
  	$this->template->build('customer_search', $data);
	  
  }
  
   public function waste_search($condition='')
  {
	$data = array();
    
    $this->template->title('Waste List',$this->title); 
	$this->template->screen('Waste List',$this->screen); 
    $this->template->set_metadata('description', "Waste List for Popup Search"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    
	/*** Get data ***/
	$this->my_server = $this->load->database('SCCC', True);		
	$query=$this->my_server->query(" select * from tbm_engi_waste where wastecode is not null  ".$condition);
	$waste_list = $query->result_array();

 	$data['waste_list']=	$waste_list;
	
		
    $this->template->set_layout('theme-no-header');
  	$this->template->build('waste_search', $data);
	  
  }
 
   public function item_search($condition='')
  {
	$data = array();
    
    $this->template->title('Item List',$this->title); 
	$this->template->screen('Item List',$this->screen); 
    $this->template->set_metadata('description', "Item List for Popup Search"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    
	/*** Get data ***/
	$this->my_server = $this->load->database('SCCC', True);		
	$query=$this->my_server->query(" select * from tbm_engi_item where itemcd is not null  ".$condition);
	$item_list = $query->result_array();

 	$data['item_list']=	$item_list;
	
		
    $this->template->set_layout('theme-no-header');
  	$this->template->build('item_search', $data);
	  
  }
   public function car_search($condition='')
  {
	$data = array();
    
    $this->template->title('Car List',$this->title); 
	$this->template->screen('Car List',$this->screen); 
    $this->template->set_metadata('description', "Car List for Popup Search"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    
	/*** Get data ***/
	$this->my_server = $this->load->database('SCCC', True);		
	$query=$this->my_server->query("select distinct a.provcd,a.licenseno,b.carctgnm,c.cartypenm
													 from tbm_lgst_car a 
													inner join tbm_lgst_carcategory b on a.carctgcd=b.carctgcd
													inner join tbm_lgst_cartype c on  a.cartypecd=c.cartypecd
													 where a.carcd is not null  ".$condition);
	$car_list = $query->result_array();

 	$data['car_list']=	$car_list;
	
		
    $this->template->set_layout('theme-no-header');
  	$this->template->build('car_search', $data);
	  
  }


 public function membername_search()
 {
    $data = array();
    $this->template->title('Membername List',$this->title); 
    $this->template->screen('Membername List',$this->screen); 
    $this->template->set_metadata('description', "Membername List for Popup Search"); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
    $this->template->set_metadata('og:description', $this->description);
    

    $this->template->set_layout('theme-no-header');
    $this->template->build('membername_search', $data);
 }
  
  
  
  
  
}