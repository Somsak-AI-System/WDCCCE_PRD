<?php

$fields =array();
foreach($_REQUEST as $key=>$val) 
{ 
	if($key == "data")
		$value=json_decode(base64_decode($val),true);	
	else
		$value=base64_decode($val);	
	if($key != "url"){
		$fields[$key] =  $value;

		if (strpos($value, "{") !== false) {
			$fields[$key]= json_decode($value);
		}
	}
} 


// encode to JSON
$json_str = json_encode($fields);


// get URL Link	
$json_url = base64_decode($_REQUEST['url']);
		 
// Initializing curl
$ch = curl_init( $json_url );
	 
// Configuring curl options
$options = array(
	CURLOPT_POST => true,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
	CURLOPT_POSTFIELDS => $json_str
);
	 
	 
// Setting curl options
curl_setopt_array( $ch, $options );
	
//execute
$result =  curl_exec($ch);


echo $result;
?>