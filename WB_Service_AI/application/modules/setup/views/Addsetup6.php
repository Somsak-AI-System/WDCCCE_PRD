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
        <td style="width:40%"><span>Main Channel</span> </td>
        <td style="width:60%">
            <select class="easyui-combobox" name="main_channel[]" id="main_channel" style="width:90%;">
                <?php foreach($main_channel as $main_channel){
                    $selected = @$setUppoint6['main_channel'] == $main_channel['main_channel'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $main_channel['main_channel']; ?>"><?php echo $main_channel['main_channel']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>

    <tr>
        <td style="width:40%"><span>Sub Channel</span> </td>
        <td style="width:60%">
            <select class="easyui-combobox" name="sub_channel[]" id="sub_channel" style="width:90%;">
                <?php foreach($sub_channel as $sub_channel){
                    $selected = @$setUppoint6['sub_channel'] == $sub_channel['sub_channel'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $sub_channel['sub_channel']; ?>"><?php echo $sub_channel['sub_channel']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>

    <tr>
        <td style="width:40%"><span>Period Start </span><span style="color:red">*</span></td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="startdate" id="startdate" value="<?php echo @$setUppoint6['startdate']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%"><span>Period End </span><span style="color:red">*</span></td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="enddate" id="enddate" value="<?php echo @$setUppoint6['enddate']; ?>"></td>
    </tr>

    <tr>
        <td style="width:40%"><span>Point </span><span style="color:red">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="number" id="number" min="0" value="<?php echo (@$setUppoint6['number'] != '') ? @$setUppoint6['number'] : 0; ?>"></td>
    </tr>

    <tr>
        <td style="width:40%"><span>Operator</span></td>
        <td style="width:60%">
            <select class="detailedViewTextBox" name="operator" id="operator">
                <option value="+" <?php if($setUppoint6['operator'] == '+') echo 'selected'; ?>>+</option>
                <option value="*" <?php if($setUppoint6['operator'] == '*') echo 'selected'; ?>>*</option>
            </select>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align:center">
            <input type="hidden" id="userID" value="<?php echo $userID; ?>">
            <button class="crmbutton small save" style="cursor:pointer" onclick="saveSetpoint6('<?php echo $action; ?>', '<?php echo $id; ?>')">Save</button>
            <button class="crmbutton small cancel" style="cursor:pointer" onclick="jQuery('#popup-dialog').window('close')">Cancel</button>
        </td>
    </tr>
</table>
<script type="text/javascript">

    $('#sub_channel').combobox({
        panelHeight:200,
        multiple:true
    });
    var sub_channel = <?php echo json_encode(@$setUppoint6['sub_channel']); ?>;
    if(sub_channel !== undefined && sub_channel !== null && sub_channel.length > 0){
        $('#sub_channel').combobox('setValue', sub_channel);
    }else{
        $('#sub_channel').combobox('setValue', []);
    }

    $('#main_channel').combobox({
        panelHeight:200,
        multiple:true
    });
    var main_channel = <?php echo json_encode(@$setUppoint6['main_channel']); ?>;
    if(main_channel !== undefined && main_channel !== null && main_channel.length > 0){
        $('#main_channel').combobox('setValue', main_channel);
    }else{
        $('#main_channel').combobox('setValue', []);
    }

</script>