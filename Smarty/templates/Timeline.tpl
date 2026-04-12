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
<!-- <script language="JavaScript" type="text/javascript" src="modules/PriceBooks/PriceBooks.js"></script> -->
<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<script type="text/javascript" src="asset/js/jquery.min.js"></script>
{include file='Buttons_List1.tpl'}
<!-- Contents -->
<div id="editlistprice" style="position:absolute;width:300px;"></div>
<div style="height: 85%;width: 100%;position: absolute;background-color: #dedede;opacity: 1;margin: 0;opacity: 0; z-index: 1;display: none;" class="bg-popup"></div>

<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<td valign=top><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:20px">
 	        {* Module Record numbering, used MOD_SEQ_ID instead of ID *}
            {if ($MODULE == 'HelpDesk')}
            <span class="lvtHeaderText"><font color="purple">[ {$MOD_SEQ_ID} ] </font>
            {php}
            	echo  $_REQUEST['name'];
            {/php} -  {$APP.SINGLE_MOD} {$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
            {else}
			 <span class="lvtHeaderText"><font color="purple">[ {$MOD_SEQ_ID} ] </font>{$NAME} -  {$SINGLE_MOD} {$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
            {/if}
             <br>
			 {$UPDATEINFO}
			 <hr noshade size=1>
			 <br>

			<!-- Account details tabs -->
			<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
			<tr>
				<td>
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
						<tr>
							{if $OP_MODE eq 'edit_view'}
		                        {assign var="action" value="EditView"}
                		    {else}
                            	{assign var="action" value="DetailView"}
		                    {/if}
							<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
							{if $MODULE eq 'Calendar'}
                                <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action={$action}&module={$MODULE}&record={$ID}&activity_mode={$ACTIVITY_MODE}&parenttab={$CATEGORY}">{$SINGLE_MOD} {$APP.LBL_INFORMATION}</a></td>
		                    {else}
                		      <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action={$action}&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$SINGLE_MOD} {$APP.LBL_INFORMATION}</a></td>
                            {/if}

							{if $SinglePane_View eq 'false'}
                                <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
                            {/if}
							
                            <td class="dvtSelectedCell" align=center nowrap>{$APP.LBL_TIMELINE}</td>
                            
                            <td class="dvtTabCache" style="width:100%">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign=top align=left >
		                	<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace" style="border-bottom:0;">
						<tr>
							<td align=left>
							<!-- content cache -->
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
									<tr>
										<td style="padding:10px">
										   <!-- General details -->
												{include file='TimeLineHidden.tpl'}
												<div id="RLContents">
                                                    {include file='TimeLineContents.tpl'}
           						                </div>
												</form>
										  {*-- End of Blocks--*}
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
						<tr>
							{if $OP_MODE eq 'edit_view'}
		                                                {assign var="action" value="EditView"}
                		                        {else}
                                		                {assign var="action" value="DetailView"}
		                                        {/if}
							<td class="dvtTabCacheBottom" style="width:10px" nowrap>&nbsp;</td>
							{if $MODULE eq 'Calendar'}
                                <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action={$action}&module={$MODULE}&record={$ID}&activity_mode={$ACTIVITY_MODE}&parenttab={$CATEGORY}">{$SINGLE_MOD} {$APP.LBL_INFORMATION}</a></td>
		                    {else}
                		        <td class="dvtUnSelectedCellBottom" align=center nowrap><a href="index.php?action={$action}&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$SINGLE_MOD} {$APP.LBL_INFORMATION}</a></td>
                            {/if}
							<!-- <td class="dvtTabCacheBottom" style="width:10px">&nbsp;</td> -->
							{if $SinglePane_View eq 'false'}
                                <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
                            {/if}
                            
                            <td class="dvtSelectedCellBottom" align=center nowrap>{$APP.LBL_TIMELINE}</td>

							<td class="dvtTabCacheBottom" style="width:100%">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
	  </div>
	<!-- PUBLIC CONTENTS STOPS-->
	</td>
	<td align=right valign=top><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
</tr>
</table>

{if $MODULE eq 'Leads' or $MODULE eq 'Contacts' or $MODULE eq 'Accounts' or $MODULE eq 'Campaigns' or $MODULE eq 'Vendors'}
<form name="SendMail" onsubmit="VtigerJS_DialogBox.block();"><div id="sendmail_cont" style="z-index:100001;position:absolute;width:300px;"></div></form>
{/if}

<script>
function OpenWindow(url)
{ldelim}
	openPopUp('xAttachFile',this,url,'attachfileWin',380,375,'menubar=no,toolbar=no,location=no,status=no,resizable=no');
{rdelim}
</script>
