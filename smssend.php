<?
include_once('config.php');
require_once('include/logging.php');
require_once('data/Tracker.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once("include/Zend/Json.php");
require_once("library/myFunction.php");

//error_reporting(E_ALL ); 
//ini_set("display_errors", 1);

if($_REQUEST["myaction"]=='send'){	
	$mobile_number = str_replace("-","",$_REQUEST["mobile"]);
	$message = $_REQUEST["message"];
	$message_type = '0';

	$params = [
		'message' => $_REQUEST['message'],
		'phone' => $mobile_number,
		'sender' => $_REQUEST['sender'],
		'send_date' => '',
		'expire' => ''
	];
	
	$header = [
		'Content-Type: application/json',
		'api_key:'.$_REQUEST['api_key'],
		'secret_key:'.$_REQUEST['secret_key']
	];
	
	$url = 'https://portal-otp.smsmkt.com/api/send-message';
	$fields_string = json_encode($params);
	$ch = curl_init( $url );
	$options = array(
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_POSTFIELDS => $fields_string,
		CURLOPT_BUFFERSIZE => 1024,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
	);

	curl_setopt_array( $ch, $options );
	$result =  curl_exec($ch);
	$result = json_decode($result, true);

	echo "<script type='text/javascript'>alert('Send Complete');window.close(); </script>";
}		
//$search = $genarate->process($sql_select.$sql_from.$sql_where,"all");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="themes/softed/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Send SMS</title>
</head>

<body>
<form action="smssend.php" method="POST">
<input type="hidden" name="myaction" value="">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="small">
<tr>
    	<td colspan="2" class="detailedViewHeader">กรุณาเลือกเงื่อนไข</td>
    </tr>
    <tr>
    <td width="90px" align="right"  class="dvtCellLabel">Mobile  Number : &nbsp;</td>
    <td class="dvtCellInfo">
    	<textarea name="mobile" cols="50" rows="5" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';"><?= getphone($_REQUEST["module"],$_REQUEST["crmid"])?></textarea>
		
		</td>
  </tr>
  <!--
    <tr>
    <td width="90px" align="right"  class="dvtCellLabel">Language  : &nbsp;</td>
    <td class="dvtCellInfo">
    <select name="lan" class="small">
    	<option value="0">English</option>
        <option value="1">Thai</option>
        <option value="2">English & Thai</option>
    </select>
	</td>
  </tr>
  -->
    <tr>
    <td width="90px" align="right"  class="dvtCellLabel">Message : &nbsp;</td>
    <td class="dvtCellInfo"><textarea name="message" cols="50" rows="5" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';"></textarea>
	</td>
  </tr>
  <!--
<input name="sendername" type="hidden" value="A I SYSTEM" />
<input name="username" type="hidden" value="AI_SYSTEM" />
<input name="password" type="hidden" value="Password1234" />
-->
<?
$sql="SELECT aicrm_config_sender_sms.* FROM aicrm_config_sender_sms WHERE deleted = '0' AND sms_status = 'Active' GROUP BY sms_username";
$result = $adb->pquery($sql, array());
$sender = $adb->query_result($result,0,"sms_sender");//"AISYSTEM";
$api_key = $adb->query_result($result,0,"api_key");//
$secret_key = $adb->query_result($result,0,"secret_key");

?>
<input name="sender" type="hidden" value="<?=$sender?>" />
<input name="api_key" type="hidden" value="<?=$api_key?>" />
<input name="secret_key" type="hidden" value="<?=$secret_key?>" />
   <tr>
    <td align="center"  class="crmTableRow small lineOnTop" colspan="2">
		<input name="submit" type="submit" value="Send Sms"  class="crmbutton small edit" onclick="document.all.myaction.value='send'"  />
	</td>
    </tr>
</table>
</form>
</body>
</html>
