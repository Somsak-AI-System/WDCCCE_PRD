<?
session_start();
ini_set('memory_limit', '1024M');

require_once("../config.inc.php");
require_once("../library/dbconfig.php");
require_once("../library/myFunction.php");
require_once("../library/myLibrary_mysqli.php");
require_once("../library/Library_excel.php");

$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

function Datediff($datefrom,$dateto){
	$startDate = strtotime($datefrom);
	$lastDate = strtotime($dateto);
	$differnce = $startDate - $lastDate;
	$differnce = ($differnce / (60*60*24)); //กรณืที่ต้องการให้ return ค่าเป็นวันนะครับ
	return $differnce;
}	

$report_no=$_REQUEST["report_no"];
$crmid=$_REQUEST["crmid"];

$param_header="Module,CRMID,Name,Phone,Send-Date";
$sql="select smartsmsid from aicrm_smartsms where smartsmsid='".$crmid."' limit 1";	
$data1 = $myLibrary_mysqli->Select($sql1);

if(count($data1)>0){
	$table="tbt_sms_log_smartsmsid_".$data1[0]['smartsmsid'];
	$sql="select from_module as Module ,from_id as CRMID ,to_name as Name,concat( \"'\" ,to_phone) as Phone ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date  from ".$table ." where 1";
}

if($report_no=="p1_001"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql="select 'Lead' as Module, aicrm_smartsms_leadsrel.leadid as CRMID, CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as Name, concat( \"'\" ,aicrm_leaddetails.mobile) as Phone
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
	LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_leadsrel.leadid is not NULL
	union all

	select 'Account' as Module, aicrm_smartsms_accountsrel.accountid as CRMID, aicrm_account.accountname as Name, concat( \"'\" ,aicrm_account.mobile) as Phone
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
	left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL
	union all 
	
	select 'Contact' as Module, aicrm_smartsms_contactsrel.contactid as CRMID, CONCAT(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as Name, concat( \"'\" ,aicrm_contactdetails.mobile) as Phone
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	LEFT JOIN aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL
	";
	//echo $sql;exit();
}else if($report_no=="p1_002"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" AND smartsmsid ='".$crmid."' AND invalid_sms=1";
	
}else if($report_no=="p1_003"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" AND status =0 AND invalid_sms =0";

}else if($report_no=="p1_004"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" and status=1";
}else if($report_no=="p1_005"){
	$param_header="Module,CRMID,Name,Phone,Send-Date,Message";
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	
	$sql="select  ".$table .".from_module as  Module , ".$table .".from_id as CRMID, ".$table .".to_name as Name, concat( \"'\" ,".$table .".to_phone) as Phone, DATE_FORMAT( ".$table .".date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date,
	tbt_sms_log_smartsms.message as Message  
	from ".$table ." 
	INNER JOIN  tbt_sms_log_smartsms on tbt_sms_log_smartsms.smartsmsid = ".$table.".smartsmsid
	AND tbt_sms_log_smartsms.to_phone = ".$table.".to_phone
	where 1 and tbt_sms_log_smartsms.smartsmsid = ".$crmid." and tbt_sms_log_smartsms.status = 2   
	and ".$table .".status = 2
	 "; 
}else if($report_no=="p1_006"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql="select tbt_sms_log_smartsmsid_".$crmid.".from_module as Module, aicrm_smartsms_leadsrel.leadid as CRMID, tbt_sms_log_smartsmsid_".$crmid.".to_name as Name, concat( \"'\" ,aicrm_leaddetails.mobile) as Phone, DATE_FORMAT( tbt_sms_log_smartsmsid_".$crmid.".date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date
	FROM aicrm_smartsms 
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN tbt_sms_log_smartsmsid_".$crmid." ON tbt_sms_log_smartsmsid_".$crmid.".smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_leaddetails on aicrm_leaddetails.leadid=aicrm_smartsms_leadsrel.leadid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 
	AND aicrm_smartsms_leadsrel.leadid is not NULL
	AND tbt_sms_log_smartsmsid_".$crmid.".from_module = 'Lead'
	AND aicrm_smartsms_leadsrel.leadid NOT IN(select tbt_sms_log_smartsmsid_".$crmid.".from_id FROM tbt_sms_log_smartsmsid_".$crmid." Inner JOIN aicrm_smartsms ON aicrm_smartsms.smartsmsid = tbt_sms_log_smartsmsid_".$crmid.".smartsmsid)

	union all
	select tbt_sms_log_smartsmsid_".$crmid.".from_module as Module, aicrm_smartsms_accountsrel.accountid as CRMID, tbt_sms_log_smartsmsid_".$crmid.".to_name as Name, concat( \"'\" ,aicrm_account.mobile) as Phone, DATE_FORMAT( tbt_sms_log_smartsmsid_".$crmid.".date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date
	FROM aicrm_smartsms
	inner JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid 
	inner JOIN aicrm_account on aicrm_account.accountid=aicrm_smartsms_accountsrel.accountid
	LEFT JOIN tbt_sms_log_smartsmsid_".$crmid." ON tbt_sms_log_smartsmsid_".$crmid.".smartsmsid = aicrm_smartsms.smartsmsid 
	AND tbt_sms_log_smartsmsid_".$crmid.".from_id = aicrm_smartsms_accountsrel.accountid AND tbt_sms_log_smartsmsid_".$crmid.".from_module = 'Account'
	Inner JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."' AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL

	union all
	select tbt_sms_log_smartsmsid_".$crmid.".from_module as Module, aicrm_smartsms_contactsrel.contactid as CRMID, tbt_sms_log_smartsmsid_".$crmid.".to_name as Name, concat( \"'\" ,aicrm_contactdetails.mobile) as Phone, DATE_FORMAT( tbt_sms_log_smartsmsid_".$crmid.".date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date
	FROM aicrm_smartsms
	inner JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
	inner JOIN aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_smartsms_contactsrel.contactid
	LEFT JOIN tbt_sms_log_smartsmsid_59833 ON tbt_sms_log_smartsmsid_59833.smartsmsid = aicrm_smartsms.smartsmsid
	AND tbt_sms_log_smartsmsid_59833.from_id = aicrm_smartsms_contactsrel.contactid
	AND tbt_sms_log_smartsmsid_59833.from_module = 'Contacts'
	Inner JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."' AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL
	";
	/*union all 
	select tbt_sms_log_smartsmsid_".$crmid.".from_module as Module, aicrm_smartsms_contactsrel.opportunityid as CRMID, tbt_sms_log_smartsmsid_".$crmid.".to_name as Name, concat( \"'\" ,aicrm_opportunitycf.cf_2351) as Phone, DATE_FORMAT( tbt_sms_log_smartsmsid_".$crmid.".date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN tbt_sms_log_smartsmsid_".$crmid." ON tbt_sms_log_smartsmsid_".$crmid.".smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_opportunitycf on aicrm_opportunitycf.opportunityid=aicrm_smartsms_opportunityrel.opportunityid
	WHERE aicrm_smartsms.smartsmsid =  '".$crmid."'
	AND aicrm_crmentity.deleted =0 
	AND aicrm_smartsms_opportunityrel.opportunityid is not NULL
	AND tbt_sms_log_smartsmsid_".$crmid.".from_module = 'Opportunity'
	AND aicrm_smartsms_opportunityrel.opportunityid NOT IN(select tbt_sms_log_smartsmsid_".$crmid.".from_id FROM tbt_sms_log_smartsmsid_".$crmid." Inner JOIN aicrm_smartsms ON aicrm_smartsms.smartsmsid = tbt_sms_log_smartsmsid_".$crmid.".smartsmsid)*/

	/*select
	    setype as Module,
	    crmid as CRMID,
	    concat(firstname,' ',lastname) as Name, 
	    mobile as Phone
	    from aicrm_smartsms_contactsrel
	    left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
	    and aicrm_contactdetails.smsstatus != 'Active'
	    and aicrm_smartsms_contactsrel.smartsmsid='".$crmid."'
	    and mobile<>''
		group by mobile */
}else if($report_no=="p1_008"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql="select
		setype as Module,
		crmid as CRMID,
		accountname as Name,
		aicrm_account.mobile as Phone
		from aicrm_smartsms_accountsrel
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_account.smsstatus != 'Active'
		and aicrm_smartsms_accountsrel.smartsmsid= '".$crmid."'
		and mobile<>''
		group by mobile
		 union
		select 
		setype as Module,
		crmid as CRMID,
		concat(firstname,' ',lastname) as Name,
		aicrm_leaddetails.mobile as Phone
		from aicrm_smartsms_leadsrel
		left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_leaddetails.smsstatus != 'Active'
		and aicrm_smartsms_leadsrel.smartsmsid='".$crmid."'
		and mobile<>''
		group by mobile
		union
	    select
	    setype as Module,
	    crmid as CRMID,
	    concat(firstname,' ',lastname) as Name, 
	    mobile as Phone
	    from aicrm_smartsms_contactsrel
	    left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
	    and aicrm_contactdetails.smsstatus != 'Active'
	    and aicrm_smartsms_contactsrel.smartsmsid='".$crmid."'
	    and mobile<>''
		group by mobile 
	";
	
}
 	// echo $sql; exit;
	$data = $myLibrary_mysqli->Select($sql);
	$myLib = new myExcel();
	if(!empty($data)){
		$title = $param_filesname;
		$a_resonse = $myLib->gen_excel($data,$title);
	}else{
		echo "<script>alert('No Data');</script>";
		echo "<script>window.location.replace('".$site_URL."SMS/view_send_sms.php?crmid=".$crmid."');</script>";
	}
?> 