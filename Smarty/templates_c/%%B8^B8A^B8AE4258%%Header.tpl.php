<?php /* Smarty version 2.6.18, created on 2026-04-08 16:50:49
         compiled from Header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'Header.tpl', 86, false),array('modifier', 'escape', 'Header.tpl', 94, false),array('modifier', 'getTranslatedString', 'Header.tpl', 120, false),array('modifier', 'vtlib_purify', 'Header.tpl', 437, false),array('function', 'math', 'Header.tpl', 723, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	
	<title><?php echo $this->_tpl_vars['CURRENT_USER']; ?>
 - <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['CATEGORY']]; ?>
 - <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_NAME']]; ?>
 - <?php echo $this->_tpl_vars['APP']['LBL_BROWSER_TITLE']; ?>
</title>
	<link REL="SHORTCUT ICON" HREF="themes/AICRM.ico">
	<style type="text/css">@import url("themes/<?php echo $this->_tpl_vars['THEME']; ?>
/style.css");</style>
	<!-- ActivityReminder customization for callback -->
	<?php echo '
	<style type="text/css">
	div.fixedLay1 { position:fixed; }

    </style>
	<!--[if lte IE 6]>
	<style type="text/css">div.fixedLay { position:absolute; }</style>
	<![endif]-->
	'; ?>

	<!-- End -->
</head>
	<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 class=small>
	<a name="top"></a>
	<!-- header -->
	
	<!-- header-vtiger crm name & RSS -->
	<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
	<!-- vtlib customization: Javascript hook -->
	<script language="JavaScript" type="text/javascript" src="include/js/vtlib.js"></script>
	<!-- END -->
	<script language="JavaScript" type="text/javascript" src="include/js/<?php  echo $_SESSION['authenticated_user_language']; ?>.lang.js?<?php  echo $_SESSION['aicrm_version']; ?>"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/QuickCreate.js"></script>
	<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
	<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_NAME']] == 'Sales Visit'): ?>
		<!-- <script type="text/javascript" src="asset/js/jquery.min.js"></script> -->
		<!-- <script type="text/javascript" src="asset/js/jquery-1.10.2.min.js"></script> -->
		<link rel="stylesheet" type="text/css" href="asset/css/bootstrap-modal.min.css">
		<script language="JavaScript" type="text/javascript" src="asset/js/bootstrap-modal.min.js"></script>
	<?php endif; ?>
	<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
	<script language="JavaScript" type="text/javascript" src="modules/Calendar/script.js"></script>
	<script language="javascript" type="text/javascript" src="include/scriptaculous/dom-drag.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/notificationPopup.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
    <script type="text/javascript" src="jscalendar/calendar.js"></script>
    <script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
    <script type="text/javascript" src="jscalendar/lang/calendar-<?php echo $this->_tpl_vars['APP']['LBL_JSCALENDAR_LANG']; ?>
.js"></script>

    <!-- asterisk Integration -->
    <?php if ($this->_tpl_vars['USE_ASTERISK'] == 'true'): ?>
    	<script type="text/javascript" src="include/js/asterisk.js"></script>
    	<script type="text/javascript">
    	if(typeof(use_asterisk) == 'undefined') use_asterisk = true;
    	</script>
    <?php endif; ?>
    <!-- END -->

		<?php if ($this->_tpl_vars['HEADERSCRIPTS']): ?>
		<!-- Custom Header Script -->
		<?php $_from = $this->_tpl_vars['HEADERSCRIPTS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['HEADERSCRIPT']):
?>
			<script type="text/javascript" src="<?php echo $this->_tpl_vars['HEADERSCRIPT']->linkurl; ?>
"></script>
		<?php endforeach; endif; unset($_from); ?>
		<!-- END -->
	<?php endif; ?>
	<?php if ($this->_tpl_vars['HEADERCSS']): ?>
		<!-- Custom Header CSS -->
		<?php $_from = $this->_tpl_vars['HEADERCSS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['HDRCSS']):
?>
			<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['HDRCSS']->linkurl; ?>
"></script>
		<?php endforeach; endif; unset($_from); ?>
		<!-- END -->
	<?php endif; ?>
	
	    <img src="<?php echo aicrm_imageurl('layerPopupBg.gif', $this->_tpl_vars['THEME']); ?>
" style="display: none;"/>
    <!--
	<TABLE border=0 cellspacing=0 cellpadding=0 width="100%"  background="themes/softed/images/Ai-HEAD.jpg" style="background-repeat:no-repeat; border:1px solid #000;" height="30px">
	<tr>
		<td valign=top></td>
		<td width=100% align=center>
		<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_NAME']] == 'Dashboards'): ?>
		<marquee id="rss" direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="javascript:stop();" onMouseOut="javascript:start();">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['ANNOUNCEMENT'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</marquee>
		<?php else: ?>
                <marquee id="rss" direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="javascript:stop();" onMouseOut="javascript:start();">&nbsp;<?php echo $this->_tpl_vars['ANNOUNCEMENT']; ?>
</marquee>
                <?php endif; ?>
		</td>
		<td class=small nowrap>
			<table border=0 cellspacing=0 cellpadding=0>
			 <tr height="50px" valign="bottom">

						<?php if ($this->_tpl_vars['HEADERLINKS']): ?>
			<td style="padding-left:10px;padding-right:5px" class=small nowrap>
				<a href="javascript:;" onMouseOver="fnvshobj(this,'vtlib_headerLinksLay');" onClick="fnvshobj(this,'vtlib_headerLinksLay');"><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
</a> <img src="<?php echo aicrm_imageurl('arrow_down.gif', $this->_tpl_vars['THEME']); ?>
" border=0>
				<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_headerLinksLay"
					onmouseout="fninvsh('vtlib_headerLinksLay')" onMouseOver="fnvshNrm('vtlib_headerLinksLay')">
					<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
</b></td></tr>
					<tr  >
						<td>
							<?php $_from = $this->_tpl_vars['HEADERLINKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['HEADERLINK']):
?>
								<?php $this->assign('headerlink_href', $this->_tpl_vars['HEADERLINK']->linkurl); ?>
								<?php $this->assign('headerlink_label', $this->_tpl_vars['HEADERLINK']->linklabel); ?>
								<?php if ($this->_tpl_vars['headerlink_label'] == ''): ?>
									<?php $this->assign('headerlink_label', $this->_tpl_vars['headerlink_href']); ?>
								<?php else: ?>
																		<?php $this->assign('headerlink_label', getTranslatedString($this->_tpl_vars['headerlink_label'], $this->_tpl_vars['HEADERLINK']->module())); ?>
								<?php endif; ?>
								<a href="<?php echo $this->_tpl_vars['headerlink_href']; ?>
" class="drop_down"><?php echo $this->_tpl_vars['headerlink_label']; ?>
</a>
							<?php endforeach; endif; unset($_from); ?>
						</td>
					</tr>
					</table>
				</div>
			</td>
			<?php endif; ?>
			
			<!-- gmailbookmarklet customization -->
			<!-- <td style="padding-left:10px;padding-right:10px" class=small nowrap>
				<?php echo $this->_tpl_vars['GMAIL_BOOKMARKLET']; ?>

			 </td>-->
			 <!-- END -->
             <!--
			 <?php if ($this->_tpl_vars['ADMIN_LINK'] != ''): ?> 			 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="javascript:void(0);" onClick="aicrm_news(this)"><?php echo $this->_tpl_vars['APP']['LBL_VTIGER_NEWS']; ?>
</a></td>
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="javascript:void(0);" onClick="aicrm_feedback();"><?php echo $this->_tpl_vars['APP']['LBL_FEEDBACK']; ?>
</a></td>
			 <?php endif; ?>
	-->
		<!--	 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=DetailView&record=<?php echo $this->_tpl_vars['CURRENT_USER_ID']; ?>
&modechk=prefview"><?php echo $this->_tpl_vars['APP']['LBL_MY_PREFERENCES']; ?>
</a></td>-->
             <!--
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap><a href="http://wiki.vtiger.com/index.php/Main_Page" target="_blank"><?php echo $this->_tpl_vars['APP']['LNK_HELP']; ?>
</a></td>
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap><a href="javascript:;" onClick="openwin();"><?php echo $this->_tpl_vars['APP']['LNK_WEARE']; ?>
</a></td>
             -->
		<!--		 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=Logout"><?php echo $this->_tpl_vars['APP']['LBL_LOGOUT']; ?>
</a> (<?php echo $this->_tpl_vars['CURRENT_USER']; ?>
)</td>
			 </tr>
			</table>
		</td>
	</tr>
	</TABLE>
-->
<div id='miniCal' style='width:300px; position:absolute; display:none; left:100px; top:100px; z-index:100000'>
</div>

<!-- header - master tabs -->
<div class="skin-greensena">
<table width="100%" cellpadding="0" cellspacing="0" border="0" >
<tr>
<td  class="logo" >
 <div class="brand"><a href="#">
 	<!-- <img src="themes/softed/images/twin_logo.png" > -->
 	<img src="themes/softed/images/logo.png" >
 </a>
</div>
</td>
<td>

<TABLE border=0 cellspacing=0 cellpadding=0 width="100%" class="hdrTabBg top-header"  >
<tr>

	<td class=small nowrap>
		<table border=0 cellspacing=0 cellpadding=0 class="main-menu" >
		<tr>
		<!--<td class=tabSeperator><img src="<?php echo aicrm_imageurl('spacer.gif', $this->_tpl_vars['THEME']); ?>
" width=2px height=30px></td>		-->
			<?php $_from = $this->_tpl_vars['HEADERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['maintabs'] => $this->_tpl_vars['detail']):
?>
				<?php if ($this->_tpl_vars['maintabs'] != $this->_tpl_vars['CATEGORY']): ?>
              		<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'My Home Page'): ?>
                        <td class="tabUnSelected main-page" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Home_B.png" border=0 style="width: 20px; height: 20px;" class="image_hover"></a></td>
                        <!-- <td class="tabUnSelected main-page"   onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/softed/images/homeb.png" border=0 style="width: 15px; height: 15px;"></a></td> -->
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Marketing'): ?>
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Marketing_B.png" class="image_hover" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Sales'): ?>
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Sales_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Service'): ?>
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Services_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Analytics'): ?>
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Analytics_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Inventory'): ?>
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Inventory_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Tools'): ?>
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Tools_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Settings'): ?>
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Setting_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php else: ?>
						<td class="tabUnSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
					<?php endif; ?>
                <?php else: ?>
                	<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'My Home Page'): ?>
                        <td class="tabSelected main-page" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/home.png" border=0 style="width: 20px; height: 20px;"></a></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Marketing'): ?>
                		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Marketing_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Sales'): ?>
                		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Sales_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Service'): ?>
                		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Services_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Analytics'): ?>
                		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Analytics_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Inventory'): ?>
                		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Inventory_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Tools'): ?>
                		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Tools_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php elseif ($this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']] == 'Settings'): ?>
                		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><img src="themes/images/Setting_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php else: ?>
				 		<td class="tabSelected" onmouseover="fnDropDown(this,'<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['maintabs']; ?>
_sub');" align="center" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['detail'][0]; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['maintabs']]; ?>
</a><img src="<?php echo aicrm_imageurl('menuDnArrow.gif', $this->_tpl_vars['THEME']); ?>
" border=0 style="padding-left:5px"></td>
                	<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>

			<!-- <td style="padding-left:15px" nowrap>

				<?php if ($this->_tpl_vars['CNT'] == 1): ?>
					<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onclick="QCreate(this);">
						<option value="none"><?php echo $this->_tpl_vars['APP']['LBL_QUICK_CREATE']; ?>
...</option>
                        <?php $_from = $this->_tpl_vars['QCMODULE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
                        <option value="<?php echo $this->_tpl_vars['detail']['1']; ?>
"><?php echo $this->_tpl_vars['APP']['NEW']; ?>
&nbsp;<?php echo $this->_tpl_vars['detail']['0']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
					</select>
				<?php else: ?>
					<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onchange="QCreate(this);">
						<option value="none"><?php echo $this->_tpl_vars['APP']['LBL_QUICK_CREATE']; ?>
...</option>
                        <?php $_from = $this->_tpl_vars['QCMODULE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
                        <option value="<?php echo $this->_tpl_vars['detail']['1']; ?>
"><?php echo $this->_tpl_vars['APP']['NEW']; ?>
&nbsp;<?php echo $this->_tpl_vars['detail']['0']; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
					</select>
				<?php endif; ?>

			</td> -->
		</tr>
		</table>
	</td>
	<td align=right style="padding-right:10px" nowrap >

	</td>
</td>
<!-- Maew add New td for User Profile -->
<!-- <td style="padding-left:15px; width: 130px; padding-right: 0px;" nowrap>Agent</td> -->
<!-- <td align="center" nowrap>
	<a href="extend" style="text-decoration: none;color: #000">
		<img src="themes/images/Agent.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">Agent
	</a>
</td> -->
<td style="padding-left:15px; width: 130px; padding-right: 0px;" nowrap>

	<?php if ($this->_tpl_vars['CNT'] == 1): ?>
	<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onclick="QCreate(this);">
		<option value="none"><?php echo $this->_tpl_vars['APP']['LBL_QUICK_CREATE']; ?>
...</option>
		<?php $_from = $this->_tpl_vars['QCMODULE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
		<option value="<?php echo $this->_tpl_vars['detail']['1']; ?>
"><?php echo $this->_tpl_vars['APP']['NEW']; ?>
&nbsp;<?php echo $this->_tpl_vars['detail']['0']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select>
	<?php else: ?>
	<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onchange="QCreate(this);">
		<option value="none"><?php echo $this->_tpl_vars['APP']['LBL_QUICK_CREATE']; ?>
...</option>
		<?php $_from = $this->_tpl_vars['QCMODULE']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
		<option value="<?php echo $this->_tpl_vars['detail']['1']; ?>
"><?php echo $this->_tpl_vars['APP']['NEW']; ?>
&nbsp;<?php echo $this->_tpl_vars['detail']['0']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select>
	<?php endif; ?>

</td>
<td style="padding-left: 10px; text-align: center;">
	<!-- <img src="themes/softed/images/message.png" style="width: 25px; height: 25px;">
	<span class="point"></span> -->
</td>
<td style="padding-left: 15px;">
	<!-- <img src="themes/softed/images/notification.png" style="width: 20px; height: 20px;">
	<span class="pointgreen"></span> -->
</td>
<td class="user-nav">
 <a href="index.php?module=Users&action=DetailView&record=<?php echo $this->_tpl_vars['CURRENT_USER_ID']; ?>
&modechk=prefview">
 	<?php if ($this->_tpl_vars['IMAGEUSER'] != ''): ?>
 		<img src="<?php echo $this->_tpl_vars['IMAGEUSER']; ?>
" style="width: 25px; height: 25px;"> <?php echo $this->_tpl_vars['CURRENT_USER']; ?>
 <!--<?php echo $this->_tpl_vars['APP']['LBL_MY_PREFERENCES']; ?>
--></a>
 	<?php else: ?>
 		<img src="themes/softed/images/profile.png" style="width: 25px; height: 25px;"> <?php echo $this->_tpl_vars['CURRENT_USER']; ?>
 <!--<?php echo $this->_tpl_vars['APP']['LBL_MY_PREFERENCES']; ?>
--></a>
 	<?php endif; ?>
</td>
<td class="user-signout">
 <!-- <a href="index.php?module=Users&action=Logout"><img src="themes/softed/images/logout_icon.png">   <?php echo $this->_tpl_vars['APP']['LBL_LOGOUT']; ?>
</a> -->
 <a href="index.php?module=Users&action=Logout"><img src="themes/softed/images/logout.png" style="width: 25px; height: 25px;"></a>
</td>
</tr>
</TABLE>
<!-- - level 2 tabs starts-->
<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="level2Bg"  >
<tr>
	<td >
		<table border=0 cellspacing=0 cellpadding=0 style="height:35px;" >
		<tr>
			<!-- ASHA: Avoid using this as it gives module name instead of module label.
			Now Using the same array QUICKACCESS that is used to show drop down menu
			(which gives both module name and module label)-->
			<!--<?php $_from = $this->_tpl_vars['HEADERS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['maintabs'] => $this->_tpl_vars['detail']):
?>
				<?php if ($this->_tpl_vars['maintabs'] == $this->_tpl_vars['CATEGORY']): ?>
					<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['number'] => $this->_tpl_vars['module']):
?>
						<?php $this->assign('modulelabel', $this->_tpl_vars['module']); ?>
      					<?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['module']]): ?>
      						<?php $this->assign('modulelabel', $this->_tpl_vars['APP'][$this->_tpl_vars['module']]); ?>
      					<?php endif; ?>
						<?php if ($this->_tpl_vars['module'] == $this->_tpl_vars['MODULE_NAME']): ?>
							<td class="level2SelTab" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['module']; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td>
						<?php else: ?>
							<td class="level2UnSelTab" nowrap> <a href="index.php?module=<?php echo $this->_tpl_vars['module']; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a> </td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>-->

			<?php $_from = $this->_tpl_vars['QUICKACCESS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['maintabs'] => $this->_tpl_vars['details']):
?>
				<?php if ($this->_tpl_vars['maintabs'] == $this->_tpl_vars['CATEGORY']): ?>
					<?php $_from = $this->_tpl_vars['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['number'] => $this->_tpl_vars['modules']):
?>
						<?php $this->assign('modulelabel', getTranslatedString($this->_tpl_vars['modules'][1], $this->_tpl_vars['modules'][0])); ?>

	   											<?php $this->assign('moduleaction', 'index'); ?>
	   					<?php if (isset ( $this->_tpl_vars['modules'][2] )): ?>
	   						<?php $this->assign('moduleaction', $this->_tpl_vars['modules'][2]); ?>
	   					<?php endif; ?>	   					
	   					
	   					<?php if ($this->_tpl_vars['MODULE_NAME'] == 'Settings'): ?>
	   						
	   						<?php if ($this->_tpl_vars['URL_ACTION'] == $this->_tpl_vars['moduleaction'] && $this->_tpl_vars['MODULE_NAME'] == 'Settings'): ?>
	   							<td class="level2SelTab" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td>
	   						
	   						<?php elseif (( $this->_tpl_vars['URL_ACTION'] == 'listroles' || $this->_tpl_vars['URL_ACTION'] == 'ListProfiles' || $this->_tpl_vars['URL_ACTION'] == 'listgroups' || $this->_tpl_vars['URL_ACTION'] == 'OrgSharingDetailView' || $this->_tpl_vars['URL_ACTION'] == 'DefaultFieldPermissions' || $this->_tpl_vars['URL_ACTION'] == 'AuditTrailList' || $this->_tpl_vars['URL_ACTION'] == 'ListLoginHistory' || $this->_tpl_vars['URL_ACTION'] == 'list_Field' || $this->_tpl_vars['URL_ACTION'] == 'list_account_send_email' || $this->_tpl_vars['URL_ACTION'] == 'list_bounce_email' || $this->_tpl_vars['URL_ACTION'] == 'list_server_email' || $this->_tpl_vars['URL_ACTION'] == 'list_reply_email' || $this->_tpl_vars['URL_ACTION'] == 'list_sender_sms' || $this->_tpl_vars['URL_ACTION'] == 'weeklyplantemplates' || $this->_tpl_vars['URL_ACTION'] == 'monthlyplantemplates' || $this->_tpl_vars['URL_ACTION'] == 'listemailtemplates' || $this->_tpl_vars['URL_ACTION'] == 'OrganizationConfig' || $this->_tpl_vars['URL_ACTION'] == 'TaxConfig' || $this->_tpl_vars['URL_ACTION'] == 'EmailConfig' || $this->_tpl_vars['URL_ACTION'] == 'ListModuleOwners' || $this->_tpl_vars['URL_ACTION'] == 'OrganizationTermsandConditions' || $this->_tpl_vars['URL_ACTION'] == 'CustomModEntityNo' || $this->_tpl_vars['URL_ACTION'] == 'CurrencyListView' || $this->_tpl_vars['URL_ACTION'] == 'LayoutBlockList' || $this->_tpl_vars['URL_ACTION'] == 'Settingprovince' ) && $this->_tpl_vars['moduleaction'] == 'index'): ?>
	   							<td class="level2SelTab"  nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td>
	   						
	   						<?php else: ?>
	   							<td class="level2UnSelTab" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td>
	   						<?php endif; ?>
	   					
	   					<?php elseif ($this->_tpl_vars['MODULE_NAME'] == 'PickList' || $this->_tpl_vars['MODULE_NAME'] == 'Administration' || $this->_tpl_vars['MODULE_NAME'] == 'Tooltip' || $this->_tpl_vars['MODULE_NAME'] == 'FieldFormulas'): ?>
	   						
	   						<?php if ($this->_tpl_vars['moduleaction'] == 'index'): ?>
	   							<td class="level2SelTab"  nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td>
	   						<?php else: ?>
	   							<td class="level2UnSelTab" nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td>
	   						<?php endif; ?>


	   					<?php else: ?>
	   						<?php if ($this->_tpl_vars['modules']['0'] == $this->_tpl_vars['MODULE_NAME']): ?>
								<td class="level2SelTab"  nowrap><a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td>
							<?php else: ?>
								<td class="level2UnSelTab"   nowrap> <a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['maintabs']; ?>
"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a> </td>
							<?php endif; ?>
	   					<?php endif; ?>
						


					<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</tr>
		</table>
	</td>
    <!-- Maew Add Search box -->
    <td>
    </td>

</tr>
</TABLE>
</td>
</tr>
</table>
<!-- Level 2 tabs ends -->
<div id="calculator_cont" style="position:absolute; z-index:10000" ></div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Clock.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="qcform" style="position:absolute;width:700px;top:80px;left:250px;z-index:100000;"></div>

<div id="newqcform" style="position:absolute;width:700px;top:50%;left:50%;z-index:100000;transform: translate(-50%, -50%);"></div>
<!-- Unified Search module selection feature -->
<div id="UnifiedSearch_moduleformwrapper" style="position:absolute;width:400px;z-index:100002;display:none;"></div>
<script type='text/javascript'>
<?php echo '
function UnifiedSearch_SelectModuleForm(obj) {
	if($(\'UnifiedSearch_moduleform\')) {
		// If we have loaded the form already.
		UnifiedSearch_SelectModuleFormCallback(obj);
	}else{
		$(\'status\').show();
		new Ajax.Request(
		\'index.php\',
		{queue: {position: \'end\', scope: \'command\'},
			method: \'post\',
			postBody: \'module=Home&action=HomeAjax&file=UnifiedSearchModules&ajax=true\',
			onComplete: function(response) {
				$(\'status\').hide();
				$(\'UnifiedSearch_moduleformwrapper\').innerHTML = response.responseText;
				UnifiedSearch_SelectModuleFormCallback(obj);
			}
		});
	}
}

function UnifiedSearch_SelectModuleFormCallback(obj) {
	fnvshobj(obj, \'UnifiedSearch_moduleformwrapper\');
}

function UnifiedSearch_SelectModuleToggle(flag) {
	Form.getElements($(\'UnifiedSearch_moduleform\')).each(
		function(element) {
			if(element.type == \'checkbox\') {
				element.checked = flag;
			}
		}
	);
}

function UnifiedSearch_SelectModuleCancel() {
	$(\'UnifiedSearch_moduleformwrapper\').hide();
}
function UnifiedSearch_SelectModuleSave() {
	var UnifiedSearch_form = document.forms.UnifiedSearch;
	UnifiedSearch_form.search_onlyin.value = Form.serialize($(\'UnifiedSearch_moduleform\')).replace(/search_onlyin=/g, \'\').replace(/&/g,\',\');
	UnifiedSearch_SelectModuleCancel();
}
'; ?>

</script>
<!-- End -->

<script>
var gVTModule = '<?php echo vtlib_purify($_REQUEST['module']); ?>
';
function fetch_clock()
{
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Utilities&action=UtilitiesAjax&file=Clock',
			onComplete: function(response)
				    {
					$("clock_cont").innerHTML=response.responseText;
					execJS($('clock_cont'));
				    }
		}
	);
}

function fetch_calc()
{
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Utilities&action=UtilitiesAjax&file=Calculator',
			onComplete: function(response)
					{
						$("calculator_cont").innerHTML=response.responseText;
						execJS($('calculator_cont'));
					}
		}
	);
}
</script>

<script>
<?php echo '
function QCreate(qcoptions){
	var module = qcoptions.options[qcoptions.options.selectedIndex].value;
	if(module != \'none\'){
		$("status").style.display="inline";
		if(module == \'Events\'){
			module = \'Calendar\';
			var urlstr = \'&activity_mode=Events\';
		}else if(module == \'Calendar\'){
			module = \'Calendar\';
			var urlstr = \'&activity_mode=Task\';
		}else{
			var urlstr = \'\';
		}
		new Ajax.Request(
			\'index.php\',
				{queue: {position: \'end\', scope: \'command\'},
				method: \'post\',
				postBody: \'module=\'+module+\'&action=\'+module+\'Ajax&file=QuickCreate\'+urlstr,
				onComplete: function(response){
					$("status").style.display="none";
					$("qcform").style.display="inline";
					$("qcform").innerHTML = response.responseText;
					// Evaluate all the script tags in the response text.
					var scriptTags = $("qcform").getElementsByTagName("script");
					for(var i = 0; i< scriptTags.length; i++){
						var scriptTag = scriptTags[i];
						eval(scriptTag.innerHTML);
					}
                    eval($("qcform"));
                    posLay(qcoptions, "qcform");
				}
			}
		);
	}else{
		hide(\'qcform\');
	}
}

function QCreateinform(module){
	//var module = qcoptions.options[qcoptions.options.selectedIndex].value;
	if(module != \'none\'){
		$("status").style.display="inline";
		if(module == \'Events\'){
			module = \'Calendar\';
			var urlstr = \'&activity_mode=Events\';
		}else if(module == \'Calendar\'){
			module = \'Calendar\';
			var urlstr = \'&activity_mode=Task\';
		}else{
			var urlstr = \'\';
		}
		new Ajax.Request(
			\'index.php\',
				{queue: {position: \'end\', scope: \'command\'},
				method: \'post\',
				postBody: \'module=\'+module+\'&action=\'+module+\'Ajax&file=QuickCreateInform\'+urlstr,
				onComplete: function(response){
					$("status").style.display="none";
					$("newqcform").style.display="inline";
					$("newqcform").innerHTML = response.responseText;
					// Evaluate all the script tags in the response text.
					var scriptTags = $("newqcform").getElementsByTagName("script");
					for(var i = 0; i< scriptTags.length; i++){
						var scriptTag = scriptTags[i];
						eval(scriptTag.innerHTML);
					}
                    eval($("newqcform"));
                    posLay(qcoptions, "newqcform");
				}
			}
		);
	}else{
		hide(\'newqcform\');
	}
}

function getFormValidate(divValidate)
{
  var st = document.getElementById(\'qcvalidate\');
  eval(st.innerHTML);
  for (var i=0; i<qcfieldname.length; i++) {
		var curr_fieldname = qcfieldname[i];
		if(window.document.QcEditView[curr_fieldname] != null)
		{
			var type=qcfielddatatype[i].split("~")
			var input_type = window.document.QcEditView[curr_fieldname].type;
			if (type[1]=="M") {
					if (!qcemptyCheck(curr_fieldname,qcfieldlabel[i],input_type))
						return false
				}
			switch (type[0]) {
				case "O"  : break;
				case "V"  : break;
				case "C"  : break;
				case "DT" :
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\').length!=0)
					{
						if (type[1]=="M")
							if (!qcemptyCheck(type[2],qcfieldlabel[i],getObj(type[2]).type))
								return false
						if(typeof(type[3])=="undefined") var currdatechk="OTH"
						else var currdatechk=type[3]

						if (!qcdateTimeValidate(curr_fieldname,type[2],qcfieldlabel[i],currdatechk))
							return false
						if (type[4]) {
							if (!dateTimeComparison(curr_fieldname,type[2],qcfieldlabel[i],type[5],type[6],type[4]))
								return false

						}
					}
				break;
				case "D"  :
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\').length!=0)
					{
						if(typeof(type[2])=="undefined") var currdatechk="OTH"
						else var currdatechk=type[2]

							if (!qcdateValidate(curr_fieldname,qcfieldlabel[i],currdatechk))
								return false
									if (type[3]) {
										if (!qcdateComparison(curr_fieldname,qcfieldlabel[i],type[4],type[5],type[3]))
											return false
									}
					}
				break;
				case "T"  :
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\').length!=0)
					{
						if(typeof(type[2])=="undefined") var currtimechk="OTH"
						else var currtimechk=type[2]

							if (!timeValidate(curr_fieldname,qcfieldlabel[i],currtimechk))
								return false
									if (type[3]) {
										if (!timeComparison(curr_fieldname,qcfieldlabel[i],type[4],type[5],type[3]))
											return false
									}
					}
				break;
				case "I"  :
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\').length!=0)
					{
						if (window.document.QcEditView[curr_fieldname].value.length!=0)
						{
							if (!qcintValidate(curr_fieldname,qcfieldlabel[i]))
								return false
							if (type[2]) {
								if (!qcnumConstComp(curr_fieldname,qcfieldlabel[i],type[2],type[3]))
									return false
							}
						}
					}
				break;
				case "N"  :
				case "NN" :
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\').length!=0)
					{
						if (window.document.QcEditView[curr_fieldname].value.length!=0)
						{
							if (typeof(type[2])=="undefined") var numformat="any"
							else var numformat=type[2]

								if (type[0]=="NN") {

									if (!numValidate(curr_fieldname,qcfieldlabel[i],numformat,true))
										return false
								} else {
									if (!numValidate(curr_fieldname,qcfieldlabel[i],numformat))
										return false
								}
							if (type[3]) {
								if (!numConstComp(curr_fieldname,qcfieldlabel[i],type[3],type[4]))
									return false
							}
						}
					}
				break;
				case "E"  :
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\').length!=0)
					{
						if (window.document.QcEditView[curr_fieldname].value.length!=0)
						{
							var etype = "EMAIL"
								if (!qcpatternValidate(curr_fieldname,qcfieldlabel[i],etype))
									return false
						}
					}
				break;
			}
		}
	}
       //added to check Start Date & Time,if Activity Status is Planned.//start
        for (var j=0; j<qcfieldname.length; j++)
		{
			curr_fieldname = qcfieldname[j];
			if(window.document.QcEditView[curr_fieldname] != null)
			{
				if(qcfieldname[j] == "date_start")
				{
					var datelabel = qcfieldlabel[j]
						var datefield = qcfieldname[j]
						var startdatevalue = window.document.QcEditView[datefield].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\')
				}
				if(qcfieldname[j] == "time_start")
				{
					var timelabel = qcfieldlabel[j]
						var timefield = qcfieldname[j]
						var timeval=window.document.QcEditView[timefield].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\')
				}
				if(qcfieldname[j] == "eventstatus" || qcfieldname[j] == "taskstatus")
				{
					var statusvalue = window.document.QcEditView[curr_fieldname].options[window.document.QcEditView[curr_fieldname].selectedIndex].value.replace(/^\\s+/g, \'\').replace(/\\s+$/g, \'\')
					var statuslabel = qcfieldlabel[j++]
				}
			}
		}
	if(statusvalue == "Planned")
        {
            var dateelements=splitDateVal(startdatevalue)
	       	var hourval=parseInt(timeval.substring(0,timeval.indexOf(":")))
            var minval=parseInt(timeval.substring(timeval.indexOf(":")+1,timeval.length))
            var dd=dateelements[0]
            var mm=dateelements[1]
            var yyyy=dateelements[2]

            var chkdate=new Date()
                chkdate.setYear(yyyy)
                chkdate.setMonth(mm-1)
                chkdate.setDate(dd)
	            chkdate.setMinutes(minval)
                chkdate.setHours(hourval)
		if(!comparestartdate(chkdate)) return false;


	 }//end
	return true;
}
</SCRIPT>
'; ?>


<div id="allMenu" onMouseOut="fninvsh('allMenu');" onMouseOver="fnvshNrm('allMenu');" style="width:650px;z-index: 10000001;display:none;">
	<table border=0 cellpadding="5" cellspacing="0" class="allMnuTable" >
	<tr>
		<td valign="top">
		<?php $this->assign('parentno', 0); ?>
		<?php $_from = $this->_tpl_vars['QUICKACCESS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['parenttablist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['parenttablist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['parenttab'] => $this->_tpl_vars['details']):
        $this->_foreach['parenttablist']['iteration']++;
?>
			<span class="allMnuHdr"><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['parenttab']]; ?>
</span>
			<?php $_from = $this->_tpl_vars['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['modulelist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['modulelist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['modules']):
        $this->_foreach['modulelist']['iteration']++;
?>
       		<?php echo smarty_function_math(array('assign' => 'num','equation' => "x + y",'x' => $this->_tpl_vars['parentno'],'y' => 1), $this);?>

			<?php echo smarty_function_math(array('assign' => 'loopvalue','equation' => "x % y",'x' => $this->_tpl_vars['num'],'y' => 15), $this);?>

			<?php $this->assign('parentno', $this->_tpl_vars['num']); ?>
			<?php if ($this->_tpl_vars['loopvalue'] == '0'): ?>
				</td><td valign="top">
			<?php endif; ?>
			<?php $this->assign('modulelabel', getTranslatedString($this->_tpl_vars['modules'][1], $this->_tpl_vars['modules'][0])); ?>
			<a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=index&parenttab=<?php echo $this->_tpl_vars['parenttab']; ?>
" class="allMnu"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		<?php endforeach; endif; unset($_from); ?>
		</td>
	</tr>
</table>
</div>

<!-- Drop Down Menu in the Main Tab -->
<?php $_from = $this->_tpl_vars['QUICKACCESS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['parenttablist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['parenttablist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['parenttab'] => $this->_tpl_vars['details']):
        $this->_foreach['parenttablist']['iteration']++;
?>
<div class="drop_mnu" id="<?php echo $this->_tpl_vars['parenttab']; ?>
_sub" onMouseOut="fnHideDrop('<?php echo $this->_tpl_vars['parenttab']; ?>
_sub')" onMouseOver="fnShowDrop('<?php echo $this->_tpl_vars['parenttab']; ?>
_sub')">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php $_from = $this->_tpl_vars['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['modulelist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['modulelist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['modules']):
        $this->_foreach['modulelist']['iteration']++;
?>
		<?php $this->assign('modulelabel', getTranslatedString($this->_tpl_vars['modules'][1], $this->_tpl_vars['modules'][0])); ?>

				<?php $this->assign('moduleaction', 'index'); ?>
	   	<?php if (isset ( $this->_tpl_vars['modules'][2] )): ?>
	   		<?php $this->assign('moduleaction', $this->_tpl_vars['modules'][2]); ?>
	   	<?php endif; ?>

		<tr><td><a href="index.php?module=<?php echo $this->_tpl_vars['modules']['0']; ?>
&action=<?php echo $this->_tpl_vars['moduleaction']; ?>
&parenttab=<?php echo $this->_tpl_vars['parenttab']; ?>
" class="drop_down"><?php echo $this->_tpl_vars['modulelabel']; ?>
</a></td></tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
</div>
<?php endforeach; endif; unset($_from); ?>
</div>

<div id="status" style="position:absolute;display:none;left:850px;top:95px;height:27px;white-space:nowrap;"><img src="<?php echo aicrm_imageurl('status.gif', $this->_tpl_vars['THEME']); ?>
"></div>
<script>
function openwin()
{
    window.open("index.php?module=Users&action=about_us","aboutwin","height=520,width=515,top=200,left=300")
}

</script>


<div id="tracker" style="display:none;position:absolute;z-index:100000001;" class="layerPopup">

	<table border="0" cellpadding="5" cellspacing="0" width="200">
	<tr style="cursor:move;">
		<td colspan="2" class="mailClientBg small" id="Track_Handle"><strong><?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
</strong></td>
		<td align="right" style="padding:5px;" class="mailClientBg small">
		<a href="javascript:;"><img src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" border="0"  onClick="fninvsh('tracker')" hspace="5" align="absmiddle"></a>
		</td></tr>
	</table>
	<table border="0" cellpadding="5" cellspacing="0" width="200" class="hdrNameBg">
	<?php $_from = $this->_tpl_vars['TRACINFO']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['trackinfo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['trackinfo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['trackelements']):
        $this->_foreach['trackinfo']['iteration']++;
?>
	<tr>
		<td class="trackerListBullet small" align="center" width="12"><?php echo $this->_foreach['trackinfo']['iteration']; ?>
</td>
		<td class="trackerList small"> <a href="index.php?module=<?php echo $this->_tpl_vars['trackelements']['module_name']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['trackelements']['crmid']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['trackelements']['item_summary']; ?>
</a> </td><td class="trackerList small">&nbsp;</td></tr>
	<?php endforeach; endif; unset($_from); ?>
	</table>
</div>

<script>
	var THandle = document.getElementById("Track_Handle");
	var TRoot   = document.getElementById("tracker");
	Drag.init(THandle, TRoot);
</script>

<!-- vtiger news -->
<script type="text/javascript">
<?php echo '
function aicrm_news(obj) {
	$(\'status\').style.display = \'inline\';
	new Ajax.Request(
		\'index.php\',
		{queue: {position: \'end\', scope: \'command\'},
			method: \'post\',
			postBody: \'module=Home&action=HomeAjax&file=HomeNews\',
			onComplete: function(response) {
				$("vtigerNewsPopupLay").innerHTML=response.responseText;
				fnvshobj(obj, \'vtigerNewsPopupLay\');
				$(\'status\').style.display = \'none\';
			}
		}
	);

}
'; ?>

</script>

<div class="lvtCol fixedLay1" id="vtigerNewsPopupLay" style="display: none; height: 250px; bottom: 2px; padding: 2px; z-index: 12; font-weight: normal;" align="left">
</div>
<!-- END -->

<!-- divs for asterisk integration -->
<div class="lvtCol fixedLay1" id="notificationDiv" style="float: right;  padding-right: 5px; overflow: hidden; border-style: solid; right: 0px; border-color: rgb(141, 141, 141); bottom: 0px; display: none; padding: 2px; z-index: 10; font-weight: normal;" align="left">
</div>

<div id="OutgoingCall" style="display: none;position: absolute;z-index:200;" class="layerPopup">
	<table  border='0' cellpadding='5' cellspacing='0' width='100%'>
		<tr style='cursor:move;' >
			<td class='mailClientBg small' id='outgoing_handle'>
				<b><?php echo $this->_tpl_vars['APP']['LBL_OUTGOING_CALL']; ?>
</b>
			</td>
		</tr>
	</table>
	<table  border='0' cellpadding='0' cellspacing='0' width='100%' class='hdrNameBg'>
		</tr>
		<tr><td style='padding:10px;' colspan='2'>
			<?php echo $this->_tpl_vars['APP']['LBL_OUTGOING_CALL_MESSAGE']; ?>

		</td></tr>
	</table>
</div>
<!-- divs for asterisk integration :: end-->