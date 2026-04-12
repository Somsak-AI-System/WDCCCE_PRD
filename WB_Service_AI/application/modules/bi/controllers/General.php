<?php
ini_set('memory_limit', -1);
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class General extends REST_Controller
{
    private $module;
    function __construct()
	{
		parent::__construct();
	    $this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->module = 'Users';
	}

    public function crmentity_get()
	{
		$req = $this->input->get();

		$page = !isset($req['page']) ? '':$req['page'];
		$page = $page == '' ? 1:$page;
		$limit = !isset($req['limit']) ? '':$req['limit'];
		$limit = $limit == '' ? 100:$limit;
		$start = ($page - 1) * $limit;

		$sql = $this->db->query('SELECT * FROM aicrm_crmentity LIMIT '.$start.', '.$limit);
		$rs = $sql->result_array();

		$this->response($rs, 200);
	}

	public function audit_trail_get()
	{
		$req = $this->input->get();

		$page = !isset($req['page']) ? '':$req['page'];
		$page = $page == '' ? 1:$page;
		$limit = !isset($req['limit']) ? '':$req['limit'];
		$limit = $limit == '' ? 100:$limit;
		$start = ($page - 1) * $limit;

		$sql = $this->db->query('SELECT * FROM aicrm_audit_trial LIMIT '.$start.', '.$limit);
		$rs = $sql->result_array();

		$this->response($rs, 200);
	}
}