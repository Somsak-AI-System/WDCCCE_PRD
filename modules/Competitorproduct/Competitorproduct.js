/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
document.write("<script type='text/javascript' src='modules/Competitorproduct/multifile.js'></"+"script>");

function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
                window.opener.document.QcEditView.parent_name.value = product_name;
                window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
                window.opener.document.EditView.parent_name.value = product_name;
                window.opener.document.EditView.parent_id.value = product_id;
	}
}

function set_return_specific(product_id, product_name) {
        
        var fldName = getOpenerObj("quote_name");
        var fldId = getOpenerObj("quote_id");
        fldName.value = product_name;
        fldId.value = product_id;
        window.opener.document.EditView.action.value = 'EditView';
        window.opener.document.EditView.convertmode.value = 'update_quote_val';
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

function set_return_inventory(competitorproductid,competitorproduct_name,curr_row,competitor_product_brand,competitor_product_group,competitor_product_size,competitor_product_thickness,selling_price){

    window.opener.document.EditView.elements["CompetitorproductName"+curr_row].value = competitorproduct_name;
    window.opener.document.EditView.elements["hdnCompetitorProductId"+curr_row].value = competitorproductid;

    window.opener.document.EditView.elements["competitor_brand"+curr_row].value = competitor_product_brand;
    window.opener.document.EditView.elements["comprtitor_product_group"+curr_row].value = competitor_product_group;
    window.opener.document.EditView.elements["comprtitor_product_size"+curr_row].value = competitor_product_size;
    window.opener.document.EditView.elements["comprtitor_product_thickness"+curr_row].value = competitor_product_thickness;
    window.opener.document.EditView.elements["competitor_price"+curr_row].value = selling_price;

}

