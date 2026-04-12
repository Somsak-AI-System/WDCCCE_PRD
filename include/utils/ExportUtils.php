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


/**	function used to get the permitted blocks
 *	@param string $module - module name
 *	@param string $disp_view - view name, this may be create_view, edit_view or detail_view
 *	@return string $blockid_list - list of block ids within the paranthesis with comma seperated
 */
function getPermittedBlocks($module, $disp_view)
{
	global $adb, $log;
	$log->debug("Entering into the function getPermittedBlocks($module, $disp_view)");

        $tabid = getTabid($module);
        $block_detail = Array();
        $query="select blockid,blocklabel,show_title from aicrm_blocks where tabid=? and $disp_view=0 and visible = 0 order by sequence";
        $result = $adb->pquery($query, array($tabid));
        $noofrows = $adb->num_rows($result);
	$blockid_list ='(';
	for($i=0; $i<$noofrows; $i++)
	{
		$blockid = $adb->query_result($result,$i,"blockid");
		if($i != 0)
			$blockid_list .= ', ';
		$blockid_list .= $blockid;
		$block_label[$blockid] = $adb->query_result($result,$i,"blocklabel");
	}
	$blockid_list .= ')';

	$log->debug("Exit from the function getPermittedBlocks($module, $disp_view). Return value = $blockid_list");
	return $blockid_list;
}

/**	function used to get the query which will list the permitted fields
 *	@param string $module - module name
 *	@param string $disp_view - view name, this may be create_view, edit_view or detail_view
 *	@return string $sql - query to get the list of fields which are permitted to the current user
 */
function getPermittedFieldsQuery($module, $disp_view)
{
	global $adb, $log;
	$log->debug("Entering into the function getPermittedFieldsQuery($module, $disp_view)");

	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	//To get the permitted blocks
	$blockid_list = getPermittedBlocks($module, $disp_view);
    $tabid = getTabid($module);
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || $module == "Users")
	{
		if($module == "Goodsreceive" && $_REQUEST['exportline'] == 1){
			
			$sql = "(SELECT aicrm_field.columnname, aicrm_field.fieldlabel, aicrm_field.tablename, aicrm_field.uitype FROM aicrm_field Inner join aicrm_blocks on aicrm_blocks.blockid = aicrm_field.block WHERE aicrm_field.tabid=".$tabid." AND aicrm_field.block IN $blockid_list AND aicrm_field.displaytype IN (1,2,3,4) and aicrm_field.presence in (0,2) ORDER BY aicrm_blocks.sequence , aicrm_field.sequence)

			UNION ALL
			(
			SELECT
				'purchasesorder_no' AS columnname,
				'หมายเลขใบสั่งซื้อ' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'purchase_order_date' AS columnname,
				'Purchase Order Date' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'projects_no' AS columnname,
				'ชื่อโครงการ' AS fieldlabel,
				'aicrm_projects' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'assignto' AS columnname,
				'ผู้รับผิดชอบโครงการ' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_code_crm' AS columnname,
				'รหัสสินค้า' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'productname' AS columnname,
				'ชื่อสินค้า' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_detail_no' AS columnname,
				'ลำดับที่สินค้าใน P/O' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_quantity' AS columnname,
				'จำนวนที่สั่งซื้อใน P/O' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_percentage' AS columnname,
				'GR 90% or 100%' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'item_status' AS columnname,
				'สถานะรายการที่สั่งซื้อ' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_qty_percent' AS columnname,
				'GR Qty.%' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'remain_quantity' AS columnname,
				'จำนวนที่เหลือ' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'unit_price' AS columnname,
				'ราคา/หน่วย' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'amount' AS columnname,
				'ราคา' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_gr_quantity' AS columnname,
				'จำนวนสินค้า GR ที่รับเข้าทั้งหมด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_defects_quantity' AS columnname,
				'จำนวนสินค้า Defects ที่รับเข้าทั้งหมด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_amount' AS columnname,
				'ราคารวมทั้งหมด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'defects_remark' AS columnname,
				'หมายเหตุสินค้าชำรุด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			";
		}elseif($module == "Purchasesorder" && $_REQUEST['exportline'] == 1){
			$sql = "(SELECT aicrm_field.columnname, aicrm_field.fieldlabel, aicrm_field.tablename, aicrm_field.uitype FROM aicrm_field Inner join aicrm_blocks on aicrm_blocks.blockid = aicrm_field.block WHERE aicrm_field.tabid=".$tabid." AND aicrm_field.block IN $blockid_list AND aicrm_field.displaytype IN (1,2,3,4) and aicrm_field.presence in (0,2) ORDER BY aicrm_blocks.sequence , aicrm_field.sequence)
			UNION ALL
			(
			SELECT
				'product_name' AS columnname,
				'ชื่อสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_brand' AS columnname,
				'ยี่ห้อสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_group' AS columnname,
				'กลุ่มสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_code_crm' AS columnname,
				'รหัสสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_prefix' AS columnname,
				'Prefix' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_factory_code' AS columnname,
				'รหัสสีสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_design_name' AS columnname,
				'ชื่่อสีสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_finish_name' AS columnname,
				'ชนิดผิว' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_size_ft' AS columnname,
				'ขนาด (ฟุต)' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_thinkness' AS columnname,
				'ความหนา (มม.)' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_grade' AS columnname,
				'เกรดสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_film' AS columnname,
				'Film' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_backprint' AS columnname,
				'Backprint' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_quantity' AS columnname,
				'จำนวนที่สั่งซื้อใน P/O' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_percentage' AS columnname,
				'GR 90% or 100%' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_quantity' AS columnname,
				'จำนวนสินค้า GR ที่รับเข้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'defects_quantity' AS columnname,
				'จำนวนสินค้าชำรุดที่รับเข้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'remain_quantity' AS columnname,
				'จำนวนที่เหลือ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_qty_percent' AS columnname,
				'GR Qty.%' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'item_status' AS columnname,
				'สถานะรายการที่สั่งซื้อ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'projects_name' AS columnname,
				'ชื่อโครงการ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'assignto' AS columnname,
				'ผู้รับผิดชอบโครงการ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_price_type' AS columnname,
				'Price Type' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'price_usd' AS columnname,
				'ราคาซื้อ USD ($)' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'unit_price' AS columnname,
				'ราคา/หน่วย' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_amount' AS columnname,
				'Total Amount' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)

			UNION ALL
			(
			SELECT
				'subtotal' AS columnname,
				'Net Total' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_after_discount' AS columnname,
				'Total After Discount' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'tax1' AS columnname,
				'Vat %' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'tax_final' AS columnname,
				'Vat' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total' AS columnname,
				'Grand Total' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			";
		}else{
			$sql = "SELECT aicrm_field.columnname, aicrm_field.fieldlabel, aicrm_field.tablename, aicrm_field.uitype FROM aicrm_field Inner join aicrm_blocks on aicrm_blocks.blockid = aicrm_field.block WHERE aicrm_field.tabid=".$tabid." AND aicrm_field.block IN $blockid_list AND aicrm_field.displaytype IN (1,2,3,4) and aicrm_field.presence in (0,2) ORDER BY aicrm_blocks.sequence , aicrm_field.sequence";
		}
 		
  	}
  	else
  	{
		$profileList = getCurrentUserProfileList();

		if($module == "Goodsreceive" && $_REQUEST['exportline'] == 1){
			$sql = "(SELECT aicrm_field.columnname, aicrm_field.fieldlabel, aicrm_field.tablename, aicrm_field.uitype FROM aicrm_field INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid=aicrm_field.fieldid INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid=aicrm_field.fieldid Inner join aicrm_blocks on aicrm_blocks.blockid = aicrm_field.block WHERE aicrm_field.tabid=".$tabid." AND aicrm_field.block IN ".$blockid_list." AND aicrm_field.displaytype IN (1,2,4) AND aicrm_profile2field.visible=0 AND aicrm_def_org_field.visible=0 AND aicrm_profile2field.profileid IN (". implode(",", $profileList) .") and aicrm_field.presence in (0,2) GROUP BY aicrm_field.fieldid ORDER BY aicrm_blocks.sequence , aicrm_field.sequence)
			UNION ALL
			(
			SELECT
				'purchasesorder_no' AS columnname,
				'หมายเลขใบสั่งซื้อ' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'purchase_order_date' AS columnname,
				'Purchase Order Date' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'projects_no' AS columnname,
				'ชื่อโครงการ' AS fieldlabel,
				'aicrm_projects' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'assignto' AS columnname,
				'ผู้รับผิดชอบโครงการ' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_code_crm' AS columnname,
				'รหัสสินค้า' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'productname' AS columnname,
				'ชื่อสินค้า' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_detail_no' AS columnname,
				'ลำดับที่สินค้าใน P/O' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_quantity' AS columnname,
				'จำนวนที่สั่งซื้อใน P/O' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_percentage' AS columnname,
				'GR 90% or 100%' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'item_status' AS columnname,
				'สถานะรายการที่สั่งซื้อ' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_qty_percent' AS columnname,
				'GR Qty.%' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'remain_quantity' AS columnname,
				'จำนวนที่เหลือ' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'unit_price' AS columnname,
				'ราคา/หน่วย' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'amount' AS columnname,
				'ราคา' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_gr_quantity' AS columnname,
				'จำนวนสินค้า GR ที่รับเข้าทั้งหมด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_defects_quantity' AS columnname,
				'จำนวนสินค้า Defects ที่รับเข้าทั้งหมด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_amount' AS columnname,
				'ราคารวมทั้งหมด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'defects_remark' AS columnname,
				'หมายเหตุสินค้าชำรุด' AS fieldlabel,
				'aicrm_inventorygoodsreceive' AS tablename,
				'1' AS uitype 
			)
			";
		}elseif($module == "Purchasesorder" && $_REQUEST['exportline'] == 1){
			$sql = "( SELECT aicrm_field.columnname, aicrm_field.fieldlabel, aicrm_field.tablename, aicrm_field.uitype FROM aicrm_field INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid=aicrm_field.fieldid INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid=aicrm_field.fieldid Inner join aicrm_blocks on aicrm_blocks.blockid = aicrm_field.block WHERE aicrm_field.tabid=".$tabid." AND aicrm_field.block IN ".$blockid_list." AND aicrm_field.displaytype IN (1,2,4) AND aicrm_profile2field.visible=0 AND aicrm_def_org_field.visible=0 AND aicrm_profile2field.profileid IN (". implode(",", $profileList) .") and aicrm_field.presence in (0,2) GROUP BY aicrm_field.fieldid ORDER BY aicrm_blocks.sequence , aicrm_field.sequence)
			
			UNION ALL
			(
			SELECT
				'product_name' AS columnname,
				'ชื่อสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_brand' AS columnname,
				'ยี่ห้อสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_group' AS columnname,
				'กลุ่มสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_code_crm' AS columnname,
				'รหัสสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_prefix' AS columnname,
				'Prefix' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_factory_code' AS columnname,
				'รหัสสีสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_design_name' AS columnname,
				'ชื่่อสีสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_finish_name' AS columnname,
				'ชนิดผิว' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_size_ft' AS columnname,
				'ขนาด (ฟุต)' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_thinkness' AS columnname,
				'ความหนา (มม.)' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_grade' AS columnname,
				'เกรดสินค้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_film' AS columnname,
				'Film' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'product_backprint' AS columnname,
				'Backprint' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_quantity' AS columnname,
				'จำนวนที่สั่งซื้อใน P/O' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_percentage' AS columnname,
				'GR 90% or 100%' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_quantity' AS columnname,
				'จำนวนสินค้า GR ที่รับเข้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'defects_quantity' AS columnname,
				'จำนวนสินค้าชำรุดที่รับเข้า' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'remain_quantity' AS columnname,
				'จำนวนที่เหลือ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'gr_qty_percent' AS columnname,
				'GR Qty.%' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'item_status' AS columnname,
				'สถานะรายการที่สั่งซื้อ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'projects_name' AS columnname,
				'ชื่อโครงการ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'assignto' AS columnname,
				'ผู้รับผิดชอบโครงการ' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'po_price_type' AS columnname,
				'Price Type' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'price_usd' AS columnname,
				'ราคาซื้อ USD ($)' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'unit_price' AS columnname,
				'ราคา/หน่วย' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_amount' AS columnname,
				'Total Amount' AS fieldlabel,
				'aicrm_inventorypurchasesorderrel' AS tablename,
				'1' AS uitype 
			)

			UNION ALL
			(
			SELECT
				'subtotal' AS columnname,
				'Net Total' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total_after_discount' AS columnname,
				'Total After Discount' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'tax1' AS columnname,
				'Vat %' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'tax_final' AS columnname,
				'Vat' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			UNION ALL
			(
			SELECT
				'total' AS columnname,
				'Grand Total' AS fieldlabel,
				'aicrm_purchasesorder' AS tablename,
				'1' AS uitype 
			)
			";
		}else{
			$sql = "SELECT aicrm_field.columnname, aicrm_field.fieldlabel, aicrm_field.tablename, aicrm_field.uitype FROM aicrm_field INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid=aicrm_field.fieldid INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid=aicrm_field.fieldid Inner join aicrm_blocks on aicrm_blocks.blockid = aicrm_field.block WHERE aicrm_field.tabid=".$tabid." AND aicrm_field.block IN ".$blockid_list." AND aicrm_field.displaytype IN (1,2,4) AND aicrm_profile2field.visible=0 AND aicrm_def_org_field.visible=0 AND aicrm_profile2field.profileid IN (". implode(",", $profileList) .") and aicrm_field.presence in (0,2) GROUP BY aicrm_field.fieldid ORDER BY aicrm_blocks.sequence , aicrm_field.sequence ";
		}
	}
	$log->debug("Exit from the function getPermittedFieldsQuery($module, $disp_view). Return value = $sql");
	// echo $sql; exit;
	return $sql;
}

/**	function used to get the list of fields from the input query as a comma seperated string
 *	@param string $query - field table query which contains the list of fields
 *	@return string $fields - list of fields as a comma seperated string
 */
function getFieldsListFromQuery($query)
{
	global $adb, $log;
	$log->debug("Entering into the function getFieldsListFromQuery($query)");

	$result = $adb->query($query);
	$num_rows = $adb->num_rows($result);

	for($i=0; $i < $num_rows;$i++)
	{
		$columnName = $adb->query_result($result,$i,"columnname");
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$tablename = $adb->query_result($result,$i,"tablename");
		$uitype = $adb->query_result($result,$i,"uitype");
		
		//HANDLE HERE - Mismatch fieldname-tablename in field table, in future we have to avoid these if elses
		if($columnName == 'smownerid')//for all assigned to user name
		{
			$fields .= "case when (aicrm_users.user_name not like '') then concat(aicrm_users.first_name,' ',aicrm_users.last_name) else aicrm_groups.groupname end as '".$fieldlabel."',";
		}
		elseif($tablename == 'aicrm_account' && $columnName == 'parentid')//Account - Member Of
		{
			 $fields .= "aicrm_account2.accountname as '".$fieldlabel."',";
		}
		elseif($tablename == 'aicrm_contactdetails' && $columnName == 'accountid')//Contact - Account Name
		{
			$fields .= "aicrm_account.accountid, aicrm_account.accountname as '".$fieldlabel."',";
		}
		elseif($tablename == 'aicrm_contactdetails' && $columnName == 'reportsto')//Contact - Reports To
		{
			$fields .= " concat(aicrm_contactdetails2.lastname,' ',aicrm_contactdetails2.firstname) as 'Reports To Contact',";
		}
		elseif($tablename == 'aicrm_seproductsrel' && $columnName == 'crmid')//Product - Related To
		{
			$fields .= "case aicrm_crmentityRelatedTo.setype
					when 'Leads' then concat('Leads ::: ',aicrm_ProductRelatedToLead.lastname,' ',aicrm_ProductRelatedToLead.firstname)
					when 'Accounts' then concat('Accounts ::: ',aicrm_ProductRelatedToAccount.accountname)
					when 'Potentials' then concat('Potentials ::: ',aicrm_ProductRelatedToPotential.potentialname)
				    End as 'Related To',";
		}
		elseif($tablename == 'aicrm_products' && $columnName == 'contactid')//Product - Contact
		{
			$fields .= " concat(aicrm_contactdetails.lastname,' ',aicrm_contactdetails.firstname) as 'Contact Name',";
		}
		//Pavani- Handling product handler
		elseif($tablename == 'aicrm_products' && $columnName == 'handler')//Product - Handler
		{
			$fields .= "aicrm_users.user_name as '".$fieldlabel."',";
		}
		elseif($tablename == 'aicrm_producttaxrel' && $columnName == 'taxclass')//avoid product - taxclass
		{
			$fields .= "";
		}
		elseif($tablename == 'aicrm_attachments' && $columnName == 'name')//Emails filename
		{
			$fields .= $tablename.".name as '".$fieldlabel."',";
		}
        elseif($tablename == 'aicrm_troubletickets' && $columnName == 'parent_id')//Ticket - Related To
        {
            $fields .= "case aicrm_crmentityRelatedTo.setype
            when 'Accounts' then concat('Accounts ::: ',aicrm_account.accountname)
			when 'Contacts' then concat('Contacts ::: ',aicrm_contactdetails.lastname,' ',aicrm_contactdetails.firstname)
            End as 'Related To',";
        }
		elseif($tablename == 'aicrm_notes' && ($columnName == 'filename' || $columnName == 'filetype' || $columnName == 'filesize' || $columnName == 'filelocationtype' || $columnName == 'filestatus' || $columnName == 'filedownloadcount' ||$columnName == 'folderid')){
			continue;
		}
		else
		{			
			if($uitype =="57"){
				//Contact
				$fields .= $tablename.".".$columnName. " as 'contactid' ,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as '" .$fieldlabel." ',";
			}
			elseif($uitype =="58"){
				//Campaing
				$fields .= $tablename.".".$columnName. " as 'campaignid' ,aicrm_campaign.campaignname as '" .$fieldlabel." ',";
			}
			elseif($uitype =="59" && $tablename != 'aicrm_deal'){ 
				//product
				$fields .= $tablename.".".$columnName. " as 'productid' ,aicrm_products.productname as '" .$fieldlabel." ',";
			}
			else if($uitype =="73"){
				//account
				$fields .= $tablename.".".$columnName. " as 'accountid',aicrm_account.accountname as '" .$fieldlabel." ',";
			}
			else if($uitype =="201"){
				//parentid
				$fields .= $tablename.".".$columnName. " as 'parentid',case when aicrm_leaddetails.leadid != '' then concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) when aicrm_account.accountid != '' then aicrm_account.accountname else '' end as '" .$fieldlabel." ',";
			}
			else if($uitype =='301'){
				$fields .= $tablename.".".$columnName. " as 'dealid' ,aicrm_deal.deal_no as '" .$fieldlabel." ',";
			}
			else if($uitype =='302'){
				$fields .= $tablename.".".$columnName. " as 'competitorid' ,aicrm_competitor.competitor_name as '" .$fieldlabel." ',";
			}
			else if($uitype =='303'){
				$fields .= $tablename.".".$columnName. " as 'promotionvoucherid' ,aicrm_promotionvoucher.promotionvoucher_name as '" .$fieldlabel." ',";
			}
			else if($uitype =='304'){
				$fields .= $tablename.".".$columnName. " as 'promotionid' ,aicrm_promotion.promotion_no as '" .$fieldlabel." ',";
			}
			else if($uitype =='305'){
				$fields .= $tablename.".".$columnName. " as 'salesorderid' ,aicrm_salesorder.salesorder_no as '" .$fieldlabel." ',";
			}
			else if($uitype =='307'){
				$fields .= $tablename.".".$columnName. " as 'quoteid' ,aicrm_quotes.quotes_no as '" .$fieldlabel." ',";
			}
			else if($uitype =='308'){
				$fields .= $tablename.".".$columnName. " as 'servicerequestid' ,aicrm_servicerequest.servicerequest_name as '" .$fieldlabel." ',";
			}
			else if($uitype =='309'){
				$fields .= $tablename.".".$columnName. " as 'ticketid' ,aicrm_troubletickets.title as '" .$fieldlabel." ',";
			}
			else if($uitype =="906"){
				//branch
				$fields .= $tablename.".".$columnName. " as 'branchid',aicrm_branchs.branch_name as '" .$fieldlabel." ',";
			}
			elseif($uitype =="910"){
				//building
				$fields .= $tablename.".".$columnName. " as 'buildingid',aicrm_building.building_name as '" .$fieldlabel." ',";
			}
			elseif($uitype =="919"){
				//agreement
				$fields .= $tablename.".".$columnName. " as 'agreementid',aicrm_agreement.agreement_no as '" .$fieldlabel." ',";
			}
			else if($uitype =="920"){
				//booking
				$fields .= $tablename.".".$columnName. " as 'reservationid',aicrm_booking.booking_no as '" .$fieldlabel." ',";
			}
			else if($uitype =="921"){
				//prevaccount
				$fields .= $tablename.".".$columnName. " as 'prevaccountid',aicrm_accountprev.accountname as '" .$fieldlabel." ',";
			}
			else if($uitype =="922"){
				//resale from module
				$fields .= $tablename.".".$columnName. " as 'refid', ";
				$fields .= " case when aicrm_resale.ref_module = 'Booking' then aicrm_booking_ref.booking_no
									 when  aicrm_resale.ref_module = 'Agreement' then aicrm_agreement_ref.agreement_no
									else ''  end  as '" .$fieldlabel." ',";
			}
			else if($uitype=="929"){
				if($columnName=="jointname1"){
					$fields .= $tablename.".".$columnName. " as 'joinnameid1',aicrm_accountjoint1.accountname as '" .$fieldlabel." name',";
				}
				if($columnName=="jointname2"){
					$fields .= $tablename.".".$columnName. " as 'joinnameid2',aicrm_accountjoint2.accountname as '" .$fieldlabel." name',";
				}
				if($columnName=="jointname3"){
					$fields .= $tablename.".".$columnName. " as 'joinnameid3',aicrm_accountjoint3.accountname as '" .$fieldlabel." name',";
				}
				if($columnName=="jointname4"){
					$fields .= $tablename.".".$columnName. " as 'joinnameid4',aicrm_accountjoint4.accountname as '" .$fieldlabel." name',";
				}
			}else if($uitype=="931"){
				if($columnName=="contactid1"){
					$fields .= $tablename.".".$columnName. " as 'contactid1',concat(aicrm_contactdetails1.firstname,' ',aicrm_contactdetails1.lastname) as '" .$fieldlabel." ',";
				}
				if($columnName=="contactid2"){
					$fields .= $tablename.".".$columnName. " as 'contactid1',concat(aicrm_contactdetails2.firstname,' ',aicrm_contactdetails1.lastname) as '" .$fieldlabel." ',";
				}
				if($columnName=="contactid3"){
					$fields .= $tablename.".".$columnName. " as 'contactid1',concat(aicrm_contactdetails3.firstname,' ',aicrm_contactdetails1.lastname) as '" .$fieldlabel." ',";
				}
			}else if($uitype =="932"){
				//user Modified
				$fields .= "concat(aicrm_usersModified.first_name,' ',aicrm_usersModified.last_name) as '" .$fieldlabel." ',";
			}
			else if($uitype =="935"){
				$fields .= $tablename.".".$columnName. " as 'serialid' ,aicrm_serial.serial_name as '" .$fieldlabel." ',";
			}
			else if($uitype =="936"){
				$fields .= $tablename.".".$columnName. " as 'sparepartid' ,aicrm_sparepart.sparepart_no as '" .$fieldlabel." ',";
			}
			else if($uitype =="937"){
				$fields .= $tablename.".".$columnName. " as 'errorsid' ,aicrm_errors.errors_no as '" .$fieldlabel." ',";
			}
			else if($uitype =="938"){
				$fields .= $tablename.".".$columnName. " as 'jobid' ,aicrm_jobs.job_no as '" .$fieldlabel." ',";
			}
			else if($uitype =="939"){
				$fields .= $tablename.".".$columnName. " as 'caseid' ,aicrm_troubletickets.ticket_no as '" .$fieldlabel." ',";
			}
			else if($uitype =="940"){
				//user Created
				$fields .= "concat(aicrm_usersCreator.first_name,' ',aicrm_usersCreator.last_name) as '" .$fieldlabel." ',";
			}
			else if($uitype =="941"){
				$fields .= $tablename.".".$columnName. " as 'palntid' ,aicrm_plant.plant_no as '" .$fieldlabel." ',";
			}
			
			else if($uitype =='943'){
				$fields .= $tablename.".".$columnName. " as 'leadid' ,aicrm_leaddetails.lead_no as '" .$fieldlabel." ',";
			}
			else if($uitype =='947'){
				$fields .= $tablename.".".$columnName. " as 'questionnaireid' ,aicrm_questionnaire.questionnaire_name as '" .$fieldlabel." ',";
			}else{
				$fields .= $tablename.".".$columnName. " as '" .$fieldlabel."',";
			}

		}
	}
	
	$fields = trim($fields,",");

	$log->debug("Exit from the function getFieldsListFromQuery($query). Return value = $fields");
	return $fields;
}

function setFromQuery($module)
{
	$query .= "";
	
	if($module =="KnowledgeBase")
	{
		//$query .= " left join aicrm_branchs on aicrm_branchs.branchid = aicrm_knowledgebase.branchid  ";
	}
	else if($module =="Activitys")
	{
		//$query .= " left join aicrm_branchs on aicrm_branchs.branchid = aicrm_activitys.branchid  ";
		$query .= " left join aicrm_account as aicrm_accountacc on aicrm_accountacc.accountid = aicrm_activitys.accountid  ";
	}
	else if($module =="HelpDesk")
	{
		$query .= " left join aicrm_account as aicrm_accountacc on aicrm_accountacc.accountid = aicrm_troubletickets.accountid  ";
	}
	
	return $query;
}

?>
