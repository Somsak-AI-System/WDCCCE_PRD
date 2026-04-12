jQuery(document).ready(function() {
});

function mySetup_Tab1_2_productPickList(currObj,module, row_no) {
	var trObj=currObj.parentNode.parentNode
	var rowId = row_no;
	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';
	var url="";
	url="plugin/Campaigns/search_pop_mySetup_Tab1_2.php?module=Promotion&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId+"&parent_module=Products&return_module="+module;
	window.open(url,"productWin","width=900,height=660,resizable=0,scrollbars=0,status=1,top=0,left=200");
}

function fnAdd_mySetup_Tab1_2(module,image_path){
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
	var col4 = row.insertCell(3);
	var col5 = row.insertCell(4);
	var col6 = row.insertCell(5);
	var col7 = row.insertCell(6);
	var col8 = row.insertCell(7);
	var col9 = row.insertCell(8);
	var col10 = row.insertCell(9);
	var col11 = row.insertCell(10);
	var col12 = row.insertCell(11);

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
	col2.className = "crmTableRow small text-middle"
	col2.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small"><input id="mySetup_Tab1_2_promotionname'+count+'" name="mySetup_Tab1_2_promotionname'+count+'" class="small" style="width: 85%;" value="" readonly="readonly" type="text">'+
						'<input id="mySetup_Tab1_2_hdnpromotionid'+count+'" name="mySetup_Tab1_2_hdnpromotionid'+count+'" value="" type="hidden"><input type="hidden" id="mySetup_Tab1_2_lineItemType'+count+'" name="mySetup_Tab1_2_lineItemType'+count+'" value="Promotion" />'+
						' &nbsp;<img id="mySetup_Tab1_2_searchIcon'+count+'" title="Products" src="themes/images/products.gif" style="cursor: pointer;" onclick="mySetup_Tab1_2_productPickList(this,\''+module+'\','+count+')" align="absmiddle">'+
						'</td></tr>';
	col3.className = "crmTableRow small text-middle"
	//col3.innerHTML='<input id="mySetup_Tab1_2_uom'+count+'" name="mySetup_Tab1_2_uom'+count+'" type="text"  style="width:50px" class=detailedViewTextBox onFocus="this.className=\'detailedViewTextBoxOn\';" onBlur="this.className=\'detailedViewTextBox\';"  value="" /><input id="mySetup_Tab1_2_qty'+count+'" name="mySetup_Tab1_2_qty'+count+'" type="hidden" class="detailedViewTextBox"  style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="settotalnoofrows_mySetup_Tab1_2();" value=""/><input id="mySetup_Tab1_2_listPrice'+count+'" name="mySetup_Tab1_2_listPrice'+count+'" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" />';
	col3.innerHTML='<span id="promotiontype'+count+'"></span>';
	
	col4.className = "crmTableRow small text-middle"
	col4.style.textAlign = "center";
	col4.innerHTML='<span id="startdate'+count+'"></span>';

	col5.className = "crmTableRow small text-middle"
	col5.style.textAlign = "center";
	col5.innerHTML='<span id="enddate'+count+'"></span>';

	col6.className = "crmTableRow small text-middle"
	col6.style.textAlign = "left";
	col6.innerHTML='<span id="status'+count+'"></span>';

	col7.className = "crmTableRow small text-middle"
	col7.style.textAlign = "right";
	col7.innerHTML='<span id="budgetcost'+count+'"></span>';

	col8.className = "crmTableRow small text-middle"
	col8.style.textAlign = "right";
	col8.innerHTML='<span id="actualcost'+count+'"></span>';

	col9.className = "crmTableRow small text-middle"
	col9.style.textAlign = "right";
	col9.innerHTML='<span id="expectedaudience'+count+'"></span>';

	col10.className = "crmTableRow small text-middle"
	col10.style.textAlign = "right";
	col10.innerHTML='<span id="actualaudience'+count+'"></span>';

	col11.className = "crmTableRow small text-middle"
	col11.style.textAlign = "right";
	col11.innerHTML='<span id="expectedrevenue'+count+'"></span>';

	col12.className = "crmTableRow small text-middle"
	col12.style.textAlign = "right";
	col12.innerHTML='<span id="actualrevenue'+count+'"></span>';
	
	settotalnoofrows_mySetup_Tab1_2();
	return count;
}

function settotalnoofrows_mySetup_Tab1_2() {
	var max_row_count = document.getElementById('mySetup_Tab1_2').rows.length;
        max_row_count = eval(max_row_count)-2;
	//set the total number of Promotion
	document.EditView.mySetup_Tab1_2_totalPromotionCount.value = max_row_count;
}

function mySetup_Tab1_2_deleteRow(module,i,image_path){
	rowCnt--;
	var tableName = document.getElementById('mySetup_Tab1_2');
	var prev = tableName.rows.length;

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
	document.getElementById("mySetup_Tab1_2_hdnpromotionid"+i).value = "";
	document.getElementById('mySetup_Tab1_2_deleted'+i).value = 1;

	//var max_row_count = document.getElementById('mySetup_Tab1_2').rows.length;
    //max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length
    
    set_total_campaigns();
}

function set_total_campaigns(){
	var tableName = document.getElementById('mySetup_Tab1_2');
	var prev = tableName.rows.length;
	iMax = tableName.rows.length;

    var v_budgetcost = 0;
    var v_actualcost = 0;
    var v_expectedaudience = 0 ;
    var v_actualaudience = 0 ;
    var v_expectedrevenue = 0 ;
    var v_actualrevenue = 0 ;
    for(var i=1;i<=iMax;i++)
    {
        rowId = i;
        if(document.getElementById("mySetup_Tab1_2_row"+rowId) && document.getElementById("mySetup_Tab1_2_row"+rowId).style.display != 'none')
		{
	        budgetcost = document.getElementById('budgetcost'+rowId).innerHTML;
	        if(budgetcost !== ''){
	            var new_budgetcost = budgetcost.replace(/,/g, '');
	            v_budgetcost += parseFloat(new_budgetcost);
	        }
	        actualcost = document.getElementById('actualcost'+rowId).innerHTML;
	        if(actualcost !== ''){
	            var new_actualcost = actualcost.replace(/,/g, '');
	            v_actualcost += parseFloat(new_actualcost);
	        }
	        expectedaudience = document.getElementById('expectedaudience'+rowId).innerHTML;
            if(expectedaudience !== ''){
                var new_expectedaudience = expectedaudience.replace(/,/g, '');
                v_expectedaudience += parseFloat(new_expectedaudience);
            }
            actualaudience = document.getElementById('actualaudience'+rowId).innerHTML;
            if(actualaudience !== ''){
                var new_actualaudience = actualaudience.replace(/,/g, '');
                v_actualaudience += parseFloat(new_actualaudience);
            }
            expectedrevenue = document.getElementById('expectedrevenue'+rowId).innerHTML;
            if(expectedrevenue !== ''){
                var new_expectedrevenue = expectedrevenue.replace(/,/g, '');
                v_expectedrevenue += parseFloat(new_expectedrevenue);
            }
            actualrevenue = document.getElementById('actualrevenue'+rowId).innerHTML;
            if(actualrevenue !== ''){
                var new_actualrevenue = actualrevenue.replace(/,/g, '');
                v_actualrevenue += parseFloat(new_actualrevenue);
            }
        }     
    }

    document.getElementById("totalbudgetcost").innerHTML = numberWithCommas(roundPriceValue(v_budgetcost));
    document.getElementById("totalactualcost").innerHTML = numberWithCommas(roundPriceValue(v_actualcost));
    document.getElementById("totalexpectedaudience").innerHTML = numberWithCommas(roundPriceValue(v_expectedaudience));
    document.getElementById("totalactualaudience").innerHTML = numberWithCommas(roundPriceValue(v_actualaudience));
    document.getElementById("totalexpectedrevenue").innerHTML = numberWithCommas(roundPriceValue(v_expectedrevenue));
    document.getElementById("totalactualrevenue").innerHTML = numberWithCommas(roundPriceValue(v_actualrevenue));
}

function mySetup_Tab1_2_moveUpDown(sType,oModule,iIndex){
	
	var aFieldIds = Array('mySetup_Tab1_2_promotionname','mySetup_Tab1_2_hdnpromotionid','promotiontype','startdate','enddate','status','budgetcost','actualcost','expectedaudience','actualaudience','expectedrevenue','actualrevenue');
	
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
			//Value
			sTemp = document.getElementById(sId).value;
			document.getElementById(sId).value = document.getElementById(sSwapId).value;
			document.getElementById(sSwapId).value = sTemp;
			//Text
			TTemp = document.getElementById(sId).innerHTML;
			document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
			document.getElementById(sSwapId).innerHTML = TTemp;
		}
	}
}

/*Select All*/
function InventorySelectAll_mySetup_Tab(mod,image_pth){
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
				var prod_array = JSON.parse($('popup_promotion_'+c).attributes['vt_prod_arr'].nodeValue);
				
				var row_id = prod_array['row_id'];
				var promotionid = prod_array['entityid'];
				var promotion_name = prod_array['promotion_name'];
				var set_tab = prod_array['set_tab'];
				var startdate = prod_array['startdate'];
				var enddate = prod_array['enddate'];
				var promotion_status = prod_array['promotion_status'];
				var budget_cost = prod_array['budget_cost'];
				var actual_cost = prod_array['actual_cost'];
				var expected_audience = prod_array['expected_audience'];
				var actual_audience = prod_array['actual_audience'];
				var expected_revenue = prod_array['expected_revenue'];
				var actual_revenue = prod_array['actual_revenue'];
				
				set_return_inventory_mySetup_Tab(row_id,promotionid,promotion_name,set_tab,startdate,enddate,promotion_status,budget_cost,actual_cost,expected_audience,actual_audience,expected_revenue,actual_revenue);

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
					var prod_array = JSON.parse($('popup_promotion_'+c).attributes['vt_prod_arr'].nodeValue);
					var promotionid = prod_array['entityid'];
					var promotion_name = prod_array['promotion_name'];
					var set_tab = prod_array['set_tab'];
					var startdate = prod_array['startdate'];
					var enddate = prod_array['enddate'];
					var promotion_status = prod_array['promotion_status'];
					var budget_cost = prod_array['budget_cost'];
					var actual_cost = prod_array['actual_cost'];
					var expected_audience = prod_array['expected_audience'];
					var actual_audience = prod_array['actual_audience'];
					var expected_revenue = prod_array['expected_revenue'];
					var actual_revenue = prod_array['actual_revenue'];

					if(y>0) {
						var row_id = window.opener.fnAdd_mySetup_Tab1_2(mod,image_pth);
					} else {
						var row_id = prod_array['row_id'];
					}
					
					set_return_inventory_mySetup_Tab(row_id,promotionid,promotion_name,set_tab,startdate,enddate,promotion_status,budget_cost,actual_cost,expected_audience,actual_audience,expected_revenue,actual_revenue);
					
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

function check_save(){
	var max_row_count = document.getElementById('mySetup_Tab1_2').rows.length;
	max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from table row length
	if(max_row_count == 0){
		alert(alert_arr.NO_LINE_ITEM_SELECTED);
		return false;
	}
	for (var i=1;i<=max_row_count;i++){
		if(document.getElementById("mySetup_Tab1_2_row"+i).style.display != 'none'){
			if (!emptyCheck("mySetup_Tab1_2_promotionname"+i,alert_arr.LINE_ITEM,"text")) return false;
		}
	}

	if(!formValidate()){
		return false
	}else{

	}
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