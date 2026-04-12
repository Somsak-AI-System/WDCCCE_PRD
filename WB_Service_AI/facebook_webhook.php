<?php 

	$my_verify_token = "MOAIOC_verify_token";

	$challenge = $_GET['hub_challenge'];
	$verify_token = $_GET['hub_verify_token'];

	if($my_verify_token === $verify_token){
		echo $challenge;
		exit;
	}
	//$page_access_token = "EAAB20aPU3ckBAIEgg2ttReWpNGKOUd75ArozgQpBkPMUKornmJZCCySDy2yRhy7eFXAn9qV84DAKOtF1GtJdGZBYlaJe8Ow9irp9US06TMM0FnYfexS0vbaf1ZCBMvujcPOT1F49RdxyZAocYNRZCOPzZC7wdLsuplFzzJTZAS4mZAnwad07XQVK";
	$page_access_token = "EAAB20aPU3ckBAJhJeP3RFUGWBbIfQHuhnHf4eiWifb2JM2WpHawiiEsaliKERrhlitmXpTAdcHdRc8I9VO3LZBio98PhgZCwW1dhsNuiY7boap9KZADEbIYYLXCbzWviBTEET0o90qhvQUTheQBEQotwN7fD8wLAxdIZAjWtabnNM8qXKlfLwmNEtnBqn5YZD";
	
	$response = file_get_contents("php://input");
	file_put_contents("textfacebook.txt", $response);
	$events = json_decode($response, true);
	$messaging = $events['entry'][0]['messaging'][0];
	//echo "OK"; exit;

	if (!is_null($messaging)) {
// Make a POST Request to Messaging API to reply to sender
			$url = 'https://moaioc.moai-crm.com/WB_Service_AI/facebookmessage/getfbmessage';
			$data = [
				'AI-API-KEY' => '1234',
				'events' => $events,
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json');

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($ch);
			curl_close($ch);

			// print_r($result);
}


// $datatest =  json_encode($events);
// print_r($datatest);
echo "OK";




	
?>