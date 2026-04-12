<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        //$this->mylib->checkLogin();
        $this->template->set_layout('template-master');
        $this->module = 'Home';
        $this->curl->_filename = $this->module;
        $this->lang->load('ai', 'english');
    }

    public function index(){
        $data = array();
        $this->template->build('home', $data);
    }
}