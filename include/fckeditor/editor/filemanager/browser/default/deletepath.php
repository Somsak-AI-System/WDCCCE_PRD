<?php
// add the absolute path to the dir that is returned by the del link...
// this is a windows server example
$file = "D:/AppServ/www/UserFiles/Image".$_REQUEST['src'];
//echo $file;exit;
if(is_dir("$file")) {
	rmdir("$file");
}
$deleteGoTo = "frmresourceslist.html?refresh";
header(sprintf("Location: %s", $deleteGoTo));
?>
