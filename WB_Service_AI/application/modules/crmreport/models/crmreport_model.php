<?php
class crmreport_model extends CI_Model
{
	var $ci;

	/**
	 */
	function __construct()
	{
		parent::__construct();
		$this->ci = &get_instance();
		$this->load->library("common");
		$this->_limit = "10";
	}
}
