<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
	
	$created_at=date('Y-m-d H:i:s');
	$user = $_SESSION['user_id'];
	$crmid = $_REQUEST["crmid"];
	$generate = ($_REQUEST["generate"]+1);
	$voucher_name = $_REQUEST["voucher_name"];
	$voucher_amount = $_REQUEST["voucher_amount"];
	$startdate = date('Y-m-d', strtotime(@$_REQUEST["startdate"]));
	$enddate = date('Y-m-d', strtotime(@$_REQUEST["enddate"]));
	$voucher_usage = $_REQUEST["voucher_usage"];
	$voucher_type = $_REQUEST["voucher_type"];
	$value = $_REQUEST["value"];
	$max_discount_amount = $_REQUEST["max_discount_amount"];
	$minimum_purchase = $_REQUEST["minimum_purchase"];
	$vouchermessage = $_REQUEST["vouchermessage"];
	$voucherremark = $_REQUEST["voucherremark"];
	
	if($crmid == ''){
		$a_reponse["status"] = false;
		$a_reponse["error"] = "No Gregorian";
		echo json_encode($a_reponse);
		exit;
	}
	$dateadd = date('Y-m-d H:i:s');
	
	$sql = "Insert into tbt_generate_promotionvoucher 
	(
	promotionvoucherid,	voucher_name,	voucher_amount,			startdate,			enddate,		voucher_usage,
	voucher_type,		value,			max_discount_amount,	minimum_purchase,	vouchermessage,	voucherremark,
	generate,			autogen_status,	created_by,				created_at) 
	value (
	'".$crmid."',		'".$voucher_name."',	'".$voucher_amount."',	'".$startdate."',	'".$enddate."',	'".$voucher_usage."',
	'".$voucher_type."',	'".$value."',		'".$max_discount_amount."',	'".$minimum_purchase."',	'".$vouchermessage."',	'".$voucherremark."',
	'".$generate."',	'Pending',		'".$user."' ,	'".$created_at."'
	) ";
	$myLibrary_mysqli->Query($sql);

	$sql_update = "Update aicrm_promotionvoucher set generate = '".$generate."' where promotionvoucherid = '".$crmid."' ";
	$myLibrary_mysqli->Query($sql_update);

	$text = 'Setting Generate Complete';
	
	$a_reponse["status"] = true;
	$a_reponse["error"] = "" ;

	$a_reponse["msg"] = $text;
	$a_reponse["result"] = "";
	$a_reponse["url"] = "index.php";
	echo json_encode($a_reponse);

?>
