<?
session_start();
include("../../../config.inc.php");
include_once("../../../library/dbconfig.php");
include_once("../../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$data = array();
$districtid = $_REQUEST['district_id'];
$postcode = $_REQUEST['post_code'];

$d_postcode = array();

$sql="select * from tbm_postcode where post_code ='".$postcode."' and district_id = '".$districtid."';";
$d_postcode = $myLibrary_mysqli->select($sql);

$data['status'] = true;
if(!empty($d_postcode)) {
  	$data['status'] = false;
}

if($data['status'] == true) {
	$sql_int = "insert into tbm_postcode (district_id,post_code) value ('".$districtid."','".$postcode."');";
  	$myLibrary_mysqli->Query($sql_int);
}

echo json_encode($data); 

?>