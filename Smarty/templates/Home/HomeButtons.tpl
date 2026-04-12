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

{*<!-- buttons for the home page -->*}
<div class="card-btn">
	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small class="small homePageButtons" style="padding-top: 10px; padding-bottom: 10px;">
		<tr>
			<td style="height:2px"></td>
		</tr>
		<tr>
			<td style="padding-left:10px;padding-right:50px" width=10% class="moduleName" nowrap>
				{$APP.$CATEGORY}&nbsp;&gt; 
				<a class="hdrLink" href="index.php?action=index&module={$MODULE}">
					{$APP.$MODULE}
				</a>
			</td>
			<td width=50% nowrap>
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="sep1" style="width:1px;"></td>
						<td class=small>
							<table border=0 cellspacing=0 cellpadding=0>
								<tr>
									<td>
										<table border=0 cellspacing=0 cellpadding=2>
											<tr>
												<td style="padding-left: 10px;">
													<!-- <img onClick='fnAddWindow(this,"addWidgetDropDown");' onMouseOut='fnRemoveWindow();' src="{'btnL3Add.gif'|@aicrm_imageurl:$THEME}" border="0" title="{$MOD.LBL_HOME_ADDWINDOW}" alt"{$MOD.LBL_HOME_ADDWINDOW}" style="cursor:pointer;"> -->
													<img src="themes/softed/images/create.png" border= "0" style="width: 20px; height: 20px; vertical-align: middle; cursor:pointer;" onClick='fnAddWindow(this,"addWidgetDropDown");' onMouseOut='fnRemoveWindow();' order="0" title="{$MOD.LBL_HOME_ADDWINDOW}" alt="{$MOD.LBL_HOME_ADDWINDOW}"> 
													<a style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" href="javascript:void(0)" onClick='fnAddWindow(this,"addWidgetDropDown");'>
														{$APP.LBL_CREATE_BUTTON_LABEL}
													</a>
												</td>
												{if $CHECK.Calendar eq 'yes' && $CALENDAR_ACTIVE eq 'yes' && $CALENDAR_DISPLAY eq 'true'}
												<td style="padding-left: 10px;">
													<!-- <img src="{'btnL3Calendar.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0  onClick='fnvshobj(this,"miniCal");getMiniCal();'/> -->

													<!-- <a style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=My Home Page");'>
                                                        <img src="themes/softed/images/calendar.png" border=0 style="width: 20px;height: 20px; vertical-align: middle;">
                                                        
                                                        {$APP.LBL_CALENDAR_TITLE}
                                                    </a> -->
												</td>
												{/if}
												{if $WORLD_CLOCK_DISPLAY eq 'true' }
												<td style="padding-left: 10px;">
													<!-- <img src="{'btnL3Clock.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');"> -->
													<!-- <img src="themes/softed/images/icon_clock_black.png" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');" style="width: 25px; height: 25px; vertical-align: middle;">
													<a style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" href="javascript:void(0)" onClick="fnvshobj(this,'wclock');">
														{$APP.LBL_CLOCK_TITLE}
													</a> -->
												</td>
												{/if}
												{if $CALCULATOR_DISPLAY eq 'true' }
												<td style="padding-left: 10px;">
													<!-- <img src="{'btnL3Calc.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"> -->
													<!-- <img src="themes/softed/images/icon_calculator_black.png" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();" style="width: 25px; height: 25px; vertical-align: middle;">
													<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick="fnvshobj(this,'calculator_cont');fetch_calc();">
														{$APP.LBL_CALCULATOR_TITLE}
													</a> -->
												</td>
												{/if}
												{if $CHAT_DISPLAY eq 'true' }
												<td style="padding-left: 10px;">
													<!-- <img src="{'tbarChat.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0  onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'> -->
													<!-- <img src="themes/softed/images/icon_chat_black.png" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0  onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");' style="width: 25px; height: 25px; vertical-align: middle;">
													<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'>
														{$APP.LBL_CHAT_TITLE}
													</a> -->
												</td>	
												{/if}
												<td style="padding-left: 10px;">
													<!-- <img  src="{'btnL3Tracker.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border="0" onClick="fnvshobj(this,'tracker');"> -->
													<img src="themes/softed/images/icon_lastview_black.png" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border="0" onClick="fnvshobj(this,'tracker');" style="width: 25px; height: 25px; vertical-align: middle;">
													<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick="fnvshobj(this,'tracker');">
														{$APP.LBL_LAST_VIEWED}
													</a>
												</td>
												<td style="padding-left: 10px;">
													<!-- <img src="{'btnL3AllMenu.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0" onmouseout="fninvsh('allMenu');" onClick="$('allMenu').style.display='block'; $('allMenu').style.visibility='visible';placeAtCenter($('allMenu'))"> -->

													<a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')" style="font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;">
			                                            <img src="themes/softed/images/menu.png" border="0" style="width: 20px; height: 20px; vertical-align: middle;">
			                                            {$APP.LBL_ALL_MENU_TITLE}
			                                        </a>

													<!-- <img src="themes/softed/images/menu.png" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0" onmouseout="fninvsh('allMenu');" onClick="$('allMenu').style.display='block'; $('allMenu').style.visibility='visible';placeAtCenter($('allMenu'))" style="width: 25px; height: 25px; vertical-align: middle;">
													
													<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick="$('allMenu').style.display='block'; $('allMenu').style.visibility='visible';placeAtCenter($('allMenu'))">
														{$APP.LBL_ALL_MENU_TITLE}
													</a> -->
												</td>
												<td style="padding-left: 10px;">
													<!-- <img  onClick='showOptions("changeLayoutDiv");' src="{'orgshar.gif'|@aicrm_imageurl:$THEME}" border="0" title="{$MOD.LBL_HOME_LAYOUT}" alt="{$MOD.LBL_HOME_LAYOUT}" style="cursor:pointer;"> -->
													<!-- <img src="themes/softed/images/icon_changelayout_black.png" onClick='showOptions("changeLayoutDiv");' border="0" title="{$MOD.LBL_HOME_LAYOUT}" alt="{$MOD.LBL_HOME_LAYOUT}" style="cursor:pointer; width: 25px; height: 25px; vertical-align: middle;">
													<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick='showOptions("changeLayoutDiv");'>
														{$MOD.LBL_HOME_LAYOUT}
													</a> -->
												</td>
												<td width="100%" align="center">
													<div id="vtbusy_info" style="display: none;">
														<img src="{'status.gif'|@aicrm_imageurl:$THEME}" border="0" />
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
	</TABLE>
</div>

<!-- <div class="card-btn">
	<table border=0 cellspacing=0 cellpadding=2 width="60%" class="small homePageButtons" style="padding-top: 10px; padding-bottom: 10px;">
		<tr style="cursor: pointer;">
			<td style="padding-left:10px;padding-right:50px" width=10% class="moduleName" nowrap>
				{$APP.$CATEGORY}&nbsp;&gt; 
				<a class="hdrLink" href="index.php?action=index&module={$MODULE}">
					{$APP.$MODULE}
				</a>
			</td>
			<td class="sep1" style="width:1px;"></td>	

			<td style="padding-left: 10px;" width="8%">
				<img onClick='fnAddWindow(this,"addWidgetDropDown");' onMouseOut='fnRemoveWindow();' src="{'btnL3Add.gif'|@aicrm_imageurl:$THEME}" border="0" title="{$MOD.LBL_HOME_ADDWINDOW}" alt"{$MOD.LBL_HOME_ADDWINDOW}" style="cursor:pointer;">
				<img src="themes/softed/images/create.png" border= "0" style="width: 20px; height: 20px; vertical-align: middle; cursor:pointer;" onClick='fnAddWindow(this,"addWidgetDropDown");' onMouseOut='fnRemoveWindow();' order="0" title="{$MOD.LBL_HOME_ADDWINDOW}" alt="{$MOD.LBL_HOME_ADDWINDOW}"> 
				<a style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" href="javascript:void(0)" onClick='fnAddWindow(this,"addWidgetDropDown");'>
					{$APP.LBL_CREATE_BUTTON_LABEL}
				</a>
				
			</td>

			{if $CHECK.Calendar eq 'yes' && $CALENDAR_ACTIVE eq 'yes' && $CALENDAR_DISPLAY eq 'true'}
			<td>
				<img src="{'btnL3Calendar.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0  onClick='fnvshobj(this,"miniCal");getMiniCal();'/>
			</td>
			{/if}
			{if $WORLD_CLOCK_DISPLAY eq 'true' }
			<td style="padding-left: 10px;" width="17%">
				<img src="{'btnL3Clock.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');">
				<img src="themes/softed/images/icon_clock_black.png" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');" style="width: 25px; height: 25px; vertical-align: middle;">
				<a style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" href="javascript:void(0)" onClick="fnvshobj(this,'wclock');">
					{$APP.LBL_CLOCK_TITLE}
				</a>
			</td>
			{/if}
			{if $CALCULATOR_DISPLAY eq 'true' }
			<td style="padding-left: 10px;" width="15%">
				<img src="{'btnL3Calc.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();">
				<img src="themes/softed/images/icon_calculator_black.png" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();" style="width: 25px; height: 25px; vertical-align: middle;">
				<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick="fnvshobj(this,'calculator_cont');fetch_calc();">
					{$APP.LBL_CALCULATOR_TITLE}
				</a>
			</td>
			{/if}
			{if $CHAT_DISPLAY eq 'true' }
			<td style="padding-left: 10px;" width="8%">
				<img src="{'tbarChat.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0  onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'>
				<img src="themes/softed/images/icon_chat_black.png" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0  onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");' style="width: 25px; height: 25px; vertical-align: middle;">
				<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'>
					{$APP.LBL_CHAT_TITLE}
				</a>
			</td>	
			{/if}
			<td style="padding-left: 10px;" width="13%">
				<img  src="{'btnL3Tracker.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border="0" onClick="fnvshobj(this,'tracker');">
				<img src="themes/softed/images/icon_lastview_black.png" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border="0" onClick="fnvshobj(this,'tracker');" style="width: 25px; height: 25px; vertical-align: middle;">
				<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick="fnvshobj(this,'tracker');">
					{$APP.LBL_LAST_VIEWED}
				</a>
			</td>

			<td style="padding-left: 10px;" width="15%">
				<img src="{'btnL3AllMenu.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0" onmouseout="fninvsh('allMenu');" onClick="$('allMenu').style.display='block'; $('allMenu').style.visibility='visible';placeAtCenter($('allMenu'))">
				<img src="themes/softed/images/menu.png" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0" onmouseout="fninvsh('allMenu');" onClick="$('allMenu').style.display='block'; $('allMenu').style.visibility='visible';placeAtCenter($('allMenu'))" style="width: 25px; height: 25px; vertical-align: middle;">
				<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick="$('allMenu').style.display='block'; $('allMenu').style.visibility='visible';placeAtCenter($('allMenu'))">
					{$APP.LBL_ALL_MENU_TITLE}
				</a>
			</td>

			<td style="padding-left: 10px;" width="20%">
				<img  onClick='showOptions("changeLayoutDiv");' src="{'orgshar.gif'|@aicrm_imageurl:$THEME}" border="0" title="{$MOD.LBL_HOME_LAYOUT}" alt="{$MOD.LBL_HOME_LAYOUT}" style="cursor:pointer;">
				<img src="themes/softed/images/icon_changelayout_black.png" onClick='showOptions("changeLayoutDiv");' border="0" title="{$MOD.LBL_HOME_LAYOUT}" alt="{$MOD.LBL_HOME_LAYOUT}" style="cursor:pointer; width: 25px; height: 25px; vertical-align: middle;">
				<a href="javascript:void(0)" style=" font-family: PromptMedium; font-size: 12px; color: #2B2B2B; font-weight: 400;" onClick='showOptions("changeLayoutDiv");'>
					{$MOD.LBL_HOME_LAYOUT}
				</a>
			</td>

			<td width="100%" align="center">
				<div id="vtbusy_info" style="display: none;">
					<img src="{'status.gif'|@aicrm_imageurl:$THEME}" border="0" />
				</div>
			</td>
		</tr>
	</table>

</div> -->

{*<!--button related stuff -->*}
<form name="Homestuff" id="formStuff" style="display: inline;">
	<input type="hidden" name="action" value="homestuff">
	<input type="hidden" name="module" value="Home">
	<div id='addWidgetDropDown' style='background-color: #fff; display:none;' onmouseover='fnShowWindow()' onmouseout='fnRemoveWindow()'>
		<ul class="widgetDropDownList">
		<li>
			<a href='javascript:;' class='drop_down' id="addmodule">
				{$MOD.LBL_HOME_MODULE}
			</a>
		</li>
{if $ALLOW_RSS eq "yes"}
		<li>
			<a href='javascript:;' class='drop_down' id="addrss">
				{$MOD.LBL_HOME_RSS}
			</a>
		</li>
{/if}	
{if $ALLOW_DASH eq "yes"}
		<li>
			<a href='javascript:;' class='drop_down' id="adddash">
				{$MOD.LBL_HOME_DASHBOARD}
			</a>
		</li>
{/if}
		<li>
			<a href='javascript:;' class='drop_down' id="addNotebook">
				{$MOD.LBL_NOTEBOOK}
			</a>
		</li>
		{*<!-- this has been commented as some websites are opening up in full page (they have a target="_top")
		<li>
			<a href='javascript:;' class='drop_down' id="addURL">
				{$MOD.LBL_URL}
			</a>
		</li>
		-->*}
	</div>
	
	{*<!-- the following div is used to display the contents for the add widget window -->*}
	<div id="addWidgetsDiv" class="layerPopup" style="z-index:2000; display:none; width: 400px;">
		<input type="hidden" name="stufftype" id="stufftype_id">	
		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
		<tr>
			<td class="layerPopupHeading" align="left" id="divHeader"></td>
			<td align="right"><a href="javascript:;" onclick="fnhide('addWidgetsDiv');$('stufftitle_id').value='';"><img src="{'close.gif'|@aicrm_imageurl:$THEME}" border="0"  align="absmiddle" /></a></td>
		</tr>
		</table>
		<table border=0 cellspacing=0 cellpadding=5 width=95% align=center> 
		<tr>
			<td class=small >
			{*<!-- popup specific content fill in starts -->*}
			<table border="0" cellspacing="0" cellpadding="3" width="100%" align="center" bgcolor="white">
			<tr id="StuffTitleId" style="display:block;">
				<td class="dvtCellLabel"  width="110" align="right">
					{$MOD.LBL_HOME_STUFFTITLE}
					<font color='red'>*</font>
				</td>
				<td class="dvtCellInfo" colspan="2" width="300">
					<input type="text" name="stufftitle" id="stufftitle_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:57%">
				</td>
			</tr>
			{*<!--
			<tr id="homeURLField" style="display:block;">
				<td class="dvtCellLabel"  width="110" align="right">
					{$MOD.LBL_URL}
					<font color='red'>*</font>
				</td>
				<td class="dvtCellInfo" colspan="2" width="300">
					<input type="text" name="url" id="url_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:57%">
				</td>
			</tr>
			-->*}
			<tr id="showrow">
				<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_SHOW}</td>
				<td class="dvtCellInfo" width="300" colspan="2">
					<select name="maxentries" id="maxentryid" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
						{section name=iter start=1 loop=13 step=1}
						<option value="{$smarty.section.iter.index}">{$smarty.section.iter.index}</option>
						{/section}
					</select>&nbsp;&nbsp;{$MOD.LBL_HOME_ITEMS}
				</td>
			</tr>
			<tr id="moduleNameRow" style="display:block">
				<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_MODULE}</td>
				<td width="300" class="dvtCellInfo" colspan="2">
					<select name="selmodule" id="selmodule_id" onchange="setFilter(this)" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
						{foreach item=arr from=$MODULE_NAME}
							{assign var="MODULE_LABEL" value=$arr.1|getTranslatedString:$arr.1}
							<option value="{$arr.1}">{$MODULE_LABEL}</option>
						{/foreach}
					</select>
					<input type="hidden" name="fldname">
				</td>
			</tr>
			<tr id="moduleFilterRow" style="display:block">
				<td class="dvtCellLabel" align="right" width="110" >{$MOD.LBL_HOME_FILTERBY}</td>
				<td id="selModFilter_id" colspan="2" class="dvtCellInfo" width="300">
				</td>
			</tr>
			<tr id="modulePrimeRow" style="display:block">
				<td class="dvtCellLabel" width="110" align="right" valign="top">{$MOD.LBL_HOME_Fields}</td>
				<td id="selModPrime_id" colspan="2" class="dvtCellInfo" width="300">
				</td>
			</tr>
			<tr id="rssRow" style="display:none">
				<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_RSSURL}<font color='red'>*</font></td>
				<td width="300" colspan="2" class="dvtCellInfo"><input type="text" name="txtRss" id="txtRss_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:58%"></td>
			</tr>
			<tr id="dashNameRow" style="display:none">
				<td class="dvtCellLabel"  width="110" align="right">{$MOD.LBL_HOME_DASHBOARD_NAME}</td>
				<td id="selDashName" class="dvtCellInfo" colspan="2" width="300"></td>
			</tr>
			<tr id="dashTypeRow" style="display:none">
				<td class="dvtCellLabel" align="right" width="110">{$MOD.LBL_HOME_DASHBOARD_TYPE}</td>
				<td id="selDashType" class="dvtCellInfo" width="300" colspan="2">
					<select name="seldashtype" id="seldashtype_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
						<option value="horizontalbarchart">{$MOD.LBL_HOME_HORIZONTAL_BARCHART}</option>
						<option value="verticalbarchart">{$MOD.LBL_HOME_VERTICAL_BARCHART}</option>
						<option value="piechart">{$MOD.LBL_HOME_PIE_CHART}</option>
					</select>
				</td>
			</tr>
			</table>	
			{*<!-- popup specific content fill in ends -->*}
			</td>
		</tr>
		</table>
		
		<table border=0 cellspacing=0 cellpadding=5 width=95% align="center">
			<tr>
				<td align="right">
					<input type="button" name="save" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " id="savebtn" class="crmbutton small save" onclick="frmValidate()"></td>
				<td align="left"><input type="button" name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="crmbutton small cancel" onclick="fnhide('addWidgetsDiv');$('stufftitle_id').value='';">
				</td>
			</tr>
		</table>
	</div>
</form>
{*<!-- add widget code ends -->*}

<div id="seqSettings" style="background-color:#E0ECFF;z-index:6000000;display:none;">
</div>


<div id="changeLayoutDiv" class="layerPopup" style="z-index:2000; display:none;">
	<table>
	<tr class="layerHeadingULine">
		<td class="big">
			{$MOD.LBL_HOME_LAYOUT}
		</td>
		<td>
			<img onclick="hideOptions('changeLayoutDiv');" src="{'close.gif'|@aicrm_imageurl:$THEME}" border="0" align="right" style="cursor: pointer;"/>
		</td>
	</tr>
	<tr id="numberOfColumns">
		<td class="dvtCellLabel" align="right">
			{$MOD.LBL_NUMBER_OF_COLUMNS}
		</td>
		<td class="dvtCellLabel">
			<select id="layoutSelect" class="small">
				<option value="2">
					{$MOD.LBL_TWO_COLUMN}
				</option>
				<option value="3">
					{$MOD.LBL_THREE_COLUMN}
				</option>
				<option value="4">
					{$MOD.LBL_FOUR_COLUMN}
				</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">
			<input type="button" name="save" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " id="savebtn" class="crmbutton small save" onclick="saveLayout();">
		</td>
		<td align="left">
			<input type="button" name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="crmbutton small cancel" onclick="hideOptions('changeLayoutDiv');">
		</td>
	</tr>
	
	</table>
</div>
