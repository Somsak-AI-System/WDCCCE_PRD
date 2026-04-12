<div class="nav-search-group bg-white">
    <div class="base-search-group bg-white">
        <div class="base-search-group-action">
            <i class="ph-magnifying-glass" for=""></i>
        </div>
        <input type="text" class="base-input-text bg-white" id="search-product-key" onkeyup="$.searchProduct(this)" name="" placeholder="ค้นหาสินค้าของคุณได้ที่นี้">
    </div>
    <div class="flex my-10">
        <div class="flex-1">
            ทั้งหมด
        </div>
        <div class="flex-none">
            <span id="list-product-total"></span> รายการ
        </div>
    </div>
</div>

<div id="product-list-item" class="list-item">
    
</div>

<script>
    var productOffset = 0;
    $(function(){
        $.searchProduct = function(obj){
            productOffset = 0
            var len = $(obj).val().length
            if(len >= 3){
                $('#product-list-item').html('')
                var searchKey = $(obj).val();
                $.getProductList(searchKey)
            }else if(len === 0){
                $('#product-list-item').html('')
                $.getProductList()
            }
        }

        $.getProductList = function(searchKey){
            searchKey = searchKey === undefined ? '':searchKey;
            productItemList = productOffset === 0 ? []:productItemList
            $.post('<?php echo site_url('Samplerequisition/getItemList'); ?>', {module:'Products', offset:productOffset, searchKey}, function(rs){
                $('#list-product-total').html(rs.total)

                if(rs.resultList.length > 0){
                    rs.resultList.map(item => {
                        var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                        console.log(itemSelectedData);
                        
                        if(itemSelectedData !== undefined){
                            item.amount = itemSelectedData.amount;
                            item.amount_of_purchase = itemSelectedData.amount_of_purchase;
                            item.remark = itemSelectedData.remark;
                            item.listprice = itemSelectedData.listprice;
                            item.price = itemSelectedData.price;
                            
                            item.product_finish = itemSelectedData.product_finish;
                            item.product_size_mm = itemSelectedData.product_size_mm;
                            item.product_thinkness = itemSelectedData.product_thinkness;
                            item.uom = itemSelectedData.uom;

                            item.type = 'products';
                            $.updateItem(item)
                            
                            var findItem = productItemList.find(e => e.id === item.id)
                            if(findItem === undefined) productItemList.push(item)
                        }else{
                            item.amount = 0;
                            item.amount_of_purchase = 0;
                            item.listprice = '0';

                            item.type = 'products';
                            productItemList.push(item)
                        }
                        
                        var rowItem = $.genRowItem(item)
                        $('#product-list-item').append(rowItem)
                    })
                    productOffset = productOffset + 20;
                }else{
                    productOffset = productOffset - 20;
                }
                
            },'json')
        }
        $.getProductList()

    })
</script>