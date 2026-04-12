<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Admin_Controller extends MX_Controller {

  public function __construct()
  {
    parent::__construct();
	if (
		$this->uri->segment(2) == "login"
		|| $this->uri->segment(2) == "checklogin"
		|| ($this->uri->segment(1)=='admin' && $this->uri->segment(2) =='')
	)
	{

	}
	else
	{
	$loggedin = $this->session->userdata('admin_login');
     if ($loggedin!="yes"){
          redirect(base_url('admin/login'));
          exit();
      }
	}
  }

}
