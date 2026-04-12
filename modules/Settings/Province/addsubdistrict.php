<?
session_start();
include("../../../config.inc.php");
include_once("../../../library/dbconfig.php");
include_once("../../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$data = array();
$provinceid = $_REQUEST['province_idamphur'];
$province_name = $_REQUEST['provinceamphur'];
$amphurid = $_REQUEST['amphur_id'];
$amphurname = $_REQUEST['amphur_name'];
$districtname = $_REQUEST['district_name'];

$subdistrict = array();

$sql="select * from tbm_district where district_name ='".$districtname."' and amphur_id = '".$amphurid."' and  province_id = '".$provinceid."';";
$subdistrict = $myLibrary_mysqli->select($sql);

$data['status'] = true;
if(!empty($subdistrict)) {
  	$data['status'] = false;
}

if($data['status'] == true) {
	$sql_int = "insert into tbm_district (district_name,amphur_id,amphur_name,province_id,province_name,status) value ('".$districtname."','".$amphurid."','".$amphurname."','".$provinceid."','".$province_name."','0');";
  	$myLibrary_mysqli->Query($sql_int);
}

echo json_encode($data); 

?>