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
<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<!-- Added to display the Product Details -->
<script type="text/javascript">
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
  <td colspan="6" align="left">

	<table width="100%"  border="0" align="center" cellpadding="9" cellspacing="0" class="crmTable" id="proTab">
	   	<tr>	   	
			<td colspan="11" class="dvInnerHeader">
			<b>{$APP.LBL_ITEM_DETAILS}</b>	  
			<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
			<input type="hidden" value="" id="inventory_currency" />
		  	<input type="hidden" value="" id="taxtype" />	  
		</tr>

	   <!-- Header for the Product Details -->
	   	<tr valign="top">
			<td width=4% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
			<td width=24% class="lvtCol"><font color='red'>* </font><b>รายการสินค้า</b></td>
			<td width=8% class="lvtCol" align="center"><strong>Brand</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>จำนวนแผ่น/กล่อง</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>สถานะของสินค้า</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Showroom</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>List Price</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Normal</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 1</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 2</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 3</strong></td>
		</tr>
			
	   	{foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
			{assign var="deleted" value="deleted"|cat:$row_no}
			{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
			{assign var="productName" value="productName"|cat:$row_no}
			{assign var="comment" value="comment"|cat:$row_no}
			{assign var="productDescription" value="productDescription"|cat:$row_no}
			{assign var="entityType" value=$data.$entityIdentifier}
			{assign var="listPrice" value="listPrice"|cat:$row_no}

			{assign var="rel_module" value="rel_module"|cat:$row_no}

			{assign var="product_brand" value="product_brand"|cat:$row_no}
			{assign var="product_weight_per_box" value="product_weight_per_box"|cat:$row_no}
			{assign var="productstatus" value="productstatus"|cat:$row_no}
			{assign var="pricelist_showroom" value="pricelist_showroom"|cat:$row_no}
			{assign var="listprice_project" value="listprice_project"|cat:$row_no}
			{assign var="pricelist_nomal" value="pricelist_nomal"|cat:$row_no}
			{assign var="pricelist_first_tier" value="pricelist_first_tier"|cat:$row_no}
			{assign var="pricelist_second_tier" value="pricelist_second_tier"|cat:$row_no}
			{assign var="pricelist_third_tier" value="pricelist_third_tier"|cat:$row_no}

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
				<table width="100%"  border="0" cellspacing="0" cellpadding="1">
					<tr>
						<td class="small" valign="top">
							<input type="text" id="{$productName}" name="{$productName}" value="{$data.$productName}" class="small" style="width: 70%;" readonly />
							<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
							<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$data.$rel_module}" />
							&nbsp;
							
							<img id="searchIcon{$row_no}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no}')" />
						</td>
					</tr>
					<tr>
						<td class="small" id="setComment">
							<textarea id="{$comment}" name="{$comment}" class=detailedViewTextBox user-success style="width:70%;height:40px">{$data.$comment}</textarea>
							<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$comment}').value=''"; style="cursor:pointer;" />
						</td>
					</tr>
				</table>
			</td>

			
			<td class="crmTableRow small lineOnTop" valign="top" align="center">
				<input data-rowno="{$row_no}" id="{$product_brand}" name="{$product_brand}" type="text" class="detailedViewTextBox product_brand user-success product_brand" onfocus="this.className='detailedViewTextBoxOn product_brand';" onblur="this.className='detailedViewTextBox product_brand';"  value="{$data.$product_brand}" readonly="readonly" style="background-color:#CCC;border: 1px solid #999;"/>
			</td>

		    <td class="crmTableRow small lineOnTop" align="center">
		    	<input data-rowno="{$row_no}" id="{$product_weight_per_box}" name="{$product_weight_per_box}" type="text" class="detailedViewTextBox product_weight_per_box user-success product_weight_per_box" onfocus="this.className='detailedViewTextBoxOn product_weight_per_box';" onblur="this.className='detailedViewTextBox product_weight_per_box';"  value="{$data.$product_weight_per_box}" readonly="readonly" style="background-color:#CCC;border: 1px solid #999;"/>
			</td>
			
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$productstatus}" name="{$productstatus}" type="text" class="detailedViewTextBox productstatus user-success productstatus" onfocus="this.className='detailedViewTextBoxOn productstatus';" onblur="this.className='detailedViewTextBox productstatus';"  value="{$data.$productstatus}" readonly="readonly" style="background-color:#CCC;border: 1px solid #999;"/>
			</td>
			
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$pricelist_showroom}" name="{$pricelist_showroom}" type="text" class="detailedViewTextBox pricelist_showroom user-success pricelist_showroom" onfocus="this.className='detailedViewTextBoxOn pricelist_showroom';" onblur="this.className='detailedViewTextBox pricelist_showroom';"  value="{$data.$pricelist_showroom}"/>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$listprice_project}" name="{$listprice_project}" type="text" class="detailedViewTextBox listprice_project user-success listprice_project" onfocus="this.className='detailedViewTextBoxOn listprice_project';" onblur="this.className='detailedViewTextBox listprice_project';"  value="{$data.$listprice_project}"/>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$pricelist_nomal}" name="{$pricelist_nomal}" type="text" class="detailedViewTextBox pricelist_nomal user-success pricelist_nomal" onfocus="this.className='detailedViewTextBoxOn pricelist_nomal';" onblur="this.className='detailedViewTextBox pricelist_nomal';"  value="{$data.$pricelist_nomal}"/>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$pricelist_first_tier}" name="{$pricelist_first_tier}" type="text" class="detailedViewTextBox pricelist_first_tier user-success pricelist_first_tier" onfocus="this.className='detailedViewTextBoxOn pricelist_first_tier';" onblur="this.className='detailedViewTextBox pricelist_first_tier';"  value="{$data.$pricelist_first_tier}"/>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$pricelist_second_tier}" name="{$pricelist_second_tier}" type="text" class="detailedViewTextBox pricelist_second_tier user-success pricelist_second_tier" onfocus="this.className='detailedViewTextBoxOn pricelist_second_tier';" onblur="this.className='detailedViewTextBox pricelist_second_tier';"  value="{$data.$pricelist_second_tier}"/>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$pricelist_third_tier}" name="{$pricelist_third_tier}" type="text" class="detailedViewTextBox pricelist_third_tier user-success pricelist_third_tier" onfocus="this.className='detailedViewTextBoxOn pricelist_third_tier';" onblur="this.className='detailedViewTextBox pricelist_third_tier';"  value="{$data.$pricelist_third_tier}"/>
			</td>

	   	</tr>
	   	<!-- Product Details First row - Ends -->
	   	{/foreach}
	</table>

	<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	   <!-- Add Product Button -->
	   	<tr>
			<td colspan="3">
				<button title="Add Products" class="crmbutton small save" onclick="fnAddProductRow('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item
				</button>
			</td>
	   	</tr>
	</table>

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="{$row_no}">
	<input type="hidden" name="total" id="total" value="">
	</td>
</tr>
<!-- Upto this Added to display the Product Details -->


