<?php
class defect_model extends CI_Model { 

	var $my_server;  
 	
 	/*function get_defect(){
	
		$sql = "select aicrm_branchs.branchid,aicrm_branchs.branch_name from aicrm_branchs
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branchs.branchid
				inner join aicrm_branchscf on aicrm_branchs.branchid= aicrm_branchscf.branchid
                where aicrm_crmentity.deleted = 0 and aicrm_branchs.branch_name <> ''  and  aicrm_branchs.projectsstatus='Active' order by aicrm_branchs.branch_name  ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}*/
	function get_data_zone(){
	
		$sql = "select aicrm_zone.zoneid,aicrm_zone.zone_name from aicrm_zone
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_zone.zoneid
				inner join aicrm_zonecf on aicrm_zonecf.zoneid= aicrm_zone.zoneid
                where aicrm_crmentity.deleted = 0 order by aicrm_zone.zone_name  ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}
   
}

?>