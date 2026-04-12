/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
jQuery(document).ready(function() {
	
	jQuery("#proTab").on("input",".qty_act",function() {
		var rowno = jQuery(this).data("rowno")	;
		  calqty_remain(rowno);
		  cal_value(rowno);
	});
	
	jQuery("#proTab").on("input",".qty_ship",function() {
		var rowno = jQuery(this).data("rowno")	;
		  calqty_remain(rowno);

	});

	jQuery("#proTab").on("input",".listPrice",function() {
		var rowno = jQuery(this).data("rowno")	;
		cal_value(rowno);

	});
});

function cal_value(rowno)
{
	
	var qty_act = jQuery('#qty_act'+rowno).val();
	var price = jQuery('#listPrice'+rowno).val();

	var listprice_total = 0;
	qty_act = isNaN(qty_act = parseInt(qty_act)) ? 0:parseInt(qty_act);
	price = isNaN(price = parseFloat(price)) ? 0:parseFloat(price);
	listprice_total = qty_act * price;
	//alert (listprice_total);
	if(listprice_total<0){
		listprice_total = 0
	}
	jQuery('#listprice_total'+rowno).val(parseFloat(listprice_total.toFixed(2)));
}



function calqty_remain(rowno)
{
	var qty_act = jQuery('#qty_act'+rowno).val();
	var qty_ship = jQuery('#qty_ship'+rowno).val();
	var qty_remain = 0;
	qty_act = isNaN(qty_act = parseInt(qty_act)) ? 0:parseInt(qty_act);
	qty_ship = isNaN(qty_ship = parseInt(qty_ship)) ? 0:parseInt(qty_ship);
	qty_remain = qty_act - qty_ship;
	if(qty_remain<0){
		qty_remain = 0
	}
	jQuery('#qty_remain'+rowno).val(qty_remain);
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
		if(typeof(form.cf_629) != 'undefined' && typeof(form.cf_630) != 'undefined')
			form.cf_630.value = form.cf_629.value;	
		if(typeof(form.cf_631) != 'undefined' && typeof(form.cf_632) != 'undefined')
			form.cf_632.value = form.cf_631.value;		
		if(typeof(form.cf_633) != 'undefined' && typeof(form.cf_634) != 'undefined')
			form.cf_634.value = form.cf_633.value;	
			
		//ncpc	
		if(typeof(form.cf_1359) != 'undefined' && typeof(form.cf_1590) != 'undefined')
			form.cf_1359.value = form.cf_1590.value;	
		if(typeof(form.cf_1582) != 'undefined' && typeof(form.cf_1595) != 'undefined')
			form.cf_1582.value = form.cf_1595.value;
		if(typeof(form.cf_1682) != 'undefined' && typeof(form.cf_1683) != 'undefined')
			form.cf_1682.value = form.cf_1683.value;	
		if(typeof(form.cf_1336) != 'undefined' && typeof(form.cf_1596) != 'undefined')
			form.cf_1336.value = form.cf_1596.value;
		if(typeof(form.cf_1684) != 'undefined' && typeof(form.cf_1685) != 'undefined')
			form.cf_1684.value = form.cf_1685.value;	
		if(typeof(form.cf_1555) != 'undefined' && typeof(form.cf_1594) != 'undefined')
			form.cf_1555.value = form.cf_1594.value;
		if(typeof(form.cf_1348) != 'undefined' && typeof(form.cf_1591) != 'undefined')
			form.cf_1348.value = form.cf_1591.value;	
		if(typeof(form.cf_1554) != 'undefined' && typeof(form.cf_1593) != 'undefined')
			form.cf_1554.value = form.cf_1593.value;
		if(typeof(form.cf_1686) != 'undefined' && typeof(form.cf_1687) != 'undefined')
			form.cf_1686.value = form.cf_1687.value;	
		if(typeof(form.bill_code) != 'undefined' && typeof(form.cf_1602) != 'undefined')
			form.bill_code.value = form.cf_1602.value;
		if(typeof(form.cf_1333) != 'undefined' && typeof(form.cf_1592) != 'undefined')
			form.cf_1333.value = form.cf_1592.value;			
	}else if (module=='Quotes'){
		
		if(typeof(form.cf_613) != 'undefined' && typeof(form.cf_614) != 'undefined')
			form.cf_614.value = form.cf_613.value;			
	}else if (module=='SalesOrder'){
		if(typeof(form.cf_615) != 'undefined' && typeof(form.cf_616) != 'undefined')
			form.cf_616.value = form.cf_615.value;
	}else if (module=='Contacts'){
		if(typeof(form.cf_603) != 'undefined' && typeof(form.cf_604) != 'undefined')
			form.cf_604.value = form.cf_603.value;
	}
	return true;

}

function copyAddressLeft(form,module) {//alert(module);
	//bill go to ship
	if(typeof(form.bill_street) != 'undefined' && typeof(form.ship_street) != 'undefined')
		form.bill_street.value = form.ship_street.value;
	
	if(typeof(form.bill_city) != 'undefined' && typeof(form.ship_city) != 'undefined')
		form.bill_city.value = form.ship_city.value;

	if(typeof(form.bill_state) != 'undefined' && typeof(form.ship_state) != 'undefined')
		form.bill_state.value = form.ship_state.value;

	if(typeof(form.bill_code) != 'undefined' && typeof(form.ship_code) != 'undefined')
		form.bill_code.value =	form.ship_code.value;

	if(typeof(form.bill_country) != 'undefined' && typeof(form.ship_country) != 'undefined')
		form.bill_country.value = form.ship_country.value;

	if(typeof(form.bill_pobox) != 'undefined' && typeof(form.ship_pobox) != 'undefined')
		form.bill_pobox.value = form.ship_pobox.value;
	if(module=='Accounts')	{	
		if(typeof(form.cf_629) != 'undefined' && typeof(form.cf_630) != 'undefined')
			form.cf_629.value = form.cf_630.value;	
		if(typeof(form.cf_631) != 'undefined' && typeof(form.cf_632) != 'undefined')
			form.cf_631.value = form.cf_632.value;	
		if(typeof(form.cf_633) != 'undefined' && typeof(form.cf_634) != 'undefined')
			form.cf_633.value = form.cf_634.value;	
		//ncpc	
		if(typeof(form.cf_1359) != 'undefined' && typeof(form.cf_1590) != 'undefined')
			form.cf_1590.value = form.cf_1359.value;	
		if(typeof(form.cf_1582) != 'undefined' && typeof(form.cf_1595) != 'undefined')
			form.cf_1595.value = form.cf_1582.value;
		if(typeof(form.cf_1682) != 'undefined' && typeof(form.cf_1683) != 'undefined')
			form.cf_1683.value = form.cf_1682.value;	
		if(typeof(form.cf_1336) != 'undefined' && typeof(form.cf_1596) != 'undefined')
			form.cf_1596.value = form.cf_1336.value;
		if(typeof(form.cf_1684) != 'undefined' && typeof(form.cf_1685) != 'undefined')
			form.cf_1685.value = form.cf_1684.value;	
		if(typeof(form.cf_1555) != 'undefined' && typeof(form.cf_1594) != 'undefined')
			form.cf_1594.value = form.cf_1555.value;
		if(typeof(form.cf_1348) != 'undefined' && typeof(form.cf_1591) != 'undefined')
			form.cf_1591.value = form.cf_1348.value;	
		if(typeof(form.cf_1554) != 'undefined' && typeof(form.cf_1593) != 'undefined')
			form.cf_1593.value = form.cf_1554.value;
		if(typeof(form.cf_1686) != 'undefined' && typeof(form.cf_1687) != 'undefined')
			form.cf_1687.value = form.cf_1686.value;	
		if(typeof(form.bill_code) != 'undefined' && typeof(form.cf_1602) != 'undefined')
			form.cf_1602.value = form.bill_code.value;
		if(typeof(form.cf_1333) != 'undefined' && typeof(form.cf_1592) != 'undefined')
			form.cf_1592.value = form.cf_1333.value;	
	}else if (module=='Quotes'){
		
		if(typeof(form.cf_613) != 'undefined' && typeof(form.cf_614) != 'undefined')
			form.cf_613.value = form.cf_614.value;				
	}else if (module=='SalesOrder'){
		if(typeof(form.cf_615) != 'undefined' && typeof(form.cf_616) != 'undefined')
			form.cf_615.value = form.cf_616.value;
	}else if (module=='Contacts'){
		if(typeof(form.cf_603) != 'undefined' && typeof(form.cf_604) != 'undefined')
			form.cf_603.value = form.cf_604.value;
	}
	return true;

}

function settotalnoofrows() {
	var max_row_count = document.getElementById('proTab').rows.length;
        max_row_count = eval(max_row_count)-2;
		console.log(max_row_count);

	//set the total number of products
	document.EditView.totalProductCount.value = max_row_count;	
}

function projectPickList(currObj,module, row_no) {
    var trObj=currObj.parentNode.parentNode
    
    var rowId = row_no;
    var currentRowId = parseInt(currObj.id.match(/([0-9]+)$/)[1]);
    
    // If we have mismatching rowId and currentRowId, it is due swapping of rows
    if(rowId != currentRowId) {
        rowId = currentRowId;
    }
    
    var currencyid = document.getElementById("inventory_currency").value;
    popuptype = 'inventory_prod';
    
    window.open("index.php?module=Projects&action=Popup&html=Popup_picker&select=enable&form=ProjectsEditView&popuptype="+popuptype+"&curr_row="+rowId+"&return_module="+module+"&currencyid="+currencyid,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
}

function poItems(currObj,module, row_no) {
    var trObj=currObj.parentNode.parentNode
    
    var rowId = row_no;
    var currentRowId = parseInt(currObj.id.match(/([0-9]+)$/)[1]);
    
    // If we have mismatching rowId and currentRowId, it is due swapping of rows
    if(rowId != currentRowId) {
        rowId = currentRowId;
    }
    
    var purchasesorderid = document.getElementById("hdnPoId"+rowId).value;
	popuptype = 'inventory_po_itemlist';
	// alert(purchasesorderid);
	if(purchasesorderid == ''){
		alert("โปรดระบุข้อมูลใบสั่งซื้อ");
		document.getElementById("purchasesorder_no"+rowId).focus();
	}else{
 		
		popup(purchasesorderid,rowId);
		 
		
	}
}

var popupPODialog = jQuery('<div />',{ id:'popup-po-itemlist-dialogs' });
function popup(purchasesorderid,rowId){
	
	//  var popupSerial = function(uuid)
	//  {
		 jQuery.post('po_popup_itemlist.php', {purchasesorderid:purchasesorderid,rowId:rowId}, function(html){
			 jQuery(popupPODialog).window({
				 title: 'Purchases Order',
				 width:1000,
				 height:600,
				 modal:true,
				 maximizable: false,
				 minimizable: false,
				 onClose: function(){
						 //window.location.reload()
					 }
				 }).html(html);
			 jQuery(popupPODialog).window('moveTo', {position:'center', element:'#po_name_'+rowId})
		 })
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
	popuptype = 'inventory_prod';
	if(module == 'Goodsreceive')
		popuptype = 'inventory_po';
	var record_id = '';
    if(document.getElementsByName("account_id").length != 0)
    	record_id= document.EditView.account_id.value;
    if(record_id != '')
    	window.open("index.php?module=Purchasesorder&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&relmod_id="+record_id+"&parent_module=Accounts&return_module="+module+"&currencyid="+currencyid,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
    else
		window.open("index.php?module=Purchasesorder&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&return_module="+module+"&currencyid="+currencyid,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
}

function priceBookPickList(currObj, row_no) {
	var trObj=currObj.parentNode.parentNode
	var rowId=row_no;//parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))
	var currencyid = document.getElementById("inventory_currency").value;
	var productId=getObj("hdnPoId"+rowId).value || -1;
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

function calcTotalamountsample() {
    var netTotal = 0.0, grandTotal = 0.0;
    var discountTotal_final = 0.0, finalTax = 0.0, sh_amount = 0.0, sh_tax = 0.0, adjustment = 0.0 ,afterdis_final=0.0;

    var taxtype = document.getElementById("taxtype").value; // tax type default => group
    //var quotestype = document.EditView.pricetype.value; // vat type Exclude/Include
  	
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;// Because the table has two header rows. so we will reduce two from row length

    for (var i=1;i<=max_row_count;i++)
    {
        if(document.getElementById('deleted'+i).value == 0)
        {

            if (document.getElementById("amount_of_sample"+i).value=="")
                document.getElementById("amount_of_sample"+i).value = 0.0
            if (!isNaN(document.getElementById("amount_of_sample"+i).value))
                netTotal += parseFloat(document.getElementById("amount_of_sample"+i).value)
        }
    }

    document.getElementById("total_amount_of_sample").value = netTotal.toFixed(2);   
}

function calcTotalamount() {
    var netTotal = 0.0, grandTotal = 0.0;
    var discountTotal_final = 0.0, finalTax = 0.0, sh_amount = 0.0, sh_tax = 0.0, adjustment = 0.0 ,afterdis_final=0.0;

    var taxtype = document.getElementById("taxtype").value; // tax type default => group
    //var quotestype = document.EditView.pricetype.value; // vat type Exclude/Include
  	
    var max_row_count = document.getElementById('proTab').rows.length;
    max_row_count = eval(max_row_count)-2;// Because the table has two header rows. so we will reduce two from row length

    for (var i=1;i<=max_row_count;i++)
    {
        if(document.getElementById('deleted'+i).value == 0)
        {

            if (document.getElementById("amount_of_purchase"+i).value=="")
                document.getElementById("amount_of_purchase"+i).value = 0.0
            if (!isNaN(document.getElementById("amount_of_purchase"+i).value))
                netTotal += parseFloat(document.getElementById("amount_of_purchase"+i).value)
        }
    }

    document.getElementById("total_amount_of_purchase").value = netTotal.toFixed(2);   
}

function deleteRow(module,i,image_path)
{
	rowCnt--;
	var tableName = document.getElementById('proTab');
	var prev = tableName.rows.length;
	document.getElementById("row"+i).style.display = 'none';
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
	document.getElementById("hdnPoId"+i).value = "";
	document.getElementById("po_display"+i).value = "1";
	//document.getElementById("poName"+i).value = "";
	document.getElementById('deleted'+i).value = 1;

	calcTotal()
	settotalnoofrows()
}
/*  End */

// Function to Calcuate the Inventory total including all products
function calcTotal() {

	var max_row_count = document.getElementById('proTab').rows.length;
	max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length
	var netprice = 0.00;
	for(var i=1;i<=max_row_count;i++)
	{
		rowId = i;
		// calcProductTotal(rowId);
	}
	// calcGrandTotal();
}

function calAmount(rowId){
	var gr_quantity = parseFloat(jQuery("#gr_quantity" + rowId).val());
	var defects_quantity = parseFloat(jQuery("#defects_quantity" + rowId).val());
	var unit_price = parseFloat(jQuery("#unit_price" + rowId).val());
	
	var sum_quantity = checkNumber(gr_quantity)+checkNumber(defects_quantity);
	// console.log(sum_quantity);
	var total=eval(sum_quantity*checkNumber(unit_price));
	// console.log(total);
	getObj("amount"+rowId).value=roundValue(total.toString())
	calTotalAmount(rowId);
}

function calRemainQuantity(rowId){
	var po_quantity = parseFloat(jQuery("#po_quantity" + rowId).val()); //จำนวนที่สั่งซื้อใน P/O
	var total_defects_quantity = parseFloat(jQuery("#total_defects_quantity" + rowId).val()); // จำนวนสินค้า Defects ที่รับเข้าทั้งหมด
	var total_gr_quantity = parseFloat(jQuery("#total_gr_quantity" + rowId).val()); //จำนวนสินค้า GR ที่รับเข้าทั้งหมด
	// var remain_quantity = parseFloat(jQuery("#hdnRemain_quantity" + rowId).val()); //จำนวนที่เหลือ
	
	var total=eval(checkNumber(po_quantity)-(checkNumber(total_defects_quantity)+checkNumber(total_gr_quantity)));
	console.log(checkNumber(po_quantity)+"- ("+checkNumber(total_defects_quantity)+"+"+checkNumber(total_gr_quantity)+")");
	getObj("remain_quantity"+rowId).value=roundValue(total.toString())
	calTotalAmount(rowId);
}

function calTotalDefects(rowId){
	var hdnTotal_defects_quantity = parseFloat(jQuery("#hdnTotal_defects_quantity" + rowId).val());
	var defects_quantity = parseFloat(jQuery("#defects_quantity" + rowId).val());
	var total=eval(checkNumber(hdnTotal_defects_quantity)+checkNumber(defects_quantity));
	getObj("total_defects_quantity"+rowId).value=roundValue(total.toString())
	calTotalAmount(rowId);
	calRemainQuantity(rowId);
}

function calTotalGR_quantity(rowId){
	var hdnTotal_gr_quantity = parseFloat(jQuery("#hdnTotal_gr_quantity" + rowId).val());
	var gr_quantity = parseFloat(jQuery("#gr_quantity" + rowId).val());
	var total=eval(checkNumber(hdnTotal_gr_quantity)+checkNumber(gr_quantity));
	getObj("total_gr_quantity"+rowId).value=roundValue(total.toString())
	calTotalAmount(rowId);
	calRemainQuantity(rowId);
}

function checkNumber(val) {
    if (isNaN(val)) {
		return 0;
	} else {
		return val;
	}
}

function calTotalAmount(rowId){
	var total_defects_quantity = parseFloat(jQuery("#total_defects_quantity" + rowId).val()); //จำนวนสินค้า Defects ที่รับเข้าทั้งหมด
	var total_gr_quantity = parseFloat(jQuery("#total_gr_quantity" + rowId).val()); //จำนวนสินค้า GR ที่รับเข้าทั้งหมด
	var unit_price = parseFloat(jQuery("#unit_price" + rowId).val());
	var total=eval((checkNumber(total_defects_quantity)+checkNumber(total_gr_quantity))*checkNumber(unit_price));
	getObj("total_amount"+rowId).value=roundValue(total.toString())

	//คำนวณ gr_qty_percent (GR Qty.%)
	var po_quantity = parseFloat(jQuery("#po_quantity" + rowId).val());
	var totalQty=eval(((checkNumber(total_defects_quantity)+checkNumber(total_gr_quantity))/checkNumber(po_quantity))*100);
	getObj("gr_qty_percent"+rowId).value=roundValue(totalQty.toString())
}
// Function to Calculate the Total for a particular product in an Inventory
function calcProductTotal(rowId) {	
		
		if(document.getElementById('deleted'+rowId) && document.getElementById('deleted'+rowId).value == 0)
		{
			
			var total=eval(getObj("qty"+rowId).value*getObj("listPrice"+rowId).value);
			getObj("productTotal"+rowId).innerHTML=roundValue(total.toString())

			var totalAfterDiscount = eval(checkNumber(total)-checkNumber(document.getElementById("discountTotal"+rowId).innerHTML));
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
	var discountTotal_final = 0.0, finalTax = 0.0, sh_amount = 0.0, sh_tax = 0.0, adjustment = 0.0;

	var taxtype = document.getElementById("taxtype").value;

	var max_row_count = document.getElementById('proTab').rows.length;
	max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length

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
//	alert(netTotal);
	document.getElementById("netTotal").innerHTML = netTotal;
	document.getElementById("subtotal").value = netTotal;
	setDiscount(this,'_final');
	calcGroupTax();
	//Tax and Adjustment values will be taken when they are valid integer or decimal values
	//if(/^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("txtTax").value))
	//	txtTaxVal = parseFloat(getObj("txtTax").value);	
	//if(/^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("txtAdjustment").value))
	//	txtAdjVal = parseFloat(getObj("txtAdjustment").value);

	discountTotal_final = document.getElementById("discountTotal_final").innerHTML

	//get the final tax based on the group or individual tax selection
	var taxtype = document.getElementById("taxtype").value;
	if(taxtype == 'group')
		finalTax = document.getElementById("tax_final").innerHTML

	sh_amount = getObj("shipping_handling_charge").value
	sh_tax = document.getElementById("shipping_handling_tax").innerHTML

	adjustment = getObj("adjustment").value

	//Add or substract the adjustment based on selection
	adj_type = document.getElementById("adjustmentType").value;
	
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
s
//This function is used to validate the Inventory modules 
function validateInventory(module) 
{	
	if(!formValidate())
		return false

	//for products, vendors and pricebook modules we won't validate the product details. here return the control
	if(module == 'Products' || module == 'Vendors' || module == 'PriceBooks' || module == 'Services')
	{
		return true;
	}

	if(module == 'Goodsreceive'){
		
		var flag_purchasesorder = 0;
		var po_display = '';
		jQuery('[id^="purchasesorder_no"]').each(function() {
			var id = jQuery(this).attr('id');
			purchasesorder = jQuery(`#${id}`).val();
			var row_no = id.replace('purchasesorder_no', '');
			po_display =jQuery(`#po_display${row_no}`).val();
			
			var product_code_crm =jQuery(`#product_code_crm${row_no}`).val();
			
			if((purchasesorder == '' || purchasesorder == 0) && po_display != 1){
				flag_purchasesorder = 1;
				alert('Purchase Order cannot be empty');
				document.getElementById(id).focus();
				flag_purchasesorder = 1;
				return false;

			}else{
				
				if(product_code_crm == '' && po_display != 1){
					alert('Line item cannot be empty');
					document.getElementById(`product_code_crm${row_no}`).focus();
					flag_purchasesorder = 1;
					return false;
				}
			}
		});

		if(flag_purchasesorder == 1){
			return false;
		}

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

		// if (!emptyCheck("poName"+i,alert_arr.LINE_ITEM,"text")) return false
		
		
		
		if(module == 'Projects'){

			if (!emptyCheck("qty"+i,"จำนวนประมาณการ","text")) return false
			if (!numValidate("qty"+i,"จำนวนประมาณการ","any")) return false
			if (!numConstComp("qty"+i,"จำนวนประมาณการ","G","0")) return false
			if (!emptyCheck("qty_act"+i,"จำนวนจริง","text")) return false
			if (!numValidate("qty_act"+i,"จำนวนจริง","any")) return false
			if (!numConstComp("qty_act"+i,"จำนวนจริง","G","0")) return false
			if (!emptyCheck("listPrice"+i,alert_arr.LIST_PRICE,"text")) return false
			if (!numValidate("listPrice"+i,alert_arr.LIST_PRICE,"any")) return false   
		/*	if (!emptyCheck("listPrice"+i,alert_arr.LIST_PRICE,"text")) return false
			if (!numValidate("listPrice"+i,alert_arr.LIST_PRICE,"any")) return false   */
		}else{
			if (!emptyCheck("qty"+i,"Qty","text")) return false
			if (!numValidate("qty"+i,"Qty","any")) return false
			if (!numConstComp("qty"+i,"Qty","G","0")) return false
			if (!emptyCheck("listPrice"+i,alert_arr.LIST_PRICE,"text")) return false
			if (!numValidate("listPrice"+i,alert_arr.LIST_PRICE,"any")) return false   
		}
		
		        
	}



	//Product - Discount validation - not allow negative values
	/*if(!validateProductDiscounts())
		return false;*/

	//Final Discount validation - not allow negative values
	/*discount_checks = document.getElementsByName("discount_final");

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
	}*/

	//Shipping & Handling validation - not allow negative values
	/*temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("shipping_handling_charge").value);
	if(!temp)
	{
		alert(alert_arr.VALID_SHIPPING_CHARGE);
		return false;
	}*/

	//Adjustment validation - allow negative values
	/*temp = /^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("adjustment").value)
	if(!temp)
	{
		alert(alert_arr.VALID_ADJUSTMENT);
		return false;
	}*/
	
	//Group - Tax Validation  - not allow negative values
	//We need to validate group tax only if taxtype is group.
	/*var taxtype=document.getElementById("taxtype").value;
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
	}*/
	
	//Taxes for Shippring and Handling  validation - not allow negative values
		/*var shtax_count=document.getElementById("sh_tax_count").value;
		for(var i=1;i<=shtax_count;i++)
		{

			temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("sh_tax_percentage"+i).value);
			if(!temp)
			{
				alert(alert_arr.VALID_SH_TAX);
				return false;
			}
		}
	calcTotal();*/ /* Product Re-Ordering Feature Code Addition */
	
	
	return true;    
}

function FindDuplicate()
{
	var max_row_count = document.getElementById('proTab').rows.length;
        max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from row length

	var duplicate = false, iposition = '', positions = '', duplicate_products = '';

	var product_id = new Array(max_row_count-1);
	var product_name = new Array(max_row_count-1);
	product_id[1] = getObj("hdnPoId"+1).value;
	product_name[1] = getObj("poName"+1).value;
	for (var i=1;i<=max_row_count;i++)
	{
		iposition = ""+i;
		for(var j=i+1;j<=max_row_count;j++)
		{
			if(i == 1)
			{
				product_id[j] = getObj("hdnPoId"+j).value;
			}
			if(product_id[i] == product_id[j] && product_id[i] != '')
			{
				if(!duplicate) positions = iposition;
				duplicate = true;
				if(positions.search(j) == -1) positions = positions+" & "+j;

				if(duplicate_products.search(getObj("poName"+j).value) == -1)
					duplicate_products = duplicate_products+getObj("poName"+j).value+" \n";
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
			postBody: 'module='+lineItemType+'&action='+lineItemType+'Ajax&file=InventoryTaxAjax&productid='+document.getElementById("hdnPoId"+curr_row).value+'&curr_row='+curr_row+'&productTotal='+document.getElementById('totalAfterDiscount'+curr_row).innerHTML,
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
    var colthree  = row.insertCell(2);
    var colfour = row.insertCell(3);
    var colfive = row.insertCell(4);
    var colsix = row.insertCell(5);
    var colseven = row.insertCell(6);
    var coleight = row.insertCell(7);
    var colnine = row.insertCell(8); 

    var colten = row.insertCell(9);
    var coleleven = row.insertCell(10);
    var coltwelve = row.insertCell(11);
    var colthirteen = row.insertCell(12);
    var colfourteen = row.insertCell(13);
    var colfifteen = row.insertCell(14);
    var colsixteen = row.insertCell(15);
    var colseventeen = row.insertCell(16);
    var coleighteen = row.insertCell(17); 

	var colnineteen = row.insertCell(18); 
	var coltwenty = row.insertCell(19); 
	var coltwentyone = row.insertCell(20); 

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
    colone.style.textAlign='center';
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
    coltwo.innerHTML =
        '<textarea id="purchasesorder_no'+count+'" name="purchasesorder_no'+count+'" class="detailedViewTextBox user-success" style="width:200px;height:40px" readonly="readonly"></textarea>'+
        '<input id="hdnPoId'+count+'" name="hdnPoId'+count+'" value="" type="hidden"><input type="hidden" id="lineItemType'+count+'" name="lineItemType'+count+'" value="Products" />'+
        '<input type="hidden" id="lineItemType'+count+'" name="lineItemType'+count+'" value="Purchasesorder" />'+
        '<input type="hidden" id="poName'+count+'" name="poName'+count+'" value="" />'+
		'<input type="hidden" id="po_display'+count+'" name="po_display'+count+'" value="0" />'+
        '&nbsp;<img id="searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
        '<br><textarea id="comment'+count+'" name="comment'+count+'" class="detailedViewTextBox user-success" style="width:200px;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" />';

    
    colthree.className = "crmTableRow small";
    colthree.style.textAlign='left';
    colthree.innerHTML='<input id="purchase_order_date'+count+'" name="purchase_order_date'+count+'" type="text" class="detailedViewTextBox user-success purchase_order_date" onfocus="this.className=\'purchase_order_date detailedViewTextBoxOn\'"  onBlur="this.className=\'purchase_order_date detailedViewTextBox\';" value="" style="background-color: #ccc;" readonly />';

    colfour.className = "crmTableRow small"
    colfour.innerHTML = '<input id="projects_code'+count+'" name="projects_code'+count+'" type="text" class="detailedViewTextBox user-success projects_code" onfocus="this.className=\'projects_code detailedViewTextBoxOn\'"  onBlur="this.className=\'projects_code detailedViewTextBox\';" value="" style="background-color: #ccc;" readonly/>'+
    	'<input id="projectsid'+count+'" name="projectsid'+count+'" type="hidden" value="">'+
		'<input id="projects_name'+count+'" name="projects_name'+count+'" type="hidden" value="">';
    
    colfive.className = "crmTableRow small"
    colfive.innerHTML = '<input id="assignto'+count+'" name="assignto'+count+'" type="text" class="detailedViewTextBox user-success assignto" onfocus="this.className=\'assignto detailedViewTextBoxOn\'"  onBlur="this.className=\'assignto detailedViewTextBox\';" value="" style="background-color: #ccc;" readonly />'+
    '<input id="smownerid'+count+'" name="smownerid'+count+'" type="hidden" value="">';

    colsix.className = "crmTableRow small"
    colsix.innerHTML = '<input id="product_code_crm'+count+'" name="product_code_crm'+count+'" type="text" class="detailedViewTextBox user-success product_code_crm" onfocus="this.className=\'product_code_crm detailedViewTextBoxOn\'"  onBlur="this.className=\'product_code_crm detailedViewTextBox\';" value="" style="background-color: #ccc;" readonly/>'+
    '<input id="productid'+count+'" name="productid'+count+'" type="hidden" value="">'+
	'<img id="searchIconitem'+count+'" title="Items PO" src="themes/images/products.gif" style="cursor: pointer;" align="absmiddle" onclick="poItems(this,\''+module+'\','+count+')" />';

    colseven.className = "crmTableRow small"
    colseven.innerHTML = '<input data-rowno="'+count+'" id="productname'+count+'" name="productname'+count+'" type="text" class="detailedViewTextBox user-success productname" onfocus="this.className=\'productname detailedViewTextBoxOn\'" onblur="this.className=\'productname detailedViewTextBox\';" value="" style="background-color: #ccc;" readonly/>';
    
    //List Price with Discount, Total after Discount and Tax labels
    coleight.className = "crmTableRow small"
    coleight.innerHTML ='<input data-rowno="'+count+'" id="po_detail_no'+count+'" width="100%" name="po_detail_no'+count+'" type="text" class="detailedViewTextBox user-success po_detail_no" onfocus="this.className=\'po_detail_no detailedViewTextBoxOn\'" onBlur="this.className=\'po_detail_no detailedViewTextBox\';" value=""  style="background-color: #ccc;" readonly/>';
    
    colnine.className = "crmTableRow small"
    colnine.innerHTML ='<input data-rowno="'+count+'" id="po_quantity'+count+'" width="100%" name="po_quantity'+count+'" type="text" class="detailedViewTextBox user-success po_quantity" onfocus="this.className=\'po_quantity detailedViewTextBoxOn\'" onBlur="this.className=\'po_quantity detailedViewTextBox\';" value="0"  style="background-color: #ccc;" readonly/>';


	colten.className = "crmTableRow small"
    colten.innerHTML ='<input data-rowno="'+count+'" id="gr_percentage'+count+'" width="100%" name="gr_percentage'+count+'" type="text" class="detailedViewTextBox user-success gr_percentage" onfocus="this.className=\'gr_percentage detailedViewTextBoxOn\'" onBlur="this.className=\'gr_percentage detailedViewTextBox\';" value=""  style="background-color: #ccc;" readonly/>';

	coleleven.className = "crmTableRow small"
    coleleven.innerHTML ='<input data-rowno="'+count+'" id="item_status'+count+'" width="100%" name="item_status'+count+'" type="text" class="detailedViewTextBox user-success item_status" onfocus="this.className=\'item_status detailedViewTextBoxOn\'" onBlur="this.className=\'item_status detailedViewTextBox\';" value=""  style="background-color: #ccc;" readonly/>';

	coltwelve.className = "crmTableRow small"
    coltwelve.innerHTML ='<input data-rowno="'+count+'" id="gr_qty_percent'+count+'" width="100%" name="gr_qty_percent'+count+'" type="text" class="detailedViewTextBox user-success gr_qty_percent" onfocus="this.className=\'gr_qty_percent detailedViewTextBoxOn\'" onBlur="this.className=\'gr_qty_percent detailedViewTextBox\';" value=""  style="background-color: #ccc;" readonly/>';


    
    colthirteen.className = "crmTableRow small"
    colthirteen.innerHTML ='<input data-rowno="'+count+'" id="gr_quantity'+count+'" width="100%" name="gr_quantity'+count+'" type="text" class="detailedViewTextBox user-success gr_quantity" onfocus="this.className=\'gr_quantity detailedViewTextBoxOn\'" onBlur="this.className=\'gr_quantity detailedViewTextBox\';calAmount('+count+'); calRemainQuantity('+count+'); calTotalGR_quantity('+count+');" value="0" />';
    
    colfourteen.className = "crmTableRow small"
    colfourteen.innerHTML ='<input data-rowno="'+count+'" id="defects_quantity'+count+'" width="100%" name="defects_quantity'+count+'" type="text" class="detailedViewTextBox user-success defects_quantity" onfocus="this.className=\'defects_quantity detailedViewTextBoxOn\'" onBlur="this.className=\'defects_quantity detailedViewTextBox\';calAmount('+count+'); calRemainQuantity('+count+'); calTotalDefects('+count+');" value="0" />';
    
    colfifteen.className = "crmTableRow small"
    colfifteen.innerHTML ='<input data-rowno="'+count+'" id="remain_quantity'+count+'" width="100%" name="remain_quantity'+count+'" type="text" class="detailedViewTextBox user-success remain_quantity" onfocus="this.className=\'remain_quantity detailedViewTextBoxOn\'" onBlur="this.className=\'remain_quantity detailedViewTextBox\';" value="0" style="background-color: #ccc;" readonly/>'+
	'<input id="hdnRemain_quantity'+count+'" name="hdnRemain_quantity'+count+'" value="" type="hidden">';
    
    colsixteen.className = "crmTableRow small"
    colsixteen.innerHTML ='<input data-rowno="'+count+'" id="unit_price'+count+'" width="100%" name="unit_price'+count+'" type="text" class="detailedViewTextBox user-success unit_price" onfocus="this.className=\'unit_price detailedViewTextBoxOn\'" onBlur="this.className=\'unit_price detailedViewTextBox\';" value="0"  style="background-color: #ccc;" readonly/>';
    
    colseventeen.className = "crmTableRow small"
    colseventeen.innerHTML ='<input data-rowno="'+count+'" id="amount'+count+'" width="100%" name="amount'+count+'" type="text" class="detailedViewTextBox user-success amount" onfocus="this.className=\'amount detailedViewTextBoxOn\'" onBlur="this.className=\'amount detailedViewTextBox\';" value="0"  style="background-color: #ccc;" readonly/>';
    
	coleighteen.className = "crmTableRow small"
    coleighteen.innerHTML ='<input data-rowno="'+count+'" id="total_gr_quantity'+count+'" width="100%" name="total_gr_quantity'+count+'" type="text" class="detailedViewTextBox user-success total_gr_quantity" onfocus="this.className=\'total_gr_quantity detailedViewTextBoxOn\'" onBlur="this.className=\'total_gr_quantity detailedViewTextBox\';" value="0" style="background-color: #ccc;" readonly/>'+
	'<input id="hdnTotal_gr_quantity'+count+'" name="hdnTotal_gr_quantity'+count+'" value="" type="hidden">';
	
	colnineteen.className = "crmTableRow small"
    colnineteen.innerHTML ='<input data-rowno="'+count+'" id="total_defects_quantity'+count+'" width="100%" name="total_defects_quantity'+count+'" type="text" class="detailedViewTextBox user-success total_defects_quantity" onfocus="this.className=\'total_defects_quantity detailedViewTextBoxOn\'" onBlur="this.className=\'total_defects_quantity detailedViewTextBox\';" value="0" style="background-color: #ccc;" readonly/>'+
	'<input id="hdnTotal_defects_quantity'+count+'" name="hdnTotal_defects_quantity'+count+'" value="" type="hidden">';
    
    
    
    coltwenty.className = "crmTableRow small"
    coltwenty.innerHTML ='<input data-rowno="'+count+'" id="total_amount'+count+'" width="100%" name="total_amount'+count+'" type="text" class="detailedViewTextBox user-success total_amount" onfocus="this.className=\'total_amount detailedViewTextBoxOn\'" onBlur="this.className=\'total_amount detailedViewTextBox\';" value="0"  style="background-color: #ccc;" readonly/>';
    
    coltwentyone.className = "crmTableRow small"
    coltwentyone.innerHTML ='<textarea id="defects_remark'+count+'" name="defects_remark'+count+'" class="user-success" style="height:40px;width: 150px"></textarea>';

    //This is to show or hide the individual or group tax
    return count;
}
//Function used to add a new product row in PO, SO, Quotes and Invoice

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
		document.getElementById("individual_tax_row"+i).className = 'TaxHide';
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
	var discount_checks = new Array();

	discount_checks = document.getElementsByName("discount"+curr_row);

	if(discount_checks[0].checked == true)
	{
		document.getElementById("discount_type"+curr_row).value = 'zero';
		document.getElementById("discount_percentage"+curr_row).style.visibility = 'hidden';
		document.getElementById("discount_amount"+curr_row).style.visibility = 'hidden';
		document.getElementById("discountTotal"+curr_row).innerHTML = 0.00;
	}
	if(discount_checks[1].checked == true)
	{
		document.getElementById("discount_type"+curr_row).value = 'percentage';
		document.getElementById("discount_percentage"+curr_row).style.visibility = 'visible';
		document.getElementById("discount_amount"+curr_row).style.visibility = 'hidden';

		var discount_amount = 0.00;
		//This is to calculate the final discount
		if(curr_row == '_final')
		{
			var discount_percentage_final_value = document.getElementById("discount_percentage"+curr_row).value;
			if(discount_percentage_final_value == '') discount_percentage_final_value = 0;
			discount_amount = eval(document.getElementById("netTotal").innerHTML)*eval(discount_percentage_final_value)/eval(100);
		}
		else//This is to calculate the product discount
		{
			var discount_percentage_value = document.getElementById("discount_percentage"+curr_row).value;
			if(discount_percentage_value == '') discount_percentage_value = 0;
			discount_amount = eval(document.getElementById("productTotal"+curr_row).innerHTML)*eval(discount_percentage_value)/eval(100);
		}
		//Rounded the decimal part of discount amount to two digits
		document.getElementById("discountTotal"+curr_row).innerHTML = roundValue(discount_amount.toString());
	}
	if(discount_checks[2].checked == true)
	{
		document.getElementById("discount_type"+curr_row).value = 'amount';
		document.getElementById("discount_percentage"+curr_row).style.visibility = 'hidden';
		document.getElementById("discount_amount"+curr_row).style.visibility = 'visible';
		//Rounded the decimal part of discount amount to two digits
		var discount_amount_value = document.getElementById("discount_amount"+curr_row).value.toString();
		if(discount_amount_value == '') discount_amount_value = 0;
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

	for(var i=1;i<=group_tax_count;i++)
	{
		var group_tax_percentage = document.getElementById("group_tax_percentage"+i).value;
		if(group_tax_percentage == '') group_tax_percentage = '0';		
		tax_amount = eval(net_total_after_discount)*eval(group_tax_percentage)/eval(100);
		document.getElementById("group_tax_amount"+i).value = tax_amount;
		group_tax_total = eval(group_tax_total) + eval(tax_amount);
	}

	document.getElementById("tax_final").innerHTML = roundValue(group_tax_total);

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
			var productid = document.getElementById("hdnPoId"+i).value;
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
		//setDiscount(list_price_elem,i); 
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
	var aFieldIds = Array('hidtax_row_no','purchasesorder_no','hdnPoId','lineItemType','poName','comment','purchase_order_date','projects_code','projectsid','projects_name','assignto','smownerid','product_code_crm','productid','productname','po_detail_no','po_quantity','gr_percentage','item_status','gr_qty_percent','gr_quantity','defects_quantity','remain_quantity','hdnRemain_quantity','unit_price','amount','total_defects_quantity','hdnTotal_defects_quantity','total_gr_quantity','hdnTotal_gr_quantity','total_amount','defects_remark');
	var aContentIds = Array('qtyInStock','netPrice','subprod_names','business_code');
	var aOnClickHandlerIds = Array('searchIcon','searchIconitem');
	
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

			TTemp = document.getElementById(sId).innerHTML;
			document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
			document.getElementById(sSwapId).innerHTML = TTemp;

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
	//calcTotal();
}

function InventorySelectAll(mod,image_pth)
{
		
    if(document.selectall.selected_id != undefined)
    {
		var x = document.selectall.selected_id.length;
		var y=0;
		idstring = "";
		namestr = "";
		var action_str="";
		if ( x == undefined) {
			if (document.selectall.selected_id.checked) {
				idstring = document.selectall.selected_id.value;
				c = document.selectall.selected_id.value;
				var prod_array = JSON.parse($('popup_product_'+c).attributes['vt_prod_arr'].nodeValue);
				var prod_id = prod_array['entityid'];
				var prod_name = prod_array['prodname'];
				var unit_price = prod_array['unitprice'];
				var taxstring = prod_array['taxstring'];
				var desc = prod_array['desc'];
				var row_id = prod_array['rowid'];
				var subprod_ids = prod_array['subprod_ids'];
				if(mod!='PurchaseOrder') {
					var qtyinstk = prod_array['qtyinstk'];
					set_return_inventory(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids);
				} else {
					set_return_inventory_po(prod_id,prod_name,unit_price,taxstring,parseInt(row_id),desc,subprod_ids);
				}
				y=1;
			} else {
				alert(alert_arr.SELECT);
				return false;
			}
		} else {
			y=0;
			for(i = 0; i < x ; i++) {
				if(document.selectall.selected_id[i].checked) {
					idstring = document.selectall.selected_id[i].value+";"+idstring;
					c = document.selectall.selected_id[i].value;
					
					var prod_array = JSON.parse($('popup_product_'+c).attributes['vt_prod_arr'].nodeValue);
					
					var prod_id = prod_array['entityid'];
					var prod_name = prod_array['prodname'];
					var prod_code =  prod_array['prod_code'];
					var unit_price = prod_array['unitprice'];
					var taxstring = prod_array['taxstring'];
					var desc = prod_array['desc'];
					var subprod_ids = prod_array['subprod_ids'];
					var uom = prod_array['uom'];
					var return_module = prod_array['return_module'];
										
					if(y>0) {
						var row_id = window.opener.fnAddProductRow(mod,image_pth);
					} else {
						var row_id = prod_array['rowid'];
					}	
								
					if(mod!='PurchaseOrder') {
						var qtyinstk = prod_array['qtyinstk'];
						set_return_inventory(prod_id,prod_code,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom,'','','',return_module);

					} else {
						set_return_inventory_po(prod_id,prod_code,prod_name,unit_price,taxstring,parseInt(row_id),desc,subprod_ids,uom);
					}					
					y=y+1;
				}
			}
		}
		if (y != 0) {
			document.selectall.idlist.value=idstring;
			return true;
		} else {
			alert(alert_arr.SELECT);
			return false;
		}
    }
}


jQuery('select[name="gr_shipping_duration"]').on('change', function() {
	var gr_shipping_duration = jQuery(this).val();
	var export_inv_date = jQuery('input[name="export_inv_date"]').val();
	if((gr_shipping_duration != '' && gr_shipping_duration != '--None--') && export_inv_date != ''){
		var Asplit_S = export_inv_date.split('-');
		var export_inv_date_new = Asplit_S[2]+"-"+Asplit_S[1]+"-"+Asplit_S[0];
		var date = new Date(export_inv_date_new);

		var gr_shipping_duration_integer = parseInt(gr_shipping_duration);
		date.setDate(date.getDate() + gr_shipping_duration_integer);
		console.log(date);
		var year = date.getFullYear();
		var month = String(date.getMonth() + 1).padStart(2, '0');
		var day = String(date.getDate()).padStart(2, '0');
		var est_time_of_arrival = day + '-' + month + '-' + year;
		jQuery('input[name="est_time_of_arrival"]').val(est_time_of_arrival);
	}
});

function export_inv_date(dateText,selectedDate){
	console.log(selectedDate);
	var Asplit_S = selectedDate.split('-');
	var export_inv_date = Asplit_S[2]+"-"+Asplit_S[1]+"-"+Asplit_S[0];
	jQuery('input[name="export_inv_date"]').val(selectedDate);

	var gr_shipping_duration = jQuery('select[name="gr_shipping_duration"] option:selected').val();
	if(gr_shipping_duration != '' && gr_shipping_duration != '--None--'){
		console.log(export_inv_date);
		console.log(gr_shipping_duration);
		var date = new Date(export_inv_date);

		var gr_shipping_duration_integer = parseInt(gr_shipping_duration);
		date.setDate(date.getDate() + gr_shipping_duration_integer);
		console.log(date);
		var year = date.getFullYear();
		var month = String(date.getMonth() + 1).padStart(2, '0');
		var day = String(date.getDate()).padStart(2, '0');
		var est_time_of_arrival = day + '-' + month + '-' + year;
		jQuery('input[name="est_time_of_arrival"]').val(est_time_of_arrival);
	}		
}
