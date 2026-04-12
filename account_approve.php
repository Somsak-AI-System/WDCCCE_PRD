<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	include("config.inc.php");

	include_once("library/dbconfig.php");
	include_once("library/myFunction.php");
	include_once("library/generate_MYSQL.php");

	global $generate;
	$generate = new generate($dbconfig ,"DB");
	$date=date('d-m-Y');
	$crmid = $_REQUEST["crmid"];

	require_once('modules/Accounts/Accounts.php');
	require_once('modules/Users/Users.php');
	require_once('include/utils/UserInfoUtil.php');


    $_REQUEST["ajxaction"] = "DETAILVIEW";

	$current_user = new Users();
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
	global $current_user;
	
		$case_focus = new Accounts();
		$case_focus->retrieve_entity_info($crmid,"Accounts");
						
		try {
			
			$case_focus->column_fields['approved_status'] = 'อนุมัติ';
			$case_focus->column_fields['approved_date'] = date('Y-m-d');
						
			$case_focus->id = $crmid;
			$case_focus->mode = "edit";		
			$case_focus->save("Accounts"); 
			
			$a_reponse["status"] = true;
			$a_reponse["error"] = "" ;
			$a_reponse["msg"] = "บันทึกเรียบร้อย" ;			
			$a_reponse["result"] = "";
			$a_reponse["url"] = "index.php";
		} catch (Exception $e) {
			$a_reponse["status"] = false;
			$a_reponse["error"] = $e->getMessage();
			$a_reponse["msg"] = "ลองอีกครั้ง" ;
			$a_reponse["result"] = "";
		}
		echo json_encode($a_reponse);
		
?>