<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<link rel="stylesheet" type="text/css" href="themes/softed/style.css">
<title>Smart Email</title>
<?php
	

	header('Content-Type: text/html; charset=utf-8');
	require_once ("../config.inc.php");
	global $path,$url_path;
	
	$path=$root_directory;//"C:/AppServ/www/mjdp";
	$url_path=$site_URL;//"http://".$_SERVER['HTTP_HOST']."/mjdp";
	
	require_once ($path."/library/dbconfig.php");
	require_once ($path."/library/genarate.inc.php");
	require_once ($path."/library/myFunction.php");
	require_once ($path."/lib/swift_required.php");
		
	global $mailer;
	Swift_Preferences::getInstance()->setCacheType('array');
	
	$genarate = new genarate($dbconfig ,"DB");
//get email====================================================================================
	$date=date('Y-m-d H:i:'."01");
	$date=date('Y-m-d H:i:s',mktime(date('H'), date('i')+10, 1, date('m'), date('d'), date('Y')));

	$systax = '$';
	//

	//Select แบบมี crmid ส่งเข้ามา เช็ค active
	$crmid=$_REQUEST["crmid"];
	$crmid = '299082';
	if($crmid!=""){
		$sql="
		select aicrm_smartemail. *
		from aicrm_smartemail
		left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_smartemail.smartemailid
		where aicrm_smartemail.smartemailid='".$crmid."'
		and aicrm_smartemail.email_status='Active'
		and aicrm_crmentity.deleted =0
		and aicrm_smartemail.email_setup=0
		";
	//Select แบบไม่มี crmid ส่งเข้ามา เช็ค active (Run Schedule)
	}else{
		$sql="
		  select aicrm_smartemail.*
		  from aicrm_smartemail
		  left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_smartemail.smartemailid
		  where aicrm_smartemail.email_status='Active'
		  and aicrm_crmentity.deleted =0
		  and aicrm_smartemail.email_setup=0
		  and between ( DATE_ADD(cast( concat(`email_start_date` ,' ' , `email_start_time` ) as datetime ),  INTERVAL - 5 MINUTE)  )
		  and  (DATE_ADD(cast( concat(`email_start_date` ,' ' , `email_start_time` ) as datetime ),  INTERVAL 5 MINUTE) )";
	}

	$campaign = $genarate->process($sql,"all");
	//create new table smartemail

	function getInbetweenStrings($start, $end, $str){
		$matches = array();
		$regex = "/\\". $start ."([a-zA-Z0-9_.]*)\\". $end ."/";
		preg_match_all($regex, $str, $matches);
		return $matches[1];
	}

	for($i=0;$i<count($campaign);$i++){
		$new_table="tbt_email_log_smartemailid_".$campaign[$i][0];
		$date_start=$campaign[$i][5]." ".$campaign[$i][6];
		$campaignid=$campaign[$i][0];
		$campaign_name=$campaign[$i][2];

		$sql="
		CREATE TABLE ".$new_table." (
		id int(11) NOT NULL auto_increment,
		campaignid int(19) NOT NULL,
		email_marketingid int(19) NOT NULL,
		emailtargetlistid int(19) NOT NULL,
		from_module varchar(250) NOT NULL,
		from_id int(19) NOT NULL,
		to_name varchar(250) NOT NULL,
		to_email varchar(250) NOT NULL,
		to_email_old varchar(250) NOT NULL,
		message text NOT NUll,
		domain_name varchar(250) NOT NULL,
		date_start datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'เวลาเริ่มส่ง',
		status int(1) default '0' COMMENT '(3,4)=ยังไม่ได้ส่ง,(1)=ส่งแล้ว,(0)=เมล์นี้มีปัญหา,(2)=ส่งแล้วมีปัญหา',
		mistype_email int(20) default '0' ,
		invalid_email int(20) default '0' ,
		active int(20) default '0' COMMENT 'ใช้งานหรือไม่ใช้งาน',
		report int(20) default '0' COMMENT 'ออกรายงานหรือไม่ออก',
		duplicate int(20) default '0' COMMENT 'อีเมล์ซ้ำหรือไม่',
		unsubscribe int(20) default '0' COMMENT 'งดรับเมล์',
		unsubscribe_date datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'วันยกเลิกข่าวสาร',
		check_send int(20) default '0' ,
		group_send int(20) default '0' ,
		PRIMARY KEY  (id,campaignid),
		KEY id (id),
		KEY campaignid (campaignid),
		KEY email_marketingid (email_marketingid),
		KEY emailtargetlistid (emailtargetlistid)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";

		if($genarate->query($sql,"all")){
		}else{
			$sql="TRUNCATE TABLE ".$new_table."";
			$genarate->query($sql,"all");
		}

		$folder_name=date('Ymd',strtotime($campaign[$i][5]))."_".$campaign[$i][0];
		if (!file_exists($path."/EDM/".$folder_name)){
			if (!mkdir($path."/EDM/".$folder_name, 0777, true)){}
		}
		$email_body=$campaign[$i][7];

		$str_arr = getInbetweenStrings($systax, $systax, $campaign[$i][7]);

		$replace_arr = array();

	if(empty($str_arr))
		{
			
			$sql="
			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message)
			select 
			'".$campaign[$i][0]."',
			'0',
			'0',
			b.contactid as id,
			b.firstname as name,
			b.email as email,
			'Contacts' as module,
			'4'
			,'".$email_body."'
			from aicrm_smartemail_contactsrel a
			left join aicrm_contactdetails b on a.contactid=b.contactid
    		left join aicrm_contactscf c on b.contactid=c.contactid
    		left join aicrm_crmentity d on d.crmid=b.contactid
			where 1
			and d.deleted=0
			and b.email<>''

			and a.smartemailid='".$campaign[$i][0]."'
			
			";
			//group by b.email
			$genarate->query($sql,"all");
		
		 //Insert Opportunity======================================
			$sql="
			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message)
			select 
			'".$campaign[$i][0]."',
			'0',
			'0',
			b.opportunityid as id,
			b.opportunity_name as name,
			b.email as email,
			'Opportunity' as module,
			'4'
			,'".$email_body."'
			from aicrm_smartemail_opportunityrel a
			left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
			left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
			left join aicrm_crmentity d on d.crmid=b.opportunityid
			where 1
			and d.deleted=0
			and b.email<>''
			and emailstatus='Active'

			and a.smartemailid='".$campaign[$i][0]."'
			
			";
			//group by b.email
			$genarate->query($sql,"all");	
						
			//Insert Lead======================================
			$sql="
			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message)
			select 
			'".$campaign[$i][0]."',
			'0',
			'0',
			b.leadid as id,
			concat(b.firstname,' ',b.lastname) as name,
			b.email as email,
			'Leads' as module,
			'4'
			,'".$email_body."'
			from aicrm_smartemail_leadsrel a
			left join aicrm_leaddetails  b on a.leadid=b.leadid
			left join aicrm_leadscf c on b.leadid=c.leadid
			left join aicrm_crmentity d on d.crmid=b.leadid
			where 1
			and d.deleted=0
			and b.email<>''
			and emailstatus in('','Active')
			and a.smartemailid='".$campaign[$i][0]."'
			
			";
			//group by b.email
			$genarate->query($sql,"all");
			//Insert Account======================================
			$sql="
			select 
			b.accountid as id,
			b.accountname as name,
			email1  as email,
			'Accounts' as module
			from aicrm_smartemail_accountsrel a
			left join aicrm_account  b on a.accountid=b.accountid
			left join aicrm_accountscf c on b.accountid=c.accountid
			left join aicrm_crmentity d on d.crmid=b.accountid
			where 1
			and d.deleted=0
			and email1<>''
			and emailstatus in('','Active')
			and a.smartemailid='".$campaign[$i][0]."'
			
			";
			//group by email1
			$dataacc = $genarate->process($sql,"all");
			
			for($k=0;$k<count($dataacc);$k++){
				$email=split(",",$dataacc[$k][2]);
				for($w=0;$w<count($email);$w++){
					if($email[$w]!=""){
						$sql="
						insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message)
						values('".$campaign[$i][0]."','0','0','".$dataacc[$k][0]."','".$dataacc[$k][1]."',
						'".$email[$w]."','".$dataacc[$k][3]."','4','".$email_body."')
						";
						$genarate->query($sql,"all");
					}
				}
			}
			
			
			
		}
	else{ // Got map field in email content

	foreach($str_arr as $arr){
	
			$ex = explode('.', $arr);
		
			if(in_array($ex[0], array('aicrm_account','aicrm_accountscf','aicrm_crmentity'))){
				$sql = 'select aicrm_account.accountid, '. $arr .'
						from aicrm_smartemail_accountsrel
						left join aicrm_account on aicrm_smartemail_accountsrel.accountid=aicrm_account.accountid
						left join aicrm_accountscf on aicrm_account.accountid=aicrm_accountscf.accountid
						left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
						where 1 and aicrm_crmentity.deleted=0
						and aicrm_account.email1<>""
						and aicrm_account.emailstatus in("","Active")
						and aicrm_smartemail_accountsrel.smartemailid='.$campaign[$i][0];

				$res = $genarate->process($sql, "all");
				foreach($res as $rs){
					$replace_arr[$rs[0]]['module'] = 'Accounts';
					$replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
				}
			}else if(in_array($ex[0], array('aicrm_contactdetails','aicrm_crmentity','aicrm_contactaddress','aicrm_contactscf'))){
				$sql = 'select aicrm_contactdetails.contactid, '. $arr .'
						from aicrm_smartemail_contactsrel
						inner join aicrm_contactdetails on aicrm_smartemail_contactsrel.contactid = aicrm_contactdetails.contactid
						left join aicrm_contactaddress on  aicrm_contactdetails.contactid=aicrm_contactaddress.contactaddressid 
						left join aicrm_contactscf on aicrm_contactdetails.contactid = aicrm_contactscf.contactid
						left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
						where 1 and aicrm_crmentity.deleted=0 and aicrm_contactdetails.email<>"" and aicrm_smartemail_contactsrel.smartemailid='.$campaign[$i][0];
		
	
				$res = $genarate->process($sql, "all");
				foreach($res as $rs){
					$replace_arr[$rs[0]]['module'] = 'Contacts';
					$replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
				}
			}else if(in_array($ex[0], array('aicrm_leaddetails','aicrm_leadscf','aicrm_crmentity'))){
				$sql = 'select aicrm_leaddetails.leadid, '. $arr .'
						from aicrm_smartemail_leadsrel
						left join aicrm_leaddetails on aicrm_smartemail_leadsrel.leadid = aicrm_leaddetails.leadid
						left join aicrm_leadscf on aicrm_leaddetails.leadid = aicrm_leadscf.leadid
						left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
						where 1
						and aicrm_crmentity.deleted=0
						and aicrm_leaddetails.email<>""
						and emailstatus in("","Active")
						and aicrm_smartemail_leadsrel.smartemailid='.$campaign[$i][0];

				$res = $genarate->process($sql, "all");
				foreach($res as $rs){
					$replace_arr[$rs[0]]['module'] = 'Leads';
					$replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
				}
			}else if(in_array($ex[0], array('aicrm_opportunity','aicrm_opportunitycf','aicrm_crmentity'))){
				$sql = 'select aicrm_opportunity.opportunityid, '. $arr .'
					from aicrm_smartemail_opportunityrel
					left join aicrm_opportunity on aicrm_smartemail_opportunityrel.opportunityid = aicrm_opportunity.opportunityid
					left join aicrm_opportunitycf on aicrm_opportunity.opportunityid = aicrm_opportunitycf.opportunityid
					left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_opportunity.opportunityid
					where 1
					and aicrm_crmentity.deleted=0
					and aicrm_opportunity.email<>""
					and emailstatus="Active"

					and aicrm_smartemail_opportunityrel.smartemailid='. $campaign[$i][0];

				$res = $genarate->process($sql, "all");
				foreach($res as $rs){
					$replace_arr[$rs[0]]['module'] = 'Opportunity';
					$replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
				}
			}
		}
			//	exit;
		
	

		foreach($replace_arr as $id => $a){
//			echo $id.' => '. $a['module'];
			$find       = array_keys($a);
			$replace    = array_values($a);
			$new_string = str_ireplace($find, $replace, $campaign[$i][7]);
	
			$body = $new_string;

			switch($a['module']){
				case'Accounts':
					$sql="insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,message,from_module,status)
					select
					'". $campaign[$i][0] ."',
					0, 0,
					'". $id ."',
					aicrm_account.accountname,
					aicrm_account.email1,
					ifempty('". $new_string ."','".$email_body."'),
					'Accounts' as module,
					4
					from aicrm_smartemail_accountsrel
					left join aicrm_account on aicrm_smartemail_accountsrel.accountid=aicrm_account.accountid
					left join aicrm_accountscf on aicrm_account.accountid=aicrm_accountscf.accountid
					left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
					where 1
					and aicrm_crmentity.deleted=0
					and aicrm_account.email1<>''
					and emailstatus in('','Active')
					and aicrm_smartemail_accountsrel.smartemailid='". $campaign[$i][0] ."'
					and aicrm_smartemail_accountsrel.accountid='". $id ."' ";
					$genarate->query($sql,"all");
					break;
				case'Contacts':
					$sql="insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,message,from_module,status)
					select
					'".$campaign[$i][0]."',
					'0', '0',
					'". $id ."',
					b.firstname as name,
					b.email as email,
					ifempty('". $new_string ."','".$email_body."'),
					'Contacts' as module,
					'4'
					from aicrm_smartemail_contactsrel a
					left join aicrm_contactdetails b on a.contactid=b.contactid
					left join aicrm_contactscf c on b.contactid=c.contactid
					left join aicrm_crmentity d on d.crmid=b.contactid
					where 1
					and d.deleted=0
					and b.email<>''

					and a.smartemailid='".$campaign[$i][0]."'
					and a.contactid='". $id ."' ";

					$genarate->query($sql,"all");
					break;
				case'Leads':
					$sql="insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,message,from_module,status)
					select
					'".$campaign[$i][0]."',
					'0', '0',
					'". $id ."',
					concat(b.firstname,' ',b.lastname) as name,
					b.email as email,
					ifempty('". $new_string ."','".$email_body."'),
					'Leads' as module,
					'4'
					from aicrm_smartemail_leadsrel a
					left join aicrm_leaddetails  b on a.leadid=b.leadid
					left join aicrm_leadscf c on b.leadid=c.leadid
					left join aicrm_crmentity d on d.crmid=b.leadid
					where 1
					and d.deleted=0
					and b.email<>''
					and emailstatus in('','Active')
					and a.smartemailid='".$campaign[$i][0]."'
					and a.leadid='". $id ."' ";

					$genarate->query($sql,"all");
					break;
				case'Opportunity':
					$sql = "insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,message,from_module,status)
					select
					'".$campaign[$i][0]."',
					'0', '0',
					'". $id ."',
					b.opportunity_name as name,
					b.email as email,
					ifempty('". $new_string ."','".$email_body."'),
					'Opportunity' as module,
					'4'

					from aicrm_smartemail_opportunityrel a
					left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
					left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
					left join aicrm_crmentity d on d.crmid=b.opportunityid
					where 1
					and d.deleted=0
					and b.email<>''
					and emailstatus='Active'

					and a.smartemailid='".$campaign[$i][0]."'
					and a.opportunityid='". $id ."' ";

					$genarate->query($sql,"all");
					break;
			}
		}

	}//End if no map field
	
		$subject = $campaign[$i][2];
		if($new_string=="")
		{
			$body=$email_body;
		}else
		{
			$body=$new_string;
		}
	
		
		$body_html='
		<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
		<html>
		<head>
		<title>'.$subject.'</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head>
		<script>
		var XMLHttpArray = [
				function() {return new XMLHttpRequest()},
				function() {return new ActiveXObject("Msxml2.XMLHTTP")},
				function() {return new ActiveXObject("Msxml2.XMLHTTP")},
				function() {return new ActiveXObject("Microsoft.XMLHTTP")}
		];
		function createXMLHTTPObject(){
				var xmlhttp = false;
				for(var i=0; i<XMLHttpArray.length; i++){
						try{
								xmlhttp = XMLHttpArray[i]();
						}catch(e){
								continue;
						}
						break;
				}
				return xmlhttp;
		}////
		
		function doQuery(click_link) {
			var req = createXMLHTTPObject();
			//alert(click_link);
			<?
				if (isset($_GET["id"])){
					$varid = $_GET["id"];
				}else{
					$varid = "0";
				}
				if (isset($_GET["campaignid"])){
					$varcampaignid = $_GET["campaignid"];
				}else{
					$varcampaignid = "0";
				}
				if (isset($_GET["marketid"])){
					$varmarketid = $_GET["marketid"];
				}else{
					$varmarketid = "0";
				}				
			?>
			var id="";
			id = <?=$varid;?>;
			var campaignid="";
			campaignid = <?=$varcampaignid;?>;
			var marketid="";
			marketid = <?=$varmarketid;?>;
			var strURL = "'.$url_path.'/EDM/update_click_html.php?id="+ id+"&click_link="+click_link+"&campaignid="+campaignid+"&marketid="+marketid;
			//alert(strURL);
				if (req){
					req.onreadystatechange = function(){
						if (req.readyState == 4) { //data is retrieved from server
							if (req.status == 200) { // which reprents ok status                    
								//alert(5555);
							}else{ 
								//alert("There was a problem while using XMLHTTP:\n");
							}
						}            
					}        
					req.open("GET", strURL, true); //open url using get method
					req.send(null);//send the results
				}
			}
		</script>
		
		<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">		
			<table align="center" class="mceItemTable" id="Table_01" border="0" cellspacing="0" cellpadding="0" width="800" >
				<tbody>
					<tr>
						  <td  >
				';

//replace link======================================================================================
	//echo $body; exit;
	$link_all = explode("href=", $body);
	
	$link_array=array();
	$link_array_chk=array();
	rsort($link_all);
	for($kkk=0;$kkk<count($link_all);$kkk++){
		$link_true="";
		$aPanel=$link_all[$kkk];
		//echo $aPanel;exit;
		$link_str= explode('" ',$aPanel);
		//echo "<pre>";
		//print_r($link_str);
		//echo "</pre>";
		//exit;
		for($kk=0;$kk<count($link_str);$kk++){
			$link_name=str_replace('"',"",$link_str[$kk]);
			if(substr($link_name,0,4)=="http"){
				$link_true=$link_name;
				$link_true = explode(">", $link_true);
				$link_array[]=$link_true[0];
			}
		}
	}
	$chk=0;
	$link_array_chk1 = array_unique($link_array);
	$link_array_chk = array_values($link_array_chk1);

	//insert link
	$sql="delete from tbt_report_tab_2 where campaign_id='".$campaignid."' and link_id in(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16)  ";
	$genarate->query($sql);		
	
	$sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','1','หากท่านไม่สามารถอ่านอีเมล์ฉบับนี้ได้ กรุณา คลิกที่นี่','0','Active');";
	$genarate->query($sql);	
	$sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','16','หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก กรุณายกเลิกการรับข่าวสารที่นี่','0','Active');";
	$genarate->query($sql);	
	$link_no=2;
	
	for($kkk=0;$kkk<count($link_array_chk);$kkk++){
		$sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','".$link_no."','".$link_array_chk[$kkk]."','0','Active');";
		$genarate->query($sql);	
		//echo $sql."<br>";
		$link_rename=$url_path."/EDM/update_click_edm.php?param=id212IDD212campaignid212CAMPAIGNNN212marketid212MARKETTT212module212MODUES212crmid212CRMMM212link212".$link_no."212click_type212CLICKTYPE212url212";
		$body=str_replace($link_array_chk[$kkk],$link_rename.str_replace("&","||||",$link_array_chk[$kkk]),$body);
		$link_no=$link_no+1;
	}
	
//replace link======================================================================================
	$body_html .=	$body;	
	
	$body_html=str_replace('IDD','<?=$_REQUEST["id"]?>',$body_html);
	$body_html=str_replace('CAMPAIGNNN','<?=$_REQUEST["campaignid"]?>',$body_html);
	$body_html=str_replace('MARKETTT','<?=$_REQUEST["marketid"]?>',$body_html);
	$body_html=str_replace('MODUES','<?=$_REQUEST["module"]?>',$body_html);
	$body_html=str_replace('CRMMM','<?=$_REQUEST["crmid"]?>',$body_html);
	$body_html=str_replace('CLICKTYPE','html',$body_html);
	
	$body_html.='</td>
					</tr>
					<tr>
						<td height="50" align="center" style="font-size: 0px;" mce_style="font-size:0px;">
							<div style="color: rgb(102, 102, 102); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; color:#666666;">
							หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก <a style="color:#fd8103;" target="_blank" href="'.$url_path.'/EDM/unsub.php?id=<?=$_GET["id"]?>&campaignid=<?=$_GET["campaignid"]?>&marketid=<?=$_GET["marketid"]?>&module=<?=$_GET["module"]?>&crmid=<?=$_GET["crmid"]?>"  onClick="doQuery(16);">กรุณายกเลิกการรับข่าวสารที่นี่</a><br mce_bogus="1" /></div>
						</td>
					</tr>
				</tbody>
			</table>	
			</body>
		</html>			
		';
		//auto เขียนไฟล์ edm_html
		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".html";
		//echo $FileName;
		$FileHandle = fopen($FileName, 'a+') or die("can't open file");
		fwrite($FileHandle,''."\r\n");
		fclose($FileHandle);
		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".php";
		$FileHandle = fopen($FileName, 'a+') or die("can't open file");
		fwrite($FileHandle,''."\r\n");
		fclose($FileHandle);
		
		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".html";
		$FileHandle = fopen($FileName, 'w') or die("can't open file");
		fwrite($FileHandle,$body_html."\r\n");
		fclose($FileHandle);
		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".php";
		$FileHandle = fopen($FileName, 'w') or die("can't open file");
		fwrite($FileHandle,$body_html."\r\n");
		fclose($FileHandle);
		


//			//Insert Contacts======================================
//			$sql="
//			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
//			select
//			'".$campaign[$i][0]."',
//			'0',
//			'0',
//			b.contactid as id,
//			b.firstname as name,
//			b.email as email,
//			'Contacts' as module,
//			'4'
//
//			from aicrm_smartemail_contactsrel a
//			left join aicrm_contactdetails b on a.contactid=b.contactid
//    		left join aicrm_contactscf c on b.contactid=c.contactid
//    		left join aicrm_crmentity d on d.crmid=b.contactid
//			where 1
//			and d.deleted=0
//			and b.email<>''
//
//			and a.smartemailid='".$campaign[$i][0]."'
//
//			";
//			//group by b.email
//			$genarate->query($sql,"all");
//
//		 //Insert Opportunity======================================
//			$sql="
//			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
//			select
//			'".$campaign[$i][0]."',
//			'0',
//			'0',
//			b.opportunityid as id,
//			b.opportunity_name as name,
//			b.email as email,
//			'Opportunity' as module,
//			'4'
//
//			from aicrm_smartemail_opportunityrel a
//			left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
//			left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
//			left join aicrm_crmentity d on d.crmid=b.opportunityid
//			where 1
//			and d.deleted=0
//			and b.email<>''
//			and emailstatus='Active'
//
//			and a.smartemailid='".$campaign[$i][0]."'
//
//			";
//			//group by b.email
//			$genarate->query($sql,"all");
//
//			//Insert Lead======================================
//			$sql="
//			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
//			select
//			'".$campaign[$i][0]."',
//			'0',
//			'0',
//			b.leadid as id,
//			concat(b.firstname,' ',b.lastname) as name,
//			b.email as email,
//			'Leads' as module,
//			'4'
//			from aicrm_smartemail_leadsrel a
//			left join aicrm_leaddetails  b on a.leadid=b.leadid
//			left join aicrm_leadscf c on b.leadid=c.leadid
//			left join aicrm_crmentity d on d.crmid=b.leadid
//			where 1
//			and d.deleted=0
//			and b.email<>''
//			and emailstatus in('','Active')
//			and a.smartemailid='".$campaign[$i][0]."'
//
//			";
//			//group by b.email
//			$genarate->query($sql,"all");
//			//Insert Account======================================
//			$sql="
//			select
//			b.accountid as id,
//			b.accountname as name,
//			email1  as email,
//			'Accounts' as module
//			from aicrm_smartemail_accountsrel a
//			left join aicrm_account  b on a.accountid=b.accountid
//			left join aicrm_accountscf c on b.accountid=c.accountid
//			left join aicrm_crmentity d on d.crmid=b.accountid
//			where 1
//			and d.deleted=0
//			and email1<>''
//			and emailstatus in('','Active')
//			and a.smartemailid='".$campaign[$i][0]."'
//
//			";
//			//group by email1
//			$dataacc = $genarate->process($sql,"all");
//
//			for($k=0;$k<count($dataacc);$k++){
//				$email=split(",",$dataacc[$k][2]);
//				for($w=0;$w<count($email);$w++){
//					if($email[$w]!=""){
//						$sql="
//						insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
//						values('".$campaign[$i][0]."','0','0','".$dataacc[$k][0]."','".$dataacc[$k][1]."',
//						'".$email[$w]."','".$dataacc[$k][3]."','4')
//						";
//						$genarate->query($sql,"all");
//					}
//				}
//			}
		//}//for $emailtargetlist
		
//จัดการข้อมูล email ซ้ำ===========================================================================			
	//set hotmail,gmail,yahoo status=3=================================================
		$sql="update ".$new_table." set `status`=3,domain_name='hotmail' where 1 and `to_email` like'%hotmail%';";
		$genarate->query($sql,"all");
		$sql="update ".$new_table." set `status`=3,domain_name='gmail' where 1 and `to_email` like'%gmail%';";
		$genarate->query($sql,"all");
		$sql="update ".$new_table." set `status`=3,domain_name='yahoo' where 1 and `to_email` like'%yahoo%';";
		$genarate->query($sql,"all");
		$sql="update ".$new_table." set domain_name='others' where 1 and status <>3 ;";
		$genarate->query($sql,"all");
		
		$sql="update ".$new_table." set active=1,report=1 where 1 ;";
		$genarate->query($sql,"all");
		
		$sql="update ".$new_table." set group_send=1 where 1 ;";
		$genarate->query($sql,"all");
		
		$sql="
		update ".$new_table." as a
		inner join (
		select 
		to_email 
		from ".$new_table."
		where 1 GROUP BY to_email HAVING count( * ) >1 
		)as b
		on a.to_email = b.to_email 
		set a.active=0,a.report=0 ,a.duplicate=1;
		";
		//echo $sql."<br>"; exit;
		$genarate->query($sql,"all");
		$sql="
		select min(id)as id
		from ".$new_table."
		where 1 
		and duplicate=1
		GROUP BY to_email
		HAVING count( * ) >1
		";
		//echo $sql."<br>";exit;
		$data_d = $genarate->process($sql,"all");
		for($k=0;$k<count($data_d);$k++){
			$sql="update ".$new_table." set active=1,report=1 where 1 and id='".$data_d[$k][0]."';";
			$genarate->query($sql,"all");
		}
		
		//แก้ตัวอักษรแปลกๆๆ
		$sql="
		select 
		id,
		to_email 
		from ".$new_table."
		where 1 
		and active=1
		and report=1
		";
		$data_d = $genarate->process($sql,"all");
		for($k=0;$k<count($data_d);$k++){
			$to_email=$data_d[$k][1];
			$to_email_old=$data_d[$k][1];
			$to_email=str_replace("$","",$to_email);
			$to_email=str_replace("'","",$to_email);
			$to_email=str_replace(",","",$to_email);
			$to_email=str_replace("ี","",$to_email);
			$to_email=str_replace("ุ","",$to_email);
			$to_email=str_replace("ู","",$to_email);
			
			if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $to_email)){
				//echo $to_email."<br>";
			}else{
				$sql="update ".$new_table." set active=1,report=1,mistype_email=1,to_email_old='".$to_email_old."',to_email='".$to_email."' where 1 and id='".$data_d[$k][0]."';";
				$genarate->query($sql,"all");
			}
		}
		//exit;
		//เช็ค format ก่อนส่ง		
		$sql="
		select 
		id,
		to_email 
		from ".$new_table."
		where 1 
		and active=1
		and report=1
		";
		$data_d = $genarate->process($sql,"all");
		for($k=0;$k<count($data_d);$k++){
			$to_email=$data_d[$k][1];
			if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $to_email)){
				
			}else{
				$sql="update ".$new_table." set active=0,report=0,invalid_email=1 where 1 and id='".$data_d[$k][0]."';";
				$genarate->query($sql,"all");
			}
		}
		$sql="update ".$new_table." set active=0,report=0,invalid_email=1 where 1 and right(to_email,1)='.';";
		$genarate->query($sql,"all");
		$sql="update ".$new_table." set active=0,report=0,invalid_email=1 where 1 and to_email LIKE  '%..%' ;";
		$genarate->query($sql,"all");
		$sql="update ".$new_table." set active=0,report=0,invalid_email=1 where 1 and to_email LIKE  '%.@%' ;";
		$genarate->query($sql,"all");
		
		//set hotmail,gmail,yahoo status=3=================================================
//จัดการข้อมูล email ซ้ำ===========================================================================	
		$sql="
		CREATE TABLE ".$new_table."_click (
		id int(11) NOT NULL auto_increment,
		`dateclick` varchar(30) default NULL,
		`remoteip` varchar(150) default NULL,
		`page` varchar(150) default NULL,
		`device` varchar(150) default NULL,
		`device_all` varchar(250) default NULL,
		`ctnum` int(11) default NULL,
		`uniqueid` int(11) default NULL,
		`email` varchar(150) default NULL,
		PRIMARY KEY  (id),
		KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
		if($genarate->query($sql,"all")){}
		
		$sql="
		CREATE TABLE ".$new_table."_open (
		id int(11) NOT NULL auto_increment,
		`dateopen` varchar(30) default NULL,
		`remoteip` varchar(150) default NULL,
		`device` varchar(150) default NULL,
		`device_all` varchar(250) default NULL,
		`ctnum` int(11) default NULL,
		`uniqueid` int(11) default NULL,
		`email` varchar(150) default NULL,
		PRIMARY KEY  (id),
		KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
		if($genarate->query($sql,"all")){}		
		
		//1.เพิ่มฟิวส์
		$sql="
		CREATE TABLE ".$new_table."_report (
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
		) ENGINE=MyISAM DEFAULT CHARSET=utf8; ";
		//echo $sql;exit;
		if($genarate->query($sql,"all")){}
		
		$sql="ALTER TABLE  `".$new_table."` ADD INDEX (  `to_email` );";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE  `".$new_table."` ADD INDEX (  `active` );";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE  `".$new_table."` ADD INDEX (  `report` );";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE  `".$new_table."_open` ADD INDEX (  `uniqueid` );";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE  `".$new_table."_click` ADD INDEX (  `uniqueid` );";
		if($genarate->query($sql,"all")){};	
		
		$sql="ALTER TABLE `".$new_table."` ADD `chk_bounce` INT( 2 ) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."` ADD `bounce` INT( 2 ) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."` ADD `chk` INT( 2 ) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."_open` ADD `bounce` INT( 2 ) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."_click` ADD `bounce` INT( 2 ) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		
		$sql="ALTER TABLE `".$new_table."_open` ADD `emailtargetlistid` int(19) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."_open` ADD `from_id` int(19) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."_open` ADD `domain_name` varchar(150) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		
		$sql="ALTER TABLE `".$new_table."_click` ADD `emailtargetlistid` int(19) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."_click` ADD `from_id` int(19) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		$sql="ALTER TABLE `".$new_table."_click` ADD `domain_name` varchar(150) NOT NULL ;";
		if($genarate->query($sql,"all")){};	
		
		//2.update ว่าข้อมูลที่ open หรือ click เป็นข้อมูลที่เกิดจาก email ที่เป็น bounce
		$sql="update ".$new_table."_open set email =0,bounce=0 ";
		if($genarate->query($sql,"all")){};	
		$sql="update ".$new_table."_click set email =0,bounce=0 ";
		if($genarate->query($sql,"all")){};	
		$sql="update ".$new_table." set chk=0,bounce=0,chk_bounce=0 ";
		if($genarate->query($sql,"all")){};		
		
		//update EDM tbt_report_tab_1
		$sql="delete from tbt_report_tab_1 where campaign_id='".$campaignid."'";
		if($genarate->query($sql,"all")){};
		//$campaignid=$campaign[$i][2];
		//$campaign_name
		$start_date=$date_start;
		$sql="select * from ".$new_table." where campaignid='".$campaignid."' ; ";
		$data_email_import= $genarate->process($sql,"all");
		$sql="select * from ".$new_table." where campaignid='".$campaignid."' and report=0 and active=0 and bounce=0 and invalid_email=1; ";
		$data_email_invalid= $genarate->process($sql,"all");
		$sql="select * from ".$new_table." where campaignid='".$campaignid."' AND `duplicate` =0 and report=1 and active=1; ";
		$data_email_dup0= $genarate->process($sql,"all");
		$sql="SELECT * FROM  ".$new_table." where campaignid='".$campaignid."' AND  `duplicate` !=0 AND report =1 AND active =1 GROUP BY  `to_email` ; ";
		$data_email_dup1= $genarate->process($sql,"all");
		$sql="SELECT * FROM  ".$new_table." where campaignid='".$campaignid."' AND `duplicate` !=0 ; ";
		$data_email_dup1_all= $genarate->process($sql,"all");
		$sql="SELECT * FROM  ".$new_table." where campaignid='".$campaignid."' AND report =1 AND active =1;";
		$data_email_send= $genarate->process($sql,"all");
		$sql="select * from ".$new_table." where campaignid='".$campaignid."' and domain_name='hotmail' and report=1 and active=1; ";
		$data_hotmail= $genarate->process($sql,"all");
		$sql="select * from ".$new_table." where campaignid='".$campaignid."' and domain_name='gmail' and report=1 and active=1; ";
		$data_gmail= $genarate->process($sql,"all");
		$sql="select * from ".$new_table." where campaignid='".$campaignid."' and domain_name='yahoo' and report=1 and active=1; ";
		$data_yahoo= $genarate->process($sql,"all");
		$sql="select * from ".$new_table." where campaignid='".$campaignid."' and domain_name='others' and report=1 and active=1; ";
		$data_others= $genarate->process($sql,"all");
		
		//tbt_report_tab_1
		$sql="insert into tbt_report_tab_1(campaign_id,campaign_name,start_date,email_import,email_invalid,email_dup0,email_dup1
		,email_dup1_all,email_send,email_hotmail,email_yahoo,email_gmail,email_others,status)
		values('".$campaignid."','".$campaign_name."','".$start_date."','".count($data_email_import)."','".count($data_email_invalid)."','".count($data_email_dup0)."','".count($data_email_dup1)."'
		,'".count($data_email_dup1_all)."','0','".count($data_hotmail)."','".count($data_yahoo)."','".count($data_gmail)."','".count($data_others)."','Active'
		)
		";
		if($genarate->query($sql,"all")){};
		
		//tbt_report_tab_3
		$sql1="delete from tbt_report_tab_3 where campaign_id ='".$campaignid."'";
		$genarate->query($sql1);
		
		$sql="insert into tbt_report_tab_3(campaign_id,email_send,status)
		values('".$campaignid."','".count($data_email_send)."','Active'
		)
		";
		if($genarate->query($sql,"all")){};
		//echo "666";
		$sql="update aicrm_smartemail set email_setup =1 where smartemailid='".$campaignid."'";
		if($genarate->query($sql,"all")){};
		
	}//for $campaign
	
	set_confix_email($path,$url_path);
//get email====================================================================================//

//echo "<script type='text/javascript'>alert('Set Up Email Complete');window.close();  window.opener.parent.location.replace('../index.php?action=DetailView&module=Smartemail&record=".$crmid."&parenttab=Marketing');<//script>";
//echo "<script type='text/javascript'>alert('Setup/Send Email Complete');window.close();  window.opener.parent.location.replace('../index.php?action=DetailView&module=Smartemail&record=".$crmid."&parenttab=Marketing');<///script>";
//sent email====================================================================================
function set_confix_email($path,$url_path){

global $genarate;	
	
$sql="select limits,sleep from tbm_config_email where id=3";
$data = $genarate->process($sql,"all");

$limit=$data[0][0];
$max_loop=$data[0][1];

$chk=0;
$loop=ceil($limit/$max_loop);
for($kk=0;$kk<500;$kk++){

	$date=date('Y-m-d H:i:s',mktime(date('H'), date('i'), 1, date('m'), date('d'), date('Y')));
	
	$crmid=$_REQUEST["crmid"];
	$crmid = '299082';
	if($crmid!=""){
		$sql="
		select aicrm_smartemail.*		
		from aicrm_smartemail
		left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_smartemail.smartemailid
		where aicrm_smartemail.smartemailid ='".$crmid."'
		and aicrm_smartemail.email_status='Active'
		and aicrm_crmentity.deleted =0
		and aicrm_smartemail.email_setup =1
		";		
	}else{
		$sql="
		select *		
		from aicrm_smartemail
		left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_smartemail.smartemailid
		where aicrm_smartemail.email_status='Active'
		and aicrm_crmentity.deleted =0
		and aicrm_smartemail.email_setup=1
		and NOW() between ( DATE_ADD(cast( concat(`email_start_date` ,' ' , `email_start_time` ) as datetime ),  INTERVAL - 5 MINUTE)  )
		and  (DATE_ADD(cast( concat(`email_start_date` ,' ' , `email_start_time` ) as datetime ),  INTERVAL 5 MINUTE) )
		";
	}

	$campaign = $genarate->process($sql,"all");	
	for($i=0;$i<count($campaign);$i++){
					
			$from_email_id=(explode(":",$campaign[$i][3]));
			$reply_email_id=(explode(":",$campaign[$i][4]));
			
			$edm_email_id=$campaign[$i][10];
			$bounce_email_id=$campaign[$i][9];
			
			$date_start=$campaign[$i][5]." ".$campaign[$i][6];
			$campaignid=$campaign[$i][0];
			//$marketid=$campaign[$i][5];

			//$new_table="tbt_email_log_campaignid_".$campaign[$i][2]."_marketid_".$campaign[$i][5];
			$new_table="tbt_email_log_smartemailid_".$campaign[$i][0];
				
			//from email	
			$sql = "select email_user,email_pass from aicrm_config_sendemail where 1 and deleted=0 and email_from_name='".$from_email_id[0]."' and email_user ='".$from_email_id[1]."' and email_type='account' ";
			$emailsend = $genarate->process($sql,"all");
			
			$from_name = $from_email_id[0];
			$from_email = $emailsend[0][0];
			$user_send=$emailsend[0][0];
			$pass_send=$emailsend[0][1];
			$subject = $campaign[$i][2];
			$body=$campaign[$i][7];
			
			//Reply Mail
			$sql = "select email_user,email_pass,email_from_name from aicrm_config_sendemail where 1 and deleted=0 and email_from_name='".$reply_email_id[0]."' and email_user='".$reply_email_id[1]."'  and email_type='reply' ";
			$emailsend = $genarate->process($sql,"all");
			$reply_name=$emailsend[0][2];
			$reply_email=$emailsend[0][0];
			
			//Bounce Email
			$sql = "select email_user,email_pass,email_from_name from aicrm_config_sendemail where 1 and deleted=0 and email_user='".$bounce_email_id."' and email_type='bounce' ";
			//echo $sql."<br>";
			$emailsend = $genarate->process($sql,"all");
			$bounce_name=$emailsend[0][2];
			$bounce_email=$emailsend[0][0];			
			
			//EDM Email
			$sql = "select email_user,email_pass,email_server,email_port,email_from_name from aicrm_config_sendemail where 1 and deleted=0 and email_server='".$edm_email_id."' and email_type='edm' ";
			//echo $sql."<br>";
			$emailsend = $genarate->process($sql,"all");
			$edm_user=$emailsend[0][0];
			$edm_pass=$emailsend[0][1];
			$edm_mail_server=$emailsend[0][2];
			$edm_mail_server_port=$emailsend[0][3];
			$edm_from_name=$emailsend[0][4];
			//$bounce_name,$bounce_email,$edm_user,$edm_pass,$edm_mail_server,$edm_mail_server_port,$edm_from_name

			$sql="
			select id,campaignid,email_marketingid,emailtargetlistid,from_module,from_id,to_name,to_email
			,message
			from ".$new_table." where status in (3,4)  and check_send=0 and active=1 
			and group_send=1 
			and invalid_email=0 
			
			order by id limit 0,".$max_loop;
			//echo $sql."<br>";exit;
			$send_email = $genarate->process($sql,"all");

			if(count($send_email)>0){
				for($pp=0;$pp<count($send_email);$pp++){
					$sql="update ".$new_table." set check_send=1 where id='".$send_email[$pp][0]."'; ";
					$genarate->query($sql);
				}
				$update=send_mail($from_name,$from_email,$user_send,$pass_send,$subject,$body,$send_email,$new_table,$url_click,$date_start,$campaignid,$marketid,$reply_name,$reply_email,$bounce_name,$bounce_email,$edm_user,$edm_pass,$edm_mail_server,$edm_mail_server_port,$edm_from_name,$path,$url_path );
			}
						
			$sql="select * from ".$new_table." where active=1 and report=1 ";	
			$data = $genarate->process($sql,"all");
			$count1=count($data);	
			$sql="select * from ".$new_table." where status in(3,4) and active=1 and report=1 ";	
			$data = $genarate->process($sql,"all");
			$count2=count($data);
			if($count1!="0" and $count2<="0"){
				//ปืด campaign การส่ง email
				$sql = "
				update aicrm_smartemail set 
				email_status ='InActive'
				where smartemailid='".$campaignid."' 
				";
				//echo $sql;exit;
				$genarate->query($sql);	
				//update วันที่สิ้นสุดการส่งเมล์
				$sql="update tbt_report_tab_1 set end_date=now() where campaign_id='".$campaignid."'";
				$genarate->query($sql);	
				
				$sql="select from_module,from_id from ".$new_table." where status=2";	
				$data = $genarate->process($sql,"all");
				for($i=0;$i<count($data);$i++){
					if($data[$i][0]=="Accounts"){
						$sql="update  aicrm_accountscf set cf_2341='InActive' where accountid 	='".$data[$i][1]."' ";
						$genarate->query($sql);		
					}else if($data[$i][0]=="Contacts"){
						$sql="update  aicrm_contactscf set cf_2344='InActive' where contactid='".$data[$i][1]."' ";
						$genarate->query($sql);		
					}else if($data[$i][0]=="Leads"){
						$sql="update  aicrm_leaddetails set emailstatus='InActive' where leadid='".$data[$i][1]."' ";
						$genarate->query($sql);		
					}else if($data[$i][0]=="EmailTarget"){
						$sql="update  aicrm_emailtargetscf set cf_926='InActive' where emailtargetid	='".$data[$i][1]."' ";
						$genarate->query($sql);		
					}else if($data[$i][0]=="Opportunity"){
						$sql="update  aicrm_opportunity set emailstatus='InActive' where opportunityid	='".$data[$i][1]."' ";
						$genarate->query($sql);		
					}
				}			
			}
			$sql="SELECT email_send FROM tbt_report_tab_3 WHERE 1 and campaign_id='".$campaignid."'";
			$data = $genarate->process($sql,"all");
			if(count($data)>0){
				$sql="SELECT * FROM ".$new_table." WHERE 1 and status=1 and invalid_email=0";
				$data_send_complete = $genarate->process($sql,"all");
				$sql="SELECT * FROM ".$new_table." WHERE 1 and status not in(1) and invalid_email=0";
				$data_send_uncomplete = $genarate->process($sql,"all");
				$sql="SELECT * FROM ".$new_table." WHERE 1 and invalid_email=1";
				$data_invalid_email = $genarate->process($sql,"all");
				$sql="update tbt_report_tab_3 set email_send_complete='".count($data_send_complete)."',email_send_uncomplete='".$data_send_complete."' WHERE 1 and campaign_id='".$campaignid."' ";
				$genarate->query($sql);		
			}
			//sleep(30);
		//}//if limit
		$sql="update aicrm_smartemail set email_send=1 where smartemailid ='".$campaignid."'";
		if($genarate->query($sql,"all")){};
	}//for campaign
//sent email=====================================================================================================================
	}
	echo "<script type='text/javascript'>alert('Setup/Send Email Complete');window.close();  window.opener.parent.location.replace('../index.php?action=DetailView&module=Smartemail&record=".$crmid."&parenttab=Marketing');</script>";
}

function send_mail($from_name,$from_email,$user_send,$pass_send,$subject,$body,$send_email,$new_table,$url_click,$date_start,$campaignid,$marketid,$reply_name,$reply_email,$bounce_name,$bounce_email,$edm_user,$edm_pass,$edm_mail_server,$edm_mail_server_port,$edm_from_name,$path,$url_path){
	
	global $mailer,$genarate;
   
//	$transport = Swift_SmtpTransport::newInstance('mail.aisyst.com', 25)
//      ->setUsername("support_crm@aisyst.com")
//      ->setPassword("1qaz2WSX");

	$transport = Swift_SmtpTransport::newInstance($edm_mail_server,$edm_mail_server_port)
	  ->setUsername($edm_user)
	  ->setPassword($edm_pass)
	;
for($p=0;$p<count($send_email);$p++){
		
		$to_email=$send_email[$p][7];
		//if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $to_email)){
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $to_email)){
			$mailer = Swift_Mailer::newInstance($transport);
			$mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(50,30));
			//echo $to_name." ".$to_email."<br>";
			// Create the message
		
			$message = Swift_Message::newInstance()
			//echo $to_name." ".$to_email."<br>";
				->setContentType("text/plain; charset=UTF-8")
				//->setBody($plain,'text/plain')			
				// Give the message a subject
				//->setSubject($subject)
				->setSubject("=?UTF-8?B?".base64_encode($subject)."?=")
				//->setCharset('utf-8')
				
				// Set the From address with an associative array
				->setFrom(array($from_email =>$from_name))
				// Add Reply to
				->setReplyTo(array($reply_email=>$reply_name))
     			->setReturnPath($bounce_email)
				// Optionally add any attachments
				//->attach(Swift_Attachment::fromPath('my-document.pdf'))
			;
			
			$id=$send_email[$p][0];
			$campaignid=$send_email[$p][1];
			$email_marketingid=$send_email[$p][2];
			$emailtargetlistid=$send_email[$p][3];
			$from_module=$send_email[$p][4];
			$from_id=$send_email[$p][5];
			$to_name=$send_email[$p][6];
			$body=$send_email[$p][8];
			
			$folder_name=date('Ymd',strtotime($date_start))."_".$campaignid;
			$url_click_view="";
			$url_click_view=$url_path.'/EDM/update_click_edm.php?param=id212'.$id.'212campaignid212'.$campaignid.'212marketid212'.$email_marketingid.'212module212'.$from_module.'212crmid212'.$from_id;
			$url_click_view.='212link2121212click_type212CLICKTYPE212url212'.$url_path.'/EDM/'.$folder_name.'/'.$folder_name.'.php?id='.$id.'*campaignid='.$campaignid;
			$url_click_view.='*marketid='.$email_marketingid.'*module='.$from_module.'*crmid='.$from_id;
			//echo $url_click;exit;
			$url_click_link="";
			$url_click_link=$url_path.'/EDM/update_click_edm.php?param=id212'.$id.'212campaignid212'.$campaignid.'212marketid212'.$email_marketingid.'212module212'.$from_module.'212crmid212'.$from_id;
			$url_click_link.='212link2122212click_type212CLICKTYPE212url212'.str_replace("&","*",$url_click);
			
			$url_un_sub="";
			$url_un_sub=$url_path.'/EDM/update_click_edm.php?param=id212'.$id.'212campaignid212'.$campaignid.'212marketid212'.$email_marketingid.'212module212'.$from_module.'212crmid212'.$from_id;
			$url_un_sub.='212link21216212click_type212CLICKTYPE212url212'.$url_path.'/EDM/unsub.php?id='.$id.'*campaignid='.$campaignid.'*marketid='.$email_marketingid.'*module='.$from_module.'*crmid='.$from_id;

			$body_html="";
			//$body_html.=$send_email[$p][8];
			$body_html='
				<table align="center" class="mceItemTable" id="Table_01" border="0" cellspacing="0" cellpadding="0" width="800">
					<tbody>
						<tr>
							<td height="40" align="right">
							<div style="text-align: right; color: rgb(106, 106, 106); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; text-align: right; color:#6a6a6a;">
							หากท่านไม่สามารถอ่านอีเมล์ฉบับนี้ได้ กรุณา <a style=" color:#fd8103; font-family: tahoma; font-size: 12px;" target="_blank" 
							href='.str_replace('CLICKTYPE','edm',$url_click_view).'>คลิกที่นี่</a> </div>
							</td>
						</tr>
						<tr>
						
						  <td style="font-family: tahoma; font-size: 12px; " mce_style="font-size:12px;font-family: tahoma;">
				';
				//$body_html.=$body;	
				
//replace link======================================================================================
				$link_all = explode("href=", $body);
				$link_array=array();
				$link_array_chk=array();
				for($kkk=0;$kkk<count($link_all);$kkk++){
					$link_true="";
					$aPanel=$link_all[$kkk];
					$link_str= explode('" ',$aPanel);
					for($kk=0;$kk<count($link_str);$kk++){
						$link_name=str_replace('"',"",$link_str[$kk]);
						if(substr($link_name,0,4)=="http"){
							$link_true=$link_name;
							$link_true = explode(">", $link_true);
							$link_array[]=$link_true[0];
						}
					}
				}
				$link_array_chk1 = array_unique($link_array);
				$link_array_chk = array_values($link_array_chk1);
				
				//$genarate->query($sql);	
				$link_no=2;
				$link_rename="";
				$link_chk="";	
				$body1= $body;
				$link_replace=array();
				rsort($link_array_chk);
				for($kkk=0;$kkk<count($link_array_chk);$kkk++){
					//$body1="";
					$link_rename="";
					$link_chk="";
					$link_chk=$link_array_chk[$kkk];
					$link_rename=$url_path."/EDM/update_click_edm.php?param=id212IDD212campaignid212CAMPAIGNNN212marketid212MARKETTT212module212MODUES212crmid212CRMMM212link212".$link_no."212click_type212CLICKTYPE212url212".str_replace("&","||||",$link_chk);
					$link_replace[]=$link_rename;
					$body1=str_replace($link_chk,"xxxxx".$link_no."xxxxx",$body1);
					$link_no=$link_no+1;
				}
				for($kkk=0;$kkk<count($link_replace);$kkk++){
					$lnk="xxxxx".($kkk+2)."xxxxx";
					$body1=str_replace($lnk,$link_replace[$kkk],$body1);	
				}
//replace link======================================================================================
				$body_html .=$body1;	
				$body_html=str_replace('IDD',$id,$body_html);
				$body_html=str_replace('CAMPAIGNNN',$campaignid,$body_html);
				$body_html=str_replace('MARKETTT',$email_marketingid,$body_html);
				$body_html=str_replace('MODUES',$from_module,$body_html);
				$body_html=str_replace('CRMMM',$from_id,$body_html);
				$body_html=str_replace('CLICKTYPE','edm',$body_html);
				
				$body_html.='</td>
						</tr>
						<tr>
							<td height="50" align="center" style="font-size: 0px;" mce_style="font-size:0px;">
								<div style="color: rgb(102, 102, 102); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; color:#666666;">
								หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก <a style="color:#fd8103;" target="_blank" href='.str_replace('CLICKTYPE','edm',$url_un_sub).'>กรุณายกเลิกการรับข่าวสารที่นี่</a><br mce_bogus="1" /></div>
							</td>
						</tr>
					</tbody>
				</table>			
			';	
			
			$url_load_open="<img src=".$url_path."/EDM/update_open.php?id=".$id."&campaignid=".$campaignid."";
			$url_load_open.="&email_marketingid=".$email_marketingid."&emailtargetlistid=".$emailtargetlistid."&table=".$new_table."";
			$url_load_open.="&from_module=".$from_module."&from_id=".$from_id."&email=".trim($to_email)."";
			$url_load_open.=" ' alt='' width='1' height='1'>";	
			
			//echo $url_load_open; exit;
			//$body_html=$send_email[$p][8]."<br>".$body_html.$unsub.$url_load_open;
			$body_html=$body_html.$unsub.$url_load_open;
			//2015-06-29
			//$body_html=$body;
			//echo $body_html."<br><br><br><br><br>";exit;
			
			if($subject!="" && $body_html!=""){//echo "ddd";
				//$message->addPart(iconv("TIS-620", "UTF-8",$body_html), 'text/html');
				$message->addPart($body_html, 'text/html');
				$message->setTo(array($to_email => $to_name));
				$result = $mailer->send($message);	
				
				$sql = "update ".$new_table." set date_start='".date('Y-m-d H:i:s')."', status='".$result."' where id='".$id."' and campaignid='".$campaignid."' and email_marketingid='".$email_marketingid."' and emailtargetlistid='".$emailtargetlistid."'";
				$genarate->query($sql);	
				
				$sql1="update tbt_report_tab_1 set email_send=email_send+1 where campaign_id='".$campaignid."'";
				$genarate->query($sql1);	
			}					
		}else{
			$sql = "update ".$new_table." set date_start='".date('Y-m-d H:i:s')."', status='2', invalid_email=1 where id='".$id."' and campaignid='".$campaignid."' and email_marketingid='".$email_marketingid."' and emailtargetlistid='".$emailtargetlistid."'";
			$genarate->query($sql);
			
			$sql1="update tbt_report_tab_1 set email_invalid=email_invalid+1 where campaign_id='".$campaignid."'";
			$genarate->query($sql1);	
		}		
	}
	return $update;
	//loop ส่ง email
	
}
	
?>

</body>
</html>