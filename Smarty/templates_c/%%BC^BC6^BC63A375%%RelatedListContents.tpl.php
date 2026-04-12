<?php /* Smarty version 2.6.18, created on 2026-04-08 16:53:26
         compiled from RelatedListContents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'RelatedListContents.tpl', 25, false),)), $this); ?>

<script type='text/javascript' src='include/js/Mail.js'></script>
<?php if ($this->_tpl_vars['SinglePane_View'] == 'true'): ?>
	<?php $this->assign('return_modname', 'DetailView'); ?>
<?php else: ?>
	<?php $this->assign('return_modname', 'CallRelatedList'); ?>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['RELATEDLISTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>

<?php $this->assign('rel_mod', $this->_tpl_vars['header']); ?>
<?php $this->assign('HEADERLABEL', getTranslatedString($this->_tpl_vars['header'], $this->_tpl_vars['rel_mod'])); ?>

<table border=0 cellspacing=0 cellpadding=0 width=100% class="small" style="border-bottom:1px solid #999999;padding:5px;">
	<tr>
		<td  valign=bottom ><b><?php echo $this->_tpl_vars['HEADERLABEL']; ?>
</b> 
			<?php if ($this->_tpl_vars['MODULE'] == 'Campaigns' && ( $this->_tpl_vars['rel_mod'] == 'Contacts' )): ?>
					<br><br><?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
: <a href="javascript:;" onclick="clear_checked_all('<?php echo $this->_tpl_vars['rel_mod']; ?>
');"><?php echo $this->_tpl_vars['APP']['LBL_NONE_NO_LINE']; ?>
</a>
			<?php endif; ?> 
		</td>
		<?php if ($this->_tpl_vars['detail'] != ''): ?>
		<td align=center><?php echo $this->_tpl_vars['detail']['navigation']['0']; ?>
</td>
			<?php echo $this->_tpl_vars['detail']['navigation']['1']; ?>

		<?php endif; ?>
		<td align=right>
			<?php echo $this->_tpl_vars['detail']['CUSTOM_BUTTON']; ?>

		</td>

			<?php if ($this->_tpl_vars['header'] == 'Contacts' && $this->_tpl_vars['MODULE'] != 'Campaigns' && $this->_tpl_vars['MODULE'] != 'Accounts' && $this->_tpl_vars['MODULE'] != 'Potentials' && $this->_tpl_vars['MODULE'] != 'Products'): ?>
				<?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
					<input alt="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_CONTACT_BUTTON_LABEL']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_CONTACT_BUTTON_LABEL']; ?>
" accessKey="" class="crmbutton small edit" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_BUTTON_LABEL']; ?>
 <?php echo $this->_tpl_vars['APP']['Contacts']; ?>
" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=<?php echo $this->_tpl_vars['ID']; ?>
<?php echo $this->_tpl_vars['search_string']; ?>
","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button"></td>
				
				<?php elseif ($this->_tpl_vars['MODULE'] != 'SmartSms' && $this->_tpl_vars['MODULE'] != 'Smartemail' && $this->_tpl_vars['MODULE'] != 'Smartquestionnaire'): ?>
					<input title="<?php echo $this->_tpl_vars['APP']['LBL_ADD_NEW']; ?>
 <?php echo $this->_tpl_vars['APP']['Contact']; ?>
" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_ADD_NEW']; ?>
 <?php echo $this->_tpl_vars['APP']['Contact']; ?>
"></td>
				<?php endif; ?>

			<?php elseif ($this->_tpl_vars['header'] == 'Users'): ?>
                    <?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
						<input title="Change" accessKey="" tabindex="2" type="button" class="crmbutton small edit" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECT_USER_BUTTON_LABEL']; ?>
" name="button" LANGUAGE=javascript onclick='return window.open("index.php?module=Users&return_module=Calendar&return_action=<?php echo $this->_tpl_vars['return_modname']; ?>
&activity_mode=Events&action=Popup&popuptype=detailview&form=EditView&form_submit=true&select=enable&return_id=<?php echo $this->_tpl_vars['ID']; ?>
&recordid=<?php echo $this->_tpl_vars['ID']; ?>
","test","width=640,height=525,resizable=0,scrollbars=0")';>
                    <?php endif; ?>
            <?php elseif ($this->_tpl_vars['header'] == 'Activity History'): ?>
                    &nbsp;</td>
            <?php endif; ?>
	</tr>
</table>
<?php $this->assign('check_status', $this->_tpl_vars['detail']); ?>
<?php if ($this->_tpl_vars['detail'] != '' && $this->_tpl_vars['detail']['header'] != ''): ?>
	<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>
		<?php if ($this->_tpl_vars['header'] == 'header'): ?>
			<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#eaeaea;" class="small">
				<tr style="height:25px" bgcolor=white>
                                <?php if ($this->_tpl_vars['MODULE'] == 'Campaigns' && ( $this->_tpl_vars['rel_mod'] == 'Contacts' || $this->_tpl_vars['rel_mod'] == 'Leads' )): ?>
                                        <td class="lvtCol"><input name ="<?php echo $this->_tpl_vars['rel_mod']; ?>
_selectall" onclick="rel_toggleSelect(this.checked,'<?php echo $this->_tpl_vars['rel_mod']; ?>
_selected_id','<?php echo $this->_tpl_vars['rel_mod']; ?>
');"  type="checkbox"></td>
                                <?php endif; ?>
				<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['headerfields']):
?>
					<td class="lvtCol" align="left"><?php echo $this->_tpl_vars['headerfields']; ?>
</td>
				<?php endforeach; endif; unset($_from); ?>
                                </tr>
		<?php elseif ($this->_tpl_vars['header'] == 'entries'): ?>
			<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>
				<tr bgcolor=white>
                    <?php if ($this->_tpl_vars['MODULE'] == 'Campaigns' && ( $this->_tpl_vars['rel_mod'] == 'Contacts' || $this->_tpl_vars['rel_mod'] == 'Leads' )): ?>
                        <td><input name="<?php echo $this->_tpl_vars['rel_mod']; ?>
_selected_id" id="<?php echo $this->_tpl_vars['header']; ?>
" value="<?php echo $this->_tpl_vars['header']; ?>
" onclick="rel_check_object(this,'<?php echo $this->_tpl_vars['rel_mod']; ?>
');" toggleselectall(this.name,="" selectall="" )="" type="checkbox"  <?php echo $this->_tpl_vars['check_status']['checked'][$this->_tpl_vars['header']]; ?>
></td>
                    <?php endif; ?>
				<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['listfields']):
?>
					                      <td align="left" onmouseover="vtlib_listview.trigger('cell.onmouseover', $(this))" onmouseout="vtlib_listview.trigger('cell.onmouseout', $(this))"><?php echo $this->_tpl_vars['listfields']; ?>
</td>
                     				<?php endforeach; endif; unset($_from); ?>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
			</table>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
<?php else: ?>
	<table style="background-color:#eaeaea;color:#000000" border="0" cellpadding="3" cellspacing="1" width="100%" class="small">
		<tr style="height: 25px;" bgcolor="white">
			<td><i><?php echo $this->_tpl_vars['APP']['LBL_NONE_INCLUDED']; ?>
</i></td>
		</tr>
	</table>
<?php endif; ?>
<br><br>
<?php if ($this->_tpl_vars['MODULE'] == 'Campaigns' && ( $this->_tpl_vars['rel_mod'] == 'Contacts' || $this->_tpl_vars['rel_mod'] == 'Leads' )): ?>
<script>
rel_default_togglestate('<?php echo $this->_tpl_vars['rel_mod']; ?>
');
</script>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>