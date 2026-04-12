<?php
class Indexselect_model extends CI_Model
{
  var $ci;

  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }

    function get_total($search_module,$where_user="")
    {
	  	try {

			$select=$this->crmentity->Get_QuerySelect_Index($search_module);
			$list=$this->crmentity->Get_Query($search_module);

			if(!empty($where_user)){
				$where = "and ".$where_user ;
			}else {
				$where="";
			}

			//$data_All = "select DISTINCT ".$select." ".$list."  ".$where.""; 
			//echo $data_all; exit;
			$data_All = "select count(DISTINCT aicrm_crmentity.crmid) as total ".$list."  ".$where."";
			//echo $data_All; exit;
			$query = $this->db->query($data_All);

			if(!$query){
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			}else{
				$a_result  = $query->result_array() ;
		
				//$total_All = count($a_result);

				if (!empty($a_result)) {
					// $a_return["status"] = true;
					// $a_return["error"] =  "";
					//$a_return["total"]= $total_All;
					$a_return["total"]= $a_result[0]['total'];
				}else{
					// $a_return["status"] = false;
					// $a_return["error"] =  "No Data";
					$a_return["total"] = 0;
				}
			}
		}catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}

		return $a_return;
	}


	function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$module,$search_module,$section,$relate_field,$userid="")
	{

		if($search_module=="Case"){
			$search_module = "HelpDesk";
		}elseif ($search_module=="Projectorders" || $search_module=="Project Orders" || $search_module=="Projectorder") {
			$search_module = "Projectorder";
		}

		$this->load->library('crmentity');

		try {

			$like = @$a_condition['like'];
			$id = @$a_condition['crmid'];
	        if($module=="Users"){
	        $id = $a_condition['userid'];
	        }

			$this->load->library('Lib_user_permission');

			$sql_tabid = "select tabid from aicrm_tab where name='".$search_module."' ";
			$query_tab = $this->db->query($sql_tabid);
			if(!empty($query_tab)){
				$tabid = $query_tab->result_array();
				$tabid = $tabid[0]['tabid'];
			}else {
				$tabid = "";
			}
			$data_sharing =  $this->lib_user_permission->Get_sharing_privileges($userid,$search_module);
			$sharing_module = @$data_sharing['SharingPermission'][$tabid];
	  
			// alert($data_sharing);/
			if($sharing_module=="0"){
			  $sharing_public = true;
			}elseif ($sharing_module=="1") {
			  $sharing_public = true;
			}elseif ($sharing_module=="2") {
			  $sharing_public = true;
			}elseif ($sharing_module=="4") {
			  $sharing_public = false;
	  
			}

			$data_privileges =  $this->lib_user_permission->Get_user_privileges($userid);
			$check_admin =  $data_privileges['is_admin'];
			$groupid =  $data_privileges['current_user_groups'];
			$parent_role =  $data_privileges['current_user_parent_role_seq'];
			if(!empty($groupid)){
			$groupid = implode(",",$groupid);
			}
			if (isset($userid) && $userid!="") {
			$where_user = " (aicrm_crmentity.smownerid IN (".$userid.")";

			if(!empty($parent_role) && $sharing_public==false ){
				$where_user .= "  OR aicrm_crmentity.smownerid IN (SELECT aicrm_user2role.userid
				FROM  aicrm_user2role
				INNER JOIN  aicrm_users ON aicrm_users.id = aicrm_user2role.userid
				INNER JOIN  aicrm_role ON aicrm_role.roleid = aicrm_user2role.roleid
				WHERE aicrm_role.parentrole LIKE '".$parent_role."::%')  ";
			}


			if(!empty($tabid)){
				$where_user .= " OR aicrm_crmentity.smownerid IN (SELECT shareduserid
				FROM  aicrm_tmp_read_user_sharing_per
				WHERE userid = '".$userid."' AND tabid = '".$tabid."') ";
			}


			if(!empty($groupid) &&  $sharing_public==false  ){
				// if(!empty($groupid)){
				  $where_user .= "OR (aicrm_groups.groupid IN (".$groupid.")
				  OR aicrm_groups.groupid IN (SELECT
				  aicrm_tmp_read_group_sharing_per.sharedgroupid
				  FROM
				  aicrm_tmp_read_group_sharing_per
				  WHERE
				  userid = '".$userid."' AND tabid = '".$tabid."')) ";
				}

			$where_user .= " )";

			}

			if($sharing_public==true){
				$where_user = "aicrm_crmentity.smownerid != 0";
			  }

			if($check_admin==true){
			$where_user="aicrm_crmentity.smownerid != 0";
			}

			$limit="";
			if(!empty($a_limit['limit']) && $a_limit['offset']>=0 ){
			$limit = "limit ".$a_limit['limit']." offset ".$a_limit['offset']." ";
			}

			list($total,$data,$count_total) = $this->get_query($module,$search_module,$like,$id,$section,$relate_field,$where_user,$limit);
			//echo $total; exit;
			
			$query_total = $this->db->query($count_total);
			
			if(!empty($query_total)){
				$a_total  = $query_total->result_array() ;
				$result_total  = $a_total[0]['total'] ;
			}else {
				$result_total  = 0;
			}
			//$result_total = 0;
			$query = $this->db->query($data);
			//alert($query); exit;

			if(!$query){	
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			}else{
				$a_result="";
				$a_result  = $query->result_array() ;
				foreach($a_result as $key => $val){

					$crmid = $a_result[$key]['id'];
	          		$relatedfiled = $this->relate_field($module,$search_module,$crmid);
	          	foreach ($relatedfiled as $ke => $va) {
	            $columname = $va['columnname'];
	            $data_value = $va['value'];
				$a_result[$key][$columname] = $data_value ;
				
	          }
	          $a_result[$key] = $a_result[$key];
			  
			  if($search_module=="Contacts"){
	            $a_result[$key]['contactname'] = $a_result[$key]['name'];
	          }elseif ($search_module=="Users") {
	            $a_result[$key]['username'] = $a_result[$key]['name'];
	          }

			  if($module=="Job" && ($search_module=="Accounts" || $search_module=="Contacts" || $search_module=="Case" || $search_module=="HelpDesk" || $search_module=="Project"|| $search_module=="Serial")){
	            $a_result[$key]['job_account_address'] = @$a_result[$key]['addressline1']." ".@$a_result[$key]['addressline2']." ".@$a_result[$key]['accountvillage']
	            ." ".@$a_result[$key]['accountalley']." ".@$a_result[$key]['accountroad']." ".@$a_result[$key]['subdistrict']." ".@$a_result[$key]['district']
	            ." ".@$a_result[$key]['province']." ".@$a_result[$key]['postalcode'] ;

	          }

			  if($module=="Job" && ($search_module=="Accounts" || $search_module=="Contacts" || $search_module=="Case" || $search_module=="HelpDesk" )){
	            $a_result[$key]['contactname'] = @$a_result[$key]['firstname']." ".@$a_result[$key]['lastname'];
	          }
				foreach($a_result[$key] as $k => $v){
					if($v==null){
						$v="";
						$val[$k] = $v;
						$val_change = $val[$k];
					}
					$val[$k] = $v;
				}
					$a_result[$key] = $val;
				}

	  			// $a_total = $this->get_total($search_module,$where_user) ;
	  			$a_data["offset"] = $a_limit["offset"];
	  			$a_data["limit"] = $a_limit["limit"];
				$a_data["total"] = $result_total;
				// $a_data["total"] = $a_total["total"];

				  $a_data["data"] = $a_result;


	  			if (!empty($a_result)) {
	  				$a_return["status"] = true;
	  				$a_return["error"] =  "";
	  				$a_return["result"] = $a_data;
	  			}else{
	  				$a_return["status"] = false;
	  				$a_return["error"] =  "No Data";
	  				$a_return["result"] = "";
	  			}
	  		}
	  		}catch (Exception $e) {
	  			$a_return["status"] = false;
	  			$a_return["error"] =  $e->getMessage();
	  			$a_return["result"] = "";
			  }
			//alert($a_return); exit;
	  		return $a_return;
	}

	
	function relate_field($module,$search_module,$crmid){

		$field_data = [];
	
		if($module=="Calendar" || $module=="Sales Visit" ||$module=="Sale Visit" ||$module=="Events"){
		  $module = 'Events';
		}elseif ($search_module=="Calendar" || $search_module=="Sales Visit" ||$search_module=="Sale Visit" ||$search_module=="Events") {
		  $search_module = 'Events';
		}
	
		$sql_tabid = "select tabid from aicrm_tab where name='".$module."' ";
		$query_tab = $this->db->query($sql_tabid);
		$tabid = $query_tab->result_array();
		$tabid = $tabid[0]['tabid'];
	
		$sql_searchtabid = "select tabid from aicrm_tab where name='".$search_module."' ";
		$query_searchtab = $this->db->query($sql_searchtabid);
		$search_tabid = $query_searchtab->result_array();
		$search_tabid = $search_tabid[0]['tabid'];
	
		$select_query = "
		SELECT  DISTINCT aicrm_relatedlists_field_mapping.fieldid  , aicrm_field.columnname as columnname ,aicrm_field.tablename from  aicrm_relatedlists_field_mapping
		LEFT JOIN   aicrm_field on aicrm_field.fieldid = aicrm_relatedlists_field_mapping.fieldid
		WHERE aicrm_relatedlists_field_mapping.destination_tabid='".$tabid."' and  aicrm_relatedlists_field_mapping.tabid='".$search_tabid."'
		and aicrm_relatedlists_field_mapping.`status`='active' and aicrm_relatedlists_field_mapping.fieldid !='0'
		UNION all
		select 0 as 'fieldid' , entityidfield as columnname , tablename from aicrm_entityname where tabid = '".$search_tabid."'
		UNION all
		SELECT aicrm_field.fieldid,aicrm_field_mapping_detail.columnname,aicrm_field.tablename
		from aicrm_field_mapping_detail
		LEFT JOIN aicrm_relatedlists_field_mapping on aicrm_relatedlists_field_mapping.id = aicrm_field_mapping_detail.mapping_id
		LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_field_mapping_detail.fieldid
		LEFT JOIN (
		  SELECT id as mapping_id from aicrm_relatedlists_field_mapping
		  WHERE aicrm_relatedlists_field_mapping.destination_tabid='".$tabid."' and  aicrm_relatedlists_field_mapping.tabid='".$search_tabid."'
		  and aicrm_relatedlists_field_mapping.`status`='active' and aicrm_relatedlists_field_mapping.fieldid ='0'
		  ) as map_tb on map_tb.mapping_id = aicrm_field_mapping_detail.mapping_id
		  WHERE aicrm_field_mapping_detail.mapping_id = map_tb.mapping_id and aicrm_field_mapping_detail.status = 'active';
		  ";
		  $query = $this->db->query($select_query);
		  $columname_releted = $query->result_array();
		  // alert($columname_releted);exit;
	
		  foreach ($columname_releted as $key => $value) {

			$columname= $columname_releted[$key]['columnname'];
	
			if(!empty($columname_releted[$key]['tablename'])){
			  $tab_name = $columname_releted[$key]['tablename'];
	
			  $list_query=$this->crmentity->Get_Query($search_module);
	
			}else {
			  $list_query=$this->crmentity->Get_Query($search_module);
			}
	
			$data = "select  ".$tab_name.".".$columname."
			".$list_query."  and aicrm_crmentity.crmid='".$crmid."' ";

			$query_columnname = $this->db->query($data);
	
			$data_columnname = $query_columnname->result_array();
			$data_columnname= $data_columnname[0][$columname];
			if(!empty($data_columnname)){
			  $columname_releted[$key]['value'] = $data_columnname;
	
			}else {
			  $columname_releted[$key]['value']="";
			}
			$field_data[] = $columname_releted[$key];
	
		  }
	
		  $sql_replace_field ="
		  SELECT DISTINCT aicrm_relatedlists_field_mapping.fieldid ,aicrm_field.columnname,
		  aicrm_relatedlists_field_mapping.destination_fieldid
		  from aicrm_relatedlists_field_mapping
		  LEFT JOIN   aicrm_field on aicrm_field.fieldid = aicrm_relatedlists_field_mapping.destination_fieldid
		  LEFT JOIN
		  (
			SELECT  DISTINCT aicrm_relatedlists_field_mapping.fieldid  , aicrm_field.columnname as columnname ,aicrm_field.tablename from  aicrm_relatedlists_field_mapping
			LEFT JOIN   aicrm_field on aicrm_field.fieldid = aicrm_relatedlists_field_mapping.fieldid
			WHERE aicrm_relatedlists_field_mapping.destination_tabid='".$tabid."' and  aicrm_relatedlists_field_mapping.tabid='".$search_tabid."'
			and aicrm_relatedlists_field_mapping.`status`='active' and aicrm_relatedlists_field_mapping.fieldid !='0'
			UNION all
			select 0 as 'fieldid' , entityidfield as columnname , tablename from aicrm_entityname where tabid = '".$search_tabid."'
			) as destination_tb on  destination_tb.fieldid = aicrm_relatedlists_field_mapping.fieldid
			WHERE aicrm_relatedlists_field_mapping.destination_tabid='".$tabid."' and  aicrm_relatedlists_field_mapping.tabid='".$search_tabid."'
			and aicrm_relatedlists_field_mapping.`status`='active'
			";

			$query_fieldid = $this->db->query($sql_replace_field);
			$fieldid_data = $query_fieldid->result_array();
			foreach ($fieldid_data as $key => $value) {
			  foreach ($field_data as $k => $v) {
				if($value['fieldid'] == $field_data[$k]['fieldid']){
				  $field_data[$k]['columnname'] = $value['columnname'];
				}
			  }
			}
	
			return $field_data;
	
	}

		
	function get_query($module="",$search_module,$like,$id,$section,$relate_field=array(),$where_user,$limit=""){

		$where_user_role="";
		if($where_user!=""){
			$where_user_role = "and ".$where_user ;
		}

		if(empty($limit)){
			$limit = "";
		}
		
		$data ="";
		$query_total = "";

	    $accountid="";
	    $contactid="";
	    $productid="";
	    $serialid="";
	    $jobid="";
	    $sparepartid="";
	    $errorsid="";
	    $ticketid="";
	    $projectsid="";
	    foreach ($relate_field as $key => $value) {

			if(isset($relate_field['accountid']) && $relate_field['accountid'] !=""){
	        	$accountid = $relate_field['accountid'];
	        }
	        if(isset($relate_field['contactid']) && $relate_field['contactid'] !="") {
	            $contactid = $relate_field['contactid'];
	        }
	        if (isset($relate_field['product_id']) && $relate_field['product_id'] !="") {
	            $productid = $relate_field['product_id'];
	        }
			if (isset($relate_field['serialid']) && $relate_field['serialid']!="") {
	            $serialid = $relate_field['serialid'];

	        }
	        if (isset($relate_field['jobid']) && $relate_field['jobid'] !="") {
	            $jobid = $relate_field['jobid'];
	        }
	        if (isset($relate_field['sparepartid']) && $relate_field['sparepartid'] !="") {
	            $sparepartid = $relate_field['sparepartid'];
	        }
	        if (isset($relate_field['errorsid']) && $relate_field['errorsid'] !="") {
	            $errorsid = $relate_field['errorsid'];
	        }
	        if (isset($relate_field['ticketid']) && $relate_field['ticketid'] !="") {
	            $ticketid = $relate_field['ticketid'];
	        }
	        if (isset($relate_field['projectsid']) && $relate_field['projectsid']!="") {
	            $projectsid = $relate_field['projectsid'];
	        }

	        if (isset($relate_field['projectid']) && $relate_field['projectid'] !="") {
	            $projectid = $relate_field['projectid'];
	        }

			if (isset($relate_field['dealid']) && $relate_field['dealid']!="") {
				$dealid = $relate_field['dealid'];
			}
			if (isset($relate_field['questionnaireid']) && $relate_field['questionnaireid']!="") {
				$dealid = $relate_field['questionnaireid'];
			}
			if (isset($relate_field['activityid']) && $relate_field['activityid']!="") {
				$activityid = $relate_field['activityid'];
			}

	    }

     if($search_module=="Folder"){

        }else{
          $select = $this->crmentity->Get_QuerySelect_Index($search_module);
          $list_query=$this->crmentity->Get_Query($search_module);
        }

		switch($search_module){
			Case "Accounts":
				if($like!=""){
					$like ="AND `aicrm_account`.`accountname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_account`.`accountid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Accounts' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_account`.`accountname` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Accounts' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_account`.`accountname` asc ";

			break;

			Case "Products":
				if($like!=""){
					$like ="AND `aicrm_products`.`productname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_products`.`productid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Products' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_products`.`productname` asc ";
	  			
	  			$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Products' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_products`.`productname` asc ";

			break;
			
			Case "Projects":
				if($like!=""){
				  $like ="AND `aicrm_projects`.`projects_name` like '".$like."'";
				  $id="";
				}else if($id!=""){
				  $id = "AND  `aicrm_projects`.`projectsid`= '".$id."'";
				  $like ="";
				}else{
				  $like ="";
				  $id="";
				}
	  
				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Products' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_projects`.`projects_name` asc ";
	  			
	  			$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Products' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_projects`.`projects_name` asc ";

			break;

			Case "Serial":
				if($like!=""){
					$like ="AND `aicrm_serial`.`serial_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_serial`.`serialid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

		        if($accountid!=""){
		          $accountid = "and aicrm_serial.accountid =".$accountid." ";
		        }
		        if($productid!=""){
		          $productid = "and aicrm_serial.product_id =".$productid." ";
		        }

				$query = "
					SELECT DISTINCT ".$select."
					".$list_query." AND `aicrm_crmentity`.`setype` =  'Serial' ".$like." ".$id." ".$accountid." ".$productid."  ".$where_user_role."
					ORDER BY `aicrm_serial`.`serial_name` DESC ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Serial' ".$like." ".$id." ".$accountid." ".$productid."  ".$where_user_role."
					ORDER BY `aicrm_serial`.`serial_name` DESC ";

			break;
			Case "Contacts":
				if($like!=""){
					$like ="AND `aicrm_contactdetails`.`firstname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_contactdetails`.`contactid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

		        if($accountid!=""){
		          $accountid = "and aicrm_contactdetails.accountid =".$accountid." ";
		        }

		        $query = "
		          SELECT DISTINCT ".$select."
		          ".$list_query." AND `aicrm_crmentity`.`setype` =  'Contacts' ".$like." ".$id." ".$accountid."  ".$where_user_role."
		          ORDER BY `aicrm_contactdetails`.`firstname` asc ";

		        $query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Contacts' ".$like." ".$id." ".$accountid."  ".$where_user_role."
		          ORDER BY `aicrm_contactdetails`.`firstname` asc ";

			break;

			Case "Job":
				if($like!=""){
					$like ="AND `aicrm_jobs`.`job_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_jobs`.`jobid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}
		        if($accountid!=""){
		          $accountid = "and aicrm_jobs.accountid =".$accountid." ";
		        }
		        if($productid!=""){
		          $productid = "and aicrm_jobs.product_id =".$productid." ";
		        }
		        if($serialid!=""){
		          $serialid = "and aicrm_jobs.serialid =".$serialid." ";
		        }
		        if($ticketid!=""){
		          $ticketid = "and aicrm_troubletickets.ticketid =".$ticketid." ";
		        }
				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Job' ".$like." ".$id." ".$accountid." ".$productid." ".$serialid."  ".$ticketid."  ".$where_user_role."
				ORDER BY `aicrm_jobs`.`job_name` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Job' ".$like." ".$id." ".$accountid." ".$productid." ".$serialid."  ".$ticketid."  ".$where_user_role."
				ORDER BY `aicrm_jobs`.`job_name` asc ";

			break;
			Case "Sparepart":
				if($like!=""){
					$like ="AND `aicrm_sparepart`.`sparepart_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_sparepart`.`sparepartid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Sparepart' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_sparepart`.`sparepart_name` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Sparepart' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_sparepart`.`sparepart_name` asc ";

			break;
			Case "Errors":
				if($like!=""){
					$like ="AND `aicrm_errors`.`errors_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_errors`.`errorsid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Errors' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_errors`.`errors_name` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Errors' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_errors`.`errors_name` asc ";

			break;
	      	Case "HelpDesk":
		        if($like!=""){
		          $like ="AND `aicrm_troubletickets`.`title` like '".$like."'";
		          $id="";
		        }else if($id!=""){
		          $id = "AND  `aicrm_troubletickets`.`ticketid`= '".$id."'";
		          $like ="";
		        }else{
		          $like ="";
		          $id="";
		        }

		        if($productid!=""){
		          $productid = "and aicrm_troubletickets.product_id =".$productid." ";
		        }

		        if($accountid!=""){
		          $accountid = "and aicrm_troubletickets.accountid =".$accountid." ";
		        }

		        if($contactid!=""){
		          $contactid = "and aicrm_troubletickets.contactid =".$contactid." ";
		        }

				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'HelpDesk' ".$like." ".$id."  ".$productid."  ".$accountid." ".$contactid."  ".$where_user_role."
				ORDER BY `aicrm_troubletickets`.`ticketid` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'HelpDesk' ".$like." ".$id."  ".$productid."  ".$accountid." ".$contactid."  ".$where_user_role."
				ORDER BY `aicrm_troubletickets`.`ticketid` asc ";

      		break;
			Case "Projects":
				if($like!=""){
					$like ="AND `aicrm_projects`.`projects_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_projects`.`projectsid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

		        if($accountid!=""){
		          $accountid = "and aicrm_projects.accountid =".$accountid." ";
		        }
		        if($contactid!=""){
		          $contactid = "and aicrm_projects.contactid =".$contactid." ";
		        }

				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Projects' ".$like." ".$id."  ".$accountid." ".$contactid."  ".$where_user_role."
				ORDER BY `aicrm_projects`.`projects_name` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Projects' ".$like." ".$id."  ".$accountid." ".$contactid."  ".$where_user_role."
				ORDER BY `aicrm_projects`.`projects_name` asc ";

			break;
			Case "Deal":
				if($like!=""){
					$like ="AND `aicrm_deal`.`deal_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_deal`.`dealid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Deal' ".$like." ".$id."   ".$where_user_role."
				ORDER BY `aicrm_deal`.`deal_name` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Deal' ".$like." ".$id."   ".$where_user_role."
				ORDER BY `aicrm_deal`.`deal_name` asc ";

			break;

      		Case "Campaigns":
				if($like!=""){
					$like ="AND `aicrm_campaign`.`campaignname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_campaign`.`campaignid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Campaigns' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_campaign`.`campaignname` asc";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Campaigns' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_campaign`.`campaignname` asc";
			break;

			Case "Questionnaire":
				if($like!=""){
					$like ="AND `aicrm_questionnaire`.`questionnaire_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_questionnaire`.`questionnaireid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Questionnaire' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_questionnaire`.`questionnaire_name` asc";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Questionnaire' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_questionnaire`.`questionnaire_name` asc";

			break;

			Case "Calendar":
				if($like!=""){
					$like ="AND `aicrm_activity`.`activitytype` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_activity`.`activityid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Calendar' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_activity`.`activitytype` asc";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Calendar' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_activity`.`activitytype` asc";

			break;

			Case "Promotion":
				if($like!=""){
					$like ="AND `aicrm_promotion`.`promotion_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  ` aicrm_promotion`.`promotionid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

				$query = "
				SELECT DISTINCT ".$select."
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Promotion' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_promotion`.`promotion_name` asc";

				$query_total = "
				SELECT count(DISTINCT aicrm_crmentity.crmid) as total
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Promotion' ".$like." ".$id."  ".$where_user_role."
				ORDER BY `aicrm_promotion`.`promotion_name` asc";

			break;

      		Case "Folder":
				if($like!=""){
					$like ="where `aicrm_attachmentsfolder`.`foldername` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        		$query = "
				SELECT DISTINCT `aicrm_attachmentsfolder`.`folderid` as id, `aicrm_attachmentsfolder`.`foldername` as name, `aicrm_attachmentsfolder`.`description` ,`aicrm_attachmentsfolder`.*
        		from `aicrm_attachmentsfolder`
         		".$like." ".$id."
				ORDER BY `aicrm_attachmentsfolder`.`foldername` asc ";

				$query_total = "
				SELECT count(DISTINCT aicrm_attachmentsfolder.folderid) as total
				from `aicrm_attachmentsfolder`
         		".$like." ".$id."
				ORDER BY `aicrm_attachmentsfolder`.`foldername` asc ";

			break;

	      	Case "Users":
		      if($like!=""){
		        $like ="AND `aicrm_users`.`user_name` like '".$like."'";
		        $id="";
		      }else if($id!=""){
		        $id = "AND  `aicrm_users`.`id`= '".$id."'";
		        $like ="";
		      }else{
		        $like ="";
		        $id="";
		      }

		      $query = "
		        select DISTINCT id as id
		          ,user_name
		          , first_name , last_name
		          , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'name'
		          , IFNULL(area,'') as area
		          , position as no
		             , case when section	= '--None--' then '' else section end as section
		        from aicrm_users
		        where
		        status='Active'
		        and section = '".$section."'
		         ".$like." ".$id."
				order by user_name asc";

				$query_total = "
				SELECT count(id) as total
				from aicrm_users
		        where
		        status='Active'
		        and section = '".$section."'
		         ".$like." ".$id."
				order by user_name asc";
				
	      	break;

		 default:
		}

		if(!empty($limit)){
			$query_limit = $query." ".$limit;
		  }else {
			$query_limit = $query;
		  }
  		
  		/*echo $query;
  		echo $query_limit; exit;*/
		return array($query,$query_limit,$query_total);	

	}

}
