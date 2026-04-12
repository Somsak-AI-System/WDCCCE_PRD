<?php /* Smarty version 2.6.18, created on 2026-04-08 16:51:34
         compiled from Inventory/QuotesProductDetails.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedCurrencyString', 'Inventory/QuotesProductDetails.tpl', 132, false),array('modifier', 'aicrm_imageurl', 'Inventory/QuotesProductDetails.tpl', 193, false),)), $this); ?>

<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<script type="text/javascript">
	jQuery.noConflict();
</script>
<script type="text/javascript" src="include/js/Inventory_QuotesProductDetails.js?v=<?php echo time(); ?>
"></script>
<script type="text/javascript" src="include/js/Inventory_<?php echo $this->_tpl_vars['MODULE']; ?>
.js"></script>
<script>
if(!e)
	window.captureEvents(Event.MOUSEMOVE);

//  window.onmousemove= displayCoords;
//  window.onclick = fnRevert;

function displayCoords(currObj,obj,mode,curr_row)
{
	if(mode != 'discount_final' && mode != 'sh_tax_div_title' && mode != 'group_tax_div_title')
	{
		var curr_productid = document.getElementById("hdnProductId"+curr_row).value;
		if(curr_productid == '')
		{
			alert("<?php echo $this->_tpl_vars['APP']['PLEASE_SELECT_LINE_ITEM']; ?>
");
			return false;
		}

		var curr_quantity = document.getElementById("qty"+curr_row).value;
        var curr_test_box = document.getElementById("test_box"+curr_row).value;
		if(curr_quantity == '')
		{
			alert("<?php echo $this->_tpl_vars['APP']['PLEASE_FILL_QUANTITY']; ?>
");
			return false;
		}
	}

	//Set the Header value for Discount
	if(mode == 'discount')
	{
		document.getElementById("discount_div_title"+curr_row).innerHTML = '<b><?php echo $this->_tpl_vars['APP']['LABEL_SET_DISCOUNT_FOR_COLON']; ?>
 '+document.getElementById("productTotal"+curr_row).innerHTML+'</b>';
	}
	else if(mode == 'tax')
	{
		document.getElementById("tax_div_title"+curr_row).innerHTML = "<b><?php echo $this->_tpl_vars['APP']['LABEL_SET_TAX_FOR']; ?>
 "+document.getElementById("totalAfterDiscount"+curr_row).innerHTML+'</b>';
	}
	else if(mode == 'discount_final')
	{
		document.getElementById("discount_div_title_final").innerHTML = '<b><?php echo $this->_tpl_vars['APP']['LABEL_SET_DISCOUNT_FOR']; ?>
 '+document.getElementById("afterdis_final").innerHTML+'</b>';
	}
	else if(mode == 'sh_tax_div_title')
	{
		document.getElementById("sh_tax_div_title").innerHTML = '<b><?php echo $this->_tpl_vars['APP']['LABEL_SET_SH_TAX_FOR_COLON']; ?>
 '+document.getElementById("shipping_handling_charge").value+'</b>';
	}
	else if(mode == 'group_tax_div_title')
	{
		var total_after_bill_discount = eval(document.getElementById("total_after_bill_discount").innerHTML);
		document.getElementById("group_tax_div_title").innerHTML = '<b>Set Group Vat for : '+total_after_bill_discount+'</b>';
	}

	fnvshobj(currObj,'tax_container');
	if(document.all)
	{
		var divleft = document.getElementById("tax_container").style.left;
		var divabsleft = divleft.substring(0,divleft.length-2);
		document.getElementById(obj).style.left = eval(divabsleft) - 120;

		var divtop = document.getElementById("tax_container").style.top;
		var divabstop =  divtop.substring(0,divtop.length-2);
		document.getElementById(obj).style.top = eval(divabstop) - 200;
	}else
	{
		document.getElementById(obj).style.left =  document.getElementById("tax_container").left;
		document.getElementById(obj).style.top = document.getElementById("tax_container").top;
	}
	document.getElementById(obj).style.display = "block";

}

	function doNothing(){
	}

	function fnHidePopDiv(obj){
		document.getElementById(obj).style.display = 'none';
	}
</script>

<!-- Added this file to display and hanld the Product Details in Inventory module  -->

<tr>
	<td colspan="4" align="left">
	<div id="dialog" style="display:none;">Dialog Content.</div>

	<table width="100%"  border="0" align="center" cellpadding="12" cellspacing="0" class="crmTable" id="proTab">
	   	<tr>
	   	
			<td colspan="6" class="dvInnerHeader">
				<b><?php echo $this->_tpl_vars['APP']['LBL_ITEM_DETAILS']; ?>
</b>
			</td>

			<td class="dvInnerHeader" align="center" colspan="4">
				<b>Price type</b>&nbsp;&nbsp;
				<select class="small" id="pricetype" name="pricetype" onchange="calcTotal();">
					<option value="Exclude Vat">Exclude Vat</option>
					<option value="Include Vat">Include Vat</option>
				</select>
			</td>

			<td class="dvInnerHeader" align="center" colspan="2" style="display:none;">
				<input type="hidden" value="<?php echo $this->_tpl_vars['INV_CURRENCY_ID']; ?>
" id="prev_selected_currency_id" />
				<b><?php echo $this->_tpl_vars['APP']['LBL_CURRENCY']; ?>
</b>&nbsp;&nbsp;
						        			        					<select class="small" id="inventory_currency" name="inventory_currency"> <!--onchange="updatePrices();"-->
					<?php $_from = $this->_tpl_vars['CURRENCIES_LIST']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['currency_details']):
?>
						<?php if ($this->_tpl_vars['currency_details']['curid'] == $this->_tpl_vars['INV_CURRENCY_ID']): ?>
							<?php $this->assign('currency_selected', 'selected'); ?>
						<?php else: ?>
							<?php $this->assign('currency_selected', ""); ?>
						<?php endif; ?>
						<OPTION value="<?php echo $this->_tpl_vars['currency_details']['curid']; ?>
" <?php echo $this->_tpl_vars['currency_selected']; ?>
><?php echo getTranslatedCurrencyString($this->_tpl_vars['currency_details']['currencylabel']); ?>
 (<?php echo $this->_tpl_vars['currency_details']['currencysymbol']; ?>
)</OPTION>
					<?php endforeach; endif; unset($_from); ?>
					</select>
							</td>

			<td class="dvInnerHeader" align="center" colspan="2">
			
			
				<b><?php echo $this->_tpl_vars['APP']['LBL_TAX_MODE']; ?>
</b>&nbsp;
		        <?php if ($this->_tpl_vars['MODULE'] == 'Quotes'): ?>
		        	<input type="hidden" id="taxtype" name="taxtype" value="group"><b><?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>
</b>
		        <?php else: ?>
				<select id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
					<OPTION value="individual" ><?php echo $this->_tpl_vars['APP']['LBL_INDIVIDUAL']; ?>
</OPTION>
					<OPTION value="group" selected><?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>
</OPTION>
				</select>
		        <?php endif; ?>
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
		                <textarea id="productName1" name="productName1"  class="detailedViewTextBox user-success" style="width:90%;height:40px" ><?php echo $this->_tpl_vars['PRODUCT_NAME']; ?>
</textarea>
					    <input type="hidden" id="hdnProductId1" name="hdnProductId1" value="<?php echo $this->_tpl_vars['PRODUCT_ID']; ?>
" />
						<input type="hidden" id="lineItemType1" name="lineItemType1" value="Products" />
						<input type="hidden" id="productcode1" name="productcode1" value="" />

						<input type="hidden" id="hdn_cal_total1" name="hdn_cal_total1" value="0" />
						<input type="hidden" id="hdn_cal_discount1" name="hdn_cal_discount1" value="0" />
						<input type="hidden" id="hdn_cal_inline_total_price1" name="hdn_cal_inline_total_price1" value="0" />

						<img id="searchIcon1" title="Products" src="<?php echo aicrm_imageurl('products.gif', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'<?php echo $this->_tpl_vars['MODULE']; ?>
',1)" />
					</td>
				</tr>

					<tr>
						<td class="small">
							<select class="small product_price_type" id="product_price_type1" name="product_price_type1" style="margin-bottom:5px;">
								<OPTION value="" <?php echo $this->_tpl_vars['product_price_type_selected']; ?>
>-- Select Price list --</OPTION>
								<?php $_from = $this->_tpl_vars['a_product_price_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['product_price_type']):
?>
									<?php if ($this->_tpl_vars['product_price_type']['product_price_type_name'] == $this->_tpl_vars['product_price_type_name']): ?>
										<?php $this->assign('product_price_type_selected', 'selected'); ?>
									<?php else: ?>
										<?php $this->assign('product_price_type_selected', ""); ?>
									<?php endif; ?>
									<OPTION value="<?php echo $this->_tpl_vars['product_price_type']['product_price_type_name']; ?>
" <?php echo $this->_tpl_vars['product_price_type_selected']; ?>
><?php echo $this->_tpl_vars['product_price_type']['product_price_type_name']; ?>
</OPTION>
								<?php endforeach; endif; unset($_from); ?>
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
						<img src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" onClick="<?php echo '$'; ?>
('comment1').value=''"; style="cursor:pointer;" />
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
					<img src="<?php echo aicrm_imageurl('search.gif', $this->_tpl_vars['THEME']); ?>
" border="0" title="<?php echo $this->_tpl_vars['APP']['LBL_SMART_SEARCH']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SMART_SEARCH']; ?>
" style="cursor: pointer;"/>
				</a>
				<br>
				<br>
				<select class="small pricelist_type" id="pricelist_type1" name="pricelist_type1" style="margin-bottom:5px;" onchange="calcTotal();">
						<?php $_from = $this->_tpl_vars['a_pricelist_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['pricelist_type']):
?>
							<?php if ($this->_tpl_vars['pricelist_type']['pricelist_type_name'] == 'ราคาต่อตร.ม.'): ?>
								<?php $this->assign('pricelist_type_selected', 'selected'); ?>
							<?php else: ?>
								<?php $this->assign('pricelist_type_selected', ""); ?>
							<?php endif; ?>
							<OPTION value="<?php echo $this->_tpl_vars['pricelist_type']['pricelist_type_name']; ?>
" <?php echo $this->_tpl_vars['pricelist_type_selected']; ?>
><?php echo $this->_tpl_vars['pricelist_type']['pricelist_type_name']; ?>
</OPTION>
						<?php endforeach; endif; unset($_from); ?>
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
				<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');displayField();" type="button" name="button">
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
			<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b><?php echo $this->_tpl_vars['APP']['LBL_TOTAL_PRICE']; ?>
</b></td>
			<td width="12%" id="netTotal" class="crmTableRow small lineOnTop" align="right">0.00</td>
	   	</tr>

	   	<tr valign="top"  id="div_discount">
			<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small lineOnTop" align="right">
			(-)&nbsp;<b>
			<span><?php echo $this->_tpl_vars['APP']['LBL_DISCOUNT']; ?>
</span>
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
						<td align="right"><img src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onClick="fnHidePopDiv('discount_div_final')" style="cursor:pointer;"></td>
				   	</tr>
				   	<tr>
						<td align="left" class="lineOnTop">
							<input type="radio" name="discount_final" checked onclick="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp; <?php echo $this->_tpl_vars['APP']['LBL_ZERO_DISCOUNT']; ?>

						</td>
						<td class="lineOnTop">&nbsp;</td>
				   	</tr>
				   	<tr>
						<td align="left">
							<input type="radio" name="discount_final" onclick="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp; % <?php echo $this->_tpl_vars['APP']['LBL_OF_PRICE']; ?>

						</td>
						<td align="right">
							<input type="text" class="small" size="5" id="discount_percentage_final" name="discount_percentage_final" value="0" style="visibility:hidden" onBlur="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp;%
						</td>
				   	</tr>
				   	<tr>
						<td align="left" nowrap>
							<input type="radio" name="discount_final" onclick="setDiscount(this,'_final'); calcGroupTax();calcTotal();set_tax_manual();">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DIRECT_PRICE_REDUCTION']; ?>

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
								<td align="right"><img src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onClick="fnHidePopDiv('group_tax_div')" style="cursor:pointer;"></td>
						   	</tr>

						<?php $_from = $this->_tpl_vars['GROUP_TAXES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['group_tax_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['group_tax_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['loop_count'] => $this->_tpl_vars['tax_detail']):
        $this->_foreach['group_tax_loop']['iteration']++;
?>
							<input type="hidden" id="group_tax_percentage_df<?php echo $this->_foreach['group_tax_loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['tax_detail']['percentage']; ?>
"  />
						   	<tr>
								<td align="left" class="lineOnTop">
									<input type="text" class="small" size="5" name="<?php echo $this->_tpl_vars['tax_detail']['taxname']; ?>
_group_percentage" id="group_tax_percentage<?php echo $this->_foreach['group_tax_loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['tax_detail']['percentage']; ?>
" onBlur="calcTotal();set_tax_manual();">&nbsp;%
								</td>
								<td align="center" class="lineOnTop"><?php echo $this->_tpl_vars['tax_detail']['taxlabel']; ?>
</td>
								<td align="right" class="lineOnTop">
									<input type="text" class="small" size="6" name="<?php echo $this->_tpl_vars['tax_detail']['taxname']; ?>
_group_amount" id="group_tax_amount<?php echo $this->_foreach['group_tax_loop']['iteration']; ?>
" style="cursor:pointer;" value="0.00" readonly>
								</td>
						   	</tr>
						<?php endforeach; endif; unset($_from); ?>
							<input type="hidden" id="group_tax_count" value="<?php echo $this->_foreach['group_tax_loop']['iteration']; ?>
">
						</table>
					</div>
					<!-- End Popup Div Group Tax -->
			</td>
			<td  class="crmTableRow small lineOnTop" align="right">
				<input id="tax_final" class="small " name="tax_final" value="<?php echo $this->_tpl_vars['FINAL']['tax_totalamount']; ?>
" style="width:70px; text-align: right !important;" onblur="set_tax_manual()" type="text">
			</td>
	   	</tr>
	  	<!-- Group Tax - ends -->

	   	<tr valign="top"  style="visibility:hidden;display:none;height: 0;">
			<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small" align="right">
			(+)&nbsp;<b><?php echo $this->_tpl_vars['APP']['LBL_SHIPPING_AND_HANDLING_CHARGES']; ?>
 </b>
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
			(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,'shipping_handling_div','sh_tax_div_title',''); calcSHTax();" ><?php echo $this->_tpl_vars['APP']['LBL_TAX_FOR_SHIPPING_AND_HANDLING']; ?>
 </a></b>
					<!-- Pop Div For Shipping and Handlin TAX -->
					<div class="discountUI" id="shipping_handling_div">
						<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
						   	<tr>
								<td id="sh_tax_div_title" colspan="2" nowrap align="left" ></td>
								<td align="right"><img src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onClick="fnHidePopDiv('shipping_handling_div')" style="cursor:pointer;"></td>
						   	</tr>

						<?php $_from = $this->_tpl_vars['SH_TAXES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sh_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sh_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['loop_count'] => $this->_tpl_vars['tax_detail']):
        $this->_foreach['sh_loop']['iteration']++;
?>

						   	<tr>
								<td align="left" class="lineOnTop">
									<input type="text" class="small" size="3" name="<?php echo $this->_tpl_vars['tax_detail']['taxname']; ?>
_sh_percent" id="sh_tax_percentage<?php echo $this->_foreach['sh_loop']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['tax_detail']['percentage']; ?>
" onBlur="calcSHTax()">&nbsp;%
								</td>
								<td align="center" class="lineOnTop"><?php echo $this->_tpl_vars['tax_detail']['taxlabel']; ?>
</td>
								<td align="right" class="lineOnTop">
									<input type="text" class="small" size="4" name="<?php echo $this->_tpl_vars['tax_detail']['taxname']; ?>
_sh_amount" id="sh_tax_amount<?php echo $this->_foreach['sh_loop']['iteration']; ?>
" style="cursor:pointer;" value="0.00" readonly>
								</td>
						   	</tr>

						<?php endforeach; endif; unset($_from); ?>
							<input type="hidden" id="sh_tax_count" value="<?php echo $this->_foreach['sh_loop']['iteration']; ?>
">
						</table>
					</div>
					<!-- End Popup Div for Shipping and Handling TAX -->
			</td>
			<td id="shipping_handling_tax" class="crmTableRow small" align="right">0.00</td>
	   	</tr>
	   	
	    <tr valign="top" style="visibility:hidden;display:none;height: 0;">
			<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
			<td class="crmTableRow small" align="right">
				<?php echo $this->_tpl_vars['APP']['LBL_ADJUSTMENT']; ?>

				<span><input type="radio" name="adjustmentType" id="adjustmentType" option="" value="+" checked="">&nbsp;Add&nbsp;&nbsp;</span>
				<span><input type="radio" name="adjustmentType" id="adjustmentType" option="" value="-">&nbsp;Deduct</span></div>
				<!-- <select id="adjustmentType" name="adjustmentType" class=small onchange="calcTotal();">
					<option value="+"><?php echo $this->_tpl_vars['APP']['LBL_ADD_ITEM']; ?>
</option>
					<option value="-"><?php echo $this->_tpl_vars['APP']['LBL_DEDUCT']; ?>
</option>
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
			<td class="crmTableRow big lineOnTop" align="right"><b><?php echo $this->_tpl_vars['APP']['LBL_GRAND_TOTAL']; ?>
</b></td>
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
{
	<?php $_from = $this->_tpl_vars['HIDDEN_FIELDS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['field']):
?>
		<?php if ($this->_tpl_vars['field']['visible'] == 1): ?>
			const <?php echo $this->_tpl_vars['field']['class']; ?>
 = document.getElementsByClassName("<?php echo $this->_tpl_vars['field']['class']; ?>
");
			//console.log("Found elements: ", elements); 
			
			for (let i = 0; i < <?php echo $this->_tpl_vars['field']['class']; ?>
.length; i++) {
				<?php echo $this->_tpl_vars['field']['class']; ?>
[i].style.display = 'none';
				//<?php echo $this->_tpl_vars['field']['class']; ?>
[i].innerHTML = '';
				//<?php echo $this->_tpl_vars['field']['class']; ?>
[i].insertAdjacentHTML('afterend', '<center class="afterend">-</center>');
			}
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
}
</script>
