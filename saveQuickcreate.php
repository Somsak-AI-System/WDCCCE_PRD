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
	require_once('modules/Accounts/Accounts.php');
	require_once('modules/Users/Users.php');
	require_once('include/utils/UserInfoUtil.php');

    $_REQUEST["ajxaction"] = "EDITVIEW";
	
	$current_user = new Users();
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
	global $current_user;
	
	$query = "SELECT accountname FROM aicrm_account,aicrm_crmentity WHERE accountname ='".$_REQUEST['accountname']."' and aicrm_account.accountid = aicrm_crmentity.crmid and aicrm_crmentity.deleted != 1";
	$checkdup =$generate->process($query,"all");

	if(!empty($checkdup)){
		
		$a_reponse["status"] = false;
		$a_reponse["error"] = "" ;
		$a_reponse["result"] = "";
		$a_reponse["msg"] = "Account Name Already Exists!" ;
		$a_reponse["crmid"] = '';
		echo json_encode($a_reponse);
		exit;
	}

	$account_focus = new Accounts();
	$account_focus->column_fields['accountname'] = $_REQUEST['accountname'];
	$account_focus->column_fields['contact_tel'] = $_REQUEST['q_contact_tel'];
	$account_focus->column_fields['contact_person'] = $_REQUEST['q_contact_person'];
	$account_focus->column_fields['status_account'] = $_REQUEST['q_status_account'];
	$account_focus->column_fields['accounttype'] = $_REQUEST['q_accounttype'];
	$account_focus->column_fields['address'] = $_REQUEST['q_address'];
	$account_focus->column_fields['bill_to_address'] = $_REQUEST['q_bill_to_address'];
	$account_focus->column_fields['mailing_address'] = $_REQUEST['q_mailing_address'];
	$account_focus->column_fields['corporate_registration_number_crn'] = $_REQUEST['q_corporate_registration_number_crn'];
	$account_focus->column_fields['assigned_user_id'] = $_REQUEST['q_assigned_user_id'];
	
	//$_REQUEST["module"] = "Accounts";
	$account_focus->id = '';
	$account_focus->mode = "";
	$account_focus->save("Accounts");

	if(isset($_REQUEST['contactid'])){
		$relate = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES (".$_REQUEST['contactid'].",'Contacts',".$account_focus->id.",'Accounts')";
		$generate->query($relate);
	}
	
	$a_reponse["status"] = true;
	$a_reponse["error"] = "" ;
	if($quotationstatus!="Complete"){
		$a_reponse["msg"] = $msg. " Complete" ;
	}
	$a_reponse["result"] = "";
	$a_reponse["crmid"] = $account_focus->id;

	echo json_encode($a_reponse);

?>