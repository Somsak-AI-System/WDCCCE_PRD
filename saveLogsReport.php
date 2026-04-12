<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");

include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");

global $generate;
$generate = new generate($dbconfig ,"DB");

$data_parram = "\nReport Name :: ".$_REQUEST['report_name']."\nBirt Report :: ".$_REQUEST['url']."\nStore :: ".$_REQUEST['store']."\nUser :: ".$_REQUEST['user_open_report']."\n____________________________________________________________________";
$file_name="Logs_".date('dmY').".txt";
$FileName = "logs/Report/".$file_name;
$FileHandle = fopen($FileName, 'a+') or die("can't open file");
fwrite($FileHandle, "Time ".date('Y-m-d H:i:s')." ".print_r($data_parram, true)."\r\n");
fclose($FileHandle);

?>