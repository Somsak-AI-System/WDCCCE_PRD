<?php /* Smarty version 2.6.18, created on 2026-04-08 16:50:52
         compiled from Inventory/QuotesProductDetailsEditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedCurrencyString', 'Inventory/QuotesProductDetailsEditView.tpl', 146, false),array('modifier', 'cat', 'Inventory/QuotesProductDetailsEditView.tpl', 190, false),array('modifier', 'aicrm_imageurl', 'Inventory/QuotesProductDetailsEditView.tpl', 270, false),)), $this); ?>
<!-- <script type="text/javascript" src="include/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script> -->
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
	jQuery(document).ready(function(){
		jQuery('form').on('keyup keypress', function(e){
            var keyCode = e.keyCode || e.which;
			if(keyCode == 13 && !jQuery(e.target).is("textarea")){
                e.preventDefault();
                console.log("ENTER PREVENTED");
                return;
			}
		});
	});
</script>
<!-- Added to display the Product Details -->
<script type="text/javascript">
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
		document.getElementById("discount_div_title"+curr_row).innerHTML = '<b><?php echo $this->_tpl_vars['APP']['LABEL_SET_DISCOUNT_FOR_X_COLON']; ?>
 '+document.getElementById("productTotal"+curr_row).innerHTML+'</b>';
	}
	else if(mode == 'tax')
	{
		document.getElementById("tax_div_title"+curr_row).innerHTML = "<b><?php echo $this->_tpl_vars['APP']['LABEL_SET_TAX_FOR']; ?>
 "+document.getElementById("totalAfterDiscount"+curr_row).innerHTML+'</b>';
	}
	else if(mode == 'discount_final')
	{
		document.getElementById("discount_div_title_final").innerHTML = '<b><?php echo $this->_tpl_vars['APP']['LABEL_SET_DISCOUNT_FOR_COLON']; ?>
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
		document.getElementById(obj).style.top = eval(divabstop);
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


<tr><td colspan="4" align="left">
<div id="dialog" style="display:none;">Dialog Content.</div>
<table width="100%"  border="0" align="center" cellpadding="12" cellspacing="0" class="crmTable" id="proTab">
   <tr>
   	<td colspan="6" class="dvInnerHeader">
		<b><?php echo $this->_tpl_vars['APP']['LBL_ITEM_DETAILS']; ?>
</b>
	</td>
	<td class="dvInnerHeader" align="center" colspan="4">
		<b>Price type</b>&nbsp;&nbsp;
		<?php if ($this->_tpl_vars['pricetype'] == 'Exclude Vat'): ?>
			<?php $this->assign('pricetype_exclud', 'selected'); ?>
		<?php elseif ($this->_tpl_vars['pricetype'] == 'Include Vat'): ?>
			<?php $this->assign('pricetype_include', 'selected'); ?>
		<?php endif; ?>
		<select class="small" id="pricetype" name="pricetype" onchange="calcTotal();">
			<option value="Exclude Vat" <?php echo $this->_tpl_vars['pricetype_exclud']; ?>
>Exclude Vat</option>
			<option value="Include Vat" <?php echo $this->_tpl_vars['pricetype_include']; ?>
 >Include Vat</option>
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
</b>&nbsp;&nbsp;

		<?php if ($this->_tpl_vars['ASSOCIATEDPRODUCTS']['1']['final_details']['taxtype'] == 'group'): ?>
			<?php $this->assign('group_selected', 'selected'); ?>
		<?php else: ?>
			<?php $this->assign('individual_selected', 'selected'); ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['MODULE'] == 'Quotes'): ?>
        	<input type="hidden" id="taxtype" name="taxtype" value="group"><b><?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>
</b>
        <?php else: ?>
		<select class="small" id="taxtype" name="taxtype" onchange="decideTaxDiv(); calcTotal();">
			<OPTION value="individual" <?php echo $this->_tpl_vars['individual_selected']; ?>
><?php echo $this->_tpl_vars['APP']['LBL_INDIVIDUAL']; ?>
</OPTION>
			<OPTION value="group" <?php echo $this->_tpl_vars['group_selected']; ?>
><?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>
</OPTION>
		</select>
        <?php endif; ?>
	</td>
	<td class="dvInnerHeader" align="center" style ="display:none">&nbsp</td>
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
		<td width=5% class="lvtCol" align="center"><b>ราคาปกติ</b></td>
		<td width=5% class="lvtCol" align="center"><b>ราคาขาย</b></td>
		<td width=5% class="lvtCol" align="center"><b>ส่วนลด</b></td>
		<td width=7% nowrap class="lvtCol" align="center"><b>ราคารวม</b></td>
	</tr>

	
   <?php $_from = $this->_tpl_vars['ASSOCIATEDPRODUCTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['row_no'] => $this->_tpl_vars['data']):
        $this->_foreach['outer1']['iteration']++;
?>
	<?php $this->assign('deleted', ((is_array($_tmp='deleted')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('hdnProductId', ((is_array($_tmp='hdnProductId')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('hdn_cal_total', ((is_array($_tmp='hdn_cal_total')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('hdn_cal_discount', ((is_array($_tmp='hdn_cal_discount')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('productName', ((is_array($_tmp='productName')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('comment', ((is_array($_tmp='comment')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>

	<?php $this->assign('pricelist_type', ((is_array($_tmp='pricelist_type')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('pricelist_type_name', $this->_tpl_vars['data'][$this->_tpl_vars['pricelist_type']]); ?>

    <?php $this->assign('product_price_type', ((is_array($_tmp='product_price_type')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('product_price_type_name', $this->_tpl_vars['data'][$this->_tpl_vars['product_price_type']]); ?>

	<?php $this->assign('package_size_sheet_per_box', ((is_array($_tmp='package_size_sheet_per_box')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('package_size_sqm_per_box', ((is_array($_tmp='package_size_sqm_per_box')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('box_quantity', ((is_array($_tmp='box_quantity')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('sales_unit', ((is_array($_tmp='sales_unit')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('sheet_quantity', ((is_array($_tmp='sheet_quantity')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('sqm_quantity', ((is_array($_tmp='sqm_quantity')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('regular_price', ((is_array($_tmp='regular_price')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('product_discount', ((is_array($_tmp='product_discount')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>

	<?php $this->assign('competitor_brand', ((is_array($_tmp='competitor_brand')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('competitor_brand_name', $this->_tpl_vars['data'][$this->_tpl_vars['competitor_brand']]); ?>
	<?php $this->assign('competitor_price', ((is_array($_tmp='competitor_price')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>

	<?php $this->assign('compet_brand_in_proj', ((is_array($_tmp='compet_brand_in_proj')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('compet_brand_in_proj_name', $this->_tpl_vars['data'][$this->_tpl_vars['compet_brand_in_proj']]); ?>
	<?php $this->assign('compet_brand_in_proj_price', ((is_array($_tmp='compet_brand_in_proj_price')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>

	<?php $this->assign('product_unit', ((is_array($_tmp='product_unit')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('product_cost_avg', ((is_array($_tmp='product_cost_avg')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('selling_price', ((is_array($_tmp='selling_price')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>


	<?php $this->assign('productDescription', ((is_array($_tmp='productDescription')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('qtyInStock', ((is_array($_tmp='qtyInStock')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('test_box', ((is_array($_tmp='test_box')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('qty', ((is_array($_tmp='qty')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('listPrice', ((is_array($_tmp='listPrice')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('listprice_exc', ((is_array($_tmp='listprice_exc')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('listprice_inc', ((is_array($_tmp='listprice_inc')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('productTotal', ((is_array($_tmp='productTotal')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('business_code', ((is_array($_tmp='business_code')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('subproduct_ids', ((is_array($_tmp='subproduct_ids')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('subprod_names', ((is_array($_tmp='subprod_names')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('entityIdentifier', ((is_array($_tmp='entityType')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('entityType', $this->_tpl_vars['data'][$this->_tpl_vars['entityIdentifier']]); ?>

	<?php $this->assign('discount_type', ((is_array($_tmp='discount_type')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('discount_percent', ((is_array($_tmp='discount_percent')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('checked_discount_percent', ((is_array($_tmp='checked_discount_percent')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('style_discount_percent', ((is_array($_tmp='style_discount_percent')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('discount_amount', ((is_array($_tmp='discount_amount')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('checked_discount_amount', ((is_array($_tmp='checked_discount_amount')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('style_discount_amount', ((is_array($_tmp='style_discount_amount')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('checked_discount_zero', ((is_array($_tmp='checked_discount_zero')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>

	<?php $this->assign('discountTotal', ((is_array($_tmp='discountTotal')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('totalAfterDiscount', ((is_array($_tmp='totalAfterDiscount')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('taxTotal', ((is_array($_tmp='taxTotal')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('netPrice', ((is_array($_tmp='netPrice')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>

    <?php $this->assign('pack_size', ((is_array($_tmp='pack_size')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php $this->assign('uom', ((is_array($_tmp='uom')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
    <?php $this->assign('price_list_std', ((is_array($_tmp='price_list_std')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
    <?php $this->assign('price_list_inv', ((is_array($_tmp='price_list_inv')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
    <?php $this->assign('productcode', ((is_array($_tmp='productcode')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>

    
    
    
    
   

   <tr id="row<?php echo $this->_tpl_vars['row_no']; ?>
" valign="top">

	<!-- column 1 - delete link - starts -->
	<td  class="crmTableRow small lineOnTop" align="center">
		<?php if ($this->_tpl_vars['row_no'] != 1): ?>
			<img src="<?php echo aicrm_imageurl('delete.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick="deleteRow('<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['row_no']; ?>
,'<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
')">
		<?php endif; ?><br/><br/>
		<?php if ($this->_tpl_vars['row_no'] != 1): ?>
			&nbsp;<a href="javascript:moveUpDown('UP','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['row_no']; ?>
)" title="Move Upward"><img src="<?php echo aicrm_imageurl('up_layout.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></a>
		<?php endif; ?>
		<?php if (! ($this->_foreach['outer1']['iteration'] == $this->_foreach['outer1']['total'])): ?>
			&nbsp;<a href="javascript:moveUpDown('DOWN','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['row_no']; ?>
)" title="Move Downward"><img src="<?php echo aicrm_imageurl('down_layout.gif', $this->_tpl_vars['THEME']); ?>
" border="0" ></a>
		<?php endif; ?>
		<input type="hidden" id="<?php echo $this->_tpl_vars['deleted']; ?>
" name="<?php echo $this->_tpl_vars['deleted']; ?>
" value="0">
	</td>

	<!-- column 2 - Product Name - starts -->
	<td class="crmTableRow small lineOnTop">
		<!-- Product Re-Ordering Feature Code Addition Starts -->
		<input type="hidden" name="hidtax_row_no<?php echo $this->_tpl_vars['row_no']; ?>
" id="hidtax_row_no<?php echo $this->_tpl_vars['row_no']; ?>
" value="<?php echo $this->_tpl_vars['tax_row_no']; ?>
"/>
		<!-- Product Re-Ordering Feature Code Addition ends -->
		<table width="100%"  border="0" cellspacing="0" cellpadding="1">
			<tr>
				<td class="small" valign="top">
					<span id="<?php echo $this->_tpl_vars['business_code']; ?>
" name="<?php echo $this->_tpl_vars['business_code']; ?>
" style="color:#C0C0C0;font-style:italic;"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['business_code']]; ?>
</span>
					<div id="productCodeView<?php echo $this->_tpl_vars['row_no']; ?>
" style="font-weight:bold;"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['productcode']]; ?>
</div>
					<textarea id="<?php echo $this->_tpl_vars['productName']; ?>
" name="<?php echo $this->_tpl_vars['productName']; ?>
" class="detailedViewTextBox user-success" style="width:90%;height:40px"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['productName']]; ?>
</textarea>
					<input type="hidden" id="<?php echo $this->_tpl_vars['hdnProductId']; ?>
" name="<?php echo $this->_tpl_vars['hdnProductId']; ?>
" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['hdnProductId']]; ?>
" />
					<input type="hidden" id="lineItemType<?php echo $this->_tpl_vars['row_no']; ?>
" name="lineItemType<?php echo $this->_tpl_vars['row_no']; ?>
" value="<?php echo $this->_tpl_vars['entityType']; ?>
" />
					<input type="hidden" id="productcode<?php echo $this->_tpl_vars['row_no']; ?>
" name="productcode<?php echo $this->_tpl_vars['row_no']; ?>
" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['productcode']]; ?>
" />

					<input type="hidden" id="hdn_cal_total<?php echo $this->_tpl_vars['row_no']; ?>
" name="hdn_cal_total<?php echo $this->_tpl_vars['row_no']; ?>
" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['hdn_cal_total']]; ?>
" />
					<input type="hidden" id="hdn_cal_discount<?php echo $this->_tpl_vars['row_no']; ?>
" name="hdn_cal_discount<?php echo $this->_tpl_vars['row_no']; ?>
" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['hdn_cal_discount']]; ?>
" />
					<input type="hidden" id="hdn_cal_inline_total_price<?php echo $this->_tpl_vars['row_no']; ?>
" name="hdn_cal_inline_total_price<?php echo $this->_tpl_vars['row_no']; ?>
" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['hdn_cal_inline_total_price']]; ?>
" />

						<img id="searchIcon<?php echo $this->_tpl_vars['row_no']; ?>
" title="Products" src="<?php echo aicrm_imageurl('products.gif', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['row_no']; ?>
')" />
						<!-- <img id="searchIcon<?php echo $this->_tpl_vars['row_no']; ?>
" title="Service" src="<?php echo aicrm_imageurl('services.gif', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;" align="absmiddle" onclick="servicePickList(this,'Service','<?php echo $this->_tpl_vars['row_no']; ?>
')" />
						<img id="searchIcon<?php echo $this->_tpl_vars['row_no']; ?>
" title="Sparepart" src="<?php echo aicrm_imageurl('services.gif', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;" align="absmiddle" onclick="sparepartPickList(this,'Sparepart','<?php echo $this->_tpl_vars['row_no']; ?>
')" /> -->
				</td>
			</tr>
			<tr>
				<td class="small">
					<select data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" class="small" id="<?php echo $this->_tpl_vars['product_price_type']; ?>
" name="<?php echo $this->_tpl_vars['product_price_type']; ?>
"  style="margin-bottom:5px;">
					    <OPTION value="" <?php echo $this->_tpl_vars['product_price_type_selected']; ?>
>-- Select Price list --</OPTION>
						<?php $_from = $this->_tpl_vars['a_product_price_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['product_type']):
?>
							<?php if ($this->_tpl_vars['product_type']['product_price_type_name'] == $this->_tpl_vars['product_price_type_name']): ?>
								<?php $this->assign('product_price_type_selected', 'selected'); ?>
							<?php else: ?>
								<?php $this->assign('product_price_type_selected', ""); ?>
							<?php endif; ?>
							<OPTION value="<?php echo $this->_tpl_vars['product_type']['product_price_type_name']; ?>
" <?php echo $this->_tpl_vars['product_price_type_selected']; ?>
><?php echo $this->_tpl_vars['product_type']['product_price_type_name']; ?>
</OPTION>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="small">
					<input type="hidden" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['subproduct_ids']]; ?>
" id="<?php echo $this->_tpl_vars['subproduct_ids']; ?>
" name="<?php echo $this->_tpl_vars['subproduct_ids']; ?>
" />
					<span id="<?php echo $this->_tpl_vars['subprod_names']; ?>
" name="<?php echo $this->_tpl_vars['subprod_names']; ?>
"  style="color:#C0C0C0;font-style:italic;"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['subprod_names']]; ?>
</span>
				</td>
			</tr>
			<tr>
				<td class="small" id="setComment">
					<textarea id="<?php echo $this->_tpl_vars['comment']; ?>
" name="<?php echo $this->_tpl_vars['comment']; ?>
" class=detailedViewTextBox user-success style="width:90%;height:40px"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['comment']]; ?>
</textarea>
					<img src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" onClick="<?php echo '$'; ?>
('<?php echo $this->_tpl_vars['comment']; ?>
').value=''"; style="cursor:pointer;" />
				</td>
			</tr>
		</table>
	</td>
	<!-- column 2 - Product Name - ends -->

	<?php if ($this->_tpl_vars['MODULE'] != 'Quotes'): ?>
    <td class="crmTableRow small lineOnTop" valign="top">
    	<span id="<?php echo $this->_tpl_vars['qtyInStock']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['qtyInStock']]; ?>
</span>
    </td>
    <?php endif; ?>

			<td class="crmTableRow small lineOnTop" align="center">
		   		<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['package_size_sheet_per_box']; ?>
" name="<?php echo $this->_tpl_vars['package_size_sheet_per_box']; ?>
" type="text" class="small detailedViewTextBox package_size_sheet_per_box" onfocus="this.className='package_size_sheet_per_box detailedViewTextBoxOn ';" onblur="this.className='package_size_sheet_per_box detailedViewTextBox ';"  value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['package_size_sheet_per_box']]; ?>
" readonly="readonly" style="background-color: #CCC;" />
		   	</td>
		   	<td class="crmTableRow small lineOnTop" align="center">
		   		<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['package_size_sqm_per_box']; ?>
" name="<?php echo $this->_tpl_vars['package_size_sqm_per_box']; ?>
" type="text" class="small detailedViewTextBox package_size_sqm_per_box" onfocus="this.className='package_size_sqm_per_box detailedViewTextBoxOn ';" onblur="this.className='package_size_sqm_per_box detailedViewTextBox ';"  value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['package_size_sqm_per_box']]; ?>
" readonly="readonly" style="background-color: #CCC;"/>
		   	</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['box_quantity']; ?>
" name="<?php echo $this->_tpl_vars['box_quantity']; ?>
" type="text" class="detailedViewTextBox small box_quantity" onfocus="this.className='box_quantity detailedViewTextBoxOn'" onBlur="this.className='selling_price detailedViewTextBox';calSheetQuantity(this,'<?php echo $this->_tpl_vars['row_no']; ?>
'); calSqmQuantity(this,'<?php echo $this->_tpl_vars['row_no']; ?>
');setDiscount(this,'<?php echo $this->_tpl_vars['row_no']; ?>
'); callTaxCalc('<?php echo $this->_tpl_vars['row_no']; ?>
');calcTotal();" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['box_quantity']]; ?>
"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['sales_unit']; ?>
" name="<?php echo $this->_tpl_vars['sales_unit']; ?>
" type="text" class="detailedViewTextBox small sales_unit" onfocus="this.className='sales_unit detailedViewTextBoxOn'" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['sales_unit']]; ?>
" readonly="readonly" style="background-color: #CCC;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['sheet_quantity']; ?>
" name="<?php echo $this->_tpl_vars['sheet_quantity']; ?>
" type="text" class="detailedViewTextBox small sheet_quantity" onfocus="this.className='sheet_quantity detailedViewTextBoxOn'" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['sheet_quantity']]; ?>
" readonly="readonly" style="background-color: #CCC;"/>
			</td>
			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['sqm_quantity']; ?>
" name="<?php echo $this->_tpl_vars['sqm_quantity']; ?>
" type="text" class="detailedViewTextBox small sqm_quantity" onfocus="this.className='sqm_quantity detailedViewTextBoxOn'" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['sqm_quantity']]; ?>
" readonly="readonly" style="background-color: #CCC;"/>
			</td>

			<td class="crmTableRow small lineOnTop" style="white-space: nowrap;" align="center">
				<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['regular_price']; ?>
" name="<?php echo $this->_tpl_vars['regular_price']; ?>
" type="text" class="detailedViewTextBox small regular_price" 
					onfocus="this.className='regular_price detailedViewTextBoxOn'" onBlur="this.className='regular_price detailedViewTextBox';setDiscount(this,'<?php echo $this->_tpl_vars['row_no']; ?>
'); callTaxCalc(<?php echo $this->_tpl_vars['row_no']; ?>
);calcTotal();" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['regular_price']]; ?>
" style="display: inline-block; vertical-align: middle; width:80px;"/>

				<a href="javascript:void(0);" onclick="popupPricelist(this,'<?php echo $this->_tpl_vars['row_no']; ?>
');" style="display: inline-block; vertical-align: middle; padding-left: 10px;">
					<img src="<?php echo aicrm_imageurl('search.gif', $this->_tpl_vars['THEME']); ?>
" border="0" title="<?php echo $this->_tpl_vars['APP']['LBL_SMART_SEARCH']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SMART_SEARCH']; ?>
" style="cursor: pointer;"/>
				</a>

				<br>
				<br>

				<select data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" class="small" id="<?php echo $this->_tpl_vars['pricelist_type']; ?>
" name="<?php echo $this->_tpl_vars['pricelist_type']; ?>
"  style="margin-bottom:5px;" onchange="calcTotal();">
					<?php $_from = $this->_tpl_vars['a_pricelist_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['product_type']):
?>
						<?php if ($this->_tpl_vars['product_type']['pricelist_type_name'] == $this->_tpl_vars['pricelist_type_name']): ?>
							<?php $this->assign('pricelist_type_selected', 'selected'); ?>
						<?php else: ?>
							<?php $this->assign('pricelist_type_selected', ""); ?>
						<?php endif; ?>
						<OPTION value="<?php echo $this->_tpl_vars['product_type']['pricelist_type_name']; ?>
" <?php echo $this->_tpl_vars['pricelist_type_selected']; ?>
><?php echo $this->_tpl_vars['product_type']['pricelist_type_name']; ?>
</OPTION>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['selling_price']; ?>
" name="<?php echo $this->_tpl_vars['selling_price']; ?>
" type="text" class="small selling_price " style="width:70px"  onfocus="this.className='selling_price detailedViewTextBoxOn'" onBlur="this.className='selling_price detailedViewTextBox';setDiscount(this,'<?php echo $this->_tpl_vars['row_no']; ?>
'); callTaxCalc(<?php echo $this->_tpl_vars['row_no']; ?>
);calcTotal();" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['selling_price']]; ?>
"/>
			</td>

			<td class="crmTableRow small lineOnTop" align="center">
				<input data-rowno="<?php echo $this->_tpl_vars['row_no']; ?>
" id="<?php echo $this->_tpl_vars['product_discount']; ?>
" name="<?php echo $this->_tpl_vars['product_discount']; ?>
" type="text" class="small product_discount" style="width:70px;background-color: #CCC;"  onfocus="this.className='product_discount detailedViewTextBoxOn'" onBlur="this.className='product_discount  detailedViewTextBox'; setDiscount(this,'<?php echo $this->_tpl_vars['row_no']; ?>
'); callTaxCalc(<?php echo $this->_tpl_vars['row_no']; ?>
);calcTotal();" value="<?php echo $this->_tpl_vars['data'][$this->_tpl_vars['product_discount']]; ?>
" readonly="readonly" />
			</td>
			
			<td class="crmTableRow small lineOnTop" align="right"><span id="netPrice<?php echo $this->_tpl_vars['row_no']; ?>
"><b>&nbsp;</b></span></td>
			
			<td class="crmTableRow small lineOnTop" align="right" colspan="2" style ="display:none">
		    	<span id = "productTotal<?php echo $this->_tpl_vars['row_no']; ?>
" style="visibility:hidden;" ></span>
		        <span id = "discountTotal<?php echo $this->_tpl_vars['row_no']; ?>
" style="visibility:hidden" ></span>
		        <span id = "taxTotal<?php echo $this->_tpl_vars['row_no']; ?>
" style="visibility:hidden" ></span>
		        <span id = "totalAfterDiscount<?php echo $this->_tpl_vars['row_no']; ?>
" ></span>
			</td>


   	</tr>
   <!-- Product Details First row - Ends -->
   <?php endforeach; endif; unset($_from); ?>
</table>



<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
   <!-- Add Product Button -->
   <tr>
	<td colspan="3">
			<!-- <input type="button" name="Button" class="crmbutton small create" value="Add Products" onclick="fnAddProductRowInventory('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');" /> -->
			<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');displayField();" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Item
			</button>

			<!-- <button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('Sparepart','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Sparepart
			</button>

			<button title="Add New Item" class="crmbutton small save" onclick="fnAddProductRowInventory('Service','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');" type="button" name="button">
				<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add Service
			</button> -->
            <!--
			<input type="button" name="Button" class="crmbutton small create" value="<?php echo $this->_tpl_vars['APP']['LBL_ADD_SERVICE']; ?>
" onclick="fnAddServiceRow('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');" />-->
	</td>
   </tr>




<!--
All these details are stored in the first element in the array with the index name as final_details
so we will get that array, parse that array and fill the details
-->
<?php $this->assign('FINAL', $this->_tpl_vars['ASSOCIATEDPRODUCTS']['1']['final_details']); ?>
   
   <tr valign="top" style ="display:none">
		<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>Total Qty.</b></td>
		<td width="12%" id="TotalQty" class="crmTableRow small lineOnTop" align="right">0.00</td>
	</tr>

   <!-- Product Details Final Total Discount, Tax and Shipping&Hanling  - Starts -->
   <tr valign="top">
	<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>TOTAL PRICE</b></td>
	<td width="12%" id="netTotal" class="crmTableRow small lineOnTop" align="right">0.00</td>
   </tr>

   <tr valign="top" id="div_discount">
	<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small lineOnTop" align="right">
		(-)&nbsp;<b>
		<span><?php echo $this->_tpl_vars['APP']['LBL_DISCOUNT']; ?>
</span>
	</td>
	<td class="crmTableRow small lineOnTop" align="right">
		<input class="small" type="text" id="discountTotal_final" onBlur="calcTotal();" name="discountTotal_final" size="5" value="<?php echo $this->_tpl_vars['FINAL']['discountTotal_final']; ?>
" style="width:70px; text-align: right !important; background-color:#EDEDED" readonly>
	</td>
   </tr>


   <!-- Group Tax - starts -->
   <tr valign="top" id="div_totalafterdiscount" >
     <td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
     <td class="crmTableRow small lineOnTop" align="right">Total After Discount</td>
     <td id="afterdis_final" class="crmTableRow small lineOnTop" align="right"><?php echo $this->_tpl_vars['FINAL']['afterdis_final']; ?>
</td>
   </tr>

   <tr valign="top" id="div_discount">
	<td class="crmTableRow small lineOnTop" width="60%" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small lineOnTop" align="right">
		<b><a href="javascript:doNothing();" onClick="displayCoords(this,'discount_div_final','discount_final','1');calcTotal();calcGroupTax();set_tax_manual();">ส่วนลดท้ายบิล</a>

		<!-- Popup Discount DIV -->
		<div class="discountUI" id="discount_div_final">
			<input type="hidden" id="discount_type_final" name="discount_type_final" value="<?php echo $this->_tpl_vars['FINAL']['discount_type_final']; ?>
">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
			   <tr>
				<td id="discount_div_title_final" nowrap align="left" ></td>
				<td align="right"><img src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onClick="fnHidePopDiv('discount_div_final')" style="cursor:pointer;"></td>
			   </tr>
			   <tr>
				<td align="left" class="lineOnTop"><input type="radio" name="discount_final" checked onclick="setDiscount(this,'_final'); calcGroupTax(); calcTotal();set_tax_manual();">&nbsp; <?php echo $this->_tpl_vars['APP']['LBL_ZERO_DISCOUNT']; ?>
</td>
				<td class="lineOnTop">&nbsp;</td>
			   </tr>
			   <tr>
				<td align="left"><input type="radio" name="discount_final" onclick="setDiscount(this,'_final');  calcTotal(); calcGroupTax();" <?php echo $this->_tpl_vars['FINAL']['checked_discount_percentage_final']; ?>
>&nbsp; % <?php echo $this->_tpl_vars['APP']['LBL_OF_PRICE']; ?>
</td>
				<td align="right"><input type="text" class="small" size="5" id="discount_percentage_final" name="discount_percentage_final" value="<?php echo $this->_tpl_vars['FINAL']['discount_percentage_final']; ?>
" <?php echo $this->_tpl_vars['FINAL']['style_discount_percentage_final']; ?>
 onBlur="setDiscount(this,'_final'); calcGroupTax(); calcTotal();set_tax_manual();">&nbsp;%</td>
			   </tr>
			   <tr>
				<td align="left" nowrap><input type="radio" name="discount_final" onclick="setDiscount(this,'_final');  calcTotal(); calcGroupTax();" <?php echo $this->_tpl_vars['FINAL']['checked_discount_amount_final']; ?>
>&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DIRECT_PRICE_REDUCTION']; ?>
</td>
				<td align="right"><input type="text" id="discount_amount_final" name="discount_amount_final" size="5" value="<?php echo $this->_tpl_vars['FINAL']['discount_amount_final']; ?>
" <?php echo $this->_tpl_vars['FINAL']['style_discount_amount_final']; ?>
 onBlur="setDiscount(this,'_final');  calcGroupTax(); calcTotal();set_tax_manual();" onkeyup="setDiscount(this,'_final');  calcGroupTax(); calcTotal();"></td>
			   </tr>
			</table>
		</div>
		<!-- End Div -->

	</td>
	<td id="bill_discount" class="crmTableRow small lineOnTop" align="right"><?php echo $this->_tpl_vars['FINAL']['bill_discount']; ?>
</td>
   </tr>

   <tr valign="top" id="div_total_after_bill_discount" >
     <td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
     <td class="crmTableRow small lineOnTop" align="right">Total หลังลดท้ายบิล</td>
     <td id="total_after_bill_discount" class="crmTableRow small lineOnTop" align="right"><?php echo $this->_tpl_vars['FINAL']['total_after_bill_discount']; ?>
</td>
   </tr>

   <tr id="group_tax_row" valign="top" class="TaxShow">
	<td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small lineOnTop" align="right">
		(+)&nbsp;<b><!--<a href="javascript:doNothing();" onClick="displayCoords(this,'group_tax_div','group_tax_div_title','');  calcTotal(); set_tax_manual(); calcGroupTax();" >-->Vat<!--</a>--></b>

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
" onBlur="calcTotal(); set_tax_manual();">&nbsp;%
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
" readonly style="width:70px; background-color:#EDEDED" onblur="set_tax_manual()" type="text">
	</td>
   </tr>
   <!-- Group Tax - ends -->



   <tr valign="top" style="visibility:hidden;display:none;height: 0;">
	<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small" align="right">
		(+)&nbsp;<b><?php echo $this->_tpl_vars['APP']['LBL_SHIPPING_AND_HANDLING_CHARGES']; ?>
 </b>
	</td>
	<td class="crmTableRow small" align="right">
		<input id="shipping_handling_charge" name="shipping_handling_charge" type="text" class="small" style="width:40px" align="right" value="<?php echo $this->_tpl_vars['FINAL']['shipping_handling_charge']; ?>
" onBlur="calcSHTax();">
	</td>
   </tr>

    <tr valign="top" id="div_total_without_vat" >
     <td class="crmTableRow small lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
     <td class="crmTableRow small lineOnTop" align="right">Total Net Amount including VAT</td>
     <td id="total_after_vat" class="crmTableRow small lineOnTop" align="right"><?php echo $this->_tpl_vars['FINAL']['total_without_vat']; ?>
</td>
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

					<?php $_from = $this->_tpl_vars['FINAL']['sh_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sh_loop'] = array('total' => count($_from), 'iteration' => 0);
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
	<td id="shipping_handling_tax" class="crmTableRow small" align="right"><?php echo $this->_tpl_vars['FINAL']['shtax_totalamount']; ?>
</td>
   </tr>


   <tr valign="top"  style="visibility:hidden;display:none;height: 0;">
	<td class="crmTableRow small" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow small" align="right">
		<?php echo $this->_tpl_vars['APP']['LBL_ADJUSTMENT']; ?>

		<select id="adjustmentType" name="adjustmentType" class=small onchange="calcTotal();">
			<option value="+"><?php echo $this->_tpl_vars['APP']['LBL_ADD_ITEM']; ?>
</option>
			<option value="-"><?php echo $this->_tpl_vars['APP']['LBL_DEDUCT']; ?>
</option>
		</select>
	</td>
	<td class="crmTableRow small" align="right">
		<input id="adjustment" name="adjustment" type="text" class="small" style="width:40px" align="right" value="<?php echo $this->_tpl_vars['FINAL']['adjustment']; ?>
" onBlur="calcTotal();">
	</td>
   </tr>

	<tr valign="top">
		<td width="88%" colspan="2" class="crmTableRow small lineOnTop" align="right"><b>คูปองส่วนลด (บาท)</b></td>
		<td width="12%" class="crmTableRow small lineOnTop" align="right">
			<input class="small" type="text" id="discount_coupons" value="<?php echo $this->_tpl_vars['FINAL']['discount_coupon']; ?>
" onBlur="calcTotal();" name="discount_coupon" size="5" value="0.00" style="width:70px; text-align: right !important;" >
		</td>
   </tr>

   <tr valign="top">
	<td class="crmTableRow big lineOnTop" style="border-right:1px #dadada;">&nbsp;</td>
	<td class="crmTableRow big lineOnTop" align="right"><b><?php echo $this->_tpl_vars['APP']['LBL_GRAND_TOTAL']; ?>
</b></td>
	<td id="grandTotal" name="grandTotal" class="crmTableRow big lineOnTop" align="right"><?php echo $this->_tpl_vars['FINAL']['grandTotal']; ?>
</td>
   </tr>
</table>

	<input type="hidden" name="totalProductCount" id="totalProductCount" value="<?php echo $this->_tpl_vars['row_no']; ?>
">
		<input type="hidden" name="subtotal" id="subtotal" value="">
		<input type="hidden" name="total" id="total" value="">

        <input type="hidden" name="total_discount" id="total_discount" value="">
        <input type="hidden" name="total_after_discount" id="total_after_discount" value="">
		<input type="hidden" name="total_without_vat" id="total_without_vat" value="">
        <input type="hidden" name="total_tax" id="total_tax" value="">
        <input type="hidden" name="mac5_accountcode" id="mac5_accountcode" value="">
		<input type="hidden" name="hdn_bill_discount" id="hdn_bill_discount" value="">
	    <input type="hidden" name="hdn_total_after_bill_discount" id="hdn_total_after_bill_discount" value="">
</td></tr>
<!-- Upto this Added to display the Product Details -->

<?php $_from = $this->_tpl_vars['ASSOCIATEDPRODUCTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row_no'] => $this->_tpl_vars['data']):
?>
	<!-- This is added to call the function calcCurrentTax which will calculate the tax amount from percentage -->
	<?php $_from = $this->_tpl_vars['data']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_row_no'] => $this->_tpl_vars['tax_data']):
?>
		<?php $this->assign('taxname', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tax_data']['taxname'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_percentage') : smarty_modifier_cat($_tmp, '_percentage')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
			<script>calcCurrentTax('<?php echo $this->_tpl_vars['taxname']; ?>
',<?php echo $this->_tpl_vars['row_no']; ?>
,<?php echo $this->_tpl_vars['tax_row_no']; ?>
);</script>
	<?php endforeach; endif; unset($_from); ?>
	<?php $this->assign('entityIndentifier', ((is_array($_tmp='entityType')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['row_no']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['row_no']))); ?>
	<?php if ($this->_tpl_vars['MODULE'] == 'Invoice' && $this->_tpl_vars['data'][$this->_tpl_vars['entityIndentifier']] != 'Services'): ?>
		<script>stock_alert('<?php echo $this->_tpl_vars['row_no']; ?>
');</script>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>


<!-- Added to calculate the tax and total values when page loads -->
<script>decideTaxDiv();</script>
<script>calcTotal();</script>
<script>calcSHTax();</script>
<!-- This above div is added to display the tax informations -->

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
			//console.log("Found elements: ", <?php echo $this->_tpl_vars['field']['class']; ?>
); 
			
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

