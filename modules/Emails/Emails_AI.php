<?
function send_mail($module,$to_email,$from_name,$from_email,$subject,$contents,$cc='',$bcc='',$attachment='',$emailid='',$logo='')
{
	$mail = new PHPMailer();
	
	setMailerProperties($mail,$subject,$contents,$from_email,$from_name,trim($to_email,","),$attachment,$emailid,$module,$logo);
	setCCAddress($mail,'cc',$cc);
	setCCAddress($mail,'bcc',$bcc);
	//print_r($mail);
	if(!$mail->Send()){
		echo "Mail False";
	}else{
		echo "Mail True";
	}	
}

function setMailerProperties($mail,$subject,$contents,$from_email,$from_name,$to_email,$attachment='',$emailid='',$module='',$logo='')
{
	
	$mail->Subject = $subject;
	$mail->Body = $contents;
	//$mail->Body = html_entity_decode(nl2br($contents));	//if we get html tags in mail then we will use this line
	$mail->AltBody = strip_tags(preg_replace(array("/<p>/i","/<br>/i","/<br \/>/i"),array("\n","\n","\n"),$contents));
	
	$mail->IsSMTP();		//set mailer to use SMTP
	//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
	setMailServerProperties($mail);
	
	
	
	
	$mail->From = $from_email;
	$mail->FromName = ($from_name);
	if($to_email != '')
	{
		if(is_array($to_email)) {
			for($j=0,$num=count($to_email);$j<$num;$j++) {
				$mail->addAddress($to_email[$j]);
			}
		} else {
			$_tmp = explode(",",$to_email);
			for($j=0,$num=count($_tmp);$j<$num;$j++) {
				$mail->addAddress($_tmp[$j]);
			}
		}
	}
	$mail->AddReplyTo($from_email);
	$mail->WordWrap = 50;
	//If we want to add the currently selected file only then we will use the following function
	if($attachment == 'current' && $emailid != '')
	{
		if (isset($_REQUEST['filename_hidden'])) {
			$file_name = $_REQUEST['filename_hidden'];
		} else {
			$file_name = $_FILES['filename']['name'];
		}
		addAttachment($mail,$file_name,$emailid);
	}
	
	//This will add all the aicrm_files which are related to this record or email
	if($attachment == 'all' && $emailid != '')
	{
		addAllAttachments($mail,$emailid);
	}	
	$mail->IsHTML(true);		// set email format to HTML
	return;
}
function setMailServerProperties($mail)
{
	global $generate;
	$sql="select * from aicrm_systems  where id=1";
	$data_email = $generate->process($sql,"all");	
	//print_r($data_email );
	if(count($data_email)>0){
		$sever =$data_email[0]['server'];
		$username=$data_email[0]['server_username'];
		$password=$data_email[0]['server_password'];
	}else{
		$sever ="mail.aisyst.com";
		$username="support_crm@aisyst.com";
		$password="1qaz2WSX";
	}
	//$sever ="bnex10.systems.com";
	//$username="support_crm@aisyst.com";
	//$password="1qaz2WSX";
	$mail->Host = $sever;		// specify main and backup server
	$mail->Username = $username ;	// SMTP username
	$mail->Password = $password ;	// SMTP password
	$mail->SMTPAuth = true;
	return;
}
function setCCAddress($mail,$cc_mod,$cc_val)
{

	if($cc_mod == 'cc')
		$method = 'AddCC';
	if($cc_mod == 'bcc')
		$method = 'AddBCC';
	if($cc_val != '')
	{
		$ccmail = explode(",",trim($cc_val,","));
		for($i=0;$i<count($ccmail);$i++)
		{
			$addr = $ccmail[$i];
			$cc_name = preg_replace('/([^@]+)@(.*)/', '$1', $addr); // First Part Of Email
			if(stripos($addr, '<')) {
				$name_addr_pair = explode("<",$ccmail[$i]);
				$cc_name = $name_addr_pair[0];
				$addr = trim($name_addr_pair[1],">");
			}
			if($ccmail[$i] != '')
				$mail->$method($addr,$cc_name);
		}
	}
}
?>