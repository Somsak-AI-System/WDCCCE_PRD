<?php
ini_set('memory_limit', '-1');
ini_set("max_execution_time", 0);
ini_set('memory_limit', '4024M');
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");
include("library/dbconfig.php");
require_once("library/generate_MYSQL.php");
require_once("library/general.php");
include_once("library/log.php");
include_once("library/myLibrary.php");
global $generate,$current_user,$root_directory,$root_directory,$site_URL,$site_URL_service,$dbconfig;

$url = $site_URL_service."/index.php/batch/import_attachment_product" ;
$crmid = $_REQUEST["crmid"];
$userid= $_REQUEST["userid"]==""? $_SESSION['authenticated_user_id']:$_REQUEST["userid"];

$a_param["crmid"] = $crmid;

$fields_string = json_encode($a_param);
$json_url = $url;
$json_string = $fields_string;
$ch = curl_init( $json_url );
$options = array(
	CURLOPT_POST => true,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
	CURLOPT_POSTFIELDS => $json_string,
	CURLOPT_BUFFERSIZE => 1024,
	CURLOPT_SSL_VERIFYHOST => false,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_DNS_USE_GLOBAL_CACHE => false,
	CURLOPT_DNS_CACHE_TIMEOUT => 2
);
curl_setopt_array( $ch, $options );
$result =  curl_exec($ch);
$result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true );
echo json_encode($result);
exit();
?>