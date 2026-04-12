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

function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.parent_name.value = product_name;
        window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
	}
}

function set_return_specific(seriallistid, seriallist_no ,from ,seriallist_name ,location,productid,productname,products_businessplusno ) {
        
    if(document.getElementById('from_link').value != '' && typeof(window.opener.document.QcEditView)!= 'undefined' )
    {
        var fldName = window.opener.document.QcEditView.seriallist_name;
        var fldId = window.opener.document.QcEditView.seriallistid;
    }else if(typeof(window.opener.document.DetailView) != 'undefined')
    {
        var fldName = window.opener.document.DetailView.seriallist_name;
        var fldId = window.opener.document.DetailView.seriallistid;
    }else
    {
        var fldName = window.opener.document.EditView.seriallist_name;
        var fldId = window.opener.document.EditView.seriallistid;
    }

    fldName.value = seriallist_name;
    fldId.value = seriallistid;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(typeof(form.seriallist_location) != 'undefined'){
        form.seriallist_location.value = location;
    }

    if(typeof(form.product_id) != 'undefined'){
        form.product_id.value = productid;
    }
    
    if(typeof(form.product_name) != 'undefined'){
        form.product_name.value = productname;
    }
    
    if(typeof(form.productcode) != 'undefined'){
        form.productcode.value = products_businessplusno;
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

