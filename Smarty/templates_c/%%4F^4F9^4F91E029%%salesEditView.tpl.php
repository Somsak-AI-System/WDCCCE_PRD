<?php /* Smarty version 2.6.18, created on 2026-04-08 16:59:27
         compiled from salesEditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtlib_purify', 'salesEditView.tpl', 50, false),array('modifier', 'aicrm_imageurl', 'salesEditView.tpl', 83, false),)), $this); ?>
<script type="text/javascript" src="asset/js/polyfiller.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-<?php echo $this->_tpl_vars['CALENDAR_LANG']; ?>
.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="include/js/FieldDependencies.js"></script>

<!-- <script type="text/javascript" src="include/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script> -->

<script type="text/javascript" src="asset/js/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>

<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">


<script type="text/javascript">
	jQuery.noConflict();
</script>

<?php if ($this->_tpl_vars['PICKIST_DEPENDENCY_DATASOURCE'] != ''): ?>
<script type="text/javascript">
	jQuery(document).ready(function() { (new FieldDependencies(<?php echo $this->_tpl_vars['PICKIST_DEPENDENCY_DATASOURCE']; ?>
)).init() });
</script>
<?php endif; ?>

<!-- overriding the pre-defined #company to avoid clash with aicrm_field in the view -->
<?php echo '
<style type=\'text/css\'>
#company {
	height: auto;
	width: 90%;
}
</style>
'; ?>


<script type="text/javascript">
var gVTModule = '<?php echo vtlib_purify($_REQUEST['module']); ?>
';
function sensex_info()
{
        var Ticker = $('tickersymbol').value;
        if(Ticker!='')
        {
                $("vtbusy_info").style.display="inline";
                new Ajax.Request(
                      'index.php',
                      {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=Tickerdetail&tickersymbol='+Ticker,
                                onComplete: function(response) {
                                        $('autocom').innerHTML = response.responseText;
                                        $('autocom').style.display="block";
                                        $("vtbusy_info").style.display="none";
                                }
                        }
                );
        }
}
function AddressSync(Addform,id)
{
	checkAddress(Addform,id);
}

</script>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Buttons_List1.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table border=0 cellspacing=0 cellpadding=0 width=99% align=center>
   <tr>
	<td valign=top><img src="<?php echo aicrm_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>

	<td class="showPanelBg" valign=top width=100%>
				<div class="small" style="padding:20px">
						<?php $this->assign('SINGLE_MOD_LABEL', $this->_tpl_vars['SINGLE_MOD']); ?>
			<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['SINGLE_MOD']]): ?> <?php $this->assign('SINGLE_MOD_LABEL', $this->_tpl_vars['APP']['SINGLE_MOD']); ?> <?php endif; ?>

			<?php if ($this->_tpl_vars['OP_MODE'] == 'edit_view'): ?>
				<?php $this->assign('USE_ID_VALUE', $this->_tpl_vars['MOD_SEQ_ID']); ?>
		  		<?php if ($this->_tpl_vars['USE_ID_VALUE'] == ''): ?> <?php $this->assign('USE_ID_VALUE', $this->_tpl_vars['ID']); ?> <?php endif; ?>
				<span class="lvtHeaderText"><font color="purple">[ <?php echo $this->_tpl_vars['USE_ID_VALUE']; ?>
 ] </font><?php echo $this->_tpl_vars['NAME']; ?>
 - <?php echo $this->_tpl_vars['APP']['LBL_EDITING']; ?>
 <?php echo $this->_tpl_vars['SINGLE_MOD_LABEL']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</span> <br>
				<?php echo $this->_tpl_vars['UPDATEINFO']; ?>

			<?php endif; ?>
			<?php if ($this->_tpl_vars['OP_MODE'] == 'create_view'): ?>
				<span class="lvtHeaderText"><?php echo $this->_tpl_vars['APP']['LBL_CREATING']; ?>
 <?php echo $this->_tpl_vars['SINGLE_MOD_LABEL']; ?>
</span> <br>
			<?php endif; ?>

			<!-- <hr noshade size=1> -->
			<hr style="border: 1px solid #F7F7F7;">
			<br>

			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'EditViewHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

						<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
			   <tr>
				<td>
					<table border=0 cellspacing=0 cellpadding=4 width=100% class="small">
					   <tr>
						<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
						<td class="dvtSelectedCell" align=center nowrap><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['SINGLE_MOD']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</td>
						<!-- <td class="dvtTabCache" style="width:10px">&nbsp;</td> -->
						<!-- <td class="dvtTabCache" style="width:100%">&nbsp;</td> -->
						<td class="dvtTabCache" align="center" style="width:70%">
							<?php if ($this->_tpl_vars['MODULE'] == 'Smartemail'): ?>
	                           <font color="#FF0000" size="+1">*** การจัดส่ง E-mail ระบบจะทำการจัดส่งวันละไม่เกิน 40,000 ฉบับ หากรายการ Smart Email ที่ทำการตั้งค่าไว้มีจำนวนมากกว่าที่กำหนด ระบบจะทำการจัดส่งส่วนที่เหลือให้อีกครั้งในวันถัดไป ***</font>
	                        <?php endif; ?>

	                        <?php if ($this->_tpl_vars['MODULE'] == 'SmartSms'): ?>
	                       		<span style="width: 100%"><font color="#FF0000" size="+1">*** การจัดส่ง SMS ระบบจะทำการจัดส่งครั้งละไม่เกิน 30,000 เบอร์โทร ต่อ 1 รายการ Smart SMS<br /> ในการตั้งเวลาส่ง SMS ให้ตั้งเวลาเพิ่มจากเวลาจริงไปอีกอย่างน้อย 20 นาที ***</font></span>
	                        <?php endif; ?>
						</td>
						<td class="dvtTabCache" align="right" style="width:30%">
							

	                    	<?php if ($this->_tpl_vars['MODULE'] == 'Webmails'): ?>
								<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.module.value='Webmails';this.form.send_mail.value='true';this.form.record.value='<?php echo $this->_tpl_vars['ID']; ?>
'" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                            	<button title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accesskey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value=" Save " style="width:70px">
	                    			<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>

	                    		</button>
							<?php elseif ($this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Leads'): ?>
                            	<button title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save'; return formValidate() " type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
									<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
									&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>

								</button>
                            <?php else: ?>
								
								<button title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save'; displaydeleted(); return formValidate() " type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
									<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
									&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>

								</button>
							<?php endif; ?>
								<!-- <input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px"> -->
							    <button title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
                                	<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px; height: 17px;vertical-align: middle;">
                                	&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>

                                </button>
	                    </td>
	                    <td>
	                    </td>
					   </tr>
					</table>
				</td>
			   </tr>
			   <tr>
				<td valign=top align=left >
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
					   <tr>

						<td align=left>
							
							<table border=0 cellspacing=0 cellpadding=0 width=100%>
							   <tr>
								<td id ="autocom"></td>
							   </tr>
							   <tr>
								<td style="padding:20px">
									<!-- General details -->
									<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
									  <!--  <tr>
										<td  colspan=4 style="padding:5px">
											<div align="center">
												
												<?php if ($this->_tpl_vars['MODULE'] == 'Webmails'): ?>
													<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.module.value='Webmails';this.form.send_mail.value='true';this.form.record.value='<?php echo $this->_tpl_vars['ID']; ?>
'" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
                                                <?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                                                <input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save'; displaydeleted();  if(formValidate()) { AjaxDuplicateValidate('Accounts','accountname',this.form); AddressSync(this.form,<?php echo $this->_tpl_vars['ID']; ?>
); }"  type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
                                                <?php else: ?>
													<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save'; displaydeleted(); return formValidate() " type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
												<?php endif; ?>
													<input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px">
											</div>
                                            
                                            <?php if ($this->_tpl_vars['MODULE'] == 'Smartemail'): ?>
                                               <font color="#FF0000" size="+1">*** การจัดส่ง E-mail ระบบจะทำการจัดส่งวันละไม่เกิน 40,000 ฉบับ หากรายการ Smart Email ที่ทำการตั้งค่าไว้มีจำนวนมากกว่าที่กำหนด ระบบจะทำการจัดส่งส่วนที่เหลือให้อีกครั้งในวันถัดไป ***</font>
                                            <?php endif; ?>

                                            <?php if ($this->_tpl_vars['MODULE'] == 'SmartSms'): ?>
                                           		<font color="#FF0000" size="+1">*** การจัดส่ง SMS ระบบจะทำการจัดส่งครั้งละไม่เกิน 30,000 เบอร์โทร ต่อ 1 รายการ Smart SMS<br /> ในการตั้งเวลาส่ง SMS ให้ตั้งเวลาเพิ่มจากเวลาจริงไปอีกอย่างน้อย 20 นาที ***</font>
                                            <?php endif; ?>
										</td>
									   </tr> -->

									   <!-- included to handle the edit fields based on ui types -->
							<?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['data']):
?>

								<!-- This is added to display the existing comments -->
								<?php if ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_COMMENTS'] || $this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_COMMENT_INFORMATION'] || $this->_tpl_vars['header'] == 'Step 1 Plan Information'): ?>
								   <tr><td>&nbsp;</td></tr>
								   <tr>
									<td colspan=4 class="dvInnerHeader">
								        <b><?php echo $this->_tpl_vars['MOD']['LBL_COMMENT_INFORMATION']; ?>
</b>
									</td>
								   </tr>
								   <tr>
									<td colspan=4 class="dvtCellInfo"><?php echo $this->_tpl_vars['COMMENT_BLOCK']; ?>
</td>
								   </tr>
								   <tr><td>&nbsp;</td></tr>
								<?php endif; ?>

								<?php if ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_ADDRESS_INFORMATION'] && ( $this->_tpl_vars['MODULE'] == 'Quotes' )): ?>
									<tr>
										<td colspan=2 class="detailedViewHeader"><b><?php echo $this->_tpl_vars['header']; ?>
</b></td>
										<td class="detailedViewHeader">
											<input name="cpy" onclick="return copyAddressLeft(EditView,'<?php echo $this->_tpl_vars['MODULE']; ?>
')" type="radio"><b><?php echo $this->_tpl_vars['APP']['LBL_RCPY_ADDRESS']; ?>
</b>
										</td>
										<td class="detailedViewHeader">
											<input name="cpy" onclick="return copyAddressRight(EditView,'<?php echo $this->_tpl_vars['MODULE']; ?>
')" type="radio"><b><?php echo $this->_tpl_vars['APP']['LBL_LCPY_ADDRESS']; ?>
</b>
										</td>
									</tr>
								<?php elseif ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_ADDRESS_INFORMATION'] && $this->_tpl_vars['MODULE'] == 'Contacts'): ?>
	                                <tr>
										<td colspan=4 class="detailedViewHeader"><b><?php echo $this->_tpl_vars['header']; ?>
</b></td>	
									</tr>	
							    <?php elseif ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
							    	<?php if ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องขอ'): ?>
							    		<tr class='case-tr-request'>
							    			<td colspan=4 class="detailedViewHeader"><b><?php echo $this->_tpl_vars['header']; ?>
</b></td>	
										</tr>
									<?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องแจ้งซ่อม' || $this->_tpl_vars['header'] == 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'): ?>
										<tr class='case-tr-service'>
							    			<td colspan=4 class="detailedViewHeader"><b><?php echo $this->_tpl_vars['header']; ?>
</b></td>	
										</tr>	
									<?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องเรียน'): ?>
										<tr class='case-tr-complain'>
							    			<td colspan=4 class="detailedViewHeader"><b><?php echo $this->_tpl_vars['header']; ?>
</b></td>	
										</tr>		
							    	<?php else: ?>
									    <tr>
											<td colspan=4 class="detailedViewHeader"><b><?php echo $this->_tpl_vars['header']; ?>
</b></td>	
										</tr>
							    	<?php endif; ?>
							    <?php elseif ($this->_tpl_vars['MODULE'] == 'Job' && $this->_tpl_vars['header'] == 'Image Infomation'): ?>

	                            
	                            <?php else: ?>
	                                <tr>
										<td colspan=4 class="detailedViewHeader"><b><?php echo $this->_tpl_vars['header']; ?>
</b></td>
								   	</tr>
								<?php endif; ?>
								<!-- Handle the ui types display -->
								<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DisplayFields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

								<tr style="height:25px"><td>&nbsp;</td></tr>
							<?php endforeach; endif; unset($_from); ?>

							<?php if ($this->_tpl_vars['MODULE'] == 'Claim'): ?>
							<tr>
								<td colspan="4">
									<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => "./modules/Claim/ProductDetail/product_list.php", 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

								</td>
							</tr>
							<?php endif; ?>
							
							<!-- Added to display the Product Details in Inventory-->
							<?php if ($this->_tpl_vars['MODULE'] == 'Quotes'): ?>
							<tr>
								<td colspan=4>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ProductDetailsEditView.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								</td>
							</tr>
							<?php endif; ?>

								   <tr>
									<td  colspan=4 style="padding:5px">
										<div align="right">
										<?php if ($this->_tpl_vars['MODULE'] == 'Emails'): ?>
										<input title="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_KEY']; ?>
" class="crmbutton small create" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid=<?php echo $this->_tpl_vars['ENTITY_ID']; ?>
&entity=<?php echo $this->_tpl_vars['ENTITY_TYPE']; ?>
','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="<?php echo $this->_tpl_vars['APP']['LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL']; ?>
">
										<input title="<?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
" accessKey="<?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['MOD']['LBL_SEND']; ?>
  " >
										<?php endif; ?>

										<?php if ($this->_tpl_vars['MODULE'] == 'Webmails'): ?>
										<input title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.module.value='Webmails';this.form.send_mail.value='true';this.form.record.value='<?php echo $this->_tpl_vars['ID']; ?>
'" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
										
										<?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
	                            		    
	                                        <button title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save'; if(formValidate()) { AjaxDuplicateValidate('Accounts','accountname',this.form); AddressSync(this.form,<?php echo $this->_tpl_vars['ID']; ?>
); }" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
" style="width:70px">
												<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
												&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>

											</button>
										<?php elseif ($this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Leads'): ?>
			                            	<button title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save'; return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
	          				                	<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
	          				                	&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>

	          				                </button>
										<?php else: ?>
	          				                
	          				                <button title="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_KEY']; ?>
" class="crmbutton small save" onclick="this.form.action.value='Save';  displaydeleted();return formValidate()" type="submit" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>
  " style="width:70px" >
	          				                	<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
	          				                	&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_SAVE_BUTTON_LABEL']; ?>

	          				                </button>
										<?php endif; ?>

                                        <!-- <input title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  " style="width:70px"> -->
                                        	<button title="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_KEY']; ?>
" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  <?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>
  ">
                                            	<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px; height: 17px;vertical-align: middle;">
                                            	&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_CANCEL_BUTTON_LABEL']; ?>

                                            </button>
										</div>
									  </td>
								     </tr>
									</table>
								</td>
							   </tr>
							</table>
						</td>
					   </tr>
					</table>
				</td>
			   </tr>
			</table>
		<div>
	</td>
	<td align=right valign=top><img src="<?php echo aicrm_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
   </tr>
</table>
<!--added to fix 4600-->
<input name='search_url' id="search_url" type='hidden' value='<?php echo $this->_tpl_vars['SEARCH']; ?>
'>
</form>

<script src="include/ckeditor/ckeditor.js"></script>

<?php if (( $this->_tpl_vars['MODULE'] == 'Emails' || 'Documents' ) && ( $this->_tpl_vars['FCKEDITOR_DISPLAY'] == 'true' )): ?>
	
    <script src="include/ckeditor/ckeditor.js"></script>
    <?php echo '
	<script>
	// Replace the <textarea id="editor1"> with a CKEditor
	// instance, using default configuration.
	CKEDITOR.replace( \'notecontent\',
	{
	    filebrowserUploadUrl: \'include/ckeditor/plugins/imgupload/imgupload.php\'
	}
	);
	</script>
	'; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
<script>
	ScrollEffect.limit = 201;
	ScrollEffect.closelimit= 200;
</script>
<?php endif; ?>
<script>

	var fielduitype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_UITYPE']; ?>
)
    var fieldname = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDNAME']; ?>
)
    var fieldlabel = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDLABEL']; ?>
)
    var fielddatatype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDDATATYPE']; ?>
)
	
	var ProductImages = new Array();
    var ProductImages1 = new Array();
    var count = 0;
    var count1 = 0;

	/*function delRowEmt(imagename)
	{
		ProductImages[count++]=imagename;
		if(ProductImages.length > 0)
			document.EditView.del_file_list.value=ProductImages.join('###');
	}*/
	function delRowEmt(imagename) {
        ProductImages[count++]=imagename;
        
        multi_selector.current_element.disabled = false;
        multi_selector.count--;
        if(ProductImages.length > 0){
        	console.log(ProductImages);
			document.EditView.del_file_list.value=ProductImages.join('###');
		}
    }

    function delRowEmt1(imagename) {
        ProductImages1[count1++] = imagename;
        
        multi_selector2.current_element1.disabled = false;
        multi_selector2.count1--;
        if(ProductImages1.length > 0){
        	console.log(ProductImages1);
			document.EditView.imagename_hidden.value=ProductImages1.join('###');
		}	
    }
    function delRowEmt2(imagename) {
        ProductImages1[count1++] = imagename;
        
        multi_selector2.current_element1.disabled = false;
        multi_selector2.count1--;
        if(ProductImages1.length > 0){
        	console.log(ProductImages1);
			document.EditView.imagereceipt_hidden.value=ProductImages1.join('###');
		}	
    }

	function displaydeleted()
	{
		
		var imagelists='';
		
		for(var x = 0; x < ProductImages.length; x++)
		{
			imagelists+=ProductImages[x]+'###';
		}

		if(imagelists != ''){
			document.EditView.imagelist.value=imagelists
		}
		
	}

</script>
<?php if (( $this->_tpl_vars['MODULE'] == 'Accounts' )): ?>
<script type='text/javascript'>
	//const myTimeout = setTimeout(display_map(), 15000);
</script>
<?php endif; ?>
<!-- vtlib customization: Help information assocaited with the fields -->
<?php if ($this->_tpl_vars['FIELDHELPINFO']): ?>
<script type='text/javascript'>
<?php echo 'var fieldhelpinfo = {}; '; ?>

<?php $_from = $this->_tpl_vars['FIELDHELPINFO']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['FIELDHELPKEY'] => $this->_tpl_vars['FIELDHELPVAL']):
?>
	fieldhelpinfo["<?php echo $this->_tpl_vars['FIELDHELPKEY']; ?>
"] = "<?php echo $this->_tpl_vars['FIELDHELPVAL']; ?>
";
<?php endforeach; endif; unset($_from); ?>
</script>
<?php endif; ?>



<?php if (( $this->_tpl_vars['MODULE'] == 'KnowledgeBase' )): ?>
       
       <script src="include/ckeditor/ckeditor.js"></script>
        <?php echo '
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( \'know_detail\',
        {
            filebrowserUploadUrl: \'include/ckeditor/plugins/imgupload/imgupload.php\'
        }
        );
        </script>
        '; ?>

<?php endif; ?>
<?php if (( $this->_tpl_vars['MODULE'] == 'KnowledgeBase' )): ?>
       
       <script src="include/ckeditor/ckeditor.js"></script>
        <?php echo '
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( \'know_detail_en\',
        {
            filebrowserUploadUrl: \'include/ckeditor/plugins/imgupload/imgupload.php\'
        }
        );
        </script>
        '; ?>

<?php endif; ?>
<?php if (( $this->_tpl_vars['MODULE'] == 'Smartemail' || $this->_tpl_vars['MODULE'] == 'Smartquestionnaire' )): ?>
     
       <script src="include/ckeditor/ckeditor.js"></script>
        <?php echo '
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( \'email_message\',
        {
            filebrowserUploadUrl: \'include/ckeditor/plugins/imgupload/imgupload.php\'
        }
        );
        </script>
        '; ?>

<?php endif; ?>

<?php if (( $this->_tpl_vars['MODULE'] == 'Campaigns' )): ?>
    <script src="include/ckeditor/ckeditor.js"></script>
    <?php echo '
    <script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace( \'camp_detail\',
    {
        filebrowserUploadUrl: \'include/ckeditor/plugins/imgupload/imgupload.php\'
    }
    );
    </script>
    '; ?>

<?php endif; ?>

<?php if (( $this->_tpl_vars['MODULE'] == 'Announcement' )): ?>
    <script src="include/ckeditor/ckeditor.js"></script>
    <?php echo '
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( \'detail\',
        {
            filebrowserUploadUrl: \'include/ckeditor/plugins/imgupload/imgupload.php\'
        }
        );
    </script>
    '; ?>

<?php endif; ?>

<?php if (( $this->_tpl_vars['MODULE'] == 'Promotion' )): ?>
    <script src="include/ckeditor/ckeditor.js"></script>
    <?php echo '
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( \'promotion_detail\',
        {
            filebrowserUploadUrl: \'include/ckeditor/plugins/imgupload/imgupload.php\'
        }
        );
    </script>
    '; ?>

<?php endif; ?>
<!-- END -->