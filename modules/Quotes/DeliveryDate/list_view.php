<?php
include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

global $site_URL, $current_user, $focus, $adb;

?>
<!-- Include the CSS for styling -->
<link rel="stylesheet" href="asset/css/datepicker.min.css">
<!-- Include the JavaScript file -->
<!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script> -->
<script src="asset/js/datepicker-full.min.js"></script>
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
                <th width="8%" align="center">วันที่ส่งของ</th>
                <th width="15%">มูลค่าส่งของ</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>

        <tbody>
            <?php if(!empty($item_list)){ 
                foreach($item_list as $i => $row){
                $rowNo = $i+1;    
            ?>
                <tr id="seq_<?php echo $rowNo; ?>" valign="top">
                    <td  align="center">
                        <?php echo $rowNo; ?>
                    </td>
                    <td align="center">
                       <?php echo date('d/m/Y', strtotime($row['delivered_date'])); ?>
                    </td>
                    <td align="right" >
                        <?php echo number_format($row['delivered_value'], 2); ?>
                    </td>
                    <td>
                        <?php echo nl2br(htmlspecialchars($row['remark'])); ?>
                    </td>
                </tr>
            <?php }} else { ?>
            
            <?php } ?>
        </tbody>
        <tbody>
            <tr class="crmTableRow big lineOnTop">
                <td align="center">Total</td>
                <td></td>
                <td align="right" >
                    <span id="total_delivered_value"><?php echo number_format($row['total_delivered_value'], 2); ?></span>
                </td>
                <td></td>
            </tr>
        </tbody>
        
        <tfoot>
            <tr>
                <td colspan="4">
                    <div class="add-product-item" onclick="editTableItem('<?php echo $_REQUEST['record'];?>')">
                        <div>อัพเดตข้อมูล</div>
                    </div>
                </td>
                
            </tr>
        </tfoot>

    </table>
</div>


