<?php
	require_once ("../../config.inc.php");
	global $path, $url_path;
	
	$path=$root_directory;
	$url_path=$site_URL;

	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");
	
	require_once("../../library/general.php");
	$General = new libGeneral();

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
	
	//Get Announcement====================================================================================
	$date=date('Y-m-d H:i:'."01");
	$date=date('Y-m-d H:i:s',mktime(date('H'), date('i')+10, 1, date('m'), date('d'), date('Y')));

	//Select แบบมี crmid ส่งเข้ามา เช็ค Active
	$crmid = @$_REQUEST["crmid"];
	
	if($crmid != ""){
		$sql="
		select aicrm_announcement. *
		from aicrm_announcement
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_announcement.announcementid
		where aicrm_announcement.announcementid='".$crmid."'
		and aicrm_announcement.status = 'Sending'
		and aicrm_crmentity.deleted = 0
		and aicrm_announcement.noti_setup = 1
		and aicrm_announcement.noti_send = 0
		";
	//Select แบบไม่มี crmid ส่งเข้ามา เช็ค active (Run Schedule)	
	}else{
		$sql="
		  select aicrm_announcement. *
		from aicrm_announcement
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_announcement.announcementid
		where aicrm_announcement.status = 'Sending'
		and aicrm_crmentity.deleted = 0
		and aicrm_announcement.noti_setup = 1
		and aicrm_announcement.noti_send = 0 ";
	}
	$announcement = $myLibrary_mysqli->select($sql);

	//Create new table Announcement
	foreach ($announcement as $key => $value) {

		$table_announcement ="tbt_announcement_log_id_".$value['announcementid'];
		$announcementid = $value['announcementid'];

		$sql="select * from ".$table_announcement." where send = 0" ;
		$data = $myLibrary_mysqli->select($sql);
				
		foreach ($data as $k => $v) {
			$url = $site_URL."WB_Service_AI/Announcement/send_notification" ;
			$a_param["AI-API-KEY"] = "1234";
			$a_param["crmid"] = $announcementid;
			$a_param["action"] = "";
			$a_param["data"] = $v;
			$a_param["userid"] = $v['userid'];
			$a_param["smownerid"] = "1" ;	
			$a_curl = $General->curl($url,$a_param,"json");

			$sql="update ".$table_announcement." set send =1 where userid='".$v['userid']."' ";
			$myLibrary_mysqli->Query($sql);
		}

		$sql="update aicrm_announcement set noti_send =1 ,status = 'Complete' ,timesent = '".date('Y-m-d H:i:s')."' where announcementid='".$announcementid."'";
		$myLibrary_mysqli->Query($sql);
	
	}

?>