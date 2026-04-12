<?php
	session_start();

	header('Content-Type: text/html; charset=utf-8');
	include("../../config.inc.php");

	include_once("../../library/dbconfig.php");
	include_once("../../library/myFunction.php");
	include_once("../../library/generate_MYSQL.php");

	global $generate;
	$generate = new generate($dbconfig ,"DB");

	$date=date('d-m-Y');


	$crmid = $_REQUEST["crmid"];
	$chk = $_REQUEST["chk"];

	if($chk == 2){
		$senddate = date('Y-m-d', strtotime(@$_REQUEST["senddate"]));
		$sendtime = @$_REQUEST["sendtime"];
	}else{
		$senddate = date("Y-m-d");
		$sendtime = date("H:i",strtotime(date("H:i")." +10 minutes"));
	}


	$sql = "Update aicrm_smartemail set email_status = 'Schedule' , email_start_date = '".$senddate."' , email_start_time = '".$sendtime."' where smartemailid = '".$crmid."' ";
	$generate->query($sql);

	$text = 'Setup Smart Email Complete';
	
	$a_reponse["status"] = true;
	$a_reponse["error"] = "" ;

	//if($quotationstatus!="Complete"){
	$a_reponse["msg"] = $text;
	//}
	$a_reponse["result"] = "";
	$a_reponse["url"] = "index.php";
	echo json_encode($a_reponse);

?>