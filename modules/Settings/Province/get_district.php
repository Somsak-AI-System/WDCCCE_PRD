<?
session_start();
include("../../../config.inc.php");
include_once("../../../library/dbconfig.php");
include_once("../../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql="select * from tbm_district where 1 and province_id='".$_REQUEST['provinceidpostalcoder']."' and amphur_id = '".$_REQUEST['amphuridpostalcoder']."' order by district_name";
$data = $myLibrary_mysqli->select($sql);

echo json_encode($data);

?>