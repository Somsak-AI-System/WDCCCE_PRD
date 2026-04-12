<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ******************************************************************************/
require_once("Smarty_setup.php");
require_once("include/utils/utils.php");
require_once("include/Zend/Json.php");

function vtGetModules($adb) {
	$modules = com_vtGetModules($adb);
	return $modules;
}

function vtEditExpressions($adb, $appStrings, $current_language, $theme, $formodule='') {
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	$smarty = new vtigerCRM_Smarty();
	$smarty->assign('APP', $appStrings);

	$mod =  array_merge(
	return_module_language($current_language,'Account'),
	return_module_language($current_language,'Settings'));

	$jsStrings = array(
		'NEED_TO_ADD_A'=>$mod['NEED_TO_ADD_A'],
		'CUSTOM_FIELD' =>$mod['LBL_CUSTOM_FIELD'],
		'LBL_USE_FUNCTION_DASHDASH'=>$mod['LBL_USE_FUNCTION_DASHDASH'],
		'LBL_USE_FIELD_VALUE_DASHDASH'=>$mod['LBL_USE_FIELD_VALUE_DASHDASH'],
		'LBL_DELETE_EXPRESSION_CONFIRM'=>$mod['LBL_DELETE_EXPRESSION_CONFIRM']
	);
	$smarty->assign("JS_STRINGS", Zend_Json::encode($jsStrings));

	$smarty->assign("MOD", $mod);
	$smarty->assign("THEME",$theme);
	$smarty->assign("IMAGE_PATH",$image_path);
	$smarty->assign("MODULE_NAME", 'FieldFormulas');
	$smarty->assign("PAGE_NAME", 'LBL_FIELDFORMULAS');
	$smarty->assign("PAGE_TITLE", 'LBL_FIELDFORMULAS');
	$smarty->assign("PAGE_DESC", 'LBL_FIELDFORMULAS_DESCRIPTION');
	$smarty->assign("FORMODULE", $formodule);

	$smarty->display(vtlib_getModuleTemplate('Calendar', 'UploadSetting.tpl'));
}

$modules = vtGetModules($adb);
vtEditExpressions($adb, $app_strings, $current_language, $theme, $_REQUEST['formodule']);

?>