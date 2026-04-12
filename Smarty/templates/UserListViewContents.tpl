{*<!--

/*********************************************************************************
** The contents of this file are subject to the aicrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  aicrm CRM Open Source
 * The Initial Developer of the Original Code is aicrm.
 * Portions created by aicrm are Copyright (C) aicrm.
 * All Rights Reserved.
*
 ********************************************************************************/

-->*}

<!--<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
<tr>
	<td class="big"><strong>{$MOD.LBL_USERS_LIST}</strong></td>
	<td class="small" align=right>&nbsp;</td>
</tr>
</table>
					
<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTableTopButtons">
{if $ERROR_MSG neq ''}
<tr>
	{$ERROR_MSG}
</tr>
{/if}
</table>
						
<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTable">
<tr>
    <td nowrap align="left" colspan="8">
        <table border=0 cellspacing=0 cellpadding=0 class="small" width=100%>
            <tr>
            	<td>{$recordListRange}&nbsp;&nbsp;
                </td>
                <td>{$NAVIGATION}
                </td>
            </tr>
        </table>
    </td>
    <td colspan="1" align="right">
    <input title="{$CMOD.LBL_NEW_USER_BUTTON_TITLE}" accessyKey="{$CMOD.LBL_NEW_USER_BUTTON_KEY}" type="submit" name="button" value="{$CMOD.LBL_NEW_USER_BUTTON_LABEL}" class="crmButton create small">
    </td>
</tr>
<tr>
	<td class="colHeader small" valign=top>#</td>
	<td class="colHeader small" valign=top>{$APP.Tools}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.3}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.5}</td>
    <td class="colHeader small" valign=top>{$LIST_HEADER.6}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.7}</td>
	
    <td class="colHeader small" valign=top>{$LIST_HEADER.8}</td>
    <td class="colHeader small" valign=top>{$LIST_HEADER.9}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.4}</td>
</tr>
        {php}
        	global $list_max_entries_per_page;
        	$i=0;
        {/php}
	{foreach name=userlist item=listvalues key=userid from=$LIST_ENTRIES}
		{assign var=flag value=0}
        {php}
        	$i=$i+1;
            //echo $_REQUEST["start"];
            if($_REQUEST['start']<2){
            	$page_nn=0;
            }else{
           	 $page_nn=($_REQUEST["start"]-1)*$list_max_entries_per_page;
            }
        {/php}
      
<tr>
	<td class="listTableRow small" valign=top>{php}echo ($i+$page_nn);{/php}</td>
	<td class="listTableRow small" nowrap valign=top><a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record={$userid}"><img src="{'editfield.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_EDIT_BUTTON}" title="{$APP.LBL_EDIT_BUTTON}" border="0"></a>
	{foreach item=name key=id from=$USERNODELETE}
		{if $userid eq $id || $userid eq $CURRENT_USERID}
			{assign var=flag value=1}
		{/if}
	{/foreach}
	{if $flag eq 0}
		<img src="{'delete.gif'|@aicrm_imageurl:$THEME}" onclick="deleteUser(this,'{$userid}')" border="0"  alt="{$APP.LBL_DELETE_BUTTON}" title="{$APP.LBL_DELETE_BUTTON}" style="cursor:pointer;"/>
	{/if}
	<a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record={$userid}&isDuplicate=true"><img src="{'settingsActBtnDuplicate.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_DUPLICATE_BUTTON}" title="{$APP.LBL_DUPLICATE_BUTTON}" border="0"></a>
</td>
	<td class="listTableRow small" valign=top><b><a href="index.php?module=Users&action=DetailView&parenttab=Settings&record={$userid}"> {$listvalues.3} </a></b><br><a href="index.php?module=Users&action=DetailView&parenttab=Settings&record={$userid}"> {$listvalues.1} {$listvalues.0}</a> ({$listvalues.2})</td>
	<td class="listTableRow small" valign=top>{$listvalues.5}&nbsp;</td>
    <td class="listTableRow small" valign=top>{$listvalues.6}&nbsp;</td>
	<td class="listTableRow small" valign=top>{$listvalues.7}&nbsp;</td>
	
    <td class="listTableRow small" valign=top>{$listvalues.8}&nbsp;</td>
    <td class="listTableRow small" valign=top>{$listvalues.9}&nbsp;</td>
	{if $listvalues.4|@strip_tags|@trim eq 'Active'}
	<td class="listTableRow small active" valign=top>{$APP.Active}</td>
	{else}
	<td class="listTableRow small inactive" valign=top>{$APP.Inactive}</td>
	{/if}	

</tr>
	{/foreach}
<tr>
    <td nowrap align="left" colspan="8">
        <table border=0 cellspacing=0 cellpadding=0 class="small" width=100%>
            <tr>
            	<td>{$recordListRange}&nbsp;&nbsp;
                </td>
                <td>{$NAVIGATION}
                </td>
            </tr>
        </table>
    </td>
    <td colspan="1" align="right">
    <input title="{$CMOD.LBL_NEW_USER_BUTTON_TITLE}" accessyKey="{$CMOD.LBL_NEW_USER_BUTTON_KEY}" type="submit" name="button" value="{$CMOD.LBL_NEW_USER_BUTTON_LABEL}" class="crmButton create small">
    </td>
</tr> 
</table>
<table border=0 cellspacing=0 cellpadding=5 width=100% >
	<tr><td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td></tr>
</table>-->

<table id="dg" title="" style="width:100%;height:613px" data-options="singleSelect:true"  toolbar="#toolbar"></table>
<div id="toolbar" style="text-align:right"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a></div>
<table border=0 cellspacing=0 cellpadding=5 width=100% >
	<tr><td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td></tr>
</table>