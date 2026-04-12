<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('modules/Reports/Reports.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
global $adb;
global $log,$current_user;
$reportid = vtlib_purify($_REQUEST["record"]);

//<<<<<<<selectcolumn>>>>>>>>>
$selectedcolumnstring = $_REQUEST["selectedColumnsString"];
//<<<<<<<selectcolumn>>>>>>>>>

//<<<<<<<reportsortcol>>>>>>>>>
$sort_by1 = vtlib_purify($_REQUEST["Group1"]);
$sort_order1 = vtlib_purify($_REQUEST["Sort1"]);
$sort_by2 = vtlib_purify($_REQUEST["Group2"]);
$sort_order2 = vtlib_purify($_REQUEST["Sort2"]);
$sort_by3 = vtlib_purify($_REQUEST["Group3"]);
$sort_order3 = vtlib_purify($_REQUEST["Sort3"]);
//<<<<<<<reportsortcol>>>>>>>>>
$selectedcolumns = explode(";",$selectedcolumnstring);
if(!in_array($sort_by1,$selectedcolumns)){
	$selectedcolumns[] = $sort_by1;
}
if(!in_array($sort_by2,$selectedcolumns)){
	$selectedcolumns[] = $sort_by2;
}
if(!in_array($sort_by3,$selectedcolumns)){
	$selectedcolumns[] = $sort_by3;
}
//<<<<<<<reportmodules>>>>>>>>>
$pmodule = vtlib_purify($_REQUEST["primarymodule"]);
$smodule = vtlib_purify($_REQUEST["secondarymodule"]);
//<<<<<<<reportmodules>>>>>>>>>

//<<<<<<<report>>>>>>>>>
$reportname = vtlib_purify($_REQUEST["reportName"]);
$reportdescription = vtlib_purify($_REQUEST["reportDesc"]);
$reporttype = vtlib_purify($_REQUEST["reportType"]);
$folderid = vtlib_purify($_REQUEST["folder"]);
//<<<<<<<report>>>>>>>>>

//<<<<<<<standarfilters>>>>>>>>>
$stdDateFilterField = vtlib_purify($_REQUEST["stdDateFilterField"]);
$stdDateFilter = vtlib_purify($_REQUEST["stdDateFilter"]);
$startdate = getDBInsertDateValue($_REQUEST["startdate"]);
$enddate = getDBInsertDateValue($_REQUEST["enddate"]);
//<<<<<<<standardfilters>>>>>>>>>

//<<<<<<<shared entities>>>>>>>>>
$sharetype = vtlib_purify($_REQUEST["stdtypeFilter"]);
$shared_entities = vtlib_purify($_REQUEST["selectedColumnsStr"]);
//<<<<<<<shared entities>>>>>>>>>

//<<<<<<<columnstototal>>>>>>>>>>
$allKeys = array_keys($_REQUEST);
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 3);
   if($string == "cb:")
   {
	   $columnstototal[] = $allKeys[$i];
   }
}
//<<<<<<<columnstototal>>>>>>>>>

//<<<<<<<advancedfilter>>>>>>>>
$allKeys = array_keys($_REQUEST);
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 4);
   if($string == "fcol")
   {
	   $adv_filter_col[] = $_REQUEST[$allKeys[$i]];
   }
}
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 3);
   if($string == "fop")
   {
           $adv_filter_option[] = $_REQUEST[$allKeys[$i]];
   }
}
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 4);
   if($string == "fval")
   {
           $adv_filter_value[] = $_REQUEST[$allKeys[$i]];
   }
}
//<<<<<<<advancedfilter>>>>>>>>
if($reportid == "")
{
	$genQueryId = $adb->getUniqueID("aicrm_selectquery");
	if($genQueryId != "")
	{
		$iquerysql = "insert into aicrm_selectquery (QUERYID,STARTINDEX,NUMOFOBJECTS) values (?,?,?)";
		$iquerysqlresult = $adb->pquery($iquerysql, array($genQueryId,0,0));
		$log->info("Reports :: Save->Successfully saved aicrm_selectquery");
		if($iquerysqlresult!=false)
		{
			//<<<<step2 aicrm_selectcolumn>>>>>>>>
			if(!empty($selectedcolumns))
			{
				for($i=0 ;$i<count($selectedcolumns);$i++)
				{
					if(!empty($selectedcolumns[$i])){
						$icolumnsql = "insert into aicrm_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (?,?,?)";
						$icolumnsqlresult = $adb->pquery($icolumnsql, array($genQueryId,$i,$selectedcolumns[$i]));
					}
				}
			}
			if($shared_entities != "")
			{
				if($sharetype == "Shared")
				{
					$selectedcolumn = explode(";",$shared_entities);
					for($i=0 ;$i< count($selectedcolumn) -1 ;$i++)
					{
						$temp = split("::",$selectedcolumn[$i]);
						$icolumnsql = "insert into aicrm_reportsharing (reportid,shareid,setype) values (?,?,?)";
						$icolumnsqlresult = $adb->pquery($icolumnsql, array($genQueryId,$temp[1],$temp[0]));
					}
				}
			}
			$log->info("Reports :: Save->Successfully saved aicrm_selectcolumn");
			//<<<<step2 aicrm_selectcolumn>>>>>>>>

			if($genQueryId != "")
			{
				$ireportsql = "insert into aicrm_report (REPORTID,FOLDERID,REPORTNAME,DESCRIPTION,REPORTTYPE,QUERYID,STATE,OWNER,SHARINGTYPE) values (?,?,?,?,?,?,?,?,?)";
				$ireportparams = array($genQueryId, $folderid, $reportname, $reportdescription, $reporttype, $genQueryId,'CUSTOM',$current_user->id,$sharetype);
				$ireportresult = $adb->pquery($ireportsql, $ireportparams);
				$log->info("Reports :: Save->Successfully saved aicrm_report");
				if($ireportresult!=false)
				{
					//<<<<reportmodules>>>>>>>
					$ireportmodulesql = "insert into aicrm_reportmodules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) values (?,?,?)";
					$ireportmoduleresult = $adb->pquery($ireportmodulesql, array($genQueryId, $pmodule, $smodule));
					$log->info("Reports :: Save->Successfully saved aicrm_reportmodules");
					//<<<<reportmodules>>>>>>>

					//<<<<step3 aicrm_reportsortcol>>>>>>>
					if($sort_by1 != "")
					{
						$sort_by1sql = "insert into aicrm_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (?,?,?,?)";
						$sort_by1result = $adb->pquery($sort_by1sql, array(1, $genQueryId, $sort_by1, $sort_order1));
					}
					if($sort_by2 != "")
					{
						$sort_by2sql = "insert into aicrm_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (?,?,?,?)";
						$sort_by2result = $adb->pquery($sort_by2sql, array(2,$genQueryId,$sort_by2,$sort_order2));
					}
					if($sort_by3 != "")
					{
						$sort_by3sql = "insert into aicrm_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (?,?,?,?)";
						$sort_by3result = $adb->pquery($sort_by3sql, array(3,$genQueryId,$sort_by3,$sort_order3));
					}
					$log->info("Reports :: Save->Successfully saved aicrm_reportsortcol");
					//<<<<step3 aicrm_reportsortcol>>>>>>>

					//<<<<step5 standarfilder>>>>>>>
					$ireportmodulesql = "insert into aicrm_reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (?,?,?,?,?)";
					$ireportmoduleresult = $adb->pquery($ireportmodulesql, array($genQueryId, $stdDateFilterField, $stdDateFilter, $startdate, $enddate));
					$log->info("Reports :: Save->Successfully saved aicrm_reportdatefilter");
					//<<<<step5 standarfilder>>>>>>>

					//<<<<step4 columnstototal>>>>>>>
					for ($i=0;$i<count($columnstototal);$i++)
					{
						$ireportsummarysql = "insert into aicrm_reportsummary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) values (?,?,?)";
						$ireportsummaryresult = $adb->pquery($ireportsummarysql, array($genQueryId, $i, $columnstototal[$i]));
					}
					$log->info("Reports :: Save->Successfully saved aicrm_reportsummary");
					//<<<<step4 columnstototal>>>>>>>

					//<<<<step5 advancedfilter>>>>>>>
					for ($i=0;$i<count($adv_filter_col);$i++)
					{
						$col = explode(":",$adv_filter_col[$i]);
						$temp_val = explode(",",$adv_filter_value[$i]);
						if(($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || ($col[4] == 'DT')) && ($col[4] != '' && $adv_filter_value[$i] != '' ))
						{
							$val = Array();
							for($x=0;$x<count($temp_val);$x++)
							{
								list($temp_date,$temp_time) = explode(" ",$temp_val[$x]);
								$temp_date = getDBInsertDateValue(trim($temp_date));
								$val[$x] =$temp_date;
								$adv_filter_value[$i] = $val[$x];
							}
						}

						$irelcriteriasql = "insert into aicrm_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (?,?,?,?,?)";
						$irelcriteriaresult = $adb->pquery($irelcriteriasql, array($genQueryId, $i, $adv_filter_col[$i], $adv_filter_option[$i], $adv_filter_value[$i]));
					}
					$log->info("Reports :: Save->Successfully saved aicrm_relcriteria");
					//<<<<step5 advancedfilter>>>>>>>

				}else
				{
					$errormessage = "<font color='red'><B>Error Message<ul>
						<li><font color='red'>Error while inserting the record</font>
						</ul></B></font> <br>" ;
					echo $errormessage;
					die;
				}
			}
		}else
		{
			$errormessage = "<font color='red'><B>Error Message<ul>
				<li><font color='red'>Error while inserting the record</font>
				</ul></B></font> <br>" ;
			echo $errormessage;
			die;
		}
		echo '<script>window.opener.location.href =window.opener.location.href;self.close();</script>';
	}
}else
{
	if($reportid != "")
	{
		if(!empty($selectedcolumns))
		{
			$idelcolumnsql = "delete from aicrm_selectcolumn where queryid=?";
			$idelcolumnsqlresult = $adb->pquery($idelcolumnsql, array($reportid));
			if($idelcolumnsqlresult != false)
			{
				for($i=0 ;$i<count($selectedcolumns);$i++)
				{
					if(!empty($selectedcolumns[$i])){
						$icolumnsql = "insert into aicrm_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (?,?,?)";
						$icolumnsqlresult = $adb->pquery($icolumnsql, array($reportid,$i,$selectedcolumns[$i]));
					}
				}
			}
		}
		$delsharesqlresult = $adb->pquery("DELETE FROM aicrm_reportsharing WHERE reportid=?", array($reportid));
		if($delsharesqlresult != false  && $sharetype=="Shared" && $shared_entities!='')
		{
			$selectedcolumn = explode(";",$shared_entities);
			for($i=0 ;$i< count($selectedcolumn) -1 ;$i++)
			{
				$temp = split("::",$selectedcolumn[$i]);
				$icolumnsql = "INSERT INTO aicrm_reportsharing (reportid,shareid,setype) VALUES (?,?,?)";
				$icolumnsqlresult = $adb->pquery($icolumnsql, array($reportid,$temp[1],$temp[0]));
			}
		}
		
		//<<<<reportmodules>>>>>>>
		$ireportmodulesql = "UPDATE aicrm_reportmodules SET primarymodule=?,secondarymodules=? WHERE reportmodulesid=?";
		$ireportmoduleresult = $adb->pquery($ireportmodulesql, array($pmodule, $smodule,$reportid));
		$log->info("Reports :: Save->Successfully saved aicrm_reportmodules");
		//<<<<reportmodules>>>>>>>
		
		$ireportsql = "update aicrm_report set REPORTNAME=?, DESCRIPTION=?, REPORTTYPE=?, SHARINGTYPE=? where REPORTID=?";
		$ireportparams = array($reportname, $reportdescription, $reporttype, $sharetype, $reportid);
		$ireportresult = $adb->pquery($ireportsql, $ireportparams);
		$log->info("Reports :: Save->Successfully saved aicrm_report");

		$idelreportsortcolsql = "delete from aicrm_reportsortcol where reportid=?";
		$idelreportsortcolsqlresult = $adb->pquery($idelreportsortcolsql, array($reportid));
		$log->info("Reports :: Save->Successfully deleted aicrm_reportsortcol");

		if($idelreportsortcolsqlresult!=false)
		{
			//<<<<step3 aicrm_reportsortcol>>>>>>>
			if($sort_by1 != "")
			{
				$sort_by1sql = "insert into aicrm_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (?,?,?,?)";
				$sort_by1result = $adb->pquery($sort_by1sql, array(1, $reportid, $sort_by1, $sort_order1));
			}
			if($sort_by2 != "")
			{
				$sort_by2sql = "insert into aicrm_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (?,?,?,?)";
				$sort_by2result = $adb->pquery($sort_by2sql, array(2, $reportid, $sort_by2, $sort_order2));
			}
			if($sort_by3 != "")
			{
				$sort_by3sql = "insert into aicrm_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (?,?,?,?)";
				$sort_by3result = $adb->pquery($sort_by3sql, array(3, $reportid, $sort_by3, $sort_order3));
			}
			$log->info("Reports :: Save->Successfully saved aicrm_reportsortcol");
			//<<<<step3 aicrm_reportsortcol>>>>>>>

			$idelreportdatefiltersql = "delete from aicrm_reportdatefilter where datefilterid=?";
			$idelreportdatefiltersqlresult = $adb->pquery($idelreportdatefiltersql, array($reportid));

			//<<<<step5 standarfilder>>>>>>>
			$ireportmodulesql = "insert into aicrm_reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (?,?,?,?,?)";
			$ireportmoduleresult = $adb->pquery($ireportmodulesql, array($reportid, $stdDateFilterField, $stdDateFilter, $startdate, $enddate));
			$log->info("Reports :: Save->Successfully saved aicrm_reportdatefilter");
			//<<<<step5 standarfilder>>>>>>>

			//<<<<step4 columnstototal>>>>>>>
			$idelreportsummarysql = "delete from aicrm_reportsummary where reportsummaryid=?";
			$idelreportsummarysqlresult = $adb->pquery($idelreportsummarysql, array($reportid));

			for ($i=0;$i<count($columnstototal);$i++)
			{
				$ireportsummarysql = "insert into aicrm_reportsummary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) values (?,?,?)";
				$ireportsummaryresult = $adb->pquery($ireportsummarysql, array($reportid, $i, $columnstototal[$i]));
			}
			$log->info("Reports :: Save->Successfully saved aicrm_reportsummary");
			//<<<<step4 columnstototal>>>>>>>


			//<<<<step5 advancedfilter>>>>>>>

			$idelrelcriteriasql = "delete from aicrm_relcriteria where queryid=?";
			$idelrelcriteriasqlresult = $adb->pquery($idelrelcriteriasql, array($reportid));

			for ($i=0;$i<count($adv_filter_col);$i++)
			{
				$col = explode(":",$adv_filter_col[$i]);
				$temp_val = explode(",",$adv_filter_value[$i]);
				if(($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || ($col[4] == 'DT')) && ($col[4] != '' && $adv_filter_value[$i] != '' ))
				{
					$val = Array();
					for($x=0;$x<count($temp_val);$x++)
					{
						list($temp_date,$temp_time) = explode(" ",$temp_val[$x]);
						$temp_date = getDBInsertDateValue(trim($temp_date));
						if(trim($temp_time) != '')
							$temp_date .= ' '.$temp_time;
						$val[$x] = $temp_date;
						$adv_filter_value[$i] = $val[$x];
					}
				}
				//since we are using pquery, we don't need to add quote to the values
				//else
				//$adv_filter_value[$i] = $adb->quote($adv_filter_value[$i]);

				$irelcriteriasql = "insert into aicrm_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (?,?,?,?,?)";
				$irelcriteriaresult = $adb->pquery($irelcriteriasql, array($reportid, $i, $adv_filter_col[$i], $adv_filter_option[$i], $adv_filter_value[$i]));
			}
			$log->info("Reports :: Save->Successfully saved aicrm_relcriteria");
			//<<<<step5 advancedfilter>>>>>>>

		}else
		{
			$errormessage = "<font color='red'><B>Error Message<ul>
				<li><font color='red'>Error while inserting the record</font>
				</ul></B></font> <br>" ;
			echo $errormessage;
			die;
		}
	}else
	{
		$errormessage = "<font color='red'><B>Error Message<ul>
			<li><font color='red'>Error while inserting the record</font>
			</ul></B></font> <br>" ;
		echo $errormessage;
		die;
	}
	echo '<script>window.opener.location.href = window.opener.location.href;self.close();</script>';
}
?>