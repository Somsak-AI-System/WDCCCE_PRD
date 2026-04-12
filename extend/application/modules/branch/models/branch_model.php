<?php
class branch_model extends CI_Model { 

	var $my_server;  
 	
 	function get_branch(){
		
		$sql = "select aicrm_branch.branchid,aicrm_branch.branch_name ,aicrm_branch.pj_project_type, aicrm_branch.pj_status from aicrm_branch
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branch.branchid
				inner join aicrm_branchcf on aicrm_branch.branchid= aicrm_branchcf.branchid
                where aicrm_crmentity.deleted = 0 order by aicrm_branch.branch_name  ";
       
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

   
}

?>