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

<script type="text/javascript" src="include/js/Inventory_{$MODULE}.js"></script>
<script type="text/javascript">
	jQuery.noConflict();
</script>
<!-- Added to display the Product Details -->
<script type="text/javascript">
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
		if(curr_quantity == '')
		{ldelim}
			alert("{$APP.PLEASE_FILL_QUANTITY}");
			return false;
		{rdelim}
	{rdelim}

	//Set the Header value for Discount
	if(mode == 'discount')
	{ldelim}
		document.getElementById("discount_div_title"+curr_row).innerHTML = '<b>{$APP.LABEL_SET_DISCOUNT_FOR_X_COLON} '+document.getElementById("productTotal"+curr_row).innerHTML+'</b>';
	{rdelim}
	else if(mode == 'tax')
	{ldelim}
		document.getElementById("tax_div_title"+curr_row).innerHTML = "<b>{$APP.LABEL_SET_TAX_FOR} "+document.getElementById("totalAfterDiscount"+curr_row).innerHTML+'</b>';
	{rdelim}
	else if(mode == 'discount_final')
	{ldelim}
		document.getElementById("discount_div_title_final").innerHTML = '<b>{$APP.LABEL_SET_DISCOUNT_FOR_COLON} '+document.getElementById("netTotal").innerHTML+'</b>';
	{rdelim}
	else if(mode == 'sh_tax_div_title')
	{ldelim}
		document.getElementById("sh_tax_div_title").innerHTML = '<b>{$APP.LABEL_SET_SH_TAX_FOR_COLON} '+document.getElementById("shipping_handling_charge").value+'</b>';
	{rdelim}
	else if(mode == 'group_tax_div_title')
	{ldelim}
		var net_total_after_discount = eval(document.getElementById("netTotal").innerHTML)-eval(document.getElementById("discountTotal_final").innerHTML);
		document.getElementById("group_tax_div_title").innerHTML = '<b>{$APP.LABEL_SET_GROUP_TAX_FOR_COLON} '+net_total_after_discount+'</b>';
	{rdelim}

	fnvshobj(currObj,'tax_container');
	if(document.all)
	{ldelim}
		var divleft = document.getElementById("tax_container").style.left;
		var divabsleft = divleft.substring(0,divleft.length-2);
		document.getElementById(obj).style.left = eval(divabsleft) - 120;

		var divtop = document.getElementById("tax_container").style.top;
		var divabstop =  divtop.substring(0,divtop.length-2);
		document.getElementById(obj).style.top = eval(divabstop);
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


<tr>
	<td colspan="9" align="left">

		<table width="100%"  border="0" align="center" cellpadding="9" cellspacing="0" class="crmTable table-Goodsreceive" id="proTab">
			<thead>
			<tr>
				<td class="dvInnerHeader" width="100%">
					<b>{$APP.LBL_ITEM_DETAILS}</b>
				</td>

				<td class="dvInnerHeader" align="center" colspan="8">
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

				{foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
				{assign var="deleted" value="deleted"|cat:$row_no}
				{assign var="purchasesorder_no" value="purchasesorder_no"|cat:$row_no}
				{assign var="hdnPoId" value="hdnPoId"|cat:$row_no}
				{assign var="lineItemType" value="lineItemType"|cat:$row_no}
				{assign var="poName" value="poName"|cat:$row_no}
				{assign var="comment" value="comment"|cat:$row_no}
				{assign var="purchase_order_date" value="purchase_order_date"|cat:$row_no}
				{assign var="projects_code" value="projects_code"|cat:$row_no}
				{assign var="projectsid" value="projectsid"|cat:$row_no}
				{assign var="projects_name" value="projects_name"|cat:$row_no}
				{assign var="assignto" value="assignto"|cat:$row_no}
				{assign var="smownerid" value="smownerid"|cat:$row_no}
				{assign var="product_code_crm" value="product_code_crm"|cat:$row_no}
				{assign var="productid" value="productid"|cat:$row_no}
				{assign var="productname" value="productname"|cat:$row_no}
				{assign var="po_detail_no" value="po_detail_no"|cat:$row_no}
				{assign var="po_quantity" value="po_quantity"|cat:$row_no}
				{assign var="gr_percentage" value="gr_percentage"|cat:$row_no}
				{assign var="item_status" value="item_status"|cat:$row_no}
				{assign var="gr_qty_percent" value="gr_qty_percent"|cat:$row_no}
				{assign var="gr_quantity" value="gr_quantity"|cat:$row_no}
				{assign var="defects_quantity" value="defects_quantity"|cat:$row_no}
				{assign var="remain_quantity" value="remain_quantity"|cat:$row_no}
				{assign var="unit_price" value="unit_price"|cat:$row_no}
				{assign var="amount" value="amount"|cat:$row_no}
				{assign var="total_defects_quantity" value="total_defects_quantity"|cat:$row_no}
				{assign var="total_gr_quantity" value="total_gr_quantity"|cat:$row_no}
				{assign var="total_amount" value="total_amount"|cat:$row_no}
				{assign var="defects_remark" value="defects_remark"|cat:$row_no}
				
			<tr id="row{$row_no}" valign="top">

				<td  class="crmTableRow small lineOnTop" align="center">
					{if $row_no neq 1}
					<img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="deleteRow('{$MODULE}',{$row_no},'{$IMAGE_PATH}')">
					{/if}<br/><br/>
					{if $row_no neq 1}
					&nbsp;<a href="javascript:moveUpDown('UP','{$MODULE}',{$row_no})" title="Move Upward"><img src="{'up_layout.gif'|@aicrm_imageurl:$THEME}" border="0"></a>
					{/if}
					{if not $smarty.foreach.outer1.last}
					&nbsp;<a href="javascript:moveUpDown('DOWN','{$MODULE}',{$row_no})" title="Move Downward"><img src="{'down_layout.gif'|@aicrm_imageurl:$THEME}" border="0" ></a>
					{/if}
					<input type="hidden" id="{$deleted}" name="{$deleted}" value="0">
				</td>

				<td class="crmTableRow small lineOnTop">
					<input type="hidden" name="hidtax_row_no{$row_no}" id="hidtax_row_no{$row_no}" value="{$tax_row_no}"/>
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
						<tr>
							<td class="small" valign="top">
								<textarea id="{$purchasesorder_no}" name="{$purchasesorder_no}" readonly="readonly" class="detailedViewTextBox user-success" style="width: 200px;height:40px">{$data.$purchasesorder_no}</textarea>
								<input type="hidden" id="{$hdnPoId}" name="{$hdnPoId}" value="{$data.$hdnPoId}" />
								<input type="hidden" id="po_display{$row_no}" name="po_display{$row_no}" value="0" />
								<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
								<img id="searchIcon{$row_no}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no}')" />
								
							</td>
						</tr>
						<tr>
							<td class="small" id="setComment">
								<textarea id="{$comment}" name="{$comment}" class=detailedViewTextBox user-success style="width: 200px;height:40px">{$data.$comment}</textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$comment}').value=''"; style="cursor:pointer;" />
							</td>
						</tr>
					</table>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$purchase_order_date}" name="{$purchase_order_date}" type="text" class="small purchase_order_date" onfocus="this.className='purchase_order_date';" onblur="this.className='purchase_order_date';settotalnoofrows();"  value="{$data.$purchase_order_date}" style="background-color: #ccc;" />
				</td>

				<td class="crmTableRow small lineOnTop">
			   		<input data-rowno="{$row_no}" id="{$projects_code}" name="{$projects_code}" type="text" class="small projects_code" onfocus="this.className='projects_code';" onblur="this.className='projects_code';settotalnoofrows();"  value="{$data.$projects_code}" style="background-color: #ccc;" readonly/>
			   		<input id="{$projectsid}" name="{$projectsid}" type="hidden" value="{$data.$projectsid}">
					<input id="{$projects_name}" name="{$projects_name}" type="hidden" value="{$data.$projects_name}">
			   	</td>
				
				<td class="crmTableRow small lineOnTop" valign="top">
					<input data-rowno="{$row_no}" id="{$assignto}"name="{$assignto}" type="text" class="small assignto" onfocus="this.className='assignto'" onBlur="this.className='assignto';settotalnoofrows();" value="{$data.$assignto}" style="background-color: #ccc;"/>
					<input id="{$smownerid}" name="{$smownerid}" type="hidden" value="{$data.$smownerid}">
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$product_code_crm}" name="{$product_code_crm}" type="text" class="small product_code_crm" onfocus="this.className='product_code_crm'" onBlur="this.className='product_code_crm';settotalnoofrows();" value="{$data.$product_code_crm}" style="background-color: #ccc;" readonly/>
					<input id="{$productid}" name="{$productid}" type="hidden" value="{$data.$productid}"/>
					<img id="searchIconitem{$row_no}" title="Items PO" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="poItems(this,'{$MODULE}',{$row_no})" />
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$productname}" name="{$productname}" type="text" class="small productname" onfocus="this.className='productname'" onBlur="this.className='productname';settotalnoofrows();" value="{$data.$productname}" style="background-color: #ccc;" readonly/>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$po_detail_no}" name="{$po_detail_no}" type="text" class="small po_detail_no" onfocus="this.className='po_detail_no'" onBlur="this.className='po_detail_no';settotalnoofrows();" value="{$data.$po_detail_no}" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$po_quantity}" name="{$po_quantity}" type="text" class="small po_quantity" onfocus="this.className='po_quantity'" onBlur="this.className='po_quantity';settotalnoofrows();" value="{$data.$po_quantity}" style="background-color: #ccc;" readonly/>
				</td>


				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$gr_percentage}" name="{$gr_percentage}" type="text" class="small gr_percentage" onfocus="this.className='gr_percentage'" onBlur="this.className='gr_percentage';settotalnoofrows();" value="{$data.$gr_percentage}" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$item_status}" name="{$item_status}" type="text" class="small item_status" onfocus="this.className='item_status'" onBlur="this.className='item_status';settotalnoofrows();" value="{$data.$item_status}" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$gr_qty_percent}" name="{$gr_qty_percent}" type="text" class="small gr_qty_percent" onfocus="this.className='gr_qty_percent'" onBlur="this.className='gr_qty_percent';settotalnoofrows();" value="{$data.$gr_qty_percent}" style="background-color: #ccc;" readonly/>
				</td>


				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$gr_quantity}" name="{$gr_quantity}" type="text" class="small gr_quantity" onfocus="this.className='gr_quantity'" onBlur="this.className='gr_quantity';settotalnoofrows(); calAmount({$row_no}); calRemainQuantity({$row_no}); calTotalGR_quantity({$row_no});" value="{$data.$gr_quantity}" />
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$defects_quantity}" name="{$defects_quantity}" type="text" class="small defects_quantity" onfocus="this.className='defects_quantity'" onBlur="this.className='defects_quantity';settotalnoofrows(); calAmount({$row_no}); calRemainQuantity({$row_no}); calTotalDefects({$row_no});" value="{$data.$defects_quantity}"/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$remain_quantity}" name="{$remain_quantity}" type="text" class="small remain_quantity" onfocus="this.className='remain_quantity'" onBlur="this.className='remain_quantity';settotalnoofrows();" value="{$data.$remain_quantity}" style="background-color: #ccc;" readonly/>
					<input type="hidden" id="hdnRemain_quantity{$row_no}" name="hdnRemain_quantity{$row_no}" value="{$data.$remain_quantity}" />
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$unit_price}" name="{$unit_price}" type="text" class="small unit_price" onfocus="this.className='unit_price'" onBlur="this.className='unit_price';settotalnoofrows();" value="{$data.$unit_price}" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$amount}" name="{$amount}" type="text" class="small amount" onfocus="this.className='amount'" onBlur="this.className='amount';settotalnoofrows();" value="{$data.$amount}" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$total_gr_quantity}" name="{$total_gr_quantity}" type="text" class="small total_gr_quantity" onfocus="this.className='total_gr_quantity'" onBlur="this.className='total_gr_quantity';settotalnoofrows();" value="{$data.$total_gr_quantity}" style="background-color: #ccc;" readonly/>
					<input type="hidden" id="hdnTotal_gr_quantity{$row_no}" name="hdnTotal_gr_quantity{$row_no}" value="{$data.$total_gr_quantity}" />
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$total_defects_quantity}" name="{$total_defects_quantity}" type="text" class="small total_defects_quantity" onfocus="this.className='total_defects_quantity'" onBlur="this.className='total_defects_quantity';settotalnoofrows();" value="{$data.$total_defects_quantity}" style="background-color: #ccc;" readonly/>
					<input type="hidden" id="hdnTotal_defects_quantity{$row_no}" name="hdnTotal_defects_quantity{$row_no}" value="{$data.$total_defects_quantity}" />
				</td>
			
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$total_amount}" name="{$total_amount}" type="text" class="small total_amount" onfocus="this.className='total_amount'" onBlur="this.className='total_amount';settotalnoofrows();" value="{$data.$total_amount}" style="background-color: #ccc;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop" align="right">
					<textarea id="{$defects_remark}" name="{$defects_remark}" class="user-success" style="height:40px;width: 150px">{$data.$defects_remark}</textarea>
				</td>
				
			</tr>

			{/foreach}
		</tbody>
		</table>
		{assign var="FINAL" value=$ASSOCIATEDPRODUCTS.1.final_details}
		<table width="100%"  border="0" align="center" cellpadding="8" cellspacing="0" class="crmTable" style="display:none;">
			<tr>
				<td width=5% valign="top" align="right"></td>
				<td width=35% ></td>
				<td width="10%"></td>
				<td width="10%"></td>
				<td width=10%></td>
				<td width=10%><strong>Total</strong></td>
				<td width=10% align="center"><input id="total_amount_of_sample" name="total_amount_of_sample" type="text" class="detailedViewTextBox" value="{$FINAL.total_amount_of_sample}" readonly="" style="background-color: #ccc" /></td>
				<td width="10%" align="center"><input id="total_amount_of_purchase" name="total_amount_of_purchase" type="text" class="detailedViewTextBox" value="{$FINAL.total_amount_of_purchase}" style="background-color: #ccc" readonly=""/></td>
			</tr>
		</table>


		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
		   <tr>
			<td colspan="3">
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRow('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Products
				</button>
			</td>
		   </tr>
			
		</table>

		<input type="hidden" name="totalProductCount" id="totalProductCount" value="{$row_no}">
		<input type="hidden" name="subtotal" id="subtotal" value="">
		<input type="hidden" name="total" id="total" value="">
	</td>
</tr>
<!-- Upto this Added to display the Product Details -->

{foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS}
	<!-- This is added to call the function calcCurrentTax which will calculate the tax amount from percentage -->
	{foreach key=tax_row_no item=tax_data from=$data.taxes}
		{assign var="taxname" value=$tax_data.taxname|cat:"_percentage"|cat:$row_no}
			<script>calcCurrentTax('{$taxname}',{$row_no},{$tax_row_no});</script>
	{/foreach}
	{assign var="entityIndentifier" value='entityType'|cat:$row_no}
	{if $MODULE eq 'Invoice' && $data.$entityIndentifier neq 'Services'}
		<script>stock_alert('{$row_no}');</script>
	{/if}
{/foreach}

{literal}
<style type="text/css">
	.table-Goodsreceive > tbody{
		display: block;
	  	table-layout: fixed;
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