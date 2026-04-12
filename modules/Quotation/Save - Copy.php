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

require_once('modules/Quotation/Quotation.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
include("modules/Emails/mail.php");

$local_log =& LoggerManager::getLogger('index');

$focus = new Quotation();
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
$focus->save("Quotation");
//echo "555";
$return_id = $focus->id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "Quotation";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

$local_log->debug("Saved record with id of ".$return_id);

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=vtlib_purify($_REQUEST['return_viewname']);

//update=====================================
	$sql=" select parent_id from aicrm_quotation where quotationid='".$focus->id."'";
	$data=$adb->pquery($sql, array());
	$ticketid=$adb->query_result($data,0,'parent_id');
	$sql="
	SELECT aicrm_troubletickets.ticketid,cf_1234,cf_1235,cf_1336,cf_1337,aicrm_usersJOB1.id as tick_user
	FROM aicrm_troubletickets 
	INNER JOIN aicrm_crmentity  ON aicrm_troubletickets.ticketid = aicrm_crmentity.crmid
	INNER JOIN aicrm_ticketcf  ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
	left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentity.smownerid = aicrm_usersJOB1.id
	WHERE aicrm_crmentity.deleted =0
	and aicrm_crmentity.crmid='".$ticketid."'
	";
	//echo $sql;exit;
	$data=$adb->pquery($sql, array());
	$rows = $adb->num_rows($data);
	//echo $rows;
	if($rows>0){
		for($i=0;$i<$rows;$i++){
			//$case_date=$adb->query_result($data,$i,'cf_1234')." ".$adb->query_result($data,$i,'cf_1235');
			$case_date=$adb->query_result($data,$i,'cf_1336')." ".$adb->query_result($data,$i,'cf_1337');
			$case_date1=$adb->query_result($data,$i,'cf_1336');
			$ticketid=$adb->query_result($data,$i,'ticketid');
			$tick_user=$adb->query_result($data,0,'tick_user');
			//หาวันที่เริ่ม
			$sql="
			select
			cf_1211 as start_date 
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
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
			
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
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
			cf_1328 as endt_date,
			cf_1339 as travel_date
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
			order by cf_1328 DESC   limit 1
			";
			//echo $sql;
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_date=$adb->query_result($data_start,0,'endt_date');
				$travel_date=$adb->query_result($data_start,0,'travel_date');
			}else{
				$endt_date="";
				$travel_date="";
			}
			//หาเวลาที่สิ้นสุด
			$sql="
			select
			cf_1217 as endt_time,
			cf_1316 as travel_time
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
			order by cf_1217 desc    limit 1
			";
			//echo $sql;
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_time=$adb->query_result($data_start,0,'endt_time');
				$travel_time=$adb->query_result($data_start,0,'travel_time');
			}else{
				$endt_time="";
				$travel_time="";
			}
			$start_date1=$start_date." ".$start_time;
			$endt_date1=$endt_date." ".$endt_time;
			$travel_time1=$travel_date." ".$travel_time;
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
			$Arrive=$Arrive2.":".$Arrive1;
			//echo $case_date."=>".$travel_date." ".$travel_time;exit;
			if($endt_date==""){
				$SLA=0;
			}else{
				$t1= strtotime($case_date);
				$t2= strtotime($travel_date." ".$travel_time);
				$SLA=($t2 - $t1)/60;
			}
			//echo $SLA." ".(1035%60);exit;
			$SLA1=($SLA%60);
			$SLA2=($SLA-($SLA%60))/60;
			if($SLA1>9){
				$SLA=$SLA2.":".$SLA1;	
			}else{
				$SLA=$SLA2.":0".$SLA1;	
			}
//new SLA==================================================================================================================
			//หาวันที่สิ้นสุด
			$sql="
			select
			cf_1328 as travel_date,
			cf_1339 as endt_date
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
			and cf_1224!='Assistant'
			and cf_1212 in(10,12,13)/**/
			order by aicrm_quotation.quotationid DESC   limit 1
			";
			//echo $sql;exit;
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_date_N=$adb->query_result($data_start,0,'endt_date');
				$travel_date_N=$adb->query_result($data_start,0,'travel_date');
			}else{
				$endt_date_N="";
				$travel_date_N="";
			}
			//หาเวลาที่สิ้นสุด
			$sql="
			select
			cf_1217 as travel_time,
			cf_1316 as endt_time
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
			and cf_1224!='Assistant'
			and cf_1212 in(10,12,13)/**/
			order by cf_1217 desc    limit 1
			";
			//echo $sql;exit;
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_time_N=$adb->query_result($data_start,0,'endt_time');
				$travel_time_N=$adb->query_result($data_start,0,'travel_time');
			}else{
				$endt_time_N="";
				$travel_time_N="";
			}
			$start_date1_N=$start_date." ".$start_time;
			$endt_date1_N=$endt_date_N." ".$endt_time_N;
			$travel_time1_N=$travel_date_N." ".$travel_time_N;
			
			//echo $case_date."=>".$endt_date1_N;exit;
			$t1= strtotime($case_date);
			$t2= strtotime($endt_date1_N);
			$M=($t2 - $t1)/86400;	
			$M=ceil($M);		
			$SLA=0;
			//echo $M;exit;
			$chk_case_date=$case_date1;
			if($M>1){
				for($i=0;$i<$M;$i++){
					if($i==0){
						//หาเวลาที่สิ้นสุด
						$sql="
						select
						cf_1217 as travel_time,
						cf_1316 as endt_time
						from aicrm_quotation 
						left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
						left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
						left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
						where 1
						and aicrm_crmentityJOB1.deleted =0
						and aicrm_quotationcf.cf_1328='".$case_date1."'
						and aicrm_quotation.parent_id='".$ticketid."'
						and cf_1224!='Assistant'
						and cf_1212 in(10,12,13)/**/
						order by aicrm_quotation.quotationid desc    limit 1
						";
						//echo $sql;exit;
						$data_start = $adb->pquery($sql, array());
						$rows1 = $adb->num_rows($data_start);
						if($rows1>0){
							$endt_time_N=$adb->query_result($data_start,0,'endt_time');
							$travel_time_N=$adb->query_result($data_start,0,'travel_time');
						}else{
							$endt_time_N="";
							$travel_time_N="";
						}
						if($endt_time_N==""){
							$endt_time_N="17:30";
						}
						$chk_case_date=$chk_case_date." ".$endt_time_N;
						//echo $case_date." ".$chk_case_date;exit;
						$t1= strtotime($case_date);
						$t2= strtotime($chk_case_date);
						$SLA=$SLA+($t2 - $t1)/60;
						
						/*
						$SLA1=($SLA%60);
						$SLA2=($SLA-($SLA%60))/60;
						if($SLA1>9){
							$SLA=$SLA2.":".$SLA1;	
						}else{
							$SLA=$SLA2.":0".$SLA1;	
						}
						echo $SLA;exit;*/
					}else{
						$chk_case_date=(date("Y-m-d",mktime(date('H',strtotime($chk_case_date)),date('i',strtotime($chk_case_date)),00,date('m',strtotime($chk_case_date)),date('d',strtotime($chk_case_date))+1,date('Y',strtotime($chk_case_date)))));
						//echo $chk_case_date."<br>";
						//หาเวลาที่เริ่ม Assistant
						$sql="
						select
						cf_1464 as start_time
						
						from aicrm_quotation 
						left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
						left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
						left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
						where 1
						and aicrm_crmentityJOB1.deleted =0
						and aicrm_quotationcf.cf_1463='".$chk_case_date."'
						and aicrm_quotation.parent_id='".$ticketid."'
						and cf_1224!='Assistant'
						
						and cf_1212 in(10,12,13)
						/*and cf_1224 ='Assistant'
						and aicrm_crmentityJOB1.crmid<'".$focus->id."'
						*/
						order by aicrm_quotation.quotationid asc   limit 1
						";
						//echo $sql."<br>";exit;
						$data_start = $adb->pquery($sql, array());
						$rows1 = $adb->num_rows($data_start);
						$start_time_Assistant="";
						$start_time="";
						if($rows1>0){
							$start_time_Assistant=$adb->query_result($data_start,0,'start_time');
						}else{
							$start_time_Assistant="";
						}
						//echo $start_time_Assistant;exit;
						//หาเวลาที่เริ่ม
						$sql="
						select
						cf_1213 as start_time
						
						from aicrm_quotation 
						left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
						left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
						left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
						where 1
						and aicrm_crmentityJOB1.deleted =0
						and aicrm_quotationcf.cf_1211='".$chk_case_date."'
						and aicrm_quotation.parent_id='".$ticketid."'
						and cf_1224!='Assistant'
						and cf_1212 in(10,12,13)/**/
						order by aicrm_quotation.quotationid asc   limit 1
						";
						//echo $sql;exit;
						$data_start = $adb->pquery($sql, array());
						$rows1 = $adb->num_rows($data_start);
						if($rows1>0){
							$start_time=$adb->query_result($data_start,0,'start_time');
						}else{
							$start_time="";
						}
						if($start_time_Assistant!=""){
							$start_time=$start_time_Assistant;
						}
						//echo $start_time."<br>";
						//หาเวลาที่สิ้นสุด
						$sql="
						select
						cf_1217 as travel_time,
						cf_1316 as endt_time
						
						from aicrm_quotation 
						left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
						left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
						left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
						where 1
						and aicrm_crmentityJOB1.deleted =0
						and aicrm_quotationcf.cf_1211='".$chk_case_date."'
						and aicrm_quotation.parent_id='".$ticketid."'
						and cf_1224!='Assistant'
						and cf_1212 in(10,12,13)/**/
						order by aicrm_quotation.quotationid asc   limit 1
						";
						//echo $sql;exit;
						$data_start = $adb->pquery($sql, array());
						$rows1 = $adb->num_rows($data_start);
						if($rows1>0){
							$travel_time=$adb->query_result($data_start,0,'travel_time');
							$endt_time=$adb->query_result($data_start,0,'endt_time');
						}else{
							$travel_time="";
							$endt_time="";
						}
						if($start_time==""){
							$start_time="08:30";
						}
						if($endt_time==""){
							$endt_time="17:30";
						}
						
						if($start_time_Assistant!=""){
							$t1= strtotime($chk_case_date." ".$start_time);
							$t2= strtotime($chk_case_date." ".$endt_time);
							$SLA=$SLA+($t2 - $t1)/60;
							//echo $chk_case_date." ".$start_time." ".$chk_case_date." ".$endt_time."<br>";
						}
					}
				}
			}else{
				//echo $travel_time1_N;exit;
				if(trim($travel_time1_N)==""){
					$SLA=0;
				}else{
					$t1= strtotime($case_date);
					$t2= strtotime($endt_date1_N);
					$SLA=($t2 - $t1)/60;
				}
			}
			//echo $SLA;exit;
			$SLA1=($SLA%60);
			$SLA2=($SLA-($SLA%60))/60;
			if($SLA1>9){
				$SLA=$SLA2.":".$SLA1;	
			}else{
				$SLA=$SLA2.":0".$SLA1;	
			}
//new SLA==================================================================================================================
			
			//echo $SLA."<br>";
			//exit;
			//echo $ticketid." ".$start_date." ".$start_time."   ".$endt_date." ".$endt_time." =".$Arrive." ".$SLA."<br>";
			$sql="update aicrm_ticketcf set cf_1264='".$start_date1."', cf_1266='".$travel_time1."', cf_1265='".$Arrive."', cf_1267='".$SLA."' where ticketid='".$ticketid."' ";
			$adb->pquery($sql, array());
			
//new SLA 28-5-2014=====================================
			//หาวันที่สิ้นสุด
			$sql="
			select
			cf_1328 as travel_date,
			cf_1339 as endt_date
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
			and cf_1212 in(10,12,13,16)
			order by aicrm_quotationcf.cf_1211 DESC   limit 1
			";
			//echo $sql;exit;
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_date_N=$adb->query_result($data_start,0,'endt_date');
				$travel_date_N=$adb->query_result($data_start,0,'travel_date');
			}else{
				$endt_date_N="";
				$travel_date_N="";
			}
			//หาเวลาที่สิ้นสุด
			$sql="
			select cf_1328,cf_1217,
			cf_1217 as travel_time,
			cf_1316 as endt_time
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotation.parent_id='".$ticketid."'
			and cf_1212 in(10,12,13,16)
			ORDER BY cf_1328 DESC , cf_1316 DESC    limit 1
			";
			//echo $sql;exit;
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$endt_time_N=$adb->query_result($data_start,0,'endt_time');
				$travel_time_N=$adb->query_result($data_start,0,'travel_time');
			}else{
				$endt_time_N="";
				$travel_time_N="";
			}
			$start_date1_N=$start_date." ".$start_time;
			$endt_date1_N=$endt_date_N." ".$endt_time_N;
			$travel_time1_N=$travel_date_N." ".$travel_time_N;
			
			//echo $case_date."=>".$endt_date1_N;exit;
			$t1= strtotime($case_date);
			$t2= strtotime($endt_date1_N);		
			$SLA=($t2 - $t1)/60;
			//echo $SLA."<br>";
			/*$SLA1=($SLA%60);
			$SLA2=($SLA-($SLA%60))/60;
			if($SLA1>9){
				$SLA=$SLA2.":".$SLA1;	
			}else{
				$SLA=$SLA2.":0".$SLA1;	
			}
			echo $SLA."<br>";*/
			//หา  Appointment date
			$sql="
			select
			aicrm_quotation.quotationid
			,cf_1463 as appointment_date 
			,cf_1464 as appointment_time
			,cf_1339 as travel_date
			,cf_1316 as travel_time
			from aicrm_quotation 
			left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
			left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
			left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
			where 1
			and aicrm_crmentityJOB1.deleted =0
			and aicrm_quotationcf.cf_1463!=''
			and aicrm_quotation.parent_id='".$ticketid."'
			and cf_1212 in(10,12,13)

			order by aicrm_quotation.quotationid
			";
			//echo $sql;exit;
			$data_start = $adb->pquery($sql, array());
			$rows1 = $adb->num_rows($data_start);
			if($rows1>0){
				$SLA_N=0;
				for($i=0;$i<$rows1;$i++){
					$st=$adb->query_result($data_start,0,'appointment_date')." ".$adb->query_result($data_start,0,'appointment_time');
					$en=$adb->query_result($data_start,0,'travel_date')." ".$adb->query_result($data_start,0,'travel_time');
					//echo $st." ".$en;
					$t1= strtotime($en);
					$t2= strtotime($st);
					$SLA_N=$SLA_N+($t2 - $t1)/60;
					//echo $SLA_N;exit;
				}
			}
			/*$SLA1=($SLA_N%60);
			$SLA2=($SLA_N-($SLA_N%60))/60;
			if($SLA1>9){
				$SLA_N=$SLA2.":".$SLA1;	
			}else{
				$SLA_N=$SLA2.":0".$SLA1;	
			}
			echo $SLA_N."<br>";exit;*/
			//echo $SLA_N."<br>";
			$SLA=$SLA-$SLA_N;
			//echo $SLA;exit;
			$SLA1=($SLA%60);
			$SLA2=($SLA-($SLA%60))/60;
			if($SLA1>9){
				$SLA=$SLA2.":".$SLA1;	
			}else{
				$SLA=$SLA2.":0".$SLA1;	
			}
			//echo $SLA."<br>";exit;
			if($SLA<0){
				$SLA=0;	
			}
			$sql="update aicrm_ticketcf set cf_1267='".$SLA."' where ticketid='".$ticketid."' ";
			$adb->pquery($sql, array());
//new SLA 28-5-2014=====================================		
		}
	}//end update case
	
	//update OT======================================================
	$sql="
	select
	cf_1211 as start_date ,cf_1328 as end_date,cf_1217 as endt_time,cf_1213 as start_time,cf_1224
	,cf_1218,cf_1222
	,cf_1221,cf_1223,cf_1225,cf_1323,cf_1324,cf_1325,cf_1326,cf_1327
	from aicrm_quotation 
	left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
	left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
	left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
	where 1
	and aicrm_crmentityJOB1.deleted =0
	and aicrm_quotation.quotationid='".$focus->id."'
	order by cf_1211 ASC  limit 1
	";
	//echo $sql."<br>";
	$data_start = $adb->pquery($sql, array());
	//print_r($data_start);
	$rows1 = $adb->num_rows($data_start);
	if($rows1>0){
		$st_date=$adb->query_result($data_start,0,'start_date');
		$en_date=$adb->query_result($data_start,0,'end_date');
		$st_datetime=$adb->query_result($data_start,0,'start_date')." ".$adb->query_result($data_start,0,'start_time');
		$ed_datetime=$adb->query_result($data_start,0,'end_date')." ".$adb->query_result($data_start,0,'endt_time');
		$start_time=$adb->query_result($data_start,0,'start_time');
		$endt_time=$adb->query_result($data_start,0,'endt_time');
		$job_detail_status=$adb->query_result($data_start,0,'cf_1224');
		
		$Expense=$adb->query_result($data_start,0,'cf_1218');
		$TotalExpense=$adb->query_result($data_start,0,'cf_1222');
		$Tollway1=$adb->query_result($data_start,0,'cf_1221');
		$Tollway2=$adb->query_result($data_start,0,'cf_1223');
		$Tollway3=$adb->query_result($data_start,0,'cf_1225');
		$Tollway4=$adb->query_result($data_start,0,'cf_1323');
		$Tollway5=$adb->query_result($data_start,0,'cf_1324');
		$Tollway6=$adb->query_result($data_start,0,'cf_1325');
		$Tollway7=$adb->query_result($data_start,0,'cf_1326');
		$Tollway8=$adb->query_result($data_start,0,'cf_1327');
	}
	$t1_0830=strtotime($st_date." "."8:30:00");
	$t1_1730=strtotime($st_date." "."17:30:00");
	$t1_2400=strtotime($st_date." "."23:59:59");
	$t2_0000=strtotime($en_date." "."00:00:00");
	//echo $t1_0830," ".$t1_1730." ".$t1_2400;
	$t1= strtotime($st_datetime);
	$t2= strtotime($ed_datetime);
	$M=($t2 - $t1)/60;
	$t_chk=strtotime($en_date." ".$endt_time);
	$OT1=0;
	$OT2=0;
	$OT3=0;
	$OT4=0;
	//echo $st_datetime." ".$ed_datetime." ".$M."<br>";exit;
	for($i=0;$i<$M;$i++){
		if($i=="0"){
			$t_000000=strtotime(date('Y-m-d',strtotime($st_datetime))." "."00:00:00");
			$t_083000=strtotime(date('Y-m-d',strtotime($st_datetime))." "."08:30:00");
			$t_173000=strtotime(date('Y-m-d',strtotime($st_datetime))." "."17:30:00");
			$t_235959=strtotime(date('Y-m-d',strtotime($st_datetime))." "."23:59:59");
			$t_235959=$t_235959+1;
		
			$chk_h=check_holiday($st_datetime);
			$t2= strtotime($st_datetime);
			//echo $chk_h."==>".$st_datetime;exit;
			if($chk_h==0){//check วันหยุด
				if($t_chk>=$t2){
					if(($t2>=$t_000000) and ($t2<$t_083000)){
						//echo $date_time."<br>";
						$OT2=$OT2+1;
					}else if(($t2>=$t_083000) and ($t2<$t_173000)){
						//echo $date_time."<br>";
						$OT1=$OT1+1;
					}else if(($t2>=$t_173000) and ($t2<$t_235959)){
						//echo $date_time." ".$t2."<br>";
						$OT2=$OT2+1;
					}
				}
			}else{
				if($t_chk>=$t2){
					if(($t2>=$t_000000) and ($t2<$t_083000)){
						//echo $date_time."<br>";
						$OT4=$OT4+1;
					}else if(($t2>=$t_083000) and ($t2<$t_173000)){
						//echo $date_time."<br>";
						$OT3=$OT3+1;
					}else if(($t2>=$t_173000) and ($t2<$t_235959)){
						//echo $date_time."<br>";
						$OT4=$OT4+1;
					}
				}else{
					//echo $date_time."<br>";
				}
			}
			$date_time=(date("Y-m-d H:i:s",mktime(date('H',strtotime($st_datetime)),date('i',strtotime($st_datetime))+1,00,date('m',strtotime($st_datetime)),date('d',strtotime($st_datetime)),date('Y',strtotime($st_datetime)))));
			
		}else{
			$t_000000=strtotime(date('Y-m-d',strtotime($date_time))." "."00:00:00");
			$t_083000=strtotime(date('Y-m-d',strtotime($date_time))." "."08:30:00");
			$t_173000=strtotime(date('Y-m-d',strtotime($date_time))." "."17:30:00");
			$t_235959=strtotime(date('Y-m-d',strtotime($date_time))." "."23:59:59");
			$t_235959=$t_235959+1;			
			$chk_h=check_holiday($date_time);
			$t2= strtotime($date_time);
			if($chk_h==0){//check วันหยุด
				if($t_chk>=$t2){
					if(($t2>=$t_000000) and ($t2<$t_083000)){
						//echo $date_time."<br>";
						$OT2=$OT2+1;
					}else if(($t2>=$t_083000) and ($t2<$t_173000)){
						//echo $date_time."<br>";
						$OT1=$OT1+1;
					}else if(($t2>=$t_173000) and ($t2<$t_235959)){
						//echo $date_time." ".$t2."<br>";
						$OT2=$OT2+1;
					}
				}
			}else{
				if($t_chk>=$t2){
					if(($t2>=$t_000000) and ($t2<$t_083000)){
						//echo $date_time."<br>";
						$OT4=$OT4+1;
					}else if(($t2>=$t_083000) and ($t2<$t_173000)){
						//echo $date_time."<br>";
						$OT3=$OT3+1;
					}else if(($t2>=$t_173000) and ($t2<$t_235959)){
						//echo $date_time."<br>";
						$OT4=$OT4+1;
					}
				}else{
					//echo $date_time."<br>";
				}
			}
			$date_time=(date("Y-m-d H:i:s",mktime(date('H',strtotime($date_time)),date('i',strtotime($date_time))+1,00,date('m',strtotime($date_time)),date('d',strtotime($date_time)),date('Y',strtotime($date_time)))));
		}
	}
	//echo $OT1." ".$OT2." ".$OT3." ".$OT4;
	$OT11=($OT1%60);
	$OT22=($OT1-($OT1%60))/60;
	$OT=$OT22.":".$OT11;
	if($OT11>9){
		if($OT22>8){
			$OT1=($OT22).":".$OT11;
		}else{
			$OT1=$OT22.":".$OT11;
		}
	}else if($OT11<=9){
		if($OT22>8){
			$OT1=($OT22).":0".$OT11;
		}else{
			$OT1=$OT22.":0".$OT11;
		}
	}else{
		if($OT22>8){
			$OT1=($OT22);
		}else{
			$OT1=$OT22;
		}
	}
	//echo $OT3;
	$OT11=($OT3%60);
	$OT22=($OT3-($OT3%60))/60;
	$OT=$OT22.":".$OT11;
	
	if($OT11>9){
		if($OT22>8){
			$OT3=($OT22).":".$OT11;
		}else{
			$OT3=$OT22.":".$OT11;
		}
		
	}else if($OT11<=9){
		if($OT22>8){
			$OT3=($OT22).":0".$OT11;
		}else{
			$OT3=$OT22.":0".$OT11;
		}
	}else{
		if($OT22>8){
			$OT3=($OT22);
		}else{
			$OT3=$OT22;
		}
	}
	//echo $OT3;
	$OT11=($OT2%60);
	$OT22=($OT2-($OT2%60))/60;
	$OT=$OT22.":".$OT11;
	if($OT11>9){
		$OT2=$OT22.":".$OT11;
	}else if($OT11<=9){
		$OT2=$OT22.":0".$OT11;
	}else{
		$OT2=$OT22;
	}
		
	$OT11=($OT4%60);
	$OT22=($OT4-($OT4%60))/60;
	$OT=$OT22.":".$OT11;
	if($OT11>9){
		$OT4=$OT22.":".$OT11;
	}else if($OT11<=9){
		$OT4=$OT22.":0".$OT11;
	}else{
		$OT4=$OT22;
	}
	//echo $OT1." ".$OT2." ".$OT3." ".$OT4;
	//exit;

//ส่วน update===========================================================================================================================
	/*$Expense=$adb->query_result($data_start,0,'cf_1218');
	$TotalExpense=$adb->query_result($data_start,0,'cf_1222');*/
	$Tollway1_b=get_Tollway($Tollway1);
	$Tollway2_b=get_Tollway($Tollway2);
	$Tollway3_b=get_Tollway($Tollway3);
	$Tollway4_b=get_Tollway($Tollway4);
	$Tollway5_b=get_Tollway($Tollway5);
	$Tollway6_b=get_Tollway($Tollway6);
	$Tollway7_b=get_Tollway($Tollway7);
	$Tollway8_b=get_Tollway($Tollway8);
	
	$TotalExpense=$Expense+$Tollway1_b+$Tollway2_b+$Tollway3_b+$Tollway4_b+$Tollway5_b+$Tollway6_b+$Tollway7_b+$Tollway8_b;
	
	$sql="update aicrm_quotationcf set cf_1258='".$OT1."', cf_1259='".$OT2."', cf_1260='".$OT3."', cf_1261='".$OT4."' 
	,cf_1222='".$TotalExpense."'
	where quotationid='".$focus->id."' ";
	//echo $sql;exit;
	$adb->pquery($sql, array());
	//echo $st_datetime." ".$ed_datetime." ".$OT1." ".$OT2." ".$OT3." ".$OT4;
	
	//update call status=============
	$call_status="";
	//echo $job_detail_status;exit;
	if($job_detail_status=="Complete"){
		$call_status="2 - Completed";
	}else if($job_detail_status="Incomplete Customer" || $job_detail_status="Incomplete Part" || $job_detail_status="Incomplete Service" || $job_detail_status="Incomplete Other"){
		$call_status="3 - Incompleted";
	}
	//echo $tick_user." ".$current_user->id;
	
	$sql="
	select smownerid from aicrm_crmentity where crmid='".$focus->id."'
	";
	$data_cc= $adb->pquery($sql, array());
	//print_r($data_start);
	$rows1 = $adb->num_rows($data_cc);
	if($rows1>0){
		$job_user_id=$adb->query_result($data_cc,0,'smownerid');
	}
	if($tick_user==$job_user_id){
		$sql="update aicrm_troubletickets set status='".$call_status."' where ticketid='".$ticketid."'";
		$adb->pquery($sql, array());
	}
	
//คำนวณ Respons Time 
	$sql="
	select
	aicrm_quotation.quotationid
	,cf_1211 as start_date
	,cf_1213 as start_time
	,cf_1215 as arrived_time
	
	from aicrm_quotation 
	left join aicrm_quotationcf on aicrm_quotationcf.quotationid=aicrm_quotation.quotationid
	left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_quotation.quotationid
	left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
	where 1
	and aicrm_crmentityJOB1.deleted =0
	and aicrm_quotation.parent_id='".$ticketid."'
	and cf_1215!=''
	/*and cf_1212 in(10,13)*/
	
	GROUP BY cf_1211   
	order by aicrm_quotation.quotationid asc
	limit 1
	";
	//echo $sql;exit;
	$data_start = $adb->pquery($sql, array());
	$rows1 = $adb->num_rows($data_start);
	$t1=0;
	$t2=0;
	if($rows1>0){
		$start_date=$adb->query_result($data_start,0,'start_date');
		$start_time=$adb->query_result($data_start,0,'start_time');
		$arrived_time=$adb->query_result($data_start,0,'arrived_time');
		$t1= strtotime($case_date);
		$t2= strtotime($start_date." ".$arrived_time);
		//echo $case_date." ".$start_date." ".$arrived_time;
	}
	
	if($t2>$t1){
		$SLA=($t2 - $t1)/60;
		$SLA_chk=($t2 - $t1)/60;
		$SLA1=($SLA%60);
		$SLA2=($SLA-($SLA%60))/60;
		if($SLA1>9){
			$SLA=$SLA2.":".$SLA1;	
		}else{
			$SLA=$SLA2.":0".$SLA1;	
		}
	
		$sql="update aicrm_ticketcf set cf_1485='".$SLA."' where ticketid='".$ticketid."'";
		$adb->pquery($sql, array());
	}else{
		$sql="update aicrm_ticketcf set cf_1485=0 where ticketid='".$ticketid."'";
		$adb->pquery($sql, array());
	}
	//update relation====================================================
	$sql="
	selelct 
	*
	from aicrm_crmentityrel
	where 1
	and crmid='".$ticketid."'
	and relcrmid='".$focus->id."'
	";
	$data= $adb->pquery($sql, array());
	$rows1 = $adb->num_rows($data);
	if($rows1>0){
	}else{
		$sql="insert into aicrm_crmentityrel (crmid,module,relcrmid,relmodule)values(
		'".$ticketid."'
		,'HelpDesk'
		,'".$focus->id."'
		,'Quotation'
		)
		";	
		$adb->pquery($sql, array());
	}
	//echo  $SLA;exit;
//ส่วน update===========================================================================================================================
header("Location: index.php?action=$return_action&module=$return_module&parenttab=$parenttab&record=$return_id&viewname=$return_viewname&start=".vtlib_purify($_REQUEST['pagenumber']).$search);
function get_Tollway($Tollway_name){
	global $adb;
	$sql="select toll_way_free from tbm_toll_way  where toll_way_name='".$Tollway_name."'";
	//echo $sql;
	$data_start = $adb->pquery($sql, array());
	//print_r($data_start);
	$rows1 = $adb->num_rows($data_start);
	if($rows1>0){
		$toll_way=$adb->query_result($data_start,0,'toll_way_free');
	}else{
		$toll_way=0;	
	}
	return $toll_way;
}

function Get_H($st_date,$en_date){
	//echo $st_date." ".$en_date;
	$t1= strtotime($st_date);
	$t2= strtotime($en_date);
	$c_date=($t2-$t1)/86400;
	//echo $t1." ".$t2;exit;
	return $c_date;
	//echo ($t2-$t1)/86400;
}
function check_holiday($st_date){
	global $adb;
	$str=explode(" ",$st_date);
	//print_r($str);
	$sql="select holiday_date from tbm_holiday where holiday_date='".$str[0]."' and holiday_status='Active' ";
	//echo $sql;exit;
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
	return $chk_h;
}
?>