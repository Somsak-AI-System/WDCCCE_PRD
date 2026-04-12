<?php
ini_set('memory_limit', '-1'); 
/*+********************************************************************************
 * The contents of this file are subject to the aicrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  aicrm CRM Open Source
 * The Initial Developer of the Original Code is aicrm.
 * Portions created by aicrm are Copyright (C) aicrm.
 * All Rights Reserved.
 ********************************************************************************/
global $calpath;
global $app_strings, $mod_strings;
global $theme;
global $log;

$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
require_once('include/database/PearDatabase.php');
require_once('data/CRMEntity.php');
require_once("modules/Reports/Reports.php");

class ReportRun extends CRMEntity
{
    var $primarymodule;
    var $secondarymodule;
    var $orderbylistsql;
    var $orderbylistcolumns;
    var $selectcolumns;
    var $groupbylist;
    var $reporttype;
    var $reportname;
    var $totallist;
    var $_groupinglist = false;
    var $_columnslist = false;
    var $_stdfilterlist = false;
    var $_columnstotallist = false;
    var $_advfilterlist = false;
    var $convert_currency = array('Potentials_Amount', 'Accounts_Annual_Revenue', 'Leads_Annual_Revenue', 'Campaigns_Budget_Cost',
        'Campaigns_Actual_Cost', 'Campaigns_Expected_Revenue', 'Campaigns_Actual_ROI', 'Campaigns_Expected_ROI');

    var $append_currency_symbol_to_value = array('Products_Unit_Price', 'Services_Price',
        'Invoice_Total', 'Invoice_Sub_Total', 'Invoice_S&H_Amount', 'Invoice_Discount_Amount', 'Invoice_Adjustment',
        'Quotes_Total', 'Quotes_Sub_Total', 'Quotes_S&H_Amount', 'Quotes_Discount_Amount', 'Quotes_Adjustment',
        'SalesOrder_Total', 'SalesOrder_Sub_Total', 'SalesOrder_S&H_Amount', 'SalesOrder_Discount_Amount', 'SalesOrder_Adjustment',
        'PurchaseOrder_Total', 'PurchaseOrder_Sub_Total', 'PurchaseOrder_S&H_Amount', 'PurchaseOrder_Discount_Amount', 'PurchaseOrder_Adjustment'
    );

    /** Function to set reportid,primarymodule,secondarymodule,reporttype,reportname, for given reportid
     *  This function accepts the $reportid as argument
     *  It sets reportid,primarymodule,secondarymodule,reporttype,reportname for the given reportid
     */

    function ReportRun($reportid)
    {
        $oReport = new Reports($reportid);
        $this->reportid = $reportid;
        $this->primarymodule = $oReport->primodule;
        $this->secondarymodule = $oReport->secmodule;
        $this->reporttype = $oReport->reporttype;
        $this->reportname = $oReport->reportname;
    }

    /** Function to get the columns for the reportid
     *  This function accepts the $reportid
     *  This function returns  $columnslist Array($tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname As Header value,
     *                          $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 As Header value,
     *                                            |
     *                          $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen As Header value
     *                             )
     *
     */
    function getQueryColumnsList($reportid)
    {
        // Have we initialized information already?
        if ($this->_columnslist !== false) {
            return $this->_columnslist;
        }

        global $adb;
        global $modules;
        global $log, $current_user, $current_language;
        $ssql = "select aicrm_selectcolumn.* from aicrm_report inner join aicrm_selectquery on aicrm_selectquery.queryid = aicrm_report.queryid";
        $ssql .= " left join aicrm_selectcolumn on aicrm_selectcolumn.queryid = aicrm_selectquery.queryid";
        $ssql .= " where aicrm_report.reportid = ?";
        $ssql .= " order by aicrm_selectcolumn.columnindex"; //echo $ssql; echo $reportid;
        $result = $adb->pquery($ssql, array($reportid));
        $permitted_fields = Array();

        while ($columnslistrow = $adb->fetch_array($result)) {
            $fieldname = "";
            $fieldcolname = $columnslistrow["columnname"];
            list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldcolname);
            list($module, $field) = split("_", $module_field);
            require('user_privileges/user_privileges_' . $current_user->id . '.php');
            if (sizeof($permitted_fields) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1) {
                list($module, $field) = split("_", $module_field);
                $permitted_fields = $this->getaccesfield($module);
            }
            $selectedfields = explode(":", $fieldcolname);
            $querycolumns = $this->getEscapedColumns($selectedfields);

            $mod_strings = return_module_language($current_language, $module);
            $fieldlabel = trim(str_replace($module, " ", $selectedfields[2]));
            $mod_arr = explode('_', $fieldlabel);
            $mod = ($mod_arr[0] == '') ? $module : $mod_arr[0];
            $fieldlabel = trim(str_replace("_", " ", $fieldlabel));
            //modified code to support in issue
            $fld_arr = explode(" ", $fieldlabel);
            $mod_lbl = getTranslatedString($fld_arr[0], $module); //module
            array_shift($fld_arr);
            $fld_lbl_str = implode(" ", $fld_arr);
            $fld_lbl = getTranslatedString($fld_lbl_str, $module); //fieldlabel
            $fieldlabel = $mod_lbl . " " . $fld_lbl;
            if (CheckFieldPermission($fieldname, $mod) != 'true' && $colname != "crmid") {
                continue;
            } else {
                $header_label = $selectedfields[2]; // Header label to be displayed in the reports table
                // To check if the field in the report is a custom field
                // and if yes, get the label of this custom field freshly from the aicrm_field as it would have been changed.
                // Asha - Reference ticket : #4906
                if ($querycolumns == "") {
                    // echo '<pre>'; print_r($selectedfields); echo '</pre>';
                    if ($selectedfields[4] == 'C') {
                        $field_label_data = split("_", $selectedfields[2]);
                        $module = $field_label_data[0];
                        if ($module != $this->primarymodule)
                            $columnslist[$fieldcolname] = "case when (" . $selectedfields[0] . "." . $selectedfields[1] . "='1')then 'yes' else case when (aicrm_crmentity$module.crmid !='') then 'no' else '-' end end as '$selectedfields[2]'";
                        else
                            $columnslist[$fieldcolname] = "case when (" . $selectedfields[0] . "." . $selectedfields[1] . "='1')then 'yes' else case when (aicrm_crmentity.crmid !='') then 'no' else '-' end end as '$selectedfields[2]'";

                    }elseif($module_field == 'Calendar_Event_Name'){

                        $columnslist[$fieldcolname] = "
                        (
                        case
                        when aicrm_projectsCalendar.projects_name !='' then aicrm_projectsCalendar.projects_name
                        when aicrm_jobsCalendar.job_name !='' then aicrm_jobsCalendar.job_name
                        when aicrm_troubleticketsCalendar.ticket_no !='' then aicrm_troubleticketsCalendar.ticket_no
                        else ''
                        end
                        ) as
                        ".$selectedfields[2];
                        
                    } elseif ($selectedfields[0] == 'aicrm_serialTools' && $selectedfields[1] == 'serial_no') {
                        $columnslist[$fieldcolname] = "aicrm_tools.tools_serial AS '" . $selectedfields[2] . "'";

                    } elseif ($selectedfields[0] == 'aicrm_user2role' && $selectedfields[1] == 'roleid') {
                        $columnslist[$fieldcolname] = "aicrm_roleUsers.rolename AS '" . $selectedfields[2] . "'";
                    } elseif ($selectedfields[0] == 'aicrm_activity' && $selectedfields[1] == 'status') {
                        $columnslist[$fieldcolname] = " case when (aicrm_activity.status not like '') then aicrm_activity.status else aicrm_activity.eventstatus end as Calendar_Status";
                    } elseif ($selectedfields[0] == 'aicrm_activity' && $selectedfields[1] == 'date_start') {
                        $columnslist[$fieldcolname] = "concat(aicrm_activity.date_start,'  ',aicrm_activity.time_start) as Calendar_Start_Date_and_Time";
                    }elseif(stristr($selectedfields[0],"aicrm_crmentity") && $selectedfields[1] == 'smcreatorid'){
                        $columnslist[$fieldcolname] = " case when (aicrm_usersCreator".$module.".user_name not like '' $condition) then concat(aicrm_usersCreator".$module.".first_name,' ',aicrm_usersCreator".$module.".last_name) end as ". $selectedfields[2];

                    }elseif(stristr($selectedfields[0],"aicrm_crmentity") && $selectedfields[1] == 'modifiedby'){
                        $columnslist[$fieldcolname] = " case when (aicrm_usersModified".$module.".user_name not like '' $condition) then concat(aicrm_usersModified".$module.".first_name,' ',aicrm_usersModified".$module.".last_name) end as ". $selectedfields[2];

                    }elseif($selectedfields[1] == 'dealid' && $module != 'Deal'){
                        $columnslist[$fieldcolname] = "aicrm_deal".$module.".deal_no AS " . $selectedfields[2];

                    }elseif($selectedfields[1] == 'campaignid' && $module != 'Campaigns'){
                        $columnslist[$fieldcolname] = "aicrm_campaign".$module.".campaignname AS " . $selectedfields[2];

                    }elseif($selectedfields[1] == 'parentid' && $module == 'Calendar'){
                        $columnslist[$fieldcolname] = "case when (aicrm_account".$module.".accountid != '' ) then aicrm_account".$module.".accountname when (aicrm_leaddetails".$module.".leadid != '') then concat(aicrm_leaddetails".$module.".firstname,' ',aicrm_leaddetails".$module.".lastname) else '' end as ". $selectedfields[2] ;

                    }elseif($selectedfields[1] == 'lastname' && $module == 'Job'){
                        $columnslist[$fieldcolname] = "case when (aicrm_contactdetails".$module.".contactid != '') then concat(aicrm_contactdetails".$module.".firstname,' ',aicrm_contactdetails".$module.".lastname) else '' end as ". $selectedfields[2] ;

                    }elseif (stristr($selectedfields[0], "aicrm_users") && ($selectedfields[1] == 'user_name') && $module_field != 'Products_Handler' && $module_field != 'Services_Owner') { //echo $selectedfields[1];
                        $temp_module_from_tablename = str_replace("aicrm_users", "", $selectedfields[0]);
                        //echo $selectedfields[0];
                        if ($module != $this->primarymodule) {
                            $condition = "and aicrm_crmentity" . $module . ".crmid!=''";
                        } else {
                            $condition = "and aicrm_crmentity.crmid!=''";
                        }
                        if ($temp_module_from_tablename == $module)
                            $columnslist[$fieldcolname] = " case when (" . $selectedfields[0] .".user_name not like '' $condition) then concat(" . $selectedfields[0] . ".first_name,' ',". $selectedfields[0].".last_name) else aicrm_groups" . $module . ".groupname end as " . $module . "_Assigned_To";
                        else//Some Fields can't assigned to groups so case avoided (fields like inventory manager)
                        $columnslist[$fieldcolname] = $selectedfields[0] . ".user_name as '" . $header_label . "'";

                    } elseif (stristr($selectedfields[0], "aicrm_users") && ($selectedfields[1] == 'user_name') && $module_field == 'Products_Handler')//Products cannot be assiged to group only to handler so group is not included
                    {
                        $columnslist[$fieldcolname] = $selectedfields[0] . ".user_name as " . $module . "_Handler";
                    } elseif ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule) {
                        $columnslist[$fieldcolname] = "aicrm_crmentity." . $selectedfields[1] . " AS '" . $header_label . "'";
                    } elseif ($selectedfields[0] == 'aicrm_invoice' && $selectedfields[1] == 'salesorderid')//handled for salesorder fields in Invoice Module Reports
                    {
                        $columnslist[$fieldcolname] = 'aicrm_salesorderInvoice.subject	AS "' . $selectedfields[2] . '"';
                    } elseif ($selectedfields[0] == 'aicrm_campaign' && $selectedfields[1] == 'product_id'){
                        $columnslist[$fieldcolname] = 'aicrm_productsCampaigns.productname AS "' . $header_label . '"';
                    } elseif ($selectedfields[0] == 'aicrm_deal' && $selectedfields[1] == 'product_id'){
                        $columnslist[$fieldcolname] = 'aicrm_productsDeal.productname AS "' . $header_label . '"';
                    
                    // } elseif ($selectedfields[0] == 'aicrm_leaddetails' && $selectedfields[1] == 'new_customer'){
                    //     echo 555; exit;
                    //     $columnslist[$fieldcolname] = 'case when aicrm_leaddetails.new_customer = \'1\' then \'ลูกค้าใหม่\' else \'ลูกค้าเก่า\' end  AS "' . $header_label . '"';

                    } else if ($selectedfields[0] == 'aicrm_contactdetailsProjects' && $selectedfields[1] == 'lastname') {
                        $columnslist[$fieldcolname] = 'CONCAT(' . $selectedfields[0] . '.firstname, " ", ' . $selectedfields[0] . '.lastname) AS "Projects_Key_Contact_Person"';
                    
                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'account_id1') {
                        $columnslist[$fieldcolname] = 'aicrm_account1Projects.accountname AS "' . $header_label . '"';
                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'account_id2') {
                        $columnslist[$fieldcolname] = 'aicrm_account2Projects.accountname AS "' . $header_label . '"';

                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'contact_id1') {
                        $columnslist[$fieldcolname] = 'aicrm_contactdetails1Projects.contactname AS "' . $header_label . '"';
                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'contact_id2') {
                        $columnslist[$fieldcolname] = 'aicrm_contactdetails2Projects.contactname AS "' . $header_label . '"';
                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'contact_id3') {
                        $columnslist[$fieldcolname] = 'aicrm_contactdetails3Projects.contactname AS "' . $header_label . '"';
                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'contact_id4') {
                        $columnslist[$fieldcolname] = 'aicrm_contactdetails4Projects.contactname AS "' . $header_label . '"';

                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'contactid1') {
                        $columnslist[$fieldcolname] = 'aicrm_contactdetails5Projects.contactname AS "' . $header_label . '"';
                    } elseif ($selectedfields[0] == 'aicrm_projects' && $selectedfields[1] == 'contactid2') {
                        $columnslist[$fieldcolname] = 'aicrm_contactdetails6Projects.contactname AS "' . $header_label . '"';

                    } elseif (in_array($selectedfields[2], $this->append_currency_symbol_to_value)) {
                        $columnslist[$fieldcolname] = 'concat(' . $selectedfields[0] . '.currency_id,"::",' . $selectedfields[0] . '.' . $selectedfields[1] . ') as "' . $header_label . '"';
                    } elseif ($selectedfields[0] == 'aicrm_notes' && ($selectedfields[1] == 'filelocationtype' || $selectedfields[1] == 'filesize' || $selectedfields[1] == 'folderid' || $selectedfields[1] == 'filestatus'))//handled for product fields in Campaigns Module Reports
                    {
                        if ($selectedfields[1] == 'filelocationtype') {
                            $columnslist[$fieldcolname] = "case " . $selectedfields[0] . "." . $selectedfields[1] . " when 'I' then 'Internal' when 'E' then 'External' else '-' end as '$selectedfields[2]'";
                        } else if ($selectedfields[1] == 'folderid') {
                            $columnslist[$fieldcolname] = "aicrm_attachmentsfolder.foldername as '$selectedfields[2]'";
                        } elseif ($selectedfields[1] == 'filestatus') {
                            $columnslist[$fieldcolname] = "case " . $selectedfields[0] . "." . $selectedfields[1] . " when '1' then 'yes' when '0' then 'no' else '-' end as '$selectedfields[2]'";
                        } elseif ($selectedfields[1] == 'filesize') {
                            $columnslist[$fieldcolname] = "case " . $selectedfields[0] . "." . $selectedfields[1] . " when '' then '-'else concat(" . $selectedfields[0] . "." . $selectedfields[1] . "/1024,'  ','KB') end as '$selectedfields[2]'";
                        }
                    } elseif ($selectedfields[0] == 'aicrm_potential' && $selectedfields[1] == 'related_to') {
                        $columnslist[$fieldcolname] = "case when aicrm_accountPotentials.accountid is not NULL then aicrm_accountPotentials.accountname else concat(aicrm_contactdetailsPotentials.lastname, ' ', aicrm_contactdetailsPotentials.firstname) end as '$selectedfields[2]'";
                    } else if ($selectedfields[0] == 'aicrm_projects' && ($selectedfields[1] == 'secondary_contact_person')) {
                        $columnslist[$fieldcolname] = "(SELECT CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) FROM aicrm_contactdetails WHERE contactid = aicrm_projects.secondary_contact_person) AS '$selectedfields[2]'";
                    } else if ($selectedfields[0] == 'aicrm_projects' && ($selectedfields[1] == 'category_manager_name')) {
                        $columnslist[$fieldcolname] = "(SELECT CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) FROM aicrm_contactdetails WHERE contactid = aicrm_projects.category_manager_name) AS '$selectedfields[2]'";
                    } elseif (stristr($selectedfields[1], 'cf_') == true && stripos($selectedfields[1], 'cf_') == 0) {
                        if (!in_array($selectedfields[1], array('cf_4305', 'cf_3543'))) {
                            $columnslist[$fieldcolname] = $selectedfields[0] . "." . $selectedfields[1] . ' AS "' . $adb->sql_escape_string(decode_html($header_label)) . '"';
                        }
                    } else {
                        $columnslist[$fieldcolname] = $selectedfields[0] . "." . $selectedfields[1] . ' AS "' . $header_label . '"';
                    }
                } else {
                    $columnslist[$fieldcolname] = $querycolumns;
                }
            }
        }
        // Save the information
        $this->_columnslist = $columnslist;
        $log->info("ReportRun :: Successfully returned getQueryColumnsList" . $reportid);
        return $columnslist;
    }

    /** Function to get field columns based on profile
     *  @ param $module : Type string
     *  returns permitted fields in array format
     */
    function getaccesfield($module)
    {
        global $current_user;
        global $adb;
        $access_fields = Array();

        $profileList = getCurrentUserProfileList();
        $query = "select aicrm_field.fieldname from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where";
        $params = array();
        if ($module == "Calendar") {
            if (count($profileList) > 0) {
                $query .= " aicrm_field.tabid in (16) and aicrm_field.displaytype in (1,2,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ") group by aicrm_field.fieldid order by block,sequence";
                array_push($params, $profileList);
            } else {
                $query .= " aicrm_field.tabid in (16) and aicrm_field.displaytype in (1,2,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 group by aicrm_field.fieldid order by block,sequence";
            }
        } else {
            array_push($params, $this->primarymodule, $this->secondarymodule);
            if (count($profileList) > 0) {
                $query .= " aicrm_field.tabid in (select tabid from aicrm_tab where aicrm_tab.name in (?,?)) and aicrm_field.displaytype in (1,2,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ") group by aicrm_field.fieldid order by block,sequence";
                array_push($params, $profileList);
            } else {
                $query .= " aicrm_field.tabid in (select tabid from aicrm_tab where aicrm_tab.name in (?,?)) and aicrm_field.displaytype in (1,2,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 group by aicrm_field.fieldid order by block,sequence";
            }
        }
        $result = $adb->pquery($query, $params);

        while ($collistrow = $adb->fetch_array($result)) {
            $access_fields[] = $collistrow["fieldname"];
        }
        //added to include ticketid for Reports module in select columnlist for all users
        if ($module == "HelpDesk")
            $access_fields[] = "ticketid";
        return $access_fields;
    }

    /** Function to get Escapedcolumns for the field in case of multiple parents
     *  @ param $selectedfields : Type Array
     *  returns the case query for the escaped columns
     */
    function getEscapedColumns($selectedfields)
    {
        global $current_user, $adb;
        $fieldname = $selectedfields[3];
        $tmp = split("_", $selectedfields[2]);
        $module = $tmp[0];

        if ($fieldname == "parent_id" && ($module == "HelpDesk" || $module == "Calendar")) {
            if ($module == "HelpDesk" && $selectedfields[0] == "aicrm_crmentityRelHelpDesk") {
                $querycolumn = "case aicrm_crmentityRelHelpDesk.setype when 'Accounts' then aicrm_accountRelHelpDesk.accountname when 'Contacts' then concat(aicrm_contactdetailsRelHelpDesk.lastname,' ',aicrm_contactdetailsRelHelpDesk.firstname) End" . " '" . $selectedfields[2] . "', aicrm_crmentityRelHelpDesk.setype 'Entity_type'";
                return $querycolumn;
            }
            if ($module == "Calendar") {
                $querycolumn = "case aicrm_crmentityRelCalendar.setype when 'Accounts' then aicrm_accountRelCalendar.accountname when 'Leads' then concat(aicrm_leaddetailsRelCalendar.lastname,' ',aicrm_leaddetailsRelCalendar.firstname) when 'Potentials' then aicrm_potentialRelCalendar.potentialname when 'Quotes' then aicrm_quotesRelCalendar.subject when 'PurchaseOrder' then aicrm_purchaseorderRelCalendar.subject when 'Invoice' then aicrm_invoiceRelCalendar.subject when 'SalesOrder' then aicrm_salesorderRelCalendar.subject when 'HelpDesk' then aicrm_troubleticketsRelCalendar.title when 'Campaigns' then aicrm_campaignRelCalendar.campaignname End" . " '" . $selectedfields[2] . "', aicrm_crmentityRelCalendar.setype 'Entity_type'";
            }
        } elseif ($fieldname == "contact_id" && strpos($selectedfields[2], "Contact_Name")) {
            if (($this->primarymodule == 'PurchaseOrder' || $this->primarymodule == 'SalesOrder' || $this->primarymodule == 'Quotes' || $this->primarymodule == 'Invoice' || $this->primarymodule == 'Calendar') && $module == $this->primarymodule) {
                if (getFieldVisibilityPermission("Contacts", $current_user->id, "firstname") == '0')
                    $querycolumn = " case when aicrm_crmentity.crmid!='' then concat(aicrm_contactdetails" . $this->primarymodule . ".lastname,' ',aicrm_contactdetails" . $this->primarymodule . ".firstname) else '-' end as " . $selectedfields[2];
                else
                    $querycolumn = " case when aicrm_crmentity.crmid!='' then aicrm_contactdetails" . $this->primarymodule . ".lastname else '-' end as " . $selectedfields[2];
            }
            if (stristr($this->secondarymodule, $module) && ($module == 'Quotes' || $module == 'SalesOrder' || $module == 'PurchaseOrder' || $module == 'Calendar' || $module == 'Invoice')) {
                if (getFieldVisibilityPermission("Contacts", $current_user->id, "firstname") == '0')
                    $querycolumn = " case when aicrm_crmentity" . $module . ".crmid!='' then concat(aicrm_contactdetails" . $module . ".lastname,' ',aicrm_contactdetails" . $module . ".firstname) else '-' end as " . $selectedfields[2];
                else
                    $querycolumn = " case when aicrm_crmentity" . $module . ".crmid!='' then aicrm_contactdetails" . $module . ".lastname else '-' end as " . $selectedfields[2];
            }
        } else {
            if (stristr($selectedfields[0], "aicrm_crmentityRel")) {
                $module = str_replace("aicrm_crmentityRel", "", $selectedfields[0]);
                $fields_query = $adb->pquery("SELECT aicrm_field.fieldname,aicrm_field.tablename,aicrm_field.fieldid from aicrm_field INNER JOIN aicrm_tab on aicrm_tab.name = ? WHERE aicrm_tab.tabid=aicrm_field.tabid and aicrm_field.fieldname=?", array($module, $selectedfields[3]));

                if ($adb->num_rows($fields_query) > 0) {
                    for ($i = 0; $i < $adb->num_rows($fields_query); $i++) {
                        $field_name = $selectedfields[3];
                        $field_id = $adb->query_result($fields_query, $i, 'fieldid');
                        $tab_name = $selectedfields[1];
                        $ui10_modules_query = $adb->pquery("SELECT relmodule FROM aicrm_fieldmodulerel WHERE fieldid=?", array($field_id));

                        if ($adb->num_rows($ui10_modules_query) > 0) {
                            $querycolumn = " case aicrm_crmentityRel$module.setype";
                            for ($j = 0; $j < $adb->num_rows($ui10_modules_query); $j++) {
                                $rel_mod = $adb->query_result($ui10_modules_query, $j, 'relmodule');
                                $rel_obj = CRMEntity::getInstance($rel_mod);
                                vtlib_setup_modulevars($rel_mod, $rel_obj);

                                $rel_tab_name = $rel_obj->table_name;
                                $link_field = $rel_tab_name . "Rel" . $module . "." . $rel_obj->list_link_field;

                                if ($rel_mod == "Contacts" || $rel_mod == "Leads") {
                                    if (getFieldVisibilityPermission($rel_mod, $current_user->id, 'firstname') == 0) {
                                        $link_field = "concat($link_field,' '," . $rel_tab_name . "Rel$module.firstname)";
                                    }
                                }
                                $querycolumn .= " when '$rel_mod' then $link_field ";
                            }
                            $querycolumn .= "end as '" . $selectedfields[2] . "', aicrm_crmentityRel$module.setype as 'Entity_type'";
                        }
                    }
                }

            }
        }
        return $querycolumn;
    }

    /** Function to get selectedcolumns for the given reportid
     *  @ param $reportid : Type Integer
     *  returns the query of columnlist for the selected columns
     */
    function getSelectedColumnsList($reportid)
    {

        global $adb;
        global $modules;
        global $log;

        $ssql = "select aicrm_selectcolumn.* from aicrm_report inner join aicrm_selectquery on aicrm_selectquery.queryid = aicrm_report.queryid";
        $ssql .= " left join aicrm_selectcolumn on aicrm_selectcolumn.queryid = aicrm_selectquery.queryid where aicrm_report.reportid = ? ";
        $ssql .= " order by aicrm_selectcolumn.columnindex";

        $result = $adb->pquery($ssql, array($reportid));
        $noofrows = $adb->num_rows($result);

        if ($this->orderbylistsql != "") {
            $sSQL .= $this->orderbylistsql . ", ";
        }

        for ($i = 0; $i < $noofrows; $i++) {
            $fieldcolname = $adb->query_result($result, $i, "columnname");
            $ordercolumnsequal = true;
            if ($fieldcolname != "") {
                for ($j = 0; $j < count($this->orderbylistcolumns); $j++) {
                    if ($this->orderbylistcolumns[$j] == $fieldcolname) {
                        $ordercolumnsequal = false;
                        break;
                    } else {
                        $ordercolumnsequal = true;
                    }
                }
                if ($ordercolumnsequal) {
                    $selectedfields = explode(":", $fieldcolname);
                    if ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule)
                        $selectedfields[0] = "aicrm_crmentity";
                    $sSQLList[] = $selectedfields[0] . "." . $selectedfields[1] . " '" . $selectedfields[2] . "'";
                }
            }
        }
        $sSQL .= implode(",", $sSQLList);

        $log->info("ReportRun :: Successfully returned getSelectedColumnsList" . $reportid);
        return $sSQL;
    }

    /** Function to get advanced comparator in query form for the given Comparator and value
     *  @ param $comparator : Type String
     *  @ param $value : Type String
     *  returns the check query for the comparator
     */
    function getAdvComparator($comparator, $value, $datatype = "")
    {
        global $log, $adb, $default_charset, $ogReport;
        $value = html_entity_decode(trim($value), ENT_QUOTES, $default_charset);
        $value_len = strlen($value);
        $is_field = false;
        if ($value[0] == '$' && $value[$value_len - 1] == '$') {
            $temp = str_replace('$', '', $value);
            $is_field = true;
        }
        if ($datatype == 'C') {
            $value = str_replace("yes", "1", str_replace("no", "0", $value));
        }

        if ($is_field == true) {
            $value = $this->getFilterComparedField($temp);
        }
        if ($comparator == "e") {
            if (trim($value) == "NULL") {
                $rtvalue = " is NULL";
            } elseif (trim($value) != "") {
                $rtvalue = " = " . $adb->quote($value);
            } elseif (trim($value) == "" && $datatype == "V") {
                $rtvalue = " = " . $adb->quote($value);
            } else {
                $rtvalue = " is NULL";
            }
        }
        if ($comparator == "n") {
            if (trim($value) == "NULL") {
                $rtvalue = " is NOT NULL";
            } elseif (trim($value) != "") {
                $rtvalue = " <> " . $adb->quote($value);
            } elseif (trim($value) == "" && $datatype == "V") {
                $rtvalue = " <> " . $adb->quote($value);
            } else {
                $rtvalue = " is NOT NULL";
            }
        }
        if ($comparator == "s") {
            $rtvalue = " like '" . formatForSqlLike($value, 2, $is_field) . "'";
        }
        if ($comparator == "ew") {
            $rtvalue = " like '" . formatForSqlLike($value, 1, $is_field) . "'";
        }
        if ($comparator == "c") {
            $rtvalue = " like '" . formatForSqlLike($value, 0, $is_field) . "'";
        }
        if ($comparator == "k") {
            $rtvalue = " not like '" . formatForSqlLike($value, 0, $is_field) . "'";
        }
        if ($comparator == "l") {
            $rtvalue = " < " . $adb->quote($value);
        }
        if ($comparator == "g") {
            $rtvalue = " > " . $adb->quote($value);
        }
        if ($comparator == "m") {
            $rtvalue = " <= " . $adb->quote($value);
        }
        if ($comparator == "h") {
            $rtvalue = " >= " . $adb->quote($value);
        }
        if ($is_field == true) {
            $rtvalue = str_replace("'", "", $rtvalue);
            $rtvalue = str_replace("\\", "", $rtvalue);
        }
        $log->info("ReportRun :: Successfully returned getAdvComparator");
        return $rtvalue;
    }

    /** Function to get field that is to be compared in query form for the given Comparator and field
     *  @ param $field : field
     *  returns the value for the comparator
     */
    function getFilterComparedField($field)
    {
        global $adb, $ogReport;
        $field = split('#', $field);
        $module = $field[0];
        $fieldname = trim($field[1]);
        $tabid = getTabId($module);
        $field_query = $adb->pquery("SELECT tablename,columnname,typeofdata,fieldname,uitype FROM aicrm_field WHERE tabid = ? AND fieldname= ?", array($tabid, $fieldname));
        $fieldtablename = $adb->query_result($field_query, 0, 'tablename');
        $fieldcolname = $adb->query_result($field_query, 0, 'columnname');
        $typeofdata = $adb->query_result($field_query, 0, 'typeofdata');
        $fieldtypeofdata = ChangeTypeOfData_Filter($fieldtablename, $fieldcolname, $typeofdata[0]);
        $uitype = $adb->query_result($field_query, 0, 'uitype');
        /*if($tr[0]==$ogReport->primodule)
				$value = $adb->query_result($field_query,0,'tablename').".".$adb->query_result($field_query,0,'columnname');
			else
				$value = $adb->query_result($field_query,0,'tablename').$tr[0].".".$adb->query_result($field_query,0,'columnname');
			*/
                if ($uitype == 68 || $uitype == 59) {
                    $fieldtypeofdata = 'V';
                }
                if ($fieldtablename == "aicrm_crmentity") {
                    $fieldtablename = $fieldtablename . $module;
                }
                if ($fieldname == "assigned_user_id") {
                    $fieldtablename = "aicrm_users" . $module;
                    $fieldcolname = "user_name";
                }
                if ($fieldname == "account_id") {
                    $fieldtablename = "aicrm_account" . $module;
                    $fieldcolname = "accountname";
                }
                if ($fieldname == "contact_id") {
                    $fieldtablename = "aicrm_contactdetails" . $module;
                    $fieldcolname = "lastname";
                }
                if ($fieldname == "dealid") {
                    $fieldtablename = "aicrm_deal" . $module;
                    $fieldcolname = "deal_no";
                }

                if ($fieldname == "parent_id") {
                    $fieldtablename = "aicrm_crmentityRel" . $module;
                    $fieldcolname = "setype";
                }
                if ($fieldname == "vendor_id") {
                    $fieldtablename = "aicrm_vendorRel" . $module;
                    $fieldcolname = "vendorname";
                }
                if ($fieldname == "potential_id") {
                    $fieldtablename = "aicrm_potentialRel" . $module;
                    $fieldcolname = "potentialname";
                }
                if ($fieldname == "assigned_user_id1") {
                    $fieldtablename = "aicrm_usersRel1";
                    $fieldcolname = "user_name";
                }
                if ($fieldname == 'quote_id') {
                    $fieldtablename = "aicrm_quotes" . $module;
                    $fieldcolname = "subject";
                }
                if ($fieldname == 'product_id' && $fieldtablename == 'aicrm_troubletickets') {
                    $fieldtablename = "aicrm_productsRel";
                    $fieldcolname = "productname";
                }
                if ($fieldname == 'product_id' && $fieldtablename == 'aicrm_campaign') {
                    $fieldtablename = "aicrm_productsCampaigns";
                    $fieldcolname = "productname";
                }
                if ($fieldname == 'product_id' && $fieldtablename == 'aicrm_products') {
                    $fieldtablename = "aicrm_productsProducts";
                    $fieldcolname = "productname";
                }
                if ($fieldname == 'campaignid' && $module == 'Potentials') {
                    $fieldtablename = "aicrm_campaign" . $module;
                    $fieldcolname = "campaignname";
                }
                if ($fieldname == 'campaignid' && $module != 'Campaigns') {
                    $fieldtablename = "aicrm_campaign" . $module;
                    $fieldcolname = "campaignname";
                }
                $value = $fieldtablename . "." . $fieldcolname;
                return $value;
            }

    /** Function to get the advanced filter columns for the reportid
     *  This function accepts the $reportid
     *  This function returns  $columnslist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
     *                          $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
     *                                            |
     *                          $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen filtercriteria
     *                             )
     *
     */


    function getAdvFilterList($reportid)
    {
        // Have we initialized information already?
        if ($this->_advfilterlist !== false) {
            return $this->_advfilterlist;
        }

        global $adb;
        global $modules;
        global $log;

        $advfiltersql = "select aicrm_relcriteria.* from aicrm_report";
        $advfiltersql .= " inner join aicrm_selectquery on aicrm_selectquery.queryid = aicrm_report.queryid";
        $advfiltersql .= " left join aicrm_relcriteria on aicrm_relcriteria.queryid = aicrm_selectquery.queryid";
        $advfiltersql .= " where aicrm_report.reportid =?";
        $advfiltersql .= " order by aicrm_relcriteria.columnindex";

        $result = $adb->pquery($advfiltersql, array($reportid));
        // echo "<pre>";print_r($result);echo "</pre>"; exit();
        while ($advfilterrow = $adb->fetch_array($result)) {
            $fieldcolname = $advfilterrow["columnname"];
            $comparator = $advfilterrow["comparator"];
            $value = $advfilterrow["value"];
            // echo $comparator."<br>";
            if ($comparator != "" && $comparator != "") {
                $selectedfields = explode(":", $fieldcolname);
                // print_r( $selectedfields); exit();
                //Added to handle yes or no for checkbox  field in reports advance filters. -shahul
                if ($selectedfields[4] == 'C') {
                    if (strcasecmp(trim($value), "yes") == 0)
                        $value = "1";
                    if (strcasecmp(trim($value), "no") == 0)
                        $value = "0";
                }
                $valuearray = explode(",", trim($value));
                //print_r($valuearray);
                $datatype = (isset($selectedfields[4])) ? $selectedfields[4] : "";
                //echo count($valuearray);
                if (isset($valuearray) && count($valuearray) > 1) {
                $advorsql = "";
                
                for ($n = 0; $n < count($valuearray); $n++) {

                    if ($selectedfields[0] == 'aicrm_crmentityRelHelpDesk' && $selectedfields[1] == 'setype') {
                        $advorsql[] = "(case aicrm_crmentityRelHelpDesk.setype when 'Accounts' then aicrm_accountRelHelpDesk.accountname else concat(aicrm_contactdetailsRelHelpDesk.lastname,' ',aicrm_contactdetailsRelHelpDesk.firstname) end) " . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                    } elseif ($selectedfields[0] == 'aicrm_crmentityRelCalendar' && $selectedfields[1] == 'setype') {
                        $advorsql[] = "(case aicrm_crmentityRelHelpDesk.setype when 'Accounts' then aicrm_accountRelHelpDesk.accountname else concat(aicrm_contactdetailsRelHelpDesk.lastname,' ',aicrm_contactdetailsRelHelpDesk.firstname) end) " . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                    } elseif (($selectedfields[0] == "aicrm_users" . $this->primarymodule || $selectedfields[0] == "aicrm_users" . $this->secondarymodule) && $selectedfields[1] == 'user_name') {
                        $module_from_tablename = str_replace("aicrm_users", "", $selectedfields[0]);
                        if ($this->primarymodule == 'Products') {
                            $advorsql[] = ($selectedfields[0] . ".user_name " . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype));
                        } else {
                            $advorsql[] = " " . $selectedfields[0] . ".user_name" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype) . " or aicrm_groups" . $module_from_tablename . ".groupname " . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                        }
                        } elseif ($selectedfields[1] == 'status')//when you use comma seperated values.
                        {
                            if ($selectedfields[2] == 'Calendar_Status')
                                $advorsql[] = "(case when (aicrm_activity.status not like '') then aicrm_activity.status else aicrm_activity.eventstatus end)" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                            elseif ($selectedfields[2] == 'HelpDesk_Status')
                                $advorsql[] = "aicrm_troubletickets.status" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                        } elseif ($selectedfields[1] == 'description')//when you use comma seperated values.
                        {
                            if ($selectedfields[0] == 'aicrm_crmentity' . $this->primarymodule)
                                $advorsql[] = "aicrm_crmentity.description" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                            else
                                $advorsql[] = $selectedfields[0] . "." . $selectedfields[1] . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                        } else {
                            $advorsql[] = $selectedfields[0] . "." . $selectedfields[1] . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
                        }
                    }
                    //If negative logic filter ('not equal to', 'does not contain') is used, 'and' condition should be applied instead of 'or'
                    if ($comparator == 'n' || $comparator == 'k') {
                        $advorsqls = implode(" and ", $advorsql);
                        //echo "555";
                    } else {
                        $advorsqls = implode(" or ", $advorsql);
                        $fieldvalue = " (" . $advorsqls . ") ";
                    }
                } elseif (($selectedfields[0] == "aicrm_users" . $this->primarymodule || $selectedfields[0] == "aicrm_users" . $this->secondarymodule) && $selectedfields[1] == 'user_name') {
                    $module_from_tablename = str_replace("aicrm_users", "", $selectedfields[0]);
                    if ($this->primarymodule == 'Products') {
                        $fieldvalue = ($selectedfields[0] . ".user_name " . $this->getAdvComparator($comparator, trim($value), $datatype));
                    } else {
                        $fieldvalue = " case when (" . $selectedfields[0] . ".user_name not like '') then " . $selectedfields[0] . ".user_name else aicrm_groups" . $module_from_tablename . ".groupname end " . $this->getAdvComparator($comparator, trim($value), $datatype);
                    }
                } elseif ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule) {
                    $fieldvalue = "aicrm_crmentity." . $selectedfields[1] . " " . $this->getAdvComparator($comparator, trim($value), $datatype);
                } elseif ($selectedfields[0] == 'aicrm_crmentityRelHelpDesk' && $selectedfields[1] == 'setype') {
                    $fieldvalue = "(aicrm_accountRelHelpDesk.accountname " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_contactdetailsRelHelpDesk.lastname " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_contactdetailsRelHelpDesk.firstname " . $this->getAdvComparator($comparator, trim($value), $datatype) . ")";

                } elseif ($selectedfields[0] == 'aicrm_crmentityRelCalendar' && $selectedfields[1] == 'setype') {
                    $fieldvalue = "(aicrm_accountRelCalendar.accountname " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or concat(aicrm_leaddetailsRelCalendar.lastname,' ',aicrm_leaddetailsRelCalendar.firstname) " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_potentialRelCalendar.potentialname " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_invoiceRelCalendar.subject " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_quotesRelCalendar.subject " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_purchaseorderRelCalendar.subject " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_salesorderRelCalendar.subject " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_troubleticketsRelCalendar.title " . $this->getAdvComparator($comparator, trim($value), $datatype) . " or aicrm_campaignRelCalendar.campaignname " . $this->getAdvComparator($comparator, trim($value), $datatype) . ")";
                } // fix for the ticket#4016 -- when you use only one value in its field.
                elseif ($selectedfields[0] == "aicrm_activity" && $selectedfields[1] == 'status') {
                    $fieldvalue = "(case when (aicrm_activity.status not like '') then aicrm_activity.status else aicrm_activity.eventstatus end)" . $this->getAdvComparator($comparator, trim($value), $datatype);
                } //end fix
                elseif ($selectedfields[3] == "contact_id" && strpos($selectedfields[2], "Contact_Name")) {
                    if ($this->primarymodule == 'PurchaseOrder' || $this->primarymodule == 'SalesOrder' || $this->primarymodule == 'Quotes' || $this->primarymodule == 'Invoice' || $this->primarymodule == 'Calendar')
                        $fieldvalue = "concat(aicrm_contactdetails" . $this->primarymodule . ".lastname,' ',aicrm_contactdetails" . $this->primarymodule . ".firstname)" . $this->getAdvComparator($comparator, trim($value), $datatype);
                    if ($this->secondarymodule == 'Quotes' || $this->secondarymodule == 'Invoice')
                        $fieldvalue = "concat(aicrm_contactdetails" . $this->secondarymodule . ".lastname,' ',aicrm_contactdetails" . $this->secondarymodule . ".firstname)" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                elseif ($selectedfields[3] == "parentid" && $selectedfields[0] == "aicrm_activity") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_account" . $this->primarymodule . ".accountname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                    $fieldvalue .= " or concat(aicrm_leaddetails" . $this->primarymodule . ".firstname,' ',aicrm_leaddetails" . $this->primarymodule . ".lastname)" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }

                //Uitype 930
                elseif ($selectedfields[3] == "account_id1" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_account1" . $this->primarymodule . ".accountname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                elseif ($selectedfields[3] == "account_id2" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_account2" . $this->primarymodule . ".accountname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                //Uitype 931
                elseif ($selectedfields[3] == "contact_id1" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_contactdetails1" . $this->primarymodule . ".contactname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                elseif ($selectedfields[3] == "contact_id2" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_contactdetails2" . $this->primarymodule . ".contactname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                elseif ($selectedfields[3] == "contact_id3" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_contactdetails3" . $this->primarymodule . ".contactname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                elseif ($selectedfields[3] == "contact_id4" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_contactdetails4" . $this->primarymodule . ".contactname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                //Uitype 934
                elseif ($selectedfields[3] == "contactid1" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_contactdetails5" . $this->primarymodule . ".contactname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                elseif ($selectedfields[3] == "contactid2" && $selectedfields[0] == "aicrm_projects") {
                    $fieldvalue = "";
                    $fieldvalue .= "aicrm_contactdetails6" . $this->primarymodule . ".contactname" . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
//ekk============================================================================================
                /*elseif($comparator == 'e' && (trim($valuearray[$n]) == "NULL" || trim($valuearray[$n]) == '')) {
					$fieldvalue = "(".$selectedfields[0].".".$selectedfields[1]." IS NULL OR ".$selectedfields[0].".".$selectedfields[1]." = '')";
					echo $n."<br>";
				}*/
//ekk============================================================================================

                else {
                    $fieldvalue = $selectedfields[0] . "." . $selectedfields[1] . $this->getAdvComparator($comparator, trim($value), $datatype);
                }
                if (isset($advfilterlist[$fieldcolname]))
                    $advfilterlist[$fieldcolname] = $advfilterlist[$fieldcolname] . ' and ' . $fieldvalue;
                else $advfilterlist[$fieldcolname] = $fieldvalue;
            }

        }
        // Save the information
        $this->_advfilterlist = $advfilterlist;

        $log->info("ReportRun :: Successfully returned getAdvFilterList" . $reportid);
        // print_r($advfilterlist); exit();
        return $advfilterlist;
    }

    /** Function to get the Standard filter columns for the reportid
     *  This function accepts the $reportid datatype Integer
     *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
     *                          $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
     *                             )
     *
     */
    function getStdFilterList($reportid)
    {
        // Have we initialized information already?
        if ($this->_stdfilterlist !== false) {
            return $this->_stdfilterlist;
        }

        global $adb;
        global $modules;
        global $log;

        $stdfiltersql = "select aicrm_reportdatefilter.* from aicrm_report";
        $stdfiltersql .= " inner join aicrm_reportdatefilter on aicrm_report.reportid = aicrm_reportdatefilter.datefilterid";
        $stdfiltersql .= " where aicrm_report.reportid = ?";

        $result = $adb->pquery($stdfiltersql, array($reportid));
        $stdfilterrow = $adb->fetch_array($result);
        if (isset($stdfilterrow)) {
            $fieldcolname = $stdfilterrow["datecolumnname"];
            $datefilter = $stdfilterrow["datefilter"];
            $startdate = $stdfilterrow["startdate"];
            $enddate = $stdfilterrow["enddate"];

            if ($fieldcolname != "none") {
                $selectedfields = explode(":", $fieldcolname);
                if ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule)
                    $selectedfields[0] = "aicrm_crmentity";
                if ($datefilter == "custom") {
                    if ($startdate != "0000-00-00" && $enddate != "0000-00-00" && $selectedfields[0] != "" && $selectedfields[1] != "") {
                        $stdfilterlist[$fieldcolname] = $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startdate . " 00:00:00' and '" . $enddate . " 23:59:59'";
                    }
                } else {
                    $startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
                    if ($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
                        $stdfilterlist[$fieldcolname] = $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startenddate[0] . " 00:00:00' and '" . $startenddate[1] . " 23:59:59'";
                    }
                }

            }
        }
        // Save the information
        $this->_stdfilterlist = $stdfilterlist;

        $log->info("ReportRun :: Successfully returned getStdFilterList" . $reportid);
        return $stdfilterlist;
    }

    /** Function to get the RunTime filter columns for the given $filtercolumn,$filter,$startdate,$enddate
     *  @ param $filtercolumn : Type String
     *  @ param $filter : Type String
     *  @ param $startdate: Type String
     *  @ param $enddate : Type String
     *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel=>$tablename.$columnname 'between' $startdate 'and' $enddate)
     *
     */
    function RunTimeFilter($filtercolumn, $filter, $startdate, $enddate)
    {
        if ($filtercolumn != "none") {
            $selectedfields = explode(":", $filtercolumn);
            if ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule)
                $selectedfields[0] = "aicrm_crmentity";
            if ($filter == "custom") {
                if ($startdate != "" && $enddate != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
                    $stdfilterlist[$filtercolumn] = $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startdate . " 00:00:00' and '" . $enddate . " 23:59:00'";
                }
            } else {
                if ($startdate != "" && $enddate != "") {
                    $startenddate = $this->getStandarFiltersStartAndEndDate($filter);
                    if ($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
                        $stdfilterlist[$filtercolumn] = $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startenddate[0] . " 00:00:00' and '" . $startenddate[1] . " 23:59:00'";
                    }
                }
            }

        }
        return $stdfilterlist;

    }

    /** Function to get standardfilter for the given reportid
     *  @ param $reportid : Type Integer
     *  returns the query of columnlist for the selected columns
     */

    function getStandardCriterialSql($reportid)
    {
        global $adb;
        global $modules;
        global $log;

        $sreportstdfiltersql = "select aicrm_reportdatefilter.* from aicrm_report";
        $sreportstdfiltersql .= " inner join aicrm_reportdatefilter on aicrm_report.reportid = aicrm_reportdatefilter.datefilterid";
        $sreportstdfiltersql .= " where aicrm_report.reportid = ?";

        $result = $adb->pquery($sreportstdfiltersql, array($reportid));
        $noofrows = $adb->num_rows($result);

        for ($i = 0; $i < $noofrows; $i++) {
            $fieldcolname = $adb->query_result($result, $i, "datecolumnname");
            $datefilter = $adb->query_result($result, $i, "datefilter");
            $startdate = $adb->query_result($result, $i, "startdate");
            $enddate = $adb->query_result($result, $i, "enddate");

            if ($fieldcolname != "none") {
                $selectedfields = explode(":", $fieldcolname);
                if ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule)
                    $selectedfields[0] = "aicrm_crmentity";
                if ($datefilter == "custom") {
                    if ($startdate != "0000-00-00" && $enddate != "0000-00-00" && $selectedfields[0] != "" && $selectedfields[1] != "") {
                        $sSQL .= $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startdate . "' and '" . $enddate . "'";
                    }
                } else {
                    $startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
                    if ($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
                        $sSQL .= $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startenddate[0] . "' and '" . $startenddate[1] . "'";
                    }
                }
            }
        }
        $log->info("ReportRun :: Successfully returned getStandardCriterialSql" . $reportid);
        return $sSQL;
    }

    /** Function to get standardfilter startdate and enddate for the given type
     *  @ param $type : Type String
     *  returns the $datevalue Array in the given format
     *        $datevalue = Array(0=>$startdate,1=>$enddate)
     */


    function getStandarFiltersStartAndEndDate($type)
    {
        $today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $tomorrow = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));

        $currentmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m"), "01", date("Y")));
        $currentmonth1 = date("Y-m-t");
        $lastmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, "01", date("Y")));
        $lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
        $nextmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, "01", date("Y")));
        $nextmonth1 = date("Y-m-t", strtotime("+1 Month"));

        $lastweek0 = date("Y-m-d", strtotime("-2 week Sunday"));
        $lastweek1 = date("Y-m-d", strtotime("-1 week Saturday"));

        $thisweek0 = date("Y-m-d", strtotime("-1 week Sunday"));
        $thisweek1 = date("Y-m-d", strtotime("this Saturday"));

        $nextweek0 = date("Y-m-d", strtotime("this Sunday"));
        $nextweek1 = date("Y-m-d", strtotime("+1 week Saturday"));

        $next7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 6, date("Y")));
        $next30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 29, date("Y")));
        $next60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 59, date("Y")));
        $next90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 89, date("Y")));
        $next120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 119, date("Y")));

        $last7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")));
        $last30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 29, date("Y")));
        $last60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 59, date("Y")));
        $last90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 89, date("Y")));
        $last120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 119, date("Y")));

        $currentFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
        $currentFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")));
        $lastFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") - 1));
        $lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") - 1));
        $nextFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
        $nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") + 1));

        if (date("m") <= 3) {
            $cFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
            $nFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y") - 1));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y") - 1));
        } else if (date("m") > 3 and date("m") <= 6) {
            $pFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $nFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));

        } else if (date("m") > 6 and date("m") <= 9) {
            $nFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
        } else if (date("m") > 9 and date("m") <= 12) {
            $nFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
            $nFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y") + 1));
            $pFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
            $pFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
            $cFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
            $cFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));

        }

        if ($type == "today") {

            $datevalue[0] = $today;
            $datevalue[1] = $today;
        } elseif ($type == "yesterday") {

            $datevalue[0] = $yesterday;
            $datevalue[1] = $yesterday;
        } elseif ($type == "tomorrow") {

            $datevalue[0] = $tomorrow;
            $datevalue[1] = $tomorrow;
        } elseif ($type == "thisweek") {

            $datevalue[0] = $thisweek0;
            $datevalue[1] = $thisweek1;
        } elseif ($type == "lastweek") {

            $datevalue[0] = $lastweek0;
            $datevalue[1] = $lastweek1;
        } elseif ($type == "nextweek") {

            $datevalue[0] = $nextweek0;
            $datevalue[1] = $nextweek1;
        } elseif ($type == "thismonth") {

            $datevalue[0] = $currentmonth0;
            $datevalue[1] = $currentmonth1;
        } elseif ($type == "lastmonth") {

            $datevalue[0] = $lastmonth0;
            $datevalue[1] = $lastmonth1;
        } elseif ($type == "nextmonth") {

            $datevalue[0] = $nextmonth0;
            $datevalue[1] = $nextmonth1;
        } elseif ($type == "next7days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next7days;
        } elseif ($type == "next30days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next30days;
        } elseif ($type == "next60days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next60days;
        } elseif ($type == "next90days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next90days;
        } elseif ($type == "next120days") {

            $datevalue[0] = $today;
            $datevalue[1] = $next120days;
        } elseif ($type == "last7days") {

            $datevalue[0] = $last7days;
            $datevalue[1] = $today;
        } elseif ($type == "last30days") {

            $datevalue[0] = $last30days;
            $datevalue[1] = $today;
        } elseif ($type == "last60days") {

            $datevalue[0] = $last60days;
            $datevalue[1] = $today;
        } else if ($type == "last90days") {

            $datevalue[0] = $last90days;
            $datevalue[1] = $today;
        } elseif ($type == "last120days") {

            $datevalue[0] = $last120days;
            $datevalue[1] = $today;
        } elseif ($type == "thisfy") {

            $datevalue[0] = $currentFY0;
            $datevalue[1] = $currentFY1;
        } elseif ($type == "prevfy") {

            $datevalue[0] = $lastFY0;
            $datevalue[1] = $lastFY1;
        } elseif ($type == "nextfy") {

            $datevalue[0] = $nextFY0;
            $datevalue[1] = $nextFY1;
        } elseif ($type == "nextfq") {

            $datevalue[0] = $nFq;
            $datevalue[1] = $nFq1;
        } elseif ($type == "prevfq") {

            $datevalue[0] = $pFq;
            $datevalue[1] = $pFq1;
        } elseif ($type == "thisfq") {
            $datevalue[0] = $cFq;
            $datevalue[1] = $cFq1;
        } else {
            $datevalue[0] = "";
            $datevalue[1] = "";
        }
        return $datevalue;
    }

    /** Function to get getGroupingList for the given reportid
     *  @ param $reportid : Type Integer
     *  returns the $grouplist Array in the following format
     *        $grouplist = Array($tablename:$columnname:$fieldlabel:fieldname:typeofdata=>$tablename:$columnname $sorder,
     *                   $tablename1:$columnname1:$fieldlabel1:fieldname1:typeofdata1=>$tablename1:$columnname1 $sorder,
     *                   $tablename2:$columnname2:$fieldlabel2:fieldname2:typeofdata2=>$tablename2:$columnname2 $sorder)
     * This function also sets the return value in the class variable $this->groupbylist
     */


    function getGroupingList($reportid)
    {
        global $adb;
        global $modules;
        global $log;

        // Have we initialized information already?
        if ($this->_groupinglist !== false) {
            return $this->_groupinglist;
        }

        $sreportsortsql = "select aicrm_reportsortcol.* from aicrm_report";
        $sreportsortsql .= " inner join aicrm_reportsortcol on aicrm_report.reportid = aicrm_reportsortcol.reportid";
        $sreportsortsql .= " where aicrm_report.reportid =? AND aicrm_reportsortcol.columnname IN (SELECT columnname from aicrm_selectcolumn WHERE queryid=?) order by aicrm_reportsortcol.sortcolid";

        $result = $adb->pquery($sreportsortsql, array($reportid, $reportid));

        while ($reportsortrow = $adb->fetch_array($result)) {
            $fieldcolname = $reportsortrow["columnname"];
            list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldcolname);
            $sortorder = $reportsortrow["sortorder"];

            if ($sortorder == "Ascending") {
                $sortorder = "ASC";

            } elseif ($sortorder == "Descending") {
                $sortorder = "DESC";
            }

            if ($fieldcolname != "none") {
                $selectedfields = explode(":", $fieldcolname);
                if ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule)
                    $selectedfields[0] = "aicrm_crmentity";
                //ekk 2016-07-27
                //$sqlvalue = "'".$selectedfields[2]."' ".$sortorder;
                $sqlvalue = $selectedfields[0] . "." . $selectedfields[1] . " " . $sortorder;
                if (stripos($selectedfields[1], 'cf_') == 0 && stristr($selectedfields[1], 'cf_') == true) { //echo "555";
                    //$grouplist[$fieldcolname] = $adb->sql_escape_string(decode_html($sqlvalue));
                    //$grouplist[$fieldcolname] = decode_html($sqlvalue);
            } else {
                $grouplist[$fieldcolname] = $sqlvalue;
            }
            $temp = split("_", $selectedfields[2], 2);
            $module = $temp[0];
            if (CheckFieldPermission($fieldname, $module) == 'true') {
                $this->groupbylist[$fieldcolname] = $selectedfields[0] . "." . $selectedfields[1] . " " . $selectedfields[2];
            }
        }
    }
        // Save the information
    $this->_groupinglist = $grouplist;

    $log->info("ReportRun :: Successfully returned getGroupingList" . $reportid);
        //echo '<pre> line 1219 ReportRun.php <br>'; print_r($grouplist); echo '</pre>';
    return $grouplist;
}

    /** function to get the selectedorderbylist for the given reportid
     *  @ param $reportid : type integer
     *  this returns the columns query for the sortorder columns
     *  this function also sets the return value in the class variable $this->orderbylistsql
     */


    function getSelectedOrderbyList($reportid)
    {

        global $adb;
        global $modules;
        global $log;

        $sreportsortsql = "select aicrm_reportsortcol.* from aicrm_report";
        $sreportsortsql .= " inner join aicrm_reportsortcol on aicrm_report.reportid = aicrm_reportsortcol.reportid";
        $sreportsortsql .= " where aicrm_report.reportid =? order by aicrm_reportsortcol.sortcolid";

        $result = $adb->pquery($sreportsortsql, array($reportid));
        $noofrows = $adb->num_rows($result);

        for ($i = 0; $i < $noofrows; $i++) {
            $fieldcolname = $adb->query_result($result, $i, "columnname");
            $sortorder = $adb->query_result($result, $i, "sortorder");

            if ($sortorder == "Ascending") {
                $sortorder = "ASC";
            } elseif ($sortorder == "Descending") {
                $sortorder = "DESC";
            }

            if ($fieldcolname != "none") {
                $this->orderbylistcolumns[] = $fieldcolname;
                $n = $n + 1;
                $selectedfields = explode(":", $fieldcolname);
                if ($n > 1) {
                    $sSQL .= ", ";
                    $this->orderbylistsql .= ", ";
                }
                if ($selectedfields[0] == "aicrm_crmentity" . $this->primarymodule)
                    $selectedfields[0] = "aicrm_crmentity";
                $sSQL .= $selectedfields[0] . "." . $selectedfields[1] . " " . $sortorder;
                $this->orderbylistsql .= $selectedfields[0] . "." . $selectedfields[1] . " " . $selectedfields[2];
            }
        }
        $log->info("ReportRun :: Successfully returned getSelectedOrderbyList" . $reportid);
        return $sSQL;
    }

    /** function to get secondary Module for the given Primary module and secondary module
     *  @ param $module : type String
     *  @ param $secmodule : type String
     *  this returns join query for the given secondary module
     */

    function getRelatedModulesQuery($module, $secmodule)
    {
        global $log, $current_user;
        $query = '';
        if ($secmodule != '') { //echo 'getRelatedModulesQuery: '; print_r($secmodule);
            $secondarymodule = explode(":", $secmodule); //print_r($secondarymodule);
            foreach ($secondarymodule as $key => $value) { //echo $value;
                $foc = CRMEntity::getInstance($value);
                $query .= $foc->generateReportsSecQuery($module, $value);
            }
        } //echo $query;
        $log->info("ReportRun :: Successfully returned getRelatedModulesQuery" . $secmodule);
        return $query;
    }

    /** function to get report query for the given module
     *  @ param $module : type String
     *  this returns join query for the given module
     */

    function getReportsQuery($module)
    {
        //echo 'Module <b>'.$module.'</b> line 1298 getReportsQuery<br>';//exit;
        // echo $module; exit;
        global $log, $current_user;
        $secondary_module = "'";
        $secondary_module .= str_replace(":", "','", $this->secondarymodule);
        $secondary_module .= "'";

        if ($module == "Leads") {
            $query = "from aicrm_leaddetails
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
            inner join aicrm_leadsubdetails on aicrm_leadsubdetails.leadsubscriptionid=aicrm_leaddetails.leadid
            inner join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leadsubdetails.leadsubscriptionid
            inner join aicrm_leadscf on aicrm_leaddetails.leadid = aicrm_leadscf.leadid

            LEFT JOIN aicrm_contactdetails aicrm_contactdetailsLeads on aicrm_leaddetails.contactid = aicrm_contactdetailsLeads.contactid

            left join aicrm_groups as aicrm_groupsLeads on aicrm_groupsLeads.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersLeads on aicrm_usersLeads.id = aicrm_crmentity.smownerid
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as aicrm_usersModifiedLeads on aicrm_crmentity.modifiedby = aicrm_usersModifiedLeads.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorLeads on aicrm_crmentity.smcreatorid = aicrm_usersCreatorLeads.id

            left join aicrm_account as aicrm_accountLeads on aicrm_accountLeads.accountid=aicrm_leaddetails.accountid
            -- left join aicrm_leaddetails as aicrm_contactdetailsLeads on aicrm_contactdetailsLeads.leadid=aicrm_leaddetails.leadid


            LEFT JOIN aicrm_leaddetails AS aicrm_leaddetailsCalendar ON aicrm_leaddetails.leadid = aicrm_leaddetailsCalendar.leadid


            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "

            where aicrm_crmentity.deleted=0 and aicrm_leaddetails.converted=0";
        } else if ($module == "KnowledgeBase") {
            $query = "
            FROM aicrm_knowledgebase
            INNER JOIN aicrm_knowledgebasecf ON aicrm_knowledgebasecf.knowledgebaseid = aicrm_knowledgebase.knowledgebaseid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_knowledgebase.knowledgebaseid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as aicrm_usersModifiedKnowledgeBase on aicrm_crmentity.modifiedby = aicrm_usersModifiedKnowledgeBase.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorKnowledgeBase on aicrm_crmentity.smcreatorid = aicrm_usersCreatorKnowledgeBase.id
            left join aicrm_users as aicrm_usersKnowledgeBase on aicrm_usersKnowledgeBase.id = aicrm_crmentity.smownerid
            left join aicrm_groups as aicrm_groupsKnowledgeBase on aicrm_groupsKnowledgeBase.groupid = aicrm_crmentity.smownerid

            left join aicrm_account as aicrm_accountCalendar on aicrm_accountCalendar.accountid=aicrm_crmentity.crmid

            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "

            WHERE aicrm_crmentity.deleted = 0";

        } else if ($module == "Application") {
            $query = "from aicrm_applications 
            left join aicrm_applicationscf on aicrm_applicationscf.applicationid = aicrm_applications.applicationid
            left join aicrm_crmentity  	on aicrm_crmentity.crmid=aicrm_applications.applicationid 
            left join aicrm_users as aicrm_usersApplication on aicrm_crmentity.smownerid=aicrm_usersApplication.id 
            left join aicrm_groups as aicrm_groupsApplication on aicrm_groupsApplication.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id 
            left join aicrm_branchs 	as aicrm_branchsApplication ON aicrm_branchsApplication.branchid = aicrm_applicationscf.branchid
            left join aicrm_branchscf 	as aicrm_branchscfApplication ON aicrm_branchsApplication.branchid = aicrm_branchscfApplication.branchid
            left join aicrm_account as aicrm_accountApplication ON aicrm_accountApplication.accountid = aicrm_applications.accountid

            where aicrm_crmentity.deleted=0

            ";
            //echo $this->getRelatedModulesQuery($module,$this->secondarymodule);exit;
        } else if ($module == "Servicerequest") {
            //echo $module; exit;
            $query = "FROM aicrm_servicerequest
            INNER JOIN aicrm_servicerequestcf ON aicrm_servicerequestcf.servicerequestid = aicrm_servicerequest.servicerequestid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicerequest.servicerequestid
            left join aicrm_users as aicrm_usersServiceRequest on aicrm_crmentity.smownerid=aicrm_usersServiceRequest.id 
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_servicerequest.accountid
            LEFT JOIN aicrm_contactdetails AS aicrm_contactdetailsServicerequest ON aicrm_contactdetailsServicerequest.contactid = aicrm_servicerequest.contactid
            left join aicrm_groups as aicrm_groupsServiceRequest on aicrm_groupsServiceRequest.groupid = aicrm_crmentity.smownerid

            LEFT JOIN aicrm_users as aicrm_usersModifiedServiceRequest on aicrm_crmentity.modifiedby = aicrm_usersModifiedServiceRequest.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorServiceRequest on aicrm_crmentity.smcreatorid = aicrm_usersCreatorServiceRequest.id

            LEFT JOIN aicrm_account AS aicrm_accountServicerequest on aicrm_accountServicerequest.accountid = aicrm_servicerequest.accountid
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        //echo $this->getRelatedModulesQuery($module,$this->secondarymodule);exit;
        } else if ($module == "Serial") {
            $query = "from aicrm_serial
            INNER JOIN aicrm_serialcf AS aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_serial.serialid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid
            LEFT JOIN aicrm_groups AS aicrm_groupsSerial ON aicrm_groupsSerial.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users AS aicrm_usersSerial ON aicrm_usersSerial.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_account AS aicrm_accountSerial on aicrm_accountSerial.accountid = aicrm_serial.accountid
            LEFT JOIN aicrm_users as aicrm_usersModifiedSerial on aicrm_crmentity.modifiedby = aicrm_usersModifiedSerial.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorSerial on aicrm_crmentity.smcreatorid = aicrm_usersCreatorSerial.id
            LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.accountid = aicrm_serial.accountid
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        } else if ($module == "Accounts") {
            $query = "from aicrm_account
            inner join aicrm_accountscf on aicrm_account.accountid = aicrm_accountscf.accountid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid 
            LEFT JOIN aicrm_users as aicrm_usersModifiedAccounts on aicrm_crmentity.modifiedby = aicrm_usersModifiedAccounts.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorAccounts on aicrm_crmentity.smcreatorid = aicrm_usersCreatorAccounts.id
            LEFT JOIN aicrm_groups as aicrm_groupsAccounts on aicrm_groupsAccounts.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as aicrm_usersAccounts on aicrm_usersAccounts.id = aicrm_crmentity.smownerid

            -- inner join aicrm_accountbillads on aicrm_account.accountid=aicrm_accountbillads.accountaddressid 
            -- inner join aicrm_accountshipads on aicrm_account.accountid=aicrm_accountshipads.accountaddressid 
            
            -- left join aicrm_groups as aicrm_groupsAccounts on aicrm_groupsAccounts.groupid = aicrm_crmentity.smownerid
            -- left join aicrm_account as aicrm_accountAccounts on aicrm_accountAccounts.accountid = aicrm_account.parentid
            -- left join aicrm_users as aicrm_usersAccounts on aicrm_usersAccounts.id = aicrm_crmentity.smownerid
            -- left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            -- left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid

            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0
            ";

        } else if ($module == "EmailTarget") {
            $query = "from aicrm_emailtargets 
            inner join aicrm_emailtargetscf on aicrm_emailtargetscf.emailtargetid = aicrm_emailtargets.emailtargetid
            inner join aicrm_crmentity  	on aicrm_crmentity.crmid=aicrm_emailtargets.emailtargetid 
            left join aicrm_groups as aicrm_groupsEmailTarget on aicrm_groupsEmailTarget.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersEmailTarget on aicrm_crmentity.smownerid=aicrm_usersEmailTarget.id 
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as user on aicrm_crmentity.smownerid=user.id  
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        }else if ($module == "Job") {
            $query = "FROM aicrm_jobs
            INNER JOIN aicrm_jobscf AS aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
            LEFT JOIN aicrm_account AS aicrm_accountJob on aicrm_accountJob.accountid = aicrm_jobs.accountid
            LEFT JOIN aicrm_groups AS aicrm_groupsJob ON aicrm_groupsJob.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users AS aicrm_usersJob ON aicrm_usersJob.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as aicrm_usersModifiedJob on aicrm_crmentity.modifiedby = aicrm_usersModifiedJob.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorJob on aicrm_crmentity.smcreatorid = aicrm_usersCreatorJob.id

            LEFT JOIN aicrm_contactdetails AS aicrm_contactdetailsJob ON aicrm_jobs.contactid = aicrm_contactdetailsJob.contactid
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        } else if ($module == "EmailTargetList") {
            $query = "from aicrm_emailtargetlists 
            inner join aicrm_emailtargetlistscf on aicrm_emailtargetlistscf.emailtargetlistid = aicrm_emailtargetlists.emailtargetlistid
            inner join aicrm_crmentity  	on aicrm_crmentity.crmid=aicrm_emailtargetlists.emailtargetlistid 
            left join aicrm_groups as aicrm_groupsEmailTargetList on aicrm_groupsEmailTargetList.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersEmailTargetList on aicrm_crmentity.smownerid=aicrm_usersEmailTargetList.id 
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as user on aicrm_crmentity.smownerid=user.id  
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        } else if ($module == "InternalTraining") {
            $query = "from aicrm_internaltrainings 
            inner join aicrm_internaltrainingscf on aicrm_internaltrainingscf.internaltrainingid = aicrm_internaltrainings.internaltrainingid
            inner join aicrm_crmentity  	on aicrm_crmentity.crmid=aicrm_internaltrainings.internaltrainingid 
            left join aicrm_groups as aicrm_groupsInternalTraining on aicrm_groupsInternalTraining.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersInternalTraining on aicrm_crmentity.smownerid=aicrm_usersInternalTraining.id 
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as user on aicrm_crmentity.smownerid=user.id  
            left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id=aicrm_internaltrainings.internaltrainingid
            left join aicrm_contactdetails  on aicrm_inventoryproductrel.productid=aicrm_contactdetails.	contactid   
            left join aicrm_account on aicrm_account.accountid=aicrm_contactdetails.accountid
            left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        } else if ($module == "Contacts") {
            $query = "from aicrm_contactdetails
            inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
            inner join aicrm_contactaddress on aicrm_contactdetails.contactid = aicrm_contactaddress.contactaddressid
            inner join aicrm_customerdetails on aicrm_customerdetails.customerid = aicrm_contactdetails.contactid
            inner join aicrm_contactsubdetails on aicrm_contactdetails.contactid = aicrm_contactsubdetails.contactsubscriptionid
            inner join aicrm_contactscf on aicrm_contactdetails.contactid = aicrm_contactscf.contactid
            left join aicrm_groups aicrm_groupsContacts on aicrm_groupsContacts.groupid = aicrm_crmentity.smownerid
            -- left join aicrm_contactdetails as aicrm_contactdetailsContacts on aicrm_contactdetailsContacts.contactid = aicrm_contactdetails.reportsto
            left join aicrm_account as aicrm_accountContacts on aicrm_accountContacts.accountid = aicrm_contactdetails.accountid
            left join aicrm_users as aicrm_usersContacts on aicrm_usersContacts.id = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as aicrm_usersModifiedContacts on aicrm_crmentity.modifiedby = aicrm_usersModifiedContacts.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorContacts on aicrm_crmentity.smcreatorid = aicrm_usersCreatorContacts.id
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid

            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0";
        } else if ($module == "Potentials") {
            $query = "from aicrm_potential 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_potential.potentialid 
            inner join aicrm_potentialscf on aicrm_potentialscf.potentialid = aicrm_potential.potentialid
            left join aicrm_account as aicrm_accountPotentials on aicrm_potential.related_to = aicrm_accountPotentials.accountid
            left join aicrm_contactdetails as aicrm_contactdetailsPotentials on aicrm_potential.related_to = aicrm_contactdetailsPotentials.contactid 
            left join aicrm_campaign as aicrm_campaignPotentials on aicrm_potential.campaignid = aicrm_campaignPotentials.campaignid
            left join aicrm_groups aicrm_groupsPotentials on aicrm_groupsPotentials.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersPotentials on aicrm_usersPotentials.id = aicrm_crmentity.smownerid  
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid  
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        } else if ($module == "Products") {
            $query = "from aicrm_products 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid 
            left join aicrm_productcf on aicrm_products.productid = aicrm_productcf.productid 
            -- left join aicrm_users as aicrm_usersProducts on aicrm_usersProducts.id = aicrm_products.handler 
            -- left join aicrm_vendor as aicrm_vendorRelProducts on aicrm_vendorRelProducts.vendorid = aicrm_products.vendor_id
            LEFT JOIN aicrm_users as aicrm_usersModifiedProducts on aicrm_crmentity.modifiedby = aicrm_usersModifiedProducts.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorProducts on aicrm_crmentity.smcreatorid = aicrm_usersCreatorProducts.id


            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0";
        } else if ($module == "HelpDesk") {
            $query = "from aicrm_troubletickets
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_troubletickets.ticketid
            inner join aicrm_ticketcf on aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
            left join aicrm_crmentity as aicrm_crmentityRelHelpDesk on aicrm_crmentityRelHelpDesk.crmid = aicrm_troubletickets.ticketid
            left join aicrm_account as aicrm_accountRelHelpDesk on aicrm_accountRelHelpDesk.accountid=aicrm_crmentityRelHelpDesk.crmid
            left join aicrm_contactdetails as aicrm_contactdetailsHelpDesk on aicrm_contactdetailsHelpDesk.contactid= aicrm_crmentityRelHelpDesk.crmid
            left join aicrm_products as aicrm_productsRel on aicrm_productsRel.productid = aicrm_troubletickets.product_id
            left join aicrm_groups as aicrm_groupsHelpDesk on aicrm_groupsHelpDesk.groupid = aicrm_crmentity.smownerid
            left join aicrm_account as aicrm_accountHelpDesk on aicrm_accountHelpDesk.accountid=aicrm_troubletickets.accountid
            left join aicrm_users as aicrm_usersHelpDesk on aicrm_crmentity.smownerid=aicrm_usersHelpDesk.id
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_users as aicrm_usersModifiedHelpDesk on aicrm_crmentity.modifiedby = aicrm_usersModifiedHelpDesk.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorHelpDesk on aicrm_crmentity.smcreatorid = aicrm_usersCreatorHelpDesk.id
            left join aicrm_account as aicrm_accountCalendar on aicrm_accountCalendar.accountid=aicrm_troubletickets.accountid 
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "				
            where aicrm_crmentity.deleted=0 ";

        } else if ($module == "Calendar") {

            $query = "
            FROM aicrm_activity
            LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid

            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersCalendar on aicrm_usersCalendar.id = aicrm_crmentity.smownerid 
            left join aicrm_groups as aicrm_groupsCalendar on aicrm_groupsCalendar.groupid = aicrm_crmentity.smownerid

            left join aicrm_account as aicrm_accountCalendar on aicrm_accountCalendar.accountid=aicrm_activity.parentid
            left JOIN aicrm_leaddetails as aicrm_leaddetailsCalendar ON aicrm_leaddetailsCalendar.leadid = aicrm_activity.parentid
            left JOIN aicrm_deal as aicrm_dealCalendar ON aicrm_dealCalendar.dealid = aicrm_activity.dealid

            LEFT JOIN aicrm_users as aicrm_usersModifiedCalendar on aicrm_crmentity.modifiedby = aicrm_usersModifiedCalendar.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorCalendar on aicrm_crmentity.smcreatorid = aicrm_usersCreatorCalendar.id

            LEFT JOIN aicrm_contactdetails AS aicrm_contactdetailsCalendar ON aicrm_contactdetailsCalendar.contactid = aicrm_activity.contactid
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";

        } else if ($module == "Quotes") {
                //echo $module; exit;
            $query = "from aicrm_quotes 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_quotes.quoteid 
            inner join aicrm_quotesbillads on aicrm_quotes.quoteid=aicrm_quotesbillads.quotebilladdressid 
            inner join aicrm_quotesshipads on aicrm_quotes.quoteid=aicrm_quotesshipads.quoteshipaddressid  
            left join aicrm_quotescf on aicrm_quotes.quoteid = aicrm_quotescf.quoteid 
            left join aicrm_groups as aicrm_groupsQuotes on aicrm_groupsQuotes.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersQuotes on aicrm_usersQuotes.id = aicrm_crmentity.smownerid
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
            -- left join aicrm_users as aicrm_usersRel1 on aicrm_usersRel1.id = aicrm_quotes.inventorymanager
            -- left join aicrm_potential as aicrm_potentialRelQuotes on aicrm_potentialRelQuotes.potentialid = aicrm_quotes.potentialid
            left join aicrm_contactdetails as aicrm_contactdetailsQuotes on aicrm_contactdetailsQuotes.contactid = aicrm_quotes.contactid 
            left join aicrm_account as aicrm_accountQuotes on aicrm_accountQuotes.accountid = aicrm_quotes.accountid
            LEFT JOIN aicrm_users as aicrm_usersModifiedQuotes on aicrm_crmentity.modifiedby = aicrm_usersModifiedQuotes.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorQuotes on aicrm_crmentity.smcreatorid = aicrm_usersCreatorQuotes.id
            left join aicrm_inventoryproductrel on aicrm_quotes.quoteid=aicrm_inventoryproductrel.id
            inner join aicrm_products on aicrm_inventoryproductrel.productid=aicrm_products.productid
            inner join aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid

            -- LEFT JOIN aicrm_deal AS aicrm_dealQuotes ON aicrm_quotes.dealid = aicrm_dealQuotes.dealid
            LEFT JOIN aicrm_campaign AS aicrm_campaignQuotes ON aicrm_quotes.campaignid = aicrm_campaignQuotes.campaignid

            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "

            where aicrm_crmentity.deleted=0 && aicrm_inventoryproductrel.productid>0";

        //left join aicrm_account on aicrm_account.accountid = aicrm_quotes.accountid
        } else if ($module == "PurchaseOrder") {
            $query = "from aicrm_purchaseorder 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_purchaseorder.purchaseorderid 
            inner join aicrm_pobillads on aicrm_purchaseorder.purchaseorderid=aicrm_pobillads.pobilladdressid 
            inner join aicrm_poshipads on aicrm_purchaseorder.purchaseorderid=aicrm_poshipads.poshipaddressid 
            left join aicrm_purchaseordercf on aicrm_purchaseorder.purchaseorderid = aicrm_purchaseordercf.purchaseorderid  
            left join aicrm_groups as aicrm_groupsPurchaseOrder on aicrm_groupsPurchaseOrder.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersPurchaseOrder on aicrm_usersPurchaseOrder.id = aicrm_crmentity.smownerid 
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid 
            left join aicrm_vendor as aicrm_vendorRelPurchaseOrder on aicrm_vendorRelPurchaseOrder.vendorid = aicrm_purchaseorder.vendorid 
            left join aicrm_contactdetails as aicrm_contactdetailsPurchaseOrder on aicrm_contactdetailsPurchaseOrder.contactid = aicrm_purchaseorder.contactid 
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0";
        } else if ($module == "Invoice") {
            $query = "from aicrm_invoice 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_invoice.invoiceid 
            inner join aicrm_invoicebillads on aicrm_invoice.invoiceid=aicrm_invoicebillads.invoicebilladdressid 
            inner join aicrm_invoiceshipads on aicrm_invoice.invoiceid=aicrm_invoiceshipads.invoiceshipaddressid 
            left join aicrm_salesorder as aicrm_salesorderInvoice on aicrm_salesorderInvoice.salesorderid=aicrm_invoice.salesorderid
            left join aicrm_invoicecf on aicrm_invoice.invoiceid = aicrm_invoicecf.invoiceid 
            left join aicrm_groups as aicrm_groupsInvoice on aicrm_groupsInvoice.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersInvoice on aicrm_usersInvoice.id = aicrm_crmentity.smownerid
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
            left join aicrm_account as aicrm_accountInvoice on aicrm_accountInvoice.accountid = aicrm_invoice.accountid
            left join aicrm_contactdetails as aicrm_contactdetailsInvoice on aicrm_contactdetailsInvoice.contactid = aicrm_invoice.contactid
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0";
        } else if ($module == "SalesOrder") {
            $query = "from aicrm_salesorder 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_salesorder.salesorderid 
            inner join aicrm_sobillads on aicrm_salesorder.salesorderid=aicrm_sobillads.sobilladdressid 
            inner join aicrm_soshipads on aicrm_salesorder.salesorderid=aicrm_soshipads.soshipaddressid 
            left join aicrm_salesordercf on aicrm_salesorder.salesorderid = aicrm_salesordercf.salesorderid  
            left join aicrm_contactdetails as aicrm_contactdetailsSalesOrder on aicrm_contactdetailsSalesOrder.contactid = aicrm_salesorder.contactid 
            left join aicrm_quotes as aicrm_quotesSalesOrder on aicrm_quotesSalesOrder.quoteid = aicrm_salesorder.quoteid				
            left join aicrm_account as aicrm_accountSalesOrder on aicrm_accountSalesOrder.accountid = aicrm_salesorder.accountid
            left join aicrm_potential as aicrm_potentialRelSalesOrder on aicrm_potentialRelSalesOrder.potentialid = aicrm_salesorder.potentialid 
            left join aicrm_invoice_recurring_info on aicrm_invoice_recurring_info.salesorderid = aicrm_salesorder.salesorderid
            left join aicrm_groups as aicrm_groupsSalesOrder on aicrm_groupsSalesOrder.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersSalesOrder on aicrm_usersSalesOrder.id = aicrm_crmentity.smownerid 
            left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
            left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid 
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0";
        } else if ($module == "Campaigns") {
            $query = "from aicrm_campaign
            inner join aicrm_campaignscf as aicrm_campaignscf on aicrm_campaignscf.campaignid=aicrm_campaign.campaignid   
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_campaign.campaignid
            left join aicrm_groups as aicrm_groupsCampaigns on aicrm_groupsCampaigns.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersCampaigns on aicrm_usersCampaigns.id = aicrm_crmentity.smownerid


            LEFT JOIN aicrm_users as aicrm_usersModifiedCampaigns on aicrm_crmentity.modifiedby = aicrm_usersModifiedCampaigns.id
            LEFT JOIN aicrm_users as aicrm_usersCreatorCampaigns on aicrm_crmentity.smcreatorid = aicrm_usersCreatorCampaigns.id

            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0";
        } else if ($module == "Activitys") {
            $query = "from aicrm_activitys 
            inner join aicrm_activityscf on aicrm_activityscf.activitysid = aicrm_activitys.activitysid
            inner join aicrm_crmentity  	on aicrm_crmentity.crmid=aicrm_activitys.activitysid 

            left join aicrm_account as aicrm_accountActivitys on aicrm_accountActivitys.accountid=aicrm_activitys.accountid
            left join aicrm_accountscf as aicrm_accountscfActivitys on aicrm_accountscfActivitys.accountid=aicrm_accountActivitys.accountid

            left join aicrm_branchs as aicrm_branchsActivitys on aicrm_branchsActivitys.branchid=aicrm_activitys.branchid
            left join aicrm_branchscf as aicrm_branchscfActivitys on aicrm_branchscfActivitys.branchid=aicrm_branchsActivitys.branchid

            left join aicrm_groups as aicrm_groupsActivitys on aicrm_groupsActivitys.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersActivitys on aicrm_crmentity.smownerid=aicrm_usersActivitys.id  
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        } else if ($module == "Opportunity") {
            $query = "from aicrm_opportunity 
            inner join aicrm_opportunitycf on aicrm_opportunitycf.opportunityid = aicrm_opportunity.opportunityid
            inner join aicrm_crmentity  	on aicrm_crmentity.crmid=aicrm_opportunity.opportunityid 

            left join aicrm_account as aicrm_accountOpportunity on aicrm_accountOpportunity.accountid=aicrm_opportunity.accountid
            left join aicrm_accountscf as aicrm_accountscfOpportunity on aicrm_accountscfOpportunity.accountid=aicrm_accountOpportunity.accountid

            left join aicrm_branchs as aicrm_branchsOpportunity on aicrm_branchsOpportunity.branchid=aicrm_opportunity.branchid
            left join aicrm_branchscf as aicrm_branchscfOpportunity on aicrm_branchscfOpportunity.branchid=aicrm_branchsOpportunity.branchid

            left join aicrm_products as aicrm_productsOpportunity on aicrm_productsOpportunity.productid=aicrm_opportunity.product_id
            left join aicrm_productcf as aicrm_productcfOpportunity on aicrm_productcfOpportunity.productid=aicrm_productsOpportunity.productid

            left join aicrm_groups as aicrm_groupsOpportunity  on aicrm_groupsOpportunity.groupid = aicrm_crmentity.smownerid
            left join aicrm_users as aicrm_usersOpportunity  on aicrm_usersOpportunity.id=aicrm_crmentity.smownerid
            " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
            where aicrm_crmentity.deleted=0 ";
        } else if ($module == "Users") {//echo "5555";
        $query = "
        FROM aicrm_users
        LEFT JOIN aicrm_user2role ON aicrm_user2role.userid = aicrm_users.id
        LEFT JOIN aicrm_role as aicrm_roleUsers ON aicrm_roleUsers.roleid = aicrm_user2role.roleid
        left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_users.id
        WHERE aicrm_users.deleted =0
        ";
    } else if ($module == "Projects") {
        $query = "
        FROM aicrm_projects
        INNER JOIN aicrm_projectscf ON aicrm_projectscf.projectsid = aicrm_projects.projectsid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid
        LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
        LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_projects.accountid
        LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_projects.contactid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
        LEFT JOIN aicrm_account as aicrm_accountProjects ON aicrm_accountProjects.accountid = aicrm_projects.accountid 
        LEFT JOIN aicrm_contactdetails as aicrm_contactdetailsProjects ON aicrm_contactdetailsProjects.contactid = aicrm_projects.contactid 
        LEFT JOIN aicrm_users as aicrm_usersProjects ON aicrm_crmentity.smownerid = aicrm_usersProjects.id 
        LEFT JOIN aicrm_groups as aicrm_groupsProjects ON aicrm_groupsProjects.groupid = aicrm_crmentity.smownerid 
        LEFT JOIN aicrm_inventoryproductrel as projectProductrel on aicrm_projects.projectsid=projectProductrel.id and projectProductrel.productid>0 

        LEFT JOIN aicrm_products as projectProduct on projectProductrel.productid=projectProduct.productid 
        LEFT JOIN aicrm_productcf as projectProductcf on projectProductcf.productid=projectProduct.productid

        LEFT JOIN aicrm_account as aicrm_account1Projects ON aicrm_account1Projects.accountid = aicrm_projects.account_id1 
        LEFT JOIN aicrm_account as aicrm_account2Projects ON aicrm_account2Projects.accountid = aicrm_projects.account_id2 

        LEFT JOIN aicrm_contactdetails as aicrm_contactdetails1Projects ON aicrm_contactdetails1Projects.contactid = aicrm_projects.contact_id1 
        LEFT JOIN aicrm_contactdetails as aicrm_contactdetails2Projects ON aicrm_contactdetails2Projects.contactid = aicrm_projects.contact_id2 
        LEFT JOIN aicrm_contactdetails as aicrm_contactdetails3Projects ON aicrm_contactdetails3Projects.contactid = aicrm_projects.contact_id3 
        LEFT JOIN aicrm_contactdetails as aicrm_contactdetails4Projects ON aicrm_contactdetails4Projects.contactid = aicrm_projects.contact_id4

        /*LEFT JOIN aicrm_contactdetails as aicrm_contactdetails5Projects ON aicrm_contactdetails5Projects.contactid = aicrm_projects.contactid1
        LEFT JOIN aicrm_contactdetails as aicrm_contactdetails6Projects ON aicrm_contactdetails6Projects.contactid = aicrm_projects.contactid2*/

        LEFT JOIN aicrm_users AS aicrm_usersCreatorProjects ON aicrm_usersCreatorProjects.id = aicrm_crmentity.smcreatorid
        LEFT JOIN aicrm_users AS aicrm_usersModifiedProjects ON  aicrm_usersModifiedProjects.id = aicrm_crmentity.modifiedby
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    } else if ($module == "PriceList") {
        $query = "
        from aicrm_pricelists 
        inner join aicrm_pricelistscf as aicrm_pricelistscf on aicrm_pricelistscf.pricelistid=aicrm_pricelists.pricelistid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_pricelists.pricelistid
        left join aicrm_groups as aicrm_groupsPriceList on aicrm_groupsPriceList.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersPriceList on aicrm_usersPriceList.id = aicrm_crmentity.smownerid
        left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
        left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedPriceList on aicrm_crmentity.modifiedby = aicrm_usersModifiedPriceList.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorPriceList on aicrm_crmentity.smcreatorid = aicrm_usersCreatorPriceList.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";


    } else if ($module == "SmartSms") {
        $query = "
        from aicrm_smartsms 
        inner join aicrm_smartsmscf as aicrm_smartsmscf on aicrm_smartsmscf.smartsmsid=aicrm_smartsms.smartsmsid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_smartsms.smartsmsid
        left join aicrm_groups as aicrm_groupsSmartSms on aicrm_groupsSmartSms.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersSmartSms on aicrm_usersSmartSms.id = aicrm_crmentity.smownerid
        
        LEFT JOIN aicrm_users as aicrm_usersModifiedSmartSms on aicrm_crmentity.modifiedby = aicrm_usersModifiedSmartSms.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorSmartSms on aicrm_crmentity.smcreatorid = aicrm_usersCreatorSmartSms.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    } else if ($module == "Smartemail") {
        $query = "
        from aicrm_smartemail 
        inner join aicrm_smartemailcf as aicrm_smartemailcf on aicrm_smartemailcf.smartemailid=aicrm_smartemail.smartemailid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_smartemail.smartemailid
        left join aicrm_groups as aicrm_groupsSmartEmail on aicrm_groupsSmartEmail.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersSmartEmail on aicrm_usersSmartEmail.id = aicrm_crmentity.smownerid
        
        LEFT JOIN aicrm_users as aicrm_usersModifiedSmartEmail on aicrm_crmentity.modifiedby = aicrm_usersModifiedSmartEmail.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorSmartEmail on aicrm_crmentity.smcreatorid = aicrm_usersCreatorSmartEmail.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    } else if ($module == "Smartquestionnaire") {
        $query = "
        from aicrm_smartquestionnaire 
        inner join aicrm_smartquestionnairecf as aicrm_smartquestionnairecf on aicrm_smartquestionnairecf.smartquestionnaireid=aicrm_smartquestionnaire.smartquestionnaireid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_smartquestionnaire.smartquestionnaireid
        left join aicrm_groups as aicrm_groupsSmartquestionnaire on aicrm_groupsSmartquestionnaire.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersSmartquestionnaire on aicrm_usersSmartquestionnaire.id = aicrm_crmentity.smownerid
        
        LEFT JOIN aicrm_users as aicrm_usersModifiedSmartquestionnaire on aicrm_crmentity.modifiedby = aicrm_usersModifiedSmartquestionnaire.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorSmartquestionnaire on aicrm_crmentity.smcreatorid = aicrm_usersCreatorSmartquestionnaire.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";
        

    } else if ($module == "Sparepartlist") {
        $query = "
        from aicrm_sparepartlist 
        inner join aicrm_sparepartlistcf as aicrm_sparepartlistcf on aicrm_sparepartlistcf.sparepartlistid=aicrm_sparepartlist.sparepartlistid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_sparepartlist.sparepartlistid
        left join aicrm_groups as aicrm_groupsSparepartlist on aicrm_groupsSparepartlist.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersSparepartlist on aicrm_usersSparepartlist.id = aicrm_crmentity.smownerid
        left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
        left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid 
        LEFT JOIN aicrm_users as aicrm_usersModifiedSparepartlist on aicrm_crmentity.modifiedby = aicrm_usersModifiedSparepartlist.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorSparepartlist on aicrm_crmentity.smcreatorid = aicrm_usersCreatorSparepartlist.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Sparepart") {
        $query = "
        from aicrm_sparepart 
        inner join aicrm_sparepartcf as aicrm_sparepartcf on aicrm_sparepartcf.sparepartid=aicrm_sparepart.sparepartid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_sparepart.sparepartid
        left join aicrm_groups as aicrm_groupsSparepart on aicrm_groupsSparepart.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersSparepart on aicrm_usersSparepart.id = aicrm_crmentity.smownerid
        left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
        left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid 
        LEFT JOIN aicrm_users as aicrm_usersModifiedSparepart on aicrm_crmentity.modifiedby = aicrm_usersModifiedSparepart.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorSparepart on aicrm_crmentity.smcreatorid = aicrm_usersCreatorSparepart.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Errorslist") {
        $query = "
        from aicrm_errorslist inner join aicrm_errorslistcf as aicrm_errorslistcf on aicrm_errorslistcf.errorslistid=aicrm_errorslist.errorslistid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_errorslist.errorslistid
        left join aicrm_groups as aicrm_groupsErrorslist on aicrm_groupsErrorslist.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersErrorslist on aicrm_usersErrorslist.id = aicrm_crmentity.smownerid
        left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedErrorslist on aicrm_crmentity.modifiedby = aicrm_usersModifiedErrorslist.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorErrorslist on aicrm_crmentity.smcreatorid = aicrm_usersCreatorErrorslist.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Errors") {
        $query = "
        from aicrm_errors 
        inner join aicrm_errorscf as aicrm_errorscf on aicrm_errorscf.errorsid=aicrm_errors.errorsid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_errors.errorsid
        left join aicrm_groups as aicrm_groupsErrors on aicrm_groupsErrors.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersErrors on aicrm_usersErrors.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedErrors on aicrm_crmentity.modifiedby = aicrm_usersModifiedErrors.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorErrors on aicrm_crmentity.smcreatorid = aicrm_usersCreatorErrors.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Competitor") {
        $query = "
        from aicrm_competitor 
        inner join aicrm_competitorcf as aicrm_competitorcf on aicrm_competitorcf.competitorid=aicrm_competitor.competitorid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_competitor.competitorid
        left join aicrm_groups as aicrm_groupsCompetitor on aicrm_groupsCompetitor.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersCompetitor on aicrm_usersCompetitor.id = aicrm_crmentity.smownerid
        
        LEFT JOIN aicrm_users as aicrm_usersModifiedCompetitor on aicrm_crmentity.modifiedby = aicrm_usersModifiedCompetitor.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorCompetitor on aicrm_crmentity.smcreatorid = aicrm_usersCreatorCompetitor.id

        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "

        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Deal") {
        $query = "
        from aicrm_deal 
        inner join aicrm_dealcf as aicrm_dealcf on aicrm_dealcf.dealid=aicrm_deal.dealid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_deal.dealid
        -- LEFT JOIN aicrm_account aicrm_accountDeal ON aicrm_accountDeal.accountid = aicrm_deal.accountid
        left join aicrm_groups as aicrm_groupsDeal on aicrm_groupsDeal.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersDeal on aicrm_usersDeal.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedDeal on aicrm_crmentity.modifiedby = aicrm_usersModifiedDeal.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorDeal on aicrm_crmentity.smcreatorid = aicrm_usersCreatorDeal.id

        LEFT JOIN aicrm_campaign AS aicrm_campaignDeal ON aicrm_deal.campaignid = aicrm_campaignDeal.campaignid

        
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Questionnaire") {
        $query = "
        from aicrm_questionnaire 
        inner join aicrm_questionnairecf as aicrm_questionnairecf on aicrm_questionnairecf.questionnaireid=aicrm_questionnaire.questionnaireid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_questionnaire.questionnaireid
        left join aicrm_groups as aicrm_groupsQuestionnaire on aicrm_groupsQuestionnaire.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersQuestionnaire on aicrm_usersQuestionnaire.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedQuestionnaire on aicrm_crmentity.modifiedby = aicrm_usersModifiedQuestionnaire.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorQuestionnaire on aicrm_crmentity.smcreatorid = aicrm_usersCreatorQuestionnaire.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Questionnairetemplate") {
        $query = "
        from aicrm_questionnairetemplate 
        inner join aicrm_questionnairetemplatecf as aicrm_questionnairetemplatecf on aicrm_questionnairetemplatecf.questionnairetemplateid=aicrm_questionnairetemplate.questionnairetemplateid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_questionnairetemplate.questionnairetemplateid
        left join aicrm_campaign as aicrm_campaignQuestionnairetemplate on aicrm_campaignQuestionnairetemplate.campaignid = aicrm_questionnairetemplate.campaignid
        left join aicrm_groups as aicrm_groupsQuestionnairetemplate on aicrm_groupsQuestionnairetemplate.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersQuestionnairetemplate on aicrm_usersQuestionnairetemplate.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedQuestionnairetemplate on aicrm_crmentity.modifiedby = aicrm_usersModifiedQuestionnairetemplate.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorQuestionnairetemplate on aicrm_crmentity.smcreatorid = aicrm_usersCreatorQuestionnairetemplate.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";
    }else if ($module == "Questionnaireanswer") {
        $query = "
        from aicrm_questionnaireanswer 
        inner join aicrm_questionnaireanswercf as aicrm_questionnaireanswercf on aicrm_questionnaireanswercf.questionnaireanswerid=aicrm_questionnaireanswer.questionnaireanswerid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_questionnaireanswer.questionnaireanswerid
        left join aicrm_groups as aicrm_groupsQuestionnaireanswer on aicrm_groupsQuestionnaireanswer.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersQuestionnaireanswer on aicrm_usersQuestionnaireanswer.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedQuestionnaireanswer on aicrm_crmentity.modifiedby = aicrm_usersModifiedQuestionnaireanswer.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorQuestionnaireanswer on aicrm_crmentity.smcreatorid = aicrm_usersCreatorQuestionnaireanswer.id

        LEFT JOIN aicrm_account AS aicrm_accountQuestionnaireanswer ON aicrm_accountQuestionnaireanswer.accountid = aicrm_questionnaireanswer.accountid
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Voucher") {
        $query = "
        from aicrm_voucher 
        inner join aicrm_vouchercf as aicrm_vouchercf on aicrm_vouchercf.voucherid=aicrm_voucher.voucherid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_voucher.voucherid
        LEFT JOIN aicrm_account aicrm_accountVoucher ON aicrm_accountVoucher.accountid = aicrm_voucher.accountid
        left join aicrm_groups as aicrm_groupsVoucher on aicrm_groupsVoucher.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersVoucher on aicrm_usersVoucher.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedVoucher on aicrm_crmentity.modifiedby = aicrm_usersModifiedVoucher.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorVoucher on aicrm_crmentity.smcreatorid = aicrm_usersCreatorVoucher.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Promotion") {
        $query = "
        from aicrm_promotion 
        inner join aicrm_promotioncf as aicrm_promotioncf on aicrm_promotioncf.promotionid=aicrm_promotion.promotionid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_promotion.promotionid
        left join aicrm_campaign as aicrm_campaignPromotion on aicrm_campaignPromotion.campaignid = aicrm_promotion.campaignid
        left join aicrm_groups as aicrm_groupsPromotion on aicrm_groupsPromotion.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersPromotion on aicrm_usersPromotion.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedPromotion on aicrm_crmentity.modifiedby = aicrm_usersModifiedPromotion.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorPromotion on aicrm_crmentity.smcreatorid = aicrm_usersCreatorPromotion.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    }else if ($module == "Documents") {
        $query = "
        from aicrm_notes 
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_notes.notesid
        left join aicrm_attachmentsfolder on aicrm_attachmentsfolder.folderid=aicrm_notes.folderid
        left join aicrm_groups as aicrm_groupsDocuments on aicrm_groupsDocuments.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersDocuments on aicrm_usersDocuments.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users as aicrm_usersModifiedDocuments on aicrm_crmentity.modifiedby = aicrm_usersModifiedDocuments.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorDocuments on aicrm_crmentity.smcreatorid = aicrm_usersCreatorDocuments.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    } else if ($module == "Competitorproduct") {
        $query = "
        from aicrm_competitorproduct 
        inner join aicrm_competitorproductcf as aicrm_competitorproductcf on aicrm_competitorproductcf.competitorproductid=aicrm_competitorproduct.competitorproductid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_competitorproduct.competitorproductid
        left join aicrm_groups as aicrm_groupsCompetitorproduct on aicrm_groupsCompetitorproduct.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersCompetitorproduct on aicrm_usersCompetitorproduct.id = aicrm_crmentity.smownerid

        left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
        left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid

        LEFT JOIN aicrm_users as aicrm_usersModifiedCompetitorproduct on aicrm_crmentity.modifiedby = aicrm_usersModifiedCompetitorproduct.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorCompetitorproduct on aicrm_crmentity.smcreatorid = aicrm_usersCreatorCompetitorproduct.id
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        WHERE aicrm_crmentity.deleted = 0 ";

    } else if ($module == "Faq") {
        $query = "from aicrm_faq 
        inner join aicrm_faqcf on aicrm_faqcf.faqid = aicrm_faq.faqid
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_faq.faqid 
        left join aicrm_groups as aicrm_groupsFaq on aicrm_groupsFaq.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_usersFaq on aicrm_crmentity.smownerid=aicrm_usersFaq.id 
        left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
        left join aicrm_users as user on aicrm_crmentity.smownerid=user.id

        LEFT JOIN aicrm_users as aicrm_usersModifiedFaq on aicrm_crmentity.modifiedby = aicrm_usersModifiedFaq.id
        LEFT JOIN aicrm_users as aicrm_usersCreatorFaq on aicrm_crmentity.smcreatorid = aicrm_usersCreatorFaq.id  
        " . $this->getRelatedModulesQuery($module, $this->secondarymodule) . "
        where aicrm_crmentity.deleted=0 ";

    }else {
        if ($module != '') {
            $focus = CRMEntity::getInstance($module);
            $query = $focus->generateReportsQuery($module)
            . $this->getRelatedModulesQuery($module, $this->secondarymodule)
            . " WHERE aicrm_crmentity.deleted=0";
        }
    }
    $log->info("ReportRun :: Successfully returned getReportsQuery" . $module);
    //echo $query;
    return $query;
}

    /** function to get query for the given reportid,filterlist,type
     *  @ param $reportid : Type integer
     *  @ param $filterlist : Type Array
     *  @ param $module : Type String
     *  this returns join query for the report
    */

    function sGetSQLforReport($reportid, $filterlist, $type = '')
    {
        global $log;
        $columnlist = $this->getQueryColumnsList($reportid);
        $groupslist = $this->getGroupingList($reportid);
        $stdfilterlist = $this->getStdFilterList($reportid);
        $columnstotallist = $this->getColumnsTotal($reportid);
        $advfilterlist = $this->getAdvFilterList($reportid);
        // print_r($advfilterlist); exit();
        $this->totallist = $columnstotallist;
        global $current_user;
        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        require('user_privileges/sharing_privileges_' . $current_user->id . '.php');
        $tab_id = getTabid($this->primarymodule);
        //Fix for ticket #4915.
        $selectlist = $columnlist;
        //columns list
        if (isset($selectlist)) {
            $selectedcolumns = implode(", ", $selectlist);
        }
        //groups list
        if (isset($groupslist)) {
            $groupsquery = implode(", ", $groupslist);
        }

        //standard list
        if (isset($stdfilterlist)) {
            $stdfiltersql = implode(", ", $stdfilterlist);
        }
        if (isset($filterlist)) {
            $stdfiltersql = implode(", ", $filterlist);
        }
        //columns to total list
        if (isset($columnstotallist)) {
            $columnstotalsql = implode(", ", $columnstotallist);
        }
        //advanced filterlist
        if (isset($advfilterlist)) {
            $advfiltersql = implode(" and ", $advfilterlist);
        }
        if ($stdfiltersql != "") {
            $wheresql = " and " . $stdfiltersql;
        }
        if ($advfiltersql != "") {
            $wheresql .= " and " . $advfiltersql;
        }
        //echo $stdfiltersql . "<br>";
        $reportquery = $this->getReportsQuery($this->primarymodule);
        // If we don't have access to any columns, let us select one column and limit result to shown we have not results
        // Fix for: http://trac.aicrm.com/cgi-bin/trac.cgi/ticket/4758 - Prasad
        $allColumnsRestricted = false;

        if ($type == 'COLUMNSTOTOTAL') {
            if ($columnstotalsql != '') {
                $reportquery = "SELECT " . $columnstotalsql . " " . $reportquery . " " . $wheresql;
            }
        } else {
            //echo $selectedcolumns."<br>";
            if ($selectedcolumns == '') {
                // Fix for: http://trac.aicrm.com/cgi-bin/trac.cgi/ticket/4758 - Prasad

                $selectedcolumns = "''"; // "''" to get blank column name
                $allColumnsRestricted = true;
            }

            $reportquery = "SELECT " . $selectedcolumns . " " . $reportquery . " " . $wheresql;
            //echo $wheresql ."<br>";
        }
        if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
            $sec_parameter = getListViewSecurityParameter($this->primarymodule);
            $reportquery .= " " . $sec_parameter;
        }

        $sec_modules = split(":", $this->secondarymodule);
        foreach ($sec_modules as $i => $key) {
            $table_id = getTabid($key);
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$table_id] == 3) {
                $sec_parameter = getSecListViewSecurityParameter($key);
                $reportquery .= " " . $sec_parameter;
            }
        }

        //if($tab_id == 9 || $tab_id == 16)
        //$reportquery.=" group by aicrm_activity.activityid ";

        if (trim($groupsquery) != "" && empty($type)) {
            $reportquery .= " order by " . $groupsquery;
            //echo " order by ".$groupsquery."<br>";
        }

        // Prasad: No columns selected so limit the number of rows directly.
        if ($allColumnsRestricted) {
            $reportquery .= " limit 0";
        }

        $log->info("ReportRun :: Successfully returned sGetSQLforReport" . $reportid);
        // echo '<pre>'.$reportquery.'</pre><br>'; //error_sql
        // exit();
        return $reportquery;

    }

    /** function to get the report output in HTML,PDF,TOTAL,PRINT,PRINTTOTAL formats depends on the argument $outputformat
     *  @ param $outputformat : Type String (valid parameters HTML,PDF,TOTAL,PRINT,PRINT_TOTAL)
     *  @ param $filterlist : Type Array
     *  This returns HTML Report if $outputformat is HTML
     *        Array for PDF if  $outputformat is PDF
     *        HTML strings for TOTAL if $outputformat is TOTAL
     *        Array for PRINT if $outputformat is PRINT
     *        HTML strings for TOTAL fields  if $outputformat is PRINTTOTAL
     *        HTML strings for
     */

    // Performance Optimization: Added parameter directOutput to avoid building big-string!
    function GenerateReport($outputformat, $filterlist, $directOutput = false)
    {
        global $adb, $current_user, $php_max_execution_time;
        global $modules, $app_strings;
        global $mod_strings, $current_language;

        //### tak 20161003 add files json data result ####
        global $root_directory;
        $path = $root_directory . "export/report/";
        $a_json = array();
        // ######### tak file json

        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        $modules_selected = array();
        $modules_selected[] = $this->primarymodule;
        if (!empty($this->secondarymodule)) {
            $sec_modules = split(":", $this->secondarymodule);
            for ($i = 0; $i < count($sec_modules); $i++) {
                $modules_selected[] = $sec_modules[$i];
            }
        }

        // Update Currency Field list
        $currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel from aicrm_field WHERE uitype in (71,72)", array());
        if ($currencyfieldres) {
            foreach ($currencyfieldres as $currencyfieldrow) {
                $modprefixedlabel = getTabModuleName($currencyfieldrow['tabid']) . ' ' . $currencyfieldrow['fieldlabel'];
                $modprefixedlabel = str_replace(' ', '_', $modprefixedlabel);
                if (!in_array($modprefixedlabel, $this->convert_currency) && !in_array($modprefixedlabel, $this->append_currency_symbol_to_value)) {
                    $this->convert_currency[] = $modprefixedlabel;
                }
            }
        }


        if ($outputformat == "HTML") {
            $sSQL = $this->sGetSQLforReport($this->reportid, $filterlist);
            //echo $sSQL;
            $result = $adb->query($sSQL);
            $error_msg = $adb->database->ErrorMsg();
            if (!$result && $error_msg != '') {
                // Performance Optimization: If direct output is requried
                if ($directOutput) {
                    echo getTranslatedString('LBL_REPORT_GENERATION_FAILED', $currentModule) . "<br>" . $error_msg;
                    $error_msg = false;
                }
                // END
                return $error_msg;
            }

            // Performance Optimization: If direct output is required
            if ($directOutput) {
                echo '<table cellpadding="5" cellspacing="0" align="center" class="rptTable"><tr>';
            }
            // END

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
                $picklistarray = $this->getAccessPickListValues();
            if ($result) {
                $y = $adb->num_fields($result);

                $arrayHeaders = Array();
                for ($x = 0; $x < $y; $x++) {
                    $fld = $adb->field_name($result, $x);
                    if (in_array($this->getLstringforReportHeaders($fld->name), $arrayHeaders)) {
                        $headerLabel = str_replace("_", " ", $fld->name);
                        $arrayHeaders[] = $headerLabel;
                    } else {
                        $headerLabel = str_replace($modules, " ", $this->getLstringforReportHeaders($fld->name));
                        $headerLabel = str_replace("_", " ", $this->getLstringforReportHeaders($fld->name));
                        $arrayHeaders[] = $headerLabel;
                    }
                    /*STRING TRANSLATION starts */
                    $mod_name = split(' ', $headerLabel, 2);
                    $module = '';
                    if (in_array($mod_name[0], $modules_selected)) {
                        $module = getTranslatedString($mod_name[0], $mod_name[0]);
                    }

                    if (!empty($this->secondarymodule)) {
                        if ($module != '') {
                            $headerLabel_tmp = $module . " " . getTranslatedString($mod_name[1], $mod_name[0]);
                        } else {
                            $headerLabel_tmp = getTranslatedString($mod_name[0] . " " . $mod_name[1]);
                        }
                    } else {
                        if ($module != '') {
                            $headerLabel_tmp = getTranslatedString($mod_name[1], $mod_name[0]);
                        } else {
                            $headerLabel_tmp = getTranslatedString($mod_name[0] . " " . $mod_name[1]);
                        }
                    }
                    if ($headerLabel == $headerLabel_tmp) $headerLabel = getTranslatedString($headerLabel_tmp);
                    else $headerLabel = $headerLabel_tmp;
                    /*STRING TRANSLATION ends */
                    $header .= "<td class='rptCellLabel'>" . $headerLabel . "</td>";

                    // Performance Optimization: If direct output is required
                    if ($directOutput) {
                        echo $header;
                        $header = '';
                    }
                    // END
                }

                // Performance Optimization: If direct output is required
                if ($directOutput) {
                    echo '</tr><tr>';
                }
                // END

                $noofrows = $adb->num_rows($result);
                //echo $noofrows;exit;
                $custom_field_values = $adb->fetch_array($result);
                
                $groupslist = $this->getGroupingList($this->reportid);
                $column_definitions = $adb->getFieldsDefinition($result);

                do {
                    //### tak 20161003 add files json data result ####
                    $a_data = array();
                    //###############

                    $arraylists = Array();
                    if (count($groupslist) == 1) {
                        $newvalue = $custom_field_values[0];
                    } elseif (count($groupslist) == 2) {
                        $newvalue = $custom_field_values[0];
                        $snewvalue = $custom_field_values[1];
                    } elseif (count($groupslist) == 3) {
                        $newvalue = $custom_field_values[0];
                        $snewvalue = $custom_field_values[1];
                        $tnewvalue = $custom_field_values[2];
                    }
                    if ($newvalue == "") $newvalue = "-";

                    if ($snewvalue == "") $snewvalue = "-";

                    if ($tnewvalue == "") $tnewvalue = "-";

                    $valtemplate .= "<tr>";

                    // Performance Optimization
                    if ($directOutput) {
                        echo $valtemplate;
                        $valtemplate = '';
                    }
                    // END

                    for ($i = 0; $i < $y; $i++) {
                        $fld = $adb->field_name($result, $i);
                        $fld_type = $column_definitions[$i]->type;
                        if (in_array($fld->name, $this->convert_currency)) {
                            if ($custom_field_values[$i] != '')
                                $fieldvalue = convertFromMasterCurrency($custom_field_values[$i], $current_user->conv_rate);
                            else
                                $fieldvalue = getTranslatedString($custom_field_values[$i]);
                        } elseif (in_array($fld->name, $this->append_currency_symbol_to_value)) {
                            $curid_value = explode("::", $custom_field_values[$i]);
                            $currency_id = $curid_value[0];
                            $currency_value = $curid_value[1];
                            $cur_sym_rate = getCurrencySymbolandCRate($currency_id);
                            if ($custom_field_values[$i] != '')
                                $fieldvalue = $cur_sym_rate['symbol'] . " " . $currency_value;
                            else
                                $fieldvalue = getTranslatedString($custom_field_values[$i]);
                        } elseif ($fld->name == "PurchaseOrder_Currency" || $fld->name == "SalesOrder_Currency"
                            || $fld->name == "Invoice_Currency" || $fld->name == "Quotes_Currency"
                        ){
                            if ($custom_field_values[$i] != '')
                                $fieldvalue = getCurrencyName($custom_field_values[$i]);
                            else
                                $fieldvalue = getTranslatedString($custom_field_values[$i]);
                        } else {
                            if ($custom_field_values[$i] != '')
                                $fieldvalue = getTranslatedString($custom_field_values[$i]);
                            else
                                $fieldvalue = getTranslatedString($custom_field_values[$i]);
                        }
                        $fieldvalue = str_replace("<", "&lt;", $fieldvalue);
                        $fieldvalue = str_replace(">", "&gt;", $fieldvalue);

                        //check for Roll based pick list
                        $temp_val = $fld->name;
                        if (is_array($picklistarray))
                            if (array_key_exists($temp_val, $picklistarray)) {
                                if (!in_array($custom_field_values[$i], $picklistarray[$fld->name]) && $custom_field_values[$i] != '')
                                    $fieldvalue = $app_strings['LBL_NOT_ACCESSIBLE'];

                            }
                            if (is_array($picklistarray[1]))
                                if (array_key_exists($temp_val, $picklistarray[1])) {
                                    $temp = explode(",", str_ireplace(' |##| ', ',', $fieldvalue));
                                    $temp_val = Array();
                                    foreach ($temp as $key => $val) {
                                        if (!in_array(trim($val), $picklistarray[1][$fld->name]) && trim($val) != '') {
                                            $temp_val[] = $app_strings['LBL_NOT_ACCESSIBLE'];
                                        } else
                                        $temp_val[] = $val;
                                    }
                                    $fieldvalue = (is_array($temp_val)) ? implode(", ", $temp_val) : '';
                                }

                                if ($fieldvalue == "") {
                                    $fieldvalue = "-";
                                } else if (stristr($fieldvalue, "|##|")) {
                                    $fieldvalue = str_ireplace(' |##| ', ', ', $fieldvalue);
                                } else if ($fld_type == "date" || $fld_type == "datetime") {
                                    $fieldvalue = getDisplayDate($fieldvalue);
                                }
                        //### tak 20161003 add files json data result ####
                                $a_data[$fld->name] = $fieldvalue;
                        //#############
                                if (($lastvalue == $fieldvalue) && $this->reporttype == "summary") {
                                    if ($this->reporttype == "summary") {
                                        $valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";
                                    } else {
                                        $valtemplate .= "<td class='rptData'>" . $fieldvalue . "</td>";
                                    }
                                } else if (($secondvalue === $fieldvalue) && $this->reporttype == "summary") {
                                    if ($lastvalue === $newvalue) {
                                        $valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";
                                    } else {
                                        $valtemplate .= "<td class='rptGrpHead'>" . $fieldvalue . "</td>";
                                    }
                                } else if (($thirdvalue === $fieldvalue) && $this->reporttype == "summary") {
                                    if ($secondvalue === $snewvalue) {
                                        $valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";
                                    } else {
                                        $valtemplate .= "<td class='rptGrpHead'>" . $fieldvalue . "</td>";
                                    }
                                } else {
                                    if ($this->reporttype == "tabular") {
                                        $valtemplate .= "<td class='rptData'>" . $fieldvalue . "</td>";
                                    } else {
                                        $valtemplate .= "<td class='rptGrpHead'>" . $fieldvalue . "</td>";
                                    }
                                }

                        // Performance Optimization: If direct output is required
                                if ($directOutput) {
                                    echo $valtemplate;
                                    $valtemplate = '';
                                }
                        // END
                            }

                    //### tak 20161003 add files json data result ####
                            $a_json[] = $a_data;
                    //######  tak

                            $valtemplate .= "</tr>";

                    // Performance Optimization: If direct output is required
                            if ($directOutput) {
                                echo $valtemplate;
                                $valtemplate = '';
                            }
                    // END

                            $lastvalue = $newvalue;
                            $secondvalue = $snewvalue;
                            $thirdvalue = $tnewvalue;
                            $arr_val[] = $arraylists;
                            set_time_limit($php_max_execution_time);
                        } while ($custom_field_values = $adb->fetch_array($result));
                // Performance Optimization
                /*if ($directOutput) {
                    echo "</tr></table>";
                    echo "<script type='text/javascript' id='__reportrun_directoutput_recordcount_script'>
                    if($('_reportrun_total')) $('_reportrun_total').innerHTML=$noofrows;</script>";*/

                    if ($directOutput) {
                    //echo $directOutput;
                        echo "</tr></table>";        
                        echo "<script type='text/javascript' id='__reportrun_directoutput_recordcount_script'>
                        if($('#_reportrun_total')) $( '#_reportrun_total' ).html( $noofrows );</script>";          
                    } else {

                        $sHTML = '<table cellpadding="5" cellspacing="0" align="center" class="rptTable">
                        <tr>' .
                        $header
                        . '<!-- BEGIN values -->
                        <tr>' .
                        $valtemplate
                        . '</tr>
                        </table>';
                    }
                //<<<<<<<<construct HTML>>>>>>>>>>>>
                    $return_data[] = $sHTML;
                    $return_data[] = $noofrows;
                    $return_data[] = $sSQL;
                //### tak 20161003 add files json data result ####
                file_put_contents($path."analytic_".$this->reportid."_".$current_user->id.".json", json_encode($a_json));
                //##### tak
                    return $return_data;
                }
            } elseif ($outputformat == "PDF") {

                $sSQL = $this->sGetSQLforReport($this->reportid, $filterlist);
                $result = $adb->query($sSQL);
                if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
                    $picklistarray = $this->getAccessPickListValues();

                if ($result) {
                    $y = $adb->num_fields($result);
                    $noofrows = $adb->num_rows($result);
                    $custom_field_values = $adb->fetch_array($result);
                    $column_definitions = $adb->getFieldsDefinition($result);

                    do {
                        $arraylists = Array();
                        for ($i = 0; $i < $y; $i++) {
                            $fld = $adb->field_name($result, $i);
                            if (in_array($fld->name, $this->convert_currency)) {
                                $fieldvalue = convertFromMasterCurrency($custom_field_values[$i], $current_user->conv_rate);
                            } elseif (in_array($fld->name, $this->append_currency_symbol_to_value)) {
                                $curid_value = explode("::", $custom_field_values[$i]);
                                $currency_id = $curid_value[0];
                                $currency_value = $curid_value[1];
                                $cur_sym_rate = getCurrencySymbolandCRate($currency_id);
                                $fieldvalue = $cur_sym_rate['symbol'] . " " . $currency_value;
                            } elseif ($fld->name == "PurchaseOrder_Currency" || $fld->name == "SalesOrder_Currency"
                                || $fld->name == "Invoice_Currency" || $fld->name == "Quotes_Currency"
                            ) {
                                $fieldvalue = getCurrencyName($custom_field_values[$i]);
                            } else {
                                $fieldvalue = getTranslatedString($custom_field_values[$i]);
                            }
                            $append_cur = str_replace($fld->name, "", decode_html($this->getLstringforReportHeaders($fld->name)));
                            $headerLabel = str_replace("_", " ", $fld->name);
                            /*STRING TRANSLATION starts */
                            $mod_name = split(' ', $headerLabel, 2);
                            $module = '';
                            if (in_array($mod_name[0], $modules_selected))
                                $module = getTranslatedString($mod_name[0], $mod_name[0]);

                            if (!empty($this->secondarymodule)) {
                                if ($module != '') {
                                    $headerLabel_tmp = $module . " " . getTranslatedString($mod_name[1], $mod_name[0]);
                                } else {
                                    $headerLabel_tmp = getTranslatedString($mod_name[0] . " " . $mod_name[1]);
                                }
                            } else {
                                if ($module != '') {
                                    $headerLabel_tmp = getTranslatedString($mod_name[1], $mod_name[0]);
                                } else {
                                    $headerLabel_tmp = getTranslatedString($mod_name[0] . " " . $mod_name[1]);
                                }
                            }
                            if ($headerLabel == $headerLabel_tmp) $headerLabel = getTranslatedString($headerLabel_tmp);
                            else $headerLabel = $headerLabel_tmp;
                            /*STRING TRANSLATION starts */
                            if (trim($append_cur) != "") $headerLabel .= $append_cur;

                            $fieldvalue = str_replace("<", "&lt;", $fieldvalue);
                            $fieldvalue = str_replace(">", "&gt;", $fieldvalue);

                        // Check for role based pick list
                            $temp_val = $fld->name;
                            if (is_array($picklistarray))
                                if (array_key_exists($temp_val, $picklistarray)) {
                                    if (!in_array($custom_field_values[$i], $picklistarray[$fld->name]) && $custom_field_values[$i] != '') {
                                        $fieldvalue = $app_strings['LBL_NOT_ACCESSIBLE'];
                                    }
                                }
                                if (is_array($picklistarray[1]))
                                    if (array_key_exists($temp_val, $picklistarray[1])) {
                                        $temp = explode(",", str_ireplace(' |##| ', ',', $fieldvalue));
                                        $temp_val = Array();
                                        foreach ($temp as $key => $val) {
                                            if (!in_array(trim($val), $picklistarray[1][$fld->name]) && trim($val) != '') {
                                                $temp_val[] = $app_strings['LBL_NOT_ACCESSIBLE'];
                                            } else
                                            $temp_val[] = $val;
                                        }
                                        $fieldvalue = (is_array($temp_val)) ? implode(", ", $temp_val) : '';
                                    }

                                    if ($fieldvalue == "") {
                                        $fieldvalue = "-";
                                    } else if (stristr($fieldvalue, "|##|")) {
                                        $fieldvalue = str_ireplace(' |##| ', ', ', $fieldvalue);
                                    } else if ($fld_type == "date" || $fld_type == "datetime") {
                                        $fieldvalue = getDisplayDate($fieldvalue);
                                    }
                                    if (array_key_exists($this->getLstringforReportHeaders($fld->name), $arraylists))
                                        $arraylists[$headerLabel] = $fieldvalue;
                                    else
                                        $arraylists[$headerLabel] = $fieldvalue;
                                }
                                $arr_val[] = $arraylists;
                                set_time_limit($php_max_execution_time);
                            } while ($custom_field_values = $adb->fetch_array($result));

                            return $arr_val;
                        }
                    } elseif ($outputformat == "TOTALHTML") {
                        $escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX');
                        $sSQL = $this->sGetSQLforReport($this->reportid, $filterlist, "COLUMNSTOTOTAL");
                        if (isset($this->totallist)) {
                            if ($sSQL != "") {
                                $result = $adb->query($sSQL);
                                $y = $adb->num_fields($result);
                                $custom_field_values = $adb->fetch_array($result);
                                $coltotalhtml .= "<table align='center' width='60%' cellpadding='3' cellspacing='0' border='0' class='rptTable'><tr><td class='rptCellLabel'>" . $mod_strings[Totals] . "</td><td class='rptCellLabel'>" . $mod_strings[SUM] . "</td><td class='rptCellLabel'>" . $mod_strings[AVG] . "</td><td class='rptCellLabel'>" . $mod_strings[MIN] . "</td><td class='rptCellLabel'>" . $mod_strings[MAX] . "</td></tr>";

                    // Performation Optimization: If Direct output is desired
                                if ($directOutput) {
                                    echo $coltotalhtml;
                                    $coltotalhtml = '';
                                }
                    // END

                                foreach ($this->totallist as $key => $value) {
                                    $fieldlist = explode(":", $key);
                                    $mod_query = $adb->pquery("SELECT distinct(tabid) as tabid, uitype as uitype from aicrm_field where tablename = ? and columnname=?", array($fieldlist[1], $fieldlist[2]));
                                    if ($adb->num_rows($mod_query) > 0) {
                                        $module_name = getTabName($adb->query_result($mod_query, 0, 'tabid'));
                                        $fieldlabel = trim(str_replace($escapedchars, " ", $fieldlist[3]));
                                        $fieldlabel = str_replace("_", " ", $fieldlabel);
                                        if ($module_name) {
                                            $field = getTranslatedString($module_name) . " " . getTranslatedString($fieldlabel, $module_name);
                                        } else {
                                            $field = getTranslatedString($module_name) . " " . getTranslatedString($fieldlabel);
                                        }
                                    }
                                    $uitype_arr[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $adb->query_result($mod_query, 0, "uitype");
                                    $totclmnflds[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $field;
                                }
                                for ($i = 0; $i < $y; $i++) {
                                    $fld = $adb->field_name($result, $i);
                                    $keyhdr[$fld->name] = $custom_field_values[$i];
                                }

                                foreach ($totclmnflds as $key => $value) {
                                    $coltotalhtml .= '<tr class="rptGrpHead" valign=top>';
                                    $col_header = trim(str_replace($modules, " ", $value));
                                    $fld_name_1 = $this->primarymodule . "_" . trim($value);
                                    $fld_name_2 = $this->secondarymodule . "_" . trim($value);
                                    if ($uitype_arr[$value] == 71 || in_array($fld_name_1, $this->convert_currency) || in_array($fld_name_1, $this->append_currency_symbol_to_value)
                                        || in_array($fld_name_2, $this->convert_currency) || in_array($fld_name_2, $this->append_currency_symbol_to_value)
                                    ) {
                                        $col_header .= " (" . $app_strings['LBL_IN'] . " " . $current_user->currency_symbol . ")";
                                    $convert_price = true;
                                } else {
                                    $convert_price = false;
                                }
                                $coltotalhtml .= '<td class="rptData">' . $col_header . '</td>';
                                $value = trim($key);
                                $arraykey = $value . '_SUM';
                                if (isset($keyhdr[$arraykey])) {
                                    if ($convert_price)
                                        $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                    else
                                        $conv_value = $keyhdr[$arraykey];
                                    $coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
                                } else {
                                    $coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
                                }

                                $arraykey = $value . '_AVG';
                                if (isset($keyhdr[$arraykey])) {
                                    if ($convert_price)
                                        $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                    else
                                        $conv_value = $keyhdr[$arraykey];
                                    $coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
                                } else {
                                    $coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
                                }

                                $arraykey = $value . '_MIN';
                                if (isset($keyhdr[$arraykey])) {
                                    if ($convert_price)
                                        $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                    else
                                        $conv_value = $keyhdr[$arraykey];
                                    $coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
                                } else {
                                    $coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
                                }

                                $arraykey = $value . '_MAX';
                                if (isset($keyhdr[$arraykey])) {
                                    if ($convert_price)
                                        $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                    else
                                        $conv_value = $keyhdr[$arraykey];
                                    $coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
                                } else {
                                    $coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
                                }

                                $coltotalhtml .= '<tr>';

                        // Performation Optimization: If Direct output is desired
                                if ($directOutput) {
                                    echo $coltotalhtml;
                                    $coltotalhtml = '';
                                }
                        // END
                            }

                            $coltotalhtml .= "</table>";

                    // Performation Optimization: If Direct output is desired
                            if ($directOutput) {
                                echo $coltotalhtml;
                                $coltotalhtml = '';
                            }
                    // END
                        }
                    }
                    return $coltotalhtml;
                } elseif ($outputformat == "PRINT") {
                    $sSQL = $this->sGetSQLforReport($this->reportid, $filterlist);
                    $result = $adb->query($sSQL);
                    if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
                        $picklistarray = $this->getAccessPickListValues();

                    if ($result) {
                        $y = $adb->num_fields($result);
                        $arrayHeaders = Array();
                        for ($x = 0; $x < $y; $x++) {
                            $fld = $adb->field_name($result, $x);
                            if (in_array($this->getLstringforReportHeaders($fld->name), $arrayHeaders)) {
                                $headerLabel = str_replace("_", " ", $fld->name);
                                $arrayHeaders[] = $headerLabel;
                            } else {
                                $headerLabel = str_replace($modules, " ", $this->getLstringforReportHeaders($fld->name));
                                $arrayHeaders[] = $headerLabel;
                            }
                            /*STRING TRANSLATION starts */
                            $mod_name = split(' ', $headerLabel, 2);
                            $module = '';
                            if (in_array($mod_name[0], $modules_selected)) {
                                $module = getTranslatedString($mod_name[0], $mod_name[0]);
                            }

                            if (!empty($this->secondarymodule)) {
                                if ($module != '') {
                                    $headerLabel_tmp = $module . " " . getTranslatedString($mod_name[1], $mod_name[0]);
                                } else {
                                    $headerLabel_tmp = getTranslatedString($mod_name[0] . " " . $mod_name[1]);
                                }
                            } else {
                                if ($module != '') {
                                    $headerLabel_tmp = getTranslatedString($mod_name[1], $mod_name[0]);
                                } else {
                                    $headerLabel_tmp = getTranslatedString($mod_name[0] . " " . $mod_name[1]);
                                }
                            }
                            if ($headerLabel == $headerLabel_tmp) $headerLabel = getTranslatedString($headerLabel_tmp);
                            else $headerLabel = $headerLabel_tmp;
                            /*STRING TRANSLATION ends */
                            $header .= "<th>" . $headerLabel . "</th>";
                        }
                        $noofrows = $adb->num_rows($result);
                        $custom_field_values = $adb->fetch_array($result);
                        $groupslist = $this->getGroupingList($this->reportid);

                        $column_definitions = $adb->getFieldsDefinition($result);

                        do {
                            $arraylists = Array();
                            if (count($groupslist) == 1) {
                                $newvalue = $custom_field_values[0];
                            } elseif (count($groupslist) == 2) {
                                $newvalue = $custom_field_values[0];
                                $snewvalue = $custom_field_values[1];
                            } elseif (count($groupslist) == 3) {
                                $newvalue = $custom_field_values[0];
                                $snewvalue = $custom_field_values[1];
                                $tnewvalue = $custom_field_values[2];
                            }

                            if ($newvalue == "") $newvalue = "-";

                            if ($snewvalue == "") $snewvalue = "-";

                            if ($tnewvalue == "") $tnewvalue = "-";

                            $valtemplate .= "<tr>";

                            for ($i = 0; $i < $y; $i++) {
                                $fld = $adb->field_name($result, $i);
                                if (in_array($fld->name, $this->convert_currency)) {
                                    $fieldvalue = convertFromMasterCurrency($custom_field_values[$i], $current_user->conv_rate);
                                } elseif (in_array($fld->name, $this->append_currency_symbol_to_value)) {
                                    $curid_value = explode("::", $custom_field_values[$i]);
                                    $currency_id = $curid_value[0];
                                    $currency_value = $curid_value[1];
                                    $cur_sym_rate = getCurrencySymbolandCRate($currency_id);
                                    $fieldvalue = $cur_sym_rate['symbol'] . " " . $currency_value;
                                } elseif ($fld->name == "PurchaseOrder_Currency" || $fld->name == "SalesOrder_Currency"
                                    || $fld->name == "Invoice_Currency" || $fld->name == "Quotes_Currency"
                                ) {
                                    $fieldvalue = getCurrencyName($custom_field_values[$i]);
                                } else {
                                    $fieldvalue = getTranslatedString($custom_field_values[$i]);
                                }

                                $fieldvalue = str_replace("<", "&lt;", $fieldvalue);
                                $fieldvalue = str_replace(">", "&gt;", $fieldvalue);

                        //Check For Role based pick list
                                $temp_val = $fld->name;
                                if (is_array($picklistarray))
                                    if (array_key_exists($temp_val, $picklistarray)) {
                                        if (!in_array($custom_field_values[$i], $picklistarray[$fld->name]) && $custom_field_values[$i] != '') {
                                            $fieldvalue = $app_strings['LBL_NOT_ACCESSIBLE'];
                                        }
                                    }
                                    if (is_array($picklistarray[1]))
                                        if (array_key_exists($temp_val, $picklistarray[1])) {

                                            $temp = explode(",", str_ireplace(' |##| ', ',', $fieldvalue));
                                            $temp_val = Array();
                                            foreach ($temp as $key => $val) {
                                                if (!in_array(trim($val), $picklistarray[1][$fld->name]) && trim($val) != '') {
                                                    $temp_val[] = $app_strings['LBL_NOT_ACCESSIBLE'];
                                                } else
                                                $temp_val[] = $val;
                                            }
                                            $fieldvalue = (is_array($temp_val)) ? implode(", ", $temp_val) : '';
                                        }


                                        if ($fieldvalue == "") {
                                            $fieldvalue = "-";
                                        } else if (stristr($fieldvalue, "|##|")) {
                                            $fieldvalue = str_ireplace(' |##| ', ', ', $fieldvalue);
                                        } else if ($fld_type == "date" || $fld_type == "datetime") {
                                            $fieldvalue = getDisplayDate($fieldvalue);
                                        }
                                        if (($lastvalue == $fieldvalue) && $this->reporttype == "summary") {
                                            if ($this->reporttype == "summary") {
                                                $valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";
                                            } else {
                                                $valtemplate .= "<td>" . $fieldvalue . "</td>";
                                            }
                                        } else if (($secondvalue == $fieldvalue) && $this->reporttype == "summary") {
                                            if ($lastvalue == $newvalue) {
                                                $valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";
                                            } else {
                                                $valtemplate .= "<td>" . $fieldvalue . "</td>";
                                            }
                                        } else if (($thirdvalue == $fieldvalue) && $this->reporttype == "summary") {
                                            if ($secondvalue == $snewvalue) {
                                                $valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";
                                            } else {
                                                $valtemplate .= "<td>" . $fieldvalue . "</td>";
                                            }
                                        } else {
                                            if ($this->reporttype == "tabular") {
                                                $valtemplate .= "<td>" . $fieldvalue . "</td>";
                                            } else {
                                                $valtemplate .= "<td>" . $fieldvalue . "</td>";
                                            }
                                        }
                                    }
                                    $valtemplate .= "</tr>";
                                    $lastvalue = $newvalue;
                                    $secondvalue = $snewvalue;
                                    $thirdvalue = $tnewvalue;
                                    $arr_val[] = $arraylists;
                                    set_time_limit($php_max_execution_time);
                                } while ($custom_field_values = $adb->fetch_array($result));

                                $sHTML = '<tr>' . $header . '</tr>' . $valtemplate;
                                $return_data[] = $sHTML;
                                $return_data[] = $noofrows;
                                return $return_data;
                            }
                        } elseif ($outputformat == "PRINT_TOTAL") {
                            $escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX');
                            $sSQL = $this->sGetSQLforReport($this->reportid, $filterlist, "COLUMNSTOTOTAL");
                            if (isset($this->totallist)) {
                                if ($sSQL != "") {
                                    $result = $adb->query($sSQL);
                                    $y = $adb->num_fields($result);
                                    $custom_field_values = $adb->fetch_array($result);

                                    $coltotalhtml .= '<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center" class="printReport" ><tr><th>' . $mod_strings[Totals] . '</th><th>' . $mod_strings[SUM] . '</th><th>' . $mod_strings[AVG] . '</th><th>' . $mod_strings[MIN] . '</th><th>' . $mod_strings[MAX] . '</th></tr>';

                                    foreach ($this->totallist as $key => $value) {
                                        $fieldlist = explode(":", $key);
                                        $totclmnflds[str_replace($escapedchars, " ", $fieldlist[3])] = str_replace($escapedchars, " ", $fieldlist[3]);
                                    }

                                    for ($i = 0; $i < $y; $i++) {
                                        $fld = $adb->field_name($result, $i);
                                        $keyhdr[$fld->name] = $custom_field_values[$i];

                                    }
                                    foreach ($totclmnflds as $key => $value) {
                                        $coltotalhtml .= '<tr valign=top>';
                                        $col_header = getTranslatedString(trim(str_replace($modules, " ", $value)));
                                        $fld_name_1 = $this->primarymodule . "_" . trim($value);
                                        $fld_name_2 = $this->secondarymodule . "_" . trim($value);
                                        if (in_array($fld_name_1, $this->convert_currency) || in_array($fld_name_1, $this->append_currency_symbol_to_value)
                                            || in_array($fld_name_2, $this->convert_currency) || in_array($fld_name_2, $this->append_currency_symbol_to_value)
                                        ) {
                                            $col_header .= " (" . $app_strings['LBL_IN'] . " " . $current_user->currency_symbol . ")";
                                        $convert_price = true;
                                    } else {
                                        $convert_price = false;
                                    }
                                    $coltotalhtml .= '<td>' . $col_header . '</td>';

                                    $arraykey = trim($value) . '_SUM';
                                    if (isset($keyhdr[$arraykey])) {
                                        if ($convert_price)
                                            $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                        else
                                            $conv_value = $keyhdr[$arraykey];
                                        $coltotalhtml .= '<td>' . $conv_value . '</td>';
                                    } else {
                                        $coltotalhtml .= '<td>&nbsp;</td>';
                                    }

                                    $arraykey = trim($value) . '_AVG';
                                    if (isset($keyhdr[$arraykey])) {
                                        if ($convert_price)
                                            $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                        else
                                            $conv_value = $keyhdr[$arraykey];
                                        $coltotalhtml .= '<td>' . $conv_value . '</td>';
                                    } else {
                                        $coltotalhtml .= '<td>&nbsp;</td>';
                                    }

                                    $arraykey = trim($value) . '_MIN';
                                    if (isset($keyhdr[$arraykey])) {
                                        if ($convert_price)
                                            $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                        else
                                            $conv_value = $keyhdr[$arraykey];
                                        $coltotalhtml .= '<td>' . $conv_value . '</td>';
                                    } else {
                                        $coltotalhtml .= '<td>&nbsp;</td>';
                                    }

                                    $arraykey = trim($value) . '_MAX';
                                    if (isset($keyhdr[$arraykey])) {
                                        if ($convert_price)
                                            $conv_value = convertFromMasterCurrency($keyhdr[$arraykey], $current_user->conv_rate);
                                        else
                                            $conv_value = $keyhdr[$arraykey];
                                        $coltotalhtml .= '<td>' . $conv_value . '</td>';
                                    } else {
                                        $coltotalhtml .= '<td>&nbsp;</td>';
                                    }

                                    $coltotalhtml .= '<tr>';
                                }

                                $coltotalhtml .= "</table>";
                            }
                        }
                        return $coltotalhtml;
                    }
                }

    //<<<<<<<new>>>>>>>>>>
                function getColumnsTotal($reportid)
                {
        // Have we initialized it already?
                    if ($this->_columnstotallist !== false) {
                        return $this->_columnstotallist;
                    }

                    global $adb;
                    global $modules;
                    global $log, $current_user;

                    $query = "select * from aicrm_reportmodules where reportmodulesid =?";
                    $res = $adb->pquery($query, array($reportid));
                    $modrow = $adb->fetch_array($res);
                    $premod = $modrow["primarymodule"];
                    $secmod = $modrow["secondarymodules"];
                    $coltotalsql = "select aicrm_reportsummary.* from aicrm_report";
                    $coltotalsql .= " inner join aicrm_reportsummary on aicrm_report.reportid = aicrm_reportsummary.reportsummaryid";
                    $coltotalsql .= " where aicrm_report.reportid =?";

                    $result = $adb->pquery($coltotalsql, array($reportid));

                    while ($coltotalrow = $adb->fetch_array($result)) {
                        $fieldcolname = $coltotalrow["columnname"];
                        if ($fieldcolname != "none") {
                            $fieldlist = explode(":", $fieldcolname);
                            $field_tablename = $fieldlist[1];
                            $field_columnname = $fieldlist[2];

                            $mod_query = $adb->pquery("SELECT distinct(tabid) as tabid from aicrm_field where tablename = ? and columnname=?", array($fieldlist[1], $fieldlist[2]));
                            if ($adb->num_rows($mod_query) > 0) {
                                $module_name = getTabName($adb->query_result($mod_query, 0, 'tabid'));
                                $fieldlabel = trim($fieldlist[3]);
                                if ($module_name) {
                                    $field_columnalias = $module_name . "_" . $fieldlist[3];
                                } else {
                                    $field_columnalias = $module_name . "_" . $fieldlist[3];
                                }
                            }

                //$field_columnalias = $fieldlist[3];
                            $field_permitted = false;
                            if (CheckColumnPermission($field_tablename, $field_columnname, $premod) != "false") {
                                $field_permitted = true;
                            } else {
                                $mod = split(":", $secmod);
                                foreach ($mod as $key) {
                                    if (CheckColumnPermission($field_tablename, $field_columnname, $key) != "false") {
                                        $field_permitted = true;
                                    }
                                }
                            }
                            if ($field_permitted == true) {
                                $field = $field_tablename . "." . $field_columnname;
                                if ($field_tablename == 'aicrm_products' && $field_columnname == 'unit_price') {
                        // Query needs to be rebuild to get the value in user preferred currency. [innerProduct and actual_unit_price are table and column alias.]
                                    $field = " innerProduct.actual_unit_price";
                                }
                                if ($field_tablename == 'aicrm_service' && $field_columnname == 'unit_price') {
                        // Query needs to be rebuild to get the value in user preferred currency. [innerProduct and actual_unit_price are table and column alias.]
                                    $field = " innerService.actual_unit_price";
                                }
                                if (($field_tablename == 'aicrm_invoice' || $field_tablename == 'aicrm_quotes' || $field_tablename == 'aicrm_purchaseorder' || $field_tablename == 'aicrm_salesorder')
                                    && ($field_columnname == 'total' || $field_columnname == 'subtotal' || $field_columnname == 'discount_amount' || $field_columnname == 's_h_amount')
                                ) {
                                    $field = " $field_tablename.$field_columnname/$field_tablename.conversion_rate ";
                            }
                            if ($fieldlist[4] == 2) {
                                $stdfilterlist[$fieldcolname] = "sum($field) '" . $field_columnalias . "'";
                            }
                            if ($fieldlist[4] == 3) {
                        //Fixed average calculation issue due to NULL values ie., when we use avg() function, NULL values will be ignored.to avoid this we use (sum/count) to find average.
                        //$stdfilterlist[$fieldcolname] = "avg(".$fieldlist[1].".".$fieldlist[2].") '".$fieldlist[3]."'";
                                $stdfilterlist[$fieldcolname] = "(sum($field)/count(*)) '" . $field_columnalias . "'";
                            }
                            if ($fieldlist[4] == 4) {
                                $stdfilterlist[$fieldcolname] = "min($field) '" . $field_columnalias . "'";
                            }
                            if ($fieldlist[4] == 5) {
                                $stdfilterlist[$fieldcolname] = "max($field) '" . $field_columnalias . "'";
                            }
                        }
                    }
                }
        // Save the information
                $this->_columnstotallist = $stdfilterlist;

                $log->info("ReportRun :: Successfully returned getColumnsTotal" . $reportid);
                return $stdfilterlist;
            }
    //<<<<<<new>>>>>>>>>


    /** function to get query for the columns to total for the given reportid
     *  @ param $reportid : Type integer
     *  This returns columnstoTotal query for the reportid
     */

    function getColumnsToTotalColumns($reportid)
    {
        global $adb;
        global $modules;
        global $log;

        $sreportstdfiltersql = "select aicrm_reportsummary.* from aicrm_report";
        $sreportstdfiltersql .= " inner join aicrm_reportsummary on aicrm_report.reportid = aicrm_reportsummary.reportsummaryid";
        $sreportstdfiltersql .= " where aicrm_report.reportid =?";

        $result = $adb->pquery($sreportstdfiltersql, array($reportid));
        $noofrows = $adb->num_rows($result);

        for ($i = 0; $i < $noofrows; $i++) {
            $fieldcolname = $adb->query_result($result, $i, "columnname");

            if ($fieldcolname != "none") {
                $fieldlist = explode(":", $fieldcolname);
                if ($fieldlist[4] == 2) {
                    $sSQLList[] = "sum(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
                }
                if ($fieldlist[4] == 3) {
                    $sSQLList[] = "avg(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
                }
                if ($fieldlist[4] == 4) {
                    $sSQLList[] = "min(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
                }
                if ($fieldlist[4] == 5) {
                    $sSQLList[] = "max(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
                }
            }
        }
        if (isset($sSQLList)) {
            $sSQL = implode(",", $sSQLList);
        }
        $log->info("ReportRun :: Successfully returned getColumnsToTotalColumns" . $reportid);
        return $sSQL;
    }

    /** Function to convert the Report Header Names into i18n
     * @param $fldname : Type Varchar
     *  Returns Language Converted Header Strings
     **/
    function getLstringforReportHeaders($fldname)
    {
        global $modules, $current_language, $current_user, $app_strings;
        $rep_header = ltrim(str_replace($modules, " ", $fldname));
        $rep_header_temp = ereg_replace(" ", "_", $rep_header);
        $rep_module = ereg_replace('_' . $rep_header_temp, "", $fldname);
        $temp_mod_strings = return_module_language($current_language, $rep_module);
        // htmlentities should be decoded in field names (eg. &). Noticed for fields like 'Terms & Conditions', 'S&H Amount'
        $rep_header = decode_html($rep_header);
        $curr_symb = "";
        if (in_array($fldname, $this->convert_currency)) {
            $curr_symb = " (" . $app_strings['LBL_IN'] . " " . $current_user->currency_symbol . ")";
        }
        if ($temp_mod_strings[$rep_header] != '') {
            $rep_header = $temp_mod_strings[$rep_header];
        }
        $rep_header .= $curr_symb;
        return $rep_header;
    }

    /** Function to get picklist value array based on profile
     *          *  returns permitted fields in array format
     **/


    function getAccessPickListValues()
    {
        global $adb;
        global $current_user;
        $id = array(getTabid($this->primarymodule));
        if ($this->secondarymodule != '')
            array_push($id, getTabid($this->secondarymodule));

        $query = 'select fieldname,columnname,fieldid,fieldlabel,tabid,uitype from aicrm_field where tabid in(' . generateQuestionMarks($id) . ') and uitype in (15,33,55)'; //and columnname in (?)';
        $result = $adb->pquery($query, $id);//,$select_column));
        $roleid = $current_user->roleid;
        $subrole = getRoleSubordinates($roleid);
        if (count($subrole) > 0) {
            $roleids = $subrole;
            array_push($roleids, $roleid);
        } else {
            $roleids = $roleid;
        }

        $temp_status = Array();
        for ($i = 0; $i < $adb->num_rows($result); $i++) {
            $fieldname = $adb->query_result($result, $i, "fieldname");
            $fieldlabel = $adb->query_result($result, $i, "fieldlabel");
            $tabid = $adb->query_result($result, $i, "tabid");
            $uitype = $adb->query_result($result, $i, "uitype");

            $fieldlabel1 = str_replace(" ", "_", $fieldlabel);
            $keyvalue = getTabModuleName($tabid) . "_" . $fieldlabel1;
            $fieldvalues = Array();
            if (count($roleids) > 1) {
                $mulsel = "select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where roleid='H2' and picklistid in (select picklistid from aicrm_$fieldname) order by sortid asc";
            } else {
                $mulsel = "select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where roleid='H2' and picklistid in (select picklistid from aicrm_$fieldname) order by sortid asc";
            }
            if ($fieldname != 'firstname')
                $mulselresult = $adb->query($mulsel);
            for ($j = 0; $j < $adb->num_rows($mulselresult); $j++) {
                $fldvalue = $adb->query_result($mulselresult, $j, $fieldname);
                if (in_array($fldvalue, $fieldvalues)) continue;
                $fieldvalues[] = $fldvalue;
            }
            $field_count = count($fieldvalues);
            if ($uitype == 15 && $field_count > 0 && ($fieldname == 'taskstatus' || $fieldname == 'eventstatus')) {
                $temp_count = count($temp_status[$keyvalue]);
                if ($temp_count > 0) {
                    for ($t = 0; $t < $field_count; $t++) {
                        $temp_status[$keyvalue][($temp_count + $t)] = $fieldvalues[$t];
                    }
                    $fieldvalues = $temp_status[$keyvalue];
                } else
                $temp_status[$keyvalue] = $fieldvalues;
            }

            if ($uitype == 33)
                $fieldlists[1][$keyvalue] = $fieldvalues;
            else if ($uitype == 55 && $fieldname == 'salutationtype')
                $fieldlists[$keyvalue] = $fieldvalues;
            else if ($uitype == 15)
                $fieldlists[$keyvalue] = $fieldvalues;
        }
        return $fieldlists;
    }


}

?>