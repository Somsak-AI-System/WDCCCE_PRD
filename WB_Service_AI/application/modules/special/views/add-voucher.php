<style>
.text-danger{
    color: red;
}
</style>

<table style="width:100%">
    <tr>
        <td style="width:40%">ชื่อบัตรกำนัล <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="text" class="detailedViewTextBox" name="promotion_name" id="promotion_name" value="<?php echo @$promotion_name; ?>" readonly></td>
    </tr>
    <tr>
        <td style="width:40%">จำนวนบัตรกำนัล <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="voucher_amount" id="voucher_amount"></td>
    </tr>
    <tr>
        <td style="width:40%">วันที่เริ่ม <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="text" class="detailedViewTextBox" name="startdate" id="startdate" value="<?php echo @$startdate; ?>" readonly></td>
    </tr>
    <tr>
        <td style="width:40%">วันที่สิ้นสุด <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="text" class="detailedViewTextBox" name="enddate" id="enddate" value="<?php echo @$enddate; ?>" readonly></td>
    </tr>
    <tr>
        <td style="width:40%">มูลค่าบัตรกำนัล (฿) <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="voucher_price" id="voucher_price"></td>
    </tr>
    <tr>
        <td style="width:40%">ข้อความบัตรกำนัล <span class="text-danger">*</span></td>
        <td style="width:60%">
            <textarea type="text" class="detailedViewTextBox" name="voucher_remark" id="voucher_remark"></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="hidden" id="userID" value="<?php echo $userID; ?>">
            <button class="crmbutton small save" onclick="saveVoucher('<?php echo $promotionid; ?>')">Save</button>
            <button class="crmbutton small cancel" onclick="jQuery('#voucher-dialog').window('close')">Cancel</button>
        </td>
    </tr>
</table>