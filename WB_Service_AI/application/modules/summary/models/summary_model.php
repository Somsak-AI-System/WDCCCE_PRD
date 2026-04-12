<?php
class summary_model extends CI_Model
{
	var $ci;

	function __construct()
	{
		parent::__construct();
		$this->ci = &get_instance();
		$this->load->library("common");
		$this->_limit = "10";
	}

	function get_list($params = "")
	{
		$module = @$params["module"];
		$action = @$params["action"];
		$userid = @$params["userid"];
		$month = @$params["month"];
		
		if (empty($userid)) {
			$a_return["status"] = false;
			$a_return["error"] = "Didn't find this user.";
			$a_return["result"] = "";
			return $a_return;
		}
		try {

			$profile = array();
			$summary = array();

			$sql = "select concat(first_name_th,' ',last_name_th) as name_thai , concat(first_name,' ',last_name) as name_eng 
		from aicrm_users 
		where id = '" . $userid . "' and deleted = 0;";
			$query = $this->ci->db->query($sql);

			if (!$query) {
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			} else {
				$a_result = $query->row_array();
				$profile = $a_result;
			}

			$summary = $this->get_report($userid, $month, $module);
			$summary_of_type = $this->get_report_of_type($userid, $month, $module);

			$a_return["status"] = true;
			$a_return["error"] =  "";
			$a_return["profile"] = $profile;
			$a_return["summary"] = !empty($summary) ? $summary : null;
			$a_return["summary_of_type"] = !empty($summary_of_type) ? $summary_of_type : null;
			// alert($a_return);exit;

		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
		return $a_return;
	}

	function get_report($userid = "", $date = "", $module = "")
	{
		if ($date != '') {
			$date = explode("/", $date);
			$month = $date[0];
			$year = $date[1];
		} else {
			$month = date('m');
			$year = date('Y');
		}

		$sql_group = " select aicrm_groups.groupid,aicrm_groups.groupname,aicrm_users.id
		from aicrm_users
		inner join aicrm_users2group on aicrm_users2group.userid = aicrm_users.id
		inner join aicrm_groups on aicrm_groups.groupid = aicrm_users2group.groupid
		where aicrm_users.id = '" . $userid . "'";

		$query_group = $this->ci->db->query($sql_group);
		$a_group  = $query_group->row_array();

		if (empty($a_group)) {
			$where_user = " '" . $userid . "' ";
		} else {

			$where_user = " '" . $userid . "' , '" . $a_group['groupid'] . "' ";
		}

		$a_data = $this->get_summary_by_module($where_user, $month, $year, $module);

		$a_result = [
			$module => $a_data,
		];

		return $a_result;
	}

	function get_report_of_type($userid = "", $date = "", $module = "")
	{
		if ($date != '') {
			$date = explode("/", $date);
			$month = $date[0];
			$year = $date[1];
		} else {
			$month = date('m');
			$year = date('Y');
		}

		$sql_group = " select aicrm_groups.groupid,aicrm_groups.groupname,aicrm_users.id
		from aicrm_users
		inner join aicrm_users2group on aicrm_users2group.userid = aicrm_users.id
		inner join aicrm_groups on aicrm_groups.groupid = aicrm_users2group.groupid
		where aicrm_users.id = '" . $userid . "'";

		$query_group = $this->ci->db->query($sql_group);
		$a_group  = $query_group->row_array();

		if (empty($a_group)) {
			$where_user = " '" . $userid . "' ";
		} else {

			$where_user = " '" . $userid . "' , '" . $a_group['groupid'] . "' ";
		}

		$service_type = $this->get_service_type($where_user, $month, $year, $module);
		$a_data = [];
		if (!empty($service_type)) {

			foreach ($service_type as $key => $val) {
				$a_data[$val['service_type']] = $this->get_summary_of_type($where_user, $month, $year, $module, $val['service_type']);
			}
		}
		return $a_data;
	}

	function get_service_type($where_user = "", $month = "", $year = "", $module = "", $tabid = "")
	{

		switch ($module) {
			case 'Job':
				$query_service_type = "
				SELECT
					aicrm_jobs.jobtype AS service_type
				FROM
					aicrm_jobs
					INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
				WHERE
					aicrm_crmentity.deleted = 0 
					AND aicrm_crmentity.smownerid IN (" . $where_user . ")
					AND MONTH ( aicrm_crmentity.createdtime ) = '" . $month . "'
					AND YEAR ( aicrm_crmentity.createdtime ) = '" . $year . "'
					GROUP BY aicrm_jobs.jobtype;
				";//echo $query_service_type; exit;
				break;
		}

		$query =  $this->ci->db->query($query_service_type);

		if (!$query) {
			$a_result = array();
		} else {
			$a_result  = $query->result_array();
		}

		return $a_result;
	}

	function get_summary_by_module($where_user = "", $month = "", $year = "", $module = "")
	{
		switch ($module) {
			case 'Job':
				$query_module = "
				SELECT 
				ifnull( sum( tmp.closedjob ), 0 ) AS closed,
				count( tmp.jobid ) AS max,
				concat(round(( ifnull( sum( tmp.closedjob ), 0 )/count( tmp.jobid )  * 100 ),0),'%') AS percentage
				FROM
					(
					SELECT
						aicrm_jobs.jobid,
					CASE WHEN aicrm_jobs.job_status = 'ปิดงาน' THEN
							1 ELSE 0 
						END AS closedjob 
					FROM
						aicrm_jobs
						INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
						INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid 
					WHERE
						aicrm_crmentity.deleted = 0 
						AND aicrm_crmentity.smownerid IN (" . $where_user . ")
						AND MONTH ( aicrm_crmentity.createdtime ) = '" . $month . "'
					AND YEAR ( aicrm_crmentity.createdtime ) = '" . $year . "'
					) AS tmp;
				";
				break;
		}

		$query = $this->ci->db->query($query_module);

		if (!$query) {
			$a_result = array();
		} else {
			$a_result  = $query->row_array();
		}

		return $a_result;
	}

	function get_summary_of_type($where_user = "", $month = "", $year = "", $module = "", $service_type = "")
	{
		switch ($module) {
			case 'Job':
				$query_module = "
				SELECT 
				ifnull( sum( tmp.closedjob ), 0 ) AS closed,
				count( tmp.jobid ) AS max
				FROM
					(
					SELECT
						aicrm_jobs.jobid,
					CASE WHEN aicrm_jobs.job_status = 'ปิดงาน' THEN
							1 ELSE 0 
						END AS closedjob 
					FROM
						aicrm_jobs
						INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
						INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid 
					WHERE
						aicrm_crmentity.deleted = 0 
						AND aicrm_crmentity.smownerid IN (" . $where_user . ")
						AND MONTH ( aicrm_crmentity.createdtime ) = '" . $month . "'
						AND YEAR ( aicrm_crmentity.createdtime ) = '" . $year . "'
						AND aicrm_jobs.jobtype = '" . $service_type . "'
					) AS tmp;
				";
				break;
		}

		$query = $this->ci->db->query($query_module);

		if (!$query) {
			$a_result = array();
		} else {
			$a_result  = $query->row_array();
		}

		return $a_result;
	}

	function get_summary($userid = "", $date = "")
	{

		$date = explode("/", $date);

		$month = $date[0];
		$year = $date[1];

		$a_jobs = $this->get_summary_by_module($userid, $month, $year, "Job");

		$a_result = [
			'Job' => $a_jobs
		];

		return $a_result;
	}


	function get_allsummary($userid = "", $date = "", $module)
	{

		$date = explode("/", $date);

		$month = $date[0];
		$year = $date[1];

		$sql_group = " select aicrm_groups.groupid,aicrm_groups.groupname,aicrm_users.id
		from aicrm_users
		inner join aicrm_users2group on aicrm_users2group.userid = aicrm_users.id
		inner join aicrm_groups on aicrm_groups.groupid = aicrm_users2group.groupid
		where aicrm_users.id = '" . $userid . "'";

		$query_group = $this->ci->db->query($sql_group);
		$a_group  = $query_group->row_array();

		if (empty($a_group)) {
			$where_user = " '" . $userid . "' ";
		} else {

			$where_user = " '" . $userid . "' , '" . $a_group['groupid'] . "' ";
		}

		$sql_tab = "SELECT tabid,name FROM aicrm_tab WHERE name = '" . $module . "'" ;
	
		$query_tab = $this->db->query($sql_tab);
		$a_tab  = $query_tab->result_array();
		$tabid = $a_tab[0]["tabid"];
		
		$service_type = $this->get_service_type($where_user, $month, $year, $module);
		// alert($service_type);exit;

		$a_data = [];
		if (!empty($service_type)) {

			foreach ($service_type as $key => $val) {
				$a_data[$val['service_type']] = $this->get_allsummary_by_servicetypeOfModule($where_user, $month, $year, $module, $tabid, $val['service_type']);
			}
		}

		// alert($a_data);
		// exit;
		$a_result = $a_data;

		return $a_result;
	}

	function get_allsummary_by_servicetypeOfModule($where_user = "", $month = "", $year = "", $module = "", $tabid = "", $service_type = "")
	{

		switch ($module) {
			case 'Job':
				$query_module = "
				SELECT
					aicrm_jobs.jobid AS id,
					aicrm_jobs.job_no AS NO,
					aicrm_jobs.job_name AS NAME,
					aicrm_jobs.jobid,
					aicrm_jobs.job_no,
					aicrm_jobs.job_name,
					ifnull( aicrm_account.accountname, '' ) AS title,
					aicrm_jobs.job_no AS description,
					aicrm_jobs.job_status AS STATUS,
				CASE
						
						WHEN aicrm_picklistcolor.color IS NULL THEN
						'#33DAFF' ELSE aicrm_picklistcolor.color 
					END AS color,
					aicrm_crmentity.createdtime AS dateAt 
				FROM
					aicrm_jobs
					INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
					LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_jobs.accountid
					LEFT JOIN ( 
						SELECT * FROM aicrm_picklistcolor WHERE aicrm_picklistcolor.tabid = '" . $tabid . "' 
						) aicrm_picklistcolor ON aicrm_picklistcolor.picklist_value = aicrm_jobs.job_status 
				WHERE
					aicrm_crmentity.deleted = 0 
					AND aicrm_crmentity.smownerid IN (" . $where_user . ")
					AND MONTH ( aicrm_crmentity.createdtime ) = '" . $month . "'
					AND YEAR ( aicrm_crmentity.createdtime ) = '" . $year . "'
					AND aicrm_jobs.jobtype = '" . $service_type . "'
					;
				";
				break;
		}
		// echo $query_module;
		$query = $this->ci->db->query($query_module);

		if (!$query) {
			$a_result = array();
		} else {
			$a_result  = $query->result_array();
		}

		return $a_result;
	}
}
