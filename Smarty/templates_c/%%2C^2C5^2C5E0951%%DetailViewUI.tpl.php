<?php /* Smarty version 2.6.18, created on 2026-04-08 16:51:13
         compiled from DetailViewUI.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'DetailViewUI.tpl', 117, false),array('modifier', 'aicrm_imageurl', 'DetailViewUI.tpl', 127, false),array('modifier', 'getTranslatedCurrencyString', 'DetailViewUI.tpl', 137, false),array('modifier', 'cat', 'DetailViewUI.tpl', 782, false),array('modifier', 'replace', 'DetailViewUI.tpl', 787, false),array('modifier', 'wordwrap', 'DetailViewUI.tpl', 789, false),array('modifier', 'regex_replace', 'DetailViewUI.tpl', 860, false),array('modifier', 'parse_calendardate', 'DetailViewUI.tpl', 1084, false),)), $this); ?>
<!-- This file is used to display the fields based on the ui type in detailview -->
		<?php if ($this->_tpl_vars['keyid'] == '1' || $this->_tpl_vars['keyid'] == 2 || $this->_tpl_vars['keyid'] == '11' || $this->_tpl_vars['keyid'] == '7' || $this->_tpl_vars['keyid'] == '9' || $this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '71' || $this->_tpl_vars['keyid'] == '72' || $this->_tpl_vars['keyid'] == '103' || $this->_tpl_vars['keyid'] == '255' || $this->_tpl_vars['keyid'] == '923' || $this->_tpl_vars['keyid'] == '924' || $this->_tpl_vars['keyid'] == '925' || $this->_tpl_vars['keyid'] == '926' || $this->_tpl_vars['keyid'] == '927' || $this->_tpl_vars['keyid'] == '928'): ?>
    <!--TextBox-->
			<?php if ($this->_tpl_vars['keyfldname'] == 'grouping' || $this->_tpl_vars['keyfldname'] == 'business_partner' || $this->_tpl_vars['keyfldname'] == 'customer_name' || $this->_tpl_vars['keyfldname'] == 'search_term_1_2' || $this->_tpl_vars['keyfldname'] == 'streets' || $this->_tpl_vars['keyfldname'] == 'street_4' || $this->_tpl_vars['keyfldname'] == 'street_5' || $this->_tpl_vars['keyfldname'] == 'districts' || $this->_tpl_vars['keyfldname'] == 'city' || $this->_tpl_vars['keyfldname'] == 'countrys' || $this->_tpl_vars['keyfldname'] == 'tax_id' || $this->_tpl_vars['keyfldname'] == 'branch_code' || $this->_tpl_vars['keyfldname'] == 'contact_person' || $this->_tpl_vars['keyfldname'] == 'telephone' || $this->_tpl_vars['keyfldname'] == 'fax' || $this->_tpl_vars['keyfldname'] == 'e_mail' || $this->_tpl_vars['keyfldname'] == 'sales_organization' || $this->_tpl_vars['keyfldname'] == 'distribution_channel' || $this->_tpl_vars['keyfldname'] == 'cust_pric_procedure' || $this->_tpl_vars['keyfldname'] == 'customer_group' || $this->_tpl_vars['keyfldname'] == 'sales_district' || $this->_tpl_vars['keyfldname'] == 'currency' || $this->_tpl_vars['keyfldname'] == 'shipping_condition' || $this->_tpl_vars['keyfldname'] == 'payment_terms' || $this->_tpl_vars['keyfldname'] == 'sales_group' || $this->_tpl_vars['keyfldname'] == 'sg_description' || $this->_tpl_vars['keyfldname'] == 'sales_office' || $this->_tpl_vars['keyfldname'] == 'customer_group_1' || $this->_tpl_vars['keyfldname'] == 'cg1_description' || $this->_tpl_vars['keyfldname'] == 'agent_payer_1' || $this->_tpl_vars['keyfldname'] == 'acct_clerks_tel_no' || $this->_tpl_vars['keyfldname'] == 'postal_code' || $this->_tpl_vars['keyfldname'] == 'material' || $this->_tpl_vars['keyfldname'] == 'base_unit_of_measure' || $this->_tpl_vars['keyfldname'] == 'um_coversion_m2_pcs' || $this->_tpl_vars['keyfldname'] == 'description_en' || $this->_tpl_vars['keyfldname'] == 'description_th' || $this->_tpl_vars['keyfldname'] == 'status' || $this->_tpl_vars['keyfldname'] == 'desc_status' || $this->_tpl_vars['keyfldname'] == 'valuation_class_description' || $this->_tpl_vars['keyfldname'] == 'valuation_class' || $this->_tpl_vars['keyfldname'] == 'material_group' || $this->_tpl_vars['keyfldname'] == 'mat_group' || $this->_tpl_vars['keyfldname'] == 'plant' || $this->_tpl_vars['keyfldname'] == 'sales_org' || $this->_tpl_vars['keyfldname'] == 'channel' || $this->_tpl_vars['keyfldname'] == 'mat_price_grp' || $this->_tpl_vars['keyfldname'] == 'mat_price_grp_desc' || $this->_tpl_vars['keyfldname'] == 'mat_gp2' || $this->_tpl_vars['keyfldname'] == 'mat_gp2_desciption' || $this->_tpl_vars['keyfldname'] == 'mat_gp1' || $this->_tpl_vars['keyfldname'] == 'mat_gp1_desciption' || $this->_tpl_vars['keyfldname'] == 'mat_gp3' || $this->_tpl_vars['keyfldname'] == 'mat_gp3_desciption' || $this->_tpl_vars['keyfldname'] == 'mat_gp4' || $this->_tpl_vars['keyfldname'] == 'mat_gp4_desciption' || $this->_tpl_vars['keyfldname'] == 'mat_gp5' || $this->_tpl_vars['keyfldname'] == 'mat_gp5_desciption' || $this->_tpl_vars['keyfldname'] == 'country' || $this->_tpl_vars['keyfldname'] == 'country_of_origin' || $this->_tpl_vars['keyfldname'] == 'list_price' || $this->_tpl_vars['keyfldname'] == 'piece_per_carton' || $this->_tpl_vars['keyfldname'] == 'squaremeters_per_carton' || $this->_tpl_vars['keyfldname'] == 'price_per_piece' || $this->_tpl_vars['keyfldname'] == 'price_per_squaremeter' || $this->_tpl_vars['keyfldname'] == 'quantity' || $this->_tpl_vars['keyfldname'] == 'quantity_sheet' || $this->_tpl_vars['keyfldname'] == 'checkindate' || $this->_tpl_vars['keyfldname'] == 'checkoutdate' || $this->_tpl_vars['keyfldname'] == 'latlong' || $this->_tpl_vars['keyfldname'] == 'ref_service_request_no'): ?>
  			<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"  valign="top">
      <?php else: ?>
			  <td width=25% class="dvtCellInfo <?php echo $this->_tpl_vars['keyfldname']; ?>
" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" data-keyid="<?php echo $this->_tpl_vars['keyid']; ?>
" data-keyfield="<?php echo $this->_tpl_vars['keyfldname']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');" valign="top">
      <?php endif; ?>

			<?php if ($this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '255' && ( $this->_tpl_vars['keyfldname'] != 'lastname' )): ?><!--SalutationSymbol-->
				<?php if ($this->_tpl_vars['keyaccess'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
					<font color='red'><?php echo $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']; ?>
</font>
				<?php else: ?>
					<?php echo $this->_tpl_vars['keysalut']; ?>

				<?php endif; ?>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['keyid'] == '923' || $this->_tpl_vars['keyid'] == '924'): ?><!--telephonecountrycode-->
				<?php if ($this->_tpl_vars['keyaccess'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
					<font color='red'><?php echo $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']; ?>
</font>
				<?php else: ?>
					<?php echo $this->_tpl_vars['keysalut']; ?>

				<?php endif; ?>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['keyid'] == '925' || $this->_tpl_vars['keyid'] == '926'): ?><!--mobilecountrycode-->
				<?php if ($this->_tpl_vars['keyaccess'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
					<font color='red'><?php echo $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']; ?>
</font>
				<?php else: ?>
					<?php echo $this->_tpl_vars['keysalut']; ?>

				<?php endif; ?>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['keyid'] == '927' || $this->_tpl_vars['keyid'] == '928'): ?><!--faxcountrycode-->
				<?php if ($this->_tpl_vars['keyaccess'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
					<font color='red'><?php echo $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']; ?>
</font>
				<?php else: ?>
					<?php echo $this->_tpl_vars['keysalut']; ?>

				<?php endif; ?>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['keyid'] == 11): ?>
				<?php if ($this->_tpl_vars['USE_ASTERISK'] == 'true'): ?>
					<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href='javascript:;' onclick='startCall("<?php echo $this->_tpl_vars['keyval']; ?>
")'><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
				<?php else: ?>
					<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
				<?php endif; ?>
			<?php else: ?>
      	<?php if ($this->_tpl_vars['keyfldname'] == 'cf_1219' || $this->_tpl_vars['keyfldname'] == 'cf_1485'): ?>
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><font color="#FF0000" size="+1" ><?php echo $this->_tpl_vars['keyval']; ?>
</font></span>
        <?php elseif ($this->_tpl_vars['keyfldname'] == 'lostremark'): ?>
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
" class="lostremark"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
        <?php else: ?>
	       <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
        <?php endif; ?>
			<?php endif; ?>

      <?php if ($this->_tpl_vars['keyfldname'] != 'latlong'): ?>
      <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
      	<input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input><br>
        <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
      </div>
      <?php endif; ?>

    <?php if ($this->_tpl_vars['keyid'] == '71' && $this->_tpl_vars['keyfldname'] == 'unit_price'): ?>
      <?php if (count($this->_tpl_vars['PRICE_DETAILS']) > 0): ?>
        <span id="multiple_currencies" width="38%" style="align:right;">
        <a href="javascript:void(0);" onclick="toggleShowHide('currency_class','multiple_currencies');"><?php echo $this->_tpl_vars['APP']['LBL_MORE_CURRENCIES']; ?>
 &raquo;</a>
        </span>

        <div id="currency_class" class="multiCurrencyDetailUI">
          <table width="100%" height="100%" class="small" cellpadding="5">
          <tr>
            <th colspan="2"><b><?php echo $this->_tpl_vars['MOD']['LBL_PRODUCT_PRICES']; ?>
</b></th>
            <th align="right">
            <img border="0" style="cursor: pointer;" onclick="toggleShowHide('multiple_currencies','currency_class');" src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
"/>
            </th>
          </tr>
          <tr class="detailedViewHeader">
            <th><?php echo $this->_tpl_vars['APP']['LBL_CURRENCY']; ?>
</th>
            <th colspan="2"><?php echo $this->_tpl_vars['APP']['LBL_PRICE']; ?>
</th>
          </tr>
            <?php $_from = $this->_tpl_vars['PRICE_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['price']):
?>
              <tr>
                <td class="dvtCellLabel" width="40%">
                <?php echo getTranslatedCurrencyString($this->_tpl_vars['price']['currencylabel']); ?>
 (<?php echo $this->_tpl_vars['price']['currencysymbol']; ?>
)
                </td>

                <td class="dvtCellInfo" width="60%" colspan="2">
                <?php echo $this->_tpl_vars['price']['curvalue']; ?>

                </td>
              </tr>
            <?php endforeach; endif; unset($_from); ?>
          </table>
        </div>
      <?php endif; ?>
    <?php endif; ?>
    </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '800'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '801'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '802'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '803'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '804'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '805'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '806'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '807'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '808'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '809'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '810'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '811'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '812'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '813'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '814'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '815'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '816'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '817'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '818'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '819'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '820'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '821'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '822'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '823'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '824'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '825'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '826'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    <?php elseif ($this->_tpl_vars['keyid'] == '900'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="salesinvoice_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesinvoice&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.salesinvoiceid.value=''; this.form.salesinvoice_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == 901): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
          <font color="<?php echo $this->_tpl_vars['fontval']; ?>
"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?></font>
        </span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
            <OPTION value=" " ></OPTION>
              <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == 902): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><font color="<?php echo $this->_tpl_vars['fontval']; ?>
"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?></font></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small" style="width:140px">
            <OPTION value=" " ></OPTION>
              <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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

        <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

        <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == 903): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><font color="<?php echo $this->_tpl_vars['fontval']; ?>
"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?></font></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small" style="width:140px">
            <OPTION value=" " ></OPTION>
            <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
        <br>
        <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

        <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>
    
    <?php elseif ($this->_tpl_vars['keyid'] == '904'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="salesinvoice_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesinvoice&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.salesinvoiceid.value=''; this.form.salesinvoice_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>
    <?php elseif ($this->_tpl_vars['keyid'] == '912'): ?> <!--ProductPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '921'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="prevacc_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=prevaccount","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.prevaccid.value=''; this.form.prevacc_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '929'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype==accountfield&field=<?php echo $this->_tpl_vars['keyfldname']; ?>
","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '930'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype==accountfield&field=<?php echo $this->_tpl_vars['keyfldname']; ?>
","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '931'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '932'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '933'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
      <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
"  value="<?php echo $this->_tpl_vars['keyval']; ?>
" class="easyui-timespinner" style="width:80px;vertical-align: middle;">
          </td>
        </tr>
      </table>
      <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

      <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
      </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '934'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
        </div>
      </td>
      <!-- <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Projects&action=Popup&html=Popup_picker&popuptype==accountfield&field=<?php echo $this->_tpl_vars['keyfldname']; ?>
","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td> -->

    <?php elseif ($this->_tpl_vars['keyid'] == '935'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="serial_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Serial&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.serialid.value=''; this.form.serial_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
        </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '936'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="sparepart_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;<img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Sparepart&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.sparepartid.value=''; this.form.sparepart_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '937'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="errors_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Errors&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.errorsid.value=''; this.form.errors_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '938'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
        <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="job_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
        <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
        <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Job&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
        <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.jobid.value=''; this.form.job_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
        <br>
        <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

        <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
    </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '939'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="ticket_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.ticketid.value=''; this.form.ticket_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '940'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '941'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="plant_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Plant&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.plantid.value=''; this.form.plant_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>



    <?php elseif ($this->_tpl_vars['keyid'] == '943'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="lead_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Leads&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.leadid.value=''; this.form.lead_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '944'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="activitytype" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.activityid.value=''; this.form.activitytype.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '963'): ?>
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
            <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
                <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="inspectiontemplate_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
                <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
                <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
"
                    LANGUAGE=javascript
                    onclick='return window.open("index.php?module=Inspectiontemplate&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");'
                    align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
                <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
"
                      title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript
                      onClick="this.form.jobid.value=''; this.form.job_no.value=''; return false;" align="absmiddle"
                      style='cursor:hand;cursor:pointer'>
                <br>
                <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
"
                      onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')"
                  class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
            </div>
        </td>
        
    <?php elseif ($this->_tpl_vars['keyid'] == '301'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="deal_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Deal&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.dealid.value=''; this.form.deal_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '302'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a class="competitorid" href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="deal_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Competitor&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.competitorid.value=''; this.form.competitor_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '303'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="promotionvoucher_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotionvoucher&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript  onClick="this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
.value=''; this.form.<?php echo $this->_tpl_vars['keyfldname']; ?>
_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '304'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="promotion_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotion&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.promotionid.value=''; this.form.promotion_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '305'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="salesorder_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesorder&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.salesorderid.value=''; this.form.salesorder_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '306'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="premuimproduct_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Premuimproduct&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.premuimproductid.value=''; this.form.premuimproduct_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '307'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="quote_no" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.premuimproductid.value=''; this.form.premuimproduct_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '308'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="servicerequest_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Servicerequest&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.servicerequestid.value=''; this.form.servicerequest_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '309'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="ticket_title" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.ticketid.value=''; this.form.ticket_title.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '310'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="activity_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.activityid.value=''; this.form.activity_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>
      
    <?php elseif ($this->_tpl_vars['keyid'] == '946'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="questionnairetemplate_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.questionnairetemplateid.value=''; this.form.questionnairetemplate_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '947'): ?>

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="questionnaire_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.questionnaireid.value=''; this.form.questionnaire_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '948'): ?>
      <!-- <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"  valign="top"></td> -->
      </tr>
      <tr style="height:200px">
      <td  colspan="4" class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"  valign="top"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '949'): ?>

      <td colspan="3" class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"  valign="top"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '13' || $this->_tpl_vars['keyid'] == '104'): ?> <!--Email-->
        <?php if ($this->_tpl_vars['keyfldname'] == 'email_bounce'): ?>
          <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">
        <?php else: ?>
          <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
        <?php endif; ?>

        <?php if ($_SESSION['internal_mailer'] == 1): ?>
          <a href="javascript:InternalMailer(<?php echo $this->_tpl_vars['ID']; ?>
,<?php echo $this->_tpl_vars['keyfldid']; ?>
,'<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
','record_id');"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
        <?php else: ?>
          <a href="mailto:<?php echo $this->_tpl_vars['keyval']; ?>
" target="_blank" ><?php echo $this->_tpl_vars['keyval']; ?>
</a>
        <?php endif; ?>
          </span>
            <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
              <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
              <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

              <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
            </div>
            <div id="internal_mailer_<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="display: none;"><?php echo $this->_tpl_vars['keyfldid']; ?>
####<?php echo $_SESSION['internal_mailer']; ?>
</div>
          </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '15' || $this->_tpl_vars['keyid'] == '16'): ?> <!--ComboBox-->

        <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
          <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE'] && $this->_tpl_vars['arr'][2] == 'selected'): ?>
            <?php $this->assign('keyval', $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']); ?>
            <?php $this->assign('fontval', 'red'); ?>
          <?php else: ?>
            <?php $this->assign('fontval', ''); ?>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>

        <?php 
          global  $chkfield;
         ?>

        <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk' && ( $this->_tpl_vars['keyfldname'] == 'email_status' || $this->_tpl_vars['keyfldname'] == 'sms_status' )): ?>
            <?php  if($chkfield=="1"){ ?>
              <td width=25% class="dvtCellInfo" align="left" >
            <?php  }else{ ?>
              <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
            <?php  } ?>
        <?php else: ?>
          <?php if ($this->_tpl_vars['keyfldname'] == 'email_status' || $this->_tpl_vars['keyfldname'] == 'sms_status' || $this->_tpl_vars['keyfldname'] == 'job_type' || $this->_tpl_vars['keyfldname'] == 'stage'): ?><!-- || $keyfldname eq 'sms_sender_name'  -->
            <td width=25% class="dvtCellInfo" align="left" >
          <?php else: ?>
            <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
          <?php endif; ?>
        <?php endif; ?>

        
        <?php if ($this->_tpl_vars['keyfldname'] == 'stage'): ?>
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><font color="<?php echo $this->_tpl_vars['fontval']; ?>
" class="stage"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?></font></span>
        <?php elseif ($this->_tpl_vars['keyfldname'] == 'lostreason'): ?>
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><font color="<?php echo $this->_tpl_vars['fontval']; ?>
" class="lostreason"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?></font></span>
        <?php else: ?>
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><font color="<?php echo $this->_tpl_vars['fontval']; ?>
" class="<?php echo $this->_tpl_vars['keyfldname']; ?>
"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?></font></span>
        <?php endif; ?>

        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
           <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
            <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
              <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
                <option value="<?php echo $this->_tpl_vars['arr'][0]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
><?php echo $this->_tpl_vars['arr'][0]; ?>
</option>
              <?php else: ?>
                <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
>
                <?php echo $this->_tpl_vars['arr'][0]; ?>

                </option>
              <?php endif; ?>

            <?php endforeach; endif; unset($_from); ?>
           </select>

            <br><input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

                                 <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>

      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '33'): ?><!--Multi Select Combo box-->
    <!--code given by Neil start Ref:http://forums.vtiger.com/viewtopic.php?p=31096#31096-->
    <!--<?php $this->assign('MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING', ", "); ?>      <?php $this->assign('DETAILVIEW_WORDWRAP_WIDTH', '70'); ?> -->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
">
        <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_val']):
?>
          <?php if ($this->_tpl_vars['sel_val'][2] == 'selected'): ?>
            <?php if ($this->_tpl_vars['selected_val'] != ''): ?>
              <?php $this->assign('selected_val', ((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('cat', true, $_tmp, ', ') : smarty_modifier_cat($_tmp, ', '))); ?>
            <?php endif; ?>
          <?php $this->assign('selected_val', ((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['sel_val'][0]) : smarty_modifier_cat($_tmp, $this->_tpl_vars['sel_val'][0]))); ?>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <?php echo ((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;&nbsp;")); ?>

      <!-- commented to fix ticket4631 -using wordwrap will affect Not Accessible font color -->
      <!--<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['selected_val'])) ? $this->_run_mod_handler('replace', true, $_tmp, $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING'], "\x1") : smarty_modifier_replace($_tmp, $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING'], "\x1")))) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', "\x0") : smarty_modifier_replace($_tmp, ' ', "\x0")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\x1", $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING']) : smarty_modifier_replace($_tmp, "\x1", $this->_tpl_vars['MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING'])))) ? $this->_run_mod_handler('wordwrap', true, $_tmp, $this->_tpl_vars['DETAILVIEW_WORDWRAP_WIDTH'], "<br>&nbsp;") : smarty_modifier_wordwrap($_tmp, $this->_tpl_vars['DETAILVIEW_WORDWRAP_WIDTH'], "<br>&nbsp;")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\x0", "&nbsp;") : smarty_modifier_replace($_tmp, "\x0", "&nbsp;")); ?>
-->
        </span>
      <!--code given by Neil End-->
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <select MULTIPLE id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" size="4" style="width:160px;" class="small">
            <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
              <option value="<?php echo $this->_tpl_vars['arr'][1]; ?>
" <?php echo $this->_tpl_vars['arr'][2]; ?>
><?php echo $this->_tpl_vars['arr'][0]; ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
          </select>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '115'): ?> <!--ComboBox Status edit only for admin Users-->
      <td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>
</td>

    <?php elseif ($this->_tpl_vars['keyid'] == '116' || $this->_tpl_vars['keyid'] == '117'): ?> <!--ComboBox currency id edit only for admin Users-->
      <?php if ($this->_tpl_vars['keyadmin'] == 1 || $this->_tpl_vars['keyid'] == '117'): ?>
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
          <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
            <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uivalueid'] => $this->_tpl_vars['arr']):
?>
              <?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
                <option value="<?php echo $this->_tpl_vars['uivalueid']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo getTranslatedCurrencyString($this->_tpl_vars['sel_value']); ?>
</option>
              <?php endforeach; endif; unset($_from); ?>
            <?php endforeach; endif; unset($_from); ?>
          </select>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
          </div>
      <?php else: ?>
        <td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>

      <?php endif; ?>
        </td>
    <?php elseif ($this->_tpl_vars['keyid'] == '17'): ?> <!--WebSite-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
      <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="http://<?php echo $this->_tpl_vars['keyval']; ?>
" target="_blank"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
        <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onkeyup="validateUrl('<?php echo $this->_tpl_vars['keyfldname']; ?>
');" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
        <br>
        <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

        <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '85'): ?><!--Skype-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
        <img src="<?php echo aicrm_imageurl('skype.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" LANGUAGE=javascript align="absmiddle"></img>
        <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="skype:<?php echo $this->_tpl_vars['keyval']; ?>
?call"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
          <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>
          <br>email_message
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
          </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '19' || $this->_tpl_vars['keyid'] == '20'): ?> <!--TextArea/Description-->
    <!-- we will empty the value of ticket and faq comment -->
      <?php if ($this->_tpl_vars['label'] == $this->_tpl_vars['MOD']['LBL_ADD_COMMENT'] || $this->_tpl_vars['label'] == 'Comment Plan' || $this->_tpl_vars['keyfldname'] == 'comments'): ?>
        <?php $this->assign('keyval', ''); ?>
      <?php endif; ?>
      <!--<?php $this->assign('DESCRIPTION_SEPARATOR_STRING', ' '); ?>  -->
      <!--<?php $this->assign('DESCRIPTION_WORDWRAP_WIDTH', '70'); ?> -->

        <?php if ($this->_tpl_vars['MODULE'] == 'Documents'): ?>
          <!--To give hyperlink to URL-->
          <td width="100%" colspan="3" class="dvtCellInfo" align="left"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>
&nbsp;
          </td>
        <?php else: ?>

        <?php if ($this->_tpl_vars['keyfldname'] == 'know_detail'): ?>
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "") : smarty_modifier_replace($_tmp, "\n", "")); ?>

          </span>

        <?php elseif ($this->_tpl_vars['keyfldname'] == 'email_message'): ?>
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "") : smarty_modifier_replace($_tmp, "\n", "")); ?>

          </span>

        <?php elseif ($this->_tpl_vars['keyfldname'] == 'sms_message'): ?>
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "") : smarty_modifier_replace($_tmp, "\n", "")); ?>

          </span>

        <?php elseif ($this->_tpl_vars['keyfldname'] == 'camp_detail'): ?>
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "") : smarty_modifier_replace($_tmp, "\n", "")); ?>

          </span>

        <?php elseif ($this->_tpl_vars['keyfldname'] == 'description'): ?>
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "") : smarty_modifier_replace($_tmp, "\n", "")); ?>

          </span>

        <?php else: ?>
          <td width="100%" colspan="3" class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>

          </span>
        <?php endif; ?>

        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <textarea id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" cols="90" rows="8"><?php echo ((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<br>", "\n") : smarty_modifier_replace($_tmp, "<br>", "\n")); ?>
</textarea>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>
    <?php endif; ?>

    <?php elseif ($this->_tpl_vars['keyid'] == '21' || $this->_tpl_vars['keyid'] == '24' || $this->_tpl_vars['keyid'] == '22'): ?> <!--TextArea/Street-->
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
          <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
            <textarea id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2><?php echo ((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/<br\s*\/>/", "") : smarty_modifier_regex_replace($_tmp, "/<br\s*\/>/", "")); ?>
</textarea>
            <br>
            <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

            <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
          </div>
        </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '50' || $this->_tpl_vars['keyid'] == '73' || $this->_tpl_vars['keyid'] == '51'): ?> <!--AccountPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '58'): ?> <!--CampaingPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '57'): ?> <!--ContactPopup-->
    <!-- Ajax edit link not provided for contact - Reports To -->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '59'): ?> <!--ProductPopup-->
    <!-- <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                <textarea   id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="product_name" tabindex="<?php echo $this->_tpl_vars['vt_tab']; ?>
" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="height:60px"  ><?php echo $this->_tpl_vars['fldvalue']; ?>
</textarea>
                <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
"></td>
              <td><img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
              <td>
                <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
              </td>
            </tr>
          </table>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
    </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '75' || $this->_tpl_vars['keyid'] == '81'): ?> <!--VendorPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

    <?php elseif ($this->_tpl_vars['keyid'] == 76): ?> <!--PotentialPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

    <?php elseif ($this->_tpl_vars['keyid'] == 78): ?> <!--QuotePopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

    <?php elseif ($this->_tpl_vars['keyid'] == 82): ?> <!--Email Body-->
      <td colspan="3" width=100% class="dvtCellInfo" align="left"><div id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
" style="width:100%;height:200px;overflow:hidden;border:1px solid gray" class="detailedViewTextBox" onmouseover="this.className='detailedViewTextBoxOn'" onmouseout="this.className='detailedViewTextBox'"><?php echo $this->_tpl_vars['keyval']; ?>
</div></td>

    <?php elseif ($this->_tpl_vars['keyid'] == 80): ?> <!--SalesOrderPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

    <?php elseif ($this->_tpl_vars['keyid'] == '52' || $this->_tpl_vars['keyid'] == '77'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <select id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
          <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['uid'] => $this->_tpl_vars['arr']):
?>
            <?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
              <option value="<?php echo $this->_tpl_vars['uid']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['sel_value']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['sel_value']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['sel_value']; ?>
<?php endif; ?></option>
            <?php endforeach; endif; unset($_from); ?>
          <?php endforeach; endif; unset($_from); ?>
          </select>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '53'): ?> <!--Assigned To-->
      <td width=25% class="dvtCellInfo" align="left" >
        <?php if ($this->_tpl_vars['keyadmin'] == 1): ?>
          <a href="<?php echo $this->_tpl_vars['keyseclink']['0']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
        <?php else: ?>
          <?php echo $this->_tpl_vars['keyval']; ?>

        <?php endif; ?>

        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input type="hidden" id="hdtxt_<?php echo $this->_tpl_vars['label']; ?>
" value="<?php echo $this->_tpl_vars['keyval']; ?>
"></input>

          <?php if ($this->_tpl_vars['keyoptions']['0'] == 'User'): ?>
            <input name="assigntype" id="assigntype" checked="checked" value="U" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>

            <?php if ($this->_tpl_vars['keyoptions']['2'] != ''): ?>
              <input name="assigntype" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP_NAME']; ?>

            <?php endif; ?>
            <span id="assign_user" style="display: block;">
          <?php else: ?>
            <input name="assigntype" id="assigntype" value="U" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_USER']; ?>

            <input name="assigntype" checked="checked" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
');" type="radio">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_GROUP_NAME']; ?>

            <span id="assign_user" style="display: none;">
          <?php endif; ?>

            <select id="txtbox_U<?php echo $this->_tpl_vars['label']; ?>
" onchange="setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
')" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" class="small">
            <?php $_from = $this->_tpl_vars['keyoptions']['1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['arr']):
?>
              <?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
                <option value="<?php echo $this->_tpl_vars['id']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
              <?php endforeach; endif; unset($_from); ?>
            <?php endforeach; endif; unset($_from); ?>
            </select>
            </span>

          <?php if ($this->_tpl_vars['keyoptions']['0'] == 'Group'): ?>
            <span id="assign_team" style="display: block;">
          <?php else: ?>
            <span id="assign_team" style="display: none;">
          <?php endif; ?>

          <select id="txtbox_G<?php echo $this->_tpl_vars['label']; ?>
" onchange="setSelectValue('<?php echo $this->_tpl_vars['label']; ?>
')" name="assigned_group_id" class="groupname small">
          <?php $_from = $this->_tpl_vars['keyoptions']['2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['arr']):
?>
            <?php $_from = $this->_tpl_vars['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sel_value'] => $this->_tpl_vars['value']):
?>
              <option value="<?php echo $this->_tpl_vars['id']; ?>
" <?php echo $this->_tpl_vars['value']; ?>
><?php echo $this->_tpl_vars['sel_value']; ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
          <?php endforeach; endif; unset($_from); ?>
          </select>
        </span>
        <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
        </div>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '99'): ?><!-- Password Field-->
      <td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['CHANGE_PW_BUTTON']; ?>
</td>

    <?php elseif ($this->_tpl_vars['keyid'] == '56'): ?> <!--CheckBox-->
      <?php if ($this->_tpl_vars['keyfldname'] == 'email_setup' || $this->_tpl_vars['keyfldname'] == 'email_send' || $this->_tpl_vars['keyfldname'] == 'setup_sms' || $this->_tpl_vars['keyfldname'] == 'send_sms' || $this->_tpl_vars['keyfldname'] == 'passed_inspection'): ?>
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" >
      <?php else: ?>
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onMouseOver="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
      <?php endif; ?>
      <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
&nbsp;</span>
      <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
      <?php if ($this->_tpl_vars['keyval'] == 'yes'): ?>
        <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="checkbox" style="border:1px solid #bababa;" checked value="0">
      <?php else: ?>
        <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="border:1px solid #bababa;" value="1">
      <?php endif; ?>
        <br>
        <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

        <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
      </div>
    </td>

    <?php elseif ($this->_tpl_vars['keyid'] == '156'): ?> <!--CheckBox for is admin-->
      <?php if ($_REQUEST['record'] != $this->_tpl_vars['CURRENT_USERID'] && $this->_tpl_vars['keyadmin'] == 1): ?>
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onMouseOver="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');"><span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['keyval']]; ?>
<?php elseif ($this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']] != ''): ?><?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['keyval']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['keyval']; ?>
<?php endif; ?>&nbsp;</span>
          <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <?php if ($this->_tpl_vars['keyval'] == 'on'): ?>
            <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="checkbox" style="border:1px solid #bababa;" checked value="1">
          <?php else: ?>
            <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="border:1px solid #bababa;" value="0">
          <?php endif; ?>
          <br>
          <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

          <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>
          </div>
      <?php else: ?>
        <td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>

      <?php endif; ?>
      </td>

    <?php elseif ($this->_tpl_vars['keyid'] == 83): ?><!-- Handle the Tax in Inventory -->
      <?php $_from = $this->_tpl_vars['TAX_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['tax']):
?>
        <td align="right" class="dvtCellLabel">
        <?php echo $this->_tpl_vars['tax']['taxlabel']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

        </td>
        <td class="dvtCellInfo" align="left">
        <?php echo $this->_tpl_vars['tax']['percentage']; ?>

        </td>
        <td colspan="2" class="dvtCellInfo">&nbsp;</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>

    <?php elseif ($this->_tpl_vars['keyid'] == 5): ?>
    
      <?php if (empty ( $this->_tpl_vars['dateFormat'] )): ?>
        <?php $this->assign('dateFormat', parse_calendardate($this->_tpl_vars['APP']['NTC_DATE_FORMAT'])); ?>
      <?php endif; ?>

      <?php if ($this->_tpl_vars['keyfldname'] == 'approved_date'): ?>
       <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">
      <?php else: ?>
       <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" onmouseover="hndMouseOver(<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['label']; ?>
');" onmouseout="fnhide('crmspanid');">
      <?php endif; ?>

      <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>

      <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
        <input style="border: 1px solid #bababa;border-radius: 3px;color: #2b2f33;background-color: #FFF;padding: 2px;line-height: 20px;" size="11" maxlength="10" type="text" id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" maxlength='100' value="<?php echo ((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/[^-]*(--)[^-]*$/', '') : smarty_modifier_regex_replace($_tmp, '/[^-]*(--)[^-]*$/', '')); ?>
"></input>
        <img src="<?php echo aicrm_imageurl('btnL3Calendar.gif', $this->_tpl_vars['THEME']); ?>
" id="jscal_trigger_<?php echo $this->_tpl_vars['keyfldname']; ?>
" style="vertical-align: middle;">
      <br>
      <input name="button_<?php echo $this->_tpl_vars['label']; ?>
" type="button" class="crmbutton small save" value="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_LABEL']; ?>
" onclick="dtlViewAjaxSave('<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['keyid']; ?>
,'<?php echo $this->_tpl_vars['keytblname']; ?>
','<?php echo $this->_tpl_vars['keyfldname']; ?>
','<?php echo $this->_tpl_vars['ID']; ?>
');fnhide('crmspanid');"/> <?php echo $this->_tpl_vars['APP']['LBL_OR']; ?>

      <a href="javascript:;" onclick="hndCancel('dtlview_<?php echo $this->_tpl_vars['label']; ?>
','editarea_<?php echo $this->_tpl_vars['label']; ?>
','<?php echo $this->_tpl_vars['label']; ?>
')" class="link"><?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
</a>

      <script type="text/javascript">
        Calendar.setup ({
        inputField : "txtbox_<?php echo $this->_tpl_vars['label']; ?>
", ifFormat : '<?php echo $this->_tpl_vars['dateFormat']; ?>
', showsTime : false, button : "jscal_trigger_<?php echo $this->_tpl_vars['keyfldname']; ?>
", singleClick : true, step : 1
        })
      </script>

      </div>
    </td>

    <?php elseif ($this->_tpl_vars['keyid'] == 69): ?><!-- for Image Reflection -->
      <td align="left" width=25%"><?php echo $this->_tpl_vars['keyval']; ?>
</td>
    <?php elseif ($this->_tpl_vars['keyid'] == 997): ?>
      <td  align="left" width=25%">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>
</td>
    <?php elseif ($this->_tpl_vars['keyid'] == 998): ?>
      <td  align="left" width=25%">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>
</td>
    <?php elseif ($this->_tpl_vars['keyid'] == 999): ?>
      <td  align="left" width=25%">&nbsp;<?php echo $this->_tpl_vars['keyval']; ?>
</td>
    <?php else: ?>
      <td class="dvtCellInfo" align="left" width=25%"><?php echo $this->_tpl_vars['keyval']; ?>
</td>
    <?php endif; ?>
