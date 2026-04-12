<style>
    .text-danger{
        color: red;
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
        <td style="width:40%"><span>Product Brand </span></td>
        <td style="width:60%">
            <select class="easyui-combobox" name="product_brand[]" id="product_brand" style="width:90%;">
                <?php foreach($product_brand as $product_brand){ 
                    $selected = @$setUppoint2['product_brand'] == $product_brand['product_brand'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $product_brand['product_brand']; ?>"><?php echo $product_brand['product_brand']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td style="width:40%"><span>Material Type </span></td>
        <td style="width:60%">
            <select class="easyui-combobox" name="material_type[]" id="material_type" style="width:90%;">
                <?php foreach($material_type as $material_type){ 
                    $selected = @$setUppoint2['material_type'] == $material_type['material_type'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $material_type['material_type']; ?>"><?php echo $material_type['material_type']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td style="width:40%"><span>รูปแบบสินค้า </span></td>
        <td style="width:60%">
            <select class="easyui-combobox" name="producttype[]" id="producttype" style="width:90%;">
                <?php foreach($producttype as $producttype){ 
                    $selected = @$setUppoint2['producttype'] == $producttype['producttype'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $producttype['producttype']; ?>"><?php echo $producttype['producttype']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td style="width:40%"><span>ประเภทสินค้า </span></td>
        <td style="width:60%">
            <select class="easyui-combobox" name="productcategory[]" id="productcategory" style="width:90%;">
                <?php foreach($productcategory as $productcategory){ 
                    $selected = @$setUppoint2['productcategory'] == $productcategory['productcategory'] ? 'selected':'';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $productcategory['productcategory']; ?>"><?php echo $productcategory['productcategory']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td style="width:40%"><span>Baht/ 1 Point </span><span style="color:red">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox easyui-validatebox" name="bahtperpoint" id="bahtperpoint" min="0" value="<?php echo (@$setUppoint2['bahtperpoint'] != '') ? @$setUppoint2['bahtperpoint'] : 0; ?>" ></td>
    </tr>
    <tr>
        <td style="width:40%"><span>Period Start </span><span style="color:red">*</span></td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="startdate" id="startdate" value="<?php echo @$setUppoint2['startdate']; ?>" re></td>
    </tr>
    <tr>
        <td style="width:40%"><span>Period End </span><span style="color:red">*</span></td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="enddate" id="enddate" value="<?php echo @$setUppoint2['enddate']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%"><span>Minimum Amount </span><span style="color:red">*</span></td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox easyui-validatebox" name="minimum" id="minimum" min="0" value="<?php echo (@$setUppoint2['minimum'] != '') ? @$setUppoint2['minimum'] : 0; ?>" ></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="hidden" id="userID" value="<?php echo $userID; ?>">
            <button class="crmbutton small save" style="cursor:pointer" onclick="saveSetpoint2('<?php echo $action; ?>', '<?php echo $id; ?>')">Save</button>
            <button class="crmbutton small cancel" style="cursor:pointer" onclick="jQuery('#popup-dialog').window('close')">Cancel</button>
        </td>
    </tr>

</table>

<script type="text/javascript">

    $('#producttype').combobox({
        panelHeight:200,
        multiple:true
    });
    var producttype = <?php echo json_encode(@$setUppoint2['producttype']); ?>;
    if(producttype !== undefined && producttype !== null && producttype.length > 0){
        $('#producttype').combobox('setValue', producttype);
    }else{
        $('#producttype').combobox('setValue', []);
    }

    $('#product_brand').combobox({
        panelHeight:200,
        multiple:true
    });
    var product_brand = <?php echo json_encode(@$setUppoint2['product_brand']); ?>;
    if(product_brand !== undefined && product_brand !== null && product_brand.length > 0){
        $('#product_brand').combobox('setValue', product_brand);
    }else{
        $('#product_brand').combobox('setValue', []);
    }

    $('#material_type').combobox({
        panelHeight:200,
        multiple:true
    });
    var material_type = <?php echo json_encode(@$setUppoint2['material_type']); ?>;
    if(material_type !== undefined && material_type !== null && material_type.length > 0){
        $('#material_type').combobox('setValue', material_type);
    }else{
        $('#material_type').combobox('setValue', []);
    }

    $('#productcategory').combobox({
        panelHeight:200,
        multiple:true
    });
    var productcategory = <?php echo json_encode(@$setUppoint2['productcategory']); ?>;
    if(productcategory !== undefined && productcategory !== null && productcategory.length > 0){
        $('#productcategory').combobox('setValue', productcategory);
    }else{
        $('#productcategory').combobox('setValue', []);
    }



    /*
    $('#product_brand').combobox({
        panelHeight:200,
        multiple:true,
        method:'get',
        valueField:'id',
        textField:'text'
    });

    var product_brand = <?php echo json_encode($new_product_brand); ?>;
    $('#product_brand').combobox('setValue', product_brand);

    $('#material_type').combobox({
        panelHeight:200,
        multiple:true,
        method:'get',
        valueField:'id',
        textField:'text'
    });

    var material_type = <?php echo json_encode($new_material_type); ?>;
    $('#material_type').combobox('setValue', material_type);

    $('#productcategory').combobox({
        panelHeight:200,
        multiple:true,
        method:'get',
        valueField:'id',
        textField:'text'
    });

    var productcategory = <?php echo json_encode($new_productcategory); ?>;
    $('#productcategory').combobox('setValue', productcategory);*/

</script>