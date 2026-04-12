<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /cvsroot/vtigercrm/aicrm_crm/modules/Quotes/Save.php,v 1.10 2005/12/14 18:51:30 jerrydgeorge Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Opportunity/Opportunity.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
include("modules/Emails/mail.php");

$local_log =& LoggerManager::getLogger('index');

$focus = new Opportunity();
//added to fix 4600
$search=vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);

$focus->column_fields['currency_id'] = $_REQUEST['inventory_currency'];
$cur_sym_rate = getCurrencySymbolandCRate($_REQUEST['inventory_currency']);
$focus->column_fields['conversion_rate'] = $cur_sym_rate['rate'];

if($_REQUEST['assigntype'] == 'U') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}
$focus->save("Opportunity");

$return_id = $focus->id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Opportunity";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);

//update=====================================
	$sql=" select parent_id from aicrm_opportunity where opportunityid='".$focus->id."'";
	$data=$adb->pquery($sql, array());
	$ticketid=$adb->query_result($data,0,'parent_id');
	$sql="
	SELECT aicrm_troubletickets.ticketid,cf_1234,cf_1235
	FROM aicrm_troubletickets 
	INNER JOIN aicrm_crmentity  ON aicrm_troubletickets.ticketid = aicrm_crmentity.crmid
	INNER JOIN aicrm_ticketcf  ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
	WHERE aicrm_crmentity.deleted =0
	and aicrm_crmentity.crmid='".$ticketid."'
	";
	//echo $sql;exit;
	$data=$adb->pquery($sql, array());
	$rows = $adb->num_rows($data);
	//echo $rows;
	if($rows>0){
		for($i=0;$i<$rows;$i++){
			$case_date=$adb->query_result($data,$i,'cf_1234')." ".$adb->query_result($data,$i,'cf_1235');
			$ticketid=$adb->query_result($data,$i,'ticketid');
			//หาวันที่เริ่ม
			$sql="
			select
			cf_1211 as start_date 
			from aicrm_opportunity 
			left join aicrm_opportunitycf on aicrm_opportunitycf.opportunityid=aicrm_opportunity.opportunityid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_opportunity.opportunityid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_opportunity.parent_id='".$ticketid."'
			order by cf_1211 ASC  limit 1
			";
			//echo $sql."<br>";
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$start_date=$adb->query_result($data_start,0,'start_date');
			}else{
				$start_date="";
			}
			//หาเวลาที่เริ่ม
			$sql="
			select
			cf_1213 as start_time
			from aicrm_opportunity 
			left join aicrm_opportunitycf on aicrm_opportunitycf.opportunityid=aicrm_opportunity.opportunityid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_opportunity.opportunityid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_opportunity.parent_id='".$ticketid."'
			order by cf_1213 asc   limit 1
			";
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$start_time=$adb->query_result($data_start,0,'start_time');
			}else{
				$start_time="";
			}
			//หาวันที่สิ้นสุด
			$sql="
			select
			cf_1211 as endt_date
			from aicrm_opportunity 
			left join aicrm_opportunitycf on aicrm_opportunitycf.opportunityid=aicrm_opportunity.opportunityid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_opportunity.opportunityid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_opportunity.parent_id='".$ticketid."'
			order by cf_1211 DESC   limit 1
			";
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_date=$adb->query_result($data_start,0,'endt_date');
			}else{
				$endt_date="";
			}
			//หาเวลาที่สิ้นสุด
			$sql="
			select
			cf_1217 as endt_time
			from aicrm_opportunity 
			left join aicrm_opportunitycf on aicrm_opportunitycf.opportunityid=aicrm_opportunity.opportunityid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_opportunity.opportunityid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_opportunity.parent_id='".$ticketid."'
			order by cf_1217 desc    limit 1
			";
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_time=$adb->query_result($data_start,0,'endt_time');
			}else{
				$endt_time="";
			}
			$start_date1=$start_date." ".$start_time;
			$endt_date1=$endt_date." ".$endt_time;
			//echo $start_date.",".$endt_date."<br>";
			if($start_date=="" || $endt_date==""){
				$Arrive=0;
			}else{
				$t1= strtotime($start_date1);
				$t2= strtotime($endt_date1);
				$Arrive=($t2 - $t1)/60;
			}
			$Arrive1=($Arrive%60);
			$Arrive2=($Arrive-($Arrive%60))/60;
			$Arrive=$Arrive2.".".$Arrive1;
			if($endt_date==""){
				$SLA=0;
			}else{
				$t1= strtotime($case_date);
				$t2= strtotime($endt_date1);
				$SLA=($t2 - $t1)/60;
			}
			$SLA1=($SLA%60);
			$SLA2=($SLA-($SLA%60))/60;
			$SLA=$SLA2.".".$SLA1;
			//echo $SLA."<br>";
			//echo $ticketid." ".$start_date." ".$start_time."   ".$endt_date." ".$endt_time." =".$Arrive." ".$SLA."<br>";
			$sql="update aicrm_ticketcf set cf_1264='".$start_date1."', cf_1266='".$endt_date1."', cf_1265='".$Arrive."', cf_1267='".$SLA."' where ticketid='".$ticketid."' ";
			$adb->pquery($sql, array());
		}
	}//end update case
	
	//update OT======================================================
	$sql="
	select
	cf_1211 as start_date ,cf_1217 as endt_time,cf_1213 as start_time,cf_1224
	from aicrm_opportunity 
	left join aicrm_opportunitycf on aicrm_opportunitycf.opportunityid=aicrm_opportunity.opportunityid
	left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_opportunity.opportunityid
	left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
	where 1
	and aicrm_crmentityJOB1.deleted =0
	and aicrm_opportunity.opportunityid='".$focus->id."'
	order by cf_1211 ASC  limit 1
	";
	//echo $sql."<br>";
	$data_start = $adb->pquery($sql, array());
	//print_r($data_start);
	$rows1 = $adb->num_rows($data_start);
	if($rows1>0){
		$st_date=$adb->query_result($data_start,0,'start_date');
		$st_datetime=$adb->query_result($data_start,0,'start_date')." ".$adb->query_result($data_start,0,'start_time');
		$ed_datetime=$adb->query_result($data_start,0,'start_date')." ".$adb->query_result($data_start,0,'endt_time');
		$start_time=$adb->query_result($data_start,0,'start_time');
		$endt_time=$adb->query_result($data_start,0,'endt_time');
		$job_detail_status=$adb->query_result($data_start,0,'cf_1224');
	}
	//echo $st_date;
	$sql="select holiday_date from tbm_holiday where holiday_date='".$st_date."'";
	$data_start = $adb->pquery($sql, array());
	//print_r($data_start);
	$rows1 = $adb->num_rows($data_start);
	if($rows1>0){
		$chk_h=1;
	}else{
		$chk_h=0;	
	}
	//$thai_day_arr=array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");  
	//$thai_date_return=$thai_day_arr[date("l",$st_date)]; 
	if(date('l',strtotime($st_date))=="Sunday" || date('l',strtotime($st_date))=="Saturday"){
		$chk_h=1;
	}
	//echo strtotime($st_datetime)."=>".strtotime($st_date." "."18:00");
	$t1_18=strtotime($st_date." "."17:30");
	$t1_8=strtotime($st_date." "."8:00");
	$t1= strtotime($st_datetime);
	$t2= strtotime($ed_datetime);
	$OT=($t2 - $t1)/60;
	$OT1=($OT%60);
	$OT2=($OT-($OT%60))/60;
	$OT=$OT2.".".$OT1;
	if($chk_h==0){//echo "55";
		if(($t2>=$t1_18) and ($t1<=$t1_18)){
			//echo $ed_datetime;exit;
			$OT=($t1_18 - $t1)/60;
			//echo $OT;exit;
			$OT1=($OT%60);
			$OT2=($OT-($OT%60))/60;
			$OT=$OT2.".".$OT1;
			$OT1=$OT;
			
			$OT=($t2 - $t1_18)/60;
			$OT11=($OT%60);
			$OT2=($OT-($OT%60))/60;
			$OT=$OT2.".".$OT11;
			
			$OT2=$OT;
			$OT3=0;
			$OT4=0;
		}else if(($t2>=$t1_18) and ($t1>=$t1_18)){
			//echo $ed_datetime;exit;
			$OT=($t2 - $t1)/60;
			//echo $OT;exit;
			$OT1=($OT%60);
			$OT2=($OT-($OT%60))/60;
			$OT=$OT2.".".$OT1;
			$OT1=0;
			
			//$OT=($t2 - $t1_18)/60;
			//$OT11=($OT%60);
			//$OT2=($OT-($OT%60))/60;
			//$OT=$OT2.".".$OT11;
			
			$OT2=$OT;
			$OT3=0;
			$OT4=0;
		}else if(($t1<=$t1_18) and ($t2<=$t1_8)){
			//echo $ed_datetime;exit;
			$OT=($t2 - $t1)/60;
			//echo $OT;exit;
			$OT1=($OT%60);
			$OT2=($OT-($OT%60))/60;
			$OT=$OT2.".".$OT1;
			$OT1=0;
			
			//$OT=($t2 - $t1_18)/60;
			//$OT11=($OT%60);
			//$OT2=($OT-($OT%60))/60;
			//$OT=$OT2.".".$OT11;
			
			$OT2=$OT;
			$OT3=0;
			$OT4=0;
		}else{
			$OT1=$OT;	
			$OT2=0;
			$OT3=0;
			$OT4=0;
		}
		/*if($OT>8){
			$OT1=8;	
			$OT2=$OT-8;
			$OT3=0;
			$OT4=0;
		}else{
			if($t1_18>=$t1){
				$OT1=0;	
				$OT2=$OT;	
				$OT3=0;
				$OT4=0;
			}else{
				$OT1=$OT;	
				$OT2=0;
				$OT3=0;
				$OT4=0;
			}
		}*/
	}else{
		if($OT>8){
			$OT1=0;	
			$OT2=0;
			$OT3=8;
			$OT4=$OT-8;
		}else{
			$OT1=0;	
			$OT2=0;
			$OT3=$OT;
			$OT4=0;
		}
	}
	$sql="update aicrm_opportunitycf set cf_1258='".$OT1."', cf_1259='".$OT2."', cf_1260='".$OT3."', cf_1261='".$OT4."' where opportunityid='".$focus->id."' ";
	$adb->pquery($sql, array());
	//echo $st_datetime." ".$ed_datetime." ".$OT1." ".$OT2." ".$OT3." ".$OT4;
	
	//update call status=============
	$call_status="";
	//echo $job_detail_status;exit;
	if($job_detail_status=="Complete"){
		$call_status="2 - Completed";
	}else if($job_detail_status="Incomplete"){
		$call_status="3 - Incompleted";
	}
	$sql="update aicrm_troubletickets set status='".$call_status."' where ticketid='".$ticketid."'";
	//echo $sql;
	$adb->pquery($sql, array());
header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
?>