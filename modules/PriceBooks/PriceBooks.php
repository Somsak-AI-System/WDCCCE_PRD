<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

class PriceBooks extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "aicrm_pricebook";
	var $table_index= 'pricebookid';
	var $tab_name = Array('aicrm_crmentity','aicrm_pricebook','aicrm_pricebookcf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_pricebook'=>'pricebookid','aicrm_pricebookcf'=>'pricebookid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_pricebookcf', 'pricebookid');
	var $column_fields = Array();

	var $sortby_fields = Array('bookname');		  

        // This is the list of fields that are in the lists.
	var $list_fields = Array(
        'Price Book Name'=>Array('pricebook'=>'bookname'),
        'Active'=>Array('pricebook'=>'active')
    );
                                
	var $list_fields_name = Array(
	    'Price Book Name'=>'bookname',
	    'Active'=>'active'
	 );
	var $list_link_field= 'bookname';

	var $search_fields = Array(
        'Price Book Name'=>Array('pricebook'=>'bookname')
    );
	var $search_fields_name = Array(
        'Price Book Name'=>'bookname',
    );

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'bookname';
	var $default_sort_order = 'ASC';

	var $mandatory_fields = Array('bookname','currency_id','pricebook_no','createdtime' ,'modifiedtime');
	
	/**	Constructor which will set the column_fields in this object
	 */
	function PriceBooks() {
		$this->log =LoggerManager::getLogger('pricebook');
		$this->log->debug("Entering PriceBooks() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('PriceBooks');
		$this->log->debug("Exiting PriceBook method ...");
	}

	function save_module($module)
	{
		// Update the list prices in the price book with the unit price, if the Currency has been changed
		$this->updateListPrices();
	}	
	
	/* Function to Update the List prices for all the products of a current price book 
	   with its Unit price, if the Currency for Price book has changed. */
	function updateListPrices() {
		global $log, $adb;
		$log->debug("Entering function updateListPrices...");
		$pricebook_currency = $this->column_fields['currency_id'];
		$prod_res = $adb->pquery("select productid from aicrm_pricebookproductrel where pricebookid=? and usedcurrency != ?", 
							array($this->id,$pricebook_currency));
		$numRows = $adb->num_rows($prod_res);
		$prod_ids = array();
		for($i=0;$i<$numRows;$i++) {
			$prod_ids[] = $adb->query_result($prod_res,$i,'productid');
		}
		if(count($prod_ids) > 0) {
			$prod_price_list = getPricesForProducts($pricebook_currency,$prod_ids);
		
			for($i=0;$i<count($prod_ids);$i++) {
				$product_id = $prod_ids[$i];
				$unit_price = $prod_price_list[$product_id];
				$query = "update aicrm_pricebookproductrel set listprice=?, usedcurrency=? where pricebookid=? and productid=?";
				$params = array($unit_price, $pricebook_currency, $this->id, $product_id);
				$adb->pquery($query, $params);
			}	
		}
		$log->debug("Exiting function updateListPrices...");
	}

	/**	Function used to get the sort order for PriceBook listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['PRICEBOOK_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['PRICEBOOK_SORT_ORDER'] != '')?($_SESSION['PRICEBOOK_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for PriceBook listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['PRICEBOOK_ORDER_BY'] if this session value is empty then default order by will be returned. 
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
			$order_by = (($_SESSION['PRICEBOOK_ORDER_BY'] != '')?($_SESSION['PRICEBOOK_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	

	/**	function used to get the products which are related to the pricebook
	 *	@param int $id - pricebook id
         *      @return array - return an array which will be returned from the function getPriceBookRelatedProducts
        **/
	function get_pricebook_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_pricebook_products(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='submit' name='button' onclick=\"this.form.action.value='AddProductsToPriceBook';this.form.module.value='$related_module';this.form.return_module.value='$currentModule';this.form.return_action.value='PriceBookDetailView'\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
		}
		
		$query = 'select aicrm_products.productid, aicrm_products.productname, aicrm_products.productcode, aicrm_products.commissionrate, aicrm_products.qty_per_unit, aicrm_products.unit_price, aicrm_crmentity.crmid, aicrm_crmentity.smownerid,aicrm_pricebookproductrel.listprice from aicrm_products inner join aicrm_pricebookproductrel on aicrm_products.productid = aicrm_pricebookproductrel.productid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid inner join aicrm_pricebook on aicrm_pricebook.pricebookid = aicrm_pricebookproductrel.pricebookid  where aicrm_pricebook.pricebookid = '.$id.' and aicrm_crmentity.deleted = 0'; 
		
		$return_value = getPriceBookRelatedProducts($query,$this,$returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_pricebook_products method ...");
		return $return_value; 
	}	

	/**	function used to get the services which are related to the pricebook
	 *	@param int $id - pricebook id
         *      @return array - return an array which will be returned from the function getPriceBookRelatedServices
        **/
	function get_pricebook_services($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_pricebook_services(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='submit' name='button' onclick=\"this.form.action.value='AddServicesToPriceBook';this.form.module.value='$related_module';this.form.return_module.value='$currentModule';this.form.return_action.value='PriceBookDetailView'\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
		}

		$query = 'select aicrm_service.serviceid, aicrm_service.servicename, aicrm_service.commissionrate,  
			aicrm_service.qty_per_unit, aicrm_service.unit_price, aicrm_crmentity.crmid, aicrm_crmentity.smownerid,  
			aicrm_pricebookproductrel.listprice from aicrm_service 
			inner join aicrm_pricebookproductrel on aicrm_service.serviceid = aicrm_pricebookproductrel.productid  
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_service.serviceid
			inner join aicrm_pricebook on aicrm_pricebook.pricebookid = aicrm_pricebookproductrel.pricebookid
			where aicrm_pricebook.pricebookid = '.$id.' and aicrm_crmentity.deleted = 0';
		
		$return_value = $other->getPriceBookRelatedServices($query,$this,$returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_pricebook_services method ...");
		return $return_value; 
	}

	/**	function used to get whether the pricebook has related with a product or not
	 *	@param int $id - product id
	 *	@return true or false - if there are no pricebooks available or associated pricebooks for the product is equal to total number of pricebooks then return false, else return true
	 */
	function get_pricebook_noproduct($id)
	{
		global $log;
		$log->debug("Entering get_pricebook_noproduct(".$id.") method ...");
		 
		$query = "select aicrm_crmentity.crmid, aicrm_pricebook.* from aicrm_pricebook inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_pricebook.pricebookid where aicrm_crmentity.deleted=0";
		$result = $this->db->pquery($query, array());
		$no_count = $this->db->num_rows($result);
		if($no_count !=0)
		{
       	 	$pb_query = 'select aicrm_crmentity.crmid, aicrm_pricebook.pricebookid,aicrm_pricebookproductrel.productid from aicrm_pricebook inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_pricebook.pricebookid inner join aicrm_pricebookproductrel on aicrm_pricebookproductrel.pricebookid=aicrm_pricebook.pricebookid where aicrm_crmentity.deleted=0 and aicrm_pricebookproductrel.productid=?';
			$result_pb = $this->db->pquery($pb_query, array($id));
			if($no_count == $this->db->num_rows($result_pb))
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return false;
			}
			elseif($this->db->num_rows($result_pb) == 0)
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			}
			elseif($this->db->num_rows($result_pb) < $no_count)
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			}
		}
		else
		{
			$log->debug("Exiting get_pricebook_noproduct method ...");
			return false;
		}
	}
	
	/*
	 * Function to get the primary query part of a report 
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module){
	 			$moduletable = $this->table_name;
	 			$moduleindex = $this->table_index;
	 			
	 			$query = "from $moduletable
					inner join aicrm_crmentity on aicrm_crmentity.crmid=$moduletable.$moduleindex
					left join aicrm_currency_info as aicrm_currency_info$module on aicrm_currency_info$module.id = $moduletable.currency_id 
					left join aicrm_groups as aicrm_groups$module on aicrm_groups$module.groupid = aicrm_crmentity.smownerid 
					left join aicrm_users as aicrm_users$module on aicrm_users$module.id = aicrm_crmentity.smownerid 
					left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid 
					left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";
	            return $query;
	}
	
	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_pricebook","pricebookid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityPriceBooks on aicrm_crmentityPriceBooks.crmid=aicrm_pricebook.pricebookid and aicrm_crmentityPriceBooks.deleted=0 
				left join aicrm_currency_info as aicrm_currency_infoPriceBooks on aicrm_currency_infoPriceBooks.id = aicrm_pricebook.currency_id 
				left join aicrm_users as aicrm_usersPriceBooks on aicrm_usersPriceBooks.id = aicrm_crmentityPriceBooks.smownerid
				left join aicrm_groups as aicrm_groupsPriceBooks on aicrm_groupsPriceBooks.groupid = aicrm_crmentityPriceBooks.smownerid"; 

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Products" => array("aicrm_pricebookproductrel"=>array("pricebookid","productid"),"aicrm_pricebook"=>"pricebookid"),
			"Services" => array("aicrm_pricebookproductrel"=>array("pricebookid","productid"),"aicrm_pricebook"=>"pricebookid"),
		);
		return $rel_tables[$secmodule];
	}

}
?>
