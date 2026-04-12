<?php
// add the absolute path to the dir that is returned by the del link...
// this is a windows server example
$file = "D:/AppServ/www/".$_REQUEST['src'];
//echo $file;exit;
if(is_file("$file")) {
	unlink("$file");
}
$deleteGoTo = "frmresourceslist.html?refresh";
header(sprintf("Location: %s", $deleteGoTo));
?>
