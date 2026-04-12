<div id="modal-add-vat" class="modal modal-bottom fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-b-none">
                <div class="modal-title">
                    เพิ่มส่วนลด
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18" onclick="$.closeAddVat();"></i>
                </span>
            </div>
            <div class="modal-body bg-white">
                <?php echo inputGroup(['uitype'=>'15', 'fieldlabel'=>'ประเภทภาษี', 'columnname'=>'vat_type', 'columnname'=>'vat_type', 'value'=>'', 'typeofdata'=>'', 'value_default'=>[
                    'ไม่รวมภาษี',
                    'รวมภาษี'
                ]]); ?>

                <div class="flex">
                    <div class="flex-1">
                        <label class="pl-5">ภาษีมูลค่าเพิ่ม</label>
                        <div class="btn-group"><input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                            <label class="btn btn-group-label btn-outline-default p-5" for="btnradio2">%</label>
                        </div>
                    </div>
                    <div class="flex-none pl-10">
                        <?php echo inputNumber(['columnname'=>'vat_percentage', 'columnname'=>'vat_percentage', 'fieldClass'=>'text-center mt-25', 'value'=>'7', 'typeofdata'=>'']); ?>
                    </div>
                </div>

                <div class="card-box p-0 mt-10">
                    <div class="card-box-body px-10 pb-10">
                        <div class="flex">
                            <div class="flex-1">
                                มูลค่าก่อนรวมภาษี
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span id="cat-vat-before"></span>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-1">
                                ภาษีมูลค่าเพิ่ม <span class="text-primary" id="tax-percent-text"></> 
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span id="cat-vat-net"></span>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-1">
                                มูลค่ารวม
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span id="cat-vat-after"></span>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-custom width-full mt-10" id="btn-vat" onclick="$.closeAddVat();">
                            บันทึก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#vat_type').change(function(){
            vatType = $(this).val()
            summaryCalculate()
        })

        if(vatPercentage === ''){
            $('#btn-vat').prop('disabled', true)
        }

        //$('#vat_percentage').keyup(function(){
        $('#vat_percentage').keyup(function(){
            var value = $(this).val()
            if(value === ''){
                $('#btn-vat').prop('disabled', true)
            }else{
                $('#btn-vat').prop('disabled', false)
            }

            vatPercentage = value
            summaryCalculate()
        })
    })
</script>