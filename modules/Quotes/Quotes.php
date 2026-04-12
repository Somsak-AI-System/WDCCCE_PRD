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
class Quotes extends CRMEntity {

	var $log;
	var $db;

	var $table_name = "aicrm_quotes";
	var $table_index= 'quoteid';
	var $table_comment = "aicrm_quotescomments";

	var $tab_name = Array('aicrm_crmentity','aicrm_quotes','aicrm_quotesbillads','aicrm_quotesshipads','aicrm_quotescf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_quotes'=>'quoteid','aicrm_quotesbillads'=>'quotebilladdressid','aicrm_quotesshipads'=>'quoteshipaddressid','aicrm_quotescf'=>'quoteid','aicrm_quotescomments'=>'quoteid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_quotescf', 'quoteid');
	var $entity_table = "aicrm_crmentity";
	
	var $billadr_table = "aicrm_quotesbillads";

	var $object_name = "Quote";

	var $new_schema = true;

	var $column_fields = Array();

	var $sortby_fields = Array('subject','crmid','smownerid','accountname','lastname');		

	// This is used to retrieve related aicrm_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
		'Quote No'=>Array('aicrm_quotes'=>'quote_no'),
		'ชื่อโครงการ'=>Array('aicrm_quotes'=>'projectsid'),
		'ผู้รับผิดชอบใบเสนอราคา'=>Array('aicrm_quotes'=> 'user_name'),
		'ยอด Grand Total'=>Array('aicrm_quotes'=> 'total'),
		// 'Grand Total'=>Array('aicrm_quotes'=> 'hdnGrandTotal'),
	);
	
	var $list_fields_name = Array(
		'Quote No' =>'quote_no',
		'ชื่อโครงการ' =>'projectsid',
		'ผู้รับผิดชอบใบเสนอราคา' => 'user_name',
		'ยอด Grand Total' => 'total',
		// 'Grand Total' => 'hdnGrandTotal',
	);
	var $list_link_field= 'quote_no';

	var $search_fields = Array(
		'Quotation No'=>Array('aicrm_quotes'=>'quote_no'),
		'ชื่อใบเสนอราคา'=>Array('aicrm_quotes'=>'quote_name'),
		'Account Name'=>Array('aicrm_quotes'=>'accountid'),
	);
	
	var $search_fields_name = Array(
		'Quotation No'=>'quote_no',
		'ชื่อใบเสนอราคา'=>'quote_name',
		'Account Name'=>'account_id',
	);

	// This is the list of aicrm_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';
	
	var $mandatory_fields = Array('subject','createdtime' ,'modifiedtime');
	
	/**	Constructor which will set the column_fields in this object
	 */
	function Quotes() {
		$this->log =LoggerManager::getLogger('quote');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Quotes');
	}

	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'QuotesAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'Quotes');
		}
		//Save Comments
		$this->insertIntoCommentTable("aicrm_quotescomments",'quoteid');

        // Update the currency id and the conversion rate for the quotes
		$update_query = "update aicrm_quotes set currency_id=?,conversion_rate=?,pricetype=? where quoteid=?";
		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'],$this->column_fields['pricetype'], $this->id);
		$adb->pquery($update_query, $update_params);

		// if ($_REQUEST['mode'] == 'edit' && $_REQUEST['action'] == 'Save' ) {
        // 	//if ($_REQUEST['mode'] == 'edit' && $_REQUEST['action'] == 'Save' && $_REQUEST['quotation_status'] != 'เปิดใบเสนอราคา') {

		// 	$level1 = $this->column_fields['approve_level1'][0];
		// 	$level2 = $this->column_fields['approve_level2'][0];
		// 	$level3 = $this->column_fields['approve_level3'][0];
		// 	$level4 = $this->column_fields['approve_level4'][0];

		// 	if ($level1 != '') {
		// 		$userid1 = "SELECT id from aicrm_users WHERE concat(first_name,' ',last_name) ='" . $level1 . "' ";

		// 		$query = mysql_query($userid1);
		// 		$result = mysql_fetch_array($query);
		// 		$id = $result['id'];

		// 		$sql = "SELECT * FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=1 ";
		// 		$query = mysql_query($sql);
		// 		$count = mysql_num_rows($query);

		// 		if ($count == 0) {
		// 			$update1 = "INSERT INTO tbt_quotes_approve (crmid,userid,username,level,appstatus,upuser,updatedate) VALUES('" . $_REQUEST['record'] . "', " . $id . ", '" . $level1 . "', '1', 0, 0, '" . date('Y-m-d H:i:s') . "')";
		// 			mysql_query($update1);
		// 		} else {
		// 			$update1 = "UPDATE tbt_quotes_approve SET username='" . $level1 . "' , userid='" . $id . "' WHERE crmid='" . $_REQUEST['record'] . "' AND level=1 ";
		// 			mysql_query($update1);
		// 		}
		// 	} else {
		// 		$update1 = "DELETE FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=1 ";
		// 		mysql_query($update1);
		// 	}

		// 	if ($level2 != '') {
		// 		$userid2 = "SELECT id from aicrm_users WHERE concat(first_name,' ',last_name) ='" . $level2 . "' ";

		// 		$query = mysql_query($userid2);
		// 		$result = mysql_fetch_array($query);
		// 		$id = $result['id'];

		// 		$sql = "SELECT * FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=2 ";
		// 		$query = mysql_query($sql);
		// 		$count = mysql_num_rows($query);

		// 		if ($count == 0) {
		// 			$update2 = "INSERT INTO tbt_quotes_approve (crmid,userid,username,level,appstatus,upuser,updatedate) VALUES('" . $_REQUEST['record'] . "', " . $id . ", '" . $level2 . "', '2', 0, 0, '" . date('Y-m-d H:i:s') . "')";
		// 			mysql_query($update2);
		// 		} else {
		// 			$update2 = "UPDATE tbt_quotes_approve SET username='" . $level2 . "' , userid=$id WHERE crmid='" . $_REQUEST['record'] . "' AND level=2 ";
		// 			mysql_query($update2);
		// 		}
		// 	} else {
		// 		$update2 = "DELETE FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=2 ";
		// 		mysql_query($update2);
		// 	}

		// 	if ($level3 != '') {
		// 		$userid3 = "SELECT id from aicrm_users WHERE concat(first_name,' ',last_name) ='" . $level3 . "' ";

		// 		$query = mysql_query($userid3);
		// 		$result = mysql_fetch_array($query);
		// 		$id = $result['id'];

		// 		$sql = "SELECT * FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=3 ";
		// 		$query = mysql_query($sql);
		// 		$count = mysql_num_rows($query);

		// 		if ($count == 0) {
		// 			$update3 = "INSERT INTO tbt_quotes_approve (crmid,userid,username,level,appstatus,upuser,updatedate) VALUES('" . $_REQUEST['record'] . "', " . $id . ", '" . $level3 . "', '3', 0, 0, '" . date('Y-m-d H:i:s') . "')";
		// 			mysql_query($update3);
		// 		} else {
		// 			$update3 = "UPDATE tbt_quotes_approve SET username='" . $level3 . "' , userid=$id WHERE crmid='" . $_REQUEST['record'] . "' AND level=3 ";
		// 			mysql_query($update3);
		// 		}
		// 	} else {
		// 		$update3 = "DELETE FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=3 ";
		// 		mysql_query($update3);
		// 	}

		// 	if ($level4 != '') {
		// 		$userid4 = "SELECT id from aicrm_users WHERE concat(first_name,' ',last_name) ='" . $level4 . "' ";

		// 		$query = mysql_query($userid4);
		// 		$result = mysql_fetch_array($query);
		// 		$id = $result['id'];

		// 		$sql = "SELECT * FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=4 ";
		// 		$query = mysql_query($sql);
		// 		$count = mysql_num_rows($query);

		// 		if ($count == 0) {
		// 			$update4 = "INSERT INTO tbt_quotes_approve (crmid,userid,username,level,appstatus,upuser,updatedate) VALUES('" . $_REQUEST['record'] . "', " . $id . ", '" . $level4 . "', '4', 0, 0, '" . date('Y-m-d H:i:s') . "')";
		// 			mysql_query($update4);
		// 		} else {
		// 			$update4 = "UPDATE tbt_quotes_approve SET username='" . $level4 . "' , userid=$id WHERE crmid='" . $_REQUEST['record'] . "' AND level=4 ";
		// 			mysql_query($update4);
		// 		}
		// 	} else {
		// 		$update4 = "DELETE FROM tbt_quotes_approve WHERE crmid='" . $_REQUEST['record'] . "' AND level=4 ";
		// 		mysql_query($update4);
		// 	}
		// }
		// update quotation before revise		
		if (isset($_REQUEST["revise_from"]) && $_REQUEST["revise_from"]!=""){
			$sql = "update aicrm_quotes set  quotation_status='เปลี่ยนแปลงใบเสนอราคา' where aicrm_quotes.quoteid = '".$_REQUEST["revise_from"]."'  ";
			$query_data= $adb->pquery($sql, "");
		}
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
		$sql = "select * from aicrm_quotescomments where quoteid=? order by createdtime desc";
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

	function get_priceList($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_priceList(".$id.") method ...");
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
           
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Competitor Analysis'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,aicrm_account.accountname,
			aicrm_crmentity.*,
			aicrm_pricelists.*,
			aicrm_pricelistscf.*,
			aicrm_account.*
			FROM aicrm_pricelists
			LEFT JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_pricelists.account_id
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_pricelists.quoteid = '".$id."' ";
			
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_priceList method ...");
        return $return_value;

    }

	/**	Function used to get the sort order for Quote listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['QUOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])){ 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		}else{
			$sorder = (($_SESSION['QUOTES_SORT_ORDER'] != '')?($_SESSION['QUOTES_SORT_ORDER']):($this->default_sort_order));
		}
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
			$order_by = (($_SESSION['QUOTES_ORDER_BY'] != '')?($_SESSION['QUOTES_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/**	Function used to get the Quote Stage history of the Quotes
	 *	@param $id - quote id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_quotestagehistory($id)
	{	
		global $log;
		$log->debug("Entering get_quotestagehistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select aicrm_quotestagehistory.*, aicrm_quotes.quote_no from aicrm_quotestagehistory inner join aicrm_quotes on aicrm_quotes.quoteid = aicrm_quotestagehistory.quoteid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_quotes.quoteid where aicrm_crmentity.deleted = 0 and aicrm_quotes.quoteid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Quote No'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['Quote Stage'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];
		
		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Total are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$quotestage_access = (getFieldVisibilityPermission('Quotes', $current_user->id, 'quotestage') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Quotes');

		$quotestage_array = ($quotestage_access != 1)? $picklistarray['quotestage']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($quotestage_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering
			//$entries[] = $row['quoteid'];
			$entries[] = $row['quote_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['quotestage'], $quotestage_array))? $row['quotestage']: $error_msg;
			$entries[] = getDisplayDate($row['lastmodified']);

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

		$log->debug("Exiting get_quotestagehistory method ...");

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
	function generateReportsSecQuery($module,$secmodule){ 

		// echo $module.' && '.$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_quotes","quoteid");
		$query .= " left join aicrm_crmentity as aicrm_crmentityQuotes on aicrm_crmentityQuotes.crmid=aicrm_quotes.quoteid and aicrm_crmentityQuotes.deleted=0
		left join aicrm_quotescf on aicrm_quotes.quoteid = aicrm_quotescf.quoteid

		left join aicrm_quotesbillads on aicrm_quotes.quoteid=aicrm_quotesbillads.quotebilladdressid
		left join aicrm_quotesshipads on aicrm_quotes.quoteid=aicrm_quotesshipads.quoteshipaddressid

		left join aicrm_groups as aicrm_groupsQuotes on aicrm_groupsQuotes.groupid = aicrm_crmentityQuotes.smownerid
		left join aicrm_users as aicrm_usersQuotes on aicrm_usersQuotes.id = aicrm_crmentityQuotes.smownerid
		-- left join aicrm_users as aicrm_usersRel1 on aicrm_usersRel1.id = aicrm_quotes.inventorymanager
		left join aicrm_inventoryproductrel on aicrm_quotes.quoteid=aicrm_inventoryproductrel.id and aicrm_inventoryproductrel.productid>0


		left join aicrm_users as aicrm_usersCreatorQuotes on aicrm_usersCreatorQuotes.id = aicrm_crmentityQuotes.smcreatorid
		left join aicrm_users as aicrm_usersModifiedQuotes on aicrm_usersModifiedQuotes.id = aicrm_crmentityQuotes.modifiedby

		left join aicrm_contactdetails as aicrm_contactdetailsQuotes on aicrm_contactdetailsQuotes.contactid = aicrm_quotes.contactid
		";

		if($module == 'Accounts' && $secmodule == 'Quotes'){
			$query .= " LEFT JOIN aicrm_account AS aicrm_accountQuotes ON aicrm_accountQuotes.accountid = aicrm_quotestmpQuotes.accountid ";
			$query .= " LEFT JOIN aicrm_deal AS aicrm_dealQuotes ON aicrm_dealQuotes.dealid = aicrm_quotestmpQuotes.dealid ";
			$query .= " LEFT JOIN aicrm_campaign AS aicrm_campaignQuotes ON aicrm_campaignQuotes.campaignid = aicrm_quotestmpQuotes.campaignid ";

		}

		if($module != 'Products' && $secmodule == 'Quotes'){
			$query .= " LEFT JOIN aicrm_products on aicrm_inventoryproductrel.productid=aicrm_products.productid ";
			$query .= " LEFT JOIN aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid ";
		}

		if($module == 'Deal' && $secmodule == 'Quotes'){
			$query .= " LEFT JOIN aicrm_account AS aicrm_accountQuotes ON aicrm_accountQuotes.accountid = aicrm_quotestmpQuotes.accountid
			LEFT JOIN aicrm_deal AS aicrm_dealQuotes ON aicrm_dealQuotes.dealid = aicrm_quotestmpQuotes.dealid
			LEFT JOIN aicrm_campaign AS aicrm_campaignQuotes On aicrm_campaignQuotes.campaignid = aicrm_quotestmpQuotes.campaignid";
		}

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		// echo $secmodule; exit;
		$rel_tables = array (
			"Calendar" =>array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_quotes"=>"quoteid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_quotes"=>"quoteid"),
			"Accounts" => array("aicrm_quotes"=>array("accountid","quoteid"),"aicrm_quotes"=>"quoteid"),
			"Contacts" => array("aicrm_quotes"=>array("contactid","quoteid"),"aicrm_quotes"=>"quoteid"),
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
		$sql = getPermittedFieldsQuery("Quotes", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT aicrm_quotes.pricetype as 'Price Type',$fields_list,
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
		aicrm_quotes.subtotal as 'TOTAL PRICE',
		aicrm_quotes.discountTotal_final as 'Discount',
		aicrm_quotes.total_after_discount as 'Total After Discount',
		aicrm_quotes.tax_final as 'Vat',
		ROUND(aicrm_quotes.total,2) as 'Grand Total',
		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		FROM aicrm_crmentity
		INNER JOIN aicrm_quotes ON aicrm_quotes.quoteid = aicrm_crmentity.crmid
		INNER JOIN aicrm_quotescf ON aicrm_quotescf.quoteid = aicrm_quotes.quoteid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
		LEFT JOIN aicrm_account on aicrm_account.accountid = aicrm_quotes.parentid
		LEFT JOIN aicrm_leaddetails on aicrm_leaddetails.leadid = aicrm_quotes.parentid
		LEFT JOIN aicrm_jobs on aicrm_jobs.jobid = aicrm_quotes.jobid
		LEFT JOIN aicrm_contactdetails on aicrm_contactdetails.contactid = aicrm_quotes.contactid
		LEFT JOIN aicrm_inventoryproductrel on aicrm_inventoryproductrel.id = aicrm_quotes.quoteid
		LEFT JOIN aicrm_products on aicrm_products.productid = aicrm_inventoryproductrel.productid
		LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
		LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id";
		$query .= setFromQuery("Quotes");
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
			$query = $query." ".getListViewSecurityParameter("Quotes");
		}
		$log->debug("Exiting create_export_query method ...");
		return $query;
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Accounts' ) {
			$this->trash('Quotes',$id);
		} elseif($return_module == 'Potentials') {
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
