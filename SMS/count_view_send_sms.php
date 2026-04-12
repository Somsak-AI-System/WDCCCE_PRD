<?
session_start();
include("../config.inc.php");
include_once("../library/dbconfig.php");
include_once("../library/myFunction.php");
include_once("../library/generate_MYSQL.php");
global $generate;
$generate = new generate($dbconfig ,"DB");
ini_set('memory_limit', '1024M');
$crmid=$_REQUEST["crmid"];
$sql="select aicrm_smartsms.* from aicrm_smartsms 
		 Inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		 where aicrm_smartsms.smartsmsid='".$crmid."' and aicrm_crmentity.deleted=0";
$data =$generate->process($sql,"all");

$sqlall="select a.mobile from
(	
		select 
		aicrm_account.mobile as mobile
		from aicrm_smartsms_accountsrel
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_accountsrel.smartsmsid='".$crmid."'
		 union all
		select 
		aicrm_leadaddress.mobile as mobile
		from aicrm_smartsms_leadsrel
		left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_leadsrel.smartsmsid='".$crmid."'
		union all
		select 
		cf_2351 as mobile
		from aicrm_smartsms_opportunityrel
		left join aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		left join aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_opportunity.opportunityid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_opportunityrel.smartsmsid='".$crmid."'
		union all
	    select 
	    mobile
	    from aicrm_smartsms_contactsrel
	    left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
	    and aicrm_smartsms_contactsrel.smartsmsid='".$crmid."'
) as a 
	";
//echo $sqlall;exit();
$data1 =$generate->process($sqlall,"all");
$c_data_sms6 = count($data1);

$sqldup="
		select sum(duprecord) duprecord
		from
		(
		select Phone,count(*)-1 duprecord
		from
		(
		select concat( \"\" ,aicrm_leadaddress.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 AND aicrm_smartsms_leadsrel.leadid is not NULL
		union all
		select concat( \"\" ,aicrm_account.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL
		union all 
		select concat( \"\" ,aicrm_opportunitycf.cf_2351) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		LEFT JOIN aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		WHERE aicrm_smartsms.smartsmsid =  '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_opportunityrel.opportunityid is not NULL
		union all 
		select concat( \"\" ,aicrm_contactdetails.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
		LEFT JOIN aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL
		) alldup
		where Phone <> ''
		group by Phone
		having count(*) > 1
		) alldup

		";


$data2 =$generate->process($sqldup,"all");
$c_data_sms7 = $data2[0]['duprecord'];
/*
echo count($data2);
print_r($data2);
echo $sqldup;
*/


/*
$sqldup=" select concat( \"\" ,aicrm_leadaddress.mobile) as Phone
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
	LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 AND aicrm_smartsms_leadsrel.leadid is not NULL
	AND aicrm_leadaddress.mobile IN(select concat( \"\" ,aicrm_account.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL
		union all 
		select concat( \"\" ,aicrm_opportunitycf.cf_2351) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		LEFT JOIN aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		WHERE aicrm_smartsms.smartsmsid =  '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_opportunityrel.opportunityid is not NULL
		union all 
		select concat( \"\" ,aicrm_contactdetails.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
		LEFT JOIN aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL)

	union all
	select concat( \"\" ,aicrm_account.mobile) as Phone
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
	left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 
	AND aicrm_smartsms_accountsrel.accountid is not NULL
	AND aicrm_account.mobile IN(select concat( \"\" ,aicrm_leadaddress.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 AND aicrm_smartsms_leadsrel.leadid is not NULL
		union all 
		select concat( \"\" ,aicrm_opportunitycf.cf_2351) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		LEFT JOIN aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		WHERE aicrm_smartsms.smartsmsid =  '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_opportunityrel.opportunityid is not NULL
		union all 
		select concat( \"\" ,aicrm_contactdetails.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
		LEFT JOIN aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL)

	union all 
	select concat( \"\" ,aicrm_opportunitycf.cf_2351) as Phone
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
	LEFT JOIN aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
	WHERE aicrm_smartsms.smartsmsid =  '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_opportunityrel.opportunityid is not NULL
	AND aicrm_opportunitycf.cf_2351 IN(select concat( \"\" ,aicrm_leadaddress.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 AND aicrm_smartsms_leadsrel.leadid is not NULL
		union all 
		select concat( \"\" ,aicrm_account.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL
		union all 
		select concat( \"\" ,aicrm_contactdetails.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
		LEFT JOIN aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL)
	union all 
	
	select concat( \"\" ,aicrm_contactdetails.mobile) as Phone
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	LEFT JOIN aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL
	AND aicrm_contactdetails.mobile IN(select concat( \"\" ,aicrm_leadaddress.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 AND aicrm_smartsms_leadsrel.leadid is not NULL
		union all 
		select concat( \"\" ,aicrm_account.mobile) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL
		union all 
		select concat( \"\" ,aicrm_opportunitycf.cf_2351) as Phone
		FROM aicrm_smartsms
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		LEFT JOIN aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		WHERE aicrm_smartsms.smartsmsid =  '".$crmid."'
		AND aicrm_crmentity.deleted =0 and aicrm_smartsms_opportunityrel.opportunityid is not NULL)
	";

$data2 =$generate->process($sqldup,"all");
$c_data_sms7 = count($data2);

*/




$sqlunsubscribe=" select 
		aicrm_account.mobile as mobile
		from aicrm_smartsms_accountsrel
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and (aicrm_account.smsstatus = 'InActive' )
		and aicrm_smartsms_accountsrel.smartsmsid= '".$crmid."'
		and mobile<>''
		group by mobile
		 union
		select 
		aicrm_leadaddress.mobile as mobile
		from aicrm_smartsms_leadsrel
		left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and (aicrm_leaddetails.smsstatus  = 'InActive' )
		and aicrm_smartsms_leadsrel.smartsmsid='".$crmid."'
		and mobile<>''
		group by mobile
		union
		select 
		cf_2351 as mobile
		from aicrm_smartsms_opportunityrel
		left join aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		left join aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_opportunity.opportunityid
		where 1
		and aicrm_crmentity.deleted=0
		and (aicrm_opportunity.smsstatus  = 'InActive' )
		and aicrm_smartsms_opportunityrel.smartsmsid='".$crmid."'
		and cf_2351<>''
		group by cf_2351
		union
	    select 
	    mobile
	    from aicrm_smartsms_contactsrel
	    left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
		and (aicrm_contactdetails.smsstatus  = 'InActive' )
	    and aicrm_smartsms_contactsrel.smartsmsid='".$crmid."'
	    and mobile<>''
		group by mobile 

	";
//echo $sql11;exit();
$data3 =$generate->process($sqlunsubscribe,"all");
$c_data_sms8 = count($data3);

$FileName = "XML/data4.xml";
$FileHandle = fopen($FileName, 'w') or die("can't open file");
if(count($data)>0){
	$c_data_sms1=0;
	$c_data_sms2=0;
	$c_data_sms3=0;
	$c_data_sms4=0;
	$c_data_sms5=0;
	for($i=0;$i<count($data);$i++){
		$new_table="tbt_sms_log_smartsmsid_".$data[$i]["smartsmsid"];
		$sql="select * from ".$new_table." where smartsmsid='".$crmid."'";
		$data_sms1 =$generate->process($sql,"all");
		if(count($data_sms1)>0){
			$c_data_sms1=$c_data_sms1+count($data_sms1);
		}
		//$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and invalid_sms=2";//sms มีปัญหา
		$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and invalid_sms=1";//sms มีปัญหา
		$data_sms2 =$generate->process($sql,"all");
		if(count($data_sms2)>0){
			$c_data_sms2=$c_data_sms2+count($data_sms2);
		}
		$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and status=0 and invalid_sms=0 and date_start <= '1900-01-01'";//sms รอส่ง
		$data_sms3 =$generate->process($sql,"all");
		if(count($data_sms3)>0){
			$c_data_sms3=$c_data_sms3+count($data_sms3);
		}
		$sql="select a.mobile
from
(	
		select 
		aicrm_account.mobile as mobile
		from aicrm_smartsms_accountsrel
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_accountsrel.smartsmsid='".$crmid."'
		and mobile<>''
		and aicrm_account.smsstatus in('','Active')
		group by mobile
		 union
		select 
		aicrm_leadaddress.mobile as mobile
		from aicrm_smartsms_leadsrel
		left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_leadsrel.smartsmsid='".$crmid."'
		and mobile<>''
		and aicrm_leaddetails.smsstatus in('','Active')
		group by mobile
		union
		select 
		cf_2351 as mobile
		from aicrm_smartsms_opportunityrel
		left join aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		left join aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_opportunity.opportunityid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_opportunityrel.smartsmsid='".$crmid."'
		and cf_2351<>''
		and aicrm_opportunity.smsstatus in('','Active')
		group by cf_2351
		union
	    select 
	    mobile
	    from aicrm_smartsms_contactsrel
	    left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
	    and aicrm_smartsms_contactsrel.smartsmsid='".$crmid."'
	    and mobile<>''
	    and aicrm_contactdetails.smsstatus in('','Active')
		group by mobile 
) as a 
where LENGTH( a.mobile )= 10 or LENGTH(replace(replace(replace(a.mobile,'+66','0'),' ',''),'-','')) = 10 and left(replace(replace(replace(a.mobile,'+66','0'),' ',''),'-',''),2) in ('08','09','06') 
group by mobile ";//sms ส่งผ่าน
		$data_sms4 =$generate->process($sql,"all");
		if(count($data_sms4)>0){
			$c_data_sms4=$c_data_sms4+count($data_sms4);
		}
		$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and status=2";//sms ส่งไม่ผ่าน
		$data_sms5 =$generate->process($sql,"all");
		if(count($data_sms5)>0){
			$c_data_sms5=$c_data_sms5+count($data_sms5);
		}
	}
	
	fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	fwrite($FileHandle,'
		<chart caption="กราฟแท่งแสดงผลการส่ง SMS" yaxisname="" numberprefix="" yaxismaxvalue="" showborder="0" theme="fint">
		<set label="SMS ทั้งหมด" value="'.$c_data_sms6.'" color="b4daf8"/>
		<set label="SMS ซ้ำ" value="'.$c_data_sms7.'" color="838f98"/>
		<set label="รายการทีไม่ต้องการรับข่าวสาร" value="'.$c_data_sms8.'" color="CC0000"/>
		<set label="รายการที่มีปัญหา" value="'.$c_data_sms2.'" color="f6c221"/>
		<set label="ราการที่รอส่ง" value="'.$c_data_sms3.'" color="91bd0e"/>
		<set label="รายการที่ส่งผ่าน" value="'.$c_data_sms4.'" color="ff9551"/>
		<set label="รายการที่ส่งไม่ผ่าน" value="'.$c_data_sms5.'" color="0c9393"/>
		</chart>
	');
	fclose($FileHandle);	
}else{
	fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	fwrite($FileHandle,'<chart caption="กราฟแท่งแสดงผลการส่ง SMS" yaxisname="" numberprefix="" yaxismaxvalue="" showborder="0" theme="fint">');
	fwrite($FileHandle,'</chart>');
	fclose($FileHandle);	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Send SMS</title>
</head>
<link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
  <link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
  <link rel="stylesheet" href="css/grid.css" type="text/css" media="all">
<link rel="stylesheet" type="text/css" href="themes/icon.css">
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery.easyui.min.js"></script>
<!-- <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts/FusionCharts.js"></SCRIPT> -->
<SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/fusioncharts.js"></SCRIPT>
<SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/fusioncharts.charts.js"></SCRIPT>
<SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/themes/fusioncharts.theme.zune.js"></SCRIPT>
<script>
$.noConflict();
</script>
<!--<script type="text/javascript" src="public/javascript/zebra_datepicker.js"></script>-->
<!--<link rel="stylesheet" href="public/css/default.css" type="text/css">-->

<script>
function fnSearch(i){
	var branch_id1=jQuery("#branch_id1").combobox('getValue');
	var branch_id2=jQuery("#branch_id2").combobox('getValue');
	var year=jQuery("#year").val();
	var productgroup=jQuery("#productgroup").combobox('getValue');
	var salename=jQuery("#salename").val();
	var orderbyid=jQuery("#orderbyid").val();
	var graphno=new String(i);
	
	var jqxhr = jQuery.post("getXML1.php",
	{
		branch_id1:branch_id1,
		branch_id2:branch_id2,
		year:year,
		productgroup:productgroup,
		salename:salename,
		orderbyid:orderbyid,
		graphno:graphno
	}
	, function() {
		//alert( "success" );
		/*var win = jQuery.messager.progress({
			title:'กรุณารอสักครู่',
			msg:'กำลังโหลดข้อมูล...'
		});
		setTimeout(function(){
			changeGraph(i);
			jQuery.messager.progress('close');
		},8000)*/
	})
	.done(function() {
		//alert( "second success" );
		var win = jQuery.messager.progress({
			title:'กรุณารอสักครู่',
			msg:'กำลังโหลดข้อมูล...'
		});
		setTimeout(function(){
			changeGraph(i);
			jQuery.messager.progress('close');
		},8000)
	})
	.fail(function() {
		//alert( "error" );
	})
	.always(function() {
		//alert( "finished" );
	});
	// Perform other work here ...
	// Set another completion function for the request above
	jqxhr.always(function() {
	//alert( "second finished" );
	});
	//changeGraph(i);
}
</script>

<!--<body class="easyui-layout" >-->
<body class="header-bg">
<form name="email" method="post" id="email">
  <header>
    <div style="height:1000px;">
      <div class="container_24">
      	<div class="logo"><img src="images/logo_sms.png" alt=""></div>
      </div>
    </div>
  </header>
<div class="container_24">
    <div>
        <div class="wrapper">
             <div style="position:absolute; margin-top: -850px; background-color:#FFF; width:1000px; border:solid 5px #006093; -webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px;">
                <table cellpadding="2" cellspacing="2" width="95%" style="margin-left:14px;"> 

                </table><br/>
              <table border="1" style="border:0px solid #CCC; font-size:14px;width:950px; line-height:40px; margin-left:14px;">
            <tr style="background-color:#e1effc">
            <?
            $sql=" SELECT  * FROM tbt_report_tab_1 WHERE 1 and campaign_id='".$crmid."' ";
            $data=$generate->process($sql,"all");	
            ?>
                <td  align="left" style="font-size:18px;">ผลสรุปการส่ง <?=$data[0]['campaign_name']?></td>
                <td>&nbsp;</td>
                 <td  align="right" style="font-size:14px;"><strong>จำนวน</strong>&nbsp;</td>
            </tr>
          <tr style="border-bottom:1px dashed;border-color:#CCC;">
                <td width="85%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_001&file_name=SMS_ทั้งหมด')"/>รายชื่อที่ส่ง SMS ทั้งหมด</a></td>
                <td align="right" valign="middle"><div style="background-color:#b4daf8; width:15px; height:15px;"></div></td>
                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms6,0)?></strong>&nbsp;</td>
            </tr>

              <tr style="border-bottom:1px dashed;border-color:#CCC;">
                <td ><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_004&file_name=รายการที่ส่งผ่าน')"/>จำนวน SMS ที่ส่งผ่าน</a></td>
                <td align="right" valign="middle"><div style="background-color:#ff9551; width:15px; height:15px;"></div></td>
                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms4,0)?></strong>&nbsp;</td>
            </tr>

       </table><br/>
           </div>
      </div>
   </div>
</div>
</form> 
 <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->       
        
</body>
</html>