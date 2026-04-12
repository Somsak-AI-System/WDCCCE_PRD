<?php
class indexlist_model extends CI_Model
{
  var $ci;

  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	$this->load->library("common");
	$this->load->library('crmentity');

    $this->_limit = "10";
  }


  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$optimize=array(),$a_params=array(),$where_user,$a_filter=array(),$check_admin=false)
  {

		$module = $a_params['module'];
		$userid = $a_params['userid'];
		$crmid = $a_params['crmid'];
    $orderby= "";
    $res_filter = array();

    if($module=="Case"){
      $module="HelpDesk";
    }elseif ($module=="Spare Part" || $module=="SparePart") {
      $module = "Sparepart";
    }elseif ($module=="Errors List" || $module=="ErrorsList" ) {
      $module = "Errorslist";
    }elseif ($module=="Spare Part List" || $module=="SparePartList") {
      $module = "Sparepartlist";
    }elseif ($module=="Sales Visit" || $module=="Events"){
      $module="Calendar";
    }elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder" || $module=="Project Order") {
      // $module = "Projectorder";
      $module = "Projects";
    }elseif($module=="Quotation"){
      $module="Quotes";
    }
    
    $limit="";
    if(!empty($a_limit['limit']) && $a_limit['offset']>=0 ){
      $limit = "limit ".$a_limit['limit']." offset ".$a_limit['offset']." ";
    }

  	try {

			if($module == "Calendar" || $module == "Sales Visit" || $module == "Schedule" || $module == "Events"){
				$module = "Events";
        array_push($res_filter, "status","assigned","objective");

        if($a_filter['all']==1 && $check_admin==true){
          $where_user = "aicrm_crmentity.smownerid != 0";
        }

        if($a_filter['my']==1){
          $where_user = "aicrm_crmentity.smownerid = '".$userid."'";
        }

        if($a_filter['Accounts']==1 && $a_filter['Leads']!=1){
          // echo 11;
          $where_user .= " and aicrm_account.accountname != '' ";
          array_push($res_filter, "Accounts");
        }elseif($a_filter['Accounts']!=1 && $a_filter['Leads']==1) {
            // echo 22;
            $where_user .= " and aicrm_leaddetails.leadname != '' ";
            array_push($res_filter, "Leads");
        }elseif($a_filter['Contacts']==1 && $a_filter['Accounts']!=1 && $a_filter['Leads']!=1){
            $where_user .= " and aicrm_contactdetails.contactid != '' ";
            array_push($res_filter, "Contacts");
        }else{
            array_push($res_filter, "Accounts", "Leads","Contacts");
        }

        $orderby = " CONCAT(aicrm_activity.date_start ,aicrm_activity.time_start ) ASC";

        if($a_filter['last_edit']==1){
          $orderby = " aicrm_crmentity.modifiedtime DESC ";
        }

        if($a_filter['last_create']==1){
          $orderby = " aicrm_crmentity.createdtime DESC ";
        }

        if($a_filter['event']==1){

          $select = "aicrm_activity.activityid,
          CASE
          WHEN (
            aicrm_users.user_name NOT LIKE ''
            ) THEN
            CONCAT('(',aicrm_users.user_name,') ',aicrm_activity.activitytype)
            ELSE
            CONCAT('(',aicrm_groups.groupname,') ',aicrm_activity.activitytype)
            END AS activitytype,
            aicrm_activity.date_start,
            aicrm_activity.due_date, aicrm_activity.time_start, aicrm_activity.time_end, aicrm_activity.sendnotification,
            aicrm_activity.eventstatus, aicrm_activity.visibility, aicrm_activity.visibility1,
            aicrm_account.accountname, aicrm_account.account_no, 
            concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as leadname,
  		      aicrm_leaddetails.lead_no,
            concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as contactname,
            aicrm_contactdetails.contact_no,
            case when leadid <> '' then concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname)
                 when aicrm_account.accountid <> '' then aicrm_account.accountname
                 else '' 
            end as 'recordname',
            case when leadid <> '' then aicrm_leaddetails.lead_no
  	           when aicrm_account.accountid <> '' then aicrm_account.account_no
                 else '' 
            end as 'record_no',
  	      case when leadid <> '' then 'Leads'
  	           when aicrm_account.accountid <> '' then 'Accounts'
                 else '' 
            end as 'parentmodule',
            aicrm_account.latlong as map,aicrm_account.latlong as location, aicrm_activity.activitytype as name,
            aicrm_activity.eventstatus as status,aicrm_users.user_name as assigned,aicrm_activity.activity_objective as objective,
          CASE
          WHEN (
            aicrm_account.accountname NOT LIKE ''
            ) THEN
            aicrm_account.accountname
            ELSE
            aicrm_leaddetails.leadname
            END AS  no,
            aicrm_activity.phone as phone, '' as navigate ,aicrm_users.user_name as user_name ,aicrm_activity.location as checkin_location , aicrm_activity.location_chkout as checkout_location
            ";
            $list=$this->crmentity->Get_Query($module); //aicrm_activitycf.location as checkin_location , aicrm_activity.location_chkout as checkout_location
            $sql = "select ".$select." ".$list." and ".$where_user." ";

             if($a_condition['date_start'] != '' && $a_condition['date_start'] != '0000-00-00' ){
              $sql .= " and aicrm_activity.date_start >= '".$a_condition['date_start']."' ";
             }
             if($a_condition['due_date'] != '' && $a_condition['due_date'] != '0000-00-00' ){
              $sql .= " and aicrm_activity.date_start <= '".$a_condition['due_date']."' ";
             }

            $sql .= "ORDER BY  ".$orderby." ,aicrm_users.user_name ASC   ".$limit."";
            //echo $sql;exit();
            $query = $this->db->query($sql);

          if(!$query){
            $a_return["status"] = false;
            $a_return["error"] = $this->db->_error_message();
            $a_return["result"] = "";
          }else{
            $a_result  = $query->result_array();
          }

        }else{

          $a_result=array();
        }

      }else{

    		$select=$this->crmentity->Get_QuerySelect($module);
    		$list=$this->crmentity->Get_Query($module);

        if($module=='Projects'){
          $username=$this->common->get_user_name($userid);
          $where_user .= " OR (aicrm_projects.related_sales_person LIKE '%".$username."%' )";
        }
        
    		$sql = "select ".$select." ".$list."  and ".$where_user." ORDER BY ".$a_order." DESC  ".$limit."";
        
        $query = $this->db->query($sql);

        if(!$query){
          $a_return["status"] = false;
          $a_return["error"] = $this->db->_error_message();
          $a_return["result"] = "";
        }else{
          $a_result  = $query->result_array();
        }
		  }

  		foreach($a_result as $key => $val){
           
  			foreach($val as $k => $v){
  				if($v==null){
  					$v="";
  					$val[$k] = $v;
  					$val_change = $val[$k];
  				}
  				$val[$k] = $v;
  			}

        if($module=="Accounts" || $module=="Leads"){
          $tag = array();
          $tag = $this->crmentity->Get_Tag($val['id']);
          $val['tag_list'] = !empty($tag) ? $tag : array() ;
        }
   
        if($val['dateAt']!=""){
          $val['dateAt'] = date("d/m/Y", strtotime($val['dateAt']));
        }

  			$a_result[$key] = $val;
  		}

      if (!empty($a_result)) {
        $a_return["status"] = true;
        $a_return["error"] =  "";
        $total = $this->get_total($module,$where_user);
        $a_return["total"] = $total['total'];
        $a_return["result"] = $a_result;
      }else{

        if($module == "Events" && $a_filter['event']!=1){
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }
      }

      if($module == "Events" && $a_filter['birthdate']==1){
        $a_return["result_birthdate"] = $this->get_birthdate($a_condition,$userid,$check_admin);
      }

      if($module == "Events" && !empty($res_filter)){
        $a_return["data_filter"] = $res_filter;
      }

		}catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
    
    return $a_return;
	}

  function get_total($module,$where_user="")
  {
    try {
      $where="";
      $select=$this->crmentity->Get_QuerySelect($module);

      $select = "count(DISTINCT aicrm_crmentity.crmid) as total";
      $list=$this->crmentity->Get_Query($module);

      if(!empty($where_user)){
          $where = "and ".$where_user ;
      }

      $data_All = "select ".$select." ".$list."  ".$where."";
      $query = $this->db->query($data_All);
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;


        if (!empty($a_result)) {
          //$a_return["total"]= $total_All;
          $a_return["total"]= $a_result[0]['total'];
        }else{
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



  public function get_birthdate($a_condition,$userid,$check_admin=false){
    // alert($a_condition);exit;
    $result = array();
    $this->load->library('Lib_user_permission');
    $data_privileges =  $this->lib_user_permission->Get_user_privileges($userid);
    $data_sharingprivileges =  $this->lib_user_permission->Get_sharing_privileges($userid);
        
    $groupid =  $data_privileges['current_user_groups'];
    $parent_role =  $data_privileges['current_user_parent_role_seq'];
    $profileGlobalPermission = $data_privileges['profileGlobalPermission'];
    $defaultOrgSharingPermission = $data_sharingprivileges['defaultOrgSharingPermission'];

    $sql = "Select new_table.* ,aicrm_crmentity.setype as module from (
        select aicrm_account.accountname as name ,aicrm_account.account_no as no, aicrm_account.accountid as id ,aicrm_account.mobile as phone ,aicrm_account.birthdate, ifnull(aicrm_account.latlong,'') as latlong from  aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
        INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
        INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
        INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
        WHERE aicrm_crmentity.deleted = 0 ";
    
    if($check_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
    {
      $sql .= " and (
              aicrm_crmentity.smownerid in(".$userid.")
              or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$parent_role."%') 
              or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
              or (";

          if(sizeof($groupid) > 0)
          {
                $sql .= " aicrm_groups.groupid in (". implode(",", $groupid) .") or ";
          }
           $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
    }

    $sql .= "UNION  ALL
        
        select concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as name ,aicrm_leaddetails.lead_no as no,aicrm_leaddetails.leadid as id,aicrm_leaddetails.mobile as phone, aicrm_leaddetails.birthdate, '' as latlong 
        from aicrm_leaddetails
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
        INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
        INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
        INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
        WHERE aicrm_crmentity.deleted = 0 ";

    if($check_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
    {
      $sql .= " and (
              aicrm_crmentity.smownerid in(".$userid.")
              or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$parent_role."%') 
              or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
              or (";

          if(sizeof($groupid) > 0)
          {
                $sql .= " aicrm_groups.groupid in (". implode(",", $groupid) .") or ";
          }
           $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
    }

    $sql .= "UNION  ALL
        select concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as name ,aicrm_contactdetails.contact_no as no,aicrm_contactdetails.contactid as id,aicrm_contactdetails.mobile as phone, aicrm_contactdetails.birthdate, '' as latlong 
        from aicrm_contactdetails
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
        INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
        INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
        INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
        WHERE aicrm_crmentity.deleted = 0 ";

    if($check_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
    {
      $sql .= " and (
              aicrm_crmentity.smownerid in(".$userid.")
              or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$parent_role."%') 
              or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
              or (";

          if(sizeof($groupid) > 0)
          {
                $sql .= " aicrm_groups.groupid in (". implode(",", $groupid) .") or ";
          }
           $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
    }

    $sql .= " ) as new_table
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = new_table.id
      where MONTH(new_table.birthdate) = MONTH('".$a_condition['date_start']."') 
      ORDER BY DAY(new_table.birthdate) ASC";

    
      $query = $this->db->query($sql);

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
        $count = "";
        $bday1 = "";
        $bday2 = "";
        $a_data = $a_result;
        $date = explode('-', $a_condition['date_start']);
        $year = $date[0];
        $i = 0;
        $u = 0;
        foreach($a_result as $key => $val){
        
          $date = explode('-', $val['birthdate']);
          $bday1 = $date[2];
          
          foreach ($a_data as $k => $v) {
            $date2 = explode('-', $v['birthdate']);
            $bday2 = $date2[2];
            $v['image'] = $this->get_birthdate_image($v['id'],$v['module']);;
            //alert($v['image']);
              if($bday2==$bday1){
                //$key_date = $year."-".$date2[1]."-".$date2[2];
                $result[$i]['total'] += 1;
                $result[$i]['date'] = $year."-".$date2[1]."-".$date2[2];
                $result[$i]['items'][] =$v;
                $u = $i;
                unset($a_data[$k]);
              }
             
          }

          if($bday2 != $bday1 && $u == $i){
            $i++;
          }

        }

      }
      //alert($result); exit;
      return $result;
  
  }

    public function get_birthdate_image($crmid,$module)
    { 
      if($module == "Accounts"){
        $a_conditionin["key"] = "aicrm_account.accountid";
        $a_conditionin["accountid"] = $crmid;
        $module = "Accounts";
      }else if($module == "Leads"){
        $a_conditionin["key"] = "aicrm_leaddetails.leadid";
        $a_conditionin["leadid"] = $crmid;
        $module = "Leads";
      }else if($module == "Contacts"){
        $a_conditionin["key"] = "aicrm_contactdetails.contactid";
        $a_conditionin["contactid"] = $crmid;
        $module = "Contacts";
      }
      
      $response_data = $this->ci->common->get_a_image($a_conditionin, $module);
      $image = '';
      
      if(isset($response_data[$crmid])){
          //alert($response_data[$crmid]['image']);
          $image = $response_data[$crmid]['image'];
          foreach ($image as $items){
            //alert($items);
            $tmp = $items;
              /*foreach ($items as $value){
                  $tmp = $value;
              }*/
          }
      }
      
      $returndata = $tmp;
      //alert($returndata);
      return $returndata;
    }

}
