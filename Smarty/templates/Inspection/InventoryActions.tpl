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

<!-- Avoid this actions display for PriceBook module-->
{if $MODULE neq 'PriceBooks'}


<!-- Added this file to display the Inventory Actions based on the Inventory Modules -->


{literal}
<script type='text/javascript'>
function sendpdf_submit()
{
	// Submit the form to get the attachment ready for submission
	document.DetailView.submit();
{/literal}

	{if $MODULE eq 'Invoice'}
		OpenCompose('{$ID}','Invoice');
	{elseif $MODULE eq 'Quotes'}
		OpenCompose('{$ID}','Quote');
	{elseif $MODULE eq 'PurchaseOrder'}
		OpenCompose('{$ID}','PurchaseOrder');
	{elseif $MODULE eq 'SalesOrder'}
		OpenCompose('{$ID}','SalesOrder');
	{/if}
{literal}
}
</script>
{/literal}

{/if}
