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
            $.post('<?php echo site_url('Quotes/getItemList'); ?>', {module:'Products', offset:productOffset, searchKey, relatedcrmid:crmID}, function(rs){
                $('#list-product-total').html(rs.total)

                if(rs.resultList.length > 0){
                    rs.resultList.map(item => {
                        var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                        //productItemListconsole.log(itemSelectedData);
                        
                        if(itemSelectedData !== undefined){
                            item.amount = itemSelectedData.amount;
                            item.remark = itemSelectedData.remark;
                            item.listprice = itemSelectedData.listprice;
                            item.price = itemSelectedData.price;
                            
                            item.product_finish = itemSelectedData.product_finish;
                            item.product_size_mm = itemSelectedData.product_size_mm;
                            item.product_thinkness = itemSelectedData.product_thinkness;
                            item.competitor_brand = itemSelectedData.competitor_brand;
                            item.competitor_price = itemSelectedData.competitor_price;
                            item.compet_brand_in_proj = itemSelectedData.compet_brand_in_proj;
                            item.compet_brand_in_proj_price = itemSelectedData.compet_brand_in_proj_price;
                            item.product_cost_avg = itemSelectedData.product_cost_avg;
                            item.uom = itemSelectedData.uom;
                            item.type = 'products';
                            $.updateItem(item)
                            
                            var findItem = productItemList.find(e => e.id === item.id)
                            if(findItem === undefined) productItemList.push(item)
                        }else{
                            item.amount = 0;
                            //item.remark = '';
                            item.listprice = '0';

                            item.competitor_brand = '--None--';
                            item.compet_brand_in_proj = '--None--';
                            
                            item.competitor_price = '';
                            item.compet_brand_in_proj_price = '';

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

        // $('#product-list-item').on('scroll', function() {
        //     let div = $(this).get(0);
        //     if(div.scrollTop + div.clientHeight >= div.scrollHeight) {
        //         var searchKey = $('#search-product-key').val()
        //         var len = searchKey.length
        //         if(len === 0 || len >=3){
        //             $.getProductList(searchKey)
        //         }
        //     }
        // });
    })
</script>