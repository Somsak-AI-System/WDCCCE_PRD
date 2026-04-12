<?php
header('Content-Type: text/html; charset=tis-620');
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$mode = $_REQUEST["mode"];
$year = $_REQUEST["year"];

	if($mode == "get"){
		$sql = "select * , (january+february+march+april+may+june+july+august+september+october+november+december) as total
				from tbm_workingday  
				where year = '".$year."'; ";
		
		$a_data = $myLibrary_mysqli->select($sql);
		if(empty($a_data)){
			$data['type'] = 'E';
			$data['data'] = array();
		}else{
			$data['type'] = 'S';
			$data['data'] = $a_data;
		}
		echo json_encode(@$data);
	}else if($mode == "save"){

		$sql = "Insert into tbm_workingday (year,january,february,march,april,may,june,july,august,september,october,november,december) VALUES ('".$year."','".$_REQUEST["january"]."','".$_REQUEST["february"]."','".$_REQUEST["march"]."','".$_REQUEST["april"]."','".$_REQUEST["may"]."','".$_REQUEST["june"]."','".$_REQUEST["july"]."','".$_REQUEST["august"]."','".$_REQUEST["september"]."','".$_REQUEST["october"]."','".$_REQUEST["november"]."','".$_REQUEST["december"]."') 
				ON DUPLICATE KEY UPDATE january='".$_REQUEST["january"]."' ,february='".$_REQUEST["february"]."' ,march='".$_REQUEST["march"]."' ,april='".$_REQUEST["april"]."' ,may='".$_REQUEST["may"]."' ,june='".$_REQUEST["june"]."' ,july='".$_REQUEST["july"]."' ,august='".$_REQUEST["august"]."' ,september='".$_REQUEST["september"]."' ,october='".$_REQUEST["october"]."' ,november='".$_REQUEST["november"]."' ,december='".$_REQUEST["december"]."' ; "; 
		//echo $sql; exit;
		$myLibrary_mysqli->Query($sql);

		$sql_total = "select (january+february+march+april+may+june+july+august+september+october+november+december) as total
				from tbm_workingday  
				where year = '".$year."'; ";
		
		$a_data = $myLibrary_mysqli->select($sql_total);

		if(empty($a_data)){
			$data['type'] = 'E';
			$data['data'] = array();
		}else{
			$data['type'] = 'S';
			$data['data'] = $a_data;
		}
		echo json_encode(@$data);
	}


