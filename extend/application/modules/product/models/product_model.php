<?php
class product_model extends CI_Model { 

	var $my_server;  
 	
 	function get_product(){
			
		$sql = "select aicrm_branchs.branchid,aicrm_branchs.branch_name from aicrm_branchs
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branchs.branchid
				inner join aicrm_branchscf on aicrm_branchs.branchid= aicrm_branchscf.branchid
                where aicrm_crmentity.deleted = 0 and aicrm_branchs.branch_name <> ''  and  aicrm_branchs.projectsstatus='Active' order by aicrm_branchs.branch_name  ";
        /*$sql = " SELECT accountid as branchid, accountname as branch_name FROM aicrm_account";*/
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}
   
}

?>