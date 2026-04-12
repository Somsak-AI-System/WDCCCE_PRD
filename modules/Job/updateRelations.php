<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/
require_once('include/database/PearDatabase.php');
require_once('user_privileges/default_module_view.php');

include("config.inc.php");
include("library/dbconfig.php");
include("library/myLibrary_mysqli.php");

$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

global $adb, $singlepane_view, $currentModule;
$idlist = vtlib_purify($_REQUEST['idlist']);
$dest_mod = vtlib_purify($_REQUEST['destination_module']);
$parenttab = getParentTab();

$forCRMRecord = vtlib_purify($_REQUEST['parentid']);

if($singlepane_view == 'true')
	$action = "DetailView";
else
	$action = "CallRelatedList";

$storearray = array();
if(!empty($_REQUEST['idlist'])) {
	// Split the string of ids
	$storearray = explode (";",trim($idlist,";"));
} else if(!empty($_REQUEST['entityid'])){
	$storearray = array($_REQUEST['entityid']);
}

$focus = CRMEntity::getInstance($currentModule);
//print_r($_REQUEST);exit;
//echo $dest_mod;exit;

foreach($storearray as $id)
{
	if($id != '')
	{
		if($dest_mod == 'Documents')
			$adb->pquery("insert into aicrm_senotesrel values (?,?)", array($forCRMRecord,$id));
		else {
			$focus->save_related_module($currentModule, $forCRMRecord, $dest_mod, $id);
		}
	}
}

//$serialid = $storearray;
if (( $_REQUEST['module'] == 'Job' && $dest_mod == 'Serial') ){
   
    ############  Product  ###########
    foreach ($storearray as $key => $value){

//        echo "<pre>"; print_r($value); echo "</pre>";
        $sql_select_productid = "select aicrm_serial.product_id , aicrm_products.productname , aicrm_serial.serial_name from aicrm_serial 
                                 INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid 
                                 INNER JOIN aicrm_products ON aicrm_products.productid = aicrm_serial.product_id 
                                 WHERE aicrm_serial.serialid IN (".$value.") and aicrm_crmentity.deleted = 0";
        $data_productid = $myLibrary_mysqli->select($sql_select_productid);
        //echo "<pre>"; print_r($data_productid[0]); echo "</pre>";

        $product_id = $data_productid[0]['product_id'];
        $productname = $data_productid[0]['productname'];
        $serial_name = $data_productid[0]['serial_name'];
        ############  Tools  ###########
        //foreach ($data_productid[0] as $key2 => $toolsid){
            $sql_select_toolsid = "select toolsid FROM
                                        aicrm_tools
                                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
                                    INNER JOIN aicrm_crmentityrel ON (
                                        aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid
                                        OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
                                    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_tools.toolsid
                                    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
                                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid ";
            $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN ('.$product_id.') OR aicrm_crmentityrel.relcrmid IN ('.$product_id.')) and aicrm_crmentity.deleted = 0 ';
            $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);
            //echo "<pre>"; print_r($data_toolsid); echo "</pre>"; exit;
        //}

        ############  Inspection Template  ###########
        foreach ($data_productid[0] as $key3 => $product_id){
            $sql_select_inspectiontemplateid = "select inspectiontemplateid from aicrm_products INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid";
            $sql_select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN ('.$product_id.') and aicrm_crmentity.deleted = 0';
            $data_inspectiontemplateid = $myLibrary_mysqli->select($sql_select_inspectiontemplateid);
            //echo "<pre>"; print_r($data_inspectiontemplateid); echo "</pre>";

            ############  Inspection  ###########
            foreach ($data_inspectiontemplateid[0] as $key4 => $inspectiontemplateid){

                //############  Job  ###########
                $job_focus = new Job();
                $job_focus->retrieve_entity_info($_REQUEST['parentid'],"Job");

//                echo "<pre>"; print_r($job_focus); echo "</pre>";exit;
                require_once('modules/Inspection/Inspection.php');
                $inspec_focus = new Inspection();

                $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid;
                $inspec_focus->column_fields['inspection_status'] = 'Open';
                $inspec_focus->column_fields['inspec_report_type'] = str_replace('&amp;', '&', $job_focus->column_fields['jobtype']);
                $inspec_focus->column_fields['jobid'] = $_REQUEST['parentid'];
                $inspec_focus->column_fields['serialid'] = $value;
                $inspec_focus->column_fields['inspection_name'] = $serial_name.' / '.$productname ;
                $inspec_focus->column_fields['assigned_user_id'] = $job_focus->column_fields['assigned_user_id'];
                $inspec_focus->id = '';
                $inspec_focus->mode = "";
                $_REQUEST["module"] = "Inspection";
                //echo "<pre>"; print_r($inspec_focus->column_fields); echo "</pre>";

                $inspec_focus->save("Inspection");
                $inspectionid = $inspec_focus->id;

                foreach ($data_toolsid as $key5 => $value5) {
                    //echo $value5['toolsid']; exit;
                    $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('".$inspectionid."','Inspection','".$value5['toolsid']."','Tools')";
                    $myLibrary_mysqli->Query($ins_tool);
                }


            }


        }
    }//exit;

}

header("Location: index.php?action=$action&module=$currentModule&record=".$forCRMRecord."&parenttab=".$parenttab);

?>