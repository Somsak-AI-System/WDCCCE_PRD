<style>
.text-danger{
    color: red;
}
</style>

<table style="width:100%">
    <tr>
        <td style="width:40%">Class Name <span class="text-danger">*</span></td>
        <td style="width:60%">
            <select class="detailedViewTextBox" name="name" id="name">
                <?php foreach($grades as $grade){ 
                    $selected = @$gradeSetting['name'] == $grade['account_grade'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $grade['account_grade']; ?>"><?php echo $grade['account_grade']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">ช่วงเวลา (Month) <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="monthPeriod" id="monthPeriod" value="<?php echo @$gradeSetting['month_period']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%">ยอดที่ต้องรักษาเริ่มต้น <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="rangeStart" id="rangeStart" value="<?php echo @$gradeSetting['range_start']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%">ยอดที่ต้องรักษาสูงสุด <span class="text-danger">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="rangeEnd" id="rangeEnd" value="<?php echo @$gradeSetting['range_end']; ?>"></td>
    </tr>

    <tr>
        <td colspan="2" style="text-align:center">
            <input type="hidden" id="userID" value="<?php echo $userID; ?>">
            <button class="crmbutton small save" style="cursor:pointer" onclick="saveGradeSetting('<?php echo $action; ?>', '<?php echo $id; ?>')">Save</button>
            <button class="crmbutton small cancel" style="cursor:pointer" onclick="jQuery('#popup-dialog').window('close')">Cancel</button>
        </td>
    </tr>
</table>