<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$dealid = $_REQUEST['dealid'];
    $userid = $_SESSION['authenticated_user_id'];
    $stage = $_REQUEST['stage'];
	$comments = !isset($_REQUEST['comments']) ? '':$_REQUEST['comments'];

    $wonreason = @$_REQUEST['wonreason'];
    $lostreason = @$_REQUEST['lostreason'];
    $remark = @$_REQUEST['remark'];
    $competitorid = @$_REQUEST['competitorid'];
    $moredetails = @$_REQUEST['moredetails'];
    $newcompetitor = @$_REQUEST['newcompetitor'];    
	$date=date('Y-m-d H:i:s');
	
	$pquery = "update aicrm_deal
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
		set aicrm_deal.stage = '".$stage."', aicrm_crmentity.modifiedtime = '".$date."', 
		aicrm_crmentity.modifiedby = '".$userid."'" ;

	if($wonreason != ''){
		$pquery .= " ,aicrm_deal.wonreason = '".$wonreason."' ";
	}
	if($lostreason != ''){
		$pquery .= " ,aicrm_deal.lostreason = '".$lostreason."' ";
	}
	if($remark != ''){
		$pquery .= " ,aicrm_deal.remark = '".$remark."' ";
	}
	if($competitorid != ''){
		$pquery .= " ,aicrm_deal.competitorid = '".$competitorid."' ";
	}
	if($moredetails != ''){
		$pquery .= " ,aicrm_deal.moredetails = '".$moredetails."' ";
	}
	$pquery .= " where aicrm_crmentity.crmid = ".$dealid."; ";
	
	$myLibrary_mysqli->Query($pquery);

	// Insert log change Status
	$sql = "INSERT INTO tbt_log_deal_changestatus (modules, crmid, userid, status, comments, date_time, form) VALUES ('Deal',
	'".$dealid."', '".$userid."', '".$stage."', '".$comments."', '".$date."', '')";
	$myLibrary_mysqli->Query($sql);
	
	$return['status'] = true;
	$return['stage'] = $stage;
	$return['date'] = date('d-m-Y H:i:s', strtotime($date));
	echo json_encode($return);
?>