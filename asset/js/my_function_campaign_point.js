$(document).ready(function() {
	
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
	
	

	jQuery('.toggle').hide();
	jQuery('input[name=stpromotion]').live('change',  function() { 

		jQuery('.toggle').hide('slow');
		jQuery(this).parent().next('.toggle').toggle('slow');
	});
	

});

function fnAdd_mySetup_Tab2(module,image_path){
	rowCnt++;

	var tableName = document.getElementById('mySetup_Tab2');
	var prev = tableName.rows.length;
	var count = eval(prev)-1;//As the table has two headers, we should reduce the count
	var row = tableName.insertRow(prev);
	row.id = "mySetup_Tab2_row"+count;
	row.style.verticalAlign = "top";
	
	var colone = row.insertCell(0);
	var coltwo = row.insertCell(1);
	
	var colthree = row.insertCell(2);
	var colfour = row.insertCell(3);
	var colfive = row.insertCell(4);

	
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
	/* Product Re-Ordering Feature Code Addition ends */
	
	
	//Delete link
	colone.className = "crmTableRow small";
	colone.id = row.id+"_col1";
	colone.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab2_deleteCampaignRow(\''+module+'\','+count+',\'themes/images/\')"><input id="mySetup_Tab2_deleted'+count+'" name="mySetup_Tab2_deleted'+count+'" type="hidden" value="0">';
	/*colone.innerHTML='<br/><br/>&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';*/
	/* Product Re-Ordering Feature Code Addition Starts */
	if(iPrevCount != 1)
	{
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab2_deleteCampaignRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab2_deleted'+iPrevCount+'" name="mySetup_Tab2_deleted'+iPrevCount+'" type="hidden" value="0">';
		/*oPrevRow.cells[0].innerHTML = '<br/><br/>&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';*/
	}
	else
	{
		oPrevRow.cells[0].innerHTML = '<input id="mySetup_Tab2_deleted'+iPrevCount+'" name="mySetup_Tab2_deleted'+iPrevCount+'" type="hidden" value="0">';
		/*oPrevRow.cells[0].innerHTML = '<br/><br/><a href="javascript:moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';*/
	}
	/* Product Re-Ordering Feature Code Addition ends */
	
	
	coltwo.className = "crmTableRow small"
	coltwo.innerHTML='<input id="mySetup_Tab2_campaign_from'+count+'" name="mySetup_Tab2_campaign_from'+count+'" type="text" class=\'detailedViewTextBox\'" onBlur="this.className=\'detailedViewTextBox\';check_number(\'mySetup_Tab2_campaign_from'+count+'\');" onFocus="this.className=\'detailedViewTextBoxOn\'"  value="" style="width:100px"/>';
	
	colthree.className = "crmTableRow small"
	colthree.innerHTML='<input id="mySetup_Tab2_campaign_to'+count+'" name="mySetup_Tab2_campaign_to'+count+'" type="text" class=\'detailedViewTextBox\'" onBlur="this.className=\'detailedViewTextBox\';check_number(\'mySetup_Tab2_campaign_to'+count+'\');" onFocus="this.className=\'detailedViewTextBoxOn\'"  value="" style="width:100px"/>';
	
	colfour.className = "crmTableRow small"
	colfour.innerHTML ='<select id="mySetup_Tab2_campaign_fomula'+count+'" name="mySetup_Tab2_campaign_fomula'+count+'"style="width:80px"><option value="plus">+</option><option value="multiply">*</option></select>'
	colfour.innerHTML += ''
	colfour.innerHTML += ''
	colfour.innerHTML += ''

	
	colfive.className = "crmTableRow small"
	colfive.innerHTML='<input id="mySetup_Tab2_campaign_parameter'+count+'" name="mySetup_Tab2_campaign_parameter'+count+'" type="text" class=\'detailedViewTextBox\'" onBlur="this.className=\'detailedViewTextBox\';check_number(\'mySetup_Tab2_campaign_parameter'+count+'\');" onFocus="this.className=\'detailedViewTextBoxOn\'"  value="" style="width:100px"/>';
	settotalnoofrows_mySetup_Tab2();
	return count;
}
function settotalnoofrows_mySetup_Tab2() {
	//alert(555);
	var max_row_count = document.getElementById('mySetup_Tab2').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.mySetup_Tab2_Count.value = max_row_count;
	//cal_Setup_Disc();
}
function mySetup_Tab2_deleteCampaignRow(module,i,image_path)
{
	rowCnt--;
	var tableName = document.getElementById('mySetup_Tab2');
	var prev = tableName.rows.length;

//	document.getElementById('proTab').deleteRow(i);
	document.getElementById("mySetup_Tab2_row"+i).style.display = 'none';

// Added For product Reordering starts
	//image_path = document.getElementById("hidImagePath").value;
	iMax = tableName.rows.length;
	for(iCount=i;iCount>=1;iCount--)
	{
		if(document.getElementById("mySetup_Tab2_row"+iCount) && document.getElementById("mySetup_Tab2_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
			break;
		}
	}
	//alert(iPrevRowIndex);
	iPrevCount = iPrevRowIndex;
	oCurRow = eval(document.getElementById("mySetup_Tab2_row"+i));
	sTemp = oCurRow.cells[0].innerHTML;
	ibFound = sTemp.indexOf("down_layout.gif");
	
	if(i != 2 && ibFound == -1 && iPrevCount != 1)
	{
		oPrevRow = eval(document.getElementById("row"+iPrevCount));
			
		iPrevCount = eval(iPrevCount);
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab2_deleteCampaignRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab2_deleted'+iPrevCount+'" name="mySetup_Tab2_deleted'+iPrevCount+'" type="hidden" value="0">';
		/*oPrevRow.cells[0].innerHTML = '&nbsp;<a href="javascript:moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';*/
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
			oPrevRow.cells[0].innerHTML = '<input type="hidden" id="mySetup_Tab2_deleted1" name="mySetup_Tab2_deleted1" value="0">&nbsp;'; 
		}
	}
// Product reordering addition ends
	//document.getElementById("mySetup_Tab2_hdnProductId"+i).value = "";
	//document.getElementById("productName"+i).value = "";
	document.getElementById('mySetup_Tab2_deleted'+i).value = 1;

	//calcTotal()
}

function fnAdd_mySetup_Tab1(module,image_path){
	//alert(555);
	rowCnt++;
	var tableName = document.getElementById('mySetup_Tab1');
	var prev = tableName.rows.length;
	var count = eval(prev)-1;//As the table has two headers, we should reduce the count
	var row = tableName.insertRow(prev);
	row.id = "mySetup_Tab1_row"+count;
	row.style.verticalAlign = "top";

	var col1 = row.insertCell(0);
	var col2 = row.insertCell(1);
	var col3 = row.insertCell(2);
	//var col4 = row.insertCell(3);
	//var col5 = row.insertCell(4);
	/* Product Re-Ordering Feature Code Addition Starts */
	iMax = tableName.rows.length;
	for(iCount=1;iCount<=iMax-3;iCount++)
	{
		if(document.getElementById("mySetup_Tab1_row"+iCount) && document.getElementById("mySetup_Tab1_row"+iCount).style.display != 'none')
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
	col1.innerHTML='<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_deleteRow(\''+module+'\','+count+',\'themes/images/\')"><input id="mySetup_Tab1_deleted'+count+'" name="mySetup_Tab1_deleted'+count+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Tab1_moveUpDown(\'UP\',\''+module+'\','+count+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	/* Product Re-Ordering Feature Code Addition Starts */
	if(iPrevCount != 1)
	{
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab1_deleted'+iPrevCount+'" name="mySetup_Tab1_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/>&nbsp;<a href="javascript:mySetup_Tab1_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:mySetup_Tab1_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	else
	{
		oPrevRow.cells[0].innerHTML = '<input id="mySetup_Tab1_deleted'+iPrevCount+'" name="mySetup_Tab1_deleted'+iPrevCount+'" type="hidden" value="0"><br/><br/><a href="javascript:mySetup_Tab1_moveUpDown(\'DOWN\',\''+module+'\','+iPrevCount+')" title="Move Downward"><img src="themes/images/down_layout.gif" border="0"></a>';
	}
	/* Product Re-Ordering Feature Code Addition ends */

	//Product Name with Popup image to select product
	col2.className = "crmTableRow small"
	col2.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small"><input id="mySetup_Tab1_productName'+count+'" name="mySetup_Tab1_productName'+count+'" class="small" style="width: 70%;" value="" readonly="readonly" type="text">'+
						'<input id="mySetup_Tab1_hdnProductId'+count+'" name="mySetup_Tab1_hdnProductId'+count+'" value="" type="hidden"><input type="hidden" id="mySetup_Tab1_lineItemType'+count+'" name="mySetup_Tab1_lineItemType'+count+'" value="Products" />'+
						'&nbsp;<img id="mySetup_Tab1_searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="mySetup_Tab1_productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
						'</td></tr><tr><td class="small"><input type="hidden" value="" id="mySetup_Tab1_subproduct_ids'+count+'" name="mySetup_Tab1_subproduct_ids'+count+'" /><span id="mySetup_Tab1_subprod_names'+count+'" name="mySetup_Tab1_subprod_names'+count+'" style="color:#C0C0C0;font-style:italic;"> </span>'+
						'</td></tr><tr><td class="small" id="mySetup_Tab1_setComment'+count+'"><textarea id="mySetup_Tab1_comment'+count+'" name="mySetup_Tab1_comment'+count+'" class=small style="width:70%;height:40px"></textarea><img src="themes/images/clear_field.gif" onClick="getObj(\'comment'+count+'\').value=\'\'"; style="cursor:pointer;" /></td></tr></tbody></table>';

	col3.className = "crmTableRow small"
	col3.innerHTML='<input id="mySetup_Tab1_qty'+count+'" name="mySetup_Tab1_qty'+count+'" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="check_number(\'mySetup_Tab1_qty'+count+'\');settotalnoofrows_mySetup_Tab1();" value=""/><input id="mySetup_Tab1_uom'+count+'" name="mySetup_Tab1_uom'+count+'" type="hidden"  style="width:50px" class=detailedViewTextBox onFocus="this.className=\'detailedViewTextBoxOn\';" onBlur="this.className=\'detailedViewTextBox\';"  value="" /><input id="mySetup_Tab1_listPrice'+count+'" name="mySetup_Tab1_listPrice'+count+'" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" />';
	settotalnoofrows_mySetup_Tab1();
	return count;
}
function settotalnoofrows_mySetup_Tab1() {
	//alert(555);
	var max_row_count = document.getElementById('mySetup_Tab1').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.mySetup_Tab1_Count.value = max_row_count;
	//cal_Setup_Disc();
}
function mySetup_Tab1_deleteRow(module,i,image_path){//alert(555);
	rowCnt--;
	var tableName = document.getElementById('mySetup_Tab1');
	var prev = tableName.rows.length;

//	document.getElementById('proTab').deleteRow(i);
	document.getElementById("mySetup_Tab1_row"+i).style.display = 'none';

// Added For product Reordering starts
	//image_path = document.getElementById("mySetup_Tab1_hidImagePath").value;alert(555);
	iMax = tableName.rows.length;
	for(iCount=i;iCount>=1;iCount--)
	{
		if(document.getElementById("mySetup_Tab1_row"+iCount) && document.getElementById("mySetup_Tab1_row"+iCount).style.display != 'none')
		{
			iPrevRowIndex = iCount;
			break;
		}
	}
	iPrevCount = iPrevRowIndex;
	oCurRow = eval(document.getElementById("mySetup_Tab1_row"+i));
	sTemp = oCurRow.cells[0].innerHTML;
	ibFound = sTemp.indexOf("down_layout.gif");

	if(i != 2 && ibFound == -1 && iPrevCount != 1)
	{
		oPrevRow = eval(document.getElementById("mySetup_Tab1_row"+iPrevCount));

		iPrevCount = eval(iPrevCount);
		oPrevRow.cells[0].innerHTML = '<img src="themes/images/delete.gif" border="0" onclick="mySetup_Tab1_deleteRow(\''+module+'\','+iPrevCount+')"><input id="mySetup_Tab1_deleted'+iPrevCount+'" name="mySetup_Tab1_deleted'+iPrevCount+'" type="hidden" value="0">&nbsp;<a href="javascript:mySetup_Tab1_moveUpDown(\'UP\',\''+module+'\','+iPrevCount+')" title="Move Upward"><img src="themes/images/up_layout.gif" border="0"></a>';
	}
	else if(iPrevCount == 1)
	{
		iSwapIndex = i;
		for(iCount=i;iCount<=iMax-2;iCount++)
		{
			if(document.getElementById("mySetup_Tab1_row"+iCount) && document.getElementById("mySetup_Tab1_row"+iCount).style.display != 'none')
			{
				iSwapIndex = iCount;
				break;
			}
		}
		if(iSwapIndex == i)
		{
			oPrevRow = eval(document.getElementById("mySetup_Tab1_row"+iPrevCount));
			iPrevCount = eval(iPrevCount);
			oPrevRow.cells[0].innerHTML = '<input type="hidden" id="mySetup_Tab1_deleted1" name="mySetup_Disc_deleted1" value="0">&nbsp;';
		}
	}
// Product reordering addition ends
	document.getElementById("mySetup_Tab1_hdnProductId"+i).value = "";
	//document.getElementById("productName"+i).value = "";
	document.getElementById('mySetup_Tab1_deleted'+i).value = 1;
}
function mySetup_Tab1_moveUpDown(sType,oModule,iIndex){
	var aFieldIds = Array('mySetup_Tab1_hidtax_row_no','mySetup_Tab1_productName','mySetup_Tab1_subproduct_ids','mySetup_Tab1_hdnProductId','mySetup_Tab1_comment','mySetup_Tab1_qty','mySetup_Tab1_listPrice','mySetup_Tab1_discount_type','mySetup_Tab1_discount_percentage','mySetup_Tab1_discount_amount','mySetup_Tab1_tax1_percentage','mySetup_Tab1_hidden_tax1_percentage','mySetup_Tab1_popup_tax_row','mySetup_Tab1_tax2_percentage','mySetup_Tab1_hidden_tax2_percentage','mySetup_Tab1_lineItemType','mySetup_Tab1_uom');
	var aContentIds = Array('mySetup_Tab1_qtyInStock','mySetup_Tab1_netPrice','mySetup_Tab1_subprod_names');
	var aOnClickHandlerIds = Array('mySetup_Tab1_searchIcon');

	iIndex = eval(iIndex) + 1;
	var oTable = document.getElementById('mySetup_Tab1');
	iMax = oTable.rows.length;
	iSwapIndex = 1;
	if(sType == 'UP')
	{
		for(iCount=iIndex-2;iCount>=1;iCount--)
		{
			if(document.getElementById("mySetup_Tab1_row"+iCount))
			{
				if(document.getElementById("mySetup_Tab1_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Tab1_deleted'+iCount).value == 0)
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
			if(document.getElementById("mySetup_Tab1_row"+iCount) && document.getElementById("mySetup_Tab1_row"+iCount).style.display != 'none' && document.getElementById('mySetup_Tab1_deleted'+iCount).value == 0)
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
function mySetup_Tab1_productPickList(currObj,module, row_no) {
	var trObj=currObj.parentNode.parentNode

	var rowId = row_no;//alert(rowId);

	//var currencyid = document.getElementById("inventory_currency").value;
	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var url="";
	//alert(5);
	//alert(document.EditView.record.value);
   // if(document.getElementsByName("parent_id").length != 0)

    //record_id= document.EditView.account_id.value;
	//cf_1603= document.EditView.cf_1603.value;
	//crm_id= document.EditView.record.value;
	url="./search_pop_product_mySetup_Tab1.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&parent_module=Products&return_module="+module;
	//alert(url);
	window.open(url,"productWin","width=900,height=625,resizable=0,scrollbars=0,status=1,top=0,left=200");
}
function InventorySelectAll_mySetup_Tab1(mod,image_pth){
	//alert("555");
    if(document.selectall.selected_id != undefined)
    {//alert("555");
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
					set_return_inventory_mySetup_Tab1(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
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
			//alert(x);
			for(i = 0; i < x ; i++) {
				if(document.selectall.selected_id[i].checked) {//alert(row_id);
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
						var row_id = window.opener.fnAdd_mySetup_Tab1(mod,image_pth);
					} else {
						var row_id = prod_array['rowid'];
					}
					
					if(mod!='PurchaseOrder') {
						//var qtyinstk = prod_array['mySetup_Disc_qtyinstk'];
						set_return_inventory_mySetup_Tab1(prod_id,prod_name,unit_price,qtyinstk,taxstring,parseInt(row_id),desc,subprod_ids,uom);
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
function check_number(field) {//คีย์ .ทศนิยมไม่ไ�"้
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
function check_save(){
	var max_row_count = document.getElementById('mySetup_Tab1').rows.length;
	max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from table row length
	if(max_row_count == 0){
		//alert(alert_arr.NO_LINE_ITEM_SELECTED);
		//return false;
	}
	/*for (var i=1;i<=max_row_count;i++){
		if(document.getElementById("mySetup_Tab1_deleted"+i).value == 1)
			continue;
		if (!emptyCheck("mySetup_Tab1_productName"+i,alert_arr.LINE_ITEM,"text")) return false
		if (!emptyCheck("mySetup_Tab1_qty"+i,"Qty","text")) return false
		if (!numValidate("mySetup_Tab1_qty"+i,"Qty","any")) return false
		if (!numConstComp("mySetup_Tab1_qty"+i,"Qty","G","0")) return false
	}*/
	
	var max_row_count = document.getElementById('mySetup_Tab2').rows.length;
	max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from table row length
	if(max_row_count == 0){
		//alert(alert_arr.NO_LINE_ITEM_SELECTED);
		//return false;
	}
	for (var i=1;i<=max_row_count;i++){
		if(document.getElementById("mySetup_Tab2_deleted"+i).value == 1)
			continue;
		if (!emptyCheck("mySetup_Tab2_campaign_from"+i,"From","text")) return false
		if (!numValidate("mySetup_Tab2_campaign_from"+i,"From","any")) return false
		if (!numConstComp("mySetup_Tab2_campaign_from"+i,"From","G","0")) return false
		
		if (!emptyCheck("mySetup_Tab2_campaign_to"+i,"To","text")) return false
		if (!numValidate("mySetup_Tab2_campaign_to"+i,"To","any")) return false
		if (!numConstComp("mySetup_Tab2_campaign_to"+i,"To","G","0")) return false
		
		if (!emptyCheck("mySetup_Tab2_campaign_parameter"+i,"Parameter","text")) return false
		if (!numValidate("mySetup_Tab2_campaign_parameter"+i,"Parameter","any")) return false
		if (!numConstComp("mySetup_Tab2_campaign_parameter"+i,"Parameter","G","0")) return false
	}
	
	if(!formValidate()){//alert(555);
		return false
	}	
}