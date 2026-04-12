<style>
.text-danger{
    color: red;
}
.detailedViewTextBox  {
    font-family: inherit;
}
#operator {
    font-size: 20px;
    height: 25px;
}
</style>

<?php

//echo '<pre>'; print_r($new_category); echo '</pre>';
?>
<table style="width:100%">
    <tr>
        <td style="width:40%">Brand </td>
        <td style="width:60%">
            <select class="easyui-combobox" style="width:90%;" name="brand[]" id="brand">
                <?php foreach($brands as $brand){
                    if($brand['cf_1731'] == '') continue;
                    ?>
                    <option value="<?php echo $brand['cf_1731']; ?>"><?php echo $brand['cf_1731']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">Material group </td>
        <td style="width:60%">
            <select class="easyui-combobox" style="width:90%;" name="material_group[]" id="material_group">
                <?php foreach($materials_group as $material_group){
                    if($material_group['cf_1728'] == '') continue;
                    ?>
                    <option value="<?php echo $material_group['cf_1728']; ?>"><?php echo $material_group['cf_1728']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">Category </td>
        <td style="width:60%">
            <select class="easyui-combobox" name="category[]" id="category" style="width:90%;" >
                <?php foreach($categories as $category){
                    if($category['cf_1729'] == '') continue;
                    ?>
                    <option value="<?php echo $category['cf_1729']; ?>"><?php echo $category['cf_1729']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">Product Hierarchy </td>
        <td style="width:60%">
            <select class="easyui-combobox" style="width:90%;" name="product_hierarchy[]" id="product_hierarchy">
                <?php foreach($products_hierarchy as $product_hierarchy){
                    if($product_hierarchy['cf_1730'] == '') continue;
                    ?>
                    <option value="<?php echo $product_hierarchy['cf_1730']; ?>"><?php echo $product_hierarchy['cf_1730']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">Product Code </td>
        <td style="width:60%"><input type="type" class="detailedViewTextBox" name="productcode" id="productcode" value="<?php echo @$recordData['productcode']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%">Campaign </td>
        <td style="width:60%">
            <select class="easyui-combobox" style="width:90%;" name="campaign[]" id="campaign">
                <?php foreach($campaigns as $campaign){
                    ?>
                    <option value="<?php echo $campaign['campaignid']; ?>"><?php echo $campaign['campaignname']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">ร้านค้า </td>
        <td style="width:60%">
            <select class="easyui-combobox" style="width:90%;" name="market[]" id="market">
                <?php foreach($markets as $market){ ?>
                    <option value="<?php echo $market['market']; ?>"><?php echo $market['market']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">Period Start </td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="startdate" id="startdate" value="<?php echo @$recordData['startdate']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%">Period End </td>
        <td style="width:60%"><input style="width:90%;height:30px;" name="enddate" id="enddate" value="<?php echo @$recordData['enddate']; ?>"></td>
    </tr>
    <tr>
        <td style="width:40%">Operator </td>
        <td style="width:60%">
            <select class="detailedViewTextBox" name="operator" id="operator">
                <option value="+" <?php if($recordData['operator'] == '+') echo 'selected'; ?>>+</option>
                <option value="*" <?php if($recordData['operator'] == '*') echo 'selected'; ?>>*</option>
            </select>
        </td>
    </tr>
    <tr>
        <td style="width:40%">Number </td>
        <td style="width:60%"><input type="number" class="detailedViewTextBox" name="number" id="number" value="<?php echo @$recordData['number']; ?>"></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="hidden" id="userID" value="<?php echo $userID; ?>">
            <button class="crmbutton small save" style="cursor:pointer" onclick="saveSetSerialByProduct('<?php echo $action; ?>', '<?php echo $id; ?>')">Save</button>
            <button class="crmbutton small cancel" style="cursor:pointer" onclick="jQuery('#popup-dialog').window('close')">Cancel</button>
        </td>
    </tr>
</table>
<script type="text/javascript">

    $('#brand').combobox({
        panelHeight:200,
        multiple:true
    });

    $('#material_group').combobox({
        panelHeight:200,
        multiple:true
    });

    $('#category').combobox({
        panelHeight:200,
        multiple:true
    });

    $('#product_hierarchy').combobox({
        panelHeight:200,
        multiple:true
    });

    $('#campaign').combobox({
        panelHeight:200,
        multiple:true
    });

    $('#market').combobox({
        panelHeight:200,
        multiple:true
    });

    var brand = <?php echo json_encode(@$recordData['brand']); ?>;
    if(brand !== undefined && brand !== null && brand.length > 0){
        $('#brand').combobox('setValue', brand);
    }else{
        $('#brand').combobox('setValue', []);
    }

    var material_group = <?php echo json_encode(@$recordData['materialgroup']); ?>;
    if(material_group !== undefined && material_group !== null && material_group.length > 0){
        $('#material_group').combobox('setValue', material_group);
    }else{
        $('#material_group').combobox('setValue', []);
    }

    var category = <?php echo json_encode(@$recordData['category']); ?>;
    if(category !== undefined && category !== null && category.length > 0){
        $('#category').combobox('setValue', category);
    }else{
        $('#category').combobox('setValue', []);
    }

    var product_hierarchy = <?php echo json_encode(@$recordData['producthierachy']); ?>;
    if(product_hierarchy !== undefined && product_hierarchy !== null && product_hierarchy.length > 0){
        $('#product_hierarchy').combobox('setValue', product_hierarchy);
    }else{
        $('#product_hierarchy').combobox('setValue', []);
    }

    var campaign = <?php echo json_encode(@$recordData['campaign']); ?>;
    if(campaign !== undefined && campaign !== null && campaign.length > 0){
        $('#campaign').combobox('setValue', campaign);
    }else{
        $('#campaign').combobox('setValue', []);
    }

    var market = <?php echo json_encode(@$recordData['market']); ?>;
    if(market !== undefined && market !== null && market.length > 0){
        $('#market').combobox('setValue', market);
    }else{
        $('#market').combobox('setValue', []);
    }

</script>