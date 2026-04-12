<?
session_start();
include("../../../config.inc.php");
include_once("../../../library/dbconfig.php");
include_once("../../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$data = array();
$province = array();

$sectorid = $_REQUEST['sectorid'];
$provincename = $_REQUEST['provincename'];

$sql="select PROV_NAME from tbm_provid where PROV_NAME ='".$provincename."'";
$province = $myLibrary_mysqli->select($sql);

$data['status'] = true;

if (!empty($province)) {
  $data['status'] = false;
}

if ($data['status'] == true) {
 	$sql_int = "insert into tbm_provid (REGIONID,PROV_NAME,status) value ('".$sectorid."','".$provincename."','0');";
  	$myLibrary_mysqli->Query($sql_int);
} 

echo json_encode($data);

?>