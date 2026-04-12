<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
global $php_max_execution_time;
//set_time_limit($php_max_execution_time);
ini_set('memory_limit', '-1');
set_time_limit(0);

RecalculateSharingRules();

header("Location: index.php?action=OrgSharingDetailView&parenttab=Settings&module=Settings");


?>
