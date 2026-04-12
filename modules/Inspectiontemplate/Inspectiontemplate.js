/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

//document.write("<script type='text/javascript' src='include/js/Inventory_Inspectiontemplate.js'></"+"script>");



function set_return_specific(inspectiontemplateid,inspectiontemplate_no,inspectiontemplate_name,formName,return_module){

	if(formName == null || formName == ''){
		formName = 'EditView';
	}else{
		if(window.opener.document.forms[formName] == null) formName = 'EditView';
	}
	
	var form = window.opener.document.forms[formName];

	if(typeof(form.inspectiontemplate_name) != 'undefined')
		form.inspectiontemplate_name.value = inspectiontemplate_name;
	if(typeof(form.inspectiontemplateid) != 'undefined')
		form.inspectiontemplateid.value = inspectiontemplateid;
	if(typeof(form.inspectiontemplate_no) != 'undefined')
		form.inspectiontemplate_no.value = inspectiontemplate_no;
		
}