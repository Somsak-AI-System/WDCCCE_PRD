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

function set_return_specific_parent(toolsid, tools_name,formName,return_module,brand,model,serial_name) {
    //getOpenerObj used for DetailView
// alert(serial_name);

    if(document.getElementById('from_link').value != '' && typeof(window.opener.document.QcEditView)!= 'undefined' )
    {
        var fldName = window.opener.document.QcEditView.tools_name;
        var fldId = window.opener.document.QcEditView.tools_id;

    }else if(typeof(window.opener.document.DetailView) != 'undefined')
    {
        var fldName = window.opener.document.DetailView.tools_name;
        var fldId = window.opener.document.DetailView.tools_id;

    }else
    {
        var fldName = window.opener.document.EditView.tools_name;
        var fldId = window.opener.document.EditView.tools_id;

    }
    fldName.value = tools_name;
    fldId.value = toolsid;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(typeof(form.toolslist_brand) != 'undefined'){
        form.toolslist_brand.value = brand;
    }
    if(typeof(form.toolslist_model) != 'undefined'){
        form.toolslist_model.value = model;
    }
    if(typeof(form.serial_name) != 'undefined'){
        form.serial_name.value = serial_name;
    }
    // if(typeof(form.pricesqm) != 'undefined'){
    //     form.pricesqm.value = pricesqm;
    //     window.opener.jQuery('input[name="pricesqm"]').trigger('change');
    // }
    //
    //
    // if(productcode=="undefined"){
    //     productcode = "";
    // }
    //
    // if(typeof(form.productcode) != 'undefined'){
    //     form.productcode.value = productcode;
    // }


}

function set_return_specific(toolsid, tools_no) {
        
        var fldName = getOpenerObj("tools_no");
        var fldId = getOpenerObj("toolsid");
        fldName.value = tools_no;
        fldId.value = toolsid;
        window.opener.document.EditView.action.value = 'EditView';
        window.opener.document.EditView.convertmode.value = 'update_tools_val';
	window.opener.document.EditView.submit();
	
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

