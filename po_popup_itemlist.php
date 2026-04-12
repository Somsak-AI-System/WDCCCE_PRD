<?php
session_start();
include("config.inc.php");
require_once("library/dbconfig.php");
require_once("library/myFunction.php");
require_once("library/generate_MYSQL.php");
global $generate;
$generate = new generate($dbconfig, "DB");

$purchasesorderid = $_REQUEST['purchasesorderid'];
$rowId = $_REQUEST['rowId'];
?>
<style>
a {
    cursor: pointer;
}
</style>

<table>
    <tr>
        <td colspan="3">Search By</td>
    </tr>
    <tr>
        <td>
            <select name="search_field" id="search_field" class="detailedViewTextBox" style="width:93px;">
			    <option value="">-- ค้นหาโดย --</option>
                <option value="sequence_no">ลำดับที่</option>
                <option value="product_code_crm">รหัสสินค้า</option>
                <option value="product_brand">ยี่ห้อสินค้า</option>
                <option value="po_quantity">จำนวนที่สั่งซื้อใน P/O</option>
				<option value="gr_percentage">GR 90% or 100%</option>
				<option value="price_usd">ราคาซื้อ USD ($)</option>
				<option value="unit_price">ราคา/หน่วย</option>
            </select>
        </td>
        <td>
            <input type="text" id="search_key" name="search_key" />
        </td>
		<td>
            <button type="button" class="crmbutton small create" style="padding:1px 5px"
                onclick="getDataPO(1)">Search</button>
        </td>
    </tr>
   
</table>

<table class="crmTable" width="100%" cellspacing="0" cellpadding="5" border="0" align="center">
    <thead>
        <tr>
            <td class="lvtCol">ลำดับที่</td>
            <td class="lvtCol">รหัสสินค้า</td>
            <td class="lvtCol">ยี่ห้อสินค้า</td>
            <td class="lvtCol">จำนวนที่สั่งซื้อใน P/O</td>
            <td class="lvtCol">GR 90% or 100%</td>
            <td class="lvtCol">ราคาซื้อ USD ($)</td>
            <td class="lvtCol">ราคา/หน่วย</td>
			<td class="lvtCol">ผู้รับผิดชอบ</td>
        </tr>
    </thead>

    <tbody id="popup-po-list"></tbody>
</table>

<div id="paging-po" style="background:#efefef;border:1px solid #ccc;"></div>

<script>
var genTableRow = function(data) {
    // console.log(data);
    if (data !== undefined && data !== null) {
        jQuery(`#popup-po-list`).html('');
data.map(item => {
    var tr = `
        <tr>
            <td class="crmTableRow small lineOnTop">${item.sequence_no}</td>
            <td class="crmTableRow small lineOnTop">
                <a onclick="setItemPO('<?php echo $rowId; ?>'
                , '${item.projectsid !== null ? item.projectsid : ''}'
                , '${item.projects_no !== null ? item.projects_no : ''}'
                , '${item.projects_name !== null ? item.projects_name : ''}'
                , '${item.smownerid}', '${item.owner_name}'
                , '${item.product_code_crm !== null ? item.product_code_crm : ''}'
                , '${item.productid !== null ? item.productid : ''}'
                , '${item.product_name !== null ? item.product_name : ''}'
                , '${item.sequence_no !== null ? item.sequence_no : ''}'
                , '${item.po_quantity !== null ? item.po_quantity : '0'}'
                , '${item.remain_quantity !== null ? item.remain_quantity : '0'}'
                , '${item.unit_price !== null ? item.unit_price : '0'}'
                , '${item.defects_quantity !== null ? item.defects_quantity : '0'}'
                , '${item.gr_quantity !== null ? item.gr_quantity : '0'}'
                , '${item.gr_percentage !== null ? item.gr_percentage : '0'}'
                , '${item.item_status !== null ? item.item_status : '0'}'
                , '${item.gr_qty_percent !== null ? item.gr_qty_percent : '0'}'
                )">
                ${item.product_code_crm}</a>
            </td>
            <td class="crmTableRow small lineOnTop">${item.product_brand}</td>
            <td class="crmTableRow small lineOnTop">${item.po_quantity}</td>
            <td class="crmTableRow small lineOnTop">${item.gr_percentage}</td>
            <td class="crmTableRow small lineOnTop">${item.price_usd}</td>
            <td class="crmTableRow small lineOnTop">${item.unit_price}</td>
			<td class="crmTableRow small lineOnTop">${item.owner_name}</td>
        </tr>
    `;
    // console.log(tr);
    jQuery(`#popup-po-list`).append(tr);
});
    }
}

var getDataPO = function(pageNumber, pageSize) {
    var searchKey = jQuery(`#search_key`).val();
	var searchField = jQuery(`#search_field option:selected`).val();
    // console.log(searchKey)
	// console.log(searchField)
    jQuery.post('po_popup_itemlist_data.php', {
		purchasesorderid:"<?php echo $purchasesorderid;?>",
        searchKey:searchKey,
		searchField:searchField,
        pageNumber:pageNumber,
        pageSize:pageSize
    }, function(rs) {
        // console.log(rs);
        genTableRow(rs.data)
        jQuery('#paging-po').pagination('refresh', { // change options and refresh pager bar information
            total: rs.totalCount,
            pageNumber: pageNumber !== undefined ? pageNumber : 1
        });
    }, 'json')
}
getDataPO();

var setItemPO = function(rowId, projectsid, projects_no, projects_name, smownerid,owner_name,product_code_crm,productid,product_name,sequence_no,po_quantity,remain_quantity,unit_price,defects_quantity,gr_quantity,gr_percentage,item_status,gr_qty_percent) {

    if(item_status=="Completed"){
        alert("This Item Detail already Complated.");
        return false;
    }else{
        jQuery(`#projects_code${rowId}`).val(projects_no);
        jQuery(`#projects_name${rowId}`).val(projects_name);
        jQuery(`#projectsid${rowId}`).val(projectsid);

        jQuery(`#assignto${rowId}`).val(owner_name);
        jQuery(`#smownerid${rowId}`).val(smownerid);

        jQuery(`#product_code_crm${rowId}`).val(product_code_crm);
        jQuery(`#productid${rowId}`).val(productid);
        jQuery(`#productname${rowId}`).val(product_name);
        jQuery(`#po_detail_no${rowId}`).val(sequence_no);
        jQuery(`#po_quantity${rowId}`).val(po_quantity);

        jQuery(`#remain_quantity${rowId}`).val(remain_quantity);
        jQuery(`#hdnRemain_quantity${rowId}`).val(remain_quantity);
        jQuery(`#unit_price${rowId}`).val(unit_price);

        jQuery(`#gr_percentage${rowId}`).val(gr_percentage);
        jQuery(`#item_status${rowId}`).val(item_status);
        jQuery(`#gr_qty_percent${rowId}`).val(gr_qty_percent);

        jQuery(`#total_defects_quantity${rowId}`).val(defects_quantity);
        jQuery(`#hdnTotal_defects_quantity${rowId}`).val(defects_quantity);
        jQuery(`#total_gr_quantity${rowId}`).val(gr_quantity);
        jQuery(`#hdnTotal_gr_quantity${rowId}`).val(gr_quantity);
    
        calAmount(`${rowId}`);
        calTotalAmount(`${rowId}`);
        settotalnoofrows();
        jQuery(popupPODialog).window('close');
    }
}

jQuery('#paging-po').pagination({
    onSelectPage: function(pageNumber, pageSize) {
        // jQuery.post('job_popup_product_data.php', {pageNumber, pageSize}, function(rs){
        // 	genTableRow(rs.data)
        // }, 'json')
        getDataPO(pageNumber, pageSize)
    }
});
</script>