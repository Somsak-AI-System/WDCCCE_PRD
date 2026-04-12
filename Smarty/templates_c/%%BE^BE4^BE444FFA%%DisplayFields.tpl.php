<?php /* Smarty version 2.6.18, created on 2026-04-08 16:50:50
         compiled from DisplayFields.tpl */ ?>

<?php $this->assign('fromlink', ""); ?>

<!-- Added this file to display the fields in Create Entity page based on ui types  -->
<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['label'] => $this->_tpl_vars['subdata']):
?>
	<?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
		<?php if ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องขอ'): ?>
			<tr class='case-tr-request' style="height:25px">
		<?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องแจ้งซ่อม' || $this->_tpl_vars['header'] == 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'): ?>
			<tr class='case-tr-service' style="height:25px">
		<?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องเรียน'): ?>
			<tr class='case-tr-complain' style="height:25px">			
		<?php else: ?>
			<tr style="height:25px">
		<?php endif; ?>		
	<?php else: ?>
		<?php if ($this->_tpl_vars['header'] == 'Product Details'): ?>
			<tr>
		<?php else: ?>
			<tr style="height:25px">
		<?php endif; ?>
	<?php endif; ?>

	<?php $_from = $this->_tpl_vars['subdata']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mainlabel'] => $this->_tpl_vars['maindata']):
?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'EditViewUI.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endforeach; endif; unset($_from); ?>
   
   </tr>
<?php endforeach; endif; unset($_from); ?>