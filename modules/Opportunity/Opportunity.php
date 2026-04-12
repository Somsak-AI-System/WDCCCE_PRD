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

class Opportunity extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_opportunity";
	var $table_index= 'opportunityid';

	var $tab_name = Array('aicrm_crmentity','aicrm_opportunity','aicrm_opportunitycf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_opportunity'=>'opportunityid','aicrm_opportunitycf'=>'opportunityid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_opportunitycf', 'opportunityid');
	var $column_fields = Array();

	var $sortby_fields = Array('opportunityid','opportunity_name','smownerid','product_id');

	var $list_fields = Array(
					'Opportunity No'=>Array('Opportunity'=>'opportunity_no'),
					'Project Name'=>Array('Opportunity'=>'branch_name'),
					'Unit Name'=>Array('aicrm_prosucts'=>'productname'),
					'Account Name'=>Array('Opportunity'=>'accountname'),
					'Account Grade'=>Array('Opportunity'=>'accountgrade'),
					'Mobile'=>Array('Opportunity'=>'cf_2351'),
					'E-Mail'=>Array('Opportunity'=>'email'),
					'จำนวนครั้งที่เข้าชมโครงการ'=>Array('Opportunity'=>'cf_2203'),
					//'Assigned To' => Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
					'Opportunity No'=>'opportunity_no',
					'Project Name'=>'branch_name',
					'Unit Name'=>'productname',
					'Account Name'=>'accountname',
					'Account Grade'=>'accountgrade',
					'Mobile'=>'cf_2351',
					'E-Mail'=>'email',
					'จำนวนครั้งที่เข้าชมโครงการ'=>'cf_2203',
					//'Account Name'=>'accountname',
				     );

	var $list_link_field= 'opportunity_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
			'Opportunity No'=>Array('Opportunity'=>'opportunity_no'),
			'Opportunity Name'=>Array('Opportunity'=>'opportunity_name'),
			'Account Source'=>Array('Opportunity'=>'cf_3477'),
			'Account Grade'=>Array('Opportunity'=>'accountgrade'),
			//'Account Name'=>Array('aicrm_account'=>'accountname'),
			);

	var $search_fields_name = Array(
			'Opportunity No'=>'opportunity_no',
			'Opportunity Name'=>'opportunity_name',
			'Account Source'=>'cf_3477',
			'Account Grade'=>'accountgrade',
			//'Account Name'=>'accountname',
			);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','opportunityid');

	function Opportunity()
	{
		$this->log =LoggerManager::getLogger('Opportunity');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Opportunity');
	}
	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'OpportunityAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'Opportunity');
		}

		// Update the currency id and the conversion rate for the quotes
		//$update_query = "update aicrm_quotes set currency_id=?, conversion_rate=? where quoteid=?";
//		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
//		$adb->pquery($update_query, $update_params);
	}

	/**	Function used to get the sort order for Quote listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['QUOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned.
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['OPPORTUNTY_SORT_ORDER'] != '')?($_SESSION['OPPORTUNTY_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for Quotes listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['QUOTES_ORDER_BY'] if this session value is empty then default order by will be returned.
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
			$order_by = (($_SESSION['OPPORTUNTY_ORDER_BY'] != '')?($_SESSION['OPPORTUNTY_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/**	function used to get the list of activities which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
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
			}
		}

		$query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, aicrm_contactdetails.contactid, aicrm_contactdetails.lastname, aicrm_contactdetails.firstname, aicrm_activity.*,aicrm_seactivityrel.*,aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime,aicrm_recurringevents.recurringtype from aicrm_activity inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid= aicrm_activity.activityid left join aicrm_contactdetails on aicrm_contactdetails.contactid = aicrm_cntactivityrel.contactid left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid left outer join aicrm_recurringevents on aicrm_recurringevents.activityid=aicrm_activity.activityid left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid where aicrm_seactivityrel.crmid=".$id." and aicrm_crmentity.deleted=0 and activitytype='Task' and (aicrm_activity.status is not NULL and aicrm_activity.status != 'Completed') and (aicrm_activity.status is not NULL and aicrm_activity.status != 'Deferred')";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}
	
	function get_smartsms($id, $cur_tab_id, $rel_tab_id, $actions=false) {
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

		$query = "SELECT aicrm_opportunity.*,aicrm_opportunitycf.*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_smartsms.*,aicrm_smartsmscf.*,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_smartsms_opportunityrel 
			LEFT JOIN aicrm_opportunity ON aicrm_opportunity.opportunityid = aicrm_smartsms_opportunityrel.opportunityid
			LEFT JOIN aicrm_opportunitycf ON aicrm_opportunitycf.opportunityid = aicrm_opportunity.opportunityid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms_opportunityrel.smartsmsid
			LEFT JOIN aicrm_smartsms ON aicrm_smartsms.smartsmsid = aicrm_smartsms_opportunityrel.smartsmsid
			LEFT JOIN aicrm_smartsmscf ON aicrm_smartsmscf.smartsmsid = aicrm_smartsms.smartsmsid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_smartsms_opportunityrel.opportunityid = ".$id;
			//echo 		$query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
			$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}
	
	function get_smartemail($id, $cur_tab_id, $rel_tab_id, $actions=false) {
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

		$query = "SELECT aicrm_opportunity.*,aicrm_opportunitycf .*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_smartemail.*,aicrm_smartemailcf.*,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_smartemail_opportunityrel 
			LEFT JOIN aicrm_opportunity ON aicrm_opportunity.opportunityid = aicrm_smartemail_opportunityrel.opportunityid
			LEFT JOIN aicrm_opportunitycf ON aicrm_opportunitycf.opportunityid = aicrm_opportunity.opportunityid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartemail_opportunityrel.smartemailid
			LEFT JOIN aicrm_smartemail ON aicrm_smartemail.smartemailid = aicrm_smartemail_opportunityrel.smartemailid
			LEFT JOIN aicrm_smartemailcf ON aicrm_smartemailcf.smartemailid = aicrm_smartemail.smartemailid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_smartemail_opportunityrel.opportunityid = ".$id;
			//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
			$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}
	
	function get_warrantys($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $adb,$log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_warrantys(".$id.") method ...");
		$this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		//echo $id;
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);
		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';
		$sql="select cf_925 from aicrm_opportunitycf where opportunityid=$id";
		$data = $adb->pquery($sql,'');
		$cf_925=$adb->query_result($data, 0, 'cf_925');
		//print_r($cf_925);
		$query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_warrantys.*,
			aicrm_warrantyscf.*,
			aicrm_account.*
			FROM aicrm_warrantys
			LEFT JOIN aicrm_warrantyscf ON aicrm_warrantyscf.warrantyid = aicrm_warrantys.warrantyid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_warrantys.warrantyid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			LEFT JOIN aicrm_account ON aicrm_account.accountid=aicrm_warrantys.accountid
			WHERE aicrm_crmentity.deleted = 0
			and aicrm_warrantyscf.cf_913='".$cf_925."'
			";
		//echo $query."<br>";
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}
	/**	function used to get the the activity history related to the quote
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetHistory
	 */
//	function get_history($id)
//	{
//		global $log;
//		$log->debug("Entering get_history(".$id.") method ...");
//		$query = "SELECT aicrm_activity.activityid, aicrm_activity.subject, aicrm_activity.status,
//			aicrm_activity.eventstatus, aicrm_activity.activitytype,aicrm_activity.date_start,
//			aicrm_activity.due_date,aicrm_activity.time_start, aicrm_activity.time_end,
//			aicrm_contactdetails.contactid,
//			aicrm_contactdetails.firstname,aicrm_contactdetails.lastname, aicrm_crmentity.modifiedtime,
//			aicrm_crmentity.createdtime, aicrm_crmentity.description, case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
//			from aicrm_activity
//				inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid
//				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid
//				left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid= aicrm_activity.activityid
//				left join aicrm_contactdetails on aicrm_contactdetails.contactid= aicrm_cntactivityrel.contactid
//                                left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
//				left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
//				where aicrm_activity.activitytype='Task'
//  				and (aicrm_activity.status = 'Completed' or aicrm_activity.status = 'Deferred')
//	 	        	and aicrm_seactivityrel.crmid=".$id."
//                                and aicrm_crmentity.deleted = 0";
//		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
//
//		$log->debug("Exiting get_history method ...");
//		return getHistory('projects',$query,$id);
//	}
//
//
//
//
//
//	/**	Function used to get the Quote Stage history of the Quotes
//	 *	@param $id - quote id
//	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
//	 */
//	function get_quotestagehistory($id)
//	{
//		global $log;
//		$log->debug("Entering get_quotestagehistory(".$id.") method ...");
//
//		global $adb;
//		global $mod_strings;
//		global $app_strings;
//
//		$query = 'select aicrm_quotestagehistory.*, aicrm_quotes.quote_no from aicrm_quotestagehistory inner join aicrm_quotes on aicrm_quotes.quoteid = aicrm_quotestagehistory.quoteid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_quotes.quoteid where aicrm_crmentity.deleted = 0 and aicrm_quotes.quoteid = ?';
//		$result=$adb->pquery($query, array($id));
//		$noofrows = $adb->num_rows($result);
//
//		$header[] = $app_strings['Quote No'];
//		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
//		$header[] = $app_strings['LBL_AMOUNT'];
//		$header[] = $app_strings['Quote Stage'];
//		$header[] = $app_strings['LBL_LAST_MODIFIED'];
//
//		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
//		//Account Name , Total are mandatory fields. So no need to do security check to these fields.
//		global $current_user;
//
//		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
//		$quotestage_access = (getFieldVisibilityPermission('projects', $current_user->id, 'quotestage') != '0')? 1 : 0;
//		$picklistarray = getAccessPickListValues('projects');
//
//		$quotestage_array = ($quotestage_access != 1)? $picklistarray['quotestage']: array();
//		//- ==> picklist field is not permitted in profile
//		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
//		$error_msg = ($quotestage_access != 1)? 'Not Accessible': '-';
//
//		while($row = $adb->fetch_array($result))
//		{
//			$entries = Array();
//
//			// Module Sequence Numbering
//			//$entries[] = $row['quoteid'];
//			$entries[] = $row['quote_no'];
//			// END
//			$entries[] = $row['accountname'];
//			$entries[] = $row['total'];
//			$entries[] = (in_array($row['quotestage'], $quotestage_array))? $row['quotestage']: $error_msg;
//			$entries[] = getDisplayDate($row['lastmodified']);
//
//			$entries_list[] = $entries;
//		}
//
//		$return_data = Array('header'=>$header,'entries'=>$entries_list);
//
//	 	$log->debug("Exiting get_quotestagehistory method ...");
//
//		return $return_data;
//	}
//
//	// Function to get column name - Overriding function of base class
//	function get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype='') {
//		if ($columname == 'potentialid' || $columname == 'contactid') {
//			if ($fldvalue == '') return null;
//		}
//		return parent::get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype);
//	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_opportunity","opportunityid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityOpportunity on aicrm_crmentityOpportunity.crmid=aicrm_opportunity.opportunityid and aicrm_crmentityOpportunity.deleted=0
				left join aicrm_opportunitycf on aicrm_opportunitycf.opportunityid = aicrm_crmentityOpportunity.crmid
				left join aicrm_groups as aicrm_groupsopportunity on aicrm_groupsopportunity.groupid = aicrm_crmentityOpportunity.smownerid
				left join aicrm_users as aicrm_usersopportunity on aicrm_usersopportunity.id = aicrm_crmentityOpportunity.smownerid
				left join aicrm_account as aicrm_accountOpportunity on aicrm_accountOpportunity.accountid=aicrm_opportunity.accountid
				";

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"SalesOrder" =>array("aicrm_salesorder"=>array("quoteid","salesorderid"),"aicrm_quotes"=>"quoteid"),
			"Calendar" =>array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_quotes"=>"quoteid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_quotes"=>"quoteid"),
			"Accounts" => array("aicrm_quotes"=>array("quoteid","accountid")),
			"Contacts" => array("aicrm_quotes"=>array("quoteid","contactid")),
			"Potentials" => array("aicrm_quotes"=>array("quoteid","potentialid")),
		);
		return $rel_tables[$secmodule];
	}
	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Opportunity", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       			FROM aicrm_crmentity

				INNER JOIN aicrm_opportunity
				ON aicrm_opportunity.opportunityid = aicrm_crmentity.crmid
				INNER JOIN aicrm_opportunitycf
				ON aicrm_opportunitycf.opportunityid = aicrm_opportunity.opportunityid
				LEFT JOIN aicrm_groups
				ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users
				ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_account
				ON aicrm_account.accountid = aicrm_opportunity.accountid
				";
		$query .= setFromQuery("Opportunity");
		$where_auto = " aicrm_crmentity.deleted = 0 ";

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
			$query = $query." ".getListViewSecurityParameter("Opportunity");
		}
		$log->debug("Exiting create_export_query method ...");
		return $query;
	}
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {//echo $return_id;exit;
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' ) {
			$this->trash('Opportunity',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'SmartSms') {
			$relation_query = "DELETE FROM aicrm_smartsms_opportunityrel WHERE smartsmsid ='".$return_id."' AND  opportunityid='".$id."'";
			//echo $relation_query;exit;
			$this->db->pquery($relation_query, array());
		} elseif($return_module == 'Smartemail') {
			$relation_query = "DELETE FROM aicrm_smartemail_opportunityrel WHERE smartemailid ='".$return_id."' AND  opportunityid='".$id."'";
			$this->db->pquery($relation_query, array());
			
			$relationrel_query = 'DELETE FROM aicrm_crmentityrel WHERE  relcrmid=? AND crmid=?';
			$this->db->pquery($relationrel_query, array($id, $return_id));
			
		}  elseif($return_module == 'EmailTargetList') {
			$relation_query = "DELETE FROM aicrm_emailtargetlist_opportunityrel WHERE emailtargetlistid ='".$return_id."' AND  opportunityid='".$id."'";
			//echo $relation_query;exit;
			$this->db->pquery($relation_query, array());
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}


?>
