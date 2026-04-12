<?php
	session_start();
	
	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$date=date('d-m-Y');
	$pquery = "select * from aicrm_lostreason where presence = 1 and lostreason != '--None--' order by lostreasonid";
	$lostreason = $myLibrary_mysqli->select($pquery);

	$pquery = "select * from aicrm_wonreason where presence = 1 and wonreason != '--None--' order by wonreasonid";
	$wonreason = $myLibrary_mysqli->select($pquery);
		
	$competitor = array();
	$com = "select aicrm_competitor.competitorid , aicrm_competitor.competitor_name 
	from aicrm_competitor
	inner join aicrm_competitorcf on aicrm_competitorcf.competitorid = aicrm_competitor.competitorid
	inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_competitor.competitorid
	where aicrm_crmentity.deleted = 0 order by aicrm_competitor.competitor_name  asc";
	$competitor = $myLibrary_mysqli->select($com);


	$data['lostreason'] = $lostreason;
	$data['wonreason'] = $wonreason;
	$data['competitor'] = $competitor;

	echo json_encode($data);
?>