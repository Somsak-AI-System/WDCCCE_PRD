/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
  function set_return_inventory_service(product_id,product_code,product_name,sellingprice,module,curr_row){
    var productnameid = "productName"+curr_row;
    window.opener.document.getElementById("productCodeView"+curr_row).innerHTML = product_code;
    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["listPrice"+curr_row].value = sellingprice;
    
    // window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    //window.opener.document.EditView.elements["uom"+curr_row].value = unit;
    

    // window.opener.document.EditView.elements["mat_gp1_desciption"+curr_row].value = htmlDecode(mat_gp1_desciption);
    // console.log(htmlDecode(mat_gp3_desciption));
    // window.opener.document.EditView.elements["mat_gp3_desciption"+curr_row].value = htmlDecode(mat_gp3_desciption);

    // window.opener.document.EditView.elements["mat_gp4_desciption"+curr_row].value = htmlDecode(mat_gp4_desciption);
    // window.opener.document.EditView.elements["piece_per_carton"+curr_row].value = um_coversion_m2_pcs;
    // window.opener.document.EditView.elements["squaremeters_per_carton"+curr_row].value = squaremeters_per_carton;
    // window.opener.document.EditView.elements["price_per_piece"+curr_row].value = price_per_piece;
    // window.opener.document.EditView.elements["price_per_squaremeter"+curr_row].value = price_per_squaremeter;

    window.opener.document.EditView.elements["lineItemType"+curr_row].value = module;

    window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;
    window.opener.calcTotal();
    window.opener.set_tax_manual();
}

  function set_return_inventory_service_pricelist(serviceid,service_no,service_name,unit_price,curr_row){
    var productnameid = "productName"+curr_row;
    //window.opener.document.getElementById("productCodeView"+curr_row).innerHTML = service_no;
    window.opener.document.EditView.elements["productName"+curr_row].value = service_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = serviceid;
    window.opener.document.EditView.elements["listPrice"+curr_row].value = unit_price;
    window.opener.document.EditView.elements["lineItemType"+curr_row].value = 'Service';

    // window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    //window.opener.document.EditView.elements["uom"+curr_row].value = unit;

    // window.opener.document.EditView.elements["mat_gp1_desciption"+curr_row].value = htmlDecode(mat_gp1_desciption);
    // console.log(htmlDecode(mat_gp3_desciption));
    // window.opener.document.EditView.elements["mat_gp3_desciption"+curr_row].value = htmlDecode(mat_gp3_desciption);

    // window.opener.document.EditView.elements["mat_gp4_desciption"+curr_row].value = htmlDecode(mat_gp4_desciption);
    // window.opener.document.EditView.elements["piece_per_carton"+curr_row].value = um_coversion_m2_pcs;
    // window.opener.document.EditView.elements["squaremeters_per_carton"+curr_row].value = squaremeters_per_carton;
    // window.opener.document.EditView.elements["price_per_piece"+curr_row].value = price_per_piece;
    // window.opener.document.EditView.elements["price_per_squaremeter"+curr_row].value = price_per_squaremeter;

    window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;
    window.opener.calcTotal();
    window.opener.set_tax_manual();
}

function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.parent_name.value = product_name;
        window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
	}
}

function set_return_specific(serviceid,service_no,form,service_name) {
        
        var fldName = getOpenerObj("service_no");
        var fldId = getOpenerObj("serviceid");
        fldName.value = service_no;
        fldId.value = serviceid;


        formName = 'EditView';
        var form = window.opener.document.forms[formName];

        if(typeof(form.cf_4623) != 'undefined'){
                form.cf_4623.value = service_name;
        }
        if(typeof(form.servicelist_name) != 'undefined'){
                form.servicelist_name.value = service_name;
        }
        
        window.opener.document.EditView.action.value = 'EditView';
        window.opener.document.EditView.convertmode.value = 'update_service_val';
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

