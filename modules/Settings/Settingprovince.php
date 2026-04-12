<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('include/utils/utils.php');
require_once('Smarty_setup.php');

global $app_strings;
global $mod_strings;
global $currentModule;
global $current_language;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$recprefix = vtlib_purify($_REQUEST['recprefix']);
$recnumber = vtlib_purify($_REQUEST['recnumber']);

/*$module_array=getCRMSupportedModules();
if(count($module_array) <= 0) {
	echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
	echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>

		<table border='0' cellpadding='5' cellspacing='0' width='98%'>
		<tbody><tr>
		<td rowspan='2' width='11%'><img src= " . aicrm_imageurl('denied.gif', $theme) . " ></td>
		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'>
			<span class='genHeaderSmall'>$app_strings[LBL_NO_MODULES_TO_SELECT]</span></td>
		</tr>
		</tbody></table>
		</div>";
	echo "</td></tr></table>";
	exit;	
}

$modulesList = array_keys($module_array);*/

$selectedModule = vtlib_purify($_REQUEST['selmodule']);
if($selectedModule == '') $selectedModule = $modulesList[0];

$mode = $_REQUEST['mode'];

include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$smarty = new vtigerCRM_Smarty;

$sql_region = 'select * from tbm_regionid order by ID';
$region = $myLibrary_mysqli->select($sql_region);
$smarty->assign("REGION",$region);

$sql_province = 'select * from tbm_provid order by PROV_NAME asc';
$province = $myLibrary_mysqli->select($sql_province);
$smarty->assign("PROVINCE",$province);


$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULES",$module_array);
$smarty->assign("SELMODULE",$selectedModule);
$smarty->assign("MODNUM_PREFIX",$recprefix);
$smarty->assign("MODNUM", $recnumber);
$smarty->assign("STATUSMSG", $STATUSMSG);

$smarty->assign("MOD",return_module_language($current_language,'Settings'));

$smarty->display('Settings/Settingprovince.tpl');
?>