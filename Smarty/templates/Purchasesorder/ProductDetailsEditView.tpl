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

<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">

<script type="text/javascript">
	jQuery.noConflict();
</script>
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
		document.getElementById("discount_div_title_final").innerHTML = '<b>{$APP.LABEL_SET_DISCOUNT_FOR_COLON} '+document.getElementById("netTotal").innerHTML+'</b>';
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
<table width="100%"  border="0" align="center" cellpadding="7" cellspacing="0" class="crmTable table-purchasesorder" id="proTab">
   	<thead>
	   	<tr>
			<td class="dvInnerHeader" width="55%">
				<b>{$APP.LBL_ITEM_DETAILS}</b>
			</td>
			<td class="dvInnerHeader" align="center"  width="20%">
				{*<b>Price type</b>&nbsp;&nbsp;
				{if $pricetype eq 'Exclude Vat'}
					{assign var=pricetype_exclud value="selected"}
				{elseif $pricetype eq 'Include Vat'}
					{assign var=pricetype_include value="selected"}
				{/if}*}
				<select class="small" id="pricetype" name="pricetype" style ="display:none" onchange="chage_format_quotes_edit();">
					<option value="Exclude Vat" {$pricetype_exclud}>Exclude Vat</option>
					<option value="Include Vat" {$pricetype_include} >Include Vat</option>
				</select>
			</td>
			<td class="dvInnerHeader" align="center"  width="20%">
				<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
				<b>{$APP.LBL_CURRENCY}</b>&nbsp;&nbsp;
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
			</td>
			<td class="dvInnerHeader" align="center" width="15%">
				<b>{$APP.LBL_TAX_MODE}</b>&nbsp;&nbsp;

				{if $ASSOCIATEDPRODUCTS.1.final_details.taxtype eq 'group'}
					{assign var="group_selected" value="selected"}
				{else}
					{assign var="individual_selected" value="selected"}
				{/if}
				{if $MODULE eq 'Purchasesorder'}
		        	<input type="hidden" id="taxtype" name="taxtype" value="group"><b>{$APP.LBL_GROUP}</b>
		        {else}
					<select class="small" id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
						<OPTION value="individual" {$individual_selected}>{$APP.LBL_INDIVIDUAL}</OPTION>
						<OPTION value="group" {$group_selected}>{$APP.LBL_GROUP}</OPTION>
					</select>
		        {/if}
			</td>
	   </tr>
   </thead>
   <!-- Header for the Product Details -->
   <tbody>
	   <tr valign="top">
		    <td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
				<td width=25 class="lvtCol"><b>ลำดับที่</b></td>
				<td width=20% class="lvtCol"><font color='red'>* </font><b>ชื่อสินค้า</b></td>
				<td width=5% class="lvtCol"><b>ชื่อโครงการ</b></td>
				<td width=5% class="lvtCol"><b>ผู้รับผิดชอบโครงการ</b></td>
				<td width=5% class="lvtCol"><b>ยี่ห้อสินค้า</b></td>
				<td width=5% class="lvtCol"><b>กลุ่มสินค้า</b></td>
				<td width=5% class="lvtCol"><b>รหัสสินค้า</b></td>
				<td width=5% class="lvtCol"><b>Prefix</b></td>
				<td width=5% class="lvtCol"><b>รหัสสีสินค้า</b></td>
				<td width=5% class="lvtCol"><b>ชื่่อสีสินค้า</b></td>
				<td width=5% class="lvtCol"><b>ชนิดผิว</b></td>
				<td width=5% class="lvtCol"><b>ขนาด (ฟุต)</b></td>
				<td width=5% class="lvtCol"><b>ความหนา (มม.)</b></td>
				<td width=5% class="lvtCol"><b>เกรดสินค้า</b></td>
				<td width=5% class="lvtCol"><b>Film</b></td>
				<td width=5% class="lvtCol"><b>Backprint</b></td>
				<td width=5% class="lvtCol"><b>จำนวนที่สั่งซื้อใน P/O</b></td>
				<td width=5% class="lvtCol"><b>GR 90% or 100%</b></td>
				<td width=5% class="lvtCol"><b>จำนวนสินค้า GR ที่รับเข้า</b></td>
				<td width=5% class="lvtCol"><b>จำนวนสินค้าชำรุดที่รับเข้า</b></td>
				<td width=5% class="lvtCol"><b>จำนวนที่เหลือ</b></td>
				<td width=5% class="lvtCol"><b>GR Qty.%</b></td>
				<td width=5% class="lvtCol"><b>สถานะรายการที่สั่งซื้อ</b></td>
				<td width=5% class="lvtCol"><b>Price Type</b></td>
				<td width=5% class="lvtCol"><b>ราคาซื้อ USD ($)</b></td>
				<td width=5% class="lvtCol"><b>ราคา/หน่วย</b></td>
				<td width=10% nowrap class="lvtCol" align="center" colspan="2"><b>Total Amount</b></td>
	   </tr>

    	{foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
			
			{assign var="po_detail_no" value="po_detail_no"|cat:$row_no}

			{assign var="gr_percentage" value="gr_percentage"|cat:$row_no}
			{assign var="gr_percentage_name" value=$data.$gr_percentage}

			{assign var="item_status" value="item_status"|cat:$row_no}

			{assign var="po_price_type" value="po_price_type"|cat:$row_no}
			{assign var="po_price_type_name" value=$data.$po_price_type}

			{assign var="product_backprint" value="product_backprint"|cat:$row_no}

			{assign var="deleted" value="deleted"|cat:$row_no}
			{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
			{assign var="productName" value="productName"|cat:$row_no}
			{assign var="comment" value="comment"|cat:$row_no}
			
			{assign var="productTotal" value="productTotal"|cat:$row_no}
			{assign var="subproduct_ids" value="subproduct_ids"|cat:$row_no}
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
		    {assign var="productcode" value="productcode"|cat:$row_no}

		    {assign var="projectsid" value="projectsid"|cat:$row_no}
		    {assign var="projects_name" value="projects_name"|cat:$row_no}
		    {assign var="smownerid" value="smownerid"|cat:$row_no}
		    {assign var="assignto" value="assignto"|cat:$row_no}
		    {assign var="product_brand" value="product_brand"|cat:$row_no}
		    {assign var="product_group" value="product_group"|cat:$row_no}
		    {assign var="product_code_crm" value="product_code_crm"|cat:$row_no}
		    {assign var="product_prefix" value="product_prefix"|cat:$row_no}
		    {assign var="product_design_no" value="product_design_no"|cat:$row_no}
		    {assign var="product_design_name" value="product_design_name"|cat:$row_no}
		    {assign var="product_finish_name" value="product_finish_name"|cat:$row_no}
		    {assign var="product_size_ft" value="product_size_ft"|cat:$row_no}
		    {assign var="product_thinkness" value="product_thinkness"|cat:$row_no}
		    {assign var="product_grade" value="product_grade"|cat:$row_no}
		    {assign var="product_film" value="product_film"|cat:$row_no}
		    {assign var="po_quantity" value="po_quantity"|cat:$row_no}
		    {assign var="gr_quantity" value="gr_quantity"|cat:$row_no}
		    {assign var="defects_quantity" value="defects_quantity"|cat:$row_no}
		    {assign var="remain_quantity" value="remain_quantity"|cat:$row_no}
			{assign var="gr_qty_percent" value="gr_qty_percent"|cat:$row_no}
		    {assign var="price_usd" value="price_usd"|cat:$row_no}
		    {assign var="unit_price" value="unit_price"|cat:$row_no}

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

				<td class="crmTableRow small lineOnTop" align="center">
					<input id="{$po_detail_no}" name="{$po_detail_no}" type="text" class="small po_detail_no" onfocus="this.className='po_detail_no';" onblur="this.className='po_detail_no';"  value="{$data.$po_detail_no}" style="width:25px !important;" readonly/>
				</td>

				
				<td class="crmTableRow small lineOnTop">
					<!-- Product Re-Ordering Feature Code Addition Starts -->
					<input type="hidden" name="hidtax_row_no{$row_no}" id="hidtax_row_no{$row_no}" value="{$tax_row_no}"/>
					<!-- Product Re-Ordering Feature Code Addition ends -->
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
						<tr>
							<td class="small" valign="top">
								<span id="{$business_code}" name="{$business_code}" style="color:#C0C0C0;font-style:italic;">{$data.$business_code}</span>
								<div id="productCodeView{$row_no}" style="font-weight:bold;">{$data.$productcode}</div>
								<textarea id="{$productName}" name="{$productName}" class="detailedViewTextBox user-success" style="width:200px;height:40px">{$data.$productName}</textarea>
								<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
								<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
								<input type="hidden" id="productcode{$row_no}" name="productcode{$row_no}" value="{$data.$productcode}" />
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
								<textarea id="{$comment}" name="{$comment}" class=detailedViewTextBox user-success style="width:200px;height:40px">{$data.$comment}</textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$comment}').value=''"; style="cursor:pointer;" />
							</td>
						</tr>
					</table>
				</td>
				
				<td class="crmTableRow small lineOnTop" valign="top">
					<input name="{$projects_name}" type="text" class="small projects_name" id="{$projects_name}" readonly onfocus="this.className='projects_name'"  style="" onblur="this.className='projects_name';" value="{$data.$projects_name}" readonly="" />
					<input type="hidden" id="{$projectsid}" name="{$projectsid}" value="{$data.$projectsid}"/>
					<img id="searchIcon1" title="Project Order" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="projectPickList(this,'{$MODULE}',1)" />
			    </td>
				   
			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$assignto}"name="{$assignto}" type="text" class="small assignto" onfocus="this.className='assignto'" onBlur="this.className='assignto';" value="{$data.$assignto}" readonly/>
					<input id="{$smownerid}" name="{$smownerid}" type="hidden" value="{$data.$smownerid}">
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_brand}" name="{$product_brand}" type="text" class="small product_brand" onfocus="this.className='product_brand';" onblur="this.className='product_brand';"  value="{$data.$product_brand}"/>
			   	</td>
			
				<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_group}" name="{$product_group}" type="text" class="small product_group" onfocus="this.className='product_group';" onblur="this.className='product_group';"  value="{$data.$product_group}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_code_crm}" name="{$product_code_crm}" type="text" class="small product_brand" onfocus="this.className='product_code_crm';" onblur="this.className='product_code_crm';"  value="{$data.$product_code_crm}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_prefix}" name="{$product_prefix}" type="text" class="small product_prefix" onfocus="this.className='product_prefix';" onblur="this.className='product_prefix';"  value="{$data.$product_prefix}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_design_no}" name="{$product_design_no}" type="text" class="small product_design_no" onfocus="this.className='product_design_no';" onblur="this.className='product_design_no';"  value="{$data.$product_design_no}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_design_name}" name="{$product_design_name}" type="text" class="small product_design_name" onfocus="this.className='product_design_name';" onblur="this.className='product_design_name';"  value="{$data.$product_design_name}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_finish_name}" name="{$product_finish_name}" type="text" class="small product_finish_name" onfocus="this.className='product_finish_name';" onblur="this.className='product_finish_name';"  value="{$data.$product_finish_name}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_size_ft}" name="{$product_size_ft}" type="text" class="small product_size_ft" onfocus="this.className='product_size_ft';" onblur="this.className='product_size_ft';"  value="{$data.$product_size_ft}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_thinkness}" name="{$product_thinkness}" type="text" class="small product_thinkness" onfocus="this.className='product_thinkness';" onblur="this.className='product_thinkness';"  value="{$data.$product_thinkness}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_grade}" name="{$product_grade}" type="text" class="small product_grade" onfocus="this.className='product_grade';" onblur="this.className='product_grade';"  value="{$data.$product_grade}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_film}" name="{$product_film}" type="text" class="small product_film" onfocus="this.className='product_film';" onblur="this.className='product_film';"  value="{$data.$product_film}"/>
			   	</td>

				<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$product_backprint}" name="{$product_backprint}" type="text" class="small product_backprint" onfocus="this.className='product_backprint';" onblur="this.className='product_backprint';"  value="{$data.$product_backprint}"/>
			   	</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$po_quantity}" name="{$po_quantity}" type="text" class="small po_quantity" onfocus="this.className='po_quantity';" onBlur="this.className='po_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'{$row_no}'); remaining_amount('{$row_no}');"  value="{$data.$po_quantity}"/>
			   	</td>

				<td class="crmTableRow small lineOnTop">
					<select data-rowno="{$row_no}" class="small" id="{$gr_percentage}" name="{$gr_percentage}"  style="width:85px;">
						{foreach item=brand key=count from=$a_gr_percentage}
							{if $brand.gr_percentage_name eq $gr_percentage_name}
								{assign var=gr_percentage_selected value="selected"}
							{else}
								{assign var=gr_percentage_selected value=""}
							{/if}
							<OPTION value="{$brand.gr_percentage_name}" {$gr_percentage_selected}>{$brand.gr_percentage_name}</OPTION>
						{/foreach}
					</select>
				</td>

			   	<td class="crmTableRow small lineOnTop" valign="top">
			   		<input id="{$gr_quantity}" name="{$gr_quantity}" type="text" class="small gr_quantity" onfocus="this.className='gr_quantity';" onBlur="this.className='gr_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax('{$row_no}'); setDiscount(this,'{$row_no}');" value="0" readonly style="background-color: #ccc" value="{$data.$gr_quantity}"/>
			   	</td>
				
				<td class="crmTableRow small lineOnTop">
					<input id="{$defects_quantity}" name="{$defects_quantity}" type="text" class="small defects_quantity" onfocus="this.className='defects_quantity'" onBlur="this.className='defects_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax('{$row_no}'); setDiscount(this,'{$row_no}');" value="{$data.$defects_quantity}" readonly style="background-color: #ccc"/>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input id="{$remain_quantity}" name="{$remain_quantity}" type="text" class="small remain_quantity" onfocus="this.className='remain_quantity'" onBlur="this.className='remain_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax('{$row_no}'); setDiscount(this,'{$row_no}');" value="{$data.$remain_quantity}" readonly style="background-color: #ccc" />
				</td>

				<td class="crmTableRow small lineOnTop">
					<input id="{$gr_qty_percent}" name="{$gr_qty_percent}" type="text" class="small gr_qty_percent" onfocus="this.className='gr_qty_percent'" onBlur="this.className='gr_qty_percent';settotalnoofrows(); set_tax_manual(); loadTaxes_Ajax('{$row_no}'); setDiscount(this,'{$row_no}');" value="{$data.$gr_qty_percent}" readonly style="background-color: #ccc" />
				</td>

				<td class="crmTableRow small lineOnTop">
					<input id="{$item_status}" name="{$item_status}" type="text" class="small item_status" onfocus="this.className='item_status'" onBlur="this.className='item_status';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax('{$row_no}'); setDiscount(this,'{$row_no}');" value="{$data.$item_status}" readonly style="background-color: #ccc" />
				</td>

				<td class="crmTableRow small lineOnTop">
					<select data-rowno="{$row_no}" class="small" id="{$po_price_type}" name="{$po_price_type}"  style="width:85px;">
						{foreach item=brand key=count from=$a_po_price_type}
							{if $brand.po_price_type_name eq $po_price_type_name}
								{assign var=po_price_type_selected value="selected"}
							{else}
								{assign var=po_price_type_selected value=""}
							{/if}
							<OPTION value="{$brand.po_price_type_name}" {$po_price_type_selected}>{$brand.po_price_type_name}</OPTION>
						{/foreach}
					</select>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<input name="{$price_usd}" type="text" class="small price_usd " id="{$price_usd}" onfocus="this.className='price_usd'" onblur="this.className='price_usd';calcTotal();setDiscount(this,'{$row_no}'); callTaxCalc('{$row_no}');calcTotal();" value="{$data.$price_usd}"/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input name="{$unit_price}" type="text" class="small unit_price" id="{$unit_price}" onfocus="this.className='unit_price'" style="" onblur="this.className='unit_price';settotalnoofrows();setDiscount(this,'{$row_no}'); callTaxCalc('{$row_no}');calcTotal();" value="{$data.$unit_price}"/>
				</td>
				
				<td class="crmTableRow small lineOnTop" align="right" colspan="2">
			    	
			        <span id = "totalAfterDiscount{$row_no}" >{$data.$totalAfterDiscount}</span>
				</td>
				<span id = "productTotal{$row_no}" style="visibility:hidden" >{$data.$productTotal}</span>
		        <span id = "discountTotal{$row_no}" style="visibility:hidden" >{$data.$discountTotal}</span>
		        <span id = "taxTotal{$row_no}" style="visibility:hidden" >{$data.$taxTotal}</span>
				<td valign="bottom" style ="display:none" class="crmTableRow small lineOnTop" align="right">
					<span id="netPrice{$row_no}"><b>{$data.$netPrice}</b></span>
				</td>

		   	</tr>
	   <!-- Product Details First row - Ends -->
	   {/foreach}
	</tbody>
</table>

<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	<tr>
		<td colspan="3">
			<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item
			</button>
		</td>
	</tr>

	{assign var="FINAL" value=$ASSOCIATEDPRODUCTS.1.final_details}

	<tr valign="top">
		<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>Total P/O Qty.</b></td>
		<td width="12%" id="TotalQty" class="crmTableRow small lineOnTop" align="right">0.00</td>
	</tr>

  	<!-- Product Details Final Total Discount, Tax and Shipping&Hanling  - Starts -->
    <tr valign="top">
		<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>{$APP.LBL_NET_TOTAL}</b></td>
		<td width="12%" id="netTotal" class="crmTableRow small lineOnTop" align="right">0.00</td>
    </tr>

   	<tr valign="top" id="div_discount">
		<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
		<td class="crmTableRow small lineOnTop" align="right">
		(-)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,'discount_div_final','discount_final','1');calcTotal();calcGroupTax();set_tax_manual();">{$APP.LBL_DISCOUNT}</a>

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
		<td id="discountTotal_final" class="crmTableRow small lineOnTop" align="right">{$FINAL.discountTotal_final}</td>
   	</tr>

   	<!-- Group Tax - starts -->
   	<tr valign="top" id="div_totalafterdiscount" >
     	<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
     	<td class="crmTableRow small lineOnTop" align="right">Total After Discount</td>
     	<td id="afterdis_final" class="crmTableRow small lineOnTop" align="right">{$FINAL.afterdis_final}</td>
   	</tr>
   	<tr id="group_tax_row" valign="top" class="TaxShow">
		<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
		<td class="crmTableRow small lineOnTop" align="right">
		(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,'group_tax_div','group_tax_div_title','');  calcTotal(); set_tax_manual(); calcGroupTax();" >Vat</a></b>

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
		<input id="tax_final" class="small " name="tax_final" value="{$FINAL.tax_totalamount}" style="width:70px" onblur="set_tax_manual()" type="text">
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
		<td class="crmTableRow big lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
		<td class="crmTableRow big lineOnTop" align="right"><b>{$APP.LBL_GRAND_TOTAL}</b></td>
		<td id="grandTotal" name="grandTotal" class="crmTableRow big lineOnTop" align="right">{$FINAL.grandTotal}</td>
   	</tr>
</table>

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="{$row_no}">
	<input type="hidden" name="subtotal" id="subtotal" value="">
	<input type="hidden" name="total" id="total" value="">

    <input type="hidden" name="total_discount" id="total_discount" value="">
    <input type="hidden" name="total_after_discount" id="total_after_discount" value="">
    <input type="hidden" name="total_tax" id="total_tax" value="">
    <input type="hidden" name="mac5_accountcode" id="mac5_accountcode" value="">

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

<!-- Added to calculate the tax and total values when page loads -->
<script>decideTaxDiv();</script>
<script>calcTotal();</script>
<script>calcSHTax();</script>
<!-- This above div is added to display the tax informations -->

{literal}
<style type="text/css">
	.table-purchasesorder > tbody{
		display: block;
	  	table-layout: fixed;
	  	width: 1700px;
	  	overflow-x: auto;
	}
	.table-purchasesorder > thead{
	  /*display: table-cell;*/
	  display: block;
	}
	.table-purchasesorder {
	  border-collapse: collapse;
	  border-spacing: 0;
	  /*width: 300px;*/
	}
</style>
{/literal}
