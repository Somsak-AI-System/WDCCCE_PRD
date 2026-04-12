/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

document.write("<script type='text/javascript' src='modules/Products/multifile.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Merge.js'></"+"script>");
document.write("<script type='text/javascript' >jQuery.noConflict();</"+"script>");
document.write("<script type='text/javascript' src='include/js/Inventory_Products.js'></"+"script>");
//document.write("<script type='text/javascript' src='include/js/Inventory_Quotation.js'></"+"script>");

function updateListPrice(unitprice,fieldname, oSelect)
{
    if(oSelect.checked == true)
    {
        document.getElementById(fieldname).style.visibility = 'visible';
        document.getElementById(fieldname).value = unitprice;
    }else
    {
        document.getElementById(fieldname).style.visibility = 'hidden';
    }
}

function check4null(form)
{
    var isError = false;
    var errorMessage = "";
    if (trim(form.productname.value) =='') {
        isError = true;
        errorMessage += "\n Product Name";
        form.productname.focus();
    }

    if (isError == true) {
        alert(alert_arr.MISSING_REQUIRED_FIELDS + errorMessage);
        return false;
    }
    return true;
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
function set_return_specific(product_id, product_name,formName,productstatus,productcategory,productdescription,unit,sellingprice,size,grade,module_return) {
    //getOpenerObj used for DetailView
    if(document.getElementById('from_link').value != '' && typeof(window.opener.document.QcEditView)!= 'undefined' )
    {
        var fldName = window.opener.document.QcEditView.product_name;
        var fldId = window.opener.document.QcEditView.product_id;

    }else if(typeof(window.opener.document.DetailView) != 'undefined')
    {
        var fldName = window.opener.document.DetailView.product_name;
        var fldId = window.opener.document.DetailView.product_id;

    }else
    {
        var fldName = window.opener.document.EditView.product_name;
        var fldId = window.opener.document.EditView.product_id;
    }
    
    fldName.value = product_name;
    fldId.value = product_id;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];
    
    if(typeof(form.product_status) != 'undefined'){
        if(productstatus == 1){
            form.product_status.value = 'Active';
        }else{
            form.product_status.value = 'Inactive';
        }
        window.opener.jQuery('input[name="product_status"]').trigger('change');
    }
    if(typeof(form.product_category) != 'undefined'){
        form.product_category.value = productcategory;
        window.opener.jQuery('input[name="product_category"]').trigger('change');
    }
    if(typeof(form.unit_of_measure) != 'undefined'){
        form.unit_of_measure.value = unit;
        window.opener.jQuery('input[name="unit_of_measure"]').trigger('change');
    }
    if(typeof(form.product_description) != 'undefined'){
        form.product_description.value = productdescription;
    }
    if(typeof(form.selling_price) != 'undefined'){
        window.opener.jQuery('input[name="selling_price"]').val(sellingprice);
    }

    if(typeof(form.cf_25743) != 'undefined'){
        form.cf_25743.value = size;
    }
    if(typeof(form.cf_25744) != 'undefined'){
        form.cf_25744.value = grade;
    }
    if(module_return == 'HelpDesk'){
        if(typeof(form.size) != 'undefined'){
            form.size.value = size;
        }
        if(typeof(form.grade) != 'undefined'){
            form.grade.value = grade;
        }
    }

    if(module_return == 'HelpDesk'){
        if(typeof(form.serial_name) != 'undefined'){
            form.serial_name.value = '';
        }
        if(typeof(form.serialid) != 'undefined'){
            form.serialid.value = '';
        }
    }
    

}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function set_return_formname_specific(formname,product_id, product_name) {
    window.opener.document.EditView1.product_name.value = product_name;
    window.opener.document.EditView1.product_id.value = product_id;
}
function add_data_to_relatedlist(entity_id,recordid) {
    opener.document.location.href="index.php?module={RETURN_MODULE}&action=updateRelations&smodule={SMODULE}&destination_module=Products&entityid="+entity_id+"&parentid="+recordid;
}

function set_return_inventory_mySetup_Disc(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id,uom) {
    var subprod = subprod_id.split("::");
    //window.opener.document.EditView.elements["mySetup_Disc_subproduct_ids"+curr_row].value = subprod[0];
    //window.opener.document.getElementById("mySetup_Disc_subprod_names"+curr_row).innerHTML = subprod[1];

    window.opener.document.EditView.elements["mySetup_Disc_productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["mySetup_Disc_hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["mySetup_Disc_listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["mySetup_Disc_comment"+curr_row].value = desc;
    //window.opener.document.EditView.elements["mySetup_Disc_pack_size"+curr_row].value = pack_size;
    window.opener.document.EditView.elements["mySetup_Disc_uom"+curr_row].value = uom;//alert(unitprice);
    //getOpenerObj("unitPrice"+curr_row).innerHTML = unitprice;
    //getOpenerObj("qtyInStock"+curr_row).innerHTML = qtyinstock;

    // Apply decimal round-off to value
    if(!isNaN(parseFloat(unitprice))) unitprice = roundPriceValue(unitprice);
    window.opener.document.EditView.elements["mySetup_Disc_listPrice"+curr_row].value = unitprice;

    var tax_array = new Array();
    var tax_details = new Array();
    tax_array = taxstr.split(',');
    for(var i=0;i<tax_array.length;i++)
    {
        tax_details = tax_array[i].split('=');
    }

    window.opener.document.EditView.elements["mySetup_Disc_qty"+curr_row].focus()
}
function set_return_inventory_mySetup_Tab1_2(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id,uom) {
    var subprod = subprod_id.split("::");
    //window.opener.document.EditView.elements["mySetup_Disc_subproduct_ids"+curr_row].value = subprod[0];
    //window.opener.document.getElementById("mySetup_Disc_subprod_names"+curr_row).innerHTML = subprod[1];
    //alert(555);
    window.opener.document.EditView.elements["mySetup_Tab1_2_productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["mySetup_Tab1_2_hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["mySetup_Tab1_2_listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["mySetup_Tab1_2_comment"+curr_row].value = desc;
    //window.opener.document.EditView.elements["mySetup_Tab1_2_pack_size"+curr_row].value = pack_size;
    window.opener.document.EditView.elements["mySetup_Tab1_2_uom"+curr_row].value = uom;

    // Apply decimal round-off to value
    if(!isNaN(parseFloat(unitprice))) unitprice = roundPriceValue(unitprice);
    window.opener.document.EditView.elements["mySetup_Tab1_2_listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["mySetup_Tab1_2_qty"+curr_row].value = qtyinstock;
    window.opener.document.EditView.elements["mySetup_Tab1_2_qty"+curr_row].focus()
}
function set_return_inventory_mySetup_Tab2(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id,uom) {
    var subprod = subprod_id.split("::");
    //window.opener.document.EditView.elements["mySetup_Disc_subproduct_ids"+curr_row].value = subprod[0];
    //window.opener.document.getElementById("mySetup_Disc_subprod_names"+curr_row).innerHTML = subprod[1];
    //alert(555);
    window.opener.document.EditView.elements["mySetup_Tab2_productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["mySetup_Tab2_hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["mySetup_Tab2_listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["mySetup_Tab2_comment"+curr_row].value = desc;
    //window.opener.document.EditView.elements["mySetup_Tab2_pack_size"+curr_row].value = pack_size;
    window.opener.document.EditView.elements["mySetup_Tab2_uom"+curr_row].value = uom;//alert(unitprice);
    //getOpenerObj("unitPrice"+curr_row).innerHTML = unitprice;
    //getOpenerObj("qtyInStock"+curr_row).innerHTML = qtyinstock;

    // Apply decimal round-off to value
    if(!isNaN(parseFloat(unitprice))) unitprice = roundPriceValue(unitprice);
    window.opener.document.EditView.elements["mySetup_Tab2_listPrice"+curr_row].value = unitprice;


    window.opener.document.EditView.elements["mySetup_Tab2_qty"+curr_row].focus()
}
function set_return_inventory_mySetup_Tab1(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id,pack_size,uom) {
    //alert(curr_row);
    var subprod = subprod_id.split("::");
    //window.opener.document.EditView.elements["mySetup_Disc_subproduct_ids"+curr_row].value = subprod[0];
    //window.opener.document.getElementById("mySetup_Disc_subprod_names"+curr_row).innerHTML = subprod[1];
    //alert(555);
    window.opener.document.EditView.elements["mySetup_Tab1_productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["mySetup_Tab1_hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["mySetup_Tab1_listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["mySetup_Tab1_comment"+curr_row].value = desc;
    window.opener.document.EditView.elements["mySetup_Tab1_pack_size"+curr_row].value = pack_size;
    window.opener.document.EditView.elements["mySetup_Tab1_uom"+curr_row].value = uom;//alert(unitprice);
    //getOpenerObj("unitPrice"+curr_row).innerHTML = unitprice;
    //getOpenerObj("qtyInStock"+curr_row).innerHTML = qtyinstock;

    // Apply decimal round-off to value
    if(!isNaN(parseFloat(unitprice))) unitprice = roundPriceValue(unitprice);
    window.opener.document.EditView.elements["mySetup_Tab1_listPrice"+curr_row].value = unitprice;


    window.opener.document.EditView.elements["mySetup_Tab1_qty"+curr_row].focus()
}
function get_product_price(product_id,relmod_id,curr_row)
{
    new Ajax.Request(
        'ajax/call_product_pricelist.php',
        {
			/*queue: {position: 'end', scope: 'command'},*/
            method: 'post',
            async:false,
            postBody: 'productid='+product_id+'&accountid='+relmod_id,
            onComplete: function(response, json)
            {
                unit_price_quotes = response.responseText;
                window.opener.document.EditView.elements["listPrice"+curr_row].value = parseFloat(unit_price_quotes);
                window.opener.document.EditView.elements["price_list_std"+curr_row].value = parseFloat(unit_price_quotes);
            }
        }
    );
}
function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}

function set_return_inventory(product_id,product_code,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id,pro_type,qty,uom,promotion_id,module_id,relmod_id,
    return_module,productcode,parent_module,productcode_view,listprice_inc,pricetype,business_code,unit,sellingprice,productcategory,productsubcategory,mat_gp3_desciption,productstatus,component_surface_finish,component_size,component_surface_thinkness,product_unit){

    if(return_module == 'PriceList' ){

        window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
        window.opener.document.EditView.elements["comment"+curr_row].value = desc;
        window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
        window.opener.document.getElementById("productcategory"+curr_row).innerHTML = productsubcategory;
        window.opener.document.getElementById("productsize"+curr_row).innerHTML = mat_gp3_desciption;
        var status = '';
        if(productstatus == 1){
            status = 'Active';
        }else{
            status = 'InActive';
        }
        window.opener.document.getElementById("productstatus"+curr_row).innerHTML = status;
        window.opener.document.getElementById("unit"+curr_row).innerHTML = unit;
        window.opener.document.EditView.elements["listPrice"+curr_row].value = sellingprice;
    } else if(return_module == 'Claim') {  
        var rowID = curr_row.split('_')
        window.opener.jQuery(`input[name=${rowID[0]}_product_id_${rowID[1]}]`).val(product_id)
        window.opener.jQuery(`input[name=${rowID[0]}_product_name_${rowID[1]}]`).val(product_name)
    }else if(return_module == 'Projects' ){
        window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
        window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;
        window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
        window.opener.document.EditView.elements["comment"+curr_row].value = desc;
        window.opener.document.EditView.elements["uom"+curr_row].value = unit;
        window.opener.document.EditView.elements["listPrice"+curr_row].value = 0;
        window.opener.document.EditView.elements["listPrice"+curr_row].value = sellingprice;
        window.opener.document.getElementById("productcategory"+curr_row).innerHTML = productcategory;
        window.opener.document.EditView.elements["qty"+curr_row].value = '0';
        window.opener.document.EditView.elements["qty_act"+curr_row].value = '0';
        window.opener.document.EditView.elements["qty_ship"+curr_row].value = '0';
        window.opener.document.EditView.elements["listprice_total"+curr_row].value = '0';

    }else if(return_module == 'Quotes' ){
        var productnameid = "productName"+curr_row;
        window.opener.document.getElementById("productCodeView"+curr_row).innerHTML = productcode;
        window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
        window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
        if(pricetype == 'Exclude Vat'){
            window.opener.document.EditView.elements["listPrice"+curr_row].value = formatNumber(unitprice);
        }else if(pricetype == 'Include Vat'){
            window.opener.document.EditView.elements["listPrice"+curr_row].value = formatNumber(listprice_inc);
        }
        window.opener.document.EditView.elements["comment"+curr_row].value = desc;
        window.opener.document.EditView.elements["uom"+curr_row].value = uom;
        window.opener.document.EditView.elements["price_list_std"+curr_row].value = formatNumber(unitprice);
        window.opener.document.EditView.elements["productcode"+curr_row].value = productcode;
        window.opener.document.EditView.elements["listprice_exc"+curr_row].value = formatNumber(unitprice);
        window.opener.document.EditView.elements["listprice_inc"+curr_row].value = formatNumber(listprice_inc);
        window.opener.document.getElementById("productCodeView"+curr_row).innerHTML = productcode_view;
        window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;
        window.opener.calcTotal();
        window.opener.set_tax_manual();

    }else if(return_module == 'Samplerequisition'){
        
        window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
        window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
        window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;
        window.opener.document.EditView.elements["comment"+curr_row].value = desc;

        window.opener.document.EditView.elements["sr_finish"+curr_row].value = component_surface_finish; //ชนิดผิว
        window.opener.document.EditView.elements["sr_size_mm"+curr_row].value = component_size; //ขนาด (มม.)
        window.opener.document.EditView.elements["sr_thickness_mm"+curr_row].value = component_surface_thinkness; //ความหนา (มม.)
        window.opener.document.EditView.elements["sr_product_unit"+curr_row].value = product_unit; //หน่วยนับ
    }else if (return_module == 'Purchasesorder'){
        window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
        window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
        window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;
        window.opener.document.EditView.elements["comment"+curr_row].value = desc;


    }else{
        window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
        window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
        window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;
        window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    }

    // Apply decimal round-off to value
    if(unitprice<="0" || unitprice==""){
        unitprice="1";
    }
    if(!isNaN(parseFloat(unitprice))) unitprice = roundPriceValue(unitprice);

   /* if(return_module == 'PriceList'){
        window.opener.document.EditView.elements["listPrice"+curr_row].value = 0;
    }else{
        window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;
    }*/
    var tax_array = new Array();
    var tax_details = new Array();
    tax_array = taxstr.split(',');
    for(var i=0;i<tax_array.length;i++)
    {
        tax_details = tax_array[i].split('=');
    }
    if(pro_type=="P" || pro_type==""){
        if(return_module == 'Projects'){
            window.opener.document.EditView.elements["qty"+curr_row].focus();
        }
    }else{
        window.opener.document.EditView.elements["qty"+curr_row].readOnly =false;
        window.opener.document.EditView.elements["listPrice"+curr_row].focus();
    }
}


function set_return_inventory_newpopup(product_id,product_code,product_name,unitprice	,qtyinstock,taxstr,curr_row,desc,subprod_id,pack_size,uom,qty,pro_type,promotion_id,return_module,relmod_id,parent_module,productcode,listprice_inc) {

    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    window.opener.document.EditView.elements["pack_size"+curr_row].value = pack_size;
    window.opener.document.EditView.elements["uom"+curr_row].value = uom;
    window.opener.document.EditView.elements["price_list_std"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["productcode"+curr_row].value = productcode;
    window.opener.document.EditView.elements["listprice_exc"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["listprice_inc"+curr_row].value = listprice_inc;

    var pricelist = jQuery('#popup_product_'+product_id).data("pricelist");

    var pricelist_json = JSON.stringify(eval("(" + pricelist + ")"));

    dateJson = jQuery.parseJSON(pricelist_json);

    //var dataObjectBase64 = base64encode(pricelist_json);
    url = 'search_pop_pricelist.php?productid='+dateJson.productid;
    url +='&accountid='+dateJson.accountid+'&accounttype='+dateJson.accounttype;
    url += '&curr_row='+curr_row;
    jQuery('#dialog').window({
        title: 'Price List',
        width: 880,
        height: 650,
        closed: false,
        cache: false,
        href: url,
        modal: true
    });

    window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;
    //window.open(url,'Price List','resizable=yes,left=50,top=50,width=880,height=650,toolbar=no,scrollbars=no,menubar=no,location=no')

    //window.close();
}
function set_return_inventory_set(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id,pack_size,uom,qty,pro_type,promotion_id,qty_set) {

    //alert(promotion_id);
    var subprod = subprod_id.split("::");
    var pre_mium = promotion_id.split("::");
    var pre_mium_id=pre_mium[0];
    var pre_mium_chk="";
    var pre_mium_value=pre_mium[2];
    //alert(pre_mium[1]);
    if(pre_mium[1]=="%"){
        pre_mium_chk="1"
    }else if(pre_mium[1]=="baht"){
        pre_mium_chk="2"
    }else{
        pre_mium_chk="0"
    }
    if(qty_set=="" || qty_set==""){
        qty_set="1";
    }
    window.opener.document.EditView.elements["subproduct_ids"+curr_row].value = subprod[0];
    window.opener.document.getElementById("subprod_names"+curr_row).innerHTML = subprod[1];

    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    window.opener.document.EditView.elements["pack_size"+curr_row].value = pack_size;
    window.opener.document.EditView.elements["uom"+curr_row].value = uom;//alert(unitprice);
    if(pro_type=="S"){
        window.opener.document.EditView.elements["qty"+curr_row].value = qty_set*qty;//alert(unitprice);
        window.opener.document.EditView.elements["pro_type"+curr_row].value = pro_type;//alert(unitprice);
        window.opener.document.EditView.elements["promotion_id"+curr_row].value = pre_mium_id;//alert(unitprice);
    }
    //getOpenerObj("unitPrice"+curr_row).innerHTML = unitprice;
    //getOpenerObj("qtyInStock"+curr_row).innerHTML = qtyinstock;

    // Apply decimal round-off to value
    if(!isNaN(parseFloat(unitprice))) unitprice = roundPriceValue(unitprice);
    window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;

    var tax_array = new Array();
    var tax_details = new Array();
    tax_array = taxstr.split(',');
    for(var i=0;i<tax_array.length;i++)
    {
        tax_details = tax_array[i].split('=');
    }
    //alert(document.getElementById("discount_2").value);
    if(pro_type=="S"){
        window.opener.document.getElementById("productTotal"+curr_row).innerHTML=((qty*qty_set)*unitprice).toFixed(2);
        window.opener.document.getElementById("totalAfterDiscount"+curr_row).innerHTML=((qty*qty_set)*unitprice).toFixed(2);
        window.opener.document.getElementById("netPrice"+curr_row).innerHTML=((qty*qty_set)*unitprice).toFixed(2);
        var max_row_count = window.opener.document.getElementById('proTab').rows.length;
        max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from table row length
        var total=0;
        //alert(max_row_count);
        for(var i=1;i<=max_row_count;i++){
            if(window.opener.document.getElementById("deleted"+i).value == 1)
                continue;
            total=total+eval(window.opener.document.getElementById("netPrice"+i).innerHTML)

            //alert(eval(window.opener.document.getElementById("netPrice"+i).innerHTML));
        }
        //window.opener.document.getElementById("netTotal").innerHTML=tatal;
        window.opener.document.getElementById("netTotal").innerHTML = eval(total).toFixed(2);
        //alert(total);
        window.opener.document.EditView.elements["qty"+curr_row].readOnly =true;
        window.opener.document.EditView.elements["listPrice"+curr_row].readOnly =true;
        //set discount
        var discount_checks = window.opener.document.getElementsByName("discount_final");
        discount_checks[pre_mium_chk].checked=true;
        //alert(pre_mium_chk);
        //alert(pre_mium_value);
        if(pre_mium_chk=="2"){
            //	var discount_xx=0;
            //discount_xx=eval(window.opener.document.EditView.elements["discount_amount_final"].value);
            //if(discount_xx>0){
            //pre_mium_value=eval(pre_mium_value+discount_xx);
            //}

            window.opener.document.EditView.elements["discount_amount_final"].value=pre_mium_value;
            window.opener.document.getElementById("grandTotal").innerHTML = eval(total-pre_mium_value).toFixed(2);
        }else if(pre_mium_chk=="1"){
            window.opener.document.EditView.elements["discount_percentage_final"].value=pre_mium_value;
            window.opener.document.getElementById("discountTotal_final").innerHTML=eval(((total/100)*pre_mium_value)).toFixed(2);
            window.opener.document.getElementById("grandTotal").innerHTML = eval(total-((total/100)*pre_mium_value)).toFixed(2);
        }
        //calcTotal();
        //window.opener.document.EditView.elements["listPrice"+curr_row].click();
        //window.opener.document.EditView.elements["listPrice"+curr_row].focus();
        //window.opener.document.EditView.elements["discount_amount_final"].focus();
    }else{
        window.opener.document.EditView.elements["qty"+curr_row].focus();
    }

    //document.getElementById("discount1").checked;
    //$jQuery('input:radio[name="discount1"]').filter('[value="2"]').attr('checked', true);
}

function set_return_inventory_po(product_id,product_name,unitprice,taxstr,curr_row,desc,subprod_id) {
    var subprod = subprod_id.split("::");
    window.opener.document.EditView.elements["subproduct_ids"+curr_row].value = subprod[0];
    window.opener.document.getElementById("subprod_names"+curr_row).innerHTML = subprod[1];

    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;
    window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    //getOpenerObj("unitPrice"+curr_row).innerHTML = unitprice;

    // Apply decimal round-off to value
    if(!isNaN(parseFloat(unitprice))) unitprice = roundPriceValue(unitprice);
    window.opener.document.EditView.elements["listPrice"+curr_row].value = unitprice;

    var tax_array = new Array();
    var tax_details = new Array();
    tax_array = taxstr.split(',');
    for(var i=0;i<tax_array.length;i++)
    {
        tax_details = tax_array[i].split('=');
    }

    window.opener.document.EditView.elements["qty"+curr_row].focus()
}

function set_return_product(product_id, product_name) {
    if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.parent_name.value = product_name;
        window.opener.document.QcEditView.parent_id.value = product_id;
    } else {
        window.opener.document.EditView.product_name.value = product_name;
        window.opener.document.EditView.product_id.value = product_id;
    }
}
function getImageListBody() {
    if (browser_ie) {
        var ImageListBody=getObj("ImageList")
    } else if (browser_nn4 || browser_nn6) {
        if (getObj("ImageList").childNodes.item(0).tagName=="TABLE") {
            var ImageListBody=getObj("ImageList")
        } else {
            var ImageListBody=getObj("ImageList")
        }
    }
    return ImageListBody;
}

// Function to Round off the Price Value
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
// End

function set_return_inventory_order(product_id,product_code,product_name,unitprice,return_module,relmod_id,parent_module,pricetype,zone,truck_size,unit,min,dlv_c,dlv_c_vat,dlv_p_vat,lp,lp_disc,c_cost,c_price_vat,c_cost_vat){

    window.opener.document.EditView.elements["productName1"].value = product_name;
    window.opener.document.EditView.elements["hdnProductId1"].value = product_id;
    window.opener.document.EditView.elements["km"].value = '';
    window.opener.document.EditView.elements["zone"].value = zone;
    window.opener.document.EditView.elements["carsize"].value = truck_size;
    window.opener.document.EditView.elements["unit1"].value = unit;
    window.opener.document.EditView.elements["number1"].value = 0;
    window.opener.document.EditView.elements["priceperunit1"].value = c_price_vat;
    window.opener.document.EditView.elements["amount1"].value = 0;
    window.opener.document.EditView.elements["min"].value = min;
    window.opener.document.EditView.elements["dlv_c"].value = dlv_c;
    window.opener.document.EditView.elements["dlv_cvat"].value = dlv_c_vat;
    window.opener.document.EditView.elements["dlv_pvat"].value = dlv_p_vat;
    window.opener.document.EditView.elements["lp"].value = lp;
    window.opener.document.EditView.elements["discount"].value = lp_disc;
    window.opener.document.EditView.elements["c_cost"].value = c_cost;
    window.opener.document.EditView.elements["afterdiscount1"].value = c_cost;
    window.opener.document.EditView.elements["purchaseamount1"].value = 0;
    
    window.opener.document.EditView.elements["profit"].value = eval(c_price_vat-c_cost_vat).toFixed(2);
    /*window.opener.document.getElementById("subTotal1").innerHTML = formatNumber(eval(c_price_vat).toFixed(2));
    var vat1 = 0;
    vat1 = vat1+eval((c_price_vat*7)/100);
    var total1 = 0;
    total1 = (c_price_vat*107)/100;
    window.opener.document.getElementById("Vat1").innerHTML = formatNumber(eval(vat1).toFixed(2));
    window.opener.document.getElementById("Total1").innerHTML = formatNumber(eval(total1).toFixed(2));
    
    window.opener.document.getElementById("subTotal2").innerHTML = formatNumber(eval(c_cost).toFixed(2));
    var vat2 = 0;
    vat2 = vat2+eval((c_cost*7)/100);
    var total2 = 0;
    total2 = (c_cost*107)/100;
    window.opener.document.getElementById("Vat2").innerHTML = formatNumber(eval(vat2).toFixed(2));
    window.opener.document.getElementById("Total2").innerHTML = formatNumber(eval(total2).toFixed(2));*/
    
}

function escapeHtml (string) {
    var entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;',
      '`': '&#x60;',
      '=': '&#x3D;'
    }
    return String(string).replace(/[&<>"'`=\/]/g, function (s) {
        return entityMap[s];
    });
}

function htmlDecode(input) {
  var doc = new DOMParser().parseFromString(input, "text/html");
  return doc.documentElement.textContent;
}

function set_return_inventory_quotation(product_id,product_code,product_name,taxstr,curr_row,desc,productcategory,productstatus,sellingprice,unit,return_module,mat_gp1_desciption,mat_gp3_desciption,mat_gp4_desciption,piece_per_carton,squaremeters_per_carton,price_per_piece,price_per_squaremeter,um_coversion_m2_pcs,product_finish,product_size_mm,product_thinkness,product_unit,product_cost_avg,description,package_size_sheet_per_box,package_size_sqm_per_box,sales_unit){
    
    
    var productnameid = "productName"+curr_row;
    //window.opener.document.getElementById("productCodeView"+curr_row).innerHTML = productcode;
    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    // window.opener.document.EditView.elements["selling_price"+curr_row].value = sellingprice;
    
    window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    // window.opener.document.EditView.elements["uom"+curr_row].value = unit;

    // window.opener.document.EditView.elements["product_finish"+curr_row].value = product_finish;
    // window.opener.document.EditView.elements["product_size_mm"+curr_row].value = product_size_mm;
    // window.opener.document.EditView.elements["product_thinkness"+curr_row].value = product_thinkness;
    // window.opener.document.EditView.elements["product_unit"+curr_row].value = product_unit;
    // window.opener.document.EditView.elements["product_cost_avg"+curr_row].value = product_cost_avg;
    
    //window.opener.document.EditView.elements["mat_gp1_desciption"+curr_row].value = htmlDecode(mat_gp1_desciption);
    //window.opener.document.EditView.elements["mat_gp3_desciption"+curr_row].value = htmlDecode(mat_gp3_desciption);
    //window.opener.document.EditView.elements["mat_gp4_desciption"+curr_row].value = htmlDecode(mat_gp4_desciption);

    //window.opener.document.EditView.elements["piece_per_carton"+curr_row].value = um_coversion_m2_pcs;
    //window.opener.document.EditView.elements["squaremeters_per_carton"+curr_row].value = squaremeters_per_carton;
    //window.opener.document.EditView.elements["price_per_piece"+curr_row].value = price_per_piece;
    //window.opener.document.EditView.elements["price_per_squaremeter"+curr_row].value = price_per_squaremeter;

    if(sales_unit == "BOX" || sales_unit == "Box" || sales_unit == "PCS" || sales_unit == "Pcs"){
        window.opener.document.EditView.elements["package_size_sheet_per_box"+curr_row].value = package_size_sheet_per_box; // ขนาดบรรจุแผ่น
        window.opener.document.EditView.elements["package_size_sqm_per_box"+curr_row].value = package_size_sqm_per_box; // ขนาดบรรจุ ตรม
    }else if(sales_unit == "-"){
        window.opener.document.EditView.elements["package_size_sheet_per_box"+curr_row].value = package_size_sheet_per_box; // ขนาดบรรจุแผ่น
        if(package_size_sqm_per_box==0 || package_size_sqm_per_box == 0.000 || package_size_sqm_per_box == ''){
            package_size_sqm_per_box = 1;
        }
        window.opener.document.EditView.elements["package_size_sqm_per_box"+curr_row].value = package_size_sqm_per_box; // ขนาดบรรจุ ตรม
    }else{
        if(package_size_sheet_per_box==0 || package_size_sheet_per_box == 0.000 || package_size_sheet_per_box == ''){
            package_size_sheet_per_box = 1;
        }
        window.opener.document.EditView.elements["package_size_sheet_per_box"+curr_row].value = package_size_sheet_per_box; // ขนาดบรรจุแผ่น
        window.opener.document.EditView.elements["package_size_sqm_per_box"+curr_row].value = package_size_sqm_per_box; // ขนาดบรรจุ ตรม
    }

    window.opener.document.EditView.elements["sales_unit"+curr_row].value = sales_unit;
    window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;

    window.opener.calcTotal();
    window.opener.set_tax_manual();
}

function set_return_inventory_salesorder(product_id,product_code,product_name,taxstr,curr_row,desc,productcategory,productstatus,sellingprice,unit,stockavailable,return_module){
    
    /*if(stockavailable == '' || stockavailable <= 0){
        alert(product_name+' :: จำนวนสินค้าในคลังไม่พร้อมขาย');
        return false;
    }*/
    var productnameid = "productName"+curr_row;
    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["listPrice"+curr_row].value = sellingprice;

    window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    window.opener.document.EditView.elements["uom"+curr_row].value = unit;
    window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;
    window.opener.document.getElementById("qtyInStock"+curr_row).innerHTML = stockavailable;
    window.opener.calcTotal();
    window.opener.set_tax_manual();
}

function set_return_inventory_projects(product_id,product_code,product_name,taxstr,curr_row,desc,productcategory,productstatus,sellingprice,unit,stockavailable,return_module){
    
    var productnameid = "productName"+curr_row;
    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    window.opener.document.EditView.elements["comment"+curr_row].value = desc;
    window.opener.document.getElementById("productcategory"+curr_row).innerHTML = productcategory;
    window.opener.document.EditView.elements["uom"+curr_row].value = unit;
    window.opener.document.EditView.elements["listPrice"+curr_row].value = sellingprice;
    window.opener.document.EditView.elements["productName"+curr_row].readOnly = false;

    window.opener.document.EditView.elements["qty"+curr_row].value = '0';
    window.opener.document.EditView.elements["qty_act"+curr_row].value = '0';
    window.opener.document.EditView.elements["qty_ship"+curr_row].value = '0';
    window.opener.document.EditView.elements["listprice_total"+curr_row].value = '0';

}

function set_return_inventory_pricelist(product_id,product_code,product_name,product_description,taxstr,curr_row,desc,productcategory,productstatus,sellingprice,unit,productsubcategory,return_module,product_finish,product_size_mm,product_thinkness,product_unit,product_cost_avg,product_brand,product_weight_per_box){//mat_gp3_desciption
    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["comment"+curr_row].value = product_description;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    //window.opener.document.getElementById("productcategory"+curr_row).innerHTML = productcategory;
    // window.opener.document.getElementById("productcategory"+curr_row).innerHTML = productsubcategory;
    //window.opener.document.getElementById("productsize"+curr_row).innerHTML = mat_gp3_desciption;


    window.opener.document.EditView.elements["product_brand"+curr_row].value = product_brand;
    window.opener.document.EditView.elements["product_weight_per_box"+curr_row].value = product_weight_per_box;

    var status = '';
    if(productstatus == 1){
        status = 'Active';
    }else{
        status = 'InActive';
    }
    window.opener.document.getElementById("productstatus"+curr_row).value = status;
    
    // window.opener.document.getElementById("unit"+curr_row).innerHTML = unit;

    // window.opener.document.EditView.elements["selling_price"+curr_row].value = sellingprice;
    // window.opener.calcTotal_inc(curr_row);
    // alert('Before calling cal_listprice_inc');
    // window.opener.cal_listprice_inc();
    // alert('After calling cal_listprice_inc');    // window.opener.calcTotal();
    // window.opener.set_tax_manual();
}

function set_return_inventory_purchasesorder(product_id,product_code,product_name,product_description,curr_row,product_brand,product_group,product_code_crm,product_prefix,product_factory_code,product_design_name,product_finish,product_size_ft,product_thinkness,product_grade,product_film,product_backprint,price_usd,selling_price){
    // alert(curr_row);
    window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
    window.opener.document.EditView.elements["productcode"+curr_row].value = product_code;
    window.opener.document.EditView.elements["comment"+curr_row].value = product_description;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
    

    window.opener.document.EditView.elements["product_brand"+curr_row].value = product_brand;
    window.opener.document.EditView.elements["product_group"+curr_row].value = product_group;
    window.opener.document.EditView.elements["product_code_crm"+curr_row].value = product_code_crm;
    window.opener.document.EditView.elements["product_prefix"+curr_row].value = product_prefix;
    window.opener.document.EditView.elements["product_factory_code"+curr_row].value = product_factory_code;
    window.opener.document.EditView.elements["product_design_name"+curr_row].value = product_design_name;
    window.opener.document.EditView.elements["product_finish_name"+curr_row].value = product_finish;
    window.opener.document.EditView.elements["product_size_ft"+curr_row].value = product_size_ft;
    window.opener.document.EditView.elements["product_thinkness"+curr_row].value = product_thinkness;
    window.opener.document.EditView.elements["product_grade"+curr_row].value = product_grade;
    window.opener.document.EditView.elements["product_film"+curr_row].value = product_film;
   
    window.opener.document.EditView.elements["product_backprint"+curr_row].value = product_backprint;
    window.opener.document.EditView.elements["price_usd"+curr_row].value = price_usd;
    window.opener.document.EditView.elements["unit_price"+curr_row].value = price_usd;
}

function set_return_inventory_samplerequisition(product_id,product_code,product_name,product_description,curr_row,product_finish,product_size_mm,product_thinkness,unit,product_catalog_code){
    window.opener.document.EditView.elements["productName"+curr_row].value = product_catalog_code;
    // window.opener.document.EditView.elements["productcode"+curr_row].value = product_code;
    window.opener.document.EditView.elements["comment"+curr_row].value = product_description;
    window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;

    window.opener.document.EditView.elements["sr_finish"+curr_row].value = product_finish;
    window.opener.document.EditView.elements["sr_size_mm"+curr_row].value = product_size_mm;
    window.opener.document.EditView.elements["sr_thickness_mm"+curr_row].value = product_thinkness;
    window.opener.document.EditView.elements["sr_product_unit"+curr_row].value = unit;


    var selectedValue = "Standard";
    var dropdown1 = window.opener.document.getElementById("sr_finish" + curr_row);
    for (var i = 0; i < dropdown1.options.length; i++) {
        if (dropdown1.options[i].value === selectedValue) {
            dropdown1.options[i].selected = true;
            break;
        }
    }

    var dropdown2 = window.opener.document.getElementById("sr_size_mm" + curr_row);
    for (var i = 0; i < dropdown2.options.length; i++) {
        if (dropdown2.options[i].value === selectedValue) {
            dropdown2.options[i].selected = true;
            break;
        }
    }

    var dropdown3 = window.opener.document.getElementById("sr_thickness_mm" + curr_row);
    for (var i = 0; i < dropdown3.options.length; i++) {
        if (dropdown3.options[i].value === selectedValue) {
            dropdown3.options[i].selected = true;
            break;
        }
    }
   
}
