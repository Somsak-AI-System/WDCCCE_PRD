<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
	
	$date=date('d-m-Y');
	$user = $_SESSION['user_id'];
	$crmid = $_REQUEST["crmid"];
	$module = $_REQUEST["module"];
	$comment = $_REQUEST["comment"];

	$tablecomment = Array(
		'Promotionvoucher' =>array(
			'table'=>'aicrm_promotionvouchercomments',
			'keyid'=>'promotionvoucherid',
		),
		'Calendar'=>array(
			'table'=>'aicrm_commentplan',
			'keyid'=>'activityid',
		),
		'Premuimproduct'=>array(
			'table'=>'aicrm_premuimproductcomments',
			'keyid'=>'premuimproductid',
		),
		'Promotion'=>array(
			'table'=>'aicrm_promotioncomments',
			'keyid'=>'promotionid',
		),
		'Campaigns'=>array(
			'table'=>'aicrm_campaigncomments',
			'keyid'=>'campaignid',
		),
		'Deal'=>array(
			'table'=>'aicrm_dealcomments',
			'keyid'=>'dealid',
		),
		'PriceList'=>array(
			'table'=>'aicrm_pricelistscomments',
			'keyid'=>'pricelistid',
		),
		'Quotes'=>array(
			'table'=>'aicrm_quotescomments',
			'keyid'=>'quoteid',
		),
		'Salesorder'=>array(
			'table'=>'aicrm_salesordercomments',
			'keyid'=>'salesorderid',
		),
		'Projects'=>array(
			'table'=>'aicrm_projectscomments',
			'keyid'=>'projectsid',
		),
		'HelpDesk'=>array(
			'table'=>'aicrm_ticketcomments',
			'keyid'=>'ticketid',
		),
		'Job'=>array(
			'table'=>'aicrm_jobscomments',
			'keyid'=>'jobid',
		),
		'Servicerequest'=>array(
			'table'=>'aicrm_servicerequestcomments',
			'keyid'=>'servicerequestid',
		),
		'Leads'=>array(
			'table'=>'aicrm_leaddetailscomments',
			'keyid'=>'leadid',
		),
		'Accounts'=>array(
			'table'=>'aicrm_accountcomments',
			'keyid'=>'accountid',
		),
		'Contacts'=>array(
			'table'=>'aicrm_contactdetailscomments',
			'keyid'=>'contactid',
		),
		'Competitor'=>array(
			'table'=>'aicrm_competitorcomments',
			'keyid'=>'competitorid',
		),
		'Products'=>array(
			'table'=>'aicrm_productscomments',
			'keyid'=>'productid',
		),
		'Service'=>array(
			'table'=>'aicrm_servicecomments',
			'keyid'=>'serviceid',
		),
		'Documents'=>array(
			'table'=>'aicrm_notescomments',
			'keyid'=>'notesid',
		),
		'Faq'=>array(
			'table'=>'aicrm_faqcomments',
			'keyid'=>'faqid',
		),
		'KnowledgeBase'=>array(
			'table'=>'aicrm_knowledgebasecomments',
			'keyid'=>'knowledgebaseid',
		),
		
	);
	
	$table = $tablecomment[$module]['table'];
	$keyid = $tablecomment[$module]['keyid'];
	if(!isset($table)){
		$a_reponse["status"] = false;
		$a_reponse["error"] = "Not insert comment";
		echo json_encode($a_reponse);
		exit;
	}
	$dateadd = date('Y-m-d H:i:s');
	$sql = "Insert into ".$table." (".$keyid.",comments,ownerid,ownertype,createdtime) value ('".$crmid."','".$comment."','".$user."','user','".$dateadd."')";
	
	$myLibrary_mysqli->Query($sql);

	$text = 'Insert Complete';

	
	$a_reponse["status"] = true;
	$a_reponse["error"] = "" ;

	$a_reponse["msg"] = $text;
	$a_reponse["result"] = "";
	$a_reponse["username"] = $_SESSION['login_user_name'];
	$a_reponse["dateadd"] = date("d-m-Y H:i:s", strtotime($dateadd));
	$a_reponse["comment"] = $comment;
	$a_reponse["url"] = "index.php";
	echo json_encode($a_reponse);

?>