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

<script type="text/javascript" src="include/js/Inventory.js"></script>
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
<table width="100%"  border="0" align="center" cellpadding="7" cellspacing="0" class="crmTable" id="proTab">
   <tr>
		<td colspan="2" class="dvInnerHeader">
		<b>{$APP.LBL_ITEM_DETAILS}</b>
	</td>
	<td class="dvInnerHeader" align="center" colspan="2">
		<b>Price type</b>&nbsp;&nbsp;
		{if $pricetype eq 'Exclude Vat'}
			{assign var=pricetype_exclud value="selected"}
		{elseif $pricetype eq 'Include Vat'}
			{assign var=pricetype_include value="selected"}
		{/if}
		<select class="small" id="pricetype" name="pricetype" onchange="chage_format_quotes_edit();">
			<option value="Exclude Vat" {$pricetype_exclud}>Exclude Vat</option>
			<option value="Include Vat" {$pricetype_include} >Include Vat</option>
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
		{*{/if}	*}
	</td>

	<td class="dvInnerHeader" align="center" colspan="1">
		<b>{$APP.LBL_TAX_MODE}</b>&nbsp;&nbsp;

		{if $ASSOCIATEDPRODUCTS.1.final_details.taxtype eq 'group'}
			{assign var="group_selected" value="selected"}
		{else}
			{assign var="individual_selected" value="selected"}
		{/if}
		
        <input type="hidden" id="taxtype" name="taxtype" value="group"><b>{$APP.LBL_GROUP}</b>
       
		<!-- <select class="small" id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
			<OPTION value="individual" {$individual_selected}>{$APP.LBL_INDIVIDUAL}</OPTION>
			<OPTION value="group" {$group_selected}>{$APP.LBL_GROUP}</OPTION>
		</select> -->
        
	</td>
	<td class="dvInnerHeader" align="center" style ="display:none">&nbsp</td>
   </tr>

   <!-- Header for the Product Details -->
   <tr valign="top">
	   <td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td><!-- {$APP.LBL_TOOLS} -->
	   <td width=47% class="lvtCol"><font color='red'>* </font><b>ชื่อสินค้า</b></td><!-- {$APP.LBL_ITEM_NAME} -->
	   <td width=6% class="lvtCol"><b>จำนวนที่พร้อมขาย</b></td>{*$APP.LBL_QTY_IN_STOCK*}
	   <td width=10% class="lvtCol" align="center"><strong>หน่วยนับ</strong></td>
	   <td width=10% class="lvtCol" align="center"><b>จำนวน</b></td><!-- {$APP.LBL_QTY} -->
	   <td class="lvtCol" style ="display:none" align="center"><strong>Price List</strong></td>
	   <td class="lvtCol" align="center" style ="display:none"><strong>Price List</strong><br><label style="color:#F00">*Exclude Vat.</lable></td>
	   <td class="lvtCol" align="center" style ="display:none"><strong>Price List</strong><br><label style="color:#F00">*Include Vat.</lable></td>
	   <td width=10% class="lvtCol" align="center"><b>ราคาขาย</b></td>
	   <td width=12%  class="lvtCol" align="center" colspan="2"><b>รวม</b></td><!-- {$APP.LBL_TOTAL} -->
	   <td valign="top" class="lvtCol" align="center" style ="display:none"><b>{$APP.LBL_NET_PRICE}</b></td>
   </tr>

	{*<td width=8% class="lvtCol" align="center"><strong>Price List Last</strong></td>*}

   {foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
	{assign var="deleted" value="deleted"|cat:$row_no}
	{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
	{assign var="productName" value="productName"|cat:$row_no}
	{assign var="comment" value="comment"|cat:$row_no}
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
					<div id="productCodeView{$row_no}" style="font-weight:bold;">{$data.$productcode}</div>
					<textarea id="{$productName}" name="{$productName}" class="detailedViewTextBox user-success" style="width:90%;height:40px">{$data.$productName}</textarea>
					<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
					<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
					<input type="hidden" id="productcode{$row_no}" name="productcode{$row_no}" value="{$data.$productcode}" />
					&nbsp;
					{if $entityType eq 'Services'}
						<img id="searchIcon{$row_no}" title="Services" src="{'services.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="servicePickList(this,'{$MODULE}','{$row_no}')" />
					{else}
						<img id="searchIcon{$row_no}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no}')" />
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
					<textarea id="{$comment}" name="{$comment}" class=detailedViewTextBox user-success style="width:90%;height:40px">{$data.$comment}</textarea>
					<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$comment}').value=''"; style="cursor:pointer;" />
				</td>
			</tr>
		</table>
	</td>
	<!-- column 2 - Product Name - ends -->

	<!-- column 3 - Quantity in Stock - starts -->
	<td class="crmTableRow small lineOnTop" valign="top" align="right"><span id="{$qtyInStock}">{$data.$qtyInStock}</span></td>
    
	   
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="{$uom}" name="{$uom}" type="text" class="small uom" onblur="this.className='detailedViewTextBox uom';"  onfocus="this.className='detailedViewTextBoxOn uom'" value="{$data.$uom}"/></td>

	   <td class="crmTableRow small lineOnTop" valign="top"><input data-rowno="{$row_no}" id="{$qty}" name="{$qty}" type="text" class="small qty "onfocus="this.className='detailedViewTextBoxOn qty'" onblur="this.className='detailedViewTextBox qty';settotalnoofrows();calcTotal(); set_tax_manual();loadTaxes_Ajax(1); setDiscount(this,'1');{if $MODULE eq 'Invoice'}stock_alert(1);{/if}" value="{$data.$qty}"/></td>
	<!-- column 3 - Quantity in Stock - ends -->
	<!-- column 6 - Pricelise standard  - starts -->
	 <td class="crmTableRow small lineOnTop" valign="top" style ="display:none">
		<input data-rowno="{$row_no}" id="{$price_list_std}" name="{$price_list_std}" type="text" class="small price_list_std" style="width:70px;background-color:#CCC;border: 1px solid  #999;" onfocus="this.className='detailedViewTextBoxOn price_list_std'" onblur="this.className='detailedViewTextBox price_list_std'" onChange="settotalnoofrows();calcTotal(); loadTaxes_Ajax({$row_no}); setDiscount(this,'{$row_no}'); calcTotal(); " value="{$data.$price_list_std}"/>
	</td>

	<!-- column 7 - Pricelise Exclude Vat  - starts -->
    <td class="crmTableRow small lineOnTop" valign="top" style ="display:none">
		<input data-rowno="{$row_no}" id="{$listprice_exc}" name="{$listprice_exc}" type="text" readonly class="small  listprice_exc" style="width:70px;background-color:#CCC;border: 1px solid  #999;" onfocus="this.className='detailedViewTextBoxOn listprice_exc'" onblur="this.className='detailedViewTextBox listprice_exc'" onChange="change_vat();settotalnoofrows(); loadTaxes_Ajax({$row_no}); setDiscount(this,'{$row_no}'); calcTotal();" value="{$data.$listprice_exc}"/>
	</td>

	<!-- column 8 - Pricelise Include Vat  - starts -->
	<td class="crmTableRow small lineOnTop" valign="top" style ="display:none">
		<input data-rowno="{$row_no}" id="{$listprice_inc}" name="{$listprice_inc}" type="text" readonly class="small listprice_inc " style="width:70px;background-color:#CCC;border: 1px solid  #999;" onfocus="this.className='detailedViewTextBoxOn listprice_inc'" onblur="this.className='detailedViewTextBox listprice_inc'" onChange="change_vat();settotalnoofrows(); loadTaxes_Ajax({$row_no}); setDiscount(this,'{$row_no}'); calcTotal();" value="{$data.$listprice_inc}"/>
	</td>

    {*<td class="crmTableRow small lineOnTop" valign="top">*}
    	{*<a href="javascript:void(0);" onclick="showdialog('{$row_no}');"><img src="{'search.gif'|@aicrm_imageurl:$THEME}" border="0" title="{$APP.LBL_SMART_SEARCH}" alt"{$APP.LBL_SMART_SEARCH}" style="cursor:pointer;"></a>*}

		<!-- <input id="{$price_list_inv}" name="{$price_list_inv}" type="text" class="small " style="width:70px" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox';calcTotal();setDiscount(this,'1'); callTaxCalc(1);calcTotal();"  onChange="setDiscount(this,'{$row_no}')" value="{$data.$price_list_inv}"/> -->
	{*</td>*}
	<!-- column 4 - Quantity - ends -->

	<!-- column 5 - List Price with Discount, Total After Discount and Tax as table - starts -->
	<td class="crmTableRow small lineOnTop" align="right" valign="top">
		<table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
			<td align="right">
				<input data-rowno="{$row_no}" id="{$listPrice}" name="{$listPrice}" value="{$data.$listPrice}" type="text" class="small listPrice" onfocus="this.className='detailedViewTextBoxOn listPrice'"  onBlur="this.className='detailedViewTextBox listPrice';calcTotal();set_tax_manual(); setDiscount(this,'{$row_no}');callTaxCalc('{$row_no}');"/><!--&nbsp;<img src="{'pricebook.gif'|@aicrm_imageurl:$THEME}" onclick="priceBookPickList(this,'{$row_no}')">-->
			</td>
		   </tr>
		</table>
	</td>
	<!-- column 5 - List Price with Discount, Total After Discount and Tax as table - ends -->


	<!-- column 6 - Product Total - starts -->
	<td class="crmTableRow small lineOnTop" align="right" colspan="2">
    	<span id = "productTotal{$row_no}" style="visibility:hidden" >{$data.$productTotal}</span>
        <span id = "discountTotal{$row_no}" style="visibility:hidden" >{$data.$discountTotal}</span>
        <span id = "taxTotal{$row_no}" style="visibility:hidden" >{$data.$taxTotal}</span>
        <span id = "totalAfterDiscount{$row_no}" >{$data.$totalAfterDiscount}</span>

        <!--
		<table width="100%" cellpadding="5" cellspacing="0">
		   <tr>
			<td id="productTotal{$row_no}" align="right">{$data.$productTotal}</td>
		   </tr>
		   <tr>
			<td id="discountTotal{$row_no}" align="right">{$data.$discountTotal}</td>
		   </tr>
		   <tr>
			<td id="totalAfterDiscount{$row_no}" align="right">{$data.$totalAfterDiscount}</td>
		   </tr>
		   <tr>
			<td id="taxTotal{$row_no}" align="right">{$data.$taxTotal}</td>
		   </tr>
		</table>
        -->
	</td>
	<!-- column 6 - Product Total - ends -->

	<!-- column 7 - Net Price - starts -->
	<td valign="bottom" style ="display:none" class="crmTableRow small lineOnTop" align="right">
		<span id="netPrice{$row_no}"><b>{$data.$netPrice}</b></span>
	</td>
	<!-- column 7 - Net Price - ends -->


   </tr>
   <!-- Product Details First row - Ends -->
   {/foreach}
</table>



<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
   <!-- Add Product Button -->
   <tr>
	<td colspan="3">
			<!-- <input type="button" name="Button" class="crmbutton small create" value="Add Products" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');" /> -->
			<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Products
			</button>
            <!--
			<input type="button" name="Button" class="crmbutton small create" value="{$APP.LBL_ADD_SERVICE}" onclick="fnAddServiceRow('{$MODULE}','{$IMAGE_PATH}');" />-->
	</td>
   </tr>




<!--
All these details are stored in the first element in the array with the index name as final_details
so we will get that array, parse that array and fill the details
-->
{assign var="FINAL" value=$ASSOCIATEDPRODUCTS.1.final_details}

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


