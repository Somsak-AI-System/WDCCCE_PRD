<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");
include("library/dbconfig.php");
require_once("library/generate_MYSQLi.php");
require_once("library/general.php");
global $generate,$current_user,$root_directory,$root_directory,$site_URL,$dbconfig;
$generate = new generate($dbconfig ,"DB");

if(!isset($_GET['announcementID']) || $_GET['announcementID']==''){
    exit();
}

$announcementID = $_GET['announcementID'];

$sql = "SELECT * FROM aicrm_announcement WHERE announcementid=".$announcementID;
$query = $generate->query($sql);
$arr = $generate->fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Announcement</title>
</head>

<body>
    <?php echo htmlspecialchars_decode(@$arr['detail']); ?>
</body>
</html>

