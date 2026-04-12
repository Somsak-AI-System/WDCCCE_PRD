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
<script type="text/javascript" src="include/js/Inventory.js"></script>
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

	<table width="100%"  border="0" align="center" cellpadding="11" cellspacing="0" class="crmTable" id="proTab">
	   	<tr>
	   	{if $MODULE neq 'Quotes'}
			<td colspan="2" class="dvInnerHeader">
		{else}
			<td colspan="5" class="dvInnerHeader">
		{/if}
				<b>{$APP.LBL_ITEM_DETAILS}</b>
			</td>
			<td class="dvInnerHeader" align="center" colspan="2">
				<b>Price type</b>&nbsp;&nbsp;
				<select class="small" id="pricetype" name="pricetype">
					<option value="Exclude Vat">Exclude Vat</option>
					<option value="Include Vat">Include Vat</option>
				</select>
			</td>
			<td class="dvInnerHeader" align="center" colspan="2">
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
			<td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
			<td width=32% class="lvtCol"><font color='red'>* </font><b>รายการสินค้า</b></td>
			
			{if $MODULE neq 'Quotes'}
			<td width=5% class="lvtCol"><b>{$APP.LBL_QTY_IN_STOCK}</b></td>
			{/if}

			<td width=7% class="lvtCol" align="center"><strong>ชนิดผิว</strong></td>
			<td width=7% class="lvtCol" align="center"><strong>ขนาด (มม.)</strong></td>
			<td width=7% class="lvtCol" align="center"><strong>ความหนา (มม.)</strong></td>
			
			<td width=7% class="lvtCol" align="center"><strong>จำนวน (หน่วย)</strong></td>
			<td width=7% class="lvtCol" align="center"><strong>หน่วยนับ</strong></td>

			<td width=7% class="lvtCol" align="center"><strong>รวมต้นทุนจริงเฉลี่ย</strong></td>
			<td width=7% class="lvtCol" align="center"><strong>ราคาคู่แข่ง</strong></td>

			<td class="lvtCol" align="center" style ="display:none"><strong>Price List</strong></td>
			<td class="lvtCol" align="center" style ="display:none"><strong>Price List</strong><br><label style="color:#F00">*Exclude Vat.</lable></td>
			<td class="lvtCol" align="center" style ="display:none"><strong>Price List</strong><br><label style="color:#F00">*Include Vat.</lable></td>

			<td width=7% class="lvtCol" align="center"><b>ราคาขาย</b></td>
			<td width=7% nowrap class="lvtCol" align="center" colspan="2"><b>รวม</b></td>
			<td valign="top" style ="display:none" class="lvtCol" align="center"><b>{$APP.LBL_NET_PRICE}</b></td>
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
						<textarea id="comment1" name="comment1" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea>
						<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('comment1').value=''"; style="cursor:pointer;" />
					</td>
				   </tr>
				</table>
			</td>
			
			<td class="crmTableRow small lineOnTop">
		   		<input id="product_finish1" name="product_finish1" type="text" class="small detailedViewTextBox product_finish" onfocus="this.className='product_finish detailedViewTextBoxOn ';" onblur="this.className='product_finish detailedViewTextBox ';"  value=""/>
		   	</td>
		   	<td class="crmTableRow small lineOnTop">
		   		<input id="product_size_mm1" name="product_size_mm1" type="text" class="small detailedViewTextBox product_size_mm" onfocus="this.className='product_size_mm detailedViewTextBoxOn ';" onblur="this.className='product_size_mm detailedViewTextBox ';"  value=""/>
		   	</td>
		   	<td class="crmTableRow small lineOnTop">
		   		<input id="product_thinkness1" name="product_thinkness1" type="text" class="small detailedViewTextBox product_thinkness" onfocus="this.className='product_thinkness detailedViewTextBoxOn ';" onblur="this.className='product_thinkness detailedViewTextBox ';"  value=""/>
		   	</td>
		   
		   	<td class="crmTableRow small lineOnTop">
				<input data-rowno="1" id="qty1" name="qty1" type="text" class="detailedViewTextBox small qty" onfocus="this.className='qty detailedViewTextBoxOn'" onBlur="this.className='qty detailedViewTextBox';settotalnoofrows();calcTotal(); set_tax_manual(); loadTaxes_Ajax(1); setDiscount(this,'1');{if $MODULE eq 'Invoice'}stock_alert(1);{/if}" value="1"/><br><span id="stock_alert1"></span>
			</td>
			
			{if $MODULE neq 'Quotes'}
				<td class="crmTableRow small lineOnTop" ><span id="qtyInStock1">{$QTY_IN_STOCK}</span></td>
			{/if}
			

		   	<td class="crmTableRow small lineOnTop">
		   		<input id="uom1" name="uom1" type="text" class="detailedViewTextBox small uom" onfocus="this.className='uom detailedViewTextBoxOn ';" onblur="this.className='uom detailedViewTextBox ';"  value=""/>
		   	</td>

			<td class="crmTableRow small lineOnTop" style ="display:none">
				<input data-rowno="1" name="price_list_std1" type="text" class="detailedViewTextBox small price_list_std " id="price_list_std1" onfocus="this.className='detailedViewTextBoxOn price_list_std'"  onblur="this.className='price_list_std detailedViewTextBox';calcTotal();setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value="0.00"/>
			</td>

			<td class="crmTableRow small lineOnTop" style ="display:none">
				<input data-rowno="1" name="listprice_exc1" type="text" class="small listprice_exc" id="listprice_exc1" readonly onfocus="this.className='detailedViewTextBoxOn listprice_exc'"  style="width:70px;background-color:#CCC;border: 1px solid  #999;" onblur="this.className='listprice_exc detailedViewTextBox';change_vat();settotalnoofrows();setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value="0.00"/>
			</td>

			<td class="crmTableRow small lineOnTop" style ="display:none">
				<input data-rowno="1" name="listprice_inc1" type="text" class="small listprice_inc" id="listprice_inc1" readonly onfocus="this.className='detailedViewTextBoxOn listprice_inc'"  style="width:70px;background-color:#CCC;border: 1px solid  #999;" onblur="this.className='listprice_inc detailedViewTextBox';change_vat();settotalnoofrows();setDiscount(this,'1'); callTaxCalc(1);calcTotal();" value="0.00"/>
			</td>
			
			<td class="crmTableRow small lineOnTop">
		   		<input id="product_cost_avg1" name="product_cost_avg1" type="text" class="small detailedViewTextBox product_cost_avg" onfocus="this.className='product_cost_avg detailedViewTextBoxOn ';" onblur="this.className='product_cost_avg detailedViewTextBox ';"  value=""/>
		   	</td>
		   	<td class="crmTableRow small lineOnTop">
		   		<input id="competitor_price1" name="competitor_price1" type="text" class="small detailedViewTextBox competitor_price" onfocus="this.className='competitor_price detailedViewTextBoxOn ';" onblur="this.className='competitor_price detailedViewTextBox ';"  value=""/>
		   	</td>

			<td class="crmTableRow small lineOnTop" align="right">
				<table width="100%" cellpadding="0" cellspacing="0">
				   	<tr>
						<td align="right">
							<input data-rowno="1" id="listPrice1" name="listPrice1" type="text" class="detailedViewTextBox small listPrice" onfocus="this.className='listPrice detailedViewTextBoxOn'" onBlur="this.className='listPrice detailedViewTextBox'; setDiscount(this,'1');callTaxCalc(1);calcTotal();set_tax_manual(); " value="0.00"/>
						</td>
				   	</tr>
				</table>
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
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item
				</button>
			</td>
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