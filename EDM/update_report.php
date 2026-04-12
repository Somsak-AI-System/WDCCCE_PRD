<?php
session_start();
include("/home/ghbuser/domains/ghb-email.com/public_html/library/dbconfig.php");
include("/home/ghbuser/domains/ghb-email.com/public_html/library/genarate.inc.php");
global $genarate;
$genarate = new genarate($dbconfig ,"db_voiz");
//เอาข้อมูลที่ open มาทำ check ว่าเอาไปทำรายงาน open
$sql="SELECT uniqueid
FROM tbt_email_log_campaignid_3132526_marketid_12_open
LEFT JOIN tbt_email_log_campaignid_3132526_marketid_12 ON `tbt_email_log_campaignid_3132526_marketid_12`.`id` = `tbt_email_log_campaignid_3132526_marketid_12_open`.`uniqueid`
WHERE active =1
AND report = '1'
AND tbt_email_log_campaignid_3132526_marketid_12_open.emailtargetlistid =3132524
AND tbt_email_log_campaignid_3132526_marketid_12.bounce =0
AND tbt_email_log_campaignid_3132526_marketid_12_open.bounce =0
AND tbt_email_log_campaignid_3132526_marketid_12_open.email =0
AND dateopen < '2015-03-09 18:00:00'
group by uniqueid
";
$data = $genarate->process($sql,"all");
for($i=0;$i<count($data);$i++){
	$sql="update tbt_email_log_campaignid_3132526_marketid_12_report set  report_true =1 where id='".$data[$i][0]."' 	";
	$genarate->query($sql);
}

//เอาข้อมูลที่ click มาทำ check ว่าเอาไปทำรายงาน open
$sql="SELECT uniqueid
FROM tbt_email_log_campaignid_3132526_marketid_12_click
LEFT JOIN tbt_email_log_campaignid_3132526_marketid_12 ON `tbt_email_log_campaignid_3132526_marketid_12`.`id` = `tbt_email_log_campaignid_3132526_marketid_12_click`.`uniqueid`
WHERE active =1
AND report = '1'
AND tbt_email_log_campaignid_3132526_marketid_12_click.emailtargetlistid =3132524
AND tbt_email_log_campaignid_3132526_marketid_12.bounce =0
AND tbt_email_log_campaignid_3132526_marketid_12_click.bounce =0
AND tbt_email_log_campaignid_3132526_marketid_12_click.email =0
AND dateclick < '2015-03-09 18:00:00'
group by uniqueid
";
$data = $genarate->process($sql,"all");
for($i=0;$i<count($data);$i++){
	$sql="update tbt_email_log_campaignid_3132526_marketid_12_report set  report_true =1,click_true=1 where id='".$data[$i][0]."' 	";
	$genarate->query($sql);
}

//random หาจำนวนที่ขาดไป
$sql="update  `tbt_email_log_campaignid_3132526_marketid_12_report`  set report_true=1
WHERE 1 
AND  `report_true` =0
AND  `bounce` =0
ORDER BY RAND( ) 
LIMIT 2204";
//$genarate->query($sql);
?>