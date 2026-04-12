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
<br />
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
	<tr>
		<td valign="top"><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
	    <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	    	<br />
	    	<div align=center>
			{include file='SetMenu.tpl'}

			<table class="settingsSelUITopLine" align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
			    	
					<td rowspan="2" valign="top" width="50"><img src="{'vtlib_modmng.gif'|@aicrm_imageurl:$THEME}" alt="{$MOD.LBL_MODULE_MANAGER}" title="{$MOD.LBL_MODULE_MANAGER}" border="0" height="48" width="48"></td>
					<td class="heading2" valign="bottom"> <b><a href="index.php?module=Settings&action=ModuleManager&parenttab=Settings">{$MOD.VTLIB_LBL_MODULE_MANAGER}</a> &gt; {$MODULE_LBL} </td>
				</tr>
				<tr>
					<td class="small" valign="top">{$MOD.VTLIB_LBL_MODULE_MANAGER_DESCRIPTION}</td>
				</tr>
				</table>
				
				<br>
				<table border="0" cellspacing="0" cellpadding="20" width="100%" class="settingsUI">
					<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="10" width="100%">
								<tr>
									{foreach key=mod_name item=mod_array from=$MENU_ARRAY name=itr}
									<td width=25% valign=top>
										{if $mod_array.label eq ''}
											&nbsp;
										{else}
										<table border=0 cellspacing=0 cellpadding=5 width="100%">
											<tr>
												{assign var=count value=$smarty.foreach.itr.iteration}
												<td rowspan=2 valign=top width="20%">
													<a href="{$mod_array.location}">
													<img src="{$mod_array.image_src}" alt="{$mod_array.label}" width="48" height="48" border=0 title="{$mod_array.label}">
													</a>
												</td>
												<td class=big valign=top>
													<a href="{$mod_array.location}">
													{$mod_array.label}
													</a>
												</td>
											</tr>
											<tr>
												<td class="small" valign=top width="80%">
													{$mod_array.desc}
												</td>
											</tr>
										</table>
										{/if}
									</td>
									{if $count mod 3 eq 0}
										</tr><tr>
									{/if}
									{/foreach}
								</tr>
							</table>
						</td>
					</tr>
				</table>

				
				<br>

				{if $MODULE_LBL eq 'Sales Visit' OR $MODULE_LBL eq 'Job'}
					{if $MODULE_LBL eq 'Sales Visit'}
						{assign var='forModule' value='Calendar'}
					{/if}

					{if $MODULE_LBL eq 'Job'}
						{assign var='forModule' value='Job'}
					{/if}
					<table border="0" cellspacing="0" cellpadding="20" width="100%" class="settingsUI">
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="10" width="100%">
									<tr>
										<td width=25% valign=top>
											<table border=0 cellspacing=0 cellpadding=5 width="100%">
												<tr>
													<td rowspan=2 valign=top width="20%">
														<a href="index.php?module=SettingUpload&action=index&parenttab=Settings&formodule={$forModule}">
															<img src="themes/images/currency.gif" alt="" width="48" height="48" border=0 title="Member Grade">
														</a>
													</td>
													<td class=big valign=top>
														<a href="index.php?module=SettingUpload&action=index&parenttab=Settings&formodule={$forModule}">
														Setting Upload
														</a>
													</td>
												</tr>
												<tr>
													<td class="small" valign=top width="80%">
														Config upload channel for {$MODULE_LBL}
													</td>
												</tr>
											</table>
										</td>
										{if $MODULE_LBL eq 'Sales Visit'}
										<td width=25% valign=top>
											<table border=0 cellspacing=0 cellpadding=0 width="100%">
												<tr>
													<td rowspan=2 valign=top width="20%">
														<a href="index.php?module=SettingCheckin&action=index&parenttab=Settings&formodule={$forModule}">
															<img src="themes/images/map-pin-line-duotone.png" alt="" width="48" height="48" border=0 title="Setting Check-in">
														</a>
													</td>
													<td class=big valign=top>
														<a href="index.php?module=SettingCheckin&action=index&parenttab=Settings&formodule={$forModule}">
														Config Check-in/out Range
														</a>
													</td>
												</tr>
												<tr>
													<td class="small" valign=top width="80%">
														Config Check-in/out Range
													</td>
												</tr>
											</table>
										</td>
										{else}
										<td></td>
										{/if}
										<td></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				{/if}

				{if $MODULE_LBL eq 'Deal'}
				<table border="0" cellspacing="0" cellpadding="20" width="100%" class="settingsUI">
					<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="10" width="100%">
								<tr>
									<td width=25% valign=top>
										<table border=0 cellspacing=0 cellpadding=5 width="100%">
											<tr>
												<td rowspan=2 valign=top width="20%">
													<a href="index.php?module=SettingComment&action=index&parenttab=Settings&formodule={$MODULE_LBL}">
														<img src="themes/images/currency.gif" alt="" width="48" height="48" border=0 title="Member Grade">
													</a>
												</td>
												<td class=big valign=top>
													<a href="index.php?module=SettingComment&action=index&parenttab=Settings&formodule={$MODULE_LBL}">
														Settinf Comment box
													</a>
												</td>
											</tr>
											<tr>
												<td class="small" valign=top width="80%">
													Settinf Comment box for {$MODULE_LBL}
												</td>
											</tr>
										</table>
									</td>
									<td></td>
									<td></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				{/if}
				
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
			</div>
		</td>
		<td valign="top"><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
	</tr>
</table>
<br>