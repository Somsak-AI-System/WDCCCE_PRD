<?
	session_start();
	include("../config.inc.php");
	include("../library/dbconfig.php");
	include("../library/myFunction.php");
	include("../library/generate_MYSQL.php");
	include ("../phpmailer/class.phpmailer.php");
	$generate = new generate($dbconfig ,"DB");
	function scriptdd_sendmail($to_name,$to_email,$from_name,$email_user_send,$email_pass_send,$subject,$body_html,$from_email) {
			//echo "555";exit;
			$cc = 'voravud@aisyst.com';
			$cc1 = 'kriengkrai@aisyst.com';
			$cc2 = 'ladda@aisyst.com';
			$cc3 = 'anchangamol@aisyst.com';
			$cc4 = 'rattana@aisyst.com';
			$mail = new PHPMailer();
			$mail -> From     = $from_email;
			$mail -> FromName = $from_name;
			$mail-> AddAddress($to_email,$to_name);
			/*
			$mail-> AddCC($cc,$cc);
			$mail-> AddCC($cc1,$cc1);
			$mail-> AddCC($cc2,$cc2);
			$mail-> AddCC($cc3,$cc3);
			$mail-> AddCC($cc4,$cc4);*/
			
			$mail-> Subject	= $subject;
			$mail-> Body		= $body_html;
			$mail-> IsHTML (true);
			$mail->IsSMTP();
			//$mail->Host = 'mail.fti-matching.com';
			$mail->Host = 'mail.aisyst.com';
			$mail->Port = 25;
			$mail->SMTPAuth		= true;
			$mail->Username = $email_user_send;
			$mail->Password = $email_pass_send;
			$mail->Send();
			$mail->ClearAddresses();
	}	
	if($_REQUEST["myaction"]=="chekemail"){
		$sql="select id from aicrm_config_sendemail where 1 and deleted=0
		and email_user='".$_REQUEST["from_email"]."'
		and email_pass='".$_REQUEST["from_emailpass"]."'
		";
		$data=$generate->process($sql,"all");
		//echo count($data);exit;
		if(count($data)<=0){
			echo "<script>alert('ไม่มีข้อมูล Email นี้อยู่ในระบบ');</script>";	
			$from_email='';
			$from_emailpass='';
		}
	}
	if($_REQUEST["myaction"]=="save"){
		//print_r(extract($_POST));
		/*echo $_REQUEST["reply_email_id"]."<br>";
		echo $_REQUEST["bounce_email_id"]."<br>";
		echo $_REQUEST["edm_email_id"]."<br>";
		exit;*/
		
		if($_REQUEST["email_marketingname"]=="" || $_REQUEST["email_marketingname"]=="--None--"){
			echo "<script>alert('กรุณาใส่ข้อมูล Email Marketing Name');</script>";	
		}else if($_REQUEST["status"]==""){
			echo "<script>alert('กรุณาเลือกข้อมูล Status');</script>";	
		}else if($_REQUEST["from_name"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Frome Name');</script>";	
		}else if($_REQUEST["from_email"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล From Email User');</script>";	
		}
		else if($_REQUEST["reply_email_id"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Reply Email');</script>";	
		}
		else if($_REQUEST["bounce_email_id"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Bounce Email');</script>";	
		}
		else if($_REQUEST["edm_email_id"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Send Email Server');</script>";	
		}
		
		/*else if($_REQUEST["url_click"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล URL For Click Thru link');</script>";	
		}*/
		else if($_REQUEST["date_start"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Start Date');</script>";	
		}else if($_REQUEST["date_start1"]==""){
			echo "<script>alert('กรุณาเลือกข้อมูล Start Time');</script>";	
		}else if($_REQUEST["template_id"]==""){
			echo "<script>alert('กรุณาเลือกข้อมูล Email Template');</script>";	
		}else{
			
			$date=date('Y-m-d H:i:s');
			$id="";
			$camid=$_REQUEST["campaignid"];
			if($_REQUEST["emailid"]==""){
				$sql="select id from aicrm_campaign_email_marketing where 1";
				$data=$generate->process($sql,"all");
				if(count($data)>0){
					$id=count($data)+1;
				}else{
					$id=1;
				}
				$sql="insert into aicrm_campaign_email_marketing (id,email_marketingname,mail_email_type,mail_status,from_name,from_email,from_emailpass,url_click,
				campaignid,template_id,date_start,createdtime,modifiedtime,created_by,modified_by,deleted,reply_email_id,edm_email_id,bounce_email_id)
				values ('".$id."','".$_REQUEST["email_marketingname"]."','".$_REQUEST["email_type"]."','".$_REQUEST["status"]."','".$_REQUEST["from_name"]."','".$_REQUEST["from_email"]."','".$_REQUEST["from_emailpass"]."','".$_REQUEST["url_click"]."',
				'".$_REQUEST["campaignid"]."','".$_REQUEST["template_id"]."','".date('Y-m-d',strtotime($_REQUEST["date_start"]))." ".$_REQUEST["date_start1"].":01"."',
				'".$date."','".$date."','".$_REQUEST["userid"]."','".$_REQUEST["userid"]."','0','".$_REQUEST["reply_email_id"]."','".$_REQUEST["edm_email_id"]."','".$_REQUEST["bounce_email_id"]."'
				);
				";
				//echo $sql;exit;
				$generate->query($sql);
				
			}else{
				$id=$_REQUEST["emailid"];
				$sql="update aicrm_campaign_email_marketing set 
				email_marketingname='".$_REQUEST["email_marketingname"]."',
				mail_email_type='".$_REQUEST["email_type"]."',
				mail_status='".$_REQUEST["status"]."',
				from_name='".$_REQUEST["from_name"]."',
				from_email='".$_REQUEST["from_email"]."',
				from_emailpass='".$_REQUEST["from_emailpass"]."',
				url_click='".$_REQUEST["url_click"]."',
				template_id='".$_REQUEST["template_id"]."',
				reply_email_id='".$_REQUEST["reply_email_id"]."',
				edm_email_id='".$_REQUEST["edm_email_id"]."',
				bounce_email_id='".$_REQUEST["bounce_email_id"]."',
				date_start='".date('Y-m-d',strtotime($_REQUEST["date_start"]))." ".$_REQUEST["date_start1"].":01"."',
				modifiedtime='".$date."',
				setup_email=0,
				send_email=0,
				modified_by='".$_REQUEST["userid"]."'
				where campaignid='".$_REQUEST["campaignid"]."'
				and id='".$_REQUEST["emailid"]."'
				";
				//echo $sql;exit;
				$generate->query($sql);
			}
			$new_table="tbt_email_log_campaignid_".$camid."_marketid_".$id;
			
			//Send Email หาผู้เกี่ยวข้อรับทราบ ================================
				$sql="select campaignname from aicrm_campaign where 1 and campaignid='".$_REQUEST["campaignid"]."' ";
				$data_cam=$generate->process($sql,"all");
				$subject="ALERTCAM: [INT] วันที่ ".date('d-m-Y',strtotime($_REQUEST["date_start"]))." เวลา ".$_REQUEST["date_start1"].":01"." MAJOR จะทำการส่งอีเมล์ Marketing";
				$name_sent = "Support Ai-CRM";
				$name_mail = "support_crm@aisyst.com";
				$msg="
				<strong>Dear Sir</strong> <br>

				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่ ".date('d-m-Y',strtotime($_REQUEST["date_start"]))." ทาง MAJOR จะทำการส่งอีเมล์ ในเวลา ".$_REQUEST["date_start1"].":01"."<br>
				<strong>Campaigns Name :</strong> ".$data_cam[0]["campaignname"]."<br>
				เตือนผู้เกี่ยวข้องเตรียมความพร้อม ในการรับมือ<br> 
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;และอาจจะมีผลกระทบกระเทือนกับผู้ที่เกี่ยวข้องบ้างค่ะ<br>
				
				<strong>Best Regards</strong><br>
				&nbsp;&nbsp;&nbsp;&nbsp;Support Ai-CRM<br>
				&nbsp;&nbsp;&nbsp;&nbsp;support@aisyst.com<br>
				";
				$to_name			="AI_Ekk [ekkachai@aisyst.com]";
				//$to_name			="AI_Mam [ladda@aisyst.com]";
				$to_email			= "ekkachai@aisyst.com"; //"$m1[$i]";
				//$to_email			= "ladda@aisyst.com"; //"$m1[$i]";
				$from_name			="$name_sent";
				$email_user_send="support_crm@aisyst.com";
				$email_pass_send="1qaz2WSX";
				$subject1			="$subject";
				$body_html			="$msg";
				$from_email			="$name_mail";
				scriptdd_sendmail(iconv("utf-8","tis-620",$to_name),$to_email,iconv("utf-8","tis-620",$from_name),$email_user_send,$email_pass_send,iconv("utf-8","tis-620",$subject1),iconv("utf-8","tis-620",$body_html),$from_email);
			//Send Email หาผู้เกี่ยวข้อรับทราบ ================================
			echo "<script type='text/javascript'>alert('บันทึกข้อมูลเรียบร้อย');window.close();  window.opener.parent.location.replace('../index.php?action=CallRelatedList&module=Campaigns&record=".$_REQUEST["campaignid"]."&parenttab=Marketing');</script>";
		}
	}else if($_REQUEST["myaction"]=="cancel"){
		echo "<script type='text/javascript'>  window.close();</script>";
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../themes/softed/style.css">
<script type="text/javascript" src="../jscalendar/calendar.js"></script>
<script type="text/javascript" src="../jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="../jscalendar/lang/calendar-th.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../jscalendar/calendar-win2k-cold-1.css">
<title>Email Marketing</title>
<script language="JavaScript">        
function deleterow(pathcode,subcode,year,groupcode){	
		accountprice_detail.myaction.value =  "DELETE";
		accountprice_detail.action = "accountprice_detail.php";
		accountprice_detail.submit();
		if (!confirm('เธ•เนเธญเธเธเธฒเธฃเธ—เธตเนเธเธฐเธฅเธเธเนเธญเธกเธนเธฅเธซเธฃเธทเธญเนเธกเน?')){
			return false;
		}
	}		
function addrow(row){	
	var row1 = new Number(row) + 1 ;
		accountprice_detail.row1.value =  row1;
		accountprice_detail.action = "accountprice_detail.php";
		accountprice_detail.submit();
	}	

function block_text(){
	if(document.trackerurl.tracker_url.disabled){
		document.trackerurl.tracker_url.disabled=false;
	}else{
		document.trackerurl.tracker_url.disabled=true;
	}
}

function checkEmail(field) { 
	var emailFilter=/^.+@.+\..{2,3}$/;
	var str=document.getElementById(field).value;
	if (!(emailFilter.test(str))) { 
		alert ("Please enter a valid E-mail");
		document.getElementById(field).value="";
		return false;
	}
	return true;
}
</script>
        <SCRIPT LANGUAGE="Javascript"><!-- 
			function printWindow(){ 
				browserVersion = parseInt(navigator.appVersion) 
				if (browserVersion >= 4) window.print() 
			} 
//--></SCRIPT> 
</head>

<body style="text-align: center" marginwidth="0" marginheight="0" topmargin="0" leftmargin="0">
<form name="emailmarketing" method="post" enctype="multipart/form-data" action="email_marketing.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="campaignid" value="<?=$_REQUEST["campaignid"]?>" />
<input type="hidden" name="userid" value="<?=$_REQUEST["userid"]?>" />
<input type="hidden" name="emailid" value="<?=$_REQUEST["emailid"]?>" />
<?
if($_REQUEST["emailid"]!="" && $_REQUEST["myaction"]!="chekemail"){
		$sql = "select email_marketingname,mail_status,from_name,template_id,date_start ,from_email,from_emailpass,url_click,mail_email_type,reply_email_id,edm_email_id,bounce_email_id
		from aicrm_campaign_email_marketing 
		where campaignid='".$_REQUEST["campaignid"]."' and id='".$_REQUEST["emailid"]."'
		";
		$data = $generate->process($sql,"all");		
		$email_marketingname=$data[0]['email_marketingname'];
		$status=$data[0]['mail_status'];
		$from_name=$data[0]['from_name'];
		$template_id=$data[0]['template_id'];
		$datearr=explode(" ",$data[0]['date_start']);
		$date_start=$datearr[0];
		$date_start1=substr($datearr[1],0,5);
		$from_email=$data[0]['from_email'];
		$from_emailpass=$data[0]['from_emailpass'];
		$url_click=$data[0]['url_click'];
		$mail_email_type=$data[0]['mail_email_type'];
		
		$reply_email_id=$data[0]['reply_email_id'];
		$edm_email_id=$data[0]['edm_email_id'];
		$bounce_email_id=$data[0]['bounce_email_id'];
		//print_r($date_start);
}
?>
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
  <tr>
    <td colspan="2" align="left" class="dvInnerHeader" ><strong>Email Marketing</strong></td>
    </tr>
  <tr>
    <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Email Marketing Name</td>
    <td width="60%" align="left" class="dvtCellInfo"><input type="text" tabindex="" name="email_marketingname" id ="email_marketingname" value="<?=$email_marketingname?>" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" />
    <input type="hidden" tabindex="" name="email_type" id ="email_type" value="" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" />
    </td>
  </tr>

  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Status</td>
    <td align="left" class="dvtCellInfo"><label for="status"></label>
      <select name="status" id="status" class="small">
        <option value="">-- None --</option>
        <option value="Active" <? if($status=="Active"){ echo "selected";}?> >Active</option>
        <option value="InActive"<? if($status=="InActive"){ echo "selected";}?> >InActive</option>
      </select></td>
  </tr>
  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>From Name</td>
    <td align="left" class="dvtCellInfo">
    <?
	if($from_name==""){
		$from_name="MAJOR Enew Letters";
	}
	?>
    
    <input type="text" tabindex="" name="from_name" id ="from_name" value="<?=$from_name?>" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
  </tr>
  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>From Email</td>
    <td align="left" class="dvtCellInfo"><?
	if($from_email==""){
		//$sql="select email_user,email_pass from aicrm_config_sendemail where id=1 and deleted=0";
		//$data = $generate->process($sql,"all");		
		$from_email="1";
		//$from_emailpass=$data[0][1];
	}
	?>      <!--<input type="text" tabindex="" name="from_email" id ="from_email" value="<?=$from_email?>" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" />-->
      <?
	  	if($_REQUEST["from_email"]!=""){
			$from_email=$_REQUEST["from_email"];
		}
		$sql = "select * from aicrm_config_sendemail where 1 and deleted=0 and email_type='account'  and email_status='Active'";
		//echo $sql;
		$emailsend = $generate->process($sql,"all");
		?>
      <select name="from_email" id="from_email"  class="small" >
        <option value="">-- None --</option>
        <?php
			for($k=0;$k<count($emailsend);$k++){
				$a = 0;
		?>
        <option value="<?=$emailsend[$k]["id"]?>" <?php if($emailsend[$k]["id"]==$from_email){ echo "selected";}?>>
          <?=$emailsend[$k]["email_user"]?>
          </option>
        <?
			}
		?>
      </select>
      <input type="hidden" tabindex="" name="from_emailpass" id ="from_emailpass" value="<?=$from_emailpass?>" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:150px" /></td>
  </tr>
  <!--<tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>From Email Pass</td>
    <td align="left" class="dvtCellInfo">&nbsp;
      <input title="Check Email" accesskey="C" class="crmbutton small save" onclick="this.form.myaction.value='chekemail'; " type="submit" name="button3" value="Check Email" style="width:80px;cursor: pointer;" /></td>
  </tr>-->
	<?
    if($reply_name==""){
		$reply_name="MAJOR Reply";
    }
    ?>
  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Reply Email</td>
    <td align="left" class="dvtCellInfo">
		<?
		if($_REQUEST["reply_email_id"]!=""){
			$reply_email_id=$_REQUEST["reply_email_id"];
			
		}
		//echo "123=>".$_REQUEST["reply_email_id"]." ;
		$sql = "select * from aicrm_config_sendemail where 1 and deleted=0 and email_type='reply' and email_status='Active' ";
		//echo $sql;
		$emailsend = $generate->process($sql,"all");
		?>
      <select name="reply_email_id" id="reply_email_id"  class="small" >
        <option value="">-- None --</option>
        <?php
			for($k=0;$k<count($emailsend);$k++){
				$a = 0;
		?>
        <option value="<?=$emailsend[$k]["id"]?>" <?php if($emailsend[$k]["id"]==$reply_email_id){ echo "selected";}?>>
          <?=$emailsend[$k]["email_user"]?>
          </option>
        <?
			}
		?>
      </select>
</td>
  </tr>
  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Bounce Email</td>
    <td align="left" class="dvtCellInfo">
		<?
		if($_REQUEST["bounce_email_id"]!=""){
			$bounce_email_id=$_REQUEST["bounce_email_id"];
		}
		$sql = "select * from aicrm_config_sendemail where 1 and deleted=0 and email_type='bounce' and email_status='Active' ";
		//echo $sql;
		$emailsend = $generate->process($sql,"all");
		?>
      <select name="bounce_email_id" id="bounce_email_id"  class="small" >
        <option value="">-- None --</option>
        <?php
			for($k=0;$k<count($emailsend);$k++){
				$a = 0;
		?>
        <option value="<?=$emailsend[$k]["id"]?>" <?php if($emailsend[$k]["id"]==$bounce_email_id){ echo "selected";}?>>
          <?=$emailsend[$k]["email_user"]?>
          </option>
        <?
			}
		?>
      </select>
</td>
  </tr>
  
  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Send Email Server</td>
    <td align="left" class="dvtCellInfo">
		<?
		if($_REQUEST["edm_email_id"]!=""){
			$edm_email_id=$_REQUEST["edm_email_id"];
		}
		$sql = "select * from aicrm_config_sendemail where 1 and deleted=0 and email_type='edm'  and email_status='Active'";
		//echo $sql;
		$emailsend = $generate->process($sql,"all");
		?>
      <select name="edm_email_id" id="edm_email_id"  class="small" >
        <option value="">-- None --</option>
        <?php
			for($k=0;$k<count($emailsend);$k++){
				$a = 0;
		?>
        <option value="<?=$emailsend[$k]["id"]?>" <?php if($emailsend[$k]["id"]==$edm_email_id){ echo "selected";}?>>
          <?=$emailsend[$k]["email_server"]?>
          </option>
        <?
			}
		?>
      </select>
</td>
  </tr>
  
  
  
  <!--<tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>URL For Click Thru link </td>
    <td align="left" class="dvtCellInfo"><input type="text" tabindex="" name="url_click" id ="url_click" value="<?=$url_click?>" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
  </tr>-->
  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Email Template</td>
    <td align="left" class="dvtCellInfo"><?
					$sql = "select templateid,templatename from aicrm_emailtemplates ORDER BY templateid";
					$templateid = $generate->process($sql,"all");
					?>
      <select name="template_id" id="template_id"  class="small" >
      <option value="">-- None --</option>
        <?php
                            for($k=0;$k<count($templateid);$k++){
                                $a = 0;
                        ?>
        <option value="<?=$templateid[$k]["templateid"]?>" <?php if($templateid[$k]["templateid"]==$template_id){ echo "selected";}?>>
          <?=$templateid[$k]["templatename"]?>
          </option>
        <?
							}
						?>
      </select></td>
  </tr>  
  <tr>
    <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Start Date &amp; Start Time</td>
    <td align="left" class="dvtCellInfo">
    <?
	if($date_start=="" || $date_start=="0000-00-00"){
		$date_start="";
	}else{
		$date_start=date('d-m-Y',strtotime($date_start));
	}
	?>
    <input type="text" tabindex="" name="date_start" id ="date_start" value="<?=$date_start?>" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:70px" />
&nbsp;<img src="../callcenter/images/icon-left1.png" name="picdate" style="cursor: pointer;" title="Select Date" onmouseover="this.style.background='#D5D555';" onmouseout="this.style.background=''" width="20" height="20" id="picdate" border="0"  />&nbsp;
    <script type="text/javascript">
		Calendar.setup({
				inputField     :    "date_start",     // id of the input field
				ifFormat       :    "%d-%m-%Y",      // format of the input field
				button         :    "picdate",  // trigger for the calendar (button ID)
				align          :    "Br",           // alignment (defaults to "Bl")
				singleClick    :    true,
				showsTime : false,
				step : 1
		});
	</script>
<select name="date_start1" id="date_start1" class="small">
<option value="">-- None --</option>
<option value='00:00' <? if($date_start1=='00:00'){ echo 'selected';}?> >00:00</option>
<option value='01:00' <? if($date_start1=='01:00'){ echo 'selected';}?> >01:00</option>
<option value='02:00' <? if($date_start1=='02:00'){ echo 'selected';}?> >02:00</option>
<option value='03:00' <? if($date_start1=='03:00'){ echo 'selected';}?> >03:00</option>
<option value='04:00' <? if($date_start1=='04:00'){ echo 'selected';}?> >04:00</option>
<option value='05:00' <? if($date_start1=='05:00'){ echo 'selected';}?> >05:00</option>
<option value='06:00' <? if($date_start1=='06:00'){ echo 'selected';}?> >06:00</option>
<option value='07:00' <? if($date_start1=='07:00'){ echo 'selected';}?> >07:00</option>
<option value='08:00' <? if($date_start1=='08:00'){ echo 'selected';}?> >08:00</option>
<option value='09:00' <? if($date_start1=='09:00'){ echo 'selected';}?> >09:00</option>
<option value='10:00' <? if($date_start1=='10:00'){ echo 'selected';}?> >10:00</option>
<option value='11:00' <? if($date_start1=='11:00'){ echo 'selected';}?> >11:00</option>
<option value='12:00' <? if($date_start1=='12:00'){ echo 'selected';}?> >12:00</option>
<option value='13:00' <? if($date_start1=='13:00'){ echo 'selected';}?> >13:00</option>
<option value='14:00' <? if($date_start1=='14:00'){ echo 'selected';}?> >14:00</option>
<option value='15:00' <? if($date_start1=='15:00'){ echo 'selected';}?> >15:00</option>
<option value='16:00' <? if($date_start1=='16:00'){ echo 'selected';}?> >16:00</option>
<option value='17:00' <? if($date_start1=='17:00'){ echo 'selected';}?> >17:00</option>
<option value='18:00' <? if($date_start1=='18:00'){ echo 'selected';}?> >18:00</option>
<option value='19:00' <? if($date_start1=='19:00'){ echo 'selected';}?> >19:00</option>
<option value='20:00' <? if($date_start1=='20:00'){ echo 'selected';}?> >20:00</option>
<option value='21:00' <? if($date_start1=='21:00'){ echo 'selected';}?> >21:00</option>
<option value='22:00' <? if($date_start1=='22:00'){ echo 'selected';}?> >22:00</option>
<option value='23:00' <? if($date_start1=='23:00'){ echo 'selected';}?> >23:00</option>
</select>   
 
<!--<input type="text" tabindex="" name="date_start1" id ="date_start1" value="<?=$date_start1?>" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:50px" /> -->
<br />
&nbsp;(dd-mm-yyyy) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(23:00) </td>
  </tr>


  <tr>
    <td colspan="2" align="center" class="dvtCellInfo"><input title="Save" accesskey="S" class="crmbutton small save" onclick="this.form.myaction.value='save'; " type="submit" name="button2" value="Save" style="width:80px;cursor: pointer;" />
      &nbsp;
      <input title="Cancel" accesskey="C" class="crmbutton small cancel" onclick="this.form.myaction.value='cancel'; " type="submit" name="button" value="Cancel" style="width:80px;cursor: pointer;" /></td>
  </tr>
</table>
</form>
</body>
</html>
