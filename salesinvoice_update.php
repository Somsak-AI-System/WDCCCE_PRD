<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("config.inc.php");
	require_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
	$date=date('d-m-Y');
	global $current_user;

	$crmid = $_REQUEST["crmId"];

	$sql = "UPDATE aicrm_salesinvoice 
	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid
	SET invoice_status='Finish', modifiedtime=NOW(), modifiedby='".$current_user->id."'
	WHERE salesinvoiceid='$crmid'";
	$myLibrary_mysqli->query($sql);

	echo json_encode(['status' => 'success', 'message' => 'Sales Invoice updated successfully.']);
?>