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

	//set the total number of products
	document.EditView.totalProductCount.value = max_row_count;	
}

function settotalnoofrowsCom() {
	var max_row_count = document.getElementById('proTabCom').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.totalCompetitorProduct.value = max_row_count;	
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
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var record_id = '';
    if(document.getElementsByName("account_id").length != 0)
    	record_id= document.EditView.account_id.value;
    if(record_id != '')
    	window.open("index.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&relmod_id="+record_id+"&parent_module=Accounts&return_module="+module+"&currencyid="+currencyid,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
    else
		window.open("index.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&return_module="+module+"&currencyid="+currencyid,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
}

function accountPickList(currObj,module, row_no) {
	var trObj=currObj.parentNode.parentNode
	
	var rowId = row_no;
	var currentRowId = parseInt(currObj.id.match(/([0-9]+)$/)[1]);
	
	// If we have mismatching rowId and currentRowId, it is due swapping of rows
	if(rowId != currentRowId) {
		rowId = currentRowId;
	}
	
	var currencyid = document.getElementById("inventory_currency").value;
	popuptype = 'inventory_prod';
	
    window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&select=enable&form=AccountsEditView&popuptype="+popuptype+"&curr_row="+rowId+"&return_module="+module+"&currencyid="+currencyid,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
}


function competitorproductPickList(currObj,module, row_no) {
	var trObj=currObj.parentNode.parentNode
	
	var rowId = row_no;
	var currentRowId = parseInt(currObj.id.match(/([0-9]+)$/)[1]);
	
	// If we have mismatching rowId and currentRowId, it is due swapping of rows
	if(rowId != currentRowId) {
		rowId = currentRowId;
	}
	
	var currencyid = document.getElementById("inventory_currency").value;
	popuptype = 'inventory_prod';
	
    window.open("index.php?module=Competitorproduct&action=Popup&html=Popup_picker&select=enable&form=CompetitorproductEditView&popuptype="+popuptype+"&curr_row="+rowId+"&return_module="+module+"&currencyid="+currencyid,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
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
	//image_path = document.getElementById("hidImagePath").value;
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
	//document.getElementById("productName"+i).value = "";
	document.getElementById('deleted'+i).value = 1;

	calcTotal()
}

function deleteComRow(module,i,image_path)
{
	rowCnt--;
	var tableName = document.getElementById('proTabCom');
	var prev = tableName.rows.length;

	document.getElementById("rowcom"+i).style.display = 'none';

	// Added For product Reordering starts
	//image_path = document.getElementById("hidImagePath").value;
	iMax = tableName.rows.length;
	for(iCount=i;iCount>=1;iCount--)
	{
		if(document.getElementById("rowcom"+iCount) && document.getElementById("rowcom"+iCount).style.display != 'none')
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
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="deleteComRow(\''+module+'\','+iPrevCount+')"><input id="deletedCom'+iPrevCount+'" name="deletedCom'+iPrevCount+'" type="hidden" value="0">&nbsp;<a href="javascript:moveUpDownCom(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	}
	else if(iPrevCount == 1)
	{
		iSwapIndex = i;
		for(iCount=i;iCount<=iMax-2;iCount++)
		{
			if(document.getElementById("rowcom"+iCount) && document.getElementById("rowcom"+iCount).style.display != 'none')
			{
				iSwapIndex = iCount;
				break;
			}
		}	
		if(iSwapIndex == i)
		{
			oPrevRow = eval(document.getElementById("rowcom"+iPrevCount));
			iPrevCount = eval(iPrevCount);
			oPrevRow.cells[0].innerHTML = '<input type="hidden" id="deletedCom1" name="deletedCom1" value="0">&nbsp;'; 
		}
	}
	// Product reordering addition ends
	document.getElementById("hdnCompetitorProductId"+i).value = "";
	document.getElementById('deletedCom'+i).value = 1;

	//calcTotal()
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
		calcProductTotal(rowId);
	}
	calcGrandTotal();
}

// Function to Calculate the Total for a particular product in an Inventory
function calcProductTotal(rowId) {	
		
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

function fnAddProductRowProjects(module,image_path){
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
    coltwo.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small">' +
        '<span id="business_code'+count+'" name="business_code'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>' +
        '<div id="productCodeView'+count+'" style="font-weight:bold;"></div>' +
        '<textarea id="productName'+count+'" name="productName'+count+'" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea>'+
        '<input id="hdnProductId'+count+'" name="hdnProductId'+count+'" value="" type="hidden"><input type="hidden" id="lineItemType'+count+'" name="lineItemType'+count+'" value="Products" />'+
        '<input type="hidden" id="lineItemType'+count+'" name="lineItemType'+count+'" value="Products" />'+
        '<input type="hidden" id="productcode'+count+'" name="productcode'+count+'" value="" />'+
        '&nbsp;<img id="searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
        '</td></tr><tr><td class="small"><input type="hidden" value="" id="subproduct_ids'+count+'" name="subproduct_ids'+count+'" /><span id="subprod_names'+count+'" name="subprod_names'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>'+
        '</td></tr><tr><td class="small" id="setComment'+count+'"><textarea id="comment'+count+'" name="comment'+count+'" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

    colthree.className = "crmTableRow small";
    colthree.innerHTML='<input data-rowno="'+count+'" id="product_brand'+count+'" name="product_brand'+count+'" type="text" class="detailedViewTextBox user-success product_brand" onfocus="this.className=\'product_brand detailedViewTextBoxOn\'"  onBlur="this.className=\'product_brand detailedViewTextBox\';" value=""/>';
    
    colfour.className = "crmTableRow small"
    colfour.innerHTML = '<input data-rowno="'+count+'" id="product_group'+count+'" name="product_group'+count+'" type="text" class="detailedViewTextBox user-success product_group" onfocus="this.className=\'product_group detailedViewTextBoxOn\'"  onBlur="this.className=\'product_group detailedViewTextBox\';" value=""/>';
    
    colfive.className = "crmTableRow small"
    temp = '<input data-rowno="'+count+'" id="account_name'+count+'" name="account_name'+count+'" type="text" class="detailedViewTextBox account_name" onfocus="this.className=\'detailedViewTextBoxOn account_name\'" onBlur="this.className=\'detailedViewTextBox account_name\';" value="" style="width: 80% !important" />';
	temp+= '<input type="hidden" name="accountid'+count+'" id="accountid'+count+'" value="0">&nbsp;';
	temp+= '<img id="searchIcon'+count+'" title="Dealer" src="themes/images/products.gif" style="cursor: pointer;" align="absmiddle" onclick="accountPickList(this,\''+module+'\','+count+')" />';
    colfive.innerHTML=temp;

    colsix.className = "crmTableRow small"
    temp1 = '<input data-rowno="'+count+'" id="jscal_field_first_delivered_date'+count+'" name="first_delivered_date'+count+'" type="text" style="border:1px solid #bababa;width: 80% !important" size="11" maxlength="10" value="" class="user-success">&nbsp;';
    temp1+= '<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_first_delivered_date'+count+'" style="vertical-align: middle;position:absolute;">';
    temp1+= '<br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>';
    temp1+= '<script type="text/javascript" id="massedit_calendar_first_delivered_date'+count+'">Calendar.setup ({inputField : "jscal_field_first_delivered_date'+count+'", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_first_delivered_date'+count+'", singleClick : true, step : 1 ,})</script>';
    colsix.innerHTML = temp1;

    colseven.className = "crmTableRow small"
    temp2 = '<input data-rowno="'+count+'" id="jscal_field_last_delivered_date'+count+'" name="last_delivered_date'+count+'" type="text" style="border:1px solid #bababa;width: 80% !important" size="11" maxlength="10" value="" class="user-success">&nbsp;';
    temp2+= '<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_last_delivered_date'+count+'" style="vertical-align: middle;position:absolute;">';
    temp2+= '<br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>';
    temp2+= '<script type="text/javascript" id="massedit_calendar_last_delivered_date'+count+'">Calendar.setup ({inputField : "jscal_field_last_delivered_date'+count+'", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_last_delivered_date'+count+'", singleClick : true, step : 1 ,})</script>';
    colseven.innerHTML = temp2;
   	
   	coleight.className = "crmTableRow small"
   	coleight.innerHTML = '<input data-rowno="'+count+'" id="estimated'+count+'" name="estimated'+count+'" type="text" class="detailedViewTextBox user-success estimated" onfocus="this.className=\'estimated detailedViewTextBoxOn\'"  onBlur="this.className=\'estimated detailedViewTextBox\';" value="0"/>';
    
    colnine.className = "crmTableRow small"
   	colnine.innerHTML = '<input data-rowno="'+count+'" id="plan'+count+'" name="plan'+count+'" type="text" class="detailedViewTextBox user-success plan" onfocus="this.className=\'plan detailedViewTextBoxOn\'"  onBlur="this.className=\'plan detailedViewTextBox\';" value="0" readonly style="background-color: #CCC;"/>';
    
    colten.className = "crmTableRow small"
   	colten.innerHTML = '<input data-rowno="'+count+'" id="delivered'+count+'" name="delivered'+count+'" type="text" class="detailedViewTextBox user-success delivered" onfocus="this.className=\'delivered detailedViewTextBoxOn\'"  onBlur="this.className=\'delivered detailedViewTextBox\';" value="0" readonly style="background-color: #CCC;"/>';
    
    coleleven.className = "crmTableRow small"
   	coleleven.innerHTML = '<input data-rowno="'+count+'" id="remain_on_hand'+count+'" name="remain_on_hand'+count+'" type="text" class="detailedViewTextBox user-success remain_on_hand" onfocus="this.className=\'remain_on_hand detailedViewTextBoxOn\'"  onBlur="this.className=\'remain_on_hand detailedViewTextBox\';" value="0" readonly style="background-color: #CCC;"/>';
        
    coltwelve.className = "crmTableRow small"
    coltwelve.innerHTML ='<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="right"><input data-rowno="'+count+'" id="listPrice'+count+'" width="100%" name="listPrice'+count+'" value="0" type="text" class="detailedViewTextBox user-success listprice" onfocus="this.className=\'listPrice detailedViewTextBoxOn\'" onBlur="this.className=\'listPrice detailedViewTextBox\';" />';
    
    settotalnoofrows();

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
	var aFieldIds = Array('hidtax_row_no','productName','subproduct_ids','hdnProductId','comment','qty','listPrice','discount_type','discount_percentage','discount_amount','tax1_percentage','hidden_tax1_percentage','popup_tax_row','tax2_percentage','hidden_tax2_percentage','lineItemType','uom','qty_act','listprice_total','qty_ship','qty_remain','status_dtl','productcategory');
	var aContentIds = Array('qtyInStock','netPrice','subprod_names','business_code');
	var aOnClickHandlerIds = Array('searchIcon');
	
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

function moveUpDownCom(sType,oModule,iIndex)
{
	var aFieldIds = Array('CompetitorproductName','hdnCompetitorProductId','competitorcomment','lineItem','competitor_brand','comprtitor_product_group','comprtitor_product_size','comprtitor_product_thickness','comprtitor_estimated_unit','competitor_price');
	var aContentIds = Array('qtyInStock','netPrice','subprod_names','business_code');
	var aOnClickHandlerIds = Array('searchIconCompetitor');
	
	iIndex = eval(iIndex) + 1;
	var oTable = document.getElementById('proTabCom');
	iMax = oTable.rows.length;
	iSwapIndex = 1;
	if(sType == 'UP')
	{ 
		for(iCount=iIndex-2;iCount>=1;iCount--)
		{
			if(document.getElementById("rowcom"+iCount))
			{
				if(document.getElementById("rowcom"+iCount).style.display != 'none' && document.getElementById('deletedCom'+iCount).value == 0)
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
			if(document.getElementById("rowcom"+iCount) && document.getElementById("rowcom"+iCount).style.display != 'none' && document.getElementById('deletedCom'+iCount).value == 0)
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


function fnAddCompetitorproductRow(module,image_path){
    rowCnt++;
    var tableName = document.getElementById('proTabCom');
    var prev = tableName.rows.length;
    var count = eval(prev)-1;//As the table has two headers, we should reduce the count
    var row = tableName.insertRow(prev);
    row.id = "rowcom"+count;
    row.style.verticalAlign = "top";

    var colone = row.insertCell(0);
    var coltwo = row.insertCell(1);
    var colthree  = row.insertCell(2);
    var colfour = row.insertCell(3);
    var colfive = row.insertCell(4);
    var colsix = row.insertCell(5);
    var colseven = row.insertCell(6);
    var coleight = row.insertCell(7);

    /* Product Re-Ordering Feature Code Addition Starts */
    iMax = tableName.rows.length;
    for(iCount=1;iCount<=iMax-3;iCount++)
    {
        if(document.getElementById("rowcom"+iCount) && document.getElementById("rowcom"+iCount).style.display != 'none')
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
    colone.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="deleteComRow(\''+module+'\','+count+',\'themes/images/\')"><input id="deletedCom'+count+'" name="deletedCom'+count+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:moveUpDownCom(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
    /* Product Re-Ordering Feature Code Addition Starts */
    if(iPrevCount != 1)
    {
        oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="deleteComRow(\''+module+'\','+iPrevCount+')"><input id="deletedCom'+iPrevCount+'" name="deletedCom'+iPrevCount+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:moveUpDownCom(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:moveUpDownCom(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
    }
    else
    {
        oPrevRow.cells[0].innerHTML = '<input id="deletedCom'+iPrevCount+'" name="deletedCom'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:moveUpDownCom(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
    }
    /* Product Re-Ordering Feature Code Addition ends */

    //Product Name with Popup image to select product
    coltwo.className = "crmTableRow small"
    coltwo.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small">' +
        '<textarea id="CompetitorproductName'+count+'" name="CompetitorproductName'+count+'" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea>'+
        '<input id="hdnCompetitorProductId'+count+'" name="hdnCompetitorProductId'+count+'" value="" type="hidden">'+
        '<input type="hidden" id="lineItem'+count+'" name="lineItem'+count+'" value="Competitorproduct" />'+
        '&nbsp;<img id="searchIconCompetitor'+count+'" title="Competitor Product" src="themes/images/products.gif" style="cursor: pointer;" onclick="competitorproductPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
        '</td></tr><tr><td class="small" id="setComment'+count+'"><textarea id="competitorcomment'+count+'" name="competitorcomment'+count+'" class="detailedViewTextBox user-success" style="width:90%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

    colthree.className = "crmTableRow small";
    colthree.innerHTML='<input data-rowno="'+count+'" id="competitor_brand'+count+'" name="competitor_brand'+count+'" type="text" class="detailedViewTextBox user-success competitor_brand" onfocus="this.className=\'competitor_brand detailedViewTextBoxOn\'"  onBlur="this.className=\'competitor_brand detailedViewTextBox\';" value=""/>';
    
    colfour.className = "crmTableRow small"
    colfour.innerHTML = '<input data-rowno="'+count+'" id="comprtitor_product_group'+count+'" name="comprtitor_product_group'+count+'" type="text" class="detailedViewTextBox user-success comprtitor_product_group" onfocus="this.className=\'comprtitor_product_group detailedViewTextBoxOn\'"  onBlur="this.className=\'comprtitor_product_group detailedViewTextBox\';" value=""/>';
    
    colfive.className = "crmTableRow small"
    colfive.innerHTML = '<input data-rowno="'+count+'" id="comprtitor_product_size'+count+'" name="comprtitor_product_size'+count+'" type="text" class="detailedViewTextBox user-success comprtitor_product_size" onfocus="this.className=\'comprtitor_product_size detailedViewTextBoxOn\'"  onBlur="this.className=\'comprtitor_product_size detailedViewTextBox\';" value=""/>';
    
    colsix.className = "crmTableRow small"
    colsix.innerHTML = '<input data-rowno="'+count+'" id="comprtitor_product_thickness'+count+'" name="comprtitor_product_thickness'+count+'" type="text" class="detailedViewTextBox user-success comprtitor_product_thickness" onfocus="this.className=\'comprtitor_product_thickness detailedViewTextBoxOn\'"  onBlur="this.className=\'comprtitor_product_thickness detailedViewTextBox\';" value=""/>';
    
    colseven.className = "crmTableRow small"
    colseven.innerHTML = '<input data-rowno="'+count+'" id="comprtitor_estimated_unit'+count+'" name="comprtitor_estimated_unit'+count+'" type="text" class="detailedViewTextBox user-success comprtitor_estimated_unit" onfocus="this.className=\'comprtitor_estimated_unit detailedViewTextBoxOn\'"  onBlur="this.className=\'comprtitor_estimated_unit detailedViewTextBox\';" value=""/>';
    
   	coleight.className = "crmTableRow small"
   	coleight.innerHTML = '<input data-rowno="'+count+'" id="competitor_price'+count+'" name="competitor_price'+count+'" type="text" class="detailedViewTextBox user-success competitor_price" onfocus="this.className=\'competitor_price detailedViewTextBoxOn\'"  onBlur="this.className=\'competitor_price detailedViewTextBox\';" value="0"/>';
   	
   	settotalnoofrowsCom();

    return count;
}
