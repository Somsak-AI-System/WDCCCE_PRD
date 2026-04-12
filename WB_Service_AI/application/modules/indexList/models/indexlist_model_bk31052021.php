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


  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$optimize=array(),$a_params=array(),$where_user)
  {

		$module = $a_params['module'];
		$userid = $a_params['userid'];
		$crmid = $a_params['crmid'];

    if($module=="Case"){
    $module="HelpDesk";
    }elseif ($module=="Spare Part" || $module=="SparePart") {
      $module = "Sparepart";
    }elseif ($module=="Errors List" || $module=="ErrorsList" ) {
      $module = "Errorslist";
    }elseif ($module=="Spare Part List" || $module=="SparePartList") {
      $module = "Sparepartlist";
    }

  	try {

			if($module == "Calendar" || $module == "Sales Visit" || $module == "Schedule" || $module == "Events"){
				$module = "Events";

        $select = "aicrm_activity.activityid,CONCAT('(',aicrm_users.user_name,') ',aicrm_activity.activitytype) as activitytype, aicrm_activity.date_start,
        aicrm_activity.due_date,aicrm_activity.time_start,aicrm_activity.time_end,aicrm_activity.sendnotification,
        aicrm_activity.eventstatus,aicrm_activity.visibility,aicrm_activity.visibility1,
        aicrm_account.accountname,aicrm_activitycf.location,aicrm_activity.activitytype as name,aicrm_account.accountname as no";
				$list=$this->crmentity->Get_Query($module);
				$sql = "select ".$select." ".$list." and ".$where_user." ORDER BY ".$a_order." DESC";
				$query = $this->db->query($sql);
			}else{

			 $select=$this->crmentity->Get_QuerySelect($module);
			 $list=$this->crmentity->Get_Query($module);
        // $sql = "select ".$select." ".$list." and aicrm_users.id =".$userid." ORDER BY ".$a_order." DESC";
			 $sql = "select ".$select." ".$list."  and ".$where_user." ORDER BY ".$a_order." DESC";
       //echo $sql; exit;
			 $query = $this->db->query($sql);
			}

      //alert($query);exit;

    	if(!$query){
				$a_return["status"] = false;
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			}else{
				$a_result  = $query->result_array();
      //   alert($a_result);exit;

			foreach($a_result as $key => $val){
					foreach($val as $k => $v){
					if($v==null){
						$v="";
						$val[$k] = $v;
						$val_change = $val[$k];
					}
					$val[$k] = $v;
				}
				$a_result[$key] = $val;
				}

		  if (!empty($a_result)) {
				$a_return["status"] = true;
				$a_return["error"] =  "";
				$a_return["total"] = count($a_result);
				$a_return["result"] = $a_result;
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
		return $a_return;
	}


  // public function user_checkpermission($module,$userid){
  //
  //   if($module!=null){
  //     $sql_tab="select tabid from aicrm_tab WHERE name='".$module."' and presence=0";
  //     $query = $this->db->query($sql_tab);
  //     $a_result  = $query->result_array() ;
  //     $tabid = $a_result[0]['tabid'];
  //
  //     if($tabid!=null){
  //       $sql_user = "Select new_table.userid,aicrm_profile2standardpermissions.* from (
  // 				select aicrm_profile2tab.profileid as profileid ,aicrm_profile2tab.tabid as tabid,aicrm_users.id as userid FROM aicrm_users
  // 				INNER JOIN aicrm_user2role ON aicrm_user2role.userid =  aicrm_users.id
  // 				LEFT JOIN aicrm_role on  aicrm_role.roleid = aicrm_user2role.roleid
  // 				LEFT JOIN aicrm_role2profile on  aicrm_role2profile.roleid = aicrm_role.roleid
  //  			LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
  // 				WHERE aicrm_users.id = '".$userid."'
  //
  //  				) as new_table
  //  			    INNER JOIN aicrm_profile2standardpermissions on aicrm_profile2standardpermissions.tabid = new_table.tabid
  // 				WHERE  aicrm_profile2standardpermissions.tabid='".$tabid."' and  aicrm_profile2standardpermissions.profileid = new_table.profileid";
  //
  //         $query = $this->db->query($sql_user);
  //         $a_result  = $query->result_array() ;
  //         foreach ($a_result as $key => $value) {
  //
  //           if($value['operation']==3 || $value['operation']==4){
  //             if($value['permissions']==0){
  //               return $permission_user = true;
  //             }else {
  //               return $permission_user = false;
  //             }
  //           }
  //           // alert($value);
  //         }
  //         // exit;
  //     }else{
  //         return $permission_user=""
  //     }
  //
  //   }
  //
  // }

}
