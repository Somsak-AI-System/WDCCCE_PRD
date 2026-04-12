<?php
	session_start();

	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$userid = $_SESSION['user_id'];
	$crmid = $_REQUEST["crmid"];
	$action = $_REQUEST["action"];
	$quantity = $_REQUEST["quantity"];

	$sql_pre = 'select stock_quantity ,booking_order ,available_stock from aicrm_premuimproduct where premuimproductid = "'.$crmid.'" ';
	$a_data = $myLibrary_mysqli->select($sql_pre);

	$text = '';
	$error = '';
	if($action == 'add'){
		$stock_quantity = ($a_data[0]['stock_quantity'] + $quantity);
		$available_stock = ($a_data[0]['available_stock'] + $quantity);
		
		$sql_u = 'update aicrm_premuimproduct
		INNER JOIN aicrm_crmentity ON aicrm_premuimproduct.premuimproductid = aicrm_crmentity.crmid
		set aicrm_premuimproduct.stock_quantity = "'.$stock_quantity.'" , aicrm_premuimproduct.available_stock = "'.$available_stock.'" 
		, aicrm_crmentity.modifiedby = "'.$userid.'"  , aicrm_crmentity.modifiedtime = "'.date('Y-m-d H:i:s').'" 
		where aicrm_premuimproduct.premuimproductid = "'.$crmid.'" ';
		$myLibrary_mysqli->Query($sql_u);

		$text = 'Adjust Stock สำเร็จ';
		$a_reponse["status"] = true;

	}else{
		$stock_quantity = ($a_data[0]['stock_quantity'] - $quantity);
		$available_stock = ($a_data[0]['available_stock'] - $quantity);
		$booking_order = $a_data[0]['booking_order'];

		if($stock_quantity <= 0){
			$error = 'ไม่สามารถลดสินค้าติดลบได้';
			$a_reponse["status"] = false;
		}else if($stock_quantity < $booking_order){
			$error = 'ไม่สามารถลดสินค้าได้ เนื่องจากมีสินค้าที่ถูกจองไว้เกินจำนวนในสต๊อกสินค้า';
			$a_reponse["status"] = false;
		}else{
			$sql_u = 'update aicrm_premuimproduct
			INNER JOIN aicrm_crmentity ON aicrm_premuimproduct.premuimproductid = aicrm_crmentity.crmid
			set aicrm_premuimproduct.stock_quantity = "'.$stock_quantity.'" , aicrm_premuimproduct.available_stock = "'.$available_stock.'" 
			, aicrm_crmentity.modifiedby = "'.$userid.'"  , aicrm_crmentity.modifiedtime = "'.date('Y-m-d H:i:s').'" 
			where aicrm_premuimproduct.premuimproductid = "'.$crmid.'" ';
			$myLibrary_mysqli->Query($sql_u);
			$text = 'Adjust Stock สำเร็จ';
			$a_reponse["status"] = true;
		}
	}
	
	$a_reponse["error"] = $error;
	$a_reponse["msg"] = $text;
	$a_reponse["result"] = "";
	$a_reponse["url"] = "index.php";
	echo json_encode($a_reponse);

?>