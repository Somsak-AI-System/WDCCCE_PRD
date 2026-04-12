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
<!-- Added this file to display the Inventory Actions based on the Inventory Modules -->
<table width="100%" border="0" cellpadding="5" cellspacing="0">
   <tr>
	<td>&nbsp;</td>
   </tr>

   <!-- This if condition is added to avoid display Tools heading because now there is no options in Tools. -->
   <tr>
	<td align="left" class="genHeaderSmall">{$APP.LBL_REPORT}</td>
   </tr>
   
	<!-- Module based actions starts -->
	<tr>
     	<td align="left" style="padding-left:10px;">
        	<!-- <a class="webMnu" href="{$Report_URL}report_order.rptdesign&crmid={$ID}&__format=pdf" target="_blank"><img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
        	<a class="webMnu" href="{$Report_URL}report_order.rptdesign&crmid={$ID}&__format=pdf" target="_blank">Tax Invoice/Receipt Report</a> -->
          <a class="webMnu" href="{$Report_URL}report_taxinvoice_receipt.rptdesign&crmid={$ID}&__format=pdf" target="_blank"><img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
          <a class="webMnu" href="{$Report_URL}report_taxinvoice_receipt.rptdesign&crmid={$ID}&__format=pdf" target="_blank">Tax Invoice/Receipt Report</a>
    	</td>
    	
	</tr>

	<tr>
     	<td align="left" style="padding-left:10px;">
        	<a class="webMnu" href="{$Report_URL}report_purchase_order.rptdesign&crmid={$ID}&__format=pdf" target="_blank"><img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
        	<a class="webMnu" href="{$Report_URL}report_purchase_order.rptdesign&crmid={$ID}&__format=pdf" target="_blank">Purchase Order Report</a>
    	</td>
	</tr>
	
	<tr>
     	<td align="left" style="padding-left:10px;">
        	<!-- <a class="webMnu" href="{$Report_URL}report_taxinvoice_receipt.rptdesign&crmid={$ID}&__format=pdf" target="_blank"><img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
        	<a class="webMnu" href="{$Report_URL}report_taxinvoice_receipt.rptdesign&crmid={$ID}&__format=pdf" target="_blank">Order Report</a> -->
          <a class="webMnu" href="{$Report_URL}report_order.rptdesign&crmid={$ID}&__format=pdf" target="_blank"><img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
          <a class="webMnu" href="{$Report_URL}report_order.rptdesign&crmid={$ID}&__format=pdf" target="_blank">Order Report</a>
    	</td>
	</tr>

</table>



