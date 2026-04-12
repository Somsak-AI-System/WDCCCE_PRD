<div id="modal-add-discount" class="modal modal-bottom fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-b-none">
                <div class="modal-title">
                    เพิ่มส่วนลด
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18" onclick="$.closeAddDiscount();"></i>
                </span>
            </div>
            <div class="modal-body bg-white">
                <div class="flex">
                    <div class="flex-1">
                        <label class="pl-5">ส่วนลด</label>
                        <div class="btn-group">
                            <input type="radio" class="btn-check" name="discount_type" id="discount_type_1" value="2" autocomplete="off" checked>
                            <label class="btn btn-group-label btn-outline-default p-5" for="discount_type_1">฿</label>

                            <input type="radio" class="btn-check" name="discount_type" id="discount_type_2" value="1" autocomplete="off">
                            <label class="btn btn-group-label btn-outline-default p-5" for="discount_type_2">%</label>
                        </div>
                    </div>
                    <div class="flex-none pl-10">
                        <?php echo inputNumber(['columnname'=>'discount_type_amount', 'columnname'=>'discount_type_amount', 'fieldClass'=>'text-center mt-25', 'value'=>'', 'typeofdata'=>'']); ?>
                    </div>
                </div>

                <div class="card-box p-0 mt-10">
                    <div class="card-box-body px-10 pb-10">
                        <div class="flex">
                            <div class="flex-1">
                                ยอดรวม
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span id="cal-discount-before"></span>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-1">
                                ส่วนลด
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span id="cal-discount-amount"></span>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-1">
                                หักส่วนลดแล้ว
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span id="cal-discount-after"></span>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-custom width-full mt-10" id="btn-discount" onclick="$.closeAddDiscount()">
                            บันทึกส่วนลด
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('input[name=discount_type]').change(function(){
            var value = $('input[name=discount_type]:checked').val()
            discountType = value
            summaryCalculate()
        });

        if(discountTypeAmount === ''){
            $('#btn-discount').prop('disabled', true)
        }

        $('#discount_type_amount').keyup(function(){
            var value = $(this).val()
                
            if(value === ''){
                $('#btn-discount').prop('disabled', true)
            }else{
                $('#btn-discount').prop('disabled', false)
            }

            discountTypeAmount = value
            summaryCalculate()
        })
    })
</script>