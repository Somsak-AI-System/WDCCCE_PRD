<?php
//error_reporting(E_ALL  & ~E_NOTICE  & ~E_WARNING );
//ini_set('display_errors', 1);
include_once("xml.php");
include_once("log.php");
class myLibrary_mysqli{
	public  $_dbconfig;
	public function __construct(){

		$this->_logfile = "../logs/Call_Store";
		$this->log = new log();
		$this->log->_logname = $this->_logfile;
	}

	public function Query($pquery="")
	{
		
		$mysqli = new mysqli($this->_dbconfig['db_server'],$this->_dbconfig['DB']['username'],$this->_dbconfig['DB']['password'],$this->_dbconfig['DB']['dbname']);
		
		if ($mysqli->connect_error) {
    		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
		}
		//$this->log->write_log("Store => ".$pquery);
		$results = $mysqli->query($pquery);

		$lastid = mysqli_insert_id($mysqli);
		mysqli_close($mysqli);
		//Return Last Id
		return $lastid;
		//$this->log->write_log("Store => ".@$results);
	}
	
	public function select($pquery="")
	{
		$mysqli = new mysqli($this->_dbconfig['db_server'],$this->_dbconfig['DB']['username'],$this->_dbconfig['DB']['password'],$this->_dbconfig['DB']['dbname']);
		
		if ($mysqli->connect_error) {
    		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
		}	
		$results = $mysqli->query($pquery);
		//$this->log->write_log("Store_Select => ".$pquery);
		


		$data = array();
		
		if(!empty($results)){
			$data = preparedata($results);
		}
		mysqli_close($mysqli);

		//$this->log->write_log("Data => ".$data);
		if(!empty($data)){
			return $data;
		}else{
			return  $data;
		}
	}
	
}

	function preparedata($results){//generate all data
				$numFields = mysqli_num_fields($results);
								
				$index = 0;
				$j = 0;
				
			while ($column = $results->fetch_field()) {
					if (!empty($column->table)) {
						$map[$index++] = array($column->table, $column->name);
					} else {
						$map[$index++] = array(0, $column->name);
					}
			 }

			$k=0;	
			$resultRow = array();						
			
			while($row = mysqli_fetch_row($results)){			
					$i = 0;				
					foreach ($row as $index => $field) {
			
						list($table, $column) = $map[$index];
						if($row[$index] == NULL){
							$resultRow[$k][$column] = '';
						}else{
							$resultRow[$k][$column] = $row[$index];
						}
						
						$i++;
					}
			
					$k++;		
				}		
			 return $resultRow;
}

function date_compare($datebegin,$dateend)
{
	$diff = abs(strtotime($dateend) - strtotime($datebegin));
	$a_data["years"] = floor($diff / (365*60*60*24));
	$a_data["months"] = floor(($diff - $a_data["years"] * 365*60*60*24) / (30*60*60*24));
	$a_data["days"] = floor(($diff - $a_data["years"] * 365*60*60*24 - $a_data["months"] *30*60*60*24)/ (60*60*24));
	$a_data["day"] = floor($diff /  (60*60*24));
	return $a_data;
}
function get_status_color($day)
{
	if($day >0 && $day <= 10 ){
		//blue;
		$a_return["icon"] = "asset/images/icons/bullet_blue.png";
		$a_return["title"] = "1-10 days";
		$a_return["class"] = "info";
	}else if($day >10 && $day <= 20){
		//green;
		$a_return["icon"] = "asset/images/icons/bullet_green.png";
		$a_return["title"] = "11-20 days";
		$a_return["class"] = "success";
	}else if($day >20 && $day <= 30){
		//yellow ;
		$a_return["icon"] = "asset/images/icons/bullet_orange.png";
		$a_return["title"] = "21-30 days";
		$a_return["class"] = "warning";
	}else if($day >30){
		//red ;
		$a_return["icon"] = "asset/images/icons/bullet_red.png";
		$a_return["title"] = ">31 days";
		$a_return["class"] = "danger";
	}else{
		$a_return["icon"] = "asset/images/icons/bullet_blue.png";
		$a_return["title"] = "1-10 days";
		$a_return["class"] = "info";
	}
	$a_return["day"] = $day;
	return $a_return;
}

function get_status_color_km($status)
{
	if($status=="Hot" ){
		//yellow ;
		$a_return["icon"] = "asset/images/icons/bullet_orange.png";
		$a_return["title"] = "Hot";
		$a_return["class"] = "warning";
	}else if($status=="New"){
		$a_return["icon"] = "asset/images/icons/bullet_blue.png";
		$a_return["title"] = "New";
		$a_return["class"] = "info";
	}else{
		$a_return["icon"] = "";
		$a_return["title"] = "";
		$a_return["class"] = "";
	}
	$a_return["day"] = $day;
	return $a_return;
}

function get_status_service_request($datebegin,$dateend){
	$a_return = date_compare($datebegin,$dateend);
	//echo $a_return["day"]." \r ";
	$a_data = get_status_color($a_return["day"]);
	return $a_data;
}


function get_status_service_request_pm( $date_request, $date_work , $closejob){

	$date_end = ($closejob==1)?$date_work:date("Y-m-d");
	$a_return = date_compare($date_request,$dateend);
	//echo $a_return["day"]." \r ";
	$a_data = get_status_color($a_return["day"]);
	return $a_data;
}
	function date_set($date=null ,$format="Y-m-d")
	{
		if ($date=="") return $date;
		$a_date = explode('-',$date);
		if(is_array($a_date) )
		{
			$yyyy_mm_dd = $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0];
			return  date($format, strtotime($yyyy_mm_dd));
		}
		else
		{
			return date($format, strtotime($date));
		}

	}

	function alert()
	{
		$arg_list = func_get_args();
		foreach($arg_list as $k => $v)
		{
			print '<pre class="alert">';
			print_r($v);
			print '</pre>';
		}
	}
?>
