<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
********************************************************************************/
global $php_max_execution_time;
set_time_limit($php_max_execution_time);

global $root_directory,$current_user;

include('library/php-export-data.class.php');
include('library/myFunction_Excel.php');

$reportid = vtlib_purify($_REQUEST["record"]);
global $root_directory;
$path = $root_directory."export/report/";
$a_data= json_decode(@file_get_contents($path."analytic_".$reportid."_".$current_user->id.'.json'),true);
if(!empty($a_data)){
	$report = ExportDataLarge($a_data, $filename="Reports.xls");
}else{
	echo "<script type='text/javascript'>alert('No Data');</script>";
	exit();
}

?>
