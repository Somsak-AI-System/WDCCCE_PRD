<div id="modal-add-item" class="modal modal-bottom fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content shadow-top-30">
            <div class="modal-header">
                <div class="modal-title">
                    เพิ่มรายการ
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18" onclick="$.closeAddItem();"></i>
                </span>
            </div>
            <!-- <div class="modal-header border-b-none">
                <div class="modal-title">
                    เพิ่มรายการ
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18" onclick="$.closeAddItem();"></i>
                </span>
            </div> -->
            <div class="modal-body bg-white" style="height: 500px;overflow-y: auto;">
                <div class="flex width-full list-item-row p-5 mb-5">
                    <div class="flex-none">
                        <div class="list-item-icon bg-blue-1">
                            <i class="ph-rows v-align-middle"></i>
                        </div>
                    </div>
                    <div class="flex-1 pl-10">
                        <div class="font-16 font-bold text-line-clamp-1 add-item-name"></div>
                        <div class="font-12 text-gray-1 text-line-clamp-1 add-item-no"></div>
                        <div class="font-14 text-primary">฿ <span class="add-item-price"></span></div>
                    </div>
                </div>
                <input type="hidden" class="add-item-id">
                
                <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-name', 'fieldlabel' => 'ชื่อสินค้า', 'columnname' => 'productname', 'columnname' => 'productname', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~M']); ?>
                
                <?php echo inputView_Placeholder(['uitype' => '1', 'fieldClass' => 'add-item-remark', 'fieldlabel' => 'หมายเหตุ', 'columnname' => 'add_item_remark', 'columnname' => 'add_item_remark', 'value' => '', 'rows' => '', 'typeofdata' => 'V~O']); ?>

                <div class="input-group adjust-item-group" data-itemid="" data-ava="">
                    <div class="flex adjust-row" style="width: 100%">
                        <div class="flex-1">
                            <div class="font-16">จำนวน</div>
                        </div>
                        <div class="flex-none">
                            <div class="input-group input-group-custom adjust-item-group" style="width:100px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text input-group-text-custom minus-amount" onclick="$.minusItemAmount(this)">
                                        <i class="ph-minus text-primary v-align-middle cursor-pointer"></i>
                                    </span>
                                </div>
                                <input type="number" id="line-item" class="form-control border-none text-center p-0 add-item-amount" onkeyup="$.isCalculates(event,this,'line-item');" value="">
                                <div class="input-group-append">
                                    <span class="input-group-text input-group-text-custom plus-amount" onclick="$.plusItemAmount(this)">
                                        <i class="ph-plus text-primary v-align-middle cursor-pointer"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex detail-row">
                        <div class="flex-1">
                            <?php echo inputGroup(['uitype' => '7', 'fieldClass' => 'add-item-listPrice', 'fieldlabel' => 'ราคาขาย', 'columnname' => 'listprice', 'columnname' => 'listprice', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                        </div>

                        <div class="flex-1">
                            <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-uom', 'fieldlabel' => 'หน่วยนับ', 'columnname' => 'uom', 'columnname' => 'uom', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                        </div>
                    </div>
                </div>
                
                
                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-product_finish', 'fieldlabel' => 'ชนิดผิว', 'columnname' => 'product_finish', 'columnname' => 'product_finish', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-product_size_mm', 'fieldlabel' => 'ขนาด (มม.)', 'columnname' => 'product_size_mm', 'columnname' => 'product_size_mm', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-product_thinkness', 'fieldlabel' => 'ความหนา (มม.)', 'columnname' => 'product_thinkness', 'columnname' => 'product_thinkness', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '15', 'fieldClass' => 'add-item-competitor_brand', 'fieldlabel' => 'แบรนด์คู่แข่ง', 'columnname' => 'competitor_brand', 'columnname' => 'competitor_brand', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O' ,'value_default'=> array(0=>'--None--',1=>'แบรนด์คู่แข่ง 1',2=>'แบรนด์คู่แข่ง 2',3=>'แบรนด์คู่แข่ง 3')]); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-competitor_price', 'fieldlabel' => 'ราคาคู่แข่ง (Exc.VAT)', 'columnname' => 'competitor_price', 'columnname' => 'competitor_price', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                </div>
                
                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '15', 'fieldClass' => 'add-item-compet_brand_in_proj', 'fieldlabel' => 'แบรนด์คู่แข่งในโครงการ', 'columnname' => 'compet_brand_in_proj', 'columnname' => 'compet_brand_in_proj', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O','value_default'=> array(0=>'--None--',1=>'แบรนด์คู่แข่งในโครงการ 1',2=>'แบรนด์คู่แข่งในโครงการ 2',3=>'แบรนด์คู่แข่งในโครงการ 3')]); ?>
                </div>

                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'add-item-compet_brand_in_proj_price', 'fieldlabel' => 'ราคาคู่แข่งในโครงการ (Exc.VAT)', 'columnname' => 'compet_brand_in_proj_price', 'columnname' => 'compet_brand_in_proj_price', 'value' => '', 'readonly' => '0', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                </div>

                
                <div class="flex-none">
                    <?php echo inputGroup(['uitype' => '1', 'fieldClass' => 'base-input-group add-item-product_cost_avg', 'fieldlabel' => 'รวมต้นทุนจริงเฉลี่ย', 'columnname' => 'product_cost_avg', 'columnname' => 'product_cost_avg', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O']); ?>
                </div>

            </div>
            
            <div class="modal-footer" style="display: block !important;">
                <div class="card-box p-0 mt-10">
                    <div class="card-box-body px-10 pb-10">
                        <div class="flex">
                            <div class="flex-1">
                                รวมยอด
                            </div>
                            <div class="flex-none text-right width-150">
                                ฿ <span class="add-item-total-price"></span>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-custom width-full mt-10 btn-update-item" data-type="" data-itemid="" onclick="$.updateSelectedItem(this)">
                            บันทึก
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>