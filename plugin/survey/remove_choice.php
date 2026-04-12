<?php
require_once ("../../config.inc.php");
$path = $root_directory;

require_once ($path."library/dbconfig.php");
require_once ($path."library/genarate.inc.php");
$genarate = new genarate($dbconfig ,"DB");

$choiceid = $_POST['choiceid'];
$sql = "DELETE FROM survey_choice WHERE choiceid='".$choiceid."'";
$genarate->process($sql,"all");

$sql = "DELETE FROM survey_choice_detail WHERE choiceid='".$choiceid."'";
$genarate->process($sql,"all");

echo json_encode(array('status'=>true));