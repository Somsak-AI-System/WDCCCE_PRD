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
<script type="text/javascript" src="include/js/Inventory_QuotesProductDetails.js?v={$smarty.now}"></script>
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
		document.getElementById("discount_div_title_final").innerHTML = '<b>{$APP.LABEL_SET_DISCOUNT_FOR} '+document.getElementById("afterdis_final").innerHTML+'</b>';
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

	<table width="100%"  border="0" align="center" cellpadding="12" cellspacing="0" class="crmTable" id="proTab">
	   	<tr>
	   	
			<td colspan="6" class="dvInnerHeader">
				<b>{$APP.LBL_ITEM_DETAILS}</b>
			</td>

			<td class="dvInnerHeader" align="center" colspan="4">
				<b>Price type</b>&nbsp;&nbsp;
				<select class="small" id="pricetype" name="pricetype" onchange="calcTotal();">
					<option value="Exclude Vat">Exclude Vat</option>
					<option value="Include Vat">Include Vat</option>
				</select>
			</td>

			<td class="dvInnerHeader" align="center" colspan="2" style="display:none;">
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

			<td class="dvInnerHeader" align="center" colspan="2">
			
			
				<b>{$APP.LBL_TAX_MODE}</b>&nbsp;
		        {if $MODULE eq 'Quotes'}
		        	<input type="hidden" id="taxtype" name="taxtype" value="group"><b>{$APP.LBL_GROUP}</b>
		        {else}
				<select id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
					<OPTION value="individual" >{$APP.LBL_INDIVIDUAL}</OPTION>
					<OPTION value="group" selected>{$APP.LBL_GROUP}</OPTION>
				</select>
		        {/if}
			</td>
			<td class="dvInnerHeader" align="center" colspan="2" style ="display:none">&nbsp</td>
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
			<td width=2% class="lvtCol" align="center"><b>ราคาปกติ</b></td>
			<td width=5% class="lvtCol" align="center"><b>ราคาขาย</b></td>
			<td width=5% class="lvtCol" align="center"><b>ส่วนลด</b></td>
			<td width=7% nowrap class="lvtCol" align="center"><b>ราคารวม</b></td>
		</tr>
		<!-- Following code is added for form the first row. Based on these we should form additional rows using script -->

	   	<!-- Product Details First row - Starts -->
	   	<tr valign="top" id="row1">

			<td  class="crmTableRow small lineOnTop" align="center">&nbsp;
				<input type="hidden" id="deleted1" name="deleted1" value="0">
			</td>
			
			<td class="crmTableRow small lineOnTop">
				<table width="100%"  border="0" cellspacing="0" cellpadding="1">
				   <tr>
					<td class="small">
						<span id="business_code1" name="business_code1" style="color:#C0C0C0;font-style:italic;"> </span>
						<div id="productCodeView1" style="font-weight:bold;"></div>
		                <textarea id="productName1" name="productName1"  class="detailedViewTextBox user-success" style="width:90%;height:40px" >{$PRODUCT_NAME}</textarea>
					    <input type="hidden" id="hdnProductId1" name="hdnProductId1" value="{$PRODUCT_ID}" />
						<input type="hidden" id="lineItemType1" name="lineItemType1" value="Products" />
						<input type="hidden" id="productcode1" name="productcode1" value="" />

						<input type="hidden" id="hdn_cal_total1" name="hdn_cal_total1" value="0" />
						<input type="hidden" id="hdn_cal_discount1" name="hdn_cal_discount1" value="0" />
						<input type="hidden" id="hdn_cal_inline_total_price1" name="hdn_cal_inline_total_price1" value="0" />

						<img id="searchIcon1" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}',1)" />
					</td>
				</tr>

					<tr>
						<td class="small">
							<select class="small product_price_type" id="product_price_type1" name="product_price_type1" style="margin-bottom:5px;">
								<OPTION value="" {$product_price_type_selected}>-- Select Price list --</OPTION>
								{foreach item=product_price_type key=count from=$a_product_price_type}
									{if $product_price_type.product_price_type_name eq $product_price_type_name}
										{assign var=product_price_type_selected value="selected"}
									{else}
										{assign var=product_price_type_selected value=""}
									{/if}
									<OPTION value="{$product_price_type.product_price_type_name}" {$product_price_type_selected}>{$product_price_type.product_price_type_name}</OPTION>
								{/foreach}
							</select>
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
						<textarea id="comment1" name="comment1" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea>
						<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('comment1').value=''"; style="cursor:pointer;" />
					</td>
				   </tr>
				</table>
			</td>
			
			<td class="crmTableRow small lineOnTop" align="center">
		   		<input id="package_size_sheet_per_box1" name="package_size_sheet_per_box1" type="text" class="small detailedViewTextBox package_size_sheet_per_box" onfocus="this.className='package_size_sheet_per_box detailedViewTextBoxOn ';" onblur="this.className='package_size_sheet_per_box detailedViewTextBox ';"  value="" readonly="readonly" style="background-color: #CCC;" />
		   	</td>
		   	<td class="crmTableRow small lineOnTop" align="center">
		   		<input id="package_size_sqm_per_box1" name="package_size_sqm_per_box1" type="text" class="small detailedViewTextBox package_size_sqm_per_box" onfocus="this.className='package_size_sqm_per_box detailedViewTextBoxOn ';" onblur="this.className='package_size_sqm_per_box detailedViewTextBox ';"  value="" readonly="readonly" style="background-color: #CCC;"/>
		   	</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="box_quantity1" name="box_quantity1" type="text" class="detailedViewTextBox small box_quantity" onfocus="this.className='box_quantity detailedViewTextBoxOn'" onBlur="this.className='selling_price detailedViewTextBox';calSheetQuantity(this,'1'); calSqmQuantity(this,'1');setDiscount(this,'1'); callTaxCalc('1');calcTotal();" value=""/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="sales_unit1" name="sales_unit1" type="text" class="detailedViewTextBox small sales_unit" onfocus="this.className='sales_unit detailedViewTextBoxOn'" value="" readonly="readonly" style="background-color: #CCC;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="sheet_quantity1" name="sheet_quantity1" type="text" class="detailedViewTextBox small sheet_quantity" onfocus="this.className='sheet_quantity detailedViewTextBoxOn'" value="" readonly="readonly" style="background-color: #CCC;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="sqm_quantity1" name="sqm_quantity1" type="text" class="detailedViewTextBox small sqm_quantity" onfocus="this.className='sqm_quantity detailedViewTextBoxOn'" value="" readonly="readonly" style="background-color: #CCC;"/>
			</td>

			<td class="crmTableRow small lineOnTop" style="white-space: nowrap;" align="center">
				<input data-rowno="1" id="regular_price1" name="regular_price1" type="text" class="detailedViewTextBox small regular_price" 
					onfocus="this.className='regular_price detailedViewTextBoxOn'" onBlur="this.className='regular_price detailedViewTextBox';setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value="" style="display: inline-block; vertical-align: middle; width:80px;"/>

				<a href="javascript:void(0);" onclick="popupPricelist(this,'1');" style="display: inline-block; vertical-align: middle; padding-left: 10px;">
					<img src="{'search.gif'|@aicrm_imageurl:$THEME}" border="0" title="{$APP.LBL_SMART_SEARCH}" alt="{$APP.LBL_SMART_SEARCH}" style="cursor: pointer;"/>
				</a>
				<br>
				<br>
				<select class="small pricelist_type" id="pricelist_type1" name="pricelist_type1" style="margin-bottom:5px;" onchange="calcTotal();">
						{foreach item=pricelist_type key=count from=$a_pricelist_type}
							{if $pricelist_type.pricelist_type_name eq 'ราคาต่อตร.ม.'}
								{assign var=pricelist_type_selected value="selected"}
							{else}
								{assign var=pricelist_type_selected value=""}
							{/if}
							<OPTION value="{$pricelist_type.pricelist_type_name}" {$pricelist_type_selected}>{$pricelist_type.pricelist_type_name}</OPTION>
						{/foreach}
				</select>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="selling_price1" name="selling_price1" type="text" class="small selling_price " style="width:70px"  onfocus="this.className='selling_price detailedViewTextBoxOn'" onBlur="this.className='selling_price detailedViewTextBox';setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value=""/>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="product_discount1" name="product_discount1" type="text" class="small product_discount" style="width:70px;background-color: #CCC;"  onfocus="this.className='product_discount detailedViewTextBoxOn'" onBlur="this.className='product_discount  detailedViewTextBox'; setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value="" readonly="readonly"/>
			</td>
			
			<td class="crmTableRow small lineOnTop" align="right"><span id="netPrice1"><b>&nbsp;</b></span></td>
			
			<td class="crmTableRow small lineOnTop" align="right" colspan="2" style ="display:none">
		    	<span id = "productTotal1" style="visibility:hidden;" ></span>
		        <span id = "discountTotal1" style="visibility:hidden" ></span>
		        <span id = "taxTotal1" style="visibility:hidden" ></span>
		        <span id = "totalAfterDiscount1" ></span>
			</td>

			

	   	</tr>
	   	<!-- Product Details First row - Ends -->
	</table>
	<!-- Upto this has been added for form the first row. Based on these above we should form additional rows using script -->

	<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	   	<!-- Add Product Button -->
	   	<tr>
			<td colspan="3">
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');displayField();" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item
				</button>
			</td>
	   	</tr>

		<tr valign="top"style ="display:none">
			<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>Total Qty.</b></td>
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
			(-)&nbsp;<b>
			<span>{$APP.LBL_DISCOUNT}</span>
			</td>
			<td class="crmTableRow small lineOnTop" align="right">
			 	<input class="small" type="text" id="discountTotal_final" onBlur="calcTotal();" name="discountTotal_final" size="5" value="0.00" style="width:70px; text-align: right !important;" readonly>
			</td>
	   	</tr>

	   	<!-- Group Tax - starts -->
	   	<tr valign="top" id="div_totalafterdiscount">
	     	<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	     	<td class="crmTableRow small lineOnTop" align="right">Total After Discount</td>
	     	<td id="afterdis_final" class="crmTableRow small lineOnTop" align="right">0.00</td>
	  	</tr>


		<tr valign="top"  id="div_discount">
			<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small lineOnTop" align="right">
			<b><a href="javascript:doNothing();" onClick="displayCoords(this,'discount_div_final','discount_final','1');calcTotal();calcGroupTax();set_tax_manual();">ส่วนลดท้ายบิล</a>

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
			<td id="bill_discount" class="crmTableRow small lineOnTop" align="right">0.00</td>
	   	</tr>

		<tr valign="top" id="div_total_after_bill_discount">
	     	<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	     	<td class="crmTableRow small lineOnTop" align="right">Total หลังลดท้ายบิล</td>
	     	<td id="total_after_bill_discount" class="crmTableRow small lineOnTop" align="right">0.00</td>
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
				<input id="tax_final" class="small " name="tax_final" value="{$FINAL.tax_totalamount}" style="width:70px; text-align: right !important;" onblur="set_tax_manual()" type="text">
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

		<tr valign="top" id="div_total_without_vat">
	     	<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	     	<td class="crmTableRow small lineOnTop" align="right">Total Net Amount including VAT</td>
	     	<td id="total_after_vat" class="crmTableRow small lineOnTop" align="right">0.00</td>
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
			<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>คูปองส่วนลด (บาท)</b></td>
			<td width="12%" class="crmTableRow small lineOnTop" align="right">
			  <input class="small" type="text" id="discount_coupons" onBlur="calcTotal();" name="discount_coupon" size="5" value="0.00" style="width:70px; text-align: right !important;" >
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
	<input type="hidden" name="total_without_vat" id="total_without_vat" value="">
	<input type="hidden" name="total_tax" id="total_tax" value="">
	<input type="hidden" name="mac5_accountcode" id="mac5_accountcode" value="">
    <input type="hidden" name="hdn_bill_discount" id="hdn_bill_discount" value="">
	<input type="hidden" name="hdn_total_after_bill_discount" id="hdn_total_after_bill_discount" value="">
	</td>
</tr>

<script type="text/javascript">
displayField();
function displayField()
{ldelim}
	{foreach item=field key=count from=$HIDDEN_FIELDS}
		{if $field.visible == 1}
			const {$field.class} = document.getElementsByClassName("{$field.class}");
			//console.log("Found elements: ", elements); 
			
			for (let i = 0; i < {$field.class}.length; i++) {ldelim}
				{$field.class}[i].style.display = 'none';
				//{$field.class}[i].innerHTML = '';
				//{$field.class}[i].insertAdjacentHTML('afterend', '<center class="afterend">-</center>');
			{rdelim}
		{/if}
	{/foreach}
{rdelim}
</script>

