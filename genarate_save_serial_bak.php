<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
include_once('config.php');
include_once("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$data = $_REQUEST['data'][0];
$serial_no = $data['serial_no'];
$serial_name = $data['serial_name'];
$productid = $data['productid'];
$accountid = $data['accountid'];

$serial_date_install = $data['serial_date_install'] == '' ? '' : convertDate($data['serial_date_install']);
$warranty_active_date = $data['warranty_active_date'] == '' ? '' : convertDate($data['warranty_active_date']);
$warranty_expired_date = $data['warranty_expired_date'] == '' ? '' : convertDate($data['warranty_expired_date']);
$pm_time_no = $data['pm_time_no'];
$time_cal_no = $data['time_cal_no'];
$around_pm = $data['around_pm'];
$around_cal = $data['around_cal'];
$assigntype = $data['assigntype'];

$assigned_user_id = $data['assigned_user_id'];
$assigned_group_id = $data['assigned_group_id'];
$assignID = $assigntype == 'U' ? $assigned_user_id : $assigned_group_id;

// echo  date("Y-m-d");exit;
$new_date = date("d-m-Y");

if ($accountid != '' && $productid != '') {

    if ($warranty_active_date != '') {

        $month_diff = '';
        
        if ($warranty_expired_date != '' && $warranty_active_date != '') {

            $month_diff = get_month_diff($warranty_expired_date, $warranty_active_date);
            echo $month_diff; exit;
            require_once('modules/Serial/Serial.php');
            $serial_focus = new Serial();

            $serial_focus->column_fields['account_id'] = $accountid;
            $serial_focus->column_fields['product_id'] = $productid;
            $serial_focus->column_fields['serial_name'] = $serial_name;
            $serial_focus->column_fields['serial_date_install'] = $serial_date_install;
            $serial_focus->column_fields['warranty_active_date'] = $warranty_active_date;
            $serial_focus->column_fields['warranty_expired_date'] = $warranty_expired_date;
            $serial_focus->column_fields['pm_time_no'] = $pm_time_no;
            $serial_focus->column_fields['time_cal_no'] = $time_cal_no;
            $serial_focus->column_fields['around_pm'] = $around_pm;
            $serial_focus->column_fields['around_cal'] = $around_cal;

            $_REQUEST["module"] = "Serial";

            $serial_focus->mode = "";
            $serial_focus->id = "";
            $serial_focus->save("Serial");
            $serialid = $serial_focus->id;

            if (!empty($serialid)) {

                // Insert PM
                /*if (($pm_time_no != '' || $pm_time_no != '--None--') && ($around_pm == '' || $around_pm == '0')) {

                    $numloop = floor($month_diff / $pm_time_no);
                    $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datenow)) . "-" . $pm_time_no . " month"));

                    for ($i = 1; $i <= $numloop; $i++) {

                        $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $pm_time_no . " month"));

                        require_once('modules/Job/Job.php');
                        $job_focus = new Job();

                        $job_focus->column_fields['jobtype'] = 'Preventive Maintenance';

                        $job_focus->column_fields['job_name'] = 'Preventive Maintenance';
                        $job_focus->column_fields['job_status'] = 'Open';
                        $job_focus->column_fields['jobdate_operate'] = $new_date;
                        $job_focus->column_fields['start_time'] = '9:00';
                        $job_focus->column_fields['end_time'] = '18:00';
                        $job_focus->column_fields['assigned_user_id'] = $assignID;
                        $job_focus->column_fields['account_id'] = $serial_focus->column_fields['account_id'];
                        $_REQUEST["module"] = "Job";

                        $job_focus->mode = "";
                        $job_focus->id = "";
                        $job_focus->save("Job");
                        $jobid = $job_focus->id;

                        require_once('modules/Calendar/Activity.php');
                        $act_focus = new Activity();
                        $act_focus->column_fields['activitytype'] = 'Preventive Maintenance';
                        $act_focus->column_fields['date_start'] = $new_date;
                        $act_focus->column_fields['time_start'] = '9:00';
                        $act_focus->column_fields['assigned_user_id'] = $assignID;
                        $act_focus->column_fields['eventstatus'] = 'Plan';
                        $act_focus->column_fields['parentid'] = $serial_focus->column_fields['account_id'];
                        $act_focus->column_fields['event_id'] = $jobid;
                        $act_focus->column_fields['description'] = 'Preventive Maintenance';
                        $_REQUEST["module"] = "Calendar";
                        $act_focus->mode = "";
                        $act_focus->id = "";
                        $act_focus->save("Calendar");

                        $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $serialid . "','Serial')";

                        $myLibrary_mysqli->Query($sql_rel);

                        //Inspection Template
                        $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
                                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
                                INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
                                INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                        $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $productid . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                        $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);

                        if (!empty($inspectiontemplateid)) {
                            require_once('modules/Inspection/Inspection.php');
                            $inspec_focus = new Inspection();
                            $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                            $inspec_focus->column_fields['inspection_status'] = 'Open';
                            $inspec_focus->column_fields['inspec_report_type'] = 'Preventive Maintenance';
                            $inspec_focus->column_fields['jobid'] = $jobid;
                            $inspec_focus->column_fields['serialid'] = $serialid;
                            $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                            $inspec_focus->column_fields['assigned_user_id'] = $assignID;
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
                            $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $productid . ') OR aicrm_crmentityrel.relcrmid IN (' . $productid . ')) and aicrm_crmentity.deleted = 0 ';
                            $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                            foreach ($data_toolsid as $key => $value) {
                                $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                $myLibrary_mysqli->Query($ins_tool);
                            }
                        }
                    }
                }*/

                //Insert around pm
                if ($around_pm != '' && $around_pm != '0') {

                    $numloop = floor($month_diff / $around_pm);
                    echo $numloop; exit;
                    $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($new_date)) . "-" . $numloop . " month"));

                    for ($i = 1; $i <= $around_pm; $i++) {

                        $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $numloop . " month"));

                        require_once('modules/Job/Job.php');
                        $job_focus = new Job();

                        $job_focus->column_fields['jobtype'] = 'Preventive Maintenance';

                        $job_focus->column_fields['job_name'] = 'Preventive Maintenance';
                        $job_focus->column_fields['job_status'] = 'Open';
                        $job_focus->column_fields['jobdate_operate'] = $new_date;
                        $job_focus->column_fields['start_time'] = '9:00';
                        $job_focus->column_fields['end_time'] = '18:00';
                        $job_focus->column_fields['assigned_user_id'] = $assignID;
                        $job_focus->column_fields['account_id'] = $serial_focus->column_fields['account_id'];
                        $_REQUEST["module"] = "Job";

                        $job_focus->mode = "";
                        $job_focus->id = "";
                        $job_focus->save("Job");
                        $jobid = $job_focus->id;

                        /*require_once('modules/Calendar/Activity.php');
                        $act_focus = new Activity();
                        $act_focus->column_fields['activitytype'] = 'Preventive Maintenance';
                        $act_focus->column_fields['date_start'] = $new_date;
                        $act_focus->column_fields['time_start'] = '9:00';
                        $act_focus->column_fields['assigned_user_id'] = $assignID;
                        $act_focus->column_fields['eventstatus'] = 'Plan';
                        $act_focus->column_fields['parentid'] = $serial_focus->column_fields['account_id'];
                        $act_focus->column_fields['event_id'] = $jobid;
                        $act_focus->column_fields['description'] = 'Preventive Maintenance';
                        $_REQUEST["module"] = "Calendar";
                        $act_focus->mode = "";
                        $act_focus->id = "";
                        $act_focus->save("Calendar");

                        $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $serialid . "','Serial')";

                        $myLibrary_mysqli->Query($sql_rel);

                        //Inspection Template
                        $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
                                        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
                                        INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
                                        INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                        $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $productid . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                        $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);*/

                        /*if (!empty($inspectiontemplateid)) {
                            require_once('modules/Inspection/Inspection.php');
                            $inspec_focus = new Inspection();
                            $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                            $inspec_focus->column_fields['inspection_status'] = 'Open';
                            $inspec_focus->column_fields['inspec_report_type'] = 'Preventive Maintenance';
                            $inspec_focus->column_fields['jobid'] = $jobid;
                            $inspec_focus->column_fields['serialid'] = $serialid;
                            $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                            $inspec_focus->column_fields['assigned_user_id'] = $assignID;
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
                            $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $productid . ') OR aicrm_crmentityrel.relcrmid IN (' . $productid . ')) and aicrm_crmentity.deleted = 0 ';
                            $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                            foreach ($data_toolsid as $key => $value) {
                                $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                $myLibrary_mysqli->Query($ins_tool);
                            }
                        }*/


                        if (strtotime($new_date) >= strtotime($warranty_expired_date)) {
                            //break;
                        }
                    }
                    exit;

                    //exit;
                }

                //Insert Calibration
                /*if (($time_cal_no != '' || $time_cal_no != '--None--') && ($around_cal == '' || $around_cal == '0')) {

                    $numloop = floor($month_diff / $time_cal_no);
                    $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datenow)) . "-" . $time_cal_no . " month"));

                    for ($i = 1; $i <= $numloop; $i++) {

                        $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $time_cal_no . " month"));
                        require_once('modules/Job/Job.php');
                        $job_focus = new Job();

                        $job_focus->column_fields['jobtype'] = 'Calibration';
                        $job_focus->column_fields['job_name'] = 'Calibration';
                        $job_focus->column_fields['job_status'] = 'Open';
                        $job_focus->column_fields['jobdate_operate'] = $new_date;
                        $job_focus->column_fields['start_time'] = '9:00';
                        $job_focus->column_fields['end_time'] = '18:00';
                        $job_focus->column_fields['assigned_user_id'] = $assignID;
                        $job_focus->column_fields['account_id'] = $serial_focus->column_fields['account_id'];
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
                        $act_focus->column_fields['assigned_user_id'] = $assignID;
                        $act_focus->column_fields['eventstatus'] = 'Plan';
                        $act_focus->column_fields['parentid'] = $serial_focus->column_fields['account_id'];
                        $act_focus->column_fields['event_id'] = $jobid;
                        $act_focus->column_fields['description'] = 'Calibration';
                        $_REQUEST["module"] = "Calendar";
                        $act_focus->mode = "";
                        $act_focus->id = "";
                        $act_focus->save("Calendar");

                        $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $serialid . "','Serial')";

                        $myLibrary_mysqli->Query($sql_rel);

                        //Inspection Template
                        $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
                                        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
                                        INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
                                        INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                        $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $productid . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                        $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);

                        if (!empty($inspectiontemplateid)) {
                            require_once('modules/Inspection/Inspection.php');
                            $inspec_focus = new Inspection();
                            $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                            $inspec_focus->column_fields['inspection_status'] = 'Open';
                            $inspec_focus->column_fields['inspec_report_type'] = 'Calibration';
                            $inspec_focus->column_fields['jobid'] = $jobid;
                            $inspec_focus->column_fields['serialid'] = $serialid;
                            $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                            $inspec_focus->column_fields['assigned_user_id'] = $assignID;
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
                            $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $productid . ') OR aicrm_crmentityrel.relcrmid IN (' . $productid . ')) and aicrm_crmentity.deleted = 0 ';
                            $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                            foreach ($data_toolsid as $key => $value) {
                                $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                $myLibrary_mysqli->Query($ins_tool);
                            }
                        }
                    }
                }

                if ($around_cal != '' && $around_cal != '0') {

                    $numloop = floor($month_diff / $around_cal);
                    $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($datenow)) . "-" . $numloop . " month"));

                    for ($i = 1; $i <= $around_cal; $i++) {

                        $new_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($new_date)) . "+" . $numloop . " month"));
                        require_once('modules/Job/Job.php');
                        $job_focus = new Job();

                        $job_focus->column_fields['jobtype'] = 'Calibration';

                        $job_focus->column_fields['job_name'] = 'Calibration';
                        $job_focus->column_fields['job_status'] = 'Open';
                        $job_focus->column_fields['jobdate_operate'] = $new_date;
                        $job_focus->column_fields['start_time'] = '9:00';
                        $job_focus->column_fields['end_time'] = '18:00';
                        $job_focus->column_fields['assigned_user_id'] = $assignID;
                        $job_focus->column_fields['account_id'] = $serial_focus->column_fields['account_id'];
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
                        $act_focus->column_fields['assigned_user_id'] = $assignID;
                        $act_focus->column_fields['eventstatus'] = 'Plan';
                        $act_focus->column_fields['parentid'] = $serial_focus->column_fields['account_id'];
                        $act_focus->column_fields['event_id'] = $jobid;
                        $act_focus->column_fields['description'] = 'Calibration';
                        $_REQUEST["module"] = "Calendar";
                        $act_focus->mode = "";
                        $act_focus->id = "";
                        $act_focus->save("Calendar");

                        $sql_rel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $jobid . "','Job','" . $serialid . "','Serial')";

                        $myLibrary_mysqli->Query($sql_rel);

                        //Inspection Template
                        $select_inspectiontemplateid = "select aicrm_inspectiontemplate.inspectiontemplateid , aicrm_inspectiontemplate.inspectiontemplate_name from aicrm_products
                                        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
                                        INNER JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
                                        INNER JOIN aicrm_crmentity crminspect ON crminspect.crmid = aicrm_inspectiontemplate.inspectiontemplateid";
                        $select_inspectiontemplateid .= ' WHERE aicrm_products.productid IN (' . $productid . ') AND crminspect.deleted = 0 AND aicrm_crmentity.deleted = 0';
                        $inspectiontemplateid = $myLibrary_mysqli->select($select_inspectiontemplateid);

                        if (!empty($inspectiontemplateid)) {
                            require_once('modules/Inspection/Inspection.php');
                            $inspec_focus = new Inspection();
                            $inspec_focus->column_fields['inspectiontemplateid'] = $inspectiontemplateid[0]['inspectiontemplateid'];
                            $inspec_focus->column_fields['inspection_status'] = 'Open';
                            $inspec_focus->column_fields['inspec_report_type'] = 'Calibration';
                            $inspec_focus->column_fields['jobid'] = $jobid;
                            $inspec_focus->column_fields['serialid'] = $serialid;
                            $inspec_focus->column_fields['inspection_name'] = $inspectiontemplateid[0]['inspectiontemplate_name'];
                            $inspec_focus->column_fields['assigned_user_id'] = $assignID;
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
                            $sql_select_toolsid .= 'WHERE  (aicrm_crmentityrel.crmid IN (' . $productid . ') OR aicrm_crmentityrel.relcrmid IN (' . $productid . ')) and aicrm_crmentity.deleted = 0 ';
                            $data_toolsid = $myLibrary_mysqli->select($sql_select_toolsid);

                            foreach ($data_toolsid as $key => $value) {
                                $ins_tool = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES ('" . $inspectionid . "','Inspection','" . $value['toolsid'] . "','Tools')";
                                $myLibrary_mysqli->Query($ins_tool);
                            }
                        }
                        
                        if (strtotime($new_date) >= strtotime($warranty_expired_date)) {
                            
                            //break;
                        
                        }
                    }
                }*/
            }
        }
    }
}


$result_data['status'] = true;

echo json_encode($result_data);

function convertDate($date)
{
    $n_date = explode('-', $date);

    $dd = $n_date[0];
    $mm = $n_date[1];
    $yy = $n_date[2];

    $Ndate = $yy . '-' . $mm . '-' . $dd;
    return $Ndate;
}
function get_month_diff($start, $end)
{

    $temp_start_date = (explode("-", $start));
    $temp_end_date = (explode("-", $end));

    $End = mktime(0, 0, 0, $temp_start_date[1], $temp_start_date[0], $temp_start_date[2]);
    $Start = mktime(0, 0, 0, $temp_end_date[1], $temp_end_date[0], $temp_end_date[2]);

    $reurn_date = round(($End - $Start) / 60 / 60 / 24 / 30);
    return $reurn_date;
}
