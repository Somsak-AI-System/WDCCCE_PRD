<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('include/utils/utils.php');
require_once('include/RelatedListView.php');
require_once('user_privileges/default_module_view.php');

class Serial extends CRMEntity
{
// Account is used to store aicrm_account information.
    var $log;
    var $db;
    var $table_name = "aicrm_serial";
    var $table_index = 'serialid';

    var $tab_name = Array('aicrm_crmentity', 'aicrm_serial', 'aicrm_serialcf');
    var $tab_name_index = Array('aicrm_crmentity' => 'crmid', 'aicrm_serial' => 'serialid', 'aicrm_serialcf' => 'serialid');
    /**
     * Mandatory table for supporting custom fields.
     */
    var $customFieldTable = Array('aicrm_serialcf', 'serialid');
    var $column_fields = Array();

    var $sortby_fields = Array('serialid', 'serial_name', 'smownerid');

    var $list_fields = Array(
        'รหัส ID ของซีเรียล/Serial ID Code' => Array('Serial' => 'serial_no'),
        'Serial Name' => Array('Serial' => 'serial_name'),
        'Serial Type' => Array('Serial' => 'serial_type'),
        'Product Name' => Array('Serial' => 'productname'),
        'Warranty Active Date' => Array('Serial' => 'warranty_active_date'),
        'Warranty Expired Date' => Array('Serial' => 'warranty_expired_date'),
        'Warranty Status' => Array('Serial' => 'warranty_status'),
        'Assigned To' => Array('crmentity' => 'assigned_user_id')
    );

    var $list_fields_name = Array(
        'รหัส ID ของซีเรียล/Serial ID Code' => 'serial_no',
        'Serial Name' => 'serial_name',
        'Serial Type' => 'serial_type',
        'Product Name' => 'productname',
        'Warranty Active Date' => 'warranty_active_date',
        'Warranty Expired Date' => 'warranty_expired_date',
        'Warranty Status' => 'warranty_status',
        'Assigned To' => 'assigned_user_id',
    );

    var $list_link_field = 'serial_no';
    //Added these variables which are used as default order by and sortorder in ListView
    var $default_order_by = 'crmid';
    var $default_sort_order = 'desc';

    //var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

    var $search_fields = Array(
        'รหัส ID ของซีเรียล/Serial ID Code' => Array('aicrm_serial' => 'serial_no'),
        'Serial Name' => Array('aicrm_serial' => 'serial_name'),
        'Warranty Active Date' => Array('aicrm_serial' => 'warranty_active_date'),
        'Warranty Expired Date' => Array('aicrm_serial' => 'warranty_expired_date'),
        'Warranty Status' => Array('aicrm_serial' => 'warranty_status'),
        'Description' => Array('Serial' => 'description'),
    );

    var $search_fields_name = Array(
        'รหัส ID ของซีเรียล/Serial ID Code' => 'serial_no',
        'Serial Name' => 'serial_name',
        'Warranty Active Date' => 'warranty_active_date',
        'Warranty Expired Date' => 'warranty_expired_date',
        'Warranty Status' => 'warranty_status',
        'Description' => 'description',
    );
    // Used when enabling/disabling the mandatory fields for the module.
    // Refers to aicrm_field.fieldname values.
    var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'serialid');

    function Serial()
    {
        $this->log = LoggerManager::getLogger('Serial');
        $this->db = PearDatabase::getInstance();
        $this->column_fields = getColumnFields('Serial');
    }

    function save_module()
    {
        global $adb;
        //in ajax save we should not call this function, because this will delete all the existing product values
        if ($_REQUEST['action'] != 'SerialAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave') {
            //Based on the total Number of rows we will save the product relationship with this entity
            saveInventoryProductDetails($this, 'Serial');
        }

        /*Puen Add CR004 Sticker*/
        if ($this->mode == '' && $this->id != '') {

            require_once("library/general.php");
            include_once("library/log.php");
            global $generate, $current_user, $root_directory, $root_directory, $site_URL;

            $General = new libGeneral();
            $Log = new log();
            $Log->_logname = "logs/sticker";

            $url = $site_URL . "WB_Service_AI/job/gen_qrcode";
            $crmid = $this->id;

            $sql = "select serial_no as serial_name
					from aicrm_serial
					INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_serial.serialid
					where 
					aicrm_crmentity.deleted =0
					and aicrm_serial.serialid = '" . $crmid . "'
					";

            $data = $adb->pquery($sql, '');
            $serialname = $adb->query_result($data, 0, "serial_name");

            $a_param["AI-API-KEY"] = "1234";
            $a_param["crmid"] = $serialname;

            $a_curl = $General->curl($url, $a_param, "json");
            $Log->write_log("url =>" . $url);
            $Log->write_log("parameter =>" . json_encode($a_param));
            $Log->write_log("response =>" . json_encode($a_curl));

        } /*End*/

//        if($this->mode == '' && $_REQUEST['action'] != "Import") {
//            include("config.inc.php");
//            include_once("library/dbconfig.php");
//            include_once("library/myLibrary_mysqli.php");
//            $myLibrary_mysqli = new myLibrary_mysqli();
//            $myLibrary_mysqli->_dbconfig = $dbconfig;
//            //echo 555;
//            //echo "<pre>"; print_r($this->column_fields); echo"</pre>";
//            if($this->column_fields['account_id'] != '' && $this->column_fields['product_id'] != ''){
//
//                if($this->column_fields['warranty_active_date'] != ''){
//
//                    if($this->column_fields['pm_time_no'] != '' && $this->column_fields['around_pm'] != ''){
//                        $new_date = $this->column_fields['warranty_active_date'];
//                        $pm_time_no = $this->column_fields['pm_time_no'];
//                        $product_id = $this->column_fields['product_id'];
//                        for ($i=1;$i<=$this->column_fields['around_pm'];$i++) {
//
//                            require_once('modules/Job/Job.php');
//                            $job_focus = new Job();
//                            $job_focus->column_fields['jobtype'] = 'Preventive Maintenance';
//                            $job_focus->column_fields['job_name'] = 'Preventive Maintenance';
//                            $job_focus->column_fields['job_status'] = 'Open';
//                            //$new_date = date("d-m-Y", strtotime("+".$pm_time_no." month", $new_date));
//                            $new_date = date("d-m-Y",strtotime(date("Y-m-d", strtotime($new_date)) . "+".$pm_time_no." month"));
//                            //echo $new_date  ;
//                            $job_focus->column_fields['jobdate_operate'] = $new_date;
//                            $job_focus->column_fields['start_time'] = '9:00';
//                            $job_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
//                            $job_focus->column_fields['account_id'] = $this->column_fields['account_id'];
//                            $_REQUEST["module"] = "Job";
//                            $job_focus->mode ="";
//                            $job_focus->id="";
//                            $job_focus->save("Job");
//                            $jobid = $job_focus->id;
//
//                            require_once('modules/Calendar/Activity.php');
//                            $act_focus = new Activity();
//                            $act_focus->column_fields['activitytype'] = 'Preventive Maintenance';
//                            $act_focus->column_fields['date_start'] = $new_date;
//                            $act_focus->column_fields['time_start'] = '9:00';
//                            $act_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
//                            $act_focus->column_fields['eventstatus'] = 'Plan';
//                            $act_focus->column_fields['account_id'] = $this->column_fields['account_id'];
//                            $act_focus->column_fields['event_id'] = $jobid;
//                            $act_focus->column_fields['description'] = 'Preventive Maintenance';
//                            $_REQUEST["module"] = "Calendar";
//                            $act_focus->mode ="";
//                            $act_focus->id="";
//                            $act_focus->save("Calendar");
//
//                            $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('".$jobid."','Job','".$this->id."','Serial')";
//                            $myLibrary_mysqli->Query($sql_rel);
//
//                            //Inspection Template
//                            $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
//							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
//							INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
//							INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
//                            $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN ('.$product_id.') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
//                            $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);
//
//                            if(!empty($inspectiontemplateid)){
//                                require_once('modules/Inspection/Inspection.php');
//                                $inspec_focus = new Inspection();
//                                $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
//                                $inspec_focus->column_fields['inspection_status'] = 'Open';
//                                $inspec_focus->column_fields['inspec_report_type'] = 'Preventive Maintenance';
//                                $inspec_focus->column_fields['jobid'] = $jobid;
//                                $inspec_focus->column_fields['serialid'] = $this->id;
//                                $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
//                                $inspec_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
//                                $_REQUEST["module"] = "Inspection";
//                                $inspec_focus->id = '';
//                                $inspec_focus->mode = "";
//                                $inspec_focus->save("Inspection");
//                                $inspectionid = $inspec_focus->id;
//
//                                $sql_select_toolsid = "select toolsid FROM aicrm_tools
//                                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
//                                    INNER JOIN aicrm_crmentityrel ON (
//                                        aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid
//                                        OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
//                                    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_tools.toolsid
//                                    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
//                                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid ";
//                                $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN ('.$product_id.') OR aicrm_crmentityrel.relcrmid IN ('.$product_id.')) and aicrm_crmentity.deleted = 0 ';
//                                $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);
//
//                                foreach ($data_toolsid as $key => $value) {
//                                    $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('".$inspectionid."','Inspection','".$value['toolsid']."','Tools')";
//                                    $myLibrary_mysqli->Query($ins_tool);
//                                }
//
//                            }
//
//
//                        }
//
//                    }//if $this->column_fields['pm_time_no']
//
//
//                    if($this->column_fields['time_cal_no'] != '' && $this->column_fields['around_cal'] != ''){
//                        $new_date = $this->column_fields['warranty_active_date'];
//                        $time_cal_no = $this->column_fields['time_cal_no'];
//                        $product_id = $this->column_fields['product_id'];
//
//                        for ($i=1;$i<=$this->column_fields['around_cal'];$i++) {
//
//                            require_once('modules/Job/Job.php');
//                            $job_focus = new Job();
//                            $job_focus->column_fields['jobtype'] = 'Calibration';
//                            $job_focus->column_fields['job_name'] = 'Calibration';
//                            $job_focus->column_fields['job_status'] = 'Open';
//                            //$new_date = date("d-m-Y", strtotime("+".$pm_time_no." month", $new_date));
//                            $new_date = date("d-m-Y",strtotime(date("Y-m-d", strtotime($new_date)) . "+".$time_cal_no." month"));
//                            //echo $new_date  ;
//                            $job_focus->column_fields['jobdate_operate'] = $new_date;
//                            $job_focus->column_fields['start_time'] = '9:00';
//                            $job_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
//                            $job_focus->column_fields['account_id'] = $this->column_fields['account_id'];
//                            $_REQUEST["module"] = "Job";
//                            $job_focus->mode ="";
//                            $job_focus->id="";
//                            $job_focus->save("Job");
//                            $jobid = $job_focus->id;
//
//                            require_once('modules/Calendar/Activity.php');
//                            $act_focus = new Activity();
//                            $act_focus->column_fields['activitytype'] = 'Calibration';
//                            $act_focus->column_fields['date_start'] = $new_date;
//                            $act_focus->column_fields['time_start'] = '9:00';
//                            $act_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
//                            $act_focus->column_fields['eventstatus'] = 'Plan';
//                            $act_focus->column_fields['account_id'] = $this->column_fields['account_id'];
//                            $act_focus->column_fields['event_id'] = $jobid;
//                            $act_focus->column_fields['description'] = 'Calibration';
//                            $_REQUEST["module"] = "Calendar";
//                            $act_focus->mode ="";
//                            $act_focus->id="";
//                            $act_focus->save("Calendar");
//
//                            $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('".$jobid."','Job','".$this->id."','Serial')";
//                            $myLibrary_mysqli->Query($sql_rel);
//
//                            //Inspection Template
//                            $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
//							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
//							INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
//							INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
//                            $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN ('.$product_id.') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
//                            $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);
//
//                            if(!empty($inspectiontemplateid)){
//                                require_once('modules/Inspection/Inspection.php');
//                                $inspec_focus = new Inspection();
//                                $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
//                                $inspec_focus->column_fields['inspection_status'] = 'Open';
//                                $inspec_focus->column_fields['inspec_report_type'] = 'Calibration';
//                                $inspec_focus->column_fields['jobid'] = $jobid;
//                                $inspec_focus->column_fields['serialid'] = $this->id;
//                                $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
//                                $inspec_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
//                                $_REQUEST["module"] = "Inspection";
//                                $inspec_focus->id = '';
//                                $inspec_focus->mode = "";
//                                $inspec_focus->save("Inspection");
//                                $inspectionid = $inspec_focus->id;
//
//                                $sql_select_toolsid = "select toolsid FROM aicrm_tools
//                                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
//                                    INNER JOIN aicrm_crmentityrel ON (
//                                        aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid
//                                        OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
//                                    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_tools.toolsid
//                                    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
//                                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid ";
//                                $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN ('.$product_id.') OR aicrm_crmentityrel.relcrmid IN ('.$product_id.')) and aicrm_crmentity.deleted = 0 ';
//                                $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);
//
//                                foreach ($data_toolsid as $key => $value) {
//                                    $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('".$inspectionid."','Inspection','".$value['toolsid']."','Tools')";
//                                    $myLibrary_mysqli->Query($ins_tool);
//                                }
//
//                            }
//
//
//                        }
//
//                    }//if $this->column_fields['time_cal_no']
//
//                }
//
//
//            }
//
//            //exit;
//        }

        if ($this->mode == '' && (isset($_REQUEST['action']) && $_REQUEST['action'] != "Import") ) {
            include("config.inc.php");
            include_once("library/dbconfig.php");
            include_once("library/myLibrary_mysqli.php");
            $myLibrary_mysqli = new myLibrary_mysqli();
            $myLibrary_mysqli->_dbconfig = $dbconfig;
            //echo 555;
            //echo "<pre>"; print_r($this->column_fields); echo"</pre>";
            if ($this->column_fields['account_id'] != '' && $this->column_fields['product_id'] != '') {

                if ($this->column_fields['warranty_active_date'] != '') {


                    /*Puen add for CR006*/
                    $month_diff = '';
                    if ($this->column_fields['warranty_expired_date'] != '' && $this->column_fields['warranty_active_date'] != '') {

                        $month_diff = $this->get_month_diff($this->column_fields['warranty_expired_date'], $this->column_fields['warranty_active_date']);
                    }

//                    echo $month_diff;exit;
                    if (($this->column_fields['pm_time_no'] != '' || $this->column_fields['pm_time_no'] != '--None--') && ($this->column_fields['around_pm'] == '' || $this->column_fields['around_pm'] == '0')) {
                        $numloop = floor($month_diff / $this->column_fields['pm_time_no']);
//                        $numloop = $this->column_fields['pm_time_no'];
                        $new_date = $this->column_fields['warranty_active_date'];
                        $pm_time_no = $this->column_fields['pm_time_no'];
//                        $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($this->column_fields['warranty_active_date'])) . "-" . $pm_time_no . " month"));

                        $product_id = $this->column_fields['product_id'];

                        for ($i = 1; $i <= $numloop; $i++) {

                            $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $pm_time_no . " month"));
//echo $new_date;echo '<br>';
                            require_once('modules/Job/Job.php');
                            $job_focus = new Job();
                            $job_focus->column_fields['jobtype'] = 'Preventive Maintenance';
                            $job_focus->column_fields['job_name'] = 'Preventive Maintenance';
                            $job_focus->column_fields['job_status'] = 'Open';
                            //$new_date = date("d-m-Y", strtotime("+".$pm_time_no." month", $new_date));
                            $job_focus->column_fields['jobdate_operate'] = $new_date;
                            $job_focus->column_fields['start_time'] = '9:00';
                            $job_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $job_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $_REQUEST["module"] = "Job";
                            $job_focus->mode = "";
                            $job_focus->id = "";
                            $job_focus->save("Job");
//                            echo '<pre>'; print_r($job_focus->column_fields); echo '</pre>';exit;

                            $jobid = $job_focus->id;

                            require_once('modules/Calendar/Activity.php');
                            $act_focus = new Activity();
                            $act_focus->column_fields['activitytype'] = 'Preventive Maintenance';
                            $act_focus->column_fields['date_start'] = $new_date;
                            $act_focus->column_fields['time_start'] = '9:00';
                            $act_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $act_focus->column_fields['eventstatus'] = 'Plan';
                            $act_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $act_focus->column_fields['event_id'] = $jobid;
                            $act_focus->column_fields['description'] = 'Preventive Maintenance';
                            $_REQUEST["module"] = "Calendar";
                            $act_focus->mode = "";
                            $act_focus->id = "";
                            $act_focus->save("Calendar");

                            $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $this->id . "','Serial')";
                            $myLibrary_mysqli->Query($sql_rel);

                            //Inspection Template
                            $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
							INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
							INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                            $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $product_id . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                            $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);

                            if (!empty($inspectiontemplateid)) {
                                require_once('modules/Inspection/Inspection.php');
                                $inspec_focus = new Inspection();
                                $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                                $inspec_focus->column_fields['inspection_status'] = 'Open';
                                $inspec_focus->column_fields['inspec_report_type'] = 'Preventive Maintenance';
                                $inspec_focus->column_fields['jobid'] = $jobid;
                                $inspec_focus->column_fields['serialid'] = $this->id;
                                $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                                $inspec_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                                $_REQUEST["module"] = "Inspection";
                                $inspec_focus->id = '';
                                $inspec_focus->mode = "";
                                $inspec_focus->save("Inspection");
                                $inspectionid = $inspec_focus->id;

                                $sql_select_toolsid = "select toolsid FROM aicrm_tools
                                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
                                    INNER JOIN aicrm_crmentityrel ON (
                                        aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid
                                        OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
                                    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_tools.toolsid
                                    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
                                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid ";
                                $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $product_id . ') OR aicrm_crmentityrel.relcrmid IN (' . $product_id . ')) and aicrm_crmentity.deleted = 0 ';
                                $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                                foreach ($data_toolsid as $key => $value) {
                                    $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                    $myLibrary_mysqli->Query($ins_tool);
                                }

                            }


                        }
//                        exit;
                    }

                    if ($this->column_fields['around_pm'] != '' && $this->column_fields['around_pm'] != '0') {
                        //if around_pm !=''

//                        $numloop = floor($month_diff / $this->column_fields['around_pm']);
                        $numloop = $this->column_fields['pm_time_no'];
                        $new_date = $this->column_fields['warranty_active_date'];
//                        $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($this->column_fields['warranty_active_date'])) . "-" . $numloop . " month"));
//                        echo $new_date;exit;
                        $pm_time_no = $this->column_fields['around_pm'];
                        $product_id = $this->column_fields['product_id'];

                        for ($i = 1; $i <= $pm_time_no; $i++) {
                            $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $numloop . " month"));
//echo $new_date;echo '<br>';
                            require_once('modules/Job/Job.php');
                            $job_focus = new Job();
                            $job_focus->column_fields['jobtype'] = 'Preventive Maintenance';
                            $job_focus->column_fields['job_name'] = 'Preventive Maintenance';
                            $job_focus->column_fields['job_status'] = 'Open';
                            //$new_date = date("d-m-Y", strtotime("+".$pm_time_no." month", $new_date));
                            $job_focus->column_fields['jobdate_operate'] = $new_date;
                            $job_focus->column_fields['start_time'] = '9:00';
                            $job_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $job_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $_REQUEST["module"] = "Job";
                            $job_focus->mode = "";
                            $job_focus->id = "";
                            $job_focus->save("Job");
//                            echo '<pre>'; print_r($job_focus->column_fields); echo '</pre>';exit;

                            $jobid = $job_focus->id;

                            require_once('modules/Calendar/Activity.php');
                            $act_focus = new Activity();
                            $act_focus->column_fields['activitytype'] = 'Preventive Maintenance';
                            $act_focus->column_fields['date_start'] = $new_date;
                            $act_focus->column_fields['time_start'] = '9:00';
                            $act_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $act_focus->column_fields['eventstatus'] = 'Plan';
                            $act_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $act_focus->column_fields['event_id'] = $jobid;
                            $act_focus->column_fields['description'] = 'Preventive Maintenance';
                            $_REQUEST["module"] = "Calendar";
                            $act_focus->mode = "";
                            $act_focus->id = "";
                            $act_focus->save("Calendar");

                            $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $this->id . "','Serial')";
                            $myLibrary_mysqli->Query($sql_rel);

                            //Inspection Template
                            $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
							INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
							INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                            $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $product_id . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                            $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);

                            if (!empty($inspectiontemplateid)) {
                                require_once('modules/Inspection/Inspection.php');
                                $inspec_focus = new Inspection();
                                $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                                $inspec_focus->column_fields['inspection_status'] = 'Open';
                                $inspec_focus->column_fields['inspec_report_type'] = 'Preventive Maintenance';
                                $inspec_focus->column_fields['jobid'] = $jobid;
                                $inspec_focus->column_fields['serialid'] = $this->id;
                                $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                                $inspec_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                                $_REQUEST["module"] = "Inspection";
                                $inspec_focus->id = '';
                                $inspec_focus->mode = "";
                                $inspec_focus->save("Inspection");
                                $inspectionid = $inspec_focus->id;

                                $sql_select_toolsid = "select toolsid FROM aicrm_tools
                                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
                                    INNER JOIN aicrm_crmentityrel ON (
                                        aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid
                                        OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
                                    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_tools.toolsid
                                    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
                                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid ";
                                $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $product_id . ') OR aicrm_crmentityrel.relcrmid IN (' . $product_id . ')) and aicrm_crmentity.deleted = 0 ';
                                $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                                foreach ($data_toolsid as $key => $value) {
                                    $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                    $myLibrary_mysqli->Query($ins_tool);
                                }

                            }
                            if (strtotime($new_date) >= strtotime($this->column_fields['warranty_expired_date'])){
                                break;
                            }

                        }
//                        exit;

                    } else {
                    }

                    /*time_cal_no*/

                    if (($this->column_fields['time_cal_no'] != '' || $this->column_fields['time_cal_no'] != '--None--') && ($this->column_fields['around_cal'] == '' || $this->column_fields['around_cal'] == '0')) {
                        $numloop = floor($month_diff / $this->column_fields['time_cal_no']);
//                        echo $this->column_fields['time_cal_no']; echo '<br>';
//                        echo $numloop;echo '<br>';
                        $new_date = $this->column_fields['warranty_active_date'];
                        $time_cal_no = $this->column_fields['time_cal_no'];
//                        $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($this->column_fields['warranty_active_date'])) . "-" . $time_cal_no . " month"));

                        $product_id = $this->column_fields['product_id'];

                        for ($i = 1; $i <= $numloop; $i++) {
                            $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $time_cal_no . " month"));
//echo $new_date;echo '<br>';
                            require_once('modules/Job/Job.php');
                            $job_focus = new Job();
                            $job_focus->column_fields['jobtype'] = 'Calibration';
                            $job_focus->column_fields['job_name'] = 'Calibration';
                            $job_focus->column_fields['job_status'] = 'Open';
                            //$new_date = date("d-m-Y", strtotime("+".$pm_time_no." month", $new_date));
                            $job_focus->column_fields['jobdate_operate'] = $new_date;
                            $job_focus->column_fields['start_time'] = '9:00';
                            $job_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $job_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $_REQUEST["module"] = "Job";
                            $job_focus->mode = "";
                            $job_focus->id = "";
                            $job_focus->save("Job");
                            $jobid = $job_focus->id;

                            require_once('modules/Calendar/Activity.php');
                            $act_focus = new Activity();
                            $act_focus->column_fields['activitytype'] = 'Calibration';
                            $act_focus->column_fields['date_start'] = $new_date;
                            $act_focus->column_fields['time_start'] = '9:00';
                            $act_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $act_focus->column_fields['eventstatus'] = 'Plan';
                            $act_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $act_focus->column_fields['event_id'] = $jobid;
                            $act_focus->column_fields['description'] = 'Calibration';
                            $_REQUEST["module"] = "Calendar";
                            $act_focus->mode = "";
                            $act_focus->id = "";
                            $act_focus->save("Calendar");

                            $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $this->id . "','Serial')";
                            $myLibrary_mysqli->Query($sql_rel);

                            //Inspection Template
                            $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
							INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
							INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                            $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $product_id . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                            $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);

                            if (!empty($inspectiontemplateid)) {
                                require_once('modules/Inspection/Inspection.php');
                                $inspec_focus = new Inspection();
                                $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                                $inspec_focus->column_fields['inspection_status'] = 'Open';
                                $inspec_focus->column_fields['inspec_report_type'] = 'Calibration';
                                $inspec_focus->column_fields['jobid'] = $jobid;
                                $inspec_focus->column_fields['serialid'] = $this->id;
                                $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                                $inspec_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                                $_REQUEST["module"] = "Inspection";
                                $inspec_focus->id = '';
                                $inspec_focus->mode = "";
                                $inspec_focus->save("Inspection");
                                $inspectionid = $inspec_focus->id;

                                $sql_select_toolsid = "select toolsid FROM aicrm_tools
                                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
                                    INNER JOIN aicrm_crmentityrel ON (
                                        aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid
                                        OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
                                    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_tools.toolsid
                                    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
                                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid ";
                                $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $product_id . ') OR aicrm_crmentityrel.relcrmid IN (' . $product_id . ')) and aicrm_crmentity.deleted = 0 ';
                                $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                                foreach ($data_toolsid as $key => $value) {
                                    $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                    $myLibrary_mysqli->Query($ins_tool);
                                }

                            }


                        }
//                        exit;

                    }

                    if ($this->column_fields['around_cal'] != '' && $this->column_fields['around_cal'] != '0') {
//                        $numloop = floor($month_diff / $this->column_fields['around_cal']);
//                        echo $numloop;exit;
                        $numloop = $this->column_fields['time_cal_no'];
//                        $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($this->column_fields['warranty_active_date'])) . "-" . $numloop . " month"));
                        $new_date = $this->column_fields['warranty_active_date'];
                        $time_cal_no = $this->column_fields['around_cal'];
                        $product_id = $this->column_fields['product_id'];

//                        $numloop_test = floor($month_diff / $this->column_fields['around_cal']);
//                        echo $numloop_test;
//                        exit;
                        for ($i = 1; $i <= $time_cal_no; $i++) {
                            $new_date = date("d-m-Y", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $numloop . " month"));
                            require_once('modules/Job/Job.php');
                            $job_focus = new Job();
                            $job_focus->column_fields['jobtype'] = 'Calibration';
                            $job_focus->column_fields['job_name'] = 'Calibration';
                            $job_focus->column_fields['job_status'] = 'Open';
                            //$new_date = date("d-m-Y", strtotime("+".$pm_time_no." month", $new_date));

                            echo $new_date;echo '<br>';
                            $job_focus->column_fields['jobdate_operate'] = $new_date;
                            $job_focus->column_fields['start_time'] = '9:00';
                            $job_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $job_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $_REQUEST["module"] = "Job";
                            $job_focus->mode = "";
                            $job_focus->id = "";
                            $job_focus->save("Job");
                            $jobid = $job_focus->id;
//                            echo '<pre>'; print_r($job_focus->column_fields); echo '</pre>';exit;
                            require_once('modules/Calendar/Activity.php');
                            $act_focus = new Activity();
                            $act_focus->column_fields['activitytype'] = 'Calibration';
                            $act_focus->column_fields['date_start'] = $new_date;
                            $act_focus->column_fields['time_start'] = '9:00';
                            $act_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                            $act_focus->column_fields['eventstatus'] = 'Plan';
                            $act_focus->column_fields['account_id'] = $this->column_fields['account_id'];
                            $act_focus->column_fields['event_id'] = $jobid;
                            $act_focus->column_fields['description'] = 'Calibration';
                            $_REQUEST["module"] = "Calendar";
                            $act_focus->mode = "";
                            $act_focus->id = "";
                            $act_focus->save("Calendar");

                            $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $this->id . "','Serial')";
                            $myLibrary_mysqli->Query($sql_rel);

                            //Inspection Template
                            $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
							INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
							INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                            $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $product_id . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                            $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);

                            if (!empty($inspectiontemplateid)) {
                                require_once('modules/Inspection/Inspection.php');
                                $inspec_focus = new Inspection();
                                $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                                $inspec_focus->column_fields['inspection_status'] = 'Open';
                                $inspec_focus->column_fields['inspec_report_type'] = 'Calibration';
                                $inspec_focus->column_fields['jobid'] = $jobid;
                                $inspec_focus->column_fields['serialid'] = $this->id;
                                $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                                $inspec_focus->column_fields['assigned_user_id'] = $this->column_fields['assigned_user_id'];
                                $_REQUEST["module"] = "Inspection";
                                $inspec_focus->id = '';
                                $inspec_focus->mode = "";
                                $inspec_focus->save("Inspection");
                                $inspectionid = $inspec_focus->id;

                                $sql_select_toolsid = "select toolsid FROM aicrm_tools
                                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
                                    INNER JOIN aicrm_crmentityrel ON (
                                        aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid
                                        OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid)
                                    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_tools.toolsid
                                    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
                                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid ";
                                $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $product_id . ') OR aicrm_crmentityrel.relcrmid IN (' . $product_id . ')) and aicrm_crmentity.deleted = 0 ';
                                $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                                foreach ($data_toolsid as $key => $value) {
                                    $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                    $myLibrary_mysqli->Query($ins_tool);
                                }

                            }
//echo '<br>';
//                            echo '===';
//                            echo strtotime($new_date);
//                            echo '<br>';
//                            echo strtotime($this->column_fields['warranty_expired_date']);exit;
//                            echo ;
                            if (strtotime($new_date) >= strtotime($this->column_fields['warranty_expired_date'])){
                                break;
                            }
                        }

                    }


                }


            }

//            exit;
        }
        // Update the currency id and the conversion rate for the quotes
    }

    /**    Function Get Month Diff
     * @return string round Value
     */
    function get_month_diff($start, $end)
    {

        $temp_start_date = (explode("-", $start));
        $temp_end_date = (explode("-", $end));


        $End = mktime(0, 0, 0, $temp_start_date[1], $temp_start_date[0], $temp_start_date[2]);
        $Start = mktime(0, 0, 0, $temp_end_date[1], $temp_end_date[0], $temp_end_date[2]);

//        $reurn_date = $End-$Start;

        $reurn_date = round(($End - $Start) / 60 / 60 / 24 / 30);
        return $reurn_date;

    }

    /**    Function used to get the sort order for Quote listview
     * @return string    $sorder    - first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['QUOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned.
     */
    function getSortOrder()
    {
        global $log;
        $log->debug("Entering getSortOrder() method ...");
        if (isset($_REQUEST['sorder']))
            $sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
        else
            $sorder = (($_SESSION['SERIAL_SORT_ORDER'] != '') ? ($_SESSION['SERIAL_SORT_ORDER']) : ($this->default_sort_order));
        $log->debug("Exiting getSortOrder() method ...");
        return $sorder;
    }

    function get_job($id, $cur_tab_id, $rel_tab_id, $actions = false)
    {
        global $log, $singlepane_view, $currentModule, $current_user;
        $log->debug("Entering get_job(" . $id . ") method ...");
        $this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);
        $parenttab = getParentTab();

        if ($singlepane_view == 'true')
            $returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
        else
            $returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

        $button = '';

        if ($actions) {
            if (is_string($actions)) $actions = explode(',', strtoupper($actions));

            if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
                $button .= "<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='" . getTranslatedString('LBL_ADD_NEW') . " Job'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_jobs.*,
			aicrm_jobscf.*
			FROM aicrm_jobs
			LEFT JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
			INNER JOIN aicrm_crmentityrel on aicrm_crmentityrel.crmid = aicrm_jobs.jobid
			INNER JOIN aicrm_serial on aicrm_serial.serialid = aicrm_crmentityrel.relcrmid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_jobs.product_id
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0  AND aicrm_crmentityrel.relcrmid = '" . $id . "'
			";
        //INNER JOIN aicrm_serial on aicrm_serial.serialid = aicrm_jobs.serialid
        //echo $query."<br>";
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;
        $log->debug("Exiting get_quotes method ...");
        return $return_value;
    }

    /**    Function used to get the order by value for Quotes listview
     * @return string    $order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['QUOTES_ORDER_BY'] if this session value is empty then default order by will be returned.
     */
    function getOrderBy()
    {
        global $log;
        $log->debug("Entering getOrderBy() method ...");

        $use_default_order_by = '';
        if (PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
            $use_default_order_by = $this->default_order_by;
        }

        if (isset($_REQUEST['order_by']))
            $order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
        else
            $order_by = (($_SESSION['SERIAL_ORDER_BY'] != '') ? ($_SESSION['SERIAL_ORDER_BY']) : ($use_default_order_by));
        $log->debug("Exiting getOrderBy method ...");
        return $order_by;
    }

    /**    function used to get the list of activities which are related to the Quotes
     * @param int $id - quote id
     * @return array - return an array which will be returned from the function GetRelatedList
     */
    function get_job_list($id, $cur_tab_id, $rel_tab_id, $actions = false)
    {
        global $log, $singlepane_view, $currentModule, $current_user;
        $log->debug("Entering get_job_list(" . $id . ") method ...");
        $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);

        $parenttab = getParentTab();

        if ($singlepane_view == 'true')
            $returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
        else
            $returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

        $button = '';

        if ($actions) {
            if (is_string($actions)) $actions = explode(',', strtoupper($actions));
            if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
                $button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
            }
            if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
                $button .= "<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "'>&nbsp;";
            }
        }

        $query = "SELECT aicrm_jobs.*, aicrm_jobscf.*, aicrm_crmentity.crmid,
	              aicrm_crmentity.smownerid,
	              aicrm_serial.serial_name,
	              CASE
                  WHEN ( aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
                  ELSE aicrm_groups.groupname END AS user_name FROM aicrm_jobs
                  LEFT JOIN aicrm_jobscf ON aicrm_jobs.jobid = aicrm_jobscf.jobid
                  INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
				  INNER JOIN aicrm_crmentityrel on aicrm_crmentityrel.crmid = aicrm_jobs.jobid
				  
                  INNER JOIN aicrm_serial ON aicrm_serial.serialid = aicrm_crmentityrel.relcrmid
                  LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                  LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                  WHERE aicrm_crmentity.deleted = 0 AND aicrm_crmentityrel.relcrmid = " . $id;
        // echo $query;
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
        //print_r($return_value);
        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_contacts method ...");
        return $return_value;
    }

    function get_activities($id, $cur_tab_id, $rel_tab_id, $actions = false)
    {
        global $log, $singlepane_view, $currentModule, $current_user;
        $log->debug("Entering get_activities(" . $id . ") method ...");
        $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/Serial.php");
        $other = new Serial();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);

        $parenttab = getParentTab();

        if ($singlepane_view == 'true')
            $returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
        else
            $returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

        $button = '';

        $button .= '<input type="hidden" name="serial_mode">';

        if ($actions) {
            if (is_string($actions)) $actions = explode(',', strtoupper($actions));
            if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
                $button .= "<input title='" . getTranslatedString('LBL_NEW') . " " . getTranslatedString('LBL_TODO', $related_module) . "' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.serial_mode.value=\"Task\";' type='submit' name='button'" .
                    " value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString('LBL_TODO', $related_module) . "'>&nbsp;";
            }
        }

        $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, aicrm_contactdetails.contactid, aicrm_contactdetails.lastname, aicrm_contactdetails.firstname, aicrm_serial.*,aicrm_seserialrel.*,aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime,aicrm_recurringevents.recurringtype from aicrm_serial inner join aicrm_seserialrel on aicrm_seserialrel.serialid=aicrm_serial.serialid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_serial.serialid left join aicrm_cntserialrel on aicrm_cntserialrel.serialid= aicrm_serial.serialid left join aicrm_contactdetails on aicrm_contactdetails.contactid = aicrm_cntserialrel.contactid left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid left outer join aicrm_recurringevents on aicrm_recurringevents.serialid=aicrm_serial.serialid left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid where aicrm_seserialrel.crmid=" . $id . " and aicrm_crmentity.deleted=0 and serialtype='Task' and (aicrm_serial.status is not NULL and aicrm_serial.status != 'Completed') and (aicrm_serial.status is not NULL and aicrm_serial.status != 'Deferred')";

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_activities method ...");
        return $return_value;
    }

    /*
     * Function to get the secondary query part of a report
     * @param - $module primary module name
     * @param - $secmodule secondary module name
     * returns the query string formed on fetching the related data for report for secondary module
     */
    function generateReportsSecQuery($module, $secmodule)
    {
        $query = $this->getRelationQuery($module, $secmodule, "aicrm_serial", "serialid");
        $query .= " LEFT JOIN aicrm_crmentity AS aicrm_crmentitySerial ON aicrm_crmentitySerial.crmid = aicrm_serial.serialid
				   AND aicrm_crmentitySerial.deleted = 0
				   LEFT JOIN aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_crmentitySerial.crmid
				   LEFT JOIN aicrm_groups AS aicrm_groupsSerial ON aicrm_groupsSerial.groupid = aicrm_crmentitySerial.smownerid
				   LEFT JOIN aicrm_users AS aicrm_usersSerial ON aicrm_usersSerial.id = aicrm_crmentitySerial.smownerid
				   LEFT JOIN aicrm_account AS aicrm_accountSerial on aicrm_accountSerial.accountid = aicrm_serial.accountid
				   left join aicrm_users as aicrm_usersModifiedSerial on aicrm_crmentitySerial.smcreatorid=aicrm_usersModifiedSerial.id
                   left join aicrm_users as aicrm_usersCreatorSerial on aicrm_crmentitySerial.smcreatorid=aicrm_usersCreatorSerial.id";
        if (($module == "Accounts" && $secmodule == "Serial") || ($module == "Products" && $secmodule == "Serial")) {
            $query .= " INNER JOIN aicrm_products as aicrm_productsSerial on aicrm_productsSerial.productid =aicrm_serial.product_id ";
        }
        return $query;
    }

    function create_export_query($where)
    {
        global $log;
        global $current_user;
        $log->debug("Entering create_export_query(" . $where . ") method ...");

        include("include/utils/ExportUtils.php");

        //To get the Permitted fields query and the permitted fields list
        $sql = getPermittedFieldsQuery("Serial", "detail_view");
        $fields_list = getFieldsListFromQuery($sql);
        $query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_serial ON aicrm_serial.serialid = aicrm_crmentity.crmid
				INNER JOIN aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_serial.serialid
				LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_serial.accountid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_serial.product_id
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
               	LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				";
        $where_auto = " aicrm_crmentity.deleted = 0 ";

        if ($where != "")
            $query .= " WHERE ($where) AND " . $where_auto;
        else
            $query .= " WHERE " . $where_auto;

        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        require('user_privileges/sharing_privileges_' . $current_user->id . '.php');
        //we should add security check when the user has Private Access
        if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3) {
            //Added security check to get the permitted records only
            $query = $query . " " . getListViewSecurityParameter("Serial");
        }
        $log->debug("Exiting create_export_query method ...");
        return $query;
    }

    /*
     * Function to get the relation tables for related modules
     * @param - $secmodule secondary module name
     * returns the array with table names and fieldnames storing relations between module and this module
     */
    function setRelationTables($secmodule)
    {
        //echo $secmodule;exit;
        $rel_tables = array(
            "Documents" => array("aicrm_senotesrel" => array("crmid", "notesid"), "aicrm_serial" => "serialid"),
            "Job" => array("aicrm_crmentityrel" => array("crmid", "relcrmid"), "aicrm_serial" => "serialid"),
        );
        return $rel_tables[$secmodule];
    }

    // Function to unlink an entity with given Id from another entity
    function unlinkRelationship($id, $return_module, $return_id)
    {
        global $log;
        if (empty($return_module) || empty($return_id)) return;

        if ($return_module == 'Potentials') {
            $relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
            $this->db->pquery($relation_query, array($id));
        } elseif ($return_module == 'Accounts') {
            $this->trash('Serial', $id);
        } elseif ($return_module == 'Contacts') {
            $relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
            $this->db->pquery($relation_query, array($id));
        } elseif ($return_module == 'SerialList') {
            $relation_query = 'DELETE FROM aicrm_seriallist_serialrel WHERE serialid =? AND  seriallistid=?';
            $this->db->pquery($relation_query, array($id, $return_id));
        }
        if ($return_module == 'Products') {
            $relation_query = 'UPDATE aicrm_serial SET product_id=0 WHERE serialid=?';
            $this->db->pquery($relation_query, array($id));
        } else {
            $sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
            $params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
            $this->db->pquery($sql, $params);
        }
    }

}

?>
