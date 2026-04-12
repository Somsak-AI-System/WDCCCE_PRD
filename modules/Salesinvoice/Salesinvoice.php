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

class Salesinvoice extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_salesinvoice";
	var $table_index= 'salesinvoiceid';

	var $tab_name = Array('aicrm_crmentity','aicrm_salesinvoice','aicrm_salesinvoicecf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_salesinvoice'=>'salesinvoiceid','aicrm_salesinvoicecf'=>'salesinvoiceid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_salesinvoicecf', 'salesinvoiceid');
	var $column_fields = Array();

	var $sortby_fields = Array('salesinvoiceid','salesinvoice_name','smownerid');

	var $list_fields = Array(
		'Sales Invoice No'=>Array('Salesinvoice'=>'salesinvoice_no'),
		'Sales Invoice Name'=>Array('Salesinvoice'=>'salesinvoice_name'),
	);

	var $list_fields_name = Array(
		'Sales Invoice No'=>'salesinvoice_no',
		'Sales Invoice Name'=>'salesinvoice_name',
	);	  			

	var $list_link_field= 'salesinvoice_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'desc';

	var $search_fields = Array(
		'Sales Invoice No'=>Array('aicrm_salesinvoice'=>'salesinvoice_no'),
		'Sales Invoice Name'=>Array('aicrm_salesinvoice'=>'salesinvoice_name'),
	);

	var $search_fields_name = Array(
		'Sales Invoice No'=>'salesinvoice_no',
		'Sales Invoice Name'=>'salesinvoice_name',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','salesinvoiceid');
	
	function Salesinvoice() 
	{
		$this->log =LoggerManager::getLogger('Salesinvoice');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Salesinvoice');
	}
	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'SalesinvoiceAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'Salesinvoice');
		}
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
			$sorder = (($_SESSION['SALESINVOICE_SORT_ORDER'] != '')?($_SESSION['SALESINVOICE_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['SALESINVOICE_ORDER_BY'] != '')?($_SESSION['SALESINVOICE_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_salesinvoice","salesinvoiceid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityEmailtarget on aicrm_crmentityEmailtarget.crmid=aicrm_salesinvoice.salesinvoiceid and aicrm_crmentityEmailtarget.deleted=0 
				left join aicrm_salesinvoicecf on aicrm_salesinvoicecf.salesinvoiceid = aicrm_crmentityEmailtarget.crmid 
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
		$sql = getPermittedFieldsQuery("Salesinvoice", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_salesinvoice ON aicrm_salesinvoice.salesinvoiceid = aicrm_crmentity.crmid
				INNER JOIN aicrm_salesinvoicecf ON aicrm_salesinvoicecf.salesinvoiceid = aicrm_salesinvoice.salesinvoiceid
				LEFT JOIN aicrm_account on aicrm_account.accountid = aicrm_salesinvoice.accountid
                LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_salesinvoice.product_id
				LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
				";
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
			$query = $query." ".getListViewSecurityParameter("Salesinvoice");
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
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_salesinvoice"=>"salesinvoiceid"),
		);
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
