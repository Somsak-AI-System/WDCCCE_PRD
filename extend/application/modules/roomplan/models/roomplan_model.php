<?php
class roomplan_model extends CI_Model { 

	var $my_server;  
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