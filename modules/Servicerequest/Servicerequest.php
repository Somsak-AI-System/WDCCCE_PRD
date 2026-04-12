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

class Servicerequest extends CRMEntity
{
// Account is used to store aicrm_account information.
    var $log;
    var $db;
    var $table_name = "aicrm_servicerequest";
    var $table_index = 'servicerequestid';
    var $table_comment = "aicrm_dealcomments";

    var $tab_name = Array('aicrm_crmentity', 'aicrm_servicerequest', 'aicrm_servicerequestcf');
    var $tab_name_index = Array('aicrm_crmentity' => 'crmid', 'aicrm_servicerequest' => 'servicerequestid', 'aicrm_servicerequestcf' => 'servicerequestid','aicrm_servicerequestcomments'=>'servicerequestid');
    /**
     * Mandatory table for supporting custom fields.
     */
    var $customFieldTable = Array('aicrm_servicerequestcf', 'servicerequestid');
    var $column_fields = Array();

    var $sortby_fields = Array('servicerequestid', 'servicerequest_name', 'smownerid','accountname');

    var $list_fields = Array(
        'หมายเลขคำขอบริการ' => Array('servicerequest' => 'servicerequest_no'),
        'ชื่อคำขอบริการ' => Array('servicerequest' => 'servicerequest_name'),
    );

    var $list_fields_name = Array(
        'หมายเลขคำขอบริการ' => 'servicerequest_no',
        'ชื่อคำขอบริการ' => 'servicerequest_name',
    );

    var $list_link_field = 'servicerequest_no';
    //Added these variables which are used as default order by and sortorder in ListView
    var $default_order_by = 'crmid';
    var $default_sort_order = 'desc';

    //var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

    var $search_fields = Array(
        'หมายเลขคำขอบริการ' => Array('aicrm_servicerequest' => 'servicerequest_no'),
        'ชื่อคำขอบริการ' => Array('aicrm_servicerequest' => 'servicerequest_name'),
    );

    var $search_fields_name = Array(
        'หมายเลขคำขอบริการ' => 'servicerequest_no',
        'ชื่อคำขอบริการ' => 'servicerequest_name',
    );
    // Used when enabling/disabling the mandatory fields for the module.
    // Refers to aicrm_field.fieldname values.
    var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'servicerequestid');

    function Servicerequest()
    {
        $this->log = LoggerManager::getLogger('Servicerequest');
        $this->db = PearDatabase::getInstance();
        $this->column_fields = getColumnFields('Servicerequest');
    }

    function save_module()
    {
        global $adb;
        //in ajax save we should not call this function, because this will delete all the existing product values
        if ($_REQUEST['action'] != 'ServicerequestAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave') {
            //Based on the total Number of rows we will save the product relationship with this entity
            //saveInventoryProductDetails($this, 'Servicerequest');
        }

        $this->insertIntoCommentTable("aicrm_servicerequestcomments",'servicerequestid');
    }

    function insertIntoCommentTable($table_name, $module)
    {
        global $log;
        $log->info("in insertIntoCommentTable  ".$table_name."    module is  ".$module);
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
                
        if($this->column_fields['comments'] != ''){         
            $comment = $this->column_fields['comments'];
        }
        else
        {
            $comment = $_REQUEST['comments'];
        }   
        
        if($comment!=""){
            $sql = "insert into ".$table_name." values(?,?,?,?,?,?)";
            $params = array('', $this->id, from_html($comment), $current_user->id, $ownertype, $current_time);
            $adb->pquery($sql, $params);
        }
    }

    function getCommentInformation($crmid)
    {
        global $log;
        $log->debug("Entering getCommentInformation(".$crmid.") method ...");
        global $adb;
        global $mod_strings, $default_charset;
        $sql = "select * from aicrm_servicerequestcomments where servicerequestid=? order by createdtime desc";
        $result = $adb->pquery($sql, array($crmid));
        $noofrows = $adb->num_rows($result);
    
        //In ajax save we should not add this div
        if($_REQUEST['action'] != 'ServiceRequestAjax')
        {
            $list .= '<div id="comments_div" style="overflow: auto;height:200px;width:100%;display:block;">';
            $enddiv = '</div>';
        }
        for($i=0;$i<$noofrows;$i++)
        {
            if($adb->query_result($result,$i,'comments') != '')
            {
                //this div is to display the comment
                $comment = $adb->query_result($result,$i,'comments');
                // Asha: Fix for ticket #4478 . Need to escape html tags during ajax save.
                if($_REQUEST['action'] == 'ServiceRequestAjax') {
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
            $sorder = (($_SESSION['COMPETITOR_SORT_ORDER'] != '') ? ($_SESSION['COMPETITOR_SORT_ORDER']) : ($this->default_sort_order));
        $log->debug("Exiting getSortOrder() method ...");
        return $sorder;
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
            $order_by = (($_SESSION['COMPETITOR_ORDER_BY'] != '') ? ($_SESSION['COMPETITOR_ORDER_BY']) : ($use_default_order_by));
        $log->debug("Exiting getOrderBy method ...");
        return $order_by;
    }

    /*
     * Function to get the secondary query part of a report
     * @param - $module primary module name
     * @param - $secmodule secondary module name
     * returns the query string formed on fetching the related data for report for secondary module
     */
    function generateReportsSecQuery($module, $secmodule)
    {
        $query = $this->getRelationQuery($module, $secmodule, "aicrm_servicerequest", "servicerequestid");
        $query .= " LEFT JOIN aicrm_crmentity AS aicrm_crmentityServicerequest ON aicrm_crmentityServicerequest.crmid = aicrm_servicerequest.servicerequestid
                    AND aicrm_crmentityServicerequest.deleted = 0
                    LEFT JOIN aicrm_servicerequestcf ON aicrm_servicerequestcf.servicerequestid = aicrm_crmentityServicerequest.crmid
                    LEFT JOIN aicrm_account as aicrm_accountServicerequest ON aicrm_accountServicerequest.accountid = aicrm_servicerequest.account_id
                    LEFT JOIN aicrm_groups AS aicrm_groupsServicerequest ON aicrm_groupsServicerequest.groupid = aicrm_crmentityServicerequest.smownerid
                    LEFT JOIN aicrm_users AS aicrm_usersServicerequest ON aicrm_usersServicerequest.id = aicrm_crmentityServicerequest.smownerid
                    left join aicrm_users as aicrm_usersModifiedServicerequest on aicrm_crmentity.smcreatorid=aicrm_usersModifiedServicerequest.id
                    left join aicrm_users as aicrm_usersCreatorServicerequest on aicrm_crmentity.smcreatorid=aicrm_usersModifiedServicerequest.id";
        //echo $query;exit;
        return $query;
    }

    function get_job($id, $cur_tab_id, $rel_tab_id, $actions = false)
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
                  CASE
                  WHEN ( aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
                  ELSE aicrm_groups.groupname END AS user_name FROM aicrm_jobs
                  LEFT JOIN aicrm_jobscf ON aicrm_jobs.jobid = aicrm_jobscf.jobid
                  INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
                  LEFT JOIN aicrm_servicerequest ON aicrm_servicerequest.servicerequestid = aicrm_jobs.servicerequestid
                  LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                  LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                  WHERE aicrm_crmentity.deleted = 0 AND aicrm_jobs.servicerequestid = " . $id;
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_contacts method ...");
        return $return_value;
    }

    function create_export_query($where)
    {
        global $log;
        global $current_user;
        $log->debug("Entering create_export_query(" . $where . ") method ...");

        include("include/utils/ExportUtils.php");

        //To get the Permitted fields query and the permitted fields list
        $sql = getPermittedFieldsQuery("Servicerequest", "detail_view");
        $fields_list = getFieldsListFromQuery($sql);
        $query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_servicerequest ON aicrm_servicerequest.servicerequestid = aicrm_crmentity.crmid
				INNER JOIN aicrm_servicerequestcf ON aicrm_servicerequestcf.servicerequestid = aicrm_servicerequest.servicerequestid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_servicerequest.accountid
                LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_servicerequest.contactid
                LEFT JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_servicerequest.salesorderid
                LEFT JOIN aicrm_salesinvoice ON aicrm_salesinvoice.salesinvoiceid = aicrm_servicerequest.salesinvoiceid
                LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
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
            $query = $query . " " . getListViewSecurityParameter("Servicerequest");
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
        $rel_tables = array(
            "Documents" => array("aicrm_senotesrel" => array("crmid", "notesid"), "aicrm_servicerequest" => "servicerequestid"),
            "Accounts" => array("aicrm_account"=>array("accountid","accountid"),"aicrm_servicerequest"=>"account_id"),
        );
        return $rel_tables[$secmodule];
    }

    // Function to unlink an entity with given Id from another entity
    function unlinkRelationship($id, $return_module, $return_id)
    {
        global $log;
        if (empty($return_module) || empty($return_id)) return;
        
            $sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
            $params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
            $this->db->pquery($sql, $params);
    }

}

?>
