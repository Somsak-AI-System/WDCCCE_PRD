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

class Sparepartlist extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_sparepartlist";
	var $table_index= 'sparepartlistid';

	var $tab_name = Array('aicrm_crmentity','aicrm_sparepartlist','aicrm_sparepartlistcf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_sparepartlist'=>'sparepartlistid','aicrm_sparepartlistcf'=>'sparepartlistid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_sparepartlistcf', 'sparepartlistid');
	var $column_fields = Array();

	var $sortby_fields = Array('sparepartlistid','sparepartlist_name','smownerid');

	var $list_fields = Array(
					'Spare Part List No'=>Array('Sparepartlist'=>'sparepartlist_no'),
					'Spare Part List Name'=>Array('Sparepartlist'=>'sparepartlist_name'),
					'Spare Part No'=>Array('Sparepartlist'=>'sparepartid'),
					'Spare Part Name'=>Array('Sparepartlist'=>'sparepart_name'),
					'Description'=>Array('Sparepartlist'=>'description'),
        			'Qty'=>Array('Sparepartlist'=>'quantity'),
        			'Lot No'=>Array('Sparepartlist'=>'lot_no'),
				);

	var $list_fields_name = Array(
					'Spare Part List No'=>'sparepartlist_no',
					'Spare Part List Name'=>'sparepartlist_name',
					'Spare Part No'=>'sparepartid',
					'Spare Part Name'=>'sparepart_name',
					'Description'=>'description',
        			'Qty'=>'quantity',
					'Lot No'=>'lot_no',
				     );	  			

	var $list_link_field= 'sparepartlist_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'desc';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
			'Spare Part List No'=>Array('aicrm_sparepartlist'=>'sparepartlist_no'),
			'Spare Part List Name'=>Array('aicrm_sparepartlist'=>'sparepartlist_name'),

			);

	var $search_fields_name = Array(
			'Spare Part List No'=>'sparepartlist_no',
			'Spare Part List Name'=>'sparepartlist_name',
			
			);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','sparepartlistid');
	
	function Sparepartlist() 
	{
		$this->log =LoggerManager::getLogger('Sparepartlist');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Sparepartlist');
	}
	function save_module()
	{
		global $adb;
		
		//Calculate Cut stock sparepart
		if($this->mode == '') {
			include("config.inc.php");
			include_once("library/dbconfig.php");
			include_once("library/myLibrary_mysqli.php");
			$myLibrary_mysqli = new myLibrary_mysqli();
			$myLibrary_mysqli->_dbconfig = $dbconfig;

			if($this->column_fields['sparepartid'] != '' && $this->column_fields['quantity'] != ''){

				$sql_update = "UPDATE aicrm_sparepart SET stock_qty = (stock_qty - ".$this->column_fields['quantity'].") where sparepartid = '".$this->column_fields['sparepartid']."' ";
				$myLibrary_mysqli->Query($sql_update);

			}//if
		
		}//if action
				
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
			$sorder = (($_SESSION['SPAREPARTLIST_SORT_ORDER'] != '')?($_SESSION['SPAREPARTLIST_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['SPAREPARTLIST_ORDER_BY'] != '')?($_SESSION['SPAREPARTLIST_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	
	
	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule)
    {
        $query = $this->getRelationQuery($module, $secmodule, "aicrm_sparepartlist", "sparepartlistid");
        //echo $query ;exit;
        $query .= " left join aicrm_crmentity as aicrm_crmentitySparepartlist on aicrm_crmentitySparepartlist.crmid=aicrm_sparepartlist.sparepartlistid and aicrm_crmentitySparepartlist.deleted=0 
				left join aicrm_sparepartlistcf on aicrm_sparepartlistcf.sparepartlistid = aicrm_crmentitySparepartlist.crmid 
				left join aicrm_groups as aicrm_groupsSparepartlist on aicrm_groupsSparepartlist.groupid = aicrm_crmentitySparepartlist.smownerid
				left join aicrm_users as aicrm_usersSparepartlist on aicrm_usersSparepartlist.id = aicrm_crmentitySparepartlist.smownerid
				left join aicrm_users as aicrm_usersModifiedSparepartlist on aicrm_crmentity.modifiedby=aicrm_usersModifiedSparepartlist.id
                left join aicrm_users as aicrm_usersCreatorSparepartlist on aicrm_crmentity.smcreatorid=aicrm_usersModifiedSparepartlist.id";

		if($module == "Job"){

		}elseif ($module == "Sparepart") {
            $query .=" LEFT JOIN aicrm_jobs on aicrm_jobs.jobid = aicrm_sparepartlist.jobid";
        }else {
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountJob ON aicrm_accountJob.accountid = aicrm_jobs.accountid";
        }
		return $query;
	}

	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Sparepartlist", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_sparepartlist ON aicrm_sparepartlist.sparepartlistid = aicrm_crmentity.crmid
				INNER JOIN aicrm_sparepartlistcf ON aicrm_sparepartlistcf.sparepartlistid = aicrm_sparepartlist.sparepartlistid
				LEFT JOIN aicrm_sparepart	ON aicrm_sparepart.sparepartid = aicrm_sparepartlist.sparepartid
				LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_sparepartlist.jobid
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
			$query = $query." ".getListViewSecurityParameter("Sparepartlist");
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
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_sparepartlist"=>"sparepartlistid"),
		);
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Job') {
			$relation_query = 'UPDATE aicrm_sparepartlist SET jobid=0 WHERE sparepartlistid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
        } elseif($return_module == 'Sparepart') {
            $relation_query = 'UPDATE aicrm_sparepartlist SET sparepartid=0 WHERE sparepartlistid=?';
            $this->db->pquery($relation_query, array($id));
        } else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
