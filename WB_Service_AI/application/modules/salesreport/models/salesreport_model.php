<?php
class salesreport_model extends CI_Model
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
  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$optimize=array(),$a_params=array())
  {

  	try {
  		// $a_condition["aicrm_crmentity.setype"] = "Job";
  		$a_condition["aicrm_users.deleted"] = "0";

		  if($optimize == '1'){
			$this->db->select("aicrm_users.id,aicrm_users.user_name,aicrm_users.first_name,aicrm_users.last_name, aicrm_users.phone_mobile,aicrm_users.email1,aicrm_users.cf_4257 ,aicrm_users.cf_4285, aicrm_users.cf_4286" );
	  	 }else{
			 $this->db->select("aicrm_users.id,aicrm_users.user_name,aicrm_users.first_name,aicrm_users.last_name, aicrm_users.phone_mobile,aicrm_users.email1,aicrm_users.cf_4257 ,aicrm_users.cf_4285, aicrm_users.cf_4286,
			 aicrm_users.signature,aicrm_users.address_street,aicrm_users.address_city,aicrm_users.address_state,aicrm_users.address_country,aicrm_users.address_postalcode" );
				// $this->db->select("aicrm_users.*, aicrm_jobscf.* ,aicrm_account.accountid, aicrm_account.accountname, aicrm_serial.serialid, aicrm_serial.serial_name, aicrm_products.productid,  aicrm_products.productname
				// ,aicrm_users.user_name as user_assign" );

			   }

		// $this->db->join('aicrm_jobscf', 'aicrm_jobscf.jobid = aicrm_jobs.jobid','inner');
		// $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_jobs.jobid' ,'inner');
		// $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','inner');
		// 	$this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_jobs.accountid','left');
		// 	$this->db->join('aicrm_serial', 'aicrm_serial.serialid = aicrm_jobs.serialid','left');
		// 	$this->db->join('aicrm_products', 'aicrm_products.productid = aicrm_jobs.product_id','left');

  		if (!empty($a_condition)) {
			$this->db->where($a_condition);

		}

	  if (!empty($a_order)) {
			for($i=0;$i<count($a_order);$i++){
				$this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);

			}
		}
		if (empty($a_limit)) {
			$a_limit["limit"] = $this->_limit;
			$a_limit["offset"] = 0;
			$this->db->limit($a_limit["limit"],$a_limit["offset"]);

		}else if($a_limit["limit"]==0){
		}else{
			$this->db->limit($a_limit["limit"],$a_limit["offset"]);

		}

		$query = $this->db->get('aicrm_users');

  		if(!$query){
			$a_return["status"] = false;
			$a_return["error"] = $this->db->_error_message();
			$a_return["result"] = "";
		}else{
			$a_result  = $query->result_array() ;

			foreach($a_result as $key => $val){
				foreach($val as $k => $v){
				if($v==null){
					$v="";
					$val[$k] = $v;
					$val_change = $val[$k];
				}
				$val[$k] = $v;
			}
			$a_result[$key] = $val;
			}


			$a_total = $this->get_total($a_condition,$a_params) ;

		  if (!empty($a_result)) {
				$a_return["status"] = true;
				$a_return["error"] =  "";
				$a_return["result"] = $a_result;
			}else{
				$a_return["status"] = false;
				$a_return["error"] =  "No Data";
				$a_return["result"] = "";
			}
		}
		}catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		  }
		return $a_return;
	}


	function get_total($a_condition=array(),$a_conditionin=array())
  {
  	try {
  		if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}else{
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
  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
  			$a_result  = $query->result_array() ;

  			if (!empty($a_result)) {
  				$a_return["status"] = true;
  				$a_return["error"] =  "";
  				$a_return["result"] = $a_result[0];
  			}else{
  				$a_return["status"] = false;
  				$a_return["error"] =  "No Data";
  				$a_return["result"] = "";
  			}
  		}
  	}catch (Exception $e) {
  		$a_return["status"] = false;
  		$a_return["error"] =  $e->getMessage();
  		$a_return["result"] = "";
		}

  	return $a_return;
	}


  function sendEmail_post($a_param){
    $date = date('Y-m-d H:i:s');
  // $submodule = $a_param["submodule"];
  // $submodule = ;

	$insert_tbm = $this->insert_update_send_report($a_param);

  $string_param = serialize($a_param);
  $data_log["userid"] = @$a_data["userid"];
		$data_log["reporttype"] = $submodule=="weeklyreport" ? "weeklyreport" :@$a_data["report_type"];
		$data_log["sendtime"] = $date;
		$data_log["adduser"] = USERID;
		$data_log["adddate"] = $date;
		$data_log["parameter"] = $string_param;
		$data_log["sendto"] =$a_param["send_to"] ;

    $insert_tbm = $this->salesvisit_model->insert_update_send_report($a_param);


  $crmid =" ";
  return $crmid;

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
			/*$sql1 = "INSERT INTO aicrm_activity_tran_send_mail_to (user_id,send_user_id,addempcd,addpcnm,adddt,updempcd,updpcnm,upddt)
			VALUES ('".$data['userid']."','".$data['send_to']."','".$data['userid']."','localhost','".date('Y-m-d H:i:s')."','".$data['userid']."','localhost','".date('Y-m-d H:i:s')."')
			ON DUPLICATE KEY UPDATE user_id='".$data['userid']."' , send_user_id ='".$data['send_to']."' ";*/
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




	// function get_imageprofile($a_conditionin=array(),$module="",$userid=""){
		function get_imageprofile($a_params){
  		// $url_new= $this->CI->config->item('url_new');
			$this->load->config('config_base');

			$url_new = $this->config->item('url_new');

  		$a_limit["limit"] = 0;
			$a_limit["offset"] = 0;
			$userid = $a_params['userid'];

			$sql = "SELECT `aicrm_attachments`.`attachmentsid`, `aicrm_attachments`.`path`, `aicrm_attachments`.`attachmentsid`, `aicrm_attachments`.`name`
			FROM (`aicrm_users`)
			LEFT JOIN `aicrm_attachments` ON `aicrm_attachments`.`name`  = `aicrm_users`.`imagename`
			WHERE `aicrm_users`.`id`='".$userid."'";
			$query = $this->db->query($sql);
			$a_result  = $query->result_array() ;

			$a_response = array();
						if($a_result[0]["name"]==''){
							$a_result[0]["attachmentsid"] ="";
						}else{
							$a_result[0]["attachmentsid"] =$url_new.$a_result[0]["path"].$a_result[0]["name"];
						}



				$a_response["userid"] =$userid;
				$a_response["image"] =$a_result[0]["attachmentsid"];

  		return $a_response;
	}



}
