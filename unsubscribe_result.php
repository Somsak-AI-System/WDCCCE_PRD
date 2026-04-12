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

$table="tbt_email_log_smartemailid_".$campaignID ;
$sql="update ".$table."  set unsubscribe=unsubscribe+1, unsubscribe_date='".date('Y-m-d H:i:s')."'
where from_id='".$accountID."' and campaignid='".$campaignID."'
";

$sql="UPDATE aicrm_account SET unsubscribe=1 ,emailstatus='InActive', unsubscribe_date='".date('Y-m-d H:i:s')."' WHERE accountid='".$accountID."'";
$result = $generate->process($sql,"all");

?>
<html><head><title>Unsubscribe</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="expires" content="0">
<style type="text/css">

</style>
</head><body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table width="640" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#3eb6e8">
  <tr>
    <td>
<table width="640" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="575" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" class="style102">ขอบคุณค่ะ </td>
      </tr>
      <tr>
        <td align="center" class="style102">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" class="style102"><p class="style102">การยกเลิกการรับข่าวสารของท่าน จะต้องใช้เวลาประมาณ 2-3 สัปดาห์</p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
        <form name="form1" method="get" action="">
          <input type="submit" name="Submit" value="Close" onClick="window.open('','_self').close();";>
        </form>
    </td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
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
</body>
</html>
