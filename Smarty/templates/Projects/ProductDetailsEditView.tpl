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
<!-- Added to display the Product Details -->
<script type="text/javascript">
if(!e)
	window.captureEvents(Event.MOUSEMOVE);

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

			<tr valign="top">
				<td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
				<td width=15% class="lvtCol"><font color="red">* </font><b>Product code</b></td>
				<td width=8% align="center" class="lvtCol"><strong>Brand</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Product Group</strong></td>
				<td width=10% align="center" class="lvtCol"><strong>Dealer Product</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>First Delivered Date</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Last Delivered Date</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Estimated</strong></td>
				<td width=7% align="center" class="lvtCol"><strong>Plan</strong></td>
				<td width=7% align="center" class="lvtCol"><strong>Delivered</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>On hand</strong></td>
				<td width=8% align="center" class="lvtCol"><strong>Selling Price</strong></td>
			</tr>

				{foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
				{assign var="deleted" value="deleted"|cat:$row_no}
				{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
				{assign var="productName" value="productName"|cat:$row_no}
				{assign var="comment" value="comment"|cat:$row_no}
				{assign var="productDescription" value="productDescription"|cat:$row_no}
				{assign var="qtyInStock" value="qtyInStock"|cat:$row_no}
				{assign var="qty" value="qty"|cat:$row_no}
				{assign var="qty_act" value="qty_act"|cat:$row_no}
				{assign var="qty_ship" value="qty_ship"|cat:$row_no}
				{assign var="listPrice" value="listPrice"|cat:$row_no}
				{assign var="status_dtl" value="status_dtl"|cat:$row_no}
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
				{assign var="uom" value="uom"|cat:$row_no}
				{assign var="productcategory" value="productcategory"|cat:$row_no}
				{assign var="discountTotal" value="discountTotal"|cat:$row_no}
				{assign var="totalAfterDiscount" value="totalAfterDiscount"|cat:$row_no}
				{assign var="taxTotal" value="taxTotal"|cat:$row_no}
				{assign var="netPrice" value="netPrice"|cat:$row_no}

				{assign var="listprice_total" value="listprice_total"|cat:$row_no}
				{assign var="qty_remain" value="qty_remain"|cat:$row_no}

				{assign var="product_brand" value="product_brand"|cat:$row_no}
				{assign var="product_group" value="product_group"|cat:$row_no}
				{assign var="accountid" value="accountid"|cat:$row_no}
				{assign var="account_name" value="account_name"|cat:$row_no}
				{assign var="first_delivered_date" value="first_delivered_date"|cat:$row_no}
				{assign var="last_delivered_date" value="last_delivered_date"|cat:$row_no}
				{assign var="plan" value="plan"|cat:$row_no}
				{assign var="estimated" value="estimated"|cat:$row_no}
				{assign var="delivered" value="delivered"|cat:$row_no}
				{assign var="remain_on_hand" value="remain_on_hand"|cat:$row_no}
				{assign var="listprice" value="listprice"|cat:$row_no}

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
								<div id="productCodeView{$row_no}" style="font-weight:bold;"></div>
								<textarea id="{$productName}" name="{$productName}" readonly="readonly" class="detailedViewTextBox user-success" style="width:90%;height:40px">{$data.$productName}</textarea>
								<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
								<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
								
								{if $entityType eq 'Services' &&  $MODULE neq 'Projects' }
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

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$product_brand}" name="{$product_brand}" type="text" class="detailedViewTextBox " onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$product_brand}"/>
				</td>

				<td class="crmTableRow small lineOnTop" valign="top">
					<input data-rowno="{$row_no}" id="{$product_group}" name="{$product_group}" type="text" class="detailedViewTextBox "  onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$product_group}"/>
				</td>
				
				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$account_name}" name="{$account_name}" type="text" class="detailedViewTextBox account_name" onfocus="this.className='detailedViewTextBoxOn account_name'" onBlur="this.className='detailedViewTextBox account_name';" value="{$data.$account_name}" style="width: 80% !important" />
					<input type="hidden" name="{$accountid}" id="{$accountid}" value="{$data.$accountid}">
					<img id="searchIcon1" title="Dealer" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="accountPickList(this,'{$MODULE}','{$row_no}')" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="jscal_field_{$first_delivered_date}" name="{$first_delivered_date}" type="text" style="border:1px solid #bababa;width: 80% !important" size="11" maxlength="10" value="{$data.$first_delivered_date}" class="user-success">&nbsp;
					<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_{$first_delivered_date}" style="vertical-align: middle;position:absolute;">
					<br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>
					
					<script type="text/javascript" id="massedit_calendar_{$first_delivered_date}">
					Calendar.setup ({ldelim}
					inputField : "jscal_field_{$first_delivered_date}", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_{$first_delivered_date}", singleClick : true, step : 1 ,
					{rdelim})
					</script>
						
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="jscal_field_{$last_delivered_date}" name="{$last_delivered_date}" type="text" style="border:1px solid #bababa;width: 80% !important" size="11" maxlength="10" value="{$data.$last_delivered_date}" class="user-success">&nbsp;
					<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_{$last_delivered_date}" style="vertical-align: middle;position:absolute;">
					<br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>
					
					<script type="text/javascript" id="massedit_calendar_{$last_delivered_date}">
					Calendar.setup ({ldelim}
					inputField : "jscal_field_{$last_delivered_date}", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_{$last_delivered_date}", singleClick : true, step : 1 ,
					{rdelim})
					</script>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='{$row_no}' id="{$estimated}" name="{$estimated}" type="text" class="detailedViewTextBox estimated" onfocus="this.className='detailedViewTextBoxOn estimated'" onBlur="this.className='detailedViewTextBox estimated';" value="{$data.$estimated}"/>
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='{$row_no}' id="{$plan}" name="{$plan}" type="text" class="detailedViewTextBox plan" onfocus="this.className='detailedViewTextBoxOn plan'" onBlur="this.className='detailedViewTextBox plan';" value="{$data.$plan}" readonly style="background-color: #CCC;" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='{$row_no}' id="{$delivered}" name="{$delivered}" type="text" class="detailedViewTextBox delivered" onfocus="this.className='detailedViewTextBoxOn delivered'" onBlur="this.className='detailedViewTextBox delivered';" value="{$data.$delivered}" readonly style="background-color: #CCC;" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='{$row_no}' id="{$remain_on_hand}" name="{$remain_on_hand}" type="text" class="detailedViewTextBox remain_on_hand" onfocus="this.className='detailedViewTextBoxOn remain_on_hand'" onBlur="this.className='detailedViewTextBox remain_on_hand';" value="{$data.$remain_on_hand}" readonly style="background-color: #CCC;" />
				</td>

				<td align="left" class="crmTableRow small lineOnTop">
					<input data-rowno='{$row_no}' id="{$listprice}" name="{$listprice}" type="text" class="detailedViewTextBox listprice" onfocus="this.className='detailedViewTextBoxOn listprice'" onblur="this.className='detailedViewTextBox listprice';" value="{$data.$listprice}"/>
				</td>

			</tr>
			<!-- Product Details First row - Ends -->
			{/foreach}
		</table>

		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
		   <tr>
			<td colspan="3">
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowProjects('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Products
				</button>
			</td>
		   </tr>
			{assign var="FINAL" value=$ASSOCIATEDPRODUCTS.1.final_details}
		</table>

		<input type="hidden" name="totalProductCount" id="totalProductCount" value="{$row_no}">
		<input type="hidden" name="subtotal" id="subtotal" value="">
		<input type="hidden" name="total" id="total" value="">
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


<br/><br/>

<tr>
	<td colspan="9" align="left">

		<table width="100%"  border="0" align="center" cellpadding="11" cellspacing="0" class="crmTable" id="proTabCom">
			<tr>
				<td colspan="11" class="dvInnerHeader">
					<b>Competitor Product Inomation</b>
				</td>
			</tr>

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

				{foreach key=row_no1 item=data from=$ASSOCIATEDCOMPETITOR name=outer1}
				{assign var="deleted" value="deleted"|cat:$row_no1}
				{assign var="hdnCompetitorProductId" value="hdnCompetitorProductId"|cat:$row_no1}
				{assign var="CompetitorproductName" value="CompetitorproductName"|cat:$row_no1}
				{assign var="competitorcomment" value="competitorcomment"|cat:$row_no1}
				{assign var="entityIdentifier" value="entityType"|cat:$row_no1}
				{assign var="entityType" value=$data.$entityIdentifier}

				{assign var="competitor_brand" value="competitor_brand"|cat:$row_no1}
				{assign var="comprtitor_product_group" value="comprtitor_product_group"|cat:$row_no1}
				{assign var="comprtitor_product_size" value="comprtitor_product_size"|cat:$row_no1}
				{assign var="comprtitor_product_thickness" value="comprtitor_product_thickness"|cat:$row_no1}
				{assign var="comprtitor_estimated_unit" value="comprtitor_estimated_unit"|cat:$row_no1}
				{assign var="competitor_price" value="competitor_price"|cat:$row_no1}

			<tr id="rowcom{$row_no1}" valign="top">

				<!-- column 1 - delete link - starts -->
				<td  class="crmTableRow small lineOnTop" align="center">
					{if $row_no1 neq 1}
					<img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="deleteComRow('{$MODULE}',{$row_no1},'{$IMAGE_PATH}')">
					{/if}<br/><br/>
					{if $row_no1 neq 1}
					&nbsp;<a href="javascript:moveUpDownCom('UP','{$MODULE}',{$row_no1})" title="Move Upward"><img src="{'up_layout.gif'|@aicrm_imageurl:$THEME}" border="0"></a>
					{/if}
					{if not $smarty.foreach.outer1.last}
					&nbsp;<a href="javascript:moveUpDownCom('DOWN','{$MODULE}',{$row_no1})" title="Move Downward"><img src="{'down_layout.gif'|@aicrm_imageurl:$THEME}" border="0" ></a>
					{/if}
					<input type="hidden" id="{$deleted}" name="{$deleted}" value="0">
				</td>

				<!-- column 2 - Product Name - starts -->
				<td class="crmTableRow small lineOnTop">
					
					<!-- Product Re-Ordering Feature Code Addition ends -->
					<table width="100%"  border="0" cellspacing="0" cellpadding="1">
						<tr>
							<td class="small" valign="top">
								
								<textarea id="{$CompetitorproductName}" name="{$CompetitorproductName}" readonly="readonly" class="detailedViewTextBox user-success" style="width:90%;height:40px">{$data.$CompetitorproductName}</textarea>
								<input type="hidden" id="{$hdnCompetitorProductId}" name="{$hdnCompetitorProductId}" value="{$data.$hdnCompetitorProductId}" />
								<input type="hidden" id="lineItem{$row_no1}" name="lineItem{$row_no1}" value="{$entityType}" />
								<img id="searchIcon{$row_no1}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no1}')" />
								
							</td>
						</tr>
						
						<tr>
							<td class="small" id="setComment">
								<textarea id="{$competitorcomment}" name="{$competitorcomment}" class=detailedViewTextBox user-success style="width:90%;height:40px">{$data.$competitorcomment}</textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$competitorcomment}').value=''"; style="cursor:pointer;" />
							</td>
						</tr>
					</table>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no1}" id="{$competitor_brand}" name="{$competitor_brand}" type="text" class="detailedViewTextBox " onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$competitor_brand}"/>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no1}" id="{$comprtitor_product_group}" name="{$comprtitor_product_group}" type="text" class="detailedViewTextBox " onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$comprtitor_product_group}"/>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no1}" id="{$comprtitor_product_size}" name="{$comprtitor_product_size}" type="text" class="detailedViewTextBox " onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$comprtitor_product_size}"/>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no1}" id="{$comprtitor_product_thickness}" name="{$comprtitor_product_thickness}" type="text" class="detailedViewTextBox " onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$comprtitor_product_thickness}"/>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no1}" id="{$comprtitor_estimated_unit}" name="{$comprtitor_estimated_unit}" type="text" class="detailedViewTextBox " onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$comprtitor_estimated_unit}"/>
				</td>

				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no1}" id="{$competitor_price}" name="{$competitor_price}" type="text" class="detailedViewTextBox " onfocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox';"  value="{$data.$competitor_price}"/>
				</td>

			</tr>
			<!-- Product Details First row - Ends -->
			{/foreach}
		</table>

		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
		   <tr>
			<td colspan="3">
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddCompetitorproductRow('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Competitor Product
				</button>
			</td>
		   </tr>
			
		</table>

		<input type="hidden" name="totalCompetitorProduct" id="totalCompetitorProduct" value="{$row_no1}">
	</td>
</tr>