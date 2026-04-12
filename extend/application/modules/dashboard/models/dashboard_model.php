<?php
class dashboard_model extends CI_Model { 

	var $my_server;  
 	
 	function get_inspector($branchid,$inspecttype){

		$sql_worksheet = "select count(*) as worksheet from aicrm_inspection 
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
		where aicrm_crmentity.deleted = 0 and aicrm_inspection.branchid =  '".$branchid."' and aicrm_inspection.inspection_type = '".$inspecttype."' ";

		$query_worksheet = $this->db->query($sql_worksheet);
		$result_worksheet = $query_worksheet->result(0);

		$result['worksheet'] = $result_worksheet[0]['worksheet'];
		if($inspecttype == 'Customer'){
			$type = "case when aicrm_products.inspector_customer = 'None' || aicrm_products.inspector_customer = '' then count(aicrm_products.productid) else 0 end as 'wait',
			case when aicrm_products.inspector_customer = 'Open' then count(aicrm_products.productid) else 0 end 'open',
			case when aicrm_products.inspector_customer = 'Processing' then count(aicrm_products.productid) else 0 end 'processing',
			case when aicrm_products.inspector_customer = 'Closed' then count(aicrm_products.productid) else 0 end 'closed'";
			$field = 'aicrm_products.inspector_customer';
		}else{
			$type = "case when aicrm_products.inspector_contractor = 'None' || aicrm_products.inspector_contractor = '' then count(aicrm_products.productid) else 0 end as 'wait',
			case when aicrm_products.inspector_contractor = 'Open' then count(aicrm_products.productid) else 0 end 'open',
			case when aicrm_products.inspector_contractor = 'Processing' then count(aicrm_products.productid) else 0 end 'processing',
			case when aicrm_products.inspector_contractor = 'Closed' then count(aicrm_products.productid) else 0 end 'closed'";
			$field = 'aicrm_products.inspector_contractor';
		}

		$sql_allstatus = "select 
			sum(a.all) as 'All',
			sum(a.wait) as 'Wait',
			sum(a.open) as 'Open',
			sum(a.processing) as 'Processing',
			sum(a.closed) as 'Closed'
			from (
			select count(aicrm_products.productid) as 'all' ,  
			".$type."
			from aicrm_products
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
			inner join aicrm_branch on aicrm_branch.branchid = aicrm_products.branchid
			inner join aicrm_crmentity crmbranch on crmbranch.crmid = aicrm_branch.branchid and crmbranch.deleted = 0
			where aicrm_crmentity.deleted = 0 and aicrm_products.branchid =  '".$branchid."'
			group by ".$field." ) as a ";

		$query_allstatus = $this->db->query($sql_allstatus);
		$result_allstatus = $query_allstatus->result(0);

		$result['allstatus'] = $result_allstatus[0];

		$sql_dealy = "select aicrm_inspection.* ,aicrm_branch.branch_name,aicrm_products.productname from aicrm_inspection
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
			inner join aicrm_products on aicrm_products.productid = aicrm_inspection.productid
			inner join aicrm_branch on aicrm_branch.branchid = aicrm_inspection.branchid
			inner join aicrm_crmentity as crmpro on crmpro.crmid = aicrm_products.productid
			where concat(inspection_date,' ',inspection_time) < now() and inspection_type = '".$inspecttype."' and aicrm_inspection.branchid =  '".$branchid."' 
			and aicrm_crmentity.deleted = 0 and crmpro.deleted = 0 and aicrm_inspection.inspection_status in ('Open','Processing') ";
		$query_dealy = $this->db->query($sql_dealy);
		$result_dealy = $query_dealy->result(0);

		$result['dealy'] = $result_dealy;
		$sql_defect = "select aicrm_zone.zone_name , COUNT(aicrm_zone.zone_name) as sum_defect
			from aicrm_inspection
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid and aicrm_crmentity.deleted = 0 
			INNER JOIN aicrm_inspectiondefectlist on .aicrm_inspectiondefectlist.inspectionid = aicrm_inspection.inspectionid
			INNER JOIN aicrm_crmentity as crmdefectlist on crmdefectlist.crmid = aicrm_inspectiondefectlist.inspectiondefectlistid and crmdefectlist.deleted = 0 
			INNER JOIN aicrm_defect on .aicrm_defect.defectid = aicrm_inspectiondefectlist.defectid
			INNER JOIN aicrm_crmentity as crmdefect on crmdefect.crmid = aicrm_defect.defectid and crmdefect.deleted = 0 
			INNER JOIN aicrm_zone on aicrm_zone.zoneid = aicrm_defect.zoneid
			INNER JOIN aicrm_crmentity as crmzoon on crmzoon.crmid = aicrm_zone.zoneid and crmzoon.deleted = 0 
			where aicrm_inspection.inspection_type = '".$inspecttype."' and aicrm_inspection.branchid = '".$branchid."' and aicrm_inspectiondefectlist.defectlist_status = 'Not Pass'
			group by aicrm_zone.zoneid order by COUNT(aicrm_zone.zone_name) desc limit 10";
		$query_defect = $this->db->query($sql_defect);
		$result_defect = $query_defect->result(0);

		$result['defect'] = $result_defect;

		return $result;
	}
	function get_data_latest(){
		$sql = "select aicrm_inspection.* from aicrm_inspection 
				INNER JOIN aicrm_inspectioncf on aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
				INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
				INNER JOIN aicrm_products on aicrm_products.productid = aicrm_inspection.productid
				INNER JOIN aicrm_crmentity as crmproduct on crmproduct.crmid = aicrm_products.productid
				INNER JOIN aicrm_branch on aicrm_branch.branchid = aicrm_inspection.branchid
				INNER JOIN aicrm_crmentity as crmbranch on crmbranch.crmid = aicrm_branch.branchid
				where aicrm_crmentity.deleted = 0 and crmbranch.deleted = 0 and crmproduct.deleted=0 
				order by aicrm_inspection.inspectionid desc limit 1";
		$query = $this->db->query($sql);
		$result = $query->result(0);
		return $result;
	}

	function get_owner_latest(){
		$sql = "select aicrm_branch.branchid from aicrm_inspection
			INNER JOIN aicrm_inspectioncf on aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
			INNER JOIN aicrm_products on aicrm_products.productid = aicrm_inspection.productid
			INNER JOIN aicrm_crmentity as crmproduct on crmproduct.crmid = aicrm_products.productid
			INNER JOIN aicrm_branch on aicrm_branch.branchid = aicrm_inspection.branchid
			INNER JOIN aicrm_crmentity as crmbranch on crmbranch.crmid = aicrm_branch.branchid
			where aicrm_crmentity.deleted = 0 and crmbranch.deleted = 0 and crmproduct.deleted=0 and
			aicrm_inspection.inspection_type = 'Customer' group by aicrm_inspection.branchid 
			order by aicrm_inspection.inspectionid desc limit 3";
		$query = $this->db->query($sql);
		$result = $query->result(0);
		return $result;
	}

	function get_owner($branchid){
		//alert($branchid);exit;
		foreach ($branchid as $key => $value) {
		
			$sql = "select 
				sum(a.all) as 'All',
				sum(a.wait) as 'Wait',
				sum(a.open) as 'Open',
				sum(a.processing) as 'Processing',
				sum(a.closed) as 'Closed',
				a.branch_name ,
				a.pj_project_type
				from (
					select count(aicrm_products.productid) as 'all' ,  
					case when aicrm_products.inspector_customer = 'None' || aicrm_products.inspector_customer = '' then count(aicrm_products.productid) else 0 end as 'wait',
					case when aicrm_products.inspector_customer = 'Open' then count(aicrm_products.productid) else 0 end 'open',
					case when aicrm_products.inspector_customer = 'Processing' then count(aicrm_products.productid) else 0 end 'processing',
					case when aicrm_products.inspector_customer = 'Closed' then count(aicrm_products.productid) else 0 end 'closed',
					aicrm_branch.branch_name,
					aicrm_branch.pj_project_type
					from aicrm_products
					inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
					inner join aicrm_branch on aicrm_branch.branchid = aicrm_products.branchid
					inner join aicrm_crmentity crmbranch on crmbranch.crmid = aicrm_branch.branchid and crmbranch.deleted = 0
					where aicrm_crmentity.deleted = 0 and aicrm_products.branchid ='".$value."'
					group by aicrm_products.inspector_customer 
				) as a ";
			$query = $this->db->query($sql);
			$all_status = $query->result(0);
			
			$sql_defect = "select aicrm_zone.zone_name , COUNT(aicrm_zone.zone_name) as sum_defect
				from aicrm_inspection
				INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid and aicrm_crmentity.deleted = 0 
				INNER JOIN aicrm_inspectiondefectlist on .aicrm_inspectiondefectlist.inspectionid = aicrm_inspection.inspectionid
				INNER JOIN aicrm_crmentity as crmdefectlist on crmdefectlist.crmid = aicrm_inspectiondefectlist.inspectiondefectlistid and crmdefectlist.deleted = 0 
				INNER JOIN aicrm_defect on .aicrm_defect.defectid = aicrm_inspectiondefectlist.defectid
				INNER JOIN aicrm_crmentity as crmdefect on crmdefect.crmid = aicrm_defect.defectid and crmdefect.deleted = 0 
				INNER JOIN aicrm_zone on aicrm_zone.zoneid = aicrm_defect.zoneid
				INNER JOIN aicrm_crmentity as crmzoon on crmzoon.crmid = aicrm_zone.zoneid and crmzoon.deleted = 0 
				where aicrm_inspection.branchid = '".$value."' and aicrm_inspectiondefectlist.defectlist_status = 'Not Pass'
				group by aicrm_zone.zoneid order by COUNT(aicrm_zone.zone_name) desc limit 10";
			$query_defect = $this->db->query($sql_defect);
			$result_defect = $query_defect->result(0);

			$result[$key]['status'] = $all_status[0];
			$result[$key]['defect'] = $result_defect;
		}
		//alert($result); exit;
		return $result;
	}

}

?>