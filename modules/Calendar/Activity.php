<?php
/*********************************************************************************
 * The contents of this file are branchid to the SugarCRM Public License Version 1.1.2
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
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/modules/Activities/Activity.php,v 1.26 2005/03/26 10:42:13 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/RenderRelatedListUI.php');
require_once('data/CRMEntity.php');

// Task is used to store customer information.
class Activity extends CRMEntity
{
    var $log;
    var $db;
    var $table_name = "aicrm_activity";
    var $table_index = 'activityid';
    var $reminder_table = 'aicrm_activity_reminder';
    var $tab_name = Array('aicrm_crmentity', 'aicrm_activity', 'aicrm_activitycf');

    var $tab_name_index = Array('aicrm_crmentity' => 'crmid', 'aicrm_activity' => 'activityid', 'aicrm_activitycf' => 'activityid', 'aicrm_seactivityrel' => 'activityid', 'aicrm_cntactivityrel' => 'activityid', 'aicrm_salesmanactivityrel' => 'activityid', 'aicrm_activity_reminder' => 'activity_id', 'aicrm_recurringevents' => 'activityid' , 'aicrm_commentplan' => 'activityid');

    var $column_fields = Array();
    var $sortby_fields = Array('branchid', 'due_date', 'date_start', 'smownerid', 'activitytype','lastname', 'event_id', 'accountname');    //Sorting is added for due date and start date

    // This is used to retrieve related aicrm_fields from form posts.
    var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contactname', 'contact_phone', 'contact_email', 'parent_name');

    /**
     * Mandatory table for supporting custom fields.
     */
    var $customFieldTable = Array('aicrm_activity', 'activityid');

    // This is the list of aicrm_fields that are in the lists.
    var $list_fields = Array(
        'หัวข้อเรื่อง' => Array('aicrm_activity' => 'activitytype'),
        'วันที่เริ่ม' => Array('aicrm_activity' => 'date_start'),
        'เวลาเริ่ม' => Array('aicrm_activity' => 'time_start'),
        'เวลาที่สิ้นสุด' => Array('aicrm_activity' => 'time_end'),
        //'ชื่อลูกค้า' => Array('aicrm_activitycf' => 'accountid'),
        'ผู้รับผิดชอบ' => Array('aicrm_crmentity' => 'assigned_user_id'),
    );

    var $list_fields_name = Array(
        'หัวข้อเรื่อง' => 'activitytype',
        'วันที่เริ่ม' => 'date_start',
        'เวลาเริ่ม' => 'time_start',
        'เวลาที่สิ้นสุด' => 'time_end',
        //'ชื่อลูกค้า' => 'accountid',
        'ผู้รับผิดชอบ' => 'assigned_user_id',
    );

    var $search_fields = Array(
        'หัวข้อเรื่อง' => Array('aicrm_activity' => 'activitytype'),
        'วันที่เริ่ม' => Array('aicrm_activity' => 'date_start'),
        'เวลาเริ่ม' => Array('aicrm_activity' => 'time_start'),
        'เวลาที่สิ้นสุด' => Array('aicrm_activity' => 'time_end'),
        /*'ชื่อลูกค้า' => Array('aicrm_activitycf' => 'accountid'),*/
        'ผู้รับผิดชอบ' => Array('aicrm_crmentity' => 'assigned_user_id')
    );

    var $search_fields_name = Array(
        'หัวข้อเรื่อง' => 'activitytype',
        'วันที่เริ่ม' => 'date_start',
        'เวลาเริ่ม' => 'time_start',
        'เวลาที่สิ้นสุด' => 'time_end',
        /*'ชื่อลูกค้า' => 'accountid',*/
        'ผู้รับผิดชอบ' => 'assigned_user_id',
    );

    var $range_fields = Array(
        'name',
        'date_modified',
        'start_date',
        'id',
        'status',
        'date_due',
        'time_start',
        'time_end',
        'description',
        'contact_name',
        'duehours',
        'dueminutes',
        'location',
        'smownerid',
        'description',
        'sale_report',
        'activitytype',
    );

    var $list_link_field = 'activitytype';
    //Added these variables which are used as default order by and sortorder in ListView
    var $default_order_by = 'aicrm_activity.activityid';
    var $default_sort_order = 'DESC';

    //var $groupTable = Array('aicrm_activitygrouprelation','activityid');

    function Activity()
    {
        $this->log = LoggerManager::getLogger('Calendar');
        $this->db = PearDatabase::getInstance();
        $this->column_fields = getColumnFields('Calendar');
    }

    function save_module($module)
    {
        global $adb;

        //Insert Comment
        $this->insertIntoServiceCommentTable("aicrm_commentplan",'Calendar');

        if ($_REQUEST['action'] != 'ActivityAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave') {
            //Based on the total Number of rows we will save the product relationship with this entity
            saveInventoryProductDetails($this, 'Activity');
        }
        
    }

    function insertIntoServiceCommentTable($table_name, $module)
    {
        global $log;
        $log->info("in insertIntoServiceCommentTable  ".$table_name."    module is  ".$module);
        global $adb;
        global $current_user;
        
        $current_time = $adb->formatDate(date('YmdHis'), true);
        if($this->column_fields['assigned_user_id'] != ''){
            $ownertype = 'user';
        }
        else
        {
            $ownertype = 'customer';
        }
        if($this->column_fields['commentplan'] != ''){         
            $comment = $this->column_fields['commentplan'];
        }
        else
        {
            $comment = $_REQUEST['commentplan'];
        }
        if($comment!=""){
            $sql = "insert into aicrm_commentplan values(?,?,?,?,?,?)";
            $params = array('', $this->id, from_html($comment), $current_user->id, $ownertype, $current_time);
            $adb->pquery($sql, $params);
        }
    }


    /** Function to insert values in aicrm_activity_reminder_popup table for the specified module
     * @param $cbmodule -- module:: Type varchar
     */
    function insertIntoActivityReminderPopup($cbmodule)
    {

        global $adb;

        $cbrecord = $this->id;
        if (isset($cbmodule) && isset($cbrecord)) {
            $cbdate = $this->column_fields['date_start'];
            $cbtime = $this->column_fields['time_start'];

            $reminder_query = "SELECT reminderid FROM aicrm_activity_reminder_popup WHERE semodule = ? and recordid = ?";
            $reminder_params = array($cbmodule, $cbrecord);
            $reminderidres = $adb->pquery($reminder_query, $reminder_params);

            $reminderid = null;
            if ($adb->num_rows($reminderidres) > 0) {
                $reminderid = $adb->query_result($reminderidres, 0, "reminderid");
            }

            if (isset($reminderid)) {
                $callback_query = "UPDATE aicrm_activity_reminder_popup set status = 0, date_start = ?, time_start = ? WHERE reminderid = ?";
                $callback_params = array($cbdate, $cbtime, $reminderid);
            } else {
                $callback_query = "INSERT INTO aicrm_activity_reminder_popup (recordid, semodule, date_start, time_start) VALUES (?,?,?,?)";
                $callback_params = array($cbrecord, $cbmodule, $cbdate, $cbtime);
            }

            $adb->pquery($callback_query, $callback_params);
        }
    }


    /** Function to insert values in aicrm_activity_remainder table for the specified module,
     * @param $table_name -- table name:: Type varchar
     * @param $module -- module:: Type varchar
     */
    function insertIntoReminderTable($table_name, $module, $recurid)
    {
        global $log;
        $log->info("in insertIntoReminderTable  " . $table_name . "    module is  " . $module);
        if ($_REQUEST['set_reminder'] == 'Yes') {
            $log->debug("set reminder is set");
            $rem_days = $_REQUEST['remdays'];
            $log->debug("rem_days is " . $rem_days);
            $rem_hrs = $_REQUEST['remhrs'];
            $log->debug("rem_hrs is " . $rem_hrs);
            $rem_min = $_REQUEST['remmin'];
            $log->debug("rem_minutes is " . $rem_min);
            $reminder_time = $rem_days * 24 * 60 + $rem_hrs * 60 + $rem_min;
            $log->debug("reminder_time is " . $reminder_time);
            if ($recurid == "") {
                if ($_REQUEST['mode'] == 'edit') {
                    $this->activity_reminder($this->id, $reminder_time, 0, $recurid, 'edit');
                } else {
                    $this->activity_reminder($this->id, $reminder_time, 0, $recurid, '');
                }
            } else {
                $this->activity_reminder($this->id, $reminder_time, 0, $recurid, '');
            }
        } elseif ($_REQUEST['set_reminder'] == 'No') {
            $this->activity_reminder($this->id, '0', 0, $recurid, 'delete');
        }
    }


    // Code included by Jaguar - starts

    /** Function to insert values in aicrm_recurringevents table for the specified tablename,module
     * @param $recurObj -- Recurring Object:: Type varchar
     */
    function insertIntoRecurringTable(& $recurObj)
    {
        global $log, $adb;
        $log->info("in insertIntoRecurringTable  ");
        $st_date = $recurObj->startdate->get_formatted_date();
        $log->debug("st_date " . $st_date);
        $end_date = $recurObj->enddate->get_formatted_date();
        $log->debug("end_date is set " . $end_date);
        $type = $recurObj->recur_type;
        $log->debug("type is " . $type);
        $flag = "true";

        if ($_REQUEST['mode'] == 'edit') {
            $activity_id = $this->id;

            $sql = 'select min(recurringdate) AS min_date,max(recurringdate) AS max_date, recurringtype, activityid from aicrm_recurringevents where activityid=? group by activityid, recurringtype';
            $result = $adb->pquery($sql, array($activity_id));
            $noofrows = $adb->num_rows($result);
            for ($i = 0; $i < $noofrows; $i++) {
                $recur_type_b4_edit = $adb->query_result($result, $i, "recurringtype");
                $date_start_b4edit = $adb->query_result($result, $i, "min_date");
                $end_date_b4edit = $adb->query_result($result, $i, "max_date");
            }
            if (($st_date == $date_start_b4edit) && ($end_date == $end_date_b4edit) && ($type == $recur_type_b4_edit)) {
                if ($_REQUEST['set_reminder'] == 'Yes') {
                    $sql = 'delete from aicrm_activity_reminder where activity_id=?';
                    $adb->pquery($sql, array($activity_id));
                    $sql = 'delete  from aicrm_recurringevents where activityid=?';
                    $adb->pquery($sql, array($activity_id));
                    $flag = "true";
                } elseif ($_REQUEST['set_reminder'] == 'No') {
                    $sql = 'delete  from aicrm_activity_reminder where activity_id=?';
                    $adb->pquery($sql, array($activity_id));
                    $flag = "false";
                } else
                $flag = "false";
            } else {
                $sql = 'delete from aicrm_activity_reminder where activity_id=?';
                $adb->pquery($sql, array($activity_id));
                $sql = 'delete  from aicrm_recurringevents where activityid=?';
                $adb->pquery($sql, array($activity_id));
            }
        }
        $date_array = $recurObj->recurringdates;
        if (isset($recurObj->recur_freq) && $recurObj->recur_freq != null)
            $recur_freq = $recurObj->recur_freq;
        else
            $recur_freq = 1;
        if ($recurObj->recur_type == 'Daily' || $recurObj->recur_type == 'Yearly')
            $recurringinfo = $recurObj->recur_type;
        elseif ($recurObj->recur_type == 'Weekly') {
            $recurringinfo = $recurObj->recur_type;
            if ($recurObj->dayofweek_to_rpt != null)
                $recurringinfo = $recurringinfo . '::' . implode('::', $recurObj->dayofweek_to_rpt);
        } elseif ($recurObj->recur_type == 'Monthly') {
            $recurringinfo = $recurObj->recur_type . '::' . $recurObj->repeat_monthby;
            if ($recurObj->repeat_monthby == 'date')
                $recurringinfo = $recurringinfo . '::' . $recurObj->rptmonth_datevalue;
            else
                $recurringinfo = $recurringinfo . '::' . $recurObj->rptmonth_daytype . '::' . $recurObj->dayofweek_to_rpt[0];
        } else {
            $recurringinfo = '';
        }
        if ($flag == "true") {
            for ($k = 0; $k < count($date_array); $k++) {
                $tdate = $date_array[$k];
                if ($tdate <= $end_date) {
                    $max_recurid_qry = 'select max(recurringid) AS recurid from aicrm_recurringevents;';
                    $result = $adb->pquery($max_recurid_qry, array());
                    $noofrows = $adb->num_rows($result);
                    for ($i = 0; $i < $noofrows; $i++) {
                        $recur_id = $adb->query_result($result, $i, "recurid");
                    }
                    $current_id = $recur_id + 1;
                    $recurring_insert = "insert into aicrm_recurringevents values (?,?,?,?,?,?)";
                    $rec_params = array($current_id, $this->id, $tdate, $type, $recur_freq, $recurringinfo);
                    $adb->pquery($recurring_insert, $rec_params);
                    if ($_REQUEST['set_reminder'] == 'Yes') {
                        $this->insertIntoReminderTable("aicrm_activity_reminder", $module, $current_id, '');
                    }
                }
            }
        }
    }


    /** Function to insert values in aicrm_invitees table for the specified module,tablename ,invitees_array
     * @param $table_name -- table name:: Type varchar
     * @param $module -- module:: Type varchar
     * @param $invitees_array Array
     */
    function insertIntoInviteeTable($module, $invitees_array)
    {
        global $log, $adb;
        $log->debug("Entering insertIntoInviteeTable(" . $module . "," . $invitees_array . ") method ...");
        if ($this->mode == 'edit') {
            $sql = "delete from aicrm_invitees where activityid=?";
            $adb->pquery($sql, array($this->id));
        }
        foreach ($invitees_array as $inviteeid) {
            if ($inviteeid != '') {
                $query = "insert into aicrm_invitees values(?,?)";
                $adb->pquery($query, array($this->id, $inviteeid));
            }
        }
        $log->debug("Exiting insertIntoInviteeTable method ...");

    }


    /** Function to insert values in aicrm_salesmanactivityrel table for the specified module
     * @param $module -- module:: Type varchar
     */

    function insertIntoSmActivityRel($module)
    {
        global $adb;
        global $current_user;
        if ($this->mode == 'edit') {
            $sql = "delete from aicrm_salesmanactivityrel where activityid=?";
            $adb->pquery($sql, array($this->id));
        }

        $user_sql = $adb->pquery("select count(*) as count from aicrm_users where id=?", array($this->column_fields['assigned_user_id']));
        if ($adb->query_result($user_sql, 0, 'count') != 0) {
            $sql_qry = "insert into aicrm_salesmanactivityrel (smid,activityid) values(?,?)";
            $adb->pquery($sql_qry, array($this->column_fields['assigned_user_id'], $this->id));

            if (isset($_REQUEST['inviteesid']) && $_REQUEST['inviteesid'] != '') {
                $selected_users_string = $_REQUEST['inviteesid'];
                $invitees_array = explode(';', $selected_users_string);
                foreach ($invitees_array as $inviteeid) {
                    if ($inviteeid != '') {
                        $resultcheck = $adb->pquery("select * from aicrm_salesmanactivityrel where activityid=? and smid=?", array($this->id, $inviteeid));
                        if ($adb->num_rows($resultcheck) != 1) {
                            $query = "insert into aicrm_salesmanactivityrel values(?,?)";
                            $adb->pquery($query, array($inviteeid, $this->id));
                        }
                    }
                }
            }
        }
    }


    // Mike Crowe Mod --------------------------------------------------------Default ordering for us

    /**
     * Function to get sort order
     * return string  $sorder    - sortorder string either 'ASC' or 'DESC'
     */
    function getSortOrder()
    {
        global $log;
        $log->debug("Entering getSortOrder() method ...");
        if (isset($_REQUEST['sorder']))
            $sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
        else
            $sorder = (($_SESSION['ACTIVITIES_SORT_ORDER'] != '') ? ($_SESSION['ACTIVITIES_SORT_ORDER']) : ($this->default_sort_order));
        $log->debug("Exiting getSortOrder method ...");
        return $sorder;
    }

    /**
     * Function to get order by
     * return string  $order_by    - fieldname(eg: 'branchid')
     */
    function getOrderBy()
    {
        //echo 5555;
        global $log;
        $log->debug("Entering getOrderBy() method ...");

        $use_default_order_by = '';
        if (PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
            $use_default_order_by = $this->default_order_by;
        }

        if (isset($_REQUEST['order_by']))
            $order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
        else
            $order_by = (($_SESSION['ACTIVITIES_ORDER_BY'] != '') ? ($_SESSION['ACTIVITIES_ORDER_BY']) : ($use_default_order_by));
        $log->debug("Exiting getOrderBy method ...");

        return $order_by;
    }

    //Function Call for Related List -- Start
    /**
     * Function to get Activity related Contacts
     * @param  integer $id - activityid
     * returns related Contacts record in array format
     */
    function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions = false)
    {
        global $log, $singlepane_view, $currentModule, $current_user;
        $log->debug("Entering get_contacts(" . $id . ") method ...");
        $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);

        $parenttab = getParentTab();

        $returnset = '&return_module=' . $this_module . '&return_action=DetailView&activity_mode=Events&return_id=' . $id;

        $search_string = '';
        $button = '';

        if ($actions) {
            if (is_string($actions)) $actions = explode(',', strtoupper($actions));
            if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
                $button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab$search_string','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
            }
        }

        $query = 'select aicrm_users.user_name,aicrm_contactdetails.accountid,aicrm_contactdetails.contactid, aicrm_contactdetails.firstname,aicrm_contactdetails.lastname, aicrm_contactdetails.department, aicrm_contactdetails.title, aicrm_contactdetails.email, aicrm_contactdetails.phone, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime from aicrm_contactdetails inner join aicrm_cntactivityrel on aicrm_cntactivityrel.contactid=aicrm_contactdetails.contactid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid where aicrm_cntactivityrel.activityid=' . $id . ' and aicrm_crmentity.deleted=0';

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_contacts method ...");
        return $return_value;
    }

    /**
     * Function to get Activity related Users
     * @param  integer $id - activityid
     * returns related Users record in array format
     */

    function get_users($id)
    {
        global $log;
        $log->debug("Entering get_contacts(" . $id . ") method ...");
        global $app_strings;

        $focus = new Users();

        $button = '<input title="Change" accessKey="" tabindex="2" type="button" class="crmbutton small edit" 
        value="' . getTranslatedString('LBL_SELECT_USER_BUTTON_LABEL') . '" name="button" LANGUAGE=javascript 
        onclick=\'return window.open("index.php?module=Users&return_module=Calendar&return_action={$return_modname}&activity_mode=Events&action=Popup&popuptype=detailview&form=EditView&form_submit=true&select=enable&return_id=' . $id . '&recordid=' . $id . '","test","width=640,height=525,resizable=0,scrollbars=0")\';>';

        $returnset = '&return_module=Calendar&return_action=CallRelatedList&return_id=' . $id;

        $query = 'SELECT aicrm_users.id, aicrm_users.first_name,aicrm_users.last_name, aicrm_users.user_name, aicrm_users.email1, aicrm_users.email2, aicrm_users.status, aicrm_users.is_admin, aicrm_user2role.roleid, aicrm_users.yahoo_id, aicrm_users.phone_home, aicrm_users.phone_work, aicrm_users.phone_mobile, aicrm_users.phone_other, aicrm_users.phone_fax,aicrm_activity.date_start,aicrm_activity.due_date,aicrm_activity.time_start,aicrm_activity.duration_hours,aicrm_activity.duration_minutes from aicrm_users inner join aicrm_salesmanactivityrel on aicrm_salesmanactivityrel.smid=aicrm_users.id  inner join aicrm_activity on aicrm_activity.activityid=aicrm_salesmanactivityrel.activityid inner join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id where aicrm_activity.activityid=' . $id;

        $return_data = GetRelatedList('Calendar', 'Users', $focus, $query, $button, $returnset);

        if ($return_data == null) $return_data = Array();
        $return_data['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_users method ...");
        return $return_data;
    }

    /**
     * Function to get activities for given criteria
     * @param   string $criteria - query string
     * returns  activity records in array format($list) or null value
     */
    function get_full_list($criteria)
    {
        global $log;
        $log->debug("Entering get_full_list(" . $criteria . ") method ...");
        $query = "select aicrm_crmentity.crmid,aicrm_crmentity.smownerid,aicrm_crmentity.setype, aicrm_activity.*, 
        aicrm_contactdetails.lastname, aicrm_contactdetails.firstname, aicrm_contactdetails.contactid 
        from aicrm_activity 
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid 
        left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid= aicrm_activity.activityid 
        left join aicrm_contactdetails on aicrm_contactdetails.contactid= aicrm_cntactivityrel.contactid 
        left join aicrm_seactivityrel on aicrm_seactivityrel.activityid = aicrm_activity.activityid 
        WHERE aicrm_crmentity.deleted=0 " . $criteria;
        $result =& $this->db->query($query);

        if ($this->db->getRowCount($result) > 0) {

            // We have some data.
            while ($row = $this->db->fetchByAssoc($result)) {
                foreach ($this->list_fields_name as $field) {
                    if (isset($row[$field])) {
                        $this->$field = $row[$field];
                    } else {
                        $this->$field = '';
                    }
                }
                $list[] = $this;
            }
        }
        if (isset($list)) {
            $log->debug("Exiting get_full_list method ...");
            return $list;
        } else {
            $log->debug("Exiting get_full_list method ...");
            return null;
        }

    }


    //calendarsync

    /**
     * Function to get meeting count
     * @param  string $user_name - User Name
     * return  integer  $row["count(*)"]  - count
     */
    function getCount_Meeting($user_name)
    {
        global $log;
        $log->debug("Entering getCount_Meeting(" . $user_name . ") method ...");
        $query = "select count(*) from aicrm_activity inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid inner join aicrm_salesmanactivityrel on aicrm_salesmanactivityrel.activityid=aicrm_activity.activityid inner join aicrm_users on aicrm_users.id=aicrm_salesmanactivityrel.smid where user_name=? and aicrm_crmentity.deleted=0 and aicrm_activity.activitytype='Meeting'";
        $result = $this->db->pquery($query, array($user_name), true, "Error retrieving contacts count");
        $rows_found = $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);
        $log->debug("Exiting getCount_Meeting method ...");
        return $row["count(*)"];
    }

    function get_calendars($user_name, $from_index, $offset)
    {
        global $log;
        $log->debug("Entering get_calendars(" . $user_name . "," . $from_index . "," . $offset . ") method ...");
        $query = "select aicrm_activity.location as location,aicrm_activity.duration_hours as duehours, aicrm_activity.duration_minutes as dueminutes,aicrm_activity.time_start as time_start, aicrm_activity.branchid as name,aicrm_crmentity.modifiedtime as date_modified, aicrm_activity.date_start start_date,aicrm_activity.activityid as id,aicrm_activity.status as status, aicrm_crmentity.description as description, aicrm_activity.priority as aicrm_priority, aicrm_activity.due_date as date_due ,aicrm_contactdetails.firstname cfn, aicrm_contactdetails.lastname cln from aicrm_activity inner join aicrm_salesmanactivityrel on aicrm_salesmanactivityrel.activityid=aicrm_activity.activityid inner join aicrm_users on aicrm_users.id=aicrm_salesmanactivityrel.smid left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid=aicrm_activity.activityid left join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_cntactivityrel.contactid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid where user_name='" . $user_name . "' and aicrm_crmentity.deleted=0 and aicrm_activity.activitytype='Meeting' limit " . $from_index . "," . $offset;
        $log->debug("Exiting get_calendars method ...");
        return $this->process_list_query1($query);
    }
    //calendarsync

    /**
     * Function to get task count
     * @param  string $user_name - User Name
     * return  integer  $row["count(*)"]  - count
     */
    function getCount($user_name)
    {
        global $log;
        $log->debug("Entering getCount(" . $user_name . ") method ...");
        $query = "select count(*) from aicrm_activity inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid inner join aicrm_salesmanactivityrel on aicrm_salesmanactivityrel.activityid=aicrm_activity.activityid inner join aicrm_users on aicrm_users.id=aicrm_salesmanactivityrel.smid where user_name=? and aicrm_crmentity.deleted=0 and aicrm_activity.activitytype='Task'";
        $result = $this->db->pquery($query, array($user_name), true, "Error retrieving contacts count");
        $rows_found = $this->db->getRowCount($result);
        $row = $this->db->fetchByAssoc($result, 0);

        $log->debug("Exiting getCount method ...");
        return $row["count(*)"];
    }

    /**
     * Function to get list of task for user with given limit
     * @param  string $user_name - User Name
     * @param  string $from_index - query string
     * @param  string $offset - query string
     * returns tasks in array format
     */
    function get_tasks($user_name, $from_index, $offset)
    {
        global $log;
        $log->debug("Entering get_tasks(" . $user_name . "," . $from_index . "," . $offset . ") method ...");
        $query = "select aicrm_activity.branchid as name,aicrm_crmentity.modifiedtime as date_modified, aicrm_activity.date_start start_date,aicrm_activity.activityid as id,aicrm_activity.status as status, aicrm_crmentity.description as description, aicrm_activity.priority as priority, aicrm_activity.due_date as date_due ,aicrm_contactdetails.firstname cfn, aicrm_contactdetails.lastname cln from aicrm_activity inner join aicrm_salesmanactivityrel on aicrm_salesmanactivityrel.activityid=aicrm_activity.activityid inner join aicrm_users on aicrm_users.id=aicrm_salesmanactivityrel.smid left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid=aicrm_activity.activityid left join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_cntactivityrel.contactid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid where user_name='" . $user_name . "' and aicrm_crmentity.deleted=0 and aicrm_activity.activitytype='Task' limit " . $from_index . "," . $offset;
        $log->debug("Exiting get_tasks method ...");
        return $this->process_list_query1($query);

    }

    /**
     * Function to process the activity list query
     * @param  string $query - query string
     * return  array    $response  - activity lists
     */
    function process_list_query1($query)
    {
        global $log;
        $log->debug("Entering process_list_query1(" . $query . ") method ...");
        $result =& $this->db->query($query, true, "Error retrieving $this->object_name list: ");
        $list = Array();
        $rows_found = $this->db->getRowCount($result);
        if ($rows_found != 0) {
            $task = Array();
            for ($index = 0, $row = $this->db->fetchByAssoc($result, $index); $row && $index < $rows_found; $index++, $row = $this->db->fetchByAssoc($result, $index)) {
                foreach ($this->range_fields as $columnName) {
                    if (isset($row[$columnName])) {

                        $task[$columnName] = $row[$columnName];
                    } else {
                        $task[$columnName] = "";
                    }
                }

                $task[contact_name] = return_name($row, 'cfn', 'cln');

                $list[] = $task;
            }
        }

        $response = Array();
        $response['list'] = $list;
        $response['row_count'] = $rows_found;
        $response['next_offset'] = $next_offset;
        $response['previous_offset'] = $previous_offset;


        $log->debug("Exiting process_list_query1 method ...");
        return $response;
    }

    /**
     * Function to get reminder for activity
     * @param  integer $activity_id - activity id
     * @param  string $reminder_time - reminder time
     * @param  integer $reminder_sent - 0 or 1
     * @param  integer $recurid - recuring eventid
     * @param  string $remindermode - string like 'edit'
     */
    function activity_reminder($activity_id, $reminder_time, $reminder_sent = 0, $recurid, $remindermode = '')
    {
        global $log;
        $log->debug("Entering aicrm_activity_reminder(" . $activity_id . "," . $reminder_time . "," . $reminder_sent . "," . $recurid . "," . $remindermode . ") method ...");
        //Check for aicrm_activityid already present in the reminder_table
        $query_exist = "SELECT activity_id FROM " . $this->reminder_table . " WHERE activity_id = ?";
        $result_exist = $this->db->pquery($query_exist, array($activity_id));

        if ($remindermode == 'edit') {
            if ($this->db->num_rows($result_exist) > 0) {
                $query = "UPDATE " . $this->reminder_table . " SET";
                $query .= " reminder_sent = ?, reminder_time = ? WHERE activity_id =?";
                $params = array($reminder_sent, $reminder_time, $activity_id);
            } else {
                $query = "INSERT INTO " . $this->reminder_table . " VALUES (?,?,?,?)";
                $params = array($activity_id, $reminder_time, 0, $recurid);
            }
        } elseif (($remindermode == 'delete') && ($this->db->num_rows($result_exist) > 0)) {
            $query = "DELETE FROM " . $this->reminder_table . " WHERE activity_id = ?";
            $params = array($activity_id);
        } else {
            $query = "INSERT INTO " . $this->reminder_table . " VALUES (?,?,?,?)";
            $params = array($activity_id, $reminder_time, 0, $recurid);
        }
        $this->db->pquery($query, $params, true, "Error in processing aicrm_table $this->reminder_table");
        $log->debug("Exiting aicrm_activity_reminder method ...");
    }

    //Used for vtigerCRM Outlook Add-In

    /**
     * Function to get tasks to display in outlookplugin
     * @param   string $username -  User name
     * return   string    $query        -  sql query
     */
    function get_tasksforol($username)
    {
        global $log, $adb;
        $log->debug("Entering get_tasksforol(" . $username . ") method ...");
        global $current_user;
        require_once("modules/Users/Users.php");
        $seed_user = new Users();
        $user_id = $seed_user->retrieve_user_id($username);
        $current_user = $seed_user;
        $current_user->retrieve_entity_info($user_id, 'Users');
        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        require('user_privileges/sharing_privileges_' . $current_user->id . '.php');

        if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
            $sql1 = "select tablename,columnname from aicrm_field where tabid=9 and tablename <> 'aicrm_recurringevents' and tablename <> 'aicrm_activity_reminder' and aicrm_field.presence in (0,2)";
            $params1 = array();
        } else {
            $profileList = getCurrentUserProfileList();
            $sql1 = "select tablename,columnname from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=9 and tablename <> 'aicrm_recurringevents' and tablename <> 'aicrm_activity_reminder' and aicrm_field.displaytype in (1,2,4,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
            $params1 = array();
            if (count($profileList) > 0) {
                $sql1 .= " and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params1, $profileList);
            }
        }
        $result1 = $adb->pquery($sql1, $params1);
        for ($i = 0; $i < $adb->num_rows($result1); $i++) {
            $permitted_lists[] = $adb->query_result($result1, $i, 'tablename');
            $permitted_lists[] = $adb->query_result($result1, $i, 'columnname');
        }
        $permitted_lists = array_chunk($permitted_lists, 2);
        $column_table_lists = array();
        for ($i = 0; $i < count($permitted_lists); $i++) {
            $column_table_lists[] = implode(".", $permitted_lists[$i]);
        }

        $query = "select aicrm_activity.activityid as taskid, " . implode(',', $column_table_lists) . " from aicrm_activity inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid 
        inner join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid 
        left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid=aicrm_activity.activityid 
        left join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_cntactivityrel.contactid 
        left join aicrm_seactivityrel on aicrm_seactivityrel.activityid = aicrm_activity.activityid 
        where aicrm_users.user_name='" . $username . "' and aicrm_crmentity.deleted=0 and aicrm_activity.activitytype='Task'";
        $log->debug("Exiting get_tasksforol method ...");
        return $query;
    }

    /**
     * Function to get calendar query for outlookplugin
     * @param   string $username -  User name                                                                            * return   string    $query        -  sql query
     */
    function get_calendarsforol($user_name)
    {
        global $log, $adb;
        $log->debug("Entering get_calendarsforol(" . $user_name . ") method ...");
        global $current_user;
        require_once("modules/Users/Users.php");
        $seed_user = new Users();
        $user_id = $seed_user->retrieve_user_id($user_name);
        $current_user = $seed_user;
        $current_user->retrieve_entity_info($user_id, 'Users');
        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        require('user_privileges/sharing_privileges_' . $current_user->id . '.php');

        if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
            $sql1 = "select tablename,columnname from aicrm_field where tabid=9 and tablename <> 'aicrm_recurringevents' and tablename <> 'aicrm_activity_reminder' and aicrm_field.presence in (0,2)";
            $params1 = array();
        } else {
            $profileList = getCurrentUserProfileList();
            $sql1 = "select tablename,columnname from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=9 and tablename <> 'aicrm_recurringevents' and tablename <> 'aicrm_activity_reminder' and aicrm_field.displaytype in (1,2,4,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
            $params1 = array();
            if (count($profileList) > 0) {
                $sql1 .= " and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params1, $profileList);
            }
        }
        $result1 = $adb->pquery($sql1, $params1);
        for ($i = 0; $i < $adb->num_rows($result1); $i++) {
            $permitted_lists[] = $adb->query_result($result1, $i, 'tablename');
            $permitted_lists[] = $adb->query_result($result1, $i, 'columnname');
            if ($adb->query_result($result1, $i, 'columnname') == "date_start") {
                $permitted_lists[] = 'aicrm_activity';
                $permitted_lists[] = 'time_start';
            }
            if ($adb->query_result($result1, $i, 'columnname') == "due_date") {
                $permitted_lists[] = 'aicrm_activity';
                $permitted_lists[] = 'time_end';
            }
        }
        $permitted_lists = array_chunk($permitted_lists, 2);
        $column_table_lists = array();
        for ($i = 0; $i < count($permitted_lists); $i++) {
            $column_table_lists[] = implode(".", $permitted_lists[$i]);
        }

        $query = "select aicrm_activity.activityid as clndrid, " . implode(',', $column_table_lists) . " from aicrm_activity 
        inner join aicrm_salesmanactivityrel on aicrm_salesmanactivityrel.activityid=aicrm_activity.activityid 
        inner join aicrm_users on aicrm_users.id=aicrm_salesmanactivityrel.smid 
        left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid=aicrm_activity.activityid 
        left join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_cntactivityrel.contactid 
        left join aicrm_seactivityrel on aicrm_seactivityrel.activityid = aicrm_activity.activityid 
        inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid 
        where aicrm_users.user_name='" . $user_name . "' and aicrm_crmentity.deleted=0 and aicrm_activity.activitytype='Meeting'";
        $log->debug("Exiting get_calendarsforol method ...");
        return $query;
    }

    function get_expense($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_expense(".$id.") method ...");
        $this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);
        $parenttab = getParentTab();

        if($singlepane_view == 'true')
            $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
        else
            $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

        $button = '';

        if($actions) {
            if(is_string($actions)) $actions = explode(',', strtoupper($actions));
           
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Competitor Analysis'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,aicrm_account.accountname,
            aicrm_crmentity.*,
            aicrm_expense.*,
            aicrm_expensecf.*,
            aicrm_account.*
            FROM aicrm_expense
            LEFT JOIN aicrm_expensecf ON aicrm_expensecf.expenseid = aicrm_expense.expenseid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_expense.expenseid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_expense.account_id
            LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0
            AND aicrm_expense.activityid = '".$id."' ";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_expense method ...");
        return $return_value;

    }

    function get_marketingtools($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_marketingtools(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}
        $query = "SELECT
        aicrm_marketingtools.*,
        aicrm_marketingtoolscf.*,
        aicrm_crmentity.crmid,
        aicrm_crmentity.smownerid,
        aicrm_activity.salesvisit_name,
        aicrm_crmentity.description,
    CASE
            
            WHEN ( aicrm_users.user_name NOT LIKE '' ) THEN
            aicrm_users.user_name ELSE aicrm_groups.groupname 
        END AS user_name 
    FROM
        aicrm_marketingtools
        LEFT JOIN aicrm_marketingtoolscf ON aicrm_marketingtoolscf.marketingtoolsid = aicrm_marketingtools.marketingtoolsid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_marketingtools.marketingtoolsid
        LEFT JOIN aicrm_activity ON aicrm_activity.activityid = aicrm_marketingtools.activityid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
    WHERE
        aicrm_crmentity.deleted = 0 
        AND aicrm_marketingtools.activityid = '".$id."' ";
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
       
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_marketingtools method ...");
		return $return_value;

    }

    function get_projects($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_projects(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}
   
        $query = "
        SELECT
        aicrm_projects.*,
        aicrm_projectscf.*,
        aicrm_crmentity.crmid,
        aicrm_crmentity.smownerid,
        aicrm_crmentity.description

    FROM
        aicrm_projects
        INNER JOIN aicrm_projectscf ON aicrm_projects.projectsid = aicrm_projectscf.projectsid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
        
        INNER JOIN (

            SELECT
            aicrm_inventoryowner.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventoryowner ON aicrm_activity.parentid = aicrm_inventoryowner.accountid 
        WHERE
            aicrm_crmentity.deleted = 0
            AND aicrm_inventoryowner.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'
            
            UNION
            
        SELECT
            aicrm_inventoryconsultant.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventoryconsultant ON aicrm_activity.parentid = aicrm_inventoryconsultant.accountid 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventoryconsultant.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'
            
            UNION
            
        SELECT
            aicrm_inventoryarchitecture.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventoryarchitecture ON aicrm_activity.parentid = aicrm_inventoryarchitecture.accountid 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventoryarchitecture.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'
            
            UNION
            
        SELECT
            aicrm_inventoryconstruction.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventoryconstruction ON aicrm_activity.parentid = aicrm_inventoryconstruction.accountid 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventoryconstruction.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'
            
            UNION
            
        SELECT
            aicrm_inventorydesigner.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventorydesigner ON aicrm_activity.parentid = aicrm_inventorydesigner.accountid 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventorydesigner.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'
            
            UNION
            
        SELECT
            aicrm_inventorycontractor.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventorycontractor ON aicrm_activity.parentid = aicrm_inventorycontractor.accountid 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventorycontractor.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'
            
            UNION
            
        SELECT
            aicrm_inventorysubcontractor.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventorysubcontractor ON aicrm_activity.parentid = aicrm_inventorysubcontractor.accountid 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventorysubcontractor.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'
            
            UNION
            
        SELECT
            aicrm_inventoryprojects.id AS projectsid
        FROM
            aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_inventoryprojects ON aicrm_activity.parentid = aicrm_inventoryprojects.accountid 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventoryprojects.id IS NOT NULL
            AND aicrm_activity.activityid = '" . $id . "'

        ) AS sub ON aicrm_projects.projectsid = sub.projectsid
    WHERE
        aicrm_crmentity.deleted = 0
       
         
        ";

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
    
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_projects method ...");
		return $return_value;

    }
    
    function get_questionnaireanswer($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_point(".$id.") method ...");
        $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
        require_once("modules/$related_module/$related_module.php");
        $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
        $singular_modname = vtlib_toSingular($related_module);

        $parenttab = getParentTab();

        if($singlepane_view == 'true')
            $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
        else
            $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

        $button = '';

        $query = "
            SELECT aicrm_questionnaireanswer.*,aicrm_questionnaireanswercf.*,aicrm_crmentity.crmid,aicrm_account.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
            FROM  aicrm_questionnaireanswer
            LEFT JOIN  aicrm_questionnaireanswercf ON aicrm_questionnaireanswercf.questionnaireanswerid = aicrm_questionnaireanswer.questionnaireanswerid 
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid =aicrm_questionnaireanswer.questionnaireanswerid 
            LEFT JOIN aicrm_account ON aicrm_account.accountid =  aicrm_questionnaireanswer.accountid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            WHERE aicrm_crmentity.deleted = 0
            AND aicrm_questionnaireanswer.activityid = ".$id;
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        $log->debug("Exiting get_point method ...");
        return $return_value;
    }

    // Function to unlink all the dependent entities of the given Entity by Id
    function unlinkDependencies($module, $id)
    {
        global $log;

        $sql = 'DELETE FROM aicrm_activity_reminder WHERE activity_id=?';
        $this->db->pquery($sql, array($id));

        $sql = 'DELETE FROM aicrm_recurringevents WHERE activityid=?';
        $this->db->pquery($sql, array($id));

        parent::unlinkDependencies($module, $id);
    }

    // Function to unlink an entity with given Id from another entity
    function unlinkRelationship($id, $return_module, $return_id)
    {
        global $log;
        if (empty($return_module) || empty($return_id)) return;

        if ($return_module == 'Accounts' || $return_module == 'Deal') {
            $relation_query = 'UPDATE aicrm_crmentity SET deleted = 1 WHERE crmid =?';
            $this->db->pquery($relation_query, array($id));

        }
        if ($return_module == 'Contacts') {
            $sql = 'UPDATE aicrm_activitycf SET contactid=0 WHERE activityid=?';
            $this->db->pquery($sql, array($id));
        } elseif ($return_module == 'HelpDesk') {
            $sql = 'DELETE FROM aicrm_seticketsrel WHERE ticketid = ? AND crmid = ?';
            $this->db->pquery($sql, array($return_id, $id));
        } else {
            $sql = 'DELETE FROM aicrm_seactivityrel WHERE activityid=?';
            $this->db->pquery($sql, array($id));

            $sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
            $params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
            $this->db->pquery($sql, $params);
        }
    }

    /**
     * this function sets the status flag of activity to true or false depending on the status passed to it
     * @param string $status - the status of the activity flag to set
     * @return:: true if successful; false otherwise
     */
    function setActivityReminder($status)
    {
        global $adb;
        if ($status == "on") {
            $flag = 0;
        } elseif ($status == "off") {
            $flag = 1;
        } else {
            return false;
        }
        $sql = "update aicrm_activity_reminder_popup set status=1 where recordid=?";
        $adb->pquery($sql, array($this->id));
        return true;
    }

    /*
     * Function to get the relation tables for related modules
     * @param - $secmodule secondary module name
     * returns the array with table names and fieldnames storing relations between module and this module
     */
    function setRelationTables($secmodule)
    {
        //echo $secmodule; exit;
        $rel_tables = array(
            //"Contacts" => array("aicrm_cntactivityrel" => array("activityid", "contactid"), "aicrm_activity" => "activityid"),
            "Leads" => array("aicrm_seactivityrel" => array("activityid", "crmid"), "aicrm_activity" => "activityid"),
            "Contacts" => array("aicrm_activitycf" => array("activityid", "contactid"), "aicrm_activity" => "activityid"),
            "Accounts" => array("aicrm_activitycf" => array("activityid", "accountid"), "aicrm_activity" => "activityid"),
            "HelpDesk" => array("aicrm_troubletickets" => array("ticketid", "ticketid"), "aicrm_activity" => "event_id"),
            "Projects" => array("aicrm_projects"=>array("projectsid","projectsid"),"aicrm_activity"=>"event_id"),
            "Job" => array("aicrm_jobs"=>array("jobid","jobid"),"aicrm_activity"=>"event_id"),
            //"Potentials" => array("aicrm_seactivityrel" => array("activityid", "crmid"), "aicrm_activity" => "activityid"),
        );
        return $rel_tables[$secmodule];
    }

    /*
     * Function to get the secondary query part of a report
     * @param - $module primary module name
     * @param - $secmodule secondary module name
     * returns the query string formed on fetching the related data for report for secondary module
     */
    function generateReportsSecQuery($module, $secmodule)
    {
        // echo $module ."&&".  $secmodule; exit;
        $query = $this->getRelationQuery($module, $secmodule, "aicrm_activity", "activityid");
        // echo $query; exit;
        $query .= " 
        LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_crmentity.crmid

        LEFT JOIN aicrm_crmentity AS aicrm_crmentityCalendar ON aicrm_crmentityCalendar.crmid = aicrm_activity.activityid AND aicrm_crmentityCalendar.deleted = 0

        LEFT JOIN aicrm_cntactivityrel ON aicrm_cntactivityrel.activityid = aicrm_activity.activityid

        LEFT JOIN aicrm_contactdetails AS aicrm_contactdetailsCalendar ON aicrm_contactdetailsCalendar.contactid = aicrm_cntactivityrel.contactid

        LEFT JOIN aicrm_seactivityrel ON aicrm_seactivityrel.activityid = aicrm_activity.activityid

        LEFT JOIN aicrm_activity_reminder ON aicrm_activity_reminder.activity_id = aicrm_activity.activityid

        LEFT JOIN aicrm_recurringevents ON aicrm_recurringevents.activityid = aicrm_activity.activityid

        LEFT JOIN aicrm_crmentity AS aicrm_crmentityRelCalendar ON aicrm_crmentityRelCalendar.crmid = aicrm_seactivityrel.crmid AND aicrm_crmentityRelCalendar.deleted = 0

        LEFT JOIN aicrm_account AS aicrm_accountRelCalendar ON aicrm_accountRelCalendar.accountid = aicrm_crmentityRelCalendar.crmid

        LEFT JOIN aicrm_leaddetails AS aicrm_leaddetailsRelCalendar ON aicrm_leaddetailsRelCalendar.leadid = aicrm_crmentityRelCalendar.crmid

        LEFT JOIN aicrm_quotes AS aicrm_quotesRelCalendar ON aicrm_quotesRelCalendar.quoteid = aicrm_crmentityRelCalendar.crmid

        LEFT JOIN aicrm_troubletickets AS aicrm_troubleticketsRelCalendar ON aicrm_troubleticketsRelCalendar.ticketid = aicrm_crmentityRelCalendar.crmid

        LEFT JOIN aicrm_campaign AS aicrm_campaignRelCalendar ON aicrm_campaignRelCalendar.campaignid = aicrm_crmentityRelCalendar.crmid

        LEFT JOIN aicrm_groups AS aicrm_groupsCalendar ON aicrm_groupsCalendar.groupid = aicrm_crmentityCalendar.smownerid

        LEFT JOIN aicrm_users AS aicrm_usersCalendar ON aicrm_usersCalendar.id = aicrm_crmentityCalendar.smownerid

        left join aicrm_users as aicrm_usersModifiedCalendar on aicrm_crmentityCalendar.smcreatorid=aicrm_usersModifiedCalendar.id

        left join aicrm_users as aicrm_usersCreatorCalendar on aicrm_crmentityCalendar.smcreatorid=aicrm_usersCreatorCalendar.id
        
        LEFT JOIN aicrm_projects as aicrm_projectsCalendar ON aicrm_projectsCalendar.projectsid = aicrm_activity.event_id

        LEFT JOIN aicrm_jobs as aicrm_jobsCalendar ON aicrm_jobsCalendar.jobid = aicrm_activity.event_id
        
        LEFT JOIN aicrm_troubletickets as aicrm_troubleticketsCalendar ON aicrm_troubleticketsCalendar.ticketid = aicrm_activity.event_id ";

        if ($module == "Accounts" && $secmodule == "Calendar" && $secmodule == "Contacts") {
            $query .= " left join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_contactdetailsCalendar.contactid ";
        }
        if($module == "Contacts" && $secmodule=='Calendar'){
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountCalendar ON aicrm_accountCalendar.accountid = aicrm_activitycf.accountid
            -- left join aicrm_projects as aicrm_projectsCalendar on aicrm_projectsCalendar.contactid=aicrm_contactdetailsCalendar.contactid ";
        }else if($module == "Leads" && $secmodule=='Calendar'){
             $query .= "
             LEFT JOIN aicrm_account AS aicrm_accountCalendar on aicrm_leaddetails.accountid = aicrm_accountCalendar.accountid
             LEFT JOIN aicrm_deal AS aicrm_dealCalendar ON aicrm_dealCalendar.parentid = aicrm_activity.parentid
             ";

        }else if($module == "Accounts" && $secmodule=='Calendar'){
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountCalendar ON aicrm_accountCalendar.parentid = aicrm_activitytmpCalendar.parentid
            LEFT JOIN aicrm_leaddetails AS aicrm_leaddetailsCalendar ON aicrm_leaddetailsCalendar.leadid = aicrm_activitytmpCalendar.parentid
            LEFT JOIN aicrm_deal AS aicrm_dealCalendar ON aicrm_dealCalendar.dealid = aicrm_activitytmpCalendar.dealid";
        }else {
            //$query .= " left join aicrm_projects as aicrm_projectsCalendar on aicrm_projectsCalendar.contactid=aicrm_contactdetailsCalendar.contactid ";
            $query .= " left join aicrm_account as aicrm_accountCalendar on aicrm_accountCalendar.accountid = aicrm_activitycf.accountid ";
           
        }
        
        return $query;
    }

    /** Function to export the account records in CSV Format
    * @param reference variable - where condition is passed when the query is executed
    * Returns Export Accounts Query.
    */
    function create_export_query($where)
    {
        global $log;
        global $current_user;
        $log->debug("Entering create_export_query(".$where.") method ...");

        include("include/utils/ExportUtils.php");

        //To get the Permitted fields query and the permitted fields list
        $sql = getPermittedFieldsQuery("Events", "detail_view");
        $fields_list = getFieldsListFromQuery($sql);

        $query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
        FROM aicrm_crmentity
        INNER JOIN aicrm_activity ON aicrm_activity.activityid = aicrm_crmentity.crmid
        INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
        LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
        LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
        LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_activity.contactid
        LEFT JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_activity.dealid
        LEFT JOIN aicrm_competitor ON aicrm_competitor.competitorid = aicrm_activity.competitorid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
        LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
        LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
        ";

        $query .= setFromQuery("Events");
        $where_auto = " aicrm_crmentity.deleted = 0 And aicrm_activity.activitytype != 'Emails'";

        if($where != "")
            $query .= " WHERE ($where) AND ".$where_auto;
        else
            $query .= " WHERE ".$where_auto;

        require('user_privileges/user_privileges_'.$current_user->id.'.php');
        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        //we should add security check when the user has Private Access
        if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
        {
            //Added security check to get the permitted records only
            $query = $query." ".getListViewSecurityParameter("Events");
        }

        $log->debug("Exiting create_export_query method ...");
        return $query;
    }

    /**     Function to get the list of comments for the given service request id
     *      @param  int  $servicerequestid - Service Request id
     *      @return list $list - return the list of comments and comment informations as a html output where as these comments and comments informations will be formed in div tag.
     **/
    function getCommentInformation($ticketid)
    {
        global $log;
        $log->debug("Entering getCommentInformation(".$ticketid.") method ...");
        global $adb;
        global $mod_strings, $default_charset;
        $sql = "select * from aicrm_commentplan where activityid=? order by createdtime desc ";
        $result = $adb->pquery($sql, array($ticketid));
        $noofrows = $adb->num_rows($result);

        //In ajax save we should not add this div
        if($_REQUEST['action'] != 'CalendarAjax')
        {
            $list .= '<div id="comments_div" style="overflow: auto;height:200px;width:100%;">';
            $enddiv = '</div>';
        }
        for($i=0;$i<$noofrows;$i++)
        {
            if($adb->query_result($result,$i,'comments') != '')
            {
                //this div is to display the comment
                $comment = $adb->query_result($result,$i,'comments');
                // Asha: Fix for ticket #4478 . Need to escape html tags during ajax save.
                if($_REQUEST['action'] == 'CalendarAjax') {
                    $comment = htmlentities($comment, ENT_QUOTES, $default_charset);
                }
                $list .= '<div valign="top" style="width:99%;padding-top:10px;" class="dataField">';
                $list .= make_clickable(nl2br($comment));

                $list .= '</div>';

                //this div is to display the author and time
                $list .= '<div valign="top" style="width:99%;border-bottom:1px dotted #CCCCCC;padding-bottom:5px;" class="dataLabel"><font color=darkred>';
                $list .= $mod_strings['LBL_AUTHOR'].' : ';

                if($adb->query_result($result,$i,'ownertype') == 'user')
                    $list .= getUserName($adb->query_result($result,$i,'ownerid'));
                elseif($adb->query_result($result,$i,'ownertype') == 'customer') {
                    $contactid = $adb->query_result($result,$i,'ownerid');
                    $list .= getContactName($contactid);
                }
                $list .= ' on '.date('d-m-Y H:i:s',strtotime($adb->query_result($result,$i,'createdtime'))).' &nbsp;';

                $list .= '</font></div>';
            }
        }

        $list .= $enddiv;

        $log->debug("Exiting getCommentInformation method ...");
        return $list;
    }

}

?>