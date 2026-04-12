<?
session_start();
include("../../../config.inc.php");
include_once("../../../library/dbconfig.php");
include_once("../../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$district = array();
$amphur_name = $_REQUEST['amphur_name'];
$province_id = $_REQUEST['province_id'];
$province_name = $_REQUEST['province_name'];

$sql="select tbm_amphur.* 
	from tbm_amphur 
	inner join tbm_provid on tbm_provid.province_id=tbm_amphur.province_id 
	where tbm_amphur.amphur_name ='".$amphur_name."' and tbm_amphur.province_id = '".$province_id."' ";
$district = $myLibrary_mysqli->select($sql);

$data['status'] = true;

if (!empty($district)) {
  $data['status'] = false;
}

if ($data['status'] == true) {
 	$sql_int = "insert into tbm_amphur (amphur_name,province_id,province_name,status) value ('".$amphur_name."','".$province_id."','".$province_name."','0');";
  	$myLibrary_mysqli->Query($sql_int);
} 

echo json_encode($data);

?>