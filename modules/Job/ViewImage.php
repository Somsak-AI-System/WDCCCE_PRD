<?php

include("config.inc.php");
include("library/dbconfig.php");
require_once("library/generate_MYSQL.php");
require_once("library/general.php");
require_once("library/Calendar.php");
global $generate,$current_user;

//$url = "http://".$_SERVER['HTTP_HOST']."/moai/WB_Service_AI/image/list_content";
$url = $site_URL_service."image/list_content";

$list_result = array();
$a_return = array();

if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$fields = array(
			'AI-API-KEY'=>"1234",
			'module' => "Job", //module
			"crmid" => $_REQUEST['record'],
			"setype" => 'Image Products',
	);
	
	$fields_string = json_encode($fields);
	$json_url = $url;
	
	$json_string = $fields_string;
	 
	// Initializing curl
	$ch = curl_init( $json_url );
	 
	// Configuring curl options
	$options = array(
		CURLOPT_POST => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
		CURLOPT_POSTFIELDS => $json_string
	);
	
	curl_setopt_array( $ch, $options );
	$result =  curl_exec($ch); // Getting jSON result string
	
	$data=@json_decode($result,true);

}

include_once 'view/view_image.php';
?>