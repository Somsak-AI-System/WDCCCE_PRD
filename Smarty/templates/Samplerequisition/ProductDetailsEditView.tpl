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
<!-- Added to display the Product Details -->
<script type="text/javascript">
if(!e)
	window.captureEvents(Event.MOUSEMOVE);

  
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

		<table width="100%"  border="0" align="center" cellpadding="9" cellspacing="0" class="crmTable" id="proTab">
			<tr>
				{if $MODULE neq 'PurchaseOrder'}
				<td colspan="2" class="dvInnerHeader">
				{else}
				<td colspan="10" class="dvInnerHeader">
				{/if}
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

			<tr valign="top">
				<td width=5% valign="top" class="lvtCol" align="right"><b>เครื่องมือ</b></td>
				<td width=35% class="lvtCol"><b>รายการสินค้า</b></td>
				<td width="10%" align="center" class="lvtCol"><strong>ชนิดผิว</strong></td>
				<td width=10% class="lvtCol" align="center"><strong>ขนาด (มม.)</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>ความหนา (มม.)</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>หน่วยนับ</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>จำนวน (แผ่น)</strong></td>
				<td width="10%" align="center" class="lvtCol"><strong>จำนวนที่คาดว่าจะใช้</strong></td>
			</tr>

				{foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
				{assign var="deleted" value="deleted"|cat:$row_no}
				{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
				{assign var="productName" value="productName"|cat:$row_no}
				{assign var="comment" value="comment"|cat:$row_no}

				{assign var="sr_finish" value="sr_finish"|cat:$row_no}
				{assign var="sr_size_mm" value="sr_size_mm"|cat:$row_no}
				{assign var="sr_thickness_mm" value="sr_thickness_mm"|cat:$row_no}
				{assign var="sr_product_unit" value="sr_product_unit"|cat:$row_no}
				{assign var="amount_of_sample" value="amount_of_sample"|cat:$row_no}
				{assign var="amount_of_purchase" value="amount_of_purchase"|cat:$row_no}
				
				
			<tr id="row{$row_no}" valign="top">

				<!-- column 1 - delete link - starts -->
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

				<!-- column 2 - Product Name - starts -->
				<td class="crmTableRow small lineOnTop">
					<!-- Product Re-Ordering Feature Code Addition Starts -->
					<input type="hidden" name="hidtax_row_no{$row_no}" id="hidtax_row_no{$row_no}" value="{$tax_row_no}"/>
					<!-- Product Re-Ordering Feature Code Addition ends -->
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
						<tr>
							<td class="small" valign="top">
								<span id="{$business_code}" name="{$business_code}" style="color:#C0C0C0;font-style:italic;">{$data.$business_code}</span>
								<div id="productCodeView{$row_no}" style="font-weight:bold;"></div>
								<textarea id="{$productName}" name="{$productName}" readonly="readonly" class="detailedViewTextBox user-success" style="width:90%;height:40px">{$data.$productName}</textarea>
								<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
								<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
								<img id="searchIcon{$row_no}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no}')" />
								
							</td>
						</tr>
						<tr>
							<td class="small">
								<input type="hidden" value="{$data.$subproduct_ids}" id="{$subproduct_ids}" name="{$subproduct_ids}" />
								<span id="{$subprod_names}" name="{$subprod_names}"  style="color:#C0C0C0;font-style:italic;">{$data.$subprod_names}</span>
							</td>
						</tr>
						<tr>
							<td class="small" id="setComment">
								<textarea id="{$comment}" name="{$comment}" class=detailedViewTextBox user-success style="width:90%;height:40px">{$data.$comment}</textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$comment}').value=''"; style="cursor:pointer;" />
							</td>
						</tr>
					</table>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<select data-rowno="{$row_no}" class="small" id="{$sr_finish}" name="{$sr_finish}">
						{foreach item=sr_finish_data key=count from=$a_sr_finish}
							{if $sr_finish_data.sr_finish_name eq $data.$sr_finish}
								{assign var=sr_finish_selected value="selected"}
							{else}
								{assign var=sr_finish_selected value=""}
							{/if}
							<OPTION value="{$sr_finish_data.sr_finish_name}" {$sr_finish_selected}>{$sr_finish_data.sr_finish_name}</OPTION>
						{/foreach}
					</select>
				</td>

				<td class="crmTableRow small lineOnTop">
					<select data-rowno="{$row_no}" class="small" id="{$sr_size_mm}" name="{$sr_size_mm}">
						{foreach item=sr_size_mm_data key=count from=$a_sr_size_mm}
							{if $sr_size_mm_data.sr_size_mm_name eq $data.$sr_size_mm}
								{assign var=sr_size_mm_selected value="selected"}
							{else}
								{assign var=sr_size_mm_selected value=""}
							{/if}
							<OPTION value="{$sr_size_mm_data.sr_size_mm_name}" {$sr_size_mm_selected}>{$sr_size_mm_data.sr_size_mm_name}</OPTION>
						{/foreach}
					</select>
				</td>
				
				<td class="crmTableRow small lineOnTop" valign="top">
					<select data-rowno="{$row_no}" class="small" id="{$sr_thickness_mm}" name="{$sr_thickness_mm}">
						{foreach item=sr_thickness_mm_data key=count from=$a_sr_thickness_mm}
							{if $sr_thickness_mm_data.sr_thickness_mm_name eq $data.$sr_thickness_mm}
								{assign var=sr_thickness_mm_selected value="selected"}
							{else}
								{assign var=sr_thickness_mm_selected value=""}
							{/if}
							<OPTION value="{$sr_thickness_mm_data.sr_thickness_mm_name}" {$sr_thickness_mm_selected}>{$sr_thickness_mm_data.sr_thickness_mm_name}</OPTION>
						{/foreach}
					</select>
					<input type="hidden" value="{$data.$discount_type}" id="discount_type{$row_no}" name="discount_type{$row_no}" />
					<input type="hidden" value="{$data.$discount_percentage}" id="discount_percentage{$row_no}" name="discount_percentage{$row_no}" />
					<input type="hidden" value="{$data.$discount_amount}" id="discount_amount{$row_no}" name="discount_amount{$row_no}" />
				</td>
				<td class="crmTableRow small lineOnTop" valign="top">
					<input data-rowno="{$row_no}" id="{$sr_product_unit}" name="{$sr_product_unit}" type="text" class="detailedViewTextBox sr_product_unit" style="width:100%" onfocus="this.className='detailedViewTextBoxOn qty_act'" onblur="this.className='detailedViewTextBox sr_product_unit';settotalnoofrows();" value="{$data.$sr_product_unit}"/>
				</td>

				<td class="crmTableRow small lineOnTop" valign="top">
					<input data-rowno="{$row_no}" id="{$amount_of_sample}" name="{$amount_of_sample}" type="text" class="detailedViewTextBox amount_of_sample " style="width:100%" onfocus="this.className='detailedViewTextBoxOn amount_of_sample'" onblur="this.className='detailedViewTextBox amount_of_sample';calcTotalamountsample();settotalnoofrows();" value="{$data.$amount_of_sample}"/>
				</td>

				<td class="crmTableRow small lineOnTop" valign="top">
					<input data-rowno="{$row_no}" id="{$amount_of_purchase}" name="{$amount_of_purchase}" type="text" class="detailedViewTextBox amount_of_purchase" style="width:100%" onfocus="this.className='detailedViewTextBoxOn amount_of_purchase'" onblur="this.className='detailedViewTextBox amount_of_purchase';calcTotalamount();settotalnoofrows();" value="{$data.$amount_of_purchase}"/>

				</td>

			</tr>

			{/foreach}
		</table>
		{assign var="FINAL" value=$ASSOCIATEDPRODUCTS.1.final_details}
		<table width="100%"  border="0" align="center" cellpadding="8" cellspacing="0" class="crmTable">
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

