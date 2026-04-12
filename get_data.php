<?php
header('Content-Type: text/html; charset=tis-620');
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;
$mode = $_REQUEST["mod"];

	if($mode=="get_user"){
		$fullname = $_REQUEST["fullname"];
		$sql = "select user_name as username
				,phone_mobile as usertel
				,position as userposition
				,area as userarea
				,email1 as email
				from aicrm_users  
				where deleted=0 ";
		$sql .= " and concat(first_name,' ',last_name) = '".$fullname."' ";
		//echo $sql; exit;
		$a_data = $myLibrary_mysqli->select($sql);
		echo json_encode($a_data[0]);
	}

	if($mode=="get_buyer"){
		$fullname = $_REQUEST["fullname"];
		$sql = "select address,phone,email from aicrm_config_vendorbuyer where type='Buyer' and deleted = 0";
		$sql .= " and name = '".$fullname."' and status = 'Active' ";
		$a_data = $myLibrary_mysqli->select($sql);
		echo json_encode($a_data[0]);
	}

	if($mode=="get_vender"){
		$fullname = $_REQUEST["fullname"];
		$sql = "select address,phone,email from aicrm_config_vendorbuyer where type='Vender' and deleted = 0";
		$sql .= " and name = '".$fullname."' and status = 'Active' ";
		$a_data = $myLibrary_mysqli->select($sql);
		echo json_encode($a_data[0]);
	}
