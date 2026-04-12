<?
session_start();
include("../../config.inc.php");
include_once("../../library/dbconfig.php");
include_once("../../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$data = array();
$province = array();

$condition = $_REQUEST['condition'];
$range = $_REQUEST['range'];
$allow = $_REQUEST['allow'];

$data['status'] = true;

$sql_int = "Update aicrm_config_checkin set conditions= '".$condition."' ,ranges = '".$range."',allow = '".$allow."' where id = 1;";
$myLibrary_mysqli->Query($sql_int);

echo json_encode($data);

?>