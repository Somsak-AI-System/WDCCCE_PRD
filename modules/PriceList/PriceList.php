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
 * All Rights PriceListd.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights PriceListd.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('include/utils/utils.php');
require_once('include/RelatedListView.php');
require_once('user_privileges/default_module_view.php');

// Account is used to store aicrm_account information.
class PriceList extends CRMEntity {
	var $log;
	var $db;
		
	var $table_name = "aicrm_pricelists";
	var $table_index= 'pricelistid';
	var $tab_name = Array('aicrm_crmentity','aicrm_pricelists','aicrm_pricelistscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_pricelists'=>'pricelistid','aicrm_pricelistscf'=>'pricelistid','aicrm_pricelistscomments'=>'pricelistid','aicrm_inventoryproductrel'=>'id');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_pricelistscf', 'pricelistid');
	var $entity_table = "aicrm_crmentity";
	var $table_comment = "aicrm_pricelistscomments";

	//var $billadr_table = "aicrm_pricelistbillads";

	var $object_name = "PriceList";

	var $new_schema = true;

	var $column_fields = Array();

	var $sortby_fields = Array('pricelist_name','crmid','smownerid','accountname','lastname');		

	// This is used to retrieve related aicrm_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
		'PriceList No'=>Array('pricelist'=>'pricelist_no'),
		'PriceList Name'=>Array('pricelist'=>'pricelist_name'),
		//'List Price' => Array('pricelist'=> 'listprice'),
		//'Assigned To'=>Array('crmentity'=>'smownerid')
	);
	
	var $list_fields_name = Array(
		'PriceList No'=>'pricelist_no',
		'PriceList Name'=>'pricelist_name',
		//'List Price'=>'listprice',
		//'Assigned To'=>'assigned_user_id'
	);
	var $list_link_field= 'pricelist_no';

	var $search_fields = Array(
		'PriceList No'=>Array('pricelist'=>'pricelist_no'),
		'PriceList Name'=>Array('pricelist'=>'pricelist_name'),
		'List Price' => Array('aicrm_inventoryproductrel'=> 'listprice'),
		//'Account Name'=>Array('pricelist'=>'accountid'),
	);
	
	var $search_fields_name = Array(
		'PriceList No'=>'pricelist_no',
		'PriceList Name'=>'pricelist_name',
		//'List Price'=>'listprice',
		//'Account Name'=>'account_id',
	);

	// This is the list of aicrm_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';
	//var $groupTable = Array('aicrm_pricelistgrouprelation','pricelistid');
	
	var $mandatory_fields = Array('pricelist_name','createdtime' ,'modifiedtime');
	
	/**	Constructor which will set the column_fields in this object
	 */
	function PriceList() {
		$this->log =LoggerManager::getLogger('pricelist');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('PriceList');
	}

	function save_module()
	{	
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
		if($_REQUEST['action'] != 'PriceListAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			saveInventoryProductPricelist($this, 'PriceList');
		}
		//echo 555; exit;
		$this->insertIntoCommentTable("aicrm_pricelistscomments",'pricelistid');
		// Update the currency id and the conversion rate for the pricelist
		/*$update_query = "update aicrm_pricelists set currency_id=?, conversion_rate=? where pricelistid=?";
		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id); 
		$adb->pquery($update_query, $update_params);*/
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
		$sql = "select * from aicrm_pricelistscomments where pricelistid=? order by createdtime desc";
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
					$comment = htmlentities($comment, ENT_QUOTES, $default_charset);
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
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['QUOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
       	$log->debug("Entering getSortOrder() method ...");	
		
		if(isset($_REQUEST['sorder'])) 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['PRICELIST_SORT_ORDER'] != '')?($_SESSION['PRICELIST_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for PriceList listview
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
			$order_by = (($_SESSION['PRICELIST_ORDER_BY'] != '')?($_SESSION['PRICELIST_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/** Function to export the account records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Accounts Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");
		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("PriceList", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,
				aicrm_inventoryproductrel.sequence_no as 'No',
	       		aicrm_inventoryproductrel.productid as 'productid' ,
	       		aicrm_products.productname as 'ชื่อสินค้า',
	       		aicrm_inventoryproductrel.comment as 'Comment',
	       		case when aicrm_products.productstatus = 1 then 'Active' else 'InActive' end as 'สถานะของสินค้า',
	       		aicrm_products.productcategory as 'ประเภทสินค้า',
	       		aicrm_products.unit as 'หน่วยการนับ',
	       		aicrm_inventoryproductrel.listprice as 'ราคาขาย',
	       		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       		FROM aicrm_crmentity
				INNER JOIN aicrm_pricelists ON aicrm_pricelists.pricelistid = aicrm_crmentity.crmid
				INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
				LEFT JOIN aicrm_inventoryproductrel on aicrm_inventoryproductrel.id = aicrm_pricelists.pricelistid
				LEFT JOIN aicrm_products on aicrm_products.productid = aicrm_inventoryproductrel.productid
				LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
               	LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				";
		$query .= setFromQuery("PriceList");

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
			$query = $query." ".getListViewSecurityParameter("PriceList");
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}
	/*function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");
		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("PriceList", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,
	       		aicrm_inventoryproductrel.sequence_no as 'No',
	       		aicrm_inventoryproductrel.productid as 'productid' ,
	       		aicrm_products.productname as 'ชื่อสินค้า',
	       		aicrm_inventoryproductrel.comment as 'Comment',
	       		aicrm_inventoryproductrel.uom as 'UOM',
	       		aicrm_inventoryproductrel.standard_price as 'Price STD.(Exclude Vat)',
	       		aicrm_inventoryproductrel.standard_price_inc as 'Price STD.(Include Vat)',
	       		aicrm_inventoryproductrel.listprice as 'Price List (Exclude Vat)',
	       		aicrm_inventoryproductrel.listprice_inc as 'Price List (Include Vat)',
	       		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       		FROM aicrm_crmentity
				INNER JOIN aicrm_pricelists ON aicrm_pricelists.pricelistid = aicrm_crmentity.crmid
				INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
				LEFT JOIN aicrm_inventoryproductrel on aicrm_inventoryproductrel.id = aicrm_pricelists.pricelistid
				LEFT JOIN aicrm_products on aicrm_products.productid = aicrm_inventoryproductrel.productid
				LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
               	LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				";
		$query .= setFromQuery("PriceList");

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
			$query = $query." ".getListViewSecurityParameter("PriceList");
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}*/
	
	function get_accounts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
        $log->debug("Entering get_accounts(".$id.") method ...");
		$this_module = $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		
		if($this->column_fields['account_type'] == '' || $this->column_fields['account_type'] == '--None--' ){
			$load_list = "alert(\"Please Check Account Type !\"); return false;";
			$add_account = "alert(\"Please Check Account Type !\"); return false;";
			$select_account  = "alert(\"Please Check Account Type !\"); return false;";
			$clear_account =  "alert(\"Please Check Account Type !\"); return false;";
		}else{
			$load_list = "loadCvList_pricelist(\"$related_module\",\"$id\");";
			$add_account = "this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\" ;";
			$select_account = "return window.open(\"index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab\",\"test\", \"width=640\",\"height=602\",\"resizable=1\",\"scrollbars=0\" );";
			$clear_account =  "return window.open(\"clear_list_data.php?crmid=$id&module=$_REQUEST[module]&related_module=$related_module\" ,\"Application\",\"height=200\",\"left=450\",\"width=400\",\"top=100\",\"resizable=0\",\"toolbar=no\",\"scrollbars=no\",\"menubar=no\",\"location=no\" ); ";
		}

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		/* To get Leads CustomView -START */
		require_once('modules/CustomView/CustomView.php');
		$lhtml = "<select id='account_cv_list' class='small'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='".$load_list."' />";
		$button .= '&nbsp;&nbsp;&nbsp;&nbsp';
	
		if($actions) {

			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				
				$button .= "<input title='Select Accounts' class='crmbutton small edit' type='button' onclick= '".$select_account."'; value='Select Accounts'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add Accounts' class='crmbutton small create'" .
					" onclick='".$add_account."' type='submit' name='button'" .
					" value='Add Accounts'>&nbsp;";
			}
		}		
		$button .="<input class='crmbutton small delete' type='button' onclick='$clear_account'; value='Clear Data'  >";			
		
		$query = "SELECT aicrm_account.*,aicrm_accountscf.*, aicrm_crmentity.crmid, aicrm_accountbillads.*,
					CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
					aicrm_crmentity.smownerid 
					FROM aicrm_account      
					LEFT JOIN aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
					left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
					INNER JOIN aicrm_pricelist_accountsrel ON aicrm_pricelist_accountsrel.accountid=aicrm_account.accountid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_pricelist_accountsrel.pricelistid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	
		
		$log->debug("Exiting get_accounts method ...");	
		return $return_value;	
	}	

	// Function to get column name - Overriding function of base class
	function get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype='') {
		if ($columname == 'potentialid' || $columname == 'contactid') {
			if ($fldvalue == '') return null;
		}
		return parent::get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype);
	}

	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_pricelists","pricelistid");
		$query .= " left join aicrm_crmentity as aicrm_crmentityPriceList on aicrm_crmentityPriceList.crmid=aicrm_pricelists.pricelistid and aicrm_crmentityPriceList.deleted=0
			left join aicrm_pricelistscf on aicrm_pricelists.pricelistid = aicrm_pricelistscf.pricelistid
			left join aicrm_pricelistbillads on aicrm_pricelists.pricelistid=aicrm_pricelistbillads.pricelistbilladdressid
			left join aicrm_pricelisthipads on aicrm_pricelists.pricelistid=aicrm_pricelisthipads.pricelisthipaddressid
			left join aicrm_groups as aicrm_groupsPriceList on aicrm_groupsPriceList.groupid = aicrm_crmentityPriceList.smownerid
			left join aicrm_users as aicrm_usersPriceList on aicrm_usersPriceList.id = aicrm_crmentityPriceList.smownerid
			left join aicrm_users as aicrm_usersRel1 on aicrm_usersRel1.id = aicrm_pricelists.inventorymanager
			left join aicrm_potential as aicrm_potentialRelPriceList on aicrm_potentialRelPriceList.potentialid = aicrm_pricelists.potentialid
			left join aicrm_contactdetails as aicrm_contactdetailsPriceList on aicrm_contactdetailsPriceList.contactid = aicrm_pricelists.contactid
			left join aicrm_users as aicrm_usersModifiedPriceList on aicrm_crmentity.smcreatorid=aicrm_usersModifiedPriceList.id
            left join aicrm_users as aicrm_usersCreatorPriceList on aicrm_crmentity.smcreatorid=aicrm_usersModifiedPriceList.id	
			left join aicrm_account as aicrm_accountPriceList on aicrm_accountPriceList.accountid = aicrm_pricelists.accountid ";

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Calendar" =>array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_pricelists"=>"pricelistid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_pricelists"=>"pricelistid"),
			"Contacts" => array("aicrm_pricelist"=>array("pricelistid","contactid")),
			"Accounts" => array("aicrm_pricelist_accountsrel"=>array("accountid","pricelistid"),"aicrm_pricelists"=>"pricelistid"),
		);
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Accounts' ) {
			$this->trash('PriceList',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_pricelist SET potentialid=0 WHERE pricelistid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_pricelist SET contactid=0 WHERE pricelistid=?';
			$this->db->pquery($relation_query, array($id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
