<?php
$PATH     = explode('/' , $_SERVER['PHP_SELF']);
$ROOT_WEB = $_SERVER['DOCUMENT_ROOT']."/".$PATH[1];

require_once($ROOT_WEB."/config.inc.php");
//require_once("../../../config.inc.php");

if(isset($dbconfig)){

	mysql_connect($dbconfig['db_server'], $dbconfig['db_username'], $dbconfig['db_password']) or die("Cannot connect the Server");
	mysql_select_db($dbconfig['db_name']) or die ("Cannot select database");
	
	mysql_query("SET character_set_results=utf8");
	mysql_query("SET character_set_client='utf8'");
	mysql_query("SET character_set_connection='utf8'");
	mysql_query("SET NAMES UTF8");

	class dq { 
		
		private $result;
		private $rows;
		
		public function query($q) {
			$q = mysql_query($q);

			return $q;
		}
		
		public function fetch($q) {
			if (mysql_query($q)) {

				$query = mysql_query($q);

				if (mysql_num_rows($query) > 0) {

					while ($f = mysql_fetch_array($query)) {
						$rows[] = $f;
					}
				} else {
					//$rows = array("empty");
					return null;
				}
			} else {
				//$rows = array("empty");
				return null;
			}
				
			return $rows;
		}

		public function mysql_insert_id() {
			
			return mysql_insert_id();
		}
	}
}
?>