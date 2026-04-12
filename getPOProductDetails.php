<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");

include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");

global $generate;
$generate = new generate($dbconfig ,"DB");

if($_REQUEST['function'] == "gr_percentage"){
	$sql = "
    SELECT 
	'' as 'gr_percentage_id',
	'-- none --' as 'gr_percentage_name'
	
	UNION
	
	SELECT 
	'1' as 'gr_percentage_id',
	'90%' as 'gr_percentage_name'
	
	UNION
	
	SELECT 
	'2' as 'gr_percentage_id',
	'100%' as 'gr_percentage_name'
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['gr_percentage_id'];
		$data_details[$i]['name'] = $data[$i]['gr_percentage_name'];
	}
		
	echo json_encode($data_details);
}
if($_REQUEST['function'] == "po_price_type"){
	$sql = "
    SELECT 
	'' as 'po_price_type_id',
	'-- none --' as 'po_price_type_name'
	
	UNION
	
	SELECT 
	'1' as 'po_price_type_id',
	'Standard' as 'po_price_type_name'
	
	UNION
	
	SELECT 
	'2' as 'po_price_type_id',
	'Special' as 'po_price_type_name'
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['po_price_type_id'];
		$data_details[$i]['name'] = $data[$i]['po_price_type_name'];
	}
		
	echo json_encode($data_details);
}
?>
