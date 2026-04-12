<?php
class user_model extends CI_Model { 

	var $my_server;  
 	
 	function get_users(){
	
		$sql = "SELECT id , user_name , first_name ,last_name ,status,phone_mobile FROM `aicrm_users` WHERE deleted = 0";

		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}
   
}

?>