<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{

  public function __construct()
  {
    parent::__construct();
	
    if (   $this->uri->segment(2) == "login"		
    	|| $this->uri->segment(2) == "checklogin" 
    	|| ($this->uri->segment(1)=='user' && $this->uri->segment(2) =='')
    )
    {
    
    }
    else
    {
    	
    	
    }
   

  }

}