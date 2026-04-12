<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");

include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");

global $generate;
$generate = new generate($dbconfig ,"DB");

if($_REQUEST['function'] == "ProductOwnBrand"){
	$sql = "
		SELECT 
		'' as 'product_own_brand_id',
		'-- none --' as 'product_own_brand_name'
		
		UNION
		
		SELECT 
		'1' as 'product_own_brand_id',
		'แบรนด์สินค้ากรีนแลม 1' as 'product_own_brand_name'
		
		UNION
		
		SELECT 
		'2' as 'product_own_brand_id',
		'แบรนด์สินค้ากรีนแลม 2' as 'product_own_brand_name'
		
		UNION
		
		SELECT 
		'3' as 'product_own_brand_id',
		'แบรนด์สินค้ากรีนแลม 3' as 'product_own_brand_name'
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['product_own_brand_id'];
		$data_details[$i]['name'] = $data[$i]['product_own_brand_name'];
	}
		
	echo json_encode($data_details);
}

if($_REQUEST['function'] == "CompetitorBrand"){
	$sql = "
	SELECT 
	'' as 'competitor_brand_id',
	'-- none --' as 'competitor_brand_name'
	
	UNION
	
	SELECT 
	'1' as 'competitor_brand_id',
	'แบรนด์คู่แข่ง 1' as 'competitor_brand_name'
	
	UNION
	
	SELECT 
	'2' as 'competitor_brand_id',
	'แบรนด์คู่แข่ง 2' as 'competitor_brand_name'
	
	UNION
	
	SELECT 
	'3' as 'competitor_brand_id',
	'แบรนด์คู่แข่ง 3' as 'competitor_brand_name'
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['competitor_brand_id'];
		$data_details[$i]['name'] = $data[$i]['competitor_brand_name'];
	}
		
	echo json_encode($data_details);

}


if($_REQUEST['function'] == "CompetitorBrandInProJ"){
	$sql = "
	SELECT 
	'' as 'compet_brand_in_proj_id',
	'-- none --' as 'compet_brand_in_proj_name'
	
	UNION
	
	SELECT 
	'1' as 'compet_brand_in_proj_id',
	'แบรนด์คู่แข่งในโครงการ 1' as 'compet_brand_in_proj_name'
	
	UNION
	
	SELECT 
	'2' as 'compet_brand_in_proj_id',
	'แบรนด์คู่แข่งในโครงการ 2' as 'compet_brand_in_proj_name'
	
	UNION
	
	SELECT 
	'3' as 'compet_brand_in_proj_id',
	'แบรนด์คู่แข่งในโครงการ 3' as 'compet_brand_in_proj_name'
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['compet_brand_in_proj_id'];
		$data_details[$i]['name'] = $data[$i]['compet_brand_in_proj_name'];
	}
		
	echo json_encode($data_details);

}

?>
