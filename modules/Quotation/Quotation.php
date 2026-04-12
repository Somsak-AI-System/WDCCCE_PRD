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

class Quotation extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_quotation";
	var $table_index= 'quotationid';

	var $tab_name = Array('aicrm_crmentity','aicrm_quotation','aicrm_quotationcf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_quotation'=>'quotationid','aicrm_quotationcf'=>'quotationid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_quotationcf', 'quotationid');
	var $column_fields = Array();

	var $sortby_fields = Array('quotationid','quotation_name','smownerid');

	var $list_fields = Array(
					'Quotation No'=>Array('Quotation'=>'quotation_no'),
					'Project Name'=>Array('Quotation'=>'quotation_name'),
					'Building Name'=>Array('Quotation'=>'quotation_name'),
					'Units Name'=>Array('Quotation'=>'quotation_name'),
					'Create Date'=>Array('aicrm_crmentity'=>'createdtime'),

				);
				//'Assigned To' => Array('crmentity'=>'smownerid')

	var $list_fields_name = Array(
					'Quotation No'=>'quotation_no',
					'Quotation Name'=>'quotation_name',
					'Email'=>'cf_916',
					'Assigned To'=>'assigned_user_id'
				     );

	var $list_link_field= 'quotation_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
			'Quotation No'=>Array('aicrm_quotation'=>'quotation_no'),
			'Quotation Name'=>Array('aicrm_quotation'=>'quotation_name'),
			//'Email'=>Array('aicrm_quotationcf'=>'cf_916'),
			);

	var $search_fields_name = Array(
			'Quotation No'=>'quotation_no',
			'Quotation Name'=>'quotation_name',
			//'Email'=>'cf_916',
			);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','quotationid');

	function Quotation()
	{
		$this->log =LoggerManager::getLogger('Quotation');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Quotation');
	}
	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'QuotationAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'Quotation');
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
			$sorder = (($_SESSION['QUOTATION_SORT_ORDER'] != '')?($_SESSION['QUOTATION_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['QUOTATION_ORDER_BY'] != '')?($_SESSION['QUOTATION_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/**	function used to get the list of sales orders which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
//	function get_salesorder($id)
//	{
//		global $log,$singlepane_view;
//		$log->debug("Entering get_salesorder(".$id.") method ...");
//		require_once('modules/SalesOrder/SalesOrder.php');
//	        $focus = new SalesOrder();
//
//		$button = '';
//
//		if($singlepane_view == 'true')
//			$returnset = '&return_module=Quotes&return_action=DetailView&return_id='.$id;
//		else
//			$returnset = '&return_module=Quotes&return_action=CallRelatedList&return_id='.$id;
//
//		$query = "select aicrm_crmentity.*, aicrm_salesorder.*, aicrm_quotes.subject as quotename, aicrm_account.accountname,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
//		from aicrm_salesorder
//		inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_salesorder.salesorderid
//		left outer join aicrm_quotes on aicrm_quotes.quoteid=aicrm_salesorder.quoteid
//		left outer join aicrm_account on aicrm_account.accountid=aicrm_salesorder.accountid
//		left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
//		left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
//		where aicrm_crmentity.deleted=0 and aicrm_salesorder.quoteid = ".$id;
//		$log->debug("Exiting get_salesorder method ...");
//		return GetRelatedList('Quotes','SalesOrder',$focus,$query,$button,$returnset);
//	}

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
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_quotation","quotationid");
		//echo $query ;exit;
		$query .=" left join aicrm_crmentity as aicrm_crmentityEmailtarget on aicrm_crmentityEmailtarget.crmid=aicrm_quotation.quotationid and aicrm_crmentityEmailtarget.deleted=0
				left join aicrm_quotationcf on aicrm_quotationcf.quotationid = aicrm_crmentityEmailtarget.crmid
				left join aicrm_groups as aicrm_groupsEmailtarget on aicrm_groupsEmailtarget.groupid = aicrm_crmentityEmailtarget.smownerid
				left join aicrm_users as aicrm_usersEmailtarget on aicrm_usersEmailtarget.id = aicrm_crmentityEmailtarget.smownerid";
		return $query;
	}

	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Quotation", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       			FROM aicrm_crmentity

				INNER JOIN aicrm_quotation
				ON aicrm_quotation.quotationid = aicrm_crmentity.crmid
				INNER JOIN aicrm_quotationcf
				ON aicrm_quotationcf.quotationid = aicrm_quotation.quotationid
				LEFT JOIN aicrm_groups
				ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users
				ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				";
		$query .= setFromQuery("Quotation");
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
			$query = $query." ".getListViewSecurityParameter("Quotation");
		}
		$log->debug("Exiting create_export_query method ...");
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

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' ) {
			$this->trash('Quotation',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'QuotationList') {
			$relation_query = 'DELETE FROM aicrm_quotationlist_quotationrel WHERE quotationid =? AND  quotationlistid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
