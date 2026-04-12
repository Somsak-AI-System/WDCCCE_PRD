/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
document.write("<script type='text/javascript' src='modules/Premuimproduct/multifile.js'></"+"script>");

function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
                window.opener.document.QcEditView.parent_name.value = product_name;
                window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
                window.opener.document.EditView.parent_name.value = product_name;
                window.opener.document.EditView.parent_id.value = product_id;
	}
}

function set_return_specific(premuimproductid, premuimproduct_no) { 
        formName = 'EditView';
        var form = window.opener.document.forms[formName];

        if(document.getElementById('from_link').value != '')
        {
        var fldName = window.opener.document.QcEditView.premuimproduct_no;
        var fldId = window.opener.document.QcEditView.premuimproductid;
        }else
        {
        var fldName = window.opener.document.EditView.premuimproduct_no;
        var fldId = window.opener.document.EditView.premuimproductid;
        }
        
        if(typeof(form.premuimproductid) != 'undefined'){
                fldId.value = premuimproductid;
        }
        if(typeof(form.premuimproduct_no) != 'undefined'){
                fldName.value = premuimproduct_no;
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

