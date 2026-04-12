<?php



// $dbconfig['db_server'] = '127.0.0.1';
// $dbconfig['db_port'] = ':3306';
// $dbconfig['db_username'] = 'admin_moaioc';
// $dbconfig['db_password'] = 'EKjJoV9qfaLKaDNk';
// $dbconfig['db_name'] = 'db_moaioc';
// $dbconfig['db_type'] = 'mysql';
// $dbconfig['db_status'] = 'true';

$Setup_Server = '127.0.0.1:3306';
$Setup_User = 'admin_moaioc';
$Setup_Pwd = 'EKjJoV9qfaLKaDNk';
$Setup_Database = 'db_moaioc';
mysql_connect($Setup_Server,$Setup_User,$Setup_Pwd);
mysql_query("use $Setup_Database");
mysql_query("SET NAMES UTF8");

?>