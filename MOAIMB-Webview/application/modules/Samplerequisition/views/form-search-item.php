<div id="modal-list" class="modal modal-full fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-custom-content">
            <div class="modal-header modal-custom-header">
                <div class="modal-custom-back-icon" onclick="$.closeList()"><i class="ph-caret-left"></i></div>
                <span>เลือกรายการ</span>
            </div>
            <div class="modal-body modal-custom-body bg-white">
                <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="list-1-tab" data-toggle="pill" href="#list-1" role="tab" aria-controls="list-1" aria-selected="true">สินค้า</a>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="list-2-tab" data-toggle="pill" href="#list-2" role="tab" aria-controls="list-2" aria-selected="true">อะไหล่</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="list-3-tab" data-toggle="pill" href="#list-3" role="tab" aria-controls="list-3" aria-selected="false">บริการ</a>
                    </li> -->
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" style="position:relative" id="list-1" role="tabpanel" aria-labelledby="list-1-tab">
                        <?php $this->load->view('list-product'); ?>
                    </div>
                </div>
            </div>

            <div class="bottom-item-list-action" id="add-item-selected-summary">
                <div class="bottom-item-list-inner bg-primary text-white cursor-pointer" onclick="$.closeList()">
                    <div class="flex-1 text-center">
                        ยืนยันรายการ (<span id="total-selected-item">0</span>)
                    </div>
                    <!-- <div class="flex-none text-right width-150">
                        ฿ <span id="total-selected-price">0.00</span>
                    </div> -->
                </div>
            </div>

        </div>
    </div>
</div>