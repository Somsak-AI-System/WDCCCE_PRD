<?php /* Smarty version 2.6.18, created on 2026-04-08 16:51:23
         compiled from EditViewUI.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'EditViewUI.tpl', 79, false),array('modifier', 'aicrm_imageurl', 'EditViewUI.tpl', 122, false),array('modifier', 'number_format', 'EditViewUI.tpl', 288, false),array('modifier', 'getTranslatedCurrencyString', 'EditViewUI.tpl', 2658, false),array('modifier', 'substr', 'EditViewUI.tpl', 2730, false),)), $this); ?>

 <script language="JavaScript" type="text/javascript">
<?php echo '
	 jQuery.fn.extend({
		insertAtCaret: function(myValue){
		  return this.each(function(i) {
			if (document.selection) {
			  //For browsers like Internet Explorer
			  this.focus();
			  var sel = document.selection.createRange();
			  sel.text = myValue;
			  this.focus();
			}
			else if (this.selectionStart || this.selectionStart == \'0\') {
			  //For browsers like Firefox and Webkit based
			  var startPos = this.selectionStart;
			  var endPos = this.selectionEnd;
			  var scrollTop = this.scrollTop;
			  this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
			  this.focus();
			  this.selectionStart = startPos + myValue.length;
			  this.selectionEnd = startPos + myValue.length;
			  this.scrollTop = scrollTop;
			} else {
			  this.value += myValue;
			  this.focus();
			}
		  });
		}
	});
'; ?>

	 var allOptions = null;
  	 function setvalue_testarray(field,value){
		if(value !== '--None--'){
			if(field == 'sms_message' ){
				jQuery('textarea#'+field).insertAtCaret(value);
				calculator();
			}
			if (field == 'email_message'){
				CKEDITOR.instances[field].insertText(value);
			}
		}
	 }
     function setAllOptions(inputOptions)
     {
         allOptions = inputOptions;
     }
    function modifyMergeFieldSelect(cause, effect , field)
    {
        var selected = cause.options[cause.selectedIndex].value;  id=field
        var s = allOptions[cause.selectedIndex];

        effect.length = s;
        for (var i = 0; i < s; i++)
        {
            effect.options[i] = s[i];
            }
        //document.getElementById('sms_message').value = '';
        }
<?php echo '

     function init()
     {
         var blankOption = new Option(\'--None--\', \'--None--\');
         var options = null;
 		'; ?>


                 var allOpts = new Object(<?php echo count($this->_tpl_vars['ALL_VARIABLES']); ?>
+1);
                 <?php $this->assign('alloptioncount', '0'); ?>
                 <?php $_from = $this->_tpl_vars['ALL_VARIABLES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['module']):
?>
                 options = new Object(<?php echo count($this->_tpl_vars['module']); ?>
+1);
                 <?php $this->assign('optioncount', '0'); ?>
             options[<?php echo $this->_tpl_vars['optioncount']; ?>
] = blankOption;
             		<?php $_from = $this->_tpl_vars['module']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>
              			<?php $this->assign('optioncount', $this->_tpl_vars['optioncount']+1); ?>
                        options[<?php echo $this->_tpl_vars['optioncount']; ?>
] = new Option('<?php echo $this->_tpl_vars['detail']['0']; ?>
', '<?php echo $this->_tpl_vars['detail']['1']; ?>
');
                    <?php endforeach; endif; unset($_from); ?>
                <?php $this->assign('alloptioncount', $this->_tpl_vars['alloptioncount']+1); ?>
              allOpts[<?php echo $this->_tpl_vars['alloptioncount']; ?>
] = options;
             <?php endforeach; endif; unset($_from); ?>
         setAllOptions(allOpts);
     }
<?php echo '


'; ?>

</script>

		<?php $this->assign('uitype', ($this->_tpl_vars['maindata'][0][0])); ?>
		<?php $this->assign('fldlabel', ($this->_tpl_vars['maindata'][1][0])); ?>
		<?php $this->assign('fldlabel_sel', ($this->_tpl_vars['maindata'][1][1])); ?>
		<?php $this->assign('fldlabel_combo', ($this->_tpl_vars['maindata'][1][2])); ?>
		<?php $this->assign('fldlabel_other', ($this->_tpl_vars['maindata'][1][3])); ?>
		<?php $this->assign('fldname', ($this->_tpl_vars['maindata'][2][0])); ?>
		<?php $this->assign('fldvalue', ($this->_tpl_vars['maindata'][3][0])); ?>
		<?php $this->assign('secondvalue', ($this->_tpl_vars['maindata'][3][1])); ?>
		<?php $this->assign('thirdvalue', ($this->_tpl_vars['maindata'][3][2])); ?>
		<?php $this->assign('typeofdata', ($this->_tpl_vars['maindata'][4])); ?>
	 	<?php $this->assign('vt_tab', ($this->_tpl_vars['maindata'][5][0])); ?>

		<?php if ($this->_tpl_vars['typeofdata'] == 'M'): ?>
			<?php $this->assign('mandatory_field', "*"); ?>
		<?php else: ?>
			<?php $this->assign('mandatory_field', ""); ?>
		<?php endif; ?>

				<?php $this->assign('usefldlabel', $this->_tpl_vars['fldlabel']); ?>
		<?php $this->assign('fldhelplink', ""); ?>
		<?php if ($this->_tpl_vars['FIELDHELPINFO'] && $this->_tpl_vars['FIELDHELPINFO'][$this->_tpl_vars['fldname']]): ?>
			<?php $this->assign('fldhelplinkimg', aicrm_imageurl('help_icon.gif', $this->_tpl_vars['THEME'])); ?>
			<?php $this->assign('fldhelplink', "<img style='cursor:pointer' onclick='vtlib_field_help_show(this, \"".($this->_tpl_vars['fldname'])."\");' border=0 src='".($this->_tpl_vars['fldhelplinkimg'])."'>"); ?>
			<?php if ($this->_tpl_vars['uitype'] != '10'): ?>
				<?php $this->assign('usefldlabel', ($this->_tpl_vars['fldlabel'])." ".($this->_tpl_vars['fldhelplink'])); ?>
			<?php endif; ?>
		<?php endif; ?>
		
				<?php if ($this->_tpl_vars['uitype'] == '10'): ?>
			<td width=20% class="dvtCellLabel" align=right>
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
			<?php echo $this->_tpl_vars['fldlabel']['displaylabel']; ?>


			<?php if (count ( $this->_tpl_vars['fldlabel']['options'] ) == 1): ?>
				<?php $this->assign('use_parentmodule', $this->_tpl_vars['fldlabel']['options']['0']); ?>
				<input type='hidden' class='small' name="<?php echo $this->_tpl_vars['fldname']; ?>
_type" value="<?php echo $this->_tpl_vars['use_parentmodule']; ?>
"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['use_parentmodule']]; ?>

			<?php else: ?>
			<br>
			<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
			<select id="<?php echo $this->_tpl_vars['fldname']; ?>
_type" class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
_type" onChange='document.QcEditView.<?php echo $this->_tpl_vars['fldname']; ?>
_display.value=""; document.QcEditView.<?php echo $this->_tpl_vars['fldname']; ?>
.value="";'>
			<?php else: ?>
			<select id="<?php echo $this->_tpl_vars['fldname']; ?>
_type" class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
_type" onChange='document.EditView.<?php echo $this->_tpl_vars['fldname']; ?>
_display.value=""; document.EditView.<?php echo $this->_tpl_vars['fldname']; ?>
.value="";$("qcform").innerHTML=""'>
			<?php endif; ?>
			<?php $_from = $this->_tpl_vars['fldlabel']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
				<option value="<?php echo $this->_tpl_vars['option']; ?>
"
				<?php if ($this->_tpl_vars['fldlabel']['selected'] == $this->_tpl_vars['option']): ?>selected<?php endif; ?>>
				<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['option']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['option']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['option']; ?>
<?php endif; ?>
				</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			<?php echo $this->_tpl_vars['fldhelplink']; ?>


			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['fldvalue']['entityid']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
">
				<input id="<?php echo $this->_tpl_vars['fldname']; ?>
_display" name="<?php echo $this->_tpl_vars['fldname']; ?>
_display" id="edit_<?php echo $this->_tpl_vars['fldname']; ?>
_display" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']['displayvalue']; ?>
">&nbsp;

				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
				<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"
alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module="+ document.QcEditView.<?php echo $this->_tpl_vars['fldname']; ?>
_type.value +"&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield=<?php echo $this->_tpl_vars['fldname']; ?>
&srcmodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&forrecord=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer; vertical-align: middle;'>&nbsp;
				<?php else: ?>
				<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"
alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module="+ document.EditView.<?php echo $this->_tpl_vars['fldname']; ?>
_type.value +"&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield=<?php echo $this->_tpl_vars['fldname']; ?>
&srcmodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&forrecord=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer; vertical-align: middle;'>&nbsp;
				<?php endif; ?>

				<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
"
alt="Clear" title="Clear" LANGUAGE=javascript	onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_display.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
			</td>
		
		<?php elseif ($this->_tpl_vars['uitype'] == 2): ?>
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?>
			</td>
			<td width=30% align=left class="dvtCellInfo">
            	<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC">
                <?php else: ?>
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
                <?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 3 || $this->_tpl_vars['uitype'] == 4): ?><!-- Non Editable field, only configured value will be loaded -->
				<td width=20% class="dvtCellLabel" align=right><font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?></td>
                <td width=30% align=left class="dvtCellInfo"><input readonly type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" <?php if ($this->_tpl_vars['MODE'] == 'edit'): ?> value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" <?php else: ?> value="<?php echo $this->_tpl_vars['MOD_SEQ_ID']; ?>
" <?php endif; ?> class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>

		<?php elseif ($this->_tpl_vars['uitype'] == 11 || $this->_tpl_vars['uitype'] == 1 || $this->_tpl_vars['uitype'] == 13 || $this->_tpl_vars['uitype'] == 7 || $this->_tpl_vars['uitype'] == 9): ?>

		   	<?php if ($this->_tpl_vars['fldname'] != 'discount_coupon'): ?>
				<td width=20% class="dvtCellLabel" align=right><font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?></td>
			<?php endif; ?>	
          
			<?php if ($this->_tpl_vars['fldname'] == 'tickersymbol' && $this->_tpl_vars['MODULE'] == 'Accounts'): ?>
				<td width=30% align=left class="dvtCellInfo">
					<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';<?php if ($this->_tpl_vars['fldname'] == 'tickersymbol' && $this->_tpl_vars['MODULE'] == 'Accounts'): ?>sensex_info()<?php endif; ?>">
					<span id="vtbusy_info" style="display:none;">
						<img src="<?php echo aicrm_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span>
				</td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'postal_code' && $this->_tpl_vars['MODULE'] == 'Accounts'): ?>
				<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

			<?php elseif ($this->_tpl_vars['fldname'] == 'discount_coupon' && $this->_tpl_vars['MODULE'] == 'Quotes'): ?>
				<input type="hidden" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">

			<?php elseif ($this->_tpl_vars['fldname'] == 'province' || $this->_tpl_vars['fldname'] == 'district' || $this->_tpl_vars['fldname'] == 'mafee_total' || $this->_tpl_vars['fldname'] == 'transfer_total' || $this->_tpl_vars['fldname'] == 'total_contract' || $this->_tpl_vars['fldname'] == 'document' || $this->_tpl_vars['fldname'] == 'account_cpprovince' || $this->_tpl_vars['fldname'] == 'account_cpdistrict' || $this->_tpl_vars['fldname'] == 'productcode' || $this->_tpl_vars['fldname'] == 'email_bounce' || $this->_tpl_vars['fldname'] == 'email_server' || ( $this->_tpl_vars['fldname'] == 'location' && $this->_tpl_vars['MODULE'] == 'Calendar' ) || ( $this->_tpl_vars['fldname'] == 'location_chkout' && $this->_tpl_vars['MODULE'] == 'Calendar' ) || $this->_tpl_vars['fldname'] == 'lead_district' || $this->_tpl_vars['fldname'] == 'lead_province' || $this->_tpl_vars['fldname'] == 'checkoutdate' || $this->_tpl_vars['fldname'] == 'checkindate' || $this->_tpl_vars['fldname'] == 'quotes_district' || $this->_tpl_vars['fldname'] == 'district_shipping' || $this->_tpl_vars['fldname'] == 'quote_no_rev' || $this->_tpl_vars['fldname'] == 'rev_no' || $this->_tpl_vars['fldname'] == 'pro_priceinclude' || $this->_tpl_vars['fldname'] == 'user_signature_time' || $this->_tpl_vars['fldname'] == 'customer_signature_time' || $this->_tpl_vars['fldname'] == 'cf_4749' || $this->_tpl_vars['fldname'] == 'sale_checkin' || $this->_tpl_vars['fldname'] == 'sale_checkoutlocation' || $this->_tpl_vars['fldname'] == 'sale_checkindate' || $this->_tpl_vars['fldname'] == 'sale_checkoutdate' || $this->_tpl_vars['fldname'] == 'billingdistrict' || $this->_tpl_vars['fldname'] == 'billingprovince' || $this->_tpl_vars['fldname'] == 'shippingdistrict' || $this->_tpl_vars['fldname'] == 'shippingprovince' || $this->_tpl_vars['fldname'] == 'leadname' || $this->_tpl_vars['fldname'] == 'contactname' || $this->_tpl_vars['fldname'] == 'province_shipping' || $this->_tpl_vars['fldname'] == 'quotes_province' || $this->_tpl_vars['fldname'] == 'ref_service_request_no' || $this->_tpl_vars['fldname'] == 'grouping' || $this->_tpl_vars['fldname'] == 'business_partner' || $this->_tpl_vars['fldname'] == 'customer_name' || $this->_tpl_vars['fldname'] == 'search_term_1_2' || $this->_tpl_vars['fldname'] == 'streets' || $this->_tpl_vars['fldname'] == 'street_4' || $this->_tpl_vars['fldname'] == 'street_5' || $this->_tpl_vars['fldname'] == 'districts' || $this->_tpl_vars['fldname'] == 'city' || $this->_tpl_vars['fldname'] == 'countrys' || $this->_tpl_vars['fldname'] == 'tax_id' || $this->_tpl_vars['fldname'] == 'contact_person' || $this->_tpl_vars['fldname'] == 'telephone' || $this->_tpl_vars['fldname'] == 'fax' || $this->_tpl_vars['fldname'] == 'e_mail' || $this->_tpl_vars['fldname'] == 'sales_organization' || $this->_tpl_vars['fldname'] == 'distribution_channel' || $this->_tpl_vars['fldname'] == 'cust_pric_procedure' || ( $this->_tpl_vars['fldname'] == 'customer_group' && $this->_tpl_vars['MODULE'] != 'Competitor' ) || $this->_tpl_vars['fldname'] == 'sales_district' || $this->_tpl_vars['fldname'] == 'currency' || $this->_tpl_vars['fldname'] == 'shipping_condition' || $this->_tpl_vars['fldname'] == 'payment_terms' || $this->_tpl_vars['fldname'] == 'sales_group' || $this->_tpl_vars['fldname'] == 'sg_description' || $this->_tpl_vars['fldname'] == 'sales_office' || $this->_tpl_vars['fldname'] == 'customer_group_1' || $this->_tpl_vars['fldname'] == 'cg1_description' || $this->_tpl_vars['fldname'] == 'agent_payer_1' || $this->_tpl_vars['fldname'] == 'acct_clerks_tel_no' || $this->_tpl_vars['fldname'] == 'material' || $this->_tpl_vars['fldname'] == 'base_unit_of_measure' || $this->_tpl_vars['fldname'] == 'um_coversion_m2_pcs' || $this->_tpl_vars['fldname'] == 'description_en' || $this->_tpl_vars['fldname'] == 'description_th' || $this->_tpl_vars['fldname'] == 'status' || $this->_tpl_vars['fldname'] == 'desc_status' || $this->_tpl_vars['fldname'] == 'valuation_class' || $this->_tpl_vars['fldname'] == 'valuation_class_description' || $this->_tpl_vars['fldname'] == 'material_group' || $this->_tpl_vars['fldname'] == 'mat_group' || $this->_tpl_vars['fldname'] == 'plant' || $this->_tpl_vars['fldname'] == 'sales_org' || $this->_tpl_vars['fldname'] == 'channel' || $this->_tpl_vars['fldname'] == 'mat_price_grp' || $this->_tpl_vars['fldname'] == 'mat_price_grp_desc' || $this->_tpl_vars['fldname'] == 'mat_gp1' || $this->_tpl_vars['fldname'] == 'mat_gp1_desciption' || $this->_tpl_vars['fldname'] == 'mat_gp2' || $this->_tpl_vars['fldname'] == 'mat_gp2_desciption' || $this->_tpl_vars['fldname'] == 'mat_gp3' || $this->_tpl_vars['fldname'] == 'mat_gp3_desciption' || $this->_tpl_vars['fldname'] == 'mat_gp4' || $this->_tpl_vars['fldname'] == 'mat_gp4_desciption' || $this->_tpl_vars['fldname'] == 'mat_gp5' || $this->_tpl_vars['fldname'] == 'mat_gp5_desciption' || $this->_tpl_vars['fldname'] == 'country' || $this->_tpl_vars['fldname'] == 'country_of_origin' || $this->_tpl_vars['fldname'] == 'list_price' || $this->_tpl_vars['fldname'] == 'piece_per_carton' || $this->_tpl_vars['fldname'] == 'squaremeters_per_carton' || $this->_tpl_vars['fldname'] == 'price_per_piece' || $this->_tpl_vars['fldname'] == 'price_per_squaremeter' || $this->_tpl_vars['fldname'] == 'quantity' || $this->_tpl_vars['fldname'] == 'quantity_sheet' || $this->_tpl_vars['fldname'] == 'revised_no' || $this->_tpl_vars['fldname'] == 'ref_sample_request' || $this->_tpl_vars['fldname'] == 'project_name' || $this->_tpl_vars['fldname'] == 'erp_product_name' || $this->_tpl_vars['fldname'] == 'erp_product_selling_stock_01_01' || $this->_tpl_vars['fldname'] == 'erp_product_defects_stock_01_04' || $this->_tpl_vars['fldname'] == 'erp_product_defects_stock_01_05' || $this->_tpl_vars['fldname'] == 'erp_product_defects_stock_01_07' || $this->_tpl_vars['fldname'] == 'erp_product_booking_stock_01_08' || $this->_tpl_vars['fldname'] == 'erp_product_selling_stock_01_09' || $this->_tpl_vars['fldname'] == 'erp_product_selling_stock_02_01' || $this->_tpl_vars['fldname'] == 'erp_product_defects_stock_02_04' || $this->_tpl_vars['fldname'] == 'erp_product_selling_stock_03_01' || $this->_tpl_vars['fldname'] == 'erp_product_defects_stock_03_04' || $this->_tpl_vars['fldname'] == 'erp_product_selling_stock_04_01' || $this->_tpl_vars['fldname'] == 'erp_product_defects_stock_05_04' || $this->_tpl_vars['fldname'] == 'productname' || $this->_tpl_vars['fldname'] == 'erp_response_status' || $this->_tpl_vars['fldname'] == 'reference_id' || $this->_tpl_vars['fldname'] == 'projects_reference_id'): ?>

				<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

			<?php elseif (( $this->_tpl_vars['fldname'] == 'cf_25708' || $this->_tpl_vars['fldname'] == 'phone' || $this->_tpl_vars['fldname'] == 'email' ) && $this->_tpl_vars['MODULE'] == 'Calendar' && $this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['flag_send_report'] == 1): ?>
				<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

				
			<?php elseif ($this->_tpl_vars['fldname'] == 'billingsubdistrict'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=billingprovince&f3=billingdistrict&f4=billingsubdistrict&f5=billingpostalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

			<?php elseif ($this->_tpl_vars['fldname'] == 'shippingsubdistrict'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=shippingprovince&f3=shippingdistrict&f4=shippingsubdistrict&f5=shippingpostalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'sms_character' || $this->_tpl_vars['fldname'] == 'sms_credit'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; width:50px"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'prepare_status' || $this->_tpl_vars['fldname'] == 'email_status' || $this->_tpl_vars['fldname'] == 'send_status' || $this->_tpl_vars['fldname'] == 'email_start_date' || $this->_tpl_vars['fldname'] == 'email_start_time' || $this->_tpl_vars['fldname'] == 'prepare_date' || $this->_tpl_vars['fldname'] == 'prepare_time' || $this->_tpl_vars['fldname'] == 'send_date' || $this->_tpl_vars['fldname'] == 'send_time'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'sub_district' && $this->_tpl_vars['MODULE'] == 'Quotes'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province&f3=district&f4=sub_district&f5=postal_code","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'quotes_subdistrict' && $this->_tpl_vars['MODULE'] == 'Quotes'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=quotes_region&f2=quotes_province&f3=quotes_district&f4=quotes_subdistrict&f5=quotes_postcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'subdistrict_shipping' && $this->_tpl_vars['MODULE'] == 'Quotes'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=quotes_region&f2=province_shipping&f3=district_shipping&f4=subdistrict_shipping&f5=postcode_shipping","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'sub_district' && $this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province&f3=district&f4=sub_district&f5=postal_code","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'sub_district' && $this->_tpl_vars['MODULE'] == 'Salesorder'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province&f3=district&f4=sub_district&f5=postal_code","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'sub_district_shipping' && $this->_tpl_vars['MODULE'] == 'Salesorder'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province_shipping&f3=district_shipping&f4=sub_district_shipping&f5=postal_code_shipping","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'region'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=province&f3=district&f4=subdistrict&f5=postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'lead_subdistrinct'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=lead_province&f3=lead_district&f4=lead_subdistrinct&f5=lead_postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'subdistrict'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=province&f3=district&f4=subdistrict&f5=postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'account_cpsubdistrict'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=account_cpregion&f2=account_cpprovince&f3=account_cpdistrict&f4=account_cpsubdistrict&f5=account_cppostalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            <?php elseif ($this->_tpl_vars['fldname'] == 'working_region'): ?>
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="<?php echo aicrm_imageurl('addrss.gif', $this->_tpl_vars['THEME']); ?>
" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=working_region&f2=working_province&f3=working_district&f4=working_subdistrict&f5=working_postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>
			
		    <?php else: ?>
				<td width=30% align=left class="dvtCellInfo">
                <?php if (( $this->_tpl_vars['fldname'] == 'firstname' || $this->_tpl_vars['fldname'] == 'middlename' || $this->_tpl_vars['fldname'] == 'lastname' || $this->_tpl_vars['fldname'] == 'f_name_en' || $this->_tpl_vars['fldname'] == 'l_name_en' ) && ( $this->_tpl_vars['MODULE'] == 'Accounts' )): ?>
                	<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_account_name();">

                <?php elseif (( $this->_tpl_vars['fldname'] == 'firstname' || $this->_tpl_vars['fldname'] == 'middlename' || $this->_tpl_vars['fldname'] == 'lastname' ) && ( $this->_tpl_vars['MODULE'] == 'Leads' )): ?>
                	<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_lead_name();">

                <?php elseif (( $this->_tpl_vars['fldname'] == 'firstname' || $this->_tpl_vars['fldname'] == 'middlename' || $this->_tpl_vars['fldname'] == 'lastname' ) && ( $this->_tpl_vars['MODULE'] == 'Contacts' )): ?>
                	<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_contact_name();">

				<?php elseif ($this->_tpl_vars['fldname'] == 'product_code_crm' && $this->_tpl_vars['MODULE'] == 'Products'): ?>
                	<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_product_name();">

				<?php elseif (( $this->_tpl_vars['fldname'] == 'branch_code' ) && $this->_tpl_vars['MODULE'] == 'Accounts'): ?>
           			<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onkeyup="validateBranchCode(this)">

				<?php elseif (( $this->_tpl_vars['fldname'] == 'claim_from' || $this->_tpl_vars['fldname'] == 'sale_channel' ) && $this->_tpl_vars['MODULE'] == 'Claim'): ?>
           			<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" style="background-color:#dfe8f1" readonly onBlur="this.className='detailedViewTextBox'">

                <?php else: ?>

                	<?php if ($this->_tpl_vars['uitype'] == 7): ?>
                		<div class="hide-inputbtns">
                			<?php if ($this->_tpl_vars['fldname'] == 'number_of_vouchers' || $this->_tpl_vars['fldname'] == 'vouchers_of_used' || $this->_tpl_vars['fldname'] == 'vouchers_of_remaining' || $this->_tpl_vars['fldname'] == 'voucher_amount' || $this->_tpl_vars['fldname'] == 'voucher_used' || $this->_tpl_vars['fldname'] == 'voucher_available' || $this->_tpl_vars['fldname'] == 'generate'): ?>
                				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="text" step= "0.000001" class="form-control detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value='<?php echo ((is_array($_tmp=$this->_tpl_vars['fldvalue'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : number_format($_tmp, 0)); ?>
' readonly="readonly" style="background-color: #CCC;">
							<?php else: ?>
								<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="number" step="0.00000000001" class="form-control detailedViewTextBox number" onFocus="this.className='detailedViewTextBox'" onBlur="this.className='detailedViewTextBox'" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" >
							<?php endif; ?>
						</div>
					<?php elseif ($this->_tpl_vars['uitype'] == 11): ?>

						<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" maxlength="12" onkeypress="return isPhoneNumber(event)">

                	<?php else: ?>
                		<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
                	<?php endif; ?>
                <?php endif; ?>
                </td>
			<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 19 || $this->_tpl_vars['uitype'] == 20): ?>
			<!-- In Add Comment are we should not display anything -->
			<?php if ($this->_tpl_vars['fldlabel'] == $this->_tpl_vars['MOD']['LBL_ADD_COMMENT'] || $this->_tpl_vars['fldlabel'] == 'Comment Plan' || $this->_tpl_vars['fldname'] == 'comments' || $this->_tpl_vars['fldname'] == 'history_status'): ?>
				<?php $this->assign('fldvalue', ""); ?>
			<?php endif; ?>
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
				<?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td colspan=3>
                <?php if ($this->_tpl_vars['fldname'] == 'email_message' || $this->_tpl_vars['fldname'] == 'sms_message'): ?>
                 <table style="margin-left:10px;">
                    <tr>
                         <td>Module</td>
                         <td></td>
                         <td style="border-left:2px dotted #cccccc;">Column</td>
                         <td></td>
                         <td></td>
                    </tr>
                    <tr>
                        <td>
                         <select style="font-family: Arial, Helvetica, sans-serif;font-size: 11px;color: #000000;border:1px solid #bababa;padding-left:5px;background-color:#ffffff;" id="entityType" class="small"
                         ONCHANGE="modifyMergeFieldSelect(this, document.getElementById('mergeFieldSelect'),<?php echo $this->_tpl_vars['fldname']; ?>
);" tabindex="6">
                         <OPTION VALUE="0" selected><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>

                         <OPTION VALUE="1"><?php echo $this->_tpl_vars['UMOD']['LBL_ACCOUNT_FIELDS']; ?>

                         <?php if ($this->_tpl_vars['MODULE'] != 'Smartquestionnaire'): ?>
                         	<OPTION VALUE="2"><?php echo $this->_tpl_vars['UMOD']['LBL_CONTACT_FIELDS']; ?>

                         	<?php if ($this->_tpl_vars['MODULE'] != 'Surveysmartemail'): ?>
                         		<OPTION VALUE="3" ><?php echo $this->_tpl_vars['UMOD']['LBL_LEAD_FIELDS']; ?>

                         	<?php endif; ?>
                         <!-- <OPTION VALUE="4" ><?php echo $this->_tpl_vars['UMOD']['LBL_OPPORTUNTY_FIELDS']; ?>
 -->
                         <?php endif; ?>
                         </select>
                        <td>
                        <td style="border-left:2px dotted #cccccc;">
                            <select style="font-family: Arial, Helvetica, sans-serif;font-size: 11px;color: #000000;border:1px solid #bababa;padding-left:5px;background-color:#ffffff;" class="small" id="mergeFieldSelect" onchange="setvalue_testarray('<?php echo $this->_tpl_vars['fldname']; ?>
',this.options[this.selectedIndex].value);" tabindex="7"><option value="0" selected><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</select>
                        <td>
                    </tr>
                 </table>
                 <?php endif; ?>

               
					<?php if ($this->_tpl_vars['MODULE'] == 'Samplerequisition' && ( $this->_tpl_vars['fldname'] == 'rejected_reason' || $this->_tpl_vars['fldname'] == 'cancel_reason' )): ?>
                		<textarea class="detailedViewTextBox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onFocus="this.className='detailedViewTextBoxOn'" name="<?php echo $this->_tpl_vars['fldname']; ?>
"  id="<?php echo $this->_tpl_vars['fldname']; ?>
"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8" readonly="readonly" style="background-color:#CCC"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
                	<?php else: ?>
                		<textarea class="detailedViewTextBox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onFocus="this.className='detailedViewTextBoxOn'" name="<?php echo $this->_tpl_vars['fldname']; ?>
"  id="<?php echo $this->_tpl_vars['fldname']; ?>
"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
                	<?php endif; ?>

                

				<?php if ($this->_tpl_vars['fldlabel'] == $this->_tpl_vars['MOD']['Solution']): ?>
					<input type = "hidden" name="helpdesk_solution" value = '<?php echo $this->_tpl_vars['fldvalue']; ?>
'>
				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 70): ?>

			<?php if ($this->_tpl_vars['fldname'] == 'user_signature_time' || $this->_tpl_vars['fldname'] == 'customer_signature_time'): ?>
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?>
				</td>
                <td width=30% align=left class="dvtCellInfo">
                	<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color:#CCC">
               	</td>
            <?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 21 || $this->_tpl_vars['uitype'] == 24): ?>
			<?php if ($this->_tpl_vars['fldname'] == 'erp_product_description'): ?>
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
					<?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				</td>
				<td width=30% align=left class="dvtCellInfo" valign="top" style="padding:2px">
					<textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" rows=2  readonly="readonly" style="background-color:#CCC"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
				</td>
			<?php else: ?>
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
					<?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				</td>
				<td width=30% align=left class="dvtCellInfo" valign="top" style="padding:2px">
					<textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" rows=2><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
				</td>
			<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 15 || $this->_tpl_vars['uitype'] == 16): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
				<?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">

            	<?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
            		<?php if ($this->_tpl_vars['fldname'] == 'activitytype' && $this->_tpl_vars['MODE'] == 'edit'): ?>
                    	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" style="width:160px;;background-color: #CCC" />
            		<?php else: ?>
            			<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" style="width:160px;" />
            		<?php endif; ?>

                <?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts' && $this->_tpl_vars['fldname'] == 'approved_status'): ?>
                    <select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" style="width:160px;background-color: #CCC" />
                <?php elseif ($this->_tpl_vars['MODULE'] == 'Purchasesorder' && $this->_tpl_vars['fldname'] == 'vender'): ?>
                	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" onchange="get_valuevender();" />
				<?php elseif ($this->_tpl_vars['MODULE'] == 'Goodsreceive' && $this->_tpl_vars['fldname'] == 'vender'): ?>
                	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" onchange="get_valuevender();" />
                <?php elseif ($this->_tpl_vars['MODULE'] == 'Purchasesorder' && $this->_tpl_vars['fldname'] == 'buyer'): ?>
                	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" onchange="get_valuebuyer();" />
                <?php elseif ($this->_tpl_vars['MODULE'] == 'Purchasesorder' && $this->_tpl_vars['fldname'] == 'payment_method'): ?>
                	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small"/>
                <?php elseif ($this->_tpl_vars['MODULE'] == 'Purchasesorder' && $this->_tpl_vars['fldname'] == 'payment_term'): ?>
                	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small"/>
                <?php else: ?>
                    <select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small" >
                <?php endif; ?>

                <?php if ($this->_tpl_vars['fldname'] == 'quotation_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'sms_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'email_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'smartquestionnaire_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'announcement_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>                       
                <?php elseif ($this->_tpl_vars['fldname'] == 'salesorder_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'service_request_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'job_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>                    
                <?php elseif ($this->_tpl_vars['fldname'] == 'stage'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'order_status_order'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
               	<?php elseif ($this->_tpl_vars['fldname'] == 'approved_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                       		 <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                        	 <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'case_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                       		 <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                        	 <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'activitytype' && $this->_tpl_vars['MODE'] == 'edit'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                       		 <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                        	 <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'competitor_seq_no'): ?>
                    
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
	                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
	                            <?php echo $this->_tpl_vars['arr'][0]; ?>

	                        </option>
                        <?php else: ?>

	                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
	                       		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
	                        <?php else: ?>
	                        	
	                        	<?php if ($this->_tpl_vars['arr'][1] == '0'): ?>
	                        		<?php if ($this->_tpl_vars['seq_no0'] == 'true'): ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
		                        	<?php else: ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
		                        	<?php endif; ?>

		                        <?php elseif ($this->_tpl_vars['arr'][1] == '1'): ?>
	                        		<?php if ($this->_tpl_vars['seq_no1'] == 'true'): ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
		                        	<?php else: ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
		                        	<?php endif; ?>

		                        <?php elseif ($this->_tpl_vars['arr'][1] == '2'): ?>
	                        		<?php if ($this->_tpl_vars['seq_no2'] == 'true'): ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
		                        	<?php else: ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
		                        	<?php endif; ?>

		                        <?php elseif ($this->_tpl_vars['arr'][1] == '3'): ?>
	                        		<?php if ($this->_tpl_vars['seq_no3'] == 'true'): ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
		                        	<?php else: ?>
		                        		<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
		                        	<?php endif; ?>
		                        	
		                        <?php else: ?>
		                        	<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
		                        <?php endif; ?>

	                        <?php endif; ?>
                           	<?php echo $this->_tpl_vars['arr'][0]; ?>

                        	</option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'samplerequisition_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'vender'): ?>
                	<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 <?php echo $this->_tpl_vars['arr'][3]; ?>
>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 <?php echo $this->_tpl_vars['arr'][3]; ?>
>
                                <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>

                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'buyer'): ?>
                	<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 <?php echo $this->_tpl_vars['arr'][3]; ?>
>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 <?php echo $this->_tpl_vars['arr'][3]; ?>
>
                                <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>

                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
				<?php elseif ($this->_tpl_vars['fldname'] == 'po_status'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
				<?php elseif ($this->_tpl_vars['fldname'] == 'payment_terms'): ?>
                    <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <?php if ($this->_tpl_vars['arr'][2] != ''): ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                        <?php else: ?>
                             <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 style='color: #777777' disabled>
                        <?php endif; ?>
                           <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>
                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>
                <?php else: ?>

                	<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
                        <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                            <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php else: ?>
                        <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                                <?php echo $this->_tpl_vars['arr'][0]; ?>

                        </option>
                        <?php endif; ?>

                    <?php endforeach; else: ?>
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                    <?php endif; unset($_from); ?>


                <?php endif; ?>
                   </select>

				<?php if ($this->_tpl_vars['fldname'] == 'ticketpriorities'): ?>
                	<br />
                    ร้องเรียน&nbsp;: เร่งด่วน=1,สำคัญ=3,ปกติ=5<br />
                    ร้องขอ&nbsp;&nbsp;&nbsp;&nbsp;: เร่งด่วน=1,สำคัญ=3,ปกติ=7
                <?php endif; ?>
			</td>


		<?php elseif ($this->_tpl_vars['uitype'] == 33): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   <select MULTIPLE name="<?php echo $this->_tpl_vars['fldname']; ?>
[]" size="4" style="width:310px;height: 100px;" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
					<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                    	<?php echo $this->_tpl_vars['arr'][0]; ?>

                    </option>
				<?php endforeach; endif; unset($_from); ?>
			   </select>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 201): ?>

			<?php if ($this->_tpl_vars['MODULE'] == 'Calendar' && $this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['flag_send_report'] == 1): ?>
				<td width="20%" class="dvtCellLabel" align=right>
					<select class="small" name="parent_type" disabled onChange='document.EditView.parent_name.value=""; document.EditView.parentid.value=""'>
						<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
							<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
 re><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
						<?php endfor; endif; ?>
					</select>
					<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="parent_id_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				</td>

				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
	                 	<td>
	                    	<input name="parent_name" id="parent_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px;background-color:#CCC " readonly>
	                    	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
	                    <td>
					 	
					</tr>
	              </table>
	            </td>

			<?php else: ?>
				<td width="20%" class="dvtCellLabel" align=right>
					<select class="small" name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parentid.value=""'>
						<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
							<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
						<?php endfor; endif; ?>
					</select>
					<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="parent_id_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				</td>

				<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    	<input name="parent_name" id="parent_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px;">
                    	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                    <td>
	                <?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
						<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php else: ?>
						<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&popuptype=specific&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php endif; ?>
					</td>
                <td>
                <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parentid.value=''; this.form.parent_name.value='';
                if(typeof(this.form.fullname) != 'undefined' )this.form.fullname.value='';
                if(typeof(this.form.email) != 'undefined' )this.form.email.value='';
                if(typeof(this.form.phone) != 'undefined' )this.form.phone.value='';
                if(typeof(this.form.customer_name) != 'undefined' )this.form.customer_name.value='';
                if(typeof(this.form.taxid_no) != 'undefined' )this.form.taxid_no.value='';
                if(typeof(this.form.mobile) != 'undefined' )this.form.mobile.value='';
                if(typeof(this.form.address) != 'undefined' )this.form.address.value='';
                if(typeof(this.form.street) != 'undefined' )this.form.street.value='';
                if(typeof(this.form.sub_district) != 'undefined' )this.form.sub_district.value='';
                if(typeof(this.form.district) != 'undefined' )this.form.district.value='';
                if(typeof(this.form.province) != 'undefined' )this.form.province.value='';
                if(typeof(this.form.postal_code) != 'undefined' )this.form.postal_code.value='';
                return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
				</td>
			<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 800): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectAccounts1("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 801): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id1("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 802): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id2("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 803): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id3("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 804): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id4("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 805): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id5("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 806): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id6("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 807): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id7("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 808): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id8("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 809): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id9("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 810): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id10("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 811): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id11("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 812): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id12("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 813): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id13("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 814): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id14("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 815): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id15("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 816): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id16("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 817): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id17("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 818): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id18("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 819): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id19("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 820): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id20("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 821): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id21("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 822): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id22("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 823): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id23("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 824): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id24("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 825): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id25("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 826): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact_id26("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

		<?php elseif ($this->_tpl_vars['uitype'] == 900): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="salesinvoice_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesinvoice&action=Popup&html=Popup_picker&form=SalesinvoiceEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td>
                    	<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.salesinvoice_no.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 901): ?>
		    <td width="20%" class="dvtCellLabel" align=right>
			  <font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

			</td>
			<td width="30%" align=left class="dvtCellInfo">
               <select class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
">
               <OPTION value=" " ></OPTION>
                <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['itemname']):
?>
                	<?php if ($this->_tpl_vars['itemname']['product_select'] == $this->_tpl_vars['itemname']['productname']): ?>
                   		<?php $this->assign('item_selected', 'selected'); ?>
               		<?php else: ?>
                    	<?php $this->assign('item_selected', ""); ?>
               		<?php endif; ?>
                    <OPTION value="<?php echo $this->_tpl_vars['itemname']['productname']; ?>
"<?php echo $this->_tpl_vars['item_selected']; ?>
 ><?php echo $this->_tpl_vars['itemname']['productname']; ?>
</OPTION>
                <?php endforeach; endif; unset($_from); ?>
                </select>
			</td>

        <?php elseif ($this->_tpl_vars['uitype'] == 902): ?>
        	<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

			</td>
			<td width="30%" align=left class="dvtCellInfo">
                <select class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" style="width:250px">
                <OPTION value=" " ></OPTION>
                <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['accountname']):
?>
                    <?php if ($this->_tpl_vars['accountname']['account_select'] == $this->_tpl_vars['accountname']['accountname']): ?>
                   		<?php $this->assign('account_selected', 'selected'); ?>
               		<?php else: ?>
                    	<?php $this->assign('account_selected', ""); ?>
               		<?php endif; ?>
                    <OPTION value="<?php echo $this->_tpl_vars['accountname']['accountname']; ?>
" <?php echo $this->_tpl_vars['account_selected']; ?>
><?php echo $this->_tpl_vars['accountname']['accountname']; ?>
</OPTION>
                <?php endforeach; endif; unset($_from); ?>
                </select>
			</td>

        <?php elseif ($this->_tpl_vars['uitype'] == 903): ?>
        	<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

			</td>
			<td width="30%" align=left class="dvtCellInfo">
                <select class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" style="width:250px">
                <option value=""><?php echo $this->_tpl_vars['APP']['LBL_NONE']; ?>
</option>
                <?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['username']):
?>
                    <?php if ($this->_tpl_vars['username']['user_select'] == $this->_tpl_vars['username']['username']): ?>
                   		<?php $this->assign('user_select', 'selected'); ?>
               		<?php else: ?>
                    	<?php $this->assign('user_select', ""); ?>
               		<?php endif; ?>
                    <OPTION value="<?php echo $this->_tpl_vars['username']['username']; ?>
" <?php echo $this->_tpl_vars['user_select']; ?>
><?php echo $this->_tpl_vars['username']['username']; ?>
</OPTION>
                <?php endforeach; endif; unset($_from); ?>
                </select>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 904): ?>
			
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="projects_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Projects&action=Popup&html=Popup_picker&popuptype=specific_case&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.projects_name.value='',this.form.projects_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 912): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"   id="product_name1" name="product_name1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px"  ><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific_case&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.product_id1.value=''; this.form.product_name1.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 914): ?>
		
			<td width="20%" class="dvtCellLabel" align=right>
				<select class="small" name="parent_types" onChange='document.EditView.event_name.value=""; document.EditView.event_id.value=""'>
					<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
						<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
					<?php endfor; endif; ?>
				</select>
				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="parent_id_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><input name="event_name" id="event_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px;">
                    	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                    <td>
	                <?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
						<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_types.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php else: ?>
						<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_types.value +"&action=Popup&html=Popup_picker&popuptype=specific&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php endif; ?>
					</td>
                <td>
                <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.event_id.value=''; this.form.event_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 921): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
					<td><input name="prevacc_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=prevaccount&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.prevaccid.value=''; this.form.prevacc_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 922): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<select class="small" name="parent_type" onChange='document.EditView.ref_name.value=""; document.EditView.refid.value=""'>
					<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
						<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
					<?php endfor; endif; ?>
				</select>
				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="parent_id_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"   id="ref_name" name="ref_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" ><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                    <td>
                <?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&popuptype=specific_refcode&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php else: ?>
					<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&popuptype=specific_refcode&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php endif; ?></td>
                    <td>
                <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.refid.value=''; this.form.ref_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 923 || $this->_tpl_vars['uitype'] == 924): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			<?php if ($this->_tpl_vars['fldvalue'] != ''): ?>

			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><br/><?php endif; ?>
			<?php endif; ?>
				<input type="text" name="telephonecountrycode" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:28%;" value= "<?php echo $this->_tpl_vars['secondvalue']; ?>
" >
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "<?php echo $this->_tpl_vars['fldvalue']; ?>
" >
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 925 || $this->_tpl_vars['uitype'] == 926): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			<?php if ($this->_tpl_vars['fldvalue'] != ''): ?>

			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><br /><?php endif; ?>
			<?php endif; ?>
				<input type="text" name="mobilecountrycode" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:28%;" value= "<?php echo $this->_tpl_vars['secondvalue']; ?>
" >
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "<?php echo $this->_tpl_vars['fldvalue']; ?>
" >
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 927 || $this->_tpl_vars['uitype'] == 928): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			<?php if ($this->_tpl_vars['fldvalue'] != ''): ?>

			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><br /><?php endif; ?>
			<?php endif; ?>
				<input type="text" name="faxcountrycode" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:28%;" value= "<?php echo $this->_tpl_vars['secondvalue']; ?>
" >
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "<?php echo $this->_tpl_vars['fldvalue']; ?>
" >
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 929): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"   id="<?php echo $this->_tpl_vars['fldname']; ?>
_name" name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" ><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=accountfield&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 930): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=accountcode&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 931): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact1("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 933): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">

			<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
				<input type="time" id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="width:80px;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class="easyui-timespinner">
			<?php else: ?>
				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="time" id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="width:80px;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class="easyui-timespinner">
				<?php else: ?>
					<?php if ($this->_tpl_vars['MODULE'] == 'Calendar' && ( $this->_tpl_vars['fldname'] == 'time_start' || $this->_tpl_vars['fldname'] == 'time_end' ) && $this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['flag_send_report'] == 1): ?>
						<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="width:80px; background-color: #CCC" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" readonly="readonly" class="easyui-timespinner">
						<style type="text/css">
							<?php echo '
							.validatebox-text.validatebox-readonly{
								background-color: #CCC !important;
							}
							'; ?>

						</style>
					<?php elseif (( $this->_tpl_vars['fldname'] == 'case_time' || $this->_tpl_vars['fldname'] == 'execution_time' || $this->_tpl_vars['fldname'] == 'time_completed' || $this->_tpl_vars['fldname'] == 'time_incomplete' || $this->_tpl_vars['fldname'] == 'time_cancelled' || $this->_tpl_vars['fldname'] == 'closed_time' ) && $this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
						<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="width:80px; background-color: #CCC" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" readonly="readonly" class="easyui-timespinner">
					<?php else: ?>
						<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="width:80px;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" autocomplete="on" class="easyui-timespinner" data-options="showSeconds:false,editable:true">
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>

			</td>

        <?php elseif ($this->_tpl_vars['uitype'] == 934): ?>
        	<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact2("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
","<?php echo $this->_tpl_vars['fldname']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <!-- <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=contactcode&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td> -->
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>
			<!-- <td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">

             	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"   id="projects_name" name="projects_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" ><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Projects&action=Popup&html=Popup_picker&popuptype=specific&form=EditView&form_submit=false&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.projects_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
            </td> -->

	    <?php elseif ($this->_tpl_vars['uitype'] == 935): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="serial_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectSerial("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <!-- onclick='return window.open("index.php?module=Serial&action=Popup&html=Popup_picker&popuptype=specific&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' -->
                    <!-- onclick='selectContact1("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
")'  -->
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.serial_name.value=''; this.form.serialid.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

	    <?php elseif ($this->_tpl_vars['uitype'] == 936): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	             <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                <td><input name="sparepart_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>

	                <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Sparepart&action=Popup&html=Popup_picker&popuptype=specific&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>

	                <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.sparepartname.value='' ; this.form.sparepart_no.value='';
	               	if(typeof(this.form.sparepartlist_name) != 'undefined' )this.form.sparepartlist_name.value='';
	               	if(typeof(this.form.spare_part_no_accounting) != 'undefined' )this.form.spare_part_no_accounting.value='';
	                this.form.sparepartid.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
	          </table>
	        </td>

	    <?php elseif ($this->_tpl_vars['uitype'] == 937): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	             <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                <td><input name="errors_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>

	                <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Errors&action=Popup&html=Popup_picker&popuptype=specific&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>

	                <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.error_name.value='' ; this.form.errors_no.value=''; this.form.errorsid.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
	          </table>
	        </td>

	    <?php elseif ($this->_tpl_vars['uitype'] == 938): ?>
				<td width="20%" class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				</td>
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                    <td><input name="job_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
	                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Job&action=Popup&html=Popup_picker&popuptype=specific&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
	                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.job_no.value=''; this.form.jobid.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

					</tr>
	              </table>
	            </td>

	    <?php elseif ($this->_tpl_vars['uitype'] == 939): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="ticket_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.ticket_no.value=''; this.form.ticketid.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

		<?php elseif ($this->_tpl_vars['uitype'] == 910): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<?php if ($this->_tpl_vars['MODULE'] == 'Questionnaire'): ?>
				 		<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    	<td><input name="questionnairetemplate_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:455px; background-color: #CCC;" readonly=""></td>
				 	<?php else: ?>
					 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                    <td><input name="questionnairetemplate_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
	                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&form=QuestionnairetemplateEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                    </td>
	                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.questionnairetemplate_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <?php endif; ?>
				</tr>
              </table>
            </td>
        <?php elseif ($this->_tpl_vars['uitype'] == 941): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="plant_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Plant&action=Popup&html=Popup_picker&form=PlantEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.plant_no.value=''; this.form.plantid.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 943): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="lead_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Leads&action=Popup&html=Popup_picker&form=LeadsEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.lead_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 944): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="activitytype" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.activitytype.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

		<?php elseif ($this->_tpl_vars['uitype'] == 963): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	             <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                <td><input name="inspectiontemplate_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:290px;"></td>
	                <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Inspectiontemplate&action=Popup&html=Popup_picker&popuptype=specific&field=<?php echo $this->_tpl_vars['fldname']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
	                <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.inspectiontemplate_name.value=''; this.form.inspectiontemplateid.value='',this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
	          </table>
	        </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 301): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<?php if ($this->_tpl_vars['MODULE'] == 'Leads'): ?>
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                    <td><input name="deal_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;background-color:#CCC " readonly ></td>
					</tr>
	              </table>
	            </td>
	        <?php elseif ($this->_tpl_vars['MODULE'] == 'Calendar' && $this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['flag_send_report'] == 1): ?>
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                    <td><input name="deal_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;background-color:#CCC " readonly ></td>
					</tr>
	              </table>
	            </td>
			<?php elseif ($this->_tpl_vars['MODULE'] == 'Quotes' && $this->_tpl_vars['fldname'] == 'dealid'): ?>
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                    <td><input name="deal_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;background-color:#CCC " readonly ></td>
					</tr>
	              </table>
	            </td>
			<?php else: ?>
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                    <td><input name="deal_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
	                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Deal&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                    </td>
	                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.deal_no.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

					</tr>
	              </table>
	            </td>
			<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 302): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="competitor_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Competitor&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td>
                    	<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.competitor_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 303): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="promotionvoucher_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotionvoucher&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td>
                    	<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.promotionvoucher_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 304): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="promotion_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotion&action=Popup&html=Popup_picker&form=PromotionEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.promotion_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>
        <?php elseif ($this->_tpl_vars['uitype'] == 305): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="salesorder_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesorder&action=Popup&html=Popup_picker&form=PromotionEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.salesorder_no.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 306): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="premuimproduct_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Premuimproduct&action=Popup&html=Popup_picker&form=PromotionEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.premuimproduct_no.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 307): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="quote_no" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&form=QuotesEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.quote_no.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 308): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="servicerequest_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Servicerequest&action=Popup&html=Popup_picker&form=ServicerequestEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.servicerequest_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 309): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="ticket_title" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.ticket_title.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 310): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="activity_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.activity_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 946): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<?php if ($this->_tpl_vars['MODULE'] == 'Questionnaire'): ?>
				 		<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    	<td><input name="questionnairetemplate_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:455px; background-color: #CCC;" readonly=""></td>
				 	<?php else: ?>
					 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
	                    <td><input name="questionnairetemplate_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
	                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&form=QuestionnairetemplateEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                    </td>
	                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.questionnairetemplate_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <?php endif; ?>
				</tr>
              </table>
            </td>

        <?php elseif ($this->_tpl_vars['uitype'] == 947): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    <td><input name="questionnaire_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnaire&action=Popup&html=Popup_picker&form=QuestionnaireEditView&popuptype=specific&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.questionnaire_name.value=''; this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>
        <?php elseif ($this->_tpl_vars['uitype'] == 948): ?>

        <!-- <td colspan="2" class="dvtCellInfo" align=right></td> -->
    	</tr>

    	<tr style="height:25px">
	        <td width=20% class="dvtCellLabel" align=right><font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?></td>
	        <td colspan="4" align=left class="dvtCellInfo">
	            <input type="hidden" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
	            <iframe class="mapview" id="mapview" style="width: 100%; height: 250px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
	        </td>
        </tr>

        <?php elseif ($this->_tpl_vars['uitype'] == 949): ?>

        	<td width=20% class="dvtCellLabel" align=right><font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?></td>
        	<td colspan="3" align=left class="dvtCellInfo">
            <input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
        
        <?php elseif ($this->_tpl_vars['uitype'] == 950): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 951): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 952): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 953): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 954): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 955): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 956): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 957): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 958): ?>
        <?php elseif ($this->_tpl_vars['uitype'] == 959): ?>

        <?php elseif ($this->_tpl_vars['uitype'] == 997): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  >
				<?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">

				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);validate_size(this);" />
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
				<input type="hidden" name="id" value=""/>
				<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
					   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
						<div align="center">
							<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]
							<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
						</div>
				   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
				   	   <?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
				
			</td>

	    <?php elseif ($this->_tpl_vars['uitype'] == 998): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  >
				<?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODULE'] == 'Job' && $this->_tpl_vars['fldname'] == 'image_customer'): ?>

					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);validate_size(this); " value="" />
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
					<input type="hidden" name="id" value=""/>

					<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>

				<?php else: ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" />
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
					<input type="hidden" name="id" value=""/>
					<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != "" && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						<div id="replaceimage">[<?php echo $this->_tpl_vars['maindata'][3]['0']['orgname']; ?>
] <a href="javascript:;" onClick="delimage(<?php echo $this->_tpl_vars['ID']; ?>
)">Del</a></div>
					<?php endif; ?>
				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 999): ?>
			<td width="20%" class="dvtCellLabel" align=right>
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  >
				<?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">

				<?php if ($this->_tpl_vars['MODULE'] == 'Job' && $this->_tpl_vars['fldname'] == 'image_user'): ?>

					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);validate_size(this); " value="" />
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
					<input type="hidden" name="id" value=""/>

					<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 53): ?> <!--Assigned To -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
					<?php $this->assign('check', 1); ?>
						<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
							<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
								<?php if ($this->_tpl_vars['value'] != ''): ?>
									<?php $this->assign('check', $this->_tpl_vars['check']*0); ?>
								<?php else: ?>
									<?php $this->assign('check', $this->_tpl_vars['check']*1); ?>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						<?php endforeach; endif; unset($_from); ?>
						<?php if ($this->_tpl_vars['check'] == 0): ?>
							<?php $this->assign('select_user', 'checked'); ?>
							<?php $this->assign('style_user', 'display:block'); ?>
							<?php $this->assign('style_group', 'display:none'); ?>
						<?php else: ?>
							<?php $this->assign('select_group', 'checked'); ?>
							<?php $this->assign('style_user', 'display:none'); ?>
							<?php $this->assign('style_group', 'display:block'); ?>
						<?php endif; ?>

						<input type="radio" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="assigntype" <?php echo $this->_tpl_vars['select_user']; ?>
 value="U" onclick="toggleAssignType(this.value)" >&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>


						<?php if ($this->_tpl_vars['secondvalue'] != ''): ?>
							<input type="radio" name="assigntype" <?php echo $this->_tpl_vars['select_group']; ?>
 value="T" onclick="toggleAssignType(this.value)">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP']; ?>

						<?php endif; ?>

						<span id="assign_user" style="<?php echo $this->_tpl_vars['style_user']; ?>
">


							<select name="assigned_user_id" class="small">
								<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
									<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
										
										<option value="<?php echo $this->_tpl_vars['key_one']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
								<?php endforeach; endif; unset($_from); ?>
							</select>
						</span>

						<?php if ($this->_tpl_vars['secondvalue'] != ''): ?>
							<span id="assign_team" style="<?php echo $this->_tpl_vars['style_group']; ?>
">
								<select name="assigned_group_id" class="small">';
									<?php $_from = $this->_tpl_vars['secondvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
										<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
											
											<option value="<?php echo $this->_tpl_vars['key_one']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
									<?php endforeach; endif; unset($_from); ?>
								</select>
							</span>
						<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 52 || $this->_tpl_vars['uitype'] == 77): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['uitype'] == 52): ?>
					<select name="assigned_user_id" class="small">
				<?php elseif ($this->_tpl_vars['uitype'] == 77): ?>
					<select name="assigned_user_id1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php else: ?>
					<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
				<?php endif; ?>

				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key_one'] => $this->_tpl_vars['arr']):
?>
					<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
						<option value="<?php echo $this->_tpl_vars['key_one']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 51): ?>

			<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
				<?php $this->assign('popuptype', 'specific_account_address'); ?>
			<?php else: ?>
				<?php $this->assign('popuptype', 'specific_contact_account_address'); ?>
			<?php endif; ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="100%">
                    <input name="account_name" readonly id="account_name" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
                    <input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                    <td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=<?php echo $this->_tpl_vars['popuptype']; ?>
&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&recordid=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 50): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"   id="account_name" name="account_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" ><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                    <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 73): ?><!-- Account Name -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">

                    <?php if ($this->_tpl_vars['MODULE'] == 'Leads'): ?>
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td>
                           	<input name="account_name" readonly id="single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;background-color:#CCC;">
                           	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                         </tr>
                       </table>
                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Projects'): ?>
                      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			                        <tr>
			                            <td>
			                            	<input name="account_name" readonly id="single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px; background-color: #ccc">
		                          			<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
		                          		</td>
			                        </tr>
		                        </table>

                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Order'): ?>
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	                        <tr>
	                            <td>
	                            	<input name="account_name" readonly id="single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:440px;">
	                            	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
	                        	</td>
	                        	<td>
	                        		<img src="<?php echo aicrm_imageurl('plus_new.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
" LANGUAGE=javascript onclick='Quickcreate("Accounts","<?php echo $this->_tpl_vars['MODULE']; ?>
");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                        	</td>
	                            <td>
	                            	<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectAccount("false","general",document.EditView)' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                            </td>
	                        	<td>
	                        		<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['fldname']; ?>
.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
	                        	</td>
	                        </tr>
                        </table>

                    <?php else: ?>

                    	<?php if ($this->_tpl_vars['flagaccount'] == true && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
	                      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                        <tr>
									<td>
			                          	<input name="account_name" readonly id="single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px; background-color: #ccc">
			                          	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
			                        </td>
		                        </tr>
	                        </table>
                        <?php else: ?>
	                        <?php if ($this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
		                       	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			                        <tr>
			                            <td>
			                            	<input name="account_name" readonly id="single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px; background-color: #ccc">
		                          			<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
		                          		</td>
			                        </tr>
		                        </table>
							<?php elseif ($this->_tpl_vars['MODULE'] == 'Quotes' && $this->_tpl_vars['fldname'] == 'designerid'): ?>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
				                  	<tr>
				                    	<td width="100%">
				                    		<input name="designer_name" readonly id="single_designerid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
				                    		<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
				                    	</td>
				                    	<td>
				                    		<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_field=<?php echo $this->_tpl_vars['fldname']; ?>
","test","left=500,width=1000,height=602,top=250,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                    	<td>
				                    		<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.designerid.value=''; this.form.designer_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                  	</tr>
				                </table>
							<?php elseif ($this->_tpl_vars['MODULE'] == 'Quotes' && $this->_tpl_vars['fldname'] == 'account_id'): ?>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
				                  	<tr>
				                    	<td width="100%">
				                    		<input name="account_name" readonly id="single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
				                    		<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
				                    	</td>
				                    	<td>
				                    		<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
","test","left=500,width=1000,height=602,top=250,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                    	<td>
				                    		<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="
											this.form.account_id.value=''; 
											this.form.account_name.value='';
											this.form.mobile.value='';
											this.form.taxid_no.value='';
											this.form.email.value='';
											this.form.village.value='';
											this.form.room_no.value='';
											this.form.address_no.value='';
											this.form.village_no.value='';
											this.form.street.value='';
											this.form.lane.value='';
											this.form.sub_district.value='';
											this.form.district.value='';
											this.form.province.value='';
											this.form.postal_code.value='';
											return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                  	</tr>
				                </table>
	                        <?php else: ?>
	                        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
				                  	<tr>
				                    	<td width="100%">
				                    		<input name="account_name" readonly id="single_accountid" type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
				                    		<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
				                    	</td>
				                    	<td>
				                    		<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
","test","left=500,width=1000,height=602,top=250,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                    	<td>
				                    		<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                  	</tr>
				                </table>

	                        <?php endif; ?>

                        <?php endif; ?>
                    <?php endif; ?>


            </td>

		<?php elseif ($this->_tpl_vars['uitype'] == 75 || $this->_tpl_vars['uitype'] == 81): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<?php if ($this->_tpl_vars['uitype'] == 81): ?>
					<?php $this->assign('pop_type', 'specific_vendor_address'); ?>
					<?php else: ?><?php $this->assign('pop_type', 'specific'); ?>
				<?php endif; ?>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype=<?php echo $this->_tpl_vars['pop_type']; ?>
&form=EditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 57): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                        <input name="contact_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'">
                        <input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                        <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact("false","general",document.EditView)' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                        <td><input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                      </tr>
                    </table>
                <?php elseif ($this->_tpl_vars['MODULE'] == 'Leads'): ?>
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td>
                           	<input name="contact_name" readonly type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px;background-color: #ccc">
                            	<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
                         </tr>
                       </table>
				<?php else: ?>

                	<?php if ($this->_tpl_vars['flagcontact'] == true && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>
                            	<input name="contact_name" readonly type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px;background-color: #ccc">
                            	<input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
                            </td>
                          </tr>
                        </table>
                	<?php else: ?>
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>
                            <input name="contact_name" readonly type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" onBlur="this.className='detailedViewTextBox'" style="width:450px;">
                            <input id="<?php echo $this->_tpl_vars['fldname']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                            <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectContact("false","general",document.EditView,"<?php echo $this->_tpl_vars['MODULE']; ?>
")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                            <td><input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                          </tr>
                        </table>
                    <?php endif; ?>

				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 58): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
                 	<tr>
				 		<td><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" ></td>
                    	<td><input name="campaign_name" readonly type="text" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:450px;"></td>
                    	<td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaigns&action=Popup&html=Popup_picker&popuptype=specific_campaign&form=EditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    	<td><input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.campaignid.value=''; this.form.campaign_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
					</tr>
              	</table>
			</td>


		<?php elseif ($this->_tpl_vars['uitype'] == 80): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectSalesOrder();' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 78): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" >&nbsp;<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectQuote()' align="absmiddle" style='cursor:hand;cursor:pointer' >&nbsp;<input type="image" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 76): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">&nbsp;<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='selectPotential()' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 17): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				&nbsp;&nbsp;http://
			<input style="width:74%;" class = 'detailedViewTextBox' type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" onkeyup="validateUrl('<?php echo $this->_tpl_vars['fldname']; ?>
');" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 85): ?>
            <td width="20%" class="dvtCellLabel" align=right>
                <font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font>
                <?php echo $this->_tpl_vars['usefldlabel']; ?>

                <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
                	<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" >
                <?php endif; ?>
            </td>
            <td width="30%" align=left class="dvtCellInfo">
				<img src="<?php echo aicrm_imageurl('skype.gif', $this->_tpl_vars['THEME']); ?>
" alt="Skype" title="Skype" LANGUAGE=javascript align="absmiddle"></img>
				<input class='detailedViewTextBox' type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
            </td>

		<?php elseif ($this->_tpl_vars['uitype'] == 71 || $this->_tpl_vars['uitype'] == 72): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['fldname'] == 'unit_price' && $this->_tpl_vars['fromlink'] != 'qcreate'): ?>
					<span id="multiple_currencies">
                    <?php if ($this->_tpl_vars['fldname'] == 'unit_price'): ?>
                        <input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'; updateUnitPrice('unit_price', '<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
');"  readonly="readonly" style="background-color: #CCC; width:60%;">
                    <?php else: ?>
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'; updateUnitPrice('unit_price', '<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
');"  value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="width:60%;">
					<?php endif; ?>

                    <?php if ($this->_tpl_vars['MASS_EDIT'] != 1 && $this->_tpl_vars['MODE'] == 'Products'): ?>
						&nbsp;<a href="javascript:void(0);" onclick="updateUnitPrice('unit_price', '<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
'); toggleShowHide('currency_class','multiple_currencies');"><?php echo $this->_tpl_vars['APP']['LBL_MORE_CURRENCIES']; ?>
 &raquo;</a>
					<?php endif; ?>
					</span>
					<?php if ($this->_tpl_vars['MASS_EDIT'] != 1): ?>
					<div id="currency_class" class="multiCurrencyEditUI" width="350">
						<input type="hidden" name="base_currency" id="base_currency" value="<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
" />
						<input type="hidden" name="base_conversion_rate" id="base_currency" value="<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
" />
						<table width="100%" height="100%" class="small" cellpadding="5">
						<tr class="detailedViewHeader">
							<th colspan="4">
								<b><?php echo $this->_tpl_vars['MOD']['LBL_PRODUCT_PRICES']; ?>
</b>
							</th>
							<th align="right">
								<img border="0" style="cursor: pointer;" onclick="toggleShowHide('multiple_currencies','currency_class');" src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
"/>
							</th>
						</tr>
						<tr class="detailedViewHeader">
							<th><?php echo $this->_tpl_vars['APP']['LBL_CURRENCY']; ?>
</th>
							<th><?php echo $this->_tpl_vars['APP']['LBL_PRICE']; ?>
</th>
							<th><?php echo $this->_tpl_vars['APP']['LBL_CONVERSION_RATE']; ?>
</th>
							<th><?php echo $this->_tpl_vars['APP']['LBL_RESET_PRICE']; ?>
</th>
							<th><?php echo $this->_tpl_vars['APP']['LBL_BASE_CURRENCY']; ?>
</th>
						</tr>
						<?php $_from = $this->_tpl_vars['PRICE_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['price']):
?>
							<tr>
								<?php if ($this->_tpl_vars['price']['check_value'] == 1 || $this->_tpl_vars['price']['is_basecurrency'] == 1): ?>
									<?php $this->assign('check_value', 'checked'); ?>
									<?php $this->assign('disable_value', ""); ?>
								<?php else: ?>
									<?php $this->assign('check_value', ""); ?>
									<?php $this->assign('disable_value', "disabled=true"); ?>
								<?php endif; ?>

								<?php if ($this->_tpl_vars['price']['is_basecurrency'] == 1): ?>
									<?php $this->assign('base_cur_check', 'checked'); ?>
								<?php else: ?>
									<?php $this->assign('base_cur_check', ""); ?>
								<?php endif; ?>

								<?php if ($this->_tpl_vars['price']['curname'] == $this->_tpl_vars['BASE_CURRENCY']): ?>
									<?php $this->assign('call_js_update_func', "updateUnitPrice('".($this->_tpl_vars['BASE_CURRENCY'])."', 'unit_price');"); ?>
								<?php else: ?>
									<?php $this->assign('call_js_update_func', ""); ?>
								<?php endif; ?>

								<td align="right" class="dvtCellLabel">
									<?php echo getTranslatedCurrencyString($this->_tpl_vars['price']['currencylabel']); ?>
 (<?php echo $this->_tpl_vars['price']['currencysymbol']; ?>
)
									<input type="checkbox" name="cur_<?php echo $this->_tpl_vars['price']['curid']; ?>
_check" id="cur_<?php echo $this->_tpl_vars['price']['curid']; ?>
_check" class="small" onclick="fnenableDisable(this,'<?php echo $this->_tpl_vars['price']['curid']; ?>
'); updateCurrencyValue(this,'<?php echo $this->_tpl_vars['price']['curname']; ?>
','<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
','<?php echo $this->_tpl_vars['price']['conversionrate']; ?>
');" <?php echo $this->_tpl_vars['check_value']; ?>
>
								</td>
								<td class="dvtCellInfo" align="left">
									<input <?php echo $this->_tpl_vars['disable_value']; ?>
 type="text" size="10" class="small" name="<?php echo $this->_tpl_vars['price']['curname']; ?>
" id="<?php echo $this->_tpl_vars['price']['curname']; ?>
" value="<?php echo $this->_tpl_vars['price']['curvalue']; ?>
" onBlur="<?php echo $this->_tpl_vars['call_js_update_func']; ?>
 fnpriceValidation('<?php echo $this->_tpl_vars['price']['curname']; ?>
');">
								</td>
								<td class="dvtCellInfo" align="left">
									<input disabled=true type="text" size="10" class="small" name="cur_conv_rate<?php echo $this->_tpl_vars['price']['curid']; ?>
" value="<?php echo $this->_tpl_vars['price']['conversionrate']; ?>
">
								</td>
								<td class="dvtCellInfo" align="center">
									<input <?php echo $this->_tpl_vars['disable_value']; ?>
 type="button" class="crmbutton small edit" id="cur_reset<?php echo $this->_tpl_vars['price']['curid']; ?>
"  onclick="updateCurrencyValue(this,'<?php echo $this->_tpl_vars['price']['curname']; ?>
','<?php echo $this->_tpl_vars['BASE_CURRENCY']; ?>
','<?php echo $this->_tpl_vars['price']['conversionrate']; ?>
');" value="<?php echo $this->_tpl_vars['APP']['LBL_RESET']; ?>
"/>
								</td>
								<td class="dvtCellInfo">
									<input <?php echo $this->_tpl_vars['disable_value']; ?>
 type="radio" class="detailedViewTextBox no-spinners" id="base_currency<?php echo $this->_tpl_vars['price']['curid']; ?>
" name="base_currency_input" value="<?php echo $this->_tpl_vars['price']['curname']; ?>
" <?php echo $this->_tpl_vars['base_cur_check']; ?>
 onchange="updateBaseCurrencyValue()" />
								</td>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
						</table>
					</div>
					<?php endif; ?>
				<?php else: ?>

					<?php if ($this->_tpl_vars['MODE'] == 'edit'): ?>
						<div class="hide-inputbtns">
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="number" step="0.001" class="form-control detailedViewTextBox <?php echo $this->_tpl_vars['fldname']; ?>
_field" onFocus="this.className='detailedViewTextBox'" onBlur="this.className='detailedViewTextBox'" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" >
						</div>
					<?php else: ?>
						<div class="hide-inputbtns">
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="number" step="0.001" class="form-control detailedViewTextBox <?php echo $this->_tpl_vars['fldname']; ?>
_field" onFocus="this.className='detailedViewTextBox'" onBlur="this.className='detailedViewTextBox'" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 56): ?><!-- Check Box -->
            <?php 
                global  $chk_close;
             ?>

			<?php if ($this->_tpl_vars['fldname'] == 'passed_inspection' && $this->_tpl_vars['MODULE'] == 'Accounts' && $this->_tpl_vars['HIDDEN_FIELDS'] == 0): ?>

			<?php else: ?>
				<td width="20%" class="dvtCellLabel" align=right>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				</td>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['fldname'] == 'notime' && $this->_tpl_vars['ACTIVITY_MODE'] == 'Events'): ?>
				<?php if ($this->_tpl_vars['fldvalue'] == 1): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onclick="toggleTime()" checked>
					</td>
				<?php else: ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" onclick="toggleTime()" >
					</td>
				<?php endif; ?>

            <?php elseif ($this->_tpl_vars['fldname'] == 'email_setup' || $this->_tpl_vars['fldname'] == 'email_send' || $this->_tpl_vars['fldname'] == 'send_sms' || $this->_tpl_vars['fldname'] == 'setup_sms'): ?>
            	<td width="30%" align=left class="dvtCellInfo">
                    <input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" style="background-color: #CCC; width:50px"class=detailedViewTextBox  onBlur="this.className='detailedViewTextBox';" readonly="readonly">
			<?php elseif (( $this->_tpl_vars['fldname'] == 'flag_erp_response_status' || $this->_tpl_vars['fldname'] == 'flag_projects' )): ?>

			    <?php if ($this->_tpl_vars['fldvalue'] == 1): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" checked style="pointer-events: none; background-color: #CCC !important; accent-color: #CCC;" readonly="readonly">
					</td>
				<?php elseif ($this->_tpl_vars['fldname'] == 'filestatus' && $this->_tpl_vars['MODE'] == 'create'): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" checked style="pointer-events: none; background-color: #CCC !important; accent-color: #CCC;" readonly="readonly">
					</td>
				<?php else: ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" <?php if (( $this->_tpl_vars['PROD_MODE'] == 'create' && ((is_array($_tmp=$this->_tpl_vars['fldname'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)) != 'cf_' ) || ( ((is_array($_tmp=$this->_tpl_vars['fldname'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)) != 'cf_' && $this->_tpl_vars['PRICE_BOOK_MODE'] == 'create' ) || $this->_tpl_vars['USER_MODE'] == 'create'): ?><?php endif; ?> style="pointer-events: none; background-color: #CCC !important; accent-color: #CCC;" readonly="readonly">
					</td>
				<?php endif; ?>
    
			<?php elseif ($this->_tpl_vars['fldname'] == 'approve_level1' || $this->_tpl_vars['fldname'] == 'approve_level2' || $this->_tpl_vars['fldname'] == 'approve_level3' || $this->_tpl_vars['fldname'] == 'approve_level4'): ?>
					<td width="30%" align=left class="dvtCellInfo">
                    <?php if ($this->_tpl_vars['fldvalue'] == 1): ?>
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onclick="calcGrandTotal()" checked>
					<?php else: ?>
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onclick="calcGrandTotal()"  >
                    <?php endif; ?>
                    </td>
			<?php elseif ($this->_tpl_vars['fldname'] == 'passed_inspection' && $this->_tpl_vars['MODULE'] == 'Accounts' && $this->_tpl_vars['HIDDEN_FIELDS'] == 0): ?>
				<input type="hidden" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
">

			<?php else: ?>
				<?php if ($this->_tpl_vars['fldvalue'] == 1): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" checked>
					</td>
				<?php elseif ($this->_tpl_vars['fldname'] == 'filestatus' && $this->_tpl_vars['MODE'] == 'create'): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="checkbox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" checked>
					</td>
				<?php else: ?>
					<td width="30%" align=left class="dvtCellInfo">
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" <?php if (( $this->_tpl_vars['PROD_MODE'] == 'create' && ((is_array($_tmp=$this->_tpl_vars['fldname'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)) != 'cf_' ) || ( ((is_array($_tmp=$this->_tpl_vars['fldname'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 3) : substr($_tmp, 0, 3)) != 'cf_' && $this->_tpl_vars['PRICE_BOOK_MODE'] == 'create' ) || $this->_tpl_vars['USER_MODE'] == 'create'): ?>checked<?php endif; ?>>
					</td>
				<?php endif; ?>
			<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 23 || $this->_tpl_vars['uitype'] == 5 || $this->_tpl_vars['uitype'] == 6): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_value'] => $this->_tpl_vars['time_value']):
?>
					<?php $this->assign('date_val', ($this->_tpl_vars['date_value'])); ?>
					<?php $this->assign('time_val', ($this->_tpl_vars['time_value'])); ?>
				<?php endforeach; endif; unset($_from); ?>
                <?php if ($this->_tpl_vars['date_val'] == '00-00-0000' || $this->_tpl_vars['date_val'] == '0000-00-00'): ?>
                	<?php $this->assign('date_val', ""); ?>
                <?php endif; ?>

				<?php if ($this->_tpl_vars['fldname'] == 'due_date' || $this->_tpl_vars['fldname'] == 'checkindate' || $this->_tpl_vars['fldname'] == 'checkoutdate' || ( $this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['flaglocation'] == true && $this->_tpl_vars['MODULE'] == 'Calendar' ) || ( $this->_tpl_vars['MODULE'] == 'Quotes' && ( $this->_tpl_vars['fldname'] == 'project_est_s_date' || $this->_tpl_vars['fldname'] == 'project_est_e_date' ) )): ?>
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"   id="jscal_field_<?php echo $this->_tpl_vars['fldname']; ?>
" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="border:1px solid #bababa;background-color: #CCC" size="11" maxlength="10" value="<?php echo $this->_tpl_vars['date_val']; ?>
"  readonly="readonly">
				<?php elseif (( $this->_tpl_vars['fldname'] == 'case_date' || $this->_tpl_vars['fldname'] == 'date_of_execution' || $this->_tpl_vars['fldname'] == 'date_completed' || $this->_tpl_vars['fldname'] == 'date_incomplete' || $this->_tpl_vars['fldname'] == 'date_cancelled' || $this->_tpl_vars['fldname'] == 'closed_date' ) && $this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  id="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="text" style="border:1px solid #bababa; background-color: #CCC" size="11" maxlength="10" readonly="readonly" value="<?php echo $this->_tpl_vars['date_val']; ?>
" >

                <?php elseif ($this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['MODULE'] == 'Calendar' && $this->_tpl_vars['MODE'] == 'edit'): ?>
                	<?php if ($this->_tpl_vars['flag_send_report'] == 1): ?>
                		<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  id="jscal_field_<?php echo $this->_tpl_vars['fldname']; ?>
"  type="text" size="11" maxlength="10" value="<?php echo $this->_tpl_vars['date_val']; ?>
" readonly="readonly" style="border:1px solid #bababa; background-color: #CCC">
                	<?php else: ?>
                		<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  id="jscal_field_<?php echo $this->_tpl_vars['fldname']; ?>
"  type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo $this->_tpl_vars['date_val']; ?>
" >
                	<?php endif; ?>
                <?php elseif ($this->_tpl_vars['fldname'] == 'approved_date'): ?>
                	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  id="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="text" style="border:1px solid #bababa; background-color: #CCC" size="11" maxlength="10" readonly="readonly" value="<?php echo $this->_tpl_vars['date_val']; ?>
" >

                <?php else: ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  id="jscal_field_<?php echo $this->_tpl_vars['fldname']; ?>
"  type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo $this->_tpl_vars['date_val']; ?>
" >

                <?php endif; ?>

                <?php if ($this->_tpl_vars['fldname'] != 'due_date'): ?>

					<?php if ($this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['flaglocation'] == true && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>

					<?php elseif (( $this->_tpl_vars['fldname'] == 'case_date' || $this->_tpl_vars['fldname'] == 'date_of_execution' || $this->_tpl_vars['fldname'] == 'date_completed' || $this->_tpl_vars['fldname'] == 'date_incomplete' || $this->_tpl_vars['fldname'] == 'date_cancelled' || $this->_tpl_vars['fldname'] == 'closed_date' ) && $this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
						<img src="<?php echo aicrm_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
" style="vertical-align: middle;">
					<?php elseif ($this->_tpl_vars['fldname'] == 'checkindate' || $this->_tpl_vars['fldname'] == 'checkoutdate' || $this->_tpl_vars['fldname'] == 'approved_date' || ( $this->_tpl_vars['MODULE'] == 'Quotes' && ( $this->_tpl_vars['fldname'] == 'project_est_s_date' || $this->_tpl_vars['fldname'] == 'project_est_e_date' ) )): ?>

					<?php elseif ($this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['MODULE'] == 'Calendar' && $this->_tpl_vars['MODE'] == 'edit'): ?>
						<?php if ($this->_tpl_vars['flag_send_report'] == 1): ?>
							<img src="<?php echo aicrm_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" id="date_start" style="vertical-align: middle;">
						<?php else: ?>
							<img src="<?php echo aicrm_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" id="jscal_trigger_<?php echo $this->_tpl_vars['fldname']; ?>
" style="vertical-align: middle;">
						<?php endif; ?>
               		<?php else: ?>
                		<img src="<?php echo aicrm_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" id="jscal_trigger_<?php echo $this->_tpl_vars['fldname']; ?>
" style="vertical-align: middle;">
					<?php endif; ?>

                <?php endif; ?>


				<?php if ($this->_tpl_vars['uitype'] == 6): ?>
					<input name="time_start" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="<?php echo $this->_tpl_vars['time_val']; ?>
">
				<?php endif; ?>

				<?php if ($this->_tpl_vars['uitype'] == 6 && $this->_tpl_vars['QCMODULE'] == 'Event'): ?>
					<input name="dateFormat" type="hidden" value="<?php echo $this->_tpl_vars['dateFormat']; ?>
">
				<?php endif; ?>
				<?php if ($this->_tpl_vars['uitype'] == 23 && $this->_tpl_vars['QCMODULE'] == 'Event'): ?>
					<input name="time_end" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="<?php echo $this->_tpl_vars['time_val']; ?>
">
				<?php endif; ?>

				<?php $_from = $this->_tpl_vars['secondvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['date_format'] => $this->_tpl_vars['date_str']):
?>
					<?php $this->assign('dateFormat', ($this->_tpl_vars['date_format'])); ?>
					<?php $this->assign('dateStr', ($this->_tpl_vars['date_str'])); ?>
				<?php endforeach; endif; unset($_from); ?>

				<?php if ($this->_tpl_vars['uitype'] == 5 || $this->_tpl_vars['uitype'] == 23): ?>
                	 <?php if ($this->_tpl_vars['fldname'] != 'due_date'): ?>
						<?php if ($this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['flaglocation'] == true && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
               			
               			<?php elseif ($this->_tpl_vars['fldname'] == 'checkindate' || $this->_tpl_vars['fldname'] == 'checkoutdate'): ?>
                        
                        <?php else: ?>
                        	<br><font size=1><em old="(yyyy-mm-dd)">(<?php echo $this->_tpl_vars['dateStr']; ?>
)</em></font>
                     	<?php endif; ?>
                     <?php endif; ?>
				<?php else: ?>
					    <br><font size=1><em old="(yyyy-mm-dd)">(<?php echo $this->_tpl_vars['dateStr']; ?>
)</em></font>
				<?php endif; ?>

          	 <?php if ($this->_tpl_vars['fldname'] != 'due_date'): ?>
          	 		<!-- && ($fldname neq 'date_start' &&  $MODULE neq 'Calendar' &&  $MODE neq 'edit') -->
                    <?php if ($this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['flaglocation'] == true && $this->_tpl_vars['MODULE'] == 'Calendar' || ( $this->_tpl_vars['flag_send_report'] == 1 )): ?>

                    <?php elseif (( $this->_tpl_vars['fldname'] == 'case_date' || $this->_tpl_vars['fldname'] == 'date_of_execution' || $this->_tpl_vars['fldname'] == 'date_completed' || $this->_tpl_vars['fldname'] == 'date_incomplete' || $this->_tpl_vars['fldname'] == 'date_cancelled' || $this->_tpl_vars['fldname'] == 'closed_date' ) && $this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>

                    <?php elseif ($this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['MODULE'] == 'Calendar' && $this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['flag_send_report'] == 1): ?>

                    <?php elseif ($this->_tpl_vars['fldname'] == 'checkindate' || $this->_tpl_vars['fldname'] == 'checkoutdate' || $this->_tpl_vars['fldname'] == 'approved_date'): ?>

                    <?php else: ?>
						<script type="text/javascript" id="massedit_calendar_<?php echo $this->_tpl_vars['fldname']; ?>
">
                            Calendar.setup ({

                                inputField : "jscal_field_<?php echo $this->_tpl_vars['fldname']; ?>
", ifFormat : "<?php echo $this->_tpl_vars['dateFormat']; ?>
", showsTime : false, button : "jscal_trigger_<?php echo $this->_tpl_vars['fldname']; ?>
", singleClick : true, step : 1 ,

                                 <?php if ($this->_tpl_vars['fldname'] == 'pricelist_startdate'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            pricelist_startdate(dateText,selectedDate);
                                            return true;
                                    }
                                <?php elseif ($this->_tpl_vars['fldname'] == 'pricelist_enddate'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            pricelist_enddate(dateText,selectedDate);
                                            return true;
                                    }

                                <?php elseif ($this->_tpl_vars['fldname'] == 'jobdate_operate'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            jobdate_operate(dateText,selectedDate);
                                            return true;
                                    }
                                <?php elseif ($this->_tpl_vars['fldname'] == 'close_date' && $this->_tpl_vars['MODULE'] != 'Job'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            close_date(dateText,selectedDate);
                                            return true;
                                    }

                                <?php elseif ($this->_tpl_vars['fldname'] == 'order_date'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            order_date(dateText,selectedDate);
                                            return true;
                                    }
                                <?php elseif ($this->_tpl_vars['fldname'] == 'date_start' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            date_start(dateText,selectedDate);
                                            return true;
                                    }
								 <?php elseif ($this->_tpl_vars['fldname'] == 'export_inv_date' && $this->_tpl_vars['MODULE'] == 'Goodsreceive'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            export_inv_date(dateText,selectedDate);
                                            return true;
                                    }
                                <?php elseif ($this->_tpl_vars['fldname'] == 'po_date' && $this->_tpl_vars['MODULE'] == 'Purchasesorder'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            po_date(dateText,selectedDate);
                                            return true;
                                    }
								<?php elseif ($this->_tpl_vars['fldname'] == 'quotation_date' && $this->_tpl_vars['MODULE'] == 'Quotes'): ?>
                                     onSelect: function(dateText,selectedDate) {
                                            quotation_date(dateText,selectedDate);
                                            return true;
                                    }
                                <?php endif; ?>

                            })
                        </script>
                    <?php endif; ?>

            <?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 63): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="text" size="2" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" >&nbsp;
				<select name="duration_minutes" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
					<?php $_from = $this->_tpl_vars['secondvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['labelval'] => $this->_tpl_vars['selectval']):
?>
						<option value="<?php echo $this->_tpl_vars['labelval']; ?>
" <?php echo $this->_tpl_vars['selectval']; ?>
><?php echo $this->_tpl_vars['labelval']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>

		<?php elseif ($this->_tpl_vars['uitype'] == 68 || $this->_tpl_vars['uitype'] == 66 || $this->_tpl_vars['uitype'] == 62): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<select class="small" name="parent_type" onChange='document.QcEditView.parent_name.value=""; document.QcEditView.parent_id.value=""'>
				<?php else: ?>
					<select class="small" name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
				<?php endif; ?>
					<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
						<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
					<?php endfor; endif; ?>
				</select>
				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="parent_id_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="<?php echo $this->_tpl_vars['fldvalue']; ?>
"   id="parentid" name="parent_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" ><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea><input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"></td>
                    <td>
	                <?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
						<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php else: ?>
						<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php endif; ?>
					</td>
                    <td>
	                <?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
						<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php else: ?>
						<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
					<?php endif; ?>
				   </td>
                  </tr>
                </table>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 357): ?>
			<td width="20%" class="dvtCellLabel" align=right>To:&nbsp;</td>
			<td width="90%" colspan="3">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
				<textarea readonly name="parent_name" cols="70" rows="2"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>&nbsp;
				<select name="parent_type" class="small">
					<?php $_from = $this->_tpl_vars['fldlabel']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['labelval'] => $this->_tpl_vars['selectval']):
?>
						<option value="<?php echo $this->_tpl_vars['labelval']; ?>
" <?php echo $this->_tpl_vars['selectval']; ?>
><?php echo $this->_tpl_vars['labelval']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>
				&nbsp;
				<?php if ($this->_tpl_vars['fromlink'] == 'qcreate'): ?>
					<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php else: ?>
					<img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				<?php endif; ?>
			</td>
		   <tr style="height:25px">
			<td width="20%" class="dvtCellLabel" align=right>CC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="ccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
			<td width="20%" class="dvtCellLabel" align=right>BCC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="bccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
		   </tr>

		<?php elseif ($this->_tpl_vars['uitype'] == 59): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                 	 <td>
                 	  <input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="product_name" id ="product_name" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px;">
                 	 	<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
">
                 	</td>

                   	                    	<td><img tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&module_return=<?php echo $this->_tpl_vars['MODULE']; ?>
&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    

                 	<?php if ($this->_tpl_vars['MODULE'] == 'Quotation'): ?>
                    	<td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; this.form.unit_size.value='';  this.form.pricesqm.value=''; get_sum_contact(); get_sum_months(); get_sum_tranfer(); return false; " align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                 	<?php else: ?>
                    	<td><input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; if(typeof(this.form.productcode) != 'undefined' )this.form.productcode.value='' ;  return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <?php endif; ?>

                  </tr>
                </table>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 55 || $this->_tpl_vars['uitype'] == 255): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1' && $this->_tpl_vars['fldvalue'] != ''): ?>
					<?php echo $this->_tpl_vars['APP']['Salutation']; ?>
<input type="checkbox" name="salutationtype_mass_edit_check" id="salutationtype_mass_edit_check" class="small" ><br />
				<?php endif; ?>

				<?php if ($this->_tpl_vars['uitype'] == 55): ?>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font> <?php echo $this->_tpl_vars['usefldlabel']; ?>

	            <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>

	            <?php elseif ($this->_tpl_vars['uitype'] == 255): ?>
					<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
				<?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			<?php if ($this->_tpl_vars['fldvalue'] != ''): ?>
			<select name="salutationtype" class="small" style="width: 20%;">
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
				<option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
				<?php echo $this->_tpl_vars['arr'][0]; ?>

				</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><br /><?php endif; ?>

			<?php endif; ?>

			<?php if (( $this->_tpl_vars['fldname'] == 'firstname' ) && ( $this->_tpl_vars['MODULE'] == 'Accounts' )): ?>
				<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_account_name();" style="width: 70%;">

			<?php elseif (( $this->_tpl_vars['fldname'] == 'lastname' ) && ( $this->_tpl_vars['MODULE'] == 'Accounts' )): ?>
				<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_account_name();">

			<?php elseif (( $this->_tpl_vars['fldname'] == 'firstname' ) && ( $this->_tpl_vars['MODULE'] == 'Leads' )): ?>
				<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_lead_name();" style="width: 70%;">

			<?php elseif (( $this->_tpl_vars['fldname'] == 'lastname' ) && ( $this->_tpl_vars['MODULE'] == 'Leads' )): ?>
				<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_lead_name();">

			<?php elseif (( $this->_tpl_vars['fldname'] == 'firstname' ) && ( $this->_tpl_vars['MODULE'] == 'Contacts' )): ?>
				<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_contact_name();" style="width: 70%;">

			<?php elseif (( $this->_tpl_vars['fldname'] == 'lastname' ) && ( $this->_tpl_vars['MODULE'] == 'Contacts' )): ?>
				<input type="text" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_contact_name();">

			<?php else: ?>

				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value= "<?php echo $this->_tpl_vars['secondvalue']; ?>
" style="width: 70%;">

			<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 22): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<textarea name="<?php echo $this->_tpl_vars['fldname']; ?>
" cols="30" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" rows="2"><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 69 && ( $this->_tpl_vars['MODULE'] != 'HelpDesk' )): ?>

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  >
				<?php endif; ?>
			</td>

			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODULE'] == 'Products'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Order'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Announcement'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_1']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)" accept="image/png, image/gif, image/jpeg"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_1']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)" accept="image/png, image/gif, image/jpeg"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Leads'): ?>

					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_1']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Questionnaire'): ?>

					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_1']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Campaigns'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Deal'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_1']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Promotion'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Promotionvoucher'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Faq'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'KnowledgeBase'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Competitorproduct'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Premuimproduct'): ?>
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

						<input id="my_file_element" type="file" name="file_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

						<?php $this->assign('image_count', 0); ?>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
							<div align="center">
								<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]<input id="file_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
							</div>
					   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
					   	   <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</div>

					<script>
												var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

												multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				<?php elseif ($this->_tpl_vars['MODULE'] == 'Job'): ?>

					<?php if ($this->_tpl_vars['fldname'] == 'imagename'): ?>
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden" id="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden" type="hidden" value="">
						<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

							<input id="my_file_element" type="file" name="<?php echo $this->_tpl_vars['fldname']; ?>
_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

							<?php $this->assign('image_count', 0); ?>
							<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
							   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
								<div align="center">
									<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]
									<input id="<?php echo $this->_tpl_vars['fldname']; ?>
_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt1("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
								</div>
						   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
						   	   <?php endforeach; endif; unset($_from); ?>
							<?php endif; ?>
						</div>

						<script>
														var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
							multi_selector.count = <?php echo $this->_tpl_vars['image_count']; ?>

														multi_selector.addElement( document.getElementById( 'my_file_element' ),'<?php echo $this->_tpl_vars['fldname']; ?>
' );
						</script>

					<?php elseif ($this->_tpl_vars['fldname'] == 'imagereceipt'): ?>

						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden" id="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="" />
						<div id="new_files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small"><?php echo $this->_tpl_vars['APP']['Files_Maximum_6']; ?>

							<input id="new_file_element" type="file" name="<?php echo $this->_tpl_vars['fldname']; ?>
_1" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
"  onchange="validateFilename(this)"/>

							<input id="temp_fieldname" type="hidden" name="temp_fieldname" value="<?php echo $this->_tpl_vars['fldname']; ?>
" >
							<?php $this->assign('image_count', 0); ?>
							<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != '' && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
							   <?php $_from = $this->_tpl_vars['maindata'][3]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['image_loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['image_loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['image_details']):
        $this->_foreach['image_loop']['iteration']++;
?>
								<div align="center">
									<img src="<?php echo $this->_tpl_vars['image_details']['path']; ?>
<?php echo $this->_tpl_vars['image_details']['name']; ?>
" height="50">&nbsp;&nbsp;[<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
]
									<input id="<?php echo $this->_tpl_vars['fldname']; ?>
_<?php echo $this->_tpl_vars['num']; ?>
" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt2("<?php echo $this->_tpl_vars['image_details']['orgname']; ?>
")'>
								</div>
						   	   <?php $this->assign('image_count', $this->_foreach['image_loop']['iteration']); ?>
						   	   <?php endforeach; endif; unset($_from); ?>
							<?php endif; ?>
						</div>

						<script>
							var multi_selector2 = new MultiSelector2( document.getElementById( 'new_files_list' ), 6 );
							multi_selector2.count = <?php echo $this->_tpl_vars['image_count']; ?>

							multi_selector2.addElement( document.getElementById( 'new_file_element' ) ,'<?php echo $this->_tpl_vars['fldname']; ?>
');
						</script>

					<?php else: ?>

						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" />
						<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
						<input type="hidden" name="id" value=""/>
						<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != "" && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
							<div id="replaceimage">[<?php echo $this->_tpl_vars['maindata'][3]['0']['orgname']; ?>
] <a href="javascript:;" onClick="delimage(<?php echo $this->_tpl_vars['ID']; ?>
)">Del</a></div>
						<?php endif; ?>

					<?php endif; ?>

				<?php else: ?>

					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" />
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
					<input type="hidden" name="id" value=""/>
					<?php if ($this->_tpl_vars['maindata'][3]['0']['name'] != "" && $this->_tpl_vars['DUPLICATE'] != 'true'): ?>
						<div id="replaceimage">[<?php echo $this->_tpl_vars['maindata'][3]['0']['orgname']; ?>
] <a href="javascript:;" onClick="delimage(<?php echo $this->_tpl_vars['ID']; ?>
)">Del</a></div>
					<?php endif; ?>
				<?php endif; ?>

			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 61): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  disabled >
				<?php endif; ?>
			</td>

			<td colspan="1" width="30%" align=left class="dvtCellInfo">
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this)"/>
				<input type="hidden" name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"/>
				<input type="hidden" name="id" value=""/><?php echo $this->_tpl_vars['fldvalue']; ?>

			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 156): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
				<?php if ($this->_tpl_vars['fldvalue'] == 'on'): ?>
					<td width="30%" align=left class="dvtCellInfo">
						<?php if (( $this->_tpl_vars['secondvalue'] == 1 && $this->_tpl_vars['CURRENT_USERID'] != $_REQUEST['record'] ) || ( $this->_tpl_vars['MODE'] == 'create' )): ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" checked>
						<?php else: ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="on">
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" disabled tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox" checked>
						<?php endif; ?>
					</td>
				<?php else: ?>
					<td width="30%" align=left class="dvtCellInfo">
						<?php if (( $this->_tpl_vars['secondvalue'] == 1 && $this->_tpl_vars['CURRENT_USERID'] != $_REQUEST['record'] ) || ( $this->_tpl_vars['MODE'] == 'create' )): ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox">
						<?php else: ?>
							<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" disabled tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" type="checkbox">
						<?php endif; ?>
					</td>
				<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 98): ?><!-- Role Selection Popup -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			<?php if ($this->_tpl_vars['thirdvalue'] == 1): ?>
				<input name="role_name" id="role_name" readonly class="txtBox" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" type="text">&nbsp;
				<a href="javascript:openPopup();"><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" align="absmiddle" border="0"></a>
			<?php else: ?>
				<input name="role_name" id="role_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="txtBox" readonly value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" type="text">&nbsp;
			<?php endif; ?>
				<input name="user_role" id="user_role" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" type="hidden">
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 104): ?><!-- Mandatory Email Fields -->
			<td width=20% class="dvtCellLabel" align=right>
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			 </td>
    	     <td width=30% align=left class="dvtCellInfo"><input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id ="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
		<?php elseif ($this->_tpl_vars['uitype'] == 115): ?><!-- for Status field Disabled for nonadmin -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   <?php if ($this->_tpl_vars['secondvalue'] == 1 && $this->_tpl_vars['CURRENT_USERID'] != $_REQUEST['record']): ?>
			   	<select id="user_status" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
			   <?php else: ?>
			   	<select id="user_status" disabled name="<?php echo $this->_tpl_vars['fldname']; ?>
" class="small">
			   <?php endif; ?>
				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
	                <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
 >
	                        <?php echo $this->_tpl_vars['arr'][0]; ?>

	                </option>
				<?php endforeach; endif; unset($_from); ?>
			   </select>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 105): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODE'] == 'edit' && $this->_tpl_vars['IMAGENAME'] != ''): ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" />[<?php echo $this->_tpl_vars['IMAGENAME']; ?>
]<br><?php echo $this->_tpl_vars['APP']['LBL_IMG_FORMATS']; ?>

					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['IMAGENAME']; ?>
" />
				<?php else: ?>
					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
"  type="file" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this);" /><br><?php echo $this->_tpl_vars['APP']['LBL_IMG_FORMATS']; ?>

					<input name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden"  type="hidden" value="<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>
" />
				<?php endif; ?>
					<input type="hidden" name="id" value=""/>
					<?php echo $this->_tpl_vars['maindata'][3]['0']['name']; ?>

			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 103): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" colspan="3" align=left class="dvtCellInfo">
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 101): ?><!-- for reportsto field USERS POPUP -->
			<td width="20%" class="dvtCellLabel" align=right>
		      <font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
            </td>
			<td width="30%" align=left class="dvtCellInfo">
			<input readonly name='reports_to_name' class="small" type="text" value='<?php echo $this->_tpl_vars['fldvalue']; ?>
' tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" ><input name='reports_to_id' type="hidden" value='<?php echo $this->_tpl_vars['secondvalue']; ?>
'>&nbsp;<input title="Change [Alt+C]" accessKey="C" type="button" class="small" value='<?php echo $this->_tpl_vars['UMOD']['LBL_CHANGE']; ?>
' name=btn1 LANGUAGE=javascript onclick='return window.open("index.php?module=Users&action=Popup&form=UsersEditView&form_submit=false&fromlink=<?php echo $this->_tpl_vars['fromlink']; ?>
&recordid=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=1000,height=603,resizable=0,scrollbars=0");'>
            </td>

		<?php elseif ($this->_tpl_vars['uitype'] == 116 || $this->_tpl_vars['uitype'] == 117): ?><!-- for currency in users details-->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   <?php if ($this->_tpl_vars['secondvalue'] == 1 || $this->_tpl_vars['uitype'] == 117): ?>
			   	<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
			   <?php else: ?>
			   	<select disabled name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
			   <?php endif; ?>

				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uivalueid'] => $this->_tpl_vars['arr']):
?>
					<?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
						<option value="<?php echo $this->_tpl_vars['uivalueid']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo getTranslatedCurrencyString($this->_tpl_vars['sel_value']); ?>
</option>
						<!-- code added to pass Currency field value, if Disabled for nonadmin -->
						<?php if ($this->_tpl_vars['value'] == 'selected' && $this->_tpl_vars['secondvalue'] != 1): ?>
							<?php $this->assign('curr_stat', ($this->_tpl_vars['uivalueid'])); ?>
						<?php endif; ?>
						<!--code ends -->
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>
			   </select>
			<!-- code added to pass Currency field value, if Disabled for nonadmin -->
			<?php if ($this->_tpl_vars['curr_stat'] != '' && $this->_tpl_vars['uitype'] != 117): ?>
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['curr_stat']; ?>
">
			<?php endif; ?>
			<!--code ends -->
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 106): ?>
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width=30% align=left class="dvtCellInfo">
				<?php if ($this->_tpl_vars['MODE'] == 'edit'): ?>
				<input type="text" readonly name="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				<?php else: ?>
				<input type="text" name="<?php echo $this->_tpl_vars['fldname']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				<?php endif; ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 99): ?>
			<?php if ($this->_tpl_vars['MODE'] == 'create'): ?>
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width=30% align=left class="dvtCellInfo">
				<input type="password" name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="<?php echo $this->_tpl_vars['fldvalue']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>
			<?php endif; ?>

		<?php elseif ($this->_tpl_vars['uitype'] == 30): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>
 <?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td colspan="3" width="30%" align=left class="dvtCellInfo">
				<?php $this->assign('check', $this->_tpl_vars['secondvalue'][0]); ?>
				<?php $this->assign('yes_val', $this->_tpl_vars['secondvalue'][1]); ?>
				<?php $this->assign('no_val', $this->_tpl_vars['secondvalue'][2]); ?>

				<input type="radio" name="set_reminder" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" value="Yes" <?php echo $this->_tpl_vars['check']; ?>
>&nbsp;<?php echo $this->_tpl_vars['yes_val']; ?>
&nbsp;
				<input type="radio" name="set_reminder" value="No">&nbsp;<?php echo $this->_tpl_vars['no_val']; ?>
&nbsp;

				<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val_arr']):
?>
					<?php $this->assign('start', ($this->_tpl_vars['val_arr'][0])); ?>
					<?php $this->assign('end', ($this->_tpl_vars['val_arr'][1])); ?>
					<?php $this->assign('sendname', ($this->_tpl_vars['val_arr'][2])); ?>
					<?php $this->assign('disp_text', ($this->_tpl_vars['val_arr'][3])); ?>
					<?php $this->assign('sel_val', ($this->_tpl_vars['val_arr'][4])); ?>
					<select name="<?php echo $this->_tpl_vars['sendname']; ?>
" class="small">
						<?php unset($this->_sections['reminder']);
$this->_sections['reminder']['name'] = 'reminder';
$this->_sections['reminder']['start'] = (int)$this->_tpl_vars['start'];
$this->_sections['reminder']['max'] = (int)$this->_tpl_vars['end'];
$this->_sections['reminder']['loop'] = is_array($_loop=$this->_tpl_vars['end']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['reminder']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['reminder']['show'] = true;
if ($this->_sections['reminder']['max'] < 0)
    $this->_sections['reminder']['max'] = $this->_sections['reminder']['loop'];
if ($this->_sections['reminder']['start'] < 0)
    $this->_sections['reminder']['start'] = max($this->_sections['reminder']['step'] > 0 ? 0 : -1, $this->_sections['reminder']['loop'] + $this->_sections['reminder']['start']);
else
    $this->_sections['reminder']['start'] = min($this->_sections['reminder']['start'], $this->_sections['reminder']['step'] > 0 ? $this->_sections['reminder']['loop'] : $this->_sections['reminder']['loop']-1);
if ($this->_sections['reminder']['show']) {
    $this->_sections['reminder']['total'] = min(ceil(($this->_sections['reminder']['step'] > 0 ? $this->_sections['reminder']['loop'] - $this->_sections['reminder']['start'] : $this->_sections['reminder']['start']+1)/abs($this->_sections['reminder']['step'])), $this->_sections['reminder']['max']);
    if ($this->_sections['reminder']['total'] == 0)
        $this->_sections['reminder']['show'] = false;
} else
    $this->_sections['reminder']['total'] = 0;
if ($this->_sections['reminder']['show']):

            for ($this->_sections['reminder']['index'] = $this->_sections['reminder']['start'], $this->_sections['reminder']['iteration'] = 1;
                 $this->_sections['reminder']['iteration'] <= $this->_sections['reminder']['total'];
                 $this->_sections['reminder']['index'] += $this->_sections['reminder']['step'], $this->_sections['reminder']['iteration']++):
$this->_sections['reminder']['rownum'] = $this->_sections['reminder']['iteration'];
$this->_sections['reminder']['index_prev'] = $this->_sections['reminder']['index'] - $this->_sections['reminder']['step'];
$this->_sections['reminder']['index_next'] = $this->_sections['reminder']['index'] + $this->_sections['reminder']['step'];
$this->_sections['reminder']['first']      = ($this->_sections['reminder']['iteration'] == 1);
$this->_sections['reminder']['last']       = ($this->_sections['reminder']['iteration'] == $this->_sections['reminder']['total']);
?>
							<?php if ($this->_sections['reminder']['index'] == $this->_tpl_vars['sel_val']): ?>
								<?php $this->assign('sel_value', 'SELECTED'); ?>
							<?php else: ?>
								<?php $this->assign('sel_value', ""); ?>
							<?php endif; ?>
							<OPTION VALUE="<?php echo $this->_sections['reminder']['index']; ?>
" "<?php echo $this->_tpl_vars['sel_value']; ?>
"><?php echo $this->_sections['reminder']['index']; ?>
</OPTION>
						<?php endfor; endif; ?>
					</select>
					&nbsp;<?php echo $this->_tpl_vars['disp_text']; ?>

				<?php endforeach; endif; unset($_from); ?>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 26): ?>
			<td width="20%" class="dvtCellLabel" align=right>
			<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['fldlabel']; ?>

			<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<select name="<?php echo $this->_tpl_vars['fldname']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class="small">
					<?php $_from = $this->_tpl_vars['fldvalue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
					<option value="<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['v']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		<?php elseif ($this->_tpl_vars['uitype'] == 27): ?>
			<td width="20%" class="dvtCellLabel" align="right" >
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['fldlabel_other']; ?>
&nbsp;
				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?><input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small" ><?php endif; ?>
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<select class="small" name="<?php echo $this->_tpl_vars['fldname']; ?>
" onchange="changeDldType((this.value=='I')? 'file': 'text');">
					<?php unset($this->_sections['combo']);
$this->_sections['combo']['name'] = 'combo';
$this->_sections['combo']['loop'] = is_array($_loop=$this->_tpl_vars['fldlabel']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['combo']['show'] = true;
$this->_sections['combo']['max'] = $this->_sections['combo']['loop'];
$this->_sections['combo']['step'] = 1;
$this->_sections['combo']['start'] = $this->_sections['combo']['step'] > 0 ? 0 : $this->_sections['combo']['loop']-1;
if ($this->_sections['combo']['show']) {
    $this->_sections['combo']['total'] = $this->_sections['combo']['loop'];
    if ($this->_sections['combo']['total'] == 0)
        $this->_sections['combo']['show'] = false;
} else
    $this->_sections['combo']['total'] = 0;
if ($this->_sections['combo']['show']):

            for ($this->_sections['combo']['index'] = $this->_sections['combo']['start'], $this->_sections['combo']['iteration'] = 1;
                 $this->_sections['combo']['iteration'] <= $this->_sections['combo']['total'];
                 $this->_sections['combo']['index'] += $this->_sections['combo']['step'], $this->_sections['combo']['iteration']++):
$this->_sections['combo']['rownum'] = $this->_sections['combo']['iteration'];
$this->_sections['combo']['index_prev'] = $this->_sections['combo']['index'] - $this->_sections['combo']['step'];
$this->_sections['combo']['index_next'] = $this->_sections['combo']['index'] + $this->_sections['combo']['step'];
$this->_sections['combo']['first']      = ($this->_sections['combo']['iteration'] == 1);
$this->_sections['combo']['last']       = ($this->_sections['combo']['iteration'] == $this->_sections['combo']['total']);
?>
						<option value="<?php echo $this->_tpl_vars['fldlabel_combo'][$this->_sections['combo']['index']]; ?>
" <?php echo $this->_tpl_vars['fldlabel_sel'][$this->_sections['combo']['index']]; ?>
 ><?php echo $this->_tpl_vars['fldlabel'][$this->_sections['combo']['index']]; ?>
 </option>
					<?php endfor; endif; ?>
				</select>
				<script>
					function aicrm_<?php echo $this->_tpl_vars['fldname']; ?>
Init(){
						var d = document.getElementsByName('<?php echo $this->_tpl_vars['fldname']; ?>
')[0];
						var type = (d.value=='I')? 'file': 'text';

						changeDldType(type, true);
					}
					if(typeof window.onload =='function'){
						var oldOnLoad = window.onload;
						document.body.onload = function(){
							aicrm_<?php echo $this->_tpl_vars['fldname']; ?>
Init();
							oldOnLoad();
						}
					}else{
						window.onload = function(){
							aicrm_<?php echo $this->_tpl_vars['fldname']; ?>
Init();
						}
					}

				</script>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 28): ?>
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red"><?php echo $this->_tpl_vars['mandatory_field']; ?>
</font><?php echo $this->_tpl_vars['usefldlabel']; ?>

				<?php if ($this->_tpl_vars['MASS_EDIT'] == '1'): ?>
					<input type="checkbox" name="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" id="<?php echo $this->_tpl_vars['fldname']; ?>
_mass_edit_check" class="small"  disabled >
				<?php endif; ?>
			</td>

			<td colspan="1" width="30%" align="left" class="dvtCellInfo">
			<script type="text/javascript">
				function changeDldType(type, onInit){
					var fieldname = '<?php echo $this->_tpl_vars['fldname']; ?>
';
					if(!onInit){
						var dh = getObj('<?php echo $this->_tpl_vars['fldname']; ?>
_hidden');
						if(dh) dh.value = '';
					}

					var v1 = document.getElementById(fieldname+'_E__');
					var v2 = document.getElementById(fieldname+'_I__');

					var text = v1.type =="text"? v1: v2;
					var file = v1.type =="file"? v1: v2;
					var filename = document.getElementById(fieldname+'_value');
					<?php echo '
					if(type == \'file\'){
						// Avoid sending two form parameters with same key to server
						file.name = fieldname;
						text.name = \'_\' + fieldname;

						file.style.display = \'\';
						text.style.display = \'none\';
						text.value = \'\';
						filename.style.display = \'\';
					}else{
						// Avoid sending two form parameters with same key to server
						text.name = fieldname;
						file.name = \'_\' + fieldname;

						file.style.display = \'none\';
						text.style.display = \'\';
						file.value = \'\';
						filename.style.display = \'none\';
						filename.innerHTML="";
					}
					'; ?>

				}
			</script>
			<div>
				<input name="<?php echo $this->_tpl_vars['fldname']; ?>
" id="<?php echo $this->_tpl_vars['fldname']; ?>
_I__" type="file" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" onchange="validateFilename(this)" style="display: none;"/>
				<input type="hidden" name="<?php echo $this->_tpl_vars['fldname']; ?>
_hidden" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
"/>
				<input type="hidden" name="id" value=""/>
				<input type="text" id="<?php echo $this->_tpl_vars['fldname']; ?>
_E__" name="<?php echo $this->_tpl_vars['fldname']; ?>
" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="<?php echo $this->_tpl_vars['secondvalue']; ?>
" /><br>
				<span id="<?php echo $this->_tpl_vars['fldname']; ?>
_value" style="display:none;">
					<?php if ($this->_tpl_vars['secondvalue'] != ''): ?>
						[<?php echo $this->_tpl_vars['secondvalue']; ?>
]
					<?php endif; ?>
				</span>
			</div>
			</td>

		<?php elseif ($this->_tpl_vars['uitype'] == 83): ?> <!-- Handle the Tax in Inventory -->
			<?php $_from = $this->_tpl_vars['TAX_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['tax']):
?>
				<?php if ($this->_tpl_vars['tax']['check_value'] == 1): ?>
					<?php $this->assign('check_value', 'checked'); ?>
					<?php $this->assign('show_value', 'visible'); ?>
				<?php else: ?>
					<?php $this->assign('check_value', ""); ?>
					<?php $this->assign('show_value', 'hidden'); ?>
				<?php endif; ?>
				<td align="right" class="dvtCellLabel" style="border:0px solid red;">
					<?php echo $this->_tpl_vars['tax']['taxlabel']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

					<input type="checkbox" name="<?php echo $this->_tpl_vars['tax']['check_name']; ?>
" id="<?php echo $this->_tpl_vars['tax']['check_name']; ?>
" class="small" onclick="fnshowHide(this,'<?php echo $this->_tpl_vars['tax']['taxname']; ?>
')" <?php echo $this->_tpl_vars['check_value']; ?>
>
				</td>
				<td class="dvtCellInfo" align="left" style="border:0px solid red;">
					<input type="text" class="detailedViewTextBox" name="<?php echo $this->_tpl_vars['tax']['taxname']; ?>
" id="<?php echo $this->_tpl_vars['tax']['taxname']; ?>
" value="<?php echo $this->_tpl_vars['tax']['percentage']; ?>
" style="visibility:<?php echo $this->_tpl_vars['show_value']; ?>
;" onBlur="fntaxValidation('<?php echo $this->_tpl_vars['tax']['taxname']; ?>
')">
				</td>
			   </tr>
			<?php endforeach; endif; unset($_from); ?>

			<td colspan="2" class="dvtCellInfo">&nbsp;</td>
		<?php endif; ?>

<!-- <div id="dialog" style="display:none;">Dialog Content.</div> -->

<script type="text/javascript">
<?php echo '

function validateBranchCode(obj)
{
	var value = jQuery(obj).val()
	if (isNaN(value)) {
		alert(\'Branch code ต้องเป็นข้อมูลตัวเลขเท่านั้น\')
		value = value.replace(/\\D/g, \'\');
	}
	

	if (value.length > 5) {
		alert(\'Branch code ระบุได้ 5 หลักเท่านั้น\')
		value = value.slice(0, 5);
	}

	jQuery(obj).val(value)
}

function Quickcreate(module,remodule){
	var msg = \'\';
	if(module == \'Accounts\' ){
		if(remodule == \'Order\'){
			var contact_id = document.EditView.contact_id.value;
			msg = "Quick create "+module;
			url = \'plugin/\'+module+\'/Quickcreate.php?contact_id=\'+contact_id;
		}else{
			msg = "Quick create "+module;
			url = \'plugin/\'+module+\'/Quickcreate.php\';
		}
	}
	jQuery(\'#dialog_create\').window({
	    title: msg,
	    width: 550,
	    height: 680,
	    closed: false,
	    cache: false,
	    href: url,
	    modal: true
	});
}//function Quickcreate

jQuery(\'.ws-inputreplace\').keypress(

);
function isPhoneNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function format_num(id){
	jQuery(\'#\'+id).keypress(function (event) {
			var keycode = event.which;
			if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 0 || keycode == 13 || keycode == 37 || keycode == 39 || keycode == 0 || keycode == 45 || (keycode >= 48 && keycode <= 57)))) {
				event.preventDefault();
			}
		});

		 var number = document.getElementById(id).value;

		 number=number.replace(/,/g,\'\');
		 val = number.split(".");

		 val1 = val[0].split("-");

		 val[0] = val[0].split("").reverse().join("");
		 val[0] = val[0].replace(/(\\d{3})/g,"$1,");
		 val[0] = val[0].split("").reverse().join("");
		 val[0] = val[0].indexOf(",")==0?val[0].substring(1):val[0];

		  val[0] = val[0].indexOf(",")==0?val[0].substring(1):val[0];

		 document.getElementById(id).value = val.join(".");
	}

			webshim.setOptions(\'forms\', {
				lazyCustomMessages: true,
				replaceValidationUI: true,
				addValidators: true
			});

			(function(){
				var stateMatches = {
					\'true\': true,
					\'false\': false,
					\'auto\': \'auto\'
				};
				var enhanceState = (location.search.match(/enhancelist\\=([true|auto|false|nopolyfill]+)/) || [\'\', \'auto\'])[1];
				//console.log(enhanceState);
				webshim.ready(\'jquery\', function(){
					jQuery(function () {

						jQuery(\'.polyfill-type select\')
								.val(enhanceState)
								.on(\'change\', function () {
									location.search = \'enhancelist=\' + jQuery(this).val();
								})
						;
						if(typeof(jQuery(".cf_4748_field").val()) != \'undefined\'){

							jQuery(\'.cf_4748_field\').prop(\'readonly\', true);
							jQuery(".cf_4748_field").css({"backgroundColor":"#CCCCCC"});
						}


					});
				});

				webshim.setOptions(\'forms\', {
					customDatalist: stateMatches[enhanceState]
				});
				webshim.setOptions(\'forms-ext\', {
					replaceUI: stateMatches[enhanceState]
				});

			})();

			// load the forms polyfill
			webshim.polyfill(\'forms forms-ext\');
'; ?>


init();

</script>

