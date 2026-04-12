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
	$data = array();
	try {
		$cc = 'somsak.k@aisystem.co.th';
		//$cc1 = '';
		//$cc2 = '';
		//$cc1 = 'supamas@wdc.co.thh';
		//$cc2 = 'suparat@wdc.co.th';
		$mail= new PHPMailer();
		$mail-> From     = $from_email;
		$mail-> FromName = $from_name;
		
		foreach($to_name as $key =>$val){
			$mail-> AddAddress($val,$val);
		}
		$mail-> AddCC($cc,$cc);
		//$mail-> AddCC($cc1,$cc1);
		//$mail-> AddCC($cc2,$cc2);
		$mail-> Subject	= $subject;
		$mail-> Body		= $body_html;
		$mail-> IsHTML (true);
		$mail->IsSMTP();
		$mail->Host = 'mail.aisyst.com';
		$mail->Port = 587;
		$mail->SMTPAuth		= true;
		$mail->Username = $email_user_send;
		$mail->Password = $email_pass_send;
		$mail->Send();
		$mail->ClearAddresses();
		
		$data['status'] = true;
		$data['msg'] = "Send Email Complete";
		$data['error'] = "";
		
		return $data;
		
	}catch (phpmailerException $e) {
		
		$data['status'] = false;
		$data['msg'] = "";
		$data['error'] = $e->errorMessage();
		return $data;
		
	}catch (Exception $e) {
	
		$data['status'] = false;
		$data['msg'] = "";
		$data['error'] = $e->getMessage();
		return $data;
	}
}
### FUNCTION SEND MAIL -----------------------------------------------------------------------------------------------
	//$url = "http://localhost:8090/medo/WB_Service_AI/batch/sendmail/weeklyplan";
	global $site_URL_service;
	//$site_URL_service = 'http://localhost/qbio/';
	$url = $site_URL_service."index.php/batch/sendmail/monthlyplan";
	//echo $url;
	//$date_time = date("Y-m-d H:s");
	$date_time = isset($_REQUEST["date_time"]) && $_REQUEST["date_time"]!= "" ? $_REQUEST["date_time"] : date("Y-m-d H:s");

	$runtype = isset($_REQUEST["runtype"]) && $_REQUEST["runtype"]!= "" ? $_REQUEST["runtype"] : "1";
	$section = isset($_REQUEST["section"]) && $_REQUEST["section"]!= "" ? $_REQUEST["section"] : "";
	
	//$date_time = date("2017-05-22 17:00");
	$fields = array(
			'AI-API-KEY'=>"1234",
			'runtype' => $runtype, /*1=auto;2=manual*/
			'date_run' => $date_time,/*มีหรือไม่มีก็ได้*/
			'section' =>$section,/*runtype 1=ไม่ต้องส่ง;2=ต้องส่งแผนกด้วย*/
	);
	
	//echo "<pre>"; print_r($fields );echo "</pre>";
	$fields_string = json_encode($fields);
	$json_url = $url;
	$json_string = $fields_string;
	$ch = curl_init( $json_url );
	
	// Configuring curl options
	$options = array(
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
			CURLOPT_POSTFIELDS => $json_string
	);
	
	// Setting curl options
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt_array( $ch, $options );
	$result =  curl_exec($ch); // Getting jSON result string

	$aa = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$result),true);
			
	$data = array();
	$res_data = '';

	if(!empty($aa['data'])){
		foreach ($aa['data'] as $key => $value) {
			$mail_to = explode(",",$value['config']['mailto']);//Send Mail To
			
			$startdate = @$value["monthlyplan"]["result"]["log"]["start_date"]; 	
			$startdate = $startdate!="" ? date("d/m/Y",strtotime($startdate)) : date("d/m/Y");
	
			$enddate = @$value["monthlyplan"]["result"]["log"]["end_date"];
			$endate = $enddate!="" ? date("d/m/Y",strtotime($enddate)) : date("d/m/Y");
			
			$subject="รายงาน สรุปผลการจัดส่ง Monthly Plan แผนก ".$value['config']['section']."  ประจำวันที่ ".$startdate." ถึง ".$endate;
			$subject=iconv("UTF-8", "TIS-620", $subject);
			$name_sent = $HELPDESK_SUPPORT_NAME;
			$name_mail = $HELPDESK_SUPPORT_EMAIL_ID;
			$msg=get_msg($value);
			$to_name			= $mail_to;
			$to_email			= $mail_to; 
			$from_name			="$name_sent";
			$email_user_send	="support_crm@aisyst.com";
			$email_pass_send	="yToKj4Sn";
			$subject1			="$subject";
			$body_html			="$msg";
			$from_email			="$name_mail";
			$data = scriptdd_sendmail($to_name,$to_email,$from_name,$email_user_send,$email_pass_send,$subject1,$body_html,$from_email);
			$res_data = $data;
			
		}
	}
	echo  json_encode($res_data);

function get_msg($a_data=array()){
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
	


	</style>

	
	</head>
	<body>
	<p>'.iconv("UTF-8", "TIS-620",$a_weeklyontime = $a_data['monthlyplan']['result']['ontime']['title']).'</p>
	<table cellspacing="0" style="width:2000px">
	<tr>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ลำดับ').'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'รหัสพนักงาน').'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ชื่อ-สกุล').''.$tab16.''.$tab16.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'อีเมล์').''.$tab16.''.$tab16.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'เบอร์มือถือ').''.$tab10.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'วัน-เวลา&nbsp;ที่ส่งรายงาน').''.$tab16.''.$tab16.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ส่งก่อนเวลา&nbsp;(วัน)').''.$tab16.''.$tab16.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ส่งก่อนเวลา&nbsp;(ชม.)').''.$tab16.''.$tab16.'</th>
	</tr>
	';
	if(count($a_data['monthlyplan']['result']['ontime'])>0){
		$a_weeklyontime = $a_data['monthlyplan']['result']['ontime']["data"];
		for($i=0;$i<count($a_weeklyontime);$i++){
			$msg.='
			<tr class="spec01">
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["no"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["id"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["email"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["phone"]).'</td>
			  <td scope="row" >'.date('d-m-Y H:i',strtotime($a_weeklyontime[$i]["senddate"])).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["count_senddate"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["count_sendtime"]).'</td>
			</tr>';
		}
	}
	$msg.='</table>';

   $msg.='<p>'.iconv("UTF-8", "TIS-620",$a_weeklyontime = $a_data['monthlyplan']['result']['late']['title']).'</p>
	<table cellspacing="0" style="width:2000px">
	<tr>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ลำดับ').'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'รหัสพนักงาน').'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ชื่อ-สกุล').''.$tab16.''.$tab16.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'อีเมล์').''.$tab16.''.$tab16.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'เบอร์มือถือ').''.$tab10.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'วัน-เวลา&nbsp;ที่ส่งรายงาน').''.$tab16.''.$tab16.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ส่งไม่ตรงเวลา&nbsp;(วัน)').''.$tab16.''.$tab16.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ส่งไม่ตรงเวลา&nbsp;(ชม.)').''.$tab16.''.$tab16.'</th>
	</tr>
	';
	if(count($a_data['monthlyplan']['result']['late'])>0){
		$a_weeklyontime = $a_data['monthlyplan']['result']['late']["data"];
		for($i=0;$i<count($a_weeklyontime);$i++){
			$msg.='
			<tr class="spec01">
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["no"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["id"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["email"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["phone"]).'</td>
			  <td scope="row" >'.date('d-m-Y H:i',strtotime($a_weeklyontime[$i]["senddate"])).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["count_senddate"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["count_sendtime"]).'</td>
			</tr>';
		}
	}
	$msg.='</table>';

    $msg.='<p>'.iconv("UTF-8", "TIS-620",$a_weeklyontime = $a_data['monthlyplan']['result']['notsend']['title']).'</p>
	<table cellspacing="0" style="width:2000px">
	<tr>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ลำดับ').'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'รหัสพนักงาน').'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'ชื่อ-สกุล').''.$tab16.''.$tab16.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'อีเมล์').''.$tab16.''.$tab16.''.$tab10.'</th>
	  <th scope="row" >'.iconv("UTF-8", "TIS-620",'เบอร์มือถือ').''.$tab10.''.$tab10.'</th>
	</tr>
	';
	if(count($a_data['monthlyplan']['result']['notsend'])>0){
		$a_weeklyontime = $a_data['monthlyplan']['result']['notsend']["data"];
		for($i=0;$i<count($a_weeklyontime);$i++){
			$msg.='
			<tr class="spec01">
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["no"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["id"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["name"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["email"]).'</td>
			  <td scope="row" >'.iconv("UTF-8", "TIS-620",$a_weeklyontime[$i]["phone"]).'</td>
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