<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");

include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");

global $generate;
$generate = new generate($dbconfig ,"DB");

if($_REQUEST['function'] == "PricelistType"){
	$sql = "
    SELECT pricelist_typeid, pricelist_type as pricelist_type_name FROM aicrm_pricelist_type WHERE presence=1 and pricelist_type != '--None--' ORDER BY pricelist_typeid ";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['pricelist_typeid'];
		$data_details[$i]['name'] = $data[$i]['pricelist_type_name'];
	}
		
	echo json_encode($data_details);

}
?>
