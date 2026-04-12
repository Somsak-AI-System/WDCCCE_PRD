<div class="overlay" style="display:none">
    <div>
        <div class="loadingio-spinner-ripple">
            <div class="ldio-animate">
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Navbar -->
<div class="top-nav">
    <div class="top-nav-content">
        <?php if ($recordData['samplerequisition_status'] == 'Create') { ?>
            <div class="top-nav-back-icon" onclick="location.href='<?php echo site_url('Samplerequisition/view/' . $crmID . '?userID=' . $userID); ?>'"><i class="ph-caret-left"></i></div>
        <?php } else { ?>
            <div class="top-nav-back-icon" onclick="location.href='<?php echo site_url('Samplerequisition/edit/' . $crmID . '?userID=' . $userID); ?>'"><i class="ph-caret-left"></i></div>
        <?php } ?>

        <span>ข้อมูลใบขอตัวอย่าง</span>
        <div class="top-nav-action flex">
            <div class="top-nav-action-icon flex-none">
                <div>
                    <i class="ph-dots-three-circle" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu dropdown-menu-end top-nav-dropdown">
                        <!-- <li>
                            <button class="dropdown-item" type="button" onclick="$.showPreview()">
                                <i class="ph-eye v-align-middle"></i> ดูตัวอย่าง
                            </button>
                        </li> -->
                        <li>
                            <button class="dropdown-item" type="button" onclick="$.deleteSamplerequisition()">
                                <i class="ph-trash v-align-middle"></i> ลบ
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Top Navbar -->

<!-- Page Content -->
<div class="page-content mt-48">
    <div class="width-full p-10 bg-white" style="position:fixed; left:0px; top:48px;">
        <div class="base-input-group bg-white cursor-pointer" onclick="location.href='<?php echo site_url('Samplerequisition/edit/' . $crmID . '?userID=' . $userID); ?>'">
            <input type="text" class="base-input-text bg-white cursor-pointer" value="ข้อมูลใบขอตัวอย่าง" readonly>
            <div class="base-input-group-action">
                <i class="ph-check-circle text-green-3"></i>
            </div>
        </div>

        <!-- Empty busket -->
        <div id="empty-busket" class="text-center <?php if (count($itemList) > 0) echo 'display-none'; ?>">
            <img class="blank-busket" src="<?php echo base_url('assets/img/icon/busket.png'); ?>">
            <div class="mt-5">ยังไม่ได้เพิ่มรายการ</div>
            <button class="btn btn-primary btn-custom mt-10" onclick="$.showList()">
                เพิ่มรายการ
            </button>
        </div>

        <div id="show-list" class="display-none">
            <div class="flex mt-10 p-10">
                <div class="flex-1">
                    <span id="selected-item-amount"></span> รายการ
                </div>
                <div class="flex-none">
                    <div class="display-inline p-10 cursor-pointer" onclick="$.showList()">
                        <i class="ph-plus v-align-middle font-20"></i>
                    </div>
                    <div class="display-inline p-10 cursor-pointer" onclick="$.deleteAllItem();">
                        <i class="ph-trash v-align-middle font-20"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="selected-list display-none">
    </div>
</div>
<!-- End Page Content -->

<?php $this->load->view('summary'); ?>
<?php $this->load->view('form-search-item'); ?>
<?php $this->load->view('form-add-item'); ?>

<?php //$this->load->view('form-discount'); ?>
<?php //$this->load->view('form-vat'); ?>
<?php //$this->load->view('form-preview'); ?>

<script>
    var crmID = '<?php echo $crmID; ?>'
    var userID = '<?php echo $userID; ?>'
    var quotatusStatus = '<?php echo $recordData['samplerequisition_status']; ?>'
    var productItemList = []
    var serviceItemList = []
    var sparePartItemList = []
    var productItemSelect = []

    var grandTotal = 0
    var grandTotalUnit = 0

    const summaryCalculate = () => {
        if (productItemSelect.length <= 0) {
            grandTotal = 0
            grandTotalUnit = 0
        } else {
           
            unitTotal = 0
            purchaseTotal = 0

            productItemSelect.map(item => {
                unitTotal += item.amount
                purchaseTotal += item.amount_of_purchase
            })

            grandTotalUnit = unitTotal
            grandTotal = purchaseTotal
        }

        $('#grand-total-unit').html(currencyFormat(grandTotalUnit,0))
        $('#grand-total').html(currencyFormat(grandTotal, 0))
    }

    function isNumber(event,obj,lineitem) {
        evt = (event) ? event : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }else{
            var itemAmount = $('#'+lineitem).val();
            //console.log(itemAmount);
            $.isCalculate(obj,lineitem)
            return true;
        }
        
    }   

    const removeComma = num => {
        if (num === undefined || num === null || num === '') return 0;
        return parseFloat(num.toString().replace(/,/g, ''));
    };

    const currencyFormat = (num, dicimal) => {
        dicimal = dicimal === undefined ? 0 : dicimal;
        if (num === undefined || num === '') return '';
        num = removeComma(num);
        if (num === 0) {
            return '0'
        }
        return num.toFixed(dicimal).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    };
    
    $(function() {
       
        $('#btn-save-samplerequisition').prop('disabled', true)

        $.post('<?php echo site_url('Samplerequisition/getProductList'); ?>', {
            crmID
        }, function(rs) {
            if (rs.status) {
                productItemSelect = rs.returnData.itemList;
                
                /*netTotal = rs.returnData.netTotal
                discountType = rs.returnData.discountType
                discountTypeAmount = rs.returnData.discountTypeAmount
                discountAmount = rs.returnData.discountAmount
                totalAfterDiscount = rs.returnData.totalAfterDiscount
                
                vatType = rs.returnData.vatType
                vatPercentage = rs.returnData.vatPercentage
                netVat = rs.returnData.netVat
                totalAfterVat = rs.returnData.totalAfterVat
                grandTotal = rs.returnData.grandTotal*/

                $.reGenRowItem()
                $.genSelectedList()
            }
        }, 'json')

        $.genSelectedList = function() {
            $('.selected-list').html('')
            if (productItemSelect.length > 0) {
                $('#empty-busket').hide();
                $('#selected-item-amount').html(productItemSelect.length)
                $('#show-list').show();
                $('.selected-list').show();
                $('#btn-save-samplerequisition').prop('disabled', false)
            } else {
                $('#empty-busket').show();
                $('#selected-item-amount').html('')
                $('#show-list').hide();
                $('.selected-list').hide();
                $('#btn-save-samplerequisition').prop('disabled', true)
            }
            summaryCalculate()
            
            productItemSelect.map(item => {
                //console.log(item);
                if (item.amount > 0) {
                    var totalPrice = item.amount * item.listprice
                    var itemAva = item.stock - item.amount >= 0 ? item.stock - item.amount : 0;
                    var rowItem = $('<div />', {
                        class: 'list-item-row p-10 mb-5'
                    });
                    var html = `<div class="list-item-row p-10 mb-5">
                        <div class="flex width-full p-5 mb-5">
                            <div class="flex-none">
                                <div class="list-item-icon bg-blue-1">
                                    <i class="ph-rows v-align-middle"></i>
                                </div>
                            </div>
                            <div class="flex-1 pl-10">
                                <div class="flex">
                                    <div class="flex-1">
                                        <div class="font-16 font-bold text-line-clamp-1">${item.name}</div>
                                    </div>
                                    <!-- <div class="flex-none">
                                        x2
                                    </div> -->
                                </div>

                                <div class="flex">
                                    <div class="flex-1">
                                        <div class="font-14 text-gray-4 text-line-clamp-1">${item.no}</div>
                                    </div>
                                    <!-- <div class="flex-none text-gray-4 text-right">
                                        ${currencyFormat(item.listprice)}
                                    </div> -->
                                </div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">หมายเหตุ:</label> ${item.remark}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ชนิดผิว:</label> ${item.product_finish}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ขนาด(มม.):</label> ${item.product_size_mm}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ความหนา(มม.):</label> ${item.product_thinkness}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">หน่วยนับ:</label> ${item.uom}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">จำนวนที่คาดว่าจะใช้:</label> ${item.amount_of_purchase}</div>
                                

                                <div class="float-right" >
                                    <div class="input-group input-group-custom adjust-item-group" data-itemid="${item.id}" data-ava="${itemAva}" style="width:100px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text input-group-text-custom minus-amount" onclick="$.minusItemAmount(this)">
                                                <i class="ph-minus text-primary v-align-middle cursor-pointer"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="line-item-${item.id}" class="form-control border-none text-center add-item-amount" value="${item.amount}" onkeyup="$.isCalculate(event,this,'line-item-${item.id}');">
                                        <div class="input-group-append">
                                            <span class="input-group-text input-group-text-custom plus-amount" onclick="$.plusItemAmount(this)">
                                                <i class="ph-plus text-primary v-align-middle cursor-pointer"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="devider"></div>

                        <div class="flex">
                            <div class="flex-1 row-item-action">
                            </div>
                            
                        </div>
                    </div>`;

                    var deleteBtn = $('<div />', {
                        class: 'display-inline p-10 cursor-pointer'
                    }).html(`<i class="ph-trash v-align-middle font-20"></i>`)
                    var editBtn = $('<div />', {
                        class: 'display-inline p-10 cursor-pointer'
                    }).html(`<i class="ph-pencil-line v-align-middle font-20"></i>`)

                    $(deleteBtn).click(function() {
                        $.deleteItem(this, item)
                    })

                    $(editBtn).click(function() {
                        $.showAddItem(item)
                    })

                    $(rowItem).html(html);
                    $(rowItem).find('.row-item-action').append(deleteBtn)
                    $(rowItem).find('.row-item-action').append(editBtn)
                    $('.selected-list').append(rowItem)
                }
            })
        }

        $.genRowItem = function(item) {
            var rowItem = $('<div />', {
                class: 'flex width-full list-item-row p-5 mb-5'
            })
            var html = `<div class="flex-none">
                    <div class="list-item-icon bg-blue-1">
                        <i class="ph-rows v-align-middle"></i>
                    </div>
                </div>
                <div class="flex-1 pl-10">
                    <div class="flex width-full">
                        <div class="flex-1">
                            <div class="font-16 font-bold text-line-clamp-1">${item.name}</div>
                        </div>
                        <div class="flex-none">`
            if (item.amount > 0) html += `<span class="badge-primary">${item.amount}</span>`
            html += `</div>
                    </div>                
                    <div class="font-12 text-gray-1 text-line-clamp-1">${item.no}</div>
                    <div class="font-14 text-primary">฿ ${item.price_display}</div>
                </div>`
            $(rowItem).html(html);
            $(rowItem).click(function() {
                $.showAddItem(item)
            })

            return rowItem;
        }

        $.isCalculate = function(event,obj,lineitem) {    
            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');
            
            var itemData = productItemSelect.find(e => e.id == itemID)
            
            if (itemData === undefined) {
                var itemProduct = productItemList.find(e => e.id == itemID)
                var itemService = serviceItemList.find(e => e.id == itemID)
                var itemSparePart = sparePartItemList.find(e => e.id == itemID)
                if (itemProduct !== undefined) itemData = itemProduct
                if (itemService !== undefined) itemData = itemService
                if (itemSparePart !== undefined) itemData = itemSparePart
            }

            var itemAmount = $('#'+lineitem).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount);

            itemData.amount = itemAmount
            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()
            
            return true;
        }

        $.isCalculates = function(event,obj,lineitem) {    
            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');
            
            var itemData = productItemSelect.find(e => e.id == itemID)
            
            if (itemData === undefined) {
                var itemProduct = productItemList.find(e => e.id == itemID)
                var itemService = serviceItemList.find(e => e.id == itemID)
                var itemSparePart = sparePartItemList.find(e => e.id == itemID)
                if (itemProduct !== undefined) itemData = itemProduct
                if (itemService !== undefined) itemData = itemService
                if (itemSparePart !== undefined) itemData = itemSparePart
            }

            var itemAmount = $('#'+lineitem).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount);

            itemData.amount = itemAmount
            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()
            return true;
        }

        $.isCalculatespurchase = function(event,obj,lineitem) {    
            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');
            
            var itemData = productItemSelect.find(e => e.id == itemID)
            
            if (itemData === undefined) {
                var itemProduct = productItemList.find(e => e.id == itemID)
                var itemService = serviceItemList.find(e => e.id == itemID)
                var itemSparePart = sparePartItemList.find(e => e.id == itemID)
                if (itemProduct !== undefined) itemData = itemProduct
                if (itemService !== undefined) itemData = itemService
                if (itemSparePart !== undefined) itemData = itemSparePart
            }

            var itemAmount = $('#'+lineitem).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount);

            itemData.amount_of_purchase = itemAmount
            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()
            return true;
        }
        
        $.reGenRowItem = function(type) {
            var newItemList = []

            if (productItemSelect.length > 0) {
                var totalAmount = 0
                var totalPrice = 0
                productItemSelect.map(item => {
                    totalAmount += item.amount
                    totalPrice += (item.amount * item.price)
                })
                $('#total-selected-item').html(totalAmount)
                $('#total-selected-price').html(currencyFormat(totalPrice, 2))
            } else {
                $('#total-selected-item').html(0)
                $('#total-selected-price').html(currencyFormat(0, 2))
            }

            switch (type) {
                case 'products':
                    $('#product-list-item').html('')
                    productItemList.map(item => {
                        var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                        if (itemSelectedData !== undefined) {
                            item = itemSelectedData
                        }

                        newItemList.push(item)
                        var rowItem = $.genRowItem(item)
                        $('#product-list-item').append(rowItem)
                    })
                    productItemList = newItemList
                    break;
                case 'service':
                    $('#service-list-item').html('')
                    serviceItemList.map(item => {
                        var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                        if (itemSelectedData !== undefined) {
                            item = itemSelectedData
                        }

                        newItemList.push(item)
                        var rowItem = $.genRowItem(item)
                        $('#service-list-item').append(rowItem)
                    })
                    serviceItemList = newItemList
                    break;
                case 'sparepart':
                    $('#sparepart-list-item').html('')
                    sparePartItemList.map(item => {
                        var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                        if (itemSelectedData !== undefined) {
                            item = itemSelectedData
                        }

                        newItemList.push(item)
                        var rowItem = $.genRowItem(item)
                        $('#sparepart-list-item').append(rowItem)
                    })
                    sparePartItemList = newItemList
                    break;
            }
        }

        $.updateItem = function(item) {
            //console.log(item);
            var itemIndex = productItemSelect.findIndex(e => e.id === item.id)
            productItemSelect[itemIndex] = item
        }

        $.minusItemAmount = function(obj) {
            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');

            var rootParent = $(parent).parents('.list-item-row')
            var rowTotalPrice = $(rootParent).find('.row-item-total-price')
            var rowDeleteBtn = $(rootParent).find('.minus-amount')

            var itemData = productItemSelect.find(e => e.id == itemID)

            var avaAmount = $(parent).data('ava');
            avaAmount = avaAmount === '' ? 0 : eval(avaAmount);

            var inputItemAmount = $(parent).find('.add-item-amount');
            var itemAmount = $(inputItemAmount).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount)
            
            itemAmount = itemAmount - 1
            //avaAmount = avaAmount + 1;

            if (itemAmount === 0) {
                if (rowDeleteBtn.length > 0) {
                    $.deleteItem(rowDeleteBtn, itemData)
                }
                return false
            }
            if (itemAmount < 0) return false

            itemData.amount = itemAmount
            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()

            $(inputItemAmount).val(itemAmount)
        }

        $.minusItemAmountPurchase = function(obj) {
            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');

            var rootParent = $(parent).parents('.list-item-row')
            var rowTotalPrice = $(rootParent).find('.row-item-total-price')
            var rowDeleteBtn = $(rootParent).find('.minus-amount')

            var itemData = productItemSelect.find(e => e.id == itemID)

            var avaAmount = $(parent).data('ava');
            avaAmount = avaAmount === '' ? 0 : eval(avaAmount);

            var inputItemAmount = $(parent).find('.add-item-amount-purchase');
            var itemAmount = $(inputItemAmount).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount)
            
            itemAmount = itemAmount - 1

            if (itemAmount === 0) {
                if (rowDeleteBtn.length > 0) {
                    $.deleteItem(rowDeleteBtn, itemData)
                }
                return false
            }
            if (itemAmount < 0) return false

            itemData.amount_of_purchase = eval(itemAmount)

            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()

            $(inputItemAmount).val(itemAmount)
        }

        $.keyItemAmount = function(obj) {

            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');
            var itemData = productItemSelect.find(e => e.id == itemID)
            
            if (itemData === undefined) {
                var itemProduct = productItemList.find(e => e.id == itemID)
                if (itemProduct !== undefined) itemData = itemProduct
            }

            var avaAmount = $(parent).data('ava');
            avaAmount = avaAmount === '' ? 0 : eval(avaAmount);
            
            var inputItemAmount = $(parent).find('.add-item-amount');
            var itemAmount = $(inputItemAmount).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount)
           
            var inputItemPrice = $(parent).find('.add-item-listPrice');
            var itemPrice = $(inputItemPrice).val(); 
            itemPrice = itemPrice === '' ? 0 : eval(itemPrice)
           
            itemData.price = itemPrice
            
            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()

            $(inputItemAmount).val(itemAmount)
            var totalPrice = itemAmount * itemData.price

            var rootParent = $(parent).parents('.list-item-row')
            var rowTotalPrice = $(rootParent).find('.row-item-total-price')
            if (rowTotalPrice.length > 0) {
                $(rowTotalPrice).html(currencyFormat(totalPrice, 2))
            } else {
                $('.add-item-total-price').html(currencyFormat(totalPrice, 2))
            }
        }
        
        $.plusItemAmount = function(obj) {
            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');
            var itemData = productItemSelect.find(e => e.id == itemID)
            
            if (itemData === undefined) {
                var itemProduct = productItemList.find(e => e.id == itemID)
                var itemService = serviceItemList.find(e => e.id == itemID)
                var itemSparePart = sparePartItemList.find(e => e.id == itemID)
                if (itemProduct !== undefined) itemData = itemProduct
            }

            var avaAmount = $(parent).data('ava');
            avaAmount = avaAmount === '' ? 0 : eval(avaAmount);
           
            var inputItemAmount = $(parent).find('.add-item-amount');
            var itemAmount = $(inputItemAmount).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount)
            //avaAmount = avaAmount - 1;
            itemAmount = itemAmount + 1;

            //if (avaAmount < 0) return false

            //$(parent).data('ava', avaAmount)
            /*if ($(parent).parents('.adjust-row').length !== 0) $(parent).parents('.adjust-row').find('.add-item-ava').html(avaAmount)*/

            itemData.amount = itemAmount
            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()

            $(inputItemAmount).val(itemAmount)
            //var totalPrice = itemAmount * itemData.price

            //var rootParent = $(parent).parents('.list-item-row')
            //var rowTotalPrice = $(rootParent).find('.row-item-total-price')
            // console.log(rowTotalPrice.length)
            /*if (rowTotalPrice.length > 0) {
                $(rowTotalPrice).html(currencyFormat(totalPrice, 2))
            } else {
                $('.add-item-total-price').html(currencyFormat(totalPrice, 2))
            }*/
        }

        $.plusItemAmountPurchase = function(obj) {
            var parent = $(obj).parents('.adjust-item-group');
            var itemID = $(parent).data('itemid');
            var itemData = productItemSelect.find(e => e.id == itemID)
            
            if (itemData === undefined) {
                var itemProduct = productItemList.find(e => e.id == itemID)
                var itemService = serviceItemList.find(e => e.id == itemID)
                var itemSparePart = sparePartItemList.find(e => e.id == itemID)
                if (itemProduct !== undefined) itemData = itemProduct
            }

            var avaAmount = $(parent).data('ava');
            avaAmount = avaAmount === '' ? 0 : eval(avaAmount);
           
            var inputItemAmount = $(parent).find('.add-item-amount-purchase');
            var itemAmount = $(inputItemAmount).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount)
            itemAmount = itemAmount + 1;

            itemData.amount_of_purchase = eval(itemAmount)

            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()

            $(inputItemAmount).val(itemAmount)
        }

        $.toggleArrow = function() {
            $('.card-bottom-arrow').toggleClass('ph-caret-up ph-caret-down')
        }

        $.closeList = function() {
            $.genSelectedList()
            $('#modal-list').modal('hide');
        }

        $.showList = function() {
            $('#modal-list').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $.closeAddItem = function() {
            $('#modal-add-item .add-item-name').html('')
            $('#modal-add-item .add-item-no').html('')
            $('#modal-add-item .add-item-price').html('')
            $('#modal-add-item .add-item-remark').val('')
            //$('#modal-add-item .add-item-ava').html('')
            $('#modal-add-item .add-item-amount').val('')
            $('#modal-add-item .add-item-amount-purchase').val('')
            $('#modal-add-item .adjust-item-group').data('itemid', '')
            $('#modal-add-item .adjust-item-group').data('ava', '')
            $('#modal-add-item .btn-update-item').data('itemid', '')
            $('#modal-add-item .btn-update-item').data('type', '')
            $('#modal-add-item .add-item-total-price').html('')

            $('#modal-add-item .add-item-name').val('')
            $('#modal-add-item .add-item-listPrice').val('')

            $('#modal-add-item .add-item-sr_product_unit').val('')
            $('#modal-add-item .add-item-sr_finish').val('')
            $('#modal-add-item .add-item-sr_size_mm').val('')
            $('#modal-add-item .add-item-sr_thickness_mm').val('')

            $('#modal-add-item').modal('hide');
        }

        $.showAddItem = function(item) {
            //console.log(item);
            if (item !== undefined) {
                var itemSelectedData = productItemSelect.find(e => e.id === item.id)
                if (itemSelectedData !== undefined) {
                    item = itemSelectedData
                } else {
                    productItemSelect.push(item)
                }
                
                var itemAva = item.stock - item.amount >= 0 ? item.stock - item.amount : 0;
                //var totalPrice = item.amount * item.price;
                var totalPrice = item.amount * item.price;
                if(item.listprice != 0){
                   $('#modal-add-item .add-item-listPrice').val(item.listprice) 
                }else{
                    $('#modal-add-item .add-item-listPrice').val(item.price) 
                }
                $('#modal-add-item .add-item-sr_product_unit').val(item.uom)

                console.log("item.product_finish = ",item.product_finish);
                if(item.product_finish === ''){
                    $('#modal-add-item .add-item-sr_finish').val('Standard')
                }else{
                    $('#modal-add-item .add-item-sr_finish').val(item.product_finish)
                }
                console.log("item.product_size_mm = ",item.product_size_mm);
                if(item.product_size_mm === ''){
                    $('#modal-add-item .add-item-sr_size_mm').val('Standard')
                }else{
                    $('#modal-add-item .add-item-sr_size_mm').val(item.product_size_mm)
                }
                console.log("item.product_thinkness = ",item.product_thinkness);
                if(item.product_thinkness === ''){
                    $('#modal-add-item .add-item-sr_thickness_mm').val('Standard')
                }else{
                    $('#modal-add-item .add-item-sr_thickness_mm').val(item.product_thinkness)
                }

                $('#modal-add-item .add-item-name').html(item.name)
                $('#modal-add-item .add-item-name').val(item.name)
                $('#modal-add-item .add-item-no').html(item.no)
                $('#modal-add-item .add-item-price').html(item.price_display)

                $('#modal-add-item .add-item-remark').val(item.remark)

                $('#modal-add-item .add-item-amount').val(item.amount)
                $('#modal-add-item .add-item-amount-purchase').val(item.amount_of_purchase)

                $('#modal-add-item .adjust-item-group').data('itemid', item.id)
                $('#modal-add-item .adjust-item-group').data('ava', itemAva)
                $('#modal-add-item .btn-update-item').data('itemid', item.id)
                $('#modal-add-item .btn-update-item').data('type', item.type)
                $('#modal-add-item .add-item-total-price').html(currencyFormat(totalPrice, ))    
            }

            $('#modal-add-item').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $.updateSelectedItem = function(obj) {
            var line_item_purchas = $('#line-item-purchase').val();
            if(line_item_purchas == '' || line_item_purchas == '0'){
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกจำนวนที่คาดว่าจะใช้',
                    confirmButtonText: 'OK'
                });
                $('#line-item-purchase').focus();
                return false;
            }
            
            var type = $(obj).data('type')
            var itemID = $(obj).data('itemid')

            if (itemID !== undefined && itemID !== '') {
                var itemProduct = productItemSelect.find(e => e.id === itemID)
                if (itemProduct !== undefined) {
                    var remark = $('#add_item_remark').val()
                    var listPrice = $('#listprice').val()
                   
                    var product_finish = $('#sr_finish').val()
                    var product_size_mm = $('#sr_size_mm').val()
                    var product_thinkness = $('#sr_thickness_mm').val()
                    var uom = $('#sr_product_unit').val()
                    
                    itemProduct.remark = remark
                    itemProduct.listprice = listPrice

                    itemProduct.product_finish = product_finish
                    itemProduct.product_size_mm = product_size_mm
                    itemProduct.product_thinkness = product_thinkness
                    itemProduct.uom = uom

                    $.updateItem(itemProduct)
                }

                $.reGenRowItem(type)
                $.closeAddItem();
                $.genSelectedList();
            }
        }

        $.closeAddDiscount = function() {
            $('#modal-add-discount').modal('hide');
        }

        $.showAddDiscount = function() {

            if (discountType != '') $(`input[name=discount_type][value=${discountType}]`).prop('checked', true);
            $('#discount_type_amount').val(discountTypeAmount)
            $('#cal-discount-before').html(currencyFormat(netTotal, 2))
            $('#cal-discount-amount').html(currencyFormat(discountAmount, 2))
            $('#cal-discount-after').html(currencyFormat(totalAfterDiscount, 2))

            $('#modal-add-discount').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $.closeAddVat = function() {
            $('#modal-add-vat').modal('hide');
        }

        $.showAddVat = function() {
            $('#vat_type').val(vatType)
            $('#vat_percentage').val(vatPercentage)

            $('#modal-add-vat').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $.closePreview = function() {
            $('#modal-preview').modal('hide');
        }

        $.deleteItem = function(obj, item) {
            Swal.fire({
                title: '',
                text: 'คุณต้องการลบรายการนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#3199e9">ไม่ใช่</span?',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(obj).parents('.list-item-row').remove();

                    item.amount = 0
                    item.remark = ''
                    $.updateItem(item)
                    $.reGenRowItem(item.type)

                    productItemSelect = productItemSelect.filter(e => e.id !== item.id)
                    $.genSelectedList();

                    Swal.fire('', 'ลบข้อมูลสำเร็จ', 'success')
                }
            })
        }

        $.deleteAllItem = function() {
            Swal.fire({
                title: '',
                text: 'คุณต้องการลบรายการทั้งหมดหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#3199e9">ไม่ใช่</span?',
            }).then((result) => {
                if (result.isConfirmed) {

                    productItemSelect.map(item => {
                        item.amount = 0
                        item.remark = ''

                        $.updateItem(item)
                        $.reGenRowItem(item.type)
                    })

                    productItemSelect = []
                    $.genSelectedList();

                    Swal.fire('', 'ลบข้อมูลสำเร็จ', 'success')
                }
            })
        }

        $.saveData = function() {
            Swal.fire({
                title: '',
                text: 'คุณต้องการบันทึกข้อมูลใบขอตัวอย่างหรือไม่ ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#3199e9">ไม่ใช่</span?',
            }).then((result) => {

                if (result.isConfirmed) {
                    $.post('<?php echo site_url('Samplerequisition/saveItemList'); ?>', {
                        // isRevise,
                        grandTotal,
                        grandTotalUnit,
                        itemList: productItemSelect
                    }, function(rs) {
                        if (rs.status) {
                            Swal.fire({
                                icon: 'success',
                                showConfirmButton: false,
                                html: `<div class="mb-15">บันทึกใบขอตัวอย่างสำเร็จ</div>
                                <button type="button" class="btn btn-primary btn-custom width-full mb-5" onclick="$.requestApprove(${rs.crmID})">ขออนุมัติทันที</button>
                                <button type="button" class="btn btn-primary btn-custom width-full mb-5" onclick="$.viewSamplerequisition(${rs.crmID})">ขออนุมัติภายหลัง</button>
                                <button type="button" class="btn btn-custom width-full mb-5" onclick="$.viewSamplerequisition(${rs.crmID})">ปิด</button>`
                            })
                        } else {
                            Swal.fire('', 'Update Error', 'error')
                        }
                    }, 'json')


                }
            })
        }

        $.deleteSamplerequisition = function() {
            Swal.fire({
                title: '',
                html: "คุณต้องการ<br/>ลบรายการนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#3199e9">ไม่ใช่</span?',
            }).then((result) => {

                if (result.isConfirmed) {
                    $.post('<?php echo site_url('Samplerequisition/deleteSamplerequisition'); ?>', {
                        crmID,
                        userID
                    }, function(rs) {
                        
                        if (rs.status) {

                            Swal.fire('', 'ลบรายการสำเร้จ', 'success')
                            window.location.href = '<?php echo site_url('Samplerequisition/create?userid=' . $this->session->userdata('userID') . '/back'); ?>'
                        } else {
                            Swal.fire('', 'Update Error', 'error')
                        }
                    }, 'json')
                }
            })
        }

        $.requestApprove = function(crmID) {
            $('.overlay').show();
            $.post('<?php echo site_url('Samplerequisition/requestApprove'); ?>', {
                crmID
            }, function(rs) {
                Swal.close()
                if (rs.status) {
                    window.location.href = '<?php echo site_url('Samplerequisition/view/' . $crmID . '?userID=' . $userID); ?>'
                } else {
                    Swal.fire('', rs.msg, 'error')
                    $('.overlay').hide();
                }
            }, 'json')
        }

        $.viewSamplerequisition = function() {
            $('.overlay').show();
            Swal.close()
            window.location.href = '<?php echo site_url('Samplerequisition/view/' . $crmID . '?userID=' . $userID); ?>'
        }
    })
</script>