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
	<td colspan="9" align="left">

		<table width="100%"  border="0" align="center" cellpadding="12" cellspacing="0" class="crmTable" id="proTab">
			<tr>
				<td colspan="11" class="dvInnerHeader">
					<b>{$APP.LBL_ITEM_DETAILS}</b>
				</td>

				<td class="dvInnerHeader" align="center" colspan="1">
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
			<!-- Header for the Product Details -->
			<tr valign="top">
				<td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
				<td width=15% class="lvtCol"><font color="red">* </font><b>Product code</b></td>
				<td width=8% align="center" class="lvtCol"><strong>Brand</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Product Group</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>Dealer Product</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>First Delivered Date</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Last Delivered Date</strong></td>
				<!-- <td width=7% align="center" class="lvtCol"><strong>Estimate/Plan/Delivered</td> -->
				<td width=8% align="center" class="lvtCol"><strong>Estimated</strong></td>
				<td width=7% align="center" class="lvtCol"><strong>Plan</strong></td>
				<td width=7% align="center" class="lvtCol"><strong>Delivered</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>On hand</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Selling Price</strong></td>
			</tr>

			<tr valign="top" id="row1">

				<td  class="crmTableRow small lineOnTop" align="center">&nbsp;
					<input type="hidden" id="deleted1" name="deleted1" value="0">
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
						<tr>
							<td class="small">
								<span id="business_code1" name="business_code1" style="color:#C0C0C0;font-style:italic;"> </span>
								<div id="productCodeView{$row_no}" style="font-weight:bold;"></div>
								<textarea id="productName1" name="productName1" readonly="readonly" class="detailedViewTextBox user-success" style="width:90%;height:40px">{$PRODUCT_NAME}</textarea>

								<input type="hidden" id="hdnProductId1" name="hdnProductId1" value="{$PRODUCT_ID}" />
								<input type="hidden" id="lineItemType1" name="lineItemType1" value="Products" />
								<img id="searchIcon1" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}',1)" />
							</td>
						</tr>
						<tr>
							<td class="small">
								<input type="hidden" value="" id="subproduct_ids1" name="subproduct_ids1" />
								<span id="subprod_names1" name="subprod_names1" style="color:#C0C0C0;font-style:italic;"> </span></td>
						</tr>
						<tr valign="bottom">
							<td class="small" id="setComment">
								<textarea id="comment1" name="comment1" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('comment1').value=''"; style="cursor:pointer;" /></td>
						</tr>
					</table>
				</td>
				
				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="product_brand1" name="product_brand1" type="text" class="detailedViewTextBox product_brand" onfocus="this.className='detailedViewTextBoxOn product_brand'" onBlur="this.className='detailedViewTextBox product_brand';" value=""/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="product_group1" name="product_group1" type="text" class="detailedViewTextBox product_group" onfocus="this.className='detailedViewTextBoxOn product_group'" onBlur="this.className='detailedViewTextBox product_group';" value=""/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input id="account_name1" name="account_name1" type="text" class="detailedViewTextBox account_name" onfocus="this.className='detailedViewTextBoxOn account_name'" onBlur="this.className='detailedViewTextBox account_name';" value="" style="width: 80% !important" />
					<input type="hidden" name="accountid1" id="accountid1" value="0">
					<img id="searchIcon1" title="Dealer" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="accountPickList(this,'{$MODULE}',1)" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1'id="jscal_field_first_delivered_date1" name="first_delivered_date1" type="text" style="border:1px solid #bababa;width: 80% !important" size="11" maxlength="10" value="" class="user-success">&nbsp;
					<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_first_delivered_date1" style="vertical-align: middle;position:absolute;">
					<br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>
					{literal}
					<script type="text/javascript" id="massedit_calendar_first_delivered_date1">
					Calendar.setup ({
					inputField : "jscal_field_first_delivered_date1", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_first_delivered_date1", singleClick : true, step : 1 ,
					})
					</script>
					{/literal}	
				</td>
				
				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1'id="jscal_field_last_delivered_date1" name="last_delivered_date1" type="text" style="border:1px solid #bababa;width: 80% !important" size="11" maxlength="10" value="" class="user-success">&nbsp;
					<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_last_delivered_date1" style="vertical-align: middle;position:absolute;">
					<br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>
					{literal}
					<script type="text/javascript" id="massedit_calendar_last_delivered_date1">
					Calendar.setup ({
					inputField : "jscal_field_last_delivered_date1", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_last_delivered_date1", singleClick : true, step : 1 ,
					})
					</script>
					{/literal}	
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="estimated1" name="estimated1" type="text" class="detailedViewTextBox estimated" onfocus="this.className='detailedViewTextBoxOn estimated'" onBlur="this.className='detailedViewTextBox estimated';" value="0"/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="plan1" name="plan1" type="text" class="detailedViewTextBox plan" onfocus="this.className='detailedViewTextBoxOn plan'" onBlur="this.className='detailedViewTextBox plan';" value="0" readonly style="background-color: #CCC;" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="delivered1" name="delivered1" type="text" class="detailedViewTextBox delivered" onfocus="this.className='detailedViewTextBoxOn delivered'" onBlur="this.className='detailedViewTextBox delivered';" value="0" readonly style="background-color: #CCC;" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="remain_on_hand1" name="remain_on_hand1" type="text" class="detailedViewTextBox remain_on_hand" onfocus="this.className='detailedViewTextBoxOn remain_on_hand'" onBlur="this.className='detailedViewTextBox remain_on_hand';" value="0" readonly style="background-color: #CCC;" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="listPrice1" name="listPrice1" type="text" class="detailedViewTextBox listPrice" onfocus="this.className='detailedViewTextBoxOn listPrice'" onblur="this.className='detailedViewTextBox listPrice';" value="0"/>
				</td>

			</tr>
			<!-- Product Details First row - Ends -->
		</table>
		<!-- Upto this has been added for form the first row. Based on these above we should form additional rows using script -->

		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
			<!-- Add Product Button -->
			<tr>
				<td colspan="3">
					<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowProjects('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
						<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Products
					</button>
				</td>
			</tr>
		</table>

		<input type="hidden" name="totalProductCount" id="totalProductCount" value="1">
		<input type="hidden" name="subtotal" id="subtotal" value="">
		<input type="hidden" name="total" id="total" value="">

	</td>
</tr>

<br>

<tr>
	<td colspan="9" align="left">

		<table width="100%"  border="0" align="center" cellpadding="12" cellspacing="0" class="crmTable" id="proTabCom">
			<tr>
				<td colspan="11" class="dvInnerHeader">
					<b>Competitor Product Inomation</b>
				</td>
			</tr>
			<!-- Header for the Product Details -->
			<tr valign="top">
				<td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
				<td width=15% class="lvtCol"><font color="red">* </font><b>Competitor Product Item</b></td>
				<td width=8% align="center" class="lvtCol"><strong>Competitor Brand</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Competitor Product Group</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>Competitor Product Size</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Competitor Product Thickness</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Competitor Estimated unit</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Competitor Price</strong></td>
			</tr>

			<tr valign="top" id="rowcom1">

				<td  class="crmTableRow small lineOnTop" align="center">&nbsp;
					<input type="hidden" id="deletedCom1" name="deletedCom1" value="0">
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
						<tr>
							<td class="small">
								<textarea id="CompetitorproductName1" name="CompetitorproductName1" readonly="readonly" class="detailedViewTextBox user-success" style="width:90%;height:40px">{$COMPETITORPRODUCT_NAME}</textarea>
								<input type="hidden" id="hdnCompetitorProductId1" name="hdnCompetitorProductId1" value="{$COMPETITORPRODUCT_ID}" />
								<input type="hidden" id="lineItem1" name="lineItem1" value="Competitorproduct" />
								<img id="searchIconCompetitor1" title="Competitor Product" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="competitorproductPickList(this,'{$MODULE}',1)" />
							</td>
						</tr>
						
						<tr valign="bottom">
							<td class="small" id="setComment">
								<textarea id="competitorcomment1" name="competitorcomment1" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('competitorcomment1').value=''"; style="cursor:pointer;" /></td>
						</tr>
					</table>
				</td>
				
				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="competitor_brand1" name="competitor_brand1" type="text" class="detailedViewTextBox competitor_brand" onfocus="this.className='detailedViewTextBoxOn competitor_brand'" onBlur="this.className='detailedViewTextBox competitor_brand';" value=""/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="comprtitor_product_group1" name="comprtitor_product_group1" type="text" class="detailedViewTextBox comprtitor_product_group" onfocus="this.className='detailedViewTextBoxOn comprtitor_product_group'" onBlur="this.className='detailedViewTextBox comprtitor_product_group';" value=""/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="comprtitor_product_size1" name="comprtitor_product_size1" type="text" class="detailedViewTextBox comprtitor_product_size" onfocus="this.className='detailedViewTextBoxOn comprtitor_product_size'" onBlur="this.className='detailedViewTextBox comprtitor_product_size';" value="" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="comprtitor_product_thickness1" name="comprtitor_product_thickness1" type="text" class="detailedViewTextBox comprtitor_product_thickness" onfocus="this.className='detailedViewTextBoxOn comprtitor_product_thickness'" onBlur="this.className='detailedViewTextBox comprtitor_product_thickness';" value=""/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="comprtitor_estimated_unit1" name="comprtitor_estimated_unit1" type="text" class="detailedViewTextBox comprtitor_estimated_unit" onfocus="this.className='detailedViewTextBoxOn comprtitor_estimated_unit'" onBlur="this.className='detailedViewTextBox comprtitor_estimated_unit';" value=""/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='1' id="competitor_price1" name="competitor_price1" type="text" class="detailedViewTextBox competitor_price" onfocus="this.className='detailedViewTextBoxOn competitor_price'" onblur="this.className='detailedViewTextBox competitor_price';" value="0"/>
				</td>

			</tr>
			<!-- Product Details First row - Ends -->
		</table>
		<!-- Upto this has been added for form the first row. Based on these above we should form additional rows using script -->

		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
			<tr>
				<td colspan="3">
					<button title="Add New Item" class="crmbutton small save" onclick="fnAddCompetitorproductRow('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
						<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Competitor Product
					</button>
				</td>
			</tr>
		</table>
		<input type="hidden" name="totalCompetitorProduct" id="totalCompetitorProduct" value="1">
	</td>
</tr>
