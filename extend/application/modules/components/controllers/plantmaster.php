<?
class plantmaster extends MX_Controller {
	 private $description, $title, $keyword, $screen;
 public function __construct()
  {
 
   parent::__construct();
    $meta = $this->config->item('meta');
    $this->_lang = $this->config->item('lang');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->lang->load('ai',$this->_lang);    
    $this->load->library('logging');
	$this->load->helper('form');
	//$this->_section = USERSECTION;
  }
}

?>