<?php
	//include ��� config.ini
	$dbconfig["DB"] = array ( 
		"dbname" => $dbconfig['db_name'], 
		"host" => $dbconfig['db_server'], 
		"username" => $dbconfig['db_username'], 
		"password" => $dbconfig['db_password']
	);		
	
	$dbconfig["SQLSERVER"] = array (
			"dbname" =>"M5CM-PD-01",
			"host" => "Driver={SQL Server Native Client 11.0};Server=192.168.10.9;Database=M5CM-PD-01;",
			"username" => "aicrm",
			"password" => 'aicrm'
	);
?>