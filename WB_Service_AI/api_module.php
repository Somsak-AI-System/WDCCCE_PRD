<?php
	
	require_once("library/function.php");
	
	
	header('Content-Type:application/json; charset=UTF-8');
	$request_body = file_get_contents('php://input');
	$request_body = str_replace("'","",$request_body);
	//echo $request_body ;  exit;
	//Load Json Data to Object
	
	//$dataJson  = @json_decode($request_body);//แตก Json ที่ Client ส่งมาเพื่อที่จะเอามา Map กับ Database
	//$dataJson = json_decode($request_body,true);//แตก Json ที่ Client ส่งมาเพื่อที่จะเอามา Map กับ Database
	$_return = array(
			'Type' => "S",
			'Message' => "",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
					//'Crmid' => null,
					//'DocNo' => null
			),
	);
		
	$a_return["data"] = array();
	
	
	$dataJson = @json_decode($request_body, true);
	
	$data= Get_user($dataJson['id'] , $dataJson['params']['username'] , $dataJson['params']['password']);
	//print_r($data); exit;
	if($data['result']['user_id']!=""){
		$data= get_module($dataJson['id'],$data['result']['user_id'],$dataJson['params']['module'],$dataJson['method'],$dataJson['params']['crmid']);
	}
	
	//$data = array();
	array_push($a_return["data"] , $data);
	
	
	$response_data = array_merge($_return,$a_return);
			
	if (!empty($response_data['data'][0]['result']) ) {
			echo json_encode($response_data);
	} else {
				$_return = array(
					'Type' => "E",
					'Message' => "",
					'cache_time' => date("Y-m-d H:i:s"),
					'total' => "1",
					'offset' => "0",
					'limit' => "1",
					'data' => array(
						
					),
			);
	  		echo json_encode($_return);
	}
	//exit;
	/*echo "5555<br>";
	echo "<pre>";
	print_r($response_data);
	echo "</pre>";
	exit;*/
	
	//echo json_encode($data);
?>