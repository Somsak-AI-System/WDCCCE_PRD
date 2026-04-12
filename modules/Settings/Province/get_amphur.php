<?
session_start();
include("../../../config.inc.php");
include_once("../../../library/dbconfig.php");
include_once("../../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$provinceid = '';
if(isset($_REQUEST["province_idamphur"])){
	$provinceid = $_REQUEST["province_idamphur"];
}else if(isset($_REQUEST["provinceidpostalcoder"])){
	$provinceid = $_REQUEST["provinceidpostalcoder"];
}

$sql="select * from tbm_amphur where 1 and province_id='".$provinceid."' order by amphur_name";
$data = $myLibrary_mysqli->select($sql);

echo json_encode($data);
?>