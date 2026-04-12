<?
include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/genarate.inc.php");
global $genarate;
$genarate = new genarate($dbconfig ,"db_voiz");

function get_sum_app($getdate,$startdt,$enddt){
	global $genarate;
	$sql="
	select 
	count(crmid),right(left(createdtime,10),2) as date
	from aicrm_applications
	left join aicrm_applicationscf on aicrm_applicationscf.applicationid=aicrm_applications.applicationid
	left join aicrm_crmentity on aicrm_applicationscf.applicationid=aicrm_crmentity.crmid 
	where  1
	and aicrm_crmentity.deleted=0
	and createdtime >= '".$startdt."'
	and createdtime <= date_add('".$enddt."',interval 1 day)
	group by right(left(createdtime,10),2)";
	//echo $sql;//exit;
	$data_app = $genarate->process($sql,"all");
	return $data_app;
}
function get_total_app($getdate,$startdt,$enddt){
	global $genarate;
	$sql="
	select 
	count(crmid)
	from aicrm_applications
	left join aicrm_applicationscf on aicrm_applicationscf.applicationid=aicrm_applications.applicationid
	left join aicrm_crmentity on aicrm_applicationscf.applicationid=aicrm_crmentity.crmid 
	where  1
	and aicrm_crmentity.deleted=0
	and createdtime < '".$startdt."'
	";
	//echo $sql;
	$data_app = $genarate->process($sql,"all");
	return $data_app;
}
function get_sum_app_new($getdate,$startdt,$enddt){
	global $genarate;
	$sql="
	select 
	count(crmid),right( left( createdtime, 7 ) , 2 ) as date
	from aicrm_applications
	left join aicrm_applicationscf on aicrm_applicationscf.applicationid=aicrm_applications.applicationid
	left join aicrm_crmentity on aicrm_applicationscf.applicationid=aicrm_crmentity.crmid 
	where  1
	and aicrm_crmentity.deleted=0
	and createdtime >= '".$startdt."'
	and createdtime <= date_add('".$enddt."',interval 1 day)
	group by right( left( createdtime, 7 ) , 2 )";
	//echo $sql;
	$data_app = $genarate->process($sql,"all");
	return $data_app;
}
function get_sum_tran($getdate,$startdt,$enddt){
	global $genarate;
	$sql="
	select 
	sum(point),right(left(trandate,10),2) as date
	from tbt_transaction
	where  1
	and tbt_transaction.flag='¤Ðá¹¹'
	and tbt_transaction.source <>'AI'
	and tbt_transaction.status ='Add'
	and trandate >= '".$startdt."'
	and trandate <= date_add('".$enddt."',interval 0 day)
	group by right(left(trandate,10),2)";
	//echo $sql;
	$data_app = $genarate->process($sql,"all");
	return $data_app;
}

function get_group($field,$where){
	global $genarate;
	//$sql="select ".$field." from  aicrm_".$field." where presence=1 ".$where." order by ".$field."";
	$sql="select ".$field." from  aicrm_".$field." where presence=1 ".$where." order by ".$field."";
	//echo $sql;
	$datagroup = $genarate->process($sql,"all");
	$bargroup=array();
	for($i=0;$i<count($datagroup);$i++){
		$bargroup[]=$datagroup[$i][0];
	}	
	return $bargroup;
}

function get_sum_group($getdate,$startdt,$enddt){
	global $genarate;
	$sql="
		SELECT aicrm_applicationscf.cf_984, sum(tran.point) as point
		FROM aicrm_applicationscf
		inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_applicationscf.applicationid
		inner join 
		(
			select
			applicationid,
			sum(point) as point
			from tbt_transaction
			where 1
			and STATUS = 'ADD'
			AND source <> 'AI'
			AND trandate >= '".$startdt."'
			AND trandate <= date_add('".$enddt."', INTERVAL 0 DAY )
			group by applicationid
		)as tran on tran.applicationid=aicrm_applicationscf.applicationid
		WHERE 1
		and aicrm_crmentity.deleted=0
		and aicrm_applicationscf.cf_984 <> ''
		AND aicrm_applicationscf.cf_984 <> '--None--'
		GROUP BY aicrm_applicationscf.cf_984	
		order by cf_984
	";
	//echo $sql;
	$data_app = $genarate->process($sql,"all");
	return $data_app;
}

function get_sum_group1($getdate,$startdt,$enddt){
	global $genarate;
	$sql="
	select 
	aicrm_applicationscf.cf_984,sum(cf_936)
	from aicrm_applications
	left join aicrm_applicationscf on aicrm_applicationscf.applicationid=aicrm_applications.applicationid
	left join aicrm_crmentity on aicrm_applicationscf.applicationid=aicrm_crmentity.crmid 
	where  1
	and aicrm_crmentity.deleted=0
	and createdtime >= '".$startdt."'
	and createdtime <= date_add('".$enddt."',interval 1 day)
	and (aicrm_applicationscf.cf_984<>'' and aicrm_applicationscf.cf_984<>'--None--')
	group by aicrm_applicationscf.cf_984";
	//echo $sql;
	$data_app = $genarate->process($sql,"all");
	return $data_app;
}

?>