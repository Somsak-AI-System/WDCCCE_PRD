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

class Purchasesorder extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_purchasesorder";
	var $table_index= 'purchasesorderid';
	var $table_comment = "aicrm_purchasesordercomments";

	var $tab_name = Array('aicrm_crmentity','aicrm_purchasesorder','aicrm_purchasesordercf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_purchasesorder'=>'purchasesorderid','aicrm_purchasesordercf'=>'purchasesorderid','aicrm_purchasesordercomments'=>'purchasesorderid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_purchasesordercf', 'purchasesorderid');
	var $column_fields = Array();

	var $sortby_fields = Array('purchasesorderid','purchasesorder_name','smownerid','purchasesorder_no','accountid','accountname','lastname');

	var $list_fields = Array(
		'Purchases Order No'=>Array('purchasesorder'=>'purchasesorder_no'),
		'Purchases Order Name'=>Array('purchasesorder'=>'purchasesorder_name'),
		'Purchase Order Status'=>Array('purchasesorder'=>'po_status'),
		'Purchase Order Date'=>Array('purchasesorder'=>'po_date'),
		
	);

	var $list_fields_name = Array(
		'Purchases Order No' =>'purchasesorder_no',
		'Purchases Order Name' =>'purchasesorder_name',
		'Purchase Order Status' =>'po_status',
		'Purchase Order Date' =>'po_date',
	);			

	var $list_link_field= 'purchasesorder_name';
	//var $list_link_field= 'purchasesorder_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'aicrm_crmentity.crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','purchasesorderid');

	var $search_fields = Array(
		'Purchases Order No'=>Array('aicrm_purchasesorder'=>'purchasesorder_no'),
		'Purchases Order Name'=>Array('aicrm_purchasesorder'=>'purchasesorder_name'),
		'Purchase Order Status'=>Array('purchasesorder'=>'po_status'),
		'Purchase Order Date'=>Array('purchasesorder'=>'po_date'),
	);

	var $search_fields_name = Array(
		'Purchases Order No'=>'purchasesorder_no',
		'Purchases Order Name'=>'purchasesorder_name',
		'Purchase Order Status' =>'po_status',
		'Purchase Order Date' =>'po_date',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','purchasesorder_name');
	
	function Purchasesorder() 
	{
		$this->log =LoggerManager::getLogger('Purchasesorder');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Purchasesorder');
	}

	function save_module()
	{
		global $adb;
		/*$sql = "insert into tbt_project_log(crmid,assignto,purchasesordertatus,adduser,adddate)
			values('".$this->id."','".$this->column_fields['assigned_user_id']."','".$this->column_fields["projectorder_status"]."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";
		$adb->pquery($sql,"");*/
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'PurchasesorderAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryPurchasesorder($this, 'Purchasesorder');
		}

		$this->insertIntoCommentTable("aicrm_purchasesordercomments",'purchasesorderid');
	}	
	
	function insertIntoCommentTable($table_name, $module)
	{
		global $log;
		$log->info("in insertIntoCommentTable  ".$table_name."    module is  ".$module);
		global $adb;
		global $current_user;
	
		$current_time = $adb->formatDate(date('YmdHis'), true);
		if($this->column_fields['assigned_user_id'] != ''){
			$ownertype = 'user';
		}	
		else
		{
			$ownertype = 'customer';
		}
				
		if($this->column_fields['comments'] != ''){			
			$comment = $this->column_fields['comments'];
		}
		else
		{
			$comment = $_REQUEST['comments'];
		}	
		
		if($comment!=""){
			$sql = "insert into ".$table_name." values(?,?,?,?,?,?)";
			$params = array('', $this->id, from_html($comment), $current_user->id, $ownertype, $current_time);
			$adb->pquery($sql, $params);
		}
	}

	function getCommentInformation($crmid)
	{
		global $log;
		$log->debug("Entering getCommentInformation(".$crmid.") method ...");
		global $adb;
		global $mod_strings, $default_charset;
		$sql = "select * from aicrm_purchasesordercomments where purchasesorderid=? order by createdtime desc";
		$result = $adb->pquery($sql, array($crmid));
		$noofrows = $adb->num_rows($result);
	
		//In ajax save we should not add this div
		if($_REQUEST['action'] != 'ServiceRequestAjax')
		{
			$list .= '<div id="comments_div" style="overflow: auto;height:200px;width:100%;display:block;">';
			$enddiv = '</div>';
		}
		for($i=0;$i<$noofrows;$i++)
		{
			if($adb->query_result($result,$i,'comments') != '')
			{
				//this div is to display the comment
				$comment = $adb->query_result($result,$i,'comments');
				// Asha: Fix for ticket #4478 . Need to escape html tags during ajax save.
				if($_REQUEST['action'] == 'ServiceRequestAjax') {
					$comment = htmlentities($comment, ENT_PURCHASESORDER, $default_charset);
				}
				$list .= '<div valign="top" style="width:99%;padding-top:10px;" class="dataField">';
				$list .= make_clickable(nl2br($comment));
	
				$list .= '</div>';
	
				//this div is to display the author and time
				$list .= '<div valign="top" style="width:99%;border-bottom:1px dotted #CCCCCC;padding-bottom:5px;" class="dataLabel"><font color=darkred>';
				$list .= $mod_strings['LBL_AUTHOR'].' : ';
	
				if($adb->query_result($result,$i,'ownertype') == 'user')
					$list .= getUserName($adb->query_result($result,$i,'ownerid'));
					elseif($adb->query_result($result,$i,'ownertype') == 'customer') {
						$contactid = $adb->query_result($result,$i,'ownerid');
						$list .= getContactName($contactid);
					}
					$list .= ' on '.date('d-m-Y H:i:s',strtotime($adb->query_result($result,$i,'createdtime'))).' &nbsp;';
	
					$list .= '</font></div>';
			}
		}
		
		$list .= $enddiv;
		
		$log->debug("Exiting getCommentInformation method ...");
		return $list;
	}
	/**	Function used to get the sort order for Quote listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['PURCHASESORDER_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['PURCHASESORDER_SORT_ORDER'] != '')?($_SESSION['PURCHASESORDER_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for Purchasesorder listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['PURCHASESORDER_ORDER_BY'] if this session value is empty then default order by will be returned. 
	 */
	function getOrderBy()
	{
		global $log;
                $log->debug("Entering getOrderBy() method ...");
                
		$use_default_order_by = '';		
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}
		
		if (isset($_REQUEST['order_by'])) {
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		}else{
			$order_by = (($_SESSION['PURCHASESORDER_ORDER_BY'] != '')?($_SESSION['PURCHASESORDER_ORDER_BY']):($use_default_order_by));
		}
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/**	function used to get the list of activities which are related to the Purchasesorder
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
		
		$query = "SELECT aicrm_activity.*, aicrm_activitycf.*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.description,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_activity 
			INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
			INNER JOIN aicrm_purchasesorder ON aicrm_purchasesorder.purchasesorderid = aicrm_activity.event_id
			INNER JOIN aicrm_purchasesordercf ON aicrm_purchasesordercf.purchasesorderid = aicrm_purchasesorder.purchasesorderid		
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_activity.event_id = ".$id;
		//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		// echo $module." && ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_purchasesorder","purchasesorderid");
		$query .=" left join aicrm_crmentity as aicrm_crmentitypurchasesorder on aicrm_crmentitypurchasesorder.crmid=aicrm_purchasesorder.purchasesorderid and aicrm_crmentitypurchasesorder.deleted=0 
				left join aicrm_purchasesordercf on aicrm_purchasesordercf.purchasesorderid = aicrm_crmentitypurchasesorder.crmid 
				left join aicrm_groups as aicrm_groupspurchasesorder on aicrm_groupspurchasesorder.groupid = aicrm_crmentitypurchasesorder.smownerid
				left join aicrm_users as aicrm_userspurchasesorder on aicrm_userspurchasesorder.id = aicrm_crmentitypurchasesorder.smownerid
				LEFT JOIN aicrm_users as aicrm_usersModifiedPurchasesorder on aicrm_crmentity.modifiedby = aicrm_usersModifiedPurchasesorder.id
            	LEFT JOIN aicrm_users as aicrm_usersCreatorPurchasesorder on aicrm_crmentity.smcreatorid = aicrm_usersCreatorPurchasesorder.id
				LEFT JOIN aicrm_inventoryproductrel as projectProductrel on aicrm_purchasesorder.purchasesorderid = projectProductrel.id and projectProductrel.productid>0 
				left join aicrm_account as aicrm_accountPurchasesorder on aicrm_accountPurchasesorder.accountid = aicrm_purchasesorder.accountid
				left join aicrm_contactdetails as aicrm_contactdetailsPurchasesorder on aicrm_contactdetailsPurchasesorder.contactid= aicrm_purchasesorder.contactid
			";
		if($module != 'Products' && $secmodule == 'Purchasesorder'){
			$query .= " LEFT JOIN aicrm_products as projectProduct on projectProductrel.productid=projectProduct.productid ";
            $query .= " LEFT JOIN aicrm_productcf as projectProductcf on projectProductcf.productid=projectProduct.productid ";
		}
		//echo $query; exit;
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		//echo 555; exit;
		$rel_tables = array (
            "Calendar" =>array("aicrm_activity"=>array("event_id","activityid"),"aicrm_purchasesorder"=>"purchasesorderid"),
            "Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_purchasesorder"=>"purchasesorderid"),
			/*"Accounts" => array("aicrm_purchasesorder"=>array("purchasesorderid","accountid"),"aicrm_purchasesorder"=>"purchasesorderid"),
			"Contacts" => array("aicrm_purchasesorder"=>array("purchasesorderid","contactid"),"aicrm_purchasesorder"=>"purchasesorderid"),*/
		);
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Accounts' ) {
			$this->trash('Purchasesorder',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_purchasesorder SET potentialid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_purchasesorder SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
