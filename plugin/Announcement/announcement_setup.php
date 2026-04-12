<?php
	require_once ("../../config.inc.php");
	global $path, $url_path;
	
	$path=$root_directory;
	$url_path=$site_URL;

	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

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
		and aicrm_announcement.status = 'Schedule'
		and aicrm_crmentity.deleted = 0
		and aicrm_announcement.noti_setup = 0
		";
	//Select แบบไม่มี crmid ส่งเข้ามา เช็ค active (Run Schedule)	
	}else{
		$sql="
		  select aicrm_announcement.*  
		  from aicrm_announcement
		  inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_announcement.announcementid
		  where aicrm_announcement.status='Schedule'
		  and aicrm_crmentity.deleted =0
		  and aicrm_announcement.noti_setup=0
		  and NOW() between ( DATE_ADD(cast( concat(senddate ,' ' , sendtime ) as datetime ),  INTERVAL - 5 MINUTE)  )
		  and  (DATE_ADD(cast( concat(senddate ,' ' , sendtime ) as datetime ),  INTERVAL 5 MINUTE) )";
	}
	
	$announcement = $myLibrary_mysqli->select($sql);
	//Create new table Announcement
	foreach ($announcement as $key => $value) {

		$new_table="tbt_announcement_log_id_".$value['announcementid'];
		$announcementid = $value['announcementid'];
		
		$sql = "CREATE TABLE ".$new_table." (
			  id int(11) NOT NULL,
			  crmid int(11) DEFAULT NULL,
			  module varchar(250) DEFAULT NULL,
			  send_date date DEFAULT NULL COMMENT 'วันที่ต้องส่ง',
			  send_time time DEFAULT NULL COMMENT 'เวลาที่ต้องส่ง',
			  send_message text COMMENT 'ข้อความที่ส่ง',
			  noti_type int(11) DEFAULT NULL COMMENT 'ประเภท noti 1=message',
			  adddt datetime DEFAULT NULL,
			  addempcd int(11) DEFAULT NULL,
			  upddt datetime DEFAULT NULL,
			  updempcd int(11) DEFAULT NULL,
			  success_data int(10) NOT NULL DEFAULT '0',
			  unsuccess_data int(10) NOT NULL DEFAULT '0',
			  total_data int(10) NOT NULL DEFAULT '0',
			  date_start_crm date DEFAULT NULL,
			  time_start_crm time DEFAULT NULL,
			  action_link varchar(255) DEFAULT 'InApp',
			  link text,
			  action_type varchar(50) NOT NULL DEFAULT 'Only',
			  userid int(11) NOT NULL,
			  send int(2) NOT NULL DEFAULT '0'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ";
		
		if($myLibrary_mysqli->Query($sql)){
			
			$sql1= "ALTER TABLE ".$new_table." ADD PRIMARY KEY (id), ADD KEY Index1 (crmid), ADD KEY Index2 (module), ADD KEY Index3 (userid);";
			$sql2= "ALTER TABLE ".$new_table." MODIFY id int(11) NOT NULL AUTO_INCREMENT;";
			$myLibrary_mysqli->Query($sql1);
			$myLibrary_mysqli->Query($sql2);

		}else{
			$sql="TRUNCATE TABLE ".$new_table."";
			$myLibrary_mysqli->Query($sql);

			$sql1= "ALTER TABLE ".$new_table." ADD PRIMARY KEY (id), ADD KEY Index1 (crmid), ADD KEY Index2 (module), ADD KEY Index3 (userid);";
			$sql2= "ALTER TABLE ".$new_table." MODIFY id int(11) NOT NULL AUTO_INCREMENT;";
			$myLibrary_mysqli->Query($sql1);
			$myLibrary_mysqli->Query($sql2);
		}

		//Insert Users
			$sql="
			insert into ".$new_table."(crmid ,module ,send_date ,send_time ,send_message ,noti_type ,date_start_crm ,time_start_crm ,userid)
			select 
			'".$value['announcementid']."',
			'Announcement',
			'".$value['senddate']."',
			'".$value['sendtime']."',
			'".$value['announcement_name']."',
			'1',
			'".$value['senddate']."',
			'".$value['sendtime']."',
			aicrm_announcement_usersrel.id
			from aicrm_announcement_usersrel
			inner join aicrm_announcement on aicrm_announcement.announcementid=aicrm_announcement_usersrel.announcementid
			where aicrm_announcement.announcementid='".$value['announcementid']."' ";
			//echo $sql;
			$myLibrary_mysqli->Query($sql);

			$sql="update aicrm_announcement set noti_setup =1 ,status = 'Sending' ,preparingtime = '".date('Y-m-d H:i:s')."' where announcementid='".$announcementid."'";
			$myLibrary_mysqli->Query($sql);
	}
?>