<div class="nav-search-group bg-white">
    <div class="base-search-group bg-white">
        <div class="base-search-group-action">
            <i class="ph-magnifying-glass" for=""></i>
        </div>
        <input type="text" class="base-input-text bg-white" id="search-sparepart-key" onkeyup="$.searchSparePart(this)" name="" placeholder="ค้นหาอะไหล่ของคุณได้ที่นี้">
    </div>
    <div class="flex my-10">
        <div class="flex-1">
            ทั้งหมด
        </div>
        <div class="flex-none">
            <span id="list-sparepart-total"></span> รายการ
        </div>
    </div>
</div>

<div id="sparepart-list-item" class="list-item">
</div>

<script>
    var sparePartOffset = 0;
    $(function(){
        $.searchSparePart = function(obj){
            sparePartOffset = 0
            var len = $(obj).val().length
            if(len >= 3){
                $('#sparepart-list-item').html('')
                var searchKey = $(obj).val();
                $.getSparePartList(searchKey)
            }else if(len === 0){
                $('#sparepart-list-item').html('')
                $.getSparePartList()
            }
        }

        $.getSparePartList = function(searchKey){
            searchKey = searchKey === undefined ? '':searchKey;
            sparePartItemList = sparePartOffset === 0 ? []:sparePartItemList
            $.post('<?php echo site_url('Quotes/getItemList'); ?>', {module:'Sparepart', offset:sparePartOffset, searchKey}, function(rs){
                $('#list-sparepart-total').html(rs.total)
                if(rs.resultList.length > 0){
                    rs.resultList.map(item => {
                        var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                        if(itemSelectedData !== undefined){
                            item.amount = itemSelectedData.amount;
                            item.remark = itemSelectedData.remark;
                            item.type = 'sparepart';
                            $.updateItem(item)

                            var findItem = sparePartItemList.find(e => e.id === item.id)
                            if(findItem === undefined) sparePartItemList.push(item)
                        }else{
                            item.amount = 0;
                            item.remark = '';
                            item.type = 'sparepart';
                            sparePartItemList.push(item)
                        }
                        
                        var rowItem = $.genRowItem(item)
                        $('#sparepart-list-item').append(rowItem)
                    })
                    sparePartOffset = sparePartOffset + 20;
                }else{
                    sparePartOffset = sparePartOffset - 20;
                }
                
            },'json')
        }
        $.getSparePartList()

        $('#sparepart-list-item').on('scroll', function() {
            let div = $(this).get(0);
            if(div.scrollTop + div.clientHeight >= div.scrollHeight) {
                var searchKey = $('#search-sparepart-key').val()
                var len = searchKey.length
                if(len === 0 || len >=3){
                    $.getSparePartList(searchKey)
                }
            }
        });
    })
</script>