<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Settings extends MY_Controller
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
    $this->lang->load('ai',$lang);
    $this->load->config('api');
    $this->url_service= $this->config->item('service');
	  $this->load->model('settings_model');

  }

  public function index()
  {
    $this->template->title("Settings" ,$this->title);  // 11 words or 70 characters
    $this->template->screen('Settings', $this->screen);
    $this->template->modulename('settings', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('blank');
    $data = array();
    $this->template->build('index', $data, FALSE, TRUE);

  }

  public function broadcastview()
  {
    $this->template->title("Settings" ,$this->title);  // 11 words or 70 characters
    $this->template->screen('Settings', $this->screen);
    $this->template->modulename('settings', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('blank');
    $data = array();
    $this->template->build('broadcastview', $data, FALSE, TRUE);

  }

  public function broadcastmanage()
  {
    $this->template->title("Settings" ,$this->title);  // 11 words or 70 characters
    $this->template->screen('Settings', $this->screen);
    $this->template->modulename('settings', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('blank');
    $data = array();
    $this->template->build('broadcastmanage', $data, FALSE, TRUE);

  }
  



}
