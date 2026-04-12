<?php
session_start();
include("config.inc.php");
require_once("library/dbconfig.php");
require_once("library/myFunction.php");
require_once("library/generate_MYSQL.php");
global $generate,$site_URL;
$generate = new generate($dbconfig, "DB");

if(!isset($_REQUEST['accountid']) || $_REQUEST['accountid']=='') exit();
if(!isset($_REQUEST['campaignid']) || $_REQUEST['campaignid']=='') exit();

$accountID = isset($_REQUEST['accountid']) ? $_REQUEST['accountid']:'';
$campaignID = isset($_REQUEST['campaignid']) ? $_REQUEST['campaignid']:'';

// $sql = "SELECT * FROM aicrm_account WHERE accountid='59451'";
// $result = $generate->process($sql,"all");
// print_r($result);

?>
<html>
<head>
<title>Unsubscribe</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="expires" content="0">
<style type="text/css">

</style>
</head>
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table width="640" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#3eb6e8">
  <tr>
    <td>
<table width="640" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="575" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" class="style69">
            <p class="style102">กรณียกเลิกการรับ<font size="-1" face="Tahoma">ข่าวสาร </font>ท่านจะไม่ได้รับข่าวสารที่อาจจะอยู่ในความสนใจของท่านผ่านทาง&nbsp;อีเมล์&nbsp;<br>
            อีกต่อไป&nbsp;เนื่องจากข่าวสารประเภทข้อเสนอพิเศษต่างๆ&nbsp;จะไม่มีการจัดส่งให้กับท่านทางไปรษณีย์
            </p>
        </td>
      </tr>
      <tr>
        <td align="left" class="style101">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" class="style69"><p class="style102">หากต้องการยกเลิก กรุณาคลิก confirm</p></td>
      </tr>
      <tr>
        <td align="left"><p><span class="style100"></span></p></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><form name="form1" method="post" action="">
      <table width="170" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td width="75">
            <input type="button" name="Submit" value="Confirm" onClick="confirmSubscribe()">
          </td>
          <td width="20">&nbsp;</td>
          <td width="75"><input type="button" name="Reset" value="Cancel" onClick="javascript:window.open('','_self');window.close()">
		  </td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
    </table>
</td>
  </tr>
</table>
</td>
  </tr>
</table>
<script>
var siteURL = '<?php echo $site_URL; ?>';
var accountID = '<?php echo $accountID; ?>';
var campaignID = '<?php echo $campaignID; ?>';
function confirmSubscribe(){
    if(confirm('ยืนยันการยกเลิก')){
      window.location.href = `${siteURL}unsubscribe_result.php?accountid=${accountID}&campaignid=${campaignID}`;
    }
}
</script>
</body>
</html>