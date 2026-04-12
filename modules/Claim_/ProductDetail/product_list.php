<?php
include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

global $site_URL, $current_user, $focus, $adb;

?>
<style>
    .font-black {
        font-weight: bold;
    }

    .text-right {
        text-align: right;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .form-select {
        border: 1px solid #dfe8f1;
        border-radius: 3px;
        color: #2b2f33;
        background-color: #FFF;
        padding: 3.5px 4px;
        line-height: 20px;
    }

    .table-product-list {
        width: 100%;
    }
    .table-product-list th,
    .table-product-list td {
        padding: 8px;
    }

    .table-product-list th {
        background-color: #EDEDED;
    }

    .table-product-list tbody td {
        border: 1px solid #EDEDED;
    }

    .table-product-list .box-product-search {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .table-product-list .add-product-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        cursor: pointer;
        background-color: #018FFB;
        color: #FFFFFF;
        padding: 8px;
        border-radius: 4px;
        width: 80px;
    }

    .table-product-list .form-control {
        width: 100%;
    }

    .table-product-list .input-number {
        text-align: right;
    }

    .table-product-list .form-control:read-only,
    .table-product-list .form-control.product_name {
        background-color: #EDEDED;
    }

    .table-product-list .action-btn {
        cursor: pointer;
    }
</style>
<?php
$rs = $adb->pquery("SELECT list1_price_type, list2_price_type FROM aicrm_claim WHERE claimid=?", [$focus->id]);
$claim = $adb->fetch_array($rs);

$buyList = [];
$claimList = [];
if($focus->id != '')
{
    $sql = "SELECT * FROM aicrm_inventoryclaim WHERE crmid=".$focus->id;
    $rs = $adb->pquery($sql, '');
    $countClaimList = $adb->num_rows($rs);
    for($i=0; $i<$countClaimList; $i++){
        $row = $adb->query_result_rowdata($rs, $i);
        switch($row['type']){
            case 'buy':
                $buyList[] = $row;
                break;
            case 'claim':
                $claimList[] = $row;
                break;
        }
    }
}
?>
<div>
    <table class="table-product-list" id="list1">
        <thead>
            <tr>
                <th colspan="6"></th>
                <th class="text-right">Price Type</th>
                <th colspan="2">
                    <select class="form-select price_type" style="width:100%" name="list1_price_type">
                        <option value="exclude_vat" <?php echo $claim['list1_price_type'] == 'exclude_vat' ? 'selected':''; ?>>Exclude VAT</option>
                        <option value="include_vat" <?php echo $claim['list1_price_type'] == 'include_vat' ? 'selected':''; ?>>Include VAT</option>
                    </select>
                </th>
            </tr>
            <tr>
                <th width="5%">ลำดับที่</th>
                <th width="35%">รายการสินค้าที่เคลม</th>
                <th width="15%">เลขที่ Invoice</th>
                <th>เฉดสี</th>
                <th>จำนวนที่ซื้อ</th>
                <th>จำนวนที่เคลม</th>
                <th>ราคา/หน่วย</th>
                <th>หน่วยสินค้า</th>
                <th>ราคาสินค้า</th>
            </tr>
        </thead>

        <tbody>
            <?php if(!empty($buyList)){ 
                foreach($buyList as $i => $row){
                $rowNo = $i+1;    
            ?>
                <tr seq='<?php echo $rowNo; ?>'>
                    <td>
                        <?php if($rowNo > 1){ ?>
                        <img src="themes/images/delete.gif" class="action-btn" onclick="jQuery.removeTableItem(this, 'list1')">
                        <?php } ?>
                    </td>
                    <td>
                        <div class="box-product-search">
                            <input type="hidden" class="product_list" name="product_list_1[]" value="<?php echo $rowNo; ?>" />
                            <input type="hidden" class="product_id" name="list1_product_id_<?php echo $rowNo; ?>" value="<?php echo $row['productid']; ?>" />
                            <input type="text" class="form-control product_name" name="list1_product_name_<?php echo $rowNo; ?>" value="<?php echo $row['productname']; ?>" onkeypress="return false" />
                            <div>
                                <img src="themes/images/products.gif" class="cursor-pointer" onclick="jQuery.selectProduct(this)"/>
                            </div>
                            <div>
                                <img src="themes/softed/images/clear_field.gif" class="cursor-pointer" onclick="jQuery.clearProduct(this)" />
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control inv_no" name="list1_inv_no_<?php echo $rowNo; ?>" value="<?php echo $row['inv_no']; ?>" />
                    </td>
                    <td>
                        <input type="text" class="form-control color" name="list1_color_<?php echo $rowNo; ?>" value="<?php echo $row['color']; ?>" />
                    </td>
                    <td>
                        <input type="text" class="form-control input-number buy_amount" name="list1_buy_amount_<?php echo $rowNo; ?>" value="<?php echo (int)$row['buy_amount']; ?>" />
                    </td>
                    <td>
                        <input type="text" class="form-control input-number claim_amount" name="list1_claim_amount_<?php echo $rowNo; ?>" value="<?php echo (int)$row['claim_amount']; ?>" />
                    </td>
                    <td>
                        <input type="text" class="form-control input-number price" name="list1_price_<?php echo $rowNo; ?>" value="<?php echo $row['price']; ?>" />
                    </td>
                    <td>
                        <!-- <input type="text" class="form-control unit" name="list1_unit_<?php echo $rowNo; ?>" value="<?php echo $row['unit']; ?>" /> -->
                        <select class="form-select unit" name="list1_unit_<?php echo $rowNo; ?>">
                            <option value=""></option>
                            <option value="แผ่น" <?php echo $row['unit'] == 'แผ่น' ? 'selected':''; ?>>แผ่น</option>
                            <option value="กล่อง" <?php echo $row['unit'] == 'กล่อง' ? 'selected':''; ?>>กล่อง</option>
                            <option value="ตร.ม." <?php echo $row['unit'] == 'ตร.ม.' ? 'selected':''; ?>>ตร.ม.</option>
                            <option value="ชิ้น" <?php echo $row['unit'] == 'ชิ้น' ? 'selected':''; ?>>ชิ้น</option>
                            <option value="ถุง" <?php echo $row['unit'] == 'ถุง' ? 'selected':''; ?>>ถุง</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control input-number total_price" name="list1_total_price_<?php echo $rowNo; ?>" value="<?php echo $row['total_price']; ?>" readonly/>
                    </td>
                </tr>
            <?php }} else { ?>
            <tr seq='1'>
                <td></td>
                <td>
                    <div class="box-product-search">
                        <input type="hidden" class="product_list" name="product_list_1[]" value="1" />
                        <input type="hidden" class="product_id" name="list1_product_id_1" />
                        <input type="text" class="form-control product_name" name="list1_product_name_1" onkeypress="return false" />
                        <div>
                            <img src="themes/images/products.gif" class="cursor-pointer" onclick="jQuery.selectProduct(this)"/>
                        </div>
                        <div>
                            <img src="themes/softed/images/clear_field.gif" class="cursor-pointer" onclick="jQuery.clearProduct(this)" />
                        </div>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control inv_no" name="list1_inv_no_1" />
                </td>
                <td>
                    <input type="text" class="form-control color" name="list1_color_1" />
                </td>
                <td>
                    <input type="text" class="form-control input-number buy_amount" name="list1_buy_amount_1" />
                </td>
                <td>
                    <input type="text" class="form-control input-number claim_amount" name="list1_claim_amount_1" />
                </td>
                <td>
                    <input type="text" class="form-control input-number price" name="list1_price_1" />
                </td>
                <td>
                    <!-- <input type="text" class="form-control unit" name="list1_unit_1" /> -->
                    <select class="form-select unit" name="list1_unit_1">
                        <option value=""></option>
                        <option value="แผ่น">แผ่น</option>
                        <option value="กล่อง">กล่อง</option>
                        <option value="ตร.ม.">ตร.ม.</option>
                        <option value="ชิ้น">ชิ้น</option>
                        <option value="ถุง">ถุง</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control input-number total_price" name="list1_total_price_1" readonly/>
                </td>
            </tr>
            <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7">
                    <div class="add-product-item"
                        onclick="jQuery.addTableItem('list1')"
                    >
                        <img src="themes/softed/images/plus_w.png" />
                        <div>Add Item</div>
                    </div>
                </td>
                <td class="font-black text-right">รวม</td>
                <td>
                    <div class="font-black text-right sum_total"></div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<div>
    <table class="table-product-list" id="list2">
        <thead>
            <tr>
                <th colspan="6"></th>
                <th class="text-right">Price Type</th>
                <th colspan="2">
                    <select class="form-select price_type" style="width:100%" name="list2_price_type">
                        <option value="exclude_vat" <?php echo $claim['list2_price_type'] == 'exclude_vat' ? 'selected':''; ?>>Exclude VAT</option>
                        <option value="include_vat" <?php echo $claim['list2_price_type'] == 'include_vat' ? 'selected':''; ?>>Include VAT</option>
                    </select>
                </th>
            </tr>
            <tr>
                <th width="5%">ลำดับที่</th>
                <th width="35%">รายการสินค้าที่เปลี่ยน</th>
                <th width="15%">เลขที่ invoice</th>
                <th>เฉดสี</th>
                <th>จำนวนที่ซื้อ</th>
                <th>จำนวนที่เคลม</th>
                <th>ราคา/หน่วย</th>
                <th>หน่วยสินค้า</th>
                <th>ราคาสินค้า</th>
            </tr>
        </thead>

        <tbody>
            <?php if(!empty($claimList)){ 
                foreach($claimList as $i => $row){
                $rowNo = $i+1;    
            ?>
            <tr seq='<?php echo $rowNo; ?>'>
                <td>
                    <?php if($rowNo > 1){ ?>
                    <img src="themes/images/delete.gif" class="action-btn" onclick="jQuery.removeTableItem(this, 'list2')">
                    <?php } ?>
                </td>
                <td>
                    <div class="box-product-search">
                        <input type="hidden" class="product_list" name="product_list_2[]" value="<?php echo $rowNo; ?>" />
                        <input type="hidden" class="product_id" name="list2_product_id_<?php echo $rowNo; ?>" value="<?php echo $row['productid']; ?>" />
                        <input type="text" class="form-control product_name" name="list2_product_name_<?php echo $rowNo; ?>" value="<?php echo $row['productname']; ?>" onkeypress="return false" />
                        <div>
                            <img src="themes/images/products.gif" class="cursor-pointer" onclick="jQuery.selectProduct(this)"/>
                        </div>
                        <div>
                            <img src="themes/softed/images/clear_field.gif" class="cursor-pointer" onclick="jQuery.clearProduct(this)" />
                        </div>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control inv_no" name="list2_inv_no_<?php echo $rowNo; ?>" value="<?php echo $row['inv_no']; ?>" />
                </td>
                <td>
                    <input type="text" class="form-control color" name="list2_color_<?php echo $rowNo; ?>" value="<?php echo $row['color']; ?>" />
                </td>
                <td>
                    <input type="text" class="form-control input-number buy_amount" name="list2_buy_amount_<?php echo $rowNo; ?>" value="<?php echo (int)$row['buy_amount']; ?>" />
                </td>
                <td>
                    <input type="text" class="form-control input-number claim_amount" name="list2_claim_amount_<?php echo $rowNo; ?>" value="<?php echo (int)$row['claim_amount']; ?>" />
                </td>
                <td>
                    <input type="text" class="form-control input-number price" name="list2_price_<?php echo $rowNo; ?>" value="<?php echo $row['price']; ?>" />
                </td>
                <td>
                    <!-- <input type="text" class="form-control unit" name="list2_unit_<?php echo $rowNo; ?>" value="<?php echo $row['unit']; ?>" /> -->
                    <select class="form-select unit" name="list2_unit_<?php echo $rowNo; ?>">
                        <option value=""></option>
                        <option value="แผ่น" <?php echo $row['unit'] == 'แผ่น' ? 'selected':''; ?>>แผ่น</option>
                        <option value="กล่อง" <?php echo $row['unit'] == 'กล่อง' ? 'selected':''; ?>>กล่อง</option>
                        <option value="ตร.ม." <?php echo $row['unit'] == 'ตร.ม.' ? 'selected':''; ?>>ตร.ม.</option>
                        <option value="ชิ้น" <?php echo $row['unit'] == 'ชิ้น' ? 'selected':''; ?>>ชิ้น</option>
                        <option value="ถุง" <?php echo $row['unit'] == 'ถุง' ? 'selected':''; ?>>ถุง</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control input-number total_price" name="list2_total_price_<?php echo $rowNo; ?>" value="<?php echo $row['total_price']; ?>" readonly/>
                </td>
            </tr>
            <?php }} else { ?>
            <tr seq='1'>
                <td></td>
                <td>
                    <div class="box-product-search">
                        <input type="hidden" class="product_list" name="product_list_2[]" value="1" />
                        <input type="hidden" class="product_id" name="list2_product_id_1" />
                        <input type="text" class="form-control product_name" name="list2_product_name_1" onkeypress="return false" />
                        <div>
                            <img src="themes/images/products.gif" class="cursor-pointer" onclick="jQuery.selectProduct(this)"/>
                        </div>
                        <div>
                            <img src="themes/softed/images/clear_field.gif" class="cursor-pointer" onclick="jQuery.clearProduct(this)" />
                        </div>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control inv_no" name="list2_inv_no_1" />
                </td>
                <td>
                    <input type="text" class="form-control color" name="list2_color_1" />
                </td>
                <td>
                    <input type="text" class="form-control input-number buy_amount" name="list2_buy_amount_1" />
                </td>
                <td>
                    <input type="text" class="form-control input-number claim_amount" name="list2_claim_amount_1" />
                </td>
                <td>
                    <input type="text" class="form-control input-number price" name="list2_price_1" />
                </td>
                <td>
                    <!-- <input type="text" class="form-control unit" name="list2_unit_1" /> -->
                    <select class="form-select unit" name="list2_unit_1">
                        <option value=""></option>
                        <option value="แผ่น">แผ่น</option>
                        <option value="กล่อง">กล่อง</option>
                        <option value="ตร.ม.">ตร.ม.</option>
                        <option value="ชิ้น">ชิ้น</option>
                        <option value="ถุง">ถุง</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control input-number total_price" name="list2_total_price_1" readonly/>
                </td>
            </tr>
            <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7">
                    <div class="add-product-item"
                        onclick="jQuery.addTableItem('list2')"
                    >
                        <img src="themes/softed/images/plus_w.png" />
                        <div>Add Item</div>
                    </div>
                </td>
                <td class="font-black text-right">รวม</td>
                <td>
                    <div class="font-black text-right sum_total"></div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    jQuery.addTableItem = function(table){
        var tempRow = jQuery(`#${table} tbody tr:first`)
        var newRow = jQuery(tempRow).clone()

        var rows = jQuery(`#${table}`).find('tbody tr').get()
        var maxNo = 0;
        jQuery.each(rows, function(i, row) {
            var seq = jQuery(row).attr('seq')
            if(eval(seq) > maxNo) maxNo = eval(seq)
        });
        var rowNo = maxNo + 1
        jQuery(newRow).attr('seq', rowNo)
        jQuery(newRow).find('input').each(function(e){
            jQuery(this).val('')
        })

        jQuery(`#${table} tbody`).append(newRow)

        var rows = jQuery(`#${table} tbody`).find('tr').get()
        rows.sort(function(a, b) {
            var keyA = eval(jQuery(a).attr('seq'))
            var keyB = eval(jQuery(b).attr('seq'))
            if (keyA < keyB) return -1
            if (keyA > keyB) return 1
            return 0;
        });

        reGenRow(table, rows)
    }

    jQuery.removeTableItem = function(obj, table){
        jQuery(obj).closest('tr').remove()
        var rows = jQuery(`#${table}`).find('tbody tr').get()
        reGenRow(table, rows)
        jQuery.sumTotal(table)
    }

    jQuery.selectProduct = function(obj){
        var table = jQuery(obj).closest('table').attr('id')
        var tr = jQuery(obj).closest('tr').attr('seq')
        var curr_row = `${table}_${tr}`
        window.open(`index.php?module=Products&action=Popup&html=Popup_picker&select=enable&form=HelpDeskEditView&popuptype=inventory_prod&curr_row=${curr_row}&return_module=Claim`,"productWin","width=640,height=600,resizable=0,scrollbars=0,status=1,top=150,left=200");
    }

    jQuery.clearProduct = function(obj){
        jQuery(obj).closest('tr').find('.product_id').val('')
        jQuery(obj).closest('tr').find('.product_name').val('')
    }

    jQuery.inputNumOnly = function(){
        jQuery('.input-number').keypress(function(evt){
            let ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 46 || ASCIICode > 57))
                return false;
            return true;
        })
    }
    jQuery.inputNumOnly()

    jQuery.numberKeyup = function(){
        jQuery('.input-number').keyup(function(evt){
            var table = jQuery(this).closest('table')
            table = jQuery(table).attr('id')
            jQuery.sumTotal(table)
        })
    }
    jQuery.numberKeyup()

    jQuery('.price_type').change(function(evt){
        var table = jQuery(this).closest('table')
        table = jQuery(table).attr('id')
        jQuery.sumTotal(table)
    })

    jQuery.sumTotal = function(table){
        var rows = jQuery(`#${table}`).find('tbody tr').get()
        var vat_type = jQuery(`#${table}`).find('.price_type').val()
        var sum_total = 0

        jQuery.each(rows, function(i, row) {
            var claim_amount = jQuery(row).find('.claim_amount').val()
            var price = jQuery(row).find('.price').val()

            if(claim_amount != 0 && price != 0){
                var total_price = claim_amount * price
                // if(vat_type === 'include_vat'){
                //     total_price = (total_price * 107) / 100
                // }
                sum_total = sum_total + total_price
                jQuery(row).find('.total_price').val(currencyFormat(total_price,2))
            } else {
                sum_total = sum_total + 0
                jQuery(row).find('.total_price').val(0)
            }
        })

        jQuery(`#${table}`).find('.sum_total').html(currencyFormat(sum_total, 2))
    }
    jQuery.sumTotal('list1')
    jQuery.sumTotal('list2')

    function removeComma(num){
        if(num === undefined || num === null || num === '') return 0;
        return parseFloat(num.toString().replace(/,/g, ''));
    };

    function currencyFormat(num, dicimal){
        dicimal = dicimal === undefined ? 0 : dicimal;
        if (num === undefined || num === '') return '';
        num = removeComma(num);
        return num.toFixed(dicimal).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    };

    function reGenRow(table, rows){
        jQuery.each(rows, function(i, row) {
            var rowNo = i+1;

            if(i > 0){
                jQuery(row).find('td:first').html(`<img src="themes/images/delete.gif" class="action-btn" onclick="jQuery.removeTableItem(this, '${table}')">`)
                jQuery(row).find('td:nth-child(2) .product_list').val(rowNo)
                jQuery(row).find('td:nth-child(2) .product_id').attr('name', `${table}_product_id_${rowNo}`)
                jQuery(row).find('td:nth-child(2) .product_name').attr('name', `${table}_product_name_${rowNo}`)
                jQuery(row).find('td:nth-child(3) .inv_no').attr('name', `${table}_inv_no_${rowNo}`)
                jQuery(row).find('td:nth-child(4) .color').attr('name', `${table}_color_${rowNo}`)
                jQuery(row).find('td:nth-child(5) .buy_amount').attr('name', `${table}_buy_amount_${rowNo}`)
                jQuery(row).find('td:nth-child(6) .claim_amount').attr('name', `${table}_claim_amount_${rowNo}`)
                jQuery(row).find('td:nth-child(7) .price').attr('name', `${table}_price_${rowNo}`)
                // jQuery(row).find('td:nth-child(8) .price_include_vat').attr('name', `${table}_price_include_vat_${rowNo}`)
                // jQuery(row).find('td:nth-child(9) .price_exclude_vat').attr('name', `${table}_price_exclude_vat_${rowNo}`)
                jQuery(row).find('td:nth-child(8) .unit').attr('name', `${table}_unit_${rowNo}`)
                jQuery(row).find('td:nth-child(9) .total_price').attr('name', `${table}_total_price_${rowNo}`)
            }
            
            jQuery(`#${table}`).children('tbody').append(row);
            jQuery.inputNumOnly()
            jQuery.numberKeyup()
        })
    }

</script>

