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
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/modules/Potentials/Potentials.php,v 1.65 2005/04/28 08:08:27 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

// aicrm_potential is used to store customer information.
class Potentials extends CRMEntity {
	var $log;
	var $db;

	var $module_name="Potentials";
	var $table_name = "aicrm_potential";
	var $table_index= 'potentialid';

	var $tab_name = Array('aicrm_crmentity','aicrm_potential','aicrm_potentialscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_potential'=>'potentialid','aicrm_potentialscf'=>'potentialid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_potentialscf', 'potentialid');

	var $column_fields = Array();

	var $sortby_fields = Array('potentialname','amount','closingdate','smownerid','accountname');

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
			'Potential'=>Array('potential'=>'potentialname'),
			'Related to'=>Array('potential'=>'related_to'),
			'Sales Stage'=>Array('potential'=>'sales_stage'),
			'Amount'=>Array('potential'=>'amount'),
			'Expected Close Date'=>Array('potential'=>'closingdate'),
			'Assigned To'=>Array('crmentity','smownerid')
			);

	var $list_fields_name = Array(
			'Potential'=>'potentialname',
			'Related to'=>'related_to',
			'Sales Stage'=>'sales_stage',
			'Amount'=>'amount',
			'Expected Close Date'=>'closingdate',
			'Assigned To'=>'assigned_user_id');

	var $list_link_field= 'potentialname';

	var $search_fields = Array(
			'Potential'=>Array('potential'=>'potentialname'),
			'Related To'=>Array('potential'=>'related_to'),
			'Expected Close Date'=>Array('potential'=>'closedate')
			);

	var $search_fields_name = Array(
			'Potential'=>'potentialname',
			'Related To'=>'related_to',
			'Expected Close Date'=>'closingdate'
			);

	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'potentialname', 'related_to');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'potentialname';
	var $default_sort_order = 'ASC';

	//var $groupTable = Array('aicrm_potentialgrouprelation','potentialid');
	function Potentials() {
		$this->log = LoggerManager::getLogger('potential');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Potentials');
	}

	function save_module($module)
	{
	}

	/**
	* Function to get sort order
	* return string  $sorder    - sortorder string either 'ASC' or 'DESC'
	*/
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['POTENTIALS_SORT_ORDER'] != '')?($_SESSION['POTENTIALS_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**
	* Function to get order by
	* return string  $order_by    - fieldname(eg: 'Potentialname')
	*/
	function getOrderBy()
	{
		global $log;
                $log->debug("Entering getOrderBy() method ...");

		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}

		if (isset($_REQUEST['order_by']))
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		else
			$order_by = (($_SESSION['POTENTIALS_ORDER_BY'] != '')?($_SESSION['POTENTIALS_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/** Function to create list query
	* @param reference variable - where condition is passed when the query is executed
	* Returns Query.
	*/
	function create_list_query($order_by, $where)
	{
		global $log,$current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
	        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        	$tab_id = getTabid("Potentials");
		$log->debug("Entering create_list_query(".$order_by.",". $where.") method ...");
		// Determine if the aicrm_account name is present in the where clause.
		$account_required = ereg("accounts\.name", $where);

		if($account_required)
		{
			$query = "SELECT aicrm_potential.potentialid,  aicrm_potential.potentialname, aicrm_potential.dateclosed FROM aicrm_potential, aicrm_account ";
			$where_auto = "account.accountid = aicrm_potential.related_to AND aicrm_crmentity.deleted=0 ";
		}
		else
		{
			$query = 'SELECT aicrm_potential.potentialid, aicrm_potential.potentialname, aicrm_crmentity.smcreatorid, aicrm_potential.closingdate FROM aicrm_potential inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_potential.potentialid LEFT JOIN aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid ';
			$where_auto = ' AND aicrm_crmentity.deleted=0';
		}

		if($where != "")
			$query .= " where $where ".$where_auto;
		else
			$query .= " where ".$where_auto;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
                {
                                $sec_parameter=getListViewSecurityParameter("Potentials");
                                $query .= $sec_parameter;

                }

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY aicrm_potential.potentialname ";



		$log->debug("Exiting create_list_query method ...");
		return $query;
	}

	/** Function to export the Opportunities records in CSV Format
	* @param reference variable - order by is passed when the query is executed
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Potentials Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Potentials", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
				FROM aicrm_potential
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_potential.potentialid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
				LEFT JOIN aicrm_account on aicrm_potential.related_to=aicrm_account.accountid
				LEFT JOIN aicrm_potentialscf on aicrm_potentialscf.potentialid=aicrm_potential.potentialid
                LEFT JOIN aicrm_groups
        	        ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_campaign
					ON aicrm_campaign.campaignid = aicrm_potential.campaignid";
		$query .= setFromQuery("Potentials");
		$where_auto = "  aicrm_crmentity.deleted = 0 ";

                if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[2] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Potentials");
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;

	}



	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
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

		$accountid = $this->column_fields['related_to'];
		$search_string = "&fromPotential=true&acc_id=$accountid";

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab$search_string','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = 'select case when (aicrm_users.user_name not like "") then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
					aicrm_contactdetails.accountid,aicrm_potential.potentialid, aicrm_potential.potentialname, aicrm_contactdetails.contactid,
					aicrm_contactdetails.lastname, aicrm_contactdetails.firstname, aicrm_contactdetails.title, aicrm_contactdetails.department,
					aicrm_contactdetails.email, aicrm_contactdetails.phone, aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
					aicrm_crmentity.modifiedtime , aicrm_account.accountname from aicrm_potential
					inner join aicrm_contpotentialrel on aicrm_contpotentialrel.potentialid = aicrm_potential.potentialid
					inner join aicrm_contactdetails on aicrm_contpotentialrel.contactid = aicrm_contactdetails.contactid
					inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
					left join aicrm_account on aicrm_account.accountid = aicrm_contactdetails.accountid
					left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
					left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id
					where aicrm_potential.potentialid = '.$id.' and aicrm_crmentity.deleted=0';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/** Returns a list of the associated calls
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function get_activities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_activities(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/Activity.php");
		$other = new Activity();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		$button .= '<input type="hidden" name="activity_mode">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_TODO', $related_module) ."'>&nbsp;";
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_EVENT', $related_module) ."'>";
			}
		}

		$query = "SELECT aicrm_activity.activityid as 'tmp_activity_id',aicrm_activity.*,aicrm_seactivityrel.*, aicrm_contactdetails.lastname,aicrm_contactdetails.firstname,
					aicrm_cntactivityrel.*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime,
					case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
					aicrm_recurringevents.recurringtype from aicrm_activity
					inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid
					inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid
					left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid = aicrm_activity.activityid
					left join aicrm_contactdetails on aicrm_contactdetails.contactid = aicrm_cntactivityrel.contactid
					inner join aicrm_potential on aicrm_potential.potentialid=aicrm_seactivityrel.crmid
					left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
					left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
					left outer join aicrm_recurringevents on aicrm_recurringevents.activityid=aicrm_activity.activityid
					where aicrm_seactivityrel.crmid=".$id." and aicrm_crmentity.deleted=0
					and ((aicrm_activity.activitytype='Task' and aicrm_activity.status not in ('Completed','Deferred'))
					or (aicrm_activity.activitytype NOT in ('Emails','Task') and  aicrm_activity.eventstatus not in ('','Held'))) ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	 /**
	 * Function to get Contact related Products
	 * @param  integer   $id  - contactid
	 * returns related Products record in array format
	 */
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
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

		$query = "SELECT aicrm_products.productid, aicrm_products.productname, aicrm_products.productcode,
				aicrm_products.commissionrate, aicrm_products.qty_per_unit, aicrm_products.unit_price,
				aicrm_crmentity.crmid, aicrm_crmentity.smownerid
			   FROM aicrm_products
			   INNER JOIN aicrm_seproductsrel ON aicrm_products.productid = aicrm_seproductsrel.productid and aicrm_seproductsrel.setype = 'Potentials'
			   INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
			   INNER JOIN aicrm_potential ON aicrm_potential.potentialid = aicrm_seproductsrel.crmid
			   WHERE aicrm_crmentity.deleted = 0 AND aicrm_potential.potentialid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/**	Function used to get the Sales Stage history of the Potential
	 *	@param $id - potentialid
	 *	return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are array which contains all the column values of an row
	 */
	function get_stage_history($id)
	{
		global $log;
		$log->debug("Entering get_stage_history(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select aicrm_potstagehistory.*, aicrm_potential.potentialname from aicrm_potstagehistory inner join aicrm_potential on aicrm_potential.potentialid = aicrm_potstagehistory.potentialid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_potential.potentialid where aicrm_crmentity.deleted = 0 and aicrm_potential.potentialid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_SALES_STAGE'];
		$header[] = $app_strings['LBL_PROBABILITY'];
		$header[] = $app_strings['LBL_CLOSE_DATE'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Sales Stage, Expected Close Dates are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$amount_access = (getFieldVisibilityPermission('Potentials', $current_user->id, 'amount') != '0')? 1 : 0;
		$probability_access = (getFieldVisibilityPermission('Potentials', $current_user->id, 'probability') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Potentials');

		$potential_stage_array = $picklistarray['sales_stage'];
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = 'Not Accessible';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			$entries[] = ($amount_access != 1)? $row['amount'] : 0;
			$entries[] = (in_array($row['stage'], $potential_stage_array))? $row['stage']: $error_msg;
			$entries[] = ($probability_access != 1) ? $row['probability'] : 0;
			$entries[] = getDisplayDate($row['closedate']);
			$entries[] = getDisplayDate($row['lastmodified']);

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_stage_history method ...");

		return $return_data;
	}

	/**
	* Function to get Potential related Task & Event which have activity type Held, Completed or Deferred.
	* @param  integer   $id
	* returns related Task or Event record in array format
	*/
	function get_history($id)
	{
			global $log;
			$log->debug("Entering get_history(".$id.") method ...");
			$query = "SELECT aicrm_activity.activityid, aicrm_activity.subject, aicrm_activity.status,
		aicrm_activity.eventstatus, aicrm_activity.activitytype,aicrm_activity.date_start,
		aicrm_activity.due_date, aicrm_activity.time_start,aicrm_activity.time_end,
		aicrm_crmentity.modifiedtime, aicrm_crmentity.createdtime,
		aicrm_crmentity.description,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
				from aicrm_activity
				inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid
				left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
				left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
				where (aicrm_activity.activitytype = 'Meeting' or aicrm_activity.activitytype='Call' or aicrm_activity.activitytype='Task')
				and (aicrm_activity.status = 'Completed' or aicrm_activity.status = 'Deferred' or (aicrm_activity.eventstatus = 'Held' and aicrm_activity.eventstatus != ''))
				and aicrm_seactivityrel.crmid=".$id."
                                and aicrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Potentials',$query,$id);
	}


	  /**
	  * Function to get Potential related Quotes
	  * @param  integer   $id  - potentialid
	  * returns related Quotes record in array format
	  */
	function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_quotes(".$id.") method ...");
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'potential_id') == '0') {
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

		$query = "select case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
					aicrm_account.accountname, aicrm_crmentity.*, aicrm_quotes.*, aicrm_potential.potentialname from aicrm_quotes
					inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_quotes.quoteid
					left outer join aicrm_potential on aicrm_potential.potentialid=aicrm_quotes.potentialid
					left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
					left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
					inner join aicrm_account on aicrm_account.accountid=aicrm_quotes.accountid
					where aicrm_crmentity.deleted=0 and aicrm_potential.potentialid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	/**
	 * Function to get Potential related SalesOrder
 	 * @param  integer   $id  - potentialid
	 * returns related SalesOrder record in array format
	 */
	function get_salesorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_salesorder(".$id.") method ...");
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'potential_id') == '0') {
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

		$query = "select aicrm_crmentity.*, aicrm_salesorder.*, aicrm_quotes.subject as quotename, aicrm_account.accountname, aicrm_potential.potentialname,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			from aicrm_salesorder
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_salesorder.salesorderid
			left outer join aicrm_quotes on aicrm_quotes.quoteid=aicrm_salesorder.quoteid
			left outer join aicrm_account on aicrm_account.accountid=aicrm_salesorder.accountid
			left outer join aicrm_potential on aicrm_potential.potentialid=aicrm_salesorder.potentialid
			left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
			left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
			 where aicrm_crmentity.deleted=0 and aicrm_potential.potentialid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
		return $return_value;
	}

	/**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb,$log;
		$log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");

		$rel_table_arr = Array("Activities"=>"aicrm_seactivityrel","Contacts"=>"aicrm_contpotentialrel","Products"=>"aicrm_seproductsrel",
						"Attachments"=>"aicrm_seattachmentsrel","Quotes"=>"aicrm_quotes","SalesOrder"=>"aicrm_salesorder",
						"Documents"=>"aicrm_senotesrel");

		$tbl_field_arr = Array("aicrm_seactivityrel"=>"activityid","aicrm_contpotentialrel"=>"contactid","aicrm_seproductsrel"=>"productid",
						"aicrm_seattachmentsrel"=>"attachmentsid","aicrm_quotes"=>"quoteid","aicrm_salesorder"=>"salesorderid",
						"aicrm_senotesrel"=>"notesid");

		$entity_tbl_field_arr = Array("aicrm_seactivityrel"=>"crmid","aicrm_contpotentialrel"=>"potentialid","aicrm_seproductsrel"=>"crmid",
						"aicrm_seattachmentsrel"=>"crmid","aicrm_quotes"=>"potentialid","aicrm_salesorder"=>"potentialid",
						"aicrm_senotesrel"=>"crmid");

		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
				$id_field = $tbl_field_arr[$rel_table];
				$entity_id_field = $entity_tbl_field_arr[$rel_table];
				// IN clause to avoid duplicate entries
				$sel_result =  $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
						" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
						array($transferId,$entityId));
				$res_cnt = $adb->num_rows($sel_result);
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						$id_field_value = $adb->query_result($sel_result,$i,$id_field);
						$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?",
							array($entityId,$transferId,$id_field_value));
					}
				}
			}
		}
		$log->debug("Exiting transferRelatedRecords...");
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_potential","potentialid");
		$query .= " left join aicrm_crmentity as aicrm_crmentityPotentials on aicrm_crmentityPotentials.crmid=aicrm_potential.potentialid and aicrm_crmentityPotentials.deleted=0
		left join aicrm_account as aicrm_accountPotentials on aicrm_potential.related_to = aicrm_accountPotentials.accountid
		left join aicrm_contactdetails as aicrm_contactdetailsPotentials on aicrm_potential.related_to = aicrm_contactdetailsPotentials.contactid
		left join aicrm_potentialscf on aicrm_potentialscf.potentialid = aicrm_potential.potentialid
		left join aicrm_groups aicrm_groupsPotentials on aicrm_groupsPotentials.groupid = aicrm_crmentityPotentials.smownerid
		left join aicrm_users as aicrm_usersPotentials on aicrm_usersPotentials.id = aicrm_crmentityPotentials.smownerid
		left join aicrm_campaign as aicrm_campaignPotentials on aicrm_potential.campaignid = aicrm_campaignPotentials.campaignid";
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Calendar" => array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_potential"=>"potentialid"),
			"Products" => array("aicrm_seproductsrel"=>array("crmid","productid"),"aicrm_potential"=>"potentialid"),
			"Quotes" => array("aicrm_quotes"=>array("potentialid","quoteid"),"aicrm_potential"=>"potentialid"),
			"SalesOrder" => array("aicrm_salesorder"=>array("potentialid","salesorderid"),"aicrm_potential"=>"potentialid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_potential"=>"potentialid"),
			"Accounts" => array("aicrm_potential"=>array("potentialid","related_to")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		/*//Backup Activity-Potentials Relation
		$act_q = "select activityid from aicrm_seactivityrel where crmid = ?";
		$act_res = $this->db->pquery($act_q, array($id));
		if ($this->db->num_rows($act_res) > 0) {
			for($k=0;$k < $this->db->num_rows($act_res);$k++)
			{
				$act_id = $this->db->query_result($act_res,$k,"activityid");
				$params = array($id, RB_RECORD_DELETED, 'aicrm_seactivityrel', 'crmid', 'activityid', $act_id);
				$this->db->pquery("insert into aicrm_relatedlists_rb values (?,?,?,?,?,?)", $params);
			}
		}
		$sql = 'delete from aicrm_seactivityrel where crmid = ?';
		$this->db->pquery($sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$this->trash($this->module_name, $id);
		} elseif($return_module == 'Campaigns') {
			$sql = 'UPDATE aicrm_potential SET campaignid = 0 WHERE potentialid = ?';
			$this->db->pquery($sql, array($id));
		} elseif($return_module == 'Products') {
			$sql = 'DELETE FROM aicrm_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Contacts') {
			$sql = 'DELETE FROM aicrm_contpotentialrel WHERE potentialid=? AND contactid=?';
			$this->db->pquery($sql, array($id, $return_id));

			// Potential directly linked with Contact (not through Account - aicrm_contpotentialrel)
			$directRelCheck = $this->db->pquery('SELECT related_to FROM aicrm_potential WHERE potentialid=? AND related_to=?', array($id, $return_id));
			if($this->db->num_rows($directRelCheck)) {
				$this->trash($this->module_name, $id);
			}

		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}
}

?>
