<?php
class job_model extends CI_Model
{
	var $ci;


	function __construct()
	{
		parent::__construct();
		$this->ci = &get_instance();
		$this->load->library("common");
		$this->load->library('crmentity');

		$this->_limit = "10";
	}

	function get_notification($a_condition = array())
	{
		try {
			$a_condition["aicrm_crmentity.deleted"] = "0";
			if (!empty($a_condition)) {
				$this->db->where($a_condition);
			}

			$this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid', "inner");
			$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid', "inner");
			$this->db->join('aicrm_crmentity_notification', 'aicrm_crmentity_notification.crmid = aicrm_jobs.jobid', "inner");
			$query = $this->db->get('aicrm_jobs');

			if (!$query) {
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			} else {
				$a_result  = $query->result_array();
				$a_data["data"] = $a_result;
				if (!empty($a_result)) {
					$a_return["status"] = true;
					$a_return["error"] =  "";
					$a_return["result"] = $a_data;
				} else {
					$a_return["status"] = false;
					$a_return["error"] =  "No Data";
					$a_return["result"] = "";
				}
			}
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
		return $a_return;
	}

	function get_list($a_condition = array(), $a_order = array(), $a_limit = array(), $optimize = array(), $a_params = array())
	{

		$module = $a_params['module'];
		$userid = $a_params['userid'];
		$crmid = $a_params['crmid'];


		try {

			$a_condition["aicrm_crmentity.deleted"] = "0";

			if ($optimize == '1') {
				$this->db->select("aicrm_jobs.jobid,aicrm_jobs.job_no,aicrm_jobs.job_name,aicrm_users.user_name as user_assign");
			} else {
				if ($crmid == "") {
					$this->db->select("aicrm_jobs.jobid, aicrm_jobs.job_no,aicrm_jobs.job_name");
				} else {
					$this->db->select("aicrm_jobs.*, aicrm_jobscf.* ,aicrm_account.accountid, aicrm_account.accountname, aicrm_serial.serialid, aicrm_serial.serial_name, aicrm_products.productid,  aicrm_products.productname
				,aicrm_users.user_name as user_assign");
				}
			}

			$this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid', 'inner');
			$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid', 'inner');
			$this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id', 'inner');
			$this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_jobs.accountid', 'left');
			$this->db->join('aicrm_serial', 'aicrm_serial.serialid = aicrm_jobs.serialid', 'left');
			$this->db->join('aicrm_products', 'aicrm_products.productid = aicrm_jobs.product_id', 'left');

			if (!empty($a_condition)) {
				$this->db->where($a_condition);
			}

			if (!empty($a_order)) {
				for ($i = 0; $i < count($a_order); $i++) {
					$this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
				}
			}
			if (empty($a_limit)) {
				$a_limit["limit"] = $this->_limit;
				$a_limit["offset"] = 0;
				$this->db->limit($a_limit["limit"], $a_limit["offset"]);
			} else if ($a_limit["limit"] == 0) {
			} else {
				$this->db->limit($a_limit["limit"], $a_limit["offset"]);
			}

			$query = $this->db->get('aicrm_jobs');

			if (!$query) {
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			} else {
				$a_result  = $query->result_array();

				foreach ($a_result as $key => $val) {
					foreach ($val as $k => $v) {
						if ($v == null) {
							$v = "";
							$val[$k] = $v;
							$val_change = $val[$k];
						}
						$val[$k] = $v;
					}
					$a_result[$key] = $val;
				}

				if ($crmid == "") {
					$a_result = $this->default_list($a_result);
				} else {
					$a_result = $a_result;
				}

				$a_total = $this->get_total($a_condition, $a_params);
				$a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;

				if (!empty($a_result)) {
					$a_return["status"] = true;
					$a_return["error"] =  "";
					$a_return["total"] = $a_data["total"];
					$a_return["result"] = $a_result;
				} else {
					$a_return["status"] = false;
					$a_return["error"] =  "No Data";
					$a_return["result"] = "";
				}
			}
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
		return $a_return;
	}

	function default_list($a_result)
	{

		foreach ($a_result as $key => $val) {
			$data['id'] = $val['jobid'];
			$data['no'] = $val['job_no'];
			$data['name'] = $val['job_name'];
			$a_result[$key] = $data;
		}
		return $a_result;
	}


	function get_total($a_condition = array(), $a_conditionin = array())
	{
		try {
			if (!empty($a_condition)) {
				$this->db->where($a_condition);
			} else {
				$a_condition["aicrm_crmentity.setype"] = "Job";
				$a_condition["aicrm_crmentity.deleted"] = "0";
			}


			if (!empty($a_conditionin)) {
				$this->db->where_in($a_conditionin);
			}

			$this->db->select("count(DISTINCT aicrm_jobs.jobid) as total");
			$this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid');
			$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid');
			$query = $this->db->get('aicrm_jobs');
			if (!$query) {
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			} else {
				$a_result  = $query->result_array();

				if (!empty($a_result)) {
					$a_return["status"] = true;
					$a_return["error"] =  "";
					$a_return["result"] = $a_result[0];
				} else {
					$a_return["status"] = false;
					$a_return["error"] =  "No Data";
					$a_return["result"] = "";
				}
			}
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
		return $a_return;
	}

	function get_job($a_request = array())
	{

		$a_return["status"] = false;
		$a_return["error"] =  "No Data";
		$a_return["result"] = "";

		$crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$userid = isset($a_request['userid']) ? $a_request['userid'] : "";
		$module = isset($a_request['module']) ? $a_request['module'] : "";


		if (empty($crmid)) {
			return $a_return;
		}

		try {

			$a_condition["aicrm_crmentity.crmid"] = $crmid;
			$a_condition["aicrm_crmentity.setype"] = $module;
			// $a_condition["aicrm_crmentity.smownerid"] = $userid;
			$a_condition["aicrm_crmentity.deleted"] = "0";

			$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid', "inner");
			$this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid', "inner");

			$this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_jobs.accountid', "left");
			$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = aicrm_jobs.contactid', "left");
			$this->db->join('aicrm_products', 'aicrm_products.productid = aicrm_jobs.product_id', "left");
			$this->db->join('aicrm_serial', 'aicrm_serial.serialid = aicrm_jobs.serialid', "left");
			$this->db->join('aicrm_troubletickets', 'aicrm_troubletickets.ticketid = aicrm_jobs.ticketid', "left");

			$this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid', "left");
			$this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid', "left");

			// $this->db->join('aicrm_users as create_by', 'create_by.id = aicrm_crmentity.smcreatorid');
			// $this->db->join('aicrm_users as modified_by', 'modified_by.id = aicrm_crmentity.modifiedby');



			if (!empty($a_condition)) {
				$this->db->where($a_condition);
			}

			$query = $this->db->get('aicrm_jobs');

			// echo $this->db->last_query(); exit;

			if (!$query) {
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			} else {
				$a_result  = $query->result_array();
				// alert($a_result); exit;
				// $a_data["data"] = $a_result;

				if (!empty($a_result)) {
					$a_return["status"] = true;
					$a_return["error"] =  "";
					$a_return["result"] = $a_result;
					// $a_return["result"] = $a_data;
				} else {
					$a_return["status"] = false;
					$a_return["error"] =  "No Data";
					$a_return["result"] = "";
				}
			}
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
		return $a_return;
	}

	function check_products($a_param)
	{
		if ($a_param == null) {
			$a_return["status"] = false;
			$a_return["error"] =  "Fail";
			$a_return["result"] = "";
			return $a_return;
		}

		$crmid = $a_param['crmid'];
		$module = $a_param['module'];
		$related_module = $a_param['related_module'];
		$productid = $a_param['productid'];
		$userid = $a_param['userid'];

		if ($productid != "") {

			$checkproducts_related = "SELECT aicrm_products.* FROM `aicrm_products`
				INNER JOIN aicrm_seproductsrel ON aicrm_products.productid = aicrm_seproductsrel.productid AND aicrm_seproductsrel.setype = 'Accounts'
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
    			INNER JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_seproductsrel.crmid
				WHERE aicrm_seproductsrel.crmid='" . $crmid . "' and aicrm_seproductsrel.productid ='" . $productid . "' ";

			$querycheck_related = $this->db->query($checkproducts_related);
			$datacheck_related = $querycheck_related->result_array();


			if (!empty($datacheck_related)) {

				$a_return["status"] = false;
				$a_return["error"] =  "This Products already exists";
				$a_return["result"] = "";
			} else {

				if ($related_module == "") {
					$related_module = "Accounts";
				}

				$insert_relted = "INSERT INTO aicrm_seproductsrel(crmid,productid,setype) 
					VALUES('" . $crmid . "','" . $productid . "','" . $related_module . "')";

				if ($this->db->query($insert_relted)) {

					$a_return["status"] = true;
					$a_return["error"] =  "Add Products Success";
					$a_return["result"] = "";
				} else {

					$a_return["status"] = false;
					$a_return["error"] =  "Add Products Fail";
					$a_return["result"] = "";
				}
			}
		} else {

			$a_return["status"] = false;
			$a_return["error"] =  "No Data";
			$a_return["result"] = "";
		}

		return $a_return;
	}
}
