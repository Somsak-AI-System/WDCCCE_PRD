<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
session_start();
header('Content-Type: text/html; charset=utf-8');
include("../../config.inc.php");
include("../../library/dbconfig.php");
global $adb,$current_user,$mod_strings,$app_strings,$cal_log,$listview_max_textlength,$list_max_entries_per_page ,$is_admin,$log;
$userid = $_SESSION['authenticated_user_id'];
include('../../user_privileges/user_privileges_'.$userid.'.php');
include('../../user_privileges/sharing_privileges_'.$userid.'.php');
require_once("../../library/general.php");
require_once("../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;


$sql_u = "select * from aicrm_users where id = '".$_SESSION['authenticated_user_id']."'";
$u_data = $myLibrary_mysqli->select($sql_u);
$is_admin = $u_data[0]['is_admin'];

$user = $_SESSION['user_id'];
$day = $_REQUEST['day'];
$month = $_REQUEST['month'];
$viewtype = @$_REQUEST['viewtype'];
$view = $_REQUEST['view'];
$module = $_REQUEST['module'];
	
	$a_data = array();
	$s_day = $day;
	
	if($view == 'week' && $viewtype == 'listview'){
		$w_day = explode(',', $s_day);
		if($module == 'All'){
			
			$sql = "Select new_table.* from 
				(
				select
				aicrm_account.accountid as crmid, 
			    aicrm_account.accountname as name, 
			    aicrm_account.mobile as mobile, 
			    aicrm_account.email1 as email,
			    0 as age,
				aicrm_account.birthdate , 
				DATE_FORMAT(aicrm_account.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_account.birthdate) as birthdate_day , 
				MONTH(aicrm_account.birthdate) as birthdate_month,
				'Accounts' as module,
				(year(curdate()) - YEAR(aicrm_account.birthdate)) AS ageinyears
				from aicrm_account
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
	            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
	            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
	            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
			}

			$sql .= "UNION  ALL
				
				select
				aicrm_leaddetails.leadid as crmid, 
			    aicrm_leaddetails.leadname as name, 
			    aicrm_leaddetails.mobile as mobile, 
			    aicrm_leaddetails.email as email,
			    0 as age,
				aicrm_leaddetails.birthdate , 
				DATE_FORMAT(aicrm_leaddetails.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_leaddetails.birthdate) as birthdate_day , 
				MONTH(aicrm_leaddetails.birthdate) as birthdate_month,
				'Leads' as module,
				(year(curdate()) - YEAR(aicrm_leaddetails.birthdate)) AS ageinyears
				from aicrm_leaddetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadsubdetails ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
	            INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";

			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='7') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='7'))) ";  
			}
			$sql .= "UNION  ALL
			    
			    select 
			    aicrm_contactdetails.contactid as crmid, 
			    aicrm_contactdetails.contactname as name, 
			    aicrm_contactdetails.mobile as mobile, 
			    aicrm_contactdetails.email as email,
			    0 as age,
			    aicrm_contactdetails.birthdate as birthdate , 
			    DATE_FORMAT(aicrm_contactdetails.birthdate, '%d-%m-%Y') AS birthday,
			    DAY(aicrm_contactdetails.birthdate) as birthdate_day ,
			    MONTH(aicrm_contactdetails.birthdate) as birthdate_month,
			    'Contacts' as module,
			    (year(curdate()) - YEAR(aicrm_contactdetails.birthdate)) AS ageinyears
				from aicrm_contactdetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
				where aicrm_crmentity.deleted =0 ";
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
			{
				$sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='4') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='4'))) ";  
			} 
			$sql .= ") as new_table where ";
			
			foreach ($w_day as $key => $value) {
			  	if($key != 0 ){
			  		$sql .= " or ";
			  	}
			  	$sql .= " (MONTH(new_table.birthdate) = month('".$value."') and DAY(new_table.birthdate) = DAY('".$value."')) ";
			}
			
			$sql .= "order by new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}else if($module == 'Accounts'){
			$sql = "Select new_table.* from 
				(
				select
				aicrm_account.accountid as crmid, 
			    aicrm_account.accountname as name, 
			    aicrm_account.mobile as mobile, 
			    aicrm_account.email1 as email,
			    0 as age,
				aicrm_account.birthdate ,
				DATE_FORMAT(aicrm_account.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_account.birthdate) as birthdate_day , 
				MONTH(aicrm_account.birthdate) as birthdate_month,
				'Accounts' as module,
				(year(curdate()) - YEAR(aicrm_account.birthdate)) AS ageinyears
				from aicrm_account
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
	            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
	            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
	            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";		    
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
			}

			$sql .= ") as new_table where ";
			  	foreach ($w_day as $key => $value) {
				  	if($key != 0 ){
				  		$sql .= " or ";
				  	}
				  	$sql .= " (MONTH(new_table.birthdate) = month('".$value."') and DAY(new_table.birthdate) = DAY('".$value."')) ";
				}
			$sql .= "ORDER BY new_table.birthdate_day";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}else if($module == 'Leads'){
			$sql = "Select new_table.* from 
				(
				select
				aicrm_leaddetails.leadid as crmid, 
			    aicrm_leaddetails.leadname as name, 
			    aicrm_leaddetails.mobile as mobile, 
			    aicrm_leaddetails.email as email,
			    0 as age,
				aicrm_leaddetails.birthdate ,
				DATE_FORMAT(aicrm_leaddetails.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_leaddetails.birthdate) as birthdate_day , 
				MONTH(aicrm_leaddetails.birthdate) as birthdate_month,
				'Leads' as module,
				(year(curdate()) - YEAR(aicrm_leaddetails.birthdate)) AS ageinyears
				from aicrm_leaddetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
	            INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='7') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='7'))) ";  
			}
			$sql .= ") as new_table where ";
				foreach ($w_day as $key => $value) {
				  	if($key != 0 ){
				  		$sql .= " or ";
				  	}
				  	$sql .= " (MONTH(new_table.birthdate) = month('".$value."') and DAY(new_table.birthdate) = DAY('".$value."')) ";
				}
			
			$sql .="order by new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}else if($module == 'Contacts'){
			$sql = "Select new_table.* from 
				(
			    select 
			    aicrm_contactdetails.contactid as crmid, 
			    aicrm_contactdetails.contactname as name, 
			    aicrm_contactdetails.mobile as mobile, 
			    aicrm_contactdetails.email as email,
			    0 as age,
			    aicrm_contactdetails.birthdate as birthdate ,
			    DATE_FORMAT(aicrm_contactdetails.birthdate, '%d-%m-%Y') AS birthday,
			    DAY(aicrm_contactdetails.birthdate) as birthdate_day ,
			    MONTH(aicrm_contactdetails.birthdate) as birthdate_month,
			    'Contacts' as module,
			    (year(curdate()) - YEAR(aicrm_contactdetails.birthdate)) AS ageinyears
				from aicrm_contactdetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
				where aicrm_crmentity.deleted =0 ";
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
			{
				$sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='4') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='4'))) ";  
			} 

			$sql .= ") as new_table where ";
				foreach ($w_day as $key => $value) {
				  	if($key != 0 ){
				  		$sql .= " or ";
				  	}
				  	$sql .= " (MONTH(new_table.birthdate) = month('".$value."') and DAY(new_table.birthdate) = DAY('".$value."')) ";
				}
			$sql .="order by new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
		}

	}else if($view != 'year'){

		if($module == 'All'){
			
			$sql = "Select new_table.* from 
				(
				select
				aicrm_account.accountid as crmid, 
			    aicrm_account.accountname as name, 
			    aicrm_account.mobile as mobile, 
			    aicrm_account.email1 as email,
			    0 as age,
				aicrm_account.birthdate ,
				DATE_FORMAT(aicrm_account.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_account.birthdate) as birthdate_day , 
				MONTH(aicrm_account.birthdate) as birthdate_month,
				'Accounts' as module,
				(year(curdate()) - YEAR(aicrm_account.birthdate)) AS ageinyears
				from aicrm_account
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
	            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
	            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
	            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
		    {
		        $sql .= " and (
		                aicrm_crmentity.smownerid in(".$userid.") 
		                or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
		                or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
		                or (";

		            if(sizeof($current_user_groups) > 0)
		            {
		                  $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
		            }
		             $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
		    }	
			$sql .= "UNION  ALL
				
				select
				aicrm_leaddetails.leadid as crmid, 
			    aicrm_leaddetails.leadname as name, 
			    aicrm_leaddetails.mobile as mobile, 
			    aicrm_leaddetails.email as email,
			    0 as age,
				aicrm_leaddetails.birthdate ,
				DATE_FORMAT(aicrm_leaddetails.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_leaddetails.birthdate) as birthdate_day , 
				MONTH(aicrm_leaddetails.birthdate) as birthdate_month,
				'Leads' as module,
				(year(curdate()) - YEAR(aicrm_leaddetails.birthdate)) AS ageinyears
				from aicrm_leaddetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
	            INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
			{
				$sql .= " and (
			        aicrm_crmentity.smownerid in(".$userid.") 
			        or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			        or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='7') 
			        or (";

			    if(sizeof($current_user_groups) > 0)
			    {
			          $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			    }
			     $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='7'))) ";  
			}

			$sql .= "UNION  ALL
			    
			    select 
			    aicrm_contactdetails.contactid as crmid, 
			    aicrm_contactdetails.contactname as name, 
			    aicrm_contactdetails.mobile as mobile, 
			    aicrm_contactdetails.email as email,
			    0 as age,
			    aicrm_contactdetails.birthdate as birthdate ,
			    DATE_FORMAT(aicrm_contactdetails.birthdate, '%d-%m-%Y') AS birthday, 
			    DAY(aicrm_contactdetails.birthdate) as birthdate_day ,
			    MONTH(aicrm_contactdetails.birthdate) as birthdate_month,
			    'Contacts' as module,
			    (year(curdate()) - YEAR(aicrm_contactdetails.birthdate)) AS ageinyears
				from aicrm_contactdetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
				where aicrm_crmentity.deleted =0 ";
			    if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
				{
				  $sql .= " and (
				          aicrm_crmentity.smownerid in(".$userid.") 
				          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
				          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='4') 
				          or (";

				      if(sizeof($current_user_groups) > 0)
				      {
				            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
				      }
				       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='4'))) ";  
				}

			$sql .= "  ) as new_table ";
			if($month != ''){
				$sql .= "WHERE new_table.birthdate_month = '".$month."' ";
			}
			if($viewtype == ''){
				if($day != ''){
					$sql .= "and new_table.birthdate_day in (".$s_day.") ";
				}
			}
			if($view == 'day'){
				$sql .= "and new_table.birthdate_day in (".$s_day.") ";
			}

			$sql .= "order by new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);			
		}else if($module == 'Accounts'){
			$sql = "Select new_table.* from 
				(
				select
				aicrm_account.accountid as crmid, 
			    aicrm_account.accountname as name, 
			    aicrm_account.mobile as mobile, 
			    aicrm_account.email1 as email,
			    0 as age,
				aicrm_account.birthdate ,
				DATE_FORMAT(aicrm_account.birthdate, '%d-%m-%Y') AS birthday,  
				DAY(aicrm_account.birthdate) as birthdate_day , 
				MONTH(aicrm_account.birthdate) as birthdate_month,
				'Accounts' as module,
				(year(curdate()) - YEAR(aicrm_account.birthdate)) AS ageinyears
				from aicrm_account
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
	            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
	            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
	            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";

			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
		    {
		        $sql .= " and (
		                aicrm_crmentity.smownerid in(".$userid.") 
		                or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
		                or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
		                or (";

		            if(sizeof($current_user_groups) > 0)
		            {
		                  $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
		            }
		             $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
		    }

			$sql .= ") as new_table ";
			  	if($month != ''){
					$sql .= "WHERE new_table.birthdate_month = '".$month."' ";
				}
				if($day != ''){
					$sql .= "and new_table.birthdate_day in (".$s_day.") ";
				}
			$sql .= "ORDER BY new_table.birthdate_day";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}else if($module == 'Leads'){
			$sql = "Select new_table.* from 
				(
				select
				aicrm_leaddetails.leadid as crmid, 
			    aicrm_leaddetails.leadname as name, 
			    aicrm_leaddetails.mobile as mobile, 
			    aicrm_leaddetails.email as email,
			    0 as age,
				aicrm_leaddetails.birthdate ,
				DATE_FORMAT(aicrm_leaddetails.birthdate, '%d-%m-%Y') AS birthday, 
				DAY(aicrm_leaddetails.birthdate) as birthdate_day , 
				MONTH(aicrm_leaddetails.birthdate) as birthdate_month,
				'Leads' as module,
				(year(curdate()) - YEAR(aicrm_leaddetails.birthdate)) AS ageinyears
				from aicrm_leaddetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
	            INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";

			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
			{
				$sql .= " and (
			        aicrm_crmentity.smownerid in(".$userid.") 
			        or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			        or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='7') 
			        or (";

			    if(sizeof($current_user_groups) > 0)
			    {
			          $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			    }
			     $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='7'))) ";  
			}

			$sql .= ") as new_table ";
				if($month != ''){
					$sql .= "WHERE new_table.birthdate_month = '".$month."' ";
				}
				if($day != ''){
					$sql .= "and new_table.birthdate_day in (".$s_day.") ";
				}
			
			$sql .="order by new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}else if($module == 'Contacts'){
			$sql = "Select new_table.* from 
				(
			    select 
			    aicrm_contactdetails.contactid as crmid, 
			    aicrm_contactdetails.contactname as name, 
			    aicrm_contactdetails.mobile as mobile, 
			    aicrm_contactdetails.email as email,
			    0 as age,
			    aicrm_contactdetails.birthdate as birthdate ,
			    DATE_FORMAT(aicrm_contactdetails.birthdate, '%d-%m-%Y') AS birthday,  
			    DAY(aicrm_contactdetails.birthdate) as birthdate_day ,
			    MONTH(aicrm_contactdetails.birthdate) as birthdate_month,
			    'Contacts' as module,
			    (year(curdate()) - YEAR(aicrm_contactdetails.birthdate)) AS ageinyears
				from aicrm_contactdetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
				where aicrm_crmentity.deleted =0 ";

			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.") 
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='4') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='4'))) ";  
			}
			
			$sql .= ") as new_table ";
				if($month != ''){
					$sql .= "WHERE new_table.birthdate_month = '".$month."' ";
				}
				if($day != ''){
					$sql .= "and new_table.birthdate_day in (".$s_day.") ";
				}
			
			$sql .="order by new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}

	}else{

		if($module == 'All'){
			$sql = "Select new_table.* from 
				(
				select 
				aicrm_account.accountid as crmid, 
			    aicrm_account.accountname as name, 
			    aicrm_account.mobile as mobile, 
			    aicrm_account.email1 as email,
			    0 as age,
				aicrm_account.birthdate ,
				DATE_FORMAT(aicrm_account.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_account.birthdate) as birthdate_day , 
				MONTH(aicrm_account.birthdate) as birthdate_month,
				'Accounts' as module,
				(year(curdate()) - YEAR(aicrm_account.birthdate)) AS ageinyears
				from aicrm_account
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
	            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
	            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
	            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.") 
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";
			}	
				
			$sql .= "UNION  ALL
				
				select 
				aicrm_leaddetails.leadid as crmid, 
			    aicrm_leaddetails.leadname as name, 
			    aicrm_leaddetails.mobile as mobile, 
			    aicrm_leaddetails.email as email,
			    0 as age,
				aicrm_leaddetails.birthdate ,
				DATE_FORMAT(aicrm_leaddetails.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_leaddetails.birthdate) as birthdate_day , 
				MONTH(aicrm_leaddetails.birthdate) as birthdate_month,
				'Leads' as module,
				(year(curdate()) - YEAR(aicrm_leaddetails.birthdate)) AS ageinyears
				from aicrm_leaddetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
	            INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='7') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='7'))) ";
			}

			$sql .= "UNION  ALL
			    
			    select
			    aicrm_contactdetails.contactid as crmid, 
			    aicrm_contactdetails.contactname as name, 
			    aicrm_contactdetails.mobile as mobile, 
			    aicrm_contactdetails.email as email,
			    0 as age,
			    aicrm_contactdetails.birthdate as birthdate ,
			    DATE_FORMAT(aicrm_contactdetails.birthdate, '%d-%m-%Y') AS birthday,
			    DAY(aicrm_contactdetails.birthdate) as birthdate_day ,
			    MONTH(aicrm_contactdetails.birthdate) as birthdate_month,
			    'Contacts' as module,
			    (year(curdate()) - YEAR(aicrm_contactdetails.birthdate)) AS ageinyears
				from aicrm_contactdetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
				where aicrm_crmentity.deleted =0 ";

			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
			{
				$sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='4') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='4')))";
			}
			$sql .= " ) as new_table where new_table.birthdate_day is not null and DAY(new_table.birthdate) != 0 and MONTH(new_table.birthdate) != 0 ";

			if($viewtype == ''){
				if($month != ''){
					$sql .= "and new_table.birthdate_month = '".$month."' ";
				}
			}

			$sql .= " ORDER BY new_table.birthdate_month ASC,new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}else if($module == 'Accounts'){
			$sql = "Select new_table.* from 
				(
				select
				aicrm_account.accountid as crmid, 
			    aicrm_account.accountname as name, 
			    aicrm_account.mobile as mobile, 
			    aicrm_account.email1 as email,
			    0 as age,
				aicrm_account.birthdate ,
				DATE_FORMAT(aicrm_account.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_account.birthdate) as birthdate_day , 
				MONTH(aicrm_account.birthdate) as birthdate_month,
				'Accounts' as module,
				(year(curdate()) - YEAR(aicrm_account.birthdate)) AS ageinyears
				from aicrm_account
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
	            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
	            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
	            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
		    {
		        $sql .= " and (
		                aicrm_crmentity.smownerid in(".$userid.") 
		                or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
		                or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='6') 
		                or (";

		            if(sizeof($current_user_groups) > 0)
		            {
		                  $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
		            }
		             $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='6'))) ";  
		    }		    
			$sql .= ") as new_table where new_table.birthdate_day is not null and DAY(new_table.birthdate) != 0 and MONTH(new_table.birthdate) != 0 ";
			if($viewtype == ''){
				if($month != ''){
					$sql .= "and new_table.birthdate_month = '".$month."' ";
				}
			}
			$sql .= "ORDER BY new_table.birthdate_month ASC,new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql;
		}else if($module == 'Leads'){
			$sql = "Select new_table.* from 
				(
				select
				aicrm_leaddetails.leadid as crmid, 
			    aicrm_leaddetails.leadname as name, 
			    aicrm_leaddetails.mobile as mobile, 
			    aicrm_leaddetails.email as email,
			    0 as age,
				aicrm_leaddetails.birthdate ,
				DATE_FORMAT(aicrm_leaddetails.birthdate, '%d-%m-%Y') AS birthday,
				DAY(aicrm_leaddetails.birthdate) as birthdate_day , 
				MONTH(aicrm_leaddetails.birthdate) as birthdate_month,
				'Leads' as module,
				(year(curdate()) - YEAR(aicrm_leaddetails.birthdate)) AS ageinyears
				from aicrm_leaddetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
	            INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
	            INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_crmentity.deleted =0 ";
			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
			{
			  $sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='7') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='7'))) ";
			}

			$sql .= ") as new_table where new_table.birthdate_day is not null and DAY(new_table.birthdate) != 0 and MONTH(new_table.birthdate) != 0 ";
			if($viewtype == ''){
				if($month != ''){
					$sql .= "and new_table.birthdate_month = '".$month."' ";
				}
			}
			$sql .= "ORDER BY new_table.birthdate_month ASC,new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
			//echo $sql; exit;
		}else if($module == 'Contacts'){
			$sql = "Select new_table.* from 
				(
			    select
			    aicrm_contactdetails.contactid as crmid, 
			    aicrm_contactdetails.contactname as name, 
			    aicrm_contactdetails.mobile as mobile, 
			    aicrm_contactdetails.email as email,
			    0 as age,
			    aicrm_contactdetails.birthdate as birthdate ,
			    DATE_FORMAT(aicrm_contactdetails.birthdate, '%d-%m-%Y') AS birthday, 
			    DAY(aicrm_contactdetails.birthdate) as birthdate_day ,
			    MONTH(aicrm_contactdetails.birthdate) as birthdate_month,
			    'Contacts' as module,
			    (year(curdate()) - YEAR(aicrm_contactdetails.birthdate)) AS ageinyears
				from aicrm_contactdetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
	            INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
	            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
				where aicrm_crmentity.deleted =0 ";

			if($is_admin=='off' && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
			{
				$sql .= " and (
			          aicrm_crmentity.smownerid in(".$userid.")
			          or aicrm_crmentity.smownerid in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '".$current_user_parent_role_seq."::%') 
			          or aicrm_crmentity.smownerid in(select shareduserid from aicrm_tmp_read_user_sharing_per where userid=".$userid." and tabid='4') 
			          or (";

			      if(sizeof($current_user_groups) > 0)
			      {
			            $sql .= " aicrm_groups.groupid in (". implode(",", $current_user_groups) .") or ";
			      }
			       $sql .= " aicrm_groups.groupid in(select aicrm_tmp_read_group_sharing_per.sharedgroupid from aicrm_tmp_read_group_sharing_per where userid=".$userid." and tabid='4')))";
			}
			$sql .= ") as new_table where new_table.birthdate_day is not null and DAY(new_table.birthdate) != 0 and MONTH(new_table.birthdate) != 0 ";
			if($viewtype == ''){
				if($month != ''){
					$sql .= "and new_table.birthdate_month = '".$month."' ";
				}
			}
			$sql .= "ORDER BY new_table.birthdate_month ASC,new_table.birthdate_day ASC";
			$a_data = $myLibrary_mysqli->select($sql);
		}

	}
	//echo $sql ; exit;
	if(!empty($a_data)){
		foreach ($a_data as $key => $value) {
			$a_data[$key]['name'] = mb_substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $value['name']), 0, 30,"utf-8") . '....';
			$a_data[$key]['number'] = ($key+1);
		}
	}
	//echo "<pre>"; print_r($a_data); echo "</pre>";
    $result['type'] = 'S';
    $result['data'] = $a_data;
    $result['msg'] = '';

	echo json_encode($result);

?>
