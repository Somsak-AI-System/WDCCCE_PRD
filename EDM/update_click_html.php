<?
session_start();
global $path,$url_path;
$path="C:/AppServ/www/moai";
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
$id=$_REQUEST['id'];
$click_link=$_REQUEST['click_link'];
$campaignid=$_REQUEST['campaignid'];
$marketid=$_REQUEST['marketid'];

//$table="tbt_email_log_campaignid_".$campaignid."_marketid_".$marketid;
$table="tbt_email_log_smartemailid_".$campaignid ;

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
$genarate->query($sql);			
$sql="
select 
to_email,emailtargetlistid,from_id,domain_name
from ".$table." where 1
and id='".$id."'
";
$data=$genarate->process($sql,"all");

$sql= "insert into ".$table."_click(`dateclick`,ctnum,`uniqueid`,page,device,device_all,email,emailtargetlistid,from_id,domain_name) values(";
$sql.= "now(),'".$click_link."',".$id.",'html','".$device."','".$useragent."','".$data[0][0]."','".$data[0][1]."','".$data[0][2]."','".$data[0][3]."')";
//echo $sql;exit;
$genarate->query($sql,"all");

//Update การเก็บสถิติ
$sql="update tbt_report_tab_1 set stop_date=now() where campaign_id='".$campaignid."'";
$genarate->query($sql);		
//เก็บการ click 
$sql="select * from tbt_report_tab_2 where campaign_id='".$campaignid."' and link_id='".$click_link."'";
$data=$genarate->process($sql,"all");
if(count($data)>0){
}else{
	if($click_link=="1"){
		$sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','".$click_link."','หากท่านไม่สามารถอ่านอีเมล์ฉบับนี้ได้ กรุณา คลิกที่นี่','0','Active');";
		$genarate->query($sql);	
	}else if($click_link=="3"){
		$sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','".$click_link."','หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก กรุณายกเลิกการรับข่าวสารที่นี่','0','Active');";
		$genarate->query($sql);	
	}else{
		$sql="select url_click from aicrm_campaign_email_marketing where campaignid='".$campaignid."' and id='".$marketid."'; ";
		$data_url_click=$genarate->process($sql,"all");

		$sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','".$click_link."','".$data_url_click[0][0]."','0','Active');";
		$genarate->query($sql);	
	}
}
//update Click To tbt_report_tab_3
$sql="
SELECT ctnum, uniqueid,dateclick,".$table."_click.domain_name
,".$table."_click.emailtargetlistid
,".$table."_click.bounce
,".$table."_click.email
FROM ".$table."_click
LEFT JOIN ".$table." ON ".$table.".id = ".$table."_click.uniqueid
WHERE active =1
AND report = 1
AND ".$table.".bounce =0
AND ".$table."_click.bounce =0
AND ".$table."_click.email =0
AND  ".$table.".campaignid='".$campaignid."'
AND ctnum!=0
";
$data=$genarate->process($sql,"all");
if(count($data)>0){
	$sql="update tbt_report_tab_3 set email_click='".count($data)."' where campaign_id='".$campaignid."'";
	$genarate->query($sql,"all");
	$sql="select link_id,total_click from tbt_report_tab_2 where campaign_id='".$campaignid."' ";
	$data_loop=$genarate->process($sql,"all");
	//print_r($data_loop);
	for($i=0;$i<count($data_loop);$i++){
		$sql="
		SELECT ctnum, uniqueid,dateclick,".$table."_click.domain_name
		,".$table."_click.emailtargetlistid
		,".$table."_click.bounce
		,".$table."_click.email
		FROM ".$table."_click
		LEFT JOIN ".$table." ON ".$table.".id = ".$table."_click.uniqueid
		WHERE active =1
		AND report = 1
		AND ".$table.".bounce =0
		AND ".$table."_click.bounce =0
		AND ".$table."_click.email =0
		AND  ".$table.".campaignid='".$campaignid."'
		AND ctnum!=0
		and ctnum ='".$data_loop[$i][0]."'
		";
		//echo $sql;exit;
		$data=$genarate->process($sql,"all");
		$sql="update tbt_report_tab_2 set total_click='".count($data)."' where campaign_id='".$campaignid."' and link_id='".$data_loop[$i][0]."';";
		$genarate->query($sql);	
		//echo $sql."<br>";
	}
}

?>