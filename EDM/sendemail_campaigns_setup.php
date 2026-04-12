<?php
	require_once ("../config.inc.php");
	global $path,$url_path;
	
	$path=$root_directory;//"C:/AppServ/www/mjdp";
	$url_path=$site_URL;//"http://".$_SERVER['HTTP_HOST']."/mjdp";
	
	require_once ($path."/library/dbconfig.php");
	require_once ($path."/library/genarate.inc.php");
	require_once ($path."/library/myFunction.php");
	require_once ($path."/lib/swift_required.php");

	$genarate = new genarate($dbconfig ,"DB");
//get email====================================================================================
	$date=date('Y-m-d H:i:'."01");
	$date=date('Y-m-d H:i:s',mktime(date('H'), date('i')+10, 1, date('m'), date('d'), date('Y')));
	
	$crmid=$_REQUEST["crmid"];
	if($crmid!=""){
		$sql="
		select 
		email_marketingname,
		from_name,
		campaignid,
		template_id,
		date_start,
		id,
		from_email,
		mail_email_type,
		date_start
		,url_click
		
		from aicrm_campaign_email_marketing
		where campaignid='".$crmid."'
		and mail_status='Active'
		and deleted =0
		and setup_email=0
		";		
	}else{
		$sql="
		select 
		email_marketingname,
		from_name,
		campaignid,
		template_id,
		date_start,
		id,
		from_email,
		mail_email_type,
		date_start
		,url_click
		
		from aicrm_campaign_email_marketing
		where mail_status='Active'
		and deleted =0
		and setup_email=0
		";
	}
	//date_start ='".$date."' and 
	//echo $sql;exit;
	$campaign = $genarate->process($sql,"all");
	for($i=0;$i<count($campaign);$i++){
		$mail_email_type=$campaign[$i][7];
		$new_table="tbt_email_log_campaignid_".$campaign[$i][2]."_marketid_".$campaign[$i][5];
		$url_click=$campaign[$i][9];
		$date_start=$campaign[$i][4];
		$campaignid=$campaign[$i][2];
		$campaign_name=$campaign[$i][0];
		$marketid=$campaign[$i][5];
		
		$folder_name=date('Ymd',strtotime($campaign[$i][8]))."_".$campaign[$i][2]."_".$campaign[$i][5];
		if (!file_exists($path."/EDM/".$folder_name)){
			if (!mkdir($path."/EDM/".$folder_name, 0777, true)){}
		}
			
		$sql="select subject,body from aicrm_emailtemplates where templateid='".$campaign[$i][3]."'";
		$template = $genarate->process($sql,"all");
		$subject = $template[0][0];
		$body=$template[0][1];
		
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
								/*<a style="color:#fd8103;" target="_blank" href='.$url_click_link.'>
								'.$body.'
								</a><br mce_bogus="1" />	*/

//replace link======================================================================================
	$link_all = explode("href=", $body);
	/*echo "<pre>";
	print_r($link_all);
	echo "</pre>";
	exit;*/
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
	/*echo "<pre>";
	print_r($link_array_chk);
	echo "</pre><br>";
	exit;*/
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
	//exit;
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
		
		$sql="
		select 
		emailtargetlistid 
		from aicrm_campaignmaillistrel
		where campaignid='".$campaign[$i][2]."'
		";
		//echo $sql;exit;
		$emailtargetlist = $genarate->process($sql,"all");
		//print_r($emailtargetlist);exit;
		for($j=0;$j<count($emailtargetlist);$j++){
		 //Insert Opportunity======================================
			$sql="
			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
			select 
			'".$campaign[$i][2]."',
			'".$campaign[$i][5]."',
			'".$emailtargetlist[$j][0]."',
			b.opportunityid as id,
			b.opportunity_name as name,
			c.email as email,
			'Opportunity' as module,
			'4'
			
			from aicrm_emailtargetlist_opportunityrel a
			left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
			left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
			left join aicrm_crmentity d on d.crmid=b.opportunityid
			where 1
			and d.deleted=0
			and c.email<>''
			and emailstatus='Active'

			and a.emailtargetlistid='".$emailtargetlist[$j][0]."'
			group by c.email
			";
			//cf_2352 email
			//cf_2353 email status
			//echo $sql."<br>";exit;
			$genarate->query($sql,"all");	
			
		 //Insert EmailTarget======================================
			$sql="
			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
			select 
			'".$campaign[$i][2]."',
			'".$campaign[$i][5]."',
			'".$emailtargetlist[$j][0]."',
			b.emailtargetid as id,
			b.emailtarget_name as name,
			c.cf_916 as email,
			'EmailTarget' as module,
			'4'
			
			from aicrm_emailtargetlist_emailtargetrel a
			left join aicrm_emailtargets  b on a.emailtargetid=b.emailtargetid
			left join aicrm_emailtargetscf c on b.emailtargetid=c.emailtargetid
			left join aicrm_crmentity d on d.crmid=b.emailtargetid
			where 1
			and d.deleted=0
			and c.cf_916<>''
			and cf_926='Active'

			and a.emailtargetlistid='".$emailtargetlist[$j][0]."'
			group by c.cf_916
			";
			//echo $sql; exit;
			$genarate->query($sql,"all");	
			//exit;
			
			//Insert Lead======================================
			$sql="
			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
			select 
			'".$campaign[$i][2]."',
			'".$campaign[$i][5]."',
			'".$emailtargetlist[$j][0]."',
			b.leadid as id,
			concat(b.firstname,' ',b.lastname) as name,
			b.email as email,
			'Leads' as module,
			'4'
			from aicrm_emailtargetlist_leadsrel a
			left join aicrm_leaddetails  b on a.leadid=b.leadid
			left join aicrm_leadscf c on b.leadid=c.leadid
			left join aicrm_crmentity d on d.crmid=b.leadid
			where 1
			and d.deleted=0
			and b.email<>''
			and emailstatus in('','Active')
			and a.emailtargetlistid='".$emailtargetlist[$j][0]."'
			group by b.email
			";
			//and cf_4435 in('','Active')
			//echo $sql."<br>";exit;
			$genarate->query($sql,"all");
			//Insert Account======================================
			$sql="
			select 
			b.accountid as id,
			b.accountname as name,
			email1  as email,
			'Accounts' as module
			from aicrm_emailtargetlist_accountsrel a
			left join aicrm_account  b on a.accountid=b.accountid
			left join aicrm_accountscf c on b.accountid=c.accountid
			left join aicrm_crmentity d on d.crmid=b.accountid
			where 1
			and d.deleted=0
			and email1<>''
			and a.emailtargetlistid='".$emailtargetlist[$j][0]."'
			group by email1
			";
			//and cf_3234<>''
			//echo $sql."<br>";exit;
			$dataacc = $genarate->process($sql,"all");
			
			for($k=0;$k<count($dataacc);$k++){
				$email=split(",",$dataacc[$k][2]);
				for($w=0;$w<count($email);$w++){
					if($email[$w]!=""){
						$sql="
						insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status)
						values('".$campaign[$i][2]."','".$campaign[$i][5]."','".$emailtargetlist[$j][0]."','".$dataacc[$k][0]."','".$dataacc[$k][1]."',
						'".$email[$w]."','".$dataacc[$k][3]."','4')
						";
						$genarate->query($sql,"all");
					}
				}
			}
		}//for $emailtargetlist
		
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
		//echo $sql."<br>";
		$genarate->query($sql,"all");
		$sql="
		select min(id)as id
		from ".$new_table."
		where 1 
		and duplicate=1
		GROUP BY to_email
		HAVING count( * ) >1
		";
		//echo $sql."<br>";
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
		//email=0 ใน table open และ click จะเป็นข้อมูลปกติ
		//email =1 ใน table open และ click จะเป็นข้อมูลไม่ปกติ ไม่เอามาออกรายงาน		
		
		//$sql="update aicrm_campaign_email_marketing set setup_email=1 where campaignid='".$campaign[$i][2]."';";
		//if($genarate->query($sql,"all")){};
		
		
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
		,'".count($data_email_dup1_all)."','0','".count($data_hotmail)."','".count($data_gmail)."','".count($data_yahoo)."','".count($data_others)."','Active'
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
		$sql="update aicrm_campaign_email_marketing set setup_email=1 where id='".$marketid."'";
		if($genarate->query($sql,"all")){};
		//echo $sql;
		//$genarate->query($sql,"all");
		//echo "555";
	}//for $campaign
	//echo "666666";
//get email====================================================================================
echo "<script type='text/javascript'>alert('Set Up Email Complete');window.close();  window.opener.parent.location.replace('../index.php?action=DetailView&module=Campaigns&record=".$crmid."&parenttab=Marketing');</script>";

?>