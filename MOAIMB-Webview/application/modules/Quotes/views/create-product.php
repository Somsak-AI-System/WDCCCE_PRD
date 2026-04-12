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
        <?php if ($recordData['quotation_status'] == 'เปิดใบเสนอราคา') { ?>
            <div class="top-nav-back-icon" onclick="location.href='<?php echo site_url('Quotes/view/' . $crmID . '?userID=' . $userID); ?>'"><i class="ph-caret-left"></i></div>
        <?php } else { ?>
            <div class="top-nav-back-icon" onclick="location.href='<?php echo site_url('Quotes/edit/' . $crmID . '?userID=' . $userID); ?>'"><i class="ph-caret-left"></i></div>
        <?php } ?>

        <span>ข้อมูลใบเสนอบริการ</span>
        <div class="top-nav-action flex">
            <div class="top-nav-action-icon flex-none">
                <div>
                    <i class="ph-dots-three-circle" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu dropdown-menu-end top-nav-dropdown">
                        <li>
                            <button class="dropdown-item" type="button" onclick="$.showPreview()">
                                <i class="ph-eye v-align-middle"></i> ดูตัวอย่าง
                            </button>
                        </li>
                        <!-- <li>
                            <button class="dropdown-item" type="button" onclick="popupshare(null)">
                                <i class="ph-share v-align-middle"></i> แชร์
                            </button>
                        </li> -->
                        <li>
                            <button class="dropdown-item" type="button" onclick="$.deleteQuotes()">
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
        <div class="base-input-group bg-white cursor-pointer" onclick="location.href='<?php echo site_url('Quotes/edit/' . $crmID . '?userID=' . $userID); ?>'">
            <input type="text" class="base-input-text bg-white cursor-pointer" value="ข้อมูลใบเสนอบริการ" readonly>
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
<?php $this->load->view('form-discount'); ?>
<?php $this->load->view('form-vat'); ?>
<?php $this->load->view('form-preview'); ?>

<script>
    var crmID = '<?php echo $crmID; ?>'
    var userID = '<?php echo $userID; ?>'
    var quotatusStatus = '<?php echo $recordData['quotation_status']; ?>'
    var productItemList = []
    var serviceItemList = []
    var sparePartItemList = []
    var productItemSelect = []

    var netTotal = 0
    var unitTotal  = 0
    var discountType = '2'
    var discountTypeAmount = 0
    var discountAmount = 0
    var totalAfterDiscount = 0
    var totalBeforeVat = 0
    var vatType = 'ไม่รวมภาษี'
    var vatPercentage = 7
    var netVat = 0
    var totalAfterVat = 0
    var grandTotal = 0

    const summaryCalculate = () => {
        if (productItemSelect.length <= 0) {
            netTotal = 0
            discountAmount = 0
            totalAfterDiscount = 0
            totalBeforeVat = 0
            netVat = 0
            totalAfterVat = 0
            grandTotal = 0
            grandTotalUnit = 0
        } else {
            netTotal = 0
            unitTotal = 0
            productItemSelect.map(item => {
                netTotal += (item.price * item.amount)
                unitTotal += item.amount
            })

            if (discountType == '1') {
                discountAmount = (netTotal * discountTypeAmount) / 100
            } else {
                discountAmount = discountTypeAmount
            }
            totalAfterDiscount = netTotal - discountAmount
            vatPercentage = parseInt(vatPercentage)
            
            if (vatType == 'รวมภาษี') {
                if (vatPercentage == '' || vatPercentage == 0) {
                    $('#tax-percent-text').html(``)
                    $('#net-tax-percent-text').html(``)
                    netVat = 0
                    totalBeforeVat = totalAfterDiscount - netVat
                    totalAfterVat = totalBeforeVat + netVat
                } else {
                    $('#tax-percent-text').html(`(รวม ${vatPercentage}%)`)
                    $('#net-tax-percent-text').html(`(รวม ${vatPercentage}%)`)
                    netVat = (totalAfterDiscount-(totalAfterDiscount/((vatPercentage+100)/100)))
                    totalBeforeVat = totalAfterDiscount - netVat
                    totalAfterVat = totalBeforeVat + netVat
                }

                //netVat = (totalAfterDiscount * vatPercentage) / 100
                //netVat = totalAfterDiscount-(totalAfterDiscount / ((vatPercentage+100)/100))                
                
            } else if (vatType == 'ไม่รวมภาษี') {
                if (vatPercentage == '' || vatPercentage == 0) {
                    $('#tax-percent-text').html(``)
                    $('#net-tax-percent-text').html(``)
                } else {
                    $('#tax-percent-text').html(`(ไม่รวม ${vatPercentage}%)`)
                    $('#net-tax-percent-text').html(`(ไม่รวม ${vatPercentage}%)`)
                }

                netVat = (totalAfterDiscount * vatPercentage) / 100
                totalBeforeVat = totalAfterDiscount
                totalAfterVat = totalBeforeVat + netVat
            } else {
                $('#tax-percent-text').html(``)
                $('#net-tax-percent-text').html(``)
                totalBeforeVat = totalAfterDiscount
                totalAfterVat = totalBeforeVat
                netVat = 0
            }

            grandTotalUnit = unitTotal
            grandTotal = totalAfterVat
        }


        $('#net-total').html(currencyFormat(netTotal, 2))

        $('#cal-discount-before').html(currencyFormat(netTotal, 2))
        $('#cal-discount-amount').html(currencyFormat(discountAmount, 2))
        $('#cal-discount-after').html(currencyFormat(totalAfterDiscount, 2))

        $('#cat-vat-net').html(currencyFormat(netVat, 2))
        $('#cat-vat-before').html(currencyFormat(totalBeforeVat, 2))
        $('#cat-vat-after').html(currencyFormat(totalAfterVat, 2))

        $('#discount-amount').html(currencyFormat(discountAmount, 2))
        $('#total-after-discount').html(currencyFormat(totalAfterDiscount, 2))
        $('#total-before-vat').html(currencyFormat(totalBeforeVat, 2))

        $('#net-vat').html(currencyFormat(netVat, 2))
        $('#total-after-vat').html(currencyFormat(totalAfterVat, 2))
        $('#grand-total-unit').html(currencyFormat(grandTotalUnit))
        $('#grand-total').html(currencyFormat(grandTotal, 2))
    }

    function popupshare(data) {
        const shareData = {
            title: 'MDN',
            text: 'Learn web development on MDN!',
            url: 'https://developer.mozilla.org'
        }

        try {
            navigator.share(shareData)
        } catch (err) {
            console.log('Error: ' + err)
        }
    }

    function popupcopyscript(info) {
        var $temp = $("<input>");
        var $copy_url = info.getAttribute('link');
        $("body").append($temp);
        $temp.val($copy_url).select();
        document.execCommand("copy");
        $temp.remove();
        info.innerHTML = "URL คัดลอกแล้ว";
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

    /*function keyItemAmount(obj) {
        var parent = $(obj).parents('.adjust-item-group');
        var itemID = $(parent).data('itemid');
        var itemData = productItemSelect.find(e => e.id == itemID)
        if (itemData === undefined) {
            var itemProduct = productItemList.find(e => e.id == itemID)
            if (itemProduct !== undefined) itemData = itemProduct
        }

        var avaAmount = $(parent).data('ava');
        avaAmount = avaAmount === '' ? 0 : eval(avaAmount);
        console.log(avaAmount);
        var inputItemAmount = $(parent).find('.add-item-amount');
        var itemAmount = $(inputItemAmount).val();
        itemAmount = itemAmount === '' ? 0 : eval(itemAmount)
        console.log(itemAmount);
        itemData.amount = itemAmount
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
    }*/

    const removeComma = num => {
        if (num === undefined || num === null || num === '') return 0;
        return parseFloat(num.toString().replace(/,/g, ''));
    };

    const currencyFormat = (num, dicimal) => {
        dicimal = dicimal === undefined ? 0 : dicimal;
        if (num === undefined || num === '') return '';
        num = removeComma(num);
        if (num === 0) {
            return '0.00'
        }
        return num.toFixed(dicimal).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    };
    $(function() {
        // setTimeout(function() {
        //     $('.overlay').show();
        // }, 1000)
        $('#btn-save-quotes').prop('disabled', true)

        $.post('<?php echo site_url('Quotes/getProductList'); ?>', {
            crmID
        }, function(rs) {
            if (rs.status) {
                productItemSelect = rs.returnData.itemList;
                netTotal = rs.returnData.netTotal
                discountType = rs.returnData.discountType
                discountTypeAmount = rs.returnData.discountTypeAmount
                discountAmount = rs.returnData.discountAmount
                totalAfterDiscount = rs.returnData.totalAfterDiscount
                // totalBeforeVat = rs.returnData.
                vatType = rs.returnData.vatType
                vatPercentage = rs.returnData.vatPercentage
                netVat = rs.returnData.netVat
                totalAfterVat = rs.returnData.totalAfterVat
                grandTotal = rs.returnData.grandTotal

                //console.log(productItemSelect);
                $.reGenRowItem()
                $.genSelectedList()

                // summaryCalculate()
            }
        }, 'json')

        $.genSelectedList = function() {
            $('.selected-list').html('')
            if (productItemSelect.length > 0) {
                $('#empty-busket').hide();
                $('#selected-item-amount').html(productItemSelect.length)
                $('#show-list').show();
                $('.selected-list').show();
                $('#btn-save-quotes').prop('disabled', false)
            } else {
                $('#empty-busket').show();
                $('#selected-item-amount').html('')
                $('#show-list').hide();
                $('.selected-list').hide();
                $('#btn-save-quotes').prop('disabled', true)
            }
            summaryCalculate()
            
            //console.log(productItemSelect);
            
            productItemSelect.map(item => {
                
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
                                    <div class="flex-none text-gray-4 text-right">
                                        ${currencyFormat(item.listprice)}
                                    </div>
                                </div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">หมายเหตุ:</label> ${item.remark}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ชนิดผิว:</label> ${item.product_finish}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ขนาด(มม.):</label> ${item.product_size_mm}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ความหนา(มม.):</label> ${item.product_thinkness}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">แบรนด์คู่แข่ง:</label> ${item.competitor_brand}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ราคาคู่แข่ง (Ecx.VAT):</label> ${currencyFormat(item.competitor_price)}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">ราคาคู่แข่งในโครงการ (Ecx.VAT):</label> ${currencyFormat(item.compet_brand_in_proj_price)}</div>
                                <div class="font-14 text-gray-4"><label class="text-black-1">รวมต้นทุนจริงเฉลี่ย:</label> ${currencyFormat(item.product_cost_avg)}</div>

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
                            <div class="flex-none text-primary text-right width-150">
                                ฿ <span class="row-item-total-price">${currencyFormat(totalPrice, 2)}</span>
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
            //console.log(item);
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
            var totalPrice = itemAmount * itemData.price

            var rootParent = $(parent).parents('.list-item-row')
            var rowTotalPrice = $(rootParent).find('.row-item-total-price')

            if (rowTotalPrice.length > 0) {
                $(rowTotalPrice).html(currencyFormat(totalPrice, 2))
            } else {
                $('.add-item-total-price').html(currencyFormat(totalPrice, 2))
            }

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
            var totalPrice = itemAmount * itemData.price

            var rootParent = $(parent).parents('.list-item-row')
            var rowTotalPrice = $(rootParent).find('.row-item-total-price')

            if (rowTotalPrice.length > 0) {
                $(rowTotalPrice).html(currencyFormat(totalPrice, 2))
            } else {
                $('.add-item-total-price').html(currencyFormat(totalPrice, 2))
            }

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

            //$(parent).data('ava', avaAmount)
            //if ($(parent).parents('.adjust-row').length !== 0) $(parent).parents('.adjust-row').find('.add-item-ava').html(avaAmount)

            itemData.amount = itemAmount
            $.updateItem(itemData)
            $.reGenRowItem(itemData.type)
            summaryCalculate()

            $(inputItemAmount).val(itemAmount)
            var totalPrice = itemAmount * itemData.price

            // console.log(rowTotalPrice.length)
            if (rowTotalPrice.length > 0) {
                $(rowTotalPrice).html(currencyFormat(totalPrice, 2))
            } else {
                $('.add-item-total-price').html(currencyFormat(totalPrice, 2))
            }
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
                if (itemService !== undefined) itemData = itemService
                if (itemSparePart !== undefined) itemData = itemSparePart
            }

            var avaAmount = $(parent).data('ava');
            avaAmount = avaAmount === '' ? 0 : eval(avaAmount);
           
            var inputItemAmount = $(parent).find('.add-item-amount');
            var itemAmount = $(inputItemAmount).val();
            itemAmount = itemAmount === '' ? 0 : eval(itemAmount)
            //console.log(itemAmount);
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
            var totalPrice = itemAmount * itemData.price

            var rootParent = $(parent).parents('.list-item-row')
            var rowTotalPrice = $(rootParent).find('.row-item-total-price')
            // console.log(rowTotalPrice.length)
            if (rowTotalPrice.length > 0) {
                $(rowTotalPrice).html(currencyFormat(totalPrice, 2))
            } else {
                $('.add-item-total-price').html(currencyFormat(totalPrice, 2))
            }
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
            $('#modal-add-item .adjust-item-group').data('itemid', '')
            $('#modal-add-item .adjust-item-group').data('ava', '')
            $('#modal-add-item .btn-update-item').data('itemid', '')
            $('#modal-add-item .btn-update-item').data('type', '')
            $('#modal-add-item .add-item-total-price').html('')

            $('#modal-add-item .add-item-name').val('')
            $('#modal-add-item .add-item-listPrice').val('')
            $('#modal-add-item .add-item-uom').val('')
            $('#modal-add-item .add-item-product_cost_avg').val('')
            $('#modal-add-item .add-item-product_finish').val('')
            $('#modal-add-item .add-item-product_size_mm').val('')
            $('#modal-add-item .add-item-product_thinkness').val('')
            $('#modal-add-item .add-item-competitor_brand').val('--None--')
            $('#modal-add-item .add-item-competitor_price').val('')
            $('#modal-add-item .add-item-compet_brand_in_proj').val('--None--')
            $('#modal-add-item .add-item-compet_brand_in_proj_price').val('')

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
                
                $('#modal-add-item .add-item-uom').val(item.uom)
                $('#modal-add-item .add-item-competitor_price').val(item.competitor_price)
                $('#modal-add-item .add-item-product_cost_avg').val(item.product_cost_avg)

                $('#modal-add-item .add-item-competitor_brand').val(item.competitor_brand)
                $('#modal-add-item .add-item-compet_brand_in_proj').val(item.compet_brand_in_proj)
                $('#modal-add-item .add-item-compet_brand_in_proj_price').val(item.compet_brand_in_proj_price)

                $('#modal-add-item .add-item-product_finish').val(item.product_finish)
                $('#modal-add-item .add-item-product_size_mm').val(item.product_size_mm)
                $('#modal-add-item .add-item-product_thinkness').val(item.product_thinkness)

                $('#modal-add-item .add-item-name').html(item.name)
                $('#modal-add-item .add-item-name').val(item.name)
                $('#modal-add-item .add-item-no').html(item.no)
                $('#modal-add-item .add-item-price').html(item.price_display)

                $('#modal-add-item .add-item-remark').val(item.remark)

                //$('#modal-add-item .add-item-ava').html(itemAva)
                $('#modal-add-item .add-item-amount').val(item.amount)
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
            var type = $(obj).data('type')
            var itemID = $(obj).data('itemid')

            if (itemID !== undefined && itemID !== '') {
                var itemProduct = productItemSelect.find(e => e.id === itemID)
                if (itemProduct !== undefined) {
                    var remark = $('#add_item_remark').val()
                    var listPrice = $('#listprice').val()
                    var uom = $('#uom').val()

                    var product_finish = $('#product_finish').val()
                    var product_size_mm = $('#product_size_mm').val()
                    var product_thinkness = $('#product_thinkness').val()
                    var competitor_brand = $('#competitor_brand').val()
                    var competitor_price = $('#competitor_price').val()
                    var compet_brand_in_proj = $('#compet_brand_in_proj').val()
                    var compet_brand_in_proj_price = $('#compet_brand_in_proj_price').val()
                    var product_cost_avg = $('#product_cost_avg').val()

                    itemProduct.remark = remark
                    itemProduct.listprice = listPrice
                    itemProduct.uom = uom
                    itemProduct.product_finish = product_finish
                    itemProduct.product_size_mm = product_size_mm
                    itemProduct.product_thinkness = product_thinkness
                    itemProduct.competitor_brand = competitor_brand
                    itemProduct.competitor_price = competitor_price
                    itemProduct.compet_brand_in_proj = compet_brand_in_proj
                    itemProduct.compet_brand_in_proj_price = compet_brand_in_proj_price
                    itemProduct.product_cost_avg = product_cost_avg

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

            //$('#vat_percentage').val(vatPercentage)
            $('#vat_percentage').val(7)

            $('#modal-add-vat').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $.closePreview = function() {
            $('#modal-preview').modal('hide');
        }

        $.showPreview = function() {


            $.post('<?php echo site_url('Quotes/saveTempItemList'); ?>', {
                netTotal,
                discountType,
                discountTypeAmount,
                discountAmount,
                totalAfterDiscount,
                totalBeforeVat,
                vatType,
                vatPercentage,
                netVat,
                totalAfterVat,
                grandTotal,
                itemList: productItemSelect
            }, function(rs) {
              
                window.location.href = '<?php echo site_url('Quotes/viewReport/' . $crmID . '?userid=' . $userID . '&action=viewTempReport'); ?>'
            }, 'json')
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
                text: 'คุณต้องการบันทึกข้อมูลใบเสนอราคาหรือไม่ ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#3199e9">ไม่ใช่</span?',
            }).then((result) => {

                // let searchParams = new URLSearchParams(window.location.search)
                // let isRevise = searchParams.get('isRevise');

                if (result.isConfirmed) {
                    $.post('<?php echo site_url('Quotes/saveItemList'); ?>', {
                        // isRevise,
                        netTotal,
                        discountType,
                        discountTypeAmount,
                        discountAmount,
                        totalAfterDiscount,
                        totalBeforeVat,
                        vatType,
                        vatPercentage,
                        netVat,
                        totalAfterVat,
                        grandTotal,
                        itemList: productItemSelect
                    }, function(rs) {
                        if (rs.status) {
                            Swal.fire({
                                icon: 'success',
                                showConfirmButton: false,
                                html: `<div class="mb-15">บันทึกใบเสนอบริการสำเร็จ</div>
                                <button type="button" class="btn btn-primary btn-custom width-full mb-5" onclick="$.requestApprove(${rs.crmID})">ขออนุมัติทันที</button>
                                <button type="button" class="btn btn-primary btn-custom width-full mb-5" onclick="$.viewQuotes(${rs.crmID})">ขออนุมัติภายหลัง</button>
                                <button type="button" class="btn btn-custom width-full mb-5" onclick="$.viewQuotes(${rs.crmID})">ปิด</button>`
                            })
                        } else {
                            Swal.fire('', 'Update Error', 'error')
                        }
                    }, 'json')


                }
            })
        }

        $.deleteQuotes = function() {
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
                    $.post('<?php echo site_url('Quotes/deleteQuotes'); ?>', {
                        crmID,
                        userID
                    }, function(rs) {
                        
                        if (rs.status) {

                            Swal.fire('', 'ลบรายการสำเร้จ', 'success')
                            window.location.href = '<?php echo site_url('Quotes/create?userid=' . $this->session->userdata('userID') . '/back'); ?>'
                        } else {
                            Swal.fire('', 'Update Error', 'error')
                        }
                    }, 'json')
                }
            })
        }

        $.requestApprove = function(crmID) {
            $('.overlay').show();
            $.post('<?php echo site_url('Quotes/requestApprove'); ?>', {
                crmID
            }, function(rs) {
                Swal.close()
                if (rs.status) {
                    /**
                     * Log Update
                     * [Issue_P34_026] ให้ทาง AI ส่ง Key ของหน้าที่จะเปิดเพิ่มเติม ให้กับทางทีม Mobile
                     */
                    // window.location.href = '<?php echo site_url('Quotes/view/viewContent' . $crmID); ?>'
                    window.location.href = '<?php echo site_url('Quotes/view/' . $crmID . '?userID=' . $userID); ?>'
                } else {
                    Swal.fire('', rs.msg, 'error')
                    $('.overlay').hide();
                }
            }, 'json')
        }

        $.viewQuotes = function() {
            $('.overlay').show();
            Swal.close()
            /**
             * Log Update
             * [Issue_P34_026] ให้ทาง AI ส่ง Key ของหน้าที่จะเปิดเพิ่มเติม ให้กับทางทีม Mobile
             */
            // window.location.href = '<?php echo site_url('Quotes/view/viewContent/' . $crmID); ?>'
            window.location.href = '<?php echo site_url('Quotes/view/' . $crmID . '?userID=' . $userID); ?>'
        }
    })
</script>