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
{if empty($ASSOCIATEDPRODUCTS)}
<tr>
	<td colspan="7" align="left">
	<table width="100%"  border="0" align="center" cellpadding="9" cellspacing="0" class="crmTable" id="proTab">
		<tr>
			<td colspan="11" class="dvInnerHeader">
				<b>{$APP.LBL_ITEM_DETAILS}</b>
				<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
				<input type="hidden" value="" id="inventory_currency" />
				<input type="hidden" value="" id="taxtype" />
			</td>
		</tr>
		<!-- Header for the Product Details -->
		<tr valign="top">
			<td width=4% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
			<td width=24% class="lvtCol"><font color='red'>* </font><b>รายการสินค้า</b></td>
			<td width=8% class="lvtCol" align="center"><strong>Brand</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>จำนวนแผ่น/กล่อง</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>สถานะของสินค้า</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Showroom</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>List Price</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Normal</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 1</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 2</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 3</strong></td>
		</tr>

		<tr valign="top" id="row1">
			<td  class="crmTableRow small lineOnTop" align="center">&nbsp;
				<input type="hidden" id="deleted1" name="deleted1" value="0">
			</td>
			
			<td class="crmTableRow small lineOnTop">
				<table width="100%"  border="0" cellspacing="0" cellpadding="1">
					<tr>
						<td class="small">
							<input id="productName1" name="productName1" class="small" style="width: 70%;" value="" readonly="readonly" type="text">
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
							<textarea id="comment1" name="comment1" class="detailedViewTextBox user-success" style="width:70%;height:40px"></textarea>
							<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}getObj{/literal}('comment1').value=''"; style="cursor:pointer;" /></td>
					</tr>
				</table>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="product_brand1" name="product_brand1" type="text" class="detailedViewTextBox product_brand" onfocus="this.className='detailedViewTextBoxOn product_brand'"  onBlur="this.className='detailedViewTextBox product_brand';"  value="" readonly="readonly" style="background-color:#CCC;border: 1px solid #999;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="product_weight_per_box1" name="product_weight_per_box1" type="text" class="detailedViewTextBox product_weight_per_box" onfocus="this.className='detailedViewTextBoxOn product_weight_per_box'"  onBlur="this.className='detailedViewTextBox product_weight_per_box';"  value="" readonly="readonly" style="background-color:#CCC;border: 1px solid #999;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="productstatus1" name="productstatus1" type="text" class="detailedViewTextBox productstatus" onfocus="this.className='detailedViewTextBoxOn productstatus'"  onBlur="this.className='detailedViewTextBox productstatus';"  value="" readonly="readonly" style="background-color:#CCC;border: 1px solid #999;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="pricelist_showroom1" name="pricelist_showroom1" type="text" class="detailedViewTextBox pricelist_showroom" onfocus="this.className='detailedViewTextBoxOn pricelist_showroom'"  onBlur="this.className='detailedViewTextBox pricelist_showroom';"  value=""/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="listprice_project1" name="listprice_project1" type="text" class="detailedViewTextBox listprice_project" onfocus="this.className='detailedViewTextBoxOn listprice_project'"  onBlur="this.className='detailedViewTextBox listprice_project';"  value=""/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="pricelist_nomal1" name="pricelist_nomal1" type="text" class="detailedViewTextBox pricelist_nomal" onfocus="this.className='detailedViewTextBoxOn pricelist_nomal'"  onBlur="this.className='detailedViewTextBox pricelist_nomal';"  value=""/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="pricelist_first_tier1" name="pricelist_first_tier1" type="text" class="detailedViewTextBox pricelist_first_tier" onfocus="this.className='detailedViewTextBoxOn pricelist_first_tier'"  onBlur="this.className='detailedViewTextBox pricelist_first_tier';"  value=""/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="pricelist_second_tier1" name="pricelist_second_tier1" type="text" class="detailedViewTextBox pricelist_second_tier" onfocus="this.className='detailedViewTextBoxOn pricelist_second_tier'"  onBlur="this.className='detailedViewTextBox pricelist_second_tier';"  value=""/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="1" id="pricelist_third_tier1" name="pricelist_third_tier1" type="text" class="detailedViewTextBox pricelist_third_tier" onfocus="this.className='detailedViewTextBoxOn pricelist_third_tier'"  onBlur="this.className='detailedViewTextBox pricelist_third_tier';"  value=""/>
			</td>
		</tr>
	</table>

	<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	    <!-- Add Product Button -->
	   	<tr>
			<td colspan="3">
				<button title="{$APP.LBL_ADD_PRODUCT}" class="crmbutton small save" onclick="fnAddProductRow('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item{*$APP.LBL_ADD_PRODUCT*}
				</button>
			</td>
	   	</tr>
	</table>

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="1">
	<input type="hidden" name="subtotal" id="subtotal" value="">
	<input type="hidden" name="total" id="total" value="">
	</td>
</tr>

{else}

	<tr>
	  <td colspan="6" align="left">

		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
		   	<tr>	   	
				<td colspan="9" class="dvInnerHeader">
				<b>{$APP.LBL_ITEM_DETAILS}</b>	  
				<input type="hidden" value="{$INV_CURRENCY_ID}" id="prev_selected_currency_id" />
				<input type="hidden" value="" id="inventory_currency" />
			  	<input type="hidden" value="" id="taxtype" />	  
			</tr>

		   <!-- Header for the Product Details -->
		   	<tr valign="top">
				<td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
				<td width=47% class="lvtCol"><font color='red'>* </font><b>ชื่อสินค้า</b></td>
				<td width=7% class="lvtCol"><strong>ขนาด</strong></td>
				<td width="7%" class="lvtCol"><b>สถานะของสินค้า</b></td>
				<td width="7%" class="lvtCol"><strong>ประเภทย่อยสินค้า</strong></td>
				<td width="7%" class="lvtCol"><strong>หน่วยการนับ</strong></td>
				<td width="10%" class="lvtCol"><strong>ราคาขาย</strong></td>
				<td width="10%" class="lvtCol"><strong>สกุลเงิน</strong></td>
		    </tr>
				
		   	{foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS name=outer1}
				{assign var="deleted" value="deleted"|cat:$row_no}
				{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
				{assign var="productName" value="productName"|cat:$row_no}
				{assign var="comment" value="comment"|cat:$row_no}
				{assign var="productDescription" value="productDescription"|cat:$row_no}
				{assign var="entityType" value=$data.$entityIdentifier}
				{assign var="listPrice" value="listPrice"|cat:$row_no}
				{assign var="productcategory" value="productcategory"|cat:$row_no}
				{assign var="productstatus" value="productstatus"|cat:$row_no}
				{assign var="unit" value="unit"|cat:$row_no}
				{assign var="productsize" value="productsize"|cat:$row_no}
				
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
								<input type="text" id="{$productName}" name="{$productName}" value="{$data.$productName}" class="small" style="width: 70%;" readonly />
								<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}" />
								<input type="hidden" id="lineItemType{$row_no}" name="lineItemType{$row_no}" value="{$entityType}" />
								&nbsp;
								{*if $entityType eq 'Services'*}
									<img id="searchIcon{$row_no}" title="Services" src="{'services.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="servicePickList(this,'{$MODULE}','{$row_no}')" />
								{*else*}
									<img id="searchIcon{$row_no}" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}','{$row_no}')" />
								{*/if*}
							</td>
						</tr>
						<tr>
							<td class="small" id="setComment">
								<textarea id="{$comment}" name="{$comment}" class=detailedViewTextBox user-success style="width:70%;height:40px">{$data.$comment}</textarea>
								<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onClick="{literal}${/literal}('{$comment}').value=''"; style="cursor:pointer;" />
							</td>
						</tr>
					</table>
				</td>

				<td class="crmTableRow small lineOnTop" valign="top">
					<span id="{$productsize}">{$data.$productsize}</span>
				</td>

				<td class="crmTableRow small lineOnTop" valign="top">
					<span id="{$productstatus}">{$data.$productstatus}</span>
				</td>

			    <td class="crmTableRow small lineOnTop">
			    	<span id="{$productcategory}">{$data.$productcategory}</span>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<span id="{$unit}">{$data.$unit}</span>
				</td>
				
				<td class="crmTableRow small lineOnTop">
					<input data-rowno="{$row_no}" id="{$listPrice}" name="{$listPrice}" type="text" class="detailedViewTextBox listPrice user-success listPrice" onfocus="this.className='detailedViewTextBoxOn listPrice';" onblur="this.className='detailedViewTextBox listPrice';"  value="{$data.$listPrice}"/>
				</td>
		   	</tr>
		   	<!-- Product Details First row - Ends -->
		   	{/foreach}
		</table>

		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
		   <!-- Add Product Button -->
		   	<tr>
				<td colspan="3">
					<!-- <input type="button" name="Button" class="crmbutton small create" value="{$APP.LBL_ADD_PRODUCT}" onclick="fnAddProductRow('{$MODULE}','{$IMAGE_PATH}');" /> -->
					<button title="Add Products" class="crmbutton small save" onclick="fnAddProductRow('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button">
					<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Products
					</button>
				</td>
		   	</tr>
		</table>

		<input type="hidden" name="totalProductCount" id="totalProductCount" value="{$row_no}">
		<input type="hidden" name="total" id="total" value="">
		</td>
	</tr>
{/if}