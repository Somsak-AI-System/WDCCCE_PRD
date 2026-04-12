<?php
class profile_model extends CI_Model
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
			$this->db->select("aicrm_users.id,aicrm_users.user_name,aicrm_users.first_name,aicrm_users.last_name, aicrm_users.phone_mobile,aicrm_users.email1 " );
		}else{
		$this->db->select("aicrm_users.id,aicrm_users.user_name,aicrm_users.first_name,aicrm_users.last_name, aicrm_users.phone_mobile,aicrm_users.email1 ,
			aicrm_users.signature,aicrm_users.address_street,aicrm_users.address_city,aicrm_users.address_state,aicrm_users.address_country,aicrm_users.address_postalcode" );

			}

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
		// alert($this->last_query());exit;

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


	function changePass_post($userid,$username,$currentpass,$newpass,$confirmpass){

	$id= $userid;
	$user_name=$username;

		$salt = substr($user_name, 0, 2);
		$salt = '$1$' . $salt . '$';
		$encrypted_password = crypt($currentpass, $salt);
		$old_password = $encrypted_password;

		$sql = "select user_password from aicrm_users where user_password ='".$old_password."' ";
		$query = $this->db->query($sql);
		$result=$query->result_array();
		if($result!=null){

			if($newpass != $confirmpass){
				$crmid = null;
			}else{

			$salt = substr($user_name, 0, 2);
			$salt = '$1$'.$salt.'$';
			$encrypted_password = crypt($newpass,$salt);
			$user_password=$encrypted_password;
			$confirm_password=$encrypted_password;

			$sql_update="update aicrm_users set user_password='".$user_password."',confirm_password='".$confirm_password."' where id='".$id."' ";
			$query = $this->db->query($sql_update);

			$crmid = $userid;
			$a_return['crmid'] = $crmid;
		}


		}else{
			$a_return['Message'] = 'Invalid Current password ';
			$a_return['crmid'] = "";
		}


		return $a_return;

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
