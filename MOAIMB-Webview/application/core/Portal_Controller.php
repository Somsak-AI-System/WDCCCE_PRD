<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Portal_Controller extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        define("APIREFRESH", isset($_GET['api_refresh']));
    }

}
