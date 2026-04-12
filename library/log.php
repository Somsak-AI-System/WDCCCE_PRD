<?php

class log{
	var $_logname = "";

	public function __construct(){
		$this->_logname = "alllog";
	}


	public function write_log($message, $logfile='') {

	// Get time of request
		if( ($time = $_SERVER['REQUEST_TIME']) == '') {
			$time = time();
		}

		$time = date("Y-m-d H:i:s");

		// Get IP address
		if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
			$remote_addr = "REMOTE_ADDR_UNKNOWN";
		}

		// Get requested script
		if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
			$request_uri = "REQUEST_URI_UNKNOWN";
		}

		// Format the date and time
		$date = date("Y-m-d H:i:s", $time);

		$date =  date("Y-m-d H:i:s");

		// Append to the log file
		$strFileName = $this->_logname."_".date("Ymd").".txt";
		//$objFopen = fopen($strFileName, 'a');

		if($fd = fopen($strFileName, "a")) {
			$result = fputcsv($fd, array($date, $remote_addr, $request_uri, $message));
			fclose($fd);

			if($result > 0)
				return array(status => true);
			else
				return array(status => false, message => 'Unable to write to '.$logfile.'!');
		}
		else {
			return array(status => false, message => 'Unable to open log '.$logfile.'!');
		}
	}
}



?>