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
<!-- <script type="text/javascript" src="include/js/jquery-1.9.1.min.js"></script> -->
<script type="text/javascript" src="include/js/Inventory_{$MODULE}.js"></script>
<script type="text/javascript">
	jQuery.noConflict();
</script>
<script>
if(!e)
	window.captureEvents(Event.MOUSEMOVE);

//  window.onmousemove= displayCoords;
//  window.onclick = fnRevert;
  
function displayCoords(currObj,obj,mode,curr_row) 
{ldelim}
	if(mode != 'discount_final' && mode != 'sh_tax_div_title' && mode != 'group_tax_div_title')
	{ldelim}
		var curr_productid = document.getElementById("hdnProductId"+curr_row).value;
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
	<td colspan="9" align="left">

		<table width="100%"  border="0" align="center" cellpadding="9" cellspacing="0" class="crmTable" id="proTab">
			<tr>
			{if $MODULE neq 'PurchaseOrder'}
				<td colspan="2" class="dvInnerHeader">
			{else}
				<td colspan="10" class="dvInnerHeader">
			{/if}
			
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


			<!-- Header for the Product Details -->
			<tr valign="top">
				<td width=5% valign="top" class="lvtCol" align="right"><b>เครื่องมือ</b></td>
				<td width=35% class="lvtCol"><font color="red">* </font><b>รายการสินค้า</b></td>
				<td width="10%" align="center" class="lvtCol"><strong>ชนิดผิว</strong></td>
				<td width="10%" align="center" class="lvtCol"><strong>ขนาด (มม.)</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>ความหนา (มม.)</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>หน่วยนับ</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>จำนวน (แผ่น)</strong></td>
				<td width="10%" align="center" class="lvtCol"><strong>จำนวนที่คาดว่าจะใช้</strong></td>
			</tr>

			<!-- Following code is added for form the first row. Based on these we should form additional rows using script -->

			<!-- Product Details First row - Starts -->
			<tr valign="top" id="row1">

				<!-- column 1 - delete link - starts -->
				<td  class="crmTableRow small lineOnTop" align="center">&nbsp;
					<input type="hidden" id="deleted1" name="deleted1" value="0">
				</td>
				<!-- column 1 - delete link - ends -->

				<!-- column 2 - Product Name - starts -->
				<td class="crmTableRow small lineOnTop">
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
						<tr>
							<td class="small">
								<span id="business_code1" name="business_code1" style="color:#C0C0C0;font-style:italic;"> </span>
								<div id="productCodeView{$row_no}" style="font-weight:bold;"></div>
								<textarea id="productName1" name="productName1" readonly="readonly" class="detailedViewTextBox user-success" style="width:90%;height:40px">{$PRODUCT_NAME}</textarea>

								<input type="hidden" id="hdnProductId1" name="hdnProductId1" value="{$PRODUCT_ID}" />
								<input type="hidden" id="lineItemType1" name="lineItemType1" value="Products" />
								<img id="searchIcon1" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}',1)" />
							</td>
						</tr>
						<tr>
							<td class="small">
								<input type="hidden" value="" id="subproduct_ids1" name="subproduct_ids1" />
								<span id="subprod_names1" name="subprod_names1" style="color:#C0C0C0;font-style:italic;"> </span></td>
						</tr>
						<tr valign="bottom">
							<td class="small" id="setComment">
								<textarea id="comment1" name="comment1" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('comment1').value=''"; style="cursor:pointer;" /></td>
						</tr>
					</table>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<select class="small" id="sr_finish1" name="sr_finish1">
						{foreach item=sr_finish key=count from=$a_sr_finish}
							{if $sr_finish.sr_finish_name eq $sr_finish_name}
								{assign var=sr_finish_selected value="selected"}
							{else}
								{assign var=sr_finish_selected value=""}
							{/if}
							<OPTION value="{$sr_finish.sr_finish_name}" {$sr_finish_selected}>{$sr_finish.sr_finish_name}</OPTION>
						{/foreach}
					</select>
				</td>

				<td class="crmTableRow small lineOnTop">
					<select class="small" id="sr_size_mm1" name="sr_size_mm1">
						{foreach item=sr_size_mm key=count from=$a_sr_size_mm}
							{if $sr_size_mm.sr_size_mm_name eq $sr_size_mm_name}
								{assign var=sr_size_mm_selected value="selected"}
							{else}
								{assign var=sr_size_mm_selected value=""}
							{/if}
							<OPTION value="{$sr_size_mm.sr_size_mm_name}" {$sr_size_mm_selected}>{$sr_size_mm.sr_size_mm_name}</OPTION>
						{/foreach}
					</select>
				</td>


				<td align="left" class="crmTableRow small lineOnTop">
					<select class="small" id="sr_thickness_mm1" name="sr_thickness_mm1">
						{foreach item=sr_thickness_mm key=count from=$a_sr_thickness_mm}
							{if $sr_thickness_mm.sr_thickness_mm_name eq $sr_thickness_mm_name}
								{assign var=sr_thickness_mm_selected value="selected"}
							{else}
								{assign var=sr_thickness_mm_selected value=""}
							{/if}
							<OPTION value="{$sr_thickness_mm.sr_thickness_mm_name}" {$sr_thickness_mm_selected}>{$sr_thickness_mm.sr_thickness_mm_name}</OPTION>
						{/foreach}
					</select>
					<input type="hidden" value="amount" id="discount_type1" name="discount_type1" />
					<input type="hidden" value="" id="discount_percentage1" name="discount_percentage1" />
					<input type="hidden" value="0" id="discount_amount1" name="discount_amount1" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="sr_product_unit1" name="sr_product_unit1" type="text" class="detailedViewTextBox sr_product_unit" onfocus="this.className='detailedViewTextBoxOn sr_product_unit'" onblur="this.className='detailedViewTextBox sr_product_unit';settotalnoofrows();" /><br>
					
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="amount_of_sample1" name="amount_of_sample1" type="text" class="detailedViewTextBox amount_of_sample" onfocus="this.className='detailedViewTextBoxOn amount_of_sample'" onblur="this.className='detailedViewTextBox qty_act';calcTotalamountsample();settotalnoofrows();" value="0"/><br>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="amount_of_purchase1" name="amount_of_purchase1" type="text" class="detailedViewTextBox amount_of_purchase" onfocus="this.className='detailedViewTextBoxOn amount_of_purchase'" onblur="this.className='detailedViewTextBox amount_of_purchase';calcTotalamount();settotalnoofrows();" value="0"/>
				</td>
				
			</tr>
			<!-- Product Details First row - Ends -->
		</table>
		<!-- Upto this has been added for form the first row. Based on these above we should form additional rows using script -->
		<table width="100%"  border="0" align="center" cellpadding="8" cellspacing="0" class="crmTable">
			<tr>
				<td width=5% valign="top" align="right"></td>
				<td width=35% ></td>
				<td width="10%"></td>
				<td width="10%"></td>
				<td width=10%></td>
				<td width=10%><strong>Total</strong></td>
				<td width=10% align="center"><input id="total_amount_of_sample" name="total_amount_of_sample" type="text" class="detailedViewTextBox" value="0" readonly="" style="background-color: #ccc" /></td>
				<td width="10%" align="center"><input id="total_amount_of_purchase" name="total_amount_of_purchase" type="text" class="detailedViewTextBox" value="0" style="background-color: #ccc" readonly=""/></td>
			</tr>
		</table>

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