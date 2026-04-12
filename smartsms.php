<?
/*$FileName = "sql.txt";
$FileHandle = fopen($FileName, 'a+') or die("can't open file");
fwrite($FileHandle,date('Y-m-d H:i:s')."\r\n");
fclose($FileHandle);*/
	header('Content-Type: text/html; charset=utf-8');
	include("config.inc.php");	
	global $root_directory,$site_URL;
	
	include($root_directory."library/dbconfig.php");	
	include ($root_directory."phpmailer/class.phpmailer.php");
	include($root_directory."library/generate_MYSQL.php");
	include($root_directory."library/myFunction.php");

	//$generate = new generate($dbconfig ,"DB");
	$generate = new generate($dbconfig ,"DB");
//get email====================================================================================
	//$date=date('Y-m-d H:i:'."01");
	//$date=date('2011-06-13 18:36:01');
	//$date=date('Y-m-d H:i:s',mktime(date('H'), date('i')+10, 1, date('m'), date('d'), date('Y')));
	//$date=date('2015-09-22 14:42:01');
	//echo $date;
	//exit;
	
	//echo date('Y-m-d H:i:s'); exit;
	$crmid=$_REQUEST["crmid"];
	if($crmid!=""){		
		$sql="select aicrm_smartsms. *
		from  aicrm_smartsms
		left join aicrm_crmentity on aicrm_crmentity.crmid =  aicrm_smartsms.smartsmsid
		where aicrm_smartsms.smartsmsid='".$crmid."'
		and aicrm_smartsms.sms_status='Active'
		and aicrm_crmentity.deleted =0
		and aicrm_smartsms.setup_sms=0
		";
	}else{
		$sql="
		select 
		sms_marketing_name,
		sms_marketing_msg,
		smartsmsid,
		date_start,
		id
		from aicrm_campaign_sms_marketing
		where date_start ='".$date."'
		and sms_status='Active'
		and deleted =0
		and setup_sms=0
		";
	}
	$campaign = $generate->process($sql,"all");
	
	for($i=0;$i<count($campaign);$i++){
		$smartsmsid=$campaign[$i]["smartsmsid"];
		$id=$campaign[$i]["id"];
		$new_table="tbt_sms_log_smartsmsid_".$campaign[$i]["smartsmsid"];
		//echo $new_table;exit;
		$sql="
		CREATE TABLE ".$new_table." (
		id int(11) NOT NULL auto_increment,
		smartsmsid int(19) NOT NULL,
		sms_marketingid int(19) NOT NULL,
		from_id int(19) NOT NULL,
		to_name varchar(250) NOT NULL,
		to_phone varchar(250) NOT NULL,
		from_module varchar(250) NOT NULL,
		send_result varchar(250) NOT NULL,
		date_start datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'เวลาเริ่มส่ง',
		status int(1) default '0' COMMENT '0=ยังไม่ได้ส่ง,1=ส่งแล้วได้รับ,2=ส่งแล้วไม่ได้รับหรือไม่มีเมล์นี้',
		invalid_sms varchar(1) NOT NULL COMMENT '0=ok,1=เบอร์ไม่ครบ',
		PRIMARY KEY  (id,smartsmsid),
		KEY id (id),
		KEY smartsmsid (smartsmsid),
		KEY sms_marketingid (sms_marketingid)
		) ENGINE=MyISAM DEFAULT CHARSET=tis620 AUTO_INCREMENT=1 ;
		";
		//echo $sql;exit;
		if($generate->query($sql,"all")){
			//echo "Create $new_table Complete "."<br>";
		}else{//if for($i=0;$i<count($campaign);$i++){
			$sql="TRUNCATE TABLE ".$new_table."";
			$generate->query($sql,"all");
		}
		
		//Insert Opportunity======================================
		$sql="
		insert into ".$new_table."(smartsmsid,sms_marketingid,from_id,to_name,to_phone,from_module,status)
		select 
		'".$campaign[$i]["smartsmsid"]."',
		'0',
		aicrm_opportunity.opportunityid as id,
		opportunity_name as name,
		cf_2351 as mobile,
		'Opportunity' as module,
		'0'
		from aicrm_smartsms_opportunityrel
		left join aicrm_opportunity  on aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		left join aicrm_opportunitycf on aicrm_opportunity.opportunityid=aicrm_opportunitycf.opportunityid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_opportunity.opportunityid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_opportunityrel.smartsmsid='".$campaign[$i]["smartsmsid"]."'
		and cf_2351<>''
		group by cf_2351
		";
		//echo $sql;exit;
		$generate->query($sql,"all");	
				
		//Insert Lead เบอร์โทรศัพท์1======================================
		$sql="
		insert into ".$new_table."(smartsmsid,sms_marketingid,from_id,to_name,to_phone,from_module,status)
		select 
		'".$campaign[$i]["smartsmsid"]."',
		'0',
		aicrm_leaddetails.leadid as id,
		concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as name,
		aicrm_leadaddress.mobile,
		'Lead' as module,
		'0'
		from aicrm_smartsms_leadsrel
		left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_leadsrel.smartsmsid='".$campaign[$i]["smartsmsid"]."'
		and mobile<>''
		group by mobile
		";
		//echo $sql; exit;
		$generate->query($sql,"all");	
				
		//Insert Account======================================
		$sql="
		insert into ".$new_table."(smartsmsid,sms_marketingid,from_id,to_name,to_phone,from_module,status)
		select 
		'".$campaign[$i]["smartsmsid"]."',
		'0',
		aicrm_account.accountid as id,
		aicrm_account.accountname as name,
		aicrm_account.mobile,
		'Account' as module,
		'0'
		from aicrm_smartsms_accountsrel
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and aicrm_smartsms_accountsrel.smartsmsid='".$campaign[$i]["smartsmsid"]."'
		and mobile<>''
		group by mobile
		";
		//echo $sql;exit;
		$generate->query($sql,"all");	
		
		
		$sql="update ".$new_table." set to_phone=replace(to_phone,'-','');";
		$generate->query($sql,"all");
		$sql="update ".$new_table." set to_phone=replace(to_phone,'+66','0');";
		$generate->query($sql,"all");
		
		//จัดการข้อมูล email ซ้ำ===========================================================================			
		$new_table_temp=$new_table."_temp";
		$sql="
		CREATE TABLE ".$new_table_temp." (
		id int(11) NOT NULL auto_increment,
		smartsmsid int(19) NOT NULL,
		sms_marketingid int(19) NOT NULL,
		from_id int(19) NOT NULL,
		to_name varchar(250) NOT NULL,
		to_phone varchar(250) NOT NULL,
		from_module varchar(250) NOT NULL,
		send_result varchar(250) NOT NULL,
		date_start datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'เวลาเริ่มส่ง',
		status int(1) default '0' COMMENT '0=ยังไม่ได้ส่ง,1=ส่งแล้วได้รับ,2=ส่งแล้วไม่ได้รับหรือไม่มีเมล์นี้',
		invalid_sms varchar(1) NOT NULL COMMENT '0=ok,1=เบอร์ไม่ครบ',
		PRIMARY KEY  (id,smartsmsid),
		KEY id (id),
		KEY smartsmsid (smartsmsid),
		KEY sms_marketingid (sms_marketingid)
		) ENGINE=MyISAM DEFAULT CHARSET=tis620 AUTO_INCREMENT=1 ;
		";
		if($generate->query($sql,"all")){
			$sql="
			insert into 	".$new_table_temp."(`smartsmsid`, `sms_marketingid`, `from_id`, `to_name`, `to_phone`, `from_module`, `send_result`, `date_start`, `status`, `invalid_sms`)
			select `smartsmsid`, `sms_marketingid`, `from_id`, `to_name`, `to_phone`, `from_module`, `send_result`, `date_start`, `status`, `invalid_sms`
			from 	".$new_table." group by to_phone
			";
			//echo $sql;exit;
			if($generate->query($sql,"all")){
				$sql="TRUNCATE TABLE ".$new_table."";
				if($generate->query($sql,"all")){
					$sql="
					insert into 	".$new_table."(`smartsmsid`, `sms_marketingid`, `from_id`, `to_name`, `to_phone`, `from_module`, `send_result`, `date_start`, `status`, `invalid_sms`)
					select `smartsmsid`, `sms_marketingid`, `from_id`, `to_name`, `to_phone`, `from_module`, `send_result`, `date_start`, `status`, `invalid_sms`
					from 	".$new_table_temp."	group by to_phone
					";
					if($generate->query($sql,"all")){
						$sql="DROP TABLE ".$new_table_temp."";
						$generate->query($sql,"all");
					}
				}
			}
		}
		$sql="update ".$new_table." set status=0,invalid_sms=0 where LENGTH(  `to_phone` ) =10;";
		$generate->query($sql,"all");
		$sql="update ".$new_table." set status=2,invalid_sms=1 where LENGTH(  `to_phone` ) !=10;";
		$generate->query($sql,"all");
		$sql="update aicrm_smartsms set setup_sms=1 where smartsmsid='".$campaign[$i]["smartsmsid"]."';";
		$generate->query($sql,"all");
		
		//update setup SMS
		$sql="update aicrm_smartsms set setup_sms=1 where id='".$id."'";
		$generate->query($sql,"all");
	}//for $campaign
//get email====================================================================================
//echo "<script type='text/javascript'>alert('Set Up SMS Complete');window.close();  window.opener.parent.location.replace('index.php?action=DetailView&module=SmartSms&record=".$crmid."&parenttab=Marketing');<///script>";				
	send_sms($root_directory,$site_URL);


function send_sms($root_directory,$site_URL){
	global $generate ;
	$crmid=$_REQUEST["crmid"];
	
	if($crmid!=""){
	/*	$sql="
		select 
		a.sms_marketing_name,
		a.sms_marketing_msg,
		a.smartsmsid,
		a.date_start,
		a.id,a.sms_sender_id , b.sms_sender, b.sms_status, b.deleted, b.sms_url, b.sms_username, b.sms_password
		from aicrm_campaign_sms_marketing as  a
		left join aicrm_config_sender_sms b on b.id = a.sms_sender_id
		where a.smartsmsid='".$crmid."'
		and a.sms_status='Active'
		and a.deleted =0
		and a.setup_sms=1
		";*/
		$sql="select aicrm_smartsms. * , b.*
		from  aicrm_smartsms
		left join aicrm_crmentity on aicrm_crmentity.crmid =  aicrm_smartsms.smartsmsid
		left join aicrm_config_sender_sms b on b.sms_sender = aicrm_smartsms.sms_sender_name
		where aicrm_smartsms.smartsmsid='".$crmid."'
		and aicrm_smartsms.sms_status='Active'
		and aicrm_crmentity.deleted =0
		and aicrm_smartsms.setup_sms=1
		and aicrm_smartsms.send_sms=0
		and b.deleted =0
		";
	}else{
		$sql="
		select 
		a.sms_marketing_name,
		a.sms_marketing_msg,
		a.smartsmsid,
		a.date_start,
		a.id,a.sms_sender_id , b.sms_sender, b.sms_status, b.deleted, b.sms_url, b.sms_username, b.sms_password
		from aicrm_campaign_sms_marketing as a
		left join aicrm_config_sender_sms b on b.id = a.sms_sender_id
		where a.date_start ='".$date."'
		and a.sms_status='Active'
		and a.deleted =0
		and a.setup_sms=1
		";
	}
	$data = $generate->process($sql,"all");
		
		for($i=0;$i<count($data);$i++){
			
			$sender_name = $data[$i]["sms_sender"];
			$user_name = $data[$i]["sms_username"];
			$pass_word = $data[$i]["sms_password"];
			$sms_url = $data[$i]["sms_url"];
			
			
			$smartsmsid=$data[$i]["smartsmsid"];
			$Sender=$data[$i]["sms_sender"];
			$id=$data[$i]["id"];
			$message=$data[$i]["sms_message"];
			$message_type = '0';
			
			$new_table="tbt_sms_log_smartsmsid_".$smartsmsid;
			
			//เช็ค Table
			$sql = "SHOW TABLES LIKE '".$new_table."'" ;
			$tableExists = $generate->process($sql,"all");
			
			
			if(!empty($tableExists)){
				$sql="
				select
				id,
				to_phone
				from ".$new_table."
				where 1
				and smartsmsid='".$smartsmsid."'
				and status=0
				";
				
				$data_sms = $generate->process($sql,"all");	

				$url = $site_URL."WB_Service_AI/ai_function/aifunction/import_send_sms";
				/*Single*/
				$fields = array(
						'AI-API-KEY'=>"1234",
						'module' => "SMS", //module
						'Username' => $user_name,
						'Password' => $pass_word,
						'PhoneList' => $data_sms,
						'Message' => $message,
						'Sender' => $Sender,
						'table_name' => $new_table,
						'sms_url'=>$sms_url,
				);
					
				$fields_string = json_encode($fields);
			
			
				//print_r($fields_string); exit;
				
				// jSON URL which should be requested
				$json_url = $url;
				// jSON String for request
				$json_string = $fields_string; 
				// Initializing curl
				$ch = curl_init( $json_url );
				 
				// Configuring curl options
				$options = array(
					CURLOPT_POST => false,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
					CURLOPT_POSTFIELDS => $json_string
				);
				 
				// Setting curl options
				curl_setopt_array( $ch, $options );
				//echo "<br><br><br><br><br>"; 
				// Getting results
				$result =  curl_exec($ch); // Getting jSON result string
		
			}//if
		}//for

			$sql="select id from ".$new_table." ;";
			$data_count1 = $generate->process($sql,"all");
			$sql="select id from ".$new_table." where 1 and status in(1,2);";
			$data_count2 = $generate->process($sql,"all");
			
			if(count($data_count1)==count($data_count2)){
				$sql="update aicrm_smartsms set sms_status='InActive' where smartsmsid='".$smartsmsid."';";
				$generate->query($sql,"all");
			}
			$sql="update aicrm_smartsms set send_sms=1 where smartsmsid='".$smartsmsid."';";
			$generate->query($sql,"all");

	echo "<script type='text/javascript'>alert('Send SMS Complete');window.close();  window.opener.parent.location.replace('index.php?action=DetailView&module=SmartSms&record=".$smartsmsid."&parenttab=Marketing');</script>";
	
	
}

?>