/*********************************************************************************

 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
jQuery(document).ready(function() {

    jQuery('input[name="order_date"]').change(function() {
        var date =jQuery(this).val();
        

    });

});

function order_date(dateText,selectedDate){  

    jQuery('input[name="order_date"]').val(selectedDate);
    var new_date = selectedDate.split('-');
    var format_Date = new_date[2]+'-'+new_date[1]+'-'+new_date[0];
    var date = new Date(format_Date);
    date.setDate(date.getDate() + 14);
    var validity = ("0" + date.getDate()).slice(-2)+'/'+("0" + (date.getMonth() + 1)).slice(-2)+'/'+date.getFullYear();
    jQuery('input[name="validity"]').val(validity);
        
}

function set_Calctotal(){

    var number1 = document.getElementById('number1').value;
    var priceperunit1 = document.getElementById('priceperunit1').value;

    var amount1 = number1*priceperunit1;
    document.getElementById("amount1").value = amount1.toFixed(2);

    var afterdiscount = document.getElementById('afterdiscount1').value;
    var purchaseamount1 = number1*afterdiscount;
    document.getElementById("purchaseamount1").value = purchaseamount1.toFixed(2);

    set_total();
}


function set_Cal2(){
	var number2 = document.getElementById('number2').value;
	var priceperunit2 = document.getElementById('priceperunit2').value;
	var amount2 = number2*priceperunit2;
    document.getElementById("amount2").value = amount2.toFixed(2);
}

function set_Cal3(){
	var number3 = document.getElementById('number3').value;
	var priceperunit3 = document.getElementById('priceperunit3').value;
	var amount3 = number3*priceperunit3;
    document.getElementById("amount3").value = amount3.toFixed(2);
}

function set_Cal4(){
	var number4 = document.getElementById('number4').value;
	var priceperunit4 = document.getElementById('priceperunit4').value;
	var amount4 = number4*priceperunit4;
    document.getElementById("amount4").value = amount4.toFixed(2);
}

function set_afterdiscount2(){
	var afterdiscount2 = document.getElementById('afterdiscount2').value;
	//console.log(afterdiscount2);
	document.getElementById("purchaseamount2").value = eval(afterdiscount2).toFixed(2);
}

function set_afterdiscount3(){
	var afterdiscount3 = document.getElementById('afterdiscount3').value;
	document.getElementById("purchaseamount3").value = eval(afterdiscount3).toFixed(2);
}

function set_afterdiscount4(){
	var afterdiscount4 = document.getElementById('afterdiscount4').value;
	document.getElementById("purchaseamount4").value = eval(afterdiscount4).toFixed(2);
}

function set_discount(){

	document.getElementById("afterdiscount1").value = 0;
	document.getElementById("purchaseamount1").value = 0;

	set_total();
}

function set_total(){
	
	var amount1 = document.getElementById('amount1').value;
	var amount2 = document.getElementById('amount2').value;
	var amount3 = document.getElementById('amount3').value;
	var amount4 = document.getElementById('amount4').value;

	var subtotal = eval(amount1)+eval(amount2)+eval(amount3)+eval(amount4);
	var vat = (subtotal*7)/100;
	var total = (subtotal*107)/100;

	document.getElementById("subTotal1").innerHTML = eval(subtotal).toFixed(2);
	document.getElementById("Vat1").innerHTML = eval(vat).toFixed(2);
	document.getElementById("Total1").innerHTML = eval(total).toFixed(2);

	document.getElementById("subtotal1").value = eval(subtotal).toFixed(2);
	document.getElementById("vat1").value = eval(vat).toFixed(2);
	document.getElementById("total1").value = eval(total).toFixed(2);

}


function set_Calctotal2(){

	var purchaseamount1 = document.getElementById('purchaseamount1').value;
	var purchaseamount2 = document.getElementById('purchaseamount2').value;
	var purchaseamount3 = document.getElementById('purchaseamount3').value;
	var purchaseamount4 = document.getElementById('purchaseamount4').value;

	var subtotal = eval(purchaseamount1)+eval(purchaseamount2)+eval(purchaseamount3)+eval(purchaseamount4);
	var vat = (subtotal*7)/100;
	var total = (subtotal*107)/100;

	document.getElementById("subTotal2").innerHTML = eval(subtotal).toFixed(2);
	document.getElementById("Vat2").innerHTML = eval(vat).toFixed(2);
	document.getElementById("Total2").innerHTML = eval(total).toFixed(2);

	document.getElementById("subtotal2").value = eval(subtotal).toFixed(2);
	document.getElementById("vat2").value = eval(vat).toFixed(2);
	document.getElementById("total2").value = eval(total).toFixed(2);
}

function set_return_case(product_id, product_name) {
    //document.EditView.parent_name1.value=product_name;
    window.opener.document.EditView.product_name1.value = product_name;
    window.opener.document.EditView.product_id1.value = product_id;
}
function copyAddressRight(form,module) {

    if(typeof(form.bill_street) != 'undefined' && typeof(form.ship_street) != 'undefined')
        form.ship_street.value = form.bill_street.value;

    if(typeof(form.bill_city) != 'undefined' && typeof(form.ship_city) != 'undefined')
        form.ship_city.value = form.bill_city.value;

    if(typeof(form.bill_state) != 'undefined' && typeof(form.ship_state) != 'undefined')
        form.ship_state.value = form.bill_state.value;

    if(typeof(form.bill_code) != 'undefined' && typeof(form.ship_code) != 'undefined')
        form.ship_code.value = form.bill_code.value;

    if(typeof(form.bill_country) != 'undefined' && typeof(form.ship_country) != 'undefined')
        form.ship_country.value = form.bill_country.value;

    if(typeof(form.bill_pobox) != 'undefined' && typeof(form.ship_pobox) != 'undefined')
        form.ship_pobox.value = form.bill_pobox.value;
    if(module=='Accounts')	{
       /* if(typeof(form.cf_629) != 'undefined' && typeof(form.cf_630) != 'undefined')
            form.cf_630.value = form.cf_629.value;*/

    }else if (module=='Quotes'){

        if(typeof(form.bill_name_shipping) != 'undefined' && typeof(form.quotes_bill_name) != 'undefined')
            form.bill_name_shipping.value = form.quotes_bill_name.value;
        if(typeof(form.address_shipping) != 'undefined' && typeof(form.quotes_address) != 'undefined')
                form.address_shipping.value = form.quotes_address.value;
        if(typeof(form.moo_shipping) != 'undefined' && typeof(form.quotes_moo) != 'undefined')
                form.moo_shipping.value = form.quotes_moo.value;
        if(typeof(form.village_shipping) != 'undefined' && typeof(form.quotes_village) != 'undefined')
                form.village_shipping.value = form.quotes_village.value;
        if(typeof(form.alley_shipping) != 'undefined' && typeof(form.quotest_alley) != 'undefined')
                form.alley_shipping.value = form.quotest_alley.value;
        if(typeof(form.road_shipping) != 'undefined' && typeof(form.quotest_road) != 'undefined')
                form.road_shipping.value = form.quotest_road.value;
        if(typeof(form.region_shipping) != 'undefined' && typeof(form.quotes_region) != 'undefined')
                form.region_shipping.value = form.quotes_region.value;
        if(typeof(form.province_shipping) != 'undefined' && typeof(form.quotes_province) != 'undefined')
                form.province_shipping.value = form.quotes_province.value;
        if(typeof(form.district_shipping) != 'undefined' && typeof(form.quotes_district) != 'undefined')
                form.district_shipping.value = form.quotes_district.value;
        if(typeof(form.subdistrict_shipping) != 'undefined' && typeof(form.quotes_subdistrict) != 'undefined')
                form.subdistrict_shipping.value = form.quotes_subdistrict.value;
        if(typeof(form.postcode_shipping) != 'undefined' && typeof(form.quotes_postcode) != 'undefined')
                form.postcode_shipping.value = form.quotes_postcode.value;
    
    }
    return true;

}

function copyAddressLeft(form,module) {//alert(module);
    //bill go to ship

    if(module=='Accounts')	{
        /*if(typeof(form.cf_629) != 'undefined' && typeof(form.cf_630) != 'undefined')
            form.cf_629.value = form.cf_630.value;*/
        
    }else if (module=='Quotes'){

        if(typeof(form.quotes_bill_name) != 'undefined' && typeof(form.bill_name_shipping) != 'undefined')
            form.quotes_bill_name.value = form.bill_name_shipping.value;
        if(typeof(form.quotes_address) != 'undefined' && typeof(form.address_shipping) != 'undefined')
                form.quotes_address.value = form.address_shipping.value;
        if(typeof(form.quotes_moo) != 'undefined' && typeof(form.moo_shipping) != 'undefined')
                form.quotes_moo.value = form.moo_shipping.value;
        if(typeof(form.quotes_village) != 'undefined' && typeof(form.village_shipping) != 'undefined')
                form.quotes_village.value = form.village_shipping.value;
        if(typeof(form.quotest_alley) != 'undefined' && typeof(form.alley_shipping) != 'undefined')
                form.quotest_alley.value = form.alley_shipping.value;
        if(typeof(form.quotest_road) != 'undefined' && typeof(form.road_shipping) != 'undefined')
                form.quotest_road.value = form.road_shipping.value;
        if(typeof(form.quotes_region) != 'undefined' && typeof(form.region_shipping) != 'undefined')
                form.quotes_region.value = form.region_shipping.value;
        if(typeof(form.quotes_province) != 'undefined' && typeof(form.province_shipping) != 'undefined')
                form.quotes_province.value = form.province_shipping.value;
        if(typeof(form.quotes_district) != 'undefined' && typeof(form.district_shipping) != 'undefined')
                form.quotes_district.value = form.district_shipping.value;
        if(typeof(form.quotes_subdistrict) != 'undefined' && typeof(form.subdistrict_shipping) != 'undefined')
                form.quotes_subdistrict.value = form.subdistrict_shipping.value;
        if(typeof(form.quotes_postcode) != 'undefined' && typeof(form.postcode_shipping) != 'undefined')
                form.quotes_postcode.value = form.postcode_shipping.value;

    }
    return true;

}

function settotalnoofrows() {
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;

    //set the total number of products
    document.EditView.totalProductCount.value = max_row_count;
}

function productPickList(currObj,module, row_no) {

    var trObj=currObj.parentNode.parentNode

    var rowId = row_no;
    var currentRowId = parseInt(currObj.id.match(/([0-9]+)$/)[1]);

    // If we have mismatching rowId and currentRowId, it is due swapping of rows
    if(rowId != currentRowId) {
        rowId = currentRowId;
    }

    var currencyid = document.getElementById("inventory_currency").value;
    var pricetype = document.getElementById("pricetype").value;

    popuptype = 'inventory_prod';
    if(module == 'PurchaseOrder')
        popuptype = 'inventory_prod_po';
    var record_id = '';
    if(document.getElementsByName("plantid").length != 0){
        record_id= document.EditView.plantid.value;
    }
    if(module == 'Order' && record_id==''){
        alert('กรุณาเลือก Plant ID');
        return false;
    }

    var pricetype = document.getElementById("pricetype").value;
    window.open("index.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=OrderEditView&popuptype="+popuptype+"&curr_row="+rowId+"&relmod_id="+record_id+"&parent_module=Plant&return_module="+module+"&currencyid="+currencyid+"&pricetype="+pricetype,"productWin","width=990,height=600,resizable=0,scrollbars=0,status=1,top=100,left=200");
}

function priceBookPickList(currObj, row_no) {
    var trObj=currObj.parentNode.parentNode
    var rowId=row_no;//parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))
    var currencyid = document.getElementById("inventory_currency").value;
    var productId=getObj("hdnProductId"+rowId).value || -1;
    window.open("index.php?module=PriceBooks&action=Popup&html=Popup_picker&form=EditView&popuptype=inventory_pb&fldname=listPrice"+rowId+"&productid="+productId+"&currencyid="+currencyid,"priceBookWin","width=640,height=565,resizable=0,scrollbars=0,top=150,left=200");
}

function getProdListBody() {
    if (browser_ie) {
        var prodListBody=getObj("productList").children[0].children[0]
    } else if (browser_nn4 || browser_nn6) {
        if (getObj("productList").childNodes.item(0).tagName=="TABLE") {
            var prodListBody=getObj("productList").childNodes.item(0).childNodes.item(0)
        } else {
            var prodListBody=getObj("productList").childNodes.item(1).childNodes.item(1)
        }
    }
    return prodListBody;
}


function deleteRow(module,i,image_path)
{
    rowCnt--;
    var tableName = document.getElementById('proTab');
    var prev = tableName.rows.length;
//	document.getElementById('proTab').deleteRow(i);
    document.getElementById("row"+i).style.display = 'none';
// Added For product Reordering starts
    image_path = document.getElementById("hidImagePath").value;
    iMax = tableName.rows.length;
    for(iCount=i;iCount>=1;iCount--)
    {
        if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none')
        {
            iPrevRowIndex = iCount;
            break;
        }
    }
    iPrevCount = iPrevRowIndex;
    oCurRow = eval(document.getElementById("row"+i));
    sTemp = oCurRow.cells[0].innerHTML;
    ibFound = sTemp.indexOf("down_layout.gif");

    if(i != 2 && ibFound == -1 && iPrevCount != 1)
    {
        oPrevRow = eval(document.getElementById("row"+iPrevCount));
        iPrevCount = eval(iPrevCount);
        oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="deleteRow(\''+module+'\','+iPrevCount+')"><input id="deleted'+iPrevCount+'" name="deleted'+iPrevCount+'" type="hidden" value="0">&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
    }
    else if(iPrevCount == 1)
    {
        iSwapIndex = i;
        for(iCount=i;iCount<=iMax-2;iCount++)
        {
            if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none')
            {
                iSwapIndex = iCount;
                break;
            }
        }
        if(iSwapIndex == i)
        {
            oPrevRow = eval(document.getElementById("row"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            oPrevRow.cells[0].innerHTML = '<input type="hidden" id="deleted1" name="deleted1" value="0">&nbsp;';
        }
    }
// Product reordering addition ends
    document.getElementById("hdnProductId"+i).value = "";
    document.getElementById('deleted'+i).value = 1;

    calcTotal()
}
/*  End */


// Function to Calcuate the Inventory total including all products
function calcTotal() {
//console.log('calcTotal Inventory.js line 273')
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length
    var netprice = 0.00;
    for(var i=1;i<=max_row_count;i++)
    {
        rowId = i;
        calcProductTotal(rowId);
    }
    calcGrandTotal();
}

// Function to Calculate the Total for a particular product in an Inventory
function calcProductTotal(rowId) {
    //console.log('calcProductTotal Inventory.js line 287');

    if(document.getElementById('deleted'+rowId) && document.getElementById('deleted'+rowId).value == 0)
    {

        var total=eval(getObj("qty"+rowId).value*getObj("listPrice"+rowId).value);
        getObj("productTotal"+rowId).innerHTML=roundValue(total.toString())

        var totalAfterDiscount = eval(total-document.getElementById("discountTotal"+rowId).innerHTML);
        getObj("totalAfterDiscount"+rowId).innerHTML=roundValue(totalAfterDiscount.toString())


        var tax_type = document.getElementById("taxtype").value;
        //if the tax type is individual then add the tax with net price
        if(tax_type == 'individual')
        {
            callTaxCalc(i);
            netprice = totalAfterDiscount+eval(document.getElementById("taxTotal"+rowId).innerHTML);
        }
        else
            netprice = totalAfterDiscount;

        getObj("netPrice"+rowId).innerHTML=roundValue(netprice.toString())

    }
}

// Function to Calculate the Net and Grand total for all the products together of an Inventory
function calcGrandTotal() {
    var netTotal = 0.0, grandTotal = 0.0;
    var discountTotal_final = 0.0, finalTax = 0.0, sh_amount = 0.0, sh_tax = 0.0, adjustment = 0.0 ,afterdis_final=0.0;

    var taxtype = document.getElementById("taxtype").value; // tax type default => group
    var quotestype = document.EditView.pricetype.value; // vat type Exclude/Include
   /* var cf_4352 = document.EditView.cf_4352.checked; // Discount Include VAT true,false

    var cf_4052 = document.EditView.cf_4052.checked; // แสดง VAT ท้ายรายการ true,false
    var cf_4126 = document.EditView.cf_4126.checked; // แสดงส่วนลดท้ายรายการ true, false
*/
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;// Because the table has two header rows. so we will reduce two from row length

    for (var i=1;i<=max_row_count;i++)
    {
        if(document.getElementById('deleted'+i).value == 0)
        {

            if (document.getElementById("netPrice"+i).innerHTML=="")
                document.getElementById("netPrice"+i).innerHTML = 0.0
            if (!isNaN(document.getElementById("netPrice"+i).innerHTML))
                netTotal += parseFloat(document.getElementById("netPrice"+i).innerHTML)
        }
    }

    var group_tax_percentage = 0;
    var total_new = 0;

        if(quotestype=="Include Vat"){ //vat+ส่วนลด
            var price_include = 0;
            var total_include = 0;

            var group_tax_count = document.getElementById("group_tax_count").value;
            for(var i=1;i<=group_tax_count;i++)
            {
                var group_tax_percentage = document.getElementById("group_tax_percentage"+i).value;
                if(group_tax_percentage == '') group_tax_percentage = '0';
                price_include = eval(netTotal)* eval(100)/ (100+eval(group_tax_percentage));
                total_new = netTotal;
                total_include += price_include;
            }

            netTotal = total_include

        }

        document.getElementById("netTotal").innerHTML = netTotal.toFixed(2);
        document.getElementById("subtotal").value = netTotal.toFixed(2);
        setDiscount(this,'_final');
        calcGroupTax();

        discountTotal_final = document.getElementById("discountTotal_final").innerHTML

        //get the final tax based on the group or individual tax selection
        var taxtype = document.getElementById("taxtype").value;
        if(taxtype == 'group')
        //finalTax = document.getElementById("tax_final").innerHTML
          finalTax = document.getElementById("tax_final").value

        sh_amount = getObj("shipping_handling_charge").value
        sh_tax = document.getElementById("shipping_handling_tax").innerHTML

        adjustment = getObj("adjustment").value

        //Add or substract the adjustment based on selection
        adj_type = document.getElementById("adjustmentType").value;

        afterdis_final = eval(netTotal)-eval(discountTotal_final);
        grandTotal = eval(netTotal)-eval(discountTotal_final)+eval(finalTax);

        if (sh_amount != '') {
            grandTotal = grandTotal + eval(sh_amount)+eval(sh_tax);
        }
        if (adjustment != '') {
            if(adj_type == '+') {
                grandTotal = grandTotal + eval(adjustment)
            }
            else {
                grandTotal = grandTotal - eval(adjustment)
            }
        }
        var tax_n = total_new - netTotal;

    var grandTotal_dis = eval(netTotal) - eval(discountTotal_final);
    //if(quotestype=="Include Vat" && cf_4352 == true){ //ส่วนลด+discount
    if(quotestype=="Include Vat"){
        document.getElementById("total_tax").value = eval(tax_n.toFixed(2))
        document.getElementById("tax_final").value = eval(tax_n.toFixed(2))

        document.getElementById("grandTotal").innerHTML = roundValue(grandTotal_dis.toString())
        document.getElementById("total").value = roundValue(grandTotal_dis.toString())
    }else{ //ส่วนลด
        document.getElementById("total_tax").value = eval(finalTax)
        document.getElementById("grandTotal").innerHTML = roundValue(grandTotal.toString())
        document.getElementById("total").value = roundValue(grandTotal.toString());
    }

    document.getElementById("total_discount").value = eval(discountTotal_final)

    document.getElementById("afterdis_final").innerHTML = roundValue(afterdis_final.toString())
    document.getElementById("total_after_discount").value = roundValue(afterdis_final.toString())
}

function set_tax_manual()
{
    var discountTotal_final = 0.0, finalTax = 0.00, grandTotal = 0.0 ,afterdis_final=0.0;

    netTotal = document.getElementById("netTotal").innerHTML;
    discountTotal_final = document.getElementById("discountTotal_final").innerHTML;
    finalTax = document.getElementById("tax_final").value;

    var quotestype = document.EditView.pricetype.value;
    //var cf_4352 = document.EditView.cf_4352.checked;

    grandTotal = eval(netTotal)-eval(discountTotal_final)+eval(finalTax);

    /*if(quotestype=="Include Vat"){
        grandTotal = eval(netTotal)-eval(discountTotal_final);
    }*/

    document.getElementById("total_tax").value = eval(finalTax)
    document.getElementById("grandTotal").innerHTML = roundValue(grandTotal.toString())
    document.getElementById("total").value = roundValue(grandTotal.toString())
}
//Method changed as per advice by jon http://forums.vtiger.com/viewtopic.php?t=4162
function roundValue(val) {
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

//This function is used to validate the Inventory modules
function validateInventory(module)
{
   
    if(!formValidate())
        return false

    if(module == 'Quotes'){
       
       if(getObj('quote_special').checked == true){
           var numapp = 0;
           var app1 = getObj('approve_level1[]').value;
           var app2 = getObj('approve_level2[]').value;
           var app3 = getObj('approve_level3[]').value;
           var app4 = getObj('approve_level4[]').value;
           if(app1 != ''){numapp += 1;}
           if(app2 != ''){numapp += 1;}
           if(app3 != ''){numapp += 1;}
           if(app4 != ''){numapp += 1;}

           if(numapp < 2){
            alert('กรุณา​เลือกผู้อนุมัติอย่างน้อย 2 level สำหรับ Special Price');
            return false;    
           }
       }

        if (!emptyCheck('approve_level4[]','ผู้อนุมัติใบเสนอราคา Final Approve',getObj('approve_level4[]').type)){
            /*currObj = getObj('approve_level4[]')
            currObj.focus()*/
            return false;
        }
    }
    //for products, vendors and pricebook modules we won't validate the product details. here return the control
    
    if(module == 'Products' || module == 'PriceBooks' || module == 'Projects')
    {
        return true;
    }

    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from table row length


    if(!FindDuplicate())
        return false;

    if(max_row_count == 0)
    {
        alert(alert_arr.NO_LINE_ITEM_SELECTED);
        return false;
    }

        for (var i=1;i<=max_row_count;i++)
        {
            //if the row is deleted then avoid validate that row values
            if(document.getElementById("deleted"+i).value == 1)
                continue;
            if (!emptyCheck("productName"+i,alert_arr.LINE_ITEM,"text")) return false
            if (!emptyCheck("hdnProductId"+i,alert_arr.LINE_ITEM,"text")) return false
            // if (!emptyCheck("qty"+i,"Qty","text")) return false
            // if (!numValidate("qty"+i,"Qty","any")) return false
            // if (!numConstComp("qty"+i,"Qty","G","0")) return false
            // if (!emptyCheck("listPrice"+i,alert_arr.LIST_PRICE,"text")) return false
            // if (!numValidate("listPrice"+i,alert_arr.LIST_PRICE,"any")) return false
        }

    if(module == 'Quotes'){
        return true;
    }
    //Product - Discount validation - not allow negative values

    if(!validateProductDiscounts())
        return false;


    //Final Discount validation - not allow negative values
    discount_checks = document.getElementsByName("discount_final");


    //Percentage selected, so validate the percentage
    if(discount_checks[1].checked == true)
    {
        temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("discount_percentage_final").value);
        if(!temp)
        {
            alert(alert_arr.VALID_FINAL_PERCENT);
            return false;
        }
    }
    if(discount_checks[2].checked == true)
    {
        temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("discount_amount_final").value);
        if(!temp)
        {
            alert(alert_arr.VALID_FINAL_AMOUNT);
            return false;
        }
    }

    //Shipping & Handling validation - not allow negative values
    temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("shipping_handling_charge").value);
    if(!temp)
    {
        alert(alert_arr.VALID_SHIPPING_CHARGE);
        return false;
    }

    //Adjustment validation - allow negative values
    temp = /^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("adjustment").value)
    if(!temp)
    {
        alert(alert_arr.VALID_ADJUSTMENT);
        return false;
    }

    //Group - Tax Validation  - not allow negative values
    //We need to validate group tax only if taxtype is group.
    var taxtype=document.getElementById("taxtype").value;
    if(taxtype=="group")
    {
        var tax_count=document.getElementById("group_tax_count").value;
        for(var i=1;i<=tax_count;i++)
        {

            temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("group_tax_percentage"+i).value);
            if(!temp)
            {
                alert(alert_arr.VALID_TAX_PERCENT);
                return false;
            }
        }
    }

    //Taxes for Shippring and Handling  validation - not allow negative values
    var shtax_count=document.getElementById("sh_tax_count").value;
    for(var i=1;i<=shtax_count;i++)
    {

        temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("sh_tax_percentage"+i).value);
        if(!temp)
        {
            alert(alert_arr.VALID_SH_TAX);
            return false;
        }
    }
    calcTotal(); /* Product Re-Ordering Feature Code Addition */
    return true;



}

function FindDuplicate()
{
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from row length

    var duplicate = false, iposition = '', positions = '', duplicate_products = '';

    var product_id = new Array(max_row_count-1);
    var product_name = new Array(max_row_count-1);
    product_id[1] = getObj("hdnProductId"+1).value;
    product_name[1] = getObj("productName"+1).value;
    for (var i=1;i<=max_row_count;i++)
    {
        iposition = ""+i;
        for(var j=i+1;j<=max_row_count;j++)
        {
            if(i == 1)
            {
                product_id[j] = getObj("hdnProductId"+j).value;
            }
            if(product_id[i] == product_id[j] && product_id[i] != '')
            {
                if(!duplicate) positions = iposition;
                duplicate = true;
                if(positions.search(j) == -1) positions = positions+" & "+j;

                if(duplicate_products.search(getObj("productName"+j).value) == -1)
                    duplicate_products = duplicate_products+getObj("productName"+j).value+" \n";
            }
        }
    }
    if(duplicate)
    {
        //alert("You have selected < "+duplicate_products+" > more than once in line items  "+positions+".\n It is advisable to select the product just once but change the Qty. Thank You");
        if(!confirm(alert_arr.SELECTED_MORE_THAN_ONCE+"\n"+duplicate_products+"\n "+alert_arr.WANT_TO_CONTINUE))
            return false;
    }
    return true;
}

function fnshow_Hide(Lay){
    var tagName = document.getElementById(Lay);
    if(tagName.style.display == 'none')
        tagName.style.display = 'block';
    else
        tagName.style.display = 'none';
}

function ValidateTax(txtObj)
{
    temp= /^\d+(\.\d\d*)*$/.test(document.getElementById(txtObj).value);
    if(temp == false)
        alert(alert_arr.ENTER_VALID_TAX);
}

function loadTaxes_Ajax(curr_row)
{
    //Retrieve all the tax values for the currently selected product
    var lineItemType = document.getElementById("lineItemType"+curr_row).value;
    new Ajax.Request(
        'index.php',
        {queue: {position: 'end', scope: 'command'},
            method: 'post',
            postBody: 'module='+lineItemType+'&action='+lineItemType+'Ajax&file=InventoryTaxAjax&productid='+document.getElementById("hdnProductId"+curr_row).value+'&curr_row='+curr_row+'&productTotal='+document.getElementById('totalAfterDiscount'+curr_row).innerHTML,
            onComplete: function(response)
            {
                $("tax_div"+curr_row).innerHTML=response.responseText;
                document.getElementById("taxTotal"+curr_row).innerHTML = getObj('hdnTaxTotal'+curr_row).value;
                calcTotal();
            }
        }
    );

}


function fnAddTaxConfigRow(sh){

    var table_id = 'add_tax';
    var td_id = 'td_add_tax';
    var label_name = 'addTaxLabel';
    var label_val = 'addTaxValue';
    var add_tax_flag = 'add_tax_type';

    if(sh != '' && sh == 'sh')
    {
        table_id = 'sh_add_tax';
        td_id = 'td_sh_add_tax';
        label_name = 'sh_addTaxLabel';
        label_val = 'sh_addTaxValue';
        add_tax_flag = 'sh_add_tax_type';
    }
    var tableName = document.getElementById(table_id);
    var prev = tableName.rows.length;
    var count = rowCnt;

    var row = tableName.insertRow(0);

    var colone = row.insertCell(0);
    var coltwo = row.insertCell(1);

    colone.className = "cellLabel small";
    coltwo.className = "cellText small";

    colone.innerHTML="<input type='text' id='"+label_name+"' name='"+label_name+"' value='"+tax_labelarr.TAX_NAME+"' class='txtBox' onclick=\"this.form."+label_name+".value=''\";/>";
    coltwo.innerHTML="<input type='text' id='"+label_val+"' name='"+label_val+"' value='"+tax_labelarr.TAX_VALUE+"' class='txtBox' onclick=\"this.form."+label_val+".value=''\";/>";

    document.getElementById(td_id).innerHTML="<input type='submit' name='Save' value=' "+tax_labelarr.SAVE_BUTTON+" ' class='crmButton small save' onclick=\"this.form.action.value='TaxConfig'; this.form."+add_tax_flag+".value='true'; this.form.parenttab.value='Settings'; return validateNewTaxType('"+label_name+"','"+label_val+"');\">&nbsp;<input type='submit' name='Cancel' value=' "+tax_labelarr.CANCEL_BUTTON+" ' class='crmButton small cancel' onclick=\"this.form.action.value='TaxConfig'; this.form.module.value='Settings'; this.form."+add_tax_flag+".value='false'; this.form.parenttab.value='Settings';\">";
}

function validateNewTaxType(fieldname, fieldvalue)
{
    if(trim(document.getElementById(fieldname).value)== '')
    {
        alert(alert_arr.VALID_TAX_NAME);
        return false;
    }
    if(trim(document.getElementById(fieldvalue).value)== '')
    {
        alert(alert_arr.CORRECT_TAX_VALUE);
        return false;
    }
    else
    {
        var temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById(fieldvalue).value);
        if(!temp)
        {
            alert(alert_arr.ENTER_POSITIVE_VALUE);
            return false;
        }
    }

    return true;
}

function validateTaxes(countname)
{
    taxcount = eval(document.getElementById(countname).value)+1;

    if(countname == 'tax_count')
    {
        taxprefix = 'tax';
        taxLabelPrefix = 'taxlabel_tax';
    }
    else
    {
        taxprefix = 'shtax';
        taxLabelPrefix = 'taxlabel_shtax';
    }

    for(var i=1;i<=taxcount;i++)
    {
        taxval = document.getElementById(taxprefix+i).value;
        taxLabelVal = document.getElementById(taxLabelPrefix+i).value;
        document.getElementById(taxLabelPrefix+i).value = taxLabelVal.replace(/^\s*|\s*$/g,'').replace(/\s+/g,'');

        if(document.getElementById(taxLabelPrefix+i).value.length == 0)
        {
            alert(alert_arr.LABEL_SHOULDNOT_EMPTY);
            return false
        }

        //Tax value - numeric validation
        var temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(taxval);
        if(!temp)
        {
            alert("'"+taxval+"' "+alert_arr.NOT_VALID_ENTRY);
            return false;
        }
    }
    return true;
}

//Function used to add a new product row in PO, SO, Quotes and Invoice
function fnAddProductRowInventory(module,image_path){
    rowCnt++;
// alert(module+'dddd');
    var tableName = document.getElementById('proTab');
    var prev = tableName.rows.length;
    var count = eval(prev)-1;//As the table has two headers, we should reduce the count
    var row = tableName.insertRow(prev);
    row.id = "row"+count;
    row.style.verticalAlign = "top";

    var colone = row.insertCell(0);
    var coltwo = row.insertCell(1);
    if(module != "Quotes"){
        var colthree = row.insertCell(2);
        var colfour = row.insertCell(3);
        var colfive = row.insertCell(4);
        var colsix = row.insertCell(5);
        var colseven = row.insertCell(6);
        var coleight = row.insertCell(7);
        var colnine = row.insertCell(8);
        var colten = row.insertCell(9);
        var coleleven = row.insertCell(10);
        var coltwelve = row.insertCell(11);
    }
    else{
        //var colpacksize = row.insertCell(2);
        //var coltestbox = row.insertCell(3);
        var colfour = row.insertCell(2);
        var colfive = row.insertCell(3);
        var colsix = row.insertCell(4);
        var colseven = row.insertCell(5);
        var coleight = row.insertCell(6);
        var colnine = row.insertCell(7);
        var colten = row.insertCell(8);
        var coleleven = row.insertCell(9);
        //var coltwelve = row.insertCell(12);
    }
    /* Product Re-Ordering Feature Code Addition Starts */
    iMax = tableName.rows.length;
    for(iCount=1;iCount<=iMax-3;iCount++)
    {
        if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none')
        {
            iPrevRowIndex = iCount;
        }
    }
    iPrevCount = eval(iPrevRowIndex);
    var oPrevRow = tableName.rows[iPrevRowIndex+1];
    var delete_row_count=count;
    /* Product Re-Ordering Feature Code Addition ends */

    //Delete link
    colone.className = "crmTableRow small";
    colone.id = row.id+"_col1";
    colone.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="deleteRow(\''+module+'\','+count+',\'themes/images/\')"><input id="deleted'+count+'" name="deleted'+count+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
    /* Product Re-Ordering Feature Code Addition Starts */
    if(iPrevCount != 1)
    {
        oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="deleteRow(\''+module+'\','+iPrevCount+')"><input id="deleted'+iPrevCount+'" name="deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
    }
    else
    {
        oPrevRow.cells[0].innerHTML = '<input id="deleted'+iPrevCount+'" name="deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
    }
    /* Product Re-Ordering Feature Code Addition ends */
    //<textarea id="productName1" name="productName1"  class="small" style="width:90%;height:40px">{$PRODUCT_NAME}</textarea>
    //Product Name with Popup image to select product
    coltwo.className = "crmTableRow small"
    coltwo.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small">' +
        '<span id="business_code'+count+'" name="business_code'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>' +
        '<div id="productCodeView'+count+'" style="font-weight:bold;"></div>' +
        '<textarea id="productName'+count+'" name="productName'+count+'" class="small" style="width:90%;height:40px"></textarea>'+
        '<input id="hdnProductId'+count+'" name="hdnProductId'+count+'" value="" type="hidden"><input type="hidden" id="lineItemType'+count+'" name="lineItemType'+count+'" value="Products" />'+
        '<input type="hidden" id="lineItemType'+count+'" name="lineItemType'+count+'" value="Products" />'+
        '<input type="hidden" id="productcode'+count+'" name="productcode'+count+'" value="" />'+
        '&nbsp;<img id="searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
        '</td></tr><tr><td class="small"><input type="hidden" value="" id="subproduct_ids'+count+'" name="subproduct_ids'+count+'" /><span id="subprod_names'+count+'" name="subprod_names'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>'+
        '</td></tr><tr><td class="small" id="setComment'+count+'"><textarea id="comment'+count+'" name="comment'+count+'" class=small style="width:90%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

    //Quantity In Stock - only for SO, Quotes and Invoice
    if(module != "Quotes"){
        colthree.className = "crmTableRow small"
        colthree.innerHTML='<span id="qtyInStock'+count+'">&nbsp;</span>';
    }
    //pack_size
    /*colpacksiz.eclassName = "crmTableRow small"
    colpacksize.innerHTML = '<textarea id="pack_size'+count+'" name="pack_size'+count+'" class="small pak_size" style="width:auto; height: auto" onfocus="this.className=\'pack_size detailedViewTextBoxOn\'"  onBlur="this.className=\'pack_size detailedViewTextBox\';" value=""/></textarea>';
*/
    //test_box
 /*   coltestbox.className = "crmTableRow small"
    coltestbox.innerHTML = '<input id="test_box'+count+'" name="test_box'+count+'" type="text" class="small test_box" style="width:50px" onfocus="this.className=\'test_box detailedViewTextBoxOn\'"  onBlur="this.className=\'test_box detailedViewTextBox\';" value=""/>';
*/
    //uom
    colfour.className = "crmTableRow small"
    colfour.innerHTML = '<input id="uom'+count+'" name="uom'+count+'" type="text" class="small uom" style="width:50px" onfocus="this.className=\'uom detailedViewTextBoxOn\'"  onBlur="this.className=\'uom detailedViewTextBox\';" value=""/>';

    //Quantity
    var temp='';
    colfive.className = "crmTableRow small"
    temp='<input data-rowno="'+count+'" id="qty'+count+'" value="1" name="qty'+count+'" type="text" class="small qty" style="width:50px" onfocus="this.className=\'qty detailedViewTextBoxOn\'" onBlur="this.className=\'qty detailedViewTextBox\';settotalnoofrows(); calcTotal(); set_tax_manual(); loadTaxes_Ajax('+count+');';
    if(module == "Invoice")
    {
        temp+='stock_alert('+count+');';
    }
    temp+='" onChange="setDiscount(this,'+count+')" value=""/><br><span id="stock_alert'+count+'"></span>';
    colfive.innerHTML=temp;

    //price standard
    colsix.className = "crmTableRow small"
    colsix.style.display = "none"
    colsix.innerHTML = '<input data-rowno="'+count+'"  id="price_list_std'+count+'" name="price_list_std'+count+'" type="text" readonly class="small price_list_std" style="width:70px;background-color:#CCC;border: 1px solid  #999;" onfocus="this.className=\'price_list_std detailedViewTextBoxOn\'" onblur="this.className=\'price_list_std detailedViewTextBox\';calcTotal();setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();" value="0.00"/>';

    //price exclude vat
    colseven.className = "crmTableRow small"
    colseven.innerHTML = '<input data-rowno="'+count+'"  id="listprice_exc'+count+'" name="listprice_exc'+count+'" type="text" readonly class="small listprice_exc" style="width:70px;background-color:#CCC;border: 1px solid  #999;" onfocus="this.className=\'listprice_exc detailedViewTextBoxOn\'" onblur="this.className=\'listprice_exc detailedViewTextBox\';change_vat();settotalnoofrows(); setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();" value="0.00"/>';

    //price include vat
    coleight.className = "crmTableRow small"
    coleight.innerHTML = '<input data-rowno="'+count+'"   id="listprice_inc'+count+'" name="listprice_inc'+count+'" type="text" readonly  class="small listprice_inc" style="width:70px;background-color:#CCC;border: 1px solid  #999;" onfocus="this.className=\'listprice_inc detailedViewTextBoxOn\'" onblur="this.className=\'listprice_inc detailedViewTextBox\';change_vat();settotalnoofrows(); setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();" value="0.00"/>';


    //colnine.className = "crmTableRow small"
    //colseven.innerHTML = '<input id="price_list_inv'+count+'" name="price_list_inv'+count+'" type="text" class="small " style="width:50px" onblur="calcTotal();setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();"/>';
    //colnine.innerHTML = '	<a href="javascript:void(0);" onclick="showdialog(\''+count+'\');"><img src="themes/softed/images/search.gif" border="0" title="" alt"" style="cursor:pointer;"></a>';
    //List Price with Discount, Total after Discount and Tax labels
    colnine.className = "crmTableRow small"
    colnine.innerHTML ='<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="right"><input data-rowno="'+count+'"  id="listPrice'+count+'" name="listPrice'+count+'" value="0.00" type="text" class="small listprice" style="width:70px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="this.className=\'detailedViewTextBox\';calcTotal();setDiscount(this,'+count+');callTaxCalc('+count+');calcTotal(); set_tax_manual();" />';
    //colfive.innerHTML +='&nbsp;<img src="themes/images/pricebook.gif" onclick="priceBookPickList(this,'+count+')">';
    colnine.innerHTML +='</td></tr>';
    //colfive.innerHTML +='<tr><td align="right" style="padding:5px;" nowrap>		(-)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'discount_div'+count+'\',\'discount\','+count+')" >'+product_labelarr.DISCOUNT+'</a> : </b><div class=\"discountUI\" id=\"discount_div'+count+'"><input type="hidden" id="discount_type'+count+'" name="discount_type'+count+'" value=""><table width="100%" border="0" cellpadding="5" cellspacing="0" class="small"><tr><td id="discount_div_title'+count+'" nowrap align="left" ></td><td align="right"><img src="themes/images/close.gif" border="0" onClick="fnHidePopDiv(\'discount_div'+count+'\')" style="cursor:pointer;"></td></tr><tr><td align="left" class="lineOnTop"><input type="radio" name="discount'+count+'" checked onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.ZERO_DISCOUNT+'</td><td class="lineOnTop">&nbsp;</td></tr><tr><td align="left"><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; % '+product_labelarr.PERCENT_OF_PRICE+' </td><td align="right"><input type="text" class="small" size="2" id="discount_percentage'+count+'" name="discount_percentage'+count+'" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp;%</td></tr><tr><td align="left" nowrap><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.DIRECT_PRICE_REDUCTION+'</td><td align="right"><input type="text" id="discount_amount'+count+'" name="discount_amount'+count+'" size="5" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();"></td></tr></table></div></td></tr><tr> <td align="right" style="padding:5px;" nowrap><b>'+product_labelarr.TOTAL_AFTER_DISCOUNT+' :</b></td></tr><tr id="individual_tax_row'+count+'" class="TaxShow"><td align="right" style="padding:5px;" nowrap>(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'tax_div'+count+'\',\'tax\','+count+')" >'+product_labelarr.TAX+' </a> : </b><div class="discountUI" id="tax_div'+count+'"></div></td></tr>';
    colnine.innerHTML +='</table> ';


    //Total and Discount, Total after Discount and Tax details
    colten.className = "crmTableRow small";
    colten.align = "right";
    colten.style.display = "none";
    colten.innerHTML = '<span id = "productTotal'+count+'" style="visibility:hidden" ></span>';
    colten.innerHTML += '<span id = "discountTotal'+count+'" style="visibility:hidden" ></span>';
    colten.innerHTML += '<span id = "taxTotal'+count+'" style="visibility:hidden" ></span>';
    colten.innerHTML += '<span id = "totalAfterDiscount'+count+'" style="visibility:hidden" ></span>';

    /*colsix.innerHTML = '<table width="100%" cellpadding="5" cellspacing="0"><tr><td id="productTotal'+count+'" align="right">&nbsp;</td></tr><tr><td id="discountTotal'+count+'" align="right">0.00</td></tr><tr><td id="totalAfterDiscount'+count+'" align="right">&nbsp;</td></tr><tr><td id="taxTotal'+count+'" align="right">0.00</td></tr></table>';*/

    //Net Price
    coleleven.className = "crmTableRow small";
    coleleven.align = "right";
    //coleleven.style.verticalAlign = "bottom";
    //coleleven.style.display = "none"
    coleleven.colSpan = "2";
    coleleven.innerHTML = '<span id="netPrice'+count+'">&nbsp;</span>';

    //This is to show or hide the individual or group tax
    if(module != 'Quotes'){
        decideTaxDiv();
    }


    calcTotal();

    return count;
}

//Function used to add a new product row in PO, SO, Quotes and Invoice
function fnAddProductRow(module,image_path){
    rowCnt++;

    var tableName = document.getElementById('proTab');
    var prev = tableName.rows.length;
    var count = eval(prev)-1;//As the table has two headers, we should reduce the count
    var row = tableName.insertRow(prev);
    row.id = "row"+count;
    row.style.verticalAlign = "top";

    var colone = row.insertCell(0);
    var coltwo = row.insertCell(1);
    if(module == "PurchaseOrder"){
        var colfour = row.insertCell(2);
        var colfive = row.insertCell(3);
        var colsix = row.insertCell(4);
        var colseven = row.insertCell(5);
    }
    else{
        var colthree = row.insertCell(2);
        var colfour = row.insertCell(3);
        var colfive = row.insertCell(4);
        var colsix = row.insertCell(5);
        var colseven = row.insertCell(6);
    }
    /* Product Re-Ordering Feature Code Addition Starts */
    iMax = tableName.rows.length;
    for(iCount=1;iCount<=iMax-3;iCount++)
    {
        if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none')
        {
            iPrevRowIndex = iCount;
        }
    }
    iPrevCount = eval(iPrevRowIndex);
    var oPrevRow = tableName.rows[iPrevRowIndex+1];
    var delete_row_count=count;
    /* Product Re-Ordering Feature Code Addition ends */


    //Delete link
    colone.className = "crmTableRow small";
    colone.id = row.id+"_col1";
    colone.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="deleteRow(\''+module+'\','+count+',\'themes/images/\')"><input id="deleted'+count+'" name="deleted'+count+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
    /* Product Re-Ordering Feature Code Addition Starts */
    if(iPrevCount != 1)
    {
        oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="deleteRow(\''+module+'\','+iPrevCount+')"><input id="deleted'+iPrevCount+'" name="deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
    }
    else
    {
        oPrevRow.cells[0].innerHTML = '<input id="deleted'+iPrevCount+'" name="deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
    }
    /* Product Re-Ordering Feature Code Addition ends */

    //Product Name with Popup image to select product
    coltwo.className = "crmTableRow small"
    coltwo.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small"><span id="business_code'+count+'" name="business_code'+count+'" style="color:#C0C0C0;font-style:italic;"> </span><input id="productName'+count+'" name="productName'+count+'" class="small" style="width: 70%;" value="" readonly="readonly" type="text">'+
        '<input id="hdnProductId'+count+'" name="hdnProductId'+count+'" value="" type="hidden"><input type="hidden" id="lineItemType'+count+'" name="lineItemType'+count+'" value="Products" />'+
        '&nbsp;<img id="searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
        '</td></tr><tr><td class="small"><input type="hidden" value="" id="subproduct_ids'+count+'" name="subproduct_ids'+count+'" /><span id="subprod_names'+count+'" name="subprod_names'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>'+
        '</td></tr><tr><td class="small" id="setComment'+count+'"><textarea id="comment'+count+'" name="comment'+count+'" class=small style="width:70%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

    //Quantity In Stock - only for SO, Quotes and Invoice
    if(module != "PurchaseOrder"){
        colthree.className = "crmTableRow small"
        colthree.innerHTML='<span id="qtyInStock'+count+'">&nbsp;</span>';
    }

    //Quantity
    var temp='';
    colfour.className = "crmTableRow small"
    temp='<input id="qty'+count+'" name="qty'+count+'" type="text" class="small " style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="settotalnoofrows(); calcTotal(); loadTaxes_Ajax('+count+');';
    if(module == "Invoice")
    {
        temp+='stock_alert('+count+');';
    }
    temp+='" onChange="setDiscount(this,'+count+')" value=""/><br><span id="stock_alert'+count+'"></span>';
    colfour.innerHTML=temp;
    //List Price with Discount, Total after Discount and Tax labels
    colfive.className = "crmTableRow small"
    colfive.innerHTML='<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="right"><input id="listPrice'+count+'" name="listPrice'+count+'" value="0.00" type="text" class="small " style="width:70px" onBlur="calcTotal();setDiscount(this,'+count+');callTaxCalc('+count+'); calcTotal();"/>&nbsp;<img src="themes/images/pricebook.gif" onclick="priceBookPickList(this,'+count+')"></td></tr><tr><td align="right" style="padding:5px;" nowrap>		(-)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'discount_div'+count+'\',\'discount\','+count+')" >'+product_labelarr.DISCOUNT+'</a> : </b><div class=\"discountUI\" id=\"discount_div'+count+'"><input type="hidden" id="discount_type'+count+'" name="discount_type'+count+'" value=""><table width="100%" border="0" cellpadding="5" cellspacing="0" class="small"><tr><td id="discount_div_title'+count+'" nowrap align="left" ></td><td align="right"><img src="themes/images/close.gif" border="0" onClick="fnHidePopDiv(\'discount_div'+count+'\')" style="cursor:pointer;"></td></tr><tr><td align="left" class="lineOnTop"><input type="radio" name="discount'+count+'" checked onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.ZERO_DISCOUNT+'</td><td class="lineOnTop">&nbsp;</td></tr><tr><td align="left"><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; % '+product_labelarr.PERCENT_OF_PRICE+' </td><td align="right"><input type="text" class="small" size="2" id="discount_percentage'+count+'" name="discount_percentage'+count+'" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp;%</td></tr><tr><td align="left" nowrap><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.DIRECT_PRICE_REDUCTION+'</td><td align="right"><input type="text" id="discount_amount'+count+'" name="discount_amount'+count+'" size="5" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();"></td></tr></table></div></td></tr><tr> <td align="right" style="padding:5px;" nowrap><b>'+product_labelarr.TOTAL_AFTER_DISCOUNT+' :</b></td></tr><tr id="individual_tax_row'+count+'" class="TaxShow"><td align="right" style="padding:5px;" nowrap>(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'tax_div'+count+'\',\'tax\','+count+')" >'+product_labelarr.TAX+' </a> : </b><div class="discountUI" id="tax_div'+count+'"></div></td></tr></table> ';

    //Quantity
    var temp='';
    colfour.className = "crmTableRow small"
    temp='<input id="qty'+count+'" name="qty'+count+'" type="text" class="small " style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="settotalnoofrows(); calcTotal(); loadTaxes_Ajax('+count+');';
    if(module == "Invoice")
    {
        temp+='stock_alert('+count+');';
    }
    temp+='" onChange="setDiscount(this,'+count+')" value=""/><br><span id="stock_alert'+count+'"></span>';
    colfour.innerHTML=temp;
    //List Price with Discount, Total after Discount and Tax labels
    colfive.className = "crmTableRow small"
    colfive.innerHTML='<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="right"><input id="listPrice'+count+'" name="listPrice'+count+'" value="0.00" type="text" class="small " style="width:70px" onBlur="calcTotal();setDiscount(this,'+count+');callTaxCalc('+count+'); calcTotal();"/>&nbsp;<img src="themes/images/pricebook.gif" onclick="priceBookPickList(this,'+count+')"></td></tr><tr><td align="right" style="padding:5px;" nowrap>		(-)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'discount_div'+count+'\',\'discount\','+count+')" >'+product_labelarr.DISCOUNT+'</a> : </b><div class=\"discountUI\" id=\"discount_div'+count+'"><input type="hidden" id="discount_type'+count+'" name="discount_type'+count+'" value=""><table width="100%" border="0" cellpadding="5" cellspacing="0" class="small"><tr><td id="discount_div_title'+count+'" nowrap align="left" ></td><td align="right"><img src="themes/images/close.gif" border="0" onClick="fnHidePopDiv(\'discount_div'+count+'\')" style="cursor:pointer;"></td></tr><tr><td align="left" class="lineOnTop"><input type="radio" name="discount'+count+'" checked onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.ZERO_DISCOUNT+'</td><td class="lineOnTop">&nbsp;</td></tr><tr><td align="left"><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; % '+product_labelarr.PERCENT_OF_PRICE+' </td><td align="right"><input type="text" class="small" size="2" id="discount_percentage'+count+'" name="discount_percentage'+count+'" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp;%</td></tr><tr><td align="left" nowrap><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.DIRECT_PRICE_REDUCTION+'</td><td align="right"><input type="text" id="discount_amount'+count+'" name="discount_amount'+count+'" size="5" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();"></td></tr></table></div></td></tr><tr> <td align="right" style="padding:5px;" nowrap><b>'+product_labelarr.TOTAL_AFTER_DISCOUNT+' :</b></td></tr><tr id="individual_tax_row'+count+'" class="TaxShow"><td align="right" style="padding:5px;" nowrap>(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'tax_div'+count+'\',\'tax\','+count+')" >'+product_labelarr.TAX+' </a> : </b><div class="discountUI" id="tax_div'+count+'"></div></td></tr></table> ';

    //Quantity
    var temp='';
    colfour.className = "crmTableRow small"
    temp='<input id="qty'+count+'" name="qty'+count+'" type="text" class="small " style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="settotalnoofrows(); calcTotal(); loadTaxes_Ajax('+count+');';
    if(module == "Invoice")
    {
        temp+='stock_alert('+count+');';
    }
    temp+='" onChange="setDiscount(this,'+count+')" value=""/><br><span id="stock_alert'+count+'"></span>';
    colfour.innerHTML=temp;
    //List Price with Discount, Total after Discount and Tax labels
    colfive.className = "crmTableRow small"
    colfive.innerHTML='<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="right"><input id="listPrice'+count+'" name="listPrice'+count+'" value="0.00" type="text" class="small " style="width:70px" onBlur="calcTotal();setDiscount(this,'+count+');callTaxCalc('+count+'); calcTotal();"/>&nbsp;<img src="themes/images/pricebook.gif" onclick="priceBookPickList(this,'+count+')"></td></tr><tr><td align="right" style="padding:5px;" nowrap>		(-)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'discount_div'+count+'\',\'discount\','+count+')" >'+product_labelarr.DISCOUNT+'</a> : </b><div class=\"discountUI\" id=\"discount_div'+count+'"><input type="hidden" id="discount_type'+count+'" name="discount_type'+count+'" value=""><table width="100%" border="0" cellpadding="5" cellspacing="0" class="small"><tr><td id="discount_div_title'+count+'" nowrap align="left" ></td><td align="right"><img src="themes/images/close.gif" border="0" onClick="fnHidePopDiv(\'discount_div'+count+'\')" style="cursor:pointer;"></td></tr><tr><td align="left" class="lineOnTop"><input type="radio" name="discount'+count+'" checked onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.ZERO_DISCOUNT+'</td><td class="lineOnTop">&nbsp;</td></tr><tr><td align="left"><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; % '+product_labelarr.PERCENT_OF_PRICE+' </td><td align="right"><input type="text" class="small" size="2" id="discount_percentage'+count+'" name="discount_percentage'+count+'" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp;%</td></tr><tr><td align="left" nowrap><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();">&nbsp; '+product_labelarr.DIRECT_PRICE_REDUCTION+'</td><td align="right"><input type="text" id="discount_amount'+count+'" name="discount_amount'+count+'" size="5" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+'); callTaxCalc('+count+');calcTotal();"></td></tr></table></div></td></tr><tr> <td align="right" style="padding:5px;" nowrap><b>'+product_labelarr.TOTAL_AFTER_DISCOUNT+' :</b></td></tr><tr id="individual_tax_row'+count+'" class="TaxShow"><td align="right" style="padding:5px;" nowrap>(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(this,\'tax_div'+count+'\',\'tax\','+count+')" >'+product_labelarr.TAX+' </a> : </b><div class="discountUI" id="tax_div'+count+'"></div></td></tr></table> ';


    //Total and Discount, Total after Discount and Tax details
    colsix.className = "crmTableRow small"
    colsix.innerHTML = '<table width="100%" cellpadding="5" cellspacing="0"><tr><td id="productTotal'+count+'" align="right">&nbsp;</td></tr><tr><td id="discountTotal'+count+'" align="right">0.00</td></tr><tr><td id="totalAfterDiscount'+count+'" align="right">&nbsp;</td></tr><tr><td id="taxTotal'+count+'" align="right">0.00</td></tr></table>';

    //Net Price
    colseven.className = "crmTableRow small";
    colseven.align = "right";
    colseven.style.verticalAlign = "bottom";
    colseven.innerHTML = '<span id="netPrice'+count+'"><b>&nbsp;</b></span>';

    //This is to show or hide the individual or group tax
    //decideTaxDiv();

    calcTotal();

    return count;
}

function decideTaxDiv()
{
    var taxtype = document.getElementById("taxtype").value

    calcTotal();

    if(taxtype == 'group')
    {
        //if group tax selected then we have to hide the individual taxes and also calculate the group tax
        hideIndividualTaxes()
        calcGroupTax();
    }
    else if(taxtype == 'individual')
        hideGroupTax()

}

function hideIndividualTaxes()
{
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length

    for(var i=1;i<=max_row_count;i++)
    {
        //document.getElementById("individual_tax_row"+i).className = 'TaxHide';
        document.getElementById("taxTotal"+i).style.display = 'none';
    }
    document.getElementById("group_tax_row").className = 'TaxShow';
}

function hideGroupTax()
{
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from table row length

    for(var i=1;i<=max_row_count;i++)
    {
        document.getElementById("individual_tax_row"+i).className = 'TaxShow';
        document.getElementById("taxTotal"+i).style.display = 'block';
    }
    document.getElementById("group_tax_row").className = 'TaxHide';
}

function setDiscount(currObj,curr_row)
{
    //console.log('setDiscount Inventory.js line 1121');
    var discount_checks = new Array();

    discount_checks = document.getElementsByName("discount"+curr_row);

    if(discount_checks[0]!=undefined && discount_checks[0].checked == true)
    {
        document.getElementById("discount_type"+curr_row).value = 'zero';
        document.getElementById("discount_percentage"+curr_row).style.visibility = 'hidden';
        document.getElementById("discount_amount"+curr_row).style.visibility = 'hidden';
        document.getElementById("discountTotal"+curr_row).innerHTML = '0.00';
    }
    if(discount_checks[1]!=undefined && discount_checks[1].checked == true)
    {
        document.getElementById("discount_type"+curr_row).value = 'percentage';
        document.getElementById("discount_percentage"+curr_row).style.visibility = 'visible';
        document.getElementById("discount_amount"+curr_row).style.visibility = 'hidden';

        var discount_amount = 0.00;
        //This is to calculate the final discount
        if(curr_row == '_final')
        {
            var discount_percentage_final_value = document.getElementById("discount_percentage"+curr_row).value;
            if(discount_percentage_final_value == '') discount_percentage_final_value = 0.00;
            discount_amount = eval(document.getElementById("netTotal").innerHTML)*eval(discount_percentage_final_value)/eval(100);
        }
        else//This is to calculate the product discount
        {
            var discount_percentage_value = document.getElementById("discount_percentage"+curr_row).value;
            if(discount_percentage_value == '') discount_percentage_value = 0.00;
            discount_amount = eval(document.getElementById("productTotal"+curr_row).innerHTML)*eval(discount_percentage_value)/eval(100);
        }
        //Rounded the decimal part of discount amount to two digits
        document.getElementById("discountTotal"+curr_row).innerHTML = roundValue(discount_amount.toString());
    }
    if(discount_checks[2]!=undefined && discount_checks[2].checked == true)
    {
        document.getElementById("discount_type"+curr_row).value = 'amount';
        document.getElementById("discount_percentage"+curr_row).style.visibility = 'hidden';
        document.getElementById("discount_amount"+curr_row).style.visibility = 'visible';
        //Rounded the decimal part of discount amount to two digits
        var discount_amount_value = document.getElementById("discount_amount"+curr_row).value.toString();
        if(discount_amount_value == '') discount_amount_value = 0.00;
        document.getElementById("discountTotal"+curr_row).innerHTML = roundValue(discount_amount_value);
    }
    // Update product total as discount would have changed.
    if(curr_row != '_final') {
        calcProductTotal(curr_row);
    }

}

//This function is added to call the tax calculation function
function callTaxCalc(curr_row)
{
    //when we change discount or list price, we have to calculate the taxes again before calculate the total
    if(getObj('tax_table'+curr_row))
    {
        tax_count = eval(document.getElementById('tax_table'+curr_row).rows.length-1);//subtract the title tr length
        for(var i=0, j=i+1;i<tax_count;i++,j++)
        {
            var tax_hidden_name = "hidden_tax"+j+"_percentage"+curr_row;
            var tax_name = document.getElementById(tax_hidden_name).value;
            calcCurrentTax(tax_name,curr_row,i);
        }
    }
}

function calcCurrentTax(tax_name, curr_row, tax_row)
{
    //we should calculate the tax amount only for the total After Discount
    var product_total = getObj("totalAfterDiscount"+curr_row).innerHTML
    //var product_total = document.getElementById("productTotal"+curr_row).innerHTML
    var new_tax_percent = document.getElementById(tax_name).value;

    var new_amount_lbl = document.getElementsByName("popup_tax_row"+curr_row);

    //calculate the new tax amount
    var new_tax_amount = eval(product_total)*eval(new_tax_percent)/eval(100);

    //Rounded the decimal part of tax amount to two digits
    new_tax_amount = roundValue(new_tax_amount.toString());

    //assign the new tax amount in the corresponding text box
    new_amount_lbl[tax_row].value = new_tax_amount;

    var tax_total = 0.00;
    for(var i=0;i<new_amount_lbl.length;i++)
    {
        tax_total = tax_total + eval(new_amount_lbl[i].value);
    }
    document.getElementById("taxTotal"+curr_row).innerHTML = roundValue(tax_total);

}

function calcGroupTax()
{
    var group_tax_count = document.getElementById("group_tax_count").value;

    var netTotal_value = document.getElementById("netTotal").innerHTML;
    if(netTotal_value == '') netTotal_value = 0;

    var discountTotal_final_value = document.getElementById("discountTotal_final").innerHTML;
    if(discountTotal_final_value == '') discountTotal_final_value = 0;

    var net_total_after_discount = eval(netTotal_value)-eval(discountTotal_final_value);
    var group_tax_total = 0.00, tax_amount=0.00;

    var quotestype = document.EditView.pricetype.value;//ประเภทของราคา
    //var cf_4352 = document.EditView.cf_4352.checked;//Discount Include VAT

    for(var i=1;i<=group_tax_count;i++)
    {
        var group_tax_percentage = document.getElementById("group_tax_percentage"+i).value;
        if(group_tax_percentage == '') group_tax_percentage = '0';

        tax_amount = eval(net_total_after_discount)*eval(group_tax_percentage)/eval(100);

        /*if(cf_4352 === true){
            if(quotestype=="Include Vat"){
                tax_amount = eval(net_total_after_discount)-((eval(net_total_after_discount)*eval(100))/(eval(100)+eval(group_tax_percentage)));
            }
        }*/
        document.getElementById("group_tax_amount"+i).value = tax_amount.toFixed(2);
        group_tax_total = eval(group_tax_total) + eval(tax_amount);
    }

    //document.getElementById("tax_final").innerHTML = roundValue(group_tax_total);
    //console.log("group_tax_total : "+group_tax_total);
    document.getElementById("tax_final").value = roundValue(group_tax_total.toFixed(2));

}

function calcSHTax()
{
    var sh_tax_count = document.getElementById("sh_tax_count").value;
    var sh_charge = document.getElementById("shipping_handling_charge").value;
    var sh_tax_total = 0.00, tax_amount=0.00;

    for(var i=1;i<=sh_tax_count;i++)
    {
        if(sh_charge == '') sh_charge = '0';
        var sh_tax_percentage = document.getElementById("sh_tax_percentage"+i).value;
        if(sh_tax_percentage == '') sh_tax_percentage = '0';
        tax_amount = eval(sh_charge)*eval(sh_tax_percentage)/eval(100);
        //Rounded the decimal part of S&H Tax amount to two digits
        document.getElementById("sh_tax_amount"+i).value = roundValue(tax_amount.toString());
        sh_tax_total = eval(sh_tax_total) + eval(tax_amount);
    }

    //Rounded the decimal part of Total S&H Tax amount to two digits
    document.getElementById("shipping_handling_tax").innerHTML = roundValue(sh_tax_total.toString());

    calcTotal();
}

function validateProductDiscounts()
{
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from table row length

    for(var i=1;i<=max_row_count;i++)
    {
        //if the row is deleted then avoid validate that row values
        if(document.getElementById("deleted"+i).value == 1)
            continue;

        discount_checks = document.getElementsByName("discount"+i);

        //Percentage selected, so validate the percentage
        if(discount_checks[1].checked == true)
        {
            temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("discount_percentage"+i).value);
            if(!temp)
            {
                alert(alert_arr.VALID_DISCOUNT_PERCENT);
                return false;
            }
        }
        if(discount_checks[2].checked == true)
        {
            temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("discount_amount"+i).value);
            if(!temp)
            {
                alert(alert_arr.VALID_DISCOUNT_AMOUNT);
                return false;
            }
        }
    }
    return true;
}

function stock_alert(curr_row)
{
    var stock=getObj("qtyInStock"+curr_row).innerHTML;
    var qty=getObj("qty"+curr_row).value;
    if (!isNaN(qty))
    {
        if(eval(qty) > eval(stock))
            getObj("stock_alert"+curr_row).innerHTML='<font color="red" size="1">'+alert_arr.STOCK_IS_NOT_ENOUGH+'</font>';
        else
            getObj("stock_alert"+curr_row).innerHTML='';
    }
    else
        getObj("stock_alert"+curr_row).innerHTML='<font color="red" size="1">'+alert_arr.INVALID_QTY+'</font>';
}

// Function to Get the price for all the products of an Inventory based on the Currency choosen by the User
function updatePrices() {

    var prev_cur = document.getElementById('prev_selected_currency_id');
    var inventory_currency = document.getElementById('inventory_currency');
    if(confirm(alert_arr.MSG_CHANGE_CURRENCY_REVISE_UNIT_PRICE)) {
        var productsListElem = document.getElementById('proTab');
        if (productsListElem == null) return;

        var max_row_count = productsListElem.rows.length;
        max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length

        var products_list = "";
        for(var i=1;i<=max_row_count;i++)
        {
            var productid = document.getElementById("hdnProductId"+i).value;
            if (i != 1)
                products_list = products_list + "::";
            products_list = products_list + productid;
        }

        if (prev_cur != null && inventory_currency != null)
            prev_cur.value = inventory_currency.value;

        var currency_id = inventory_currency.value;
        //Retrieve all the prices for all the products in currently selected currency
        new Ajax.Request(
            'index.php',
            {queue: {position: 'end', scope: 'command'},
                method: 'post',
                postBody: 'module=Products&action=ProductsAjax&file=InventoryPriceAjax&currencyid='+currency_id+'&productsList='+products_list,
                onComplete: function(response)
                {
                    if(trim(response.responseText).indexOf('SUCCESS') == 0) {
                        var res = trim(response.responseText).split("$");
                        updatePriceValues(res[1]);
                    } else {
                        alert(alert_arr.OPERATION_DENIED);
                    }
                }
            }
        );
    } else {
        if (prev_cur != null && inventory_currency != null)
            inventory_currency.value = prev_cur.value;
    }
}

// Function to Update the price for the products in the Inventory Edit View based on the Currency choosen by the User.
function updatePriceValues(pricesList) {

    if (pricesList == null || pricesList == '') return;
    var prices_list = pricesList.split("::");

    var productsListElem = document.getElementById('proTab');
    if (productsListElem == null) return;

    var max_row_count = productsListElem.rows.length;
    max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length

    var products_list = "";
    for(var i=1;i<=max_row_count;i++)
    {
        var list_price_elem = document.getElementById("listPrice"+i);
        var unit_price = prices_list[i-1]; // Price values index starts from 0
        list_price_elem.value = unit_price;

        // Set Direct Discout amount to 0
        var discount_amount = document.getElementById("discount_amount"+i);
        if(discount_amount != null) discount_amount.value = '0';

        calcProductTotal(i);
        setDiscount(list_price_elem,i);
        callTaxCalc(i);
    }
    resetSHandAdjValues();
    calcTotal();
}

// Function to Reset the S&H Charges and Adjustment value with change in Currency
function resetSHandAdjValues() {
    var sh_amount = document.getElementById('shipping_handling_charge');
    if (sh_amount != null) sh_amount.value = '0';

    var sh_amount_tax = document.getElementById('shipping_handling_tax');
    if (sh_amount_tax != null) sh_amount_tax.innerHTML = '0';

    var adjustment = document.getElementById('adjustment');
    if (adjustment != null) adjustment.value = '0';

    var final_discount = document.getElementById('discount_amount_final');
    if (final_discount != null) final_discount.value = '0';
}
// End

/** Function for Product Re-Ordering Feature Code Addition Starts
 * It will be responsible for moving record up/down, 1 step at a time
 */
function moveUpDown(sType,oModule,iIndex)
{
    var aFieldIds = Array('hidtax_row_no','productCodeView','productcode','productName','subproduct_ids','hdnProductId','comment','qty','listPrice','discount_type','discount_percentage','discount_amount','tax1_percentage','hidden_tax1_percentage','popup_tax_row','tax2_percentage','hidden_tax2_percentage','lineItemType','lineItemType','pack_size','test_box','uom','listprice_exc','listprice_inc');
    var aContentIds = Array('qtyInStock','netPrice','subprod_names','productCodeView','business_code');
    var aOnClickHandlerIds = Array('searchIcon');

    var num = iIndex;

    iIndex = eval(iIndex) + 1;
    var oTable = document.getElementById('proTab');
    iMax = oTable.rows.length;
    iSwapIndex = 1;
    if(sType == 'UP')
    {
        for(iCount=iIndex-2;iCount>=1;iCount--)
        {
            if(document.getElementById("row"+iCount))
            {
                if(document.getElementById("row"+iCount).style.display != 'none' && document.getElementById('deleted'+iCount).value == 0)
                {
                    iSwapIndex = iCount+1;
                    /*var hdnProductId = document.getElementById("hdnProductId"+num).value;
                     if(hdnProductId == '' || hdnProductId == 0){
                     document.getElementById("productName"+iCount).readOnly = true;
                     document.getElementById("productName"+num).readOnly = false;
                     }else{
                     document.getElementById("productName"+iCount).readOnly = false;
                     document.getElementById("productName"+num).readOnly = true;
                     }*/
                    break;
                }
            }
        }
    }
    else
    {
        for(iCount=iIndex;iCount<=iMax-2;iCount++)
        {
            if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none' && document.getElementById('deleted'+iCount).value == 0)
            {
                iSwapIndex = iCount;
                /*var hdnProductId = document.getElementById("hdnProductId"+num).value;
                 if(hdnProductId == '' || hdnProductId == 0){
                 document.getElementById("productName"+iCount).readOnly = true;
                 document.getElementById("productName"+num).readOnly = true;
                 }else{
                 document.getElementById("productName"+iCount).readOnly = true;
                 document.getElementById("productName"+num).readOnly = true;
                 }*/
                break;
            }
        }
        iSwapIndex += 1;
    }



    var oCurTr = oTable.rows[iIndex];
    var oSwapRow = oTable.rows[iSwapIndex];

    iMaxCols = oCurTr.cells.length;
    iIndex -= 1;
    iSwapIndex -= 1;

    iCheckIndex = 0;
    iSwapCheckIndex = 0;
    /*for(j=0;j<=2;j++)
     {
     if(eval('document.getElementById(\'frmEditView\').discount'+iIndex+'['+j+']'))
     {
     sFormElement = eval('document.getElementById(\'frmEditView\').discount'+iIndex+'['+j+']');
     if(sFormElement.checked)
     {
     iCheckIndex = j;
     break;
     }
     }
     }

     for(j=0;j<=2;j++)
     {
     if(eval('document.getElementById(\'frmEditView\').discount'+iSwapIndex+'['+j+']'))
     {
     sFormElement = eval('document.getElementById(\'frmEditView\').discount'+iSwapIndex+'['+j+']');
     if(sFormElement.checked)
     {
     iSwapCheckIndex = j;
     break;
     }
     }
     }
     if(eval('document.getElementById(\'frmEditView\').discount'+iIndex+'['+iSwapCheckIndex+']'))
     {
     oElement = eval('document.getElementById(\'frmEditView\').discount'+iIndex+'['+iSwapCheckIndex+']');
     oElement.checked = true;
     }
     if(eval('document.getElementById(\'frmEditView\').discount'+iSwapIndex+'['+iCheckIndex+']'))
     {
     oSwapElement = eval('document.getElementById(\'frmEditView\').discount'+iSwapIndex+'['+iCheckIndex+']');
     oSwapElement.checked = true;
     }*/

    iMaxElement = aFieldIds.length;
    for(iCt=0;iCt<iMaxElement;iCt++)
    {
        sId = aFieldIds[iCt] + iIndex;
        sSwapId = aFieldIds[iCt] + iSwapIndex;
        if(document.getElementById(sId) && document.getElementById(sSwapId))
        {
            sTemp = document.getElementById(sId).value;
            document.getElementById(sId).value = document.getElementById(sSwapId).value;
            document.getElementById(sSwapId).value = sTemp;
        }
        //oCurTr.cells[iCt].innerHTML;
    }
    iMaxElement = aContentIds.length;
    for(iCt=0;iCt<iMaxElement;iCt++)
    {
        sId = aContentIds[iCt] + iIndex;
        sSwapId = aContentIds[iCt] + iSwapIndex;
        if(document.getElementById(sId) && document.getElementById(sSwapId))
        {
            sTemp = document.getElementById(sId).innerHTML;
            document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
            document.getElementById(sSwapId).innerHTML = sTemp;
        }
    }
    iMaxElement = aOnClickHandlerIds.length;
    for(iCt=0;iCt<iMaxElement;iCt++)
    {
        sId = aOnClickHandlerIds[iCt] + iIndex;
        sSwapId = aOnClickHandlerIds[iCt] + iSwapIndex;
        if(document.getElementById(sId) && document.getElementById(sSwapId))
        {
            sTemp = document.getElementById(sId).onclick;
            document.getElementById(sId).onclick = document.getElementById(sSwapId).onclick;
            document.getElementById(sSwapId).onclick = sTemp;

            sTemp = document.getElementById(sId).src;
            document.getElementById(sId).src = document.getElementById(sSwapId).src;
            document.getElementById(sSwapId).src = sTemp;

            sTemp = document.getElementById(sId).title;
            document.getElementById(sId).title = document.getElementById(sSwapId).title;
            document.getElementById(sSwapId).title = sTemp;
        }
    }
    //FindDuplicate();
    /*settotalnoofrows();
     calcTotal();

     loadTaxes_Ajax(iIndex);
     loadTaxes_Ajax(iSwapIndex);
     callTaxCalc(iIndex);
     callTaxCalc(iSwapIndex);
     setDiscount(this,iIndex);
     setDiscount(this,iSwapIndex); */
    sId = 'tax1_percentage' + iIndex;
    sTaxRowId = 'hidtax_row_no' + iIndex;
    if(document.getElementById(sTaxRowId))
    {
        if(!(iTaxVal = document.getElementById(sTaxRowId).value))
            iTaxVal = 0;
        //calcCurrentTax(sId,iIndex,iTaxVal);
    }

    sSwapId = 'tax1_percentage' + iSwapIndex;
    sSwapTaxRowId = 'hidtax_row_no' + iSwapIndex;
    if(document.getElementById(sSwapTaxRowId))
    {
        if(!(iSwapTaxVal = document.getElementById(sSwapTaxRowId).value))
            iSwapTaxVal = 0;
        //calcCurrentTax(sSwapId,iSwapIndex,iSwapTaxVal);
    }
    calcTotal();

    var row_count = iMax-2;
    for(num=1;num<=row_count; num++){
        var hdnProductId = document.getElementById("hdnProductId"+num).value;
        if(hdnProductId == '' && hdnProductId == 0){
            document.getElementById("productName"+num).readOnly = true;
        }else if(hdnProductId != '' && hdnProductId != 0){
            document.getElementById("productName"+num).readOnly = false;
        }
    }

}

function InventorySelectAll(mod, image_pth) {
    if (document.selectall.selected_id != undefined) {
        var x = document.selectall.selected_id.length;
        var y = 0;
        idstring = "";
        namestr = "";
        var action_str = "";
        if (x == undefined) {
            if (document.selectall.selected_id.checked) {
                idstring = document.selectall.selected_id.value;
                c = document.selectall.selected_id.value;
                var prod_array = JSON.parse($('popup_product_' + c).attributes['vt_prod_arr'].nodeValue);
                var prod_id = prod_array['entityid'];
                var prod_name = prod_array['prodname'];
                var unit_price = prod_array['unitprice'];
                var taxstring = prod_array['taxstring'];
                var desc = prod_array['desc'];
                var row_id = prod_array['rowid'];
                var subprod_ids = prod_array['subprod_ids'];
                if (mod != 'PurchaseOrder') {
                    var qtyinstk = prod_array['qtyinstk'];
                    set_return_inventory(prod_id, prod_name, unit_price, qtyinstk, taxstring, parseInt(row_id), desc, subprod_ids);
                } else {
                    set_return_inventory_po(prod_id, prod_name, unit_price, taxstring, parseInt(row_id), desc, subprod_ids);
                }
                y = 1;
            } else {
                alert(alert_arr.SELECT);
                return false;
            }
        } else {
            y = 0;
            for (i = 0; i < x; i++) {
                if (document.selectall.selected_id[i].checked) {
                    idstring = document.selectall.selected_id[i].value + ";" + idstring;
                    c = document.selectall.selected_id[i].value;
                    var prod_array = JSON.parse($('popup_product_' + c).attributes['vt_prod_arr'].nodeValue);
                    var prod_id = prod_array['entityid'];
                    var prod_name = prod_array['prodname'];
                    var prod_code = prod_array['prod_code'];
                    var unit_price = prod_array['unitprice'];
                    var taxstring = prod_array['taxstring'];
                    var desc = prod_array['desc'];
                    var subprod_ids = prod_array['subprod_ids'];
                    var uom = prod_array['uom'];
                    var listprice_inc = prod_array['listprice_inc'];
                    var return_module = prod_array['return_module'];

                    if (y > 0) {
                        if (return_module == 'Quotes') {
                            var row_id = window.opener.fnAddProductRowInventory(mod, image_pth);
                        } else {
                            var row_id = window.opener.fnAddProductRow(mod, image_pth);
                        }
                    } else {
                        var row_id = prod_array['rowid'];
                    }

                    if (mod != 'PurchaseOrder') {
                        var qtyinstk = prod_array['qtyinstk'];
                        //set_return_inventory(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids);
                        set_return_inventory(prod_id, prod_code, prod_name, unit_price, qtyinstk, taxstring, parseInt(row_id), desc, subprod_ids, uom, '', '', '', return_module, '', '', '', listprice_inc);
                    } else {
                        set_return_inventory_po(prod_id, prod_name, unit_price, taxstring, parseInt(row_id), desc, subprod_ids);
                    }
                    y = y + 1;
                }
            }
        }
        if (y != 0) {
            document.selectall.idlist.value = idstring;
            return true;
        } else {
            alert(alert_arr.SELECT);
            return false;
        }
    }
}
