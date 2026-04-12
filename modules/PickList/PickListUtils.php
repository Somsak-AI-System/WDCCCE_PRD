<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

/**
 * this file will be used to store the functions to be used in the picklist module
 */

/**
 * Function to get picklist fields for the given module
 * @ param $fld_module
 * It gets the picklist details array for the given module in the given format
 * $fieldlist = Array(Array('fieldlabel'=>$fieldlabel,'generatedtype'=>$generatedtype,'columnname'=>$columnname,'fieldname'=>$fieldname,'value'=>picklistvalues))
 */
function getUserFldArray($fld_module,$roleid){
	global $adb, $log;
	$user_fld = Array();
	$tabid = getTabid($fld_module);

	$query="select aicrm_field.fieldlabel,aicrm_field.columnname,aicrm_field.fieldname, aicrm_field.uitype" .
			" FROM aicrm_field inner join aicrm_picklist on aicrm_field.fieldname = aicrm_picklist.name" .
			" where (displaytype=1 and aicrm_field.tabid=? and aicrm_field.uitype in ('15','55','33','16') " .
			" or (aicrm_field.tabid=? and fieldname='salutation' and fieldname !='vendortype')) " .
			" and aicrm_field.presence in (0,2) ORDER BY aicrm_picklist.picklistid ASC";
	//echo $query; exit;
	$result = $adb->pquery($query, array($tabid, $tabid));
	$noofrows = $adb->num_rows($result);

    if($noofrows > 0){
		$fieldlist = array();
    	for($i=0; $i<$noofrows; $i++){
			$user_fld = array();
			$fld_name = $adb->query_result($result,$i,"fieldname");

			$user_fld['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");
			$user_fld['generatedtype'] = $adb->query_result($result,$i,"generatedtype");
			$user_fld['columnname'] = $adb->query_result($result,$i,"columnname");
			$user_fld['fieldname'] = $adb->query_result($result,$i,"fieldname");
			$user_fld['uitype'] = $adb->query_result($result,$i,"uitype");
			$user_fld['value'] = getAssignedPicklistValues($user_fld['fieldname'], $roleid, $adb);
			$fieldlist[] = $user_fld;
		}
	}
	return $fieldlist;
}

/**
 * Function to get modules which has picklist values
 * It gets the picklist modules and return in an array in the following format
 * $modules = Array($tabid=>$tablabel,$tabid1=>$tablabel1,$tabid2=>$tablabel2,-------------,$tabidn=>$tablabeln)
 */
function getPickListModules(){
	global $adb;
	// vtlib customization: Ignore disabled modules.
	//show picklist user
	$query = 'select distinct aicrm_field.fieldname,aicrm_field.tabid,aicrm_tab.tablabel, aicrm_tab.name as tabname,uitype from aicrm_field inner join aicrm_tab on aicrm_tab.tabid=aicrm_field.tabid where uitype IN (15,33) /*and aicrm_field.tabid != 29*/ and aicrm_tab.presence != 1 and aicrm_field.presence in (0,2) order by aicrm_field.tabid ASC';
	// END
	$result = $adb->pquery($query, array());
	while($row = $adb->fetch_array($result)){
		$modules[$row['tablabel']] = $row['tabname'];
	}
	return $modules;
}

/**
 * this function returns all the roles present in the CRM so that they can be displayed in the picklist module
 * @return array $role - the roles present in the CRM in the array format
 */
function getrole2picklist(){
	global $adb;
	$query = "select rolename,roleid from aicrm_role where roleid not in('H1') order by rolename";
	$result = $adb->pquery($query, array());
	while($row = $adb->fetch_array($result)){
		$role[$row['roleid']] = $row['rolename'];
	}
	return $role;

}

/**
 * this function returns the picklists available for a module
 * @param array $picklist_details - the details about the picklists in the module
 * @return array $module_pick - the picklists present in the module in an array format
 */
function get_available_module_picklist($picklist_details){
	$avail_pick_values = $picklist_details;
	foreach($avail_pick_values as $key => $val){
		$module_pick[$avail_pick_values[$key]['fieldname']] = getTranslatedString($avail_pick_values[$key]['fieldlabel']);
	}
	return $module_pick;
}

/**
 * this function returns all the picklist values that are available for a given
 * @param string $fieldName - the name of the field
 * @return array $arr - the array containing the picklist values
 */
function getAllPickListValues($fieldName){
	global $adb;
	$sql = 'SELECT * FROM aicrm_'.$adb->sql_escape_string($fieldName);
	$result = $adb->query($sql);
	$count = $adb->num_rows($result);

	$arr = array();
	for($i=0;$i<$count;$i++){
		$arr[] = $adb->query_result($result, $i, $fieldName);
	}
	return $arr;
}


/**
 * this function accepts the fieldname and the language string array and returns all the editable picklist values for that fieldname
 * @param string $fieldName - the name of the picklist
 * @param array $lang - the language string array
 * @param object $adb - the peardatabase object
 * @return array $pick - the editable picklist values
 */
function getEditablePicklistValues($fieldName, $lang, $adb){
	$values = array();
	$fieldName = $adb->sql_escape_string($fieldName);
	$sql="select $fieldName from aicrm_$fieldName where presence=1 and $fieldName <> '--None--'";
	$res = $adb->query($sql);
	$RowCount = $adb->num_rows($res);
	if($RowCount > 0){
		for($i=0;$i<$RowCount;$i++){
			$pick_val = $adb->query_result($res,$i,$fieldName);
			if($lang[$pick_val] != ''){
				$values[]=$lang[$pick_val];
			}else{
				$values[]=$pick_val;
			}
		}
	}
	return $values;
}

/**
 * this function accepts the fieldname and the language string array and returns all the non-editable picklist values for that fieldname
 * @param string $fieldName - the name of the picklist
 * @param array $lang - the language string array
 * @param object $adb - the peardatabase object
 * @return array $pick - the no-editable picklist values
 */
function getNonEditablePicklistValues($fieldName, $lang, $adb){
	$values = array();
	$fieldName = $adb->sql_escape_string($fieldName);
	$sql = "select $fieldName from aicrm_$fieldName where presence=0";
	$result = $adb->query($sql);
	$count = $adb->num_rows($result);
	for($i=0;$i<$count;$i++){
		$non_val = $adb->query_result($result,$i,$fieldName);
		if($lang[$non_val] != ''){
			$values[]=$lang[$non_val];
		}else{
			$values[]=$non_val;
		}
	}
	if(count($values)==0){
		$values = "";
	}
	return $values;
}

/**
 * this function returns all the assigned picklist values for the given tablename for the given roleid
 * @param string $tableName - the picklist tablename
 * @param integer $roleid - the roleid of the role for which you want data
 * @param object $adb - the peardatabase object
 * @return array $val - the assigned picklist values in array format
 */
function getAssignedPicklistValues($tableName, $roleid, $adb){ //echo $roleid.' | ';
	$arr = array();

	$sub = getSubordinateRoleAndUsers($roleid); 
	$subRoles = array($roleid);
	$subRoles = array_merge($subRoles, array_keys($sub));

	$sql = "select picklistid from aicrm_picklist where name = ?";
	$result = $adb->pquery($sql, array($tableName));

	if($adb->num_rows($result)){
		$picklistid = $adb->query_result($result, 0, "picklistid");


		$roleids = array();
		foreach($subRoles as $role){
			$roleids[] = $role;
		}
		// echo implode(',', $roleids).'</br>';
		/*$sql = "SELECT distinct ".$adb->sql_escape_string($tableName)." FROM ". $adb->sql_escape_string("aicrm_$tableName")
				. " inner join aicrm_role2picklist on ".$adb->sql_escape_string("aicrm_$tableName").".picklist_valueid=aicrm_role2picklist.picklistvalueid"
				. " and roleid in (".generateQuestionMarks($roleids).") where aicrm_role2picklist.picklistid =$picklistid order by sortid";*/
		/*print_r($roleids)."<br>";*/
		//echo $sql."<br>";
		if($adb->sql_escape_string($tableName) == 'cf_25740'){
			$sql = "SELECT distinct ".$adb->sql_escape_string($tableName)." FROM ". $adb->sql_escape_string("aicrm_$tableName")
				. " inner join aicrm_role2picklist on ".$adb->sql_escape_string("aicrm_$tableName").".picklist_valueid=aicrm_role2picklist.picklistvalueid"
				. " and roleid in (".generateQuestionMarks($roleids).") where aicrm_role2picklist.picklistid =$picklistid order by ". $adb->sql_escape_string("aicrm_$tableName")
				. ".".$tableName." asc";
		}else{
			$sql = "SELECT distinct ".$adb->sql_escape_string($tableName)." FROM ". $adb->sql_escape_string("aicrm_$tableName")
				. " inner join aicrm_role2picklist on ".$adb->sql_escape_string("aicrm_$tableName").".picklist_valueid=aicrm_role2picklist.picklistvalueid"
				. " and roleid in (".generateQuestionMarks($roleids).") where aicrm_role2picklist.picklistid =$picklistid order by sortid";
		}
		//echo $sql."<br>";
		$result = $adb->pquery($sql, $roleids);
		$count = $adb->num_rows($result);

		if($count) {
			while($resultrow = $adb->fetch_array($result)) {
				$arr[] = $resultrow[$tableName];
			}
		}
	}
	// END

	return $arr;
}
?>
