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
 * $Header: /cvsroot/vtigercrm/aicrm_crm/include/utils/ListViewUtils.php,v 1.32 2006/02/03 06:53:08 mangai Exp $
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new
require_once('user_privileges/default_module_view.php'); //new
require_once('include/utils/UserInfoUtil.php');
require_once('include/Zend/Json.php');

/**This function is used to get the list view header values in a list view
 *Param $focus - module object
 *Param $module - module name
 *Param $sort_qry - sort by value
 *Param $sorder - sorting order (asc/desc)
 *Param $order_by - order by
 *Param $relatedlist - flag to check whether the header is for listvie or related list
 *Param $oCv - Custom view object
 *Returns the listview header values in an array
 */
function getListViewHeader($focus, $module, $sort_qry = '', $sorder = '', $order_by = '', $relatedlist = '', $oCv = '', $relatedmodule = '', $skipActions = false)
{
    global $log, $singlepane_view;
    $log->debug("Entering getListViewHeader(" . $module . "," . $sort_qry . "," . $sorder . "," . $order_by . "," . $relatedlist . "," . get_class($oCv) . ") method ...");
    global $adb;
    global $theme;
    global $app_strings;
    global $mod_strings;

    $arrow = '';
    $qry = getURLstring($focus);
    $theme_path = "themes/" . $theme . "/";
    $image_path = $theme_path . "images/";
    $list_header = Array();
    //Get the aicrm_tabid of the module
    $tabid = getTabid($module);
    $tabname = getParentTab();
    global $current_user;
    //added for aicrm_customview 27/5
    //echo "<pre>"; print_r($oCv); echo "</pre>"; exit;
    if ($oCv) {
        if (isset($oCv->list_fields)) {
            $focus->list_fields = $oCv->list_fields;
        }
    }
    //echo "<pre>"; print_r($focus); echo "</pre>"; 
    // Remove fields which are made inactive
    $focus->filterInactiveFields($module);

    //Added to reduce the no. of queries logging for non-admin user -- by Minnie-start
    $field_list = array();
    $j = 0;
    require('user_privileges/user_privileges_' . $current_user->id . '.php');
   
    foreach ($focus->list_fields as $name => $tableinfo) {
        $fieldname = $focus->list_fields_name[$name];
        if ($oCv) {
            if (isset($oCv->list_fields_name)) {
                $fieldname = $oCv->list_fields_name[$name];
            }
        }
        if ($fieldname == 'accountname' && $module != 'Accounts') {
            $fieldname = 'account_id';
        }
        if ($fieldname == 'lastname' && ($module == 'Quotes' || $module == 'Calendar' || $module == 'Projects' || $module == 'HelpDesk')) {
            $fieldname = 'contact_id';
        }

        if ($fieldname == 'productname' && $module != 'Products') {
            $fieldname = 'product_id';
        }
        array_push($field_list, $fieldname);
        $j++;
    }
    
    $field = Array();
    if ($is_admin == false) {

        if ($module == 'Emails') {
            $query = "SELECT fieldname FROM aicrm_field WHERE tabid = ? and aicrm_field.presence in (0,2)";
        } else {
            $profileList = getCurrentUserProfileList();
            $params = array();

            $query = "SELECT DISTINCT aicrm_field.fieldname
            FROM aicrm_field
            INNER JOIN aicrm_profile2field
            ON aicrm_profile2field.fieldid = aicrm_field.fieldid
            INNER JOIN aicrm_def_org_field
            ON aicrm_def_org_field.fieldid = aicrm_field.fieldid";
            if ($module == "Calendar") {
                $query .= " WHERE aicrm_field.tabid in (9,16) and aicrm_field.presence in (0,2)";
            } else {
                $query .= " WHERE aicrm_field.tabid = ? and aicrm_field.presence in (0,2)";
                array_push($params, $tabid);
            }

            $query .= " AND aicrm_profile2field.visible = 0
            AND aicrm_def_org_field.visible = 0
            AND aicrm_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ")
            AND aicrm_field.fieldname IN (" . generateQuestionMarks($field_list) . ")";
           //print_r($profileList); print_r($field_list);
           //echo $query; exit;

           array_push($params, $profileList, $field_list);
        }

        $result = $adb->pquery($query, $params);
        
        for ($k = 0; $k < $adb->num_rows($result); $k++) {
            $field[] = $adb->query_result($result, $k, "fieldname");
        }
    }
    //end
    //modified for aicrm_customview 27/5 - $app_strings change to $mod_strings

    foreach ($focus->list_fields as $name => $tableinfo) {
        //added for aicrm_customview 27/5
    if ($oCv) {
        if (isset($oCv->list_fields_name)) {
            $fieldname = $oCv->list_fields_name[$name];
            if ($fieldname == 'accountname' && $module != 'Accounts') {
                $fieldname = 'account_id';
            }
            if ($fieldname == 'lastname' && ($module == 'Quotes' || $module == 'Calendar' || $module == 'Projects' || $module == 'HelpDesk')) {
                $fieldname = 'contact_id';
            }
            if ($fieldname == 'productname' && $module != 'Products') {
                $fieldname = 'product_id';
            }
        } else {
            $fieldname = $focus->list_fields_name[$name];
        }
    } else {
        $fieldname = $focus->list_fields_name[$name];
        if ($fieldname == 'accountname' && $module != 'Accounts') {
            $fieldname = 'account_id';
        }
        if ($fieldname == 'lastname' && ($module == 'Quotes' || $module == 'Calendar' || $module == 'Projects' || $module == 'HelpDesk')) {
            $fieldname = 'contact_id';
        }
        if ($fieldname == 'productname' && $module != 'Products') {
            $fieldname = 'product_id';
        }

    }
    if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || in_array($fieldname, $field) || $fieldname == '' || ($name == 'Close' && $module == 'Calendar')) {
        if (isset($focus->sortby_fields) && $focus->sortby_fields != '') {
                //Added on 14-12-2005 to avoid if and else check for every list aicrm_field for arrow image and change order
            $change_sorder = array('ASC' => 'DESC', 'DESC' => 'ASC');
            $arrow_gif = array('ASC' => 'arrow_down.gif', 'DESC' => 'arrow_up.gif');

            foreach ($focus->list_fields[$name] as $tab => $col) {
                if (in_array($col, $focus->sortby_fields)) {
                    if ($order_by == $col) {
                        $temp_sorder = $change_sorder[$sorder];
                        $arrow = "&nbsp;<img src ='" . aicrm_imageurl($arrow_gif[$sorder], $theme) . "' border='0'>";
                    } else {
                        $temp_sorder = 'ASC';
                    }
                    $lbl_name = getTranslatedString($name, $module);
                        //added to display aicrm_currency symbol in listview header
                    if ($lbl_name == 'Amount') {
                        $lbl_name .= ' (' . $app_strings['LBL_IN'] . ' ' . $user_info['currency_symbol'] . ')';
                    }
                    if ($relatedlist != '' && $relatedlist != 'global')
                        if ($singlepane_view == 'true') {
                            $name = "<a href='index.php?module=" . $relatedmodule . "&action=DetailView&relmodule=" . $module . "&order_by=" . $col . "&record=" . $relatedlist . "&sorder=" . $temp_sorder . "&parenttab=" . $tabname . "' class='listFormHeaderLinks'>" . $lbl_name . "" . $arrow . "</a>";

                        } else {
                            $name = "<a href='index.php?module=" . $relatedmodule . "&action=CallRelatedList&relmodule=" . $module . "&order_by=" . $col . "&record=" . $relatedlist . "&sorder=" . $temp_sorder . "&parenttab=" . $tabname . "' class='listFormHeaderLinks'>" . $lbl_name . "" . $arrow . "</a>";

                        }
                        elseif ($module == 'Users' && $name == 'User Name')
                            $name = "<a href='javascript:;' onClick='getListViewEntries_js(\"" . $module . "\",\"parenttab=" . $tabname . "&order_by=" . $col . "&sorder=" . $temp_sorder . "" . $sort_qry . "\");' class='listFormHeaderLinks'>" . getTranslatedString('LBL_LIST_USER_NAME_ROLE', $module) . "" . $arrow . "</a>";
                        elseif ($relatedlist == "global") {
                            $name = $lbl_name;

                        } else {
                            if ($lbl_name != "Close") {
                                $name = "<a href='javascript:;' onClick='getListViewEntries_js(\"" . $module . "\",\"parenttab=" . $tabname . "&order_by=" . $col . "&start=" . $_SESSION["lvs"][$module]["start"] . "&sorder=" . $temp_sorder . "" . $sort_qry . "\");' class='listFormHeaderLinks'>" . $lbl_name . "" . $arrow . "</a>";
                            }
                        }
                        $arrow = '';
                    } else {
                        if (stripos($col, 'cf_') === 0) {
                            $tablenameArray = array_keys($tableinfo, $col);
                            $tablename = $tablenameArray[0];
                            $cf_columns = $adb->getColumnNames($tablename);

                            if (array_search($col, $cf_columns) != null) {
                                $pquery = "select fieldlabel,typeofdata from aicrm_field where tablename = ? and fieldname = ? and aicrm_field.presence in (0,2)";
                                $cf_res = $adb->pquery($pquery, array($tablename, $col));
                                if (count($cf_res) > 0) {
                                    $cf_fld_label = $adb->query_result($cf_res, 0, "fieldlabel");
                                    $typeofdata = explode("~", $adb->query_result($cf_res, 0, "typeofdata"));
                                    $new_field_label = $tablename . ":" . $col . ":" . $col . ":" . $module . "_" . str_replace(" ", "_", $cf_fld_label) . ":" . $typeofdata[0];
                                    $name = $cf_fld_label;

                                    // Update the existing field name in the database with new field name.
                                    $upd_query = "update aicrm_cvcolumnlist set columnname = ? where columnname like '" . $tablename . ":" . $col . ":" . $col . "%'";
                                    $upd_params = array($new_field_label);
                                    $adb->pquery($upd_query, $upd_params);

                                }
                            }
                        } else {
                            $name = getTranslatedString($name, $module);

                        }
                    }
                }
            }
            //added to display aicrm_currency symbol in related listview header
            if ($name == 'Amount' && $relatedlist != '') {
                $name .= ' (' . $app_strings['LBL_IN'] . ' ' . $user_info['currency_symbol'] . ')';
            }

            if ($module == "Calendar" && $name == $app_strings['Close']) {
                if (isPermitted("Calendar", "EditView") == 'yes') {
                    if ((getFieldVisibilityPermission('Events', $current_user->id, 'eventstatus') == '0') || (getFieldVisibilityPermission('Calendar', $current_user->id, 'taskstatus') == '0')) {
                        //array_push($list_header,$name);
                    }
                }
            } else {

                $list_header[] = $name;

            }
        }
    }

    //Added for Action - edit and delete link header in listview
    if (!$skipActions && (isPermitted($module, "EditView", "") == 'yes' || isPermitted($module, "Delete", "") == 'yes'))
        $list_header[] = $app_strings["LBL_ACTION"];

    $log->debug("Exiting getListViewHeader method ...");
    //echo "<pre>"; print_r($list_header); echo "</pre>"; exit;
    return $list_header;
}

/**This function is used to get the list view header in popup
 *Param $focus - module object
 *Param $module - module name
 *Param $sort_qry - sort by value
 *Param $sorder - sorting order (asc/desc)
 *Param $order_by - order by
 *Returns the listview header values in an array
 */
function getSearchListViewHeader($focus, $module, $sort_qry = '', $sorder = '', $order_by = '')
{
    global $log;
    $log->debug("Entering getSearchListViewHeader(" . get_class($focus) . "," . $module . "," . $sort_qry . "," . $sorder . "," . $order_by . ") method ...");
    global $adb;
    global $theme;
    global $app_strings;
    global $mod_strings, $current_user;
    $arrow = '';
    $list_header = Array();
    $tabid = getTabid($module);
    if (isset($_REQUEST['task_relmod_id'])) {
        $task_relmod_id = vtlib_purify($_REQUEST['task_relmod_id']);
        $pass_url .= "&task_relmod_id=" . $task_relmod_id;
    }
    if (isset($_REQUEST['relmod_id'])) {
        $relmod_id = vtlib_purify($_REQUEST['relmod_id']);
        $pass_url .= "&relmod_id=" . $relmod_id;
    }
    if (isset($_REQUEST['task_parent_module'])) {
        $task_parent_module = vtlib_purify($_REQUEST['task_parent_module']);
        $pass_url .= "&task_parent_module=" . $task_parent_module;
    }
    if (isset($_REQUEST['parent_module'])) {
        $parent_module = vtlib_purify($_REQUEST['parent_module']);
        $pass_url .= "&parent_module=" . $parent_module;
    }
    if (isset($_REQUEST['fromPotential']) && (isset($_REQUEST['acc_id']) && $_REQUEST['acc_id'] != '')) {
        $pass_url .= "&parent_module=Accounts&relmod_id=" . vtlib_purify($_REQUEST['acc_id']);
    }

    // vtlib Customization : For uitype 10 popup during paging
    if ($_REQUEST['form'] == 'vtlibPopupView') {
        $pass_url .= '&form=vtlibPopupView&forfield=' . vtlib_purify($_REQUEST['forfield']) . '&srcmodule=' . vtlib_purify($_REQUEST['srcmodule']) . '&forrecord=' . vtlib_purify($_REQUEST['forrecord']);
    }
    // END

    //Added to reduce the no. of queries logging for non-admin user -- by Minnie-start
    $field_list = array();
    $j = 0;
    require('user_privileges/user_privileges_' . $current_user->id . '.php');
    foreach ($focus->search_fields as $name => $tableinfo) {
        $fieldname = $focus->search_fields_name[$name];
        array_push($field_list, $fieldname);
        $j++;
    }
    $field = Array();
    if ($is_admin == false && $module != 'Users') {
        if ($module == 'Emails') {
            $query = "SELECT fieldname FROM aicrm_field WHERE tabid = ? and aicrm_field.presence in (0,2)";
            $params = array($tabid);
        } else {
            $profileList = getCurrentUserProfileList();
            $query = "SELECT DISTINCT aicrm_field.fieldname
            FROM aicrm_field
            INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid = aicrm_field.fieldid
            INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid = aicrm_field.fieldid
            WHERE aicrm_field.tabid = ?
            AND aicrm_profile2field.visible=0
            AND aicrm_def_org_field.visible=0
            AND aicrm_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ")
            AND aicrm_field.fieldname IN (" . generateQuestionMarks($field_list) . ") and aicrm_field.presence in (0,2)";

            $params = array($tabid, $profileList, $field_list);
        }

        $result = $adb->pquery($query, $params);
        for ($k = 0; $k < $adb->num_rows($result); $k++) {
            $field[] = $adb->query_result($result, $k, "fieldname");
        }
    }
    //end
    $theme_path = "themes/" . $theme . "/";
    $image_path = $theme_path . "images/";

    $focus->filterInactiveFields($module);

    foreach ($focus->search_fields as $name => $tableinfo) {
        $fieldname = $focus->search_fields_name[$name];
        $tabid = getTabid($module);

        global $current_user;
        require('user_privileges/user_privileges_' . $current_user->id . '.php');

        if (isset($focus->sortby_fields) && $focus->sortby_fields != '') {
            foreach ($focus->search_fields[$name] as $tab => $col) {
                if (in_array($col, $focus->sortby_fields)) {
                    if ($order_by == $col) {
                        if ($sorder == 'ASC') {
                            $sorder = "DESC";
                            $arrow = "<img src ='" . aicrm_imageurl('arrow_down.gif', $theme) . "' border='0'>";
                        } else {
                            $sorder = 'ASC';
                            $arrow = "<img src ='" . aicrm_imageurl('arrow_up.gif', $theme) . "' border='0'>";
                        }
                    }
                    // vtlib customization: If translation is not available use the given name
                    $tr_name = getTranslatedString($name, $module);
                    $name = "<a href='javascript:;' onClick=\"getListViewSorted_js('" . $module . "','" . $sort_qry . $pass_url . "&order_by=" . $col . "&sorder=" . $sorder . "')\" class='listFormHeaderLinks'>" . $tr_name . "&nbsp;" . $arrow . "</a>";
                    // END
                    $arrow = '';
                } else {
                    // vtlib customization: If translation is not available use the given name
                    $tr_name = getTranslatedString($name, $module);
                    $name = $tr_name;
                    // END
                }
            }
        }
        $list_header[] = $name;
    }
    //echo "<pre>"; print_r($list_header); echo "</pre>"; exit;
    $log->debug("Exiting getSearchListViewHeader method ...");
    return $list_header;

}

/**This function generates the navigation array in a listview
 *Param $display - start value of the navigation
 *Param $noofrows - no of records
 *Param $limit - no of entries per page
 *Returns an array type
 */

//code contributed by raju for improved pagination
function getNavigationValues($display, $noofrows, $limit)
{
    global $log;
    $log->debug("Entering getNavigationValues(" . $display . "," . $noofrows . "," . $limit . ") method ...");
    $navigation_array = Array();
    global $limitpage_navigation;
    if (isset($_REQUEST['allflag']) && $_REQUEST['allflag'] == 'All') {
        $navigation_array['start'] = 1;
        $navigation_array['first'] = 1;
        $navigation_array['end'] = 1;
        $navigation_array['prev'] = 0;
        $navigation_array['next'] = 0;
        $navigation_array['end_val'] = $noofrows;
        $navigation_array['current'] = 1;
        $navigation_array['allflag'] = 'Normal';
        $navigation_array['verylast'] = 1;
        $log->debug("Exiting getNavigationValues method ...");
        return $navigation_array;
    }
    if ($noofrows != 0) {
        if (((($display * $limit) - $limit) + 1) > $noofrows) {
            $display = floor($noofrows / $limit);
        }
        $start = ((($display * $limit) - $limit) + 1);
    } else {
        $start = 0;
    }

    $end = $start + ($limit - 1);
    if ($end > $noofrows) {
        $end = $noofrows;
    }
    $paging = ceil($noofrows / $limit);
    // Display the navigation
    if ($display > 1) {
        $previous = $display - 1;
    } else {
        $previous = 0;
    }
    if ($noofrows < $limit) {
        $first = '';
    } elseif ($noofrows != $limit) {
        $last = $paging;
        $first = 1;
        if ($paging > $limitpage_navigation) {
            $first = $display - floor(($limitpage_navigation / 2));
            if ($first < 1) $first = 1;
            $last = ($limitpage_navigation - 1) + $first;
        }
        if ($last > $paging) {
            $first = $paging - ($limitpage_navigation - 1);
            $last = $paging;
        }
    }
    if ($display < $paging) {
        $next = $display + 1;
    } else {
        $next = 0;
    }
    $navigation_array['start'] = $start;
    $navigation_array['first'] = $first;
    $navigation_array['end'] = $last;
    $navigation_array['prev'] = $previous;
    $navigation_array['next'] = $next;
    $navigation_array['end_val'] = $end;
    $navigation_array['current'] = $display;
    $navigation_array['allflag'] = 'All';
    $navigation_array['verylast'] = $paging;
    $log->debug("Exiting getNavigationValues method ...");
    return $navigation_array;

}


//End of code contributed by raju for improved pagination

/**This function generates the List view entries in a list view
 *Param $focus - module object
 *Param $list_result - resultset of a listview query
 *Param $navigation_array - navigation values in an array
 *Param $relatedlist - check for related list flag
 *Param $returnset - list query parameters in url string
 *Param $edit_action - Edit action value
 *Param $del_action - delete action value
 *Param $oCv - aicrm_customview object
 *Returns an array type
 */

//parameter added for aicrm_customview $oCv 27/5
function getListViewEntries($focus, $module, $list_result, $navigation_array, $relatedlist = '', $returnset = '', $edit_action = 'EditView', $del_action = 'Delete', $oCv = '', $page = '', $selectedfields = '', $contRelatedfields = '', $skipActions = false, $return_module = '')
{
    global $log;
    global $mod_strings;
    $log->debug("Entering getListViewEntries(" . get_class($focus) . "," . $module . "," . $list_result . "," . $navigation_array . "," . $relatedlist . "," . $returnset . "," . $edit_action . "," . $del_action . "," . get_class($oCv) . ") method ...");
    $tabname = getParentTab();
    global $adb, $current_user;
    global $app_strings;
    $noofrows = $adb->num_rows($list_result);

    $list_block = Array();
    global $theme;
    $evt_status = '';
    $theme_path = "themes/" . $theme . "/";
    $image_path = $theme_path . "images/";
    //getting the aicrm_fieldtable entries from database
    $tabid = getTabid($module);

    if ($oCv) {
        if (isset($oCv->list_fields)) {
            $focus->list_fields = $oCv->list_fields;
        }
    }
    if (is_array($selectedfields) && $selectedfields != '') {
        $focus->list_fields = $selectedfields;
    }
    // Remove fields which are made inactive
    $focus->filterInactiveFields($module);

    //Added to reduce the no. of queries logging for non-admin user -- by minnie-start
    $field_list = array();
    $j = 0;
    require('user_privileges/user_privileges_' . $current_user->id . '.php');
    foreach ($focus->list_fields as $name => $tableinfo) {

        $fieldname = $focus->list_fields_name[$name];
        if ($oCv) {
            if (isset($oCv->list_fields_name)) {
                $fieldname = $oCv->list_fields_name[$name];
            }
        }
        if ($fieldname == 'accountname' && $module != 'Accounts') {
            $fieldname = 'account_id';
        }
        if ($fieldname == 'lastname' && ($module == 'Quotes' || $module == 'Calendar' || $module == 'Projects' || $module == 'HelpDesk'))
            $fieldname = 'contact_id';

        if ($fieldname == 'productname' && $module != 'Products') {
            $fieldname = 'product_id';
        }

        array_push($field_list, $fieldname);
        $j++;
    }

    $field = Array();
    if ($is_admin == false) {
        if ($module == 'Emails') {
            $query = "SELECT fieldname FROM aicrm_field WHERE tabid = ? and aicrm_field.presence in (0,2)";
            $params = array($tabid);
        } else {
            $profileList = getCurrentUserProfileList();
            $params = array();
            $query = "SELECT DISTINCT aicrm_field.fieldname
            FROM aicrm_field
            INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid = aicrm_field.fieldid
            INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid = aicrm_field.fieldid";

            if ($module == "Calendar")
                $query .= " WHERE aicrm_field.tabid in (9,16) and aicrm_field.presence in (0,2)";
            else {
                $query .= " WHERE aicrm_field.tabid = ? and aicrm_field.presence in (0,2)";
                array_push($params, $tabid);
            }

            $query .= " AND aicrm_profile2field.visible = 0
            AND aicrm_profile2field.visible = 0
            AND aicrm_def_org_field.visible = 0
            AND aicrm_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ")
            AND aicrm_field.fieldname IN (" . generateQuestionMarks($field_list) . ")";

            array_push($params, $profileList, $field_list);
        }

        $result = $adb->pquery($query, $params);
        for ($k = 0; $k < $adb->num_rows($result); $k++) {
            $field[] = $adb->query_result($result, $k, "fieldname");
        }
    }
    //constructing the uitype and columnname array
    $ui_col_array = Array();

    $params = array();
    $query = "SELECT uitype, columnname, fieldname FROM aicrm_field ";

    if ($module == "Calendar")
        $query .= " WHERE aicrm_field.tabid in (9,16) and aicrm_field.presence in (0,2)";
    else {
        $query .= " WHERE aicrm_field.tabid = ? and aicrm_field.presence in (0,2)";
        array_push($params, $tabid);
    }
    $query .= " AND fieldname IN (" . generateQuestionMarks($field_list) . ") ";

    array_push($params, $field_list);

    $result = $adb->pquery($query, $params);
    $num_rows = $adb->num_rows($result);
    for ($i = 0; $i < $num_rows; $i++) {
        $tempArr = array();
        $uitype = $adb->query_result($result, $i, 'uitype');
        $columnname = $adb->query_result($result, $i, 'columnname');
        $field_name = $adb->query_result($result, $i, 'fieldname');
        $tempArr[$uitype] = $columnname;
        $ui_col_array[$field_name] = $tempArr;
    }
    //end
    if ($navigation_array['start'] != 0)
        for ($i = 1; $i <= $noofrows; $i++) {
            $list_header = Array();
            //Getting the entityid
            if ($module != 'Users') {
                $entity_id = $adb->query_result($list_result, $i - 1, "crmid");
                $owner_id = $adb->query_result($list_result, $i - 1, "smownerid");
            } else {
                $entity_id = $adb->query_result($list_result, $i - 1, "id");
            }

            $priority = $adb->query_result($list_result, $i - 1, "priority");

            $font_color_high = "color:#00DD00;";
            $font_color_medium = "color:#DD00DD;";
            $P_FONT_COLOR = "";
            // echo $module;
            foreach ($focus->list_fields as $name => $tableinfo) {
                $fieldname = $focus->list_fields_name[$name];
                
                // echo '<pre>'; print_r($fieldname); echo "</pre>"; 
                //added for aicrm_customview 27/5
                if ($oCv) {

                    if (isset($oCv->list_fields_name)) {
                        $fieldname = $oCv->list_fields_name[$name];
                        if ($fieldname == 'accountname' && $module != 'Accounts') {
                            $fieldname = 'account_id';
                        }
                        if ($fieldname == 'lastname' && ($module == 'Quotes' || $module == 'Calendar' || $module == 'Projects' || $module == 'HelpDesk')) {
                            $fieldname = 'contact_id';
                        }
                        if ($fieldname == 'productname' && $module != 'Products') {
                            $fieldname = 'product_id';
                        }
                    } else {
                        $fieldname = $focus->list_fields_name[$name];
                    }

                } else {
                    $fieldname = $focus->list_fields_name[$name];
                    if ($fieldname == 'accountname' && $module != 'Accounts') {
                        $fieldname = 'account_id';
                    }
                    if ($fieldname == 'lastname' && ($module == 'Quotes' || $module == 'Calendar' || $module == 'Projects' || $module == 'HelpDesk')) {
                        $fieldname = 'contact_id';
                    }
                    if ($fieldname == 'productname' && $module != 'Products') {
                        $fieldname = 'product_id';
                    }
                }

                if ($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || in_array($fieldname, $field) || $fieldname == '' || ($name == 'Close' && $module == 'Calendar')) {

                    if ($fieldname == '') {
                        $table_name = '';
                        $column_name = '';
                        foreach ($tableinfo as $tablename => $colname) {

                            $table_name = $tablename;
                            $column_name = $colname;
                        }
                        $value = $adb->query_result($list_result, $i - 1, $colname);

                    } else {
                        if ($module == 'Calendar') {
                            $act_id = $adb->query_result($list_result, $i - 1, "activityid");

                            $cal_sql = "select activitytype from aicrm_activity where activityid=?";
                            $cal_res = $adb->pquery($cal_sql, array($act_id));
                            if ($adb->num_rows($cal_res) >= 0)
                                $activitytype = $adb->query_result($cal_res, 0, "activitytype");
                        }
                        if (($module == 'Calendar' || $module == 'Emails' || $module == 'HelpDesk' || $module == 'Leads' || $module == 'LeadManagement' || $module == 'Contacts') && (($fieldname == 'parent_id') || ($name == 'Contact Name' || $name == 'ชื่อผู้ติดต่อ') || ($name == 'Close') || ($fieldname == 'firstname'))) {
                           
                            if ($module == 'Calendar') {
                                if ($fieldname == 'status') {
                                    if ($activitytype == 'Task') {
                                        $fieldname = 'taskstatus';
                                    } else {
                                        $fieldname = 'eventstatus';
                                    }
                                }
                                if ($activitytype == 'Task') {
                                    if (getFieldVisibilityPermission('Calendar', $current_user->id, $fieldname) == '0') {
                                        $has_permission = 'yes';
                                    } else {
                                        $has_permission = 'no';
                                    }
                                } else {
                                    if (getFieldVisibilityPermission('Events', $current_user->id, $fieldname) == '0') {
                                        $has_permission = 'yes';
                                    } else {
                                        $has_permission = 'no';
                                    }
                                }
                            }
                            if ($module != 'Calendar' || ($module == 'Calendar' && $has_permission == 'yes')) {

                                if ($fieldname == 'parent_id') {
                                    $value = getRelatedTo($module, $list_result, $i - 1);
                                }
                                if ($name == 'Contact Name' || $name == 'ชื่อผู้ติดต่อ') {
                                    
                                    $contact_id = $adb->query_result($list_result, $i - 1, "contactid");
                                    $contact_name = getFullNameFromQResult($list_result, $i - 1, "Contacts");
                                    $value = "";
                                    //Added to get the contactname for activities custom view - t=2190
                                    if ($contact_id != '' && $contact_name == '') {
                                        $contact_name = getContactName($contact_id);
                                    }
                                   
                                    if (($contact_name != "") && ($contact_id != 'NULL')) {
                                        // Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
                                        $value = "<a href='index.php?module=Contacts&action=DetailView&parenttab=" . $tabname . "&record=" . $entity_id . "' style='" . $P_FONT_COLOR . "'>" . $contact_name . "</a>";
                                        
                                        // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
                                    }
                                }
                                if ($fieldname == "firstname") {
                                    $first_name = textlength_check($adb->query_result($list_result, $i - 1, "firstname"));

                                    $value = '<a href="index.php?action=DetailView&module=' . $module . '&parenttab=' . $tabname . '&record=' . $entity_id . '">' . $first_name . '</a>';
                                }

                                if ($name == 'Close') {
                                    $status = $adb->query_result($list_result, $i - 1, "status");
                                    $activityid = $adb->query_result($list_result, $i - 1, "activityid");
                                    if (empty($activityid)) {
                                        $activityid = $adb->query_result($list_result, $i - 1, "tmp_activity_id");
                                    }
                                    $activitytype = $adb->query_result($list_result, $i - 1, "activitytype");
                                    // TODO - Picking activitytype when it is not present in the Custom View.
                                    // Going forward, this column should be added to the select list if not already present as a performance improvement.
                                    if (empty($activitytype)) {
                                        $activitytypeRes = $adb->pquery('SELECT activitytype FROM aicrm_activity WHERE activityid=?', array($activityid));
                                        if ($adb->num_rows($activitytypeRes) > 0) {
                                            $activitytype = $adb->query_result($activitytypeRes, 0, 'activitytype');
                                        }
                                    }
                                    if ($activitytype != 'Task' && $activitytype != 'Emails') {
                                        $eventstatus = $adb->query_result($list_result, $i - 1, "eventstatus");
                                        if (isset($eventstatus)) {
                                            $status = $eventstatus;
                                        }
                                    }
                                    if ($status == 'Deferred' || $status == 'Completed' || $status == 'Held' || $status == '') {
                                        $value = "";
                                    } else {
                                        if ($activitytype == 'Task')
                                            $evt_status = '&status=Completed';
                                        else
                                            $evt_status = '&eventstatus=Held';
                                        if (isPermitted("Calendar", 'EditView', $activityid) == 'yes') {
                                            if ($returnset == '') {
                                                $returnset = '&return_module=Calendar&return_action=ListView&return_id=' . $activityid . '&return_viewname=' . $oCv->setdefaultviewid;
                                            }
                                        } else {
                                            $value = "";
                                        }
                                    }
                                }

                            } else {
                                $value = "";
                            }

                        } elseif ($module == "Documents" && ($fieldname == 'filelocationtype' || $fieldname == 'filename' || $fieldname == 'filesize' || $fieldname == 'filestatus' || $fieldname == 'filetype')) {
                            $value = $adb->query_result($list_result, $i - 1, $fieldname);
                            if ($fieldname == 'filelocationtype') {
                                if ($value == 'I')
                                    $value = getTranslatedString('LBL_INTERNAL', $module);
                                elseif ($value == 'E')
                                    $value = getTranslatedString('LBL_EXTERNAL', $module);
                                else
                                    $value = ' --';
                            }

                            if ($fieldname == 'filename') {
                                $downloadtype = $adb->query_result($list_result, $i - 1, 'filelocationtype');
                                if ($downloadtype == 'I') {
                                    $fld_value = $value;
                                    $ext_pos = strrpos($fld_value, ".");
                                    $ext = substr($fld_value, $ext_pos + 1);
                                    $ext = strtolower($ext);
                                    if ($value != '') {
                                        if ($ext == 'bin' || $ext == 'exe' || $ext == 'rpm')
                                            $fileicon = "<img src='" . aicrm_imageurl('fExeBin.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
                                        elseif ($ext == 'jpg' || $ext == 'gif' || $ext == 'bmp')
                                            $fileicon = "<img src='" . aicrm_imageurl('fbImageFile.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
                                        elseif ($ext == 'txt' || $ext == 'doc' || $ext == 'xls')
                                            $fileicon = "<img src='" . aicrm_imageurl('fbTextFile.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
                                        elseif ($ext == 'zip' || $ext == 'gz' || $ext == 'rar')
                                            $fileicon = "<img src='" . aicrm_imageurl('fbZipFile.gif', $theme) . "' hspace='3' align='absmiddle'    border='0'>";
                                        else
                                            $fileicon = "<img src='" . aicrm_imageurl('fbUnknownFile.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
                                    }
                                } elseif ($downloadtype == 'E') {
                                    if (trim($value) != '') {
                                        $fld_value = $value;
                                        $fileicon = "<img src='" . aicrm_imageurl('fbLink.gif', $theme) . "' alt='" . getTranslatedString('LBL_EXTERNAL_LNK', $module) . "' title='" . getTranslatedString('LBL_EXTERNAL_LNK', $module) . "' hspace='3' align='absmiddle' border='0'>";
                                    } else {
                                        $fld_value = '--';
                                        $fileicon = '';
                                    }
                                } else {
                                    $fld_value = ' --';
                                    $fileicon = '';
                                }

                                $file_name = $adb->query_result($list_result, $i - 1, 'filename');
                                $notes_id = $adb->query_result($list_result, $i - 1, 'crmid');
                                $folder_id = $adb->query_result($list_result, $i - 1, 'folderid');
                                $download_type = $adb->query_result($list_result, $i - 1, 'filelocationtype');
                                $file_status = $adb->query_result($list_result, $i - 1, 'filestatus');
                                $fileidQuery = "select attachmentsid from aicrm_seattachmentsrel where crmid=?";
                                $fileidres = $adb->pquery($fileidQuery, array($notes_id));
                                $fileid = $adb->query_result($fileidres, 0, 'attachmentsid');

                                if ($file_name != '' && $file_status == 1) {
                                    if ($download_type == 'I') {
                                       /* $fld_value = "<a href='index.php?module=uploads&action=downloadfile&entityid=$notes_id&fileid=$fileid' title='" . getTranslatedString("LBL_DOWNLOAD_FILE", $module) . "' onclick='javascript:dldCntIncrease($notes_id);'>" . $fld_value . "</a>";*/
                                       $fld_value = "<a href='index.php?module=uploads&action=downloadfile&entityid=$notes_id&fileid=$fileid' title='".getTranslatedString("LBL_DOWNLOAD_FILE",$module)."' onclick='javascript:dldCntIncrease($notes_id);'>".$fld_value."</a>";
                                   } elseif ($download_type == 'E') {
                                    $fld_value = "<a target='_blank' href='$file_name' onclick='javascript:dldCntIncrease($notes_id);' title='" . getTranslatedString("LBL_DOWNLOAD_FILE", $module) . "'>" . $fld_value . "</a>";
                                } else {
                                    $fld_value = ' --';
                                }
                            }

                            $value = $fileicon . $fld_value;
                        }
                        if ($fieldname == 'filesize') {
                            $downloadtype = $adb->query_result($list_result, $i - 1, 'filelocationtype');
                            if ($downloadtype == 'I') {
                                $filesize = $value;
                                if ($filesize < 1024)
                                    $value = $filesize . ' B';
                                elseif ($filesize > 1024 && $filesize < 1048576)
                                    $value = round($filesize / 1024, 2) . ' KB';
                                else if ($filesize > 1048576)
                                    $value = round($filesize / (1024 * 1024), 2) . ' MB';
                            } else {
                                $value = ' --';
                            }
                        }
                        if ($fieldname == 'filestatus') {
                            $filestatus = $value;
                            if ($filestatus == 1)
                                $value = getTranslatedString('yes', $module);
                            elseif ($filestatus == 0)
                                $value = getTranslatedString('no', $module);
                            else
                                $value = ' --';
                        }
                        if ($fieldname == 'filetype') {
                            $downloadtype = $adb->query_result($list_result, $i - 1, 'filelocationtype');
                            $filetype = $adb->query_result($list_result, $i - 1, 'filetype');
                            if ($downloadtype == 'E' || $downloadtype != 'I') {
                                $value = ' --';
                            } else
                            $value = $filetype;
                        }
                        if ($fieldname == 'notecontent') {
                            $value = decode_html($value);
                            $value = textlength_check($value);
                        }

                    } elseif ($module == "Products" && $name == "Related to") {
                        $value = getRelatedTo($module, $list_result, $i - 1);
                        
                        //} elseif (($name == 'Contact Name' || $name == 'ชื่อผู้ติดต่อ' ) && ($module == 'Projects'|| $module == 'Quotes' || $module == 'Job' || $module == 'Order')) {
                    } elseif (($name == 'Contact Name' || $name == 'ชื่อผู้ติดต่อ' || $name == 'ผู้ติดต่อ')) {
                        if ($name == 'Contact Name' || $name == 'ชื่อผู้ติดต่อ' || $name == 'ผู้ติดต่อ'){

                            $contact_id = $adb->query_result($list_result, $i - 1, "contactid");
                            $contact_name = getFullNameFromQResult($list_result, $i - 1, "Contacts");
                            $value = "";

                                //echo isPermitted('Contacts','DetailView',$contact_id);

                            if (($contact_name != "") && ($contact_id != 'NULL')){
                                if($module == 'Projects'){
                                  $display = isPermitted('Contacts','DetailView',$contact_id);
                                  if($display == 'no'){
                                     $value = $contact_name;
                                 }else{
                                    $value = "<a href='index.php?module=Contacts&action=DetailView&parenttab=" . $tabname . "&record=" . $contact_id . "' style='" . $P_FONT_COLOR . "'>" . $contact_name . "</a>";
                                }
                            }else{
                                $value = "<a href='index.php?module=Contacts&action=DetailView&parenttab=" . $tabname . "&record=" . $contact_id . "' style='" . $P_FONT_COLOR . "'>" . $contact_name . "</a>";
                            }



                        }
                    }
                            //Update Check Label Product => Unit Name
                            //} elseif($name == 'Product' || $name == 'Unit Name') {
                } elseif ($name == 'Product') {
                    $product_id = textlength_check($adb->query_result($list_result, $i - 1, "productname"));
                    $value = $product_id;

                } elseif ($name == 'Unit Name' && $module != 'Products') {
                    $product_id = $adb->query_result($list_result, $i - 1, "productid");
                    $value = textlength_check($product_id);

                    if ($product_id != '')
                        $product_name = getProductName($product_id);
                    else
                        $product_name = '';

                    $value = '<a href="index.php?module=Products&action=DetailView&parenttab=' . $tabname . '&record=' . $product_id . '">' . textlength_check($product_name) . '</a>';

                } else if($module == 'Projects' && preg_match('/account_id/i', $fieldname)){
                    $account_id = $adb->query_result($list_result, $i - 1, $fieldname);
                    if($account_id != '' && $account_id != '0'){
                        $value = getAccountName($adb->query_result($list_result, $i - 1, $fieldname));
                    } else {
                        $value = '';
                    }
                }else if(($module == 'Quotes' || $module == 'Claim') && $name == 'ชื่อโครงการ'){
                    $projectsid = $adb->query_result($list_result, $i - 1, $fieldname);
                    
                    if ($projectsid != ''){
                        $project_name = getProject($projectsid);
                    }else{
                        $project_name = '';
                    }
                    $value = $project_name;
                    $value = '<a href="index.php?module=Projects&action=DetailView&record=' . $projectsid . '&parenttab=' . $tabname . '" style="' . $P_FONT_COLOR . '" target="_blank">' . $project_name . '</a>';

                }elseif ($name == 'Account Name' || $name == 'ชื่อลูกค้า') {
                    //modified for aicrm_customview 27/5
                    if ($module == 'Accounts') {
                        $account_id = $adb->query_result($list_result, $i - 1, "crmid");
                        $account_name = textlength_check($adb->query_result($list_result, $i - 1, "accountname"));
                                // Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
                                $value = '<a href="index.php?module=Accounts&action=DetailView&record=' . $account_id . '&parenttab=' . $tabname . '" style="' . $P_FONT_COLOR . '">' . $account_name . '</a>'; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
                            } elseif ($module == 'Branchs' || $module == 'Smartquestionnaire' || $module == 'Questionnairetemplate'|| $module == 'Serial' || $module == 'Errors' || $module == 'Errorslist' || $module == 'Sparepart' || $module == 'Sparepartlist' || $module == 'Competitor' || $module == 'Smartemail' || $module == 'Projects' || $module == 'Opportunity' || $module == 'Activitys' || $module == 'KnowledgeBase' || $module == 'Quotation' || $module == 'PriceList' || $module == 'Job' || $module == 'SmartSms' || $module == 'Potentials' || $module == 'Contacts' || $module == 'Faq' || $module == 'Plant' || $module == 'Order' || $module == 'Announcement' || $module == 'Promotionvoucher' || $module == 'Competitorproduct' || $module == 'Premuimproduct' || $module == 'Servicerequest' || $module == 'Point' || $module == 'Redemption' || $module == 'Salesorder' || $module == 'Seriallist'|| $module == 'Inspection' || $module == 'Inspectiontemplate' || $module == 'Salesinvoice' || $module == 'Expense' || $module == 'Purchasesorder' || $module == 'Samplerequisition' || $module == 'Goodsreceive' || $module == 'Questionnaire' || $module == 'Marketingtools' || $module == 'Quotes') {
                                //Potential,Contacts,Invoice,SalesOrder & Quotes  records   sort by Account Name
                                $accountname = textlength_check($adb->query_result($list_result, $i - 1, "accountname"));
                                $accountid = $adb->query_result($list_result, $i - 1, "accountid");
                                if($module == 'Projects'){
                                  $display = isPermitted('Projects','DetailView',$accountid);
                                  if($display == 'no'){
                                     $value = $accountname;
                                 }else{
                                    $value = '<a href="index.php?module=Accounts&action=DetailView&record=' . $accountid . '&parenttab=' . $tabname . '" style="' . $P_FONT_COLOR . '">' . $accountname . '</a>';
                                }
                            }else{
                                $value = '<a href="index.php?module=Accounts&action=DetailView&record=' . $accountid . '&parenttab=' . $tabname . '" style="' . $P_FONT_COLOR . '">' . $accountname . '</a>';
                            }
                        } elseif($module == 'Calendar' || $module == 'Deal' || $module == 'Quotes' ) {
                            $parentid = $adb->query_result($list_result, $list_result_count, "parentid");
                            $parent_module = getSalesEntityType($parentid);
                            if ($parent_module == "Leads") {
                                $tablename = "aicrm_leaddetails";
                                $name = "concat(firstname,' ',lastname) as full_name";
                                $fieldname = "full_name";
                                $idname = "leadid";
                            }
                            if ($parent_module == "Accounts") {
                                $tablename = "aicrm_account";
                                $name = "accountname";
                                $fieldname = "accountname";
                                $idname = "accountid";
                            }

                            if($parent_module != '' && $parentid != '' && $parentid != '0') {

                                $sql = "SELECT ".$name." FROM $tablename WHERE $idname = ?";
                                $fieldvalue = $adb->query_result($adb->pquery($sql, array($parentid)), 0, $fieldname);
                                $fieldvalue = $adb->query_result($adb->pquery($sql, array($parentid)), 0, $fieldname);
                                $value = '<a href=index.php?module=' . $parent_module . '&action=DetailView&record=' . $parentid . '&parenttab=' . urlencode($tabname) . '>' . $fieldvalue . '</a>';
                            }else{
                                $value = '';
                            }

                        } else {

                            $account_id = $adb->query_result($list_result, $i - 1, "accountid");
                            $account_name = getAccountName($account_id);
                            $acc_name = textlength_check($account_name);
                                // Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
                                $value = '<a href="index.php?module=Accounts&action=DetailView&record=' . $account_id . '&parenttab=' . $tabname . '" style="' . $P_FONT_COLOR . '">' . $acc_name . '</a>'; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
                            }

                        } elseif ($fieldname == 'product_id1') {

                            if ($module == 'HelpDesk')
                                $product_id = $adb->query_result($list_result, $i - 1, "product_id1");
                            else
                                $product_id = $adb->query_result($list_result, $i - 1, "productid");

                            if ($product_id != '')
                                $product_name = getProductName($product_id);
                            else
                                $product_name = '';

                            $value = '<a href="index.php?module=Products&action=DetailView&parenttab=' . $tabname . '&record=' . $product_id . '">' . textlength_check($product_name) . '</a>';

                        } elseif (($module == 'Job' || $module == 'HelpDesk' || $module == 'Salesinvoice' || $module == 'PriceBook' || $module == 'PriceList' || $module == 'Quotes' || $module == 'Serial') && ($name == 'Product Name' || $name == 'Mat Master' || $name == 'ชื่อสินค้า')) {

                            if ($module == 'HelpDesk' || $module == 'Faq' ){
                                $product_id = $adb->query_result($list_result, $i - 1, "product_id");
                            }else{
                                $product_id = $adb->query_result($list_result, $i - 1, "productid");
                            }

                            if ($product_id != ''){
                                $product_name = getProductName($product_id);
                            }
                            else{
                                $product_name = '';
                            }

                            $value = '<a href="index.php?module=Products&action=DetailView&parenttab=' . $tabname . '&record=' . $product_id . '">' . textlength_check($product_name) . '</a>';

                        } elseif (($module == 'Calendar') && $name == 'Check In Location') {
                            $value = $adb->query_result($list_result, $i - 1, "location");
                            $value_array = split(',', $value);
                            $value = "<a href='https://www.google.co.th/maps/dir//" . $value_array[0] . "," . $value_array[1] . "/@" . $value_array[0] . "," . $value_array[1] . ",20z' target=_blank >" . $value . "</a>";

                        } elseif($fieldname == 'event_id'){
                            $event_id = $adb->query_result($list_result,$i-1,"event_id");

                            $parent_module = getSalesEntityType($event_id);
                            if($parent_module == "Job"){
                                $event_name = getjob($event_id);
                                $value='<a href="index.php?module='.$parent_module.'&action=DetailView&parenttab=Sales&record='.$event_id.'">'.$event_name.'</a>';
                            }elseif($parent_module == "HelpDesk"){
                                $event_name = get_HelpDesk_No($event_id);
                                $value='<a href="index.php?module='.$parent_module.'&action=DetailView&parenttab=Sales&record='.$event_id.'">'.$event_name.'</a>';
                             }elseif($parent_module == "Deal"){
                                $event_name = getdealname($event_id);
                                $value='<a href="index.php?module='.$parent_module.'&action=DetailView&parenttab=Sales&record='.$event_id.'">'.$event_name.'</a>';
                            }elseif($parent_module == "Projects"){
                                $event_name = get_Projects_Name($event_id);
                                $value='<a href="index.php?module='.$parent_module.'&action=DetailView&parenttab=Sales&record='.$event_id.'">'.$event_name.'</a>';
                            }else{
                                $value='';
                            }

                        } elseif (($module == 'Quotes' && $name == 'Potential Name')) {
                            $potential_id = $adb->query_result($list_result, $i - 1, "potentialid");
                            $potential_name = getPotentialName($potential_id);
                            $value = '<a href="index.php?module=Potentials&action=DetailView&parenttab=' . $tabname . '&record=' . $potential_id . '">' . textlength_check($potential_name) . '</a>';

                        } elseif ($module == 'Emails' && $relatedlist != '' && ($name == 'Subject' || $name == 'Date Sent' || $name == 'To')) {
                            $list_result_count = $i - 1;
                            $tmp_value = getValue($ui_col_array, $list_result, $fieldname, $focus, $module, $entity_id, $list_result_count, "list", "", $returnset, $oCv->setdefaultviewid);
                            $value = '<a href="javascript:;" onClick="ShowEmail(\'' . $entity_id . '\');">' . textlength_check($tmp_value) . '</a>';
                            if ($name == 'Date Sent') {
                                $sql = "select email_flag from aicrm_emaildetails where emailid=?";
                                $result = $adb->pquery($sql, array($entity_id));
                                $email_flag = $adb->query_result($result, 0, "email_flag");
                                if ($email_flag != 'SAVED')
                                    $value = getValue($ui_col_array, $list_result, $fieldname, $focus, $module, $entity_id, $list_result_count, "list", "", $returnset, $oCv->setdefaultviewid);
                                else
                                    $value = '';
                            }

                        } elseif ($module == 'Quotes' && in_array($fieldname, ['user_name', 'total'])){
                            $value = $adb->query_result($list_result, $i - 1, $fieldname);
                        } elseif ($module == 'Projects' && in_array($fieldname, ['assigned_user_id'])){
                            $value = $adb->query_result($list_result, $i - 1, 'user_name');
                        } else {
                            if (count($ui_col_array[$fieldname]) > 0) {

                            } else {

                                $params = array();
                                $query = "SELECT uitype, columnname, fieldname FROM aicrm_field where 1 ";
                                $query .= " AND columnname='" . $fieldname . "' ";
                                $result = $adb->pquery($query, "");
                                $num_rows = $adb->num_rows($result);
                                for ($kkk = 0; $kkk < $num_rows; $kkk++) {
                                    $tempArr = array();
                                    $uitype = $adb->query_result($result, $kkk, 'uitype');
                                    $columnname = $adb->query_result($result, $kkk, 'columnname');
                                    $field_name = $adb->query_result($result, $kkk, 'fieldname');
                                    $tempArr[$uitype] = $columnname;
                                    $ui_col_array[$field_name] = $tempArr;
                                }
                                $tempArr[$uitype] = $columnname;
                                $ui_col_array[$field_name] = $tempArr;
                            }

                            $list_result_count = $i - 1;
                            $value = getValue($ui_col_array, $list_result, $fieldname, $focus, $module, $entity_id, $list_result_count, "list", "", $returnset, $oCv->setdefaultviewid);

                        }
                    }

                    // vtlib customization: For listview javascript triggers
                    $value = "$value <span type='vtlib_metainfo' vtrecordid='{$entity_id}' vtfieldname='{$fieldname}' vtmodule='$module' style='display:none;'></span>";
                    // END

                    if ($module == "Calendar" && $name == $app_strings['Close']) {
                        if (isPermitted("Calendar", "EditView") == 'yes') {
                            if ((getFieldVisibilityPermission('Events', $current_user->id, 'eventstatus') == '0') || (getFieldVisibilityPermission('Calendar', $current_user->id, 'taskstatus') == '0')) {
                                //array_push($list_header,$value);
                            }
                        }
                    } else
                    $list_header[] = $value;

                }
                // echo $value; echo "<br>";
            }
            
            $varreturnset = '';
            if ($returnset == ''){
                $varreturnset = '&return_module=' . $module . '&return_action=index';
            }else{
                $varreturnset = $returnset;
            }

            if ($module == 'Calendar') {
                $actvity_type = $adb->query_result($list_result, $list_result_count, 'activitytype');
                if ($actvity_type == 'Task')
                    $varreturnset .= '&activity_mode=Task';
                else
                    $varreturnset .= '&activity_mode=Events';
            }

            //Added for Actions ie., edit and delete links in listview
            $links_info = "";
            
            if ($module=="Quotes") {
                $quotation_status = $adb->query_result($list_result,$i-1,"quotation_status");

                /*if($is_admin == true || ($quotation_status=='เปิดใบเสนอราคา')){*/
                if($quotation_status=='เปิดใบเสนอราคา'){
                    if(!(is_array($selectedfields) && $selectedfields != ''))
                    {
                        if(isPermitted($module,"EditView",$entity_id) == 'yes'){

                            $edit_link = getListViewEditLink($module,$entity_id,$relatedlist,$varreturnset,$list_result,$list_result_count);
                            if(isset($_REQUEST['start']) && $_REQUEST['start'] > 1 && $module != 'Emails'){
                                $links_info .= "<a href=\"$edit_link&start=".vtlib_purify($_REQUEST['start'])."\">".$app_strings["LNK_EDIT"]."</a> ";
                            }else{
                                $links_info .= "<a href=\"$edit_link\">".$app_strings["LNK_EDIT"]."</a> ";
                            }
                        }

                        if(isPermitted($module,"Delete",$entity_id) == 'yes'){
                            $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);
                            if($links_info != "" && $del_link != ""){
                                $links_info .=  " | ";
                            }
                            if($del_link != ""){
                                $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                            }
                        }
                    }
                    // Record Change Notification
                    if(method_exists($focus, 'isViewed') && PerformancePrefs::getBoolean('LISTVIEW_RECORD_CHANGE_INDICATOR', true)) {
                        if(!$focus->isViewed($entity_id)) {
                            $links_info .= " | <img src='" . aicrm_imageurl('important1.gif', $theme) . "' border=0 title='Owner Unread'>";
                        }
                    }
                }

            }else if ($module=="Voucher") {

                if(!(is_array($selectedfields) && $selectedfields != ''))
                {

                    if(isPermitted($module,"Delete",$entity_id) == 'yes'){
                        $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);

                        if($del_link != ""){
                            $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                        }
                    }
                }
                // Record Change Notification
                if(method_exists($focus, 'isViewed') && PerformancePrefs::getBoolean('LISTVIEW_RECORD_CHANGE_INDICATOR', true)) {
                    if(!$focus->isViewed($entity_id)) {
                        $links_info .= " | <img src='" . aicrm_imageurl('important1.gif', $theme) . "' border=0 title='Owner Unread'>";
                    }
                }

            }else if ($module=="Salesinvoice") {

                if(!(is_array($selectedfields) && $selectedfields != ''))
                {

                    if(isPermitted($module,"Delete",$entity_id) == 'yes'){
                        $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);

                        if($del_link != ""){
                            $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                        }
                    }
                }
                // Record Change Notification
                if(method_exists($focus, 'isViewed') && PerformancePrefs::getBoolean('LISTVIEW_RECORD_CHANGE_INDICATOR', true)) {
                    if(!$focus->isViewed($entity_id)) {
                        $links_info .= " | <img src='" . aicrm_imageurl('important1.gif', $theme) . "' border=0 title='Owner Unread'>";
                    }
                }

            }else if ($module=="Leads") {
                
                if(isPermitted($module,"EditView","") == 'yes'){
                    //echo "<pre>"; print_r($list_result); echo "</pre>"; exit;
                    $converted = $adb->query_result($list_result, $i-1,"converted");
                    $edit_link = getListViewEditLink($module,$entity_id,$relatedlist,$varreturnset,$list_result,$list_result_count);

                    //echo $converted; echo '<br>';
                    if($converted!="1"){
                        $links_info .= "<a href=\"$edit_link\">".$app_strings["LNK_EDIT"]."</a>";
                    }else{
                        $links_info .= "";
                    }
                    if(isPermitted($module,"Delete","") == 'yes'){
                        $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);
                        if($converted!="1"){
                            if($links_info != "" && $del_link != ""){
                                $links_info .=  " | ";
                            }
                            if($del_link != ""){
                                $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                            }
                        }else{
                            $links_info .= "";
                        }
                        if($relatedlist == 'relatedlist'){
                             $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                        }
                    }
                }

            }else if ($module=="Inspection" || $module == "Inspectiontemplate") {

                if(isPermitted($module,"EditView",$entity_id) == 'yes'){

                    $edit_link = getListViewEditLink($module,$entity_id,$relatedlist,$varreturnset,$list_result,$list_result_count);
                    if(isset($_REQUEST['start']) && $_REQUEST['start'] > 1 && $module != 'Emails'){
                    // $links_info .= "<a href=\"$edit_link&start=".vtlib_purify($_REQUEST['start'])."\">".$app_strings["LNK_EDIT"]."</a> ";
                    }else{
                    // $links_info .= "<a href=\"$edit_link\">".$app_strings["LNK_EDIT"]."</a> ";
                    }
                }

                if(isPermitted($module,"Delete",$entity_id) == 'yes'){
                    $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);
                    if($links_info != "" && $del_link != ""){
                    // $links_info .=  " | ";
                    }
                    if($del_link != ""){
                    // $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                    }
                }

            }else if ($module=="Projects") {
                if(isPermitted($module,"EditView",$entity_id) == 'yes'){

                    $edit_link = getListViewEditLink($module,$entity_id,$relatedlist,$varreturnset,$list_result,$list_result_count);
                    if(isset($_REQUEST['start']) && $_REQUEST['start'] > 1 && $module != 'Emails'){
                        $links_info .= "<a href=\"$edit_link&start=".vtlib_purify($_REQUEST['start'])."\" class='ls-modal-link' onclick='modal_projects(\"$edit_link\",event)'>".$app_strings["LNK_EDIT"]."</a> ";
                    }else{
                        $links_info .= "<a href=\"$edit_link\" class='ls-modal-link' onclick='modal_projects(\"$edit_link\",event)'>".$app_strings["LNK_EDIT"]."</a> ";
                    }
                }

                if(isPermitted($module,"Delete",$entity_id) == 'yes'){
                    $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);
                    if($links_info != "" && $del_link != ""){
                        $links_info .=  " | ";
                    }
                    if($del_link != ""){
                        $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                    }
                }
            /*}else if ($module=="Calendar") {
                
                if(isPermitted($module,"EditView","") == 'yes'){
                    $flag_send_report = $adb->query_result($list_result, $i-1,"flag_send_report");
                    $edit_link = getListViewEditLink($module,$entity_id,$relatedlist,$varreturnset,$list_result,$list_result_count);
                    if($flag_send_report!="1"){
                        $links_info .= "<a href=\"$edit_link\">".$app_strings["LNK_EDIT"]."</a>";
                    }else{
                        $links_info .= "";
                    }
                    if(isPermitted($module,"Delete","") == 'yes'){
                        $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);
                        if($flag_send_report!="1"){
                            if($links_info != "" && $del_link != ""){
                                $links_info .=  " | ";
                            }
                            if($del_link != ""){
                                $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                            }
                        }else{
                            $links_info .= "";
                        }
                        if($relatedlist == 'relatedlist'){
                             $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                        }
                    }
                }*/

            }else{
                if(!(is_array($selectedfields) && $selectedfields != ''))
                {
                    if(isPermitted($module,"EditView",$entity_id) == 'yes'){

                        $edit_link = getListViewEditLink($module,$entity_id,$relatedlist,$varreturnset,$list_result,$list_result_count);
                        if(isset($_REQUEST['start']) && $_REQUEST['start'] > 1 && $module != 'Emails'){
                            $links_info .= "<a href=\"$edit_link&start=".vtlib_purify($_REQUEST['start'])."\">".$app_strings["LNK_EDIT"]."</a> ";
                        }else{
                            $links_info .= "<a href=\"$edit_link\">".$app_strings["LNK_EDIT"]."</a> ";
                        }
                    }

                    if(isPermitted($module,"Delete",$entity_id) == 'yes'){
                        $del_link = getListViewDeleteLink($module,$entity_id,$relatedlist,$varreturnset);
                        if($links_info != "" && $del_link != ""){
                            $links_info .=  " | ";
                        }
                        if($del_link != ""){
                            $links_info .=  "<a href='javascript:confirmdelete(\"".addslashes(urlencode($del_link))."\")'>".$app_strings["LNK_DELETE"]."</a>";
                        }
                    }
                }
                // Record Change Notification
                if(method_exists($focus, 'isViewed') && PerformancePrefs::getBoolean('LISTVIEW_RECORD_CHANGE_INDICATOR', true)) {
                    if(!$focus->isViewed($entity_id)) {
                        $links_info .= " | <img src='" . aicrm_imageurl('important1.gif', $theme) . "' border=0 title='Owner Unread'>";
                    }
                }
            }

        // END
        /*if($module=="Point" || $module=="Redemption"){
            $links_info="&nbsp;";
        }*/

        if($links_info != "" && !$skipActions){
            $list_header[] = $links_info;
        }else{
            $links_info = '';
            $list_header[] = $links_info;
        }
        $list_block[$entity_id] = $list_header;

    }

    $log->debug("Exiting getListViewEntries method ...");
    return $list_block;

}
    /**This function generates the List view entries in a popup list view
     *Param $focus - module object
     *Param $list_result - resultset of a listview query
     *Param $navigation_array - navigation values in an array
     *Param $relatedlist - check for related list flag
     *Param $returnset - list query parameters in url string
     *Param $edit_action - Edit action value
     *Param $del_action - delete action value
     *Param $oCv - aicrm_customview object
     *Returns an array type
     */
    function getSearchListViewEntries($focus, $module, $list_result, $navigation_array, $form = '')
    {
        global $log;
        $log->debug("Entering getSearchListViewEntries(" . get_class($focus) . "," . $module . "," . $list_result . "," . $navigation_array . ") method ...");
        global $adb, $app_strings, $theme, $current_user, $list_max_entries_per_page;
        $noofrows = $adb->num_rows($list_result);

        $list_header = '';
        $theme_path = "themes/" . $theme . "/";
        $image_path = $theme_path . "images/";
        $list_block = Array();

        //getting the aicrm_fieldtable entries from database
        $tabid = getTabid($module);
        if($tabid == 9){
            $tabid = 16;
        }
        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        //Added to reduce the no. of queries logging for non-admin user -- by Minnie-start
        $field_list = array();
        $j = 0;

        foreach ($focus->search_fields as $name => $tableinfo) {
            $fieldname = $focus->search_fields_name[$name];
            array_push($field_list, $fieldname);
            $j++;
        }

        $field = Array();
        if ($is_admin == false && $module != 'Users') {
            if ($module == 'Emails') {
                $query = "SELECT fieldname FROM aicrm_field WHERE tabid = ? and aicrm_field.presence in (0,2)";
                $params = array($tabid);

            } else {
                $profileList = getCurrentUserProfileList();
                $query = "SELECT DISTINCT aicrm_field.fieldname
                FROM aicrm_field
                INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid = aicrm_field.fieldid
                INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid = aicrm_field.fieldid
                WHERE aicrm_field.tabid = ?
                AND aicrm_profile2field.visible = 0
                AND aicrm_def_org_field.visible = 0
                AND aicrm_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ")
                AND aicrm_field.fieldname IN (" . generateQuestionMarks($field_list) . ") and aicrm_field.presence in (0,2)";
                $params = array($tabid, $profileList, $field_list);
            }

            $result = $adb->pquery($query, $params);
            for ($k = 0; $k < $adb->num_rows($result); $k++) {
                $field[] = $adb->query_result($result, $k, "fieldname");
            }
        }

        //constructing the uitype and columnname array
        $ui_col_array = Array();

        $query = "SELECT uitype, columnname, fieldname
        FROM aicrm_field
        WHERE tabid=?
        AND fieldname IN (" . generateQuestionMarks($field_list) . ") and aicrm_field.presence in (0,2)";

        $result = $adb->pquery($query, array($tabid, $field_list));
        $num_rows = $adb->num_rows($result);
        
        for ($i = 0; $i < $num_rows; $i++) {
            $tempArr = array();
            $uitype = $adb->query_result($result, $i, 'uitype');
            $columnname = $adb->query_result($result, $i, 'columnname');
            $field_name = $adb->query_result($result, $i, 'fieldname');
            $tempArr[$uitype] = $columnname;
            $ui_col_array[$field_name] = $tempArr;
        }

        if ($navigation_array['end_val'] > 0) {

            for ($i = 1; $i <= $noofrows; $i++) {
                //Getting the entityid

                if ($module != 'Users') {
                    $entity_id = $adb->query_result($list_result, $i - 1, "crmid");
                } else {
                    $entity_id = $adb->query_result($list_result, $i - 1, "id");
                }

                $list_header = Array();

                foreach ($focus->search_fields as $name => $tableinfo) {

                    $fieldname = $focus->search_fields_name[$name];

                    if ($fieldname == '') {
                        $table_name = '';
                        $column_name = '';
                        foreach ($tableinfo as $tablename => $colname) {
                            $table_name = $tablename;
                            $column_name = $colname;
                        }
                        $value = $adb->query_result($list_result, $i - 1, $colname);

                    } else {
                        if (($module == 'Calls' || $module == 'Tasks' || $module == 'Meetings' || $module == 'Emails') && (($name == 'Related to') || ($name == 'Contact Name' || $name == 'ชื่อผู้ติดต่อ') || ($name == 'Vendor Name'))) {

                            if ($name == 'Related to')
                                $value = getRelatedTo($module, $list_result, $i - 1);

                            if ($name == 'Contact Name' || $name == 'ชื่อผู้ติดต่อ') {
                                $contact_id = $adb->query_result($list_result, $i - 1, "contactid");
                                $contact_name = getFullNameFromQResult($list_result, $i - 1, "Contacts");
                                $value = "";
                                if (($contact_name != "") && ($contact_id != 'NULL')){
                                    $value = "<a href='index.php?module=Contacts&action=DetailView&record=" . $contact_id . "'>" . $contact_name . "</a>";
                                }
                            }
                        } elseif (($module == 'Documents') && $name == 'Related to') {
                            $value = getRelatedToEntity($module, $list_result, $i - 1);
                        } elseif ($name == 'Account Name' && ($module == 'Projects' || $module == 'Potentials' || $module == 'Quotes')) {
                            $account_id = $adb->query_result($list_result, $i - 1, "accountid");
                            $account_name = getAccountName($account_id);
                            $value = textlength_check($account_name);
                        } elseif ($name == 'Account Name' && ($module == 'ServiceRequest')) {
                            $account_id = $adb->query_result($list_result, $i - 1, "accountid");
                            if ($account_id != '')
                                $account_name = getAccountName($account_id);
                            else
                                $account_name = '';

                            $value = '<a href="index.php?module=Accounts&action=DetailView&parenttab=' . $tabname . '&record=' . $account_id . '">' . textlength_check($account_name) . '</a>';
                        } elseif ($name == 'Account Name' && $module == 'Contacts') {
                            $account_id = $adb->query_result($list_result, $i - 1, "accountid");
                            $account_name = getAccountName($account_id);
                            $value = textlength_check($account_name);
                        } elseif ($fieldname == 'account_id' && $module == 'Contacts') {
                            $value = $adb->query_result($list_result, $i - 1, "accountid");
                        } // vtlib customization: Generic popup handling
                        elseif($fieldname == 'contact_id' && $module == 'Projects') {
                            $contact_id = $adb->query_result($list_result, $i - 1, "contactid");
                            $contact_name = getContactName($contact_id);
                            $value = "";
                            if (($contact_name != "") && ($contact_id != 'NULL')){
                                $value = textlength_check($contact_name);
                            }
                        }
                        elseif (isset($focus->popup_fields) && in_array($fieldname, $focus->popup_fields)) {

                            global $default_charset;
                            $forfield = htmlspecialchars($_REQUEST['forfield'], ENT_QUOTES, $default_charset);
                            $list_result_count = $i - 1;
                            $value = getValue($ui_col_array, $list_result, $fieldname, $focus, $module, $entity_id, $list_result_count, "search", $focus->popup_type);
                            if (isset($forfield) && $forfield != '' && $focus->popup_type != 'detailview') {
                                $value = strip_tags($value); // Remove any previous html conversion
                                $value = "<a href='javascript:window.close();' onclick='return vtlib_setvalue_from_popup($entity_id, \"$value\", \"$forfield\")'>$value</a>";
                            }
                        } // END
                        else {

                            $list_result_count = $i - 1;
                            if (count($ui_col_array[$fieldname]) > 0) {

                            }else{
                                $params = array();
                                $query = "SELECT uitype, columnname, fieldname FROM aicrm_field where 1 ";
                                $query .= " AND columnname='" . $fieldname . "' ";
                                $result = $adb->pquery($query, "");
                                $num_rows = $adb->num_rows($result);

                                for ($i = 0; $i < $num_rows; $i++) {
                                    $tempArr = array();
                                    $uitype = $adb->query_result($result, $i, 'uitype');
                                    $columnname = $adb->query_result($result, $i, 'columnname');
                                    $field_name = $adb->query_result($result, $i, 'fieldname');
                                    $tempArr[$uitype] = $columnname;
                                    $ui_col_array[$field_name] = $tempArr;
                                }
                                $tempArr[$uitype] = $columnname;
                                $ui_col_array[$field_name] = $tempArr;
                            }
                            //echo $fieldname; echo "<br>";
                            $value = getValue($ui_col_array, $list_result, $fieldname, $focus, $module, $entity_id, $list_result_count, "search", $focus->popup_type, $form);
                        }
                    }
                    $list_header[] = $value;
                }

                /*if ($module == 'Products' && ($focus->popup_type == 'inventory_prod' || $focus->popup_type == 'inventory_prod_po')) {

                    global $default_charset;
                    require('user_privileges/user_privileges_' . $current_user->id . '.php');
                    $row_id = $_REQUEST['curr_row'];

                    //To get all the tax types and values and pass it to product details
                    $tax_str = '';
                    $tax_details = getAllTaxes();

                    for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                        $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                    }

                    $tax_str = trim($tax_str, ',');
                    $rate = $user_info['conv_rate'];

                    if (getFieldVisibilityPermission($module, $current_user->id, 'unit_price') == '0') {
                        $unitprice = $adb->query_result($list_result, $list_result_count, 'unit_price');

                        if ($_REQUEST['currencyid'] != null) {
                            $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id));
                            $unitprice = $prod_prices[$entity_id];
                        }
                    } else {
                        $unit_price = '';
                    }

                    $sub_products = '';
                    $sub_prod = '';
                    $sub_prod_query = $adb->pquery("SELECT aicrm_products.productid,aicrm_products.productname,aicrm_products.products_businessplusno from aicrm_products INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_products.productid INNER JOIN aicrm_seproductsrel on aicrm_seproductsrel.crmid=aicrm_products.productid WHERE aicrm_seproductsrel.productid=? and aicrm_seproductsrel.setype='Products'", array($entity_id));

                    for ($k = 0; $k < $adb->num_rows($sub_prod_query); $k++) {
                        $id = $adb->query_result($sub_prod_query, $k, "productid");
                        $str_sep = '';
                        if ($k > 0) $str_sep = ":";
                        $sub_products .= $str_sep . $id;
                        $sub_prod .= $str_sep . " - " . $adb->query_result($sub_prod_query, $k, "productname");
                        $products_business .= $str_sep . " - " . $adb->query_result($sub_prod_query, $k, "products_businessplusno");
                    }

                    $sub_det = $sub_products . "::" . str_replace(":", "<br>", $sub_prod);
                    $qty_stock = $adb->query_result($list_result, $list_result_count, 'qtyinstock');
                    $slashes_temp_val = popup_from_html(getProductName($entity_id));
                    $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                    $description = $adb->query_result($list_result, $list_result_count, 'description');

                    $slashes_desc = htmlspecialchars($description, ENT_QUOTES, $default_charset);

                    $sub_products_link = '<a href="index.php?module=Products&action=Popup&html=Popup_picker&return_module=' . vtlib_purify($_REQUEST['return_module']) . '&record_id=' . vtlib_purify($entity_id) . '&form=HelpDeskEditView&select=enable&popuptype=' . $focus->popup_type . '&curr_row=' . vtlib_purify($row_id) . '&currencyid=' . vtlib_purify($_REQUEST['currencyid']) . '" > Sub Products</a>';

                    if (!isset($_REQUEST['record_id'])) {
                        $sub_products_query = $adb->pquery("SELECT * from aicrm_seproductsrel WHERE productid=? AND setype='Products'", array($entity_id));
                        if ($adb->num_rows($sub_products_query) > 0)
                            $list_header[] = $sub_products_link;
                        else
                            $list_header[] = $app_strings['LBL_NO_SUB_PRODUCTS'];
                    }
                }*/
                //echo $entity_id; echo "<br>";
                $list_block[$entity_id] = $list_header;
            }
        }

        $list = $list_block;
        $log->debug("Exiting getSearchListViewEntries method ...");
        //echo "<pre>"; print_r($list); echo "</pre>"; exit;
        return $list;
    }


    /**This function generates the value for a given aicrm_field namee
     *Param $field_result - aicrm_field result in array
     *Param $list_result - resultset of a listview query
     *Param $fieldname - aicrm_field name
     *Param $focus - module object
     *Param $module - module name
     *Param $entity_id - entity id
     *Param $list_result_count - list result count
     *Param $mode - mode type
     *Param $popuptype - popup type
     *Param $returnset - list query parameters in url string
     *Param $viewid - custom view id
     *Returns an string value
     */


    function getValue($field_result, $list_result, $fieldname, $focus, $module, $entity_id, $list_result_count, $mode, $popuptype, $returnset = '', $viewid = '')
    {   

        global $log, $listview_max_textlength, $app_strings, $current_language, $currentModule;
        $log->debug("Entering getValue(" . $field_result . "," . $list_result . "," . $fieldname . "," . get_class($focus) . "," . $module . "," . $entity_id . "," . $list_result_count . "," . $mode . "," . $popuptype . "," . $returnset . "," . $viewid . ") method ...");
        global $adb, $current_user, $default_charset;
        //echo $fieldname ; echo "<br>";echo "<br>";

        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        $tabname = getParentTab();
        $tabid = getTabid($module);
        $current_module_strings = return_module_language($current_language, $module);
        $uicolarr = $field_result[$fieldname];

        foreach ($uicolarr as $key => $value) {
            $uitype = $key;
            $colname = $value;
        }

        $field_val = $adb->query_result($list_result, $list_result_count, $colname);

        if (stristr(html_entity_decode($field_val), "<a href") === false && $uitype != 8) {
           if ($module == "Activitys" && $colname == "description" && $_REQUEST["action"] == "CallRelatedList") {
            $temp_val = nl2br($field_val);
            } else {
                $temp_val = textlength_check($field_val);
            }
        } elseif ($uitype != 8) {
            $temp_val = html_entity_decode($field_val, ENT_QUOTES);
        } else {
            $temp_val = textlength_check($field_val);
        }

        // vtlib customization: New uitype to handle relation between modules
        if ($uitype == '10') {
            $parent_id = $field_val;
            if (!empty($parent_id)) {
                $parent_module = getSalesEntityType($parent_id);
                $valueTitle = $parent_module;
                if ($app_strings[$valueTitle]) $valueTitle = $app_strings[$valueTitle];

                $displayValueArray = getEntityName($parent_module, $parent_id);
                if (!empty($displayValueArray)) {
                    foreach ($displayValueArray as $key => $value) {
                        $displayValue = $value;
                    }
                }
                $value = "<a href='index.php?module=$parent_module&action=DetailView&record=$parent_id' title='$valueTitle'>$displayValue</a>";
            } else {
                $value = '';
            }
        } // END
        else if ($uitype == 53) {

            $value = textlength_check($adb->query_result($list_result, $list_result_count, 'user_name'));


        } elseif ($uitype == 52) {
            $value = getUserName($adb->query_result($list_result, $list_result_count, 'handler'));
        } elseif ($uitype == 51)//Accounts - Member Of
        {
            $parentid = $adb->query_result($list_result, $list_result_count, "parentid");
            if ($module == 'Accounts')
                $entity_name = textlength_check(getAccountName($parentid));
            elseif ($module == 'Products')
                $entity_name = textlength_check(getProductName($parentid));

            //$value = '<a href="index.php?module=' . $module . '&action=DetailView&record=' . $parentid . '&parenttab=' . $tabname . '" style="' . $P_FONT_COLOR . '">' . $entity_name . '</a>';
        } elseif ($uitype == 77) {
            $value = getUserName($adb->query_result($list_result, $list_result_count, 'inventorymanager'));
        } elseif ($uitype == 5 || $uitype == 6 || $uitype == 23 || $uitype == 70) {
            if ($temp_val != '' && $temp_val != '0000-00-00') {
                $value = getDisplayDate($temp_val);
            } elseif ($temp_val == '0000-00-00') {
                $value = '';
            } else {
                $value = $temp_val;
            }
        } elseif (($uitype == 15 && $fieldname != "activitytype") || ($uitype == 55 && $fieldname == "salutationtype")) {


            $colname = $colname == 'eventstatus' ? 'status' : $colname;
            //$colname = $colname == 'taskstatus' ? 'eventstatus' : $colname;
            $temp_val = decode_html($adb->query_result($list_result, $list_result_count, $colname));

            if (($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1) && $temp_val != '') {
                $temp_acttype = $adb->query_result($list_result, $list_result_count, 'activitytype');
                if (($temp_acttype == 'Meeting' || $temp_acttype == 'Call') && $fieldname == "taskstatus")
                    $temptable = "eventstatus";
                else
                    $temptable = $fieldname;

                $roleid = $current_user->roleid;
                $roleids = Array();
                $subrole = getRoleSubordinates($roleid);
                if (count($subrole) > 0)
                    $roleids = $subrole;
                array_push($roleids, $roleid);

                //here we are checking wheather the table contains the sortorder column .If  sortorder is present in the main picklist table, then the role2picklist will be applicable for this table...

                $sql = "select * from aicrm_$temptable where $temptable=?";

                $res = $adb->pquery($sql, array(decode_html($temp_val)));
                $picklistvalueid = $adb->query_result($res, 0, 'picklist_valueid');
                if ($picklistvalueid != null) {
                    $pick_query = "select * from aicrm_role2picklist where picklistvalueid=$picklistvalueid and roleid in (" . generateQuestionMarks($roleids) . ")";
                    $res_val = $adb->pquery($pick_query, array($roleids));
                    $num_val = $adb->num_rows($res_val);
                }
                if ($num_val > 0 || ($temp_acttype == 'Task' && $fieldname == 'activitytype'))
                    $temp_val = $temp_val;
                else
                    $temp_val = "<font color='red'>" . $app_strings['LBL_NOT_ACCESSIBLE'] . "</font>";
            }
            $value = ($current_module_strings[$temp_val] != '') ? $current_module_strings[$temp_val] : (($app_strings[$temp_val] != '') ? ($app_strings[$temp_val]) : $temp_val);
            if ($value != "<font color='red'>" . $app_strings['LBL_NOT_ACCESSIBLE'] . "</font>") {
                $value = textlength_check($value);
            }

            if ($fieldname == 'activitytype') {
                $value = '<a href="index.php?module=' . $module . '&action=DetailView&record=' . $entity_id . '&parenttab=' . $tabname . '" style="' . $P_FONT_COLOR . '">' . $value . '</a>';
            }

            if($fieldname == 'sms_sender_name' && $value != "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>"){
                $sql="SELECT * FROM aicrm_config_sender_sms WHERE id=?";
                $result=$adb->pquery($sql, array($temp_val));
                $sender_name=$adb->query_result($result,0,"sms_sender");
                $value= $sender_name ;
            }
            
            if($fieldname == 'email_server' && $value != "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>"){
                $sql="SELECT * FROM aicrm_config_sendemail WHERE id=?";
                $result=$adb->pquery($sql, array($temp_val));
                $email_server=$adb->query_result($result,0,"email_server");
                $value= $email_server ;
            }

        } elseif ($uitype == 71 || $uitype == 72) {
            if ($temp_val != '') {
                if ($fieldname == 'unit_price') {
                    $currency_id = getProductBaseCurrency($entity_id, $module);
                    $cursym_convrate = getCurrencySymbolandCRate($currency_id);
                    $value = "<font style='color:grey;'>" . $cursym_convrate['symbol'] . "</font> " . $temp_val;
                } else {
                    $rate = $user_info['conv_rate'];
                    //changes made to remove aicrm_currency symbol infront of each aicrm_potential amount
                    if ($temp_val != 0) $value = convertFromDollar($temp_val, $rate);
                    else $value = $temp_val;
                }
            } else {
                $value = '';
            }

        } elseif ($uitype == 17) {
            $value = '<a href="http://' . $field_val . '" target="_blank">' . $temp_val . '</a>';
        } elseif ($uitype == 13 || $uitype == 104 && ($_REQUEST['action'] != 'Popup' && $_REQUEST['file'] != 'Popup')) {
            if ($_SESSION['internal_mailer'] == 1) {
                //check added for email link in user detailview
                if ($module == 'Calendar') {
                    if (getActivityType($entity_id) == 'Task') {
                        $tabid = 9;
                    } else {
                        $tabid = 16;
                    }
                } else {
                    $tabid = getTabid($module);
                }
                $fieldid = getFieldid($tabid, $fieldname);
                if (empty($popuptype)) {
                    $value = '<a href="javascript:InternalMailer(' . $entity_id . ',' . $fieldid . ',\'' . $fieldname . '\',\'' . $module . '\',\'record_id\');">' . $temp_val . '</a>';
                } else {
                    $value = $temp_val;
                }
            } else
            $value = '<a href="mailto:' . $field_val . '">' . $temp_val . '</a>';
        } elseif ($uitype == 56) {
            if ($temp_val == 1) {
                $value = $app_strings['yes'];
            } elseif ($temp_val == 0) {
                $value = $app_strings['no'];
            } else {
                $value = '';
            }
        } elseif ($uitype == 57) {
            if ($temp_val != '') {
                $sql = "SELECT * FROM aicrm_contactdetails WHERE contactid=?";
                $result = $adb->pquery($sql, array($temp_val));
                $value = '';
                if ($adb->num_rows($result)) {
                    $name = getFullNameFromQResult($result, 0, "Contacts");
                    $value = '<a href=index.php?module=Contacts&action=DetailView&record=' . $temp_val . '>' . $name . '</a>';
                }
            } else
            $value = '';
        } //Added by Minnie to get Campaign Source
        elseif ($uitype == 58) {
            if ($temp_val != '') {
                $sql = "SELECT * FROM aicrm_campaign WHERE campaignid=?";
                $result = $adb->pquery($sql, array($temp_val));
                $campaignname = $adb->query_result($result, 0, "campaignname");
                $value = '<a href=index.php?module=Campaigns&action=DetailView&record=' . $temp_val . '>' . $campaignname . '</a>';
            } else
            $value = '';
        }
        //End
        //Added By *Raj* for the Issue ProductName not displayed in CustomView of HelpDesk
        elseif ($uitype == 59) {

            if ($temp_val != '') {

                $data = getProductName($temp_val);
                $value = '<a href="index.php?module=Products&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }

        } elseif ($uitype == 921) {
            if ($temp_val != '') {
                $data = getAccountPrevName($temp_val);
                $value = '<a href="index.php?module=Accounts&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }
        } elseif ($uitype == 929) {
            if ($temp_val != '') {
                $data = getAccountNameField($temp_val);
                $value = '<a href="index.php?module=Accounts&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }
        } elseif ($uitype == 930) {
            if ($temp_val != '') {
                $data = getAccountcoderms($temp_val);
                $value = '<a href="index.php?module=Accounts&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }
        } elseif ($uitype == 931) {
            if ($temp_val != '') {
                $data = getContactcode($temp_val);
                $value = '<a href="index.php?module=Contacts&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }
        } elseif ($uitype == 932) {// "modify by";
            if($temp_val != '' && $temp_val!='0')
            {
                $data = getOwnerName($temp_val);
                $value ='<a href="index.php?module=Users&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            } else {
                $value ='';
            }
        } elseif ($uitype == 934) {
            if ($temp_val != '') {
                $data = getContactcode($temp_val);
                $value = '<a href="index.php?module=Contacts&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }
            /*if ($temp_val != '') {
                $data = getProject($temp_val);
                $value = '<a href="index.php?module=Projects&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }*/
        } elseif ($uitype == 935) {
            if($temp_val != '')
            {
                $data = getSerial($temp_val);
                $value ='<a href="index.php?module=Serial&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }
            else
            {
                $value = '';
            }
        } elseif ($uitype == 936) {
            if($temp_val != '')
            {
                $data = getSparepart($temp_val);
                $value ='<a href="index.php?module=Sparepart&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }
            else
            {
                $value = '';
            }
        } elseif ($uitype == 937) {
            if($temp_val != '')
            {
                $data = geterrorno($temp_val);
                $value ='<a href="index.php?module=Errors&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }
            else
            {
                $value = '';
            }
        } elseif ($uitype == 938) {
            if($temp_val != '')
            {
                $data = getjob($temp_val);
                $value ='<a href="index.php?module=Job&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }
            else
            {
                $value = '';
            }
        } elseif ($uitype == 939) {
            if ($temp_val != '') {
                $data = getcase($temp_val);
                $value = '<a href="index.php?module=HelpDesk&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }
        } elseif ($uitype == 910) {
            if($temp_val != ''){
                $data = getquestionnairetemplatename($temp_val);
                $value ='<a href="index.php?module=Questionnairetemplate&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif ($uitype == 940) {
            if ($temp_val != '' && $temp_val != '0') {
                $data = getOwnerName($temp_val);
                $value = '<a href="index.php?module=Users&action=DetailView&record=' . $temp_val . '">' . $data . '</a>';
            } else {
                $value = '';
            }
        } elseif ($uitype == 941) {
            if($temp_val != '')
            {
                $data = getplant($temp_val);
                $value ='<a href="index.php?module=Plant&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }
            else
            {
                $value = '';
            }      
        } elseif ($uitype == 943) {
            if($temp_val != ''){
                $data = getLeadName($temp_val);
                $value ='<a href="index.php?module=Leads&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif ($uitype == 944) {
            if($temp_val != ''){
                $data = getactivity($temp_val);
                $value ='<a href="index.php?module=Calendar&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 301) {
            if($temp_val != ''){
                $data = getdealname($temp_val);
                $value ='<a href="index.php?module=Deal&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 302) {
            if($temp_val != ''){
                $data = getcompetitorname($temp_val);
                $value ='<a href="index.php?module=Competitor&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 303) {
            if($temp_val != ''){
                $data = getpromotionvouchername($temp_val);
                $value ='<a href="index.php?module=Promotionvoucher&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 304){
            if($temp_val != ''){
                $data = getpromotion($temp_val);
                $value ='<a href="index.php?module=Promotion&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 305){
            if($temp_val != ''){
                $data = getsalesorderno($temp_val);
                $value ='<a href="index.php?module=Salesorder&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 306){
            if($temp_val != ''){
                $data = getpremuimproductno($temp_val);
                $value ='<a href="index.php?module=Premuimproduct&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 307){
            if($temp_val != ''){
                $data = getquotesno($temp_val);
                $value ='<a href="index.php?module=Quotes&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 308){
            if($temp_val != ''){
                $data = getservicerequest($temp_val);
                $value ='<a href="index.php?module=HelpDesk&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 309){
            if($temp_val != ''){
                $data = getticket($temp_val);
                $value ='<a href="index.php?module=Servicerequest&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 900){
            if($temp_val != ''){
                $data = getsalesinvoice($temp_val);
                $value ='<a href="index.php?module=Salesinvoice&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 946){
            if($temp_val != ''){
                $data = getquestionnairetemplatename($temp_val);
                $value ='<a href="index.php?module=Questionnairetemplate&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 947){
            if($temp_val != ''){
                $data = getquestionnairename($temp_val);
                $value ='<a href="index.php?module=Questionnaire&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }else{
                $value = '';
            }
        } elseif($uitype == 963){
            if($temp_val != '')
            {
                $data = getinspection_template_name($temp_val);
                $value ='<a href="index.php?module=Inspectiontemplate&action=DetailView&record='.$temp_val.'">'.$data.'</a>';
            }
            else
            {
                $value = '';
            }
        } elseif ($uitype == 61) {
            $attachmentid = $adb->query_result($adb->pquery("SELECT * FROM aicrm_seattachmentsrel WHERE crmid = ?", array($entity_id)), 0, 'attachmentsid');
            $value = '<a href = "index.php?module=uploads&action=downloadfile&return_module=' . $module . '&fileid=' . $attachmentid . '&filename=' . $temp_val . '">' . $temp_val . '</a>';
        } elseif ($uitype == 62) {
            $parentid = $adb->query_result($list_result, $list_result_count, "parent_id");
            $parenttype = $adb->query_result($list_result, $list_result_count, "parent_type");

            if ($parenttype == "Leads") {
                $tablename = "aicrm_leaddetails";
                $fieldname = "lastname";
                $idname = "leadid";
            }
            if ($parenttype == "LeadManagement") {
                $tablename = "aicrm_leadmanage";
                $fieldname = "lastname";
                $idname = "leadid";
            }
            if ($parenttype == "Accounts") {
                $tablename = "aicrm_account";
                $fieldname = "accountname";
                $idname = "accountid";
            }
            if ($parenttype == "Products") {
                $tablename = "aicrm_products";
                $fieldname = "productname";
                $idname = "productid";
            }
            if ($parenttype == "HelpDesk") {
                $tablename = "aicrm_troubletickets";
                $fieldname = "title";
                $idname = "ticketid";
            }

            if ($parentid != '') {
                $sql = "SELECT * FROM $tablename WHERE $idname = ?";
                $fieldvalue = $adb->query_result($adb->pquery($sql, array($parentid)), 0, $fieldname);
                $value = '<a href=index.php?module=' . $parenttype . '&action=DetailView&record=' . $parentid . '&parenttab=' . urlencode($tabname) . '>' . $fieldvalue . '</a>';
            } else
            $value = '';
        } elseif ($uitype == 66) {
            $parentid = $adb->query_result($list_result, $list_result_count, "parent_id");
            $parenttype = $adb->query_result($list_result, $list_result_count, "parent_type");

            if ($parenttype == "Leads") {
                $tablename = "aicrm_leaddetails";
                $fieldname = "lastname";
                $idname = "leadid";
            }
            if ($parenttype == "LeadManagement") {
                $tablename = "aicrm_leadmanage";
                $fieldname = "lastname";
                $idname = "leadid";
            }

            if ($parenttype == "Accounts") {
                $tablename = "aicrm_account";
                $fieldname = "accountname";
                $idname = "accountid";
            }
            if ($parenttype == "HelpDesk") {
                $tablename = "aicrm_troubletickets";
                $fieldname = "title";
                $idname = "ticketid";
            }
            if ($parentid != '') {
                $sql = "SELECT * FROM $tablename WHERE $idname = ?";
                $fieldvalue = $adb->query_result($adb->pquery($sql, array($parentid)), 0, $fieldname);

                $value = '<a href=index.php?module=' . $parenttype . '&action=DetailView&record=' . $parentid . '&parenttab=' . urlencode($tabname) . '>' . $fieldvalue . '</a>';
            } else
            $value = '';
        } elseif ($uitype == 67) {
            $parentid = $adb->query_result($list_result, $list_result_count, "parent_id");
            $parenttype = $adb->query_result($list_result, $list_result_count, "parent_type");

            if ($parenttype == "Leads") {
                $tablename = "aicrm_leaddetails";
                $fieldname = "lastname";
                $idname = "leadid";
            }
            if ($parenttype == "LeadManagement") {
                $tablename = "aicrm_leadmanage";
                $fieldname = "lastname";
                $idname = "leadid";
            }
            if ($parenttype == "Contacts") {
                $tablename = "aicrm_contactdetails";
                $fieldname = "contactname";
                $idname = "contactid";
            }
               $parenttype = $adb->query_result($list_result, $list_result_count, "parent_type");

            if ($parenttype == '' && $parentid != '')
                $parenttype = getSalesEntityType($parentid);

            if ($parenttype == "Contacts") {
                $tablename = "aicrm_contactdetails";
                $fieldname = "contactname";
                $idname = "contactid";
            }
            if ($parenttype == "Accounts") {
                $tablename = "aicrm_account";
                $fieldname = "accountname";
                $idname = "accountid";
            }
            if ($parentid != '') {
                $sql = "SELECT * FROM $tablename WHERE $idname = ?";
                $fieldvalue = $adb->query_result($adb->pquery($sql, array($parentid)), 0, $fieldname);

                $value = '<a href=index.php?module=' . $parenttype . '&action=DetailView&record=' . $parentid . '&parenttab=' . urlencode($tabname) . '>' . $fieldvalue . '</a>';
            } else
            $value = '';
        } elseif ($uitype == 78) {
            if ($temp_val != '') {

                $quote_name = getQuoteName($temp_val);
                $value = '<a href=index.php?module=Quotes&action=DetailView&record=' . $temp_val . '&parenttab=' . urlencode($tabname) . '>' . textlength_check($quote_name) . '</a>';
            } else
            $value = '';
        } elseif ($uitype == 98) {
            $value = '<a href="index.php?action=RoleDetailView&module=Settings&parenttab=Settings&roleid=' . $temp_val . '">' . textlength_check(getRoleName($temp_val)) . '</a>';
        } elseif ($uitype == 33) {
                //echo $fieldname; echo '<br>';
            $value = ($temp_val != "") ? str_ireplace(' |##| ', ', ', $temp_val) : "";
            if (!$is_admin && $value != '' && ( $fieldname != 'pjorder_employee' && $fieldname != 'approve_level1' && $fieldname != 'approve_level2' && $fieldname != 'approve_level3' && $fieldname != 'approve_level4' && $fieldname != 'related_sales_person' )) {
                $value = ($field_val != "") ? str_ireplace(' |##| ', ', ', $field_val) : "";
                if ($value != '') {
                    $value_arr = explode(',', trim($value));
                    $roleid = $current_user->roleid;
                    $subrole = getRoleSubordinates($roleid);
                    if (count($subrole) > 0) {
                        $roleids = $subrole;
                        array_push($roleids, $roleid);
                    } else {
                        $roleids = $roleid;
                    }
                    if (count($roleids) > 0) {
                        $pick_query = "select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where roleid in (" . generateQuestionMarks($roleids) . ") and picklistid in (select picklistid from aicrm_$fieldname) order by $fieldname asc";
                        $params = array($roleids);
                    } else {
                        $pick_query = "select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where picklistid in (select picklistid from aicrm_$fieldname) order by $fieldname asc";
                        $params = array();
                    }
                    $pickListResult = $adb->pquery($pick_query, $params);
                    $picklistval = Array();
                    for ($i = 0; $i < $adb->num_rows($pickListResult); $i++) {
                        $picklistarr[] = $adb->query_result($pickListResult, $i, $fieldname);
                    }
                    $value_temp = Array();
                    $string_temp = '';
                    $str_c = 0;
                    foreach ($value_arr as $ind => $val) {
                        $notaccess = '<font color="red">' . $app_strings['LBL_NOT_ACCESSIBLE'] . "</font>";
                        if (!$listview_max_textlength || !(strlen(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $string_temp)) > $listview_max_textlength)) {
                            $value_temp1 = (in_array(trim($val), $picklistarr)) ? $val : $notaccess;
                            if ($str_c != 0)
                                $string_temp .= ' , ';
                            $string_temp .= $value_temp1;
                            $str_c++;
                        } else
                        $string_temp .= '...';

                    }
                    $value = $string_temp;
                }
            }
        } elseif ($uitype == 85) {
            $value = ($temp_val != "") ? "<a href='skype:{$temp_val}?call'>{$temp_val}</a>" : "";
        } elseif ($uitype == 116) {
            $value = ($temp_val != "") ? getCurrencyName($temp_val) : "";
        } elseif ($uitype == 117) {
                // NOTE: Without symbol the value could be used for filtering/lookup hence avoiding the translation
            $value = ($temp_val != "") ? getCurrencyName($temp_val, false) : "";
        } elseif ($uitype == 26) {
            $sql = "select foldername from aicrm_attachmentsfolder where folderid = ?";
            $res = $adb->pquery($sql, array($temp_val));
            $foldername = $adb->query_result($res, 0, 'foldername');
            $value = $foldername;    

            if ($parentid != '') {
                $sql = "SELECT * FROM $tablename WHERE $idname = ?";
                $fieldvalue = $adb->query_result($adb->pquery($sql, array($parentid)), 0, $fieldname);

                $value = '<a href=index.php?module=' . $parenttype . '&action=DetailView&record=' . $parentid . '&parenttab=' . urlencode($tabname) . '>' . $fieldvalue . '</a>';
            } else
                $value = '';
        } elseif ($uitype == 68) {
            $parentid = $adb->query_result($list_result, $list_result_count, "parent_id");

        } //added for asterisk integration
        elseif ($uitype == 11) {
            $value = "<a href='javascript:;' onclick='startCall(&quot;$temp_val&quot;, &quot;$entity_id&quot;)'>" . $temp_val . "</a>";
        }
        //asterisk changes end here
        //Added for email status tracking
        elseif ($uitype == 25) {
            $contactid = $_REQUEST['record'];
            $emailid = $adb->query_result($list_result, $list_result_count, "activityid");
            $result = $adb->pquery("SELECT access_count FROM aicrm_email_track WHERE crmid=? AND mailid=?", array($contactid, $emailid));
            $value = $adb->query_result($result, 0, "access_count");
            if (!$value) {
                $value = 0;
            }
        } elseif ($uitype == 8) {
            if (!empty($temp_val)) {
                $temp_val = html_entity_decode($temp_val, ENT_QUOTES, $default_charset);
                $json = new Zend_Json();
                $value = vt_suppressHTMLTags(implode(',', $json->decode($temp_val)));
            }
        } //end email status tracking
        else {
            //echo $fieldname; echo " *----* " ; echo $focus->list_link_field; echo "<br>";

            if ($fieldname == $focus->list_link_field) {
                //echo $mode; exit;
                if ($mode == "search") {
                    // echo $popuptype; exit;
                    if ($popuptype == "specific" || $popuptype == "specific_ServiceRequest" || $popuptype == "toDospecific" || $popuptype == 'specific_case') {
                        if ($colname == "lastname" && $module == 'Contacts') {
                            $temp_val = getFullNameFromQResult($list_result, $list_result_count, "Contacts");
                        }

                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                        // echo $module; exit;

                        //Added to avoid the error when select SO from Invoice through AjaxEdit
                        if ($module == 'Serial'){
                            require_once('modules/Serial/Serial.php');
                            $serial_focus = new Serial();
                            $serial_focus->retrieve_entity_info($entity_id,"Serial");
                            $slashes_temp_val = popup_from_html($temp_val);
                            $module_return = !empty($_REQUEST['return_module']) ? $_REQUEST['return_module'] : '';
                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            if($serial_focus->column_fields['product_id']!="" and $serial_focus->column_fields['product_id']!="0"){
                                require_once('modules/Products/Products.php');
                                $product_focus = new Products();
                                $product_focus->retrieve_entity_info($serial_focus->column_fields['product_id'], "Products");
                                $productname = popup_decode_html($product_focus->column_fields['productname']);
                                $products_businessplusno = popup_decode_html($product_focus->column_fields['products_businessplusno']);
                            }else{
                                $productname = '';
                                $products_businessplusno = '';
                            }

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($serial_focus->column_fields['serial_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($serial_focus->column_fields['serial_name']);
                            $value .=  '", "'.popup_decode_html($serial_focus->column_fields['product_id']);
                            $value .=  '", "'.popup_decode_html($productname);
                            $value .=  '", "'.popup_decode_html($module_return);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Accounts'){

                            require_once('modules/Accounts/Accounts.php');
                            $acc_focus = new Accounts();
                            $acc_focus->retrieve_entity_info($entity_id,"Accounts");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            $module_return = !empty($_REQUEST['module_return']) ? $_REQUEST['module_return'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($slashes_temp_val)).'","'.$form;
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['accountname']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['mobile']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['email1']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['nametitle']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['firstname']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['lastname']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['idcardno']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['birthdate']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['gender']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['mobile2']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['email2']);
                            $value .=  '", "'.popup_decode_html($module_return);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['address1']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['street']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['subdistrict']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['district']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['province']);
                            $value .=  '", "'.popup_decode_html($acc_focus->column_fields['postalcode']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Leads'){

                            require_once('modules/Leads/Leads.php');
                            $leads_focus = new Leads();
                            $leads_focus->retrieve_entity_info($entity_id,"Leads");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            $module_return = !empty($_REQUEST['module_return']) ? $_REQUEST['module_return'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $full_name = $leads_focus->column_fields['firstname'].' '.$leads_focus->column_fields['lastname'];
                            
                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($slashes_temp_val)).'","'.$form;
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['salutationtype']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['firstname']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['lastname']);
                            $value .=  '", "'.popup_decode_html($full_name);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['mobile']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['email']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['idcardno']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['birthdate']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['gender']);
                            $value .=  '", "'.popup_decode_html($module_return);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['address1']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['lead_street']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['lead_subdistrinct']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['lead_district']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['lead_province']);
                            $value .=  '", "'.popup_decode_html($leads_focus->column_fields['lead_postalcode']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Plant'){

                            require_once('modules/Plant/Plant.php');
                            $plant_focus = new Plant();
                            $plant_focus->retrieve_entity_info($entity_id,"Plant");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($plant_focus->column_fields['plant_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($plant_focus->column_fields['plant_name']);
                            $value .=  '", "'.popup_decode_html($plant_focus->column_fields['vendor_name']);
                            $value .=  '", "'.popup_decode_html($plant_focus->column_fields['vendor_bank']);
                            $value .=  '", "'.popup_decode_html($plant_focus->column_fields['vendor_bank_account']);
                            $value .=  '", "'.popup_decode_html($plant_focus->column_fields['vendor_address']);
                            $value .=  '", "'.popup_decode_html($plant_focus->column_fields['plant_id']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Questionnaire'){

                            require_once('modules/Questionnaire/Questionnaire.php');
                            $ques_focus = new Questionnaire();
                            $ques_focus->retrieve_entity_info($entity_id,"Questionnaire");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($ques_focus->column_fields['questionnaire_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($ques_focus->column_fields['questionnaire_name']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Questionnairetemplate'){

                            require_once('modules/Questionnairetemplate/Questionnairetemplate.php');
                            $questionnaire_focus = new Questionnairetemplate();
                            $questionnaire_focus->retrieve_entity_info($entity_id,"Questionnairetemplate");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($questionnaire_focus->column_fields['questionnairetemplate_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($questionnaire_focus->column_fields['questionnairetemplate_name']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Promotion'){

                            require_once('modules/Promotion/Promotion.php');
                            $promotion_focus = new Promotion();
                            $promotion_focus->retrieve_entity_info($entity_id,"Promotion");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($promotion_focus->column_fields['promotion_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($promotion_focus->column_fields['promotion_name']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Promotionvoucher'){

                            require_once('modules/Promotionvoucher/Promotionvoucher.php');
                            $promotion_focus = new Promotionvoucher();
                            $promotion_focus->retrieve_entity_info($entity_id,"Promotionvoucher");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($promotion_focus->column_fields['promotionvoucher_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($promotion_focus->column_fields['promotionvoucher_name']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Competitor'){

                            require_once('modules/Competitor/Competitor.php');
                            $competitor_focus = new Competitor();
                            $competitor_focus->retrieve_entity_info($entity_id,"Competitor");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($competitor_focus->column_fields['competitor_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($competitor_focus->column_fields['competitor_name']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Deal'){

                            require_once('modules/Deal/Deal.php');
                            $deal_focus = new Deal();
                            $deal_focus->retrieve_entity_info($entity_id,"Deal");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($deal_focus->column_fields['deal_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($deal_focus->column_fields['deal_name']);
                            $value .=  '", "'.popup_decode_html($_REQUEST['module_return']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Calendar'){

                            require_once('modules/Calendar/Activity.php');
                            $activity_focus = new Activity();
                            $activity_focus->retrieve_entity_info($entity_id,"Calendar");
                            
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.$activity_focus->column_fields['salesvisit_name'].'","'.$form;
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Job' && isset($_REQUEST['module_return']) && $_REQUEST['module_return'] =='Calendar'){
                            require_once('modules/Job/Job.php');
                            $job_focus = new Job();
                            $job_focus->retrieve_entity_info($entity_id,"Job");
                            $slashes_temp_val = popup_from_html($temp_val);
                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            
                            if($job_focus->column_fields['contact_id'] != "" && $job_focus->column_fields['contact_id'] !="0" ){

                                $querystr = "SELECT aicrm_contactdetails.contactid,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as full_name , aicrm_contactdetails.position,aicrm_contactdetails.email,aicrm_contactdetails.mobile,aicrm_contactdetails.department FROM aicrm_contactdetails 
                                Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
                                WHERE aicrm_crmentity.deleted = 0 and aicrm_contactdetails.contactid = '".$job_focus->column_fields['contact_id']."' ";
                                //echo $querystr; exit;
                                $queryres = $adb->pquery($querystr, array());

                                $contactid = $adb->query_result($queryres, 0, 'contactid');
                                $contactname = $adb->query_result($queryres, 0, 'full_name');
                                $con_position = $adb->query_result($queryres, 0, 'position');
                                $email = $adb->query_result($queryres, 0, 'email');
                                $mobile = $adb->query_result($queryres, 0, 'mobile');
                                $con_department = $adb->query_result($queryres, 0, 'department');

                            }else{
                                $contactid =  '';
                                $contactname = '';
                                $con_position = '';
                                $email = '';
                                $mobile = '';
                                $con_department = '';
                            }

                            if($job_focus->column_fields['account_id'] != "" && $job_focus->column_fields['account_id'] != "0"){
                                $queryacc = "SELECT aicrm_account.accountid,aicrm_account.accountname 
                                FROM aicrm_account 
                                Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                                WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$job_focus->column_fields['account_id']."' ";
                                $query_acc = $adb->pquery($queryacc, array());
                                $accountid = $adb->query_result($query_acc, 0, 'accountid');
                                $accountname = $adb->query_result($query_acc, 0, 'accountname');

                            }else{
                                $accountid = '';
                                $accountname = '';
                            }

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_event("'.$entity_id.'", "'.nl2br(decode_html($slashes_temp_val)) . '","'.$form;
                            $value .=  '", "'.popup_decode_html($_REQUEST['module_return']);
                            $value .=  '", "'.popup_decode_html($contactid);
                            $value .=  '", "'.popup_decode_html($contactname);
                            $value .=  '", "'.popup_decode_html($con_position);
                            $value .=  '", "'.popup_decode_html($email);
                            $value .=  '", "'.popup_decode_html($mobile);
                            $value .=  '", "'.popup_decode_html($con_department);
                            $value .=  '", "'.popup_decode_html($accountid);
                            $value .=  '", "'.popup_decode_html($accountname);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';
                        }else if ($module == 'HelpDesk' ){

                            if(isset($_REQUEST['module_return']) && ($_REQUEST['module_return'] =='Calendar' || $_REQUEST['module_return'] =='Questionnaire')){
                                require_once('modules/HelpDesk/HelpDesk.php');
                                $help_focus = new HelpDesk();
                                $help_focus->retrieve_entity_info($entity_id,"HelpDesk");
                                $slashes_temp_val = popup_from_html($temp_val);
                                $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                                if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                                if($help_focus->column_fields['contact_id'] != "" && $help_focus->column_fields['contact_id'] !="0" ){

                                    $querystr = "SELECT aicrm_contactdetails.contactid,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as full_name , aicrm_contactdetails.position,aicrm_contactdetails.email,aicrm_contactdetails.mobile,aicrm_contactdetails.department FROM aicrm_contactdetails 
                                    Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
                                    WHERE aicrm_crmentity.deleted = 0 and aicrm_contactdetails.contactid = '".$help_focus->column_fields['contact_id']."' ";
                                    $queryres = $adb->pquery($querystr, array());

                                    $contactid = $adb->query_result($queryres, 0, 'contactid');
                                    $contactname = $adb->query_result($queryres, 0, 'full_name');
                                    $con_position = $adb->query_result($queryres, 0, 'position');
                                    $email = $adb->query_result($queryres, 0, 'email');
                                    $mobile = $adb->query_result($queryres, 0, 'mobile');
                                    $con_department = $adb->query_result($queryres, 0, 'department');

                                }else{
                                    $contactid =  '';
                                    $contactname = '';
                                    $con_position = '';
                                    $email = '';
                                    $mobile = '';
                                    $con_department = '';
                                }

                                if($help_focus->column_fields['account_id'] != "" && $help_focus->column_fields['account_id'] != "0"){
                                    $queryacc = "SELECT aicrm_account.accountid,aicrm_account.accountname 
                                    FROM aicrm_account 
                                    Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                                    WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$help_focus->column_fields['account_id']."' ";
                                    $query_acc = $adb->pquery($queryacc, array());
                                    $accountid = $adb->query_result($query_acc, 0, 'accountid');
                                    $accountname = $adb->query_result($query_acc, 0, 'accountname');

                                }else{
                                    $accountid = '';
                                    $accountname = '';
                                }

                                $title = $help_focus->column_fields['ticket_title'];

                                $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_event("'.$entity_id.'", "'.$title.'" ,"'.nl2br(decode_html($slashes_temp_val)) . '","'.$form;
                                $value .=  '", "'.popup_decode_html($_REQUEST['module_return']);
                                $value .=  '", "'.popup_decode_html($contactid);
                                $value .=  '", "'.popup_decode_html($contactname);
                                $value .=  '", "'.popup_decode_html($con_position);
                                $value .=  '", "'.popup_decode_html($email);
                                $value .=  '", "'.popup_decode_html($mobile);
                                $value .=  '", "'.popup_decode_html($con_department);
                                $value .=  '", "'.popup_decode_html($accountid);
                                $value .=  '", "'.popup_decode_html($accountname);
                                $value .='");\'>'.$temp_val;
                                $value .='</a>';

                            }else{

                                require_once('modules/HelpDesk/HelpDesk.php');
                                $help_focus = new HelpDesk();
                                $help_focus->retrieve_entity_info($entity_id,"HelpDesk");
                                $slashes_temp_val = popup_from_html($temp_val);
                                $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                                if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                                if($help_focus->column_fields['contact_id'] != "" && $help_focus->column_fields['contact_id'] !="0" ){

                                    $querystr = "SELECT aicrm_contactdetails.contactid,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as full_name , aicrm_contactdetails.email,aicrm_contactdetails.mobile ,aicrm_contactdetails.firstname ,aicrm_contactdetails.lastname 
                                    FROM aicrm_contactdetails 
                                    Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
                                    WHERE aicrm_crmentity.deleted = 0 and aicrm_contactdetails.contactid = '".$help_focus->column_fields['contact_id']."' ";
                                    $queryres = $adb->pquery($querystr, array());

                                    $contactid = $adb->query_result($queryres, 0, 'contactid');
                                    $contactname = $adb->query_result($queryres, 0, 'full_name');
                                    $firstname = $adb->query_result($queryres, 0, 'full_name');
                                    $lastname = $adb->query_result($queryres, 0, 'full_name');
                                    //$con_position = $adb->query_result($queryres, 0, 'con_position');
                                    $con_position = '';
                                    $email = $adb->query_result($queryres, 0, 'email');
                                    $mobile = $adb->query_result($queryres, 0, 'mobile');
                                    //$con_department = $adb->query_result($queryres, 0, 'con_department');
                                    $con_department = '';

                                }else{
                                    $contactid =  '';
                                    $contactname = '';
                                    $firstname = '';
                                    $lastname = '';
                                    $con_position = '';
                                    $email = '';
                                    $mobile = '';
                                    $con_department = '';
                                }

                                if($help_focus->column_fields['account_id'] != "" && $help_focus->column_fields['account_id'] != "0"){
                                    $queryacc = "SELECT aicrm_account.accountid,aicrm_account.accountname,aicrm_account.addressline1
                                    ,aicrm_account.subdistrict,aicrm_account.district,aicrm_account.province,aicrm_account.postalcode  
                                    FROM aicrm_account 
                                    Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                                    WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$help_focus->column_fields['account_id']."' ";
                                    //echo $queryacc; exit;
                                    $query_acc = $adb->pquery($queryacc, array());
                                    $accountid = $adb->query_result($query_acc, 0, 'accountid');
                                    $accountname = $adb->query_result($query_acc, 0, 'accountname');
                                    $addressline1 = $adb->query_result($query_acc, 0, 'addressline1');//เลขที่
                                    $addressline2 = $adb->query_result($query_acc, 0, 'villageno');//หมู่
                                    $buildings =$adb->query_result($query_acc, 0, 'village');//อาคาร/หมู่บ้าน
                                    $unitline1 = $adb->query_result($query_acc, 0, 'addressline');//ห้องเลขที่/ชั้นที่
                                    $alley = $adb->query_result($query_acc, 0, 'lane');//ตรอก/ซอย
                                    $accountroad = $adb->query_result($query_acc, 0, 'street');//ถนน
                                    $subdistrict = $adb->query_result($query_acc, 0, 'subdistrict');//อำเภอ
                                    $district = $adb->query_result($query_acc, 0, 'district');//เขต
                                    $province = $adb->query_result($query_acc, 0, 'province');//จังหวัด
                                    $postalcode = $adb->query_result($query_acc, 0, 'postalcode');//ไปรษณีย์

                                }else{
                                    $accountid = '';
                                    $accountname = '';
                                    $addressline1 = '';
                                    $addressline2 = '';
                                    $buildings = '';
                                    $unitline1 = '';
                                    $alley = '';
                                    $accountroad = '';
                                    $subdistrict = '';
                                    $district = '';
                                    $province = '';
                                    $postalcode = '';
                                }

                                $title = $help_focus->column_fields['ticket_title'];

                                $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'","'.$title.'","'.nl2br(decode_html($slashes_temp_val)) . '","'.$form;
                                $value .=  '", "'.popup_decode_html($_REQUEST['module_return']);
                                $value .=  '", "'.popup_decode_html($contactid);
                                $value .=  '", "'.popup_decode_html($contactname);
                                $value .=  '", "'.popup_decode_html($con_position);
                                $value .=  '", "'.popup_decode_html($email);
                                $value .=  '", "'.popup_decode_html($mobile);
                                $value .=  '", "'.popup_decode_html($con_department);
                                $value .=  '", "'.popup_decode_html($accountid);
                                $value .=  '", "'.popup_decode_html($accountname);

                                $value .=  '", "'.popup_decode_html($addressline1);
                                $value .=  '", "'.popup_decode_html($addressline2);
                                $value .=  '", "'.popup_decode_html($buildings);
                                $value .=  '", "'.popup_decode_html($unitline1);
                                $value .=  '", "'.popup_decode_html($alley);
                                $value .=  '", "'.popup_decode_html($accountroad);
                                $value .=  '", "'.popup_decode_html($subdistrict);
                                $value .=  '", "'.popup_decode_html($district);
                                $value .=  '", "'.popup_decode_html($province);
                                $value .=  '", "'.popup_decode_html($postalcode);
                                $value .=  '", "'.popup_decode_html($firstname);
                                $value .=  '", "'.popup_decode_html($lastname);

                                $value .='");\'>'.$temp_val;
                                $value .='</a>';

                            }

                        }else if ($module == 'Projects' && $_REQUEST['module_return'] =='Questionnaire'){

                            require_once('modules/Projects/Projects.php');
                            $project_focus = new Projects();
                            $project_focus->retrieve_entity_info($entity_id,"Projects");
                            $slashes_temp_val = popup_from_html($temp_val);
                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            if($project_focus->column_fields['contact_id'] != "" && $project_focus->column_fields['contact_id'] !="0" ){

                                $querystr = "SELECT aicrm_contactdetails.contactid,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as full_name , aicrm_contactdetails.email,aicrm_contactdetails.mobile FROM aicrm_contactdetails 
                                Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
                                WHERE aicrm_crmentity.deleted = 0 and aicrm_contactdetails.contactid = '".$project_focus->column_fields['contact_id']."' ";
                                $queryres = $adb->pquery($querystr, array());

                                $contactid = $adb->query_result($queryres, 0, 'contactid');
                                $contactname = $adb->query_result($queryres, 0, 'full_name');
                                //$con_position = $adb->query_result($queryres, 0, 'con_position');
                                $email = $adb->query_result($queryres, 0, 'email');
                                $mobile = $adb->query_result($queryres, 0, 'mobile');
                                //$con_department = $adb->query_result($queryres, 0, 'con_department');

                            }else{
                                $contactid =  '';
                                $contactname = '';
                                //$con_position = '';
                                $email = '';
                                $mobile = '';
                                //$con_department = '';
                            }

                            if($project_focus->column_fields['account_id'] != "" && $project_focus->column_fields['account_id'] != "0"){
                                $queryacc = "SELECT aicrm_account.accountid,aicrm_account.accountname 
                                FROM aicrm_account 
                                Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                                WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$project_focus->column_fields['account_id']."' ";
                                $query_acc = $adb->pquery($queryacc, array());
                                $accountid = $adb->query_result($query_acc, 0, 'accountid');
                                $accountname = $adb->query_result($query_acc, 0, 'accountname');

                            }else{
                                $accountid = '';
                                $accountname = '';
                            }

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_event("'.$entity_id.'", "'.nl2br(decode_html($slashes_temp_val)) . '","'.$form;
                            $value .=  '", "'.popup_decode_html($_REQUEST['module_return']);
                            $value .=  '", "'.popup_decode_html($contactid);
                            $value .=  '", "'.popup_decode_html($contactname);
                            //$value .=  '", "'.popup_decode_html($con_position);
                            $value .=  '", "'.popup_decode_html($email);
                            $value .=  '", "'.popup_decode_html($mobile);
                            //$value .=  '", "'.popup_decode_html($con_department);
                            $value .=  '", "'.popup_decode_html($accountid);
                            $value .=  '", "'.popup_decode_html($accountname);
                            $value .=  '", "'.$project_focus->column_fields['projects_no'];
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Projects' && $_REQUEST['module_return'] =='Calendar'){

                            require_once('modules/Projects/Projects.php');
                            $project_focus = new Projects();
                            $project_focus->retrieve_entity_info($entity_id,"Projects");
                            $slashes_temp_val = popup_from_html($temp_val);
                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            if($project_focus->column_fields['contact_id'] != "" && $project_focus->column_fields['contact_id'] !="0" ){

                                $querystr = "SELECT aicrm_contactdetails.contactid,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as full_name , aicrm_contactdetails.email,aicrm_contactdetails.mobile FROM aicrm_contactdetails 
                                Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
                                WHERE aicrm_crmentity.deleted = 0 and aicrm_contactdetails.contactid = '".$project_focus->column_fields['contact_id']."' ";
                                $queryres = $adb->pquery($querystr, array());

                                $contactid = $adb->query_result($queryres, 0, 'contactid');
                                $contactname = $adb->query_result($queryres, 0, 'full_name');
                                //$con_position = $adb->query_result($queryres, 0, 'con_position');
                                $email = $adb->query_result($queryres, 0, 'email');
                                $mobile = $adb->query_result($queryres, 0, 'mobile');
                                //$con_department = $adb->query_result($queryres, 0, 'con_department');

                            }else{
                                $contactid =  '';
                                $contactname = '';
                                //$con_position = '';
                                $email = '';
                                $mobile = '';
                                //$con_department = '';
                            }

                            if($project_focus->column_fields['account_id'] != "" && $project_focus->column_fields['account_id'] != "0"){
                                $queryacc = "SELECT aicrm_account.accountid,aicrm_account.accountname 
                                FROM aicrm_account 
                                Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                                WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$project_focus->column_fields['account_id']."' ";
                                $query_acc = $adb->pquery($queryacc, array());
                                $accountid = $adb->query_result($query_acc, 0, 'accountid');
                                $accountname = $adb->query_result($query_acc, 0, 'accountname');

                            }else{
                                $accountid = '';
                                $accountname = '';
                            }

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_event("'.$entity_id.'", "'.nl2br(decode_html($slashes_temp_val)) . '","'.$form;
                            $value .=  '", "'.popup_decode_html($_REQUEST['module_return']);
                            $value .=  '", "'.popup_decode_html($contactid);
                            $value .=  '", "'.popup_decode_html($contactname);
                            //$value .=  '", "'.popup_decode_html($con_position);
                            $value .=  '", "'.popup_decode_html($email);
                            $value .=  '", "'.popup_decode_html($mobile);
                            //$value .=  '", "'.popup_decode_html($con_department);
                            $value .=  '", "'.popup_decode_html($accountid);
                            $value .=  '", "'.popup_decode_html($accountname);
                            $value .=  '", "'.$project_focus->column_fields['projects_no'];
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        // }else if ($module == 'Projects' && $_REQUEST['module_return'] =='Quotes'){

                        //     require_once('modules/Projects/Projects.php');
                        //     $project_focus = new Projects();
                        //     $project_focus->retrieve_entity_info($entity_id,"Projects");
                        //     $slashes_temp_val = popup_from_html($temp_val);
                        //     $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                        //     if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                        //     if($project_focus->column_fields['contact_id'] != "" && $project_focus->column_fields['contact_id'] !="0" ){

                        //         $querystr = "SELECT aicrm_contactdetails.contactid,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as full_name , aicrm_contactdetails.email,aicrm_contactdetails.mobile FROM aicrm_contactdetails 
                        //         Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
                        //         WHERE aicrm_crmentity.deleted = 0 and aicrm_contactdetails.contactid = '".$project_focus->column_fields['contact_id']."' ";
                        //         $queryres = $adb->pquery($querystr, array());

                        //         $contactid = $adb->query_result($queryres, 0, 'contactid');
                        //         $contactname = $adb->query_result($queryres, 0, 'full_name');
                        //         //$con_position = $adb->query_result($queryres, 0, 'con_position');
                        //         $email = $adb->query_result($queryres, 0, 'email');
                        //         $mobile = $adb->query_result($queryres, 0, 'mobile');
                        //         //$con_department = $adb->query_result($queryres, 0, 'con_department');

                        //     }else{
                        //         $contactid =  '';
                        //         $contactname = '';
                        //         //$con_position = '';
                        //         $email = '';
                        //         $mobile = '';
                        //         //$con_department = '';
                        //     }

                        //     if($project_focus->column_fields['account_id'] != "" && $project_focus->column_fields['account_id'] != "0"){
                        //         $queryacc = "SELECT aicrm_account.accountid,aicrm_account.accountname 
                        //         FROM aicrm_account 
                        //         Inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                        //         WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$project_focus->column_fields['account_id']."' ";
                        //         $query_acc = $adb->pquery($queryacc, array());
                        //         $accountid = $adb->query_result($query_acc, 0, 'accountid');
                        //         $accountname = $adb->query_result($query_acc, 0, 'accountname');

                        //     }else{
                        //         $accountid = '';
                        //         $accountname = '';
                        //     }

                        //     $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_event("'.$entity_id.'", "'.nl2br(decode_html($slashes_temp_val)) . '","'.$form;
                        //     $value .=  '", "'.popup_decode_html($_REQUEST['module_return']);
                        //     $value .=  '", "'.popup_decode_html($contactid);
                        //     $value .=  '", "'.popup_decode_html($contactname);
                        //     //$value .=  '", "'.popup_decode_html($con_position);
                        //     $value .=  '", "'.popup_decode_html($email);
                        //     $value .=  '", "'.popup_decode_html($mobile);
                        //     //$value .=  '", "'.popup_decode_html($con_department);
                        //     $value .=  '", "'.popup_decode_html($accountid);
                        //     $value .=  '", "'.popup_decode_html($accountname);
                        //     $value .=  '", "'.$project_focus->column_fields['projects_no'];
                        //     $value .=  '", "'.$project_focus->column_fields['projectorder_status'];
                        //     $value .=  '", "'.popup_decode_html(date("d-m-Y", strtotime($project_focus->column_fields['project_s_date'])));
                        //     $value .=  '", "'.popup_decode_html(date("d-m-Y", strtotime($project_focus->column_fields['project_estimate_e_date'])));
                        //     $value .='");\'>'.$temp_val;
                        //     $value .='</a>';

                        }else if ($module == 'Sparepart'){

                            require_once('modules/Sparepart/Sparepart.php');
                            $sparepart_focus = new Sparepart();
                            $sparepart_focus->retrieve_entity_info($entity_id,"Sparepart");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($sparepart_focus->column_fields['sparepart_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($sparepart_focus->column_fields['sparepart_name']);
                                //$value .=  '", "'.popup_decode_html($sparepart_focus->column_fields['description']);
                            $value .=  '", "'.str_replace(array("\n", "\r"), '\n',popup_decode_html(decode_html(htmlspecialchars($sparepart_focus->column_fields['description']))));
                            $value .=  '", "'.popup_decode_html($sparepart_focus->column_fields['spare_part_no_accounting']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Errors'){
                            require_once('modules/Errors/Errors.php');
                            $errors_focus = new Errors();
                            $errors_focus->retrieve_entity_info($entity_id,"Errors");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($errors_focus->column_fields['errors_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($errors_focus->column_fields['errors_name']);
                                //$value .=  '", "'.popup_decode_html($errors_focus->column_fields['description']);
                            $value .=  '", "'.str_replace(array("\n", "\r"), '\n',popup_decode_html(decode_html(htmlspecialchars($errors_focus->column_fields['description']))));
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Quotes'){
                            require_once('modules/Quotes/Quotes.php');
                            $quotes_focus = new Quotes();
                            $quotes_focus->retrieve_entity_info($entity_id,"Quotes");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($slashes_temp_val)).'","'.$form;
                            $value .=  '", "'.popup_decode_html($quotes_focus->column_fields['quote_no']);
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Projects'){
                            require_once('modules/Projects/Projects.php');
                            $projects_focus = new Projects();
                            $projects_focus->retrieve_entity_info($entity_id,"Projects");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $querystr = "SELECT deal_no FROM `aicrm_deal` WHERE dealid = '".$projects_focus->column_fields['dealid']."' ";
                            $queryres = $adb->pquery($querystr, array());
                            $deal_no = $adb->query_result($queryres, 0, 'deal_no');

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html(str_replace("'", "", $slashes_temp_val))).'","'.$form;
                            $value .=  '", "'.popup_decode_html($projects_focus->column_fields['projects_no']);
							$value .=  '", "'.popup_decode_html(date("d-m-Y", strtotime($projects_focus->column_fields['project_open_date'])));
                            $value .=  '", "'.popup_decode_html(date("d-m-Y", strtotime($projects_focus->column_fields['project_estimate_e_date'])));
                            $value .=  '", "'.popup_decode_html($projects_focus->column_fields['dealid']);
                            $value .=  '", "'.popup_decode_html($deal_no);
                            $value .='");\'>'.$temp_val.$project_focus->column_fields['projects_no'];
                            $value .='</a>';
                            
                        }else if ($module == 'Service'){
                            require_once('modules/Service/Service.php');
                            $service_focus = new Service();
                            $service_focus->retrieve_entity_info($entity_id,"Service");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($service_focus->column_fields['service_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($service_focus->column_fields['service_name']);
                                //$value .=  '", "'.popup_decode_html($errors_focus->column_fields['description']);
                                //$value .=  '", "'.str_replace(array("\n", "\r"), '\n',popup_decode_html(decode_html(htmlspecialchars($errors_focus->column_fields['description']))));
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Servicerequest'){
                            require_once('modules/Servicerequest/Servicerequest.php');
                            $service_focus = new Servicerequest();
                            $service_focus->retrieve_entity_info($entity_id,"Servicerequest");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("'.$entity_id.'", "'.nl2br(decode_html($service_focus->column_fields['servicerequest_no'])).'","'.$form;
                            $value .=  '", "'.popup_decode_html($service_focus->column_fields['servicerequest_name']);
                                //$value .=  '", "'.popup_decode_html($errors_focus->column_fields['description']);
                                //$value .=  '", "'.str_replace(array("\n", "\r"), '\n',popup_decode_html(decode_html(htmlspecialchars($errors_focus->column_fields['description']))));
                            $value .='");\'>'.$temp_val;
                            $value .='</a>';

                        }else if ($module == 'Products') {
                            require_once('modules/Products/Products.php');
                            $acct_focus = new Products();
                            $acct_focus->retrieve_entity_info($entity_id, "Products");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if (!empty($form)) $form = htmlspecialchars($form, ENT_QUOTES, $default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("' . $entity_id . '", "' . nl2br(decode_html($acct_focus->column_fields['productname'])) . '","' . $form;
                            $value .= '", "' . popup_decode_html($acct_focus->column_fields['productstatus']);
                            $value .= '", "' . popup_decode_html($acct_focus->column_fields['productcategory']);
                            $value .= '", "' . popup_decode_html($acct_focus->column_fields['productdescription']);
                            $value .= '", "' . popup_decode_html($acct_focus->column_fields['unit']);
                            $value .= '", "' . popup_decode_html($acct_focus->column_fields['sellingprice']);
                            $value .= '", "' . popup_decode_html($acct_focus->column_fields['mat_gp3_desciption']);
                            $value .= '", "' . popup_decode_html($acct_focus->column_fields['mat_gp4_desciption']);
                            $value .= '", "' . popup_decode_html($_REQUEST["module_return"]);
                            $value .= '");\'>' . $temp_val;
                            $value .= '</a>';

                        } else if ($module == 'Inspectiontemplate') {

                            require_once('modules/Inspectiontemplate/Inspectiontemplate.php');
                            $ins_focus = new Inspectiontemplate();
                            $ins_focus->retrieve_entity_info($entity_id, "Inspectiontemplate");
                            $slashes_temp_val = popup_from_html($temp_val);

                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if (!empty($form)) $form = htmlspecialchars($form, ENT_QUOTES, $default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("' . $entity_id . '", "' . nl2br(decode_html($ins_focus->column_fields['inspectiontemplate_no'])) . '", "' . nl2br(decode_html($ins_focus->column_fields['inspectiontemplate_name'])) . '","' . $form;
                            $value .= '");\'>' . $temp_val;
                            $value .= '</a>';

                        } elseif ($module == 'Contacts') {
                            require_once('modules/Contacts/Contacts.php');
                            $cntct_focus = new Contacts();
                            $cntct_focus->retrieve_entity_info($entity_id, "Contacts");
                            $slashes_temp_val = popup_from_html($temp_val);
                            
                            ///Accounts
                            $accountid = popup_decode_html($cntct_focus->column_fields['account_id']);
                            if (isset($accountid) && $accountid != '0') {
                                require_once('modules/Accounts/Accounts.php');
                                $account_focus = new Accounts();
                                $account_focus->retrieve_entity_info($cntct_focus->column_fields['account_id'], "Accounts");
                                $account_id = popup_decode_html($cntct_focus->column_fields['account_id']); //Account ID
                                $account_name = popup_decode_html($account_focus->column_fields['accountname']); //Account Name
                                $account_mobile = popup_decode_html($account_focus->column_fields['mobile']); //Mobile
                                $account_email = popup_decode_html($account_focus->column_fields['email1']); //Email
                            } else {
                                $account_id = ''; //Account ID
                                $account_name = ''; //Account Name
                                $account_mobile = ''; //Mobile
                                $account_email = ''; //Email
                            }
                                
                            //ADDED TO CHECK THE FIELD PERMISSIONS FOR
                            $xyz = array('mailingstreet', 'mailingcity', 'mailingzip', 'mailingpobox', 'mailingcountry', 'mailingstate', 'otherstreet', 'othercity', 'otherzip', 'otherpobox', 'othercountry', 'otherstate');
                            for ($i = 0; $i < 12; $i++) {
                                if (getFieldVisibilityPermission($module, $current_user->id, $xyz[$i]) == '0') {
                                    $cntct_focus->column_fields[$xyz[$i]] = $cntct_focus->column_fields[$xyz[$i]];
                                } else{
                                    $cntct_focus->column_fields[$xyz[$i]] = '';
                                }
                            }
                            // For ToDo creation the underlying form is not named as EditView
                            //echo nl2br(decode_html($slashes_temp_val));
                            $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                            if (!empty($form)) $form = htmlspecialchars($form, ENT_QUOTES, $default_charset);
                            
                            $value = '<a href="javascript:window.close();" onclick=\'set_return_contact_address("' . $entity_id . '"
                            , "' . nl2br(decode_html($slashes_temp_val)) . '"
                            , "' . popup_decode_html($cntct_focus->column_fields['mobile']) . '"
                            , "' . popup_decode_html($cntct_focus->column_fields['email']) . '"
                            , "' . popup_decode_html($cntct_focus->column_fields['contactname']) . '"
                            , "' . $form . '"
                            , "' . $account_id . '"
                            , "' . $account_name . '"
                            , "' . $account_mobile . '"
                            , "' . $account_email .'" 
                            , "' . $_REQUEST['return_module'] . '"
                            , "' . popup_decode_html($cntct_focus->column_fields['line_id']) . '"
                            );\'>' . $temp_val . '</a>';

                        } else {

                            if ($popuptype == 'toDospecific') {
                                $value = '<a href="javascript:window.close();" onclick=\'set_return_toDospecific("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';
                            } elseif ($popuptype == 'specific_ServiceRequest') {
                                $value = '<a href="javascript:window.close();" onclick=\'set_return_specific1("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';
                            } elseif ($popuptype == 'specific_case') {
                                $value = '<a href="javascript:window.close();" onclick=\'set_return_case("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';
                            } else {
                                $value = '<a href="javascript:window.close();" onclick=\'set_return_specific("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';
                                $value .= '</a>';
                            }
                            
                        }

                } elseif ($popuptype == "detailview") {
                    if ($colname == "lastname" && ($module == 'Contacts' || $module == 'LeadManagement' || $module == 'SmartSms')) {
                        $temp_val = getFullNameFromQResult($list_result, $list_result_count, $module);
                    }

                    $slashes_temp_val = popup_from_html($temp_val);
                    $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                    $focus->record_id = $_REQUEST['recordid'];
                    if ($_REQUEST['return_module'] == "Calendar") {
                        $value = '<a href="javascript:window.close();" id="calendarCont' . $entity_id . '" LANGUAGE=javascript onclick=\'add_data_to_relatedlist_incal("' . $entity_id . '","' . decode_html($slashes_temp_val) . '");\'>' . $temp_val . '</a>';
                    } else {
                        $value = '<a href="javascript:window.close();" onclick=\'add_data_to_relatedlist("' . $entity_id . '","' . $focus->record_id . '","' . $module . '");\'>' . $temp_val . '</a>';
                    }
                } elseif ($popuptype == "formname_specific") {
                    $slashes_temp_val = popup_from_html($temp_val);
                    $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                    $value = '<a href="javascript:window.close();" onclick=\'set_return_formname_specific("' . $_REQUEST['form'] . '", "' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';
                    } //Quotes==========================================================================================================================
                    elseif ($popuptype == "inventory_prod") {

                        if ($module == "Contacts") {
                            if ($_REQUEST['curr_row'] != "") {
                                $row_id = $_REQUEST['curr_row'];
                                $_SESSION['curr_row'] = $_REQUEST['curr_row'];
                            } else {
                                if ($_SESSION['curr_row'] != "") {
                                    $row_id = $_SESSION['curr_row'];
                                } else {
                                    $row_id = 0;
                                }
                            }
                            //To get all the tax types and values and pass it to product details
                            $tax_str = '';
                            $tax_details = getAllTaxes();
                            for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                                $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                            }
                            $tax_str = trim($tax_str, ',');
                            $rate = $user_info['conv_rate'];
                            if (getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0') {
                                $unitprice = '';
                                if ($_REQUEST['currencyid'] != null) {
                                    $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id));
                                    $unitprice = $prod_prices[$entity_id];
                                }
                            } else {
                                $unit_price = '';
                            }
                            $sub_products = '';
                            $sub_prod = '';

                            $sub_prod_query = $adb->pquery("select
                              con.contactid,con.firstname,con.lastname,
                              acc.accountname
                              from aicrm_contactdetails con
                              left join aicrm_contactscf concf on con.contactid=concf.contactid
                              left join aicrm_crmentity crm on crm.crmid=con.contactid
                              left join aicrm_account acc on acc.accountid=con.accountid
                              where crm.deleted=0 and con.contactid=?", array($entity_id));

                            for ($i = 0; $i < $adb->num_rows($sub_prod_query); $i++) {
                                $id = $adb->query_result($sub_prod_query, $i, "contactid");
                                $str_sep = '';
                                if ($i > 0) $str_sep = ":";
                                $sub_products .= $str_sep . $id;
                                $sub_prod .= $adb->query_result($sub_prod_query, $i, "firstname") . " " . $adb->query_result($sub_prod_query, $i, "lastname");
                            }

                            $sub_det = $sub_products . "::" . str_replace(":", "<br>", $sub_prod);
                            $qty_stock = '';

                            $slashes_temp_val = popup_from_html($temp_val);
                            $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                            $description = $adb->query_result($sub_prod_query, $i, 'accountname');
                            $slashes_desc = htmlspecialchars($description, ENT_QUOTES, $default_charset);
                            $sql = "select
                            con.contactid,con.firstname,con.lastname,
                            acc.accountname
                            from aicrm_contactdetails con
                            left join aicrm_contactscf concf on con.contactid=concf.contactid
                            left join aicrm_crmentity crm on crm.crmid=con.contactid
                            left join aicrm_account acc on acc.accountid=con.accountid
                            where crm.deleted=0  and con.contactid=?";
                            $result = $adb->pquery($sql, array($entity_id));
                            $slashes_desc = $adb->query_result($result, 0, "accountname");

                            $tmp_arr = array("entityid" => $entity_id, "prodname" => "" . $sub_prod . "", "unitprice" => "$unitprice", "qtyinstk" => "$qty_stock", "taxstring" => "$tax_str", "rowid" => "$row_id", "desc" => "$slashes_desc", "subprod_ids" => "$sub_det");
                            require_once('include/Zend/Json.php');
                            $prod_arr = Zend_Json::encode($tmp_arr);
                            $sql = "select
                            crm.description as comment
                            from aicrm_crmentity crm
                            left join aicrm_products pro on pro.productid=crm.crmid   where crm.deleted='0' and pro.productid=?";
                            $result = $adb->pquery($sql, array($entity_id));
                            $comment = $adb->query_result($result, 0, "comment");

                            $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory("' . $entity_id . '", "' . $sub_prod . '", "' . $unitprice . '", "' . $qty_stock . '","' . $tax_str . '","' . $row_id . '","' . $slashes_desc . '","' . $sub_det . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                        } else if ($module == "Premium") {
                            if ($_REQUEST['curr_row'] != "") {
                                $row_id = $_REQUEST['curr_row'];
                                $_SESSION['curr_row'] = $_REQUEST['curr_row'];
                            } else {
                                if ($_SESSION['curr_row'] != "") {
                                    $row_id = $_SESSION['curr_row'];
                                } else {
                                    $row_id = 0;
                                }
                            }
                            //To get all the tax types and values and pass it to product details
                            $tax_str = '';
                            $tax_details = getAllTaxes();
                            for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                                $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                            }
                            $tax_str = trim($tax_str, ',');
                            $rate = $user_info['conv_rate'];
                            if (getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0') {
                                $unitprice = '';
                                if ($_REQUEST['currencyid'] != null) {
                                    $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id));
                                    $unitprice = $prod_prices[$entity_id];
                                }
                            } else {
                                $unit_price = '';
                            }
                            $sub_products = '';
                            $sub_prod = '';
                            $sub_prod_query = $adb->pquery("
                              select
                              aicrm_premiums.premiumid,aicrm_premiums.premium_name
                              from aicrm_premiums
                              left join aicrm_premiumscf concf on aicrm_premiums.premiumid=aicrm_premiumscf.premiumid
                              left join aicrm_crmentity crm on crm.crmid=aicrm_premiums.premiumid
                              where crm.deleted=0 and aicrm_premiums.premiumid=?", array($entity_id));
                            for ($i = 0; $i < $adb->num_rows($sub_prod_query); $i++) {
                                $id = $adb->query_result($sub_prod_query, $i, "premiumid");
                                $str_sep = '';
                                if ($i > 0) $str_sep = ":";
                                $sub_products .= $str_sep . $id;
                                $sub_prod .= $adb->query_result($sub_prod_query, $i, "premium_name");
                            }

                            $sub_det = $sub_products . "::" . str_replace(":", "<br>", $sub_prod);
                            $qty_stock = '';

                            $slashes_temp_val = popup_from_html($temp_val);
                            $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                            $description = decode_html_force($adb->query_result($list_result, $list_result_count, 'description'));
                            $slashes_desc = addslashes(htmlspecialchars($description, ENT_QUOTES, $default_charset));

                            $slashes_desc = str_replace(array("\r", "\n"), array('\r', '\n'), $slashes_desc);

                            $tmp_arr = array("entityid" => $entity_id, "prodname" => "" . stripslashes(nl2br($slashes_temp_val)) . "", "unitprice" => "$unitprice", "qtyinstk" => "$qty_stock", "taxstring" => "$tax_str", "rowid" => "$row_id", "desc" => "$slashes_desc", "subprod_ids" => "$sub_det");
                            require_once('include/Zend/Json.php');
                            $prod_arr = Zend_Json::encode($tmp_arr);
                            $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $unitprice . '", "' . $qty_stock . '","' . $tax_str . '","' . $row_id . '","' . $slashes_desc . '","' . $sub_det . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                        } else if ($module == "Accounts") {
                            if ($_REQUEST['curr_row'] != "") {
                                $row_id = $_REQUEST['curr_row'];
                                $_SESSION['curr_row'] = $_REQUEST['curr_row'];
                            } else {
                                if ($_SESSION['curr_row'] != "") {
                                    $row_id = $_SESSION['curr_row'];
                                } else {
                                    $row_id = 0;
                                }
                            }
                            //To get all the tax types and values and pass it to product details
                            $tax_str = '';
                            $tax_details = getAllTaxes();
                            for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                                $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                            }
                            $tax_str = trim($tax_str, ',');
                            $rate = $user_info['conv_rate'];
                            if (getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0') {
                                $unitprice = '';
                                if ($_REQUEST['currencyid'] != null) {
                                    $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id));
                                    $unitprice = $prod_prices[$entity_id];
                                }
                            } else {
                                $unit_price = '';
                            }
                            $sub_products = '';
                            $sub_prod = '';
                            $sub_prod_query = $adb->pquery("
                              select
                              aicrm_account.accountid,aicrm_account.accountname
                              from aicrm_account
                              left join aicrm_accountscf on aicrm_account.accountid=aicrm_accountscf.accountid
                              left join aicrm_crmentity crm on crm.crmid=aicrm_account.accountid
                              where crm.deleted=0 and aicrm_account.accountid=?", array($entity_id));
                            for ($i = 0; $i < $adb->num_rows($sub_prod_query); $i++) {
                                $id = $adb->query_result($sub_prod_query, $i, "accountid");
                                $str_sep = '';
                                if ($i > 0) $str_sep = ":";
                                $sub_products .= $str_sep . $id;
                                $sub_prod .= $adb->query_result($sub_prod_query, $i, "accountname");
                            }

                            $sub_det = $sub_products . "::" . str_replace(":", "<br>", $sub_prod);
                            $qty_stock = '';

                            $slashes_temp_val = popup_from_html($temp_val);
                            $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                            $description = decode_html_force($adb->query_result($list_result, $list_result_count, 'description'));
                            $slashes_desc = addslashes(htmlspecialchars($description, ENT_QUOTES, $default_charset));

                            $slashes_desc = str_replace(array("\r", "\n"), array('\r', '\n'), $slashes_desc);

                            $tmp_arr = array("entityid" => $entity_id, "prodname" => "" . stripslashes(nl2br($slashes_temp_val)) . "","rowid" => "$row_id");
                            require_once('include/Zend/Json.php');
                            $prod_arr = Zend_Json::encode($tmp_arr);
                            $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '","' . $row_id . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';
                        
                        } else if ($module == "Competitorproduct") {
                            if ($_REQUEST['curr_row'] != "") {
                                $row_id = $_REQUEST['curr_row'];
                                $_SESSION['curr_row'] = $_REQUEST['curr_row'];
                            } else {
                                if ($_SESSION['curr_row'] != "") {
                                    $row_id = $_SESSION['curr_row'];
                                } else {
                                    $row_id = 0;
                                }
                            }
                           
                            require_once('modules/Competitorproduct/Competitorproduct.php');
                            $comp_focus = new Competitorproduct();
                            $comp_focus->retrieve_entity_info($entity_id, "Competitorproduct");

                            $competitor_product_brand = $comp_focus->column_fields['competitor_product_brand'];
                            $competitor_product_group = $comp_focus->column_fields['competitor_product_group'];
                            $competitor_product_size = $comp_focus->column_fields['competitor_product_size'];
                            $competitor_product_thickness = $comp_focus->column_fields['competitor_product_thickness'];
                            $selling_price = $comp_focus->column_fields['selling_price'];

                            $slashes_temp_val = popup_from_html($temp_val);
                            $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                            $description = decode_html_force($adb->query_result($list_result, $list_result_count, 'description'));
                            $slashes_desc = addslashes(htmlspecialchars($description, ENT_QUOTES, $default_charset));

                            $slashes_desc = str_replace(array("\r", "\n"), array('\r', '\n'), $slashes_desc);
                            $tmp_arr = array("entityid" => $entity_id, "prodname" => "" . stripslashes(nl2br($slashes_temp_val)) . "","rowid" => "$row_id","competitor_product_brand" => $competitor_product_brand,"competitor_product_group" => $competitor_product_group,"competitor_product_size" => $competitor_product_size,"competitor_product_thickness" => $competitor_product_thickness,"selling_price" => $selling_price);
                            require_once('include/Zend/Json.php');
                            $prod_arr = Zend_Json::encode($tmp_arr);
                            $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '","' . $row_id . '","' . $competitor_product_brand . '","' . $competitor_product_group . '","' . $competitor_product_size . '","' . $competitor_product_thickness . '","' . $selling_price . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                        } else if ($module == "Projects") {
                            if ($_REQUEST['curr_row'] != "") {
                                $row_id = $_REQUEST['curr_row'];
                                $_SESSION['curr_row'] = $_REQUEST['curr_row'];
                            } else {
                                if ($_SESSION['curr_row'] != "") {
                                    $row_id = $_SESSION['curr_row'];
                                } else {
                                    $row_id = 0;
                                }
                            }
                            //To get all the tax types and values and pass it to product details
                            $tax_str = '';
                            $tax_details = getAllTaxes();
                            for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                                $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                            }
                            $tax_str = trim($tax_str, ',');
                            $rate = $user_info['conv_rate'];
                            if (getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0') {
                                $unitprice = '';
                                if ($_REQUEST['currencyid'] != null) {
                                    $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id));
                                    $unitprice = $prod_prices[$entity_id];
                                }
                            } else {
                                $unit_price = '';
                            }
                            $sub_products = '';
                            $sub_prod = '';
                            $sub_prod_query = $adb->pquery("
                              select
                              aicrm_projects.projectsid,aicrm_projects.projects_name
                              from aicrm_projects
                              left join aicrm_projectscf on aicrm_projects.projectsid=aicrm_projectscf.projectsid
                              left join aicrm_crmentity crm on crm.crmid=aicrm_projects.projectsid
                              where crm.deleted=0 and aicrm_projects.projectsid=?", array($entity_id));
                            for ($i = 0; $i < $adb->num_rows($sub_prod_query); $i++) {
                                $id = $adb->query_result($sub_prod_query, $i, "projectsid");
                                $str_sep = '';
                                if ($i > 0) $str_sep = ":";
                                $sub_products .= $str_sep . $id;
                                $sub_prod .= $adb->query_result($sub_prod_query, $i, "projects_name");
                            }

                            $sub_det = $sub_products . "::" . str_replace(":", "<br>", $sub_prod);
                            $qty_stock = '';
                            $return_module = $_REQUEST['return_module'];
                            
                            $slashes_temp_val = popup_from_html($temp_val);
                            $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                            $description = decode_html_force($adb->query_result($list_result, $list_result_count, 'description'));
                            $slashes_desc = addslashes(htmlspecialchars($description, ENT_QUOTES, $default_charset));

                            $slashes_desc = str_replace(array("\r", "\n"), array('\r', '\n'), $slashes_desc);

                            $assignto = getUserFullName($adb->query_result($list_result, $list_result_count, 'smownerid'));
                            // echo $assignto; exit;
                            $tmp_arr = array("entityid" => $entity_id, "prodname" => "" . stripslashes(nl2br($slashes_temp_val)) . "","rowid" => "$row_id");
                            require_once('include/Zend/Json.php');
                            $prod_arr = Zend_Json::encode($tmp_arr);
                            $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventoryrel("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '","' . $row_id . '","' . $return_module . '","' . $assignto . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';
                        } else {

                            $row_id = $_REQUEST['curr_row'];
                            //To get all the tax types and values and pass it to product details
                            $tax_str = '';
                            $tax_details = getAllTaxes();
                            for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                                $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                            }
                            $tax_str = trim($tax_str, ',');
                            $rate = $user_info['conv_rate'];
                            if (getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0') {
                                $unitprice = $adb->query_result($list_result, $list_result_count, 'unit_price');
                                if ($_REQUEST['currencyid'] != null) {
                                    $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id));
                                    $unitprice = $prod_prices[$entity_id];
                                }
                            } else {
                                $unit_price = '';
                            }
                            $sub_products = '';
                            $sub_prod = '';

                            $sub_prod_query = $adb->pquery("SELECT aicrm_products.productid,aicrm_products.productname,aicrm_products.qtyinstock,aicrm_products.products_businessplusno,aicrm_crmentity.description from aicrm_products INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_products.productid WHERE aicrm_products.productid=? ", array($entity_id));

                            for ($i = 0; $i < $adb->num_rows($sub_prod_query); $i++) {
                                //$sub_prod=array();
                                $id = $adb->query_result($sub_prod_query, $i, "productid");
                                $str_sep = '';
                                if ($i > 0) $str_sep = ":";
                                $sub_products .= $str_sep . $id;
                                $sub_prod .= $str_sep . " - " . $adb->query_result($sub_prod_query, $i, "productname");
                            }

                            $return_module = $_REQUEST['return_module'];
                            $relmod_id = $_REQUEST['relmod_id'];
                            $parent_module = $_REQUEST['parent_module'];

                            $sub_prod = str_replace('&quot;', '\&quot;', $sub_prod);
                            $sub_det = $sub_products . "::" . str_replace(":", "", $sub_prod);
                            $qty_stock = $adb->query_result($list_result, $list_result_count, 'qtyinstock');
                            $unitprice = $adb->query_result($list_result, $list_result_count, 'unit_price');
                            $listprice_inc = $adb->query_result($list_result, $list_result_count, 'pro_priceinclude');
                            $pack_size = $adb->query_result($list_result, $list_result_count, 'pack_size');
                            $test_box = $adb->query_result($list_result, $list_result_count, 'test_box');
                            $uom = $adb->query_result($list_result, $list_result_count, 'usageunit');
                            $productname = $adb->query_result($list_result, $list_result_count, 'productname');
                            $productcode = $adb->query_result($list_result, $list_result_count, 'productcode');
                            $products_businessplusno = $adb->query_result($list_result, $list_result_count, 'products_businessplusno');

                            $product_code = $adb->query_result($list_result, $list_result_count, 'product_code');
                            $unit = $adb->query_result($list_result, $list_result_count, 'unit');
                            $sellingprice = $adb->query_result($list_result, $list_result_count, 'price_per_box');
                            $productstatus = $adb->query_result($list_result, $list_result_count, 'productstatus');
                            $productcategory = $adb->query_result($list_result, $list_result_count, 'productcategory');
                            $productsubcategory = $adb->query_result($list_result, $list_result_count, 'productsubcategory');
                            $stockavailable = $adb->query_result($list_result, $list_result_count, 'stockavailable');

                            $mat_gp1_desciption = $adb->query_result($list_result, $list_result_count, 'mat_gp1_desciption');//MAT.GP1 Desciption
                            $mat_gp3_desciption = $adb->query_result($list_result, $list_result_count, 'mat_gp3_desciption');//MAT.GP3 Desciption
                            $mat_gp4_desciption = $adb->query_result($list_result, $list_result_count, 'mat_gp3_desciption');//MAT.GP4 Desciption
                            $piece_per_carton = $adb->query_result($list_result, $list_result_count, 'piece_per_carton');//จำนวนแผ่น/กล่อง
                            $squaremeters_per_carton = $adb->query_result($list_result, $list_result_count, 'squaremeters_per_carton');//จำนวนตร.ม./กล่อง
                            $price_per_piece = $adb->query_result($list_result, $list_result_count, 'price_per_piece');//ราคา/แผ่น
                            $price_per_squaremeter = $adb->query_result($list_result, $list_result_count, 'price_per_squaremeter');//ราคา/ตร.ม.
                            
                            $component_surface_finish = $adb->query_result($list_result, $list_result_count, 'component_surface_finish');
                            $component_size = $adb->query_result($list_result, $list_result_count, 'component_size');
                            $component_surface_thinkness = $adb->query_result($list_result, $list_result_count, 'component_surface_thinkness');
                            $product_unit = $adb->query_result($list_result, $list_result_count, 'product_unit');
                            
                            $um_coversion_m2_pcs = $adb->query_result($list_result, $list_result_count, 'um_coversion_m2_pcs');//UM Coversion (PCS/CTN)

                            $productname = str_replace('&quot;', '\&quot;', $productname);
                            $qty = "";
                            $pro_type = "";
                            $promotion_id = "";
                            $slashes_temp_val = popup_from_html($temp_val);
                            $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                            $description = decode_html_force($adb->query_result($list_result, $list_result_count, 'description'));
                            $slashes_desc = addslashes(htmlspecialchars($description, ENT_QUOTES, $default_charset));

                            $productname = str_replace(array("\r", "\n"), array(''), $productname);
                            $sub_det = str_replace(array("\r", "\n"), array(''), $sub_det);
                            $slashes_desc = str_replace(array("\r", "\n"), array('\r', '\n'), $slashes_desc);

                            $tmp_arr = array("entityid" => $entity_id, "prod_code" => "" . nl2br($slashes_temp_val) . "", "prodname" => "$productname", "unitprice" => "$unitprice", "qtyinstk" => "$qty_stock", "taxstring" => "$tax_str", "rowid" => "$row_id", "desc" => "$slashes_desc", "subprod_ids" => "$sub_det", "pack_size" => "$pack_size", "test_box" => "$test_box", "uom" => "$uom", "test_box" => "$test_box", "qty" => "$qty", "pro_type" => "$pro_type", "promotion_id" => "$promotion_id", "return_module" => "$return_module", "relmod_id" => "$relmod_id", "parent_module" => "$parent_module", "productcode" => "$productcode", "listprice_inc" => "$listprice_inc", "productstatus" => "$productstatus", "product_code" => "$product_code", "unit" => "$unit", "sellingprice" => "$sellingprice", "productcategory" => "$productcategory", "stockavailable" => "$stockavailable", "products_businessplusno" => "$products_businessplusno", "mat_gp1_desciption" => "$mat_gp1_desciption" , "mat_gp3_desciption" => "$mat_gp3_desciption", "mat_gp4_desciption" => "$mat_gp4_desciption", "piece_per_carton" => "$piece_per_carton", "squaremeters_per_carton" => "$squaremeters_per_carton", "price_per_piece" => "$price_per_piece", "price_per_squaremeter" => "$price_per_squaremeter" ,"productsubcategory" => "$productsubcategory","um_coversion_m2_pcs" => "$um_coversion_m2_pcs","component_surface_finish" => "$component_surface_finish","component_size" => "$component_size","component_surface_thinkness" => "$component_surface_thinkness","product_unit" => "$product_unit");
                            $prod_arr = Zend_Json::encode($tmp_arr);

                            $countdata_listprice = 1;
                            //

                            /*if ($return_module == "Quotes") {
                                $accountid = $relmod_id;
                                if ($accountid != "") {
                                    $sql = "select aicrm_account.accounttype
                                   from aicrm_account
                                   inner join aicrm_accountscf on aicrm_account.accountid = aicrm_accountscf.accountid
                                   inner join aicrm_crmentity on aicrm_account.accountid = aicrm_crmentity.crmid
                                   where aicrm_account.accountid = ?
                                   and  aicrm_crmentity.deleted = 0 ";
                                    $result_account = $adb->pquery($sql, array($accountid));
                                    $accounttype = $adb->query_result($result_account, 0, "accounttype");
                                    $date = date('Y-m-d');

                                    $sql = "(
                                    select aicrm_pricelists.pricelist_name
                                    ,aicrm_pricelists.account_type as price_accounttype
                                    ,'accountid' as price_type
                                    ,DATE_FORMAT(aicrm_pricelists.pricelist_startdate , '%d-%m-%Y') as startdate
                                    ,DATE_FORMAT(aicrm_pricelists.pricelist_enddate , '%d-%m-%Y') as enddate    
                                    ,aicrm_inventoryproductrel.productid
                                    ,ifnull(aicrm_inventoryproductrel.listprice,'0') as listprice
                                    ,ifnull(aicrm_inventoryproductrel.listprice_inc,'0') as listprice_inc
                                    ,'' as products_businessplusno
                                    from aicrm_pricelists
                                    inner join aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
                                    inner join aicrm_pricelistscf on aicrm_pricelists.pricelistid = aicrm_pricelistscf.pricelistid
                                    left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id = aicrm_pricelists.pricelistid
                                    left join aicrm_pricelist_accountsrel ON aicrm_pricelist_accountsrel.pricelistid=aicrm_pricelists.pricelistid
                                    where aicrm_crmentity.deleted = 0
                                    and aicrm_pricelists.pricelist_startdate <= '" . $date . "'
                                    and aicrm_pricelists.pricelist_enddate >= '" . $date . "'
                                    and aicrm_inventoryproductrel.productid = '" . $entity_id . "'
                                    and aicrm_pricelist_accountsrel.accountid = '" . $accountid . "'
                                )UNION
                                (
                                    select aicrm_pricelists.pricelist_name
                                    ,aicrm_pricelists.account_type as price_accounttype
                                    ,'accounttype'  as price_type   
                                    ,DATE_FORMAT(aicrm_pricelists.pricelist_startdate , '%d-%m-%Y') as startdate    
                                    ,DATE_FORMAT(aicrm_pricelists.pricelist_enddate , '%d-%m-%Y') as enddate            
                                    ,aicrm_inventoryproductrel.productid,ifnull(aicrm_inventoryproductrel.listprice,'0') as listprice
                                    ,ifnull(aicrm_inventoryproductrel.listprice_inc,'0') as listprice_inc
                                    ,'' as products_businessplusno
                                    from aicrm_pricelists
                                    inner join aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
                                    inner join aicrm_pricelistscf on aicrm_pricelists.pricelistid = aicrm_pricelistscf.pricelistid
                                    left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id = aicrm_pricelists.pricelistid
                                    where aicrm_crmentity.deleted = 0
                                    and aicrm_pricelists.pricelist_startdate <= '" . $date . "'
                                    and aicrm_pricelists.pricelist_enddate >= '" . $date . "'
                                    and aicrm_inventoryproductrel.productid = '" . $entity_id . "'
                                    and aicrm_pricelists.account_type = '" . $accounttype . "'
                                    order by aicrm_pricelists.pricelist_enddate desc
                                ) UNION
                            
                                (
                                    select 'Standard Price' as pricelist_name
                                    ,'' as price_accounttype
                                    ,'unite price'  as  price_type  
                                    ,'' as startdate    
                                    ,'' as enddate          
                                    ,aicrm_products.productid,aicrm_products.unit_price as listprice
                                    ,aicrm_products.pro_priceinclude as listprice_inc
                                    ,aicrm_products.products_businessplusno
                                    from aicrm_products
                                    inner join aicrm_productcf on aicrm_products.productid = aicrm_productcf.productid
                                    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
                                    where aicrm_products.productid ='" . $entity_id . "'
                                )
                                ";
                                echo $sql; exit;
                                    $result_product = $adb->pquery($sql, array());
                                    $unitprice = $adb->query_result($result_product, 0, "listprice");
                                    $listprice_inc = $adb->query_result($result_product, 0, "listprice_inc");
                                    $products_businessplusno = $adb->query_result($result_product, 0, "products_businessplusno");
                                    $countdata_listprice = $adb->num_rows($result_product);
                                    if ($countdata_listprice > 1) {

                                        $a_data["productid"] = $entity_id;
                                        $a_data["accountid"] = $accountid;
                                        $a_data["accounttype"] = $accounttype;
                                        $s_data = json_encode($a_data);
                                        $s_data = str_replace('"', "'", $s_data);

                                    }

                                }

                            }*/

                            $pricetype = $_REQUEST['pricetype'];
                            if ($countdata_listprice > 1) {
                                $value = '<a href="javascript:void(0);" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_newpopup("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $productname . '", "' . $unitprice . '", "' . $qty_stock . '","' . $tax_str . '","' . $row_id . '","' . nl2br($slashes_desc) . '","' . $sub_det . '","' . $pack_size . '","' . $test_box . '","' . $uom . '","' . $qty . '","' . $pro_type . '","' . $promotion_id . '" ,"' . $return_module . '" ,"' . $relmod_id . '" ,"' . $parent_module . '" ,"' . $productcode . '", "' . $listprice_inc . '", "' . $pricetype . '", "' . $products_businessplusno . '");\' data-pricelist="' . $s_data . '" >' . $temp_val . '</a>';

                            } else {

                                if ($return_module == "Order") {

                                    $zone = $adb->query_result($list_result, $list_result_count, 'zone');
                                    $truck_size = $adb->query_result($list_result, $list_result_count, 'truck_size');
                                    $unit = $adb->query_result($list_result, $list_result_count, 'unit');
                                    $min = $adb->query_result($list_result, $list_result_count, 'min');
                                    $dlv_c = $adb->query_result($list_result, $list_result_count, 'dlv_c');
                                    $dlv_c_vat = $adb->query_result($list_result, $list_result_count, 'dlv_c_vat');
                                    $dlv_p_vat = $adb->query_result($list_result, $list_result_count, 'dlv_p_vat');
                                    $lp = $adb->query_result($list_result, $list_result_count, 'lp');
                                    $lp_disc = $adb->query_result($list_result, $list_result_count, 'lp_disc');
                                    $c_cost = $adb->query_result($list_result, $list_result_count, 'c_cost');
                                    $c_price_vat = $adb->query_result($list_result, $list_result_count, 'c_price_vat');
                                    $c_cost_vat = $adb->query_result($list_result, $list_result_count, 'c_cost_vat');

                                    $value = '<a href="javascript:window.close();" id=\'popup_product_'.$entity_id.'\' onclick=\'set_return_inventory_order("'.$entity_id.'","'. nl2br($slashes_temp_val).'","'.$productname.'","'.$unitprice.'","'.$return_module.'","'.$relmod_id.'","'.$parent_module.'","'.$pricetype.'","'.$zone.'","'.$truck_size.'","'.$unit.'","'.$min.'","'.$dlv_c.'","'.$dlv_c_vat.'","'.$dlv_p_vat.'","'.$lp.'","'.$lp_disc.'","'.$c_cost.'","'.$c_price_vat.'","'.$c_cost_vat.'" );\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                                }else if ($return_module == "Quotes") {

                                    $accountid = $_REQUEST['relmod_id'];
                                    $projectsid = $_REQUEST['projectsid'];
                                    $pricetype = $_REQUEST['pricetype'];

                                    if($accountid != '' && $projectsid != ''){
                                        $sql = "SELECT
                                        aicrm_inventorypricelist.selling_price,
                                        aicrm_inventorypricelist.selling_price_inc,
                                        aicrm_inventorypricelist.product_finish,
                                        aicrm_inventorypricelist.product_size_mm,
                                        aicrm_inventorypricelist.product_thinkness,
                                        aicrm_inventorypricelist.product_unit,
                                        aicrm_inventorypricelist.product_cost_avg
                                        FROM
                                        aicrm_pricelists
                                        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
                                        INNER JOIN aicrm_pricelistscf ON aicrm_pricelists.pricelistid = aicrm_pricelistscf.pricelistid
                                        LEFT JOIN aicrm_inventorypricelist ON aicrm_inventorypricelist.id = aicrm_pricelists.pricelistid 
                                        WHERE
                                        aicrm_crmentity.deleted = 0
                                        AND aicrm_pricelists.pricelist_enddate >= '".date('Y-m-d')."'
                                        AND aicrm_pricelists.accountid = '".$accountid."'
                                        AND aicrm_pricelists.projectsid = '".$projectsid."'
                                        AND aicrm_inventorypricelist.productid = '".$entity_id."'
                                        LIMIT 1";
                                        // echo $sql.";<br><br>";
                                        
                                        $result_pricelists = $adb->pquery($sql, array());
                                        $countdata_listprice = $adb->num_rows($result_pricelists);
                                        // echo $countdata_listprice."<br><br>";
                                        if($countdata_listprice>0){
                                            if($pricetype == "Include Vat"){
                                                $sellingprice = $adb->query_result($result_pricelists,0,"selling_price_inc");
                                            }else{
                                                $sellingprice = $adb->query_result($result_pricelists,0,"selling_price");
                                            }
                                            $product_finish = $adb->query_result($result_pricelists,0,"product_finish");//ชนิดผิว
                                            $product_size_mm = $adb->query_result($result_pricelists,0,"product_size_mm");//ขนาด (มม.)
                                            $product_thinkness = $adb->query_result($result_pricelists,0,"product_thinkness");//ความหนา (มม.)
                                            $product_unit = $adb->query_result($result_pricelists,0,"product_unit");//หน่วยนับ
                                            $product_cost_avg = $adb->query_result($result_pricelists,0,"product_cost_avg");//รวมต้นทุนจริงเฉลี่ย
                                            
                                        }else{
                                            $sellingprice = $adb->query_result($list_result, $list_result_count, 'selling_price');
                                            $product_finish = $adb->query_result($list_result, $list_result_count, 'product_finish');//ชนิดผิว
                                            $product_size_mm = $adb->query_result($list_result, $list_result_count, 'product_size_mm');//ขนาด (มม.)
                                            $product_thinkness = $adb->query_result($list_result, $list_result_count, 'product_thinkness');//ความหนา (มม.)
                                            $product_unit = $adb->query_result($list_result, $list_result_count, 'unit');//หน่วยนับ
                                            $product_cost_avg = $adb->query_result($list_result, $list_result_count, 'product_cost_avg');//รวมต้นทุนจริงเฉลี่ย
                                            
                                        }
                                    }else{
                                        $sellingprice = $adb->query_result($list_result, $list_result_count, 'selling_price');
                                        $product_finish = $adb->query_result($list_result, $list_result_count, 'product_finish');//ชนิดผิว
                                        $product_size_mm = $adb->query_result($list_result, $list_result_count, 'product_size_mm');//ขนาด (มม.)
                                        $product_thinkness = $adb->query_result($list_result, $list_result_count, 'product_thinkness');//ความหนา (มม.)
                                        $product_unit = $adb->query_result($list_result, $list_result_count, 'unit');//หน่วยนับ
                                        $product_cost_avg = $adb->query_result($list_result, $list_result_count, 'product_cost_avg');//รวมต้นทุนจริงเฉลี่ย
                                    }

                                    $productcategory = $adb->query_result($list_result, $list_result_count, 'productcategory');
                                    $productstatus = $adb->query_result($list_result, $list_result_count, 'productstatus');

                                    $unit = $adb->query_result($list_result, $list_result_count, 'unit');

                                    $mat_gp1_desciption = $adb->query_result($list_result, $list_result_count, 'mat_gp1_desciption');//MAT.GP1 Desciption
                                    $mat_gp1_desciption = htmlspecialchars($mat_gp1_desciption, ENT_QUOTES, $default_charset);

                                    $mat_gp3_desciption = $adb->query_result($list_result, $list_result_count, 'mat_gp3_desciption');//MAT.GP3 Desciption
                                    $mat_gp3_desciption = htmlspecialchars($mat_gp3_desciption, ENT_QUOTES, $default_charset);

                                    $mat_gp4_desciption = $adb->query_result($list_result, $list_result_count, 'mat_gp4_desciption');//MAT.GP4 Desciption
                                    $mat_gp4_desciption = htmlspecialchars($mat_gp4_desciption, ENT_QUOTES, $default_charset);

                                    $piece_per_carton = $adb->query_result($list_result, $list_result_count, 'piece_per_carton');//จำนวนแผ่น/กล่อง
                                    $squaremeters_per_carton = $adb->query_result($list_result, $list_result_count, 'squaremeters_per_carton');//จำนวนตร.ม./กล่อง
                                    $price_per_piece = $adb->query_result($list_result, $list_result_count, 'price_per_piece');//ราคา/แผ่น
                                    $price_per_squaremeter = $adb->query_result($list_result, $list_result_count, 'price_per_squaremeter');//ราคา/ตร.ม.
                                    $um_coversion_m2_pcs = $adb->query_result($list_result, $list_result_count, 'um_coversion_m2_pcs');//UM Coversion (PCS/CTN)

                                    $package_size_sheet_per_box = $adb->query_result($list_result, $list_result_count, 'package_size_sheet_per_box');//ขนาดบรรจุ (แผ่น/กล่อง)
                                    $package_size_sqm_per_box = $adb->query_result($list_result, $list_result_count, 'package_size_sqm_per_box');//ขนาดบรรจุ (ตรม./กล่อง)

                                    $material_code = $adb->query_result($list_result, $list_result_count, 'material_code');

                                    if($material_code != ''){
                                        $productname = $material_code." : ".$productname;
                                    }

                                    $sales_unit = $adb->query_result($list_result, $list_result_count, 'unit');

                                    $description = $adb->query_result($list_result, $list_result_count, 'description');
                                    $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_quotation("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $productname . '","' . $tax_str . '","' . $row_id . '","' . nl2br($slashes_desc) . '", "'.$productcategory.'", "'.$productstatus.'", "'.$sellingprice.'", "'.$unit.'" ,"' . $return_module . '","' . $mat_gp1_desciption . '","' . $mat_gp3_desciption . '","' . $mat_gp4_desciption . '","' . $piece_per_carton . '","' . $squaremeters_per_carton . '","' . $price_per_piece . '","' . $price_per_squaremeter . '","' . $um_coversion_m2_pcs . '"
                                    
                                    ,"'.$product_finish.'"
                                    ,"'.$product_size_mm.'"
                                    ,"'.$product_thinkness.'"
                                    ,"'.$product_unit.'"
                                    ,"'.$product_cost_avg.'"
                                    ,"'.$description.'"

                                    ,"'.$package_size_sheet_per_box.'"
                                    ,"'.$package_size_sqm_per_box.'"
                                    ,"'.$sales_unit.'"
                                );\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';
                                }else if ($return_module == "Sparepart") {
                                   require_once('modules/Sparepart/Sparepart.php');
                                   $sparepart_focus = new Sparepart();
                                   $sparepart_focus->retrieve_entity_info($entity_id,"Sparepart");
                                   // $slashes_temp_val = popup_from_html($temp_val);
                                   // $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                                   // if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);xdsdfsddfss
                                   $value = '<a href="javascript:window.close();" onclick=\'set_return_inventory_sparepart("'.$entity_id.'", "'.nl2br(decode_html($sparepart_focus->column_fields['sparepart_no'])).'"';
                                   $value .=  ', "'.popup_decode_html($sparepart_focus->column_fields['sparepart_name']).'"';
                                   $value .=  ', "'.$sparepart_focus->column_fields['sparepart_cost'].'"';
                                   $value .=  ', "'.$sparepart_focus->column_fields['sparepart_price'].'"';
                                   $value .=  ', "'.$module.'"';
                                   $value .=',"' . $row_id . '");\'>'.$temp_val;
                                   $value .='</a>';

                                }else if ($module == 'Service'){
                                    require_once('modules/Service/Service.php');
                                    $service_focus = new Service();
                                    $service_focus->retrieve_entity_info($entity_id,"Service");

                                    /*$service_focus->column_fields['bill_street']
                                    $service_focus->column_fields['bill_street']*/
                                    // $slashes_temp_val = popup_from_html($temp_val);
                                    // $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                                    // if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);
                                    if($return_module == "PriceList"){
                                        $value = '<a href="javascript:window.close();" onclick=\'set_return_inventory_service_pricelist("'.$entity_id.'", "'.nl2br(decode_html($service_focus->column_fields['service_no'])).'",';
                                        $value .=  '"'.popup_decode_html($service_focus->column_fields['service_name']).'"';
                                        $value .=  ', "'.$service_focus->column_fields['unit_price'].'"';
                                        
                                        $value .=',"' . $row_id . '");\'>'.$temp_val;
                                        $value .='</a>'; 

                                    }else{
                                       $value = '<a href="javascript:window.close();" onclick=\'set_return_inventory_service("'.$entity_id.'", "'.nl2br(decode_html($service_focus->column_fields['service_no'])).'",';
                                        $value .=  '"'.popup_decode_html($service_focus->column_fields['service_name']).'"';
                                        $value .=  ', "'.$service_focus->column_fields['unit_price'].'"';
                                        $value .=  ', "'.$module.'"';
                                        $value .=',"' . $row_id . '");\'>'.$temp_val;
                                        $value .='</a>'; 
                                    }

                                }else if ($module == 'Sparepart'){
                                    require_once('modules/Sparepart/Sparepart.php');
                                    $sparepart_focus = new Sparepart();
                                    $sparepart_focus->retrieve_entity_info($entity_id,"Sparepart");

                                    // $slashes_temp_val = popup_from_html($temp_val);
                                    // $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                                    // if(!empty($form)) $form = htmlspecialchars($form,ENT_QUOTES,$default_charset);
                                    if($return_module == "PriceList"){
                                        $value = '<a href="javascript:window.close();" onclick=\'set_return_inventory_sparepart_pricelist("'.$entity_id.'", "'.nl2br(decode_html($sparepart_focus->column_fields['sparepart_no'])).'",';
                                        $value .=  '"'.popup_decode_html($sparepart_focus->column_fields['sparepart_name']).'"';
                                        $value .=  ', "'.$sparepart_focus->column_fields['sparepart_price'].'"';
                                        $value .=  ', "'.$sparepart_focus->column_fields['sparepart_status'].'"';
                                        $value .=',"' . $row_id . '");\'>'.$temp_val;
                                        $value .='</a>'; 

                                    }else{
                                        $value = '<a href="javascript:window.close();" onclick=\'set_return_inventory_service("'.$entity_id.'", "'.nl2br(decode_html($sparepart_focus->column_fields['sparepart_no'])).'",';
                                        $value .=  '"'.popup_decode_html($sparepart_focus->column_fields['sparepart_name']).'"';
                                        $value .=  ', "'.$sparepart_focus->column_fields['sparepart_price'].'"';
                                        $value .=  ', "'.$sparepart_focus->column_fields['sparepart_status'].'"';
                                        $value .=',"' . $row_id . '");\'>'.$temp_val;
                                        $value .='</a>'; 
                                    }
                                    
                                }else if ($return_module == "Salesorder") {

                                    $productcategory = $adb->query_result($list_result, $list_result_count, 'productcategory');
                                    $productstatus = $adb->query_result($list_result, $list_result_count, 'productstatus');
                                    $sellingprice = $adb->query_result($list_result, $list_result_count, 'price_per_box');
                                    $stockavailable = $adb->query_result($list_result, $list_result_count, 'stockavailable');
                                    $unit = $adb->query_result($list_result, $list_result_count, 'base_unit_of_measure');
                                    
                                    $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_salesorder("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $productname . '","' . $tax_str . '","' . $row_id . '","' . nl2br($slashes_desc) . '", "'.$productcategory.'", "'.$productstatus.'", "'.$sellingprice.'", "'.$unit.'", "'.$stockavailable.'" ,"' . $return_module . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                                }else if($return_module == "PriceList"){

                                    $productdescription = $adb->query_result($list_result, $list_result_count, 'productdescription');
                                    $productcategory = $adb->query_result($list_result, $list_result_count, 'productcategory');
                                    $productstatus = $adb->query_result($list_result, $list_result_count, 'productstatus');
                                    $sellingprice = $adb->query_result($list_result, $list_result_count, 'selling_price');
                                    $unit = $adb->query_result($list_result, $list_result_count, 'unit');
                                    $productsubcategory = $adb->query_result($list_result, $list_result_count, 'productsubcategory');
                                    //$mat_gp3_desciption = $adb->query_result($list_result, $list_result_count, 'mat_gp3_desciption');
                                    //$mat_gp3_desciption = htmlspecialchars($mat_gp3_desciption, ENT_QUOTES, $default_charset);
                                    $product_finish = $adb->query_result($list_result, $list_result_count, 'product_finish');//ชนิดผิว
                                    $product_size_mm = $adb->query_result($list_result, $list_result_count, 'product_size_mm');//ขนาด (มม.)
                                    $product_thinkness = $adb->query_result($list_result, $list_result_count, 'product_thinkness');//ความหนา (มม.)
                                    $product_unit = $adb->query_result($list_result, $list_result_count, 'product_unit');//หน่วยนับ
                                    $product_cost_avg = $adb->query_result($list_result, $list_result_count, 'product_cost_avg');//รวมต้นทุนจริงเฉลี่ย

                                    $product_brand = $adb->query_result($list_result, $list_result_count, 'product_brand');
                                    $product_weight_per_box = $adb->query_result($list_result, $list_result_count, 'product_weight_per_box');
                                    /*$value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_pricelist("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $productname . '","'.$productdescription.'","' . $tax_str . '","' . $row_id . '","' . nl2br($slashes_desc) . '", "'.$productcategory.'", "'.$productstatus.'", "'.$sellingprice.'", "'.$unit.'" , "'.$productsubcategory.'" ,"' . $return_module . '","'.$mat_gp3_desciption.'");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';*/
                                    $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_pricelist("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $productname . '","'.$productdescription.'","' . $tax_str . '","' . $row_id . '","' . nl2br($slashes_desc) . '", "'.$productcategory.'", "'.$productstatus.'", "'.$sellingprice.'", "'.$unit.'" , "'.$productsubcategory.'" ,"' . $return_module . '"
                                
                                    ,"'.$product_finish.'"
                                    ,"'.$product_size_mm.'"
                                    ,"'.$product_thinkness.'"
                                    ,"'.$product_unit.'"
                                    ,"'.$product_cost_avg.'"

                                    ,"'.$product_brand.'"
                                    ,"'.$product_weight_per_box.'"
                                );\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';
                                }else if ($return_module == "Projects") {

                                    $productcategory = $adb->query_result($list_result, $list_result_count, 'productcategory');
                                    $productstatus = $adb->query_result($list_result, $list_result_count, 'productstatus');
                                    $sellingprice = $adb->query_result($list_result, $list_result_count, 'price_per_box');
                                    $stockavailable = $adb->query_result($list_result, $list_result_count, 'stockavailable');
                                    $unit = $adb->query_result($list_result, $list_result_count, 'unit');
                                    
                                    $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_projects("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $productname . '","' . $tax_str . '","' . $row_id . '","' . nl2br($slashes_desc) . '", "'.$productcategory.'", "'.$productstatus.'", "'.$sellingprice.'", "'.$unit.'", "'.$stockavailable.'" ,"' . $return_module . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                                }else if ($return_module == "Purchasesorder") {
                                    $product_no = $adb->query_result($list_result, $list_result_count, 'product_no');
                                    $productdescription = $adb->query_result($list_result, $list_result_count, 'productdescription');
                                    $product_brand = $adb->query_result($list_result, $list_result_count, 'product_brand');
                                    $product_group = $adb->query_result($list_result, $list_result_count, 'product_group');
                                    $product_code_crm = $adb->query_result($list_result, $list_result_count, 'product_code_crm');
                                    $product_prefix = $adb->query_result($list_result, $list_result_count, 'product_prefix');
                                    $product_factory_code = $adb->query_result($list_result, $list_result_count, 'product_factory_code');
                                    $product_design_name = $adb->query_result($list_result, $list_result_count, 'product_design_name');
                                    $product_finish = $adb->query_result($list_result, $list_result_count, 'product_finish');
                                    $product_size_ft = $adb->query_result($list_result, $list_result_count, 'product_size_ft');
                                    $product_thinkness = $adb->query_result($list_result, $list_result_count, 'product_thinkness');
                                    $product_grade = $adb->query_result($list_result, $list_result_count, 'product_grade');
                                    $product_film = $adb->query_result($list_result, $list_result_count, 'product_film');
                                    $product_backprint = $adb->query_result($list_result, $list_result_count, 'product_backprint');
                                    $price_usd = $adb->query_result($list_result, $list_result_count, 'price_usd');
                                    $selling_price = $adb->query_result($list_result, $list_result_count, 'selling_price');
                                    $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_purchasesorder(
                                        "' . $entity_id . '"
                                        , "' . $product_no . '"
                                        , "' . $productname . '"
                                        , "' . $productdescription . '"
                                        ,"' . $row_id . '"
                                        ,"'.$product_brand.'"
                                        ,"'.$product_group.'"
                                        ,"'.$product_code_crm.'"
                                        ,"'.$product_prefix.'"
                                        ,"'.$product_factory_code.'"
                                        ,"'.$product_design_name.'"
                                        ,"'.$product_finish.'"
                                        ,"'.$product_size_ft.'"
                                        ,"'.$product_thinkness.'"
                                        ,"'.$product_grade.'"
                                        ,"'.$product_film.'"
                                        ,"'.$product_backprint.'"
                                        ,"'.$price_usd.'"
                                        ,"'.$selling_price.'"
                                );\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                                }else if ($return_module == "Samplerequisition") {
                                    $product_no = $adb->query_result($list_result, $list_result_count, 'product_no');
                                    $productdescription = $adb->query_result($list_result, $list_result_count, 'productdescription');
                                    $product_finish = $adb->query_result($list_result, $list_result_count, 'product_finish');
                                    $product_size_mm = $adb->query_result($list_result, $list_result_count, 'product_size_mm');
                                    $product_thinkness = $adb->query_result($list_result, $list_result_count, 'product_thinkness');
                                    $unit = $adb->query_result($list_result, $list_result_count, 'unit');
                                    $product_catalog_code = $adb->query_result($list_result, $list_result_count, 'product_catalog_code');
                                   
                                    $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_samplerequisition(
                                        "' . $entity_id . '"
                                        , "' . $product_no . '"
                                        , "' . $productname . '"
                                        , "' . $productdescription . '"
                                        ,"' . $row_id . '"
                                        ,"'.$product_finish.'"
                                        ,"'.$product_size_mm.'"
                                        ,"'.$product_thinkness.'"
                                        ,"'.$unit.'"
                                        ,"'.$product_catalog_code.'"
                                );\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';
                                }else{
                                    $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $productname . '", "' . $unitprice . '", "' . $qty_stock . '","' . $tax_str . '","' . $row_id . '","' . nl2br($slashes_desc) . '","' . $sub_det . '","' . $pack_size . '","' . $test_box . '","' . $uom . '","' . $qty . '","' . $pro_type . '","' . $promotion_id . '" ,"' . $return_module . '" ,"' . $relmod_id . '" ,"' . $parent_module . '" ,"' . $productcode . '", "' . $listprice_inc . '", "' . $pricetype . '", "' . $products_businessplusno . '", "' . $component_surface_finish . '", "' . $component_size . '", "' . $component_surface_thinkness . '", "' . $product_unit . '");\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';
                                }
                            }
                        }
                    }
                    elseif ($popuptype == "inventory_prod_po") {
                        $row_id = $_REQUEST['curr_row'];

                        //To get all the tax types and values and pass it to product details
                        $tax_str = '';
                        $tax_details = getAllTaxes();
                        for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                            $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                        }
                        $tax_str = trim($tax_str, ',');
                        $rate = $user_info['conv_rate'];

                        if (getFieldVisibilityPermission($module, $current_user->id, 'unit_price') == '0') {
                            $unitprice = $adb->query_result($list_result, $list_result_count, 'unit_price');
                            if ($_REQUEST['currencyid'] != null) {
                                $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id), $module);
                                $unitprice = $prod_prices[$entity_id];
                            }
                        } else {
                            $unit_price = '';
                        }
                        $sub_products = '';
                        $sub_prod = '';
                        $sub_prod_query = $adb->pquery("SELECT aicrm_products.productid,aicrm_products.productname,aicrm_products.qtyinstock,aicrm_crmentity.description from aicrm_products INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_products.productid INNER JOIN aicrm_seproductsrel on aicrm_seproductsrel.crmid=aicrm_products.productid WHERE aicrm_seproductsrel.productid=? and aicrm_seproductsrel.setype='Products'", array($entity_id));
                        for ($i = 0; $i < $adb->num_rows($sub_prod_query); $i++) {

                            $id = $adb->query_result($sub_prod_query, $i, "productid");
                            $str_sep = '';
                            if ($i > 0) $str_sep = ":";
                            $sub_products .= $str_sep . $id;
                            $sub_prod .= $str_sep . " - $id." . $adb->query_result($sub_prod_query, $i, "productname");
                        }

                        $sub_det = $sub_products . "::" . str_replace(":", "<br>", $sub_prod);

                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $description = $adb->query_result($list_result, $list_result_count, 'description');
                        $slashes_desc = htmlspecialchars($description, ENT_QUOTES, $default_charset);

                        $slashes_desc = str_replace(array("\r", "\n"), array('\r', '\n'), $slashes_desc);
                        $tmp_arr = array("entityid" => $entity_id, "prodname" => "" . stripslashes(nl2br($slashes_temp_val)) . "", "unitprice" => "$unitprice", "taxstring" => "$tax_str", "rowid" => "$row_id", "desc" => "$slashes_desc", "subprod_ids" => "$sub_det");
                        require_once('include/Zend/Json.php');
                        $prod_arr = Zend_Json::encode($tmp_arr);

                        $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory_po("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $unitprice . '", "' . $tax_str . '","' . $row_id . '","' . $slashes_desc . '","' . $sub_det . '"); \'  vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                    } else if($popuptype == "inventory_po"){
                        if ($_REQUEST['curr_row'] != "") {
                            $row_id = $_REQUEST['curr_row'];
                            $_SESSION['curr_row'] = $_REQUEST['curr_row'];
                        } else {
                            if ($_SESSION['curr_row'] != "") {
                                $row_id = $_SESSION['curr_row'];
                            } else {
                                $row_id = 0;
                            }
                        }
                        $purchasesorder_no = $adb->query_result($list_result, $list_result_count, 'purchasesorder_no');
                        $purchasesorder_name = $adb->query_result($list_result, $list_result_count, 'purchasesorder_name');
                        $po_date = $adb->query_result($list_result, $list_result_count, 'po_date');
                        $po_status = $adb->query_result($list_result, $list_result_count, 'po_status');

                        $value = '<a href="javascript:window.close();" id=\'popup_po_' . $entity_id . '\' onclick=\'set_return_inventory_purchasesorder("' . $entity_id . '"
                        ,"' . $row_id . '"
                        , "' . $purchasesorder_no . '"
                        , "' . $purchasesorder_name . '"
                        , "' . $po_date . '"
                        , "' . $po_status . '"
                        );\' vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';
                    } elseif ($popuptype == "inventory_service") {
                        $row_id = $_REQUEST['curr_row'];

                        //To get all the tax types and values and pass it to product details
                        $tax_str = '';
                        $tax_details = getAllTaxes();
                        for ($tax_count = 0; $tax_count < count($tax_details); $tax_count++) {
                            $tax_str .= $tax_details[$tax_count]['taxname'] . '=' . $tax_details[$tax_count]['percentage'] . ',';
                        }
                        $tax_str = trim($tax_str, ',');
                        $rate = $user_info['conv_rate'];
                        if (getFieldVisibilityPermission('Services', $current_user->id, 'unit_price') == '0') {
                            $unitprice = $adb->query_result($list_result, $list_result_count, 'unit_price');
                            if ($_REQUEST['currencyid'] != null) {
                                $prod_prices = getPricesForProducts($_REQUEST['currencyid'], array($entity_id), $module);
                                $unitprice = $prod_prices[$entity_id];
                            }
                        } else {
                            $unit_price = '';
                        }

                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $description = decode_html_force($adb->query_result($list_result, $list_result_count, 'description'));
                        $slashes_desc = htmlspecialchars($description, ENT_QUOTES, $default_charset);

                        $slashes_desc = str_replace(array("\r", "\n"), array('\r', '\n'), $slashes_desc);
                        $tmp_arr = array("entityid" => $entity_id, "prodname" => "" . stripslashes(nl2br($slashes_temp_val)) . "", "unitprice" => "$unitprice", "taxstring" => "$tax_str", "rowid" => "$row_id", "desc" => "$slashes_desc");
                        require_once('include/Zend/Json.php');
                        $prod_arr = Zend_Json::encode($tmp_arr);

                        $value = '<a href="javascript:window.close();" id=\'popup_product_' . $entity_id . '\' onclick=\'set_return_inventory("' . $entity_id . '", "' . nl2br($slashes_temp_val) . '", "' . $unitprice . '", "' . $tax_str . '","' . $row_id . '","' . $slashes_desc . '");\'  vt_prod_arr=\'' . $prod_arr . '\' >' . $temp_val . '</a>';

                    } elseif ($popuptype == "inventory_pricelist") {

                        $prod_id = $_REQUEST['productid'];
                        $flname = $_REQUEST['fldname'];
                        $listprice = getPriceList($prod_id, $entity_id);
                        //echo $temp_val; echo "<br>"; exit;
                        $temp_val = popup_from_html($temp_val);
                        $value = '<a href="javascript:window.close();" onclick=\'set_return_inventory_plist("' . $listprice . '", "' . $flname . '"); \'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "inventory_pb") {

                        $prod_id = $_REQUEST['productid'];
                        $flname = $_REQUEST['fldname'];
                        $listprice = getListPrice($prod_id, $entity_id);

                        $temp_val = popup_from_html($temp_val);
                        $value = '<a href="javascript:window.close();" onclick=\'set_return_inventory_pb("' . $listprice . '", "' . $flname . '"); \'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "prevaccount") {
                        require_once('modules/Accounts/Accounts.php');
                        $acct_focus = new Accounts();
                        $acct_focus->retrieve_entity_info($entity_id, "Accounts");
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                        //Address
                        $value = '<a href="javascript:window.close();" onclick=\'set_return_prevaccount("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val));

                        //infomation
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['firstname']) . '", "' . popup_decode_html($acct_focus->column_fields['lastname']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['salutation']) . '", "' . popup_decode_html($acct_focus->column_fields['middlename']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['cf_3057']);

                        $value .= '");\'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "specific_account_project") {

                        require_once('modules/Accounts/Accounts.php');
                        $acct_focus = new Accounts();
                        $acct_focus->retrieve_entity_info($entity_id, "Accounts");
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $xyz = array('addressline1', 'bill_street', 'bill_city', 'bill_code', 'bill_pobox', 'bill_country', 'bill_state', 'ship_street', 'ship_city', 'ship_code', 'ship_pobox', 'ship_country', 'ship_state');
                        for ($i = 0; $i < 12; $i++) {
                            if (getFieldVisibilityPermission($module, $current_user->id, $xyz[$i]) == '0') {
                                $acct_focus->column_fields[$xyz[$i]] = $acct_focus->column_fields[$xyz[$i]];
                            } else
                            $acct_focus->column_fields[$xyz[$i]] = '';
                        }
                        $bill_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['bill_street']));
                        $ship_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['ship_street']));

                        $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                        if (!empty($form)) $form = htmlspecialchars($form, ENT_QUOTES, $default_charset);

                        //Address
                        $value = '<a href="javascript:window.close();" onclick=\'set_return_project("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '","' . $form;
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['addressline1']) . '", "' . popup_decode_html($acct_focus->column_fields['addressline2']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['accountvillage']) . '", "' . popup_decode_html($acct_focus->column_fields['accountalley']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['accountroad']) . '", "' . popup_decode_html($acct_focus->column_fields['region']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['province']) . '", "' . popup_decode_html($acct_focus->column_fields['district']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['subdistrict']) . '", "' . popup_decode_html($acct_focus->column_fields['postalcode']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['mobile']) . '", "' . popup_decode_html($acct_focus->column_fields['phone']);
                        $value .= '","' . $_REQUEST["module_return"];
                        $value .= '");\'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "specific_account_address") {

                        require_once('modules/Accounts/Accounts.php');
                        $acct_focus = new Accounts();
                        $acct_focus->retrieve_entity_info($entity_id, "Accounts");
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $xyz = array('addressline1', 'bill_street', 'bill_city', 'bill_code', 'bill_pobox', 'bill_country', 'bill_state', 'ship_street', 'ship_city', 'ship_code', 'ship_pobox', 'ship_country', 'ship_state');
                        for ($i = 0; $i < 12; $i++) {
                            if (getFieldVisibilityPermission($module, $current_user->id, $xyz[$i]) == '0') {
                                $acct_focus->column_fields[$xyz[$i]] = $acct_focus->column_fields[$xyz[$i]];
                            } else
                            $acct_focus->column_fields[$xyz[$i]] = '';
                        }

                        // echo $_REQUEST["module_return"]; exit;

                        if ($_REQUEST["module_return"] == "Quotes") {

                            require_once('library/libCommon.php');
                            $libcommon = new libCommon();
                            list($quotes_user, $quotes_user_tel, $quotes_user_position) = $libcommon->get_quotes_user_request($acct_focus);
                        } else {
                            $quotes_user = "";
                            $quotes_user_tel = "";
                            $quotes_user_position = "";
                        }
                        $bill_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['bill_street']));
                        $ship_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['ship_street']));

                        $form = !empty($_REQUEST['form']) ? $_REQUEST['form'] : '';
                        if (!empty($form)) $form = htmlspecialchars($form, ENT_QUOTES, $default_charset);

                        //Address

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_address("' . $entity_id . '"
                        , "' . popup_decode_html($acct_focus->column_fields['accountname']) . '","' . $form ;
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['mobile']) . '"
                        , "' . popup_decode_html($acct_focus->column_fields['idcardno']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['branch']) . '"
                        , "' . popup_decode_html($acct_focus->column_fields['nickname']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['career']) . '"
                        , "' . popup_decode_html($acct_focus->column_fields['village']);

                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['addressline']) . '"
                        , "' . popup_decode_html($acct_focus->column_fields['addressline1']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['villageno']) . '"
                        , "' . popup_decode_html($acct_focus->column_fields['lane']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['street']) . '"
                        , "' . popup_decode_html($acct_focus->column_fields['subdistrict']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['district']) . '"
                        , "' . popup_decode_html($acct_focus->column_fields['province']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['postalcode']). '"
                        , "' . popup_decode_html($acct_focus->column_fields['erpaccountid']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['email1']). '"
                        , "' . popup_decode_html($acct_focus->column_fields['birthdate']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['nametitle']). '"
                        , "' . popup_decode_html($acct_focus->column_fields['firstname']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['lastname']). '"
                        , "' . popup_decode_html($acct_focus->column_fields['idcardno']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['gender']). '"
                        , "' . popup_decode_html($acct_focus->column_fields['mobile2']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['email2']). '"
                        , "' . popup_decode_html($acct_focus->column_fields['address1']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['address2']). '"
                        , "' . popup_decode_html($acct_focus->column_fields['socialidline']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['socialidfacebook']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingvillage']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingaddressline']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingaddressline1']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingvillageno']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingstreet']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billinglane']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingsubdistrict']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingdistrict']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingprovince']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billingpostalcode']);
                        $value .= '", "' .$_REQUEST["module_return"];
                        $value .= '", "' .$_REQUEST["return_field"];
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['billing_address']);
                        $value .= '", "' . popup_decode_html($acct_focus->column_fields['account_payment_term']);
                        $value .= '");\'>' . $temp_val . '</a>';
                
                } elseif ($popuptype == "specific_contact_account_address") {
                    require_once('modules/Accounts/Accounts.php');
                    $acct_focus = new Accounts();
                    $acct_focus->retrieve_entity_info($entity_id, "Accounts");

                    $slashes_temp_val = popup_from_html($temp_val);
                    $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                    $bill_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['bill_street']));
                    $ship_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['ship_street']));

                    $value = '<a href="javascript:window.close();" onclick=\'set_return_contact_address("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '", "' . $bill_street . '", "' . $ship_street . '", "' . popup_decode_html($acct_focus->column_fields['bill_city']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_city']) . '", "' . popup_decode_html($acct_focus->column_fields['bill_state']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_state']) . '", "' . popup_decode_html($acct_focus->column_fields['bill_code']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_code']) . '", "' . popup_decode_html($acct_focus->column_fields['bill_country']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_country']) . '","' . popup_decode_html($acct_focus->column_fields['bill_pobox']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_pobox']) . '", "' . popup_decode_html($acct_focus->column_fields['cf_629']) . '", "' . popup_decode_html($acct_focus->column_fields['cf_630']) . '", "' . popup_decode_html($acct_focus->column_fields['cf_631']) . '", "' . popup_decode_html($acct_focus->column_fields['cf_632']) . '", "' . popup_decode_html($acct_focus->column_fields['cf_633']) . '", "' . popup_decode_html($acct_focus->column_fields['cf_634']);

                    $value .= '", "' . popup_decode_html($acct_focus->column_fields['addressline1']) . '", "' . popup_decode_html($acct_focus->column_fields['addressline2']);
                    $value .= '", "' . popup_decode_html($acct_focus->column_fields['accountvillage']) . '", "' . popup_decode_html($acct_focus->column_fields['accountalley']);
                    $value .= '", "' . popup_decode_html($acct_focus->column_fields['accountroad']) . '", "' . popup_decode_html($acct_focus->column_fields['region']);
                    $value .= '", "' . popup_decode_html($acct_focus->column_fields['province']) . '", "' . popup_decode_html($acct_focus->column_fields['district']);
                    $value .= '", "' . popup_decode_html($acct_focus->column_fields['subdistrict']) . '", "' . popup_decode_html($acct_focus->column_fields['postalcode']);
                    $value .= '");\'>' . $temp_val . '</a>';

                } elseif ($popuptype == "specific_potential_account_address") {
                    $slashes_temp_val = popup_from_html($temp_val);
                    $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                        // For B2C support, Potential was enabled to be linked to Contacts also.
                        // Hence we need case handling for it.
                    $relatedid = $adb->query_result($list_result, $list_result_count, "related_to");
                    $relatedentity = getSalesEntityType($relatedid);
                    if ($relatedentity == 'Accounts') {
                        require_once('modules/Accounts/Accounts.php');
                        $acct_focus = new Accounts();
                        $acct_focus->retrieve_entity_info($relatedid, "Accounts");
                        $account_name = getAccountName($relatedid);

                        $slashes_account_name = popup_from_html($account_name);
                        $slashes_account_name = htmlspecialchars($slashes_account_name, ENT_QUOTES, $default_charset);

                        $xyz = array('bill_street', 'bill_city', 'bill_code', 'bill_pobox', 'bill_country', 'bill_state', 'ship_street', 'ship_city', 'ship_code', 'ship_pobox', 'ship_country', 'ship_state');
                        for ($i = 0; $i < 12; $i++) {
                            if (getFieldVisibilityPermission('Accounts', $current_user->id, $xyz[$i]) == '0') {
                                $acct_focus->column_fields[$xyz[$i]] = $acct_focus->column_fields[$xyz[$i]];
                            } else
                            $acct_focus->column_fields[$xyz[$i]] = '';
                        }
                        $bill_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['bill_street']));
                        $ship_street = str_replace(array("\r", "\n"), array('\r', '\n'), popup_decode_html($acct_focus->column_fields['ship_street']));

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_address("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '", "' . $relatedid . '", "' . nl2br(decode_html($slashes_account_name)) . '", "' . $bill_street . '", "' . $ship_street . '", "' . popup_decode_html($acct_focus->column_fields['bill_city']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_city']) . '", "' . popup_decode_html($acct_focus->column_fields['bill_state']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_state']) . '", "' . popup_decode_html($acct_focus->column_fields['bill_code']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_code']) . '", "' . popup_decode_html($acct_focus->column_fields['bill_country']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_country']) . '","' . popup_decode_html($acct_focus->column_fields['bill_pobox']) . '", "' . popup_decode_html($acct_focus->column_fields['ship_pobox']) . '");\'>' . $temp_val . '</a>';
                    } else if ($relatedentity == 'Contacts') {

                        require_once('modules/Contacts/Contacts.php');
                        $contact_name = getContactName($relatedid);

                        $slashes_contact_name = popup_from_html($contact_name);

                        $slashes_contact_name = htmlspecialchars($slashes_contact_name, ENT_QUOTES, $default_charset);

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_contact("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '", "' . $relatedid . '", "' . nl2br(decode_html($slashes_contact_name)) . '");\'>' . $temp_val . '</a>';

                    } else {
                        $value = $temp_val;
                    }
                    } //added by rdhital/Raju for better emails
                    elseif ($popuptype == "set_return_emails") {
                        if ($module == 'Accounts') {
                            $name = $adb->query_result($list_result, $list_result_count, 'accountname');
                            $accid = $adb->query_result($list_result, $list_result_count, 'accountid');
                            if (CheckFieldPermission('email1', $module) == "true") {
                                $emailaddress = $adb->query_result($list_result, $list_result_count, "email1");
                                $email_check = 1;
                            } else
                            $email_check = 0;
                            if ($emailaddress == '') {
                                if (CheckFieldPermission('email2', $module) == 'true') {
                                    $emailaddress2 = $adb->query_result($list_result, $list_result_count, "email2");
                                    $email_check = 2;
                                } else {
                                    if ($email_check == 1)
                                        $email_check = 4;
                                    else
                                        $email_check = 3;
                                }
                            }
                            $querystr = "SELECT fieldid,fieldlabel,columnname FROM aicrm_field WHERE tabid=? and uitype=13 and aicrm_field.presence in (0,2)";
                            $queryres = $adb->pquery($querystr, array(getTabid($module)));
                            //Change this index 0 - to get the aicrm_fieldid based on email1 or email2
                            $fieldid = $adb->query_result($queryres, 0, 'fieldid');

                            $slashes_name = popup_from_html($name);
                            $slashes_name = htmlspecialchars($slashes_name, ENT_QUOTES, $default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'return set_return_emails(' . $entity_id . ',' . $fieldid . ',"' . decode_html($slashes_name) . '","' . $emailaddress . '","' . $emailaddress2 . '","' . $email_check . '"); \'>' . textlength_check($name) . '</a>';

                        } elseif ($module == 'Vendors') {
                            $name = $adb->query_result($list_result, $list_result_count, 'vendorname');
                            $venid = $adb->query_result($list_result, $list_result_count, 'vendorid');
                            if (CheckFieldPermission('email', $module) == "true") {
                                $emailaddress = $adb->query_result($list_result, $list_result_count, "email");
                                $email_check = 1;
                            } else
                            $email_check = 0;
                            $querystr = "SELECT fieldid,fieldlabel,columnname FROM aicrm_field WHERE tabid=? and uitype=13 and aicrm_field.presence in (0,2)";
                            $queryres = $adb->pquery($querystr, array(getTabid($module)));
                            //Change this index 0 - to get the aicrm_fieldid based on email1 or email2
                            $fieldid = $adb->query_result($queryres, 0, 'fieldid');

                            $slashes_name = popup_from_html($name);
                            $slashes_name = htmlspecialchars($slashes_name, ENT_QUOTES, $default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'return set_return_emails(' . $entity_id . ',' . $fieldid . ',"' . decode_html($slashes_name) . '","' . $emailaddress . '","' . $emailaddress2 . '","' . $email_check . '"); \'>' . textlength_check($name) . '</a>';

                        } elseif ($module == 'Contacts' || $module == 'Leads' || $module == 'LeadManagement') {
                            $name = getFullNameFromQResult($list_result, $list_result_count, $module);
                            if (CheckFieldPermission('email', $module) == "true") {
                                $emailaddress = $adb->query_result($list_result, $list_result_count, "email");
                                $email_check = 1;
                            } else
                            $email_check = 0;
                            if ($emailaddress == '') {
                                if (CheckFieldPermission('yahooid', $module) == 'true') {
                                    $emailaddress2 = $adb->query_result($list_result, $list_result_count, "yahooid");
                                    $email_check = 2;
                                } else {
                                    if ($email_check == 1)
                                        $email_check = 4;
                                    else
                                        $email_check = 3;
                                }
                            }

                            $querystr = "SELECT fieldid,fieldlabel,columnname FROM aicrm_field WHERE tabid=? and uitype=13 and aicrm_field.presence in (0,2)";
                            $queryres = $adb->pquery($querystr, array(getTabid($module)));
                            //Change this index 0 - to get the aicrm_fieldid based on email or yahooid
                            $fieldid = $adb->query_result($queryres, 0, 'fieldid');

                            $slashes_name = popup_from_html($name);
                            $slashes_name = htmlspecialchars($slashes_name, ENT_QUOTES, $default_charset);

                            $value = '<a href="javascript:window.close();" onclick=\'return set_return_emails(' . $entity_id . ',' . $fieldid . ',"' . decode_html($slashes_name) . '","' . $emailaddress . '","' . $emailaddress2 . '","' . $email_check . '"); \'>' . $name . '</a>';

                        } else {
                            $firstname = $adb->query_result($list_result, $list_result_count, "first_name");
                            $lastname = $adb->query_result($list_result, $list_result_count, "last_name");
                            $name = $lastname . ' ' . $firstname;
                            $emailaddress = $adb->query_result($list_result, $list_result_count, "email1");

                            $slashes_name = popup_from_html($name);
                            $slashes_name = htmlspecialchars($slashes_name, ENT_QUOTES, $default_charset);
                            $email_check = 1;
                            $value = '<a href="javascript:window.close();" onclick=\'return set_return_emails(' . $entity_id . ',-1,"' . decode_html($slashes_name) . '","' . $emailaddress . '","' . $emailaddress2 . '","' . $email_check . '"); \'>' . textlength_check($name) . '</a>';

                        }

                    } elseif ($popuptype == "specific_campaign") {
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_campaign("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "accountfield") {
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $field = $_REQUEST["field"];

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_accountfield("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '", "' . $field . '");\'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "accountcode") {
                        require_once('modules/Accounts/Accounts.php');
                        $acct_focus = new Accounts();
                        $acct_focus->retrieve_entity_info($entity_id, "Accounts");
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $field = $_REQUEST["field"];

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_accountcode("' . $entity_id . '","' . popup_decode_html($acct_focus->column_fields['sap_code']) . '" ,"' . nl2br(decode_html($slashes_temp_val)) . '", "' . $field . '", "' . popup_decode_html($acct_focus->column_fields['mobile']) . '");\'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "accountrel") {

                        require_once('modules/Accounts/Accounts.php');
                        $acct_focus = new Accounts();
                        $acct_focus->retrieve_entity_info($entity_id, "Accounts");
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $field = $_REQUEST["field"];
                        $return_module = $_REQUEST["return_module"];

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_accountrel("' . $entity_id . '","' . nl2br(decode_html($slashes_temp_val)) . '", "' . $field . '", "' . popup_decode_html($acct_focus->column_fields['account_no']) . '", "' . $return_module . '");\'>' . $temp_val . '</a>';


                    } elseif ($popuptype == "contactcode") {
                        require_once('modules/Contacts/Contacts.php');
                        $cont_focus = new Contacts();
                        $cont_focus->retrieve_entity_info($entity_id, "Contacts");
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $field = $_REQUEST["field"];
                        $return_module = $_REQUEST["return_module"];
                        $accountid = popup_decode_html($cont_focus->column_fields['account_id']);
                        
                        if (isset($accountid) && $accountid != 0 && $accountid != '') {

                                $sql_c = "select aicrm_crmentity.deleted
                                from aicrm_crmentity
                                where aicrm_crmentity.crmid=?";
                                $c_result = $adb->pquery($sql_c, array($accountid));
                                $acc_deleted = $adb->query_result($c_result, 0, "deleted");
                                
                                if($acc_deleted == 0){
                                    require_once('modules/Accounts/Accounts.php');
                                    $account_focus = new Accounts();
                                    $account_focus->retrieve_entity_info($cont_focus->column_fields['account_id'], "Accounts");
                                    $account_id = popup_decode_html($cont_focus->column_fields['account_id']); //Account ID
                                    $account_name = popup_decode_html(@$account_focus->column_fields['accountname']); //Account Name
                                    $account_mobile = popup_decode_html(@$account_focus->column_fields['mobile']); //Mobile
                                    $account_no = popup_decode_html(@$account_focus->column_fields['account_no']); //
                                }else{
                                    $account_id = ''; //Account ID
                                    $account_name = ''; //Account Name
                                    $account_mobile = ''; //Mobile
                                    $account_no = '';
                                }
                            } else {
                                $account_id = ''; //Account ID
                                $account_name = ''; //Account Name
                                $account_mobile = ''; //Mobile
                                $account_no = '';
                            }

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_contactcode1("' . $entity_id . '" ,"' . nl2br(decode_html($slashes_temp_val)) . '" ,"' . popup_decode_html($cont_focus->column_fields['lastname']) . '","' . popup_decode_html($cont_focus->column_fields['email']) . '", "' . $field . '", "' . popup_decode_html($cont_focus->column_fields['mobile']) . '" , "' . $account_id . '" , "' . popup_decode_html($account_name) . '" , "' . popup_decode_html($account_mobile) . '", "' . $return_module . '", "' . $account_no . '" ,"' .popup_decode_html($cont_focus->column_fields['service_level_type']). '","' .popup_decode_html($cont_focus->column_fields['service_level']). '");\'>' . $temp_val . '</a>';

                    } elseif ($popuptype == "contactcode2") {
                        require_once('modules/Contacts/Contacts.php');
                        $cont_focus = new Contacts();
                        $cont_focus->retrieve_entity_info($entity_id, "Contacts");
                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);
                        $field = $_REQUEST["field"];

                        $accountid = popup_decode_html($cont_focus->column_fields['account_id']);
                        
                        if (isset($accountid) && $accountid != 0 && $accountid != '') {

                                $sql_c = "select aicrm_crmentity.deleted
                                from aicrm_crmentity
                                where aicrm_crmentity.crmid=?";
                                $c_result = $adb->pquery($sql_c, array($accountid));
                                $acc_deleted = $adb->query_result($c_result, 0, "deleted");
                                
                                if($acc_deleted == 0){
                                    require_once('modules/Accounts/Accounts.php');
                                    $account_focus = new Accounts();
                                    $account_focus->retrieve_entity_info($cont_focus->column_fields['account_id'], "Accounts");
                                    $account_id = popup_decode_html($cont_focus->column_fields['account_id']); //Account ID
                                    $account_name = popup_decode_html(@$account_focus->column_fields['accountname']); //Account Name
                                    $account_mobile = popup_decode_html(@$account_focus->column_fields['mobile']); //Mobile
                                }else{
                                    $account_id = ''; //Account ID
                                    $account_name = ''; //Account Name
                                    $account_mobile = ''; //Mobile
                                }
                            } else {
                                $account_id = ''; //Account ID
                                $account_name = ''; //Account Name
                                $account_mobile = ''; //Mobile
                            }

                        $value = '<a href="javascript:window.close();" onclick=\'set_return_specific_contactcode2("' . $entity_id . '" ,"' . nl2br(decode_html($slashes_temp_val)) . '" ,"' . popup_decode_html($cont_focus->column_fields['email']) . '", "' . $field . '", "' . popup_decode_html($cont_focus->column_fields['mobile']) . '" , "' . $account_id . '" , "' . popup_decode_html($account_name) . '" , "' . popup_decode_html($account_mobile) . '");\'>' . $temp_val . '</a>';

                    } else {

                        if ($colname == "lastname")
                            $temp_val = getFullNameFromQResult($list_result, $list_result_count, $module);

                        $slashes_temp_val = popup_from_html($temp_val);
                        $slashes_temp_val = htmlspecialchars($slashes_temp_val, ENT_QUOTES, $default_charset);

                        $log->debug("Exiting getValue method ...");
                        if ($_REQUEST['maintab'] == 'Calendar') {
                            $value = '<a href="javascript:window.close();" onclick=\'set_return_todo("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';
                        } else {

                            if($module == "Accounts"){
                                $sql = "SELECT accountcode from aicrm_account WHERE accountid='" . $entity_id . "' ";
                                $data_acc = $adb->pquery($sql, '');
                                $cf_961 = $adb->query_result($data_acc, 0, "accountcode");
                                $value = '<a href="javascript:window.close();" onclick=\'set_return_code("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '","' . $cf_961 . '");\'>' . $temp_val . '</a>';
                            }else{
                                $value = '<a href="javascript:window.close();" onclick=\'set_return("' . $entity_id . '", "' . nl2br(decode_html($slashes_temp_val)) . '");\'>' . $temp_val . '</a>';
                            }

                        }
                    }

                } else {

                    if (($module == "Leads" && $colname == "lastname") || ($module == "LeadManagement" && $colname == "lastname") || ($module == "Contacts" && $colname == "lastname")) {
                        $value = '<a href="index.php?action=DetailView&module=' . $module . '&record=' . $entity_id . '&parenttab=' . $tabname . '">' . $temp_val . '</a>';
                    } elseif ($module == "Calendar") {

                        $actvity_type = $adb->query_result($list_result, $list_result_count, 'activitytype');
                        $actvity_type = ($actvity_type != '') ? $actvity_type : $adb->query_result($list_result, $list_result_count, 'type');
                        if ($actvity_type == "Task") {
                            $value = '<a href="index.php?action=DetailView&module=' . $module . '&record=' . $entity_id . '&activity_mode=Task&parenttab=' . $tabname . '">' . $temp_val . '</a>';
                        } else {
                            $value = '<a href="index.php?action=DetailView&module=' . $module . '&record=' . $entity_id . '&activity_mode=Events&parenttab=' . $tabname . '">' . $temp_val . '</a>';
                        }

                    } elseif ($module == "PriceBooks") {

                        $value = '<a href="index.php?action=DetailView&module=PriceBooks&record=' . $entity_id . '&parenttab=' . $tabname . '">' . $temp_val . '</a>';

                    } elseif ($module == 'Emails') {
                        $value = $temp_val;
                    } else {
                        if($module == "Projects"){
                            $display = isPermitted('Projects', "EditView", $entity_id);
                            $value = '<a href="MOAIMB-Webview/index.php/Projects/view_web/'.$entity_id.'?userid='.$current_user->id.'" class="ls-modal-link" onclick="modal_projects(\'MOAIMB-Webview/index.php/Projects/view_web/'.$entity_id.'?userid='.$current_user->id.'&display='.$display.'\',event)">' . $temp_val . '</a>';
                        }else{
                            $value = '<a href="index.php?action=DetailView&module=' . $module . '&record=' . $entity_id . '&parenttab=' . $tabname . '">' . $temp_val . '</a>';
                        }
                    }
                }
            } elseif ($fieldname == 'expectedroi' || $fieldname == 'actualroi' || $fieldname == 'actualcost' || $fieldname == 'budgetcost' || $fieldname == 'expectedrevenue') {
                $rate = $user_info['conv_rate'];
                $value = convertFromDollar($temp_val, $rate);
            } elseif (($module == 'Quotes') && ($fieldname == 'hdnGrandTotal' || $fieldname == 'hdnSubTotal' || $fieldname == 'txtAdjustment'
                || $fieldname == 'hdnDiscountAmount' || $fieldname == 'hdnS_H_Amount')
        ) {
                $currency_info = getInventoryCurrencyInfo($module, $entity_id);
                $currency_id = $currency_info['currency_id'];
                $currency_symbol = $currency_info['currency_symbol'];
                $value = $currency_symbol . number_format($temp_val, 2, '.', ',');
            } else {
                $value = $temp_val;
            }
        }

        // Mike Crowe Mod --------------------------------------------------------Make right justified and aicrm_currency value
        if (in_array($uitype, array(71, 72, 7, 9, 90))) {
            if($uitype == 7 && $fieldname == 'package_size_sqm_per_box'){
                $value = '<span align="right">' . number_format($value, 4, '.', ',') . '</div>';
            }else{
                $value = '<span align="right">' . number_format($value, 2, '.', ',') . '</div>';
            }
        }
        $log->debug("Exiting getValue method ...");
        if ($module == 'Contacts') {

        }
        //echo $value; echo "<br>";
        return $value;
    }

    /** Function to get the list query for a module
     * @param $module -- module name:: Type string
     * @param $where -- where:: Type string
     * @returns $query -- query:: Type query
     */
    function getListQuery($module, $where = '')
    {
        global $log;
        $log->debug("Entering getListQuery(" . $module . "," . $where . ") method ...");

        global $current_user;
        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        require('user_privileges/sharing_privileges_' . $current_user->id . '.php');
        $tab_id = getTabid($module);
        switch ($module) {

            Case "Claim":
            $query = "SELECT distinct(aicrm_claim.claimid),aicrm_crmentity.*, aicrm_claim.*, aicrm_claimcf.* 
            FROM aicrm_claim
            INNER JOIN aicrm_claimcf ON aicrm_claimcf.claimid = aicrm_claim.claimid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_claim.claimid
            INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_claim.accountid
            INNER JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_claim.projectsid
	        LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_claim.contactid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Branchs":
            $query = "SELECT distinct(aicrm_branchs.branchsid),aicrm_crmentity.*, aicrm_branchs.*, aicrm_branchscf.* 
            FROM aicrm_branchs
            INNER JOIN aicrm_branchscf ON aicrm_branchscf.branchsid = aicrm_branchs.branchsid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_branchs.branchsid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Projects":
            $query = "SELECT distinct(aicrm_projects.projectsid),aicrm_crmentity.*, aicrm_projects.*, aicrm_projectscf.*
            ,aicrm_projects.accountid , aicrm_projects.contactid 
            FROM aicrm_projects
            INNER JOIN aicrm_projectscf ON aicrm_projectscf.projectsid = aicrm_projects.projectsid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid
            LEFT JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_projects.dealid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_projects.accountid
            LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_projects.contactid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Smartemail":
            $query = "SELECT distinct(aicrm_smartemail.smartemailid),aicrm_crmentity.*, aicrm_smartemail.*, aicrm_smartemailcf.*
            FROM aicrm_smartemail
            INNER JOIN aicrm_smartemailcf ON aicrm_smartemailcf.smartemailid = aicrm_smartemail.smartemailid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartemail.smartemailid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }

            break;

            Case "Competitor":
            $query = "SELECT distinct(aicrm_competitor.competitorid),aicrm_crmentity.*, aicrm_competitor.*, aicrm_competitorcf.*
            FROM aicrm_competitor
            INNER JOIN aicrm_competitorcf ON aicrm_competitorcf.competitorid = aicrm_competitor.competitorid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitor.competitorid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Competitorproduct":
            $query = "SELECT distinct(aicrm_competitorproduct.competitorproductid),aicrm_crmentity.*, aicrm_competitorproduct.*, aicrm_competitorproductcf.*
            FROM aicrm_competitorproduct
            INNER JOIN aicrm_competitorproductcf ON aicrm_competitorproductcf.competitorproductid = aicrm_competitorproduct.competitorproductid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitorproduct.competitorproductid
            LEFT JOIN aicrm_competitor ON aicrm_competitor.competitorid = aicrm_competitorproduct.competitorid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Premuimproduct":
            $query = "SELECT distinct(aicrm_premuimproduct.premuimproductid),aicrm_crmentity.*, aicrm_premuimproduct.*, aicrm_premuimproductcf.*
            FROM aicrm_premuimproduct
            INNER JOIN aicrm_premuimproductcf ON aicrm_premuimproductcf.premuimproductid = aicrm_premuimproduct.premuimproductid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_premuimproduct.premuimproductid
            LEFT JOIN aicrm_premuimproductcomments ON aicrm_premuimproductcomments.premuimproductid = aicrm_premuimproduct.premuimproductid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Service":
            $query = "SELECT distinct(aicrm_service.serviceid),aicrm_crmentity.*, aicrm_service.*, aicrm_servicecf.*
            FROM aicrm_service
            INNER JOIN aicrm_servicecf ON aicrm_servicecf.serviceid = aicrm_service.serviceid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_service.serviceid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Servicerequest":
            $query = "SELECT distinct(aicrm_servicerequest.servicerequestid),aicrm_crmentity.*, aicrm_servicerequest.*, aicrm_servicerequestcf.*
            FROM aicrm_servicerequest
            INNER JOIN aicrm_servicerequestcf ON aicrm_servicerequestcf.servicerequestid = aicrm_servicerequest.servicerequestid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicerequest.servicerequestid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_servicerequest.accountid
            LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_servicerequest.contactid
            LEFT JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_servicerequest.salesorderid
            LEFT JOIN aicrm_salesinvoice ON aicrm_salesinvoice.salesinvoiceid = aicrm_servicerequest.salesinvoiceid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Serial":
            $query = "SELECT distinct(aicrm_serial.serialid),aicrm_crmentity.*, aicrm_serial.*, aicrm_serialcf.* 
            FROM aicrm_serial
            INNER JOIN aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_serial.serialid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_serial.accountid
            LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_serial.product_id
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;
                //product_id
            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Seriallist":
            $query = "SELECT distinct(aicrm_seriallist.seriallistid),aicrm_crmentity.*, aicrm_seriallist.*, aicrm_seriallistcf.* 
            FROM aicrm_seriallist
            INNER JOIN aicrm_seriallistcf ON aicrm_seriallistcf.seriallistid = aicrm_seriallist.seriallistid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_seriallist.seriallistid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Inspection":
                $query = "SELECT distinct(aicrm_inspection.inspectionid),aicrm_crmentity.*, aicrm_inspection.*, aicrm_inspectioncf.* 
                 FROM aicrm_inspection
                INNER JOIN aicrm_inspectioncf ON aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid
                LEFT JOIN aicrm_serial ON aicrm_serial.serialid = aicrm_inspection.serialid
                LEFT JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_inspection.inspectiontemplateid
                LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_inspection.jobid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

            Case "Inspectiontemplate":
                $query = "SELECT distinct(aicrm_inspectiontemplate.inspectiontemplateid),aicrm_crmentity.*, aicrm_inspectiontemplate.*, aicrm_inspectiontemplatecf.* 
                FROM aicrm_inspectiontemplate
                INNER JOIN aicrm_inspectiontemplatecf ON aicrm_inspectiontemplatecf.inspectiontemplateid = aicrm_inspectiontemplate.inspectiontemplateid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspectiontemplate.inspectiontemplateid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;
                
                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;

                }
                break;

            Case "Errors":
            $query = "SELECT distinct(aicrm_errors.errorsid),aicrm_crmentity.*, aicrm_errors.*, aicrm_errorscf.* 
            FROM aicrm_errors
            INNER JOIN aicrm_errorscf ON aicrm_errorscf.errorsid = aicrm_errors.errorsid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_errors.errorsid
            LEFT JOIN aicrm_products ON aicrm_errors.product_id = aicrm_products.productid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Errorslist":
            $query = "SELECT distinct(aicrm_errorslist.errorslistid),aicrm_crmentity.*, aicrm_errorslist.*, aicrm_errorslistcf.* 
            FROM aicrm_errorslist
            INNER JOIN aicrm_errorslistcf ON aicrm_errorslistcf.errorslistid = aicrm_errorslist.errorslistid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_errorslist.errorslistid
            LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_errorslist.jobid
            LEFT JOIN aicrm_errors ON aicrm_errors.errorsid = aicrm_errorslist.errorsid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;
                //product_id
            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Sparepart":
            $query = "SELECT distinct(aicrm_sparepart.sparepartid),aicrm_crmentity.*, aicrm_sparepart.*, aicrm_sparepartcf.* 
            FROM aicrm_sparepart
            INNER JOIN aicrm_sparepartcf ON aicrm_sparepartcf.sparepartid = aicrm_sparepart.sparepartid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_sparepart.sparepartid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Sparepartlist":
            $query = "SELECT distinct(aicrm_sparepartlist.sparepartlistid),aicrm_crmentity.*, aicrm_sparepartlist.*, aicrm_sparepartlistcf.* ,aicrm_jobs.*
            FROM aicrm_sparepartlist
            INNER JOIN aicrm_sparepartlistcf ON aicrm_sparepartlistcf.sparepartlistid = aicrm_sparepartlist.sparepartlistid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_sparepartlist.sparepartlistid
            LEFT JOIN aicrm_sparepart on aicrm_sparepart.sparepartid = aicrm_sparepartlist.sparepartid
            LEFT JOIN aicrm_jobs on aicrm_jobs.jobid = aicrm_sparepartlist.jobid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;
                //product_id
            if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "Job":
            $query = "SELECT distinct(aicrm_jobs.jobid),aicrm_crmentity.*, aicrm_jobs.*, aicrm_jobscf.* 
            FROM aicrm_jobs
            INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_jobs.accountid
            LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_jobs.product_id
            LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_jobs.contactid
            LEFT JOIN aicrm_servicerequest ON aicrm_servicerequest.servicerequestid = aicrm_jobs.servicerequestid
            LEFT JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_jobs.ticketid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Activitys":
            $query = "SELECT distinct(aicrm_activitys.activitysid),aicrm_crmentity.*, aicrm_activitys.*, aicrm_activityscf.*
            FROM aicrm_activitys
            INNER JOIN aicrm_activityscf ON aicrm_activityscf.activitysid = aicrm_activitys.activitysid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activitys.activitysid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_account on aicrm_account.accountid=aicrm_activitys.accountid
            LEFT JOIN aicrm_branch on aicrm_branch.branchid=aicrm_activitys.branchid
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Opportunity":
            $query = "SELECT distinct(aicrm_opportunity.opportunityid),aicrm_crmentity.*, aicrm_opportunity.*, aicrm_opportunitycf.*,aicrm_account.*,aicrm_products.*
            FROM aicrm_opportunity
            INNER JOIN aicrm_opportunitycf ON aicrm_opportunitycf.opportunityid = aicrm_opportunity.opportunityid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_opportunity.opportunityid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_account on aicrm_account.accountid=aicrm_opportunity.accountid
            LEFT JOIN aicrm_products on aicrm_products.productid=aicrm_opportunity.product_id
            LEFT JOIN aicrm_branch on aicrm_branch.branchid=aicrm_opportunity.branchid
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }

            break;

            Case "KnowledgeBase":
            $query = "SELECT distinct(aicrm_knowledgebase.knowledgebaseid),aicrm_crmentity.*, aicrm_knowledgebase.*, aicrm_knowledgebasecf.*
            FROM aicrm_knowledgebase
            INNER JOIN aicrm_knowledgebasecf ON aicrm_knowledgebasecf.knowledgebaseid = aicrm_knowledgebase.knowledgebaseid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_knowledgebase.knowledgebaseid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }

            break;

            Case "Quotation":
            $query = "SELECT distinct(aicrm_quotation.quotationid),aicrm_crmentity.*, aicrm_quotation.*, aicrm_quotationcf.* ,aicrm_building.*,aicrm_branch.*,aicrm_account.*,aicrm_products.*
            FROM aicrm_quotation
            INNER JOIN aicrm_quotationcf ON aicrm_quotationcf.quotationid = aicrm_quotation.quotationid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotation.quotationid
            LEFT JOIN aicrm_building ON aicrm_building.buildingid = aicrm_quotation.buildingid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotation.accountid
            LEFT JOIN aicrm_branch ON aicrm_branch.branchid = aicrm_quotation.branchid
            LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_quotation.product_id
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }

            break;

            Case "PriceList":
            $query = "SELECT distinct(aicrm_pricelists.pricelistid),aicrm_crmentity.*, aicrm_pricelists.*, aicrm_pricelistscf.*
            FROM aicrm_pricelists
            INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;

            }
            break;

            Case "SmartSms":
            $query = "SELECT distinct(aicrm_smartsms.smartsmsid),aicrm_crmentity.*, aicrm_smartsms.*, aicrm_smartsmscf.*
            FROM aicrm_smartsms
            INNER JOIN aicrm_smartsmscf ON aicrm_smartsmscf.smartsmsid = aicrm_smartsms.smartsmsid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Smartquestionnaire":
            $query = "SELECT distinct(aicrm_smartquestionnaire.smartquestionnaireid), aicrm_crmentity.*, aicrm_smartquestionnaire.*, aicrm_smartquestionnairecf.*
            FROM aicrm_smartquestionnaire
            INNER JOIN aicrm_smartquestionnairecf ON aicrm_smartquestionnairecf.smartquestionnaireid = aicrm_smartquestionnaire.smartquestionnaireid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartquestionnaire.smartquestionnaireid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 ".$where;
            if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "HelpDesk":
            $query = "SELECT aicrm_crmentity.crmid, aicrm_crmentity.smownerid, case when (aicrm_users.user_name not like '') then concat(aicrm_users.first_name,' ',aicrm_users.last_name) else aicrm_groups.groupname end as user_name, 
            aicrm_troubletickets.*, aicrm_ticketcf.* ,aicrm_contactdetails.contactid, aicrm_contactdetails.firstname,aicrm_contactdetails.lastname,
            aicrm_account.accountname, aicrm_account.accountid
            FROM aicrm_troubletickets
            INNER JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_contactdetails ON aicrm_troubletickets.contactid = aicrm_contactdetails.contactid
            LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_troubletickets.product_id
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id 
            WHERE aicrm_crmentity.deleted = 0 ".$where;

            if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
            {
                $sec_parameter=getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }

            break;

            Case "Accounts":
                //Query modified to sort by assigned to
            $query = "
            SELECT aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_account.*,aicrm_users.user_name, aicrm_accountscf.*
            FROM aicrm_account
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 
            " . $where;
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $query .= " AND (aicrm_crmentity.smownerid IN (" . $current_user->id . ")
                OR aicrm_crmentity.smownerid IN (
                SELECT aicrm_user2role.userid
                FROM aicrm_user2role
                INNER JOIN aicrm_users
                ON aicrm_users.id = aicrm_user2role.userid
                INNER JOIN aicrm_role
                ON aicrm_role.roleid = aicrm_user2role.roleid
                WHERE aicrm_role.parentrole LIKE '" . $current_user_parent_role_seq . "::%')
                OR aicrm_crmentity.smownerid IN (
                SELECT shareduserid
                FROM aicrm_tmp_read_user_sharing_per
                WHERE userid=" . $current_user->id . "
                AND tabid=" . $tab_id . ")
                OR (";

                if (sizeof($current_user_groups) > 0) {
                    $query .= " aicrm_groups.groupid IN (" . implode(",", getCurrentUserGroupList()) . ")
                    OR ";
                }
                $query .= " aicrm_groups.groupid IN (
                SELECT aicrm_tmp_read_group_sharing_per.sharedgroupid
                FROM aicrm_tmp_read_group_sharing_per
                WHERE userid=" . $current_user->id . "
                AND tabid=" . $tab_id . "))) ";
            }
            break;

            Case "Potentials":
                //Query modified to sort by assigned to
            $query = "SELECT aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
            aicrm_account.accountname,
            aicrm_potential.related_to, aicrm_potential.potentialname,
            aicrm_potential.sales_stage, aicrm_potential.amount,
            aicrm_potential.currency, aicrm_potential.closingdate,
            aicrm_potential.typeofrevenue,
            aicrm_potentialscf.*
            FROM aicrm_potential
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_potential.potentialid
            INNER JOIN aicrm_potentialscf ON aicrm_potentialscf.potentialid = aicrm_potential.potentialid
            LEFT JOIN aicrm_account ON aicrm_potential.related_to = aicrm_account.accountid
            LEFT JOIN aicrm_contactdetails ON aicrm_potential.related_to = aicrm_contactdetails.contactid
            LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_potential.campaignid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }

            break;

            Case "LeadManagement":
            $query = "SELECT aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
            aicrm_leadmanage.firstname, aicrm_leadmanage.lastname,
            aicrm_leadmanage.company, aicrm_leadmanageaddress.phone,
            aicrm_leadmanagesubdetail.website, aicrm_leadmanage.email,
            aicrm_leadmanagecf.*
            FROM aicrm_leadmanage
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leadmanage.leadid
            INNER JOIN aicrm_leadmanagesubdetail ON aicrm_leadmanagesubdetail.leadsubscriptionid = aicrm_leadmanage.leadid
            INNER JOIN aicrm_leadmanageaddress ON aicrm_leadmanageaddress.leadaddressid = aicrm_leadmanagesubdetail.leadsubscriptionid
            INNER JOIN aicrm_leadmanagecf ON aicrm_leadmanage.leadid = aicrm_leadmanagecf.leadid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0
            AND aicrm_leadmanage.converted = 0 " . $where;
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Leads":
            $query = "SELECT aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
            aicrm_leaddetails.*, aicrm_leadaddress.*,
            aicrm_leadsubdetails.*,aicrm_leadscf.*
            FROM aicrm_leaddetails
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
            INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
            INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
            INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid

            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_leaddetails.accountid
            -- LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_leaddetails.contactid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0
            " . $where;
                    //LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_leaddetails.campaignid
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Products":
            $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, aicrm_crmentity.crmid, aicrm_crmentity.description, aicrm_products.*, aicrm_productcf.*
            FROM aicrm_products
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
            INNER JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            -- LEFT JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id";


            if ((isset($_REQUEST["from_dashboard"]) && $_REQUEST["from_dashboard"] == true) && (isset($_REQUEST["type"]) && $_REQUEST["type"] == "dbrd")){
                $query .= " INNER JOIN aicrm_inventoryproductrel on aicrm_inventoryproductrel.productid = aicrm_products.productid";
            }

            $query .= " WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false){
                $sales_org = explode(' |##| ',$current_user->column_fields['sales_org']);
                $user_org = implode("','",$sales_org);
                // $query .= " and aicrm_products.sales_org in ('".$user_org."') ";
            }
            
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Documents":
            $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,aicrm_crmentity.crmid, aicrm_crmentity.modifiedtime,
            aicrm_crmentity.smownerid,aicrm_attachmentsfolder.*,aicrm_notes.*
            FROM aicrm_notes
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_notes.notesid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_attachmentsfolder ON aicrm_notes.folderid = aicrm_attachmentsfolder.folderid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 " . $where;
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Contacts":
                //Query modified to sort by assigned to
            $query = "SELECT aicrm_crmentity.smownerid, aicrm_crmentity.crmid,aicrm_contactscf.*,aicrm_contactdetails.*
            FROM aicrm_contactdetails
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
            INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
            INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
            INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status='Active'
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id";

            if ((isset($_REQUEST["from_dashboard"]) && $_REQUEST["from_dashboard"] == true) && (isset($_REQUEST["type"]) && $_REQUEST["type"] == "dbrd"))
                $query .= " INNER JOIN aicrm_campaigncontrel on aicrm_campaigncontrel.contactid = aicrm_contactdetails.contactid";
            $query .= " WHERE aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }

            break;

            Case "Calendar":
            $query = "SELECT aicrm_activity.activityid as act_id,aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.setype, aicrm_activity.*,aicrm_account.accountid, aicrm_account.accountname ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_activity.flag_send_report
            FROM aicrm_activity
            LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
            LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
            LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid 
            LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_activity.contactid 
            LEFT JOIN aicrm_competitor ON aicrm_competitor.competitorid = aicrm_activity.competitorid
            
            LEFT JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_activity.event_id
            LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_activity.event_id
            LEFT JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_activity.event_id
            LEFT JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_activity.event_id

            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
            WHERE aicrm_crmentity.deleted = 0 AND activitytype != 'Emails' " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "Emails":
            $query = "SELECT DISTINCT aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
            aicrm_activity.activityid, aicrm_activity.activitytype,
            aicrm_activity.date_start,
            aicrm_contactdetails.lastname, aicrm_contactdetails.firstname,
            aicrm_contactdetails.contactid
            FROM aicrm_activity
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_seactivityrel ON aicrm_seactivityrel.activityid = aicrm_activity.activityid
            LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_seactivityrel.crmid
            LEFT JOIN aicrm_cntactivityrel ON aicrm_cntactivityrel.activityid = aicrm_activity.activityid AND aicrm_cntactivityrel.contactid = aicrm_cntactivityrel.contactid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_salesmanactivityrel ON aicrm_salesmanactivityrel.activityid = aicrm_activity.activityid
            LEFT JOIN aicrm_emaildetails ON aicrm_emaildetails.emailid = aicrm_activity.activityid
            WHERE aicrm_activity.activitytype = 'Emails'
            AND aicrm_crmentity.deleted = 0 " . $where;

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
            break;

            Case "PriceBooks":
            $query = "SELECT aicrm_crmentity.crmid, aicrm_pricebook.*, aicrm_currency_info.currency_name
            FROM aicrm_pricebook
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricebook.pricebookid
            INNER JOIN aicrm_pricebookcf ON aicrm_pricebook.pricebookid = aicrm_pricebookcf.pricebookid
            LEFT JOIN aicrm_currency_info ON aicrm_pricebook.currency_id = aicrm_currency_info.id
            WHERE aicrm_crmentity.deleted = 0 " . $where;
            break;

            Case "Quotes":
                //Query modified to sort by assigned to
            $query = "SELECT aicrm_crmentity.*,
            aicrm_quotes.*,
            aicrm_quotesbillads.*,
            aicrm_quotesshipads.*,
            aicrm_account.accountname,
            aicrm_currency_info.currency_name
            FROM aicrm_quotes
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
            INNER JOIN aicrm_quotesbillads ON aicrm_quotes.quoteid = aicrm_quotesbillads.quotebilladdressid
            INNER JOIN aicrm_quotesshipads ON aicrm_quotes.quoteid = aicrm_quotesshipads.quoteshipaddressid
            LEFT JOIN aicrm_quotescf ON aicrm_quotes.quoteid = aicrm_quotescf.quoteid
            LEFT JOIN aicrm_currency_info ON aicrm_quotes.currency_id = aicrm_currency_info.id
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotes.accountid
            
            LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_quotes.campaignid
            LEFT JOIN aicrm_promotion ON aicrm_promotion.promotionid = aicrm_quotes.promotionid
            
            LEFT JOIN aicrm_projects ON aicrm_quotes.projectsid = aicrm_projects.projectsid
            LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_quotes.event_id
            LEFT JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_quotes.event_id
            LEFT JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_quotes.event_id

            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
            LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id

            LEFT JOIN aicrm_contactdetails ON aicrm_quotes.contactid = aicrm_contactdetails.contactid


            WHERE aicrm_crmentity.deleted = 0 " . $where;
                //LEFT JOIN aicrm_users as aicrm_usersQuotes ON aicrm_usersQuotes.id = aicrm_quotes.inventorymanager
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
                /*
                aicrm_potential.potentialname,
                LEFT OUTER JOIN aicrm_potential ON aicrm_potential.potentialid = aicrm_quotes.potentialid
                LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_quotes.contactid*/
                break;

                Case "Campaigns":
                //Query modified to sort by assigned to
                //query modified -Code contribute by Geoff(http://forums.vtiger.com/viewtopic.php?t=3376)
                $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*,
                aicrm_campaign.*
                FROM aicrm_campaign
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_campaign.campaignid
                INNER JOIN aicrm_campaignscf ON aicrm_campaign.campaignid = aicrm_campaignscf.campaignid
                LEFT JOIN aicrm_promotion ON aicrm_promotion.promotionid = aicrm_campaign.promotionid
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 " . $where;
                if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                    $sec_parameter = getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Faq":
                $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , distinct(aicrm_faq.faqid),aicrm_crmentity.*, aicrm_faq.*, aicrm_faqcf.*
                FROM aicrm_faq
                INNER JOIN aicrm_faqcf ON aicrm_faqcf.faqid = aicrm_faq.faqid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_faq.faqid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Plant":
                $query = "SELECT distinct(aicrm_plant.plantid), case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , aicrm_crmentity.*, aicrm_plant.*, aicrm_plantcf.*
                FROM aicrm_plant
                INNER JOIN aicrm_plantcf ON aicrm_plantcf.plantid = aicrm_plant.plantid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_plant.plantid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                // echo $query;
                break;
                Case "Order":
                $query = "SELECT distinct(aicrm_order.orderid) , case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , aicrm_crmentity.*, aicrm_order.*, aicrm_ordercf.*
                FROM aicrm_order
                INNER JOIN aicrm_ordercf ON aicrm_ordercf.orderid = aicrm_order.orderid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_order.orderid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_plant ON aicrm_plant.plantid = aicrm_order.plantid
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                //echo $query; exit;
                break;

                Case "Deal":
                $query = "SELECT distinct(aicrm_deal.dealid), case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , aicrm_crmentity.*, aicrm_deal.*, aicrm_dealcf.*
                FROM aicrm_deal
                INNER JOIN aicrm_dealcf ON aicrm_dealcf.dealid = aicrm_deal.dealid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_deal.dealid
                LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_deal.campaignid
                LEFT JOIN aicrm_promotion ON aicrm_promotion.promotionid = aicrm_deal.promotionid

                LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_deal.parentid
                LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_deal.parentid 
                LEFT JOIN aicrm_competitor ON aicrm_competitor.competitorid = aicrm_deal.competitorid
                
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Promotion":
                $query = "SELECT distinct(aicrm_promotion.promotionid),case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_promotion.*, aicrm_promotioncf.*
                FROM aicrm_promotion
                INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.promotionid = aicrm_promotion.promotionid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_promotion.promotionid
                LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_promotion.campaignid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                
                Case "Promotionvoucher":
                $query = "SELECT distinct(aicrm_promotionvoucher.promotionvoucherid),case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_promotionvoucher.*, aicrm_promotionvouchercf.*
                FROM aicrm_promotionvoucher
                INNER JOIN aicrm_promotionvouchercf ON aicrm_promotionvouchercf.promotionvoucherid = aicrm_promotionvoucher.promotionvoucherid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_promotionvoucher.promotionvoucherid
                LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_promotionvoucher.campaignid
                LEFT JOIN aicrm_promotionvouchercomments ON aicrm_promotionvouchercomments.promotionvoucherid = aicrm_promotionvoucher.promotionvoucherid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Voucher":
                $query = "SELECT distinct(aicrm_voucher.voucherid),case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_voucher.*, aicrm_vouchercf.*
                FROM aicrm_voucher
                INNER JOIN aicrm_vouchercf ON aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid
                LEFT JOIN aicrm_promotionvoucher ON aicrm_promotionvoucher.promotionvoucherid = aicrm_voucher.promotionvoucherid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Questionnaire":
                $query = "SELECT distinct(aicrm_questionnaire.questionnaireid),case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_questionnaire.*, aicrm_questionnairecf.*
                FROM aicrm_questionnaire
                INNER JOIN aicrm_questionnairecf ON aicrm_questionnairecf.questionnaireid = aicrm_questionnaire.questionnaireid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
                LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_questionnaire.accountid
                LEFT JOIN aicrm_questionnairetemplate ON aicrm_questionnairetemplate.questionnairetemplateid = aicrm_questionnaire.questionnairetemplateid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Questionnairetemplate":
                $query = "SELECT distinct(aicrm_questionnairetemplate.questionnairetemplateid),case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_questionnairetemplate.*, aicrm_questionnairetemplatecf.*
                FROM aicrm_questionnairetemplate
                INNER JOIN aicrm_questionnairetemplatecf ON aicrm_questionnairetemplatecf.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnairetemplate.questionnairetemplateid
                LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_questionnairetemplate.campaignid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Questionnaireanswer":
                $query = "SELECT distinct(aicrm_questionnaireanswer.questionnaireanswerid), aicrm_crmentity.*, aicrm_questionnaireanswer.*, aicrm_questionnaireanswercf.*
                FROM aicrm_questionnaireanswer
                INNER JOIN aicrm_questionnaireanswercf ON aicrm_questionnaireanswercf.questionnaireanswerid = aicrm_questionnaireanswer.questionnaireanswerid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnaireanswer.questionnaireanswerid
                LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_questionnaireanswer.accountid
                LEFT JOIN aicrm_questionnairetemplate ON aicrm_questionnairetemplate.questionnairetemplateid = aicrm_questionnaireanswer.questionnairetemplateid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                WHERE aicrm_crmentity.deleted = 0 ".$where;
                if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Announcement":
                $query = "SELECT distinct(aicrm_announcement.announcementid), case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_announcement.*, aicrm_announcementcf.*
                FROM aicrm_announcement
                INNER JOIN aicrm_announcementcf ON aicrm_announcementcf.announcementid = aicrm_announcement.announcementid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_announcement.announcementid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Redemption":
                $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , distinct(aicrm_redemption.redemptionid),aicrm_crmentity.*, aicrm_redemption.*, aicrm_redemptioncf.*
                FROM aicrm_redemption
                INNER JOIN aicrm_redemptioncf ON aicrm_redemptioncf.redemptionid = aicrm_redemption.redemptionid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_redemption.redemptionid
                LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_redemption.accountid
                LEFT JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_redemption.salesorderid
                LEFT JOIN aicrm_premuimproduct ON aicrm_premuimproduct.premuimproductid = aicrm_redemption.premuimproductid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Point":
                $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , distinct(aicrm_point.pointid),aicrm_crmentity.*, aicrm_point.*, aicrm_pointcf.*
                FROM aicrm_point
                INNER JOIN aicrm_pointcf ON aicrm_pointcf.pointid = aicrm_point.pointid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_point.pointid
                LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_point.accountid
                LEFT JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_point.salesorderid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;
                Case "Salesorder":
                $query = "SELECT distinct(aicrm_salesorder.salesorderid) ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_salesorder.*, aicrm_salesordercf.*
                FROM aicrm_salesorder
                INNER JOIN aicrm_salesordercf ON aicrm_salesordercf.salesorderid = aicrm_salesorder.salesorderid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesorder.salesorderid
                LEFT JOIN aicrm_account on aicrm_account.accountid = aicrm_salesorder.accountid
                LEFT JOIN aicrm_deal on aicrm_deal.dealid = aicrm_salesorder.dealid
                LEFT JOIN aicrm_quotes on aicrm_quotes.quoteid = aicrm_salesorder.quoteid
                LEFT JOIN aicrm_campaign on aicrm_campaign.campaignid = aicrm_salesorder.campaignid
                LEFT JOIN aicrm_promotion on aicrm_promotion.promotionid = aicrm_salesorder.promotionid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Salesinvoice":
                $query = "SELECT distinct(aicrm_salesinvoice.salesinvoiceid) ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_salesinvoice.*, aicrm_salesinvoicecf.*
                FROM aicrm_salesinvoice
                INNER JOIN aicrm_salesinvoicecf ON aicrm_salesinvoicecf.salesinvoiceid = aicrm_salesinvoice.salesinvoiceid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid
                LEFT JOIN aicrm_account on aicrm_account.accountid = aicrm_salesinvoice.accountid
                LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_salesinvoice.product_id
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Expense":
                $query = "SELECT distinct(aicrm_expense.expenseid) ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_expense.*, aicrm_expensecf.*
                FROM aicrm_expense
                INNER JOIN aicrm_expensecf ON aicrm_expensecf.expenseid = aicrm_expense.expenseid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_expense.expenseid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Purchasesorder":
                $query = "SELECT distinct(aicrm_purchasesorder.purchasesorderid) ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_purchasesorder.*, aicrm_purchasesordercf.*
                FROM aicrm_purchasesorder
                INNER JOIN aicrm_purchasesordercf ON aicrm_purchasesordercf.purchasesorderid = aicrm_purchasesorder.purchasesorderid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_purchasesorder.purchasesorderid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Samplerequisition":
                $query = "SELECT distinct(aicrm_samplerequisition.samplerequisitionid) ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_samplerequisition.*, aicrm_samplerequisitioncf.*
                FROM aicrm_samplerequisition
                INNER JOIN aicrm_samplerequisitioncf ON aicrm_samplerequisitioncf.samplerequisitionid = aicrm_samplerequisition.samplerequisitionid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_samplerequisition.samplerequisitionid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Goodsreceive":
                $query = "SELECT distinct(aicrm_goodsreceive.goodsreceiveid) ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_goodsreceive.*, aicrm_goodsreceivecf.*
                FROM aicrm_goodsreceive
                INNER JOIN aicrm_goodsreceivecf ON aicrm_goodsreceivecf.goodsreceiveid = aicrm_goodsreceive.goodsreceiveid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_goodsreceive.goodsreceiveid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Marketingtools":
                $query = "SELECT distinct(aicrm_marketingtools.marketingtoolsid) ,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,aicrm_crmentity.*, aicrm_marketingtools.*, aicrm_marketingtoolscf.*
                FROM aicrm_marketingtools
                INNER JOIN aicrm_marketingtoolscf ON aicrm_marketingtoolscf.marketingtoolsid = aicrm_marketingtools.marketingtoolsid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_marketingtools.marketingtoolsid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;

                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;
                }
                break;

                Case "Users":
                $query = "select id,user_name,roleid,first_name,last_name,email1,phone_mobile,phone_work,is_admin,status,section,position
                from aicrm_users
                inner join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id
                where deleted=0 " . $where;
                break;


                Case "Tools":
                $query = "SELECT distinct(aicrm_tools.toolsid),aicrm_crmentity.*, aicrm_tools.*, aicrm_toolscf.* 
                FROM aicrm_tools
                INNER JOIN aicrm_toolscf ON aicrm_toolscf.toolsid = aicrm_tools.toolsid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_tools.toolsid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 ".$where;
                //product_id
                if($is_admin==false && $profileGlobalPermission[1] == 1&& $profileGlobalPermission[2] == 1&& $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $sec_parameter=getListViewSecurityParameter($module);
                    $query .= $sec_parameter;

                }
                break;

                default:
                // vtlib customization: Include the module file
                $focus = CRMEntity::getInstance($module);
                $query = $focus->getListQuery($module, $where);
            // END
            }

        $log->debug("Exiting getListQuery method ...");
        return $query;
    }

    /**Function returns the list of records which an user is entiled to view
     *Param $module - module name
     *Returns a database query - type string
     */

    function getReadEntityIds($module)
    {
        global $log;
        $log->debug("Entering getReadEntityIds(" . $module . ") method ...");
        global $current_user;
        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        require('user_privileges/sharing_privileges_' . $current_user->id . '.php');
        $tab_id = getTabid($module);

        if ($module == "Leads") {
            $query = "SELECT aicrm_crmentity.crmid
            FROM aicrm_leaddetails
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0
            AND aicrm_leaddetails.converted = 0 ";
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
        }
        if ($module == "LeadManagement") {
            $query = "SELECT aicrm_crmentity.crmid
            FROM aicrm_leadmanage
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leadmanage.leadid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0
            AND aicrm_leadmanage.converted = 0 ";
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
        }
        if ($module == "Accounts") {
            //Query modified to sort by assigned to
            $query = "SELECT aicrm_crmentity.crmid
            FROM aicrm_account
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 ";
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }


        }

        if ($module == "Potentials") {
            //Query modified to sort by assigned to
            $query = "SELECT aicrm_crmentity.crmid
            FROM aicrm_potential
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_potential.potentialid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 ";

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }


        }

        if ($module == "Contacts") {
            //Query modified to sort by assigned to
            $query = "SELECT aicrm_crmentity.crmid
            FROM aicrm_contactdetails
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 ";

            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
        }

        if ($module == "Products") {
            $query = "SELECT DISTINCT aicrm_crmentity.crmid
            FROM aicrm_products
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
            LEFT JOIN aicrm_seproductsrel ON aicrm_seproductsrel.productid = aicrm_products.productid
            WHERE aicrm_crmentity.deleted = 0
            AND (aicrm_seproductsrel.crmid IS NULL
            OR aicrm_seproductsrel.crmid IN (" . getReadEntityIds('Leads') . ")
            OR aicrm_seproductsrel.crmid IN (" . getReadEntityIds('Accounts') . ")
            OR aicrm_seproductsrel.crmid IN (" . getReadEntityIds('Potentials') . ")
            OR aicrm_seproductsrel.crmid IN (" . getReadEntityIds('Contacts') . ")) ";
        }

        if ($module == "Quotes") {
            $query = "SELECT aicrm_crmentity.crmid
            FROM aicrm_quotes
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 " . $where;
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
        }

        if ($module == "HelpDesk") {
            $query = "SELECT aicrm_crmentity.crmid
            FROM aicrm_troubletickets
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_crmentity.deleted = 0 " . $where;
            if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
                $sec_parameter = getListViewSecurityParameter($module);
                $query .= $sec_parameter;
            }
        }

        $log->debug("Exiting getReadEntityIds method ...");
        return $query;

    }

    /** Function to get alphabetical search links
     *Param $module - module name
     *Param $action - action
     *Param $fieldname - aicrm_field name
     *Param $query - query
     *Param $type - search type
     *Param $popuptype - popup type
     *Param $recordid - record id
     *Param $return_module - return module
     *Param $append_url - url string to be appended
     *Param $viewid - custom view id
     *Param $groupid - group id
     *Returns an string value
     */
    function AlphabeticalSearch($module, $action, $fieldname, $query, $type, $popuptype = '', $recordid = '', $return_module = '', $append_url = '', $viewid = '', $groupid = '')
    {
        global $log;

        $log->debug("Entering AlphabeticalSearch(" . $module . "," . $action . "," . $fieldname . "," . $query . "," . $type . "," . $popuptype . "," . $recordid . "," . $return_module . "," . $append_url . "," . $viewid . "," . $groupid . ") method ...");
        if ($type == 'advanced')
            $flag = '&advanced=true';

        if ($popuptype != '')
            $popuptypevalue = "&popuptype=" . $popuptype;

        if ($recordid != '')
            $returnvalue = '&recordid=' . $recordid;
        if ($return_module != '')
            $returnvalue .= '&return_module=' . $return_module;

        // vtlib Customization : For uitype 10 popup during paging
        if ($_REQUEST['form'] == 'vtlibPopupView') {
            $returnvalue .= '&form=vtlibPopupView&forfield=' . vtlib_purify($_REQUEST['forfield']) . '&srcmodule=' . vtlib_purify($_REQUEST['srcmodule']) . '&forrecord=' . vtlib_purify($_REQUEST['forrecord']);
        }
        // END

        for ($var = 'A', $i = 1; $i <= 26; $i++, $var++)
            // Mike Crowe Mod --------------------------------------------------------added groupid to url
            $list .= '<td class="searchAlph" id="alpha_' . $i . '" align="center" onClick=\'alphabetic("' . $module . '","gname=' . $groupid . '&query=' . $query . '&search_field=' . $fieldname . '&searchtype=BasicSearch&type=alpbt&search_text=' . $var . $flag . $popuptypevalue . $returnvalue . $append_url . '","alpha_' . $i . '")\'>' . $var . '</td>';

        $log->debug("Exiting AlphabeticalSearch method ...");
        //echo $list;
        return $list;
    }

    /**Function to get parent name for a given parent id
     *Param $module - module name
     *Param $list_result- result set
     *Param $rset - result set index
     *Returns an string value
     */
    function getRelatedToEntity($module, $list_result, $rset)
    {
        global $log;
        $log->debug("Entering getRelatedToEntity(" . $module . "," . $list_result . "," . $rset . ") method ...");

        global $adb;
        $seid = $adb->query_result($list_result, $rset, "relatedto");
        $action = "DetailView";

        if (isset($seid) && $seid != '') {
            $parent_module = $parent_module = getSalesEntityType($seid);
            if ($parent_module == 'Accounts') {
                $numrows = $adb->num_rows($evt_result);

                $parent_module = $adb->query_result($evt_result, 0, 'setype');
                $parent_id = $adb->query_result($evt_result, 0, 'crmid');

                if ($numrows > 1) {
                    $parent_module = 'Multiple';
                    $parent_name = $app_strings['LBL_MULTIPLE'];
                }
                //Raju -- Ends
                $parent_query = "SELECT accountname FROM aicrm_account WHERE accountid=?";
                $parent_result = $adb->pquery($parent_query, array($seid));
                $parent_name = $adb->query_result($parent_result, 0, "accountname");
            }
            if ($parent_module == 'LeadManagement') {
                $parent_query = "SELECT firstname,lastname FROM aicrm_leadmanage WHERE leadid=?";
                $parent_result = $adb->pquery($parent_query, array($seid));
                $parent_name = getFullNameFromQResult($parent_result, 0, "LeaLeadManagementds");
            }
            if ($parent_module == 'Leads') {
                $parent_query = "SELECT firstname,lastname FROM aicrm_leaddetails WHERE leadid=?";
                $parent_result = $adb->pquery($parent_query, array($seid));
                $parent_name = getFullNameFromQResult($parent_result, 0, "Leads");
            }
            if ($parent_module == 'Potentials') {
                $parent_query = "SELECT potentialname FROM aicrm_potential WHERE potentialid=?";
                $parent_result = $adb->pquery($parent_query, array($seid));
                $parent_name = $adb->query_result($parent_result, 0, "potentialname");
            }
            if ($parent_module == 'Products') {
                $parent_query = "SELECT productname FROM aicrm_products WHERE productid=?";
                $parent_result = $adb->pquery($parent_query, array($seid));
                $parent_name = $adb->query_result($parent_result, 0, "productname");
            }
            $parent_value = "<a href='index.php?module=" . $parent_module . "&action=" . $action . "&record=" . $seid . "'>" . $parent_name . "</a>";
        } else {
            $parent_value = '';
        }
        $log->debug("Exiting getRelatedToEntity method ...");
        return $parent_value;

    }

    /**Function to get parent name for a given parent id
     *Param $module - module name
     *Param $list_result- result set
     *Param $rset - result set index
     *Returns an string value
     */

//used in home page listTop aicrm_files
    function getRelatedTo($module, $list_result, $rset)
    {
        global $adb, $log, $app_strings;
        $log->debug("Entering getRelatedTo(" . $module . "," . $list_result . "," . $rset . ") method ...");
        $tabname = getParentTab();
        if ($module == "Documents") {
            $notesid = $adb->query_result($list_result, $rset, "notesid");
            $action = "DetailView";
            $evt_query = "SELECT aicrm_senotesrel.crmid, aicrm_crmentity.setype
            FROM aicrm_senotesrel
            INNER JOIN aicrm_crmentity
            ON  aicrm_senotesrel.crmid = aicrm_crmentity.crmid
            WHERE aicrm_senotesrel.notesid = ?";
            $params = array($notesid);
        } else if ($module == "Products") {
            $productid = $adb->query_result($list_result, $rset, "productid");
            $action = "DetailView";
            $evt_query = "SELECT aicrm_seproductsrel.crmid, aicrm_crmentity.setype
            FROM aicrm_seproductsrel
            INNER JOIN aicrm_crmentity
            ON aicrm_seproductsrel.crmid = aicrm_crmentity.crmid
            WHERE aicrm_seproductsrel.productid =?";
            $params = array($productid);
        } else {
            $activity_id = $adb->query_result($list_result, $rset, "activityid");
            $action = "DetailView";
            $evt_query = "SELECT aicrm_seactivityrel.crmid, aicrm_crmentity.setype
            FROM aicrm_seactivityrel
            INNER JOIN aicrm_crmentity
            ON  aicrm_seactivityrel.crmid = aicrm_crmentity.crmid
            WHERE aicrm_seactivityrel.activityid=?";
            $params = array($activity_id);

            if ($module == 'HelpDesk') {
                $activity_id = $adb->query_result($list_result, $rset, "parent_id");
                if ($activity_id != '') {
                    $evt_query = "SELECT crmid, setype FROM aicrm_crmentity WHERE crmid=?";
                    $params = array($activity_id);
                }
            }
        }
        //added by raju to change the related to in emails inot multiple if email is for more than one contact
        $evt_result = $adb->pquery($evt_query, $params);
        $numrows = $adb->num_rows($evt_result);

        $parent_module = $adb->query_result($evt_result, 0, 'setype');
        $parent_id = $adb->query_result($evt_result, 0, 'crmid');


        if ($numrows > 1) {
            $parent_module = 'Multiple';
            $parent_name = $app_strings['LBL_MULTIPLE'];
        }
        //Raju -- Ends
        if ($module == 'HelpDesk' && ($parent_module == 'Accounts' || $parent_module == 'Contacts')) {
            global $theme;
            $module_icon = '<img src="themes/images/' . $parent_module . '.gif" alt="' . $app_strings[$parent_module] . '" title="' . $app_strings[$parent_module] . '" border=0 align=center> ';
        }

        $action = "DetailView";
        if ($parent_module == 'Accounts') {
            $parent_query = "SELECT accountname FROM aicrm_account WHERE accountid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = textlength_check($adb->query_result($parent_result, 0, "accountname"));
        }
        if ($parent_module == 'Leads') {
            $parent_query = "SELECT firstname,lastname FROM aicrm_leaddetails WHERE leadid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = getFullNameFromQResult($parent_result, 0, "Leads");
        }
        if ($parent_module == 'LeadManagement') {
            $parent_query = "SELECT firstname,lastname FROM aicrm_leadmanage WHERE leadid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = getFullNameFromQResult($parent_result, 0, "LeadManagement");
        }
        if ($parent_module == 'Potentials') {
            $parent_query = "SELECT potentialname FROM aicrm_potential WHERE potentialid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = textlength_check($adb->query_result($parent_result, 0, "potentialname"));
        }
        if ($parent_module == 'Products') {
            $parent_query = "SELECT productname FROM aicrm_products WHERE productid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = $adb->query_result($parent_result, 0, "productname");
        }
        if ($parent_module == 'Quotes') {
            $parent_query = "SELECT subject FROM aicrm_quotes WHERE quoteid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = $adb->query_result($parent_result, 0, "subject");
        }
        if ($parent_module == 'Contacts' && ($module == 'Emails' || $module == 'HelpDesk')) {
            $parent_query = "SELECT firstname,lastname FROM aicrm_contactdetails WHERE contactid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = getFullNameFromQResult($parent_result, 0, "Contacts");
        }
        if ($parent_module == 'HelpDesk') {
            $parent_query = "SELECT title FROM aicrm_troubletickets WHERE ticketid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = $adb->query_result($parent_result, 0, "title");
            $parent_name = textlength_check($parent_name);
        }
        if ($parent_module == 'Campaigns') {
            $parent_query = "SELECT campaignname FROM aicrm_campaign WHERE campaignid=?";
            $parent_result = $adb->pquery($parent_query, array($parent_id));
            $parent_name = $adb->query_result($parent_result, 0, "campaignname");
            $parent_name = textlength_check($parent_name);
        }

        //added by rdhital for better emails - Raju
        if ($parent_module == 'Multiple') {
            $parent_value = $parent_name;
        } else {
            $parent_value = $module_icon . "<a href='index.php?module=" . $parent_module . "&action=" . $action . "&record=" . $parent_id . "&parenttab=" . $tabname . "'>" . textlength_check($parent_name) . "</a>";
        }
        //code added by raju ends
        $log->debug("Exiting getRelatedTo method ...");
        return $parent_value;

    }

    /**Function to get the table headers for a listview
     *Param $navigation_arrray - navigation values in array
     *Param $url_qry - url string
     *Param $module - module name
     *Param $action- action file name
     *Param $viewid - view id
     *Returns an string value
     */

    function getTableHeaderNavigation($navigation_array, $url_qry, $module = '', $action_val = 'index', $viewid = '')
    {
        //echo $url_string;
        global $log, $app_strings;
        $log->debug("Entering getTableHeaderNavigation(" . $navigation_array . "," . $url_qry . "," . $module . "," . $action_val . "," . $viewid . ") method ...");
        global $theme, $current_user;
        $theme_path = "themes/" . $theme . "/";
        $image_path = $theme_path . "images/";
        if ($module == 'Documents') {
            $output = '<td class="mailSubHeader" width="100%" align="center">';
        } else {
            $output = '<td align="right" style="padding: 5px;">';
        }
        $tabname = getParentTab();
        //echo $module;
        $url_string = '';

        // vtlib Customization : For uitype 10 popup during paging
        if ($_REQUEST['form'] == 'vtlibPopupView') {
            $url_string .= '&form=vtlibPopupView&forfield=' . vtlib_purify($_REQUEST['forfield']) . '&srcmodule=' . vtlib_purify($_REQUEST['srcmodule']) . '&forrecord=' . vtlib_purify($_REQUEST['forrecord']);
        }
        // END

        if ($module == 'Calendar' && $action_val == 'index') {
            if ($_REQUEST['view'] == '') {
                if ($current_user->activity_view == "This Year") {
                    $mysel = 'year';
                } else if ($current_user->activity_view == "This Month") {
                    $mysel = 'month';
                } else if ($current_user->activity_view == "This Week") {
                    $mysel = 'week';
                } else {
                    $mysel = 'day';
                }
            }
            $data_value = date('Y-m-d H:i:s');
            preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $data_value, $value);
            $date_data = Array(
                'day' => $value[3],
                'month' => $value[2],
                'year' => $value[1],
                'hour' => $value[4],
                'min' => $value[5],
            );
            $tab_type = ($_REQUEST['subtab'] == '') ? 'event' : vtlib_purify($_REQUEST['subtab']);
            $url_string .= isset($_REQUEST['view']) ? "&view=" . vtlib_purify($_REQUEST['view']) : "&view=" . $mysel;
            $url_string .= isset($_REQUEST['subtab']) ? "&subtab=" . vtlib_purify($_REQUEST['subtab']) : '';
            $url_string .= isset($_REQUEST['viewOption']) ? "&viewOption=" . vtlib_purify($_REQUEST['viewOption']) : '&viewOption=listview';
            $url_string .= isset($_REQUEST['day']) ? "&day=" . vtlib_purify($_REQUEST['day']) : '&day=' . $date_data['day'];
            $url_string .= isset($_REQUEST['week']) ? "&week=" . vtlib_purify($_REQUEST['week']) : '';
            $url_string .= isset($_REQUEST['month']) ? "&month=" . vtlib_purify($_REQUEST['month']) : '&month=' . $date_data['month'];
            $url_string .= isset($_REQUEST['year']) ? "&year=" . vtlib_purify($_REQUEST['year']) : "&year=" . $date_data['year'];
            $url_string .= isset($_REQUEST['n_type']) ? "&n_type=" . vtlib_purify($_REQUEST['n_type']) : '';
            $url_string .= isset($_REQUEST['search_option']) ? "&search_option=" . vtlib_purify($_REQUEST['search_option']) : '';
        }
        if ($module == 'Calendar' && $action_val != 'index') //added for the All link from the homepage -- ticket 5211
        $url_string .= isset($_REQUEST['from_homepage']) ? "&from_homepage=" . vtlib_purify($_REQUEST['from_homepage']) : '';

        if ($url_qry != "") {
            $url_string .= $url_qry;
        }
        //echo $navigation_array['prev'];
        if (($navigation_array['prev']) != 0) {
            if ($module == 'Calendar' && $action_val == 'index') {

                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=1\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';

                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=' . $navigation_array['prev'] . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else if ($action_val == "FindDuplicate") {
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($action_val == 'UnifiedSearch') {
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($module == 'Documents') {
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '&folderid=' . $action_val . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else {
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            }
        } else {
            $output .= '<img src="' . aicrm_imageurl('start_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
            $output .= '<img src="' . aicrm_imageurl('previous_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
        }

        if ($module == 'Calendar' && $action_val == 'index') {
            $jsNavigate = "cal_navigation('$tab_type','$url_string','&start='+this.value);";
        } else if ($action_val == "FindDuplicate") {
            $jsNavigate = "getDuplicateListViewEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string');";
        } elseif ($action_val == 'UnifiedSearch') {
            $jsNavigate = "getUnifiedSearchEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string');";
        } elseif ($module == 'Documents') {
            $jsNavigate = "getListViewEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string&folderid=$action_val');";
        } else {
            $jsNavigate = "getListViewEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string');";
        }
        if ($module == 'Documents') {
            $url = '&folderid=' . $action_val;
        } else {
            $url = '';
        }
        $jsHandler = "return VT_disableFormSubmit(event);";
        $output .= "<input class='small' name='pagenum' type='text' value='{$navigation_array['current']}'
        style='width: 3em;margin-right: 0.7em;border-radius:3px;border: 1px solid #000000;' onchange=\"$jsNavigate\"
        onkeypress=\"$jsHandler\">";
        $output .= "<span name='" . $module . "_listViewCountContainerName' class='small' style='white-space:nowrap;margin-right:0.3em;'>";
        $output .= $app_strings['LBL_LIST_OF'] . ' ' . $navigation_array['verylast'] . '</span>';

        if (($navigation_array['next']) != 0) {
            if ($module == 'Calendar' && $action_val == 'index') {

                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=' . $navigation_array['next'] . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=' . $navigation_array['verylast'] . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else if ($action_val == "FindDuplicate") {
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($action_val == 'UnifiedSearch') {
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="themes/images/end.gif" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($module == 'Documents') {
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '&folderid=' . $action_val . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '&folderid=' . $action_val . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else {//echo $url_string;
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            }
        } else {
            $output .= '<img src="' . aicrm_imageurl('next_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
            $output .= '<img src="' . aicrm_imageurl('end_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
        }
        $output .= '</td>';
        $log->debug("Exiting getTableHeaderNavigation method ...");
        if ($navigation_array['first'] == '')
            return;
        else

            return $output;
    }

    function getPopupCheckquery($current_module, $relmodule, $relmod_recordid)
    {
        global $log, $adb;
        $log->debug("Entering getPopupCheckquery(" . $current_module . "," . $relmodule . "," . $relmod_recordid . ") method ...");
        if ($current_module == "Contacts") {

            /*if ($relmodule == "Accounts" && $relmod_recordid != '')
            $condition = "AND (aicrm_crmentityrel.crmid = '".$relmod_recordid."' OR aicrm_crmentityrel.relcrmid = '".$relmod_recordid."')";*/

            if ($relmodule == "Potentials") {
                $query = "select contactid from aicrm_contpotentialrel where potentialid=?";
                $result = $adb->pquery($query, array($relmod_recordid));
                $contact_id = $adb->query_result($result, 0, "contactid");
                if ($contact_id != '' && $contact_id != 0)
                    $condition = "and aicrm_contactdetails.contactid= " . $contact_id;
                else {
                    $query = "select related_to from aicrm_potential where potentialid=?";
                    $result = $adb->pquery($query, array($relmod_recordid));
                    $acc_id = $adb->query_result($result, 0, "related_to");
                    if ($acc_id != '') {
                        $condition = "and aicrm_contactdetails.accountid= " . $acc_id;
                    }
                }
            } elseif ($relmodule == "Quotes") {

                $query = "select accountid,contactid from aicrm_quotes where quoteid=?";
                $result = $adb->pquery($query, array($relmod_recordid));
                $contactid = $adb->query_result($result, 0, "contactid");
                if ($contactid != '' && $contactid != 0)
                    $condition = "and aicrm_contactdetails.contactid= " . $contactid;
                else {
                    $account_id = $adb->query_result($result, 0, "accountid");
                    if ($account_id != '')
                        $condition = "and aicrm_contactdetails.accountid= " . $account_id;
                }
            } elseif ($relmodule == "Campaigns") {
                $query = "select contactid from aicrm_campaigncontrel where campaignid =?";
                $result = $adb->pquery($query, array($relmod_recordid));
                $rows = $adb->num_rows($result);
                if ($rows != 0) {
                    $j = 0;
                    $contactid_comma = "(";
                    for ($k = 0; $k < $rows; $k++) {
                        $contactid = $adb->query_result($result, $k, 'contactid');
                        $contactid_comma .= $contactid;
                        if ($k < ($rows - 1))
                            $contactid_comma .= ', ';
                    }
                    $contactid_comma .= ")";
                } else
                $contactid_comma = "(0)";
                $condition = "and aicrm_contactdetails.contactid in " . $contactid_comma;
            } elseif ($relmodule == "Products") {
                $query = "select crmid from aicrm_seproductsrel where productid=? and setype=?";
                $result = $adb->pquery($query, array($relmod_recordid, "Contacts"));
                $rows = $adb->num_rows($result);
                if ($rows != 0) {
                    $j = 0;
                    $contactid_comma = "(";
                    for ($k = 0; $k < $rows; $k++) {
                        $contactid = $adb->query_result($result, $k, 'crmid');
                        $contactid_comma .= $contactid;
                        if ($k < ($rows - 1))
                            $contactid_comma .= ', ';
                    }
                    $contactid_comma .= ")";
                } else
                $contactid_comma = "(0)";
                $condition = "and aicrm_contactdetails.contactid in " . $contactid_comma;
            } elseif ($relmodule == "HelpDesk" || $relmodule == "Case") {
                $query = "select parent_id from aicrm_troubletickets where ticketid =?";
                $result = $adb->pquery($query, array($relmod_recordid));
                $parent_id = $adb->query_result($result, 0, "parent_id");
                if ($parent_id != "") {
                    $crmquery = "select setype from aicrm_crmentity where crmid=?";
                    $parentmodule_id = $adb->pquery($crmquery, array($parent_id));
                    $parent_modname = $adb->query_result($parentmodule_id, 0, "setype");
                    if ($parent_modname == "Accounts")
                        $condition = "and aicrm_contactdetails.accountid= " . $parent_id;
                    if ($parent_modname == "Contacts")
                        $condition = "and aicrm_contactdetails.contactid= " . $parent_id;
                } else
                $condition = " and aicrm_contactdetails.contactid=0";

            }
        } elseif ($current_module == "Potentials") {
            if ($relmodule == 'Accounts' || $relmodule == 'Contacts') {
                if ($relmodule == 'Contacts') {
                    $pot_query = "select aicrm_crmentity.crmid,aicrm_contactdetails.contactid,aicrm_potential.potentialid from aicrm_potential inner join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_potential.related_to inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid where aicrm_crmentity.deleted=0 and aicrm_potential.related_to=?";
                } else {
                    $pot_query = "select aicrm_crmentity.crmid,aicrm_account.accountid,aicrm_potential.potentialid from aicrm_potential inner join aicrm_account on aicrm_account.accountid=aicrm_potential.related_to inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid where aicrm_crmentity.deleted=0 and aicrm_potential.related_to=?";
                }
                $pot_result = $result = $adb->pquery($pot_query, array($relmod_recordid));
                $rows = $adb->num_rows($pot_result);
                $potids_comma = "";
                if ($rows != 0) {
                    $j = 0;
                    $potids_comma .= "(";
                    for ($k = 0; $k < $rows; $k++) {
                        $potential_ids = $adb->query_result($pot_result, $k, 'potentialid');
                        $potids_comma .= $potential_ids;
                        if ($k < ($rows - 1))
                            $potids_comma .= ',';
                    }
                    $potids_comma .= ")";
                } else
                $potids_comma = "(0)";
                $condition = "and aicrm_potential.potentialid in " . $potids_comma;
            }
        } else if ($current_module == "Products") {

        } else if ($current_module == 'Quotes') {
            if ($relmodule == 'Accounts') {
                $quote_query = "select quoteid from aicrm_quotes where accountid=?";
                $quote_result = $result = $adb->pquery($quote_query, array($relmod_recordid));
                $rows = $adb->num_rows($quote_result);
                if ($rows != 0) {
                    $j = 0;
                    $qtids_comma = "(";
                    for ($k = 0; $k < $rows; $k++) {
                        $quote_ids = $adb->query_result($quote_result, $k, 'quoteid');
                        $qtids_comma .= $quote_ids;
                        if ($k < ($rows - 1))
                            $qtids_comma .= ',';
                    }
                    $qtids_comma .= ")";
                } else
                $qtids_comma = "(0)";
                $condition = "and aicrm_quotes.quoteid in " . $qtids_comma;
            }
        } else
        $condition = '';
        $where = $condition;
        $log->debug("Exiting getPopupCheckquery method ...");
        return $where;
    }

    /**This function return the entity ids that need to be excluded in popup listview for a given record
     * Param $currentmodule - modulename of the entity to be selected
     * Param $returnmodule - modulename for which the entity is assingned
     * Param $recordid - the record id for which the entity is assigned
     * Return type string.
     */

    function getRelCheckquery($currentmodule, $returnmodule, $recordid)
    {
        global $log, $adb;
        $log->debug("Entering getRelCheckquery(" . $currentmodule . "," . $returnmodule . "," . $recordid . ") method ...");
        $skip_id = Array();
        $where_relquery = "";
        $params = array();

        if ($currentmodule == "Contacts" && $returnmodule == "Potentials") {
            $reltable = 'aicrm_contpotentialrel';
            $condition = 'WHERE potentialid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'contactid';
            $table = 'aicrm_contactdetails';
        } elseif ($currentmodule == "Contacts" && $returnmodule == "SmartSms") {
            $reltable = 'aicrm_smartsms_contactsrel';
            $condition = 'WHERE smartsmsid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'contactid';
            $table = 'aicrm_contactdetails';
        } elseif ($currentmodule == "Users" && $returnmodule == "SmartSms") {
            $reltable = 'aicrm_smartsms_usersrel';
            $condition = 'WHERE smartsmsid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'id';
            $table = 'aicrm_users';
        } elseif ($currentmodule == "Opportunity" && $returnmodule == "SmartSms") {
            $reltable = 'aicrm_smartsms_opportunityrel';
            $condition = 'WHERE smartsmsid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'opportunityid';
            $table = 'aicrm_opportunity';
        } elseif ($currentmodule == "Leads" && $returnmodule == "SmartSms") {
            $reltable = 'aicrm_smartsms_leadsrel';
            $condition = 'WHERE smartsmsid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'leadid';
            $table = 'aicrm_leaddetails';
        } elseif ($currentmodule == "Accounts" && $returnmodule == "SmartSms") {
            $reltable = 'aicrm_smartsms_accountsrel';
            $condition = 'WHERE smartsmsid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'accountid';
            $table = 'aicrm_account';
        /*} elseif ($currentmodule == "Leads" && $returnmodule == "Campaigns") {
            $reltable = 'aicrm_campaignleadrel';
            $condition = 'WHERE campaignid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'leadid';
            $table = 'aicrm_leaddetails';*/
        } elseif ($currentmodule == "Users" && $returnmodule == "Calendar") {
            $reltable = 'aicrm_salesmanactivityrel';
            $condition = 'WHERE activityid = ?';
            array_push($params, $recordid);
            $selectfield = 'smid';
            $field = 'id';
            $table = 'aicrm_users';
        /*} elseif ($currentmodule == "Campaigns" && $returnmodule == "Leads") {
            $reltable = 'aicrm_campaignleadrel';
            $condition = 'WHERE leadid = ?';
            array_push($params, $recordid);
            $field = $selectfield = 'campaignid';
            $table = 'aicrm_campaign';*/
        } elseif ($currentmodule == "Products" && ($returnmodule == "Potentials" || $returnmodule == "Accounts" || $returnmodule == "Contacts" || $returnmodule == "Leads")) {
            $reltable = 'aicrm_seproductsrel';
            $condition = 'WHERE crmid = ? and setype = ?';
            array_push($params, $recordid, $returnmodule);
            $field = $selectfield = 'productid';
            $table = 'aicrm_products';
        } elseif (($currentmodule == "Leads" || $currentmodule == "Accounts" || $currentmodule == "Potentials" || $currentmodule == "Contacts") && $returnmodule == "Products")//added to fix the issues(ticket 4001,4002 and 4003)
        {
            $reltable = 'aicrm_seproductsrel';
            $condition = 'WHERE productid = ? and setype = ?';
            array_push($params, $recordid, $currentmodule);
            $selectfield = 'crmid';
            if ($currentmodule == "Leads") {
                $field = 'leadid';
                $table = 'aicrm_leaddetails';
            } elseif ($currentmodule == "Accounts") {
                $field = 'accountid';
                $table = 'aicrm_account';
            } elseif ($currentmodule == "Contacts") {
                $field = 'contactid';
                $table = 'aicrm_contactdetails';
            } elseif ($currentmodule == "Potentials") {
                $field = 'potentialid';
                $table = 'aicrm_potential';
            }
        } elseif ($currentmodule == "Documents") {
            $reltable = "aicrm_senotesrel";
            $selectfield = "notesid";
            $condition = "where crmid = ?";
            array_push($params, $recordid);
            $table = "aicrm_notes";
            $field = "notesid";
        }
        //end
        if ($reltable != null) {
            $query = "SELECT " . $selectfield . " FROM " . $reltable . " " . $condition;
        } elseif ($currentmodule != $returnmodule && $returnmodule != "") { // If none of the above relation matches, then the relation is assumed to be stored in aicrm_crmentityrel
            $query = "SELECT relcrmid AS relatedid FROM aicrm_crmentityrel WHERE  crmid = ? and module = ? and relmodule = ?
            UNION SELECT crmid AS relatedid FROM aicrm_crmentityrel WHERE relcrmid = ? and relmodule = ? and module = ?";
            array_push($params, $recordid, $returnmodule, $currentmodule, $recordid, $returnmodule, $currentmodule);

            $focus_obj = CRMEntity::getInstance($currentmodule);
            $field = $focus_obj->table_index;
            $table = $focus_obj->table_name;
            $selectfield = 'relatedid';
        }

        if ($query != '') {
            $result = $adb->pquery($query, $params);
            if ($adb->num_rows($result) != 0) {
                for ($k = 0; $k < $adb->num_rows($result); $k++) {
                    $skip_id[] = $adb->query_result($result, $k, $selectfield);
                }
                $skipids = implode(",", constructList($skip_id, 'INTEGER'));
                if (count($skipids) > 0) {
                    $where_relquery = "and " . $table . "." . $field . " not in (" . $skipids . ")";
                }
            }
        }
        $log->debug("Exiting getRelCheckquery method ...");
        return $where_relquery;
    }

    /**This function stores the variables in session sent in list view url string.
     *Param $lv_array - list view session array
     *Param $noofrows - no of rows
     *Param $max_ent - maximum entires
     *Param $module - module name
     *Param $related - related module
     *Return type void.
     */

    function setSessionVar($lv_array, $noofrows, $max_ent, $module = '', $related = '')
    {
        $start = '';
        if ($noofrows >= 1) {
            $lv_array['start'] = 1;
            $start = 1;
        } elseif ($related != '' && $noofrows == 0) {
            $lv_array['start'] = 1;
            $start = 1;
        } else {
            $lv_array['start'] = 0;
            $start = 0;
        }

        if (isset($_REQUEST['start']) && $_REQUEST['start'] != '') {
            $lv_array['start'] = ListViewSession::getRequestStartPage();
            $start = ListViewSession::getRequestStartPage();
        } elseif ($_SESSION['rlvs'][$module][$related]['start'] != '') {

            if ($related != '') {
                $lv_array['start'] = $_SESSION['rlvs'][$module][$related]['start'];
                $start = $_SESSION['rlvs'][$module][$related]['start'];
            }
        }
        if (isset($_REQUEST['viewname']) && $_REQUEST['viewname'] != '')
            $lv_array['viewname'] = vtlib_purify($_REQUEST['viewname']);

        if ($related == '')
            $_SESSION['lvs'][$_REQUEST['module']] = $lv_array;
        else
            $_SESSION['rlvs'][$module][$related] = $lv_array;

        if ($start < ceil($noofrows / $max_ent) && $start != '') {
            $start = ceil($noofrows / $max_ent);
            if ($related == '')
                $_SESSION['lvs'][$currentModule]['start'] = $start;
        }
    }

    /**Function to get the table headers for related listview
     *Param $navigation_arrray - navigation values in array
     *Param $url_qry - url string
     *Param $module - module name
     *Param $action- action file name
     *Param $viewid - view id
     *Returns an string value
     */

//Temp function to be be deleted
    function getRelatedTableHeaderNavigation($navigation_array, $url_qry, $module = '', $action_val = '', $viewid = '')
    {
        global $log, $singlepane_view, $app_strings;
        $log->debug("Entering getTableHeaderNavigation(" . $navigation_array . "," . $url_qry . "," . $module . "," . $action_val . "," . $viewid . ") method ...");
        global $theme;
        $theme_path = "themes/" . $theme . "/";
        $image_path = $theme_path . "images/";
        $tabid = getTabid($module);
        $tabname = getParentTab();
        $url_qry .= '&parenttab=' . $tabname;
        $output = '<td align="right" style="padding="5px;">';
        
        if ($singlepane_view == 'true')
            $action_val = 'DetailView';
        else
            $action_val = 'CallRelatedList';

        if (($navigation_array['prev']) != 0) {
            $output .= '<a href="index.php?module=' . $module . '&action=' . $action_val . $url_qry . '&start=1&viewname=' . $viewid . '" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            $output .= '<a href="index.php?module=' . $module . '&action=' . $action_val . $url_qry . '&start=' . $navigation_array['prev'] . '&viewname=' . $viewid . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';

        } else {
            $output .= '<img src="' . aicrm_imageurl('start_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
            $output .= '<img src="' . aicrm_imageurl('previous_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
        }

        $jsNavigate = "index.php?module=$module&action=$action_val$url_qry&start='+this.value+'&viewname=$viewid";
        $jsHandler = "return VT_disableFormSubmit(event);";
        $output .= "<input class='small' name='pagenum' type='text' value='{$navigation_array['current']}'
        style='width: 3em;margin-right: 0.7em;border-radius:3px;border: 1px solid #000000;' onchange=\"location.href='$jsNavigate'\"
        onkeypress=\"$jsHandler\">";
        $output .= "<span name='listViewCountContainerName' class='small' style='white-space: nowrap;margin-right: 0.3em;'>";
        $output .= $app_strings['LBL_LIST_OF'] . ' ' . $navigation_array['verylast'] . '</span>';

        if (($navigation_array['next']) != 0) {
            $output .= '<a href="index.php?module=' . $module . '&action=' . $action_val . $url_qry . '&start=' . $navigation_array['next'] . '&viewname=' . $viewid . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            $output .= '<a href="index.php?module=' . $module . '&action=' . $action_val . $url_qry . '&start=' . $navigation_array['verylast'] . '&viewname=' . $viewid . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
        } else {
            $output .= '<img src="' . aicrm_imageurl('next_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
            $output .= '<img src="' . aicrm_imageurl('end_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
        }
        $output .= '</td>';
        $log->debug("Exiting getTableHeaderNavigation method ...");
        if ($navigation_array['first'] == '')
            return;
        else
            return $output;
    }

    /**    Function to get the Edit link details for ListView and RelatedListView
     * @param string $module - module name
     * @param int $entity_id - record id
     * @param string $relatedlist - string "relatedlist" or may be empty. if empty means ListView else relatedlist
     * @param string $returnset - may be empty in case of ListView. For relatedlists, return_module, return_action and return_id values will be passed like &return_module=Accounts&return_action=CallRelatedList&return_id=10
     *    return string    $edit_link    - url string which cotains the editlink details (module, action, record, etc.,) like index.php?module=Accounts&action=EditView&record=10
     */
    function getListViewEditLink($module, $entity_id, $relatedlist, $returnset, $result, $count)
    {
        global $adb,$current_user;
        
        $return_action = "index";
        $edit_link = "index.php?module=$module&action=EditView&record=$entity_id";
        $tabname = getParentTab();
        //Added to fix 4600
        $url = getBasic_Advance_SearchURL();

        //This is relatedlist listview
        if ($relatedlist == 'relatedlist') {
            $edit_link .= $returnset;
        } else {
            if ($module == 'Calendar') {
                $return_action = "ListView";
                $actvity_type = $adb->query_result($result, $count, 'type');
                if ($actvity_type == 'Task')
                    $edit_link .= '&activity_mode=Task';
                else
                    $edit_link .= '&activity_mode=Events';
            }
            $edit_link .= "&return_module=$module&return_action=$return_action";
        }

        $edit_link .= "&parenttab=" . $tabname . $url;
        //Appending view name while editing from ListView
        $edit_link .= "&return_viewname=" . $_SESSION['lvs'][$module]["viewname"];
        if ($module == 'Emails'){
            $edit_link = 'javascript:;" onclick="OpenCompose(\'' . $entity_id . '\',\'edit\');';
        }

        if($module == 'Projects'){
            $edit_link = "MOAIMB-Webview/index.php/Projects/edit_web/".$entity_id ."?userid=".$current_user->id."&flagedit=no";
        }
        return $edit_link;
    }

    /**    Function to get the Del link details for ListView and RelatedListView
     * @param string $module - module name
     * @param int $entity_id - record id
     * @param string $relatedlist - string "relatedlist" or may be empty. if empty means ListView else relatedlist
     * @param string $returnset - may be empty in case of ListView. For relatedlists, return_module, return_action and return_id values will be passed like &return_module=Accounts&return_action=CallRelatedList&return_id=10
     *    return string    $del_link    - url string which cotains the editlink details (module, action, record, etc.,) like index.php?module=Accounts&action=Delete&record=10
     */
    function getListViewDeleteLink($module, $entity_id, $relatedlist, $returnset)
    {
        $tabname = getParentTab();
        $current_module = vtlib_purify($_REQUEST['module']);
        $viewname = $_SESSION['lvs'][$current_module]['viewname'];
        
        //Added to fix 4600
        $url = getBasic_Advance_SearchURL();

        if ($module == "Calendar")
            $return_action = "ListView";
        else
            $return_action = "index";

        //This is added to avoid the del link in Product related list for the following modules
        $avoid_del_links = Array("PurchaseOrder", "SalesOrder", "Quotes", "Invoice");

        if (($current_module == 'Products' || $current_module == 'Services') && in_array($module, $avoid_del_links)) {
            return '';
        }

        $del_link = "index.php?module=$module&action=Delete&record=$entity_id";

        //This is added for relatedlist listview
        if ($relatedlist == 'relatedlist') {
            $del_link .= $returnset;
        } else {
            $del_link .= "&return_module=$module&return_action=$return_action";
        }

        $del_link .= "&parenttab=" . $tabname . "&return_viewname=" . $viewname . $url;

        // vtlib customization: override default delete link for custom modules
        $requestModule = vtlib_purify($_REQUEST['module']);
        $requestRecord = vtlib_purify($_REQUEST['record']);
        $requestAction = vtlib_purify($_REQUEST['action']);
        $parenttab = vtlib_purify($_REQUEST['parenttab']);
        $isCustomModule = vtlib_isCustomModule($requestModule);
        if ($isCustomModule && !in_array($requestAction, Array('index', 'ListView'))) {
            $del_link = "index.php?module=$requestModule&action=updateRelations&parentid=$requestRecord";
            $del_link .= "&destination_module=$module&idlist=$entity_id&mode=delete&parenttab=$parenttab";
        }
        // END

        return $del_link;
    }

    /* Function to get the Entity Id of a given Entity Name */
    function getEntityId($module, $entityName)
    {
        global $log, $adb;
        $log->info("in getEntityId " . $entityName);

        $query = "select fieldname,tablename,entityidfield from aicrm_entityname where modulename = ?";
        $result = $adb->pquery($query, array($module));
        $fieldsname = $adb->query_result($result, 0, 'fieldname');
        $tablename = $adb->query_result($result, 0, 'tablename');
        $entityidfield = $adb->query_result($result, 0, 'entityidfield');
        if (!(strpos($fieldsname, ',') === false)) {
            $fieldlists = explode(',', $fieldsname);
            $fieldsname = "concat(";
            $fieldsname = $fieldsname . implode(",' ',", $fieldlists);
            $fieldsname = $fieldsname . ")";
        }

        if ($entityName != '') {
            $sql = "select $entityidfield from $tablename INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = $tablename.$entityidfield " .
            " WHERE aicrm_crmentity.deleted = 0 and $fieldsname=?";
            $result = $adb->pquery($sql, array($entityName));
            if ($adb->num_rows($result) > 0) {
                $entityId = $adb->query_result($result, 0, $entityidfield);
            }
        }
        if (!empty($entityId))
            return $entityId;
        else
            return 0;
    }

    /**    function used to get the parent id for the given input parent name --Pavani **/
    function getParentId($parent_name)
    {
        global $adb;
        if ($parent_name == '' || $parent_name == NULL)
            $parent_id = 0;
        //For now it have conditions only for accounts and contacts, if needed can add more
        $relatedTo = explode(':', $parent_name);
        $parent_module = $relatedTo[0];
        $parent_module = trim($parent_module, " ");
        $parent_name = $relatedTo[3];
        $parent_name = trim($parent_name, " ");
        $num_rows = 0;
        if ($parent_module == 'Contacts') {
            $query = "select crmid from aicrm_contactdetails, aicrm_crmentity WHERE concat(lastname,' ',firstname)=? and aicrm_crmentity.crmid =aicrm_contactdetails.contactid and aicrm_crmentity.deleted=0";
            $result = $adb->pquery($query, array($parent_name));
            $num_rows = $adb->num_rows($result);
        } else if ($parent_module == 'Accounts') {
            $query = "select crmid from aicrm_account, aicrm_crmentity WHERE accountname=? and aicrm_crmentity.crmid =aicrm_account.accountid and aicrm_crmentity.deleted=0";
            $result = $adb->pquery($query, array($parent_name));
            $num_rows = $adb->num_rows($result);
        } else $num_rows = 0;
        if ($num_rows == 0) $parent_id = 0;
        else $parent_id = $adb->query_result($result, 0, "crmid");
        return $parent_id;
    }

    function decode_html($str)
    {
        global $default_charset;
        // Direct Popup action or Ajax Popup action should be treated the same.
        if ($_REQUEST['action'] == 'Popup' || $_REQUEST['file'] == 'Popup')
            return html_entity_decode($str);
        else
            return html_entity_decode($str, ENT_QUOTES, $default_charset);
    }

    /**
     * Alternative decoding function which coverts irrespective of $_REQUEST values.
     * Useful incase of Popup (Listview etc...) where if decode_html will not work as expected
     */
    function decode_html_force($str)
    {
        global $default_charset;
        return html_entity_decode($str, ENT_QUOTES, $default_charset);
    }

    function popup_decode_html($str)
    {
        global $default_charset;
        $slashes_str = popup_from_html($str);
        $slashes_str = htmlspecialchars($slashes_str, ENT_QUOTES, $default_charset);
        return decode_html(br2nl($slashes_str));
    }

//function added to check the text length in the listview.
    function textlength_check($field_val)
    {
        global $listview_max_textlength;
        if ($listview_max_textlength) {
            $temp_val = preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val);
            if (strlen($field_val) > $listview_max_textlength) {
                //$temp_val = substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val), 0, $listview_max_textlength) . '...';
                $temp_val = mb_substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val), 0, $listview_max_textlength,"utf-8") . '...';

            }
        } else {
            $temp_val = $field_val;
        }
        return $temp_val ;
        //return preg_replace("/[\x00-\x1F\x80-\xFF]/","", $field_val);
    }

    /** Function to get permitted fields of current user of a particular module to find duplicate records --Pavani*/
    function getMergeFields($module, $str)
    {
        global $adb, $current_user;
        $tabid = getTabid($module);
        if ($str == "available_fields") {
            $result = getFieldsResultForMerge($tabid);
        } else { //if($str == fileds_to_merge)
            $sql = "select * from aicrm_user2mergefields where tabid=? and userid=? and visible=1";
            $result = $adb->pquery($sql, array($tabid, $current_user->id));
        }

        $num_rows = $adb->num_rows($result);

        $user_profileid = fetchUserProfileId($current_user->id);
        $permitted_list = getProfile2FieldPermissionList($module, $user_profileid);

        $sql_def_org = "select fieldid from aicrm_def_org_field where tabid=? and visible=0";
        $result_def_org = $adb->pquery($sql_def_org, array($tabid));
        $num_rows_org = $adb->num_rows($result_def_org);
        $permitted_org_list = Array();
        for ($i = 0; $i < $num_rows_org; $i++)
            $permitted_org_list[$i] = $adb->query_result($result_def_org, $i, "fieldid");

        require('user_privileges/user_privileges_' . $current_user->id . '.php');
        for ($i = 0; $i < $num_rows; $i++) {
            $field_id = $adb->query_result($result, $i, "fieldid");
            foreach ($permitted_list as $field => $data)
                if ($data[4] == $field_id and $data[1] == 0) {
                    if ($is_admin == 'true' || (in_array($field_id, $permitted_org_list))) {
                        $field = "<option value=\"" . $field_id . "\">" . getTranslatedString($data[0], $module) . "</option>";
                        $fields .= $field;
                        break;
                    }
                }
            }
            return $fields;
        }


    /**
     * this function accepts a modulename and a fieldname and returns the first related module for it
     * it expects the uitype of the field to be 10
     * @param string $module - the modulename
     * @param string $fieldname - the field name
     * @return string $data - the first related module
     */
    function getFirstModule($module, $fieldname)
    {
        global $adb;
        $sql = "select fieldid, uitype from aicrm_field where tabid=? and fieldname=?";
        $result = $adb->pquery($sql, array(getTabid($module), $fieldname));

        if ($adb->num_rows($result) > 0) {
            $uitype = $adb->query_result($result, 0, "uitype");

            if ($uitype == 10) {
                $fieldid = $adb->query_result($result, 0, "fieldid");
                $sql = "select * from aicrm_fieldmodulerel where fieldid=?";
                $result = $adb->pquery($sql, array($fieldid));
                $count = $adb->num_rows($result);

                if ($count > 0) {
                    $data = $adb->query_result($result, 0, "relmodule");
                }
            }
        }
        return $data;
    }

    function VT_getSimpleNavigationValues($start, $size, $total)
    {
        $prev = $start - 1;
        if ($prev < 0) {
            $prev = 0;
        }
        if ($total === null) {
            return array('start' => $start, 'first' => $start, 'current' => $start, 'end' => $start, 'end_val' => $size, 'allflag' => 'All',
                'prev' => $prev, 'next' => $start + 1, 'verylast' => 'last');
        }
        if (empty($total)) {
            $lastPage = 1;
        } else {
            $lastPage = ceil($total / $size);
        }

        $next = $start + 1;
        if ($next > $lastPage) {
            $next = 0;
        }
        return array('start' => $start, 'first' => $start, 'current' => $start, 'end' => $start, 'end_val' => $size, 'allflag' => 'All',
            'prev' => $prev, 'next' => $next, 'verylast' => $lastPage);
    }

    /**Function to get the simplified table headers for a listview
     *Param $navigation_arrray - navigation values in array
     *Param $url_qry - url string
     *Param $module - module name
     *Param $action- action file name
     *Param $viewid - view id
     *Returns an string value
     */

    function getTableHeaderSimpleNavigation($navigation_array, $url_qry, $module = '', $action_val = 'index', $viewid = '')
    {
        global $log, $app_strings;
        global $theme, $current_user;
        $theme_path = "themes/" . $theme . "/";
        $image_path = $theme_path . "images/";
        if ($module == 'Documents') {
            $output = '<td class="mailSubHeader" width="100%" align="center">';
        } else {
            $output = '<td align="right" style="padding: 5px;">';
        }
        $tabname = getParentTab();

        $url_string = '';

        /*Padding 20 -200*/
        if (isset($_REQUEST['pagesize']) && $_REQUEST['pagesize']!="")
        {
            $url_string = '&pagesize='.$_REQUEST['pagesize'];

        }else{
            $url_string = '';
        }
        /*Padding 20 -200*/

        // vtlib Customization : For uitype 10 popup during paging
        if ($_REQUEST['form'] == 'vtlibPopupView') {
            $url_string .= '&form=vtlibPopupView&forfield=' . vtlib_purify($_REQUEST['forfield']) . '&srcmodule=' . vtlib_purify($_REQUEST['srcmodule']) . '&forrecord=' . vtlib_purify($_REQUEST['forrecord']);
        }
        // END

        if ($module == 'Calendar' && $action_val == 'index') {
            if ($_REQUEST['view'] == '') {
                if ($current_user->activity_view == "This Year") {
                    $mysel = 'year';
                } else if ($current_user->activity_view == "This Month") {
                    $mysel = 'month';
                } else if ($current_user->activity_view == "This Week") {
                    $mysel = 'week';
                } else {
                    $mysel = 'day';
                }
            }
            $data_value = date('Y-m-d H:i:s');
            preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $data_value, $value);
            $date_data = Array(
                'day' => $value[3],
                'month' => $value[2],
                'year' => $value[1],
                'hour' => $value[4],
                'min' => $value[5],
            );
            $tab_type = ($_REQUEST['subtab'] == '') ? 'event' : vtlib_purify($_REQUEST['subtab']);
            $url_string .= isset($_REQUEST['view']) ? "&view=" . vtlib_purify($_REQUEST['view']) : "&view=" . $mysel;
            $url_string .= isset($_REQUEST['subtab']) ? "&subtab=" . vtlib_purify($_REQUEST['subtab']) : '';
            $url_string .= isset($_REQUEST['viewOption']) ? "&viewOption=" . vtlib_purify($_REQUEST['viewOption']) : '&viewOption=listview';
            $url_string .= isset($_REQUEST['day']) ? "&day=" . vtlib_purify($_REQUEST['day']) : '&day=' . $date_data['day'];
            $url_string .= isset($_REQUEST['week']) ? "&week=" . vtlib_purify($_REQUEST['week']) : '';
            $url_string .= isset($_REQUEST['month']) ? "&month=" . vtlib_purify($_REQUEST['month']) : '&month=' . $date_data['month'];
            $url_string .= isset($_REQUEST['year']) ? "&year=" . vtlib_purify($_REQUEST['year']) : "&year=" . $date_data['year'];
            $url_string .= isset($_REQUEST['n_type']) ? "&n_type=" . vtlib_purify($_REQUEST['n_type']) : '';
            $url_string .= isset($_REQUEST['search_option']) ? "&search_option=" . vtlib_purify($_REQUEST['search_option']) : '';
        }
        if ($module == 'Calendar' && $action_val != 'index') //added for the All link from the homepage -- ticket 5211
        $url_string .= isset($_REQUEST['from_homepage']) ? "&from_homepage=" . vtlib_purify($_REQUEST['from_homepage']) : '';

        if (($navigation_array['prev']) != 0) {
            if ($module == 'Calendar' && $action_val == 'index') {
                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=1\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=' . $navigation_array['prev'] . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else if ($action_val == "FindDuplicate") {
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($action_val == 'UnifiedSearch') {
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($module == 'Documents') {
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '&folderid=' . $action_val . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else {
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=1' . $url_string . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . aicrm_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['prev'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . aicrm_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            }
        } else {
            $output .= '<img src="' . aicrm_imageurl('start_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
            $output .= '<img src="' . aicrm_imageurl('previous_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
        }
        if ($module == 'Calendar' && $action_val == 'index') {
            $jsNavigate = "cal_navigation('$tab_type','$url_string','&start='+this.value+');";
        } else if ($action_val == "FindDuplicate") {
            $jsNavigate = "getDuplicateListViewEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string');";
        } elseif ($action_val == 'UnifiedSearch') {
            $jsNavigate = "getUnifiedSearchEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string');";
        } elseif ($module == 'Documents') {
            $jsNavigate = "getListViewEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string&folderid=$action_val');";
        } else {
            $jsNavigate = "getListViewEntries_js('$module','parenttab=$tabname&start='+this.value+'$url_string');";
        }
        if ($module == 'Documents' && $action_val != 'UnifiedSearch') {
            $url = '&folderid=' . $action_val;
        } else {
            $url = '';
        }
        $jsHandler = "return VT_disableFormSubmit(event);";
        $output .= "<input class='small' name='pagenum' type='text' value='{$navigation_array['current']}'
        style='width: 3em;margin-right: 0.7em;border-radius:3px;border: 1px solid #000000;' onchange=\"$jsNavigate\"
        onkeypress=\"$jsHandler\">";
        $output .= "<span name='" . $module . "_listViewCountContainerName' class='small' style='white-space: nowrap;margin-right:0.3em;'>";
        if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true) {
            $output .= $app_strings['LBL_LIST_OF'] . ' ' . $navigation_array['verylast'];
        } else {
            $output .= "<img src='" . aicrm_imageurl('windowRefresh.gif', $theme) . "' alt='" . $app_strings['LBL_HOME_COUNT'] . "'
            onclick='getListViewCount(\"" . $module . "\",this,this.parentNode,\"" . $url . "\")'
            align='absmiddle' name='" . $module . "_listViewCountRefreshIcon'/>
            <img name='" . $module . "_listViewCountContainerBusy' src='" . aicrm_imageurl('vtbusy.gif', $theme) . "' style='display: none;'
            align='absmiddle' alt='" . $app_strings['LBL_LOADING'] . "'>";
        }
        $output .= '</span>';

        if (($navigation_array['next']) != 0) {
            if ($module == 'Calendar' && $action_val == 'index') {
                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=' . $navigation_array['next'] . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="cal_navigation(\'' . $tab_type . '\',\'' . $url_string . '\',\'&start=' . $navigation_array['verylast'] . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else if ($action_val == "FindDuplicate") {
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getDuplicateListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($action_val == 'UnifiedSearch') {
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getUnifiedSearchEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="themes/images/end.gif" border="0" align="absmiddle"></a>&nbsp;';
            } elseif ($module == 'Documents') {
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '&folderid=' . $action_val . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '&folderid=' . $action_val . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            } else {
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['next'] . $url_string . '\');" alt="' . $app_strings['LNK_LIST_NEXT'] . '" title="' . $app_strings['LNK_LIST_NEXT'] . '"><img src="' . aicrm_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
                $output .= '<a href="javascript:;" onClick="getListViewEntries_js(\'' . $module . '\',\'parenttab=' . $tabname . '&start=' . $navigation_array['verylast'] . $url_string . '\');" alt="' . $app_strings['LBL_LAST'] . '" title="' . $app_strings['LBL_LAST'] . '"><img src="' . aicrm_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
            }
        } else {
            $output .= '<img src="' . aicrm_imageurl('next_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
            $output .= '<img src="' . aicrm_imageurl('end_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
        }
        $output .= '</td>';
        if ($navigation_array['first'] == '')
            return;
        else
            return $output;
    }

    function getRecordRangeMessage($listResult, $limitStartRecord, $noofrows)
    {
        //echo "<pre>"; print_r($noofrows); echo "</pre>"; exit;
        global $adb, $app_strings;
        $numRows = $adb->num_rows($listResult);

        $recordListRangeMsg = '';
        if ($numRows > 0) {
            $recordListRangeMsg = $app_strings['LBL_SHOWING'] . ' ' . $app_strings['LBL_RECORDS'] .
            ' ' . ($limitStartRecord + 1) . ' - ' . ($limitStartRecord + $numRows) . '   ' . $app_strings['LBL_TOTALRECORD'] .
            ' ' . number_format($noofrows, 0, '.', ',') . ' ' . $app_strings['LBL_RECORDS'];
        }
        //echo $recordListRangeMsg; echo "<br>";
        return $recordListRangeMsg;
    }

    function getRecordRangeMessage1($listResult, $limitStartRecord)
    {
        global $adb, $app_strings;
        $numRows = $adb->num_rows($listResult);
        $recordListRangeMsg = '';
        if ($numRows > 0) {
            $recordListRangeMsg = $app_strings['LBL_SHOWING'] . ' ' . $app_strings['LBL_RECORDS'] .
            ' ' . ($limitStartRecord + 1) . ' - ' . ($limitStartRecord + $numRows);
        }
        return $recordListRangeMsg;
    }

    ?>