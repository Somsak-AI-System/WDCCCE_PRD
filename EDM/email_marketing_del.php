<?
	session_start();
	include("../config.inc.php");
	include("../library/dbconfig.php");
	include("../library/myFunction.php");
	include("../library/genarate.inc.php");
	include ("../phpmailer/class.phpmailer.php");
	$genarate = new genarate($dbconfig ,"DB");
	$emailid=$_REQUEST["emailid"];
	$campaignid=$_REQUEST["campaignid"];
	$userid=$_REQUEST["userid"];
	$date=date('Y-m-d H:i:s');
	$sql1 = "update  aicrm_campaign_email_marketing  set  deleted='1',modified_by='".$userid."',modifiedtime='".$date."'
				where  id='".$emailid."' 
				and campaignid='".$campaignid."'
				";
			//echo $sql1."<br>";exit;
	$genarate->query($sql1);
	echo "<script type='text/javascript'>  window.location.replace('index.php?action=CallRelatedList&module=Campaigns&record=".$campaignid."&parenttab=Marketing');</script>";
	
?>