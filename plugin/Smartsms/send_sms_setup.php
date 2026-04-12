<?
	//ini_set('display_errors', 'On');
    //error_reporting(E_ALL);
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

//get email====================================================================================
	$date=date('Y-m-d');
	$systax = '$';

	function getInbetweenStrings($start, $end, $str){
		$matches = array();
		$regex = "/\\". $start ."([a-zA-Z0-9_.]*)\\". $end ."/";
		preg_match_all($regex, $str, $matches);
		return $matches[1];
	}

	$crmid=@$_REQUEST["crmid"];

	if($crmid!=""){
		$sql="select aicrm_smartsms. *
		from  aicrm_smartsms
		left join aicrm_crmentity on aicrm_crmentity.crmid =  aicrm_smartsms.smartsmsid
		where aicrm_smartsms.smartsmsid='".$crmid."'
		and aicrm_smartsms.sms_status='Schedule'
		and aicrm_crmentity.deleted =0
		and aicrm_smartsms.setup_sms=0
		";
	}else{
		$sql="select aicrm_smartsms. *
		from  aicrm_smartsms
		left join aicrm_crmentity on aicrm_crmentity.crmid =  aicrm_smartsms.smartsmsid
		where aicrm_smartsms.sms_status='Schedule'
		and aicrm_crmentity.deleted =0
		and NOW() between ( DATE_ADD(cast( concat(sms_start_date ,' ' , sms_start_time ) as datetime ),  INTERVAL - 5 MINUTE)  )
    	and (DATE_ADD(cast( concat(sms_start_date ,' ' , sms_start_time ) as datetime ),  INTERVAL 5 MINUTE) )
		and aicrm_smartsms.setup_sms=0 ";
	}

	$data = $myLibrary_mysqli->select($sql);

	foreach ($data as $key => $value) {
		
		$smartsmsid = $value["smartsmsid"];
		$new_table = "tbt_sms_log_smartsmsid_" . $value["smartsmsid"];
		
		$sql = "
		CREATE TABLE " . $new_table . " (
		id int(11) NOT NULL auto_increment,
		smartsmsid int(19) NOT NULL,
		sms_marketingid int(19) NOT NULL,
		message text CHARACTER SET utf8,
		from_id int(19) NOT NULL,
		to_name varchar(250) NOT NULL,
		to_phone varchar(250) NOT NULL,
		from_module varchar(250) NOT NULL,
		send_result varchar(250) NOT NULL,
		resend int(1) default '0' COMMENT '0=รอส่ง/ส่งผ่านแล้ว,1=ส่งซ้ำ',
		date_start datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'เวลาเริ่มส่ง',
		status int(1) default '0' COMMENT '0=ยังไม่ได้ส่ง,1=ส่งแล้วได้รับ,2=ส่งแล้วไม่ได้รับหรือไม่มีเมล์นี้',
		invalid_sms varchar(1) NOT NULL COMMENT '0=ok,1=เบอร์ไม่ครบ',
		check_send int(1) NOT NULL default '0' COMMENT '0=ยังไม่ได้ส่งไปหา Gatway,1=ส่งไปหา Gatway',
		duplicate varchar(1) default '0' NOT NULL COMMENT '0=ไม่ซ้ำ,1=ซ้ำ',
		unsubscribe varchar(50) NOT NULL default '' COMMENT 'ต้องการรับข่าวสาร',
		PRIMARY KEY  (id,smartsmsid),
		KEY id (id),
		KEY smartsmsid (smartsmsid),
		KEY sms_marketingid (sms_marketingid)
		) ENGINE=MyISAM DEFAULT CHARSET=tis620 AUTO_INCREMENT=1 ;
		";

		if($myLibrary_mysqli->Query($sql)) {

		} else {
			$sql = "TRUNCATE TABLE " . $new_table . "";
			$myLibrary_mysqli->Query($sql);
		}
		
		$str_arr = getInbetweenStrings($systax, $systax, str_replace("'","''",$value['sms_message']));
		$replace_arr = array();

		/*--/////////////////เพิ่ม data table แบ่ง group-personal //////////////////////////////--*/
		
		$massage=$value["sms_message"];  
		// echo $massage; exit;               
		if(strstr($massage,$systax))
		 {
		 	$sql="update aicrm_smartsms set sms_type = 'personal'  where smartsmsid='".$value["smartsmsid"]."';";
		 	$myLibrary_mysqli->Query($sql);
		
		}else{
		 	//group
		 	$sql="update aicrm_smartsms set sms_type = 'group'  where smartsmsid='".$value["smartsmsid"]."';";
		 	$myLibrary_mysqli->Query($sql);
		}

		/*--////////////////////////////////////////////////////////////////////////////////////--*/

		//echo '<pre>';print_r($str_arr);echo '</pre>';exit;
		if(empty($str_arr)){

			//Insert Lead เบอร์โทรศัพท์1======================================
			$sql="
			insert into ".$new_table."(smartsmsid,sms_marketingid,from_id,to_name,to_phone,from_module,status,message,unsubscribe)
			select 
			'".$value["smartsmsid"]."',
			'0',
			aicrm_leaddetails.leadid as id,
			concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as name,
			aicrm_leaddetails.mobile,
			'Lead' as module,
			'0'
			,'". str_replace("'","''",$value['sms_message'])."',
			aicrm_leaddetails.smsstatus as smsstatus
			from aicrm_smartsms_leadsrel
			left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
			left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
			left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
			left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
			where 1
			and aicrm_crmentity.deleted=0
			and aicrm_smartsms_leadsrel.smartsmsid='".$value["smartsmsid"]."'
			";
			$myLibrary_mysqli->Query($sql);
					
			//Insert Account======================================
			$sql="
			insert into ".$new_table."(smartsmsid,sms_marketingid,from_id,to_name,to_phone,from_module,status,message,unsubscribe)
			select 
			'".$value["smartsmsid"]."',
			'0',
			aicrm_account.accountid as id,
			aicrm_account.accountname as name,
			aicrm_account.mobile,
			'Account' as module,
			'0'
			,'". str_replace("'","''",$value['sms_message'])."',
			aicrm_account.smsstatus as smsstatus
			from aicrm_smartsms_accountsrel
			left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
			left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
			left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
			left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
			left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
			where 1
			and aicrm_crmentity.deleted=0
			and aicrm_smartsms_accountsrel.smartsmsid='".$value["smartsmsid"]."'
			
			";
			$myLibrary_mysqli->Query($sql);
			
			//Insert Contact======================================
		
		}else{

			foreach($str_arr as $arr){
				$ex = explode('.', $arr);

				if(in_array($ex[0], array('aicrm_account','aicrm_accountscf','aicrm_accountbillads','aicrm_accountshipads','aicrm_crmentity'))){
					$sql = 'select aicrm_account.accountid, '. $arr .'
							from aicrm_smartsms_accountsrel
							left join aicrm_account on aicrm_smartsms_accountsrel.accountid = aicrm_account.accountid
							left join aicrm_accountscf on aicrm_accountscf.accountid = aicrm_account.accountid
							left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid = aicrm_account.accountid
							left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid = aicrm_account.accountid
							left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
							where 1 and aicrm_crmentity.deleted=0
							and aicrm_smartsms_accountsrel.smartsmsid='.$value["smartsmsid"].' group by aicrm_account.mobile';
							
					$myLibrary_mysqli->Query($sql);

					foreach($res as $rs){
						$replace_arr[$rs['accountid']]['module'] = 'Accounts';
						$replace_arr[$rs['accountid']][$systax.$arr.$systax] = $rs[$ex[1]];
					}
				}else if(in_array($ex[0], array('aicrm_contactdetails','aicrm_contactaddress','aicrm_contactscf','aicrm_crmentity'))){
					$sql = 'select aicrm_contactdetails.contactid, '. $arr .'
							from aicrm_smartsms_contactsrel
							left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid = aicrm_contactdetails.contactid
							left join aicrm_contactscf on aicrm_contactdetails.contactid = aicrm_contactscf.contactid
							left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
							where 1 and aicrm_crmentity.deleted=0
							and aicrm_smartsms_contactsrel.smartsmsid = '.$value["smartsmsid"].' group by mobile';
					
					$myLibrary_mysqli->Query($sql);

					foreach($res as $rs){
						$replace_arr[$rs['contactid']]['module'] = 'Contacts';
						$replace_arr[$rs['contactid']][$systax.$arr.$systax] = $rs[$ex[1]];
					}
				} else if(in_array($ex[0], array('aicrm_leaddetails','aicrm_leadscf','aicrm_leadaddress','aicrm_crmentity'))){
					$sql = 'select aicrm_leaddetails.leadid, '. $arr .'
							from aicrm_smartsms_leadsrel
							left join aicrm_leaddetails on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
							left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
							left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
							left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
							where 1
							and aicrm_crmentity.deleted=0
							and aicrm_smartsms_leadsrel.smartsmsid='.$value["smartsmsid"].' group by aicrm_leaddetails.mobile';

					$myLibrary_mysqli->Query($sql);

					foreach($res as $rs){
						$replace_arr[$rs['leadid']]['module'] = 'Leads';
						$replace_arr[$rs['leadid']][$systax.$arr.$systax] = $rs[$ex[1]];
					}
				}
			}

			$new_string = '';
			foreach($replace_arr as $id => $a){
				$find       = array_keys($a);
				$replace    = array_values($a);
				$new_string = str_replace($find, $replace, str_replace("'","''",$value['sms_message']));

				switch($a['module']){
					case'Accounts':
						$sql="insert into ".$new_table."(smartsmsid,sms_marketingid,from_id,to_name,to_phone,message,from_module,status)
						select
						'".$value["smartsmsid"]."',
						'0',
						aicrm_account.accountid as id,
						aicrm_account.accountname as name,
						aicrm_account.mobile as mobile,
						'". $new_string ."',
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
						and aicrm_smartsms_accountsrel.smartsmsid=".$value["smartsmsid"]."
						and aicrm_account.accountid =".$id;
						
						$myLibrary_mysqli->Query($sql);
						break;

					case'Contacts':
						$sql='insert into '.$new_table.'(smartsmsid,sms_marketingid,from_id,to_name,to_phone,message,from_module,status)
						select
						'.$value['smartsmsid'].',
						"0",
						aicrm_contactdetails.contactid as id,
						firstname as name,
						mobile,
						"'. $new_string .'",
						"Contacts" as module,
						"0"
						from aicrm_smartsms_contactsrel
						left join aicrm_contactdetails  on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
						left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
						left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
						where 1
						and aicrm_crmentity.deleted=0
						and aicrm_smartsms_contactsrel.smartsmsid='.$value['smartsmsid'].'
						and aicrm_contactdetails.contactid='.$id;

						$myLibrary_mysqli->Query($sql);
						break;

					case'Leads':
						$sql='insert into '.$new_table.'(smartsmsid,sms_marketingid,from_id,to_name,to_phone,message,from_module,status)
						select
						'.$value['smartsmsid'].',
						"0",
						aicrm_leaddetails.leadid as id,
						concat(aicrm_leaddetails.firstname," ",aicrm_leaddetails.lastname) as name,
						aicrm_leaddetails.mobile as mobile,
						"'. $new_string .'",
						"Lead" as module,
						"0"
						from aicrm_smartsms_leadsrel
						left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
						left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
						left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
						left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
						where 1
						and aicrm_crmentity.deleted=0
						and aicrm_smartsms_leadsrel.smartsmsid='.$value['smartsmsid'].'
						and aicrm_leaddetails.leadid='.$id;

						$myLibrary_mysqli->Query($sql);
						break;
				}
			}
		
		}// End if not empty
		
		$sql="update ".$new_table." set to_phone=replace(to_phone,'+66','0');";
		$myLibrary_mysqli->Query($sql);
		
		$sql = "update ".$new_table." set to_phone = removeExtraChar(to_phone)";
		$myLibrary_mysqli->Query($sql);
		
		//จัดการข้อมูล email ซ้ำ===========================================================================
		$new_table_temp=$new_table."_temp";
		// Maew add if not exist when set Status to Active Again
		$sql="
		CREATE TABLE IF NOT EXISTS ".$new_table_temp." (
		id int(11) NOT NULL auto_increment,
		smartsmsid int(19) NOT NULL,
		sms_marketingid int(19) NOT NULL,
		message text CHARACTER SET utf8,
		from_id int(19) NOT NULL,
		to_name varchar(250) NOT NULL,
		to_phone varchar(250) NOT NULL,
		from_module varchar(250) NOT NULL,
		send_result varchar(250) NOT NULL,
		resend int(1) default '0' COMMENT '0=รอส่ง/ส่งผ่านแล้ว,1=ส่งซ้ำ',
		date_start datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'เวลาเริ่มส่ง',
		status int(1) default '0' COMMENT '0=ยังไม่ได้ส่ง,1=ส่งแล้วได้รับ,2=ส่งแล้วไม่ได้รับหรือไม่มีเมล์นี้',
		invalid_sms varchar(1) NOT NULL COMMENT '0=ok,1=เบอร์ไม่ครบ',
		duplicate varchar(1) default '0' NOT NULL COMMENT '0=ไม่ซ้ำ,1=ซ้ำ',
		unsubscribe varchar(50) NOT NULL default '' COMMENT 'ต้องการรับข่าวสาร',
		PRIMARY KEY  (id,smartsmsid),
		KEY id (id),
		KEY smartsmsid (smartsmsid),
		KEY sms_marketingid (sms_marketingid)
		) ENGINE=MyISAM DEFAULT CHARSET=tis620 AUTO_INCREMENT=1 ;
		";

		if($myLibrary_mysqli->Query($sql)){
			$sql="	truncate table ".$new_table_temp."";  // Maew Add script to truncate table for set active again
			$myLibrary_mysqli->Query($sql);
			
			$sql="
			insert into 	".$new_table_temp."
			(smartsmsid, sms_marketingid, from_id, to_name, to_phone, from_module, send_result, date_start, status, invalid_sms ,message ,unsubscribe)
			select smartsmsid, sms_marketingid, from_id, to_name, to_phone, from_module, send_result, date_start, status, invalid_sms,message,unsubscribe
			from 	".$new_table." 
			";
			//group by to_phone
			if($myLibrary_mysqli->Query($sql)){
			/*$sql="TRUNCATE TABLE ".$new_table."";
				if($generate->query($sql,"all")){
					$sql="
					insert into 	".$new_table."(smartsmsid, sms_marketingid, from_id, to_name, to_phone, from_module, send_result, date_start, status, invalid_sms)
					select smartsmsid, sms_marketingid, from_id, to_name, to_phone, from_module, send_result, date_start, status, invalid_sms
					from 	".$new_table_temp."	group by to_phone
					";

					if($generate->query($sql,"all")){
						$sql="DROP TABLE ".$new_table_temp."";
						$generate->query($sql,"all");
					}
				}*/
			}
		}
		$sql="update ".$new_table." set status=0,invalid_sms=0 where LENGTH(  to_phone ) =10;";
		$myLibrary_mysqli->Query($sql);
		$sql="update ".$new_table." set status=3,invalid_sms=1 where LENGTH(  to_phone ) !=10;";
		$myLibrary_mysqli->Query($sql);
		$sql="update ".$new_table." set status=3,invalid_sms=1 where to_phone Not Like '0%'; ";
		$myLibrary_mysqli->Query($sql);
		
		
		$HAVING="
		update ".$new_table." as a 
		inner join (
			select to_phone 
			from ".$new_table." 
			where (invalid_sms != 1 and status != 3 ) or (unsubscribe != 'InActive' and status = 0) GROUP BY to_phone HAVING count( * ) >1 ) as 
			b on a.to_phone = b.to_phone set a.duplicate=1;
		";
		$myLibrary_mysqli->Query($HAVING);
	
		$sql=" select min(id) as id
		from ".$new_table."
		where duplicate=1
		GROUP BY to_phone
		HAVING count( * ) >1
		";
		$data_d =  $myLibrary_mysqli->select($sql);
		
		foreach ($data_d as $k => $v) {
			$sql_dup="update ".$new_table." set duplicate = 0 where id='".$v['id']."'; ";
			$myLibrary_mysqli->Query($sql_dup);
		}
		
		$sql="update aicrm_smartsms set setup_sms=1,preparingtime= '".date('Y-m-d H:i:s')."' where smartsmsid='".$value["smartsmsid"]."';";
		//echo $sql; exit;
		$myLibrary_mysqli->Query($sql);
				
		$sql="delete FROM  tbt_sms_log_smartsms WHERE 1 and smartsmsid = '".$value["smartsmsid"]."'; ";
		$myLibrary_mysqli->Query($sql);
		
	}//foreach $data


//get email====================================================================================

echo "<script type='text/javascript'>alert('Set Up SMS Complete');window.close();  window.opener.parent.location.replace('index.php?action=DetailView&module=SmartSms&record=".$crmid."&parenttab=Marketing');</script>";

?>