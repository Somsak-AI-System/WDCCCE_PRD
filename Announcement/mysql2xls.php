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
//$generate = new generate($dbconfig ,"DB"); 

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
$sql1="select announcementid from aicrm_announcement where announcementid='".$crmid."' limit 1";	
//$data1 = $generate->process($sql,"all");
$data1 = $myLibrary_mysqli->Select($sql1);
//echo "<pre>"; print_r($data); echo "</pre>"; exit;
if(count($data1)>0){
	$table="tbt_announcement_log_id_".$data1[0]['announcementid'];
}

$param_filesname = $_REQUEST["file_name"]."_".date('dmYHis');

if($report_no=="p1_001"){	
	$sql ="SELECT aicrm_users.user_name as 'Username',concat(aicrm_users.first_name,' ',aicrm_users.last_name) as 'ชื่อ-สกุล Eng', concat(aicrm_users.first_name_th,' ',aicrm_users.last_name_th) as 'ชื่อ-สกุล Th', aicrm_users.phone_mobile as 'เบอร์โทร' , aicrm_users.email1 as 'อีเมล' ,aicrm_users.status as 'สถานะ' , aicrm_users.section as 'แผนก' , aicrm_users.position as 'ตำแหน่ง'
	FROM aicrm_announcement_usersrel
	inner join aicrm_users on aicrm_users.id = aicrm_announcement_usersrel.id
	where aicrm_announcement_usersrel.announcementid = '".$crmid."';";
}else if($report_no=="p1_002"){
	$sql="SELECT aicrm_users.user_name as 'Username',concat(aicrm_users.first_name,' ',aicrm_users.last_name) as 'ชื่อ-สกุล Eng', concat(aicrm_users.first_name_th,' ',aicrm_users.last_name_th) as 'ชื่อ-สกุล Th', aicrm_users.phone_mobile as 'เบอร์โทร' , aicrm_users.email1 as 'อีเมล' ,aicrm_users.status as 'สถานะ' , aicrm_users.section as 'แผนก' , aicrm_users.position as 'ตำแหน่ง'
		FROM ".$table." 
		inner join aicrm_users on aicrm_users.id = ".$table." .userid 
		where ".$table.".send = 0 ";
}else if($report_no=="p1_003"){
	$sql="SELECT aicrm_users.user_name as 'Username',concat(aicrm_users.first_name,' ',aicrm_users.last_name) as 'ชื่อ-สกุล Eng', concat(aicrm_users.first_name_th,' ',aicrm_users.last_name_th) as 'ชื่อ-สกุล Th', aicrm_users.phone_mobile as 'เบอร์โทร' , aicrm_users.email1 as 'อีเมล' ,aicrm_users.status as 'สถานะ' , aicrm_users.section as 'แผนก' , aicrm_users.position as 'ตำแหน่ง',
		 DATE_FORMAT(ai_notification.datesend,'%d/%m/%Y') as 'วันที่ส่ง' ,DATE_FORMAT(ai_notification.timesend,'%H:%i:%s') as 'เวลาที่ส่ง'
		 FROM ".$table." 
		 inner join aicrm_users on aicrm_users.id = ".$table." .userid 
		 inner join ai_notification on ai_notification.userid = ".$table." .userid and ai_notification.crmid = '".$crmid."'
		 where ".$table.".send = 1 order by ai_notification.datesend asc , ai_notification.timesend asc";
		//echo $sql; exit;
}else if($report_no=="p1_004"){
	
	$sql="SELECT aicrm_users.user_name as 'Username',concat(aicrm_users.first_name,' ',aicrm_users.last_name) as 'ชื่อ-สกุล Eng', concat(aicrm_users.first_name_th,' ',aicrm_users.last_name_th) as 'ชื่อ-สกุล Th', aicrm_users.phone_mobile as 'เบอร์โทร' , aicrm_users.email1 as 'อีเมล' ,aicrm_users.status as 'สถานะ' , aicrm_users.section as 'แผนก' , aicrm_users.position as 'ตำแหน่ง',
		DATE_FORMAT(ai_notification.datesend,'%d/%m/%Y') as 'วันที่ส่ง' ,DATE_FORMAT(ai_notification.timesend,'%H:%i:%s') as 'เวลาที่ส่ง',
		DATE_FORMAT(ai_notification.readdate,'%d/%m/%Y') as 'วันที่อ่าน' ,DATE_FORMAT(ai_notification.readdate,'%H:%i:%s') as 'เวลาที่อ่าน'
		FROM ".$table." 
		inner join aicrm_users on aicrm_users.id = ".$table." .userid 
		inner join ai_notification on ai_notification.userid = ".$table." .userid and ai_notification.crmid = '".$crmid."'
		where ai_notification.module = 'Announcement' and ai_notification.flagread = 1 order by ai_notification.readdate asc";
		//echo $sql; exit;
}else if($report_no=="p1_005"){
	$sql="SELECT aicrm_users.user_name as 'Username',concat(aicrm_users.first_name,' ',aicrm_users.last_name) as 'ชื่อ-สกุล Eng', concat(aicrm_users.first_name_th,' ',aicrm_users.last_name_th) as 'ชื่อ-สกุล Th', aicrm_users.phone_mobile as 'เบอร์โทร' , aicrm_users.email1 as 'อีเมล' ,aicrm_users.status as 'สถานะ' , aicrm_users.section as 'แผนก' , aicrm_users.position as 'ตำแหน่ง',
		DATE_FORMAT(ai_notification.datesend,'%d/%m/%Y') as 'วันที่ส่ง' ,DATE_FORMAT(ai_notification.timesend,'%H:%i:%s') as 'เวลาที่ส่ง',
		DATE_FORMAT(ai_notification.readdate,'%d/%m/%Y') as 'วันที่อ่าน' ,DATE_FORMAT(ai_notification.readdate,'%H:%i:%s') as 'เวลาที่อ่าน',
		DATE_FORMAT(ai_notification.acceptdate,'%d/%m/%Y') as 'วันที่รับทราบ' ,DATE_FORMAT(ai_notification.acceptdate,'%H:%i:%s') as 'เวลาที่รับทราบ'
		FROM ".$table." 
		inner join aicrm_users on aicrm_users.id = ".$table." .userid 
		inner join ai_notification on ai_notification.userid = ".$table." .userid and ai_notification.crmid = '".$crmid."'
		where ai_notification.module = 'Announcement' and ai_notification.flagaccept = 1 order by ai_notification.acceptdate asc";
}
	$data = $myLibrary_mysqli->Select($sql);
	$myLib = new myExcel();
	if(!empty($data)){
		$title = $param_filesname;
		$a_resonse = $myLib->gen_excel($data,$title);
	}else{
		echo "<script>alert('No Data');</script>";
		echo "<script>window.location.replace('".$site_URL."Announcement/report.php?crmid=".$crmid."');</script>";
	}
?> 