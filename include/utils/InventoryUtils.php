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

/**
 * This function returns the Product detail block values in array format.
 * Input Parameter are $module - module name, $focus - module object, $num_of_products - no.of aicrm_products associated with it  * $associated_prod = associated product details
 * column aicrm_fields/
 */

function getProductDetailsBlockInfo($mode,$module,$focus='',$num_of_products='',$associated_prod='')
{
	global $log;
	$log->debug("Entering getProductDetailsBlockInfo(".$mode.",".$module.",".$num_of_products.",".$associated_prod.") method ...");
	
	$productDetails = Array();
	$productBlock = Array();
	if($num_of_products=='')
	{
		$num_of_products = getNoOfAssocProducts($module,$focus);
	}
	$productDetails['no_products'] = $num_of_products;
	if($associated_prod=='')
        {
		$productDetails['product_details'] = getAssociatedProducts($module,$focus);
	}
	else
	{
		$productDetails['product_details'] = $associated_prod;
	}
	if($focus != '')
	{
		$productBlock[] = Array('mode'=>$focus->mode);
		$productBlock[] = $productDetails['product_details'];
		$productBlock[] = Array('taxvalue' => $focus->column_fields['txtTax']);
		$productBlock[] = Array('taxAdjustment' => $focus->column_fields['txtAdjustment']);
		$productBlock[] = Array('hdnSubTotal' => $focus->column_fields['hdnSubTotal']);
		$productBlock[] = Array('hdnGrandTotal' => $focus->column_fields['hdnGrandTotal']);
	}
	else
	{
		$productBlock[] = Array(Array());
		
	}
	$log->debug("Exiting getProductDetailsBlockInfo method ...");
	return $productBlock;
}

/**
 * This function updates the stock information once the product is ordered.
 * Param $productid - product id
 * Param $qty - product quantity in no's
 * Param $mode - mode type
 * Param $ext_prod_arr - existing aicrm_products 
 * Param $module - module name
 * return type void
 */

function updateStk($product_id,$qty,$mode,$ext_prod_arr,$module)
{
	global $log;
	$log->debug("Entering updateStk(".$product_id.",".$qty.",".$mode.",".$ext_prod_arr.",".$module.") method ...");
	global $adb;
	global $current_user;

	$log->debug("Inside updateStk function, module=".$module);
	$log->debug("Product Id = $product_id & Qty = $qty");

	$prod_name = getProductName($product_id);
	$qtyinstk= getPrdQtyInStck($product_id);
	$log->debug("Prd Qty in Stock ".$qtyinstk);
	
	$upd_qty = $qtyinstk-$qty;
	//Send Email Update Stock Bas 19-11-2021
	//sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty,$module);
	

	$log->debug("Exiting updateStk method ...");
}

/**
 * This function sends a mail to the handler whenever the product reaches the reorder level.
 * Param $product_id - product id
 * Param $upd_qty - updated product quantity in no's
 * Param $prod_name - product name
 * Param $qtyinstk - quantity in stock 
 * Param $qty - quantity  
 * Param $module - module name
 * return type void
 */

function sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty,$module)
{
	global $log;
	$log->debug("Entering sendPrdStckMail(".$product_id.",".$upd_qty.",".$prod_name.",".$qtyinstk.",".$qty.",".$module.") method ...");
	global $current_user;
	global $adb;
	$reorderlevel = getPrdReOrderLevel($product_id);
	$log->debug("Inside sendPrdStckMail function, module=".$module);
	$log->debug("Prd reorder level ".$reorderlevel);
	
	if($upd_qty < $reorderlevel)
	{
		//send mail to the handler
		$handler=getPrdHandler($product_id);
		$handler_name = getUserName($handler);
		$to_address= getUserEmail($handler);
		//echo $handler_name; exit;
		//Get the email details from database;
		if($module == 'Quotes')
		{
			$notification_table = 'QuoteNotification';
			$quan_name = '{QUOTEQUANTITY}';
		}

		$query = "select * from aicrm_inventorynotification where notificationname=?";
		$result = $adb->pquery($query, array($notification_table));

		$subject = $adb->query_result($result,0,'notificationsubject');
		$body = $adb->query_result($result,0,'notificationbody');

		$subject = str_replace('{PRODUCTNAME}',$prod_name,$subject);
		$body = str_replace('{HANDLER}',$handler_name,$body);	
		$body = str_replace('{PRODUCTNAME}',$prod_name,$body);	
		if($module == 'Invoice')
		{
			$body = str_replace('{CURRENTSTOCK}',$upd_qty,$body);	
			$body = str_replace('{REORDERLEVELVALUE}',$reorderlevel,$body);
		}
		else
		{
			$body = str_replace('{CURRENTSTOCK}',$qtyinstk,$body);	
			$body = str_replace($quan_name,$qty,$body);	
		}
		$body = str_replace('{CURRENTUSER}',$current_user->user_name,$body);	

		$mail_status = send_mail($module,$to_address,$current_user->user_name,$current_user->email1,decode_html($subject),nl2br(to_html($body)));
	}
	$log->debug("Exiting sendPrdStckMail method ...");
}

/**This function is used to get the quantity in stock of a given product
*Param $product_id - product id
*Returns type numeric
*/
function getPrdQtyInStck($product_id)
{
	global $log;
	$log->debug("Entering getPrdQtyInStck(".$product_id.") method ...");
	global $adb;
	$query1 = "SELECT stockavailable FROM aicrm_products WHERE productid = ?";
	$result=$adb->pquery($query1, array($product_id));
	$qtyinstck= $adb->query_result($result,0,"qtyinstock");
	$log->debug("Exiting getPrdQtyInStck method ...");
	return $qtyinstck;
}

/**This function is used to get the reorder level of a product
*Param $product_id - product id
*Returns type numeric
*/

function getPrdReOrderLevel($product_id)
{
	global $log;
	$log->debug("Entering getPrdReOrderLevel(".$product_id.") method ...");
	global $adb;
	$query1 = "SELECT reorderlevel FROM aicrm_products WHERE productid = ?";
	$result=$adb->pquery($query1, array($product_id));
	$reorderlevel= $adb->query_result($result,0,"reorderlevel");
	$log->debug("Exiting getPrdReOrderLevel method ...");
	return $reorderlevel;
}

/**This function is used to get the handler for a given product
*Param $product_id - product id
*Returns type numeric
*/

function getPrdHandler($product_id)
{
	global $log;
	$log->debug("Entering getPrdHandler(".$product_id.") method ...");
	global $adb;
	$query1 = "SELECT handler FROM aicrm_products WHERE productid = ?";
	$result=$adb->pquery($query1, array($product_id));
	$handler= $adb->query_result($result,0,"handler");
	$log->debug("Exiting getPrdHandler method ...");
	return $handler;
}

/**	function to get the taxid
 *	@param string $type - tax type (VAT or Sales or Service)
 *	return int   $taxid - taxid corresponding to the Tax type from aicrm_inventorytaxinfo aicrm_table
 */
function getTaxId($type)
{
	global $adb, $log;
	$log->debug("Entering into getTaxId($type) function.");

	$res = $adb->pquery("SELECT taxid FROM aicrm_inventorytaxinfo WHERE taxname=?", array($type));
	$taxid = $adb->query_result($res,0,'taxid');

	$log->debug("Exiting from getTaxId($type) function. return value=$taxid");
	return $taxid;
}

/**	function to get the taxpercentage
 *	@param string $type       - tax type (VAT or Sales or Service)
 *	return int $taxpercentage - taxpercentage corresponding to the Tax type from aicrm_inventorytaxinfo aicrm_table
 */
function getTaxPercentage($type)
{
	global $adb, $log;
	$log->debug("Entering into getTaxPercentage($type) function.");

	$taxpercentage = '';

	$res = $adb->pquery("SELECT percentage FROM aicrm_inventorytaxinfo WHERE taxname = ?", array($type));
	$taxpercentage = $adb->query_result($res,0,'percentage');

	$log->debug("Exiting from getTaxPercentage($type) function. return value=$taxpercentage");
	return $taxpercentage;
}

/**	function to get the product's taxpercentage
 *	@param string $type       - tax type (VAT or Sales or Service)
 *	@param id  $productid     - productid to which we want the tax percentage
 *	@param id  $default       - if 'default' then first look for product's tax percentage and product's tax is empty then it will return the default configured tax percentage, else it will return the product's tax (not look for default value)
 *	return int $taxpercentage - taxpercentage corresponding to the Tax type from aicrm_inventorytaxinfo aicrm_table
 */
function getProductTaxPercentage($type,$productid,$default='')
{
	global $adb, $log;
	$log->debug("Entering into getProductTaxPercentage($type,$productid) function.");

	$taxpercentage = '';

	$res = $adb->pquery("SELECT taxpercentage
			FROM aicrm_inventorytaxinfo
			INNER JOIN aicrm_producttaxrel
				ON aicrm_inventorytaxinfo.taxid = aicrm_producttaxrel.taxid
			WHERE aicrm_producttaxrel.productid = ?
			AND aicrm_inventorytaxinfo.taxname = ?", array($productid, $type));
	$taxpercentage = $adb->query_result($res,0,'taxpercentage');

	//This is to retrive the default configured value if the taxpercentage related to product is empty
	if($taxpercentage == '' && $default == 'default')
		$taxpercentage = getTaxPercentage($type);


	$log->debug("Exiting from getProductTaxPercentage($productid,$type) function. return value=$taxpercentage");
	return $taxpercentage;
}

/**	Function used to add the history entry in the relevant tables for PO, SO, Quotes and Invoice modules
 *	@param string 	$module		- current module name
 *	@param int 	$id		- entity id
 *	@param string 	$relatedname	- parent name of the entity ie, required field venor name for PO and account name for SO, Quotes and Invoice
 *	@param float 	$total		- grand total value of the product details included tax
 *	@param string 	$history_fldval	- history field value ie., quotestage for Quotes and status for PO, SO and Invoice
 */
function addInventoryHistory($module, $id, $relatedname, $total, $history_fldval)
{
	global $log, $adb;
	$log->debug("Entering into function addInventoryHistory($module, $id, $relatedname, $total, $history_fieldvalue)");

	$history_table_array = Array(
					"Quotes"=>"aicrm_quotestagehistory"
	);

	$histid = $adb->getUniqueID($history_table_array[$module]);
 	$modifiedtime = $adb->formatDate(date('Y-m-d H:i:s'), true);
 	$query = "insert into $history_table_array[$module] values(?,?,?,?,?,?)";
	$qparams = array($histid,$id,$relatedname,$total,$history_fldval,$modifiedtime);	
	$adb->pquery($query, $qparams);

	$log->debug("Exit from function addInventoryHistory");
}

/**	Function used to get the list of Tax types as a array
 *	@param string $available - available or empty where as default is all, if available then the taxes which are available now will be returned otherwise all taxes will be returned
 *      @param string $sh - sh or empty, if sh passed then the shipping and handling related taxes will be returned
 *      @param string $mode - edit or empty, if mode is edit, then it will return taxes including desabled.
 *      @param string $id - crmid or empty, getting crmid to get tax values..
 *	return array $taxtypes - return all the tax types as a array
 */
function getAllTaxes($available='all', $sh='',$mode='',$id='')
{
	global $adb, $log;
	$log->debug("Entering into the function getAllTaxes($available,$sh,$mode,$id)");
	$taxtypes = Array();
	if($sh != '' && $sh == 'sh')
	{
		$tablename = 'aicrm_shippingtaxinfo';
		$value_table='aicrm_inventoryshippingrel';
	}
	else
	{
		$tablename = 'aicrm_inventorytaxinfo';
		$value_table='aicrm_inventoryproductrel';
	}
	
	if($mode == 'edit' && $id != '' )
	{
		//Getting total no of taxes

		$result_ids=array();
		$result=$adb->pquery("select taxname,taxid from $tablename",array());
		$noofrows=$adb->num_rows($result);

		$inventory_tax_val_result=$adb->pquery("select * from $value_table where id=?",array($id));

		//Finding which taxes are associated with this (SO,PO,Invoice,Quotes) and getting its taxid.
		for($i=0;$i<$noofrows;$i++)
		{

			$taxname=$adb->query_result($result,$i,'taxname');
			$taxid=$adb->query_result($result,$i,'taxid');

			$tax_val=$adb->query_result($inventory_tax_val_result,0,$taxname);
			if($tax_val != '')
			{
				array_push($result_ids,$taxid);
			}

		}
		//We are selecting taxes using that taxids. So It will get the tax even if the tax is disabled.
		$where_ids='';
		if (count($result_ids) > 0)
		{
			$insert_str = str_repeat("?,", count($result_ids)-1);
			$insert_str .= "?";
			$where_ids="taxid in ($insert_str) or";
		}

		$res = $adb->pquery("select * from $tablename  where $where_ids  deleted=0 order by taxid",$result_ids);
	}
	else
	{
		//This where condition is added to get all products or only availble products
		if($available != 'all' && $available == 'available')
		{
			$where = " where $tablename.deleted=0";
		}
	
		$res = $adb->pquery("select * from $tablename $where order by deleted",array());

	}
	
	$noofrows = $adb->num_rows($res);
	for($i=0;$i<$noofrows;$i++)
	{
		$taxtypes[$i]['taxid'] = $adb->query_result($res,$i,'taxid');
		$taxtypes[$i]['taxname'] = $adb->query_result($res,$i,'taxname');
		$taxtypes[$i]['taxlabel'] = $adb->query_result($res,$i,'taxlabel');
		$taxtypes[$i]['percentage'] = $adb->query_result($res,$i,'percentage');
		$taxtypes[$i]['deleted'] = $adb->query_result($res,$i,'deleted');
	}
	$log->debug("Exit from the function getAllTaxes($available,$sh,$mode,$id)");
	//print_r($taxtypes); 
	return $taxtypes;
}


/**	Function used to get all the tax details which are associated to the given product
 *	@param int $productid - product id to which we want to get all the associated taxes
 *	@param string $available - available or empty or available_associated where as default is all, if available then the taxes which are available now will be returned, if all then all taxes will be returned otherwise if the value is available_associated then all the associated taxes even they are not available and all the available taxes will be retruned
 *	@return array $tax_details - tax details as a array with productid, taxid, taxname, percentage and deleted
 */
function getTaxDetailsForProduct($productid, $available='all')
{
	global $log, $adb;
	$log->debug("Entering into function getTaxDetailsForProduct($productid)");
	if($productid != '')
	{
		//where condition added to avoid to retrieve the non available taxes
		$where = '';
		if($available != 'all' && $available == 'available')
		{
			$where = ' and aicrm_inventorytaxinfo.deleted=0';
		}
		if($available != 'all' && $available == 'available_associated')
		{
			$query = "SELECT aicrm_producttaxrel.*, aicrm_inventorytaxinfo.* FROM aicrm_inventorytaxinfo left JOIN aicrm_producttaxrel ON aicrm_inventorytaxinfo.taxid = aicrm_producttaxrel.taxid WHERE aicrm_producttaxrel.productid = ? or aicrm_inventorytaxinfo.deleted=0 GROUP BY aicrm_inventorytaxinfo.taxid";
		}
		else
		{
			$query = "SELECT aicrm_producttaxrel.*, aicrm_inventorytaxinfo.* FROM aicrm_inventorytaxinfo INNER JOIN aicrm_producttaxrel ON aicrm_inventorytaxinfo.taxid = aicrm_producttaxrel.taxid WHERE aicrm_producttaxrel.productid = ? $where";
		}
		$params = array($productid);

		//Postgres 8 fixes
 		if( $adb->dbType == "pgsql")
 		    $query = fixPostgresQuery( $query, $log, 0);
		
		$res = $adb->pquery($query, $params);
		for($i=0;$i<$adb->num_rows($res);$i++)
		{
			$tax_details[$i]['productid'] = $adb->query_result($res,$i,'productid');
			$tax_details[$i]['taxid'] = $adb->query_result($res,$i,'taxid');
			$tax_details[$i]['taxname'] = $adb->query_result($res,$i,'taxname');
			$tax_details[$i]['taxlabel'] = $adb->query_result($res,$i,'taxlabel');
			$tax_details[$i]['percentage'] = $adb->query_result($res,$i,'taxpercentage');
			$tax_details[$i]['deleted'] = $adb->query_result($res,$i,'deleted');
		}
	}
	else
	{
		$log->debug("Product id is empty. we cannot retrieve the associated products.");
	}

	$log->debug("Exit from function getTaxDetailsForProduct($productid)");
	return $tax_details;
}

/**	Function used to delete the Inventory product details for the passed entity
 *	@param int $objectid - entity id to which we want to delete the product details from REQUEST values where as the entity will be Purchase Order, Sales Order, Quotes or Invoice
 *	@param string $return_old_values - string which contains the string return_old_values or may be empty, if the string is return_old_values then before delete old values will be retrieved
 *	@return array $ext_prod_arr - if the second input parameter is 'return_old_values' then the array which contains the productid and quantity which will be retrieved before delete the product details will be returned otherwise return empty
 */
function deleteInventoryProductDetails($focus)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function deleteInventoryProductDetails(".$focus->id.").");
	
	$product_info = $adb->pquery("SELECT productid, quantity, sequence_no, incrementondel from aicrm_inventoryproductrel WHERE id=?",array($focus->id));
	$numrows = $adb->num_rows($product_info);
	for($index = 0;$index <$numrows;$index++){
		$productid = $adb->query_result($product_info,$index,'productid');
		$sequence_no = $adb->query_result($product_info,$index,'sequence_no');
		$qty = $adb->query_result($product_info,$index,'quantity');
		$incrementondel = $adb->query_result($product_info,$index,'incrementondel');
		
		if($incrementondel){
			$focus->update_product_array[$focus->id][$sequence_no][$productid]= $qty;
			$sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$sequence_no)); 
			if($adb->num_rows($sub_prod_query)>0){
				for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
					$sub_prod_id = $adb->query_result($sub_prod_query,$j,"productid");
					$focus->update_product_array[$focus->id][$sequence_no][$sub_prod_id]= $qty;
				}
			}
			
		}
	}
	$updateInventoryProductRel_update_product_array = $focus->update_product_array;


    // Start Log inventory
	$sql = "SELECT * FROM aicrm_inventoryproductrel WHERE id=" . $focus->id . " ORDER BY sequence_no";
	$rs = $adb->pquery($sql, '');
	$countList = $adb->num_rows($rs);
	$item_list = [];

	for ($i = 0; $i < $countList; $i++) {
		$row = $adb->query_result_rowdata($rs, $i);
		$item_list[] = $row;
	}
	// echo "<pre>"; print_r($item_list); echo "</pre>";exit;

	$file_name = "Quotes_" . $focus->id . ".txt";
	$folder_path = "logs/Quotes_inventory/";

	if (!file_exists($folder_path)) {
		mkdir($folder_path, 0777, true);  // สร้างโฟลเดอร์และกำหนดสิทธิ์
	}

	$FileName = $folder_path . $file_name;
	$file = fopen($FileName, 'a+') or die("Can't open file");

	if ($file) {
		foreach ($item_list as $i => $item) {
			// กรองเฉพาะคีย์ที่เป็นชื่อคอลัมน์เท่านั้น
			$filtered_item = array_filter($item, function($key) {
				return !is_numeric($key);
			}, ARRAY_FILTER_USE_KEY);

			// สร้างรายการคอลัมน์และค่า
			$columns = implode(", ", array_map(function ($col) {
				return "`$col`";
			}, array_keys($filtered_item)));

			$values = implode(", ", array_map(function ($value) {
				return "'" . addslashes($value) . "'";
			}, array_values($filtered_item)));

			$query = "INSERT INTO aicrm_inventoryproductrel ($columns) VALUES ($values);\n";
			
			// เพิ่ม timestamp และ action message
			fwrite($file, "-- ".date('Y-m-d H:i:s') . " => Action :: Delete item :: ".($i+1)."\r\n" . $query . "\r\n");
		}
		fclose($file);
	}
	// End Log inventory




    $adb->pquery("delete from aicrm_inventoryproductrel where id=?", array($focus->id));
    $adb->pquery("delete from aicrm_inventorysubproductrel where id=?", array($focus->id));
    $adb->pquery("delete from aicrm_inventoryshippingrel where id=?", array($focus->id));

	$log->debug("Exit from function deleteInventoryProductDetails(".$focus->id.")");
}

function updateInventoryProductRel($entity)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$entity_id = vtws_getIdComponents($entity->getId());
	$entity_id = $entity_id[1];
	$update_product_array = $updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function updateInventoryProductRel(".$entity_id.").");

	if(!empty($update_product_array)){
		foreach($update_product_array as $id=>$seq){
			foreach($seq as $seq=>$product_info)
			{
				foreach($product_info as $key=>$index){
					$updqtyinstk= getPrdQtyInStck($key);
					$upd_qty = $updqtyinstk+$index;
					updateProductQty($key, $upd_qty);
				}
			}
		}
	}
	$adb->pquery("UPDATE aicrm_inventoryproductrel SET incrementondel=1 WHERE id=?",array($entity_id));
	
	$product_info = $adb->pquery("SELECT productid,sequence_no, quantity from aicrm_inventoryproductrel WHERE id=?",array($entity_id));
	$numrows = $adb->num_rows($product_info);
	for($index = 0;$index <$numrows;$index++){
		$productid = $adb->query_result($product_info,$index,'productid');
		$qty = $adb->query_result($product_info,$index,'quantity');
		$sequence_no = $adb->query_result($product_info,$index,'sequence_no');
		$qtyinstk= getPrdQtyInStck($productid);
		$upd_qty = $qtyinstk-$qty;
		updateProductQty($productid, $upd_qty);
		$sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($entity_id,$sequence_no)); 
		if($adb->num_rows($sub_prod_query)>0){
			for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				$sub_prod_id = $adb->query_result($sub_prod_query,$j,"productid");
				$sqtyinstk= getPrdQtyInStck($sub_prod_id);
				$supd_qty = $sqtyinstk-$qty;
				updateProductQty($sub_prod_id, $supd_qty);
			}
		}
	}

	$log->debug("Exit from function updateInventoryProductRel(".$entity_id.")");
}

/**	Function used to save the Inventory product details for the passed entity
 *	@param object reference $focus - object reference to which we want to save the product details from REQUEST values where as the entity will be Purchase Order, Sales Order, Quotes or Invoice
 *	@param string $module - module name
 *	@param $update_prod_stock - true or false (default), if true we have to update the stock for PO only
 *	@return void
 */
function save_campaing($focus, $module, $update_prod_stock='false', $updateDemand=''){

	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryProductDetails($module).");
	//tab1	
	$mySetup_Tab1_Count = $_REQUEST['mySetup_Tab1_Count'];
	$mySetup_Tab2_Count = $_REQUEST['mySetup_Tab2_Count'];
	$query="delete from aicrm_inventory_campaign_dtl1 where id='".$focus->id."'";//echo $query;
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_campaign_dtl2 where id='".$focus->id."'";
	$adb->pquery($query,"");
	
	//tab1
	$prod_seq=1;
	for($i=1; $i<=$mySetup_Tab1_Count; $i++){
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["mySetup_Tab1_deleted".$i] == 1)
		continue;
		$productid = $_REQUEST['mySetup_Tab1_hdnProductId'.$i];
		$comment = $_REQUEST['mySetup_Tab1_comment'.$i];
		$uom = $_REQUEST['mySetup_Tab1_uom'.$i];
		$qty = $_REQUEST['mySetup_Tab1_qty'.$i];
		$listPrice = $_REQUEST['mySetup_Tab1_listPrice'.$i];
		
		$query ="insert into aicrm_inventory_campaign_dtl1(id, productid, sequence_no, quantity, listprice,uom, comment)
		values('".$focus->id."','".$productid."','".$prod_seq."','".$qty."','".$listPrice."','".$uom."','".$comment."')
		";
		$adb->pquery($query,"");
		$prod_seq++;
	}
	
	//tab2
	$prod_seq=1;
	for($i=1; $i<=$mySetup_Tab2_Count; $i++){
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["mySetup_Tab2_deleted".$i] == 1)
		continue;
		$campaign_from = $_REQUEST['mySetup_Tab2_campaign_from'.$i];
		$campaign_to = $_REQUEST['mySetup_Tab2_campaign_to'.$i];
		$campaign_fomula = $_REQUEST['mySetup_Tab2_campaign_fomula'.$i];
		$campaign_parameter = $_REQUEST['mySetup_Tab2_campaign_parameter'.$i];
		
		$query ="insert into aicrm_inventory_campaign_dtl2(id, sequence_no, campaign_from, campaign_to,campaign_fomula, campaign_parameter)
		values('".$focus->id."','".$prod_seq."','".$campaign_from."','".$campaign_to."','".$campaign_fomula."','".$campaign_parameter."')
		";
		$adb->pquery($query,"");
		$prod_seq++;
	}
	$log->debug("Exit from function save_campaing($module).");
}

function save_pro_tab1($focus, $module, $update_prod_stock='false', $updateDemand=''){
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryProductDetails($module).");
	//Tab	
	$tot_no_prod_tab1 = $_REQUEST['mySetup_Tab1_1_totalProductCount'];
	$tot_no_prod_tab2 = $_REQUEST['mySetup_Tab1_2_totalProductCount'];
	//Tab 1
	$query="delete from aicrm_inventory_protab1_dtl1 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab1_dtl2 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab1_dtl3 where id='".$focus->id."'";
	$adb->pquery($query,"");
	//Tab 2
	$query="delete from aicrm_inventory_protab2_dtl1 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab2_dtl2 where id='".$focus->id."'";
	$adb->pquery($query,"");
	//Tab 3
	$query="delete from aicrm_inventory_protab3 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab3_dtl where id='".$focus->id."'";
	$adb->pquery($query,"");

	//tab1
	$prod_seq=1;
	for($i=1; $i<=$tot_no_prod_tab1; $i++){
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["mySetup_Tab1_1_deleted".$i] == 1)
			continue;
		$productprice_from = $_REQUEST['mySetup_Tab1_1_productprice_from'.$i];
		$productprice_to = $_REQUEST['mySetup_Tab1_1_productprice_to'.$i];
		$campaign_fomula = $_REQUEST['campaign_fomula'.$i];
		$total_row_premium = $_REQUEST['total_row_premium'.$i];
		//echo $productprice_from." ".$productprice_to." ".$campaign_fomula." ".$total_row_premium;exit;
		$query ="insert into aicrm_inventory_protab1_dtl1(id, productprice_from, productprice_to, sequence_no, campaign_fomula)
		values('".$focus->id."','".$productprice_from."','".$productprice_to."','".$prod_seq."','".$campaign_fomula."')
		";
		//echo $query."<br>";
		$adb->pquery($query,"");
		for($k=1; $k<=$total_row_premium; $k++){
			$premiumrecord=0;
			if($_REQUEST["mySetup_Tab1_1_premiumrecord_".$i."_".$k] == ""){
				continue;
			}else{
				$premiumrecord=$_REQUEST["mySetup_Tab1_1_premiumrecord_".$i."_".$k];
			}

			$prod_seqq=1;
			for($kk=1; $kk<=$premiumrecord; $kk++){
				
				$premiumproductid=$_REQUEST["mySetup_Tab1_1_premiumid_dtl_".$i."_".$k."_".$kk];
				$premiumcode=$_REQUEST["mySetup_Tab1_1_premiumcode_dtl_".$i."_".$k."_".$kk];
				$productid=$_REQUEST["mySetup_Tab1_1_productid_dtl_".$i."_".$k."_".$kk];
				$uom=$_REQUEST["mySetup_Tab1_1_uom_dtl_".$i."_".$k."_".$kk];
				$qty=$_REQUEST["mySetup_Tab1_1_qty_dtl_".$i."_".$k."_".$kk];
				$listPrice=$_REQUEST["mySetup_Tab1_1_price_dtl_".$i."_".$k."_".$kk];
		
				$query ="insert into aicrm_inventory_protab1_dtl2(id, row_id, premiumproductid, premiumproductcode, productid, sequence_no, quantity, listprice,uom, comment)
				values('".$focus->id."','".$prod_seq."','".$premiumproductid."','".$premiumcode."','".$productid."','".$prod_seqq."','".$qty."','".$listPrice."','".$uom."','".$comment."')
				";
		
				$adb->pquery($query,"");
				$prod_seqq++;
			}
		}
		$prod_seq++;

	}
	
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	//tab2
	$prod_seq=1;
	$campaign_fomula = $_REQUEST['campaign_fomula_pro'];

	for($i=1; $i<=$tot_no_prod_tab2; $i++){
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["mySetup_Tab1_2_deleted".$i] == 1)
			continue;
		$productid = $_REQUEST['mySetup_Tab1_2_hdnProductId'.$i];
		$comment = $_REQUEST['mySetup_Tab1_2_comment'.$i];
		$uom = $_REQUEST['mySetup_Tab1_2_uom'.$i];
		$qty = $_REQUEST['mySetup_Tab1_2_qty'.$i];
		$listPrice = $_REQUEST['mySetup_Tab1_2_listPrice'.$i];
		
		$query ="insert into aicrm_inventory_protab1_dtl3(id, productid, sequence_no, quantity, listprice,uom, comment,campaign_fomula)
		values('".$focus->id."','".$productid."','".$prod_seq."','".$qty."','".$listPrice."','".$uom."','".$comment."','".$campaign_fomula."')
		";
		
		$adb->pquery($query,"");
		$prod_seq++;
	}
	$query="update aicrm_promotion set set_tab=1 where promotionid='".$focus->id."'";
	$adb->pquery($query,"");
	$log->debug("Exit from function save_pro_tab1($module).");
}

function save_pro_tab2($focus, $module, $update_prod_stock='false', $updateDemand=''){

	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function save_pro_tab2($module).");

	//Tab 1
	$query="delete from aicrm_inventory_protab1_dtl1 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab1_dtl2 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab1_dtl3 where id='".$focus->id."'";
	$adb->pquery($query,"");
	//Tab 2
	$query="delete from aicrm_inventory_protab2_dtl1 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab2_dtl2 where id='".$focus->id."'";
	$adb->pquery($query,"");
	//Tab 3
	$query="delete from aicrm_inventory_protab3 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab3_dtl where id='".$focus->id."'";
	$adb->pquery($query,"");
	
	$campaign_fomula_head = $_REQUEST['mySetup_Tab2_campaign_fomula_head'];
	$mySetup_Tab2_qty = $_REQUEST['mySetup_Tab2_qty'];
	//echo $campaign_fomula_head;
	$tot_no_prod_tab2 = $_REQUEST['mySetup_Tab2_totalProductCount'];
	$campaign_fomula = $_REQUEST['mySetup_Tab2_campaign_fomula1'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	//tab2
	$prod_seq=1;
	for($i=1; $i<=$tot_no_prod_tab2; $i++){

		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["mySetup_Tab2_deleted".$i] == 1)
			continue;
		$productid = $_REQUEST['mySetup_Tab2_hdnProductId'.$i];
		$comment = $_REQUEST['mySetup_Tab2_comment'.$i];
		$uom = $_REQUEST['mySetup_Tab2_uom'.$i];
		$qty = $_REQUEST['mySetup_Tab2_qty'.$i];
		$listPrice = $_REQUEST['mySetup_Tab2_listPrice'.$i];
		
		$query ="insert into aicrm_inventory_protab2_dtl1(id, productid, sequence_no, quantity, listprice,uom, comment,campaign_fomula,hearder_fomula,hearder_qty)
		values('".$focus->id."','".$productid."','".$prod_seq."','".$qty."','".$listPrice."','".$uom."','".$comment."','".$campaign_fomula."','".$campaign_fomula_head."','".$mySetup_Tab2_qty."')
		";
		
		$adb->pquery($query,"");
		$prod_seq++;
	}
	
	$prod_seqq=1;
	$total_row_premium = $_REQUEST['total_row_mySetup_Tab2_1'];
	for($k=1; $k<=$total_row_premium; $k++){
		$premiumrecord=0;
		
		if($_REQUEST["mySetup_Tab2_premiumrecord_1_".$k] == ""){
			continue;
		}else{
			
			$premiumrecord=$_REQUEST["mySetup_Tab2_premiumrecord_1_".$k];
		}
		
		$prod_seqq=1;
		for($kk=1; $kk<=$premiumrecord; $kk++){
		
			$premiumproductid=$_REQUEST["mySetup_Tab2_premiumid_dtl_1_".$k."_".$kk];
			$premiumproductcode=$_REQUEST["mySetup_Tab2_premiumcode_dtl_1_".$k."_".$kk];
			$productid=$_REQUEST["mySetup_Tab2_productid_dtl_1_".$k."_".$kk];
			$uom=$_REQUEST["mySetup_Tab2_uom_dtl_1_".$k."_".$kk];
			$qty=$_REQUEST["mySetup_Tab2_qty_dtl_1_".$k."_".$kk];
			$listPrice=$_REQUEST["mySetup_Tab2_price_dtl_1_".$k."_".$kk];
		
			$query ="insert into aicrm_inventory_protab2_dtl2(id, row_id,premiumproductid,premiumproductcode, productid, sequence_no, quantity, listprice,uom, comment)
			values('".$focus->id."',1,'".$premiumproductid."','".$premiumproductcode."','".$productid."','".$prod_seqq."','".$qty."','".$listPrice."','".$uom."','".$comment."')
			";
		
			$adb->pquery($query,"");
			$prod_seqq++;
		}
	}
	
	$query="update aicrm_promotion set set_tab=2 where promotionid='".$focus->id."'";
	$adb->pquery($query,"");
	$log->debug("Exit from function save_pro_tab2($module).");
}

function save_pro_tab3($focus, $module, $update_prod_stock='false', $updateDemand=''){

	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryProductDetails($module).");
	//header================================
	$mySetup_Disc_Name = $_REQUEST['mySetup_Disc_Name'];
	$mySetup_Disc_Discount_type = $_REQUEST['mySetup_Disc_Discount_type'];
	$mySetup_Disc_Dis_value = $_REQUEST['mySetup_Disc_Dis_value'];
	$mySetup_Disc_total = $_REQUEST['mySetup_Disc_total'];
	$mySetup_Disc_Discount = $_REQUEST['mySetup_Disc_Discount'];
	$mySetup_Disc_Net = $_REQUEST['mySetup_Disc_Net'];
	
	//Tab 1
	$query="delete from aicrm_inventory_protab1_dtl1 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab1_dtl2 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab1_dtl3 where id='".$focus->id."'";
	$adb->pquery($query,"");
	//Tab 2
	$query="delete from aicrm_inventory_protab2_dtl1 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab2_dtl2 where id='".$focus->id."'";
	$adb->pquery($query,"");
	//Tab 3
	$query="delete from aicrm_inventory_protab3 where id='".$focus->id."'";
	$adb->pquery($query,"");
	$query="delete from aicrm_inventory_protab3_dtl where id='".$focus->id."'";
	$adb->pquery($query,"");

	$query ="insert into aicrm_inventory_protab3(`id`, `disc_name`, `disc_discount_type`, `disc_dis_value`, `disc_total`, `disc_discount`, `disc_net`)
	values('".$focus->id."','".$mySetup_Disc_Name."','".$mySetup_Disc_Discount_type."','".$mySetup_Disc_Dis_value."','".$mySetup_Disc_total."','".$mySetup_Disc_Discount."','".$mySetup_Disc_Net."')
	";

	$adb->pquery($query,"");
	//detail=================================
	$ext_prod_arr = Array();
	//delete detail
	
	$tot_no_prod = $_REQUEST['mySetup_Disc_totalProductCount'];

	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;
	for($i=1; $i<=$tot_no_prod; $i++){
		//if the product is deleted then we should avoid saving the deleted products
		//echo $_REQUEST["mySetup_Disc_deleted".$i]."";
		if($_REQUEST["mySetup_Disc_deleted".$i] == 1)
			continue;
		$productid = $_REQUEST['mySetup_Disc_hdnProductId'.$i];
		$comment = $_REQUEST['mySetup_Disc_comment'.$i];
		$uom = $_REQUEST['mySetup_Disc_uom'.$i];
		$qty = $_REQUEST['mySetup_Disc_qty'.$i];
		$listPrice = $_REQUEST['mySetup_Disc_listPrice'.$i];
		
		$query ="insert into aicrm_inventory_protab3_dtl(id, productid, sequence_no, quantity, listprice,uom, comment)
		values('".$focus->id."','".$productid."','".$prod_seq."','".$qty."','".$listPrice."','".$uom."','".$comment."')
		";

		$adb->pquery($query,"");
		$prod_seq++;
	}

	$query="update aicrm_promotion set set_tab=3 where promotionid='".$focus->id."'";
	$adb->pquery($query,"");
	$log->debug("Exit from function save_pro_tab3($module).");
}

function saveInventoryProductDetails($focus, $module, $update_prod_stock='false', $updateDemand='')
{
	// echo "<pre>"; print_r($focus); echo "</pre>";
	// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryProductDetails($module).");
	//Added to get the convertid
	if(isset($_REQUEST['convert_from']) && $_REQUEST['convert_from'] !='')
	{
		$id=$_REQUEST['return_id'];
	}
	else if(isset($_REQUEST['duplicate_from']) && $_REQUEST['duplicate_from'] !='')
	{
		$id=$_REQUEST['duplicate_from'];
	}
	else if(isset($_REQUEST['revise_from']) && $_REQUEST['revise_from'] !='')
	{
		$id=$_REQUEST['revise_from'];
	}

	$ext_prod_arr = Array();
	if($focus->mode == 'edit')
	{
		if($_REQUEST['taxtype'] == 'group')
			$all_available_taxes = getAllTaxes('available','','edit',$id);
			$return_old_values = '';
		if($module != 'PurchaseOrder')
		{
			$return_old_values = 'return_old_values';
		}

		//we will retrieve the existing product details and store it in a array and then delete all the existing product details and save new values, retrieve the old value and update stock only for SO, Quotes and Invoice not for PO
		//$ext_prod_arr = deleteInventoryProductDetails($focus->id,$return_old_values);
		deleteInventoryProductDetails($focus);
		// exit;
	}
	else
	{
	if($_REQUEST['taxtype'] == 'group')
		$all_available_taxes = getAllTaxes('available','','edit',$id);
	}	
	$tot_no_prod = $_REQUEST['totalProductCount'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;

	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;

	    $prod_id = $_REQUEST['hdnProductId'.$i];
		$hdn_cal_total = $_REQUEST['hdn_cal_total'.$i];
		$hdn_cal_discount = $_REQUEST['hdn_cal_discount'.$i];
		if(isset($_REQUEST['productDescription'.$i]))
			$description = $_REQUEST['productDescription'.$i];

        $qty = $_REQUEST['qty'.$i];
        $listprice = $_REQUEST['listPrice'.$i];
        $listprice_inc = $_REQUEST['listprice_inc'.$i];
        $listprice_exc = $_REQUEST['listprice_exc'.$i];
        
		$comment = $_REQUEST['comment'.$i];
		$problem = $_REQUEST['problem'.$i];
		$cause = $_REQUEST['cause'.$i];
		$protect = $_REQUEST['protect'.$i];
		$startdt = $_REQUEST['startdt'.$i];
		$line = $_REQUEST['line'.$i];
		$type = $_REQUEST['type'.$i];
		$tik_status = $_REQUEST['tik_status'.$i];
		$lot_no = $_REQUEST['lot_no'.$i];
		
		$no_head = $_REQUEST['no_head'.$i];
		$no_sub = $_REQUEST['no_sub'.$i];
		$type_pro = $_REQUEST['type_pro'.$i];
		$count_osp = $_REQUEST['count_osp'.$i];
		$purchase_cost = $_REQUEST['purchase_cost'.$i];
		$count_store = $_REQUEST['count_store'.$i];
		$cost_sales = $_REQUEST['cost_sales'.$i];
		$consult_cost = $_REQUEST['consult_cost'.$i];
		$sale_com = $_REQUEST['sale_com'.$i];
		$plan_com = $_REQUEST['plan_com'.$i];
		$status1 = $_REQUEST['status1'.$i];
		$status2 = $_REQUEST['status2'.$i];
		$status3 = $_REQUEST['status3'.$i];
		
		$type_application = $_REQUEST['type_application'.$i];
		$coordinator = $_REQUEST['coordinator'.$i];
		$coordinator_phone = $_REQUEST['coordinator_phone'.$i];
		$coordinator_fax = $_REQUEST['coordinator_fax'.$i];
		$coordinator_mobile = $_REQUEST['coordinator_mobile'.$i];
		$coordinator_email = $_REQUEST['coordinator_email'.$i];
		$request_cer = $_REQUEST['request_cer'.$i];
		$remark = $_REQUEST['remark'.$i];
		$payment = $_REQUEST['payment'.$i];
		$payment_status = $_REQUEST['payment_status'.$i];
		$premium_code = $_REQUEST['premium_code'.$i];
		$score = $_REQUEST['score'.$i];

		$last_price = $_REQUEST['price_list_inv'.$i];
		$productName = $_REQUEST['productName'.$i];
		$product_price_type = $_REQUEST['product_price_type'.$i];
		$pricelist_type = $_REQUEST['pricelist_type'.$i];
		
        $pack_size = $_REQUEST['pack_size'.$i];
        $test_box = $_REQUEST['test_box'.$i];
		$uom = $_REQUEST['uom'.$i];
		$valid_from = $_REQUEST['valid_from'.$i];
		$valid_to = $_REQUEST['valid_to'.$i];
		
		$pro_type = $_REQUEST['pro_type'.$i];
		$promotion_id = $_REQUEST['promotion_id'.$i];
		//Module Project
		$qty_act= $_REQUEST['qty_act'.$i];
		$listprice_total= $_REQUEST['listprice_total'.$i];
		$qty_ship= $_REQUEST['qty_ship'.$i];
		$status_dtl= $_REQUEST['status_dtl'.$i];
		$qty_remain= $_REQUEST['qty_remain'.$i];
		
		$product_finish = $_REQUEST['product_finish'.$i];
		$product_size_mm = $_REQUEST['product_size_mm'.$i];
		$product_thinkness = $_REQUEST['product_thinkness'.$i];
		$product_cost_avg = $_REQUEST['product_cost_avg'.$i];
		$competitor_price = $_REQUEST['competitor_price'.$i];

		$competitor_brand = $_REQUEST['competitor_brand'.$i];
		$compet_brand_in_proj = $_REQUEST['compet_brand_in_proj'.$i];
		$compet_brand_in_proj_price = $_REQUEST['compet_brand_in_proj_price'.$i];
		$product_unit = $_REQUEST['product_unit'.$i];
		$selling_price = $_REQUEST['selling_price'.$i];


		$package_size_sheet_per_box = $_REQUEST['package_size_sheet_per_box'.$i];
		$package_size_sqm_per_box = $_REQUEST['package_size_sqm_per_box'.$i];
		$box_quantity = $_REQUEST['box_quantity'.$i];
		$sales_unit = $_REQUEST['sales_unit'.$i];
		$sheet_quantity = $_REQUEST['sheet_quantity'.$i];
		$sqm_quantity = $_REQUEST['sqm_quantity'.$i];
		$regular_price = $_REQUEST['regular_price'.$i];
		$product_discount = $_REQUEST['product_discount'.$i];
		

		$standard_price_inc = $_REQUEST['standard_price_inc'.$i];
		if($module=="PriceList"){
			//$standard_price = $_REQUEST['StandardPrice'.$i];
			$listprice = $_REQUEST['listPrice'.$i];
		}elseif($module=="Quotes"){
			$listprice = $selling_price;
		}else{
			$standard_price = $_REQUEST['price_list_std'.$i];
		}

		$valid_from=str_replace("/","-",$valid_from);
		$valid_to=str_replace("/","-",$valid_to);
		
		if($module=="PriceList"){
			$relatemodule = $_REQUEST['lineItemType'.$i];
			$monetary = $_REQUEST['monetary'.$i];
		}
		if($module=="PriceList"){
			$query ="insert into aicrm_inventoryproductrel(id, productid, product_name, sequence_no, listprice, comment, description, module, monetary)
			values('".$focus->id."','".$prod_id."', '".$productName."' ,'".$prod_seq."','".$listprice."' ,'".$comment."','".$description."','".$relatemodule."','".$monetary."')";
			$adb->pquery($query,"");
		}else if($module=="Projects"){
			$query ="insert into aicrm_inventoryproductrel(id, productid, sequence_no, quantity, comment, description, uom, listprice ,quantity_act ,listprice_total , quantity_ship , status,quantity_remain) 
			values('".$focus->id."','".$prod_id."','".$prod_seq."','".$qty."','".$comment."','".$description."','".$uom."','".$listprice."' , '".$qty_act."' , '".$listprice_total."' , '".$qty_ship."' , '".$status_dtl ."' , '". $qty_remain  ."')";
			$adb->pquery($query,"");
		}else{
			$relatemodule = $_REQUEST['lineItemType'.$i];
			$query ="insert into aicrm_inventoryproductrel(id, productid, sequence_no, quantity, listprice
			,listprice_inc,listprice_exc, `comment`, description,problem,cause,protect,startdt,line,`type`,tik_status,lot_no,no_head
			,no_sub,type_pro,count_osp,purchase_cost,count_store,cost_sales,consult_cost,sale_com,plan_com
			,status1,status2,status3,type_application,coordinator,coordinator_phone,coordinator_fax
			,coordinator_mobile,coordinator_email,request_cer,remark,payment,payment_status,premium_code
			,score,pack_size,test_box,uom,product_name,last_price,standard_price,module,product_finish,product_size_mm,product_thinkness,product_cost_avg,competitor_price
			,competitor_brand,compet_brand_in_proj,compet_brand_in_proj_price,product_unit,selling_price

			,package_size_sheet_per_box,package_size_sqm_per_box,box_quantity,sheet_quantity,sqm_quantity,regular_price,product_discount,product_price_type,pricelist_type,sales_unit,hdn_cal_total,hdn_cal_discount
			) 
			values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	
			$qparams = array($focus->id,$prod_id,$prod_seq,$qty,$listprice
			,$listprice_inc,$listprice_exc,$comment,$description,$problem,$cause,$protect,$startdt,$line,$type,$tik_status,$lot_no,$no_head
			,$no_sub,$type_pro,$count_osp,$purchase_cost,$count_store,$cost_sales,$consult_cost,$sale_com,$plan_com
			,$status1,$status2,$status3,$type_application,$coordinator,$coordinator_phone,$coordinator_fax
			,$coordinator_mobile,$coordinator_email,$request_cer,$remark,$payment,$payment_status,$premium_code
			,$score,$pack_size,$test_box,$uom,$productName,$last_price,$standard_price,$relatemodule,$product_finish,$product_size_mm,$product_thinkness,$product_cost_avg,$competitor_price
			,$competitor_brand,$compet_brand_in_proj,$compet_brand_in_proj_price,$product_unit,$selling_price
			,$package_size_sheet_per_box,$package_size_sqm_per_box,$box_quantity,$sheet_quantity,$sqm_quantity,$regular_price,$product_discount,$product_price_type,$pricelist_type,$sales_unit,$hdn_cal_total,$hdn_cal_discount
			);
			$adb->pquery($query,$qparams);

			// Start Log inventory
			$file_name = "Quotes_" . $focus->id . ".txt";
			$folder_path = "logs/Quotes_inventory/";

			if (!file_exists($folder_path)) {
				mkdir($folder_path, 0777, true);
			}

			$FileName = $folder_path . $file_name;
			$file = fopen($FileName, 'a+') or die("Can't open file");

			if ($file) {
				$data_query = "INSERT INTO aicrm_inventoryproductrel (
					id, productid, sequence_no, quantity, listprice, listprice_inc, listprice_exc, `comment`, description, 
					problem, cause, protect, startdt, line, `type`, tik_status, lot_no, no_head, no_sub, type_pro, count_osp, 
					purchase_cost, count_store, cost_sales, consult_cost, sale_com, plan_com, status1, status2, status3, 
					type_application, coordinator, coordinator_phone, coordinator_fax, coordinator_mobile, coordinator_email, 
					request_cer, remark, payment, payment_status, premium_code, score, pack_size, test_box, uom, product_name, 
					last_price, standard_price, module, product_finish, product_size_mm, product_thinkness, product_cost_avg, 
					competitor_price, competitor_brand, compet_brand_in_proj, compet_brand_in_proj_price, product_unit, 
					selling_price, package_size_sheet_per_box, package_size_sqm_per_box, box_quantity, sheet_quantity, 
					sqm_quantity, regular_price, product_discount, product_price_type, pricelist_type, sales_unit, 
					hdn_cal_total, hdn_cal_discount
				) VALUES (
					'" . $focus->id . "', '" . $prod_id . "', '" . $prod_seq . "', '" . $qty . "', '" . $listprice . "',
					'" . $listprice_inc . "', '" . $listprice_exc . "', '" . $comment . "', '" . $description . "', 
					'" . $problem . "', '" . $cause . "', '" . $protect . "', '" . $startdt . "', '" . $line . "', 
					'" . $type . "', '" . $tik_status . "', '" . $lot_no . "', '" . $no_head . "', '" . $no_sub . "', 
					'" . $type_pro . "', '" . $count_osp . "', '" . $purchase_cost . "', '" . $count_store . "', 
					'" . $cost_sales . "', '" . $consult_cost . "', '" . $sale_com . "', '" . $plan_com . "', 
					'" . $status1 . "', '" . $status2 . "', '" . $status3 . "', '" . $type_application . "', 
					'" . $coordinator . "', '" . $coordinator_phone . "', '" . $coordinator_fax . "', '" . $coordinator_mobile . "', 
					'" . $coordinator_email . "', '" . $request_cer . "', '" . $remark . "', '" . $payment . "', 
					'" . $payment_status . "', '" . $premium_code . "', '" . $score . "', '" . $pack_size . "', 
					'" . $test_box . "', '" . $uom . "', '" . $productName . "', '" . $last_price . "', 
					'" . $standard_price . "', '" . $relatemodule . "', '" . $product_finish . "', '" . $product_size_mm . "', 
					'" . $product_thinkness . "', '" . $product_cost_avg . "', '" . $competitor_price . "', 
					'" . $competitor_brand . "', '" . $compet_brand_in_proj . "', '" . $compet_brand_in_proj_price . "', 
					'" . $product_unit . "', '" . $selling_price . "', '" . $package_size_sheet_per_box . "', 
					'" . $package_size_sqm_per_box . "', '" . $box_quantity . "', '" . $sheet_quantity . "', 
					'" . $sqm_quantity . "', '" . $regular_price . "', '" . $product_discount . "', 
					'" . $product_price_type . "', '" . $pricelist_type . "', '" . $sales_unit . "', 
					'" . $hdn_cal_total . "', '" . $hdn_cal_discount . "'
				);";
		
				fwrite($file, "-- ".date('Y-m-d H:i:s') . " => Action :: Insert item :: ".$prod_seq."\r\n" . $data_query . "\r\n");
				fclose($file);
			}
			// End Log inventory

		}

		$sub_prod_str = $_REQUEST['subproduct_ids'.$i];
		if (!empty($sub_prod_str)) {
			$sub_prod = split(":",$sub_prod_str);
			for($j=0;$j<count($sub_prod);$j++){
				$query ="insert into aicrm_inventorysubproductrel(id, sequence_no, productid) values(?,?,?)";
				$qparams = array($focus->id,$prod_seq,$sub_prod[$j]);
				$adb->pquery($query,$qparams);
			}
		}
		$prod_seq++;

		if($module != 'PurchaseOrder' && $module != 'HelpDesk' && $module != 'Projects' && $module != 'Quotes' && $module != 'Salesinvoice' && $module != 'PriceList')
		{
			//update the stock with existing details
			updateStk($prod_id,$qty,$focus->mode,$ext_prod_arr,$module);
		}

		//we should update discount and tax details
		$updatequery = "update aicrm_inventoryproductrel set ";
		$updateparams = array();

		//set the discount percentage or discount amount in update query, then set the tax values
		if($_REQUEST['discount_type'.$i] == 'percentage')
		{
			$updatequery .= " discount_percent=?,";
			array_push($updateparams, $_REQUEST['discount_percentage'.$i]);
		}
		elseif($_REQUEST['discount_type'.$i] == 'amount')
		{
			$updatequery .= " discount_amount=?,";
			$discount_amount = $_REQUEST['discount_amount'.$i];
			array_push($updateparams, $discount_amount);
		}
		if($_REQUEST['taxtype'] == 'group')
		{
			for($tax_count=0;$tax_count<count($all_available_taxes);$tax_count++)
			{
				$tax_name = $all_available_taxes[$tax_count]['taxname'];
				$tax_val = $all_available_taxes[$tax_count]['percentage'];
				$request_tax_name = $tax_name."_group_percentage";
				if(isset($_REQUEST[$request_tax_name]))
					$tax_val =$_REQUEST[$request_tax_name];
				$updatequery .= " $tax_name = ?,";
				array_push($updateparams,$tax_val);
			}
			$updatequery = trim($updatequery,',')." where id=? and productid=?";
			array_push($updateparams,$focus->id,$prod_id);
		}
		else
		{
			$taxes_for_product = getTaxDetailsForProduct($prod_id,'all');
			for($tax_count=0;$tax_count<count($taxes_for_product);$tax_count++)
			{
				$tax_name = $taxes_for_product[$tax_count]['taxname'];
				$request_tax_name = $tax_name."_percentage".$i;
			
				$updatequery .= " $tax_name = ?,";
				array_push($updateparams, $_REQUEST[$request_tax_name]);
			}
				$updatequery = trim($updatequery,',')." where id=? and productid=?";
				array_push($updateparams, $focus->id,$prod_id);
		}

 		if( !preg_match( '/set\s+where/i', $updatequery)) {
 		    $adb->pquery($updatequery,$updateparams);
 		}
	}
	
	//we should update the netprice (subtotal), taxtype, group discount, S&H charge, S&H taxes, adjustment and total
	//netprice, group discount, taxtype, S&H amount, adjustment and total to entity table
	$updatequery  = " update $focus->table_name set ";
	$updateparams = array();
	$subtotal = $_REQUEST['subtotal'];
	$updatequery .= " subtotal=?,";
	array_push($updateparams, $subtotal);

	$updatequery .= " taxtype=?,";
	array_push($updateparams, $_REQUEST['taxtype']);

	//for discount percentage or discount amount
	if($_REQUEST['discount_type_final'] == 'percentage')
	{
		$updatequery .= " discount_percent=?,";
		array_push($updateparams, $_REQUEST['discount_percentage_final']);
	}
	elseif($_REQUEST['discount_type_final'] == 'amount')
	{
		$discount_amount_final = $_REQUEST['discount_amount_final'];
		$updatequery .= " discount_amount=?,";
		array_push($updateparams, $discount_amount_final);
	}
	
	$shipping_handling_charge = $_REQUEST['shipping_handling_charge'];
	$updatequery .= " s_h_amount=?,";
	array_push($updateparams, $shipping_handling_charge);

	//if the user gave - sign in adjustment then add with the value
	$adjustmentType = '';
	if($_REQUEST['adjustmentType'] == '-')
		$adjustmentType = $_REQUEST['adjustmentType'];

	$adjustment = $_REQUEST['adjustment'];
	$updatequery .= " adjustment=?,";
	array_push($updateparams, $adjustmentType.$adjustment);

	if($_REQUEST['discountTotal_final']!='' && ($module == 'Quotes' || $module == 'Salesinvoice')){
		$discountTotal_final = $_REQUEST['discountTotal_final'];
		$updatequery .= " discount_amount=?,";
		array_push($updateparams, $discountTotal_final);
	}

	if($_REQUEST['total_discount']!=''){
		$total_discount = $_REQUEST['total_discount'];
		$updatequery .= " discountTotal_final=?,";
		array_push($updateparams, $total_discount);
	}
	
	if($_REQUEST['total_after_discount']!=''){
		$total_after_discount = $_REQUEST['total_after_discount'];
		$updatequery .= " total_after_discount=?,";
		array_push($updateparams, $total_after_discount);
	}
	if($_REQUEST['tax1_group_percentage']!=''){
		$tax1_group_percentage = $_REQUEST['tax1_group_percentage'];
		$updatequery .= " tax1=?,";
		array_push($updateparams, $tax1_group_percentage);
	}
	
	if($_REQUEST['total_tax']!=''){
		$total_tax = $_REQUEST['total_tax'];
		$updatequery .= " tax_final=?,";
		array_push($updateparams, $total_tax);
	}
	
	if($module=="Quotes" || $module=="Salesorder" || $module=="Salesinvoice"){

		if($module=="Quotes" || $module=="Salesinvoice"){
			if($_REQUEST['tax_final']!=''){
				$tax_final = $_REQUEST['tax_final'];
				$updatequery .= " tax_final=?,";
				array_push($updateparams, $tax_final);
			}
		}

		include_once 'library/general.php';
		$text_currency_th = num2thai(str_replace(",","",number_format($_REQUEST['total'],2)));
		$text_currency_en = bahtEng(str_replace(",","",number_format($_REQUEST['total'],2)));
	
		if($focus->column_fields['currency_id']=="2")
		{
			$text_currency_th = str_replace("บาท","ดอลลาร์ ",str_replace("สตางค์","เซนต์",$text_currency_th));
			$text_currency_en = str_replace("Baht","Dollars",str_replace("Satang","Cents",$text_currency_en));
		}
	
		$updatequery .= " text_currency_th=?,";
		array_push($updateparams, $text_currency_th);
	
		$updatequery .= " text_currency_en=?,";
		array_push($updateparams, $text_currency_en);

		if($module=="Quotes" || $module=="Salesinvoice"){
			$discount_coupon = $_REQUEST['discount_coupon'];
			$updatequery .= " discount_coupon=?,";
			array_push($updateparams, $discount_coupon);

			$total_without_vat = $_REQUEST['total_without_vat'];
			$updatequery .= " total_without_vat=?,";
			array_push($updateparams, $total_without_vat);

			$total_after_bill_discount = $_REQUEST['total_after_bill_discount'];
			$updatequery .= " total_after_bill_discount=?,";
			array_push($updateparams, $total_after_bill_discount);

			$hdn_bill_discount = $_REQUEST['hdn_bill_discount'];
			$updatequery .= " hdn_bill_discount=?,";
			array_push($updateparams, $hdn_bill_discount);

			$hdn_total_after_bill_discount = $_REQUEST['hdn_total_after_bill_discount'];
			$updatequery .= " hdn_total_after_bill_discount=?,";
			array_push($updateparams, $hdn_total_after_bill_discount);

			if($module=="Salesinvoice") {
				$deposit_amount = $_REQUEST['n_deposit'];
				$updatequery .= " deposit_amount=?,";
				array_push($updateparams, $deposit_amount);

				$deposit_amount_after = $_REQUEST['n_after_deposit'];
				$updatequery .= " deposit_amount_after=?,";
				array_push($updateparams, $deposit_amount_after);
			}
		}

	}
	
	$total = $_REQUEST['total'];
	$updatequery .= " total=?";
	array_push($updateparams, $total);
	//$id_array = Array('PurchaseOrder'=>'purchaseorderid','SalesOrder'=>'salesorderid','Quotes'=>'quoteid','Invoice'=>'invoiceid');
	//Added where condition to which entity we want to update these values
	$updatequery .= " where ".$focus->table_index."=?";
	array_push($updateparams, $focus->id);
	//echo $updatequery;

	// Start Log inventory
	$file_name = "Quotes_" . $focus->id . ".txt";
	$folder_path = "logs/Quotes_inventory/";

	if (!file_exists($folder_path)) {
		mkdir($folder_path, 0777, true);
	}

	$FileName = $folder_path . $file_name;
	$file = fopen($FileName, 'a+') or die("Can't open file");

	// Replace placeholders with values from the array
	$txt_updatequery = $updatequery;
	foreach ($updateparams as $param) {
		$txt_updatequery = preg_replace('/\?/', "'" . $param . "'", $txt_updatequery, 1);
	}

	if ($file) {
		fwrite($file, "-- ".date('Y-m-d H:i:s') . " => Action :: Update Quotes \r\n" .$txt_updatequery."\r\n");
		fclose($file);
	}
	// End Log inventory

	//echo "<pre>"; print_r($updateparams); echo "</pre>"; exit;
	$adb->pquery($updatequery,$updateparams);
	//to save the S&H tax details in aicrm_inventoryshippingrel table
	$sh_tax_details = getAllTaxes('all','sh');
	$sh_query_fields = "id,";
	$sh_query_values = "?,";
	$sh_query_params = array($focus->id);
	for($i=0;$i<count($sh_tax_details);$i++)
	{
		$tax_name = $sh_tax_details[$i]['taxname']."_sh_percent";
		if($_REQUEST[$tax_name] != '')
		{
			$sh_query_fields .= $sh_tax_details[$i]['taxname'].",";
			$sh_query_values .= "?,";
			array_push($sh_query_params, $_REQUEST[$tax_name]);
		}
	}
	$sh_query_fields = trim($sh_query_fields,',');
	$sh_query_values = trim($sh_query_values,',');

	$sh_query = "insert into aicrm_inventoryshippingrel($sh_query_fields) values($sh_query_values)";
	$adb->pquery($sh_query,$sh_query_params);
	$log->debug("Exit from function saveInventoryProductDetails($module).");
}

function deleteInventorySamplerequisition($focus)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function deleteInventorySamplerequisition(".$focus->id.").");
    $adb->pquery("delete from aicrm_inventorysamplerequisition where id=?", array($focus->id));
	$log->debug("Exit from function deleteInventorySamplerequisition(".$focus->id.")");
}

function saveInventorySamplerequisition($focus, $module, $update_prod_stock='false', $updateDemand='')
{	
	//echo "<pre>"; print_r($focus); echo "</pre>";
	//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventorySamplerequisition($module).");
	//Added to get the convertid
	if(isset($_REQUEST['convert_from']) && $_REQUEST['convert_from'] !='')
	{
		$id=$_REQUEST['return_id'];
	}
	else if(isset($_REQUEST['duplicate_from']) && $_REQUEST['duplicate_from'] !='')
	{
		$id=$_REQUEST['duplicate_from'];
	}
	else if(isset($_REQUEST['revise_from']) && $_REQUEST['revise_from'] !='')
	{
		$id=$_REQUEST['revise_from'];
	}

	$ext_prod_arr = Array();
	if($focus->mode == 'edit')
	{
		if($_REQUEST['taxtype'] == 'group')
			$all_available_taxes = getAllTaxes('available','','edit',$id);
			$return_old_values = '';
		if($module != 'PurchaseOrder')
		{
			$return_old_values = 'return_old_values';
		}
		
		deleteInventorySamplerequisition($focus);
	}
	else
	{
	if($_REQUEST['taxtype'] == 'group')
		$all_available_taxes = getAllTaxes('available','','edit',$id);
	}	
	$tot_no_prod = $_REQUEST['totalProductCount'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;

	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;

	    $prod_id = $_REQUEST['hdnProductId'.$i];
		if(isset($_REQUEST['productDescription'.$i]))
			$description = $_REQUEST['productDescription'.$i];

		$lineItemType = $_REQUEST['lineItemType'.$i];
		$productcode = $_REQUEST['productcode'.$i];
		$subproduct_ids = $_REQUEST['subproduct_ids'.$i];
		$comment = $_REQUEST['comment'.$i];
		$sr_finish = $_REQUEST['sr_finish'.$i];
		$sr_size_mm = $_REQUEST['sr_size_mm'.$i];
		$sr_thickness_mm = $_REQUEST['sr_thickness_mm'.$i];
		$sr_product_unit = $_REQUEST['sr_product_unit'.$i];
		$amount_of_sample = $_REQUEST['amount_of_sample'.$i];
		$amount_of_purchase = $_REQUEST['amount_of_purchase'.$i];
		
		$query ="insert into aicrm_inventorysamplerequisition(id, productid, sequence_no, comment, description, lineitemtype, productcode ,subproduct_ids ,sr_finish ,sr_size_mm , sr_thickness_mm , sr_product_unit,amount_of_sample,amount_of_purchase) 
		values('".$focus->id."','".$prod_id."','".$prod_seq."','".$comment."','".$description."','".$lineItemType."','".$productcode."' , '".$subproduct_ids."' , '".$sr_finish."' , '".$sr_size_mm."' , '".$sr_thickness_mm."' , '".$sr_product_unit ."' , '". $amount_of_sample  ."', '". $amount_of_purchase  ."')";
		$adb->pquery($query,"");
		
		$prod_seq++;
	}
	
	/*total_amount_of_sample
	total_amount_of_purchase*/

	//we should update the netprice (subtotal), taxtype, group discount, S&H charge, S&H taxes, adjustment and total
	//netprice, group discount, taxtype, S&H amount, adjustment and total to entity table
	$updatequery  = " update $focus->table_name set ";
	$updateparams = array();
	$total_amount_of_sample = $_REQUEST['total_amount_of_sample'];
	$updatequery .= " total_amount_of_sample=?,";
	array_push($updateparams, $total_amount_of_sample);

	$updatequery .= " total_amount_of_purchase=?";
	array_push($updateparams, $_REQUEST['total_amount_of_purchase']);
	
	$updatequery .= " where ".$focus->table_index."=?";
	array_push($updateparams, $focus->id);
	
	$adb->pquery($updatequery,$updateparams);
	
	$log->debug("Exit from function saveInventorySamplerequisition($module).");
}

function deleteInventoryPurchasesorder($focus)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function deleteInventoryPurchasesorder(".$focus->id.").");
    $adb->pquery("delete from aicrm_inventorypurchasesorderrel where id=?", array($focus->id));
	$log->debug("Exit from function deleteInventoryPurchasesorder(".$focus->id.")");
}

function saveInventoryPurchasesorder($focus, $module, $update_prod_stock='false', $updateDemand='')
{	
	// echo "<pre>";
	// print_r($_REQUEST);
	// echo "</pre>"; exit;
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryPurchasesorder($module).");
	//Added to get the convertid
	if(isset($_REQUEST['convert_from']) && $_REQUEST['convert_from'] !='')
	{
		$id=$_REQUEST['return_id'];
	}
	else if(isset($_REQUEST['duplicate_from']) && $_REQUEST['duplicate_from'] !='')
	{
		$id=$_REQUEST['duplicate_from'];
	}
	else if(isset($_REQUEST['revise_from']) && $_REQUEST['revise_from'] !='')
	{
		$id=$_REQUEST['revise_from'];
	}

	$ext_prod_arr = Array();
	if($focus->mode == 'edit')
	{
		if($_REQUEST['taxtype'] == 'group')
			$all_available_taxes = getAllTaxes('available','','edit',$id);
			$return_old_values = '';
		
			deleteInventoryPurchasesorder($focus);
	}
	else
	{
	if($_REQUEST['taxtype'] == 'group')
		$all_available_taxes = getAllTaxes('available','','edit',$id);
	}	
	$tot_no_prod = $_REQUEST['totalProductCount'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;

	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;

	    $prod_id = $_REQUEST['hdnProductId'.$i];
		if(isset($_REQUEST['productDescription'.$i]))
			$description = $_REQUEST['productDescription'.$i];

		$productName = $_REQUEST['productName'.$i];
		$lineItemType = $_REQUEST['lineItemType'.$i];
		$comment = $_REQUEST['comment'.$i];

		$projects_name = $_REQUEST['projects_name'.$i];
		$projectsid = $_REQUEST['projectsid'.$i];
		$assignto = $_REQUEST['assignto'.$i];
		$smownerid = $_REQUEST['smownerid'.$i];
		$product_brand = $_REQUEST['product_brand'.$i];
		$product_group = $_REQUEST['product_group'.$i];
		$product_code_crm = $_REQUEST['product_code_crm'.$i];
		$product_prefix = $_REQUEST['product_prefix'.$i];
		$product_factory_code = $_REQUEST['product_factory_code'.$i];
		$product_design_name = $_REQUEST['product_design_name'.$i];
		$product_finish_name = $_REQUEST['product_finish_name'.$i];
		$product_size_ft = $_REQUEST['product_size_ft'.$i];
		$product_thinkness = $_REQUEST['product_thinkness'.$i];
		$product_grade = $_REQUEST['product_grade'.$i];
		$product_film = $_REQUEST['product_film'.$i];
		$product_backprint = $_REQUEST['product_backprint'.$i];
		$po_quantity = $_REQUEST['po_quantity'.$i];
		$gr_percentage = $_REQUEST['gr_percentage'.$i];
		$gr_quantity = $_REQUEST['gr_quantity'.$i];
		$defects_quantity = $_REQUEST['defects_quantity'.$i];
		$remain_quantity = $_REQUEST['remain_quantity'.$i];
		$gr_qty_percent = $_REQUEST['gr_qty_percent'.$i];
		$item_status = $_REQUEST['item_status'.$i];
		$po_price_type = $_REQUEST['po_price_type'.$i];
		$price_usd = $_REQUEST['price_usd'.$i];
		$unit_price = $_REQUEST['unit_price'.$i];
        
        $totalamount = ($po_quantity*$unit_price);

		$query ="insert into aicrm_inventorypurchasesorderrel(id, productid, product_name, sequence_no, comment,projectsid,projects_name,assignto,smownerid,product_brand,product_group,product_code_crm,product_prefix,product_factory_code,product_design_name,product_finish_name,product_size_ft,product_thinkness,product_grade,product_film,po_quantity,gr_quantity,defects_quantity,remain_quantity,gr_qty_percent,price_usd,unit_price,total_amount,product_backprint,gr_percentage,item_status,po_price_type) values('".$focus->id."','".$prod_id."', '".$productName."' ,'".$prod_seq."','".$comment."','".$projectsid."','".$projects_name."','".$assignto."','".$smownerid."','".$product_brand."','".$product_group."','".$product_code_crm."','".$product_prefix."','".$product_factory_code."','".$product_design_name."','".$product_finish_name."','".$product_size_ft."','".$product_thinkness."','".$product_grade."','".$product_film."','".$po_quantity."','".$gr_quantity."','".$defects_quantity."','".$remain_quantity."','".$gr_qty_percent."','".$price_usd."','".$unit_price."','".$totalamount."','".$product_backprint."','".$gr_percentage."','".$item_status."','".$po_price_type."')";
		$adb->pquery($query,"");

		$prod_seq++;

		//we should update discount and tax details
		$updatequery = "update aicrm_inventorypurchasesorderrel set ";
		$updateparams = array();

		//set the discount percentage or discount amount in update query, then set the tax values
		if($_REQUEST['discount_type'.$i] == 'percentage')
		{
			$updatequery .= " discount_percent=?,";
			array_push($updateparams, $_REQUEST['discount_percentage'.$i]);
		}
		elseif($_REQUEST['discount_type'.$i] == 'amount')
		{
			$updatequery .= " discount_amount=?,";
			$discount_amount = $_REQUEST['discount_amount'.$i];
			array_push($updateparams, $discount_amount);
		}
		if($_REQUEST['taxtype'] == 'group')
		{
			for($tax_count=0;$tax_count<count($all_available_taxes);$tax_count++)
			{
				$tax_name = $all_available_taxes[$tax_count]['taxname'];
				$tax_val = $all_available_taxes[$tax_count]['percentage'];
				$request_tax_name = $tax_name."_group_percentage";
				if(isset($_REQUEST[$request_tax_name]))
					$tax_val =$_REQUEST[$request_tax_name];
				$updatequery .= " $tax_name = ?,";
				array_push($updateparams,$tax_val);
			}
			$updatequery = trim($updatequery,',')." where id=? and productid=?";
			array_push($updateparams,$focus->id,$prod_id);
		}
		else
		{
			$taxes_for_product = getTaxDetailsForProduct($prod_id,'all');
			for($tax_count=0;$tax_count<count($taxes_for_product);$tax_count++)
			{
				$tax_name = $taxes_for_product[$tax_count]['taxname'];
				$request_tax_name = $tax_name."_percentage".$i;
			
				$updatequery .= " $tax_name = ?,";
				array_push($updateparams, $_REQUEST[$request_tax_name]);
			}
				$updatequery = trim($updatequery,',')." where id=? and productid=?";
				array_push($updateparams, $focus->id,$prod_id);
		}

 		if( !preg_match( '/set\s+where/i', $updatequery)) {
 		    $adb->pquery($updatequery,$updateparams);
 		}
	}
	
	//we should update the netprice (subtotal), taxtype, group discount, S&H charge, S&H taxes, adjustment and total
	//netprice, group discount, taxtype, S&H amount, adjustment and total to entity table
	$updatequery  = " update $focus->table_name set ";
	$updateparams = array();
	$subtotal = $_REQUEST['subtotal'];
	$updatequery .= " subtotal=?,";
	array_push($updateparams, $subtotal);

	$updatequery .= " taxtype=?,";
	array_push($updateparams, $_REQUEST['taxtype']);

	//for discount percentage or discount amount
	if($_REQUEST['discount_type_final'] == 'percentage')
	{
		$updatequery .= " discount_percent=?,";
		array_push($updateparams, $_REQUEST['discount_percentage_final']);
	}
	elseif($_REQUEST['discount_type_final'] == 'amount')
	{
		$discount_amount_final = $_REQUEST['discount_amount_final'];
		$updatequery .= " discount_amount=?,";
		array_push($updateparams, $discount_amount_final);
	}
	
	$shipping_handling_charge = $_REQUEST['shipping_handling_charge'];
	$updatequery .= " s_h_amount=?,";
	array_push($updateparams, $shipping_handling_charge);

	//if the user gave - sign in adjustment then add with the value
	$adjustmentType = '';
	if($_REQUEST['adjustmentType'] == '-')
		$adjustmentType = $_REQUEST['adjustmentType'];

	$adjustment = $_REQUEST['adjustment'];
	$updatequery .= " adjustment=?,";
	array_push($updateparams, $adjustmentType.$adjustment);

	if($_REQUEST['total_discount']!=''){
		$total_discount = $_REQUEST['total_discount'];
		$updatequery .= " discountTotal_final=?,";
		array_push($updateparams, $total_discount);
	}
	
	if($_REQUEST['total_after_discount']!=''){
		$total_after_discount = $_REQUEST['total_after_discount'];
		$updatequery .= " total_after_discount=?,";
		array_push($updateparams, $total_after_discount);
	}
	if($_REQUEST['tax1_group_percentage']!=''){
		$tax1_group_percentage = $_REQUEST['tax1_group_percentage'];
		$updatequery .= " tax1=?,";
		array_push($updateparams, $tax1_group_percentage);
	}
	
	if($_REQUEST['total_tax']!=''){
		$total_tax = $_REQUEST['total_tax'];
		$updatequery .= " tax_final=?,";
		array_push($updateparams, $total_tax);
	}
	
	
	include_once 'library/general.php';
	$text_currency_th = num2thai(str_replace(",","",number_format($_REQUEST['total'],2)));
	$text_currency_en = bahtEng(str_replace(",","",number_format($_REQUEST['total'],2)));

	if($focus->column_fields['currency_id']=="2")
	{
		$text_currency_th = str_replace("บาท","ดอลลาร์ ",str_replace("สตางค์","เซนต์",$text_currency_th));
		$text_currency_en = str_replace("Baht","Dollars",str_replace("Satang","Cents",$text_currency_en));
	}

	$updatequery .= " text_currency_th=?,";
	array_push($updateparams, $text_currency_th);

	$updatequery .= " text_currency_en=?,";
	array_push($updateparams, $text_currency_en);

	$total = $_REQUEST['total'];
	$updatequery .= " total=?";
	array_push($updateparams, $total);

	$updatequery .= " where ".$focus->table_index."=?";
	array_push($updateparams, $focus->id);
	
	$adb->pquery($updatequery,$updateparams);
	//to save the S&H tax details in aicrm_inventoryshippingrel table
	$sh_tax_details = getAllTaxes('all','sh');
	$sh_query_fields = "id,";
	$sh_query_values = "?,";
	$sh_query_params = array($focus->id);
	for($i=0;$i<count($sh_tax_details);$i++)
	{
		$tax_name = $sh_tax_details[$i]['taxname']."_sh_percent";
		if($_REQUEST[$tax_name] != '')
		{
			$sh_query_fields .= $sh_tax_details[$i]['taxname'].",";
			$sh_query_values .= "?,";
			array_push($sh_query_params, $_REQUEST[$tax_name]);
		}
	}
	$sh_query_fields = trim($sh_query_fields,',');
	$sh_query_values = trim($sh_query_values,',');

	$log->debug("Exit from function saveInventoryProductDetails($module).");
}
/**	function used to get the tax type for the entity (PO, SO, Quotes or Invoice)
 *	@param string $module - module name
 *	@param int $id - id of the PO or SO or Quotes or Invoice
 *	@return string $taxtype - taxtype for the given entity which will be individual or group
 */
function getInventoryTaxType($module, $id)
{
	global $log, $adb;
	if ($module!="HelpDesk" && $module!="ServiceRequest" && $module!="InternalTraining" && $module!="Promotion" && $module!="CampaignPoint" && $module!="Salesinvoice"){
		$log->debug("Entering into function getInventoryTaxType($module, $id).");

		$inv_table_array = Array('Purchasesorder'=>'aicrm_purchasesorder','Salesorder'=>'aicrm_salesorder','Quotes'=>'aicrm_quotes','Projects'=>'aicrm_projects','Invoice'=>'aicrm_invoice','Order'=>'aicrm_order');
		$inv_id_array = Array('Purchasesorder'=>'purchasesorderid','Salesorder'=>'salesorderid','Quotes'=>'quoteid','Projects'=>'projectsid','Invoice'=>'invoiceid','Order'=>'orderid');
		
		$res = $adb->pquery("select taxtype from $inv_table_array[$module] where $inv_id_array[$module]=?", array($id));
		
		$taxtype = $adb->query_result($res,0,'taxtype');

		$log->debug("Exit from function getInventoryTaxType($module, $id).");
	}else{
		$taxtype="0";
	}

	return $taxtype;
}

/**	function used to get the price type for the entity (PO, SO, Quotes or Invoice)
 *	@param string $module - module name
 *	@param int $id - id of the PO or SO or Quotes or Invoice
 *	@return string $pricetype - pricetype for the given entity which will be unitprice or secondprice
 */
function getInventoryCurrencyInfo($module, $id)
{	
	global $log, $adb;
	if ($module!="HelpDesk" && $module!="ServiceRequest" && $module!="InternalTraining" && $module!="Promotion" && $module!="CampaignPoint" && $module!="Salesinvoice"){
		$log->debug("Entering into function getInventoryCurrencyInfo($module, $id).");

		$inv_table_array = Array('Purchasesorder'=>'aicrm_purchasesorder','Salesorder'=>'aicrm_salesorder','Quotes'=>'aicrm_quotes','Projects'=>'aicrm_projects','Invoice'=>'aicrm_invoice','HelpDesk'=>'aicrm_ticketstatus','Order'=>'aicrm_order');

		$inv_id_array = Array('Purchasesorder'=>'purchasesorderid','Salesorder'=>'salesorderid','Quotes'=>'quoteid','Projects'=>'projectsid','Invoice'=>'invoiceid','HelpDesk'=>'ticketstatus_id','Order'=>'orderid' );
		
		$inventory_table = $inv_table_array[$module];
		$inventory_id = $inv_id_array[$module];
		$res = $adb->pquery("select currency_id, $inventory_table.conversion_rate as conv_rate, aicrm_currency_info.* from $inventory_table
							inner join aicrm_currency_info on $inventory_table.currency_id = aicrm_currency_info.id
							where $inventory_id=?", array($id));
		$currency_info = array();
		$currency_info['currency_id'] = $adb->query_result($res,0,'currency_id');
		$currency_info['conversion_rate'] = $adb->query_result($res,0,'conv_rate');
		$currency_info['currency_name'] = $adb->query_result($res,0,'currency_name');
		$currency_info['currency_code'] = $adb->query_result($res,0,'currency_code');
		$currency_info['currency_symbol'] = $adb->query_result($res,0,'currency_symbol');
		$log->debug("Exit from function getInventoryCurrencyInfo($module, $id).");
	}else{
	
	}
	return $currency_info;
}

/**	function used to get the taxvalue which is associated with a product for PO/SO/Quotes or Invoice
 *	@param int $id - id of PO/SO/Quotes or Invoice
 *	@param int $productid - product id
 *	@param string $taxname - taxname to which we want the value
 *	@return float $taxvalue - tax value
 */
function getInventoryProductTaxValue($id, $productid, $taxname)
{
	global $log, $adb;
	$log->debug("Entering into function getInventoryProductTaxValue($id, $productid, $taxname).");
	
	$res = $adb->pquery("select $taxname from aicrm_inventoryproductrel where id = ? and productid = ?", array($id, $productid));
	$taxvalue = $adb->query_result($res,0,$taxname);

	if($taxvalue == '')
		$taxvalue = '0.00';

	$log->debug("Exit from function getInventoryProductTaxValue($id, $productid, $taxname).");

	return $taxvalue;
}

/**	function used to get the shipping & handling tax percentage for the given inventory id and taxname
 *	@param int $id - entity id which will be PO/SO/Quotes or Invoice id
 *	@param string $taxname - shipping and handling taxname
 *	@return float $taxpercentage - shipping and handling taxpercentage which is associated with the given entity
 */
function getInventorySHTaxPercent($id, $taxname)
{
	global $log, $adb;
	$log->debug("Entering into function getInventorySHTaxPercent($id, $taxname)");
	
	$res = $adb->pquery("select $taxname from aicrm_inventoryshippingrel where id= ?", array($id));
	$taxpercentage = $adb->query_result($res,0,$taxname);

	if($taxpercentage == '')
		$taxpercentage = '0.00';

	$log->debug("Exit from function getInventorySHTaxPercent($id, $taxname)");

	return $taxpercentage;
}

/**	Function used to get the list of all Currencies as a array
 *  @param string available - if 'all' returns all the currencies, default value 'available' returns only the currencies which are available for use.
 *	return array $currency_details - return details of all the currencies as a array
 */
function getAllCurrencies($available='available') {
	global $adb, $log;
	$log->debug("Entering into function getAllCurrencies($available)");
	
	$sql = "select * from aicrm_currency_info";
	if ($available != 'all') {
		$sql .= " where currency_status='Active' and deleted=0";
	}
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$currency_details[$i]['currencylabel'] = $adb->query_result($res,$i,'currency_name');
		$currency_details[$i]['currencycode'] = $adb->query_result($res,$i,'currency_code');
		$currency_details[$i]['currencysymbol'] = $adb->query_result($res,$i,'currency_symbol');
		$currency_details[$i]['curid'] = $adb->query_result($res,$i,'id');
		$currency_details[$i]['conversionrate'] = $adb->query_result($res,$i,'conversion_rate');
		$currency_details[$i]['curname'] = 'curname' . $adb->query_result($res,$i,'id');			
	}
	
	$log->debug("Entering into function getAllCurrencies($available)");
	return $currency_details;
	
}

/**	Function used to get all the price details for different currencies which are associated to the given product
 *	@param int $productid - product id to which we want to get all the associated prices
 *  @param decimal $unit_price - Unit price of the product
 *  @param string $available - available or available_associated where as default is available, if available then the prices in the currencies which are available now will be returned, otherwise if the value is available_associated then prices of all the associated currencies will be retruned
 *	@return array $price_details - price details as a array with productid, curid, curname
 */
function getPriceDetailsForProduct($productid, $unit_price, $available='available', $itemtype='Products')
{
	global $log, $adb;
	$log->debug("Entering into function getPriceDetailsForProduct($productid)");
	if($productid != '')
	{
		$product_currency_id = getProductBaseCurrency($productid, $itemtype);
		$product_base_conv_rate = getBaseConversionRateForProduct($productid,'edit',$itemtype);
		// Detail View
		if ($available == 'available_associated') {
			$query = "select aicrm_currency_info.*, aicrm_productcurrencyrel.converted_price, aicrm_productcurrencyrel.actual_price 
					from aicrm_currency_info 
					inner join aicrm_productcurrencyrel on aicrm_currency_info.id = aicrm_productcurrencyrel.currencyid
					where aicrm_currency_info.currency_status = 'Active' and aicrm_currency_info.deleted=0 
					and aicrm_productcurrencyrel.productid = ? and aicrm_currency_info.id != ?";
			$params = array($productid, $product_currency_id);
		} else { // Edit View
			$query = "select aicrm_currency_info.*, aicrm_productcurrencyrel.converted_price, aicrm_productcurrencyrel.actual_price 
					from aicrm_currency_info 
					left join aicrm_productcurrencyrel 
					on aicrm_currency_info.id = aicrm_productcurrencyrel.currencyid and aicrm_productcurrencyrel.productid = ?
					where aicrm_currency_info.currency_status = 'Active' and aicrm_currency_info.deleted=0";
			$params = array($productid);			
		}

		//Postgres 8 fixes
 		if( $adb->dbType == "pgsql")
 		    $query = fixPostgresQuery( $query, $log, 0);

		$res = $adb->pquery($query, $params);
		for($i=0;$i<$adb->num_rows($res);$i++)
		{
			$price_details[$i]['productid'] = $productid;
			$price_details[$i]['currencylabel'] = $adb->query_result($res,$i,'currency_name');
			$price_details[$i]['currencycode'] = $adb->query_result($res,$i,'currency_code');
			$price_details[$i]['currencysymbol'] = $adb->query_result($res,$i,'currency_symbol');
			$currency_id = $adb->query_result($res,$i,'id');
			$price_details[$i]['curid'] = $currency_id;
			$price_details[$i]['curname'] = 'curname' . $adb->query_result($res,$i,'id');
			$cur_value = $adb->query_result($res,$i,'actual_price');
			
			// Get the conversion rate for the given currency, get the conversion rate of the product currency to base currency. 
			// Both together will be the actual conversion rate for the given currency.
			$conversion_rate = $adb->query_result($res,$i,'conversion_rate');
			$actual_conversion_rate = $product_base_conv_rate * $conversion_rate;
			
			if ($cur_value == null || $cur_value == '') {
				$price_details[$i]['check_value'] = false;
				if	($unit_price != null) {
					$cur_value = convertFromMasterCurrency($unit_price, $actual_conversion_rate);
				} else {
					$cur_value = '0';
				}
			} else {
				$price_details[$i]['check_value'] = true;
			}
			$price_details[$i]['curvalue'] = $cur_value;
			$price_details[$i]['conversionrate'] = $actual_conversion_rate;		
			
			$is_basecurrency = false;
			if ($currency_id == $product_currency_id) {
				$is_basecurrency = true;
			}
			$price_details[$i]['is_basecurrency'] = $is_basecurrency;		
		}
	}
	else
	{
		if($available == 'available') { // Create View
			global $current_user;
			
			$user_currency_id = fetchCurrency($current_user->id);
			
			$query = "select aicrm_currency_info.* from aicrm_currency_info 
					where aicrm_currency_info.currency_status = 'Active' and aicrm_currency_info.deleted=0";
			$params = array();
			
			$res = $adb->pquery($query, $params);
			for($i=0;$i<$adb->num_rows($res);$i++)
			{
				$price_details[$i]['currencylabel'] = $adb->query_result($res,$i,'currency_name');
				$price_details[$i]['currencycode'] = $adb->query_result($res,$i,'currency_code');
				$price_details[$i]['currencysymbol'] = $adb->query_result($res,$i,'currency_symbol');
				$currency_id = $adb->query_result($res,$i,'id');
				$price_details[$i]['curid'] = $currency_id;
				$price_details[$i]['curname'] = 'curname' . $adb->query_result($res,$i,'id');
				
				// Get the conversion rate for the given currency, get the conversion rate of the product currency(logged in user's currency) to base currency. 
				// Both together will be the actual conversion rate for the given currency.
				$conversion_rate = $adb->query_result($res,$i,'conversion_rate');
				$user_cursym_convrate = getCurrencySymbolandCRate($user_currency_id);
				$product_base_conv_rate = 1 / $user_cursym_convrate['rate'];
				$actual_conversion_rate = $product_base_conv_rate * $conversion_rate;
				
				$price_details[$i]['check_value'] = false;
				$price_details[$i]['curvalue'] = '0';
				$price_details[$i]['conversionrate'] = $actual_conversion_rate;		
			
				$is_basecurrency = false;
				if ($currency_id == $user_currency_id) {
					$is_basecurrency = true;
				}
				$price_details[$i]['is_basecurrency'] = $is_basecurrency;				
			}
		} else {
			$log->debug("Product id is empty. we cannot retrieve the associated prices.");
		}
	}

	$log->debug("Exit from function getPriceDetailsForProduct($productid)");
	return $price_details;
}

/**	Function used to get the base currency used for the given Product
 *	@param int $productid - product id for which we want to get the id of the base currency
 *  @return int $currencyid - id of the base currency for the given product
 */
function getProductBaseCurrency($productid,$module='Products') {
	global $adb, $log;
	if ($module == 'Services') {
		$sql = "select currency_id from aicrm_service where serviceid=?";		
	} else {
		$sql = "select currency_id from aicrm_products where productid=?";
	}
	$params = array($productid);	
	$res = $adb->pquery($sql, $params);
	$currencyid = $adb->query_result($res, 0, 'currency_id');	
	return $currencyid;	
}

/**	Function used to get the conversion rate for the product base currency with respect to the CRM base currency
 *	@param int $productid - product id for which we want to get the conversion rate of the base currency
 *  @param string $mode - Mode in which the function is called
 *  @return number $conversion_rate - conversion rate of the base currency for the given product based on the CRM base currency
 */
function getBaseConversionRateForProduct($productid, $mode='edit', $module='Products') {
	global $adb, $log, $current_user;
	
	if ($mode == 'edit') {
		if ($module == 'Services') {			
			$sql = "select conversion_rate from aicrm_service inner join aicrm_currency_info 
					on aicrm_service.currency_id = aicrm_currency_info.id where aicrm_service.serviceid=?";
		} else {
			$sql = "select conversion_rate from aicrm_products inner join aicrm_currency_info 
					on aicrm_products.currency_id = aicrm_currency_info.id where aicrm_products.productid=?";
		}
		$params = array($productid);
	} else {
		$sql = "select conversion_rate from aicrm_currency_info where id=?";
		$params = array(fetchCurrency($current_user->id));		
	}
	
	$res = $adb->pquery($sql, $params);
	$conv_rate = $adb->query_result($res, 0, 'conversion_rate');
	
	return 1 / $conv_rate;
}

/**	Function used to get the prices for the given list of products based in the specified currency
 *	@param int $currencyid - currency id based on which the prices have to be provided
 *	@param array $product_ids - List of product id's for which we want to get the price based on given currency
 *  @return array $prices_list - List of prices for the given list of products based on the given currency in the form of 'product id' mapped to 'price value'
 */
function getPricesForProducts($currencyid, $product_ids, $module='Products') {
	global $adb,$log,$current_user;
	
	$price_list = array();
	if (count($product_ids) > 0) {
		if ($module == 'Services') {
			$query = "SELECT aicrm_currency_info.id, aicrm_currency_info.conversion_rate, " .
					"aicrm_service.serviceid AS productid, aicrm_service.unit_price, " .
					"aicrm_productcurrencyrel.actual_price " .
					"FROM (aicrm_currency_info, aicrm_service) " .
					"left join aicrm_productcurrencyrel on aicrm_service.serviceid = aicrm_productcurrencyrel.productid " .
					"and aicrm_currency_info.id = aicrm_productcurrencyrel.currencyid " .
					"where aicrm_service.serviceid in (". generateQuestionMarks($product_ids) .") and aicrm_currency_info.id = ?";
		} else {
			$query = "SELECT aicrm_currency_info.id, aicrm_currency_info.conversion_rate, " .
					"aicrm_products.productid, aicrm_products.unit_price, " .
					"aicrm_productcurrencyrel.actual_price " .
					"FROM (aicrm_currency_info, aicrm_products) " .
					"left join aicrm_productcurrencyrel on aicrm_products.productid = aicrm_productcurrencyrel.productid " .
					"and aicrm_currency_info.id = aicrm_productcurrencyrel.currencyid " .
					"where aicrm_products.productid in (". generateQuestionMarks($product_ids) .") and aicrm_currency_info.id = ?";			
		}
		$params = array($product_ids, $currencyid);
		$result = $adb->pquery($query, $params);
		
		for($i=0;$i<$adb->num_rows($result);$i++)
		{
			$product_id = $adb->query_result($result, $i, 'productid');
			if(getFieldVisibilityPermission($module,$current_user->id,'unit_price') == '0') {
				$actual_price = $adb->query_result($result, $i, 'actual_price');
				
				if ($actual_price == null || $actual_price == '') {
					$unit_price = $adb->query_result($result, $i, 'unit_price');
					$product_conv_rate = $adb->query_result($result, $i, 'conversion_rate');
					$product_base_conv_rate = getBaseConversionRateForProduct($product_id,'edit',$module);
					$conversion_rate = $product_conv_rate * $product_base_conv_rate;
					
					$actual_price = $unit_price * $conversion_rate;
				}
				$price_list[$product_id] = $actual_price;
			} else {
				$price_list[$product_id] = '';
			}
		}
	}
	return $price_list;
}

/**	Function used to get the currency used for the given Price book
 *	@param int $pricebook_id - pricebook id for which we want to get the id of the currency used
 *  @return int $currencyid - id of the currency used for the given pricebook
 */
function getPriceBookCurrency($pricebook_id) {
	global $adb;
	$result = $adb->pquery("select currency_id from aicrm_pricebook where pricebookid=?", array($pricebook_id));
	$currency_id = $adb->query_result($result,0,'currency_id');
	return $currency_id;
}



function saveInventoryProductDetailsOrder($focus, $module, $update_prod_stock='false', $updateDemand='')
{	
	/*echo "<pre>"; print_r($focus); echo "</pre>";
	echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;*/
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryProductDetails($module).");
	//Added to get the convertid
	if(isset($_REQUEST['convert_from']) && $_REQUEST['convert_from'] !='')
	{
		$id=$_REQUEST['return_id'];
	}
	else if(isset($_REQUEST['duplicate_from']) && $_REQUEST['duplicate_from'] !='')
	{
		$id=$_REQUEST['duplicate_from'];
	}
	else if(isset($_REQUEST['revise_from']) && $_REQUEST['revise_from'] !='')
	{
		$id=$_REQUEST['revise_from'];
	}

	$ext_prod_arr = Array();
	if($focus->mode == 'edit')
	{
		if($_REQUEST['taxtype'] == 'group')
			$all_available_taxes = getAllTaxes('available','','edit',$id);
			$return_old_values = '';
		deleteInventoryProductDetailsOrder($focus);
	}
	else
	{
	if($_REQUEST['taxtype'] == 'group')
		$all_available_taxes = getAllTaxes('available','','edit',$id);
	}	
	$tot_no_prod = $_REQUEST['totalProductCount'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;
	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;
		
	    $prod_id = @$_REQUEST['hdnProductId'.$i];
	    $productname = @$_REQUEST['productName'.$i];
	    $producttype = '';
	    if($i==1 || $i==2){
	    	$producttype = @$_REQUEST['producttype'.$i];	
	    }
	    $km = @$_REQUEST['km'];
	    $zone = @$_REQUEST['zone'];
	    $carsize = @$_REQUEST['carsize'];
	    $unit = @$_REQUEST['unit'.$i];
	    $number = @$_REQUEST['number'.$i];
	    $priceperunit = @$_REQUEST['priceperunit'.$i];
	    $amount = @$_REQUEST['amount'.$i];
	    $min = @$_REQUEST['min'];
	    $dlv_c = @$_REQUEST['dlv_c'];
	    $dlv_cvat = @$_REQUEST['dlv_cvat'];
	    $dlv_pvat = @$_REQUEST['dlv_pvat'];
	    $lp = @$_REQUEST['lp'];
	    $discount = @$_REQUEST['discount'];
	    $c_cost = @$_REQUEST['c_cost'];
	    $afterdiscount = @$_REQUEST['afterdiscount'.$i];
	    $purchaseamount = @$_REQUEST['purchaseamount'.$i];

	    $subtotal1 = @$_REQUEST['subtotal1'];
	    $vat1 = @$_REQUEST['vat1'];
	    $total1 = @$_REQUEST['total1'];
	    $subtotal2 = @$_REQUEST['subtotal2'];
	    $vat2 = @$_REQUEST['vat2'];
	    $total2 = @$_REQUEST['total2'];
	    
		$query ="insert into aicrm_inventoryproductrelorder(id,productid,sequence_no,productname,producttype,km,zone,carsize,unit,number,priceperunit,amount,min,dlv_c,dlv_cvat,dlv_pvat,lp,discount,c_cost,afterdiscount,purchaseamount,subtotal1,vat1,total1,subtotal2,vat2,total2) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$qparams = array($focus->id,$prod_id,$prod_seq,$productname,$producttype,$km,$zone,$carsize,$unit,$number,$priceperunit,$amount,$min,$dlv_c,$dlv_cvat,$dlv_pvat,$lp,$discount,$c_cost,$afterdiscount,$purchaseamount,$subtotal1,$vat1,$total1,$subtotal2,$vat2,$total2);
		//cho $query;
		//echo "<pre>";print_r($qparams);echo "</pre>";exit;
		$adb->pquery($query,$qparams);

		$prod_seq++;
	}
	
	//we should update the netprice (subtotal), taxtype, group discount, S&H charge, S&H taxes, adjustment and total
	$updatequery  = " update $focus->table_name set ";
	$updateparams = array();
	$updatequery .= " taxtype=?,";
	array_push($updateparams, $_REQUEST['taxtype']);

	$updatequery .= " pricetype=?,";
	array_push($updateparams, $_REQUEST['pricetype']);

	$updatequery .= " currency_id=?,";
	array_push($updateparams, $_REQUEST['inventory_currency']);
	
	$updatequery .= " inventory_currency=?,";
	array_push($updateparams, $_REQUEST['inventory_currency']);

	$updatequery .= " subtotal1=?,";
	array_push($updateparams, $_REQUEST['subtotal1']);
	$updatequery .= " vat1=?,";
	array_push($updateparams, $_REQUEST['vat1']);
	$updatequery .= " total1=?,";
	array_push($updateparams, $_REQUEST['total1']);
	$updatequery .= " subtotal2=?,";
	array_push($updateparams, $_REQUEST['subtotal2']);
	$updatequery .= " vat2=?,";
	array_push($updateparams, $_REQUEST['vat2']);
	$updatequery .= " total2=?,";
	array_push($updateparams, $_REQUEST['total2']);
	//for discount percentage or discount amount
	
	include_once 'library/general.php';
	$text_currency_th = num2thai(str_replace(",","",number_format($_REQUEST['total1'],2)));
	$text_currency_en = bahtEng(str_replace(",","",number_format($_REQUEST['total1'],2)));

	$text_currency_th = str_replace("บาท","ดอลลาร์ ",str_replace("สตางค์","เซนต์",$text_currency_th));
	$text_currency_en = str_replace("Baht","Dollars",str_replace("Satang","Cents",$text_currency_en));
	
	$updatequery .= " text_currency_th=?,";
	array_push($updateparams, $text_currency_th);
	$updatequery .= " text_currency_en=? ";
	array_push($updateparams, $text_currency_en);
	
	//Added where condition to which entity we want to update these values
	$updatequery .= " where ".$focus->table_index."=?";
	array_push($updateparams, $focus->id);
	$adb->pquery($updatequery,$updateparams);

	//echo $updatequery;
	//echo "<pre>"; print_r($updateparams); echo "</pre>"; exit;
	$log->debug("Exit from function saveInventoryProductDetails($module).");
}


function deleteInventoryProductDetailsOrder($focus)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function deleteInventoryProductDetailsOrder(".$focus->id.").");
    $adb->pquery("delete from aicrm_inventoryproductrelorder where id=?", array($focus->id));
	$log->debug("Exit from function deleteInventoryProductDetails(".$focus->id.")");
}


function save_promotion_tab($focus, $module, $update_prod_stock='false', $updateDemand=''){
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function save_promotion_tab($module).");
	//Tab	
	$tot_no_prod_tab2 = $_REQUEST['mySetup_Tab1_2_totalPromotionCount'];
	//Tab 1
	$query="delete from aicrm_inventory_campaign_promotion where id='".$focus->id."'";
	$adb->pquery($query,"");
	
	//tab2
	$pro_seq=1;
	for($i=1; $i<=$tot_no_prod_tab2; $i++){
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["mySetup_Tab1_2_deleted".$i] == 1)
			continue;
		$promotionid = $_REQUEST['mySetup_Tab1_2_hdnpromotionid'.$i];

		$query ="insert into aicrm_inventory_campaign_promotion(id, promotionid, sequence_no)
		values('".$focus->id."','".$promotionid."','".$pro_seq."')";
		$adb->pquery($query,"");
		$pro_seq++;

		//$query_pro="update aicrm_promotion set campaignid='".$focus->id."' where promotionid='".$promotionid."'";
		//$adb->pquery($query_pro,"");
	}
	
	$log->debug("Exit from function save_promotion_tab($module).");
}

function deleteInventoryGoodsreceive($focus)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function deleteInventoryGoodsreceive(".$focus->id.").");
    $adb->pquery("delete from aicrm_inventorygoodsreceive where id=?", array($focus->id));
	$log->debug("Exit from function deleteInventoryGoodsreceive(".$focus->id.")");
}

function saveInventoryGoodsreceive($focus, $module, $update_prod_stock='false', $updateDemand='')
{	
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryGoodsreceive($module).");
	//Added to get the convertid
	if(isset($_REQUEST['convert_from']) && $_REQUEST['convert_from'] !='')
	{
		$id=$_REQUEST['return_id'];
	}
	else if(isset($_REQUEST['duplicate_from']) && $_REQUEST['duplicate_from'] !='')
	{
		$id=$_REQUEST['duplicate_from'];
	}
	else if(isset($_REQUEST['revise_from']) && $_REQUEST['revise_from'] !='')
	{
		$id=$_REQUEST['revise_from'];
	}

	if($focus->mode == 'edit')
	{
		deleteInventoryGoodsreceive($focus);
	}

	$tot_no_prod = $_REQUEST['totalProductCount'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;

	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;

	    $purchasesorderid = $_REQUEST['hdnPoId'.$i];
		$purchasesorder_no = $_REQUEST['purchasesorder_no'.$i];
		$purchasesorder_name = $_REQUEST['purchasesorder_name'.$i];
		$sequence_no = $prod_seq;
		$lineitemtype = $_REQUEST['lineItemType'.$i];
		$comment = $_REQUEST['comment'.$i];
		$purchase_order_date = $_REQUEST['purchase_order_date'.$i];
		$projectsid = $_REQUEST['projectsid'.$i];
		$projects_name = $_REQUEST['projects_name'.$i];
		$assignto = $_REQUEST['assignto'.$i];
		$smownerid = $_REQUEST['smownerid'.$i];
		$product_code_crm = $_REQUEST['product_code_crm'.$i];
		$productid = $_REQUEST['productid'.$i];
		$productname = $_REQUEST['productname'.$i];
		$po_detail_no = $_REQUEST['po_detail_no'.$i];
		$po_quantity = $_REQUEST['po_quantity'.$i];
		$gr_percentage = $_REQUEST['gr_percentage'.$i];
		$item_status = $_REQUEST['item_status'.$i];
		$gr_qty_percent = $_REQUEST['gr_qty_percent'.$i];
		$gr_quantity = $_REQUEST['gr_quantity'.$i];
		$defects_quantity = $_REQUEST['defects_quantity'.$i];
		$remain_quantity = $_REQUEST['remain_quantity'.$i];
		$unit_price = $_REQUEST['unit_price'.$i];
		$amount = $_REQUEST['amount'.$i];
		$total_defects_quantity = $_REQUEST['total_defects_quantity'.$i];
		$total_gr_quantity = $_REQUEST['total_gr_quantity'.$i];
		$total_amount = $_REQUEST['total_amount'.$i];
		$defects_remark = $_REQUEST['defects_remark'.$i];


		$select_po = "SELECT * FROM aicrm_inventorypurchasesorderrel WHERE id=? AND sequence_no=?";
		$result_select_po = $adb->pquery($select_po, array($purchasesorderid,$po_detail_no));

		$gr_qty_percent_po = $adb->query_result($result_select_po,0,'gr_qty_percent');
		$gr_quantity_po = $adb->query_result($result_select_po,0,'gr_quantity');
		$defect_quantity_po = $adb->query_result($result_select_po,0,'defects_quantity');
		$remain_quantity_po = $adb->query_result($result_select_po,0,'remain_quantity');
		$item_status_po = $adb->query_result($result_select_po,0,'item_status');

		// Insert log Update PO
		$sql_insert_log = "insert into tbt_log_update_po (goodsreceiveid,purchasesorderid,sequence_no,gr_qty_percent_po,gr_qty_percent_gr,gr_quantity_po,gr_quantity_gr,defect_quantity_po,defect_quantity_gr,remain_quantity_po,remain_quantity_gr,item_status_po,item_status_gr,date_update,action) values ('".$focus->id."','".$purchasesorderid."','".$po_detail_no."','".$gr_qty_percent_po."','".$gr_qty_percent."','".$gr_quantity_po."','".$total_gr_quantity."','".$defect_quantity_po."','".$total_defects_quantity."','".$remain_quantity_po."','".$remain_quantity."','".$item_status_po."','".$item_status."','".date('Y-m-d H:i:s')."','".$focus->mode."')";
		$adb->pquery($sql_insert_log,"");

		$gr_qty_percent = (int)$gr_qty_percent;
		$gr_percentage = (int)str_replace("%", "", $gr_percentage);

		if ($gr_qty_percent >= $gr_percentage) {
			$item_status = "Completed";
		}else{
			$item_status = "Intransit";
		}

		// Update Item PO
		$update_item_po = "UPDATE aicrm_inventorypurchasesorderrel SET gr_qty_percent='".$gr_qty_percent."',gr_quantity='".$total_gr_quantity."',defects_quantity='".$total_defects_quantity."',remain_quantity='".$remain_quantity."',item_status='".$item_status."' WHERE id='".$purchasesorderid."' AND sequence_no='".$po_detail_no."' ";
		$adb->pquery($update_item_po,"");

		//เมื่อ GR มีการเลือกสินค้า P/O ใน Item Detail ของ GR >> ใน P/O จะ Auto ปรับ Status จาก Open --> Intransit
		
		$check_status_po_query = $adb->pquery("SELECT id from aicrm_inventorypurchasesorderrel WHERE id=? AND item_status!=?",array($purchasesorderid,"Completed")); 
		if($adb->num_rows($check_status_po_query)>0){
			$update_status_po = "UPDATE aicrm_purchasesorder SET po_status='Intransit' WHERE purchasesorderid = '".$purchasesorderid."'";
			$adb->pquery($update_status_po,"");
		}else{
			$update_status_po = "UPDATE aicrm_purchasesorder SET po_status='Completed' WHERE purchasesorderid = '".$purchasesorderid."'";
			$adb->pquery($update_status_po,"");
		}

		
		
		$query ="insert into aicrm_inventorygoodsreceive(
			id,purchasesorderid,purchasesorder_no,purchasesorder_name,sequence_no,lineitemtype,comment,purchase_order_date,projectsid,projects_name,smownerid,product_code_crm,productid,productname,po_detail_no,po_quantity,gr_percentage,item_status,gr_qty_percent,gr_quantity,defects_quantity,remain_quantity,unit_price,amount,total_defects_quantity,total_gr_quantity,total_amount,defects_remark,assignto) 
		values('".$focus->id."','".$purchasesorderid."','".$purchasesorder_no."','".$purchasesorder_name."','".$sequence_no."','".$lineitemtype."','".$comment."','".$purchase_order_date."','".$projectsid."','".$projects_name."','".$smownerid."','".$product_code_crm."','".$productid."','".$productname."','".$po_detail_no."','".$po_quantity."','".$gr_percentage."','".$item_status."','".$gr_qty_percent."','".$gr_quantity."','".$defects_quantity."','".$remain_quantity."','".$unit_price."','".$amount."','".$total_defects_quantity."','".$total_gr_quantity."','".$total_amount."','".$defects_remark."','".$assignto."')";
		$adb->pquery($query,"");
		
		$prod_seq++;
	}
		
	$log->debug("Exit from function saveInventoryGoodsreceive($module).");
}

function deleteInventoryPricelist($focus)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function deleteInventoryPricelist(".$focus->id.").");
    $adb->pquery("delete from aicrm_inventorypricelist where id=?", array($focus->id));
	$log->debug("Exit from function deleteInventoryPricelist(".$focus->id.")");
}

function saveInventoryProductPricelist($focus, $module, $update_prod_stock='false', $updateDemand='')
{	
	global $log, $adb;
	$id=$focus->id;
	$log->debug("Entering into function saveInventoryProductPricelist($module).");
	//Added to get the convertid
	if(isset($_REQUEST['convert_from']) && $_REQUEST['convert_from'] !='')
	{
		$id=$_REQUEST['return_id'];
	}
	else if(isset($_REQUEST['duplicate_from']) && $_REQUEST['duplicate_from'] !='')
	{
		$id=$_REQUEST['duplicate_from'];
	}
	else if(isset($_REQUEST['revise_from']) && $_REQUEST['revise_from'] !='')
	{
		$id=$_REQUEST['revise_from'];
	}

	if($focus->mode == 'edit')
	{
		deleteInventoryPricelist($focus);
	}

	$tot_no_prod = $_REQUEST['totalProductCount'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;

	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;

	    $prod_id = $_REQUEST['hdnProductId'.$i];
		$lineItemType = $_REQUEST['lineItemType'.$i];
		$comment = $_REQUEST['comment'.$i];

		$product_brand = $_REQUEST['product_brand'.$i];
		$product_weight_per_box = $_REQUEST['product_weight_per_box'.$i];
		$productstatus = $_REQUEST['productstatus'.$i];
		$pricelist_showroom = $_REQUEST['pricelist_showroom'.$i];
		$listprice_project = $_REQUEST['listprice_project'.$i];
		$pricelist_nomal = $_REQUEST['pricelist_nomal'.$i];

		$pricelist_first_tier = $_REQUEST['pricelist_first_tier'.$i];
		$pricelist_second_tier = $_REQUEST['pricelist_second_tier'.$i];
		$pricelist_third_tier = $_REQUEST['pricelist_third_tier'.$i];

		$selling_price = $_REQUEST['selling_price'.$i];
		$selling_price_inc = $_REQUEST['selling_price_inc'.$i];
		
		$query ="insert into aicrm_inventorypricelist(id, productid, sequence_no, comment,product_brand,product_weight_per_box,productstatus,pricelist_showroom,listprice_project,pricelist_nomal,pricelist_first_tier,pricelist_second_tier,pricelist_third_tier,selling_price,selling_price_inc) 
		values('".$focus->id."','".$prod_id."','".$prod_seq."','".$comment."','".$product_brand."','".$product_weight_per_box."','".$productstatus."','".$pricelist_showroom."','".$listprice_project."','".$pricelist_nomal."','".$pricelist_first_tier."','".$pricelist_second_tier."','".$pricelist_third_tier."','".$selling_price."','".$selling_price_inc."')";
		$adb->pquery($query,"");
		
		$prod_seq++;
	}
		
	$log->debug("Exit from function saveInventoryProductPricelist($module).");
}



function deleteInventoryProjects($focus)
{
	global $log, $adb,$updateInventoryProductRel_update_product_array;
	$log->debug("Entering into function deleteInventoryProjects(".$focus->id.").");
    
    $adb->pquery("delete from aicrm_inventoryprojects where id=?", array($focus->id));
    $adb->pquery("delete from aicrm_inventorycompetitorproduct where id=?", array($focus->id));

	$log->debug("Exit from function deleteInventoryProjects(".$focus->id.")");
}

function saveInventoryProductDetails_Projects($focus, $module, $update_prod_stock='false', $updateDemand='')
{	
	global $log, $adb;
	$id=$focus->id;
	/*echo "<pre>"; print_r($focus); echo "</pre>";
	echo "<pre>"; print_r($_REQUEST); echo "</pre>";
	exit;*/
	$log->debug("Entering into function saveInventoryProductDetails_Projects($module).");
	//Added to get the convertid
	if(isset($_REQUEST['convert_from']) && $_REQUEST['convert_from'] !='')
	{
		$id=$_REQUEST['return_id'];
	}
	else if(isset($_REQUEST['duplicate_from']) && $_REQUEST['duplicate_from'] !='')
	{
		$id=$_REQUEST['duplicate_from'];
	}
	else if(isset($_REQUEST['revise_from']) && $_REQUEST['revise_from'] !='')
	{
		$id=$_REQUEST['revise_from'];
	}

	if($focus->mode == 'edit')
	{
		deleteInventoryProjects($focus);
	}

	$tot_no_prod = $_REQUEST['totalProductCount'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seq=1;

	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;

	    $prod_id = $_REQUEST['hdnProductId'.$i];
		$lineItemType = $_REQUEST['lineItemType'.$i];
		$comment = $_REQUEST['comment'.$i];

		$product_brand = $_REQUEST['product_brand'.$i];
		$product_group = $_REQUEST['product_group'.$i];
		$first_delivered_date = '';
		$accountid = $_REQUEST['accountid'.$i];
		if (isset($_REQUEST['first_delivered_date'.$i]) && $_REQUEST['first_delivered_date'.$i] != '') {
			$first_delivered_date = date("Y-m-d", strtotime($_REQUEST['first_delivered_date'.$i]));
		}
		$last_delivered_date = '';
		if (isset($_REQUEST['last_delivered_date'.$i]) && $_REQUEST['last_delivered_date'.$i] != '') {
			$last_delivered_date = date("Y-m-d", strtotime($_REQUEST['last_delivered_date'.$i]));
		}
		$plan = $_REQUEST['plan'.$i];
		$estimated = $_REQUEST['estimated'.$i];
		$delivered = $_REQUEST['delivered'.$i];
		$remain_on_hand = $_REQUEST['remain_on_hand'.$i];
		$listPrice = $_REQUEST['listPrice'.$i];

		$query ="insert into aicrm_inventoryprojects(id, productid, sequence_no, comment,product_brand ,product_group ,accountid ,first_delivered_date ,last_delivered_date ,plan,estimated,delivered,remain_on_hand,listprice) 
		values('".$focus->id."','".$prod_id."','".$prod_seq."','".$comment."','".$product_brand."','".$product_group."','".$accountid."','".$first_delivered_date."','".$last_delivered_date."','".$plan."','".$estimated."','".$delivered."','".$remain_on_hand."','".$listPrice."')";
		$adb->pquery($query,"");
		$prod_seq++;
	}

	//Competitor Product Inomation
	$tot_no_prodcom = $_REQUEST['totalCompetitorProduct'];
	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	$prod_seqcom=1;

	for($i=1; $i<=$tot_no_prodcom; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deletedCom".$i] == 1)
			continue;

	    $cprod_id = $_REQUEST['hdnCompetitorProductId'.$i];
		$lineItemType = $_REQUEST['lineItem'.$i];
		$competitorcomment = $_REQUEST['competitorcomment'.$i];

		$competitor_brand = $_REQUEST['competitor_brand'.$i];
		$comprtitor_product_group = $_REQUEST['comprtitor_product_group'.$i];
		$comprtitor_product_size = $_REQUEST['comprtitor_product_size'.$i];
		$comprtitor_product_thickness = $_REQUEST['comprtitor_product_thickness'.$i];
		$comprtitor_estimated_unit = $_REQUEST['comprtitor_estimated_unit'.$i];
		$competitor_price = $_REQUEST['competitor_price'.$i];

		$query_com ="insert into aicrm_inventorycompetitorproduct(id, competitorproductid, sequence_no, competitorcomment,competitor_brand ,comprtitor_product_group ,comprtitor_product_size ,comprtitor_product_thickness ,comprtitor_estimated_unit ,competitor_price) 
		values('".$focus->id."','".$cprod_id."','".$prod_seqcom."','".$competitorcomment."','".$competitor_brand."','".$comprtitor_product_group."','".$comprtitor_product_size."','".$comprtitor_product_thickness."','".$comprtitor_estimated_unit."','".$competitor_price."')";
		$adb->pquery($query_com,"");
		$prod_seqcom++;
	}
		
	$log->debug("Exit from function saveInventoryProductDetails_Projects($module).");
}

function getCompetitor_Brand() {
	global $adb, $log;
	
	$sql = "
	SELECT 
	'' as 'competitor_brand_id',
	'-- none --' as 'competitor_brand_name'
	
	UNION
	
	SELECT 
	'1' as 'competitor_brand_id',
	'แบรนด์คู่แข่ง 1' as 'competitor_brand_name'
	
	UNION
	
	SELECT 
	'2' as 'competitor_brand_id',
	'แบรนด์คู่แข่ง 2' as 'competitor_brand_name'
	
	UNION
	
	SELECT 
	'3' as 'competitor_brand_id',
	'แบรนด์คู่แข่ง 3' as 'competitor_brand_name'
	
	";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['competitor_brand_id'] = $adb->query_result($res,$i,'competitor_brand_id');
		$data_details[$i]['competitor_brand_name'] = $adb->query_result($res,$i,'competitor_brand_name');
	}
	
	return $data_details;
	
}

function getCompet_Brand_in_proj() {
	global $adb, $log;
	
	$sql = "
	SELECT 
	'' as 'compet_brand_in_proj_id',
	'-- none --' as 'compet_brand_in_proj_name'
	
	UNION
	
	SELECT 
	'1' as 'compet_brand_in_proj_id',
	'แบรนด์คู่แข่งในโครงการ 1' as 'compet_brand_in_proj_name'
	
	UNION
	
	SELECT 
	'2' as 'compet_brand_in_proj_id',
	'แบรนด์คู่แข่งในโครงการ 2' as 'compet_brand_in_proj_name'
	
	UNION
	
	SELECT 
	'3' as 'compet_brand_in_proj_id',
	'แบรนด์คู่แข่งในโครงการ 3' as 'compet_brand_in_proj_name'
	
	";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['compet_brand_in_proj_id'] = $adb->query_result($res,$i,'compet_brand_in_proj_id');
		$data_details[$i]['compet_brand_in_proj_name'] = $adb->query_result($res,$i,'compet_brand_in_proj_name');
	}
	
	return $data_details;
	
}

function getGR_Percentage() {
	global $adb, $log;
	
	$sql = "
	
	SELECT 
	'1' as 'gr_percentage_id',
	'90%' as 'gr_percentage_name'
	
	UNION
	
	SELECT 
	'2' as 'gr_percentage_id',
	'100%' as 'gr_percentage_name'

	";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['gr_percentage_id'] = $adb->query_result($res,$i,'gr_percentage_id');
		$data_details[$i]['gr_percentage_name'] = $adb->query_result($res,$i,'gr_percentage_name');
	}
	
	return $data_details;
	
}

function get_po_price_type() {
	global $adb, $log;
	
	$sql = "
	SELECT 
	'' as 'po_price_type_id',
	'-- none --' as 'po_price_type_name'
	
	UNION
	
	SELECT 
	'1' as 'po_price_type_id',
	'Standard' as 'po_price_type_name'
	
	UNION
	
	SELECT 
	'2' as 'po_price_type_id',
	'Special' as 'po_price_type_name'

	";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['po_price_type_id'] = $adb->query_result($res,$i,'po_price_type_id');
		$data_details[$i]['po_price_type_name'] = $adb->query_result($res,$i,'po_price_type_name');
	}
	
	return $data_details;
	
}

function get_sr_finish() {
	global $adb, $log;
	
	$sql = "
	SELECT 
	'' AS sr_finish_id, '--None--' AS sr_finish_name  UNION SELECT 
	'1' AS sr_finish_id, 'Standard' AS sr_finish_name  UNION SELECT
	'2' AS sr_finish_id, 'BK-(sanded 2 sides)-Backer' AS sr_finish_name  UNION SELECT 
	'3' AS sr_finish_id, 'GL-Aligator-GAA' AS sr_finish_name  UNION SELECT 
	'4' AS sr_finish_id, 'GL-Anti Finger print-AFX' AS sr_finish_name  UNION SELECT 
	'5' AS sr_finish_id, 'GL-Aria-WAR' AS sr_finish_name  UNION SELECT 
	'6' AS sr_finish_id, 'GL-Ash-GSH' AS sr_finish_name  UNION SELECT 
	'7' AS sr_finish_id, 'GL-Blazing Delight-SLA' AS sr_finish_name  UNION SELECT 
	'8' AS sr_finish_id, 'GL-Burnished Wood-WBR' AS sr_finish_name  UNION SELECT 
	'9' AS sr_finish_id, 'GL-Cadiz-CDZ,PCD' AS sr_finish_name  UNION SELECT 
	'10' AS sr_finish_id, 'GL-Caravan Leather-GFC' AS sr_finish_name  UNION SELECT 
	'11' AS sr_finish_id, 'GL-Coast Line-CTL' AS sr_finish_name  UNION SELECT 
	'12' AS sr_finish_id, 'GL-Dazzle-GDZ' AS sr_finish_name  UNION SELECT 
	'13' AS sr_finish_id, 'GL-Embossed Fleur-EFL' AS sr_finish_name  UNION SELECT 
	'14' AS sr_finish_id, 'GL-Embossed Interweave-EW' AS sr_finish_name  UNION SELECT 
	'15' AS sr_finish_id, 'GL-Fawn-SFN' AS sr_finish_name  UNION SELECT 
	'16' AS sr_finish_id, 'GL-Gloss-GPG,GPL,MG,PCA,SGA,SGB,WGA' AS sr_finish_name  UNION SELECT 
	'17' AS sr_finish_id, 'GL-Handscraped-WQA' AS sr_finish_name  UNION SELECT 
	'18' AS sr_finish_id, 'GL-High Definition Gloss-HDG' AS sr_finish_name  UNION SELECT 
	'19' AS sr_finish_id, 'GL-High Gloss-GP,PCB' AS sr_finish_name  UNION SELECT 
	'20' AS sr_finish_id, 'GL-Jupiter-PJP,SJP' AS sr_finish_name  UNION SELECT 
	'21' AS sr_finish_id, 'GL-Leather-GSL' AS sr_finish_name  UNION SELECT 
	'22' AS sr_finish_id, 'GL-Matt-GPM,GSM' AS sr_finish_name  UNION SELECT 
	'23' AS sr_finish_id, 'GL-Microlie v-GSI' AS sr_finish_name  UNION SELECT 
	'24' AS sr_finish_id, 'GL-Microline V-GFI' AS sr_finish_name  UNION SELECT 
	'25' AS sr_finish_id, 'GL-microlines v-GWI' AS sr_finish_name  UNION SELECT 
	'26' AS sr_finish_id, 'GL-Onda Horizontal-GYA' AS sr_finish_name  UNION SELECT 
	'27' AS sr_finish_id, 'GL-onda V-GPN,GSN' AS sr_finish_name  UNION SELECT 
	'28' AS sr_finish_id, 'GL-Pacific Trail-PTR,SPP,WPP' AS sr_finish_name  UNION SELECT 
	'29' AS sr_finish_id, 'GL-Parallel streaks-WPA' AS sr_finish_name  UNION SELECT 
	'30' AS sr_finish_id, 'GL-Quarter Cut-GWK' AS sr_finish_name  UNION SELECT 
	'31' AS sr_finish_id, 'GL-Rafia-SRF,WRF' AS sr_finish_name  UNION SELECT 
	'32' AS sr_finish_id, 'GL-Raw silk-GSR,GWR' AS sr_finish_name  UNION SELECT 
	'33' AS sr_finish_id, 'GL-Raw Silk-GPR' AS sr_finish_name  UNION SELECT 
	'34' AS sr_finish_id, 'GL-Retro-SRT' AS sr_finish_name  UNION SELECT 
	'35' AS sr_finish_id, 'GL-Santhia-WSN' AS sr_finish_name  UNION SELECT 
	'36' AS sr_finish_id, 'GL-Satin-SAT' AS sr_finish_name  UNION SELECT 
	'37' AS sr_finish_id, 'GL-Scuff-Resistant Gloss-SR' AS sr_finish_name  UNION SELECT 
	'38' AS sr_finish_id, 'GL-Sierra-SIR' AS sr_finish_name  UNION SELECT 
	'39' AS sr_finish_id, 'GL-Soft Touch-GSS,' AS sr_finish_name  UNION SELECT 
	'40' AS sr_finish_id, 'GL-Soft Touch/Satin-PAT' AS sr_finish_name  UNION SELECT 
	'41' AS sr_finish_id, 'GL-Sparkle-SPR' AS sr_finish_name  UNION SELECT 
	'42' AS sr_finish_id, 'GL-Stone-GFO' AS sr_finish_name  UNION SELECT 
	'43' AS sr_finish_id, 'GL-Suede-GSA-D,GWA-E,GFA,GFS,GPA-C,PCS,WGE' AS sr_finish_name  UNION SELECT 
	'44' AS sr_finish_id, 'GL-Summer Bloom-SUA' AS sr_finish_name  UNION SELECT 
	'45' AS sr_finish_id, 'GL-Super Gloss-HGA, HGP,HGW' AS sr_finish_name  UNION SELECT 
	'46' AS sr_finish_id, 'GL-Super Suede-SSA,WGS' AS sr_finish_name  UNION SELECT 
	'47' AS sr_finish_id, 'GL-Synchro-SY1,SY2' AS sr_finish_name  UNION SELECT 
	'48' AS sr_finish_id, 'GL-Techno steel-GPT,GST' AS sr_finish_name  UNION SELECT 
	'49' AS sr_finish_id, 'GL-Techno Steel-GWT' AS sr_finish_name  UNION SELECT 
	'50' AS sr_finish_id, 'GL-Texmex-GFT,GTM' AS sr_finish_name  UNION SELECT 
	'51' AS sr_finish_id, 'GL-Trace-TRC' AS sr_finish_name  UNION SELECT 
	'52' AS sr_finish_id, 'GL-Veracious Bark-WVB,PVB,GCN' AS sr_finish_name  UNION SELECT 
	'53' AS sr_finish_id, 'GL-Vertical Line-GPV' AS sr_finish_name  UNION SELECT 
	'54' AS sr_finish_id, 'GL-Vertical Lines-GSV' AS sr_finish_name  UNION SELECT 
	'55' AS sr_finish_id, 'GL-Wackey Wicker-SWA' AS sr_finish_name  UNION SELECT 
	'56' AS sr_finish_id, 'GL-Zero Reflection-GWM' AS sr_finish_name  UNION SELECT 
	'57' AS sr_finish_id, 'GL-M-Matt-GCM' AS sr_finish_name  UNION SELECT 
	'58' AS sr_finish_id, 'GL-M-Metalics-GMA,GMC' AS sr_finish_name  UNION SELECT 
	'59' AS sr_finish_id, 'GL-M-MIrror-GM' AS sr_finish_name  UNION SELECT 
	'60' AS sr_finish_id, 'GL-M-Stone-GEO,GGO,GKO,GZO' AS sr_finish_name  UNION SELECT 
	'61' AS sr_finish_id, 'GL-M-Vertical-GEV' AS sr_finish_name  UNION SELECT 
	'62' AS sr_finish_id, 'GL-M-Zero Reflection-GEM,GGM,GKM,GZM' AS sr_finish_name  UNION SELECT 
	'63' AS sr_finish_id, 'NM-Buff Leather-BFL' AS sr_finish_name  UNION SELECT 
	'64' AS sr_finish_id, 'NM-Chiseled Wood-CHW' AS sr_finish_name  UNION SELECT 
	'65' AS sr_finish_id, 'NM-Classic Quilted-QLT' AS sr_finish_name  UNION SELECT 
	'66' AS sr_finish_id, 'NM-Cleaved Stone-CST' AS sr_finish_name  UNION SELECT 
	'67' AS sr_finish_id, 'NM-Cosmic Connection-COC' AS sr_finish_name  UNION SELECT 
	'68' AS sr_finish_id, 'NM-Disco-DSC' AS sr_finish_name  UNION SELECT 
	'69' AS sr_finish_id, 'NM-Engraved-ENG' AS sr_finish_name  UNION SELECT 
	'70' AS sr_finish_id, 'NM-Essentia-NPN' AS sr_finish_name  UNION SELECT 
	'71' AS sr_finish_id, 'NM-Extra Matt-XMT' AS sr_finish_name  UNION SELECT 
	'72' AS sr_finish_id, 'NM-Glazed-GLZ,PWD' AS sr_finish_name  UNION SELECT 
	'73' AS sr_finish_id, 'NM-Gloss-EGM' AS sr_finish_name  UNION SELECT 
	'74' AS sr_finish_id, 'NM-Khadi-KHD,TEX' AS sr_finish_name  UNION SELECT 
	'75' AS sr_finish_id, 'NM-Magical Flow-MF' AS sr_finish_name  UNION SELECT 
	'76' AS sr_finish_id, 'NM-Metal Spell-MTS' AS sr_finish_name  UNION SELECT 
	'77' AS sr_finish_id, 'NM-Novel Gloss-NGL' AS sr_finish_name  UNION SELECT 
	'78' AS sr_finish_id, 'NM-Painted Wood-NPW' AS sr_finish_name  UNION SELECT 
	'79' AS sr_finish_id, 'NM-Pure Grain-PGR' AS sr_finish_name  UNION SELECT 
	'80' AS sr_finish_id, 'NM-Quadro-QUD' AS sr_finish_name  UNION SELECT 
	'81' AS sr_finish_id, 'NM-Raw Bark-RBK' AS sr_finish_name  UNION SELECT 
	'82' AS sr_finish_id, 'NM-Simpatico-SMP' AS sr_finish_name  UNION SELECT 
	'83' AS sr_finish_id, 'NM-M-Soft Buff-SBF' AS sr_finish_name  UNION SELECT 
	'84' AS sr_finish_id, 'NM-Suede-NSB,NPA-B,NWA-B,NCC,NCG' AS sr_finish_name  UNION SELECT 
	'85' AS sr_finish_id, 'NM-Super Gloss-NCG' AS sr_finish_name  UNION SELECT 
	'86' AS sr_finish_id, 'NM-Torrent-TRN' AS sr_finish_name  UNION SELECT 
	'87' AS sr_finish_id, 'NM-Voodo-VOD' AS sr_finish_name  UNION SELECT 
	'88' AS sr_finish_id, 'SP-Crimp-CM' AS sr_finish_name  UNION SELECT 
	'89' AS sr_finish_id, 'SP-Cross Bar-CRB' AS sr_finish_name  UNION SELECT 
	'90' AS sr_finish_id, 'SP-Cross Lines-CRL' AS sr_finish_name  UNION SELECT 
	'91' AS sr_finish_id, 'SP-High Streak-HST' AS sr_finish_name  UNION SELECT 
	'92' AS sr_finish_id, 'SP-Illusion-ILS' AS sr_finish_name  UNION SELECT 
	'93' AS sr_finish_id, 'SP-M-Metal Brushed -NM' AS sr_finish_name  UNION SELECT 
	'94' AS sr_finish_id, 'SP-Microlines Vertical-MLV' AS sr_finish_name  UNION SELECT 
	'95' AS sr_finish_id, 'SP-Organic-OG' AS sr_finish_name  UNION SELECT 
	'96' AS sr_finish_id, 'SP-Paragon-PAR' AS sr_finish_name  UNION SELECT 
	'97' AS sr_finish_id, 'SP-Sculpted-SCT' AS sr_finish_name  UNION SELECT 
	'98' AS sr_finish_id, 'SP-Soft Brushed-NM9030S' AS sr_finish_name  UNION SELECT 
	'99' AS sr_finish_id, 'SP-Suede-U,-' AS sr_finish_name  UNION SELECT 
	'100' AS sr_finish_id, 'SP-Vertical Lines-VER' AS sr_finish_name  UNION SELECT 
	'101' AS sr_finish_id, 'SP-Ariz-ARZ' AS sr_finish_name  UNION SELECT 
	'102' AS sr_finish_id, 'SP-Atune-ATN' AS sr_finish_name  UNION SELECT 
	'103' AS sr_finish_id, 'SP-Brisk-BSK' AS sr_finish_name  UNION SELECT 
	'104' AS sr_finish_id, 'SP-Matt-MAT/P' AS sr_finish_name  UNION SELECT 
	'105' AS sr_finish_id, 'NM-Rocka-RKA' AS sr_finish_name  UNION SELECT 
	'106' AS sr_finish_id, 'NM-Roso-RSP' AS sr_finish_name  UNION SELECT 
	'107' AS sr_finish_id, 'NM-Taurus-TRS' AS sr_finish_name

	";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['sr_finish_id'] = $adb->query_result($res,$i,'sr_finish_id');
		$data_details[$i]['sr_finish_name'] = $adb->query_result($res,$i,'sr_finish_name');
	}
	
	return $data_details;
	
}

function get_sr_size_mm() {
	global $adb, $log;
	
	$sql = "
	SELECT 
	'' AS sr_size_mm_id, '--None--' AS sr_size_mm_name  UNION SELECT
	'1' AS sr_size_mm_id, 'Standard' AS sr_size_mm_name UNION SELECT
	'2' AS sr_size_mm_id, 'A5' AS sr_size_mm_name  UNION SELECT 
	'3' AS sr_size_mm_id, 'A4' AS sr_size_mm_name  UNION SELECT 
	'4' AS sr_size_mm_id, 'A3' AS sr_size_mm_name  UNION SELECT 
	'5' AS sr_size_mm_id, '44x67mm' AS sr_size_mm_name  UNION SELECT 
	'6' AS sr_size_mm_id, '64x128mm' AS sr_size_mm_name  UNION SELECT 
	'7' AS sr_size_mm_id, '89x127mm' AS sr_size_mm_name  UNION SELECT 
	'8' AS sr_size_mm_id, '300x300mm' AS sr_size_mm_name  UNION SELECT 
	'9' AS sr_size_mm_id, '300x600mm' AS sr_size_mm_name  UNION SELECT 
	'10' AS sr_size_mm_id, '100x100mm' AS sr_size_mm_name  UNION SELECT 
	'11' AS sr_size_mm_id, '600x1200mm' AS sr_size_mm_name  UNION SELECT 
	'12' AS sr_size_mm_id, '1220x2440mm' AS sr_size_mm_name  UNION SELECT 
	'13' AS sr_size_mm_id, '1300x3050mm' AS sr_size_mm_name  UNION SELECT 
	'14' AS sr_size_mm_id, '1525x3660mm' AS sr_size_mm_name  UNION SELECT 
	'15' AS sr_size_mm_id, '1830x3660mm' AS sr_size_mm_name  UNION SELECT 
	'16' AS sr_size_mm_id, 'Customized' AS sr_size_mm_name 
	
	";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['sr_size_mm_id'] = $adb->query_result($res,$i,'sr_size_mm_id');
		$data_details[$i]['sr_size_mm_name'] = $adb->query_result($res,$i,'sr_size_mm_name');
	}
	
	return $data_details;
	
}

function get_sr_thickness_mm() {
	global $adb, $log;
	
	$sql = "
	SELECT 
	'' AS sr_thickness_mm_id, '--None--' AS sr_thickness_mm_name  UNION SELECT
	'1' AS sr_thickness_mm_id, 'Standard' AS sr_thickness_mm_name  UNION SELECT  
	'2' AS sr_thickness_mm_id, '0.5PF' AS sr_thickness_mm_name  UNION SELECT 
	'3' AS sr_thickness_mm_id, '0.6' AS sr_thickness_mm_name  UNION SELECT 
	'4' AS sr_thickness_mm_id, '0.6PF' AS sr_thickness_mm_name  UNION SELECT 
	'5' AS sr_thickness_mm_id, '0.7' AS sr_thickness_mm_name  UNION SELECT 
	'6' AS sr_thickness_mm_id, '0.8' AS sr_thickness_mm_name  UNION SELECT 
	'7' AS sr_thickness_mm_id, '0.9' AS sr_thickness_mm_name  UNION SELECT 
	'8' AS sr_thickness_mm_id, '1' AS sr_thickness_mm_name  UNION SELECT 
	'9' AS sr_thickness_mm_id, '1.2' AS sr_thickness_mm_name  UNION SELECT 
	'10' AS sr_thickness_mm_id, '1.5' AS sr_thickness_mm_name  UNION SELECT 
	'11' AS sr_thickness_mm_id, '2' AS sr_thickness_mm_name  UNION SELECT 
	'12' AS sr_thickness_mm_id, '3' AS sr_thickness_mm_name  UNION SELECT 
	'13' AS sr_thickness_mm_id, '4' AS sr_thickness_mm_name  UNION SELECT 
	'14' AS sr_thickness_mm_id, '5' AS sr_thickness_mm_name  UNION SELECT 
	'15' AS sr_thickness_mm_id, '6' AS sr_thickness_mm_name  UNION SELECT 
	'16' AS sr_thickness_mm_id, '8' AS sr_thickness_mm_name  UNION SELECT 
	'17' AS sr_thickness_mm_id, '10' AS sr_thickness_mm_name  UNION SELECT 
	'18' AS sr_thickness_mm_id, '12' AS sr_thickness_mm_name  UNION SELECT 
	'19' AS sr_thickness_mm_id, '13' AS sr_thickness_mm_name  UNION SELECT 
	'20' AS sr_thickness_mm_id, '16' AS sr_thickness_mm_name  UNION SELECT 
	'21' AS sr_thickness_mm_id, '18' AS sr_thickness_mm_name  UNION SELECT 
	'22' AS sr_thickness_mm_id, '20' AS sr_thickness_mm_name  UNION SELECT 
	'23' AS sr_thickness_mm_id, '25' AS sr_thickness_mm_name  UNION SELECT 
	'24' AS sr_thickness_mm_id, '30' AS sr_thickness_mm_name 

	";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['sr_thickness_mm_id'] = $adb->query_result($res,$i,'sr_thickness_mm_id');
		$data_details[$i]['sr_thickness_mm_name'] = $adb->query_result($res,$i,'sr_thickness_mm_name');
	}
	
	return $data_details;
	
}

function get_role($userid) {
	global $adb, $log;
	$sql = $adb->pquery('SELECT roleid FROM aicrm_user2role WHERE userid= ?',array($userid));
	$roleid = $adb->query_result($sql,0,'roleid');
	return $roleid;
}

function get_product_price_type() {
	global $adb, $log;

	$roleid = get_role($_SESSION['authenticated_user_id']);
	
	$sql = "SELECT
		product_price_typeid,
		product_price_type AS product_price_type_name 
	FROM
		aicrm_product_price_type
		LEFT JOIN aicrm_role2picklist ON aicrm_product_price_type.picklist_valueid = aicrm_role2picklist.picklistvalueid 
	WHERE
		presence = 1 
		AND product_price_type != '--None--' 
		AND aicrm_role2picklist.roleid = '".$roleid."' 
	ORDER BY
		product_price_type";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['product_price_typeid'] = $adb->query_result($res,$i,'product_price_typeid');
		$data_details[$i]['product_price_type_name'] = $adb->query_result($res,$i,'product_price_type_name');
	}
	
	return $data_details;
	
}

function get_pricelist_type() {
	global $adb, $log;
	
	$sql = "SELECT pricelist_typeid, pricelist_type as pricelist_type_name FROM aicrm_pricelist_type WHERE presence=1 and pricelist_type != '--None--' ORDER BY pricelist_typeid ";
	$res=$adb->pquery($sql, array());
	$noofrows = $adb->num_rows($res);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$data_details[$i]['pricelist_typeid'] = $adb->query_result($res,$i,'pricelist_typeid');
		$data_details[$i]['pricelist_type_name'] = $adb->query_result($res,$i,'pricelist_type_name');
	}
	
	return $data_details;
	
}

function getDiscountCoupon($quoteid) {
	global $adb;
	$result = $adb->pquery("select discount_coupon from aicrm_quotes where quoteid=?", array($quoteid));
	$discount_coupon = $adb->query_result($result,0,'discount_coupon');
	return $discount_coupon;
}

function getBillDiscount($quoteid) {
	global $adb;
	$result = $adb->pquery("select hdn_bill_discount from aicrm_quotes where quoteid=?", array($quoteid));
	$hdn_bill_discount = $adb->query_result($result,0,'hdn_bill_discount');
	return $hdn_bill_discount;
}


?>