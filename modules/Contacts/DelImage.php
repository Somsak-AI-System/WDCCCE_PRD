<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

function DelImage($id)
{		
	global $adb;
	$query= "select aicrm_seattachmentsrel.attachmentsid from aicrm_seattachmentsrel inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid where aicrm_crmentity.setype='Contacts Image' and aicrm_seattachmentsrel.crmid=?";
	$result = $adb->pquery($query, array($id));
	$attachmentsid = $adb->query_result($result,$i,"attachmentsid");
	
	$rel_delquery='delete from aicrm_seattachmentsrel where crmid=?  and attachmentsid=?';
	$adb->pquery($rel_delquery, array($id, $attachmentsid));
	
	$crm_delquery="delete from aicrm_crmentity where crmid=?";
	$adb->pquery($crm_delquery, array($attachmentsid));

	$base_query="update aicrm_contactdetails set imagename='' where contactid=?";
	$adb->pquery($base_query, array($id));
}

function DelAttachment($id)
{
	global $adb;
	$selresult = $adb->pquery("select name,path from aicrm_attachments where attachmentsid=?", array($id));
	unlink($adb->query_result($selresult,0,'path').$id."_".$adb->query_result($selresult,0,'name'));
	$query="delete from aicrm_seattachmentsrel where attachmentsid=?";
	$adb->pquery($query, array($id));
	$query="delete from aicrm_attachments where attachmentsid=?";
	$adb->pquery($query, array($id));

}
$id = $_REQUEST["recordid"];
if(isset($_REQUEST["attachmodule"]) && $_REQUEST["attachmodule"]=='Emails')
{
	DelAttachment($id);
}
else
{
	DelImage($id);
}
echo 'SUCCESS';
?>
