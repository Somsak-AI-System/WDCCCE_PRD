<?php

class Salesvisit_model extends CI_Model {

	function __construct()
	{
		$this->CI = get_instance();
	}
	
	function get_section($data){
		$sql = "select section from aicrm_users where id ='".$data."' ";
		$a_data = $this->db->query($sql);
		$result = $a_data->result(0);
		return $result;
	}
	
	function get_role_amin($data){
		
		$sql = "SELECT *
				    FROM aicrm_users
					INNER JOIN aicrm_user2role ON aicrm_user2role.userid = aicrm_users.id
					INNER JOIN aicrm_role ON aicrm_role.roleid = aicrm_user2role.roleid
					WHERE aicrm_users.deleted =0
					AND aicrm_user2role.userid = '".$data."'
					AND lower( aicrm_role.rolename ) LIKE '%admin%' ";
		$a_data = $this->db->query($sql);
		$result = $a_data->result(0);
		return $result;
	
	}

	function insert_update_send_report($data){
				
		if($data['submodule'] == 'weeklyplan'){
			if($data['report_type'] == 'Weekly Plan'){
				$sql = "UPDATE aicrm_activity_tran_weekly_plan SET weekly_send_date = '".date('Y-m-d H:i:s')."' , weekly_check_send = '1' WHERE weekly_id = '".$data['report_plan']."' AND weekly_year = '".$data['year']."' AND weekly_user_id = '".$data['userid']."' ";
			}else{
				$sql = "UPDATE aicrm_activity_tran_monthly_plan SET monthly_send_date = '".date('Y-m-d H:i:s')."' , monthly_check_send = '1' WHERE monthly_id = '".$data['report_plan']."' AND monthly_year = '".$data['year']."' AND monthly_user_id = '".$data['userid']."' ";
			}
		}else if($data['submodule'] == 'weeklyreport'){
			$sql = "UPDATE aicrm_activity_tran_daily_report SET daily_send_date = '".date('Y-m-d H:i:s')."' , daily_check_send = '1' WHERE daily_id = '".$data['report_plan']."' AND daily_year = '".$data['year']."' AND daily_user_id = '".$data['userid']."' ";
		}
		if($this->db->query($sql)){
			
			$sql1 = "select * from aicrm_activity_tran_send_mail_to where user_id = '".$data['userid']."' and deleted = 0";
			$a_data = $this->db->query($sql1);
			$result = $a_data->result(0);
			
			if(count($result) == 0){
				$sql2 = "INSERT INTO aicrm_activity_tran_send_mail_to (user_id,send_user_id,addempcd,addpcnm,adddt) 
			VALUES ('".$data['userid']."','".$data['send_to']."','".$data['userid']."','localhost','".date('Y-m-d H:i:s')."' ) ";
			
			}else{
				$sql2 = "UPDATE aicrm_activity_tran_send_mail_to SET send_user_id = '".$data['send_to']."' ,updempcd = '".$data['userid']."',updpcnm = 'localhost' ,updpcnm = '".date('Y-m-d H:i:s')."' where user_id = '".$data['userid']."' ";
			}

			$this->db->query($sql2);
			return true;
		}else{
			return false;
		}
			
	}

}

?>