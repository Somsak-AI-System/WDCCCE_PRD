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
    if (document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.parent_name.value = product_name;
        window.opener.document.QcEditView.parent_id.value = product_id;
    } else {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
    }
}

function set_return_specific(serialid, serial_no, formName, serial_name, product_id, productname, return_module) {
    
    var fldName = getOpenerObj("serial_name");
    var fldId = getOpenerObj("serialid");

    fldName.value = serial_name;
    fldId.value = serialid;

    /*if (return_module == 'Tools') {
        formName = 'EditView';
        var form = window.opener.document.forms[formName];
        var fldbrand = getOpenerObj("brand_tools");
        var fldmodel = getOpenerObj("tools_model");
        fldbrand.value = brand;
        fldmodel.value = model;
    }*/

    /*if (return_module == 'Sparepart') {
       
        formName = 'EditView';
        var form = window.opener.document.forms[formName];

        if(typeof(form.sparepart_brand) != 'undefined'){
            form.sparepart_brand.value = brand;
        }
        if(typeof(form.model) != 'undefined'){
            form.model.value = model;
        }
    }*/

    /*if (return_module == 'Caselist') {
        formName = 'EditView';
        var form = window.opener.document.forms[formName];

        var fldbrand = getOpenerObj("caselist_series");
        var fldmodel = getOpenerObj("caselist_model");
        var fldproduct_id = getOpenerObj("product_id");
        var fldproductname = getOpenerObj("product_name");
        fldbrand.value = brand;
        fldmodel.value = model;
        fldproduct_id.value = product_id;
        fldproductname.value = productname;

        if(typeof(form.caselist_name) != 'undefined'){
            form.caselist_name.value = productname+'/'+serial_name;
        }
    }*/

    if (return_module == 'HelpDesk') {
        formName = 'EditView';
        var form = window.opener.document.forms[formName];

        if(typeof(form.product_name) != 'undefined'){
            form.product_name.value = productname;
        }
        if(typeof(form.product_id) != 'undefined'){
            form.product_id.value = product_id;
        }
    }
    /*window.opener.document.EditView.action.value = 'EditView';
    window.opener.document.EditView.convertmode.value = 'update_serial_val';
    window.opener.document.EditView.submit();*/


}
function add_data_to_relatedlist(entity_id, recordid) {

    opener.document.location.href = "index.php?module=Emails&action=updateRelations&destination_module=Accounts&entityid=" + entity_id + "&parentid=" + recordid;
}
function set_return_formname_specific(formname, product_id, product_name) {
    window.opener.document.EditView1.quote_name.value = product_name;
    window.opener.document.EditView1.quote_id.value = product_id;
}
function set_return_todo(product_id, product_name) {
    if (document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.task_parent_name.value = product_name;
        window.opener.document.QcEditView.task_parent_id.value = product_id;
    } else {
        window.opener.document.createTodo.task_parent_name.value = product_name;
        window.opener.document.createTodo.task_parent_id.value = product_id;
    }
}

