<?php
	//include ไฟล์ config.ini
	require_once("../config.inc.php");
	//echo "555".$dbconfig['db_server'];
	$dbconfig["DB"] = array ( 
				  		"dbname" => $dbconfig['db_name'], 
						"host" => $dbconfig['db_server'], 
						"username" => $dbconfig['db_username'], 
						"password" => $dbconfig['db_password']
	);		
	
	//print_r($dbconfig["DB"]);	
?>