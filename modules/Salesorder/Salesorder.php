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

// Account is used to store aicrm_account information.
class Salesorder extends CRMEntity {
	var $log;
	var $db;
		
	var $table_name = "aicrm_salesorder";
	var $table_index= 'salesorderid';
	var $table_comment = "aicrm_salesordercomments";
	var $tab_name = Array('aicrm_crmentity','aicrm_salesorder','aicrm_salesordercf','aicrm_salesordercomments');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_salesorder'=>'salesorderid','aicrm_salesordercf'=>'salesorderid','aicrm_salesordercomments'=>'salesorderid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_salesordercf', 'salesorderid');
	var $entity_table = "aicrm_crmentity";
	
	var $billadr_table = "aicrm_salesorderbillads";

	var $object_name = "Salesorder";

	var $new_schema = true;

	var $column_fields = Array();

	var $sortby_fields = Array('subject','crmid','smownerid','accountname','lastname','account_id','contact_id','hdnGrandTotal','hdnGrandTotal','campaignid','projectid');

	// This is used to retrieve related aicrm_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
		'หมายเลขใบขาย'=>Array('aicrm_salesorder'=>'salesorder_no'),
		'ชื่อใบขาย'=>Array('aicrm_salesorder'=>'salesorder_name'),
	);
	
	var $list_fields_name = Array(
		'หมายเลขใบขาย' =>'salesorder_no',
		'ชื่อใบขาย' =>'salesorder_name',
	);
	var $list_link_field= 'salesorder_no';

	var $search_fields = Array(
		'หมายเลขใบขาย'=>Array('aicrm_salesorder'=>'salesorder_no'),
		'ชื่อใบขาย'=>Array('aicrm_salesorder'=>'salesorder_name'),
	);
	
	var $search_fields_name = Array(
		'หมายเลขใบขาย'=>'salesorder_no',
	    'ชื่อใบขาย'=>'salesorder_name',
	);

	// This is the list of aicrm_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';
	
	var $mandatory_fields = Array('subject','createdtime' ,'modifiedtime');
	
	/**	Constructor which will set the column_fields in this object
	 */
	function Salesorder() {
		$this->log =LoggerManager::getLogger('salesorder');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Salesorder');
	}

	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'SalesorderAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
			saveInventoryProductDetails($this, 'Salesorder');
		}

		$this->insertIntoCommentTable("aicrm_salesordercomments",'salesorderid');
		//echo "<pre>"; print_r($this->column_fields); echo"</pre>"; exit;
        // Update the currency id and the conversion rate for the salesorder
        $update_query = "update aicrm_salesorder set currency_id=?,conversion_rate=?,pricetype=? where salesorderid=?";
        $update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'],$this->column_fields['pricetype'], $this->id);
        $adb->pquery($update_query, $update_params);

        if ($_REQUEST['mode'] == 'edit' && $_REQUEST['action'] == 'Save' ) {
        	//if ($_REQUEST['mode'] == 'edit' && $_REQUEST['action'] == 'Save' && $_REQUEST['quotation_status'] != 'เปิดใบเสนอราคา') {
            
            $level4 = $this->column_fields['approve_level4'][0];
                        
            if ($level4 != '') {
                $userid4 = "SELECT id from aicrm_users WHERE concat(first_name,' ',last_name) ='" . $level4 . "' ";

                $query = mysql_query($userid4);
                $result = mysql_fetch_array($query);
                $id = $result['id'];

                $sql = "SELECT * FROM tbt_salesorder_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=4 ";
                $query = mysql_query($sql);
                $count = mysql_num_rows($query);

                if ($count == 0) {
                    $update4 = "INSERT INTO tbt_salesorder_approve (crmid,userid,username,level,appstatus,upuser,updatedate) VALUES('" . $_REQUEST['record'] . "', " . $id . ", '" . $level4 . "', '4', 0, 0, '" . date('Y-m-d H:i:s') . "')";
                    mysql_query($update4);
                } else {
                    $update4 = "UPDATE tbt_salesorder_approve SET username='" . $level4 . "' , userid=$id WHERE crmid='" . $_REQUEST['record'] . "' AND level=4 ";
                    mysql_query($update4);
                }
            } else {
                $update4 = "DELETE FROM tbt_salesorder_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=4 ";
                mysql_query($update4);
            }
        }
		// update quotation before revise		
		/*if (isset($_REQUEST["revise_from"]) && $_REQUEST["revise_from"]!=""){
			$sql = "update aicrm_salesorder set  quotation_status='เปลี่ยนแปลงใบเสนอราคา' where aicrm_salesorder.salesorderid = '".$_REQUEST["revise_from"]."'  ";
			$query_data= $adb->pquery($sql, "");
		}*/
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
		$sql = "select * from aicrm_salesordercomments where salesorderid=? order by createdtime desc";
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

	/**	Function used to get the sort order for Salesorder listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['SALESORDER_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])){ 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		}else{
			$sorder = (($_SESSION['SALESORDER_SORT_ORDER'] != '')?($_SESSION['SALESORDER_SORT_ORDER']):($this->default_sort_order));
		}
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for Salesorder listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['SALESORDER_ORDER_BY'] if this session value is empty then default order by will be returned. 
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
			$order_by = (($_SESSION['SALESORDER_ORDER_BY'] != '')?($_SESSION['SALESORDER_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	
	/**	Function used to get the Salesorder Stage history of the Salesorder
	 *	@param $id - salesorder id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_salesordertagehistory($id)
	{	
		global $log;
		$log->debug("Entering get_salesordertagehistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select aicrm_salesordertagehistory.*, aicrm_salesorder.salesorder_no from aicrm_salesordertagehistory inner join aicrm_salesorder on aicrm_salesorder.salesorderid = aicrm_salesordertagehistory.salesorderid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_salesorder.salesorderid where aicrm_crmentity.deleted = 0 and aicrm_salesorder.salesorderid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['หมายเลขใบขาย'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['Salesorder Stage'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];
		
		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Total are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$salesordertage_access = (getFieldVisibilityPermission('Salesorder', $current_user->id, 'salesordertage') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Salesorder');

		$salesordertage_array = ($salesordertage_access != 1)? $picklistarray['salesordertage']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($salesordertage_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering
			//$entries[] = $row['salesorderid'];
			$entries[] = $row['salesorder_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['salesordertage'], $salesordertage_array))? $row['salesordertage']: $error_msg;
			$entries[] = getDisplayDate($row['lastmodified']);

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_salesordertagehistory method ...");

		return $return_data;
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
	function generateReportsSecQuery($module,$secmodule){ //echo $module.' : '.$secmodule;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_salesorder","salesorderid");
		$query .= " left join aicrm_crmentity as aicrm_crmentitySalesorder on aicrm_crmentitySalesorder.crmid=aicrm_salesorder.salesorderid and aicrm_crmentitySalesorder.deleted=0
			left join aicrm_salesordercf on aicrm_salesorder.salesorderid = aicrm_salesordercf.salesorderid
			left join aicrm_groups as aicrm_groupsSalesorder on aicrm_groupsSalesorder.groupid = aicrm_crmentitySalesorder.smownerid
			left join aicrm_users as aicrm_usersSalesorder on aicrm_usersSalesorder.id = aicrm_crmentitySalesorder.smownerid
			left join aicrm_inventoryproductrel on aicrm_salesorder.salesorderid=aicrm_inventoryproductrel.id and aicrm_inventoryproductrel.productid>0
			left join aicrm_users as aicrm_usersModifiedSalesorder on aicrm_crmentitySalesorder.modifiedby=aicrm_usersModifiedSalesorder.id
            left join aicrm_users as aicrm_usersCreatorSalesorder on aicrm_crmentitySalesorder.smcreatorid=aicrm_usersCreatorSalesorder.id	
			left join aicrm_contactdetails as aicrm_contactdetailsSalesorder on aicrm_contactdetailsSalesorder.contactid = aicrm_salesorder.contactid
			left join aicrm_account as aicrm_accountSalesorder on aicrm_accountSalesorder.accountid = aicrm_salesorder.accountid
			left join aicrm_project as aicrm_projectSalesorder on aicrm_projectSalesorder.projectid = aicrm_salesorder.projectid
			left join aicrm_campaign as aicrm_campaignSalesorder on aicrm_campaignSalesorder.campaignid = aicrm_salesorder.campaignid";

		if($module != 'Products' && $secmodule == 'Salesorder'){
            $query .= " LEFT JOIN aicrm_products on aicrm_inventoryproductrel.productid=aicrm_products.productid ";
            $query .= " LEFT JOIN aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid ";
        }

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
            "Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_salesorder"=>"salesorderid"),
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
        $sql = getPermittedFieldsQuery("Salesorder", "detail_view");
        $fields_list = getFieldsListFromQuery($sql);
        $query = "SELECT aicrm_salesorder.pricetype as 'Price Type',$fields_list,
        		aicrm_inventoryproductrel.sequence_no as 'No',
	       		aicrm_inventoryproductrel.productid as 'productid' ,
	       		aicrm_products.productname as 'ชื่อสินค้า',
	       		aicrm_inventoryproductrel.comment as 'Comment',
	       		aicrm_inventoryproductrel.uom as 'UOM',
	       		aicrm_inventoryproductrel.quantity as 'Qty',
	       		aicrm_inventoryproductrel.listprice_exc as 'Price List (Exclude Vat)',
	       		aicrm_inventoryproductrel.listprice_inc as 'Price List (Include Vat)',
	       		aicrm_inventoryproductrel.listprice as 'Selling Price',
	       		ROUND((ROUND(aicrm_inventoryproductrel.quantity,2) * ROUND(aicrm_inventoryproductrel.listprice,2)),2) as 'Price Total',
	       		aicrm_salesorder.subtotal as 'TOTAL PRICE',
	       		aicrm_salesorder.discountTotal_final as 'Discount',
	       		aicrm_salesorder.
	       		 as 'Total After Discount',
	       		aicrm_salesorder.tax_final as 'Vat',
	       		ROUND(aicrm_salesorder.total,2) as 'Grand Total',
        		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       		FROM aicrm_crmentity
				INNER JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_crmentity.crmid
				INNER JOIN aicrm_salesordercf ON aicrm_salesordercf.salesorderid = aicrm_salesorder.salesorderid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_account on aicrm_account.accountid = aicrm_salesorder.accountid
                LEFT JOIN aicrm_deal on aicrm_deal.dealid = aicrm_salesorder.dealid
                LEFT JOIN aicrm_quotes on aicrm_quotes.quoteid = aicrm_salesorder.quoteid
                LEFT JOIN aicrm_campaign on aicrm_campaign.campaignid = aicrm_salesorder.campaignid
                LEFT JOIN aicrm_promotion on aicrm_promotion.promotionid = aicrm_salesorder.promotionid
				LEFT JOIN aicrm_inventoryproductrel on aicrm_inventoryproductrel.id = aicrm_salesorder.salesorderid
				LEFT JOIN aicrm_products on aicrm_products.productid = aicrm_inventoryproductrel.productid
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
               	LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id";
        $query .= setFromQuery("Salesorder");
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
            $query = $query." ".getListViewSecurityParameter("Salesorder");
        }
        $log->debug("Exiting create_export_query method ...");
        return $query;
    }
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Accounts' ) {
			$this->trash('Salesorder',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_salesorder SET potentialid=0 WHERE salesorderid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_salesorder SET contactid=0 WHERE salesorderid=?';
			$this->db->pquery($relation_query, array($id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
