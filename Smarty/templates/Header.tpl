{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	
	<title>{$CURRENT_USER} - {$APP.$CATEGORY} - {$APP.$MODULE_NAME} - {$APP.LBL_BROWSER_TITLE}</title>
	<link REL="SHORTCUT ICON" HREF="themes/AICRM.ico">
	<style type="text/css">@import url("themes/{$THEME}/style.css");</style>
	<!-- ActivityReminder customization for callback -->
	{literal}
	<style type="text/css">
	div.fixedLay1 { position:fixed; }

    </style>
	<!--[if lte IE 6]>
	<style type="text/css">div.fixedLay { position:absolute; }</style>
	<![endif]-->
	{/literal}
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
	<script language="JavaScript" type="text/javascript" src="include/js/{php} echo $_SESSION['authenticated_user_language'];{/php}.lang.js?{php} echo $_SESSION['aicrm_version'];{/php}"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/QuickCreate.js"></script>
	<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
	{if $APP.$MODULE_NAME eq 'Sales Visit'}
		<!-- <script type="text/javascript" src="asset/js/jquery.min.js"></script> -->
		<!-- <script type="text/javascript" src="asset/js/jquery-1.10.2.min.js"></script> -->
		<link rel="stylesheet" type="text/css" href="asset/css/bootstrap-modal.min.css">
		<script language="JavaScript" type="text/javascript" src="asset/js/bootstrap-modal.min.js"></script>
	{/if}
	<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
	<script language="JavaScript" type="text/javascript" src="modules/Calendar/script.js"></script>
	<script language="javascript" type="text/javascript" src="include/scriptaculous/dom-drag.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/notificationPopup.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
    <script type="text/javascript" src="jscalendar/calendar.js"></script>
    <script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
    <script type="text/javascript" src="jscalendar/lang/calendar-{$APP.LBL_JSCALENDAR_LANG}.js"></script>

    <!-- asterisk Integration -->
    {if $USE_ASTERISK eq 'true'}
    	<script type="text/javascript" src="include/js/asterisk.js"></script>
    	<script type="text/javascript">
    	if(typeof(use_asterisk) == 'undefined') use_asterisk = true;
    	</script>
    {/if}
    <!-- END -->

	{* vtlib customization: Inclusion of custom javascript and css as registered *}
	{if $HEADERSCRIPTS}
		<!-- Custom Header Script -->
		{foreach item=HEADERSCRIPT from=$HEADERSCRIPTS}
			<script type="text/javascript" src="{$HEADERSCRIPT->linkurl}"></script>
		{/foreach}
		<!-- END -->
	{/if}
	{if $HEADERCSS}
		<!-- Custom Header CSS -->
		{foreach item=HDRCSS from=$HEADERCSS}
			<link rel="stylesheet" type="text/css" href="{$HDRCSS->linkurl}"></script>
		{/foreach}
		<!-- END -->
	{/if}
	{* END *}

	{* PREFECTHING IMAGE FOR BLOCKING SCREEN USING VtigerJS_DialogBox API *}
    <img src="{'layerPopupBg.gif'|@aicrm_imageurl:$THEME}" style="display: none;"/>
    {* END *}
<!--
	<TABLE border=0 cellspacing=0 cellpadding=0 width="100%"  background="themes/softed/images/Ai-HEAD.jpg" style="background-repeat:no-repeat; border:1px solid #000;" height="30px">
	<tr>
		<td valign=top></td>
		<td width=100% align=center>
		{if $APP.$MODULE_NAME eq 'Dashboards'}
		<marquee id="rss" direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="javascript:stop();" onMouseOut="javascript:start();">&nbsp;{$ANNOUNCEMENT|escape}</marquee>
		{else}
                <marquee id="rss" direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="javascript:stop();" onMouseOut="javascript:start();">&nbsp;{$ANNOUNCEMENT}</marquee>
                {/if}
		</td>
		<td class=small nowrap>
			<table border=0 cellspacing=0 cellpadding=0>
			 <tr height="50px" valign="bottom">

			{* vtlib customization: Header links on the top panel *}
			{if $HEADERLINKS}
			<td style="padding-left:10px;padding-right:5px" class=small nowrap>
				<a href="javascript:;" onMouseOver="fnvshobj(this,'vtlib_headerLinksLay');" onClick="fnvshobj(this,'vtlib_headerLinksLay');">{$APP.LBL_MORE}</a> <img src="{'arrow_down.gif'|@aicrm_imageurl:$THEME}" border=0>
				<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_headerLinksLay"
					onmouseout="fninvsh('vtlib_headerLinksLay')" onMouseOver="fnvshNrm('vtlib_headerLinksLay')">
					<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b>{$APP.LBL_MORE}</b></td></tr>
					<tr  >
						<td>
							{foreach item=HEADERLINK from=$HEADERLINKS}
								{assign var="headerlink_href" value=$HEADERLINK->linkurl}
								{assign var="headerlink_label" value=$HEADERLINK->linklabel}
								{if $headerlink_label eq ''}
									{assign var="headerlink_label" value=$headerlink_href}
								{else}
									{* Pickup the translated label provided by the module *}
									{assign var="headerlink_label" value=$headerlink_label|@getTranslatedString:$HEADERLINK->module()}
								{/if}
								<a href="{$headerlink_href}" class="drop_down">{$headerlink_label}</a>
							{/foreach}
						</td>
					</tr>
					</table>
				</div>
			</td>
			{/if}
			{* END *}

			<!-- gmailbookmarklet customization -->
			<!-- <td style="padding-left:10px;padding-right:10px" class=small nowrap>
				{$GMAIL_BOOKMARKLET}
			 </td>-->
			 <!-- END -->
             <!--
			 {if $ADMIN_LINK neq ''} {* Show links only for admin *}
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="javascript:void(0);" onClick="aicrm_news(this)">{$APP.LBL_VTIGER_NEWS}</a></td>
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="javascript:void(0);" onClick="aicrm_feedback();">{$APP.LBL_FEEDBACK}</a></td>
			 {/if}
	-->
		<!--	 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=DetailView&record={$CURRENT_USER_ID}&modechk=prefview">{$APP.LBL_MY_PREFERENCES}</a></td>-->
             <!--
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap><a href="http://wiki.vtiger.com/index.php/Main_Page" target="_blank">{$APP.LNK_HELP}</a></td>
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap><a href="javascript:;" onClick="openwin();">{$APP.LNK_WEARE}</a></td>
             -->
		<!--		 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="index.php?module=Users&action=Logout">{$APP.LBL_LOGOUT}</a> ({$CURRENT_USER})</td>
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
		<!--<td class=tabSeperator><img src="{'spacer.gif'|@aicrm_imageurl:$THEME}" width=2px height=30px></td>		-->
			{foreach key=maintabs item=detail from=$HEADERS}
				{if $maintabs ne $CATEGORY}
              		{if $APP[$maintabs] eq 'My Home Page'}
                        <td class="tabUnSelected main-page" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Home_B.png" border=0 style="width: 20px; height: 20px;" class="image_hover"></a></td>
                        <!-- <td class="tabUnSelected main-page"   onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/softed/images/homeb.png" border=0 style="width: 15px; height: 15px;"></a></td> -->
                	{elseif $APP[$maintabs] eq 'Marketing'}
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Marketing_B.png" class="image_hover" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Sales'}
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Sales_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Service'}
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Services_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Analytics'}
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Analytics_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Inventory'}
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Inventory_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Tools'}
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Tools_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Settings'}
                		<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Setting_B.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;" class="image_hover">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{else}
						<td class="tabUnSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
					{/if}
                {else}
                	{if $APP[$maintabs] eq 'My Home Page'}
                        <td class="tabSelected main-page" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/home.png" border=0 style="width: 20px; height: 20px;"></a></td>
                	{elseif $APP[$maintabs] eq 'Marketing'}
                		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Marketing_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Sales'}
                		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Sales_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Service'}
                		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Services_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Analytics'}
                		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Analytics_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Inventory'}
                		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Inventory_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Tools'}
                		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Tools_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{elseif $APP[$maintabs] eq 'Settings'}
                		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}"><img src="themes/images/Setting_W.png" border=0 style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{else}
				 		<td class="tabSelected" onmouseover="fnDropDown(this,'{$maintabs}_sub');" onMouseOut="fnHideDrop('{$maintabs}_sub');" align="center" nowrap><a href="index.php?module={$detail[0]}&action=index&parenttab={$maintabs}">{$APP[$maintabs]}</a><img src="{'menuDnArrow.gif'|@aicrm_imageurl:$THEME}" border=0 style="padding-left:5px"></td>
                	{/if}
				{/if}
			{/foreach}

			<!-- <td style="padding-left:15px" nowrap>

				{if $CNT eq 1}
					<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onclick="QCreate(this);">
						<option value="none">{$APP.LBL_QUICK_CREATE}...</option>
                        {foreach  item=detail from=$QCMODULE}
                        <option value="{$detail.1}">{$APP.NEW}&nbsp;{$detail.0}</option>
                        {/foreach}
					</select>
				{else}
					<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onchange="QCreate(this);">
						<option value="none">{$APP.LBL_QUICK_CREATE}...</option>
                        {foreach  item=detail from=$QCMODULE}
                        <option value="{$detail.1}">{$APP.NEW}&nbsp;{$detail.0}</option>
                        {/foreach}
					</select>
				{/if}

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

	{if $CNT eq 1}
	<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onclick="QCreate(this);">
		<option value="none">{$APP.LBL_QUICK_CREATE}...</option>
		{foreach  item=detail from=$QCMODULE}
		<option value="{$detail.1}">{$APP.NEW}&nbsp;{$detail.0}</option>
		{/foreach}
	</select>
	{else}
	<select class="flat_select" id="qccombo" style="width:130px; height:30px"  onchange="QCreate(this);">
		<option value="none">{$APP.LBL_QUICK_CREATE}...</option>
		{foreach  item=detail from=$QCMODULE}
		<option value="{$detail.1}">{$APP.NEW}&nbsp;{$detail.0}</option>
		{/foreach}
	</select>
	{/if}

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
 <a href="index.php?module=Users&action=DetailView&record={$CURRENT_USER_ID}&modechk=prefview">
 	{if $IMAGEUSER neq ''}
 		<img src="{$IMAGEUSER}" style="width: 25px; height: 25px;"> {$CURRENT_USER} <!--{$APP.LBL_MY_PREFERENCES}--></a>
 	{else}
 		<img src="themes/softed/images/profile.png" style="width: 25px; height: 25px;"> {$CURRENT_USER} <!--{$APP.LBL_MY_PREFERENCES}--></a>
 	{/if}
</td>
<td class="user-signout">
 <!-- <a href="index.php?module=Users&action=Logout"><img src="themes/softed/images/logout_icon.png">   {$APP.LBL_LOGOUT}</a> -->
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
			<!--{foreach  key=maintabs item=detail from=$HEADERS}
				{if $maintabs eq $CATEGORY}
					{foreach  key=number item=module from=$detail}
						{assign var="modulelabel" value=$module}
      					{if $APP.$module}
      						{assign var="modulelabel" value=$APP.$module}
      					{/if}
						{if $module eq $MODULE_NAME}
							<td class="level2SelTab" nowrap><a href="index.php?module={$module}&action=index&parenttab={$maintabs}">{$modulelabel}</a></td>
						{else}
							<td class="level2UnSelTab" nowrap> <a href="index.php?module={$module}&action=index&parenttab={$maintabs}">{$modulelabel}</a> </td>
						{/if}
					{/foreach}
				{/if}
			{/foreach}-->

			{foreach key=maintabs item=details from=$QUICKACCESS}
				{if $maintabs eq $CATEGORY}
					{foreach  key=number item=modules from=$details}
						{assign var="modulelabel" value=$modules[1]|@getTranslatedString:$modules[0]}

	   					{* Use Custom module action if specified *}
						{assign var="moduleaction" value="index"}
	   					{if isset($modules[2])}
	   						{assign var="moduleaction" value=$modules[2]}
	   					{/if}	   					
	   					
	   					{if $MODULE_NAME eq 'Settings'}
	   						
	   						{if $URL_ACTION eq $moduleaction && $MODULE_NAME eq 'Settings'}
	   							<td class="level2SelTab" nowrap><a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$maintabs}">{$modulelabel}</a></td>
	   						
	   						{elseif ($URL_ACTION eq 'listroles' || $URL_ACTION eq 'ListProfiles' || $URL_ACTION eq 'listgroups' || $URL_ACTION eq 'OrgSharingDetailView' || $URL_ACTION eq 'DefaultFieldPermissions' || $URL_ACTION eq 'AuditTrailList' || $URL_ACTION eq 'ListLoginHistory' || $URL_ACTION eq 'list_Field' || $URL_ACTION eq 'list_account_send_email' || $URL_ACTION eq 'list_bounce_email' || $URL_ACTION eq 'list_server_email' || $URL_ACTION eq 'list_reply_email' || $URL_ACTION eq 'list_sender_sms' || $URL_ACTION eq 'weeklyplantemplates' || $URL_ACTION eq 'monthlyplantemplates' || $URL_ACTION eq 'listemailtemplates' || $URL_ACTION eq 'OrganizationConfig' || $URL_ACTION eq 'TaxConfig' || $URL_ACTION eq 'EmailConfig' || $URL_ACTION eq 'ListModuleOwners' || $URL_ACTION eq 'OrganizationTermsandConditions' || $URL_ACTION eq 'CustomModEntityNo' || $URL_ACTION eq 'CurrencyListView' || $URL_ACTION eq 'LayoutBlockList' || $URL_ACTION eq 'Settingprovince') && $moduleaction eq 'index'}
	   							<td class="level2SelTab"  nowrap><a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$maintabs}">{$modulelabel}</a></td>
	   						
	   						{else}
	   							<td class="level2UnSelTab" nowrap><a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$maintabs}">{$modulelabel}</a></td>
	   						{/if}
	   					
	   					{elseif $MODULE_NAME eq 'PickList' || $MODULE_NAME eq 'Administration' || $MODULE_NAME eq 'Tooltip' || $MODULE_NAME eq 'FieldFormulas'}
	   						
	   						{if $moduleaction eq 'index'}
	   							<td class="level2SelTab"  nowrap><a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$maintabs}">{$modulelabel}</a></td>
	   						{else}
	   							<td class="level2UnSelTab" nowrap><a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$maintabs}">{$modulelabel}</a></td>
	   						{/if}


	   					{else}
	   						{if $modules.0 eq $MODULE_NAME}
								<td class="level2SelTab"  nowrap><a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$maintabs}">{$modulelabel}</a></td>
							{else}
								<td class="level2UnSelTab"   nowrap> <a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$maintabs}">{$modulelabel}</a> </td>
							{/if}
	   					{/if}
						


					{/foreach}
				{/if}
			{/foreach}
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
	{include file="Clock.tpl"}

<div id="qcform" style="position:absolute;width:700px;top:80px;left:250px;z-index:100000;"></div>

<div id="newqcform" style="position:absolute;width:700px;top:50%;left:50%;z-index:100000;transform: translate(-50%, -50%);"></div>
<!-- Unified Search module selection feature -->
<div id="UnifiedSearch_moduleformwrapper" style="position:absolute;width:400px;z-index:100002;display:none;"></div>
<script type='text/javascript'>
{literal}
function UnifiedSearch_SelectModuleForm(obj) {
	if($('UnifiedSearch_moduleform')) {
		// If we have loaded the form already.
		UnifiedSearch_SelectModuleFormCallback(obj);
	}else{
		$('status').show();
		new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Home&action=HomeAjax&file=UnifiedSearchModules&ajax=true',
			onComplete: function(response) {
				$('status').hide();
				$('UnifiedSearch_moduleformwrapper').innerHTML = response.responseText;
				UnifiedSearch_SelectModuleFormCallback(obj);
			}
		});
	}
}

function UnifiedSearch_SelectModuleFormCallback(obj) {
	fnvshobj(obj, 'UnifiedSearch_moduleformwrapper');
}

function UnifiedSearch_SelectModuleToggle(flag) {
	Form.getElements($('UnifiedSearch_moduleform')).each(
		function(element) {
			if(element.type == 'checkbox') {
				element.checked = flag;
			}
		}
	);
}

function UnifiedSearch_SelectModuleCancel() {
	$('UnifiedSearch_moduleformwrapper').hide();
}
function UnifiedSearch_SelectModuleSave() {
	var UnifiedSearch_form = document.forms.UnifiedSearch;
	UnifiedSearch_form.search_onlyin.value = Form.serialize($('UnifiedSearch_moduleform')).replace(/search_onlyin=/g, '').replace(/&/g,',');
	UnifiedSearch_SelectModuleCancel();
}
{/literal}
</script>
<!-- End -->

<script>
var gVTModule = '{$smarty.request.module|@vtlib_purify}';
function fetch_clock()
{ldelim}
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Utilities&action=UtilitiesAjax&file=Clock',
			onComplete: function(response)
				    {ldelim}
					$("clock_cont").innerHTML=response.responseText;
					execJS($('clock_cont'));
				    {rdelim}
		{rdelim}
	);
{rdelim}

function fetch_calc()
{ldelim}
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Utilities&action=UtilitiesAjax&file=Calculator',
			onComplete: function(response)
					{ldelim}
						$("calculator_cont").innerHTML=response.responseText;
						execJS($('calculator_cont'));
					{rdelim}
		{rdelim}
	);
{rdelim}
</script>

<script>
{literal}
function QCreate(qcoptions){
	var module = qcoptions.options[qcoptions.options.selectedIndex].value;
	if(module != 'none'){
		$("status").style.display="inline";
		if(module == 'Events'){
			module = 'Calendar';
			var urlstr = '&activity_mode=Events';
		}else if(module == 'Calendar'){
			module = 'Calendar';
			var urlstr = '&activity_mode=Task';
		}else{
			var urlstr = '';
		}
		new Ajax.Request(
			'index.php',
				{queue: {position: 'end', scope: 'command'},
				method: 'post',
				postBody: 'module='+module+'&action='+module+'Ajax&file=QuickCreate'+urlstr,
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
		hide('qcform');
	}
}

function QCreateinform(module){
	//var module = qcoptions.options[qcoptions.options.selectedIndex].value;
	if(module != 'none'){
		$("status").style.display="inline";
		if(module == 'Events'){
			module = 'Calendar';
			var urlstr = '&activity_mode=Events';
		}else if(module == 'Calendar'){
			module = 'Calendar';
			var urlstr = '&activity_mode=Task';
		}else{
			var urlstr = '';
		}
		new Ajax.Request(
			'index.php',
				{queue: {position: 'end', scope: 'command'},
				method: 'post',
				postBody: 'module='+module+'&action='+module+'Ajax&file=QuickCreateInform'+urlstr,
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
		hide('newqcform');
	}
}

function getFormValidate(divValidate)
{
  var st = document.getElementById('qcvalidate');
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
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
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
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
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
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
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
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
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
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
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
					if (window.document.QcEditView[curr_fieldname] != null && window.document.QcEditView[curr_fieldname].value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
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
						var startdatevalue = window.document.QcEditView[datefield].value.replace(/^\s+/g, '').replace(/\s+$/g, '')
				}
				if(qcfieldname[j] == "time_start")
				{
					var timelabel = qcfieldlabel[j]
						var timefield = qcfieldname[j]
						var timeval=window.document.QcEditView[timefield].value.replace(/^\s+/g, '').replace(/\s+$/g, '')
				}
				if(qcfieldname[j] == "eventstatus" || qcfieldname[j] == "taskstatus")
				{
					var statusvalue = window.document.QcEditView[curr_fieldname].options[window.document.QcEditView[curr_fieldname].selectedIndex].value.replace(/^\s+/g, '').replace(/\s+$/g, '')
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
{/literal}

{* Quick Access Functionality *}
<div id="allMenu" onMouseOut="fninvsh('allMenu');" onMouseOver="fnvshNrm('allMenu');" style="width:650px;z-index: 10000001;display:none;">
	<table border=0 cellpadding="5" cellspacing="0" class="allMnuTable" >
	<tr>
		<td valign="top">
		{assign var="parentno" value=0}
		{foreach name=parenttablist key=parenttab item=details from=$QUICKACCESS}
			<span class="allMnuHdr">{$APP[$parenttab]}</span>
			{foreach name=modulelist item=modules from=$details}
       		{math assign="num" equation="x + y" x=$parentno y=1}
			{math assign="loopvalue" equation="x % y" x=$num y=15}
			{assign var="parentno" value=$num}
			{if $loopvalue eq '0'}
				</td><td valign="top">
			{/if}
			{assign var="modulelabel" value=$modules[1]|@getTranslatedString:$modules[0]}
			<a href="index.php?module={$modules.0}&action=index&parenttab={$parenttab}" class="allMnu">{$modulelabel}</a>
			{/foreach}
		{/foreach}
		</td>
	</tr>
</table>
</div>

<!-- Drop Down Menu in the Main Tab -->
{foreach name=parenttablist key=parenttab item=details from=$QUICKACCESS}
<div class="drop_mnu" id="{$parenttab}_sub" onMouseOut="fnHideDrop('{$parenttab}_sub')" onMouseOver="fnShowDrop('{$parenttab}_sub')">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		{foreach name=modulelist item=modules from=$details}
		{assign var="modulelabel" value=$modules[1]|@getTranslatedString:$modules[0]}

		{* Use Custom module action if specified *}
		{assign var="moduleaction" value="index"}
	   	{if isset($modules[2])}
	   		{assign var="moduleaction" value=$modules[2]}
	   	{/if}

		<tr><td><a href="index.php?module={$modules.0}&action={$moduleaction}&parenttab={$parenttab}" class="drop_down">{$modulelabel}</a></td></tr>
		{/foreach}
	</table>
</div>
{/foreach}
</div>

<div id="status" style="position:absolute;display:none;left:850px;top:95px;height:27px;white-space:nowrap;"><img src="{'status.gif'|@aicrm_imageurl:$THEME}"></div>
<script>
function openwin()
{ldelim}
    window.open("index.php?module=Users&action=about_us","aboutwin","height=520,width=515,top=200,left=300")
{rdelim}

</script>


<div id="tracker" style="display:none;position:absolute;z-index:100000001;" class="layerPopup">

	<table border="0" cellpadding="5" cellspacing="0" width="200">
	<tr style="cursor:move;">
		<td colspan="2" class="mailClientBg small" id="Track_Handle"><strong>{$APP.LBL_LAST_VIEWED}</strong></td>
		<td align="right" style="padding:5px;" class="mailClientBg small">
		<a href="javascript:;"><img src="{'close.gif'|@aicrm_imageurl:$THEME}" border="0"  onClick="fninvsh('tracker')" hspace="5" align="absmiddle"></a>
		</td></tr>
	</table>
	<table border="0" cellpadding="5" cellspacing="0" width="200" class="hdrNameBg">
	{foreach name=trackinfo item=trackelements from=$TRACINFO}
	<tr>
		<td class="trackerListBullet small" align="center" width="12">{$smarty.foreach.trackinfo.iteration}</td>
		<td class="trackerList small"> <a href="index.php?module={$trackelements.module_name}&action=DetailView&record={$trackelements.crmid}&parenttab={$CATEGORY}">{$trackelements.item_summary}</a> </td><td class="trackerList small">&nbsp;</td></tr>
	{/foreach}
	</table>
</div>

<script>
	var THandle = document.getElementById("Track_Handle");
	var TRoot   = document.getElementById("tracker");
	Drag.init(THandle, TRoot);
</script>

<!-- vtiger news -->
<script type="text/javascript">
{literal}
function aicrm_news(obj) {
	$('status').style.display = 'inline';
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Home&action=HomeAjax&file=HomeNews',
			onComplete: function(response) {
				$("vtigerNewsPopupLay").innerHTML=response.responseText;
				fnvshobj(obj, 'vtigerNewsPopupLay');
				$('status').style.display = 'none';
			}
		}
	);

}
{/literal}
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
				<b>{$APP.LBL_OUTGOING_CALL}</b>
			</td>
		</tr>
	</table>
	<table  border='0' cellpadding='0' cellspacing='0' width='100%' class='hdrNameBg'>
		</tr>
		<tr><td style='padding:10px;' colspan='2'>
			{$APP.LBL_OUTGOING_CALL_MESSAGE}
		</td></tr>
	</table>
</div>
<!-- divs for asterisk integration :: end-->