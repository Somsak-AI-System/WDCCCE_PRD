/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
//alert("555");
document.write("<script type='text/javascript' src='include/js/Inventory_pro.js'></"+"script>");


function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.parent_name.value = product_name;
        window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
	}
}

function set_return_specific(inspectionid, inspection_name, formName, inspection_no, buildingid, projectid, building_name, project_name) {
    if (formName == null || formName == '') {
        formName = 'EditView';
    } else {
        // In case formName is specified but does not exists then revert to EditView form
        if (window.opener.document.forms[formName] == null) formName = 'EditView';
    }

    var form = window.opener.document.forms[formName];
    form.inspection_no.value = inspection_name;
    form.inspectionid.value = inspectionid;

    if (typeof(form.building_no) != 'undefined')
        form.building_no.value = building_name;
    if (typeof(form.buildingid) != 'undefined')
        form.buildingid.value = buildingid;
    if (typeof(form.project_no) != 'undefined')
        form.project_no.value = project_name;
    if (typeof(form.projectid) != 'undefined')
        form.projectid.value = projectid;

}
function add_data_to_relatedlist(entity_id,recordid) {

        opener.document.location.href="index.php?module=Emails&action=updateRelations&destination_module=Accounts&entityid="+entity_id+"&parentid="+recordid;
}
function set_return_formname_specific(formname,product_id, product_name) {
        window.opener.document.EditView1.quote_name.value = product_name;
        window.opener.document.EditView1.quote_id.value = product_id;
}
function set_return_todo(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.task_parent_name.value = product_name;
        window.opener.document.QcEditView.task_parent_id.value = product_id;
	} else {
        window.opener.document.createTodo.task_parent_name.value = product_name;
        window.opener.document.createTodo.task_parent_id.value = product_id;
	}
}

