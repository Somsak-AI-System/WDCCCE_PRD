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

{assign var="fromlink" value=""}

<!-- Added this file to display the fields in Create Entity page based on ui types  -->
{foreach key=label item=subdata from=$data}
	{if $MODULE eq 'HelpDesk'}
		{if $header eq 'รายละเอียดเรื่องร้องขอ' }
			<tr class='case-tr-request' style="height:25px">
		{elseif $header eq 'รายละเอียดเรื่องแจ้งซ่อม' || $header eq 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'}
			<tr class='case-tr-service' style="height:25px">
		{elseif $header eq 'รายละเอียดเรื่องร้องเรียน'}
			<tr class='case-tr-complain' style="height:25px">			
		{else}
			<tr style="height:25px">
		{/if}		
	{else}
		{if $header eq 'Product Details'}
			<tr>
		{else}
			<tr style="height:25px">
		{/if}
	{/if}

	{foreach key=mainlabel item=maindata from=$subdata}
		{include file='EditViewUI.tpl'}
	{/foreach}
   
   </tr>
{/foreach}
