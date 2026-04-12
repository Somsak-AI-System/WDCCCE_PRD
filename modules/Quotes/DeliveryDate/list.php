<?php
include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

global $site_URL, $current_user, $focus, $adb;

?>
<!-- Include the CSS for styling -->
<!-- <link rel="stylesheet" href="asset/css/datepicker.min.css"> -->
<!-- Include the JavaScript file -->
<!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script> -->
<!-- <script src="asset/js/datepicker-full.min.js"></script> -->
<script src="asset/js/currency-number.js"></script>

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

    /**/
    .base-input-group {
        display: flex;
        align-items: center;
    }

    .base-input-text {
        flex-grow: 1; /* Makes the input take available width */
    }

    .base-input-group-action {
        margin-left: 5px; /* Adds space between input and image */
        cursor: pointer;
    }

</style>
<?php
$item_list = [];
if($focus->id != '')
{
    $sql = "SELECT * FROM aicrm_inventory_quotes_list WHERE crmid=".$focus->id;
    $rs = $adb->pquery($sql, '');
    $countList = $adb->num_rows($rs);
    for($i=0; $i<$countList; $i++){
        $row = $adb->query_result_rowdata($rs, $i);
        $item_list[] = $row;
    }
}
?>
<div style="margin-top:20px;">
    <table class="table-product-list" id="list1">
        <thead>
            <tr>
                <th width="5%" align="center">ลำดับที่</th>
                <th width="10%">วันที่ส่งของ</th>
                <th width="15%">มูลค่าส่งของ</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>

        <tbody id="item-list">
            <?php if(!empty($item_list)){ 
                foreach($item_list as $i => $row){
                $rowNo = $i+1;    
            ?>
                <tr id="seq_<?php echo $rowNo; ?>" valign="top">
                    <td align="center">
                        <?php if($rowNo > 1){ ?>
                        <img src="themes/images/delete.gif" class="action-btn" onclick="jQuery.removeTableItem('seq_<?php echo $rowNo; ?>')">
                        <?php } echo $rowNo; ?>
                        <input type="hidden" name="list[]" value="<?php echo $rowNo; ?>">
                    </td>
                    <td align="center">
                        <input name="delivered_date<?php echo $rowNo; ?>" tabindex="" id="jscal_field_delivered_date<?php echo $rowNo; ?>" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo date('d-m-Y', strtotime($row['delivered_date'])); ?>" class="user-success">
                        
                        <img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_delivered_date<?php echo $rowNo; ?>" style="vertical-align: middle;">

                        <br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>
                        
                        <script type="text/javascript" id="massedit_calendar_delivered_date<?php echo $rowNo; ?>">
                        Calendar.setup ({inputField : "jscal_field_delivered_date<?php echo $rowNo; ?>", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_delivered_date<?php echo $rowNo; ?>", singleClick : true, step : 1 ,})</script>
                    </td>
                    <td>
                        <input type="text" class="form-control autonumeric" id="delivered_value<?php echo $rowNo; ?>" name="delivered_value<?php echo $rowNo; ?>" onblur="calcTotalDeliveredValue('delivered_value<?php echo $rowNo; ?>');" value="<?php echo number_format($row['delivered_value'], 2); ?>" />
                    </td>
                    <td>
                        <textarea class="form-control" name="remark<?php echo $rowNo; ?>" id="remark<?php echo $rowNo; ?>" rows="2"><?php echo $row['remark']; ?></textarea>
                    </td>
                </tr>
            <?php }} else { ?>
            <tr id="seq_1" valign="top">
                <td align="center">
                    1
                    <input type="hidden" name="list[]" value="1">
                </td>
                <td align="center">
                    <input name="delivered_date1" tabindex="" id="jscal_field_delivered_date1" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo date('d-m-Y'); ?>" class="user-success">
                        
                    <img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_delivered_date1" style="vertical-align: middle;">

                    <br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>
                        
                    <script type="text/javascript" id="massedit_calendar_delivered_date1">
                        Calendar.setup ({inputField : "jscal_field_delivered_date1", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_delivered_date1", singleClick : true, step : 1 ,})</script>
                </td>
                <td>
                    <input type="text" class="form-control autonumeric" id="delivered_value1" name="delivered_value1" value="0" onblur="calcTotalDeliveredValue('delivered_value1');"  />
                </td>
                <td>
                    <textarea class="form-control" name="remark1" id="remark1" rows="2"></textarea>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tbody>
            <tr class="crmTableRow big lineOnTop">
                <td align="center">Total</td>
                <td></td>
                <td>
                    <span id="total_delivered_value"></span>
                    <input type="hidden" name="hdn_total_delivered_value" id="hdn_total_delivered_value" value="">
                </td>
                <td></td>
            </tr>
        </tbody>
        
        <tfoot>
            <tr>
                <td colspan="4">
                    <div class="add-product-item" onclick="addTableItem()">
                        <img src="themes/softed/images/plus_w.png" />
                        <div>Add Item</div>
                    </div>
                </td>
                
            </tr>
        </tfoot>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInputs = document.querySelectorAll('.datepicker_input');
    dateInputs.forEach(dateInput => {
        new Datepicker(dateInput, {
            autohide: true,
            format: 'dd/mm/yyyy'
        });
    });

    // Function to open the datepicker for the clicked input
    window.openDatepicker = function (element) {
        const inputField = element.parentElement.querySelector('.datepicker_input');
        if (inputField) {
            inputField.focus();  // Focus on the input field
            inputField.dispatchEvent(new Event('click'));  // Trigger the datepicker
        }
    };
});


jQuery('.autonumeric').autoNumeric();

</script>
<script>
    function addTableItem() {
        let date = new Date();
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        let year = date.getFullYear();

        var inputs = jQuery('input[name="list[]"]');
        var maxValue = null;
        inputs.each(function() {
            var value = parseInt(jQuery(this).val());

            if (maxValue === null || value > maxValue) {
                maxValue = value;
            }
        });

        maxValue = parseInt(maxValue + 1);
        // console.log("Max value:", maxValue);

        var add_col = `
            <tr id="seq_${maxValue}" valign="top">
                <td align="center">
                    <img src="themes/images/delete.gif" class="action-btn" onclick="jQuery.removeTableItem('seq_${maxValue}')"> ${maxValue}
                    <input type="hidden" name="list[]" value="${maxValue}">
                </td>
                <td align="center">
                <input name="delivered_date${maxValue}" tabindex="" id="jscal_field_delivered_date${maxValue}" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="${day}/${month}/${year}" class="user-success">
                    <img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_delivered_date${maxValue}" style="vertical-align: middle;">
                    <br><font size="1"><em old="(yyyy-mm-dd)">(dd-mm-yyyy)</em></font>
                </td>
                <td>
                    <input type="text" class="form-control autonumeric" id="delivered_value${maxValue}" name="delivered_value${maxValue}" value="0" onblur="calcTotalDeliveredValue('delivered_value${maxValue}');" />
                </td>
                <td>
                    <textarea class="form-control" name="remark${maxValue}" id="remark${maxValue}" rows="2"></textarea>
                </td>
            </tr>
        `;

        jQuery('#item-list').append(add_col);

        // Now, add the Calendar setup script separately
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.id = `massedit_calendar_delivered_date${maxValue}`;
        script.innerHTML = `
            Calendar.setup({
                inputField: "jscal_field_delivered_date${maxValue}", 
                ifFormat: "%d-%m-%Y", 
                showsTime: false, 
                button: "jscal_trigger_delivered_date${maxValue}", 
                singleClick: true, 
                step: 1
            });
        `;
        document.body.appendChild(script);

        jQuery('.autonumeric').autoNumeric();
    }


    jQuery.removeTableItem = function(tr_id){
        jQuery("#" + tr_id).remove();
        calcTotalDeliveredValueOnload();
    }

    function calcTotalDeliveredValue(id) {
        let total = 0;

        // Iterate through each input with IDs starting with 'delivered_value'
        jQuery('[id^="delivered_value"]').each(function() {
            // Get the value and convert it to float
            const value = parseFloat(jQuery(this).val().replace(/,/g, '')); // Remove commas if using formatted numbers
            if (!isNaN(value)) { // Check if it's a valid number
                total += value; // Add to total
            }
        });

        // Update the total in the designated span
        var grandTotal = jQuery('#grandTotal').text();
        if(total > grandTotal){
            alert("มูลค่าส่งของ มากกว่าจำนวน Grand Total");
            jQuery('#'+id).val('').focus();
        }

        jQuery('#total_delivered_value').text(roundValue(total));
        jQuery('#hdn_total_delivered_value').val(roundValue(total));
    }

    calcTotalDeliveredValueOnload();
    function calcTotalDeliveredValueOnload() {
        let total = 0;

        // Iterate through each input with IDs starting with 'delivered_value'
        jQuery('[id^="delivered_value"]').each(function() {
            // Get the value and convert it to float
            const value = parseFloat(jQuery(this).val().replace(/,/g, '')); // Remove commas if using formatted numbers
            if (!isNaN(value)) { // Check if it's a valid number
                total += value; // Add to total
            }
        });

        jQuery('#total_delivered_value').text(roundValue(total));
        jQuery('#hdn_total_delivered_value').val(roundValue(total));
    }
    

</script>

