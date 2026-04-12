<?
session_start();
include("C:/AppServ/www/glapcrmuat/config.inc.php");
include_once("C:/AppServ/www/glapcrmuat/library/dbconfig.php");
include_once("C:/AppServ/www/glapcrmuat/library/myFunction.php");
include_once("C:/AppServ/www/glapcrmuat/library/generate_MYSQL.php");
include_once("C:/AppServ/www/glapcrmuat/phpmailer/class.phpmailer.php");
global $generate;
$generate = new generate($dbconfig ,"DB");
### FUNCTION SEND MAIL ------------------------------------------------------------------------------------------------
function scriptdd_sendmail($to_name,$to_email,$from_name,$email_user_send,$email_pass_send,$subject,$body_html,$from_email) {
		$cc = 'thanisorn@aisyst.com';
		$mail= new PHPMailer();
		$mail-> From     = $from_email;
		$mail-> FromName = $from_name;
		$mail-> AddAddress($to_email,$to_name);

        $sql = "select * from aicrm_systems where server_type ='email'";
        $query = mysql_query($sql);
        $arr = mysql_fetch_array($query);

        $mail_server = $arr['server'];
        $mail_server_port = $arr['server_port'];
        $mail_server_username = $arr['server_username'];
        $mail_server_password = $arr['server_password'];
        $smtp_auth = $arr['smtp_auth'];

		$mail-> AddCC($cc,$cc);
		//$mail-> AddCC($cc1,$cc1);
		//$mail-> AddCC($cc2,$cc2);
		$mail-> Subject	= $subject;
		$mail-> Body		= $body_html;
		$mail-> IsHTML (true);
		$mail->IsSMTP();

        $mail->Host = $mail_server; //'mail.aisyst.com';
        $mail->Port = $mail_server_port; //25;
        $mail->SMTPAuth = $smtp_auth; //true;
        $mail->Username = $mail_server_username; //$email_user_send;
        $mail->Password = $mail_server_password; //$email_pass_send;

		$mail->Send();
		$mail->ClearAddresses();
}
### FUNCTION SEND MAIL -----------------------------------------------------------------------------------------------
	$subject="Reminder!! Case still in Create Time or Modified Time";
	$name_sent = "Customer Service";
	$name_mail = "support@aisyst.com";
	$msg=get_msg();
	$to_name			="AI_Ekk [ekkachai@aisyst.com]";
	//$to_name			="AI_Mam [ladda@aisyst.com]";
	//$to_email			= "medt@aisyst.com"; //"$m1[$i]";
	$to_email			="ekkachai@aisyst.com"; //"$m1[$i]";
	$from_name			="$name_sent";
	$email_user_send	="support_crm@aisyst.com";
	$email_pass_send	="1qaz2WSX";
	$subject1			="$subject";
	$body_html			="$msg";
	//$body_html			="123";
	$from_email			="$name_mail";
	scriptdd_sendmail($to_name,$to_email,$from_name,$email_user_send,$email_pass_send,$subject1,$body_html,$from_email);

function get_msg(){
	global $generate;
	
	$tab2="&nbsp;&nbsp;";
	$tab4="&nbsp;&nbsp;&nbsp;&nbsp;";
	$tab6="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$tab8="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$tab10="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$tab12="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$tab14="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$tab16="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	
	$msg='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
	<title>Untitled Document</title>
	
	<style type="text/css">
	body {    width:6000; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family:tahoma; font-size:8.5pt;}
	table { border-collapse:collapse; display:block; border:1px solid #333; font-family: Arial, Verdana, Helvetica, sans-serif; wordwrap:false;padding:2px;}
	table th,table td {border:1px solid #333;font-family:tahoma; wordwrap:false; }

	th {
		font: bold 11px "tahoma";
		color: #000000;
		text-align: left;
		background: #CAE8EA url(images/bg_header.jpg) ;
	}
	tr {
		font: bold 11px "tahoma";
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		padding: 6px 6px 6px 6px;
		
	}
	td {
		font: bold 11px "tahoma";
		color: #000000;
	}
	tr.spec01 {
		background-color:#F00;	
	}
	tr.spec02 {
		background-color: #F90
	}
	tr.spec03 {
		background-color: #FF6
	}
	tr.spec04 {
		background-color: #FFF
	}
	


	</style>

	
	</head>
	<body>
	<table cellspacing="0" style="width:3200px">
	<tr>
	  <th scope="row">Case&nbsp;No.'.$tab16.''.$tab2.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'หัวข้อ&nbsp;เรื่อง').''.$tab16.''.$tab14.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ประเภท&nbsp;การ&nbsp;รับ&nbsp;เรื่อง').''.$tab12.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'สถานะ').''.$tab10.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ความสำคัญ').''.$tab16.''.$tab16.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'วันที่&nbsp;เปิด&nbsp;Case').''.$tab16.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'เวลา&nbsp;ที่&nbsp;เปิด&nbsp;Case').''.$tab16.''.$tab10.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'Account&nbsp;Name').''.$tab16.''.$tab8.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'Contact&nbsp;Name').''.$tab16.''.$tab2.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'เบอร์&nbsp;โทร&nbsp;ผู้&nbsp;ติดต่อ').''.$tab16.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'E-&nbsp;mail&nbsp;ผู้&nbsp;ติดต่อ').''.$tab8.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'รายละเอียด&nbsp;การ&nbsp;รับ&nbsp;เรื่อง').''.$tab12.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'Product&nbsp;Code').''.$tab16.''.$tab8.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'Product&nbsp;Name').''.$tab16.''.$tab14.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'รุ่น').''.$tab2.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'S/N').''.$tab2.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'อาการ&nbsp;เสีย').''.$tab2.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ผู้&nbsp;รับ&nbsp;ผิด&nbsp;ชอบ&nbsp;งาน&nbsp;ต่อ').''.$tab16.''.$tab2.''.$tab10.'</th>
	</tr>
	';
	$sql="
	select 		
		ticket_no as 'Case No'		
		,aicrm_troubletickets.title as 'หัวข้อเรื่อง'
		,cf_2724 as 'ประเภทการรับเรื่อง'
		,aicrm_troubletickets.status as 'สถานะ'
		,cf_2919 as 'ความสำคัญ'
		,cf_1490 as 'วันที่เปิด Case'
		,cf_2278 as 'เวลาที่เปิด Case'
		,case when aicrm_account.accountname='' then cf_4113 else aicrm_account.accountname end as 'Account Name'		
		,case when aicrm_contactdetails.firstname='' then concat(cf_4100,' ',cf_4101) else concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) end as 'Contact Name'		
		,case when aicrm_troubletickets.phone='' then cf_4102 else aicrm_troubletickets.phone end as 'เบอร์โทรผู้ติดต่อ'
		,case when aicrm_troubletickets.email='' then cf_4112 else aicrm_troubletickets.email end as 'E-mail ผู้ติดต่อ'
		,cf_2918 as 'รายละเอียดการรับเรื่อง'
		,aicrm_products.productcode as 'Product Code'		
		,aicrm_products.productname as 'Product Name'		
		,cf_4155 as 'รุ่น'
		,cf_4115 as 'S/N'		
		,cf_4168 as 'อาการเสีย'
		,aicrm_users.user_name as 'ผู้รับผิดชอบงานต่อ'
		FROM aicrm_troubletickets 		
		left join aicrm_ticketcf on  aicrm_troubletickets.ticketid =  aicrm_ticketcf.ticketid 		
		left join aicrm_crmentity on aicrm_troubletickets.ticketid = aicrm_crmentity.crmid		
		left join aicrm_account on aicrm_troubletickets.accountid=aicrm_account.accountid 		
		left join aicrm_contactdetails on aicrm_troubletickets.contactid=aicrm_contactdetails.contactid		
		left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id		
		left join aicrm_products on aicrm_products.productid=aicrm_troubletickets.product_id		
		where 1		
		and aicrm_crmentity.deleted=0		
		and cf_4325=0		
		and cf_2724='แจ้งซ่อม'
		and cf_2919='เร่งด่วน'
		and aicrm_troubletickets.status<> 'ปิดงาน'
		and DATEDIFF( NOW( ) , cf_1490 ) >=1		
	";
	//echo $sql;exit;
	$data = $generate->process($sql,"all");
	//print_r($data); exit;
	if(count($data)>0){
		for($i=0;$i<count($data);$i++){
			$msg.='
			<tr class="spec01">
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Case No"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["หัวข้อเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ประเภทการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["สถานะ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ความสำคัญ"]).'</td>
			  <td scope="row" >'.date('d-m-Y',strtotime($data[$i]["วันที่เปิด Case"])).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เวลาที่เปิด Case"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Account Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Contact Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เบอร์โทรผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["E-mail ผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รายละเอียดการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Code"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รุ่น"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["S/N"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["อาการเสีย"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ผู้รับผิดชอบงานต่อ"]).'</td>
			</tr>';
		}
	}
	$sql="
	select 
		ticket_no as 'Case No'		
		,aicrm_troubletickets.title as 'หัวข้อเรื่อง'
		,cf_2724 as 'ประเภทการรับเรื่อง'
		,aicrm_troubletickets.status as 'สถานะ'
		,cf_2919 as 'ความสำคัญ'
		,cf_1490 as 'วันที่เปิด Case'
		,cf_2278 as 'เวลาที่เปิด Case'
		,case when aicrm_account.accountname='' then cf_4113 else aicrm_account.accountname end as 'Account Name'		
		,case when aicrm_contactdetails.firstname='' then concat(cf_4100,' ',cf_4101) else concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) end as 'Contact Name'		
		,case when aicrm_troubletickets.phone='' then cf_4102 else aicrm_troubletickets.phone end as 'เบอร์โทรผู้ติดต่อ'
		,case when aicrm_troubletickets.email='' then cf_4112 else aicrm_troubletickets.email end as 'E-mail ผู้ติดต่อ'
		,cf_2918 as 'รายละเอียดการรับเรื่อง'
		,aicrm_products.productcode as 'Product Code'		
		,aicrm_products.productname as 'Product Name'		
		,cf_4155 as 'รุ่น'
		,cf_4115 as 'S/N'		
		,cf_4168 as 'อาการเสีย'
		,aicrm_users.user_name as 'ผู้รับผิดชอบงานต่อ'
		FROM aicrm_troubletickets 		
		left join aicrm_ticketcf on  aicrm_troubletickets.ticketid =  aicrm_ticketcf.ticketid 		
		left join aicrm_crmentity on aicrm_troubletickets.ticketid = aicrm_crmentity.crmid		
		left join aicrm_account on aicrm_troubletickets.accountid=aicrm_account.accountid 		
		left join aicrm_contactdetails on aicrm_troubletickets.contactid=aicrm_contactdetails.contactid		
		left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id		
		left join aicrm_products on aicrm_products.productid=aicrm_troubletickets.product_id		
		where 1		
		and aicrm_crmentity.deleted=0		
		and cf_4325=0		
		and cf_2724='แจ้งซ่อม'
		and cf_2919='มาก'
		and aicrm_troubletickets.status<> 'ปิดงาน' 		
		and DATEDIFF( NOW( ) , cf_1490 ) > 1 and DATEDIFF( NOW( ) , cf_1490 )<=3
	";
	//echo $sql;exit;
	$data = $generate->process($sql,"all");
	//print_r($data);
	if(count($data)>0){
		for($i=0;$i<count($data);$i++){
			$msg.='
			<tr class="spec02">
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Case No"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["หัวข้อเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ประเภทการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["สถานะ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ความสำคัญ"]).'</td>
			  <td scope="row" >'.date('d-m-Y',strtotime($data[$i]["วันที่เปิด Case"])).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เวลาที่เปิด Case"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Account Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Contact Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เบอร์โทรผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["E-mail ผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รายละเอียดการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Code"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รุ่น"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["S/N"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["อาการเสีย"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ผู้รับผิดชอบงานต่อ"]).'</td>
			</tr>';
		}
	}
	$sql="
	select 
		ticket_no as 'Case No'		
		,aicrm_troubletickets.title as 'หัวข้อเรื่อง'
		,cf_2724 as 'ประเภทการรับเรื่อง'
		,aicrm_troubletickets.status as 'สถานะ'
		,cf_2919 as 'ความสำคัญ'
		,cf_1490 as 'วันที่เปิด Case'
		,cf_2278 as 'เวลาที่เปิด Case'
		,case when aicrm_account.accountname='' then cf_4113 else aicrm_account.accountname end as 'Account Name'		
		,case when aicrm_contactdetails.firstname='' then concat(cf_4100,' ',cf_4101) else concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) end as 'Contact Name'		
		,case when aicrm_troubletickets.phone='' then cf_4102 else aicrm_troubletickets.phone end as 'เบอร์โทรผู้ติดต่อ'
		,case when aicrm_troubletickets.email='' then cf_4112 else aicrm_troubletickets.email end as 'E-mail ผู้ติดต่อ'
		,cf_2918 as 'รายละเอียดการรับเรื่อง'
		,aicrm_products.productcode as 'Product Code'		
		,aicrm_products.productname as 'Product Name'		
		,cf_4155 as 'รุ่น'
		,cf_4115 as 'S/N'		
		,cf_4168 as 'อาการเสีย'
		,aicrm_users.user_name as 'ผู้รับผิดชอบงานต่อ'
		FROM aicrm_troubletickets 		
		left join aicrm_ticketcf on  aicrm_troubletickets.ticketid =  aicrm_ticketcf.ticketid 		
		left join aicrm_crmentity on aicrm_troubletickets.ticketid = aicrm_crmentity.crmid		
		left join aicrm_account on aicrm_troubletickets.accountid=aicrm_account.accountid 		
		left join aicrm_contactdetails on aicrm_troubletickets.contactid=aicrm_contactdetails.contactid		
		left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id		
		left join aicrm_products on aicrm_products.productid=aicrm_troubletickets.product_id		
		where 1		
		and aicrm_crmentity.deleted=0		
		and cf_4325=0		
		and cf_2724='แจ้งซ่อม'
		and cf_2919='ปานกลาง'
		and aicrm_troubletickets.status<> 'ปิดงาน'   		
		and DATEDIFF( NOW( ) , cf_1490 ) > 3 and DATEDIFF( NOW( ) , cf_1490 )<=5
	";
	//echo $sql;exit;
	$data = $generate->process($sql,"all");
	//print_r($data);
	if(count($data)>0){
		for($i=0;$i<count($data);$i++){
			$msg.='
			<tr class="spec03">
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Case No"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["หัวข้อเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ประเภทการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["สถานะ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ความสำคัญ"]).'</td>
			  <td scope="row" >'.date('d-m-Y',strtotime($data[$i]["วันที่เปิด Case"])).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เวลาที่เปิด Case"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Account Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Contact Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เบอร์โทรผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["E-mail ผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รายละเอียดการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Code"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รุ่น"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["S/N"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["อาการเสีย"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ผู้รับผิดชอบงานต่อ"]).'</td>
			</tr>';
		}
	}
	$sql="
	select 
		ticket_no as 'Case No'		
		,aicrm_troubletickets.title as 'หัวข้อเรื่อง'
		,cf_2724 as 'ประเภทการรับเรื่อง'
		,aicrm_troubletickets.status as 'สถานะ'
		,cf_2919 as 'ความสำคัญ'
		,cf_1490 as 'วันที่เปิด Case'
		,cf_2278 as 'เวลาที่เปิด Case'
		,case when aicrm_account.accountname='' then cf_4113 else aicrm_account.accountname end as 'Account Name'		
		,case when aicrm_contactdetails.firstname='' then concat(cf_4100,' ',cf_4101) else concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) end as 'Contact Name'		
		,case when aicrm_troubletickets.phone='' then cf_4102 else aicrm_troubletickets.phone end as 'เบอร์โทรผู้ติดต่อ'
		,case when aicrm_troubletickets.email='' then cf_4112 else aicrm_troubletickets.email end as 'E-mail ผู้ติดต่อ'
		,cf_2918 as 'รายละเอียดการรับเรื่อง'
		,aicrm_products.productcode as 'Product Code'		
		,aicrm_products.productname as 'Product Name'		
		,cf_4155 as 'รุ่น'
		,cf_4115 as 'S/N'		
		,cf_4168 as 'อาการเสีย'
		,aicrm_users.user_name as 'ผู้รับผิดชอบงานต่อ'
		FROM aicrm_troubletickets 		
		left join aicrm_ticketcf on  aicrm_troubletickets.ticketid =  aicrm_ticketcf.ticketid 		
		left join aicrm_crmentity on aicrm_troubletickets.ticketid = aicrm_crmentity.crmid		
		left join aicrm_account on aicrm_troubletickets.accountid=aicrm_account.accountid 		
		left join aicrm_contactdetails on aicrm_troubletickets.contactid=aicrm_contactdetails.contactid		
		left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id		
		left join aicrm_products on aicrm_products.productid=aicrm_troubletickets.product_id		
		where 1		
		and aicrm_crmentity.deleted=0		
		and cf_4325=0		
		and cf_2724='แจ้งซ่อม'
		and cf_2919='น้อย'
		and aicrm_troubletickets.status<> 'ปิดงาน' 				
		and DATEDIFF( NOW( ) , cf_1490 ) >=7
	";
	//echo $sql;exit;
	$data = $generate->process($sql,"all");
	//print_r($data);
	if(count($data)>0){
		for($i=0;$i<count($data);$i++){
			$msg.='
			<tr class="spec04">
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Case No"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["หัวข้อเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ประเภทการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["สถานะ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ความสำคัญ"]).'</td>
			  <td scope="row" >'.date('d-m-Y',strtotime($data[$i]["วันที่เปิด Case"])).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เวลาที่เปิด Case"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Account Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Contact Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["เบอร์โทรผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["E-mail ผู้ติดต่อ"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รายละเอียดการรับเรื่อง"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Code"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["Product Name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["รุ่น"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["S/N"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["อาการเสีย"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$data[$i]["ผู้รับผิดชอบงานต่อ"]).'</td>
			</tr>';
		}
	}
	$msg.='</table>
	</body>
	</html>';
	//echo $msg;exit;
	return $msg;
}
?>