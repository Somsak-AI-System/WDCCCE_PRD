<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/
echo header('Pragma: public');
echo header('Expires: 0');
echo header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
echo header('Cache-Control: private', false);
ini_set("include_path","../../");

session_start();
global $path,$url_path;
$path="D:/AppServ/www/moai";
//$path="/home/admin/domains/crmthai.net/public_html/sena/";
$url_path="http://".$_SERVER['HTTP_HOST']."/moai/";
//$url_path="http://sena.crmthai.net/";
require_once ($path."/config.inc.php");		
require_once ($path."/library/dbconfig.php");
require_once ($path."/phpmailer/class.phpmailer.php");
require_once ($path."/library/genarate.inc.php");
require_once ($path."/library/myFunction.php");
require_once ($path."/lib/swift_required.php");

//$generate = new generate($dbconfig ,"DB");
$genarate = new genarate($dbconfig ,"DB");
if($_REQUEST['app_key'] != $application_unique_key) {
	//exit;
}

$id=$_REQUEST['id'];
$campaignid=$_REQUEST['campaignid'];
$email_marketingid=$_REQUEST['email_marketingid'];
$emailtargetlistid=$_REQUEST['emailtargetlistid'];
$from_module=$_REQUEST['from_module'];
$from_id=$_REQUEST['from_id'];
$email=$_REQUEST['email'];
$table=$_REQUEST['table'];

/*$id= 3 ;
$campaignid=11254;
$email_marketingid=0;
$emailtargetlistid=0;
$from_module='Accounts';
$from_id=11255;
$email='wuttipong@aisyst.com';
$table='tbt_email_log_smartemailid_11254';*/

$date=date('Y-m-d H:i:s');
$sql="
select * from ".$table."_open where 1
and uniqueid='".$id."'
and dateopen='".$date."'
";
$data=$genarate->process($sql,"all");

$useragent = $_SERVER['HTTP_USER_AGENT'];
$useragent = strtolower($useragent);
$device="";
	if(strrpos($useragent,"iphone") > 0){
		$device="iphone";
	}else if(strrpos($useragent,"symbianos") > 0){
		$device="symbianos";
	}else if(strrpos($useragent,"ipad") > 0){
		$device="ipad";		
	}else if(strrpos($useragent,"ipod") > 0){
		$device="ipod";	
	}else if(strrpos($useragent,"android") > 0){
		$device="android";	
	}else if(strrpos($useragent,"blackberry") > 0){
		$device="blackberry";	
	}else if(strrpos($useragent,"samsung") > 0){
		$device="samsung";	
	}else if(strrpos($useragent,"nokia") > 0){
		$device="nokia";	
	}else if(strrpos($useragent,"windows ce") > 0){
		$device="windows ce";	
	}else if(strrpos($useragent,"sonyericsson") > 0){
		$device="sonyericsson";	
	}else if(strrpos($useragent,"webos") > 0){
		$device="webos";	
	}else if(strrpos($useragent,"wap") > 0){
		$device="wap";	
	}else if(strrpos($useragent,"motor") > 0){
		$device="motor";	
	}else if(strrpos($useragent,"symbian") > 0){
		$device="symbian";		
	}else if(strrpos($useragent,"msoffice") > 0 || strrpos($useragent,"msoffice") > 0){
		$device="Outlook";		
	}else if(strrpos($useragent,"Firefox") > 0 || strrpos($useragent,"firefox") > 0){
		$device="mozilla firefox";		
	}else if(strrpos($useragent,"MSIE") > 0 || strrpos($useragent,"msie") > 0){
		$device="Internet Explorer";		
	}else if(strrpos($useragent,"Safari") > 0 || strrpos($useragent,"safari") > 0){
		$device="Safari";		
	}
//Update �����ʶԵ�
$sql="update tbt_report_tab_1 set stop_date=now() where campaign_id='".$campaignid."'";
$genarate->query($sql);		

if(count($data)>0){
	
}else{
	$sql="
	select 
	to_email,emailtargetlistid,from_id,domain_name
	from ".$table." where 1
	and id='".$id."'
	";
	$data=$genarate->process($sql,"all");
	$sql= "insert ignore into ".$table."_open(`dateopen`,`uniqueid`,device,device_all,email,emailtargetlistid,from_id,domain_name) values(";
	$sql.= "'".$date."',".$id.",'".$device."','".$useragent."','".$data[0][0]."','".$data[0][1]."','".$data[0][2]."','".$data[0][3]."')";
	$genarate->query($sql,"all");
}
//update Open To tbt_report_tab_3
$sql="
SELECT ctnum, uniqueid,min(dateopen)as dateopen,".$table."_open.domain_name
,".$table."_open.emailtargetlistid
,".$table."_open.bounce
,".$table."_open.email
FROM ".$table."_open
LEFT JOIN ".$table." ON ".$table.".id = ".$table."_open.uniqueid
WHERE active =1
AND report = 1
AND ".$table.".bounce =0
AND ".$table."_open.bounce =0
AND ".$table."_open.email =0
and  ".$table.".campaignid='".$campaignid."'
group by uniqueid
";
$data=$genarate->process($sql,"all");
$sql="update tbt_report_tab_3 set email_open='".count($data)."' where campaign_id='".$campaignid."'";
$genarate->query($sql,"all");
//echo $sql;exit;
?>