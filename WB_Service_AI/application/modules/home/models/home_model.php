<?php
class home_model extends CI_Model
{
  var $ci;

  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	$this->load->library("common");
    $this->_limit = "10";
  }




//   function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$a_conditionin=array())
  function get_list($userid="")
  {
  		if(empty($userid)){ 
  			$a_return["status"] = false;
			$a_return["error"] = "Didn't find this user.";
			$a_return["result"] = "";
  			return $a_return; 
  		}

  	try {

  		$profile = array();
  		$event = array();
  		$summary = array();
  		$recent = array();
 
		$sql=" select concat(first_name_th,' ',last_name_th) as name_thai , concat(first_name,' ',last_name) as name_eng 
		from aicrm_users 
		where id = '".$userid."' and deleted = 0;";

		$query = $this->ci->db->query($sql);

  		if(!$query){
			$a_return["status"] = false;
			$a_return["error"] = $this->db->_error_message();
			$a_return["result"] = "";
		}else{
			// $a_result  = $query->result_array() ;
			$a_result = $query->row_array();
			$profile = $a_result;
		}

		$event = $this->get_event($userid);

		$summary = $this->get_report($userid);

		$recent = $this->get_recent($userid);

		$a_return["status"] = true;
		$a_return["error"] =  "";
		$a_return["profile"] = $profile;
		$a_return["event"] = !empty($event) ? $event : null;
		$a_return["summary"] = !empty($summary) ? $summary : null;
		$a_return["recent"] = !empty($recent) ? $recent : null;

		// alert($a_return);exit;
		
		}catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		 }
		return $a_return;
	}


	function get_event($userid=""){

		$sql=" select 
		aicrm_activity.activityid,
		aicrm_activity.activitytype ,
		aicrm_activity.date_start,
		aicrm_activity.due_date,
		aicrm_activity.time_start,
		aicrm_activity.time_end,
		CASE
		WHEN aicrm_account.accountname IS NULL THEN
		aicrm_leaddetails.leadname ELSE aicrm_account.accountname 
		END AS NO,
		CASE
		WHEN aicrm_account.accountname IS NULL THEN
		aicrm_leaddetails.leadname ELSE aicrm_account.accountname 
		END AS accountname,
		CASE
		WHEN aicrm_account.account_no IS NULL THEN
		aicrm_leaddetails.lead_no ELSE aicrm_account.account_no 
		END AS account_no,
		IFNULL(aicrm_account.latlong,'') as map,
		aicrm_activity.location,
		aicrm_activity.phone as phone,
		aicrm_activity.location as checkin_location , 
		aicrm_activity.location_chkout as checkout_location
		FROM aicrm_activity
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
		INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
		LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		WHERE aicrm_crmentity.deleted = 0 and aicrm_crmentity.smownerid != 0  and aicrm_crmentity.smownerid = '".$userid."' and aicrm_activity.date_start = CURDATE()
		ORDER BY CONCAT(aicrm_activity.date_start ,aicrm_activity.time_start ) ASC;";

		$query = $this->ci->db->query($sql);

		if(!$query){
			$a_result = array();
		}else{
			$a_result  = $query->result_array();
		}

		return $a_result;
	}

	function get_report($userid=""){

		$month = date('m');
		$year = date('Y');

		$sql_deal=" select
		ifnull(sum(tmp.closeddeal),0) as closed ,
		count(tmp.dealid) as max
		from (
		 select aicrm_deal.dealid , 
		    case when aicrm_deal.stage = 'ปิดแพ้' || aicrm_deal.stage = 'ปิดชนะ' then 1 
		    else 0 
		    end as closeddeal
		 from aicrm_deal
		 inner join aicrm_dealcf on aicrm_dealcf.dealid = aicrm_deal.dealid
		 inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
		 where aicrm_crmentity.deleted = 0 and aicrm_crmentity.smownerid = '".$userid."'
		 and MONTH(aicrm_crmentity.createdtime) = '".$month."'
		    and YEAR(aicrm_crmentity.createdtime) = '".$year."'
		) as tmp;";

		$query = $this->ci->db->query($sql_deal);

		if(!$query){
			$a_deal = array();
		}else{
			$a_deal  = $query->row_array();	
		}

		
		$sql_visit="select
		ifnull(SUM(tmp.closedvisit),0) as closed,
		count(tmp.activityid) as max
		from (
		 select 
		 aicrm_activity.activityid,
		 case when aicrm_activity.eventstatus != 'Plan' then 1 else 0 end as closedvisit
		 FROM aicrm_activity
		 INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
		 INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
		 LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
		 WHERE aicrm_crmentity.deleted = 0 and aicrm_crmentity.smownerid = '".$userid."'
		 and MONTH(aicrm_crmentity.createdtime) =  '".$month."'
		    and YEAR(aicrm_crmentity.createdtime) = '".$year."'
		) as tmp;";

		$query = $this->ci->db->query($sql_visit);

		if(!$query){
			$a_visit = array();
		}else{
			$a_visit  = $query->row_array();			
		}

		$a_result = array('visit' => $a_visit , 'deal' => $a_deal );

		// alert($a_result);exit;
		return $a_result;
	}

	function get_recent($userid=""){

		$sql=" select
		aicrm_audit_trial.module,
		CASE 
		WHEN aicrm_audit_trial.module = 'Accounts' THEN aicrm_account.name 
		WHEN aicrm_audit_trial.module = 'Lead' THEN aicrm_leaddetails.name 
		WHEN aicrm_audit_trial.module = 'Deal' THEN aicrm_deal.name
		WHEN aicrm_audit_trial.module = 'Calendar' THEN aicrm_activity.name
		WHEN aicrm_audit_trial.module = 'Questionnaire' THEN aicrm_questionnaire.name
		ELSE '' 
		END as name,
		CASE 
		WHEN aicrm_audit_trial.action = 'DetailView' THEN concat('View ',aicrm_audit_trial.module )
		WHEN aicrm_audit_trial.action = 'EditView' THEN concat('Edit ',aicrm_audit_trial.module )
		WHEN aicrm_audit_trial.action = 'Save' THEN concat('Add ',aicrm_audit_trial.module )
		ELSE ''
		END as action,
		aicrm_audit_trial.recordid as id,
		aicrm_audit_trial.userid ,
		DATE_FORMAT(actiondate,'%d/%m/%Y %H:%i:%s') as viewtime
		from aicrm_audit_trial
		INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_account.accountid, aicrm_account.accountname as name from aicrm_account
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_account on aicrm_account.accountid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_leaddetails.leadid, concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as name from aicrm_leaddetails
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_leaddetails on aicrm_leaddetails.leadid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_activity.activityid, aicrm_account.accountname as name from aicrm_activity
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_activity.activityid
		     INNER JOIN aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
		    LEFT JOIN aicrm_account on aicrm_account.accountid = aicrm_activity.parentid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_activity on aicrm_activity.activityid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_deal.dealid, aicrm_deal.deal_no as name from aicrm_deal
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_deal on aicrm_deal.dealid = aicrm_audit_trial.recordid
		LEFT JOIN (
		 select aicrm_questionnaire.questionnaireid, aicrm_questionnaire.questionnaire_no as name from aicrm_questionnaire
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
		    where aicrm_crmentity.deleted = 0
		) as aicrm_questionnaire on aicrm_questionnaire.questionnaireid = aicrm_audit_trial.recordid
		where aicrm_audit_trial.userid='".$userid."' and aicrm_audit_trial.action in('DetailView','EditView','Save')
		and aicrm_audit_trial.module in('Accounts','Calendar','Deal','Leads','Questionnaire')
		and aicrm_crmentity.deleted=0 
		order by aicrm_audit_trial.actiondate 
		desc limit 10;";

		$query = $this->ci->db->query($sql);

		if(!$query){
			$a_result = array();
		}else{
			$a_result  = $query->result_array();
		}
		//alert($a_result);exit();

		return $a_result;
	}


function get_summary($userid="",$date=""){

		$date = explode("/",$date);
		
		$month = $date[0];
		$year = $date[1];

		$a_visit = $this->get_summary_by_module($userid, $month, $year, "Calendar");
		$a_deal = $this->get_summary_by_module($userid, $month, $year, "Deal");
		$a_result = [];
		array_push($a_result,$a_visit);
		array_push($a_result,$a_deal);
		return $a_result;

}

function get_summary_by_module($where_user = "", $month = "", $year = "", $module = "")
	{
		switch ($module) {
	
			case 'Calendar':
				$query_module = "select
				'Calendar' as module,
				ifnull(SUM(tmp.closedvisit),0) as closed,
				count(tmp.activityid) as max
				from (
				 select 
				 aicrm_activity.activityid,
				 case when aicrm_activity.eventstatus != 'Plan' then 1 else 0 end as closedvisit
				 FROM aicrm_activity
				 INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
				 INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
				 LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				 WHERE aicrm_crmentity.deleted = 0 and aicrm_crmentity.smownerid = '".$where_user."'
				 and MONTH(aicrm_crmentity.createdtime) =  '".$month."'
					and YEAR(aicrm_crmentity.createdtime) = '".$year."'
				) as tmp;";
				break;

			case 'Deal':
				$query_module = "select
				'Deal' as module,
				ifnull(sum(tmp.closeddeal),0) as closed ,
				count(tmp.dealid) as max
				from (
				 select aicrm_deal.dealid , 
					case when aicrm_deal.stage = 'ปิดแพ้' || aicrm_deal.stage = 'ปิดชนะ' then 1 
					else 0 
					end as closeddeal
				 from aicrm_deal
				 inner join aicrm_dealcf on aicrm_dealcf.dealid = aicrm_deal.dealid
				 inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
				 where aicrm_crmentity.deleted = 0 and aicrm_crmentity.smownerid = '".$where_user."'
				 and MONTH(aicrm_crmentity.createdtime) = '".$month."'
					and YEAR(aicrm_crmentity.createdtime) = '".$year."'
				) as tmp;";
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
	function get_allsummary($userid = "", $date = "")
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

		$sql_tab = "SELECT tabid,name FROM aicrm_tab WHERE name IN ('Deal','Calendar','Job','HelpDesk','Inspection')";
		$query_tab = $this->db->query($sql_tab);
		$a_tab  = $query_tab->result_array();
		foreach ($a_tab as $key => $value) {
			if ($value['name'] == "Calendar") {
				$a_visit['module'] = "Calendar";
				$a_visit['result'] = $this->get_allsummary_by_module($userid, $month, $year, "Calendar", $value['tabid']);
			} elseif ($value['name'] == "Deal") {
				$a_deal['module'] = "Deal";
				$a_deal['result'] = $this->get_allsummary_by_module($userid, $month, $year, "Deal", $value['tabid']);
			} elseif ($value['name'] == "Job") {
				$a_jobs['module'] = "Job";
				$a_jobs['result'] = $this->get_allsummary_by_module($userid, $month, $year, "Job", $value['tabid']);
			} elseif ($value['name'] == "HelpDesk") {
				$a_case['module'] = "HelpDesk";
				$a_case['result'] = $this->get_allsummary_by_module($userid, $month, $year, "HelpDesk", $value['tabid']);
			} elseif ($value['name'] == "Inspection") {
				$a_inspection['module'] = "Inspection";
				$a_inspection['result'] = $this->get_allsummary_by_module($userid, $month, $year, "Inspection", $value['tabid']);
			} else {
			}
		}

		$a_result = [];
		if(!empty($a_visit)) array_push($a_result,$a_visit);
		if(!empty($a_deal)) array_push($a_result,$a_deal);
		// if(!empty($a_jobs)) array_push($a_result,$a_jobs);
		// if(!empty($a_case)) array_push($a_result,$a_case);
		//if(!empty($a_inspection)) array_push($a_result,$a_inspection);

		// alert($a_result); exit;

		return $a_result;
	}

	function get_allsummary_by_module($where_user = "", $month = "", $year = "", $module = "", $tabid = "")
	{

		switch ($module) {
			case 'Job':
				$query_module = "SELECT
					aicrm_jobs.jobid AS id,
					aicrm_jobs.job_no AS no,
					aicrm_jobs.job_name AS title,
					aicrm_jobs.job_no AS description,
					IFNULL( aicrm_account.accountname, '' ) AS name,
					aicrm_jobs.job_status AS status,
					aicrm_jobs.job_type AS value,
					CASE WHEN aicrm_picklistcolor.color IS NULL THEN '#33DAFF' ELSE aicrm_picklistcolor.color END AS color,
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
					AND YEAR ( aicrm_crmentity.createdtime ) = '" . $year . "';
				";
				break;

			case 'Calendar':
				$query_module = "SELECT 
					aicrm_activity.activityid AS id,
					aicrm_activity.activity_no AS no,
					aicrm_activity.activitytype AS value,
					aicrm_activity.activitytype AS title,
					aicrm_activity.activity_no AS description,
					CASE WHEN aicrm_account.accountname IS NULL OR aicrm_account.accountname = '' THEN aicrm_leaddetails.leadname ELSE aicrm_account.accountname END AS name,
					aicrm_activity.eventstatus AS status,
					CASE WHEN aicrm_picklistcolor.color IS NULL THEN '#33DAFF' ELSE aicrm_picklistcolor.color END AS color,
					aicrm_activity.date_start AS dateAt,
					aicrm_activity.time_start AS timeStart,
					aicrm_activity.time_end AS timeEnd
				FROM aicrm_activity
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
				INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
				LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
				LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				LEFT JOIN (
					select * 
					from aicrm_picklistcolor 
					where aicrm_picklistcolor.tabid='" . $tabid . "'
					) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_activity.eventstatus
				WHERE aicrm_crmentity.deleted = 0 and aicrm_crmentity.smownerid in (" . $where_user . ") 
				and MONTH(aicrm_crmentity.createdtime) = '" . $month . "'
				and YEAR(aicrm_crmentity.createdtime) = '" . $year . "'";
				break;

			case 'Deal':
				$query_module = "SELECT 
					aicrm_deal.dealid AS id, 
					aicrm_deal.deal_no AS no,
					aicrm_deal.deal_name AS title,
					aicrm_deal.deal_no AS description, 
					aicrm_deal.dealamount AS value,
					CASE WHEN aicrm_account.accountname IS NULL OR aicrm_account.accountname = '' THEN aicrm_leaddetails.leadname ELSE aicrm_account.accountname END AS name,
					'' AS subtitle,
					aicrm_deal.stage AS status,
					CASE WHEN aicrm_picklistcolor.color IS NULL THEN '#33DAFF' ELSE aicrm_picklistcolor.color END AS color,
					aicrm_crmentity.createdtime AS dateAt 
					FROM aicrm_deal
					INNER JOIN aicrm_dealcf on aicrm_dealcf.dealid = aicrm_deal.dealid
					INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
					LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_deal.parentid
					LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_deal.parentid
					LEFT JOIN (
						select * 
						from aicrm_picklistcolor 
						where aicrm_picklistcolor.tabid='" . $tabid . "'
						) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_deal.stage
					where aicrm_crmentity.deleted = 0 and aicrm_crmentity.smownerid in (" . $where_user . ")
					and MONTH(aicrm_crmentity.createdtime) = '" . $month . "'
					and YEAR(aicrm_crmentity.createdtime) = '" . $year . "';";
				break;

			case 'HelpDesk':
				$query_module = "SELECT
						aicrm_troubletickets.ticketid AS id,
						aicrm_troubletickets.ticket_no AS no,
						aicrm_troubletickets.title AS title,
						aicrm_troubletickets.ticket_no AS description,
						IFNULL( aicrm_account.accountname, '' ) AS name,
						aicrm_troubletickets.case_status AS status,
						CASE WHEN aicrm_picklistcolor.color IS NULL THEN '#33DAFF' ELSE aicrm_picklistcolor.color END AS color,
						aicrm_crmentity.createdtime AS dateAt 
					FROM
					aicrm_troubletickets
					INNER JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
					LEFT JOIN aicrm_ticketcomments ON aicrm_ticketcomments.ticketid = aicrm_troubletickets.ticketid
					LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_troubletickets.contactid
					LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
					LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_troubletickets.product_id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN ( 
						SELECT * FROM aicrm_picklistcolor WHERE aicrm_picklistcolor.tabid = '" . $tabid . "' 
						) aicrm_picklistcolor ON aicrm_picklistcolor.picklist_value = aicrm_troubletickets.case_status 
				WHERE
					aicrm_crmentity.deleted = 0 
					AND aicrm_crmentity.smownerid IN (" . $where_user . ")
					AND MONTH ( aicrm_crmentity.createdtime ) = '" . $month . "'
					AND YEAR ( aicrm_crmentity.createdtime ) = '" . $year . "';
					";
				break;
			case 'Inspection':
				$query_module = "SELECT
					aicrm_inspection.inspectionid AS id,
					aicrm_inspection.inspection_no AS no,
					aicrm_inspection.inspection_name AS name,
					aicrm_inspection.inspection_no AS description,
					aicrm_inspection.inspection_status AS status,
					CASE WHEN aicrm_picklistcolor.color IS NULL THEN '#33DAFF' ELSE aicrm_picklistcolor.color END AS color,
					aicrm_crmentity.createdtime AS dateAt 
				FROM
					aicrm_inspection
					INNER JOIN aicrm_inspectioncf ON aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid
					LEFT JOIN ( 
						SELECT * FROM aicrm_picklistcolor WHERE aicrm_picklistcolor.tabid = '41' 
						) aicrm_picklistcolor ON aicrm_picklistcolor.picklist_value = aicrm_inspection.inspection_status 
				WHERE
					aicrm_crmentity.deleted = 0 
					AND aicrm_crmentity.smownerid IN (" . $where_user . ")
					AND MONTH ( aicrm_crmentity.createdtime ) = '" . $month . "'
					AND YEAR ( aicrm_crmentity.createdtime ) = '" . $year . "';
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
