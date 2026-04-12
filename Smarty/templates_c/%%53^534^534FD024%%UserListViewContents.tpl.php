<?php /* Smarty version 2.6.18, created on 2026-04-09 16:31:39
         compiled from UserListViewContents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'UserListViewContents.tpl', 76, false),array('modifier', 'strip_tags', 'UserListViewContents.tpl', 94, false),array('modifier', 'trim', 'UserListViewContents.tpl', 94, false),)), $this); ?>

<!--<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
<tr>
	<td class="big"><strong><?php echo $this->_tpl_vars['MOD']['LBL_USERS_LIST']; ?>
</strong></td>
	<td class="small" align=right>&nbsp;</td>
</tr>
</table>
					
<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTableTopButtons">
<?php if ($this->_tpl_vars['ERROR_MSG'] != ''): ?>
<tr>
	<?php echo $this->_tpl_vars['ERROR_MSG']; ?>

</tr>
<?php endif; ?>
</table>
						
<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTable">
<tr>
    <td nowrap align="left" colspan="8">
        <table border=0 cellspacing=0 cellpadding=0 class="small" width=100%>
            <tr>
            	<td><?php echo $this->_tpl_vars['recordListRange']; ?>
&nbsp;&nbsp;
                </td>
                <td><?php echo $this->_tpl_vars['NAVIGATION']; ?>

                </td>
            </tr>
        </table>
    </td>
    <td colspan="1" align="right">
    <input title="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_USER_BUTTON_TITLE']; ?>
" accessyKey="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_USER_BUTTON_KEY']; ?>
" type="submit" name="button" value="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_USER_BUTTON_LABEL']; ?>
" class="crmButton create small">
    </td>
</tr>
<tr>
	<td class="colHeader small" valign=top>#</td>
	<td class="colHeader small" valign=top><?php echo $this->_tpl_vars['APP']['Tools']; ?>
</td>
	<td class="colHeader small" valign=top><?php echo $this->_tpl_vars['LIST_HEADER']['3']; ?>
</td>
	<td class="colHeader small" valign=top><?php echo $this->_tpl_vars['LIST_HEADER']['5']; ?>
</td>
    <td class="colHeader small" valign=top><?php echo $this->_tpl_vars['LIST_HEADER']['6']; ?>
</td>
	<td class="colHeader small" valign=top><?php echo $this->_tpl_vars['LIST_HEADER']['7']; ?>
</td>
	
    <td class="colHeader small" valign=top><?php echo $this->_tpl_vars['LIST_HEADER']['8']; ?>
</td>
    <td class="colHeader small" valign=top><?php echo $this->_tpl_vars['LIST_HEADER']['9']; ?>
</td>
	<td class="colHeader small" valign=top><?php echo $this->_tpl_vars['LIST_HEADER']['4']; ?>
</td>
</tr>
        <?php 
        	global $list_max_entries_per_page;
        	$i=0;
         ?>
	<?php $_from = $this->_tpl_vars['LIST_ENTRIES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['userlist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['userlist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['userid'] => $this->_tpl_vars['listvalues']):
        $this->_foreach['userlist']['iteration']++;
?>
		<?php $this->assign('flag', 0); ?>
        <?php 
        	$i=$i+1;
            //echo $_REQUEST["start"];
            if($_REQUEST['start']<2){
            	$page_nn=0;
            }else{
           	 $page_nn=($_REQUEST["start"]-1)*$list_max_entries_per_page;
            }
         ?>
      
<tr>
	<td class="listTableRow small" valign=top><?php echo ($i+$page_nn); ?></td>
	<td class="listTableRow small" nowrap valign=top><a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record=<?php echo $this->_tpl_vars['userid']; ?>
"><img src="<?php echo aicrm_imageurl('editfield.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
" border="0"></a>
	<?php $_from = $this->_tpl_vars['USERNODELETE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['name']):
?>
		<?php if ($this->_tpl_vars['userid'] == $this->_tpl_vars['id'] || $this->_tpl_vars['userid'] == $this->_tpl_vars['CURRENT_USERID']): ?>
			<?php $this->assign('flag', 1); ?>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['flag'] == 0): ?>
		<img src="<?php echo aicrm_imageurl('delete.gif', $this->_tpl_vars['THEME']); ?>
" onclick="deleteUser(this,'<?php echo $this->_tpl_vars['userid']; ?>
')" border="0"  alt="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON']; ?>
" style="cursor:pointer;"/>
	<?php endif; ?>
	<a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record=<?php echo $this->_tpl_vars['userid']; ?>
&isDuplicate=true"><img src="<?php echo aicrm_imageurl('settingsActBtnDuplicate.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON']; ?>
" border="0"></a>
</td>
	<td class="listTableRow small" valign=top><b><a href="index.php?module=Users&action=DetailView&parenttab=Settings&record=<?php echo $this->_tpl_vars['userid']; ?>
"> <?php echo $this->_tpl_vars['listvalues']['3']; ?>
 </a></b><br><a href="index.php?module=Users&action=DetailView&parenttab=Settings&record=<?php echo $this->_tpl_vars['userid']; ?>
"> <?php echo $this->_tpl_vars['listvalues']['1']; ?>
 <?php echo $this->_tpl_vars['listvalues']['0']; ?>
</a> (<?php echo $this->_tpl_vars['listvalues']['2']; ?>
)</td>
	<td class="listTableRow small" valign=top><?php echo $this->_tpl_vars['listvalues']['5']; ?>
&nbsp;</td>
    <td class="listTableRow small" valign=top><?php echo $this->_tpl_vars['listvalues']['6']; ?>
&nbsp;</td>
	<td class="listTableRow small" valign=top><?php echo $this->_tpl_vars['listvalues']['7']; ?>
&nbsp;</td>
	
    <td class="listTableRow small" valign=top><?php echo $this->_tpl_vars['listvalues']['8']; ?>
&nbsp;</td>
    <td class="listTableRow small" valign=top><?php echo $this->_tpl_vars['listvalues']['9']; ?>
&nbsp;</td>
	<?php if (trim(smarty_modifier_strip_tags($this->_tpl_vars['listvalues']['4'])) == 'Active'): ?>
	<td class="listTableRow small active" valign=top><?php echo $this->_tpl_vars['APP']['Active']; ?>
</td>
	<?php else: ?>
	<td class="listTableRow small inactive" valign=top><?php echo $this->_tpl_vars['APP']['Inactive']; ?>
</td>
	<?php endif; ?>	

</tr>
	<?php endforeach; endif; unset($_from); ?>
<tr>
    <td nowrap align="left" colspan="8">
        <table border=0 cellspacing=0 cellpadding=0 class="small" width=100%>
            <tr>
            	<td><?php echo $this->_tpl_vars['recordListRange']; ?>
&nbsp;&nbsp;
                </td>
                <td><?php echo $this->_tpl_vars['NAVIGATION']; ?>

                </td>
            </tr>
        </table>
    </td>
    <td colspan="1" align="right">
    <input title="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_USER_BUTTON_TITLE']; ?>
" accessyKey="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_USER_BUTTON_KEY']; ?>
" type="submit" name="button" value="<?php echo $this->_tpl_vars['CMOD']['LBL_NEW_USER_BUTTON_LABEL']; ?>
" class="crmButton create small">
    </td>
</tr> 
</table>
<table border=0 cellspacing=0 cellpadding=5 width=100% >
	<tr><td class="small" nowrap align=right><a href="#top"><?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>
</a></td></tr>
</table>-->

<table id="dg" title="" style="width:100%;height:613px" data-options="singleSelect:true"  toolbar="#toolbar"></table>
<div id="toolbar" style="text-align:right"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a></div>
<table border=0 cellspacing=0 cellpadding=5 width=100% >
	<tr><td class="small" nowrap align=right><a href="#top"><?php echo $this->_tpl_vars['MOD']['LBL_SCROLL']; ?>
</a></td></tr>
</table>