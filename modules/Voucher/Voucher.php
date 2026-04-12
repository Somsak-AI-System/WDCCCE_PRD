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

class Voucher extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_voucher";
	var $table_index= 'voucherid';
	var $_images_path= 'upload/voucher/';

	var $tab_name = Array('aicrm_crmentity','aicrm_voucher','aicrm_vouchercf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_voucher'=>'voucherid','aicrm_vouchercf'=>'voucherid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_vouchercf', 'voucherid');
	var $column_fields = Array();

	var $sortby_fields = Array('voucherid','voucher_name','smownerid');

	var $list_fields = Array(
		'หมายเลขบัตรกำนัล'=>Array('Voucher'=>'voucher_no'),
		'ชื่อบัตรกำนัล'=>Array('Voucher'=>'voucher_name'),
		'วันที่เริ่มใช้งานบัตรกำนัล'=>Array('Voucher'=>'startdate'),
		'วันที่สิ้นสุดการใช้บัตรกำนัล'=>Array('Voucher'=>'enddate'),
		'มูลค่าบัตรกำนัล'=>Array('Voucher'=>'value'),
		'ข้อความบัตรกำนัล'=>Array('Voucher'=>'vouchermessage'),
		'สถานะของบัตรกำนัล'=>Array('Voucher'=>'voucher_status'),
		'ผู้รับผิดชอบ' => Array('crmentity'=>'smownerid')
	);

	var $list_fields_name = Array(
		'หมายเลขบัตรกำนัล'=>'voucher_no',
		'ชื่อบัตรกำนัล'=>'voucher_name',
		'วันที่เริ่มใช้งานบัตรกำนัล'=>'startdate',
		'วันที่สิ้นสุดการใช้บัตรกำนัล'=>'enddate',
		'มูลค่าบัตรกำนัล'=>'value',
		'ข้อความบัตรกำนัล'=>'vouchermessage',
		'สถานะของบัตรกำนัล'=>'voucher_status',
		'ผู้รับผิดชอบ'=>'assigned_user_id'
     );

	var $list_link_field= 'voucher_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
		'หมายเลขบัตรกำนัล'=>Array('aicrm_voucher'=>'voucher_no'),
		'ชื่อบัตรกำนัล'=>Array('aicrm_voucher'=>'voucher_name'),
		'ผู้รับผิดชอบ' => Array('crmentity'=>'smownerid')
	);

	var $search_fields_name = Array(
		'หมายเลขบัตรกำนัล'=>'voucher_no',
		'ชื่อบัตรกำนัล'=>'voucher_name',
		'ผู้รับผิดชอบ'=>'assigned_user_id'
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','voucherid');

	function Voucher()
	{
		$this->log =LoggerManager::getLogger('Voucher');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Voucher');
	}
	function save_module()
	{
		global $adb;
		$this->insertIntoAttachment($this->id,'Voucher');
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
			$sorder = (($_SESSION['VOUCHER_SORT_ORDER'] != '')?($_SESSION['VOUCHER_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['VOUCHER_ORDER_BY'] != '')?($_SESSION['VOUCHER_ORDER_BY']):($use_default_order_by));
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
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_voucher","voucherid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityVoucher on aicrm_crmentityVoucher.crmid=aicrm_voucher.voucherid and aicrm_crmentityVoucher.deleted=0
				left join aicrm_vouchercf on aicrm_vouchercf.voucherid = aicrm_crmentityVoucher.crmid
				left join aicrm_groups as aicrm_groupsVoucher on aicrm_groupsVoucher.groupid = aicrm_crmentityVoucher.smownerid
				left join aicrm_users as aicrm_usersVoucher on aicrm_usersVoucher.id = aicrm_crmentityVoucher.smownerid
				left join aicrm_users as aicrm_usersModifiedVoucher on aicrm_crmentity.smcreatorid=aicrm_usersModifiedVoucher.id
                left join aicrm_users as aicrm_usersCreatorVoucher on aicrm_crmentity.smcreatorid=aicrm_usersCreatorVoucher.id
				";

		if( ($module=='Accounts' && $secmodule =='Voucher') || $module=='Promotion' && $secmodule =='Voucher'){
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountVoucher ON aicrm_accountVoucher.accountid = aicrm_voucher.accountid";
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
		$sql = getPermittedFieldsQuery("Voucher", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_voucher ON aicrm_voucher.voucherid = aicrm_crmentity.crmid
				INNER JOIN aicrm_vouchercf ON aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
				LEFT JOIN aicrm_promotionvoucher ON aicrm_promotionvoucher.promotionvoucherid = aicrm_voucher.promotionvoucherid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
				";
		$query .= setFromQuery("Voucher");
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
			$query = $query." ".getListViewSecurityParameter("Voucher");
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
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_voucher"=>"voucherid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		if($return_module == 'Accounts' ) {
			$this->trash('Voucher',$id);
		} else if($return_module == 'Promotionvoucher'){
			$sql = "UPDATE aicrm_crmentity set deleted=1,modifiedtime=?,modifiedby=? where crmid=?";
			$params = array(date('Y-m-d H:i:s'), $_SESSION['user_id'] ,$id);
			$this->db->pquery($sql, $params);
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	function insertIntoAttachment($id,$module){
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				if($_REQUEST[$fileindex.'_hidden'] != '')
					$files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
				else
					$files['original_name'] = stripslashes($files['name']);
				$files['original_name'] = str_replace('"','',$files['original_name']);

				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		//Remove the deleted aicrm_attachments from db - Products
		if($module == 'Products' && $_REQUEST['del_file_list'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['del_file_list'],"###"));
			foreach($del_file_list as $del_file_name)
			{
				$attach_res = $adb->pquery("select aicrm_attachments.attachmentsid from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
				$attachments_id = $adb->query_result($attach_res,0,'attachmentsid');

				$del_res1 = $adb->pquery("delete from aicrm_attachments where attachmentsid=?", array($attachments_id));
				$del_res2 = $adb->pquery("delete from aicrm_seattachmentsrel where attachmentsid=?", array($attachments_id));
			}
		}else if($module == 'Voucher' && $_REQUEST['del_file_list'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['del_file_list'],"###"));
			foreach($del_file_list as $del_file_name)
			{
				$attach_res = $adb->pquery("select aicrm_attachments.attachmentsid from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
				$attachments_id = $adb->query_result($attach_res,0,'attachmentsid');
				$del_res1 = $adb->pquery("delete from aicrm_attachments where attachmentsid=?", array($attachments_id));
				$del_res2 = $adb->pquery("delete from aicrm_seattachmentsrel where attachmentsid=?", array($attachments_id));
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

}

?>
