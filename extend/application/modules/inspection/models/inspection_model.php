<?php
class inspection_model extends CI_Model { 

	var $my_server;  
 	
 	public function __construct()
  	{
    	parent::__construct();
    	$this->url_service = $this->config->item('service');
    	$this->url_path = $this->config->item('path');
  	}

 	function get_inspection(){
			
		$sql = "select aicrm_branchs.branchid,aicrm_branchs.branch_name from aicrm_branchs
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branchs.branchid
				inner join aicrm_branchscf on aicrm_branchs.branchid= aicrm_branchscf.branchid
                where aicrm_crmentity.deleted = 0 and aicrm_branchs.branch_name <> ''  and  aicrm_branchs.projectsstatus='Active' order by aicrm_branchs.branch_name  ";
        /*$sql = " SELECT accountid as branchid, accountname as branch_name FROM aicrm_account";*/
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}


	function get_branch($branchid=null){
			
		$sql = "select aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_branch.pj_project_type from aicrm_branch
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branch.branchid
				inner join aicrm_branchcf on aicrm_branch.branchid= aicrm_branchcf.branchid
                where aicrm_crmentity.deleted = 0 ";
        if($branchid != ''){
        	$sql .= " and aicrm_branch.branchid in (".$branchid.") ";
        }
        $sql .= " order by aicrm_branch.branch_name ";
       
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_branch_mnt($branchid=null){
			
		$sql = "select aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_branch.pj_project_type from aicrm_branch
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branch.branchid
				inner join aicrm_branchcf on aicrm_branch.branchid= aicrm_branchcf.branchid
                where aicrm_crmentity.deleted = 0 and pj_status = 'Active' ";
        if($branchid != ''){
        	$sql .= " and aicrm_branch.branchid in (".$branchid.") ";
        }
        $sql .= " order by aicrm_branch.branch_name ";
       
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_building($branchid=null,$buildingid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name from aicrm_building
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_building.buildingid
				inner join aicrm_buildingcf on aicrm_buildingcf.buildingid= aicrm_building.buildingid
                where aicrm_crmentity.deleted = 0 ";
        if($branchid != ''){
        	$sql .= " and aicrm_building.branchid in (".$branchid.") ";
        }
        if($buildingid != ''){
        	$sql .= " and aicrm_building.buildingid in (".$buildingid.") ";
        }
        $sql .= " order by aicrm_building.building_name ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_building_mnt($branchid=null,$buildingid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name from aicrm_building
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_building.buildingid
				inner join aicrm_buildingcf on aicrm_buildingcf.buildingid= aicrm_building.buildingid
                where aicrm_crmentity.deleted = 0 and bd_status = 'Active' ";
        if($branchid != ''){
        	$sql .= " and aicrm_building.branchid in (".$branchid.") ";
        }
        if($buildingid != ''){
        	$sql .= " and aicrm_building.buildingid in (".$buildingid.") ";
        }
        $sql .= " order by aicrm_building.building_name ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_building_post($branchid=null,$buildingid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name from aicrm_building
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_building.buildingid
				inner join aicrm_buildingcf on aicrm_buildingcf.buildingid= aicrm_building.buildingid
                where aicrm_crmentity.deleted = 0 and bd_status = 'Active' ";
        if($branchid != ''){
        	$sql .= " and aicrm_building.branchid in (".$branchid.") ";
        }
        if($buildingid != ''){
        	$sql .= " and aicrm_building.buildingid in (".$buildingid.") ";
        }
        $sql .= " order by aicrm_building.building_name ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_unit($branchid=null,$buildingid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name,aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_products.productid,aicrm_products.productname from aicrm_products
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
				inner join aicrm_productcf on aicrm_productcf.productid= aicrm_products.productid
                inner join aicrm_branch on aicrm_branch.branchid = aicrm_products.branchid
				left join aicrm_building on aicrm_building.buildingid = aicrm_products.buildingid and aicrm_building.bd_status = 'Active' 
                where aicrm_crmentity.deleted = 0 ";

        if($branchid != ''){
        	$sql .= " and aicrm_products.branchid in (".$branchid.") ";
        }
        if($buildingid != ''){
        	$sql .= " and aicrm_products.buildingid in (".$buildingid.") ";
        }
        $sql .= " order by aicrm_products.productname ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_unit_post($branchid=null,$buildingid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name,aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_products.productid,aicrm_products.productname from aicrm_products
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
				inner join aicrm_productcf on aicrm_productcf.productid= aicrm_products.productid
				inner join aicrm_branch on aicrm_branch.branchid = aicrm_products.branchid
				left join aicrm_building on aicrm_building.buildingid = aicrm_products.buildingid and aicrm_building.bd_status = 'Active' 
                where aicrm_crmentity.deleted = 0 and aicrm_branch.pj_status = 'Active' ";
        
        if($branchid != ''){
        	$sql .= " and aicrm_products.branchid in (".$branchid.") ";
        }
        if($buildingid != ''){
        	$sql .= " and aicrm_products.buildingid in (".$buildingid.") ";
        }
        $sql .= " order by aicrm_products.productname ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_unit_mnt($branchid=null,$buildingid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name,aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_products.productid,aicrm_products.productname from aicrm_products
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
				inner join aicrm_productcf on aicrm_productcf.productid= aicrm_products.productid
				inner join aicrm_branch on aicrm_branch.branchid = aicrm_products.branchid
				left join aicrm_building on aicrm_building.buildingid = aicrm_products.buildingid and aicrm_building.bd_status = 'Active'
                where aicrm_crmentity.deleted = 0 and aicrm_branch.pj_status = 'Active' ";
        if($branchid != ''){
        	$sql .= " and aicrm_products.branchid in (".$branchid.") ";
        }
        if($buildingid != ''){
        	$sql .= " and aicrm_products.buildingid in (".$buildingid.") ";
        }
        
        $sql .= " order by aicrm_products.productname ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_unit_inspection($productid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name,aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_products.productid,aicrm_products.productname,CONCAT(aicrm_products.productname,' [',aicrm_branch.branch_name,']') as inspection_name ,aicrm_products.customer_name,aicrm_products.phone,aicrm_products.phone_other,aicrm_products.email
		from aicrm_products
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
				inner join aicrm_productcf on aicrm_productcf.productid= aicrm_products.productid
				inner join aicrm_branch on aicrm_branch.branchid = aicrm_products.branchid
				left join aicrm_building on aicrm_building.buildingid = aicrm_products.buildingid and aicrm_building.bd_status = 'Active'
                where aicrm_crmentity.deleted = 0  and aicrm_branch.pj_status = 'Active' ";
     
        if($productid != ''){
        	$sql .= " and aicrm_products.productid in (".$productid.") ";
        }
        $sql .= " order by aicrm_products.productname ";

		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_status(){
			
	$sql = "select aicrm_inspection_status.inspection_statusid,aicrm_inspection_status.inspection_status from aicrm_inspection_status
            where aicrm_inspection_status.presence = 1 order by aicrm_inspection_status.inspection_statusid";

	$query = $this->db->query($sql);
	$result = $query->result(0);
	
	return $result;
	}


	function get_brunch_building($productid=null){
			
		$sql = "select aicrm_building.buildingid,aicrm_building.building_name,aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_products.productid,aicrm_products.productname from aicrm_products
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
				inner join aicrm_productcf on aicrm_productcf.productid= aicrm_products.productid
				inner join aicrm_branch on aicrm_branch.branchid = aicrm_products.branchid
				left join aicrm_building on aicrm_building.buildingid = aicrm_products.buildingid and aicrm_building.bd_status = 'Active'
                where aicrm_crmentity.deleted = 0  and aicrm_branch.pj_status = 'Active' ";
        if($productid != ''){
        	$sql .= " and aicrm_products.productid in (".$productid.") ";
        }
        $sql .= " order by aicrm_products.productname ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_inspection_detail($crmid=null){
			
		$sql = "select aicrm_inspection.inspection_name,aicrm_inspection.productid,aicrm_inspection.branchid,aicrm_inspection.buildingid
		,aicrm_inspection.inspection_date,aicrm_inspection.inspection_time,aicrm_inspection.inspection_type ,aicrm_inspection.customer_name
		,aicrm_inspection.phone,aicrm_inspection.email,'Open' as inspection_status  ,aicrm_inspection.phone_other,aicrm_inspection.vendor_name
		,aicrm_inspection.vendor_mobile,aicrm_inspection.vendor_email,aicrm_crmentity.description,aicrm_crmentity.smownerid,aicrm_crmentity.smcreatorid ,aicrm_inspection.show_inspection_list,aicrm_inspection.show_defect_list
				from aicrm_inspection
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
                where aicrm_crmentity.deleted = 0 and aicrm_inspection.inspectionid in (".$crmid.") ";
        
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}



	function get_defect_list($crmid=null){
		//echo $this->url_path; exit;
		/*CONCAT('http://aisystem.dyndns.biz:8090/lkhd/',inspectiondefectlistimage_new.path,inspectiondefectlistimage_new.name)as imageurl ,*/
		/*CONCAT('http://aisystem.dyndns.biz:8090/lkhd/',inspectiondefectlistimage.path,inspectiondefectlistimage.name) as before_imageurl*/
		$sql = "SELECT DISTINCT aicrm_inspectiondefectlist.*,aicrm_inspection.inspection_type,aicrm_inspection.inspection_timeno,
				aicrm_zone.zoneid,aicrm_zone.zone_name  ,aicrm_defect.defectid,
				inspectiondefectlistimage_new.inspectiondefectlistimageid as inspectiondefectlistimageid ,
				aicrm_crmentity.description as comment,
				CONCAT('".$this->url_path."',inspectiondefectlistimage_new.path,inspectiondefectlistimage_new.name)as imageurl ,
				inspectiondefectlistimage.inspectiondefectlistimageid as before_inspectiondefectlistimageid ,
				aicrm_inspection.vendor_name as before_partner_name ,
				inspection_comment.before_comment ,
				inspection_comment.name as before_inspector_name,
				
				CONCAT('".$this->url_path."',inspectiondefectlistimage.path,inspectiondefectlistimage.name) as before_imageurl
				from aicrm_inspectiondefectlist
				LEFT JOIN aicrm_defect on aicrm_defect.defectid = aicrm_inspectiondefectlist.defectid
				LEFT JOIN aicrm_zone on aicrm_zone.zoneid = aicrm_defect.zoneid
				LEFT  JOIN aicrm_inspection on aicrm_inspection.inspectionid = aicrm_inspectiondefectlist.inspectionid
				LEFT JOIN aicrm_defectlist_status on aicrm_defectlist_status.defectlist_status = aicrm_inspectiondefectlist.defectlist_status
				LEFT JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspectiondefectlist.inspectiondefectlistid

				LEFT JOIN (
				     SELECT aicrm_crmentity.crmid,aicrm_crmentity.description as before_comment,CONCAT(aicrm_users.first_name,' ',aicrm_users.last_name) as name
				     from aicrm_crmentity
				     LEFT JOIN aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
				)inspection_comment on inspection_comment.crmid = aicrm_inspectiondefectlist.parentid

				LEFT JOIN (
				     Select DISTINCT aicrm_inspectiondefectlistimage.*, aicrm_attachments.*,aicrm_crmentity.description as new_comment from aicrm_inspectiondefectlistimage
				     inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid = aicrm_inspectiondefectlistimage.inspectiondefectlistimageid
				     inner join aicrm_attachments on aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
				     inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_seattachmentsrel.crmid
				     where aicrm_crmentity.deleted='0'
				) inspectiondefectlistimage_new on inspectiondefectlistimage_new.inspectiondefectlistid = aicrm_inspectiondefectlist.inspectiondefectlistid
				LEFT JOIN (
				          Select aicrm_inspectiondefectlistimage.*, aicrm_attachments.* from aicrm_inspectiondefectlistimage
				          inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid = aicrm_inspectiondefectlistimage.inspectiondefectlistimageid
				          inner join aicrm_attachments on aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
				) inspectiondefectlistimage on inspectiondefectlistimage.inspectiondefectlistid = aicrm_inspectiondefectlist.parentid and aicrm_inspectiondefectlist.parentid != 0
				          WHERE aicrm_inspection.inspectionid='".$crmid."'  and  aicrm_crmentity.deleted='0' ORDER BY SUBSTRING(aicrm_zone.zone_name,2, 1) asc";
        //echo $sql; exit;
		$query = $this->db->query($sql);
		$result = $query->result(0);
		
		return $result;
	}

	function get_signature_customer($crmid=null){
	  $sql_customer = "select aicrm_attachments.*,aicrm_crmentity.setype,aicrm_inspection.inspection_type from aicrm_inspection
      inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid = aicrm_inspection.inspectionid
      inner join aicrm_attachments on aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
      inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid
      where aicrm_crmentity.setype='Image 999'  and aicrm_seattachmentsrel.crmid='".$crmid."' and aicrm_crmentity.deleted='0'
      ";
      $customer = $this->db->query($sql_customer);
	  $result = $customer->result(0);

	  return $result;
  	}
  	function get_signature_contractor($crmid=null){
	  $sql_inspector = "select aicrm_attachments.*,aicrm_crmentity.setype,inspection_type from aicrm_inspection
      inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid = aicrm_inspection.inspectionid
      inner join aicrm_attachments on aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
      inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid
      where aicrm_crmentity.setype='Image 998'  and aicrm_seattachmentsrel.crmid='".$crmid."' and aicrm_crmentity.deleted='0'
      ";
      $inspector = $this->db->query($sql_inspector);
	  $result = $inspector->result(0);
	  
	  return $result;

  	}

  	function check_inspection($branchid=null,$buildingid=null,$productid=null,$inspection_type=null){
  		$sql_inspector = "SELECT aicrm_inspection.*, aicrm_inspectioncf.*, aicrm_branch.branch_name, aicrm_building.building_name, aicrm_products.productname, concat(aicrm_users.first_name, ' ', aicrm_users.last_name) as user_assign, 
  			sum(case when aicrm_inspectiondefectlist.defectlist_status = 'None' Then 1 else 0 end ) none, 
  			sum(case when aicrm_inspectiondefectlist.defectlist_status = 'Pass' Then 1 else 0 end) pass, 
  			sum(case when aicrm_inspectiondefectlist.defectlist_status = 'Not Pass' Then 1 else 0 end) notpass, 
  			sum(case when aicrm_inspectiondefectlist.defectlist_status = 'N/A' Then 1 else 0 end) na
			FROM (aicrm_inspection)
			INNER JOIN aicrm_inspectioncf ON aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
			INNER JOIN aicrm_branch ON aicrm_branch.branchid=aicrm_inspection.branchid
			INNER JOIN aicrm_products ON aicrm_products.productid=aicrm_inspection.productid
			LEFT JOIN aicrm_building ON aicrm_building.buildingid=aicrm_inspection.buildingid 
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid
			INNER JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			LEFT JOIN aicrm_inspectiondefectlist ON aicrm_inspectiondefectlist.inspectionid = aicrm_inspection.inspectionid
			WHERE aicrm_inspection.branchid = '".$branchid."'
			AND aicrm_inspection.buildingid = '".$buildingid."'
			AND aicrm_inspection.productid = '".$productid."'
			AND aicrm_inspection.inspection_type = '".$inspection_type."'
			AND aicrm_crmentity.setype =  'Inspection'
			AND aicrm_crmentity.deleted =  '0'
			GROUP BY aicrm_inspection.inspectionid
			ORDER BY aicrm_inspection.inspectionid DESC";
			
      $inspector = $this->db->query($sql_inspector);
	  $result = $inspector->result(0);
	  
	  return $result;
  	}



  	function get_data_export($fields=array()){

  		//alert($fields); exit;

  		$sql_data = "SELECT 
		aicrm_inspection.*,
		aicrm_inspectioncf.*,
		branch.*, 
		building.*,
		products.*,
		products.*,
		inspectiondefectlist.*,
		concat(aicrm_users.first_name,' ',aicrm_users.last_name,' [',aicrm_users.user_name,']') as user_inspector
		FROM aicrm_inspection 
		INNER JOIN aicrm_inspectioncf on aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
		INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
		INNER JOIN aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
		INNER JOIN (
			select aicrm_branch.branchid,aicrm_branch.branch_name from aicrm_branch
			INNER JOIN aicrm_branchcf on aicrm_branchcf.branchid = aicrm_branch.branchid
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branch.branchid
			WHERE  aicrm_crmentity.deleted = 0
		) branch on branch.branchid = aicrm_inspection.branchid
		LEFT JOIN (
			select aicrm_building.buildingid, aicrm_building.branchid ,aicrm_building.building_name 
			from aicrm_building
			INNER JOIN aicrm_buildingcf on aicrm_buildingcf.buildingid = aicrm_building.buildingid
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_building.buildingid
			WHERE  aicrm_crmentity.deleted = 0
		) building on building.branchid = branch.branchid
		INNER JOIN (
			select aicrm_products.productid, aicrm_products.branchid ,aicrm_products.product_no ,aicrm_products.productname ,aicrm_products.house_no, aicrm_roomplan.roomplan_name
			from aicrm_products
			INNER JOIN aicrm_productcf on aicrm_productcf.productid = aicrm_products.productid
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
			LEFT JOIN aicrm_roomplan on aicrm_roomplan.roomplanid = aicrm_products.roomplanid
			WHERE  aicrm_crmentity.deleted = 0
		) products on products.productid = aicrm_inspection.productid
		INNER JOIN (
			select aicrm_inspectiondefectlist.* ,
			aicrm_crmentity.description, 
			aicrm_zone.zoneid,
			aicrm_zone.zone_name,
			aicrm_defect.defect_status
			from aicrm_inspectiondefectlist
		    INNER JOIN aicrm_inspectiondefectlistcf on aicrm_inspectiondefectlistcf.inspectiondefectlistid = aicrm_inspectiondefectlist.inspectiondefectlistid
		    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspectiondefectlist.inspectiondefectlistid
		    INNER JOIN aicrm_defect on aicrm_defect.defectid = aicrm_inspectiondefectlist.defectid
		    INNER JOIN aicrm_defectcf on aicrm_defectcf.defectid = aicrm_defectcf.defectid
		    INNER JOIN aicrm_zone on aicrm_zone.zoneid = aicrm_defect.zoneid
		    INNER JOIN aicrm_zonecf on aicrm_zonecf.zoneid = aicrm_zone.zoneid
		    where aicrm_crmentity.deleted = 0  
		) inspectiondefectlist on inspectiondefectlist.inspectionid = aicrm_inspection.inspectionid
		WHERE aicrm_crmentity.deleted = 0 ";

		if(isset($fields['branchid']) && $fields['branchid'] != ''){
			$sql_data .="  and aicrm_inspection.branchid in (".implode(',',$fields['branchid']).") ";
		}
		if(isset($fields['buildingid']) && $fields['buildingid'] != ''){
			$sql_data .="  and aicrm_inspection.buildingid in (".implode(',',$fields['buildingid']).") ";
		}
		if(isset($fields['productid']) && $fields['productid'] != ''){
			$sql_data .="  and aicrm_inspection.productid in (".implode(',',$fields['productid']).") ";
		}
		if(isset($fields['startdate']) && $fields['startdate'] != ''){
			$sql_data .="  and aicrm_inspection.inspection_date >= '".$fields['startdate']."' ";
		}
		if(isset($fields['enddate']) && $fields['enddate'] != ''){
			$sql_data .="  and aicrm_inspection.inspection_date <= '".$fields['enddate']."' ";
		}
		if(isset($fields['inspection_no']) && $fields['inspection_no'] != ''){
			$sql_data .="  and aicrm_inspection.inspection_no LIKE '%".$fields['inspection_no']."%' ";
		}
		if(isset($fields['inspection_status']) && $fields['inspection_status'] != ''){
			$sql_data .="  and aicrm_inspection.inspection_status = '".$fields['inspection_status']."' ";
		}
		
		$sql_data .=" group by inspectiondefectlist.inspectiondefectlistid";
	  //echo $sql_data; exit;
      $inspector_data = $this->db->query($sql_data);
	  $result = $inspector_data->result(0);
	  
	  return $result;
  	}
  	 
}

?>