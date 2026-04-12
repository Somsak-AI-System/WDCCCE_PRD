<?php
session_start();
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");
include_once("library/myLibrary_mysqli.php");

global $generate, $myLibrary_mysqli;
$generate = new generate($dbconfig, "DB");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$date = date('Y-m-d');
$smownerid = $_SESSION["authenticated_user_id"];
$crmid = $_REQUEST["crmid"];
$grand_total = $_REQUEST['grand_total'];




if($_REQUEST['action'] == "save" && $crmid != ''){
    $sql_delete = "DELETE FROM aicrm_inventory_quotes_list WHERE crmid='".$crmid."'";
    $myLibrary_mysqli->Query($sql_delete);
    
    
    function convertdateToDB($date){
        $data = explode("-", $date);
        return $data['2']."-".$data['1']."-".$data['0'];
     }
     
     if(isset($_REQUEST['list']) && !empty($_REQUEST['list'])){
         foreach($_REQUEST['list'] as $i => $no){
             $delivered_date = convertdateToDB($_REQUEST['delivered_date'.$no]);
             // echo $delivered_date; exit;
             $delivered_value = str_replace(',', '', $_REQUEST['delivered_value'.$no]);
             $remark = $_REQUEST['remark'.$no];
             $total_delivered_value =  str_replace(',', '', $_REQUEST['hdn_total_delivered_value']);
     
             if(!empty($delivered_value) && $delivered_value != ''){
                 $sql_insert = "INSERT INTO aicrm_inventory_quotes_list (crmid, sequence_no, delivered_date, delivered_value, remark,total_delivered_value)
                 VALUES ('".$crmid."', '".$no."', '".$delivered_date."', '".$delivered_value."', '".$remark."', '".$total_delivered_value."')";
                $myLibrary_mysqli->Query($sql_insert);
             }
     
         }
         // exit;
     }

    exit;
}

?>

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

    .add-product-item {
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
        float: right;
        margin-right : 10px;
    }
    .table-product-list .add-product-item {
        float: left;
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

    .save, .btnedit,.Cancel_Quotaion{
        padding: 8px !important;
        width: 80px;
    }
    
    .form-control{
        width: 80% !important;
        height: 50% !important;
    }

    .user-success{
        padding: 5px 7px !important;
        border: 1px solid #ccc !important;
        border-radius: 4px !important;
    }

</style>
<?php
$item_list = [];
if($crmid != '')
{
    $sql = "SELECT * FROM aicrm_inventory_quotes_list WHERE crmid=".$crmid;
    $data = $myLibrary_mysqli->select($sql);
    for($i=0; $i<count($data); $i++){
        $row = $data[$i];
        $item_list[] = $row;
    }
}
// echo "<pre>"; print_r($item_list); echo "</pre>";exit;
?>
<div style="margin-top:20px;">


<div class="add-product-item" onclick="addTableItem()">
    <img src="themes/softed/images/plus_w.png" /> <div>Add Item</div>
</div>
<form action="get_delivery_date.php" name="delivery-form" id="delivery-form" method="POST">
    <table class="table-product-list" id="list1">
        <thead>
            <tr>
                <th width="5%" align="center">ลำดับที่</th>
                <th width="20%">วันที่ส่งของ</th>
                <th width="20%">มูลค่าส่งของ</th>
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
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="crmid" value="<?php echo $crmid;?>">
                    <button type="button" class="btn crmbutton small save" onclick="Savedelivery();">&nbsp;<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;Save&nbsp;&nbsp;</button>
                    <button type="button" class="btn crmbutton small cancel Cancel_Quotaion" onclick="jQuery('#dialog').dialog('close');"><img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">&nbsp;Cancel</button>
                </td>
            </tr>
        </tfoot>



    </table>
  </form>
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
        var grandTotal = "<?php echo $grand_total != '' ? $grand_total : 0;?>";
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

    ////

    function Savedelivery() {
    // Serialize the form data
        var formData = jQuery("#delivery-form").serialize();

        var flag_delivered = 0;
            jQuery('[id^="delivered_value"]').each(function(i, e) {
                var selectedValue = jQuery(e).val();
                // alert(selectedValue);
                if(selectedValue == '' || selectedValue == 0){
                    flag_delivered = 1;
                }
            });

            if(flag_delivered == 1){
                alert('โปรดระบุมูลค่าส่งของ');
                return false;
            }
        
        // Send the data via AJAX
        jQuery.ajax({
            url: "get_delivery_date.php", // The PHP script to handle the request
            type: "POST",                 // HTTP method
            data: formData,               // Form data to send
            success: function(response) {
                // Handle the success response
                console.log("Data saved successfully:", response);
                alert("successfully!");
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle the error response
                console.error("Error saving data:", error);
                alert("Failed");
                location.reload();
            }
        });
    }

    

</script>

