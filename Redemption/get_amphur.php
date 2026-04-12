<?
session_start();
include("../config.inc.php");
require_once("../library/dbconfig.php");
require_once("../library/myFunction.php");
require_once("../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql="select * from tbm_amphur where 1 and province_id='".$_REQUEST["tbm_provinceid"]."' order by amphur_code";
$data = $myLibrary_mysqli->select($sql);

$content='<option value="" selected="selected"></option>';

foreach ($data as $key => $value) {
	$content.='<option value="'.$value["amphur_id"].'" >'.$value["amphur_name"].'</option>';	
}

echo $content;
?>