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
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>

<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
        <br>

	<div align=center>
			{include file='SetMenu.tpl'}
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<form action="index.php" method="post" name="new" id="form" onsubmit="VtigerJS_DialogBox.block();">
				<input type="hidden" name="module" value="Settings">
				<input type="hidden" name="action" value="createnewgroup">
				<input type="hidden" name="mode" value="create">
				<input type="hidden" name="parenttab" value="Settings">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{'ico-groups.gif'|@aicrm_imageurl:$THEME}" alt="{$MOD.LBL_TOLL_WAY}" width="48" height="48" border=0 title="{$MOD.LBL_TOLL_WAY}"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$CMOD.LBL_Target_Menu}</b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_Target_Menu}</td>
				</tr>
				</table>
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>

				<td>
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong>{$MOD.LBL_Target_Menu}</strong></td>
						<td class="small" align=right>{$CMOD.LBL_TOTAL} {$GRPCNT} Record </td>
					</tr>
					</table>
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTableTopButtons">
					<tr>
						<td class=small align=right><a href="#" onClick="javascript:window.open('modules/Settings/edit_config_targeted.php?action_type=list_config_targeted&action=add' , '','menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=0,width=500,height=500,top=150,left=400 ' )";><input type="button" value="New Targeted" title="New Targeted" class="crmButton create small"></a></td>
					</tr>
					</table>
						
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTable">
					<tr>
						<td class="colHeader small" valign=top width=2%>#</td>
						<td class="colHeader small" valign=top width=8% align="center">{$LIST_HEADER.0}</td>
						<td class="colHeader small" valign=top width=7% align="center">{$LIST_HEADER.1}</td>
						<td class="colHeader small" valign=top width=7% align="center">{$LIST_HEADER.2}</td>
                        <td class="colHeader small" valign=top width=7% align="center">{$LIST_HEADER.3}</td>
                        <td class="colHeader small" valign=top width=7% align="center">{$LIST_HEADER.4}</td>
                        <td class="colHeader small" valign=top width=15% align="center">{$LIST_HEADER.5}</td>
                        <td class="colHeader small" valign=top width=17% align="center">{$LIST_HEADER.6}</td>
                        <td class="colHeader small" valign=top width=15% align="center">{$LIST_HEADER.7}</td>
                        <td class="colHeader small" valign=top width=15% align="center">{$LIST_HEADER.8}</td>
					  </tr>
						{foreach name=otlist item=otvalues from=$LIST_ENTRIES}
					  <tr>
						<td class="listTableRow small" valign=top>{$smarty.foreach.otlist.iteration}</td>
						<td class="listTableRow small" valign=top nowrap align="center">
							  	<a href="#" onClick="javascript:window.open('modules/Settings/edit_config_targeted.php?action_type=list_config_targeted&action=edit&id={$otvalues.id}' , '','menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=0,width=500,height=500,top=150,left=400 ' )";><img src="{'editfield.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_EDIT}" title="{$APP.LBL_EDIT}" border="0" align="absmiddle"></a> | <a href="#" onClick="javascript:window.open('modules/Settings/delete_config_targeted.php?action_type=list_config_targeted&action=delete&id={$otvalues.id}' , '','menuber=no,toorlbar=no,location=no,scrollbars=no, status=no,resizable=0,width=400,height=150,top=220,left=400 ' )";><img src="{'delete.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_DELETE_BUTTON}" title="{$APP.LBL_DELETE_BUTTON}" border="0" align="absmiddle"></a>
								<!--&nbsp;|	<a href="#" onClick="deletegroup(this,'{$groupvalues.groupid}')";><img src="{'delete.gif'|@aicrm_imageurl:$THEME}" alt="{$LNK_DELETE}" title="{$APP.LNK_DELETE}" border="0" align="absmiddle"></a>-->
						</td>
						<td class="listTableRow small" valign=top align="center"><font color="#FF0000"><strong>{$otvalues.target_year}</strong></font></td>
                        <td class="listTableRow small" valign=top align="right"><font color="#000"><strong>{$otvalues.call_in}</strong></font></td>
                        <td class="listTableRow small" valign=top align="right"><font color="#000"><strong>{$otvalues.call_out}</strong></font></td>
                        <td class="listTableRow small" valign=top align="right"><font color="#000"><strong>{$otvalues.walk}</strong></font></td>
                        <td class="listTableRow small" valign=top align="right"><font color="#000"><strong>{$otvalues.gross_booking}</strong></font></td>
                        <td class="listTableRow small" valign=top align="right"><font color="#000"><strong>{$otvalues.booking_amount}</strong></font></td>
                        <td class="listTableRow small" valign=top align="right"><font color="#000"><strong>{$otvalues.net_booking}</strong></font></td>
                    	<td class="listTableRow small" valign=top align="right"><font color="#000"><strong>{$otvalues.net_booking_amount}</strong></font></td>
<!--                        {if $otvalues.sms_status eq 'Active'}
                        <td class="listTableRow small active" align="center">{$otvalues.sms_status}</td>
                        {else}
                        <td class="listTableRow small inactive" align="center">{$otvalues.sms_status}</td>
                        {/if}-->
					  </tr>
					{/foreach}
					</table>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr><td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td></tr>
					</table>
					
					
				</td>
				</tr>
				</table>
			
			
			
			</td>
			</tr>
			</table>
		</td>
	</tr>
	</form>
	</table>
		
	</div>
</td>
        <td valign="top"><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
   </tr>
</tbody>
</table>

<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<script>
function deletegroup(obj,groupid)
{ldelim}
	$("status").style.display="inline";
        new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody:'module=Users&action=UsersAjax&file=GroupDeleteStep1&groupid='+groupid,
                        onComplete: function(response) {ldelim}
                                $("status").style.display="none";
                                $("tempdiv").innerHTML=response.responseText;
								fnvshobj(obj,"tempdiv");
                        {rdelim}
                {rdelim}
        );
{rdelim}

</script>
