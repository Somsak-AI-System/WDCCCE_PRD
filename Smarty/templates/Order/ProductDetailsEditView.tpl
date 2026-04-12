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
</script>

<tr><td colspan="4" align="left">
<div id="dialog" style="display:none;">Dialog Content.</div>
<table width="100%"  border="0" align="center" cellpadding="17" cellspacing="0" class="crmTable" id="proTab">
   <tr>
   	<td colspan="6" class="dvInnerHeader"><b>{$APP.LBL_ITEM_DETAILS}</b></td>
	<td class="dvInnerHeader" align="center" colspan="4">
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
	<td class="dvInnerHeader" align="center" colspan="5">
		<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
		<b>{$APP.LBL_CURRENCY}</b>&nbsp;&nbsp;
			<select class="small" id="inventory_currency" name="inventory_currency">
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

	<td class="dvInnerHeader" align="center" colspan="3">
		<b>{$APP.LBL_TAX_MODE}</b>&nbsp;&nbsp;

		{if $ASSOCIATEDPRODUCTS.1.final_details.taxtype eq 'group'}
			{assign var="group_selected" value="selected"}
		{else}
			{assign var="individual_selected" value="selected"}
		{/if}
		{if $MODULE eq 'Quotes'}
        	<input type="hidden" id="taxtype" name="taxtype" value="group"><b>{$APP.LBL_GROUP}</b>
        {else}
		<select class="small" id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
			<OPTION value="individual" {$individual_selected}>{$APP.LBL_INDIVIDUAL}</OPTION>
			<OPTION value="group" {$group_selected}>{$APP.LBL_GROUP}</OPTION>
		</select>
        {/if}
	</td>

   </tr>

   <!-- Header for the Product Details -->
   <tr valign="top">
	<td width=15% class="lvtCol"><font color='red'>*</font><b>รายการสินค้า</b></td>
	<!-- <td width=5% class="lvtCol"><b>Product Type</b></td> -->
	<td width=5% class="lvtCol"><b>Km</b></td>
	<td width=5% class="lvtCol"><b>Zone</b></td>
	<td width=5% class="lvtCol"><b>ขนาดรถ</b></td>
	<td width=5% class="lvtCol"><b>หน่วย</b></td>
	<td width=5% class="lvtCol"><b>จำนวน</b></td>
	<td width=5% class="lvtCol"><b>ราคา/หน่วย</b></td>
	<td width=5% class="lvtCol"><b>จำนวนเงิน</b></td>
	<td width=5% class="lvtCol"><b>Min</b></td>
	<td width=5% class="lvtCol"><b>DLV_C</b></td>
	<td width=5% class="lvtCol"><b>DLV_C+VAT</b></td>
	<td width=5% class="lvtCol"><b>DLV_P+VAT</b></td>
	<td width=5% class="lvtCol"><b>LP</b></td>
	<td width=5% class="lvtCol"><b>ส่วนลด</b></td>
	<td width=5% class="lvtCol"><b>C_Cost</b></td>
	<td width=5% class="lvtCol"><b>ราคาหลังหักส่วนลด (ก่อน VAT)</b></td>
	<td width=5% class="lvtCol"><b>จำนวนเงิน(ซื้อ)</b></td>
   </tr>

   {foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
	{assign var="deleted" value="deleted"|cat:$row_no}
	{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
	{assign var="productName" value="productName"|cat:$row_no}
	{assign var="producttype" value="producttype"|cat:$row_no}
	{assign var="km" value="km"|cat:$row_no}
	{assign var="zone" value="zone"|cat:$row_no}
	{assign var="carsize" value="carsize"|cat:$row_no}
	{assign var="unit" value="unit"|cat:$row_no}

	{assign var="number" value="number"|cat:$row_no}
	{assign var="priceperunit" value="priceperunit"|cat:$row_no}
	{assign var="amount" value="amount"|cat:$row_no}
	{assign var="min" value="min"|cat:$row_no}
	{assign var="dlv_c" value="dlv_c"|cat:$row_no}
	{assign var="dlv_cvat" value="dlv_cvat"|cat:$row_no}
	{assign var="dlv_pvat" value="dlv_pvat"|cat:$row_no}
	{assign var="lp" value="lp"|cat:$row_no}
	{assign var="discount" value="discount"|cat:$row_no}
	{assign var="c_cost" value="c_cost"|cat:$row_no}
	{assign var="afterdiscount" value="afterdiscount"|cat:$row_no}
	{assign var="purchaseamount" value="purchaseamount"|cat:$row_no}
	
   <tr id="row{$row_no}" valign="top">

	<!-- column 1 - Product Name - start -->
	<td class="crmTableRow small lineOnTop">
		<!-- Product Re-Ordering Feature Code Addition Starts -->
		<input type="hidden" name="hidtax_row_no{$row_no}" id="hidtax_row_no{$row_no}" value="{$tax_row_no}"/>
		<!-- Product Re-Ordering Feature Code Addition ends -->
		<table width="100%"  border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td class="small" valign="top">
					{if $data.$producttype eq 'Product'}
						<span id="{$business_code}" name="{$business_code}" style="color:#C0C0C0;font-style:italic;">{$data.$business_code}</span>
						<div id="productCodeView{$row_no}" style="font-weight:bold;">{$data.$productcode}</div>
						<textarea id="{$productName}" name="{$productName}" class="small" style="width:90%;height:40px">{$data.$productName}</textarea>
						<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
						<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
						<input type="hidden" id="productcode{$row_no}" name="productcode{$row_no}" value="{$data.$productcode}" />
						&nbsp;
						<img id="searchIcon{$row_no}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no}')" />
					{else}
						<input id="{$productName}" name="{$productName}" type="text" class="small {$productName}" onfocus="this.className='{$productName} detailedViewTextBoxOn';" onblur="this.className='{$productName} detailedViewTextBox';" value="{$data.$productName}">
					{/if}
				</td>
			</tr>
		</table>
	</td>
	<!-- column 1 - Product Name - ends -->

	<!-- column 2 - Product Type - starts -->
	 {if $data.$producttype neq ''}
	   <!-- <td class="crmTableRow small lineOnTop" valign="top"> -->
	   	<input id="{$producttype}" name="{$producttype}" type="hidden" class="small producttype" style="width:50px;" onblur="this.className='detailedViewTextBox producttype';"  onfocus="this.className='detailedViewTextBoxOn producttype'" value="{$data.$producttype}"/>
	   <!-- </td> -->
	 {else}
	 	<!-- <td class="crmTableRow small lineOnTop" valign="top"></td> -->
	 {/if}
	<!-- column 2 - Product Type - ends -->

	<!-- column 3 - KM - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="km" name="km" type="text" class="small km" style="width:50px;background-color:#CCC;border: 1px solid  #999;" onblur="this.className='detailedViewTextBox km';"  onfocus="this.className='detailedViewTextBoxOn km'" value="{$data.$km}" readonly="readonly" /></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 3 - KM - ends -->

	<!-- column 4 - zone - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="zone" name="zone" type="text" class="small zone" style="width:50px;background-color:#CCC;border: 1px solid  #999;" onblur="this.className='detailedViewTextBox zone';"  onfocus="this.className='detailedViewTextBoxOn zone'" value="{$data.$zone}" readonly="readonly"/></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 4 - zone - ends -->

	<!-- column 5 - carsize - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="carsize" name="carsize" type="text" class="small carsize" style="width:50px;background-color:#CCC;border: 1px solid  #999;" onblur="this.className='detailedViewTextBox carsize';"  onfocus="this.className='detailedViewTextBoxOn carsize'" value="{$data.$carsize}" readonly="readonly"/></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 5 - carsize - ends -->

	<!-- column 6 - unit - starts -->
	 <td class="crmTableRow small lineOnTop" valign="top"><input id="{$unit}" name="{$unit}" type="text" class="small unit" style="width:50px;" onblur="this.className='detailedViewTextBox unit';"  onfocus="this.className='detailedViewTextBoxOn unit'" value="{$data.$unit}"/></td>
	<!-- column 6 - unit - ends -->

	<!-- column 7 - number - starts -->
	 <td class="crmTableRow small lineOnTop" valign="top"><input id="{$number}" name="{$number}" type="text" class="small number" style="width:50px;text-align: right;" onblur="this.className='detailedViewTextBox number';set_Cal2();set_Cal3();set_Cal4();set_Calctotal();set_Calctotal2();"  onfocus="this.className='detailedViewTextBoxOn number'" value="{$data.$number}"/></td>
	<!-- column 7 - number - ends -->

	<!-- column 8 - priceperunit - starts -->
	 <td class="crmTableRow small lineOnTop" valign="top"><input id="{$priceperunit}" name="{$priceperunit}" type="text" class="small priceperunit" style="width:50px;text-align: right;" onblur="this.className='detailedViewTextBox priceperunit';set_Cal2();set_Cal3();set_Cal4();set_Calctotal();"  onfocus="this.className='detailedViewTextBoxOn priceperunit'" value="{$data.$priceperunit}"/></td>
	<!-- column 8 - priceperunit - ends -->

	<!-- column 9 - amount - starts -->
	 <td class="crmTableRow small lineOnTop" valign="top"><input id="{$amount}" name="{$amount}" type="text" class="small amount" style="width:50px;background-color:#CCC;border: 1px solid  #999;text-align: right;" onblur="this.className='detailedViewTextBox amount';set_Calctotal();"  onfocus="this.className='detailedViewTextBoxOn amount'" value="{$data.$amount}" readonly="readonly" /></td>
	<!-- column 9 - amount - ends -->

	<!-- column 10 - min - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="min" name="min" type="text" class="small min" style="width:50px;background-color:#CCC;border: 1px solid  #999; text-align: right;" onblur="this.className='detailedViewTextBox min';"  onfocus="this.className='detailedViewTextBoxOn min'" value="{$data.$min}" readonly="readonly"/></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 10 - min - ends -->

	<!-- column 11 - dlv_c - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="dlv_c" name="dlv_c" type="text" class="small dlv_c" style="width:50px;background-color:#CCC;border: 1px solid  #999; text-align: right;" onblur="this.className='detailedViewTextBox dlv_c';"  onfocus="this.className='detailedViewTextBoxOn dlv_c'" value="{$data.$dlv_c}" readonly="readonly"/></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 11 - dlv_c - ends -->

	<!-- column 12 - dlv_cvat - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="dlv_cvat" name="dlv_cvat" type="text" class="small dlv_cvat" style="width:50px;background-color:#CCC;border: 1px solid  #999; text-align: right;" onblur="this.className='detailedViewTextBox dlv_cvat';"  onfocus="this.className='detailedViewTextBoxOn dlv_cvat'" value="{$data.$dlv_cvat}" readonly="readonly"/></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 12 - dlv_cvat - ends -->

	<!-- column 13 - dlv_pvat - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="dlv_pvat" name="dlv_pvat" type="text" class="small dlv_pvat" style="width:50px;background-color:#CCC;border: 1px solid  #999; text-align: right;" onblur="this.className='detailedViewTextBox dlv_pvat';"  onfocus="this.className='detailedViewTextBoxOn dlv_pvat'" value="{$data.$dlv_pvat}" readonly="readonly"/></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 13 - dlv_pvat - ends -->

	<!-- column 14 - lp - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="lp" name="lp" type="text" class="small lp" style="width:50px;background-color:#CCC;border: 1px solid  #999; text-align: right;" onblur="this.className='detailedViewTextBox lp';"  onfocus="this.className='detailedViewTextBoxOn lp'" value="{$data.$lp}" readonly="readonly"/></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 14 - lp - ends -->

	<!-- column 15 - dlv_pvat - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="discount" name="discount" type="text" class="small discount" style="width:50px;text-align: right;" onblur="this.className='detailedViewTextBox discount';set_discount();set_Calctotal2();"  onfocus="this.className='detailedViewTextBoxOn discount'" value="{$data.$discount}" /></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 15 - dlv_pvat - ends -->

	<!-- column 16 - c_cost - starts -->
	 {if $data.$producttype eq 'Product'}
	   <td class="crmTableRow small lineOnTop" valign="top"><input id="c_cost" name="c_cost" type="text" class="small c_cost" style="width:50px;background-color:#CCC;border: 1px solid  #999;text-align: right;" onblur="this.className='detailedViewTextBox c_cost';"  onfocus="this.className='detailedViewTextBoxOn c_cost'" value="{$data.$c_cost}" readonly="readonly" /></td>
	 {else}
	 	<td class="crmTableRow small lineOnTop" valign="top"></td>
	 {/if}
	<!-- column 16 - c_cost - ends -->

	<!-- column 17 - c_cost - starts -->
	 <td class="crmTableRow small lineOnTop" valign="top"><input id="{$afterdiscount}" name="{$afterdiscount}" type="text" class="small {$afterdiscount}" style="width:50px;text-align: right;" onblur="this.className='detailedViewTextBox {$afterdiscount}';set_afterdiscount2();set_afterdiscount3();set_afterdiscount4();set_Calctotal();set_Calctotal2();"  onfocus="this.className='detailedViewTextBoxOn c_cost'" value="{$data.$afterdiscount}" /></td>
	<!-- column 17 - c_cost - ends -->

	<!-- column 18 - c_cost - starts -->
	<td class="crmTableRow small lineOnTop" valign="top"><input id="{$purchaseamount}" name="{$purchaseamount}" type="text" class="small {$purchaseamount}" style="width:50px;background-color:#CCC;border:1px solid #999; text-align:right;" onblur="this.className='detailedViewTextBox {$purchaseamount}';"  onfocus="this.className='detailedViewTextBoxOn c_cost'" value="{$data.$purchaseamount}" readonly="readonly" /></td>
	<!-- column 18 - c_cost - ends -->

   </tr>
   <!-- Product Details First row - Ends -->
   {/foreach}
<!-- </table>

<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable"> -->
{assign var="FINAL" value=$ASSOCIATEDPRODUCTS.1.final_details}
	
   <tr valign="top">
   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>รวม</b></td>
	<td colspan="1" id="subTotal1" class="crmTableRow small lineOnTop" align="right">{$FINAL.subtotal1}</td>
	<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>รวม</b></td>
	<td colspan="1" id="subTotal2" class="crmTableRow small lineOnTop" align="right">{$FINAL.subtotal2}</td>
   </tr>

   <tr valign="top">
   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>ภาษีมุลค่าเพิ่ม 7%</b></td>
	<td colspan="1" id="Vat1" class="crmTableRow small lineOnTop" align="right">{$FINAL.vat1}</td>
	<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>ภาษีมุลค่าเพิ่ม 7%</b></td>
	<td colspan="1" id="Vat2" class="crmTableRow small lineOnTop" align="right">{$FINAL.vat2}</td>
   </tr>

   <tr valign="top">
   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>รวมทั้งสิ้น</b></td>
	<td colspan="1" id="Total1" class="crmTableRow small lineOnTop" align="right">{$FINAL.total1}</td>
	<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>รวมทั้งสิ้น</b></td>
	<td colspan="1" id="Total2" class="crmTableRow small lineOnTop" align="right">{$FINAL.total2}</td>
   </tr>
   
</table>
		
		<input type="hidden" name="profit" id="profit" value="">
		
		<input type="hidden" name="totalProductCount" id="totalProductCount" value="4">
		<input type="hidden" name="subtotal1" id="subtotal1" value="{$FINAL.subtotal1}">
		<input type="hidden" name="vat1" id="vat1" value="{$FINAL.vat1}">
		<input type="hidden" name="total1" id="total1" value="{$FINAL.total1}">
		<input type="hidden" name="subtotal2" id="subtotal2" value="{$FINAL.subtotal2}">
		<input type="hidden" name="vat2" id="vat2" value="{$FINAL.vat2}">
		<input type="hidden" name="total2" id="total2" value="{$FINAL.total2}">
 
 	</td>
</tr>
<!-- Upto this Added to display the Product Details -->



