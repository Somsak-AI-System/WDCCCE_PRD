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
if(!e)
	window.captureEvents(Event.MOUSEMOVE);

//  window.onmousemove= displayCoords;
//  window.onclick = fnRevert;

</script>

<!-- Added this file to display and hanld the Product Details in Inventory module  -->

   <tr>
	<td colspan="4" align="left">
<div id="dialog" style="display:none;">Dialog Content.</div>


<table width="100%"  border="0" align="center" cellpadding="17" cellspacing="0" class="crmTable" id="proTab">
   <tr>
	<td colspan="6" class="dvInnerHeader">
		<b>{$APP.LBL_ITEM_DETAILS}</b>
	</td>

	<td class="dvInnerHeader" align="center" colspan="4">
		<b>Price type</b>&nbsp;&nbsp;
		<select class="small" id="pricetype" name="pricetype" readonly="readonly" >
			<option value="Exclude Vat">Exclude Vat</option>
			<option value="Include Vat">Include Vat</option>
		</select>
	</td>
	<td class="dvInnerHeader" align="center" colspan="5">
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

	<td class="dvInnerHeader" align="center" colspan="3">
		<b>{$APP.LBL_TAX_MODE}</b>&nbsp;
        {if $MODULE eq 'Quotes'}
        	<input type="hidden" id="taxtype" name="taxtype" value="group"><b>{$APP.LBL_GROUP}</b>
        {else}
		<select id="taxtype" name="taxtype" onchange="decideTaxDiv();">
			<OPTION value="group" selected>{$APP.LBL_GROUP}</OPTION>
			<OPTION value="individual" >{$APP.LBL_INDIVIDUAL}</OPTION>
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
   
   <!-- Following code is added for form the first row. Based on these we should form additional rows using script -->
   <!-- Product Details First row - Starts -->
   <tr valign="top" id="row1">

	<!-- column 2 - รายการสินค้า - starts -->
	<td class="crmTableRow small lineOnTop">
		<table width="100%"  border="0" cellspacing="0" cellpadding="1">
		   <tr>
			<td class="small">
				<span id="business_code" name="business_code" style="color:#C0C0C0;font-style:italic;"> </span>
				<div id="productCodeView" style="font-weight:bold;"></div>
                <textarea id="productName1" name="productName1"  class="small" style="width:85%;height:40px"  >{$PRODUCT_NAME}</textarea>
			    <input type="hidden" id="hdnProductId1" name="hdnProductId1" value="{$PRODUCT_ID}"/>
				<input type="hidden" id="lineItemType" name="lineItemType" value="Products" />
				<input type="hidden" id="productcode" name="productcode" value="" />
				<img id="searchIcon1" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}',1)" />
			</td>
		</tr>
		<tr>
			<td class="small">
				<input type="hidden" value="" id="subproduct_ids" name="subproduct_ids" />
				<span id="subprod_names" name="subprod_names" style="color:#C0C0C0;font-style:italic;"> </span>
			</td>
		   </tr>
		</table>
	</td>
	<!-- column 2 - รายการสินค้า - ends -->

	<!-- column 3 - Product type - starts -->
	<!-- <td class="crmTableRow small lineOnTop"> -->
		<input id="producttype1" name="producttype1" type="hidden" class="small producttype1" onfocus="this.className='producttype1 detailedViewTextBoxOn';" onblur="this.className='producttype1 detailedViewTextBox';"  value="Product" style="width:40px; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" />
	<!-- </td> -->
	<!-- column 3 - Product type - ends -->

	<!-- column 4 - Km - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="km" name="km" type="text" class="small km" onfocus="this.className='km detailedViewTextBoxOn'" onblur="this.className='km detailedViewTextBox';" readonly="readonly" style="width:40px; background-color:#CCC;border: 1px solid  #999;"/>
	</td>
	<!-- column 4 - Km - ends -->

	<!-- column 5 - Zone - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="zone" name="zone" type="text" class="small zone" onfocus="this.className='zone detailedViewTextBoxOn'" onblur="this.className='zone detailedViewTextBox';" readonly="readonly" style="width:40px; background-color:#CCC;border: 1px solid  #999;"/>
	</td>
	<!-- column 5 - Zone - ends -->

	<!-- column 6 - ขนาดรถ - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="carsize" name="carsize" type="text" class="small carsize" onfocus="this.className='carsize detailedViewTextBoxOn'" onblur="this.className='carsize detailedViewTextBox';" readonly="readonly" style="width:40px; background-color:#CCC;border: 1px solid  #999;"/>
	</td>
	<!-- column 6 - ขนาดรถ - ends -->

	<!-- column 7 - หน่วย - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="unit1" name="unit1" type="text" class="small unit1" onfocus="this.className='unit1 detailedViewTextBoxOn'" onblur="this.className='unit1 detailedViewTextBox';" style="width:40px;" />
	</td>
	<!-- column 7 - หน่วย - ends -->

	<!-- column 8 - จำนวน - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="number1" name="number1" type="text" class="small number1" onfocus="this.className='number1 detailedViewTextBoxOn'" onblur="this.className='number1 detailedViewTextBox';set_Calctotal();set_Calctotal2();" style="width:40px; text-align: right;" value="0" />
	</td>
	<!-- column 8 - จำนวน - ends -->

	<!-- column 9 - ราคา/หน่วย - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="priceperunit1" name="priceperunit1" type="text" class="small priceperunit1" onfocus="this.className='priceperunit1 detailedViewTextBoxOn'" onblur="this.className='priceperunit1 detailedViewTextBox';set_Calctotal();" style="width:40px;text-align: right;" value="0"/>
	</td>
	<!-- column 9 - ราคา/หน่วย - ends -->

	<!-- column 10 - จำนวนเงิน - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="amount1" name="amount1" type="text" class="small amount1" onfocus="this.className='amount1 detailedViewTextBoxOn'" onblur="this.className='amount1 detailedViewTextBox';" style="width:40px; background-color:#CCC;text-align: right;border: 1px solid  #999;" readonly="readonly" value="0" />
	</td>
	<!-- column 10 - จำนวนเงิน - ends -->

	<!-- column 11 - Min - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="min" name="min" type="text" class="small min" onfocus="this.className='min detailedViewTextBoxOn'" onblur="this.className='min detailedViewTextBox';" style="width:40px;text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly"/>
	</td>
	<!-- column 11 - Min - ends -->

	<!-- column 12 - DLV_C - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="dlv_c" name="dlv_c" type="text" class="small dlv_c" onfocus="this.className='dlv_c detailedViewTextBoxOn'" onblur="this.className='dlv_c detailedViewTextBox';" style="width:40px;text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly"/>
	</td>
	<!-- column 12 - DLV_C - ends -->

	<!-- column 13 - DLV_C+VAT - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="dlv_cvat" name="dlv_cvat" type="text" class="small dlv_cvat" onfocus="this.className='dlv_cvat detailedViewTextBoxOn'" onblur="this.className='dlv_cvat detailedViewTextBox';" style="width:40px;text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly"/>
	</td>
	<!-- column 13 - DLV_C+VAT - ends -->

	<!-- column 14 - DLV_P+VAT - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="dlv_pvat" name="dlv_pvat" type="text" class="small dlv_pvat" onfocus="this.className='dlv_pvat detailedViewTextBoxOn'" onblur="this.className='dlv_pvat detailedViewTextBox';" style="width:40px; text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly"/>
	</td>
	<!-- column 14 - DLV_P+VAT - ends -->

	<!-- column 15 - LP - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="lp" name="lp" type="text" class="small lp" onfocus="this.className='lp detailedViewTextBoxOn'" onblur="this.className='lp detailedViewTextBox';" style="width:40px; text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" />
	</td>
	<!-- column 15 - LP - ends -->

	<!-- column 16 - ส่วนลด - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="discount" name="discount" type="text" class="small discount" onfocus="this.className='discount detailedViewTextBoxOn'" onblur="this.className='discount detailedViewTextBox';set_discount();set_Calctotal2();" style="width:40px; text-align: right;" />
	</td>
	<!-- column 16 - ส่วนลด - ends -->

	<!-- column 17 - C_Cost - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="c_cost" name="c_cost" type="text" class="small c_cost" onfocus="this.className='c_cost detailedViewTextBoxOn'" onblur="this.className='c_cost detailedViewTextBox';" style="width:40px; text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" />
	</td>
	<!-- column 17 - C_Cost - ends -->

	<!-- column 18 - ราคาหลังหักส่วนลด - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="afterdiscount1" name="afterdiscount1" type="text" class="small afterdiscount1" onfocus="this.className='afterdiscount1 detailedViewTextBoxOn'" onblur="this.className='afterdiscount1 detailedViewTextBox';set_Calctotal();set_Calctotal2();" style="width:40px;text-align: right;" value="0" />
	</td>
	<!-- column 18 - ราคาหลังหักส่วนลด - ends -->

	<!-- column 19 - จำนวนเงินซื้อ - starts -->
	<td class="crmTableRow small lineOnTop">
		<input data-rowno="1" id="purchaseamount1" name="purchaseamount1" type="text" class="small purchaseamount1" onfocus="this.className='purchaseamount1 detailedViewTextBoxOn'" onblur="this.className='purchaseamount1 detailedViewTextBox';" style="width:40px;text-align: right;background-color:#CCC;border: 1px solid  #999;" readonly="readonly" value="0" />
	</td>
	<!-- column 19 - จำนวนเงินซื้อ - ends -->
   </tr>

   <tr>
   	<td class="crmTableRow small lineOnTop">
   		<input id="productName2" name="productName2" type="text" class="small productName2" onfocus="this.className='productName2 detailedViewTextBoxOn';" onblur="this.className='productName2 detailedViewTextBox';"  value="ค่าขนส่ง (ไม่เต็มเที่ยว)" style="width:200px;" />
   	</td>
   	<!-- <td class="crmTableRow small lineOnTop"> -->
   		<input id="producttype2" name="producttype2" type="hidden" class="small producttype2" onfocus="this.className='producttype2 detailedViewTextBoxOn';" onblur="this.className='producttype2 detailedViewTextBox';"  value="Service" style="width:40px; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" />
   	<!-- </td> -->
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="unit2" name="unit2" type="text" class="small unit2" onfocus="this.className='unit2 detailedViewTextBoxOn'" onblur="this.className='unit2 detailedViewTextBox';" style="width:40px;" value="รายการ" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="number2" name="number2" type="text" class="small number2" onfocus="this.className='number2 detailedViewTextBoxOn'" onblur="this.className='number2 detailedViewTextBox';set_Cal2(); set_Calctotal();" style="width:40px;text-align: right;" value="0"/>
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="priceperunit2" name="priceperunit2" type="text" class="small priceperunit2" onfocus="this.className='priceperunit2 detailedViewTextBoxOn'" onblur="this.className='priceperunit2 detailedViewTextBox';set_Cal2();set_Calctotal();" style="width:40px;text-align: right;" value="0"/>
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="amount2" name="amount2" type="text" class="small amount2" onfocus="this.className='amount2 detailedViewTextBoxOn'" onblur="this.className='amount2 detailedViewTextBox';" style="width:40px; text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="afterdiscount2" name="afterdiscount2" type="text" class="small afterdiscount2" onfocus="this.className='afterdiscount2 detailedViewTextBoxOn'" onblur="this.className='afterdiscount2 detailedViewTextBox';set_afterdiscount2();set_Calctotal2();" style="width:40px;text-align: right;" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="purchaseamount2" name="purchaseamount2" type="text" class="small amount" onfocus="this.className='purchaseamount2 detailedViewTextBoxOn'" style="width:40px; text-align: right; background-color:#CCC;border: 1px solid  #999;" value="0" onblur="this.className='purchaseamount2 detailedViewTextBox';" readonly="readonly"/>
   	</td>
   </tr>

   <tr>
   	<td class="crmTableRow small lineOnTop">
   		<input id="productName3" name="productName3" type="text" class="small productName3" onfocus="this.className='productName3 detailedViewTextBoxOn';" onblur="this.className='productName3 detailedViewTextBox';"  value="ค่าบริการ ปั๊มลาก (ปริมาณคอนกรีตผ่านปั๊มไม่เกิน 50 ลบ.ม.)" style="width:200px;" />
   	</td>
   	<!-- <td class="crmTableRow small lineOnTop"></td> -->
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="unit3" name="unit3" type="text" class="small unit3" onfocus="this.className='unit3 detailedViewTextBoxOn'" onblur="this.className='unit3 detailedViewTextBox';" style="width:40px;" value="รายการ" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="number3" name="number3" type="text" class="small number3" onfocus="this.className='number3 detailedViewTextBoxOn'" onblur="this.className='number3 detailedViewTextBox';set_Cal3();set_Calctotal();" style="width:40px; text-align: right;" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="priceperunit3" name="priceperunit3" type="text" class="small priceperunit3" onfocus="this.className='priceperunit3 detailedViewTextBoxOn'" onblur="this.className='priceperunit3 detailedViewTextBox';set_Cal3();set_Calctotal();" style="width:40px;text-align: right;" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="amount3" name="amount3" type="text" class="small amount3" onfocus="this.className='amount3 detailedViewTextBoxOn'" onblur="this.className='amount3 detailedViewTextBox';" style="width:40px;text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" value="0"/>
   	</td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="afterdiscount3" name="afterdiscount3" type="text" class="small afterdiscount3" onfocus="this.className='afterdiscount3 detailedViewTextBoxOn'" onblur="this.className='afterdiscount3 detailedViewTextBox';set_afterdiscount3();set_Calctotal2();"  style="width:40px;text-align: right;" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="purchaseamount3" name="purchaseamount3" type="text" class="small purchaseamount3" onfocus="this.className='purchaseamount3 detailedViewTextBoxOn'" onblur="this.className='purchaseamount3 detailedViewTextBox';"  style="width:40px;text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" value="0" />
   	</td>
   </tr>

   <tr>
   	<td class="crmTableRow small lineOnTop">
   		<input id="productName4" name="productName4" type="text" class="small productName4" onfocus="this.className='productName4 detailedViewTextBoxOn';" onblur="this.className='productName4 detailedViewTextBox';"  value="ค่าบริการ เก็บตัวอย่างก้อนปูน (Cube)" style="width:200px;" />
   	</td>
   	<!-- <td class="crmTableRow small lineOnTop"></td> -->
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="unit4" name="unit4" type="text" class="small unit4" onfocus="this.className='unit4 detailedViewTextBoxOn'" onblur="this.className='unit4 detailedViewTextBox';" style="width:40px;" value="รายการ" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="number4" name="number4" type="text" class="small number4" onfocus="this.className='number4 detailedViewTextBoxOn'" onblur="this.className='number4 detailedViewTextBox';set_Cal4();set_Calctotal();" style="width:40px; text-align: right;" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="priceperunit4" name="priceperunit4" type="text" class="small priceperunit4" onfocus="this.className='priceperunit4 detailedViewTextBoxOn'" onblur="this.className='priceperunit4 detailedViewTextBox';set_Cal4();set_Calctotal();" style="width:40px;text-align: right;" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="amount4" name="amount4" type="text" class="small amount4" onfocus="this.className='amount4 detailedViewTextBoxOn'" onblur="this.className='amount4 detailedViewTextBox';" style="width:40px; text-align: right; background-color:#CCC;border: 1px solid  #999;" value="0" readonly="readonly"/>
   	</td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop"></td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="afterdiscount4" name="afterdiscount4" type="text" class="small afterdiscount4" onfocus="this.className='afterdiscount4 detailedViewTextBoxOn'" onblur="this.className='afterdiscount4 detailedViewTextBox';set_afterdiscount4();set_Calctotal2();" style="width:40px;text-align: right;" value="0" />
   	</td>
   	<td class="crmTableRow small lineOnTop">
   		<input data-rowno="1" id="purchaseamount4" name="purchaseamount4" type="text" class="small purchaseamount4" onfocus="this.className='purchaseamount4 detailedViewTextBoxOn'" onblur="this.className='purchaseamount4 detailedViewTextBox';" style="width:40px; text-align: right; background-color:#CCC;border: 1px solid  #999;" readonly="readonly" value="0" />
   	</td>
   </tr>

   <tr valign="top">
   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>รวม</b></td>
	<td colspan="1" id="subTotal1" class="crmTableRow small lineOnTop" align="right">0.00</td>
	<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>รวม</b></td>
	<td colspan="1" id="subTotal2" class="crmTableRow small lineOnTop" align="right">0.00</td>
   </tr>

   <tr valign="top">
   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>ภาษีมุลค่าเพิ่ม 7%</b></td>
	<td colspan="1" id="Vat1" class="crmTableRow small lineOnTop" align="right">0.00</td>
	<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>ภาษีมุลค่าเพิ่ม 7%</b></td>
	<td colspan="1" id="Vat2" class="crmTableRow small lineOnTop" align="right">0.00</td>
   </tr>

   <tr valign="top">
   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>รวมทั้งสิ้น</b></td>
	<td colspan="1" id="Total1" class="crmTableRow small lineOnTop" align="right">0.00</td>
	<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>รวมทั้งสิ้น</b></td>
	<td colspan="1" id="Total2" class="crmTableRow small lineOnTop" align="right">0.00</td>
   </tr>
   <!-- Product Details First row - Ends -->
</table>
	
	<input type="hidden" name="profit" id="profit" value="">

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="4">

	<input type="hidden" name="subtotal1" id="subtotal1" value="">
	<input type="hidden" name="vat1" id="vat1" value="">
	<input type="hidden" name="total1" id="total1" value="">

	<input type="hidden" name="subtotal2" id="subtotal2" value="">
	<input type="hidden" name="vat2" id="vat2" value="">
	<input type="hidden" name="total2" id="total2" value="">

	</td>
   </tr>

