<div class="top-nav">
    <div class="top-nav-content">
        <div class="top-nav-back-icon"><i class="ph-caret-left"></i></div>
        <span>Components Demo</span>
        <div class="top-nav-action flex">
            <div class="top-nav-action-icon flex-none"><i class="ph-floppy-disk"></i></div>
            <div class="top-nav-action-icon flex-none">
                <div>
                    <i class="ph-dots-three-circle" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item" type="button">
                                <i class="ph-eye v-align-middle"></i> ดูตัวอย่าง
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button">
                                <i class="ph-share v-align-middle"></i> แชร์
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button">
                                <i class="ph-trash v-align-middle"></i> ลบ
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content mt-48">
    <div class="card-box">
        <div class="card-box-header flex">
            <div class="card-box-title flex-1">
                Basic Components
            </div>
            <div class="card-box-action flex-none">
                <div data-bs-toggle="collapse" href="#collapse1" role="button" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" class="mr-5 cursor-pointer">
                        <path d="M 0 0 L 4.952 6.8 L 9.905 0 Z" transform="translate(1.548 1.547) rotate(-180 4.952 3.4)" fill="var(--token-50a2f280-bd0d-4887-b758-f067a93e2d2c, rgb(43, 43, 43))" stroke-width="2.47" stroke="var(--token-50a2f280-bd0d-4887-b758-f067a93e2d2c, rgb(43, 43, 43))" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="collapse show" id="collapse1">
            <div class="card-box-body">
                <?php echo inputGroup(['type'=>'1', 'fieldLabel'=>'ช่องกรอกข้อมูล', 'fieldName'=>'text', 'fieldID'=>'text', 'value'=>'', 'required'=>true]); ?>

                <?php echo inputGroup(['type'=>'2', 'fieldLabel'=>'ช่องกรอกข้อมูล', 'fieldName'=>'text', 'fieldID'=>'text', 'value'=>'', 'required'=>true]); ?>

                <?php
                    $pickListData = [];
                    for($i=1; $i<=10; $i++){
                        $pickListData[] = ['label'=>'choice '.$i, 'value'=>$i];
                    }

                    $checkBoxData = [];
                    for($i=1; $i<=3; $i++){
                        $checkBoxData[] = ['label'=>'checkbox '.$i, 'value'=>$i];
                    }
                ?>
                <?php echo inputGroup(['type'=>'3', 'fieldLabel'=>'ช่องกรอกข้อมูล', 'fieldName'=>'text', 'fieldID'=>'text', 'value'=>'', 'required'=>true, 'pickListData'=>$pickListData]); ?>

                <?php echo inputGroup(['type'=>'4', 'fieldLabel'=>'ช่องกรอกข้อมูล', 'fieldName'=>'text', 'fieldID'=>'text', 'value'=>'', 'required'=>true, 'pickListData'=>$pickListData]); ?>

                <?php echo inputGroup(['type'=>'5', 'fieldLabel'=>'DatePicker', 'fieldName'=>'datepicker', 'fieldID'=>'datepicker', 'value'=>'', 'required'=>true]); ?>

                <?php echo inputGroup(['type'=>'6', 'fieldLabel'=>'Checkbox', 'fieldName'=>'checkbox', 'fieldID'=>'checkbox', 'value'=>'', 'required'=>true, 'pickListData'=>$checkBoxData]); ?>

                <?php echo inputGroup(['type'=>'7', 'fieldLabel'=>'Popup Select 1', 'fieldName'=>'pop1', 'fieldID'=>'pop1', 'value'=>'', 'required'=>true]); ?>

                <?php echo inputGroup(['type'=>'7', 'fieldLabel'=>'Popup Select 2', 'fieldName'=>'pop2', 'fieldID'=>'pop2', 'value'=>'', 'required'=>true]); ?>

                <div class="mb-5">
                    <label class="pl-5">ชื่อลูกค้า <span class="text-danger">*</span></label>
                    <div class="base-input-group">
                        <input type="text" class="base-input-text" placeholder="ค้นหา">
                        <div class="base-input-group-action">
                            <i class="ph-magnifying-glass cursor-pointer" onclick="$.searchShow()"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="pl-5">มอบหมายให้ <span class="text-danger">*</span></label>
                    <div class="btn-group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-group-label btn-outline-default" for="btnradio1">ผู้ใช้</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-group-label btn-outline-default" for="btnradio2">กลุ่มผู้ใช้</label>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="modal modal-bottom fade popup-search-modal" id="search_modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="flex">
                        <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal();">
                            <i class="ph-caret-left font-18"></i>
                        </div>
                        <div class="flex-1 pl-5">
                            ค้นหา
                        </div>
                    </div>                    
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal();"></i>
                </span>
            </div>
            <div class="modal-body">
                <div class="row pb-10 modal-sticky-top">
                    <div class="col">
                        <div class="base-input-group bg-white">
                            <input type="text" class="base-input-text bg-white" placeholder="ค้นหา">
                            <div class="base-input-group-action">
                                <i class="ph-magnifying-glass"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const getDatePickerTitle = elem => {
        // From the label or the aria-label
        const label = elem.nextElementSibling;
        let titleText = '';
        if (label && label.tagName === 'LABEL') {
            titleText = label.textContent;
        } else {
            titleText = elem.getAttribute('aria-label') || '';
        }
        return titleText;
    }

    $(function() {
        const elems = $('.datepicker_input');
        for (const elem of elems) {
            const datepicker = new Datepicker(elem, {
                format: 'dd/mm/yyyy', // UK format
                title: getDatePickerTitle(elem)
            });
        }

        $('.select-multi').multiselect({
            buttonClass: 'multiselect-button',
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'เลือก',
            selectAllText: 'เลือกทั้งหมด',
            filterPlaceholder: 'ค้นหา',
            enableFiltering: true,
            numberDisplayed: 5
        });

        $.searchShow = function(modalID) {
            $(`#${modalID}`).modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $.closeModal = function(modalID){
            $(`#${modalID}`).modal('hide');
        }
    })
</script>