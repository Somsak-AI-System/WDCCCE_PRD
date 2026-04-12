<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("../config.inc.php");
include("../library/dbconfig.php");
global $dbconfig;

$conn = mysql_connect($dbconfig['db_server'], $dbconfig['db_username'], $dbconfig['db_password']);
$connDB = mysql_select_db($dbconfig['db_name']);
mysql_query('SET NAMES UTF8');

$promotionID = $_REQUEST['promotionID'];

$sql = "SELECT * FROM aicrm_promotion INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.promotionid = aicrm_promotio.promotionid WHERE aicrm_promotion.promotionid =".$promotionID;
$query = mysql_query($sql);
$arr = mysql_fetch_assoc($query);

print_r($arr);
