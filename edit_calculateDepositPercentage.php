<?php
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
include_once('config.php');
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

// global $current_user;
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$data = $_REQUEST['data'][0];
$crmid = $data['crmid'];
$deposit = $data['deposit'];


if ($crmid != '' && $deposit != '') {
    $query = "UPDATE aicrm_quotes SET deposit = '".$deposit."' WHERE quoteid = '".$crmid."'";
    $myLibrary_mysqli->Query($query);
}

$result_data['status'] = true;
echo json_encode($result_data);