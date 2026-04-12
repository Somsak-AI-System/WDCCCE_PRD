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

	<table width="100%"  border="0" align="center" cellpadding="7" cellspacing="0" class="crmTable table-purchasesorder" id="proTab">
	   	<thead>
		   	<tr>
				<td class="dvInnerHeader" width="55%">
					<b>{$APP.LBL_ITEM_DETAILS}</b>
				</td>
				<td class="dvInnerHeader" align="center" width="20%">
					
					<select class="small" id="pricetype" name="pricetype" style="display: none;">
						<option value="Exclude Vat">Exclude Vat</option>
						<option value="Include Vat">Include Vat</option>
					</select>
				</td>
				<td class="dvInnerHeader" align="center" width="20%">
					<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
					<b>{$APP.LBL_CURRENCY}</b>&nbsp;&nbsp;
					{*{if $MODULE eq 'Quotes'}*}
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
					{*{/if}		*}
				</td>

				<td class="dvInnerHeader" align="center" width="15%">
					<b>{$APP.LBL_TAX_MODE}</b>&nbsp;
			        {if $MODULE eq 'Purchasesorder'}
			        	<input type="hidden" id="taxtype" name="taxtype" value="group"><b>{$APP.LBL_GROUP}</b>
			        {else}
					<select id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
						<OPTION value="individual" >{$APP.LBL_INDIVIDUAL}</OPTION>
						<OPTION value="group" selected>{$APP.LBL_GROUP}</OPTION>
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
			<!-- Following code is added for form the first row. Based on these we should form additional rows using script -->

		   	<!-- Product Details First row - Starts -->
		   	<tr valign="top" id="row1">
				<td  class="crmTableRow small lineOnTop" align="center">&nbsp;
					<input type="hidden" id="deleted1" name="deleted1" value="0">
				</td>
				<td class="crmTableRow small lineOnTop" align="center">
					<input id="po_detail_no1" name="po_detail_no1" type="text" class="small po_detail_no" onfocus="this.className='po_detail_no';" onblur="this.className='po_detail_no';"  value="1" style="width:25px !important;" readonly/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
					   <tr>
						<td class="small">
							<span id="business_code1" name="business_code1" style="color:#C0C0C0;font-style:italic;"> </span>
							<div id="productCodeView1" style="font-weight:bold;"></div>
			                <textarea id="productName1" name="productName1"  class="detailedViewTextBox user-success" style="width:200px;height:40px" >{$PRODUCT_NAME}</textarea>
						    <input type="hidden" id="hdnProductId1" name="hdnProductId1" value="{$PRODUCT_ID}" />
							<input type="hidden" id="lineItemType1" name="lineItemType1" value="Products" />
							<input type="hidden" id="productcode1" name="productcode1" value="" />
							<img id="searchIcon1" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}',1)" />
						</td>
					</tr>
					<tr>
						<td class="small">
							<input type="hidden" value="" id="subproduct_ids1" name="subproduct_ids1" />
							<span id="subprod_names1" name="subprod_names1" style="color:#C0C0C0;font-style:italic;"> </span>
						</td>
					   </tr>
					   <tr valign="bottom">
						<td class="small" id="setComment">
							<textarea id="comment1" name="comment1" class="detailedViewTextBox user-success" style="width:200px;height:40px"></textarea>
							<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('comment1').value=''"; style="cursor:pointer;" />
						</td>
					   </tr>
					</table>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" name="projects_name1" type="text" class="small projects_name" id="projects_name1" readonly onfocus="this.className='projects_name'"  style="" onblur="this.className='projects_name';" value="" readonly="" />
					<input type="hidden" id="projectsid1" name="projectsid1" value="" />
					<img id="searchIcon1" title="Project Order" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="projectPickList(this,'{$MODULE}',1)" />
				</td>
				<td class="crmTableRow small lineOnTop" align="right">
					<input data-rowno="1" id="assignto1"name="assignto1" type="text" class="small assignto" onfocus="this.className='assignto'" onBlur="this.className='assignto';" value="" readonly/>
					<input id="smownerid1" name="smownerid1" type="hidden" value="">
				</td>

				<td class="crmTableRow small lineOnTop">
					<input id="product_brand1" name="product_brand1" type="text" class="small product_brand" onfocus="this.className='product_brand';" onblur="this.className='product_brand';"  value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
			   		<input id="product_group1" name="product_group1" type="text" class="small product_group" onfocus="this.className='product_group';" onblur="this.className='product_group';"  value=""/>
			   	</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_code_crm1" name="product_code_crm1" type="text" class="small product_code_crm" onfocus="this.className='product_code_crm'" onBlur="this.className='product_code_crm';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<!--  -->
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_prefix1" name="product_prefix1" type="text" class="small product_prefix" onfocus="this.className='product_prefix'" onBlur="this.className='product_prefix';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_design_no1" name="product_design_no1" type="text" class="small product_design_no" onfocus="this.className='product_design_no'" onBlur="this.className='product_design_no';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_design_name1" name="product_design_name1" type="text" class="small product_design_name" onfocus="this.className='product_design_name'" onBlur="this.className='product_design_name';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_finish_name1" name="product_finish_name1" type="text" class="small product_finish_name" onfocus="this.className='product_finish_name'" onBlur="this.className='product_finish_name';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_size_ft1" name="product_size_ft1" type="text" class="small product_size_ft" onfocus="this.className='product_size_ft'" onBlur="this.className='product_size_ft';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_thinkness1" name="product_thinkness1" type="text" class="small product_thinkness" onfocus="this.className='product_thinkness'" onBlur="this.className='product_thinkness';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_grade1" name="product_grade1" type="text" class="small product_grade" onfocus="this.className='product_grade'" onBlur="this.className='product_grade';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_film1" name="product_film1" type="text" class="small product_film" onfocus="this.className='product_film'" onBlur="this.className='product_film';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="product_backprint1" name="product_backprint1" type="text" class="small product_backprint" onfocus="this.className='product_backprint'" onBlur="this.className='product_backprint';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value=""/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="po_quantity1" name="po_quantity1" type="text" class="small po_quantity" onfocus="this.className='po_quantity'" onBlur="this.className='po_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1'); remaining_amount('1');" value="0"/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<select class="small gr_percentage" id="gr_percentage1" name="gr_percentage1" style="width:85px;">
						{foreach item=gr_percentage key=count from=$a_gr_percentage}
							{if $gr_percentage.gr_percentage_name eq $gr_percentage_name}
								{assign var=gr_percentage_selected value="selected"}
							{else}
								{assign var=gr_percentage_selected value=""}
							{/if}
							<OPTION value="{$gr_percentage.gr_percentage_name}" {$gr_percentage_selected}>{$gr_percentage.gr_percentage_name}</OPTION>
						{/foreach}
					</select>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="gr_quantity1" name="gr_quantity1" type="text" class="small gr_quantity" onfocus="this.className='gr_quantity'" onBlur="this.className='gr_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value="0" readonly style="background-color: #ccc"/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="defects_quantity1" name="defects_quantity1" type="text" class="small defects_quantity" onfocus="this.className='defects_quantity'" onBlur="this.className='defects_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value="0" readonly style="background-color: #ccc"/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="remain_quantity1" name="remain_quantity1" type="text" class="small remain_quantity" onfocus="this.className='remain_quantity'" onBlur="this.className='remain_quantity';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value="0" readonly style="background-color: #ccc" />
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="gr_qty_percent1" name="gr_qty_percent1" type="text" class="small gr_qty_percent" onfocus="this.className='gr_qty_percent'" onBlur="this.className='gr_qty_percent';settotalnoofrows();  set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value="0" readonly style="background-color: #ccc" />
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" id="item_status1" name="item_status1" type="text" class="small item_status" onfocus="this.className='item_status'" onBlur="this.className='item_status';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');" value="Pending" readonly style="background-color: #ccc" />
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<select class="small po_price_type" id="po_price_type1" name="po_price_type1" style="width:85px;">
						{foreach item=po_price_type key=count from=$a_po_price_type}
							{if $po_price_type.po_price_type_name eq $po_price_type_name}
								{assign var=po_price_type_selected value="selected"}
							{else}
								{assign var=po_price_type_selected value=""}
							{/if}
							<OPTION value="{$po_price_type.po_price_type_name}" {$po_price_type_selected}>{$po_price_type.po_price_type_name}</OPTION>
						{/foreach}
					</select>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" name="price_usd1" type="text" class="small price_usd " id="price_usd1" onfocus="this.className='price_usd'"  onblur="this.className='price_usd';calcTotal();setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value="0.00"/>
				</td>
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="1" name="unit_price1" type="text" class="small unit_price" id="unit_price1" onfocus="this.className='unit_price'"  style="" onblur="this.className='unit_price';settotalnoofrows();setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value="0.00"/>
				</td>

				<td class="crmTableRow small lineOnTop" align="right">
			    	<span id = "productTotal1" style="visibility:hidden;" ></span>
			    	<span id = "totalAfterDiscount1" ></span>
			        <span id = "discountTotal1" style="visibility:hidden" ></span>
			        <span id = "taxTotal1" style="visibility:hidden" ></span>
				</td>
				<td valign="bottom" style ="display:none" class="crmTableRow small lineOnTop" align="right"><span id="netPrice1"><b>&nbsp;</b></span></td>
				
		   	</tr>
		   	<!-- Product Details First row - Ends -->
	   	</tbody>
	</table>
	<!-- Upto this has been added for form the first row. Based on these above we should form additional rows using script -->

	<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	   	<!-- Add Product Button -->
	   	<tr>
			<td colspan="3">
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item
				</button>
			</td>
	   	</tr>

		<tr valign="top">
			<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>Total P/O Qty.</b></td>
			<td width="12%" id="TotalQty" class="crmTableRow small lineOnTop" align="right">0.00</td>
	   	</tr>

	   <!-- Product Details Final Total Discount, Tax and Shipping&Hanling  - Starts -->
	   	<tr valign="top">
			<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>{$APP.LBL_TOTAL_PRICE}</b></td>
			<td width="12%" id="netTotal" class="crmTableRow small lineOnTop" align="right">0.00</td>
	   	</tr>

	   	<tr valign="top"  id="div_discount">
			<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small lineOnTop" align="right">
			(-)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,'discount_div_final','discount_final','1');calcTotal();calcGroupTax();set_tax_manual();">{$APP.LBL_DISCOUNT}</a>

			<!-- Popup Discount DIV -->
			<div class="discountUI" id="discount_div_final">
				<input type="hidden" id="discount_type_final" name="discount_type_final" value="">
				<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
					<tr>
						<td id="discount_div_title_final" nowrap align="left" ></td>
						<td align="right"><img src="{'close.gif'|@aicrm_imageurl:$THEME}" border="0" onClick="fnHidePopDiv('discount_div_final')" style="cursor:pointer;"></td>
				   	</tr>
				   	<tr>
						<td align="left" class="lineOnTop">
							<input type="radio" name="discount_final" checked onclick="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp; {$APP.LBL_ZERO_DISCOUNT}
						</td>
						<td class="lineOnTop">&nbsp;</td>
				   	</tr>
				   	<tr>
						<td align="left">
							<input type="radio" name="discount_final" onclick="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp; % {$APP.LBL_OF_PRICE}
						</td>
						<td align="right">
							<input type="text" class="small" size="5" id="discount_percentage_final" name="discount_percentage_final" value="0" style="visibility:hidden" onBlur="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp;%
						</td>
				   	</tr>
				   	<tr>
						<td align="left" nowrap>
							<input type="radio" name="discount_final" onclick="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp;{$APP.LBL_DIRECT_PRICE_REDUCTION}
						</td>
						<td align="right">
							<input type="text" id="discount_amount_final" name="discount_amount_final" size="5" value="0" style="visibility:hidden" onBlur="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();" onkeyup="setDiscount(this,'_final');  calcGroupTax(); calcTotal();set_tax_manual();">
						</td>
				   	</tr>
				</table>
			</div>
			<!-- End Div -->
			</td>
			<td id="discountTotal_final" class="crmTableRow small lineOnTop" align="right">0.00</td>
	   	</tr>

	   	<!-- Group Tax - starts -->
	   	<tr valign="top" id="div_totalafterdiscount">
	     	<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	     	<td class="crmTableRow small lineOnTop" align="right">Total After Discount</td>
	     	<td id="afterdis_final" class="crmTableRow small lineOnTop" align="right">0.00</td>
	  	</tr>
	   	<tr id="group_tax_row" valign="top" class="TaxShow">
			<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small lineOnTop" align="right">
			(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,'group_tax_div','group_tax_div_title',''); calcGroupTax();" >Vat</a></b>
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
									<input type="text" class="small" size="5" name="{$tax_detail.taxname}_group_percentage" id="group_tax_percentage{$smarty.foreach.group_tax_loop.iteration}" value="{$tax_detail.percentage}" onBlur="calcTotal();set_tax_manual();">&nbsp;%
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

	   	<tr valign="top"  style="visibility:hidden;display:none;height: 0;">
			<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small" align="right">
			(+)&nbsp;<b>{$APP.LBL_SHIPPING_AND_HANDLING_CHARGES} </b>
			</td>
			<td class="crmTableRow small" align="right">
				<input id="shipping_handling_charge" name="shipping_handling_charge" type="text" class="small" style="width:40px" align="right" value="0.00" onBlur="calcSHTax();">
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

						{foreach item=tax_detail name=sh_loop key=loop_count from=$SH_TAXES}

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
			<td id="shipping_handling_tax" class="crmTableRow small" align="right">0.00</td>
	   	</tr>
	   	
	    <tr valign="top" style="visibility:hidden;display:none;height: 0;">
			<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small" align="right">
				{$APP.LBL_ADJUSTMENT}
				<span><input type="radio" name="adjustmentType" id="adjustmentType" option="" value="+" checked="">&nbsp;Add&nbsp;&nbsp;</span>
				<span><input type="radio" name="adjustmentType" id="adjustmentType" option="" value="-">&nbsp;Deduct</span></div>
				<!-- <select id="adjustmentType" name="adjustmentType" class=small onchange="calcTotal();">
					<option value="+">{$APP.LBL_ADD_ITEM}</option>
					<option value="-">{$APP.LBL_DEDUCT}</option>
				</select> -->
			</td>
			<td class="crmTableRow small" align="right">
				<input id="adjustment" name="adjustment" type="text" class="small" style="width:40px" align="right" value="0.00" onBlur="calcTotal();">
			</td>
	   	</tr>
	   	<tr valign="top">
			<td class="crmTableRow big lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow big lineOnTop" align="right"><b>{$APP.LBL_GRAND_TOTAL}</b></td>
			<td id="grandTotal" name="grandTotal" class="crmTableRow big lineOnTop" align="right">&nbsp;</td>
	   	</tr>
	</table>

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="">
	<input type="hidden" name="subtotal" id="subtotal" value="">
	<input type="hidden" name="total" id="total" value="">
	<input type="hidden" name="total_discount" id="total_discount" value="">
    <input type="hidden" name="total_after_discount" id="total_after_discount" value="">
	<input type="hidden" name="total_tax" id="total_tax" value="">
	<input type="hidden" name="mac5_accountcode" id="mac5_accountcode" value="">

	</td>
</tr>

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