<?php /* Smarty version 2.6.18, created on 2026-04-08 16:51:13
         compiled from DetailViewFields.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'DetailViewFields.tpl', 29, false),array('modifier', 'count', 'DetailViewFields.tpl', 35, false),array('modifier', 'aicrm_imageurl', 'DetailViewFields.tpl', 47, false),array('modifier', 'cat', 'DetailViewFields.tpl', 545, false),array('modifier', 'replace', 'DetailViewFields.tpl', 550, false),array('modifier', 'regex_replace', 'DetailViewFields.tpl', 569, false),)), $this); ?>

<!-- This file is used to display the fields based on the ui type in detailview -->
		<?php if ($this->_tpl_vars['keyid'] == '1' || $this->_tpl_vars['keyid'] == 2 || $this->_tpl_vars['keyid'] == '11' || $this->_tpl_vars['keyid'] == '7' || $this->_tpl_vars['keyid'] == '9' || $this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '71' || $this->_tpl_vars['keyid'] == '72' || $this->_tpl_vars['keyid'] == '255'): ?> <!--TextBox-->
			<td width=25% class="dvtCellInfo" align="left">
				<?php if ($this->_tpl_vars['keyid'] == '55' || $this->_tpl_vars['keyid'] == '255' && ( $this->_tpl_vars['keyfldname'] != 'lastname' )): ?><!--SalutationSymbol-->
					<?php if ($this->_tpl_vars['keyaccess'] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
						<font color='red'><?php echo $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']; ?>
</font>
					<?php else: ?>
						<?php echo $this->_tpl_vars['keysalut']; ?>

					<?php endif; ?>
								<?php endif; ?>

        <?php if ($this->_tpl_vars['keyid'] == 7): ?>
          <span id ="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
      	    <!-- <span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : number_format($_tmp, 2, ".", ",")); ?>
</span> -->
        <?php else: ?>
					   <span id ="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span>
				<?php endif; ?>
        
        <?php if ($this->_tpl_vars['keyid'] == '71' && $this->_tpl_vars['keyfldname'] == 'unit_price'): ?>
            <?php if (count($this->_tpl_vars['PRICE_DETAILS']) > 0): ?>
              <span id="multiple_currencies" width="38%" style="align:right;">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="toggleShowHide('currency_class','multiple_currencies');"><?php echo $this->_tpl_vars['APP']['LBL_MORE_CURRENCIES']; ?>
 &raquo;</a>
              </span>

            <div id="currency_class" class="multiCurrencyDetailUI">
            <table width="100%" height="100%" class="small" cellpadding="5">
              <tr class="detailedViewHeader">
                <th colspan="2">
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
                <th colspan="2"><?php echo $this->_tpl_vars['APP']['LBL_PRICE']; ?>
</th>
              </tr>
              <?php $_from = $this->_tpl_vars['PRICE_DETAILS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count'] => $this->_tpl_vars['price']):
?>
                <tr>
                  <td class="dvtCellLabel" width="40%">
                  <?php echo $this->_tpl_vars['price']['currencylabel']; ?>
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

    <?php elseif ($this->_tpl_vars['keyid'] == '73'): ?> <!--AccountPopup-->
      <?php if ($this->_tpl_vars['MODULE'] == 'Projects'): ?>
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">
          <?php if ($this->_tpl_vars['display_acc'] == 'yes'): ?>
            <a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
          <?php else: ?>
            <?php echo $this->_tpl_vars['keyval']; ?>

          <?php endif; ?>
        </td>
      <?php else: ?>
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>
      <?php endif; ?>

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

    <?php elseif ($this->_tpl_vars['keyid'] == 902): ?>
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
    <?php elseif ($this->_tpl_vars['keyid'] == 903): ?>
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
" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=accountfield&field=<?php echo $this->_tpl_vars['keyfldname']; ?>
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
      </td>
    
    <?php elseif ($this->_tpl_vars['keyid'] == '939'): ?>
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span></td>
    
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
      
		<?php elseif ($this->_tpl_vars['keyid'] == '13'): ?> <!--Email-->
			<td width=25% class="dvtCellInfo" align="left">
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
			</td>

		<?php elseif ($this->_tpl_vars['keyid'] == '15' || $this->_tpl_vars['keyid'] == '16'): ?> 
      <td width=25% class="dvtCellInfo" align="left">&nbsp;
        <?php $_from = $this->_tpl_vars['keyoptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arr']):
?>
          <?php if ($this->_tpl_vars['arr'][0] == $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']): ?>
            <?php $this->assign('keyval', $this->_tpl_vars['APP']['LBL_NOT_ACCESSIBLE']); ?>
            <?php $this->assign('fontval', 'red'); ?>
          <?php else: ?>
            <?php $this->assign('fontval', ''); ?>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        <font color="<?php echo $this->_tpl_vars['fontval']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</font>
      </td>

		<?php elseif ($this->_tpl_vars['keyid'] == '33'): ?>
			<td width=25% class="dvtCellInfo" align="left">
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

			</td>

		<?php elseif ($this->_tpl_vars['keyid'] == '17'): ?> <!--WebSite-->
			<td width=25% class="dvtCellInfo" align="left">&nbsp;<a href="http://<?php echo $this->_tpl_vars['keyval']; ?>
" target="_blank"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>

		<?php elseif ($this->_tpl_vars['keyid'] == '85'): ?><!--Skype-->
			<td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
">
				&nbsp;<img src="<?php echo aicrm_imageurl('skype.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SKYPE']; ?>
" LANGUAGE=javascript align="absmiddle"></img>
				<span id="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><a href="skype:<?php echo $this->_tpl_vars['keyval']; ?>
?call"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
			</td>

		<?php elseif ($this->_tpl_vars['keyid'] == '19' || $this->_tpl_vars['keyid'] == '20'): ?> <!--TextArea/Description-->
			<?php if ($this->_tpl_vars['label'] == $this->_tpl_vars['MOD']['LBL_ADD_COMMENT'] || $this->_tpl_vars['label'] == 'Comment Plan' || $this->_tpl_vars['keyfldname'] == 'comments'): ?>
        <?php $this->assign('keyval', ''); ?>
      <?php endif; ?>

      <?php if ($this->_tpl_vars['keyfldname'] == 'quota_notapprove'): ?>
        <td width=100% class="dvtCellInfo" align="left"colspan="3">
          <font color="#FF0000"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>

          </font>
        </td>
      <?php elseif ($this->_tpl_vars['keyfldname'] == 'quota_cancel'): ?>
        <td width=100% class="dvtCellInfo" align="left"colspan="3">
          <font color="#FF0000"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>

          </font>
        </td>
      <?php else: ?>
  			<td width=100% class="dvtCellInfo" align="left"colspan="3">
  				<!--To give hyperlink to URL-->
  				<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['keyval'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>") : smarty_modifier_regex_replace($_tmp, "/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>")))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"") : smarty_modifier_regex_replace($_tmp, "/,\"|\.\"|\)\"|\)\.\"|\.\)\"/", "\"")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br>&nbsp;") : smarty_modifier_replace($_tmp, "\n", "<br>&nbsp;")); ?>

  			</td>
      <?php endif; ?>

		<?php elseif ($this->_tpl_vars['keyid'] == '21' || $this->_tpl_vars['keyid'] == '24' || $this->_tpl_vars['keyid'] == '22'): ?> <!--TextArea/Street-->
			 	
        <td width=25% class="dvtCellInfo" align="left">&nbsp;<span id ="dtlview_<?php echo $this->_tpl_vars['label']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</span></td>

		<?php elseif ($this->_tpl_vars['keyid'] == '50' || $this->_tpl_vars['keyid'] == '51' || $this->_tpl_vars['keyid'] == '57' || $this->_tpl_vars['keyid'] == '59' || $this->_tpl_vars['keyid'] == '75' || $this->_tpl_vars['keyid'] == '81' || $this->_tpl_vars['keyid'] == '76' || $this->_tpl_vars['keyid'] == '78' || $this->_tpl_vars['keyid'] == '80'): ?> <!--AccountPopup-->

    <?php if ($this->_tpl_vars['keyid'] == '57' && $this->_tpl_vars['MODULE'] == 'Projects'): ?>
      <td width=25% class="dvtCellInfo" align="left">
        <?php if ($this->_tpl_vars['display_con'] == 'yes'): ?>
          <a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
        <?php else: ?>
          <?php echo $this->_tpl_vars['keyval']; ?>

        <?php endif; ?>
      </td>
    <?php else: ?>
			<td width=25% class="dvtCellInfo" align="left"><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></td>
    <?php endif; ?>
		
    <?php elseif ($this->_tpl_vars['keyid'] == 82): ?> <!--Email Body-->
			<td colspan="3" width=100% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>
</td>

		<?php elseif ($this->_tpl_vars['keyid'] == '53'): ?> <!--Assigned To-->
      <td width=25% class="dvtCellInfo" align="left">
        <?php if ($this->_tpl_vars['keyseclink'] == ''): ?>
            <?php echo $this->_tpl_vars['keyval']; ?>

        <?php else: ?>
           	<a href="<?php echo $this->_tpl_vars['keyseclink']['0']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a>
        <?php endif; ?>
	     &nbsp;
      </td>

		<?php elseif ($this->_tpl_vars['keyid'] == '56'): ?> <!--CheckBox-->
			<td width=25% class="dvtCellInfo" align="left"><?php echo $this->_tpl_vars['keyval']; ?>
&nbsp;</td>
    
    <?php elseif ($this->_tpl_vars['keyid'] == '58'): ?>
      
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_<?php echo $this->_tpl_vars['label']; ?>
" ><a href="<?php echo $this->_tpl_vars['keyseclink']; ?>
"><?php echo $this->_tpl_vars['keyval']; ?>
</a></span>
        <div id="editarea_<?php echo $this->_tpl_vars['label']; ?>
" style="display:none;">
          <input id="popuptxt_<?php echo $this->_tpl_vars['label']; ?>
" name="campaign_name" readonly type="text" value="<?php echo $this->_tpl_vars['keyval']; ?>
">
          <input id="txtbox_<?php echo $this->_tpl_vars['label']; ?>
" name="<?php echo $this->_tpl_vars['keyfldname']; ?>
" type="hidden" value="<?php echo $this->_tpl_vars['keysecid']; ?>
">&nbsp;
          <img src="<?php echo aicrm_imageurl('select.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaign&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="<?php echo aicrm_imageurl('clear_field.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLEAR']; ?>
" LANGUAGE=javascript onClick="this.form.campaignid.value=''; this.form.campaign_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
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

		<?php elseif ($this->_tpl_vars['keyid'] == 83): ?><!-- Handle the Tax in Inventory -->
				<td align="right" class="dvtCellLabel">
					<?php echo $this->_tpl_vars['APP']['LBL_VAT']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

				</td>
				<td class="dvtCellInfo" align="left">&nbsp;
					<?php echo $this->_tpl_vars['VAT_TAX']; ?>

				</td>
				<td colspan="2" class="dvtCellInfo">&nbsp;</td>
			</tr>
			<!--<tr>
				<td align="right" class="dvtCellLabel">
					<?php echo $this->_tpl_vars['APP']['LBL_SALES']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_TAX']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

				</td>
				<td class="dvtCellInfo" align="left">&nbsp;
					<?php echo $this->_tpl_vars['SALES_TAX']; ?>

				</td>
				<td colspan="2" class="dvtCellInfo">&nbsp;</td>
			</tr>
			<tr>
				<td align="right" class="dvtCellLabel">
					<?php echo $this->_tpl_vars['APP']['LBL_SERVICE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_TAX']; ?>
 <?php echo $this->_tpl_vars['APP']['COVERED_PERCENTAGE']; ?>

				</td>
				<td class="dvtCellInfo" align="left" >&nbsp;
					<?php echo $this->_tpl_vars['SERVICE_TAX']; ?>

				</td>-->

		<?php elseif ($this->_tpl_vars['keyid'] == 69): ?><!-- for Image Reflection -->
			<td align="left" width=25%"><?php echo $this->_tpl_vars['keyval']; ?>
</td>
		<?php else: ?>
			<td class="dvtCellInfo" align="left" width=25%"><?php echo $this->_tpl_vars['keyval']; ?>
</td>
		<?php endif; ?>