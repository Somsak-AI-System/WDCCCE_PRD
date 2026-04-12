<?php
session_start();
include("config.inc.php");
require_once("library/dbconfig.php");
require_once("library/myFunction.php");
require_once("library/generate_MYSQL.php");
global $generate;
$generate = new generate($dbconfig ,"DB");


// print_r($_REQUEST); exit();

$sql = "SELECT
aicrm_inventorypurchasesorderrel.* ,
aicrm_crmentity.smownerid,
CONCAT(aicrm_users.first_name_th,' ',aicrm_users.last_name_th) owner_name,
aicrm_projects.projects_no
FROM
aicrm_inventorypurchasesorderrel 
LEFT JOIN aicrm_crmentity ON aicrm_inventorypurchasesorderrel.id = aicrm_crmentity.crmid
LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
LEFT JOIN aicrm_projects ON aicrm_inventorypurchasesorderrel.projectsid = aicrm_projects.projectsid
	";

$where = " WHERE aicrm_inventorypurchasesorderrel.id='".$_REQUEST['purchasesorderid']."'";
if(isset($_REQUEST['searchKey']) && $_REQUEST['searchKey']!='' && $_REQUEST['searchField']!=''){
	$where .= " AND (".$_REQUEST['searchField']." LIKE '%" . $_REQUEST["searchKey"] . "%')";
}

$start = 0;
$pageSize = isset($_REQUEST['pageSize']) && $_REQUEST['pageSize']!='' ? $_REQUEST['pageSize']:10;
if(isset($_REQUEST['pageNumber']) && $_REQUEST['pageNumber'] > 1){
	$page = $_REQUEST['pageNumber'] - 1;
	$start = $page * $pageSize;
}
$limit = "ORDER BY aicrm_inventorypurchasesorderrel.sequence_no LIMIT ".$start.", ".$pageSize;

$sqlCount = $sql.$where;
$sql = $sql.$where.$limit;

// echo $sql; exit();

$dataCount = $generate->process($sqlCount,"all");
// print_r($dataCount); exit();

$data = $generate->process($sql,"all");
// $generate->query($sql);
// print_r($data); exit();

$returnData = array(
	'totalCount' => count($dataCount),
	'data' => $data
);

// -- LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
// 	-- LEFT JOIN aicrm_users AS create_by ON aicrm_crmentity.smcreatorid = create_by.id
// 	-- LEFT JOIN aicrm_users AS modified_by ON aicrm_crmentity.modifiedby = modified_by.id  

echo json_encode($returnData);
