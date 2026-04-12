<?
//error_reporting(E_ALL);
//ini_set('display_errors', 'on');
//ini_set('mysql.connect_timeout', 3600); 
ini_set('memory_limit','512M');
 
 //echo "555";
### Include File -----------------------------------------------------------------------------------------------------
	global $path,$url_path;	
	$path="C:/AppServ/www/mjdp";
	//$path="/home/admin/domains/crmthai.net/public_html/tmao/";
	$url_path="http://".$_SERVER['HTTP_HOST']."/mjdp";
	require_once ($path."/config.inc.php");		
	require_once ($path."/library/dbconfig.php");
	//require_once ($path."/phpmailer/class.phpmailer.php");
	require_once ($path."/library/genarate.inc.php");
	require_once ($path."/library/myFunction.php");
	require_once ($path."/lib/swift_required.php");
	global $genarate;
	//$generate = new generate($dbconfig ,"DB");
	$genarate = new genarate($dbconfig ,"DB");
	
	global $mailer;
	Swift_Preferences::getInstance()->setCacheType('array');
	

function send_mail($from_name,$from_email,$user_send,$pass_send,$subject,$body,$send_email,$new_table,$url_click,$date_start,$campaignid,$marketid,$reply_name,$reply_email,$bounce_name,$bounce_email,$edm_user,$edm_pass,$edm_mail_server,$edm_mail_server_port,$edm_from_name){
	global $path,$url_path;
	global $mailer;
	global $genarate;

	/*$transport = Swift_SmtpTransport::newInstance('smtp2.lifesgoodthailand.com', 25)
		->setUsername("lgthailand")
		->setPassword("1qazxsw2")
	;	*/
	
	/*$transport = Swift_SmtpTransport::newInstance($edm_mail_server,$edm_mail_server_port)
	  ->setUsername($edm_user)
	  ->setPassword($edm_pass)
	;*/
	$transport = Swift_SmtpTransport::newInstance('smtp3.ghb-email.com', 25)
	  ->setUsername("aimail")
	  ->setPassword("aimail@2009")
	;
	for($p=0;$p<count($send_email);$p++){
		$to_email=$send_email[$p][7];
		//echo $to_name." ".$to_email."<br>";
		//exit;
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
     			/////////->setReturnPath($bounce_email)
				// Optionally add any attachments
				//->attach(Swift_Attachment::fromPath('my-document.pdf'))
			;
			
			$id=$send_email[$p][0];
			$campaignid=$send_email[$p][1];
			$email_marketingid=$send_email[$p][2];
			$emailtargetlistid=$send_email[$p][3];
			$from_module=$send_email[$p][4];
			$from_id=$send_email[$p][5];
			//$to_name=iconv("TIS-620", "UTF-8",$send_email[$p][6]);
			 $to_name=$send_email[$p][6];
			 
			//,$date_start,$campaignid,$marketid
			$folder_name=date('Ymd',strtotime($date_start))."_".$campaignid."_".$marketid;
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
								/*<a style="color:#fd8103;" target="_blank" href='.$url_click_link.'>
								'.$body.'
								</a><br mce_bogus="1" />	*/
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
				
				$sql = "update ".$new_table." set date_start='".date('Y-m-d H:i:s')."', status='".$result."' where id='".$id."' and campaignid='".$campaignid."' and email_marketingid='".$email_marketingid."' and emailtargetlistid='".$emailtargetlistid."';";
				$genarate->query($sql);	
				
				$sql1="update tbt_report_tab_1 set email_send=email_send+1 where campaign_id='".$campaignid."'";
				$genarate->query($sql1);	
				
			}					
		}else{
			$sql = "update ".$new_table." set date_start='".date('Y-m-d H:i:s')."', status='2', invalid_email=1 where id='".$id."' and campaignid='".$campaignid."' and email_marketingid='".$email_marketingid."' and emailtargetlistid='".$emailtargetlistid."';";
			$genarate->query($sql);
			
			$sql1="update tbt_report_tab_1 set email_invalid=email_invalid+1 where campaign_id='".$campaignid."'";
			$genarate->query($sql1);	
		}		
	}
	//exit;
	return $update;
	//loop ส่ง email
}

//sent email====================================================================================
$sql="select limits,sleep from tbm_config_email where id=3";
//echo $sql;exit;
$data = $genarate->process($sql,"all");
$limit=$data[0][0];
$max_loop=$data[0][1];
//$max_loop=100;
//echo $max_loop."<br>";
$chk=0;
$loop=ceil($limit/$max_loop);

//for($kk=0;$kk<1;$kk++){	
for($kk=0;$kk<500;$kk++){
	//echo "START_".date('Y-m-d H:i:s')."<br>";
	//$date=date('Y-m-d H:i:'."01");
	//$date=(date("Y-m-d H:i:s",mktime(date('H'),date('i'),01,date('m'),date('d'),date('Y'))));
	$date=date('Y-m-d H:i:s',mktime(date('H'), date('i'), 1, date('m'), date('d'), date('Y')));
	//$date=date('2015-09-22 15:16:01');
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
		,reply_email_id
		,edm_email_id
		,bounce_email_id
		
		from aicrm_campaign_email_marketing
		where campaignid='".$crmid."'
		and mail_status='Active'
		and deleted =0
		and setup_email=1
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
		,reply_email_id
		,edm_email_id
		,bounce_email_id
		
		from aicrm_campaign_email_marketing
		where mail_status='Active'
		and deleted =0
		and setup_email=1
		";
	}
	//date_start ='".$date."' and 
	//echo $sql;exit;
	$campaign = $genarate->process($sql,"all");

	for($i=0;$i<count($campaign);$i++){
		//if($chk<$limit){
			$templateid=$campaign[$i][3];
			$url_click=$campaign[$i][9];
			
			$from_email_id=$campaign[$i][6];
			$reply_email_id=$campaign[$i][10];
			$edm_email_id=$campaign[$i][11];
			$bounce_email_id=$campaign[$i][12];
			
			$date_start=$campaign[$i][4];
			$campaignid=$campaign[$i][2];
			$marketid=$campaign[$i][5];
			//$person_lang=$campaign[$i][13];
			//$date_start,$campaignid,$marketid
			$new_table="tbt_email_log_campaignid_".$campaign[$i][2]."_marketid_".$campaign[$i][5];
			$sql="select subject,body from aicrm_emailtemplates where templateid='".$templateid."'";
			//echo $sql;exit;
			$template = $genarate->process($sql,"all");
			//$subject=$template[0][0];
				
			//from email	
			$sql = "select email_user,email_pass from aicrm_config_sendemail where 1 and deleted=0 and id='".$from_email_id."' and email_type='account' ";
			$emailsend = $genarate->process($sql,"all");
			
			$from_name = $campaign[$i][1];
			$from_email = $emailsend[0][0];
			$user_send=$emailsend[0][0];
			$pass_send=$emailsend[0][1];
			$subject = $template[0][0];
			$body=$template[0][1];
			
			//Reply Mail
			$sql = "select email_user,email_pass,email_from_name from aicrm_config_sendemail where 1 and deleted=0 and id='".$reply_email_id."' and email_type='reply' ";
			$emailsend = $genarate->process($sql,"all");
			$reply_name=$emailsend[0][2];
			$reply_email=$emailsend[0][0];
			
			//Bounce Email
			$sql = "select email_user,email_pass,email_from_name from aicrm_config_sendemail where 1 and deleted=0 and id='".$bounce_email_id."' and email_type='bounce' ";
			//echo $sql."<br>";
			$emailsend = $genarate->process($sql,"all");
			$bounce_name=$emailsend[0][2];
			$bounce_email=$emailsend[0][0];
			
			//EDM Email
			$sql = "select email_user,email_pass,email_server,email_port,email_from_name from aicrm_config_sendemail where 1 and deleted=0 and id='".$edm_email_id."' and email_type='edm' ";
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
			from ".$new_table." where status in (3,4)  and check_send=0 and active=1 
			and group_send=1 
			and invalid_email=0 
			
			/*
			AND `to_email` LIKE '%ghb%'
			*/
			
			order by id limit 0,".$max_loop;
			//echo $sql."<br>";exit;
			
			$send_email = $genarate->process($sql,"all");
		
			if(count($send_email)>0){
				for($pp=0;$pp<count($send_email);$pp++){
					$sql="update ".$new_table." set check_send=1 where id='".$send_email[$pp][0]."'; ";
					$genarate->query($sql);
				}
				$update=send_mail($from_name,$from_email,$user_send,$pass_send,$subject,$body,$send_email,$new_table,$url_click,$date_start,$campaignid,$marketid,$reply_name,$reply_email,$bounce_name,$bounce_email,$edm_user,$edm_pass,$edm_mail_server,$edm_mail_server_port,$edm_from_name);
			}
			//exit;
			$sql="select * from ".$new_table." where active=1 and report=1 ";	
			$data = $genarate->process($sql,"all");
			$count1=count($data);	
			$sql="select * from ".$new_table." where status in(3,4) and active=1 and report=1 ";	
			$data = $genarate->process($sql,"all");
			$count2=count($data);
			if($count1!="0" and $count2<="0"){
				//ปืด campaign การส่ง email
				$sql = "
				update aicrm_campaign_email_marketing set 
				mail_status='InActive'
				where id='".$marketid."' and campaignid='".$campaignid."' 
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
						$sql="update  aicrm_leadscf set cf_2340='InActive' where leadid='".$data[$i][1]."' ";
						$genarate->query($sql);		
					}else if($data[$i][0]=="EmailTarget"){
						$sql="update  aicrm_emailtargetscf set cf_926='InActive' where emailtargetid	='".$data[$i][1]."' ";
						$genarate->query($sql);		
					}else if($data[$i][0]=="Opportunity"){
						$sql="update  aicrm_opportunitycf set cf_2353='InActive' where opportunityid	='".$data[$i][1]."' ";
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
		$sql="update aicrm_campaign_email_marketing set send_email=1 where id='".$marketid."'";
		if($genarate->query($sql,"all")){};
	}//for campaign
//sent email====================================================================================
//echo "STOP_".date('Y-m-d H:i:s')."<br>";
}
echo "<script type='text/javascript'>alert('Send Email Complete');window.close();  window.opener.parent.location.replace('../index.php?action=DetailView&module=Campaigns&record=".$crmid."&parenttab=Marketing');</script>";


?>