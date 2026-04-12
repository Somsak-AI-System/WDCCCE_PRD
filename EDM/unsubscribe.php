<?
session_start();
global $path,$url_path;
$path="D:/AppServ/www/moai";
$url_path="http://".$_SERVER['HTTP_HOST']."/moai/";
require_once ($path."/config.inc.php");		
require_once ($path."/library/dbconfig.php");
require_once ($path."/phpmailer/class.phpmailer.php");
require_once ($path."/library/genarate.inc.php");
require_once ($path."/library/myFunction.php");
require_once ($path."/lib/swift_required.php");

//$generate = new generate($dbconfig ,"DB");
$genarate = new genarate($dbconfig ,"DB");
$id=$_REQUEST['id'];
$campaignid=$_REQUEST['campaignid'];
$marketid=$_REQUEST['marketid'];
$module=$_REQUEST['module'];
$crmid=$_REQUEST['crmid'];
//$table="tbt_email_log_campaignid_".$campaignid."_marketid_".$marketid;
$table="tbt_email_log_smartemailid_".$campaignid ;
$sql="update ".$table."  set unsubscribe=unsubscribe+1,unsubscribe_date='".date('Y-m-d H:i:s')."'
where id='".$id."' and campaignid='".$campaignid."'
";
//echo $sql;exit;
if($genarate->query($sql,"all")){
	//echo "555";
}

if($module=="Accounts"){
	$sql="update aicrm_account set unsubscribe=1,emailstatus='InActive',unsubscribe_date='".date('Y-m-d H:i:s')."' where accountid='".$crmid."' ";
	$genarate->query($sql,"all");	
/*}else if($module=="Contacts"){
	$sql="update aicrm_contactscf set cf_4763=1,cf_4436='InActive',cf_4764='".date('Y-m-d H:i:s')."' where contactid='".$crmid."' ";
	$genarate->query($sql,"all");
	*/
}else if($module=="Leads"){
	$sql="update aicrm_leaddetails set unsubscribe=1,emailstatus='InActive',unsubscribe_date='".date('Y-m-d H:i:s')."' where leadid='".$crmid."' ";
	$genarate->query($sql,"all");	
	
/*} else if($module=="EmailTarget"){
	$sql="update aicrm_emailtargetscf set cf_2349=1,cf_926='InActive',cf_2350='".date('Y-m-d H:i:s')."' where emailtargetid='".$crmid."' ";
	$genarate->query($sql,"all");	
	*/
} else if($module=="Opportunity"){
	$sql="update aicrm_opportunity set unsubscribe=1,emailstatus='InActive',unsubscribe_date='".date('Y-m-d H:i:s')."' where opportunityid='".$crmid."' ";
	$genarate->query($sql,"all");	
}
$sql="
SELECT 
* 
FROM ".$table." 
WHERE 1
and report=1
and active=1
and bounce=0
and unsubscribe>0
";
$data=$genarate->process($sql,"all");
if(count($data)>0){
	$sql="update tbt_report_tab_3 set email_unsun='".count($data)."' where campaign_id='".$campaignid."'";
	$genarate->query($sql,"all");
}
//echo $sql;exit;
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
<table width="640" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td><img src="images/banner.jpg" ></td>
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
          <input type="submit" name="Submit" value="Close" onClick="window.close()";>
        </form></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
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