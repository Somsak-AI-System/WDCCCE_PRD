<?php
session_start();
include("config.inc.php");

	//$url = $site_URL."WB_Service_AI/image/delete_image";
	$url = $site_URL_service."image/delete_image";
  	$crmid = $_REQUEST['crmid'];  
  	$module = $_REQUEST['module'];   
    $data1["crmid"] =   array(
		'0' => $crmid,
	);
	
	$data1["modifiedby"] = $_SESSION["authenticated_user_id"] ;	
	$data_save=$data1;
	$fields = array(
			'AI-API-KEY'=>"1234",
			'data'=> $data_save,
			'module'=> $module ,
	);
	//url-ify the data for the POST
	$fields_string = json_encode($fields);
	// jSON URL which should be requested
	$json_url = $url;
		 
	// jSON String for request
	$json_string = $fields_string;
	 
	// Initializing curl
	$ch = curl_init( $json_url );
	 
	// Configuring curl options
	$options = array(
		CURLOPT_POST => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
		CURLOPT_POSTFIELDS => $json_string,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
	);

	// Setting curl options
	curl_setopt_array( $ch, $options );
	// Getting results
	$result =  curl_exec($ch); // Getting jSON result string	
	echo json_encode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$result),true);

?>