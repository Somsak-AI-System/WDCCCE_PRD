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

class Errorslist extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_errorslist";
	var $table_index= 'errorslistid';

	var $tab_name = Array('aicrm_crmentity','aicrm_errorslist','aicrm_errorslistcf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_errorslist'=>'errorslistid','aicrm_errorslistcf'=>'errorslistid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_errorslistcf', 'errorslistid');
	var $column_fields = Array();

	var $sortby_fields = Array('errorslistid','errorslist_name','smownerid');

	var $list_fields = Array(
					'Errors List No'=>Array('Errorslist'=>'errorslist_no'),
					'Errors List Name'=>Array('Errorslist'=>'errorslist_name'),
					'Errors No'=>Array('Errors'=>'errorsid'),
					'Errors Name'=>Array('Errors'=>'errors_name'),
					'Description'=>Array('Errorslist'=>'description'),
	);

	var $list_fields_name = Array(
					'Errors List No'=>'errorslist_no',
					'Errors List Name'=>'errorslist_name',
					'Errors No'=>'errorsid',
					'Errors Name'=>'errors_name',
					'Description'=>'description',	
	);	  			

	var $list_link_field= 'errorslist_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'desc';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');
	var $search_fields = Array(
			'Errors List No'=>Array('aicrm_errorslist'=>'errorslist_no'),
			'Errors List Name'=>Array('aicrm_errorslist'=>'errorslist_name'),
	);

	var $search_fields_name = Array(
			'Errors List No'=>'errorslist_no',
			'Errors List Name'=>'errorslist_name',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','errorslistid');
	
	function Errorslist() 
	{
		$this->log =LoggerManager::getLogger('Errorslist');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Errorslist');
	}
	function save_module()
	{
		global $adb;
	
	}	
	
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['ERRORSLIST_SORT_ORDER'] != '')?($_SESSION['ERRORSLIST_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['ERRORSLIST_ORDER_BY'] != '')?($_SESSION['ERRORSLIST_ORDER_BY']):($use_default_order_by));
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

		$query = $this->getRelationQuery($module,$secmodule,"aicrm_errorslist","errorslistid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityErrorslist on aicrm_crmentityErrorslist.crmid=aicrm_errorslist.errorslistid and aicrm_crmentityErrorslist.deleted=0 
				left join aicrm_errorslistcf on aicrm_errorslistcf.errorslistid = aicrm_crmentityErrorslist.crmid 
				left join aicrm_groups as aicrm_groupsErrorslist on aicrm_groupsErrorslist.groupid = aicrm_crmentityErrorslist.smownerid
				left join aicrm_users as aicrm_usersErrorslist on aicrm_usersErrorslist.id = aicrm_crmentityErrorslist.smownerid
				left join aicrm_users as aicrm_usersModifiedErrorslist on aicrm_crmentity.smcreatorid=aicrm_usersModifiedErrorslist.id
                left join aicrm_users as aicrm_usersCreatorErrorslist on aicrm_crmentity.smcreatorid=aicrm_usersCreatorErrorslist.id";
        if($module == "Job") {
			
		}elseif ($module == "Errors" && $secmodule =="Errorslist"){
			$query .=" LEFT JOIN aicrm_errors as aicrm_errorsErrorslist on aicrm_errorsErrorslist.errorsid = aicrm_errorslist.errorsid 
					   left join aicrm_jobs as aicrm_jobsErrorslist on aicrm_jobsErrorslist.jobid = aicrm_errorslist.jobid
			";
		
        }elseif ($module == "Errors") {
            $query .=" LEFT JOIN aicrm_jobs on aicrm_jobs.jobid = aicrm_errorslist.jobid
            LEFT JOIN aicrm_account AS aicrm_accountJob on aicrm_accountJob.accountid = aicrm_jobs.accountid ";
		
        }else {
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountJob on aicrm_accountJob.accountid = aicrm_jobs.accountid";
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
		$sql = getPermittedFieldsQuery("Errorslist", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_errorslist ON aicrm_errorslist.errorslistid = aicrm_crmentity.crmid
				INNER JOIN aicrm_errorslistcf ON aicrm_errorslistcf.errorslistid = aicrm_errorslist.errorslistid
				LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_errorslist.jobid
				LEFT JOIN aicrm_errors on aicrm_errors.errorsid = aicrm_errorslist.errorsid
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
			$query = $query." ".getListViewSecurityParameter("Errorslist");
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
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_errorslist"=>"errorslistid"),
			"Job" => array("aicrm_jobs"=>array("errorslistid","jobid"),"aicrm_errorslist"=>"errorslistid"),
            //"Errors" => array("aicrm_errors"=>array("jobid","jobid"),"aicrm_errorslist"=>"errorslistid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Errors') {
			$relation_query = 'UPDATE aicrm_errorslist SET errorsid=0 WHERE errorslistid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Job') {
			$relation_query = 'UPDATE aicrm_errorslist SET jobid=0 WHERE errorslistid=?';
			$this->db->pquery($relation_query, array($id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
