<?php
session_start();
include("C:/AppServ/www/glapcrmuat/config.inc.php");
include_once("C:/AppServ/www/glapcrmuat/library/dbconfig.php");
include_once("C:/AppServ/www/glapcrmuat/library/myFunction.php");
include_once("C:/AppServ/www/glapcrmuat/library/myLibrary_mysqli.php");
include_once("C:/AppServ/www/glapcrmuat/phpmailer/class.phpmailer.php");
$myLibrary_mysqli = new myLibrary_mysqli($dbconfig,'DB');
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql="
insert into ai_check_user_login_log(chk_start_time, chk_end_time, count)
SELECT NOW( ) AS chk_start_time, (NOW( ) + INTERVAL 1 MINUTE) AS chk_end_time ,count(*) as count
FROM  ai_check_user_login 
WHERE 1 
AND  status =0
AND  end_time > ( NOW( ) + INTERVAL 1 MINUTE ) 
";
$log_id = $myLibrary_mysqli->Query($sql);

$sql="select chk_start_time,chk_end_time from ai_check_user_login_log where id='".$log_id."'";
$data = $myLibrary_mysqli->select($sql);

$sql="
SELECT  ".$log_id.",NOW( ) AS chk_start_time, (NOW( ) + INTERVAL 1 MINUTE) AS chk_end_time ,  user_id ,  username, use_date ,  start_time ,  end_time ,  sysytem_name 
FROM  ai_check_user_login 
WHERE 1 
AND  status =0
AND  end_time > '".$data[0]["chk_end_time"]."'
INTO OUTFILE 'D:/crm_log_login/".$log_id."_".str_replace(" ","_",str_replace("-","",str_replace(":","",$data[0]["chk_start_time"])))."__".str_replace(" ","_",str_replace("-","",str_replace(":","",$data[0]["chk_end_time"]))).".txt'; 
";
$myLibrary_mysqli->Query($sql);
?>