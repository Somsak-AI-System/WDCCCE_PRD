<?php

class Register_model extends CI_Model {

	function __construct()
	{
		$this->CI = get_instance();
	}


	function check_username($a_condition=array())
	{		
		$sql ="SELECT count(cf_2131) AS number_username FROM aicrm_contactscf WHERE cf_2131 = '".$a_condition['username']."'";
		$query = $this->db->query($sql);
		$data = $query->result(0);
		//echo $sql;
		return $data[0];
	}
}

?>