<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");

include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");

global $generate;
$generate = new generate($dbconfig ,"DB");

if($_REQUEST['function'] == "ProductPriceType"){
	$sql = "
    SELECT product_price_typeid, product_price_type as product_price_type_name FROM aicrm_product_price_type WHERE presence=1 and product_price_type != '--None--' ORDER BY product_price_type ";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['product_price_typeid'];
		$data_details[$i]['name'] = $data[$i]['product_price_type_name'];
	}
		
	echo json_encode($data_details);

}
?>
