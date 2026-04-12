jQuery(document).ready(function() {

	jQuery('#tt').tabs({
	    border:false,
	    onSelect:function(title){
	        alert(title+' is selected');
	    }
	});

	jQuery('#bu').multiSelect({
		selectableHeader: "<div class='detailedViewHeader'>BU</div>",
		selectionHeader: "<div class='detailedViewHeader'>Selected BU</div>",
	});
	jQuery('#select-all').click(function(){
		jQuery('#bu').multiSelect('select_all');
	  return false;
	});
	jQuery('#deselect-all').click(function(){
	  jQuery('#bu').multiSelect('deselect_all');
	  return false;
	});
	jQuery('#sku').multiSelect({
		 selectableHeader: "<div class='detailedViewHeader'>SKU</div>",
		 selectionHeader: "<div class='detailedViewHeader'>Selected SKU</div>",
	});
	jQuery('#select-all-sku').click(function(){
		jQuery('#sku').multiSelect('select_all');
	 return false;
	});
	jQuery('#deselect-all-sku').click(function(){
	 jQuery('#sku').multiSelect('deselect_all');
	 return false;
	});
	//jQuery('.toggle').hide();
	jQuery('input[name=stpromotion]').live('change',  function() {
		jQuery('.toggle').hide('slow');
		jQuery(this).parent().next('.toggle').toggle('slow');
	});


});

function show_tab(chk_tab){
	//alert(chk_tab);
	jQuery('.toggle').hide();
	jQuery('input:radio[name="stpromotion"]').filter('[value="'+chk_tab+'"]').attr('checked', true);

	var  stpromotion = jQuery('#stpromotion'+chk_tab);
	jQuery(stpromotion).parent().next('.toggle').toggle('slow');
	//console.log(jQeury('input:radio[name="stpromotion"]').val);
}
function mySetup_Disc_productPickList(currObj,module, row_no) {

	var trObj=currObj.parentNode.parentNode

	var rowId = row_no;

	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var url="";

	url="plugin/Promotion/search_pop_product_mySetup_Disc.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&parent_module=Products&return_module="+module;
	
	window.open(url,"productWin","width=900,height=625,resizable=0,scrollbars=0,status=1,top=0,left=200");
}
function mySetup_Tab2_productPickList(currObj,module, row_no) {
	var trObj=currObj.parentNode.parentNode

	var rowId = row_no;
	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var url="";
	url="plugin/Promotion/search_pop_product_mySetup_Tab2.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&parent_module=Products&return_module="+module;
	window.open(url,"productWin","width=900,height=625,resizable=0,scrollbars=0,status=1,top=0,left=200");
}
function mySetup_Tab1_2_productPickList(currObj,module, row_no) {

	var trObj=currObj.parentNode.parentNode
	var rowId = row_no;
	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var url="";
	url="plugin/Promotion/search_pop_product_mySetup_Tab1_2.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&parent_module=Products&return_module="+module;

	window.open(url,"productWin","width=900,height=625,resizable=0,scrollbars=0,status=1,top=0,left=200");
}
function mySetup_Tab1_1_PremiumList(currObj,module, row_no) {

	var trObj=currObj.parentNode.parentNode

	var rowId = row_no;

	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var url="";
	url="plugin/Promotion/search_pop_product_mySetup_Tab1_1.php?module=Premium&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&parent_module=Products&return_module="+module;

	window.open(url,"productWin","width=900,height=625,resizable=0,scrollbars=0,status=1,top=0,left=200");
}
function mySetup_Tab2_PremiumList(currObj,module, row_no) {

	var trObj=currObj.parentNode.parentNode

	var rowId = row_no;

	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var url="";
	url="plugin/Promotion/search_pop_premium_mySetup_Tab2.php?module=Premium&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&parent_module=Products&return_module="+module;

	window.open(url,"productWin","width=900,height=625,resizable=0,scrollbars=0,status=1,top=0,left=200");
}
function InventorySelectAll_mySetup_Disc(mod,image_pth){

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
				var uom = prod_array['usageunit'];
				if(mod!='PurchaseOrder') {
					var qtyinstk = prod_array['qtyinstk'];
					set_return_inventory_mySetup_Disc(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
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
					var unit_price = prod_array['unitprice'];
					var taxstring = prod_array['taxstring'];
					var desc = prod_array['desc'];
					var subprod_ids = prod_array['subprod_ids'];
					var uom = prod_array['usageunit'];

					if(y>0) {
						var row_id = window.opener.fnAddSetup_Disc(mod,image_pth);
					} else {
						var row_id = prod_array['rowid'];
					}
					
					if(mod!='PurchaseOrder') {
						var qtyinstk = prod_array['mySetup_Disc_qtyinstk'];
						set_return_inventory_mySetup_Disc(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
					} else {
						set_return_inventory_po(prod_id,prod_name,unit_price,taxstring,parseInt(row_id),desc,subprod_ids);
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
function InventorySelectAll_mySetup_Tab1_2(mod,image_pth){

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
				var uom = prod_array['usageunit'];
				if(mod!='PurchaseOrder') {
					var qtyinstk = prod_array['qtyinstk'];
					set_return_inventory_mySetup_Tab1_2(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
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
					var unit_price = prod_array['unitprice'];
					var taxstring = prod_array['taxstring'];
					var desc = prod_array['desc'];
					var subprod_ids = prod_array['subprod_ids'];
					var uom = prod_array['usageunit'];

					if(y>0) {
						var row_id = window.opener.fnAdd_mySetup_Tab1_2(mod,image_pth);
					} else {
						var row_id = prod_array['rowid'];
					}
					
					if(mod!='PurchaseOrder') {
						var qtyinstk = prod_array['mySetup_Disc_qtyinstk'];
						set_return_inventory_mySetup_Tab1_2(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
					} else {
						set_return_inventory_po(prod_id,prod_name,unit_price,taxstring,parseInt(row_id),desc,subprod_ids);
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
function InventorySelectAll_mySetup_Tab2(mod,image_pth){
	
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
				var uom = prod_array['usageunit'];
				if(mod!='PurchaseOrder') {
					var qtyinstk = prod_array['qtyinstk'];
					set_return_inventory_mySetup_Tab2(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
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
					var unit_price = prod_array['unitprice'];
					var taxstring = prod_array['taxstring'];
					var desc = prod_array['desc'];
					var subprod_ids = prod_array['subprod_ids'];
					var uom = prod_array['usageunit'];

					if(y>0) {
						var row_id = window.opener.fnAdd_mySetup_Tab2(mod,image_pth);
					} else {
						var row_id = prod_array['rowid'];
					}
					
					if(mod!='PurchaseOrder') {

						set_return_inventory_mySetup_Tab2(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
					} else {
						set_return_inventory_po(prod_id,prod_name,unit_price,taxstring,parseInt(row_id),desc,subprod_ids);
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
function fnAddSetup_Disc(module,image_path){
	rowCnt++;
	var tableName = document.getElementById('mySetup_Disc');
	var prev = tableName.rows.length;
	var count = eval(prev)-1;//As the table has two headers, we should reduce the count
	var row = tableName.insertRow(prev);
	row.id = "mySetup_Disc_row"+count;
	row.style.verticalAlign = "top";

	var col1 = row.insertCell(0);
	var col2 = row.insertCell(1);
	var col3 = row.insertCell(2);
	var col4 = row.insertCell(3);
	var col5 = row.insertCell(4);
	/* Product Re-Ordering Feature Code Addition Starts */
	iMax = tableName.rows.length;
	for(iCount=1;iCount<=iMax-3;iCount++)
	{
		if(document.getElementById("mySetup_Disc_row"+iCount) && document.getElementById("mySetup_Disc_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
		}
	}
	iPrevCount = eval(iPrevRowIndex);
	var oPrevRow = tableName.rows[iPrevRowIndex+1];
	var delete_row_count=count;
	//Delete link
	col1.className = "crmTableRow small";
	col1.id = row.id+"_col1";
	col1.style.textAlign = "center";
	col1.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="mySetup_Disc_deleteRow(\''+module+'\','+count+',\'themes/images/\')"><input id="mySetup_Disc_deleted'+count+'" name="mySetup_Disc_deleted'+count+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Disc_moveUpDown(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	/* Product Re-Ordering Feature Code Addition Starts */
	if(iPrevCount != 1)
	{
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Disc_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Disc_deleted'+iPrevCount+'" name="mySetup_Disc_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Disc_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:mySetup_Disc_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	else
	{
		oPrevRow.cells[0].innerHTML = '<input id="mySetup_Disc_deleted'+iPrevCount+'" name="mySetup_Disc_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:mySetup_Disc_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	/* Product Re-Ordering Feature Code Addition ends */

	//Product Name with Popup image to select product
	col2.className = "crmTableRow small"
	col2.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small"><input id="mySetup_Disc_productName'+count+'" name="mySetup_Disc_productName'+count+'" class="small" style="width: 70%;" value="" readonly="readonly" type="text">'+
						'<input id="mySetup_Disc_hdnProductId'+count+'" name="mySetup_Disc_hdnProductId'+count+'" value="" type="hidden"><input type="hidden" id="mySetup_Disc_lineItemType'+count+'" name="mySetup_Disc_lineItemType'+count+'" value="Products" />'+
						'&nbsp;<img id="mySetup_Disc_searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="mySetup_Disc_productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
						'</td></tr><tr><td class="small"><input type="hidden" value="" id="mySetup_Disc_subproduct_ids'+count+'" name="mySetup_Disc_subproduct_ids'+count+'" /><span id="mySetup_Disc_subprod_names'+count+'" name="mySetup_Disc_subprod_names'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>'+
						'</td></tr><tr><td class="small" id="mySetup_Disc_setComment'+count+'"><textarea id="mySetup_Disc_comment'+count+'" name="mySetup_Disc_comment'+count+'" class=small style="width:70%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

	col3.className = "crmTableRow small"
	col3.innerHTML='<input id="mySetup_Disc_uom'+count+'" name="mySetup_Disc_uom'+count+'" type="text"  style="width:50px" class=detailedViewTextBox onFocus="this.className=\'detailedViewTextBoxOn\';" onBlur="this.className=\'detailedViewTextBox\';"  value="" />';

	//Quantity
	var temp='';
	col4.className = "crmTableRow small"
	temp='<input id="mySetup_Disc_qty'+count+'" name="mySetup_Disc_qty'+count+'" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="check_number(\'mySetup_Disc_qty'+count+'\');settotalnoofrows_Setup_Disc();" value=""/>';
	col4.innerHTML=temp;

	col5.className = "crmTableRow small"
	col5.innerHTML='<input id="mySetup_Disc_listPrice'+count+'" name="mySetup_Disc_listPrice'+count+'" value="0.00" type="text" class="detailedViewTextBox"  style="width:70px" onBlur="check_number(\'mySetup_Disc_listPrice'+count+'\');settotalnoofrows_Setup_Disc();"/>';
	return count;
}

function fnAdd_mySetup_Tab1_2(module,image_path){
	//alert(555);
	rowCnt++;
	var tableName = document.getElementById('mySetup_Tab1_2');
	var prev = tableName.rows.length;
	var count = eval(prev)-1;//As the table has two headers, we should reduce the count
	var row = tableName.insertRow(prev);
	row.id = "mySetup_Tab1_2_row"+count;
	row.style.verticalAlign = "top";

	var col1 = row.insertCell(0);
	var col2 = row.insertCell(1);
	var col3 = row.insertCell(2);

	/* Product Re-Ordering Feature Code Addition Starts */
	iMax = tableName.rows.length;
	for(iCount=1;iCount<=iMax-3;iCount++)
	{
		if(document.getElementById("mySetup_Tab1_2_row"+iCount) && document.getElementById("mySetup_Tab1_2_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
		}
	}
	iPrevCount = eval(iPrevRowIndex);
	var oPrevRow = tableName.rows[iPrevRowIndex+1];
	var delete_row_count=count;
	//Delete link
	col1.className = "crmTableRow small";
	col1.id = row.id+"_col1";
	col1.style.textAlign = "center";
	col1.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_2_deleteRow(\''+module+'\','+count+',\'themes/images/\')"><input id="mySetup_Tab1_2_deleted'+count+'" name="mySetup_Tab1_2_deleted'+count+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Tab1_2_moveUpDown(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	/* Product Re-Ordering Feature Code Addition Starts */
	if(iPrevCount != 1)
	{
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_2_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab1_2_deleted'+iPrevCount+'" name="mySetup_Tab1_2_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Tab1_2_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:mySetup_Tab1_2_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	else
	{
		oPrevRow.cells[0].innerHTML = '<input id="mySetup_Tab1_2_deleted'+iPrevCount+'" name="mySetup_Tab1_2_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:mySetup_Tab1_2_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	/* Product Re-Ordering Feature Code Addition ends */

	//Product Name with Popup image to select product
	col2.className = "crmTableRow small"
	col2.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small"><input id="mySetup_Tab1_2_productName'+count+'" name="mySetup_Tab1_2_productName'+count+'" class="small" style="width: 70%;" value="" readonly="readonly" type="text">'+
						'<input id="mySetup_Tab1_2_hdnProductId'+count+'" name="mySetup_Tab1_2_hdnProductId'+count+'" value="" type="hidden"><input type="hidden" id="mySetup_Tab1_2_lineItemType'+count+'" name="mySetup_Tab1_2_lineItemType'+count+'" value="Products" />'+
						'&nbsp;<img id="mySetup_Tab1_2_searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="mySetup_Tab1_2_productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
						'</td></tr><tr><td class="small"><input type="hidden" value="" id="mySetup_Tab1_2_subproduct_ids'+count+'" name="mySetup_Tab1_2_subproduct_ids'+count+'" /><span id="mySetup_Tab1_2_subprod_names'+count+'" name="mySetup_Tab1_2_subprod_names'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>'+
						'</td></tr><tr><td class="small" id="mySetup_Tab1_2_setComment'+count+'"><textarea id="mySetup_Tab1_2_comment'+count+'" name="mySetup_Tab1_2_comment'+count+'" class=small style="width:70%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

	col3.className = "crmTableRow small"
	col3.innerHTML='<input id="mySetup_Tab1_2_uom'+count+'" name="mySetup_Tab1_2_uom'+count+'" type="text"  style="width:50px" class=detailedViewTextBox onFocus="this.className=\'detailedViewTextBoxOn\';" onBlur="this.className=\'detailedViewTextBox\';"  value="" /><input id="mySetup_Tab1_2_qty'+count+'" name="mySetup_Tab1_2_qty'+count+'" type="hidden" class="detailedViewTextBox"  style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="settotalnoofrows_mySetup_Tab1_2();" value=""/><input id="mySetup_Tab1_2_listPrice'+count+'" name="mySetup_Tab1_2_listPrice'+count+'" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" />';
	settotalnoofrows_mySetup_Tab1_2();
	return count;
}
function fnAdd_mySetup_Tab2(module,image_path){
	rowCnt++;
	var tableName = document.getElementById('mySetup_Tab2');
	var prev = tableName.rows.length;
	var count = eval(prev)-1;//As the table has two headers, we should reduce the count
	var row = tableName.insertRow(prev);
	row.id = "mySetup_Tab2_row"+count;
	row.style.verticalAlign = "top";

	var col1 = row.insertCell(0);
	var col2 = row.insertCell(1);
	var col3 = row.insertCell(2);

	/* Product Re-Ordering Feature Code Addition Starts */
	iMax = tableName.rows.length;
	for(iCount=1;iCount<=iMax-3;iCount++)
	{
		if(document.getElementById("mySetup_Tab2_row"+iCount) && document.getElementById("mySetup_Tab2_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
		}
	}
	iPrevCount = eval(iPrevRowIndex);
	var oPrevRow = tableName.rows[iPrevRowIndex+1];
	var delete_row_count=count;
	//Delete link
	col1.className = "crmTableRow small";
	col1.id = row.id+"_col1";
	col1.style.textAlign = "center";
	col1.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab2_deleteRow(\''+module+'\','+count+',\'themes/images/\')"><input id="mySetup_Tab2_deleted'+count+'" name="mySetup_Tab2_deleted'+count+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Tab2_moveUpDown(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	/* Product Re-Ordering Feature Code Addition Starts */
	if(iPrevCount != 1)
	{
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab2_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab2_deleted'+iPrevCount+'" name="mySetup_Tab2_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Tab2_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:mySetup_Tab2_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	else
	{
		oPrevRow.cells[0].innerHTML = '<input id="mySetup_Tab2_deleted'+iPrevCount+'" name="mySetup_Tab2_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:mySetup_Tab2_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	/* Product Re-Ordering Feature Code Addition ends */

	//Product Name with Popup image to select product
	col2.className = "crmTableRow small"
	col2.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small"><input id="mySetup_Tab2_productName'+count+'" name="mySetup_Tab2_productName'+count+'" class="small" style="width: 70%;" value="" readonly="readonly" type="text">'+
						'<input id="mySetup_Tab2_hdnProductId'+count+'" name="mySetup_Tab2_hdnProductId'+count+'" value="" type="hidden"><input type="hidden" id="mySetup_Tab2_lineItemType'+count+'" name="mySetup_Tab2_lineItemType'+count+'" value="Products" />'+
						'&nbsp;<img id="mySetup_Tab2_searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="mySetup_Tab2_productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
						'</td></tr><tr><td class="small"><input type="hidden" value="" id="mySetup_Tab2_subproduct_ids'+count+'" name="mySetup_Tab2_subproduct_ids'+count+'" /><span id="mySetup_Tab2_subprod_names'+count+'" name="mySetup_Tab2_subprod_names'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>'+
						'</td></tr><tr><td class="small" id="mySetup_Tab2_setComment'+count+'"><textarea id="mySetup_Tab2_comment'+count+'" name="mySetup_Tab2_comment'+count+'" class=small style="width:70%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

	col3.className = "crmTableRow small"
	col3.innerHTML='<input id="mySetup_Tab2_qty'+count+'" name="mySetup_Tab2_qty'+count+'" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="check_number(\'mySetup_Tab2_qty'+count+'\');settotalnoofrows_mySetup_Tab2();" value=""/><input id="mySetup_Tab2_uom'+count+'" name="mySetup_Tab2_uom'+count+'" type="hidden"  style="width:50px" class=detailedViewTextBox onFocus="this.className=\'detailedViewTextBoxOn\';" onBlur="this.className=\'detailedViewTextBox\';"  value="" /><input id="mySetup_Tab2_listPrice'+count+'" name="mySetup_Tab2_listPrice'+count+'" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" />';
	settotalnoofrows_mySetup_Tab2();
	return count;
}
function mySetup_Tab2_deleteRow(module,i,image_path){//alert(555);
	rowCnt--;
	var tableName = document.getElementById('mySetup_Tab2');
	var prev = tableName.rows.length;

	document.getElementById("mySetup_Tab2_row"+i).style.display = 'none';

// Added For product Reordering starts

	iMax = tableName.rows.length;
	for(iCount=i;iCount>=1;iCount--)
	{
		if(document.getElementById("mySetup_Tab2_row"+iCount) && document.getElementById("mySetup_Tab2_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
			break;
		}
	}
	iPrevCount = iPrevRowIndex;
	oCurRow = eval(document.getElementById("mySetup_Tab2_row"+i));
	sTemp = oCurRow.cells[0].innerHTML;
	ibFound = sTemp.indexOf("down_layout.gif");

	if(i != 2 && ibFound == -1 && iPrevCount != 1)
	{
		oPrevRow = eval(document.getElementById("mySetup_Tab2_row"+iPrevCount));

		iPrevCount = eval(iPrevCount);
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab2_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab2_deleted'+iPrevCount+'" name="mySetup_Tab2_deleted'+iPrevCount+'" type="hidden" value="0">&nbsp;<a href="javascript:mySetup_Tab2_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	}
	else if(iPrevCount == 1)
	{
		iSwapIndex = i;
		for(iCount=i;iCount<=iMax-2;iCount++)
		{
			if(document.getElementById("mySetup_Tab2_row"+iCount) && document.getElementById("mySetup_Tab2_row"+iCount).style.display != 'none')
			{
				iSwapIndex = iCount;
				break;
			}
		}
		if(iSwapIndex == i)
		{
			oPrevRow = eval(document.getElementById("mySetup_Tab2_row"+iPrevCount));
			iPrevCount = eval(iPrevCount);
			oPrevRow.cells[0].innerHTML = '<input type="hidden" id="mySetup_Tab2_deleted1" name="mySetup_Disc_deleted1" value="0">&nbsp;';
		}
	}
	//Product reordering addition ends
	document.getElementById("mySetup_Tab2_hdnProductId"+i).value = "";
	//document.getElementById("productName"+i).value = "";
	document.getElementById('mySetup_Tab2_deleted'+i).value = 1;
}
function mySetup_Tab2_moveUpDown(sType,oModule,iIndex){
	var aFieldIds = Array('mySetup_Tab2_hidtax_row_no','mySetup_Tab2_productName','mySetup_Tab2_subproduct_ids','mySetup_Tab2_hdnProductId','mySetup_Tab2_comment','mySetup_Tab2_qty','mySetup_Tab2_listPrice','mySetup_Tab2_discount_type','mySetup_Tab2_discount_percentage','mySetup_Tab2_discount_amount','mySetup_Tab2_tax1_percentage','mySetup_Tab2_hidden_tax1_percentage','mySetup_Tab2_popup_tax_row','mySetup_Tab2_tax2_percentage','mySetup_Tab2_hidden_tax2_percentage','mySetup_Tab2_lineItemType','mySetup_Tab2_uom');
	var aContentIds = Array('mySetup_Tab2_qtyInStock','mySetup_Tab2_netPrice','mySetup_Tab2_subprod_names');
	var aOnClickHandlerIds = Array('mySetup_Tab2_searchIcon');

	iIndex = eval(iIndex) + 1;
	var oTable = document.getElementById('mySetup_Tab2');
	iMax = oTable.rows.length;
	iSwapIndex = 1;
	if(sType == 'UP')
	{
		for(iCount=iIndex-2;iCount>=1;iCount--)
		{
			if(document.getElementById("mySetup_Tab2_row"+iCount))
			{
				if(document.getElementById("mySetup_Tab2_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Tab2_deleted'+iCount).value == 0)
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
			if(document.getElementById("mySetup_Tab2_row"+iCount) && document.getElementById("mySetup_Tab2_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Tab2_deleted'+iCount).value == 0)
			{
				iSwapIndex = iCount;
				break;
			}
		}
		iSwapIndex += 1;
	}

	var oSwapRow = oTable.rows[iSwapIndex];

	iIndex -= 1;
	iSwapIndex -= 1;

	//alert(555);
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
}
function settotalnoofrows_Setup_Disc() {
	var max_row_count = document.getElementById('mySetup_Disc').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.mySetup_Disc_totalProductCount.value = max_row_count;
	cal_Setup_Disc();
}
function settotalnoofrows_mySetup_Tab1_1() {
	var max_row_count = document.getElementById('mySetup_Tab1_1').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.mySetup_Tab1_1_totalProductCount.value = max_row_count;
}
function settotalnoofrows_mySetup_Tab1_2() {
	var max_row_count = document.getElementById('mySetup_Tab1_2').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.mySetup_Tab1_2_totalProductCount.value = max_row_count;
}
function settotalnoofrows_mySetup_Tab2() {
	var max_row_count = document.getElementById('mySetup_Tab2').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.mySetup_Tab2_totalProductCount.value = max_row_count;
}
function cal_Setup_Disc(){
	var max_row_count = document.getElementById('mySetup_Disc').rows.length;
	max_row_count = eval(
	max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length
	var netprice = 0.00;
	var total=0;
	var dis_type=document.getElementById('mySetup_Disc_Discount_type').value;
	var dis_value =document.getElementById('mySetup_Disc_Dis_value').value;
	var dis=0;
	var per=100;
	for(var i=1;i<=max_row_count;i++)
	{
		if(document.getElementById("mySetup_Disc_deleted"+i).value == 1)
			continue;
		total=total+(document.getElementById('mySetup_Disc_qty'+i).value*document.getElementById('mySetup_Disc_listPrice'+i).value);
	}
	document.getElementById('mySetup_Disc_total').value=total;
	if(dis_type=="%"){
		dis=(total / per)*dis_value;
		document.getElementById('mySetup_Disc_Discount').value=dis;
		document.getElementById('mySetup_Disc_Net').value=total-dis;
	}else if(dis_type=="baht"){
		dis=dis_value;
		document.getElementById('mySetup_Disc_Discount').value=dis;
		document.getElementById('mySetup_Disc_Net').value=total-dis;
	}
}
function check_save(){
	var chk_btn = new Array();
	chk_btn = document.getElementsByName("stpromotion")
	
	//alert(stpromotion3);
	if(chk_btn[0].checked == true){
		document.getElementById("mySetup_Disc_chk").value=1;
		for (var i=1;i<=max_row_count;i++){
			if(document.getElementById("mySetup_Tab1_1_deleted"+i).value == 1)
				continue;
			if (!emptyCheck("mySetup_Tab1_1_productprice_from"+i,"Productprice From","text")) return false
			if (!numValidate("mySetup_Tab1_1_productprice_from"+i,"Productprice From","any")) return false
			if (!numValidate("mySetup_Tab1_1_productprice_to"+i,"Productprice To","text")) return false
			if (!emptyCheck("mySetup_Tab1_1_productprice_to"+i,"Productprice To","text")) return false
		}
	}else if(chk_btn[1].checked == true){
		var max_row_count = document.getElementById('mySetup_Tab2').rows.length;
		max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from table row length
		if(max_row_count == 0){
			alert(alert_arr.NO_LINE_ITEM_SELECTED);
			return false;
		}
		for (var i=1;i<=max_row_count;i++){
			if(document.getElementById("mySetup_Tab2_deleted"+i).value == 1)
				continue;
			if (!emptyCheck("mySetup_Tab2_productName"+i,alert_arr.LINE_ITEM,"text")) return false
			if (!emptyCheck("mySetup_Tab2_qty"+i,"Qty","text")) return false
			if (!numValidate("mySetup_Tab2_qty"+i,"Qty","any")) return false
			if (!numConstComp("mySetup_Tab2_qty"+i,"Qty","G","0")) return false
		}
		document.getElementById("mySetup_Disc_chk").value=2;
	}else if(chk_btn[2].checked == true){
		var max_row_count = document.getElementById('mySetup_Disc').rows.length;
		max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from table row length
		if(max_row_count == 0){
			alert(alert_arr.NO_LINE_ITEM_SELECTED);
			return false;
		}

		document.getElementById("mySetup_Disc_chk").value=3;
		if (!emptyCheck("mySetup_Disc_Name","Discount Name","text")) return false
		if (!emptyCheck("mySetup_Disc_Discount_type","Discount type","text")) return false
		for (var i=1;i<=max_row_count;i++){
			if(document.getElementById("mySetup_Disc_deleted"+i).value == 1)
				continue;
			if (!emptyCheck("mySetup_Disc_productName"+i,alert_arr.LINE_ITEM,"text")) return false
			if (!emptyCheck("mySetup_Disc_qty"+i,"Qty","text")) return false
			if (!numValidate("mySetup_Disc_qty"+i,"Qty","any")) return false
			if (!numConstComp("mySetup_Disc_qty"+i,"Qty","G","0")) return false
			if (!emptyCheck("mySetup_Disc_listPrice"+i,alert_arr.LIST_PRICE,"text")) return false
			if (!numValidate("mySetup_Disc_listPrice"+i,alert_arr.LIST_PRICE,"any")) return false
		}
	}else{
		alert("Please Select Tab");
		return false
	}
	if(!formValidate()){
		return false
	}else{

	}
}
function mySetup_Tab1_1_deleteRow(module,i,image_path)
{
	rowCnt--;
	var tableName = document.getElementById('mySetup_Tab1_1');
	var prev = tableName.rows.length;

	document.getElementById("mySetup_Tab1_1_row"+i).style.display = 'none';

	//Added For product Reordering starts
	//image_path = document.getElementById("mySetup_Tab1_1_hidImagePath").value;
	iMax = tableName.rows.length;
	for(iCount=i;iCount>=1;iCount--)
	{
		if(document.getElementById("mySetup_Tab1_1_row"+iCount) && document.getElementById("mySetup_Tab1_1_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
			break;
		}
	}
	
	iPrevCount = iPrevRowIndex;
	oCurRow = eval(document.getElementById("mySetup_Tab1_1_row"+i));
	sTemp = oCurRow.cells[0].innerHTML;
	ibFound = sTemp.indexOf("down_layout.gif");

	if(i != 2 && ibFound == -1 && iPrevCount != 1)
	{
		oPrevRow = eval(document.getElementById("mySetup_Tab1_1_row"+iPrevCount));

		iPrevCount = eval(iPrevCount);
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_1_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab1_1_deleted'+iPrevCount+'" name="mySetup_Tab1_1_deleted'+iPrevCount+'" type="hidden" value="0">';
	}
	else if(iPrevCount == 1)
	{
		iSwapIndex = i;
		for(iCount=i;iCount<=iMax-2;iCount++)
		{
			if(document.getElementById("mySetup_Tab1_1_row"+iCount) && document.getElementById("mySetup_Tab1_1_row"+iCount).style.display != 'none')
			{
				iSwapIndex = iCount;
				break;
			}
		}
		if(iSwapIndex == i)
		{
			oPrevRow = eval(document.getElementById("mySetup_Tab1_1_row"+iPrevCount));
			iPrevCount = eval(iPrevCount);
			oPrevRow.cells[0].innerHTML = '<input type="hidden" id="mySetup_Tab1_1_deleted1" name="mySetup_Tab1_1_deleted1" value="0">&nbsp;';
		}
	}
	// Product reordering addition ends

	document.getElementById('mySetup_Tab1_1_deleted'+i).value = 1;
}

function mySetup_Disc_deleteRow(module,i,image_path){
	rowCnt--;
	var tableName = document.getElementById('mySetup_Disc');
	var prev = tableName.rows.length;

	document.getElementById("mySetup_Disc_row"+i).style.display = 'none';

	//Added For product Reordering starts
	iMax = tableName.rows.length;
	for(iCount=i;iCount>=1;iCount--)
	{
		if(document.getElementById("mySetup_Disc_row"+iCount) && document.getElementById("mySetup_Disc_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
			break;
		}
	}
	iPrevCount = iPrevRowIndex;
	oCurRow = eval(document.getElementById("mySetup_Disc_row"+i));
	sTemp = oCurRow.cells[0].innerHTML;
	ibFound = sTemp.indexOf("down_layout.gif");
	
	if(i != 2 && ibFound == -1 && iPrevCount != 1)
	{
		oPrevRow = eval(document.getElementById("mySetup_Disc_row"+iPrevCount));

		iPrevCount = eval(iPrevCount);
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Disc_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Disc_deleted'+iPrevCount+'" name="mySetup_Disc_deleted'+iPrevCount+'" type="hidden" value="0">&nbsp;<a href="javascript:mySetup_Disc_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	}
	else if(iPrevCount == 1)
	{
		iSwapIndex = i;
		for(iCount=i;iCount<=iMax-2;iCount++)
		{
			if(document.getElementById("mySetup_Disc_row"+iCount) && document.getElementById("mySetup_Disc_row"+iCount).style.display != 'none')
			{
				iSwapIndex = iCount;
				break;
			}
		}
		if(iSwapIndex == i)
		{
			oPrevRow = eval(document.getElementById("mySetup_Disc_row"+iPrevCount));
			iPrevCount = eval(iPrevCount);
			oPrevRow.cells[0].innerHTML = '<input type="hidden" id="mySetup_Disc_deleted1" name="mySetup_Disc_deleted1" value="0">&nbsp;';
		}
	}
	//Product reordering addition ends
	document.getElementById("mySetup_Disc_hdnProductId"+i).value = "";
	document.getElementById('mySetup_Disc_deleted'+i).value = 1;
	settotalnoofrows_Setup_Disc();
}

function mySetup_Tab1_2_deleteRow(module,i,image_path){
	rowCnt--;
	var tableName = document.getElementById('mySetup_Tab1_2');
	var prev = tableName.rows.length;

	//document.getElementById('proTab').deleteRow(i);
	document.getElementById("mySetup_Tab1_2_row"+i).style.display = 'none';

	//Added For product Reordering starts
	//image_path = document.getElementById("mySetup_Tab1_2_hidImagePath").value;
	iMax = tableName.rows.length;
	for(iCount=i;iCount>=1;iCount--)
	{
		if(document.getElementById("mySetup_Tab1_2_row"+iCount) && document.getElementById("mySetup_Tab1_2_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
			break;
		}
	}
	iPrevCount = iPrevRowIndex;
	oCurRow = eval(document.getElementById("mySetup_Tab1_2_row"+i));
	sTemp = oCurRow.cells[0].innerHTML;
	ibFound = sTemp.indexOf("down_layout.gif");

	if(i != 2 && ibFound == -1 && iPrevCount != 1)
	{
		oPrevRow = eval(document.getElementById("mySetup_Tab1_2_row"+iPrevCount));

		iPrevCount = eval(iPrevCount);
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_2_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab1_2_deleted'+iPrevCount+'" name="mySetup_Tab1_2_deleted'+iPrevCount+'" type="hidden" value="0">&nbsp;<a href="javascript:mySetup_Tab1_2_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	}
	else if(iPrevCount == 1)
	{
		iSwapIndex = i;
		for(iCount=i;iCount<=iMax-2;iCount++)
		{
			if(document.getElementById("mySetup_Tab1_2_row"+iCount) && document.getElementById("mySetup_Tab1_2_row"+iCount).style.display != 'none')
			{
				iSwapIndex = iCount;
				break;
			}
		}
		if(iSwapIndex == i)
		{
			oPrevRow = eval(document.getElementById("mySetup_Tab1_2_row"+iPrevCount));
			iPrevCount = eval(iPrevCount);
			oPrevRow.cells[0].innerHTML = '<input type="hidden" id="mySetup_Tab1_2_deleted1" name="mySetup_Disc_deleted1" value="0">&nbsp;';
		}
	}
	//Product reordering addition ends
	document.getElementById("mySetup_Tab1_2_hdnProductId"+i).value = "";
	//document.getElementById("productName"+i).value = "";
	document.getElementById('mySetup_Tab1_2_deleted'+i).value = 1;
}

/** Function for Product Re-Ordering Feature Code Addition Starts
 * It will be responsible for moving record up/down, 1 step at a time
 */
function mySetup_Disc_moveUpDown(sType,oModule,iIndex){
	var aFieldIds = Array('mySetup_Disc_hidtax_row_no','mySetup_Disc_productName','mySetup_Disc_subproduct_ids','mySetup_Disc_hdnProductId','mySetup_Disc_comment','mySetup_Disc_qty','mySetup_Disc_listPrice','mySetup_Disc_discount_type','mySetup_Disc_discount_percentage','mySetup_Disc_discount_amount','mySetup_Disc_tax1_percentage','mySetup_Disc_hidden_tax1_percentage','mySetup_Disc_popup_tax_row','mySetup_Disc_tax2_percentage','mySetup_Disc_hidden_tax2_percentage','mySetup_Disc_lineItemType','mySetup_Disc_uom');
	var aContentIds = Array('mySetup_Disc_qtyInStock','mySetup_Disc_netPrice','mySetup_Disc_subprod_names');
	var aOnClickHandlerIds = Array('mySetup_Disc_searchIcon');

	iIndex = eval(iIndex) + 1;
	var oTable = document.getElementById('mySetup_Disc');
	iMax = oTable.rows.length;
	iSwapIndex = 1;
	if(sType == 'UP')
	{
		for(iCount=iIndex-2;iCount>=1;iCount--)
		{
			if(document.getElementById("mySetup_Disc_row"+iCount))
			{
				if(document.getElementById("mySetup_Disc_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Disc_deleted'+iCount).value == 0)
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
			if(document.getElementById("mySetup_Disc_row"+iCount) && document.getElementById("mySetup_Disc_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Disc_deleted'+iCount).value == 0)
			{
				iSwapIndex = iCount;
				break;
			}
		}
		iSwapIndex += 1;
	}

	var oSwapRow = oTable.rows[iSwapIndex];

	iIndex -= 1;
	iSwapIndex -= 1;

	//alert(555);
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
}
function mySetup_Tab1_2_moveUpDown(sType,oModule,iIndex){
	var aFieldIds = Array('mySetup_Tab1_2_hidtax_row_no','mySetup_Tab1_2_productName','mySetup_Tab1_2_subproduct_ids','mySetup_Tab1_2_hdnProductId','mySetup_Tab1_2_comment','mySetup_Tab1_2_qty','mySetup_Tab1_2_listPrice','mySetup_Tab1_2_discount_type','mySetup_Tab1_2_discount_percentage','mySetup_Tab1_2_discount_amount','mySetup_Tab1_2_tax1_percentage','mySetup_Tab1_2_hidden_tax1_percentage','mySetup_Tab1_2_popup_tax_row','mySetup_Tab1_2_tax2_percentage','mySetup_Tab1_2_hidden_tax2_percentage','mySetup_Tab1_2_lineItemType','mySetup_Tab1_2_uom');
	var aContentIds = Array('mySetup_Tab1_2_qtyInStock','mySetup_Tab1_2_netPrice','mySetup_Tab1_2_subprod_names');
	var aOnClickHandlerIds = Array('mySetup_Tab1_2_searchIcon');

	iIndex = eval(iIndex) + 1;
	var oTable = document.getElementById('mySetup_Tab1_2');
	iMax = oTable.rows.length;
	iSwapIndex = 1;
	if(sType == 'UP')
	{
		for(iCount=iIndex-2;iCount>=1;iCount--)
		{
			if(document.getElementById("mySetup_Tab1_2_row"+iCount))
			{
				if(document.getElementById("mySetup_Tab1_2_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Tab1_2_deleted'+iCount).value == 0)
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
			if(document.getElementById("mySetup_Tab1_2_row"+iCount) && document.getElementById("mySetup_Tab1_2_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Tab1_2_deleted'+iCount).value == 0)
			{
				iSwapIndex = iCount;
				break;
			}
		}
		iSwapIndex += 1;
	}

	var oSwapRow = oTable.rows[iSwapIndex];

	iIndex -= 1;
	iSwapIndex -= 1;

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
function fnAdd_mySetup_Tab1_1(module,image_path){
	rowCnt++;

	var tableName = document.getElementById('mySetup_Tab1_1');
	var prev = tableName.rows.length;
	var count = eval(prev)-1;//As the table has two headers, we should reduce the count
	var row = tableName.insertRow(prev);
	row.id = "mySetup_Tab1_1_row"+count;
	row.style.verticalAlign = "top";

	var col1 = row.insertCell(0);
	var col2 = row.insertCell(1);
	var col3 = row.insertCell(2);
	var col4 = row.insertCell(3);

	/* Product Re-Ordering Feature Code Addition Starts */
	iMax = tableName.rows.length;
	for(iCount=1;iCount<=iMax-3;iCount++)
	{
		if(document.getElementById("mySetup_Tab1_1_row"+iCount) && document.getElementById("mySetup_Tab1_1_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
		}
	}
	iPrevCount = eval(iPrevRowIndex);
	var oPrevRow = tableName.rows[iPrevRowIndex+1];
	var delete_row_count=count;
	/* Product Re-Ordering Feature Code Addition ends */


	//Delete link
	col1.className = "crmTableRow small";
	col1.id = row.id+"_col1";
	col1.style.textAlign = "center";
	col1.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_1_deleteRow(\''+module+'\','+count+',\'themes/images/\')"><input id="mySetup_Tab1_1_deleted'+count+'" name="mySetup_Tab1_1_deleted'+count+'" type="hidden" value="0">';
	/* Product Re-Ordering Feature Code Addition Starts */
	if(iPrevCount != 1)
	{
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_1_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab1_1_deleted'+iPrevCount+'" name="mySetup_Tab1_1_deleted'+iPrevCount+'" type="hidden" value="0">';
	}
	else
	{
		//oPrevRow.cells[0].innerHTML = '<input id="mySetup_Tab1_1_deleted'+iPrevCount+'" name="mySetup_Tab1_1_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:mySetup_Tab1_1_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}

	/* Product Re-Ordering Feature Code Addition ends */


	col2.className = "crmTableRow small"
	col2.innerHTML='<input id="mySetup_Tab1_1_productprice_from'+count+'" name="mySetup_Tab1_1_productprice_from'+count+'" type="text" class=\'detailedViewTextBox\'" onBlur="this.className=\'detailedViewTextBox\';check_number(\'mySetup_Tab1_1_productprice_from'+count+'\');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className=\'detailedViewTextBoxOn\'"  value="" style="width:100px"/>';

	col3.className = "crmTableRow small"
	col3.innerHTML='<input id="mySetup_Tab1_1_productprice_to'+count+'" name="mySetup_Tab1_1_productprice_to'+count+'" type="text" class=\'detailedViewTextBox\'" onBlur="this.className=\'detailedViewTextBox\';check_number(\'mySetup_Tab1_1_productprice_to'+count+'\');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className=\'detailedViewTextBoxOn\'"  value="" style="width:100px"/>';

	col4.className = "crmTableRow small"
	col4.innerHTML='<img id="mySetup_Tab1_1_searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="mySetup_Tab1_1_PremiumList(this,\''+module+'\','+count+')" align="absmiddle">&nbsp;<div style="float:right">เงื่อนไข: <select id="campaign_fomula'+count+'" name="campaign_fomula'+count+'" style="width:80px"><option value="and">and</option><option value="or">or</option></select></div><div class="mySetup_Tab1_1_premium"></div><input type="hidden" name="total_row_premium'+count+'" id="total_row_premium'+count+'" value="">';

	return count;
}

function premiumSearch(module,i,image_path)
{

}

function deleteCampaignRow(module,i,image_path)
{
	rowCnt--;
	var tableName = document.getElementById('myformelement');
	var prev = tableName.rows.length;

	//document.getElementById('proTab').deleteRow(i);
	document.getElementById("row"+i).style.display = 'none';

	//Added For product Reordering starts
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
	//alert(iPrevRowIndex);
	iPrevCount = iPrevRowIndex;
	oCurRow = eval(document.getElementById("row"+i));
	sTemp = oCurRow.cells[0].innerHTML;
	ibFound = sTemp.indexOf("down_layout.gif");

	if(i != 2 && ibFound == -1 && iPrevCount != 1)
	{
		oPrevRow = eval(document.getElementById("row"+iPrevCount));

		iPrevCount = eval(iPrevCount);
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="deleteCampaignRow(\''+module+'\','+iPrevCount+')"><input id="deleted'+iPrevCount+'" name="deleted'+iPrevCount+'" type="hidden" value="0">';
		/*oPrevRow.cells[0].innerHTML = '&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';*/
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
	//calcTotal()
}
function delete_premium(id)
{
	jQuery("#"+id).empty();
}
//function return_premium(row,module,rowid)
function return_premium(premiumid ,productid,productname,uom,quantity,listprice,row_id,prefix,premiumnm,premiumcode){
	var $this = prefix+"_row"+row_id;
	var table_row = jQuery(window.opener.document).find('#' + $this + ' .mySetup_Tab1_1_premium > table').length;
	var last_id =table_row+1;
	//convert string to array
	var productArray = productid.split(',');
	var productnameArray = productname.split(',');
	var uomArray = uom.split(',');
	var premiumcodeArray = premiumcode.split(',');
	var quantityArray = quantity.split(',');
	var listpriceArray = listprice.split(',');

	//clear data in row
	//jQuery("#"+$this+" div").empty();

	//jQuery(window.opener.document).find('#' + $this+" div").empty();
	if(productArray.length>0){
		var tableid = 'table_'+$this+'_'+last_id;
		var lrecord = prefix+"_premiumrecord_"+row_id+"_"+last_id;
		data = '<table  width="100%" id="'+tableid+'"  border="0" cellpadding="5" cellspacing="0" class="crmTable">';
		data += '<tr><td colspan="6" class="detailedViewHeader"><img src="themes/images/delete.gif" border="0" onclick="delete_premium(\''+tableid+'\')">';
		data += '<input type="hidden" id="'+lrecord+'" name="'+lrecord+'" value="'+(productArray.length)+'"/>';
		data += '<strong> Premium ' +premiumid +' :: ' + premiumnm +'</strong> </td></tr>';
		data += '<tr class="lvtCol"><td>ลำดับ</td>';
		data += '<td class="lvtCol">รหัสสินค้าพรีเมี่ยม</td>';
		data += '<td class="lvtCol">ชื่อสินค้าพรีเมี่ยม</td>';
		data += '<td class="lvtCol">หน่วยการนับ</td>';
		data += '<td class="lvtCol">จำนวน</td>';
		data += '<td class="lvtCol">ราคาสินค้า</td>';
		data += '</tr>';
		j = 0;
		for (var i = 0; i < productArray.length; i++) {
			j++;
			var lpremium = prefix+"_premiumid_dtl_"+row_id+"_"+last_id+"_"+(j);
			var lpremiumcode = prefix+"_premiumcode_dtl_"+row_id+"_"+last_id+"_"+(j);
			var lproid = prefix+"_productid_dtl_"+row_id+"_"+last_id+"_"+(j);
			var lpronm = prefix+"_productname_dtl_"+row_id+"_"+last_id+"_"+j;
			var luom = prefix+"_uom_dtl_"+row_id+"_"+last_id+"_"+j;
			var lqty = prefix+"_qty_dtl_"+row_id+"_"+last_id+"_"+j;
			var lprice = prefix+"_price_dtl_"+row_id+"_"+last_id+"_"+j;
			data += '<tr><td class="crmTableRow small lineOnTop">'+(j)+'</td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="hidden" id="'+lpremium+'" name="'+lpremium+'" value="'+(premiumid)+'"/>';
			data += '<input type="hidden" id="'+lproid+'" name="'+lproid+'" class="small" style="width:70%" value="'+(productArray[i])+'" readonly="readonly" />';
			data += '<input type="text" id="'+lpremiumcode+'" name="'+lpremiumcode+'" class="small" style="width:70%" value="'+(premiumcodeArray[i])+'" readonly="readonly" /></td>';
			
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+lpronm+'" name="'+lpronm+'" class="small" style="width:70%" value="'+(productnameArray[i])+'" readonly="readonly" /></td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+luom+'" name="'+luom+'" class="small" style="width:70%" value="'+(uomArray[i])+'" readonly="readonly" /></td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+lqty+'" name="'+lqty+'" class="small" style="width:70%" value="'+(quantityArray[i])+'" readonly="readonly" /></td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+lprice+'" name="'+lprice+'" class="small" style="width:70%" value="0" readonly="readonly" /></td>';
			data += '</tr>';
		}
		data += '</table>';



		//jQuery("#"+$this+" .mySetup_Tab1_1_premium").append(data);
		jQuery(window.opener.document).find('#total_row_premium'+row_id ).val(last_id);
		jQuery(window.opener.document).find('#' + $this + ' .mySetup_Tab1_1_premium').append(data);
	}

}

function check_number(field) {//à¸„à¸µà¸¢à¹Œ .à¸—à¸¨à¸™à¸´à¸¢à¸¡à¹„à¸¡à¹ˆà¹„à¸"à¹‰
	var len, digit , chk;
	if(document.getElementById(field).value == " "){
		alert("Please Insert Number Only!..");
		len=0;
	}else{
		len = document.getElementById(field).value.length;
	}
	for(var i=0 ; i<len ; i++){
		digit = document.getElementById(field).value.charAt(i)
		//alert(digit);
		if((digit >="0" && digit <="9") || digit=="." ){
		}else{
			chk='1';
		}
	}
	if(chk=="1"){
		alert("Please Insert Number Only!..");
		document.getElementById(field).value="0";
	}	
}

function return_premium_tab2(premiumid ,productid,productname,uom,quantity,listprice,row_id,prefix,premiumnm,premiumnmcode){
	var $this = prefix+"_row"+row_id;
	var table_row = jQuery(window.opener.document).find('#' + $this + ' .mySetup_Tab2_premium > table').length;
	var last_id =table_row+1;
	//convert string to array
	var productArray = productid.split(',');
	var productnameArray = productname.split(',');
	var premiumnmcodeArray = premiumnmcode.split(',');
	var uomArray = uom.split(',');
	var quantityArray = quantity.split(',');
	var listpriceArray = listprice.split(',');

	//clear data in row
	//jQuery("#"+$this+" div").empty();

	//jQuery(window.opener.document).find('#' + $this+" div").empty();
	if(productArray.length>0){
		var tableid = 'table_'+$this+'_'+last_id;
		var lrecord = prefix+"_premiumrecord_"+row_id+"_"+last_id;
		data = '<table  width="100%" id="'+tableid+'"  border="0" cellpadding="5" cellspacing="0" class="crmTable">';
		data += '<tr><td colspan="6" class="detailedViewHeader"><img src="themes/images/delete.gif" border="0" onclick="delete_premium(\''+tableid+'\')">';
		data += '<input type="hidden" id="'+lrecord+'" name="'+lrecord+'" value="'+(productArray.length)+'"/>';
		data += '<strong> Premium ' +premiumid +' :: ' + premiumnm +'</strong> </td></tr>';
		data += '<tr class="lvtCol"><td>ลำดับ</td>';
		data += '<td class="lvtCol">รหัสสินค้าพรีเมี่ยม</td>';
		data += '<td class="lvtCol">ชื่อสินค้าพรีเมี่ยม</td>';
		data += '<td class="lvtCol">หน่วยการนับ</td>';
		data += '<td class="lvtCol">จำนวน</td>';
		data += '<td class="lvtCol">ราคาสินค้า</td>';
		data += '</tr>';
		j = 0;
		for (var i = 0; i < productArray.length; i++) {
			j++;
			var lpremium = prefix+"_premiumid_dtl_"+row_id+"_"+last_id+"_"+(j);
			var lproid = prefix+"_productid_dtl_"+row_id+"_"+last_id+"_"+(j);
			var lpremiumcode = prefix+"_premiumcode_dtl_"+row_id+"_"+last_id+"_"+(j);
			var lpronm = prefix+"_productname_dtl_"+row_id+"_"+last_id+"_"+j;
			var luom = prefix+"_uom_dtl_"+row_id+"_"+last_id+"_"+j;
			var lqty = prefix+"_qty_dtl_"+row_id+"_"+last_id+"_"+j;
			var lprice = prefix+"_price_dtl_"+row_id+"_"+last_id+"_"+j;
			data += '<tr><td class="crmTableRow small lineOnTop">'+(j)+'</td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="hidden" id="'+lpremium+'" name="'+lpremium+'" value="'+(premiumid)+'"/>';
			data += '<input type="hidden" id="'+lproid+'" name="'+lproid+'" class="small" style="width:70%" value="'+(productArray[i])+'" readonly="readonly" />';
			data += '<input type="text" id="'+lpremiumcode+'" name="'+lpremiumcode+'" class="small" style="width:70%" value="'+(premiumnmcodeArray[i])+'" readonly="readonly" /></td>';


			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+lpronm+'" name="'+lpronm+'" class="small" style="width:70%" value="'+(productnameArray[i])+'" readonly="readonly" /></td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+luom+'" name="'+luom+'" class="small" style="width:70%" value="'+(uomArray[i])+'" readonly="readonly" /></td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+lqty+'" name="'+lqty+'" class="small" style="width:70%" value="'+(quantityArray[i])+'" readonly="readonly" /></td>';
			data += '<td class="crmTableRow small lineOnTop">';
			data += '<input type="text" id="'+lprice+'" name="'+lprice+'" class="small" style="width:70%" value="'+(listpriceArray[i])+'" readonly="readonly" /></td>';
			data += '</tr>';
		}
		data += '</table>';



		//jQuery("#"+$this+" .mySetup_Tab1_1_premium").append(data);
		jQuery(window.opener.document).find('#total_row_mySetup_Tab2_'+row_id ).val(last_id);
		jQuery(window.opener.document).find('#' + $this + ' .mySetup_Tab2_premium').append(data);
	}

}