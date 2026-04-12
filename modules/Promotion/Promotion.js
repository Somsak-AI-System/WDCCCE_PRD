/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
document.write("<script type='text/javascript' src='modules/Promotion/multifile.js'></"+"script>");

document.write("<script type='text/javascript' src='plugin/Promotion/js/my_function_promotion.js'></"+"script>");

function set_return_inventory(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id) {
	if (!isNaN(curr_row)){
		
	}else{
	
	}
	var subprod = subprod_id.split("::");
	window.opener.document.EditView.elements["subproduct_ids"+curr_row].value = subprod[0];
	window.opener.document.getElementById("subprod_names"+curr_row).innerHTML = subprod[1];
	window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
	window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
	window.opener.document.EditView.elements["comment"+curr_row].value = desc;
	window.opener.document.EditView.elements["productName"+curr_row].focus()
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

function set_return_specific(promotionid,promotion_no,from,promotion_name) {
	
    if(document.getElementById('from_link').value != '' && typeof(window.opener.document.QcEditView)!= 'undefined' )
    {
        var fldName = window.opener.document.QcEditView.promotion_name;
        var fldId = window.opener.document.QcEditView.promotionid;
    }else if(typeof(window.opener.document.DetailView) != 'undefined')
    {
        var fldName = window.opener.document.DetailView.promotion_name;
        var fldId = window.opener.document.DetailView.promotionid;
    }else
    {
        var fldName = window.opener.document.EditView.promotion_name;
        var fldId = window.opener.document.EditView.promotionid;
    }

    fldName.value = promotion_name;
    fldId.value = promotionid;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    /*if(typeof(form.promotion_name) != 'undefined'){
        form.promotion_name.value = promotion_name;
    }*/
 
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

//function set_return_inventory_mySetup_Tab(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id,uom) {
function set_return_inventory_mySetup_Tab(curr_row,promotionid,promotion_name,set_tab,startdate,enddate,promotion_status,budget_cost,actual_cost,expected_audience,actual_audience,expected_revenue,actual_revenue) {
    
    //var subprod = subprod_id.split("::");
    window.opener.document.EditView.elements["mySetup_Tab1_2_promotionname"+curr_row].value = promotion_name;
    window.opener.document.EditView.elements["mySetup_Tab1_2_hdnpromotionid"+curr_row].value = promotionid;
    var promotiontype = '';
    if(set_tab ==1){
        promotiontype = 'Product Price';
    }else if(set_tab ==2){
        promotiontype = 'Product';
    }else if(set_tab ==3){
        promotiontype = 'Discount Product';
    }
    window.opener.document.getElementById("promotiontype"+curr_row).innerHTML = promotiontype;
    window.opener.document.getElementById("startdate"+curr_row).innerHTML = startdate;
    window.opener.document.getElementById("enddate"+curr_row).innerHTML = enddate;
    window.opener.document.getElementById("status"+curr_row).innerHTML = promotion_status;
    if(!isNaN(parseFloat(budget_cost))) budget_cost = roundPriceValue(budget_cost);
    window.opener.document.getElementById("budgetcost"+curr_row).innerHTML = numberWithCommas(budget_cost);
    if(!isNaN(parseFloat(actual_cost))) actual_cost = roundPriceValue(actual_cost);
    window.opener.document.getElementById("actualcost"+curr_row).innerHTML = numberWithCommas(actual_cost);
    if(!isNaN(parseFloat(expected_audience))) expected_audience = roundPriceValue(expected_audience);
    window.opener.document.getElementById("expectedaudience"+curr_row).innerHTML = numberWithCommas(expected_audience);
    if(!isNaN(parseFloat(actual_audience))) actual_audience = roundPriceValue(actual_audience);
    window.opener.document.getElementById("actualaudience"+curr_row).innerHTML = numberWithCommas(actual_audience);
    if(!isNaN(parseFloat(expected_revenue))) expected_revenue = roundPriceValue(expected_revenue);
    window.opener.document.getElementById("expectedrevenue"+curr_row).innerHTML = numberWithCommas(expected_revenue);
    if(!isNaN(parseFloat(actual_revenue))) actual_revenue = roundPriceValue(actual_revenue);
    window.opener.document.getElementById("actualrevenue"+curr_row).innerHTML = numberWithCommas(actual_revenue);

    set_total();
}
function set_total(){
    var max_row_count = window.opener.document.getElementById('mySetup_Tab1_2').rows.length;
    max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length
    var v_budgetcost = 0;
    var v_actualcost = 0;
    var v_expectedaudience = 0 ;
    var v_actualaudience = 0 ;
    var v_expectedrevenue = 0 ;
    var v_actualrevenue = 0 ;
    for(var i=1;i<=max_row_count;i++)
    {
        rowId = i;
        if(window.opener.document.getElementById("mySetup_Tab1_2_row"+rowId) && window.opener.document.getElementById("mySetup_Tab1_2_row"+rowId).style.display != 'none')
        {
            budgetcost = window.opener.document.getElementById('budgetcost'+rowId).innerHTML;
            if(budgetcost !== ''){
                var new_budgetcost = budgetcost.replace(/,/g, '');
                v_budgetcost += parseFloat(new_budgetcost);
            }
            actualcost = window.opener.document.getElementById('actualcost'+rowId).innerHTML;
            if(actualcost !== ''){
                var new_actualcost = actualcost.replace(/,/g, '');
                v_actualcost += parseFloat(new_actualcost);
            }
            expectedaudience = window.opener.document.getElementById('expectedaudience'+rowId).innerHTML;
            if(expectedaudience !== ''){
                var new_expectedaudience = expectedaudience.replace(/,/g, '');
                v_expectedaudience += parseFloat(new_expectedaudience);
            }
            actualaudience = window.opener.document.getElementById('actualaudience'+rowId).innerHTML;
            if(actualaudience !== ''){
                var new_actualaudience = actualaudience.replace(/,/g, '');
                v_actualaudience += parseFloat(new_actualaudience);
            }
            expectedrevenue = window.opener.document.getElementById('expectedrevenue'+rowId).innerHTML;
            if(expectedrevenue !== ''){
                var new_expectedrevenue = expectedrevenue.replace(/,/g, '');
                v_expectedrevenue += parseFloat(new_expectedrevenue);
            }
            actualrevenue = window.opener.document.getElementById('actualrevenue'+rowId).innerHTML;
            if(actualrevenue !== ''){
                var new_actualrevenue = actualrevenue.replace(/,/g, '');
                v_actualrevenue += parseFloat(new_actualrevenue);
            }
        }         
    }
    window.opener.document.getElementById("totalbudgetcost").innerHTML = numberWithCommas(roundPriceValue(v_budgetcost));
    window.opener.document.getElementById("totalactualcost").innerHTML = numberWithCommas(roundPriceValue(v_actualcost));
    window.opener.document.getElementById("totalexpectedaudience").innerHTML = numberWithCommas(roundPriceValue(v_expectedaudience));
    window.opener.document.getElementById("totalactualaudience").innerHTML = numberWithCommas(roundPriceValue(v_actualaudience));
    window.opener.document.getElementById("totalexpectedrevenue").innerHTML = numberWithCommas(roundPriceValue(v_expectedrevenue));
    window.opener.document.getElementById("totalactualrevenue").innerHTML = numberWithCommas(roundPriceValue(v_actualrevenue));
}

function roundPriceValue(val) {
    val = parseFloat(val);
    val = Math.round(val*100)/100;
    val = val.toString();

    if (val.indexOf(".")<0) {
        val+=".00"
    } else {
        var dec=val.substring(val.indexOf(".")+1,val.length)
        if (dec.length>2)
            val=val.substring(0,val.indexOf("."))+"."+dec.substring(0,2)
        else if (dec.length==1)
            val=val+"0"
    }

    return val;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}