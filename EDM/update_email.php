<?
session_start();
include("/home/ghbuser/domains/ghb-email.com/public_html/library/dbconfig.php");
include("/home/ghbuser/domains/ghb-email.com/public_html/library/genarate.inc.php");
$genarate = new genarate($dbconfig ,"db_voiz");

$sql="
select 
*
from tbt_email_log
where 
`crmid`=0
";
$data = $genarate->process($sql,"all");
for($i=0;$i<count($data);$i++){
	$id=$data[$i][0];
	$to_email=$data[$i][3];
	
	$sql="
	SELECT `from_id`,`invalid_email`,`unsubscribe` FROM `tbt_email_log_campaignid_2896110_marketid_3` WHERE 1
	and `emailtargetlistid`=2896112
	and  to_email  = '".$to_email."'
	";
	$data1 = $genarate->process($sql,"all");
	if(count($data1)>0){
		$from_id=$data1[0][0];
		$invalid_email=$data1[0][1];
		$unsubscribe=$data1[0][2];
		
		//update email id
		$sql="update tbt_email_log set crmid ='".$from_id."' where id='".$id."' limit 1";
		if($genarate->query($sql,"all")){};	
	}
}
?>