<?
session_start();
include("../config.inc.php");
require_once("../library/dbconfig.php");
require_once("../library/myFunction.php");
require_once("../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql="select * from tbm_provid where 1 order by PROVID";
$data = $myLibrary_mysqli->select($sql);

$content='<option value="" selected="selected"></option>';

foreach ($data as $key => $value) {
	$content.='<option value="'.$value["province_id"].'" >'.$value["PROV_NAME"].'</option>';	
}

echo $content;
?>