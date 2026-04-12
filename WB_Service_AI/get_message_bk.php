<?php // callback.php

// $access_token = 'rV062cOQi/wf14o/R+SNgpHb3DQWawt5j4gLz8HTbAWcMTw1rwUew6Qol1yNxbhBDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yx2xFZEwamkTbahQIgfJBCmoW8fA4IuuQSVx/eZabxyuFGUYhWQfeY8sLGRXgo3xvw=';

$access_token = 'ub5SR9vzRhqbh6Gvf5vaZ7RlZoBfcjvvi7+CW2SfC0zh6RJL35FpCEyGCHd0ww3qDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yw+s6TMw/DAW9uiFkeOu+AMqbzy0lf1oLRvJ1OkqFQTr1GUYhWQfeY8sLGRXgo3xvw=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
file_put_contents("textline.txt", $content);
$events = json_decode($content, true);

// $events = array(
//     'events' => Array
//         (
//             '0' => Array('type' => 'message',
//                     	   'replyToken' => 'fd1adfda3af34ab081f6cf73ab12624c',
//                            'source' => Array('userId' => 'U0835aa50283c11096e0b2ecc0d2167d9','type' => 'user'),
//                     	   'timestamp' => '1586337335984',
//                     	   'mode' => 'active',
//                    		   'message' => Array('type' => 'text','id' => '11748003185072','text' => 'สวัสดีจ้า')
//                 			)
//         ),

//     'destination' => 'Uf7337dc58eaa075b8b87da2b2541fdfb'
// );



// $FileName = "https://moaioc.moai-crm.com/WB_Service_AI/log/text.txt";
// // $FileName = "/home/mobit/domains/mobitsynergy.com/public_html/2015/api/log/text.txt";
// $FileHandle = fopen($FileName, 'a+') or die("can't open file");
// $dataevent = "\r\n";
// foreach ($events as $key => $value) {
// 	$dataevent .= "".$events[$key]; 
// }

// fwrite($FileHandle,"Save Create ".date('d-m-Y H:i:s')."Event : ".print_r($events, true)." \r\n");

// fclose($FileHandle);


if (!is_null($events['events'])) {
// if (!is_null($events)) {

// Make a POST Request to Messaging API to reply to sender
			$url = 'https://moaioc.moai-crm.com/WB_Service_AI/linemessage/getmessage';
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

