<div class="nav-search-group bg-white">
    <div class="base-search-group bg-white">
        <div class="base-search-group-action">
            <i class="ph-magnifying-glass" for=""></i>
        </div>
        <input type="text" class="base-input-text bg-white" id="search-service-key" onkeyup="$.searchService(this)" name="" placeholder="ค้นหาบริการของคุณได้ที่นี้">
    </div>
    <div class="flex my-10">
        <div class="flex-1">
            ทั้งหมด
        </div>
        <div class="flex-none">
            <span id="list-service-total"></span> รายการ
        </div>
    </div>
</div>

<div id="service-list-item" class="list-item">
    
</div>

<script>
    var serviceOffset = 0;
    $(function(){
        $.searchService = function(obj){
            serviceOffset = 0
            var len = $(obj).val().length
            if(len >= 3){
                $('#service-list-item').html('')
                var searchKey = $(obj).val();
                $.getServiceList(searchKey)
            }else if(len === 0){
                $('#service-list-item').html('')
                $.getServiceList()
            }
        }

        $.getServiceList = function(searchKey){
            searchKey = searchKey === undefined ? '':searchKey;
            serviceItemList = serviceOffset === 0 ? []:serviceItemList
            $.post('<?php echo site_url('Quotes/getItemList'); ?>', {module:'Service', offset:serviceOffset, searchKey}, function(rs){
                $('#list-service-total').html(rs.total)
                if(rs.resultList.length > 0){
                    rs.resultList.map(item => {
                        var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                        if(itemSelectedData !== undefined){
                            item.amount = itemSelectedData.amount;
                            item.remark = itemSelectedData.remark;
                            item.type = 'service';
                            $.updateItem(item)

                            var findItem = serviceItemList.find(e => e.id === item.id)
                            if(findItem === undefined) serviceItemList.push(item)
                        }else{
                            item.amount = 0;
                            item.remark = '';
                            item.type = 'service';
                            serviceItemList.push(item)
                        }
                        
                        var rowItem = $.genRowItem(item)
                        $('#service-list-item').append(rowItem)
                    })
                    serviceOffset = serviceOffset + 20;
                }else{
                    serviceOffset = serviceOffset - 20;
                }
                
            },'json')
        }
        $.getServiceList()

        $('#service-list-item').on('scroll', function() {
            let div = $(this).get(0);
            if(div.scrollTop + div.clientHeight >= div.scrollHeight) {
                var searchKey = $('#search-service-key').val()
                var len = searchKey.length
                if(len === 0 || len >=3){
                    $.getServiceList(searchKey)
                }
            }
        });
    })
</script>