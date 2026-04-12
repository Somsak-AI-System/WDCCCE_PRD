<?php
class home_model extends CI_Model { 

	var $my_server;  
 	
	function get_assigned(){
		
		$sql = "select id , concat(first_name,' ',last_name) as fullname from aicrm_users where deleted = 0 order by fullname asc";

		$query = $this->db->query($sql);
		
		$result = $query->result(0);
		return $result;
	}

}

?>