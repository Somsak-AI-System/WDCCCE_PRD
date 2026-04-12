{*<!--

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
<!-- <link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css"> -->
<script type="text/javascript">
	jQuery.noConflict();
</script>
<script type="text/javascript" src="include/js/Inventory_{$MODULE}.js"></script>
<script>
if(!e)
	window.captureEvents(Event.MOUSEMOVE);

function displayCoords(currObj,obj,mode,curr_row)
{ldelim}
	if(mode != 'discount_final' && mode != 'sh_tax_div_title' && mode != 'group_tax_div_title')
	{ldelim}
		var curr_productid = document.getElementById("hdnPoId"+curr_row).value;
		if(curr_productid == '')
		{ldelim}
			alert("{$APP.PLEASE_SELECT_LINE_ITEM}");
			return false;
		{rdelim}

		var curr_quantity = document.getElementById("qty"+curr_row).value;
        var curr_test_box = document.getElementById("test_box"+curr_row).value;
		if(curr_quantity == '')
		{ldelim}
			alert("{$APP.PLEASE_FILL_QUANTITY}");
			return false;
		{rdelim}
	{rdelim}

	//Set the Header value for Discount
	if(mode == 'discount')
	{ldelim}
		document.getElementById("discount_div_title"+curr_row).innerHTML = '<b>{$APP.LABEL_SET_DISCOUNT_FOR_COLON} '+document.getElementById("productTotal"+curr_row).innerHTML+'</b>';
	{rdelim}
	else if(mode == 'tax')
	{ldelim}
		document.getElementById("tax_div_title"+curr_row).innerHTML = "<b>{$APP.LABEL_SET_TAX_FOR} "+document.getElementById("totalAfterDiscount"+curr_row).innerHTML+'</b>';
	{rdelim}
	else if(mode == 'discount_final')
	{ldelim}
		document.getElementById("discount_div_title_final").innerHTML = '<b>{$APP.LABEL_SET_DISCOUNT_FOR} '+document.getElementById("netTotal").innerHTML+'</b>';
	{rdelim}
	else if(mode == 'sh_tax_div_title')
	{ldelim}
		document.getElementById("sh_tax_div_title").innerHTML = '<b>{$APP.LABEL_SET_SH_TAX_FOR_COLON} '+document.getElementById("shipping_handling_charge").value+'</b>';
	{rdelim}
	else if(mode == 'group_tax_div_title')
	{ldelim}
		var net_total_after_discount = eval(document.getElementById("netTotal").innerHTML)-eval(document.getElementById("discountTotal_final").innerHTML);
		document.getElementById("group_tax_div_title").innerHTML = '<b>Set Group Vat for : '+net_total_after_discount+'</b>';
	{rdelim}

	fnvshobj(currObj,'tax_container');
	if(document.all)
	{ldelim}
		var divleft = document.getElementById("tax_container").style.left;
		var divabsleft = divleft.substring(0,divleft.length-2);
		document.getElementById(obj).style.left = eval(divabsleft) - 120;

		var divtop = document.getElementById("tax_container").style.top;
		var divabstop =  divtop.substring(0,divtop.length-2);
		document.getElementById(obj).style.top = eval(divabstop) - 200;
	{rdelim}else
	{ldelim}
		document.getElementById(obj).style.left =  document.getElementById("tax_container").left;
		document.getElementById(obj).style.top = document.getElementById("tax_container").top;
	{rdelim}
	document.getElementById(obj).style.display = "block";

{rdelim}

function doNothing(){ldelim}
{rdelim}

function fnHidePopDiv(obj){ldelim}
	document.getElementById(obj).style.display = 'none';
{rdelim}
</script>

<!-- Added this file to display and hanld the Product Details in Inventory module  -->

<tr>
	<td colspan="4" align="left">
	<div id="dialog" style="display:none;">Dialog Content.</div>

	<table width="100%"  border="0" align="center" cellpadding="7" cellspacing="0" class="crmTable table-Goodsreceive" id="proTab">
	   	<thead>
				<tr>
					<td class="dvInnerHeader" width="100%">
						<b>{$APP.LBL_ITEM_DETAILS}</b>
					</td>

					<td class="dvInnerHeader" align="center" colspan="7">
						<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
						<input type="hidden" value="1" id="inventory_currency" name="inventory_currency" />
						<input type="hidden" value="group" id="taxtype" name="taxtype" />
						<input type="hidden" class="small" size="5" name="group_group_percentage" id="group_group_percentage" value="0" >
						<input type="hidden" class="small" size="6" name="individual_group_amount" id="individual_group_amount" value="0" >
						<input type="hidden" value="amount" id="discount_type_final" name="discount_type_final" >
						<input type="hidden" value="" id="discount_percentage_final" name="discount_percentage_final" >
						<input type="hidden" value="0" id="discount_amount_final" name="discount_amount_final" >
						<input type="hidden" class="small" size="3" name="shtax1_sh_percent" id="sh_tax_percentage1" value="0">
						<input type="hidden" class="small" size="4" name="shtax1_sh_amount" id="sh_tax_amount1" value="0">
						<input type="hidden" class="small" size="3" name="shtax2_sh_percent" id="sh_tax_percentage2" value="0">
						<input type="hidden" class="small" size="4" name="shtax2_sh_amount" id="sh_tax_amount2" value="0">
						<input type="hidden" class="small" size="3" name="shtax3_sh_percent" id="sh_tax_percentage3" value="0">
						<input type="hidden" class="small" size="4" name="shtax3_sh_amount" id="sh_tax_amount3" value="0">
						<input id="shipping_handling_charge" name="shipping_handling_charge" type="hidden" value="0">
						<input type="hidden" value="" id="adjustmentType" name="adjustmentType" >
						<input id="adjustmentType" name="adjustmentType" type="hidden" value="0">
					</td>
				</tr>
			</thead>
	   <!-- Header for the Product Details -->
	   	<tbody>
			<tr valign="top">
				<td width="100px" valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
				<td width="400px" class="lvtCol"><font color="red">* </font><b>หมายเลขใบสั่งซื้อ</b></td>
				<td width="200px" align="center" class="lvtCol"><strong>Purchase Order Date</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>ชื่อโครงการ</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>ผู้รับผิดชอบโครงการ</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>รหัสสินค้า</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>ชื่อสินค้า</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>ลำดับที่สินค้าใน P/O</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>จำนวนที่สั่งซื้อใน P/O</strong></td>

				<td width="200px" align="center" class="lvtCol"><strong>GR 90% or 100%</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>สถานะรายการที่สั่งซื้อ</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>GR Qty.%</strong></td>

				<td width="200px" align="center" class="lvtCol"><strong>จำนวนสินค้า GR ที่รับเข้า</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>จำนวนสินค้าชำรุดที่รับเข้า</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>จำนวนที่เหลือ</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>ราคา/หน่วย</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>ราคา</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>จำนวนสินค้า GR ที่รับเข้าทั้งหมด</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>จำนวนสินค้า Defects ที่รับเข้าทั้งหมด</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>ราคารวมทั้งหมด</strong></td>
				<td width="200px" align="center" class="lvtCol"><strong>หมายเหตุสินค้าชำรุด</strong></td>
			</tr>
			<!-- Following code is added for form the first row. Based on these we should form additional rows using script -->

		   	<!-- Product Details First row - Starts -->
		   	<tr valign="top" id="row1">
				<td  class="crmTableRow small lineOnTop" align="center">&nbsp;
					<input type="hidden" id="deleted1" name="deleted1" value="0">
				</td>
				<td class="crmTableRow small lineOnTop">
					<textarea id="purchasesorder_no1" name="purchasesorder_no1"  class="user-success" style="height:40px;width: 200px" readonly>{$PRODUCT_NAME}</textarea>
					<input type="hidden" id="hdnPoId1" name="hdnPoId1" value="{$PRODUCT_ID}" />
					<input type="hidden" id="lineItemType1" name="lineItemType1" value="Purchasesorder" />
					<input type="hidden" id="poName1" name="poName1" value="" />
					<input type="hidden" id="po_display1" name="po_display1" value="0" />
					<img id="searchIcon1" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}',1)" />
					<br>
					<textarea id="comment1" name="comment1" class="user-success" style="height:40px;width: 200px"></textarea>
					<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('comment1').value=''"; style="cursor:pointer;" />
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<input id="purchase_order_date1" name="purchase_order_date1" type="text" class="small purchase_order_date" onfocus="this.className='purchase_order_date';" onblur="this.className='purchase_order_date';settotalnoofrows();"  value="" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
			   		<input id="projects_code1" name="projects_code1" type="text" class="small projects_code" onfocus="this.className='projects_code';" onblur="this.className='projects_code';settotalnoofrows();"  value="" style="background-color: #ccc;" readonly/>
			   		<input id="projectsid1" name="projectsid1" type="hidden" value="">
					<input id="projects_name1" name="projects_name1" type="hidden" value="">
			   	</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="assignto1"name="assignto1" type="text" class="small assignto" onfocus="this.className='assignto'" onBlur="this.className='assignto';settotalnoofrows();" value="" style="background-color: #ccc;" readonly/>
					<input id="smownerid1" name="smownerid1" type="hidden" value="">
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_code_crm1" name="product_code_crm1" type="text" class="small product_code_crm" onfocus="this.className='product_code_crm'" onBlur="this.className='product_code_crm';settotalnoofrows();" value="" style="background-color: #ccc;" readonly/>
					<input id="productid1" name="productid1" type="hidden" value=""/>
					<img id="searchIconitem1" title="Items PO" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="poItems(this,'{$MODULE}',1)" />
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="productname1" name="productname1" type="text" class="small productname" onfocus="this.className='productname'" onBlur="this.className='productname';settotalnoofrows();" value="" style="background-color: #ccc;" readonly/>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="po_detail_no1" name="po_detail_no1" type="text" class="small po_detail_no" onfocus="this.className='po_detail_no'" onBlur="this.className='po_detail_no';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="po_quantity1" name="po_quantity1" type="text" class="small po_quantity" onfocus="this.className='po_quantity'" onBlur="this.className='po_quantity';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
				</td>


				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="gr_percentage1" name="gr_percentage1" type="text" class="small gr_percentage" onfocus="this.className='gr_percentage'" onBlur="this.className='gr_percentage';settotalnoofrows();" value="" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="item_status1" name="item_status1" type="text" class="small item_status" onfocus="this.className='item_status'" onBlur="this.className='item_status';settotalnoofrows();" value="" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="gr_qty_percent1" name="gr_qty_percent1" type="text" class="small gr_qty_percent" onfocus="this.className='gr_qty_percent'" onBlur="this.className='gr_qty_percent';settotalnoofrows();" value="" style="background-color: #ccc;" readonly/>
				</td>


				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="gr_quantity1" name="gr_quantity1" type="text" class="small gr_quantity" onfocus="this.className='gr_quantity'" onBlur="this.className='gr_quantity';settotalnoofrows(); calAmount(1); calRemainQuantity(1); calTotalGR_quantity(1);" value="0" />
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="defects_quantity1" name="defects_quantity1" type="text" class="small defects_quantity" onfocus="this.className='defects_quantity'" onBlur="this.className='defects_quantity';settotalnoofrows(); calAmount(1); calRemainQuantity(1); calTotalDefects(1);" value="0"/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="remain_quantity1" name="remain_quantity1" type="text" class="small remain_quantity" onfocus="this.className='remain_quantity'" onBlur="this.className='remain_quantity';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
					<input type="hidden" id="hdnRemain_quantity1" name="hdnRemain_quantity1" value="" />
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="unit_price1" name="unit_price1" type="text" class="small unit_price" onfocus="this.className='unit_price'" onBlur="this.className='unit_price';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="amount1" name="amount1" type="text" class="small amount" onfocus="this.className='amount'" onBlur="this.className='amount';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="total_gr_quantity1" name="total_gr_quantity1" type="text" class="small total_gr_quantity" onfocus="this.className='total_gr_quantity'" onBlur="this.className='total_gr_quantity';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
					<input type="hidden" id="hdnTotal_gr_quantity1" name="hdnTotal_gr_quantity1" value="" />
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="total_defects_quantity1" name="total_defects_quantity1" type="text" class="small total_defects_quantity" onfocus="this.className='total_defects_quantity'" onBlur="this.className='total_defects_quantity';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
					<input type="hidden" id="hdnTotal_defects_quantity1" name="hdnTotal_defects_quantity1" value="" />
				</td>
			
				
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="total_amount1" name="total_amount1" type="text" class="small total_amount" onfocus="this.className='total_amount'" onBlur="this.className='total_amount';settotalnoofrows();" value="0" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop" align="right">
					<textarea id="defects_remark1" name="defects_remark1" class="user-success" style="height:40px;width: 150px"></textarea>
				</td>
		   	</tr>
		   	<!-- Product Details First row - Ends -->
	   	</tbody>
	</table>
	<!-- Upto this has been added for form the first row. Based on these above we should form additional rows using script -->

	<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
		<!-- Add Product Button -->
		<tr>
			<td colspan="3">
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRow('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Products
				</button>
			</td>
		</tr>
	</table>

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="">
	<input type="hidden" name="subtotal" id="subtotal" value="">
	<input type="hidden" name="total" id="total" value="">

	</td>
</tr>

{literal}
<style type="text/css">
	.table-Goodsreceive > tbody{
		display: block;
	  	table-layout: fixed;
	  	/*width: 3700px;*/
	  	width: 1700px;
	  	overflow-x: auto;
	}
	.table-Goodsreceive > thead{
	  display: block;
	}
	.table-Goodsreceive {
	  border-collapse: collapse;
	  border-spacing: 0;
	}
</style>
{/literal}