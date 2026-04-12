<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Campaigns/Campaigns.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

class LeadManagement extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "aicrm_leadmanage";
	var $table_index= 'leadid';

	var $tab_name = Array('aicrm_crmentity','aicrm_leadmanage','aicrm_leadmanagesubdetail','aicrm_leadmanageaddress','aicrm_leadmanagecf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_leadmanage'=>'leadid','aicrm_leadmanagesubdetail'=>'leadsubscriptionid','aicrm_leadmanageaddress'=>'leadaddressid','aicrm_leadmanagecf'=>'leadid');

	var $entity_table = "aicrm_crmentity";

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_leadmanagecf', 'leadid');

	//construct this from database;
	var $column_fields = Array();
	var $sortby_fields = Array('firstname','lastname','email','phone','company','smownerid','website');

	// This is used to retrieve related aicrm_fields from form posts.
	var $additional_column_fields = Array('smcreatorid', 'smownerid', 'contactid','potentialid' ,'crmid');

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
		'First Name'=>Array('leaddetails'=>'firstname'),
		'Last Name'=>Array('leaddetails'=>'lastname'),

		'Company'=>Array('leaddetails'=>'company'),
		'Phone'=>Array('leadaddress'=>'phone'),
		'Website'=>Array('leadsubdetails'=>'website'),
		'Email'=>Array('leaddetails'=>'email'),
		'Assigned To'=>Array('crmentity'=>'smownerid')
	);
	var $list_fields_name = Array(
		'First Name'=>'firstname',
		'Last Name'=>'lastname',

		'Company'=>'company',
		'Phone'=>'phone',
		'Website'=>'website',
		'Email'=>'email',
		'Assigned To'=>'assigned_user_id'
	);
	var $list_link_field= 'lastname';

	var $search_fields = Array(
		'First Name'=>Array('leaddetails'=>'firstname'),
		'Last Name'=>Array('leaddetails'=>'lastname'),
		'Company'=>Array('leaddetails'=>'company')
	);
	var $search_fields_name = Array(
		'First Name'=>'firstname',
		'Last Name'=>'lastname',
		'Company'=>'company'
	);

	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'lastname', 'createdtime' ,'modifiedtime');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','leadsource','leadstatus','rating','industry','yahooid','email','annualrevenue','designation','salutation');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	//var $groupTable = Array('aicrm_leadgrouprelation','leadid');

	function LeadManagement()	{
		$this->log = LoggerManager::getLogger('LeadManagement');
		$this->log->debug("Entering LeadManagement() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('LeadManagement');
		$this->log->debug("Exiting Lead method ...");
	}

	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module)
	{
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
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['LEADS_SORT_ORDER'] != '')?($_SESSION['LEADS_SORT_ORDER']):($this->default_sort_order));

		$log->debug("Exiting getSortOrder method ...");

		return $sorder;
	}

	/**
	 * Function to get order by
	 * return string  $order_by    - fieldname(eg: 'leadname')
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
			$order_by = (($_SESSION['LEADS_ORDER_BY'] != '')?($_SESSION['LEADS_ORDER_BY']):($use_default_order_by));

		$log->debug("Exiting getOrderBy method ...");

		return $order_by;
	}
	// Mike Crowe Mod --------------------------------------------------------



	/** Function to export the lead records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Leads Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("LeadManagement", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	      			FROM ".$this->entity_table."
				INNER JOIN aicrm_leadmanage
					ON aicrm_crmentity.crmid=aicrm_leadmanage.leadid
				LEFT JOIN aicrm_leadmanagesubdetail
					ON aicrm_leadmanage.leadid = aicrm_leadmanagesubdetail.leadsubscriptionid
				LEFT JOIN aicrm_leadmanageaddress
					ON aicrm_leadmanage.leadid=aicrm_leadmanageaddress.leadaddressid
				LEFT JOIN aicrm_leadmanagecf
					ON aicrm_leadmanagecf.leadid=aicrm_leadmanage.leadid
	                        LEFT JOIN aicrm_groups
                        	        ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users
					ON aicrm_crmentity.smownerid = aicrm_users.id and aicrm_users.status='Active'
				";

		$query .= setFromQuery("LeadManagement");
		$where_auto = " aicrm_crmentity.deleted=0 AND aicrm_leadmanage.converted =0";

		if($where != "")
			$query .= " where ($where) AND ".$where_auto;
		else
			$query .= " where ".$where_auto;

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("LeadManagement");
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}



	/** Returns a list of the associated tasks
 	 * @param  integer   $id      - leadid
 	 * returns related Task or Event record in array format
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

		$query = "SELECT aicrm_activity.*,aicrm_seactivityrel.*, aicrm_contactdetails.lastname, aicrm_contactdetails.contactid, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,aicrm_recurringevents.recurringtype from aicrm_activity inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid = aicrm_activity.activityid left join aicrm_contactdetails on aicrm_contactdetails.contactid = aicrm_cntactivityrel.contactid left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid left outer join aicrm_recurringevents on aicrm_recurringevents.activityid=aicrm_activity.activityid left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid where aicrm_seactivityrel.crmid=".$id." and aicrm_crmentity.deleted = 0 and ((aicrm_activity.activitytype='Task' and aicrm_activity.status not in ('Completed','Deferred')) or (aicrm_activity.activitytype NOT in ('Emails','Task') and  aicrm_activity.eventstatus not in ('','Held'))) ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/** Returns a list of the associated Campaigns
	  * @param $id -- campaign id :: Type Integer
	  * @returns list of campaigns in array format
	  */
	function get_campaigns($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_campaigns(".$id.") method ...");
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

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,
				aicrm_campaign.campaignid, aicrm_campaign.campaignname, aicrm_campaign.campaigntype, aicrm_campaign.campaignstatus,
				aicrm_campaign.expectedrevenue, aicrm_campaign.closingdate, aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
				aicrm_crmentity.modifiedtime from aicrm_campaign
				inner join aicrm_campaignleadrel on aicrm_campaignleadrel.campaignid=aicrm_campaign.campaignid
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_campaign.campaignid
				left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
				left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
				where aicrm_campaignleadrel.leadid=".$id." and aicrm_crmentity.deleted=0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_campaigns method ...");
		return $return_value;
	}


		/** Returns a list of the associated emails
	 	 * @param  integer   $id      - leadid
	 	 * returns related emails record in array format
		*/
	function get_emails($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_emails(".$id.") method ...");
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

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$query ="select case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name," .
				" aicrm_activity.activityid, aicrm_activity.subject, aicrm_activity.semodule, aicrm_activity.activitytype," .
				" aicrm_activity.date_start, aicrm_activity.status, aicrm_activity.priority, aicrm_crmentity.crmid," .
				" aicrm_crmentity.smownerid,aicrm_crmentity.modifiedtime, aicrm_users.user_name, aicrm_seactivityrel.crmid as parent_id " .
				" from aicrm_activity" .
				" inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid" .
				" inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid" .
				" left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid" .
				" inner join aicrm_users on  aicrm_users.id=aicrm_crmentity.smownerid" .
				" where aicrm_activity.activitytype='Emails' and aicrm_crmentity.deleted=0 and aicrm_seactivityrel.crmid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/**
	 * Function to get Lead related Task & Event which have activity type Held, Completed or Deferred.
	 * @param  integer   $id      - leadid
	 * returns related Task or Event record in array format
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT aicrm_activity.activityid, aicrm_activity.subject, aicrm_activity.status,
			aicrm_activity.eventstatus, aicrm_activity.activitytype,aicrm_activity.date_start,
			aicrm_activity.due_date,aicrm_activity.time_start,aicrm_activity.time_end,
			aicrm_crmentity.modifiedtime,aicrm_crmentity.createdtime,
			aicrm_crmentity.description, aicrm_users.user_name,aicrm_groups.groupname
				from aicrm_activity
				inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid
				left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
				left join aicrm_users on aicrm_crmentity.smownerid= aicrm_users.id
				where (aicrm_activity.activitytype = 'Meeting' or aicrm_activity.activitytype='Call' or aicrm_activity.activitytype='Task')
				and (aicrm_activity.status = 'Completed' or aicrm_activity.status = 'Deferred' or (aicrm_activity.eventstatus = 'Held' and aicrm_activity.eventstatus != ''))
				and aicrm_seactivityrel.crmid=".$id."
	                        and aicrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('LeadManagement',$query,$id);
	}

	/**
	* Function to get lead related Products
	* @param  integer   $id      - leadid
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
			   INNER JOIN aicrm_seproductsrel ON aicrm_products.productid = aicrm_seproductsrel.productid and aicrm_seproductsrel.setype = 'LeadManagement'
			   INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
			   INNER JOIN aicrm_leadmanage ON aicrm_leadmanage.leadid = aicrm_seproductsrel.crmid
			   WHERE aicrm_crmentity.deleted = 0 AND aicrm_leadmanage.leadid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/** Function to get the Combo List Values of Leads Field
	 * @param string $list_option
	 * Returns Combo List Options
	*/
	function get_lead_field_options($list_option)
	{
		global $log;
		$log->debug("Entering get_lead_field_options(".$list_option.") method ...");
		$comboFieldArray = getComboArray($this->combofieldNames);
		$log->debug("Exiting get_lead_field_options method ...");
		return $comboFieldArray[$list_option];
	}

	/** Function to get the Columnnames of the Leads Record
	* Used By vtigerCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames_Lead()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Lead() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select fieldlabel from aicrm_field where tabid=7 and aicrm_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select aicrm_field.fieldid,fieldlabel from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=7 and aicrm_field.displaytype in (1,2,3,4) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and aicrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
				array_push($params1, $profileList);
			}
		}
		$result = $this->db->pquery($sql1, $params1);
		$numRows = $this->db->num_rows($result);
		for($i=0; $i < $numRows;$i++)
		{
	   	$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
	   	$custom_fields[$i] = ereg_replace(" ","",$custom_fields[$i]);
	   	$custom_fields[$i] = strtoupper($custom_fields[$i]);
		}
		$mergeflds = $custom_fields;
		$log->debug("Exiting getColumnNames_Lead method ...");
		return $mergeflds;
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

		$rel_table_arr = Array("Activities"=>"aicrm_seactivityrel","Documents"=>"aicrm_senotesrel","Attachments"=>"aicrm_seattachmentsrel",
					"Products"=>"aicrm_seproductsrel","Campaigns"=>"aicrm_campaignleadrel");

		$tbl_field_arr = Array("aicrm_seactivityrel"=>"activityid","aicrm_senotesrel"=>"notesid","aicrm_seattachmentsrel"=>"attachmentsid",
					"aicrm_seproductsrel"=>"productid","aicrm_campaignleadrel"=>"campaignid");

		$entity_tbl_field_arr = Array("aicrm_seactivityrel"=>"crmid","aicrm_senotesrel"=>"crmid","aicrm_seattachmentsrel"=>"crmid",
					"aicrm_seproductsrel"=>"crmid","aicrm_campaignleadrel"=>"leadid");

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
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_leadmanage","leadid");
		$query .= " left join aicrm_crmentity as aicrm_crmentityLeads on aicrm_crmentityLeads.crmid = aicrm_leadmanage.leadid and aicrm_crmentityLeads.deleted=0
			left join aicrm_leadmanageaddress on aicrm_leadmanage.leadid = aicrm_leadmanageaddress.leadaddressid
			left join aicrm_leadmanagesubdetail on aicrm_leadmanagesubdetail.leadsubscriptionid = aicrm_leadmanage.leadid
			left join aicrm_leadmanagecf on aicrm_leadmanagecf.leadid = aicrm_leadmanage.leadid
			left join aicrm_groups as aicrm_groupsLeads on aicrm_groupsLeads.groupid = aicrm_crmentityLeads.smownerid
			left join aicrm_users as aicrm_usersLeads on aicrm_usersLeads.id = aicrm_crmentityLeads.smownerid ";

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Calendar" => array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_leadmanage"=>"leadid"),
			"Products" => array("aicrm_seproductsrel"=>array("crmid","productid"),"aicrm_leadmanage"=>"leadid"),
			"Campaigns" => array("aicrm_campaignleadrel"=>array("leadid","campaignid"),"aicrm_leadmanage"=>"leadid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_leadmanage"=>"leadid"),
			"Services" => array("aicrm_crmentityrel"=>array("crmid","relcrmid"),"aicrm_leadmanage"=>"leadid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Campaigns') {
			$sql = 'DELETE FROM aicrm_campaignleadrel WHERE leadid=? AND campaignid=?';
			$this->db->pquery($sql, array($id, $return_id));
		}
		elseif($return_module == 'Products') {
			$sql = 'DELETE FROM aicrm_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'EmailTargetList') {
			$relation_query = 'DELETE FROM aicrm_emailtargetlist_leadsrel    WHERE leadid    =? AND  emailtargetlistid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

//End

}

?>