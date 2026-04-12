<?php
session_start();
include("config.inc.php");
require_once("library/dbconfig.php");
require_once("library/myFunction.php");
require_once("library/generate_MYSQL.php");
global $generate;
$generate = new generate($dbconfig, "DB");

$productID = $_REQUEST['productID'];
$today = date('Y-m-d');

$sql = "SELECT * FROM aicrm_pricelists
INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
WHERE aicrm_crmentity.deleted = 0
AND aicrm_pricelists.product_id = '".$productID."'
AND aicrm_pricelists.status_pricelist = 'Active'
AND aicrm_pricelists.pricelist_startdate <= '".$today."'
AND aicrm_pricelists.pricelist_enddate >= '".$today."'
ORDER BY aicrm_crmentity.modifiedtime DESC
LIMIT 1";
$pricelist = $generate->process($sql,"all");

// $sql = "SELECT aicrm_product_details.* FROM aicrm_product_details 
// INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_product_details.productdetailsid
// WHERE aicrm_crmentity.deleted = 0
// AND aicrm_product_details.product_id = '".$productID."'
// ORDER BY aicrm_crmentity.modifiedtime DESC
// LIMIT 1";
// $productdetail = $generate->process($sql,"all");

echo json_encode($pricelist);
