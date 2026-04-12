<?php
class myLibrary_odbc{
	public  $_dbconfig;
	public function __construct(){

	}

	public function select($pquery="")
	{
		//echo "<pre>";print_r($this->_dbconfig);echo "</pre>";
		$odbc = odbc_connect($this->_dbconfig['host'],$this->_dbconfig['username'],$this->_dbconfig['password'],SQL_CUR_USE_ODBC);
		
		//echo "<pre>";print_r($odbc);echo "</pre>";
		if ($odbc->connect_error) {
    		die('Error : ('. $odbc->connect_errno .') '. $odbc->connect_error);
		}	
		$results = odbc_exec($odbc,$pquery);
		
		$data = array();
		
		if(!empty($results)){
			$data = preparedata($results);
		}	
		
		if(!empty($data)){
			return $data;
		}else{
			return  $data;
		}
	}
	
}

function preparedata($results){//generate all data
	$i = 0;
	$j = 0;
	$resultRow = array();
	//$a_data = odbc_fetch_array($results);
	//echo "<pre>";print_r($a_data);echo "</pre>";exit();
	//exit();
	while(odbc_fetch_row($results))
	{
		//echo odbc_result($results, "DEBnameT")."<br>";
		//Build tempory
		for ($j = 1; $j <= odbc_num_fields($results); $j++)
		{
			$field_name = odbc_field_name($results, $j);
			//echo $field_name." ";
			$data = odbc_result($results, $field_name);
			
			//echo $field_name." ".$data."<br>";
			//
			//$data = iconv("CP1252", "utf-8",$data);
			//$value = iconv('UCS-2LE', 'UTF-8', $data);
			$ar[$field_name] = iconv(   "tis-620", "utf-8", $data );
			//$ar[$field_name] =   $data ;
					
		}
	
		$resultRow[$i] = $ar;
		$i++;
	}
		
		//	echo "<pre>";print_r($resultRow);echo "</pre>";
	 return $resultRow;
}
?>
