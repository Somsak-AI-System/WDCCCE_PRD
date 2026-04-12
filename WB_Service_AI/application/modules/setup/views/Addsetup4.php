<style>
    .text-danger{
        color: red;
    }
    .detailedViewTextBox  {
        font-family: inherit;
    }
    #operator {
        font-size: 14px;
        height: 25px;
    }
    @font-face {
        font-family: PromptMedium;
        src: url(../../themes/softed/fonts/Prompt-Medium.ttf);
    }
    body{
        font-family: PromptMedium;
    }
    .detailedViewTextBox{
        font-family: PromptMedium;
    }
    span{
        font-size: 14px;
    }
</style>

<table style="width:100%">

    <tr>
        <td style="width:40%"><span>Account Grade</span> </td>
        <td style="width:60%">
            <select class="detailedViewTextBox" name="accountgrade" id="accountgrade">
                <?php foreach($accountgrade as $accountgrade){
                    $selected = @$setUppoint4['accountgrade'] == $accountgrade['accountgrade'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $accountgrade['accountgrade']; ?>"><?php echo $accountgrade['accountgrade']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%"><span>Period Start </span><span style="color:red">*</span></td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="startdate" id="startdate" value="<?php echo @$setUppoint4['startdate']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%"><span>Period End </span><span style="color:red">*</span></td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="enddate" id="enddate" value="<?php echo @$setUppoint4['enddate']; ?>"></td>
    </tr>

    <tr>
        <td style="width:40%"><span>Point </span><span style="color:red">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="number" id="number" min="0" value="<?php echo (@$setUppoint4['number'] != '') ? @$setUppoint4['number'] : 0; ?>"></td>
    </tr>

    <tr>
        <td style="width:40%"><span>Operator</span></td>
        <td style="width:60%">
            <select class="detailedViewTextBox" name="operator" id="operator">
                <option value="+" <?php if($setUppoint4['operator'] == '+') echo 'selected'; ?>>+</option>
                <option value="*" <?php if($setUppoint4['operator'] == '*') echo 'selected'; ?>>*</option>
            </select>
        </td>
    </tr>

    <tr>
        <td style="width:40%"><span>Minimum Amount </span><span style="color:red">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="minimum" id="minimum" min="0" value="<?php echo (@$setUppoint4['minimum'] != '') ? @$setUppoint4['minimum'] : 0; ?>"></td>
    </tr>

    <tr>
        <td colspan="2" style="text-align:center">
            <input type="hidden" id="userID" value="<?php echo $userID; ?>">
            <button class="crmbutton small save" style="cursor:pointer" onclick="saveSetpoint4('<?php echo $action; ?>', '<?php echo $id; ?>')">Save</button>
            <button class="crmbutton small cancel" style="cursor:pointer" onclick="jQuery('#popup-dialog').window('close')">Cancel</button>
        </td>
    </tr>
</table>
<script type="text/javascript">

    

</script>