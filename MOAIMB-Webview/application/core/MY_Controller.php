<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        define("APIREFRESH", isset($_GET['api_refresh']));

        define('USERID', $this->session->userdata('userid'));
        define('USERNAME', $this->session->userdata('username'));
    }

}