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

<!-- {php} if($_REQUEST['action'] != 'TimelineList'){ {/php} -->
<script type="text/javascript" src="modules/{$MODULE}/{$MODULE}.js"></script>
<!-- {php} } {/php} -->

<div class="card-btn">
	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
		<tr><td style="height:2px"></td></tr>
		<tr>
			{if $MODULE eq 'Calendar'}
			{assign var="MODULELABEL" value='Sales Visit'}
			{else}
			{assign var="MODULELABEL" value=$MODULE|@getTranslatedString:$MODULE}
			{/if}

			{if $CATEGORY eq 'Settings'}
			<!-- No List View in Settings - Action is index -->
			<td style="padding-left:15px !important; padding-right:50px" class="moduleName" nowrap><a class="hdrLink" href="index.php?action=index&module={$MODULE}&parenttab={$CATEGORY}">{$MODULELABEL}</a></td>
			{else}
			<td style="padding-left:15px !important; padding-right:50px" class="moduleName" nowrap>{$APP.$CATEGORY} > <a class="hdrLink" href="index.php?action=ListView&module={$MODULE}&parenttab={$CATEGORY}">{$MODULELABEL}</a></td>
			{/if}
			<td width=50% nowrap>

				<table border="0" cellspacing="0" cellpadding="0" >
					<tr>
						<td class="sep1" style="width:1px;"></td>
						<td class=small >
							<!-- Add and Search -->
							<table border=0 cellspacing=0 cellpadding=0>
								<tr>
									<td>
										<table border=0 cellspacing=0 cellpadding=2>
											<tr>
												{if $CHECK.EditView eq 'yes' && $MODULE neq 'Inspection'}
													{if $MODULE eq 'Calendar'}
														<td class="submenu" style="padding-right:10px;padding-left:20px;">
															<a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}">
																<img src="themes/softed/images/create.png" alt="{$APP.LBL_CREATE_BUTTON_LABEL} {$SINGLE_MOD}..." title="{$APP.LBL_CREATE_BUTTON_LABEL} {$SINGLE_MOD}..." border=0 style="width: 20px; vertical-align: middle;">
																{$APP.LBL_CREATE_BUTTON_LABEL}
															</a>
														</td>
													{elseif $MODULE eq 'Opportunity'  || $MODULE eq 'Voucher'}
														<td style="padding-right:10px;padding-left:20px;">
														<img src="themes/softed/images/create_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
														<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
	                                                            {$APP.LBL_CREATE_BUTTON_LABEL}
	                                                        </span>
														</td>
													{else}
														<td class="submenu" style="padding-right:10px;padding-left:20px;">
															<a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}">
																<img src="themes/softed/images/create.png" alt="{$APP.LBL_CREATE_BUTTON_LABEL} {$SINGLE_MOD}..." title="{$APP.LBL_CREATE_BUTTON_LABEL} {$SINGLE_MOD}..." border=0 style="width:20px;height:20px;vertical-align: middle;">
																{$APP.LBL_CREATE_BUTTON_LABEL}
															</a>
														</td>
													{/if}
												{else}
													<td style="padding-right:10px;padding-left:20px;">
														<!-- <img src="{$IMAGE_PATH}btnL3Add-Faded.gif" border=0> -->
														<img src="themes/softed/images/create_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
														<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
	                                                            {$APP.LBL_CREATE_BUTTON_LABEL}
	                                                        </span>
													</td>
												{/if}
													<td style="padding-right:5px">
														<img src="themes/softed/images/search_g.png" border=0 style="width:25px;height:20px;vertical-align: middle;">
														<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9;">{$APP.LBL_SEARCH_TITLE}</span>
													</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
						<!-- <td style="width:10px;">&nbsp;</td> -->
						<td class="small">
							<!-- Calendar Clock Calculator and Chat -->
							<table border=0 cellspacing=0 cellpadding=5>
								<tr>
									{if $CALENDAR_DISPLAY eq 'true'}
									
										{if $CATEGORY eq 'Settings' || $CATEGORY eq 'Tools' || $CATEGORY eq 'Analytics'}
										
											{if $CHECK.Calendar eq 'yes'}
												<td class="submenu" style="padding-right:10px;padding-left:10px;">
													<a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=My Home Page");'>
														<img src="themes/softed/images/calendar.png" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0 style="width:20px;height:20px;vertical-align: middle;">
														{$APP.LBL_CALENDAR_TITLE}
													</a>
												</td>
											{else}
												<td style="padding-right:10px;padding-left:10px;">
													<img src="themes/softed/images/calendar_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
													<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
														{$APP.LBL_CALENDAR_TITLE}
													</span>
												</td>
											{/if}
										
										{else}
											{if $CHECK.Calendar eq 'yes'}
												<td class="submenu" style="padding-right:10px;padding-left:10px;">
													<a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab={$CATEGORY}");'>
														<img src="themes/softed/images/calendar.png" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0 style="width:20px;height:20px; vertical-align: middle;">
														{$APP.LBL_CALENDAR_TITLE}
													</a>
												</td>
											{else}
												<td style="padding-right:10px;padding-left:10px;">
													<img src="{'btnL3Calendar-Faded.gif'|@aicrm_imageurl:$THEME}">
												</td>
											{/if}
										{/if}
									{/if}

									{if $WORLD_CLOCK_DISPLAY eq 'true'}
									<!-- <td style="padding-right:0px"><a href="javascript:;"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td>-->
									{/if}
									{if $CALCULATOR_DISPLAY eq 'true'}
									<!--<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td>-->
									{/if}
									{if $CHAT_DISPLAY eq 'true'}
									<!--<td style="padding-right:0px"><a href="javascript:;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}tbarChat.gif" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0></a>-->
										{/if}
									</td>
					<!--<td style="padding-right:5px"><img src="{$IMAGE_PATH}btnL3Tracker.gif" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border=0 onClick="fnvshobj(this,'tracker');">
					</td>-->
				</tr>
			</table>
		</td>
		<!-- <td style="width:10px;">&nbsp;</td> -->
		<td class="small">
			<!-- Import / Export -->
			<table border=0 cellspacing=0 cellpadding=5>
				<tr>

					{* vtlib customization: Hook to enable import/export button for custom modules. Added CUSTOM_MODULE *}

					{if $MODULE eq 'HelpDesk' || $MODULE eq 'Contacts'  || $MODULE eq 'Accounts' || $MODULE eq 'Documents' ||  $CUSTOM_MODULE eq 'true' }

					{if $CHECK.Import eq 'yes' && $MODULE neq 'Documents' && $MODULE neq 'Activitys'}
					<td class="submenu" style="padding-right:10px;padding-left:10px;">
						<a href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}">
							<img src="themes/softed/images/import.png" alt="{$APP.LBL_IMPORT} {$APP.$MODULE}" title="{$APP.LBL_IMPORT} {$APP.$MODULE}" border="0" style="width:20px;height:20px;vertical-align: middle;">
							{$APP.LBL_IMPORT}
						</a>
					</td>
					{else}
					<td style="padding-right:10px;padding-left:10px;">
						<!-- <img src="{$IMAGE_PATH}tbarImport-Faded.gif" border="0"> -->
						<img src="themes/softed/images/import_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
						<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
							{$APP.LBL_IMPORT}
						</span>
					</td>
					
					{/if}

					{if $CHECK.Export eq 'yes'}
					<td class="submenu" style="padding-right:0px; padding-left: 10px;">
						<a name='export_link' href="javascript:void(0)" onclick="return selectedRecords('{$MODULE}','{$CATEGORY}')">
							
							<img src="themes/softed/images/export.png" alt="{$APP.LBL_EXPORT} {$APP.$MODULE}" title="{$APP.LBL_EXPORT} {$APP.$MODULE}" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
							{$APP.LBL_EXPORT}
						</a>
					</td>
					{else}
					<td style="padding-right:10px padding-left: 10px;">
						<!-- <img src="{$IMAGE_PATH}tbarExport-Faded.gif" border="0"> -->
						<img src="themes/softed/images/export_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
						<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
							{$APP.LBL_EXPORT}
						</span>
					</td>
					<!-- <td style="padding-right:5px"><img src="{'tbarExport-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"></td> -->
					{/if}

					{else}
				<!--<td style="padding-right:0px;padding-left:5px;"><img src="{'tbarImport-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"></td>
					<td style="padding-right:5px"><img src="{'tbarExport-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"></td>-->
					{/if}
					{if $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts'|| $MODULE eq 'Products'|| $MODULE eq 'HelpDesk'}
					{if $VIEW eq true}
					<td style="padding-right:10px padding-left: 10px;">
						<a href="index.php?module={$MODULE}&action=FindDuplicateRecords&button_view&list_view">
							<img src="{'findduplicates.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_FIND_DUPICATES}" title="{$APP.LBL_FIND_DUPLICATES}" border="0">
						</a>
					</td>
					{else}
					<!--<td style="padding-right:5px"><img src="{'FindDuplicates-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"></td>-->
					{/if}
					{else}
					<!--<td style="padding-right:5px"><img src="{'FindDuplicates-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"></td>-->
					{/if}
				</tr>
			</table>
			<!-- <td style="width:10px;">&nbsp;</td> -->
			<td class="small">
				<!-- All Menu -->
				<table border=0 cellspacing=0 cellpadding=5>
					<tr>
						<td class="submenu" style="padding-left:10px; padding-right: 10px;">
							<a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')">
								<img src="themes/softed/images/menu.png" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0" style="width: 20px; height: 20px;vertical-align: middle;">
								{$APP.LBL_ALL_MENU_TITLE}
							</a>
						</td>
						{if $CHECK.moduleSettings eq 'yes'}
						<td class="submenu" style="padding-left:10px; padding-right: 10px;">
							<a href='index.php?module=Settings&action=ModuleManager&module_settings=true&formodule={$MODULE}&parenttab=Settings'>
								
								<img src="themes/softed/images/tools_b.png" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
								{$APP.LBL_SETTINGS}
							</a>
						</td>
						{/if}
					</tr>
				</table>
			</td>

			{if $MODULE eq 'Reports'}
			<td >
				<table border=0 cellspacing=0 cellpadding=0>
					<tr>
					<td style="padding-right:5px"><a href="javascript:;" onclick="gcurrepfolderid=0;fnvshobj(this,'reportLay');"><img src="{'reportsCreate.gif'|@aicrm_imageurl:$THEME}" alt="{$MOD.LBL_CREATE_REPORT}..." title="{$MOD.LBL_CREATE_REPORT}..." border=0></a></td>
		                        <td>&nbsp;</td>
		            <td style="padding-right:5px"><a href="javascript:;" onclick="createrepFolder(this,'orgLay');"><img src="{'reportsFolderCreate.gif'|@aicrm_imageurl:$THEME}" alt="{$MOD.Create_New_Folder}..." title="{$MOD.Create_New_Folder}..." border=0></a></td>
		                        <td>&nbsp;</td>
		            <td style="padding-right:5px"><a href="javascript:;" onclick="fnvshobj(this,'folderLay');"><img src="{'reportsMove.gif'|@aicrm_imageurl:$THEME}" alt="{$MOD.Move_Reports}..." title="{$MOD.Move_Reports}..." border=0></a></td>
		                        <td>&nbsp;</td>
		            <td style="padding-right:5px"><a href="javascript:;" onClick="massDeleteReport();"><img src="{'reportsDelete.gif'|@aicrm_imageurl:$THEME}" alt="{$MOD.LBL_DELETE_FOLDER}..." title="{$MOD.Delete_Report}..." border=0></a></td>
					</tr>
				</table>
			</td>
			{/if}
		</tr>
	</table>
</td>
<td>
	<div class="skin-greensena main-search">

		<form name="UnifiedSearch" method="post" action="index.php" style="margin:0px" onsubmit="VtigerJS_DialogBox.block();">
			<a href="javascript:void(0);" onclick="UnifiedSearch_SelectModuleForm(this);">
				<img src="themes/softed/images/searchmodule.png" align="left" border="0" style="margin-top:3px; width: 25px; padding-right: 5px;">
			</a>&nbsp;
			<input type="hidden" name="action" value="UnifiedSearch" style="margin:0px">
			<input type="hidden" name="module" value="Home" style="margin:0px">
			<input type="hidden" name="parenttab" value="Marketing" style="margin:0px">
			<input type="hidden" name="search_onlyin" value="--USESELECTED--" style="margin:0px">
			<input type="text" class="tftextinput" name="query_string" value="" onfocus="this.value=''" style="width:150px; margin-bottom: 5px;" placeholder="Searching">
			<!-- <input type="submit"  class="tfbutton"  value="Find" alt="Find" title="Find"> -->
			<button type="submit" class="tfbutton" value="Find" alt="Find" title="Find" style="width: 80px;">
				<i><img src="themes/softed/images/search.png" border="0" style="width: 10px;vertical-align: middle;"></i> &nbsp;Find
			</button>
		</form>

	</div>
</td>
</tr>
</TABLE>
</div>
