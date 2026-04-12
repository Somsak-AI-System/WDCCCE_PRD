<?php
/*********************************************************************************
 ** The contents of this file are subject to the aicrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  aicrm CRM Open Source
 * The Initial Developer of the Original Code is aicrm.
 * Portions created by aicrm are Copyright (C) aicrm.
 * All Rights Reserved.
 *
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
require_once('data/CRMEntity.php');
require_once('include/utils/UserInfoUtil.php');
global $calpath;
global $app_strings, $mod_strings;
global $app_list_strings;
global $modules;
global $blocks;
global $adv_filter_options;
global $log;

global $report_modules;
global $related_modules;
global $old_related_modules;

$adv_filter_options = array("e" => "equals",
    "n" => "not equal to",
    "s" => "starts with",
    "ew" => "ends with",
    "c" => "contains",
    "k" => "does not contain",
    "l" => "less than",
    "g" => "greater than",
    "m" => "less or equal",
    "h" => "greater or equal"
);

$old_related_modules = Array(
    'Accounts' => Array('Contacts', 'Quotes'),
    'Contacts' => Array('Accounts', 'Quotes', 'Projects'),
    'Calendar' => Array('Contacts','Projects'),
    'PurchaseOrder' => Array('Contacts'),
    'Invoice' => Array('Accounts'),
    'Competitor' => Array('Accounts'),
);

$related_modules = Array();

class Reports extends CRMEntity
{
    /**
     * This class has the informations for Reports and inherits class CRMEntity and
     * has the variables required to generate,save,restore aicrm_reports
     * and also the required functions for the same
     * Contributor(s): ______________________________________..
     */
    var $srptfldridjs;
    var $column_fields = Array();
    var $sort_fields = Array();
    var $sort_values = Array();

    var $id;
    var $mode;
    var $mcount;

    var $startdate;
    var $enddate;

    var $ascdescorder;

    var $stdselectedfilter;
    var $stdselectedcolumn;

    var $primodule;
    var $secmodule;
    var $columnssummary;
    var $is_editable;
    var $reporttype;
    var $reportname;
    var $reportdescription;
    var $folderid;
    var $module_blocks;

    var $pri_module_columnslist;
    var $sec_module_columnslist;

    var $advft_column;
    var $advft_option;
    var $advft_value;
    var $adv_rel_fields = Array();

    var $module_list = Array();

    /** Function to set primodule,secmodule,reporttype,reportname,reportdescription,folderid for given aicrm_reportid
     *  This function accepts the aicrm_reportid as argument
     *  It sets primodule,secmodule,reporttype,reportname,reportdescription,folderid for the given aicrm_reportid
     */

    function Reports($reportid = "")
    {
        global $adb, $current_user, $theme, $mod_strings;
        $this->initListOfModules();
        if ($reportid != "") {
            // Lookup information in cache first
            $cachedInfo = VTCacheUtils::lookupReport_Info($current_user->id, $reportid);
            $subordinate_users = VTCacheUtils::lookupReport_SubordinateUsers($reportid);

            if ($cachedInfo === false) {
                $ssql = "select aicrm_reportmodules.*,aicrm_report.* from aicrm_report inner join aicrm_reportmodules on aicrm_report.reportid = aicrm_reportmodules.reportmodulesid";
                $ssql .= " where aicrm_report.reportid = ?";
                $params = array($reportid);

                require_once('include/utils/GetUserGroups.php');
                require('user_privileges/user_privileges_' . $current_user->id . '.php');
                $userGroups = new GetUserGroups();
                $userGroups->getAllUserGroups($current_user->id);
                $user_groups = $userGroups->user_groups;
                if (!empty($user_groups) && $is_admin == false) {
                    $user_group_query = " (shareid IN (" . generateQuestionMarks($user_groups) . ") AND setype='groups') OR";
                    array_push($params, $user_groups);
                }

                $non_admin_query = " aicrm_report.reportid IN (SELECT reportid from aicrm_reportsharing WHERE $user_group_query (shareid=? AND setype='users'))";
                if ($is_admin == false) {
                    $ssql .= " and ( (" . $non_admin_query . ") or aicrm_report.sharingtype='Public' or aicrm_report.owner = ? or aicrm_report.owner in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '" . $current_user_parent_role_seq . "::%'))";
                    array_push($params, $current_user->id);
                    array_push($params, $current_user->id);
                }

                $query = $adb->pquery("select userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '" . $current_user_parent_role_seq . "::%'", array());
                $subordinate_users = Array();
                for ($i = 0; $i < $adb->num_rows($query); $i++) {
                    $subordinate_users[] = $adb->query_result($query, $i, 'userid');
                }

                // Update subordinate user information for re-use
                VTCacheUtils::updateReport_SubordinateUsers($reportid, $subordinate_users);

                $result = $adb->pquery($ssql, $params);
                if ($result && $adb->num_rows($result)) {
                    $reportmodulesrow = $adb->fetch_array($result);

                    // Update information in cache now
                    VTCacheUtils::updateReport_Info(
                        $current_user->id, $reportid, $reportmodulesrow["primarymodule"],
                        $reportmodulesrow["secondarymodules"], $reportmodulesrow["reporttype"],
                        $reportmodulesrow["reportname"], $reportmodulesrow["description"],
                        $reportmodulesrow["folderid"], $reportmodulesrow["owner"]
                    );
                }

                // Re-look at cache to maintain code-consistency below
                $cachedInfo = VTCacheUtils::lookupReport_Info($current_user->id, $reportid);
            }

            if ($cachedInfo) {
                $this->primodule = $cachedInfo["primarymodule"];
                $this->secmodule = $cachedInfo["secondarymodules"];
                $this->reporttype = $cachedInfo["reporttype"];
                $this->reportname = decode_html($cachedInfo["reportname"]);
                $this->reportdescription = decode_html($cachedInfo["description"]);
                $this->folderid = $cachedInfo["folderid"];
                if ($is_admin == true || in_array($cachedInfo["owner"], $subordinate_users) || $cachedInfo["owner"] == $current_user->id)
                    $this->is_editable = 'true';
                else
                    $this->is_editable = 'false';
            } else {
                if ($_REQUEST['mode'] != 'ajax') {
                    include('themes/' . $theme . '/header.php');
                }
                echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
                echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 80%; position: relative; z-index: 10000000;'>
		
				<table border='0' cellpadding='5' cellspacing='0' width='98%'>
				<tbody><tr>
				<td rowspan='2' width='11%'><img src='" . aicrm_imageurl('denied.gif', $theme) . "' ></td>
				<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'>You are not allowed to View this Report </span></td>
				</tr>
				<tr>
				<td class='small' align='right' nowrap='nowrap'>			   	
				<a href='javascript:window.history.back();'>$app_strings[LBL_GO_BACK]</a><br>								   		     </td>
				</tr>
				</tbody></table> 
				</div>";
                echo "</td></tr></table>";
                break;

            }
        }
    }

    // Update the module list for listing columns for report creation.
    function updateModuleList($module)
    {
        global $adb;
        if (!isset($module)) return;
        require_once('include/utils/utils.php');
        $tabid = getTabid($module);
        if ($module == 'Calendar') {
            $tabid = array(9, 16);
        }
        $sql = "SELECT blockid, blocklabel FROM aicrm_blocks WHERE tabid IN (" . generateQuestionMarks($tabid) . ")";
        $res = $adb->pquery($sql, array($tabid));
        $noOfRows = $adb->num_rows($res);
        if ($noOfRows <= 0) return;
        for ($index = 0; $index < $noOfRows; ++$index) {
            $blockid = $adb->query_result($res, $index, 'blockid');
            if (in_array($blockid, $this->module_list[$module])) continue;
            $blockid_list[] = $blockid;
            $blocklabel = $adb->query_result($res, $index, 'blocklabel');
            $this->module_list[$module][$blocklabel] = $blockid;
        }
    }

    // Initializes the module list for listing columns for report creation.
    function initListOfModules()
    {
        global $adb, $current_user, $old_related_modules;

        $restricted_modules = array('Emails', 'Events', 'Webmails');
        $restricted_blocks = array('LBL_IMAGE_INFORMATION', 'LBL_COMMENTS', 'LBL_COMMENT_INFORMATION');

        $this->module_id = array();
        $this->module_list = array();

        // Prefetch module info to check active or not and also get list of tabs
        $modulerows = vtlib_prefetchModuleActiveInfo(false);

        $cachedInfo = VTCacheUtils::lookupReport_ListofModuleInfos();

        if ($cachedInfo !== false) {
            $this->module_list = $cachedInfo['module_list'];
            $this->related_modules = $cachedInfo['related_modules'];

        } else {

            if ($modulerows) {
                foreach ($modulerows as $resultrow) {
                    if ($resultrow['presence'] == '1') continue;      // skip disabled modules
                    if ($resultrow['isentitytype'] != '1') continue;  // skip extension modules
                    if (in_array($resultrow['name'], $restricted_modules)) { // skip restricted modules
                        continue;
                    }
                    if ($resultrow['name'] != 'Calendar') {
                        $this->module_id[$resultrow['tabid']] = $resultrow['name'];
                    } else {
                        $this->module_id[9] = $resultrow['name'];
                        $this->module_id[16] = $resultrow['name'];

                    }
                    $this->module_list[$resultrow['name']] = array();
                }

                $moduleids = array_keys($this->module_id);
                $reportblocks =
                    $adb->pquery("SELECT blockid, blocklabel, tabid FROM aicrm_blocks WHERE tabid IN (" . generateQuestionMarks($moduleids) . ")",
                        array($moduleids));
                $prev_block_label = '';
                if ($adb->num_rows($reportblocks)) {
                    while ($resultrow = $adb->fetch_array($reportblocks)) {
                        $blockid = $resultrow['blockid'];
                        $blocklabel = $resultrow['blocklabel'];
                        $module = $this->module_id[$resultrow['tabid']];

                        if (in_array($blocklabel, $restricted_blocks) ||
                            in_array($blockid, $this->module_list[$module]) ||
                            isset($this->module_list[$module][getTranslatedString($blocklabel, $module)])
                        ) {
                            continue;
                        }

                        if (!empty($blocklabel)) {
                            if ($module == 'Calendar' && $blocklabel == 'LBL_CUSTOM_INFORMATION')
                                $this->module_list[$module][$blockid] = getTranslatedString($blocklabel, $module);
                            else
                                $this->module_list[$module][$blockid] = getTranslatedString($blocklabel, $module);
                            $prev_block_label = $blocklabel;
                        } else {
                            $this->module_list[$module][$blockid] = getTranslatedString($prev_block_label, $module);
                        }
                    }
                }

                $relatedmodules = $adb->pquery(
                    "SELECT aicrm_tab.name, aicrm_relatedlists.tabid FROM aicrm_tab 
					INNER JOIN aicrm_relatedlists on aicrm_tab.tabid=aicrm_relatedlists.related_tabid 
					WHERE aicrm_tab.isentitytype=1 
					AND aicrm_tab.name NOT IN(" . generateQuestionMarks($restricted_modules) . ") 
					AND aicrm_tab.presence=0 AND aicrm_relatedlists.label!='Activity History'",
                    array($restricted_modules)
                );
               
                if ($adb->num_rows($relatedmodules)) {
                    while ($resultrow = $adb->fetch_array($relatedmodules)) {
                        $module = $this->module_id[$resultrow['tabid']];

                        if (!isset($this->related_modules[$module])) {
                            $this->related_modules[$module] = array();
                        }

                        if ($module != $resultrow['name']) {
                            $this->related_modules[$module][] = $resultrow['name'];
                        }

                        // To achieve Backward Compatability with Report relations
                        if (isset($old_related_modules[$module])) {

                            $rel_mod = array();
                            foreach ($old_related_modules[$module] as $key => $name) {
                                if (vtlib_isModuleActive($name) && isPermitted($name, 'index', '')) {
                                    $rel_mod[] = $name;
                                }
                            }
                            if (!empty($rel_mod)) {
                                $this->related_modules[$module] = array_merge($this->related_modules[$module], $rel_mod);
                                $this->related_modules[$module] = array_unique($this->related_modules[$module]);
                            }
                        }
                    }
                }
                // Put the information in cache for re-use
                VTCacheUtils::updateReport_ListofModuleInfos($this->module_list, $this->related_modules);
            }
        }
    }
    // END


    /** Function to get the Listview of Reports
     *  This function accepts no argument
     *  This generate the Reports view page and returns a string
     *  contains HTML
     */

    function sgetRptFldr($mode = '')
    {

        global $adb, $log, $mod_strings;
        $returndata = Array();
        $sql = "select * from aicrm_reportfolder order by folderid";
        $result = $adb->pquery($sql, array());
        $reportfldrow = $adb->fetch_array($result);
        if ($mode != '') {
            // Fetch detials of all reports of folder at once
            $reportsInAllFolders = $this->sgetRptsforFldr(false);

            do {
                if ($reportfldrow["state"] == $mode) {
                    $details = Array();
                    $details['state'] = $reportfldrow["state"];
                    $details['id'] = $reportfldrow["folderid"];
                    $details['name'] = ($mod_strings[$reportfldrow["foldername"]] == '') ? $reportfldrow["foldername"] : $mod_strings[$reportfldrow["foldername"]];
                    $details['description'] = $reportfldrow["description"];
                    $details['fname'] = popup_decode_html($details['name']);
                    $details['fdescription'] = popup_decode_html($reportfldrow["description"]);
                    $details['details'] = $reportsInAllFolders[$reportfldrow["folderid"]];
                    $returndata[] = $details;
                }
            } while ($reportfldrow = $adb->fetch_array($result));
        } else {
            do {
                $details = Array();
                $details['state'] = $reportfldrow["state"];
                $details['id'] = $reportfldrow["folderid"];
                $details['name'] = ($mod_strings[$reportfldrow["foldername"]] == '') ? $reportfldrow["foldername"] : $mod_strings[$reportfldrow["foldername"]];
                $details['description'] = $reportfldrow["description"];
                $details['fname'] = popup_decode_html($details['name']);
                $details['fdescription'] = popup_decode_html($reportfldrow["description"]);
                $returndata[] = $details;
            } while ($reportfldrow = $adb->fetch_array($result));
        }

        $log->info("Reports :: ListView->Successfully returned aicrm_report folder HTML");
        return $returndata;
    }

    /** Function to get the Reports inside each modules
     *  This function accepts the folderid
     *  This Generates the Reports under each Reports module
     *  This Returns a HTML sring
     */

    function sgetRptsforFldr($rpt_fldr_id)
    {
        $srptdetails = "";
        global $adb;
        global $log;
        global $mod_strings, $current_user;
        $returndata = Array();

        require_once('include/utils/UserInfoUtil.php');

        $sql = "select aicrm_report.*, aicrm_reportmodules.*, aicrm_reportfolder.folderid from aicrm_report inner join aicrm_reportfolder on aicrm_reportfolder.folderid = aicrm_report.folderid";
        $sql .= " inner join aicrm_reportmodules on aicrm_reportmodules.reportmodulesid = aicrm_report.reportid";

        $params = array();

        // If information is required only for specific report folder?
        if ($rpt_fldr_id !== false) {
            $sql .= " where aicrm_reportfolder.folderid=?";
            $params[] = $rpt_fldr_id;
        }

        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        require_once('include/utils/GetUserGroups.php');
        $userGroups = new GetUserGroups();
        $userGroups->getAllUserGroups($current_user->id);
        $user_groups = $userGroups->user_groups;
        if (!empty($user_groups) && $is_admin == false) {
            $user_group_query = " (shareid IN (" . generateQuestionMarks($user_groups) . ") AND setype='groups') OR";
            array_push($params, $user_groups);
        }

        $non_admin_query = " aicrm_report.reportid IN (SELECT reportid from aicrm_reportsharing WHERE $user_group_query (shareid=? AND setype='users'))";
        if ($is_admin == false) {
            $sql .= " and ( (" . $non_admin_query . ") or aicrm_report.sharingtype='Public' or aicrm_report.owner = ? or aicrm_report.owner in(select aicrm_user2role.userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '" . $current_user_parent_role_seq . "::%'))";
            array_push($params, $current_user->id);
            array_push($params, $current_user->id);
        }
        $query = $adb->pquery("select userid from aicrm_user2role inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid where aicrm_role.parentrole like '" . $current_user_parent_role_seq . "::%'", array());
        $subordinate_users = Array();
        for ($i = 0; $i < $adb->num_rows($query); $i++) {
            $subordinate_users[] = $adb->query_result($query, $i, 'userid');
        }
        $result = $adb->pquery($sql, $params);

        $report = $adb->fetch_array($result);
        if (count($report) > 0) {
            do {
                $report_details = Array();
                $report_details ['customizable'] = $report["customizable"];
                $report_details ['reportid'] = $report["reportid"];
                $report_details ['primarymodule'] = $report["primarymodule"];
                $report_details ['secondarymodules'] = $report["secondarymodules"];
                $report_details ['state'] = $report["state"];
                $report_details ['description'] = $report["description"];
                $report_details ['reportname'] = $report["reportname"];
                $report_details ['sharingtype'] = $report["sharingtype"];
                if ($is_admin == true || in_array($report["owner"], $subordinate_users) || $report["owner"] == $current_user->id)
                    $report_details ['editable'] = 'true';
                else
                    $report_details['editable'] = 'false';

                if (isPermitted($report["primarymodule"], 'index') == "yes")
                    $returndata [$report["folderid"]][] = $report_details;
            } while ($report = $adb->fetch_array($result));
        }

        if ($rpt_fldr_id !== false) {
            $returndata = $returndata[$rpt_fldr_id];
        }

        $log->info("Reports :: ListView->Successfully returned aicrm_report details HTML");
        return $returndata;
    }

    /** Function to get the array of ids
     *  This function forms the array for the ExpandCollapse
     *  Javascript
     *  It returns the array of ids
     *  Array('1RptFldr','2RptFldr',........,'9RptFldr','10RptFldr')
     */

    function sgetJsRptFldr()
    {
        $srptfldr_js = "var ReportListArray=new Array(" . $this->srptfldridjs . ")
			setExpandCollapse()";
        return $srptfldr_js;
    }

    /** Function to set the Primary module aicrm_fields for the given Report
     *  This function sets the primary module columns for the given Report
     *  It accepts the Primary module as the argument and set the aicrm_fields of the module
     *  to the varialbe pri_module_columnslist and returns true if sucess
     */

    function getPriModuleColumnsList($module)
    {
        //$this->updateModuleList($module);
        foreach ($this->module_list[$module] as $key => $value) {    //echo $value."<br>";
            if (!empty($ret_module_list[$module][$value])) {
                if ($module == "HelpDesk" and $value == "Case Information") {
                    $ret_module_list[$module][$value] = array_merge($ret_module_list[$module][$value], $this->getColumnsListbyBlock($module, 25));
                } else {
                    $ret_module_list[$module][$value] = array_merge($ret_module_list[$module][$value], $this->getColumnsListbyBlock($module, $key));
                }
            } else {
                //echo "555".$ret_module_list[$module][$value]."<br>";
                //echo $module."<br>";
                $ret_module_list[$module][$value] = $this->getColumnsListbyBlock($module, $key);
            }
        }
        $this->pri_module_columnslist = $ret_module_list;
        return true;
    }

    /** Function to set the Secondary module fileds for the given Report
     *  This function sets the secondary module columns for the given module
     *  It accepts the module as the argument and set the aicrm_fields of the module
     *  to the varialbe sec_module_columnslist and returns true if sucess
     */

    function getSecModuleColumnsList($module)
    {
        if ($module != "") {
            $secmodule = explode(":", $module);
            for ($i = 0; $i < count($secmodule); $i++) {
                //$this->updateModuleList($secmodule[$i]);
                if ($this->module_list[$secmodule[$i]]) {
                    foreach ($this->module_list[$secmodule[$i]] as $key => $value) {
                        if (!empty($ret_module_list[$secmodule[$i]][$value])) {
                            if ($module == "HelpDesk" and $value == "Case Information") {
                                $ret_module_list[$secmodule[$i]][$value] = array_merge($ret_module_list[$secmodule[$i]][$value], $this->getColumnsListbyBlock($secmodule[$i], 25));
                            } else {
                                $ret_module_list[$secmodule[$i]][$value] = array_merge($ret_module_list[$secmodule[$i]][$value], $this->getColumnsListbyBlock($secmodule[$i], $key));
                            }
                        } else {
                            $ret_module_list[$secmodule[$i]][$value] = $this->getColumnsListbyBlock($secmodule[$i], $key);
                        }
                    }
                    $this->sec_module_columnslist[$secmodule[$i]] = $ret_module_list[$secmodule[$i]];
                }
            }
        }
        return true;
    }

    /** Function to get aicrm_fields for the given module and block
     *  This function gets the aicrm_fields for the given module
     *  It accepts the module and the block as arguments and
     *  returns the array column lists
     *  Array module_columnlist[ aicrm_fieldtablename:fieldcolname:module_fieldlabel1:fieldname:fieldtypeofdata]=fieldlabel
     */

    function getColumnsListbyBlock($module, $block)
    {
//        echo $module.' => '.$block."<br>";
        global $adb;
        global $log;
        global $current_user;
        global $app_strings, $mod_strings;
        if (is_string($block)) $block = explode(",", $block);

        $tabid = getTabid($module);
        if ($module == 'Calendar') {
            $tabid = array('16');
        }
        $params = array($tabid, $block);

        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        //Security Check
        if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
            $sql = "select * from aicrm_field where aicrm_field.tabid in (" . generateQuestionMarks($tabid) . ") and aicrm_field.block in (" . generateQuestionMarks($block) . ") and aicrm_field.displaytype in (1,2,3) and aicrm_field.presence in (0,2) ";

            //fix for Ticket #4016
            if ($module == "Calendar")
                $sql .= " group by aicrm_field.fieldlabel order by sequence";
            else
                $sql .= " order by sequence";
        } else {

            $profileList = getCurrentUserProfileList();
            $sql = "select * from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid in (" . generateQuestionMarks($tabid) . ")  and aicrm_field.block in (" . generateQuestionMarks($block) . ") and aicrm_field.displaytype in (1,2,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
            if (count($profileList) > 0) {
                $sql .= " and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params, $profileList);
            }

            //fix for Ticket #4016
            if ($module == "Calendar")
                $sql .= " group by aicrm_field.fieldid,aicrm_field.fieldlabel order by sequence";
            else
                $sql .= " group by aicrm_field.fieldid order by sequence";
        }

		/*
        if ($module == 'HelpDesk' && $block == 25) {
            $module_columnlist['aicrm_crmentity:crmid:HelpDesk_Ticket_ID:ticketid:I'] = 'Ticket ID';
        }
		*/
//ekk add===========================================================================================
        if ($module == 'Questionnaire' && $block == 199) {
            $module_columnlist['tbt_transaction:trandate:' . $app_strings['report_app_trandate'] . ':trandate:I'] = $app_strings['report_app_trandate'];
        }
        if($module == 'Quotes' && $block == 52){
            $module_columnlist['aicrm_products:productname:'.$app_strings['ITEM_DETAIL_PRODUCTNAME'].':productname:I'] = $app_strings['ITEM_DETAIL_PRODUCTNAME'];
            $module_columnlist['aicrm_inventoryproductrel:uom:'.$app_strings['ITEM_DETAIL_UOM'].':uom:I'] = $app_strings['ITEM_DETAIL_UOM'];
            $module_columnlist['aicrm_inventoryproductrel:quantity:'.$app_strings['ITEM_DETAIL_quantity'].':quantity:I'] = $app_strings['ITEM_DETAIL_quantity'];
            $module_columnlist['aicrm_inventoryproductrel:listprice:'.$app_strings['ITEM_DETAIL_listprice'].':listprice:N'] = $app_strings['ITEM_DETAIL_listprice'];

            $module_columnlist['aicrm_inventoryproductrel:listprice_inc:'.$app_strings['ITEM_DETAIL_listprice_in'].':listprice_inc:N'] = $app_strings['ITEM_DETAIL_listprice_in'];
            $module_columnlist['aicrm_inventoryproductrel:standard_price:'.$app_strings['ITEM_DETAIL_sellingprice'].':standard_price:N'] = $app_strings['ITEM_DETAIL_sellingprice'];



            $module_columnlist['aicrm_inventoryproductrel:discount_percent:'.$app_strings['ITEM_DETAIL_discount_percent'].':discount_percent:I'] = $app_strings['ITEM_DETAIL_discount_percent'];
            $module_columnlist['aicrm_inventoryproductrel:discount_amount:'.$app_strings['ITEM_DETAIL_discount_amount'].':discount_amount:I'] = $app_strings['ITEM_DETAIL_discount_amount'];
            $module_columnlist['aicrm_inventoryproductrel:tax1:'.$app_strings['ITEM_DETAIL_tax1'].':tax1:I'] = $app_strings['ITEM_DETAIL_tax1'];
        }
        if($module == 'Projects' && $block == 413){
            $module_columnlist['projectProduct:productname:'.$app_strings['ITEM_DETAIL_PRODUCTNAME'].':productname:I'] = $app_strings['ITEM_DETAIL_PRODUCTNAME'];
            //$module_columnlist['projectProductrel:uom:'.$app_strings['ITEM_DETAIL_PIECE_OUM'].':uom:I'] = $app_strings['ITEM_DETAIL_PIECE_OUM'];
            $module_columnlist['projectProductrel:quantity:'.$app_strings['ITEM_DETAIL_WEIGHT_UOM'].':quantity:I'] = $app_strings['ITEM_DETAIL_WEIGHT_UOM'];
            $module_columnlist['projectProductrel:quantity_act:'.$app_strings['ITEM_DETAIL_YEAR_QA'].':quantity_act:I'] = $app_strings['ITEM_DETAIL_YEAR_QA'];
            $module_columnlist['projectProductrel:uom:'.$app_strings['ITEM_DETAIL_PIECE_OUM'].':uom:I'] = $app_strings['ITEM_DETAIL_PIECE_OUM'];
            $module_columnlist['projectProductrel:listprice:'.$app_strings['ITEM_DETAIL_PRICE'].':listprice:I'] = $app_strings['ITEM_DETAIL_PRICE'];
            $module_columnlist['projectProductrel:listprice_total:'.$app_strings['ITEM_DETAIL_TOTAL_PRICE'].':listprice_total:I'] = $app_strings['ITEM_DETAIL_TOTAL_PRICE'];
            //$module_columnlist['projectProductrel:total_weight:'.$app_strings['ITEM_DETAIL_TOTAL_WEIGHT'].':total_weight:I'] = $app_strings['ITEM_DETAIL_TOTAL_WEIGHT'];
            //$module_columnlist['projectProductrel:total_value:'.$app_strings['ITEM_DETAIL_TOTAL_VALUE'].':total_value:I'] = $app_strings['ITEM_DETAIL_TOTAL_VALUE'];
        }
//ekk add===========================================================================================

        $result = $adb->pquery($sql, $params);
        $noofrows = $adb->num_rows($result);

        for ($i = 0; $i < $noofrows; $i++) {
            $fieldtablename = $adb->query_result($result, $i, "tablename");
            $fieldcolname = $adb->query_result($result, $i, "columnname");
            $fieldname = $adb->query_result($result, $i, "fieldname");
            $fieldtype = $adb->query_result($result, $i, "typeofdata");
            $uitype = $adb->query_result($result, $i, "uitype");
            $fieldtype = explode("~", $fieldtype);
            $fieldtypeofdata = $fieldtype[0];

            //Here we Changing the displaytype of the field. So that its criteria will be displayed correctly in Reports Advance Filter.
            $fieldtypeofdata = ChangeTypeOfData_Filter($fieldtablename, $fieldcolname, $fieldtypeofdata);

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
            if ($fieldname == "parent_id") {
                $fieldtablename = "aicrm_crmentityRel" . $module;
                $fieldcolname = "setype";
            }
            if ($fieldname == "dealid") {
                $fieldtablename = "aicrm_deal" . $module;
                $fieldcolname = "deal_no";
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
          
            if ($fieldname == "product_id" && $module == "Opportunity") {//echo "ddd";
                $fieldtablename = "aicrm_products" . $module;
                $fieldcolname = "productname";
            }
            if ($fieldname == "product_id" && $module == "Errors") {
                $fieldtablename = "aicrm_products" . $module;
                $fieldcolname = "productname";
            }

            if ($fieldname == "projectid" && $module == "Calendar") {
                $fieldtablename = "aicrm_projects" . $module;
                $fieldcolname = "projects_name";
            }
           
            if ($fieldname == "roleid" && $module == "Users") {
                $fieldtablename = "aicrm_role" . $module;
                $fieldcolname = "rolename";
            }
            $product_id_tables = array(
                "aicrm_troubletickets" => "aicrm_productsRel",
                "aicrm_campaign" => "aicrm_productsCampaigns",
                "aicrm_faq" => "aicrm_productsFaq",
            );
            if ($fieldname == 'product_id' && isset($product_id_tables[$fieldtablename])) {
                $fieldtablename = $product_id_tables[$fieldtablename];
                $fieldcolname = "productname";
            }
            if ($fieldname == 'campaignid' && $module == 'Potentials') {
                $fieldtablename = "aicrm_campaign" . $module;
                $fieldcolname = "campaignname";
            }
            if ($fieldname == 'currency_id' && $fieldtablename == 'aicrm_pricebook') {
                $fieldtablename = "aicrm_currency_info" . $module;
                $fieldcolname = "currency_name";
            }

            // echo $fieldname; echo '<br>';
            // echo $module; echo '<br>';
            // echo $fieldtablename; echo '<br>';

            if ($fieldname == "errorsid" && $module == "Errorslist") {
                $fieldtablename = "aicrm_errors" . $module;
                $fieldcolname = "errors_no";
            }
            if ($fieldname == "jobid" && $module == "Errorslist") {
                $fieldtablename = "aicrm_jobs" . $module;
                $fieldcolname = "job_no";
            }

            if ($fieldname == "inspectiontemplateid" && $module == "Inspection") {
                $fieldtablename = "aicrm_inspectiontemplate" . $module;
                $fieldcolname = "inspectiontemplate_name";
            }
            if ($fieldname == "jobid" && $module == "Inspection") {
                $fieldtablename = "aicrm_jobs" . $module;
                $fieldcolname = "job_name";
            }
            if ($fieldname == "serialid" && $module == "Inspection") {
                $fieldtablename = "aicrm_serial" . $module;
                $fieldcolname = "serial_no";
            }
            if ($fieldname == "tools_serial" && $module == "Tools") {
                $fieldtablename = "aicrm_serial" . $module;
                $fieldcolname = "serial_no";
            }

            $fieldlabel = $adb->query_result($result, $i, "fieldlabel");
            $fieldlabel1 = str_replace(" ", "_", $fieldlabel);
            $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $fieldlabel1 . ":" . $fieldname . ":" . $fieldtypeofdata;
            $this->adv_rel_fields[$fieldtypeofdata][] = '$' . $module . '#' . $fieldname . '$' . "::" . getTranslatedString($module, $module) . " " . $fieldlabel;
            //added to escape attachments fields in Reports as we have multiple attachments
            if ($module != 'HelpDesk' || $fieldname != 'filename')
                $module_columnlist[$optionvalue] = $fieldlabel;
        }
        $log->info("Reports :: FieldColumns->Successfully returned ColumnslistbyBlock" . $module . $block);
        return $module_columnlist;
    }

    /** Function to set the standard filter aicrm_fields for the given aicrm_report
     *  This function gets the standard filter aicrm_fields for the given aicrm_report
     *  and set the values to the corresponding variables
     *  It accepts the repordid as argument
     */

    function getSelectedStandardCriteria($reportid)
    {
        global $adb;
        $sSQL = "select aicrm_reportdatefilter.* from aicrm_reportdatefilter inner join aicrm_report on aicrm_report.reportid = aicrm_reportdatefilter.datefilterid where aicrm_report.reportid=?";
        $result = $adb->pquery($sSQL, array($reportid));
        $selectedstdfilter = $adb->fetch_array($result);

        $this->stdselectedcolumn = $selectedstdfilter["datecolumnname"];
        $this->stdselectedfilter = $selectedstdfilter["datefilter"];

        if ($selectedstdfilter["datefilter"] == "custom") {
            if ($selectedstdfilter["startdate"] != "0000-00-00") {
                $this->startdate = $selectedstdfilter["startdate"];
            }
            if ($selectedstdfilter["enddate"] != "0000-00-00") {
                $this->enddate = $selectedstdfilter["enddate"];
            }
        }
    }

    /** Function to get the combo values for the standard filter
     *  This function get the combo values for the standard filter for the given aicrm_report
     *  and return a HTML string
     */

    function getSelectedStdFilterCriteria($selecteddatefilter = "")
    {
        global $mod_strings;

        $datefiltervalue = Array("custom", "prevfy", "thisfy", "nextfy", "prevfq", "thisfq", "nextfq",
            "yesterday", "today", "tomorrow", "lastweek", "thisweek", "nextweek", "lastmonth", "thismonth",
            "nextmonth", "last7days", "last30days", "last60days", "last90days", "last120days",
            "next30days", "next60days", "next90days", "next120days"
        );

        $datefilterdisplay = Array("Custom", "Previous FY", "Current FY", "Next FY", "Previous FQ", "Current FQ", "Next FQ", "Yesterday",
            "Today", "Tomorrow", "Last Week", "Current Week", "Next Week", "Last Month", "Current Month",
            "Next Month", "Last 7 Days", "Last 30 Days", "Last 60 Days", "Last 90 Days", "Last 120 Days",
            "Next 7 Days", "Next 30 Days", "Next 60 Days", "Next 90 Days", "Next 120 Days"
        );

        for ($i = 0; $i < count($datefiltervalue); $i++) {
            if ($selecteddatefilter == $datefiltervalue[$i]) {
                $sshtml .= "<option selected value='" . $datefiltervalue[$i] . "'>" . $mod_strings[$datefilterdisplay[$i]] . "</option>";
            } else {
                $sshtml .= "<option value='" . $datefiltervalue[$i] . "'>" . $mod_strings[$datefilterdisplay[$i]] . "</option>";
            }
        }

        return $sshtml;
    }

    /** Function to get the selected standard filter columns
     *  This function returns the selected standard filter criteria
     *  which is selected for aicrm_reports as an array
     *  Array stdcriteria_list[fieldtablename:fieldcolname:module_fieldlabel1]=fieldlabel
     */

    function getStdCriteriaByModule($module)
    {
        global $adb;
        global $log;
        global $current_user;
        require('user_privileges/user_privileges_' . $current_user->id . '.php');

        $tabid = getTabid($module);
        foreach ($this->module_list[$module] as $key => $blockid) {
            $blockids[] = $blockid;
        }
        $blockids = implode(",", $blockids);

        $params = array($tabid, $blockids);
        if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
            //uitype 6 and 23 added for start_date,EndDate,Expected Close Date
            $sql = "select * from aicrm_field where aicrm_field.tabid=? and (aicrm_field.uitype =5 or aicrm_field.uitype = 6 or aicrm_field.uitype = 23 or aicrm_field.displaytype=2) and aicrm_field.block in (" . generateQuestionMarks($block) . ") and aicrm_field.presence in (0,2) order by aicrm_field.sequence";
        } else {
            $profileList = getCurrentUserProfileList();
            $sql = "select * from aicrm_field inner join aicrm_tab on aicrm_tab.tabid = aicrm_field.tabid inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid  where aicrm_field.tabid=? and (aicrm_field.uitype =5 or aicrm_field.displaytype=2) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.block in (" . generateQuestionMarks($block) . ") and aicrm_field.presence in (0,2)";
            if (count($profileList) > 0) {
                $sql .= " and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params, $profileList);
            }
            $sql .= " order by aicrm_field.sequence";
        }

        $result = $adb->pquery($sql, $params);

        while ($criteriatyperow = $adb->fetch_array($result)) {
            $fieldtablename = $criteriatyperow["tablename"];
            $fieldcolname = $criteriatyperow["columnname"];
            $fieldlabel = $criteriatyperow["fieldlabel"];

            if ($fieldtablename == "aicrm_crmentity") {
                $fieldtablename = $fieldtablename . $module;
            }
            $fieldlabel1 = str_replace(" ", "_", $fieldlabel);
            $optionvalue = $fieldtablename . ":" . $fieldcolname . ":" . $module . "_" . $fieldlabel1;
            $stdcriteria_list[$optionvalue] = $fieldlabel;
        }

        $log->info("Reports :: StdfilterColumns->Successfully returned Stdfilter for" . $module);
        return $stdcriteria_list;

    }

    /** Function to form a javascript to determine the start date and end date for a standard filter
     *  This function is to form a javascript to determine
     *  the start date and End date from the value selected in the combo lists
     */

    function getCriteriaJS()
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

        $sjsStr = '<script language="JavaScript" type="text/javaScript">
			function showDateRange( type )
			{
				if (type!="custom")
				{
					document.NewReport.startdate.readOnly=true
					document.NewReport.enddate.readOnly=true
					getObj("jscal_trigger_date_start").style.visibility="hidden"
					getObj("jscal_trigger_date_end").style.visibility="hidden"
				}
				else
				{
					document.NewReport.startdate.readOnly=false
					document.NewReport.enddate.readOnly=false
					getObj("jscal_trigger_date_start").style.visibility="visible"
					getObj("jscal_trigger_date_end").style.visibility="visible"
				}
				if( type == "today" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($today) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($today) . '";
				}
				else if( type == "yesterday" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($yesterday) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($yesterday) . '";
				}
				else if( type == "tomorrow" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($tomorrow) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($tomorrow) . '";
				}        
				else if( type == "thisweek" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($thisweek0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($thisweek1) . '";
				}                
				else if( type == "lastweek" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($lastweek0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($lastweek1) . '";
				}                
				else if( type == "nextweek" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($nextweek0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($nextweek1) . '";
				}                

				else if( type == "thismonth" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($currentmonth0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($currentmonth1) . '";
				}                

				else if( type == "lastmonth" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($lastmonth0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($lastmonth1) . '";
				}             
				else if( type == "nextmonth" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($nextmonth0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($nextmonth1) . '";
				}           
				else if( type == "next7days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($today) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($next7days) . '";
				}                
				else if( type == "next30days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($today) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($next30days) . '";
				}                
				else if( type == "next60days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($today) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($next60days) . '";
				}                
				else if( type == "next90days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($today) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($next90days) . '";
				}        
				else if( type == "next120days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($today) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($next120days) . '";
				}        
				else if( type == "last7days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($last7days) . '";
					document.NewReport.enddate.value =  "' . getDisplayDate($today) . '";
				}                        
				else if( type == "last30days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($last30days) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($today) . '";
				}                
				else if( type == "last60days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($last60days) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($today) . '";
				}        
				else if( type == "last90days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($last90days) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($today) . '";
				}        
				else if( type == "last120days" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($last120days) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($today) . '";
				}        
				else if( type == "thisfy" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($currentFY0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($currentFY1) . '";
				}                
				else if( type == "prevfy" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($lastFY0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($lastFY1) . '";
				}                
				else if( type == "nextfy" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($nextFY0) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($nextFY1) . '";
				}                
				else if( type == "nextfq" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($nFq) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($nFq1) . '";
				}                        
				else if( type == "prevfq" )
				{

					document.NewReport.startdate.value = "' . getDisplayDate($pFq) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($pFq1) . '";
				}                
				else if( type == "thisfq" )
				{
					document.NewReport.startdate.value = "' . getDisplayDate($cFq) . '";
					document.NewReport.enddate.value = "' . getDisplayDate($cFq1) . '";
				}                
				else
				{
					document.NewReport.startdate.value = "";
					document.NewReport.enddate.value = "";
				}        
			}        
		</script>';

        return $sjsStr;
    }

    function getEscapedColumns($selectedfields)
    {
        $fieldname = $selectedfields[3];
        if ($fieldname == "parent_id") {
            if ($this->primarymodule == "HelpDesk" && $selectedfields[0] == "aicrm_crmentityRelHelpDesk") {
                $querycolumn = "case aicrm_crmentityRelHelpDesk.setype when 'Accounts' then aicrm_accountRelHelpDesk.accountname when 'Contacts' then aicrm_contactdetailsRelHelpDesk.lastname End" . " '" . $selectedfields[2] . "', aicrm_crmentityRelHelpDesk.setype 'Entity_type'";
                return $querycolumn;
            }
            if ($this->primarymodule == "Products" || $this->secondarymodule == "Products") {
                $querycolumn = "case aicrm_crmentityRelProducts.setype when 'Accounts' then aicrm_accountRelProducts.accountname when 'Leads' then aicrm_leaddetailsRelProducts.lastname when 'Potentials' then aicrm_potentialRelProducts.potentialname End" . " '" . $selectedfields[2] . "', aicrm_crmentityRelProducts.setype 'Entity_type'";
            }
            if ($this->primarymodule == "Calendar" || $this->secondarymodule == "Calendar") {
                $querycolumn = "case aicrm_crmentityRelCalendar.setype when 'Accounts' then aicrm_accountRelCalendar.accountname when 'Leads' then aicrm_leaddetailsRelCalendar.lastname when 'Potentials' then aicrm_potentialRelCalendar.potentialname when 'Quotes' then aicrm_quotesRelCalendar.subject when 'PurchaseOrder' then aicrm_purchaseorderRelCalendar.subject when 'Invoice' then aicrm_invoiceRelCalendar.subject End" . " '" . $selectedfields[2] . "', aicrm_crmentityRelCalendar.setype 'Entity_type'";
            }
        }
        return $querycolumn;
    }

    function getaccesfield($module)
    {
        global $current_user;
        global $adb;
        $access_fields = Array();

        $profileList = getCurrentUserProfileList();
        $query = "select aicrm_field.fieldname from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where";
        $params = array();
        if ($module == "Calendar") {
            $query .= " aicrm_field.tabid in (9,16) and aicrm_field.displaytype in (1,2,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
            if (count($profileList) > 0) {
                $query .= " and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params, $profileList);
            }
            $query .= " group by aicrm_field.fieldid order by block,sequence";
        } else {
            array_push($params, $this->primodule, $this->secmodule);
            $query .= " aicrm_field.tabid in (select tabid from aicrm_tab where aicrm_tab.name in (?,?)) and aicrm_field.displaytype in (1,2,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
            if (count($profileList) > 0) {
                $query .= " and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($params, $profileList);
            }
            $query .= " group by aicrm_field.fieldid order by block,sequence";
        }
        $result = $adb->pquery($query, $params);


        while ($collistrow = $adb->fetch_array($result)) {
            $access_fields[] = $collistrow["fieldname"];
        }
        return $access_fields;
    }

    /** Function to set the order of grouping and to find the columns responsible
     *  to the grouping
     *  This function accepts the aicrm_reportid as variable,sets the variable ascdescorder[] to the sort order and
     *  returns the array array_list which has the column responsible for the grouping
     *  Array array_list[0]=columnname
     */


    function getSelctedSortingColumns($reportid)
    {

        global $adb;
        global $log;

        $sreportsortsql = "select aicrm_reportsortcol.* from aicrm_report";
        $sreportsortsql .= " inner join aicrm_reportsortcol on aicrm_report.reportid = aicrm_reportsortcol.reportid";
        $sreportsortsql .= " where aicrm_report.reportid =? order by aicrm_reportsortcol.sortcolid";

        $result = $adb->pquery($sreportsortsql, array($reportid));
        $noofrows = $adb->num_rows($result);

        for ($i = 0; $i < $noofrows; $i++) {
            $fieldcolname = $adb->query_result($result, $i, "columnname");
            $sort_values = $adb->query_result($result, $i, "sortorder");
            $this->ascdescorder[] = $sort_values;
            $array_list[] = $fieldcolname;
        }

        $log->info("Reports :: Successfully returned getSelctedSortingColumns");
        return $array_list;
    }

    /** Function to get the selected columns list for a selected aicrm_report
     *  This function accepts the aicrm_reportid as the argument and get the selected columns
     *  for the given aicrm_reportid and it forms a combo lists and returns
     *  HTML of the combo values
     */

    function getSelectedColumnsList($reportid)
    {
        global $adb;
        global $app_strings, $$modules;
        global $log, $current_user;

        $ssql = "select aicrm_selectcolumn.* from aicrm_report inner join aicrm_selectquery on aicrm_selectquery.queryid = aicrm_report.queryid";
        $ssql .= " left join aicrm_selectcolumn on aicrm_selectcolumn.queryid = aicrm_selectquery.queryid";
        $ssql .= " where aicrm_report.reportid = ?";
        $ssql .= " order by aicrm_selectcolumn.columnindex";
        $result = $adb->pquery($ssql, array($reportid));
        $permitted_fields = Array();

        $selected_mod = split(":", $this->secmodule);
        array_push($selected_mod, $this->primodule);

        while ($columnslistrow = $adb->fetch_array($result)) {
            $fieldname = "";
            $fieldcolname = $columnslistrow["columnname"];

            $selmod_field_disabled = true;
            foreach ($selected_mod as $smod) {
                if ((stripos($fieldcolname, ":" . $smod . "_") > -1) && vtlib_isModuleActive($smod)) {
                    $selmod_field_disabled = false;
                    break;
                }
            }
            if ($selmod_field_disabled == false) {
                list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldcolname);
                require('user_privileges/user_privileges_' . $current_user->id . '.php');
                list($module, $field) = split("_", $module_field);
                if (sizeof($permitted_fields) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1) {
                    $permitted_fields = $this->getaccesfield($module);
                }
                $querycolumns = $this->getEscapedColumns($selectedfields);
                $fieldlabel = trim(str_replace($module, " ", $module_field));
                $mod_arr = explode('_', $fieldlabel);
                $mod = ($mod_arr[0] == '') ? $module : $mod_arr[0];
                $fieldlabel = trim(str_replace("_", " ", $fieldlabel));
                //modified code to support i18n issue
                $mod_lbl = getTranslatedString($mod, $module); //module
                $fld_lbl = getTranslatedString($fieldlabel, $module); //fieldlabel
                $fieldlabel = $mod_lbl . " " . $fld_lbl;
                if (CheckFieldPermission($fieldname, $mod) != 'true' && $colname != "crmid") {
                    $shtml .= "<option permission='no' value=\"" . $fieldcolname . "\" disabled = 'true'>" . $fieldlabel . "</option>";
                } else {
                    $shtml .= "<option permission='yes' value=\"" . $fieldcolname . "\">" . $fieldlabel . "</option>";
                }
            }
            //end

            if ($module == "Accounts") {
                //$module_columnlist['tbt_transaction:trandate:'.$app_strings['report_acc_trandate'].':trandate:I'] = $app_strings['report_acc_trandate'];
                if ($fieldcolname == "tbt_transaction:trandate:" . $app_strings['report_acc_trandate'] . ":trandate:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:trandate:" . $app_strings['report_acc_trandate'] . ":trandate:I\">" . $mod_lbl . " " . $app_strings['report_acc_trandate'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:trantime:'.$app_strings['report_acc_trantime'].':trantime:I'] = $app_strings['report_acc_trantime'];
                if ($fieldcolname == "tbt_transaction:trantime:" . $app_strings['report_acc_trantime'] . ":trantime:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:trantime:" . $app_strings['report_acc_trantime'] . ":trantime:I\">" . $mod_lbl . " " . $app_strings['report_acc_trantime'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:applicationcard:'.$app_strings['report_acc_applicationcard'].':applicationcard:I'] = $app_strings['report_acc_applicationcard'];
                if ($fieldcolname == "tbt_transaction:applicationcard:" . $app_strings['report_acc_applicationcard'] . ":applicationcard:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:applicationcard:" . $app_strings['report_acc_applicationcard'] . ":applicationcard:I\">" . $mod_lbl . " " . $app_strings['report_acc_applicationcard'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:point_total:'.$app_strings['report_acc_point_total'].':point_total:N'] = $app_strings['report_acc_point_total'];
                if ($fieldcolname == "tbt_transaction:point_total:" . $app_strings['report_acc_point_total'] . ":point_total:N") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:point_total:" . $app_strings['report_acc_point_total'] . ":point_total:N\">" . $mod_lbl . " " . $app_strings['report_acc_point_total'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:point:'.$app_strings['report_acc_point'].':point:N'] = $app_strings['report_acc_point'];
                if ($fieldcolname == "tbt_transaction:point:" . $app_strings['report_acc_point'] . ":point:N") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:point:" . $app_strings['report_acc_point'] . ":point:N\">" . $mod_lbl . " " . $app_strings['report_acc_point'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:point_remain:'.$app_strings['report_acc_point_remain'].':point_remain:N'] = $app_strings['report_acc_point_remain'];
                if ($fieldcolname == "tbt_transaction:point_remain:" . $app_strings['report_acc_point_remain'] . ":point_remain:N") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:point_remain:" . $app_strings['report_acc_point_remain'] . ":point_remain:N\">" . $mod_lbl . " " . $app_strings['report_acc_point_remain'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:productid:'.$app_strings['report_acc_productid'].':productid:I'] = $app_strings['report_acc_productid'];
                if ($fieldcolname == "tbt_transaction:productid:" . $app_strings['report_acc_productid'] . ":productid:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:productid:" . $app_strings['report_acc_productid'] . ":productid:I\">" . $mod_lbl . " " . $app_strings['report_acc_productid'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:product_name:'.$app_strings['report_acc_product_name'].':product_name:I'] = $app_strings['report_acc_product_name'];
                if ($fieldcolname == "tbt_transaction:product_name:" . $app_strings['report_acc_product_name'] . ":product_name:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:product_name:" . $app_strings['report_acc_product_name'] . ":product_name:I\">" . $mod_lbl . " " . $app_strings['report_acc_product_name'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:branchid:'.$app_strings['report_acc_branchid'].':branchid:I'] = $app_strings['report_acc_branchid'];
                if ($fieldcolname == "tbt_transaction:branchid:" . $app_strings['report_acc_branchid'] . ":branchid:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:branchid:" . $app_strings['report_acc_branchid'] . ":branchid:I\">" . $mod_lbl . " " . $app_strings['report_acc_branchid'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:branch_name:'.$app_strings['report_acc_branch_name'].':branch_name:I'] = $app_strings['report_acc_branch_name'];
                if ($fieldcolname == "tbt_transaction:branch_name:" . $app_strings['report_acc_branch_name'] . ":branch_name:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:branch_name:" . $app_strings['report_acc_branch_name'] . ":branch_name:I\">" . $mod_lbl . " " . $app_strings['report_acc_branch_name'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:status:'.$app_strings['report_acc_status'].':status:I'] = $app_strings['report_acc_status'];
                if ($fieldcolname == "tbt_transaction:status:" . $app_strings['report_acc_status'] . ":status:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:status:" . $app_strings['report_acc_status'] . ":status:I\">" . $mod_lbl . " " . $app_strings['report_acc_status'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:tranupdate:'.$app_strings['report_acc_tranupdate'].':tranupdate:I'] = $app_strings['report_acc_tranupdate'];
                if ($fieldcolname == "tbt_transaction:tranupdate:" . $app_strings['report_acc_tranupdate'] . ":tranupdate:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:tranupdate:" . $app_strings['report_acc_tranupdate'] . ":tranupdate:I\">" . $mod_lbl . " " . $app_strings['report_acc_tranupdate'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:tranimport:'.$app_strings['report_acc_tranimport'].':tranimport:I'] = $app_strings['report_acc_tranimport'];
                if ($fieldcolname == "tbt_transaction:tranimport:" . $app_strings['report_acc_tranimport'] . ":tranimport:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:tranimport:" . $app_strings['report_acc_tranimport'] . ":tranimport:I\">" . $mod_lbl . " " . $app_strings['report_acc_tranimport'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:flag:'.$app_strings['report_acc_flag'].':flag:I'] = $app_strings['report_acc_flag'];
                if ($fieldcolname == "tbt_transaction:flag:" . $app_strings['report_acc_flag'] . ":flag:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:flag:" . $app_strings['report_acc_flag'] . ":flag:I\">" . $mod_lbl . " " . $app_strings['report_acc_flag'] . "</option>";
                }
                //$module_columnlist['tbt_transaction:source:'.$app_strings['report_acc_source'].':source:I'] = $app_strings['report_acc_source'];
                if ($fieldcolname == "tbt_transaction:source:" . $app_strings['report_acc_source'] . ":source:I") {
                    $shtml .= "<option permission='yes' value=\"tbt_transaction:source:" . $app_strings['report_acc_source'] . ":source:I\">" . $mod_lbl . " " . $app_strings['report_acc_source'] . "</option>";
                }
            }
            
            if ($module == "Quotes") {
                if($fieldcolname == "aicrm_products:productname:".$app_strings['ITEM_DETAIL_PRODUCTNAME'].":productname:I"){
                    $shtml .= "<option permission='yes' value=\"aicrm_products:productname:".$app_strings['ITEM_DETAIL_PRODUCTNAME'].":productname:I\">" .$app_strings['ITEM_DETAIL_PRODUCTNAME']. "</option>";
                }
                if($fieldcolname == "aicrm_inventoryproductrel:uom:".$app_strings['ITEM_DETAIL_UOM'].":uom:I"){
                    $shtml .= "<option permission='yes' value=\"aicrm_inventoryproductrel:uom:".$app_strings['ITEM_DETAIL_UOM'].":uom:I\">" .$app_strings['ITEM_DETAIL_UOM']. "</option>";
                }
                if($fieldcolname == "aicrm_inventoryproductrel:quantity:".$app_strings['ITEM_DETAIL_quantity'].":quantity:I"){
                    $shtml .= "<option permission='yes' value=\"aicrm_inventoryproductrel:quantity:".$app_strings['ITEM_DETAIL_quantity'].":quantity:I\">" .$app_strings['ITEM_DETAIL_quantity']. "</option>";
                }
                if($fieldcolname == "aicrm_inventoryproductrel:listprice:".$app_strings['ITEM_DETAIL_listprice'].":listprice:N"){
                    $shtml .= "<option permission='yes' value=\"aicrm_inventoryproductrel:listprice:".$app_strings['ITEM_DETAIL_listprice'].":listprice:N\">" .$app_strings['ITEM_DETAIL_listprice']. "</option>";
                }
                if($fieldcolname == "aicrm_inventoryproductrel:discount_percent:".$app_strings['ITEM_DETAIL_discount_percent'].":discount_percent:I"){
                    $shtml .= "<option permission='yes' value=\"aicrm_inventoryproductrel:discount_percent:".$app_strings['ITEM_DETAIL_discount_percent'].":discount_percent:I\">" .$app_strings['ITEM_DETAIL_discount_percent']. "</option>";
                }
                if($fieldcolname == "aicrm_inventoryproductrel:discount_amount:".$app_strings['ITEM_DETAIL_discount_amount'].":discount_amount:I"){
                    $shtml .= "<option permission='yes' value=\"aicrm_inventoryproductrel:discount_amount:".$app_strings['ITEM_DETAIL_discount_amount'].":discount_amount:I\">" .$app_strings['ITEM_DETAIL_discount_amount']. "</option>";
                }
                if($fieldcolname == "aicrm_inventoryproductrel:tax1:".$app_strings['ITEM_DETAIL_tax1'].":tax1:I"){
                    $shtml .= "<option permission='yes' value=\"aicrm_inventoryproductrel:tax1:".$app_strings['ITEM_DETAIL_tax1'].":tax1:I\">" .$app_strings['ITEM_DETAIL_tax1']. "</option>";
                }
            }
            if ($module == "Projects"){

                if($fieldcolname == "projectProduct:productname:".$app_strings['ITEM_DETAIL_PRODUCTNAME'].":productname:I"){
                    $shtml .= "<option permission='yes' value=\"projectProduct:productname:".$app_strings['ITEM_DETAIL_PRODUCTNAME'].":productname:I\">" .$app_strings['ITEM_DETAIL_PRODUCTNAME']. "</option>";
                }
                if($fieldcolname == "projectProductrel:uom:".$app_strings['ITEM_DETAIL_PIECE_OUM'].":uom:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:uom:".$app_strings['ITEM_DETAIL_PIECE_OUM'].":uom:I\">" .$app_strings['ITEM_DETAIL_PIECE_OUM']. "</option>";
                }
                if($fieldcolname == "projectProductrel:quantity:".$app_strings['ITEM_DETAIL_WEIGHT_UOM'].":quantity:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:quantity:".$app_strings['ITEM_DETAIL_WEIGHT_UOM'].":quantity:I\">" .$app_strings['ITEM_DETAIL_WEIGHT_UOM']. "</option>";
                }
                if($fieldcolname == "projectProductrel:quantity_act:".$app_strings['ITEM_DETAIL_YEAR_QA'].":quantity_act:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:quantity_act:".$app_strings['ITEM_DETAIL_YEAR_QA'].":quantity_act:I\">" .$app_strings['ITEM_DETAIL_YEAR_QA']. "</option>";
                }
                if($fieldcolname == "projectProductrel:uom:".$app_strings['ITEM_DETAIL_UOM'].":uom:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:uom:".$app_strings['ITEM_DETAIL_UOM'].":uom:I\">" .$app_strings['ITEM_DETAIL_UOM']. "</option>";
                }
                if($fieldcolname == "projectProductrel:listprice:".$app_strings['ITEM_DETAIL_PRICE'].":listprice:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:listprice:".$app_strings['ITEM_DETAIL_PRICE'].":listprice:I\">" .$app_strings['ITEM_DETAIL_PRICE']. "</option>";
                }
                if($fieldcolname == "projectProductrel:listprice_total:".$app_strings['ITEM_DETAIL_TOTAL_PRICE'].":listprice_total:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:listprice_total:".$app_strings['ITEM_DETAIL_TOTAL_PRICE'].":listprice_total:I\">" .$app_strings['ITEM_DETAIL_TOTAL_PRICE']. "</option>";
                }
                /*if($fieldcolname == "projectProductrel:total_weight:".$app_strings['ITEM_DETAIL_TOTAL_WEIGHT'].":total_weight:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:total_weight:".$app_strings['ITEM_DETAIL_TOTAL_WEIGHT'].":total_weight:I\">" .$app_strings['ITEM_DETAIL_TOTAL_WEIGHT']. "</option>";
                }
                if($fieldcolname == "projectProductrel:total_value:".$app_strings['ITEM_DETAIL_TOTAL_VALUE'].":total_value:I"){
                    $shtml .= "<option permission='yes' value=\"projectProductrel:total_value:".$app_strings['ITEM_DETAIL_TOTAL_VALUE'].":total_value:I\">" .$app_strings['ITEM_DETAIL_TOTAL_VALUE']. "</option>";
                }*/
            }
        }
        $log->info("ReportRun :: Successfully returned getQueryColumnsList" . $reportid);
        return $shtml;
    }

    function getAdvancedFilterList($reportid)
    {
        global $adb;
        global $modules;
        global $log;
        $ssql = 'select aicrm_relcriteria.* from aicrm_report inner join aicrm_relcriteria on aicrm_relcriteria.queryid = aicrm_report.queryid left join aicrm_selectquery on aicrm_relcriteria.queryid = aicrm_selectquery.queryid';
        $ssql .= " where aicrm_report.reportid = ? order by aicrm_relcriteria.columnindex";

        $result = $adb->pquery($ssql, array($reportid));

        while ($relcriteriarow = $adb->fetch_array($result)) {
            $this->advft_column[] = $relcriteriarow["columnname"];
            $this->advft_option[] = $relcriteriarow["comparator"];
            $advfilterval = $relcriteriarow["value"];
            $col = explode(":", $relcriteriarow["columnname"]);
            $temp_val = explode(",", $relcriteriarow["value"]);
            if ($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || ($col[4] == 'DT')) {
                $val = Array();
                for ($x = 0; $x < count($temp_val); $x++) {
                    list($temp_date, $temp_time) = explode(" ", $temp_val[$x]);
                    $temp_date = getDisplayDate(trim($temp_date));
                    if (trim($temp_time) != '')
                        $temp_date .= ' ' . $temp_time;
                    $val[$x] = $temp_date;
                    if ($x == 0)
                        $advfilterval = $val[$x];
                    else
                        $advfilterval = ',' . $val[$x];
                }

            }

            $this->advft_value[] = $advfilterval;
        }
        $log->info("Reports :: Successfully returned getAdvancedFilterList");
        return true;
    }
    //<<<<<<<<advanced filter>>>>>>>>>>>>>>

    /** Function to get the list of aicrm_report folders when Save and run  the aicrm_report
     *  This function gets the aicrm_report folders from database and form
     *  a combo values of the folders and return
     *  HTML of the combo values
     */

    function sgetRptFldrSaveReport()
    {
        global $adb;
        global $log;

        $sql = "select * from aicrm_reportfolder order by folderid";
        $result = $adb->pquery($sql, array());
        $reportfldrow = $adb->fetch_array($result);
        $x = 0;
        do {
            $shtml .= "<option value='" . $reportfldrow['folderid'] . "'>" . $reportfldrow['foldername'] . "</option>";
        } while ($reportfldrow = $adb->fetch_array($result));

        $log->info("Reports :: Successfully returned sgetRptFldrSaveReport");
        return $shtml;
    }

    /** Function to get the column to total aicrm_fields in Reports
     *  This function gets columns to total aicrm_field
     *  and generated the html for that aicrm_fields
     *  It returns the HTML of the aicrm_fields along with the check boxes
     */

    function sgetColumntoTotal($primarymodule, $secondarymodule)
    {
        $options = Array();
        $options [] = $this->sgetColumnstoTotalHTML($primarymodule, 0);
        if (!empty($secondarymodule)) {
            //$secondarymodule = explode(":",$secondarymodule);
            for ($i = 0; $i < count($secondarymodule); $i++) {
                $options [] = $this->sgetColumnstoTotalHTML($secondarymodule[$i], ($i + 1));
            }
        }
        return $options;
    }

    /** Function to get the selected columns of total aicrm_fields in Reports
     *  This function gets selected columns of total aicrm_field
     *  and generated the html for that aicrm_fields
     *  It returns the HTML of the aicrm_fields along with the check boxes
     */


    function sgetColumntoTotalSelected($primarymodule, $secondarymodule, $reportid)
    {
        global $adb;
        global $log;
        $options = Array();
        if ($reportid != "") {
            $ssql = "select aicrm_reportsummary.* from aicrm_reportsummary inner join aicrm_report on aicrm_report.reportid = aicrm_reportsummary.reportsummaryid where aicrm_report.reportid=?";

            $result = $adb->pquery($ssql, array($reportid));
            if ($result) {
                $reportsummaryrow = $adb->fetch_array($result);

                do {
                    $this->columnssummary[] = $reportsummaryrow["columnname"];

                } while ($reportsummaryrow = $adb->fetch_array($result));
            }
        }
        $options [] = $this->sgetColumnstoTotalHTML($primarymodule, 0);
        if ($secondarymodule != "") {
            $secondarymodule = explode(":", $secondarymodule);
            for ($i = 0; $i < count($secondarymodule); $i++) {
                $options [] = $this->sgetColumnstoTotalHTML($secondarymodule[$i], ($i + 1));
            }
        }

        $log->info("Reports :: Successfully returned sgetColumntoTotalSelected");
        return $options;

    }

    /** Function to form the HTML for columns to total
     *  This function formulates the HTML format of the
     *  aicrm_fields along with four checkboxes
     *  It returns the HTML of the aicrm_fields along with the check boxes
     */


    function sgetColumnstoTotalHTML($module)
    {
        //retreive the aicrm_tabid
        global $adb;
        global $log;
        global $current_user;
        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        $tabid = getTabid($module);
        $escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX');
        $sparams = array($tabid);
        if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0) {
            $ssql = "select * from aicrm_field inner join aicrm_tab on aicrm_tab.tabid = aicrm_field.tabid where aicrm_field.uitype != 50 and aicrm_field.tabid=? and aicrm_field.displaytype in (1,2,3) and aicrm_field.presence in (0,2) ";
        } else {
            $profileList = getCurrentUserProfileList();
            $ssql = "select * from aicrm_field inner join aicrm_tab on aicrm_tab.tabid = aicrm_field.tabid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid  where aicrm_field.uitype != 50 and aicrm_field.tabid=? and aicrm_field.displaytype in (1,2,3) and aicrm_def_org_field.visible=0 and aicrm_profile2field.visible=0 and aicrm_field.presence in (0,2)";
            if (count($profileList) > 0) {
                $ssql .= " and aicrm_profile2field.profileid in (" . generateQuestionMarks($profileList) . ")";
                array_push($sparams, $profileList);
            }
        }

        //Added to avoid display the Related fields (Account name,Vandor name,product name, etc) in Report Calculations(SUM,AVG..)
        switch ($tabid) {
            case 2://Potentials
                //ie. Campaign name will not displayed in Potential's report calcullation
                $ssql .= " and aicrm_field.fieldname not in ('campaignid')";
                break;
            case 4://Contacts
                $ssql .= " and aicrm_field.fieldname not in ('account_id')";
                break;
            case 6://Accounts
                $ssql .= " and aicrm_field.fieldname not in ('account_id')";
                break;
            case 9://Calandar
                $ssql .= " and aicrm_field.fieldname not in ('parent_id','contact_id')";
                break;
            case 13://Trouble tickets(HelpDesk)
                $ssql .= " and aicrm_field.fieldname not in ('parent_id','product_id')";
                break;
            case 14://Products
                $ssql .= " and aicrm_field.fieldname not in ('vendor_id','product_id')";
                break;
            case 20://Quotes
                $ssql .= " and aicrm_field.fieldname not in ('potential_id','assigned_user_id1','account_id')";
                break;
            case 21://Purchase Order
                $ssql .= " and aicrm_field.fieldname not in ('contact_id','vendor_id')";
                break;
            case 22://SalesOrder
                $ssql .= " and aicrm_field.fieldname not in ('potential_id','account_id','contact_id','quote_id')";
                break;
            case 23://Invoice
                $ssql .= " and aicrm_field.fieldname not in ('salesorder_id','contact_id','account_id')";
                break;
            case 26://Campaings
                $ssql .= " and aicrm_field.fieldname not in ('product_id')";
                break;

        }

        $ssql .= " order by sequence";
        //echo  $ssql ;
        $result = $adb->pquery($ssql, $sparams);
        $columntototalrow = $adb->fetch_array($result);
        $options_list = Array();
        do {
            $typeofdata = explode("~", $columntototalrow["typeofdata"]);

            if ($typeofdata[0] == "N" || $typeofdata[0] == "I") {
                $options = Array();
                if (isset($this->columnssummary)) {
                    $selectedcolumn = "";
                    $selectedcolumn1 = "";

                    for ($i = 0; $i < count($this->columnssummary); $i++) {
                        $selectedcolumnarray = explode(":", $this->columnssummary[$i]);
                        $selectedcolumn = $selectedcolumnarray[1] . ":" . $selectedcolumnarray[2] . ":" .
                            str_replace($escapedchars, "", $selectedcolumnarray[3]);

                        if ($selectedcolumn != $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . str_replace(" ", "_", $columntototalrow['fieldlabel'])) {
                            $selectedcolumn = "";
                        } else {
                            $selectedcolumn1[$selectedcolumnarray[4]] = $this->columnssummary[$i];
                        }

                    }
                    if (isset($_REQUEST["record"]) && $_REQUEST["record"] != '') {
                        $options['label'][] = getTranslatedString($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' -' . getTranslatedString($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
                    }

                    $columntototalrow['fieldlabel'] = str_replace(" ", "_", $columntototalrow['fieldlabel']);
                    $options [] = getTranslatedString($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' - ' . getTranslatedString($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
                    if ($selectedcolumn1[2] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . "_SUM:2") {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_SUM:2" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_SUM:2" type="checkbox" value="">';
                    }
                    if ($selectedcolumn1[3] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . "_AVG:3") {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_AVG:3" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_AVG:3" type="checkbox" value="">';
                    }

                    if ($selectedcolumn1[4] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . "_MIN:4") {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_MIN:4" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_MIN:4" type="checkbox" value="">';
                    }

                    if ($selectedcolumn1[5] == "cb:" . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . "_MAX:5") {
                        $options [] = '<input checked name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_MAX:5" type="checkbox" value="">';
                    } else {
                        $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_MAX:5" type="checkbox" value="">';
                    }
                } else {
                    $options [] = getTranslatedString($columntototalrow['tablabel'], $columntototalrow['tablabel']) . ' - ' . getTranslatedString($columntototalrow['fieldlabel'], $columntototalrow['tablabel']);
                    $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_SUM:2" type="checkbox" value="">';
                    $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_AVG:3" type="checkbox" value="" >';
                    $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_MIN:4"type="checkbox" value="" >';
                    $options [] = '<input name="cb:' . $columntototalrow['tablename'] . ':' . $columntototalrow['columnname'] . ':' . $columntototalrow['fieldlabel'] . '_MAX:5" type="checkbox" value="" >';
                }
                $options_list [] = $options;
            }
        } while ($columntototalrow = $adb->fetch_array($result));

        $log->info("Reports :: Successfully returned sgetColumnstoTotalHTML");
        return $options_list;
    }
}

/** Function to get the primary module list in aicrm_reports
 *  This function generates the list of primary modules in aicrm_reports
 *  and returns an array of permitted modules
 */

function getReportsModuleList($focus)
{
    global $adb;
    global $app_list_strings;
    //global $report_modules;
    global $mod_strings;
    $modules = Array();
    foreach ($focus->module_list as $key => $value) {
        if (isPermitted($key, 'index') == "yes") {
            $count_flag = 1;

            if ($key != 'Calendar') {
                $modules [$key] = getTranslatedString($key, $key);
            } else {
                $modules [$key] = getTranslatedString('Sales Visit', 'Sales Visit');
            }
        }
    }
    return $modules;
}

/** Function to get the Related module list in aicrm_reports
 *  This function generates the list of secondary modules in aicrm_reports
 *  and returns the related module as an Array
 */

function getReportRelatedModules($module, $focus)
{
    global $app_list_strings;
    global $related_modules;
    global $mod_strings;
    $optionhtml = Array();
    if (vtlib_isModuleActive($module)) {
        if (!empty($focus->related_modules[$module])) {
            foreach ($focus->related_modules[$module] as $rel_modules) {
                if (isPermitted($rel_modules, 'index') == "yes") {
                    $optionhtml [] = $rel_modules;
                }
            }
        }
    }
    return $optionhtml;
}
?>
