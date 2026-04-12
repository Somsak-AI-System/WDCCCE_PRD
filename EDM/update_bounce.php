<?
session_start();
include("/home/ghbuser/domains/ghb-email.com/public_html/library/dbconfig.php");
include("/home/ghbuser/domains/ghb-email.com/public_html/library/genarate.inc.php");
$genarate = new genarate($dbconfig ,"db_voiz");

//1.เพิ่มฟิวส์
/*$sql="
CREATE TABLE `tbt_email_log_campaignid_3132526_marketid_12_report` (
  `id` int(11) NOT NULL,
  `emailtargetlistid` int(19) NOT NULL,
  `from_module` varchar(250) NOT NULL,
  `from_id` int(19) NOT NULL,
  `to_name` varchar(250) NOT NULL,
  `to_email` varchar(250) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `active` int(20) DEFAULT '0' COMMENT 'ใช้งานหรือไม่ใช้งาน',
  `report` int(20) DEFAULT '0' COMMENT 'ออกรายงานหรือไม่ออก',
  `unsubscribe` int(20) DEFAULT '0' COMMENT 'งดรับเมล์',
  `unsubscribe_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'วันยกเลิกข่าวสาร',
  `bounce` varchar(1) NOT NULL DEFAULT '0',
  `click_true` varchar(1) NOT NULL DEFAULT '0',
  `report_true` varchar(1) NOT NULL DEFAULT '0',
  KEY `id` (`id`),
  KEY `to_email` (`to_email`),
  KEY `active` (`active`),
  KEY `report` (`report`)
) ENGINE=MyISAM DEFAULT CHARSET=tis620;
";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE  `tbt_email_log_campaignid_3132526_marketid_12` ADD INDEX (  `to_email` );";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE  `tbt_email_log_campaignid_3132526_marketid_12` ADD INDEX (  `active` );";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE  `tbt_email_log_campaignid_3132526_marketid_12` ADD INDEX (  `report` );";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE  `tbt_email_log_campaignid_3132526_marketid_12_open` ADD INDEX (  `uniqueid` );";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE  `tbt_email_log_campaignid_3132526_marketid_12_click` ADD INDEX (  `uniqueid` );";
if($genarate->query($sql,"all")){};	

$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12` ADD `bounce` INT( 2 ) NOT NULL ;";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12` ADD `chk` INT( 2 ) NOT NULL ;";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_open` ADD `bounce` INT( 2 ) NOT NULL ;";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_click` ADD `bounce` INT( 2 ) NOT NULL ;";
if($genarate->query($sql,"all")){};	

$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_open` ADD `emailtargetlistid` int(19) NOT NULL ;";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_open` ADD `from_id` int(19) NOT NULL ;";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_open` ADD `domain_name` varchar(150) NOT NULL ;";
if($genarate->query($sql,"all")){};	

$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_click` ADD `emailtargetlistid` int(19) NOT NULL ;";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_click` ADD `from_id` int(19) NOT NULL ;";
if($genarate->query($sql,"all")){};	
$sql="ALTER TABLE `tbt_email_log_campaignid_3132526_marketid_12_click` ADD `domain_name` varchar(150) NOT NULL ;";
if($genarate->query($sql,"all")){};	
exit;
*/
//2.update ว่าข้อมูลที่ open หรือ click เป็นข้อมูลที่เกิดจาก email ที่เป็น bounce

/*$sql="update tbt_email_log_campaignid_3132526_marketid_12_open set email =0,bounce=0 ";
if($genarate->query($sql,"all")){};	
$sql="update tbt_email_log_campaignid_3132526_marketid_12_click set email =0,bounce=0 ";
if($genarate->query($sql,"all")){};	
$sql="update tbt_email_log_campaignid_3132526_marketid_12 set chk=0,bounce=0 ";
if($genarate->query($sql,"all")){};
//email=0 ใน table open และ click จะเป็นข้อมูลปกติ
//email =1 ใน table open และ click จะเป็นข้อมูลไม่ปกติ ไม่เอามาออกรายงาน
exit;*/

//3.update bounce data
/*$sql="
select 
id,campaignid,email_marketingid,emailtargetlistid,from_module,from_id,to_email,domain_name,chk 
from tbt_email_log_campaignid_3132526_marketid_12 where 1
and report=1
and active=1
and chk=0
";
$data = $genarate->process($sql,"all");
for($i=0;$i<count($data);$i++){
	$id=$data[$i][0];
	$campaignid=$data[$i][1];
	$email_marketingid=$data[$i][2];
	$emailtargetlistid=$data[$i][3];
	$from_module=$data[$i][4];
	$from_id=$data[$i][5];
	$to_email=$data[$i][6];
	$domain_name=$data[$i][7];
	
	$sql="
	select id from tbt_bounce_data where 	email  like '%".$to_email."%' and email_id=0  and rule_cat!='no_role'
	";
	$data1 = $genarate->process($sql,"all");
	if(count($data1)>0){
		$bounce_id=$data1[0][0];
		//update email id
		$sql="update tbt_bounce_data set email_id ='".$from_id."' where id='".$bounce_id."'";
		if($genarate->query($sql,"all")){};	
		//update bounce table หลัก
		$sql="update tbt_email_log_campaignid_3132526_marketid_12 set bounce =1,chk=1 where id='".$id."' ";
		if($genarate->query($sql,"all")){};	
		//update bounce table click
		$sql="update tbt_email_log_campaignid_3132526_marketid_12_click set bounce =1,email=1
		,from_id ='".$from_id."',domain_name ='".$domain_name."',emailtargetlistid ='".$emailtargetlistid."'
		where uniqueid='".$id."'";
		if($genarate->query($sql,"all")){};	
		//update bounce table open
		$sql="update tbt_email_log_campaignid_3132526_marketid_12_open set bounce =1,email=1 
		,from_id ='".$from_id."',domain_name ='".$domain_name."',emailtargetlistid ='".$emailtargetlistid."'
		where uniqueid='".$id."'";
		if($genarate->query($sql,"all")){};	
	}else{
		//update bounce table หลัก
		$sql="update tbt_email_log_campaignid_3132526_marketid_12 set chk=1,bounce =0 where id='".$id."' ";
		if($genarate->query($sql,"all")){};	
		//update bounce table click
		$sql="update tbt_email_log_campaignid_3132526_marketid_12_click set bounce =0,email=0
		,from_id ='".$from_id."',domain_name ='".$domain_name."',emailtargetlistid ='".$emailtargetlistid."'
		where uniqueid='".$id."'";
		if($genarate->query($sql,"all")){};	
		//update bounce table open
		$sql="update tbt_email_log_campaignid_3132526_marketid_12_open set bounce =0,email=0
		,from_id ='".$from_id."',domain_name ='".$domain_name."',emailtargetlistid ='".$emailtargetlistid."'
		where uniqueid='".$id."'";
		if($genarate->query($sql,"all")){};	
	}
}
exit;
*/$sql="
insert into tbt_email_log_campaignid_3132526_marketid_12_report(id, emailtargetlistid, from_module, from_id, to_name, to_email, domain_name, active, report, unsubscribe, unsubscribe_date, bounce)
SELECT id, emailtargetlistid, from_module, from_id, to_name, to_email, domain_name, active, report, unsubscribe, unsubscribe_date, bounce
FROM tbt_email_log_campaignid_3132526_marketid_12
WHERE 1 
and emailtargetlistid=3132524
and report=1
and active=1
";
if($genarate->query($sql,"all")){};	

$sql="
select 
email_id
from tbt_bounce_data where 1
and email_id!=0
";
$data = $genarate->process($sql,"all");
for($i=0;$i<count($data);$i++){
	$sql="update tbt_email_log_campaignid_3132526_marketid_12 set bounce =1,chk=1 where from_id='".$data[$i][0]."' ";
	//if($genarate->query($sql,"all")){};
}
//exit;
?>