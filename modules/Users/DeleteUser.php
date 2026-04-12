<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

global $adb;
$del_id =  $_REQUEST['delete_user_id'];
$tran_id = $_REQUEST['transfer_user_id'];

//Updating the smcreatorid,smownerid, modifiedby in aicrm_crmentity
$sql1 = "update aicrm_crmentity set smcreatorid=? where smcreatorid=?";
$adb->pquery($sql1, array($tran_id, $del_id));
$sql2 = "update aicrm_crmentity set smownerid=? where smownerid=?";
$adb->pquery($sql2, array($tran_id, $del_id));
$sql3 = "update aicrm_crmentity set modifiedby=? where modifiedby=?";
$adb->pquery($sql3, array($tran_id, $del_id));

//deleting from aicrm_tracker
$sql4 = "delete from aicrm_tracker where user_id=?";
$adb->pquery($sql4, array($del_id));

//updating created by in aicrm_lar
$sql5 = "update aicrm_lar set createdby=? where createdby=?";
$adb->pquery($sql5, array($tran_id, $del_id));

//updating the aicrm_import_maps
$sql6 ="update aicrm_import_maps set assigned_user_id=? where assigned_user_id=?";
$adb->pquery($sql6, array($tran_id, $del_id));

//update assigned_user_id in aicrm_files
$sql7 ="update aicrm_files set assigned_user_id=? where assigned_user_id=?";
$adb->pquery($sql7, array($tran_id, $del_id)); 

//update assigned_user_id in aicrm_users_last_import
$sql8 = "update aicrm_users_last_import set assigned_user_id=? where assigned_user_id=?";
$adb->pquery($sql8, array($tran_id, $del_id));

//updating handler in aicrm_products
$sql9 = "update aicrm_products set handler=? where handler=?";
$adb->pquery($sql9, array($tran_id, $del_id));

//updating inventorymanager in aicrm_quotes
$sql10 = "update aicrm_quotes set inventorymanager=? where inventorymanager=?";
$adb->pquery($sql10, array($tran_id, $del_id));

//updating reports_to_id in aicrm_users
$sql11 = "update aicrm_users set reports_to_id=? where reports_to_id=?";
$adb->pquery($sql11, array($tran_id, $del_id));

//updating user_id in aicrm_moduleowners
$sql12 = "update aicrm_moduleowners set user_id=? where user_id=?";
$adb->pquery($sql12, array($tran_id, $del_id));

//delete from aicrm_users to group aicrm_table
$sql13 = "delete from aicrm_user2role where userid=?";
$adb->pquery($sql13, array($del_id));

//delete from aicrm_users to aicrm_role aicrm_table
$sql14 = "delete from aicrm_users2group where userid=?";
$adb->pquery($sql14, array($del_id));


//delete from user aicrm_table;
$sql15 = "delete from aicrm_users where id=?";
$adb->pquery($sql15, array($del_id));

//if check to delete user from detail view
if(isset($_REQUEST["ajax_delete"]) && $_REQUEST["ajax_delete"] == 'false'){
	$a_return['status'] = 'false';
	//header("Location: index.php?action=ListView&module=Users");
}else{
	$a_return['status'] = 'true';
	echo json_encode($a_return);  
	//header("Location: index.php?action=UsersAjax&module=Users&file=ListView&ajax=true");
}
?>