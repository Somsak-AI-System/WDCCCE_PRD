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

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .table-product-list {
        width: 100%;
        border-collapse: separate !important;
	    border-spacing: 0px !important;
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
                <th colspan="2"><?php echo strtoupper(str_replace('_', ' ', $claim['list1_price_type'])); ?></th>
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
            <?php
                $totalList1 = 0;
                foreach($buyList as $i => $row){
                $rowNo = $i+1;
                if($row['total_price']!='' && $row['total_price']!=0) $totalList1 = $totalList1 + $row['total_price'];
            ?>
                <tr seq='<?php echo $rowNo; ?>'>
                    <td class="text-center"><?php echo $rowNo; ?></td>
                    <td><?php echo $row['productname']; ?></td>
                    <td><?php echo $row['inv_no']; ?></td>
                    <td><?php echo $row['color']; ?></td>
                    <td class="text-right"><?php echo number_format($row['buy_amount'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($row['claim_amount'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['unit']; ?></td>
                    <td class="text-right"><?php echo number_format($row['total_price'], 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7">
                </td>
                <td class="font-black text-right">รวม</td>
                <td>
                    <div class="font-black text-right sum_total"><?php echo number_format($totalList1, 2); ?></div>
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
                <th colspan="2"><?php echo strtoupper(str_replace('_', ' ', $claim['list2_price_type'])); ?></th>
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
            <?php 
                $totalList2 = 0;
                foreach($claimList as $i => $row){
                $rowNo = $i+1;
                if($row['total_price']!='' && $row['total_price']!=0) $totalList2 = $totalList2 + $row['total_price']; 
            ?>
                <tr seq='<?php echo $rowNo; ?>'>
                    <td class="text-center"><?php echo $rowNo; ?></td>
                    <td><?php echo $row['productname']; ?></td>
                    <td><?php echo $row['inv_no']; ?></td>
                    <td><?php echo $row['color']; ?></td>
                    <td class="text-right"><?php echo number_format($row['buy_amount'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($row['claim_amount'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['unit']; ?></td>
                    <td class="text-right"><?php echo number_format($row['total_price'], 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7">
                </td>
                <td class="font-black text-right">รวม</td>
                <td>
                    <div class="font-black text-right sum_total"><?php echo number_format($totalList2, 2); ?></div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

