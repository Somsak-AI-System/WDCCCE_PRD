<?php
class building_model extends CI_Model { 

	var $my_server;  
 	
 	function get_building(){
	
		/*$sql = "select aicrm_branch.branchid ,aicrm_branch.branch_name , aicrm_branch.pj_status 
		from aicrm_branch 
		inner join aicrm_branchcf on aicrm_branchcf.branchid = aicrm_branch.branchid
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branch.branchid
		where aicrm_crmentity.deleted = 0 ";*/
		
		$sql = "select aicrm_building.buildingid,aicrm_branchs.branch_name,aicrm_building.building_name 
			from aicrm_building
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_building.buildingid
				inner join aicrm_buildingcf on aicrm_buildingcf.buildingid= aicrm_building.buildingid
				inner join aicrm_branchs on aicrm_branchs.branchid= aicrm_building.branchid
                where aicrm_crmentity.deleted = 0 order by aicrm_building.building_name  ";
        
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}
	   
}

?>