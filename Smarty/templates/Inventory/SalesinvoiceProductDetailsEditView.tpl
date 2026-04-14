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
<!-- <script type="text/javascript" src="include/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script> -->
<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">

<script type="text/javascript">
	jQuery.noConflict();
</script>

<script type="text/javascript" src="include/js/Inventory_SalesinvoiceProductDetails.js?v={$smarty.now}"></script>
<script type="text/javascript" src="include/js/Inventory_{$MODULE}.js"></script>

<script>
	jQuery(document).ready(function(){ldelim}
		jQuery('form').on('keyup keypress', function(e){ldelim}
            var keyCode = e.keyCode || e.which;
			if(keyCode == 13 && !jQuery(e.target).is("textarea")){ldelim}
                e.preventDefault();
                console.log("ENTER PREVENTED");
                return;
			{rdelim}
		{rdelim});
	{rdelim});
</script>
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
		document.getElementById("discount_div_title_final").innerHTML = '<b>{$APP.LABEL_SET_DISCOUNT_FOR_COLON} '+document.getElementById("afterdis_final").innerHTML+'</b>';
	{rdelim}
	else if(mode == 'sh_tax_div_title')
	{ldelim}
		document.getElementById("sh_tax_div_title").innerHTML = '<b>{$APP.LABEL_SET_SH_TAX_FOR_COLON} '+document.getElementById("shipping_handling_charge").value+'</b>';
	{rdelim}
	else if(mode == 'group_tax_div_title')
	{ldelim}
		var total_after_bill_discount = eval(document.getElementById("total_after_bill_discount").innerHTML);
		document.getElementById("group_tax_div_title").innerHTML = '<b>Set Group Vat for : '+total_after_bill_discount+'</b>';
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


<tr><td colspan="4" align="left">
<div id="dialog" style="display:none;">Dialog Content.</div>
<table width="100%"  border="0" align="center" cellpadding="12" cellspacing="0" class="crmTable" id="proTab">
   <tr>
   	<td colspan="6" class="dvInnerHeader">
		<b>{$APP.LBL_ITEM_DETAILS}</b>
	</td>
	<td class="dvInnerHeader" align="center" colspan="4">
		<b>Price type {$pricetype}</b>&nbsp;&nbsp;
		
		{if $pricetype eq 'Exclude Vat'}
			{assign var=pricetype_exclud value="selected"}
		{elseif $pricetype eq 'Include Vat'}
			{assign var=pricetype_include value="selected"}
		{/if}
		<select class="small" id="pricetype" name="pricetype" onchange="calcTotal();">
			<option value="Exclude Vat" {$pricetype_exclud}>Exclude Vat</option>
			<option value="Include Vat" {$pricetype_include} >Include Vat</option>
		</select>
	</td>
	<td class="dvInnerHeader" align="center" colspan="2" style="display:none;">
		<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
		<b>{$APP.LBL_CURRENCY}</b>&nbsp;&nbsp;
		{*{if $MODULE eq 'Quotes' || $MODULE eq 'Salesinvoice'}*}
        	{*<input type="hidden" id="inventory_currency" name="inventory_currency" value="1"> <b>{$CURRENCIES_LIST.0.currencylabel|@getTranslatedCurrencyString} ({$CURRENCIES_LIST.0.currencysymbol})</b>*}
        {*{else}*}
			<select class="small" id="inventory_currency" name="inventory_currency"> <!--onchange="updatePrices();"-->
			{foreach item=currency_details key=count from=$CURRENCIES_LIST}
				{if $currency_details.curid eq $INV_CURRENCY_ID}
					{assign var=currency_selected value="selected"}
				{else}
					{assign var=currency_selected value=""}
				{/if}
				<OPTION value="{$currency_details.curid}" {$currency_selected}>{$currency_details.currencylabel|@getTranslatedCurrencyString} ({$currency_details.currencysymbol})</OPTION>
			{/foreach}
			</select>
		{*{/if}	*}
	</td>
	<td class="dvInnerHeader" align="center" colspan="2">
		<b>{$APP.LBL_TAX_MODE}</b>&nbsp;&nbsp;
		
		{if $ASSOCIATEDPRODUCTS.1.final_details.taxtype eq 'group'}
			{assign var="group_selected" value="selected"}
		{else}
			{assign var="individual_selected" value="selected"}
		{/if}
		{if $MODULE eq 'Quotes' || $MODULE eq 'Salesinvoice'}
			<input type="hidden" id="taxtype" name="taxtype" value="group"><b>{$APP.LBL_GROUP}</b>
		{else}
		<select class="small" id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
			<OPTION value="individual" {$individual_selected}>{$APP.LBL_INDIVIDUAL}</OPTION>
			<OPTION value="group" {$group_selected}>{$APP.LBL_GROUP}</OPTION>
		</select>
		{/if}
	</td>
	<td class="dvInnerHeader" align="center" style ="display:none">&nbsp</td>
   </tr>

   <!-- Header for the Product Details -->
    <tr valign="top">
	   	<td width=5% valign="top" class="lvtCol" align="center"><b>ลำดับที่</b></td>
		<td width=20% class="lvtCol"><font color='red'>* </font><b>รายการสินค้า</b></td>
		<td width=5% class="lvtCol" align="center"><strong>ขนาดบรรจุแผ่น</strong></td>
		<td width=5% class="lvtCol" align="center"><strong>ขนาดบรรจุ ตรม</strong></td>
		<td width=5% class="lvtCol" align="center"><strong>จำนวน</strong></td>
		<td width=5% class="lvtCol" align="center"><strong>หน่วยขาย</strong></td>
		<td width=5% class="lvtCol" align="center"><strong>จำนวนแผ่น</strong></td>
		<td width=5% class="lvtCol" align="center"><strong>จำนวน ตรม.</strong></td>
		<td width=5% class="lvtCol" align="center"><b>ราคา Price List</b></td>
		<td width=5% class="lvtCol" align="center"><b>ราคาขายไม่รวมค่าขนส่ง / ราคาค่าขนส่ง</b></td>
		<td width=5% class="lvtCol" align="center"><b>ส่วนลด</b></td>
		<td width=7% nowrap class="lvtCol" align="center"><b>ราคารวม</b></td>
	</tr>

	{*<td width=8% class="lvtCol" align="center"><strong>Price List Last</strong></td>*}

   {foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
	{assign var="deleted" value="deleted"|cat:$row_no}
	{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
	{assign var="hdn_cal_total" value="hdn_cal_total"|cat:$row_no}
	{assign var="hdn_cal_discount" value="hdn_cal_discount"|cat:$row_no}
	{assign var="productName" value="productName"|cat:$row_no}
	{assign var="comment" value="comment"|cat:$row_no}

	{assign var="pricelist_type" value="pricelist_type"|cat:$row_no}
	{assign var="pricelist_type_name" value=$data.$pricelist_type}

    {assign var="product_price_type" value="product_price_type"|cat:$row_no}
	{assign var="product_price_type_name" value=$data.$product_price_type}

	{assign var="package_size_sheet_per_box" value="package_size_sheet_per_box"|cat:$row_no}
	{assign var="package_size_sqm_per_box" value="package_size_sqm_per_box"|cat:$row_no}
	{assign var="box_quantity" value="box_quantity"|cat:$row_no}
	{assign var="sales_unit" value="sales_unit"|cat:$row_no}
	{assign var="sheet_quantity" value="sheet_quantity"|cat:$row_no}
	{assign var="sqm_quantity" value="sqm_quantity"|cat:$row_no}
	{assign var="regular_price" value="regular_price"|cat:$row_no}
	{assign var="product_discount" value="product_discount"|cat:$row_no}

	{assign var="competitor_brand" value="competitor_brand"|cat:$row_no}
	{assign var="competitor_brand_name" value=$data.$competitor_brand}
	{assign var="competitor_price" value="competitor_price"|cat:$row_no}

	{assign var="compet_brand_in_proj" value="compet_brand_in_proj"|cat:$row_no}
	{assign var="compet_brand_in_proj_name" value=$data.$compet_brand_in_proj}
	{assign var="compet_brand_in_proj_price" value="compet_brand_in_proj_price"|cat:$row_no}

	{assign var="product_unit" value="product_unit"|cat:$row_no}
	{assign var="product_cost_avg" value="product_cost_avg"|cat:$row_no}
	{assign var="selling_price" value="selling_price"|cat:$row_no}
	{assign var="selling_price_product" value="selling_price_product"|cat:$row_no}
	{assign var="selling_price_delivery" value="selling_price_delivery"|cat:$row_no}

	{assign var="productDescription" value="productDescription"|cat:$row_no}
	{assign var="qtyInStock" value="qtyInStock"|cat:$row_no}
	{assign var="test_box" value="test_box"|cat:$row_no}
	{assign var="qty" value="qty"|cat:$row_no}
	{assign var="listPrice" value="listPrice"|cat:$row_no}
	{assign var="listprice_exc" value="listprice_exc"|cat:$row_no}
	{assign var="listprice_inc" value="listprice_inc"|cat:$row_no}
	{assign var="productTotal" value="productTotal"|cat:$row_no}
	{assign var="business_code" value="business_code"|cat:$row_no}
	{assign var="subproduct_ids" value="subproduct_ids"|cat:$row_no}
	{assign var="subprod_names" value="subprod_names"|cat:$row_no}
	{assign var="entityIdentifier" value="entityType"|cat:$row_no}
	{assign var="entityType" value=$data.$entityIdentifier}

	{assign var="discount_type" value="discount_type"|cat:$row_no}
	{assign var="discount_percent" value="discount_percent"|cat:$row_no}
	{assign var="checked_discount_percent" value="checked_discount_percent"|cat:$row_no}
	{assign var="style_discount_percent" value="style_discount_percent"|cat:$row_no}
	{assign var="discount_amount" value="discount_amount"|cat:$row_no}
	{assign var="checked_discount_amount" value="checked_discount_amount"|cat:$row_no}
	{assign var="style_discount_amount" value="style_discount_amount"|cat:$row_no}
	{assign var="checked_discount_zero" value="checked_discount_zero"|cat:$row_no}

	{assign var="discountTotal" value="discountTotal"|cat:$row_no}
	{assign var="totalAfterDiscount" value="totalAfterDiscount"|cat:$row_no}
	{assign var="taxTotal" value="taxTotal"|cat:$row_no}
	{assign var="netPrice" value="netPrice"|cat:$row_no}

    {assign var="pack_size" value="pack_size"|cat:$row_no}
	{assign var="uom" value="uom"|cat:$row_no}
    {assign var="price_list_std" value="price_list_std"|cat:$row_no}
    {assign var="price_list_inv" value="price_list_inv"|cat:$row_no}
    {assign var="productcode" value="productcode"|cat:$row_no}
	{assign var="pricelistid" value="pricelistid"|cat:$row_no}
	{assign var="lineitemid" value="lineitemid"|cat:$row_no}

	<!--  [CR010] เก็บ log การอัพเดท pricelist -->
	{assign var="tier_type" value="tier_type"|cat:$row_no}
	{assign var="product_dummy" value="product_dummy"|cat:$row_no}

   <tr id="row{$row_no}" valign="top">

	<!-- column 1 - delete link - starts -->
	<td  class="crmTableRow small lineOnTop" align="center">
		{if $MODULE neq 'Salesinvoice'}
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
		{else}
			<img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="deleteRow('{$MODULE}',{$row_no},'{$IMAGE_PATH}')">
			<input type="hidden" id="{$deleted}" name="{$deleted}" value="0">
		{/if}
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
					<div id="productCodeView{$row_no}" style="font-weight:bold;">{$data.$productcode}</div>
					<textarea id="{$productName}" name="{$productName}" class="detailedViewTextBox user-success" style="width:90%;height:40px;{if $MODULE eq 'Salesinvoice'}background-color: #CCC;{/if}" {if $MODULE eq 'Salesinvoice'}readonly="readonly"{/if}>{$data.$productName}</textarea>
					<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
					<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
					<input type="hidden" id="productcode{$row_no}" name="productcode{$row_no}" value="{$data.$productcode}" />
					<input type="hidden" id="product_dummy{$row_no}" name="product_dummy{$row_no}" value="{$data.$product_dummy}" />

					<input type="hidden" id="hdn_cal_total{$row_no}" name="hdn_cal_total{$row_no}" value="{$data.$hdn_cal_total}" />
					<input type="hidden" id="hdn_cal_discount{$row_no}" name="hdn_cal_discount{$row_no}" value="{$data.$hdn_cal_discount}" />
					<input type="hidden" id="hdn_cal_inline_total_price{$row_no}" name="hdn_cal_inline_total_price{$row_no}" value="{$data.$hdn_cal_inline_total_price}" />
						{if $MODULE neq 'Salesinvoice'}
						<img id="searchIcon{$row_no}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no}')" />
						{/if}
						<!-- <img id="searchIcon{$row_no}" title="Service" src="{'services.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="servicePickList(this,'Service','{$row_no}')" />
						<img id="searchIcon{$row_no}" title="Sparepart" src="{'services.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="sparepartPickList(this,'Sparepart','{$row_no}')" /> -->
				</td>
			</tr>
			<tr>
				<td class="small">
					{if $MODULE neq 'Salesinvoice'}
					<select data-rowno="{$row_no}" class="small" id="{$product_price_type}" name="{$product_price_type}"  style="margin-bottom:5px;">
					    <OPTION value="" {$product_price_type_selected}>-- Select Price list --</OPTION>
						{foreach item=product_type key=count from=$a_product_price_type}
							{if $product_type.product_price_type_name eq $product_price_type_name}
								{assign var=product_price_type_selected value="selected"}
							{else}
								{assign var=product_price_type_selected value=""}
							{/if}
							<OPTION value="{$product_type.product_price_type_name}" {$product_price_type_selected}>{$product_type.product_price_type_name}</OPTION>
						{/foreach}
					</select>
					{else}
						{$product_price_type_name}
					{/if}
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
					<textarea id="{$comment}" name="{$comment}" class=detailedViewTextBox user-success style="width:90%;height:40px;{if $MODULE eq 'Salesinvoice'}background-color: #CCC;{/if}" {if $MODULE eq 'Salesinvoice'}readonly="readonly"{/if}>{$data.$comment}</textarea>
					{if $MODULE neq 'Salesinvoice'}<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$comment}').value=''"; style="cursor:pointer;" />{/if}
				</td>
			</tr>
		</table>
	</td>
	<!-- column 2 - Product Name - ends -->

	{if $MODULE neq 'Quotes' && $MODULE neq 'Salesinvoice'}
    <td class="crmTableRow small lineOnTop" valign="top">
    	<span id="{$qtyInStock}">{$data.$qtyInStock}</span>
    </td>
    {/if}

			<td class="crmTableRow small lineOnTop" align="center">
		   		<input data-rowno="{$row_no}" id="{$package_size_sheet_per_box}" name="{$package_size_sheet_per_box}" type="text" class="small detailedViewTextBox package_size_sheet_per_box" onfocus="this.className='package_size_sheet_per_box detailedViewTextBoxOn ';" onblur="this.className='package_size_sheet_per_box detailedViewTextBox ';"  value="{$data.$package_size_sheet_per_box}" readonly="readonly" style="background-color: #CCC;" />
		   	</td>
		   	<td class="crmTableRow small lineOnTop" align="center">
		   		<input data-rowno="{$row_no}" id="{$package_size_sqm_per_box}" name="{$package_size_sqm_per_box}" type="text" class="small detailedViewTextBox package_size_sqm_per_box" onfocus="this.className='package_size_sqm_per_box detailedViewTextBoxOn ';" onblur="this.className='package_size_sqm_per_box detailedViewTextBox ';"  value="{$data.$package_size_sqm_per_box}" readonly="readonly" style="background-color: #CCC;"/>
		   	</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$box_quantity}" name="{$box_quantity}" type="text" class="detailedViewTextBox small box_quantity" onfocus="this.className='box_quantity detailedViewTextBoxOn'" onBlur="this.className='selling_price detailedViewTextBox';calSheetQuantity(this,'{$row_no}'); calSqmQuantity(this,'{$row_no}');setDiscount(this,'{$row_no}'); callTaxCalc('{$row_no}');calcTotal();" value="{$data.$box_quantity}" />
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$sales_unit}" name="{$sales_unit}" type="text" class="detailedViewTextBox small sales_unit" onfocus="this.className='sales_unit detailedViewTextBoxOn'" value="{$data.$sales_unit}" readonly="readonly" style="background-color: #CCC;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$sheet_quantity}" name="{$sheet_quantity}" type="text" class="detailedViewTextBox small sheet_quantity" onfocus="this.className='sheet_quantity detailedViewTextBoxOn'" value="{$data.$sheet_quantity}" readonly="readonly" style="background-color: #CCC;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$sqm_quantity}" name="{$sqm_quantity}" type="text" class="detailedViewTextBox small sqm_quantity" onfocus="this.className='sqm_quantity detailedViewTextBoxOn'" value="{$data.$sqm_quantity}" readonly="readonly" style="background-color: #CCC;"/>
			</td>

			<td class="crmTableRow small lineOnTop" style="white-space: nowrap;" align="center">
				<input type="hidden" id="pricelist{$row_no}" name="pricelist{$row_no}" value="{$data.$pricelistid}" />
				<input type="hidden" id="tier_type{$row_no}" name="tier_type{$row_no}" value="{$data.$tier_type}" />
				<input data-rowno="{$row_no}" id="{$regular_price}" name="{$regular_price}" type="text" class="detailedViewTextBox small regular_price" onfocus="this.className='regular_price detailedViewTextBoxOn'" onBlur="this.className='regular_price detailedViewTextBox';setDiscount(this,'{$row_no}'); callTaxCalc({$row_no});calcTotal();" value="{$data.$regular_price}" readonly="readonly" style="display: inline-block; vertical-align: middle; width:80px; background-color: #CCC;"/>

				{if $MODULE neq 'Salesinvoice'}
				<a href="javascript:void(0);" onclick="popupPricelist(this,'{$row_no}');" style="display: inline-block; vertical-align: middle; padding-left: 10px;">
					<img src="{'search.gif'|@aicrm_imageurl:$THEME}" border="0" title="{$APP.LBL_SMART_SEARCH}" alt="{$APP.LBL_SMART_SEARCH}" style="cursor: pointer;"/>
				</a>
				{/if}

				<br>
				<br>

				{if $MODULE neq 'Salesinvoice'}
				<select data-rowno="{$row_no}" class="small" id="{$pricelist_type}" name="{$pricelist_type}"  style="margin-bottom:5px;" onchange="calcTotal();">
					{foreach item=product_type key=count from=$a_pricelist_type}
						{if $product_type.pricelist_type_name eq $pricelist_type_name}
							{assign var=pricelist_type_selected value="selected"}
						{else}
							{assign var=pricelist_type_selected value=""}
						{/if}
						<OPTION value="{$product_type.pricelist_type_name}" {$pricelist_type_selected}>{$product_type.pricelist_type_name}</OPTION>
					{/foreach}
				</select>
				{else}
					<input type='text' readonly id="{$pricelist_type}" name="{$pricelist_type}" value='{$pricelist_type_name}' />
				{/if}
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input type="hidden" id="{$selling_price}" name="{$selling_price}" value="{$data.$selling_price}"/>
				<div class="small" style="margin-bottom:4px;"><label class="small">ราคาขายไม่รวมค่าขนส่ง</label><br/>
				<input data-rowno="{$row_no}" id="{$selling_price_product}" name="{$selling_price_product}" type="text" class="small selling_price_product" style="width:70px;{if $MODULE eq 'Salesinvoice'}background-color:#ccc{/if}" {if $MODULE eq 'Salesinvoice'}readonly{/if} onfocus="this.className='selling_price_product detailedViewTextBoxOn'" onBlur="this.className='selling_price_product detailedViewTextBox';if(window.syncSellingPriceFromProductDelivery) window.syncSellingPriceFromProductDelivery('{$row_no}');setDiscount(this,'{$row_no}'); callTaxCalc({$row_no});calcTotal();" onkeyup="if(window.syncSellingPriceFromProductDelivery) window.syncSellingPriceFromProductDelivery('{$row_no}');" value="{$data.$selling_price_product}"/></div>
				<div class="small"><label class="small">ราคาค่าขนส่ง</label><br/>
				<input data-rowno="{$row_no}" id="{$selling_price_delivery}" name="{$selling_price_delivery}" type="text" class="small selling_price_delivery" style="width:70px;{if $MODULE eq 'Salesinvoice'}background-color:#ccc{/if}" {if $MODULE eq 'Salesinvoice'}readonly{/if} onfocus="this.className='selling_price_delivery detailedViewTextBoxOn'" onBlur="this.className='selling_price_delivery detailedViewTextBox';if(window.syncSellingPriceFromProductDelivery) window.syncSellingPriceFromProductDelivery('{$row_no}');setDiscount(this,'{$row_no}'); callTaxCalc({$row_no});calcTotal();" onkeyup="if(window.syncSellingPriceFromProductDelivery) window.syncSellingPriceFromProductDelivery('{$row_no}');" value="{$data.$selling_price_delivery}"/></div>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="{$row_no}" id="{$product_discount}" name="{$product_discount}" type="text" class="small product_discount" style="width:70px;background-color: #CCC;"  onfocus="this.className='product_discount detailedViewTextBoxOn'" onBlur="this.className='product_discount  detailedViewTextBox'; setDiscount(this,'{$row_no}'); callTaxCalc({$row_no});calcTotal();" value="{$data.$product_discount}" readonly="readonly" />
			</td>
			
			<td class="crmTableRow small lineOnTop" align="right"><span id="netPrice{$row_no}"><b>&nbsp;</b></span></td>
			
			<td class="crmTableRow small lineOnTop" align="right" colspan="2" style ="display:none">
		    	<span id = "productTotal{$row_no}" style="visibility:hidden;" ></span>
		        <span id = "discountTotal{$row_no}" style="visibility:hidden" ></span>
		        <span id = "taxTotal{$row_no}" style="visibility:hidden" ></span>
		        <span id = "totalAfterDiscount{$row_no}" ></span>
			</td>


   	</tr>
   <!-- Product Details First row - Ends -->
   {/foreach}
</table>



<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
   <!-- Add Product Button -->
   {if $MODULE neq 'Salesinvoice'}
   <tr>
	<td colspan="3">
			<!-- <input type="button" name="Button" class="crmbutton small create" value="Add Products" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');" /> -->
			<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');displayField();" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item
			</button>

			<!-- <button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('Sparepart','{$IMAGE_PATH}');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Sparepart
			</button>

			<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('Service','{$IMAGE_PATH}');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Service
			</button> -->
            <!--
			<input type="button" name="Button" class="crmbutton small create" value="{$APP.LBL_ADD_SERVICE}" onclick="fnAddServiceRow('{$MODULE}','{$IMAGE_PATH}');" />-->
	</td>
   </tr>
   {/if}




<!--
All these details are stored in the first element in the array with the index name as final_details
so we will get that array, parse that array and fill the details
-->
{assign var="FINAL" value=$ASSOCIATEDPRODUCTS.1.final_details}
   
   <tr valign="top" style ="display:none">
		<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>Total Qty.</b></td>
		<td width="12%" id="TotalQty" class="crmTableRow small lineOnTop" align="right">0.00</td>
	</tr>

   <!-- Product Details Final Total Discount, Tax and Shipping&Hanling  - Starts -->
   <tr valign="top">
	<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>TOTAL PRICE</b></td>
	<td width="12%" id="netTotal" class="crmTableRow small lineOnTop" align="right">0.00</td>
   </tr>

   <tr valign="top" id="div_discount">
	<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small lineOnTop" align="right">
		(-)&nbsp;<b>
		<span>{$APP.LBL_DISCOUNT}</span>
	</td>
	<td class="crmTableRow small lineOnTop" align="right">
		<input class="small" type="text" id="discountTotal_final" onBlur="calcTotal();" name="discountTotal_final" size="5" value="{$FINAL.discountTotal_final}" style="width:70px; text-align: right !important; background-color:#EDEDED" readonly>
	</td>
   </tr>


   <!-- Group Tax - starts -->
   <tr valign="top" id="div_totalafterdiscount" >
     <td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;"></td>
     <td class="crmTableRow small lineOnTop" align="right">Total After Discount</td>
     <td id="afterdis_final" class="crmTableRow small lineOnTop" align="right">{$FINAL.afterdis_final}</td>
   </tr>

   <tr valign="top" id="div_discount">
	<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small lineOnTop" align="right">
		<b><a href="javascript:doNothing();" onClick="displayCoords(this,'discount_div_final','discount_final','1');calcTotal();calcGroupTax();set_tax_manual();">ส่วนลดท้ายบิล</a>

		<!-- Popup Discount DIV -->
		<div class="discountUI" id="discount_div_final">
			<input type="hidden" id="discount_type_final" name="discount_type_final" value="{$FINAL.discount_type_final}">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
			   <tr>
				<td id="discount_div_title_final" nowrap align="left" ></td>
				<td align="right"><img src="{'close.gif'|@aicrm_imageurl:$THEME}" border="0" onClick="fnHidePopDiv('discount_div_final')" style="cursor:pointer;"></td>
			   </tr>
			   <tr>
				<td align="left" class="lineOnTop"><input type="radio" name="discount_final" checked onclick="setDiscount(this,'_final'); calcGroupTax(); calcTotal();set_tax_manual();">&nbsp; {$APP.LBL_ZERO_DISCOUNT}</td>
				<td class="lineOnTop">&nbsp;</td>
			   </tr>
			   <tr>
				<td align="left"><input type="radio" name="discount_final" onclick="setDiscount(this,'_final');  calcTotal(); calcGroupTax();" {$FINAL.checked_discount_percentage_final}>&nbsp; % {$APP.LBL_OF_PRICE}</td>
				<td align="right"><input type="text" class="small" size="5" id="discount_percentage_final" name="discount_percentage_final" value="{$FINAL.discount_percentage_final}" {$FINAL.style_discount_percentage_final} onBlur="setDiscount(this,'_final'); calcGroupTax(); calcTotal();set_tax_manual();">&nbsp;%</td>
			   </tr>
			   <tr>
				<td align="left" nowrap><input type="radio" name="discount_final" onclick="setDiscount(this,'_final');  calcTotal(); calcGroupTax();" {$FINAL.checked_discount_amount_final}>&nbsp;{$APP.LBL_DIRECT_PRICE_REDUCTION}</td>
				<td align="right"><input type="text" id="discount_amount_final" name="discount_amount_final" size="5" value="{$FINAL.discount_amount_final}" {$FINAL.style_discount_amount_final} onBlur="setDiscount(this,'_final');  calcGroupTax(); calcTotal();set_tax_manual();" onkeyup="setDiscount(this,'_final');  calcGroupTax(); calcTotal();"></td>
			   </tr>
			</table>
		</div>
		<!-- End Div -->

	</td>
	<td id="bill_discount" class="crmTableRow small lineOnTop" align="right">{$FINAL.bill_discount}</td>
   </tr>

   <tr valign="top" id="div_total_after_bill_discount" >
     <td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
     <td class="crmTableRow small lineOnTop" align="right">Total หลังลดท้ายบิล</td>
     <td id="total_after_bill_discount" class="crmTableRow small lineOnTop" align="right">{$FINAL.total_after_bill_discount}</td>
   </tr>

	{if $MODULE eq 'Salesinvoice'}
   	<tr valign="top" id="div_total_after_bill_discount" >
		<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">
			<input type='hidden' id='n_deposit' name='n_deposit' />
		</td>
		<td class="crmTableRow small lineOnTop" align="right">มัดจำ</td>
		<td id="n_deposit_display" class="crmTableRow small lineOnTop" align="right"></td>
  	</tr>

   	<tr valign="top" id="div_total_after_bill_discount" >
		<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">
			<input type='hidden' id='n_after_deposit' name='n_after_deposit' />
		</td>
		<td class="crmTableRow small lineOnTop" align="right">หลังหักมัดจำ</td>
		<td id="n_after_deposit_display" class="crmTableRow small lineOnTop" align="right"></td>
   	</tr>
	{/if}

   <tr id="group_tax_row" valign="top" class="TaxShow">
	<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small lineOnTop" align="right">
		(+)&nbsp;<b><!--<a href="javascript:doNothing();" onClick="displayCoords(this,'group_tax_div','group_tax_div_title','');  calcTotal(); set_tax_manual(); calcGroupTax();" >-->Vat<!--</a>--></b>

		<!-- Pop Div For Group TAX -->
		<div class="discountUI" id="group_tax_div">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
				<tr>
					<td id="group_tax_div_title" colspan="2" nowrap align="left" ></td>
					<td align="right"><img src="{'close.gif'|@aicrm_imageurl:$THEME}" border="0" onClick="fnHidePopDiv('group_tax_div')" style="cursor:pointer;"></td>
				</tr>

                {foreach item=tax_detail name=group_tax_loop key=loop_count from=$GROUP_TAXES}
					<input type="hidden" id="group_tax_percentage_df{$smarty.foreach.group_tax_loop.iteration}" value="{$tax_detail.percentage}"  />
					<tr>
						<td align="left" class="lineOnTop">
							<input type="text" class="small" size="5" name="{$tax_detail.taxname}_group_percentage" id="group_tax_percentage{$smarty.foreach.group_tax_loop.iteration}" value="{$tax_detail.percentage}" onBlur="calcTotal(); set_tax_manual();">&nbsp;%
						</td>
						<td align="center" class="lineOnTop">{$tax_detail.taxlabel}</td>
						<td align="right" class="lineOnTop">
							<input type="text" class="small" size="6" name="{$tax_detail.taxname}_group_amount" id="group_tax_amount{$smarty.foreach.group_tax_loop.iteration}" style="cursor:pointer;" value="0.00" readonly>
						</td>
					</tr>

                {/foreach}
				<input type="hidden" id="group_tax_count" value="{$smarty.foreach.group_tax_loop.iteration}">

			</table>

		</div>
		<!-- End Popup Div Group Tax -->


	</td>
	<td  class="crmTableRow small lineOnTop" align="right">
		<input id="tax_final" class="small " name="tax_final" value="{$FINAL.tax_totalamount}" readonly style="width:70px; background-color:#EDEDED" onblur="set_tax_manual()" type="text">
	</td>
   </tr>
   <!-- Group Tax - ends -->



   <tr valign="top" style="visibility:hidden;display:none;height: 0;">
	<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small" align="right">
		(+)&nbsp;<b>{$APP.LBL_SHIPPING_AND_HANDLING_CHARGES} </b>
	</td>
	<td class="crmTableRow small" align="right">
		<input id="shipping_handling_charge" name="shipping_handling_charge" type="text" class="small" style="width:40px" align="right" value="{$FINAL.shipping_handling_charge}" onBlur="calcSHTax();">
	</td>
   </tr>

    <tr valign="top" id="div_total_without_vat" >
     <td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
     <td class="crmTableRow small lineOnTop" align="right">Total Net Amount including VAT</td>
     <td id="total_after_vat" class="crmTableRow small lineOnTop" align="right">{$FINAL.total_without_vat}</td>
   </tr>

   <tr valign="top"  style="visibility:hidden;display:none;height: 0;">
	<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small" align="right">
		(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,'shipping_handling_div','sh_tax_div_title',''); calcSHTax();" >{$APP.LBL_TAX_FOR_SHIPPING_AND_HANDLING} </a></b>

				<!-- Pop Div For Shipping and Handlin TAX -->
				<div class="discountUI" id="shipping_handling_div">
					<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
					   <tr>
						<td id="sh_tax_div_title" colspan="2" nowrap align="left" ></td>
						<td align="right"><img src="{'close.gif'|@aicrm_imageurl:$THEME}" border="0" onClick="fnHidePopDiv('shipping_handling_div')" style="cursor:pointer;"></td>
					   </tr>

					{foreach item=tax_detail name=sh_loop key=loop_count from=$FINAL.sh_taxes}

					   <tr>
						<td align="left" class="lineOnTop">
							<input type="text" class="small" size="3" name="{$tax_detail.taxname}_sh_percent" id="sh_tax_percentage{$smarty.foreach.sh_loop.iteration}" value="{$tax_detail.percentage}" onBlur="calcSHTax()">&nbsp;%
						</td>
						<td align="center" class="lineOnTop">{$tax_detail.taxlabel}</td>
						<td align="right" class="lineOnTop">
							<input type="text" class="small" size="4" name="{$tax_detail.taxname}_sh_amount" id="sh_tax_amount{$smarty.foreach.sh_loop.iteration}" style="cursor:pointer;" value="0.00" readonly>
						</td>
					   </tr>

					{/foreach}
					<input type="hidden" id="sh_tax_count" value="{$smarty.foreach.sh_loop.iteration}">

					</table>
				</div>
				<!-- End Popup Div for Shipping and Handling TAX -->

	</td>
	<td id="shipping_handling_tax" class="crmTableRow small" align="right">{$FINAL.shtax_totalamount}</td>
   </tr>


   <tr valign="top"  style="visibility:hidden;display:none;height: 0;">
	<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small" align="right">
		{$APP.LBL_ADJUSTMENT}
		<select id="adjustmentType" name="adjustmentType" class=small onchange="calcTotal();">
			<option value="+">{$APP.LBL_ADD_ITEM}</option>
			<option value="-">{$APP.LBL_DEDUCT}</option>
		</select>
	</td>
	<td class="crmTableRow small" align="right">
		<input id="adjustment" name="adjustment" type="text" class="small" style="width:40px" align="right" value="{$FINAL.adjustment}" onBlur="calcTotal();">
	</td>
   </tr>

	<tr valign="top">
		<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>คูปองส่วนลด (บาท)</b></td>
		<td width="12%" class="crmTableRow small lineOnTop" align="right">
			<input class="small" type="text" id="discount_coupons" value="{$FINAL.discount_coupon}" onKeyup="calcTotal()" onBlur="calcTotal();" name="discount_coupon" size="5" value="0.00" style="width:70px; text-align: right !important;" >
		</td>
   </tr>

   <tr valign="top">
	<td class="crmTableRow big lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow big lineOnTop" align="right"><b>{$APP.LBL_GRAND_TOTAL}</b></td>
	<td id="grandTotal" name="grandTotal" class="crmTableRow big lineOnTop" align="right">{$FINAL.grandTotal}</td>
   </tr>
</table>
<input type='hidden' name='q_tax' id='q_tax' value='{$tax1}' />
<input type='hidden' name='sub_total' id='sub_total' value='{$subTotal}' />
<input type='hidden' name='sub_discount' id='sub_discount' value='{$subDiscount}' />
<input type='hidden' name='active_module' id='active_module' value='{$MODULE}'>

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="{$row_no}">
		<input type="hidden" name="subtotal" id="subtotal" value="">
		<input type="hidden" name="total" id="total" value="">

        <input type="hidden" name="total_discount" id="total_discount" value="">
        <input type="hidden" name="total_after_discount" id="total_after_discount" value="{$FINAL.afterdis_final}">
		<input type="hidden" name="total_without_vat" id="total_without_vat" value="">
        <input type="hidden" name="total_tax" id="total_tax" value="">
        <input type="hidden" name="mac5_accountcode" id="mac5_accountcode" value="">
		<input type="hidden" name="hdn_bill_discount" id="hdn_bill_discount" value="">
	    <input type="hidden" name="hdn_total_after_bill_discount" id="hdn_total_after_bill_discount" value="">
</td></tr>
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


<!-- Added to calculate the tax and total values when page loads -->
<script>decideTaxDiv();</script>
<script>calcTotal();</script>
<script>calcSHTax();</script>
<!-- This above div is added to display the tax informations -->

<script type="text/javascript">
displayField();
function displayField()
{ldelim}
	{foreach item=field key=count from=$HIDDEN_FIELDS}
		{if $field.visible == 1}
			const {$field.class} = document.getElementsByClassName("{$field.class}");
			//console.log("Found elements: ", {$field.class}); 
			
			for (let i = 0; i < {$field.class}.length; i++) {ldelim}
				{$field.class}[i].style.display = 'none';
				//{$field.class}[i].innerHTML = '';
				//{$field.class}[i].insertAdjacentHTML('afterend', '<center class="afterend">-</center>');
			{rdelim}
		{/if}
	{/foreach}
{rdelim}
</script>


