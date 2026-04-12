<?php 
	//import_request_variables("gP", "");
	
	$CAMPAIGNID = $_GET["CAMPAIGNID"];
	$UNIQUEID = $_GET["UNIQUEID"];
?>

<html><head><title>Unsubscribe</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="expires" content="0">
<style type="text/css">
<!--
.style38 {font-size: 9}
.style69 {font-size: 2px; font-family: "MS Sans Serif"; color: #0066CC; }
.style77 {font-size: 24}
.style80 {font-size: 2px}
.style82 {font-family: "MS Sans Serif"}
.style88 {font-family: Tahoma}
.style91 {font-size: 12px; }
.style100 {color: #440669}
.style101 {font-size: 2px; color: #440669; font-family: "MS Sans Serif"; }
.style102 {font-size: 12px; color: #440669; font-family: Tahoma; }
-->
</style>
</head><body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table width="640" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#3eb6e8">
  <tr>
    <td>
<table width="640" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="images/banner.jpg" ></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="575" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" class="style69"><p class="style102">กรณียกเลิกการรับ<font size="-1" face="Tahoma">ข่าวสาร </font>ท่านจะไม่ได้รับข่าวสารที่อาจจะอยู่ในความสนใจของท่านผ่านทาง&nbsp;อีเมล์&nbsp;<br>
          อีกต่อไป&nbsp;เนื่องจากข่าวสารประเภทข้อเสนอพิเศษต่างๆ&nbsp;จะไม่มีการจัดส่งให้กับท่านทางไปรษณีย์</p></td>
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
    <td align="center"><form name="form1" method="post" action="unsub_confirm.htm">
      <table width="170" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td width="75"><input type="button" name="Submit" value="Confirm" onClick="window.location.replace('unsubscribe.php?id=<?=$_REQUEST["id"]?>&campaignid=<?=$_REQUEST["campaignid"]?>&marketid=<?=$_REQUEST["marketid"]?>&module=<?=$_REQUEST["module"]?>&crmid=<?=$_REQUEST["crmid"]?>')">
          </td>
          <td width="20">&nbsp;</td>
          <td width="75"><input type="button" name="Reset" value="Cancel" onClick="javascript:window.open('','_self');window.close()">
		  </td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td align="center"><img src="images/footer.jpg" ></td>
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
