<?php
require_once ("config.inc.php");
date_default_timezone_set("Asia/Bangkok");
global $path,$url_path;

ini_set('memory_limit', '4024M');

$path = $root_directory;

require_once ($path."library/dbconfig.php");
require_once ($path."library/genarate.inc.php");
$genarate = new genarate($dbconfig ,"DB");

switch($_REQUEST['type']){
    case '1':
        $date = date('Y-m-d');
        $time = date('H:i');
    break;
    case '2':
        $date = date('Y-m-d', strtotime($_REQUEST['date']));
        $time = $_REQUEST['time'];
    break;
}

switch($_REQUEST['Module']){
    case 'Surveysmartemail':
        $sql = "UPDATE aicrm_surveysmartemail SET email_start_date='".$date."', email_start_time='".$time."' WHERE surveysmartemailid='".$_REQUEST['crmID']."'";
        $genarate->process($sql,"all");
    break;
    case 'Smartquestionnaire':
        $sql = "UPDATE aicrm_smartquestionnaire SET email_start_date='".$date."', email_start_time='".$time."' WHERE smartquestionnaireid='".$_REQUEST['crmID']."'";
        $genarate->process($sql,"all");
    break;
}


echo json_encode(array('status'=>true));
?>