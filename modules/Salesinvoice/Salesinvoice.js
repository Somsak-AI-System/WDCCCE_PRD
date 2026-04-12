/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.parent_name.value = product_name;
        window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
	}
}

/*function set_return_specific(salesinvoiceid, salesinvoice_no) {
        
        var fldName = getOpenerObj("salesinvoice_no");
        var fldId = getOpenerObj("salesinvoiceid");
        fldName.value = salesinvoice_no;
        fldId.value = salesinvoiceid;
        window.opener.document.EditView.action.value = 'EditView';
        window.opener.document.EditView.convertmode.value = 'update_salesinvoice_val';
	window.opener.document.EditView.submit();
	
}*/
function set_return_specific(salesinvoiceid,salesinvoice_no,formName,salesinvoice_name,description ) {

    if(document.getElementById('from_link').value != '' && typeof(window.opener.document.QcEditView)!= 'undefined' )
    {
        var fldName = window.opener.document.QcEditView.salesinvoice_no;
        var fldId = window.opener.document.QcEditView.salesinvoiceid;
    }else if(typeof(window.opener.document.DetailView) != 'undefined')
    {
        var fldName = window.opener.document.DetailView.salesinvoice_no;
        var fldId = window.opener.document.DetailView.salesinvoiceid;
    }else
    {
        var fldName = window.opener.document.EditView.salesinvoice_no;
        var fldId = window.opener.document.EditView.salesinvoiceid;
    }

    fldName.value = salesinvoice_no;
    fldId.value = salesinvoiceid;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(typeof(form.error_name) != 'undefined'){
        form.error_name.value = salesinvoice_name;
    }
    if(typeof(form.description) != 'undefined'){
        form.description.value = description;
    }

    if(typeof(form.salesinvoicelist_name) != 'undefined'){
        form.salesinvoicelist_name.value = salesinvoice_name;
    }

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

