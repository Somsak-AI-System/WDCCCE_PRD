<div class="card-bottom">
    <div class="card-box p-0">
        <div class="card-box-header mb-0">
            <div class="width-full text-center" data-bs-toggle="collapse" href="#box1" role="button" aria-expanded="false" onclick="$.toggleArrow();">
                <i class="card-bottom-arrow ph-caret-up"></i>
            </div>
        </div>
        <div class="collapse" id="box1">
            <div class="card-box-body px-10 pt-10">
                <div class="flex">
                    <div class="flex-1">
                        รวม
                    </div>
                    <div class="flex-none text-right width-150">
                        ฿ <span id="net-total">0.00</span>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-1">
                        ส่วนลด
                    </div>
                    <!-- <div class="flex-none text-right width-150 text-primary cursor-pointer" onclick="$.showAddDiscount()">
                        ฿ <span id="discount-amount">0.00</span>
                    </div> -->
                    <div class="flex-none text-right width-150 cursor-pointer">
                        ฿ <span id="discount-amount">0.00</span>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-1">
                        รวมหลังหักส่วนลด
                    </div>
                    <div class="flex-none text-right width-150">
                        ฿ <span id="total-after-discount">0.00</span>
                    </div>
                </div>

                <div class="devider"></div>

                <div class="flex">
                    <div class="flex-1">
                        มูลค่าก่อนรวมภาษี
                    </div>
                    <div class="flex-none text-right width-150">
                        ฿ <span id="total-before-vat">0.00</span>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-1">
                        ภาษีมูลค่าเพิ่ม <span class="text-primary" id="net-tax-percent-text"></>
                    </div>
                    <div class="flex-none text-right width-150 text-primary cursor-pointer" onclick="$.showAddVat()">
                        ฿ <span id="net-vat">0.00</span>
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-1">
                        มูลค่ารวม
                    </div>
                    <div class="flex-none text-right width-150">
                        ฿ <span id="total-after-vat">0.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-box-body px-10 pb-10">
            <div class="devider"></div>
            <div class="flex">
                <div class="flex-1">
                    รวมจำนวน
                </div>
                <div class="flex-none text-right width-150">
                    <span id="grand-total-unit">0</span> ชิ้น
                </div>
            </div>
            <div class="flex">
                <div class="flex-1">
                    รวมทั้งหมด
                </div>
                <div class="flex-none text-right width-150">
                    ฿ <span id="grand-total">0.00</span>
                </div>
            </div>
            <button class="btn btn-primary btn-custom width-full mt-10" id="btn-save-quotes" onclick="$.saveData()">
                บันทึกใบเสนอบริการ
            </button>
        </div>
    </div>
</div>