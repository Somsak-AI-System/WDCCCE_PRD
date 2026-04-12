<?
session_start();
include("../config.inc.php");
require_once("../library/dbconfig.php");
require_once("../library/myFunction.php");
require_once("../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql="select * from tbm_postcode where 1 and district_id='".$_REQUEST["tbm_districtid"]."' order by post_code";
$data = $myLibrary_mysqli->select($sql);

$content='';
foreach ($data as $key => $value) {
	$content.='<option value="'.$value["post_code"].'" >'.$value["post_code"].'</option>';	
}


echo $content;
?>